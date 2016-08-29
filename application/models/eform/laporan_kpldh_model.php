<?php
class Laporan_kpldh_model extends CI_Model {

    var $tabel    = 'data_keluarga';
    var $lang     = '';

    function __construct() {
        parent::__construct();
        $this->lang   = $this->config->item('language');
    }
    /*public function get_jum_kelamin($kecamatan=0,$kelurahan=0,$rw=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        $this->db->group_by("data_keluarga_anggota.id_pilihan_kelamin");
        $this->db->select("mst_keluarga_pilihan.value as kelamin, COUNT(data_keluarga_anggota.id_pilihan_kelamin) as jumlah,
            (SELECT COUNT(a.id_pilihan_kelamin) AS jumlah FROM  data_keluarga_anggota a where a.id_data_keluarga = data_keluarga_anggota.id_data_keluarga) AS total,id_kecamatan");
        $this->db->join("data_keluarga","data_keluarga_anggota.id_data_keluarga = data_keluarga.id_data_keluarga");
        $this->db->join("mst_keluarga_pilihan",'data_keluarga_anggota.id_pilihan_kelamin = mst_keluarga_pilihan.id_pilihan AND tipe="jk"');
        $query = $this->db->get('data_keluarga_anggota');
        return $query->result();
        
    }*/
    public function get_jum_kelamin($kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->group_by("data_keluarga_anggota.id_pilihan_kelamin");
        $this->db->select("mst_keluarga_pilihan.value as kelamin, COUNT(data_keluarga_anggota.id_pilihan_kelamin) as jumlah,
            (SELECT COUNT(a.id_pilihan_kelamin) AS jumlah FROM  data_keluarga_anggota a where a.id_data_keluarga = data_keluarga_anggota.id_data_keluarga) AS total,id_kecamatan");
        $this->db->join("data_keluarga","data_keluarga_anggota.id_data_keluarga = data_keluarga.id_data_keluarga");
        $this->db->join("mst_keluarga_pilihan",'data_keluarga_anggota.id_pilihan_kelamin = mst_keluarga_pilihan.id_pilihan AND tipe="jk"');
        $query = $this->db->get('data_keluarga_anggota');
        return $query->result();
        
    }
    public function get_nilai_infant($kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("TIMESTAMPDIFF(MONTH,tgl_lahir,CURDATE()) <=12");
        $this->db->select("data_keluarga.id_kecamatan,COUNT(data_keluarga_anggota.id_pilihan_kelamin) as jumlah,
            (SELECT COUNT(a.id_pilihan_kelamin) AS jumlah FROM  data_keluarga_anggota a WHERE a.id_data_keluarga = data_keluarga_anggota.id_data_keluarga) AS total");
         $this->db->join("data_keluarga","data_keluarga_anggota.id_data_keluarga = data_keluarga.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota');
        return $query->result();
    }
    public function get_nilai_usia($usia1=0,$usia2=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("(YEAR(CURDATE())-YEAR(tgl_lahir)) >=".'"'.$usia1.'"'."");
        $this->db->where("(YEAR(CURDATE())-YEAR(tgl_lahir)) <=".'"'.$usia2.'"'."");
        $this->db->select("data_keluarga.id_kecamatan,COUNT(data_keluarga_anggota.id_pilihan_kelamin) as jumlah,
            (SELECT COUNT(a.id_pilihan_kelamin) AS jumlah FROM  data_keluarga_anggota a WHERE a.id_data_keluarga = data_keluarga_anggota.id_data_keluarga) AS total");
         $this->db->join("data_keluarga","data_keluarga_anggota.id_data_keluarga = data_keluarga.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota');
        return $query->result();
    }
    public function get_nilai_lansia($usia=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("(YEAR(CURDATE())-YEAR(tgl_lahir)) >=".'"'.$usia.'"'."");
        $this->db->select("data_keluarga.id_kecamatan,COUNT(data_keluarga_anggota.id_pilihan_kelamin) as jumlah,
            (SELECT COUNT(a.id_pilihan_kelamin) AS jumlah FROM  data_keluarga_anggota a WHERE a.id_data_keluarga = data_keluarga_anggota.id_data_keluarga) AS total");
         $this->db->join("data_keluarga","data_keluarga_anggota.id_data_keluarga = data_keluarga.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota');
        return $query->result();
    }
    public function get_jml_pendidikan($pedidikan=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->group_by("id_pilihan_pendidikan");
        $this->db->where("id_pilihan_pendidikan =".'"'.$pedidikan.'"'."");
        $this->db->select("data_keluarga.id_kecamatan,COUNT(id_pilihan_pendidikan) AS jumlah,mst_keluarga_pilihan.value, (SELECT COUNT(a.id_pilihan_pendidikan) AS jumlah FROM data_keluarga_anggota a  WHERE a.id_data_keluarga = data_keluarga_anggota.id_data_keluarga) AS total");
         $this->db->join("data_keluarga","data_keluarga_anggota.id_data_keluarga = data_keluarga.id_data_keluarga");
         $this->db->join("mst_keluarga_pilihan",'data_keluarga_anggota.id_pilihan_pendidikan = mst_keluarga_pilihan.id_pilihan AND tipe="pendidikan"');
        $query = $this->db->get('data_keluarga_anggota');
        return $query->result();
    }
    public function get_jml_pekerjaan($pekerjaan=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->group_by("id_pilihan_pekerjaan");
        $this->db->where("id_pilihan_pekerjaan =".'"'.$pekerjaan.'"'."");
        $this->db->select("data_keluarga.id_kecamatan,COUNT(id_pilihan_pekerjaan) AS jumlah,mst_keluarga_pilihan.value, (SELECT COUNT(a.id_pilihan_pekerjaan) AS jumlah FROM data_keluarga_anggota a  WHERE a.id_data_keluarga = data_keluarga_anggota.id_data_keluarga) AS total");
         $this->db->join("data_keluarga","data_keluarga_anggota.id_data_keluarga = data_keluarga.id_data_keluarga");
         $this->db->join("mst_keluarga_pilihan",'data_keluarga_anggota.id_pilihan_pekerjaan = mst_keluarga_pilihan.id_pilihan AND tipe="pekerjaan"');
        $query = $this->db->get('data_keluarga_anggota');
        return $query->result();
    }

    function get_data_posyandu($value=7,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->group_by("VALUE");
        $this->db->where("kode","pembangunan_III_13_radio");
        $this->db->where("data_keluarga_pembangunan.value",$value);
        $this->db->select("COUNT(*) AS jml");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_pembangunan.id_data_keluarga");
        $query = $this->db->get('data_keluarga_pembangunan');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return  $key->jml;
            }

        }else{
            return 0;
        }
        
    }
    function get_data_jmlposyandu($kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode","pembangunan_III_13_radio");
        $this->db->select("COUNT(*) AS jml");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_pembangunan.id_data_keluarga");
        $query = $this->db->get('data_keluarga_pembangunan');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return  $key->jml;
            }

        }else{
            return 0;
        }
        
    }
    function get_data_disabilitas($value=7,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->group_by("VALUE");
        $this->db->where("kode","kesehatan_0_g_10_radio");
        $this->db->where("data_keluarga_anggota_profile.value",$value);
        $this->db->select("COUNT(*) AS jml");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return  $key->jml;
            }

        }else{
            return 0;
        }
        
    }
    function totaljmldisabilitas($kecamatan=0,$kelurahan=0,$rw=0,$rt=0){
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode","kesehatan_0_g_10_radio");
        $this->db->select("COUNT(*) AS jml ");
        $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jml;
            }
        }else{
            return 0;
        }

    }
    function get_data_ikutkb($value=7,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->group_by("VALUE");
        $this->db->where("kode","berencana_II_3_kb_radio");
        $this->db->where("data_keluarga_kb.value",$value);
        $this->db->select("COUNT(*) AS jml");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_kb.id_data_keluarga");
        $query = $this->db->get('data_keluarga_kb');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return  $key->jml;
            }

        }else{
            return 0;
        }
        
    }
    function totaljmlkb($kecamatan=0,$kelurahan=0,$rw=0,$rt=0){
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode","berencana_II_3_kb_radio");
        $this->db->select("COUNT(*) AS jml ");
        $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_kb.id_data_keluarga");
        $query = $this->db->get('data_keluarga_kb');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jml;
            }
        }else{
            return 0;
        }

    }
    function get_data_alasankb($kode=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
     //   $this->db->group_by("VALUE");
        $this->db->where("kode",$kode);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_kb.id_data_keluarga");
        $query = $this->db->get('data_keluarga_kb');
        if ($query->num_rows()>0) {
            return $query->result();

        }else{
            return 0;
        }
        
    }
    function get_data_kepemilikan($kode=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_pembangunan.id_data_keluarga");
        $query = $this->db->get('data_keluarga_pembangunan');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function get_data_ataprumah($kode=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_pembangunan.id_data_keluarga");
        $query = $this->db->get('data_keluarga_pembangunan');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function get_data_dindingrumah($kode=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_pembangunan.id_data_keluarga");
        $query = $this->db->get('data_keluarga_pembangunan');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function get_data_jenislantairumah($kode=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_pembangunan.id_data_keluarga");
        $query = $this->db->get('data_keluarga_pembangunan');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function get_data_jenispeneranganrumah($kode=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_pembangunan.id_data_keluarga");
        $query = $this->db->get('data_keluarga_pembangunan');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function get_data_sumberair($kode=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_pembangunan.id_data_keluarga");
        $query = $this->db->get('data_keluarga_pembangunan');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function get_data_bahanbakar($kode=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_pembangunan.id_data_keluarga");
        $query = $this->db->get('data_keluarga_pembangunan');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function get_data_fasilitasbab($kode=0,$value=7,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->where("value",$value);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_pembangunan.id_data_keluarga");
        $query = $this->db->get('data_keluarga_pembangunan');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function get_data_cucitangan($kode=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function get_data_lokasibab($kode=0,$value=7,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->where("value",$value);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
     function get_data_sikatgigi($kode=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function get_data_merokok($kode=0,$value=7,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->where("value",$value);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function totalalasankb($kecamatan=0,$kelurahan=0,$rw=0,$rt=0){
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->like("kode","berencana_II_7_berkb_");
        $this->db->select("COUNT(*) AS jml ");
        $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_kb.id_data_keluarga");
        $query = $this->db->get('xdata_keluarga_kb');
        
        if ($query->num_rows()>0) {
            return $query->result();
        }else{
            return 0;
        }

    }
    function get_data_kesehatan($kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->group_by("data_keluarga_anggota.id_pilihan_jkn");
        $this->db->select("id_kecamatan,mst_keluarga_pilihan.value as jkn, COUNT(data_keluarga_anggota.id_pilihan_jkn) as jumlah,
            (SELECT COUNT(a.id_pilihan_jkn) AS jumlah FROM  data_keluarga_anggota a where a.id_data_keluarga = data_keluarga_anggota.id_data_keluarga) AS total");
        $this->db->join("data_keluarga","data_keluarga_anggota.id_data_keluarga = data_keluarga.id_data_keluarga");
        $this->db->join("mst_keluarga_pilihan",'data_keluarga_anggota.id_pilihan_jkn = mst_keluarga_pilihan.id_pilihan AND tipe="jkn"');
        $query = $this->db->get('data_keluarga_anggota');
        return $query->result();
        
    }
    public function get_data_usiamerokok($usia1=0,$usia2=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("data_keluarga_anggota_profile.value >=",$usia1);
        $this->db->where("data_keluarga_anggota_profile.value <=",$usia2);
        $this->db->where("data_keluarga_anggota_profile.kode","kesehatan_1_g_3_text");
        $this->db->select("COUNT(VALUE) as jumlah");
        $this->db->join("data_keluarga","data_keluarga_anggota_profile.id_data_keluarga = data_keluarga.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function get_data_ginjal($kode=0,$value=7,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->where("value",$value);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function get_data_paru($kode=0,$value=7,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->where("value",$value);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function get_data_dm($kode=0,$value=7,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->where("value",$value);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
    }
    function get_data_hipertensi($kode=0,$value=7,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->where("value",$value);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
    }
     function get_data_jantung($kode=0,$value=7,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->where("value",$value);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
    } 
    function get_data_stroke($kode=0,$value=7,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->where("value",$value);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
    } 
    function get_data_kanker($kode=0,$value=7,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->where("value",$value);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
    } 
    function get_data_asma($kode=0,$value=7,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->where("value",$value);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
    } 
     function get_data_sulittidur($kode=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function get_data_mudahtakut($kode=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function get_data_berfikirjernih($kode=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function get_data_tidakbahagia($kode=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function get_data_menagis($kode=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function get_data_mengakhirihidup($kode=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function get_data_hilangminat($kode=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
    function get_data_anggotaprofile($kecamatan=0,$kelurahan=0,$rw=0,$rt=0){
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->select("COUNT(data_keluarga_anggota.id_data_keluarga) as jumlah");
        $this->db->join("data_keluarga","data_keluarga_anggota.id_data_keluarga = data_keluarga.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
    }

    public function get_data_totalusiamerokok($kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("data_keluarga_anggota_profile.kode","kesehatan_1_g_3_text");
        $this->db->select("COUNT(VALUE) as total");
        $this->db->join("data_keluarga","data_keluarga_anggota_profile.id_data_keluarga = data_keluarga.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows>0) {
            foreach ($query->result() as $key) {
                return $key->total;
            }
        }
        return 0;
        
    }
    function get_datawhere($code,$condition,$table){
        $this->db->select("*");
        $this->db->like($condition,$code);
        return $this->db->get($table)->result();
    }
    function totaljumlah($kecamatan=0,$kelurahan=0,$rw=0,$rt=0){
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->select("data_keluarga.id_kecamatan,COUNT(data_keluarga_anggota.id_data_keluarga) as totalorang");
        $this->db->join("data_keluarga","data_keluarga_anggota.id_data_keluarga = data_keluarga.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota');
        if ($query->num_rows()>0) {
            return $query->result();
        }else{
            return 0;
        }
        
    }
    function totalorang($kecamatan=0,$kelurahan=0,$rw=0,$rt=0){
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->select("data_keluarga.id_kecamatan,COUNT(data_keluarga_anggota.id_data_keluarga) as totalorang");
        $this->db->join("data_keluarga","data_keluarga_anggota.id_data_keluarga = data_keluarga.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota');
        if ($query->num_rows()>0) {
            return $query->result();
        }else{
            return 0;
        }
        
    }
    function get_nama($kolom_sl,$tabel,$kolom_wh,$kond){
       $this->db->where($kolom_wh,$kond);
        $this->db->select($kolom_sl);
        $query = $this->db->get($tabel)->result();
        foreach ($query as $key) {
            return $key->$kolom_sl;
        }
    }

    function jumlahorang($kecamatan=0,$kelurahan=0,$rw=0,$rt=0){
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->select("data_keluarga.id_kecamatan,COUNT(data_keluarga_anggota.id_data_keluarga) as totalorang");
        $this->db->join("data_keluarga","data_keluarga_anggota.id_data_keluarga = data_keluarga.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->totalorang;
            }
        }else{
            return 0;
        }
        
    }
    function get_data_radioyatidak($kode=0,$value=7,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->where("value",$value);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
    } 
    function get_data_radioKB($kode=0,$value=7,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->where("value",$value);
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_kb.id_data_keluarga");
        $query = $this->db->get('data_keluarga_kb');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
    } 
    function get_data_imunisasibalita($kode=0,$value=7,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->where("value",$value);
        $this->db->where("YEAR(CURDATE())-YEAR(data_keluarga_anggota.tgl_lahir) <= 5");
        $this->db->select("COUNT(*) AS jumlah");
        $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota_profile.id_data_keluarga");
        $this->db->join("data_keluarga_anggota","data_keluarga_anggota.id_data_keluarga = data_keluarga_anggota_profile.id_data_keluarga AND data_keluarga_anggota.no_anggota = data_keluarga_anggota_profile.no_anggota");
        $query = $this->db->get('data_keluarga_anggota_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
    } 
    public function get_jum_wanitasubur($kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        $this->db->group_by("id_desa");
        $this->db->where('id_pilihan_kelamin','6');
        $this->db->where("(YEAR(CURDATE()) - YEAR(tgl_lahir)) >= '16'");
        $this->db->where("(YEAR(CURDATE()) - YEAR(tgl_lahir)) <= '49'");
        $this->db->select("id_desa,COUNT(*) AS jumlah,cl_village.value ");
        $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota.id_data_keluarga",'left');
        $this->db->join("cl_village","cl_village.code = id_desa",'left');
        $this->db->join("mst_keluarga_pilihan",'data_keluarga_anggota.id_pilihan_kelamin = mst_keluarga_pilihan.id_pilihan AND tipe="jk"');
        $query = $this->db->get('data_keluarga_anggota');
        return $query->result();   
    }
    public function get_jum_pus($kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->group_by("id_desa");
        $this->db->select("id_desa,SUM(pus_ikutkb)+SUM(pus_tidakikutkb) AS jumlah,cl_village.value ");
        $this->db->join("cl_village","cl_village.code = id_desa",'left');
        $query = $this->db->get('data_keluarga');
        return $query->result();   
    }
    public function get_jum_hamil($kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->group_by("id_desa");
        $this->db->select("id_desa,COUNT(data_keluarga_anggota_profile.value) AS jumlah, cl_village.value");
        $this->db->join("cl_village","cl_village.code = data_keluarga.id_desa",'left');
        $this->db->join("data_keluarga_anggota_profile","data_keluarga_anggota_profile.id_data_keluarga = data_keluarga.id_data_keluarga",'left');
        $this->db->where("data_keluarga_anggota_profile.`kode` = 'kesehatan_0_g_9_radio' AND data_keluarga_anggota_profile.`value`='0'");
        $query = $this->db->get('data_keluarga');
        return $query->result();   
    }
    public function get_jum_kk($kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->group_by("id_desa");
        $this->db->select("id_desa,COUNT(*) AS jumlah,cl_village.value ");
        $this->db->join("cl_village","cl_village.code = id_desa",'left');
        $query = $this->db->get('data_keluarga');
        return $query->result();   
    }
    public function get_statusgizi($kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode","kesehatan_6_g_4_sg_text");
        $this->db->where("((`value` LIKE '%ba%' OR `value` LIKE '%cukup%' OR `value` LIKE '%nor%' OR `value` LIKE '%nir%' OR `value` LIKE '%bi%' OR `value` LIKE '%bs%'  OR `value` LIKE '%bw%'  OR `value` LIKE '%cu%'  OR `value` LIKE '%good%' OR `value` LIKE '%mal%') AND (`value` NOT LIKE '%kur%' AND `value` NOT LIKE '%under%' AND `value` NOT LIKE '%kar%' AND `value` NOT LIKE '%kut%' AND `value` NOT LIKE '%kuw%' AND `value` NOT LIKE '%run%' AND `value` NOT LIKE '%leb%' AND `value` NOT LIKE '%ver%' AND `value` NOT LIKE '%bes%' AND `value` NOT LIKE '%gem%'))");
        $this->db->join("data_keluarga","data_keluarga.id_data_keluarga = data_keluarga_anggota_profile.id_data_keluarga");
        $this->db->select("COUNT(*) as jumlah");
        $query = $this->db->get('data_keluarga_anggota_profile');
        $data['baik']   = $query->row()->jumlah;


        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode","kesehatan_6_g_4_sg_text");
        $this->db->where("((`value` LIKE '%kur%' OR `value` LIKE '%under%' OR `value` LIKE '%kar%' OR `value` LIKE '%kut%' OR `value` LIKE '%kuw%' OR `value` LIKE '%run%') AND (`value` NOT LIKE '%ba%' AND `value` NOT LIKE '%cukup%' AND `value` NOT LIKE '%nor%' AND `value` NOT LIKE '%nir%' AND `value` NOT LIKE '%bi%' AND `value` NOT LIKE '%bs%'  AND `value` NOT LIKE '%bw%'  AND `value` NOT LIKE '%cu%'  AND `value` NOT LIKE '%good%' AND `value` NOT LIKE '%mal%' AND `value` NOT LIKE '%leb%' AND `value` NOT LIKE '%ver%' AND `value` NOT LIKE '%bes%' AND `value` NOT LIKE '%gem%'))");
        $this->db->join("data_keluarga","data_keluarga.id_data_keluarga = data_keluarga_anggota_profile.id_data_keluarga");
        $this->db->select("COUNT(*) as jumlah");
        $query = $this->db->get('data_keluarga_anggota_profile');
        $data['kurang'] = $query->row()->jumlah;


        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode","kesehatan_6_g_4_sg_text");
        $this->db->where("((`value` LIKE '%leb%' OR `value` LIKE '%ver%' OR `value` LIKE '%bes%' OR `value` LIKE '%gem%') AND (`value` NOT LIKE '%ba%' AND `value` NOT LIKE '%cukup%' AND `value` NOT LIKE '%nor%' AND `value` NOT LIKE '%nir%' AND `value` NOT LIKE '%bi%' AND `value` NOT LIKE '%bs%'  AND `value` NOT LIKE '%bw%'  AND `value` NOT LIKE '%cu%'  AND `value` NOT LIKE '%good%' AND `value` NOT LIKE '%mal%' AND `value` NOT LIKE '%kur%' AND `value` NOT LIKE '%under%' AND `value` NOT LIKE '%kar%' AND `value` NOT LIKE '%kut%' AND `value` NOT LIKE '%kuw%' AND `value` NOT LIKE '%run%'))");
        $this->db->join("data_keluarga","data_keluarga.id_data_keluarga = data_keluarga_anggota_profile.id_data_keluarga");
        $this->db->select("COUNT(*) as jumlah");
        $query = $this->db->get('data_keluarga_anggota_profile');
        $data['lebih']  = $query->row()->jumlah;


        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->join("data_keluarga","data_keluarga.id_data_keluarga = data_keluarga_anggota.id_data_keluarga");
        $this->db->select("COUNT(*) as jumlah");
        $query = $this->db->get('data_keluarga_anggota');
        $data['total']  = $query->row()->jumlah;

        return $data;

    }

    public function get_jum_status_kawin($kecamatan=0,$kelurahan=0,$rw=0,$rt=0){
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->group_by("data_keluarga_anggota.id_pilihan_kawin");
        $this->db->select("mst_keluarga_pilihan.value as `status`, COUNT(data_keluarga_anggota.id_pilihan_kawin) as jumlah,
            (SELECT COUNT(a.id_pilihan_kawin) AS jumlah FROM  data_keluarga_anggota a where a.id_data_keluarga = data_keluarga_anggota.id_data_keluarga) AS total,id_kecamatan");
        $this->db->join("data_keluarga","data_keluarga_anggota.id_data_keluarga = data_keluarga.id_data_keluarga");
        $this->db->join("mst_keluarga_pilihan",'data_keluarga_anggota.id_pilihan_kawin = mst_keluarga_pilihan.id_pilihan AND tipe="kawin"');
        $query = $this->db->get('data_keluarga_anggota');
        return $query->result();
        
    }
    public function get_total_jum_kk($kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        
        $this->db->select("COUNT(*) AS jumlah");
        $query = $this->db->get('data_keluarga');
        $data = $query->row_array();   
        return $data['jumlah'];
    }
    function get_data_sumberair_profile($kode=0,$kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
    {
        if ($kecamatan!=0) {
            $this->db->where("id_kecamatan",$kecamatan);
        }
        if ($kelurahan!=0) {
            $this->db->where("id_desa",$kelurahan);
        }
        if ($rw!=0) {
            $this->db->where("rw",$rw);
        }
        if ($rt!=0) {
            $this->db->where("rt",$rt);
        }
        $this->db->where("kode",$kode);
        $this->db->where("value",'1');
        $this->db->select("COUNT(*) AS jumlah");
         $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_profile.id_data_keluarga");
        $query = $this->db->get('data_keluarga_profile');
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                return $key->jumlah;
             } 

        }else{
            return 0;
        }
        
    }
}