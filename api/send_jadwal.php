<?php

include_once("../helper/koneksi.php");
include_once("../helper/function.php");


// Takes raw data from the request
$data = json_decode(file_get_contents('php://input'), true);

$nomor = $data['nomor'];
$pesan = $data['msg'];
header('Content-Type: application/json');

$api_key = get("key");
if($api_key != api_key()){
    $ret['status'] = false;
    $ret['msg'] = "Api key salah";
    echo json_encode($ret, true);
    exit;
}

if(!isset($nomor) && !isset($pesan) && !isset($data['jadwal'])){
    $ret['status'] = false;
    $ret['msg'] = "Nomor / pesan / jadwal tidak boleh kosong";
    echo json_encode($ret, true);
    exit;
}

$jadwal = date("Y-m-d H:i:s", strtotime($data['jadwal']));

$q = mysqli_query($koneksi, "INSERT INTO pesan(`id_blast`, `nomor`, `pesan`, `jadwal`, `tiap_bulan`, `make_by`)
            VALUES('api', '$nomor', '$pesan', '$jadwal', '0', 'api')");

$ret['status'] = true;
$ret['msg'] = "Pesan berhasil dikirim";
echo json_encode($ret, true);
exit;
