<?php echo $this->include("template/header_v"); ?>
<style>
    th,
    td {
        padding: 5px !important;
    }

    .antam-card {
        border-radius: 16px;
    }

    .antam-content {
        border-radius: 16px;
        overflow: hidden;
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .antam-card:hover .antam-content {
        transform: translateY(-6px);
        box-shadow: 0 14px 30px rgba(0, 0, 0, .15);
    }

    /* ACTION BUTTON */
    .antam-actions {
        position: absolute;
        top: 10px;
        right: 10px;
        display: flex;
        gap: 6px;
        opacity: 0;
        z-index: 5;
        transition: opacity .2s ease;
    }

    .antam-card:hover .antam-actions {
        opacity: 1;
    }

    .antam-actions form {
        margin: 0;
    }

    .antam-actions .btn {
        width: 34px;
        height: 34px;
        padding: 0;
        border-radius: 50%;
    }
</style>
<div class='container-fluid'>
    <div class='row'>
        <div class='col-12'>
            <div class="card">
                <div class="card-body">


                    <div class="row">
                        <?php if (!isset($_GET['id']) && !isset($_POST['new']) && !isset($_POST['edit'])) {
                            $coltitle = "col-md-10";
                        } else {
                            $coltitle = "col-md-8";
                        } ?>
                        <div class="<?= $coltitle; ?>">
                            <h4 class="card-title"></h4>
                            <!-- <h6 class="card-subtitle">Export data to Copy, CSV, Excel, PDF & Print</h6> -->
                        </div>
                        <?php if (!isset($_POST['new']) && !isset($_POST['edit']) && !isset($_GET['report'])) { ?>
                            <?php if (isset($_GET["id"])) { ?>
                                <form action="<?= base_url("userantam"); ?>" method="get" class="col-md-2">
                                    <h1 class="page-header col-md-12">
                                        <button class="btn btn-warning btn-block btn-lg" value="OK" style="">Back</button>
                                    </h1>
                                </form>
                            <?php } ?>
                            <form method="post" class="col-md-2">
                                <h1 class="page-header col-md-12">
                                    <button name="new" class="btn btn-info btn-block btn-lg" value="OK" style="">New</button>
                                    <input type="hidden" name="userantam_id" />
                                </h1>
                            </form>
                        <?php } ?>
                    </div>

                    <?php if (isset($_POST['new']) || isset($_POST['edit'])) { ?>
                        <div class="">
                            <?php if (isset($_POST['edit'])) {
                                $userantam_namabutton = 'name="change"';
                                $judul = "Update Karyawan";
                                $ketuserantam_password = "Kosongkan jika tidak ingin merubah userantam_password!";
                            } else {
                                $userantam_namabutton = 'name="create"';
                                $judul = "Tambah Karyawan";
                                $ketuserantam_password = "Jangan dikosongkan!";
                            } ?>
                            <div class="lead">
                                <h3><?= $judul; ?></h3>
                            </div>
                            <form class="form-horizontal" method="post" enctype="multipart/form-data">


                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="userantam_username">Username:</label>
                                    <div class="col-sm-10">
                                        <input required type="text" class="form-control" id="userantam_username" name="userantam_username" placeholder="" value="<?= $userantam_username; ?>">

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="userantam_password">Password:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="userantam_password" name="userantam_password" placeholder="<?= $userantam_password; ?>" value="<?= $userantam_password; ?>">
                                    </div>
                                </div>

                                <input type="hidden" name="userantam_id" value="<?= $userantam_id; ?>" />
                                <input type="hidden" name="user_id" value="<?= session()->get("user_id"); ?>" />
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" id="submit" class="btn btn-primary col-md-5" <?= $userantam_namabutton; ?> value="OK">Submit</button>
                                        <a class="btn btn-warning col-md-offset-1 col-md-5" href="<?= base_url("antam"); ?>">Back</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php } else { ?>
                        <?php if ($message != "") { ?>
                            <div class="alert alert-info alert-dismissable">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong><?= $message; ?></strong>
                            </div>
                        <?php } ?>

                        <div class="table-responsive m-t-40">
                            <div class="row g-4">
                                <?php
                                $userantam = $this->db->table('userantam')
                                    ->orderBy('userantam_username', 'ASC')
                                    ->get();

                                foreach ($userantam->getResult() as $p) {
                                ?>
                                    <div class="col-6 col-md-3">
                                        <div class="antam-card position-relative">

                                            <!-- ACTION BUTTON -->
                                            <div class="antam-actions">
                                                <form method="post">
                                                    <input type="hidden" name="userantam_id" value="<?= $p->userantam_id; ?>">
                                                    <button type="submit" class="btn btn-light btn-sm" name="edit" title="Edit">
                                                        ✏️
                                                    </button>
                                                </form>

                                                <form method="post" class="btn-action" style="">
                                                    <button class="btn btn-sm btn-danger shadow-sm delete" onclick="return confirm(' you want to delete?');" name="delete" value="OK">🗑️</button>
                                                    <input type="hidden" name="userantam_id" value="<?= $p->userantam_id; ?>" />
                                                </form>
                                            </div>

                                            <!-- CARD -->
                                            <div class="card shadow-sm antam-content text-center">
                                                <img src="images/global/user.png"
                                                    class="card-img-top"
                                                    alt="<?= esc($p->userantam_username); ?>">
                                                <div class="card-body">
                                                    <h6 class="mb-0">Username : <?= esc($p->userantam_username); ?></h6>
                                                    <h6 class="mb-0">Password : <?= esc($p->userantam_password); ?></h6>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                <?php } ?>
                            </div>


                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.select').select2();
    var title = "User Antam";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
    $(document).ready(function() {
        $('#tabelk').DataTable({
            dom: 'Blfrtip', // <- tambahkan 'l' di sini
            buttons: [{
                    extend: 'print',
                    exportOptions: {
                        columns: ':not(:first-child)'
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':not(:first-child)'
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':not(:first-child)'
                    }
                }
            ],
            ordering: false,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            pageLength: 10
        });
    });
</script>

<?php echo  $this->include("template/footer_v"); ?>