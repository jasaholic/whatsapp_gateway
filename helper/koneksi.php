<?php

$host = "localhost";
$username = "root";
$password = "";
$db = "wa_project";

$koneksi = mysqli_connect($host, $username, $password, $db) or die("GAGAL");

$base_url = "http://localhost/project/";
date_default_timezone_set('Asia/Jakarta');
