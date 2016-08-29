<?php
class Data_kepala_keluarga extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');

		$this->load->model('bpjs');
		$this->load->model('morganisasi_model');
		$this->load->model('eform/datakeluarga_model');
		$this->load->model('eform/pembangunan_keluarga_model');
		$this->load->model('eform/anggota_keluarga_kb_model');
		$this->load->model('eform/dataform_model');

	    $this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
		$this->load->helper('file');
		$this->load->helper('download');
	}
	function downloadimport($value='')
    {
    	ob_clean(); 
		$data = file_get_contents("./public/files/hasil/$value.xlsx"); //assuming my file is on localhost
		$name = "$value.xlsx"; 
		force_download($name,$data);
    }
	function index(){
		$this->authentication->verify('eform','edit');

		$data['title_group'] 	= "eForm - Ketuk Pintu";
		$data['title_form'] 	= "Data Kepala Keluarga";
		$data['dataleveluser'] 	= $this->datakeluarga_model->get_dataleveluser();

		$this->session->set_userdata('filter_code_kecamatan','');
		$this->session->set_userdata('filter_code_kelurahan','');
		$this->session->set_userdata('filter_code_rukunwarga','');
		$this->session->set_userdata('filter_code_cl_rukunrumahtangga','');
		$this->session->set_userdata('filter_code_cl_bulandata','');
		$this->session->set_userdata('filter_code_cl_tahundata','');

		$kode_sess = $this->session->userdata("puskesmas");
		$data['datakecamatan'] = $this->datakeluarga_model->get_datawhere(substr($kode_sess, 0,7),"code","cl_kec");
		
		$data['content'] = $this->parser->parse("eform/datakeluarga/show",$data,true);
		$this->template->show($data,"home");
	}	

	function urut($id_data_keluarga=0){
		$this->authentication->verify('eform','edit');

		$data 			= $this->datakeluarga_model->get_data_row($id_data_keluarga); 
		$data['vacant']	= $this->datakeluarga_model->get_urut_available($data); 

		die($this->parser->parse("eform/datakeluarga/urut",$data));
	}

	function nomor($id_data_keluarga=0,$nomor="000"){
		$this->authentication->verify('eform','edit');
		if($this->datakeluarga_model->nomor($id_data_keluarga,$nomor)){
			echo "OK";
		}else{
			echo "FAILED";
		}
	}

    function dataallexport(){
    	$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

		$this->authentication->verify('eform','show');

    	if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if ($field=="tanggal_pengisian") {
					$this->db->like("tanggal_pengisian",date("Y-m-d",strtotime($value)));
				}else{
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
		if($this->session->userdata('filter_code_rukunwarga') != '') {
			$this->db->where('data_keluarga.rw',$this->session->userdata('filter_code_rukunwarga'));
		}
		if($this->session->userdata('filter_code_cl_rukunrumahtangga') != '') {
			$this->db->where('data_keluarga.rt',$this->session->userdata('filter_code_cl_rukunrumahtangga'));
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
		$rows = $this->datakeluarga_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = $act->id_data_keluarga;
		}
		$keluarga = implode("','", $data);
		// die($keluarga);
		$profile 	 	= $this->datakeluarga_model->get_data_all_profile($keluarga);
		$kb 		 	= $this->datakeluarga_model->get_data_all_kb($keluarga);
		$pembangunan 	= $this->datakeluarga_model->get_data_all_pembangunan($keluarga);
		$anggota 		= $this->datakeluarga_model->get_data_all_anggota($keluarga);
		$anggota_pr 	= $this->datakeluarga_model->get_data_all_anggota_profile($keluarga);
		$rows 			= $this->datakeluarga_model->get_data_all($keluarga);
		$no=1;
		// die(print_r($rows));
		$data_tabel = array();
		foreach($rows as $act) {
			
			$id = $act['id'];
			$no_anggota = $act['no_anggota'];
			$datasumberpendaptan =(isset($profile[$id]['profile_c_2_a_radio']) && $profile[$id]['profile_c_2_a_radio']==1 ? "Pekerjaan;" : "").' '. (isset($profile[$id]['profile_c_2_b_radio']) && $profile[$id]['profile_c_2_b_radio']==1 ? "Sumbangan;" : "").' '.(isset($profile[$id]['profile_c_2_c_radio']) && $profile[$id]['profile_c_2_c_radio']== 1? "Lainnya" : "").' '.((!isset($profile[$id]['profile_c_2_a_radio']) && !isset($profile[$id]['profile_c_2_b_radio']) && !isset($profile[$id]['profile_c_2_c_radio'])) ? 'Tidak' : '');
$data_tabel[] = array(
	'namakepalakeluarga' => $act['namakepalakeluarga'],
	'no'			=> $no++,
	'nama'			=> ($act['nama'] 		=="" || $act['nama'] 		=="-" ? 'Tidak' : $act['nama']),
	'nourutkel'		=> ($act['nourutkel'] 	=="" || $act['nourutkel'] 	=="-" ? 'Tidak' : $act['nourutkel']),
	'nik'			=> ($act['nik'] 		=="" || $act['nik'] 	=="-"? 'Tidak' : $act['nik']),
	'tlp'			=> ($act['no_hp'] 		=="" || $act['no_hp'] 	=="-"?  'Tidak' : $act['no_hp']),
	'tmptlahir'		=> ($act['tmpt_lahir']	=="" || $act['tmpt_lahir'] 	=="-"? 'Tidak' :$act['tmpt_lahir'].", "),
	'tgllahir'		=> ($act['tgl_lahir']	=="" || $act['tgl_lahir'] 	=="-"? 'Tidak' : date("d-m-Y",strtotime($act['tgl_lahir']))),
	'umur'			=> ($act['usia'] 		=="" || $act['usia'] 	=="-"? 'Tidak' : $act['usia']." Thn"),
	'suku'			=> ($act['suku'] 		=="" || $act['usia'] 	=="-" ? "Tidak" : $act['suku']),
	'jmljiwa_l'		=> $act['jml_anaklaki']		!="" ? $act['jml_anaklaki'] : "0",
	'jmljiwa_p'		=> $act['jml_anakperempuan']!="" ? $act['jml_anakperempuan'] : "0",
	'pus_ikutkb'	=> $act['pus_ikutkb']		!="" ? $act['pus_ikutkb'] : "0",
	'pus_tidakikutkb'=> $act['pus_tidakikutkb']!="" ? $act['pus_tidakikutkb'] : "0",
	'beras'			=> isset($profile[$id]['profile_a_1_a_radio']) && $profile[$id]['profile_a_1_a_radio']==1? "Ya" : "Tidak",
	'nonberas'		=> isset($profile[$id]['profile_a_1_b_radio']) && $profile[$id]['profile_a_1_b_radio']==1? "Ya" : "Tidak",
	'sumber_air'	=> (isset($profile[$id]['profile_a_2_a_radio']) && $profile[$id]['profile_a_2_a_radio']==1? "PAM/Ledeng/Kemasan;" : "").' '.(isset($profile[$id]['profile_a_2_b_radio']) && $profile[$id]['profile_a_2_b_radio']==1? "Sumur Terlindung;" : "").' '.(isset($profile[$id]['profile_a_2_c_radio']) && $profile[$id]['profile_a_2_c_radio']==1? "Air Hujan/Sungai" : "").' '.(isset($profile[$id]['profile_a_1_h']) && $profile[$id]['profile_a_1_h'] !=""? $profile[$id]['profile_a_1_h'].';' : "").' '.(isset($profile[$id]['profile_a_2_d_lainnya']) && $profile[$id]['profile_a_2_d_lainnya'] !=""? $profile[$id]['profile_a_2_d_lainnya'].';' : "").' '.((!isset($profile[$id]['profile_a_2_a_radio']) && !isset($profile[$id]['profile_a_2_b_radio']) && !isset($profile[$id]['profile_a_2_c_radio']) && !isset($profile[$id]['profile_a_1_h']) && !isset($profile[$id]['profile_a_2_d_lainnya']) && !isset($profile[$id]['profile_a_1_h']))?'Tidak':''),
	'jamban'		=> isset($profile[$id]['profile_a_3_a_radio']) && $profile[$id]['profile_a_3_a_radio']==1? "Ya" : "Tidak",
	'sampah'		=> isset($profile[$id]['profile_a_4_a_radio']) && $profile[$id]['profile_a_4_a_radio']==1? "Ada" : "Tidak Ada",
	'limbah'		=> isset($profile[$id]['profile_a_5_a_radio']) && $profile[$id]['profile_a_5_a_radio']==1? "Ada" : "Tidak Ada",
	'stiker'		=> isset($profile[$id]['profile_a_6_a_radio']) && $profile[$id]['profile_a_6_a_radio']==1? "Ada" : "Tidak Ada",
	'up4k'			=> isset($profile[$id]['profile_b_1_a_radio']) && $profile[$id]['profile_b_1_a_radio']==1? "Ya" : "Tidak",
	'kesling'		=> isset($profile[$id]['profile_b_2_a_radio']) && $profile[$id]['profile_b_2_a_radio']==1? "Ya" : "Tidak",
	'pancasila'		=> ((isset($profile[$id]['profile_b_3_a_radio'])) ? ($profile[$id]['profile_b_3_a_radio']==1 ? "Ya" : ($profile[$id]['profile_b_3_a_radio']==0 ? "Tidak" : "Tidak")):'Tidak'),
	'kerjabakti'	=> ((isset($profile[$id]['profile_b_4_a_radio'])) ? ($profile[$id]['profile_b_4_a_radio']==1? "Ya" : ($profile[$id]['profile_b_4_a_radio']==0? "Tidak" : "Tidak")):'Tidak'),
	'rukunmati'		=> ((isset($profile[$id]['profile_b_5_a_radio'])) ? ($profile[$id]['profile_b_5_a_radio']==1? "Ya" : ($profile[$id]['profile_b_5_a_radio']==0? "Tidak" : "Tidak")):'Tidak'),
	'keagamaan'		=> ((isset($profile[$id]['profile_b_6_a_radio'])) ? ($profile[$id]['profile_b_6_a_radio']==1? "Ya" : ($profile[$id]['profile_b_6_a_radio']==0? "Tidak" : "Tidak")):'Tidak'),
	'jimpitan'		=> ((isset($profile[$id]['profile_b_7_a_radio'])) ? ($profile[$id]['profile_b_7_a_radio']==1? "Ya" : ($profile[$id]['profile_b_7_a_radio']==0? "Tidak" : "Tidak")):'Tidak'),
	'arisan'		=> ((isset($profile[$id]['profile_b_8_a_radio'])) ? ($profile[$id]['profile_b_8_a_radio']==1? "Ya" : ($profile[$id]['profile_b_8_a_radio']==0? "Tidak" : "Tidak")):'Tidak'),
	'koperasi'		=> ((isset($profile[$id]['profile_b_9_a_radio'])) ? ($profile[$id]['profile_b_9_a_radio']==1? "Ya" : ($profile[$id]['profile_b_9_a_radio']==0? "Tidak" : "Tidak")):'Tidak'),
	'kegiatanlain'	=> isset($profile[$id]['profile_b_10_a_radio']) && $profile[$id]['profile_b_10_a_radio']==1? "Ya" : "Tidak",
	'pendapatan'	=> isset($profile[$id]['profile_c_1_a_jumlah']) && $profile[$id]['profile_c_1_a_jumlah']!=""? $profile[$id]['profile_c_1_a_jumlah'] : "Tidak",
	'sumber_pendapatan'		=> (trim($datasumberpendaptan) == '' ? 'Tidak' : $datasumberpendaptan),
	'hubungan'		=> ((isset($anggota[$id][$no_anggota]['id_pilihan_hubungan'])) ? ($anggota[$id][$no_anggota]['id_pilihan_hubungan']==1? "KK" : ($anggota[$id][$no_anggota]['id_pilihan_hubungan']== 2 ? "Istri" : ($anggota[$id][$no_anggota]['id_pilihan_hubungan']==3? "Anak" : ($anggota[$id][$no_anggota]['id_pilihan_hubungan']==4? "Lain" : "Tidak")))):'Tidak'),
	'jenis_kelamin'	=> ((isset($anggota[$id][$no_anggota]['id_pilihan_kelamin'])) ? ($anggota[$id][$no_anggota]['id_pilihan_kelamin']==5? "L" : ($anggota[$id][$no_anggota]['id_pilihan_kelamin']==6 ? "P" : 'Tidak')):'Tidak'),
	'agama'			=> ((isset($anggota[$id][$no_anggota]['id_pilihan_agama'])) ? ($anggota[$id][$no_anggota]['id_pilihan_agama']==7? "Islam" : ($anggota[$id][$no_anggota]['id_pilihan_agama']==8? "Kristen" : ($anggota[$id][$no_anggota]['id_pilihan_agama']==9? "Katolik" : ($anggota[$id][$no_anggota]['id_pilihan_agama']==10? "Hindu" : ($anggota[$id][$no_anggota]['id_pilihan_agama']==11? "Budha" : ($anggota[$id][$no_anggota]['id_pilihan_agama']==12? "Konghucu" : ($anggota[$id][$no_anggota]['id_pilihan_agama']==13? "Lain" : "Tidak"))))))):'Tidak'),
	'pendidikan'	=> ((isset($anggota[$id][$no_anggota]['id_pilihan_pendidikan'])) ? ($anggota[$id][$no_anggota]['id_pilihan_pendidikan']== 14 ? "Tidak Tamat SD/MI" : ($anggota[$id][$no_anggota]['id_pilihan_pendidikan']==15? "Masih SD/MI" : ($anggota[$id][$no_anggota]['id_pilihan_pendidikan']==16? "Tamat SD/MI" : ($anggota[$id][$no_anggota]['id_pilihan_pendidikan']==17? "Masih SLTP/MTs" : ($anggota[$id][$no_anggota]['id_pilihan_pendidikan']==18? "Tamat SLTP/MTs" : ($anggota[$id][$no_anggota]['id_pilihan_pendidikan']==19? "Masih SLTA/MA" : ($anggota[$id][$no_anggota]['id_pilihan_pendidikan']==20? "Tamat SLTA/MA" : ($anggota[$id][$no_anggota]['id_pilihan_pendidikan']==21? "Masih PT/Akademi" : ($anggota[$id][$no_anggota]['id_pilihan_pendidikan']==22? "Tamat PT/Akademi" : ($anggota[$id][$no_anggota]['id_pilihan_pendidikan']==23? "Tidak/Belum Sekolah" : "Tidak/Belum Sekolah")))))))))):'Tidak/Belum Sekolah'),
	'pekerjaan'		=> ((isset($anggota[$id][$no_anggota]['id_pilihan_pekerjaan'])) ? ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==24? "Petani" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==25? "Nelayan" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==46? "Pedagang" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==26? "PNS/TNI/Porli" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==27? "Pegawai Swasta" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==28? "Wiraswasta" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==29? "Pensiunan" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==30? "Pekerja Lepas" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==31? "Lainnya" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==32? "Tidak/Belum Bekerja" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==42? "Bekerja" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==43? "Belum Bekerja" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==44? "TidakBekerja" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==45? "IRT" : "Tidak")))))))))))))):'Tidak'),
	'status_kawin'	=> ((isset($anggota[$id][$no_anggota]['id_pilihan_kawin'])) ? ($anggota[$id][$no_anggota]['id_pilihan_kawin']==33? "Belum Kawin" : ($anggota[$id][$no_anggota]['id_pilihan_kawin']==34? "Kawin" : ($anggota[$id][$no_anggota]['id_pilihan_kawin']==35? "Janda/Duda" : "Tidak"))) :'Tidak'),
	'usaha_lingkungan'=> ((isset($anggota_pr[$id][$no_anggota]['profile_b_2_a_radio'])) ? ($anggota_pr[$id][$no_anggota]['profile_b_2_a_radio']==1? "Ya" : ($anggota_pr[$id][$no_anggota]['profile_b_2_a_radio']==0 ? "Tidak" : "Tidak")):'Tidak'),
	'bpjsjkn'		=> ((isset($anggota[$id][$no_anggota]['id_pilihan_jkn'])) ? ($anggota[$id][$no_anggota]['id_pilihan_jkn']==36 ? "BPJS-PBI" : ($anggota[$id][$no_anggota]['id_pilihan_jkn']==37? "BPJS-Non PBI" : ($anggota[$id][$no_anggota]['id_pilihan_jkn']==38? "Non BPJS" : ($anggota[$id][$no_anggota]['id_pilihan_jkn']==39? "Tidak Memiliki" : "Tidak")))):'Tidak'),
	'akte_lahir'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_1_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_1_radio']==0? "Ada" : ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_1_radio']==1? "Tidak Ada" : "Tidak")):'Tidak'),
	'wna_status'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_2_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_2_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_2_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'putus_sekolah'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_3_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_3_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_3_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'paud_pernah'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_4_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_4_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_4_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kelompok_bljr'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kelbel_a'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']==0? "A" : "Tidak",
	'kelbel_b'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']==1? "B" : "Tidak",
	'kelbel_c'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']==2? "C" : "Tidak",
	'kelbel_kf'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']==3? "KF" : "Tidak",
	'tabungan_punya'=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_6_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_6_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_6_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'koperasi_punya'=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_7_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_7_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_7_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'subur_usia'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_8_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_8_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_8_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'hamil_status'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_9_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_9_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_9_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'disabilitas_st'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_10_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_10_radio']== 0 ? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_10_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'disabilitas_jenis'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_10_jenisrumah']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_10_jenisrumah']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_10_jenisrumah']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_0_g_10_jenisrumah']) : "Tidak",
	'kb_suami'			=> ((isset($kb[$id]['berencana_II_1_suami']) && $kb[$id]['berencana_II_1_suami']!="") ? (isset($anggota[$id][$no_anggota]['id_pilihan_hubungan']) && ($anggota[$id][$no_anggota]['id_pilihan_hubungan']==1 || $anggota[$id][$no_anggota]['id_pilihan_hubungan']==2 ) ? $kb[$id]['berencana_II_1_suami'] : '0') : "0"),
	'kb_istri'			=> ((isset($kb[$id]['berencana_II_1_istri']) && $kb[$id]['berencana_II_1_istri']!="") ? (isset($anggota[$id][$no_anggota]['id_pilihan_hubungan']) && ($anggota[$id][$no_anggota]['id_pilihan_hubungan']==1 || $anggota[$id][$no_anggota]['id_pilihan_hubungan']==2 ) ? $kb[$id]['berencana_II_1_istri'] : '0') : "0"),
	'kb_lahir_l'		=> isset($kb[$id]['berencana_II_2_laki']) && $kb[$id]['berencana_II_2_laki']!=""? $kb[$id]['berencana_II_2_laki'] : "Tidak",
	'kb_lahir_p'		=> isset($kb[$id]['berencana_II_2_perempuan']) && $kb[$id]['berencana_II_2_perempuan']!=""? $kb[$id]['berencana_II_2_perempuan'] : "Tidak",
	'kb_hidup_l'		=> isset($kb[$id]['berencana_II_2_laki_hidup']) && $kb[$id]['berencana_II_2_laki_hidup']!=""? $kb[$id]['berencana_II_2_laki_hidup'] : "0",
	'kb_hidup_p'		=> isset($kb[$id]['berencana_II_2_perempuan_hidup']) && $kb[$id]['berencana_II_2_perempuan_hidup']!=""? $kb[$id]['berencana_II_2_perempuan_hidup'] : "0",
	'ikut_sertakb'		=> ((isset($kb[$id]['berencana_II_3_kb_radio'])) ? ($kb[$id]['berencana_II_3_kb_radio']==0? "Sedang" : ($kb[$id]['berencana_II_3_kb_radio']==1? "Pernah" : ($kb[$id]['berencana_II_3_kb_radio']==2? "Tidak Pernah" : "Tidak"))):'Tidak'),
	'metode_kb'			=> ((isset($kb[$id]['berencana_II_4_kontrasepsi_sepsi'])) ? ($kb[$id]['berencana_II_4_kontrasepsi_sepsi']==0? "IUD" : ($kb[$id]['berencana_II_4_kontrasepsi_sepsi']==1? "MOW" : ($kb[$id]['berencana_II_4_kontrasepsi_sepsi']==2? "MOP" : ($kb[$id]['berencana_II_4_kontrasepsi_sepsi']==6? "Implan" : ($kb[$id]['berencana_II_4_kontrasepsi_sepsi']==3? "Suntik" : ($kb[$id]['berencana_II_4_kontrasepsi_sepsi']==7? "Pil" : ($kb[$id]['berencana_II_4_kontrasepsi_sepsi']==5? "Pil" : ($kb[$id]['berencana_II_4_kontrasepsi_sepsi']==8? "Tradisional" : "Tidak")))))))):'Tidak'),
	'lama_kb'			=> (isset($kb[$id]['berencana_II_5_tahun']) && $kb[$id]['berencana_II_5_tahun']!=""? $kb[$id]['berencana_II_5_tahun'].' Tahun' : "").' '.(isset($kb[$id]['berencana_II_5_bulan']) && $kb[$id]['berencana_II_5_bulan']!=""? $kb[$id]['berencana_II_5_bulan'].' Bulan' : "").' '.(((!isset($kb[$id]['berencana_II_5_tahun'])) && (!isset($kb[$id]['berencana_II_5_bulan'])) ) ? 'Tidak' : ""),
	'ingin_anak'		=> ((isset($kb[$id]['berencana_II_6_anak_radio'])) ? ($kb[$id]['berencana_II_6_anak_radio']==1 || $kb[$id]['berencana_II_6_anak_radio']==0 ? "Ya" : ($kb[$id]['berencana_II_6_anak_radio']==2? "Tidak" : "Tidak")):'Tidak'),
	'alasan_tdk_kb'		=> (isset($kb[$id]['berencana_II_7_berkb_hamil_cebox']) && $kb[$id]['berencana_II_7_berkb_hamil_cebox']==1? "Sedang Hamil;" : "").' '.(isset($kb[$id]['berencana_II_7_berkb_fertilasi_cebox']) && $kb[$id]['berencana_II_7_berkb_fertilasi_cebox']==1? "Fertilitass" : "").' '.(isset($kb[$id]['berencana_II_7_berkb_tidaksetuju_cebox']) && $kb[$id]['berencana_II_7_berkb_tidaksetuju_cebox']==1? "Tidak Setuju KB" : "").' '.(isset($kb[$id]['berencana_II_7_berkb_tidaktahu_cebox']) && $kb[$id]['berencana_II_7_berkb_tidaktahu_cebox']==1? "Tidak Tahu KB" : "").' '.(isset($kb[$id]['berencana_II_7_berkb_efeksamping_cebox']) && $kb[$id]['berencana_II_7_berkb_efeksamping_cebox']==1? "Takut Efeknya" : "").' '.(isset($kb[$id]['berencana_II_7_berkb_pelayanan_cebox']) && $kb[$id]['berencana_II_7_berkb_pelayanan_cebox']==1? "Pelayanan KB Jauh" : "").' '.(isset($kb[$id]['berencana_II_7_berkb_tidakmampu_cebox']) && $kb[$id]['berencana_II_7_berkb_tidakmampu_cebox']==1? "Tidak Mampu/Mahal" : "").' '.(isset($kb[$id]['berencana_II_7_berkb_lainya_cebox']) && $kb[$id]['berencana_II_7_berkb_lainya_cebox']==1? "Lainnya" : "").' '.((!isset($kb[$id]['berencana_II_7_berkb_hamil_cebox']) && !isset($kb[$id]['berencana_II_7_berkb_fertilasi_cebox']) && !isset($kb[$id]['berencana_II_7_berkb_tidaksetuju_cebox']) && !isset($kb[$id]['berencana_II_7_berkb_tidaktahu_cebox']) && !isset($kb[$id]['berencana_II_7_berkb_efeksamping_cebox']) && !isset($kb[$id]['berencana_II_7_berkb_pelayanan_cebox']) && !isset($kb[$id]['berencana_II_7_berkb_tidakmampu_cebox']) && !isset($kb[$id]['berencana_II_7_berkb_lainya_cebox'])) ? 'Tidak': ''),
	'tempat_kb'			=> ((isset($kb[$id]['berencana_II_8_pelayanan_radkb'])) ? ($kb[$id]['berencana_II_8_pelayanan_radkb']==0? "RSUP/RSUD" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==1? "RSU TNI" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==2? "RS Porli" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==3? "RS Swasta" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==4? "Klinik Umum" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==5? "Puskesmas" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==6? "Klinik Pratama" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==7? "Praktek Dokter" : ($kb[$id]['berencana_II_8_pelayanan_radkb']== 8 ? "RS Prtama" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==9? "Pustu/Pusling/Bides" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==10? "Poskesdes/Polindes" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==11? "Praktek Bidan" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==12? "Pelayanan Bergerak" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==13? "Lainnya" : "Tidak")))))))))))))):'Tidak'),
	'beli_pakain'		=> ((isset($pembangunan[$id]['pembangunan_III_1_radio'])) ? ($pembangunan[$id]['pembangunan_III_1_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_1_radio']==1 ||$pembangunan[$id]['pembangunan_III_1_radio']==2) ? "Tidak" : "Tidak")):'Tidak'),
	'makansehari2x'		=> ((isset($pembangunan[$id]['pembangunan_III_2_radio'])) ? ($pembangunan[$id]['pembangunan_III_2_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_2_radio']==1 ||$pembangunan[$id]['pembangunan_III_2_radio']==2)? "Tidak" : "Tidak")):'Tidak'),
	'berobat_faskes'		=> ((isset($pembangunan[$id]['pembangunan_III_3_radio'])) ?  ($pembangunan[$id]['pembangunan_III_3_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_3_radio']==1 ||$pembangunan[$id]['pembangunan_III_3_radio']==2 )? "Tidak" : "Tidak")):'Tidak'),
	'pakaian_beda'		=> ((isset($pembangunan[$id]['pembangunan_III_4_radio'])) ?($pembangunan[$id]['pembangunan_III_4_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_4_radio']==1 || $pembangunan[$id]['pembangunan_III_4_radio']==2)? "Tidak" : "Tidak")):'Tidak'),
	'pem_daging'		=> ((isset($pembangunan[$id]['pembangunan_III_5_radio'])) ? ($pembangunan[$id]['pembangunan_III_5_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_5_radio']==1||$pembangunan[$id]['pembangunan_III_5_radio']==2 ) ? "Tidak" : "Tidak")):'Tidak'),
	'pem_ibadah'		=> ((isset($pembangunan[$id]['pembangunan_III_6_radio'])) ? ($pembangunan[$id]['pembangunan_III_6_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_6_radio']==1 || $pembangunan[$id]['pembangunan_III_6_radio']==2 ) ? "Tidak" : "Tidak")):'Tidak'),
	'pem_kb_subur'		=> ((isset($pembangunan[$id]['pembangunan_III_7_radio'])) ? ($pembangunan[$id]['pembangunan_III_7_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_7_radio']==1 ||$pembangunan[$id]['pembangunan_III_7_radio']==2) ? "Tidak" : "Tidak")):'Tidak'),
	'pem_tabung'		=> ((isset($pembangunan[$id]['pembangunan_III_8_radio'])) ? ($pembangunan[$id]['pembangunan_III_8_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_8_radio']==1 || $pembangunan[$id]['pembangunan_III_8_radio']==2) ? "Tidak" : "Tidak")):'Tidak'),
	'pem_komunikasi'	=> ((isset($pembangunan[$id]['pembangunan_III_9_radio'])) ? ($pembangunan[$id]['pembangunan_III_9_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_9_radio']==1|| $pembangunan[$id]['pembangunan_III_9_radio']==2) ? "Tidak" : "Tidak")):'Tidak'),
	'pem_sosial'		=> ((isset($pembangunan[$id]['pembangunan_III_10_radio'])) ? ($pembangunan[$id]['pembangunan_III_10_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_10_radio']==1 || $pembangunan[$id]['pembangunan_III_10_radio']==2)? "Tidak" : "Tidak")):'Tidak'),
	'pem_akses'			=> ((isset($pembangunan[$id]['pembangunan_III_11_radio'])) ? ($pembangunan[$id]['pembangunan_III_11_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_11_radio']==1 || $pembangunan[$id]['pembangunan_III_11_radio']==2) ? "Tidak" : "Tidak")):'Tidak'),
	'pem_pengurus'		=> ((isset($pembangunan[$id]['pembangunan_III_12_radio'])) ? ($pembangunan[$id]['pembangunan_III_12_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_12_radio']==1 || $pembangunan[$id]['pembangunan_III_12_radio']==2)? "Tidak" : "Tidak")):'Tidak'),
	'pem_posyandu'		=> ((isset($pembangunan[$id]['pembangunan_III_13_radio'])) ? ($pembangunan[$id]['pembangunan_III_13_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_13_radio']==1|| $pembangunan[$id]['pembangunan_III_13_radio']==2)? "Tidak" : "Tidak")):'Tidak'),
	'pem_bkb'			=> ((isset($pembangunan[$id]['pembangunan_III_14_radio'])) ? ($pembangunan[$id]['pembangunan_III_14_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_14_radio']==1 || $pembangunan[$id]['pembangunan_III_14_radio']==2) ? "Tidak" : "Tidak")):'Tidak'),
	'pem_bkr'			=> ((isset($pembangunan[$id]['pembangunan_III_15_radio'])) ? ($pembangunan[$id]['pembangunan_III_15_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_15_radio']==1 ||$pembangunan[$id]['pembangunan_III_15_radio']==2 ) ? "Tidak" : "Tidak")):'Tidak'),
	'pem_pik'			=> ((isset($pembangunan[$id]['pembangunan_III_16_radio'])) ? ($pembangunan[$id]['pembangunan_III_16_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_16_radio']==1 ||$pembangunan[$id]['pembangunan_III_16_radio']==2)? "Tidak" : "Tidak")):'Tidak'),
	'pem_bkl'			=> ((isset($pembangunan[$id]['pembangunan_III_17_radio'])) ? ($pembangunan[$id]['pembangunan_III_17_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_17_radio']==1|| $pembangunan[$id]['pembangunan_III_17_radio']==2)? "Tidak" : "Tidak")):'Tidak'),
	'pem_uppks'			=> ((isset($pembangunan[$id]['pembangunan_III_18_radio'])) ? ($pembangunan[$id]['pembangunan_III_18_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_18_radio']==1|| $pembangunan[$id]['pembangunan_III_18_radio']==2)? "Tidak" : "Tidak")):'Tidak'),
	'pem_atap_terluas'	=> (isset($pembangunan[$id]['pembangunan_III_1_19_cebo4']) && $pembangunan[$id]['pembangunan_III_1_19_cebo4']==0? "Daun;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_2_19_cebo4']) && $pembangunan[$id]['pembangunan_III_2_19_cebo4']==1? "Seng/Asbes;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_3_19_cebo4']) && $pembangunan[$id]['pembangunan_III_3_19_cebo4']==2? "Genteng;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_4_19_cebo4']) && $pembangunan[$id]['pembangunan_III_4_19_cebo4']==3? "Lainnya;" : "").' '.((!isset($pembangunan[$id]['pembangunan_III_1_19_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_2_19_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_3_19_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_4_19_cebo4']))? 'Tidak':''),
	'pem_dinding_terluas'=> isset($pembangunan[$id]['pembangunan_III_1_20_cebo4']) && $pembangunan[$id]['pembangunan_III_1_20_cebo4']==0? "Tembok;" : "".' '.(isset($pembangunan[$id]['pembangunan_III_2_20_cebo4']) && $pembangunan[$id]['pembangunan_III_2_20_cebo4']==1? "Kayu/Seng;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_3_20_cebo4']) && $pembangunan[$id]['pembangunan_III_3_20_cebo4']==2? "Bambu;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_4_20_cebo4']) && $pembangunan[$id]['pembangunan_III_4_20_cebo4']==3? "Lainnya;" : "").' '.((!isset($pembangunan[$id]['pembangunan_III_1_20_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_2_20_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_3_20_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_4_20_cebo4'])) ? 'Tidak':''),
	'pem_lantai_terluas'	=> (isset($pembangunan[$id]['pembangunan_III_1_21_cebo4']) && $pembangunan[$id]['pembangunan_III_1_21_cebo4']==0? "Ubin/Kramik/Marmer;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_2_21_cebo4']) && $pembangunan[$id]['pembangunan_III_2_21_cebo4']==1? "Semen/Papan;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_3_21_cebo4']) && $pembangunan[$id]['pembangunan_III_3_21_cebo4']==2? "Tanah;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_4_21_cebo4']) && $pembangunan[$id]['pembangunan_III_4_21_cebo4']==3? "Lainnya;" : "").' '.((!isset($pembangunan[$id]['pembangunan_III_1_21_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_2_21_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_3_21_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_4_21_cebo4'])) ? 'Tidak' :''),
	'pem_terang_utama'=> (isset($pembangunan[$id]['pembangunan_III_22_1_cebo4']) && $pembangunan[$id]['pembangunan_III_22_1_cebo4']==0? "Listrik;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_22_2_cebo4']) && $pembangunan[$id]['pembangunan_III_22_2_cebo4']==1? "Genset/Disel;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_22_3_cebo4']) && $pembangunan[$id]['pembangunan_III_22_3_cebo4']==2? "Lampu Minyak;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_22_4_cebo4']) && $pembangunan[$id]['pembangunan_III_22_4_cebo4']==3? "Lainnya;" : "").' '.((!isset($pembangunan[$id]['pembangunan_III_22_1_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_22_2_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_22_3_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_22_4_cebo4'])) ?'Tidak' :''),
	'air_minum'			=>(isset($pembangunan[$id]['pembangunan_III_23_1_cebo4']) && $pembangunan[$id]['pembangunan_III_23_1_cebo4']==0? "Ledeng/Kemasan;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_23_2_cebo4']) && $pembangunan[$id]['pembangunan_III_23_2_cebo4']==1? "Sumur/Pompa;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_23_3_cebo4']) && $pembangunan[$id]['pembangunan_III_23_3_cebo4']==2? "Air hujan/Sungai;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_23_4_cebo4']) && $pembangunan[$id]['pembangunan_III_23_4_cebo4']==3? "Lainnya; " : "").' '.((!isset($pembangunan[$id]['pembangunan_III_23_1_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_23_2_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_23_3_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_23_4_cebo4'])) ? 'Tidak' :''),
	'pem_bakar_bakar'	=> (isset($pembangunan[$id]['pembangunan_III_24_1_cebo4']) && $pembangunan[$id]['pembangunan_III_24_1_cebo4']==0? "Listrik/Gas;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_24_2_cebo4']) && $pembangunan[$id]['pembangunan_III_24_2_cebo4']==1? "Minyak Tanah;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_24_3_cebo4']) && $pembangunan[$id]['pembangunan_III_24_3_cebo4']==2? "Arang/Kayu;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_24_4_cebo4']) && $pembangunan[$id]['pembangunan_III_24_4_cebo4']==3? "Lainnya" : "").' '.((!isset($pembangunan[$id]['pembangunan_III_24_1_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_24_2_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_24_3_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_24_4_cebo4'])) ? 'Tidak' :''),
	'pem_bab_fasilitas'	=> ((isset($pembangunan[$id]['pembangunan_III_25_radi4'])) ? ($pembangunan[$id]['pembangunan_III_25_radi4']==0? "Jamban Sendiri" : ($pembangunan[$id]['pembangunan_III_25_radi4']==1? "Jamban Bersama" : ($pembangunan[$id]['pembangunan_III_25_radi4']==2? "Jamban Umum" : ($pembangunan[$id]['pembangunan_III_25_radi4']==3? "Lainnya" : "Tidak")))):'Tidak'),
	'pem_rumah_milik'	=> (isset($pembangunan[$id]['pembangunan_III_26_1_cebo4']) && $pembangunan[$id]['pembangunan_III_26_1_cebo4']==0? "Sendiri" : "").' '.(isset($pembangunan[$id]['pembangunan_III_26_2_cebo4']) && $pembangunan[$id]['pembangunan_III_26_2_cebo4']==1? "Sewa/Kontrak;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_26_3_cebo4']) && $pembangunan[$id]['pembangunan_III_26_3_cebo4']==2? "Menumpang" : "").' '.(isset($pembangunan[$id]['pembangunan_III_26_4_cebo4']) && $pembangunan[$id]['pembangunan_III_26_4_cebo4']==3? ":Lainnya" : "").' '.((!isset($pembangunan[$id]['pembangunan_III_26_1_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_26_2_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_26_3_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_26_4_cebo4'])) ? 'Tidak' :''),
	'pem_luas'			=> isset($pembangunan[$id]['pembangunan_III_27_luas']) && $pembangunan[$id]['pembangunan_III_27_luas']!=""? $pembangunan[$id]['pembangunan_III_27_luas'] : "Tidak",
	'pem_menetap'		=> isset($pembangunan[$id]['pembangunan_III_28_orang']) && $pembangunan[$id]['pembangunan_III_28_orang']!=""? $pembangunan[$id]['pembangunan_III_28_orang'] : "Tidak",
	'kesehatan_1_g_1_a_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_a_cebox']==1? "Ya" : "Tidak",
	'kesehatan_1_g_1_b_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_b_cebox']==1? "Ya" : "Tidak",
	'kesehatan_1_g_1_c_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_c_cebox']==1? "Ya" : "Tidak",
	'kesehatan_1_g_1_d_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_d_cebox']==1? "Ya" : "Tidak",
	'kesehatan_1_g_1_e_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_e_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_e_cebox']==1? "Ya" : "Tidak",
	'kesehatan_1_g_1_f_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_f_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_f_cebox']==1? "Ya" : "Tidak",
	'kesehatan_bab_lokasi'		=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_radi5'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_radi5']==0? "Jamban" : ($anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_radi5']==1? "Kolam/Sawah/Selokan" : ($anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_radi5']==2? "Sungai/Danau/Laut" : ($anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_radi5']==4? "Pantai/Tanah Lapang/Kebun" : "Tidak")))):'Tidak'),
	'kes_sakit_gigi_ya'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_f_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_f_cebox']==1? "Ya" : "Tidak",
	'kes_sakit_gigi_tidak'	=> !isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_f_cebox']) || $anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_f_cebox']!=1? "Tidak" : "Tidak",
	'kesehatan_1_g_4_a_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_a_cebox']==1? "Ya" : "Tidak",
	'kesehatan_1_g_4_b_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_b_cebox']==1? "Ya" : "Tidak",
	'kesehatan_1_g_4_c_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_c_cebox']==1? "Ya" : "Tidak",
	'kesehatan_1_g_4_d_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_d_cebox']==1? "Ya" : "Tidak",
	'kesehatan_1_g_4_e_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_e_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_e_cebox']==1? "Ya" : "Tidak",
	'kesehatan_1_g_4_f_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_f_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_f_cebox']==1? "Ya" : "Tidak",
	'kes_rokoksebulan'			=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5'])) ? (($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5']==0 || $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5']==1)? "Ya" : (($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5']==2 || $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5']==3 || $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5']==4) ? "Tidak" : "Tidak")):'Tidak'),
	'kes_rokok_setiap'	=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_text'] =="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_text']) : "Tidak"),
	'kes_rokok_pertama'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_text']) : "Tidak",
	'pneumonia_pernah'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radi4'])) ? (($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radi4']==0 || $anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radi4']==1)? "Ya" : (($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radi4']==2 || $anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radi4']==3)? "Tidak" : "Tidak")):'Tidak'),
	'pneumonia_gejala'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radi4'])) ? (($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radi4']==0 || $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radi4']==1)? "Ya" : (($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radi4']==2|| $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radi4']==3)? "Tidak" : "Tidak")):'Tidak'),
	'kesulitangejala_pnomea'	=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_a_cebox']==1? "Napas Cepat;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_b_cebox']==1? "Napas Cuping Hidung" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_c_cebox']==1? "Tarikan dinding dada bawah ke dalam;" : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_b_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_c_cebox']))  ? 'Tidak':''),
	'kes_ginjal'		=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kes_batu'		=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kes_tb_batuk'		=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_tb_radi3'])) ? (($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_tb_radi3']==0 || $anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_tb_radi3']==1)? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_tb_radi3']==2? "Tidak" : "Tidak")):'Tidak'),
	'kesehatan_gejala_batuk'	=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_a_cebox']==1? "Dahak;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_b_cebox']==1? "Darah/Dahak campur darah;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_c_cebox']==1? "Demam;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_d_cebox']==1? "Nyeri Dada;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_e_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_e_cebox']==1? "Sesak Nafas;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_f_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_f_cebox']==1? "Berkeringat malam hari tanpa kegiatan fisik;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_g_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_g_cebox']==1? "Nafsu Makan Menurun;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_h_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_h_cebox']==1? "Berta badan menurun/sulit bertambah" : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_b_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_c_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_d_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_e_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_f_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_g_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_h_cebox']))?'Tidak':''),
	'perludidiagonosa'=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_a_tb_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_a_tb_cebox']==1? "Ya, kurang dari 1 tahun;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_b_tb_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_b_tb_cebox']==1? "Ya, Lebih dari 1 tahun" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_c_tb_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_c_tb_cebox']==1? "Tidak" : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_a_tb_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_b_tb_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_c_tb_cebox'])) ?'Tidak':''),
	'pemeriksaan_tb_gunakan'	=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_4_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_4_a_cebox']==1? "Pemeriksaan dahak; " : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_4_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_4_b_cebox']==1? "Pemeriksaan foto dada (rontgen);" : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_4_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_4_b_cebox'])) ? 'Tidak' :''),
	'kanker_periksa'			=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_1_kk_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_1_kk_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_1_kk_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kanker_thn'				=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_kk_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_kk_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_kk_text'] =="-" ? "Tidak" : $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_kk_text']) : "Tidak",
	'jenis_kanker'				=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_a_cebox']==1? "Leher Rahim (Cervix Uteri);" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_b_cebox']==1? "Payudara;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_c_cebox']==1? "Prostat;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_d_cebox']==1? "Kolorektal/ Usus Besar;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_e_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_e_cebox']==1? "Paru dan Bronkus;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_f_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_f_cebox']==1? "Nasofaring;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_g_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_g_cebox']==1? "GetahBbening" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_h_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_h_text']!=''? ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_h_text']=='-' ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_h_text'].';') : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_b_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_c_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_d_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_e_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_f_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_g_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_h_text'])) ? 'Tidak':''),
	'kanker_tes_iva'			=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kanker_pap_smear'			=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_6_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_6_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_6_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'pengobatan_dijalani'		=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_a_cebox']==1? "Pembedahan/ operasi;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_b_cebox']==1? "Radiasi/ penyinaran;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_c_cebox']==1? "Kemoterapi;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_d_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_d_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_d_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_3_g_d_text']) : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_b_cebox']) &&  !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_c_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_d_text'])) ? 'Tidak' :''),
	'ppok_pernah'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_1_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_1_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_1_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kesehatan_gelaja_sesak'	=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_a_cebox']==1? "Terpapar Udara Dingin;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_b_cebox']==1? "Debu;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_c_cebox']==1? "Asap Rokok" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_d_cebox']==1? "Stress;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_e_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_e_cebox']==1? "Flu atau Infeksi;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_f_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_f_cebox']==1? "Kelelahan;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_g_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_g_cebox']==1? "Alergi obat" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_h_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_h_cebox']==1? "Alergi Makanan;" : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_b_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_c_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_d_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_e_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_f_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_g_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_h_cebox'])) ?'Tidak':''),
	'gejala_sesak_disertai'		=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_a_cebox']==1? "Mengi;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_b_cebox']==1? "Sesak Napas Berkurang atau Menghilang dengan Pengobatan" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_c_cebox']==1? "Sesak Napas Berkurang atau Menghilang tanpa Pengobatan;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_d_cebox']==1? " Sesak Napas Lebih Berat dirasakan pada Malam Hari atau Menjelang Pagi;" : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_b_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_c_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_d_cebox'])) ? 'Tidak' :''),
	'pertama_kali_sesak'		=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_mg_d_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_mg_d_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_mg_d_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_mg_d_text']) : "Tidak"),
	'ppok_kambuh'				=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_radio']))? ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_radio']==0? "Ya" : (isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'diabet_diagnosa'			=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'pengendalian_dm'			=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_a_cebox']==1? "Diet;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_b_cebox']==1? "Olahraga;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_c_cebox']==1? "Minum obat anti diabetik;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_d_cebox']==1? "Injeksi Insulin;" : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_b_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_c_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_d_cebox']))?'Tidak':''),
	'gejala_dm_satubulan'		=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_a_cebox']==1? "Sering lapar;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_b_cebox']==1? "Sering Haus;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_c_cebox']==1? "Sering Buang Air Kecil & Jumlah Banyak" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_d_cebox']==1? "Berat Badan turun;" : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_b_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_c_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_d_cebox'])) ?'Tidak' :''),
	'darting_diagnosa'			=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_hp_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_hp_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_hp_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kesehatan_4_g_2_hp_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_hp_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_hp_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_hp_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_hp_text']) : "Tidak",
	'darting_obat'				=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_hp_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_hp_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_hp_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'jantung_diagnosa'			=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_jk_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_jk_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_jk_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kesehatan_4_g_2_jk_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_jk_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_jk_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_jk_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_jk_text']) : "Tidak",
	'gejala_alami_jantung'		=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_a_cebox']==1? "Nyeri di dalam dada/ rasa tertekan berat/ tidak nyaman di dada ;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_b_cebox']==1? "Nyeri/ tidak nyaman di dada bagian tengah/ dada kiri depan/ menjalar ke lengan kiri;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_c_cebox']==1? "Nyeri/ tidak nyaman di dada dirasakan waktu endaki/ naik tangga/ berjalan tergesa-gesa;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_d_cebox']==1? "Nyeri/ tidak nyaman di dada hilang ketika menghentikan aktivitas/ istirahat" : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_b_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_c_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_d_cebox'])) ? 'Tidak' :''),
	'stroke_diagnosa'			=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_sk_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_sk_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_sk_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kesehatan_4_g_2_sk_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_sk_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_sk_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_sk_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_sk_text']) : "Tidak",
	'gejala_struke_mendadak'	=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_a_cebox']==1? "Kelumpuhan pada satu sisi tubuh;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_b_cebox']==1? "Kesemutan atau baal satu sisi tubuh;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_c_cebox']==1? " Mulut jadi mencong tanpa kelumpuhan otot mata;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_d_cebox']==1? "Bicara pelo;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_e_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_e_cebox']==1? "Sulit bicara/ komunikasi dan/atau tidak mengerti pembicaraan;" : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_b_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_c_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_d_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_e_cebox'])) ? 'Tidak' : ''),
	'kesehatan_5_g_1_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_1_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_1_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_2_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_2_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_2_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_3_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_3_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_3_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_4_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_4_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_4_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_5_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_5_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_5_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_6_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_6_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_6_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_7_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_7_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_7_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_8_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_8_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_8_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_9_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_9_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_9_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_10_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_10_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_10_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_11_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_11_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_11_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_12_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_12_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_12_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_13_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_13_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_13_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_14_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_14_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_14_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_15_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_15_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_15_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_17_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_17_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_17_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_18_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_18_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_18_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_19_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_19_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_19_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_20_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_20_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_20_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_23_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_23_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_23_kk_cebox']==1? "Ya" : "Tidak",
	'semua_20_obat'				=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_21_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_5_g_21_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_5_g_21_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'pernah_obat_faskes'		=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_22_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_5_g_22_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_5_g_22_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'stat_imunisasi'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']==0? "Lengkap" : (isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']==1? "Tidak tahu" : (isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']==2? "Lengkap sesuai umur" : (isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']==3? "Tidak lengkap" : "Tidak"))),
	'kesehatan_6_g_2_ol_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_ol_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_ol_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_ol_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_ol_text']) : "Tidak",
	'kesehatan_6_g_2_td_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_td_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_td_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_td_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_td_text']) : "Tidak",
	'kesehatan_6_g_3_td_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_td_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_td_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_td_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_td_text']) : "Tidak",
	'kesehatan_6_g_3_tn_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_tn_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_tn_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_tn_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_tn_text']) : "Tidak",
	'kesehatan_6_g_3_p_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_p_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_p_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_p_text'] =="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_p_text']) : "Tidak",
	'kesehatan_6_g_3_s_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_s_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_s_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_s_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_s_text']) : "Tidak",
	'kesehatan_6_g_4_at_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_at_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_at_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_at_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_at_text']) : "Tidak",
	'kesehatan_6_g_4_bb_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_bb_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_bb_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_bb_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_bb_text']) : "Tidak",
	'kesehatan_6_g_4_sg_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_sg_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_sg_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_sg_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_sg_text']) : "Tidak",
	'kesehatan_konjungtiva'		=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_5_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_5_radio']==0? "Pucat" : ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_5_radio']==1? "Normal" : "Tidak")):'Tidak'),
	'kesehatan_6_g_6_text'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_6_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_6_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_6_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_6_g_6_text']) : "Tidak",
	'kesehatan_6_g_7_text'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_7_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_7_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_7_text']=="-" ? 'Tidak' :$anggota_pr[$id][$no_anggota]['kesehatan_6_g_7_text']) : "Tidak",
);
		}
// die(print_r($data_tabel));
		$kode='P'.$this->session->userdata('puskesmas');
		$kd_prov = $this->morganisasi_model->get_nama('value','cl_province','code',substr($kode, 1,2));
		$kd_kab  = $this->morganisasi_model->get_nama('value','cl_district','code',substr($kode, 1,4));
		$nama  = $this->morganisasi_model->get_nama('value','cl_phc','code',$kode);

		if ($this->input->post('kecamatan')!='' || $this->input->post('kecamatan')!='null') {
			$kecamatan = $this->input->post('kecamatan');
		}else{
			$kecamatan = '-';
		}
		if ($this->input->post('kelurahan')!='' || $this->input->post('kelurahan')!='null') {
			$kelurahan = $this->input->post('kelurahan');
		}else{
			$kelurahan = '-';
		}
		
		$tanggal_export = date("d-m-Y");
		$data_puskesmas[] = array('nama_puskesmas' => $nama,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'tanggal_export' => $tanggal_export,'kd_kab' => $kd_kab,'kecamatan' => strtoupper($kecamatan),'kelurahan' => $kelurahan);
		
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/data_detailall.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_kpldh_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}

    function datakepalakeluaraexport(){
    	$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

		$this->authentication->verify('eform','show');

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
		
		if($this->session->userdata('filter_code_kelurahan') != '') {
			$this->db->where('data_keluarga.id_desa',$this->session->userdata('filter_code_kelurahan'));
		}
		 if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('data_keluarga.id_kecamatan',$this->session->userdata('filter_code_kecamatan'));
		}
		if($this->session->userdata('filter_code_rukunwarga') != '') {
			$this->db->where('data_keluarga.rw',$this->session->userdata('filter_code_rukunwarga'));
		}
		if($this->session->userdata('filter_code_cl_rukunrumahtangga') != '') {
			$this->db->where('data_keluarga.rt',$this->session->userdata('filter_code_cl_rukunrumahtangga'));
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
		$rows_all = $this->datakeluarga_model->get_data_export();

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
		
		if($this->session->userdata('filter_code_kelurahan') != '') {
			$this->db->where('data_keluarga.id_desa',$this->session->userdata('filter_code_kelurahan'));
		}
		 if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('data_keluarga.id_kecamatan',$this->session->userdata('filter_code_kecamatan'));
		}
		if($this->session->userdata('filter_code_rukunwarga') != '') {
			$this->db->where('data_keluarga.rw',$this->session->userdata('filter_code_rukunwarga'));
		}
		if($this->session->userdata('filter_code_cl_rukunrumahtangga') != '') {
			$this->db->where('data_keluarga.rt',$this->session->userdata('filter_code_cl_rukunrumahtangga'));
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
		$rows = $this->datakeluarga_model->get_data_export(/*$this->input->post('recordstartindex'), $this->input->post('pagesize')*/);
		$no=1;
		$data_tabel = array();
		foreach($rows as $act) {
			$data_tabel[] = array(
				'no'					=> $no++,
				'id_data_keluarga'		=> $act->id_data_keluarga,
				'tanggal_pengisian'		=> $act->tanggal_pengisian,
				'jam_data'				=> $act->jam_data,
				'alamat'				=> $act->alamat,
				'id_propinsi'			=> $act->id_propinsi,
				'id_kota'				=> $act->id_kota,
				'id_kecamatan'			=> $act->id_kecamatan,
				'value'					=> $act->value,
				'rt'					=> $act->rt,
				'rw'					=> $act->rw,
				'norumah'				=> $act->norumah,
				'nourutkel'				=> $act->nourutkel,
				'id_kodepos'			=> $act->id_kodepos,
				'namakepalakeluarga'	=> $act->namakepalakeluarga,
				'notlp'					=> $act->notlp,
				'namadesawisma'			=> $act->namadesawisma,
				'id_pkk'				=> $act->id_pkk,
				'nama_komunitas'		=> $act->nama_komunitas,
				'laki'					=> $act->laki,
				'pr'					=> $act->pr,
				'jmljiwa'				=> $act->jmljiwa,
				'edit'					=> 1,
				'delete'				=> 1
			);
		}

				$kode='P '.$this->session->userdata('puskesmas');
				$kd_prov = $this->morganisasi_model->get_nama('value','cl_province','code',substr($kode, 2,2));
				$kd_kab  = $this->morganisasi_model->get_nama('value','cl_district','code',substr($kode, 2,4));
				$nama  = $this->morganisasi_model->get_nama('value','cl_phc','code',$kode);
				$kd_kec  = 'KEC. '.$this->morganisasi_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));
				$kd_upb  = 'KEC. '.$this->morganisasi_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));

		if ($this->input->post('kecamatan')!='' || $this->input->post('kecamatan')!='null' ) {
			$kecamatan = $this->input->post('kecamatan');

		}else{
			$kecamatan = '-';
		}
		if ($this->input->post('kelurahan')!='' && $this->input->post('kelurahan')!='null' && $this->input->post('kelurahan') !='Pilih Keluarahan') {
			$kelurahan = $this->input->post('kelurahan');
		}else{
			$kelurahan = '-';
		}
		if ($this->input->post('rukunwarga')!='' && $this->input->post('rukunwarga')!='null' && $this->input->post('rukunwarga')!='Pilih RW') {
			$rukunwarga = $this->input->post('rukunwarga');
		}else{
			$rukunwarga = '-';
		}
		if ($this->input->post('rukunrumahtangga')!='' && $this->input->post('rukunrumahtangga')!='null' && $this->input->post('rukunrumahtangga')!='Pilih RT') {
			$rukunrumahtangga = $this->input->post('rukunrumahtangga');
		}else{
			$rukunrumahtangga = '-';
		}
		if ($this->input->post('tahunfilter')!='' || $this->input->post('tahunfilter')!='null') {
			$tahunfilter = $this->input->post('tahunfilter');
		}else{
			$tahunfilter = date("Y");
		}
		if ($this->input->post('bulanfilter')!='' || $this->input->post('bulanfilter')!='null') {
			$bulanfilter = $this->input->post('bulanfilter');
		}else{
			$bulanfilter = date("M");
		}
		$tanggal_export = date("d-m-Y");
		$kodekecamatan 	= $this->input->post('kodekecamatan');
		$kodedesa 	   	= $this->input->post('kodedesa');
		$koderw 		= $this->input->post('koderw');
		$kodert 		= $this->input->post('kodert');
		$kodetahun 		= $this->input->post('kodetahun');
		$kodebulan 		= $this->input->post('kodebulan');
		if ($kodekecamatan!='null' && $kodekecamatan !='' && isset($kodekecamatan)) {
			$this->db->where('data_keluarga.id_kecamatan',$kodekecamatan);
		}
		if ($kodedesa != 'null' && $kodedesa !='' && isset($kodedesa)) {
			$this->db->where('data_keluarga.id_desa',$kodedesa);
		}
		if ($koderw != 'null' && $koderw !='' && isset($koderw)) {
			$this->db->where('data_keluarga.rw',$koderw);
		}
		if ($kodert != 'null' && $kodert !='' && isset($kodert)) {
			$this->db->where('data_keluarga.rt',$kodert);
		}
		if ($kodetahun != 'null' && $kodetahun !='' && isset($kodetahun)) {
			$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$kodetahun);	
		}
		if ($kodebulan != 'null' && $kodebulan !='' && isset($kodebulan)) {
			$this->db->where('MONTH(data_keluarga.tanggal_pengisian)',$kodebulan);
		}
		$datajml = $this->datakeluarga_model->datajml();

		$jumlahjiwa = $datajml['jml_jiwa'];
		$jumlahlaki = $datajml['jml_laki'];
		$jumlahkk = $datajml['jml_kk'];
		$jumlahperempuan = $datajml['jml_perempuan'];
		$data_puskesmas[] = array('nama_puskesmas' => $nama,'kecamatan' => $kecamatan,'kelurahan' => $kelurahan,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'tanggal_export' => $tanggal_export,'kd_kab' => $kd_kab,'rw' => $rukunwarga,'rt' => $rukunrumahtangga,'tahunfilter' => $tahunfilter,'bulanfilter' => $bulanfilter,'jumlahjiwa' => $jumlahjiwa,'jumlahlaki' => $jumlahlaki,'jumlahperempuan' => $jumlahperempuan,'jumlahkk' => $jumlahkk);
		
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/data_kepala_keluarga.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_ketukpintu_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}

	function json(){
		$this->authentication->verify('eform','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);
				if ($field=="tanggal_pengisian") {
					$this->db->like("tanggal_pengisian",date("Y-m-d",strtotime($value)));
				}else{
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
		if($this->session->userdata('filter_code_rukunwarga') != '') {
			$this->db->where('data_keluarga.rw',$this->session->userdata('filter_code_rukunwarga'));
		}
		if($this->session->userdata('filter_code_cl_rukunrumahtangga') != '') {
			$this->db->where('data_keluarga.rt',$this->session->userdata('filter_code_cl_rukunrumahtangga'));
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
		$rows_all = $this->datakeluarga_model->get_data();

    	if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if ($field=="tanggal_pengisian") {
					$this->db->like("tanggal_pengisian",date("Y-m-d",strtotime($value)));
				}else{
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
		if($this->session->userdata('filter_code_rukunwarga') != '') {
			$this->db->where('data_keluarga.rw',$this->session->userdata('filter_code_rukunwarga'));
		}
		if($this->session->userdata('filter_code_cl_rukunrumahtangga') != '') {
			$this->db->where('data_keluarga.rt',$this->session->userdata('filter_code_cl_rukunrumahtangga'));
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
		$rows = $this->datakeluarga_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_data_keluarga'		=> $act->id_data_keluarga,
				'tanggal_pengisian'		=> $act->tanggal_pengisian,
				'jam_data'				=> $act->jam_data,
				'alamat'				=> $act->alamat,
				'id_propinsi'			=> $act->id_propinsi,
				'id_kota'				=> $act->id_kota,
				'id_kecamatan'			=> $act->id_kecamatan,
				'value'					=> $act->value,
				'rt'					=> $act->rt,
				'rw'					=> $act->rw,
				'norumah'				=> $act->norumah,
				'nourutkel'				=> $act->nourutkel,
				'id_kodepos'			=> $act->id_kodepos,
				'namakepalakeluarga'	=> $act->namakepalakeluarga,
				'notlp'					=> $act->notlp,
				'namadesawisma'			=> $act->namadesawisma,
				'id_pkk'				=> $act->id_pkk,
				'nama_komunitas'		=> $act->nama_komunitas,
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

	function json_anggotaKeluarga($anggota){
		$this->authentication->verify('eform','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);
				if($field=="tgl_lahir"){
					$this->db->like("tgl_lahir",date("Y-m-d",strtotime($value)));
				}else{
					$this->db->like($field,$value);	
				}
				
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		$this->db->where("data_keluarga_anggota.id_data_keluarga",$anggota);
		$rows_all = $this->datakeluarga_model->get_data_anggotaKeluarga();

    	if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field=="tgl_lahir"){
					$this->db->like("tgl_lahir",date("Y-m-d",strtotime($value)));
				}else{
					$this->db->like($field,$value);	
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		$this->db->where("data_keluarga_anggota.id_data_keluarga",$anggota);
		$rows = $this->datakeluarga_model->get_data_anggotaKeluarga($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_data_keluarga'		=> $act->id_data_keluarga,
				'no_anggota'			=> $act->no_anggota,
				'nama'					=> $act->nama,
				'nik'					=> $act->nik,
				'tmpt_lahir'			=> $act->tmpt_lahir,
				'tgl_lahir'				=> $act->tgl_lahir,
				'id_pilihan_hubungan'	=> $act->id_pilihan_hubungan,
				'id_pilihan_kelamin'	=> $act->id_pilihan_kelamin,
				'id_pilihan_agama'		=> $act->id_pilihan_agama,
				'id_pilihan_pendidikan'	=> $act->id_pilihan_pendidikan,
				'id_pilihan_pekerjaan'	=> $act->id_pilihan_pekerjaan,
				'id_pilihan_kawin'		=> $act->id_pilihan_kawin,
				'id_pilihan_jkn'		=> $act->id_pilihan_jkn,
				'jeniskelamin'			=> $act->jeniskelamin,
				'hubungan'				=> $act->hubungan,
				'bpjs'					=> $act->bpjs,
				'usia'					=> $act->usia,
				'suku'					=> $act->suku,
				'no_hp'					=> $act->no_hp,
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

	function json_anggotaKeluargaexport($anggota){
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		$this->authentication->verify('eform','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);
				if($field=="tgl_lahir"){
					$this->db->like("tgl_lahir",date("Y-m-d",strtotime($value)));
				}else{
					$this->db->like($field,$value);	
				}
				
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		$this->db->where("data_keluarga_anggota.id_data_keluarga",$anggota);
		$rows_all = $this->datakeluarga_model->get_data_anggotaKeluarga();

    	if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field=="tgl_lahir"){
					$this->db->like("tgl_lahir",date("Y-m-d",strtotime($value)));
				}else{
					$this->db->like($field,$value);	
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		$this->db->where("data_keluarga_anggota.id_data_keluarga",$anggota);
		$rows = $this->datakeluarga_model->get_data_anggotaKeluarga();
		$no=1;
		$data_tabel = array();
		foreach($rows as $act) {
			$data_tabel[] = array(
				'no'					=> $no++,
				'id_data_keluarga'		=> $act->id_data_keluarga,
				'no_anggota'			=> $act->no_anggota,
				'nama'					=> $act->nama,
				'nik'					=> $act->nik,
				'tmpt_lahir'			=> $act->tmpt_lahir,
				'tgl_lahir'				=> $act->tgl_lahir,
				'id_pilihan_hubungan'	=> $act->id_pilihan_hubungan,
				'id_pilihan_kelamin'	=> $act->id_pilihan_kelamin,
				'id_pilihan_agama'		=> $act->id_pilihan_agama,
				'id_pilihan_pendidikan'	=> $act->id_pilihan_pendidikan,
				'id_pilihan_pekerjaan'	=> $act->id_pilihan_pekerjaan,
				'id_pilihan_kawin'		=> $act->id_pilihan_kawin,
				'id_pilihan_jkn'		=> $act->id_pilihan_jkn,
				'jeniskelamin'			=> $act->jeniskelamin,
				'hubungan'				=> $act->hubungan,
				'bpjs'					=> $act->bpjs,
				'usia'					=> $act->usia,
				'suku'					=> $act->suku,
				'agama'					=> $act->agama,
				'pendidikan'			=> $act->pendidikan,
				'pekerjaan'				=> $act->pekerjaan,
				'kawin'					=> $act->kawin,
				'jkn'					=> $act->jkn,
				'no_hp'					=> $act->no_hp,
				'edit'					=> 1,
				'delete'				=> 1
			);
		}

		
		$kode='P'.$this->session->userdata('puskesmas');
				$kd_prov = $this->morganisasi_model->get_nama('value','cl_province','code',substr($kode, 1,2));
				$kd_kab  = $this->morganisasi_model->get_nama('value','cl_district','code',substr($kode, 1,4));
				$nama  = $this->morganisasi_model->get_nama('value','cl_phc','code',$kode);
				$kd_kec  = 'KEC. '.$this->morganisasi_model->get_nama('nama','cl_kec','code',substr($kode, 1,7));
				$kd_upb  = 'KEC. '.$this->morganisasi_model->get_nama('nama','cl_kec','code',substr($kode, 1,7));
				
		
		$datadetail = $this->datakeluarga_model->get_data_export_detail($anggota);
		$desa  = $this->morganisasi_model->get_nama('value','cl_village','code',$datadetail['id_desa']);
		$tanggal_export = date("d-m-Y");
		$data_puskesmas[] = array('nama_puskesmas' => $nama,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'tanggal_export' => $tanggal_export,'kd_kab' => $kd_kab,
			'kepala_keluarga' => $datadetail['namakepalakeluarga'],'kecamatan' => $kd_kec,'desa' => $desa,'rw' => $datadetail['rw'],'rt' => $datadetail['rt'],'norumah' => $datadetail['norumah'],'kodepos' => $datadetail['id_kodepos'],'pendata' => $datadetail['nama_pendata'],'koordinator' => $datadetail['nama_koordinator'],'alamat' => $datadetail['alamat']);
		
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/anggotakeluarga.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_anggotakeluarga_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}

	function adddataform_profile(){
		 $action = $this->dataform_model->insertdataform_profile();
		 die("$action");
	}

    function export_template(){
    	ob_clean(); 
		$data = file_get_contents("./public/files/template/templateImport.xlsx"); //assuming my file is on localhost
		$name = 'templateImport.xlsx'; 
		force_download($name,$data);
    }
    function doimportdataok($excel=''){

        $inputFileName = "./assets/$excel";
        try {
                $inputFileType = IOFactory::identify($inputFileName);
                $objReader = IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);

            } catch(Exception $e) {
            	$this->session->set_flashdata('alert_fail', 'Silahkan Tentukan File Terlebih Dahulu');
	    		redirect(base_url()."eform/data_kepala_keluarga/import_add");
                die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
            }
 
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow    = $sheet->getHighestDataRow();
            $highestColumn = $sheet->getHighestDataColumn();
            $temp = array();
            $data = array();
            $provinsi 		= $this->input->post('provinsi');
            $kota 			= $this->input->post('kota');
            $id_kecamatan 	= $this->input->post('id_kecamatan');
            $kelurahan 		= $this->input->post('kelurahan');
            $tgl_pengisian 	= date("y-m-d");
            $jam_data 		= date("H:i:s");
            for ($row = 2; $row <= $highestRow; $row++){ 
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
                // echo $highestColumn;
        	    $kk[$rowData[0][3]]['nourutkel']			= $rowData[0][3];
        	    $kk[$rowData[0][3]]['tanggal_pengisian']	= $tgl_pengisian;
        	    $kk[$rowData[0][3]]['jam_data']				= $jam_data;
        	    $kk[$rowData[0][3]]['alamat']				= $rowData[0][6];
        	    $kk[$rowData[0][3]]['id_propinsi']			= $provinsi;
        	    $kk[$rowData[0][3]]['id_kota']				= $kota;
        	    $kk[$rowData[0][3]]['id_kecamatan']			= $id_kecamatan;
        	    $kk[$rowData[0][3]]['id_desa']				= $kelurahan;
        	    $kk[$rowData[0][3]]['id_kodepos']			= $rowData[0][5];
        	    $kk[$rowData[0][3]]['rw']					= $rowData[0][1];
        	    $kk[$rowData[0][3]]['rt']					= $rowData[0][2];
        	    $kk[$rowData[0][3]]['norumah']				= $rowData[0][4];
        	    $kk[$rowData[0][3]]['namakepalakeluarga']	= $rowData[0][8];
        	    $kk[$rowData[0][3]]['notlp']				= $rowData[0][9];
        	    $kk[$rowData[0][3]]['namadesawisma']		= $rowData[0][10];
        	    $kk[$rowData[0][3]]['nama_komunitas']		= $rowData[0][7];
        	    $kk[$rowData[0][3]]['id_pkk']				= (strtolower($rowData[0][3])=='kk' ? '2' : (strtolower($rowData[0][3])=='istri' ? '3' : '1'));
        	    $kk[$rowData[0][3]]['nama_koordinator']		= $rowData[0][15];
        	    $kk[$rowData[0][3]]['nama_pendata']			= $rowData[0][16];
        	    $kk[$rowData[0][3]]['jam_selesai']			= $jam_data;
        	    $kk[$rowData[0][3]]['jml_anaklaki']			= $rowData[0][11];
        	    $kk[$rowData[0][3]]['jml_anakperempuan']	= $rowData[0][12];
        	    $kk[$rowData[0][3]]['pus_ikutkb']			= $rowData[0][13];
        	    $kk[$rowData[0][3]]['pus_tidakikutkb']		= $rowData[0][14];
        	    //profil keluarga
        	    $kk[$rowData[0][3]]['profkel_mpberas']		= $rowData[0][31];
        	    $kk[$rowData[0][3]]['profkel_mpnonberas']	= $rowData[0][32];
        	    $kk[$rowData[0][3]]['profkel_mpmieinstan']	= $rowData[0][33];
        	    $kk[$rowData[0][3]]['profkel_mpcepatsaji']	= $rowData[0][34];
        	    $kk[$rowData[0][3]]['profkel_mpdonat']		= $rowData[0][35];
        	    $kk[$rowData[0][3]]['profkel_mpbiskuit']	= $rowData[0][36];
        	    $kk[$rowData[0][3]]['profkel_mpgorengan']	= $rowData[0][37];
        	    $kk[$rowData[0][3]]['profkel_mplainnya']	= $rowData[0][38];
        	    $kk[$rowData[0][3]]['profkel_sapam']		= $rowData[0][39];
        	    $kk[$rowData[0][3]]['profkel_sasumur']		= $rowData[0][40];
        	    $kk[$rowData[0][3]]['profkel_saairhujan']	= $rowData[0][41];
        	    $kk[$rowData[0][3]]['profkel_salainnya']	= $rowData[0][42];
        	    $kk[$rowData[0][3]]['profkel_jmbnsendiri']	= $rowData[0][43];
        	    $kk[$rowData[0][3]]['profkel_buangsampah']	= $rowData[0][44];
        	    $kk[$rowData[0][3]]['profkel_buangairlmbh']	= $rowData[0][45];
        	    $kk[$rowData[0][3]]['profkel_stikerp4k']	= $rowData[0][46];
        	    $kk[$rowData[0][3]]['profkel_up2k']			= $rowData[0][47];
        	    $kk[$rowData[0][3]]['profkel_kesehatanling']= $rowData[0][48];
        	    $kk[$rowData[0][3]]['profkel_ppp']			= $rowData[0][49];
        	    $kk[$rowData[0][3]]['profkel_kerjabakti']	= $rowData[0][50];
        	    $kk[$rowData[0][3]]['profkel_rknkematian']	= $rowData[0][51];
        	    $kk[$rowData[0][3]]['profkel_keagamaan']	= $rowData[0][52];
        	    $kk[$rowData[0][3]]['profkel_jimpitan']		= $rowData[0][53];
        	    $kk[$rowData[0][3]]['profkel_arisan']		= $rowData[0][54];
        	    $kk[$rowData[0][3]]['profkel_koperasi']		= $rowData[0][55];
        	    $kk[$rowData[0][3]]['profkel_kklainnya']	= $rowData[0][56];
        	    $kk[$rowData[0][3]]['profkel_pendapatan']	= $rowData[0][57];
        	    $kk[$rowData[0][3]]['profkel_pekerjaan']	= $rowData[0][58];
        	    $kk[$rowData[0][3]]['profkel_sumbangan']	= $rowData[0][59];
        	    $kk[$rowData[0][3]]['profkel_ekonomilain']	= $rowData[0][60];
        	    //KB
        	    $kk[$rowData[0][3]]['kb_usiakawinsuami']	= $rowData[0][61];
        	    $kk[$rowData[0][3]]['kb_usiakawinistri']	= $rowData[0][62];
        	    $kk[$rowData[0][3]]['kb_jmlanaklaki']		= $rowData[0][63];
        	    $kk[$rowData[0][3]]['kb_jmlanakperempuan']	= $rowData[0][64];
        	    $kk[$rowData[0][3]]['kb_kepesetaankb']		= $rowData[0][65];
        	    $kk[$rowData[0][3]]['kb_metodekb']			= $rowData[0][66];
        	    $kk[$rowData[0][3]]['kb_lamakbtahun']		= $rowData[0][67];
        	    $kk[$rowData[0][3]]['kb_lamakbbulan']		= $rowData[0][68];
        	    $kk[$rowData[0][3]]['kb_inginpunyaanak']	= $rowData[0][69];
        	    $kk[$rowData[0][3]]['kb_akbhamil']			= $rowData[0][70];
        	    $kk[$rowData[0][3]]['kb_akbfertilitas']		= $rowData[0][71];
        	    $kk[$rowData[0][3]]['kb_akbtidaksetuju']	= $rowData[0][72];
        	    $kk[$rowData[0][3]]['kb_akbtidaktahu']		= $rowData[0][73];
        	    $kk[$rowData[0][3]]['kb_akbtakutefeksamping']= $rowData[0][74];
        	    $kk[$rowData[0][3]]['kb_akbjauh']			= $rowData[0][75];
        	    $kk[$rowData[0][3]]['kb_akbmahal']			= $rowData[0][76];
        	    $kk[$rowData[0][3]]['kb_akblainnya']		= $rowData[0][77];
        	    $kk[$rowData[0][3]]['kb_tmptpelayanan']	= $rowData[0][78];
        	    //pembangunan
        	    $kk[$rowData[0][3]]['pembangunan_satusetel1tahun']	= $rowData[0][79];
        	    $kk[$rowData[0][3]]['pembangunan_makan2minggu']		= $rowData[0][80];
        	    $kk[$rowData[0][3]]['pembangunan_sakitfayankes']	= $rowData[0][81];
        	    $kk[$rowData[0][3]]['pembangunan_bedabaju']			= $rowData[0][82];
        	    $kk[$rowData[0][3]]['pembangunan_telursatuminggu']	= $rowData[0][83];
        	    $kk[$rowData[0][3]]['pembangunan_beribadah']		= $rowData[0][84];
        	    $kk[$rowData[0][3]]['pembangunan_usiasuburkb']		= $rowData[0][85];
        	    $kk[$rowData[0][3]]['pembangunan_emasatujuta']		= $rowData[0][86];
        	    $kk[$rowData[0][3]]['pembangunan_komunikasi']		= $rowData[0][87];
        	    $kk[$rowData[0][3]]['pembangunan_sosialrt']			= $rowData[0][88];
        	    $kk[$rowData[0][3]]['pembangunan_koranradio']		= $rowData[0][89];
        	    $kk[$rowData[0][3]]['pembangunan_kegiatansos']		= $rowData[0][90];
        	    $kk[$rowData[0][3]]['pembangunan_ikutposyandu']		= $rowData[0][91];
        	    $kk[$rowData[0][3]]['pembangunan_ikutbkb']		= $rowData[0][92];
        	    $kk[$rowData[0][3]]['pembangunan_ikutbkr']		= $rowData[0][93];
        	    $kk[$rowData[0][3]]['pembangunan_ikutpik']		= $rowData[0][94];
        	    $kk[$rowData[0][3]]['pembangunan_ikutbkl']		= $rowData[0][95];
        	    $kk[$rowData[0][3]]['pembangunan_uppks']		= $rowData[0][96];
        	    $kk[$rowData[0][3]]['pembangunan_jadaun']		= $rowData[0][97];
        	    $kk[$rowData[0][3]]['pembangunan_jaseng']		= $rowData[0][98];
        	    $kk[$rowData[0][3]]['pembangunan_jagenteng']	= $rowData[0][99];
        	    $kk[$rowData[0][3]]['pembangunan_jalainnya']	= $rowData[0][100];
        	    $kk[$rowData[0][3]]['pembangunan_jdtembok']		= $rowData[0][101];
        	    $kk[$rowData[0][3]]['pembangunan_jdkayu']		= $rowData[0][102];
        	    $kk[$rowData[0][3]]['pembangunan_jdbambbu']		= $rowData[0][103];
        	    $kk[$rowData[0][3]]['pembangunan_jdlainnya']	= $rowData[0][104];
        	    $kk[$rowData[0][3]]['pembangunan_jlubin']		= $rowData[0][105];
        	    $kk[$rowData[0][3]]['pembangunan_jlsemen']		= $rowData[0][106];
        	    $kk[$rowData[0][3]]['pembangunan_jltanah']		= $rowData[0][107];
				$kk[$rowData[0][3]]['pembangunan_jllain']		= $rowData[0][108];
				$kk[$rowData[0][3]]['pembangunan_splistrik']	= $rowData[0][109];
				$kk[$rowData[0][3]]['pembangunan_spgenset']		= $rowData[0][110];
				$kk[$rowData[0][3]]['pembangunan_splampu']		= $rowData[0][111];
				$kk[$rowData[0][3]]['pembangunan_splain']		= $rowData[0][112];
				$kk[$rowData[0][3]]['pembangunan_sauledeng']	= $rowData[0][113];
				$kk[$rowData[0][3]]['pembangunan_sausumur']		= $rowData[0][114];
				$kk[$rowData[0][3]]['pembangunan_sauhujan']		= $rowData[0][115];
				$kk[$rowData[0][3]]['pembangunan_saulain']		= $rowData[0][116];
				$kk[$rowData[0][3]]['pembangunan_bblistrik']	= $rowData[0][117];
				$kk[$rowData[0][3]]['pembangunan_bbminyak']		= $rowData[0][118];
				$kk[$rowData[0][3]]['pembangunan_bbarang']		= $rowData[0][119];
				$kk[$rowData[0][3]]['pembangunan_bblain']		= $rowData[0][120];
				$kk[$rowData[0][3]]['pembangunan_fsltsbab']		= $rowData[0][121];
				$kk[$rowData[0][3]]['pembangunan_mrsendiri']	= $rowData[0][122];
				$kk[$rowData[0][3]]['pembangunan_mrsewa']		= $rowData[0][123];
				$kk[$rowData[0][3]]['pembangunan_mrmenumpang']	= $rowData[0][124];
				$kk[$rowData[0][3]]['pembangunan_mrlainnya']	= $rowData[0][125];
				$kk[$rowData[0][3]]['pembangunan_luasrm']		= $rowData[0][126];
				$kk[$rowData[0][3]]['pembangunan_tinggaldirm']	= $rowData[0][127];
        	    //anggotakeluarga
        	    $kk[$rowData[0][3]]['anggota'][]			= $rowData[0];
            }
            //delete_files($media['file_path']);
            
			
            $data_keluarga 	= array();
            $result_error 	= array();
            $result_true	= array();
            
            
            $id_data_keluarga='';
            $nourutkel='';
            $no_anggota='';
            $data_keluarga_profile=array();
            
            $datakepalakeluarga = array();
            
            foreach ($kk as $anggota) {

            	$data_keluarga = array(
            				'tanggal_pengisian'  		=> $anggota['tanggal_pengisian'],
            				'jam_data'  				=> $anggota['jam_data'],
            				'alamat'  					=> $anggota['alamat'],
            				'id_propinsi'  				=> $anggota['id_propinsi'],
            				'id_kota'  					=> $anggota['id_kota'],
            				'id_kecamatan'  			=> $anggota['id_kecamatan'],
            				'id_desa'  					=> $anggota['id_desa'],
            				'id_kodepos'  				=> $anggota['id_kodepos'],
            				'rw'  						=> $anggota['rw'],
            				'rt'  						=> $anggota['rt'],
            				'norumah'  					=> $anggota['norumah'],
            				'namakepalakeluarga'  		=> $anggota['namakepalakeluarga'],
            				'notlp'  					=> $anggota['notlp'],
            				'namadesawisma'  			=> $anggota['namadesawisma'],
            				'nama_komunitas'  			=> $anggota['nama_komunitas'],
            				'id_pkk'  					=> $anggota['id_pkk'],
            				'nama_koordinator'  		=> $anggota['nama_koordinator'],
            				'nama_pendata'  			=> $anggota['nama_pendata'],
            				'jam_selesai'  				=> $anggota['jam_selesai'],
            				'jml_anaklaki'  			=> $anggota['jml_anaklaki'],
            				'jml_anakperempuan'  		=> $anggota['jml_anakperempuan'],
            				'pus_ikutkb'  				=> $anggota['pus_ikutkb'],
            				'pus_tidakikutkb'  			=> $anggota['pus_tidakikutkb']
            			);
            	$nik = array();
            	$mergenik='';
            	foreach ($anggota['anggota'] as $act) {
            		$datanik = $mergenik.$act[17].',';
            		$nik[] = $act[17];
            		$mergenik = $datanik;
            	}
            	
            	$cekid_keluarga = $this->datakeluarga_model->cekidkeluargawherein($nik);
            	

           		if ($cekid_keluarga == 'bedakeluarga') {
            		$this->session->set_flashdata('alert_fail', "data ($mergenik) berbeda kepala keluarga");
	    			redirect(base_url()."eform/data_kepala_keluarga/import_add");
            	}else if ($cekid_keluarga == 'tidakadaID') {
            		//insert kepala keluarga

            		$id = $this->datakeluarga_model->getNourutkel($anggota['id_desa']);
            		$datatambahkepalakeluarga = array(
    							'id_data_keluarga'  => $id['id_data_keluarga'],
            					'nourutkel'         => $id['nourutkel'],
    					);
    				$datamerege=array_merge($datatambahkepalakeluarga,$data_keluarga) ;
    				$this->db->insert('data_keluarga',$datamerege);	
    			

    				$this->db->delete('data_keluarga_profile',array('id_data_keluarga'=>$cekid_keluarga));
    				$iddatakeluarga = $datatambahkepalakeluarga['id_data_keluarga'];
    				
    				
    				//insert anggota keluarga
    				foreach ($anggota['anggota'] as $parsingkeluargaanggota) {
            			$id_pilihan_hubungan 	= $this->datakeluarga_model->getIdmst_keluarga_pilihan('hubungan',$parsingkeluargaanggota[23]);
	            		$id_pilihan_agama 		= $this->datakeluarga_model->getIdmst_keluarga_pilihan('agama',$parsingkeluargaanggota[24]);
	            		$id_pilihan_pendidikan 	= $this->datakeluarga_model->getIdmst_keluarga_pilihan('pendidikan',$parsingkeluargaanggota[25]);
	            		$id_pilihan_pekerjaan 	= $this->datakeluarga_model->getIdmst_keluarga_pilihan('pekerjaan',$parsingkeluargaanggota[26]);
	            		$id_pilihan_kawin 		= $this->datakeluarga_model->getIdmst_keluarga_pilihan('kawin',$parsingkeluargaanggota[27]);
	            		$id_pilihan_jkn 		= $this->datakeluarga_model->getIdmst_keluarga_pilihan('jkn',$parsingkeluargaanggota[28]);
	            		$data_keluarga_anggota = array(
	            				'nama'  					=> $parsingkeluargaanggota[19],
	            				'nik'  						=> $parsingkeluargaanggota[17],
	            				'tmpt_lahir'  				=> $parsingkeluargaanggota[21],
	            				'tgl_lahir'  				=> $parsingkeluargaanggota[22],
	            				'id_pilihan_hubungan'  		=> $id_pilihan_hubungan,
	            				'id_pilihan_kelamin'  		=> (strtolower($parsingkeluargaanggota[20])=='p' ? '6' : '5'),
	            				'id_pilihan_agama'  		=> $id_pilihan_agama,
	            				'id_pilihan_pendidikan'  	=> $id_pilihan_pendidikan,
	            				'id_pilihan_pekerjaan'  	=> $id_pilihan_pekerjaan,
	            				'id_pilihan_kawin'  		=> $id_pilihan_kawin,
	            				'id_pilihan_jkn'  			=> $id_pilihan_jkn,
	            				'bpjs'  					=> $parsingkeluargaanggota[18],
	            				'suku'  					=> $parsingkeluargaanggota[29],
	            				'no_hp'  					=> $parsingkeluargaanggota[30],
	            			);
            			
        				$datatambahanggota = array(
        							'id_data_keluarga' => $datatambahkepalakeluarga['id_data_keluarga'], 
        							'no_anggota'	   => $this->datakeluarga_model->noanggota($datatambahkepalakeluarga['id_data_keluarga']),
        					);
        				$datamerege=array_merge($datatambahanggota,$data_keluarga_anggota) ;
        				$this->db->insert('data_keluarga_anggota',$datamerege);
            			$code = explode("_", $excel);
        				$dataimport = array(
        					'id_import' 		=> $code[0],
        					'username'			=> $this->session->userdata('username'),
        					'id_data_keluarga'	=> $datatambahanggota['id_data_keluarga'],
        					'no_anggota'		=> $datatambahanggota['no_anggota'],
        					);
        				$this->db->insert('import',$dataimport);
        				$dataiddatakeluarga = $datatambahanggota['id_data_keluarga'];
						$datanoanggota 		= $datatambahanggota['no_anggota'];

            		// $this->db->delete('data_keluarga_anggota_profile',array('id_data_keluarga'=>$cekid_keluarga, 'no_anggota' => $datanoanggota));
						$this->dataanggotakeluarga($parsingkeluargaanggota,$dataiddatakeluarga,$datanoanggota);
            			
            		}
            	}else{ 
            		$data_keluargawhere = array(
            			'id_data_keluarga' 	=> $cekid_keluarga,
            			);
            		$this->db->update('data_keluarga',$data_keluarga,$data_keluargawhere);
            		//update profile
            		$this->db->delete('data_keluarga_profile',array('id_data_keluarga'=>$cekid_keluarga));
            		$this->db->delete('data_keluarga_kb',array('id_data_keluarga'=>$cekid_keluarga));
            		$this->db->delete('data_keluarga_pembangunan',array('id_data_keluarga'=>$cekid_keluarga));
            		$iddatakeluarga = $cekid_keluarga;
            		
            		foreach ($anggota['anggota'] as $parsingkeluargaanggota) {
            			
            			
            			$id_pilihan_hubungan 	= $this->datakeluarga_model->getIdmst_keluarga_pilihan('hubungan',$parsingkeluargaanggota[23]);
	            		$id_pilihan_agama 		= $this->datakeluarga_model->getIdmst_keluarga_pilihan('agama',$parsingkeluargaanggota[24]);
	            		$id_pilihan_pendidikan 	= $this->datakeluarga_model->getIdmst_keluarga_pilihan('pendidikan',$parsingkeluargaanggota[25]);
	            		$id_pilihan_pekerjaan 	= $this->datakeluarga_model->getIdmst_keluarga_pilihan('pekerjaan',$parsingkeluargaanggota[26]);
	            		$id_pilihan_kawin 		= $this->datakeluarga_model->getIdmst_keluarga_pilihan('kawin',$parsingkeluargaanggota[27]);
	            		$id_pilihan_jkn 		= $this->datakeluarga_model->getIdmst_keluarga_pilihan('jkn',$parsingkeluargaanggota[28]);
	            		$data_keluarga_anggota = array(
	            				'nama'  					=> $parsingkeluargaanggota[19],
	            				'nik'  						=> $parsingkeluargaanggota[17],
	            				'tmpt_lahir'  				=> $parsingkeluargaanggota[21],
	            				'tgl_lahir'  				=> $parsingkeluargaanggota[22],
	            				'id_pilihan_hubungan'  		=> $id_pilihan_hubungan,
	            				'id_pilihan_kelamin'  		=> (strtolower($parsingkeluargaanggota[20])=='p' ? '6' : '5'),
	            				'id_pilihan_agama'  		=> $id_pilihan_agama,
	            				'id_pilihan_pendidikan'  	=> $id_pilihan_pendidikan,
	            				'id_pilihan_pekerjaan'  	=> $id_pilihan_pekerjaan,
	            				'id_pilihan_kawin'  		=> $id_pilihan_kawin,
	            				'id_pilihan_jkn'  			=> $id_pilihan_jkn,
	            				'bpjs'  					=> $parsingkeluargaanggota[18],
	            				'suku'  					=> $parsingkeluargaanggota[29],
	            				'no_hp'  					=> $parsingkeluargaanggota[30],
	            			);
	            		$cekid_anggotakeluarga 	= $this->datakeluarga_model->cekIDanggotakeluarga($parsingkeluargaanggota[17]);
            			if ($cekid_anggotakeluarga=='kosong') {
            				$datatambah = array(
            							'id_data_keluarga' => $cekid_keluarga, 
            							'no_anggota'	   => $this->datakeluarga_model->noanggota($cekid_keluarga),
            					);
            				$datamerege=array_merge($datatambah,$data_keluarga_anggota) ;
            				
            				$this->db->insert('data_keluarga_anggota',$datamerege);
            				$code = explode("_", $excel);
            				$dataimport = array(
            					'id_import' 		=> $code[0],
            					'username'			=> $this->session->userdata('username'),
            					'id_data_keluarga'	=> $datatambah['id_data_keluarga'],
            					'no_anggota'		=> $datatambah['no_anggota'],
            					);
            				$this->db->insert('import',$dataimport);
            				$datanoanggota 		= $datatambah['no_anggota'];
            				$dataiddatakeluarga = $datatambah['id_data_keluarga'];
            			}else{
            				$pisah = explode('######', $cekid_anggotakeluarga);
            				$datawheresimpan = array(
            					'id_data_keluarga'  		=> $pisah[0],
	            				'no_anggota'  				=> $pisah[1]
	            				);
            				$this->db->update('data_keluarga_anggota',$data_keluarga_anggota,$datawheresimpan);
            				$code = explode("_", $excel);
            				$dataimport = array(
            					'id_import' 		=> $code[0],
            					'username'			=> $this->session->userdata('username'),
            					'id_data_keluarga'	=> $pisah[0],
            					'no_anggota'		=> $pisah[1],
            					'status'			=> '1',
            					);
            				$this->db->insert('import',$dataimport);	
            				$datanoanggota 		= $datawheresimpan['no_anggota'];
            				$dataiddatakeluarga = $datawheresimpan['id_data_keluarga'];
            			}


            			$this->db->delete('data_keluarga_anggota_profile',array('id_data_keluarga'=>$dataiddatakeluarga, 'no_anggota' => $datanoanggota));
            			$this->dataanggotakeluarga($parsingkeluargaanggota,$dataiddatakeluarga,$datanoanggota);
            			
            		}

            	}

            	
            		$data_keluarga_profile_beras = array(
            				'value'		=> (strtolower($anggota['profkel_mpberas'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_nonberas = array(
            				'value'		=> (strtolower($anggota['profkel_mpnonberas'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_mieinstan = array(
            				'value'		=> (strtolower($anggota['profkel_mpmieinstan'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_cepatsaji = array(
            				'value'		=> (strtolower($anggota['profkel_mpcepatsaji'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_donat = array(
            				'value'		=> (strtolower($anggota['profkel_mpdonat'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_biskuit = array(
            				'value'		=> (strtolower($anggota['profkel_mpbiskuit'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_gorengan = array(
            				'value'		=> (strtolower($anggota['profkel_mpgorengan'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_lainnya = array(
            				'value'		=> (strtolower($anggota['profkel_mplainnya'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_sapam = array(
            				'value'		=> (strtolower($anggota['profkel_sapam'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_sasumur = array(
            				'value'		=> (strtolower($anggota['profkel_sasumur'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_sahujan = array(
            				'value'		=> (strtolower($anggota['profkel_saairhujan'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_salain = array(
            				'value'		=> (strtolower($anggota['profkel_salainnya'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_jambankeluarga = array(
            				'value'		=> (strtolower($anggota['profkel_jmbnsendiri'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_buangsampah = array(
            				'value'		=> (strtolower($anggota['profkel_buangsampah'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_buangairlimbah = array(
            				'value'		=> (strtolower($anggota['profkel_buangairlmbh'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_stikerp4k = array(
            				'value'		=> (strtolower($anggota['profkel_stikerp4k'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_op2k = array(
            				'value'		=> (strtolower($anggota['profkel_up2k'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_usahakesling = array(
            				'value'		=> (strtolower($anggota['profkel_kesehatanling'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_ppp = array(
            				'value'		=> (strtolower($anggota['profkel_ppp'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_kerjabakti = array(
            				'value'		=> (strtolower($anggota['profkel_kerjabakti'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_rukunkematian = array(
            				'value'		=> (strtolower($anggota['profkel_rknkematian'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_keagamaan = array(
            				'value'		=> (strtolower($anggota['profkel_keagamaan'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_jimpitan = array(
            				'value'		=> (strtolower($anggota['profkel_jimpitan'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_arisan = array(
            				'value'		=> (strtolower($anggota['profkel_arisan'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_koperasi = array(
            				'value'		=> (strtolower($anggota['profkel_koperasi'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_kegiatanlain = array(
            				'value'		=> (strtolower($anggota['profkel_kklainnya'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_pendapatanbulan = array(
            				'value'		=> $anggota['profkel_pendapatan'],
            			);
            		$data_keluarga_profile_sppekerjaan = array(
            				'value'		=> (strtolower($anggota['profkel_pekerjaan'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_spsumbangan = array(
            				'value'		=> (strtolower($anggota['profkel_sumbangan'])=='ya' ? '1' : '0' ),
            			);
            		$data_keluarga_profile_splain= array(
            				'value'		=> (strtolower($anggota['profkel_ekonomilain'])=='ya' ? '1' : '0' ),
            			);
            	
            		//insertprofile
    				$dataIdKeluargaBeras =array(
    						'id_data_keluarga'  => $iddatakeluarga,
    						'id'		=> 'D',
            				'kode'		=> 'profile_a_1_a_radio',
    					);

    				$arrayMergeBeras = array_merge($dataIdKeluargaBeras,$data_keluarga_profile_beras);
    				$this->db->insert('data_keluarga_profile',$arrayMergeBeras);

    				$dataIdKeluargaNonBeras =array(
    						'id_data_keluarga'  => $iddatakeluarga,
    						'id'		=> 'D',
            				'kode'		=> 'profile_a_1_b_radio',
    					);
    				$arrayMergeNonBeras = array_merge($dataIdKeluargaNonBeras,$data_keluarga_profile_nonberas);
    				$this->db->insert('data_keluarga_profile',$arrayMergeNonBeras);

    				$dataIdKeluargaMieInstan =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_a_1_c_radio',
    					);
    				$arrayMergeMieInstan = array_merge($dataIdKeluargaMieInstan,$data_keluarga_profile_mieinstan);
    				$this->db->insert('data_keluarga_profile',$arrayMergeMieInstan);

    				$dataIdKeluargaCepatSaji =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_a_1_d_radio',
    					);
    				$arrayMergeCepatSaji = array_merge($dataIdKeluargaCepatSaji,$data_keluarga_profile_cepatsaji);
    				$this->db->insert('data_keluarga_profile',$arrayMergeCepatSaji);

    				$dataIdKeluargaDonat =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_a_1_e_radio',
    					);
    				$arrayMergeDonat = array_merge($dataIdKeluargaDonat,$data_keluarga_profile_donat);
    				$this->db->insert('data_keluarga_profile',$arrayMergeDonat);

    				$dataIdKeluargaBiskuit =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_a_1_f_radio',
    					);
    				$arrayMergeBiskuit = array_merge($dataIdKeluargaBiskuit,$data_keluarga_profile_biskuit);
    				$this->db->insert('data_keluarga_profile',$arrayMergeBiskuit);

    				$dataIdKeluargaGorengan =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_a_1_g_radio',
    					);
    				$arrayMergeGorengan = array_merge($dataIdKeluargaGorengan,$data_keluarga_profile_gorengan);
    				$this->db->insert('data_keluarga_profile',$arrayMergeGorengan);

    				$dataIdKeluargaLainnya =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_a_1_h_radio',
    					);
    				$arrayMergeLainnya = array_merge($dataIdKeluargaLainnya,$data_keluarga_profile_lainnya);
    				$this->db->insert('data_keluarga_profile',$arrayMergeLainnya);

    				$dataIdKeluargaSaPam =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_a_2_a_radio',
    					);
    				$arrayMergeSaPam = array_merge($dataIdKeluargaSaPam,$data_keluarga_profile_sapam);
    				$this->db->insert('data_keluarga_profile',$arrayMergeSaPam);

    				$dataIdKeluargaSaSumur  =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_a_2_b_radio',
    					);
    				$arrayMergeSaSumur = array_merge($dataIdKeluargaSaSumur,$data_keluarga_profile_sasumur);
    				$this->db->insert('data_keluarga_profile',$arrayMergeSaSumur);

    				$dataIdKeluargaSaHujan  =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_a_2_c_radio',
    					);
    				$arrayMergeSaHujan = array_merge($dataIdKeluargaSaHujan,$data_keluarga_profile_sahujan);
    				$this->db->insert('data_keluarga_profile',$arrayMergeSaHujan);

    				$dataIdKeluargaSaLain =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_a_2_d_radio',
    					);
    				$arrayMergeSaLain = array_merge($dataIdKeluargaSaLain,$data_keluarga_profile_salain);
    				$this->db->insert('data_keluarga_profile',$arrayMergeSaLain);

    				$dataIdKeluargaJambanKeluarga  =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_a_3_a_radio',
    					);
    				$arrayMergeJambanKeluarga = array_merge($dataIdKeluargaJambanKeluarga,$data_keluarga_profile_jambankeluarga);
    				$this->db->insert('data_keluarga_profile',$arrayMergeJambanKeluarga);

    				$dataIdKeluargaBuangsampah =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_a_4_a_radio',
    					);
    				$arrayMergeBuangsampah = array_merge($dataIdKeluargaBuangsampah,$data_keluarga_profile_buangsampah);
    				$this->db->insert('data_keluarga_profile',$arrayMergeBuangsampah);

    				$dataIdKeluargaBuanglimbah =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_a_5_a_radio',
    					);
    				$arrayMergeAirlimbah = array_merge($dataIdKeluargaBuanglimbah,$data_keluarga_profile_buangairlimbah);
    				$this->db->insert('data_keluarga_profile',$arrayMergeAirlimbah);

    				$dataIdKeluargaSticker =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_a_6_a_radio',
    					);
    				$arrayMergeSticker = array_merge($dataIdKeluargaSticker,$data_keluarga_profile_stikerp4k);
    				$this->db->insert('data_keluarga_profile',$arrayMergeSticker);

    				$dataIdKeluargaUp2k =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_b_1_a_radio',
    					);
    				$arrayMergeUp2k = array_merge($dataIdKeluargaUp2k,$data_keluarga_profile_op2k);
    				$this->db->insert('data_keluarga_profile',$arrayMergeUp2k);

    				$dataIdKeluargaKesling =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_b_2_a_radio',
    					);
    				$arrayMergeKesling = array_merge($dataIdKeluargaKesling,$data_keluarga_profile_usahakesling);
    				$this->db->insert('data_keluarga_profile',$arrayMergeKesling);

    				$dataIdKeluargaBuangPPP  =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_b_3_a_radio',
    					);
    				$arrayMergePPP = array_merge($dataIdKeluargaBuangPPP,$data_keluarga_profile_ppp);
    				$this->db->insert('data_keluarga_profile',$arrayMergePPP);

    				$dataIdKeluargakerjabakti =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_b_4_a_radio',
    					);
    				$arrayMergekerjabakti = array_merge($dataIdKeluargakerjabakti,$data_keluarga_profile_kerjabakti);
    				$this->db->insert('data_keluarga_profile',$arrayMergekerjabakti);

    				$dataIdKeluargaRukunKem=array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_b_5_a_radio',
    					);
    				$arrayMergeRukunKem = array_merge($dataIdKeluargaRukunKem,$data_keluarga_profile_rukunkematian);
    				$this->db->insert('data_keluarga_profile',$arrayMergeRukunKem);

    				$dataIdKeluargaKeagamaan =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_b_6_a_radio',
    					);
    				$arrayMergeKeagamaan = array_merge($dataIdKeluargaKeagamaan,$data_keluarga_profile_keagamaan);
    				$this->db->insert('data_keluarga_profile',$arrayMergeKeagamaan);

    				$dataIdKeluargaJimpitan =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_b_7_a_radio',
    					);
    				$arrayMergeJimpitan= array_merge($dataIdKeluargaJimpitan,$data_keluarga_profile_jimpitan);
    				$this->db->insert('data_keluarga_profile',$arrayMergeJimpitan);

    				$dataIdKeluargaArisan =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_b_8_a_radio',
    					);
    				$arrayMergeArisan = array_merge($dataIdKeluargaArisan,$data_keluarga_profile_arisan);
 	   				$this->db->insert('data_keluarga_profile',$arrayMergeArisan);

 	   				$dataIdKeluargaKoperasi =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_b_9_a_radio',
    					);
    				$arrayMergeKoperasi = array_merge($dataIdKeluargaKoperasi,$data_keluarga_profile_koperasi);
    				$this->db->insert('data_keluarga_profile',$arrayMergeKoperasi);

    				$dataIdKeluargaLainnya =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_b_10_a_radio',
    					);
    				$arrayMergeLainnya = array_merge($dataIdKeluargaLainnya,$data_keluarga_profile_kegiatanlain);
    				$this->db->insert('data_keluarga_profile',$arrayMergeLainnya);

    				$dataIdKeluargaPendapanBulan =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_c_1_a_jumlah',
    					);
    				$arrayMergePendapanBulan = array_merge($dataIdKeluargaPendapanBulan,$data_keluarga_profile_pendapatanbulan);
    				$this->db->insert('data_keluarga_profile',$arrayMergePendapanBulan);

    				$dataIdKeluargaSPPekerjaan =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_c_2_a_radio',
    					);
    				$arrayMergeSPPekerjaan = array_merge($dataIdKeluargaSPPekerjaan,$data_keluarga_profile_sppekerjaan);
    				$this->db->insert('data_keluarga_profile',$arrayMergeSPPekerjaan);

    				$dataIdKeluargaSPsumbangan =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_c_2_b_radio',
    					);
    				$arrayMergeSPsumbangan = array_merge($dataIdKeluargaSPsumbangan,$data_keluarga_profile_spsumbangan);
    				$this->db->insert('data_keluarga_profile',$arrayMergeSPsumbangan);

    				$dataIdKeluargaspLain =array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'		=> 'D',
            				'kode'		=> 'profile_c_2_c_radio',
    					);
    				$arrayMergespLain= array_merge($dataIdKeluargaspLain,$data_keluarga_profile_splain);
    				$this->db->insert('data_keluarga_profile',$arrayMergespLain);



    				//insertkeluargaberencana
    				$datapokokkeluargaberencana = $arrayName = array(
    					'id_data_keluarga'  => $iddatakeluarga,
            			'id'				=> 'II',
    					);
    				$data_keluarga_kb_usiakawinsuami =array(
            				'kode'				=> 'berencana_II_1_suami',
            				'value'				=> $anggota['kb_usiakawinsuami'],
    					);
    				$datameregerkbusiakawinsuami = array_merge($datapokokkeluargaberencana,$data_keluarga_kb_usiakawinsuami);
    				$this->db->insert('data_keluarga_kb',$datameregerkbusiakawinsuami);

    				$data_keluarga_kb_usiakawinistri =array(
            				'kode'				=> 'berencana_II_1_istri',
            				'value'				=> $anggota['kb_usiakawinistri'],
    					);
    				$datameregerkbusiakawinistrii = array_merge($datapokokkeluargaberencana,$data_keluarga_kb_usiakawinistri);
    				$this->db->insert('data_keluarga_kb',$datameregerkbusiakawinistrii);

    				$data_keluarga_kb_jmllaki =array(
            				'kode'				=> 'berencana_II_2_laki_hidup',
            				'value'				=> $anggota['kb_jmlanaklaki'],
    					);
    				$datameregerkbjmllaki = array_merge($datapokokkeluargaberencana,$data_keluarga_kb_jmllaki);
    				$this->db->insert('data_keluarga_kb',$datameregerkbjmllaki);

    				$data_keluarga_kb_jmlper =array(
            				'kode'				=> 'berencana_II_2_perempuan_hidup',
            				'value'				=> $anggota['kb_jmlanakperempuan'],
    					);
    				$datameregerkbjmlper= array_merge($datapokokkeluargaberencana,$data_keluarga_kb_jmlper);
    				$this->db->insert('data_keluarga_kb',$datameregerkbjmlper);

    				$data_keluarga_kb_kepesertaankb =array(
            				'kode'				=> 'berencana_II_3_kb_radio',
            				'value'				=> (strtolower($anggota['kb_kepesetaankb'])=='sedang' ? '0' : (strtolower($anggota['kb_kepesetaankb'])=='pernah' ? '1' : (strtolower($anggota['kb_kepesetaankb']) =='tidak pernah' ? '2' : '2'))),
    					);
    				$datameregerkepesertaankbi = array_merge($datapokokkeluargaberencana,$data_keluarga_kb_kepesertaankb);
    				$this->db->insert('data_keluarga_kb',$datameregerkepesertaankbi);

    				$data_keluarga_kb_metodekb =array(
            				'kode'				=> 'berencana_II_4_kontrasepsi_sepsi',
            				'value'				=> (strtolower($anggota['kb_metodekb'])=='iud' ? '0' : (strtolower($anggota['kb_metodekb'])=='mow' ? '1' : (strtolower($anggota['kb_metodekb'])=='mop' ? '2' : (strtolower($anggota['kb_metodekb'])=='suntik' ? '3' : (strtolower($anggota['kb_metodekb'])=='batal pilih' ? '4' : (strtolower($anggota['kb_metodekb'])=='kondom' ? '5' : (strtolower($anggota['kb_metodekb'])=='implan' ? '6' : (strtolower($anggota['kb_metodekb'])=='pil' ? '7' : (strtolower($anggota['kb_metodekb'])=='tradisional' ? '8' : '4'))))))))),
    					);
    				$datameregerkbmetodekb= array_merge($datapokokkeluargaberencana,$data_keluarga_kb_metodekb);
    				$this->db->insert('data_keluarga_kb',$datameregerkbmetodekb);

    				$data_keluarga_kb_lamatahun =array(
            				'kode'				=> 'berencana_II_5_tahun',
            				'value'				=> $anggota['kb_lamakbtahun'],
    					);
    				$datameregerkblamatahun= array_merge($datapokokkeluargaberencana,$data_keluarga_kb_lamatahun);
    				$this->db->insert('data_keluarga_kb',$datameregerkblamatahun);

    				$data_keluarga_kb_lamabulan =array(
            				'kode'				=> 'berencana_II_5_bulan',
            				'value'				=> $anggota['kb_lamakbbulan'],
    					);
    				$datameregerkblamabulan= array_merge($datapokokkeluargaberencana,$data_keluarga_kb_lamabulan);
    				$this->db->insert('data_keluarga_kb',$datameregerkblamabulan);

    				$data_keluarga_kb_inginpunyanak =array(
            				'kode'				=> 'berencana_II_6_anak_radio',
            				'value'				=> (strtolower($anggota['kb_inginpunyaanak'])=='ya, segera'? '0' : (strtolower($anggota['kb_inginpunyaanak'])=='ya, kemudian'? '1' : (strtolower($anggota['kb_inginpunyaanak'])=='tidak'? '2' : '2') )),
    					);
    				$datameregerkbinginpunyanak= array_merge($datapokokkeluargaberencana,$data_keluarga_kb_inginpunyanak);
    				$this->db->insert('data_keluarga_kb',$datameregerkbinginpunyanak);

    				$data_keluarga_kb_sedanghamil =array(
            				'kode'				=> 'berencana_II_7_berkb_hamil_cebox',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['kb_akbhamil'])=='ya') {
    					$datameregerkbsedanghamil= array_merge($datapokokkeluargaberencana,$data_keluarga_kb_sedanghamil);
    					$this->db->insert('data_keluarga_kb',$datameregerkbsedanghamil);
    				}
    				

    				$data_keluarga_kb_fertilitas =array(
            				'kode'				=> 'berencana_II_7_berkb_fertilasi_cebox',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['kb_akbfertilitas'])=='ya') {
    					$datameregerkbfertilitas= array_merge($datapokokkeluargaberencana,$data_keluarga_kb_fertilitas);
    					$this->db->insert('data_keluarga_kb',$datameregerkbfertilitas);
    				}

    				$data_keluarga_kb_tidaksetuju =array(
            				'kode'				=> 'berencana_II_7_berkb_tidaksetuju_cebox',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['kb_akbtidaksetuju'])=='ya') {
    					$datameregerkbtidaksetuju= array_merge($datapokokkeluargaberencana,$data_keluarga_kb_tidaksetuju);
    					$this->db->insert('data_keluarga_kb',$datameregerkbtidaksetuju);
    				}

    				$data_keluarga_kb_tidaktahu =array(
            				'kode'				=> 'berencana_II_7_berkb_tidaktahu_cebox',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['kb_akbtidaktahu'])=='ya') {
    					$datameregerkbtidaktahu= array_merge($datapokokkeluargaberencana,$data_keluarga_kb_tidaktahu);
    					$this->db->insert('data_keluarga_kb',$datameregerkbtidaktahu);
    				}

    				$data_keluarga_kb_efek =array(
            				'kode'				=> 'berencana_II_7_berkb_efeksamping_cebox',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['kb_akbtakutefeksamping'])=='ya') {
    					$datameregerkbefek= array_merge($datapokokkeluargaberencana,$data_keluarga_kb_efek);
    					$this->db->insert('data_keluarga_kb',$datameregerkbefek);
    				}

    				$data_keluarga_kb_pelayananjauh =array(
            				'kode'				=> 'berencana_II_7_berkb_pelayanan_cebox',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['kb_akbjauh'])=='ya') {
    					$datameregerkbpelayananjauh= array_merge($datapokokkeluargaberencana,$data_keluarga_kb_pelayananjauh);
    					$this->db->insert('data_keluarga_kb',$datameregerkbpelayananjauh);
    				}

    				$data_keluarga_kb_mahal =array(
            				'kode'				=> 'berencana_II_7_berkb_tidakmampu_cebox',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['kb_akbmahal'])=='ya') {
    					$datameregerkbmahal= array_merge($datapokokkeluargaberencana,$data_keluarga_kb_mahal);
    					$this->db->insert('data_keluarga_kb',$datameregerkbmahal);
    				}

    				$data_keluarga_kb_kblain =array(
            				'kode'				=> 'berencana_II_7_berkb_lainya_cebox',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['kb_akblainnya'])=='ya') {
    					$datameregerkbkblain= array_merge($datapokokkeluargaberencana,$data_keluarga_kb_kblain);
    					$this->db->insert('data_keluarga_kb',$datameregerkbkblain);
    				}

    				// $this->db->insert('data_keluarga_kb',$data_keluarga_kb_fertilitas);
    				$data_keluarga_kb_tempatpelayanan =array(
            				'kode'				=> 'berencana_II_8_pelayanan_radkb',
            				'value'				=> (strtolower($anggota['kb_tmptpelayanan'])=='rsup/rsud' ? '0' : (strtolower($anggota['kb_tmptpelayanan'])=='rs tni' ? '1' : (strtolower($anggota['kb_tmptpelayanan'])=='rs porli' ? '2' : (strtolower($anggota['kb_tmptpelayanan'])=='rs swasta' ? '3' : (strtolower($anggota['kb_tmptpelayanan'])=='klinik utama' ? '4' : (strtolower($anggota['kb_tmptpelayanan'])=='puskesmas' ? '5' : (strtolower($anggota['kb_tmptpelayanan'])=='klinik pratama' ? '6' : (strtolower($anggota['kb_tmptpelayanan'])=='praktek dokter' ? '7' : (strtolower($anggota['kb_tmptpelayanan'])=='rs pratama' ? '8' : (strtolower($anggota['kb_tmptpelayanan'])=='pustu/pusling/bidan desa' ? '9' : (strtolower($anggota['kb_tmptpelayanan'])=='poskesdes/polindes' ? '10' : (strtolower($anggota['kb_tmptpelayanan'])=='praktek bidan' ? '11' : (strtolower($anggota['kb_tmptpelayanan'])=='pelayanan bergerak' ? '12' : (strtolower($anggota['kb_tmptpelayanan'])=='lainnya' ? '13' : '13' )))))))))))))),
    					);
    				$datameregerkbkbtempatpelayanan= array_merge($datapokokkeluargaberencana,$data_keluarga_kb_tempatpelayanan);
    				$this->db->insert('data_keluarga_kb',$datameregerkbkbtempatpelayanan);

    				

    				///insertpembangunan
    				$datapokokpembangunan  = array(
    						'id_data_keluarga'  => $iddatakeluarga,
            				'id'				=> 'III',
    					);
    				$data_keluarga_pembangunan_pakaiantahun =array(
            				'kode'				=> 'pembangunan_III_1_radio',
            				'value'				=> (strtolower($anggota['pembangunan_satusetel1tahun'])=='ya' ? '0' : (strtolower($anggota['pembangunan_satusetel1tahun'])=='tidak' ? '1' : (strtolower($anggota['pembangunan_satusetel1tahun'])=='tidak berlaku' ? '2' : '1'))),
    					);
    				$datameregerpempakaiantahun= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_pakaiantahun);
    				$this->db->insert('data_keluarga_pembangunan',$datameregerpempakaiantahun);

    				$data_keluarga_pembangunan_makan2kali =array(
            				'kode'				=> 'pembangunan_III_2_radio',
            				'value'				=> (strtolower($anggota['pembangunan_makan2minggu'])=='ya' ? '0' : (strtolower($anggota['pembangunan_makan2minggu'])=='tidak' ? '1' : (strtolower($anggota['pembangunan_makan2minggu'])=='tidak berlaku' ? '2' : '1'))),
    					);
    				$datameregerpemmakan2kali= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_makan2kali);
    				$this->db->insert('data_keluarga_pembangunan',$datameregerpemmakan2kali);

    				$data_keluarga_pembangunan_berobatfayanke =array(
            				'kode'				=> 'pembangunan_III_3_radio',
            				'value'				=> (strtolower($anggota['pembangunan_sakitfayankes'])=='ya' ? '0' : (strtolower($anggota['pembangunan_sakitfayankes'])=='tidak' ? '1' : (strtolower($anggota['pembangunan_sakitfayankes'])=='tidak berlaku' ? '2' : '1'))),
    					);
    				$datameregerpemberobatfayanke= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_berobatfayanke);
    				$this->db->insert('data_keluarga_pembangunan',$datameregerpemberobatfayanke);

    				$data_keluarga_pembangunan_pakaianberbeda =array(
            				'kode'				=> 'pembangunan_III_4_radio',
            				'value'				=> (strtolower($anggota['pembangunan_bedabaju'])=='ya' ? '0' : (strtolower($anggota['pembangunan_bedabaju'])=='tidak' ? '1' : (strtolower($anggota['pembangunan_bedabaju'])=='tidak berlaku' ? '2' : '1'))),
    					);
    				$datameregerpempakaianberbeda= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_pakaianberbeda);
    				$this->db->insert('data_keluarga_pembangunan',$datameregerpempakaianberbeda);

    				$data_keluarga_pembangunan_dagingseminggu =array(
            				'kode'				=> 'pembangunan_III_5_radio',
            				'value'				=> (strtolower($anggota['pembangunan_telursatuminggu'])=='ya' ? '0' : (strtolower($anggota['pembangunan_telursatuminggu'])=='tidak' ? '1' : (strtolower($anggota['pembangunan_telursatuminggu'])=='tidak berlaku' ? '2' : '1'))),
    					);
    				$datameregerpemdagingseminggu= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_dagingseminggu);
    				$this->db->insert('data_keluarga_pembangunan',$datameregerpemdagingseminggu);

    				$data_keluarga_pembangunan_beribadah =array(
            				'kode'				=> 'pembangunan_III_6_radio',
            				'value'				=> (strtolower($anggota['pembangunan_beribadah'])=='ya' ? '0' : (strtolower($anggota['pembangunan_beribadah'])=='tidak' ? '1' : (strtolower($anggota['pembangunan_beribadah'])=='tidak berlaku' ? '2' : '1'))),
    					);
    				$datameregerpemberibadah= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_beribadah);
    				$this->db->insert('data_keluarga_pembangunan',$datameregerpemberibadah);

    				$data_keluarga_pembangunan_pasangansubur =array(
            				'kode'				=> 'pembangunan_III_7_radio',
            				'value'				=> (strtolower($anggota['pembangunan_usiasuburkb'])=='ya' ? '0' : (strtolower($anggota['pembangunan_usiasuburkb'])=='tidak' ? '1' : (strtolower($anggota['pembangunan_usiasuburkb'])=='tidak berlaku' ? '2' : '1'))),
    					);
    				$datameregerpempasangansubur= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_pasangansubur);
    				$this->db->insert('data_keluarga_pembangunan',$datameregerpempasangansubur);

    				$data_keluarga_pembangunan_uangsejuta =array(
            				'kode'				=> 'pembangunan_III_8_radio',
            				'value'				=> (strtolower($anggota['pembangunan_emasatujuta'])=='ya' ? '0' : (strtolower($anggota['pembangunan_emasatujuta'])=='tidak' ? '1' : (strtolower($anggota['pembangunan_emasatujuta'])=='tidak berlaku' ? '2' : '1'))),
    					);
    				$datameregerpemuangsejuta= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_uangsejuta);
    				$this->db->insert('data_keluarga_pembangunan',$datameregerpemuangsejuta);

    				$data_keluarga_pembangunan_komunikasi =array(
            				'kode'				=> 'pembangunan_III_9_radio',
            				'value'				=> (strtolower($anggota['pembangunan_komunikasi'])=='ya' ? '0' : (strtolower($anggota['pembangunan_komunikasi'])=='tidak' ? '1' : (strtolower($anggota['pembangunan_komunikasi'])=='tidak berlaku' ? '2' : '1'))),
    					);
    				$datameregerpemkomunikasi= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_komunikasi);
    				$this->db->insert('data_keluarga_pembangunan',$datameregerpemkomunikasi);

    				$data_keluarga_pembangunan_sosial =array(
            				'kode'				=> 'pembangunan_III_10_radio',
            				'value'				=> (strtolower($anggota['pembangunan_sosialrt'])=='ya' ? '0' : (strtolower($anggota['pembangunan_sosialrt'])=='tidak' ? '1' : (strtolower($anggota['pembangunan_sosialrt'])=='tidak berlaku' ? '2' : '1'))),
    					);
    				$datameregerpemsosial= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_sosial);
    				$this->db->insert('data_keluarga_pembangunan',$datameregerpemsosial);

    				$data_keluarga_pembangunan_informasitv =array(
            				'kode'				=> 'pembangunan_III_11_radio',
            				'value'				=> (strtolower($anggota['pembangunan_koranradio'])=='ya' ? '0' : (strtolower($anggota['pembangunan_koranradio'])=='tidak' ? '1' : (strtolower($anggota['pembangunan_koranradio'])=='tidak berlaku' ? '2' : '1'))),
    					);
    				$datameregerpeminformasitv= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_informasitv);
    				$this->db->insert('data_keluarga_pembangunan',$datameregerpeminformasitv);

    				$data_keluarga_pembangunan_kegiatansos =array(
            				'kode'				=> 'pembangunan_III_12_radio',
            				'value'				=> (strtolower($anggota['pembangunan_kegiatansos'])=='ya' ? '0' : (strtolower($anggota['pembangunan_kegiatansos'])=='tidak' ? '1' : (strtolower($anggota['pembangunan_kegiatansos'])=='tidak berlaku' ? '2' : '1'))),
    					);
    				$datameregerpemkegiatansos= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_kegiatansos);
    				$this->db->insert('data_keluarga_pembangunan',$datameregerpemkegiatansos);

    				$data_keluarga_pembangunan_kegiatanpos =array(
            				'kode'				=> 'pembangunan_III_13_radio',
            				'value'				=> (strtolower($anggota['pembangunan_ikutposyandu'])=='ya' ? '0' : (strtolower($anggota['pembangunan_ikutposyandu'])=='tidak' ? '1' : (strtolower($anggota['pembangunan_ikutposyandu'])=='tidak berlaku' ? '2' : '1'))),
    					);
    				$datameregerpemkegiatanpos= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_kegiatanpos);
    				$this->db->insert('data_keluarga_pembangunan',$datameregerpemkegiatanpos);

    				$data_keluarga_pembangunan_bkb =array(
            				'kode'				=> 'pembangunan_III_14_radio',
            				'value'				=> (strtolower($anggota['pembangunan_ikutbkb'])=='ya' ? '0' : (strtolower($anggota['pembangunan_ikutbkb'])=='tidak' ? '1' : (strtolower($anggota['pembangunan_ikutbkb'])=='tidak berlaku' ? '2' : '1'))),
    					);
    				$datameregerpembkb= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_bkb);
    				$this->db->insert('data_keluarga_pembangunan',$datameregerpembkb);

    				$data_keluarga_pembangunan_bkr =array(
            				'kode'				=> 'pembangunan_III_15_radio',
            				'value'				=> (strtolower($anggota['pembangunan_ikutbkr'])=='ya' ? '0' : (strtolower($anggota['pembangunan_ikutbkr'])=='tidak' ? '1' : (strtolower($anggota['pembangunan_ikutbkr'])=='tidak berlaku' ? '2' : '1'))),
    					);
    				$datameregerpembkr= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_bkr);
    				$this->db->insert('data_keluarga_pembangunan',$datameregerpembkr);

    				$data_keluarga_pembangunan_pik =array(
            				'kode'				=> 'pembangunan_III_16_radio',
            				'value'				=> (strtolower($anggota['pembangunan_ikutpik'])=='ya' ? '0' : (strtolower($anggota['pembangunan_ikutpik'])=='tidak' ? '1' : (strtolower($anggota['pembangunan_ikutpik'])=='tidak berlaku' ? '2' : '1'))),
    					);
    				$datameregerpempik= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_pik);
    				$this->db->insert('data_keluarga_pembangunan',$datameregerpempik);

    				$data_keluarga_pembangunan_bkl =array(
            				'kode'				=> 'pembangunan_III_17_radio',
            				'value'				=> (strtolower($anggota['pembangunan_ikutbkl'])=='ya' ? '0' : (strtolower($anggota['pembangunan_ikutbkl'])=='tidak' ? '1' : (strtolower($anggota['pembangunan_ikutbkl'])=='tidak berlaku' ? '2' : '1'))),
    					);
    				$datameregerpembkl= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_bkl);
    				$this->db->insert('data_keluarga_pembangunan',$datameregerpembkl);

    				$data_keluarga_pembangunan_upps =array(
            				'kode'				=> 'pembangunan_III_18_radio',
            				'value'				=> (strtolower($anggota['pembangunan_uppks'])=='ya' ? '0' : (strtolower($anggota['pembangunan_uppks'])=='tidak' ? '1' : (strtolower($anggota['pembangunan_uppks'])=='tidak berlaku' ? '2' : '1'))),
    					);
    				$datameregerpemupps= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_upps);
    				$this->db->insert('data_keluarga_pembangunan',$datameregerpemupps);

    				$data_keluarga_pembangunan_daun =array(
            				'kode'				=> 'pembangunan_III_1_19_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_jadaun'])=='ya') {
    					$datameregerpemdaun= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_daun);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemdaun);
    				}

    				$data_keluarga_pembangunan_seng =array(
            				'kode'				=> 'pembangunan_III_2_19_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_jaseng'])=='ya') {
    					$datameregerpemseng= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_seng);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemseng);
    				}

    				$data_keluarga_pembangunan_genteng =array(
            				'kode'				=> 'pembangunan_III_3_19_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_jagenteng'])=='ya') {
    					$datameregerpemgenteng= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_genteng);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemgenteng);
    				}

    				$data_keluarga_pembangunan_atplain =array(
            				'kode'				=> 'pembangunan_III_4_19_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_jalainnya'])=='ya') {
    					$datameregerpematplain= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_atplain);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpematplain);
    				}

    				$data_keluarga_pembangunan_ddtembok =array(
            				'kode'				=> 'pembangunan_III_1_20_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_jdtembok'])=='ya') {
    					$datameregerpemddtembok= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_ddtembok);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemddtembok);
    				}

    				$data_keluarga_pembangunan_ddkayu =array(
            				'kode'				=> 'pembangunan_III_2_20_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_jdkayu'])=='ya') {
    					$datameregerpemddkayu = array_merge($datapokokpembangunan,$data_keluarga_pembangunan_ddkayu);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemddkayu);
    				}

    				$data_keluarga_pembangunan_ddbambu =array(
            				'kode'				=> 'pembangunan_III_3_20_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_jdbambbu'])=='ya') {
    					$datameregerpemddbambu= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_ddbambu);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemddbambu);
    				}

    				$data_keluarga_pembangunan_ddlainnya =array(
            				'kode'				=> 'pembangunan_III_4_20_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_jdlainnya'])=='ya') {
    					$datameregerpemddlainnya= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_ddlainnya);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemddlainnya);
    				}

    				$data_keluarga_pembangunan_lubin =array(
            				'kode'				=> 'pembangunan_III_1_21_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_jlubin'])=='ya') {
    					$datameregerpemlubin= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_lubin);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemlubin);
    				}

    				$data_keluarga_pembangunan_lsemen =array(
            				'kode'				=> 'pembangunan_III_2_21_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_jlsemen'])=='ya') {
    					$datameregerpemlsemen= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_lsemen);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemlsemen);
    				}

    				$data_keluarga_pembangunan_ltanah =array(
            				'kode'				=> 'pembangunan_III_3_21_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_jltanah'])=='ya') {
    					$datameregerpemltanah= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_ltanah);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemltanah);
    				}

    				$data_keluarga_pembangunan_llainnya =array(
            				'kode'				=> 'pembangunan_III_4_21_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_jllain'])=='ya') {
    					$datameregerpemllainnya= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_llainnya);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemllainnya);
    				}

    				$data_keluarga_pembangunan_plistrik =array(
            				'kode'				=> 'pembangunan_III_22_1_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_splistrik'])=='ya') {
    					$datameregerpemplistrik= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_plistrik);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemplistrik);
    				}

    				$data_keluarga_pembangunan_pgenset =array(
            				'kode'				=> 'pembangunan_III_22_2_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_spgenset'])=='ya') {
    					$datameregerpempgenset= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_pgenset);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpempgenset);
    				}

    				$data_keluarga_pembangunan_plampu =array(
            				'kode'				=> 'pembangunan_III_22_3_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_splampu'])=='ya') {
    					$datameregerpemplampu= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_plampu);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemplampu);
    				}

    				$data_keluarga_pembangunan_plainu =array(
            				'kode'				=> 'pembangunan_III_22_4_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_splain'])=='ya') {
    					$datameregerpemplainu= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_plainu);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemplainu);
    				}

    				$data_keluarga_pembangunan_mledeng =array(
            				'kode'				=> 'pembangunan_III_23_1_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_sauledeng'])=='ya') {
    					$datameregerpemmledeng= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_mledeng);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemmledeng);
    				}

    				$data_keluarga_pembangunan_msumur =array(
            				'kode'				=> 'pembangunan_III_23_2_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_sausumur'])=='ya') {
    					$datameregerpemmsumur= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_msumur);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemmsumur);
    				}

    				$data_keluarga_pembangunan_mair =array(
            				'kode'				=> 'pembangunan_III_23_3_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_sauhujan'])=='ya') {
    					$datameregerpemmair= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_mair);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemmair);
    				}

    				$data_keluarga_pembangunan_mlainnya =array(
            				'kode'				=> 'pembangunan_III_23_4_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_saulain'])=='ya') {
    					$datameregerpemmlainny= array_merge($datapokokpembangunan,$data_keluarga_pembangunan_mlainnya);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemmlainny);
    				}

    				$data_keluarga_kb_bblistrik =array(
            				'kode'				=> 'pembangunan_III_24_1_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_bblistrik'])=='ya') {
    					$datameregerpembblistrik= array_merge($datapokokpembangunan,$data_keluarga_kb_bblistrik);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpembblistrik);
    				}

    				$data_keluarga_kb_bbminyak =array(
            				'kode'				=> 'pembangunan_III_24_2_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_bbminyak'])=='ya') {
    					$datameregerpembbminyak= array_merge($datapokokpembangunan,$data_keluarga_kb_bbminyak);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpembbminyak);
    				}

    				$data_keluarga_kb_bbarang=array(
            				'kode'				=> 'pembangunan_III_24_3_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_bbarang'])=='ya') {
    					$datameregerpembbarang= array_merge($datapokokpembangunan,$data_keluarga_kb_bbarang);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpembbarang);
    				}

    				$data_keluarga_kb_bblainnya =array(
            				'kode'				=> 'pembangunan_III_24_4_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_bblain'])=='ya') {
    					$datameregerpembblainnya= array_merge($datapokokpembangunan,$data_keluarga_kb_bblainnya);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpembblainnya);
    				}

    				$data_keluarga_kb_fasil=array(
            				'kode'				=> 'pembangunan_III_25_radi4',
            				'value'				=> (strtolower($anggota['pembangunan_fsltsbab'])=='jamban sendiri' ? '0' : (strtolower($anggota['pembangunan_fsltsbab'])=='jamban bersama' ? '1' : (strtolower($anggota['pembangunan_fsltsbab'])=='jamban umum' ? '2' : (strtolower($anggota['pembangunan_fsltsbab'])=='lainnya' ? '3' : '3')))),
    					);
    				$datameregerpemfasil= array_merge($datapokokpembangunan,$data_keluarga_kb_fasil);
    				$this->db->insert('data_keluarga_pembangunan',$datameregerpemfasil);

    				$data_keluarga_kb_ttmilik =array(
            				'kode'				=> 'pembangunan_III_26_1_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_mrsendiri'])=='ya') {
    					$datameregerpemttmilik= array_merge($datapokokpembangunan,$data_keluarga_kb_ttmilik);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemttmilik);
    				}

    				$data_keluarga_kb_ttsewa =array(
            				'kode'				=> 'pembangunan_III_26_2_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_mrsewa'])=='ya') {
    					$datameregerpemttsewa= array_merge($datapokokpembangunan,$data_keluarga_kb_ttsewa);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemttsewa);
    				}

    				$data_keluarga_kb_ttmenumpang=array(
            				'kode'				=> 'pembangunan_III_26_3_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_mrmenumpang'])=='ya') {
    					$datameregerpemttmenumpang= array_merge($datapokokpembangunan,$data_keluarga_kb_ttmenumpang);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemttmenumpang);
    				}

    				$data_keluarga_kb_ttlainn =array(
            				'kode'				=> 'pembangunan_III_26_4_cebo4',
            				'value'				=> '1',
    					);
    				if (strtolower($anggota['pembangunan_mrlainnya'])=='ya') {
    					$datameregerpemttlainn= array_merge($datapokokpembangunan,$data_keluarga_kb_ttlainn);
    					$this->db->insert('data_keluarga_pembangunan',$datameregerpemttlainn);
    				}

    				$data_keluarga_kb_luasrumah =array(
            				'kode'				=> 'pembangunan_III_27_luas',
            				'value'				=> $anggota['pembangunan_luasrm'],
    					);
    				$datameregerpemluasrumah= array_merge($datapokokpembangunan,$data_keluarga_kb_luasrumah);
    				$this->db->insert('data_keluarga_pembangunan',$datameregerpemluasrumah);

    				$data_keluarga_kb_jmltinggaldirumah =array(
            				'kode'				=> 'pembangunan_III_28_orang',
            				'value'				=> $anggota['pembangunan_tinggaldirm'],
    					);
    				$datameregerpemtjmltinggaldirumah= array_merge($datapokokpembangunan,$data_keluarga_kb_jmltinggaldirumah);
            		$this->db->insert('data_keluarga_pembangunan',$datameregerpemtjmltinggaldirumah); 
            }
            
           
	}	
	function dataanggotakeluarga($parsingkeluargaanggota='',$dataiddatakeluarga='',$datanoanggota=''){

		$datapokokanggota = array(
				'id'				=> 'G',
				'id_data_keluarga'	=> $dataiddatakeluarga,
				'no_anggota'		=> $datanoanggota,
			);
		$data_keluarga_anggota_profile_akte = array(
				'kode'				=> 'kesehatan_0_g_1_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[128])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[128])=='tidak' ? '1' : '1')),
			);
		$datamergeakte = array_merge($datapokokanggota,$data_keluarga_anggota_profile_akte);
		$this->db->insert('data_keluarga_anggota_profile',$datamergeakte);	

		$data_keluarga_anggota_profile_wna = array(
				'kode'				=> 'kesehatan_0_g_2_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[129])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[129])=='tidak' ? '1' : '1')),
			);
		$datamergewna = array_merge($datapokokanggota,$data_keluarga_anggota_profile_wna);
		$this->db->insert('data_keluarga_anggota_profile',$datamergewna);	

		$data_keluarga_anggota_profile_putussekolah = array(
				'kode'				=> 'kesehatan_0_g_3_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[130])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[130])=='tidak' ? '1' : '1')),
			);
		$datamergesekolah = array_merge($datapokokanggota,$data_keluarga_anggota_profile_putussekolah);
		$this->db->insert('data_keluarga_anggota_profile',$datamergesekolah);	

		$data_keluarga_anggota_profile_ikutpaud = array(
				'kode'				=> 'kesehatan_0_g_4_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[131])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[131])=='tidak' ? '1' : '1')),
			);
		$datamergeikutpaud = array_merge($datapokokanggota,$data_keluarga_anggota_profile_ikutpaud);
		$this->db->insert('data_keluarga_anggota_profile',$datamergeikutpaud);	

		$data_keluarga_anggota_profile_ikutbelajar = array(
				'kode'				=> 'kesehatan_0_g_5_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[132])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[132])=='tidak' ? '1' : '1')),
			);
		$datamergeikutbelajar = array_merge($datapokokanggota,$data_keluarga_anggota_profile_ikutbelajar);
		$this->db->insert('data_keluarga_anggota_profile',$datamergeikutbelajar);	

		$data_keluarga_anggota_profile_jenispaket = array(
				'kode'				=> 'kesehatan_0_g_5_radi4',
				'value'				=> (strtolower($parsingkeluargaanggota[133])=='a' ? '0' : (strtolower($parsingkeluargaanggota[133])=='b' ? '1' : (strtolower($parsingkeluargaanggota[133])=='c' ? '2' : (strtolower($parsingkeluargaanggota[133])=='kf' ? '3' : $parsingkeluargaanggota[133]='')))),
			);
		if (strtolower($parsingkeluargaanggota[133])!='') {
			$datamergejenispaket = array_merge($datapokokanggota,$data_keluarga_anggota_profile_jenispaket);
			$this->db->insert('data_keluarga_anggota_profile',$datamergejenispaket);	
		}
            			

		$data_keluarga_anggota_profile_punyatabungan = array(
				'kode'				=> 'kesehatan_0_g_6_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[134])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[134])=='tidak' ? '1' : '1')),
			);
		$datamergepunyatabungan = array_merge($datapokokanggota,$data_keluarga_anggota_profile_punyatabungan);
		$this->db->insert('data_keluarga_anggota_profile',$datamergepunyatabungan);	

		$data_keluarga_anggota_profile_ikutkoperasi = array(
				'kode'				=> 'kesehatan_0_g_7_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[135])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[135])=='tidak' ? '1' : '1')),
			);
		$datamergeikutkoperasi = array_merge($datapokokanggota,$data_keluarga_anggota_profile_ikutkoperasi);
		$this->db->insert('data_keluarga_anggota_profile',$datamergeikutkoperasi);	

		$data_keluarga_anggota_profile_jeniskoperasi = array(
				'kode'				=> 'kesehatan_0_g_7_koperasi',
				'value'				=> $parsingkeluargaanggota[136],
			);
		$datamergejeniskoperasi = array_merge($datapokokanggota,$data_keluarga_anggota_profile_jeniskoperasi);
		$this->db->insert('data_keluarga_anggota_profile',$datamergejeniskoperasi);	

		$data_keluarga_anggota_profile_usiasubur = array(
				'kode'				=> 'kesehatan_0_g_8_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[137])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[137])=='tidak' ? '1' : '1')),
			);
		$datamergeusiasubur = array_merge($datapokokanggota,$data_keluarga_anggota_profile_usiasubur);
		$this->db->insert('data_keluarga_anggota_profile',$datamergeusiasubur);	

		$data_keluarga_anggota_profile_hamil = array(
				'kode'				=> 'kesehatan_0_g_9_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[138])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[138])=='tidak' ? '1' : '1')),
			);
		$datamergehamil = array_merge($datapokokanggota,$data_keluarga_anggota_profile_hamil);
		$this->db->insert('data_keluarga_anggota_profile',$datamergehamil);	

		$data_keluarga_anggota_profile_disabilitas = array(
				'kode'				=> 'kesehatan_0_g_10_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[139])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[139])=='tidak' ? '1' : '1')),
			);
		$datamergedisabilitas = array_merge($datapokokanggota,$data_keluarga_anggota_profile_disabilitas);
		$this->db->insert('data_keluarga_anggota_profile',$datamergedisabilitas);	

		$data_keluarga_anggota_profile_jenisdisabilitas= array(
				'kode'				=> 'kesehatan_0_g_10_jenisrumah',
				'value'				=> $parsingkeluargaanggota[140],
			);
		$datamergejenisdisabilitas = array_merge($datapokokanggota,$data_keluarga_anggota_profile_jenisdisabilitas);
		$this->db->insert('data_keluarga_anggota_profile',$datamergejenisdisabilitas);	

		$data_keluarga_anggota_profile_cctsblmmakan = array(
				'kode'				=> 'kesehatan_1_g_1_a_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[141])=='ya') {
			$datamergecctsblmmakan = array_merge($datapokokanggota,$data_keluarga_anggota_profile_cctsblmmakan);
			$this->db->insert('data_keluarga_anggota_profile',$datamergecctsblmmakan);	
		}
            			
		$data_keluarga_anggota_profile_ccttangankotor = array(
				'kode'				=> 'kesehatan_1_g_1_b_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[144])=='ya') {
			$datamergeccttangankotor = array_merge($datapokokanggota,$data_keluarga_anggota_profile_ccttangankotor);
			$this->db->insert('data_keluarga_anggota_profile',$datamergeccttangankotor);	
		}

		$data_keluarga_anggota_profile_cctstlbab = array(
				'kode'				=> 'kesehatan_1_g_1_c_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[145])=='ya') {
			$datamergecctstlbab = array_merge($datapokokanggota,$data_keluarga_anggota_profile_cctstlbab);
			$this->db->insert('data_keluarga_anggota_profile',$datamergecctstlbab);	
		}

		$data_keluarga_anggota_profile_cctcebokbayi = array(
				'kode'				=> 'kesehatan_1_g_1_d_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[142])=='ya') {
			$datamergecctcebokbayi = array_merge($datapokokanggota,$data_keluarga_anggota_profile_cctcebokbayi);
			$this->db->insert('data_keluarga_anggota_profile',$datamergecctcebokbayi);	
		}

		$data_keluarga_anggota_profile_cctstlpestisida = array(
				'kode'				=> 'kesehatan_1_g_1_e_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[146])=='ya') {
			$datamergecctstlpestisida = array_merge($datapokokanggota,$data_keluarga_anggota_profile_cctstlpestisida);
			$this->db->insert('data_keluarga_anggota_profile',$datamergecctstlpestisida);	
		}

		$data_keluarga_anggota_profile_cctsmenyusui = array(
				'kode'				=> 'kesehatan_1_g_1_f_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[143])=='ya') {
			$datamergecctsmenyusui= array_merge($datapokokanggota,$data_keluarga_anggota_profile_cctsmenyusui);
			$this->db->insert('data_keluarga_anggota_profile',$datamergecctsmenyusui);	
		}

		$data_keluarga_anggota_profile_lokasibab = array(
				'kode'				=> 'kesehatan_1_g_2_radi5',
				'value'				=> (strtolower($parsingkeluargaanggota[147])=='jamban' ? '0' : (strtolower($parsingkeluargaanggota[147])=='kolam' ? '1' : (strtolower($parsingkeluargaanggota[147])=='sungai' ? '2' : (strtolower($parsingkeluargaanggota[147])=='lubang tanah' ? '3' :(strtolower($parsingkeluargaanggota[147])=='pantai' ? '4' :$parsingkeluargaanggota[147]='') )))),
			);
		if ($parsingkeluargaanggota[147]!='') {
			$datamergelokasibab = array_merge($datapokokanggota,$data_keluarga_anggota_profile_lokasibab);
			$this->db->insert('data_keluarga_anggota_profile',$datamergelokasibab);	
		}

		$data_keluarga_anggota_profile_sikatgigihari = array(
				'kode'				=> 'kesehatan_1_g_3_f_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[148])=='ya') {
			$datamergesikatgigihari = array_merge($datapokokanggota,$data_keluarga_anggota_profile_sikatgigihari);
			$this->db->insert('data_keluarga_anggota_profile',$datamergesikatgigihari);	
		}

		$data_keluarga_anggota_profile_sgmandipagi = array(
				'kode'				=> 'kesehatan_1_g_4_a_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[149])=='ya') {
			$datamergesgmandipagi = array_merge($datapokokanggota,$data_keluarga_anggota_profile_sgmandipagi);
			$this->db->insert('data_keluarga_anggota_profile',$datamergesgmandipagi);	
		}

		$data_keluarga_anggota_profile_sgmandisore = array(
				'id'				=> 'G',
				'id_data_keluarga'	=> $dataiddatakeluarga,
				'no_anggota'		=> $datanoanggota,
				'kode'				=> 'kesehatan_1_g_4_b_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[150])=='ya') {
			$datamergesgmandisore = array_merge($datapokokanggota,$data_keluarga_anggota_profile_sgmandisore);
			$this->db->insert('data_keluarga_anggota_profile',$datamergesgmandisore);	
		}

		$data_keluarga_anggota_profile_sgmakanpagi = array(
				'kode'				=> 'kesehatan_1_g_4_c_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[151])=='ya') {
			$datamergesgmakanpagi= array_merge($datapokokanggota,$data_keluarga_anggota_profile_sgmakanpagi);
			$this->db->insert('data_keluarga_anggota_profile',$datamergesgmakanpagi);	
		}


		$data_keluarga_anggota_profile_sgbangunpagi = array(
				'id'				=> 'G',
				'id_data_keluarga'	=> $dataiddatakeluarga,
				'no_anggota'		=> $datanoanggota,
				'kode'				=> 'kesehatan_1_g_4_d_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[154])=='ya') {
			$datamergesgbangunpagi= array_merge($datapokokanggota,$data_keluarga_anggota_profile_sgbangunpagi);
			$this->db->insert('data_keluarga_anggota_profile',$datamergesgbangunpagi);	
		}


		$data_keluarga_anggota_profile_sgtdrmalam = array(
				'kode'				=> 'kesehatan_1_g_4_e_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[152])=='ya') {
			$datamergesgtdrmalam = array_merge($datapokokanggota,$data_keluarga_anggota_profile_sgtdrmalam);
			$this->db->insert('data_keluarga_anggota_profile',$datamergesgtdrmalam);	
		}

		$data_keluarga_anggota_profile_sgmakansiang = array(
				'kode'				=> 'kesehatan_1_g_4_f_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[153])=='ya') {
			$datamergesgmakansiang = array_merge($datapokokanggota,$data_keluarga_anggota_profile_sgmakansiang);
			$this->db->insert('data_keluarga_anggota_profile',$datamergesgmakansiang);	
		}

		$data_keluarga_anggota_profile_merokoksebulan= array(
				'kode'				=> 'kesehatan_1_g_1_radi5',
				'value'				=> (strtolower($parsingkeluargaanggota[155])=='ya, setiap hari' ? '0' : (strtolower($parsingkeluargaanggota[155])=='ya, kadang-kadang' ? '1' : (strtolower($parsingkeluargaanggota[155])=='tidak, tapi dulu merokok setiap hari' ? '2' : (strtolower($parsingkeluargaanggota[155])=='tidak, tapi dulu kadang-kadang' ? '3' : (strtolower($parsingkeluargaanggota[155])=='tidak pernah sama sekali' ? '4' : '4'))))),
			);
		$datamergemerokoksebulan = array_merge($datapokokanggota,$data_keluarga_anggota_profile_merokoksebulan);
		$this->db->insert('data_keluarga_anggota_profile',$datamergemerokoksebulan);	
		
		$data_keluarga_anggota_profile_umurmerokok = array(
				'kode'				=> 'kesehatan_1_g_1_text',
				'value'				=> $parsingkeluargaanggota[156],
			);
		if (strtolower($parsingkeluargaanggota[156])!='') {
			$datamergeumurmerokok  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_umurmerokok);
			$this->db->insert('data_keluarga_anggota_profile',$datamergeumurmerokok);	
		}
			
		$data_keluarga_anggota_profile_merokoksetiaphari = array(
				'kode'				=> 'kesehatan_1_g_2_text',
				'value'				=> $parsingkeluargaanggota[157],
			);
		if (strtolower($parsingkeluargaanggota[157])!='') {
			$datamergemerokoksetiaphari  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_merokoksetiaphari);
			$this->db->insert('data_keluarga_anggota_profile',$datamergemerokoksetiaphari);	
		}

		$data_keluarga_anggota_profile_pertamamerokok = array(
				'kode'				=> 'kesehatan_1_g_3_text',
				'value'				=> $parsingkeluargaanggota[158],
			);
		if (strtolower($parsingkeluargaanggota[158])!='') {
			$datamergepertamamerokok  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_pertamamerokok);
			$this->db->insert('data_keluarga_anggota_profile',$datamergepertamamerokok);	
		}

		$data_keluarga_anggota_profile_rokokkonsumsi = array(
				'kode'				=> 'kesehatan_1_g_4_text',
				'value'				=> $parsingkeluargaanggota[159],
			);
		if (strtolower($parsingkeluargaanggota[159])!='') {
			$datamergerokokkonsumsi  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_rokokkonsumsi);
			$this->db->insert('data_keluarga_anggota_profile',$datamergerokokkonsumsi);	
		}

		$data_keluarga_anggota_profile_airputih = array(
				'kode'				=> 'kesehatan_3_1_a_text',
				'value'				=> $parsingkeluargaanggota[160],
			);
		if (strtolower($parsingkeluargaanggota[160])!='') {
			$datamergeairputih  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_airputih);
			$this->db->insert('data_keluarga_anggota_profile',$datamergeairputih);	
		}

		$data_keluarga_anggota_profile_airsusu = array(
				'kode'				=> 'kesehatan_3_1_b_text',
				'value'				=> $parsingkeluargaanggota[161],
			);
		if (strtolower($parsingkeluargaanggota[161])!='') {
			$datamergeairsusuh  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_airsusu);
			$this->db->insert('data_keluarga_anggota_profile',$datamergeairsusuh);	
		}

		$data_keluarga_anggota_profile_kopi = array(
				'kode'				=> 'kesehatan_3_1_c_text',
				'value'				=> $parsingkeluargaanggota[162],
			);
		if (strtolower($parsingkeluargaanggota[162])!='') {
			$datamergekopi = array_merge($datapokokanggota,$data_keluarga_anggota_profile_kopi);
			$this->db->insert('data_keluarga_anggota_profile',$datamergekopi);	
		}

		$data_keluarga_anggota_profile_tehtawar = array(
				'id'				=> 'G',
				'id_data_keluarga'	=> $dataiddatakeluarga,
				'no_anggota'		=> $datanoanggota,
				'kode'				=> 'kesehatan_3_1_d_text',
				'value'				=> $parsingkeluargaanggota[163],
			);
		if (strtolower($parsingkeluargaanggota[163])!='') {
			$datamergetehtawar = array_merge($datapokokanggota,$data_keluarga_anggota_profile_tehtawar);
			$this->db->insert('data_keluarga_anggota_profile',$datamergetehtawar);	
		}

		$data_keluarga_anggota_profile_tehmanis = array(
				'kode'				=> 'kesehatan_3_1_e_text',
				'value'				=> $parsingkeluargaanggota[164],
			);
		if (strtolower($parsingkeluargaanggota[164])!='') {
			$datamergetehmanis = array_merge($datapokokanggota,$data_keluarga_anggota_profile_tehmanis);
			$this->db->insert('data_keluarga_anggota_profile',$datamergetehmanis);	
		}

		$data_keluarga_anggota_profile_jusbuah = array(
				'kode'				=> 'kesehatan_3_1_f_text',
				'value'				=> $parsingkeluargaanggota[165],
			);
		if (strtolower($parsingkeluargaanggota[165])!='') {
			$datamergejusbuah = array_merge($datapokokanggota,$data_keluarga_anggota_profile_jusbuah);
			$this->db->insert('data_keluarga_anggota_profile',$datamergejusbuah);	
		}

		$data_keluarga_anggota_profile_bersoda = array(
				'kode'				=> 'kesehatan_3_1_g_text',
				'value'				=> $parsingkeluargaanggota[166],
			);
		if (strtolower($parsingkeluargaanggota[166])!='') {
			$datamergebersoda = array_merge($datapokokanggota,$data_keluarga_anggota_profile_bersoda);
			$this->db->insert('data_keluarga_anggota_profile',$datamergebersoda);	
		}

		$data_keluarga_anggota_profile_beralkohol = array(
				'kode'				=> 'kesehatan_3_1_h_text',
				'value'				=> $parsingkeluargaanggota[167],
			);
		if (strtolower($parsingkeluargaanggota[167])!='') {
			$datamergeberalkohol = array_merge($datapokokanggota,$data_keluarga_anggota_profile_beralkohol);
			$this->db->insert('data_keluarga_anggota_profile',$datamergeberalkohol);	
		}

		$data_keluarga_anggota_profile_mslain = array(
				'kode'				=> 'kesehatan_3_1_i_text',
				'value'				=> $parsingkeluargaanggota[168],
			);
		if (strtolower($parsingkeluargaanggota[168])!='') {
			$datamergemslain = array_merge($datapokokanggota,$data_keluarga_anggota_profile_mslain);
			$this->db->insert('data_keluarga_anggota_profile',$datamergemslain);	
		}

		$data_keluarga_anggota_profile_pernahrontgen = array(
				'kode'				=> 'kesehatan_2_g_1_radi4',
				'value'				=> (strtolower($parsingkeluargaanggota[169])=='ya, dalam ≤ 1 bulan terakhir' ? '0' : (strtolower($parsingkeluargaanggota[169])=='ya, > 1 bulan - 12 bulan' ? '1' : (strtolower($parsingkeluargaanggota[169])=='tidak' ? '2' : '2'))),
			);
		if (strtolower($parsingkeluargaanggota[169])!='') {
			$datamergepernahrontgen = array_merge($datapokokanggota,$data_keluarga_anggota_profile_pernahrontgen);
			$this->db->insert('data_keluarga_anggota_profile',$datamergepernahrontgen);	
		}

		$data_keluarga_anggota_profile_demambatuk = array(
				'kode'				=> 'kesehatan_2_g_2_radi4',
				'value'				=> (strtolower($parsingkeluargaanggota[170])=='ya, dalam ≤ 1 bulan terakhir' ? '0' : (strtolower($parsingkeluargaanggota[170])=='ya, > 1 bulan - 12 bulan' ? '1' : (strtolower($parsingkeluargaanggota[170])=='tidak' ? '2' : '2'))),
			);
		if (strtolower($parsingkeluargaanggota[170])!='') {
			$datamergedemambatuk = array_merge($datapokokanggota,$data_keluarga_anggota_profile_demambatuk);
			$this->db->insert('data_keluarga_anggota_profile',$datamergedemambatuk);	
		}

		$data_keluarga_anggota_profile_kcepatnapas = array(
				'kode'				=> 'kesehatan_2_g_3_a_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[171])=='ya') {
			$datamergekcepatnapas= array_merge($datapokokanggota,$data_keluarga_anggota_profile_kcepatnapas);
			$this->db->insert('data_keluarga_anggota_profile',$datamergekcepatnapas);	
		}

		$data_keluarga_anggota_profile_knapascuping = array(
				'kode'				=> 'kesehatan_2_g_3_b_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[172])=='ya') {
			$datamergeknapascuping = array_merge($datapokokanggota,$data_keluarga_anggota_profile_knapascuping);
			$this->db->insert('data_keluarga_anggota_profile',$datamergeknapascuping);	
		}

		$data_keluarga_anggota_profile_kdindingkebawah = array(
				'kode'				=> 'kesehatan_2_g_3_c_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[173])=='ya') {
			$datamergekdindingkebawah= array_merge($datapokokanggota,$data_keluarga_anggota_profile_kdindingkebawah);
			$this->db->insert('data_keluarga_anggota_profile',$datamergekdindingkebawah);	
		}

		$data_keluarga_anggota_profile_dxgagalginjal = array(
				'kode'				=> 'kesehatan_2_g_1_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[174])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[174])=='tidak' ? '1' :'1')),
			);
		if (strtolower($parsingkeluargaanggota[174])!='') {
			$datamergedxgagalginjal = array_merge($datapokokanggota,$data_keluarga_anggota_profile_dxgagalginjal);
			$this->db->insert('data_keluarga_anggota_profile',$datamergedxgagalginjal);	
		}

		$data_keluarga_anggota_profile_dxbatuginjal = array(
				'kode'				=> 'kesehatan_2_g_2_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[175])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[175])=='tidak' ? '1' :'1')),
			);
		if (strtolower($parsingkeluargaanggota[175])!='') {
			$datamergedxbatuginjal = array_merge($datapokokanggota,$data_keluarga_anggota_profile_dxbatuginjal);
			$this->db->insert('data_keluarga_anggota_profile',$datamergedxbatuginjal);	
		}

		$data_keluarga_anggota_profile_akhirbatuk = array(
				'kode'				=> 'kesehatan_2_g_1_tb_radi3',
				'value'				=> (strtolower($parsingkeluargaanggota[176])=='ya, < 2 minggu terakhir' ? '0' : (strtolower($parsingkeluargaanggota[176])=='ya, ≥ 2 minggu' ? '1' : (strtolower($parsingkeluargaanggota[176])=='tidak' ? '2' :'2'))),
			);
		if (strtolower($parsingkeluargaanggota[176])!='') {
			$datamergeakhirbatuk = array_merge($datapokokanggota,$data_keluarga_anggota_profile_akhirbatuk);
			$this->db->insert('data_keluarga_anggota_profile',$datamergeakhirbatuk);	
		}

		$data_keluarga_anggota_profile_gbdahak = array(
				'kode'				=> 'kesehatan_2_g_2_a_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[177])=='ya') {
			$datamergegbdahak  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_gbdahak);
			$this->db->insert('data_keluarga_anggota_profile',$datamergegbdahak);	
		}

		$data_keluarga_anggota_profile_gbdarah = array(
				'kode'				=> 'kesehatan_2_g_2_b_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[178])=='ya') {
			$datamergegbdarah  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_gbdarah);
			$this->db->insert('data_keluarga_anggota_profile',$datamergegbdarah);	
		}

		$data_keluarga_anggota_profile_gbdemam = array(
				'kode'				=> 'kesehatan_2_g_2_c_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[179])=='ya') {
			$datamergegbdemam  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_gbdemam);
			$this->db->insert('data_keluarga_anggota_profile',$datamergegbdemam);	
		}

		$data_keluarga_anggota_profile_gbnyeridada = array(
				'kode'				=> 'kesehatan_2_g_2_d_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[180])=='ya') {
			$datamergegbnyeridada  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_gbnyeridada);
			$this->db->insert('data_keluarga_anggota_profile',$datamergegbnyeridada);	
		}

		$data_keluarga_anggota_profile_gbnapas = array(
				'kode'				=> 'kesehatan_2_g_2_e_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[181])=='ya') {
			$datamergegbnapas  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_gbnapas);
			$this->db->insert('data_keluarga_anggota_profile',$datamergegbnapas);	
		}

		$data_keluarga_anggota_profile_gbfisik = array(
				'kode'				=> 'kesehatan_2_g_2_f_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[182])=='ya') {
			$datamergegbfisik   = array_merge($datapokokanggota,$data_keluarga_anggota_profile_gbfisik);
			$this->db->insert('data_keluarga_anggota_profile',$datamergegbfisik);	
		}

		$data_keluarga_anggota_profile_gbmenurun = array(
				'kode'				=> 'kesehatan_2_g_2_g_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[183])=='ya') {
			$datamergegbmenuru  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_gbmenurun);
			$this->db->insert('data_keluarga_anggota_profile',$datamergegbmenuru);	
		}

		$data_keluarga_anggota_profile_gbbertambah = array(
				'kode'				=> 'kesehatan_2_g_2_h_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[184])=='ya') {
			$datamergegbbertambah  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_gbbertambah);
			$this->db->insert('data_keluarga_anggota_profile',$datamergegbbertambah);	
		}

		$data_keluarga_anggota_profile_tbkurangtahun = array(
				'kode'				=> 'kesehatan_2_g_3_a_tb_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[185])=='ya') {
			$datamergetbkurangtahun  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_tbkurangtahun);
			$this->db->insert('data_keluarga_anggota_profile',$datamergetbkurangtahun);	
		}

		$data_keluarga_anggota_profile_tblebihtahun = array(
				'kode'				=> 'kesehatan_2_g_3_b_tb_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[186])=='ya') {
			$datamergetblebihtahun = array_merge($datapokokanggota,$data_keluarga_anggota_profile_tblebihtahun);
			$this->db->insert('data_keluarga_anggota_profile',$datamergetblebihtahun);	
		}

		$data_keluarga_anggota_profile_tbtidak = array(
				'kode'				=> 'kesehatan_2_g_3_c_tb_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[187])=='ya') {
			$datamergetbtidak  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_tbtidak);
			$this->db->insert('data_keluarga_anggota_profile',$datamergetbtidak);	
		}

		$data_keluarga_anggota_profile_pmdahak = array(
				'kode'				=> 'kesehatan_2_g_4_a_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[188])=='ya') {
			$datamergepmdahak  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_pmdahak);
			$this->db->insert('data_keluarga_anggota_profile',$datamergepmdahak);	
		}

		$data_keluarga_anggota_profile_pmrotgen = array(
				'kode'				=> 'kesehatan_2_g_4_b_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[189])=='ya') {
			$datamergepmrotgen  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_pmrotgen);
			$this->db->insert('data_keluarga_anggota_profile',$datamergepmrotgen);	
		}

		$data_keluarga_anggota_profile_dxkanker = array(
				'kode'				=> 'kesehatan_3_g_1_kk_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[190])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[190])=='tidak' ? '1' :'1')),
			);
		if (strtolower($parsingkeluargaanggota[190])!='') {
			$datamergedxkanker  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_dxkanker);
			$this->db->insert('data_keluarga_anggota_profile',$datamergedxkanker);	
		}

		$data_keluarga_anggota_profile_tahunkanker = array(
				'kode'				=> 'kesehatan_3_g_2_kk_text',
				'value'				=> $parsingkeluargaanggota[191],
			);
		if (strtolower($parsingkeluargaanggota[191])!='') {
			$datamergetahunkanker   = array_merge($datapokokanggota,$data_keluarga_anggota_profile_tahunkanker);
			$this->db->insert('data_keluarga_anggota_profile',$datamergetahunkanker);	
		}

		$data_keluarga_anggota_profile_jkrahim = array(
				'kode'				=> 'kesehatan_3_g_3_kk_a_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[192])=='ya') {
			$datamergedjkrahim   = array_merge($datapokokanggota,$data_keluarga_anggota_profile_jkrahim);
			$this->db->insert('data_keluarga_anggota_profile',$datamergedjkrahim);	
		}

		$data_keluarga_anggota_profile_jkpayudara = array(
				'kode'				=> 'kesehatan_3_g_3_kk_b_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[193])=='ya') {
			$datamergejkpayudara  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_jkpayudara);
			$this->db->insert('data_keluarga_anggota_profile',$datamergejkpayudara);	
		}

		$data_keluarga_anggota_profile_jkprostat = array(
				'kode'				=> 'kesehatan_3_g_3_kk_c_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[194])=='ya') {
			$datamergejkprostat  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_jkprostat);
			$this->db->insert('data_keluarga_anggota_profile',$datamergejkprostat);	
		}

		$data_keluarga_anggota_profile_jkusus = array(
				'kode'				=> 'kesehatan_3_g_3_kk_d_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[195])=='ya') {
			$datamergejkusus = array_merge($datapokokanggota,$data_keluarga_anggota_profile_jkusus);
			$this->db->insert('data_keluarga_anggota_profile',$datamergejkusus);	
		}

		$data_keluarga_anggota_profile_jkbronkus = array(
				'kode'				=> 'kesehatan_3_g_3_kk_e_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[196])=='ya') {
			$datamergejkbronkus  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_jkbronkus);
			$this->db->insert('data_keluarga_anggota_profile',$datamergejkbronkus);	
		}

		$data_keluarga_anggota_profile_jknasofaring = array(
				'kode'				=> 'kesehatan_3_g_3_kk_f_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[197])=='ya') {
			$datamergejknasofaring  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_jknasofaring);
			$this->db->insert('data_keluarga_anggota_profile',$datamergejknasofaring);	
		}

		$data_keluarga_anggota_profile_jkgetahbening = array(
				'kode'				=> 'kesehatan_3_g_3_kk_g_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[198])=='ya') {
			$datamergejkgetahbening = array_merge($datapokokanggota,$data_keluarga_anggota_profile_jkgetahbening);
			$this->db->insert('data_keluarga_anggota_profile',$datamergejkgetahbening);	
		}

		$data_keluarga_anggota_profile_jklainnya = array(
				'kode'				=> 'kesehatan_3_g_3_kk_h_text',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[199])!='') {
			$datamergejklainnya = array_merge($datapokokanggota,$data_keluarga_anggota_profile_jklainnya);
			$this->db->insert('data_keluarga_anggota_profile',$datamergejklainnya);	
		}

		$data_keluarga_anggota_profile_tesiva = array(
				'kode'				=> 'kesehatan_3_g_4_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[200])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[200])=='tidak' ? '1' :'1')),
			);
		if (strtolower($parsingkeluargaanggota[200])!='') {
			$datamergetesiva = array_merge($datapokokanggota,$data_keluarga_anggota_profile_tesiva);
			$this->db->insert('data_keluarga_anggota_profile',$datamergetesiva);	
		}

		$data_keluarga_anggota_profile_pkoperasi= array(
				'kode'				=> 'kesehatan_3_g_5_kk_a_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[201])=='ya') {
			$datamergepkoperasi = array_merge($datapokokanggota,$data_keluarga_anggota_profile_pkoperasi);
			$this->db->insert('data_keluarga_anggota_profile',$datamergepkoperasi);	
		}

		$data_keluarga_anggota_profile_pkpenyinaran = array(
				'kode'				=> 'kesehatan_3_g_5_kk_b_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[202])=='ya') {
			$datamergepkpenyinaran = array_merge($datapokokanggota,$data_keluarga_anggota_profile_pkpenyinaran);
			$this->db->insert('data_keluarga_anggota_profile',$datamergepkpenyinaran);	
		}

		$data_keluarga_anggota_profile_pkkemot = array(
				'kode'				=> 'kesehatan_3_g_5_kk_c_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[203])=='ya') {
			$datamergepkkemot = array_merge($datapokokanggota,$data_keluarga_anggota_profile_pkkemot);
			$this->db->insert('data_keluarga_anggota_profile',$datamergepkkemot);	
		}

		$data_keluarga_anggota_profile_pklainnya = array(
				'kode'				=> 'kesehatan_3_g_d_text',
				'value'				=> $parsingkeluargaanggota[204],
			);
		if (strtolower($parsingkeluargaanggota[204])!='') {
			$datamergepklainnya = array_merge($datapokokanggota,$data_keluarga_anggota_profile_pklainnya);
			$this->db->insert('data_keluarga_anggota_profile',$datamergepklainnya);	
		}

		$data_keluarga_anggota_profile_papsmear = array(
				'kode'				=> 'kesehatan_3_g_6_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[205])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[205])=='tidak' ? '1' :'1')),
			);
		if (strtolower($parsingkeluargaanggota[205])!='') {
			$datamergepapsmear = array_merge($datapokokanggota,$data_keluarga_anggota_profile_papsmear);
			$this->db->insert('data_keluarga_anggota_profile',$datamergepapsmear);	
		}

		$data_keluarga_anggota_profile_gejalasesaknapas = array(
				'kode'				=> 'kesehatan_3_g_1_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[206])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[206])=='tidak' ? '1' :'1')),
			);
		if (strtolower($parsingkeluargaanggota[206])!='') {
			$datamergegejalasesaknapas = array_merge($datapokokanggota,$data_keluarga_anggota_profile_gejalasesaknapas);
			$this->db->insert('data_keluarga_anggota_profile',$datamergegejalasesaknapas);	
		}

		$data_keluarga_anggota_profile_gsndingin = array(
				'kode'				=> 'kesehatan_3_g_2_sn_a_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[207])=='ya') {
			$datamergegsndingi = array_merge($datapokokanggota,$data_keluarga_anggota_profile_gsndingin);
			$this->db->insert('data_keluarga_anggota_profile',$datamergegsndingi);	
		}

		$data_keluarga_anggota_profile_gsndebu = array(
				'kode'				=> 'kesehatan_3_g_2_sn_b_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[208])=='ya') {
			$datamergegsndebu = array_merge($datapokokanggota,$data_keluarga_anggota_profile_gsndebu);
			$this->db->insert('data_keluarga_anggota_profile',$datamergegsndebu);	
		}

		$data_keluarga_anggota_profile_gsnrokok = array(
				'kode'				=> 'kesehatan_3_g_2_sn_c_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[209])=='ya') {
			$datamergegsnrokok = array_merge($datapokokanggota,$data_keluarga_anggota_profile_gsnrokok);
			$this->db->insert('data_keluarga_anggota_profile',$datamergegsnrokok);	
		}

		$data_keluarga_anggota_profile_gsnstress = array(
				'kode'				=> 'kesehatan_3_g_2_sn_d_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[210])=='ya') {
			$datamergegsnstress = array_merge($datapokokanggota,$data_keluarga_anggota_profile_gsnstress);
			$this->db->insert('data_keluarga_anggota_profile',$datamergegsnstress);	
		}

		$data_keluarga_anggota_profilegsnflue = array(
				'kode'				=> 'kesehatan_3_g_2_sn_e_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[211])=='ya') {
			$datamergeprofilegsnflue = array_merge($datapokokanggota,$data_keluarga_anggota_profilegsnflue);
			$this->db->insert('data_keluarga_anggota_profile',$datamergeprofilegsnflue);	
		}

		$data_keluarga_anggota_profile_gsnkelahan = array(
				'kode'				=> 'kesehatan_3_g_2_sn_f_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[212])=='ya') {
			$datamergegsnkelahan = array_merge($datapokokanggota,$data_keluarga_anggota_profile_gsnkelahan);
			$this->db->insert('data_keluarga_anggota_profile',$datamergegsnkelahan);	
		}

		$data_keluarga_anggota_profile_gsnalergi = array(
				'kode'				=> 'kesehatan_3_g_2_sn_g_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[213])=='ya') {
			$datamergegsnalergi = array_merge($datapokokanggota,$data_keluarga_anggota_profile_gsnalergi);
			$this->db->insert('data_keluarga_anggota_profile',$datamergegsnalergi);	
		}

		$data_keluarga_anggota_profile_gsnmakanan = array(
				'kode'				=> 'kesehatan_3_g_2_sn_h_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[214])=='ya') {
			$datamergegsnmakanan = array_merge($datapokokanggota,$data_keluarga_anggota_profile_gsnmakanan);
			$this->db->insert('data_keluarga_anggota_profile',$datamergegsnmakanan);	
		}

		$data_keluarga_anggota_profile_snmengi = array(
				'kode'				=> 'kesehatan_3_g_3_mg_a_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[215])=='ya') {
			$datamergesnmengi = array_merge($datapokokanggota,$data_keluarga_anggota_profile_snmengi);
			$this->db->insert('data_keluarga_anggota_profile',$datamergesnmengi);	
		}

		$data_keluarga_anggota_profile_snmenghilangdengan = array(
				'kode'				=> 'kesehatan_3_g_3_mg_b_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[216])=='ya') {
			$datamergesnmenghilangdengan = array_merge($datapokokanggota,$data_keluarga_anggota_profile_snmenghilangdengan);
			$this->db->insert('data_keluarga_anggota_profile',$datamergesnmenghilangdengan);	
		}

		$data_keluarga_anggota_profile_snmenghilangtanpa = array(
				'kode'				=> 'kesehatan_3_g_3_mg_c_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[217])=='ya') {
			$datamergesnmenghilangtanpa = array_merge($datapokokanggota,$data_keluarga_anggota_profile_snmenghilangtanpa);
			$this->db->insert('data_keluarga_anggota_profile',$datamergesnmenghilangtanpa);	
		}

		$data_keluarga_anggota_profile_snlebihberat = array(
				'kode'				=> 'kesehatan_3_g_3_mg_d_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[218])=='ya') {
			$datamergesnlebihberat = array_merge($datapokokanggota,$data_keluarga_anggota_profile_snlebihberat);
			$this->db->insert('data_keluarga_anggota_profile',$datamergesnlebihberat);	
		}

		$data_keluarga_anggota_profile_merasakeluhan = array(
				'kode'				=> 'kesehatan_3_g_4_mg_d_text',
				'value'				=> $parsingkeluargaanggota[219],
			);
		if (strtolower($parsingkeluargaanggota[219])!='') {
			$datamergemerasakeluhan = array_merge($datapokokanggota,$data_keluarga_anggota_profile_merasakeluhan);
			$this->db->insert('data_keluarga_anggota_profile',$datamergemerasakeluhan);	
		}

		$data_keluarga_anggota_profile_napaskambuh12 = array(
				'kode'				=> 'kesehatan_3_g_5_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[220])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[220])=='tidak' ?'1' :'1')),
			);
		if (strtolower($parsingkeluargaanggota[220])!='') {
			$datamergenapaskambuh12 = array_merge($datapokokanggota,$data_keluarga_anggota_profile_napaskambuh12);
			$this->db->insert('data_keluarga_anggota_profile',$datamergenapaskambuh12);	
		}

		$data_keluarga_anggota_profile_dxkencingmanis = array(
				'kode'				=> 'kesehatan_4_g_1_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[221])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[221])=='tidak' ? '1' :'1')),
			);
		$datamergedxkencingmanis = array_merge($datapokokanggota,$data_keluarga_anggota_profile_dxkencingmanis);
		$this->db->insert('data_keluarga_anggota_profile',$datamergedxkencingmanis);	

		$data_keluarga_anggota_profile_pdmdiet = array(
				'kode'				=> 'kesehatan_4_g_2_p_a_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[222])=='ya') {
			$datamergepdmdiet = array_merge($datapokokanggota,$data_keluarga_anggota_profile_pdmdiet);
			$this->db->insert('data_keluarga_anggota_profile',$datamergepdmdiet);	
		}

		$data_keluarga_anggota_profile_dmolahraga = array(
				'kode'				=> 'kesehatan_4_g_2_p_b_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[223])=='ya') {
			$datamergedmolahraga = array_merge($datapokokanggota,$data_keluarga_anggota_profile_dmolahraga);
			$this->db->insert('data_keluarga_anggota_profile',$datamergedmolahraga);	
		}

		$data_keluarga_anggota_profile_dmobat = array(
				'kode'				=> 'kesehatan_4_g_2_p_c_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[224])=='ya') {
			$datamergedmobat = array_merge($datapokokanggota,$data_keluarga_anggota_profile_dmobat);
			$this->db->insert('data_keluarga_anggota_profile',$datamergedmobat);	
		}

		$data_keluarga_anggota_profile_dminsulin = array(
				'kode'				=> 'kesehatan_4_g_2_p_d_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[225])=='ya') {
			$datamergedminsulin  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_dminsulin);
			$this->db->insert('data_keluarga_anggota_profile',$datamergedminsulin);	
		}

		$data_keluarga_anggota_profile_bulanlapar = array(
				'kode'				=> 'kesehatan_4_g_3_p_a_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[226])=='ya') {
			$datamergebulanlapar  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_bulanlapar);
			$this->db->insert('data_keluarga_anggota_profile',$datamergebulanlapar);	
		}

		$data_keluarga_anggota_profile_bulanhaus = array(
				'kode'				=> 'kesehatan_4_g_3_p_b_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[227])=='ya') {
			$datamergebulanhaus  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_bulanhaus);
			$this->db->insert('data_keluarga_anggota_profile',$datamergebulanhaus);	
		}

		$data_keluarga_anggota_profile_bulanbanyak = array(
				'kode'				=> 'kesehatan_4_g_3_p_c_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[228])=='ya') {
			$datamergebulanbanyak  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_bulanbanyak);
			$this->db->insert('data_keluarga_anggota_profile',$datamergebulanbanyak);	
		}

		$data_keluarga_anggota_profile_bulanturun = array(
				'kode'				=> 'kesehatan_4_g_3_p_d_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[229])=='ya') {
			$datamergebulanturun  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_bulanturun);
			$this->db->insert('data_keluarga_anggota_profile',$datamergebulanturun);	
		}

		$data_keluarga_anggota_profile_dxht= array(
				'kode'				=> 'kesehatan_4_g_1_hp_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[230])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[230])=='tidak' ? '1' :'1')),
			);
		$datamergedxht  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_dxht);
		$this->db->insert('data_keluarga_anggota_profile',$datamergedxht);	

		$data_keluarga_anggota_profile_dxhtpertama = array(
				'kode'				=> 'kesehatan_4_g_2_hp_text',
				'value'				=> $parsingkeluargaanggota[231],
			);
		if ($parsingkeluargaanggota[231] != '') {
			$datamergedxhtpertam  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_dxhtpertama);
			$this->db->insert('data_keluarga_anggota_profile',$datamergedxhtpertam);	
		}

		$data_keluarga_anggota_profile_dxhtsedangobat = array(
				'kode'				=> 'kesehatan_4_g_3_hp_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[232])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[232])=='tidak' ? '1' :'1')),
			);
		$datamergedxhtsedangobat  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_dxhtsedangobat);
		$this->db->insert('data_keluarga_anggota_profile',$datamergedxhtsedangobat);	

		$data_keluarga_anggota_profile_dxjantung= array(
				'kode'				=> 'kesehatan_4_g_1_jk_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[233])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[233])=='tidak' ? '1' :'1')),
			);
		$datamergedxjantung  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_dxjantung);
		$this->db->insert('data_keluarga_anggota_profile',$datamergedxjantung);	
		
		$data_keluarga_anggota_profile_dxjantunpertama = array(
				'kode'				=> 'kesehatan_4_g_2_jk_text',
				'value'				=> $parsingkeluargaanggota[234],
			);
		if ($parsingkeluargaanggota[234] != '') {
			$datamergedxjantunpertama = array_merge($datapokokanggota,$data_keluarga_anggota_profile_dxjantunpertama);
			$this->db->insert('data_keluarga_anggota_profile',$datamergedxjantunpertama);	
		}

		$data_keluarga_anggota_profile_jantungnyeri = array(
				'kode'				=> 'kesehatan_4_g_3_jk_a_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[235])=='ya') {
			$datamergejantungnyeri  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_jantungnyeri);
			$this->db->insert('data_keluarga_anggota_profile',$datamergejantungnyeri);	
		}

		$data_keluarga_anggota_profile_jantungkelengan = array(
				'kode'				=> 'kesehatan_4_g_3_jk_b_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[236])=='ya') {
			$datamergedxjantung  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_jantungkelengan);
			$this->db->insert('data_keluarga_anggota_profile',$datamergedxjantung);	
		}

		$data_keluarga_anggota_profile_jantungtergesa = array(
				'kode'				=> 'kesehatan_4_g_3_jk_c_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[237])=='ya') {
			$datamergejantungtergesa = array_merge($datapokokanggota,$data_keluarga_anggota_profile_jantungtergesa);
			$this->db->insert('data_keluarga_anggota_profile',$datamergejantungtergesa);	
		}

		$data_keluarga_anggota_profile_jantungaktivitas = array(
				'kode'				=> 'kesehatan_4_g_3_jk_d_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[238])=='ya') {
			$datamergejantungaktivitas = array_merge($datapokokanggota,$data_keluarga_anggota_profile_jantungaktivitas);
			$this->db->insert('data_keluarga_anggota_profile',$datamergejantungaktivitas);	
		}

		$data_keluarga_anggota_profile_dxstorke= array(
				'kode'				=> 'kesehatan_4_g_1_sk_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[239])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[239])=='tidak' ? '1' :'1')),
			);
		$datamergedxstorke = array_merge($datapokokanggota,$data_keluarga_anggota_profile_dxstorke);
		$this->db->insert('data_keluarga_anggota_profile',$datamergedxstorke);	

		$data_keluarga_anggota_profile_dxstrokepertama = array(
				'kode'				=> 'kesehatan_4_g_2_sk_text',
				'value'				=> $parsingkeluargaanggota[240],
			);
		if ($parsingkeluargaanggota[240] != '') {
			$datamergedxstrokepertama  = array_merge($datapokokanggota,$data_keluarga_anggota_profile_dxstrokepertama);
			$this->db->insert('data_keluarga_anggota_profile',$datamergedxstrokepertama);	
		}

		$data_keluarga_anggota_profile_sksisitubuh = array(
				'kode'				=> 'kesehatan_4_g_3_sk_a_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[241])=='ya') {
			$datamergesksisitubuh= array_merge($datapokokanggota,$data_keluarga_anggota_profile_sksisitubuh);
			$this->db->insert('data_keluarga_anggota_profile',$datamergesksisitubuh);	
		}

		$data_keluarga_anggota_profile_skkesemutan = array(
				'kode'				=> 'kesehatan_4_g_3_sk_b_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[242])=='ya') {
			$datamergeskkesemutan= array_merge($datapokokanggota,$data_keluarga_anggota_profile_skkesemutan);
			$this->db->insert('data_keluarga_anggota_profile',$datamergeskkesemutan);	
		}

		$data_keluarga_anggota_profile_skmencong = array(
				'kode'				=> 'kesehatan_4_g_3_sk_c_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[243])=='ya') {
			$datamergeskmencong= array_merge($datapokokanggota,$data_keluarga_anggota_profile_skmencong);
			$this->db->insert('data_keluarga_anggota_profile',$datamergeskmencong);	
		}

		$data_keluarga_anggota_profile_skpelo = array(
				'kode'				=> 'kesehatan_4_g_3_sk_d_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[244])=='ya') {
			$datamergeskpelo= array_merge($datapokokanggota,$data_keluarga_anggota_profile_skpelo);
			$this->db->insert('data_keluarga_anggota_profile',$datamergeskpelo);	
		}

		$data_keluarga_anggota_profile_skkomunikasi = array(
				'kode'				=> 'kesehatan_4_g_3_sk_e_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[245])=='ya') {
			$datamergeskkomunikasi= array_merge($datapokokanggota,$data_keluarga_anggota_profile_skkomunikasi);
			$this->db->insert('data_keluarga_anggota_profile',$datamergeskkomunikasi);	
		}

		$data_keluarga_anggota_profile_5sakitkepala = array(
				'kode'				=> 'kesehatan_5_g_1_kk_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[246])=='ya') {
			$datamerge5sakitkepalai= array_merge($datapokokanggota,$data_keluarga_anggota_profile_5sakitkepala);
			$this->db->insert('data_keluarga_anggota_profile',$datamerge5sakitkepalai);	
		}


		$data_keluarga_anggota_profile_5tidaknapsu = array(
				'kode'				=> 'kesehatan_5_g_2_kk_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[247])=='ya') {
			$datamerge5tidaknapsu= array_merge($datapokokanggota,$data_keluarga_anggota_profile_5tidaknapsu);
			$this->db->insert('data_keluarga_anggota_profile',$datamerge5tidaknapsu);	
		}


		$data_keluarga_anggota_profile_5sulittidur = array(
				'kode'				=> 'kesehatan_5_g_3_kk_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[248])=='ya') {
			$datamerge5sulittidur= array_merge($datapokokanggota,$data_keluarga_anggota_profile_5sulittidur);
			$this->db->insert('data_keluarga_anggota_profile',$datamerge5sulittidur);	
		}


		$data_keluarga_anggota_profile_5mudahtakut = array(
				'kode'				=> 'kesehatan_5_g_4_kk_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[249])=='ya') {
			$datamerge5mudahtakut= array_merge($datapokokanggota,$data_keluarga_anggota_profile_5mudahtakut);
			$this->db->insert('data_keluarga_anggota_profile',$datamerge5mudahtakut);	
		}


		$data_keluarga_anggota_profile_5tegang = array(
				'kode'				=> 'kesehatan_5_g_5_kk_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[250])=='ya') {
			$datamerge5tegang= array_merge($datapokokanggota,$data_keluarga_anggota_profile_5tegang);
			$this->db->insert('data_keluarga_anggota_profile',$datamerge5tegang);	
		}


		$data_keluarga_anggota_profile_5gemetar = array(
				'kode'				=> 'kesehatan_5_g_6_kk_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[251])=='ya') {
			$datamerge5gemetar= array_merge($datapokokanggota,$data_keluarga_anggota_profile_5gemetar);
			$this->db->insert('data_keluarga_anggota_profile',$datamerge5gemetar);	
		}

		$data_keluarga_anggota_profile_5terganggu = array(
				'kode'				=> 'kesehatan_5_g_7_kk_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[252])=='ya') {
			$datamerge5terganggu= array_merge($datapokokanggota,$data_keluarga_anggota_profile_5terganggu);
			$this->db->insert('data_keluarga_anggota_profile',$datamerge5terganggu);	
		}

		$data_keluarga_anggota_profile_5jernih = array(
				'kode'				=> 'kesehatan_5_g_8_kk_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[253])=='ya') {
			$datamerge5jernih= array_merge($datapokokanggota,$data_keluarga_anggota_profile_5jernih);
			$this->db->insert('data_keluarga_anggota_profile',$datamerge5jernih);	
		}

		$data_keluarga_anggota_profile_5bahagia = array(
				'kode'				=> 'kesehatan_5_g_9_kk_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[254])=='ya') {
			$datamerge_5bahagiah= array_merge($datapokokanggota,$data_keluarga_anggota_profile_5bahagia);
			$this->db->insert('data_keluarga_anggota_profile',$datamerge_5bahagiah);	
		}

		$data_keluarga_anggota_profile_5menangis = array(
				'kode'				=> 'kesehatan_5_g_10_kk_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[255])=='ya') {
			$datamerge55menangis= array_merge($datapokokanggota,$data_keluarga_anggota_profile_5menangis);
			$this->db->insert('data_keluarga_anggota_profile',$datamerge55menangis);	
		}

		$data_keluarga_anggota_profile_5sulit = array(
				'kode'				=> 'kesehatan_5_g_11_kk_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[256])=='ya') {
			$datamerge5sulit = array_merge($datapokokanggota,$data_keluarga_anggota_profile_5sulit);
			$this->db->insert('data_keluarga_anggota_profile',$datamerge5sulit);	
		}

		$data_keluarga_anggota_profile_5keputusan = array(
				'kode'				=> 'kesehatan_5_g_12_kk_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[257])=='ya') {
			$datamerge5keputusan= array_merge($datapokokanggota,$data_keluarga_anggota_profile_5keputusan);
			$this->db->insert('data_keluarga_anggota_profile',$datamerge5keputusan);	
		}

		$data_keluarga_anggota_profile_5pekerjaan = array(
				'kode'				=> 'kesehatan_5_g_13_kk_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[258])=='ya') {
			$datamerge5pekerjaan= array_merge($datapokokanggota,$data_keluarga_anggota_profile_5pekerjaan);
			$this->db->insert('data_keluarga_anggota_profile',$datamerge5pekerjaan);	
		}

		$data_keluarga_anggota_profile_5manfaat = array(
				'kode'				=> 'kesehatan_5_g_14_kk_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[259])=='ya') {
			$datamerge5manfaat= array_merge($datapokokanggota,$data_keluarga_anggota_profile_5manfaat);
			$this->db->insert('data_keluarga_anggota_profile',$datamerge5manfaat);	
		}

		$data_keluarga_anggota_profile_5minat = array(
				'kode'				=> 'kesehatan_5_g_15_kk_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[260])=='ya') {
			$datamerge5minat = array_merge($datapokokanggota,$data_keluarga_anggota_profile_5minat);
			$this->db->insert('data_keluarga_anggota_profile',$datamerge5minat);	
		}

		$data_keluarga_anggota_profile_5berharga = array(
				'kode'				=> 'kesehatan_5_g_17_kk_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[261])=='ya') {
			$datamerge5berharga= array_merge($datapokokanggota,$data_keluarga_anggota_profile_5berharga);
			$this->db->insert('data_keluarga_anggota_profile',$datamerge5berharga);	
		}

		$data_keluarga_anggota_profile_5hidup = array(
				'kode'				=> 'kesehatan_5_g_18_kk_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[262])=='ya') {
			$datamerge5hidup= array_merge($datapokokanggota,$data_keluarga_anggota_profile_5hidup);
			$this->db->insert('data_keluarga_anggota_profile',$datamerge5hidup);	
		}

		$data_keluarga_anggota_profile_5merasalelah = array(
				'kode'				=> 'kesehatan_5_g_19_kk_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[263])=='ya') {
			$datamerge5merasalelah= array_merge($datapokokanggota,$data_keluarga_anggota_profile_5merasalelah);
			$this->db->insert('data_keluarga_anggota_profile',$datamerge5merasalelah);	
		}
		$data_keluarga_anggota_profile_5tidakenak = array(
				'kode'				=> 'kesehatan_5_g_20_kk_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[264])=='ya') {
			$datamerge5tidakena= array_merge($datapokokanggota,$data_keluarga_anggota_profile_5tidakenak);
			$this->db->insert('data_keluarga_anggota_profile',$datamerge5tidakena);	
		}

		$data_keluarga_anggota_profile_5mudahlelah = array(
				'kode'				=> 'kesehatan_5_g_23_kk_cebox',
				'value'				=> '1',
			);
		if (strtolower($parsingkeluargaanggota[265])=='ya') {
			$datamerge5mudahlelah= array_merge($datapokokanggota,$data_keluarga_anggota_profile_5mudahlelah);
			$this->db->insert('data_keluarga_anggota_profile',$datamerge5mudahlelah);	
		}

		$data_keluarga_anggota_profile_120fayankes= array(
				'kode'				=> 'kesehatan_5_g_21_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[266])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[266])=='tidak' ? '1' :'1')),
			);
		$datamerge120fayankes= array_merge($datapokokanggota,$data_keluarga_anggota_profile_120fayankes);
		$this->db->insert('data_keluarga_anggota_profile',$datamerge120fayankes);	

		$data_keluarga_anggota_profile_2minggu= array(
				'kode'				=> 'kesehatan_5_g_22_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[267])=='ya' ? '0' : (strtolower($parsingkeluargaanggota[267])=='tidak' ? '1' :'1')),
			);
		$datamerge2minggu= array_merge($datapokokanggota,$data_keluarga_anggota_profile_2minggu);
		$this->db->insert('data_keluarga_anggota_profile',$datamerge2minggu);	


		$data_keluarga_anggota_profile_statusimunisasi= array(
				'kode'				=> 'kesehatan_6_g_1_radi4',
				'value'				=> (strtolower($parsingkeluargaanggota[268])=='lengkap' ? '0' : (strtolower($parsingkeluargaanggota[268])=='tidak tahu' ? '1' : (strtolower($parsingkeluargaanggota[268])=='lengkap sesuai umur' ? '2' : (strtolower($parsingkeluargaanggota[268])=='tidak lengkap' ? '3' : $parsingkeluargaanggota[268]='')))),
			);
		if ($parsingkeluargaanggota[268]!='') {
			$datamergestatusimunisasi= array_merge($datapokokanggota,$data_keluarga_anggota_profile_statusimunisasi);
			$this->db->insert('data_keluarga_anggota_profile',$datamergestatusimunisasi);	
		}	

		$data_keluarga_anggota_profile_olahraga= array(
				'kode'				=> 'kesehatan_6_g_2_ol_text',
				'value'				=> $parsingkeluargaanggota[269],
			);
		if ($parsingkeluargaanggota[269]!='') {
			$datamergeolahraga= array_merge($datapokokanggota,$data_keluarga_anggota_profile_olahraga);
			$this->db->insert('data_keluarga_anggota_profile',$datamergeolahraga);	
		}

		$data_keluarga_anggota_profile_tidur= array(
				'kode'				=> 'kesehatan_6_g_2_td_text',
				'value'				=> $parsingkeluargaanggota[270],
			);
		if ($parsingkeluargaanggota[270]!='') {
			$datamergetidur= array_merge($datapokokanggota,$data_keluarga_anggota_profile_tidur);
			$this->db->insert('data_keluarga_anggota_profile',$datamergetidur);	
		}

		$data_keluarga_anggota_profile_td= array(
				'kode'				=> 'kesehatan_6_g_3_td_text',
				'value'				=> $parsingkeluargaanggota[271],
			);
		if ($parsingkeluargaanggota[271]!='') {
			$datamergetd= array_merge($datapokokanggota,$data_keluarga_anggota_profile_td);
			$this->db->insert('data_keluarga_anggota_profile',$datamergetd);	
		}


		$data_keluarga_anggota_profile_nadi= array(
				'kode'				=> 'kesehatan_6_g_3_tn_text',
				'value'				=> $parsingkeluargaanggota[272],
			);
		if ($parsingkeluargaanggota[272]!='') {
			$datamergenadi= array_merge($datapokokanggota,$data_keluarga_anggota_profile_nadi);
			$this->db->insert('data_keluarga_anggota_profile',$datamergenadi);	
		}

		$data_keluarga_anggota_profile_pernapasan= array(
				'kode'				=> 'kesehatan_6_g_3_p_text',
				'value'				=> $parsingkeluargaanggota[273],
			);
		if ($parsingkeluargaanggota[273]!='') {
			$datamergepernapasan= array_merge($datapokokanggota,$data_keluarga_anggota_profile_pernapasan);
			$this->db->insert('data_keluarga_anggota_profile',$datamergepernapasan);	
		}

		$data_keluarga_anggota_profile_suhu= array(
				'kode'				=> 'kesehatan_6_g_3_s_text',
				'value'				=> $parsingkeluargaanggota[274],
			);
		if ($parsingkeluargaanggota[274]!='') {
			$datamergesuhu= array_merge($datapokokanggota,$data_keluarga_anggota_profile_suhu);
			$this->db->insert('data_keluarga_anggota_profile',$datamergesuhu);	
		}

		$data_keluarga_anggota_profile_tinggi= array(
				'kode'				=> 'kesehatan_6_g_4_at_text',
				'value'				=> $parsingkeluargaanggota[275],
			);
		if ($parsingkeluargaanggota[275]!='') {
			$datamergetinggi= array_merge($datapokokanggota,$data_keluarga_anggota_profile_tinggi);
			$this->db->insert('data_keluarga_anggota_profile',$datamergetinggi);	
		}

		$data_keluarga_anggota_profile_bb= array(				
				'kode'				=> 'kesehatan_6_g_4_bb_text',
				'value'				=> $parsingkeluargaanggota[276],
			);
		if ($parsingkeluargaanggota[276]!='') {
			$datamergebb= array_merge($datapokokanggota,$data_keluarga_anggota_profile_bb);
			$this->db->insert('data_keluarga_anggota_profile',$datamergebb);	
		}
		$data_keluarga_anggota_profile_statusgizi= array(
				'kode'				=> 'kesehatan_6_g_4_sg_text',
				'value'				=> $parsingkeluargaanggota[278],
			);
		if ($parsingkeluargaanggota[276]!='') {
			$datamergestatusgizi= array_merge($datapokokanggota,$data_keluarga_anggota_profile_statusgizi);
			$this->db->insert('data_keluarga_anggota_profile',$datamergestatusgizi);	
		}

		$data_keluarga_anggota_profile_konjunctiva= array(
				'kode'				=> 'kesehatan_6_g_5_radio',
				'value'				=> (strtolower($parsingkeluargaanggota[277])=='pucat' ? '0' : (strtolower($parsingkeluargaanggota[277])=='normal' ? '1' : $parsingkeluargaanggota[277]='')),
			);
		if ($parsingkeluargaanggota[277]!='') {
			$datamergekonjunctiva= array_merge($datapokokanggota,$data_keluarga_anggota_profile_konjunctiva);
			$this->db->insert('data_keluarga_anggota_profile',$datamergekonjunctiva);	
		}

		$data_keluarga_anggota_profile_riwayatkesehatan= array(
				'kode'				=> 'kesehatan_6_g_6_text',
				'value'				=> $parsingkeluargaanggota[279],
			);
		if ($parsingkeluargaanggota[279]!='') {
			$datamergeriwayatkesehatan= array_merge($datapokokanggota,$data_keluarga_anggota_profile_riwayatkesehatan);
			$this->db->insert('data_keluarga_anggota_profile',$datamergeriwayatkesehatan);	
		}

		$data_keluarga_anggota_profile_analisakesehatan= array(
				'id'				=> 'G',
				'id_data_keluarga'	=> $dataiddatakeluarga,
				'no_anggota'		=> $datanoanggota,
				'kode'				=> 'kesehatan_6_g_7_text',
				'value'				=> $parsingkeluargaanggota[280],
			);
		if ($parsingkeluargaanggota[280]!='') {
			$datamergeanalisakesehatan= array_merge($datapokokanggota,$data_keluarga_anggota_profile_analisakesehatan);
			$this->db->insert('data_keluarga_anggota_profile',$datamergeanalisakesehatan);	
		}
	}
    function doimport(){

        $fileName = time().'_'.$_FILES['file_excel']['name'];

        $config['upload_path'] = './assets/'; 
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size'] = 10000;
         
        $this->load->library('upload',$config);
        $this->upload->initialize($config);
         
        if(! $this->upload->do_upload('file_excel') )
        $this->upload->display_errors();
             
        $media = $this->upload->data('file_excel');
        $inputFileName = './assets/'.$media['file_name'];
        $namafile = $media['file_name'];
        try {
                $inputFileType = IOFactory::identify($inputFileName);
                $objReader = IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);

            } catch(Exception $e) {
            	$this->session->set_flashdata('alert_fail', 'Silahkan Tentukan File Terlebih Dahulu');
	    		redirect(base_url()."eform/data_kepala_keluarga/import_add");
                die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
            }
 
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow    = $sheet->getHighestDataRow();
            $highestColumn = $sheet->getHighestDataColumn();
            $temp = array();
            $data = array();
            for ($row = 2; $row <= $highestRow; $row++){ 
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
                // echo $highestColumn;
        	    $kk[$rowData[0][3]][] = $rowData;
            }
            // delete_files($media['file_path']);
            $provinsi 		= $this->input->post('provinsi');
            $kota 			= $this->input->post('kota');
            $id_kecamatan 	= $this->input->post('id_kecamatan');
            $kelurahan 		= $this->input->post('kelurahan');
            $id 			= $this->datakeluarga_model->getNourutkel($kelurahan);
            $jam_data 		= date("H:i:s");
            $tanggal_pengisian = date("d-m-Y");

            $barisexcel=1;
            // $kolomexcel=1;
            $result_all 	= array();
            $result_error 	= array();
            $result_true	= array();
            $arr = array();
            // $err = false;
            // print_r($kk);
            foreach ($kk as $anggota) {
            	// print_r($anggota);
            	foreach ($anggota as $act => $value) {
            		// print_r($value);
            		$result = array();
            		$x=$value[0];
            		$kolomexcel=0;
            		foreach ($x as $kolom => $nilai) {
            			$kolomexcel++;
            			if ($kolom == 0) { //no
            				if(is_numeric($nilai)){
            					$result[]=$nilai;

            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;
            				}
            			}else if ($kolom == 1) { //rw
            				if(is_numeric($nilai)){
            					$result[]=$nilai;

            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;
            				}
            		    }else if ($kolom == 2) {//rt
            				if(is_numeric($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;
            				}
            			}else if ($kolom == 3) {//urt
            				if(is_numeric($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;
            				}
            			}else if ($kolom == 4) {//no rumah
            				if(is_string($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai;
            					$err=true;
            				}
            			}else if ($kolom == 5) {//kode pos
            				if(is_numeric($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;
            				}
            			}else if ($kolom == 6) {//alamat
            				if(is_string($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai;
            					$err=true;
            				}
            			}else if ($kolom == 7) {//nama komunitas
            				if(is_string($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai;
            					$err=true;
            				}
            			}else if ($kolom == 8) {//nama kepala keluarga
            				if(is_string($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai;
            					$err=true;
            				}
            			}else if ($kolom == 9) {//no.tlp
        					$result[]=$nilai;
            			}else if ($kolom == 10) {//nama dasa wisma
            				if(is_string($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai;
            					$err=true;
            				}
            			}else if ($kolom == 11) {//jlm laki-laki
            				if(is_numeric($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;
            				}
            			}else if ($kolom == 12) {//jml wanita
            				if(is_numeric($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;
            				}
            			}else if ($kolom == 13) {//jml peserta kb
            				if(is_numeric($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;
            				}
            			}else if ($kolom == 14) {//jml bukan peserta kb
            				if(is_numeric($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;
            				}
            			}else if ($kolom == 15) {//nama koordinator
            				if(is_string($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai;
            					$err=true;
            				}
            			}else if ($kolom == 16) {//nama pendata
            				if(is_string($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai;
            					$err=true;
            				}
            			}else if ($kolom == 17) {//nik
            				if(is_numeric($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;
            				}
            			}else if ($kolom == 18) {//bpjs
        					$result[]=$nilai;
            			}else if ($kolom == 19) {//nama 
            				if(is_string($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai;
            					$err=true;
            				}
            			}else if ($kolom == 20) {//gender
            				$nilai = strtolower($nilai);
            				if($nilai =="l" || $nilai =="p"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({L},{P}) ';
            					$err=true;
            				}
            			}else if ($kolom == 21) {//Tempat lahir
            				if(is_string($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai;
            					$err=true;
            				}
            			}else if ($kolom == 22) {// Tanggal lahir
            				$tgl = explode("-", $nilai);
            				$jml = count($tgl);

            				if($jml ==3 && strlen($tgl[0])== 4 && strlen($tgl[1])==2 && strlen($tgl[2])==2){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(1993-10-04)';
            					$err=true;
            				}
            			}else if ($kolom == 23) {//hubungan
           					$nilai = strtolower($nilai);
            				if($nilai =="kk"|| $nilai=="istri" ||$nilai=="anak"||$nilai=="lain-lain"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({kk},{istri},{anak},{lain-lain}) ';

            					$err=true;
            				}
            			}else if ($kolom == 24) {//agama
           					$nilai = strtolower($nilai);
            				if($nilai =="islam" || $nilai="kristen" 
            					|| $nilai="katolik" || $nilai=="hindu" || $nilai=="budha"|| $nilai == "konghucu" || $nilai == "lainnya"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({islam},{kristen},{katolik},{hindu},{budha},{konghucu},{lainnya}) ';
            					$err=true;
            				}
            			}else if ($kolom == 25) {//pendidikan
           					$nilai = strtolower($nilai);
            				if($nilai =="tidak tamat sd/mi"|| $nilai =="masih sd/mi"|| $nilai =="tamat sd/mi"
            					|| $nilai =="masih sltp/mtsn"|| $nilai =="tamat sltp/mtsn"|| $nilai =="masih slta/ma"
            					|| $nilai =="tamat slta/ma"|| $nilai =="masih pt/akademi"|| $nilai =="tamat pt/akademi"
            					|| $nilai =="tidak/belum sekolah"|| $nilai =="belum sekolah"|| $nilai =="tidak sekolah"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({tidak tamat sd/mi},{masih sd/mi},{masih sltp/mtsn},{tamat sltp/mtsn},
            						{masih slta/ma},{tamat slta/ma},{masih pt/akademi},{tamat pt/akademi},{tidak/belum sekolah},{belum sekolah},{tidak sekolah}) ';
            					$err=true;
            				}
            			}else if ($kolom == 26) {//pekerjaan
           					$nilai = strtolower($nilai);
            				if($nilai=="petani"||$nilai=="nelayan"||$nilai=="pns/tni/polri"||$nilai=="pegawai swasta"||$nilai=="wiraswasta"
            					||$nilai=="pensiunan"||$nilai=="pekerja lepas"||$nilai=="lainnya"||$nilai=="tidak/belum bekerja"||$nilai=="bekerja"||$nilai=="belum bekerja"||$nilai=="tidak bekerja"
            					||$nilai=="irt"||$nilai=="pedagang"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({Petani},{Nelayan},{Pns/tni/polri},{Pegawai Swasta},{Wiraswasta},{Pensiunan},{Pekerja Lepas},{Lainnya},{Tidak/belum Bekerja}
            						,{Bekerja},{Belum Bekerja},{Tidak Bekerja},{Irt},{Pedagang}) ';
            					$err=true;
            				}
            			}else if ($kolom == 27) {//Status Kawin
           					$nilai = strtolower($nilai);
            				if($nilai=="belum kawin"|| $nilai=="kawin"|| $nilai=="janda/duda"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({Belum Kawin},{Kawin},{Janda/duda}) ';
            					$err=true;
            				}
            			}else if ($kolom == 28) {//jkn
           					$nilai = strtolower($nilai);
            				if($nilai=="bpjs-pbi"|| $nilai=="bpjs-nonpbi"|| $nilai=="non bpjs"|| $nilai=="tidak memiliki"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({Bpjs-pbi},{Bpjs-nonpbi},{Non Bpjs},{Tidak Memiliki}) ';
            					$err=true;
            				}
            			}else if ($kolom == 29) {//suku
            				if(is_string($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai;
            					$err=true;
            				}
            			}else if ($kolom == 30) {//no hp 
            				if(is_string($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai;
            					$err=true;
            				}
            			}else if ($kolom == 31) {//Beras
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 32) {// Non Beras
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 33) {//mie instan
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 34) {//makanan cepat saji
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
						}else if ($kolom == 35) {//donut & sejenisnya
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 36) {//biskuit kering
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
               			}else if ($kolom == 37) {//gorengan
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 38) {//lainnya
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 39) {//sumber air keluarga
            			$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 40) {//sumur terlindungi
            				$nilai = strtolower($nilai);
            				if($nilai == "ya"|| $nilai == "tidak" ){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 41) {//air hujan/sungai
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 42) {//lainnya
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 43) {//jamban keluarga
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 44) {//saluran pembuangan sampah
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 45) {//saluran pembuangan limbah
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 46) {//stiker p4k
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 47) {//up2k
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 48) {//Usaha Kesehatan Lingkungan
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 49) {//Pengahayatan Pengamalan Pancasila
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 50) {//kerja bakti
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 51) {//Rukun Kematian
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 52) {//Keagamaan
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 53) {//Jimpitan
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 54) {//Arisan
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 55) {//Koperasi
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 56) {//Lainnya2
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 57) {//Pendapatan Per Bulan
            				if(is_numeric($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;
            				}
            			}else if ($kolom == 58) {//Pekerjaan2
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 59) {//Sumbangan
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 60) {//lainnya
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;
            				}
            			}else if ($kolom == 61) {//Usia pertama kawin suami
            				if(is_numeric($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;
            				}
            			}else if ($kolom == 62) {//Usia pertama kawin istri
            				if(is_numeric($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;
            				}
            			}else if ($kolom == 63) {//Jumlah Anak Laki-Laki
            				if(is_numeric($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;
            				}
            			}else if ($kolom == 64) {//Jumlah Anak Perempuan
            				if(is_numeric($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;
            				}
            			}else if ($kolom == 65) {//Kepersertaan KB
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai =="sedang" || $nilai =="pernah" || $nilai =="tidak pernah"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({sedang},{pernah},{tidak pernah}) ';
            					$err=true;
            				}
            			}else if ($kolom == 66) {//Metode KB yg sedang/ pernah dilakukan
            				$nilai = strtolower($nilai);
            				if(is_string($nilai) || $nilai =="iud" || $nilai =="mow" || $nilai =="mop" || $nilai =="suntik" 
            					|| $nilai =="batal pilih" || $nilai =="kondom" || $nilai =="implan"|| $nilai =="pil"|| $nilai =="tradisional"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({IUD},{MOW},{MOP},{Suntik},{Batal Pilih},{Kondom},{Implan},{Pil},{Tradisional}) ';
            					$err=true;
            				}
            			}else if ($kolom == 67) {//Tahun
            				if(is_numeric($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;
            				}
            			}else if ($kolom == 68) {//Bulan
            				if(is_numeric($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;
            				}
            			}else if ($kolom == 69) {//Ingin pny anak lagi ?
            				$nilai = strtolower($nilai);
            				if($nilai == "ya, segera"|| $nilai == "ya, kemudian"
            					|| $nilai == "tidak"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya, segera},{ya, kemudian},{tidak}) ';

            					$err=true;
            				}
            			}else if ($kolom == 70) {//Sedang hamil
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 71) {//Alasan fertilitas
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 72) {//Tidak menyetujui KB
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 73) {//Tidak tahu tentang KB
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 74) {//Takut efek samping
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 75) {//Pelayanan KB Jauh
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 76) {//Tidak mampu/mahal
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 77) {//Lainnya3
            				$nilai = strtolower($nilai);
            				if($nilai == "ya" || $nilai == "tidak" || $nilai == ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 78) {//Tempat pelayanan KB
            				$nilai = strtolower($nilai);
            				if(is_string($nilai) || $nilai =="rsup/rsud"|| $nilai =="rs tni"|| $nilai =="rs polri"|| $nilai =="rs swasta"|| $nilai =="klinik utama"
            					|| $nilai =="puskesmas"|| $nilai =="klinik pratama"|| $nilai =="praktek dokter"|| $nilai =="rs pratama"|| $nilai =="pustu/pusling/bidan desa"|| $nilai =="poskesdes/polindes"
            					|| $nilai =="praktek bidan"|| $nilai =="pelayanan bergerak"|| $nilai =="lainnya"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({rsup/rsud},{rs tni},{rs polri},{rs swasta},{klinik utama},
            						{puskesmas},{klinik pratama},{praktek dokter},{rs pratama},{pustu/pusling/bidan desa},{poskesdes/polindes},
            						{praktek bidan},{pelayanan bergerak},{lainnya}) ';
            					$err=true;
            				}
            			}else if ($kolom == 79) {//Klrg beli satu stel pakaian baru u/ selruh anggota klrg 1th/x
            				$nilai = strtolower($nilai);
            				if($nilai == "ya"|| $nilai == "tidak"|| $nilai == "tidak berlaku"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak},{tidak berlaku})';
            					$err=true;
            				}
            			}else if ($kolom == 80) {//Seluruh anggota klrg makan min 2x/hr
            				$nilai = strtolower($nilai);
            				if($nilai == "ya"|| $nilai == "tidak"|| $nilai == "tidak berlaku"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak},{tidak berlaku}) ';
            					$err=true;
            				}
            			}else if ($kolom == 81) {//Slrh anggota klrg jika sakit berobat ke fasyankes
            				$nilai = strtolower($nilai);
            				if($nilai == "ya"|| $nilai == "tidak"|| $nilai == "tidak berlaku"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak},{tidak berlaku}) ';
            					$err=true;
            				}
            			}else if ($kolom == 82) {//Slrh anggota klrg punya baju beda u/ di rumah/kerja/sekolah/pergi
            				$nilai = strtolower($nilai);
            				if($nilai == "ya"|| $nilai == "tidak"|| $nilai == "tidak berlaku"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak},{tidak berlaku}) ';
            					$err=true;
            				}
            			}else if ($kolom == 83) {//Slrh anggota klrg mkn daging/telllur/ikan min 1mg/x
            				$nilai = strtolower($nilai);
            				if($nilai == "ya"|| $nilai == "tidak"|| $nilai == "tidak berlaku"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak},{tidak berlaku}) ';
            					$err=true;
            				}
            			}else if ($kolom == 84) {//Slrh anggota klrg beribadah
            				$nilai = strtolower($nilai);
            				if($nilai == "ya"|| $nilai == "tidak"|| $nilai == "tidak berlaku"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak},{tidak berlaku}) ';
            					$err=true;
            				}
            			}else if ($kolom == 85) {//Pasangan usia subur dgn 2 anak/> menjadi peserta KB
            				$nilai = strtolower($nilai);
            				if($nilai == "ya"|| $nilai == "tidak"|| $nilai == "tidak berlaku"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak},{tidak berlaku}) ';
            					$err=true;
            				}
            			}else if ($kolom == 86) {//Klrg punya tabungan emas/ tanah/hewan min senilai Rp. 1 jt
            				$nilai = strtolower($nilai);
            				if($nilai == "ya"|| $nilai == "tidak"|| $nilai == "tidak berlaku"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak},{tidak berlaku}) ';
            					$err=true;
            				}
            			}else if ($kolom == 87) {//Klrg punya kebiasaan berkomunikasi dgn slrh anggota klrg
            				$nilai = strtolower($nilai);
            				if($nilai == "ya"|| $nilai == "tidak"|| $nilai == "tidak berlaku"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak},{tidak berlaku}) ';
            					$err=true;
            				}
            			}else if ($kolom == 88) {//Klrg ikut kegiatan sosial di link. RT
            				$nilai = strtolower($nilai);
            				if($nilai == "ya"|| $nilai == "tidak"|| $nilai == "tidak berlaku"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak},{tidak berlaku}) ';
            					$err=true;
            				}
            			}else if ($kolom == 89) {//Klrg punya akses informasi dr tv/koran/radio
            				$nilai = strtolower($nilai);
            				if($nilai == "ya"|| $nilai == "tidak"|| $nilai == "tidak berlaku"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak},{tidak berlaku}) ';
            					$err=true;
            				}
            			}else if ($kolom == 90) {//Klrg punya anggota klrg yg jd pengurus keg. Sosial
            				$nilai = strtolower($nilai);
            				if($nilai == "ya"|| $nilai == "tidak"|| $nilai == "tidak berlaku"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak},{tidak berlaku}) ';
            					$err=true;
            				}
            			}else if ($kolom == 91) {//Klrg punya balita yg ikut posyandu
            				$nilai = strtolower($nilai);
            				if($nilai == "ya"|| $nilai == "tidak"|| $nilai == "tidak berlaku"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak},{tidak berlaku}) ';
            					$err=true;
            				}
            			}else if ($kolom == 92) {//Klrg punya balita yg ikut BKB
            				$nilai = strtolower($nilai);
            				if($nilai == "ya"|| $nilai == "tidak"|| $nilai == "tidak berlaku"){
								$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak},{tidak berlaku}) ';
            					$err=true;
            				}
            			}else if ($kolom == 93) {//Klrg punya remaja yg ikut BKR
            				$nilai = strtolower($nilai);
            				if($nilai == "ya"|| $nilai == "tidak"|| $nilai == "tidak berlaku"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak},{tidak berlaku}) ';
            					$err=true;
            				}
            			}else if ($kolom == 94) {//Klrg punya remaja yg ikut PIK
            				$nilai = strtolower($nilai);
            				if($nilai == "ya"|| $nilai == "tidak"|| $nilai == "tidak berlaku"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak},{tidak berlaku}) ';
            					$err=true;
            				}
            			}else if ($kolom == 95) {//Klrg punya lansia yg ikut BKL
            				$nilai = strtolower($nilai);
            				if($nilai == "ya"|| $nilai == "tidak"|| $nilai == "tidak berlaku"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak},{tidak berlaku}) ';
            					$err=true;
            				}
            			}else if ($kolom == 96) {//Klrg mengikuti kegiatan UPPKS
            				$nilai = strtolower($nilai);
            				if($nilai == "ya"|| $nilai == "tidak"|| $nilai == "tidak berlaku"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak},{tidak berlaku}) ';
            					$err=true;
            				}
            			}else if ($kolom == 97) {//Jenis atap terluas : Daun/Rumbia
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 98) {//Jenis atap terluas : Seng/Asbes
							$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 99) {//Jenis atap terluas : Genteng/Sirap
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 100) {//Jenis atap terluas : Lainnya
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 101) {//Jenis dinding terluas : Tembok
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 102) {//Jenis dinding terluas : Kayu/Seng
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 103) {//Jenis dinding terluas : Bambu
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 104) {//Jenis dinding terluas : Lainnya
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 105) {//Jenis lantai rumah terluas : Ubin/Kramik/Marmer
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 106) {//Jenis lantai rumah terluas : Semen/Papan
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 107) {//Jenis lantai rumah terluas : Tanah
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 108) {//Jenis lantai rumah terluas : Lainnya
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 109) {//Sumber penerangan utama : Listrik
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 110) {//Sumber penerangan utama : Genset/Diesel
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 111) {//Sumber penerangan utama : Lampu Minyak
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 112) {//Sumber penerangan utama : Lainnya
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 113) {//Sumber air minum utama : Ledeng/Kemasan
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 114) {//Sumber air minum utama :  Sumur terlindung/Pompa
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 115) {//Sumber air minum utama : Air hujan/sungai
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 116) {//Sumber air minum utama : Lainnya
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 117) {//Bahan bakar utama untuk memasak : Listri/Gas
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 118) {//Bahan bakar utama untuk memasak : Minyak Tanah
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 119) {//Bahan bakar utama untuk memasak : Arang/Kayu
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 120) {//Bahan bakar utama untuk memasak : Lainnya
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)|| $nilai=="ya"||$nilai == "tidak"|| $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 121) {
            				$nilai = strtolower($nilai);
            				if($nilai=="jamban sendiri" || $nilai=="jamban bersama" || $nilai=="jamban umum" || $nilai=="lainnya" ){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({jamban sendiri},{jamban bersama},{jamban umum},{lainnya})';
            					$err=true;

            				}
            			}else if ($kolom == 122) {
            				$nilai = strtolower($nilai);
            				if($nilai=="ya" || $nilai=="tidak" || $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 123) {
            				$nilai = strtolower($nilai);
            				if($nilai=="ya" || $nilai=="tidak" || $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 124) {
            				$nilai = strtolower($nilai);
            				if($nilai=="ya" || $nilai=="tidak" || $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 125) {
            				$nilai = strtolower($nilai);
            				if($nilai=="ya" || $nilai=="tidak" || $nilai==""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 126) {
            				$nilai = strtolower($nilai);
            				if(is_numeric($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;

            				}
            			}else if ($kolom == 127) {
            				if(is_numeric($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;

            				}
            			}else if ($kolom == 128) { //akte lahir
            				$nilai = strtolower($nilai);
            				if($nilai=="ya" || $nilai=="tidak"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 129) {//wna
            				$nilai = strtolower($nilai);
            				if($nilai=="ya" || $nilai=="tidak"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 130) {
            				$nilai = strtolower($nilai);
            				if($nilai=="ya" || $nilai=="tidak"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 131) {
            				$nilai = strtolower($nilai);

            				if($nilai=="ya" || $nilai=="tidak"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 132) {
            				$nilai = strtolower($nilai);
            				if($nilai=="ya" || $nilai=="tidak"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 133) {
            				$nilai = strtolower($nilai);
            				if($nilai=="a" || $nilai=="b" || $nilai=="c" || $nilai=="kf"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({a},{b},{c},{kf})';
            					$err=true;
            				}
            			}else if ($kolom == 134) {
            				$nilai = strtolower($nilai);
            				if($nilai=="ya" || $nilai=="tidak"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 135) {
            				$nilai = strtolower($nilai);
            				if($nilai=="ya" || $nilai=="tidak"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 136) {
            				$nilai = strtolower($nilai);

            				if(is_string($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(string)';
            					$err=true;

            				}
            			}else if ($kolom == 137) {
            				$nilai = strtolower($nilai);
            				if($nilai=="ya" || $nilai=="tidak"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 138) {
            				$nilai = strtolower($nilai);
            				if($nilai=="ya" || $nilai=="tidak"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 139) {
            				$nilai = strtolower($nilai);
            				if($nilai=="ya" || $nilai=="tidak"){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 140) {
            				$nilai = strtolower($nilai);
            				if(is_string($nilai)){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(string)';
            					$err=true;
            				}
            			}else
            			if ($kolom == 141) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 142) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 143) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 144) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 145) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 146) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 147) {
            				if(strtolower($nilai)=="jamban" || strtolower($nilai)=="kolam" || strtolower($nilai)== "sawah" || strtolower($nilai)== "selokan" || strtolower($nilai)== "sungai" || strtolower($nilai)== "danau" || strtolower($nilai)=="laut" || strtolower($nilai)=="lubang tanah" || strtolower($nilai)=="pantai" || strtolower($nilai)== "tanah lapangan" || strtolower($nilai)== "kebun" || strtolower($nilai)== "halaman" || strtolower($nilai)== "kolam/sawah/selokan" || strtolower($nilai)== "sungai/danau/laut" || strtolower($nilai)== "pantai/tanah lapangan/kebun/halaman" || strtolower($nilai)== "tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({jamban},{kolam},{sawah},{selokan},{sungai},{danau},{laut},{lubang tanah},{pantai},{tanah lapangan},{kebun},{halaman})';
            					$err=true;

            				}
            			}else if ($kolom == 148) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 149) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 150) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 151) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 152) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 153) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 154) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak}) ';
            					$err=true;

            				}
            			}else if ($kolom == 155) {
            				if(strtolower($nilai)=="ya, setiap hari" || strtolower($nilai)=="ya, kadang-kadang" || strtolower($nilai)=="tidak, tapi dulu merokok setiap hari" || strtolower($nilai)=="tidak, tapi dulu kadang-kadang"  || strtolower($nilai)=="tidak pernah sama sekali" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya, setiap hari},{ya, kadang-kadang},{tidak, tapi dulu merokok setiap hari},{tidak, tapi dulu kadang-kadang},{tidak pernah sama sekali},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 156) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;

            				}
            			}else if ($kolom == 157) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;

            				}
            			}else if ($kolom == 158) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;

            				}
            			}else if ($kolom == 159) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;

            				}
            			}else if ($kolom == 160) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;

            				}
            			}else if ($kolom == 161) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;

            				}
            			}else if ($kolom == 162) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;

            				}
            			}else if ($kolom == 163) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;

            				}
            			}else if ($kolom == 164) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;

            				}
            			}else if ($kolom == 165) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;

            				}
            			}else if ($kolom == 166) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;

            				}
            			}else if ($kolom == 167) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;

            				}
            			}else if ($kolom == 168) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;

            				}
            			}else if ($kolom == 169) {
            				if(strtolower($nilai)== "ya, dalam ≤ 1 bulan terakhir" || strtolower($nilai)== "ya, > 1 bulan - 12 bulan" || strtolower($nilai)== "tidak" || strtolower($nilai)== "tidak tahu" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya, dalam ≤ 1 bulan terakhir},{ya, > 1 bulan - 12 bulan},{tidak},{tidak tahu})';
            					$err=true;

            				}
            			}else if ($kolom == 170) {
            				if(strtolower($nilai)== "ya, dalam ≤ 1 bulan terakhir" || strtolower($nilai)== "ya, > 1 bulan - 12 bulan" || strtolower($nilai)== "tidak" || strtolower($nilai)== "tidak tahu" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya, > 1 bulan - 12 bulan},{ya, dalam ≤ 1 bulan terakhir},{tidak},{tidak tahu})';
            					$err=true;

            				}
            			}else if ($kolom == 171) {
            				if(strtolower($nilai)== "ya" || strtolower($nilai)== "tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 172) {
            				if(strtolower($nilai)== "ya" || strtolower($nilai)== "tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 173) {
            				if(strtolower($nilai)== "ya" || strtolower($nilai)== "tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 174) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 175) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 176) {
            				if(strtolower($nilai)=="ya, < 2 minggu terakhir" || strtolower($nilai)=="tidak" || strtolower($nilai)=="ya, ≥ 2 minggu" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya, < 2 minggu terakhir},{ya, ≥ 2 minggu},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 177) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak"  || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 178) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak"  || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 179) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak"  || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 180) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak"  || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 181) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak"  || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 182) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak"  || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 183) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak"  || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 184) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak"  || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 185) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak"  || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 186) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak"  || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 187) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak"  || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 188) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak"  || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 189) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak"  || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 190) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 191) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;

            				}
            			}else if ($kolom == 192) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 193) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 194) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 195) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 196) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 197) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 198) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 199) {
            				if($nilai!="" || isset($nilai) || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(tidak)';
            					$err=true;

            				}
            			}else if ($kolom == 200) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 201) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 202) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 203) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 204) {
            				if($nilai!="" || isset($nilai) || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(tidak)';
            					$err=true;

            				}
            			}else if ($kolom == 205) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 206) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 207) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 208) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 209) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 210) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 211) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 212) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 213) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 214) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 215) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 216) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 217) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 218) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 219) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;

            				}
            			}else if ($kolom == 220) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 221) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 222) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 223) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 224) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 225) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 226) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 227) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 228) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 229) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 230) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 231) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;

            				}
            			}else if ($kolom == 232) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 233) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 234) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;

            				}
            			}else if ($kolom == 235) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 236) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 237) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 238) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 239) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 240) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;

            				}
            			}else if ($kolom == 241) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 242) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 243) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 244) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 245) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'{ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 246) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak"  || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 247) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak"  || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 248) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 249) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 250) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 251) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 252) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 253) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 254) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 255) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 256) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 257) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 258) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 259) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 260) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 261) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 262) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 263) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 264) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 265) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 266) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 267) {
            				if(strtolower($nilai)=="ya" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({ya},{tidak})';
            					$err=true;
            				}
            			}else if ($kolom == 268) {
            				if(strtolower($nilai)=="lengkap" || strtolower($nilai)=="tidak tahu" || strtolower($nilai)=="lengkap sesuai umur" || strtolower($nilai)=="tidak lengkap" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'({lengkap},{tidak tahu},{lengkap sesuai umur},{tidak lengkap})';
            					$err=true;
            				}
            			}else if ($kolom == 269 || strtolower($nilai)== "") {
            				if($nilai!="" || isset($nilai)  || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(tidak)';
            					$err=true;
            				}
            			}else if ($kolom == 270 || strtolower($nilai)== "") {
            				if($nilai!="" || isset($nilai) || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(tidak)';
            					$err=true;
            				}
            			}else if ($kolom == 271 || strtolower($nilai)== "") {
            				if($nilai!="" || isset($nilai) || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(tidak)';
            					$err=true;
            				}
            			}else if ($kolom == 272) {
            				if($nilai!="" || isset($nilai) || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(tidak';
            					$err=true;
            				}
            			}else if ($kolom == 273) {
            				if($nilai!="" || isset($nilai) || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(tidak)';
            					$err=true;
            				}
            			}else if ($kolom == 274) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;
            				}
            			}else if ($kolom == 275) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;
            				}
            			}else if ($kolom == 276) {
            				if(is_numeric($nilai) || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(numeric)';
            					$err=true;
            				}
            			}else if ($kolom == 277) {
            				if(strtolower($nilai)=="pucat" || strtolower($nilai)=="normal" || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(pucat/normal/tidak)';
            					$err=true;
            				}
            			}else if ($kolom == 278) {
            				if(is_string($nilai)  || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(string)';
            					$err=true;
            				}
            			}else if ($kolom == 279) {
            				if($nilai!="" || isset($nilai) || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(tidak boleh kosong)';
            					$err=true;
            				}
            			}else if ($kolom == 280) {
            				if($nilai!="" || isset($nilai) || strtolower($nilai)=="tidak" || strtolower($nilai)== ""){
            					$result[]=$nilai;
            				}else{
            					$result[]='***'.$nilai.'(tidak)';
            					$err=true;
            				}
            			}else{
            					$result[]='benar';
	        			}

            		}

        			if(isset($err) && $err==true) $result[]='error';
        			else $result[]='ok';

        			$result_all[]  = $result;
            	}
            }
            // print_r($result_all);
            // die();
            $data_tabelerror = array();
            $data_tabeltrue = array();
            $data_tabelall = array();
			foreach($result_all as $data) {
				$row_data = array(
					'no' 						=> $data[0],
					'rw' 						=> $data[1],
					'rt' 						=> $data[2],
					'urt' 						=> $data[3],
					'norumah' 					=> $data[4],
					'kodepos' 					=> $data[5],
					'alamat' 					=> $data[6],
					'namakomunitas' 			=> $data[7],
					'namakepalakeluarga' 		=> $data[8],
					'notelepon' 				=> $data[9],
					'namadasawisma' 			=> $data[10],
					'jmllaki' 					=> $data[11],
					'jmlperempuan' 				=> $data[12],
					'jmlpuskb' 					=> $data[13],
					'jmlnonpuskb' 				=> $data[14],
					'namakoordinator' 			=> $data[15],
					'namapendata' 				=> $data[16],
					'nik' 						=> $data[17],
					'bpjs' 						=> $data[18],
					'nama' 						=> $data[19],
					'jeniskelamin' 				=> $data[20],
					'tempat' 					=> $data[21],
					'tgllahir' 					=> $data[22],
					'hubungan' 					=> $data[23],
					'agama' 					=> $data[24],
					'pendidikan' 				=> $data[25],
					'pekerjaan' 				=> $data[26],
					'statuskawin' 				=> $data[27],
					'jkn' 						=> $data[28],
					'suku' 						=> $data[29],
					'nohp' 						=> $data[30],
					'mkberas' 					=> $data[31],
					'mknonberas' 				=> $data[32],
					'mkmieinstan' 				=> $data[33],
					'mkcepatsaji' 				=> $data[34],
					'mkdonat' 					=> $data[35],
					'mkbiskuit' 				=> $data[36],
					'mkgorengan' 				=> $data[37],
					'mklain' 					=> $data[38],
					'sapam' 					=> $data[39],
					'sasumur' 					=> $data[40],
					'sahujan' 					=> $data[41],
					'salain' 					=> $data[42],
					'jambankeluarga' 			=> $data[43],
					'spm' 						=> $data[44],
					'smpal' 					=> $data[45],
					'stikerp4k' 				=> $data[46],
					'up2k' 						=> $data[47],
					'ukl' 						=> $data[48],
					'ppp' 						=> $data[49],
					'kerjabakti' 				=> $data[50],
					'rukunkematian' 			=> $data[51],
					'keagamaan' 				=> $data[52],
					'jimpitan' 					=> $data[53],
					'arisan' 					=> $data[54],
					'koperasi' 					=> $data[55],
					'kklain' 					=> $data[56],
					'pendapatan' 				=> $data[57],
					'sppekerjaan' 				=> $data[58],
					'spsumbangan' 				=> $data[59],
					'splain' 					=> $data[60],
					'uksuami' 					=> $data[61],
					'ukistri' 					=> $data[62],
					'jmlanaklaki' 				=> $data[63],
					'jmlanakpr' 				=> $data[64],
					'kepesertaankb' 			=> $data[65],
					'metodekb' 					=> $data[66],
					'lamakbtahun' 				=> $data[67],
					'lamakbbulan' 				=> $data[68],
					'inginpunyaanak' 			=> $data[69],
					'akbhamil' 					=> $data[70],
					'akbfertilitas' 			=> $data[71],
					'akbtidaksetuju' 			=> $data[72],
					'akbtidaktahu' 				=> $data[73],
					'akbefeksamping' 			=> $data[74],
					'akbpelayanan' 				=> $data[75],
					'kbtdkmampu' 				=> $data[76],
					'akblain' 					=> $data[77],
					'tmptpelayanankb' 			=> $data[78],
					'pakaianbaru' 				=> $data[79],
					'makan2kali' 				=> $data[80],
					'berobatsakit' 				=> $data[81],
					'bajubeda' 					=> $data[82],
					'makandaging' 				=> $data[83],
					'beribada' 					=> $data[84],
					'pasangansubur' 			=> $data[85],
					'satujuta' 					=> $data[86],
					'komunikasi' 				=> $data[87],
					'linkrt' 					=> $data[88],
					'aksesinformasi' 			=> $data[89],
					'pengurussos' 				=> $data[90],
					'balitapos' 				=> $data[91],
					'bkb' 						=> $data[92],
					'bkr' 						=> $data[93],
					'pik' 						=> $data[94],
					'bkl' 						=> $data[95],
					'uppks' 					=> $data[96],
					'jatdaun' 					=> $data[97],
					'jatseng' 					=> $data[98],
					'jatgenteng' 				=> $data[99],
					'jatlainnya' 				=> $data[100],
					'jdttembok' 				=> $data[101],
					'jdtkayu' 					=> $data[102],
					'jdtbambu' 					=> $data[103],
					'jdtlain' 					=> $data[104],
					'jlrtkramik' 				=> $data[105],
					'jlrtsemen' 				=> $data[106],
					'jlrttanah' 				=> $data[107],
					'jlrtlain' 					=> $data[108],
					'spulistrik' 				=> $data[109],
					'spugenset' 				=> $data[110],
					'spulampuminyak' 			=> $data[111],
					'spulain' 					=> $data[112],
					'samuledeng' 				=> $data[113],
					'samusumur' 				=> $data[114],
					'samusungai' 				=> $data[115],
					'samulain' 					=> $data[116],
					'bbulistrik' 				=> $data[117],
					'bbuminyak' 				=> $data[118],
					'bbuarang' 					=> $data[119],
					'bbulain' 					=> $data[120],
					'ftbab' 					=> $data[121],
					'skrssendiri' 				=> $data[122],
					'skrssewa' 					=> $data[123],
					'skrsmenumpang' 			=> $data[124],
					'skrslain' 					=> $data[125],
					'luasrumah' 				=> $data[126],
					'jmlorangmenetap' 			=> $data[127],
					'aktelahir' 				=> $data[128],
					'wna' 						=> $data[129],
					'putussekolah' 				=> $data[130],
					'ikutpaud' 					=> $data[131],
					'ikutpaketbelajar' 			=> $data[132],
					'pilihpaket' 				=> $data[133],
					'punyatabungan' 			=> $data[134],
					'ikutkoperasi' 				=> $data[135],
					'jeniskoperasi' 			=> $data[136],
					'usiasubur' 				=> $data[137],
					'hamil' 					=> $data[138],
					'disabilitas' 				=> $data[139],
					'jenisdisabilitas' 			=> $data[140],
					'ctpssebelummakan' 			=> $data[141],
					'ctpssebelumcebok' 			=> $data[142],
					'ctpssebelummenyusui' 		=> $data[143],
					'ctpstangankotor' 			=> $data[144],
					'ctpssetelahbab' 			=> $data[145],
					'ctpssetelahpestisida' 		=> $data[146],
					'lokasibab' 				=> $data[147],
					'sikatgigi' 				=> $data[148],
					'sgmandipagi' 				=> $data[149],
					'sgmandisore' 				=> $data[150],
					'sgmakanpagi' 				=> $data[151],
					'sgmakanmalam' 				=> $data[152],
					'sgmakansiang' 				=> $data[153],
					'sgbangunpagi' 				=> $data[154],
					'merokoksebulan' 			=> $data[155],
					'merokokumur' 				=> $data[156],
					'mmsetiaphari' 				=> $data[157],
					'pkmerokok' 				=> $data[158],
					'jumlahbatang' 				=> $data[159],
					'msairputih' 				=> $data[160],
					'mssusu' 					=> $data[161],
					'mskopi' 					=> $data[162],
					'mstehtawar' 				=> $data[163],
					'mstehmanis' 				=> $data[164],
					'jusbuah' 					=> $data[165],
					'minumbersoda' 				=> $data[166],
					'minumberalkohol' 			=> $data[167],
					'minumlain' 				=> $data[168],
					'pernahdidiagnosa' 			=> $data[169],
					'mengalamidemam' 			=> $data[170],
					'kanapascepat' 				=> $data[171],
					'kanapascuping' 			=> $data[172],
					'katarikan' 				=> $data[173],
					'didiagnosaginjal' 			=> $data[174],
					'didiagnosabatuginjal' 		=> $data[175],
					'akhirbatu' 				=> $data[176],
					'bgdahak' 					=> $data[177],
					'bgdarah' 					=> $data[178],
					'bgdemam' 					=> $data[179],
					'bgnyeridada' 				=> $data[180],
					'bgnyerinapas' 				=> $data[181],
					'bgteriaknapas' 			=> $data[182],
					'bgnapsumenurun' 			=> $data[183],
					'bgbadanmenurun' 			=> $data[184],
					'didxparukurang' 			=> $data[185],
					'didxlebih' 				=> $data[186],
					'didxtbparu' 				=> $data[187],
					'pdxtbdahak' 				=> $data[188],
					'pdxtbrontgen' 				=> $data[189],
					'didxkanker' 				=> $data[190],
					'tahundidxkanker' 			=> $data[191],
					'jkleher' 					=> $data[192],
					'jkpayudara' 				=> $data[193],
					'prostat' 					=> $data[194],
					'jkkolorekal' 				=> $data[195],
					'jkparu' 					=> $data[196],
					'jknasofaring' 				=> $data[197],
					'jkgetahbening' 			=> $data[198],
					'jklain' 					=> $data[199],
					'testiva' 					=> $data[200],
					'pengobatanoperasi' 		=> $data[201],
					'pengobatanlaser' 			=> $data[202],
					'pengobatankemotrapi' 		=> $data[203],
					'pengobatanlain' 			=> $data[204],
					'papsmear' 					=> $data[205],
					'gejalasesak' 				=> $data[206],
					'gsdingin' 					=> $data[207],
					'gsdebu' 					=> $data[208],
					'gsasap' 					=> $data[209],
					'gsstress' 					=> $data[210],
					'gsflu' 					=> $data[211],
					'gskelelahan' 				=> $data[212],
					'gsalergi' 					=> $data[213],
					'gsmakan' 					=> $data[214],
					'gsmengi' 					=> $data[215],
					'gsberkurang' 				=> $data[216],
					'gspengobatan' 				=> $data[217],
					'gspagi' 					=> $data[218],
					'umursesak' 				=> $data[219],
					'sesakkambuh' 				=> $data[220],
					'pernahddx' 				=> $data[221],
					'updmdiet' 					=> $data[222],
					'updmolahraga' 				=> $data[223],
					'updmminum' 				=> $data[224],
					'updminjeksi' 				=> $data[225],
					'gdseringlapar' 			=> $data[226],
					'gdseringhaus' 				=> $data[227],
					'gdseringbak' 				=> $data[228],
					'gdberatbadan' 				=> $data[229],
					'didxht' 					=> $data[230],
					'umurdidxht' 				=> $data[231],
					'obatht' 					=> $data[232],
					'didxjantung' 				=> $data[233],
					'tahundidxjantung' 			=> $data[234],
					'gjsesak' 					=> $data[235],
					'gjnyeritdknyaman' 			=> $data[236],
					'gjnyeritergesa' 			=> $data[237],
					'gjistirahat' 				=> $data[238],
					'didxstroke' 				=> $data[239],
					'didxtahunstroke' 			=> $data[240],
					'kslumpuh' 					=> $data[241],
					'kssemutan' 				=> $data[242],
					'ksmulut' 					=> $data[243],
					'kspelo' 					=> $data[244],
					'kssulit' 					=> $data[245],
					'seringsakitkepala' 		=> $data[246],
					'tdknafsumakan' 			=> $data[247],
					'sulitidur' 				=> $data[248],
					'mudahtakut' 				=> $data[249],
					'merasategang' 				=> $data[250],
					'tangangemetar' 			=> $data[251],
					'pencenaan' 				=> $data[252],
					'sulitberpikir' 			=> $data[253],
					'merasatidakbahagia' 		=> $data[254],
					'menangissering' 			=> $data[255],
					'merasasulit' 				=> $data[256],
					'sulitmengambil' 			=> $data[257],
					'pekerjaanseharihari' 		=> $data[258],
					'tidakmampu' 				=> $data[259],
					'kehilanganminat' 			=> $data[260],
					'merasatdkberharga' 		=> $data[261],
					'mmpunyaipikiran' 			=> $data[262],
					'merasalelah' 				=> $data[263],
					'mengalamirasatdkenak' 		=> $data[264],
					'mudahlelah' 				=> $data[265],
					'keluhantsbfayankes' 		=> $data[266],
					'dalamduaminggu' 			=> $data[267],
					'statusimunisasi' 			=> $data[268],
					'aktivitasolah' 			=> $data[269],
					'aktivitastdr' 				=> $data[270],
					'td' 						=> $data[271],
					'n' 						=> $data[272],
					'r' 						=> $data[273],
					's' 						=> $data[274],
					'tb' 						=> $data[275],
					'bb' 						=> $data[276],
					'conjungtiva' 				=> $data[277],
					'statusgizi' 				=> $data[278],
					'riwayatkesehatan' 			=> $data[279],
					'analisismasalah' 			=> $data[280],
				);
				
				$data_tabelall[] = $row_data;
				if ($data[281]=='error') {
					$data_tabelerror[] = $row_data;
				}
				if ($data[281]=='ok') {
					$data_tabeltrue[] = $row_data;
				}
			}
			$dir = getcwd().'/';
			$template = $dir.'public/files/template/templateImportFilter.xlsx';		
			$code = date('YmdHis');

            $TBS = new clsTinyButStrong;		
			$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
			$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);
			$TBS->MergeBlock('a', $data_tabelerror);
			$output_file_name = 'public/files/hasil/'.$code.'_tabelerror.xlsx';
			$output = $dir.$output_file_name;
			$TBS->Show(OPENTBS_FILE, $output);

            $TBS = new clsTinyButStrong;		
			$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
			$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);
			$TBS->MergeBlock('a', $data_tabeltrue);
			$output_file_name = 'public/files/hasil/'.$code.'_tabelok.xlsx';
			$output = $dir.$output_file_name;
			$TBS->Show(OPENTBS_FILE, $output);

            $TBS = new clsTinyButStrong;		
			$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
			$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);
			$TBS->MergeBlock('a', $data_tabelall);
			$output_file_name = 'public/files/hasil/'.$code.'_tabelall.xlsx';
			$output = $dir.$output_file_name;
			$TBS->Show(OPENTBS_FILE, $output);
			
			$res = array(
				'namafile' => $namafile,
				'code' 	=> $code,
				'all' 	=> count($data_tabelall),
				'ok' 	=> count($data_tabeltrue),
				'error' => count($data_tabelerror)
			);

			die(json_encode($res));
	}	

	function import_add(){
		$this->authentication->verify('eform','add');

		$data['title_group'] 	= "eForm - Ketuk Pintu";
		$data['title_form']		= "Import Excel KPLDH";
		$data['action']			= "import_add";
		$data['id_data_keluarga']="";
      	$data['data_provinsi'] 	= $this->datakeluarga_model->get_provinsi();
      	$data['data_kotakab'] 	= $this->datakeluarga_model->get_kotakab();
      	$data['data_kecamatan'] = $this->datakeluarga_model->get_kecamatan();
      	$data['data_desa'] 		= $this->datakeluarga_model->get_desa();
      	$data['data_pos'] 		= $this->datakeluarga_model->get_pos();
      	$data['data_pkk'] 		= $this->datakeluarga_model->get_pkk();

		$data['content'] = $this->parser->parse("eform/datakeluarga/import_add",$data,true);
		$this->template->show($data,"home");
	}
    
	function add(){
		$this->authentication->verify('eform','add');

        $this->form_validation->set_rules('tgl_pengisian', 'Tanggal Pengisian', 'trim|required');
        $this->form_validation->set_rules('jam_data', 'Jam Pendataan', 'trim|required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
        $this->form_validation->set_rules('dusun', 'Dusun / RW', 'trim|required');
        $this->form_validation->set_rules('rt', 'RT', 'trim|required');
        $this->form_validation->set_rules('norumah', 'No Rumah', 'trim|required');
        $this->form_validation->set_rules('namakomunitas', 'Nama Komunitas', 'trim|required');
        $this->form_validation->set_rules('namakepalakeluarga', 'Nama Kepala Keluarga', 'trim|required');
        $this->form_validation->set_rules('notlp', 'No. HP / Telepon', 'trim|required');
        $this->form_validation->set_rules('namadesawisma', 'Nama Desa Wisma', 'trim|required');
        $this->form_validation->set_rules('jml_anaklaki', 'Jumlah Laki-laki', 'trim|required');
        $this->form_validation->set_rules('jml_anakperempuan', 'Jumlah Perempuan', 'trim|required');
        $this->form_validation->set_rules('pus_ikutkb', 'Jumlah PUS Peserta KB', 'trim|required');
        $this->form_validation->set_rules('pus_tidakikutkb', 'Jumlah PUS Bukan Peserta KB', 'trim|required');
        $this->form_validation->set_rules('jabatanstuktural', '', 'trim');
        $this->form_validation->set_rules('kelurahan', '', 'trim');
        $this->form_validation->set_rules('kodepos', '', 'trim');
        
		if($this->form_validation->run()== FALSE){
			$data['title_group'] = "eForm - Ketuk Pintu";
			$data['title_form']="Tambah Data Keluarga";
			$data['action']="add";
			$data['id_data_keluarga']="";
          	$data['data_provinsi'] = $this->datakeluarga_model->get_provinsi();
          	$data['data_kotakab'] = $this->datakeluarga_model->get_kotakab();
          	$data['data_kecamatan'] = $this->datakeluarga_model->get_kecamatan();
          	$data['data_desa'] = $this->datakeluarga_model->get_desa();
          	$data['data_pos'] = $this->datakeluarga_model->get_pos();
          	$data['data_pkk'] = $this->datakeluarga_model->get_pkk();

			$data['content'] = $this->parser->parse("eform/datakeluarga/form",$data,true);
			$this->template->show($data,"home");
		}elseif($id = $this->datakeluarga_model->insert_entry()){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url().'eform/data_kepala_keluarga/edit/'.$id);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."eform/data_kepala_keluarga/");
		}

	}
    
	function addtable(){
		 $this->datakeluarga_model->insertDataTable();
	}
    
	function edit($id_data_keluarga=0){
		$this->authentication->verify('eform','edit');

        $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
        $this->form_validation->set_rules('kelurahan', 'Kelurahan / Desa', 'trim|required');
        $this->form_validation->set_rules('dusun', 'Dusun / RW', 'trim|required');
        $this->form_validation->set_rules('rt', 'RT', 'trim|required');
        $this->form_validation->set_rules('norumah', 'No Rumah', 'trim|required');
        $this->form_validation->set_rules('namakomunitas', 'Nama Komunitas', 'trim|required');
        $this->form_validation->set_rules('namakepalakeluarga', 'Nama Kepala Keluarga', 'trim|required');
        $this->form_validation->set_rules('notlp', 'No. HP / Telepon', 'trim|required');
        $this->form_validation->set_rules('namadesawisma', 'Nama Desa Wisma', 'trim|required');
        $this->form_validation->set_rules('jabatanstuktural', '', 'trim');
        $this->form_validation->set_rules('kelurahan', '', 'trim');
        $this->form_validation->set_rules('kodepos', '', 'trim');
        $this->form_validation->set_rules('jml_anaklaki', 'Jumlah Laki-laki', 'trim|required');
        $this->form_validation->set_rules('jml_anakperempuan', 'Jumlah Perempuan', 'trim|required');
        $this->form_validation->set_rules('pus_ikutkb', 'Jumlah PUS Peserta KB', 'trim|required');
        $this->form_validation->set_rules('pus_tidakikutkb', 'Jumlah PUS Bukan Peserta KB', 'trim|required');
        $this->form_validation->set_rules('nama_koordinator', '', 'trim');
        $this->form_validation->set_rules('nama_pendata', '', 'trim');
        $this->form_validation->set_rules('jam_selesai', '', 'trim');

		if($this->form_validation->run()== FALSE){
			$data = $this->datakeluarga_model->get_data_row($id_data_keluarga); 

			$data['title_group'] = "eForm - Ketuk Pintu";
			$data['title_form']="Ubah Data Keluarga";
			$data['action']="edit";

			$data['id_data_keluarga'] = $id_data_keluarga;
          	$data['data_provinsi'] = $this->datakeluarga_model->get_provinsi();
          	$data['data_kotakab'] = $this->datakeluarga_model->get_kotakab();
          	$data['data_kecamatan'] = $this->datakeluarga_model->get_kecamatan();
          	$data['data_desa'] = $this->datakeluarga_model->get_desa();
          	$data['data_pos'] = $this->datakeluarga_model->get_pos();
          	$data['data_pkk'] = $this->datakeluarga_model->get_pkk();
            $data['jabatan_pkk'] = $this->datakeluarga_model->get_pkk_value($data['id_pkk']);

			$data['data_profile']  = $this->datakeluarga_model->get_data_profile($id_data_keluarga); 
            //$data['data_print'] = $this->parser->parse("eform/datakeluarga/print", $data, true);

			$data['content'] = $this->parser->parse("eform/datakeluarga/form_detail",$data,true);
			$this->template->show($data,"home");
		}elseif($id_data_keluarga = $this->datakeluarga_model->update_entry($id_data_keluarga)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."eform/data_kepala_keluarga/edit/".$id_data_keluarga);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."eform/data_kepala_keluarga/edit/".$id_data_keluarga);
		}
	}

	function tab($pageIndex,$id_data_keluarga){
		$data = array();
		$data['id_data_keluarga']=$id_data_keluarga;

		switch ($pageIndex) {
			case 1:
				$this->profile($id_data_keluarga);

				break;
			case 2:
				$this->anggota($id_data_keluarga);

				break;
			case 3:
				$this->kb($id_data_keluarga);

				break;
			default:
				$this->pembangunan($id_data_keluarga);
				break;
		}

	}

	function dodel($kode=0){
		$this->authentication->verify('eform','del');

		if($this->datakeluarga_model->delete_entry($kode)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
			redirect(base_url()."eform/data_kepala_keluarga/");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."eform/data_kepala_keluarga/");
		}
	}
	function anggota_dodel($idkeluarga=0,$noanggota=0){
		$this->authentication->verify('eform','del');

		if($this->datakeluarga_model->delete_Anggotakeluarga($idkeluarga,$noanggota)){
			$data['alert_form'] = 'Delete data ('.$idkeluarga.')';
			die($this->parser->parse("eform/datakeluarga/form_anggota_form",$data));
		}else{
			$data['alert_form'] = 'Delete data error';
			die($this->parser->parse("eform/datakeluarga/form_anggota_form",$data));
		}
	}

	function anggota($kode=0)
	{
		$this->authentication->verify('eform','edit');
		$data['dataleveluser'] = $this->datakeluarga_model->get_dataleveluser();
		$data['action']="edit";
		$data['id_data_keluarga'] = $kode;

		die($this->parser->parse("eform/datakeluarga/form_anggota",$data));
	}
	
	function anggota_add($kode=0)
	{
		$this->authentication->verify('eform','edit');

		$this->form_validation->set_rules('nik', 'NIK ', 'trim|required');
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('tmpt_lahir', 'Tempat Lahir', 'trim|required');
        $this->form_validation->set_rules('suku', 'Suku', 'trim|required');
        $this->form_validation->set_rules('no_hp', 'No HP', 'trim|required');
        $this->form_validation->set_rules('bpjs', 'bpjs', 'trim');
        $this->form_validation->set_rules('providerPeserta', 'providerPeserta', 'trim');

        $data['action']="add";
		$data['id_data_keluarga'] = $kode;
		$data['alert_form'] = "";

        $data['data_pilihan_hubungan'] = $this->datakeluarga_model->get_pilihan("hubungan");
      	$data['data_pilihan_kelamin'] = $this->datakeluarga_model->get_pilihan("jk");
      	$data['data_pilihan_agama'] = $this->datakeluarga_model->get_pilihan("agama");
      	$data['data_pilihan_pendidikan'] = $this->datakeluarga_model->get_pilihan("pendidikan");
      	$data['data_pilihan_pekerjaan'] = $this->datakeluarga_model->get_pilihan("pekerjaan");
      	$data['data_pilihan_kawin'] = $this->datakeluarga_model->get_pilihan("kawin");
      	$data['data_pilihan_jkn'] = $this->datakeluarga_model->get_pilihan("jkn");

      	$data['alert_form'] = '';
        if($this->form_validation->run()== FALSE){
        	$data['alert_form'] = '';
			die($this->parser->parse("eform/datakeluarga/form_anggota_add",$data));
		}elseif($noanggota=$this->datakeluarga_model->insert_dataAnggotaKeluarga($kode)){
			$this->anggota_edit($this->input->post('id_data_keluarga'),$noanggota);	
		}else{
			$data['alert_form'] = 'Save data failed...';
			die($this->parser->parse("eform/datakeluarga/form_anggota_add",$data));
		}
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
	function addanggotaprofile()
	{
	 	$actionprofile = $this->datakeluarga_model->addanggotaprofile();
	 	die("$actionprofile");
	} 
	function anggota_edit($idkeluarga=0,$noanggota=0)
	{
		$this->authentication->verify('eform','edit');
		$data = $this->datakeluarga_model->get_data_row_anggota($idkeluarga,$noanggota);
		
        $data['action']="edit";
		$data['id_data_keluarga'] = $idkeluarga;
		$data['noanggota'] = $noanggota;
		$data['alert_form'] = "";

        $data['data_pilihan_hubungan'] = $this->datakeluarga_model->get_pilihan("hubungan");
      	$data['data_pilihan_kelamin'] = $this->datakeluarga_model->get_pilihan("jk");
      	$data['data_pilihan_agama'] = $this->datakeluarga_model->get_pilihan("agama");
      	$data['data_pilihan_pendidikan'] = $this->datakeluarga_model->get_pilihan("pendidikan");
      	$data['data_pilihan_pekerjaan'] = $this->datakeluarga_model->get_pilihan("pekerjaan");
      	$data['data_pilihan_kawin'] = $this->datakeluarga_model->get_pilihan("kawin");
      	$data['data_pilihan_jkn'] = $this->datakeluarga_model->get_pilihan("jkn");

      	//$data['kdPoli'] = $this->bpjs->bpjs_option('poli');

      	$data['alert_form'] = '';

        $data['data_profile_anggota'] = $this->datakeluarga_model->get_data_anggotaprofile($idkeluarga,$noanggota);
		die($this->parser->parse("eform/datakeluarga/form_anggota_form",$data));
	}

	function db_search($by = 'nik',$no){
      	$data = $this->bpjs->db_search($by,$no);
      	echo $data;
	}

	function bpjs_search($by = 'nik',$no){
      	$data = $this->bpjs->bpjs_search($by,$no);
      	echo json_encode($data);
	}

	function update_kepala(){
		$actionkepala = $this->datakeluarga_model->update_kepala();
		die("$actionkepala");
	}
	
	function profile($kode=0)
	{
		$this->authentication->verify('eform','edit');

        $this->form_validation->set_rules('xx', '', 'trim|required');

		if($this->form_validation->run()== FALSE){
			//$data = $this->anggota_keluarga_kb_model->get_data_row($kode); 

			$data['action']="edit";
			$data['id_data_keluarga'] = $kode;
			//$data['data_keluarga_kb']  = $this->anggota_keluarga_kb_model->get_data_profile($kode); 
			$data['alert_form'] = "";
		
		/*}elseif($this->anggota_keluarga_kb_model->update_entry($kode)){
			$data['alert_form'] = 'Save data successful...';
		}else{
			$data['alert_form'] = 'Save data successful...';*/
		}
		$data['data_formprofile']  = $this->dataform_model->get_data_formprofile($kode); 
		die($this->parser->parse("eform/datakeluarga/form_profile",$data));
	}

	function kb($kode=0)
	{
		$this->authentication->verify('eform','edit');

        $this->form_validation->set_rules('xx', '', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data = $this->anggota_keluarga_kb_model->get_data_row($kode); 

			$data['action']="edit";
			$data['id_data_keluarga'] = $kode;
			$data['data_keluarga_kb']  = $this->anggota_keluarga_kb_model->get_data_keluargaberencana($kode); 
			$data['alert_form'] = "";
		
		}elseif($this->anggota_keluarga_kb_model->update_entry($kode)){
			$data['alert_form'] = 'Save data successful...';
		}else{
			$data['alert_form'] = 'Save data successful...';
		}
		die($this->parser->parse("eform/datakeluarga/form_kb",$data));
	}

	function addkeluargaberencana()
	{
		$actionberencana= $this->anggota_keluarga_kb_model->insertDataKeluargaBerencana();
		die("$actionberencana");
	}

	function pembangunan($kode=0)
	{
		$this->authentication->verify('eform','edit');

        $this->form_validation->set_rules('xx', '', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data = $this->pembangunan_keluarga_model->get_data_row($kode); 

			$data['action']="edit";
			$data['id_data_keluarga'] = $kode;
			$data['data_pembangunan']  = $this->pembangunan_keluarga_model->get_data_pembangunan ($kode); 
			$data['alert_form'] = "";

		}elseif($this->pembangunan_keluarga_model->update_entry($kode)){
			$data['alert_form'] = 'Save data successful...';
		}else{
			$data['alert_form'] = 'Save data successful...';
		}

		die($this->parser->parse("eform/datakeluarga/form_pembangunan",$data));
	}

	function addpembangunan(){
		$actionpembangunan = $this->pembangunan_keluarga_model->insertdatatable_pembangunan();
		die("$actionpembangunan");
	}

	function get_kecamatanfilter(){
		if ($this->input->post('kecamatan')!="null") {
			if($this->input->is_ajax_request()) {
				$kecamatan = $this->input->post('kecamatan');
				$this->session->set_userdata('filter_code_kecamatan',$this->input->post('kecamatan'));
				$kode 	= $this->datakeluarga_model->get_datawhere($kecamatan,"code","cl_village");

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
					$kode 	= $this->datakeluarga_model->get_datawhere($kelurahan,"id_desa","data_keluarga");

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

	function get_rukunwargafilter(){
		if ($this->input->post('rukunwarga')!="null" || $this->input->post('kelurahan')!="null") {	
			if($this->input->is_ajax_request()) {
				if($this->input->post('rukunwarga') != '') {
					$this->session->set_userdata('filter_code_rukunwarga',$this->input->post('rukunwarga'));
				}else{
					$this->session->set_userdata('filter_code_rukunwarga','');
				}
				$this->session->set_userdata('filter_code_cl_rukunrumahtangga','');
				$rukunwarga = $this->input->post('rukunwarga');
				$kelurahan = $this->input->post('kelurahan');

				$this->db->where("rw",$rukunwarga);
				$this->db->group_by("rt");
				$kode 	= $this->datakeluarga_model->get_datawhere($kelurahan,"id_desa","data_keluarga");

				echo '<option value="">Pilih RT</option>';
				foreach($kode as $kode) :
					echo $select = $kode->rt == set_value('rukunrumahtangga') ? 'selected' : '';
					echo '<option value="'.$kode->rt.'" '.$select.'>' . $kode->rt . '</option>';
				endforeach;

				return FALSE;
			}
			

			show_404();
		}
	}

	function get_rukunrumahtanggafilter(){
		if ($this->input->post('rukunrumahtangga')!="null") {
			if($_POST) {
				if($this->input->post('rukunrumahtangga') != '') {
					$this->session->set_userdata('filter_code_cl_rukunrumahtangga',$this->input->post('rukunrumahtangga'));
				}else{
					$this->session->set_userdata('filter_code_cl_rukunrumahtangga','');
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

	function autocomplite_namakoordinator(){
		$search = explode("&",$this->input->server('QUERY_STRING'));
		$search = str_replace("query=","",$search[0]);
		$search = str_replace("+"," ",$search);

		$this->db->where("nama_koordinator like '%".$search."%'");
		$this->db->limit(10,0);
		$this->db->group_by('nama_koordinator');
		$query= $this->db->get("data_keluarga")->result();
		foreach ($query as $q) {
			$nama[] = array('nama_koordinator' 	=> $q->nama_koordinator);
		}
		echo json_encode($nama);
	}

	function autocomplite_namapendata(){
		$search = explode("&",$this->input->server('QUERY_STRING'));
		$search = str_replace("query=","",$search[0]);
		$search = str_replace("+"," ",$search);

		$this->db->where("nama_pendata like '%".$search."%'");
		$this->db->limit(10,0);
		$this->db->group_by('nama_pendata');
		$query= $this->db->get("data_keluarga")->result();
		foreach ($query as $q) {
			$namapendata[] = array('nama_pendata'	=> $q->nama_pendata);
		}
		echo json_encode($namapendata);
	}
}