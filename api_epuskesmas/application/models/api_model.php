<?php
class Api_model extends CI_Model {
    
    function __construct(){
        parent::__construct();
        $codepuskesmas=$this->input->post('kodepuskesmas');
        if (!empty($codepuskesmas)) {
            $this->load->database("epuskesmas_live_jaktim_".$codepuskesmas, FALSE, TRUE);
        }
    }
    function do_get_dataStokObatApotek($token){
        $content=array();  
        $request_token  = $this->input->post('request_token');
        $client_id      = $this->input->post('client_id');
        
        $query = $this->db->get_where('mas_user_api', array('user_id'=>$client_id,'token'=>$request_token),1);
        if($query->num_rows() == 0) {
            $nameObat = $this->input->post('nameObat');
            $kodeObat = $this->input->post('kodeObat');
            $limit    = $this->input->post('limit');
            if(empty($kodeObat))
            {
                if(!empty($nameObat))
                {
                    $this->db->like('cl_drugnm.nama',$nameObat);
                }
            }
            else
            {
                $this->db->where('cl_drugnm.code',$kodeObat);
                if(!empty($nama))
                {
                    $this->db->or_like('cl_drugnm.nama',$nameObat);
                }
            }
            if (!empty($limit)) {
                $limit = $limit;
            }else{
                $limit = 10;
            }
            $this->db->group_by("cl_drugnm.code");
            $this->db->select("cl_drugnm.code, cl_drugnm.nama, cl_drugnm.harga, cl_drugbsunit.value,obat_stock_ket.stok_min, obat_stock_ket.stok_max, SUM(obat_stok_detail.jml_obat) AS stok_sekarang");
            $this->db->join('cl_drugbsunit','cl_drugnm.satuan = cl_drugbsunit.id','left');
            $this->db->join('obat_stok_detail','cl_drugnm.code = obat_stok_detail.id_obat','left');
            $this->db->join('obat_stock_ket','cl_drugnm.code = obat_stock_ket.obat_kode','left');
            $querygetObat=$this->db->get('cl_drugnm',$limit);
            if($querygetObat->num_rows() >0)
            {
                $datas = $querygetObat->result_array();
                foreach ($datas as $data) {
                    $content[] = array(   
                                    'code'      => $data['code'],
                                    'nama'      => $data['nama'],
                                    'value'     => $data['value'],
                                    'harga'     => $data['harga'],
                                    'stok_min'  => $data['stok_min']==null?"":$data['stok_min'],
                                    'stok_max'  => $data['stok_max']==null?"":$data['stok_max'],
                                    'stok_sekarang'=>$data['stok_sekarang']);
                }
                 $status_code = $this->status_code('200');
            }
            else
            {
                $content = array("reason"=>"Data yang dicari tidak diketemukan");
                $status_code = $this->status_code('204');
            }
        }else{
             $query->free_result(); 
             $content = array('validation'=>'Client ID and Token you entered is incorrect.');  
             $status_code = $this->status_code('412'); 
        }
        
        $update['token']=$token;
        $this->db->update('mas_user_api',$update,array('user_id' => $client_id));
        
        $result = array('header'=>$this->header($token),'content'=>$content,'status_code'=>$status_code);
        return $result;
    }
    function do_get_dataAllPasien($token){
        $content=array();  
        $request_token  = $this->input->post('request_token');
        $client_id      = $this->input->post('client_id');
        
        $query = $this->db->get_where('mas_user_api', array('user_id'=>$client_id,'token'=>$request_token),1);
        if($query->num_rows() == 0) {
            $nik        = $this->input->post('nik');
            $bpjs       = $this->input->post('bpjs');
            $nama       = $this->input->post('nama');
            $limit      = $this->input->post('limit');
            
            if(!empty($nama))
            {
                $this->db->like('pasien.cl_pname',$nama);
            }
            if(!empty($bpjs))
            {
                $this->db->where('bpjs.no_bpjs',$bpjs);
            }
            if(!empty($nik))
            {
                $this->db->where('pasien.cl_nik',$nik);
            }
            
            if (!empty($limit)) {
                $limit = $limit;
            }else{
                $limit = 10;
            }
            $this->db->order_by("id");
            $this->db->select("pasien.cl_pid AS id, concat_ws(' ',cl_pname,cl_midname,cl_surname) AS nama_lengkap, pasien.cl_address AS alamat, desa.value AS desa, concat(cl_bplace,' , ',substr(cl_bday,7,2),'-',substr(cl_bday,5,2),'-',substr(cl_bday,1,4)) AS ttl, pasien.data_origin, desa.*,bpjs.*",false);
            $this->db->join('cl_village desa','pasien.cl_village=desa.code','left');
            $this->db->join('bpjs_data_pasien bpjs','bpjs.cl_pid=pasien.cl_pid','left');
            $querygetObat=$this->db->get('cl_pasien as pasien',$limit);
            if($querygetObat->num_rows() >0)
            {
                $datas = $querygetObat->result_array();
                foreach ($datas as $data) {
                    $content[] = array(   
                                    'id'                => $data['id'],
                                    'nama_lengkap'      => $data['nama_lengkap'],
                                    'alamat'            => $data['alamat'],
                                    'desa'              => $data['desa'],
                                    'ttl'               => $data['ttl'],
                                    'data_origin'       => $data['data_origin'],
                                    'no_bpjs'           =>$data['no_bpjs'],
                                    'kode_provider'     =>$data['kode_provider'],
                                    'nama_provider'    =>$data['nama_provider'],
                                    'hubunganKeluarga' =>$data['hubunganKeluarga'],
                                    'jnsKelasKode'     =>$data['jnsKelasKode'],
                                    'jnsKelasNama'     =>$data['jnsKelasNama'],
                                    'jnsPesertaKode'   =>$data['jnsPesertaKode'],
                                    'jnsPesertaNama'   =>$data['jnsPesertaNama'],
                                    'status'           =>$data['status'],
                                    );
                }
                 $status_code = $this->status_code('200');
            }
            else
            {
                $content = array("reason"=>"Data yang dicari tidak diketemukan");
                $status_code = $this->status_code('204');
            }
        }else{
             $query->free_result(); 
             $content = array('validation'=>'Client ID and Token you entered is incorrect.');  
             $status_code = $this->status_code('412'); 
        }
        
        $update['token']=$token;
        $this->db->update('mas_user_api',$update,array('user_id' => $client_id));
        
        $result = array('header'=>$this->header($token),'content'=>$content,'status_code'=>$status_code);
        return $result;
    }
    function do_get_dataAllDokter($token){
        $content=array();  
        $request_token  = $this->input->post('request_token');
        $client_id      = $this->input->post('client_id');
        
        $query = $this->db->get_where('mas_user_api', array('user_id'=>$client_id,'token'=>$request_token),1);
        if($query->num_rows() == 0) {
            $no_peg     = $this->input->post('no_peg');
            $nama       = $this->input->post('nama');
            $limit      = $this->input->post('limit');
            
            if(!empty($nama))
            {
                $this->db->like('app_sdm.sdm_nama',$nama);
            }
            if(!empty($no_peg))
            {
                $this->db->where('app_sdm.sdm_nopeg',$no_peg);
            }
            
            if (!empty($limit)) {
                $limit = $limit;
            }else{
                $limit = 10;
            }
            $this->db->order_by("id");
            $this->db->select("app_sdm.sdm_id as id,app_sdm.sdm_nama as nama,app_sdm.sdm_nopeg,app_sdm.tanggal_lahir,app_sdm.tempat_lahir, ds_phc_staff.nama as jenis_sdm,",false);
            $this->db->join('ds_phc_staff','ds_phc_staff.id=app_sdm.sdm_jenis_id','left');
            $querygetObat=$this->db->get('app_sdm',$limit);
            if($querygetObat->num_rows() >0)
            {
                $datas = $querygetObat->result_array();
                foreach ($datas as $data) {
                    $content[] = array(   
                                    'id'                => $data['id'],
                                    'nama'              => $data['nama'],
                                    'sdm_nopeg'         => $data['sdm_nopeg'],
                                    'tempat_lahir'      => date("d-m-Y",strtotime($data['tanggal_lahir'])).', '.$data['tempat_lahir'],
                                    'jenis_sdm'         => $data['jenis_sdm'],
                                    );
                }
                 $status_code = $this->status_code('200');
            }
            else
            {
                $content = array("reason"=>"Data yang dicari tidak diketemukan");
                $status_code = $this->status_code('204');
            }
        }else{
             $query->free_result(); 
             $content = array('validation'=>'Client ID and Token you entered is incorrect.');  
             $status_code = $this->status_code('412'); 
        }
        
        $update['token']=$token;
        $this->db->update('mas_user_api',$update,array('user_id' => $client_id));
        
        $result = array('header'=>$this->header($token),'content'=>$content,'status_code'=>$status_code);
        return $result;
    }

    function do_get_dataAllObat($token){
        $content=array();  
        $request_token  = $this->input->post('request_token');
        $client_id      = $this->input->post('client_id');
        
        $query = $this->db->get_where('mas_user_api', array('user_id'=>$client_id,'token'=>$request_token),1);
        if($query->num_rows() == 0) {

            $kodeObat   = $this->input->post('kodeObat');
            $nameObat   = $this->input->post('nameObat');
            $limit      = $this->input->post('limit');
            
            if(!empty($nameObat))
            {
                $this->db->like('cl_drugnm.nama',$nameObat);
            }
            if(!empty($kodeObat))
            {
                $this->db->where('cl_drugnm.code',$kodeObat);
            }
            
            if (!empty($limit)) {
                $limit = $limit;
            }else{
                $limit = 10;
            }
            $this->db->order_by("id");
            $this->db->select("cl_drugnm.code as id,cl_drugnm.nama as nama_obat,cl_drugnm.kelas as kelas,cl_drugnm.title,cl_drugsunit.value as satuan_obat,cl_drugnm.jenis as jenis,cl_drugnm.isi as isi,cl_drugnm.harga as harga,",false);
            $this->db->join('cl_drugsunit','cl_drugnm.satuan=cl_drugsunit.id','left');
            $querygetObat=$this->db->get('cl_drugnm',$limit);
            if($querygetObat->num_rows() >0)
            {
                $datas = $querygetObat->result_array();
                foreach ($datas as $data) {
                    $content[] = array(   
                                    'id'                => $data['id'],
                                    'nama_obat'         => $data['nama_obat'],
                                    'kelas'             => $data['kelas'],
                                    'title'             => $data['title'],
                                    'satuan_obat'       => $data['satuan_obat'],
                                    'jenis'             => $data['jenis'],
                                    'isi'               => $data['isi'],
                                    'harga'             => $data['harga'],
                                    );
                }
                 $status_code = $this->status_code('200');
            }
            else
            {
                $content = array("reason"=>"Data yang dicari tidak diketemukan");
                $status_code = $this->status_code('204');
            }
        }else{
             $query->free_result(); 
             $content = array('validation'=>'Client ID and Token you entered is incorrect.');  
             $status_code = $this->status_code('412'); 
        }
        
        $update['token']=$token;
        $this->db->update('mas_user_api',$update,array('user_id' => $client_id));
        
        $result = array('header'=>$this->header($token),'content'=>$content,'status_code'=>$status_code);
        return $result;
    }
    function header($token=''){
        $arr['request_time']   = $this->input->post('request_time');
        $arr['response_time']  = time();
        $arr['request_token']  = $this->input->post('request_token');
        $arr['response_token'] = $token;
        $arr['request_output'] = $this->input->post('request_output');
        $arr['client_id']      = $this->input->post('client_id');
        $arr['last_active']    = $this->input->post('request_time');
        
        return $arr;
    }
    
    function status_code($code){
        $query = $this->db->get_where('mas_error_code', array('code'=>$code),1);
        if($query->num_rows()>0){
            $data = $query->row_array();
        
            $status_code = array('code'=>$data['code'],'detail'=>$data['name']);
        }else{
            $status_code = array('code'=>'','detail'=>'');
        }
        
        return $status_code;
    }
}
?>