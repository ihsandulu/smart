<?php

namespace App\Models\transaction;

use App\Models\core_m;

class invvdrp_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek invvdrp
        if ($this->request->getVar("invvdr_id")) {
            $invvdrpd["invvdr_id"] = $this->request->getVar("invvdr_id");
        } else {
            $invvdrpd["invvdr_id"] = -1;
        }
        $us = $this->db
            ->table("invvdr")
            ->getWhere($invvdrpd);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "invvdrp_id_dep", "trx_id", "trx_code");
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
        $data["invvdr_no"] = $this->request->getGet("invvdr_no");
        $data["invvdr_id"] = $this->request->getGet("invvdr_id");
        $data["vendor_name"] = $this->request->getGet("vendor_name");
        $data["vendor_id"] = $this->request->getGet("vendor_id");



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $invvdrp_id =   $this->request->getPost("invvdrp_id");

            //delete invvdrp
            $this->db
                ->table("invvdrp")
                ->delete(array("invvdrp_id" =>  $invvdrp_id));


            // Hitung total pembayaran dari invvdrp
            $invvdr_id = $this->request->getGet("invvdr_id");
            $invvdrp_nominal = $this->db
                ->table("invvdrp")
                ->select("SUM(invvdrp_nominal) AS invvdr_payment")
                ->where("invvdr_id", $invvdr_id)
                ->get()
                ->getRow();

            // Pastikan hasil tidak null
            $invvdr_payment = $invvdrp_nominal ? $invvdrp_nominal->invvdr_payment : 0;

            // Siapkan data update
            $inputi = [
                "invvdr_payment" => $invvdr_payment
            ];

            // Lakukan update pada tabel invvdr
            $this->db
                ->table("invvdr")
                ->where("invvdr_id", $invvdr_id)
                ->update($inputi);

            //****delete kas****//
            $kas = $this->db->table('kas')
                ->where("invvdrp_id", $invvdrp_id)
                ->get();
            if ($kas->getNumRows() > 0) {
                foreach ($kas->getResult() as $kas) {
                    $kas_id =   $kas->kas_id;
                    $kas_date =   $kas->kas_date;
                    $kas_pettyid =   $kas->kas_pettyid;

                    $this->db
                        ->table("kas")
                        ->delete(array("kas_id" =>  $kas_id));

                    //apakah di tanggal yg sama ada id yg lebih rendah dari dia
                    /* $kas = $this->db
                        ->table("kas")
                        ->where("kas_date", $kas_date)
                        ->where("kas_id <",  $kas_id)
                        ->orderBy("kas_date", "DESC")
                        ->orderBy("kas_id", "DESC")
                        ->limit(1)
                        ->get();
                    if ($kas->getNumRows() == 0) {
                        //jika tidak ada maka cari transksi tgl sebelumnya
                        $kas = $this->db
                            ->table("kas")
                            ->where("kas_date <", $kas_date)
                            ->orderBy("kas_date", "DESC")
                            ->orderBy("kas_id", "DESC")
                            ->limit(1)
                            ->get();
                    }
                    // echo $this->db->getLastQuery(); //die;
                    $saldo = 0;
                    $bigcash = 0;
                    $pettycash = 0;
                    foreach ($kas->getResult() as $kas) {
                        $saldo = $kas->kas_saldo;
                        $bigcash = $kas->kas_bigcash;
                        $pettycash = $kas->kas_pettycash;
                    } */

                    // echo $saldo."-".$bigcash."-".$pettycash;die;
                    //******update transaksi setelahnya******//
                    //update dulu transaksi setelahnya yg satu tanggal dengan dia
                    /* $kas = $this->db->table("kas")
                        ->where("kas_id >", $kas_id)
                        ->where("kas_date",  $kas_date)
                        ->orderBy("kas_id", "ASC")->get();
                    // echo $this->db->getLastQuery();die;
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
                        // dd($input2);
                        $this->db->table('kas')->update($input2, array("kas_id" => $kas_id));
                        // echo $this->db->getLastQuery(); die;
                    } */
                    // dd();
                    //baru update transaksi ditanggal setelahnya dengan urutan by date asc dan id asc
                    /* $kas = $this->db->table("kas")
                        ->where("kas_date >",  $kas_date)
                        ->orderBy("kas_date", "ASC")
                        ->orderBy("kas_id", "ASC")
                        ->get();
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
                        // dd($input2);
                        $kas_id = $kas->kas_id;
                        $this->db->table('kas')->update($input2, array("kas_id" => $kas_id));
                        // echo $this->db->getLastQuery(); die;
                    } */

                    /* if ($kas_pettyid > 0) {
                        $this->db
                            ->table("kas")
                            ->delete(array("kas_id" =>  $kas_pettyid));
                    } */
                }
            }

            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'invvdrp_id') {
                    $inputdr[$e] = $this->request->getPost($e);
                }
            }
            // dd($inputdr);
            //****masukkan ke table invvdrp*****//
            $invvdr_no = $this->request->getPost("invvdr_no");
            $this->db->table('invvdrp')->insert($inputdr);
            // echo $this->db->getLastQuery();die; 
            $invvdrp_id = $this->db->insertID();

            //****update payment di table invvdr*****//
            // Hitung total pembayaran dari invvdrp
            $invvdr_id = $this->request->getGet("invvdr_id");
            $invvdrptotal = $this->db
                ->table("invvdrp")
                ->select("SUM(invvdrp_nominal) AS invvdrptotal")
                ->where("invvdr_id", $invvdr_id)
                ->get()
                ->getRow();
            // Pastikan hasil tidak null
            $invvdr_payment = $invvdrptotal ? $invvdrptotal->invvdrptotal : 0;
            $inputi = [
                "invvdr_payment" => $invvdr_payment
            ];
            $this->db
                ->table("invvdr")
                ->where("invvdr_id", $invvdr_id)
                ->update($inputi);

            //****input ke table kas*****//
            //cek apakah rekening nkl
            $rekening = $this->db->table("rekening")->where("rekening_id", $inputdr["invvdrp_from"])->get();
            $nkl = 0;
            foreach ($rekening->getResult() as $row) {
                if ($row->rekening_type == "NKL") {
                    $nkl = 1;
                }
            }
            if ($inputdr["invvdrp_from"] == -1) {
                $nkl = 1;
            }
            if ($nkl == 1) {
                $input["kas_rekdari"] = $inputdr["invvdrp_from"];
                $input["kas_rekke"] = $inputdr["invvdrp_to"];
                $input["kas_nominal"] = $inputdr["invvdrp_nominal"];
                $input["kas_qty"] = 1;
                $input["kas_uraian"] = "Pembayaran ke Vendor";
                $input["kas_keterangan"] = $inputdr["invvdrp_keterangan"];
                $input["invvdrp_id"] = $invvdrp_id;
                $input["kas_date"] = $inputdr["invvdrp_date"];
                $input["kas_type"] = "Kredit";
                $input["kas_total"] = $inputdr["invvdrp_nominal"];
                if ($inputdr["invvdrp_from"] == -1) {
                    $debettype = "pettycash";
                } else {
                    $debettype = "bigcash";
                }
                $input["kas_debettype"] = $debettype;
                /* $kas = $this->db->table("kas")
                ->where("kas_date <=", $input["kas_date"])
                ->orderBy("kas_id", "desc")
                ->limit("1")->get();
                $saldo = 0;
                $bigcash = 0;
                $pettycash = 0;

                if ($kas->getNumRows() > 0) {
                    foreach ($kas->getResult() as $kas) {
                        if ($input["kas_type"] == "Debet") {
                            $saldo = $kas->kas_saldo + $input["kas_total"];
                            if ($input["kas_debettype"] == "bigcash") {
                                $bigcash = $kas->kas_bigcash + $input["kas_total"];
                                $pettycash = $kas->kas_pettycash;
                            }
                            if ($input["kas_debettype"] == "pettycash") {
                                $pettycash = $kas->kas_pettycash + $input["kas_total"];
                                $bigcash = $kas->kas_bigcash;
                            }
                        } else {
                            $saldo = $kas->kas_saldo - $input["kas_total"];
                            if ($input["kas_debettype"] == "bigcash") {
                                $bigcash = $kas->kas_bigcash - $input["kas_total"];
                                $pettycash = $kas->kas_pettycash;
                            }
                            if ($input["kas_debettype"] == "pettycash") {
                                $pettycash = $kas->kas_pettycash - $input["kas_total"];
                                $bigcash = $kas->kas_bigcash;
                            }
                        }
                    }
                } else {
                    if ($input["kas_type"] == "Debet") {
                        $saldo = 0 + $input["kas_total"];
                        if ($input["kas_debettype"] == "bigcash") {
                            $bigcash = 0 + $input["kas_total"];
                            $pettycash = 0;
                        }
                        if ($input["kas_debettype"] == "pettycash") {
                            $pettycash = 0 + $input["kas_total"];
                            $bigcash = 0;
                        }
                    } else {
                        $saldo = 0 - $input["kas_total"];
                        if ($input["kas_debettype"] == "bigcash") {
                            $bigcash = 0 - $input["kas_total"];
                            $pettycash = 0;
                        }
                        if ($input["kas_debettype"] == "pettycash") {
                            $pettycash = 0 - $input["kas_total"];
                            $bigcash = 0;
                        }
                    }
                }
                $input["kas_saldo"] = $saldo;
                $input["kas_bigcash"] = $bigcash;
                $input["kas_pettycash"] = $pettycash; */

                $inv_temp = $this->request->getGet("invvdr_temp");
                $invvdrd = $this->db->table('invvdrd')
                    ->where("invvdr_temp", $inv_temp)
                    ->get();
                $jobdano      = array();
                foreach ($invvdrd->getResult() as $rinvvdrd) {
                    if ($rinvvdrd->job_dano !== '' && ! in_array($rinvvdrd->job_dano, $jobdano)) {
                        $jobdano[] = $rinvvdrd->job_dano;
                    }
                }
                $jobdanos      = implode(', ', $jobdano);
                $input["job_dano"] = $jobdanos;

                $builder = $this->db->table('kas');
                $builder->insert($input);
                // echo $this->db->getLastQuery(); die;
                $kas_id = $this->db->insertID();

                //input kas_id di inv vendor payment
                $inputkas["kas_id"] = $kas_id;
                $this->db->table('invvdrp')->update($inputkas, array("invvdrp_id" => $invvdrp_id));
            }

            //******update transaksi setelahnya******//
            /*  $kas = $this->db->table("kas")
                ->where("kas_date >", $input["kas_date"])
                ->orderBy("kas_date", "ASC")
                ->orderBy("kas_id", "ASC")
                ->get();
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

                $data["message"] = "Insert Data Success";
            }
            */

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;



        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'invvdrp_picture') {
                    $inputdr[$e] = $this->request->getPost($e);
                }
            }

            // dd($inputdr);
            $invvdrp_id = $this->request->getPost("invvdrp_id");
            $this->db->table('invvdrp')->update($inputdr, array("invvdrp_id" => $invvdrp_id));

            // Hitung total pembayaran dari invvdrp
            $invvdr_id = $this->request->getGet("invvdr_id");
            $invvdrptotal = $this->db
                ->table("invvdrp")
                ->select("SUM(invvdrp_nominal) AS invvdrptotal")
                ->where("invvdr_id", $invvdr_id)
                ->get()
                ->getRow();

            // Pastikan hasil tidak null
            $invvdr_payment = $invvdrptotal ? $invvdrptotal->invvdrptotal : 0;

            // Siapkan data update
            $inputi = [
                "invvdr_payment" => $invvdr_payment
            ];

            // Lakukan update pada tabel invvdr
            $this->db
                ->table("invvdr")
                ->where("invvdr_id", $invvdr_id)
                ->update($inputi);

            //*****Update Table Kas******// 
            //cek apakah rekening nkl
            $rekening = $this->db->table("rekening")->where("rekening_id", $inputdr["invvdrp_from"])->get();
            $nkl = 0;
            foreach ($rekening->getResult() as $row) {
                if ($row->rekening_type == "NKL") {
                    $nkl = 1;
                }
            }
            if ($inputdr["invvdrp_from"] == -1) {
                $nkl = 1;
            }
            if ($nkl == 1) {
                $kas = $this->db->table('kas')
                    ->where("invvdrp_id", $invvdrp_id)
                    ->get();
                if ($kas->getNumRows() > 0) {
                    if ($inputdr["invvdrp_from"] == "-1") {
                        $kas_debettype = "pettycash";
                    } else {
                        $kas_debettype = "bigcash";
                    }
                    $input["kas_debettype"] = $kas_debettype;
                    $input["kas_date"] = $inputdr["invvdrp_date"];
                    $input["kas_type"] = "Kredit";
                    $input["kas_total"] = $inputdr["invvdrp_nominal"];
                    $kas_id = $this->request->getPost("kas_id");

                    $input["kas_rekdari"] = $inputdr["invvdrp_from"];
                    $input["kas_rekke"] = $inputdr["invvdrp_to"];
                    $input["kas_nominal"] = $inputdr["invvdrp_nominal"];
                    $input["kas_qty"] = 1;
                    $input["kas_uraian"] = "Pembayaran ke Vendor";
                    $input["kas_keterangan"] = $inputdr["invvdrp_keterangan"];
                    $input["invvdrp_id"] = $invvdrp_id;

                    //apakah di tanggal yg sama ada id yg lebih rendah dari dia
                    /* $kas = $this->db
                    ->table("kas")
                    ->where("kas_date", $input["kas_date"])
                    ->where("kas_id <",  $kas_id)
                    ->orderBy("kas_date", "DESC")
                    ->orderBy("kas_id", "DESC")
                    ->limit(1)
                    ->get();
                    if ($kas->getNumRows() == 0) {
                        //jika tidak ada maka cari transksi tgl sebelumnya
                        $kas = $this->db
                            ->table("kas")
                            ->where("kas_date <", $input["kas_date"])
                            ->orderBy("kas_date", "DESC")
                            ->orderBy("kas_id", "DESC")
                            ->limit(1)
                            ->get();
                    } */
                    // echo $this->db->getLastQuery(); //die;
                    /* $saldo = 0;
                    $bigcash = 0;
                    $pettycash = 0;
                    foreach ($kas->getResult() as $kas) {
                        /* $saldoawal = $kas->kas_saldo;
                        $bigcashawal = $kas->kas_bigcash;
                        $pettycashawal = $kas->kas_pettycash; */
                    // echo $saldoawal . "==" . $bigcashawal . "==" . $pettycashawal;die;
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
                        } 
                    } */
                    /* $input["kas_saldo"] = $saldo;
                    $input["kas_bigcash"] = $bigcash;
                    $input["kas_pettycash"] = $pettycash; */

                    if ($input["kas_rekke"] != "-1") {
                        $input["kas_pettyid"] = 0;
                    }
                    // dd($input);

                    $inv_temp = $this->request->getGet("invvdr_temp");
                    $invvdrd = $this->db->table('invvdrd')
                        ->where("invvdr_temp", $inv_temp)
                        ->get();
                    $jobdano      = array();
                    foreach ($invvdrd->getResult() as $rinvvdrd) {
                        if ($rinvvdrd->job_dano !== '' && ! in_array($rinvvdrd->job_dano, $jobdano)) {
                            $jobdano[] = $rinvvdrd->job_dano;
                        }
                    }
                    $jobdanos      = implode(', ', $jobdano);
                    $input["job_dano"] = $jobdanos;
                    $this->db->table('kas')->update($input, array("kas_id" => $kas_id));
                    // echo $this->db->getLastQuery(); die;
                } else {
                    $input["kas_rekdari"] = $inputdr["invvdrp_from"];
                    $input["kas_rekke"] = $inputdr["invvdrp_to"];
                    $input["kas_nominal"] = $inputdr["invvdrp_nominal"];
                    $input["kas_qty"] = 1;
                    $input["kas_uraian"] = "Pembayaran ke Vendor";
                    $input["kas_keterangan"] = $inputdr["invvdrp_keterangan"];
                    $input["invvdrp_id"] = $invvdrp_id;
                    $input["kas_date"] = $inputdr["invvdrp_date"];
                    $input["kas_type"] = "Kredit";
                    $input["kas_total"] = $inputdr["invvdrp_nominal"];
                    if ($inputdr["invvdrp_from"] == -1) {
                        $debettype = "pettycash";
                    } else {
                        $debettype = "bigcash";
                    }
                    $input["kas_debettype"] = $debettype;

                    $inv_temp = $this->request->getGet("invvdr_temp");
                    $invvdrd = $this->db->table('invvdrd')
                        ->where("invvdr_temp", $inv_temp)
                        ->get();
                    $jobdano      = array();
                    foreach ($invvdrd->getResult() as $rinvvdrd) {
                        if ($rinvvdrd->job_dano !== '' && ! in_array($rinvvdrd->job_dano, $jobdano)) {
                            $jobdano[] = $rinvvdrd->job_dano;
                        }
                    }
                    $jobdanos      = implode(', ', $jobdano);
                    $input["job_dano"] = $jobdanos;

                    $builder = $this->db->table('kas');
                    $builder->insert($input);
                    // echo $this->db->getLastQuery(); die;
                    $kas_id = $this->db->insertID();

                    //input kas_id di inv vendor payment
                    $inputkas["kas_id"] = $kas_id;
                    $this->db->table('invvdrp')->update($inputkas, array("invvdrp_id" => $invvdrp_id));
                }
            } else {
                //delete kas
                $kas = $this->db->table('kas')
                    ->where("invvdrp_id", $invvdrp_id)
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

            //******update transaksi setelahnya******//
            //update dulu transaksi setelahnya yg satu tanggal dengan dia
            /* $kas = $this->db->table("kas")
                ->where("kas_id >", $kas_id)
                ->where("kas_date",  $input["kas_date"])
                ->orderBy("kas_id", "ASC")->get(); */
            // echo $this->db->getLastQuery();die;
            /* foreach ($kas->getResult() as $kas) {
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
                // dd($input2);
                $this->db->table('kas')->update($input2, array("kas_id" => $kas_id));
                // echo $this->db->getLastQuery(); die;
            } */
            // dd();
            //baru update transaksi ditanggal setelahnya dengan urutan by date asc dan id asc
            /* $kas = $this->db->table("kas")
                ->where("kas_date >",  $input["kas_date"])
                ->orderBy("kas_date", "ASC")
                ->orderBy("kas_id", "ASC")
                ->get();
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
                // dd($input2);
                $kas_id = $kas->kas_id;
                $this->db->table('kas')->update($input2, array("kas_id" => $kas_id));
                // echo $this->db->getLastQuery(); die;
            } */


            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }


        return $data;
    }
}
