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
       if ($function=='do_action_dataDiagnosa_validation' || $function=='do_action_dataResep_validation') {
           header("Access-Control-Allow-Origin: *");
            
            $content = $this->direct_model($function,$form);
            
            if($request_output=='json'){
                echo json_encode($content);
            }else{
                $xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"?><user_info></user_info>");
                $this->array_to_xml($content,$xml_user_info);
                print $xml_user_info->asXML();
            }
       }else{
            for($x=0;$x<count($form);$x++){
               $imp = implode("|",$form[$x]['rules']);
               $this->form_validation->set_rules($form[$x]['var'], $form[$x]['label'], $imp); 
            }
           
            $this->form_validation->set_message('customAlpha', 'You can only use Alphabet (A-Z)');
            $this->form_validation->set_message('customAlphaNumber', 'You can only use Number (0-9)');
            $this->form_validation->set_message('customAlphaAndNumber', 'You can only use Alphabet (A-Z) and Number (0-9)');
           
            if($this->form_validation->run()== FALSE){
                header("Access-Control-Allow-Origin: *");
                $status_code = $this->api_model->status_code('412'); 
                $result = array('header'=>$this->api_model->header(),'content'=>array('validation'=>validation_errors()),'status_code'=>$status_code);
                
                if($request_output=='json'){
                    echo json_encode($result);
                }else{
                    $xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"?><user_info></user_info>");
                    $this->array_to_xml($result,$xml_user_info);
                    print $xml_user_info->asXML();
                }
                
            }else{
                header("Access-Control-Allow-Origin: *");
                
                $content = $this->direct_model($function);
                
                if($request_output=='json'){
                    echo json_encode($content);
                }else{
                    $xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"?><user_info></user_info>");
                    $this->array_to_xml($content,$xml_user_info);
                    print $xml_user_info->asXML();
                }
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
    
    function direct_model($function,$data=array()){
        if($function=='do_get_dataStokObatApotek_validation'){
            return $this->api_model->do_get_dataStokObatApotek();
        }else if($function=='do_get_dataAllPasien_validation'){
            return $this->api_model->do_get_dataAllPasien();
        }else if($function=='do_get_data_DetailPasien_validation'){
            return $this->api_model->do_get_data_DetailPasien();
        }else if($function=='do_get_dataAllDokter_validation'){
            return $this->api_model->do_get_dataAllDokter();   
        }else if($function=='do_get_dataAllObat_validation'){
            return $this->api_model->do_get_dataAllObat();   
        }else if($function=='do_get_dataPasienByDiagnosa_validation'){
            return $this->api_model->do_get_dataPasienByDiagnosa();   
        }else if($function=='do_action_dataDiagnosa_validation'){
            return $this->api_model->do_action_dataDiagnosa($data);   
        }else if($function=='do_action_dataResep_validation'){
            return $this->api_model->do_action_dataResep($data);   
        }else if($function=='do_aaction_dataAnamnesa_validation'){
            return $this->api_model->do_aaction_dataAnamnesa($data);   
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
    function get_data_DetailPasien(){
        $request_token = $this->input->post('request_token');
        $client_id = $this->input->post('client_id');
        
        $rules_request_token    = array('min_length[32]','max_length[32]','trim','required');
        $rules_client_id        = array('min_length[14]','max_length[14]','trim','required','callback_customAlphaNumber');
        
        $form[0] = array('var'=>'request_token','label'=>'Request Token','value'=>$request_token,'rules'=>$rules_request_token);
        $form[1] = array('var'=>'client_id','label'=>'Client ID','value'=>$client_id,'rules'=>$rules_client_id);
        
        $this->form_validasi('do_get_data_DetailPasien_validation',$form);
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
    function getarray_data_diagnosa(){
        $data['request_time ']      = $this->input->post('request_time');
        $data['request_token']      = $this->input->post('request_token'); 
        $data['client_id']          = $this->input->post('client_id');
        $data['request_output']     = $this->input->post('request_output');
        $data['no_register']        = $this->input->post('no_register');
        $data['jumlahdata']         = $this->input->post('jumlahdata');

        for ($i=1; $i <=$data['jumlahdata'] ; $i++)
        {   
                $data['diagnosa'][]=array(
                    'diagnosa_no_icdx'          => $this->input->post("diagnosa_no_icdx$i"), 
                    'diagnosa_no_urut'          => $this->input->post("diagnosa_no_urut$i"), 
                    'diagnosa_nama_diagnosa'    => $this->input->post("diagnosa_nama_diagnosa$i"), 
                    'diagnosa_jenis_kasus'      => $this->input->post("diagnosa_jenis_kasus$i"), 
                    'diagnosa_jenis_diagnosa'   => $this->input->post("diagnosa_jenis_diagnosa$i"), 
                    );
        }
        $this->action_data_diagnosa($data);
        
    }
    function action_data_diagnosa($data=array()){

        $this->form_validasi('do_action_dataDiagnosa_validation',$data);
    }
     function getarray_data_Resep(){
        $data['request_time ']      = $this->input->post('request_time');
        $data['request_token']      = $this->input->post('request_token'); 
        $data['client_id']          = $this->input->post('client_id');
        $data['request_output']     = $this->input->post('request_output');
        $data['no_register']        = $this->input->post('no_register');
        $data['jumlahdata']         = $this->input->post('jumlahdata');

        for ($i=1; $i <=$data['jumlahdata'] ; $i++)
        {   
                $data['resep'][]=array(
                    'resep_no_urut'     => $this->input->post("resep_no_urut$i"), 
                    'resep_kodeobat'    => $this->input->post("resep_kodeobat$i"), 
                    'resep_nama_obat'   => $this->input->post("resep_nama_obat$i"), 
                    'resep_jumlah'      => $this->input->post("resep_jumlah$i"), 
                    'resep_racikan'     => $this->input->post("resep_racikan$i"), 
                    'resep_dosis'       => $this->input->post("resep_dosis$i"), 
                    );
        }
        $this->action_data_resep($data);
        
    }




    function action_data_resep($data=array()){
        
        $this->form_validasi('do_action_dataResep_validation',$data);
    }
     function action_data_anamnesa($data=array()){
        $request_token          = $this->input->post('request_token');
        $client_id              = $this->input->post('client_id');
        $anamnesa_asisten_id    = $this->input->post('anamnesa_asisten_id');
        $anamnesa_asisten_nama  = $this->input->post('anamnesa_asisten_nama');
        $anamnesa_dokter_id     = $this->input->post('anamnesa_dokter_id');
        $anamnesa_dokter_nama   = $this->input->post('anamnesa_dokter_nama');
        $reg_id                 = $this->input->post('reg_id');
        

        $rules_request_token    = array('min_length[32]','max_length[32]','trim','required');
        $rules_client_id        = array('min_length[14]','max_length[14]','trim','required','callback_customAlphaNumber');
        $rules_anamnesa_asisten_id      = array('trim','required');
        $rules_anamnesa_asisten_nama    = array('trim','required');
        $rules_anamnesa_dokter_id       = array('trim','required');
        $rules_anamnesa_dokter_nama     = array('trim','required');
        $rules_reg_id                   = array('trim','required');

        $form[0] = array('var'=>'request_token','label'=>'Request Token','value'=>$request_token,'rules'=>$rules_request_token);
        $form[1] = array('var'=>'client_id','label'=>'Client ID','value'=>$client_id,'rules'=>$rules_client_id);
        $form[2] = array('var'=>'anamnesa_asisten_id','label'=>'Kode Asisten','value'=>$anamnesa_asisten_id,'rules'=>$rules_anamnesa_asisten_id);
        $form[3] = array('var'=>'anamnesa_asisten_nama','label'=>'Nama Asisten','value'=>$anamnesa_asisten_nama,'rules'=>$rules_anamnesa_asisten_nama);
        $form[4] = array('var'=>'anamnesa_dokter_id','label'=>'Kode Dokter','value'=>$anamnesa_dokter_id,'rules'=>$rules_anamnesa_dokter_id);
        $form[5] = array('var'=>'anamnesa_dokter_nama','label'=>'Nama Dokter','value'=>$anamnesa_dokter_nama,'rules'=>$rules_anamnesa_dokter_nama);
        $form[6] = array('var'=>'reg_id','label'=>'Register Pasien','value'=>$reg_id,'rules'=>$rules_reg_id);
        
        $this->form_validasi('do_aaction_dataAnamnesa_validation',$form);
    }
}
?>