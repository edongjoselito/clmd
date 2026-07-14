<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schools extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model(['School_model','Division_model','Location_model']);
    }

    public function ajax_municipalities()
    {
        $this->output->set_content_type('application/json');
        try {
            $province = $this->input->get('province', TRUE);
            echo json_encode($this->Location_model->get_municipalities($province));
        } catch (Exception $e) {
            log_message('error', 'ajax_municipalities: ' . $e->getMessage());
            $this->output->set_status_header(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function ajax_barangays()
    {
        $this->output->set_content_type('application/json');
        try {
            $province = $this->input->get('province', TRUE);
            $municipality = $this->input->get('municipality', TRUE);
            echo json_encode($this->Location_model->get_barangays($province, $municipality));
        } catch (Exception $e) {
            log_message('error', 'ajax_barangays: ' . $e->getMessage());
            $this->output->set_status_header(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function index()
    {
        $filters = [
            'school_type' => $this->input->get('type', TRUE) ?: null,
            'search'      => $this->input->get('q', TRUE) ?: null,
        ];

        $division_name = null;

        // Division users only see their division's schools
        if ($this->current_user['role'] === 'division') {
            $filters['division_id'] = $this->current_user['division_id'];
            $division = $this->Division_model->get($this->current_user['division_id']);
            $division_name = $division ? $division['name'] : null;
        } elseif ($div = $this->input->get('division_id', TRUE)) {
            $filters['division_id'] = $div;
            $division = $this->Division_model->get($div);
            $division_name = $division ? $division['name'] : null;
        }

        $this->render('schools/index', [
            'rows'          => $this->School_model->all($filters),
            'divisions'     => $this->Division_model->all(),
            'filters'       => $filters,
            'division_name' => $division_name,
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
            $this->form_validation->set_rules('school_code', 'School Code', 'trim|max_length[50]|alpha_numeric');
            $this->form_validation->set_rules('school_type', 'School Type', 'trim|required|in_list[Public,Private]');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[100]');
            $this->form_validation->set_rules('province', 'Province', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('city', 'City', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('barangay', 'Barangay', 'trim|required|max_length[100]');

            if ($this->form_validation->run() === TRUE) {
                $payload = [
                    'school_name' => $this->input->post('school_name', TRUE),
                    'school_code' => $this->input->post('school_code', TRUE) ?: null,
                    'school_type' => $this->input->post('school_type', TRUE),
                    'email'       => $this->input->post('email', TRUE),
                    'province'    => $this->input->post('province', TRUE),
                    'city'        => $this->input->post('city', TRUE),
                    'barangay'    => $this->input->post('barangay', TRUE),
                    'is_active'   => $this->input->post('is_active') ? 1 : 0,
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

        $provinces = $this->Location_model->get_provinces();
        $municipalities = [];
        $barangays = [];

        if ($is_edit && !empty($row['province'])) {
            $municipalities = $this->Location_model->get_municipalities($row['province']);
            if (!empty($row['city'])) {
                $barangays = $this->Location_model->get_barangays($row['province'], $row['city']);
            }
        }

        $this->render('schools/form', [
            'row'            => $row,
            'is_edit'        => $is_edit,
            'provinces'      => $provinces,
            'municipalities' => $municipalities,
            'barangays'      => $barangays,
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
