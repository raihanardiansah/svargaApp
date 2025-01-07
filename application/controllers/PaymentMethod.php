<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class PaymentMethod extends REST_Controller{
    //declare constructor
    function __construct() {
    parent::__construct();
    $this->load->model('Payment_method_model');
    }
    
    //menampilkan data menu
    function index_get(){
        //call function getPayment from model 
        $data = $this->Payment_method_model->getPaymentMethod();
        $result = $data;
        //show response 
        $this->response($result, REST_Controller:: HTTP_OK);
    }

    
}