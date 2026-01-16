<?php

namespace App\Models\transaction;

use App\Models\core_m;

class kas_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek kas
        if ($this->request->getPost("kas_id")) {
            $kasd["kas_id"] = $this->request->getPost("kas_id");
        } else {
            $kasd["kas_id"] = -1;
        }
        $us = $this->db
            ->table("kas")
            ->getWhere($kasd);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "kas_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $kas) {
                foreach ($this->db->getFieldNames('kas') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $kas->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('kas') as $field) {
                $data[$field] = "";
            }
        }

        //////////////////////////////TUTUP BUKU/////////////////////////////////////////
        if (isset($_POST["tp"])) {
            // Tentukan titik point bulan kemaren (akhir bulan kemaren)
            $tab1l = date("Y-m-t", strtotime("first day of last month"));

            //cari di table tbuku tanggal akhir 2 bulan sebelumnya
            $tab2l = date("Y-m-t", strtotime("first day of -2 months"));

            //tgl tp
            $tgltp = $_POST["kas_date"];

            //////////////////////////////AWAL TUTUP BUKU ALL/////////////////////////////////////////

            //////////////////////////////TUTUP BUKU KAS/////////////////////////////////////////       
            //rekening_id = 0 artinya semua rekening

            //cari tp sebelumnya
            $tpsbl = $this->db->table("tbuku")
                ->where("rekening_id", "0")
                ->where("tbuku_type", "kas")
                ->where("tbuku_date <", $tgltp)
                ->orderBy("tbuku_date", "DESC")
                ->limit(1)
                ->get();
            if ($tpsbl->getNumRows() > 0) {
                //jika ditemukan maka ambil datanya
                $row = $tpsbl->getRow();
                $rekeningsbl = $row->rekening_id;
                $tgltpsbl = $row->tbuku_date;
                $typesbl = $row->tbuku_type;
                $totalsbl = $row->tbuku_total;
                $debetsbl = $row->tbuku_debet;
                $kreditsbl = $row->tbuku_kredit;
            } else {
                //jika tdk ditemukan maka semua masih kosong
                $rekeningsbl = 0;
                $tgltpsbl = 0;
                $typesbl = 0;
                $totalsbl = 0;
                $debetsbl = 0;
                $kreditsbl = 0;
            }

            //hitung total 1 bulan kemaren 
            $kas = $this->db->table("kas")
                ->select("SUM(CASE 
                    WHEN kas_type = 'Debet' THEN kas_total 
                    WHEN kas_type = 'Kredit' THEN -kas_total 
                    ELSE 0 END) AS saldo_akhir")
                ->where("kas_date >", $tgltpsbl)
                ->where("kas_date <=", $tgltp)
                ->get();
            $tkas = 0;
            if ($kas->getNumRows() > 0) {
                $tkas = $kas->getRow()->saldo_akhir;
            }


            //hitung debet 1 bulan kemaren 
            $kas = $this->db->table("kas")
                ->select("SUM(kas_total) AS saldo_akhir")
                ->where("kas_type", "Debet")
                ->where("kas_date >", $tgltpsbl)
                ->where("kas_date <=", $tgltp)
                ->get();
            // echo $this->db->getLastQuery();die;
            $tdebet = 0;
            if ($kas->getNumRows() > 0) {
                $tdebet = $kas->getRow()->saldo_akhir;
            }

            //hitung kredit 1 bulan kemaren 
            $kas = $this->db->table("kas")
                ->select("SUM(kas_total) AS saldo_akhir")
                ->where("kas_type", "Kredit")
                ->where("kas_date >", $tgltpsbl)
                ->where("kas_date <=", $tgltp)
                ->get();
            $tkredit = 0;
            if ($kas->getNumRows() > 0) {
                $tkredit = $kas->getRow()->saldo_akhir;
            }

            $inputkas["rekening_id"] = 0;
            $inputkas["tbuku_date"] = $tgltp;
            $inputkas["tbuku_type"] = "kas";
            $inputkas["tbuku_total"] = $tkas + $totalsbl;
            $inputkas["tbuku_debet"] = $tdebet + $debetsbl;
            $inputkas["tbuku_kredit"] = $tkredit + $kreditsbl;
            // dd($inputkas);
            $this->db->table("tbuku")->insert($inputkas);


            //////////////////////////////TUTUP BUKU BIGCASH/////////////////////////////////////////

            //cari tp sebelumnya
            $tpsbl = $this->db->table("tbuku")
                ->where("rekening_id", "0")
                ->where("tbuku_type", "bigcash")
                ->where("tbuku_date <", $tgltp)
                ->orderBy("tbuku_date", "DESC")
                ->get();
            if ($tpsbl->getNumRows() > 0) {
                //jika ditemukan maka ambil datanya
                $row = $tpsbl->getRow();
                $rekeningsbl = $row->rekening_id;
                $tgltpsbl = $row->tbuku_date;
                $typesbl = $row->tbuku_type;
                $totalsbl = $row->tbuku_total;
                $debetsbl = $row->tbuku_debet;
                $kreditsbl = $row->tbuku_kredit;
            } else {
                //jika tdk ditemukan maka semua masih kosong
                $rekeningsbl = 0;
                $tgltpsbl = 0;
                $typesbl = 0;
                $totalsbl = 0;
                $debetsbl = 0;
                $kreditsbl = 0;
            }

            //hitung total 1 bulan kemaren 
            $kas = $this->db->table("kas")
                ->select("SUM(CASE 
                    WHEN kas_type = 'Debet' THEN kas_total 
                    WHEN kas_type = 'Kredit' THEN -kas_total 
                    ELSE 0 END) AS saldo_akhir")
                ->where("kas_debettype", "bigcash")
                ->where("kas_date >", $tgltpsbl)
                ->where("kas_date <=", $tgltp)
                ->get();
            $tkas = 0;
            if ($kas->getNumRows() > 0) {
                $tkas = $kas->getRow()->saldo_akhir;
            }

            //hitung debet 1 bulan kemaren 
            $kas = $this->db->table("kas")
                ->select("SUM(kas_total) AS saldo_akhir")
                ->where("kas_debettype", "bigcash")
                ->where("kas_type", "Debet")
                ->where("kas_date >", $tgltpsbl)
                ->where("kas_date <=", $tgltp)
                ->get();
            // echo $this->db->getLastQuery();die;
            $tdebet = 0;
            if ($kas->getNumRows() > 0) {
                $tdebet = $kas->getRow()->saldo_akhir;
            }

            //hitung kredit 1 bulan kemaren 
            $kas = $this->db->table("kas")
                ->select("SUM(kas_total) AS saldo_akhir")
                ->where("kas_debettype", "bigcash")
                ->where("kas_type", "Kredit")
                ->where("kas_date >", $tgltpsbl)
                ->where("kas_date <=", $tgltp)
                ->get();
            $tkredit = 0;
            if ($kas->getNumRows() > 0) {
                $tkredit = $kas->getRow()->saldo_akhir;
            }


            $inputbigcash["rekening_id"] = 0;
            $inputbigcash["tbuku_date"] = $tgltp;
            $inputbigcash["tbuku_type"] = "bigcash";
            $inputbigcash["tbuku_total"] = $tkas + $totalsbl;
            $inputbigcash["tbuku_debet"] = $tdebet + $debetsbl;
            $inputbigcash["tbuku_kredit"] = $tkredit + $kreditsbl;
            // dd($inputbigcash);
            $this->db->table("tbuku")->insert($inputbigcash);


            //////////////////////////////TUTUP BUKU PETTYCASH/////////////////////////////////////////

            //cari tp sebelumnya
            $tpsbl = $this->db->table("tbuku")
                ->where("rekening_id", "0")
                ->where("tbuku_type", "pettycash")
                ->where("tbuku_date <", $tgltp)
                ->orderBy("tbuku_date", "DESC")
                ->get();
            if ($tpsbl->getNumRows() > 0) {
                //jika ditemukan maka ambil datanya
                $row = $tpsbl->getRow();
                $rekeningsbl = $row->rekening_id;
                $tgltpsbl = $row->tbuku_date;
                $typesbl = $row->tbuku_type;
                $totalsbl = $row->tbuku_total;
                $debetsbl = $row->tbuku_debet;
                $kreditsbl = $row->tbuku_kredit;
            } else {
                //jika tdk ditemukan maka semua masih kosong
                $rekeningsbl = 0;
                $tgltpsbl = 0;
                $typesbl = 0;
                $totalsbl = 0;
                $debetsbl = 0;
                $kreditsbl = 0;
            }

            //hitung total 1 bulan kemaren 
            $kas = $this->db->table("kas")
                ->select("SUM(CASE 
                    WHEN kas_type = 'Debet' THEN kas_total 
                    WHEN kas_type = 'Kredit' THEN -kas_total 
                    ELSE 0 END) AS saldo_akhir")
                ->where("kas_debettype", "pettycash")
                ->where("kas_date >", $tgltpsbl)
                ->where("kas_date <=", $tgltp)
                ->get();
            $tkas = 0;
            if ($kas->getNumRows() > 0) {
                $tkas = $kas->getRow()->saldo_akhir;
            }

            //hitung debet 1 bulan kemaren 
            $kas = $this->db->table("kas")
                ->select("SUM(kas_total) AS saldo_akhir")
                ->where("kas_debettype", "pettycash")
                ->where("kas_type", "Debet")
                ->where("kas_date >", $tgltpsbl)
                ->where("kas_date <=", $tgltp)
                ->get();
            // echo $this->db->getLastQuery();die;
            $tdebet = 0;
            if ($kas->getNumRows() > 0) {
                $tdebet = $kas->getRow()->saldo_akhir;
            }

            //hitung kredit 1 bulan kemaren 
            $kas = $this->db->table("kas")
                ->select("SUM(kas_total) AS saldo_akhir")
                ->where("kas_debettype", "pettycash")
                ->where("kas_type", "Kredit")
                ->where("kas_date >", $tgltpsbl)
                ->where("kas_date <=", $tgltp)
                ->get();
            $tkredit = 0;
            if ($kas->getNumRows() > 0) {
                $tkredit = $kas->getRow()->saldo_akhir;
            }
            $inputpettycash["rekening_id"] = 0;
            $inputpettycash["tbuku_date"] = $tgltp;
            $inputpettycash["tbuku_type"] = "pettycash";
            $inputpettycash["tbuku_total"] = $tkas + $totalsbl;
            $inputpettycash["tbuku_debet"] = $tdebet + $debetsbl;
            $inputpettycash["tbuku_kredit"] = $tkredit + $kreditsbl;
            // dd($inputpettycash);
            $this->db->table("tbuku")->insert($inputpettycash);


            //////////////////////////////AKHIR TUTUP BUKU ALL/////////////////////////////////////////


            //////////////////////////////AWAL TUTUP BUKU REKENING/////////////////////////////////////////

            $rekening = $this->db->table("rekening")
                ->where("rekening_type", "NKL")
                ->get();
            foreach ($rekening->getResult() as $rowrek) {

                //////////////////////////////TUTUP BUKU KAS/////////////////////////////////////////

                //cari tp sebelumnya
                $tpsbl = $this->db->table("tbuku")
                    ->where("rekening_id", $rowrek->rekening_id)
                    ->where("tbuku_type", "kas")
                    ->where("tbuku_date <", $tgltp)
                    ->orderBy("tbuku_date", "DESC")
                    ->get();
                if ($tpsbl->getNumRows() > 0) {
                    //jika ditemukan maka ambil datanya
                    $row = $tpsbl->getRow();
                    $rekeningsbl = $row->rekening_id;
                    $tgltpsbl = $row->tbuku_date;
                    $typesbl = $row->tbuku_type;
                    $totalsbl = $row->tbuku_total;
                    $debetsbl = $row->tbuku_debet;
                    $kreditsbl = $row->tbuku_kredit;
                } else {
                    //jika tdk ditemukan maka semua masih kosong
                    $rekeningsbl = 0;
                    $tgltpsbl = 0;
                    $typesbl = 0;
                    $totalsbl = 0;
                    $debetsbl = 0;
                    $kreditsbl = 0;
                }

                //hitung total 1 bulan kemaren 
                $kas = $this->db->table("kas")
                    ->select("SUM(CASE 
                    WHEN kas_type = 'Debet' THEN kas_total 
                    WHEN kas_type = 'Kredit' THEN -kas_total 
                    ELSE 0 END) AS saldo_akhir")
                    ->groupStart()
                    ->where("kas_rekdari", $rowrek->rekening_id)
                    ->orWhere("kas_rekke", $rowrek->rekening_id)
                    ->groupEnd()
                    ->where("kas_date >", $tgltpsbl)
                    ->where("kas_date <=", $tgltp)
                    ->get();
                $tkas = 0;
                if ($kas->getNumRows() > 0) {
                    $tkas = $kas->getRow()->saldo_akhir;
                }


                //hitung debet 1 bulan kemaren 
                $kas = $this->db->table("kas")
                    ->select("SUM(kas_total) AS saldo_akhir")
                    ->groupStart()
                    ->where("kas_rekdari", $rowrek->rekening_id)
                    ->orWhere("kas_rekke", $rowrek->rekening_id)
                    ->groupEnd()
                    ->where("kas_type", "Debet")
                    ->where("kas_date >", $tgltpsbl)
                    ->where("kas_date <=", $tgltp)
                    ->get();
                // echo $this->db->getLastQuery();die;
                $tdebet = 0;
                if ($kas->getNumRows() > 0) {
                    $tdebet = $kas->getRow()->saldo_akhir;
                }


                //hitung kredit 1 bulan kemaren 
                $kas = $this->db->table("kas")
                    ->select("SUM(kas_total) AS saldo_akhir")
                    ->groupStart()
                    ->where("kas_rekdari", $rowrek->rekening_id)
                    ->orWhere("kas_rekke", $rowrek->rekening_id)
                    ->groupEnd()
                    ->where("kas_type", "Kredit")
                    ->where("kas_date >", $tgltpsbl)
                    ->where("kas_date <=", $tgltp)
                    ->get();
                $tkredit = 0;
                if ($kas->getNumRows() > 0) {
                    $tkredit = $kas->getRow()->saldo_akhir;
                }

                $inputkas["rekening_id"] = $rowrek->rekening_id;
                $inputkas["tbuku_date"] = $tgltp;
                $inputkas["tbuku_type"] = "kas";
                $inputkas["tbuku_total"] = $tkas + $totalsbl;
                $inputkas["tbuku_debet"] = $tdebet + $debetsbl;
                $inputkas["tbuku_kredit"] = $tkredit + $kreditsbl;
                // dd($inputkas);
                $this->db->table("tbuku")->insert($inputkas);


                //////////////////////////////TUTUP BUKU BIGCASH/////////////////////////////////////////

                //cari tp sebelumnya
                $tpsbl = $this->db->table("tbuku")
                    ->where("rekening_id", $rowrek->rekening_id)
                    ->where("tbuku_type", "bigcash")
                    ->where("tbuku_date <", $tgltp)
                    ->orderBy("tbuku_date", "DESC")
                    ->get();
                if ($tpsbl->getNumRows() > 0) {
                    //jika ditemukan maka ambil datanya
                    $row = $tpsbl->getRow();
                    $rekeningsbl = $row->rekening_id;
                    $tgltpsbl = $row->tbuku_date;
                    $typesbl = $row->tbuku_type;
                    $totalsbl = $row->tbuku_total;
                    $debetsbl = $row->tbuku_debet;
                    $kreditsbl = $row->tbuku_kredit;
                } else {
                    //jika tdk ditemukan maka semua masih kosong
                    $rekeningsbl = 0;
                    $tgltpsbl = 0;
                    $typesbl = 0;
                    $totalsbl = 0;
                    $debetsbl = 0;
                    $kreditsbl = 0;
                }


                //hitung total 1 bulan kemaren 
                $kas = $this->db->table("kas")
                    ->select("SUM(CASE 
                    WHEN kas_type = 'Debet' THEN kas_total 
                    WHEN kas_type = 'Kredit' THEN -kas_total 
                    ELSE 0 END) AS saldo_akhir")
                    ->groupStart()
                    ->where("kas_rekdari", $rowrek->rekening_id)
                    ->orWhere("kas_rekke", $rowrek->rekening_id)
                    ->groupEnd()
                    ->where("kas_debettype", "bigcash")
                    ->where("kas_date >", $tgltpsbl)
                    ->where("kas_date <=", $tgltp)
                    ->get();
                $tkas = 0;
                if ($kas->getNumRows() > 0) {
                    $tkas = $kas->getRow()->saldo_akhir;
                }



                //hitung debet 1 bulan kemaren 
                $kas = $this->db->table("kas")
                    ->select("SUM(kas_total) AS saldo_akhir")
                    ->groupStart()
                    ->where("kas_rekdari", $rowrek->rekening_id)
                    ->orWhere("kas_rekke", $rowrek->rekening_id)
                    ->groupEnd()
                    ->where("kas_debettype", "bigcash")
                    ->where("kas_type", "Debet")
                    ->where("kas_date >", $tgltpsbl)
                    ->where("kas_date <=", $tgltp)
                    ->get();
                // echo $this->db->getLastQuery();die;
                $tdebet = 0;
                if ($kas->getNumRows() > 0) {
                    $tdebet = $kas->getRow()->saldo_akhir;
                }


                //hitung kredit 1 bulan kemaren 
                $kas = $this->db->table("kas")
                    ->select("SUM(kas_total) AS saldo_akhir")
                    ->groupStart()
                    ->where("kas_rekdari", $rowrek->rekening_id)
                    ->orWhere("kas_rekke", $rowrek->rekening_id)
                    ->groupEnd()
                    ->where("kas_debettype", "bigcash")
                    ->where("kas_type", "Kredit")
                    ->where("kas_date >", $tgltpsbl)
                    ->where("kas_date <=", $tgltp)
                    ->get();
                //    echo $this->db->getLastQuery();die; 
                $tkredit = 0;
                if ($kas->getNumRows() > 0) {
                    $tkredit = $kas->getRow()->saldo_akhir;
                }



                $inputbigcash["rekening_id"] = $rowrek->rekening_id;
                $inputbigcash["tbuku_date"] = $tgltp;
                $inputbigcash["tbuku_type"] = "bigcash";
                $inputbigcash["tbuku_total"] = $tkas + $totalsbl;
                $inputbigcash["tbuku_debet"] = $tdebet + $debetsbl;
                $inputbigcash["tbuku_kredit"] = $tkredit + $kreditsbl;
                // dd($inputbigcash);
                $this->db->table("tbuku")->insert($inputbigcash);


                //////////////////////////////TUTUP BUKU PETTYCASH/////////////////////////////////////////

                //cari tp sebelumnya
                $tpsbl = $this->db->table("tbuku")
                    ->where("rekening_id", $rowrek->rekening_id)
                    ->where("tbuku_type", "pettycash")
                    ->where("tbuku_date <", $tgltp)
                    ->orderBy("tbuku_date", "DESC")
                    ->get();
                if ($tpsbl->getNumRows() > 0) {
                    //jika ditemukan maka ambil datanya
                    $row = $tpsbl->getRow();
                    $rekeningsbl = $row->rekening_id;
                    $tgltpsbl = $row->tbuku_date;
                    $typesbl = $row->tbuku_type;
                    $totalsbl = $row->tbuku_total;
                    $debetsbl = $row->tbuku_debet;
                    $kreditsbl = $row->tbuku_kredit;
                } else {
                    //jika tdk ditemukan maka semua masih kosong
                    $rekeningsbl = 0;
                    $tgltpsbl = 0;
                    $typesbl = 0;
                    $totalsbl = 0;
                    $debetsbl = 0;
                    $kreditsbl = 0;
                }

                //hitung total 1 bulan kemaren 
                $kas = $this->db->table("kas")
                    ->select("SUM(CASE 
                    WHEN kas_type = 'Debet' THEN kas_total 
                    WHEN kas_type = 'Kredit' THEN -kas_total 
                    ELSE 0 END) AS saldo_akhir")
                    ->groupStart()
                    ->where("kas_rekdari", $rowrek->rekening_id)
                    ->orWhere("kas_rekke", $rowrek->rekening_id)
                    ->groupEnd()
                    ->where("kas_debettype", "pettycash")
                    ->where("kas_date >", $tgltpsbl)
                    ->where("kas_date <=", $tgltp)
                    ->get();
                $tkas = 0;
                if ($kas->getNumRows() > 0) {
                    $tkas = $kas->getRow()->saldo_akhir;
                }

                //hitung debet 1 bulan kemaren 
                $kas = $this->db->table("kas")
                    ->select("SUM(kas_total) AS saldo_akhir")
                    ->groupStart()
                    ->where("kas_rekdari", $rowrek->rekening_id)
                    ->orWhere("kas_rekke", $rowrek->rekening_id)
                    ->groupEnd()
                    ->where("kas_debettype", "pettycash")
                    ->where("kas_type", "Debet")
                    ->where("kas_date >", $tgltpsbl)
                    ->where("kas_date <=", $tgltp)
                    ->get();
                // echo $this->db->getLastQuery();die;
                $tdebet = 0;
                if ($kas->getNumRows() > 0) {
                    $tdebet = $kas->getRow()->saldo_akhir;
                }

                //hitung kredit 1 bulan kemaren 
                $kas = $this->db->table("kas")
                    ->select("SUM(kas_total) AS saldo_akhir")
                    ->groupStart()
                    ->where("kas_rekdari", $rowrek->rekening_id)
                    ->orWhere("kas_rekke", $rowrek->rekening_id)
                    ->groupEnd()
                    ->where("kas_debettype", "pettycash")
                    ->where("kas_type", "Kredit")
                    ->where("kas_date >", $tgltpsbl)
                    ->where("kas_date <=", $tgltp)
                    ->get();
                $tkredit = 0;
                if ($kas->getNumRows() > 0) {
                    $tkredit = $kas->getRow()->saldo_akhir;
                }
                $inputpettycash["rekening_id"] = $rowrek->rekening_id;
                $inputpettycash["tbuku_date"] = $tgltp;
                $inputpettycash["tbuku_type"] = "pettycash";
                $inputpettycash["tbuku_total"] = $tkas + $totalsbl;
                $inputpettycash["tbuku_debet"] = $tdebet + $debetsbl;
                $inputpettycash["tbuku_kredit"] = $tkredit + $kreditsbl;
                // dd($inputpettycash);
                $this->db->table("tbuku")->insert($inputpettycash);
            }
            /////////////////////////////////AKHIR TUTUP BUKU REKENING///////////////////////////////////
        }
        /////////////////////////////////////////TUTUP BUKU/////////////////////////////////////////

        //delete
        if ($this->request->getPost("delete") == "OK") {
            $kas_id =   $this->request->getPost("kas_id");
            $kas_date =   $this->request->getPost("kas_date");
            $kas_pettyid =   $this->request->getPost("kas_pettyid");

            $this->db
                ->table("kas")
                ->delete(array("kas_id" =>  $kas_id));


            //apakah di tanggal yg sama ada id yg lebih rendah dari dia
            $kas = $this->db
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
            }

            // echo $saldo."-".$bigcash."-".$pettycash;die;
            //******update transaksi setelahnya******//
            //update dulu transaksi setelahnya yg satu tanggal dengan dia
            $kas = $this->db->table("kas")
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
            }
            // dd();
            //baru update transaksi ditanggal setelahnya dengan urutan by date asc dan id asc
            $kas = $this->db->table("kas")
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
            }

            if ($kas_pettyid > 0) {
                $this->db
                    ->table("kas")
                    ->delete(array("kas_id" =>  $kas_pettyid));
            }
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'kas_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }

            // dd($input);
            $kas = $this->db->table("kas")
                ->where("kas_date <=", $input["kas_date"])
                ->orderBy("kas_id", "desc")
                ->limit("1")->get();
            $saldo = 0;
            $bigcash = 0;
            $pettycash = 0;

            if ($kas->getNumRows() > 0) {
                foreach ($kas->getResult() as $kas) {
                    if ($input["kas_type"] == "Debet") {
                        $saldo = (float)$kas->kas_saldo + (float)$input["kas_total"];
                        if ($input["kas_debettype"] == "bigcash") {
                            $bigcash = (float)$kas->kas_bigcash + (float)$input["kas_total"];
                            $pettycash = $kas->kas_pettycash;
                        }
                        if ($input["kas_debettype"] == "pettycash") {
                            $pettycash = (float)$kas->kas_pettycash + (float)$input["kas_total"];
                            $bigcash = $kas->kas_bigcash;
                        }
                    } else {
                        $saldo = (float)$kas->kas_saldo - (float)$input["kas_total"];
                        if ($input["kas_debettype"] == "bigcash") {
                            $bigcash = (float)$kas->kas_bigcash - (float)$input["kas_total"];
                            $pettycash = $kas->kas_pettycash;
                        }
                        if ($input["kas_debettype"] == "pettycash") {
                            $pettycash = (float)$kas->kas_pettycash - (float)$input["kas_total"];
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
            $input["kas_pettycash"] = $pettycash;

            $builder = $this->db->table('kas');
            $builder->insert($input);
            // echo $this->db->getLastQuery(); die;
            $kas_id = $this->db->insertID();


            //******update transaksi setelahnya******//
            $kas = $this->db->table("kas")
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
            //echo $_POST["create"];die;
        }

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'kas_picture' && $e != 'kas_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }

            $kas_id = $this->request->getPost("kas_id");
            //apakah di tanggal yg sama ada id yg lebih rendah dari dia
            $kas = $this->db
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
            }
            // echo $this->db->getLastQuery(); //die;
            $saldo = 0;
            $bigcash = 0;
            $pettycash = 0;
            foreach ($kas->getResult() as $kas) {
                $saldoawal = $kas->kas_saldo;
                $bigcashawal = $kas->kas_bigcash;
                $pettycashawal = $kas->kas_pettycash;
                // echo $saldoawal . "==" . $bigcashawal . "==" . $pettycashawal;die;
                if ($input["kas_type"] == "Debet") {
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
            }
            $input["kas_saldo"] = $saldo;
            $input["kas_bigcash"] = $bigcash;
            $input["kas_pettycash"] = $pettycash;

            if ($input["kas_rekke"] != "-1") {
                $input["kas_pettyid"] = 0;
            }
            // dd($input);
            $this->db->table('kas')->update($input, array("kas_id" => $kas_id));
            // echo $this->db->getLastQuery(); die;

            //******update transaksi setelahnya******//
            //update dulu transaksi setelahnya yg satu tanggal dengan dia
            $kas = $this->db->table("kas")
                ->where("kas_id >", $kas_id)
                ->where("kas_date",  $input["kas_date"])
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
            }
            // dd();
            //baru update transaksi ditanggal setelahnya dengan urutan by date asc dan id asc
            $kas = $this->db->table("kas")
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
            }

            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }

        //transfer ke pettycash
        /* if (($this->request->getPost("kas_debettype") == "bigcash" && $this->request->getPost("kas_rekke") == "-1" && $this->request->getPost("kas_type") == "Kredit") || $this->request->getPost("kas_pettyid") > 0) {
            if ($this->request->getPost("kas_debettype") == "bigcash" && $this->request->getPost("kas_rekke") == "-1" && $this->request->getPost("kas_type") == "Kredit") {
                foreach ($this->request->getPost() as $e => $f) {
                    if ($e != 'change' && $e != 'create' && $e != 'kas_picture' && $e != 'kas_id') {
                        $inputp[$e] = $this->request->getPost($e);
                    }
                }
                $inputp["kas_bigid"] = $kas_id;
                $inputp["kas_type"] = "Debet";
                $inputp["kas_debettype"] = "pettycash";
                // dd($this->request->getPost());
                if ($this->request->getPost("create") == "OK" || $this->request->getPost("kas_pettyid") == 0) {
                    $this->db->table("kas")->insert($inputp);
                    $inserted_id = $this->db->insertID();
                    $inputpbigcash["kas_pettyid"] = $inserted_id;
                    $this->db->table("kas")->where("kas_id", $kas_id)->update($inputpbigcash);
                }

                $where["kas_id"] = $this->request->getPost("kas_pettyid");
                if ($this->request->getPost("change") == "OK" && $this->request->getPost("kas_pettyid") > 0) {

                    $this->db->table("kas")->where($where)->update($inputp);
                }
            } else {
                $this->db
                    ->table("kas")
                    ->delete(array("kas_id" =>  $this->request->getPost("kas_pettyid")));
            }
        } */
        return $data;
    }
}
