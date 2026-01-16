<?php echo $this->include("template/header_v");
$identity = $this->db->table("identity")->get()->getRow(); ?>
<style>
    td {
        white-space: nowrap;
    }

    .ftagihan {
        font-size: 12px;
        line-height: 15px !important;
    }

    .uang {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        text-align: left;
    }

    .wrap-text {
        white-space: normal;
        word-wrap: break-word;
        word-break: break-word;
        /* untuk browser modern */
    }

    .w100 {
        width: 100px !important;
    }

    td {
        font-size: 12px;
    }

    td.w200 {
        white-space: normal !important;
        word-wrap: break-word;
        word-break: break-word;
        max-width: 200px;
    }

    /* atur tinggi baris agar lebih rapat */
    .wraptext {
        white-space: normal;
        /* agar teks bisa membungkus baris baru */
        word-break: break-word;
        line-height: 1.2 !important;
        vertical-align: middle;
    }




    /* styling wrapper */
    #table-wrapper {
        overflow: auto;
        cursor: grab;
        -webkit-overflow-scrolling: touch;
        /* smoother on iOS */
        touch-action: pan-y;
        /* biarkan vertical scroll tetap berfungsi, kita tangani horizontal drag */
        user-select: none;
        /* mencegah teks terseleksi saat drag */
    }


    /* Tambahan styling agar proporsional */
    .dataTables_wrapper .dataTables_scrollBody {
        cursor: grab;
        overflow-y: hidden !important;
        /* cegah baris tinggi berlebihan */
    }

    table.dataTable {
        border-collapse: collapse !important;
        width: 100% !important;
        table-layout: fixed;
        /* penting agar kolom stabil */
        white-space: nowrap;
    }

    table.dataTable td,
    table.dataTable th {
        text-overflow: ellipsis;
        overflow: hidden;
        vertical-align: middle;
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
                                    isset(session()->get("halaman")['122']['act_create'])
                                    && session()->get("halaman")['122']['act_create'] == "1"
                                )
                            ) { ?>
                                <form method="get" class="col-md-2" action="<?= base_url("invvdrd"); ?>">
                                    <h1 class="page-header col-md-12">
                                        <button name="new" class="btn btn-info btn-block btn-sm" value="OK" style="">New</button>
                                        <input type="hidden" name="invvdr_id" />
                                        <?php
                                        $invvdr_temp = date("dmyhis");
                                        ?>
                                        <input type="hidden" name="invvdr_temp" value="<?= $invvdr_temp; ?>" />
                                    </h1>
                                </form>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div class="alert alert-danger">
                        Due date in this week :
                        <?php $due = $this->db
                            ->table("job")
                            ->where("job_duedate >=", date("Y-m-d", strtotime("-3 days")))
                            ->where("job_duedate <=", date("Y-m-d", strtotime("+7 days")))
                            ->groupBy("job_duedate")
                            ->get();
                        //echo $this->db->last_query();
                        foreach ($due->getResult() as $due) { ?>
                            <strong><?= $due->job_dano; ?></strong>,
                        <?php } ?>

                    </div>
                    <?php if ($message != "") { ?>
                        <div class="alert alert-info alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong><?= $message; ?></strong>
                        </div>
                    <?php } ?>
                    <form method="get">
                        <div class="row alert alert-dark">
                            <?php
                            $dari = date("Y-m-d", strtotime("-1 week", strtotime(date("Y-m-d"))));
                            $ke = date("Y-m-d");
                            $lunas = "";
                            if (isset($_GET["dari"])) {
                                $dari = $_GET["dari"];
                            }
                            if (isset($_GET["ke"])) {
                                $ke = $_GET["ke"];
                            }
                            if (isset($_GET["lunas"])) {
                                $lunas = $_GET["lunas"];
                            }
                            if (isset($_GET["vendor_id"])) {
                                $vendor_id = $_GET["vendor_id"];
                            }
                            ?>
                            <div class="col-2 ">
                                <input data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="manual" title="Dari" type="date" class="form-control tooltip-statis" placeholder="Dari" name="dari" value="<?= $dari; ?>">
                            </div>
                            <div class="col-2  ">
                                <input data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="manual" title="Ke" type="date" class="form-control tooltip-statis" placeholder="Ke" name="ke" value="<?= $ke; ?>">
                            </div>
                            <div class="col-3  ">
                                <select class="form-control select" name="vendor_id" value="<?= $vendor_id; ?>">
                                    <option value="" <?= ($vendor_id == "") ? "selected" : ""; ?>>All Vendor</option>
                                    <?php $vendor = $this->db->table("vendor")->orderBy("vendor_name", "ASC")->get();
                                    foreach ($vendor->getResult() as $row) { ?>
                                        <option value="<?= $row->vendor_id; ?>" <?= ($vendor_id == $row->vendor_id) ? "selected" : ""; ?>><?= $row->vendor_name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-2  ">
                                <select class="form-control" name="lunas" value="<?= $lunas; ?>">
                                    <option value="">Semua</option>
                                    <option value="1" <?= ($lunas == "1") ? "selected" : ""; ?>>Lunas</option>
                                    <option value="0" <?= ($lunas == "0") ? "selected" : ""; ?>>Belum Lunas</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-block btn-primary">Search</button>
                            </div>
                        </div>
                    </form>
                    <div id="table-wrapper" style="overflow:auto; cursor: grab;">
                        <?php
                        $invvdrd = $this->db->table("invvdrd")
                            ->where("invvdrd_date BETWEEN '" . $dari . "' AND '" . $ke . "'")
                            ->get();
                        $ainvvdrd = array();
                        foreach ($invvdrd->getResult() as $invvdrd) {
                            $ainvvdrd[$invvdrd->invvdr_id]["job_dano"][] = $invvdrd->job_dano;
                            $ainvvdrd[$invvdrd->invvdr_id]["invvdrd_description"][] = $invvdrd->invvdrd_description;
                        }
                        // print_r($ainvvdrd);
                        ?>
                        <table id="ex23" class="display table table-hover table-striped table-bordered">
                            <!-- <table id="dataTable" class="table table-condensed table-hover w-auto dtable"> -->
                            <thead class="">
                                <tr>
                                    <?php if (!isset($_GET["report"])) { ?>
                                        <th>Action</th>
                                    <?php } ?>
                                    <!-- <th>No.</th> -->
                                    <th>Date</th>
                                    <th>Invoice Number</th>
                                    <th>DA Number</th>
                                    <th style="width:200px;">Description</th>
                                    <th>Vendor</th>
                                    <th>Tagihan</th>
                                    <th>Pembayaran</th>
                                    <th>Sisa Hutang</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $invvdr = $this->db->table("invvdr")
                                    ->where("invvdr_date BETWEEN '" . $dari . "' AND '" . $ke . "'")
                                    ->get();
                                $ainvvdr = array();
                                foreach ($invvdr->getResult() as $invvdr) {
                                    $ainvvdr[$invvdr->invvdr_id]["job_dano"][] = $invvdr->job_dano;
                                }
                                // dd($ainvvdr);
                                $build = $this->db
                                    ->table("invvdr")
                                    ->join("vendor", "vendor.vendor_id=invvdr.vendor_id", "left");
                                $build->where("invvdr_date BETWEEN '" . $dari . "' AND '" . $ke . "'");
                                if (isset($_GET["lunas"]) && $_GET["lunas"] != "") {
                                    if ($lunas == "1") {
                                        $build->where("invvdr_payment >= invvdr_grand");
                                    } else if ($lunas == "0") {
                                        $build->where("invvdr_payment < invvdr_grand");
                                    }
                                }
                                if (isset($_GET["vendor_id"]) && $_GET["vendor_id"] != "") {
                                    $build->where("invvdr.vendor_id", $vendor_id);
                                }
                                $usr = $build->orderBy("invvdr.invvdr_id", "DESC")
                                    ->get();

                                // echo $this->db->getLastquery();
                                $no = 1;
                                $debettype = array("pettycash" => "Petty Cash", "bigcash" => "Big Cash");
                                $pembayaran = 0;
                                $sisahutangnya = 0;
                                $bayar = 0;
                                $hutang = 0;
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
                                                    <form target="_self" method="get" class="btn-action" style="" action="<?= base_url("invvdrd"); ?>">
                                                        <button title="Edit" data-bs-toggle="tooltip" class="btn btn-sm btn-warning " name="editinvvdr" value="OK"><span class="fa fa-edit" style="color:white;"></span> </button>
                                                        <input type="hidden" name="invvdr_id" value="<?= $usr->invvdr_id; ?>" />
                                                        <input type="hidden" name="invvdr_temp" value="<?= $usr->invvdr_temp; ?>" />
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
                                                        <button title="Delete" data-bs-toggle="tooltip" class="btn btn-sm btn-danger delete" onclick="return confirm(' you want to delete?');" name="delete" value="OK"><span class="fa fa-close" style="color:white;"></span> </button>
                                                        <input type="hidden" name="invvdr_id" value="<?= $usr->invvdr_id; ?>" />
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
                                                    <form method="get" class="btn-action" style="" action="<?= base_url("invvdrp"); ?>">
                                                        <button title="Pembayaran" data-bs-toggle="tooltip" class="btn btn-sm btn-success" name="payment" value="OK"><span class="fa fa-money" style="color:white;"></span> </button>
                                                        <input type="hidden" name="invvdr_id" value="<?= $usr->invvdr_id; ?>" />
                                                        <input type="hidden" name="invvdr_no" value="<?= $usr->invvdr_no; ?>" />
                                                        <input type="hidden" name="vendor_id" value="<?= $usr->vendor_id; ?>" />
                                                        <input type="hidden" name="vendor_name" value="<?= $usr->vendor_name; ?>" />
                                                        <input type="hidden" name="invvdr_temp" value="<?= $usr->invvdr_temp; ?>" />
                                                    </form>
                                                <?php } ?>
                                                <!-- <form method="get" target="_blank" class="btn-action" style="" action="<?= base_url("invvdrprint"); ?>">
                                                    <button title="Print Invoice Vendor" data-bs-toggle="tooltip" class="btn btn-sm btn-warning" name="print" value="OK"><span class="fa fa-print" style="color:white;"></span> </button>
                                                    <input type="hidden" name="invvdr_id" value="<?= $usr->invvdr_id; ?>" />
                                                    <input type="hidden" name="invvdr_no" value="<?= $usr->invvdr_no; ?>" />
                                                    <input type="hidden" name="vendor_id" value="<?= $usr->vendor_id; ?>" />
                                                    <input type="hidden" name="vendor_name" value="<?= $usr->vendor_name; ?>" />
                                                </form> -->
                                            </td>
                                        <?php } ?>
                                        <!-- <td><?= $no++; ?></td> -->
                                        <td><?= $usr->invvdr_date; ?></td>
                                        <td><?= $usr->invvdr_no; ?></td>
                                        <td class=" wraptext w100">
                                            <?=
                                            isset($ainvvdrd[$usr->invvdr_id]["job_dano"]) && is_array($ainvvdrd[$usr->invvdr_id]["job_dano"])
                                                ? implode(', ', $ainvvdrd[$usr->invvdr_id]["job_dano"])
                                                : '-'
                                            ?>
                                        </td>
                                        <td class=" wraptext w200">
                                            <?=
                                            isset($ainvvdrd[$usr->invvdr_id]["invvdrd_description"]) && is_array($ainvvdrd[$usr->invvdr_id]["invvdrd_description"])
                                                ? implode(', ', $ainvvdrd[$usr->invvdr_id]["invvdrd_description"])
                                                : '-'
                                            ?>
                                        </td>
                                        <td class="text-left"><?= $usr->vendor_name; ?></td>
                                        <td class="ftagihan">
                                            <?php
                                            $dtagihan = $usr->invvdr_dtagihan;
                                            $ppn1k1 = 0;
                                            $ppn11 = 0;
                                            $ppn12 = 0;
                                            $pph = 0;
                                            ?>
                                            <span class="uang">
                                                <span>Tagihan:</span>
                                                <span><?= number_format($usr->invvdr_tagihan, 2, ".", ","); ?></span>
                                            </span>
                                            <span class="uang">
                                                <span>Diskon:</span>
                                                <span><?= number_format($usr->invvdr_discount, 2, ".", ","); ?></span>
                                            </span>
                                            <span class="uang">
                                                <span>Stlh Diskon:</span>
                                                <span><?= number_format($usr->invvdr_dtagihan, 2, ".", ","); ?></span>
                                            </span>
                                            <?php if ($usr->invvdr_ppn1k1 > 0) {
                                                $ppn1k1 = $dtagihan * 1.1 / 100; ?>
                                                <span class="uang">
                                                    <span>PPN1,1:</span>
                                                    <span><?= number_format($ppn1k1, 2, ".", ","); ?></span>
                                                </span>
                                            <?php } ?>
                                            <?php if ($usr->invvdr_ppn11 > 0) {
                                                $ppn11 = $dtagihan * 11 / 100; ?>
                                                <span class="uang">
                                                    <span>PPN11:</span>
                                                    <span><?= number_format($ppn11, 2, ".", ","); ?></span>
                                                </span>
                                            <?php } ?>
                                            <?php if ($usr->invvdr_ppn12 > 0) {
                                                $ppn12 = $dtagihan * 12 / 100; ?>
                                                <span class="uang">
                                                    <span>PPN12:</span>
                                                    <span><?= number_format($ppn12, 2, ".", ","); ?></span>
                                                </span>
                                            <?php } ?>
                                            <?php if ($usr->invvdr_pph > 0) {
                                                $pph = $dtagihan * 2 / 100; ?>
                                                <span class="uang">
                                                    <span>PPH:</span>
                                                    <span><?= number_format($pph, 2, ".", ","); ?></span>
                                                </span>
                                            <?php } ?>
                                            <?php
                                            $tharga = $dtagihan + $ppn1k1 + $ppn11 + $ppn12;
                                            $grand = $tharga - $pph;
                                            ?>
                                            <span class="uang">
                                                <span>Grand Total</span>
                                                <span><?= number_format($grand, 2, ".", ","); ?></span>
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <?= number_format($usr->invvdr_payment, 2, ".", ","); ?>
                                        </td>
                                        <td class="text-right">
                                            <?php
                                            $sisahutang = $grand - $usr->invvdr_payment;
                                            echo number_format($sisahutang, 0, ".", ","); ?>
                                        </td>
                                    </tr>
                                <?php $bayar += $usr->invvdr_payment;
                                    $hutang += $sisahutang;
                                } ?>
                                <tr>
                                    <?php if (!isset($_GET["report"])) { ?>
                                        <td style="padding-left:0px; padding-right:0px;">

                                        </td>
                                    <?php } ?>
                                    <!-- <td><?= $no++; ?></td> -->
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="f12 bold">
                                        Total :
                                    </td>
                                    <td class="f12 bold"><?= number_format($bayar, 0, ".", ","); ?></td>
                                    <td class="f12 bold"><?= number_format($hutang, 0, ".", ","); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.select').select2();
    var title = "<?= $title; ?>";
    let pembayaran = ". Pembayaran : <?= number_format($bayar, 0, ".", ","); ?> , Sisa Hutang : <?= number_format($hutang, 0, ".", ","); ?>";
    $("title").text(title);
    $(".card-title").text(title + pembayaran);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tooltipTriggerList = document.querySelectorAll('.tooltip-statis');

        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            const tooltip = new bootstrap.Tooltip(tooltipTriggerEl);

            // Menampilkan tooltip secara manual
            tooltip.show();
        });
    });
    $(document).ready(function() {
        $('#ex23').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'print',
                    exportOptions: {
                        columns: ':not(:first-child)'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':not(:first-child)'
                    },
                    orientation: 'landscape',
                    pageSize: 'A4',
                    customize: function(doc) {
                        // Tambah jarak antara title dan tabel
                        doc.content[1].margin = [0, 20, 0, 0]; // [left, top, right, bottom]

                        // Biar kolom rata dan memenuhi lebar
                        doc.content[1].table.widths =
                            Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: 'Export Excel',
                    exportOptions: {
                        // Ekspor semua kolom KECUALI kolom pertama (indeks 0)
                        columns: [1, 2, 3, 4, 5, 6, 7, 8], // sesuaikan dengan jumlah kolom kamu

                        // Format data untuk hilangkan pemisah ribuan
                        format: {
                            body: function(data, row, column, node) {
                                // Contoh: tambahkan kembali pemisah ribuan untuk kolom tertentu


                                // Kolom 6 (jika mengandung <span class="uang">...) kita tangani secara khusus
                                if (column === 5) {
                                    const temp = $('<div>').html(data);
                                    let result = [];

                                    temp.find('span.uang').each(function() {
                                        const label = $(this).find('span').eq(0).text().trim();
                                        const value = $(this).find('span').eq(1).text().trim();
                                        result.push(`${label} ${value}`);
                                    });

                                    return result.join(', ');
                                }

                                return data;
                            }
                        }
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
<script>
    $(function() {
        let isDown = false;
        let startX, scrollLeft;

        const slider = $('#table-wrapper');

        slider.on('mousedown', function(e) {
            isDown = true;
            slider.css('cursor', 'grabbing');
            startX = e.pageX - slider.offset().left;
            scrollLeft = slider.scrollLeft();
        });

        slider.on('mouseleave mouseup', function() {
            isDown = false;
            slider.css('cursor', 'grab');
        });

        slider.on('mousemove', function(e) {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offset().left;
            const walk = (x - startX) * 1; // kecepatan drag
            slider.scrollLeft(scrollLeft - walk);
        });
    });
</script>
<?php echo  $this->include("template/footer_v"); ?>