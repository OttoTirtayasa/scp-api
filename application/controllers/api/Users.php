<?php
  // API for retrieve Users
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Users extends REST_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model('Users_model', 'user_model');
  }

  public function index_get(){
    $user_id = $this->get('user_id');

    if($user_id == null){
      $users = $this->user_model->get_users();
    } else{
      $users = $this->user_model->get_users($user_id);
    }

    if($users){
      $this->response([
        'status' => true,
        'data' => $users
      ], REST_Controller::HTTP_OK);
    }else{
      $this->response([
        'status' => false,
        'message' => 'Users Not Found'
      ], REST_Controller::HTTP_NOT_FOUND);
    }
  }

  public function user_dinas_get(){
    $dinas_id = $this->get('dinas_id');
    $query = "SELECT * FROM sc_user where dinas_id = $dinas_id";
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

  public function updateprofile_post(){
    $user_id = $this->post('user_id');
    $nama = $this->post('nama');
    $alamat = $this->post('alamat');
    $telephone = $this->post('telephone');

    $data = array(
      'user_id' => $user_id,
      'alamat' => $alamat,
      'nama' => $nama,
      'telephone' => $telephone
    );
    $this->db->where('user_id', $user_id);
    $this->db->update('sc_user', $data);

    if($this->db->affected_rows() == 1 ){
          $this->response([
            'status' => true,
            'data' => $data
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'User not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
  }


  public function uname_get(){
    $username = $this->get('username');
    $user = $this->user_model->get_users_fullname($username);
    if($user){
      $this->response([
        'status' => true,
        'data' => $user
      ], REST_Controller::HTTP_OK);
    }else{
      $this->response([
        'status' => false,
        'message' => 'User Not Found'
      ], REST_Controller::HTTP_NOT_FOUND);
    }
  }

  public function kontraktorall_get(){
    $allkontraktor = $this->user_model->get_allkontraktor();
    if($allkontraktor){
      $this->response([
        'status' => true,
        'data' => $allkontraktor  
      ], REST_Controller::HTTP_OK);
    }else{
      $this->response([
        'status' => false,
        'message' => 'Data Not Found'
      ], REST_Controller::HTTP_NOT_FOUND);
    }
  }

  public function 



}

?>

