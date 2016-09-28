<?php
class Epus_api extends CI_Controller {

    public function __construct(){
		parent::__construct();

		$this->load->model('epus');
	}

	function pasien_search($qr=""){
		$config = $this->epus->get_config("get_data_allPasien");

		$url 		= $config['server'];
		$qr 		= $this->input->post("qr");
		$puskesmas 	= "P".$this->session->userdata('puskesmas');

		$fields_string = array(
        	'client_id' 		=> $config['client_id'],
	        'kodepuskesmas' 	=> $puskesmas,
	        'filterPasien' 	 	=> $qr,
	        'limit' 			=> $config['limit'],
	        'request_output' 	=> $config['request_output'],
	        'request_time' 		=> $config['request_time'],
	        'request_token' 	=> $config['request_token']
	    );


		$curl = curl_init();

        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_POST,count($fields_string));
		curl_setopt($curl,CURLOPT_POSTFIELDS, $fields_string);

        $result = curl_exec($curl);
		curl_close($curl);

        echo $result;
	}

	function pasien_detail($id_pasien=""){
		$config = $this->epus->get_config("get_data_DetailPasien");

		$url 		= $config['server'];
		$puskesmas 	= "P".$this->session->userdata('puskesmas');

		$fields_string = array(
        	'client_id' 		=> $config['client_id'],
	        'id_pasien' 	 	=> $id_pasien,
	        'kodepuskesmas' 	=> $puskesmas,
	        'request_output' 	=> $config['request_output'],
	        'request_time' 		=> $config['request_time'],
	        'request_token' 	=> $config['request_token']
	    );


		$curl = curl_init();

        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_POST,count($fields_string));
		curl_setopt($curl,CURLOPT_POSTFIELDS, $fields_string);

        $result = curl_exec($curl);
		curl_close($curl);

        echo $result;
	}

	function pasien_reg_update($id_pasien="", $id_kunjungan=""){
		$config = $this->epus->get_config("get_data_DetailPasien");

		$url 		= $config['server'];
		$puskesmas 	= "P".$this->session->userdata('puskesmas');

		$fields_string = array(
        	'client_id' 		=> $config['client_id'],
	        'id_pasien' 	 	=> $id_pasien,
	        'kodepuskesmas' 	=> $puskesmas,
	        'request_output' 	=> $config['request_output'],
	        'request_time' 		=> $config['request_time'],
	        'request_token' 	=> $config['request_token']
	    );


		$curl = curl_init();

        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_POST,count($fields_string));
		curl_setopt($curl,CURLOPT_POSTFIELDS, $fields_string);

        $result = curl_exec($curl);
		curl_close($curl);

		$res = json_decode($result);

		if(!empty($res->content->reg_id) && $res->content->reg_id!=""){
			$data = array();
			$data['reg_id']	= $res->content->reg_id;

			$this->db->where('id_kunjungan', $id_kunjungan);
			$this->db->update('kunjungan', $data);

			echo ($res->content->reg_id);
		}else{
			echo time(). " - Not Found";
		}	
	}

	function dokter_search($qr=""){
		$config = $this->epus->get_config("get_data_allDokter");

		$url 		= $config['server'];
		$qr 		= $this->input->post("qr");
		$puskesmas 	= $this->input->post("puskesmas");

		$fields_string = array(
        	'client_id' 		=> $config['client_id'],
	        'kodepuskesmas' 	=> $puskesmas,
	        'filterDokter' 	 	=> $qr,
	        'limit' 			=> 9999,
	        'request_output' 	=> $config['request_output'],
	        'request_time' 		=> $config['request_time'],
	        'request_token' 	=> $config['request_token']
	    );


		$curl = curl_init();

        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_POST,count($fields_string));
		curl_setopt($curl,CURLOPT_POSTFIELDS, $fields_string);

        $result = curl_exec($curl);
		curl_close($curl);

		$res = json_decode($result);

		$this->db->where('cl_phc', $puskesmas);
		$this->db->delete('cl_sdm');

		if(!empty($res->content)){
			foreach ($res->content as $value) {
				$data = array();
				$data['cl_phc'] 	= $puskesmas;
				$data['code'] 		= $value->code;
				$data['value'] 		= $value->nama;
				$data['sdm_id'] 		= $value->sdm_id;
				$data['sdm_nopeg'] 		= $value->sdm_nopeg;
				$data['sdm_jenis_id'] 	= $value->sdm_jenis_id;
				$data['sdm_jenis'] 		= $value->sdm_jenis;

				$this->db->insert('cl_sdm', $data);
			}

			echo count($res->content);
		}else{
			echo "-";
		}
	}

	function obat_search($qr=""){
		$config = $this->epus->get_config("get_data_stokObatApotek");

		$url 		= $config['server'];
		$qr 		= $this->input->post("qr");
		$puskesmas 	= $this->input->post("puskesmas");

		$fields_string = array(
        	'client_id' 		=> $config['client_id'],
	        'kodepuskesmas' 	=> $puskesmas,
	        'filterObat' 	 	=> $qr,
	        'limit' 			=> 9999,
	        'request_output' 	=> $config['request_output'],
	        'request_time' 		=> $config['request_time'],
	        'request_token' 	=> $config['request_token']
	    );


		$curl = curl_init();

        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_POST,count($fields_string));
		curl_setopt($curl,CURLOPT_POSTFIELDS, $fields_string);

        $result = curl_exec($curl);
		curl_close($curl);

		$res = json_decode($result);

		$this->db->where('cl_phc', $puskesmas);
		$this->db->delete('cl_drug');

		if(!empty($res->content)){
			foreach ($res->content as $value) {
				$data = array();
				$data['cl_phc'] 	= $puskesmas;
				$data['drug_id']	= $value->drug_id;
				$data['code'] 		= $value->code;
				$data['nama'] 		= $value->nama;
				$data['harga'] 		= $value->harga;
				$data['satuan'] 	= $value->satuan;
				$data['stok'] 		= $value->stok;

				$this->db->insert('cl_drug', $data);
			}

			echo count($res->content);
		}else{
			echo "-";
		}
	}
}