<?php
require APPPATH.'/libraries/REST_Controller.php';


class Api extends REST_Controller  {

    public function __construct(){
		parent::__construct();
		$this->load->model('sms/sentitems_model');
		$this->load->model('sms/setting_model');
	}

	function index(){
		foreach (getallheaders() as $name => $value) {
		    echo "$name: $value\n";
		}
	}

    function sms_post()
    {       
        $data = array('post'	=>	$this->post('id'));
        echo json_encode($data);
    }

	function sms_get()
    {
    	$data = $this->sentitems_model->get_data();
       	$res = array('sms'	=>	$data);
        echo json_encode($res);
    }


    function user_put()
    {       
        $data = array('returned: '. $this->put('id'));
        $this->response($data);
    }
 
    function user_delete()
    {
        $data = array('returned: '. $this->delete('id'));
        $this->response($data);
    }
}
?>