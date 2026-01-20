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
        $request = $this->request;

        // Ambil data POST
        $user_id   = $request->getPost('user_id');
        $token     = $request->getPost('fcmtokens_token');
        $platform  = $request->getPost('fcmtokens_platform') ?? 'android';

        // Validasi wajib
        if (!$user_id || !$token) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'user_id dan token wajib diisi'
            ])->setStatusCode(400);
        }

        $db = $this->db;
        $builder = $db->table('fcmtokens');

        // Cek apakah token sudah ada
        $existing = $builder
            ->where('fcmtokens_token', $token)
            ->get()
            ->getRow();

        $now = date('Y-m-d H:i:s');

        if ($existing) {
            // UPDATE
            $builder
                ->where('fcmtokens_id', $existing->fcmtokens_id)
                ->update([
                    'user_id'               => $user_id,
                    'fcmtokens_platform'    => $platform,
                    'fcmtokens_updated_at'  => $now
                ]);

            return $this->response->setJSON([
                'status'  => true,
                'message' => 'FCM token diperbarui'
            ]);
        } else {
            // INSERT
            $builder->insert([
                'user_id'              => $user_id,
                'fcmtokens_token'      => $token,
                'fcmtokens_platform'   => $platform,
                'fcmtokens_updated_at' => $now
            ]);

            return $this->response->setJSON([
                'status'  => true,
                'message' => 'FCM token disimpan'
            ]);
        }
    }
}
