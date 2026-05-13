<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Curriculum_model extends CI_Model
{
    protected $table = 'curriculum';

    public function all($filters = [])
    {
        $this->db->select('c.*, u.full_name AS created_by_name')
                 ->from('curriculum c')
                 ->join('users u', 'u.user_id = c.created_by', 'left')
                 ->order_by('c.created_at', 'DESC');

        if (!empty($filters['grade_level'])) {
            $this->db->where('c.grade_level', $filters['grade_level']);
        }
        if (!empty($filters['subject'])) {
            $this->db->where('c.subject', $filters['subject']);
        }
        if (!empty($filters['school_year'])) {
            $this->db->where('c.school_year', $filters['school_year']);
        }
        if (isset($filters['is_active'])) {
            $this->db->where('c.is_active', (int)$filters['is_active']);
        }
        return $this->db->get()->result_array();
    }

    public function get($id)
    {
        return $this->db->get_where($this->table, ['curriculum_id' => $id])->row_array();
    }

    public function insert($data) { $this->db->insert($this->table, $data); return $this->db->insert_id(); }
    public function update($id, $data) { return $this->db->update($this->table, $data, ['curriculum_id' => $id]); }
    public function delete($id) { return $this->db->delete($this->table, ['curriculum_id' => $id]); }
}
