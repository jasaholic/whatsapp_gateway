<?php
include_once("helper/koneksi.php");
include_once("helper/function.php");

// Takes raw data from the request
$data = json_decode(file_get_contents('php://input'), true);

$nomor = $data['nomor'];
$pesan = $data['pesan'];
$id_pesan = $data['id_pesan']['id'];

if(isset($nomor) && isset($pesan) && isset($id_pesan)){
    $nomor = phoneToStandard($nomor);
    $tanggal = date("Y-m-d H:i:s");
    $nomor_saya = getSingleValDB("pengaturan", "id", "1", "nomor");
    $q = mysqli_query($koneksi, "INSERT INTO receive_chat(`id_pesan`, `nomor`, `pesan`, `tanggal`, `nomor_saya`)
                                VALUE('$id_pesan', '$nomor', '$pesan', '$tanggal', '$nomor_saya')");
    
    callback($id_pesan, $nomor, $pesan, $tanggal, $nomor_saya);
    autoReply($nomor, $pesan);
    echo "sukses";
}

