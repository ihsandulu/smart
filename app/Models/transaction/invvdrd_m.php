<?php

namespace App\Models\transaction;

use App\Models\core_m;

class invvdrd_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $invvdrd_id =   $this->request->getPost("invvdrd_id");
            //update Invoice
            $invvdr_temp =   $this->request->getPost("invvdr_temp");
            $invvdrd = $this->db->table('invvdrd')
                ->where("invvdr_temp", $invvdr_temp)
                ->where("invvdrd_id !=", $invvdrd_id)
                ->get();
            $jobdano      = array();
            $tagihan = 0;
            foreach ($invvdrd->getResult() as $rinvvdrd) {
                if ($rinvvdrd->job_dano !== '' && ! in_array($rinvvdrd->job_dano, $jobdano)) {
                    $jobdano[] = $rinvvdrd->job_dano;
                }
                $tagihan += $rinvvdrd->invvdrd_total;
            }
            $jobdanos      = implode(', ', $jobdano);
            $inputi["job_dano"] = $jobdanos;

            $invvdr = $this->db->table('invvdr')->where("invvdr_temp", $invvdr_temp)->get();
            foreach ($invvdr->getResult() as $row) {
                $inputi["invvdr_tagihan"] = $tagihan;
                $dtagihan = $tagihan - $row->invvdr_discount;
                $inputi["invvdr_dtagihan"] = $dtagihan;

                $ppn1k1 = 0;
                $ppn11 = 0;
                $ppn12 = 0;
                $pph = 0;
                if ($row->invvdr_ppn1k1 > 0) {
                    $ppn1k1 = $dtagihan * 1.1 / 100;
                }
                if ($row->invvdr_ppn11 > 0) {
                    $ppn11 = $dtagihan * 11 / 100;
                }
                if ($row->invvdr_ppn12 > 0) {
                    $ppn12 = $dtagihan * 12 / 100;
                }
                if ($row->invvdr_pph > 0) {
                    $pph = $dtagihan * 2 / 100;
                }
                $tharga = $dtagihan + $ppn1k1 + $ppn11 + $ppn12;
                $grand = $tharga - $pph;
                $inputi["invvdr_grand"] = $grand;
                $this->db->table('invvdr')->update($inputi, array("invvdr_temp" => $invvdr_temp));
                // echo $this->db->getLastQuery();die;
            }



            //delete invvdrd
            $this->db
                ->table("invvdrd")
                ->delete(array("invvdrd_id" =>  $invvdrd_id));

            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'invvdrd_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            // dd($input);
            $this->db->table('invvdrd')->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $invvdrd_id = $this->db->insertID();

            $invvdr_temp =   $input["invvdr_temp"];
            //update Invoice
            if ($this->request->getGet("editinvvdr") == "OK") {
                $invvdrd = $this->db->table('invvdrd')->where("invvdr_temp", $invvdr_temp)->get();
                $jobdano      = array();
                $tagihan = 0;
                foreach ($invvdrd->getResult() as $rinvvdrd) {
                    if ($rinvvdrd->job_dano !== '' && ! in_array($rinvvdrd->job_dano, $jobdano)) {
                        $jobdano[] = $rinvvdrd->job_dano;
                    }
                    $tagihan += $rinvvdrd->invvdrd_total;
                }
                $jobdanos      = implode(', ', $jobdano);
                $inputi["job_dano"] = $jobdanos;


                $invvdr = $this->db->table('invvdr')->where("invvdr_temp", $invvdr_temp)->get();
                foreach ($invvdr->getResult() as $row) {
                    $inputi["invvdr_tagihan"] = $tagihan;
                    $dtagihan = $tagihan - $row->invvdr_discount;
                    $inputi["invvdr_dtagihan"] = $dtagihan;

                    $ppn1k1 = 0;
                    $ppn11 = 0;
                    $ppn12 = 0;
                    $pph = 0;
                    if ($row->invvdr_ppn1k1 > 0) {
                        $ppn1k1 = $dtagihan * 1.1 / 100;
                    }
                    if ($row->invvdr_ppn11 > 0) {
                        $ppn11 = $dtagihan * 11 / 100;
                    }
                    if ($row->invvdr_ppn12 > 0) {
                        $ppn12 = $dtagihan * 12 / 100;
                    }
                    if ($row->invvdr_pph > 0) {
                        $pph = $dtagihan * 2 / 100;
                    }
                    $tharga = $dtagihan + $ppn1k1 + $ppn11 + $ppn12;
                    $grand = $tharga - $pph;
                    $inputi["invvdr_grand"] = $grand;
                    // dd($inputi);
                    $this->db->table('invvdr')->update($inputi, array("invvdr_temp" => $invvdr_temp));
                }
            }

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //insert invvdr
        if ($this->request->getPost("createinvvdr") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'invvdr_id' && $e != 'customer_singkatan' && $e != 'createinvvdr') {
                    $input[$e] = $this->request->getPost($e);
                }
            }

            $invvdr_temp = $this->request->getGet('invvdr_temp');
            $invvdrd  = $this->db
                ->table('invvdrd')
                ->where('invvdr_temp', $invvdr_temp)
                ->get();

            $jobdano      = array();
            foreach ($invvdrd->getResult() as $rinvvdrd) {
                if ($rinvvdrd->job_dano !== '' && ! in_array($rinvvdrd->job_dano, $jobdano)) {
                    $jobdano[] = $rinvvdrd->job_dano;
                }
            }
            $jobdanos      = implode(', ', $jobdano);
            $input["job_dano"] = $jobdanos;
            // dd($input);

            $ppn1k1 = 0;
            $ppn11 = 0;
            $ppn12 = 0;
            $pph = 0;
            $dtagihan = $input["invvdr_dtagihan"];
            if (isset($input["invvdr_ppn1k1"]) && $input["invvdr_ppn1k1"] > 0) {
                $ppn1k1 = $dtagihan * 1.1 / 100;
            }
            if (isset($input["invvdr_ppn11"]) && $input["invvdr_ppn11"] > 0) {
                $ppn11 = $dtagihan * 11 / 100;
            }
            if (isset($input["invvdr_ppn12"]) && $input["invvdr_ppn12"] > 0) {
                $ppn12 = $dtagihan * 12 / 100;
            }
            if (isset($input["invvdr_pph"]) && $input["invvdr_pph"] > 0) {
                $pph = $dtagihan * 2 / 100;
            }
            $tharga = $dtagihan + $ppn1k1 + $ppn11 + $ppn12;
            $grand = $tharga - $pph;
            $input["invvdr_grand"] = $grand;

            // dd($input);
            $this->db->table('invvdr')->insert($input);
            // echo $this->db->getLastQuery(); die;
            $invvdr_id = $this->db->insertID();

            //updane nomor Invoice invvdrd
            $inputad["invvdrd_date"] = $input["invvdr_date"];
            $inputad["invvdr_id"] = $invvdr_id;
            $this->db
                ->table('invvdrd')
                ->where('invvdr_temp', $invvdr_temp)
                ->update($inputad);
            // echo $this->db->getLastQuery(); die;          

            $data["message"] = "Insert Data Success";
            header('Location: ' . base_url('invvdr'));
            exit;
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'invvdrd_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('invvdrd')->update($input, array("invvdrd_id" => $this->request->getPost("invvdrd_id")));

            $invvdr_temp =   $this->request->getGet("invvdr_temp");
            //update Invoice
            if ($this->request->getGet("editinvvdr") == "OK") {
                $invvdrd = $this->db->table('invvdrd')->where("invvdr_temp", $invvdr_temp)->get();
                $tagihan = 0;
                $jobdano      = array();
                foreach ($invvdrd->getResult() as $rinvvdrd) {
                    if ($rinvvdrd->job_dano !== '' && ! in_array($rinvvdrd->job_dano, $jobdano)) {
                        $jobdano[] = $rinvvdrd->job_dano;
                    }
                    $tagihan += $rinvvdrd->invvdrd_total;
                }
                $jobdanos      = implode(', ', $jobdano);
                $inputi["job_dano"] = $jobdanos;

                $invvdr = $this->db->table('invvdr')->where("invvdr_temp", $invvdr_temp)->get();
                foreach ($invvdr->getResult() as $row) {
                    $inputi["invvdr_tagihan"] = $tagihan;
                    $dtagihan = $tagihan - $row->invvdr_discount;
                    $inputi["invvdr_dtagihan"] = $dtagihan;

                    $ppn1k1 = 0;
                    $ppn11 = 0;
                    $ppn12 = 0;
                    $pph = 0;
                    if ($row->invvdr_ppn1k1 > 0) {
                        $ppn1k1 = $dtagihan * 1.1 / 100;
                    }
                    if ($row->invvdr_ppn11 > 0) {
                        $ppn11 = $dtagihan * 11 / 100;
                    }
                    if ($row->invvdr_ppn12 > 0) {
                        $ppn12 = $dtagihan * 12 / 100;
                    }
                    if ($row->invvdr_pph > 0) {
                        $pph = $dtagihan * 2 / 100;
                    }
                    $tharga = $dtagihan + $ppn1k1 + $ppn11 + $ppn12;
                    $grand = $tharga - $pph;
                    $inputi["invvdr_grand"] = $grand;
                    // dd($inputi);
                    $this->db->table('invvdr')->update($inputi, array("invvdr_temp" => $invvdr_temp));
                }

                $this->db->table('invvdr')->update($inputi, array("invvdr_temp" => $invvdr_temp));
            }

            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }

        //update Invoice
        if ($this->request->getPost("changeinvvdr") == "OK") {
            $input["invvdr_ppn1k1"] = 0;
            $input["invvdr_ppn11"] = 0;
            $input["invvdr_ppn12"] = 0;
            $input["invvdr_pph"] = 0;
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'changeinvvdr' && $e != 'customer_singkatan') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            // dd($input);
            $invvdr_temp = $input["invvdr_temp"];
            $invvdrd  = $this->db
                ->table('invvdrd')
                ->where('invvdr_temp', $invvdr_temp)
                ->get();
            $jobdano      = array();
            foreach ($invvdrd->getResult() as $rinvvdrd) {
                if ($rinvvdrd->job_dano !== '' && ! in_array($rinvvdrd->job_dano, $jobdano)) {
                    $jobdano[] = $rinvvdrd->job_dano;
                }
            }

            $jobdanos      = implode(', ', $jobdano);
            $input["job_dano"] = $jobdanos;

            $ppn1k1 = 0;
            $ppn11 = 0;
            $ppn12 = 0;
            $pph = 0;
            $dtagihan = $input["invvdr_dtagihan"];
            if (isset($input["invvdr_ppn1k1"]) && $input["invvdr_ppn1k1"] > 0) {
                $ppn1k1 = $dtagihan * 1.1 / 100;
            }
            if (isset($input["invvdr_ppn11"]) && $input["invvdr_ppn11"] > 0) {
                $ppn11 = $dtagihan * 11 / 100;
            }
            if (isset($input["invvdr_ppn12"]) && $input["invvdr_ppn12"] > 0) {
                $ppn12 = $dtagihan * 12 / 100;
            }
            if (isset($input["invvdr_pph"]) && $input["invvdr_pph"] > 0) {
                $pph = $dtagihan * 2 / 100;
            }
            $tharga = $dtagihan + $ppn1k1 + $ppn11 + $ppn12;
            $grand = $tharga - $pph;
            $input["invvdr_grand"] = $grand;

            $this->db->table('invvdr')->update($input, array("invvdr_id" => $this->request->getPost("invvdr_id")));


            //updane nomor Invoice invvdrd
            $inputad["invvdrd_date"] = $input["invvdr_date"];
            $this->db
                ->table('invvdrd')
                ->where('invvdr_temp', $invvdr_temp)
                ->update($inputad);
            // echo $this->db->getLastQuery(); die;

            $data["message"] = "Insert Data Success";
            header('Location: ' . base_url('invvdr'));
            exit;
        }


        //cek invvdrd
        if ($this->request->getVar("invvdr_id")) {
            $invvdrdd["invvdr_id"] = $this->request->getVar("invvdr_id");
        } else {
            $invvdrdd["invvdr_id"] = -1;
        }
        $us = $this->db
            ->table("invvdr")
            ->getWhere($invvdrdd);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "invvdrd_id_dep", "trx_id", "trx_code");
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
            $data["invvdr_temp"] = $_GET["invvdr_temp"];
        }

        $invvdrd = $this->db->table('invvdrd')->where("invvdr_temp", $data["invvdr_temp"])->get();
        // echo $this->db->getLastQuery();die;
        $tagihan = 0;
        foreach ($invvdrd->getResult() as $rinvvdrd) {
            $tagihan += $rinvvdrd->invvdrd_total;
        }
        /* if (!isset($data["invvdr_tagihan"])) {
            $data["invvdr_tagihan"] = $tagihan;
        }
        if (!isset($data["invvdr_dtagihan"])) {
            $data["invvdr_dtagihan"] = $tagihan - $data["invvdr_discount"];
        } */
        $data["invvdr_tagihan"] = $tagihan;
        // $data["invvdr_dtagihan"] = $tagihan - $data["invvdr_discount"];
        $data["invvdr_dtagihan"] = $tagihan - floatval($data["invvdr_discount"]);

        // dd($data);

        return $data;
    }
}
