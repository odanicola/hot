<?php
class Antrian_model extends CI_Model {

    var $tabel_dokter        = 'bpjs_data_dokter';

    function __construct() {
        parent::__construct();
    }

    function get_data_pasien($start=0,$limit=999999,$options=array()){
        $this->db->where('kunjungan.username',$this->session->userdata('username'));
        $this->db->select("id_kunjungan,app_users_profile.username,app_users_profile.nama,app_users_profile.jk,app_users_profile.phone_number, app_users_profile.bpjs,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),tgl_lahir)), '%Y')+0 AS usia,kunjungan.status_antri,kunjungan.tgl,kunjungan.waktu",false);
        $this->db->join('app_users_profile','kunjungan.username = app_users_profile.username AND kunjungan.code = app_users_profile.code');
        $this->db->where('status_antri','antri');
        $this->db->where('tgl','CURDATE()',false);
        $this->db->order_by('id_kunjungan','asc');
        $querylogin = $this->db->get('kunjungan');
        $keylogin   = $querylogin->row_array();
        
        $this->db->select("id_kunjungan,app_users_profile.username,app_users_profile.nama,app_users_profile.jk,app_users_profile.phone_number, app_users_profile.bpjs,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),tgl_lahir)), '%Y')+0 AS usia,kunjungan.status_antri,kunjungan.tgl,kunjungan.waktu",false);
        $this->db->join('app_users_profile','kunjungan.username = app_users_profile.username AND kunjungan.code = app_users_profile.code');
        $this->db->where('status_antri','antri');
        $this->db->where('tgl','CURDATE()',false);
        $this->db->order_by('id_kunjungan','asc');
        if ((int)substr($keylogin['id_kunjungan'],-3) < 11) {
            $query = $this->db->get('kunjungan',10);
        } else {
            $query = $this->db->get('kunjungan',9);
        }
        // print_r($query->result_array());
        // die();
        $i=0;
        foreach ($query->result_array() as $key) {
            $data[$i] = array(
                'id_kunjungan'  => $key['id_kunjungan'],
                'urut'          => $key['id_kunjungan'],
                'tgl'           => $key['tgl'],
                'waktu'         => $key['waktu'],
                'username'      => $key['username'],
                'jk'            => $key['jk'],
                'nama'          => $key['nama'],
                'usia'          => $key['usia'],
                'bpjs'          => $key['bpjs'],
                'phone_number'  => $key['phone_number'],
                'status_antri'  => $key['status_antri'],
            );
            $i++;
        };
        
        if ((int)substr($keylogin['id_kunjungan'],-3) < 11) {
        } else {
            $data[$i] = array(
                'id_kunjungan'  => $keylogin['id_kunjungan'],
                'urut'          => $keylogin['id_kunjungan'],
                'tgl'           => $keylogin['tgl'],
                'waktu'         => $keylogin['waktu'],
                'username'      => $keylogin['username'],
                'jk'            => $keylogin['jk'],
                'nama'          => $keylogin['nama'],
                'usia'          => $keylogin['usia'],
                'bpjs'          => $keylogin['bpjs'],
                'phone_number'  => $keylogin['phone_number'],
                'status_antri'  => $keylogin['status_antri'],
            );
        }

        return $data;
    }

    function get_data_non_pasien($start=0,$limit=999999,$options=array()){
        $this->db->select("id_kunjungan,app_users_profile.username,app_users_profile.nama,app_users_profile.jk,app_users_profile.phone_number, app_users_profile.bpjs,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),tgl_lahir)), '%Y')+0 AS usia,kunjungan.status_antri,kunjungan.tgl,kunjungan.waktu",false);
        $this->db->join('app_users_profile','kunjungan.username = app_users_profile.username AND kunjungan.code = app_users_profile.code');
        $this->db->where('status_antri','antri');
        $this->db->where('tgl','CURDATE()',false);
        if($this->session->userdata('level')!='pasien'){
            $this->db->order_by('id_kunjungan','asc');
        }else{
            $this->db->order_by('id_kunjungan','asc');
        }
        $query = $this->db->get('kunjungan',$limit,$start);
        return $query->result();
    }

    function get_pus ($code,$condition,$table){
        $this->db->select("*");
        $this->db->like($condition,$code);
        $query = $this->db->get($table);
        return $query->result();
    }

}






