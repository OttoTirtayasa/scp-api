<?php
  // API for retrieve Progress
  use Restserver\Libraries\REST_Controller;
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  defined('BASEPATH') OR exit('No direct script access allowed');
  require APPPATH . 'libraries/REST_Controller.php';
  require APPPATH . 'libraries/Format.php';

  class Proyek extends REST_Controller {

    public function __construct(){
      parent::__construct();
      $this->load->model('Proyek_model');
      $this->load->model('Users_model');
    }

    public function index_post(){
      $data = array(
        'id_owner' => $this->post('id_owner'),
        'id_pengawas' => $this->post('id_pengawas'),
        'id_kontraktor' => $this->post('id_kontraktor'),
        'nama' => $this->post('nama'),
        'volume' => $this->post('volume'),
        #===========ATTENTION==============
        # Format untuk tanggal = YYYY-MM-DD
        'tgl_awal' => $this->post('tgl_awal'),
        'tgl_akhir' => $this->post('tgl_akhir')
      );

      $create_check = $this->Proyek_model->post_proyek($data);
      if ($create_check) {
        $this->response([
          'status' => true,
          'message'=> 'Create Project Success'
          ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Create Project Failed'
          ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function listpengawas_get(){
      $id_owner=1;
      $show_pengawas= $this->Users_model->get_pengawas($id_owner);
      if ($show_pengawas) {
        $this->response([
          'status' => true,
          'data'=> $show_pengawas
          ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message'=> 'Pengawas not Found'
          ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

      

  }


 ?> 