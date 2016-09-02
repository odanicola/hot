<?php
class Hot_model extends CI_Model {

    var $tabel_pasien = 'app_users_profile';
    var $t_puskesmas  = 'cl_phc';

    function __construct() {
        parent::__construct();
    }

    function get_data_pasien($options=array()){
        $this->db->select("app_users_profile.nama,app_users_profile.jk,app_users_profile.phone_number, app_users_profile.bpjs,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),tgl_lahir)), '%Y')+0 AS usia",false);
        $this->db->join('app_users_list','app_users_profile.username = app_users_list.username','left');
        $this->db->where("level","pasien");
        $query = $this->db->get($this->tabel_pasien);
        return $query->result();
    }

    function get_pus ($code,$condition,$table){
        $this->db->select("*");
        $this->db->like($condition,$code);
        return $this->db->get($table)->result();
    }
   
}






