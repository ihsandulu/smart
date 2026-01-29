<?php

namespace App\Models\master;

use App\Models\core_m;

class mproduct_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek product
        if ($this->request->getPost("product_id")) {
            $productd["product_id"] = $this->request->getPost("product_id");
        } else {
            $productd["product_id"] = -1;
        }
        $us = $this->db
            ->table("product")
            ->getWhere($productd);
        // echo $this->db->getLastquery();
        // die;
        $larang = array("log_id", "id",  "action", "data", "id_dep", "trx_id", "trx_code", "contact_id_dep");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $product) {
                foreach ($this->db->getFieldNames('product') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $product->$field;
                    }
                }                
            }
        } else {
            foreach ($this->db->getFieldNames('product') as $field) {
                $data[$field] = "";
            }
        }



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $product_id = $this->request->getPost("product_id");

            $this->db
                ->table("product")
                ->delete(array("product_id" =>  $product_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create') {
                    $inputu[$e] = $this->request->getPost($e);
                }
            }
           
            $this->db->table('product')->insert($inputu);
            /* echo $this->db->getLastQuery();
            die; */
            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;
        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change') {
                    $inputu[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('product')
                ->where("product_id", $inputu["product_id"])
                ->update($inputu);
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
