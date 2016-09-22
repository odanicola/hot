<?php
class Api extends CI_Controller {
    
    function __construct(){
        parent::__construct();
        $this->load->model('api_model');
        $this->load->helper('html');
    }
    
    function index(){
    
    }
    
    public function customAlpha($str){
        if ( preg_match('/^[a-z ]+$/i',$str)){
            return true;
        }else{
            return false;
        }
    }
    
    public function customAlphaNumber($str){
        if ( preg_match('/^[0-9]+$/i',$str)){
            return true;
        }else{
            return false;
        }
    }
    
    public function customAlphaAndNumber($str){
        if ( preg_match('/^[0-9 a-z]+$/i',$str)){
            return true;
        }else{
            return false;
        }
    }
    
    function form_validasi($function,$form){
       $request_output=$this->input->post('request_output');
       for($x=0;$x<count($form);$x++){
           $imp = implode("|",$form[$x]['rules']);
           $this->form_validation->set_rules($form[$x]['var'], $form[$x]['label'], $imp); 
       }
       
       $this->form_validation->set_message('customAlpha', 'You can only use Alphabet (A-Z)');
       $this->form_validation->set_message('customAlphaNumber', 'You can only use Number (0-9)');
       $this->form_validation->set_message('customAlphaAndNumber', 'You can only use Alphabet (A-Z) and Number (0-9)');
       
       if($this->form_validation->run()== FALSE){
			header("Access-Control-Allow-Origin: *");
            $token="";
            $status_code = $this->api_model->status_code('412'); 
            $result = array('header'=>$this->api_model->header($token),'content'=>array('validation'=>validation_errors()),'status_code'=>$status_code);
            
            if($request_output=='json'){
                echo json_encode($result);
            }else{
                $xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"?><user_info></user_info>");
                $this->array_to_xml($result,$xml_user_info);
                print $xml_user_info->asXML();
            }
            
       }else{
            header("Access-Control-Allow-Origin: *");
            $token=$this->randomString(32);
            $content = $this->direct_model($function,$token);
            
            if($request_output=='json'){
                echo json_encode($content);
            }else{
                $xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"?><user_info></user_info>");
                $this->array_to_xml($content,$xml_user_info);
                print $xml_user_info->asXML();
            }
       }
    }
    
    function array_to_xml($array, &$xml_user_info) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                if(!is_numeric($key)){
                    $subnode = $xml_user_info->addChild("$key");
                    $this->array_to_xml($value, $subnode);
                }else{
                    $subnode = $xml_user_info->addChild("item$key");
                    $this->array_to_xml($value, $subnode);
                }
            }else {
                $xml_user_info->addChild("$key",htmlspecialchars("$value"));
            }
        }
    }
    
    function direct_model($function,$token){
        if($function=='do_get_dataStokObatApotek_validation'){
            return $this->api_model->do_get_dataStokObatApotek($token);
        }else if($function=='do_get_dataAllPasien_validation'){
            return $this->api_model->do_get_dataAllPasien($token);
        }else if($function=='do_get_dataAllPasien_validation'){
            return $this->api_model->do_get_dataAllPasien($token);
        }else if($function=='do_get_dataAllDokter_validation'){
            return $this->api_model->do_get_dataAllDokter($token);   
        }else if($function=='do_get_dataAllObat_validation'){
            return $this->api_model->do_get_dataAllObat($token);   
        }else if($function=='do_get_dataPasienByDiagnosa_validation'){
            return $this->api_model->do_get_dataPasienByDiagnosa($token);   
        }else if($function=='do_insert_dataDiagnosa_validation'){
            return $this->api_model->do_insert_dataDiagnosa($token);   
        }
    }
    
    function randomString($length) {
        $key="";    
        $keys = array_merge(range(0,9), range('a', 'z'));
    
        for($i=0; $i < $length; $i++) {
            $key .= $keys[mt_rand(0, count($keys) - 1)];
        }
        
        return $key;
    }
    

    function get_data_stokObatApotek(){
        $request_token = $this->input->post('request_token');
        $client_id = $this->input->post('client_id');
        
        $rules_request_token    = array('min_length[32]','max_length[32]','trim','required');
        $rules_client_id        = array('min_length[14]','max_length[14]','trim','required','callback_customAlphaNumber');
        
        $form[0] = array('var'=>'request_token','label'=>'Request Token','value'=>$request_token,'rules'=>$rules_request_token);
        $form[1] = array('var'=>'client_id','label'=>'Client ID','value'=>$client_id,'rules'=>$rules_client_id);
        
        $this->form_validasi('do_get_dataStokObatApotek_validation',$form);
    }

    function get_data_allPasien(){
        $request_token = $this->input->post('request_token');
        $client_id = $this->input->post('client_id');
        
        $rules_request_token    = array('min_length[32]','max_length[32]','trim','required');
        $rules_client_id        = array('min_length[14]','max_length[14]','trim','required','callback_customAlphaNumber');
        
        $form[0] = array('var'=>'request_token','label'=>'Request Token','value'=>$request_token,'rules'=>$rules_request_token);
        $form[1] = array('var'=>'client_id','label'=>'Client ID','value'=>$client_id,'rules'=>$rules_client_id);
        
        $this->form_validasi('do_get_dataAllPasien_validation',$form);
    }
    function get_data_allDokter(){
        $request_token = $this->input->post('request_token');
        $client_id = $this->input->post('client_id');
        
        $rules_request_token    = array('min_length[32]','max_length[32]','trim','required');
        $rules_client_id        = array('min_length[14]','max_length[14]','trim','required','callback_customAlphaNumber');
        
        $form[0] = array('var'=>'request_token','label'=>'Request Token','value'=>$request_token,'rules'=>$rules_request_token);
        $form[1] = array('var'=>'client_id','label'=>'Client ID','value'=>$client_id,'rules'=>$rules_client_id);
        
        $this->form_validasi('do_get_dataAllDokter_validation',$form);
    }
    function get_data_allObat(){
        $request_token = $this->input->post('request_token');
        $client_id = $this->input->post('client_id');
        
        $rules_request_token    = array('min_length[32]','max_length[32]','trim','required');
        $rules_client_id        = array('min_length[14]','max_length[14]','trim','required','callback_customAlphaNumber');
        
        $form[0] = array('var'=>'request_token','label'=>'Request Token','value'=>$request_token,'rules'=>$rules_request_token);
        $form[1] = array('var'=>'client_id','label'=>'Client ID','value'=>$client_id,'rules'=>$rules_client_id);
        
        $this->form_validasi('do_get_dataAllObat_validation',$form);
    }
    function form_get_pasienByDiagnosa(){
        $request_token = $this->input->post('request_token');
        $client_id = $this->input->post('client_id');
        
        $rules_request_token    = array('min_length[32]','max_length[32]','trim','required');
        $rules_client_id        = array('min_length[14]','max_length[14]','trim','required','callback_customAlphaNumber');
        
        $form[0] = array('var'=>'request_token','label'=>'Request Token','value'=>$request_token,'rules'=>$rules_request_token);
        $form[1] = array('var'=>'client_id','label'=>'Client ID','value'=>$client_id,'rules'=>$rules_client_id);
        
        $this->form_validasi('do_get_dataPasienByDiagnosa_validation',$form);
    }
    function insert_data_diagnosa(){
        $request_token  = $this->input->post('request_token');
        $client_id      = $this->input->post('client_id');
        $no_register    = $this->input->post('no_register');
        $no_icdx        = $this->input->post('no_icdx');
        $nama_diagnosa  = $this->input->post('nama_diagnosa');
        $jenis_kasus   = $this->input->post('jenis_kasus');
        $jenis_diagnosa = $this->input->post('jenis_diagnosa');
        $no_urut = $this->input->post('no_urut');

        $rules_request_token    = array('min_length[32]','max_length[32]','trim','required');
        $rules_client_id        = array('min_length[14]','max_length[14]','trim','required','callback_customAlphaNumber');
        $rules_no_icdx          = array('trim','required');
        $rules_nama_diagnosa    = array('trim','required');
        $rules_jenis_kasus     = array('trim','required');
        $rules_jenis_diagnosa   = array('trim','required');
        $rules_no_register      = array('trim','required');
        $rules_no_urut          = array('trim');
        
        $form[0] = array('var'=>'request_token','label'=>'Request Token','value'=>$request_token,'rules'=>$rules_request_token);
        $form[1] = array('var'=>'client_id','label'=>'Client ID','value'=>$client_id,'rules'=>$rules_client_id);
        $form[2] = array('var'=>'no_register','label'=>'No Register','value'=>$no_register,'rules'=>$rules_no_register);
        $form[3] = array('var'=>'no_icdx','label'=>'No ICD-X','value'=>$no_icdx,'rules'=>$rules_no_icdx);
        $form[4] = array('var'=>'nama_diagnosa','label'=>'Nama Diagnosa','value'=>$nama_diagnosa,'rules'=>$rules_nama_diagnosa);
        $form[5] = array('var'=>'jenis_kasus','label'=>'Jenis Kasus','value'=>$jenis_kasus,'rules'=>$rules_jenis_kasus);
        $form[6] = array('var'=>'jenis_diagnosa','label'=>'Jenis Diagnosa','value'=>$jenis_diagnosa,'rules'=>$rules_jenis_diagnosa);
        $form[7] = array('var'=>'no_urut','label'=>'No urut','value'=>$no_urut,'rules'=>$rules_no_urut);
        
        $this->form_validasi('do_insert_dataDiagnosa_validation',$form);
    }
}
?>