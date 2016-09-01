<?php
class Morganisasi extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('morganisasi_model');
		$this->load->model('admin_model');
		$this->load->helper('html');
		$this->load->helper('captcha');
		$this->load->library('image_CRUD');
	}
	
	function index(){
		$this->authentication->verify('morganisasi','show');
		$data = array();
		$data['title_group'] 	= "Dashboard";
		$data['title_form'] 	= "Home";

		if($this->session->userdata('level')=="pasien"){
			$v = "hot/show";
		}else{
			$v = "hot/show_operator";
		}
		$data['content']= $this->parser->parse($v,$data,true);
		
		$this->template->show($data,'home');
	}

	function filter(){
		if($_POST) {
			if($this->input->post('bar_tpe') != '') {
				$this->session->set_userdata('filter_bar_tipe',$this->input->post('bar_tipe'));
			}
		}
	}

	function profile()
	{
		$this->authentication->verify('morganisasi','edit');		
		$data = $this->morganisasi_model->get_profile(); 
		$data['title_group']		="Dashboard";
		$data['title_form']			="Profil Pengguna";

		$data['username']			= $this->session->userdata('username');
		$data['provinsi_option']	= $this->crud->provinsi_option();
		$data['content']			= $this->parser->parse("sik/profile",$data,true);

		$this->template->show($data,"home");
		
	}

	function profile_doupdate() {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|callback_check_email2');
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'trim|required');
        $this->form_validation->set_rules('phone_number', 'Nama Pendaftar', 'trim');

		if($this->form_validation->run()== FALSE){
			// echo "0";
			echo validation_errors();
			// $this->session->set_flashdata('alert', "".validation_errors());
			// redirect(base_url()."sik/profile");
		}elseif($username=$this->morganisasi_model->update_profile()){
			// $this->session->set_flashdata('alert', 'Save data successful...');
			// echo "1";
			echo "Data berhasil disimpan";
			// redirect(base_url()."sik/profile");
		}else{
			// $this->session->set_flashdata('alert_form', 'Save data failed...');
			// echo "0";
			// redirect(base_url()."sik/profile");
			echo "Penyimpanan data gagal dilakukan";
		}
	}

	function profile_dosave()
	{
        $this->form_validation->set_rules('username','Username','trim|required|min_length[5]|max_length[12]|callback_check_username2');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
        $this->form_validation->set_rules('password','Password','trim|required|min_length[5]|matches[passconf]|callback_check_pass2');
 		$this->form_validation->set_rules('nama','Nama Lengkap Penanggung Jawab','trim|required');
		$this->form_validation->set_rules('phone_number','Nomor Telepon Penanggung Jawab','trim|required');
       
		
		if($this->form_validation->run()== FALSE){
			echo validation_errors();
		}
		else
		{	
			$this->morganisasi_model->create_profile();
			echo"1";		
		}
	}

	function puskesmas(){
		$data = array();
		$filter = $this->input->server('QUERY_STRING');
		parse_str($filter, $_GET);

		$this->db->select("cl_district.*");
		$this->db->where("key","district");
		$this->db->join("cl_district","cl_district.code=app_config.value");
		$cnf = $this->db->get("app_config")->row();
		if(!empty($cnf->code)){
			$this->db->like("value",$_GET['q']);
			$this->db->like("code",$cnf->code);
			$phc = $this->db->get("cl_phc")->result();		
			foreach($phc as $x){
				echo $x->value."	|	".str_replace("P","",$x->code)."	|	".$cnf->value."		| 	#
				";
			}
		}

		die();
	}

	function kota($kode_provinsi="",$kode_kota="")
	{
		$data['kota'] = "<option>-</option>";
		$kota = $this->crud->get_kota($kode_provinsi);		
		foreach($kota as $x=>$y){
			$data['kota'] .= "<option value='".$x."' ";
			if($kode_kota == $x) $data['kota'] .="selected";
			$data['kota'] .=">".$y."</option>";
		}

		header('Content-type: text/X-JSON');
		echo json_encode($data);
		exit;
	}
	
	function kecamatan($kode_kota="",$kode_kec="")
	{
		$data['kecamatan'] = "<option>-</option>";
		$kecamatan = $this->crud->get_kecamatan($kode_kota);		
		foreach($kecamatan as $x=>$y){
			$data['kecamatan'] .= "<option value='".$x."' ";
			if($kode_kec == $x) $data['kecamatan'] .="selected";
			$data['kecamatan'] .=">".$y."</option>";
		}

		header('Content-type: text/X-JSON');
		echo json_encode($data);
		exit;
	}
	
	function desa($kode_kec="",$kode_desa="")
	{
		$data['desa'] = "<option>-</option>";
		$desa = $this->crud->get_desa($kode_kec);		
		foreach($desa as $x=>$y){
			$data['desa'] .= "<option value='".$x."' ";
			if($kode_desa == $x) $data['desa'] .="selected";
			$data['desa'] .=">".$y."</option>";
		}

		header('Content-type: text/X-JSON');
		echo json_encode($data);
		exit;
	}
	
	function check_email($str){
		
			$check = $this->morganisasi_model->check_email($str);
			
			if($check>0){
				echo "0__Email tidak dapat digunakan";
			}else{
				echo "1__Email dapat digunakan";
			}
		
	}

	function check_email2($str){
		
			$check = $this->morganisasi_model->check_email($str);
			
			if($check>0){
				$this->form_validation->set_message('check_email2', 'Email tidak dapat digunakan');
				return FALSE;
			}else{
				return TRUE;
			}
		
	}

	function check_username($str){
		$forbidden = array("admin", "administrator", "operator", "manager", "root");
		if(in_array($str, $forbidden)){
			echo "0__Username tidak boleh digunakan";
		}else{
			
			$check = $this->morganisasi_model->check_username($str);
			if($check>0){
				echo "0__Username telah digunakan";
			}else{
				echo "1__Username dapat digunakan";
			}
		}
	}

	function check_username2($str){
		if(!preg_match('/[\\|`~\s\/}{\]\[!@#$%^&*()-+=?><,]/i', $str)){
			$forbidden = array("admin", "administrator", "operator", "manager", "root");
			if(in_array($str, $forbidden)){
				$this->form_validation->set_message('check_username2', 'Username tidak boleh digunakan');
				return FALSE;
			}else{
				$check = $this->morganisasi_model->check_username($str);
				if($check>0){
					$this->form_validation->set_message('check_username2', 'Username telah digunakan');
					return FALSE;
				}else{
					return TRUE;
				}
			}
		}else{
			$this->form_validation->set_message('check_username2', 'Username hanya boleh menggunakan huruf, angka, titik dan garis bawah');
			return FALSE;
		}
	}

	function check_pass2($str){
		$regex1=preg_match('/[A-Z]/', $str);
		$regex2=preg_match('/[a-z]/', $str);
		$regex3=preg_match('/[0-9]/', $str);
		
		
		 if (!$regex1 || !$regex2 || !$regex3){
			if(!$regex1==true)
			{
				$this->form_validation->set_message('check_pass2', 'Format password harus kombinasi huruf besar');
			}
			else if(!$regex2==true)
			{
				$this->form_validation->set_message('check_pass2', 'Format password harus kombinasi huruf kecil');
			}
			else
			{
				$this->form_validation->set_message('check_pass2', 'Format password harus kombinasi angka');
			}
			return FALSE;
		 }
		 else{
			return TRUE;
 		 }
  	}

	function unset_session()
	{
		if($this->morganisasi_model->finishsession()){
			$this->session->unset_userdata('id_session');
			echo"1";
		}else{
			echo "error";
		}
	}

	function profile_doedit(){
        $this->form_validation->set_rules('nama','Nama Lengkap Penanggung Jawab','trim|required');
        $this->form_validation->set_rules('jabatan','Jabatan Penanggung Jawab','trim|required');
        //$this->form_validation->set_rules('nomor_sik','Nomor SIK Penanggung Jawab','trim|required');
        $this->form_validation->set_rules('phone_number','Nomor Telepon Penanggung Jawab','trim|required');
        $this->form_validation->set_rules('email','Email','trim|required|valid_email');
        
		if($this->form_validation->run()== FALSE){
			echo validation_errors();
		}else{
			if($this->morganisasi_model->update_profile()){
				
				$this->morganisasi_model->update_status();
			
				echo "1";
			}else{
				echo "Simpan Data Error";
			}
		}
	}

	function profile_dopasswd(){
		$this->form_validation->set_rules('npassword','Password Baru','trim|required|min_length[5]|matches[cpassword]|callback_check_pass2');
		$this->form_validation->set_rules('cpassword', 'Konfirmasi Password', 'trim|required');
        
		if($this->form_validation->run()== FALSE){
			// $this->session->set_flashdata('alert', "".validation_errors());
			echo validation_errors();
			// redirect(base_url()."sik/profile");
		}else{
			if($this->morganisasi_model->check_password()){
    			$username =$this->session->userdata('username');
				if(!$this->morganisasi_model->update_password($username)) {
					// $this->session->set_flashdata('alert', 'Save data failed...');
					echo "Save data failed...";
				} else {
					// echo "Password berhasil disimpan";
					echo "1";
					// $this->session->set_flashdata('alert', 'Password berhasil disimpan');
				}

				// redirect(base_url()."sik/profile");
			}else{
				// $this->session->set_flashdata('alert', 'Password lama salah...');
				// redirect(base_url()."sik/profile");
				echo "Password lama salah...";
			}
		}
	}

	function valid_captcha($str)
	{
	  $expiration = time()-3600;
	  $this->db->query("DELETE FROM captcha WHERE captcha_time < ".$expiration);
	  $sql = "SELECT COUNT(*) AS count FROM captcha WHERE word = ?
	  AND ip_address = ? AND captcha_time > ?";
	  $binds = array($str, $this->input->ip_address(), $expiration);
	  $query = $this->db->query($sql, $binds);
	  $row = $query->row();
	  if ($row->count == 0)
	  {
		  $this->form_validation->set_message('valid_captcha', 'Captcha did not match');
		  return FALSE;
	  }else{
		  return TRUE;
	  }
	}

	function daftar(){

        $this->form_validation->set_rules('username','Username', 'trim');
        $this->form_validation->set_rules('bpjs','BPJS', 'trim');
        $this->form_validation->set_rules('pass','Password', 'trim');
        // $this->form_validation->set_rules('pass2','Konfirmasi Password', 'trim|required|min_length[5]|matches[pass]|callback_check_pass2');
        $this->form_validation->set_rules('nama','Nama', 'trim');
        $this->form_validation->set_rules('jk','Jenis Kelamin', 'trim');
        $this->form_validation->set_rules('tgl_lahir','Tanggal Lahir', 'trim');
        $this->form_validation->set_rules('phone_number','No.Telepon', 'trim');
        $this->form_validation->set_rules('email', 'Email','trim');
        $this->form_validation->set_rules('alamat', 'Alamat','trim');
        $this->form_validation->set_rules('code','Puskesmas','trim');

	    $data['action']				    = "add";
		$data['alert_form'] 		    = '';
		$data['bulan']			= array(
									1	=> "Januari", 
									2	=> "Februari", 
									3	=> "Maret", 
									4	=> "April", 
									5	=>  "Mei", 
									6	=> "Juni", 
									7	=>  "Juli", 
									8	=> "Agustus", 
									9	=> "September", 
									10	=> "Oktober", 
									11	=> "November", 
									12	=> "Desember");

		$data['datapuskesmas']  = $this->morganisasi_model->get_datawhere("317204","code","cl_phc");
		$bln = (int) date('m');
		$thn = date('Y');

		if($this->form_validation->run()== FALSE){
			die($this->parser->parse("sik/login",$data));
		}elseif($res = $this->morganisasi_model->daftar()){
			if ($res == 'false') {
				die("NOTOK");
			}else{
				die("OK");
			}
		}else{
			$data['alert_form'] = 'Save data failed...';
		}

		die($this->parser->parse("sik/login",$data));
	}
	

	function login()
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if($this->form_validation->run()){
			$this->user->login();
		}else{
			if($this->session->userdata('logged_in')){
				redirect(base_url());
			}
		}

		$data['datapuskesmas']  = $this->morganisasi_model->get_datawhere("317204","code","cl_phc");
		$data['title_group']	="Login";
		$data['title_form']		="Login";
		$data['bulan']			= array(
									1	=> "Januari", 
									2	=> "Februari", 
									3	=> "Maret", 
									4	=> "April", 
									5	=>  "Mei", 
									6	=> "Juni", 
									7	=>  "Juli", 
									8	=> "Agustus", 
									9	=> "September", 
									10	=> "Oktober", 
									11	=> "November", 
									12	=> "Desember");

		$data['content'] = $this->parser->parse("sik/login",$data,true);

		$bln = (int) date('m');
		$thn = date('Y');
		
		$this->template->show($data,'home');
	}

	function logout()
	{
		$this->user->logout();
	}
}
