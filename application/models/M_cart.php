<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_cart extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function add_to_cart($data) {
        // Insert the data into the cart table
        return $this->db->insert('cart', $data);
    }

    public function get_cart_item($user_id, $menu_id) {
        // Query untuk memeriksa apakah item dengan user_id dan menu_id sudah ada
        $this->db->where('user_id', $user_id);
        $this->db->where('menu_id', $menu_id);
        $query = $this->db->get('cart');
        
        if ($query->num_rows() > 0) {
            return $query->row_array(); // Mengembalikan data item yang ada
        } else {
            return null; // Tidak ada item
        }
    }

    public function update_cart_item($user_id, $menu_id, $new_quantity) {
        // Update kuantitas item di keranjang
        $this->db->set('quantity', $new_quantity);
        $this->db->where('user_id', $user_id);
        $this->db->where('menu_id', $menu_id);
        return $this->db->update('cart');
    }
    
    

    public function get_cart_items($user_id) {
        // Mengambil data cart dan menu yang digabungkan
        $this->db->select('cart.cart_id, cart.menu_id, cart.quantity, cart.added_at, menu.name, menu.price, menu.image');
        $this->db->from('cart');
        $this->db->join('menu', 'cart.menu_id = menu.menu_id'); // Join berdasarkan menu_id
        $this->db->where('cart.user_id', $user_id);
        $query = $this->db->get();

        return $query->result_array();
    }

    
    
    
}
