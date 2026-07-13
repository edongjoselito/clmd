<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Divisions extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_role(['regional']);
        $this->load->model('Division_model');
    }

    public function index()
    {
        $this->render('divisions/index', ['divisions' => $this->Division_model->all()], 'Divisions');
    }

    public function create() { $this->_form(); }

    public function edit($id)
    {
        $div = $this->Division_model->get($id);
        if (!$div) show_404();
        $this->_form($div);
    }

    private function _form($div = null)
    {
        $is_edit = !empty($div);

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[150]');

            if ($this->form_validation->run() === TRUE) {
                $payload = [
                    'name'      => $this->input->post('name', TRUE),
                    'address'   => $this->input->post('address', TRUE) ?: null,
                    'contact'   => $this->input->post('contact', TRUE) ?: null,
                    'is_active' => $this->input->post('is_active') ? 1 : 0,
                ];
                if ($is_edit) {
                    $this->Division_model->update($div['division_id'], $payload);
                } else {
                    $this->Division_model->insert($payload);
                }
                $this->session->set_flashdata('success', 'Division saved.');
                redirect('divisions');
            }
        }

        $this->render('divisions/form', [
            'div' => $div, 'is_edit' => $is_edit
        ], $is_edit ? 'Edit Division' : 'New Division');
    }

    public function delete($id)
    {
        $this->Division_model->delete($id);
        $this->session->set_flashdata('success', 'Division deleted.');
        redirect('divisions');
    }
}
