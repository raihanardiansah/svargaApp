<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Account extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('account_model');
    }

    function index_post() {
        $username = $this->post('username');
        $password = $this->post('password');
        $data = $this->account_model->login($username, $password);
        // $this->response($data, REST_Controller::HTTP_OK);
        if (empty($data)){
            $output = array(
                'success' => false,
                'message' => 'Username or Password is incorrect',
                'data' => null
            );
            $this->response($output, REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        } else {
            $result = $data;
            $output = array(
                'success' => true,
                'message' => 'Login Success',
                'data' => $data
            );
            $this->response($output, REST_Controller::HTTP_OK);
        }
    }


}    
?>