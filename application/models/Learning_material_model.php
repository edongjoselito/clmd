<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Learning_material_model extends CI_Model
{
    protected $table = 'learning_materials';

    public function all($filters = [])
    {
        $this->db->select('lm.*, d.name AS division_name, d.code AS division_code,
                           sub.full_name AS submitted_by_name,
                           rev.full_name AS reviewed_by_name')
                 ->from('learning_materials lm')
                 ->join('divisions d',  'd.division_id = lm.division_id', 'left')
                 ->join('users sub',    'sub.user_id = lm.submitted_by', 'left')
                 ->join('users rev',    'rev.user_id = lm.reviewed_by', 'left')
                 ->order_by('lm.created_at', 'DESC');

        if (!empty($filters['division_id'])) {
            $this->db->where('lm.division_id', $filters['division_id']);
        }
        if (!empty($filters['status'])) {
            $this->db->where('lm.status', $filters['status']);
        }
        if (!empty($filters['grade_level'])) {
            $this->db->where('lm.grade_level', $filters['grade_level']);
        }
        if (!empty($filters['subject'])) {
            $this->db->where('lm.subject', $filters['subject']);
        }
        return $this->db->get()->result_array();
    }

    public function get($id)
    {
        return $this->db->select('lm.*, d.name AS division_name, sub.full_name AS submitted_by_name')
                        ->from('learning_materials lm')
                        ->join('divisions d', 'd.division_id = lm.division_id', 'left')
                        ->join('users sub',   'sub.user_id = lm.submitted_by', 'left')
                        ->where('lm.material_id', $id)
                        ->get()->row_array();
    }

    public function insert($data) { $this->db->insert($this->table, $data); return $this->db->insert_id(); }
    public function update($id, $data) { return $this->db->update($this->table, $data, ['material_id' => $id]); }
    public function delete($id) { return $this->db->delete($this->table, ['material_id' => $id]); }

    public function counts_by_status($division_id = null)
    {
        $this->db->select('status, COUNT(*) AS total')
                 ->from('learning_materials')
                 ->group_by('status');
        if ($division_id) {
            $this->db->where('division_id', $division_id);
        }
        $rows = $this->db->get()->result_array();
        $out = ['Pending' => 0, 'Approved' => 0, 'Rejected' => 0, 'Revised' => 0];
        foreach ($rows as $r) { $out[$r['status']] = (int)$r['total']; }
        return $out;
    }
}
