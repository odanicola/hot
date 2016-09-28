<?php
class Kunjungan_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_data_pasien($start=0,$limit=999999,$options=array()){
        $this->db->select("id_kunjungan,app_users_profile.username,app_users_profile.nama,app_users_profile.jk,app_users_profile.phone_number, app_users_profile.bpjs,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),tgl_lahir)), '%Y')+0 AS usia,kunjungan.status_antri,kunjungan.tgl,kunjungan.waktu,kunjungan.systolic,kunjungan.diastolic,kunjungan.reg_id,app_users_profile.cl_pid",false);
        $this->db->join('app_users_profile','kunjungan.username = app_users_profile.username AND kunjungan.code = app_users_profile.code');
        if($this->session->userdata('level')!='pasien'){
            $this->db->order_by('id_kunjungan','asc');
        }else{
            $this->db->order_by('id_kunjungan','desc');
        }
        $query = $this->db->get('kunjungan',$limit,$start);
        return $query->result();
    }

    function get_filter_pasien(){
        $filter = $this->input->post('nama');

        $this->db->where("level","pasien");
        $this->db->where("(nama LIKE '%".$filter."%' OR bpjs LIKE '%".$filter."%' OR app_users_profile.username LIKE '%".$filter."%')");

        $this->db->select("CONCAT(app_users_profile.nama,', ',app_users_profile.jk,' / ',DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),app_users_profile.tgl_lahir)), '%Y')+0,' Tahun <br>',app_users_profile.username,'<br>',bpjs) as nama,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),tgl_lahir)), '%Y')+0 AS usia, app_users_profile.nama as name, app_users_profile.username, app_users_profile.jk,app_users_profile.bpjs",false);
        $this->db->join('app_users_list','app_users_profile.username = app_users_list.username','left');
        $query = $this->db->get("app_users_profile",10,0);
        return $query->result();
    }

    function get_autocomplete_pasien(){
        $filter = $this->input->post('nama');

        $this->db->where("app_users_profile.code",$this->session->userdata('puskesmas'));
        $this->db->where("level","pasien");
        $this->db->where("(nama LIKE '%".$filter."%' OR bpjs LIKE '%".$filter."%' OR app_users_profile.username LIKE '%".$filter."%')");

        $this->db->select("CONCAT(app_users_profile.nama,', ',app_users_profile.jk,' / ',DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),app_users_profile.tgl_lahir)), '%Y')+0,' Tahun <br>',app_users_profile.username,'<br>',bpjs) as nama,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),tgl_lahir)), '%Y')+0 AS usia, app_users_profile.nama as name, app_users_profile.username, app_users_profile.jk,app_users_profile.bpjs",false);
        $this->db->join('app_users_list','app_users_profile.username = app_users_list.username','left');
        $query = $this->db->get("app_users_profile",10,0);
        return $query->result();
    }

    function get_pemeriksaan($id_kunjungan){
        $this->db->select("kunjungan.*,app_users_profile.nama,app_users_profile.jk,app_users_profile.phone_number,app_users_profile.tb,app_users_profile.bb,app_users_profile.bpjs,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),tgl_lahir)), '%Y')+0 AS usia",false);
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

    function get_sebelumnya($nik="",$tgl_kunjungan){
        $data = array();
        $this->db->where("username",$nik);
        $this->db->where("tgl <",$tgl_kunjungan);
        $this->db->where("status_antri","selesai");
        $query = $this->db->get("kunjungan");

        return $query->num_rows();
    }

    function insert(){

        $this->db->select('MAX(id_kunjungan) as id');
        $this->db->where('code',$this->input->post('code'));
        $this->db->where('tgl',date("Y-m-d",strtotime($this->input->post('tgl'))));
        $id = $this->db->get('kunjungan')->row();
        if(!empty($id->id)){
            $tmp = intval(substr($id->id, -3))+1;

            $number = str_repeat("0",3-strlen($tmp)).$tmp;
        }else{
            $number="001";
        }

        $data['id_kunjungan']    = $this->input->post('code').date("Ymd",strtotime($this->input->post('tgl'))).$number;
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

    function simpan($id_kunjungan){

        $data['waktu']          = $this->input->post('waktu');
        $data['tb']             = $this->input->post('tb');
        $data['bb']             = $this->input->post('bb');
        $data['bmi']            = $this->input->post('bmi');
        $data['kategori']       = $this->input->post('kategori');
        $data['systolic']       = $this->input->post('systolic');
        $data['diastolic']      = $this->input->post('diastolic');
        $data['pulse']          = $this->input->post('pulse');
        $data['gds']            = $this->input->post('gds');
        $data['gdp']            = $this->input->post('gdp');
        $data['gdpp']           = $this->input->post('gdpp');
        $data['kolesterol']     = $this->input->post('kolesterol');
        $data['asamurat']       = $this->input->post('asamurat');
        $data['is_diabetic']    = $this->input->post('is_diabetic');
        $data['is_ckd']         = $this->input->post('is_ckd');
        $data['is_black']       = $this->input->post('is_black');
        $data['status_antri']   = $this->input->post('status_antri');
        $data['username_op']    = $this->input->post('username_op');


        $this->db->where('id_kunjungan',$id_kunjungan);
        if ($this->db->update('kunjungan',$data)) {
            return 'true';  
        }else{
            return 'false';
        }
    }

    function delete($id_kunjungan){
        return $this->db->delete('kunjungan', array('id_kunjungan' => $id_kunjungan));
    } 
}






