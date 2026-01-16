<?php echo $this->include("template/header_v"); ?>
<style>
    td {
        white-space: nowrap;
    }
</style>

<div class='container-fluid'>
    <div class='row'>
        <div class='col-12'>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <?php if (!isset($_GET['user_id']) && !isset($_POST['new']) && !isset($_POST['edit'])) {
                            $coltitle = "col-md-10";
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
                                    isset(session()->get("halaman")['113']['act_create'])
                                    && session()->get("halaman")['113']['act_create'] == "1"
                                )
                            ) { ?>
                                <form method="post" class="col-md-2">
                                    <h1 class="page-header col-md-12">
                                        <button name="new" class="btn btn-info btn-block btn-sm" value="OK" style="">New</button>
                                        <input type="hidden" name="quotation_id" />
                                    </h1>
                                </form>
                            <?php } ?>
                        <?php } ?>
                    </div>

                    <?php if (isset($_POST['new']) || isset($_POST['edit'])) { ?>
                        <div class="">
                            <?php if (isset($_POST['edit'])) {
                                $namabutton = 'name="change"';
                                $judul = "Update quotation";
                            } else {
                                $namabutton = 'name="create"';
                                $judul = "Tambah quotation";
                            } ?>
                            <div class="lead">
                                <h3><?= $judul; ?></h3>
                            </div>
                            <form class="form-horizontal row" method="post" enctype="multipart/form-data">



                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="quotation_date">DATE:</label>
                                    <div class="col-sm-12">
                                        <input required type="date" autofocus class="form-control" id="quotation_date" name="quotation_date" placeholder="" value="<?= ($quotation_date == "") ? date("Y-m-d") : $quotation_date; ?>">
                                    </div>
                                </div>

                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="quotation_dog">Description of Goods:</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="quotation_dog" name="quotation_dog" placeholder="" value="<?= $quotation_dog; ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="quotation_cusname">Customer:</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="quotation_cusname" name="quotation_cusname" placeholder="" value="<?= $quotation_cusname; ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="quotation_singkatan">Nama Singkatan Customer:</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="quotation_singkatan" name="quotation_singkatan" placeholder="" value="<?= $quotation_singkatan; ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="quotation__attn">Attn:</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="quotation__attn" name="quotation__attn" placeholder="" value="<?= $quotation__attn; ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="quotation_address">Address:</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="quotation_address" name="quotation_address" placeholder="" value="<?= $quotation_address; ?>">
                                    </div>
                                </div>

                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="origin_id">Dari:</label>
                                    <div class="col-sm-12">
                                        <select name="origin_id" value="<?= $origin_id; ?>" class="form-control select">
                                            <option value="" <?= ($origin_id == "") ? "selected" : ""; ?>>Select From</option>
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
                                    <label class="control-label col-sm-12" for="destination_id">Ke:</label>
                                    <div class="col-sm-12">
                                        <select name="destination_id" value="<?= $destination_id; ?>" class="form-control select">
                                            <option value="" <?= ($destination_id == "") ? "selected" : ""; ?>>Select To</option>
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
                                    <label class="control-label col-sm-12" for="quotation_greeting">Greeting:</label>
                                    <div class="col-sm-12"><?php if ($quotation_greeting == "") {
                                                                $quotation_greeting = "With refer to above subject, here with we would like to send you our quotation for your shipment with detailsas follow :";
                                                            }
                                                            ?>
                                        <textarea name="quotation_greeting" id="quotation_greeting"><?= $quotation_greeting; ?></textarea>
                                        <script>
                                            CKEDITOR.replace('quotation_greeting');
                                        </script>
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="quotation_termcondition">Term Condition:</label>
                                    <div class="col-sm-12">
                                        <?php if ($quotation_termcondition == "") {
                                            $quotation_termcondition = "Term Condition :<br/>- Port to Door ( Free On Truck )<br/>- Exclude Insurance<br/>- Exclude PPN / PPH";
                                        }
                                        ?>
                                        <textarea name="quotation_termcondition" id="quotation_termcondition"><?= $quotation_termcondition; ?></textarea>
                                        <script>
                                            CKEDITOR.replace('quotation_termcondition');
                                        </script>
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="quotation_termpayment">Term Payment:</label>
                                    <?php if ($quotation_termpayment == "") {
                                        $quotation_termpayment = "Term Of Payment :<br/>- Dp 50% - PO / SPK di terima<br/>- Pelunasan 50%<br/>- Setelah BAST di Tandatangani oleh penerima di Lokasi tujuan";
                                    }
                                    ?>
                                    <div class="col-sm-12">
                                        <textarea name="quotation_termpayment" id="quotation_termpayment"><?= $quotation_termpayment; ?></textarea>
                                        <script>
                                            CKEDITOR.replace('quotation_termpayment');
                                        </script>
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label col-sm-12" for="quotation_closing">Closing:</label>
                                    <?php if ($quotation_closing == "") {
                                        $quotation_closing = "If you need more information about our quotation and our services, please do not hesitate to contact us.<br/>Thank you for your kind attention and opportunity has you gave to us";
                                    }
                                    ?>
                                    <div class="col-sm-12">
                                        <textarea name="quotation_closing" id="quotation_closing"><?= $quotation_closing; ?></textarea>
                                        <script>
                                            CKEDITOR.replace('quotation_closing');
                                        </script>
                                    </div>
                                </div>

                                <input type="hidden" name="quotation_id" value="<?= $quotation_id; ?>" />
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-sm-offset-2 col-sm-12">
                                        <button type="submit" id="submit" class="btn btn-primary col-md-5" <?= $namabutton; ?> value="OK">Submit</button>
                                        <a class="btn btn-warning col-md-offset-1 col-md-5" href="<?= base_url("quotation"); ?>">Back</a>
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
                                $dari = date("Y-m-d", strtotime("-5 days"));
                                $ke = date("Y-m-d");
                                $quotation_type = "";
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
                                        <th>Date</th>
                                        <th>Quotation No.</th>
                                        <th>Customer</th>
                                        <th>Address</th>
                                        <th>Marketer</th>
                                        <th>Description of Goods</th>
                                        <th>Origin</th>
                                        <th>Destination</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $build = $this->db
                                        ->table("quotation")
                                        ->join("origin", "origin.origin_id = quotation.origin_id", "left")
                                        ->join("destination", "destination.destination_id = quotation.destination_id", "left")
                                        ->join("user", "user.user_id = quotation.user_id", "left");
                                    if ($this->session->get("position_name") == "SALES") {
                                        $build->where("user_id", $this->session->get("user_id"));
                                    }
                                    $build->where("quotation_date BETWEEN '" . $dari . "' AND '" . $ke . "'");
                                    $usr = $build->orderBy("quotation.quotation_id", "DESC")
                                        ->get();

                                    //echo $this->db->getLastquery();
                                    $no = 1;
                                    $debettype = array("pettycash" => "Petty Cash", "bigcash" => "Big Cash");
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
                                                            isset(session()->get("halaman")['113']['act_delete'])
                                                            && session()->get("halaman")['113']['act_delete'] == "1"
                                                        )
                                                    ) { ?>
                                                        <form method="post" class="btn-action" style="">
                                                            <button title="Delete" data-bs-toggle="tooltip" class="btn btn-sm btn-danger delete" onclick="return confirm(' you want to delete?');" name="delete" value="OK"><span class="fa fa-close" style="color:white;"></span> </button>
                                                            <input type="hidden" name="quotation_id" value="<?= $usr->quotation_id; ?>" />
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
                                                            isset(session()->get("halaman")['113']['act_update'])
                                                            && session()->get("halaman")['113']['act_update'] == "1"
                                                        )
                                                    ) { ?>
                                                        <form method="post" class="btn-action" style="">
                                                            <button title="Edit" data-bs-toggle="tooltip" class="btn btn-sm btn-warning " name="edit" value="OK"><span class="fa fa-edit" style="color:white;"></span> </button>
                                                            <input type="hidden" name="quotation_id" value="<?= $usr->quotation_id; ?>" />
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
                                                            isset(session()->get("halaman")['113']['act_create'])
                                                            && session()->get("halaman")['113']['act_create'] == "1"
                                                        )
                                                    ) { ?>
                                                        <form method="get" target="_self" action="<?= base_url("quotationd"); ?>" class="btn-action" style="">
                                                            <button title="Detail" data-bs-toggle="tooltip" class="btn btn-sm btn-primary" name="detail" value="OK"><span class="fa fa-cubes" style="color:white;"></span> </button>
                                                            <input type="hidden" name="quotation_id" value="<?= $usr->quotation_id; ?>" />
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
                                                            isset(session()->get("halaman")['113']['act_read'])
                                                            && session()->get("halaman")['113']['act_read'] == "1"
                                                        )
                                                    ) { ?>
                                                        <form method="get" target="_blank" action="<?= base_url("quotationprint"); ?>" class="btn-action" style="">
                                                            <button title="Print Quotation" data-bs-toggle="tooltip" class="btn btn-sm btn-success" name="print" value="OK"><span class="fa fa-print" style="color:white;"></span> </button>
                                                            <input type="hidden" name="quotation_id" value="<?= $usr->quotation_id; ?>" />
                                                        </form>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>
                                            <!-- <td><?= $no++; ?></td> -->
                                            <td><?= $usr->quotation_date; ?></td>
                                            <td><?= $usr->quotation_no; ?></td>
                                            <td><?= $usr->quotation_cusname; ?> - <?= $usr->quotation__attn; ?></td>
                                            <td><?= $usr->quotation_address; ?></td>
                                            <td><?= $usr->user_nama; ?></td>
                                            <td><?= $usr->quotation_dog; ?></td>
                                            <td><?= $usr->origin_name; ?></td>
                                            <td><?= $usr->destination_name; ?></td>
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