<?php

namespace App\Models\transaction;

use App\Models\core_m;

class antam_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek userantam
        if ($this->request->getPost("userantam_id")) {
            $userantamd["userantam_id"] = $this->request->getPost("userantam_id");
        } else {
            $userantamd["userantam_id"] = -1;
        }
        $us = $this->db
            ->table("userantam")
            ->getWhere($userantamd);
        // echo $this->db->getLastquery();
        // die;
        $larang = array("log_id", "id",  "action", "data", "id_dep", "trx_id", "trx_code", "contact_id_dep");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $userantam) {
                foreach ($this->db->getFieldNames('userantam') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $userantam->$field;
                    }
                }                
            }
        } else {
            foreach ($this->db->getFieldNames('userantam') as $field) {
                $data[$field] = "";
            }
        }

// dd($_POST);
// die;
        //delete
        if ($this->request->getPost("delete") == "OK") {
            $userantam_id = $this->request->getPost("userantam_id");

            $this->db
                ->table("userantam")
                ->delete(array("userantam_id" =>  $userantam_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create') {
                    $inputu[$e] = $this->request->getPost($e);
                }
            }
           
            $this->db->table('userantam')->insert($inputu);
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
            $this->db->table('userantam')
                ->where("userantam_id", $inputu["userantam_id"])
                ->update($inputu);
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
