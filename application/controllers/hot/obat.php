<?php
class Obat extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('hot_model');
	}

	function index(){
		$this->authentication->verify('mst','edit');
		$data['title_group'] 	= "Dashboard";
		$data['title_form']  	= "Data Obat";
		$data['datapuskesmas']  = $this->hot_model->get_pus("317204","code","cl_phc");
		
		$this->session->set_userdata('filter_puskesmas','');
		$data['content'] 	 	= $this->parser->parse("hot/data_obat",$data,true);

		$this->template->show($data,"home");
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

		$rows_all = $this->hot_model->get_data_obat();

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

		$rows = $this->hot_model->get_data_obat($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'code'	    => $act->code,
				'sediaan'	=> $act->sediaan,
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

}
