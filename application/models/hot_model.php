<?php
class Hot_model extends CI_Model {

    var $tabel_pasien_profil = 'app_users_profile';
    var $tabel_pasien_akun   = 'app_users_list';
    var $tabel_dokter        = 'cl_sdm';
    var $tabel_obat          = 'cl_drug';
    var $t_puskesmas         = 'cl_phc';

    function __construct() {
        parent::__construct();
    }

    function get_data_dokter($start=0,$limit=999999,$options=array()){
        $query = $this->db->get($this->tabel_dokter,$limit,$start);
        return $query->result();
    }

    function get_data_obat($start=0,$limit=999999,$options=array()){
        $query = $this->db->get($this->tabel_obat,$limit,$start);
        return $query->result();
    }

    function get_data_pasien($start=0,$limit=999999,$options=array()){
        $this->db->select("app_users_profile.username,app_users_profile.nama,app_users_profile.jk,app_users_profile.phone_number, app_users_profile.bpjs,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),tgl_lahir)), '%Y')+0 AS usia",false);
        $this->db->join('app_users_list','app_users_profile.username = app_users_list.username','left');
        $this->db->where("level","pasien");
        $query = $this->db->get($this->tabel_pasien_profil,$limit,$start);
        return $query->result();
    }

    function get_data_dokter_where($code,$cl_phc){
        $this->db->select("*");
        $this->db->where("code",$code);
        $this->db->where("cl_phc",$cl_phc);
        $query = $this->db->get($this->tabel_dokter);
        if ($query->num_rows()>0) {
            $data = $query->row_array();
        }
        $query->free_result();
        return $data;
    }

    function get_data_obat_where($code){
        $this->db->select("*");
        $this->db->where("code",$code);
        $query = $this->db->get($this->tabel_obat);
        if ($query->num_rows()>0) {
            $data = $query->row_array();
        }
        $query->free_result();
        return $data;
    }

    function get_profil_pasien_where($username){
        $this->db->select("*");
        $this->db->where("username",$username);
        $query = $this->db->get($this->tabel_pasien_profil);
        if ($query->num_rows()>0) {
            $data = $query->row_array();
        }
        $query->free_result();
        return $data;
    }

    function get_akun_pasien_where($username){
        $this->db->select("*");
        $this->db->where("username",$username);
        $query = $this->db->get($this->tabel_pasien_akun);
        if ($query->num_rows()>0) {
            $data = $query->row_array();
        }
        $query->free_result();
        return $data;
    }

    function get_pasien_where($username){
        $this->db->select("app_users_profile.username,app_users_profile.code,app_users_profile.tgl_lahir,app_users_list.password,app_users_profile.alamat,app_users_profile.email,app_users_profile.nama,app_users_profile.jk,app_users_profile.phone_number, app_users_profile.bpjs,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),tgl_lahir)), '%Y')+0 AS usia",false);
        $this->db->join('app_users_list','app_users_profile.username = app_users_list.username','left');
        $this->db->where("level","pasien");
        $this->db->where("app_users_profile.username",$username);
        $query = $this->db->get($this->tabel_pasien_profil);
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

    function insert_pasien(){

        $data_list['username']           = $this->input->post('username');
        $data_list['code']               = substr($this->input->post('code'),1,10);
        $data_list['level']              = "pasien";
        $data_list['password']           = $this->_prep_password($this->input->post('password'));
        $data_list['status_active']      = 1;
        $data_list['status_aproved']     = 0;
        $data_list['online']             = 0;
        $data_list['last_login']         = 0;
        $data_list['last_active']        = 0;
        $data_list['datereg']            = time();  

        $data_profile['username']        = $this->input->post('username');
        $data_profile['nama']            = $this->input->post('nama');
        $data_profile['code']            = substr($this->input->post('code'),1,10);
        $data_profile['phone_number']    = $this->input->post('phone_number');
        $data_profile['email']           = $this->input->post('email');
        $data_profile['bpjs']            = $this->input->post('bpjs');
        $data_profile['jk']              = $this->input->post('jk');
        $data_profile['tgl_lahir']       = date("Y-m-d",strtotime($this->input->post('tgl_lahir')));
        $data_profile['alamat']          = $this->input->post('alamat');
        $data_profile['tb']              = $this->input->post('tb');
        $data_profile['bb']              = $this->input->post('bb');

        $this->db->where('username',$this->input->post('username'));
        $query = $this->db->get('app_users_list');

        if ($query->num_rows() > 0) {
            return 'false';
        }else{
            $this->db->insert('app_users_list', $data_list);
            $this->db->insert('app_users_profile', $data_profile);
                return 'true';  
        }
    }

    function update_pasien($username){

        $data_list['code']               = substr($this->input->post('code'),1,10);
        $data_list['level']              = "pasien";
        $data_list['password']           = $this->_prep_password($this->input->post('password'));
        $data_list['status_active']      = 1;
        $data_list['status_aproved']     = 0;
        $data_list['online']             = 0;
        $data_list['last_login']         = 0;
        $data_list['last_active']        = 0;
        $data_list['datereg']            = time();  

        $data_profile['nama']            = $this->input->post('nama');
        $data_profile['code']            = substr($this->input->post('code'),1,10);
        $data_profile['phone_number']    = $this->input->post('phone_number');
        $data_profile['email']           = $this->input->post('email');
        $data_profile['bpjs']            = $this->input->post('bpjs');
        $data_profile['jk']              = $this->input->post('jk');
        $data_profile['tgl_lahir']       = date("Y-m-d",strtotime($this->input->post('tgl_lahir')));
        $data_profile['alamat']          = $this->input->post('alamat');
        
        $this->db->where('username',$username);
        $query = $this->db->get('app_users_list');

        if($this->db->update('app_users_list', $data_list, array('username'=>$username))&&$this->db->update('app_users_profile', $data_profile, array('username'=>$username))){
            return true; 
        }else{
            return mysql_error();
        }
    }

    function update_pasien_profil($username){
        $data_profile['nama']            = $this->input->post('nama');
        $data_profile['code']            = substr($this->input->post('code'),1,10);
        $data_profile['phone_number']    = $this->input->post('phone_number');
        $data_profile['email']           = $this->input->post('email');
        $data_profile['bpjs']            = $this->input->post('bpjs');
        $data_profile['jk']              = $this->input->post('jk');
        $data_profile['tgl_lahir']       = date("Y-m-d",strtotime($this->input->post('tgl_lahir')));
        $data_profile['alamat']          = $this->input->post('alamat');
        $data_profile['tb']              = $this->input->post('tb');
        $data_profile['bb']              = $this->input->post('bb');

        $data_list['code']               = substr($this->input->post('code'),1,10);

        $this->db->get('app_users_profile');
        $this->db->where('username',$username);
        $this->db->update('app_users_profile',$data_profile);

        $this->db->get('app_users_list');
        $this->db->where('username',$username);
        $this->db->update('app_users_list',$data_list);
        return 'true';  
    }

    function update_pasien_akun($username){
        $data_list['password'] = $this->_prep_password($this->input->post('password'));
        
        $this->db->where('username',$username);
        if($this->db->update('app_users_list', $data_list)){
            return true; 
        }else{
            return mysql_error();
        }
    }

    function update_dokter($code,$cl_phc){

        $data['value']            = $this->input->post('value');
        $data['status']           = $this->input->post('status');
        
        $this->db->where('code',$code);
        $this->db->where('cl_phc',$cl_phc);

        if($this->db->update('cl_sdm',$data)){
            return true; 
        }else{
            return mysql_error();
        }
    }

    function update_obat($code){

        $data['value']            = $this->input->post('value');
        $data['status']           = $this->input->post('status');
        $data['sediaan']          = $this->input->post('sediaan');
        
        $this->db->where('code',$code);
        if($this->db->update('cl_drug',$data)){
            return true; 
        }else{
            return mysql_error();
        }
    }

    function delete_pasien($username){
        $this->db->delete('app_users_list', array('username' => $username));
        $this->db->delete('app_users_profile', array('username' => $username));
    } 

    function check_email($str){
        $uid = ($this->session->userdata('username')!="") ? $this->session->userdata('username') : "";
        $this->db->where('email',$str);
        $this->db->where('username <>', $uid);
        $query = $this->db->get('app_users_profile');
        return $query->num_rows();
    }

    function _prep_password($password){
        return $this->encrypt->sha1($password.$this->config->item('encryption_key'));
    }
}






