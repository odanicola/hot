<?php
class Chart_kpldh_model extends CI_Model {

    var $tabel    = 'data_keluarga';
    var $lang     = '';

    function __construct() {
        parent::__construct();
        $this->lang   = $this->config->item('language');
    }

    function get_laporan($start=0,$limit=999999,$options=array()){
            $id_judul = $this->input->post("id_judul");
            $variabel = $this->input->post("variabel");

        if ($id_judul==5 || $id_judul==8 || $id_judul==9 || $id_judul==10 || $id_judul==11 || $id_judul==12 || $id_judul==13 || $id_judul==14 || $id_judul==15 || $id_judul==16 || $id_judul==17 || $id_judul==41 || $id_judul==44 || $id_judul==45  ||$id_judul==48) {
            $this->db->select("data_keluarga.*,cl_village.value");
            $this->db->join('cl_village', "data_keluarga.id_desa = cl_village.code",'inner');
            $this->db->order_by('data_keluarga.tanggal_pengisian','asc');
            $query =$this->db->get('data_keluarga',$limit,$start);
        }elseif ($id_judul==6 || $id_judul==18 || $id_judul==19 || $id_judul==20 || $id_judul==21 || $id_judul==22 || $id_judul==23 || $id_judul==24 || $id_judul==25 || $id_judul==26  || $id_judul==27  || $id_judul==28  || $id_judul==29 || $id_judul==30|| $id_judul==38 || $id_judul==39 || $id_judul==40|| $id_judul==49) {
            $this->db->select("data_keluarga_anggota.*, hubungan.value as hubungan,jeniskelamin.value as jeniskelamin,(year(curdate())-year(data_keluarga_anggota.tgl_lahir)) as usia");
            $this->db->join('data_keluarga_anggota_profile', 'data_keluarga_anggota_profile.id_data_keluarga = data_keluarga.id_data_keluarga');
            $this->db->join('data_keluarga_anggota', 'data_keluarga_anggota.id_data_keluarga = data_keluarga_anggota_profile.id_data_keluarga AND data_keluarga_anggota.no_anggota = data_keluarga_anggota_profile.no_anggota', 'left');
            $this->db->join("mst_keluarga_pilihan hubungan","data_keluarga_anggota.id_pilihan_hubungan = hubungan.id_pilihan and hubungan.tipe='hubungan'",'left');
            $this->db->join("mst_keluarga_pilihan jeniskelamin","data_keluarga_anggota.id_pilihan_kelamin = jeniskelamin.id_pilihan and jeniskelamin.tipe ='jk'",'left');
            $query =$this->db->get('data_keluarga',$limit,$start);
        }elseif ($id_judul==42) {
            $this->db->select("data_keluarga_anggota.*, hubungan.value as hubungan,jeniskelamin.value as jeniskelamin,(year(curdate())-year(data_keluarga_anggota.tgl_lahir)) as usia");
            $this->db->join("mst_keluarga_pilihan hubungan","data_keluarga_anggota.id_pilihan_hubungan = hubungan.id_pilihan and hubungan.tipe='hubungan'",'left');
            $this->db->join("mst_keluarga_pilihan jeniskelamin","data_keluarga_anggota.id_pilihan_kelamin = jeniskelamin.id_pilihan and jeniskelamin.tipe ='jk'",'left');
            $this->db->join("data_keluarga","data_keluarga.id_data_keluarga = data_keluarga_anggota.id_data_keluarga");
            $this->db->join("cl_village village","village.code = data_keluarga.id_desa",'left');
            $query =$this->db->get('data_keluarga_anggota',$limit,$start);
        }elseif ($id_judul==31 || $id_judul==32 || $id_judul==33 || $id_judul==34 || $id_judul==35 || $id_judul==36 || $id_judul==37 || $id_judul==46 || $id_judul==47) {
            if ($variabel=="Ya" || $variabel=="Gizi Baik" || $variabel=="Gizi Kurang" || $variabel=="Gizi Lebih") {
                $this->db->select("data_keluarga_anggota.*, hubungan.value as hubungan,jeniskelamin.value as jeniskelamin,(year(curdate())-year(data_keluarga_anggota.tgl_lahir)) as usia");
                $this->db->join('data_keluarga_anggota_profile', 'data_keluarga_anggota_profile.id_data_keluarga = data_keluarga.id_data_keluarga');
                $this->db->join('data_keluarga_anggota', 'data_keluarga_anggota.id_data_keluarga = data_keluarga_anggota_profile.id_data_keluarga AND data_keluarga_anggota.no_anggota = data_keluarga_anggota_profile.no_anggota', 'left');
                $this->db->join("mst_keluarga_pilihan hubungan","data_keluarga_anggota.id_pilihan_hubungan = hubungan.id_pilihan and hubungan.tipe='hubungan'",'left');
                $this->db->join("mst_keluarga_pilihan jeniskelamin","data_keluarga_anggota.id_pilihan_kelamin = jeniskelamin.id_pilihan and jeniskelamin.tipe ='jk'",'left');
                $query =$this->db->get('data_keluarga',$limit,$start);
            }else{
                $this->db->select("data_keluarga_anggota.*, hubungan.value as hubungan,jeniskelamin.value as jeniskelamin,(year(curdate())-year(data_keluarga_anggota.tgl_lahir)) as usia");
                $this->db->join('data_keluarga', 'data_keluarga.id_data_keluarga = data_keluarga_anggota.id_data_keluarga');
                $this->db->join("mst_keluarga_pilihan hubungan","data_keluarga_anggota.id_pilihan_hubungan = hubungan.id_pilihan and hubungan.tipe='hubungan'",'left');
                $this->db->join("mst_keluarga_pilihan jeniskelamin","data_keluarga_anggota.id_pilihan_kelamin = jeniskelamin.id_pilihan and jeniskelamin.tipe ='jk'",'left');
                $query =$this->db->get('data_keluarga_anggota',$limit,$start);
            }
        }else{
            $this->db->select("data_keluarga_anggota.*, hubungan.value as hubungan,jeniskelamin.value as jeniskelamin,(year(curdate())-year(data_keluarga_anggota.tgl_lahir)) as usia,agama.value as agama,pendidikan.value as pendidikan,pekerjaan.value as pekerjaan,kawin.value as kawin,jkn.value as jkn");
            $this->db->join("mst_keluarga_pilihan hubungan","data_keluarga_anggota.id_pilihan_hubungan = hubungan.id_pilihan and hubungan.tipe='hubungan'",'left');
            $this->db->join("mst_keluarga_pilihan jeniskelamin","data_keluarga_anggota.id_pilihan_kelamin = jeniskelamin.id_pilihan and jeniskelamin.tipe ='jk'",'left');
            $this->db->join("mst_keluarga_pilihan agama","data_keluarga_anggota.id_pilihan_agama = agama.id_pilihan and agama.tipe ='agama'",'left');
            $this->db->join("mst_keluarga_pilihan pendidikan","data_keluarga_anggota.id_pilihan_pendidikan = pendidikan.id_pilihan and pendidikan.tipe= 'pendidikan'",'left');
            $this->db->join("mst_keluarga_pilihan pekerjaan","data_keluarga_anggota.id_pilihan_pekerjaan = pekerjaan.id_pilihan and pekerjaan.tipe = 'pekerjaan'" ,'left');
            $this->db->join("mst_keluarga_pilihan kawin","data_keluarga_anggota.id_pilihan_kawin = kawin.id_pilihan and kawin.tipe='kawin'",'left');
            $this->db->join("mst_keluarga_pilihan jkn","data_keluarga_anggota.id_pilihan_jkn = jkn.id_pilihan and jkn.tipe='jkn'",'left');
            $this->db->join("data_keluarga","data_keluarga.id_data_keluarga = data_keluarga_anggota.id_data_keluarga");
            $this->db->order_by('data_keluarga_anggota.no_anggota','asc');
            $query =$this->db->get("data_keluarga_anggota",$limit,$start);
        }
        
        return $query->result();
    }
    function get_mst_pendidikan(){
        $this->db->select('*');
        $this->db->where("tipe","pendidikan");
        return $this->db->get('mst_keluarga_pilihan');
    }
    function get_mst_pekerjaan(){
        $this->db->select('*');
        $this->db->where("tipe","pekerjaan");
        return $this->db->get('mst_keluarga_pilihan');
    }    
    function get_mst_jamkesehatan(){
        $this->db->select('*');
        $this->db->where("tipe","jkn");
        return $this->db->get('mst_keluarga_pilihan');
    }    
    function get_mst_kawin(){
        $this->db->select('*');
        $this->db->where("tipe","kawin");
        return $this->db->get('mst_keluarga_pilihan');
    }

    function get_village(){
        $this->db->select('*');
        return $this->db->get('cl_village');
    }
    
}