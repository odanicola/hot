<?php
class Pasien_model extends CI_Model {

    var $tabel_pasien_profil = 'app_users_profile';
    var $tabel_pasien_akun   = 'app_users_list';

    function __construct() {
        parent::__construct();
    }

    function get_data_pasien($start=0,$limit=999999,$options=array()){
        $puskesmas = $this->session->userdata('puskesmas');

        $this->db->select("app_users_profile.username,app_users_profile.nama,app_users_profile.jk,app_users_profile.phone_number,app_users_profile.cl_pid, app_users_profile.bpjs,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),tgl_lahir)), '%Y')+0 AS usia",false);
        $this->db->join('app_users_list','app_users_profile.username = app_users_list.username','left');
        $this->db->where("level","pasien");
        $this->db->where("app_users_profile.code",$puskesmas);
        $query = $this->db->get($this->tabel_pasien_profil,$limit,$start);
        return $query->result();
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
        $data_profile['cl_pid']          = $this->input->post('cl_pid');
        $data_profile['kode_provider']   = $this->input->post('kode_provider');
        $data_profile['nama_provider']   = $this->input->post('nama_provider');

        $this->db->where('username',$this->input->post('username'));
        $query = $this->db->get('app_users_list');

        if ($query->num_rows() > 0) {
            // echo "NIK pasien sudah pernah terdaftar";
            return 'false'.'|NIK pasien pernah terdaftar<br>';
        }else{
            if($this->db->insert('app_users_list', $data_list)){
                $this->db->insert('app_users_profile', $data_profile);
                return 'true'.'|Data berhasil disimpan<br>';  
            }else{
                echo mysql_error();
            }
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
        $data_profile['cl_pid']          = $this->input->post('cl_pid');
        $data_profile['phone_number']    = $this->input->post('phone_number');
        $data_profile['email']           = $this->input->post('email');
        $data_profile['bpjs']            = $this->input->post('bpjs');
        $data_profile['jk']              = $this->input->post('jk');
        $data_profile['tgl_lahir']       = date("Y-m-d",strtotime($this->input->post('tgl_lahir')));
        $data_profile['alamat']          = $this->input->post('alamat');
        $data_profile['tb']              = $this->input->post('tb');
        $data_profile['bb']              = $this->input->post('bb');
        $data_profile['kode_provider']   = $this->input->post('kode_provider');
        $data_profile['nama_provider']   = $this->input->post('nama_provider');

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

    function delete_pasien($username){
        $this->db->delete('app_users_list', array('username' => $username));
        $this->db->delete('app_users_profile', array('username' => $username));
        $this->db->delete('kunjungan', array('username' => $username));
    } 

    function get_pus ($code,$condition,$table){
        $this->db->select("*");
        $this->db->like($condition,$code);
        $query = $this->db->get($table);
        return $query->result();
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






