<?php
include_once('../helper/koneksi.php');
include_once('../helper/function.php');
$nomor = $_POST['number'];
$pesan = $_POST['message'];

$send = sendMSG($nomor, $pesan);

$ceknomor = $koneksi->query("SELECT nomor FROM receive_chat WHERE nomor = '$nomor' LIMIT 1");
$data = mysqli_fetch_assoc($ceknomor);
$idpesan = $data['id_pesan'];
$datetime = date('Y-m-d H:i:s');
$nomorscanner = $koneksi->query("SELECT nomor FROM pengaturan")->fetch_assoc()['nomor'];
$insert = $koneksi->query("INSERT INTO receive_chat VALUES (null,'$idpesan','$nomor','$pesan','1','$nomorscanner','$datetime')");
