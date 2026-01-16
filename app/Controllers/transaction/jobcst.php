<?php

namespace App\Controllers\transaction;


use App\Controllers\BaseController;

class jobcst extends BaseController
{

    protected $sesi_user;
    public function __construct()
    {
        $sesi_user = new \App\Models\global_m();
        $sesi_user->ceksesi();
    }


    public function index()
    {
        $data = new \App\Models\transaction\jobcst_m();
        $data = $data->data();
        $data["title"] = "Tracking Kiriman";
        return view('transaction/jobcst_v', $data);
    }
}
