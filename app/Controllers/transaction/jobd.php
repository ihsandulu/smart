<?php

namespace App\Controllers\transaction;


use App\Controllers\BaseController;

class jobd extends BaseController
{

    protected $sesi_user;
    public function __construct()
    {
        $sesi_user = new \App\Models\global_m();
        $sesi_user->ceksesi();
    }


    public function index()
    {
        $data = new \App\Models\transaction\jobd_m();
        $data = $data->data();
        $data["title"]="Detail Job";
        return view('transaction/jobd_v', $data);
    }
}
