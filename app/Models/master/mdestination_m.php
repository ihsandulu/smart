<?php

namespace App\Models\master;

use App\Models\core_m;

class mdestination_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek destination
        if ($this->request->getVar("destination_id")) {
            $destinationd["destination_id"] = $this->request->getVar("destination_id");
        } else {
            $destinationd["destination_id"] = -1;
        }
        $us = $this->db
            ->table("destination")
            ->getWhere($destinationd);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "destination_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $destination) {
                foreach ($this->db->getFieldNames('destination') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $destination->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('destination') as $field) {
                $data[$field] = "";
            }
        }



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $destination_id =   $this->request->getPost("destination_id");
            $this->db
                ->table("destination")
                ->delete(array("destination_id" =>  $destination_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'destination_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }

            $builder = $this->db->table('destination');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $destination_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'destination_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('destination')->update($input, array("destination_id" => $this->request->getPost("destination_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
