<?php

namespace App\Controllers\transaction;


use App\Controllers\BaseController;

class antam extends BaseController
{

    protected $sesi_user;
    public function __construct()
    {
        $sesi_user = new \App\Models\global_m();
        $sesi_user->ceksesi();
    }


    public function index()
    {
        $data = new \App\Models\transaction\antam_m();
        $data = $data->data();
        return view('transaction/antam_v', $data);
    }
}
