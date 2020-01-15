<?php
  // API for retrieve Dinas
  use Restserver\Libraries\REST_Controller;
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  defined('BASEPATH') OR exit('No direct script access allowed');
  require APPPATH . 'libraries/REST_Controller.php';
  require APPPATH . 'libraries/Format.php';

  class Dinas extends REST_Controller {
    
    public function __construct(){
      parent::__construct();
      $this->load->model('Dinas_model');
    }

    public function index_get(){
      $dinas = $this->Dinas_model->get_dinas();
      if($dinas){
          $this->response([
            'status' => true,
            'data' => $dinas
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
            'status' => false,
            'message' => 'Data Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function dinas_get(){
      $dinas = $this->get('dinas_id');
      $query = "SELECT * FROM dinas where dinas_id = $dinas";
      $query_result = $this->db->query($query)->result_array();
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

    public function user_get(){
      $user_id = $this->get('user_id');
      if($user_id){
        $query = "SELECT a.dinas_nama FROM dinas a, sc_user b WHERE b.user_id = $user_id AND a.dinas_id = b.dinas_id";
        $qresult = $this->db->query($query)->result_array();
        if($qresult){
          $this->response([
            'status' => true,
            'data' => $qresult
          ], REST_Controller::HTTP_OK);
        }else{
          $this->response([
            'status' => false,
            'message' => 'Data Not Found'
          ], REST_Controller::HTTP_NOT_FOUND);
        }
      }
    }

    public function nomenklatur_post(){
      $dinas_id = $this->post('dinas_id');
      $dinas_pptk = $this->post('dinas_pptk');

      $data = array(
          "dinas_id" => $ke_id,
          "dinas_pptk" => $dinas_pptk,  
      );

      $this->db->where('dinas_id', $dinas_id);
      $this->db->update('sc_dinas', $data);
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

