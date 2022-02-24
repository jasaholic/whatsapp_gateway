<?php
include_once("helper/koneksi.php");
include_once("helper/function.php");

header('content-type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
file_put_contents('whatsapp.txt', '[' . date('Y-m-d H:i:s') . "]\n" . json_encode($data) . "\n\n", FILE_APPEND);
$nomor =   preg_replace('/@c.us/', '', $data['sender']);;
$pesan = $data['msg'];

// auto reply
$msg = strtolower($pesan);
$cekautoreply = $koneksi->query("SELECT * FROM autoreply WHERE keyword = '$msg'");
if (mysqli_num_rows($cekautoreply) > 0) {
    $datareply = mysqli_fetch_assoc($cekautoreply);
    $res = $datareply['response'];
    $send = sendMSG($nomor, $res);
} else {
    // wa web
    $cek = $koneksi->query("SELECT * FROM receive_chat WHERE nomor = '$nomor' ");
    $nomorscanner = $koneksi->query("SELECT nomor FROM pengaturan")->fetch_assoc()['nomor'];
    if (mysqli_num_rows($cek) > 0) {
        $datetime = date('Y-m-d H:i:s');
        $datachat = mysqli_fetch_assoc($cek);
        $idpesan = $datachat['id_pesan'];
        $insert = $koneksi->query("INSERT INTO receive_chat VALUES (null,'$idpesan','$nomor','$pesan','0','$nomorscanner','$datetime')");
    } else {
        $datetime = date('Y-m-d H:i:s');
        $id = rand(1111, 9999);
        $insert = $koneksi->query("INSERT INTO receive_chat VALUES (null,'$id','$nomor','$pesan','0','$nomorscanner','$datetime')");
    }
}
