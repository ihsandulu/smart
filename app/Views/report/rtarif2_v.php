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
        white-space: nowrap;
    }
</style>
<div class="header">
    <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?= base_url("rtarif"); ?>">
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
            </a>
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
                        <?php if (!isset($_POST['new']) && !isset($_POST['edit']) && !isset($_GET['report'])) { ?>
                            <?php if (isset($_GET["user_id"])) { ?>
                                <form action="<?= base_url("user"); ?>" method="get" class="col-md-2">
                                    <h1 class="page-header col-md-12">
                                        <button class="btn btn-warning btn-block btn-lg" value="OK" style="">Back</button>
                                    </h1>
                                </form>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="scroll-table">
                                <div class="table-wrapper" id="drag-scroll">
                                    <table id="example2310" class="display nowrap table table-hover table-striped " cellspacing="0" width="100%">
                                        <!-- <table id="dataTable" class="table table-condensed table-hover w-auto dtable"> -->
                                        <thead class="">
                                            <tr>
                                                <th>Asal</th>
                                                <th>Tujuan</th>
                                                <th>Satuan</th>
                                                <th>Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $usr = $this->db
                                                ->table("tarif")
                                                ->join("satuantarif", "satuantarif.satuantarif_id = tarif.satuantarif_id")
                                                ->join("origin", "origin.origin_id = tarif.origin_id")
                                                ->join("destination", "destination.destination_id = tarif.destination_id")
                                                ->orderBy("tarif_id", "DESC")
                                                ->get();
                                            //echo $this->db->getLastquery();

                                            $no = 1;
                                            $atarif = array();

                                            // Proses pengelompokan data
                                            foreach ($usr->getResult() as $data) {?>
                                                    <tr>
                                                        <td class="text-center"><?= strtoupper($data->origin_name); ?></td>
                                                        <td class="text-center"><?= strtoupper($data->destination_name); ?></td>                                                        
                                                        <td class="text-center"><?= strtoupper($data->satuantarif_name); ?></td>                                                      
                                                        <td class="text-center"><?= number_format($data->tarif_price, 0, ",", "."); ?></td>                                                        
                                                    </tr>
                                            <?php }?>
                                        </tbody>
                                    </table>
                                </div>
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
<script type="text/javascript">
    $(document).ready(function() {
        $('#example2310').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'copyHtml5',
                    title: 'Tarif <?= $this->session->get("identity_name"); ?>',
                    filename: 'Tarif <?= $this->session->get("identity_name"); ?> ',
                    text: 'Copy'
                },
                {
                    extend: 'csvHtml5',
                    title: 'Tarif <?= $this->session->get("identity_name"); ?>',
                    filename: 'Tarif <?= $this->session->get("identity_name"); ?> ',
                    text: 'Export to CSV'
                },
                {
                    extend: 'excelHtml5',
                    title: 'Tarif <?= $this->session->get("identity_name"); ?> Excel',
                    filename: 'Tarif <?= $this->session->get("identity_name"); ?> ',
                    text: 'Export to Excel'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Tarif <?= $this->session->get("identity_name"); ?>',
                    filename: 'Tarif <?= $this->session->get("identity_name"); ?> ',
                    text: 'Export to PDF',
                    customize: function(doc) {
                        doc.content[1].table.headerRows = 1;
                        doc.content[1].table.body[0].forEach(function(h) {
                            h.text = h.text.toUpperCase();
                            h.fillColor = '#dddddd';
                        });
                    }
                },
                {
                    extend: 'print',
                    title: 'Judul Custom',
                    text: 'Print'
                }
            ]
        });
    });
</script>