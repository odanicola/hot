<?php
class Pbk_model extends CI_Model {

    var $tabel    = 'app_users_profile';
	var $lang	  = 'ina';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }

    function get_data($start=0,$limit=999999,$options=array()){
	    $this->db->select("app_users_profile.username,app_users_profile.nama,app_users_profile.phone_number,sms_grup.id_grup,sms_grup.nama AS nama_group ");
	    $this->db->join('sms_grup', 'sms_grup.id_grup = app_users_profile.id_grup', 'left'); 
	    $this->db->join('app_users_list', 'app_users_list.username = app_users_profile.username', 'left'); 
		$this->db->where("app_users_list.level",'pasien');
	    $query = $this->db->get($this->tabel,$limit,$start);
    	return $query->result();
    }

 	function get_data_row($username){
		$data = array();
	    $this->db->select("app_users_profile.username,app_users_profile.nama,app_users_profile.phone_number,sms_grup.id_grup,sms_grup.nama AS nama_group ");
	    $this->db->join('sms_grup', 'sms_grup.id_grup = app_users_profile.id_grup', 'left'); 
	    $this->db->join('app_users_list', 'app_users_list.username = app_users_profile.username', 'left'); 
		$this->db->where("app_users_profile.username",$username);
		$query = $this->db->get($this->tabel)->row_array();

		if(!empty($query)){
			return $query;
		}else{
			return $data;
		}

		$query->free_result();    
	}

    function get_grupoption($limit=999999,$start=0){
    	$this->db->order_by('nama','asc');
        $query = $this->db->get('sms_grup',$limit,$start);
        return $query->result();
    }

	public function getSelectedData($tabel,$data)
    {
        return $this->db->get_where($tabel, array('phone_number'=>$data));
    }

    function insert_entry()
    {
		$data['phone_number']  = $this->input->post('phone_number');
		$data['nama']		   = $this->input->post('nama');
		$data['id_grup']	   = $this->input->post('id_grup');

		if($this->getSelectedData($this->tabel, $data['phone_number'])->num_rows() > 0) {
			return 0;
		}else{
			if($this->db->insert($this->tabel, $data)){
			 return 1;
			}else{
				return mysql_error();
			}
		}
    }

    function update_entry($username)
    {
		$data['nama']			= $this->input->post('nama');
		$data['id_grup']		= $this->input->post('id_grup');
		$data['phone_number']	= $this->input->post('phone_number');

		$this->db->where('username',$username);
		if($this->db->update($this->tabel, $data)){
			return true;
		}else{
			return mysql_error();
		}
    }

	function delete_entry($username){
		
		$this->db->select("app_users_profile.username,app_users_profile.nama,app_users_profile.phone_number,sms_grup.id_grup,sms_grup.nama AS nama_group ");
	    $this->db->join('sms_grup', 'sms_grup.id_grup = app_users_profile.id_grup', 'left'); 
		$this->db->where("username",$username);

		return $this->db->delete($this->tabel);
	}
}