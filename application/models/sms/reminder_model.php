<?php
class Reminder_model extends CI_Model {

    var $tabel    = 'sms_opini';
	var $lang	  = 'ina';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }

    function get_data_pasien($start=0,$limit=999999,$options=array()){
        $this->db->select("id_kunjungan,tgl,app_users_profile.username,app_users_profile.nama,app_users_profile.jk,app_users_profile.phone_number, app_users_profile.bpjs,DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),tgl_lahir)), '%Y')+0 AS usia",false);
        $this->db->join('app_users_profile','kunjungan.username = app_users_profile.username AND kunjungan.code = app_users_profile.code');
        $this->db->where('status_antri','antri');
        $this->db->where('tgl','CURDATE()',false);
        $this->db->order_by('id_kunjungan','asc');
        $query = $this->db->get('kunjungan',$limit,$start);
        return $query->result();
    }

    function get_data($start=0,$limit=999999,$options=array())
    {
	    $query = $this->db->get($this->tabel,$limit,$start);
    	return $query->result();
	
    }

 	function get_data_row($id){
		$data = array();
		$this->db->where("nomor",$id);
		$query = $this->db->get($this->tabel)->row_array();

		if(!empty($query)){
			return $query;
		}else{
			return $data;
		}

		$query->free_result();    
	}

 	function get_data_ID($id){
		$data = array();
		$this->db->where("id_opini",$id);
		$query = $this->db->get($this->tabel)->row_array();

		if(!empty($query)){
			if($query['status']=="baru"){
				$update = array("status"=>"baca");
				$this->db->where("id_opini",$id);
				$this->db->update($this->tabel,$update);
			}

			return $query;
		}else{
			return $data;
		}

		$query->free_result();    
	}

	function move($id)
	{
		$data = array(
			'id_sms_tipe'	=> $this->input->post('id_sms_tipe'),
		);

		$this->db->where('id_opini',$id);
		if($this->db->update('sms_opini',$data)){
			return true;
		}else{
			return false;
		}
	}

	public function getSelectedData($tabel,$data)
    {
        return $this->db->get_where($tabel, array('nomor'=>$data));
    }

	function delete_entry($id)
	{
		$this->db->where('id_opini',$id);

		return $this->db->delete($this->tabel);
	}

    function get_tipe($jenis='terima')
    {
		$this->db->where('jenis',$jenis);

	    $query = $this->db->get('sms_tipe');
    	return $query->result();
	
    }}