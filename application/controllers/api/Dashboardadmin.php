<?php
  // API for retrieve Dashboardadmin
  use Restserver\Libraries\REST_Controller;
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  defined('BASEPATH') OR exit('No direct script access allowed');
  require APPPATH . 'libraries/REST_Controller.php';
  require APPPATH . 'libraries/Format.php';

  class Dashboardadmin extends REST_Controller {
    
    public function __construct(){
      parent::__construct();
      $this->load->model('Dinas_model');
      $this->load->model('Kegiatan_model');
      $this->load->model('Paket_model');
      $this->load->model('Bidang_model');
      $this->load->model('Progress_model');
      $this->load->model('Users_model');
    }

    public function index_get(){
      $dinas_id = $this->get('dinas_id');

      // paket all
      $query1 = "SELECT COUNT(a.pa_id) paket_all
                FROM
                sc_paket a,
                sc_kegiatan b,
                dinas c
                WHERE
                a.ke_id = b.ke_id AND
                b.dinas_id = c.dinas_id AND
                c.dinas_id = $dinas_id";
      $q_result1 = $this->db->query($query1)->result()[0]->paket_all;

      // paket belum
      $query2 = "SELECT COUNT(a.pa_id) paket_belum_mulai FROM 
                sc_paket a,
                sc_kegiatan c,
                dinas d
                WHERE
                d.dinas_id = c.dinas_id AND
                c.ke_id = a.ke_id AND
                d.dinas_id = $dinas_id AND
                a.pa_awal_kontrak IS NULL AND
                a.status = 0";
      $q_result2 = $this->db->query($query2)->result()[0]->paket_belum_mulai;

      // paket selesai
      $query3 = "SELECT COUNT(b.pr_id) paket_selesai
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
        $q_result3 = $this->db->query($query3)->result()[0]->paket_selesai;

        $sum_temp = (int) $q_result3 + (int) $q_result2;

        // get paket yang sedang dikerjakan
        $q_result4 = (int) $q_result1 - (int) $sum_temp;

        // put to object
        $array = array(
          "paket_all" => $q_result1,
          "paket_belum_mulai" => $q_result2,
          "paket_selesai" => $q_result3,
          "paket_progress" => (string) $q_result4
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


    // PAGU ========
    public function paguadmin_get(){
      $dinas_id = $this->get('dinas_id');
      $sum_allpagu;
      $query1 = "SELECT SUM(sc_paket.pa_pagu) as total_pagu1
                FROM dinas
                LEFT JOIN sc_kegiatan on dinas.dinas_id=sc_kegiatan.dinas_id
                LEFT JOIN sc_paket on sc_paket.ke_id=sc_kegiatan.ke_id
                where dinas.dinas_id=$dinas_id";
      $q_result1 = $this->db->query($query1)->result()[0]->total_pagu1;

      $query2 = "SELECT 
                SUM(sc_anggaran.anp_pagu) as total_pagu2
                FROM dinas
                LEFT JOIN sc_kegiatan on dinas.dinas_id=sc_kegiatan.dinas_id
                LEFT JOIN sc_anggaran on sc_anggaran.ke_id=sc_kegiatan.ke_id
                where dinas.dinas_id=$dinas_id";
      $q_result2 = $this->db->query($query2)->result()[0]->total_pagu2;


      $sum_allpagu = $q_result1 + $q_result2;
      $array = array("total_pagu_pptk" => $sum_allpagu);
      $array_push = array($array);

      if($sum_allpagu > 0){
      $this->response([
          'status' => true,
          'data' => $array_push
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'User Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }


    // REALIASASI ========
    public function realadmin_get(){
      $dinas_id = $this->get('dinas_id');
      $sum_allreal;
      $query = "SELECT 
                sum(f.pr_daya_serap_kontrak) as total_realisasi,
                a.web_link
                FROM
                dinas a,
                sc_kegiatan d,
                sc_paket e left JOIN sc_progress f on f.pa_id = e.pa_id
                WHERE
                d.dinas_id = a.dinas_id and
                d.ke_id = e.ke_id and
                e.pa_id = f.pa_id and
                a.dinas_id =$dinas_id";
      $q_result = $this->db->query($query)->result()[0]->total_realisasi;
      if($q_result == NULL){
        $q_result == 0;
      }
      // var_dump($q_result);

      $query2 = "SELECT 
                sum(f.se_daya_serap) as total_realisasi2,
                a.web_link
                FROM
                dinas a,
                sc_kegiatan d,
                sc_anggaran e left JOIN sc_serapan f on f.an_id = e.an_id
                WHERE
                d.dinas_id = a.dinas_id and
                d.ke_id = e.ke_id and
                e.an_id = f.an_id and
                a.dinas_id =$dinas_id";
      $q_result2 = $this->db->query($query2)->result()[0]->total_realisasi2;
      if($q_result2 == NULL){
        $q_result2 == 0;
      }

      $sum_allreal = $q_result + $q_result2;

      $array = array("total_real_pptk" => $sum_allreal);
      $array_push = array($array);

      if($sum_allreal > 0){
       $this->response([
         'status' => true,
         'data' => $array_push
       ], REST_Controller::HTTP_OK);
      }else{
       $this->response([
         'status' => false,
         'message' => 'Data count error'
       ], REST_Controller::HTTP_NOT_FOUND);
      }

    }

    /// SISA ANGGARAN
    public function sisaadmin_get(){
      $dinas_id = $this->get('dinas_id');
      $total_pagu;
      $real_pagu;
      $sisa_pagu_result;

      $query1 = "SELECT SUM(sc_paket.pa_pagu) as total_pagu1
                FROM dinas
                LEFT JOIN sc_kegiatan on dinas.dinas_id=sc_kegiatan.dinas_id
                LEFT JOIN sc_paket on sc_paket.ke_id=sc_kegiatan.ke_id
                where dinas.dinas_id=$dinas_id";
      $q_result1 = $this->db->query($query1)->result()[0]->total_pagu1;
      if($q_result1 == NULL){
        $q_result1 = 0;
      }

      $query2 = "SELECT 
                SUM(sc_anggaran.anp_pagu) as total_pagu2
                FROM dinas
                LEFT JOIN sc_kegiatan on dinas.dinas_id=sc_kegiatan.dinas_id
                LEFT JOIN sc_anggaran on sc_anggaran.ke_id=sc_kegiatan.ke_id
                where dinas.dinas_id=$dinas_id";
      $q_result2 = $this->db->query($query2)->result()[0]->total_pagu2;
      if($q_result2 == NULL){
        $q_result2 = 0;
      }

      $total_pagu = $q_result1 + $q_result2;


      // REALISASI
      $query3 = "SELECT 
                sum(f.pr_daya_serap_kontrak) as total_realisasi,
                a.web_link
                FROM
                dinas a,
                sc_kegiatan d,
                sc_paket e left JOIN sc_progress f on f.pa_id = e.pa_id
                WHERE
                d.dinas_id = a.dinas_id and
                d.ke_id = e.ke_id and
                e.pa_id = f.pa_id and
                a.dinas_id =$dinas_id";
      $q_result3 = $this->db->query($query3)->result()[0]->total_realisasi;
      if($q_result3 == NULL){
        $q_result3 == 0;
      }
      // var_dump($q_result);

      $query4 = "SELECT 
                sum(f.se_daya_serap) as total_realisasi2,
                a.web_link
                FROM
                dinas a,
                sc_kegiatan d,
                sc_anggaran e left JOIN sc_serapan f on f.an_id = e.an_id
                WHERE
                d.dinas_id = a.dinas_id and
                d.ke_id = e.ke_id and
                e.an_id = f.an_id and
                a.dinas_id =$dinas_id";
      $q_result4 = $this->db->query($query4)->result()[0]->total_realisasi2;
      if($q_result4 == NULL){
        $q_result4 == 0;
      } 


      $real_pagu = $q_result3 + $q_result4;

      $sisa_pagu_result = $total_pagu - $real_pagu;
      $array = array("total_sisa" => $sisa_pagu_result);
      $array_push = array($array);
      
       if($sisa_pagu_result){
        $this->response([
          'status' => true,
          'data' => $array_push
        ], REST_Controller::HTTP_OK);
       }else{
        $this->response([
          'status' => false,
          'message' => 'Data count error'
        ], REST_Controller::HTTP_NOT_FOUND);
       }
    }

    public function chartrealisasi_get(){
      $this->db->query("SET @runningTotal = 0");
      $query = "SELECT 
                x1 x,
                y1 as sum,
                @runningTotal := @runningTotal + totals.y1 AS y
            FROM
            (SELECT x1 , SUM(y1) y1
            FROM(
            SELECT MONTH(se_tanggal) x1, SUM(se_daya_serap) y1 FROM sc_serapan GROUP BY x1
            UNION ALL
            SELECT MONTH(pr_tanggal) x2, SUM(pr_daya_serap_kontrak) y2 FROM sc_progress WHERE pr_jenis = 'Keuangan' GROUP BY x2
            )t GROUP BY x1) totals
            ORDER BY x1";
      $q_result = $this->db->query($query)->result_array();
      if($q_result){
        $this->response([
          'status' => true,
          'data' => $q_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Data not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function paketall_get(){
      $dinas_id = $this->get('dinas_id');
      $query = "SELECT a.*
                FROM
                sc_paket a,
                sc_kegiatan b,
                dinas c
                WHERE
                a.ke_id = b.ke_id AND
                b.dinas_id = c.dinas_id AND
                c.dinas_id = $dinas_id";
        $q_result = $this->db->query($query)->result()[0]->paket_all;
        $array = array("paket_all" => $q_result);
        $array_push = array($array);
        if($q_result){
          $this->response([
            'status' => true,
            'data' => $array_push
          ], REST_Controller::HTTP_OK);
         }else{
          $this->response([
            'status' => false,
            'message' => 'Data count error'
          ], REST_Controller::HTTP_NOT_FOUND);
         }
    }

    public function paketbelum_get(){
      $dinas_id = $this->get('dinas_id');
      $query = "SELECT a.*
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
      $q_result = $this->db->query($query)->result_array();
      if($q_result){
        $this->response([
            'status' => true,
            'data' => $q_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Data Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function paketselesai_get(){
      $dinas_id = $this->get('dinas_id');
      $query = "SELECT a.*
                FROM 
                sc_paket a,
                sc_progress b,
                sc_kegiatan c,
                dinas d
                WHERE
                d.dinas_id = c.dinas_id AND
                c.ke_id = a.ke_id AND
                a.pa_id = b.pa_id AND
                a.pa_awal_kontrak IS NOT NULL AND 
                a.pa_nilai_kontrak IS NOT NULL AND
                d.dinas_id = $dinas_id AND
                b.pr_target = 100";
       $q_result = $this->db->query($query)->result_array();
       if($q_result){
         $this->response([
             'status' => true,
             'data' => $q_result
         ], REST_Controller::HTTP_OK);
       }else{
         $this->response([
           'status' => false,
           'message' => 'Data Not Found'
         ], REST_Controller::HTTP_NOT_FOUND);
       }
    }

    public function paketbelumselesai_get(){
      $dinas_id = $this->get('dinas_id');
      $query = "SELECT a.*
                FROM 
                sc_paket a,
                sc_progress b,
                sc_kegiatan c,
                dinas d
                WHERE
                d.dinas_id = c.dinas_id AND
                c.ke_id = a.ke_id AND
                a.pa_id = b.pa_id AND
                a.pa_awal_kontrak IS NOT NULL AND
                a.pa_nilai_kontrak IS NOT NULL AND
                d.dinas_id = $dinas_id AND
                b.pr_real < 100 OR CURDATE() >= a.pa_awal_kontrak 
                GROUP BY a.pa_id";
       $q_result = $this->db->query($query)->result_array();
       if($q_result){
         $this->response([
             'status' => true,
             'data' => $q_result
         ], REST_Controller::HTTP_OK);
       }else{
         $this->response([
           'status' => false,
           'message' => 'Data Not Found'
         ], REST_Controller::HTTP_NOT_FOUND);
       }
    }

    public function total_anggaran_get(){
      $dinas_id = $this->get('dinas_id');
      $query = "SELECT SUM(a.anp_pagu) total_anggaran
                FROM 
                sc_anggaran a,
                sc_kegiatan c,
                dinas d
                WHERE
                d.dinas_id = c.dinas_id AND
                c.ke_id = a.ke_id AND
                d.dinas_id = $dinas_id";
       $q_result = $this->db->query($query3)->result()[0]->total_anggaran;
       if($q_result){
         $this->response([
             'status' => true,
             'data' => $q_result
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
