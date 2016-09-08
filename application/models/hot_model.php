<?php
class Hot_model extends CI_Model {

    var $tabel_pasien_profil = 'app_users_profile';
    var $tabel_pasien_akun   = 'app_users_list';
    var $tabel_dokter        = 'bpjs_data_dokter';
    var $t_puskesmas         = 'cl_phc';

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
        $query = $this->db->get($this->tabel_pasien_profil);
        return $query->result();
    }

    function get_data_dokter_where($code){
        $this->db->select("*");
        $this->db->where("code",$code);
        $query = $this->db->get($this->tabel_dokter);
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

    function inesert_pasien(){

        $data_list['username']           = $this->input->post('username');
        $data_list['code']               = substr($this->input->post('code'),1,10);
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
        $data_profile['code']            = substr($this->input->post('code'),1,10);
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
            $this->db->insert('app_users_list', $data_list);
            $this->db->insert('app_users_profile', $data_profile);
                return 'true';  
        }
    }

    function update_pasien($username){

        $data_list['code']               = substr($this->input->post('code'),1,10);
        $data_list['level']              = "pasien";
        $data_list['password']           = $this->encrypt->sha1($this->input->post('pass').$this->config->item('encryption_key'));
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
        
        $this->db->where('username',$username);
        if($this->db->update('app_users_profile',$data_profile)){
            return true; 
        }else{
            return mysql_error();
        }
    }

    function update_pasien_akun($username){

        $data_list['password'] = $this->encrypt->sha1($this->input->post('pass').$this->config->item('encryption_key'));
        
        $this->db->where('username',$username);
        if($this->db->update('app_users_list', $data_list)){
            return true; 
        }else{
            return mysql_error();
        }
    }

    function update_dokter($code){

        $data['value']            = $this->input->post('value');
        $data['status']           = $this->input->post('status');
        
        $this->db->where('code',$code);
        if($this->db->update('bpjs_data_dokter',$data)){
            return true; 
        }else{
            return mysql_error();
        }
    }

    function delete_pasien($username){
        $this->db->delete('app_users_list', array('username' => $username));
        $this->db->delete('app_users_profile', array('username' => $username));
    } 
}






