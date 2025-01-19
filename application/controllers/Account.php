<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Account extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('account_model');
    }

    //Login
    function login_post() {
        $email = $this->post('email');
        $password = $this->post('password');
        $data = $this->account_model->login($email, $password);

        // Account.php
        if (empty($data)) {
            $output = array(
                'success' => false,
                'message' => 'Username atau Password Salah, Silahkan Coba Lagi',
                'data' => null
            );
        } else {
            $output = array(
                'success' => true,
                'message' => 'Login Berhasil',
                'data' => $data
            );
        }
        $this->response($output, REST_Controller::HTTP_OK);
    }

    // Fungsi untuk menangani request registrasi
    function register_post() {
        // Mendapatkan data dari request
        $name = $this->post('name');
        $email = $this->post('email');
        $password = $this->post('password');
        $confirmPassword = $this->post('confirm_password');

        // Validasi data input
        if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
            $this->response([
                'success' => false,
                'message' => 'Semua kolom harus diisi',
                'data' => null
            ], REST_Controller::HTTP_OK);
            return;
        }

        // Validasi format email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->response([
                'success' => false,
                'message' => 'Email tidak valid',
                'data' => null
            ], REST_Controller::HTTP_OK);
            return;
        }

        // Validasi apakah username sudah ada
        if ($this->account_model->checkUsernameExists($name)) {
            $this->response([
                'success' => false,
                'message' => 'Username sudah terdaftar',
                'data' => null
            ], REST_Controller::HTTP_OK);
            return;
        }

        // Validasi apakah email sudah ada
        if ($this->account_model->checkEmailExists($email)) {
            $this->response([
                'success' => false,
                'message' => 'Email sudah terdaftar',
                'data' => null
            ], REST_Controller::HTTP_OK);
            return;
        }

        // Validasi password minimal 6 karakter
        if (strlen($password) < 6) {
            $this->response([
                'success' => false,
                'message' => 'Password harus minimal 6 karakter',
                'data' => null
            ], REST_Controller::HTTP_OK);
            return;
        }

        // Validasi apakah password dan confirm password cocok
        if ($password !== $confirmPassword) {
            $this->response([
                'success' => false,
                'message' => 'Password dan Konfirmasi Password tidak cocok',
                'data' => null
            ], REST_Controller::HTTP_OK);
            return;
        }

        // Registrasi pengguna
        $user_id = $this->account_model->registerUser($name, $email, $password);

        if ($user_id) {
            $this->response([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'data' => ['user_id' => $user_id]
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'success' => false,
                'message' => 'Gagal mendaftar, coba lagi',
                'data' => null
            ], REST_Controller::HTTP_OK);
        }
    }

    // Fungsi untuk memperbarui profil pengguna
    public function updateProfile_put() {
        // Mendapatkan data dari request PUT
        $user_id = $this->put('user_id');  // ID pengguna yang sedang login
        $email = $this->put('email');
        $name = $this->put('name');
        $phone_number = $this->put('phone_number');
        $password = $this->put('password');   // Password lama
        $newPassword = $this->put('newPassword'); // Password baru

        // Validasi apakah semua data penting ada
        if (empty($user_id) || empty($email) || empty($name) || empty($phone_number)) {
            $this->response([
                'success' => false,
                'message' => 'Semua kolom harus diisi',
                'data' => null
            ], REST_Controller::HTTP_OK);
            return;
        }

        // Validasi apakah password baru tidak kosong jika ingin menggantinya
        if (!empty($newPassword) && strlen($newPassword) < 6) {
            $this->response([
                'success' => false,
                'message' => 'Password baru harus minimal 6 karakter',
                'data' => null
            ], REST_Controller::HTTP_OK);
            return;
        }

        // Memeriksa apakah password lama sesuai dengan yang ada di database jika password lama diberikan
        if (!empty($password)) {
            $user_data = $this->account_model->getUserById($user_id);
            if (!$user_data || !password_verify($password, $user_data['password'])) {
                $this->response([
                    'success' => false,
                    'message' => 'Password lama salah',
                    'data' => null
                ], REST_Controller::HTTP_OK);
                return;
            }
        }

        // Jika password baru diberikan, hash password baru
        if (!empty($newPassword)) {
            $newPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        } else {
            // Jika tidak ada password baru, gunakan password lama yang sudah ada
            $user_data = $this->account_model->getUserById($user_id);
            $newPassword = $user_data['password'];  // Tidak ada perubahan password
        }

        // Update profil pengguna
        $update_data = [
            'email' => $email,
            'name' => $name,
            'phone_number' => $phone_number,
            'password' => $newPassword
        ];

        $update_result = $this->account_model->updateUserProfile($user_id, $update_data);

        if ($update_result) {
            $this->response([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'data' => null
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'success' => false,
                'message' => 'Gagal memperbarui profil, coba lagi',
                'data' => null
            ], REST_Controller::HTTP_OK);
        }
    }

}    
?>