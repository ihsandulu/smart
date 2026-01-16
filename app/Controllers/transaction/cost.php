<?php

namespace App\Controllers\transaction;


use App\Controllers\BaseController;

class cost extends BaseController
{

    protected $sesi_user;
    public function __construct()
    {
        $sesi_user = new \App\Models\global_m();
        $sesi_user->ceksesi();
    }


    public function index()
    {
        $data = new \App\Models\transaction\cost_m();
        $data = $data->data();
        $data["title"]="Detail Cost";
        return view('transaction/cost_v', $data);
    }
}
