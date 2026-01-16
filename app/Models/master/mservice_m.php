<?php

namespace App\Models\master;

use App\Models\core_m;

class mservice_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek service
        if ($this->request->getVar("service_id")) {
            $serviced["service_id"] = $this->request->getVar("service_id");
        } else {
            $serviced["service_id"] = -1;
        }
        $us = $this->db
            ->table("service")
            ->getWhere($serviced);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "service_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $service) {
                foreach ($this->db->getFieldNames('service') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $service->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('service') as $field) {
                $data[$field] = "";
            }
        }



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $service_id =   $this->request->getPost("service_id");
            $this->db
                ->table("service")
                ->delete(array("service_id" =>  $service_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'service_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }

            $builder = $this->db->table('service');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $service_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'service_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('service')->update($input, array("service_id" => $this->request->getPost("service_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
