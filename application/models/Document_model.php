<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Document_model extends CI_Model
{
    protected $table = 'documents';

    /** Canonical document types required for the combined certification. */
    const TYPE_CERTIFICATION = 'Certification of Compliance to DepEd Order No. 54, s. 2022';
    const TYPE_ENDORSEMENT   = 'Endorsement';

    public function all($filters = [])
    {
        $this->db->select('doc.*,
                           s.school_name, s.school_type, s.email AS school_email,
                           s.province, s.city, s.barangay,
                           d.name AS division_name, d.code AS division_code,
                           sub.full_name AS submitted_by_name,
                           rev.full_name AS reviewed_by_name')
                 ->from('documents doc')
                 ->join('schools s',   's.school_id = doc.school_id', 'left')
                 ->join('divisions d', 'd.division_id = doc.division_id', 'left')
                 ->join('users sub',   'sub.user_id = doc.submitted_by', 'left')
                 ->join('users rev',   'rev.user_id = doc.reviewed_by', 'left')
                 ->order_by('doc.created_at', 'DESC');

        if (!empty($filters['division_id'])) {
            $this->db->where('doc.division_id', $filters['division_id']);
        }
        if (!empty($filters['status'])) {
            $this->db->where('doc.status', $filters['status']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start()
                     ->like('doc.document_title', $filters['search'])
                     ->or_like('s.school_name', $filters['search'])
                     ->or_like('doc.control_no', $filters['search'])
                     ->group_end();
        }
        return $this->db->get()->result_array();
    }

    public function get($id)
    {
        return $this->db->select('doc.*,
                                  s.school_name, s.school_type, s.email AS school_email,
                                  s.province, s.city, s.barangay,
                                  d.name AS division_name, d.code AS division_code,
                                  sub.full_name AS submitted_by_name,
                                  rev.full_name AS reviewed_by_name')
                        ->from('documents doc')
                        ->join('schools s',   's.school_id = doc.school_id', 'left')
                        ->join('divisions d', 'd.division_id = doc.division_id', 'left')
                        ->join('users sub',   'sub.user_id = doc.submitted_by', 'left')
                        ->join('users rev',   'rev.user_id = doc.reviewed_by', 'left')
                        ->where('doc.document_id', $id)
                        ->get()->row_array();
    }

    public function get_by_control_no($control_no)
    {
        return $this->db->select('doc.*,
                                  s.school_name, s.school_type,
                                  d.name AS division_name')
                        ->from('documents doc')
                        ->join('schools s',   's.school_id = doc.school_id', 'left')
                        ->join('divisions d', 'd.division_id = doc.division_id', 'left')
                        ->where('doc.control_no', $control_no)
                        ->get()->row_array();
    }

    public function insert($data) { $this->db->insert($this->table, $data); return $this->db->insert_id(); }
    public function update($id, $data) { return $this->db->update($this->table, $data, ['document_id' => $id]); }
    public function delete($id) { return $this->db->delete($this->table, ['document_id' => $id]); }

    public function counts_by_status($division_id = null)
    {
        $this->db->select('status, COUNT(DISTINCT school_id) AS total')
                 ->from('documents')
                 ->group_by('status');
        if ($division_id) $this->db->where('division_id', $division_id);

        $rows = $this->db->get()->result_array();
        $out = ['For Approval' => 0, 'Approved' => 0, 'Rejected' => 0, 'Revised' => 0];
        foreach ($rows as $r) { $out[$r['status']] = (int)$r['total']; }
        return $out;
    }

    /**
     * Get the latest Approved Certification + Endorsement docs for a school.
     * Returns ['certification' => row|null, 'endorsement' => row|null].
     */
    public function get_pair($school_id)
    {
        $fetch = function ($type) use ($school_id) {
            return $this->db->where([
                                'school_id'     => $school_id,
                                'document_type' => $type,
                            ])
                            ->order_by('created_at', 'DESC')
                            ->limit(1)
                            ->get($this->table)->row_array();
        };
        return [
            'certification' => $fetch(self::TYPE_CERTIFICATION),
            'endorsement'   => $fetch(self::TYPE_ENDORSEMENT),
        ];
    }

    public function get_approved_pair($school_id)
    {
        $fetch = function ($type) use ($school_id) {
            return $this->db->select('doc.*,
                                      s.school_name, s.school_type, s.email AS school_email,
                                      s.province, s.city, s.barangay,
                                      d.name AS division_name, d.code AS division_code,
                                      rev.full_name AS reviewed_by_name')
                            ->from('documents doc')
                            ->join('schools s',   's.school_id = doc.school_id', 'left')
                            ->join('divisions d', 'd.division_id = doc.division_id', 'left')
                            ->join('users rev',   'rev.user_id = doc.reviewed_by', 'left')
                            ->where([
                                'doc.school_id'     => $school_id,
                                'doc.document_type' => $type,
                                'doc.status'        => 'Approved',
                            ])
                            ->order_by('doc.approved_at', 'DESC')
                            ->limit(1)
                            ->get()->row_array();
        };
        return [
            'certification' => $fetch(self::TYPE_CERTIFICATION),
            'endorsement'   => $fetch(self::TYPE_ENDORSEMENT),
        ];
    }

    /** True if a school has both required types Approved. */
    public function pair_ready($school_id)
    {
        $pair = $this->get_approved_pair($school_id);
        return !empty($pair['certification']) && !empty($pair['endorsement']);
    }

    /**
     * For a list of documents, return a set of school_ids whose pair is ready.
     * Avoids N+1 queries on the index view.
     */
    public function ready_school_ids(array $school_ids)
    {
        if (empty($school_ids)) return [];
        $rows = $this->db->select('school_id, document_type')
                         ->where_in('school_id', $school_ids)
                         ->where('status', 'Approved')
                         ->where_in('document_type', [self::TYPE_CERTIFICATION, self::TYPE_ENDORSEMENT])
                         ->get($this->table)->result_array();
        $map = [];
        foreach ($rows as $r) {
            $map[$r['school_id']][$r['document_type']] = true;
        }
        $ready = [];
        foreach ($map as $sid => $types) {
            if (!empty($types[self::TYPE_CERTIFICATION]) && !empty($types[self::TYPE_ENDORSEMENT])) {
                $ready[(int)$sid] = true;
            }
        }
        return $ready;
    }

    public function next_control_no()
    {
        $year = date('Y');
        $like = "CLMD-RXI-{$year}-%";
        $row = $this->db->select_max('control_no')
                        ->like('control_no', "CLMD-RXI-{$year}-", 'after')
                        ->get($this->table)->row_array();
        $next = 1;
        if (!empty($row['control_no'])) {
            $parts = explode('-', $row['control_no']);
            $next  = (int)end($parts) + 1;
        }
        return sprintf('CLMD-RXI-%s-%04d', $year, $next);
    }
}
