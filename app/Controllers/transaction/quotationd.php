<?php

namespace App\Controllers\transaction;


use App\Controllers\BaseController;

class quotationd extends BaseController
{

    protected $sesi_user;
    public function __construct()
    {
        $sesi_user = new \App\Models\global_m();
        $sesi_user->ceksesi();
    }


    public function index()
    {
        $data = new \App\Models\transaction\quotationd_m();
        $data = $data->data();
        $data["title"]="Detail Quotation";
        return view('transaction/quotationd_v', $data);
    }
}
