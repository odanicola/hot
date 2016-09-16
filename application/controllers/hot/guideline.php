<?php
class Guideline extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('hot_model');
	}

	function index(){
		$this->authentication->verify('mst','edit');
		$data['title_group'] 	= "Dashboard";
		$data['title_form']  	= "Guideline";
		$data['datapuskesmas']  = $this->hot_model->get_pus("317204","code","cl_phc");
		
		$this->session->set_userdata('filter_puskesmas','');
		$data['content'] 	 	= $this->parser->parse("hot/guideline_show",$data,true);

		$this->template->show($data,"home");
	}

}
