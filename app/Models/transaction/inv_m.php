<?php

namespace App\Models\transaction;

use App\Models\core_m;

class inv_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek inv
        if ($this->request->getVar("inv_id")) {
            $invd["inv_id"] = $this->request->getVar("inv_id");
        } else {
            $invd["inv_id"] = -1;
        }
        $us = $this->db
            ->table("inv")
            ->getWhere($invd);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "inv_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $inv) {
                foreach ($this->db->getFieldNames('inv') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $inv->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('inv') as $field) {
                $data[$field] = "";
            }
        }



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $inv_id =   $this->request->getPost("inv_id");

            //update job
            $invd = $this->db->table('invd')
                ->where("inv_id", $inv_id)
                ->get();
            $jobdano      = array();
            foreach ($invd->getResult() as $rinvd) {
                if ($rinvd->job_dano !== '' && ! in_array($rinvd->job_dano, $jobdano)) {
                    $jobdano[] = $rinvd->job_dano;
                }
            }
            $inputjob["inv_temp"] = "";

            if (!empty($jobdano)) {
                $this->db
                    ->table('job')
                    ->whereIn('job_dano', $jobdano)
                    ->update($inputjob);
                // echo $this->db->getLastQuery();die;
            }

            //delete invd
            $this->db
                ->table("invd")
                ->delete(array("inv_id" =>  $inv_id));

            //delete inv
            $this->db
                ->table("inv")
                ->delete(array("inv_id" =>  $inv_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'inv_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $cekdano = $this->db
                ->table("inv")
                ->orderBy("inv_dano", "desc")
                ->limit(1)
                ->get();
            $dano = 250001;
            foreach ($cekdano->getResult() as $dano) {
                $dano = $dano->inv_dano + 1;
            }
            $input["inv_dano"] = $dano;

            $cekinv = $this->db
                ->table("inv")
                ->orderBy("inv_invoice", "desc")
                ->limit(1)
                ->get();
            $inv_invoice = 1;
            foreach ($cekinv->getResult() as $cekinv) {
                $ainv_invoice = explode("/", $cekinv->inv_invoice);
                $inv_invoice = $ainv_invoice[0] + 1;
            }
            $inv_invoice = str_pad($inv_invoice, 3, "0", STR_PAD_LEFT);

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

            $inv_invoice = $inv_invoice . "/INV/NKL-" . $input["customer_singkatan"] . "/" . $romawi[$bulan] . "/" . date("Y");
            $input["inv_invoice"] = $inv_invoice;
            $input["inv_date"] = date("Y-m-d");
            $builder = $this->db->table('inv');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $inv_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'inv_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('inv')->update($input, array("inv_id" => $this->request->getPost("inv_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
