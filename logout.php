<?php
include_once("helper/koneksi.php");
include_once("helper/function.php");

session_destroy();
redirect("login.php");