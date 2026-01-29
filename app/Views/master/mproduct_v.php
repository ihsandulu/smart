<?php echo $this->include("template/header_v"); ?>
<style>
    th,
    td {
        padding: 5px !important;
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
                                <form action="<?= base_url("product"); ?>" method="get" class="col-md-2">
                                    <h1 class="page-header col-md-12">
                                        <button class="btn btn-warning btn-block btn-lg" value="OK" style="">Back</button>
                                    </h1>
                                </form>
                            <?php } ?>
                            <form method="post" class="col-md-2">
                                <h1 class="page-header col-md-12">
                                    <button name="new" class="btn btn-info btn-block btn-lg" value="OK" style="">New</button>
                                    <input type="hidden" name="product_id" />
                                </h1>
                            </form>
                        <?php } ?>
                    </div>

                    <?php if (isset($_POST['new']) || isset($_POST['edit'])) { ?>
                        <div class="">
                            <?php if (isset($_POST['edit'])) {
                                $product_namabutton = 'name="change"';
                                $judul = "Update Karyawan";
                                $ketproduct_password = "Kosongkan jika tidak ingin merubah product_password!";
                            } else {
                                $product_namabutton = 'name="create"';
                                $judul = "Tambah Karyawan";
                                $ketproduct_password = "Jangan dikosongkan!";
                            } ?>
                            <div class="lead">
                                <h3><?= $judul; ?></h3>
                            </div>
                            <form class="form-horizontal" method="post" enctype="multipart/form-data">


                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="product_name">Nama Product:</label>
                                    <div class="col-sm-10">
                                        <input required type="text" class="form-control" id="product_name" name="product_name" placeholder="" value="<?= $product_name; ?>">

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="product_description">Deskripsi:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="product_description" name="product_description" placeholder="<?= $product_description; ?>" value="<?= $product_description; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="category_id">Kategori:</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="category_id" name="category_id">
                                            <option value="0" <?= ($category_id == 0) ? "selected" : ""; ?>>Pilih Kategori</option>
                                            <?php $category = $this->db->table('category')->orderBy('category_name', 'ASC')->get();
                                            foreach ($category->getResult() as $cat) { ?>
                                                <option value="<?= $cat->category_id; ?>" <?= ($category_id == $cat->category_id) ? "selected" : ""; ?>><?= $cat->category_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="product_sell">Harga:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="product_sell" name="product_sell" placeholder="" value="<?= $product_sell; ?>">

                                    </div>
                                </div>



                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="product_waktu">Masa Berlaku:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="product_waktu" name="product_waktu" placeholder="" value="<?= $product_waktu; ?>">
                                        <select class="form-control" id="product_masa" name="product_masa">
                                            <option value="days" <?= ($product_masa == "days") ? "selected" : ""; ?>>Hari</option>
                                            <option value="months" <?= ($product_masa == "months") ? "selected" : ""; ?>>Bulan</option>
                                            <option value="years" <?= ($product_masa == "years") ? "selected" : ""; ?>>Tahun</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="product_youtube">Youtube:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="product_youtube" name="product_youtube" placeholder="" value="<?= $product_youtube; ?>">

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="product_devicenum">Batasan Device:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="product_devicenum" name="product_devicenum" placeholder="" value="<?= $product_devicenum; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="product_picture">Gambar</label>
                                    <div class="col-sm-12">
                                        <input type="file" class="form-control" id="product_picture" name="product_picture" placeholder="" value="<?= $product_picture; ?>">
                                        <?php if ($product_picture != "") {
                                            $user_image = "images/product_picture/" . $product_picture;
                                        } else {
                                            $user_image = "images/product_picture/no_image.png";
                                        } ?>
                                        <img id="product_picture_image" width="100" height="100" src="<?= base_url($user_image); ?>" />
                                        <script>
                                            function readURL(input) {
                                                if (input.files && input.files[0]) {
                                                    var reader = new FileReader();

                                                    reader.onload = function(e) {
                                                        $('#product_picture_image').attr('src', e.target.result);
                                                    }

                                                    reader.readAsDataURL(input.files[0]);
                                                }
                                            }

                                            $("#product_picture").change(function() {
                                                readURL(this);
                                            });
                                        </script>
                                    </div>
                                </div>

                                <input type="hidden" name="product_id" value="<?= $product_id; ?>" />
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" id="submit" class="btn btn-primary col-md-5" <?= $product_namabutton; ?> value="OK">Submit</button>
                                        <a class="btn btn-warning col-md-offset-1 col-md-5" href="<?= base_url("mproduct"); ?>">Back</a>
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
                            <table id="tabelk" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead class="">
                                    <tr>
                                        <?php if (!isset($_GET["report"])) { ?>
                                            <th>&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;</th>
                                        <?php } ?>
                                        <th>No.</th>
                                        <th>Product</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $usr = $this->db
                                        ->table("product")
                                        ->orderBy("product_name", "asc")
                                        ->get();
                                    //echo $this->db->getLastquery();
                                    $no = 1;
                                    foreach ($usr->getResult() as $usr) { ?>
                                        <tr>
                                            <?php if (!isset($_GET["report"])) { ?>
                                                <td style="padding-left:0px; padding-right:0px;">
                                                    <!-- <?php
                                                            if (
                                                                (
                                                                    isset(session()->get("position_id")[0][0])
                                                                    && (
                                                                        session()->get("position_id") == "1"
                                                                        || session()->get("position_id") == "2"
                                                                    )
                                                                ) ||
                                                                (
                                                                    isset(session()->get("halaman")['124']['act_read'])
                                                                    && session()->get("halaman")['124']['act_read'] == "1"
                                                                )
                                                            ) { ?>
                                                    <form method="get" class="btn-action" style="" action="<?= base_url("mproductposition"); ?>">
                                                        <button class="btn btn-sm btn-primary "><span class="fa fa-products" style="color:white;"></span> </button>
                                                        <input type="hidden" name="product_id" value="<?= $usr->product_id; ?>" />
                                                    </form>
                                                    <?php } ?> -->

                                                    <?php
                                                    if (
                                                        (
                                                            isset(session()->get("position_id")[0][0])
                                                            && (
                                                                session()->get("position_id") == "1"
                                                                || session()->get("position_id") == "2"
                                                            )
                                                        ) ||
                                                        (
                                                            isset(session()->get("halaman")['124']['act_update'])
                                                            && session()->get("halaman")['124']['act_update'] == "1"
                                                        )
                                                    ) { ?>
                                                        <form method="post" class="btn-action" style="">
                                                            <button class="btn btn-sm btn-warning " name="edit" value="OK"><span class="fa fa-edit" style="color:white;"></span> </button>
                                                            <input type="hidden" name="product_id" value="<?= $usr->product_id; ?>" />
                                                        </form>
                                                    <?php } ?>

                                                    <?php
                                                    if (
                                                        (
                                                            isset(session()->get("position_id")[0][0])
                                                            && (
                                                                session()->get("position_id") == "1"
                                                                || session()->get("position_id") == "2"
                                                            )
                                                        ) ||
                                                        (
                                                            isset(session()->get("halaman")['124']['act_delete'])
                                                            && session()->get("halaman")['124']['act_delete'] == "1"
                                                        )
                                                    ) { ?>
                                                        <form method="post" class="btn-action" style="">
                                                            <button class="btn btn-sm btn-danger delete" onclick="return confirm(' you want to delete?');" name="delete" value="OK"><span class="fa fa-close" style="color:white;"></span> </button>
                                                            <input type="hidden" name="product_id" value="<?= $usr->product_id; ?>" />
                                                        </form>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>
                                            <td><?= $no++; ?></td>
                                            <td class="text-left"><?= $usr->product_name; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.select').select2();
    var title = "Master Product";
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