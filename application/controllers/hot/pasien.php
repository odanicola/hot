<?php
class Pasien extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');
		
		$this->load->model('hot/pasien_model');

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

	function filter_puskesmas(){
		if($_POST) {
			if($this->input->post('puskesmas') != '') {
				$this->session->set_userdata('filter_puskesmas',$this->input->post('puskesmas'));
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

		if($this->session->userdata('filter_jenis_bpjs')!='' && $this->session->userdata('filter_jenis_bpjs')!='-'){
			if($this->session->userdata('filter_jenis_bpjs')==01){
				$this->db->where('app_users_profile.bpjs IS NOT NULL');
			}else{
				$this->db->where('app_users_profile.bpjs IS NULL ');
			}
		}

		if($this->session->userdata('filter_jenis_kelamin')!='' && $this->session->userdata('filter_jenis_kelamin')!='-'){
			$this->db->where('app_users_profile.jk',$this->session->userdata('filter_jenis_kelamin'));
		}

		if($this->session->userdata('filter_puskesmas')!='' && $this->session->userdata('filter_puskesmas')!='-'){
			$this->db->where('app_users_profile.code',substr($this->session->userdata('filter_puskesmas'),1));
		}

		if($this->session->userdata('filter_urutan_usia')!='' && $this->session->userdata('filter_urutan_usia')!='-'){
			if($this->session->userdata('filter_urutan_usia')==01){
				$this->db->order_by("usia", "ASC");
			}else{
				$this->db->order_by("usia", "DESC");
			}
		}

		$rows_all = $this->pasien_model->get_data_pasien();

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

		if($this->session->userdata('filter_jenis_bpjs')!='' && $this->session->userdata('filter_jenis_bpjs')!='-'){
			if($this->session->userdata('filter_jenis_bpjs')==01){
				$this->db->where('app_users_profile.bpjs IS NOT NULL');
			}else{
				$this->db->where('app_users_profile.bpjs IS NULL ');
			}
		}

		if($this->session->userdata('filter_jenis_kelamin')!='' && $this->session->userdata('filter_jenis_kelamin')!='-'){
			$this->db->where('app_users_profile.jk',$this->session->userdata('filter_jenis_kelamin'));
		}

		if($this->session->userdata('filter_puskesmas')!='' && $this->session->userdata('filter_puskesmas')!='-'){
			$this->db->where('app_users_profile.code',substr($this->session->userdata('filter_puskesmas'),1));
		}

		if($this->session->userdata('filter_urutan_usia')!='' && $this->session->userdata('filter_urutan_usia')!='-'){
			if($this->session->userdata('filter_urutan_usia')==01){
				$this->db->order_by("usia", "ASC");
			}else{
				$this->db->order_by("usia", "DESC");
			}
		}

		$rows = $this->pasien_model->get_data_pasien($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'username'	    => $act->username,
				'jk'			=> $act->jk,
				'nama'   	    => $act->nama,
				'usia'   	    => $act->usia,
				'bpjs'   	    => $act->bpjs,
				'phone_number'	=> $act->phone_number,
				'cl_pid'		=> $act->cl_pid,
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
		$this->session->set_userdata('filter_puskesmas','P'.$this->session->userdata('puskesmas'));

		$data['datapuskesmas']  = $this->pasien_model->get_pus("317204","code","cl_phc");

		$data['content'] = $this->parser->parse("hot/data_pasien",$data,true);

		$this->template->show($data,"home");
	}

	function add(){
		$this->authentication->verify('hot','add');

		$data['title_group'] = "Dashboard";
		$data['title_form']  = "Tambah Data Pasien";
		$data['action']      = "add";
		$data['alert_form']  = '';
		$data['code']  		 = 'P'.$this->session->userdata('puskesmas');

		$data['datapuskesmas']  = $this->pasien_model->get_pus("317204","code","cl_phc");

		$data['content'] = $this->parser->parse("hot/data_pasien_add",$data,true);
		
		$this->template->show($data,"home");
	}

	function doadd(){
		$this->authentication->verify('hot','add');

        $this->form_validation->set_rules('username','NIK', 'trim|required');
        $this->form_validation->set_rules('bpjs','BPJS', 'trim');
        $this->form_validation->set_rules('password','Password', 'trim|required|matches[password2]');
        $this->form_validation->set_rules('nama','Nama', 'trim|required');
        $this->form_validation->set_rules('jk','Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('tgl_lahir','Tanggal Lahir', 'trim');
        $this->form_validation->set_rules('phone_number','No.Telepon', 'trim');
        $this->form_validation->set_rules('email', 'Email','trim|required|callback_check_email2');
        $this->form_validation->set_rules('alamat', 'Alamat','trim');
        $this->form_validation->set_rules('code','Puskesmas','trim');
        $this->form_validation->set_rules('tb','Tinggi Badan','trim');
        $this->form_validation->set_rules('bb','Berat Badan','trim');
        $this->form_validation->set_rules('cl_pid','No MR','trim');

		if($this->form_validation->run()== FALSE){
			$err = "err|".validation_errors();
			die($err);
		}elseif($res = $this->pasien_model->insert_pasien()){
			die($res);
		}else{
			die($res);
		}
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
        
        $data 				   = $this->pasien_model->get_profil_pasien_where($username); 
        $data['title_group']   = "Dashboard";
		$data['title_form']    = "Profil Pasien";
		$data['action']		   = 'edit';
		$data['username']	   = $username;
		$data['datapuskesmas'] = $this->pasien_model->get_pus("317204","code","cl_phc");
		$data['alert_form']    = '';

		if($this->form_validation->run() == FALSE){
			die($this->parser->parse("hot/data_pasien_profil",$data));
		}elseif($this->pasien_model->update_pasien_profil($username)=='true'){
        	
        	$data 			 	   = $this->pasien_model->get_profil_pasien_where($username); 
        	$data['title_group']   = "Dashboard";
			$data['title_form']    = "Profil Pasien";
			$data['username']	   = $username;
			$data['action']  	   = 'edit';
			$data['datapuskesmas'] = $this->pasien_model->get_pus("317204","code","cl_phc");
			echo "OK|";
		}else{
			echo "NOTOK|";
		}
		die($this->parser->parse("hot/data_pasien_profil",$data));
	}
	
	function akun_pasien_edit($username){
        $this->form_validation->set_rules('password','Password', 'trim|required|matches[password2]');

	        $data 				   = $this->pasien_model->get_akun_pasien_where($username); 
	        $data['title_group']   = "Dashboard";
			$data['title_form']    = "Akun Pasien";
			$data['action']		   = 'edit';
			$data['username']	   = $username;
			$data['alert_form']    = '';

		if($this->form_validation->run() == FALSE){
			die($this->parser->parse("hot/data_pasien_akun",$data));
		}elseif($this->pasien_model->update_pasien_akun($username)){
        	$data 			 	   = $this->pasien_model->get_akun_pasien_where($username); 
        	$data['title_group']   = "Dashboard";
			$data['title_form']    = "Akun Pasien";
			$data['username']	   = $username;
			$data['action']  	   = 'edit';
			echo "OK|";
			// $data['alert_form']    = 'Save data successful...';
		}else{
			echo "NOTOK|";
			// $data['alert_form'] = 'Save data failed...';
		}
		die($this->parser->parse("hot/data_pasien_akun",$data));
	}

	function edit($username=0) {
		$this->authentication->verify('hot','add');
        $this->form_validation->set_rules('password','Password', 'trim|required|matches[password2]');

        $data 				 = $this->pasien_model->get_pasien_where($username); 
		$data['username']	 = $username;
		$data['title_group'] = "Dashboard";
		$data['title_form']  = "Data Pasien";
		$data['content'] 	 = $this->parser->parse("hot/data_pasien_edit",$data,true);

		$this->template->show($data,"home");
	}

	function del($username=0){
		$this->authentication->verify('hot','del');

		$data['username']		= $username;
		if($this->pasien_model->delete_pasien($username)){
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
			$check = $this->pasien_model->check_email($str);
			
			if($check>0){
				$this->form_validation->set_message('check_email2', 'Email tidak dapat digunakan');
				return FALSE;
			}else{
				return TRUE;
			}
		
	}

	function check_pass2($str){
		$regex1=preg_match('/[A-Z]/', $str);
		$regex2=preg_match('/[a-z]/', $str);
		$regex3=preg_match('/[0-9]/', $str);
		
		
		 if (!$regex1 || !$regex2 || !$regex3){
			if(!$regex1==true)
			{
				$this->form_validation->set_message('check_pass2', 'Format password harus kombinasi huruf besar');
			}
			else if(!$regex2==true)
			{
				$this->form_validation->set_message('check_pass2', 'Format password harus kombinasi huruf kecil');
			}
			else
			{
				$this->form_validation->set_message('check_pass2', 'Format password harus kombinasi angka');
			}
			return FALSE;
		 }
		 else{
			return TRUE;
 		 }
  	}

  	function export(){
		$this->authentication->verify('hot','show');
		
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_lahir') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		$rows_all = $this->pasien_model->get_data_pasien();

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_pengadaan') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}

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

		if($this->session->userdata('filter_puskesmas')!=''){
			$this->db->where('app_users_profile.code',$this->session->userdata('filte_puskesmas'));
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
	
		$rows = $this->pasien_model->get_data_pasien($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		$no=1;
		foreach($rows as $act) {
			$data[] = array(
				'username'	    => $act->username,
				'jk'			=> $act->jk,
				'nama'   	    => $act->nama,
				'usia'   	    => $act->usia,
				'bpjs'   	    => $act->bpjs,
				'phone_number'	=> $act->phone_number
			);
		}

		$dir = getcwd().'/';
		$template = $dir.'public/files/template/data_pasien.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data);
		
		$code = date('Y-m-d-H-i-s');
		$output_file_name = 'public/files/hasil/hasil_export_pasien_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}

}
