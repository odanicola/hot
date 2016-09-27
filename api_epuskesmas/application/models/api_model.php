<?php
class Api_model extends CI_Model {
    
    function __construct(){
        parent::__construct();
        $codepuskesmas=$this->input->post('kodepuskesmas');
        if (!empty($codepuskesmas)) {
            $this->load->database("epuskesmas_live_jaktim_".$codepuskesmas, FALSE, TRUE);
        }
    }
    function do_get_dataStokObatApotek(){
        $content=array();  
        $request_token  = $this->input->post('request_token');
        $client_id      = $this->input->post('client_id');
        
        $query = $this->db->get_where('mas_user_api', array('user_id'=>$client_id,'token'=>$request_token),1);
        if($query->num_rows() == 0) {
            $filterObat = $this->input->post('filterObat');
            $limit    = $this->input->post('limit');
            if(!empty($filterObat))
            {
                $this->db->like('cl_drugnm.code',$filterObat);
                $this->db->or_like('cl_drugnm.nama',$filterObat);
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
                $content = array("validation"=>"Data yang dicari tidak diketemukan");
                $status_code = $this->status_code('204');
            }
        }else{
             $query->free_result(); 
             $content = array('validation'=>'Client ID and Token you entered is incorrect.');  
             $status_code = $this->status_code('412'); 
        }
        
        // $update['token']=$token;
        // $this->db->update('mas_user_api',$update,array('user_id' => $client_id));
        
        $result = array('header'=>$this->header(),'content'=>$content,'status_code'=>$status_code);
        return $result;
    }

    function do_get_dataAllPasien(){
        $content=array();  
        $request_token  = $this->input->post('request_token');
        $client_id      = $this->input->post('client_id');
        
        $query = $this->db->get_where('mas_user_api', array('user_id'=>$client_id,'token'=>$request_token),1);
        if($query->num_rows() == 0) {
            // $nik        = $this->input->post('nik');
            // $bpjs       = $this->input->post('bpjs');
            // $nama       = $this->input->post('nama');
            // $id_pasien  = $this->input->post('id_pasien');
            $filterPasien  = $this->input->post('filterPasien');
            $limit      = $this->input->post('limit');
            
            // if(!empty($nama))
            // {
            //     $this->db->like('pasien.cl_pname',$nama);
            // }
            // if(!empty($id_pasien))
            // {
            //     if (!empty($nama)) {
            //         $this->db->or_where('pasien.cl_pid',$id_pasien);
            //     }else{
            //         $this->db->where('pasien.cl_pid',$id_pasien);
            //     }
            // }
            // if(!empty($bpjs))
            // {
            //     if (!empty($nama) || !empty($id_pasien)) {
            //         $this->db->or_where('bpjs.no_bpjs',$bpjs);
            //     }else{
            //         $this->db->where('bpjs.no_bpjs',$bpjs);
            //     }
            // }
            // if(!empty($nik))
            // {
            //     if (!empty($nama) || !empty($id_pasien) || !empty($bpjs)) {
            //         $this->db->or_where('pasien.cl_nik',$nik);
            //     }else{
            //         $this->db->where('pasien.cl_nik',$nik);
            //     }
            // }
            if(!empty($filterPasien))
            {
                $this->db->like('pasien.cl_nik',$filterPasien);
                $this->db->or_like('pasien.cl_pid',$filterPasien);
                $this->db->or_like('bpjs.no_bpjs',$filterPasien);
                $this->db->or_like('pasien.cl_pname',$filterPasien);
            }
            if (!empty($limit)) {
                $limit = $limit;
            }else{
                $limit = 10;
            }
            $this->db->order_by("id");
            $this->db->select("pasien.cl_pid AS id,bpjs.no_bpjs,concat_ws(' ',cl_pname,cl_midname,cl_surname) AS nama_lengkap, pasien.cl_address AS alamat,pasien.cl_nik as nik",false);
            $this->db->join('bpjs_data_pasien bpjs','bpjs.cl_pid=pasien.cl_pid','left');
            $querygetPasien=$this->db->get('cl_pasien as pasien',$limit);
            if($querygetPasien->num_rows() > 0)
            {
                $datas = $querygetPasien->result_array();
                foreach ($datas as $data) {
                    $content[] = array(   
                                    'id'                => $data['id'],
                                    'nik'               => $data['nik'],
                                    'no_bpjs'           => $data['no_bpjs'],
                                    'nama_lengkap'      => $data['nama_lengkap'],
                                    'alamat'            => $data['alamat']
                                    );
                }
                 $status_code = $this->status_code('200');
            }
            else
            {
                $content = array("validation"=>"No Data Found");
                $status_code = $this->status_code('204');
            }
        }else{
             $query->free_result(); 
             $content = array('validation'=>'Client ID and Token you entered is incorrect.');  
             $status_code = $this->status_code('412'); 
        }
        
        // $update['token']=$token;
        // $this->db->update('mas_user_api',$update,array('user_id' => $client_id));
        
        $result = array('header'=>$this->header(),'content'=>$content,'status_code'=>$status_code);
        return $result;
    }
    function do_get_data_DetailPasien(){
        $content=array();  
        $request_token  = $this->input->post('request_token');
        $client_id      = $this->input->post('client_id');
        
        $query = $this->db->get_where('mas_user_api', array('user_id'=>$client_id,'token'=>$request_token),1);
        if($query->num_rows() == 0) {
            $id_pasien        = $this->input->post('id_pasien');
            
            $this->db->like('pasien.cl_pid',$id_pasien);

            $this->db->order_by("id");
            $this->db->select("pasien.cl_pid AS id,pasien.cl_nik as nik, concat_ws(' ',cl_pname,cl_midname,cl_surname) AS nama_lengkap, pasien.cl_address AS alamat, desa.value AS desa, concat(cl_bplace,' , ',substr(cl_bday,7,2),'-',substr(cl_bday,5,2),'-',substr(cl_bday,1,4)) AS ttl, pasien.data_origin, desa.*,bpjs.*",false);
            $this->db->join('cl_village desa','pasien.cl_village=desa.code','left');
            $this->db->join('bpjs_data_pasien bpjs','bpjs.cl_pid=pasien.cl_pid','left');
            $querygetObat=$this->db->get('cl_pasien as pasien');
            if($querygetObat->num_rows() >0)
            {
                $data = $querygetObat->row_array();
                $content = array(   
                                'id'                => $data['id'],
                                'nama_lengkap'      => $data['nama_lengkap'],
                                'alamat'            => $data['alamat'],
                                'desa'              => $data['desa'],
                                'ttl'               => $data['ttl'],
                                'nik'               => $data['nik'],
                                'data_origin'       => $data['data_origin'],
                                'no_bpjs'           => $data['no_bpjs'],
                                'kode_provider'     => $data['kode_provider'],
                                'nama_provider'     => $data['nama_provider'],
                                'hubunganKeluarga'  => $data['hubunganKeluarga'],
                                'jnsKelasKode'      => $data['jnsKelasKode'],
                                'jnsKelasNama'      => $data['jnsKelasNama'],
                                'jnsPesertaKode'    => $data['jnsPesertaKode'],
                                'jnsPesertaNama'    => $data['jnsPesertaNama'],
                                'status'            => $data['status'],
                                );
                 $status_code = $this->status_code('200');
            }
            else
            {
                $content = array("validation"=>"Data yang dicari tidak diketemukan");
                $status_code = $this->status_code('204');
            }
        }else{
             $query->free_result(); 
             $content = array('validation'=>'Client ID and Token you entered is incorrect.');  
             $status_code = $this->status_code('412'); 
        }
        
        // $update['token']=$token;
        // $this->db->update('mas_user_api',$update,array('user_id' => $client_id));
        
        $result = array('header'=>$this->header(),$content,'status_code'=>$status_code);
        return $result;
    }
    function do_get_dataAllDokter(){
        $content=array();  
        $request_token  = $this->input->post('request_token');
        $client_id      = $this->input->post('client_id');
        
        $query = $this->db->get_where('mas_user_api', array('user_id'=>$client_id,'token'=>$request_token),1);
        if($query->num_rows() == 0) {
            // $no_peg     = $this->input->post('no_peg');
            // $nama       = $this->input->post('nama');
            $filterDokter       = $this->input->post('filterDokter');
            $limit      = $this->input->post('limit');
            
            // if(empty($no_peg))
            // {
            //     if(!empty($nama))
            //     {
            //         $this->db->like('app_sdm.sdm_nama',$nama);
            //     }
            // }
            // else
            // {
            //     $this->db->where('app_sdm.sdm_nopeg',$no_peg);
            //     if(!empty($nama))
            //     {
            //         $this->db->or_like('app_sdm.sdm_nama',$nama);
            //     }
            // }
            if(!empty($filterDokter))
            {
                $this->db->like('app_sdm.sdm_id',$filterDokter);
                $this->db->or_like('app_sdm.sdm_nopeg',$filterDokter);
                $this->db->or_like('app_sdm.sdm_nama',$filterDokter);
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
                $content = array("validation"=>"Data yang dicari tidak diketemukan");
                $status_code = $this->status_code('204');
            }
        }else{
             $query->free_result(); 
             $content = array('validation'=>'Client ID and Token you entered is incorrect.');  
             $status_code = $this->status_code('412'); 
        }
        
        // $update['token']=$token;
        // $this->db->update('mas_user_api',$update,array('user_id' => $client_id));
        
        $result = array('header'=>$this->header(),'content'=>$content,'status_code'=>$status_code);
        return $result;
    }

    
    function do_get_dataPasienByDiagnosa(){
        $content=array();  
        $request_token  = $this->input->post('request_token');
        $client_id      = $this->input->post('client_id');
        
        $query = $this->db->get_where('mas_user_api', array('user_id'=>$client_id,'token'=>$request_token),1);
        if($query->num_rows() == 0) {

            $filterDiagnosa   = $this->input->post('filterDiagnosa');
            $limit          = $this->input->post('limit');
            // if(empty($kode_dianosa))
            // {
            //     if(!empty($nama_diagnosa))
            //     {
            //         $this->db->like('cl_icdx.value',$nama_diagnosa);
            //     }
            // }
            // else
            // {
            //     $this->db->where('cl_icdx.code',$kode_dianosa);
            //     if(!empty($nama_diagnosa))
            //     {
            //         $this->db->or_like('cl_icdx.value',$nama_diagnosa);
            //     }
            // }
            if(!empty($filterDiagnosa))
            {
                $this->db->like('cl_icdx.value',$filterDiagnosa);
                $this->db->or_like('cl_icdx.code',$filterDiagnosa);
            }
            if (!empty($limit)) {
                $limit = $limit;
            }else{
                $limit = 10;
            }
            $this->db->group_by("pasien_id");
            $this->db->select("cl_pasien.cl_pid as pasien_id,cl_pasien.cl_pname as nama_pasien,IF(cl_pasien.cl_gender='0' OR cl_pasien.cl_gender='2','Perempuan','Laki-laki') AS jeniskelamin,cl_pasien.cl_address as alamat,cl_icdx.value as diagonosa,app_poli_detail_diagnosa.diag_id as kode_diagnosa,app_reg.reg_poli,app_reg.reg_jenis_kasus,app_reg.reg_pemeriksa as kode_periksa,pemeriksa1.sdm_nama AS nama_pemeriksa,app_reg.reg_pemeriksa2 as kode_asiste,pemeriksa2.sdm_nama AS nama_asisten,app_reg.asuransi as kode_asuransi,cl_hinsnm.value AS nama_asuransi",false);
            $this->db->join('cl_pasien','app_reg.cl_pid = cl_pasien.cl_pid','left');
            $this->db->join('app_poli_detail_diagnosa','app_reg.reg_id = app_poli_detail_diagnosa.reg_id','left');
            $this->db->join('cl_icdx','cl_icdx.code = app_poli_detail_diagnosa.diag_id','left');
            $this->db->join('app_sdm pemeriksa1','pemeriksa1.sdm_id = app_reg.reg_pemeriksa','left');
            $this->db->join('app_sdm pemeriksa2','pemeriksa2.sdm_id = app_reg.reg_pemeriksa','left');
            $this->db->join('cl_hinsnm','app_reg.asuransi = cl_hinsnm.id ','left');
            $querygetObat=$this->db->get('app_reg',$limit);
            if($querygetObat->num_rows() >0)
            {
                $datas = $querygetObat->result_array();
                foreach ($datas as $data) {
                    $content[] = array(   
                                    'pasien_id'         => $data['pasien_id'],
                                    'nama_pasien'       => $data['nama_pasien'],
                                    'jeniskelamin'      => $data['jeniskelamin'],
                                    'alamat'            => $data['alamat'],
                                    'diagonosa'         => $data['diagonosa'],
                                    'kode_diagnosa'     => $data['kode_diagnosa'],
                                    'reg_poli'          => $data['reg_poli'],
                                    'reg_jenis_kasus'   => $data['reg_jenis_kasus'],
                                    'kode_periksa'      => $data['kode_periksa'],
                                    'nama_pemeriksa'    => $data['nama_pemeriksa'],
                                    'nama_pemeriksa'    => $data['nama_pemeriksa'],
                                    'kode_asiste'       => $data['kode_asiste'],
                                    'nama_asisten'      => $data['nama_asisten'],
                                    'kode_asuransi'     => $data['kode_asuransi'],
                                    'nama_asuransi'     => $data['nama_asuransi'],
                                    );
                }
                 $status_code = $this->status_code('200');
            }
            else
            {
                $content = array("validation"=>"Data yang dicari tidak diketemukan");
                $status_code = $this->status_code('204');
            }
        }else{
             $query->free_result(); 
             $content = array('validation'=>'Client ID and Token you entered is incorrect.');  
             $status_code = $this->status_code('412'); 
        }
        
        // $update['token']=$token;
        // $this->db->update('mas_user_api',$update,array('user_id' => $client_id));
        
        $result = array('header'=>$this->header(),'content'=>$content,'status_code'=>$status_code);
        return $result;
    }

    function do_action_dataDiagnosa($data=array()){
        $this->db->where('reg_id',$data['no_register']);
        if ($this->db->delete('app_poli_detail_diagnosa')==FALSE) {
            $content=array('validation'=>'Execution data is failed'); 
            $status_code = $this->status_code('417'); 
        }else{
            foreach ($data['diagnosa'] as $key) {
                $qCekUsername = $this->db->get_where('cl_icdx', array('trim(code)'=>$key['diagnosa_no_icdx']),1);
                if($qCekUsername->num_rows() > 0){
                    $datasave['reg_id']=$data['no_register'];
                    $datasave['diag_id']=$key['diagnosa_no_icdx'];
                    $datasave['diag_kasus']=$key['diagnosa_jenis_kasus'];
                    $datasave['diag_jenis']=$key['diagnosa_jenis_diagnosa'];
                    $datasave['no']=$key['diagnosa_no_urut'];
                    $datasave['no_urut']='0';
                    
                    $this->db->insert('app_poli_detail_diagnosa', $datasave);
                }else{
                   $content=array('validation'=>'Sorry,You cannot insert data. Please check your diagnosa in master data!'); 
                    $status_code = $this->status_code('412'); 
                }
                $nama_diagnosa = $key['diagnosa_nama_diagnosa'];
            }
            if (!isset($status_code) && empty($status_code)) {
                $this->db->where('reg_id',$data['no_register']);
                $this->db->set('app_update_time',time());
                $this->db->set('app_update',$this->session->userdata('username'));
                $this->db->update('app_reg');

                $id = $datasave['reg_id'];
                $options = array('reg_id' => $id,'diag_id'=>$datasave['diag_id'],'no'=>$datasave['no']);
                $query = $this->db->get_where('app_poli_detail_diagnosa',$options,1);
                if ($query->num_rows() > 0){
                    $data = $query->row_array();
                    $content = array('id'=>$data['reg_id'],'kode diagnosa'=>$data['diag_id'],'nama diagnosa'=>$nama_diagnosa,'no urut'=>$data['no'],'kode kasus'=>$data['diag_kasus'],'kode jenis'=>$data['diag_jenis']);
                    $status_code = $this->status_code('201');
                }else{
                    $query->free_result();   
                    $content=array('validation'=>'No data found.'); 
                    $status_code = $this->status_code('204'); 
                }
            }
        }
        

        $result = array('header'=>$this->header(),'content'=>$content,'status_code'=>$status_code);
        
        return $result;
    }
    function cekstokobat($data){
        foreach ($data['resep'] as $key) {
            $query = $this->db->query("SELECT SUM(jml_obat) AS jml_stok FROM obat_stok_detail WHERE id_obat='".trim($key['resep_kodeobat'])."'");
            $data=$query->row_array();
            $jml_stok=intval($data['jml_stok']);
            if($key['resep_jumlah']>$jml_stok) {
                $data =  'gagal|'.trim($key['resep_kodeobat']).'|'.$jml_stok;
                return $data;
                die();
            }
        }
        $data =  'sukses|'.trim($key['resep_kodeobat']).'|'.$jml_stok;
        return $data;
    }
    function do_action_dataResep($data=array()){
        $check = explode("|",$this->cekstokobat($data));
        if ($check[0]=='sukses') {
            $this->db->where('reg_id',$data['no_register']);
            if ($this->db->delete('app_poli_detail_resep')==FALSE) {
                $content=array('validation'=>'Delete data is failed'); 
                $status_code = $this->status_code('417'); 
            }else{
                foreach ($data['resep'] as $key) {
                    $qCekUsername = $this->db->get_where('app_poli_detail_resep', array('reg_id'=>$data['no_register'],'obat_id'=>$key['resep_kodeobat']),1);
                    if($qCekUsername->num_rows()>0){
                        $content=array('validation'=>'Diagnosa entered is already existed.'); 
                        $status_code = $this->status_code('412'); 
                    }else{
                        $sno_urut=sprintf("%04s", $key['resep_no_urut']);;
                        $datasave['id_transc']="RE".$_POST['no_register'].$sno_urut;
                        $datasave['reg_id']=$data['no_register'];
                        $datasave['obat_id']=$key['resep_kodeobat'];
                        $datasave['obat_jml']=$key['resep_jumlah'];
                        $datasave['obat_racik']=$key['resep_racikan'];
                        $datasave['obat_dosis']=$key['resep_dosis'];
                        $datasave['no']=$key['resep_no_urut'];
                        $datasave['no_urut']='0';
                        $datasave['created_date']=time();
                        $datasave['created_by']=$this->session->userdata('username');
                        $datasave['modified_by']=$this->session->userdata('username');
                        $datasave['modified_date']=time();
                        
                        
                        $cekinsert = $this->db->insert('app_poli_detail_resep', $datasave);
                    }
                    $nama_obat = $key['resep_nama_obat'];
                }
                if ($cekinsert==TRUE) {
                    $this->db->where('reg_id',$data['no_register']);
                    $this->db->set('status_apotek','1');
                    $this->db->update('app_reg');
                }
                $this->db->where('reg_id',$data['no_register']);
                $this->db->set('app_update_time',time());
                $this->db->set('app_update',$this->session->userdata('username'));
                $this->db->update('app_reg');

                $id = $datasave['reg_id'];
                $options = array('reg_id' => $id,'obat_id'=>$datasave['obat_id'],'no'=>$datasave['no']);
                $query = $this->db->get_where('app_poli_detail_resep',$options,1);
                if ($query->num_rows() > 0){
                    $data = $query->row_array();
                    $content = array('id'=>$data['reg_id'],'kode obat'=>$data['obat_id'],'nama obat'=>$nama_obat,'no urut'=>$data['no'],
                                    'jumlah obat'=>$data['obat_jml'],'dosis obat'=>$data['obat_dosis'],'Kode Racikan'=>$data['obat_racik']);
                    $status_code = $this->status_code('201');
                }else{
                    $query->free_result();   
                    $content=array('validation'=>'No data found.'); 
                    $status_code = $this->status_code('204'); 
                }
            }
        }else{
            $content=array('validation'=>"Sorry, Insert data is failed.Stok $check[1] is $check[2]"); 
            $status_code = $this->status_code('417'); 
        }
        
        $result = array('header'=>$this->header(),'content'=>$content,'status_code'=>$status_code);
        
        return $result;
    }
    function do_aaction_dataAnamnesa(){
        $this->db->where('reg_id',$this->input->post('reg_id'));
        if ($this->db->delete('app_poli_detail_anamnesa')==FALSE) {
            $content=array('validation'=>'Execution data is failed'); 
            $status_code = $this->status_code('417'); 
        }else{
            foreach($_POST as $a=>$b)
            {
                if(substr($a,0,9)=='anamnesa_')
                {
                    $c = str_replace('_input','',$a);
                    $c = substr($c,9,100);
                    $no_urut=empty($_POST['no_urut'])?0:$_POST['no_urut'];
                    $sql = "INSERT INTO app_poli_detail_anamnesa(reg_id,name,value,no_urut,created_by,created_date) 
                    VALUES('".$_POST['reg_id']."','".$c."','".$b."',".$no_urut.",'".$_SESSION['username']."','".time()."')
                    ON DUPLICATE KEY UPDATE value='".$b."',modified_by='".$_SESSION['username']."',modified_date='".time()."'
                    ";
                    //echo $sql;die;
                    mysql_query($sql);
                }
            }
            foreach ($data['resep'] as $key) {
                $qCekUsername = $this->db->get_where('app_poli_detail_resep', array('reg_id'=>$data['no_register'],'obat_id'=>$key['resep_kodeobat']),1);
                if($qCekUsername->num_rows()>0){
                    $content=array('validation'=>'Diagnosa entered is already existed.'); 
                    $status_code = $this->status_code('412'); 
                }else{
                    $sno_urut=sprintf("%04s", $key['resep_no_urut']);;
                    $datasave['id_transc']="RE".$_POST['no_register'].$sno_urut;
                    $datasave['reg_id']=$data['no_register'];
                    $datasave['obat_id']=$key['resep_kodeobat'];
                    $datasave['obat_jml']=$key['resep_jumlah'];
                    $datasave['obat_racik']=$key['resep_racikan'];
                    $datasave['obat_dosis']=$key['resep_dosis'];
                    $datasave['no']=$key['resep_no_urut'];
                    $datasave['no_urut']='0';
                    $datasave['created_date']=time();
                    $datasave['created_by']=$this->session->userdata('username');
                    $datasave['modified_by']=$this->session->userdata('username');
                    $datasave['modified_date']=time();
                    
                    
                    $this->db->insert('app_poli_detail_resep', $datasave);
                }
                $nama_obat = $key['resep_nama_obat'];
            }
            $this->db->where('reg_id',$data['no_register']);
            $this->db->set('status_apotek','1');
            $this->db->update('app_reg');

            $id = $datasave['reg_id'];
            $options = array('reg_id' => $id,'obat_id'=>$datasave['obat_id'],'no'=>$datasave['no']);
            $query = $this->db->get_where('app_poli_detail_resep',$options,1);
            if ($query->num_rows() > 0){
                $data = $query->row_array();
                $content = array('id'=>$data['reg_id'],'kode obat'=>$data['obat_id'],'nama obat'=>$nama_obat,'no urut'=>$data['no'],
                                'jumlah obat'=>$data['obat_jml'],'dosis obat'=>$data['obat_dosis'],'Kode Racikan'=>$data['obat_racik']);
                $status_code = $this->status_code('201');
            }else{
                $query->free_result();   
                $content=array('validation'=>'No data found.'); 
                $status_code = $this->status_code('204'); 
            }
        }
        $result = array('header'=>$this->header(),'content'=>$content,'status_code'=>$status_code);
        
        return $result;
    }
    function header(){
        $arr['request_time']   = $this->input->post('request_time');
        $arr['response_time']  = time();
        $arr['request_output'] = $this->input->post('request_output');
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
    function do_aaction_dataSettingBPJS(){
        $content=array();  
        $request_token  = $this->input->post('request_token');
        $client_id      = $this->input->post('client_id');
        
        $query = $this->db->get_where('mas_user_api', array('user_id'=>$client_id,'token'=>$request_token),1);
        if($query->num_rows() == 0) {
            $querygetObat=$this->db->get('bpjs_setting');
            if($querygetObat->num_rows() >0)
            {
                $datas = $querygetObat->result_array();
                foreach ($datas as $data) {
                    $content[][$data['name']]=$data['value'];
                }
                 $status_code = $this->status_code('200');
            }
            else
            {
                $content = array("validation"=>"Data yang dicari tidak diketemukan");
                $status_code = $this->status_code('204');
            }
        }else{
             $query->free_result(); 
             $content = array('validation'=>'Client ID and Token you entered is incorrect.');  
             $status_code = $this->status_code('412'); 
        }
        
        // $update['token']=$token;
        // $this->db->update('mas_user_api',$update,array('user_id' => $client_id));
        
        $result = array('header'=>$this->header(),'content'=>$content,'status_code'=>$status_code);
        return $result;
    }
}
?>