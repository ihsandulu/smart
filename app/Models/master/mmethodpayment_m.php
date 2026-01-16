<?php

namespace App\Models\master;

use App\Models\core_m;

class mmethodpayment_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek methodpayment
        if ($this->request->getVar("methodpayment_id")) {
            $methodpaymentd["methodpayment_id"] = $this->request->getVar("methodpayment_id");
        } else {
            $methodpaymentd["methodpayment_id"] = -1;
        }
        $us = $this->db
            ->table("methodpayment")
            ->getWhere($methodpaymentd);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "methodpayment_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $methodpayment) {
                foreach ($this->db->getFieldNames('methodpayment') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $methodpayment->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('methodpayment') as $field) {
                $data[$field] = "";
            }
        }



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $methodpayment_id =   $this->request->getPost("methodpayment_id");
            $this->db
                ->table("methodpayment")
                ->delete(array("methodpayment_id" =>  $methodpayment_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'methodpayment_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }

            $builder = $this->db->table('methodpayment');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $methodpayment_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'methodpayment_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('methodpayment')->update($input, array("methodpayment_id" => $this->request->getPost("methodpayment_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
