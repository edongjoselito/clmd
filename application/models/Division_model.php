<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Division_model extends CI_Model
{
    protected $table = 'divisions';

    public function all()
    {
        return $this->db->order_by('name', 'ASC')->get($this->table)->result_array();
    }

    public function get($id)
    {
        return $this->db->get_where($this->table, ['division_id' => $id])->row_array();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->update($this->table, $data, ['division_id' => $id]);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, ['division_id' => $id]);
    }

    public function code_exists($code, $exclude_id = null)
    {
        $this->db->where('code', $code);
        if ($exclude_id) {
            $this->db->where('division_id !=', $exclude_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }
}
