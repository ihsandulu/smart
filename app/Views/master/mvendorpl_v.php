<?php echo $this->include("template/header_v"); ?>

<div class='container-fluid'>
    <div class='row'>
        <div class='col-12'>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <?php if (!isset($_GET['user_id']) && !isset($_POST['new']) && !isset($_POST['edit'])) {
                            $coltitle = "col-md-8";
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
                                    isset(session()->get("halaman")['96']['act_create'])
                                    && session()->get("halaman")['96']['act_create'] == "1"
                                )
                            ) { ?>
                                <form action="<?= base_url("mvendor"); ?>" method="get" class="col-md-2">
                                    <h1 class="page-header col-md-12">
                                        <button class="btn btn-warning btn-block btn-lg" value="OK" style="">Back</button>
                                    </h1>
                                </form>
                                <form method="post" class="col-md-2">
                                    <h1 class="page-header col-md-12">
                                        <button name="new" class="btn btn-info btn-block btn-lg" value="OK" style="">New</button>
                                        <input type="hidden" name="vendorpl_id" />
                                    </h1>
                                </form>
                            <?php } ?>
                        <?php } ?>
                    </div>

                    <?php if (isset($_POST['new']) || isset($_POST['edit'])) { ?>
                        <div class="">
                            <?php if (isset($_POST['edit'])) {
                                $namabutton = 'name="change"';
                                $judul = "Update Pricelist";
                            } else {
                                $namabutton = 'name="create"';
                                $judul = "Tambah Pricelist";
                            } ?>
                            <div class="lead">
                                <h3><?= $judul; ?></h3>
                            </div>
                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="vendortruck_id">Trucking:</label>
                                    <div class="col-sm-10">
                                        <select  autofocus class="form-control" id="vendortruck_id" name="vendortruck_id">
                                            <option value="0" <?= ($vendortruck_id == "0") ? "selected" : ""; ?>>Select Type</option>
                                            <?php $vendortruck = $this->db->table("vendortruck")->where("vendor_id",$_GET["vendor_id"])->orderBy("vendortruck_name", "ASC")->get();
                                            foreach ($vendortruck->getResult() as $row) { ?>
                                                <option value="<?= $row->vendortruck_id; ?>" <?= ($vendortruck_id == $row->vendortruck_id) ? "selected" : ""; ?>><?= $row->vendortruck_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="vessel_id">Vessel:</label>
                                    <div class="col-sm-10">
                                        <select   class="form-control" id="vessel_id" name="vessel_id">
                                            <option value="0" <?= ($vessel_id == "0") ? "selected" : ""; ?>>Select Vessel</option>
                                            <?php $vessel = $this->db->table("vessel")->orderBy("vessel_name", "ASC")->get();
                                            foreach ($vessel->getResult() as $row) { ?>
                                                <option value="<?= $row->vessel_id; ?>" <?= ($vessel_id == $row->vessel_id) ? "selected" : ""; ?>><?= $row->vessel_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="origin_id">Origin:</label>
                                    <div class="col-sm-10">
                                        <select required autofocus class="form-control" id="origin_id" name="origin_id">
                                            <option value="0" <?= ($origin_id == "0") ? "selected" : ""; ?>>Select Origin</option>
                                            <?php $origin = $this->db->table("origin")->orderBy("origin_name", "ASC")->get();
                                            foreach ($origin->getResult() as $row) { ?>
                                                <option value="<?= $row->origin_id; ?>" <?= ($origin_id == $row->origin_id) ? "selected" : ""; ?>><?= $row->origin_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="destination_id">Destination:</label>
                                    <div class="col-sm-10">
                                        <select required autofocus class="form-control" id="destination_id" name="destination_id">
                                            <option value="0" <?= ($destination_id == "0") ? "selected" : ""; ?>>Select Destination</option>
                                            <?php $destination = $this->db->table("destination")->orderBy("destination_name", "ASC")->get();
                                            foreach ($destination->getResult() as $row) { ?>
                                                <option value="<?= $row->destination_id; ?>" <?= ($destination_id == $row->destination_id) ? "selected" : ""; ?>><?= $row->destination_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="vendorpl_nominal">Nominal:</label>
                                    <div class="col-sm-10">
                                        <input required type="text" class="form-control" id="vendorpl_nominal" name="vendorpl_nominal" placeholder="" value="<?= $vendorpl_nominal; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="vendorpl_satuan">Satuan:</label>
                                    <div class="col-sm-10">
                                        <select required autofocus class="form-control" id="vendorpl_satuan" name="vendorpl_satuan">
                                            <option value="" <?= ($vendorpl_satuan == "") ? "selected" : ""; ?>>Select Satuan</option>
                                            <?php $satuantarif = $this->db->table("satuantarif")->orderBy("satuantarif_name", "ASC")->get();
                                            foreach ($satuantarif->getResult() as $row) { ?>
                                                <option value="<?= $row->satuantarif_name; ?>" <?= ($vendorpl_satuan == $row->satuantarif_name) ? "selected" : ""; ?>><?= $row->satuantarif_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <input type="hidden" name="vendor_id" value="<?= (isset($_GET["vendor_id"]) && $_GET["vendor_id"] != "") ? $_GET["vendor_id"] : $vendor_id; ?>" />
                                <input type="hidden" name="vendorpl_id" value="<?= $vendorpl_id; ?>" />
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" id="submit" class="btn btn-primary col-md-5" <?= $namabutton; ?> value="OK">Submit</button>
                                        <a class="btn btn-warning col-md-offset-1 col-md-5" href="<?= base_url("mvendorpl?vendor_id=" . $_GET["vendor_id"] . "&vendor_name=" . $_GET["vendor_name"]); ?>">Back</a>
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

                        <div class="table-responsive m-t-40">
                            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <!-- <table id="dataTable" class="table table-condensed table-hover w-auto dtable"> -->
                                <thead class="">
                                    <tr>
                                        <?php if (!isset($_GET["report"])) { ?>
                                            <th>Action</th>
                                        <?php } ?>
                                        <!-- <th>No.</th> -->
                                        <th>Origin</th>
                                        <th>Destination</th>
                                        <th>Nominal</th>
                                        <th>Satuan</th>
                                        <th>Trucking</th>
                                        <th>Vessel</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $usr = $this->db
                                        ->table("vendorpl")
                                        ->join("vendortruck", "vendortruck.vendortruck_id=vendorpl.vendortruck_id", "left")
                                        ->join("vessel", "vessel.vessel_id=vendorpl.vessel_id", "left")
                                        ->join("origin", "origin.origin_id=vendorpl.origin_id", "left")
                                        ->join("destination", "destination.destination_id=vendorpl.destination_id", "left")
                                        ->where("vendorpl.vendor_id", $_GET["vendor_id"])
                                        ->orderBy("origin_name", "ASC")
                                        ->orderBy("destination_name", "ASC")
                                        ->orderBy("vessel_name", "ASC")
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
                                                            isset(session()->get("halaman")['96']['act_update'])
                                                            && session()->get("halaman")['96']['act_update'] == "1"
                                                        )
                                                    ) { ?>
                                                        <form method="post" class="btn-action" style="">
                                                            <button class="btn btn-sm btn-warning " name="edit" value="OK"><span class="fa fa-edit" style="color:white;"></span> </button>
                                                            <input type="hidden" name="vendorpl_id" value="<?= $usr->vendorpl_id; ?>" />
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
                                                            isset(session()->get("halaman")['96']['act_delete'])
                                                            && session()->get("halaman")['96']['act_delete'] == "1"
                                                        )
                                                    ) { ?>
                                                        <form method="post" class="btn-action" style="">
                                                            <button class="btn btn-sm btn-danger delete" onclick="return confirm(' you want to delete?');" name="delete" value="OK"><span class="fa fa-close" style="color:white;"></span> </button>
                                                            <input type="hidden" name="vendorpl_id" value="<?= $usr->vendorpl_id; ?>" />
                                                        </form>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>
                                            <!-- <td><?= $no++; ?></td> -->
                                            <td class="text-left"><?= $usr->origin_name; ?></td>
                                            <td class="text-left"><?= $usr->destination_name; ?></td>
                                            <td class="text-left"><?= $usr->vendorpl_nominal; ?></td>
                                            <td class="text-left"><?= $usr->vendorpl_satuan; ?></td>
                                            <td class="text-left"><?= $usr->vendortruck_name; ?></td>
                                            <td class="text-left"><?= $usr->vessel_name; ?></td>
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
    var title = "Master Pricelist <?= $_GET["vendor_name"]; ?>";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>

<?php echo  $this->include("template/footer_v"); ?>