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
	      $response = \Httpful\Request::put($this->server.$url)
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
	      die(json_encode(array("res"=>"error","msg"=>$data)));
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
   
    function deletebpjs($kode){
    	return $datavisit 	= $this->deleteApi("/kunjungan/".$kode);
    }
    function updatebpjs($data=array()){
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
        $datavisit = $this->putApi('kunjungan', $data_kunjungan);
        return  $datavisit;
    }
    function getkunjunganbpjs($nobpjs){
    	$data = $this->getApi('kunjungan/peserta/'.$nobpjs);
    	// print_r($data);
    	// die();
    	if (isset($data['response']['list'][0]['noKunjungan'])) {
    		return $data['response']['list'][0]['noKunjungan'];
    	}else{
    		return '';
    	}
    	
    }
   
	
   

}
?>