<?php

namespace App\Models\master;

use App\Models\core_m;

class mtarif_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek tarif
        if ($this->request->getVar("tarif_id")) {
            $tarifd["tarif_id"] = $this->request->getVar("tarif_id");
        } else {
            $tarifd["tarif_id"] = -1;
        }
        $us = $this->db
            ->table("tarif")
            ->getWhere($tarifd);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "tarif_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $tarif) {
                foreach ($this->db->getFieldNames('tarif') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $tarif->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('tarif') as $field) {
                $data[$field] = "";
            }
        }



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $tarif_id =   $this->request->getPost("tarif_id");
            $this->db
                ->table("tarif")
                ->delete(array("tarif_id" =>  $tarif_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'tarif_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }

            $builder = $this->db->table('tarif');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $tarif_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'tarif_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('tarif')->update($input, array("tarif_id" => $this->request->getPost("tarif_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
