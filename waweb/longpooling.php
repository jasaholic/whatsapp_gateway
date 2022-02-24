<?php

include_once("../helper/koneksi.php");
include_once("../helper/function.php");

$login = cekSession();
if($login == 0){
    redirect("../login.php");
}

if(get("nomor")){
    $nomor = get("nomor");
    $last_sync = get("lastsync");
    $last_sync = strtotime($last_sync);
    $nomor_saya = getSingleValDB("pengaturan", "id", "1", "nomor");
    $q = mysqli_query($koneksi, "SELECT * FROM receive_chat WHERE nomor='$nomor' AND nomor_saya='$nomor_saya' ORDER BY tanggal ASC");
    $final = [];
    while($row = mysqli_fetch_assoc($q)){
      if($last_sync <= strtotime($row['tanggal'])){
        if(date("Y-m-d", strtotime($row['tanggal'])) == date("Y-m-d")){
          $row['tanggal'] = date("H:i", strtotime($row['tanggal']));
        }else{
          $row['tanggal'] = date("d M y H:i", strtotime($row['tanggal']));
        }
        $final[] = $row;
      }
    }
    echo json_encode($final);
}
