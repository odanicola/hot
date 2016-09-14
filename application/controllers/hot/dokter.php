<?php
class Dokter extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('hot_model');
	}

	function index(){
		$this->authentication->verify('hot','edit');
		$data['title_group'] 	= "Dashboard";
		$data['title_form']  	= "Data Dokter";
		$data['datapuskesmas']  = $this->hot_model->get_pus("317204","code","cl_phc");
		
		$this->session->set_userdata('filter_puskesmas','');
		$data['content'] 	 	= $this->parser->parse("hot/data_dokter",$data,true);

		$this->template->show($data,"home");
	}

	function filter_puskesmas(){
		if($_POST) {
			if($this->input->post('puskesmas') != '') {
				$this->session->set_userdata('filter_puskesmas',$this->input->post('puskesmas'));
			}
		}
	}
	
	function json(){
		$this->authentication->verify('hot','show');

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

		$kodepus=$this->session->userdata('filter_puskesmas');
		if ($this->session->userdata('filter_puskesmas')!='' && $this->session->userdata('filter_puskesmas')!='-') {
			$this->db->where('cl_phc',$kodepus);
		}

		$rows_all = $this->hot_model->get_data_dokter();

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

		$kodepus=$this->session->userdata('filter_puskesmas');
		if ($this->session->userdata('filter_puskesmas')!='' && $this->session->userdata('filter_puskesmas')!='-') {
			$this->db->where('cl_phc',$kodepus);
		}

		$rows = $this->hot_model->get_data_dokter($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'code'	    => $act->code,
				'status'	=> $act->status,
				'value'		=> $act->value,
				'edit'		=> 1,
				'delete'	=> 1
			);
		}
		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}

	function edit($code=0){
		$this->authentication->verify('hot','edit');

        $this->form_validation->set_rules('value','Nama', 'trim');
        $this->form_validation->set_rules('status','Status', 'trim');

		if($this->form_validation->run()== FALSE){
			$data 					= $this->hot_model->get_data_dokter_where($code); 
			$data['title_group']    = "Dashboard";
			$data['title_form']     = "Ubah Data Dokter";
			$data['action']		    = "edit";
			$data['code']			= $code;
			$data['content'] 		= $this->parser->parse("hot/data_dokter_add",$data,true);

		}elseif($this->hot_model->update_dokter($code)==1){
				$this->session->set_flashdata('alert_form', 'Save data successful...');
				redirect(base_url()."hot/dokter");
				die("OK");
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."hot/dokter/edit");
			$data['alert_form'] = 'Save data failed...';
			die("NOTOK");
		}
		$this->template->show($data,"home");
	}
}
