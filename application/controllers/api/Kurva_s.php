<?php
  // API for retrieve Users
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Kurva_s extends REST_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model('Kurva_s_model');
  }

  public function index_get(){
    $paket_id = $this->get('pa_id');
    $query = "SELECT * FROM `sc_rencana` WHERE `pa_id`=$paket_id ORDER BY CAST(`re_progress` AS SIGNED);";
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

  public function delete_rencana_post(){
    //delete kurva s rencana
    $re_id = $this->post('re_id');
    $data = array(
      're_id' => $re_id,
    );

    $this->db->where('re_id', $re_id);
    $this->db->delete('sc_rencana', $data);

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

  public function index_delete(){
    $id = $this->delete('re_id');
    if($id == null){
    $this->response([
          'status' => false,
      'message' => 'Provide an rencana id!'
      ], REST_Controller::HTTP_NOT_FOUND);
      }else{
        if($this->Kurva_s_model->delete_data($id) > 0){
              // ok
          $this->response([
            'status' => true,
            're_id' => $id,
            'message' => 'Deleted'
          ], REST_Controller::HTTP_OK);
        }else{
             // not ok id not found
          $this->response([
            'status' => false,
            'message' => 'Rencana Id not found!'
          ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
  }

  public function submit_rencana_post(){
    $pa_id = $this->post('pa_id');
    $re_tanggal = $this->post('re_tanggal');
    $re_progress = $this->post('re_progress');

    $data = array(
      'pa_id' => $pa_id,
      're_tanggal' => $re_tanggal,
      're_progress' => $re_progress,
    );

    $this->db->insert('sc_rencana', $data);
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

}

?>

