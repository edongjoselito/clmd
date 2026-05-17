<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_role(['regional']); // user mgmt is regional-only
        $this->load->model(['User_model','Division_model']);
    }

    public function index()
    {
        $data = [
            'users'     => $this->User_model->all(),
            'divisions' => $this->Division_model->all(),
        ];
        $this->render('users/index', $data, 'Users');
    }

    public function create()
    {
        $this->_form();
    }

    public function edit($id)
    {
        $user = $this->User_model->get($id);
        if (!$user) show_404();
        $this->_form($user);
    }

    private function _form($user = null)
    {
        $is_edit = !empty($user);

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('username', 'Username', 'trim|required|alpha_numeric|min_length[3]|max_length[50]|callback_username_check');
            $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|max_length[100]');
            $this->form_validation->set_rules('position', 'Position', 'trim|max_length[100]');
            $this->form_validation->set_rules('role', 'Role', 'trim|required|in_list[regional,division]');
            $this->form_validation->set_rules('division_id', 'Division', 'integer');
            if (!$is_edit) {
                $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
            }

            if ($this->form_validation->run() === TRUE) {
                $username = $this->input->post('username', TRUE);
                $exclude  = $is_edit ? $user['user_id'] : null;

                if ($this->User_model->username_exists($username, $exclude)) {
                    $this->session->set_flashdata('error', 'Username already exists.');
                } else {
                    $payload = [
                        'username'    => $username,
                        'full_name'   => $this->input->post('full_name', TRUE),
                        'email'       => $this->input->post('email', TRUE) ?: null,
                        'role'        => $this->input->post('role', TRUE),
                        'division_id' => $this->input->post('division_id', TRUE) ?: null,
                        'position'    => $this->input->post('position', TRUE) ?: null,
                        'is_active'   => $this->input->post('is_active') ? 1 : 0,
                    ];
                    if ($payload['role'] === 'regional') {
                        $payload['division_id'] = null;
                    }
                    $pwd = $this->input->post('password', TRUE);
                    if ($pwd) $payload['password'] = $pwd;

                    if ($is_edit) {
                        $this->User_model->update($user['user_id'], $payload);
                        $this->log_activity('user_update', 'Updated user ' . $payload['username']);
                    } else {
                        $this->User_model->insert($payload);
                        $this->log_activity('user_create', 'Created user ' . $payload['username']);
                    }
                    $this->session->set_flashdata('success', 'User saved.');
                    redirect('users');
                }
            }
        }

        $this->render('users/form', [
            'user'      => $user,
            'is_edit'   => $is_edit,
            'divisions' => $this->Division_model->all(),
        ], $is_edit ? 'Edit User' : 'New User');
    }

    public function delete($id)
    {
        $u = $this->User_model->get($id);
        if (!$u) show_404();
        if ((int)$id === (int)$this->current_user['user_id']) {
            $this->session->set_flashdata('error', 'You cannot delete your own account.');
        } else {
            $this->User_model->delete($id);
            $this->log_activity('user_delete', 'Deleted user ' . $u['username']);
            $this->session->set_flashdata('success', 'User deleted.');
        }
        redirect('users');
    }
}
