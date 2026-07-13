<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_role(['regional']);
        $this->load->model('Setting_model');
    }

    public function index()
    {
        if ($this->input->method() === 'post') {
            $payload = [
                'chief_name'      => $this->input->post('chief_name', TRUE) ?: null,
                'chief_position'  => $this->input->post('chief_position', TRUE) ?: null,
            ];

            // Handle letterhead upload
            if (!empty($_FILES['letterhead']['name'])) {
                $upload_dir = FCPATH . 'uploads/letterhead/';
                if (!is_dir($upload_dir)) @mkdir($upload_dir, 0775, true);
                $config = [
                    'upload_path'   => $upload_dir,
                    'allowed_types' => 'jpg|jpeg|png',
                    'max_size'      => 5120,
                    'encrypt_name'  => TRUE,
                ];
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('letterhead')) {
                    $info = $this->upload->data();
                    $payload['letterhead_path'] = 'uploads/letterhead/' . $info['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                }
            }

            // Handle signature upload
            if (!empty($_FILES['signature']['name'])) {
                $upload_dir = FCPATH . 'uploads/signature/';
                if (!is_dir($upload_dir)) @mkdir($upload_dir, 0775, true);
                $config = [
                    'upload_path'   => $upload_dir,
                    'allowed_types' => 'jpg|jpeg|png',
                    'max_size'      => 5120,
                    'encrypt_name'  => TRUE,
                ];
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('signature')) {
                    $info = $this->upload->data();
                    $payload['signature_path'] = 'uploads/signature/' . $info['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                }
            }

            $this->Setting_model->update($payload);
            $this->session->set_flashdata('success', 'Settings updated.');
            redirect('settings');
        }

        $this->render('settings/index', [
            'settings' => $this->Setting_model->get()
        ], 'Settings');
    }
}
