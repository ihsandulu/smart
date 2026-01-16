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

                    <?php if ($message != "") { ?>
                        <div class="alert alert-info alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong><?= $message; ?></strong>
                        </div>
                    <?php } ?>
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

                    </div>

                    <div class="">
                        <div class="lead">
                            <h3>Edit Profile</h3>
                        </div>
                        <form class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="user_email">Email:</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="user_email" name="user_email" placeholder="" value="<?= $user_email; ?>">

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="user_wa">Whatsapp/Phone:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="user_wa" name="user_wa" placeholder="" value="<?= $user_wa; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="user_npwp">NPWP:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="user_npwp" name="user_npwp" placeholder="" value="<?= $user_npwp; ?>">

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="user_address">Alamat:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="user_address" name="user_address" placeholder="" value="<?= $user_address; ?>">

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="user_borndate">Tgl Lahir:</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" id="user_borndate" name="user_borndate" placeholder="" value="<?= $user_borndate; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="user_borncity">Tempat Lahir:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="user_borncity" name="user_borncity" placeholder="" value="<?= $user_borncity; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-12" for="user_picture">Picture</label>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control" id="user_picture" name="user_picture" placeholder="" value="<?= $user_picture; ?>">
                                    <?php if ($user_picture != "") {
                                        $user_image = "images/user_picture/" . $user_picture;
                                    } else {
                                        $user_image = "images/user_picture/no_image.png";
                                    } ?>
                                    <img id="user_picture_image" width="100" height="100" src="<?= base_url($user_image); ?>" />
                                    <script>
                                        function readURL(input) {
                                            if (input.files && input.files[0]) {
                                                var reader = new FileReader();

                                                reader.onload = function(e) {
                                                    $('#user_picture_image').attr('src', e.target.result);
                                                }

                                                reader.readAsDataURL(input.files[0]);
                                            }
                                        }

                                        $("#user_picture").change(function() {
                                            readURL(this);
                                        });
                                    </script>
                                </div>
                            </div>

                            <input type="hidden" name="user_id" value="<?= $user_id; ?>" />
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" id="submit" class="btn btn-primary col-md-5" name="change" value="OK">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.select').select2();
    var title = "Master Karyawan";
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