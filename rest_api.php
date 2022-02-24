<?php
include_once("helper/koneksi.php");
include_once("helper/function.php");
$login = cekSession();
if ($login == 0) {
    redirect("login.php");
}

if (post("callback")) {
    $callback = post("callback");
    mysqli_query($koneksi, "UPDATE pengaturan SET callback = '$callback' WHERE id='1'");
    toastr_set("success", "Sukses edit callback");
}

if (get("act") == "cn") {
    mysqli_query($koneksi, "UPDATE pengaturan SET callback = NULL WHERE id='1'");
    toastr_set("success", "Sukses menonaktifkan callback");
    redirect("rest_api.php");
}


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Wa Blast - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" />
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Wa Blast</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="waweb/index.php" target="_blank">
                    <i class="fab fa-whatsapp"></i>
                    Whatsapp Web <span class="badge badge-success" style="font-size:50%">NEW</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="auto_reply.php">
                    <i class="fas fa-reply-all"></i>
                    Auto-reply <span class="badge badge-success" style="font-size:50%">NEW</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="nomor.php">
                    <i class="fas fa-fw fa-phone-alt"></i>
                    <span>Data Nomor</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="kirim.php">
                    <i class="fas fa-fw fa-comments"></i>
                    <span>Kirim Masal</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="tes_kirim.php">
                    <i class="fas fa-fw fa-comment-alt"></i>
                    <span>Tes Kirim</span></a>
            </li>

            <li class="nav-item active">
                <a class="nav-link" href="rest_api.php">
                    <i class="fas fa-fw fa-code"></i>
                    <span>Rest API</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="pengaturan.php">
                    <i class="fas fa-fw fa-cogs"></i>
                    <span>Pengaturan</span></a>
            </li>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                        </li>


                        <!-- Nav Item - Messages -->

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $_SESSION['username'] ?></span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">WEBHOOK CHAT


                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form action="" method="post">
                                        <!-- <input type="text" name="callback" placeholder="URL Callback Handler" class="form-control" value="<?= $c ?>">
                                        <br>
                                        <button class="btn btn-success btn-block" type="submit">Simpan</button> -->

                                    </form>
                                    <hr>
                                    <p> Webhook callback adalah fitur untuk mengirim notifikasi pesan masuk ke aplikasi lain</p>
                                    <p> Kami akan mengirimkan request berupa JSON dengan method <span class="badge badge-primary">POST</span> yang kami kirim adalah <span class="text-info">number</span> dan <span class="text-info">message</span> Berikut contoh code sample webhook responder atau download file <a href="">disini</a> (PHP)
                                        <span class="text-danger">*pastikan sudah setting link webhook di file index.js</span>
                                    </p>
                                    <img src="img/webhok.png" alt="" class="img-fluid">


                                </div>
                            </div>
                            <br>
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">WEBHOOK GROUP


                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form action="" method="post">
                                        <!-- <input type="text" name="callback" placeholder="URL Callback Handler" class="form-control" value="<?= $c ?>">
                                        <br>
                                        <button class="btn btn-success btn-block" type="submit">Simpan</button> -->

                                    </form>
                                    <hr>
                                    <p> Webhook group adalah fitur untuk mengirim notifikasi join/left group ke aplikasi lain bisa digunakan untuk mengucapkan selamat tinggal otomatis jika mantan keluar grup hehe</p>
                                    <p> Kami akan mengirimkan request berupa JSON dengan method <span class="badge badge-primary">POST</span> yang kami kirim adalah <span class="text-info">number , id group</span> <span class="text-info">dan aksi (left/join)</span> Berikut contoh code sample webhook group atau download file <a href="">disini</a> (PHP)
                                        <span class="text-danger">*pastikan sudah setting link webhook group di file index.js</span>
                                    </p>
                                    <img src="img/hookgroup.png" alt="" class="img-fluid">


                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- DataTales Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">REST API</h6>
                                </div>
                                <div class="card-body">


                                    <br>
                                    <h5>=== Send Message (METHOD POST) ===</h5>
                                    <label> <b>Endpoint</b> <span class="badge badge-primary">POST</span> </label>
                                    <input type="text" class="form-control" value="<?= $base_url ?>v2/send-message" readonly>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label> <b>Parameter (JSON)</b> </label>
                                            <br>
                                            <p class="text-muted">*Number bisa diganti dengan id group</p>
                                            <img src="img/parameter-send-message.png" alt="" class="img-fluid">
                                        </div>
                                        <div class="col-md-6">
                                            <label> <b>Response (JSON)</b> </label>
                                            <br>
                                            <img src="img/respons_send_message.png" alt="" class="img-fluid">
                                        </div>
                                    </div>

                                    <br>

                                    <h5>=== Send Message (Terjadwal) ===</h5>
                                    <label> <b>Endpoint</b> <span class="badge badge-primary">POST</span> </label>
                                    <input type="text" class="form-control" value="<?= $base_url ?>api/send_jadwal.php" readonly>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label> <b>Parameter (JSON)</b> </label>
                                            <br>
                                            <img src="img/send_jadwal.png" alt="" class="img-fluid">
                                        </div>
                                        <div class="col-md-6">
                                            <label> <b>Response (JSON)</b> </label>
                                            <br>
                                            <img src="img/respon.png" alt="" class="img-fluid">
                                        </div>
                                    </div>

                                    <br>

                                    <h5>=== Send Media ===</h5>
                                    <label> <b>Endpoint</b> <span class="badge badge-primary">POST</span> </label>
                                    <input type="text" class="form-control" value="<?= $base_url ?>v2/send-media" readonly>
                                    <br>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-12">
                                            <div class="card" style="border: 1px solid blue;">
                                                <div class="card-body">
                                                    <h4 class="header-title">
                                                        <b>
                                                            <font class="text-primary">Mengirim media</font>
                                                        </b>
                                                    </h4>
                                                    <hr>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped">
                                                            <tbody>
                                                                <tr>
                                                                    <th style="width:25%">METHOD</th>
                                                                    <th style="width:75%">POST</th>
                                                                </tr>
                                                                <tr>
                                                                    <th style="width:25%">URL</th>
                                                                    <td style="width:75%">
                                                                        http://domainanda/v2/send-media
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="width:25%">number</th>
                                                                    <td style="width:75%">0812xxxxxxx</td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="width:25%">filetype</th>
                                                                    <td style="width:75%">jpg/gif/png/mp3/pdf/docx/doc </td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="width:25%">url</th>
                                                                    <td style="width:75%">link File/media </td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="width:25%">caption</th>
                                                                    <td style="width:75%">Diisi jika kirim gambar </td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="width:25%">Filename</th>
                                                                    <td style="width:75%">Diisi jika kirim document (pdf/docx) </td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="width:25%">voice</th>
                                                                    <td style="width:75%">Diisi jika kirim audio true/false</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-5">
                                        <div class="card col-12">
                                            <div class="card-body">
                                                <b>Contoh</b>
                                                <div class="alert alert-secondary bg-primary">
                                                    <pre class="text-light">
$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($POSTNYA));
curl_setopt($ch, CURLOPT_URL, 'link');
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$result = curl_exec($ch);
curl_close($ch);
print json_decode($result, true);


Hasil yang di terima :
result
 array(2) {
     ["status"]=>
     bool(true)
     ["response"]=>
     array(4) {
       ["key"]=>
       array(3) {
         ["remoteJid"]=>
         string(28) "6289522811620@s.whatsapp.net"
         ["fromMe"]=>
         bool(true)
         ["id"]=>
         string(12) "3EB037A030D7"
       }
       ["message"]=>
       array(1) {
         ["extendedTextMessage"]=>
         array(1) {
           ["text"]=>
           string(14) "tes wa via php"
         }
       }
       ["messageTimestamp"]=>
       string(10) "1610885761"
       ["status"]=>
       string(10) "SERVER_ACK"
     }
   }

</pre>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <br>



                                    <br>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; <a href="https://web.facebook.com/menz.pedia.96/">mnzcreate</a></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>
    <script>
        <?php

        toastr_show();

        ?>
    </script>
</body>

</html>