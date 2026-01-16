<?php

namespace App\Models\master;

use App\Models\core_m;

class morigin_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek origin
        if ($this->request->getVar("origin_id")) {
            $origind["origin_id"] = $this->request->getVar("origin_id");
        } else {
            $origind["origin_id"] = -1;
        }
        $us = $this->db
            ->table("origin")
            ->getWhere($origind);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "origin_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $origin) {
                foreach ($this->db->getFieldNames('origin') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $origin->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('origin') as $field) {
                $data[$field] = "";
            }
        }



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $origin_id =   $this->request->getPost("origin_id");
            $this->db
                ->table("origin")
                ->delete(array("origin_id" =>  $origin_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'origin_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }

            $builder = $this->db->table('origin');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $origin_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'origin_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('origin')->update($input, array("origin_id" => $this->request->getPost("origin_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
