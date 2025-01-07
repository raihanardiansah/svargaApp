<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_method_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Fungsi untuk mengambil semua data menu
    public function getPaymentMethod() {
        $data = $this->db->get('payment_method');
        return $data->result_array();
    }
}
?>