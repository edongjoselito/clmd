<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_model extends CI_Model
{
    public function get()
    {
        $row = $this->db->get_where('settings', ['setting_id' => 1])->row_array();
        if (!$row) {
            $this->db->insert('settings', ['setting_id' => 1]);
            $row = $this->db->get_where('settings', ['setting_id' => 1])->row_array();
        }
        return $row;
    }

    public function update($data)
    {
        return $this->db->update('settings', $data, ['setting_id' => 1]);
    }
}
