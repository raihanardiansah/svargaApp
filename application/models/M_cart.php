<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_cart extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Fungsi untuk menambahkan item ke keranjang
    public function add_to_cart($data) {
        // Insert the data into the cart table
        return $this->db->insert('cart', $data);
    }

    // Fungsi untuk memeriksa apakah item sudah ada di keranjang
    public function get_cart_item($user_id, $menu_id) {
        // Query untuk memeriksa apakah item dengan user_id dan menu_id sudah ada
        $this->db->where('user_id', $user_id);
        $this->db->where('menu_id', $menu_id);
        $query = $this->db->get('cart');
        
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
    }

    // Fungsi untuk memperbarui kuantitas item ketika menu sudah ada di keranjang
    public function update_cart_item($user_id, $menu_id, $new_quantity) {
        // Update kuantitas item di keranjang
        $this->db->set('quantity', $new_quantity);
        $this->db->where('user_id', $user_id);
        $this->db->where('menu_id', $menu_id);
        
        // Menjalankan query update
        if ($this->db->update('cart')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // Fungsi untuk mengambil semua item di keranjang
    public function get_cart_items($user_id) {
        // Mengambil data cart dan menu yang digabungkan
        $this->db->select('cart.cart_id, cart.menu_id, cart.quantity, cart.added_at, menu.name, menu.price, menu.image');
        $this->db->from('cart');
        $this->db->join('menu', 'cart.menu_id = menu.menu_id'); // Join berdasarkan menu_id
        $this->db->where('cart.user_id', $user_id);
        $query = $this->db->get();

        return $query->result_array();
    }

    // Fungsi untuk memperbarui kuantitas item ketika user menambah kuantitas
    public function update_cart_item_quantity($cart_id, $user_id, $menu_id, $quantity) {
        // Update kuantitas item di keranjang
        $this->db->set('quantity', $quantity);
        $this->db->where('cart_id', $cart_id);
        $this->db->where('user_id', $user_id);
        $this->db->where('menu_id', $menu_id);
        
        // Menjalankan query update
        if ($this->db->update('cart')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    // Fungsi untuk menghapus item dari keranjang
    public function remove_cart_item($user_id, $cart_id) {
        // Menghapus item berdasarkan user_id dan cart_id
        $this->db->where('user_id', $user_id);
        $this->db->where('cart_id', $cart_id);
        $this->db->delete('cart'); 
    
        // Cek apakah penghapusan berhasil
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    // Fungsi untuk membuat pesanan
public function create_order($order_data) {
    // Memasukkan data pesanan ke tabel orders
    $this->db->insert('orders', $order_data);
    
    // Mengembalikan ID pesanan yang baru dibuat
    return $this->db->insert_id();
}

// Fungsi untuk menambahkan item pesanan ke tabel order_items
public function add_order_item($order_item_data) {
    // Memasukkan item pesanan ke tabel order_items
    return $this->db->insert('order_items', $order_item_data);
}

// Fungsi untuk mengosongkan keranjang setelah pesanan dibuat
public function clear_cart($user_id) {
    // Menghapus semua item di keranjang untuk user tertentu
    $this->db->where('user_id', $user_id);
    $this->db->delete('cart');
    
    // Mengembalikan true jika ada perubahan
    return $this->db->affected_rows() > 0;
}

}
