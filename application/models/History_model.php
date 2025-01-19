<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class History_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getAllHistory($user_id) {
        $this->db->select("
            o.order_id, 
            DATE_FORMAT(o.order_date, '%d-%m-%Y %H:%i') AS order_date,
            o.status, 
            o.total, 
            m.name, 
            m.image,
            (SELECT COUNT(*) FROM order_item oi WHERE oi.order_id = o.order_id) - 1 as additional_items
        ");
        $this->db->from('order o');
        $this->db->join('order_item oi', 'o.order_id = oi.order_id', 'inner');
        $this->db->join('menu m', 'oi.menu_id = m.menu_id', 'inner');
        $this->db->where('o.user_id', $user_id);
        $this->db->group_by('o.order_id');
        $this->db->order_by('o.order_date', 'DESC');
        $query = $this->db->get();

        return $query->result_array();
    }
}
