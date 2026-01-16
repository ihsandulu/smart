<?php

namespace App\Controllers\transaction;


use App\Controllers\BaseController;

class invvdrp extends BaseController
{

    protected $sesi_user;
    public function __construct()
    {
        $sesi_user = new \App\Models\global_m();
        $sesi_user->ceksesi();
    }


    public function index()
    {
        $data = new \App\Models\transaction\invvdrp_m();
        $data = $data->data();
        $data["title"]="Detail Pembayaran";
        return view('transaction/invvdrp_v', $data);
    }
}
