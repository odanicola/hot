<?php
class Obat extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('hot/obat_model');

	}

	function index(){
		$this->authentication->verify('mst','edit');
		$data['title_group'] 	= "Dashboard";
		$data['title_form']  	= "Data Obat";
		$data['datapuskesmas']  = $this->obat_model->get_pus("317204","code","cl_phc");

		$this->session->set_userdata('filter_puskesmas','P'.$this->session->userdata('puskesmas'));
		$data['content'] 	 	= $this->parser->parse("hot/data_obat",$data,true);

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
		$rows_all = $this->obat_model->get_data_obat();

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
		$rows = $this->obat_model->get_data_obat($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'drug_id'	=> $act->drug_id,
				'code'	    => $act->code,
				'harga'		=> $act->harga,
				'nama'		=> $act->nama,
				'satuan'	=> $act->satuan,
				'stok'		=> $act->stok,
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
        $this->form_validation->set_rules('sediaan','Sediaan', 'trim');
        $this->form_validation->set_rules('status','Status', 'trim');

		if($this->form_validation->run()== FALSE){
			$data 					= $this->obat_model->get_data_obat_where($code); 
			$data['title_group']    = "Dashboard";
			$data['title_form']     = "Ubah Data Obat";
			$data['action']		    = "edit";
			$data['code']			= $code;
			$data['content'] 		= $this->parser->parse("hot/data_obat_form",$data,true);

		}elseif($this->obat_model->update_obat($code)==1){
				$this->session->set_flashdata('alert_form', 'Save data successful...');
				redirect(base_url()."hot/obat");
				die("OK");
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."hot/obat/edit");
			$data['alert_form'] = 'Save data failed...';
			die("NOTOK");
		}
		$this->template->show($data,"home");
	}

}
