<?php

include_once("../helper/koneksi.php");
include_once("../helper/function.php");
// Takes raw data from the request
$data = json_decode(file_get_contents('php://input'), true);

$nomor = $data['nomor'];
header('Content-Type: application/json');

$api_key = get("key");
if($api_key != api_key()){
    $ret['status'] = false;
    $ret['msg'] = "Api key salah";
    echo json_encode($ret, true);
    exit;
}

if(cekNomorWhatsapp($nomor)){
    //true
    $ret['status'] = true;
    $ret['msg'] = "Nomor terdaftar whatsapp";
}else{
    $ret['status'] = false;
    $ret['msg'] = "Nomor tidak terdaftar whatsapp";
}

echo json_encode($ret, true);
exit;
?>