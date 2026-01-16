<?php

namespace App\Models\transaction;

use App\Models\core_m;

class jobcst_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek job
        if ($this->request->getGet("job_id")) {
            $jobd["job_id"] = $this->request->getGet("job_id");
        } 
        $us = $this->db
            ->table("job")
            ->getWhere($jobd);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "job_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $job) {
                foreach ($this->db->getFieldNames('job') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $job->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('job') as $field) {
                $data[$field] = "";
            }
            $data["job_temp"] = date("YmdHis") . $this->session->get("user_id");
            $data["job_shipmentdate"] = date("Y-m-d");
            $data["job_sell"] = 0;
            $data["job_total"] = 0;
            $data["job_refund"] = 0;
            $data["job_profit"] = 0;
            $data["job_fee"] = 0;
            $data["job_gp"] = 0;
        }




        //total cost
        $us = $this->db
            ->table("cost")
            ->where("job_temp", $data["job_temp"])
            ->get();
        // echo $this->db->getLastQuery();die;
        $job_cost = 0;
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $cost) {
                $job_cost += $cost->cost_total;
            }
        } else {
            $job_cost = 0;
        }
        $data["job_cost"] = $job_cost;

        //total description of goods
        $goods = $this->db
            ->table("jobd")
            ->where("job_temp", $data["job_temp"])
            ->get();
        // echo $this->db->getLastQuery();die;
        $job_total = 0;
        if ($goods->getNumRows() > 0) {
            foreach ($goods->getResult() as $jobds) {
                $job_total += $jobds->jobd_total;
            }
        } else {
            $job_total = 0;
        }
        $data["job_total"] = $job_total;



        return $data;
    }
}
