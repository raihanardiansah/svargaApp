<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Fungsi untuk mengambil semua data menu
    public function getMenu() {
        $data = $this->db->get('menu');
        return $data->result_array();
    }
}
?>