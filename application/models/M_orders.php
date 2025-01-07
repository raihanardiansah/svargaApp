<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_orders extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();  // Memuat database
    }


    public function create_order($order_data) {
        // Memasukkan data pesanan ke tabel orders
        $this->db->insert('order', $order_data);

        // Mengembalikan ID order yang baru dibuat
        return $this->db->insert_id();
    }


    public function get_cart_items($user_id) {
        // Mengambil data item keranjang dari tabel cart_items untuk user tertentu
        $this->db->select('cart.menu_id, cart.quantity, menu.price');
        $this->db->from('cart');
        $this->db->join('menu', 'menu.menu_id = cart.menu_id'); // Gabungkan dengan tabel menu untuk mendapatkan harga
        $this->db->where('cart.user_id', $user_id);
        $query = $this->db->get();
    
        // Mengembalikan hasil query sebagai array
        return $query->result_array();
    }
    

    public function add_order_item($order_item_data) {
        // Memasukkan data item pesanan ke tabel order_items
        return $this->db->insert('order_item', $order_item_data);
    }

    public function clear_cart($user_id) {
        // Menghapus semua item keranjang untuk user tertentu
        $this->db->where('user_id', $user_id);
        return $this->db->delete('cart');
    }
}
?>
