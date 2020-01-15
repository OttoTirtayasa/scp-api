<?php
  // API for retrieve Paket
  use Restserver\Libraries\REST_Controller;
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  defined('BASEPATH') OR exit('No direct script access allowed');
  require APPPATH . 'libraries/REST_Controller.php';
  require APPPATH . 'libraries/Format.php';

  class Paket extends REST_Controller {
    
    public function __construct(){
      parent::__construct();
      $this->load->model('Paket_model', 'paket_model');
    }

    public function index_get(){
      $paket_id = $this->get('pa_id');
      
      if($paket_id == null){
        $paket = $this->paket_model->get_paket();
      }else{
        $paket = $this->paket_model->get_paket($paket_id);
      }

      if($paket){
          $this->response([
            'status' => true,
            'data' => $paket
        ], REST_Controller::HTTP_OK);
      }else{
          $this->response([
            'status' => false,
            'message' => 'Paket Not Found'
        ], REST_Controller::HTTP_BAD_REQUEST);
      }
    }

    public function index_post(){
      $ke_id = $this->post('ke_id');
      $pptk_id = $this->post('pptk_id');
      $pa_judul = $this->post('pa_judul');
      $pa_jenis = $this->post('pa_jenis');
      $pa_norekening = $this->post('pa_norekening');
      $pa_volume = $this->post('pa_volume');
      $pa_satuan = $this->post('pa_satuan');
      $pa_tahun = $this->post('pa_tahun');
      $pa_pagu = $this->post('pa_pagu');

      $data = array(
          "ke_id" => $ke_id,
          "pptk_id" => $pptk_id,
          "pa_judul" => $pa_judul,
          "pa_jenis" => $pa_jenis,
          "pa_volume" => $pa_volume,
          "pa_satuan" => $pa_satuan,
          "pa_tahun" => $pa_tahun,
          "pa_pagu" => $pa_pagu
      );

      $this->db->insert('sc_paket', $data);
      if($this->db->affected_rows() == 1 ){
          $this->response([
            'status' => true,
            'data' => $data
        ], REST_Controller::HTTP_CREATED);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Input Paket Failed'
        ], REST_Controller::HTTP_BAD_REQUEST);
      }
    }

    public function index_delete(){
      $id = $this->delete('pa_id');
      if($id == null){
        $this->response([
            'status' => false,
            'message' => 'Provide an paket id!'
        ], REST_Controller::HTTP_NOT_FOUND);
      }else{
        if($this->paket_model->deletePaket($id) > 0){
            // ok
            $this->response([
              'status' => true,
              'paket_id' => $paket_id,
              'message' => 'Deleted'
          ], REST_Controller::HTTP_OK);
        }else{
           // not ok id not found
           $this->response([
              'status' => false,
              'message' => 'Paket Id not found!'
          ], REST_Controller::HTTP_NOT_FOUND);
        }
      }
    }

    public function kegiatan_get(){
      $kegiatan_id = $this->get('ke_id');
      $paket = $this->paket_model->get_paket_kegiatan($kegiatan_id);
      if($paket){
        $this->response([
            'status' => true,
            'data' => $paket
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Paket which dinas_id is not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }

    } 

    public function join_get(){
      $this->db->select('a.pa_id, 
                         a.pptk_id,
                         a.pa_judul,
                         a.pa_jenis, 
                         a.pa_volume,
                         a.pa_satuan, 
                         a.pa_lokasi, 
                         a.pa_loc_latitude, 
                         a.pa_tahun, 
                         a.pa_pagu, 
                         a.pa_nomor_kontrak,
                         a.pa_loc_longitude,
                         b.ke_id, 
                         b.ke_judul,
                         b.ke_norekening,
                         b.ke_tahun,
                         b.status,
                         b.dinas_id, b.bi_id');
      $this->db->from('sc_paket a');
      $this->db->join('sc_kegiatan b', 'a.ke_id = b.ke_id');
      $this->db->where('b.status', '0');
      $query = $this->db->get()->result_array();
      
      if($query){
        $this->response([
          'status' => true,
          'data' => $query
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Paket Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }


    public function pptk_get(){
      $pptk_id = $this->get('pptk_id');
      $paket = $this->paket_model->get_paket_pptkuser($pptk_id);
      if($paket){
        $this->response([
            'status' => true,
            'data' => $paket
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Paket not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function bidang_get(){
        $bi_id = $this->get('bi_id');
        $dinas_id = $this->get('dinas_id');
        if($bi_id && $dinas_id){
            $query = "SELECT a.* 
                      FROM sc_paket a, sc_kegiatan b, sc_bidang c 
                      WHERE c.bi_id = b.bi_id AND a.ke_id = b.ke_id AND c.bi_id = $bi_id AND b.dinas_id = $dinas_id";
            $q_result = $this->db->query($query)->result_array();
            if($q_result){
              $this->response([
                'status' => true,
                'data' => $q_result
              ], REST_Controller::HTTP_OK);
            }else{
              $this->response([
                'status' => false,
                'message' => 'Paket not found'
              ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function bidangpaket_get(){
      $bi_id = $this->get('bi_id');
      $query = "SELECT a.* FROM sc_paket a, sc_kegiatan b, sc_bidang c WHERE c.bi_id = b.bi_id AND a.ke_id = b.ke_id AND c.bi_id = $bi_id";
      $q_result = $this->db->query($query)->result_array();
      if($q_result){
        $this->response([
          'status' => true,
          'data' => $q_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Paket not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    // daftar paket berdasarkan dinas
    public function dinas_get(){
      $dinas_id = $this->get('dinas_id');
      $query="SELECT c.* FROM dinas a, sc_kegiatan b, sc_paket c
              WHERE 
              a.dinas_id = b.dinas_id and
              b.ke_id = c.ke_id and
              a.dinas_id = $dinas_id ORDER BY c.pa_judul";
      $q_result = $this->db->query($query)->result_array();
      if($q_result){
        $this->response([
          'status' => true,
          'data' => $q_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Paket not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function paketall_get(){
      $dinas_id = $this->get('dinas_id');

      // paket all
      $query1 = "SELECT a.*
                FROM
                sc_paket a,
                sc_kegiatan b,
                dinas c
                WHERE
                a.ke_id = b.ke_id AND
                b.dinas_id = c.dinas_id AND
                c.dinas_id = $dinas_id";
      $q_result1 = $this->db->query($query1)->result();

      //paket belum mulai
      $query2 = "SELECT a.* 
                FROM 
                sc_paket a,
                sc_kegiatan c,
                dinas d
                WHERE
                d.dinas_id = c.dinas_id AND
                c.ke_id = a.ke_id AND
                d.dinas_id = $dinas_id AND
                a.pa_awal_kontrak IS NULL AND
                a.status = 0";
      $q_result2 = $this->db->query($query2)->result();

      //paket sudah selesai
      $query3 = "SELECT b.*
                FROM 
                sc_paket a,
                sc_progress b,
                sc_kegiatan c,
                dinas d
                WHERE
                d.dinas_id = c.dinas_id AND
                c.ke_id = a.ke_id AND 
                d.dinas_id = $dinas_id AND
                a.pa_id = b.pa_id AND
                a.pa_awal_kontrak IS NOT NULL AND 
                a.pa_nilai_kontrak IS NOT NULL AND
                b.pr_real = 100";
      $q_result3 = $this->db->query($query3)->result();

      //SD = 5, PSQ3 = 2, PBMQ2 = 2 Q4= 4 Q = 7-4 3 
      //paket sedang berjalan
      $query4 = "SELECT a.* 
                FROM 
                sc_paket a,
                sc_kegiatan c,
                dinas d
                WHERE
                d.dinas_id = c.dinas_id AND
                c.ke_id = a.ke_id AND
                d.dinas_id = $dinas_id AND
                a.pa_awal_kontrak IS NOT NULL AND
                a.pa_nilai_kontrak IS NOT NULL AND
                CURDATE()  > cast(a.pa_awal_kontrak as DATE) AND
                a.status = 0";
      $q_result4 = $this->db->query($query4)->result();

      $array = array(
          "paket_all" => $q_result1,
          "paket_belum_mulai" => $q_result2,
          "paket_selesai" => $q_result3,
          "paket_progress" => $q_result4
        );

      $array_push = array($array);
        if($array ){
          $this->response([
              'status' => true,
              'data' => $array_push
          ], REST_Controller::HTTP_OK);
        }else{
          $this->response([
            'status' => false,
            'message' => 'Data Not Found'
          ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function paketallpptk_get(){
      $dinas_id = $this->get('dinas_id');
      $pptk_id = $this->get('pptk_id');

      // paket all
      $query1 = "SELECT a.*
                FROM
                sc_paket a,
                sc_kegiatan b,
                dinas c
                WHERE
                a.ke_id = b.ke_id AND
                b.dinas_id = c.dinas_id AND
                a.pptk_id = $pptk_id AND
                c.dinas_id = $dinas_id";
      $q_result1 = $this->db->query($query1)->result();

      //paket belum mulai
      $query2 = "SELECT a.* 
                FROM 
                sc_paket a,
                sc_kegiatan c,
                dinas d
                WHERE
                d.dinas_id = c.dinas_id AND
                c.ke_id = a.ke_id AND
                d.dinas_id = $dinas_id AND
                a.pptk_id = $pptk_id AND
                a.pa_awal_kontrak IS NULL AND
                a.status = 0";
      $q_result2 = $this->db->query($query2)->result();

      //paket sudah selesai
      $query3 = "SELECT b.*
                FROM 
                sc_paket a,
                sc_progress b,
                sc_kegiatan c,
                dinas d
                WHERE
                d.dinas_id = c.dinas_id AND
                c.ke_id = a.ke_id AND 
                d.dinas_id = $dinas_id AND
                a.pptk_id = $pptk_id AND
                a.pa_id = b.pa_id AND
                a.pa_awal_kontrak IS NOT NULL AND 
                a.pa_nilai_kontrak IS NOT NULL AND
                b.pr_real = 100";
      $q_result3 = $this->db->query($query3)->result();

      //SD = 5, PSQ3 = 2, PBMQ2 = 2 Q4= 4 Q = 7-4 3 
      //paket sedang berjalan
      $query4 = "SELECT a.* 
                FROM 
                sc_paket a,
                sc_kegiatan c,
                dinas d
                WHERE
                d.dinas_id = c.dinas_id AND
                c.ke_id = a.ke_id AND
                d.dinas_id = $dinas_id AND
                a.pptk_id = $pptk_id AND
                a.pa_awal_kontrak IS NOT NULL AND
                a.pa_nilai_kontrak IS NOT NULL AND
                CURDATE()  > cast(a.pa_awal_kontrak as DATE) AND
                a.status = 0";
      $q_result4 = $this->db->query($query4)->result();

      $array = array(
          "paket_all" => $q_result1,
          "paket_belum_mulai" => $q_result2,
          "paket_selesai" => $q_result3,
          "paket_progress" => $q_result4
        );

      $array_push = array($array);
        if($array ){
          $this->response([
              'status' => true,
              'data' => $array_push
          ], REST_Controller::HTTP_OK);
        }else{
          $this->response([
            'status' => false,
            'message' => 'Data Not Found'
          ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function paketallbidang_get(){
      $dinas_id = $this->get('dinas_id');
      $bi_id = $this->get('bi_id');

      // paket all
      $query1 = "SELECT a.*
                FROM
                sc_paket a,
                sc_kegiatan b,
                dinas c
                WHERE
                a.ke_id = b.ke_id AND
                b.dinas_id = c.dinas_id AND
                b.bi_id = $bi_id AND
                c.dinas_id = $dinas_id";
      $q_result1 = $this->db->query($query1)->result();

      //paket belum mulai
      $query2 = "SELECT a.* 
                FROM 
                sc_paket a,
                sc_kegiatan c,
                dinas d
                WHERE
                d.dinas_id = c.dinas_id AND
                c.ke_id = a.ke_id AND
                d.dinas_id = $dinas_id AND
                c.bi_id = $bi_id AND
                a.pa_awal_kontrak IS NULL AND
                a.status = 0";
      $q_result2 = $this->db->query($query2)->result();

      //paket sudah selesai
      $query3 = "SELECT b.*
                FROM 
                sc_paket a,
                sc_progress b,
                sc_kegiatan c,
                dinas d
                WHERE
                d.dinas_id = c.dinas_id AND
                c.ke_id = a.ke_id AND 
                d.dinas_id = $dinas_id AND
                c.bi_id = $bi_id AND
                a.pa_id = b.pa_id AND
                a.pa_awal_kontrak IS NOT NULL AND 
                a.pa_nilai_kontrak IS NOT NULL AND
                b.pr_real = 100";
      $q_result3 = $this->db->query($query3)->result();

      //SD = 5, PSQ3 = 2, PBMQ2 = 2 Q4= 4 Q = 7-4 3 
      //paket sedang berjalan
      $query4 = "SELECT a.* 
                FROM 
                sc_paket a,
                sc_kegiatan c,
                dinas d
                WHERE
                d.dinas_id = c.dinas_id AND
                c.ke_id = a.ke_id AND
                d.dinas_id = $dinas_id AND
                c.bi_id = $bi_id AND
                a.pa_awal_kontrak IS NOT NULL AND
                a.pa_nilai_kontrak IS NOT NULL AND
                CURDATE()  > cast(a.pa_awal_kontrak as DATE) AND
                a.status = 0";
      $q_result4 = $this->db->query($query4)->result();

      $array = array(
          "paket_all" => $q_result1,
          "paket_belum_mulai" => $q_result2,
          "paket_selesai" => $q_result3,
          "paket_progress" => $q_result4
        );

      $array_push = array($array);
        if($array ){
          $this->response([
              'status' => true,
              'data' => $array_push
          ], REST_Controller::HTTP_OK);
        }else{
          $this->response([
            'status' => false,
            'message' => 'Data Not Found'
          ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function paketbs_get(){
      $dinas_id = $this->get('dinas_id');

      // paket all
      $query1 = "SELECT a.* paket_all
                FROM
                sc_paket a,
                sc_kegiatan b,
                dinas c
                WHERE
                a.ke_id = b.ke_id AND
                b.dinas_id = c.dinas_id AND
                c.dinas_id = $dinas_id";
      $q_result1 = $this->db->query($query1)->result()[0]->paket_all;
      if($q_result){
        $this->response([
          'status' => true,
          'data' => $q_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Paket not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function mapprogmax_get(){
      $dinas_id = $this->get('dinas_id');
      $query = "SELECT a.pa_id, MAX(CONVERT(b.pr_real, double)) data, b.pr_tanggal, a.*, b.pr_real, b.pr_tanggal, b.pr_deviasi, b.pr_target
                FROM
                sc_paket a LEFT JOIN sc_progress b ON a.pa_id = b.pa_id AND b.pr_jenis = 'Fisik', 
                sc_kegiatan c,
                dinas d
                WHERE
                d.dinas_id = $dinas_id AND
                c.ke_id = a.ke_id AND
                d.dinas_id = c.dinas_id
                GROUP BY  
                a.pa_id ORDER BY data";
      $q_result = $this->db->query($query)->result_array();
      if($q_result){
        $this->response([
          'status' => true,
          'data' => $q_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Paket not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function mapprogmaxpptk_get(){
      $pptk_id = $this->get('pptk_id');
      $query = "SELECT a.pa_id, MAX(CONVERT(b.pr_real, double)) as data, 
                b.pr_tanggal, a.*, b.pr_real, b.pr_tanggal, b.pr_deviasi, b.pr_target 
                FROM sc_paket a LEFT JOIN sc_progress b ON a.pa_id = b.pa_id AND b.pr_jenis = 'Fisik', 
                sc_kegiatan c, 
                dinas d 
                WHERE a.pptk_id = $pptk_id AND 
                c.ke_id = a.ke_id AND 
                d.dinas_id = c.dinas_id 
                GROUP BY a.pa_id ORDER BY data";
      $q_result = $this->db->query($query)->result_array();
      if($q_result){
        $this->response([
          'status' => true,
          'data' => $q_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Paket not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function mapprogmaxbidang_get(){
      $bi_id = $this->get('bi_id');
      $query = "SELECT a.pa_id, MAX(CONVERT(b.pr_real, double)) data, b.pr_tanggal, a.*, b.pr_real, b.pr_tanggal, b.pr_deviasi, b.pr_target
                FROM
                sc_paket a LEFT JOIN sc_progress b ON a.pa_id = b.pa_id AND b.pr_jenis = 'Fisik', 
                sc_kegiatan c,
                sc_bidang e,
                dinas d
                WHERE
                e.bi_id = c.bi_id AND
                e.bi_id = $bi_id AND
                c.ke_id = a.ke_id AND
                d.dinas_id = c.dinas_id
                GROUP BY  
                a.pa_id ORDER BY data";
      $q_result = $this->db->query($query)->result_array();
      if($q_result){
        $this->response([
          'status' => true,
          'data' => $q_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Paket not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function updatemap_post(){
      $pa_id = $this->post('pa_id');
      $pa_lokasi = $this->post('pa_lokasi');
      $pa_loc_latitude = $this->post('pa_loc_latitude'); 
      $pa_loc_longitude = $this->post('pa_loc_longitude');

      $data = array(
        'pa_loc_latitude' => $pa_loc_latitude,
        'pa_loc_longitude' => $pa_loc_longitude,
        'pa_lokasi' => $pa_lokasi
      );

      $pack = array(
        'pa_id' => $pa_id,
        'pa_lokasi' => $pa_lokasi,
        'pa_loc_latitude' => $pa_loc_latitude,
        'pa_loc_longitude' => $pa_loc_longitude,
      );

      $this->db->where('pa_id', $pa_id);
      $this->db->update('sc_paket', $data);
      if($this->db->affected_rows() == 1 ){
          $this->response([
            'status' => true,
            'data' => $pack
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Paket not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function updatekontrak_post(){
      $pa_id = $this->post('pa_id');
      $pa_nomor_kontrak = $this->post('pa_nomor_kontrak');
      $pa_nilai_kontrak = $this->post('pa_nilai_kontrak'); 
      $pa_awal_kontrak = $this->post('pa_awal_kontrak');
      $pa_akhir_kontrak = $this->post('pa_akhir_kontrak');

      $data = array(
        'pa_nomor_kontrak' => $pa_nomor_kontrak,
        'pa_nilai_kontrak' => $pa_nilai_kontrak,
        'pa_awal_kontrak' => $pa_awal_kontrak,
        'pa_akhir_kontrak' => $pa_akhir_kontrak
      );

      $pack = array(
        'pa_id' => $pa_id,
        'pa_nomor_kontrak' => $pa_nomor_kontrak,
        'pa_nilai_kontrak' => $pa_nilai_kontrak,
        'pa_awal_kontrak' => $pa_awal_kontrak,
        'pa_akhir_kontrak' => $pa_akhir_kontrak
      );

      $this->db->where('pa_id', $pa_id);
      $this->db->update('sc_paket', $data);
      if($this->db->affected_rows() == 1 ){
          $this->response([
            'status' => true,
            'data' => $pack
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Paket not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function catatan_get(){
      $pa_id = $this->get('pa_id');
      $query = "SELECT * FROM sc_catatan a where a.pa_id = $pa_id";
      $query_result = $this->db->query($query)->result();
      if($query_result){
        $this->response([
          'status' => true,
          'data' => $query_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Data not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function submit_catatan_post(){
      $pa_id = $this->post('pa_id');
      $ca_catatan = $this->post('ca_catatan');
      $date_created = date_create()->format('Y-m-d H:i:s');
      $date_updated = date_create()->format('Y-m-d H:i:s');

      $data = array(
          "pa_id" => $pa_id,
          "ca_catatan" => $ca_catatan,
          "date_created" => $date_created,
          "date_updated" => $date_updated
      );

      $this->db->insert('sc_catatan', $data);
      if($this->db->affected_rows() == 1 ){
          $this->response([
            'status' => true,
            'data' => $data
        ], REST_Controller::HTTP_CREATED);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Input Catatan Failed'
        ], REST_Controller::HTTP_BAD_REQUEST);
      }

    }

    public function catatan_delete_post(){
      $ca_id = $this->post('ca_id');

      $data = array(
        'ca_id' => $ca_id,
      );

      $this->db->where('ca_id', $ca_id);
      $this->db->delete('sc_catatan', $data);

      if($this->db->affected_rows() == 1 ){
          $this->response([
            'status' => true,
            'data' => $data
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Catatan not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function penyediajasa_post(){
      $pa_id = $this->post('pa_id');
      $ko_id = $this->post('ko_id');

      $data = array(
        'pa_id' => $pa_id,
        'ko_id' => $ko_id
      );
      $this->db->where('pa_id', $pa_id);
      $this->db->update('sc_paket', $data);
      if($this->db->affected_rows() == 1 ){
          $this->response([
            'status' => true,
            'data' => $data
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Paket not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function count_progress_get(){
      $pa_id = $this->get('pa_id');
      $count = 0;

      //kurva_s_rencana
      $query1 = "SELECT COUNT(re_id) kurva_s_rencana FROM `sc_rencana` WHERE `pa_id`=$pa_id";
      $query_result1 = $this->db->query($query1)->result()[0]->kurva_s_rencana;
      if($query_result1 == 0){
        $count = $count + 1;
      } 

      //edit_kontrak
      $query2 = "SELECT COUNT(sc_paket.pa_nomor_kontrak) edit_kontrak FROM `sc_paket` WHERE `pa_id`=$pa_id";
      $query_result2 = $this->db->query($query2)->result()[0]->edit_kontrak;
      if($query_result2 == 0){
        $count = $count + 1;
      } 

      //Lokasi
      $query3 = "SELECT COUNT(pa_id) lokasi FROM `sc_paket` WHERE `pa_id`=$pa_id AND pa_loc_latitude <> 0";
      $query_result3 = $this->db->query($query3)->result()[0]->lokasi;
      if($query_result3 == 0){
        $count = $count + 1;
      } 

      //progress
      $query4 = "SELECT COUNT(pr_id) progress FROM `sc_progress` WHERE pa_id=$pa_id";
      $query_result4 = $this->db->query($query4)->result()[0]->progress;
      if($query_result4 == 0){
        $count = $count + 1;
      }

      //penyedia_jasa
      $query5 = "SELECT COUNT(pa_id) penyedia_jasa FROM `sc_paket` WHERE pa_id=$pa_id AND ko_id IS NOT NULL";
      $query_result5 = $this->db->query($query5)->result()[0]->penyedia_jasa;
      if($query_result5 == 0){
        $count = $count + 1;
      }

      // put to object
      $array = array(
        "count" => $count
      );

      // pack as array
      $array_push = array($array);
      if($array ){
        $this->response([
            'status' => true,
            'data' => $array_push
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Data Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }

    }


  }

?>

