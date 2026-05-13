<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index()
    {
        redirect('login');
    }

    public function login()
    {
        if ($this->session->userdata('user')) {
            redirect('dashboard');
        }

        $data = ['error' => null, 'username' => ''];

        if ($this->input->method() === 'post') {
            $username = trim((string)$this->input->post('username', TRUE));
            $password = (string)$this->input->post('password', TRUE);
            $data['username'] = $username;

            if ($username === '' || $password === '') {
                $data['error'] = 'Please provide both username and password.';
            } else {
                $user = $this->User_model->get_by_username($username);
                if (!$user || (int)$user['is_active'] !== 1 || !password_verify($password, $user['password'])) {
                    $data['error'] = 'Invalid credentials or inactive account.';
                } else {
                    unset($user['password']);
                    $this->session->set_userdata('user', $user);
                    $this->User_model->touch_last_login($user['user_id']);
                    redirect('dashboard');
                }
            }
        }

        $this->load->view('auth/login', $data);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
