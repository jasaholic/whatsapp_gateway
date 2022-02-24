<?php

include_once("../helper/koneksi.php");
include_once("../helper/function.php");

$login = cekSession();
if($login == 0){
    redirect("../login.php");
}

syncMSG();
echo "success";
?>