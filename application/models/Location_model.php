<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Location_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_provinces()
    {
        return $this->db
            ->select('Province AS province')
            ->distinct()
            ->order_by('Province', 'ASC')
            ->get('settings_address')
            ->result_array();
    }

    public function get_municipalities($province)
    {
        return $this->db
            ->select('City AS municipality')
            ->distinct()
            ->where('Province', $province)
            ->order_by('City', 'ASC')
            ->get('settings_address')
            ->result_array();
    }

    public function get_barangays($province, $municipality)
    {
        return $this->db
            ->select('Brgy AS barangay')
            ->distinct()
            ->where('Province', $province)
            ->where('City', $municipality)
            ->order_by('Brgy', 'ASC')
            ->get('settings_address')
            ->result_array();
    }
}
