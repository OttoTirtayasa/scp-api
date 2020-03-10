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

    public function setproyek_post(){
      $data = array(
        'id_owner' => $this->post('id_owner'),
        'id_pengawas' => $this->post('id_pengawas'),
        'id_kontraktor' => $this->post('id_kontraktor'),
        'nama' => $this->post('nama'),
        'lokasi' => $this->post('lokasi'),
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

    public function pengawasbyid_post(){
      $id = $this->post('id');
      $pengawas = $this->user_model->get_pengawasbyowner($id);
      if($pengawas){
        $this->response([
          'status' => true,
          'data' => $pengawas
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Data Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function getproyekbyowner_post(){
      $id_owner = $this->post('id_owner');
      $proyek = $this->Proyek_model->get_proyek_by_owner($id_owner);
      if($proyek){
        $this->response([
          'status' => true,
          'data' => $proyek
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Data Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function getproyekbykontraktor_post(){
      $id_kontraktor = $this->post('id_kontraktor');
      $proyek = $this->Proyek_model->get_proyek_by_kontraktor($id_kontraktor);
      if($proyek){
        $this->response([
          'status' => true,
          'data' => $proyek
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Data Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function getproyekbypengawas_post(){
      $id_pengawas = $this->post('id_pengawas');
      $proyek = $this->Proyek_model->get_proyek_by_pengawas($id_pengawas);
      if($proyek){
        $this->response([
          'status' => true,
          'data' => $proyek
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Data Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function getproyekbyid_post(){
      $id = $this->post('id');
      $proyek = $this->Proyek_model->get_proyek_by_id($id);
      if($proyek){
        $this->response([
          'status' => true,
          'data' => $proyek
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