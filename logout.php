<?php

$id="logout";
header('refresh:1;url=http://logoutfromakk@' . $_SERVER['HTTP_HOST'] . '/');
ini_set('include_path', 'inc');
include("db.php");
include("define.php");
include("head.php");
echo "Logout - bitte das Passwortfenster abbrechen, sonst ist das neue Login ggf. etwas <quote>holprig</quote>\n";
include("footer.php");
