<?php

namespace App\Models\transaction;

use App\Models\core_m;

class jobd_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        $data["job_temp"] = $this->request->getVar("temp");



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $jobd_id =   $this->request->getPost("jobd_id");

            //delete jobd
            $this->db
                ->table("jobd")
                ->delete(array("jobd_id" =>  $jobd_id));

            //edit job
            $jobtemp = $this->request->getGet("temp");
            //hitung total detail job
            $usr = $this->db->table("jobd")->select("SUM(jobd_total)AS total")->where("job_temp", $jobtemp)->get();
            $job_total = 0;
            foreach ($usr->getResult() as $row) {
                $job_total = $row->total;
                $inputjob["job_total"] = $job_total;
            }

            //hitung total costing
            $cost = $this->db->table("cost")->select("SUM(cost_total)AS total")->where("job_temp", $jobtemp)->get();
            $job_cost = 0;
            foreach ($cost->getResult() as $row) {
                $job_cost = $row->total;
                $inputjob["job_cost"] = $job_cost;
            }
            $job_refund = 0;
            $job = $this->db->table("job")->where("job_temp", $jobtemp)->get();
            foreach ($job->getResult() as $row) {
                $job_refund = $row->job_refund;
            }

            $job_profit = ($job_total - $job_cost - $job_refund);
            $inputjob["job_profit"] = $job_profit;

            $job_gp = 0;
            if ($job_total > 0) {
                $job_gp = ($job_profit / $job_total) * 100;
            }
            $inputjob["job_gp"] = $job_gp;


            $job_fee = $job_profit * 15 / 100;
            $inputjob["job_fee"] = $job_fee;


            $this->db->table("job")->where("job_temp", $jobtemp)->update($inputjob);
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'jobd_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            // dd($input);
            $this->db->table('jobd')->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $jobd_id = $this->db->insertID();

            //edit job
            $jobtemp = $this->request->getGet("temp");
            //hitung total detail job
            $usr = $this->db->table("jobd")->select("SUM(jobd_total)AS total")->where("job_temp", $jobtemp)->get();
            $job_total = 0;
            foreach ($usr->getResult() as $row) {
                $job_total = $row->total;
                $inputjob["job_total"] = $job_total;
            }

            //hitung total costing
            $cost = $this->db->table("cost")->select("SUM(cost_total)AS total")->where("job_temp", $jobtemp)->get();
            $job_cost = 0;
            foreach ($cost->getResult() as $row) {
                $job_cost = $row->total;
                $inputjob["job_cost"] = $job_cost;
            }
            $job_refund = 0;
            $job = $this->db->table("job")->where("job_temp", $jobtemp)->get();
            foreach ($job->getResult() as $row) {
                $job_refund = $row->job_refund;
            }

            $job_profit = ($job_total - $job_cost - $job_refund);
            $inputjob["job_profit"] = $job_profit;

            $job_gp = 0;
            if ($job_total > 0) {
                $job_gp = ($job_profit / $job_total) * 100;
            }
            $inputjob["job_gp"] = $job_gp;


            $job_fee = $job_profit * 15 / 100;
            $inputjob["job_fee"] = $job_fee;


            $this->db->table("job")->where("job_temp", $jobtemp)->update($inputjob);
            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'jobd_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('jobd')->update($input, array("jobd_id" => $this->request->getPost("jobd_id")));

            //edit job
            $jobtemp = $this->request->getGet("temp");
            //hitung total detail job
            $usr = $this->db->table("jobd")->select("SUM(jobd_total)AS total")->where("job_temp", $jobtemp)->get();
            $job_total = 0;
            foreach ($usr->getResult() as $row) {
                $job_total = $row->total;
                $inputjob["job_total"] = $job_total;
            }

            //hitung total costing
            $cost = $this->db->table("cost")->select("SUM(cost_total)AS total")->where("job_temp", $jobtemp)->get();
            $job_cost = 0;
            foreach ($cost->getResult() as $row) {
                $job_cost = $row->total;
                $inputjob["job_cost"] = $job_cost;
            }
            $job_refund = 0;
            $job = $this->db->table("job")->where("job_temp", $jobtemp)->get();
            foreach ($job->getResult() as $row) {
                $job_refund = $row->job_refund;
            }

            $job_profit = ($job_total - $job_cost - $job_refund);
            $inputjob["job_profit"] = $job_profit;

            $job_gp = 0;
            if ($job_total > 0) {
                $job_gp = ($job_profit / $job_total) * 100;
            }
            $inputjob["job_gp"] = $job_gp;


            $job_fee = $job_profit * 15 / 100;
            $inputjob["job_fee"] = $job_fee;


            $this->db->table("job")->where("job_temp", $jobtemp)->update($inputjob);

            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
