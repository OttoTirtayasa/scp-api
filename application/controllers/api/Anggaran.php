<?php
  // API for retrieve Anggaran
  use Restserver\Libraries\REST_Controller;
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  defined('BASEPATH') OR exit('No direct script access allowed');
  require APPPATH . 'libraries/REST_Controller.php';
  require APPPATH . 'libraries/Format.php';

  class Anggaran extends REST_Controller {
    
    public function __construct(){
      parent::__construct();
      $this->load->model('Anggaran_model');
    }

    public function index_get(){
      $anggaran = $this->Anggaran_model->get_dinas();
      if($anggaran){
          $this->response([
            'status' => true,
            'data' => $anggaran
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
            'status' => false,
            'message' => 'Users Not Found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
      
    }

    public function anggaran_get(){
      $an_id = $this->get('an_id');
      $query = "SELECT * FROM sc_anggaran where an_id = $an_id";
      $query_result = $this->db->query($query)->result_array();
      if($query_result){
        $this->response([
          'status' => true,
          'data' => $query_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,  
          'message' => 'Data not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function index_post(){
      $ke_id = $this->post('ke_id');
      $pptk_id = $this->post('pptk_id');
      $an_nama = $this->post('an_nama');
      $an_norekening = $this->post('an_norekening');
      $an_tahunanggaran = $this->post('an_tahunanggaran');
      $anp_pagu = $this->post('anp_pagu');

      $data = array(
          "ke_id" => $ke_id,
          "pptk_id" => $pptk_id,
          "an_nama" => $an_nama,
          "an_norekening" => $an_norekening,
          "an_tahunanggaran" => $an_tahunanggaran,
          "anp_pagu" => $anp_pagu
      );

      $this->db->insert('sc_anggaran', $data);
      if($this->db->affected_rows() == 1 ){
          $this->response([
            'status' => true,
            'data' => $data
        ], REST_Controller::HTTP_CREATED);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Input Anggaran Failed'
        ], REST_Controller::HTTP_BAD_REQUEST);
      }
    }

    public function pptk_get(){
      $pptk_id = $this->get('pptk_id');
      $query_result = $this->Anggaran_model->get_anggaran_pptkuser($pptk_id);
      if($query_result){
        $this->response([
            'status' => true,
            'data' => $query_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,  
          'message' => 'Data not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function bidang_get(){
      $dinas_id = $this->get('dinas_id');
      $bi_id = $this->get('bi_id');
      $query = "SELECT c.* FROM 
              dinas a,
              sc_kegiatan b,
              sc_anggaran c
              WHERE
              a.dinas_id = $dinas_id AND
              a.dinas_id = b.dinas_id AND
              b.bi_id = $bi_id AND
              c.ke_id = b.ke_id";
      $query_result = $this->db->query($query)->result_array();
      if($query_result){
        $this->response([
          'status' => true,
          'data' => $query_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Data not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function dinas_get(){
      $dinas_id = $this->get('dinas_id');
      $query = "SELECT c.* FROM  dinas a, sc_kegiatan b,  sc_anggaran c
                   WHERE a.dinas_id = b.dinas_id AND
                   b.ke_id = c.ke_id and a.dinas_id = $dinas_id";
      $query_result = $this->db->query($query)->result_array();
      if($query_result){
        $this->response([
          'status' => true,
          'data' => $query_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Data not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function serapan_get(){
      $an_id = $this->get('an_id');
      $query = "SELECT * FROM sc_serapan a where a.an_id = $an_id";
      $query_result = $this->db->query($query)->result();
      if($query_result){
        $this->response([
          'status' => true,
          'data' => $query_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Data not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function serapan_sum_get(){
      $an_id = $this->get('an_id');
      $query = "SELECT SUM(b.se_daya_serap) jumlah
                FROM 
                sc_anggaran a,
                sc_serapan b
                WHERE
                b.an_id = $an_id AND
                b.an_id = a.an_id";
      $query_result = $this->db->query($query)->result();
      if($query_result){
        $this->response([
          'status' => true,
          'data' => $query_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Data not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function serapan_bidang_sum_get(){
      $bi_id = $this->get('bi_id');
      $dinas_id = $this->get('dinas_id');
      $query = "SELECT SUM(b.se_daya_serap) jumlah
                FROM 
                sc_anggaran a,
                sc_serapan b,
                dinas c,
                sc_kegiatan d
                WHERE
                c.dinas_id = d.dinas_id AND
                c.dinas_id = $dinas_id AND
                d.bi_id = $bi_id AND
                a.ke_id = d.ke_id AND
                b.an_id = a.an_id";
      $query_result = $this->db->query($query)->result();
      if($query_result){
        $this->response([
          'status' => true,
          'data' => $query_result
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Data not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function serapan_post(){
      $an_id            = $this->post('an_id');
      $se_daya_serap    = $this->post('se_daya_serap');
      $se_sisa          = $this->post('se_sisa');
      $se_tanggal       = $this->post('se_tanggal');
      $se_keterangan    = $this->post('se_keterangan');
      $ke_id            = $this->post('ke_id');
      $se_jenis         = 'Keuangan';

      $data = array(
          "an_id" => $an_id,
          "se_daya_serap" => $se_daya_serap,
          "se_sisa" => $se_sisa,
          "ke_id" => $ke_id,
          "se_tanggal" => $se_tanggal,
          "se_keterangan" => $se_keterangan,
          "se_jenis" => $se_jenis
      );

      // perbaikan
      $this->db->insert('sc_serapan', $data);
      if($this->db->affected_rows() == 1 ){
          $this->response([
            'status' => true,
            'data' => $data
        ], REST_Controller::HTTP_CREATED);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Input Serapan Failed'
        ], REST_Controller::HTTP_BAD_REQUEST);
      }
    }

    public function serapan_delete_post(){
      $se_id = $this->post('se_id');

      $data = array(
        'se_id' => $se_id
      );

      $this->db->where('se_id', $se_id);
      $this->db->delete('sc_serapan', $data);

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

    public function anggaran_kontrak_post(){
      $an_id = $this->post('an_id');
      $an_nomor_kontrak = $this->post('an_nomor_kontrak');
      $an_nilai_kontrak = $this->post('an_nilai_kontrak');
      $an_awal_kontrak = $this->post('an_awal_kontrak');
      $an_akhir_kontrak = $this->post('an_akhir_kontrak');

      $data = array(
          "an_id" => $an_id,
          "an_nomor_kontrak" => $an_nomor_kontrak,
          "an_nilai_kontrak" => $an_nilai_kontrak,
          "an_awal_kontrak" => $an_awal_kontrak,
          "an_akhir_kontrak" => $an_akhir_kontrak
      );

      $this->db->where('an_id', $an_id);
      $this->db->update('sc_anggaran', $data);
      if($this->db->affected_rows() == 1 ){
          $this->response([
            'status' => true,
            'data' => $data
        ], REST_Controller::HTTP_CREATED);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Input Catatan Failed'
        ], REST_Controller::HTTP_BAD_REQUEST);
      }
    }

    public function penyediajasa_post(){
      $an_id = $this->post('an_id');
      $ko_id = $this->post('ko_id');

      $data = array(
        'an_id' => $an_id,
        'ko_id' => $ko_id
      );
      $this->db->where('an_id', $an_id);
      $this->db->update('sc_anggaran', $data);
      if($this->db->affected_rows() == 1 ){
          $this->response([
            'status' => true,
            'data' => $data
        ], REST_Controller::HTTP_OK);
      }else{
        $this->response([
          'status' => false,
          'message' => 'Anggaran not found'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }
    

  }

?>

