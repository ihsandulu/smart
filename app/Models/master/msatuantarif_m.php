<?php

namespace App\Models\master;

use App\Models\core_m;

class msatuantarif_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek satuantarif
        if ($this->request->getVar("satuantarif_id")) {
            $satuantarifd["satuantarif_id"] = $this->request->getVar("satuantarif_id");
        } else {
            $satuantarifd["satuantarif_id"] = -1;
        }
        $us = $this->db
            ->table("satuantarif")
            ->getWhere($satuantarifd);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "satuantarif_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $satuantarif) {
                foreach ($this->db->getFieldNames('satuantarif') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $satuantarif->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('satuantarif') as $field) {
                $data[$field] = "";
            }
        }



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $satuantarif_id =   $this->request->getPost("satuantarif_id");
            $this->db
                ->table("satuantarif")
                ->delete(array("satuantarif_id" =>  $satuantarif_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'satuantarif_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }

            $builder = $this->db->table('satuantarif');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $satuantarif_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'satuantarif_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('satuantarif')->update($input, array("satuantarif_id" => $this->request->getPost("satuantarif_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
