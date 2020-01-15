<?php
  // API for retrieve Progress
  use Restserver\Libraries\REST_Controller;
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  defined('BASEPATH') OR exit('No direct script access allowed');
  require APPPATH . 'libraries/REST_Controller.php';
  require APPPATH . 'libraries/Format.php';

  class Program extends REST_Controller {
    
    public function __construct(){
      parent::__construct();
      $this->load->model('Program_model');
    }

    public function index_get(){
      $program_id = $this->get('program_id');
      $query = "SELECT * FROM `program` WHERE `program_id`=$program_id";
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

    public function submit_program_post(){
      $dinas_id = $this->post('dinas_id');
      $program_nama = $this->post('program_nama');

      $data = array(
        'dinas_id' => $dinas_id,
        'program_nama' => $program_nama,
      );

      $this->db->insert('program', $data);
      if($this->db->affected_rows() == 1 ){
        $this->response([
          'status' => true,
          'data' => $data
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Input Progress Failed'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function delete_program_post(){
      $program_id = $this->post('program_id');
      $data = array(
        'program_id' => $program_id,
      );

      $this->db->where('program_id', $program_id);
      $this->db->delete('program', $data);

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

  }

?>

