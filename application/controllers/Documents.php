<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documents extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model(['Document_model','School_model','Division_model','Notification_model','Setting_model']);
    }

    public function index()
    {
        $filters = [
            'status' => $this->input->get('status', TRUE) ?: null,
            'search' => $this->input->get('q', TRUE) ?: null,
        ];
        if ($this->current_user['role'] === 'division') {
            $filters['division_id'] = $this->current_user['division_id'];
        } elseif ($div = $this->input->get('division_id', TRUE)) {
            $filters['division_id'] = $div;
        }

        $this->render('documents/index', [
            'rows'      => $this->Document_model->all($filters),
            'divisions' => $this->Division_model->all(),
            'filters'   => $filters,
        ], 'Documents');
    }

    public function create()
    {
        $this->require_role(['division']);
        $this->_form();
    }

    public function edit($id)
    {
        $this->require_role(['division']);
        $row = $this->Document_model->get($id);
        if (!$row) show_404();
        if ((int)$row['division_id'] !== (int)$this->current_user['division_id']) show_error('Forbidden', 403);
        if ($row['status'] === 'Approved') show_error('Approved documents cannot be edited.', 403);
        $this->_form($row);
    }

    private function _form($row = null)
    {
        $is_edit = !empty($row);

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('school_id', 'School', 'required|integer');
            $this->form_validation->set_rules('document_title', 'Document Title', 'trim|required|max_length[255]');
            $this->form_validation->set_rules('document_type', 'Document Type', 'trim|required|max_length[120]');

            if ($this->form_validation->run() === TRUE) {
                $school = $this->School_model->get($this->input->post('school_id'));
                if (!$school || (int)$school['division_id'] !== (int)$this->current_user['division_id']) {
                    $this->session->set_flashdata('error', 'Invalid school selection.');
                    redirect(current_url());
                }

                $payload = [
                    'school_id'      => $school['school_id'],
                    'document_title' => $this->input->post('document_title', TRUE),
                    'document_type'  => $this->input->post('document_type', TRUE),
                    'remarks'        => $this->input->post('remarks', TRUE) ?: null,
                ];

                $fp = $this->_handle_upload();
                if ($fp) $payload['file_path'] = $fp;

                if ($is_edit) {
                    if ($row['status'] !== 'For Approval') {
                        $payload['status']       = 'For Approval';
                        $payload['review_notes'] = null;
                    }
                    $this->Document_model->update($row['document_id'], $payload);
                    $doc_id = $row['document_id'];
                    $this->log_activity('document_update', "Doc #$doc_id");
                } else {
                    $payload['division_id']  = $this->current_user['division_id'];
                    $payload['submitted_by'] = $this->current_user['user_id'];
                    $payload['status']       = 'For Approval';
                    $doc_id = $this->Document_model->insert($payload);
                    $this->log_activity('document_submit', "Doc #$doc_id");
                }

                // notify regional users
                $this->Notification_model->notify_regional(
                    'New Document for Approval',
                    sprintf('%s - %s (%s)',
                        $this->current_user['full_name'] ?? '',
                        $payload['document_title'],
                        $school['school_name']
                    ),
                    site_url('documents/view/' . $doc_id)
                );

                $this->session->set_flashdata('success', 'Document submitted for approval.');
                redirect('documents');
            }
        }

        $this->render('documents/form', [
            'row'     => $row,
            'is_edit' => $is_edit,
            'schools' => $this->School_model->for_dropdown($this->current_user['division_id']),
        ], $is_edit ? 'Edit Document' : 'Submit Document');
    }

    public function view($id)
    {
        $row = $this->Document_model->get($id);
        if (!$row) show_404();
        if ($this->current_user['role'] === 'division'
            && (int)$row['division_id'] !== (int)$this->current_user['division_id']) {
            show_error('Forbidden', 403);
        }
        $this->render('documents/view', ['row' => $row], 'View Document');
    }

    public function review($id)
    {
        $this->require_role(['regional']);
        $row = $this->Document_model->get($id);
        if (!$row) show_404();

        if ($this->input->method() === 'post') {
            $action = $this->input->post('action', TRUE); // approve | reject | revise
            $notes  = $this->input->post('review_notes', TRUE) ?: null;

            $map = ['approve' => 'Approved', 'reject' => 'Rejected', 'revise' => 'Revised'];
            if (!isset($map[$action])) show_error('Invalid action.', 400);

            $update = [
                'status'       => $map[$action],
                'review_notes' => $notes,
                'reviewed_by'  => $this->current_user['user_id'],
                'reviewed_at'  => date('Y-m-d H:i:s'),
            ];

            if ($action === 'approve') {
                if (empty($row['control_no'])) {
                    $update['control_no'] = $this->Document_model->next_control_no();
                }
                $update['approved_at'] = date('Y-m-d H:i:s');
            }

            $this->Document_model->update($id, $update);
            $this->log_activity('document_review', "Doc #$id -> {$map[$action]}");

            // notify the original submitter
            $msg_title = 'Document ' . $map[$action];
            $msg_body  = sprintf('%s (%s)', $row['document_title'], $row['school_name']);
            $this->Notification_model->create(
                $row['submitted_by'], $msg_title, $msg_body,
                site_url('documents/view/' . $id)
            );

            $this->session->set_flashdata('success', 'Review saved.');
            redirect('documents');
        }

        $this->render('documents/review', ['row' => $row], 'Review Document');
    }

    /** Print certification page (only for Approved documents). */
    public function certificate($id)
    {
        $row = $this->Document_model->get($id);
        if (!$row) show_404();
        if ($row['status'] !== 'Approved') {
            show_error('Certificate is available only after approval.', 403);
        }
        if ($this->current_user['role'] === 'division'
            && (int)$row['division_id'] !== (int)$this->current_user['division_id']) {
            show_error('Forbidden', 403);
        }

        $verify_url = site_url('verify/' . urlencode($row['control_no']));
        $qr_url     = 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data='
                      . urlencode($verify_url);

        $this->load->view('documents/certificate', [
            'row'        => $row,
            'settings'   => $this->Setting_model->get(),
            'verify_url' => $verify_url,
            'qr_url'     => $qr_url,
        ]);
    }

    public function delete($id)
    {
        $row = $this->Document_model->get($id);
        if (!$row) show_404();
        if ($this->current_user['role'] === 'division') {
            if ((int)$row['division_id'] !== (int)$this->current_user['division_id']
                || $row['status'] === 'Approved') {
                show_error('Forbidden', 403);
            }
        }
        $this->Document_model->delete($id);
        $this->session->set_flashdata('success', 'Document deleted.');
        redirect('documents');
    }

    private function _handle_upload()
    {
        if (empty($_FILES['file']['name'])) return null;
        $upload_dir = FCPATH . 'uploads/documents/';
        if (!is_dir($upload_dir)) @mkdir($upload_dir, 0775, true);

        $config = [
            'upload_path'   => $upload_dir,
            'allowed_types' => 'pdf|doc|docx|jpg|jpeg|png',
            'max_size'      => 51200,
            'encrypt_name'  => TRUE,
        ];
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('file')) {
            $info = $this->upload->data();
            return 'uploads/documents/' . $info['file_name'];
        }
        $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
        return null;
    }
}
