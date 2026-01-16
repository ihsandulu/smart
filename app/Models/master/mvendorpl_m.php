<?php

namespace App\Models\master;

use App\Models\core_m;

class mvendorpl_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek vendorpl
        if ($this->request->getVar("vendorpl_id")) {
            $vendorpld["vendorpl_id"] = $this->request->getVar("vendorpl_id");
        } else {
            $vendorpld["vendorpl_id"] = -1;
        }
        $us = $this->db
            ->table("vendorpl")
            ->getWhere($vendorpld);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "vendorpl_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $vendorpl) {
                foreach ($this->db->getFieldNames('vendorpl') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $vendorpl->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('vendorpl') as $field) {
                $data[$field] = "";
            }
        }



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $vendorpl_id =   $this->request->getPost("vendorpl_id");
            $this->db
                ->table("vendorpl")
                ->delete(array("vendorpl_id" =>  $vendorpl_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'vendorpl_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }

            $builder = $this->db->table('vendorpl');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $vendorpl_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'vendorpl_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('vendorpl')->update($input, array("vendorpl_id" => $this->request->getPost("vendorpl_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
