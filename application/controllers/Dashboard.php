<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model(['Document_model','School_model','Division_model','User_model']);
    }

    public function index()
    {
        $user = $this->current_user;
        $div_id = ($user['role'] === 'division') ? $user['division_id'] : null;

        $counts = $this->Document_model->counts_by_status($div_id);

        // Get recent submissions
        $filters = ['division_id' => $div_id];
        if ($user['role'] === 'regional') {
            $filters['status'] = 'For Approval';
        }
        $recent_subs = $this->Document_model->all($filters);

        // Group by school
        $grouped_subs = [];
        foreach ($recent_subs as $r) {
            $sid = (int)$r['school_id'];
            if (!isset($grouped_subs[$sid])) {
                $grouped_subs[$sid] = [
                    'school_id' => $r['school_id'],
                    'school_name' => $r['school_name'],
                    'school_type' => $r['school_type'],
                    'division_code' => $r['division_code'] ?? null,
                    'division_name' => $r['division_name'] ?? null,
                    'documents' => []
                ];
            }
            $grouped_subs[$sid]['documents'][] = $r;
        }
        $grouped_subs = array_slice($grouped_subs, 0, 5, true);

        if ($user['role'] === 'regional') {
            $data = [
                'counts'      => $counts,
                'total_users' => count($this->User_model->all()),
                'total_divs'  => count($this->Division_model->all()),
                'total_schools' => $this->db->count_all('schools'),
                'recent_subs' => $grouped_subs,
            ];
        } else {
            $data = [
                'counts'        => $counts,
                'total_schools' => count($this->School_model->all(['division_id' => $div_id])),
                'recent_subs'   => $grouped_subs,
            ];
        }

        $this->render('dashboard/index', $data, 'Dashboard');
    }
}
