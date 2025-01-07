<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . 'libraries/REST_Controller.php');

class Orders extends REST_Controller {

    public function __construct() {
        parent::__construct();
        // Memuat model cart
        $this->load->model('M_orders');
    }



    public function index_post() {
        // Get data from POST request
        $user_id = $this->post('user_id');
        $total = $this->post('total');
        $location = $this->post('location');
        
        // Validasi input
        if ($user_id && $total) {
            // Menyimpan data pesanan
            $order_data = array(
                'user_id' => $user_id,
                'order_date' => date('Y-m-d H:i:s'),
                'status' => 'belum bayar', // Status default
                'total' => $total,
                'location' => $location
            );
        
            // Memasukkan pesanan ke database
            $order_id = $this->M_orders->create_order($order_data);
        
            if ($order_id) {
                // Mengambil semua item keranjang untuk user tersebut
                $cart_items = $this->M_orders->get_cart_items($user_id);
        
                // Menambahkan item ke dalam order_items
                foreach ($cart_items as $item) {
                    $subtotal = $item['price'] * $item['quantity'];  // Menghitung subtotal berdasarkan harga dan quantity
                    
                    $order_item_data = array(
                        'order_id' => $order_id,
                        'menu_id' => $item['menu_id'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $subtotal
                    );
        
                    // Menambahkan item pesanan
                    $this->M_orders->add_order_item($order_item_data);
                }
        
                // Mengosongkan keranjang setelah order dibuat
                $this->M_orders->clear_cart($user_id);
        
                // Mengirim response sukses
                $this->response([
                    'status' => TRUE,
                    'message' => 'Order created successfully',
                    'order_id' => $order_id
                ], REST_Controller::HTTP_OK);
            } else {
                // Jika gagal membuat order
                $this->response([
                    'status' => FALSE,
                    'message' => 'Failed to create order'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            // Jika input tidak valid
            $this->response([
                'status' => FALSE,
                'message' => 'User ID and Total are required.'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}