<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discount_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Fungsi untuk mengambil semua data menu
    public function getDiscount() {
        $data = $this->db->get('discount');
        return $data->result_array();
    }
}
?>