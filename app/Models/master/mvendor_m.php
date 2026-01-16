<?php

namespace App\Models\master;

use App\Models\core_m;

class mvendor_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek vendor
        if ($this->request->getVar("vendor_id")) {
            $vendord["vendor_id"] = $this->request->getVar("vendor_id");
        } else {
            $vendord["vendor_id"] = -1;
        }
        $us = $this->db
            ->table("vendor")
            ->getWhere($vendord);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "vendor_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $vendor) {
                foreach ($this->db->getFieldNames('vendor') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $vendor->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('vendor') as $field) {
                $data[$field] = "";
            }
        }



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $vendor_id =   $this->request->getPost("vendor_id");
            $this->db
                ->table("vendor")
                ->delete(array("vendor_id" =>  $vendor_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'vendor_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }

            $builder = $this->db->table('vendor');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $vendor_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'vendor_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('vendor')->update($input, array("vendor_id" => $this->request->getPost("vendor_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
