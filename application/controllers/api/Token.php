<?php
  // API for retrieve Login
  use Restserver\Libraries\REST_Controller;
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  defined('BASEPATH') OR exit('No direct script access allowed');
  require APPPATH . 'libraries/REST_Controller.php';
  require APPPATH . 'libraries/Format.php';

  class Token extends REST_Controller {
    
    public function __construct(){
      parent::__construct();
      $this->load->model('Users_model');
    }

    public function index_get(){
      $dinas_id = $this->get('dinas_id');
        $array = array(
          "token" => 'dS7WKoF3tJAAAAAAAAAREwtZmpsvou4Td4DTB9y6Vt8PWFPogD4gI4DIAc9Jb25h',
        
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

  }

?>

