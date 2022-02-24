<?php

header('content-type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
file_put_contents('hookgroup.txt', '[' . date('Y-m-d H:i:s') . "]\n" . json_encode($data) . "\n\n", FILE_APPEND);
$nomor = $data['number']; // nomor pengguna
$idgroup = $data['groupid']; // id groupnya
$aksi = $data['action']; // aksinya, masuk = add ,keluar / di kick = remove