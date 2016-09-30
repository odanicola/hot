<?php
class Api_model extends CI_Model {
    
    function __construct(){
        parent::__construct();
        $this->load->model('bpjs');
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
            
            $this->db->where('pasien.cl_pid',$id_pasien);

            $this->db->order_by("id");
            $this->db->select("pasien.cl_pid AS id,pasien.cl_nik as nik, concat_ws(' ',cl_pname,cl_midname,cl_surname) AS nama_lengkap, pasien.cl_address AS alamat, desa.value AS desa,cl_bplace as tmp_lahir,cl_bday as tgl_lahir ,concat(cl_bplace,' , ',substr(cl_bday,7,2),'-',substr(cl_bday,5,2),'-',substr(cl_bday,1,4)) AS ttl, pasien.data_origin, desa.*,bpjs.*,(SELECT IF(status_periksa='0',reg_id,'')reg_id FROM app_reg WHERE cl_pid=pasien.cl_pid ORDER BY reg_time DESC LIMIT 1) AS reg_id,IF(pasien.cl_gender='0' OR pasien.cl_gender='2','Perempuan','Laki-laki') AS jeniskelamin,pasien.cl_mobile_no as noHp,pasien.cl_phone_no",false);
            $this->db->join('cl_village desa','pasien.cl_village=desa.code','left');
            $this->db->join('bpjs_data_pasien bpjs','bpjs.cl_pid=pasien.cl_pid','left');
            $querygetObat=$this->db->get('cl_pasien as pasien');
            if($querygetObat->num_rows() >0)
            {
                $data = $querygetObat->row_array();
                $content = array(   
                                'id'                => $data['id'],
                                'nik'               => $data['nik'],
                                'no_bpjs'           => $data['no_bpjs'],
                                'nama_lengkap'      => $data['nama_lengkap'],
                                'alamat'            => $data['alamat'],
                                'desa'              => $data['desa'],
                                'tmp_lahir'         => $data['tmp_lahir'],
                                'noHp'              => ((strlen($data['noHp']) > 9) ? $data['noHp'] : (strlen($data['cl_phone_no']) > 5) ? $data['cl_phone_no'] :  $data['noHp']),
                                'tgl_lahirda'       => $data['tgl_lahir'],
                                'tgl_lahir'         => substr($data['tgl_lahir'],0,4).'-'.substr($data['tgl_lahir'],4,2).'-'.substr($data['tgl_lahir'],6,2),
                                'jeniskelamin'      => $data['jeniskelamin'],
                                'data_origin'       => $data['data_origin'],
                                'kode_provider'     => $data['kode_provider'],
                                'nama_provider'     => $data['nama_provider'],
                                'reg_id'            => $data['reg_id'],
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
        $result['header'] = $this->header();
        $result['content'] = $content;
        $result['status_code'] = $status_code;
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
                $this->db->set('app_update',$data['pengguna']);
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
                        $datasave['created_by']=$data['pengguna'];
                        $datasave['modified_by']=$$data['pengguna'];
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
                $this->db->set('app_update',$data['pengguna']);
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
    function do_aaction_dataAnamnesa($dataanamnesa=array()){
        $this->db->where('reg_id',$this->input->post('reg_id'));
        if ($this->db->delete('app_poli_detail_anamnesa')==FALSE) {
            $content=array('validation'=>'Execution data is failed'); 
            $status_code = $this->status_code('417'); 
        }else{
            // print_r($dataanamnesa);
            // die();
            foreach ($dataanamnesa['anamnesa'] as $data) {
                foreach ($data as $key => $value) {
                    $datasave['reg_id'] =  $dataanamnesa['no_register'];
                    $datasave['name']   =  $key;
                    $datasave['value']  =  $value;
                    $datasave['no_urut']=  '0';
                    $datasave['created_date']   =  time();
                    $datasave['created_by']     =  $dataanamnesa['pengguna'];
                    $datasave['modified_date']  =  time();
                    $datasave['modified_by']    =  $dataanamnesa['pengguna'];
                    $this->db->insert('app_poli_detail_anamnesa',$datasave);
                    if ($key=='dokter_id') {
                        $kodedokter = $value;
                    }
                    if ($key=='asisten_id') {
                        $kodeasisten=$value;
                    }
                }
            }
            $this->db->where('reg_id',$dataanamnesa['no_register']);
            $this->db->set('app_update',$dataanamnesa['pengguna']);
            $this->db->set('app_update_time',time());
            $this->db->set('reg_pemeriksa',$kodedokter);
            $this->db->set('reg_pemeriksa2',$kodeasisten);
            $this->db->update('app_reg');

            
            $reg_id= $this->input->post('reg_id');
            $this->db->where('reg_id',$reg_id);
            $query = $this->db->get('app_poli_detail_anamnesa');
            if ($query->num_rows() > 0){
                $data = $query->result_array();
                $content['id'] = $reg_id;
                foreach ($data as $keypil) {
                    $content[$keypil['name']] = $keypil['value'];
                }
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
                    $content[$data['name']]=$data['value'];
                }
                 $status_code = $this->status_code('200');
            }
            else
            {
                $content['validation'] = "Data yang dicari tidak diketemukan";
                $status_code = $this->status_code('204');
            }
        }else{
             $query->free_result(); 
             $content['validation'] = 'Client ID and Token you entered is incorrect.';  
             $status_code = $this->status_code('412'); 
        }
        
        // $update['token']=$token;
        // $this->db->update('mas_user_api',$update,array('user_id' => $client_id));
        
        $result['header'] = $this->header();
        $result['content'] = $content;
        $result['status_code'] = $status_code;
        return $result;
    }
    function anamnesaByReg($id_reg='0',$name=''){
        $data = $this->db->get_where('app_poli_detail_anamnesa',array('reg_id' => $id_reg,'name' => $name))->row_array();   
        return $data['value'];
    }
    function diagnosaByReg($id_reg='0',$no_urut=''){
        $data = $this->db->get_where('app_poli_detail_diagnosa',array('reg_id' => $id_reg,'no' => $no_urut))->row_array();   
        return $data['diag_id'];
    }
    function do_get_dataBPJSDiagnosaAnamnesaResep($data=array()){
        $this->db->where('reg_id',$this->input->post('reg_id'));
        if ($this->db->delete('app_poli_detail_anamnesa')==FALSE) {
            $content=array('validation'=>'Execution anamnesa data is failed'); 
            $status_code = $this->status_code('417'); 
        }else{
            foreach ($data['anamnesa'] as $dataanam) {
                foreach ($dataanam as $keyanam => $valueanam) {

                    $datasaveanam['reg_id'] =  $data['no_register'];
                    $datasaveanam['name']   =  $keyanam;
                    $datasaveanam['value']  =  $valueanam;
                    $datasaveanam['no_urut']=  '0';
                    $datasaveanam['created_date']   =  time();
                    $datasaveanam['created_by']     =  $data['pengguna'];
                    $datasaveanam['modified_date']  =  time();
                    $datasaveanam['modified_by']    =  $data['pengguna'];

                    $cekjmlanamnesa = $this->db->get_where('app_poli_detail_anamnesa', array('reg_id'=>$data['no_register'],'name'=>$keyanam),1);
                    if ($cekjmlanamnesa->num_rows() > 0) {
                        $content=array('validation'=>'Insert anamnesa data is failed because duplicate data'); 
                        $status_code = $this->status_code('417'); 
                    }else{
                        $this->db->insert('app_poli_detail_anamnesa',$datasaveanam);
                    }
                    
                    if ($keyanam=='dokter_id') {
                        $kodedokter = $valueanam;
                    }
                    if ($keyanam=='asisten_id') {
                        $kodeasisten=$valueanam;
                    }
                }
            }

        }
        $this->db->where('reg_id',$data['no_register']);
        $this->db->set('app_update',$data['pengguna']);
        $this->db->set('app_update_time',time());
        $this->db->set('reg_pemeriksa',$kodedokter);
        $this->db->set('reg_pemeriksa2',$kodeasisten);
        if ($this->db->update('app_reg')==FALSE) {
            $content=array('validation'=>'Update docter data in app_reg is failed'); 
            $status_code = $this->status_code('417'); 
        }
        if (!isset($content['validation']) && empty($content['validation'])) {
            $this->db->where('reg_id',$data['no_register']);
            if ($this->db->delete('app_poli_detail_diagnosa')==FALSE) {
                $content=array('validation'=>'Execution diagnosa data is failed'); 
                $status_code = $this->status_code('417'); 
            }else{
                foreach ($data['diagnosa'] as $keydiag) {
                    $qCekUsername = $this->db->get_where('cl_icdx', array('trim(code)'=>$keydiag['diagnosa_no_icdx']),1);
                    if($qCekUsername->num_rows() > 0){
                        $datasavediag['reg_id']=$data['no_register'];
                        $datasavediag['diag_id']=$keydiag['diagnosa_no_icdx'];
                        $datasavediag['diag_kasus']=$keydiag['diagnosa_jenis_kasus'];
                        $datasavediag['diag_jenis']=$keydiag['diagnosa_jenis_diagnosa'];
                        $datasavediag['no']=$keydiag['diagnosa_no_urut'];
                        $datasavediag['no_urut']='0';

                        $cekjmldiagnosa = $this->db->get_where('app_poli_detail_diagnosa', array('reg_id'=>$data['no_register'],'diag_id'=>$keydiag['diagnosa_no_icdx']),1);
                        if ($cekjmldiagnosa->num_rows() > 0) {
                            $content=array('validation'=>'Insert diagnosa data is failed because duplicate data'); 
                            $status_code = $this->status_code('417'); 
                        }else{
                            $this->db->insert('app_poli_detail_diagnosa', $datasavediag);
                        }
                    }else{
                       $content=array('validation'=>'Sorry,You cannot insert data. Please check your diagnosa in master data!'); 
                        $status_code = $this->status_code('412'); 
                    }
                    $nama_diagnosa = $keydiag['diagnosa_nama_diagnosa'];
                }
            }   
            $this->db->where('reg_id',$data['no_register']);
            $this->db->set('app_update_time',time());
            $this->db->set('app_update',$data['pengguna']);
            if ($this->db->update('app_reg')==FALSE) {
                $content=array('validation'=>'Update time diagnosa data in app_reg is failed'); 
                $status_code = $this->status_code('417'); 
            }
        }
        
        if (!isset($content['validation']) && empty($content['validation'])) {
            $check = explode("|",$this->cekstokobat($data));
            // print_r($check);
            if ($check[0]=='sukses') {
                $this->db->where('reg_id',$data['no_register']);
                if ($this->db->delete('app_poli_detail_resep')==FALSE) {
                    $content=array('validation'=>'Execution Resep data is failed'); 
                    $status_code = $this->status_code('417'); 
                }else{
                    foreach ($data['resep'] as $keyres) {
                        $qCekUsername = $this->db->get_where('app_poli_detail_resep', array('reg_id'=>$data['no_register'],'obat_id'=>$keyres['resep_kodeobat']),1);
                        if($qCekUsername->num_rows()>0){
                            $content=array('validation'=>'Diagnosa entered is already existed.'); 
                            $status_code = $this->status_code('412'); 
                            $cekinsert=FALSE;
                        }else{
                            $sno_urut=sprintf("%04s", $keyres['resep_no_urut']);;
                            $datasaveres['id_transc']="RE".$data['no_register'].$sno_urut;
                            $datasaveres['reg_id']=$data['no_register'];
                            $datasaveres['obat_id']=$keyres['resep_kodeobat'];
                            $datasaveres['obat_jml']=$keyres['resep_jumlah'];
                            $datasaveres['obat_racik']=$keyres['resep_racikan'];
                            $datasaveres['obat_dosis']=$keyres['resep_dosis'];
                            $datasaveres['no']=$keyres['resep_no_urut'];
                            $datasaveres['no_urut']='0';
                            $datasaveres['created_date']=time();
                            $datasaveres['created_by']=$data['pengguna'];
                            $datasaveres['modified_by']=$data['pengguna'];
                            $datasaveres['modified_date']=time();
                            
                            $cekjmlresep = $this->db->get_where('app_poli_detail_resep', array('reg_id'=>$data['no_register'],'obat_id'=>$keyres['resep_kodeobat']),1);
                            if ($cekjmlresep->num_rows() > 0) {
                                $content=array('validation'=>'Insert Resep data is failed because duplicate data'); 
                                $status_code = $this->status_code('417'); 
                            }else{
                                $cekinsert = $this->db->insert('app_poli_detail_resep', $datasaveres);
                            }
                        }
                    }
                    if ($cekinsert==TRUE) {
                        $this->db->where('reg_id',$data['no_register']);
                        $this->db->set('status_apotek','1');
                        if ($this->db->update('app_reg')==FALSE) {
                             $content=array('validation'=>'Update status apotek data in app_reg is failed'); 
                             $status_code = $this->status_code('417'); 
                        }
                    }
                    $this->db->where('reg_id',$data['no_register']);
                    $this->db->set('app_update_time',time());
                    $this->db->set('app_update',$data['pengguna']);
                    if ($this->db->update('app_reg')==FALSE) {
                        $content=array('validation'=>'Update time and username data in app_reg is failed'); 
                        $status_code = $this->status_code('417'); 
                    }
                }
            }else{
                $content=array('validation'=>"Sorry, Insert resep data is failed.Stok $check[1] is $check[2]"); 
                $status_code = $this->status_code('417'); 
            }
        }
        $this->db->join('bpjs_data_pasien','bpjs_data_pasien.cl_pid=app_reg.cl_pid','left');
        $this->db->join('bpjs_data_kunjungan','bpjs_data_kunjungan.reg_id=app_reg.reg_id','left');
        $this->db->where('app_reg.reg_id',$data['no_register']);
        $cekPasienbpjs=$this->db->get('app_reg');
        $databpjs = $cekPasienbpjs->row_array();
        if ($cekPasienbpjs->num_rows() > 0 && isset($databpjs['bpjs_no_kunjungan']) && isset($databpjs['no_bpjs'])) {
            $dataregidanamnesa  = $this->anamnesaByReg($databpjs['reg_id'],'anamnesa');
            $dataregidkesadaran = $this->anamnesaByReg($databpjs['reg_id'],'kesadaran');
            $dataregidsistole   = $this->anamnesaByReg($databpjs['reg_id'],'sistole');
            $dataregiddiastole  = $this->anamnesaByReg($databpjs['reg_id'],'diastole');
            $dataregidberat     = $this->anamnesaByReg($databpjs['reg_id'],'berat');
            $dataregidtinggi    = $this->anamnesaByReg($databpjs['reg_id'],'tinggi');
            $dataregidnafas     = $this->anamnesaByReg($databpjs['reg_id'],'nafas');
            $dataregidnadi      = $this->anamnesaByReg($databpjs['reg_id'],'nadi');
            $dataregidterapi    = $this->anamnesaByReg($databpjs['reg_id'],'terapi');
            $dataregdiagnosa2   = $this->diagnosaByReg($databpjs['reg_id'],'2');
            $dataregdiagnosa3   = $this->diagnosaByReg($databpjs['reg_id'],'3');

            $data_kunjungan = array(
              "noKunjungan"             =>  $databpjs['bpjs_no_kunjungan'],
              "noKartu"                 =>  $databpjs['no_bpjs'],
              "tglDaftar"               =>  date("d-m-Y",strtotime($databpjs['reg_time'])),
              "keluhan"                 =>  isset($dataregidanamnesa) ? $dataregidanamnesa : '',
              "kdSadar"                 =>  isset($dataregidkesadaran) ? $dataregidkesadaran : '',
              "sistole"                 =>  isset($dataregidsistole) ? $dataregidsistole : '',
              "diastole"                =>  isset($dataregiddiastole) ? $dataregiddiastole : '',
              "beratBadan"              =>  isset($dataregidberat) ? $dataregidberat : '',
              "tinggiBadan"             =>  isset($dataregidtinggi) ? $dataregidtinggi : '',
              "respRate"                =>  isset($dataregidnafas) ? $dataregidnafas : '',
              "heartRate"               =>  isset($dataregidnadi) ? $dataregidnadi : '',
              "terapi"                  =>  isset($dataregidterapi) ? $dataregidterapi : '',
              "kdProviderRujukLanjut"   =>  '',
              "kdStatusPulang"          =>  $data['status_pulang'],
              "tglPulang"               =>  date("d-m-Y",time()),
              "kdDokter"                =>  $data['kode_dokter'],
              "kdDiag1"                 =>  $this->diagnosaByReg($databpjs['reg_id'],'1'),
              "kdDiag2"                 =>  isset($dataregdiagnosa2) ? $dataregdiagnosa2 : '',
              "kdDiag3"                 =>  isset($dataregdiagnosa3) ? $dataregdiagnosa3 : '',
              "kdPoliRujukInternal"     =>  '',
              "kdPoliRujukLanjut"       =>  ''
            ); 
            $hasilinsertbpjs = $this->bpjs->insertbpjs($data_kunjungan);
            print_r($hasilinsertbpjs);
            die();
        }
        if (!isset($status_code) && empty($status_code)) {
            $reg_id= $this->input->post('reg_id');
            $this->db->where('reg_id',$reg_id);
            $query = $this->db->get('app_poli_detail_anamnesa');
            $id = $data['no_register'];
            $optionresep = array('reg_id' => $id,'obat_id'=>$datasaveres['obat_id'],'no'=>$datasaveres['no']);
            $queryresep = $this->db->get_where('app_poli_detail_resep',$optionresep,1);

            $optiondiag = array('reg_id' => $id,'diag_id'=>$datasavediag['diag_id'],'no'=>$datasavediag['no']);
            $querydiagnosa = $this->db->get_where('app_poli_detail_diagnosa',$optiondiag,1);

            $this->db->where('reg_id',$reg_id);
            $queryanamnesa = $this->db->get('app_poli_detail_anamnesa');
            if ($queryresep->num_rows() > 0 || $querydiagnosa->num_rows() > 0 || $queryanamnesa->num_rows() > 0){
                $dataresep = $queryresep->row_array();
                $datadiagnosa = $querydiagnosa->row_array();
                $dataanam = $queryanamnesa->result_array();
                foreach ($dataanam as $keypil) {
                    $content[$keypil['name']] = $keypil['value'];
                }
                $content += array('id'=>$dataresep['reg_id'],'kode diagnosa'=>$datadiagnosa['diag_id'],'nama diagnosa'=>$nama_diagnosa,'no urut'=>$datadiagnosa['no'],'kode kasus'=>$datadiagnosa['diag_kasus'],'kode jenis'=>$datadiagnosa['diag_jenis'],'kode obat'=>$dataresep['obat_id'],'no urut'=>$dataresep['no'],'jumlah obat'=>$dataresep['obat_jml'],'dosis obat'=>$dataresep['obat_dosis'],'Kode Racikan'=>$dataresep['obat_racik']);
                    $status_code = $this->status_code('201');
            }else{
                $query->free_result();   
                $content=array('validation'=>'No data found.'); 
                $status_code = $this->status_code('204'); 
            }
        }
            
        $result = array('header'=>$this->header(),'content'=>$content,'status_code'=>$status_code);
        print_r($result);
        die();

        return $result;


        switch($data['status_pulang'])
        {
            case "4":
                $dataubah = array(  'bpjs_kode_poli_rujukan_internal'   => '',
                                    'bpjs_kode_rs_rujukan'              => $data['bpjs_provider'],
                                    'bpjs_kode_poli_rujukan'            => $data['bpjs_poli'],
                                );
                $datawhere = array( 'status_keluar'                     => '4',
                                    'reg_id'                            => $data['no_register'],
                                  );    
                $this->update('bpjs_data_kunjungan',$dataubah,$datawhere);
            break;
            
            // status 5 adalah rujuk internal
            case "5":
                $dataubah = array(  'bpjs_kode_poli_rujukan_internal'   => $data['bpjs_poli_inter'],
                                );
                $datawhere = array( 'status_keluar'                     => '3',
                                    'reg_id'                            => $data['no_register'],
                                  );    
                $this->update('bpjs_data_kunjungan',$dataubah,$datawhere);
            break;

            default :
            if($data['status_pulang']=="" ) {
                if(substr($data['no_register'],0,2)=="RJ")
                    $data['status_pulang'] = "3";
                else
                    $data['status_pulang'] = "0";
            }
            $this->db->where('reg_id',$data['no_register']);
            $this->db->set('status_keluar',$data['status_pulang']);
            $this->db->update('bpjs_data_kunjungan');
        }

        $this->db->where("reg.reg_id",$data['no_register']);
        $this->db->select("reg.reg_time,pasien.no_bpjs,kunjungan.bpjs_no_kunjungan,kunjungan.bpjs_kode_rs_rujukan as provider,bpjs_kode_poli_rujukan as poli_provider,kunjungan.status_kelua",false);
        $this->db->join('bpjs_data_kunjungan kunjungan','kunjungan.reg_id=reg.reg_id','left');
        $this->db->join('bpjs_data_pasien pasien','pasien.cl_pid=reg.cl_pid','left');
        $data_peserta   = $this->db->get('app_reg reg')->row_array();


        $this->db->where('reg_id',$data['no_register']);
        $tampilanam= $this->db->get('app_poli_detail_anamnesa')->result_array();
        foreach ($tampilanam as $datanam) {
            $data_anam[$datanam['name']] = $datanam['value'];
        }

        $this->db->where('code_mapping',$data_anam["dokter_id"]);
        $data_dokter = $this->db->get('bpjs_data_dokter')->row_array();

        $this->db->where('reg_id',$data['no_register']);
        $this->db->select("SELECT diag_id,bpjs_data_icdx.status_spesialis,bpjs_data_icdx.value");
        $this->db->join('bpjs_data_icdx','bpjs_data_icdx.code=app_poli_detail_diagnosa.diag_id','left');
        $query = $this->db->get('app_poli_detail_diagnosa',3);
        //return $sql;
        foreach ($query->result_array as $datobat)
        {
            $data_diag[] = $datobat["diag_id"];
        }

        $kdStatusPulang = $data_peserta["status_keluar"];
        $noKunjungan    = $data_peserta["bpjs_no_kunjungan"];
        $noKartu        = $data_peserta["no_bpjs"];
        $tglDaftar      = date('d-m-Y',$data_peserta["reg_time"]);
        $tglPulang      = date('d-m-Y',$data_peserta["reg_time"]);

        $keluhan        = $data_anam["anamnesa"];
        $sistole        = $data_anam["sistole"];
        $diastole       = $data_anam["diastole"];
        $beratBadan     = $data_anam["berat"];
        $tinggiBadan    = $data_anam["tinggi"];
        $respRate       = $data_anam["nafas"];
        $heartRate      = $data_anam["nadi"];
        $kdSadar        = $data_anam["kesadaran"];
        $terapi         = $data_anam["terapi"];
        if($this->func_bpjsIsDkiJakarta()) 
        {
            if(Bpjs::func_getProviderAsal($data['no_register'])!='default')
            {
                $sql_dokter = $this->db->query("SELECT kdDokter FROM bpjs_data_pendaftaran WHERE reg_id='".$data['no_register']."'");
                $data_dokternyah = $sql_dokter->row_array();
                $kdDokter       = $data_dokternyah["kdDokter"];
            }
        }
        $d=1;
        foreach($data_diag as $a)
        {
            /*limit diagnosa hanya 3*/
            if($d==1) $kdDiag1 = $a;
            if($d==2) $kdDiag2 = $a;
            if($d==3) $kdDiag3 = $a;
            $d++;
        }
        if($data["status_pulang"]=="5")
        {
        } else
        {
            $kdProviderRujukLanjut  = $data_peserta["provider"];
            $kdPoliRujukLanjut      = $data_peserta["poli_provider"];
        }

        $data_kunjungan = array(
          "noKunjungan"             =>  $noKunjungan,
          "noKartu"                 =>  $noKartu,
          "tglDaftar"               =>  $tglDaftar,
          "keluhan"                 =>  $keluhan,
          "kdSadar"                 =>  $kdSadar,
          "sistole"                 =>  $sistole,
          "diastole"                =>  $diastole,
          "beratBadan"              =>  $beratBadan,
          "tinggiBadan"             =>  $tinggiBadan,
          "respRate"                =>  $respRate,
          "heartRate"               =>  $heartRate,
          "terapi"                  =>  $terapi,
          "kdProviderRujukLanjut"   =>  $kdProviderRujukLanjut,
          "kdStatusPulang"          =>  $kdStatusPulang,
          "tglPulang"               =>  $tglPulang,
          "kdDokter"                =>  $kdDokter,
          "kdDiag1"                 =>  $kdDiag1,
          "kdDiag2"                 =>  $kdDiag2,
          "kdDiag3"                 =>  $kdDiag3,
          "kdPoliRujukInternal"     =>  $kdPoliRujukInternal,
          "kdPoliRujukLanjut"       =>  $kdPoliRujukLanjut
        ); 
        if($noKunjungan=="")
        { //tambah kunjungan, pakai post
           
            $response = $this->bpjs->inserbpjs($data_kunjungan);
            $response = json_decode($response,true);

            if($response["metaData"]["code"]=="201")
            {
                /*tambahkan status rujukan*/
                if($data_peserta["provider"]<>"") $status_rujukan = ",status_rujuk='1' ";
                
                /*masukkan ke table*/
                $sql = "UPDATE bpjs_data_kunjungan SET bpjs_no_kunjungan='".$response["response"]["message"]."'".$status_rujukan." WHERE reg_id='".$reg_id."';";
                mysql_query($sql);
                
                /*masukkan data rujukan*/
                if($kdStatusPulang == "4")
                {
                    $sql = "UPDATE bpjs_data_kunjungan SET bpjs_kode_rs_rujukan='".$data_peserta["provider"]."',bpjs_kode_poli_rujukan='".$data_peserta["poli_provider"]."' WHERE reg_id='".$reg_id."'";
                    mysql_query($sql);
                }
                //update 24 maret 2016 //bug dalam menyimpan status pulang
                else
                {
                    $sql = "UPDATE bpjs_data_kunjungan SET bpjs_kode_rs_rujukan='',bpjs_kode_poli_rujukan='',status_keluar='".$kdStatusPulang."',status_rujuk='0' WHERE reg_id='".$reg_id."'";
                    mysql_query($sql);
                }
                
                /*masukkan ke tabel diagnosa*/
                foreach($data_diag as $a)
                {
                    mysql_query("INSERT IGNORE INTO bpjs_kunjungan_detail_diagnosa(bpjs_no_kunjungan,id_diagnosa_bpjs,id_diagnosa_mapping,status_nonSpesialis,created_date) VALUES('".$response["response"]["message"]."','".$a."','".$a."','0','".time()."')");
                }
                
                $result = "<table>
                    <tr><td colspan=2 align=center><b>Sinkronisasi data ke BPJS berhasil</b></td></tr>
                    <tr>
                        <td><image src='images/show_success.gif'></td>
                        <td>Data kunjungan berhasil ditambahkan. <br>Nomor kunjungan ".$response["response"]["message"]."</td>
                    </tr>
                </table>";
                $result_res = "ok";
                
                /*tambahan untuk TACC
                 masukkan data ke table bpjs_data_kunjungan 
                 */
                 if(isset($_POST['taccKode']) && isset($_POST['bypassVersion']))
                 {
                    $sql_tacc = "UPDATE bpjs_data_kunjungan SET kdTacc='".$_POST['taccKode']."',alasanTacc='".$_POST['taccAlasan']."' WHERE reg_id='".$reg_id."'";
                    mysql_query($sql_tacc);
                }
                 
            }elseif($response["metaData"]["code"]=="304")
            {
                $result = "<table>
                    <tr><td colspan=2 align=center><b>Sinkronisasi data ke BPJS gagal</b></td></tr>
                    <tr>
                        <td><image src='images/show_error.gif'></td>
                        <td>Data kunjungan gagal ditambahkan. <br>Reason : Data kunjungan poli telah dientri sebelumnya<br>Mungkin telah dientri di aplikasi Pcare<br>Apakah anda ingin mengupdate data di Pcare dengan data baru hasil pemeriksaan di epuskesmas?
                        <br>
                        <input type=button class=\"btn-green\" value=\"Ya, Update Data\" id='bpjs_button_update_kunjungan' onclick=\"getRiwayatKunjungan()\">
                        
                        </td>
                    </tr>
                </table>";
                $result_res = "err";
            }
            else
            {
                
                /*untuk TACC*/
                $tacc = false;
                foreach($response["response"] as $a=>$b)
                {
                    if (strpos($b["message"], 'Diagnosa ini merupakan diagnosa Non-Spesial') !== false)
                    {
                        $tacc = true;
                        break;
                    }
                }
                
                if($tacc)
                {
                    $result = "<script>
                        function sendRujukTacc()
                        {
                            $('#popup_windowMonitoring').show();
                            $('#popup_contentMonitoring').html(\"<img src='images/loading.gif'>\"); 
                            $.post('index.php?file=".$_GET['file']."&act=dosendkunjungan&id_reg=".$reg_id."&out=".$out."',{
                                taccKode:$('#bpjsRujukTaccKode').val(),
                                taccAlasan:$('#bpjsRujukTaccAlasan').val(),
                                bypassVersion:'v2',
                                status_pulang   : $('#bpjs_select_status_pulang').val(),
                                bpjs_provider   : $('#bpjs_provider').val(),
                                bpjs_poli       : $('#bpjs_poli').val(),
                                bpjs_poli_inter : $('#bpjs_poli_internal').val(),
                                status_diag_non_spesialis : $('#status_diag_non_spesialis').val()
                            },function(hasil){
                                var json=eval(\"(\"+hasil+\")\");
                                var content = json.html;
                                $('#popup_contentMonitoring').html(content);
                            });
                            
                        }
                    </script><table>
                        <tr><td colspan=2 align=center><b>Sinkronisasi data ke BPJS gagal</b></td></tr>
                        <tr>
                            <td valign=top><image src='images/show_warning.gif'></td>
                            <td>Pengiriman data kunjungan ke BPJS gagal karena diagnosa merupakan<br>diagnosa non spesialis.<br>
                            Silakan isi alasan TACC kemudian kirim ulang kunjungan.<br><br>
                            <table>
                                <tr>
                                    <td>Alasan</td>
                                    <td>:</td>
                                    <td><select class=combo-box id=bpjsRujukTaccKode>
                                        <option value='1'>Time</option>
                                        <option value='2'>Age</option>
                                        <option value='3'>Complication</option>
                                        <option value='4'>COmorbidity</option>
                                    </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Catatan</td>
                                    <td>:</td>
                                    <td>
                                        <textarea class=text-field cols=30 rows=2 id=bpjsRujukTaccAlasan></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan=3><br>
                                        <input type=button class=btn-green value='Kirim Rujukan TACC' onclick=sendRujukTacc()>
                                    </td>
                                </tr>
                            </table>
                            </td>
                        </tr>
                    </table>";
                }
                else
                {
                    $result = "<table>
                        <tr><td colspan=2 align=center><b>Sinkronisasi data ke BPJS gagal</b></td></tr>
                        <tr>
                            <td><image src='images/show_error.gif'></td>
                            <td>Data kunjungan gagal ditambahkan. <br>Reason ".$response["metaData"]["message"]."<br>Silakan periksa kembali field-field berikut :";
                    foreach($response["response"] as $a=>$b)
                    {
                        $result .= "<br>".$b["field"]."=>".$b["message"];
                    }
                        $result .= "    </td>
                        </tr>
                    </table>";
                }
                /*hapus data rujukan*/
                if($kdStatusPulang == "4")
                {
                    $sql = "UPDATE bpjs_data_kunjungan SET bpjs_kode_rs_rujukan='',bpjs_kode_poli_rujukan='',status_keluar='0',status_rujuk='0' WHERE reg_id='".$reg_id."'";
                    mysql_query($sql);
                }
                $result_res = "err";
            }
        }
        else
        { //edit kunjungan, pakai put
            
            try
            {
                $response = \Httpful\Request::put(Bpjs::func_bpjsrestheader('server').'/'.$versiApi.'/kunjungan')
                ->xConsId(Bpjs::func_bpjsrestheader('consid',Bpjs::func_getProviderAsal($reg_id)))
                ->xTimestamp(Bpjs::func_bpjsRestData("xtime",Bpjs::func_getProviderAsal($reg_id)))
                ->xSignature(Bpjs::func_bpjsRestData("xsign",Bpjs::func_getProviderAsal($reg_id)))
                ->xAuthorization("Basic ".Bpjs::func_bpjsRestData("xauth",Bpjs::func_getProviderAsal($reg_id)))
                ->body($data_kunjungan)
                ->sendsJson()
                ->timeout(90)
                ->send();
            }
            catch(Exception $E)
            {
                $reflector = new \ReflectionClass($E);
                $classProperty = $reflector->getProperty('message');
                $classProperty->setAccessible(true);
                $data = $classProperty->getValue($E);
                $result = "<table><tr><td><image src='images/show_error.gif'></td><td>Gagal mengirim data ke server BPJS.<br>".$data."</td></tr></table>";
                if($out=="json") $result = json_encode(array("res"=>"err","html"=>$result));
                return $result;
            }
            
            //return Bpjs::func_bpjsrestheader('server').'/'.$versiApi.'/kunjungan'.$response; 
            //echo $response; die;
            $response = json_decode($response,true);
            if($response["metaData"]["code"]=="200")
            {
                /*jika no_kunjungan_update diset*/
                /*masukkan ke table*/
                if($no_kunjungan_update!=="")
                $sql = "UPDATE bpjs_data_kunjungan SET bpjs_no_kunjungan='".$no_kunjungan_update."' WHERE reg_id='".$reg_id."';";
                mysql_query($sql);
                
                
                /*tambahkan status rujukan*/
                $sql = "UPDATE bpjs_data_kunjungan SET status_rujuk='1' WHERE reg_id='".$reg_id."';";
                mysql_query($sql);
                
                /*masukkan data rujukan*/
                if($kdStatusPulang == "4")
                {
                    $sql = "UPDATE bpjs_data_kunjungan SET bpjs_kode_rs_rujukan='".$data_peserta["provider"]."',bpjs_kode_poli_rujukan='".$data_peserta["poli_provider"]."' WHERE reg_id='".$reg_id."'";
                    mysql_query($sql);
                }else
                {
                    $sql = "UPDATE bpjs_data_kunjungan SET bpjs_kode_rs_rujukan='',bpjs_kode_poli_rujukan='',status_keluar='".$kdStatusPulang."',status_rujuk='0' WHERE reg_id='".$reg_id."'";
                    mysql_query($sql);
                }
                
                /*update tabel diagnosa*/
                mysql_query("DELETE FROM bpjs_kunjungan_detail_diagnosa WHERE bpjs_no_kunjungan='".$noKunjungan."'");
                foreach($data_diag as $a)
                {
                    mysql_query("INSERT IGNORE INTO bpjs_kunjungan_detail_diagnosa(bpjs_no_kunjungan,id_diagnosa_bpjs,id_diagnosa_mapping,status_nonSpesialis,created_date) VALUES('".$noKunjungan."','".$a."','".$a."','0','".time()."')");
                }
                
                $result = "<table>
                    <tr><td colspan=2 align=center><b>Sinkronisasi data ke BPJS berhasil</b></td></tr>
                    <tr>
                        <td><image src='images/show_success.gif'></td>
                        <td>Data kunjungan berhasil diubah. <br>Nomor kunjungan ".$noKunjungan."</td>
                    </tr>
                </table>";
                
                /*tambahan untuk TACC
                 masukkan data ke table bpjs_data_kunjungan 
                 */
                 if(isset($_POST['taccKode']) && isset($_POST['bypassVersion']))
                 {
                    $sql_tacc = "UPDATE bpjs_data_kunjungan SET kdTacc='".$_POST['taccKode']."',alasanTacc='".$_POST['taccAlasan']."' WHERE reg_id='".$reg_id."'";
                    mysql_query($sql_tacc);
                }
                 
                $data = array("no_kunjungan"=>$noKunjungan);
                $result_res = "ok";
            }
            else
            {
                
                /*untuk TACC*/
                $tacc = false;
                foreach($response["response"] as $a=>$b)
                {
                    //if (strpos($b["message"], 'Diagnosa ini merupakan diagnosa Non-Spesial') !== false)
                    if (strpos($b["message"], 'Diagnosa ini merupakan diagnosa Non-Spesial') !== false)
                    //if (strpos($b["message"], 'Tidak sesuai dengan') !== false)
                    {
                        $tacc = true;
                        break;
                    }
                }
                
                if($tacc)
                {
                    $result = "<script>
                        function sendRujukTacc()
                        {
                            var tacckode     = $('#bpjsRujukTaccKode').val();
                            var taccalasan   = $('#bpjsRujukTaccAlasan').val();
                            $('#popup_windowMonitoring').show();
                            $('#popup_contentMonitoring').html(\"<img src='images/loading.gif'>\"); 
                            $.post('index.php?file=".$_GET['file']."&act=dosendkunjungan&id_reg=".$reg_id."&out=".$out."',{
                                taccKode:tacckode,
                                taccAlasan:taccalasan,
                                bypassVersion:'v2',
                                status_pulang   : $('#bpjs_select_status_pulang').val(),
                                bpjs_provider   : $('#bpjs_provider').val(),
                                bpjs_poli       : $('#bpjs_poli').val(),
                                bpjs_poli_inter : $('#bpjs_poli_internal').val(),
                                status_diag_non_spesialis : $('#status_diag_non_spesialis').val()
                            },function(hasil){
                                var json=eval(\"(\"+hasil+\")\");
                                var content = json.html;
                                $('#popup_contentMonitoring').html(content);
                            });
                            
                        }
                    </script><table>
                        <tr><td colspan=2 align=center><b>Sinkronisasi data ke BPJS gagal</b></td></tr>
                        <tr>
                            <td valign=top><image src='images/show_warning.gif'></td>
                            <td>Pengiriman data kunjungan ke BPJS gagal karena diagnosa merupakan<br>diagnosa non spesialis.<br>
                            Silakan isi alasan TACC kemudian kirim ulang kunjungan.<br><br>
                            <table>
                                <tr>
                                    <td>Alasan</td>
                                    <td>:</td>
                                    <td><select class=combo-box id=bpjsRujukTaccKode>
                                        <option value='1'>Time</option>
                                        <option value='2'>Age</option>
                                        <option value='3'>Complication</option>
                                        <option value='4'>COmorbidity</option>
                                    </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Catatan</td>
                                    <td>:</td>
                                    <td>
                                        <textarea class=text-field cols=30 rows=2 id=bpjsRujukTaccAlasan></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan=3><br>
                                        <input type=button class=btn-green value='Kirim Rujukan TACC' onclick=sendRujukTacc()>
                                    </td>
                                </tr>
                            </table>
                            </td>
                        </tr>
                    </table>";
                }
                else
                {
                    $result = "<table>
                        <tr><td colspan=2 align=center><b>Sinkronisasi data ke BPJS gagal</b></td></tr>
                        <tr>
                            <td><image src='images/show_error.gif'></td>
                            <td>Data kunjungan gagal diubah. <br>Reason ".$response["metaData"]["message"]."<br>Silakan periksa kembali field-field berikut :";
                            foreach($response["response"] as $a=>$b)
                            {
                                $result .= "<br>".$b["field"]."=>".$b["message"];
                            }
                                $result .= "    </td>
                        </tr>
                    </table>";
                }
                
                /*hapus data rujukan*/
                if($kdStatusPulang == "4")
                {
                    $sql = "UPDATE bpjs_data_kunjungan SET bpjs_kode_rs_rujukan='',bpjs_kode_poli_rujukan='',status_keluar='".$kdStatusPulang."',status_rujuk='0' WHERE reg_id='".$reg_id."'";
                    mysql_query($sql);
                }
                $result_res = "err";
            }
        }
        if($out=="json")
        {
            $result = json_encode(array("res"=>$result_res,"html"=>$result,"data"=>$data,"kode_metadata"=>$response["metaData"]["code"]));
        } 
        return $result;
    }
    function func_bpjsIsDkiJakarta()
    {
        $sql = $this->db->query("SELECT value FROM bpjs_setting WHERE name='versi dki'");
        $data = $sql->row_array();
        if($data['value']=="on") return true; else return false;
    }
    function func_getProviderAsal($reg_id)
    {
        $sql = $this->db->query("SELECT provider_kirim FROM bpjs_data_pendaftaran WHERE reg_id='".$reg_id."'");
        $data = $sql->row_array();
        if($data["provider_kirim"]=="") 
        {
            $this->db->where('reg_id',$reg_id);
            $this->db->set('provider_kirim',$this->func_getSelfProvider());
            $this->db->update('bpjs_data_pendaftaran');
            return "default";
        }
        
        if($data["provider_kirim"]==$this->func_getSelfProvider()) 
        return "default"; //berarti provider kirimnya adalah puskesmas bersangkutan
        else 
        return $data["provider_kirim"];
    }

    function func_getSelfProvider()
    {
        $sql = $this->db->query("SELECT 
        (SELECT value FROM bpjs_setting WHERE name='bpjs_username') as username
        ");
        $data = $sql->row_array();
        return $data["username"];
    }
}
?>