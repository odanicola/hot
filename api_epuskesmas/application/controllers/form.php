<?php
class Form extends CI_Controller {
    
    function __construct(){
        parent::__construct();
        $this->load->helper('html');
    }
    
    function index(){
    
    }
    
    function form_get_stokObatApotek(){
        $data = array();
        $data['title_form'] = "Form Test send_logout";
        
        $this->load->view('api/form_get_StokObatApotek',$data);
    }
    function form_get_allPasien(){
        $data = array();
        $data['title_form'] = "Form Test send_logout";

        $this->load->view('api/form_get_allpasien',$data);
    }
    function form_get_allDokter(){
        $data = array();
        $data['title_form'] = "Form Test send_logout";

        $this->load->view('api/form_get_alldokter',$data);
    }
    function form_get_allObat(){
        $data = array();
        $data['title_form'] = "Form Test send_logout";

        $this->load->view('api/form_get_allObat',$data);
    }
    function form_get_pasienByDiagnosa(){
        $data = array();
        $data['title_form'] = "Form Test send_logout";

        $this->load->view('api/form_get_pasienByDiagnosa',$data);
    }
}
?>