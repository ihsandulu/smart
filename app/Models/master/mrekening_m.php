<?php

namespace App\Models\master;

use App\Models\core_m;

class mrekening_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek rekening
        if ($this->request->getVar("rekening_id")) {
            $rekeningd["rekening_id"] = $this->request->getVar("rekening_id");
        } else {
            $rekeningd["rekening_id"] = -1;
        }
        $us = $this->db
            ->table("rekening")
            ->getWhere($rekeningd);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "rekening_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $rekening) {
                foreach ($this->db->getFieldNames('rekening') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $rekening->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('rekening') as $field) {
                $data[$field] = "";
            }
        }



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $rekening_id =   $this->request->getPost("rekening_id");
            $this->db
                ->table("rekening")
                ->delete(array("rekening_id" =>  $rekening_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'rekening_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }

            $builder = $this->db->table('rekening');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $rekening_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'rekening_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('rekening')->update($input, array("rekening_id" => $this->request->getPost("rekening_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
