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
			}elseif($this->session->userdata('filter_jenis_bpjs')==01) {
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
			}else{
				$this->db->where('app_users_profile.bpjs IS NULL');
			}
		}else{

		}

		if($this->session->userdata('filter_jenis_kelamin')!=''){
			if($this->session->userdata('filter_jenis_kelamin')=='L'){
				$this->db->where('app_users_profile.jk','L');
			}else{
				$this->db->where('app_users_profile.jk','P');
			}
		}else{

		}

		if($this->session->userdata('filter_urutan_usia')!=''){
			if($this->session->userdata('filter_urutan_usia')==01){
				$this->db->order_by("usia", "ASC");
			}else{
				$this->db->order_by("usia", "DESC");
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
		$this->authentication->verify('mst','edit');
		$data['title_group'] = "Dashboard";
		$data['title_form']  = "Data Pasien";

		$this->session->set_userdata('filter_jenis_bpjs','');
		$this->session->set_userdata('filter_urutan_usia','');
		$this->session->set_userdata('filter_jenis_kelamin','');

		$data['content'] = $this->parser->parse("hot/data_pasien",$data,true);

		$this->template->show($data,"home");
	}

	function add(){
		$this->authentication->verify('mst','add');

        $this->form_validation->set_rules('username','Username', 'trim');
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
			$data['title_group']    = "Dashboard";
			$data['title_form']     = "Tambah Data Pasien";
			$data['action']		    = "add";
			$data['datapuskesmas']  = $this->hot_model->get_pus("317204","code","cl_phc");
		
			$data['content'] = $this->parser->parse("hot/data_pasien_add",$data,true);
			$this->template->show($data,"home");
		}elseif($this->hot_model->inesert_pasien()){
		    if ($res == 'false') {
				$this->session->set_flashdata('alert_form', 'Save data failed...');
				redirect(base_url()."hot");
				
				$data['alert_form'] = 'Save data failed...';
				die("NOTOK");
			}else{
		    	$this->session->set_flashdata('alert', 'Save data successful...');
				redirect(base_url()."hot/add_pasien");
				die("OK");
			}

		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."hot/add_pasien");

		}
	}

	function edit($username=0){
		$this->authentication->verify('mst','edit');

        $this->form_validation->set_rules('username','Username', 'trim');
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
			$data['title_group']    = "Dashboard";
			$data['title_form']     = "Ubah Data Pasien";
			$data['action']		    = "edit";
			$data['username']		= $username;
			$data 				    = $this->hot_model->get_pasien_where($username); 

			$data['datapuskesmas']  = $this->hot_model->get_pus("317204","code","cl_phc");
			$data['content'] 		= $this->parser->parse("hot/data_pasien_add",$data,true);

			$this->template->show($data,"home");
		}elseif($this->hot_model->update_pasien($username)){
		    if ($res == 'false') {
				$this->session->set_flashdata('alert_form', 'Save data failed...');
				redirect(base_url()."hot");
				
				$data['alert_form'] = 'Save data failed...';
				die("NOTOK");
			}else{
		    	$this->session->set_flashdata('alert', 'Save data successful...');
				redirect(base_url()."hot/add_pasien");
				die("OK");
			}
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."hot/add_pasien");
		}
	}

	function delete($username=0){
		$this->authentication->verify('mst','del');

		if($this->hot_model->delete_pasien($username)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
			redirect(base_url()."mst/agama");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."mst/agama");
		}
	}

}
