<?php echo $this->include("template/headersaja_v"); ?>
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
        /* white-space: nowrap; */
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

    .f-white {
        color: white;
    }

    .f-black {
        color: black;
    }

    .judul {
        font-weight: bold;
        font-size: 17px;
        color: rgba(9, 24, 58, 0.85);
        margin-top: 30px !important;
    }

    .deskripsi {
        border: grey;
        padding: 20px;
        border-radius: 5px;
        color: rgba(9, 24, 58, 0.75);
        background-color: rgba(214, 146, 69, 0.1);
    }

    .deskripsisub {
        border: grey;
        padding: 10px;
        border-radius: 5px;
        color: rgba(9, 24, 58, 0.75);
        background-color: rgba(206, 137, 59, 0.1);
        margin-bottom: 10px;
    }
</style>
<div class="header">
    <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <div class="navbar-header">
            <div class="navbar-brand">
                <b>
                    <?php
                    // $identitypicture=session()->get("identity_logo");
                    $identity_name = session()->get("identity_name");
                    $identity = $this->db->table("identity")->get()->getRow();
                    $identitypicture = $identity->identity_logo;
                    $identity_name = $identity->identity_name;
                    if ($identitypicture != "") {
                        $user_image = "images/identity_logo/" . $identitypicture . "?" . time();
                    } else {
                        $user_image = "images/identity_logo/no_image.png";
                    } ?>
                    <img id="logotop" src="<?= base_url($user_image); ?>" alt="homepage" class="dark-logo" />
                </b>
                <span><?= ($identity_name != "") ? $identity_name : "POS"; ?></span>
            </div>
        </div>

        <div class="navbar-collapse">
            <ul class="navbar-nav mr-auto mt-md-0">
                <li class="nav-item navitem">

                </li>
                <li class="nav-item navitem m-l-10">

                </li>
            </ul>

            <ul class="navbar-nav my-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="images/global/user.png" alt="user" class="profile-pic" style="height: 20px;width:auto; margin-right:5px;" />
                        <?= $this->session->get("contact_first_name"); ?> <?= $this->session->get("contact_last_name"); ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                        <ul class="dropdown-user">
                            <li>
                                <a target="_blank" href="https://api.whatsapp.com/send?phone=6285899599167">
                                    <i class="fa fa-phone"></i> Whatsapp
                                    <br />+62 858-995-99167
                                </a>
                            </li>

                            <li>
                                <hr />
                                <div class="p-3" style="color:rgba(0,0,0,0.5);">
                                    021-4393 8026<br />
                                    Jl. Manggar Raya. No. 21, Tugu Utara, Koja - Jakarta Utara<br />
                                    cs@nevankiranalogistik.co.id<br />
                                    08:00 - 17:00
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>
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
                            <h6 class="card-subtitle">LOGISTICS PROVIDER WITH STRONG SOLUTIONS</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button id="btncetakpdf" class="btn btn-danger" onclick="cetakPDF()">Cetak PDF</button>
                            <hr />
                            <div id="areaCetak">
                                <div class="row mt-2 judul fa fa-truck"> PENGIRIMAN</div>
                                <div class="row" id="titlepdf">
                                    <?php $coltitle = "col-md-10"; ?>
                                    <div class="<?= $coltitle; ?>"><b>
                                            <?php
                                            // $identitypicture=session()->get("identity_logo");
                                            $identity_name = session()->get("identity_name");
                                            $identity = $this->db->table("identity")->get()->getRow();
                                            $identitypicture = $identity->identity_logo;
                                            $identity_name = $identity->identity_name;
                                            if ($identitypicture != "") {
                                                $user_image = "images/identity_logo/" . $identitypicture . "?" . time();
                                            } else {
                                                $user_image = "images/identity_logo/no_image.png";
                                            } ?>
                                            <img id="logotop" src="<?= base_url($user_image); ?>" alt="homepage" class="dark-logo" />
                                        </b>
                                        <h4 class="card-title"></h4>
                                        <h6 class="card-subtitle">LOGISTICS PROVIDER WITH STRONG SOLUTIONS</h6>
                                    </div>
                                </div>
                                <?php
                                //cari job_dano yg sesuai tgl
                                $build = $this->db
                                    ->table("job");
                                $cari = $build->get();
                                $arjob = array();
                                $arjobprice = array();
                                foreach ($cari->getResult() as $row) {
                                    $arjob[] = $row->job_dano;
                                    $arjobprice[$row->job_dano] = $row->job_total;
                                }
                                // dd($arjob);
                                //data payment job
                                $jobpay = array();
                                if (!empty($arjob)) {
                                    $invpayment = $this->db
                                        ->table("invpayment")
                                        ->join("invd", "invd.inv_temp = invpayment.inv_temp", "left")
                                        ->whereIn("invd.job_dano", $arjob)
                                        ->get();
                                    // echo $this->db->getLastQuery();
                                    foreach ($invpayment->getResult() as $row) {
                                        $amount = $row->invpayment_total;
                                        if (!isset($jobpay[$row->job_dano])) {
                                            $jobpay[$row->job_dano] = 0;
                                        }
                                        $jobpay[$row->job_dano] += $amount;
                                    }
                                }
                                // dd($jobpay);
                                $build = $this->db->table("job")
                                    ->select("*,job.job_dano as job_dano")
                                    // ->join("inv", "inv.job_dano=job.job_dano", "left");
                                    ->join("inv", "FIND_IN_SET(job.job_dano, REPLACE(inv.job_dano, ' ', ''))", "left");

                                // echo $build->getCompiledSelect();
                                // exit;
                                $jobb = $build->get();
                                // echo $this->db->getLastQuery();
                                $arlunas = array();
                                $arbelum = array();
                                $arinvoice = array();
                                foreach ($jobb->getResult() as $row) {
                                    $job_dano = $row->job_dano;
                                    $dibayar = isset($jobpay[$job_dano]) ? $jobpay[$job_dano] : 0;
                                    $inv_grand = isset($row->inv_grand) ? $row->inv_grand : 0;
                                    // if ($dibayar >= $row->job_total) {
                                    if (($dibayar >= $inv_grand) && $inv_grand > 0) {
                                        $arlunas[] = $job_dano;
                                        $arlunasin[$job_dano] = $dibayar . " >= " . $row->inv_grand;
                                    } else {
                                        $arbelum[] = $job_dano;
                                        $arlunasin[$job_dano] = $dibayar . " >= " . $row->inv_grand;
                                    }
                                    // echo $dibayar . " >= " . $inv_grand . "<br/>";
                                    // dd($arlunas);
                                    if ($row->inv_id) {
                                        $arinvoice[] = $job_dano;
                                    }
                                }
                                // print_r($arbelum);
                                $build = $this->db
                                    ->table("job")
                                    ->select("*,job.job_temp as job_temp, GROUP_CONCAT(jobd.jobd_descgood SEPARATOR ', ') as jobd_list,GROUP_CONCAT(jobd.jobd_koli SEPARATOR ', ') as jobd_lkoli,GROUP_CONCAT(jobd.jobd_cbm SEPARATOR ', ') as jobd_lcbm")
                                    ->join("(SELECT user_id AS supervisi_id, user_nama as supervisi_name from user)AS supervisi", "supervisi.supervisi_id = job.job_supervisi", "left")
                                    ->join("customer", "customer.customer_id = job.customer_id", "left")
                                    ->join("origin", "origin.origin_id = job.origin_id", "left")
                                    ->join("destination", "destination.destination_id = job.destination_id", "left")
                                    ->join("vendor", "vendor.vendor_id = job.vendor_id", "left")
                                    ->join("vendortruck", "vendortruck.vendortruck_id = job.vendortruck_id", "left")
                                    ->join("(SELECT vendor_id as vendor_id2, vendor_name AS vendor_name2 FROM vendor) AS v2", "v2.vendor_id2 = vendortruck.vendor_id", "left")
                                    ->join("(SELECT vendor_id as vendor_idd, vendor_name AS vendor_named FROM vendor) AS dooring", "dooring.vendor_idd = job.job_dooring", "left")
                                    ->join("service", "service.service_id = job.service_id", "left")
                                    ->join("vessel", "vessel.vessel_id = job.vessel_id", "left")
                                    ->join("jobd", "jobd.job_temp = job.job_temp", "left");;



                                $build->where("job.job_id", $_GET["job_id"]);



                                // echo $build->getCompiledSelect();
                                // exit;
                                $usr = $build
                                    ->groupBy("job.job_id") // WAJIB, biar GROUP_CONCAT jalan per job
                                    ->orderBy("job_dano", "ASC")
                                    ->get();
                                // echo $this->db->getLastquery();die;
                                $no = 1;
                                $job_total = 0;
                                $job_cost = 0;
                                $job_refund = 0;
                                $job_fee = 0;
                                $job_profit = 0;
                                $job_gp = 0;
                                $statuspickup = array("", "Done", "Pending");
                                foreach ($usr->getResult() as $usr) {
                                    switch ($usr->job_pickupstatus) {
                                        case "0":
                                            $linestatus = "";
                                            $textstatus = "text-dark";
                                            break;
                                        case "1":
                                            $linestatus = "bg-success";
                                            $textstatus = "text-dark";
                                            break;
                                        case "2":
                                            $linestatus = "bg-warning";
                                            $textstatus = "text-dark";
                                            break;
                                    }
                                    if ($usr->job_status == "DONE") {
                                        $linestatus = "bg-dark";
                                        $textstatus = "text-white";
                                    }
                                ?>
                                    <div>DA Number : <?= $usr->job_dano; ?></div>
                                    <div class="alert alert-warning row mt-2">
                                        <div class="col-md-6 col-sm-12 col-xs-12 f-black  mt-1">
                                            <!-- <b>Pengirim :</b> <br /><?= $usr->customer_name; ?> -->
                                            <b>Origin :</b> <br /><?= $usr->origin_name; ?>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12 f-black mt-1">
                                            <!-- <b>Penerima :</b> <br /><?= $usr->job_kepada; ?> -->
                                            <b>Destination :</b> <br /><?= $usr->destination_name; ?>
                                        </div>
                                    </div>

                                    <div class="row mt-2 judul fa fa-cubes"> PRODUCT INFORMATION</div>
                                    <div class="row mt-2">
                                        <?php
                                        $build = $this->db
                                            ->table("jobd");
                                        $build->where("job_temp", $job_temp);
                                        $jobd = $build->get();

                                        //echo $this->db->getLastquery();
                                        $no = 1;
                                        foreach ($jobd->getResult() as $jobd) { ?>
                                            <div class="deskripsi">
                                                <div class="deskripsisub"> <?= $jobd->jobd_descgood; ?> </div>
                                                Koli : <?= number_format($jobd->jobd_koli, 0, ",", "."); ?><br />
                                                CBM/KGS : <?= number_format($jobd->jobd_cbm, 3, ",", "."); ?>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="row mt-2 judul fa fa-plane"> TRACKING</div>
                                    <div class="row mt-2">
                                        <div class="deskripsi">
                                            <?php
                                            $build = $this->db
                                                ->table("tracking");
                                            $build->where("job_id", $_GET["job_id"])
                                                ->orderBy("tracking_date", "ASC");
                                            $tracking = $build->get();

                                            //echo $this->db->getLastquery();
                                            $no = 1;
                                            foreach ($tracking->getResult() as $tracking) { ?>
                                                <div class="deskripsisub"> <label class="label label-warning"><?= date("d-M-Y", strtotime($tracking->tracking_date)); ?></label> <br/><?= $tracking->tracking_desc; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <!-- 

                                    <div class="row mt-2 judul fa fa-hand-lizard-o"> INFO PICKUP</div>
                                    <div class="row mt-2">
                                        <div class="deskripsi">
                                            <div>Pickup Date : <?= $usr->job_pickup; ?></div>
                                            <div>Pickup State : <?= $statuspickup[$usr->job_pickupstatus]; ?></div>
                                            <div>Customer : <?= $usr->customer_name; ?></div>
                                            <div>Handover : <?= $usr->job_handover; ?></div>
                                            <div> Pickup Address : <?= $usr->job_pickupaddress; ?></div>
                                            <div> Pickup Person : <?= $usr->job_pickupusername; ?></div>
                                        </div>
                                    </div>

                                    <div class="row mt-2 judul fa fa-plane"> INFO PENGIRIMAN</div>
                                    <div class="row mt-2">
                                        <div class="deskripsi">
                                            <div> Shipment Date : <?= $usr->job_shipmentdate; ?></div>
                                            <div> Shipment State : <?= $usr->job_status; ?></div>
                                            <div>Service : <?= $usr->service_name; ?></div>
                                            <div>Truck : <?= $usr->vendortruck_name; ?> - <?= $usr->vendor_name2; ?></div>
                                            <div>Vessel : <?= $usr->vessel_name; ?></div>
                                            <div>To : <?= $usr->job_kepada; ?></div>
                                            <div>To Address : <?= $usr->job_kepadaaddress; ?></div>
                                            <div><?= $usr->job_tujuan; ?></div>
                                            <div><?= $usr->job_tujuanaddress; ?></div>
                                        </div>
                                    </div> -->
                                <?php } ?>
                            </div>
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
</script>