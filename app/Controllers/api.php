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
        $user_id = $this->request->getPost('user_id');
        $token   = $this->request->getPost('fcmtokens_token');
        $platform    = $this->request->getPost('fcmtokens_platform');



        if (!$user_id || !$token) {
            return $this->respond([
                'status' => false,
                'message' => 'user_id dan fcmtokens_token wajib diisi'
            ], 400);
        }

        $builder = $this->db->table('fcmtokens');
        $now = date('Y-m-d H:i:s');

        $existing = $builder
            ->where('fcmtokens_token', $token)
            ->get()
            ->getRow();

        if ($existing) {
            $builder->where('fcmtokens_id', $existing->fcmtokens_id)
                ->update([
                    'user_id' => $user_id,
                    'fcmtokens_platform' => $platform,
                    'fcmtokens_updated_at' => $now
                ]);

            return $this->respond([
                'status' => true,
                'message' => 'FCM token diperbarui'
            ]);
        }

        $builder->insert([
            'user_id' => $user_id,
            'fcmtokens_token' => $token,
            'fcmtokens_platform' => $platform,
            'fcmtokens_updated_at' => $now
        ]);

        return $this->respond([
            'status' => true,
            'message' => 'FCM token disimpan'
        ]);
    }

    public function hakakses()
    {
        $crud = $this->request->getGET("crud");
        $val = $this->request->getGET("val");
        $val = json_decode($val);
        $position_id = $this->request->getGET("position_id");
        $pages_id = $this->request->getGET("pages_id");
        $where["position_id"] = $this->request->getGET("position_id");
        $where["pages_id"] = $this->request->getGET("pages_id");
        $cek = $this->db->table('positionpages')->where($where)->get()->getNumRows();
        if ($cek > 0) {
            $input1[$crud] = $val;
            $this->db->table('positionpages')->update($input1, $where);
            echo $this->db->getLastQuery();
        } else {
            $input2["position_id"] = $position_id;
            $input2["pages_id"] = $pages_id;
            $input2[$crud] = $val;
            $this->db->table('positionpages')->insert($input2);
            echo $this->db->getLastQuery();
        }
    }

    public function saveToken1()
    {
        file_put_contents(
            WRITEPATH . 'logs/hit.txt',
            date('Y-m-d H:i:s') . " HIT\n",
            FILE_APPEND
        );

        // ✅ CORS (debug dulu)
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type');
        $this->response->setHeader('Access-Control-Allow-Methods', 'POST');

        // ⬇️ AMBIL JSON BODY
        $data = $this->request->getJSON(true);

        // Mapping data JSON
        $user_id  = $data['user_id'] ?? null;
        $token    = $data['fcmtokens_token'] ?? null;
        $platform = $data['fcmtokens_platform'] ?? 'android';

        // Validasi wajib
        if (!$user_id || !$token) {
            return $this->respond([
                'status'  => false,
                'message' => 'user_id dan fcmtokens_token wajib diisi'
            ], 400);
        }

        $builder = $this->db->table('fcmtokens');

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
                    'user_id'              => $user_id,
                    'fcmtokens_platform'   => $platform,
                    'fcmtokens_updated_at' => $now
                ]);

            return $this->respond([
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

            return $this->respond([
                'status'  => true,
                'message' => 'FCM token disimpan'
            ]);
        }
    }

    public function user()
    {

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        // ===== HANDLE PREFLIGHT OPTIONS =====
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            return $this->response->setStatusCode(200);
        }


        $email = $this->request->getGet('email');
        $password = $this->request->getGet('password');

        $user = $this->db->table('user')
            ->where('user_email', $email)
            ->get()
            ->getRow();

        if (!$user) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'User tidak ditemukan',
                'total' => 0,
                'data' => []
            ]);
        }

        // ===== Decrypt Password =====
        $key = "ihsandulu123456";
        $method = "AES-256-CBC";

        $raw = base64_decode($user->user_password);
        $iv = substr($raw, 0, openssl_cipher_iv_length($method));
        $enc = substr($raw, openssl_cipher_iv_length($method));

        $decrypted = openssl_decrypt($enc, $method, $key, 0, $iv);

        if ($decrypted !== $password) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Password salah',
                'total' => 0,
                'data' => []
            ]);
        }

        // ===== Hanya kirim field tertentu =====
        $data = [
            'id' => $user->id,
            'user_name' => $user->user_name
        ];

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Data ditemukan',
            'total' => 1,
            'data' => [$data]
        ]);
    }

    public function userantam()
    {
        $email = $this->request->getGet('email');
        $password = $this->request->getGet('password');
        $user = $this->db
            ->table('user')
            ->where('user_email', $email)
            ->get();
        if ($user->getNumRows() == 0) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'User tidak ditemukan',
                'total' => 0,
                'data' => []
            ]);
        } else {
            foreach ($user->getResult() as $u) {
                $password_db = $u->user_password;

                $key = "ihsandulu123456"; // Kunci rahasia (jangan hardcode di produksi)
                $method = "AES-256-CBC";
                $datak = base64_decode($password_db);
                $iv_dec = substr($datak, 0, openssl_cipher_iv_length($method));
                $encrypted_data = substr($datak, openssl_cipher_iv_length($method));
                $decrypted = openssl_decrypt($encrypted_data, $method, $key, 0, $iv_dec);
                // echo $decrypted." == ".$password;die;
                if ($decrypted == $password) {
                    $userId = $u->id;
                    $data = $this->db
                        ->table('userantam')
                        ->where('user_id', $userId)
                        ->get()
                        ->getResultArray();

                    if (!empty($data)) {
                        return $this->response->setJSON([
                            'status' => true,
                            'message' => 'Data ditemukan',
                            'total' => count($data),
                            'data' => $data
                        ]);
                    } else {
                        return $this->response->setJSON([
                            'status' => false,
                            'message' => 'Data tidak ditemukan',
                            'total' => 0,
                            'data' => []
                        ]);
                    }
                } else {
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => 'Password salah',
                        'total' => 0,
                        'data' => []
                    ]);
                }
            }
        }
    }
}
