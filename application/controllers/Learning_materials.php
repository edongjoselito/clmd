<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Learning_materials extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model(['Learning_material_model','Division_model']);
    }

    public function index()
    {
        $filters = [];
        if ($this->current_user['role'] === 'division') {
            $filters['division_id'] = $this->current_user['division_id'];
        }
        $status = $this->input->get('status', TRUE);
        if ($status) $filters['status'] = $status;

        $rows = $this->Learning_material_model->all($filters);
        $this->render('learning_materials/index', [
            'rows'      => $rows,
            'divisions' => $this->Division_model->all(),
            'status'    => $status,
        ], 'Learning Materials');
    }

    public function create()
    {
        $this->require_role(['division']); // division submits
        $this->_form();
    }

    public function edit($id)
    {
        $row = $this->Learning_material_model->get($id);
        if (!$row) show_404();
        // division can edit only own pending; regional can always edit
        if ($this->current_user['role'] === 'division') {
            if ((int)$row['division_id'] !== (int)$this->current_user['division_id']) show_error('Forbidden', 403);
            if ($row['status'] === 'Approved') show_error('Approved materials cannot be edited.', 403);
        }
        $this->_form($row);
    }

    private function _form($row = null)
    {
        $is_edit = !empty($row);

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[255]');
            $this->form_validation->set_rules('type', 'Type', 'required');
            $this->form_validation->set_rules('grade_level', 'Grade Level', 'trim|required');
            $this->form_validation->set_rules('subject', 'Subject', 'trim|required');

            if ($this->form_validation->run() === TRUE) {
                $payload = [
                    'title'       => $this->input->post('title', TRUE),
                    'type'        => $this->input->post('type', TRUE),
                    'grade_level' => $this->input->post('grade_level', TRUE),
                    'subject'     => $this->input->post('subject', TRUE),
                    'quarter'     => $this->input->post('quarter', TRUE) ?: null,
                    'description' => $this->input->post('description', TRUE) ?: null,
                ];

                $fp = $this->_handle_upload();
                if ($fp) $payload['file_path'] = $fp;

                if ($is_edit) {
                    // resubmitting after revisions resets status to Pending
                    if ($this->current_user['role'] === 'division' && $row['status'] !== 'Pending') {
                        $payload['status']  = 'Pending';
                        $payload['remarks'] = null;
                    }
                    $this->Learning_material_model->update($row['material_id'], $payload);
                } else {
                    $payload['division_id']  = $this->current_user['division_id'];
                    $payload['submitted_by'] = $this->current_user['user_id'];
                    $payload['status']       = 'Pending';
                    $this->Learning_material_model->insert($payload);
                }
                $this->session->set_flashdata('success', 'Learning material saved.');
                redirect('learning-materials');
            }
        }

        $this->render('learning_materials/form', [
            'row' => $row, 'is_edit' => $is_edit
        ], $is_edit ? 'Edit Material' : 'Submit Material');
    }

    public function view($id)
    {
        $row = $this->Learning_material_model->get($id);
        if (!$row) show_404();
        if ($this->current_user['role'] === 'division'
            && (int)$row['division_id'] !== (int)$this->current_user['division_id']) {
            show_error('Forbidden', 403);
        }
        $this->render('learning_materials/view', ['row' => $row], 'View Material');
    }

    public function review($id)
    {
        $this->require_role(['regional']);
        $row = $this->Learning_material_model->get($id);
        if (!$row) show_404();

        if ($this->input->method() === 'post') {
            $status  = $this->input->post('status', TRUE);
            $remarks = $this->input->post('remarks', TRUE) ?: null;
            if (!in_array($status, ['Approved','Rejected','Revised'], true)) {
                show_error('Invalid status.', 400);
            }
            $this->Learning_material_model->update($id, [
                'status'      => $status,
                'remarks'     => $remarks,
                'reviewed_by' => $this->current_user['user_id'],
                'reviewed_at' => date('Y-m-d H:i:s'),
            ]);
            $this->log_activity('material_review', "Material #$id -> $status");
            $this->session->set_flashdata('success', 'Review saved.');
            redirect('learning-materials');
        }

        $this->render('learning_materials/review', ['row' => $row], 'Review Material');
    }

    public function delete($id)
    {
        $row = $this->Learning_material_model->get($id);
        if (!$row) show_404();
        if ($this->current_user['role'] === 'division') {
            if ((int)$row['division_id'] !== (int)$this->current_user['division_id']
                || $row['status'] === 'Approved') {
                show_error('Forbidden', 403);
            }
        }
        $this->Learning_material_model->delete($id);
        $this->session->set_flashdata('success', 'Material deleted.');
        redirect('learning-materials');
    }

    private function _handle_upload()
    {
        if (empty($_FILES['file']['name'])) return null;
        $upload_dir = FCPATH . 'uploads/materials/';
        if (!is_dir($upload_dir)) @mkdir($upload_dir, 0775, true);

        $config = [
            'upload_path'   => $upload_dir,
            'allowed_types' => 'pdf|doc|docx|ppt|pptx|xls|xlsx|zip|mp4',
            'max_size'      => 102400,
            'encrypt_name'  => TRUE,
        ];
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('file')) {
            $info = $this->upload->data();
            return 'uploads/materials/' . $info['file_name'];
        }
        $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
        return null;
    }
}
