<?php

namespace App\Controllers\transaction;


use App\Controllers\BaseController;

class job extends BaseController
{

    protected $sesi_user;
    public function __construct()
    {
        $sesi_user = new \App\Models\global_m();
        $sesi_user->ceksesi();
    }


    public function index()
    {
        $data = new \App\Models\transaction\job_m();
        $data = $data->data();
        $data["ppn"] = 0;
        if (isset($_GET["report"])) {
            $data["title"] = "Sales Report";
        } else {
            $data["title"] = "Job";
        }
        $data["posisi"] = "sales";
        $data["url"] = "job";
        return view('transaction/job_v', $data);
    }

    public function operasional()
    {
        $data = new \App\Models\transaction\job_m();
        $data = $data->data();
        $data["ppn"] = 0;
        $data["posisi"] = "operasional";
        $data["url"] = "joboperasional";
        if (isset($_GET["report"])) {
            $data["title"] = "Sales Report";
        } else {
            $data["title"] = "Edit Qty/CBM";
        }

        return view('transaction/job_v', $data);
    }

    public function sales()
    {
        $data = new \App\Models\transaction\job_m();
        $data = $data->data();
        $data["ppn"] = 0;
        $data["posisi"] = "sales";
        $data["url"] = "jobsales";
        if (isset($_GET["report"])) {
            $data["title"] = "Sales Report";
        } else {
            $data["title"] = "Leads Job";
        }

        return view('transaction/job_v', $data);
    }

    public function purchasing()
    {
        $data = new \App\Models\transaction\job_m();
        $data = $data->data();
        $data["ppn"] = 0;
        $data["posisi"] = "purchasing";
        $data["url"] = "jobpurchasing";
        if (isset($_GET["report"])) {
            $data["title"] = "Sales Report";
        } else {
            $data["title"] = "Job";
        }

        return view('transaction/job_v', $data);
    }


    public function finance()
    {
        $data = new \App\Models\transaction\job_m();
        $data = $data->data();
        $data["ppn"] = 0;
        $data["posisi"] = "finance";
        $data["url"] = "jobfinance";
        if (isset($_GET["report"])) {
            $data["title"] = "Sales Report";
        } else {
            $data["title"] = "Job";
        }

        return view('transaction/job_v', $data);
    }


    public function ppn()
    {
        $data = new \App\Models\transaction\job_m();
        $data = $data->data();
        $data["ppn"] = 1;
        $data["title"] = "Customer PPN";
        return view('transaction/job_v', $data);
    }


    public function nppn()
    {
        $data = new \App\Models\transaction\job_m();
        $data = $data->data();
        $data["ppn"] = 2;
        $data["title"] = "Customer Non PPN";
        return view('transaction/job_v', $data);
    }
}
