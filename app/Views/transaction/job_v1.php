<?php echo $this->include("template/header_v"); ?>

<div class='container-fluid'>
    <div class='row'>
        <div class='col-12'>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <?php if (!isset($_GET['user_id']) && !isset($_POST['new']) && !isset($_POST['edit'])) {
                            if ($ppn == 0) {
                                $coltitle = "col-md-10";
                            } else {
                                $coltitle = "col-md-8";
                            }
                        } else {
                            $coltitle = "col-md-8";
                        } ?>
                        <div class="<?= $coltitle; ?>">
                            <h4 class="card-title"></h4>
                            <!-- <h6 class="card-subtitle">Export data to Copy, CSV, Excel, PDF & Print</h6> -->
                        </div>

                        <?php if (!isset($_POST['new']) && !isset($_POST['edit']) && !isset($_GET['report'])) { ?>
                            <?php if (isset($_GET["user_id"])) { ?>
                                <form action="<?= base_url("user"); ?>" method="get" class="col-md-2">
                                    <h1 class="page-header col-md-12">
                                        <button class="btn btn-warning btn-block btn-lg" value="OK" style="">Back</button>
                                    </h1>
                                </form>
                            <?php } ?>
                            <?php
                            if (
                                (
                                    isset(session()->get("position_administrator")[0][0])
                                    && (
                                        session()->get("position_administrator") == "1"
                                        || session()->get("position_administrator") == "2"
                                    )
                                ) ||
                                (
                                    isset(session()->get("halaman")['49']['act_create'])
                                    && session()->get("halaman")['49']['act_create'] == "1"
                                )
                            ) { ?>
                                <?php if ($ppn != 0) { ?>
                                    <div class="col-4 text-right">
                                        <form method="post" class="form-inline" style="position: relative; top: -10px;">
                                            <div class="form-group">
                                                <label class="control-label" for="job_id">DA Number : &nbsp;</label>
                                                <select required name="job_id" value="<?= $job_id; ?>" class="form-control select">
                                                    <option value="">Select DA Number</option>
                                                    <?php
                                                    $usr = $this->db
                                                        ->table("job")
                                                        ->orderBy("job_dano", "ASC")
                                                        ->get();
                                                    foreach ($usr->getResult() as $usr) { ?>
                                                        <option value="<?= $usr->job_id; ?>" <?= ($job_id == $usr->job_id) ? "selected" : ""; ?>><?= $usr->job_dano; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>&nbsp;
                                            <button type="submit" id="submit" class="btn btn-primary" name="edit" value="OK">New</button>
                                        </form>
                                    </div>
                                <?php } else { ?>
                                    <form method="post" class="col-md-2">
                                        <h1 class="page-header col-md-12">
                                            <button name="new" class="btn btn-info btn-block btn-sm" value="OK" style="">New</button>
                                            <input type="hidden" name="job_id" />
                                        </h1>
                                    </form>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </div>

                    <?php if (isset($_POST['new']) || isset($_POST['edit'])) { ?>
                        <div class="">
                            <?php if (isset($_POST['edit'])) {
                                $namabutton = 'name="change"';
                                $judul = "Update Job";
                            } else {
                                $namabutton = 'name="create"';
                                $judul = "Tambah Job";
                            } ?>
                            <div class="lead">
                                <h3><?= $judul; ?></h3>
                            </div>
                            <form class="form-horizontal row" method="post" enctype="multipart/form-data">

                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="job_shipmentdate">SHIPMENT DATE:</label>
                                    <div class="col-sm-12">
                                        <input type="date" autofocus class="form-control" id="job_shipmentdate" name="job_shipmentdate" placeholder="" value="<?= $job_shipmentdate; ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="customer_id">SHIPPER NAME:</label>
                                    <div class="col-sm-12">
                                        <select onchange="isisingkatan()" class="form-control select" id="customer_id" name="customer_id">
                                            <option value="">--Select--</option>
                                            <?php
                                            $usr = $this->db
                                                ->table("customer")
                                                ->orderBy("customer_name", "ASC")
                                                ->get();
                                            foreach ($usr->getResult() as $usr) { ?>
                                                <option data-singkatan="<?= $usr->customer_singkatan; ?>" value="<?= $usr->customer_id; ?>" <?= ($customer_id == $usr->customer_id) ? "selected" : ""; ?>><?= $usr->customer_name; ?></option>
                                            <?php } ?>
                                        </select>
                                        <script>
                                            function isisingkatan() {
                                                let singkatan = $("#customer_id option:selected").data("singkatan");
                                                // alert(singkatan);
                                                $("#customer_singkatan").val(singkatan);
                                            }
                                        </script>
                                        <input type="hidden" id="customer_singkatan" name="customer_singkatan" value="<?= $customer_singkatan; ?>" />
                                    </div>
                                </div>

                                <?php if ($ppn != 2) { ?>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="origin_id">ORIGIN:</label>
                                        <div class="col-sm-12">
                                            <select class="form-control select" id="origin_id" name="origin_id">
                                                <option value="">--Select--</option>
                                                <?php
                                                $usr = $this->db
                                                    ->table("origin")
                                                    ->orderBy("origin_name", "ASC")
                                                    ->get();
                                                foreach ($usr->getResult() as $usr) { ?>
                                                    <option value="<?= $usr->origin_id; ?>" <?= ($origin_id == $usr->origin_id) ? "selected" : ""; ?>><?= $usr->origin_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="destination_id">DESTINATION:</label>
                                        <div class="col-sm-12">
                                            <select class="form-control select" id="destination_id" name="destination_id">
                                                <option value="">--Select--</option>
                                                <?php
                                                $usr = $this->db
                                                    ->table("destination")
                                                    ->orderBy("destination_name", "ASC")
                                                    ->get();
                                                foreach ($usr->getResult() as $usr) { ?>
                                                    <option value="<?= $usr->destination_id; ?>" <?= ($destination_id == $usr->destination_id) ? "selected" : ""; ?>><?= $usr->destination_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_descgood">DESCRIPTION OF GOODS:</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="job_descgood" name="job_descgood" placeholder="" value="<?= $job_descgood; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        &nbsp;
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_methode">Metode:</label>
                                        <div class="col-sm-12">
                                            <select onchange="metode()" class="form-control select" id="job_methode" name="job_methode">
                                                <option value="">--Select--</option>
                                                <option value="lumpsum" <?= ($job_methode == "lumpsum") ? "selected" : ""; ?>>Lumpsum</option>
                                                <option value="cbm" <?= ($job_methode == "cbm") ? "selected" : ""; ?>>CBM / KGS</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_qty">QTY:</label>
                                        <div class="col-sm-12">
                                            <input onchange="totalsell()" type="number" class="form-control" id="job_qty" name="job_qty" placeholder="" value="<?= $job_qty; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_cbm">CBM / KGS:</label>
                                        <div class="col-sm-12">
                                            <input onchange="totalsell()" type="text" class="form-control" id="job_cbm" name="job_cbm" placeholder="" value="<?= $job_cbm; ?>">
                                        </div>
                                    </div>


                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_satuan">SATUAN:</label>
                                        <div class="col-sm-12">
                                            <select class="form-control select" id="job_satuan" name="job_satuan">
                                                <option value="">--Select--</option>
                                                <?php
                                                $usr = $this->db
                                                    ->table("satuan")
                                                    ->orderBy("satuan_name", "ASC")
                                                    ->get();
                                                foreach ($usr->getResult() as $usr) { ?>
                                                    <option value="<?= $usr->satuan_name; ?>" <?= ($job_satuan == $usr->satuan_name) ? "selected" : ""; ?>><?= $usr->satuan_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="service_id">SERVICE:</label>
                                        <div class="col-sm-12">
                                            <select class="form-control select" id="service_id" name="service_id">
                                                <option value="">--Select--</option>
                                                <?php
                                                $usr = $this->db
                                                    ->table("service")
                                                    ->orderBy("service_name", "ASC")
                                                    ->get();
                                                foreach ($usr->getResult() as $usr) { ?>
                                                    <option value="<?= $usr->service_id; ?>" <?= ($service_id == $usr->service_id) ? "selected" : ""; ?>><?= $usr->service_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="vendortruck_id">TRUCKING:</label>
                                        <div class="col-sm-12">
                                            <select class="form-control select" id="vendortruck_id" name="vendortruck_id">
                                                <option value="">--Select--</option>
                                                <?php
                                                $usr = $this->db
                                                    ->table("vendortruck")
                                                    ->join("vendor", "vendor.vendor_id = vendortruck.vendor_id", "left")
                                                    ->orderBy("vendortruck_name", "ASC")
                                                    ->orderBy("vendor_name", "ASC")
                                                    ->get();
                                                foreach ($usr->getResult() as $usr) { ?>
                                                    <option value="<?= $usr->vendortruck_id; ?>" <?= ($vendortruck_id == $usr->vendortruck_id) ? "selected" : ""; ?>><?= $usr->vendortruck_name; ?> - <?= $usr->vendor_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="vessel_id">VESSEL:</label>
                                        <div class="col-sm-12">
                                            <select class="form-control select" id="vessel_id" name="vessel_id">
                                                <option value="">--Select--</option>
                                                <?php
                                                $usr = $this->db
                                                    ->table("vessel")
                                                    ->orderBy("vessel_name", "ASC")
                                                    ->get();
                                                foreach ($usr->getResult() as $usr) { ?>
                                                    <option value="<?= $usr->vessel_id; ?>" <?= ($vessel_id == $usr->vessel_id) ? "selected" : ""; ?>><?= $usr->vessel_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php if ($ppn == 0) { ?>
                                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                            <label class="control-label col-sm-12" for="vendor_id">VENDOR / PELAYARAN:</label>
                                            <div class="col-sm-12">
                                                <select class="form-control select" id="vendor_id" name="vendor_id">
                                                    <option value="">--Select--</option>
                                                    <?php
                                                    $usr = $this->db
                                                        ->table("vendor")
                                                        ->orderBy("vendor_name", "ASC")
                                                        ->get();
                                                    foreach ($usr->getResult() as $usr) { ?>
                                                        <option value="<?= $usr->vendor_id; ?>" <?= ($vendor_id == $usr->vendor_id) ? "selected" : ""; ?>><?= $usr->vendor_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                            <label class="control-label col-sm-12" for="job_dooring">DOORING:</label>
                                            <div class="col-sm-12">
                                                <select class="form-control select" id="job_dooring" name="job_dooring">
                                                    <option value="">--Select--</option>
                                                    <?php
                                                    $usr = $this->db
                                                        ->table("vendor")
                                                        ->orderBy("vendor_name", "ASC")
                                                        ->get();
                                                    foreach ($usr->getResult() as $usr) { ?>
                                                        <option value="<?= $usr->vendor_id; ?>" <?= ($job_dooring == $usr->vendor_id) ? "selected" : ""; ?>><?= $usr->vendor_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                    <?php } ?>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_sell">SELL RPRICE:</label>
                                        <div class="col-sm-12">
                                            <input onchange="totalsell()" type="number" class="form-control" id="job_sell" name="job_sell" placeholder="" value="<?= $job_sell; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_total">TOTAL PRICE:</label>
                                        <div class="col-sm-12">
                                            <input onchange="profit(); totalinv();" type="number" onchange="tprice()" class="form-control" id="job_total" name="job_total" placeholder="" value="<?= $job_total; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_cost">COST:</label>
                                        <div class="col-sm-12">
                                            <input onchange="profit()" type="number" class="form-control" id="job_cost" name="job_cost" placeholder="" value="<?= $job_cost; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_refund">REFUND:</label>
                                        <div class="col-sm-12">
                                            <input onchange="profit()" type="number" class="form-control" id="job_refund" name="job_refund" placeholder="" value="<?= $job_refund; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_fee"> MARKET FEE 15%:</label>
                                        <div class="col-sm-12">
                                            <input type="number" class="form-control" id="job_fee" name="job_fee" placeholder="" value="<?= $job_fee; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_profit">PROFIT:</label>
                                        <div class="col-sm-12">
                                            <input onchange="fee()" type="number" class="form-control" id="job_profit" name="job_profit" placeholder="" value="<?= $job_profit; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_gp">GP %:</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="job_gp" name="job_gp" placeholder="" value="<?= $job_gp; ?>">
                                        </div>
                                    </div>
                                    <?php if ($ppn == 0) { ?>
                                        <div class="form-group col-md-4 col-sm-6 col-xs-12">&nbsp;
                                        </div>
                                    <?php } ?>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_ppn1k1">PPN 1.1%:</label>
                                        <div class="col-sm-12">
                                            <input onchange="totalinv()" type="number" class="form-control" id="job_ppn1k1" name="job_ppn1k1" placeholder="" value="<?= $job_ppn1k1; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_ppn11">PPN 11%:</label>
                                        <div class="col-sm-12">
                                            <input type="number" class="form-control" id="job_ppn11" name="job_ppn11" placeholder="" value="<?= $job_ppn11; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_ppn12">PPN 12%:</label>
                                        <div class="col-sm-12">
                                            <input type="number" class="form-control" id="job_ppn12" name="job_ppn12" placeholder="" value="<?= $job_ppn12; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_pph">PPH 2%:</label>
                                        <div class="col-sm-12">
                                            <input onchange="dp()" type="number" class="form-control" id="job_pph" name="job_pph" placeholder="" value="<?= $job_pph; ?>">
                                        </div>
                                    </div>
                                    <?php if ($ppn == 1) { ?>
                                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                            <label class="control-label col-sm-12" for="vendor_id">Payment Methode:</label>
                                            <div class="col-sm-12">
                                                <input type="number" min="1" class="form-control" id="job_paynom" name="job_paynom" placeholder="" value="<?= ($job_paynom > 0) ? $job_paynom : 1; ?>">
                                                <select class="form-control select" id="job_payunit" name="job_payunit">
                                                    <option value="WEEK" <?= ($job_payunit == "WEEK") ? "selected" : ""; ?>>WEEK</option>
                                                    <option value="MONTH" <?= ($job_payunit == "MONTH") ? "selected" : ""; ?>>MONTH</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                            <label class="control-label col-sm-12" for="job_taxno">Tax Number:</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="job_taxno" name="job_taxno" placeholder="" value="<?= $job_taxno; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                            <label class="control-label col-sm-12" for="job_invdate">Invoice Date:</label>
                                            <div class="col-sm-12">
                                                <input type="date" class="form-control" id="job_invdate" name="job_invdate" placeholder="" value="<?= $job_invdate; ?>">
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <!-- <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="job_invoice">Invoice No:</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="job_invoice" name="job_invoice" placeholder="" value="<?= $job_invoice; ?>">
                                    </div>
                                </div> -->
                                <?php if ($ppn == 1) { ?>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_bupot">Bupot No:</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="job_bupot" name="job_bupot" placeholder="" value="<?= $job_bupot; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_npwp">NPWP:</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="job_npwp" name="job_npwp" placeholder="" value="<?= $job_npwp; ?>">
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if ($ppn != 0) { ?>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_duedate">Due Date:</label>
                                        <div class="col-sm-12">
                                            <input type="date" class="form-control" id="job_duedate" name="job_duedate" placeholder="" value="<?= $job_duedate; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_totalinv">Total INV:</label>
                                        <div class="col-sm-12">
                                            <input onchange="dp()" type="number" class="form-control" id="job_totalinv" name="job_totalinv" placeholder="" value="<?= $job_totalinv; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_dp">DP:</label>
                                        <div class="col-sm-12">
                                            <input onchange="dp()" type="text" class="form-control" id="job_dp" name="job_dp" placeholder="" value="<?= $job_dp; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_repayment">Repayment:</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="job_repayment" name="job_repayment" placeholder="" value="<?= $job_repayment; ?>">
                                        </div>
                                    </div>
                                    <script>
                                        function totalinv() {
                                            let job_ppn1k1 = $("#job_ppn1k1").val();
                                            let job_total = $("#job_total").val();
                                            let job_totalinv = parseInt(job_total) * parseInt(job_ppn1k1) / 100;
                                            $("#job_totalinv").val(job_totalinv);
                                            dp();
                                        }

                                        function dp() {
                                            let job_dp = $("#job_dp").val();
                                            let job_totalinv = $("#job_totalinv").val();
                                            let job_pph = $("#job_pph").val();
                                            let total = parseInt(job_totalinv) - parseInt(job_pph) - parseInt(job_dp);
                                            $("#job_repayment").val(total);
                                            $("#job_admission").val(total);
                                        }
                                    </script>
                                <?php } ?>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="job_admission">ADMISSION:</label>
                                    <div class="col-sm-12">
                                        <input type="number" class="form-control" id="job_admission" name="job_admission" placeholder="" value="<?= $job_admission; ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="job_explanation">EXPLANATION:</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="job_explanation" name="job_explanation" placeholder="" value="<?= $job_explanation; ?>">
                                    </div>
                                </div>

                                <script>
                                    $(document).ready(function() {
                                        metode(); // jalankan saat halaman dimuat


                                    });

                                    function metode() {
                                        let metode = $("#job_methode").val();
                                        if (metode == "cbm") {
                                            $("#job_qty").attr("readonly", true);
                                            $("#job_cbm").attr("readonly", false);
                                            setTimeout(function() {
                                                $("#job_qty").val("0");
                                                $("#job_cbm").focus();
                                            }, 1000);
                                            totalsell();
                                        } else if (metode == "lumpsum") {
                                            $("#job_qty").attr("readonly", false);
                                            $("#job_cbm").attr("readonly", true);
                                            setTimeout(function() {
                                                $("#job_cbm").val("0");
                                                $("#job_qty").focus();
                                            }, 1000);
                                            totalsell();
                                        } else {
                                            $("#job_qty").attr("readonly", true);
                                            $("#job_cbm").attr("readonly", true);
                                        }
                                    }

                                    function totalsell() {
                                        let cbm = $("#job_cbm").val();
                                        let qty = $("#job_qty").val();
                                        let sell = $("#job_sell").val();
                                        let total = 0;
                                        let a = $("#job_methode").val();
                                        if (a == "cbm") {
                                            total = cbm * sell;
                                        } else {
                                            total = qty * sell;
                                        }
                                        $("#job_total").val(total);
                                        tprice();
                                        totalinv();
                                    }

                                    function tprice() {
                                        let job_total = $("#job_total").val();
                                        let ppn1k1 = job_total * 1.1 / 100;
                                        let ppn11 = job_total * 11 / 100;
                                        let ppn12 = job_total * 12 / 100;
                                        let pph = job_total * 2 / 100;
                                        $("#job_ppn1k1").val(ppn1k1);
                                        $("#job_ppn11").val(ppn11);
                                        $("#job_ppn12").val(ppn12);
                                        $("#job_pph").val(pph);
                                        profit();
                                        totalinv();
                                        dp();
                                    }

                                    function profit() {
                                        let job_total = $("#job_total").val();
                                        let job_cost = $("#job_cost").val();
                                        let job_refund = $("#job_refund").val();
                                        let profit = (parseInt(job_total) - parseInt(job_cost) - parseInt(job_refund));
                                        $("#job_profit").val(profit);
                                        let gp = 0;
                                        if (job_total > 0) {
                                            gp = (profit / job_total) * 100;
                                        }
                                        $("#job_gp").val(gp);
                                        fee();
                                    }

                                    function fee() {
                                        let job_profit = $("#job_profit").val();
                                        let fee = parseInt(job_profit) * 15 / 100;
                                        $("#job_fee").val(fee);
                                    }
                                </script>

                                <input type="hidden" name="job_ppntype" value="<?= $ppn; ?>" />
                                <input type="hidden" name="job_id" value="<?= $job_id; ?>" />
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <div class="col-sm-offset-2 col-sm-12">
                                        <button type="submit" id="submit" class="btn btn-primary col-md-5" <?= $namabutton; ?> value="OK">Submit</button>
                                        <a class="btn btn-warning col-md-offset-1 col-md-5" href="javascript:history.back()">Back</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php } else { ?>
                        <?php if ($message != "") { ?>
                            <div class="alert alert-info alert-dismissable">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong><?= $message; ?></strong>
                            </div>
                        <?php } ?>


                        <form method="get">
                            <div class="row alert alert-dark">
                                <?php
                                $dari = date("Y-m-d");
                                $ke = date("Y-m-d");
                                $idepartemen = 0;
                                if (isset($_GET["dari"])) {
                                    $dari = $_GET["dari"];
                                }
                                if (isset($_GET["ke"])) {
                                    $ke = $_GET["ke"];
                                }
                                ?>
                                <div class="col-3 ">
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="text-dark">Dari :</label>
                                        </div>
                                        <div class="col-8">
                                            <input type="date" class="form-control" placeholder="Dari" name="dari" value="<?= $dari; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3 row ">
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="text-dark">Ke :</label>
                                        </div>
                                        <div class="col-8">
                                            <input type="date" class="form-control" placeholder="Ke" name="ke" value="<?= $ke; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <button type="submit" class="btn btn-block btn-primary">Search</button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive m-t-40">
                            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <!-- <table id="dataTable" class="table table-condensed table-hover w-auto dtable"> -->
                                <thead class="">
                                    <tr>
                                        <?php if (!isset($_GET["report"])) { ?>
                                            <th>Action</th>
                                        <?php } ?>
                                        <!-- <th>No.</th> -->
                                        <?php if ($ppn == 0) { ?>
                                            <th>Methode</th>
                                        <?php } ?>

                                        <th>Shipment Date</th>
                                        <th>DA Number</th>
                                        <th>Shipper Name</th>
                                        <?php if ($ppn != 2) { ?>
                                            <th>Origin</th>
                                            <th>Destination</th>
                                            <th>Description of Goods</th>
                                            <th>Qty</th>
                                            <th>Satuan</th>
                                            <th>CBM/MT</th>
                                            <th>Service</th>
                                            <th>Trucking</th>
                                            <th>Vessel</th>
                                            <?php if ($ppn == 0) { ?>
                                                <th>Vendor/Pelayaran</th>
                                                <th>Dooring</th>
                                            <?php } ?>
                                            <th>Sell Price</th>
                                            <th>Total Price</th>
                                            <th>Cost</th>
                                            <th>Refund</th>
                                            <th>Market Fee 15%</th>
                                            <th>Profit</th>
                                            <th>GP%</th>
                                            <th>PPN 1,1%</th>
                                            <th>PPN 11%</th>
                                            <th>PPN 12%</th>
                                            <th>PPH 2%</th>
                                            <?php if ($ppn == 1) { ?>
                                                <th>Payment Methode</th>
                                                <th>Tax No.</th>
                                                <th>Inv Date</th>
                                            <?php } ?>
                                        <?php } ?>

                                        <th>Invoice No.</th>
                                        <?php if ($ppn == 1) { ?>
                                            <th>Bupot No.</th>
                                            <th>NPWP</th>
                                        <?php } ?>
                                        <?php if ($ppn != 0) { ?>
                                            <th>Due Date</th>
                                            <th>Total INV</th>
                                            <th>DP</th>
                                            <th>Repayment</th>
                                        <?php } ?>
                                        <th>Admission</th>
                                        <th>Explanation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $build = $this->db
                                        ->table("job")
                                        ->join("customer", "customer.customer_id = job.customer_id", "left")
                                        ->join("origin", "origin.origin_id = job.origin_id", "left")
                                        ->join("destination", "destination.destination_id = job.destination_id", "left")
                                        ->join("vendor", "vendor.vendor_id = job.vendor_id", "left")
                                        ->join("vendortruck", "vendortruck.vendortruck_id = job.vendortruck_id", "left")
                                        ->join("(SELECT vendor_id as vendor_id2, vendor_name AS vendor_name2 FROM vendor) AS v2", "v2.vendor_id2 = vendortruck.vendor_id", "left")
                                        ->join("service", "service.service_id = job.service_id", "left")
                                        ->join("vessel", "vessel.vessel_id = job.vessel_id", "left");
                                    if ($ppn != 0) {
                                        $build->where("job_ppntype", $ppn);
                                    }
                                    $build->where("job_shipmentdate BETWEEN '" . $dari . "' AND '" . $ke . "'");
                                    $usr = $build
                                        ->orderBy("job_id", "ASC")
                                        ->get();
                                    //echo $this->db->getLastquery();
                                    $no = 1;
                                    foreach ($usr->getResult() as $usr) { ?>
                                        <tr>
                                            <?php if (!isset($_GET["report"])) { ?>
                                                <td style="padding-left:0px; padding-right:0px;">
                                                    <?php
                                                    if (
                                                        (
                                                            isset(session()->get("position_administrator")[0][0])
                                                            && (
                                                                session()->get("position_administrator") == "1"
                                                                || session()->get("position_administrator") == "2"
                                                            )
                                                        ) ||
                                                        (
                                                            isset(session()->get("halaman")['49']['act_update'])
                                                            && session()->get("halaman")['49']['act_update'] == "1"
                                                        )
                                                    ) { ?>
                                                        <form method="post" class="btn-action" style="">
                                                            <button class="btn btn-sm btn-warning " name="edit" value="OK"><span class="fa fa-edit" style="color:white;"></span> </button>
                                                            <input type="hidden" name="job_id" value="<?= $usr->job_id; ?>" />
                                                        </form>
                                                    <?php } ?>

                                                    <?php
                                                    if (
                                                        (
                                                            isset(session()->get("position_administrator")[0][0])
                                                            && (
                                                                session()->get("position_administrator") == "1"
                                                                || session()->get("position_administrator") == "2"
                                                            )
                                                        ) ||
                                                        (
                                                            isset(session()->get("halaman")['49']['act_delete'])
                                                            && session()->get("halaman")['49']['act_delete'] == "1"
                                                        )
                                                    ) { ?>
                                                        <form method="post" class="btn-action" style="">
                                                            <button class="btn btn-sm btn-danger delete" onclick="return confirm(' you want to delete?');" name="delete" value="OK"><span class="fa fa-close" style="color:white;"></span> </button>
                                                            <input type="hidden" name="job_id" value="<?= $usr->job_id; ?>" />
                                                        </form>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>
                                            <!-- <td><?= $no++; ?></td> -->
                                            <?php if ($ppn == 0) { ?>
                                                <td><?= $usr->job_methode; ?></td>
                                            <?php } ?>
                                            <td style="white-space:nowrap;"><?= $usr->job_shipmentdate; ?></td>
                                            <td><?= $usr->job_dano; ?></td>
                                            <td style="white-space:nowrap;"><?= $usr->customer_name; ?></td>

                                            <?php if ($ppn != 2) { ?>
                                                <td><?= $usr->origin_name; ?></td>
                                                <td><?= $usr->destination_name; ?></td>
                                                <td style="white-space:nowrap;"><?= $usr->job_descgood; ?></td>
                                                <td><?= number_format($usr->job_qty, 0, ",", "."); ?></td>
                                                <td><?= $usr->job_satuan; ?></td>
                                                <td><?= number_format($usr->job_cbm, 3, ",", "."); ?></td>
                                                <td style="white-space:nowrap;"><?= $usr->service_name; ?></td>
                                                <td style="white-space:nowrap;"><?= $usr->vendortruck_name; ?> - <?= $usr->vendor_name2; ?></td>
                                                <td><?= $usr->vessel_id; ?></td>
                                                <?php if ($ppn == 0) { ?>
                                                    <td><?= $usr->vendor_name; ?></td>
                                                    <td><?= $usr->job_dooring; ?></td>
                                                <?php } ?>
                                                <td><?= number_format($usr->job_sell, 0, ",", "."); ?></td>
                                                <td><?= number_format($usr->job_total, 0, ",", "."); ?></td>
                                                <td><?= number_format($usr->job_cost, 0, ",", "."); ?></td>
                                                <td><?= number_format($usr->job_refund, 0, ",", "."); ?></td>
                                                <td><?= number_format($usr->job_fee, 0, ",", "."); ?></td>
                                                <td><?= number_format($usr->job_profit, 0, ",", "."); ?></td>
                                                <td><?= number_format($usr->job_gp, 0, ",", "."); ?></td>
                                                <td><?= number_format($usr->job_ppn1k1, 0, ",", "."); ?></td>
                                                <td><?= number_format($usr->job_ppn11, 0, ",", "."); ?></td>
                                                <td><?= number_format($usr->job_ppn12, 0, ",", "."); ?></td>
                                                <td><?= number_format($usr->job_pph, 0, ",", "."); ?></td>
                                                <?php if ($ppn == 1) { ?>
                                                    <td><?= $usr->job_paynom; ?> <?= $usr->job_payunit; ?></td>
                                                    <td><?= $usr->job_taxno; ?></td>
                                                    <td style="white-space:nowrap;"><?= $usr->job_invdate; ?></td>
                                                <?php } ?>
                                            <?php } ?>
                                            <td style="white-space:nowrap;"><?= $usr->inv_no; ?></td>
                                            <?php if ($ppn == 1) { ?>
                                                <td style="white-space:nowrap;"><?= $usr->job_bupot; ?></td>
                                                <td style="white-space:nowrap;"><?= $usr->job_npwp; ?></td>
                                            <?php } ?>
                                            <?php if ($ppn != 0) { ?>
                                                <td style="white-space:nowrap;"><?= $usr->job_duedate; ?></td>
                                                <td><?= number_format($usr->job_totalinv, 0, ",", "."); ?></td>
                                                <td><?= number_format($usr->job_dp, 0, ",", "."); ?></td>
                                                <td><?= number_format($usr->job_repayment, 0, ",", "."); ?></td>
                                            <?php } ?>
                                            <td style="white-space:nowrap;"><?= $usr->job_admission; ?></td>
                                            <td style="white-space:nowrap;"><?= $usr->job_explanation; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.select').select2();
    var title = "<?= $title; ?>";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>

<?php echo  $this->include("template/footer_v"); ?>