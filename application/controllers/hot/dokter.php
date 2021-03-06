<?php
class Dokter extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('hot/dokter_model');

	}

	function index(){
		$this->authentication->verify('hot','edit');
		$data['title_group'] 	= "Dashboard";
		$data['title_form']  	= "Data Dokter";
		$data['datapuskesmas']  = $this->dokter_model->get_pus("317204","code","cl_phc");
		
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

		$rows_all = $this->dokter_model->get_data_dokter();

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

		$rows = $this->dokter_model->get_data_dokter($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'cl_phc'	=> $act->cl_phc,
				'sdm_id'	=> $act->sdm_id,
				'code'	    => $act->code,
				'value'		=> $act->value,
				'sdm_nopeg'	=> $act->sdm_nopeg,
				'sdm_jenis'	=> $act->sdm_jenis
			);
		}
		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}

	function dokter_search(){
		$qr = $this->input->post('qr');
		$puskesmas 	= "P".$this->session->userdata('puskesmas');

		$this->db->where('cl_phc',$puskesmas);
		$this->db->where("value LIKE '%".$qr."%' OR sdm_id LIKE '%".$qr."%'");
		$rows = $this->dokter_model->get_data_dokter(0,10);
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'cl_phc'	=> $act->cl_phc,
				'sdm_id'	=> $act->sdm_id,
				'code'	    => $act->code,
				'value'		=> $act->value,
				'sdm_nopeg'	=> $act->sdm_nopeg,
				'sdm_jenis'	=> $act->sdm_jenis
			);
		}
		$size = sizeof($rows);
		$json = array(
			'content' => $data
		);

		echo json_encode($json);	
	}
}
