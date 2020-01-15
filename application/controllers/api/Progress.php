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
      $this->load->model('Progress_model');
    }

    public function index_get(){
      $paket_id = $this->get('pa_id');

      if($paket_id == null){
        $progress = $this->Progress_model->get_progress();
      }else{
        $progress = $this->Progress_model->get_progress($paket_id);
      }

      if($progress){
          $this->response([
            'status' => true,
            'data' => $progress
        ], REST_Controller::HTTP_OK);
      }else{
          $this->response([
            'status' => false,
            'message' => 'Progress Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function fisik_get(){
      $paket_id = $this->get('pa_id');

      if($paket_id == null){
        $progress = $this->Progress_model->get_fisik();
      }else{
        $progress = $this->Progress_model->get_fisik($paket_id);
      }

      if($progress){
          $this->response([
            'status' => true,
            'data' => $progress
        ], REST_Controller::HTTP_OK);
      }else{
          $this->response([
            'status' => false,
            'message' => 'Progress Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function keuangan_get(){
      $paket_id = $this->get('pa_id');
      $progress = $this->Progress_model->get_keuangan($paket_id);
      if($progress){
          $this->response([
            'status' => true,
            'data' => $progress
        ], REST_Controller::HTTP_OK);
      }else{
          $this->response([
            'status' => false,
            'message' => 'Progress Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function keuangansort_get(){
      $pa_id = $this->get('pa_id');
      $query = "SELECT b.*
                FROM 
                sc_paket a,
                sc_progress b,
                sc_kegiatan c,
                dinas d
                WHERE
                d.dinas_id = c.dinas_id AND
                c.ke_id = a.ke_id AND 
                d.dinas_id = $dinas_id AND
                b.pa_id = a.pa_id AND
                b.pa_id = $pa_id AND
                b.pr_jenis = 'Keuangan'
                ORDER BY b.pr_tanggal ASC";
      $q_result = $this->db->query($query)->result_array();
      if($q_result){
        $this->response([
          'status' => true,
          'data' => $q_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Paket not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function prog_daya_serap_get(){
      $pa_id = $this->get('pa_id');
      $query = "SELECT SUM(b.pr_daya_serap_kontrak) jumlah
                FROM 
                sc_paket a,
                sc_progress b
                WHERE
                b.pa_id = $pa_id AND
                b.pa_id = a.pa_id AND
                b.pr_jenis = 'Keuangan'";
      $q_result = $this->db->query($query)->result_array();
      if($q_result){
        $this->response([
          'status' => true,
          'data' => $q_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Paket not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function bidang_daya_serap_get(){
      $bi_id = $this->get('bi_id');
      $dinas_id = $this->get('dinas_id');

      $query = "SELECT SUM(b.pr_daya_serap_kontrak) jumlah
                FROM 
                sc_paket a,
                sc_kegiatan c,
                dinas d,
                sc_progress b
                WHERE
                d.dinas_id = c.dinas_id AND
                c.bi_id = $bi_id AND
                d.dinas_id = $dinas_id AND
                c.ke_id = a.ke_id AND
                a.pa_id = b.pa_id AND
                b.pr_jenis = 'Keuangan'";
      $q_result = $this->db->query($query)->result_array();
      if($q_result){
        $this->response([
          'status' => true,
          'data' => $q_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Paket not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function paketall(){
      $user_id = $this->get('pa_id');
      
    }

    public function index_delete(){
      $id = $this->delete('pr_id');
      if($id == null){
        $this->response([
            'status' => false,
            'message' => 'Provide an paket id!'
        ], REST_Controller::HTTP_NOT_FOUND);
      }else{
        if($this->Progress_model->delete_progress($id) > 0){
            // ok
            $this->response([
              'status' => true,
              'progress_id' => $progress_id,
              'message' => 'Deleted'
          ], REST_Controller::HTTP_OK);
        }else{
           // not ok id not found
           $this->response([
              'status' => false,
              'message' => 'Progress Id not found!'
          ], REST_Controller::HTTP_NOT_FOUND);
        }
      }
    }

    public function progressf_post(){
      $pa_id = $this->post('pa_id');
      $pr_target = $this->post('pr_target');
      $pr_real = $this->post('pr_real'); 
      $pr_deviasi = $this->post('pr_deviasi');
      $pr_tanggal = $this->post('pr_tanggal');
      $pr_jenis = 'Fisik';

      $data = array(
        'pa_id' => $pa_id,
        'pr_target' => $pr_target,
        'pr_real' => $pr_real,
        'pr_deviasi' => $pr_deviasi,
        'pr_tanggal' => $pr_tanggal,
        'pr_jenis' => $pr_jenis
      );

      $this->db->insert('sc_progress', $data);
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

    public function progressk_post(){
      $pa_id = $this->post('pa_id');
      $ke_id = $this->post('ke_id');
      $pr_daya_serap_kontrak = $this->post('pr_daya_serap_kontrak');
      $pr_tanggal = $this->post('pr_tanggal');
      $pr_keterangan = $this->post('pr_keterangan');
      $pr_jenis = 'Keuangan';

      $data = array(
        'pa_id' => $pa_id,
        'pr_daya_serap_kontrak' => $pr_daya_serap_kontrak,
        'pr_tanggal' => $pr_tanggal,
        'pr_keterangan' => $pr_keterangan,
        'pr_jenis' => $pr_jenis,
        'ke_id' => $ke_id
      );

      $this->db->insert('sc_progress', $data);
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

    public function editprogressf_post(){
      $pa_id = $this->post('pa_id');
      $pr_target = $this->post('pr_target');
      $pr_real = $this->post('pr_real'); 
      $pr_deviasi = $this->post('pr_deviasi');
      $pr_tanggal = $this->post('pr_tanggal');
      $pr_jenis = 'Fisik';

      $data = array(
        'pr_target' => $pr_target,
        'pr_real' => $pr_real,
        'pr_deviasi' => $pr_deviasi,
        'pr_tanggal' => $pr_tanggal,
        'pr_jenis' => $pr_jenis
      );

      $pack = array(
        'pa_id' => $pa_id,
        'pr_target' => $pr_target,
        'pr_real' => $pr_real,
        'pr_deviasi' => $pr_deviasi,
        'pr_tanggal' => $pr_tanggal,
        'pr_jenis' => $pr_jenis
      );

      $this->db->where('pa_id', $pa_id);
      $this->db->update('sc_progress', $data);
      if($this->db->affected_rows() == 1 ){
          $this->response([
            'status' => true,
            'data' => $pack
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Progress not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }


    public function addprogressf_post(){
      $pa_id = $this->post('pa_id');
      $ke_id = $this->post('ke_id');
      $pr_target = $this->post('pr_target');
      $pr_real = $this->post('pr_real'); 
      $pr_deviasi = $this->post('pr_deviasi');
      $pr_tanggal = $this->post('pr_tanggal');
      $pr_jenis = 'Fisik';


      $data = array(
        'ke_id' => $ke_id,
        'pa_id' => $pa_id,
        'pr_target' => $pr_target,
        'pr_real' => $pr_real,
        'pr_deviasi' => $pr_deviasi,
        'pr_tanggal' => $pr_tanggal,
        'pr_jenis' => $pr_jenis
      );

      $this->db->insert('sc_progress', $data);
      if($this->db->affected_rows() == 1 ){
          $this->response([
            'status' => true,
            'data' => $data
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Progress not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }


    public function editprogressk_post(){
      $pa_id = $this->post('pa_id');
      $pr_daya_serap_kontrak = $this->post('pr_daya_serap_kontrak');
      $pr_tanggal = $this->post('pr_tanggal');
      $pr_keterangan = $this->post('pr_keterangan');
      $pr_jenis = 'Keuangan';

      $data = array(
        'pr_daya_serap_kontrak' => $pr_daya_serap_kontrak,
        'pr_tanggal' => $pr_tanggal,
        'pr_keterangan' => $pr_keterangan,
        'pr_jenis' => $pr_jenis
      );

      $pack = array(
        'pa_id' => $pa_id,
        'pr_daya_serap_kontrak' => $pr_daya_serap_kontrak,
        'pr_tanggal' => $pr_tanggal,
        'pr_keterangan' => $pr_keterangan,
        'pr_jenis' => $pr_jenis
      );

      $this->db->where('pa_id', $pa_id);
      $this->db->update('sc_progress', $data);
      if($this->db->affected_rows() == 1 ){
          $this->response([
            'status' => true,
            'data' => $pack
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Progress not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }


    public function progresslast_get(){
      $pa_id = $this->get('pa_id');
      $query = "SELECT a.pr_real, a.pr_target, a.pr_deviasi, a.pr_tanggal FROM sc_progress a WHERE a.pa_id = $pa_id AND a.pr_jenis = 'Fisik' order by a.pr_id DESC LIMIT 1";
      $q_result = $this->db->query($query)->result_array();
      if($q_result){
        $this->response([
            'status' => true,
            'data' => $q_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Progress Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function getlastprogressall_get(){
      $pa_id = $this->get('pa_id');
      $query = "SELECT *
                from sc_progress a 
                where a.pa_id = $pa_id
                and a.pr_jenis = 'Fisik'
                ORDER BY a.pr_real ASC limit 1";
      $q_result = $this->db->query($query)->result_array();
      if($q_result){
        $this->response([
            'status' => true,
            'data' => $q_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Progress Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

  }

?>

