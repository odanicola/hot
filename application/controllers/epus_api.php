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
}