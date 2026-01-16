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

    public function save_mqtt()
    {
        // hanya POST
        if ($this->request->getMethod() !== 'post') {
            return $this->fail('Invalid request method');
        }

        // ambil JSON
        $json = $this->request->getJSON(true);

        if (!$json) {
            return $this->fail('Invalid JSON payload');
        }

        // field WAJIB sesuai tabel mqtt_messages
        $topic   = $json['topic']   ?? null;
        $message = $json['message'] ?? null;
        $device  = $json['device']  ?? null;

        if (!$topic || !$message) {
            return $this->fail('Incomplete data');
        }

        // insert ke TABEL mqtt_messages
        $this->db->table('mqtt_messages')->insert([
            'topic'      => $topic,
            'payload'    => $message,
            'device_id'  => $device,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->respond([
            'status' => true,
            'msg'    => 'MQTT message saved'
        ]);
    }

}
