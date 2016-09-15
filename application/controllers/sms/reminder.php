<?php
class Reminder extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('sms/reminder_model');

	}

	function index(){
		$this->authentication->verify('hot','edit');
		$data['title_group'] 	= "SMS Gateway";
		$data['title_form']  	= "Reminder";
		$data['content'] 	 	= $this->parser->parse("sms/reminder/show",$data,true);

		$this->template->show($data,"home");
	}

	function filter_puskesmas(){
		if($_POST) {
			if($this->input->post('puskesmas') != '') {
				$this->session->set_userdata('filter_puskesmas',$this->input->post('puskesmas'));
			}
		}
	}

	function json(){
		$tgl = $this->session->userdata('filter_tahun')."-".($this->session->userdata('filter_bulan')<10 ? "0":"").$this->session->userdata('filter_bulan')."-".($this->session->userdata('filter_tanggal')<10 ? "0":"").$this->session->userdata('filter_tanggal');

		if($this->session->userdata('level')=="pasien"){
			$this->db->where('kunjungan.username',$this->session->userdata('username'));	
		}else{
			$this->db->where('kunjungan.code',$this->session->userdata('puskesmas'));	
		} 
		// $this->db->where('kunjungan.tgl',$tgl);
		
		$rows_all = $this->reminder_model->get_data_pasien();

		if($this->session->userdata('level')=="pasien"){
			$this->db->where('kunjungan.username',$this->session->userdata('username'));	
		}else{
			$this->db->where('kunjungan.code',$this->session->userdata('puskesmas'));	
		} 
		// $this->db->where('kunjungan.tgl',$tgl);

		$rows = $this->reminder_model->get_data_pasien($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_kunjungan'	=> $act->id_kunjungan,
				'urut'	    	=> substr($act->id_kunjungan,-3),
				'username'	    => $act->username,
				'tgl'			=> $act->tgl,
				'jk'			=> $act->jk,
				'nama'   	    => $act->nama,
				'usia'   	    => $act->usia,
				'bpjs'   	    => $act->bpjs,
				'phone_number'	=> $act->phone_number,
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

	function edit($code=0){
		$this->authentication->verify('hot','edit');

        $this->form_validation->set_rules('value','Nama', 'trim');
        $this->form_validation->set_rules('status','Status', 'trim');

		if($this->form_validation->run()== FALSE){
			$data 					= $this->hot_model->get_data_dokter_where($code); 
			$data['title_group']    = "Dashboard";
			$data['title_form']     = "Ubah Data Dokter";
			$data['action']		    = "edit";
			$data['code']			= $code;
			$data['content'] 		= $this->parser->parse("hot/data_dokter_add",$data,true);

		}elseif($this->hot_model->update_dokter($code)==1){
				$this->session->set_flashdata('alert_form', 'Save data successful...');
				redirect(base_url()."hot/dokter");
				die("OK");
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."hot/dokter/edit");
			$data['alert_form'] = 'Save data failed...';
			die("NOTOK");
		}
		$this->template->show($data,"home");
	}
}
