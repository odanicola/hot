<?php
class Sinkronisasi extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('hot/sinkronisasi_model');
		$this->load->model('hot_model');
	}

	function daftar(){
		$this->authentication->verify('hot','add');

        $this->form_validation->set_rules('username','NIK', 'trim|required');
        $this->form_validation->set_rules('bpjs','BPJS', 'trim');
        $this->form_validation->set_rules('pass','Password', 'trim');
        $this->form_validation->set_rules('nama','Nama', 'trim');
        $this->form_validation->set_rules('jk','Jenis Kelamin', 'trim');
        $this->form_validation->set_rules('tgl_lahir','Tanggal Lahir', 'trim');
        $this->form_validation->set_rules('phone_number','No.Telepon', 'trim');
        $this->form_validation->set_rules('email', 'Email','trim');
        $this->form_validation->set_rules('alamat', 'Alamat','trim');
        $this->form_validation->set_rules('code','Puskesmas','trim');

		if($this->form_validation->run()== FALSE){
			$data['title_group'] = "Dashboard";
			$data['title_form']  = "Pendaftaran Kunjungan Pasien";
			$data['action']      = "add";
			$data['nik']  		 = $this->session->userdata('username');

			$data['kunjungan']  = $this->kunjungan_model->get_kunjungan($data['nik']);

			$data['code']  		 = $this->session->userdata('puskesmas');

			$data['datapuskesmas']  = $this->hot_model->get_pus("317204","code","cl_phc");

			$data['content'] = $this->parser->parse("hot/kunjungan_daftar_add",$data,true);
			$this->template->show($data,"home");
		}elseif($this->kunjungan_model->insert()=='true'){
			echo 'OK';
		}else{
			echo 'ERROR';
		}
	}

	function daftar_batal(){
		$username = $this->input->post('username');

		if($this->kunjungan_model->batal($username)){
			echo "OK";
		}else{
			echo "ERROR";
		}
	}

	function index(){
		$this->authentication->verify('hot','show');
		$data['title_group'] = "Sinkronisasi";
		$data['title_form']  = "Data Sinkronisasi";

		$this->session->userdata('filter_tahun')		=="" ? $this->session->set_userdata('filter_tahun',date('Y')): '';
		$this->session->userdata('filter_bulan')		=="" ? $this->session->set_userdata('filter_bulan',date('n')): '';

		$data['filter_tahun'] 			= $this->session->userdata('filter_tahun');
		$data['filter_bulan'] 			= $this->session->userdata('filter_bulan');
		$data['filter_status_sync'] 	= $this->session->userdata('filter_status_sync');
		$data['filter_jenis_kelamin'] 	= $this->session->userdata('filter_jenis_kelamin');

		$data['tahun_option'] = array();
		for($i=date("Y");$i>=(date("Y"))-5;$i--){
			$data['tahun_option'][] = $i;
		}

		
		$this->session->set_userdata('filter_tahun','');
		$data['bulan']			= array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
		$data['content'] = $this->parser->parse("hot/sinkronisasi",$data,true);

		$this->template->show($data,"home");
	}

	function filter_jenis_kelamin(){
		if($_POST) {
			if($this->input->post('filter_jenis_kelamin') != '') {
				$this->session->set_userdata('filter_jenis_kelamin',$this->input->post('filter_jenis_kelamin'));
			}
		}
	}

	function filter_status_sync(){
		if($_POST) {
			if($this->input->post('filter_status_sync') != '') {
				$this->session->set_userdata('filter_status_sync',$this->input->post('filter_status_sync'));
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


	function json(){

		if($this->session->userdata('level')=="pasien"){
			$this->db->where('kunjungan.username',$this->session->userdata('username'));	
		}else{
			$this->db->where('kunjungan.code',$this->session->userdata('puskesmas'));	
		} 

		if($this->session->userdata('filter_jenis_kelamin')!='' && $this->session->userdata('filter_jenis_kelamin')!='-'){
			$this->db->where('app_users_profile.jk',$this->session->userdata('filter_jenis_kelamin'));
		}

		if($this->session->userdata('filter_status_sync')!='' && $this->session->userdata('filter_status_sync')!='-'){
			$this->db->where('kunjungan.status_pcare',$this->session->userdata('filter_status_sync'));
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

		$rows_all = $this->sinkronisasi_model->get_data_pasien();

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

		if($this->session->userdata('level')=="pasien"){
			$this->db->where('kunjungan.username',$this->session->userdata('username'));	
		}else{
			$this->db->where('kunjungan.code',$this->session->userdata('puskesmas'));	
		} 

		if($this->session->userdata('filter_jenis_kelamin')!='' && $this->session->userdata('filter_jenis_kelamin')!='-'){
			$this->db->where('app_users_profile.jk',$this->session->userdata('filter_jenis_kelamin'));
		}

		if($this->session->userdata('filter_status_sync')!='' && $this->session->userdata('filter_status_sync')!='-'){
			$this->db->where('kunjungan.status_pcare',$this->session->userdata('filter_status_sync'));
		}

		$rows = $this->sinkronisasi_model->get_data_pasien($this->input->post('recordstartindex'), $this->input->post('pagesize'));
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

	function edit($id_kunjungan=0){
		$this->authentication->verify('hot','edit');

		$data 				    = $this->kunjungan_model->get_pemeriksaan($id_kunjungan); 
		$data['title_group']    = "Kunjungan";
		$data['title_form']     = "Pengukuran";
		$data['action']		    = "edit";
		$data['id_kunjungan']	= $id_kunjungan;
		$data['tgl']	= date("d M Y", strtotime($data['tgl']));
		$data['waktu']	= date("H:i:s",time());

		$data['content'] 		= $this->parser->parse("hot/kunjungan_edit",$data,true);

		$this->template->show($data,"home");
	}

	function del($username=0){
		$this->authentication->verify('hot','del');

		$data['username']		= $username;
		if($this->kunjungan_model->delete_pasien($username)){
			$this->session->set_flashdata('alert', 'Delete data ('.$username.')');
			redirect(base_url()."hot/pasien");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."hot/pasien");
		}
	}

}
