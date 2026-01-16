<?php

namespace App\Models\transaction;

use App\Models\core_m;

class quotation_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek quotation
        if ($this->request->getVar("quotation_id")) {
            $quotationd["quotation_id"] = $this->request->getVar("quotation_id");
        } else {
            $quotationd["quotation_id"] = -1;
        }
        $us = $this->db
            ->table("quotation")
            ->getWhere($quotationd);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "quotation_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $quotation) {
                foreach ($this->db->getFieldNames('quotation') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $quotation->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('quotation') as $field) {
                $data[$field] = "";
            }
        }



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $quotation_id =   $this->request->getPost("quotation_id");
            $this->db
                ->table("quotation")
                ->delete(array("quotation_id" =>  $quotation_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'quotation_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }

            $cekquotation = $this->db
                ->table("quotation")
                ->orderBy("quotation_id", "desc")
                ->limit(1)
                ->get();
            $quotation_no = 1;
            foreach ($cekquotation->getResult() as $cekquotation) {
                $aquotation_no = explode("/", $cekquotation->quotation_no);
                $quotation_no = $aquotation_no[0] + 1;
            }
            $quotation_no = str_pad($quotation_no, 3, "0", STR_PAD_LEFT);

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

            $quotation_no = $quotation_no . "/QT/NKL-" . $input["quotation_singkatan"] . "/" . $romawi[$bulan] . "/" . date("Y");
            $input["quotation_no"] = $quotation_no;
            $input["user_id"] = $this->session->get("user_id");

            $builder = $this->db->table('quotation');
            $builder->insert($input);
            // echo $this->db->getLastQuery(); die;
            $quotation_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'quotation_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $quotation_id = $this->request->getPost("quotation_id");
            $this->db->table('quotation')->update($input, array("quotation_id" => $quotation_id));

            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
