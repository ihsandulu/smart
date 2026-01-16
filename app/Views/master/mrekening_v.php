<?php echo $this->include("template/header_v"); ?>

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
                                    isset(session()->get("halaman")['109']['act_create'])
                                    && session()->get("halaman")['109']['act_create'] == "1"
                                )
                            ) { ?>
                                <form method="post" class="col-md-2">
                                    <h1 class="page-header col-md-12">
                                        <button name="new" class="btn btn-info btn-block btn-lg" value="OK" style="">New</button>
                                        <input type="hidden" name="rekening_id" />
                                    </h1>
                                </form>
                            <?php } ?>
                        <?php } ?>
                    </div>

                    <?php if (isset($_POST['new']) || isset($_POST['edit'])) { ?>
                        <div class="">
                            <?php if (isset($_POST['edit'])) {
                                $namabutton = 'name="change"';
                                $judul = "Update Rekening";
                            } else {
                                $namabutton = 'name="create"';
                                $judul = "Tambah Rekening";
                            } ?>
                            <div class="lead">
                                <h3><?= $judul; ?></h3>
                            </div>
                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="rekening_type">Type:</label>
                                    <div class="col-sm-10">
                                        <select onchange="pilihtipe();ppn();" required autofocus class="form-control" id="rekening_type" name="rekening_type">
                                            <option value="" <?= ($rekening_type == "") ? "selected" : ""; ?>>Pilih Tipe</option>
                                            <option value="NKL" <?= ($rekening_type == "NKL") ? "selected" : ""; ?>>NKL</option>
                                            <option value="Customer" <?= ($rekening_type == "Customer") ? "selected" : ""; ?>>Customer</option>
                                            <option value="Vendor" <?= ($rekening_type == "Vendor") ? "selected" : ""; ?>>Vendor</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" id="ppnn">
                                    <label class="control-label col-sm-2" for="rekening_ppn">PPN/NON:</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="rekening_ppn" name="rekening_ppn">
                                            <option value="" <?= ($rekening_ppn == "") ? "selected" : ""; ?>>Pilih PPN/NON</option>
                                            <option value="PPN" <?= ($rekening_ppn == "PPN") ? "selected" : ""; ?>>PPN</option>
                                            <option value="NON" <?= ($rekening_ppn == "NON") ? "selected" : ""; ?>>NON</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" id="vendor">
                                    <label class="control-label col-sm-12" for="vendor_id">Vendor:</label>
                                    <div class="col-sm-12">
                                        <select id="vendor_id" name="vendor_id" value="<?= $vendor_id; ?>" class="form-control select">
                                            <option value="" <?= ($vendor_id == "") ? "selected" : ""; ?>>Select Vendor</option>
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
                                <div class="form-group" id="customer">
                                    <label class="control-label col-sm-12" for="customer_id">Customer:</label>
                                    <div class="col-sm-12">
                                        <select id="customer_id" name="customer_id" value="<?= $customer_id; ?>" class="form-control select">
                                            <option value="" <?= ($customer_id == "") ? "selected" : ""; ?>>Select customer</option>
                                            <?php
                                            $usr = $this->db
                                                ->table("customer")
                                                ->orderBy("customer_name", "ASC")
                                                ->get();
                                            foreach ($usr->getResult() as $usr) { ?>
                                                <option value="<?= $usr->customer_id; ?>" <?= ($customer_id == $usr->customer_id) ? "selected" : ""; ?>><?= $usr->customer_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="bank_id">Bank:</label>
                                    <div class="col-sm-10">
                                        <select required autofocus class="form-control select" id="bank_id" name="bank_id">
                                            <option value="">Pilih Bank</option>
                                            <?php
                                            $bank = $this->db->table("bank")->orderBy("bank_name", "ASC")->get();
                                            foreach ($bank->getResult() as $row) { ?>
                                                <option <?= ($row->bank_id == $bank_id) ? "selected" : ""; ?> value="<?= $row->bank_id; ?>"><?= $row->bank_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="rekening_an">Atas Nama:</label>
                                    <div class="col-sm-10">
                                        <input required type="text" class="form-control" id="rekening_an" name="rekening_an" placeholder="" value="<?= $rekening_an; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="rekening_no">Nomor Rekening:</label>
                                    <div class="col-sm-10">
                                        <input required type="text" class="form-control" id="rekening_no" name="rekening_no" placeholder="" value="<?= $rekening_no; ?>">
                                    </div>
                                </div>

                                <input type="hidden" name="rekening_id" value="<?= $rekening_id; ?>" />
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" id="submit" class="btn btn-primary col-md-5" <?= $namabutton; ?> value="OK">Submit</button>
                                        <a class="btn btn-warning col-md-offset-1 col-md-5" href="<?= base_url("mrekening"); ?>">Back</a>
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
                                        <th>Type</th>
                                        <th>Nama Customer/Vendor/NKL</th>
                                        <th>Bank</th>
                                        <th>Atas Nama</th>
                                        <th>Nomor Rekening</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $usr = $this->db
                                        ->table("rekening")
                                        ->join("bank", "bank.bank_id=rekening.bank_id", "left")
                                        ->join("customer", "customer.customer_id=rekening.customer_id", "left")
                                        ->join("vendor", "vendor.vendor_id=rekening.vendor_id", "left")
                                        ->orderBy("rekening_type", "ASC")
                                        ->get();
                                    // echo $this->db->getLastquery();
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
                                                            isset(session()->get("halaman")['109']['act_update'])
                                                            && session()->get("halaman")['109']['act_update'] == "1"
                                                        )
                                                    ) { ?>
                                                        <form method="post" class="btn-action" style="">
                                                            <button class="btn btn-sm btn-warning " name="edit" value="OK"><span class="fa fa-edit" style="color:white;"></span> </button>
                                                            <input type="hidden" name="rekening_id" value="<?= $usr->rekening_id; ?>" />
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
                                                            isset(session()->get("halaman")['109']['act_delete'])
                                                            && session()->get("halaman")['109']['act_delete'] == "1"
                                                        )
                                                    ) { ?>
                                                        <form method="post" class="btn-action" style="">
                                                            <button class="btn btn-sm btn-danger delete" onclick="return confirm(' you want to delete?');" name="delete" value="OK"><span class="fa fa-close" style="color:white;"></span> </button>
                                                            <input type="hidden" name="rekening_id" value="<?= $usr->rekening_id; ?>" />
                                                        </form>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>
                                            <!-- <td><?= $no++; ?></td> -->
                                            <td class="text-left"><?= $usr->rekening_type; ?></td>
                                            <td class="text-left">
                                                <?php
                                                $nama = "";
                                                if ($usr->rekening_type == "NKL") {
                                                    $nama = "NKL";
                                                }
                                                if ($usr->rekening_type == "Customer") {
                                                    $nama = $usr->customer_name;
                                                }
                                                if ($usr->rekening_type == "Vendor") {
                                                    $nama = $usr->vendor_name;
                                                }
                                                echo $nama;
                                                ?>
                                            </td>
                                            <td class="text-left"><?= $usr->bank_name; ?></td>
                                            <td class="text-left"><?= $usr->rekening_an; ?></td>
                                            <td class="text-left"><?= $usr->rekening_no; ?></td>
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
    function pilihtipe() {
        var rekening_type = $("#rekening_type").val();
        if (rekening_type == "Customer") {
            $('#customer').show();
            $('#vendor').hide();
        } else if (rekening_type == "Vendor") {
            $('#vendor').show();
            $('#customer').hide();
        } else {
            $('#customer').hide();
            $('#vendor').hide();
        }
    }
    function ppn() {
        var rekening_type = $("#rekening_type").val();
        if (rekening_type == "NKL") {
            $('#ppnn').show();
        } else {
            $('#ppnn').hide();
        }
    }
    $(document).ready(function() {
        $('#customer').hide();
        $('#vendor').hide();
        pilihtipe();
        ppn();
    });
    $('.select').select2();
    var title = "Master Rekening";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>

<?php echo  $this->include("template/footer_v"); ?>