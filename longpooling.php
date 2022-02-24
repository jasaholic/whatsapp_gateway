<?php

include_once("helper/koneksi.php");
include_once("helper/function.php");

$last_sync = get("lastsync");
$last_sync = strtotime($last_sync);
$q = mysqli_query($koneksi, "SELECT * FROM pesan ORDER BY `time` DESC LIMIT 50");
$final = [];
while($row = mysqli_fetch_assoc($q)){
    if(strtotime($row['time']) >= $last_sync){
        $r['id'] = $row['id'];
        $r['id_blast'] = $row['id_blast'];
        $r['status'] = $row['status'];
        $final[] = $r;
    }
}
echo json_encode($final);
