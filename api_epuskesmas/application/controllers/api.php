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
}
?>