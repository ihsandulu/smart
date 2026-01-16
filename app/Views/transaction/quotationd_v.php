<?php echo $this->include("template/header_v");
$identity = $this->db->table("identity")->get()->getRow(); ?>
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
                            <input type="text" class="form-control" style="width: 200px;" id="quotationd_description" name="quotationd_description" placeholder="Description">
                        </div>
                        <div class="form-group">
                            <input onkeyup="kali()" type="text" class="form-control" style="width: 80px;" id="quotationd_qty" name="quotationd_qty" placeholder="QTY">
                        </div>
                        <div class="form-group">
                            <select required class="form-control" id="quotationd_satuan" name="quotationd_satuan">
                                <option value="">Pilih Satuan</option>
                                <?php $satuan = $this->db->table("satuan")
                                    ->orderBy("satuan_name", "ASC")
                                    ->get();
                                foreach ($satuan->getResult() as $satuan) {
                                ?>
                                    <option value="<?= $satuan->satuan_name; ?>"><?= $satuan->satuan_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input onkeyup="kali()" type="text" class="form-control" style="width: 120px;" id="quotationd_price" name="quotationd_price" placeholder="Price">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" style="width: 120px;" id="quotationd_total" name="quotationd_total" placeholder="Total">
                        </div>
                        <script>
                            function pilihdano() {
                                let dano = selected.data("dano");
                                let des = selected.data("des");
                                let qty = selected.data("qty");
                                let satuan = selected.data("satuan");
                                let price = selected.data("price");
                                $("#quotationd_description").val(des);
                                $("#quotationd_qty").val(qty);
                                $("#quotationd_satuan").val(satuan);
                                $("#quotationd_price").val(price);
                                kali();
                            }

                            function kali() {
                                let qty = $("#quotationd_qty").val();
                                let price = $("#quotationd_price").val();
                                let total = qty * price;
                                $("#quotationd_total").val(total);
                            }
                        </script>
                        <input type="hidden" id="quotation_id" name="quotation_id" value="<?= $_GET["quotation_id"]; ?>" />
                        <input type="hidden" id="quotationd_id" name="quotationd_id" value="" />
                        

                        &nbsp;&nbsp;<button id="btnquotationd" type="submit" name="create" value="OK" class="btn btn-primary">Submit</button>
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
                                    <th>Description</th>
                                    <th>QTY</th>
                                    <th>Satuan</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $build = $this->db
                                    ->table("quotationd");
                                if (isset($_GET["quotation_id"])) {
                                    $build->where("quotation_id", $_GET["quotation_id"]);
                                }
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
                                                        isset(session()->get("halaman")['113']['act_update'])
                                                        && session()->get("halaman")['113']['act_update'] == "1"
                                                    )
                                                ) { ?>
                                                    <form method="post" class="btn-action" >
                                                        <button type="button" onclick="editquotationd('<?= $usr->quotationd_id; ?>')" class="btn btn-sm btn-warning " name="edit" value="OK">
                                                            <span class="fa fa-edit" style="color:white;"></span>
                                                        </button>
                                                       
                                                        <input type="hidden" id="quotation_id<?= $usr->quotationd_id; ?>" name="quotation_id" value="<?= $usr->quotation_id; ?>" />
                                                        <input type="hidden" id="quotationd_total<?= $usr->quotationd_id; ?>" name="quotationd_total" value="<?= $usr->quotationd_total; ?>" />
                                                        <input type="hidden" id="quotationd_price<?= $usr->quotationd_id; ?>" name="quotationd_price" value="<?= $usr->quotationd_price; ?>" />
                                                        <input type="hidden" id="quotationd_satuan<?= $usr->quotationd_id; ?>" name="quotationd_satuan" value="<?= $usr->quotationd_satuan; ?>" />
                                                        <input type="hidden" id="quotationd_qty<?= $usr->quotationd_id; ?>" name="quotationd_qty" value="<?= $usr->quotationd_qty; ?>" />
                                                        <input type="hidden" id="quotationd_description<?= $usr->quotationd_id; ?>" name="quotationd_description" value="<?= $usr->quotationd_description; ?>" />
                                                        
                                                        <input type="hidden" id="quotationd_id<?= $usr->quotationd_id; ?>" name="quotationd_id" value="<?= $usr->quotationd_id; ?>" />
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
                                                        isset(session()->get("halaman")['113']['act_delete'])
                                                        && session()->get("halaman")['113']['act_delete'] == "1"
                                                    )
                                                ) { ?>
                                                    <form method="post" class="btn-action" style="">
                                                        <button class="btn btn-sm btn-danger delete" onclick="return confirm(' you want to delete?');" name="delete" value="OK"><span class="fa fa-close" style="color:white;"></span> </button>
                                                        <input type="hidden" name="quotationd_id" value="<?= $usr->quotationd_id; ?>" />
                                                        <input type="hidden" name="quotation_id" value="<?= $quotation_id; ?>" />
                                                    </form>
                                                <?php } ?>
                                            </td>
                                        <?php } ?>
                                        <!-- <td><?= $no++; ?></td> -->
                                        <td><?= $usr->quotationd_description; ?></td>
                                        <td><?= number_format($usr->quotationd_qty, 0, ",", "."); ?></td>
                                        <td><?= $usr->quotationd_satuan; ?></td>
                                        <td><?= number_format($usr->quotationd_price, 0, ",", "."); ?></td>
                                        <td><?= number_format($usr->quotationd_total, 0, ",", "."); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <script>
                            function editquotationd(quotationd_id) {
                                let quotation_id = $("#quotation_id" + quotationd_id).val();
                                let quotationd_total = $("#quotationd_total" + quotationd_id).val();
                                let quotationd_price = $("#quotationd_price" + quotationd_id).val();
                                let quotationd_satuan = $("#quotationd_satuan" + quotationd_id).val();
                                let quotationd_qty = $("#quotationd_qty" + quotationd_id).val();
                                let quotationd_description = $("#quotationd_description" + quotationd_id).val();
                                let quotationdid = $("#quotationd_id" + quotationd_id).val();

                                $("#quotation_id").val(quotation_id);
                                $("#quotationd_total").val(quotationd_total);
                                $("#quotationd_price").val(quotationd_price);
                                $("#quotationd_satuan").val(quotationd_satuan);
                                $("#quotationd_qty").val(quotationd_qty);
                                $("#quotationd_description").val(quotationd_description);
                                $("#quotationd_id").val(quotationdid);

                                $("#btnquotationd").attr("name", "change");
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let pagetitle = '&nbsp;&nbsp;<a href="<?= base_url("quotation"); ?>" class="btn btn-warning"><i class="fa fa-undo"></i> Back to Quotation</a>';
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