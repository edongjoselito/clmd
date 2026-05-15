<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schools extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model(['School_model','Division_model']);
    }

    public function index()
    {
        $filters = [
            'school_type' => $this->input->get('type', TRUE) ?: null,
            'search'      => $this->input->get('q', TRUE) ?: null,
        ];

        // Division users only see their division's schools
        if ($this->current_user['role'] === 'division') {
            $filters['division_id'] = $this->current_user['division_id'];
        } elseif ($div = $this->input->get('division_id', TRUE)) {
            $filters['division_id'] = $div;
        }

        $this->render('schools/index', [
            'rows'      => $this->School_model->all($filters),
            'divisions' => $this->Division_model->all(),
            'filters'   => $filters,
        ], 'Schools');
    }

    public function create()
    {
        $this->require_role(['division']);
        $this->_form();
    }

    public function edit($id)
    {
        $this->require_role(['division']);
        $row = $this->School_model->get($id);
        if (!$row) show_404();
        if ((int)$row['division_id'] !== (int)$this->current_user['division_id']) show_error('Forbidden', 403);
        $this->_form($row);
    }

    private function _form($row = null)
    {
        $is_edit = !empty($row);

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('school_name', 'School Name', 'trim|required|max_length[255]');
            $this->form_validation->set_rules('school_type', 'School Type', 'required|in_list[Public,Private]');

            if ($this->form_validation->run() === TRUE) {
                $payload = [
                    'school_name'  => $this->input->post('school_name', TRUE),
                    'school_code'  => $this->input->post('school_code', TRUE) ?: null,
                    'school_type'  => $this->input->post('school_type', TRUE),
                    'address'      => $this->input->post('address', TRUE) ?: null,
                    'municipality' => $this->input->post('municipality', TRUE) ?: null,
                    'is_active'    => $this->input->post('is_active') ? 1 : 0,
                ];
                if ($is_edit) {
                    $this->School_model->update($row['school_id'], $payload);
                } else {
                    $payload['division_id'] = $this->current_user['division_id'];
                    $payload['created_by']  = $this->current_user['user_id'];
                    $this->School_model->insert($payload);
                }
                $this->session->set_flashdata('success', 'School saved.');
                redirect('schools');
            }
        }

        $this->render('schools/form', [
            'row' => $row, 'is_edit' => $is_edit
        ], $is_edit ? 'Edit School' : 'New School');
    }

    public function delete($id)
    {
        $this->require_role(['division']);
        $row = $this->School_model->get($id);
        if (!$row) show_404();
        if ((int)$row['division_id'] !== (int)$this->current_user['division_id']) show_error('Forbidden', 403);
        $this->School_model->delete($id);
        $this->session->set_flashdata('success', 'School deleted.');
        redirect('schools');
    }
}
