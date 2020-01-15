<?php
  // API for retrieve Penyedia Jasa
  use Restserver\Libraries\REST_Controller;
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  defined('BASEPATH') OR exit('No direct script access allowed');
  require APPPATH . 'libraries/REST_Controller.php';
  require APPPATH . 'libraries/Format.php';

  class Penyediajasa extends REST_Controller {
    
    public function __construct(){
      parent::__construct();
      $this->load->model('Penyedia_jasa_model');
    }

    public function index_get(){
      $penyedia_jasa = $this->Penyedia_jasa_model->get_penyedia_jasa();
      if($penyedia_jasa){
          $this->response([
            'status' => true,
            'data' => $penyedia_jasa
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
            'status' => false,
            'message' => 'Penyedia Jasa Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function dinas_get(){
      $dinas_id = $this->get('dinas_id');
      $query = "SELECT
                b.ko_id,
                b.ko_nama
                FROM
                sc_paket a,
                sc_kegiatan c,
                sc_kontraktor b,
                dinas d
                WHERE
                a.ko_id = b.ko_id
                AND a.ke_id = c.ke_id
                AND d.dinas_id = $dinas_id";
      $query_result = $this->db->query($query)->result_array();
      if($query_result){
          $this->response([
            'status' => true,
            'data' => $query_result
          ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Penyedia Jasa Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function penyedia_get(){
      $ko_nama = $this->get('ko_nama');
      $query="SELECT * FROM sc_kontraktor where ko_nama=$ko_nama ";
      $query_result = $this->db->query($query)->result_array();
      if($query_result){
        $this->response([
          'status' => true,
          'data' => $query_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Penyedia Jasa Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function penyedia_bypaket_get(){
      $pa_id = $this->get('pa_id');
      $query = "SELECT
                b.*, a.pa_id
                FROM
                sc_paket a,
                sc_kontraktor b
                WHERE
                a.ko_id = b.ko_id
                AND a.pa_id = $pa_id";
      $query_result = $this->db->query($query)->result_array();
      if($query_result){
        $this->response([
          'status' => true,
          'data' => $query_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Penyedia Jasa Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function submit_penyedia_backup_post(){
      $pa_id = $this->post('pa_id');
      $ko_id = $this->post('ko_id');
      $ko_nama = $this->post('ko_nama');
      $date_created = date_create()->format('Y-m-d H:i:s');
      $date_updated = date_create()->format('Y-m-d H:i:s');
        if($pa_id && $ko_nama){
          // cek terlebih dahulu apakah nama kontraktor sudah terdaftar
          $query="SELECT * FROM sc_kontraktor where ko_nama = '$ko_nama' ";
          $query_result = $this->db->query($query)->result_array();
          if($query_result){ // jika ya
            $data= array(
              "ko_id" => $query_result[0]['ko_id']
            );
            $this->db->where('pa_id', $pa_id);
            $this->db->update('sc_paket', $data);
            if($this->db->affected_rows() == 1 ){
                $this->response([
                  'status' => true,
                  'data' => $query_result
              ], REST_Controller::HTTP_OK);
            }else{  
              $this->response([
                'status' => false,
                'message' => 'Update Failed'
              ], REST_Controller::HTTP_NOT_FOUND);
            } 
          }else{ // jika tidak
              $data = array(
                  "ko_nama" => $ko_nama,
                  "date_created" => $date_created,
                  "date_updated" => $date_updated
              );
              $this->db->insert('sc_kontraktor', $data);
              if($this->db->affected_rows() == 1 ){
                  $query2="SELECT * FROM sc_kontraktor where ko_nama = '$ko_nama'";
                  $query2_result = $this->db->query($query2)->result_array();
                  if($query2_result){
                    $data= array(
                      "ko_id" => $query2_result[0]['ko_id']
                    );
                    $this->db->where('pa_id', $pa_id);
                    $this->db->update('sc_paket', $data);
                    if($this->db->affected_rows() == 1 ){
                        $this->response([
                          'status' => true,
                          'data' => $query2_result
                      ], REST_Controller::HTTP_OK);
                    }else{  
                      $this->response([
                        'status' => false,
                        'message' => 'Update Failed'
                      ], REST_Controller::HTTP_NOT_FOUND);
                    }
                  }
              }else{
                $this->response([
                  'status' => false,
                  'message' => 'Submit Penyedia Jasa Failed'
                ], REST_Controller::HTTP_BAD_REQUEST);
              }
          }
        }
      }

  }

?>

