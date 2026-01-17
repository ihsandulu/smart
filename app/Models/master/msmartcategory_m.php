<?php

namespace App\Models\master;

use App\Models\core_m;

class msmartcategory_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek smartcategory
        if ($this->request->getVar("smartcategory_id")) {
            $smartcategoryd["smartcategory_id"] = $this->request->getVar("smartcategory_id");
        } else {
            $smartcategoryd["smartcategory_id"] = -1;
        }
        $us = $this->db
            ->table("smartcategory")
            ->getWhere($smartcategoryd);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "smartcategory_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $smartcategory) {
                foreach ($this->db->getFieldNames('smartcategory') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $smartcategory->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('smartcategory') as $field) {
                $data[$field] = "";
            }
        }



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $smartcategory_id =   $this->request->getPost("smartcategory_id");
            $this->db
                ->table("smartcategory")
                ->delete(array("smartcategory_id" =>  $smartcategory_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'smartcategory_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }

            $builder = $this->db->table('smartcategory');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $smartcategory_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'smartcategory_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('smartcategory')->update($input, array("smartcategory_id" => $this->request->getPost("smartcategory_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
