<?php
class Reminder extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('hot/reminder_model');

	}

	function index(){
		$this->authentication->verify('hot','edit');
		$data['title_group'] 	      = "SMS Gateway";
		$data['title_form']  		  = "Reminder";
		$data['bulan']				  = array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
		$data['filter_jenis_kelamin'] = $this->session->userdata('filter_jenis_kelamin');
		
		$this->session->set_userdata('filter_bulan','');
		$this->session->set_userdata('filter_tahun','');
		$this->session->userdata('filter_jenis_kelamin')=="" ? $this->session->set_userdata('filter_jenis_kelamin','L'): '';
		
		if($this->session->userdata('level')=="pasien"){
			$v = "hot/reminder_pasien";
		}else{
			$v = "hot/reminder_petugas";
		}
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

	function filter_jenis_kelamin(){
		if($_POST) {
			if($this->input->post('filter_jenis_kelamin') != '') {
				$this->session->set_userdata('filter_jenis_kelamin',$this->input->post('filter_jenis_kelamin'));
			}
		}
	}

	function filter_tahun(){
		if($_POST) {
			if($this->input->post('filter_tahun') != '') {
				$this->session->set_userdata('filter_tahun',$this->input->post('filter_tahun'));
			}
		}
	}

	function filter_bulan(){
		if($_POST) {
			if($this->input->post('filter_bulan') != '') {
				$this->session->set_userdata('filter_bulan',$this->input->post('filter_bulan'));
			}
		}
	}

	function json_petugas(){

		if($this->session->userdata('level')=="pasien"){
			$this->db->where('kunjungan.username',$this->session->userdata('username'));	
		}else{
			$this->db->where('kunjungan.code',$this->session->userdata('puskesmas'));	
		} 

		if($this->session->userdata('filter_jenis_kelamin')!='' && $this->session->userdata('filter_jenis_kelamin')!='-'){
			$this->db->where('app_users_profile.jk',$this->session->userdata('filter_jenis_kelamin'));
		}

		if($this->session->userdata('filter_bulan')!=''){
			if($this->session->userdata('filter_bulan')=="all"){

			}else{
				$this->db->where("MONTH(tgl)",$this->session->userdata('filter_bulan'));
			}
		}else{
			$this->db->where("MONTH(tgl)",date("m"));
		}

		if($this->session->userdata('filter_tahun')!=''){
			if($this->session->userdata('filter_tahun')=="all"){

			}else{
				$this->db->where("YEAR(tgl)",$this->session->userdata('filter_tahun'));
			}
		}else{
			$this->db->where("YEAR(tgl)",date("Y"));
		}
		
		$rows_all = $this->reminder_model->get_data_petugas();

		if($this->session->userdata('level')=="pasien"){
			$this->db->where('kunjungan.username',$this->session->userdata('username'));	
		}else{
			$this->db->where('kunjungan.code',$this->session->userdata('puskesmas'));	
		} 

		if($this->session->userdata('filter_jenis_kelamin')!='' && $this->session->userdata('filter_jenis_kelamin')!='-'){
			$this->db->where('app_users_profile.jk',$this->session->userdata('filter_jenis_kelamin'));
		}

		if($this->session->userdata('filter_bulan')!=''){
			if($this->session->userdata('filter_bulan')=="all"){

			}else{
				$this->db->where("MONTH(kontrol_tgl)",$this->session->userdata('filter_bulan'));
			}
		}else{
			$this->db->where("MONTH(kontrol_tgl)",date("m"));
		}

		if($this->session->userdata('filter_tahun')!=''){
			if($this->session->userdata('filter_tahun')=="all"){

			}else{
				$this->db->where("YEAR(kontrol_tgl)",$this->session->userdata('filter_tahun'));
			}
		}else{
			$this->db->where("YEAR(kontrol_tgl)",date("Y"));
		}

		$rows = $this->reminder_model->get_data_petugas($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_kunjungan'	=> $act->id_kunjungan,
				'urut'	    	=> substr($act->id_kunjungan,-3),
				'username'	    => $act->username,
				'kontrol_tgl'	=> $act->kontrol_tgl,
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

	function json_pasien(){

		if($this->session->userdata('level')=="pasien"){
			$this->db->where('kunjungan.username',$this->session->userdata('username'));	
		}else{
			$this->db->where('kunjungan.code',$this->session->userdata('puskesmas'));	
		} 

		$rows_all = $this->reminder_model->get_data_pasien();

		if($this->session->userdata('level')=="pasien"){
			$this->db->where('kunjungan.username',$this->session->userdata('username'));	
		}else{
			$this->db->where('kunjungan.code',$this->session->userdata('puskesmas'));	
		} 

		$rows = $this->reminder_model->get_data_pasien($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_kunjungan'	=> $act->id_kunjungan,
				'urut'	    	=> substr($act->id_kunjungan,-3),
				'username'	    => $act->username,
				'nama'	    	=> $act->nama,
				'kontrol_tgl'	=> $act->kontrol_tgl,
				'anjuran_dokter'=> $act->anjuran_dokter,
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
