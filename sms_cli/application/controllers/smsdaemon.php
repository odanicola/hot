<?php
class Smsdaemon extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('sms/inbox_model');
		$this->load->model('sms/opini_model');
		$this->load->model('sms/autoreply_model');
		$this->load->model('sms/bc_model');
		$this->load->model('sms/pbk_model');
		$this->load->model('sms/setting_model');

	   	require_once(APPPATH.'third_party/httpful.phar');

        $this->load->config('rest');
	}
	
	function index(){
		$data = $this->setting_model->get_data(); 
		$data['title_group'] = "SMS";
		$data['title_form'] = "Setting";

		$data['content'] = $this->parser->parse("sms/setting/setting",$data,true);
		$this->template->show($data,'home');
	}

	function doupdate(){
		$this->form_validation->set_rules('com', 'COM', 'trim|required');
		
		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('alert_form', validation_errors());
			redirect(base_url()."sms/setting");
		}elseif($this->setting_model->update_entry()){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."sms/setting");
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."sms/setting");
		}
	}

	function cleaninbox(){
		$this->db->query("TRUNCATE inbox");

		die("<br><br>Inbox sudah dibersihkan.");
	}	

	function cleansent(){
		$this->db->query("TRUNCATE sentitems");

		die("<br><br>Sent Items sudah dibersihkan.");
	}	

	function cleanoutbox(){
		$this->db->query("TRUNCATE outbox");

		die("<br><br>Outbox sudah dibersihkan.");
	}	

	function restart(){
        $this->db->where('key','path');
        $row = $this->db->get('sms_config')->row();
        $path = $row->value;

		$stop = NULL;

		exec($path."gammu-smsd -k",$stop);
		exec($path."gammu-smsd -c smsdrc -s", $hasil);

		foreach ($hasil as $row) {
			echo "<br>".$row;
		}
		
		die();
	}	

	function ussd(){
        $this->db->where('key','path');
        $row = $this->db->get('sms_config')->row();
        $path = $row->value;

		$ussd = $this->input->post('ussd');

		$hasil = NULL;
		$stop = NULL;
	
		exec($path."gammu-smsd -k",$stop);
		
		exec($path."gammu -c ".$path."gammurc getussd ".$ussd, $hasil);
		
		exec($path."gammu-smsd -c smsdrc -s");
		
		$i=0;
		foreach ($hasil as $row) {
			if($i>2) echo "<br>".$row;
			$i++;
		}
		
		die();
	}	

	function identify(){
        $this->db->where('key','path');
        $row = $this->db->get('sms_config')->row();
        $path = $row->value;

		$hasil = NULL;
		$stop = NULL;

		exec($path."gammu-smsd -k",$stop);
		
		exec($path."gammu -c ".$path."gammurc --identify ", $hasil);

		exec($path."gammu-smsd -c smsdrc -s");

		foreach ($hasil as $row) {
			echo "<br>".$row;
		}
		
		die();
	}	

	function cli($args = ""){

		if($this->input->is_cli_request()) {
			ini_set('max_execution_time', 0);
			ini_set('max_input_time', -1);
			ini_set('html_errors', 'Off');
			ini_set('register_argc_argv', 'On');
			ini_set('output_buffering', 'Off');
			ini_set('implicit_flush', 'On');
			
			$loop=true;
			$x=1;
			while($loop){
				echo("\n".date("d-m-Y h:i:s") ." ".$x." ".$args." versi 1.0");
				
				$this->get_sms();
				$this->post_sms();
				
				/*$this->sms_reply($args);

				$this->sms_autoreply($args);

				$this->sms_opini($args);

				$this->sms_broadcast($args);*/

				$x++;
				sleep(5);
			}	
		}else{
			$this->get_sms();
			$this->post_sms();
			die("access via cli");
		}

	}

	function get_sms(){
		$uri = $this->config->item('sms_server')."sms";
	   	try
	    {
		  $response = \Httpful\Request::get($uri)
		    ->authenticateWith($this->config->item('sms_username'), $this->config->item('sms_password'))
		    ->send();    
	    }
	    catch(Exception $E)
	    {
	      $reflector = new \ReflectionClass($E);
	      $classProperty = $reflector->getProperty('message');
	      $classProperty->setAccessible(true);
	      $data = $classProperty->getValue($E);
	      $data = array("metaData"=>array("message" =>'error',"code"=>777));
	    }

		$sms = json_decode($response,true);
		print_r($sms);
		if(is_array($sms['sms'])){
			foreach ($sms['sms'] as $row) {
				$this->sms_send($row['DestinationNumber'],$row['TextDecoded']);
			}
		}
	}

	function post_sms(){
		$uri = $this->config->item('sms_server')."sms";
		$data = array(
              "id" => "1234",
              "status" => "OK"
            ); 
	   	try
	    {
		  $response = \Httpful\Request::post($uri)
		    ->authenticateWith($this->config->item('sms_username'), $this->config->item('sms_password'))
		    ->body($data)	              // attach a body/payload...
			->sendsJson()                 // tell it we're sending (Content-Type) JSON...
			->timeout(60)
		    ->send();    
	    }
	    catch(Exception $E)
	    {
	      $reflector = new \ReflectionClass($E);
	      $classProperty = $reflector->getProperty('message');
	      $classProperty->setAccessible(true);
	      $data = $classProperty->getValue($E);
	      $data = array("metaData"=>array("message" =>'error',"code"=>777));
	    }

		print_r (json_decode($response,true));
	}
	
	function sms_send($nomor = "", $pesan=""){
		$data = array();
		$data['DestinationNumber'] = $nomor;
		$data['TextDecoded'] = $pesan;

		$this->db->insert('outbox',$data);
	}
}
