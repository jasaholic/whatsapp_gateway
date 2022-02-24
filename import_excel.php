<?php
include_once("helper/koneksi.php");
include_once("helper/function.php");
include_once("lib/excel.php");


if (!empty($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
    // Be sure we're dealing with an upload
    if (is_uploaded_file($_FILES['file']['tmp_name']) === false) {
        throw new \Exception('Error on upload: Invalid file definition');
    }

    // Rename the uploaded file
    $uploadName = $_FILES['file']['name'];
    $ext = strtolower(substr($uploadName, strripos($uploadName, '.') + 1));

    $allow = ['xls', 'xlsx'];
    if (in_array($ext, $allow)) {
        if ($ext == "xls") {
            $filename = round(microtime(true)) . mt_rand() . '.xls';
        }

        if ($ext == "xlsx") {
            $filename = round(microtime(true)) . mt_rand() . '.xlsx';
        }
    } else {
        toastr_set("error", "Format xls, xlsx only");
        redirect("kirim.php");
        exit;
    }

    move_uploaded_file($_FILES['file']['tmp_name'], 'excel/' . $filename);
    // Insert it into our tracking along with the original name
    $file = "excel/" . $filename;
} else {
    $file = null;
}


if ($file == null) {
    toastr_set("error", "Format file tidak sesuai");
    redirect("nomor.php");
    exit;
}

$a = post("a");
$b = post("b");
$c = post("c");
$d = post("d");

if ($a && $b && $c && $file) {
    $a = $a - 1;
    $b = $b - 1;
    $c = $c - 1;
    $d = $d - 1;

    if ($xlsx = SimpleXLSX::parse($file)) {

        $i = 0;

        foreach ($xlsx->rows() as $elt) {
            if ($i >= $a) {
                $nama = $elt[$b];
                $nomor = $elt[$c];
                $pesan = $elt[$d];
                $u = $_SESSION['username'];


                $count = countDB("nomor", "nomor", $nomor);

                if ($count == 0) {
                    $q = mysqli_query($koneksi, "INSERT INTO nomor(`nama`, `nomor`,`pesan`, `make_by`)
                        VALUES('$nama', '$nomor','$pesan', '$u')");
                }
            }
            $i++;
        }
    } else {
        toastr_set("error", "Kesalahan parsing file");
        redirect("nomor.php");
        exit;
    }

    toastr_set("success", "Sukses input nomor");
    redirect("nomor.php");
}
