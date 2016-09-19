<?php
class Dokter_model extends CI_Model {

    var $tabel_dokter        = 'bpjs_data_dokter';

    function __construct() {
        parent::__construct();
    }

    function get_data_dokter($start=0,$limit=999999,$options=array()){
        $query = $this->db->get($this->tabel_dokter,$limit,$start);
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

    function update_dokter($code,$cl_phc){

        $data['value']            = $this->input->post('value');
        $data['status']           = $this->input->post('status');
        
        $this->db->where('code',$code);
        $this->db->where('cl_phc',$cl_phc);

        if($this->db->update('bpjs_data_dokter',$data)){
            return true; 
        }else{
            return mysql_error();
        }
    }

    function get_pus ($code,$condition,$table){
        $this->db->select("*");
        $this->db->like($condition,$code);
        $query = $this->db->get($table);
        return $query->result();
    }
}






