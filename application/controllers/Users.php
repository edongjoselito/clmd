<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_role(['regional','division']);
        $this->load->model(['User_model','Division_model']);
    }

    public function index()
    {
        $filters = [];
        if ($this->current_user['role'] === 'division') {
            $filters['division_id'] = $this->current_user['division_id'];
        }
        $data = [
            'users'     => $this->User_model->all($filters),
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
        if ($this->current_user['role'] === 'division'
            && (int)$user['division_id'] !== (int)$this->current_user['division_id']) {
            show_error('Forbidden', 403);
        }
        if ($this->current_user['role'] === 'division' && $user['role'] === 'regional') {
            show_error('Forbidden', 403);
        }
        $this->_form($user);
    }

    private function _form($user = null)
    {
        $is_edit = !empty($user);

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[100]|callback_email_check');
            $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('position', 'Position', 'trim|max_length[100]');
            $this->form_validation->set_rules('role', 'Role', 'trim|required|in_list[regional,division]');
            $this->form_validation->set_rules('division_id', 'Division', 'integer');
            if (!$is_edit) {
                $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
            }

            if ($this->form_validation->run() === TRUE) {
                $email = $this->input->post('email', TRUE);
                $payload = [
                    'username'    => $email,
                    'full_name'   => $this->input->post('full_name', TRUE),
                    'email'       => $email,
                    'role'        => $this->input->post('role', TRUE),
                    'division_id' => $this->input->post('division_id', TRUE) ?: null,
                    'position'    => $this->input->post('position', TRUE) ?: null,
                    'is_active'   => $this->input->post('is_active') ? 1 : 0,
                ];
                if ($this->current_user['role'] === 'division') {
                    $payload['role']        = 'division';
                    $payload['division_id'] = $this->current_user['division_id'];
                } elseif ($payload['role'] === 'regional') {
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

        $this->render('users/form', [
            'user'      => $user,
            'is_edit'   => $is_edit,
            'divisions' => $this->Division_model->all(),
        ], $is_edit ? 'Edit User' : 'New User');
    }

    public function email_check($email)
    {
        $exclude = null;
        if ($this->uri->segment(2) === 'edit' && $this->uri->segment(3)) {
            $exclude = (int)$this->uri->segment(3);
        }
        if ($this->User_model->email_exists($email, $exclude)) {
            $this->form_validation->set_message('email_check', 'The %s is already registered.');
            return FALSE;
        }
        return TRUE;
    }

    public function delete($id)
    {
        $u = $this->User_model->get($id);
        if (!$u) show_404();
        if ((int)$id === (int)$this->current_user['user_id']) {
            $this->session->set_flashdata('error', 'You cannot delete your own account.');
        } elseif ($this->current_user['role'] === 'division'
                  && ((int)$u['division_id'] !== (int)$this->current_user['division_id']
                      || $u['role'] === 'regional')) {
            $this->session->set_flashdata('error', 'You are not authorized to delete this user.');
        } else {
            $this->User_model->delete($id);
            $this->log_activity('user_delete', 'Deleted user ' . $u['username']);
            $this->session->set_flashdata('success', 'User deleted.');
        }
        redirect('users');
    }
}
