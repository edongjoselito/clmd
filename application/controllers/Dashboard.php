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

        if ($user['role'] === 'regional') {
            $data = [
                'counts'      => $counts,
                'total_users' => count($this->User_model->all()),
                'total_divs'  => count($this->Division_model->all()),
                'total_schools' => $this->db->count_all('schools'),
                'recent_subs' => array_slice($this->Document_model->all(['status' => 'For Approval']), 0, 5),
            ];
        } else {
            $data = [
                'counts'        => $counts,
                'total_schools' => count($this->School_model->all(['division_id' => $div_id])),
                'recent_subs'   => array_slice(
                    $this->Document_model->all(['division_id' => $div_id]), 0, 5
                ),
            ];
        }

        $this->render('dashboard/index', $data, 'Dashboard');
    }
}
