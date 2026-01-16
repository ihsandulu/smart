<?php

namespace App\Controllers\transaction;


use App\Controllers\BaseController;

class invd extends BaseController
{

    protected $sesi_user;
    public function __construct()
    {
        $sesi_user = new \App\Models\global_m();
        $sesi_user->ceksesi();
    }


    public function index()
    {
        $data = new \App\Models\transaction\invd_m();
        $data = $data->data();
        $data["title"]="Detail Invoice Customer";
        return view('transaction/invd_v', $data);
    }
}
