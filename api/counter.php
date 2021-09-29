<?php
ini_set('include_path', '../inc');
include("db.php");
$info = new allginfo("akk.ini",1);

header("Content-Type: text/html; charset=utf-8");

$db = new mydb();
$sql = "select sum(akkPT) as akkreditiertPT, sum(akkAV) as akkreditiertAV from tblakk";
$row = $db->query($sql)->fetch();

die(json_encode($row));
