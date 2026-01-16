<?php echo $this->include("template/header_v");
$identity = $this->db->table("identity")->get()->getRow(); 
if(isset($_GET["tbl"])){$tbl=$_GET["tbl"];}else{$tbl="";}
?>
<style>
    td {
        white-space: nowrap;
    }

    .popover-body {
        color: #fff !important;
    }

    .bs-popover-top {
        background: #000;
    }

    .bs-popover-top .arrow::before {
        border-top-color: #000;
        color: #000 !important;
    }

    .text-black {
        color: #000 !important;
    }
</style>

<div class='container-fluid'>
    <div class='row'>
        <div class='col-12'>
            <div class="card">
                <div class="card-body">

                    <?php if ($message != "") { ?>
                        <div class="alert alert-info alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong><?= $message; ?></strong>
                        </div>
                    <?php } ?>


                    <form method="post" class="form-inline alert alert-info" action="">
                        <div class="form-group">
                            <select required onchange="metoden()" class="form-control" id="jobd_methode" name="jobd_methode">
                                <option value="">--Methode--</option>
                                <option value="lumpsum">Lumpsum (Koli)</option>
                                <option value="cbm">CBM</option>
                                <option value="kgs">KGS</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" style="width: 400px;" id="jobd_descgood" name="jobd_descgood" placeholder="Description">
                        </div>
                        <div class="form-group">
                            <input onkeyup="kali(); isiotom();" type="text" class="form-control" style="width: 80px;" id="jobd_qty" name="jobd_qty" placeholder="QTY">
                        </div>
                        <div class="form-group" id="kolian">
                            <input type="text" class="form-control" style="width: 80px;" id="jobd_koli" name="jobd_koli" placeholder="Koli" title="Koli" data-bs-toggle="tooltip">
                        </div>
                        <div class="form-group" id="cbman">
                            <input type="text" class="form-control" style="width: 80px;" id="jobd_cbm" name="jobd_cbm" placeholder="CBM/KGS" title="CBM/KGS" data-bs-toggle="tooltip">
                        </div>
                        <script>
                            function isiotom() {
                                let qty = $("#jobd_qty").val();
                                let metode = $("#jobd_methode").val();
                                if (metode == "lumpsum") {
                                    $("#jobd_koli").val(qty);
                                    $("#jobd_cbm").val("");
                                } else if (metode == "cbm") {
                                    $("#jobd_cbm").val(qty);
                                    $("#jobd_koli").val("");
                                } else if (metode == "kgs") {
                                    $("#jobd_cbm").val(qty);
                                    $("#jobd_koli").val("");
                                } else {
                                    $("#jobd_koli").val("");
                                    $("#jobd_cbm").val("");
                                }
                            }

                            function metoden() {
                                let metode = $("#jobd_methode").val();
                                if (metode == "lumpsum") {
                                    $("#kolian").hide();
                                    $("#cbman").show();
                                    $("#jobd_qty").attr("placeholder", "Koli");
                                } else if (metode == "cbm") {
                                    $("#kolian").show();
                                    $("#cbman").hide();
                                    $("#jobd_qty").attr("placeholder", "CBM/KGS");
                                } else if (metode == "kgs") {
                                    $("#kolian").show();
                                    $("#cbman").hide();
                                    $("#jobd_qty").attr("placeholder", "CBM/KGS");
                                } else {
                                    $("#kolian").hide();
                                    $("#cbman").hide();
                                    $("#jobd_qty").attr("placeholder", "QTY");
                                }
                            }
                            $(document).ready(function() {
                                $("#kolian").hide();
                                $("#cbman").hide();
                                metoden();
                            });
                        </script>
                        <div class="form-group">
                            <select class="form-control" id="jobd_satuan" name="jobd_satuan">
                                <option value="">--Satuan--</option>
                                <?php
                                $usr = $this->db
                                    ->table("satuan")
                                    ->orderBy("satuan_name", "ASC")
                                    ->get();
                                foreach ($usr->getResult() as $usr) { ?>
                                    <option value="<?= $usr->satuan_name; ?>"><?= $usr->satuan_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input onkeyup="kali()" type="text" class="form-control" style="width: 120px;" id="jobd_sell" name="jobd_sell" placeholder="Price">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" style="width: 120px;" id="jobd_total" name="jobd_total" placeholder="Total">
                        </div>

                        <script>
                            function kali() {
                                let qty = $("#jobd_qty").val();
                                let price = $("#jobd_sell").val();
                                let total = qty * price;
                                $("#jobd_total").val(total);
                            }
                        </script>
                        <input type="hidden" id="job_temp" name="job_temp" value="<?= $job_temp; ?>" />
                        <input type="hidden" id="jobd_id" name="jobd_id" value="" />

                        &nbsp;&nbsp;<button id="btnjobd" type="submit" name="create" value="OK" class="btn btn-primary">Submit</button>
                    </form>

                    <div class="table-responsive ">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <!-- <table id="dataTable" class="table table-condensed table-hover w-auto dtable"> -->
                            <thead class="">
                                <tr>
                                    <?php if (!isset($_GET["report"])) { ?>
                                        <th>Action</th>
                                    <?php } ?>
                                    <!-- <th>No.</th> -->
                                    <th>Methode</th>
                                    <th>Description</th>
                                    <th>Koli</th>
                                    <th>CBM/KGS</th>
                                    <th>Satuan</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $build = $this->db
                                    ->table("jobd");
                                $build->where("job_temp", $job_temp);
                                $usr = $build->get();

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
                                                    )
                                                ) { ?>
                                                    <form method="post" class="btn-action">
                                                        <button type="button" onclick="editjobd('<?= $usr->jobd_id; ?>')" class="btn btn-sm btn-warning " name="edit" value="OK">
                                                            <span class="fa fa-edit" style="color:white;"></span>
                                                        </button>
                                                        <input type="hidden" id="job_temp<?= $usr->jobd_id; ?>" name="job_temp" value="<?= $usr->job_temp; ?>" />
                                                        <input type="hidden" id="jobd_total<?= $usr->jobd_id; ?>" name="jobd_total" value="<?= $usr->jobd_total; ?>" />
                                                        <input type="hidden" id="jobd_sell<?= $usr->jobd_id; ?>" name="jobd_sell" value="<?= $usr->jobd_sell; ?>" />
                                                        <input type="hidden" id="jobd_satuan<?= $usr->jobd_id; ?>" name="jobd_satuan" value="<?= $usr->jobd_satuan; ?>" />
                                                        <input type="hidden" id="jobd_methode<?= $usr->jobd_id; ?>" name="jobd_methode" value="<?= $usr->jobd_methode; ?>" />

                                                        <input type="hidden" id="jobd_qty<?= $usr->jobd_id; ?>" name="jobd_qty" value="<?= $usr->jobd_qty; ?>" />
                                                        <input type="hidden" id="jobd_descgood<?= $usr->jobd_id; ?>" name="jobd_descgood" value="<?= $usr->jobd_descgood; ?>" />
                                                        <input type="hidden" id="jobd_id<?= $usr->jobd_id; ?>" name="jobd_id" value="<?= $usr->jobd_id; ?>" />


                                                        <input type="hidden" id="jobd_koli<?= $usr->jobd_id; ?>" name="jobd_koli" value="<?= $usr->jobd_koli; ?>" />
                                                        <input type="hidden" id="jobd_cbm<?= $usr->jobd_id; ?>" name="jobd_cbm" value="<?= $usr->jobd_cbm; ?>" />
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
                                                    )
                                                ) { ?>
                                                    <form method="post" class="btn-action" style="">
                                                        <button class="btn btn-sm btn-danger delete" onclick="return confirm(' you want to delete?');" name="delete" value="OK"><span class="fa fa-close" style="color:white;"></span> </button>
                                                        <input type="hidden" name="jobd_id" value="<?= $usr->jobd_id; ?>" />
                                                    </form>
                                                <?php } ?>
                                            </td>
                                        <?php } ?>
                                        <!-- <td><?= $no++; ?></td> -->
                                        <td><?= $usr->jobd_methode; ?></td>
                                        <td><?= $usr->jobd_descgood; ?></td>
                                        <td><?= number_format($usr->jobd_koli, 0, ",", "."); ?></td>
                                        <td><?= number_format($usr->jobd_cbm, 3, ",", "."); ?></td>
                                        <td><?= $usr->jobd_satuan; ?></td>
                                        <td><?= number_format($usr->jobd_sell, 2, ",", "."); ?></td>
                                        <td><?= number_format($usr->jobd_total, 2, ",", "."); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <script>
                            function editjobd(jobd_id) {
                                let job_temp = $("#job_temp" + jobd_id).val();
                                let jobd_total = $("#jobd_total" + jobd_id).val();
                                let jobd_sell = $("#jobd_sell" + jobd_id).val();
                                let jobd_qty = $("#jobd_qty" + jobd_id).val();
                                let jobd_satuan = $("#jobd_satuan" + jobd_id).val();
                                let jobd_methode = $("#jobd_methode" + jobd_id).val();
                                let jobd_descgood = $("#jobd_descgood" + jobd_id).val();
                                let jobdid = $("#jobd_id" + jobd_id).val();
                                let jobd_koli = $("#jobd_koli" + jobd_id).val();
                                let jobd_cbm = $("#jobd_cbm" + jobd_id).val();

                                $("#job_temp").val(job_temp);
                                $("#jobd_total").val(jobd_total);
                                $("#jobd_sell").val(jobd_sell);
                                $("#jobd_satuan").val(jobd_satuan);
                                $("#jobd_methode").val(jobd_methode);

                                $("#jobd_descgood").val(jobd_descgood);
                                $("#jobd_id").val(jobdid);

                                $("#btnjobd").attr("name", "change");
                                $("#jobd_qty").val(jobd_qty);

                                metoden();

                                if (jobd_methode == "lumpsum") {
                                    jobd_koli = jobd_qty;
                                } else if (jobd_methode == "cbm" || jobd_methode == "kgs") {
                                    jobd_cbm = jobd_qty;
                                }
                                $("#jobd_koli").val(jobd_koli);
                                $("#jobd_cbm").val(jobd_cbm);





                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    <?php if(isset($_GET["t"])&&$_GET["t"]=="jc"){
        $urin = base_url($_GET["url"]);
    }else{
        $urin = base_url($_GET["url"] . "?t=" . $_GET["t"] . "&temp=" . $job_temp."&tbl=".$tbl);
    }
    ?>
    let pagetitle = '&nbsp;&nbsp;<a href="<?= $urin; ?>" class="btn btn-warning"><i class="fa fa-undo"></i> Back to Job</a>';
    $(document).ready(function() {
        $("#page-title").append(pagetitle);
    });

    $('.select').select2();
    var title = "<?= $title; ?>";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>

<?php echo  $this->include("template/footer_v"); ?>