<?php
class Obat_model extends CI_Model {

    var $tabel_obat          = 'cl_drug';

    function __construct() {
        parent::__construct();
    }

    function get_data_obat($start=0,$limit=999999,$options=array()){
        $query = $this->db->get($this->tabel_obat,$limit,$start);
        return $query->result();
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

    function update_obat($code){

        $data['value']            = $this->input->post('value');
        $data['status']           = $this->input->post('status');
        $data['sediaan']          = $this->input->post('sediaan');
        
        $this->db->where('code',$code);
        if($this->db->update('bpjs_data_obat',$data)){
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






