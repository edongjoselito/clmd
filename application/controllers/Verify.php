<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Public certificate verification page (target of QR code).
 */
class Verify extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Document_model','Setting_model']);
    }

    public function index($control_no = null)
    {
        $row = $control_no ? $this->Document_model->get_by_control_no($control_no) : null;
        $this->load->view('verify/index', [
            'row'        => $row,
            'control_no' => $control_no,
            'settings'   => $this->Setting_model->get(),
        ]);
    }
}
