<?php

namespace App\Models\transaction;

use App\Models\core_m;

class invpayment_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek invpayment
        if ($this->request->getVar("inv_id")) {
            $invpaymentd["inv_id"] = $this->request->getVar("inv_id");
        } else {
            $invpaymentd["inv_id"] = -1;
        }
        $us = $this->db
            ->table("inv")
            ->getWhere($invpaymentd);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "invpayment_id_dep", "trx_id", "trx_code");
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
        $data["inv_temp"] = $this->request->getGet("inv_temp");
        $data["inv_id"] = $this->request->getGet("inv_id");
        $data["customer_name"] = $this->request->getGet("customer_name");
        $data["customer_id"] = $this->request->getGet("customer_id");



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $invpayment_id =   $this->request->getPost("invpayment_id");

            //delete invpayment
            $this->db
                ->table("invpayment")
                ->delete(array("invpayment_id" =>  $invpayment_id));


            // Hitung total pembayaran dari invpayment
            $inv_temp = $this->request->getGet("inv_temp");
            $invpayment_total = $this->db
                ->table("invpayment")
                ->select("SUM(invpayment_total) AS inv_payment")
                ->where("inv_temp", $inv_temp)
                ->get()
                ->getRow();

            // Pastikan hasil tidak null
            $inv_payment = $invpayment_total ? $invpayment_total->inv_payment : 0;

            // Siapkan data update
            $inputi = [
                "inv_payment" => $inv_payment
            ];

            // Lakukan update pada tabel inv
            $this->db
                ->table("inv")
                ->where("inv_temp", $inv_temp)
                ->update($inputi);

            //delete kas
            $kas = $this->db->table('kas')
                ->where("invpayment_id", $invpayment_id)
                ->get();
            if ($kas->getNumRows() > 0) {
                foreach ($kas->getResult() as $kas) {
                    $kas_id =   $kas->kas_id;
                    $this->db
                        ->table("kas")
                        ->delete(array("kas_id" =>  $kas_id));

                    /* $kassebelumnya = $this->db->table("kas")->where("kas_id <", $kas_id)->limit(1)->orderBy("kas_id", "DESC")->get();
                    // echo $this->db->getLastQuery(); die;
                    $saldo = 0;
                    $bigcash = 0;
                    $pettycash = 0;
                    foreach ($kassebelumnya->getResult() as $kas) {
                        $saldo = $kas->kas_saldo;
                        $bigcash = $kas->kas_bigcash;
                        $pettycash = $kas->kas_pettycash;
                    } */

                    // echo $saldo;die;
                    /* $kas = $this->db->table("kas")->where("kas_id >", $kas_id)->orderBy("kas_id", "ASC")->get();
                    // echo $this->db->getLastQuery(); die;
                    foreach ($kas->getResult() as $kas) {
                        if ($kas->kas_type == "Debet") {
                            $saldo = $saldo + $kas->kas_total;
                            if ($kas->kas_debettype == "bigcash") {
                                $bigcash = $bigcash + $kas->kas_total;
                            }
                            if ($kas->kas_debettype == "pettycash") {
                                $pettycash = $pettycash + $kas->kas_total;
                            }
                        } else {
                            $saldo = $saldo - $kas->kas_total;
                            if ($kas->kas_debettype == "bigcash") {
                                $bigcash = $bigcash - $kas->kas_total;
                            }
                            if ($kas->kas_debettype == "pettycash") {
                                $pettycash = $pettycash - $kas->kas_total;
                            }
                        }
                        $input2["kas_saldo"] = $saldo;
                        $input2["kas_bigcash"] = $bigcash;
                        $input2["kas_pettycash"] = $pettycash;
                        $kas_id = $kas->kas_id;
                        $this->db->table('kas')->update($input2, array("kas_id" => $kas_id));
                        // echo $this->db->getLastQuery(); die;
                    } */
                }
            }

            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'invpayment_id' && $e != 'inv_no') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            // dd($input);
            $this->db->table('invpayment')->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $invpayment_id = $this->db->insertID();


            // Hitung total pembayaran dari invpayment
            $inv_temp = $this->request->getGet("inv_temp");
            $invpaymenttotal = $this->db
                ->table("invpayment")
                ->select("SUM(invpayment_total) AS invpaymenttotal")
                ->where("inv_temp", $inv_temp)
                ->get()
                ->getRow();

            // Pastikan hasil tidak null
            $inv_payment = $invpaymenttotal ? $invpaymenttotal->invpaymenttotal : 0;

            // Siapkan data update
            $inputi = [
                "inv_payment" => $inv_payment
            ];

            // Lakukan update pada tabel inv
            $this->db
                ->table("inv")
                ->where("inv_temp", $inv_temp)
                ->update($inputi);

            //input kas
            //cek apakah rekening nkl
            $rekening = $this->db->table("rekening")->where("rekening_id", $input["invpayment_to"])->get();
            $nkl = 0;
            foreach ($rekening->getResult() as $row) {
                if ($row->rekening_type == "NKL") {
                    $nkl = 1;
                }
            }
            if ($input["invpayment_to"] == -1) {
                $nkl = 1;
            }
            if ($nkl == 1) {
                $invd = $this->db->table('invd')
                    ->where("inv_temp", $inv_temp)
                    ->get();
                $jobdano      = array();
                foreach ($invd->getResult() as $rinvd) {
                    if ($rinvd->job_dano !== '' && ! in_array($rinvd->job_dano, $jobdano)) {
                        $jobdano[] = $rinvd->job_dano;
                    }
                }
                $jobdanos      = implode(', ', $jobdano);
                if ($input["invpayment_to"] == "-1") {
                    $kas_debettype = "pettycash";
                } else {
                    $kas_debettype = "bigcash";
                }
                /* $kas = $this->db->table("kas")->orderBy("kas_id", "desc")->limit("1")->get();
                $saldo = 0;
                $bigcash = 0;
                $pettycash = 0;
                foreach ($kas->getResult() as $kas) {
                    $saldo = $kas->kas_saldo + $input["invpayment_total"];
                    if ($kas_debettype == "bigcash") {
                        $bigcash = $kas->kas_bigcash + $input["invpayment_total"];
                        $pettycash = $kas->kas_pettycash;
                    }
                    if ($kas_debettype == "pettycash") {
                        $pettycash = $kas->kas_pettycash + $input["invpayment_total"];
                        $bigcash = $kas->kas_bigcash;
                    }
                } */
                $inputkas[] = array(
                    "kas_date" => $input["invpayment_date"],
                    "job_dano" => $jobdanos,
                    "kas_uraian" => $this->request->getPost("inv_no"),
                    "kas_qty" => $input["invpayment_qty"],
                    "kas_nominal" => $input["invpayment_price"],
                    "kas_total" => $input["invpayment_total"],
                    "kas_rekdari" => $input["invpayment_from"],
                    "kas_rekke" => $input["invpayment_to"],
                    "kas_keterangan" => $input["invpayment_keterangan"],
                    "kas_type" => "Debet",
                    "kas_debettype" => $kas_debettype,
                    // "kas_saldo" => $saldo,
                    // "kas_bigcash" => $bigcash,
                    // "kas_pettycash" => $pettycash,
                    "vendor_id" => 0,
                    "kas_vendorsaldo" => 0,
                    "invpayment_id" => $invpayment_id,
                );
                $this->db->table('kas')->insertBatch($inputkas);
            }

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;



        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'invpayment_picture' && $e != 'inv_no') {
                    $inputp[$e] = $this->request->getPost($e);
                }
            }

            if ($inputp["invpayment_to"] == "-1") {
                $kas_debettype = "pettycash";
            } else {
                $kas_debettype = "bigcash";
            }
            $input["kas_debettype"] = $kas_debettype;
            $input["kas_type"] = "Debet";
            $input["kas_total"] = $inputp["invpayment_total"];

            // dd($inputp);
            $invpayment_id = $this->request->getPost("invpayment_id");
            $this->db->table('invpayment')->update($inputp, array("invpayment_id" => $invpayment_id));

            // Hitung total pembayaran dari invpayment
            $inv_temp = $this->request->getGet("inv_temp");
            $invpaymenttotal = $this->db
                ->table("invpayment")
                ->select("SUM(invpayment_total) AS invpaymenttotal")
                ->where("inv_temp", $inv_temp)
                ->get()
                ->getRow();

            // Pastikan hasil tidak null
            $inv_payment = $invpaymenttotal ? $invpaymenttotal->invpaymenttotal : 0;

            // Siapkan data update
            $inputi = [
                "inv_payment" => $inv_payment
            ];

            // Lakukan update pada tabel inv
            $this->db
                ->table("inv")
                ->where("inv_temp", $inv_temp)
                ->update($inputi);

            //input kas
            //cek apakah rekening nkl
            $rekening = $this->db->table("rekening")->where("rekening_id", $inputp["invpayment_to"])->get();
            $nkl = 0;
            foreach ($rekening->getResult() as $row) {
                if ($row->rekening_type == "NKL") {
                    $nkl = 1;
                }
            }
            if ($inputp["invpayment_to"] == -1) {
                $nkl = 1;
            }
            if ($nkl == 1) {
                $kas = $this->db->table('kas')
                    ->where("invpayment_id", $invpayment_id)
                    ->get();

                /* $saldo = 0;
                $bigcash = 0;
                $pettycash = 0; */
                if ($kas->getNumRows() > 0) {
                    foreach ($kas->getResult() as $kas) {
                        $kas_id = $kas->kas_id;
                        /* $kas_totalawal = $kas->kas_total;
                    $kas_saldo = $kas->kas_saldo;
                    $kas_bigcash = $kas->kas_bigcash;
                    $kas_pettycash = $kas->kas_pettycash;
                    $kas_type = $kas->kas_type; */
                        $kas_debettype = $kas->kas_debettype;
                        /* if ($kas_type == "Debet") {
                        $saldoawal = $kas_saldo - $kas_totalawal;
                        if ($kas_debettype == "bigcash") {
                            $bigcashawal = $kas_bigcash - $kas_totalawal;
                            $pettycashawal = $kas_pettycash;
                        } else {
                            $bigcashawal = $kas_bigcash;
                            $pettycashawal = $kas_pettycash - $kas_totalawal;
                        }
                        // echo $bigcashawal." - ".$pettycashawal;die;
                    } else {
                        $saldoawal = $kas_saldo + $kas_totalawal;
                        if ($kas_debettype == "bigcash") {
                            $bigcashawal = $kas_bigcash + $kas_totalawal;
                            $pettycashawal = $kas_pettycash;
                        } else {
                            $bigcashawal = $kas_bigcash;
                            $pettycashawal = $kas_pettycash + $kas_totalawal;
                        }
                        // echo $kas_saldo." + ".$kas_totalawal;die;
                    } */
                        // echo $saldoawal."==".$bigcashawal."==".$pettycashawal;die;
                        // dd($inputp);
                        /* if ($input["kas_type"] == "Debet") {
                        $saldo = $saldoawal + $input["kas_total"];
                        if ($input["kas_debettype"] == "bigcash") {
                            $bigcash = $bigcashawal + $input["kas_total"];
                            $pettycash = $pettycashawal;
                        } else {
                            $bigcash = $bigcashawal;
                            $pettycash = $pettycashawal + $input["kas_total"];
                        }
                        // echo $bigcash."==".$pettycash;die;
                    } else {
                        $saldo =  $saldoawal - $input["kas_total"];
                        if ($input["kas_debettype"] == "bigcash") {
                            $bigcash = $bigcashawal - $input["kas_total"];
                            $pettycash = $pettycashawal;
                        }
                        if ($input["kas_debettype"] == "pettycash") {
                            $bigcash = $bigcashawal;
                            $pettycash = $pettycashawal - $input["kas_total"];
                        }
                        //  echo $saldo."==".$bigcash."==".$pettycash;die;
                    } */
                    }
                    /* $input["kas_saldo"] = $saldo;
                    $input["kas_bigcash"] = $bigcash;
                    $input["kas_pettycash"] = $pettycash; */

                    $this->db->table('kas')->update($input, array("kas_id" => $kas_id));

                    // echo $this->db->getLastQuery(); die;


                    /* $kas = $this->db->table("kas")->where("kas_id >", $kas_id)->orderBy("kas_id", "ASC")->get();
                    // echo $this->db->getLastQuery(); die;
                    foreach ($kas->getResult() as $kas) {
                        if ($kas->kas_type == "Debet") {
                            $saldo = $saldo + $kas->kas_total;
                            if ($kas->kas_debettype == "bigcash") {
                                $bigcash = $bigcash + $kas->kas_total;
                            }
                            if ($kas->kas_debettype == "pettycash") {
                                $pettycash = $pettycash + $kas->kas_total;
                            }
                        } else {
                            $saldo = $saldo - $kas->kas_total;
                            if ($kas->kas_debettype == "bigcash") {
                                $bigcash = $bigcash - $kas->kas_total;
                            }
                            if ($kas->kas_debettype == "pettycash") {
                                $pettycash = $pettycash - $kas->kas_total;
                            }
                        }
                        $input2["kas_saldo"] = $saldo;
                        $input2["kas_bigcash"] = $bigcash;
                        $input2["kas_pettycash"] = $pettycash;
                        $kas_id = $kas->kas_id;
                        $this->db->table('kas')->update($input2, array("kas_id" => $kas_id));
                        // echo $this->db->getLastQuery(); die;
                    } */
                } else {
                    $invd = $this->db->table('invd')
                        ->where("inv_temp", $inv_temp)
                        ->get();
                    $jobdano      = array();
                    foreach ($invd->getResult() as $rinvd) {
                        if ($rinvd->job_dano !== '' && ! in_array($rinvd->job_dano, $jobdano)) {
                            $jobdano[] = $rinvd->job_dano;
                        }
                    }
                    $jobdanos      = implode(', ', $jobdano);                    

                    $inputkas[] = array(
                        "kas_date" => $inputp["invpayment_date"],
                        "job_dano" => $jobdanos,
                        "kas_uraian" => $this->request->getPost("inv_no"),
                        "kas_qty" => $inputp["invpayment_qty"],
                        "kas_nominal" => $inputp["invpayment_price"],
                        "kas_total" => $inputp["invpayment_total"],
                        "kas_rekdari" => $inputp["invpayment_from"],
                        "kas_rekke" => $inputp["invpayment_to"],
                        "kas_keterangan" => $inputp["invpayment_keterangan"],
                        "kas_type" => "Debet",
                        "kas_debettype" => $kas_debettype,
                        // "kas_saldo" => $saldo,
                        // "kas_bigcash" => $bigcash,
                        // "kas_pettycash" => $pettycash,
                        "vendor_id" => 0,
                        "kas_vendorsaldo" => 0,
                        "invpayment_id" => $invpayment_id,
                    );
                    $this->db->table('kas')->insertBatch($inputkas);
                }
            } else {
                //delete kas
                $kas = $this->db->table('kas')
                    ->where("invpayment_id", $invpayment_id)
                    ->get();
                if ($kas->getNumRows() > 0) {
                    foreach ($kas->getResult() as $kas) {
                        $kas_id =   $kas->kas_id;
                        $this->db
                            ->table("kas")
                            ->delete(array("kas_id" =>  $kas_id));
                    }
                }
            }


            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }


        return $data;
    }
}
