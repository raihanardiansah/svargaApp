<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Menu extends REST_Controller{
    //declare constructor
    function __construct() {
    parent::__construct();
    $this->load->model('Menu_model');
    }
    //function get/show data food
    function index_get(){
        //call function getFood from model 
        $data = $this->Menu_model->getMenu();
        $result = $data;
        //show response 
        $this->response($result, REST_Controller:: HTTP_OK);
    }
}