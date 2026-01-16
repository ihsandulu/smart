<?php
echo $this->include("template/headersaja_v");
function terbilang($angka)
{
    $angka = abs($angka);
    $huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];

    if ($angka < 12) {
        return $huruf[$angka];
    } elseif ($angka < 20) {
        return $huruf[$angka - 10] . " Belas";
    } elseif ($angka < 100) {
        return $huruf[floor($angka / 10)] . " Puluh " . terbilang($angka % 10);
    } elseif ($angka < 200) {
        return "Seratus " . terbilang($angka - 100);
    } elseif ($angka < 1000) {
        return $huruf[floor($angka / 100)] . " Ratus " . terbilang($angka % 100);
    } elseif ($angka < 2000) {
        return "Seribu " . terbilang($angka - 1000);
    } elseif ($angka < 1000000) {
        return terbilang(floor($angka / 1000)) . " Ribu " . terbilang($angka % 1000);
    } elseif ($angka < 1000000000) {
        return terbilang(floor($angka / 1000000)) . " Juta " . terbilang($angka % 1000000);
    } else {
        return "Angka Terlalu Besar";
    }
}


?>
<style>
    .resumeg {
        background-color: aqua !important;
        color: white !important;
    }

    .resumer {
        background-color: indianred !important;
        color: white !important;
    }

    .resumey {
        background-color: beige !important;
        color: white !important;
    }

    .resumebl {
        background-color: aquamarine !important;
        color: white !important;
    }

    .resumeb {
        background-color: darkgrey !important;
        color: white !important;
    }

    td {
        line-height: 20px !important;
        padding: 10px !important;
    }

    .garis {
        height: 0px;
        border-bottom: rgba(57, 56, 56, 0.16) solid 1px;
        margin: 0px 20px 0px -10px !important;
    }


    .scroll-table {
        overflow: hidden;
        cursor: grab;
    }

    .scroll-table:active {
        cursor: grabbing;
    }

    .table-wrapper {
        overflow: auto;
        white-space: nowrap;
    }

    table {
        min-width: 500px;
    }

    .row1 {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        margin-right: 0px;
        margin-left: 0px;
    }

    @media (min-width: 768px) {
        .col10 {
            -ms-flex: 0 0 83.333333%;
            flex: 0 0 83.333333%;
            max-width: 83.333333%;
        }

        .col2 {
            -ms-flex: 0 0 16.666667%;
            flex: 0 0 16.666667%;
            max-width: 16.666667%;
        }
    }

    @media (max-width: 767px) {
        .header {
            position: relative;
            width: 100%;
        }
    }

    @media print {
        .col10 {
            display: inline-block;
            width: 83.333333%;
        }

        .col2 {
            display: inline-block;
            width: 16.666667%;
        }

        .row1 {
            display: flex;
            flex-wrap: wrap;
        }
    }

    #logotop {
        width: auto;
        height: 100px;
    }

    #perusahaan {
        font-size: 28px;
        font-weight: bold;
    }

    #tagline {
        font-size: 20px;
        font-weight: bold;
        color: grey;
    }

    .uang {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        text-align: left;
    }

    .border-bottom {
        border-bottom: black solid 1px;
        padding-bottom: 30px;
    }

    .judul {
        font-size: 25px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .atas {
        height: 3.5cm;
    }

    .tengah {
        padding: 10px 50px 20px 50px;
    }
</style>

<div class='container-fluid'>
    <div class='row'>
        <div class='col-12 border-bottom atas'>
            <div class="row">
                <div class='col-4 text-right'>
                    <?php
                    // $identitypicture=session()->get("identity_logo");
                    $identity_name = session()->get("identity_name");
                    $identity = $this->db->table("identity")->get()->getRow();
                    $identitypicture = $identity->identity_logo;
                    $identity_company = $identity->identity_company;
                    if ($identitypicture != "") {
                        $user_image = "images/identity_logo/" . $identitypicture . "?" . time();
                    } else {
                        $user_image = "images/identity_logo/no_image.png";
                    } ?>
                    <img id="logotop" src="<?= base_url($user_image); ?>" alt="homepage" class="dark-logo" />
                </div>
                <div class='col-8 text-left'>
                    <div id="perusahaan"><?= ($identity_company != "") ? $identity_company : "PT. Nevan Kirana Logistik"; ?></div>
                    <div id="tagline">PROJECT CARGO - INLAND TRUCK - CARGO MOOVING - CONTAINERIZED</div>
                </div>
            </div>
        </div>

        <?php
        $job = $this->db->table("job")
            ->join("customer", "customer.customer_id = job.customer_id", "left")
            ->join("(SELECT user_id AS supervisi_id, user_nama as supervisi_name, user_wa as supervisi_phone from user)AS supervisi", "supervisi.supervisi_id = job.job_supervisi", "left")
            ->where("job_id", $_GET["job_id"])
            ->get()
            ->getRow();
        ?>
        <div class='col-12 tengah'>
            <div class="row">
                <div class="col-12 judul text-center">Surat Jalan</div>
                <div class="col-7">
                    <div class="row">
                        <div class="col-3">
                            Kepada Yth
                        </div>
                        <div class="col-9">
                            : <b><?= $job->job_kepada; ?></b><br/>
                            <?= $job->job_kepadaaddress; ?>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="row">
                        <div class="col-4">
                            No. Polisi
                        </div>
                        <div class="col-8">
                            : <?= $job->job_nopol; ?>
                        </div>
                        <div class="col-4">
                            Pengirim
                        </div>
                        <div class="col-8">
                            : <?= $job->job_handover; ?>
                        </div>
                        <div class="col-4">
                            DA Number
                        </div>
                        <div class="col-8">
                            : <?= $job->job_dano; ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-2">
                    <div class="scroll-table">
                        <div class="table-wrapper" id="drag-scroll">
                            <div id="areaCetak">
                                <table id="" class="display nowrap table table-hover table-striped " cellspacing="0" width="100%">
                                    <!-- <table id="dataTable" class="table table-condensed table-hover w-auto dtable"> -->
                                    <thead class="">
                                        <tr>
                                            <th>No.</th>
                                            <th>Description</th>
                                            <th>Methode</th>
                                            <th>Qty</th>
                                            <th>Satuan</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $jobd = $this->db->table("jobd")
                                            ->where("job_temp", $_GET["temp"])->get();
                                        // echo $this->db->getLastQuery();
                                        $no = 1;
                                        foreach ($jobd->getResult() as $jobd) { ?>
                                            <tr>
                                                <td class="text-left"><?= $no++; ?></td>
                                                <td class="text-left"><?= $jobd->jobd_descgood; ?></td>
                                                <td class="text-left"><?= $jobd->jobd_methode; ?></td>
                                                <td class="text-center"><?= number_format($jobd->jobd_qty, 3, ",", "."); ?> <?= $jobd->jobd_satuan; ?></td>
                                                <td>
                                                    <span class="uang"><span>IDR</span><span><?= number_format($jobd->jobd_sell, 2, ",", "."); ?></span></span>
                                                </td>
                                                <td>
                                                    <span class="uang"><span>IDR</span><span><?= number_format($jobd->jobd_total, 2, ",", "."); ?></span></span>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <i class="col-12 mt-3 fst-italic">
                    Isi dalam kemasan/peti tidak kami periksa.
                </i>



                <div class="col-12 row">
                    <div class="col-3 text-center">
                        <div class="col-12 mt-2 text-center">
                            Shipper
                        </div>
                        <div class="col-12 text-center" style="width:auto;height:70px;">

                        </div>
                        <div class="col-12 text-center">
                            <?= $job->customer_name; ?><br/>
                            (<?= $job->customer_phone; ?>)
                        </div>
                    </div>
                    <div class="col-3 text-center">
                        <div class="col-12 mt-2 text-center">
                            Transporter
                        </div>
                        <div class="col-12 text-center" style="width:auto;height:70px;">

                        </div>
                        <div class="col-12 text-center">
                            <?= $job->job_pengemudi; ?><br/>
                            (<?= $job->job_pengemudiphone; ?>)
                        </div>
                    </div>
                    <div class="col-3 text-center">
                        <div class="col-12 mt-2 text-center">
                            Received
                        </div>
                        <div class="col-12 text-center" style="width:auto;height:70px;">

                        </div>
                        <div class="col-12 text-center">
                            <?= $job->job_tujuan; ?><br/>
                            (<?= $job->job_tujuanphone; ?>)
                        </div>
                    </div>
                    <div class="col-3 text-center">
                        <div class="col-12 mt-2 text-center">
                            Supervisi
                        </div>
                        <div class="col-12 text-center">
                            <img style="width:auto;height:70px;" src="<?= base_url("images/identity_stempelsj/" . $this->session->get("identity_stempelsj")); ?>" />
                        </div>
                        <div class="col-12 text-center">
                            <?= $job->supervisi_name; ?><br/>
                            (<?= $job->supervisi_phone; ?>)
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <div class="row">
                        <b class="col-2 ">
                            Head office
                        </b>
                        <div class="col-10">
                            : <?= $this->session->get("identity_address"); ?>

                            <div class="row">
                                <div class="col-12">
                                    Telp
                                    : <?= $this->session->get("identity_phone"); ?>
                                </div>
                                <div class="col-12">
                                    Email
                                    : <?= $this->session->get("identity_email"); ?>
                                </div>
                                <div class="col-12">
                                    Website
                                    : <?= $this->session->get("identity_website"); ?>
                                </div>
                            </div>
                        </div>
                        <b class="col-2">
                            Branch office
                        </b>
                        <div class="col-10">
                            : <?= $this->session->get("identity_branch"); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.select').select2();
    var title = "Tarif <?= $this->session->get("identity_name"); ?>";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>

<?php echo $this->include("template/footersaja_v"); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script type="text/javascript">
    function cetakPDF() {
        $('#titlepdf').show();
        $('#btncetakpdf').hide();
        $('.dataTables_filter').hide();
        const element = document.getElementById('areaCetak');
        html2pdf()
            .set({
                margin: 0.5,
                filename: 'export.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'a4',
                    orientation: 'portrait'
                }
            })
            .from(element)
            .save()
            .then(() => {
                // Tampilkan kembali search
                $('#titlepdf').hide();
                $('.dataTables_filter').show();
                $('#btncetakpdf').show();
            });
    }

    $(document).ready(function() {
        $('#titlepdf').hide();
        $('#example2310').DataTable({
            paging: false, // tidak ada pagination
            info: false, // tidak ada info "Showing 1 to 10 of ..."
            lengthChange: false, // tidak ada dropdown jumlah baris
            searching: true
        });
    });
    print();
</script>