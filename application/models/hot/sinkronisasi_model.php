<?php
class Sinkronisasi_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_data_pasien($start=0,$limit=999999,$options=array()){
        $this->db->select("id_kunjungan,app_users_profile.username,app_users_profile.nama,app_users_profile.jk,app_users_profile.phone_number, app_users_profile.bpjs,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),tgl_lahir)), '%Y')+0 AS usia",false);
        $this->db->join('app_users_profile','kunjungan.username = app_users_profile.username AND kunjungan.code = app_users_profile.code');
        $this->db->where('status_antri','selesai');
        $this->db->order_by('id_kunjungan','asc');
        $query = $this->db->get('kunjungan',$limit,$start);
        return $query->result();
    }

    function get_pemeriksaan($id_kunjungan){
        $this->db->select("kunjungan.*,app_users_profile.nama,app_users_profile.jk,app_users_profile.phone_number, app_users_profile.bpjs,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),tgl_lahir)), '%Y')+0 AS usia",false);
        $this->db->join('app_users_profile','kunjungan.username = app_users_profile.username AND kunjungan.code = app_users_profile.code');
        $this->db->order_by('id_kunjungan','asc');
        $this->db->where('id_kunjungan',$id_kunjungan);
        $query = $this->db->get('kunjungan');
        return $query->row_array();
    }

    function get_kunjungan($nik=""){
        $data = array();
        $this->db->where("username",$nik);
        $this->db->where("status_antri","antri");
        $query = $this->db->get("kunjungan");
        if ($query->num_rows()>0) {
            $data = $query->row_array();
        }

        $query->free_result();
        return $data;
    }

    function insert(){

        $data['id_kunjungan']    = $this->input->post('code').date("Ymd",strtotime($this->input->post('tgl')))."001";
        $data['username']        = $this->input->post('username');
        $data['code']            = $this->input->post('code');
        $data['status_antri']    = "antri";
        $data['tgl']             = date("Y-m-d",strtotime($this->input->post('tgl')));

        $this->db->where('username',$this->input->post('username'));
        $this->db->where('tgl',date("Y-m-d",strtotime($this->input->post('tgl'))));
        $query = $this->db->get('kunjungan');

        if ($query->num_rows() > 0) {
            return 'false';
        }else{
            $this->db->insert('kunjungan', $data);
            return 'true';  
        }
    }

    function batal(){
        $data['status_antri']   = 'batal';

        $this->db->where('username',$this->input->post('username'));
        $this->db->where('status_antri','antri');

        if ($this->db->update('kunjungan',$data)) {
            return true;
        }else{
            return false;  
        }
    }

    function delete_pasien($username){
        $this->db->delete('app_users_list', array('username' => $username));
        $this->db->delete('app_users_profile', array('username' => $username));
    } 
}






