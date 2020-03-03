<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Login extends REST_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('Users_model');
    }

    public function getUser_post(){
        $username = $this->post('username');
        $password = $this->post('password');

        $login_check = $this->Users_model->get_login($username, $password);
        if($login_check){
            $this->response([
                'status' => true,
                'data' => $login_check
                ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => false,
                'message' => 'Login Failed'
                ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}