<?php

namespace App\Models;

use CodeIgniter\Database\Exceptions\DatabaseException;


class signup_m extends core_m
{
    public function index()
    {

        $data = array();
        $icon = "";
        $nama = "";
        $identity = $this->db->table("identity")->get();
        foreach ($identity->getResult() as $identity) {
            $icon = $identity->identity_logo;
            $nama = $identity->identity_name;
        }
        $masuk = 0;
        $data["icon"] = $icon;
        $data["nama"] = $nama;
        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create') {
                    $inputu[$e] = $this->request->getPost($e);
                }
            }

            // Kunci dan metode enkripsi
            $key = "ihsandulu123456"; // Kunci rahasia (jangan hardcode di produksi)
            $method = "AES-256-CBC";
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));

            // Enkripsi
            $password = $inputu["user_password"];
            $encrypted = openssl_encrypt($password, $method, $key, 0, $iv);
            $encrypted = base64_encode($iv . $encrypted); // Gabungkan IV agar bisa didekripsi nanti
            $inputu["user_password"] = $encrypted;
            try {
                $inputu["position_id"]=2;
                $this->db->table('user')->insert($inputu);
                $pesan = 'Insert berhasil';
                $masuk = 1;
            } catch (DatabaseException $e) {
                $masuk = 0;
                // Ambil error MySQL
                if ($e->getCode() == 1062) {
                    $pesan = 'Email sudah terdaftar';
                } else {
                    $pesan = 'Terjadi kesalahan database';
                }
            }
            $data["message"] = $pesan;
        } else {
            $data["message"] = "Selamat Datang di " . $nama;
            $masuk = 0;
        }
        $data['masuk'] = $masuk;

        $this->session->setFlashdata('message', $data["message"]);
        return $data;
    }
}
