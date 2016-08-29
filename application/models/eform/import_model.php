<?php
class Import_model extends CI_Model {

    var $tabel    = 'import';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
        $this->load->model('bpjs');
    }
    function get_data_puskesmas()
    {   
        $this->db->order_by('value','asc');
        $query = $this->db->get('cl_phc'); 
        return $query->result();    
    }
    function lengkapdata($bpjs){
        $this->db->where('bpjs',$bpjs);
        $query = $this->db->get('data_keluarga_anggota');
        if ($query->num_rows() > 0) {
            $data = $query->row_array();
        }
        $query->free_result();
        return $data;
    }
    function add_pesetabpjsterdaftar($nik,$no_kartu,$id_import,$username,$id_data_keluarga,$no_anggota){
            $this->db->where('id_import',$id_import);
            $this->db->where('username',$username);
            $this->db->where('id_data_keluarga',$id_data_keluarga);
            $this->db->where('no_anggota',$no_anggota);
            $values = array(
                'keterangan'            => $no_kartu,
                'status'                => '1'
            );
            $this->db->update('import',$values);
            return true;
    }
    function get_datawhere ($code,$condition,$table){
        $this->db->select("*");
        $this->db->like($condition,$code);
        return $this->db->get($table)->result();
    }
    function get_datawhereasli ($code,$condition,$table){
        $this->db->select("*");
        $this->db->where($condition,$code);
        return $this->db->get($table)->result();
    }
    function get_nama($kolom_sl,$tabel,$kolom_wh,$kond){
       $this->db->where($kolom_wh,$kond);
        $this->db->select($kolom_sl);
        $query = $this->db->get($tabel)->result();
        foreach ($query as $key) {
            return $key->$kolom_sl;
        }
    }
    function get_data_row($id_data_keluarga='',$id_import='',$username='',$no_anggota=0){
        $this->db->select("*,(select count(*) from import a where a.id_import = import.id_import and a.username = import.username) as jumlah",false);
        $this->db->where('id_data_keluarga',$id_data_keluarga);
        $this->db->where('id_import',$id_import);
        $this->db->where('username',$username);
        $this->db->where('no_anggota',$no_anggota);
        $query = $this->db->get('import');   
        if ($query->num_rows() > 0) {
            $data = $query->row_array();
        }
        $query->free_result();
        return $data;
    }
    function get_data($start=0,$limit=999999,$options=array())
    {
        $this->db->select("COUNT(*) AS jumlah,import.*",false);
        $this->db->group_by('id_import');
        $this->db->join('data_keluarga','data_keluarga.id_data_keluarga=import.id_data_keluarga','left');
        $query = $this->db->get('import',$limit,$start);
        return $query->result();
    }

 	function get_data_detail($id_data_keluarga='',$id_import,$username,$start=0,$limit=999999,$options=array()){
		 $this->db->select("$this->tabel.*,data_keluarga_anggota.*,jeniskelamin.value as jeniskelamin,(year(curdate())-year(data_keluarga_anggota.tgl_lahir)) as usia",false);
        $this->db->order_by('data_keluarga.tanggal_pengisian','desc');
        // $this->db->where('import.id_data_keluarga',$id_data_keluarga);
        $this->db->where('import.id_import',$id_import);
        $this->db->where('import.username',$username);
        $this->db->join('data_keluarga','data_keluarga.id_data_keluarga=import.id_data_keluarga','left');
        $this->db->join('data_keluarga_anggota','data_keluarga_anggota.id_data_keluarga=import.id_data_keluarga and data_keluarga_anggota.no_anggota = import.no_anggota','left');
         $this->db->join("mst_keluarga_pilihan jeniskelamin","data_keluarga_anggota.id_pilihan_kelamin = jeniskelamin.id_pilihan and jeniskelamin.tipe ='jk'",'left');
        $query = $this->db->get($this->tabel,$limit,$start);
        return $query->result();
	}
    
}