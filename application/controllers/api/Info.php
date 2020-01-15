<?php
  // API for retrieve Login
  use Restserver\Libraries\REST_Controller;
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  defined('BASEPATH') OR exit('No direct script access allowed');
  require APPPATH . 'libraries/REST_Controller.php';
  require APPPATH . 'libraries/Format.php';

  class Info extends REST_Controller {
    
    public function __construct(){
      parent::__construct();
      $this->load->model('Info_model');
    }

    public function index_get(){
        $info = $this->Info_model->get_info();
        if($info ){
          $this->response([
              'status' => true,
              'data' => $info
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

