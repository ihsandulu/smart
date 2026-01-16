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
                                id="inv_date"
                                name="inv_date"
                                placeholder="Tanggal Invoice"
                                data-bs-toggle="popover"
                                data-bs-content="Pilih tanggal invoice"
                                data-bs-trigger="manual"
                                data-bs-placement="top"
                                value="<?= $inv_date; ?>">
                        </div>
                        <script>
                            $(function() {
                                $('#inv_date')
                                    .popover({
                                        content: 'Pilih tanggal invoice',
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
                                id="inv_duedate"
                                name="inv_duedate"
                                placeholder="Due Date"
                                data-bs-toggle="popover"
                                data-bs-content="Pilih tanggal jatuh tempo"
                                data-bs-trigger="manual"
                                data-bs-placement="top"
                                value="<?= $inv_duedate; ?>">
                        </div>
                        <script>
                            $(function() {
                                $('#inv_duedate')
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
                            <input type="text" class="form-control" style="width: 100px;" id="inv_discount" name="inv_discount" placeholder="Discount" value="<?= $inv_discount; ?>">
                        </div>
                        <div class="form-group">
                            <select required onchange="singkatan()" class="form-control select" id="customer_id" name="customer_id">
                                <option value="" data-singkatan="" <?= ($customer_id == "") ? "selected" : ""; ?>>Customer</option>
                                <?php $customer = $this->db->table("customer")
                                    ->orderBy("customer_name", "ASC")
                                    ->get();
                                foreach ($customer->getResult() as $customer) {
                                ?>
                                    <option value="<?= $customer->customer_id; ?>" data-singkatan="<?= $customer->customer_singkatan; ?>" <?= ($customer_id == $customer->customer_id) ? "selected" : ""; ?>><?= $customer->customer_name; ?></option>
                                <?php } ?>
                                <script>
                                    function singkatan() {
                                        let singkatan = $("#customer_id option:selected").data("singkatan");
                                        $("#customer_singkatan").val(singkatan);
                                    }
                                </script>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="text-black">&nbsp;1.1%&nbsp;</label>
                            <input <?= ($inv_ppn1k1 == 1) ? "checked" : ""; ?> type="checkbox" class="" style="" id="inv_ppn1k1" name="inv_ppn1k1" value="1">
                        </div>
                        <div class="form-group">
                            <label class="text-black">&nbsp;11%&nbsp;</label>
                            <input <?= ($inv_ppn11 == 1) ? "checked" : ""; ?> type="checkbox" class="" style="" id="inv_ppn11" name="inv_ppn11" value="1">
                        </div>
                        <div class="form-group">
                            <label class="text-black">&nbsp;12%&nbsp;</label>
                            <input <?= ($inv_ppn12 == 1) ? "checked" : ""; ?> type="checkbox" class="" style="" id="inv_ppn12" name="inv_ppn12" value="1">
                        </div>
                        <div class="form-group">
                            <label class="text-black">&nbsp;PPH&nbsp;</label>
                            <input <?= ($inv_pph == 1) ? "checked" : ""; ?> type="checkbox" class="" style="" id="inv_pph" name="inv_pph" value="1">
                        </div>

                        <input type="hidden" id="user_id" name="user_id" value="<?= session('user_id'); ?>" />
                        <input type="hidden" id="inv_temp" name="inv_temp" value="<?= $inv_temp; ?>" />
                        <input type="hidden" id="inv_id" name="inv_id" value="<?= $inv_id; ?>" />
                        <input type="hidden" id="customer_singkatan" name="customer_singkatan" value="" />

                        <?php
                        if (isset($_GET["editinv"])) {
                            $namebtninv = 'name="changeinv"';
                        } else {
                            $namebtninv = 'name="createinv"';
                        }
                        ?>
                        &nbsp;&nbsp;<button type="submit" <?= $namebtninv; ?> value="OK" class="btn btn-primary">Submit</button>
                    </form>
                    <form method="post" class="form-inline alert alert-info" action="">
                        <div class="form-group">
                            <select onchange="pilihdano()" class="form-control select" id="job_id" name="job_id">
                                <option value="">Pilih Da Number</option>
                                <?php $job = $this->db->table("job")
                                    // ->where("inv_temp", "")
                                    // ->orWhere("inv_temp", $inv_temp)
                                    ->orderBy("job_dano", "ASC")
                                    ->get();
                                foreach ($job->getResult() as $job) {
                                ?>
                                    <option value="<?= $job->job_id; ?>" data-temp="<?= $job->job_temp; ?>" data-des="<?= $job->job_descgood; ?>" data-dano="<?= $job->job_dano; ?>" data-qty="<?= $job->job_qty; ?>" data-satuan="<?= $job->job_satuan; ?>" data-price="<?= $job->job_sell; ?>"><?= $job->job_dano; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <!-- <div class="form-group">
                            <input type="text" class="form-control" style="width: 200px;" id="invd_description" name="invd_description" placeholder="Description">
                        </div>
                        <div class="form-group">
                            <input onkeyup="kali()" type="text" class="form-control" style="width: 80px;" id="invd_qty" name="invd_qty" placeholder="QTY">
                        </div>
                        <div class="form-group">
                            <select required class="form-control" id="invd_satuan" name="invd_satuan">
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
                            <input onkeyup="kali()" type="text" class="form-control" style="width: 120px;" id="invd_price" name="invd_price" placeholder="Price">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" style="width: 120px;" id="invd_total" name="invd_total" placeholder="Total">
                        </div> -->
                        <script>
                            function pilihdano() {
                                let selected = $("#job_id option:selected");
                                let dano = selected.data("dano");
                                // let des = selected.data("des");
                                // let qty = selected.data("qty");
                                // let satuan = selected.data("satuan");
                                // let price = selected.data("price");
                                let temp = selected.data("temp");
                                $("#job_dano").val(dano);
                                // $("#invd_description").val(des);
                                // $("#invd_qty").val(qty);
                                // $("#invd_satuan").val(satuan);
                                // $("#invd_price").val(price);
                                $("#job_temp").val(temp);
                                kali();
                            }

                            function kali() {
                                let qty = $("#invd_qty").val();
                                let price = $("#invd_price").val();
                                let total = qty * price;
                                $("#invd_total").val(total);
                            }
                        </script>
                        <input type="hidden" id="job_temp" name="job_temp" value="" />
                        <input type="hidden" id="job_dano" name="job_dano" value="" />
                        <input type="hidden" id="inv_temp" name="inv_temp" value="<?= $inv_temp; ?>" />
                        <input type="hidden" id="inv_id" name="inv_id" value="<?= $inv_id; ?>" />
                        <input type="hidden" id="invd_id" name="invd_id" value="" />
                        <?php if (isset($_GET["editinv"])) { ?>
                            <input type="hidden" id="invd_date" name="invd_date" value="<?= $inv_date; ?>" />
                        <?php } ?>

                        &nbsp;&nbsp;<button id="btninvd" type="submit" name="create" value="OK" class="btn btn-primary">Submit</button>
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
                                    <th>Koli</th>
                                    <th>CBM</th>
                                    <th>Satuan</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $build = $this->db
                                    ->table("invd");
                                if (isset($_GET["inv_temp"])) {
                                    $build->where("inv_temp", $inv_temp);
                                }
                                $usr = $build->get();

                                // echo $this->db->getLastquery();
                                $no = 1;
                                foreach ($usr->getResult() as $usr) { ?>
                                    <tr>
                                        <?php if (!isset($_GET["report"])) { ?>
                                            <td style="padding-left:0px; padding-right:0px;">
                                                <!-- <?php
                                                if (
                                                    (
                                                        isset(session()->get("position_administrator")[0][0])
                                                        && (
                                                            session()->get("position_administrator") == "1"
                                                            || session()->get("position_administrator") == "2"
                                                        )
                                                    ) ||
                                                    (
                                                        isset(session()->get("halaman")['111']['act_update'])
                                                        && session()->get("halaman")['111']['act_update'] == "1"
                                                    )
                                                ) { ?>
                                                    <form method="post" class="btn-action" >
                                                        <button type="button" onclick="editinvd('<?= $usr->invd_id; ?>')" class="btn btn-sm btn-warning " name="edit" value="OK">
                                                            <span class="fa fa-edit" style="color:white;"></span>
                                                        </button>
                                                        <input type="hidden" id="job_dano<?= $usr->invd_id; ?>" name="job_dano" value="<?= $usr->job_dano; ?>" />
                                                        <input type="hidden" id="inv_temp<?= $usr->invd_id; ?>" name="inv_temp" value="<?= $usr->inv_temp; ?>" />
                                                        <input type="hidden" id="inv_id<?= $usr->invd_id; ?>" name="inv_id" value="<?= $usr->inv_id; ?>" />
                                                        <input type="hidden" id="invd_total<?= $usr->invd_id; ?>" name="invd_total" value="<?= $usr->invd_total; ?>" />
                                                        <input type="hidden" id="invd_price<?= $usr->invd_id; ?>" name="invd_price" value="<?= $usr->invd_price; ?>" />
                                                        <input type="hidden" id="invd_satuan<?= $usr->invd_id; ?>" name="invd_satuan" value="<?= $usr->invd_satuan; ?>" />
                                                        <input type="hidden" id="invd_qty<?= $usr->invd_id; ?>" name="invd_qty" value="<?= $usr->invd_qty; ?>" />
                                                        <input type="hidden" id="invd_description<?= $usr->invd_id; ?>" name="invd_description" value="<?= $usr->invd_description; ?>" />
                                                        <input type="hidden" id="job_id<?= $usr->invd_id; ?>" name="job_id" value="<?= $usr->job_id; ?>" />
                                                        <input type="hidden" id="invd_id<?= $usr->invd_id; ?>" name="invd_id" value="<?= $usr->invd_id; ?>" />
                                                    </form>
                                                <?php } ?> -->

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
                                                        isset(session()->get("halaman")['111']['act_delete'])
                                                        && session()->get("halaman")['111']['act_delete'] == "1"
                                                    )
                                                ) { ?>
                                                    <form method="post" class="btn-action" style="">
                                                        <button class="btn btn-sm btn-danger delete" onclick="return confirm(' you want to delete?');" name="delete" value="OK"><span class="fa fa-close" style="color:white;"></span> </button>
                                                        <input type="hidden" name="invd_id" value="<?= $usr->invd_id; ?>" />
                                                        <input type="hidden" name="inv_temp" value="<?= $inv_temp; ?>" />
                                                        <input type="hidden" name="job_id" value="<?= $usr->job_id; ?>" />
                                                    </form>
                                                <?php } ?>
                                            </td>
                                        <?php } ?>
                                        <!-- <td><?= $no++; ?></td> -->
                                        <td><?= $usr->job_dano; ?></td>
                                        <td><?= $usr->invd_description; ?></td>
                                        <td><?= number_format($usr->invd_koli, 0, ",", "."); ?></td>
                                        <td><?= number_format($usr->invd_cbm, 3, ",", "."); ?></td>
                                        <td><?= $usr->invd_satuan; ?></td>
                                        <td><?= number_format($usr->invd_price, 0, ",", "."); ?></td>
                                        <td><?= number_format($usr->invd_total, 0, ",", "."); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <script>
                            function editinvd(invd_id) {
                                let job_dano = $("#job_dano" + invd_id).val();
                                let inv_temp = $("#inv_temp" + invd_id).val();
                                let inv_id = $("#inv_id" + invd_id).val();
                                let invd_total = $("#invd_total" + invd_id).val();
                                let invd_price = $("#invd_price" + invd_id).val();
                                let invd_satuan = $("#invd_satuan" + invd_id).val();
                                let invd_qty = $("#invd_qty" + invd_id).val();
                                let invd_description = $("#invd_description" + invd_id).val();
                                let job_id = $("#job_id" + invd_id).val();
                                let invdid = $("#invd_id" + invd_id).val();

                                $("#job_id").val(job_id).trigger('change');
                                $("#job_dano").val(job_dano);
                                $("#inv_temp").val(inv_temp);
                                $("#inv_id").val(inv_id);
                                $("#invd_total").val(invd_total);
                                $("#invd_price").val(invd_price);
                                $("#invd_satuan").val(invd_satuan);
                                $("#invd_qty").val(invd_qty);
                                $("#invd_description").val(invd_description);
                                $("#invd_id").val(invdid);

                                $("#btninvd").attr("name", "change");
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let pagetitle = '&nbsp;&nbsp;<a href="<?= base_url("inv"); ?>" class="btn btn-warning"><i class="fa fa-undo"></i> Back to Invoice</a>';
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