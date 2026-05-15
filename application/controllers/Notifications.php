<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model('Notification_model');
    }

    public function index()
    {
        $rows = $this->Notification_model->all_for($this->current_user['user_id']);
        $this->Notification_model->mark_all_read($this->current_user['user_id']);
        $this->render('notifications/index', ['rows' => $rows], 'Notifications');
    }

    public function read($id)
    {
        $this->Notification_model->mark_read($id, $this->current_user['user_id']);
        $row = $this->db->get_where('notifications', [
            'notif_id' => $id, 'user_id' => $this->current_user['user_id']
        ])->row_array();
        if ($row && !empty($row['link_url'])) {
            redirect($row['link_url']);
        }
        redirect('notifications');
    }

    public function mark_all_read()
    {
        $this->Notification_model->mark_all_read($this->current_user['user_id']);
        redirect($this->input->server('HTTP_REFERER') ?: site_url('dashboard'));
    }
}
