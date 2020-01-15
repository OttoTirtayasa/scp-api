<?php
  // API for retrieve Dashboardadmin
  use Restserver\Libraries\REST_Controller;
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  defined('BASEPATH') OR exit('No direct script access allowed');
  require APPPATH . 'libraries/REST_Controller.php';
  require APPPATH . 'libraries/Format.php';

  class Dashboardsuper extends REST_Controller {
    
    public function __construct(){
      parent::__construct();
      $this->load->model('Dinas_model');
      $this->load->model('Kegiatan_model');
      $this->load->model('Paket_model');
      $this->load->model('Bidang_model');
      $this->load->model('Progress_model');
      $this->load->model('Users_model');
    }

    public function index_get(){
        $array = array(
          "token" => 'dS7WKoF3tJAAAAAAAAAQpq5ExNdQtJbcwehKmbMl5PIxFLcFYCNwo9HY1uzCUoJn',
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
