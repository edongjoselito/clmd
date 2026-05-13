<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    protected $table = 'users';

    public function get_by_username($username)
    {
        return $this->db->get_where($this->table, ['username' => $username])->row_array();
    }

    public function get($id)
    {
        return $this->db->get_where($this->table, ['user_id' => $id])->row_array();
    }

    public function all($filters = [])
    {
        $this->db->select('u.*, d.name AS division_name, d.code AS division_code')
                 ->from('users u')
                 ->join('divisions d', 'd.division_id = u.division_id', 'left')
                 ->order_by('u.full_name', 'ASC');

        if (!empty($filters['role'])) {
            $this->db->where('u.role', $filters['role']);
        }
        if (!empty($filters['division_id'])) {
            $this->db->where('u.division_id', $filters['division_id']);
        }
        return $this->db->get()->result_array();
    }

    public function insert($data)
    {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        return $this->db->update($this->table, $data, ['user_id' => $id]);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, ['user_id' => $id]);
    }

    public function touch_last_login($id)
    {
        $this->db->update($this->table, ['last_login' => date('Y-m-d H:i:s')], ['user_id' => $id]);
    }

    public function username_exists($username, $exclude_id = null)
    {
        $this->db->where('username', $username);
        if ($exclude_id) {
            $this->db->where('user_id !=', $exclude_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }
}
