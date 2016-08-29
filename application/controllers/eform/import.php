<?php
class Import extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');

		$this->load->model('bpjs');
		$this->load->model('eform/import_model');

	}
	function pengadaan_export(){
		$this->authentication->verify('eform','show');
		
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl') {
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
		if ($this->session->userdata('puskesmas')!='') {
			$this->db->where('code_cl_phc','P'.$this->session->userdata('puskesmas'));
		}
		$rows = $this->import_model->get_data();
		$data_tabel = array();
		$no=1;
		foreach($rows as $act) {
	    	if($act->status_penyuluhan==1 && $act->status_senam==1){
	    		$kegiatan = "Penyuluhan dan Senam";
	    	}elseif($act->status_penyuluhan==1 && $act->status_senam==0){
	    		$kegiatan = "Penyuluhan";
	    	}else{
	    		$kegiatan = "Senam";
	    	}
	    	
			$data_tabel[] = array(
				'no'						=> $no++,
				'id_data_kegiatan' 			=> $act->id_data_kegiatan,
				'tgl' 						=> date("d-m-Y",strtotime($act->tgl)),
				'kode_kelompok' 			=> $act->kode_kelompok,
				'kelompok' 					=> $act->kelompok,
				'kode_club' 				=> $act->club,
				'status_penyuluhan' 		=> $act->status_penyuluhan,
				'status_senam'				=> $act->status_senam,
				'materi'					=> $act->materi,
				'pembicara'					=> $act->pembicara,
				'jmlpeserta'				=> $act->jmlpeserta,
				'kegiatan'					=> $kegiatan,
				'lokasi'					=> $act->lokasi,
				'eduId'						=> $act->eduId,
				'biaya'						=> number_format($act->biaya,2),
				'keterangan'				=> $act->keterangan,
				'edit'						=> 1,
				'delete'					=> 1
			);
		}


		$puskes = $this->input->post('puskes');
		if(empty($puskes) or $puskes == 'Pilih Puskesmas'){
			$nama = 'Semua Data Puskesmas';
		}else{
			$nama = $this->input->post('puskes');
		}
		$puskes = $this->input->post('puskes');
		$kode_sess=$this->session->userdata('puskesmas');
		$kd_prov = $this->import_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
		$kd_kab  = $this->import_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
		$kd_kec  = 'KEC. '.$this->import_model->get_nama('nama','cl_kec','code',substr($kode_sess, 0,7));
		$tahun_ = date("Y");
		$data_puskesmas[] = array('nama_puskesmas' => $puskes,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'tahun' => $tahun_);
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/dataKelompok.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = date('Y-m-d-H-i-s');
		$output_file_name = 'public/files/hasil/hasil_export_dataKelompok_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}
	function index(){
		$this->authentication->verify('eform','edit');
		$data['title_group'] = "Import Data";
		$data['title_form'] = "Daftar Import Excel";

		$this->session->set_userdata('filter_code_kecamatan','');
		$this->session->set_userdata('filter_code_kelurahan','');
		$this->session->set_userdata('filter_code_rukunwarga','');
		$this->session->set_userdata('filter_code_cl_rukunrumahtangga','');
		$this->session->set_userdata('filter_code_cl_bulandata','');
		$this->session->set_userdata('filter_code_cl_tahundata','');
		$this->session->set_userdata('filter_code_statushomevisit','');

		$kode_sess = $this->session->userdata("puskesmas");
		$data['datakecamatan'] = $this->import_model->get_datawhere(substr($kode_sess, 0,7),"code","cl_kec");
		$data['content'] = $this->parser->parse("eform/import/show",$data,true);
		$this->template->show($data,"home");
	}

	function bpjs_search($by = 'nik',$no){
      	$data = $this->bpjs->bpjs_search($by,$no);

      	echo json_encode($data);
	}
	function total_data_bpjs($id_keluarga=0,$id_import=0,$username=0,$no_anggota=0)
	{
		$this->db->where('id_import',$id_import);
		$this->db->select("COUNT(*) AS jmlbpjs,
			(SELECT COUNT(*) FROM import a WHERE a.id_import=import.id_import AND a.status='1') AS sudah,
			(SELECT COUNT(*) FROM import a WHERE a.id_import=import.id_import AND a.status='0') AS belum", false);
		$this->db->join('data_keluarga_anggota','data_keluarga_anggota.id_data_keluarga = import.id_data_keluarga AND data_keluarga_anggota.no_anggota = import.no_anggota and LENGTH(data_keluarga_anggota.bpjs)=13');
		$query = $this->db->get('import')->result();
		foreach ($query as $q) {
			$totaldatabpjs[] = array(
				'jmlbpjs' 	=> $q->jmlbpjs, 
				'sudah' 	=> $q->sudah, 
				'belum' 	=> $q->belum, 
			);
			echo json_encode($totaldatabpjs);
		}
	}
	function edit($id_keluarga=0,$id_import=0,$username=0,$no_anggota=0){
		$this->authentication->verify('eform','edit');
		$this->session->set_userdata('filter_code_statushomevisit','');
		if($this->form_validation->run()== FALSE){
			$data 	= $this->import_model->get_data_row($id_keluarga,$id_import,$username,$no_anggota);
			$data['title_group'] 	= "Import Data";
			$data['title_form']		= "Detail Import";
			$data['action']			= "edit";
			$data['id_keluarga']	= $id_keluarga;
			$data['id_import']		= $id_import;
			$data['username']		= $username;
			$data['datafilterimport']= array('all' => 'All Data','sudah' => 'Sudah Home Visit','belum' => 'Belum Home Visit');

			$data['detailimport']	= $this->parser->parse('eform/import/detail', $data, TRUE);
			$data['content'] 	= $this->parser->parse("eform/import/edit",$data,true);
		}elseif($this->import_model->update_entry($id_kegiatan)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."eform/import/edit/".$id_kegiatan);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."eform/import/edit/".$id_kegiatan);
		}

		$this->template->show($data,"home");
	}
	function json(){
		$this->authentication->verify('eform','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tanggal') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where('id_import',$value);
				}else if($field == 'jam') {
					$value = date("H:i:s",strtotime($value));
					$this->db->where('id_import',$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				if ($ord=='jam' || $ord=='tanggal') {
					$ord='id_import';
				}
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		if($this->session->userdata('filter_code_kelurahan') != '') {
			$this->db->where('data_keluarga.id_desa',$this->session->userdata('filter_code_kelurahan'));
		}
		 if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('data_keluarga.id_kecamatan',$this->session->userdata('filter_code_kecamatan'));
		}
		
		if($this->session->userdata('filter_code_cl_bulandata') != '') {
			if($this->session->userdata('filter_code_cl_bulandata') == 'all') {
			}else{
				$this->db->where('MONTH(data_keluarga.tanggal_pengisian)',$this->session->userdata('filter_code_cl_bulandata'));
			}
		}
		if($this->session->userdata('filter_code_cl_tahundata') != '') {
			if($this->session->userdata('filter_code_cl_tahundata') == 'all') {
			}else{
				$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$this->session->userdata('filter_code_cl_tahundata'));	
			}
		}else{
			$thnda=date("Y");
			$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$thnda);	
		}

		$rows_all = $this->import_model->get_data();


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tanggal') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where('id_import',$value);
				}else if($field == 'jam') {
					$value = date("H:i:s",strtotime($value));
					$this->db->where('id_import',$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				if ($ord=='jam' || $ord=='tanggal') {
					$ord='id_import';
				}
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		if($this->session->userdata('filter_code_kelurahan') != '') {
			$this->db->where('data_keluarga.id_desa',$this->session->userdata('filter_code_kelurahan'));
		}
		 if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('data_keluarga.id_kecamatan',$this->session->userdata('filter_code_kecamatan'));
		}
		
		if($this->session->userdata('filter_code_cl_bulandata') != '') {
			if($this->session->userdata('filter_code_cl_bulandata') == 'all') {
			}else{
				$this->db->where('MONTH(data_keluarga.tanggal_pengisian)',$this->session->userdata('filter_code_cl_bulandata'));
			}
		}
		if($this->session->userdata('filter_code_cl_tahundata') != '') {
			if($this->session->userdata('filter_code_cl_tahundata') == 'all') {
			}else{
				$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$this->session->userdata('filter_code_cl_tahundata'));	
			}
		}else{
			$thnda=date("Y");
			$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$thnda);	
		}
		$rows = $this->import_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();

		foreach($rows as $act) {
			$data[] = array(
				'id_import'				=> $act->id_import,
				'username'				=> $act->username,
				'id_data_keluarga'		=> $act->id_data_keluarga,
				'no_anggota'			=> $act->no_anggota,
				'keterangan'			=> $act->keterangan,
				'status'				=> $act->status,
				'jumlah'				=> $act->jumlah,
				'jam'					=> date("H:i:s",$act->id_import),
				'tanggal'				=> date("Y-m-d",$act->id_import),
				'edit'					=> 1,
				'delete'				=> 1
			);
		}


		
		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	
	public function json_detail($id_data_keluarga=0,$id_import=0,$username=''){
		$this->authentication->verify('eform','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}else if($field == 'jeniskelamin') {
					$this->db->where('(year(curdate())-year(data_keluarga_anggota.tgl_lahir))',$value);
				}else if($field == 'usia') {
					$this->db->where('jeniskelamin.value',$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		if($this->session->userdata('filter_code_kelurahan') != '') {
			$this->db->where('data_keluarga.id_desa',$this->session->userdata('filter_code_kelurahan'));
		}
		 if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('data_keluarga.id_kecamatan',$this->session->userdata('filter_code_kecamatan'));
		}
		
		if($this->session->userdata('filter_code_cl_bulandata') != '') {
			if($this->session->userdata('filter_code_cl_bulandata') == 'all') {
			}else{
				$this->db->where('MONTH(data_keluarga.tanggal_pengisian)',$this->session->userdata('filter_code_cl_bulandata'));
			}
		}
		if($this->session->userdata('filter_code_cl_tahundata') != '') {
			if($this->session->userdata('filter_code_cl_tahundata') == 'all') {
			}else{
				$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$this->session->userdata('filter_code_cl_tahundata'));	
			}
		}else{
			$thnda=date("Y");
			$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$thnda);	
		}
		if($this->session->userdata('filter_code_statushomevisit') != '') {
			if($this->session->userdata('filter_code_statushomevisit') == 'all') {
			}else{
				if ($this->session->userdata('filter_code_statushomevisit') == 'sudah') {
					$this->db->where('import.status','1');	
				}else{
					$this->db->where('import.status','0');	
				}
			}
		}

		$rows_all = $this->import_model->get_data_detail($id_data_keluarga,$id_import,$username);


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}else if($field == 'jeniskelamin') {
					$this->db->where('(year(curdate())-year(data_keluarga_anggota.tgl_lahir))',$value);
				}else if($field == 'usia') {
					$this->db->where('jeniskelamin.value',$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		if($this->session->userdata('filter_code_kelurahan') != '') {
			$this->db->where('data_keluarga.id_desa',$this->session->userdata('filter_code_kelurahan'));
		}
		 if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('data_keluarga.id_kecamatan',$this->session->userdata('filter_code_kecamatan'));
		}
		
		if($this->session->userdata('filter_code_cl_bulandata') != '') {
			if($this->session->userdata('filter_code_cl_bulandata') == 'all') {
			}else{
				$this->db->where('MONTH(data_keluarga.tanggal_pengisian)',$this->session->userdata('filter_code_cl_bulandata'));
			}
		}
		if($this->session->userdata('filter_code_cl_tahundata') != '') {
			if($this->session->userdata('filter_code_cl_tahundata') == 'all') {
			}else{
				$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$this->session->userdata('filter_code_cl_tahundata'));	
			}
		}else{
			$thnda=date("Y");
			$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$thnda);	
		}
		if($this->session->userdata('filter_code_statushomevisit') != '') {
			if($this->session->userdata('filter_code_statushomevisit') == 'all') {
			}else{
				if ($this->session->userdata('filter_code_statushomevisit') == 'sudah') {
					$this->db->where('import.status','1');	
				}else{
					$this->db->where('import.status','0');	
				}
			}
		}
		$rows = $this->import_model->get_data_detail($id_data_keluarga,$id_import,$username,$this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();

		foreach($rows as $act) {
			$data[] = array(
				'id_data_keluarga'		=> $act->id_data_keluarga,
				'id_import'				=> $act->id_import,
				'username'				=> $act->username,
				'no_anggota'			=> $act->no_anggota,
				'nama'					=> $act->nama,
				'nik'					=> $act->nik,
				'tmpt_lahir'			=> $act->tmpt_lahir,
				'id_pilihan_kelamin'	=> $act->id_pilihan_kelamin,
				'tgl_lahir'				=> $act->tgl_lahir,
				'tgl_lahirdata'			=> $act->tgl_lahir,
				'jeniskelamin'			=> $act->jeniskelamin,
				'bpjs'					=> $act->bpjs,
				'usia'					=> $act->usia,
				'suku'					=> $act->suku,
				'no_hp'					=> $act->no_hp,
				'ceklis'				=> isset($act->status) ? 1:0,
				'status'				=> $act->status,
				'edit'					=> 1,
				'delete'				=> 1
			);
		}


		
		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	
	function dodelpermohonan($id_data_kegiatan=0,$no_kartu=0){

		if($this->import_model->delete_entryitem($id_data_kegiatan,$no_kartu)){
				
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}
	function filter(){
		if($_POST) {
			if($this->input->post('code_cl_phc') != '') {
				$this->session->set_userdata('filter_code_cl_phc',$this->input->post('code_cl_phc'));
			}
		}
	}
	
	function addpesetabpjsterdaftar($nik=0,$no_kartu=0,$id_import=0,$username=0,$id_data_keluarga=0,$no_anggota=0){

		if ($data = $this->bpjs->inserbpjs($no_kartu)) {
			if ($data=='bpjserror' || $data=='datatidakada') {
				die("Error|Maaf! Proses simpan data gagal");
			}else{
				if($this->import_model->add_pesetabpjsterdaftar($nik,$no_kartu,$id_import,$username,$id_data_keluarga,$no_anggota)==true){			
					die("OK|Data Telah disimpan");
				}else{
					die("Error|Maaf! Proses simpan data gagal");
				}
			}
		}else{
			die("Error|Maaf! Proses simpan data gagal");
		}
	}
	
	public function add_peserta($kode=0)
	{	
		$data['action']			= "add";
		$data['kode']			= $kode;
        $this->form_validation->set_rules('nik', 'NIK', 'trim');
        $this->form_validation->set_rules('bpjs', 'No BPJS', 'trim|required');
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'trim|required');
        $this->form_validation->set_rules('id_pilihan_kelamin', 'Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('jenis_peserta', 'Jenis Peserta', 'trim|required');
        $this->form_validation->set_rules('nmProvider', 'Jenis Peserta', 'trim');
        $this->form_validation->set_rules('jnsKelas', 'Jenis Peserta', 'trim');
        $this->form_validation->set_rules('noHP', 'Jenis Peserta', 'trim');

		if($this->form_validation->run()== FALSE){
			$data['data_pilihan_kelamin'] = $this->import_model->get_pilihan("jk");
			$data['alert_form']		= '';
			$data['action']			= "add";
			$data['kode']			= $kode;
			$data['notice']			= validation_errors();

			die($this->parser->parse('eform/import/peserta_form', $data));
		}else{
			$this->db->where('id_data_kegiatan',$kode);
			$this->db->where('no_kartu',$this->input->post('bpjs'));
			$qwery=$this->db->get('data_kegiatan_peserta');
			if ($qwery->num_rows() > 0) {
				die("Error|Data Telah Tersimpan");
			}else{
				$jenispeserta = $this->input->post('jenis_peserta');
				$tgl = explode('-', $this->input->post('tgl_lahir'));
				$tgl_lahir = $tgl[2].'-'.$tgl[1].'-'.$tgl[0];
				$kel = $this->input->post('id_pilihan_kelamin');
				if ($kel=='6') {
					$kelamin='P';# code...
				}else{
					$kelamin='L';
				}
				$values = array(
					'id_data_kegiatan'		=>$kode,
					'no_kartu' 			  	=> $this->input->post('bpjs'),
					'nama'					=> $this->input->post('nama'),
					'sex' 					=> $this->input->post('id_pilihan_kelamin'),
					'jenis_peserta'		 	=> $jenispeserta,
					'tgl_lahir' 			=> $tgl_lahir,
				);
				$simpan=$this->db->insert('data_kegiatan_peserta', $values);
				if($simpan==true){
					die("OK|Data Tersimpan");
				}else{
					 die("Error|Proses data gagal");
				}
			}
			
		}
	}
	function dodel_peserta($kode=0,$id_peserta="",$table=0){
		$this->authentication->verify('eform','del');

		if($this->import_model->delete_entryitem_table($kode,$id_peserta,$table)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}
	function dodel($kode=0){
		$this->authentication->verify('eform','del');

		if($this->import_model->deletealldata($kode)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}
	function send(){
		$this->authentication->verify('eform','add');
      	$data = $this->import_model->bpjs_send_kegiatan();

      	echo $data;
	}

	function resend(){
		$this->authentication->verify('eform','add');
      	$data = $this->import_model->bpjs_resend_kegiatan();

      	echo $data;
	}
	function get_kecamatanfilter(){
		if ($this->input->post('kecamatan')!="null") {
			if($this->input->is_ajax_request()) {
				$kecamatan = $this->input->post('kecamatan');
				$this->session->set_userdata('filter_code_kecamatan',$this->input->post('kecamatan'));
				$kode 	= $this->import_model->get_datawhere($kecamatan,"code","cl_village");

					echo '<option value="">Pilih Keluarahan</option>';
				foreach($kode as $kode) :
					echo $select = $kode->code == set_value('kelurahan') ? 'selected' : '';
					echo '<option value="'.$kode->code.'" '.$select.'>' . $kode->value . '</option>';
				endforeach;

				return FALSE;
			}

			show_404();
		}
	}

	function get_kelurahanfilter(){
		if ($this->input->post('kelurahan')!="null") {
			if($this->input->is_ajax_request()) {
				if ($this->session->set_userdata('filter_code_rukunwarga')!=null) {
					$this->session->set_userdata('filter_code_rukunwarga','');
				}
				$kelurahan = $this->input->post('kelurahan');
				if ($kelurahan=='' || empty($kelurahan)) {
					echo '<option value="">Pilih Rukun Warga</option>';
					if ($this->session->set_userdata('filter_code_kelurahan')!=null) {
						$this->session->set_userdata('filter_code_kelurahan','');
					}
				}else{
					$this->session->set_userdata('filter_code_kelurahan',$this->input->post('kelurahan'));
					$this->db->group_by("rw");
					$kode 	= $this->import_model->get_datawhere($kelurahan,"id_desa","data_keluarga");

						echo '<option value="">Pilih RW</option>';
					foreach($kode as $kode) :
						echo $select = $kode->rw == set_value('rukuwarga') ? 'selected' : '';
						echo '<option value="'.$kode->rw.'" '.$select.'>' . $kode->rw . '</option>';
					endforeach;
				}

				return FALSE;
			}

			show_404();
		}
	}

	

	function get_filterstatushomevisit(){
		if ($this->input->post('statushomevisit')!="null") {
			if($_POST) {
				if($this->input->post('statushomevisit') != '') {
					$this->session->set_userdata('filter_code_statushomevisit',$this->input->post('statushomevisit'));
				}else{
					$this->session->set_userdata('filter_code_statushomevisit','');
				}
			}
		}
	}
	function get_filterbulandata(){
		if ($this->input->post('bulanfilter')!="null") {
			if($_POST) {
				if($this->input->post('bulanfilter') != '') {
					$this->session->set_userdata('filter_code_cl_bulandata',$this->input->post('bulanfilter'));
				}else{
					$this->session->set_userdata('filter_code_cl_bulandata','');
				}
			}
		}
	}
	function get_filtertahundata(){
		if ($this->input->post('tahunfilter')!="null") {
			if($this->input->is_ajax_request()) {
				$tahunfilter = $this->input->post('tahunfilter');
				if ($tahunfilter=='' || empty($tahunfilter) || $tahunfilter=='all') {
					echo '<option value="all">Bulan</option>';
					if ($tahunfilter=='all') {
						$this->session->set_userdata('filter_code_cl_tahundata',$tahunfilter);
						$this->session->set_userdata('filter_code_cl_bulandata','');
					}else{
						$this->session->set_userdata('filter_code_cl_tahundata','');
						$this->session->set_userdata('filter_code_cl_bulandata','');
					}
				}else{
					$bln=array(1=>"Januari","Februari","Maret","April","Mei","Juni","July","Agustus","September","Oktober","November","Desember");
					$this->session->set_userdata('filter_code_cl_tahundata',$this->input->post('tahunfilter'));
					echo '<option value="all">All</option>';
					foreach ($bln as $key => $value) {
						echo $select = $key == set_value('bulanfilter') ? 'selected' : '';
						echo '<option value="'.$key.'" '.$select.'>' . $value . '</option>';
					}
				}

				return FALSE;
			}

			show_404();
		}
	}
}

