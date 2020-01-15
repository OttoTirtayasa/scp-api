<?php
  // API for retrieve Progress
  use Restserver\Libraries\REST_Controller;
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  defined('BASEPATH') OR exit('No direct script access allowed');
  require APPPATH . 'libraries/REST_Controller.php';
  require APPPATH . 'libraries/Format.php';

  class Progress extends REST_Controller {
    
    public function __construct(){
      parent::__construct();
      $this->load->model('Anggaran_model');
    }

    public function index_get(){

    }

  }

?>

