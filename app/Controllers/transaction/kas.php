<?php

namespace App\Controllers\transaction;


use App\Controllers\BaseController;

class kas extends BaseController
{

    protected $sesi_user;
    public function __construct()
    {
        $sesi_user = new \App\Models\global_m();
        $sesi_user->ceksesi();
    }


    public function index()
    {
        $data = new \App\Models\transaction\kas_m();
        $data = $data->data();
        $data["title"]="Kas";
        $data["url"]="kas";
        $kas=$this->db->table("kas")
        ->orderBy("kas_date","DESC")
        ->orderBy("kas_id","DESC")
        ->limit(1)
        ->get();
        $saldo=0;
        foreach($kas->getResult() as $row){
            $saldo=$row->kas_saldo;
        }
        $data["saldo"]=$saldo;
        return view('transaction/kas_v', $data);
    }

     public function bigcash()
    {
        $data = new \App\Models\transaction\kas_m();
        $data = $data->data();
        $data["title"]="Big Cash";
        $data["url"]="bigcash";
        $kas=$this->db->table("kas")
        ->where("kas_debettype","bigcash")
        ->orderBy("kas_date","DESC")
        ->orderBy("kas_id","DESC")
        ->limit(1)
        ->get();
        // echo $this->db->getLastQuery();die;
        $saldo=0;
        foreach($kas->getResult() as $row){
            $saldo=$row->kas_bigcash;
        }
        $data["saldo"]=$saldo;
        return view('transaction/kas_v', $data);
    }

     public function pettycash()
    {
        $data = new \App\Models\transaction\kas_m();
        $data = $data->data();
        $data["title"]="Petty Cash";
        $data["url"]="pettycash";
        $kas=$this->db->table("kas")
        ->where("kas_debettype","pettycash")
        ->orderBy("kas_date","DESC")
        ->orderBy("kas_id","DESC")
        ->limit(1)
        ->get();
        $saldo=0;
        foreach($kas->getResult() as $row){
            $saldo=$row->kas_pettycash;
        }
        $data["saldo"]=$saldo;
        return view('transaction/kas_v', $data);
    }
}
