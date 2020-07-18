<?php
$info = new allginfo();
$action = "";
$h2 = "";

$website=array();
$website['start']      = array("hmenu"=>"", "menu"=>"", "page"=>"", "text"=>"Akkreditierung", "titel"=>"Akkreditierung");
$website['user']       = array("hmenu"=>"", "menu"=>"", "page"=>"user", "text"=>"Userverwaltung", "titel"=>"Userverwaltung");
$website['passwd']     = array("hmenu"=>"", "menu"=>"", "page"=>"passwd", "text"=>"Passwort ändern", "titel"=>"Passwort ändern");
$website['mneu']       = array("hmenu"=>"", "menu"=>"", "page"=>"mneu", "text"=>"Neuanlage Mitglied", "titel"=>"Neuanlage");
$website['printakk']   = array("hmenu"=>"", "menu"=>"", "page"=>"printakk", "text"=>"Druckliste Akkreditierte", "titel"=>"Druckansicht Akkreditierte");
$website['upload']     = array("hmenu"=>"", "menu"=>"", "page"=>"upload", "text"=>"Upload Mitgliedsdaten", "titel"=>"Upload");
$website['einnahmen']  = array("hmenu"=>"", "menu"=>"", "page"=>"einnahmen", "text"=>"Eingenommene Beiträge", "titel"=>"Eingenommene Beiträge");
$website['aenderungen']  = array("hmenu"=>"", "menu"=>"", "page"=>"aenderungen", "text"=>"Geänderte Mitglieder", "titel"=>"Geänderte Mitglieder");
$website['statistik']  = array("hmenu"=>"", "menu"=>"", "page"=>"statistik", "text"=>"Statistik", "titel"=>"Akkreditierungsstatistik");
$website['anonstat']  = array("hmenu"=>"", "menu"=>"", "page"=>"public/statistik", "text"=>"Öff. Statistik", "titel"=>"Akkreditierungsstatistik");
$website['logout']     = array("hmenu"=>"", "menu"=>"", "page"=>"logout", "text"=>"Logout " . $info->akkuser, "titel"=>"Logout");
$website['about']      = array("hmenu"=>"", "menu"=>"", "page"=>"about", "text"=>"Lizenz Akkreditierungstool und Dank", "titel"=>"Lizenz und Dank");

include('functions.php');
