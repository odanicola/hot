<?php
class Bpjs_api extends CI_Controller {

    public function __construct(){
		parent::__construct();

		$this->load->model('bpjs');
	}

	function cekkonek(){
		$data = $this->bpjs->get_data_bpjs();
		
		if (isset($data['code']) && isset($data['server']) && isset($data['username']) && isset($data['password']) && isset($data['consid']) && isset($data['secretkey'])) {
			die('ready');
		}else{
			die('off');
		}
	}
	function simpanbpjs($kode=0){
		$data = $this->bpjs->inserbpjs($kode);
		die($data);
	}
	
	function hapusbpjs($kode=0){
		$data = $this->bpjs->deletebpjs($kode);
		die($data);
	}

	function db_search($by = 'nik',$no){
      	$data = $this->bpjs->db_search($by,$no);
      	echo $data;
	}

	function bpjs_search($by = 'nik',$no){
      	$data = $this->bpjs->bpjs_search($by,$no);
      	echo json_encode($data);
	}
	
}