<?php
  // API for retrieve Bidang
  use Restserver\Libraries\REST_Controller;
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  defined('BASEPATH') OR exit('No direct script access allowed');
  require APPPATH . 'libraries/REST_Controller.php';
  require APPPATH . 'libraries/Format.php';

  class Bidang extends REST_Controller {
    
    public function __construct(){
      parent::__construct();
      $this->load->model('Bidang_model');
    }

    public function index_get(){
      $dinas = $this->Bidang_model->get_bidang();
      if($dinas){
          $this->response([
            'status' => true,
            'data' => $dinas
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
            'status' => false,
            'message' => 'Bidang Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function bidang_dinas_get(){
      $dinas = $this->get('dinas_id');
      $query = "SELECT COUNT(b.bi_id) as total_bidang
                FROM
                dinas a,
                sc_bidang b
                WHERE 
                b.dinas_id = a.dinas_id AND
                a.dinas_id = $dinas";
      $q_result = $this->db->query($query)->result()[0]->bidang_all;
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

    public function create_post(){
      // $nama_bidang = $this->post('nama_bidang');
      $data = array(
        'dinas_id' => $this->post('dinas_id'),
        'bi_nama' => $this->post('bi_nama')
      );
      $result = $this->Bidang_model->add_bidang($data);
      $this->set_response([
          'status' => true,
          'message' => 'Success'
      ], REST_Controller::HTTP_CREATED);
    }

    public function edit_post(){
      $bi_nama = $this->post('bi_nama');
      $bi_id = $this->post('bi_id');
      $data = array(
        'bi_nama' => $bi_nama
      );
      $this->db->where('bi_id', $bi_id);
      $this->db->update('sc_bidang', $data);
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

    public function bidang_get(){
      $bi_id = $this->get('bi_id');
      $ke_id = $this->get('ke_id');
      if($bi_id){
        $query = "SELECT * FROM sc_bidang where bi_id = $bi_id and is_deleted = 0";
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
      }else if($ke_id){
        $query1 = "SELECT * FROM sc_bidang a, sc_kegiatan b WHERE a.bi_id = b.bi_id AND b.ke_id = $ke_id";
        $query_result1 = $this->db->query($query1)->result();
        if($query_result1){
          $this->response([
            'status' => true,
            'data' => $query_result1
          ], REST_Controller::HTTP_OK);
        }else{
          $this->response([
            'status' => false,
            'message' => 'Data not found'
          ], REST_Controller::HTTP_NOT_FOUND);
        }
      }
    }
    

  }

?>

