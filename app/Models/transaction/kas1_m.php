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
        // Tentukan titik point bulan kemaren (akhir bulan kemaren)
        $tab1l = date("Y-m-t", strtotime("first day of last month"));

        //cari di table tbuku tanggal akhir 2 bulan sebelumnya
        $tab2l = date("Y-m-t", strtotime("first day of -2 months"));

        //////////////////////////////AWAL TUTUP BUKU ALL/////////////////////////////////////////

        //////////////////////////////TUTUP BUKU KAS/////////////////////////////////////////
        //apakah ditemukan tp di akhir bulan kemaren?
        $tp1bln = $this->db->table("tbuku")
            ->where("rekening_id", "0")
            ->where("tbuku_date", $tab1l)
            ->where("tbuku_type", "kas")
            ->get();
        // echo $this->db->getLastQuery();die;
        //jika tdk ditemukan maka lanjutkan proses
        if ($tp1bln->getNumRows() == 0) {
            //cari tp sebelumnya
            $tp2bln = $this->db->table("tbuku")
                ->where("rekening_id", "0")
                ->where("tbuku_date", $tab2l)
                ->where("tbuku_type", "kas")
                ->get();
            if ($tp2bln->getNumRows() > 0) {
                //jika ditemukan maka ambil datanya
                $row = $tp2bln->getRow();
                $rekening_id2 = $row->rekening_id;
                $tbuku_date2 = $row->tbuku_date;
                $tbuku_type2 = $row->tbuku_type;
                $tbuku_total2 = $row->tbuku_total;
                $tbuku_debet2 = $row->tbuku_debet;
                $tbuku_kredit2 = $row->tbuku_kredit;
            } else {
                //jika tdk ditemukan maka semua masih kosong
                $rekening_id2 = 0;
                $tbuku_date2 = 0;
                $tbuku_type2 = 0;
                $tbuku_total2 = 0;
                $tbuku_debet2 = 0;
                $tbuku_kredit2 = 0;
            }

            //hitung total 1 bulan kemaren 
            $kas = $this->db->table("kas")
                ->select("SUM(CASE 
                    WHEN kas_type = 'Debet' THEN kas_total 
                    WHEN kas_type = 'Kredit' THEN -kas_total 
                    ELSE 0 END) AS saldo_akhir")
                ->where("kas_date >", $tab2l)
                ->where("kas_date <=", $tab1l)
                ->get();
            $tkas = 0;
            if ($kas->getNumRows() > 0) {
                $tkas = $kas->getRow()->saldo_akhir;
            }

            //hitung debet 1 bulan kemaren 
            $kas = $this->db->table("kas")
                ->select("SUM(kas_total) AS saldo_akhir")
                ->where("kas_type", "Debet")
                ->where("kas_date >", $tab2l)
                ->where("kas_date <=", $tab1l)
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
                ->where("kas_date >", $tab2l)
                ->where("kas_date <=", $tab1l)
                ->get();
            $tkredit = 0;
            if ($kas->getNumRows() > 0) {
                $tkredit = $kas->getRow()->saldo_akhir;
            }
            $inputkas["rekening_id"] = 0;
            $inputkas["tbuku_date"] = $tab1l;
            $inputkas["tbuku_type"] = "kas";
            $inputkas["tbuku_total"] = $tkas + $tbuku_total2;
            $inputkas["tbuku_debet"] = $tdebet + $tbuku_debet2;
            $inputkas["tbuku_kredit"] = $tkredit + $tbuku_kredit2;
            // dd($inputkas);
            $this->db->table("tbuku")->insert($inputkas);
        }

        //////////////////////////////TUTUP BUKU BIGCASH/////////////////////////////////////////
        //apakah ditemukan tp di akhir bulan kemaren?
        $tp1bln = $this->db->table("tbuku")
            ->where("rekening_id", "0")
            ->where("tbuku_date", $tab1l)
            ->where("tbuku_type", "bigcash")
            ->get();
        // echo $this->db->getLastQuery();die;
        //jika tdk ditemukan maka lanjutkan proses
        if ($tp1bln->getNumRows() == 0) {
            //cari tp sebelumnya
            $tp2bln = $this->db->table("tbuku")
                ->where("rekening_id", "0")
                ->where("tbuku_date", $tab2l)
                ->where("tbuku_type", "bigcash")
                ->get();
            if ($tp2bln->getNumRows() > 0) {
                //jika ditemukan maka ambil datanya
                $row = $tp2bln->getRow();
                $rekening_id2 = $row->rekening_id;
                $tbuku_date2 = $row->tbuku_date;
                $tbuku_type2 = $row->tbuku_type;
                $tbuku_total2 = $row->tbuku_total;
                $tbuku_debet2 = $row->tbuku_debet;
                $tbuku_kredit2 = $row->tbuku_kredit;
            } else {
                //jika tdk ditemukan maka semua masih kosong
                $rekening_id2 = 0;
                $tbuku_date2 = 0;
                $tbuku_type2 = 0;
                $tbuku_total2 = 0;
                $tbuku_debet2 = 0;
                $tbuku_kredit2 = 0;
            }

            //hitung total 1 bulan kemaren 
            $kas = $this->db->table("kas")
                ->select("SUM(CASE 
                    WHEN kas_type = 'Debet' THEN kas_total 
                    WHEN kas_type = 'Kredit' THEN -kas_total 
                    ELSE 0 END) AS saldo_akhir")
                ->where("kas_debettype", "bigcash")
                ->where("kas_date >", $tab2l)
                ->where("kas_date <=", $tab1l)
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
                ->where("kas_date >", $tab2l)
                ->where("kas_date <=", $tab1l)
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
                ->where("kas_date >", $tab2l)
                ->where("kas_date <=", $tab1l)
                ->get();
            $tkredit = 0;
            if ($kas->getNumRows() > 0) {
                $tkredit = $kas->getRow()->saldo_akhir;
            }
            $inputbigcash["rekening_id"] = 0;
            $inputbigcash["tbuku_date"] = $tab1l;
            $inputbigcash["tbuku_type"] = "bigcash";
            $inputbigcash["tbuku_total"] = $tkas + $tbuku_total2;
            $inputbigcash["tbuku_debet"] = $tdebet + $tbuku_debet2;
            $inputbigcash["tbuku_kredit"] = $tkredit + $tbuku_kredit2;
            // dd($inputbigcash);
            $this->db->table("tbuku")->insert($inputbigcash);
        }

        //////////////////////////////TUTUP BUKU PETTYCASH/////////////////////////////////////////
        //apakah ditemukan tp di akhir bulan kemaren?
        $tp1bln = $this->db->table("tbuku")
            ->where("rekening_id", "0")
            ->where("tbuku_date", $tab1l)
            ->where("tbuku_type", "pettycash")
            ->get();
        // echo $this->db->getLastQuery();die;
        //jika tdk ditemukan maka lanjutkan proses
        if ($tp1bln->getNumRows() == 0) {
            //cari tp sebelumnya
            $tp2bln = $this->db->table("tbuku")
                ->where("rekening_id", "0")
                ->where("tbuku_date", $tab2l)
                ->where("tbuku_type", "pettycash")
                ->get();
            if ($tp2bln->getNumRows() > 0) {
                //jika ditemukan maka ambil datanya
                $row = $tp2bln->getRow();
                $rekening_id2 = $row->rekening_id;
                $tbuku_date2 = $row->tbuku_date;
                $tbuku_type2 = $row->tbuku_type;
                $tbuku_total2 = $row->tbuku_total;
                $tbuku_debet2 = $row->tbuku_debet;
                $tbuku_kredit2 = $row->tbuku_kredit;
            } else {
                //jika tdk ditemukan maka semua masih kosong
                $rekening_id2 = 0;
                $tbuku_date2 = 0;
                $tbuku_type2 = 0;
                $tbuku_total2 = 0;
                $tbuku_debet2 = 0;
                $tbuku_kredit2 = 0;
            }

            //hitung total 1 bulan kemaren 
            $kas = $this->db->table("kas")
                ->select("SUM(CASE 
                    WHEN kas_type = 'Debet' THEN kas_total 
                    WHEN kas_type = 'Kredit' THEN -kas_total 
                    ELSE 0 END) AS saldo_akhir")
                ->where("kas_debettype", "pettycash")
                ->where("kas_date >", $tab2l)
                ->where("kas_date <=", $tab1l)
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
                ->where("kas_date >", $tab2l)
                ->where("kas_date <=", $tab1l)
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
                ->where("kas_date >", $tab2l)
                ->where("kas_date <=", $tab1l)
                ->get();
            $tkredit = 0;
            if ($kas->getNumRows() > 0) {
                $tkredit = $kas->getRow()->saldo_akhir;
            }
            $inputpettycash["rekening_id"] = 0;
            $inputpettycash["tbuku_date"] = $tab1l;
            $inputpettycash["tbuku_type"] = "pettycash";
            $inputpettycash["tbuku_total"] = $tkas + $tbuku_total2;
            $inputpettycash["tbuku_debet"] = $tdebet + $tbuku_debet2;
            $inputpettycash["tbuku_kredit"] = $tkredit + $tbuku_kredit2;
            // dd($inputpettycash);
            $this->db->table("tbuku")->insert($inputpettycash);
        }

        //////////////////////////////AKHIR TUTUP BUKU ALL/////////////////////////////////////////


        //////////////////////////////AWAL TUTUP BUKU REKENING/////////////////////////////////////////

        $rekening = $this->db->table("rekening")
            ->where("rekening_type", "NKL")
            ->get();
        foreach ($rekening->getResult() as $rowrek) {

            //////////////////////////////TUTUP BUKU KAS/////////////////////////////////////////
            //apakah ditemukan tp di akhir bulan kemaren?
            $tp1bln = $this->db->table("tbuku")
                ->where("rekening_id", $rowrek->rekening_id)
                ->where("tbuku_date", $tab1l)
                ->where("tbuku_type", "kas")
                ->get();
            // echo $this->db->getLastQuery();die;
            //jika tdk ditemukan maka lanjutkan proses
            if ($tp1bln->getNumRows() == 0) {
                //cari tp sebelumnya
                $tp2bln = $this->db->table("tbuku")
                    ->where("rekening_id", $rowrek->rekening_id)
                    ->where("tbuku_date", $tab2l)
                    ->where("tbuku_type", "kas")
                    ->get();
                if ($tp2bln->getNumRows() > 0) {
                    //jika ditemukan maka ambil datanya
                    $row = $tp2bln->getRow();
                    $rekening_id2 = $row->rekening_id;
                    $tbuku_date2 = $row->tbuku_date;
                    $tbuku_type2 = $row->tbuku_type;
                    $tbuku_total2 = $row->tbuku_total;
                    $tbuku_debet2 = $row->tbuku_debet;
                    $tbuku_kredit2 = $row->tbuku_kredit;
                } else {
                    //jika tdk ditemukan maka semua masih kosong
                    $rekening_id2 = 0;
                    $tbuku_date2 = 0;
                    $tbuku_type2 = 0;
                    $tbuku_total2 = 0;
                    $tbuku_debet2 = 0;
                    $tbuku_kredit2 = 0;
                }

                //hitung total 1 bulan kemaren 
                $kas = $this->db->table("kas")
                    ->select("SUM(CASE 
                    WHEN kas_type = 'Debet' THEN kas_total 
                    WHEN kas_type = 'Kredit' THEN -kas_total 
                    ELSE 0 END) AS saldo_akhir")
                    ->where("kas_date >", $tab2l)
                    ->where("kas_date <=", $tab1l)
                    ->get();
                $tkas = 0;
                if ($kas->getNumRows() > 0) {
                    $tkas = $kas->getRow()->saldo_akhir;
                }

                //hitung debet 1 bulan kemaren 
                $kas = $this->db->table("kas")
                    ->select("SUM(kas_total) AS saldo_akhir")
                    ->where("kas_type", "Debet")
                    ->where("kas_date >", $tab2l)
                    ->where("kas_date <=", $tab1l)
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
                    ->where("kas_date >", $tab2l)
                    ->where("kas_date <=", $tab1l)
                    ->get();
                $tkredit = 0;
                if ($kas->getNumRows() > 0) {
                    $tkredit = $kas->getRow()->saldo_akhir;
                }
                $inputkas["rekening_id"] = $rowrek->rekening_id;
                $inputkas["tbuku_date"] = $tab1l;
                $inputkas["tbuku_type"] = "kas";
                $inputkas["tbuku_total"] = $tkas + $tbuku_total2;
                $inputkas["tbuku_debet"] = $tdebet + $tbuku_debet2;
                $inputkas["tbuku_kredit"] = $tkredit + $tbuku_kredit2;
                // dd($inputkas);
                $this->db->table("tbuku")->insert($inputkas);
            }

            //////////////////////////////TUTUP BUKU BIGCASH/////////////////////////////////////////
            //apakah ditemukan tp di akhir bulan kemaren?
            $tp1bln = $this->db->table("tbuku")
                ->where("rekening_id", $rowrek->rekening_id)
                ->where("tbuku_date", $tab1l)
                ->where("tbuku_type", "bigcash")
                ->get();
            // echo $this->db->getLastQuery();die;
            //jika tdk ditemukan maka lanjutkan proses
            if ($tp1bln->getNumRows() == 0) {
                //cari tp sebelumnya
                $tp2bln = $this->db->table("tbuku")
                    ->where("rekening_id", $rowrek->rekening_id)
                    ->where("tbuku_date", $tab2l)
                    ->where("tbuku_type", "bigcash")
                    ->get();
                if ($tp2bln->getNumRows() > 0) {
                    //jika ditemukan maka ambil datanya
                    $row = $tp2bln->getRow();
                    $rekening_id2 = $row->rekening_id;
                    $tbuku_date2 = $row->tbuku_date;
                    $tbuku_type2 = $row->tbuku_type;
                    $tbuku_total2 = $row->tbuku_total;
                    $tbuku_debet2 = $row->tbuku_debet;
                    $tbuku_kredit2 = $row->tbuku_kredit;
                } else {
                    //jika tdk ditemukan maka semua masih kosong
                    $rekening_id2 = 0;
                    $tbuku_date2 = 0;
                    $tbuku_type2 = 0;
                    $tbuku_total2 = 0;
                    $tbuku_debet2 = 0;
                    $tbuku_kredit2 = 0;
                }

                //hitung total 1 bulan kemaren 
                $kas = $this->db->table("kas")
                    ->select("SUM(CASE 
                    WHEN kas_type = 'Debet' THEN kas_total 
                    WHEN kas_type = 'Kredit' THEN -kas_total 
                    ELSE 0 END) AS saldo_akhir")
                    ->where("kas_debettype", "bigcash")
                    ->where("kas_date >", $tab2l)
                    ->where("kas_date <=", $tab1l)
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
                    ->where("kas_date >", $tab2l)
                    ->where("kas_date <=", $tab1l)
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
                    ->where("kas_date >", $tab2l)
                    ->where("kas_date <=", $tab1l)
                    ->get();
                $tkredit = 0;
                if ($kas->getNumRows() > 0) {
                    $tkredit = $kas->getRow()->saldo_akhir;
                }
                $inputbigcash["rekening_id"] = $rowrek->rekening_id;
                $inputbigcash["tbuku_date"] = $tab1l;
                $inputbigcash["tbuku_type"] = "bigcash";
                $inputbigcash["tbuku_total"] = $tkas + $tbuku_total2;
                $inputbigcash["tbuku_debet"] = $tdebet + $tbuku_debet2;
                $inputbigcash["tbuku_kredit"] = $tkredit + $tbuku_kredit2;
                // dd($inputbigcash);
                $this->db->table("tbuku")->insert($inputbigcash);
            }

            //////////////////////////////TUTUP BUKU PETTYCASH/////////////////////////////////////////
            //apakah ditemukan tp di akhir bulan kemaren?
            $tp1bln = $this->db->table("tbuku")
                ->where("rekening_id", $rowrek->rekening_id)
                ->where("tbuku_date", $tab1l)
                ->where("tbuku_type", "pettycash")
                ->get();
            // echo $this->db->getLastQuery();die;
            //jika tdk ditemukan maka lanjutkan proses
            if ($tp1bln->getNumRows() == 0) {
                //cari tp sebelumnya
                $tp2bln = $this->db->table("tbuku")
                    ->where("rekening_id", $rowrek->rekening_id)
                    ->where("tbuku_date", $tab2l)
                    ->where("tbuku_type", "pettycash")
                    ->get();
                if ($tp2bln->getNumRows() > 0) {
                    //jika ditemukan maka ambil datanya
                    $row = $tp2bln->getRow();
                    $rekening_id2 = $row->rekening_id;
                    $tbuku_date2 = $row->tbuku_date;
                    $tbuku_type2 = $row->tbuku_type;
                    $tbuku_total2 = $row->tbuku_total;
                    $tbuku_debet2 = $row->tbuku_debet;
                    $tbuku_kredit2 = $row->tbuku_kredit;
                } else {
                    //jika tdk ditemukan maka semua masih kosong
                    $rekening_id2 = 0;
                    $tbuku_date2 = 0;
                    $tbuku_type2 = 0;
                    $tbuku_total2 = 0;
                    $tbuku_debet2 = 0;
                    $tbuku_kredit2 = 0;
                }

                //hitung total 1 bulan kemaren 
                $kas = $this->db->table("kas")
                    ->select("SUM(CASE 
                    WHEN kas_type = 'Debet' THEN kas_total 
                    WHEN kas_type = 'Kredit' THEN -kas_total 
                    ELSE 0 END) AS saldo_akhir")
                    ->where("kas_debettype", "pettycash")
                    ->where("kas_date >", $tab2l)
                    ->where("kas_date <=", $tab1l)
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
                    ->where("kas_date >", $tab2l)
                    ->where("kas_date <=", $tab1l)
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
                    ->where("kas_date >", $tab2l)
                    ->where("kas_date <=", $tab1l)
                    ->get();
                $tkredit = 0;
                if ($kas->getNumRows() > 0) {
                    $tkredit = $kas->getRow()->saldo_akhir;
                }
                $inputpettycash["rekening_id"] = $rowrek->rekening_id;
                $inputpettycash["tbuku_date"] = $tab1l;
                $inputpettycash["tbuku_type"] = "pettycash";
                $inputpettycash["tbuku_total"] = $tkas + $tbuku_total2;
                $inputpettycash["tbuku_debet"] = $tdebet + $tbuku_debet2;
                $inputpettycash["tbuku_kredit"] = $tkredit + $tbuku_kredit2;
                // dd($inputpettycash);
                $this->db->table("tbuku")->insert($inputpettycash);
            }
        }
        //////////////////////////////AKHIR TUTUP BUKU REKENING/////////////////////////////////////////

        $titikpoint = $this->db->table("kas")
            ->where("SUBSTR(kas_date, 1, 7)", date("Y-m", strtotime("-1 months")))
            ->orderBy("kas_date", "DESC")
            ->orderBy("kas_id", "DESC")
            ->limit(1)
            ->get();

        if ($titikpoint->getNumRows() > 0) {
            $row = $titikpoint->getRow(); // langsung ambil 1 row
            $ketp = $row->kas_date;
            $ketpid = $row->kas_id;

            // Cari titik point sebelumnya yang punya kas_tp ≠ 0
            $titikpoints = $this->db->table("kas")
                ->where("kas_tp !=", "0")
                ->where("kas_date <", $ketp)
                ->orderBy("kas_date", "DESC")
                ->orderBy("kas_id", "DESC")
                ->limit(1)
                ->get();

            $daritp = "";
            $saldotps = 0;
            $saldotpbs = 0;
            $saldotpps = 0;
            if ($titikpoints->getNumRows() > 0) {
                $rows = $titikpoints->getRow();
                $daritp = $rows->kas_date;
                $saldotps = $rows->kas_tp;
                $saldotpbs = $rows->kas_tpb;
                $saldotpps = $rows->kas_tpp;
            }

            //************AWAL HITUNG KAS SEMUA REKENING****************/
            // Hitung total kas dari titik sebelumnya ke tgl terakhir bulan kemaren
            $build = $this->db
                ->table("kas")
                ->select("SUM(CASE 
                    WHEN kas_type = 'Debet' THEN kas_total 
                    WHEN kas_type = 'Kredit' THEN -kas_total 
                    ELSE 0 END) AS saldo_akhir");
            if (!empty($daritp)) {
                //jika ditemukan tp di tanggal sebelumnya
                $build->where("kas_date >", $daritp);
            }
            $build->where("kas_date <=", $ketp);
            $kas = $build->get();
            $saldon = 0;
            if ($kas->getNumRows() > 0) {
                $s = $kas->getRow();
                $saldon = $s->saldo_akhir;
            }
            $inputtp["kas_tp"] = $saldon + $saldotps;
            $wheretp["kas_id"] = $ketpid;
            $this->db->table("kas")->where($wheretp)->update($inputtp);

            // Hitung total bigcash dari titik sebelumnya ke tgl terakhir bulan kemaren
            $build = $this->db
                ->table("kas")
                ->select("SUM(CASE 
                    WHEN kas_type = 'Debet' THEN kas_total 
                    WHEN kas_type = 'Kredit' THEN -kas_total 
                    ELSE 0 END) AS saldo_akhir");
            if (!empty($daritp)) {
                $build->where("kas_date >", $daritp);
            }
            $build->where("kas_date <=", $ketp);
            $build->where("kas_debettype", "bigcash");
            $kas = $build->get();
            $saldon = 0;
            if ($kas->getNumRows() > 0) {
                $s = $kas->getRow();
                $saldon = $s->saldo_akhir;
            }
            $inputtp["kas_tpb"] = $saldon + $saldotpbs;
            $wheretp["kas_id"] = $ketpid;
            $this->db->table("kas")->where($wheretp)->update($inputtp);

            // Hitung total kas dari titik sebelumnya ke tgl terakhir bulan kemaren
            $build = $this->db
                ->table("kas")
                ->select("SUM(CASE 
                    WHEN kas_type = 'Debet' THEN kas_total 
                    WHEN kas_type = 'Kredit' THEN -kas_total 
                    ELSE 0 END) AS saldo_akhir");
            if (!empty($daritp)) {
                $build->where("kas_date >", $daritp);
            }
            $build->where("kas_date <=", $ketp);
            $build->where("kas_debettype", "pettycash");
            $kas = $build->get();
            $saldon = 0;
            if ($kas->getNumRows() > 0) {
                $s = $kas->getRow();
                $saldon = $s->saldo_akhir;
            }
            $inputtp["kas_tpp"] = $saldon + $saldotpps;
            $wheretp["kas_id"] = $ketpid;
            $this->db->table("kas")->where($wheretp)->update($inputtp);

            //jadikan sembunyi transaksi sebelum titik point sekarang
            $inputsem["kas_tps"] = 1;
            $build = $this->db->table("kas");
            if (!empty($daritp)) {
                $build->where("kas_date >", $daritp);
            }
            $build->where("kas_date <=", $ketp);
            $build->update($inputsem);
        }

        // echo $this->db->getLastQuery(); die;
        //************AKHIR HITUNG KAS SEMUA REKENING****************/

        //////////////////////////////TUTUP BUKU/////////////////////////////////////////

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
            // dd($input);
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
