<?php

namespace App\Models\master;

use App\Models\core_m;

class mprofile_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";


        //upload image
        $data['uploaduser_picture'] = "";
        if (isset($_FILES['user_picture']) && $_FILES['user_picture']['name'] != "") {
            // $request = \Config\Services::request();
            $file = $this->request->getFile('user_picture');
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
                $direktori = 'images/user_picture'; //definisikan direktori upload            
                $user_picture = str_replace(' ', '_', $name);
                $user_picture = date("H_i_s_") . $user_picture; //definisikan nama fiel yang baru
                $map = directory_map($direktori, FALSE, TRUE); // List direktori

                //Cek File apakah ada 
                foreach ($map as $key) {
                    if ($key == $user_picture) {
                        delete_files($direktori, $user_picture); //Hapus terlebih dahulu jika file ada
                    }
                }
                //Metode Upload Pilih salah satu
                //$path = $this->request->getFile('uploadedFile')->identity($direktori, $namabaru);
                //$file->move($direktori, $namabaru)
                if ($file->move($direktori, $user_picture)) {
                    $data['uploaduser_picture'] = "Upload Success !";
                    $input['user_picture'] = $user_picture;
                    $this->session->set("user_picture", $user_picture);
                } else {
                    $data['uploaduser_picture'] = "Upload Gagal !";
                }
            } else {
                // File Tipe Tidak Sesuai
                $data['uploaduser_picture'] = "Format File Salah !";
            }
        }



        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change') {
                    $input[$e] = $this->request->getPost($e);
                }
            }

            $this->db->table('user')
                ->where("user_id", $input["user_id"])
                ->update($input);
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }

        //cek user
        $userd["user_id"] = session()->get("user_id");
        $us = $this->db
            ->table("user")
            ->getWhere($userd);
        // echo $this->db->getLastquery();die;
        $larang = array("log_id", "id",  "action", "data", "user_id_dep", "trx_id", "trx_code", "contact_id_dep");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $user) {
                foreach ($this->db->getFieldNames('user') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $user->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('user') as $field) {
                $data[$field] = "";
            }
        }
        // dd($data);

        return $data;
    }
}
