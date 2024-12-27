<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');

class Cart extends REST_Controller {

    public function __construct() {
        parent::__construct();
        // Memuat model cart
        $this->load->model('M_cart');
    }

    // Endpoint untuk menambahkan item ke keranjang
    public function add_to_cart_post() {
    // Get data from POST request
    $user_id = $this->post('user_id'); // Menggunakan metode $this->post() pada REST_Controller
    $menu_id = $this->post('menu_id');
    $quantity = $this->post('quantity');

    // Validasi input
    if ($user_id && $menu_id && $quantity) {
        // Periksa apakah item sudah ada di keranjang
        $existing_item = $this->M_cart->get_cart_item($user_id, $menu_id);

        if ($existing_item) {
            // Jika item sudah ada, tambahkan kuantitasnya
            $new_quantity = $existing_item['quantity'] + $quantity;
            $update = $this->M_cart->update_cart_item($user_id, $menu_id, $new_quantity);

            if ($update) {
                // Jika berhasil memperbarui kuantitas, kembalikan status 200 (OK)
                $this->response([
                    'status' => TRUE,
                    'message' => 'Quantity updated successfully'
                ], REST_Controller::HTTP_OK); // 200
            } else {
                // Jika gagal memperbarui kuantitas, kembalikan status 400 (Bad Request)
                $this->response([
                    'status' => FALSE,
                    'message' => 'Failed to update quantity'
                ], REST_Controller::HTTP_BAD_REQUEST); // 400
            }
        } else {
            // Jika item belum ada, tambahkan item baru ke keranjang
            $data = array(
                'user_id' => $user_id,
                'menu_id' => $menu_id,
                'quantity' => $quantity,
                'added_at' => date('Y-m-d H:i:s')
            );

            // Panggil model untuk menambahkan item ke cart
            $insert = $this->M_cart->add_to_cart($data);

            if ($insert) {
                // Jika berhasil, kembalikan status 201 (Created)
                $this->response([
                    'status' => TRUE,
                    'message' => 'Item added to cart'
                ], REST_Controller::HTTP_CREATED); // 201
            } else {
                // Jika gagal, kembalikan status 400 (Bad Request)
                $this->response([
                    'status' => FALSE,
                    'message' => 'Failed to add item to cart'
                ], REST_Controller::HTTP_BAD_REQUEST); // 400
            }
        }
    } else {
        // Jika input tidak valid, kembalikan status 400 (Bad Request)
        $this->response([
            'status' => FALSE,
            'message' => 'Invalid input. Ensure all fields are provided.'
        ], REST_Controller::HTTP_BAD_REQUEST); // 400
    }
}




    public function index_get($user_id) {
        $cart_items = $this->M_cart->get_cart_items($user_id);
        $this->response($cart_items, REST_Controller::HTTP_OK);
    }

    public function update($cart_id) {
        $username = $this->put('user_id');
        $name = $this->put('name');
        $level = $this->put('level');
        $password = $this->put('password');
    }

    
    

}

       // Memeriksa apakah data ditemukan
        // if (!empty($cart_items)) {
        //     $this->response([
        //         'status' => TRUE,
        //         'message' => 'Cart items retrieved successfully',
        //         'data' => $cart_items
        //     ], REST_Controller::HTTP_OK); // 200
        // } else {
        //     $this->response([
        //         'status' => FALSE,
        //         'message' => 'No cart items found for this user'
        //     ], REST_Controller::HTTP_NOT_FOUND); // 404
        // }

