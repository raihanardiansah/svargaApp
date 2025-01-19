<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class History extends REST_Controller{
    //declare constructor
    function __construct() {
    parent::__construct();
    $this->load->model('History_model');
    }
    
    //menampilkan data menu
    function history_get($user_id){
        //call function getFood from model 
        $data = $this->History_model->getAllHistory($user_id);
        $result = $data;
        //show response 
        $this->response($result, REST_Controller:: HTTP_OK);
    }
}