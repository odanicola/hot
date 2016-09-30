<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package		 CodeIgniter
 * @subpackage 	 Model
 * @category	 PCare API
 *
 */
 
class Bpjs extends CI_Model {

	var $server;
	var $consid;
	var $secretkey;
	var $username; 
	var $password;
	var $xtime;
	var $xauth;
	var $data;
	var $signature;
	var $xsign;

	function __construct() {
		parent::__construct();
	   	
	   	require_once(APPPATH.'third_party/httpful.phar');

		$this->get_data_bpjs("live");
	}

	function get_demo_bpjs(){
    	$data = array();
    	$data['server'] 	='http://dvlp.bpjs-kesehatan.go.id:9080/pcare-rest-dev/v1/';
    	$data['username'] 	='pkm-tanahgaram';
    	$data['password'] 	='hctg1234';
    	$data['consid'] 	='28944';
    	$data['secretkey'] 	='1kV7BF08BC';
    	return $data;
	}
	function get_data_bpjs(){
        
        $data = $this->get_demo_bpjs();

	    $this->server 		= $data['server'];
	    $this->username 	= $data['username'];
	    $this->password 	= $data['password'];
	    $this->consid 		= $data['consid'];
	    $this->secretkey 	= $data['secretkey'];
	    $this->xtime 		= time();
	    $this->maxxtimeget 	= 15;
	    $this->maxxtimepost	= 120;
	    $this->xauth 		= base64_encode($this->username.':'.$this->password.':095');
	    $this->data 		= $this->consid."&".$this->xtime;
	    $this->signature 	= hash_hmac('sha256', $this->data, $this->secretkey, true);
	    $this->xsign 		= base64_encode($this->signature);

	    return $data;
    }

	function getApi($url=""){

	   $this->get_data_bpjs();

	   try
	    {
	      $response = \Httpful\Request::get($this->server.$url)
	      ->xConsId($this->consid)
	      ->xTimestamp($this->xtime)
	      ->xSignature($this->xsign)
	      ->xAuthorization("Basic ".$this->xauth)
	      ->timeout($this->maxxtimeget)
	      ->send();
	       $data = json_decode($response,true);
	    }
	    catch(Exception $E)
	    {
	      $reflector = new \ReflectionClass($E);
	      $classProperty = $reflector->getProperty('message');
	      $classProperty->setAccessible(true);
	      $data = $classProperty->getValue($E);
	      $data = "Tidak dapat terkoneksi ke server BPJS, silakan dicoba lagi";
	      $data = array("metaData"=>array("message" =>'error',"code"=>777));
	      //die(json_encode(array("res"=>"error","msg"=>$data)));

	    }
	    if($data["metaData"]["code"]=="500"){
	      die(json_encode(array("res"=>"500","msg"=>$data["metaData"]["message"])));
	    } 
	    /*update message ketika nomor kartu tidak ditemukan--> kasus no kartu tidak sama dengan 13*/
	    if($data["metaData"]["code"]=="412"){
	      die(json_encode(array("res"=>"412","msg"=>$data["response"][0]["message"])));
	    } 
	    print_r($data);
	    die();
	    return $data;
	}

	function postApi($url="", $data=array()){
		$this->get_data_bpjs("live");
	   
	   try
	    {
	      $response = \Httpful\Request::post($this->server.$url)
	      ->xConsId($this->consid)
	      ->xTimestamp($this->xtime)
	      ->xSignature($this->xsign)
	      ->xAuthorization("Basic ".$this->xauth)
		  ->body($data)
		  ->sendsJson()
		  ->timeout($this->maxxtimepost)
	      ->send();
	      $data = json_decode($response,true);
	    }
	    catch(Exception $E)
	    {
	      $reflector = new \ReflectionClass($E);
	      $classProperty = $reflector->getProperty('message');
	      $classProperty->setAccessible(true);
	      $data = $classProperty->getValue($E);
	      $data = "Tidak dapat terkoneksi ke server BPJS, silakan dicoba lagi";
	      $data = array("metaData"=>array("message" =>'error',"code"=>777));
	    }

	    return $data;
	}

	function putApi($url="", $data=array()){
	   $this->get_data_bpjs("live");
	   try
	    {
	      $response = \Httpful\Request::post($this->server.$url)
	      ->xConsId($this->consid)
	      ->xTimestamp($this->xtime)
	      ->xSignature($this->xsign)
	      ->xAuthorization("Basic ".$this->xauth)
		  ->body($data)
		  ->sendsJson()
		  ->timeout($this->maxxtimepost)
	      ->send();
	    }
	    catch(Exception $E)
	    {
	      $reflector = new \ReflectionClass($E);
	      $classProperty = $reflector->getProperty('message');
	      $classProperty->setAccessible(true);
	      $data = $classProperty->getValue($E);
	      $data = "Tidak dapat terkoneksi ke server BPJS, silakan dicoba lagi";
	      die(json_encode(array("res"=>"error","msg"=>$data)));
	    }
	    $data = json_decode($response,true);
	    
		if($response["metaData"]["code"]=="200"){

		}else{

		}

	    return $data;
	}

	function deleteApi($url="",$methode="live"){
	   $this->get_data_bpjs($methode);
	   try
        {
          $response = \Httpful\Request::delete($this->server.$url)
          ->xConsId($this->consid)
          ->xTimestamp($this->xtime)
          ->xSignature($this->xsign)
          ->xAuthorization("Basic ".$this->xauth)
          ->send();
          $data = json_decode($response,true);
        }
        catch(Exception $E)
        {
          $reflector = new \ReflectionClass($E);
          $classProperty = $reflector->getProperty('message');
          $classProperty->setAccessible(true);
          $data = $classProperty->getValue($E);
          $data = "Tidak dapat terkoneksi ke server BPJS, silakan dicoba lagi";
          $data = array("metaData"=>array("message" =>'error',"code"=>777));
        }
        return $data;
	}

	function bpjs_club($kdJnsKelompok="01"){
		$data = $this->getApi('pcare-rest-dev/v1/kelompok/club/'.$kdJnsKelompok);

      	return $data['response']['list'];
	}

	function bpjs_option($type="poli"){
		$data = $this->getApi('poli/fktp/0/99');

      	return $data['response']['list'];
	}

	function db_search($by="nik",$no){
		if($by == "nik"){
	    	$this->db->where('nik',$no);
	    	$search = $this->db->get('data_keluarga_anggota')->row();
			if(!empty($search->id_data_keluarga)) return "exists";
		}else{
	    	$this->db->where('bpjs',$no);
	    	$search = $this->db->get('data_keluarga_anggota')->row();
			if(!empty($search->id_data_keluarga)) return "exists";
		}
	}

	function bpjs_search($by="nik",$no){
		if($by == "nik"){
			$data = $this->getApi('peserta/nik/'.$no,"global",2);
		}else{
			$data = $this->getApi('peserta/'.$no,"global",2);
		}

      	return $data;
	}
			
	function insertbpjs($data=array()){
       $tampildata = $this->getApi('peserta/'.$data['noKartu']);
       print_r($tampildata);
       die();
       if (($tampildata['metaData']['message']=='error')&&($tampildata['metaData']['code']=='777')) {
           return $tampildata;
       }else{
	        if (array_key_exists("kdProvider",$tampildata['response']['kdProviderPst'])){
	            $kodeprov = $tampildata['response']['kdProviderPst']['kdProvider'];
	        }else{
	            $kodeprov = '0';
	        }
            $data_kunjungan = array(
              "noKunjungan"             =>  $data['noKunjungan'],
              "noKartu"                 =>  $data['noKartu'],
              "tglDaftar"               =>  $data['tglDaftar'],
              "keluhan"                 =>  $data['keluhan'],
              "kdSadar"                 =>  $data['kdSadar'],
              "sistole"                 =>  $data['sistole'],
              "diastole"                =>  $data['diastole'],
              "beratBadan"              =>  $data['beratBadan'],
              "tinggiBadan"             =>  $data['tinggiBadan'],
              "respRate"                =>  $data['respRate'],
              "heartRate"               =>  $data['heartRate'],
              "terapi"                  =>  $data['terapi'],
              "kdProviderRujukLanjut"   =>  $data['kdProviderRujukLanjut'],
              "kdStatusPulang"          =>  $data['kdStatusPulang'],
              "tglPulang"               =>  $data['tglPulang'],
              "kdDokter"                =>  $data['kdDokter'],
              "kdDiag1"                 =>  $data['kdDiag1'],
              "kdDiag2"                 =>  $data['kdDiag2'],
              "kdDiag3"                 =>  $data['kdDiag3'],
              "kdPoliRujukInternal"     =>  $data['kdPoliRujukInternal'],
              "kdPoliRujukLanjut"       =>  $data['kdPoliRujukLanjut'],
            ); 
            $datavisit = $this->postApi('kunjungan', $data_kunjungan);
            return  $datavisit;
        }
    }
   
    function simpandatabpjs($nourut=0,$kartu=0){
        $tampildata = $this->getApi('peserta/'.$kartu);
        if (($tampildata['metaData']['message']=='error') && ($tampildata['metaData']['code']=='777')) {
           return  'bpjserror';
        }else{
           if (isset($tampildata['response']['kdProviderPst']['kdProvider']) && $tampildata['response']['kdProviderPst']['kdProvider']!=""){
                $kodeprov = $tampildata['response']['kdProviderPst']['kdProvider'];
            }else{
                $kodeprov = '0';
            }
            $this->db->where('no_kartu',$kartu);
            $this->db->where('tgl_daftar',date("d-m-Y"));
            $dt = $this->db->get('data_keluarga_anggota_bpjs')->row();
            if(empty($dt->no_kartu)){
	            $data = array(
		            'kd_provider_peserta'  =>  $kodeprov,
		            'no_kartu'  	=> $kartu,
		            'tgl_daftar'  	=> date("d-m-Y"),
		            'no_urut'  		=> $nourut
	            );
	            $this->db->insert('data_keluarga_anggota_bpjs',$data);
            }
            return 'datatersimpan';
        }
    }
    function keluargaanggotabpjs($kode=0){
        $this->db->where('no_kartu',$kode);
        $query = $this->db->get('data_keluarga_anggota_bpjs');
        if ($query->num_rows() > 0) {
            $data = $query->row_array();
        }else{
            $data['kd_provider_peserta'] = '';
            $data['no_kartu'] 	= '';
            $data['tgl_daftar'] = '';
            $data['no_urut'] 	= '';
        }
        $query->free_result();
        return $data;
    }
    function deletebpjs($kode){
    	$tampildata = $this->keluargaanggotabpjs($kode);
    	/*$datavisit 	= $this->deleteApi("/pendaftaran/peserta/".$tampildata['no_kartu']."/tglDaftar/".$tampildata['tgl_daftar']."/noUrut/".$tampildata['no_urut']);
        if (($datavisit['metaData']['message']=='OK')&&($datavisit['metaData']['code']=='200')) {
            return 'datatersimpan';
        }else{
            return 'bpjserror';
        }*/
    }

	function bpjs_send_kegiatan($kode){
    	$this->db->where('id_data_kegiatan',$kode);
    	$data = $this->db->get('data_kegiatan')->row_array();

    	if($data['status_penyuluhan']==1 && $data['status_senam']==1){
    		$kdKegiatan = "11";
    	}elseif($data['status_penyuluhan']==1 && $data['status_senam']==0){
    		$kdKegiatan = "10";
    	}else{
    		$kdKegiatan = "01";
    	}

        $data_kegiatan = array(
          "eduId" 		=> null,
          "clubId" 		=> $data['kode_club'],
          "tglPelayanan"=> date("d-m-Y",strtotime($data['tgl'])),
          "kdKegiatan" 	=> $kdKegiatan,
          "kdKelompok" 	=> $data['kode_kelompok'],
          "materi" 		=> $data['materi'],
          "pembicara" 	=> $data['pembicara'],
          "lokasi" 		=> $data['lokasi'],
          "keterangan" 	=> $data['keterangan'],
          "biaya" 		=> $data['biaya'],
        ); 
        $datavisit = $this->postApi('kelompok/kegiatan', $data_kegiatan);
        if (($datavisit['metaData']['message']=='CREATED') && ($datavisit['metaData']['code']=='201')){
        	$update = array();
        	$update['eduId'] = $datavisit['response']['message'];
        	$this->db->where('id_data_kegiatan',$kode);
        	$this->db->update('data_kegiatan',$update);

        	$this->bpjs_resend_kegiatan($kode);
        	return 'ok';
        }
        elseif(($datavisit['metaData']['message']=='NOT_MODIFIED') && ($datavisit['metaData']['code']=='304')){
            return 'dataada';
        }
        elseif(($datavisit['metaData']['message']=='PRECONDITION_FAILED') && ($datavisit['metaData']['code']=='412')){
            return print_r($datavisit['response'],true);
        }else{
            return 'bpjserror';
        }
    }
   
	function bpjs_resend_kegiatan($kode){
    	$this->db->where('id_data_kegiatan',$kode);
    	$kegiatan = $this->db->get('data_kegiatan')->row_array();

    	/*if($kegiatan['status_penyuluhan']==1 && $kegiatan['status_senam']==1){
    		$kdKegiatan = "11";
    	}elseif($kegiatan['status_penyuluhan']==1 && $kegiatan['status_senam']==0){
    		$kdKegiatan = "10";
    	}else{
    		$kdKegiatan = "01";
    	}
        $data_kegiatan = array(
          "eduId" 		=> $kegiatan['eduId'],
          "clubId" 		=> $kegiatan['kode_club'],
          "tglPelayanan"=> date("d-m-Y",strtotime($kegiatan['tgl'])),
          "kdKegiatan" 	=> $kdKegiatan,
          "kdKelompok" 	=> $kegiatan['kode_kelompok'],
          "materi" 		=> $kegiatan['materi'],
          "pembicara" 	=> $kegiatan['pembicara'],
          "lokasi" 		=> $kegiatan['lokasi'],
          "keterangan" 	=> $kegiatan['keterangan'],
          "biaya" 		=> $kegiatan['biaya'],
        ); 
        $datavisit = $this->postApi('kelompok/kegiatan/', $data_kegiatan);
    	$getpeserta = $this->getApi("kelompok/peserta/".$kegiatan['eduId'], "live");
    	if(is_array($getpeserta['response']['list'])){
    		$list = $getpeserta['response']['list'];
    		foreach ($list as $pst) {
    			$delpeserta = $this->deleteApi("kelompok/peserta/".$kegiatan['eduId']."/".$pst['peserta']['noKartu'],"live");
			   	echo "\n".$this->server."kelompok/peserta/".$kegiatan['eduId']."/".$pst['peserta']['noKartu'];
			   	echo "\n".$this->consid;
			   	echo "\n".$this->xtime;
			   	echo "\n".$this->xsign;
			   	echo "\n"."Basic ".$this->xauth;
			   	echo "\n";

    			print_r($delpeserta);
    		}
    	}
        */

    	$this->db->where('id_data_kegiatan',$kode);
    	$peserta = $this->db->get('data_kegiatan_peserta')->result_array();
    	foreach ($peserta as $value) {
	        $data_peserta = array(
	          "eduId" 		=> $kegiatan['eduId'],
	          "noKartu" 	=> $value['no_kartu'],
	        ); 
        	$datapeserta = $this->postApi('kelompok/peserta', $data_peserta);
        	if (($datapeserta['metaData']['message']=='CREATED') && ($datapeserta['metaData']['code']=='201')){
	        	$update = array();
	        	$update['eduId'] = $kegiatan['eduId'];
	        	$this->db->where('id_data_kegiatan',$kode);
	        	$this->db->where('no_kartu',$value['no_kartu']);
	        	$this->db->update('data_kegiatan_peserta',$update);
	        }
	        /*elseif(($datapeserta['metaData']['message']=='NOT_MODIFIED') && ($datavisit['metaData']['code']=='304')){
	            return 'dataada';
	        }
	        elseif(($datapeserta['metaData']['message']=='PRECONDITION_FAILED') && ($datavisit['metaData']['code']=='412')){
	            return print_r($datapeserta['response'],true);
	        }else{
	            return 'bpjserror';
	        }*/
    	}

    	return "Data peserta berhasil terkirim ke PCare";
        
    }
   

}
?>