<?php
ini_set('include_path', '../inc');
include("db.php");
$info = new allginfo("akk.ini",1);

header("Content-Type: text/html; charset=utf-8");

$db = new mydb();
$sql = "select count(akkId) AS mitglieder,sum(akk) as akkreditiert from tblakk";
$row = $db->query($sql)->fetch();

die($row['akkreditiert']);