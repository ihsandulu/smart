<?php

namespace App\Models\transaction;

use App\Models\core_m;

class invvdr_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek invvdr
        if ($this->request->getVar("invvdr_id")) {
            $invvdrd["invvdr_id"] = $this->request->getVar("invvdr_id");
        } else {
            $invvdrd["invvdr_id"] = -1;
        }
        $us = $this->db
            ->table("invvdr")
            ->getWhere($invvdrd);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "invvdr_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $invvdr) {
                foreach ($this->db->getFieldNames('invvdr') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $invvdr->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('invvdr') as $field) {
                $data[$field] = "";
            }
        }



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $invvdr_id =   $this->request->getPost("invvdr_id");

            //cek pembayaran
            $cekpembayaran = $this->db
                ->table("invvdrp")
                ->where("invvdr_id", $invvdr_id)
                ->get();
            if ($cekpembayaran->getNumRows() > 0) {
                $data["message"] = "Hapus transaksi pembayaran terlebih dahulu!";
            } else {

                //update job
                /* $invvdr_no =   $this->request->getPost("invvdr_no");
                $invvdrd = $this->db->table('invvdrd')
                    ->where("invvdr_id", $invvdr_id)
                    ->get();
                $jobdano      = array();
                foreach ($invvdrd->getResult() as $rinvvdrd) {
                    if ($rinvvdrd->job_dano !== '' && ! in_array($rinvvdrd->job_dano, $jobdano)) {
                        $jobdano[] = $rinvvdrd->job_dano;
                    }
                }
                $inputjob["invvdr_no"] = "";

                if (!empty($jobdano)) {
                    $this->db
                        ->table('job')
                        ->whereIn('job_dano', $jobdano)
                        ->update($inputjob);
                    // echo $this->db->getLastQuery();die;
                } */

                //delete invvdrd
                $this->db
                    ->table("invvdrd")
                    ->delete(array("invvdr_id" =>  $invvdr_id));

                //delete invvdr
                $this->db
                    ->table("invvdr")
                    ->delete(array("invvdr_id" =>  $invvdr_id));
                $data["message"] = "Delete Success";
            }
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'invvdr_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $cekdano = $this->db
                ->table("invvdr")
                ->orderBy("invvdr_dano", "desc")
                ->limit(1)
                ->get();
            $dano = 250001;
            foreach ($cekdano->getResult() as $dano) {
                $dano = $dano->invvdr_dano + 1;
            }
            $input["invvdr_dano"] = $dano;

            $cekinvvdr = $this->db
                ->table("invvdr")
                ->orderBy("invvdr_invvdroice", "desc")
                ->limit(1)
                ->get();
            $invvdr_invvdroice = 1;
            foreach ($cekinvvdr->getResult() as $cekinvvdr) {
                $ainvvdr_invvdroice = explode("/", $cekinvvdr->invvdr_invvdroice);
                $invvdr_invvdroice = $ainvvdr_invvdroice[0] + 1;
            }
            $invvdr_invvdroice = str_pad($invvdr_invvdroice, 3, "0", STR_PAD_LEFT);

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

            $invvdr_invvdroice = $invvdr_invvdroice . "/invvdr/NKL-" . $input["customer_singkatan"] . "/" . $romawi[$bulan] . "/" . date("Y");
            $input["invvdr_invvdroice"] = $invvdr_invvdroice;
            $input["invvdr_date"] = date("Y-m-d");
            $builder = $this->db->table('invvdr');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $invvdr_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'invvdr_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('invvdr')->update($input, array("invvdr_id" => $this->request->getPost("invvdr_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
