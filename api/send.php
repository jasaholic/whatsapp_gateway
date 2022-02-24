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

if(!isset($nomor) && !isset($pesan)){
    $ret['status'] = false;
    $ret['msg'] = "Nomor / pesan tidak boleh kosong";
    echo json_encode($ret, true);
    exit;
}

$res = sendMSG($nomor, $pesan);
if($res['status'] == "true"){
    $ret['status'] = true;
    $ret['msg'] = "Pesan berhasil dikirim";
    echo json_encode($ret, true);
    exit;
}else{
    $ret['status'] = false;
    $ret['msg'] = $res['msg'];
    echo json_encode($ret, true);
    exit;
}
