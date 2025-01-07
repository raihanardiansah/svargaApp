<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_model extends CI_Model {

    // Function to create a new payment record
    public function create_payment($payment_data) {
        $this->db->insert('payment', $payment_data);
        return $this->db->insert_id();
    }

    // Function to update the order status
    public function update_order_status($order_id, $status) {
        $this->db->set('status', $status);
        $this->db->where('order_id', $order_id);
        return $this->db->update('order');
    }

    // Function to update the total in the order table
    public function update_order_total($order_id, $new_total) {
        // Mengambil data order berdasarkan order_id
        $this->db->set('total', $new_total);
        $this->db->where('order_id', $order_id);
        return $this->db->update('order');
    }
}
