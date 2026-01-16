<?php

namespace App\Models\master;

use App\Models\core_m;

class mvendortruck_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek vendortruck
        if ($this->request->getVar("vendortruck_id")) {
            $vendortruckd["vendortruck_id"] = $this->request->getVar("vendortruck_id");
        } else {
            $vendortruckd["vendortruck_id"] = -1;
        }
        $us = $this->db
            ->table("vendortruck")
            ->getWhere($vendortruckd);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "vendortruck_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $vendortruck) {
                foreach ($this->db->getFieldNames('vendortruck') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $vendortruck->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('vendortruck') as $field) {
                $data[$field] = "";
            }
        }



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $vendortruck_id =   $this->request->getPost("vendortruck_id");
            $this->db
                ->table("vendortruck")
                ->delete(array("vendortruck_id" =>  $vendortruck_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'vendortruck_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }

            $builder = $this->db->table('vendortruck');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $vendortruck_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'vendortruck_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('vendortruck')->update($input, array("vendortruck_id" => $this->request->getPost("vendortruck_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
