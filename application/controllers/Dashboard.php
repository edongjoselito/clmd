<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model(['Learning_material_model','Curriculum_model','Division_model','User_model']);
    }

    public function index()
    {
        $user = $this->current_user;

        if ($user['role'] === 'regional') {
            $data = [
                'counts'        => $this->Learning_material_model->counts_by_status(),
                'total_users'   => count($this->User_model->all()),
                'total_divs'    => count($this->Division_model->all()),
                'total_curr'    => count($this->Curriculum_model->all(['is_active' => 1])),
                'recent_subs'   => array_slice($this->Learning_material_model->all(), 0, 5),
            ];
        } else {
            $div_id = $user['division_id'];
            $data = [
                'counts'      => $this->Learning_material_model->counts_by_status($div_id),
                'recent_subs' => array_slice(
                    $this->Learning_material_model->all(['division_id' => $div_id]), 0, 5
                ),
                'total_curr'  => count($this->Curriculum_model->all(['is_active' => 1])),
            ];
        }

        $this->render('dashboard/index', $data, 'Dashboard');
    }
}
