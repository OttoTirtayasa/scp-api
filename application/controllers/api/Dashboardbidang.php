<?php
  // API for retrieve Dashboardpptk
  use Restserver\Libraries\REST_Controller;
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  defined('BASEPATH') OR exit('No direct script access allowed');
  require APPPATH . 'libraries/REST_Controller.php';
  require APPPATH . 'libraries/Format.php';

  class Dashboardbidang extends REST_Controller {
    
    public function __construct(){
      parent::__construct();
      $this->load->model('Dinas_model');
      $this->load->model('Kegiatan_model');
      $this->load->model('Paket_model');
      $this->load->model('Users_model');
    }

    public function index_get(){
        $bi_id = $this->get('bi_id');
        $q_result4; // sedang

        // paket all
        $query1 = "SELECT COUNT(a.pa_id) paket_all 
        FROM 
        sc_paket a, sc_kegiatan b
        WHERE 
        b.bi_id = $bi_id and 
        b.ke_id = a.ke_id and
        a.status = 0";
        $q_result1 = $this->db->query($query1)->result()[0]->paket_all;

        // paket belum
        $query2 = "SELECT COUNT(a.pa_id) paket_belum_mulai
                  FROM 
                  sc_paket a,
                  sc_kegiatan b
                  WHERE
                  a.pa_awal_kontrak IS NULL
                  and b.bi_id = $bi_id
                  and a.ke_id = b.ke_id
                  and a.status = 0";
        $q_result2 = $this->db->query($query2)->result()[0]->paket_belum_mulai;
        
        // paket selesai
        $query3 = "SELECT COUNT(a.pa_id) paket_selesai
                  FROM 
                  sc_paket a,
                  sc_kegiatan c,
                  sc_progress b
                  WHERE
                  a.pa_id = b.pa_id AND
                  a.pa_awal_kontrak IS NOT NULL AND 
                  a.pa_nilai_kontrak IS NOT NULL AND
                  c.bi_id = $bi_id AND
                  a.ke_id = c.ke_id AND
                  b.pr_target = 100";
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

    public function paketall_get(){
      $bi_id = $this->get('bi_id');
      $query = "SELECT COUNT(a.pa_id) paket_all 
        FROM 
        sc_paket a, sc_kegiatan b 
        WHERE 
        b.bi_id = $bi_id and 
        b.ke_id = a.ke_id and
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
          'message' => 'User Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function paketbelum_get(){
      $bi_id = $this->get('bi_id');
      $query = "SELECT COUNT(a.pa_id) paket_belum_mulai
                  FROM 
                  sc_paket a,
                  sc_kegiatan b
                  WHERE
                  a.pa_awal_kontrak IS NULL
                  and b.bi_id = $bi_id
                  and a.ke_id = b.ke_id
                  and a.status = 0";
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
      $bi_id = $this->get('bi_id');
      $query = "SELECT COUNT(a.pa_id) paket_selesai
                  FROM 
                  sc_paket a,
                  sc_kegiatan c,
                  sc_progress b
                  WHERE
                  a.pa_id = b.pa_id AND
                  a.pa_awal_kontrak IS NOT NULL AND 
                  a.pa_nilai_kontrak IS NOT NULL AND
                  c.bi_id = $bi_id AND
                  a.ke_id = c.ke_id AND
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

    public function pagubidang_get(){
      $bi_id = $this->get('bi_id');
      $sum_allpagu;
      $query1 = "SELECT
                SUM(a.pa_pagu) total_pagu_bidang
                FROM
                sc_paket a,
                sc_kegiatan b
                WHERE
                b.bi_id = $bi_id AND
                a.ke_id = b.ke_id";
      $q_result1 = $this->db->query($query1)->result()[0]->total_pagu_bidang;

      $query2 = "SELECT
                SUM(a.anp_pagu) total_pagu_bidang_ang
                FROM
                sc_anggaran a,
                sc_kegiatan b
                WHERE
                b.bi_id = $bi_id AND
                a.ke_id = b.ke_id";
      $q_result2 =$this->db->query($query2)->result()[0]->total_pagu_bidang_ang;
      
      $sum_allpagu = (int) $q_result1 + (int) $q_result2;
      $array = array("total_pagu_pptk" => $sum_allpagu);
      $array_push = array($array);

      if($sum_allpagu){
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


    public function realbidang_get(){
      $bi_id = $this->get('bi_id');
      $query1 = "SELECT SUM(b.pr_daya_serap_kontrak) total_real1
                FROM
                sc_paket a,
                sc_kegiatan c,
                sc_progress b
                WHERE
                a.pa_id = b.pa_id AND
                b.pr_daya_serap_kontrak != 0 AND
                c.ke_id = a.ke_id AND
                c.bi_id = $bi_id";
       $q_result1 = $this->db->query($query1)->result()[0]->total_real1;

      $query2 = "SELECT
                SUM(b.se_daya_serap) total_real2
                FROM
                sc_anggaran a,
                sc_kegiatan c,
                sc_serapan b
                WHERE
                a.an_id = b.an_id AND
                c.ke_id = a.ke_id AND
                c.bi_id = $bi_id";
      $q_result2 = $this->db->query($query2)->result()[0]->total_real2;

      $sum_all_real = $q_result1 + $q_result2;
      $array = array("total_real_pptk" => $sum_all_real);
      $array_push = array($array);

      // result
      if($sum_all_real){
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

    public function sisabidang_get(){
      $bi_id = $this->get('bi_id');
      $sum_allpagu;
      $sum_all_real;
      $sum_all_sisa;

      $query1 = "SELECT
                SUM(a.pa_pagu) total_pagu_pptk
                FROM
                sc_paket a,
                sc_kegiatan b
                WHERE
                b.bi_id = $bi_id AND
                a.ke_id = b.ke_id";
      $q_result1 = $this->db->query($query1)->result()[0]->total_pagu_pptk;

      $query2 = "SELECT
                SUM(a.anp_pagu) total_pagu_pptk_ang
                FROM
                sc_anggaran a,
                sc_kegiatan b
                WHERE
                b.bi_id = $bi_id AND
                a.ke_id = b.ke_id";
      $q_result2 =$this->db->query($query2)->result()[0]->total_pagu_pptk_ang;
      
      $sum_allpagu = (int) $q_result1 + (int) $q_result2;

      $query3 = "SELECT SUM(b.pr_daya_serap_kontrak) total_real1
                FROM
                sc_paket a,
                sc_kegiatan c,
                sc_progress b
                WHERE
                a.pa_id = b.pa_id AND
                b.pr_daya_serap_kontrak != 0 AND
                c.ke_id = a.ke_id AND
                c.bi_id = $bi_id";
      $q_result3  = $this->db->query($query3)->result()[0]->total_real1;

      $query4 = "SELECT
                SUM(b.se_daya_serap) total_real2
                FROM
                sc_anggaran a,
                sc_kegiatan c,
                sc_serapan b
                WHERE
                a.an_id = b.an_id AND
                c.ke_id = a.ke_id AND
                c.bi_id = $bi_id";
      $q_result4 = $this->db->query($query4)->result()[0]->total_real2;
      $sum_all_real = (int) $q_result3 + (int) $q_result4;

      // result
      $sum_all_sisa = $sum_allpagu - $sum_all_real;
      $array = array("total_sisa" => $sum_all_sisa);
      $array_push = array($array);

      if($sum_all_sisa){
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

    public function total_anggaran_get(){
      $bi_id = $this->get('bi_id');
      $query = "SELECT SUM(a.anp_pagu) total_anggaran
                FROM 
                sc_anggaran a,
                sc_kegiatan b
                WHERE
                a.ke_id = b.ke_id AND
                b.bi_id = $bi_id";
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

