<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class School_model extends CI_Model
{
    protected $table = 'schools';

    public function all($filters = [])
    {
        $this->db->select('s.*, d.name AS division_name, d.code AS division_code')
                 ->from('schools s')
                 ->join('divisions d', 'd.division_id = s.division_id', 'left')
                 ->order_by('s.school_name', 'ASC');

        if (!empty($filters['division_id'])) {
            $this->db->where('s.division_id', $filters['division_id']);
        }
        if (!empty($filters['school_type'])) {
            $this->db->where('s.school_type', $filters['school_type']);
        }
        if (isset($filters['is_active'])) {
            $this->db->where('s.is_active', (int)$filters['is_active']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start()
                     ->like('s.school_name', $filters['search'])
                     ->or_like('s.school_code', $filters['search'])
                     ->or_like('s.municipality', $filters['search'])
                     ->group_end();
        }
        return $this->db->get()->result_array();
    }

    public function get($id)
    {
        return $this->db->get_where($this->table, ['school_id' => $id])->row_array();
    }

    public function insert($data) { $this->db->insert($this->table, $data); return $this->db->insert_id(); }
    public function update($id, $data) { return $this->db->update($this->table, $data, ['school_id' => $id]); }
    public function delete($id) { return $this->db->delete($this->table, ['school_id' => $id]); }

    /** Schools available for selection in document submission. */
    public function for_dropdown($division_id)
    {
        return $this->db->select('school_id, school_name, school_type')
                        ->where(['division_id' => $division_id, 'is_active' => 1])
                        ->order_by('school_name', 'ASC')
                        ->get($this->table)->result_array();
    }
}
