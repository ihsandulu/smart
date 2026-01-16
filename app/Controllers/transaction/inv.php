<?php

namespace App\Controllers\transaction;


use App\Controllers\BaseController;

class inv extends BaseController
{

    protected $sesi_user;
    public function __construct()
    {
        $sesi_user = new \App\Models\global_m();
        $sesi_user->ceksesi();
    }


    public function index()
    {
        $data = new \App\Models\transaction\inv_m();
        $data = $data->data();
        $data["title"]="Invoice Customer";
        return view('transaction/inv_v', $data);
    }
}
