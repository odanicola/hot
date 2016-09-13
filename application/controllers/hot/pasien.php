<?php
class Pasien extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('hot_model');
	}

	function filter_jenis_bpjs(){
		if($_POST) {
			if($this->input->post('jenis_bpjs') != '') {
				$this->session->set_userdata('filter_jenis_bpjs',$this->input->post('jenis_bpjs'));
			}
		}
	}

	function filter_jenis_kelamin(){
		if($_POST) {
			if($this->input->post('jenis_kelamin') != '') {
				$this->session->set_userdata('filter_jenis_kelamin',$this->input->post('jenis_kelamin'));
			}
		}
	}

	function filter_urutan_usia(){
		if($_POST) {
			if($this->input->post('urutan_usia') != '') {
				$this->session->set_userdata('filter_urutan_usia',$this->input->post('urutan_usia'));
			}
		}
	}
	
	function json(){

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				$this->db->like($field,$value);
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		if($this->session->userdata('filter_jenis_bpjs')!=''){
			if($this->session->userdata('filter_jenis_bpjs')==01){
				$this->db->where('app_users_profile.bpjs IS NOT NULL');
			}elseif($this->session->userdata('filter_jenis_bpjs')==02) {
				$this->db->where('app_users_profile.bpjs IS NULL ');
			}else{
				$this->db->order_by('app_users_profile.nama','ASC');
			}
		}else{

		}

		if($this->session->userdata('filter_jenis_kelamin')!=''){
			if($this->session->userdata('filter_jenis_kelamin')=='L'){
				$this->db->where('app_users_profile.jk','L');
			}elseif ($this->session->userdata('filter_jenis_kelamin')=='P') {
				$this->db->where('app_users_profile.jk','P');
			}else{
				$this->db->order_by('app_users_profile.nama','ASC');
			}
		}else{

		}

		if($this->session->userdata('filter_urutan_usia')!=''){
			if($this->session->userdata('filter_urutan_usia')==01){
				$this->db->order_by("usia", "ASC");
			}elseif($this->session->userdata('filter_urutan_usia')==02){
				$this->db->order_by("usia", "DESC");
			}else{
				$this->db->order_by('app_users_profile.nama','ASC');
			}
		}else{

		}

		$rows_all = $this->hot_model->get_data_pasien();

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				$this->db->like($field,$value);
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		if($this->session->userdata('filter_jenis_bpjs')!=''){
			if($this->session->userdata('filter_jenis_bpjs')==01){
				$this->db->where('app_users_profile.bpjs IS NOT NULL');
			}elseif($this->session->userdata('filter_jenis_bpjs')==02) {
				$this->db->where('app_users_profile.bpjs IS NULL ');
			}else{
				$this->db->order_by('app_users_profile.nama','ASC');
			}
		}else{

		}

		if($this->session->userdata('filter_jenis_kelamin')!=''){
			if($this->session->userdata('filter_jenis_kelamin')=='L'){
				$this->db->where('app_users_profile.jk','L');
			}elseif ($this->session->userdata('filter_jenis_kelamin')=='P') {
				$this->db->where('app_users_profile.jk','P');
			}else{
				$this->db->order_by('app_users_profile.nama','ASC');
			}
		}else{

		}

		if($this->session->userdata('filter_urutan_usia')!=''){
			if($this->session->userdata('filter_urutan_usia')==01){
				$this->db->order_by("usia", "ASC");
			}elseif($this->session->userdata('filter_urutan_usia')==02){
				$this->db->order_by("usia", "DESC");
			}else{
				$this->db->order_by('app_users_profile.nama','ASC');
			}
		}else{

		}

		$rows = $this->hot_model->get_data_pasien($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
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

	function index(){
		$this->authentication->verify('hot','edit');

		$data['title_group'] = "Dashboard";
		$data['title_form']  = "Data Pasien";

		$this->session->set_userdata('filter_jenis_bpjs','');
		$this->session->set_userdata('filter_urutan_usia','');
		$this->session->set_userdata('filter_jenis_kelamin','');

		$data['content'] = $this->parser->parse("hot/data_pasien",$data,true);

		$this->template->show($data,"home");
	}

	function add(){
		$this->authentication->verify('hot','add');

        $this->form_validation->set_rules('username','NIK', 'trim|required');
        $this->form_validation->set_rules('bpjs','BPJS', 'trim');
        $this->form_validation->set_rules('password','Password', 'trim|required');
        $this->form_validation->set_rules('nama','Nama', 'trim|required');
        $this->form_validation->set_rules('jk','Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('tgl_lahir','Tanggal Lahir', 'trim');
        $this->form_validation->set_rules('phone_number','No.Telepon', 'trim');
        $this->form_validation->set_rules('email', 'Email','trim|required|callback_check_email2');
        $this->form_validation->set_rules('alamat', 'Alamat','trim');
        $this->form_validation->set_rules('code','Puskesmas','trim');
        $this->form_validation->set_rules('tb','Tinggi Badan','trim');
        $this->form_validation->set_rules('bb','Berat Badan','trim');

		if($this->form_validation->run()== FALSE){
			$data['title_group'] = "Dashboard";
			$data['title_form']  = "Tambah Data Pasien";
			$data['action']      = "add";
			$data['alert_form']  = '';

			$data['datapuskesmas']  = $this->hot_model->get_pus("317204","code","cl_phc");

			$data['content'] = $this->parser->parse("hot/data_pasien_add",$data,true);
		}elseif($this->hot_model->inesert_pasien()=='true'){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."hot/pasien");
			die("OK");

		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."hot/pasien/add");
			$data['alert_form'] = 'Save data failed...';
			die("NOTOK");
		}
		
		$this->template->show($data,"home");
	}

	function profil_pasien_edit($username){

        $this->form_validation->set_rules('username','NIK', 'trim');
        $this->form_validation->set_rules('bpjs','BPJS', 'trim');
        $this->form_validation->set_rules('nama','Nama', 'trim');
        $this->form_validation->set_rules('jk','Jenis Kelamin', 'trim');
        $this->form_validation->set_rules('tgl_lahir','Tanggal Lahir', 'trim');
        $this->form_validation->set_rules('phone_number','No.Telepon', 'trim');
        $this->form_validation->set_rules('email', 'Email','trim');
        $this->form_validation->set_rules('alamat', 'Alamat','trim');
        $this->form_validation->set_rules('code','Puskesmas','trim');
        $this->form_validation->set_rules('tb','Tinggi Badan','trim');
        $this->form_validation->set_rules('bb','Berat Badan','trim');
        
        $data 				   = $this->hot_model->get_profil_pasien_where($username); 
        $data['title_group']   = "Dashboard";
		$data['title_form']    = "Profil Pasien";
		$data['action']		   = 'edit';
		$data['username']	   = $username;
		$data['datapuskesmas'] = $this->hot_model->get_pus("317204","code","cl_phc");
		$data['alert_form']    = '';

		if($this->form_validation->run() == FALSE){
			die($this->parser->parse("hot/data_pasien_profil",$data));
		}elseif($this->hot_model->update_pasien_profil($username)){
        	
        	$data 			 	   = $this->hot_model->get_profil_pasien_where($username); 
        	$data['title_group']   = "Dashboard";
			$data['title_form']    = "Profil Pasien";
			$data['username']	   = $username;
			$data['action']  	   = 'edit';
			$data['datapuskesmas'] = $this->hot_model->get_pus("317204","code","cl_phc");
			
			$data['alert_form']    = 'Save data successful...';
		}else{
			$data['alert_form']    = 'Save data failed...';
		}

		die($this->parser->parse("hot/data_pasien_profil",$data));
	}
	
	function akun_pasien_edit($username){
		$this->form_validation->set_rules('password','Password', 'trim');

	        $data 				   = $this->hot_model->get_akun_pasien_where($username); 
	        $data['title_group']   = "Dashboard";
			$data['title_form']    = "Akun Pasien";
			$data['action']		   = 'edit';
			$data['username']	   = $username;
			$data['alert_form']    = '';

		if($this->form_validation->run() == FALSE){
			die($this->parser->parse("hot/data_pasien_akun",$data));
		}elseif($this->hot_model->update_pasien_akun($username)){
        	$data 			 	   = $this->hot_model->get_akun_pasien_where($username); 
        	$data['title_group']   = "Dashboard";
			$data['title_form']    = "Akun Pasien";
			$data['username']	   = $username;
			$data['action']  	   = 'edit';
			$data['alert_form']    = 'Save data successful...';
		}else{
			$data['alert_form'] = 'Save data failed...';
		}
		die($this->parser->parse("hot/data_pasien_akun",$data));
	}

	function edit($username=0) {
		$this->authentication->verify('hot','add');

        $data 				 = $this->hot_model->get_pasien_where($username); 
		$data['username']	 = $username;
		$data['title_group'] = "Dashboard";
		$data['title_form']  = "Data Pasien";
		$data['content'] 	 = $this->parser->parse("hot/data_pasien_edit",$data,true);

		$this->template->show($data,"home");
	}

	function del($username=0){
		$this->authentication->verify('hot','del');

		$data['username']		= $username;
		if($this->hot_model->delete_pasien($username)){
			$this->session->set_flashdata('alert', 'Delete data ('.$username.')');
			redirect(base_url()."hot/pasien");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."hot/pasien");
		}
	}

	function data_pasien_edit($pageIndex,$username){
		$data = array();
		$data['username']=$username;

		switch ($pageIndex) {
			case 1:
				$this->profil_pasien_edit($username);

				break;
			case 2:
				$this->akun_pasien_edit($username);


				die($this->parser->parse("hot/data_pasien_akun",$data));
				break;
		}

	}

	function check_email2($str){
			$check = $this->hot_model->check_email($str);
			
			if($check>0){
				$this->form_validation->set_message('check_email2', 'Email tidak dapat digunakan');
				return FALSE;
			}else{
				return TRUE;
			}
		
	}



}
