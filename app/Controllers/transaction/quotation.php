<?php

namespace App\Controllers\transaction;


use App\Controllers\BaseController;

class quotation extends BaseController
{

    protected $sesi_user;
    public function __construct()
    {
        $sesi_user = new \App\Models\global_m();
        $sesi_user->ceksesi();
    }


    public function index()
    {
        $data = new \App\Models\transaction\quotation_m();
        $data = $data->data();
        $data["title"]="Quotation";
        return view('transaction/quotation_v', $data);
    }
}
