<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Fungsi untuk melakukan login
    // Account_model.php
    public function login($email, $password) {
        $this->db->where('email', $email);
        $data = $this->db->get('account');
        $user = $data->row_array();

        // Jika username ditemukan, cek password
        if ($user && password_verify($password, $user['password'])) {
            return $user;
            
        }
        return null;
    }


    // Fungsi untuk memeriksa apakah username sudah terdaftar
    public function checkUsernameExists($name) {
        $this->db->where('name', $name);
        $data = $this->db->get('account');
        return $data->row_array();
    }

    // Fungsi untuk memeriksa apakah email sudah terdaftar
    public function checkEmailExists($email) {
        $this->db->where('email', $email);
        $data = $this->db->get('account');
        return $data->row_array();
    }

    // Fungsi untuk melakukan registrasi
    public function registerUser($name, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'level' => 'User',
            'location' => 'Yogyakarta',
        ];

        // Menyimpan data ke database
        $this->db->insert('account', $data);
        return $this->db->insert_id();
    }

    // Mendapatkan data pengguna berdasarkan ID
    public function getUserById($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('account');
        return $query->row_array();
    }

    // Memperbarui data profil pengguna
    public function updateUserProfile($user_id, $data) {
        $this->db->where('user_id', $user_id);
        return $this->db->update('account', $data);
    }

}
?>