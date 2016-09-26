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
        $data['title_form'] = "Form Stok Obat di Apotek";
        
        $this->load->view('api/form_get_StokObatApotek',$data);
    }
    function form_get_allPasien(){
        $data = array();
        $data['title_form'] = "Form Semua Pasien";

        $this->load->view('api/form_get_allpasien',$data);
    }
    function form_get_detailPasien(){
        $data = array();
        $data['title_form'] = "Form Semua Pasien";

        $this->load->view('api/form_get_detailPasien',$data);
    }
    function form_get_allDokter(){
        $data = array();
        $data['title_form'] = "Form Semua Pegawai";

        $this->load->view('api/form_get_alldokter',$data);
    }
    function form_get_allObat(){
        $data = array();
        $data['title_form'] = "Form Semua Obat";

        $this->load->view('api/form_get_allObat',$data);
    }
    function form_get_pasienByDiagnosa(){
        $data = array();
        $data['title_form'] = "Form Semua Pasien Berdasarkan Penyakit";

        $this->load->view('api/form_get_pasienByDiagnosa',$data);
    }
    function form_get_insertAnamnesa(){
        $data = array();
        $data['title_form'] = "Form Semua Insert Anamnesa";

        $this->load->view('api/form_get_insertAnamnesa',$data);
    }
    function form_get_insertDiagosa(){
        $data = array();
        $data['title_form'] = "Form Semua Insert Diagnosa";

        $this->load->view('api/form_get_insertDiagosa',$data);   
    }
    function form_get_insertResep(){
        $data = array();
        $data['title_form'] = "Form Semua Insert Resep";

        $this->load->view('api/form_get_insertResep',$data);   
    }
    function form_get_settingBPJS(){
        $data = array();
        $data['title_form'] = "Form Semua Insert Resep";

        $this->load->view('api/form_get_settingBPJS',$data);   
    }
}
?>