<?php

namespace App\Models\master;

use App\Models\core_m;

class msatuan_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek satuan
        if ($this->request->getVar("satuan_id")) {
            $satuand["satuan_id"] = $this->request->getVar("satuan_id");
        } else {
            $satuand["satuan_id"] = -1;
        }
        $us = $this->db
            ->table("satuan")
            ->getWhere($satuand);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "satuan_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $satuan) {
                foreach ($this->db->getFieldNames('satuan') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $satuan->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('satuan') as $field) {
                $data[$field] = "";
            }
        }



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $satuan_id =   $this->request->getPost("satuan_id");
            $this->db
                ->table("satuan")
                ->delete(array("satuan_id" =>  $satuan_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'satuan_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }

            $builder = $this->db->table('satuan');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $satuan_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'satuan_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('satuan')->update($input, array("satuan_id" => $this->request->getPost("satuan_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
