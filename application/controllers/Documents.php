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

        $rows = $this->Document_model->all($filters);
        $school_ids = array_values(array_unique(array_filter(array_map(function($r){
            return (int)$r['school_id'];
        }, $rows))));
        $ready = $this->Document_model->ready_school_ids($school_ids);

        // Group documents by school
        $grouped = [];
        foreach ($rows as $r) {
            $sid = (int)$r['school_id'];
            if (!isset($grouped[$sid])) {
                $grouped[$sid] = [
                    'school_id' => $r['school_id'],
                    'school_name' => $r['school_name'],
                    'school_type' => $r['school_type'],
                    'division_code' => $r['division_code'] ?? null,
                    'division_name' => $r['division_name'] ?? null,
                    'documents' => []
                ];
            }
            $grouped[$sid]['documents'][] = $r;
        }

        $this->render('documents/index', [
            'rows'      => $rows,
            'grouped'   => $grouped,
            'divisions' => $this->Division_model->all(),
            'filters'   => $filters,
            'ready'     => $ready,
        ], 'Division Endorsement');
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
            $this->form_validation->set_rules('current_track', 'Current Track', 'required|max_length[100]');
            $this->form_validation->set_rules('current_strand', 'Current Strand', 'required|max_length[100]');
            $this->form_validation->set_rules('strengthened_track', 'Strengthened Track', 'required|max_length[100]');
            $this->form_validation->set_rules('strengthened_strand', 'Strengthened Strand', 'required|max_length[150]');

            if ($this->form_validation->run() === TRUE) {
                $school = $this->School_model->get($this->input->post('school_id'));
                if (!$school || (int)$school['division_id'] !== (int)$this->current_user['division_id']) {
                    $this->session->set_flashdata('error', 'Invalid school selection.');
                    redirect(current_url());
                }

                // Handle dual file upload for new submissions
                if (!$is_edit) {
                    $cert_fp = $this->_handle_upload('cert_file');
                    $endorse_fp = $this->_handle_upload('endorse_file');

                    if (!$cert_fp || !$endorse_fp) {
                        $this->session->set_flashdata('error', 'Both files must be uploaded.');
                        redirect(current_url());
                    }

                    // Insert Certification
                    $cert_payload = [
                        'school_id'                  => $school['school_id'],
                        'division_id'                => $this->current_user['division_id'],
                        'submitted_by'               => $this->current_user['user_id'],
                        'document_title'             => 'Certification of Compliance',
                        'document_type'              => 'Certification of Compliance to DepEd Order No. 54, s. 2022',
                        'file_path'                  => $cert_fp,
                        'remarks'                    => null,
                        'current_track'              => $this->input->post('current_track', TRUE),
                        'current_strand'             => $this->input->post('current_strand', TRUE),
                        'current_specializations'    => $this->input->post('current_specializations', TRUE) ?: null,
                        'strengthened_track'         => $this->input->post('strengthened_track', TRUE),
                        'strengthened_strand'        => $this->input->post('strengthened_strand', TRUE),
                        'strengthened_specializations' => $this->input->post('strengthened_specializations', TRUE) ?: null,
                        'status'                     => 'For Approval',
                    ];
                    $cert_id = $this->Document_model->insert($cert_payload);
                    $this->log_activity('document_submit', "Cert #$cert_id");

                    // Insert Endorsement
                    $endorse_payload = [
                        'school_id'                  => $school['school_id'],
                        'division_id'                => $this->current_user['division_id'],
                        'submitted_by'               => $this->current_user['user_id'],
                        'document_title'             => 'Endorsement',
                        'document_type'              => 'Endorsement',
                        'file_path'                  => $endorse_fp,
                        'remarks'                    => null,
                        'current_track'              => $this->input->post('current_track', TRUE),
                        'current_strand'             => $this->input->post('current_strand', TRUE),
                        'current_specializations'    => $this->input->post('current_specializations', TRUE) ?: null,
                        'strengthened_track'         => $this->input->post('strengthened_track', TRUE),
                        'strengthened_strand'        => $this->input->post('strengthened_strand', TRUE),
                        'strengthened_specializations' => $this->input->post('strengthened_specializations', TRUE) ?: null,
                        'status'                     => 'For Approval',
                    ];
                    $endorse_id = $this->Document_model->insert($endorse_payload);
                    $this->log_activity('document_submit', "Endorse #$endorse_id");

                    // notify regional users
                    $this->Notification_model->notify_regional(
                        'New Documents for Approval',
                        sprintf('%s submitted Certification & Endorsement for %s',
                            $this->current_user['full_name'] ?? '',
                            $school['school_name']
                        ),
                        site_url('documents')
                    );

                    $this->session->set_flashdata('success', 'Both documents submitted for approval.');
                    redirect('documents');
                } else {
                    // Handle edit mode (single document)
                    $payload = [
                        'school_id'                  => $school['school_id'],
                        'current_track'              => $this->input->post('current_track', TRUE),
                        'current_strand'             => $this->input->post('current_strand', TRUE),
                        'current_specializations'    => $this->input->post('current_specializations', TRUE) ?: null,
                        'strengthened_track'         => $this->input->post('strengthened_track', TRUE),
                        'strengthened_strand'        => $this->input->post('strengthened_strand', TRUE),
                        'strengthened_specializations' => $this->input->post('strengthened_specializations', TRUE) ?: null,
                    ];

                    $fp = $this->_handle_upload('cert_file');
                    if ($fp) $payload['file_path'] = $fp;

                    if ($row['status'] !== 'For Approval') {
                        $payload['status']       = 'For Approval';
                        $payload['review_notes'] = null;
                    }
                    $this->Document_model->update($row['document_id'], $payload);
                    $doc_id = $row['document_id'];
                    $this->log_activity('document_update', "Doc #$doc_id");

                    // notify regional users
                    $this->Notification_model->notify_regional(
                        'Document Updated for Approval',
                        sprintf('%s - %s',
                            $this->current_user['full_name'] ?? '',
                            $school['school_name']
                        ),
                        site_url('documents/view/' . $doc_id)
                    );

                    $this->session->set_flashdata('success', 'Document updated and submitted for approval.');
                    redirect('documents');
                }
            }
        }

        $this->render('documents/form', [
            'row'     => $row,
            'is_edit' => $is_edit,
            'schools' => $this->School_model->for_dropdown($this->current_user['division_id']),
        ], $is_edit ? 'Edit Division Endorsement' : 'Division Endorsement');
    }

    public function view($id)
    {
        $row = $this->Document_model->get($id);
        if (!$row) show_404();
        if ($this->current_user['role'] === 'division'
            && (int)$row['division_id'] !== (int)$this->current_user['division_id']) {
            show_error('Forbidden', 403);
        }

        // Get all documents for this school
        $school_docs = $this->db->select('*')
                                ->from('documents')
                                ->where('school_id', $row['school_id'])
                                ->order_by('created_at', 'DESC')
                                ->get()->result_array();

        $this->render('documents/view', [
            'row'         => $row,
            'school_docs' => $school_docs,
            'pair_ready'  => $this->Document_model->pair_ready($row['school_id']),
        ], 'View Submissions');
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

            // If this approval completes the pair, notify the submitter that
            // the combined certification is now printable.
            if ($action === 'approve' && $this->Document_model->pair_ready($row['school_id'])) {
                $this->Notification_model->create(
                    $row['submitted_by'],
                    'Certification Ready to Print',
                    sprintf('Both documents for %s are now Approved. You may print the combined Certification.',
                            $row['school_name']),
                    site_url('documents/certificate/' . $row['school_id'])
                );
            }

            $this->session->set_flashdata('success', 'Review saved.');
            redirect('documents');
        }

        $this->render('documents/review', ['row' => $row], 'Review Document');
    }

    /**
     * Print combined certification page for a school.
     * Requires BOTH the Certification of Compliance (DO 54 s. 2022)
     * and the Endorsement to be Approved.
     */
    public function certificate($school_id)
    {
        $school = $this->School_model->get($school_id);
        if (!$school) show_404();

        if ($this->current_user['role'] === 'division'
            && (int)$school['division_id'] !== (int)$this->current_user['division_id']) {
            show_error('Forbidden', 403);
        }

        $pair = $this->Document_model->get_approved_pair($school_id);
        if (empty($pair['certification']) || empty($pair['endorsement'])) {
            show_error(
                'Printing is not yet allowed. Both the <strong>Certification of Compliance to '
                .'DepEd Order No. 54, s. 2022</strong> and the <strong>Endorsement</strong> '
                .'must be approved before a certification can be issued.',
                403,
                'Certification Not Yet Available'
            );
        }

        // Use the Certification document's control_no as the package reference
        $verify_url = site_url('verify/' . urlencode($pair['certification']['control_no']));
        $qr_url     = 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data='
                      . urlencode($verify_url);

        $this->load->view('documents/certificate', [
            'school'     => $pair['certification'], // contains joined school + division fields
            'cert'       => $pair['certification'],
            'endorse'    => $pair['endorsement'],
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

    public function view_file($id)
    {
        $row = $this->Document_model->get($id);
        if (!$row) show_404();
        if ($this->current_user['role'] === 'division'
            && (int)$row['division_id'] !== (int)$this->current_user['division_id']) {
            show_error('Forbidden', 403);
        }
        
        $file_path = FCPATH . $row['file_path'];
        if (!file_exists($file_path) || !is_readable($file_path)) {
            show_404();
        }
        
        // Output file with inline display headers
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($file_path) . '"');
        header('Content-Length: ' . filesize($file_path));
        header('Cache-Control: public, must-revalidate, max-age=1');
        header('Pragma: public');
        readfile($file_path);
        exit;
    }

    private function _handle_upload($field_name = 'file')
    {
        if (empty($_FILES[$field_name]['name'])) return null;
        $upload_dir = FCPATH . 'uploads/documents/';
        if (!is_dir($upload_dir)) @mkdir($upload_dir, 0775, true);

        $config = [
            'upload_path'   => $upload_dir,
            'allowed_types' => 'pdf',
            'max_size'      => 51200,
            'encrypt_name'  => TRUE,
            'file_ext_tolower' => TRUE,
        ];
        $this->load->library('upload', $config);

        if ($this->upload->do_upload($field_name)) {
            $info = $this->upload->data();
            return 'uploads/documents/' . $info['file_name'];
        }
        $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
        return null;
    }
}
