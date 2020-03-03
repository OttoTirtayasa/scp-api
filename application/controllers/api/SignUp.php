<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class SignUp extends REST_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('Users_model');
    }

    public function setUsers_post(){
        $nama = $this->post('nama');
        $username = $this->post('username');
        $password = $this->post('password');
        $alamat = $this->post('alamat');
        $telepon = $this->post('telepon');
        $role = $this->post('role');

        $signup_check = $this->Users_model->post_signup($nama, $username, $password , $alamat, $telepon, $role);
        if ($signup_check) {
            $this->response([
                'status' => true,
                'data' => $signup_check,
                ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => false,
                'message' => 'SignUp Failed'
                ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}