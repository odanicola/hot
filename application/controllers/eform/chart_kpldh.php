<?php
class Chart_kpldh extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('eform/datakeluarga_model');
		$this->load->model('eform/pembangunan_keluarga_model');
		$this->load->model('eform/anggota_keluarga_kb_model');
		$this->load->model('eform/chart_kpldh_model');
	}

	function json_laporan(){
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
		$this->laporan_pilih();
		$rows_all = $this->chart_kpldh_model->get_laporan();

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
		$this->laporan_pilih();
		$rows = $this->chart_kpldh_model->get_laporan($this->input->post('recordstartindex'), $this->input->post('pagesize'));
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
	function json_laporan_kepala(){
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
		$this->laporan_pilih();
		$rows_all = $this->chart_kpldh_model->get_laporan();

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
		$this->laporan_pilih();
		$rows = $this->chart_kpldh_model->get_laporan($this->input->post('recordstartindex'), $this->input->post('pagesize'));
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
	function laporan_pilih($judul=0){
		$judul = $this->input->post("judul");
		$kecamatan = $this->input->post("kecamatan");
		$kelurahan = $this->input->post("kelurahan");
		$rw = $this->input->post("rw");
		$rt = $this->input->post("rt");
		$id_judul = $this->input->post("id_judul");
		$variabel = $this->input->post("variabel");
		$mst_pendidikan = $this->chart_kpldh_model->get_mst_pendidikan();
		$mst_pekerjaan = $this->chart_kpldh_model->get_mst_pekerjaan();
		$mst_jamkesehatan = $this->chart_kpldh_model->get_mst_jamkesehatan();
		$mst_kawin = $this->chart_kpldh_model->get_mst_kawin();
		$village = $this->chart_kpldh_model->get_village();
		
		if($kecamatan!=""){
    		$this->db->where("data_keluarga.id_kecamatan",$kecamatan);
		}
		if($id_judul!="42" && $id_judul!="43" && $id_judul!="45" && $id_judul!="49"){
			if ($kelurahan!="") {
				$this->db->where("data_keluarga.id_desa",$kelurahan);	
			}
			if ($rw!="") {
				$this->db->where("data_keluarga.rw",$rw);	
			}
			if ($rt!="") {
				$this->db->where("data_keluarga.rt",$rt);	
			}
		}

		if($id_judul=="1"){
			if($variabel=="Laki-laki"){
        		$this->db->where("id_pilihan_kelamin",5);
			}else{
        		$this->db->where("id_pilihan_kelamin",6);
			}
		}else if($id_judul=="2"){
			if ($variabel=="Infant  (0-12 bulan)") {
				$this->db->where("TIMESTAMPDIFF(MONTH,tgl_lahir,CURDATE()) <=12");
			}elseif ($variabel=="Toddler  (1-3 tahun)") {
				$this->db->where("(YEAR(CURDATE())-YEAR(tgl_lahir)) >=".'"'.'1'.'"'."");
        		$this->db->where("(YEAR(CURDATE())-YEAR(tgl_lahir)) <=".'"'.'3'.'"'."");
			}elseif ($variabel=="Preschool (4 tahun-5 tahun)") {
				$this->db->where("(YEAR(CURDATE())-YEAR(tgl_lahir)) >=".'"'.'4'.'"'."");
        		$this->db->where("(YEAR(CURDATE())-YEAR(tgl_lahir)) <=".'"'.'5'.'"'."");
			}elseif ($variabel=="Usia sekolah (6 tahun- 12 tahun)") {
				$this->db->where("(YEAR(CURDATE())-YEAR(tgl_lahir)) >=".'"'.'6'.'"'."");
        		$this->db->where("(YEAR(CURDATE())-YEAR(tgl_lahir)) <=".'"'.'12'.'"'."");
			}elseif ($variabel=="Remaja ( 13 tahun-20 tahun)") {
				$this->db->where("(YEAR(CURDATE())-YEAR(tgl_lahir)) >=".'"'.'13'.'"'."");
        		$this->db->where("(YEAR(CURDATE())-YEAR(tgl_lahir)) <=".'"'.'20'.'"'."");
			}elseif ($variabel=="Dewasa (21 tahun-44 tahun)") {
				$this->db->where("(YEAR(CURDATE())-YEAR(tgl_lahir)) >=".'"'.'21'.'"'."");
        		$this->db->where("(YEAR(CURDATE())-YEAR(tgl_lahir)) <=".'"'.'44'.'"'."");
			}elseif ($variabel=="Prelansia (45 tahun-59 tahun)") {
				$this->db->where("(YEAR(CURDATE())-YEAR(tgl_lahir)) >=".'"'.'45'.'"'."");
        		$this->db->where("(YEAR(CURDATE())-YEAR(tgl_lahir)) <=".'"'.'59'.'"'."");
			}elseif ($variabel=="Lansia (>60 tahun)") {
				$this->db->where("(YEAR(CURDATE())-YEAR(tgl_lahir)) >=".'"'.'60'.'"'."");
			}
		}else if($id_judul=="3"){
			foreach ($mst_pendidikan->result_array() as $mst_pilihan) {
				if ($variabel==$mst_pilihan['value']) {
					$this->db->where("id_pilihan_pendidikan",$mst_pilihan['id_pilihan']);
				}
			}
		}else if($id_judul=="4"){
			foreach ($mst_pekerjaan->result_array() as $mst_pilihan) {
				if ($variabel==$mst_pilihan['value']) {
					$this->db->where("id_pilihan_pekerjaan",$mst_pilihan['id_pilihan']);
				}
			}
		}else if($id_judul=="5"){
			$this->db->join('data_keluarga_pembangunan','data_keluarga_pembangunan.id_data_keluarga = data_keluarga.id_data_keluarga','left');
			$this->db->where("data_keluarga_pembangunan.kode","pembangunan_III_13_radio");
			if ($variabel=="Ya") {
				$this->db->where("data_keluarga_pembangunan.value",'0');
			}elseif ($variabel=="Tidak") {
				$this->db->where("data_keluarga_pembangunan.value",'1');
			}else{
				$this->db->where("data_keluarga_pembangunan.value",'2');
			}
		}else if($id_judul=="6"){
			$this->db->where("kode","kesehatan_0_g_10_radio");
			if ($variabel=="Ya") {
				$this->db->where("data_keluarga_anggota_profile.value",'0');
			}else{
				$this->db->where("data_keluarga_anggota_profile.value",'1');
			}
		}else if($id_judul=="7"){
			foreach ($mst_jamkesehatan->result_array() as $mst_pilihan) {
				if ($variabel==$mst_pilihan['value']) {
					$this->db->where("id_pilihan_jkn",$mst_pilihan['id_pilihan']);
				}
			}
		}else if($id_judul=="8"){
			$this->db->join('data_keluarga_kb','data_keluarga_kb.id_data_keluarga = data_keluarga.id_data_keluarga','left');
			$this->db->where("kode","berencana_II_3_kb_radio");
        	if ($variabel=='Sedang') {
        		$this->db->where("data_keluarga_kb.value",'0');	
        	}elseif ($variabel=='Pernah') {
        		$this->db->where("data_keluarga_kb.value",'1');	
        	}else{
        		$this->db->where("data_keluarga_kb.value",'2');	
        	}
		}else if($id_judul=="9"){
			$this->db->join('data_keluarga_kb','data_keluarga_kb.id_data_keluarga = data_keluarga.id_data_keluarga','left');
			if ($variabel=="Sedang Hamil") {
				$this->db->where("kode","berencana_II_7_berkb_hamil_cebox");
			}elseif ($variabel=="Tidak Setuju") {
				$this->db->where("kode","berencana_II_7_berkb_tidaksetuju_cebox");
			}elseif ($variabel=="Tidak Tahu") {
				$this->db->where("kode","berencana_II_7_berkb_tidaktahu_cebox");
			}elseif ($variabel=="Takut Efek KB") {
				$this->db->where("kode","berencana_II_7_berkb_efeksamping_cebox");
			}elseif ($variabel=="Pelayanan KB Jauh") {
				$this->db->where("kode","berencana_II_7_berkb_pelayanan_cebox");
			}elseif ($variabel=="Tidak Mampu / Mahal") {
				$this->db->where("kode","berencana_II_7_berkb_tidakmampu_cebox");
			}elseif ($variabel=="Fertilasi") {
				$this->db->where("kode","berencana_II_7_berkb_fertilasi_cebox");
			}else{
				$this->db->where("kode","berencana_II_7_berkb_lainya_cebox");
			}
		}else if($id_judul=="10"){
			$this->db->join('data_keluarga_pembangunan','data_keluarga_pembangunan.id_data_keluarga = data_keluarga.id_data_keluarga','left');
			if ($variabel=="Milik Sendiri") {
				$this->db->where("kode","pembangunan_III_26_1_cebo4");
			}elseif ($variabel=="Sewa / Kontrak") {
				$this->db->where("kode","pembangunan_III_26_2_cebo4");
			}elseif ($variabel=="Menumpang") {
				$this->db->where("kode","pembangunan_III_26_3_cebo4");
			}else{
				$this->db->where("kode","pembangunan_III_26_4_cebo4");
			}
		}else if($id_judul=="11"){
			$this->db->join('data_keluarga_pembangunan','data_keluarga_pembangunan.id_data_keluarga = data_keluarga.id_data_keluarga','left');
			if ($variabel=="Daun / Rumbia") {
				$this->db->where("kode","pembangunan_III_1_19_cebo4");
			}elseif ($variabel=="Seng / Asbes") {
				$this->db->where("kode","pembangunan_III_2_19_cebo4");
			}elseif ($variabel=="Genteng / Sirap") {
				$this->db->where("kode","pembangunan_III_3_19_cebo4");
			}else{
				$this->db->where("kode","pembangunan_III_4_19_cebo4");
			}
		}else if($id_judul=="12"){
			$this->db->join('data_keluarga_pembangunan','data_keluarga_pembangunan.id_data_keluarga = data_keluarga.id_data_keluarga','left');
			if ($variabel=="Tembok") {
				$this->db->where("kode","pembangunan_III_1_20_cebo4");
			}elseif ($variabel=="Kayu / Seng") {
				$this->db->where("kode","pembangunan_III_2_20_cebo4");
			}elseif ($variabel=="Bambu") {
				$this->db->where("kode","pembangunan_III_3_20_cebo4");
			}else{
				$this->db->where("kode","pembangunan_III_4_20_cebo4");
			}
		}else if($id_judul=="13"){
			$this->db->join('data_keluarga_pembangunan','data_keluarga_pembangunan.id_data_keluarga = data_keluarga.id_data_keluarga','left');
			if ($variabel=="Ubin / Keramik / Marmer") {
				$this->db->where("kode","pembangunan_III_1_21_cebo4");
			}elseif ($variabel=="Semen / Papan") {
				$this->db->where("kode","pembangunan_III_2_21_cebo4");
			}elseif ($variabel=="Tanah") {
				$this->db->where("kode","pembangunan_III_3_21_cebo4");
			}else{
				$this->db->where("kode","pembangunan_III_4_21_cebo4");
			}
		}else if($id_judul=="14"){
			$this->db->join('data_keluarga_pembangunan','data_keluarga_pembangunan.id_data_keluarga = data_keluarga.id_data_keluarga','left');
			if ($variabel=="Listrik") {
				$this->db->where("kode","pembangunan_III_22_1_cebo4");
			}elseif ($variabel=="Genset / Diesel") {
				$this->db->where("kode","pembangunan_III_22_2_cebo4");
			}elseif ($variabel=="Lampu Minyak") {
				$this->db->where("kode","pembangunan_III_22_3_cebo4");
			}else{
				$this->db->where("kode","pembangunan_III_22_4_cebo4");
			}
		}else if($id_judul=="15"){
			$this->db->join('data_keluarga_pembangunan','data_keluarga_pembangunan.id_data_keluarga = data_keluarga.id_data_keluarga','left');
			if ($variabel=="Ledeng / Kemasan") {
				$this->db->where("kode","pembangunan_III_23_1_cebo4");
			}elseif ($variabel=="Sumur Terlindung / Pompa") {
				$this->db->where("kode","pembangunan_III_23_2_cebo4");
			}elseif ($variabel=="Air Hujan / Sungai") {
				$this->db->where("kode","pembangunan_III_23_3_cebo4");
			}else{
				$this->db->where("kode","pembangunan_III_23_4_cebo4");
			}
		}else if($id_judul=="16"){
			$this->db->join('data_keluarga_pembangunan','data_keluarga_pembangunan.id_data_keluarga = data_keluarga.id_data_keluarga','left');
			if ($variabel=="Listrik / Gas") {
				$this->db->where("kode","pembangunan_III_24_1_cebo4");
			}elseif ($variabel=="Minyak Tanah") {
				$this->db->where("kode","pembangunan_III_24_2_cebo4");
			}elseif ($variabel=="Arang / Kayu") {
				$this->db->where("kode","pembangunan_III_24_3_cebo4");
			}else{
				$this->db->where("kode","pembangunan_III_24_4_cebo4");
			}
		}else if($id_judul=="17"){
			$this->db->join('data_keluarga_pembangunan','data_keluarga_pembangunan.id_data_keluarga = data_keluarga.id_data_keluarga','left');
			$this->db->where("kode","pembangunan_III_25_radi4");
			if ($variabel=="Jamban Sendiri") {
				$this->db->where("data_keluarga_pembangunan.value",'0');
			}elseif ($variabel=="Jamban Bersama") {
				$this->db->where("data_keluarga_pembangunan.value",'1');
			}elseif ($variabel=="Jamban Umum") {
				$this->db->where("data_keluarga_pembangunan.value",'2');
			}else{
				$this->db->where("data_keluarga_pembangunan.value",'3');
			}
		}else if($id_judul=="18"){
			if ($variabel=="Sebelum menyiapkan makanan") {
				$this->db->where("kode",'kesehatan_1_g_1_a_cebox');
			}elseif ($variabel=="Setiap kali tangan kotor (pegang uang, binatang, berkebun)") {
				$this->db->where("kode",'kesehatan_1_g_1_b_cebox');
			}elseif ($variabel=="Setelah buang air besar") {
				$this->db->where("kode",'kesehatan_1_g_1_c_cebox');
			}elseif ($variabel=="Setelah mencebok bayi") {
				$this->db->where("kode",'kesehatan_1_g_1_d_cebox');
			}elseif ($variabel=="Setelah menggunakan pestisida/insektisida") {
				$this->db->where("kode",'kesehatan_1_g_1_e_cebox');
			}else{
				$this->db->where("kode",'kesehatan_1_g_1_f_cebox');
			}
		}else if($id_judul=="19"){
			$this->db->where("kode",'kesehatan_1_g_2_radi5');
			if ($variabel=="Jamban") {
				$this->db->where("data_keluarga_anggota_profile.value",'0');
			}elseif ($variabel=="Kolam/ Sawah/ Selokan") {
				$this->db->where("data_keluarga_anggota_profile.value",'1');
			}elseif ($variabel=="Sungai/ Danau/ Laut") {
				$this->db->where("data_keluarga_anggota_profile.value",'2');
			}elseif ($variabel=="Lubang tanah") {
				$this->db->where("data_keluarga_anggota_profile.value",'3');
			}else{
				$this->db->where("data_keluarga_anggota_profile.value",'4');
			}
		}else if($id_judul=="20"){
			if ($variabel=="Saat mandi pagi") {
				$this->db->where("kode",'kesehatan_1_g_4_a_cebox');
			}elseif ($variabel=="Saat mandi sore") {
				$this->db->where("kode",'kesehatan_1_g_4_b_cebox');
			}elseif ($variabel=="Sesudah makan pagi") {
				$this->db->where("kode",'kesehatan_1_g_4_c_cebox');
			}elseif ($variabel=="Sesudah bangun pagi") {
				$this->db->where("kode",'kesehatan_1_g_4_d_cebox');
			}elseif ($variabel=="Sebelum tidur malam") {
				$this->db->where("kode",'kesehatan_1_g_4_e_cebox');
			}else{
				$this->db->where("kode",'kesehatan_1_g_4_f_cebox');
			}
		}else if($id_judul=="21"){
			$this->db->where("kode",'kesehatan_1_g_1_radi5');
			if ($variabel=="Ya, setiap hari") {
				$this->db->where("data_keluarga_anggota_profile.value",'0');
			}elseif ($variabel=="Ya, kadang-kadang") {
				$this->db->where("data_keluarga_anggota_profile.value",'1');
			}elseif ($variabel=="Tidak, tapi dulu merokok setiap hari") {
				$this->db->where("data_keluarga_anggota_profile.value",'2');
			}elseif ($variabel=="Tidak, tapi dulu kadang-kadang") {
				$this->db->where("data_keluarga_anggota_profile.value",'3');
			}else{
				$this->db->where("data_keluarga_anggota_profile.value",'4');
			}
		}else if($id_judul=="22"){
			$this->db->where("data_keluarga_anggota_profile.kode","kesehatan_1_g_3_text");
        	if ($variabel=="Remaja (13-20)") {
        		$this->db->where("data_keluarga_anggota_profile.value >=",'13');
        		$this->db->where("data_keluarga_anggota_profile.value <=",'20');
        	}else{
        		$this->db->where("data_keluarga_anggota_profile.value >=",'21');
        		$this->db->where("data_keluarga_anggota_profile.value <=",'40');
        	}
		}else if($id_judul=="23"){
			$this->db->where("data_keluarga_anggota_profile.kode","kesehatan_2_g_2_radio");
        	if ($variabel=="Ya") {
        		$this->db->where("data_keluarga_anggota_profile.value",'0');
        	}else{
        		$this->db->where("data_keluarga_anggota_profile.value",'1');
        	}
		}else if($id_judul=="24"){
			$this->db->where("data_keluarga_anggota_profile.kode","kesehatan_2_g_1_tb_radi3");
        	if ($variabel=="Ya, < 2 minggu terakhir") {
        		$this->db->where("data_keluarga_anggota_profile.value",'0');
        	}elseif ($variabel=="Ya, ≥ 2 minggu") {
        		$this->db->where("data_keluarga_anggota_profile.value",'1');
        	}else{
        		$this->db->where("data_keluarga_anggota_profile.value",'2');
        	}
		}else if($id_judul=="25"){
			$this->db->where("data_keluarga_anggota_profile.kode","kesehatan_4_g_1_radio");
        	if ($variabel=="Ya") {
        		$this->db->where("data_keluarga_anggota_profile.value",'0');
        	}else{
        		$this->db->where("data_keluarga_anggota_profile.value",'1');
        	}
		}else if($id_judul=="26"){
			$this->db->where("data_keluarga_anggota_profile.kode","kesehatan_4_g_1_hp_radio");
        	if ($variabel=="Ya") {
        		$this->db->where("data_keluarga_anggota_profile.value",'0');
        	}else{
        		$this->db->where("data_keluarga_anggota_profile.value",'1');
        	}
		}else if($id_judul=="27"){
			$this->db->where("data_keluarga_anggota_profile.kode","kesehatan_4_g_1_jk_radio");
        	if ($variabel=="Ya") {
        		$this->db->where("data_keluarga_anggota_profile.value",'0');
        	}else{
        		$this->db->where("data_keluarga_anggota_profile.value",'1');
        	}
		}else if($id_judul=="28"){
			$this->db->where("data_keluarga_anggota_profile.kode","kesehatan_4_g_1_sk_radio");
        	if ($variabel=="Ya") {
        		$this->db->where("data_keluarga_anggota_profile.value",'0');
        	}else{
        		$this->db->where("data_keluarga_anggota_profile.value",'1');
        	}
		}else if($id_judul=="29"){
			$this->db->where("data_keluarga_anggota_profile.kode","kesehatan_3_g_1_kk_radio");
        	if ($variabel=="Ya") {
        		$this->db->where("data_keluarga_anggota_profile.value",'0');
        	}else{
        		$this->db->where("data_keluarga_anggota_profile.value",'1');
        	}
		}else if($id_judul=="30"){
			$this->db->where("data_keluarga_anggota_profile.kode","kesehatan_3_g_1_radio");
        	if ($variabel=="Ya") {
        		$this->db->where("data_keluarga_anggota_profile.value",'0');
        	}else{
        		$this->db->where("data_keluarga_anggota_profile.value",'1');
        	}
		}else if($id_judul=="31"){
			//sulittidur
			if ($variabel=="Ya") {
				$this->db->where("data_keluarga_anggota_profile.kode","kesehatan_5_g_3_kk_cebox");
        	}else{
        		$this->db->where('(`data_keluarga`.`id_kecamatan` =  "'.$kecamatan.'"
				AND CONCAT( data_keluarga_anggota.id_data_keluarga, data_keluarga_anggota.no_anggota ) NOT IN (
						SELECT CONCAT(data_keluarga_anggota_profile.id_data_keluarga, data_keluarga_anggota_profile.no_anggota ) 
						FROM (`data_keluarga`) JOIN `data_keluarga_anggota_profile` ON `data_keluarga_anggota_profile` . `id_data_keluarga` = `data_keluarga` . `id_data_keluarga`
						WHERE `data_keluarga` . `id_kecamatan` = "'.$kecamatan.'" AND `data_keluarga_anggota_profile` . `kode` = "kesehatan_5_g_3_kk_cebox")) 
        			');
			}
		}else if($id_judul=="32"){
			//mudahtakut
			if ($variabel=="Ya") {
        		$this->db->where("kode",'kesehatan_5_g_4_kk_cebox');
        	}else{
        		$this->db->where('(`data_keluarga`.`id_kecamatan` =  "'.$kecamatan.'"
				AND CONCAT( data_keluarga_anggota.id_data_keluarga, data_keluarga_anggota.no_anggota ) NOT IN (
						SELECT CONCAT(data_keluarga_anggota_profile.id_data_keluarga, data_keluarga_anggota_profile.no_anggota ) 
						FROM (`data_keluarga`) JOIN `data_keluarga_anggota_profile` ON `data_keluarga_anggota_profile` . `id_data_keluarga` = `data_keluarga` . `id_data_keluarga`
						WHERE `data_keluarga` . `id_kecamatan` = "'.$kecamatan.'" AND `data_keluarga_anggota_profile` . `kode` = "kesehatan_5_g_4_kk_cebox")) 
        			');
        	}
		}else if($id_judul=="33"){
			//berfikirjernih
			if ($variabel=="Ya") {
        		$this->db->where("kode",'kesehatan_5_g_8_kk_cebox');
        	}else{
        		$this->db->where('(`data_keluarga`.`id_kecamatan` =  "'.$kecamatan.'"
				AND CONCAT( data_keluarga_anggota.id_data_keluarga, data_keluarga_anggota.no_anggota ) NOT IN (
						SELECT CONCAT(data_keluarga_anggota_profile.id_data_keluarga, data_keluarga_anggota_profile.no_anggota ) 
						FROM (`data_keluarga`) JOIN `data_keluarga_anggota_profile` ON `data_keluarga_anggota_profile` . `id_data_keluarga` = `data_keluarga` . `id_data_keluarga`WHERE `data_keluarga` . `id_kecamatan` = "'.$kecamatan.'" AND `data_keluarga_anggota_profile` . `kode` = "kesehatan_5_g_8_kk_cebox")) 
        			');
        	}
		}else if($id_judul=="34"){
			//tidakbahagia
			if ($variabel=="Ya") {
        		$this->db->where("kode",'kesehatan_5_g_9_kk_cebox');
        	}else{
        		$this->db->where('(`data_keluarga`.`id_kecamatan` =  "'.$kecamatan.'"
				AND CONCAT( data_keluarga_anggota.id_data_keluarga, data_keluarga_anggota.no_anggota ) NOT IN (
						SELECT CONCAT(data_keluarga_anggota_profile.id_data_keluarga, data_keluarga_anggota_profile.no_anggota ) 
						FROM (`data_keluarga`) JOIN `data_keluarga_anggota_profile` ON `data_keluarga_anggota_profile` . `id_data_keluarga` = `data_keluarga` . `id_data_keluarga`WHERE `data_keluarga` . `id_kecamatan` = "'.$kecamatan.'" AND `data_keluarga_anggota_profile` . `kode` = "kesehatan_5_g_9_kk_cebox")) 
        			');
        	}
		}else if($id_judul=="35"){
			//menangis
			if ($variabel=="Ya") {
        		$this->db->where("kode",'kesehatan_5_g_10_kk_cebox');
        	}else{
        		$this->db->where('(`data_keluarga`.`id_kecamatan` =  "'.$kecamatan.'"
				AND CONCAT( data_keluarga_anggota.id_data_keluarga, data_keluarga_anggota.no_anggota ) NOT IN (
						SELECT CONCAT(data_keluarga_anggota_profile.id_data_keluarga, data_keluarga_anggota_profile.no_anggota ) 
						FROM (`data_keluarga`) JOIN `data_keluarga_anggota_profile` ON `data_keluarga_anggota_profile` . `id_data_keluarga` = `data_keluarga` . `id_data_keluarga`WHERE `data_keluarga` . `id_kecamatan` = "'.$kecamatan.'" AND `data_keluarga_anggota_profile` . `kode` = "kesehatan_5_g_10_kk_cebox")) 
        			');
        	}
		}else if($id_judul=="36"){
			//mengakhirihidup
			if ($variabel=="Ya") {
        		$this->db->where("kode",'kesehatan_5_g_18_kk_cebox');
        	}else{
        		$this->db->where('(`data_keluarga`.`id_kecamatan` =  "'.$kecamatan.'"
				AND CONCAT( data_keluarga_anggota.id_data_keluarga, data_keluarga_anggota.no_anggota ) NOT IN (
						SELECT CONCAT(data_keluarga_anggota_profile.id_data_keluarga, data_keluarga_anggota_profile.no_anggota ) 
						FROM (`data_keluarga`) JOIN `data_keluarga_anggota_profile` ON `data_keluarga_anggota_profile` . `id_data_keluarga` = `data_keluarga` . `id_data_keluarga`WHERE `data_keluarga` . `id_kecamatan` = "'.$kecamatan.'" AND `data_keluarga_anggota_profile` . `kode` = "kesehatan_5_g_18_kk_cebox")) 
        			');
        	}
		}else if($id_judul=="37"){
			//hilangminat
			if ($variabel=="Ya") {
        		$this->db->where("kode",'kesehatan_5_g_15_kk_cebox');
        	}else{
        		$this->db->where('(`data_keluarga`.`id_kecamatan` =  "'.$kecamatan.'"
				AND CONCAT( data_keluarga_anggota.id_data_keluarga, data_keluarga_anggota.no_anggota ) NOT IN (
						SELECT CONCAT(data_keluarga_anggota_profile.id_data_keluarga, data_keluarga_anggota_profile.no_anggota ) 
						FROM (`data_keluarga`) JOIN `data_keluarga_anggota_profile` ON `data_keluarga_anggota_profile` . `id_data_keluarga` = `data_keluarga` . `id_data_keluarga`WHERE `data_keluarga` . `id_kecamatan` = "'.$kecamatan.'" AND `data_keluarga_anggota_profile` . `kode` = "kesehatan_5_g_15_kk_cebox")) 
        			');
        	}
		}else if($id_judul=="38"){
			$this->db->where("data_keluarga_anggota_profile.kode","kesehatan_3_g_4_radio");
        	if ($variabel=="Ya") {
        		$this->db->where("data_keluarga_anggota_profile.value",'0');
        	}else{
        		$this->db->where("data_keluarga_anggota_profile.value",'1');
        	}
		}else if($id_judul=="39"){
			$this->db->where("data_keluarga_anggota_profile.kode","kesehatan_3_g_6_radio");
        	if ($variabel=="Ya") {
        		$this->db->where("data_keluarga_anggota_profile.value",'0');
        	}else{
        		$this->db->where("data_keluarga_anggota_profile.value",'1');
        	}
		}else if($id_judul=="40"){
			$this->db->where("data_keluarga_anggota_profile.kode","kesehatan_6_g_1_radi4");
        	if ($variabel=="Lengkap") {
        		$this->db->where("data_keluarga_anggota_profile.value",'0');
        	}elseif ($variabel=="Tidak Tahu") {
        		$this->db->where("data_keluarga_anggota_profile.value",'1');
        	}elseif ($variabel=="Lengkap Sesuai Umur") {
        		$this->db->where("data_keluarga_anggota_profile.value",'2');
        	}else{
        		$this->db->where("data_keluarga_anggota_profile.value",'3');
        	}
		}else if($id_judul=="41"){
        	$this->db->join('data_keluarga_anggota_profile','data_keluarga_anggota_profile.id_data_keluarga = data_keluarga.id_data_keluarga','left');
        	$this->db->join("data_keluarga_anggota","data_keluarga_anggota.id_data_keluarga = data_keluarga_anggota_profile.id_data_keluarga AND data_keluarga_anggota.no_anggota = data_keluarga_anggota_profile.no_anggota");
        	$this->db->where("YEAR(CURDATE())-YEAR(data_keluarga_anggota.tgl_lahir) <= 5");
        	$this->db->where("data_keluarga_anggota_profile.kode",'kesehatan_6_g_1_radi4');
        	if ($variabel=="Lengkap") {
        		$this->db->where("data_keluarga_anggota_profile.value",'0');
        	}elseif ($variabel=="Tidak Tahu") {
        		$this->db->where("data_keluarga_anggota_profile.value",'1');
        	}elseif ($variabel=="Lengkap Sesuai Umur") {
        		$this->db->where("data_keluarga_anggota_profile.value",'2');
        	}else{
        		$this->db->where("data_keluarga_anggota_profile.value",'3');
        	}
		}else if($id_judul=="42"){
			//wanitasubur
			$this->db->where("id_pilihan_kelamin","6");
			$this->db->where("(YEAR(CURDATE()) - YEAR(tgl_lahir)) >= '16'");
			$this->db->where("(YEAR(CURDATE()) - YEAR(tgl_lahir)) <= '49'");
			foreach ($village->result_array() as $cl_village) {
				if ($variabel==$cl_village['value']) {
					$this->db->where("village.value",$cl_village['value']);
				}
			}
		}else if($id_judul=="43"){
			//jmlkk
		}else if($id_judul=="44"){
			$this->db->join('data_keluarga_kb','data_keluarga_kb.id_data_keluarga = data_keluarga.id_data_keluarga','left');
			$this->db->where("kode",'berencana_II_4_kontrasepsi_sepsi');
			if ($variabel=="IUD") {
        		$this->db->where("data_keluarga_kb.value",'0');
        	}elseif ($variabel=="MOW") {
        		$this->db->where("data_keluarga_kb.value",'1');
        	}elseif ($variabel=="MOP") {
        		$this->db->where("data_keluarga_kb.value",'2');
        	}elseif ($variabel=="Suntik") {
        		$this->db->where("data_keluarga_kb.value",'3');
        	}elseif ($variabel=="Batal Pilih") {
        		$this->db->where("data_keluarga_kb.value",'4');
        	}elseif ($variabel=="Kondom") {
        		$this->db->where("data_keluarga_kb.value",'5');
        	}elseif ($variabel=="Implan") {
        		$this->db->where("data_keluarga_kb.value",'6');
        	}elseif ($variabel=="Pil") {
        		$this->db->where("data_keluarga_kb.value",'7');
        	}else{
        		$this->db->where("data_keluarga_kb.value",'8');
        	}
		}else if($id_judul=="45"){
			//PUS
		}else if($id_judul=="46"){
			
			$baik = "((`data_keluarga_anggota_profile`.`value` LIKE '%ba%' OR `data_keluarga_anggota_profile`.`value` LIKE '%cukup%' OR `data_keluarga_anggota_profile`.`value` LIKE '%nor%' OR `data_keluarga_anggota_profile`.`value` LIKE '%nir%' OR `data_keluarga_anggota_profile`.`value` LIKE '%bi%' OR `data_keluarga_anggota_profile`.`value` LIKE '%bs%'  OR `data_keluarga_anggota_profile`.`value` LIKE '%bw%'  OR `data_keluarga_anggota_profile`.`value` LIKE '%cu%'  OR `data_keluarga_anggota_profile`.`value` LIKE '%good%' OR `data_keluarga_anggota_profile`.`value` LIKE '%mal%') AND (`data_keluarga_anggota_profile`.`value` NOT LIKE '%kur%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%under%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%kar%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%kut%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%kuw%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%run%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%leb%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%ver%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%bes%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%gem%'))";
			$kurang = "((`data_keluarga_anggota_profile`.`value` LIKE '%kur%' OR `data_keluarga_anggota_profile`.`value` LIKE '%under%' OR `data_keluarga_anggota_profile`.`value` LIKE '%kar%' OR `data_keluarga_anggota_profile`.`value` LIKE '%kut%' OR `data_keluarga_anggota_profile`.`value` LIKE '%kuw%' OR `data_keluarga_anggota_profile`.`value` LIKE '%run%') AND (`data_keluarga_anggota_profile`.`value` NOT LIKE '%ba%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%cukup%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%nor%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%nir%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%bi%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%bs%'  AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%bw%'  AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%cu%'  AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%good%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%mal%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%leb%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%ver%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%bes%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%gem%'))";
			$lebih = "((`data_keluarga_anggota_profile`.`value` LIKE '%leb%' OR `data_keluarga_anggota_profile`.`value` LIKE '%ver%' OR `data_keluarga_anggota_profile`.`value` LIKE '%bes%' OR `data_keluarga_anggota_profile`.`value` LIKE '%gem%') AND (`data_keluarga_anggota_profile`.`value` NOT LIKE '%ba%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%cukup%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%nor%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%nir%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%bi%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%bs%'  AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%bw%'  AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%cu%'  AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%good%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%mal%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%kur%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%under%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%kar%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%kut%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%kuw%' AND `data_keluarga_anggota_profile`.`value` NOT LIKE '%run%'))";
			
																																																																																																										
			
			if ($variabel=="Gizi Baik") {
				$this->db->where("data_keluarga_anggota_profile.kode","kesehatan_6_g_4_sg_text");		
				$this->db->where($baik);
			}elseif ($variabel=="Gizi Kurang") {
				$this->db->where("data_keluarga_anggota_profile.kode","kesehatan_6_g_4_sg_text");		
				$this->db->where($kurang);
			}elseif ($variabel=="Gizi Lebih") {
				$this->db->where("data_keluarga_anggota_profile.kode","kesehatan_6_g_4_sg_text");		
				$this->db->where($lebih);
			}else{
				//error
				$this->db->where('(`data_keluarga`.`id_kecamatan` =  "'.$kecamatan.'"
				AND CONCAT( data_keluarga_anggota.id_data_keluarga, data_keluarga_anggota.no_anggota ) NOT IN (
						SELECT CONCAT(data_keluarga_anggota_profile.id_data_keluarga, data_keluarga_anggota_profile.no_anggota ) 
						FROM (`data_keluarga`) JOIN `data_keluarga_anggota_profile` ON `data_keluarga_anggota_profile` . `id_data_keluarga` = `data_keluarga` . `id_data_keluarga`WHERE `data_keluarga` . `id_kecamatan` = "'.$kecamatan.'" AND `data_keluarga_anggota_profile` . `kode` = "kesehatan_6_g_4_sg_text" AND '.$baik.' AND '.$kurang.')) 
        			');
        	}
		}else if($id_judul=="47"){
			foreach ($mst_kawin->result_array() as $mst_kawin) {
				if ($variabel==$mst_kawin['value']) {
					$this->db->where("id_pilihan_kawin",$mst_kawin['id_pilihan']);
				}
			}
		}else if($id_judul=="48"){
			$this->db->join("data_keluarga_profile","data_keluarga_profile.id_data_keluarga = data_keluarga.id_data_keluarga");
			$this->db->where("data_keluarga_profile.value",'1');
			if ($variabel=="Ledeng / Kemasan") {
				$this->db->where("data_keluarga_profile.kode","profile_a_2_a_radio");
			}elseif ($variabel=="Sumur Terlindung / Pompa") {
				$this->db->where("data_keluarga_profile.kode","profile_a_2_b_radio");
			}elseif ($variabel=="Air Hujan / Sungai") {
				$this->db->where("data_keluarga_profile.kode","profile_a_2_c_radio");
			}else{
				$this->db->where("data_keluarga_profile.kode","profile_a_2_d_radio");
			}
		}else if($id_judul=="49"){
			//Hamil
			$this->db->join("cl_village","cl_village.code = data_keluarga.id_desa",'left');
			foreach ($village->result_array() as $cl_village) {
				if ($variabel==$cl_village['value']) {
					$this->db->where("data_keluarga_anggota_profile.kode","kesehatan_0_g_9_radio");
					$this->db->where("data_keluarga_anggota_profile.value","0");
					$this->db->where("cl_village.value",$cl_village['value']);
				}
			}
		}else{
			return $judul;
		}
	}	
}
