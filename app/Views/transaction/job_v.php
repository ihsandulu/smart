<?php
echo $this->include("template/header_v");
if (isset($_GET["tbl"])) {
    $tbl = $_GET["tbl"];
} else {
    $tbl = "";
}
?>
<style>
    #example23 th:nth-child(1),
    #example23 td:nth-child(1) {
        width: 500px !important;
        max-width: 500px !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .select2-container {
        width: 100% !important;
    }

    .select2-selection {
        height: 38px !important;
        /* biar sama tinggi dengan form-control Bootstrap */
        line-height: 38px !important;
    }

    #e23 th,
    #e23 td {
        width: auto !important;
    }
</style>

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

                        <?php if (!isset($_POST['new']) && !isset($_POST['edit']) && !isset($_GET['report']) && !isset($_GET['t'])) { ?>
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
                                    isset(session()->get("halaman")['102']['act_create'])
                                    && session()->get("halaman")['102']['act_create'] == "1"
                                ) ||
                                (
                                    isset(session()->get("halaman")['115']['act_create'])
                                    && session()->get("halaman")['115']['act_create'] == "1"
                                ) ||
                                (
                                    isset(session()->get("halaman")['116']['act_create'])
                                    && session()->get("halaman")['116']['act_create'] == "1"
                                ) ||
                                (
                                    isset(session()->get("halaman")['118']['act_create'])
                                    && session()->get("halaman")['118']['act_create'] == "1"
                                ) ||
                                (
                                    isset(session()->get("halaman")['119']['act_create'])
                                    && session()->get("halaman")['119']['act_create'] == "1"
                                ) ||
                                (
                                    isset(session()->get("halaman")['114']['act_create'])
                                    && session()->get("halaman")['114']['act_create'] == "1"
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
                                                    $builder = $this->db
                                                        ->table("job");
                                                    if ($this->session->get("position_name") == "Marketing Sales") {
                                                        $builder->where("job_sales", $this->session->get("user_id"));
                                                    }
                                                    $usr = $builder->orderBy("job_dano", "ASC")
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
                                    <?php if ($posisi != "operasional") { ?>
                                        <form method="post" class="col-md-2">
                                            <h1 class="page-header col-md-12">
                                                <button name="new" class="btn btn-info btn-block btn-sm" value="OK" style="">New</button>
                                                <input type="hidden" name="job_id" />
                                            </h1>
                                        </form>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </div>

                    <?php if (
                        isset($_POST['new']) || isset($_POST['edit']) || (isset($_GET["t"]) && $_GET["t"] == "ec")
                    ) { ?>
                        <div class="">
                            <?php if (isset($_POST['edit']) || (isset($_GET['tbl']) && $_GET['tbl'] == "edit")) {
                                $namabutton = 'name="change"';
                                $judul = "Update Job";
                            } else {
                                $namabutton = 'name="create"';
                                $judul = "Tambah Job";
                            } ?>
                            <div class="lead">
                                <h3><?= $judul; ?></h3>
                            </div>
                            <form class="form-horizontal row" method="post" enctype="multipart/form-data" action="<?= base_url($url); ?>">
                                <?php if ($posisi != "operasional") { ?>
                                    <!-- <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                            <label class="control-label col-sm-12" for="job_sell">SELL RPRICE:</label>
                                            <div class="col-sm-12">
                                                <input onchange="totalsell()" type="text" class="form-control" id="job_sell" name="job_sell" placeholder="" value="<?= $job_sell; ?>">
                                            </div>
                                        </div> -->
                                    <?php if ($posisi == "sales" || $posisi == "finance") { ?>
                                        <?php //if ($job_cost > 0) { 
                                        ?>
                                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                            <label class="control-label col-sm-12" for="">Cost:</label>
                                            <div class="col-sm-12">
                                                <a target="_self" href="<?= base_url("cost?t=ec&temp=" . $job_temp . "&url=" . $url . "&tbl=" . $tbl); ?>" class="btn btn-warning">Cost List</a>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                            <label class="control-label col-sm-12" for="job_cost">Cost:</label>
                                            <div class="col-sm-12">
                                                <input readonly onchange="profit()" type="text" class="form-control" id="job_cost" name="job_cost" placeholder="" value="<?= $job_cost; ?>">
                                            </div>
                                        </div>
                                        <?php //} 
                                        ?>

                                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                            <label class="control-label col-sm-12" for="">Description of Goods:</label>
                                            <div class="col-sm-12">
                                                <a target="_self" href="<?= base_url("jobd?t=ec&temp=" . $job_temp . "&url=" . $url . "&tbl=" . $tbl); ?>" class="btn btn-success">Description of Goods List</a>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                            <label class="control-label col-sm-12" for="job_total">TOTAL PRICE:</label>
                                            <div class="col-sm-12">
                                                <input readonly onchange="profit(); " type="text" class="form-control" id="job_total" name="job_total" placeholder="" value="<?= $job_total; ?>">
                                            </div>
                                        </div>


                                        <?php if ($job_total > 0) { ?>
                                            <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                                <label class="control-label col-sm-12" for="job_refund">REFUND:</label>
                                                <div class="col-sm-12">
                                                    <input onchange="profit()" type="text" class="form-control" id="job_refund" name="job_refund" placeholder="" value="<?= $job_refund; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                                <label class="control-label col-sm-12" for="job_profit">PROFIT:</label>
                                                <div class="col-sm-12">
                                                    <input onchange="fee()" type="text" class="form-control" id="job_profit" name="job_profit" placeholder="" value="<?= $job_profit; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                                <label class="control-label col-sm-12" for="job_fee"> MARKET FEE 15%:</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="job_fee" name="job_fee" placeholder="" value="<?= $job_fee; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                                <label class="control-label col-sm-12" for="job_gp">GP %:</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="job_gp" name="job_gp" placeholder="" value="<?= $job_gp; ?>">
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                                <?php if ($posisi != "operasional") { ?>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_sales">Marketer Name:</label>
                                        <div class="col-sm-12">
                                            <select onchange="namasales()" class="form-control select" id="job_sales" name="job_sales">
                                                <option value="">--Select--</option>
                                                <?php
                                                $usr = $this->db
                                                    ->table("user")
                                                    ->join("position", "position.position_id=user.position_id", "left")
                                                    ->where("position_name", "Marketing Sales")
                                                    ->orderBy("user_nama", "ASC")
                                                    ->get();
                                                foreach ($usr->getResult() as $usr) { ?>
                                                    <option data-sales="<?= $usr->user_nama; ?>" value="<?= $usr->user_id; ?>" <?= ($job_sales == $usr->user_id) ? "selected" : ""; ?>><?= $usr->user_nama; ?> - <?= $usr->position_name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                function namasales() {
                                                    let namanya = $("#job_sales option:selected").data("sales");
                                                    // alert(namanya);
                                                    $("#job_salesname").val(namanya);
                                                }
                                            </script>
                                            <input type="hidden" id="job_salesname" name="job_salesname" value="<?= $job_salesname; ?>" />
                                        </div>
                                    </div>
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
                                <?php } ?>



                                <?php if ($ppn != 2) { ?>
                                    <?php if ($posisi != "operasional") { ?>
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
                                        <!-- <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                            <label class="control-label col-sm-12" for="job_descgood">DESCRIPTION OF GOODS:</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="job_descgood" name="job_descgood" placeholder="" value="<?= $job_descgood; ?>">
                                            </div>
                                        </div> -->
                                    <?php } ?>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_pickup">Pickup Date:</label>
                                        <div class="col-sm-12">
                                            <input type="date" autofocus class="form-control" id="job_pickup" name="job_pickup" placeholder="" value="<?= $job_pickup; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_pickupuser">Operational Officer:</label>
                                        <div class="col-sm-12">
                                            <select onchange="namapetugas()" class="form-control select" id="job_pickupuser" name="job_pickupuser">
                                                <option value="">--Select--</option>
                                                <?php
                                                $usr = $this->db
                                                    ->table("user")
                                                    ->join("departemen", "departemen.departemen_id=user.departemen_id", "left")
                                                    ->join("position", "position.position_id=user.position_id", "left")
                                                    ->where("departemen_name", "OPERATION")
                                                    ->orderBy("user_nama", "ASC")
                                                    ->get();
                                                if ($this->session->get("departemen_name") == "OPERATION") {
                                                    $job_pickupuser = $this->session->get("user_id");
                                                }
                                                foreach ($usr->getResult() as $usr) { ?>
                                                    <option data-petugas="<?= $usr->user_nama; ?>" value="<?= $usr->user_id; ?>" <?= ($job_pickupuser == $usr->user_id) ? "selected" : ""; ?>><?= $usr->user_nama; ?> - <?= $usr->position_name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                function namapetugas() {
                                                    let namanya = $("#job_pickupuser option:selected").data("petugas");
                                                    // alert(namanya);
                                                    $("#job_pickupusername").val(namanya);
                                                }
                                            </script>
                                            <input type="hidden" id="job_pickupusername" name="job_pickupusername" value="<?= $job_pickupusername; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_handover">Hand Over / Yang Menyerahkan Barang:</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="job_handover" name="job_handover" placeholder="Penyerah Barang" value="<?= $job_handover; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12 text-info" style="font-weight:bold;" for="job_pickupstatus">Pickup Status:</label>
                                        <div class="col-sm-12">
                                            <select class="form-control" id="job_pickupstatus" name="job_pickupstatus">
                                                <option value="0" <?= ($job_pickupstatus == "0") ? "selected" : ""; ?>>--Select--</option>
                                                <option value="1" <?= ($job_pickupstatus == "1") ? "selected" : ""; ?>>Done</option>
                                                <option value="2" <?= ($job_pickupstatus == "2") ? "selected" : ""; ?>>Pending</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_methode">Metode:</label>
                                        <div class="col-sm-12">
                                            <select onchange="metode()" class="form-control select" id="job_methode" name="job_methode">
                                                <option value="">--Select--</option>
                                                <option value="lumpsum" <?= ($job_methode == "lumpsum") ? "selected" : ""; ?>>Lumpsum</option>
                                                <option value="cbm" <?= ($job_methode == "cbm") ? "selected" : ""; ?>>CBM / KGS</option>
                                            </select>
                                        </div>
                                    </div> -->
                                    <!-- <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_qty">QTY:</label>
                                        <div class="col-sm-12">
                                            <input onchange="totalsell()" type="text" class="form-control" id="job_qty" name="job_qty" placeholder="" value="<?= $job_qty; ?>">
                                        </div>
                                    </div> -->
                                    <!-- <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_cbm">CBM / KGS:</label>
                                        <div class="col-sm-12">
                                            <input onchange="totalsell()" type="text" class="form-control" id="job_cbm" name="job_cbm" placeholder="" value="<?= $job_cbm; ?>">
                                        </div>
                                    </div> -->


                                    <!-- <div class="form-group col-md-4 col-sm-6 col-xs-12">
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
                                    </div> -->
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

                                    <?php if ($posisi != "operasional") { ?>
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
                                    <?php } ?>
                                    <?php if ($ppn == 0) { ?>
                                        <?php if ($posisi != "operasional") { ?>
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
                                    <?php } ?>

                                    <?php if ($ppn == 0) { ?>
                                        <!-- <div class="form-group col-md-4 col-sm-6 col-xs-12">&nbsp;
                                        </div> -->
                                    <?php } ?>

                                    <?php if ($ppn == 1) { ?>
                                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                            <label class="control-label col-sm-12" for="vendor_id">Payment Methode:</label>
                                            <div class="col-sm-12">
                                                <input type="text" min="1" class="form-control" id="job_paynom" name="job_paynom" placeholder="" value="<?= ($job_paynom > 0) ? $job_paynom : 1; ?>">
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



                                <?php } ?>
                                <!--  <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="job_explanation">EXPLANATION:</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="job_explanation" name="job_explanation" placeholder="" value="<?= $job_explanation; ?>">
                                    </div>
                                </div> -->

                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12 text-success" style="font-weight:bold;" for="job_status">Status Job:</label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="job_status" name="job_status" value="<?= $job_status; ?>">
                                            <option value="">Pilih Status</option>
                                            <option value="PROCESS" <?= ($job_status == "PROCESS") ? "selected" : ""; ?>>PROCESS</option>
                                            <option value="DONE" <?= ($job_status == "DONE") ? "selected" : ""; ?>>DONE</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-12 label label-success">
                                    <h2 class="text-white">Surat Jalan</h2>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="job_pickupaddress">PICKUP ADDRESS:</label>
                                    <div class="col-sm-12">
                                        <input type="text" autofocus class="form-control" id="job_pickupaddress" name="job_pickupaddress" placeholder="" value="<?= $job_pickupaddress; ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="job_kepada">Kepada (di SJ):</label>
                                    <div class="col-sm-12">
                                        <input type="text" autofocus class="form-control" id="job_kepada" name="job_kepada" placeholder="" value="<?= $job_kepada; ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="job_kepadaaddress">Alamat Kepada (di SJ):</label>
                                    <div class="col-sm-12">
                                        <input type="text" autofocus class="form-control" id="job_kepadaaddress" name="job_kepadaaddress" placeholder="" value="<?= $job_kepadaaddress; ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="job_tujuan">Nama Tujuan:</label>
                                    <div class="col-sm-12">
                                        <input type="text" autofocus class="form-control" id="job_tujuan" name="job_tujuan" placeholder="" value="<?= $job_tujuan; ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="job_tujuanphone">Telpon Tujuan:</label>
                                    <div class="col-sm-12">
                                        <input type="text" autofocus class="form-control" id="job_tujuanphone" name="job_tujuanphone" placeholder="" value="<?= $job_tujuanphone; ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="job_tujuanaddress">Alamat Tujuan:</label>
                                    <div class="col-sm-12">
                                        <input type="text" autofocus class="form-control" id="job_tujuanaddress" name="job_tujuanaddress" placeholder="" value="<?= $job_tujuanaddress; ?>">
                                    </div>
                                </div>
                                <?php if ($posisi == "operasional" || $posisi == "finance") { ?>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_pengemudi">Pengemudi:</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="job_pengemudi" name="job_pengemudi" placeholder="" value="<?= $job_pengemudi; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_pengemudiphone">Whatsapp Pemgemudi:</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="job_pengemudiphone" name="job_pengemudiphone" placeholder="" value="<?= $job_pengemudiphone; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_nopol">No. Polisi:</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="job_nopol" name="job_nopol" placeholder="" value="<?= $job_nopol; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label col-sm-12" for="job_supervisi">Supervisi:</label>
                                        <div class="col-sm-12">
                                            <select class="form-control select" id="job_supervisi" name="job_supervisi">
                                                <option value="">--Select--</option>
                                                <?php
                                                $usr = $this->db
                                                    ->table("user")
                                                    ->join("position", "position.position_id=user.position_id", "left")
                                                    ->where("position_name", "Operation Supervisor")
                                                    ->orderBy("user_nama", "ASC")
                                                    ->get();
                                                foreach ($usr->getResult() as $usr) { ?>
                                                    <option value="<?= $usr->user_id; ?>" <?= ($job_supervisi == $usr->user_id) ? "selected" : ""; ?>><?= $usr->user_nama; ?> - <?= $usr->position_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-12" for="job_picture">Bukti</label>
                                        <div class="col-sm-12">
                                            <input type="file" class="form-control" id="job_picture" name="job_picture" placeholder="" value="<?= $job_picture; ?>">
                                            <?php
                                            if ($job_picture != "") {
                                                $user_image = "images/job_picture/" . $job_picture;
                                            } else {
                                                $user_image = "images/job_picture/no_image.png";
                                            }
                                            ?>
                                            <img id="job_picture_image" style="cursor:pointer;" width="100" height="100" src="<?= base_url($user_image); ?>" alt="Bukti" />

                                            <!-- Modal untuk gambar besar -->
                                            <div id="imgModal" style="
                                            display:none; 
                                            position:fixed; 
                                            z-index:999; 
                                            left:0; top:0; width:100%; height:100%; 
                                            background-color: rgba(0,0,0,0.8);
                                            align-items: center; 
                                            justify-content: center;">
                                                <span id="closeModal" style="
                                                position:absolute; 
                                                top:20px; right:35px; 
                                                color:#fff; 
                                                font-size:40px; 
                                                font-weight:bold; 
                                                cursor:pointer;">&times;</span>
                                                <img id="modalImage" src="" style="max-width:90%; max-height:90%; margin:auto; display:block;" />
                                            </div>

                                            <script>
                                                function readURL(input) {
                                                    if (input.files && input.files[0]) {
                                                        var reader = new FileReader();

                                                        reader.onload = function(e) {
                                                            $('#job_picture_image').attr('src', e.target.result);
                                                        }

                                                        reader.readAsDataURL(input.files[0]);
                                                    }
                                                }

                                                $("#job_picture").change(function() {
                                                    readURL(this);
                                                });

                                                // Event klik gambar untuk buka modal
                                                const img = document.getElementById('job_picture_image');
                                                const modal = document.getElementById('imgModal');
                                                const modalImg = document.getElementById('modalImage');
                                                const closeModal = document.getElementById('closeModal');

                                                img.onclick = function() {
                                                    modal.style.display = "flex";
                                                    modalImg.src = this.src;
                                                }

                                                closeModal.onclick = function() {
                                                    modal.style.display = "none";
                                                }

                                                // Klik di luar gambar modal juga bisa tutup modal
                                                modal.onclick = function(event) {
                                                    if (event.target == modal) {
                                                        modal.style.display = "none";
                                                    }
                                                }
                                            </script>
                                        </div>
                                    </div>
                                <?php } ?>

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
                                        profit();
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
                                    $(document).ready(function() {
                                        profit();
                                    });
                                </script>

                                <input type="hidden" name="job_ppntype" value="<?= $ppn; ?>" />
                                <input type="hidden" name="job_id" value="<?= $job_id; ?>" />
                                <input type="hidden" name="job_temp" value="<?= $job_temp; ?>" />
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <div class="col-sm-offset-2 col-sm-12">
                                        <button type="submit" id="submit" class="btn btn-primary col-md-5" <?= $namabutton; ?> value="OK">Submit</button>
                                        <a class="btn btn-warning col-md-offset-1 col-md-5" href="<?= base_url($url); ?>">Back</a>
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
                        <?php
                        $spilihan = "";
                        if (isset($_GET["spilihan"])) {
                            $spilihan = $_GET["spilihan"];
                        }
                        ?>
                        <script>
                            function pilihan(isi) {
                                $(".pilihan").hide();
                                if (isi == "jobdano") {
                                    $("#jobdano").show();
                                    $(".spilih").val(isi);
                                } else if (isi == "periode") {
                                    $("#periode").show();
                                    $(".spilih").val(isi);
                                } else {
                                    $("#pilih").show();
                                    $(".spilih").val(isi);
                                }
                            }

                            $(document).ready(function() {
                                pilihan('<?= $spilihan; ?>');
                            });
                        </script>
                        <form method="get" id="pilih" class="pilihan">
                            <div class="row alert alert-dark">
                                <div class="col-3">
                                    <select name="spilihan" class="form-control spilih" onchange="pilihan(this.value);">
                                        <option value="" <?= ($spilihan == "") ? "selected" : ""; ?>>Pilih Pencarian</option>
                                        <option value="jobdano" <?= ($spilihan == "jobdano") ? "selected" : ""; ?>>DA Number</option>
                                        <option value="periode" <?= ($spilihan == "periode") ? "selected" : ""; ?>>Periode</option>
                                    </select>
                                </div>
                            </div>
                        </form>


                        <form method="get" id="jobdano" class="pilihan">
                            <div class="row alert alert-dark">
                                <?php
                                $job_dano = "";
                                if (isset($_GET["job_dano"])) {
                                    $job_dano = $_GET["job_dano"];
                                }
                                ?>
                                <div class="col-3">
                                    <select name="spilihan" class="form-control spilih" onchange="pilihan(this.value);">
                                        <option value="" <?= ($spilihan == "") ? "selected" : ""; ?>>Pilih Pencarian</option>
                                        <option value="jobdano" <?= ($spilihan == "jobdano") ? "selected" : ""; ?>>DA Number</option>
                                        <option value="periode" <?= ($spilihan == "periode") ? "selected" : ""; ?>>Periode</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <input title="Da Number" type="text" class="form-control " placeholder="Da Number" name="job_dano" value="<?= $job_dano; ?>">

                                </div>

                                <div class="col-1">
                                    <?php if (isset($_GET["report"])) { ?><input type="hidden" name="report" value="OK"><?php } ?>
                                    <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>

                        <form method="get" id="periode" class="pilihan">
                            <div class="row alert alert-dark">
                                <div class="col-3">
                                    <select name="spilihan" class="form-control spilih" onchange="pilihan(this.value);">
                                        <option value="" <?= ($spilihan == "") ? "selected" : ""; ?>>Pilih Pencarian</option>
                                        <option value="jobdano" <?= ($spilihan == "jobdano") ? "selected" : ""; ?>>DA Number</option>
                                        <option value="periode" <?= ($spilihan == "periode") ? "selected" : ""; ?>>Periode</option>
                                    </select>
                                </div>

                                <?php
                                $dari = date("Y-m-d", strtotime("-5 days"));
                                $ke = date("Y-m-d");
                                $status = "";
                                $sinvoice = "";
                                $lunas = "";
                                $job_sales = "";
                                $idepartemen = 0;
                                if (isset($_GET["dari"])) {
                                    $dari = $_GET["dari"];
                                }
                                if (isset($_GET["ke"])) {
                                    $ke = $_GET["ke"];
                                }
                                if (isset($_GET["status"])) {
                                    $status = $_GET["status"];
                                }
                                if (isset($_GET["lunas"])) {
                                    $lunas = $_GET["lunas"];
                                }
                                if (isset($_GET["sinvoice"])) {
                                    $sinvoice = $_GET["sinvoice"];
                                }
                                if (isset($_GET["job_sales"])) {
                                    $job_sales = $_GET["job_sales"];
                                }
                                if (isset($_GET["customer_id"])) {
                                    $customer_id = $_GET["customer_id"];
                                }
                                ?>
                                <div class="col-2 mb-1">
                                    <input data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="manual" title="Dari" type="date" class="form-control tooltip-statis" placeholder="Dari" name="dari" value="<?= $dari; ?>">
                                </div>
                                <div class="col-2">
                                    <input data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="manual" title="Ke" type="date" class="form-control tooltip-statis" placeholder="Ke" name="ke" value="<?= $ke; ?>">

                                </div>
                                <div class="col-3">
                                    <select class="form-control" name="job_sales">
                                        <option value="" <?= ($job_sales == "") ? "selected" : ""; ?>>Sales Name</option>
                                        <?php $user = $this->db->table("user")->where("position_id", "102")->orderBy("user_nama")->get();
                                        foreach ($user->getResult() as $row) { ?>
                                            <option value="<?= $row->user_id; ?>" <?= ($job_sales == $row->user_id) ? "selected" : ""; ?>><?= $row->user_nama; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <select class="form-control select" name="customer_id">
                                        <option value="" <?= ($customer_id == "") ? "selected" : ""; ?>>Customer</option>
                                        <?php $customer = $this->db->table("customer")->orderBy("customer_name")->get();
                                        foreach ($customer->getResult() as $row) { ?>
                                            <option value="<?= $row->customer_id; ?>" <?= ($customer_id == $row->customer_id) ? "selected" : ""; ?>><?= $row->customer_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <select class="form-control" name="sinvoice">
                                        <option value="" <?= ($sinvoice == "") ? "selected" : ""; ?>>Status Invoice</option>
                                        <option value="invoice" <?= ($sinvoice == "invoice") ? "selected" : ""; ?>>Invoice</option>
                                        <option value="belum" <?= ($sinvoice == "belum") ? "selected" : ""; ?>>Belum</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <select class="form-control" name="lunas">
                                        <option value="" <?= ($lunas == "") ? "selected" : ""; ?>>Status Lunas</option>
                                        <option value="lunas" <?= ($lunas == "lunas") ? "selected" : ""; ?>>Lunas</option>
                                        <option value="belum" <?= ($lunas == "belum") ? "selected" : ""; ?>>Belum</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <select class="form-control" name="status">
                                        <option value="" <?= ($status == "") ? "selected" : ""; ?>>Status Job</option>
                                        <option value="PROCESS" <?= ($status == "PROCESS") ? "selected" : ""; ?>>PROCESS</option>
                                        <option value="DONE" <?= ($status == "DONE") ? "selected" : ""; ?>>DONE</option>
                                    </select>
                                </div>
                                <div class="col-1">
                                    <?php if (isset($_GET["report"])) { ?><input type="hidden" name="report" value="OK"><?php } ?>
                                    <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>

                        <!-- Modal -->
                        <div class="modal fade" id="tmodal" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Tracking DA No.<span id="ttitle"></span></h4>
                                    </div>
                                    <div id="tsuccess" class="alert alert-success" style="display:none;">
                                        Data berhasil disimpan.
                                    </div>
                                    <div class="modal-body">
                                        <form>
                                            <input type="date" class="form-control mb-2" id="date">
                                            <input type="text" placeholder="Description" class="form-control mb-2" id="desc">
                                            <input type="hidden" id="jid">
                                            <input type="hidden" id="jda">
                                            <button onclick="tinput()" type="button" class="btn btn-primary">Submit</button>
                                        </form>
                                        <table id="e231" class="mt-3 display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead class="">
                                                <tr>
                                                    <th class="col-4">Action</th>
                                                    <th>Date</th>
                                                    <th>Description</th>
                                                </tr>
                                            </thead>
                                            <tbody class="" id="ttable">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <script>
                            function tkosong() {
                                $("#ttitle").html("");
                                $("#ttable").html("");
                                $("#jid").val("");
                                $("#jda").val("");
                                $("#date").val("");
                                $("#desc").val("");
                            }

                            function tfkosong() {
                                $("#date").val("");
                                $("#desc").val("");
                            }
                        </script>
                        <script>
                            function tinput() {
                                let tdate = $("#date").val();
                                let jdesc = $("#desc").val();
                                let jid = $("#jid").val();
                                let jda = $("#jda").val();
                                // alert("<?= base_url("api/trackinginsert"); ?>?tdate="+tdate+"&jdesc="+jdesc+"&jid="+jid+"&jda="+jda);
                                $.get("<?= base_url("api/trackinginsert"); ?>", {
                                        tdate: tdate,
                                        jdesc: jdesc,
                                        jid: jid,
                                        jda: jda
                                    })
                                    .done(function(data) {
                                        tfkosong();
                                        trackingtable(jid);
                                        $("#tsuccess").html(data).fadeIn(200);
                                        setTimeout(function() {
                                            $("#tsuccess").fadeOut(300);
                                        }, 1000);
                                    });
                            }
                        </script>
                        <script>
                            function tdelete(id, jid) {
                                $.get("<?= base_url("api/trackingdelete"); ?>", {
                                        tid: id
                                    })
                                    .done(function(data) {
                                        tfkosong();
                                        trackingtable(jid);
                                        $("#tsuccess").html(data).fadeIn(200);
                                        setTimeout(function() {
                                            $("#tsuccess").fadeOut(300);
                                        }, 1000);
                                    });
                            }
                        </script>
                        <script>
                            function trackingtable(jid) {
                                // alert('<?= base_url("api/trackingtable"); ?>?jid=' + jid);
                                $.get("<?= base_url("api/trackingtable"); ?>", {
                                        jid: jid
                                    })
                                    .done(function(data) {
                                        $("#ttable").html(data);
                                    });
                            }
                        </script>
                        <script>
                            function tracking(jid, jda) {
                                // alert(jid + ', ' + jda);
                                tkosong();
                                $("#ttitle").html(jda);
                                $("#jid").val(jid);
                                $("#jda").val(jda);
                                trackingtable(jid);
                                $("#tmodal").modal("show");
                            }
                        </script>

                        <div class="table-responsive m-t-40">
                            <table id="e23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <!-- <table id="dataTable" class="table table-condensed table-hover w-auto dtable"> -->
                                <thead class="">
                                    <tr>
                                        <?php if (!isset($_GET["report"])) { ?>
                                            <th class="col-4">Action</th>
                                        <?php } ?>
                                        <!-- <th>No.</th> -->
                                        <?php if ($ppn == 0) { ?>
                                            <!-- <th>Methode</th> -->
                                        <?php } ?>
                                        <?php if ((session()->get("position_id") == 96) || (session()->get("position_id") == 108) || (session()->get("position_id") == 99) || (session()->get("position_id") == 100) || (session()->get("position_id") == 101)) { ?>
                                            <th>Invoice</th>
                                            <th>Lunas</th>
                                        <?php } ?>

                                        <th>Shipment Date</th>
                                        <th>Status Job</th>
                                        <th>Pickup Status</th>
                                        <th>Sales</th>
                                        <th>DA Number</th>
                                        <th>Shipper Name</th>
                                        <?php if ($ppn != 2) { ?>
                                            <th>Origin</th>
                                            <th>Destination</th>
                                            <?php if ((session()->get("position_id") == 96) || (session()->get("position_id") == 108) || (session()->get("position_id") == 99) || (session()->get("position_id") == 100) || (session()->get("position_id") == 101)) { ?>
                                                <th>Description</th>
                                            <?php } else { ?>
                                                <th>Tujuan</th>
                                                <th>Alamat Tujuan</th>
                                            <?php } ?>
                                            <!-- <th>Description of Goods</th>
                                            <th>Qty</th>
                                            <th>Satuan</th>
                                            <th>CBM/MT</th> -->
                                            <th>Service</th>
                                            <th>Trucking</th>
                                            <th>Vessel</th>
                                            <?php if ((session()->get("position_id") == 96) || (session()->get("position_id") == 108) || (session()->get("position_id") == 99) || (session()->get("position_id") == 100) || (session()->get("position_id") == 101)) {
                                            } else { ?>
                                                <th>Pickup Address</th>
                                            <?php } ?>
                                            <th>Pickup Date</th>
                                            <th>Petugas</th>
                                            <?php if ((session()->get("position_id") == 96) || (session()->get("position_id") == 108) || (session()->get("position_id") == 99) || (session()->get("position_id") == 100) || (session()->get("position_id") == 101)) { ?>
                                                <th>Qty</th>
                                                <th>CBM/KGS</th>
                                            <?php } else { ?>
                                                <th>Penyerah</th>
                                                <th>Kepada (SJ)</th>
                                                <th>Alamat Kepada</th>
                                            <?php } ?>


                                            <th>Supervisi</th>
                                            <th>Bukti</th>
                                            <?php if ((session()->get("position_id") == 96) || (session()->get("position_id") == 108) || (session()->get("position_id") == 99) || (session()->get("position_id") == 100) || (session()->get("position_id") == 101)) { ?>

                                            <?php } else { ?>
                                                <th>Nopol</th>
                                                <th>Pengemudi</th>
                                            <?php } ?>

                                            <?php if ($ppn == 0) { ?>
                                                <th>Vendor/Pelayaran</th>
                                                <th>Dooring</th>
                                            <?php } ?>
                                            <?php if ($posisi != "operasional") { ?>
                                                <!-- <th>Sell Price</th> -->
                                                <th>Total Price</th>
                                                <th>Cost</th>
                                                <th>Refund</th>
                                                <th>Market Fee 15%</th>
                                                <th>Profit</th>
                                                <th>GP%</th>
                                            <?php } ?>
                                            <?php if ($ppn == 1) { ?>
                                                <th>Payment Methode</th>
                                                <th>Tax No.</th>
                                                <th>Inv Date</th>
                                            <?php } ?>
                                        <?php } ?>

                                        <?php if ($ppn == 1) { ?>
                                            <th>Bupot No.</th>
                                            <th>NPWP</th>
                                        <?php } ?>
                                        <!-- <th>Explanation</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    //cari job_dano yg sesuai tgl
                                    $build = $this->db
                                        ->table("job");
                                    if ($spilihan == "periode") {
                                        $build->where("job_shipmentdate BETWEEN '" . $dari . "' AND '" . $ke . "'");
                                    }
                                    if ($spilihan == "jobdano") {
                                        $build->where("job.job_dano", $this->request->getGet("job_dano"));
                                    }
                                    $cari = $build->get();
                                    $arjob = array();
                                    $arjobprice = array();
                                    foreach ($cari->getResult() as $row) {
                                        $arjob[] = $row->job_dano;
                                        $arjobprice[$row->job_dano] = $row->job_total;
                                    }
                                    // dd($arjob);
                                    //data payment job
                                    $jobpay = array();
                                    if (!empty($arjob)) {
                                        $invpayment = $this->db
                                            ->table("invpayment")
                                            ->join("invd", "invd.inv_temp = invpayment.inv_temp", "left")
                                            ->whereIn("invd.job_dano", $arjob)
                                            ->get();
                                        // echo $this->db->getLastQuery();
                                        foreach ($invpayment->getResult() as $row) {
                                            $amount = $row->invpayment_total;
                                            if (!isset($jobpay[$row->job_dano])) {
                                                $jobpay[$row->job_dano] = 0;
                                            }
                                            $jobpay[$row->job_dano] += $amount;
                                        }
                                    }
                                    // dd($jobpay);
                                    $build = $this->db->table("job")
                                        ->select("*,job.job_dano as job_dano")
                                        // ->join("inv", "inv.job_dano=job.job_dano", "left");
                                        ->join("inv", "FIND_IN_SET(job.job_dano, REPLACE(inv.job_dano, ' ', ''))", "left");

                                    if ($spilihan == "periode") {
                                        $build->where("job_shipmentdate BETWEEN '" . $dari . "' AND '" . $ke . "'");
                                    }
                                    if ($spilihan == "jobdano") {
                                        $build->where("job.job_dano", $this->request->getGet("job_dano"));
                                    }
                                    // echo $build->getCompiledSelect();
                                    // exit;
                                    $jobb = $build->get();
                                    // echo $this->db->getLastQuery();
                                    $arlunas = array();
                                    $arbelum = array();
                                    $arinvoice = array();
                                    foreach ($jobb->getResult() as $row) {
                                        $job_dano = $row->job_dano;
                                        $dibayar = isset($jobpay[$job_dano]) ? $jobpay[$job_dano] : 0;
                                        $inv_grand = isset($row->inv_grand) ? $row->inv_grand : 0;
                                        // if ($dibayar >= $row->job_total) {
                                        if (($dibayar >= $inv_grand) && $inv_grand > 0) {
                                            $arlunas[] = $job_dano;
                                            $arlunasin[$job_dano] = $dibayar . " >= " . $row->inv_grand;
                                        } else {
                                            $arbelum[] = $job_dano;
                                            $arlunasin[$job_dano] = $dibayar . " >= " . $row->inv_grand;
                                        }
                                        // echo $dibayar . " >= " . $inv_grand . "<br/>";
                                        // dd($arlunas);
                                        if ($row->inv_id) {
                                            $arinvoice[] = $job_dano;
                                        }
                                    }
                                    // print_r($arbelum);
                                    $build = $this->db
                                        ->table("job")
                                        ->select("*,job.job_temp as job_temp, GROUP_CONCAT(jobd.jobd_descgood SEPARATOR ', ') as jobd_list,GROUP_CONCAT(jobd.jobd_koli SEPARATOR ', ') as jobd_lkoli,GROUP_CONCAT(jobd.jobd_cbm SEPARATOR ', ') as jobd_lcbm")
                                        ->join("(SELECT user_id AS supervisi_id, user_nama as supervisi_name from user)AS supervisi", "supervisi.supervisi_id = job.job_supervisi", "left")
                                        ->join("customer", "customer.customer_id = job.customer_id", "left")
                                        ->join("origin", "origin.origin_id = job.origin_id", "left")
                                        ->join("destination", "destination.destination_id = job.destination_id", "left")
                                        ->join("vendor", "vendor.vendor_id = job.vendor_id", "left")
                                        ->join("vendortruck", "vendortruck.vendortruck_id = job.vendortruck_id", "left")
                                        ->join("(SELECT vendor_id as vendor_id2, vendor_name AS vendor_name2 FROM vendor) AS v2", "v2.vendor_id2 = vendortruck.vendor_id", "left")
                                        ->join("(SELECT vendor_id as vendor_idd, vendor_name AS vendor_named FROM vendor) AS dooring", "dooring.vendor_idd = job.job_dooring", "left")
                                        ->join("service", "service.service_id = job.service_id", "left")
                                        ->join("vessel", "vessel.vessel_id = job.vessel_id", "left")
                                        ->join("jobd", "jobd.job_temp = job.job_temp", "left");;

                                    if ($lunas == "lunas") {
                                        if (!empty($arlunas)) {
                                            $build->whereIn("job.job_dano", $arlunas);
                                        } else {
                                            $build->where("1=0"); // supaya hasil langsung kosong, tanpa error
                                        }
                                    } else if ($lunas == "belum") {
                                        if (!empty($arbelum)) {
                                            $build->whereIn("job.job_dano", $arbelum);
                                        } else {
                                            $build->where("1=0"); // hasil kosong juga
                                        }
                                    }
                                    if ($ppn != 0) {
                                        $build->where("job_ppntype", $ppn);
                                    }
                                    if (isset($_GET["status"]) && $_GET["status"] != "") {
                                        if ($status == "DONE") {
                                            $build->where("job_status", "DONE");
                                        } else if ($status == "PROCESS") {
                                            $build->where("job_status", "PROCESS");
                                        }
                                    }
                                    if (isset($_GET["sinvoice"]) && $_GET["sinvoice"] != "") {
                                        if ($sinvoice == "invoice") {
                                            $build->where("inv_temp !=", "");
                                        } else if ($sinvoice == "belum") {
                                            $build->where("inv_temp", "");
                                        }
                                    }
                                    if (isset($_GET["customer_id"]) && $_GET["customer_id"] != "") {
                                        $build->where("job.customer_id", $_GET["customer_id"]);
                                    }
                                    if (!isset($_GET["spilihan"])) {
                                        $build->where("job_shipmentdate BETWEEN '" . $dari . "' AND '" . $ke . "'");
                                    }
                                    if ($spilihan == "periode") {
                                        $build->where("job_shipmentdate BETWEEN '" . $dari . "' AND '" . $ke . "'");
                                    }
                                    if ($spilihan == "jobdano") {
                                        $build->where("job.job_dano", $this->request->getGet("job_dano"));
                                    }
                                    if (isset($_GET["job_sales"]) && $_GET["job_sales"] != "") {
                                        $build->where("job_sales", $_GET["job_sales"]);
                                    }
                                    if (session()->get("position_id") == "102") {
                                        $build->where("job_sales", session()->get("user_id"));
                                    }

                                    if (isset($_GET["job_dano"]) && $_GET["job_dano"] != "") {
                                        $build->where("job_dano", $_GET["job_dano"]);
                                    }
                                    // echo $build->getCompiledSelect();
                                    // exit;
                                    $usr = $build
                                        ->groupBy("job.job_id") // WAJIB, biar GROUP_CONCAT jalan per job
                                        ->orderBy("job_dano", "ASC")
                                        ->get();
                                    // echo $this->db->getLastquery();
                                    $no = 1;
                                    // $usr->job_total, $usr->job_cost, $usr->job_refund, $usr->job_fee, $usr->job_profit, $usr->job_gp
                                    $job_total = 0;
                                    $job_cost = 0;
                                    $job_refund = 0;
                                    $job_fee = 0;
                                    $job_profit = 0;
                                    $job_gp = 0;
                                    $statuspickup = array("", "Done", "Pending");
                                    foreach ($usr->getResult() as $usr) {
                                        switch ($usr->job_pickupstatus) {
                                            case "0":
                                                $linestatus = "";
                                                $textstatus = "text-dark";
                                                break;
                                            case "1":
                                                $linestatus = "bg-success";
                                                $textstatus = "text-dark";
                                                break;
                                            case "2":
                                                $linestatus = "bg-warning";
                                                $textstatus = "text-dark";
                                                break;
                                        }
                                        if ($usr->job_status == "DONE") {
                                            $linestatus = "bg-dark";
                                            $textstatus = "text-white";
                                        }
                                    ?>
                                        <tr class="<?= $linestatus; ?>">
                                            <?php if (!isset($_GET["report"])) { ?>
                                                <td class="<?= $textstatus; ?> col-4" style="padding-left:0px; padding-right:0px;" class="<?= $textstatus; ?>">
                                                    <form target="_blank" method="get" class="btn-action" style="" action="<?= base_url("jobcst"); ?>">
                                                        <button title="Tracking Customer" data-bs-toggle="tooltip" class="btn btn-sm btn-success " value="OK"><span class="fa fa-link" style=""></span> </button>
                                                        <input type="hidden" name="job_id" value="<?= $usr->job_id; ?>" />
                                                    </form>
                                                    <form target="_blank" method="get" class="btn-action" style="" action="<?= base_url("jobcst"); ?>">
                                                        <button onclick="tracking('<?= $usr->job_id; ?>','<?= $usr->job_dano; ?>')" type="button" title="Input Tracking" data-bs-toggle="tooltip" class="btn btn-sm btn-warning " value="OK"><span class="fa fa-plane" style=""></span> </button>
                                                        <input type="hidden" name="job_id" value="<?= $usr->job_id; ?>" />
                                                    </form>
                                                    <?php
                                                    if ($posisi != "purchasing" && $posisi != "operasional") {
                                                        if (
                                                            (
                                                                isset(session()->get("position_administrator")[0][0])
                                                                && (
                                                                    session()->get("position_administrator") == "1"
                                                                    || session()->get("position_administrator") == "2"
                                                                )
                                                            ) ||
                                                            (
                                                                isset(session()->get("halaman")['102']['act_delete'])
                                                                && session()->get("halaman")['102']['act_delete'] == "1"
                                                            ) ||
                                                            (
                                                                isset(session()->get("halaman")['115']['act_delete'])
                                                                && session()->get("halaman")['115']['act_delete'] == "1"
                                                            ) ||
                                                            (
                                                                isset(session()->get("halaman")['116']['act_delete'])
                                                                && session()->get("halaman")['116']['act_delete'] == "1"
                                                            ) ||
                                                            (
                                                                isset(session()->get("halaman")['118']['act_delete'])
                                                                && session()->get("halaman")['118']['act_delete'] == "1"
                                                            ) ||
                                                            (
                                                                isset(session()->get("halaman")['119']['act_delete'])
                                                                && session()->get("halaman")['119']['act_delete'] == "1"
                                                            ) ||
                                                            (
                                                                isset(session()->get("halaman")['114']['act_delete'])
                                                                && session()->get("halaman")['114']['act_delete'] == "1"
                                                            )
                                                        ) { ?>
                                                            <form method="post" class="btn-action" style="">
                                                                <button title="Delete" data-bs-toggle="tooltip" class="btn btn-sm btn-danger delete" onclick="return confirm(' you want to delete?');" name="delete" value="OK"><span class="fa fa-close" style="color:white;"></span> </button>
                                                                <input type="hidden" name="job_id" value="<?= $usr->job_id; ?>" />
                                                            </form>
                                                    <?php }
                                                    } ?>

                                                    <?php
                                                    if ($posisi != "purchasing") {
                                                        if (
                                                            (
                                                                isset(session()->get("position_administrator")[0][0])
                                                                && (
                                                                    session()->get("position_administrator") == "1"
                                                                    || session()->get("position_administrator") == "2"
                                                                )
                                                            ) ||
                                                            (
                                                                isset(session()->get("halaman")['102']['act_update'])
                                                                && session()->get("halaman")['102']['act_update'] == "1"
                                                            ) ||
                                                            (
                                                                isset(session()->get("halaman")['115']['act_update'])
                                                                && session()->get("halaman")['115']['act_update'] == "1"
                                                            ) ||
                                                            (
                                                                isset(session()->get("halaman")['116']['act_update'])
                                                                && session()->get("halaman")['116']['act_update'] == "1"
                                                            ) ||
                                                            (
                                                                isset(session()->get("halaman")['118']['act_update'])
                                                                && session()->get("halaman")['118']['act_update'] == "1"
                                                            ) ||
                                                            (
                                                                isset(session()->get("halaman")['119']['act_update'])
                                                                && session()->get("halaman")['119']['act_update'] == "1"
                                                            ) |
                                                            (
                                                                isset(session()->get("halaman")['114']['act_update'])
                                                                && session()->get("halaman")['114']['act_update'] == "1"
                                                            )
                                                        ) { ?>
                                                            <form method="post" class="btn-action" style="" action="<?= base_url(uri_string()) . '?tbl=edit'; ?>">
                                                                <button title="Edit" data-bs-toggle="tooltip" class="btn btn-sm btn-warning " name="edit" value="OK"><span class="fa fa-edit" style="color:white;"></span> </button>
                                                                <input type="hidden" name="job_id" value="<?= $usr->job_id; ?>" />
                                                            </form>
                                                    <?php }
                                                    } ?>

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
                                                            isset(session()->get("halaman")['102']['act_update'])
                                                            && session()->get("halaman")['102']['act_update'] == "1"
                                                        ) ||
                                                        (
                                                            isset(session()->get("halaman")['115']['act_update'])
                                                            && session()->get("halaman")['115']['act_update'] == "1"
                                                        ) ||
                                                        (
                                                            isset(session()->get("halaman")['116']['act_update'])
                                                            && session()->get("halaman")['116']['act_update'] == "1"
                                                        ) ||
                                                        (
                                                            isset(session()->get("halaman")['118']['act_update'])
                                                            && session()->get("halaman")['118']['act_update'] == "1"
                                                        ) ||
                                                        (
                                                            isset(session()->get("halaman")['119']['act_update'])
                                                            && session()->get("halaman")['119']['act_update'] == "1"
                                                        ) ||
                                                        (
                                                            isset(session()->get("halaman")['114']['act_update'])
                                                            && session()->get("halaman")['114']['act_update'] == "1"
                                                        )
                                                    ) { ?>
                                                        <form method="get" class="btn-action" style="" action="<?= base_url("jobd"); ?>">
                                                            <button title="Details" data-bs-toggle="tooltip" class="btn btn-sm btn-secondary " name="jobd" value="OK"><span class="fa fa-cubes" style=""></span> </button>
                                                            <input type="hidden" name="job_id" value="<?= $usr->job_id; ?>" />
                                                            <input type="hidden" name="t" value="jc" />
                                                            <input type="hidden" name="temp" value="<?= $usr->job_temp; ?>" />
                                                            <input type="hidden" name="url" value="<?= $url; ?>" />
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
                                                            isset(session()->get("halaman")['102']['act_update'])
                                                            && session()->get("halaman")['102']['act_update'] == "1"
                                                        ) ||
                                                        (
                                                            isset(session()->get("halaman")['115']['act_update'])
                                                            && session()->get("halaman")['115']['act_update'] == "1"
                                                        ) ||
                                                        (
                                                            isset(session()->get("halaman")['116']['act_update'])
                                                            && session()->get("halaman")['116']['act_update'] == "1"
                                                        ) ||
                                                        (
                                                            isset(session()->get("halaman")['118']['act_update'])
                                                            && session()->get("halaman")['118']['act_update'] == "1"
                                                        ) ||
                                                        (
                                                            isset(session()->get("halaman")['119']['act_update'])
                                                            && session()->get("halaman")['119']['act_update'] == "1"
                                                        ) ||
                                                        (
                                                            isset(session()->get("halaman")['114']['act_update'])
                                                            && session()->get("halaman")['114']['act_update'] == "1"
                                                        )
                                                    ) { ?>
                                                        <form method="get" class="btn-action" style="" action="<?= base_url("cost"); ?>">
                                                            <button title="Costing" data-bs-toggle="tooltip" class="btn btn-sm btn-info " name="cost" value="OK"><span class="fa fa-money" style="color:white;"></span> </button>
                                                            <input type="hidden" name="job_id" value="<?= $usr->job_id; ?>" />
                                                            <input type="hidden" name="t" value="jc" />
                                                            <input type="hidden" name="temp" value="<?= $usr->job_temp; ?>" />
                                                            <input type="hidden" name="url" value="<?= $url; ?>" />
                                                        </form>
                                                    <?php } ?>

                                                    <?php
                                                    if ($posisi == "operasional" || $posisi == "finance") {
                                                        if (
                                                            (
                                                                isset(session()->get("position_administrator")[0][0])
                                                                && (
                                                                    session()->get("position_administrator") == "1"
                                                                    || session()->get("position_administrator") == "2"
                                                                )
                                                            ) ||
                                                            (
                                                                isset(session()->get("halaman")['102']['act_update'])
                                                                && session()->get("halaman")['102']['act_update'] == "1"
                                                            ) ||
                                                            (
                                                                isset(session()->get("halaman")['115']['act_update'])
                                                                && session()->get("halaman")['115']['act_update'] == "1"
                                                            ) ||
                                                            (
                                                                isset(session()->get("halaman")['116']['act_update'])
                                                                && session()->get("halaman")['116']['act_update'] == "1"
                                                            ) ||
                                                            (
                                                                isset(session()->get("halaman")['118']['act_update'])
                                                                && session()->get("halaman")['118']['act_update'] == "1"
                                                            ) ||
                                                            (
                                                                isset(session()->get("halaman")['119']['act_update'])
                                                                && session()->get("halaman")['119']['act_update'] == "1"
                                                            ) ||
                                                            (
                                                                isset(session()->get("halaman")['114']['act_update'])
                                                                && session()->get("halaman")['114']['act_update'] == "1"
                                                            )
                                                        ) { ?>
                                                            <form target="_blank" method="get" class="btn-action" style="" action="<?= base_url("sjprint"); ?>">
                                                                <button title="Surat Jalan" data-bs-toggle="tooltip" class="btn btn-sm btn-primary" name="sj" value="OK"><span class="fa fa-address-card-o" style="color:white;"></span> </button>
                                                                <input type="hidden" name="job_id" value="<?= $usr->job_id; ?>" />
                                                                <input type="hidden" name="t" value="jc" />
                                                                <input type="hidden" name="temp" value="<?= $usr->job_temp; ?>" />
                                                                <input type="hidden" name="url" value="<?= $url; ?>" />
                                                            </form>
                                                    <?php }
                                                    } ?>
                                                </td>
                                            <?php } ?>
                                            <!-- <td class="<?= $textstatus; ?>"><?= $no++; ?></td> -->
                                            <?php if ($ppn == 0) { ?>
                                                <!-- <td class="<?= $textstatus; ?>"><?= $usr->job_methode; ?></td> -->
                                            <?php } ?>
                                            <?php if ((session()->get("position_id") == 96) || (session()->get("position_id") == 108) || (session()->get("position_id") == 99) || (session()->get("position_id") == 100) || (session()->get("position_id") == 101)) { ?>
                                                <td class="<?= $textstatus; ?>" style="white-space:nowrap;">
                                                    <?php if (in_array($usr->job_dano, $arinvoice)) {
                                                        echo "Invoice";
                                                    } else {
                                                        echo "Belum";
                                                    } ?>
                                                </td>
                                                <td class="<?= $textstatus; ?>" style="white-space:nowrap;">
                                                    <?php if (in_array($usr->job_dano, $arlunas)) {
                                                        echo "Lunas";
                                                    } else {
                                                        echo "Belum";
                                                    } ?>
                                                </td>
                                            <?php } ?>

                                            <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->job_shipmentdate; ?></td>
                                            <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->job_status; ?></td>
                                            <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $statuspickup[$usr->job_pickupstatus]; ?></td>
                                            <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->job_salesname; ?></td>
                                            <td class="<?= $textstatus; ?>"><?= $usr->job_dano; ?></td>
                                            <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->customer_name; ?></td>

                                            <?php if ($ppn != 2) { ?>
                                                <td class="<?= $textstatus; ?>"><?= $usr->origin_name; ?></td>
                                                <td class="<?= $textstatus; ?>"><?= $usr->destination_name; ?></td>

                                                <?php if ((session()->get("position_id") == 96) || (session()->get("position_id") == 108) || (session()->get("position_id") == 99) || (session()->get("position_id") == 100) || (session()->get("position_id") == 101)) { ?>
                                                    <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->jobd_list; ?></td>
                                                <?php } else { ?>
                                                    <td class="<?= $textstatus; ?>"><?= $usr->job_tujuan; ?></td>
                                                    <td class="<?= $textstatus; ?>"><?= $usr->job_tujuanaddress; ?></td>
                                                <?php } ?>
                                                <!-- <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->job_descgood; ?></td>
                                                <td class="<?= $textstatus; ?>"><?= number_format($usr->job_qty, 0, ",", "."); ?></td>
                                                <td class="<?= $textstatus; ?>"><?= $usr->job_satuan; ?></td>
                                                <td class="<?= $textstatus; ?>"><?= number_format($usr->job_cbm, 3, ",", "."); ?></td> -->
                                                <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->service_name; ?></td>
                                                <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->vendortruck_name; ?> - <?= $usr->vendor_name2; ?></td>
                                                <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->vessel_name; ?></td>

                                                <?php if ((session()->get("position_id") == 96) || (session()->get("position_id") == 108) || (session()->get("position_id") == 99) || (session()->get("position_id") == 100) || (session()->get("position_id") == 101)) {
                                                } else { ?>
                                                    <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->job_pickupaddress; ?></td>
                                                <?php } ?>
                                                <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->job_pickup; ?></td>
                                                <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->job_pickupusername; ?></td>
                                                <?php if ((session()->get("position_id") == 96) || (session()->get("position_id") == 108) || (session()->get("position_id") == 99) || (session()->get("position_id") == 100) || (session()->get("position_id") == 101)) { ?>
                                                    <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->jobd_lkoli; ?></td>
                                                    <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->jobd_lcbm; ?></td>
                                                <?php } else { ?>
                                                    <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->job_handover; ?></td>
                                                    <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->job_kepada; ?></td>
                                                    <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->job_kepadaaddress; ?></td>
                                                <?php } ?>


                                                <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->supervisi_name; ?></td>
                                                <td class="<?= $textstatus; ?>" style="white-space:nowrap;">
                                                    <button
                                                        class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#gambarModal"
                                                        onclick="setGambar('<?= base_url('images/job_picture/' . $usr->job_picture); ?>')">
                                                        Lihat Bukti
                                                    </button>
                                                </td>
                                                <?php if ((session()->get("position_id") == 96) || (session()->get("position_id") == 108) || (session()->get("position_id") == 99) || (session()->get("position_id") == 100) || (session()->get("position_id") == 101)) { ?>

                                                <?php } else { ?>
                                                    <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->job_nopol; ?></td>
                                                    <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->job_pengemudi; ?></td>
                                                <?php } ?>

                                                <?php if ($ppn == 0) { ?>
                                                    <td class="<?= $textstatus; ?>"><?= $usr->vendor_name; ?></td>
                                                    <td class="<?= $textstatus; ?>"><?= $usr->vendor_named; ?></td>
                                                <?php } ?>
                                                <?php if ($posisi != "operasional") { ?>
                                                    <!-- <td class="<?= $textstatus; ?>"><?= number_format($usr->job_sell, 0, ",", "."); ?></td> -->
                                                    <td class="<?= $textstatus; ?>"><?= number_format($usr->job_total, 0, ",", ".");
                                                                                    $job_total += $usr->job_total; ?></td>
                                                    <td class="<?= $textstatus; ?>"><?= number_format($usr->job_cost, 0, ",", ".");
                                                                                    $job_cost += $usr->job_cost;  ?></td>
                                                    <td class="<?= $textstatus; ?>"><?= number_format($usr->job_refund, 0, ",", ".");
                                                                                    $job_refund += $usr->job_refund;  ?></td>
                                                    <td class="<?= $textstatus; ?>"><?= number_format($usr->job_fee, 0, ",", ".");
                                                                                    $job_fee += $usr->job_fee;  ?></td>
                                                    <td class="<?= $textstatus; ?>"><?= number_format($usr->job_profit, 0, ",", ".");
                                                                                    $job_profit += $usr->job_profit;  ?></td>
                                                    <td class="<?= $textstatus; ?>"><?= number_format($usr->job_gp, 0, ",", ".");
                                                                                    $job_gp += $usr->job_gp;  ?></td>
                                                <?php } ?>
                                                <?php if ($ppn == 1) { ?>
                                                    <td class="<?= $textstatus; ?>"><?= $usr->job_paynom; ?> <?= $usr->job_payunit; ?></td>
                                                    <td class="<?= $textstatus; ?>"><?= $usr->job_taxno; ?></td>
                                                    <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->job_invdate; ?></td>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if ($ppn == 1) { ?>
                                                <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->job_bupot; ?></td>
                                                <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->job_npwp; ?></td>
                                            <?php } ?>

                                            <!-- <td class="<?= $textstatus; ?>" style="white-space:nowrap;"><?= $usr->job_explanation; ?></td> -->
                                        </tr>
                                    <?php } ?>

                                    <tr class="bold">
                                        <?php if (!isset($_GET["report"])) { ?>
                                            <td class="text-right"></td>
                                        <?php } ?>
                                        <!-- <td class="text-right"></td> -->
                                        <?php if ($ppn == 0) { ?>
                                            <!-- <td class="text-right"></td> -->
                                        <?php } ?>
                                        <?php if ((session()->get("position_id") == 96) || (session()->get("position_id") == 108) || (session()->get("position_id") == 99) || (session()->get("position_id") == 100) || (session()->get("position_id") == 101)) { ?>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                        <?php } ?>

                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <?php if ($ppn != 2) { ?>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <?php if ((session()->get("position_id") == 96) || (session()->get("position_id") == 108) || (session()->get("position_id") == 99) || (session()->get("position_id") == 100) || (session()->get("position_id") == 101)) { ?>
                                                <td class="text-right"></td>
                                            <?php } else { ?>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            <?php } ?>
                                            <!-- <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td> -->
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <?php if ((session()->get("position_id") == 96) || (session()->get("position_id") == 108) || (session()->get("position_id") == 99) || (session()->get("position_id") == 100) || (session()->get("position_id") == 101)) {
                                            } else { ?>
                                                <td class="text-right"></td>
                                            <?php } ?>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <?php if ((session()->get("position_id") == 96) || (session()->get("position_id") == 108) || (session()->get("position_id") == 99) || (session()->get("position_id") == 100) || (session()->get("position_id") == 101)) { ?>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            <?php } else { ?>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            <?php } ?>


                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <?php if ((session()->get("position_id") == 96) || (session()->get("position_id") == 108) || (session()->get("position_id") == 99) || (session()->get("position_id") == 100) || (session()->get("position_id") == 101)) { ?>

                                            <?php } else { ?>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            <?php } ?>

                                            <?php if ($ppn == 0) { ?>
                                                <td class="text-right"></td>
                                                <td class="text-right">TOTAL</td>
                                            <?php } ?>
                                            <?php if ($posisi != "operasional") { ?>
                                                <!-- <td class="text-right"></td> -->
                                                <td><?= number_format($job_total, 0, ",", "."); ?></td>
                                                <td><?= number_format($job_cost, 0, ",", "."); ?></td>
                                                <td><?= number_format($job_refund, 0, ",", "."); ?></td>
                                                <td><?= number_format($job_fee, 0, ",", "."); ?></td>
                                                <td><?= number_format($job_profit, 0, ",", "."); ?></td>
                                                <td><?= number_format($job_gp, 0, ",", "."); ?></td>
                                            <?php } ?>
                                            <?php if ($ppn == 1) { ?>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                                <<td class="text-right">
                                                    </td>
                                                <?php } ?>
                                            <?php } ?>

                                            <?php if ($ppn == 1) { ?>
                                                <td class="text-right"></td>
                                                <td class="text-right"></td>
                                            <?php } ?>
                                            <!-- <td class="text-right"></td> -->
                                    </tr>

                                    <!-- <tr class="bold">
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right">Total</td>
                                        <td><?= number_format($job_total, 0, ",", "."); ?></td>
                                        <td><?= number_format($job_cost, 0, ",", "."); ?></td>
                                        <td><?= number_format($job_refund, 0, ",", "."); ?></td>
                                        <td><?= number_format($job_fee, 0, ",", "."); ?></td>
                                        <td><?= number_format($job_profit, 0, ",", "."); ?></td>
                                        <td><?= number_format($job_gp, 0, ",", "."); ?></td>
                                    </tr> -->
                                </tbody>
                            </table>
                            <!-- Modal Bootstrap -->
                            <div class="modal fade" id="gambarModal" tabindex="-1" aria-labelledby="gambarModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content bg-dark">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title text-white" id="gambarModalLabel">Bukti Gambar</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img id="modalGambarSrc" src="" alt="Gambar Bukti" class="img-fluid rounded" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                function setGambar(src) {
                                    document.getElementById('modalGambarSrc').src = src;

                                    // Panggil modal secara manual via Bootstrap JS
                                    const modalElement = document.getElementById('gambarModal');
                                    const modal = new bootstrap.Modal(modalElement);
                                    modal.show();
                                }
                            </script>


                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.select').select2({
        width: '100%'
    });

    var title = "<?= $title; ?>";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tooltipTriggerList = document.querySelectorAll('.tooltip-statis');

        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            const tooltip = new bootstrap.Tooltip(tooltipTriggerEl);

            // Menampilkan tooltip secara manual
            // tooltip.show();
        });
    });
    $(document).ready(function() {
        $('#e23').DataTable({
            dom: 'Bfrtip',
            autoWidth: false,
            buttons: [{
                    extend: 'print',
                    exportOptions: {
                        columns: ':not(:first-child)'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Export PDF',
                    orientation: 'landscape',
                    pageSize: 'LEGAL', // atau pakai ukuran kustom di customize
                    customize: function(doc) {
                        doc.pageSize = {
                            width: 1500, // dalam poin (1 inch = 72 poin)
                            height: 800
                        };
                        doc.defaultStyle.fontSize = 6;
                        doc.styles.tableHeader.fontSize = 7;
                        doc.pageMargins = [10, 10, 10, 10];
                        doc.content[1].table.widths =
                            Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':not(:first-child)'
                    }
                }
            ],
            ordering: false, // Mencegah DataTables mengatur order by
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            pageLength: 10 // Default jumlah baris per halaman
        });
    });
</script>

<?php echo  $this->include("template/footer_v"); ?>