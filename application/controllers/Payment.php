<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . 'libraries/REST_Controller.php');

class Payment extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Payment_model');
        $this->load->database();
    }

    // POST: /payment
    public function create_post() {
        $payment_data = $this->post();

        // Pastikan data yang dibutuhkan ada
        if (!isset($payment_data['order_id']) || !isset($payment_data['id_discount']) || 
            !isset($payment_data['id_method']) || !isset($payment_data['payment_status']) || 
            !isset($payment_data['payment_date']) || !isset($payment_data['total']) ||
            !isset($payment_data['status'])) {
            $this->response(['error' => 'Missing required parameters'], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        // Persiapkan data untuk dimasukkan ke database
        $payment_data_to_insert = array(
            'order_id' => $payment_data['order_id'],
            'id_discount' => $payment_data['id_discount'],
            'id_method' => $payment_data['id_method'],
            'payment_status' => $payment_data['payment_status'],
            'payment_date' => date('Y-m-d H:i:s', $payment_data['payment_date'] / 1000), // Convert timestamp to date format
        );

        // Insert data ke dalam tabel payment
        $payment_id = $this->Payment_model->create_payment($payment_data_to_insert);

        // Jika berhasil menyimpan pembayaran, update status order dan total
        if ($payment_id) {
            // Mengupdate status order menjadi status yang diterima dari parameter
            $this->Payment_model->update_order_status($payment_data['order_id'], $payment_data['status']);

            // Mengupdate total di tabel order
            $this->Payment_model->update_order_total($payment_data['order_id'], $payment_data['total']);

            // Mengembalikan respons berhasil
            $this->response(['message' => 'Payment created successfully', 'payment_id' => $payment_id], REST_Controller::HTTP_OK);
        } else {
            // Jika gagal, mengembalikan respons gagal
            $this->response(['error' => 'Failed to create payment'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
