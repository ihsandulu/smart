<?php

namespace App\Models\transaction;

use App\Models\core_m;

class job_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek job
        if ($this->request->getPost("job_id")) {
            $jobd["job_id"] = $this->request->getPost("job_id");
        } else if ($this->request->getPost("temp")) {
            $jobd["job_temp"] = $this->request->getPost("temp");
        } else if ($this->request->getGet("temp")) {
            $jobd["job_temp"] = $this->request->getGet("temp");
        } else {
            $jobd["job_id"] = -1;
        }
        $us = $this->db
            ->table("job")
            ->getWhere($jobd);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "job_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $job) {
                foreach ($this->db->getFieldNames('job') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $job->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('job') as $field) {
                $data[$field] = "";
            }
            $data["job_temp"] = date("YmdHis") . $this->session->get("user_id");
            $data["job_shipmentdate"] = date("Y-m-d");
            $data["job_sell"] = 0;
            $data["job_total"] = 0;
            $data["job_refund"] = 0;
            $data["job_profit"] = 0;
            $data["job_fee"] = 0;
            $data["job_gp"] = 0;
        }



        if (isset($_GET["temp"])) {
            $data["job_temp"] = $_GET["temp"];
        }

        //total cost
        $us = $this->db
            ->table("cost")
            ->where("job_temp", $data["job_temp"])
            ->get();
        // echo $this->db->getLastQuery();die;
        $job_cost = 0;
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $cost) {
                $job_cost += $cost->cost_total;
            }
        } else {
            $job_cost = 0;
        }
        $data["job_cost"] = $job_cost;

        //total description of goods
        $goods = $this->db
            ->table("jobd")
            ->where("job_temp", $data["job_temp"])
            ->get();
        // echo $this->db->getLastQuery();die;
        $job_total = 0;
        if ($goods->getNumRows() > 0) {
            foreach ($goods->getResult() as $jobds) {
                $job_total += $jobds->jobd_total;
            }
        } else {
            $job_total = 0;
        }
        $data["job_total"] = $job_total;


        //delete
        if ($this->request->getPost("delete") == "OK") {
            $job_id =   $this->request->getPost("job_id");
            $this->db
                ->table("job")
                ->delete(array("job_id" =>  $job_id));
            $data["message"] = "Delete Success";
        }

        //upload image quotation
        $data['uploadjob_picture'] = "";
        if (isset($_FILES['job_picture']) && $_FILES['job_picture']['name'] != "") {
            // $request = \Config\Services::request();
            $file = $this->request->getFile('job_picture');
            $name = $file->getName(); // Mengetahui Nama File
            $originalName = $file->getClientName(); // Mengetahui Nama Asli
            $tempfile = $file->getTempName(); // Mengetahui Nama TMP File name
            $ext = $file->getClientExtension(); // Mengetahui extensi File
            $type = $file->getClientMimeType(); // Mengetahui Mime File
            $size_kb = $file->getSize('kb'); // Mengetahui Ukuran File dalam kb
            $size_mb = $file->getSize('mb'); // Mengetahui Ukuran File dalam mb


            //$namabaru = $file->getRandomName();//define nama fiel yang baru secara acak

            if ($type == 'image/jpg' || $type == 'image/jpeg' || $type == 'image/png') //cek mime file
            {    // File Tipe Sesuai   
                helper('filesystem'); // Load Helper File System
                $direktori = 'images/job_picture'; //definisikan direktori upload            
                $job_picture = str_replace(' ', '_', $name);
                $job_picture = date("H_i_s_") . $job_picture; //definisikan nama fiel yang baru
                $map = directory_map($direktori, FALSE, TRUE); // List direktori

                //Cek File apakah ada 
                foreach ($map as $key) {
                    if ($key == $job_picture) {
                        delete_files($direktori, $job_picture); //Hapus terlebih dahulu jika file ada
                    }
                }
                //Metode Upload Pilih salah satu
                //$path = $this->request->getFile('uploadedFile')->identity($direktori, $namabaru);
                //$file->move($direktori, $namabaru)
                if ($file->move($direktori, $job_picture)) {
                    $data['uploadjob_picture'] = "Upload Success !";
                    $input['job_picture'] = $job_picture;
                } else {
                    $data['uploadjob_picture'] = "Upload Gagal !";
                }
            } else {
                // File Tipe Tidak Sesuai
                $data['uploadjob_picture'] = "Format File Salah !";
            }
        }
        // dd($_POST);
        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'job_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $cekdano = $this->db
                ->table("job")
                ->orderBy("job_dano", "desc")
                ->limit(1)
                ->get();
            $dano = 250192;
            foreach ($cekdano->getResult() as $dano) {
                $dano = $dano->job_dano + 1;
            }
            $input["job_dano"] = $dano;

            $cekinv = $this->db
                ->table("job")
                ->orderBy("job_invoice", "desc")
                ->limit(1)
                ->get();
            $job_invoice = 1;
            foreach ($cekinv->getResult() as $cekinv) {
                $ajob_invoice = explode("/", $cekinv->job_invoice);
                $job_invoice = $ajob_invoice[0] + 1;
            }
            $job_invoice = str_pad($job_invoice, 3, "0", STR_PAD_LEFT);

            $bulan = date("n"); // angka bulan 1-12

            $romawi = [
                1 => 'I',
                2 => 'II',
                3 => 'III',
                4 => 'IV',
                5 => 'V',
                6 => 'VI',
                7 => 'VII',
                8 => 'VIII',
                9 => 'IX',
                10 => 'X',
                11 => 'XI',
                12 => 'XII',
            ];

            $job_invoice = $job_invoice . "/INV/NKL-" . $input["customer_singkatan"] . "/" . $romawi[$bulan] . "/" . date("Y");
            $input["job_invoice"] = $job_invoice;
            $input["job_date"] = date("Y-m-d");
            $builder = $this->db->table('job');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $job_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'job_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('job')->update($input, array("job_id" => $this->request->getPost("job_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
