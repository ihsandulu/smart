<?php

namespace App\Models\master;

use App\Models\core_m;

class mvessel_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek vessel
        if ($this->request->getVar("vessel_id")) {
            $vesseld["vessel_id"] = $this->request->getVar("vessel_id");
        } else {
            $vesseld["vessel_id"] = -1;
        }
        $us = $this->db
            ->table("vessel")
            ->getWhere($vesseld);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "vessel_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $vessel) {
                foreach ($this->db->getFieldNames('vessel') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $vessel->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('vessel') as $field) {
                $data[$field] = "";
            }
        }



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $vessel_id =   $this->request->getPost("vessel_id");
            $this->db
                ->table("vessel")
                ->delete(array("vessel_id" =>  $vessel_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'vessel_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }

            $builder = $this->db->table('vessel');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $vessel_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'vessel_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('vessel')->update($input, array("vessel_id" => $this->request->getPost("vessel_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
