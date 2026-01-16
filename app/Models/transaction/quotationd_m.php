<?php

namespace App\Models\transaction;

use App\Models\core_m;

class quotationd_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek quotationd
        if ($this->request->getVar("quotationd_id")) {
            $quotationdd["quotationd_id"] = $this->request->getVar("quotationd_id");
        } else {
            $quotationdd["quotationd_id"] = -1;
        }
        $us = $this->db
            ->table("quotationd")
            ->getWhere($quotationdd);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "quotationd_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $quotationd) {
                foreach ($this->db->getFieldNames('quotationd') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $quotationd->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('quotationd') as $field) {
                $data[$field] = "";
            }
        }



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $quotationd_id =   $this->request->getPost("quotationd_id");
            $this->db
                ->table("quotationd")
                ->delete(array("quotationd_id" =>  $quotationd_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'quotationd_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            // dd($input);
            $builder = $this->db->table('quotationd');
            $builder->insert($input);
            // echo $this->db->getLastQuery(); die;
            $quotationd_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'quotationd_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $quotationd_id = $this->request->getPost("quotationd_id");
            $this->db->table('quotationd')->update($input, array("quotationd_id" => $quotationd_id));

            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
