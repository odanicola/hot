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
    
    function form_validasi($function,$form,$data=array()){
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
            
            $content = $this->direct_model($function,$data);
            
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
        }else if($function=='do_get_dataSettingBPJS_validation'){
            return $this->api_model->do_aaction_dataSettingBPJS($data);   
        }else if($function=='do_get_dataBPJSDiagnosaAnamnesa_validation'){
            return $this->api_model->do_get_dataBPJSDiagnosaAnamnesa($data);   
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
        $kodepuskesmas = $this->input->post('kodepuskesmas');
        
        $rules_request_token    = array('min_length[32]','max_length[32]','trim','required');
        $rules_client_id        = array('min_length[14]','max_length[14]','trim','required','callback_customAlphaNumber');
        $rules_kodepuskesmas    = array('trim','required');
        
        $form[0] = array('var'=>'request_token','label'=>'Request Token','value'=>$request_token,'rules'=>$rules_request_token);
        $form[1] = array('var'=>'client_id','label'=>'Client ID','value'=>$client_id,'rules'=>$rules_client_id);
        $form[2] = array('var'=>'kodepuskesmas','label'=>'Kode Puskesmas','value'=>$kodepuskesmas,'rules'=>$rules_kodepuskesmas);
        
        $this->form_validasi('do_get_dataStokObatApotek_validation',$form);
    }

    function get_data_allPasien(){
        $request_token = $this->input->post('request_token');
        $client_id = $this->input->post('client_id');
        $kodepuskesmas = $this->input->post('kodepuskesmas');


        $rules_request_token    = array('min_length[32]','max_length[32]','trim','required');
        $rules_client_id        = array('min_length[14]','max_length[14]','trim','required','callback_customAlphaNumber');
        $rules_kodepuskesmas    = array('trim','required');
        
        $form[0] = array('var'=>'request_token','label'=>'Request Token','value'=>$request_token,'rules'=>$rules_request_token);
        $form[1] = array('var'=>'client_id','label'=>'Client ID','value'=>$client_id,'rules'=>$rules_client_id);
        $form[2] = array('var'=>'kodepuskesmas','label'=>'Kode Puskesmas','value'=>$kodepuskesmas,'rules'=>$rules_kodepuskesmas);
        
        $this->form_validasi('do_get_dataAllPasien_validation',$form);
    }
    function get_data_DetailPasien(){
        $request_token  = $this->input->post('request_token');
        $client_id      = $this->input->post('client_id');
        $kodepuskesmas = $this->input->post('kodepuskesmas');
        

 
        $rules_request_token    = array('min_length[32]','max_length[32]','trim','required');
        $rules_client_id        = array('min_length[14]','max_length[14]','trim','required','callback_customAlphaNumber');
        $rules_kodepuskesmas    = array('trim','required');
        
        $form[0] = array('var'=>'request_token','label'=>'Request Token','value'=>$request_token,'rules'=>$rules_request_token);
        $form[1] = array('var'=>'client_id','label'=>'Client ID','value'=>$client_id,'rules'=>$rules_client_id);
        $form[2] = array('var'=>'kodepuskesmas','label'=>'Kode Puskesmas','value'=>$kodepuskesmas,'rules'=>$rules_kodepuskesmas);
        
        $this->form_validasi('do_get_data_DetailPasien_validation',$form);
    }
    function get_data_allDokter(){
        $request_token = $this->input->post('request_token');
        $client_id = $this->input->post('client_id');
        $kodepuskesmas = $this->input->post('kodepuskesmas');


        $rules_request_token    = array('min_length[32]','max_length[32]','trim','required');
        $rules_client_id        = array('min_length[14]','max_length[14]','trim','required','callback_customAlphaNumber');
        $rules_kodepuskesmas    = array('trim','required');
        
        $form[0] = array('var'=>'request_token','label'=>'Request Token','value'=>$request_token,'rules'=>$rules_request_token);
        $form[1] = array('var'=>'client_id','label'=>'Client ID','value'=>$client_id,'rules'=>$rules_client_id);
        $form[2] = array('var'=>'kodepuskesmas','label'=>'Kode Puskesmas','value'=>$kodepuskesmas,'rules'=>$rules_kodepuskesmas);
        
        $this->form_validasi('do_get_dataAllDokter_validation',$form);
    }
    function get_data_allObat(){
        $request_token = $this->input->post('request_token');
        $client_id = $this->input->post('client_id');
        $kodepuskesmas = $this->input->post('kodepuskesmas');


        $rules_request_token    = array('min_length[32]','max_length[32]','trim','required');
        $rules_client_id        = array('min_length[14]','max_length[14]','trim','required','callback_customAlphaNumber');
        $rules_kodepuskesmas    = array('trim','required');
        
        $form[0] = array('var'=>'request_token','label'=>'Request Token','value'=>$request_token,'rules'=>$rules_request_token);
        $form[1] = array('var'=>'client_id','label'=>'Client ID','value'=>$client_id,'rules'=>$rules_client_id);
        $form[2] = array('var'=>'kodepuskesmas','label'=>'Kode Puskesmas','value'=>$kodepuskesmas,'rules'=>$rules_kodepuskesmas);
        
        $this->form_validasi('do_get_dataAllObat_validation',$form);
    }
    function form_get_pasienByDiagnosa(){
        $request_token = $this->input->post('request_token');
        $client_id = $this->input->post('client_id');
        $kodepuskesmas = $this->input->post('kodepuskesmas');
        
        
        $rules_request_token    = array('min_length[32]','max_length[32]','trim','required');
        $rules_client_id        = array('min_length[14]','max_length[14]','trim','required','callback_customAlphaNumber');
        $rules_kodepuskesmas    = array('trim','required');
        
        $form[0] = array('var'=>'request_token','label'=>'Request Token','value'=>$request_token,'rules'=>$rules_request_token);
        $form[1] = array('var'=>'client_id','label'=>'Client ID','value'=>$client_id,'rules'=>$rules_client_id);
        $form[2] = array('var'=>'kodepuskesmas','label'=>'Kode Puskesmas','value'=>$kodepuskesmas,'rules'=>$rules_kodepuskesmas);
        $this->form_validasi('do_get_dataPasienByDiagnosa_validation',$form);
    }
    function getarray_data_diagnosa(){
        $data['request_time ']      = $this->input->post('request_time');
        $data['request_token']      = $this->input->post('request_token'); 
        $data['client_id']          = $this->input->post('client_id');
        $data['request_output']     = $this->input->post('request_output');
        $data['no_register']        = $this->input->post('no_register');
        $data['pengguna']           = $this->input->post('pengguna');
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
        return $data;
        
    }
    function action_data_diagnosa($data=array()){
        $request_token = $this->input->post('request_token');
        $client_id = $this->input->post('client_id');
        $kodepuskesmas = $this->input->post('kodepuskesmas');
        
        
        $rules_request_token    = array('min_length[32]','max_length[32]','trim','required');
        $rules_client_id        = array('min_length[14]','max_length[14]','trim','required','callback_customAlphaNumber');
        $rules_kodepuskesmas    = array('trim','required');
        
        $form[0] = array('var'=>'request_token','label'=>'Request Token','value'=>$request_token,'rules'=>$rules_request_token);
        $form[1] = array('var'=>'client_id','label'=>'Client ID','value'=>$client_id,'rules'=>$rules_client_id);
        $form[2] = array('var'=>'kodepuskesmas','label'=>'Kode Puskesmas','value'=>$kodepuskesmas,'rules'=>$rules_kodepuskesmas);
        
        $data = $this->getarray_data_diagnosa();

        $this->form_validasi('do_action_dataDiagnosa_validation',$form,$data);
    }
     function getarray_data_Resep(){
        $data['request_time ']      = $this->input->post('request_time');
        $data['request_token']      = $this->input->post('request_token'); 
        $data['client_id']          = $this->input->post('client_id');
        $data['request_output']     = $this->input->post('request_output');
        $data['no_register']        = $this->input->post('no_register');
        $data['pengguna']           = $this->input->post('pengguna');
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
        return $data;
        
    }
    function action_data_resep($data=array()){
        $request_token = $this->input->post('request_token');
        $client_id = $this->input->post('client_id');
        $kodepuskesmas = $this->input->post('kodepuskesmas');
        
        
        $rules_request_token    = array('min_length[32]','max_length[32]','trim','required');
        $rules_client_id        = array('min_length[14]','max_length[14]','trim','required','callback_customAlphaNumber');
        $rules_kodepuskesmas    = array('trim','required');
        
        $form[0] = array('var'=>'request_token','label'=>'Request Token','value'=>$request_token,'rules'=>$rules_request_token);
        $form[1] = array('var'=>'client_id','label'=>'Client ID','value'=>$client_id,'rules'=>$rules_client_id);
        $form[2] = array('var'=>'kodepuskesmas','label'=>'Kode Puskesmas','value'=>$kodepuskesmas,'rules'=>$rules_kodepuskesmas);
        
        $data = $this->getarray_data_Resep();

        $this->form_validasi('do_action_dataResep_validation',$form,$data);
    }
    function getarray_data_Anamnesa(){
        $data['request_time ']      = $this->input->post('request_time');
        $data['request_token']      = $this->input->post('request_token'); 
        $data['client_id']          = $this->input->post('client_id');
        $data['request_output']     = $this->input->post('request_output');
        $data['no_register']        = $this->input->post('reg_id');
        $data['pengguna']           = $this->input->post('pengguna');
        $data['anamnesa'][]['anamnesa']     = $this->input->post('anamnesa_anamnesa');
        $data['anamnesa'][]['dokter_id']     = $this->input->post('anamnesa_dokter_id');
        $data['anamnesa'][]['dokter_nama']   = $this->input->post('anamnesa_dokter_nama');
        $data['anamnesa'][]['asisten_nama']  = $this->input->post('anamnesa_asisten_nama');
        $data['anamnesa'][]['asisten_id']    = $this->input->post('anamnesa_asisten_id');
        $data['anamnesa'][]['sistole']       = $this->input->post('anamnesa_sistole');
        $data['anamnesa'][]['kesadaran']     = $this->input->post('anamnesa_kesadaran');
        $data['anamnesa'][]['berat']         = $this->input->post('anamnesa_berat');
        $data['anamnesa'][]['diastole']      = $this->input->post('anamnesa_diastole');
        $data['anamnesa'][]['tinggi']        = $this->input->post('anamnesa_tinggi');
        $data['anamnesa'][]['nadi']          = $this->input->post('anamnesa_nadi');
        $data['anamnesa'][]['suhu']          = $this->input->post('anamnesa_suhu');
        $data['anamnesa'][]['nafas']         = $this->input->post('anamnesa_nafas');
        $data['anamnesa'][]['terapi']        = $this->input->post('anamnesa_terapi');

        return $data;
        
    }
     function action_data_anamnesa($data=array()){
        $request_token = $this->input->post('request_token');
        $client_id = $this->input->post('client_id');
        $kodepuskesmas = $this->input->post('kodepuskesmas');
        
        
        $rules_request_token    = array('min_length[32]','max_length[32]','trim','required');
        $rules_client_id        = array('min_length[14]','max_length[14]','trim','required','callback_customAlphaNumber');
        $rules_kodepuskesmas    = array('trim','required');
        
        $form[0] = array('var'=>'request_token','label'=>'Request Token','value'=>$request_token,'rules'=>$rules_request_token);
        $form[1] = array('var'=>'client_id','label'=>'Client ID','value'=>$client_id,'rules'=>$rules_client_id);
        $form[2] = array('var'=>'kodepuskesmas','label'=>'Kode Puskesmas','value'=>$kodepuskesmas,'rules'=>$rules_kodepuskesmas);
        
        $data = $this->getarray_data_Anamnesa();

        $this->form_validasi('do_aaction_dataAnamnesa_validation',$form,$data);
    }
    function get_data_SettingBPJS(){
        $request_token = $this->input->post('request_token');
        $client_id = $this->input->post('client_id');
         $kodepuskesmas = $this->input->post('kodepuskesmas');

        $rules_request_token    = array('min_length[32]','max_length[32]','trim','required');
        $rules_client_id        = array('min_length[14]','max_length[14]','trim','required','callback_customAlphaNumber');
        $rules_kodepuskesmas    = array('trim','required');
        
        $form[0] = array('var'=>'request_token','label'=>'Request Token','value'=>$request_token,'rules'=>$rules_request_token);
        $form[1] = array('var'=>'client_id','label'=>'Client ID','value'=>$client_id,'rules'=>$rules_client_id);
        $form[2] = array('var'=>'kodepuskesmas','label'=>'Kode Puskesmas','value'=>$kodepuskesmas,'rules'=>$rules_kodepuskesmas);
        $this->form_validasi('do_get_dataSettingBPJS_validation',$form);
    }
    function getarray_data_BPJSDiagnosaAnamnesa(){
        $data['request_time ']      = $this->input->post('request_time');
        $data['request_token']      = $this->input->post('request_token'); 
        $data['client_id']          = $this->input->post('client_id');
        $data['request_output']     = $this->input->post('request_output');
        $data['no_register']        = $this->input->post('reg_id');
        $data['pengguna']           = $this->input->post('pengguna');
        $data['bpjs_poli']          = $this->input->post('bpjs_poli');
        $data['bpjs_poli_inter']    = $this->input->post('bpjs_poli_inter');
        $data['bpjs_provider']      = $this->input->post('bpjs_provider');
        $data['status_pulang']      = $this->input->post('status_pulang');


        $data['anamnesa'][]['anamnesa']     = $this->input->post('anamnesa_anamnesa');
        $data['anamnesa'][]['dokter_id']     = $this->input->post('anamnesa_dokter_id');
        $data['anamnesa'][]['dokter_nama']   = $this->input->post('anamnesa_dokter_nama');
        $data['anamnesa'][]['asisten_nama']  = $this->input->post('anamnesa_asisten_nama');
        $data['anamnesa'][]['asisten_id']    = $this->input->post('anamnesa_asisten_id');
        $data['anamnesa'][]['sistole']       = $this->input->post('anamnesa_sistole');
        $data['anamnesa'][]['kesadaran']     = $this->input->post('anamnesa_kesadaran');
        $data['anamnesa'][]['berat']         = $this->input->post('anamnesa_berat');
        $data['anamnesa'][]['diastole']      = $this->input->post('anamnesa_diastole');
        $data['anamnesa'][]['tinggi']        = $this->input->post('anamnesa_tinggi');
        $data['anamnesa'][]['nadi']          = $this->input->post('anamnesa_nadi');
        $data['anamnesa'][]['suhu']          = $this->input->post('anamnesa_suhu');
        $data['anamnesa'][]['nafas']         = $this->input->post('anamnesa_nafas');
        $data['anamnesa'][]['terapi']        = $this->input->post('anamnesa_terapi');
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
        $this->form_validasi('do_get_dataBPJSDiagnosaAnamnesa_validation',$form);
    }
}
?>