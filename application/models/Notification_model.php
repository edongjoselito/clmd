<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends CI_Model
{
    protected $table = 'notifications';

    public function create($user_id, $title, $message, $link_url = null)
    {
        $this->db->insert($this->table, [
            'user_id'  => $user_id,
            'title'    => $title,
            'message'  => $message,
            'link_url' => $link_url,
        ]);
    }

    /** Notify all regional users (e.g. when division submits a document). */
    public function notify_regional($title, $message, $link_url = null)
    {
        $users = $this->db->select('user_id')
                          ->where(['role' => 'regional', 'is_active' => 1])
                          ->get('users')->result_array();
        foreach ($users as $u) {
            $this->create($u['user_id'], $title, $message, $link_url);
        }
    }

    public function unread_count($user_id)
    {
        return (int)$this->db->where(['user_id' => $user_id, 'is_read' => 0])
                             ->count_all_results($this->table);
    }

    public function recent($user_id, $limit = 8)
    {
        return $this->db->where('user_id', $user_id)
                        ->order_by('created_at', 'DESC')
                        ->limit($limit)
                        ->get($this->table)->result_array();
    }

    public function all_for($user_id)
    {
        return $this->db->where('user_id', $user_id)
                        ->order_by('created_at', 'DESC')
                        ->get($this->table)->result_array();
    }

    public function mark_all_read($user_id)
    {
        $this->db->update($this->table, ['is_read' => 1], ['user_id' => $user_id, 'is_read' => 0]);
    }

    public function mark_read($notif_id, $user_id)
    {
        $this->db->update($this->table, ['is_read' => 1], ['notif_id' => $notif_id, 'user_id' => $user_id]);
    }
}
