<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base controller for CLMD - DepEd Region XI
 *
 * Provides:
 *  - auth guard (redirect to /login if not signed in)
 *  - role guard (regional | division)
 *  - common layout rendering helper
 */
class MY_Controller extends CI_Controller
{
    /** @var array session-cached current user */
    protected $current_user = null;

    public function __construct()
    {
        parent::__construct();
        $this->current_user = $this->session->userdata('user');

        // Validate session against DB; if the user was deleted or deactivated,
        // force logout to avoid foreign-key errors and stale state.
        if (!empty($this->current_user)) {
            $row = $this->db->select('user_id, username, full_name, email, role, division_id, position, is_active')
                            ->get_where('users', ['user_id' => $this->current_user['user_id']])
                            ->row_array();
            if (!$row || (int)$row['is_active'] !== 1) {
                $this->session->sess_destroy();
                $this->current_user = null;
            } else {
                // refresh cached user data
                $this->current_user = $row;
                $this->session->set_userdata('user', $row);
            }
        }
    }

    /** Redirect to login if not authenticated. */
    protected function require_login()
    {
        if (empty($this->current_user)) {
            redirect('login');
        }
    }

    /**
     * Restrict access to a list of allowed roles.
     * @param array $roles e.g. ['regional'] or ['regional','division']
     */
    protected function require_role(array $roles)
    {
        $this->require_login();
        if (!in_array($this->current_user['role'], $roles, true)) {
            show_error('You are not authorized to access this resource.', 403, 'Forbidden');
        }
    }

    /**
     * Render a page using the master layout.
     */
    protected function render($view, $data = [], $title = 'CLMD - DepEd Region XI')
    {
        $this->load->model(['Notification_model','Setting_model']);
        $data['_title']         = $title;
        $data['_user']          = $this->current_user;
        $data['_unread_count']  = $this->Notification_model->unread_count($this->current_user['user_id']);
        $data['_recent_notifs'] = $this->Notification_model->recent($this->current_user['user_id'], 8);
        $data['_settings']      = $this->Setting_model->get();
        $data['_content']       = $this->load->view($view, $data, TRUE);
        $this->load->view('layouts/main', $data);
    }

    /** Persist an activity log row. */
    protected function log_activity($action, $details = '')
    {
        $this->load->database();
        $this->db->insert('activity_logs', [
            'user_id'    => isset($this->current_user['user_id']) ? $this->current_user['user_id'] : null,
            'action'     => $action,
            'details'    => $details,
            'ip_address' => $this->input->ip_address(),
        ]);
    }
}
