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
<?php
//******* Cari titik point terakhir, di table tbuku untuk menentukan mana yg didisable buttonnya  *********//
$tgltbterakhir = "2000-12-01";
$tbukun = $this->db->table("tbuku")->orderBy("tbuku_date", "DESC")->limit(1)->get();
foreach ($tbukun->getResult() as $row) {
    $tgltbterakhir = $row->tbuku_date;
}
?>
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
                            <input type="date"
                                class="form-control"
                                style="width:100px;"
                                id="invvdrp_date"
                                name="invvdrp_date"
                                placeholder="Tanggal Invoice"
                                data-bs-toggle="popover"
                                data-bs-content="Pilih tanggal invoice"
                                data-bs-trigger="manual"
                                data-bs-placement="top"
                                min="<?=$tgltbterakhir;?>"
                                value="">
                        </div>
                        <script>
                            $(function() {
                                $('#invvdrp_date')
                                    .popover({
                                        content: 'Pilih Tanggal Pembayaran',
                                        trigger: 'manual',
                                        placement: 'top',
                                        template: '<div class="popover bs-popover-top" role="tooltip"><div class="arrow"></div><div class="popover-body"></div></div>'
                                    })
                                    .popover('show');
                            });
                        </script>
                        <div class="form-group">
                            <input type="text" class="form-control" style="width: 120px;" id="invvdrp_nominal" name="invvdrp_nominal" placeholder="Nominal">
                        </div>
                        <div class="form-group">
                            <select required class="form-control" id="methodpayment_id" name="methodpayment_id">
                                <option value="">Payment Methode</option>
                                <?php $methodpayment = $this->db->table("methodpayment")
                                    ->orderBy("methodpayment_name", "ASC")
                                    ->get();
                                foreach ($methodpayment->getResult() as $methodpayment) {
                                ?>
                                    <option value="<?= $methodpayment->methodpayment_id; ?>"><?= $methodpayment->methodpayment_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <select style="width: 100px;" class="form-control" id="invvdrp_from" name="invvdrp_from" placeholder="From"
                                data-bs-toggle="popover"
                                data-bs-content="Asal"
                                data-bs-trigger="manual"
                                data-bs-placement="top">
                                <option value="">From</option>
                                <option value="-1">Pettycash</option>
                                <?php $rekening = $this->db->table("rekening")
                                    ->where("rekening_type", "NKL")
                                    ->orderBy("rekening_no", "ASC")
                                    ->get();
                                foreach ($rekening->getResult() as $rekening) {
                                ?>
                                    <option value="<?= $rekening->rekening_id; ?>"><?= $rekening->rekening_no; ?> <?= $rekening->rekening_an; ?></option>
                                <?php } ?>
                            </select>
                            <script>
                                $(function() {
                                    $('#invvdrp_from')
                                        .popover({
                                            content: 'Asal',
                                            trigger: 'manual',
                                            placement: 'top',
                                            template: '<div class="popover bs-popover-top" role="tooltip"><div class="arrow"></div><div class="popover-body"></div></div>'
                                        })
                                        .popover('show');
                                });
                            </script>
                        </div>
                        <div class="form-group">
                            <select style="width: 100px;" class="form-control" id="invvdrp_to" name="invvdrp_to" placeholder="To"
                                data-bs-toggle="popover"
                                data-bs-content="Tujuan"
                                data-bs-trigger="manual"
                                data-bs-placement="top">
                                <option value="">To</option>
                                <?php $rekening = $this->db->table("rekening")
                                    ->where("rekening_type", "Vendor")
                                    ->orderBy("rekening_no", "ASC")
                                    ->get();
                                foreach ($rekening->getResult() as $rekening) {
                                ?>
                                    <option value="<?= $rekening->rekening_id; ?>"><?= $rekening->rekening_no; ?> <?= $rekening->rekening_an; ?></option>
                                <?php } ?>
                            </select>
                            <script>
                                $(function() {
                                    $('#invvdrp_to')
                                        .popover({
                                            content: 'Tujuan',
                                            trigger: 'manual',
                                            placement: 'top',
                                            template: '<div class="popover bs-popover-top" role="tooltip"><div class="arrow"></div><div class="popover-body"></div></div>'
                                        })
                                        .popover('show');
                                });
                            </script>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" style="width: 200px;" id="invvdrp_keterangan" name="invvdrp_keterangan" placeholder="Keterangan">
                        </div>

                        <input type="hidden" id="invvdr_id" name="invvdr_id" value="<?= $invvdr_id; ?>" />
                        <input type="hidden" id="invvdr_no" name="invvdr_no" value="<?= $invvdr_no; ?>" />
                        <input type="hidden" id="vendor_id" name="vendor_id" value="<?= $vendor_id; ?>" />
                        <input type="hidden" id="vendor_name" name="vendor_name" value="<?= $vendor_name; ?>" />
                        <input type="hidden" id="invvdrp_id" name="invvdrp_id" value="" />
                        <input type="hidden" id="kas_id" name="kas_id" value="" />

                        &nbsp;&nbsp;<button id="btninvvdrp" type="submit" name="create" value="OK" class="btn btn-primary">Submit</button>
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
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Nominal</th>
                                    <th>Methode</th>
                                    <th>Rek.Asal</th>
                                    <th>Rek.Tujuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php


                                $build = $this->db
                                    ->table("invvdrp")
                                    ->join("methodpayment", "methodpayment.methodpayment_id=invvdrp.methodpayment_id", "left")
                                    ->join("(SELECT rekening_id as asalid, rekening_no as asalno from rekening) As asal", "asal.asalid=invvdrp.invvdrp_from", "left")
                                    ->join("(SELECT rekening_id as tujuanid, rekening_no as tujuanno from rekening) As tujuan", "tujuan.tujuanid=invvdrp.invvdrp_to", "left");
                                if (isset($_GET["invvdr_no"])) {
                                    $build->where("invvdr_no", $invvdr_no);
                                }
                                $usr = $build
                                    ->orderBy("invvdrp_date", "ASC")
                                    ->orderBy("invvdrp_id", "ASC")
                                    ->get();

                                //echo $this->db->getLastquery();
                                $no = 1;
                                foreach ($usr->getResult() as $usr) { ?>
                                    <tr>
                                        <?php if (!isset($_GET["report"])) { ?>
                                            <td style="padding-left:0px; padding-right:0px;">
                                                <?php if ($usr->invvdrp_date > $tgltbterakhir) { ?>
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
                                                            <button type="button" onclick="editinvvdrp('<?= $usr->invvdrp_id; ?>')" class="btn btn-sm btn-warning " name="edit" value="OK">
                                                                <span class="fa fa-edit" style="color:white;"></span>
                                                            </button>

                                                            <input type="hidden" id="invvdr_no<?= $usr->invvdrp_id; ?>" name="invvdr_no" value="<?= $usr->invvdr_no; ?>" />
                                                            <input type="hidden" id="invvdrp_nominal<?= $usr->invvdrp_id; ?>" name="invvdrp_nominal" value="<?= $usr->invvdrp_nominal; ?>" />


                                                            <input type="hidden" id="invvdrp_keterangan<?= $usr->invvdrp_id; ?>" name="invvdrp_keterangan" value="<?= $usr->invvdrp_keterangan; ?>" />
                                                            <input type="hidden" id="invvdrp_id<?= $usr->invvdrp_id; ?>" name="invvdrp_id" value="<?= $usr->invvdrp_id; ?>" />
                                                            <input type="hidden" id="kas_id<?= $usr->invvdrp_id; ?>" name="kas_id" value="<?= $usr->kas_id; ?>" />
                                                            <input type="hidden" id="invvdrp_date<?= $usr->invvdrp_id; ?>" name="invvdrp_date" value="<?= $usr->invvdrp_date; ?>" />
                                                            <input type="hidden" id="invvdrp_from<?= $usr->invvdrp_id; ?>" name="invvdrp_from" value="<?= $usr->invvdrp_from; ?>" />
                                                            <input type="hidden" id="invvdrp_to<?= $usr->invvdrp_id; ?>" name="invvdrp_to" value="<?= $usr->invvdrp_to; ?>" />
                                                            <input type="hidden" id="methodpayment_id<?= $usr->invvdrp_id; ?>" name="methodpayment_id" value="<?= $usr->methodpayment_id; ?>" />
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
                                                            <input type="hidden" name="kas_id" value="<?= $usr->kas_id; ?>" />
                                                            <input type="hidden" name="invvdrp_id" value="<?= $usr->invvdrp_id; ?>" />
                                                            <input type="hidden" name="invvdr_no" value="<?= $invvdr_no; ?>" />
                                                        </form>
                                                    <?php } ?>
                                                <?php } ?>
                                            </td>
                                        <?php } ?>
                                        <!-- <td><?= $no++; ?></td> -->
                                        <td><?= $usr->invvdrp_date; ?></td>
                                        <td><?= $usr->invvdrp_keterangan; ?></td>
                                        <td><?= number_format($usr->invvdrp_nominal, 0, ",", "."); ?></td>
                                        <td><?= $usr->methodpayment_name; ?></td>
                                        <td><?= ($usr->asalno == "") ? "" : $usr->asalno; ?></td>
                                        <td><?= ($usr->tujuanno == "") ? "" : $usr->tujuanno; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <script>
                            function editinvvdrp(invvdrp_id) {
                                let job_dano = $("#job_dano" + invvdrp_id).val();
                                let invvdr_no = $("#invvdr_no" + invvdrp_id).val();
                                let inv_id = $("#inv_id" + invvdrp_id).val();
                                let invvdrp_nominal = $("#invvdrp_nominal" + invvdrp_id).val();
                                let invvdrp_satuan = $("#invvdrp_satuan" + invvdrp_id).val();
                                let invvdrp_keterangan = $("#invvdrp_keterangan" + invvdrp_id).val();
                                let job_id = $("#job_id" + invvdrp_id).val();
                                let invvdrpid = $("#invvdrp_id" + invvdrp_id).val();
                                let kas_id = $("#kas_id" + invvdrp_id).val();
                                let invvdrp_date = $("#invvdrp_date" + invvdrp_id).val();
                                let invvdrp_from = $("#invvdrp_from" + invvdrp_id).val();
                                let invvdrp_to = $("#invvdrp_to" + invvdrp_id).val();
                                let methodpayment_id = $("#methodpayment_id" + invvdrp_id).val();

                                $("#job_dano").val(job_dano);
                                $("#invvdr_no").val(invvdr_no);
                                $("#inv_id").val(inv_id);
                                $("#invvdrp_nominal").val(invvdrp_nominal);
                                $("#invvdrp_satuan").val(invvdrp_satuan);
                                $("#invvdrp_keterangan").val(invvdrp_keterangan);
                                $("#job_id").val(job_id);
                                $("#invvdrp_id").val(invvdrpid);
                                $("#kas_id").val(kas_id);
                                $("#invvdrp_date").val(invvdrp_date);
                                $("#invvdrp_from").val(invvdrp_from);
                                $("#invvdrp_to").val(invvdrp_to);
                                $("#methodpayment_id").val(methodpayment_id);

                                $("#btninvvdrp").attr("name", "change");
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let pagetitle = '&nbsp;&nbsp;<a href="<?= base_url("invvdr"); ?>" class="btn btn-warning"><i class="fa fa-undo"></i> Back to Invoice</a>';
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