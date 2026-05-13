<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Curriculum extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model('Curriculum_model');
    }

    public function index()
    {
        $rows = $this->Curriculum_model->all(); // visible to both roles
        $this->render('curriculum/index', ['rows' => $rows], 'Curriculum');
    }

    public function create()
    {
        $this->require_role(['regional']);
        $this->_form();
    }

    public function edit($id)
    {
        $this->require_role(['regional']);
        $row = $this->Curriculum_model->get($id);
        if (!$row) show_404();
        $this->_form($row);
    }

    private function _form($row = null)
    {
        $is_edit = !empty($row);

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[255]');
            $this->form_validation->set_rules('grade_level', 'Grade Level', 'trim|required|max_length[30]');
            $this->form_validation->set_rules('subject', 'Subject', 'trim|required|max_length[100]');

            if ($this->form_validation->run() === TRUE) {
                $payload = [
                    'title'       => $this->input->post('title', TRUE),
                    'grade_level' => $this->input->post('grade_level', TRUE),
                    'subject'     => $this->input->post('subject', TRUE),
                    'description' => $this->input->post('description', TRUE) ?: null,
                    'school_year' => $this->input->post('school_year', TRUE) ?: null,
                    'is_active'   => $this->input->post('is_active') ? 1 : 0,
                ];

                $file_path = $this->_handle_upload();
                if ($file_path) $payload['file_path'] = $file_path;

                if ($is_edit) {
                    $this->Curriculum_model->update($row['curriculum_id'], $payload);
                } else {
                    $payload['created_by'] = $this->current_user['user_id'];
                    $this->Curriculum_model->insert($payload);
                }
                $this->session->set_flashdata('success', 'Curriculum saved.');
                redirect('curriculum');
            }
        }

        $this->render('curriculum/form', [
            'row' => $row, 'is_edit' => $is_edit
        ], $is_edit ? 'Edit Curriculum' : 'New Curriculum');
    }

    public function delete($id)
    {
        $this->require_role(['regional']);
        $this->Curriculum_model->delete($id);
        $this->session->set_flashdata('success', 'Curriculum deleted.');
        redirect('curriculum');
    }

    private function _handle_upload()
    {
        if (empty($_FILES['file']['name'])) return null;

        $upload_dir = FCPATH . 'uploads/curriculum/';
        if (!is_dir($upload_dir)) @mkdir($upload_dir, 0775, true);

        $config = [
            'upload_path'   => $upload_dir,
            'allowed_types' => 'pdf|doc|docx|ppt|pptx|xls|xlsx|zip',
            'max_size'      => 51200,
            'encrypt_name'  => TRUE,
        ];
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('file')) {
            $info = $this->upload->data();
            return 'uploads/curriculum/' . $info['file_name'];
        }
        $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
        return null;
    }
}
