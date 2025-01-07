<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . 'libraries/REST_Controller.php');

class Cart extends REST_Controller {

    public function __construct() {
        parent::__construct();
        // Memuat model cart
        $this->load->model('M_cart');
    }

    //menambahkan item ke keranjang
    public function add_to_cart_post() {
    // Get data from POST request
    $user_id = $this->post('user_id');
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
                    // Jika berhasil memperbarui kuantitas
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Ditambahkan Ke Keranjang'
                    ], REST_Controller::HTTP_OK);
                } else {
                    // Jika gagal memperbarui kuantitas
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Gagal Menambahkan Ke Keranjang'
                    ], REST_Controller::HTTP_BAD_REQUEST);
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
                    // Jika berhasil
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Ditambahkan Ke Keranjang'
                    ], REST_Controller::HTTP_CREATED);
                } else {
                    // Jika gagal
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Gagal Menambahkan Ke Keranjang'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            }
        } else {
            // Jika input tidak valid
            $this->response([
                'status' => FALSE,
                'message' => 'input tidak valid. User ID, Menu ID, dan Quantity harus diisi.'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    //menampilkan semua item di keranjang
    public function index_get($user_id) {
        $cart_items = $this->M_cart->get_cart_items($user_id);
        $this->response($cart_items, REST_Controller::HTTP_OK);
    }


    //mengupdate item di keranjang ketika user menambah kuantitas
    public function index_put($cart_id) {
        $user_id = $this->put('user_id');       
        $menu_id = $this->put('menu_id');
        $new_quantity = $this->put('quantity');
        
        // Panggil model untuk update item
        $update = $this->M_cart->update_cart_item_quantity($cart_id, $user_id, $menu_id, $new_quantity);
        
        // Cek apakah update berhasil
        if ($update) {
            // Mengembalikan response dengan quantity yang diperbarui
            $this->response([
                'status' => TRUE,
                'message' => 'Quantity updated successfully = ' . $new_quantity . ' for cart item with ID = ' . $cart_id,
                'quantity' => $new_quantity
            ], REST_Controller::HTTP_OK);
        } else {
            // Jika gagal
            $this->response([
                'status' => FALSE,
                'message' => 'Failed to update quantity'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    //menghapus item di keranjang
    public function index_delete($user_id, $cart_id) {
        // Panggil model untuk update item
        $update = $this->M_cart->remove_cart_item($user_id, $cart_id);
    
        // Cek apakah update berhasil
        if ($update) {
            // Jika berhasil
            $this->response([
                'status' => TRUE,
                'message' => 'Item berhasil dihapus'
            ], REST_Controller::HTTP_OK);
        } else {
            // Jika gagal
            $this->response([
                'status' => FALSE,
                'message' => 'Gagal menghapus item'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }}