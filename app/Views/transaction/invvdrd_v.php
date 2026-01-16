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

                    <form method="post" class="form-inline alert alert-warning" action="">
                        <div class="form-group">
                            <input type="date"
                                class="form-control"
                                style="width:200px;"
                                id="invvdr_date"
                                name="invvdr_date"
                                placeholder="Tanggal Invoice"
                                data-bs-toggle="popover"
                                data-bs-content="Pilih tanggal Invoice"
                                data-bs-trigger="manual"
                                data-bs-placement="top"
                                value="<?= $invvdr_date; ?>">
                        </div>
                        <script>
                            $(function() {
                                $('#invvdr_date')
                                    .popover({
                                        content: 'Pilih tanggal Invoice',
                                        trigger: 'manual',
                                        placement: 'top',
                                        template: '<div class="popover bs-popover-top" role="tooltip"><div class="arrow"></div><div class="popover-body"></div></div>'
                                    })
                                    .popover('show');
                            });
                        </script>
                        <div class="form-group">
                            <input type="date"
                                class="form-control"
                                style="width:200px;"
                                id="invvdr_duedate"
                                name="invvdr_duedate"
                                placeholder="Due Date"
                                data-bs-toggle="popover"
                                data-bs-content="Pilih tanggal jatuh tempo"
                                data-bs-trigger="manual"
                                data-bs-placement="top"
                                value="<?= $invvdr_duedate; ?>">
                        </div>
                        <script>
                            $(function() {
                                $('#invvdr_duedate')
                                    .popover({
                                        content: 'Pilih tanggal jatuh tempo',
                                        trigger: 'manual',
                                        placement: 'top',
                                        template: '<div class="popover bs-popover-top" role="tooltip"><div class="arrow"></div><div class="popover-body"></div></div>'
                                    })
                                    .popover('show');
                            });
                        </script>
                        <div class="form-group">
                            <select required class="form-control" id="vendor_id" name="vendor_id">
                                <option value="" data-singkatan="" <?= ($vendor_id == "") ? "selected" : ""; ?>>Vendor</option>
                                <?php $vendor = $this->db->table("vendor")
                                    ->orderBy("vendor_name", "ASC")
                                    ->get();
                                foreach ($vendor->getResult() as $vendor) {
                                ?>
                                    <option value="<?= $vendor->vendor_id; ?>" <?= ($vendor_id == $vendor->vendor_id) ? "selected" : ""; ?>><?= $vendor->vendor_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" style="width: 100px;" id="invvdr_no" name="invvdr_no" placeholder="Invoice No." value="<?= $invvdr_no; ?>">
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="vessel_id" name="vessel_id">
                                <option value="" data-singkatan="" <?= ($vessel_id == "") ? "selected" : ""; ?>>Vessel</option>
                                <?php $vessel = $this->db->table("vessel")
                                    ->orderBy("vessel_name", "ASC")
                                    ->get();
                                foreach ($vessel->getResult() as $vessel) {
                                ?>
                                    <option value="<?= $vessel->vessel_id; ?>" <?= ($vessel_id == $vessel->vessel_id) ? "selected" : ""; ?>><?= $vessel->vessel_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="origin_id" name="origin_id">
                                <option value="" data-singkatan="" <?= ($origin_id == "") ? "selected" : ""; ?>>Origin</option>
                                <?php $origin = $this->db->table("origin")
                                    ->orderBy("origin_name", "ASC")
                                    ->get();
                                foreach ($origin->getResult() as $origin) {
                                ?>
                                    <option value="<?= $origin->origin_id; ?>" <?= ($origin_id == $origin->origin_id) ? "selected" : ""; ?>><?= $origin->origin_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="destination_id" name="destination_id">
                                <option value="" data-singkatan="" <?= ($destination_id == "") ? "selected" : ""; ?>>Destination</option>
                                <?php $destination = $this->db->table("destination")
                                    ->orderBy("destination_name", "ASC")
                                    ->get();
                                foreach ($destination->getResult() as $destination) {
                                ?>
                                    <option value="<?= $destination->destination_id; ?>" <?= ($destination_id == $destination->destination_id) ? "selected" : ""; ?>><?= $destination->destination_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input onkeyup="dtagih()" type="text" class="form-control" style="width: 100px;" id="invvdr_tagihan" name="invvdr_tagihan" placeholder="Tagihan" value="<?= $invvdr_tagihan; ?>">
                        </div>
                        <div class="form-group">
                            <input onkeyup="dtagih()" type="text" class="form-control" style="width: 100px;" id="invvdr_discount" name="invvdr_discount" placeholder="Discount" value="<?= $invvdr_discount; ?>">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" style="width: 100px;" id="invvdr_dtagihan" name="invvdr_dtagihan" placeholder="Stl Diskon" value="<?= $invvdr_dtagihan; ?>">
                        </div>
                        <script>
                            function dtagih() {
                                let tagihan = $("#invvdr_tagihan").val();
                                let discount = $("#invvdr_discount").val();
                                let dtagihan = tagihan - discount;
                                $("#invvdr_dtagihan").val(dtagihan);
                            }
                        </script>
                        <div class="form-group">
                            <label class="text-black">&nbsp;1.1%&nbsp;</label>
                            <input <?= ($invvdr_ppn1k1 == 1) ? "checked" : ""; ?> type="checkbox" class="" style="" id="invvdr_ppn1k1" name="invvdr_ppn1k1" value="1">
                        </div>
                        <div class="form-group">
                            <label class="text-black">&nbsp;11%&nbsp;</label>
                            <input <?= ($invvdr_ppn11 == 1) ? "checked" : ""; ?> type="checkbox" class="" style="" id="invvdr_ppn11" name="invvdr_ppn11" value="1">
                        </div>
                        <div class="form-group">
                            <label class="text-black">&nbsp;12%&nbsp;</label>
                            <input <?= ($invvdr_ppn12 == 1) ? "checked" : ""; ?> type="checkbox" class="" style="" id="invvdr_ppn12" name="invvdr_ppn12" value="1">
                        </div>
                        <div class="form-group">
                            <label class="text-black">&nbsp;PPH&nbsp;</label>
                            <input <?= ($invvdr_pph == 1) ? "checked" : ""; ?> type="checkbox" class="" style="" id="invvdr_pph" name="invvdr_pph" value="1">
                        </div>

                        <input type="hidden" id="invvdr_id" name="invvdr_id" value="<?= $invvdr_id; ?>" />
                        <input type="hidden" id="invvdr_temp" name="invvdr_temp" value="<?= $invvdr_temp; ?>" />

                        <?php
                        if (isset($_GET["editinvvdr"])) {
                            $namebtninvvdr = 'name="changeinvvdr"';
                        } else {
                            $namebtninvvdr = 'name="createinvvdr"';
                        }
                        ?>
                        &nbsp;&nbsp;<button type="submit" <?= $namebtninvvdr; ?> value="OK" class="btn btn-primary">Submit</button>
                    </form>
                    <form id="myForm" method="post" class="form-inline alert alert-info" action="">
                        <div class="form-group">
                            <select onchange="pilihdano()" class="form-control select" id="job_id" name="job_id">
                                <option value="">Pilih Da Number</option>
                                <?php $job = $this->db->table("job")
                                    // ->where("invvdr_no", "")
                                    // ->orWhere("invvdr_no", $invvdr_no)
                                    ->orderBy("job_dano", "ASC")
                                    ->get();
                                foreach ($job->getResult() as $job) {
                                ?>
                                    <option value="<?= $job->job_id; ?>" data-des="<?= $job->job_descgood; ?>" data-dano="<?= $job->job_dano; ?>" data-qty="<?= $job->job_qty; ?>" data-satuan="<?= $job->job_satuan; ?>" data-price="<?= $job->job_sell; ?>"><?= $job->job_dano; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" style="width: 200px;" id="invvdrd_description" name="invvdrd_description" placeholder="Description">
                        </div>
                        <div class="form-group">
                            <input onkeyup="kali()" type="text" class="form-control" style="width: 80px;" id="invvdrd_qty" name="invvdrd_qty" placeholder="QTY">
                        </div>
                        <div class="form-group">
                            <select required class="form-control" id="invvdrd_satuan" name="invvdrd_satuan">
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
                            <input onkeyup="kali()" type="text" class="form-control" style="width: 80px;" id="invvdrd_price" name="invvdrd_price" placeholder="Price">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" style="width: 80px;" id="invvdrd_total" name="invvdrd_total" placeholder="Total">
                        </div>

                        <script>
                            function pilihdano() {
                                let selected = $("#job_id option:selected");
                                let dano = selected.data("dano");
                                let des = selected.data("des");
                                let qty = selected.data("qty");
                                let satuan = selected.data("satuan");
                                $("#job_dano").val(dano);
                                $("#invvdrd_description").val(des);
                                $("#invvdrd_qty").val(qty);
                                $("#invvdrd_satuan").val(satuan);
                                kali();
                            }

                            function kali() {
                                let qty = $("#invvdrd_qty").val();
                                let price = $("#invvdrd_price").val();
                                let total = qty * price;
                                $("#invvdrd_total").val(total);
                            }
                        </script>
                        <input type="hidden" id="job_dano" name="job_dano" value="" />
                        <input type="hidden" id="invvdr_temp" name="invvdr_temp" value="<?= $invvdr_temp; ?>" />
                        <input type="hidden" id="invvdr_id" name="invvdr_id" value="<?= $invvdr_id; ?>" />
                        <input type="hidden" id="invvdrd_id" name="invvdrd_id" value="" />
                        <?php if (isset($_GET["editinvvdr"])) { ?>
                            <input type="hidden" id="invvdrd_date" name="invvdrd_date" value="<?= $invvdr_date; ?>" />
                        <?php } ?>

                        &nbsp;&nbsp;<button id="btninvvdrd" type="submit" name="create" value="OK" class="btn btn-primary">Submit</button>
                        &nbsp;&nbsp;<button type="button" class="btn btn-warning" onclick="bersih()">Clear</button>
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
                                    <th>DA Number</th>
                                    <th>Description</th>
                                    <th>QTY</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $build = $this->db
                                    ->table("invvdrd");
                                if (isset($_GET["invvdr_temp"])) {
                                    $build->where("invvdr_temp", $_GET["invvdr_temp"]);
                                }
                                $usr = $build->get();

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
                                                        isset(session()->get("halaman")['122']['act_update'])
                                                        && session()->get("halaman")['122']['act_update'] == "1"
                                                    )
                                                ) { ?>
                                                    <form method="post" class="btn-action">
                                                        <button type="button" onclick="editinvvdrd('<?= $usr->invvdrd_id; ?>')" class="btn btn-sm btn-warning " name="edit" value="OK">
                                                            <span class="fa fa-edit" style="color:white;"></span>
                                                        </button>
                                                        <input type="hidden" id="job_dano<?= $usr->invvdrd_id; ?>" name="job_dano" value="<?= $usr->job_dano; ?>" />
                                                        <input type="hidden" id="invvdr_temp<?= $usr->invvdrd_id; ?>" name="invvdr_temp" value="<?= $usr->invvdr_temp; ?>" />
                                                        <input type="hidden" id="invvdr_id<?= $usr->invvdrd_id; ?>" name="invvdr_id" value="<?= $usr->invvdr_id; ?>" />

                                                        <input type="hidden" id="invvdrd_satuan<?= $usr->invvdrd_id; ?>" name="invvdrd_satuan" value="<?= $usr->invvdrd_satuan; ?>" />
                                                        <input type="hidden" id="invvdrd_qty<?= $usr->invvdrd_id; ?>" name="invvdrd_qty" value="<?= $usr->invvdrd_qty; ?>" />
                                                        <input type="hidden" id="invvdrd_description<?= $usr->invvdrd_id; ?>" name="invvdrd_description" value="<?= $usr->invvdrd_description; ?>" />
                                                        <input type="hidden" id="job_id<?= $usr->invvdrd_id; ?>" name="job_id" value="<?= $usr->job_id; ?>" />
                                                        <input type="hidden" id="invvdrd_id<?= $usr->invvdrd_id; ?>" name="invvdrd_id" value="<?= $usr->invvdrd_id; ?>" />
                                                        <input type="hidden" id="invvdrd_price<?= $usr->invvdrd_id; ?>" name="invvdrd_price" value="<?= $usr->invvdrd_price; ?>" />
                                                        <input type="hidden" id="invvdrd_total<?= $usr->invvdrd_id; ?>" name="invvdrd_total" value="<?= $usr->invvdrd_total; ?>" />
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
                                                        isset(session()->get("halaman")['122']['act_delete'])
                                                        && session()->get("halaman")['122']['act_delete'] == "1"
                                                    )
                                                ) { ?>
                                                    <form method="post" class="btn-action" style="">
                                                        <button class="btn btn-sm btn-danger delete" onclick="return confirm(' you want to delete?');" name="delete" value="OK"><span class="fa fa-close" style="color:white;"></span> </button>
                                                        <input type="hidden" name="invvdrd_id" value="<?= $usr->invvdrd_id; ?>" />
                                                        <input type="hidden" name="invvdr_temp" value="<?= $invvdr_temp; ?>" />
                                                        <input type="hidden" name="job_id" value="<?= $usr->job_id; ?>" />
                                                    </form>
                                                <?php } ?>
                                            </td>
                                        <?php } ?>
                                        <!-- <td><?= $no++; ?></td> -->
                                        <td><?= $usr->job_dano; ?></td>
                                        <td><?= $usr->invvdrd_description; ?></td>
                                        <td><?= number_format($usr->invvdrd_qty, 0, ",", "."); ?></td>
                                        <td><?= number_format($usr->invvdrd_price, 0, ",", "."); ?></td>
                                        <td><?= number_format($usr->invvdrd_total, 0, ",", "."); ?></td>
                                        <td><?= $usr->invvdrd_satuan; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <script>
                            function editinvvdrd(invvdrd_id) {
                                let job_dano = $("#job_dano" + invvdrd_id).val();
                                let invvdr_temp = $("#invvdr_temp" + invvdrd_id).val();
                                let invvdr_id = $("#invvdr_id" + invvdrd_id).val();
                                let invvdrd_satuan = $("#invvdrd_satuan" + invvdrd_id).val();
                                let invvdrd_qty = $("#invvdrd_qty" + invvdrd_id).val();
                                let invvdrd_description = $("#invvdrd_description" + invvdrd_id).val();
                                let job_id = $("#job_id" + invvdrd_id).val();
                                let invvdrdid = $("#invvdrd_id" + invvdrd_id).val();
                                let invvdrd_price = $("#invvdrd_price" + invvdrd_id).val();
                                let invvdrdtotal = $("#invvdrd_total" + invvdrd_id).val();


                                $("#job_id").val(job_id).trigger('change');
                                $("#job_dano").val(job_dano);
                                $("#invvdr_temp").val(invvdr_temp);
                                $("#invvdr_id").val(invvdr_id);
                                $("#invvdrd_satuan").val(invvdrd_satuan);
                                $("#invvdrd_qty").val(invvdrd_qty);
                                $("#invvdrd_description").val(invvdrd_description);
                                $("#invvdrd_id").val(invvdrdid);
                                $("#invvdrd_price").val(invvdrd_price);
                                $("#invvdrd_total").val(invvdrdtotal);

                                $("#btninvvdrd").attr("name", "change");
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let pagetitle = '&nbsp;&nbsp;<a href="<?= base_url("invvdr"); ?>" class="btn btn-warning"><i class="fa fa-undo"></i> Back to Invoice Vendor</a>';
    $(document).ready(function() {
        $("#page-title").append(pagetitle);
    });

    $('.select').select2();
    var title = "<?= $title; ?>";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);

    function bersih() {
        $('#myForm').find('input[type=text], input[type=number], textarea, select').val('');
        // Jika menggunakan select2, reset juga:
        $('#myForm').find('select').val(null).trigger('change');
        $("#btninvvdrd").attr("name", "create");
    }
</script>

<?php echo  $this->include("template/footer_v"); ?>