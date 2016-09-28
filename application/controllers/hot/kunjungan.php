<?php
class Kunjungan extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('hot/kunjungan_model');
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
			$data['code']  		 = $this->session->userdata('puskesmas');
			$data['nik']  		 = $this->session->userdata('username');

			$data['kunjungan']  = $this->kunjungan_model->get_kunjungan($data['nik']);

			if($this->session->userdata('level')=='administrator'){
				$code = "317204";
			}else{
				$code = $this->session->userdata('puskesmas');
			}
			$data['datapuskesmas']  = $this->hot_model->get_pus($code,"code","cl_phc");
			$this->session->set_userdata('filter_puskesmas','P'.$this->session->userdata('puskesmas'));

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
		$data['title_group'] = "Kunjungan";
		$data['title_form']  = "Data Kunjungan";

		$this->session->userdata('filter_tahun')		=="" ? $this->session->set_userdata('filter_tahun',date('Y')): '';
		$this->session->userdata('filter_bulan')		=="" ? $this->session->set_userdata('filter_bulan',date('n')): '';
		$this->session->userdata('filter_tanggal')		=="" ? $this->session->set_userdata('filter_tanggal',date('d')): '';

		$data['filter_tahun'] 			= $this->session->userdata('filter_tahun');
		$data['filter_bulan'] 			= $this->session->userdata('filter_bulan');
		$data['filter_tanggal']	 		= $this->session->userdata('filter_tanggal');
		$data['filter_status_antri'] 	= $this->session->userdata('filter_status_antri');
		$data['filter_jenis_kelamin'] 	= $this->session->userdata('filter_jenis_kelamin');

		$data['bulan_option'] = array("0","Januari","Februari","Maret","April","Mei","Juni","July","Agustus","September","Oktober","November","Desember");
		$data['tahun_option'] = array();
		for($i=date("Y");$i>=(date("Y"))-5;$i--){
			$data['tahun_option'][] = $i;
		}
		$data['tanggal_option'] = array();
		for($i=0;$i<=31;$i++){
			$d = $i <10 ? '0'.$i : $i;
			$data['tanggal_option'][] = $d;
		}

		$data['content'] = $this->parser->parse("hot/kunjungan",$data,true);

		$this->template->show($data,"home");
	}

	function filter_jenis_kelamin(){
		if($_POST) {
			if($this->input->post('filter_jenis_kelamin') != '') {
				$this->session->set_userdata('filter_jenis_kelamin',$this->input->post('filter_jenis_kelamin'));
			}
		}
	}

	function filter_status_antri(){
		if($_POST) {
			$this->session->set_userdata('filter_status_antri','');
			if($this->input->post('filter_status_antri') != '') {
				$this->session->set_userdata('filter_status_antri',$this->input->post('filter_status_antri'));
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
	
	function filter_tanggal(){
		if($_POST) {
			if($this->input->post('filter_tanggal') != '') {
				$this->session->set_userdata('filter_tanggal',$this->input->post('filter_tanggal'));
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

		if($this->session->userdata('filter_jenis_kelamin')!='' && $this->session->userdata('filter_jenis_kelamin')!='-'){
			$this->db->where('app_users_profile.jk',$this->session->userdata('filter_jenis_kelamin'));
		}

		if($this->session->userdata('level')!='pasien'){
			$this->db->where('kunjungan.tgl',$tgl);
		}
		if($this->session->userdata('filter_status_antri')!=''){
			$this->db->where('kunjungan.status_antri', $this->session->userdata('filter_status_antri'));
		}
		$rows_all = $this->kunjungan_model->get_data_pasien();


		if($this->session->userdata('level')=="pasien"){
			$this->db->where('kunjungan.username',$this->session->userdata('username'));	
		}else{
			$this->db->where('kunjungan.code',$this->session->userdata('puskesmas'));	
		} 

		if($this->session->userdata('filter_jenis_kelamin')!='' && $this->session->userdata('filter_jenis_kelamin')!='-'){
			$this->db->where('app_users_profile.jk',$this->session->userdata('filter_jenis_kelamin'));
		}

		if($this->session->userdata('level')!='pasien'){
			$this->db->where('kunjungan.tgl',$tgl);
		}
		if($this->session->userdata('filter_status_antri')!=''){
			$this->db->where('kunjungan.status_antri', $this->session->userdata('filter_status_antri'));
		}
		$rows = $this->kunjungan_model->get_data_pasien($this->input->post('recordstartindex'), $this->input->post('pagesize'));
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
				'cl_pid'		=> $act->cl_pid,
				'reg_id'		=> $act->reg_id
			);
		}

		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows'      => $data
		);

		echo json_encode(array($json));
	}

	function json_autocomplete(){
		$rows_all = $this->kunjungan_model->get_autocomplete_pasien();
		$data = array();
		foreach($rows_all as $act) {
			$data[] = array(
				'username'	    => $act->username,
				'jk'			=> $act->jk,
				'nama'   	    => $act->nama,
				'usia'   	    => $act->usia,
				'bpjs'   	    => $act->bpjs
			);
		}

		echo json_encode($data);
	}

	function json_sebelumnya($username, $tgl){
		$this->db->where('kunjungan.username', $username);
		$this->db->where('kunjungan.tgl < ', $tgl);
		$this->db->where('kunjungan.status_antri', 'selesai');
		$rows_all = $this->kunjungan_model->get_data_pasien();

		$this->db->where('kunjungan.username', $username);
		$this->db->where('kunjungan.tgl < ', $tgl);
		$this->db->where('kunjungan.status_antri', 'selesai');
		$rows = $this->kunjungan_model->get_data_pasien($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_kunjungan'	=> $act->id_kunjungan,
				'urut'	    	=> substr($act->id_kunjungan,-3),
				'tgl'	    	=> date("d-m-Y",strtotime($act->tgl)),
				'waktu'	    	=> $act->waktu,
				'username'	    => $act->username,
				'systolic'	    => $act->systolic,
				'diastolic'	    => $act->diastolic,
			);
		}

		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows'      => $data
		);

		echo json_encode(array($json));
	}

	function sebelumnya($username="",$tgl=""){
		$this->authentication->verify('hot','show');

		$data['sebelumnya']	= $this->kunjungan_model->get_sebelumnya($username,$tgl); 
		$data['username'] 	= $username;
		$data['tgl'] 		= $tgl;

		die($this->parser->parse("hot/kunjungan_sebelumnya",$data));
	}

	function edit($id_kunjungan=0){
		$this->authentication->verify('hot','edit');

		$data 				    = $this->kunjungan_model->get_pemeriksaan($id_kunjungan); 
		$data['sebelumnya']	    = $this->kunjungan_model->get_sebelumnya($data['username'],$data['tgl']); 
		$data['title_group']    = "Kunjungan";
		$data['title_form']     = "Pengukuran";
		$data['action']		    = "edit";
		$data['id_kunjungan']	= $id_kunjungan;
		$data['systolic']		= $data['systolic']=="" ? 130 : $data['systolic'];
		$data['diastolic']		= $data['diastolic']=="" ? 80 : $data['diastolic'];
		$data['pulse']			= $data['pulse']=="" ? 80 : $data['pulse'];
		$data['gds']			= $data['gds']=="" ? 180 : $data['gds'];
		$data['gdp']			= $data['gdp']=="" ? 100 : $data['gdp'];
		$data['gdpp']			= $data['gdpp']=="" ? 140 : $data['gdpp'];
		$data['kolesterol']		= $data['kolesterol']=="" ? 200 : $data['kolesterol'];
		$data['asamurat']		= $data['asamurat']=="" ? 7.7 : $data['asamurat'];
		$data['tgl_kunjungan']	= $data['tgl'];
		$data['tgl']	= date("d M Y", strtotime($data['tgl']));
		$data['waktu']	= date("H:i:s",time());

		$data['content'] 		= $this->parser->parse("hot/kunjungan_edit",$data,true);

		$this->template->show($data,"home");
	}

	function simpan($id_kunjungan=0){
		$this->authentication->verify('hot','edit');

		if($this->kunjungan_model->simpan($id_kunjungan)){
			echo "OK";
		}else{
			echo "ERROR";
		}
	}

	function del($id_kunjungan=0){
		$this->authentication->verify('hot','del');

		$id_kunjungan = $this->input->post('id_kunjungan');
		if($this->kunjungan_model->delete($id_kunjungan)){
			echo "OK";
		}else{
			echo "ERROR";
		}
	}

}
