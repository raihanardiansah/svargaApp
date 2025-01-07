<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Discount extends REST_Controller{
    //declare constructor
    function __construct() {
    parent::__construct();
    $this->load->model('Discount_model');
    }
    
    //menampilkan data menu
    function index_get(){
        //call function getPayment from model 
        $data = $this->Discount_model->getdiscount();
        $result = $data;
        //show response 
        $this->response($result, REST_Controller:: HTTP_OK);
    }

    
}