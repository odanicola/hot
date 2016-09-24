<?php
class Antrian extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('hot/antrian_model');
	}

	function index(){
		$this->authentication->verify('hot','edit');
		$data['title_group'] 	= "Dashboard";
		$data['title_form']  	= "Data Antrian";
		$data['datapuskesmas']  = $this->antrian_model->get_pus("317204","code","cl_phc");
		
		if($this->session->userdata('level')=="pasien"){
			$v = "hot/antrian_pasien";
		}else{
			$v = "hot/antrian_non_pasien";
		}

		$this->session->set_userdata('filter_puskesmas','');
		$data['content']= $this->parser->parse($v,$data,true);

		$this->template->show($data,"home");
	}

	function filter_puskesmas(){
		if($_POST) {
			if($this->input->post('puskesmas') != '') {
				$this->session->set_userdata('filter_puskesmas',$this->input->post('puskesmas'));
			}
		}
	}

	function json_pasien(){

		$rows_all = $this->antrian_model->get_data_pasien();
		$rows = $this->antrian_model->get_data_pasien($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_kunjungan'	=> $act['id_kunjungan'],
				'urut'	    	=> substr($act['id_kunjungan'],-3),
				'tgl'	    	=> date("d-m-Y",strtotime($act['tgl'])),
				'waktu'	    	=> $act['waktu'],
				'username'	    => $act['username'],
				'jk'			=> $act['jk'],
				'nama'   	    => $act['nama'],
				'usia'   	    => $act['usia'],
				'bpjs'   	    => $act['bpjs'],
				'phone_number'	=> $act['phone_number'],
				'status_antri'	=> ucwords($act['status_antri']),
				'edit'		    => 1,
				'delete'	    => 1
			);
		}

		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows'      => $data
		);

		echo json_encode(array($json));
	}

	function json_non_pasien(){

		$kodepus=$this->session->userdata('filter_puskesmas');
		$new_kodepus = substr($kodepus,1);
		if ($this->session->userdata('filter_puskesmas')!='' && $this->session->userdata('filter_puskesmas')!='-') {
			$this->db->where('app_users_profile.code',$new_kodepus);
		}else{
			$this->db->order_by('kunjungan.id_kunjungan','asc');
		}

		$rows_all = $this->antrian_model->get_data_non_pasien();

		$kodepus=$this->session->userdata('filter_puskesmas');
		$new_kodepus = substr($kodepus,1);
		if ($this->session->userdata('filter_puskesmas')!='' && $this->session->userdata('filter_puskesmas')!='-') {
			$this->db->where('app_users_profile.code',$new_kodepus);
		}else{
			$this->db->order_by('kunjungan.id_kunjungan','asc');
		}

		$rows = $this->antrian_model->get_data_non_pasien($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_kunjungan'	=> $act->id_kunjungan,
				'urut'	    	=> substr($act->id_kunjungan,-3),
				'tgl'	    	=> date("d-m-Y",strtotime($act->tgl)),
				'waktu'	    	=> $act->waktu,
				'username'	    => $act->username,
				'jk'			=> $act->jk,
				'nama'   	    => $act->nama,
				'usia'   	    => $act->usia,
				'bpjs'   	    => $act->bpjs,
				'phone_number'	=> $act->phone_number,
				'status_antri'	=> ucwords($act->status_antri),
				'edit'		    => 1,
				'delete'	    => 1
			);
		}

		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows'      => $data
		);

		echo json_encode(array($json));
	}

}
