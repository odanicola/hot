<?php
class Kunjungan_model extends CI_Model {

    var $tabel_pasien = 'app_users_profile';
    var $tabel_dokter = 'bpjs_data_dokter';
    var $t_puskesmas  = 'cl_phc';

    function __construct() {
        parent::__construct();
    }

    function get_data_dokter($options=array()){
        $this->db->select("*");
        $query = $this->db->get($this->tabel_dokter);
        return $query->result();
    }

    function get_data_pasien($options=array()){
        $this->db->select("app_users_profile.username,app_users_profile.nama,app_users_profile.jk,app_users_profile.phone_number, app_users_profile.bpjs,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),tgl_lahir)), '%Y')+0 AS usia",false);
        $this->db->join('app_users_list','app_users_profile.username = app_users_list.username','left');
        $this->db->where("level","pasien");
        $query = $this->db->get($this->tabel_pasien);
        return $query->result();
    }

    function get_pasien_where($username){
        $this->db->select("app_users_profile.username,app_users_list.password,app_users_profile.alamat,app_users_profile.email,app_users_profile.nama,app_users_profile.jk,app_users_profile.phone_number, app_users_profile.bpjs,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),tgl_lahir)), '%Y')+0 AS usia",false);
        $this->db->join('app_users_list','app_users_profile.username = app_users_list.username','left');
        $this->db->where("level","pasien");
        $this->db->where("app_users_profile.username",$username);
        $query = $this->db->get($this->tabel_pasien);
        if ($query->num_rows()>0) {
            $data = $query->row_array();
        }

        $query->free_result();
        return $data;
    }

    function get_pus ($code,$condition,$table){
        $this->db->select("*");
        $this->db->like($condition,$code);
        $query = $this->db->get($table);
        return $query->result();
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

    function update_pasien($username){

        $data_list['username']           = $this->input->post('username');
        $data_list['code']               = $this->input->post('code');
        $data_list['level']              = "pasien";
        $data_list['password']           = $this->encrypt->sha1($this->input->post('pass').$this->config->item('encryption_key'));
        $data_list['status_active']      = 1;
        $data_list['status_aproved']     = 0;
        $data_list['online']             = 0;
        $data_list['last_login']         = 0;
        $data_list['last_active']        = 0;
        $data_list['datereg']            = time();  

        $data_profile['username']        = $this->input->post('username');
        $data_profile['nama']            = $this->input->post('nama');
        $data_profile['code']            = $this->input->post('code');
        $data_profile['phone_number']    = $this->input->post('phone_number');
        $data_profile['email']           = $this->input->post('email');
        $data_profile['bpjs']            = $this->input->post('bpjs');
        $data_profile['jk']              = $this->input->post('jk');
        $data_profile['tgl_lahir']       = date("Y-m-d",strtotime($this->input->post('tgl_lahir')));
        $data_profile['alamat']          = $this->input->post('alamat');

        $this->db->where('username',$this->input->post('username'));
        $query = $this->db->get('app_users_list');

        if ($query->num_rows() > 0) {
            return 'false';
        }else{
            $this->db->update('app_users_list', $data_list);
            $this->db->update('app_users_profile', $data_profile);
                return 'true';  
        }
    }

    function delete_pasien($username){
        $this->db->delete('app_users_list', array('username' => $username));
        $this->db->delete('app_users_profile', array('username' => $username));
    } 
}






