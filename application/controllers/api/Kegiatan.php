<?php
  // API for retrieve Kegiatam
  use Restserver\Libraries\REST_Controller;
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  defined('BASEPATH') OR exit('No direct script access allowed');
  require APPPATH . 'libraries/REST_Controller.php';
  require APPPATH . 'libraries/Format.php';

  class Kegiatan extends REST_Controller {
    
    public function __construct(){
      parent::__construct();
      $this->load->model('Kegiatan_model');
    } 

    public function index_get(){
      $ke_id = $this->get('ke_id');
      if($ke_id){
        $query = "SELECT * from sc_kegiatan a WHERE a.ke_id = $ke_id AND a.status = 0";
        $query_result = $this->db->query($query)->result();
        if($query_result){
          $this->response([
              'status' => true,
              'data' => $query_result
          ], REST_Controller::HTTP_OK);
        }else{
          $this->response([
            'status' => false,
            'message' => 'Data Not Found'
          ], REST_Controller::HTTP_NOT_FOUND);
        }
      }

      $kegiatan = $this->Kegiatan_model->get_kegiatan();
      if($kegiatan){
          $this->response([
            'status' => true,
            'data' => $kegiatan
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
            'status' => false,
            'message' => 'Data Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
      
    }

    public function paket_get(){
      $dinas_id = $this->get('dinas_id');
      $pptk_id = $this->get('pptk_id');
      $bi_id = $this->get('bi_id');
      $query_result_send;
      if($dinas_id){
        $query1 = "SELECT * FROM sc_kegiatan a where a.dinas_id = $dinas_id";
        $query_result = $this->db->query($query1)->result();
        $len = count($query_result);
        $query2;
        for($i = 0; $i < $len; $i++){
          $ke_id = $query_result[$i]->ke_id;
          if($pptk_id){
            $query2 = "SELECT * FROM sc_paket b where b.ke_id = $ke_id and b.pptk_id = $pptk_id";
            $query_result2 = $this->db->query($query2)->result();
          }else if($bi_id){
            $query2 = "SELECT * FROM sc_paket b, sc_kegiatan c where b.ke_id = $ke_id AND b.ke_id = c.ke_id and c.bi_id = $bi_id";
            $query_result2 = $this->db->query($query2)->result();
          }else{
            $query2 = "SELECT * FROM sc_paket b where b.ke_id = $ke_id";
            $query_result2 = $this->db->query($query2)->result();
          }
          $query_result[$i]->child = $query_result2;
        }

        if($query_result){
              $this->response([
                  'status' => true,
                  'data' => $query_result
              ], REST_Controller::HTTP_OK);
          
        }else{
          $this->response([
            'status' => false,
            'message' => 'Data Not Found'
          ], REST_Controller::HTTP_NOT_FOUND);
        }
      }
    }


    public function anggaran_get(){
      $dinas_id = $this->get('dinas_id');
      $pptk_id = $this->get('pptk_id');
      $bi_id = $this->get('bi_id');
      $query_result_send;
      if($dinas_id){
        $query1 = "SELECT * FROM sc_kegiatan a where a.dinas_id = $dinas_id";
        $query_result = $this->db->query($query1)->result();
        $len = count($query_result);
        $query2;
        for($i = 0; $i < $len; $i++){
          $ke_id = $query_result[$i]->ke_id;
          if($pptk_id){
            $query2 = "SELECT * FROM sc_anggaran b where b.ke_id = $ke_id and b.pptk_id = $pptk_id";
            $query_result2 = $this->db->query($query2)->result();
          }else if($bi_id){
            $query2 = "SELECT * FROM sc_anggaran b, sc_kegiatan c where b.ke_id = $ke_id AND b.ke_id = c.ke_id and c.bi_id = $bi_id";
            $query_result2 = $this->db->query($query2)->result();
          }else{
            $query2 = "SELECT * FROM sc_anggaran b where b.ke_id = $ke_id";
            $query_result2 = $this->db->query($query2)->result();
          }
          $query_result[$i]->child = $query_result2;
        }

        if($query_result){
              $this->response([
                  'status' => true,
                  'data' => $query_result
              ], REST_Controller::HTTP_OK);
          
        }else{
          $this->response([
            'status' => false,
            'message' => 'Data Not Found'
          ], REST_Controller::HTTP_NOT_FOUND);
        }
      }
    }


    public function dinas_get(){
      $dinas = $this->get('dinas_id');
      $kegiatan = $this->Kegiatan_model->get_kegiatan_dinas($dinas);
      if($kegiatan){
        $this->response([
            'status' => true,
            'data' => $kegiatan
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
            'status' => false,
            'message' => 'Data Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function bidang_get(){
      $bi_id = $this->get('bi_id');
      $kegiatan = $this->Kegiatan_model->get_kegiatan_bidang($bi_id);
      if($kegiatan){
        $this->response([
            'status' => true,
            'data' => $kegiatan
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Data Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function tahun_get(){
      $tahun = $this->get('tahun');
      $kegiatan = $this->Kegiatan_model->get_kegiatan_bidang($tahun);
      if($kegiatan){
        $this->response([
            'status' => true,
            'data' => $kegiatan
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Data Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function user_get(){
      $user_id =  $this->get('user_id');
      $kegiatan = $this->Kegiatan_model->get_kegiatan_user($user_id);
      if($kegiatan){
        $this->response([
            'status' => true,
            'data' => $kegiatan
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Data Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }


    public function userdinas_get(){
      $user_id =  $this->get('user_id');
      $dinas_id =  $this->get('dinas_id');
      $kegiatan = $this->Kegiatan_model->get_kegiatan_userdinas($user_id, $dinas_id);
      if($kegiatan){
        $this->response([
            'status' => true,
            'data' => $kegiatan
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Data Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function bidangdinas_get(){
      $user_id =  $this->get('bi_id');
      $dinas_id =  $this->get('dinas_id');
      $kegiatan = $this->Kegiatan_model->get_kegiatan_bidangdinas($user_id, $dinas_id);
      if($kegiatan){
        $this->response([
            'status' => true,
            'data' => $kegiatan
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Data Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function arsip_get(){
      $ke_id = $this->get('ke_id');
      if($ke_id){
        $query = "SELECT * from sc_kegiatan a WHERE a.ke_id = $ke_id AND a.status = 1";
        $query_result = $this->db->query($query)->result();
        if($query_result){
          $this->response([
              'status' => true,
              'data' => $query_result
          ], REST_Controller::HTTP_OK);
        }else{
          $this->response([
            'status' => false,
            'message' => 'Data Not Found'
          ], REST_Controller::HTTP_NOT_FOUND);
        }
      }
    }

    public function arsip_post(){
      $ke_id = $this->post('ke_id');

      $data = array(
          "ke_id" => $ke_id,
          "status" => 1,  
      );

      $this->db->where('ke_id', $ke_id);
      $this->db->update('sc_kegiatan', $data);
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
    
  }

?>

