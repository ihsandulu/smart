<?php

namespace App\Controllers;

use phpDocumentor\Reflection\Types\Null_;
use CodeIgniter\API\ResponseTrait;

class api extends BaseController
{
    use ResponseTrait;

    protected $sesi_user;
    protected $db;
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $sesi_user = new \App\Models\global_m();
        $sesi_user->ceksesi();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        echo "Page Not Found!";
    }

    public function saveToken()
    {
        // Ambil data POST (Cordova / Android)
        $user_id   = $this->request->getPost('user_id');
        $fcm_token = $this->request->getPost('fcm_token');
        $platform  = $this->request->getPost('platform') ?? 'android';

        // Validasi minimal
        if (!$user_id || !$fcm_token) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'user_id dan fcm_token wajib diisi'
            ]);
        }

        // Cek token sudah ada atau belum
        $exists = $this->db->table('fcmtokens')
            ->where('user_id', $user_id)
            ->where('fcm_token', $fcm_token)
            ->get()
            ->getRow();

        if (!$exists) {
            $this->db->table('fcmtokens')->insert([
                'user_id'    => $user_id,
                'fcm_token'  => $fcm_token,
                'platform'   => $platform,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'FCM token berhasil disimpan'
        ]);
    }
}
