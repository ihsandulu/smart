<?php echo $this->include("template/header_v");
$identity = $this->db->table("identity")->get()->getRow();
if (isset($_GET["tbl"])) {
    $tbl = $_GET["tbl"];
} else {
    $tbl = "";
}
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
                            <input type="date" class="form-control" style="width: 200px;" id="cost_date" name="cost_date" placeholder="Date">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" style="width: 400px;" id="cost_description" name="cost_description" placeholder="Description">
                        </div>
                        <div class="form-group">
                            <input onkeyup="kali()" type="text" class="form-control" style="width: 80px;" id="cost_qty" name="cost_qty" placeholder="QTY">
                        </div>
                        <div class="form-group">
                            <input onkeyup="kali()" type="text" class="form-control" style="width: 120px;" id="cost_nominal" name="cost_nominal" placeholder="Price">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" style="width: 120px;" id="cost_total" name="cost_total" placeholder="Total">
                        </div>
                        <div class="form-group">
                            <select class="form-control" style="" id="cost_from" name="cost_from">
                                <option value="">From</option>
                                <?php
                                $rek = $this->db->table("rekening")
                                    ->orderBy("rekening_an", "ASC")
                                    ->get();
                                foreach ($rek->getResult() as $r) { ?>
                                    <option value="<?= $r->rekening_id; ?>"><?= $r->rekening_no; ?> - <?= $r->rekening_an; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-control" style="" id="cost_to" name="cost_to">
                                <option value="">To</option>
                                <?php
                                $rek = $this->db->table("rekening")
                                    ->orderBy("rekening_an", "ASC")
                                    ->get();
                                foreach ($rek->getResult() as $r) { ?>
                                    <option value="<?= $r->rekening_id; ?>"><?= $r->rekening_no; ?> - <?= $r->rekening_an; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" style="width: 400px;" id="cost_ket" name="cost_ket" placeholder="Keterangan">
                        </div>
                        <script>
                            function kali() {
                                let qty = $("#cost_qty").val();
                                let price = $("#cost_nominal").val();
                                let total = qty * price;
                                $("#cost_total").val(total);
                            }
                        </script>
                        <input type="hidden" id="job_temp" name="job_temp" value="<?= $job_temp; ?>" />
                        <input type="hidden" id="cost_id" name="cost_id" value="" />

                        &nbsp;&nbsp;<button id="btncost" type="submit" name="create" value="OK" class="btn btn-primary">Submit</button>
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
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $build = $this->db
                                    ->table("cost")
                                    ->join("(SELECT rekening_id as fromid, rekening_no as fromrek, rekening_an as froman from rekening) AS dari", "dari.fromid = cost.cost_from", "left")
                                    ->join("(SELECT rekening_id as toid, rekening_no as torek, rekening_an as toan from rekening) AS ke", "ke.toid = cost.cost_to", "left");
                                $build->where("job_temp", $job_temp);
                                $build->where("job_temp !=", "");
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
                                                        <button type="button" onclick="editcost('<?= $usr->cost_id; ?>')" class="btn btn-sm btn-warning " name="edit" value="OK">
                                                            <span class="fa fa-edit" style="color:white;"></span>
                                                        </button>
                                                        <input type="hidden" id="job_temp<?= $usr->cost_id; ?>" name="job_temp" value="<?= $usr->job_temp; ?>" />
                                                        <input type="hidden" id="cost_date<?= $usr->cost_id; ?>" name="cost_date" value="<?= $usr->cost_date; ?>" />
                                                        <input type="hidden" id="cost_total<?= $usr->cost_id; ?>" name="cost_total" value="<?= $usr->cost_total; ?>" />
                                                        <input type="hidden" id="cost_nominal<?= $usr->cost_id; ?>" name="cost_nominal" value="<?= $usr->cost_nominal; ?>" />

                                                        <input type="hidden" id="cost_qty<?= $usr->cost_id; ?>" name="cost_qty" value="<?= $usr->cost_qty; ?>" />
                                                        <input type="hidden" id="cost_description<?= $usr->cost_id; ?>" name="cost_description" value="<?= $usr->cost_description; ?>" />
                                                        <input type="hidden" id="cost_id<?= $usr->cost_id; ?>" name="cost_id" value="<?= $usr->cost_id; ?>" />
                                                        <input type="hidden" id="fromid<?= $usr->cost_id; ?>" name="fromid" value="<?= $usr->fromid; ?>" />
                                                        <input type="hidden" id="toid<?= $usr->cost_id; ?>" name="toid" value="<?= $usr->toid; ?>" />
                                                        <input type="hidden" id="cost_ket<?= $usr->cost_id; ?>" name="cost_ket" value="<?= $usr->cost_ket; ?>" />
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
                                                        <input type="hidden" name="cost_id" value="<?= $usr->cost_id; ?>" />
                                                    </form>
                                                <?php } ?>
                                            </td>
                                        <?php } ?>
                                        <!-- <td><?= $no++; ?></td> -->
                                        <td><?= $usr->cost_description; ?></td>
                                        <td><?= number_format($usr->cost_qty, 3, ",", "."); ?></td>
                                        <td><?= number_format($usr->cost_nominal, 2, ",", "."); ?></td>
                                        <td><?= number_format($usr->cost_total, 2, ",", "."); ?></td>
                                        <td><?= $usr->fromrek; ?>-<?= $usr->froman; ?></td>
                                        <td><?= $usr->torek; ?>-<?= $usr->toan; ?></td>
                                        <td><?= $usr->cost_ket; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <script>
                            function editcost(cost_id) {
                                let job_temp = $("#job_temp" + cost_id).val();
                                let cost_total = $("#cost_total" + cost_id).val();
                                let cost_nominal = $("#cost_nominal" + cost_id).val();
                                let cost_qty = $("#cost_qty" + cost_id).val();
                                let cost_description = $("#cost_description" + cost_id).val();
                                let cost_date = $("#cost_date" + cost_id).val();
                                let costid = $("#cost_id" + cost_id).val();
                                let fromid = $("#fromid" + cost_id).val();
                                let toid = $("#toid" + cost_id).val();
                                let cost_ket = $("#cost_ket" + cost_id).val();

                                $("#job_temp").val(job_temp);
                                $("#cost_total").val(cost_total);
                                $("#cost_nominal").val(cost_nominal);
                                $("#cost_qty").val(cost_qty);
                                $("#cost_description").val(cost_description);
                                $("#cost_date").val(cost_date);
                                $("#cost_id").val(costid);
                                $("#cost_from").val(fromid);
                                $("#cost_to").val(toid);
                                $("#cost_ket").val(cost_ket);

                                $("#btncost").attr("name", "change");
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    <?php if ($_GET["t"] == "jc") {
        $urln = base_url($_GET["url"]);
    } else {
        $urln = base_url($_GET["url"] . "?t=" . $_GET["t"] . "&temp=" . $job_temp . "&tbl=" . $tbl);
    }
    ?>
    let pagetitle = '&nbsp;&nbsp;<a href="<?= $urln;  ?>" class="btn btn-warning"><i class="fa fa-undo"></i> Back to Job</a>';
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