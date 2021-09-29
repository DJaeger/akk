<?php
$id="statistik";
ini_set('include_path', '../inc');
include("db.php");
include("functions.php");

?>
<html lang="de">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta http-equiv="refresh" content="30">
<title>Akk Akkreditierungsstatistik</title>
<link rel="shortcut icon" href="/favicon.ico" >
<link rel="stylesheet" type="text/css" href="/css/akk.css" media="screen">
<link rel="stylesheet" type="text/css" href="/css/print.css" media="print">
<!-- DO NOT REMOVE THIS
Hier steht ein Dank an Wilm, der das erste Akk-Tool überhaupt für die Piratenpartei programmiert hat,
und an Hendrik und Sebastian, die die Akkreditierung immer reibungslos zum Laufen gebracht haben.
Das Akk-tool wurde zum ersten Mal auf dem BPT 12.2 in Offenbach eingesetzt.
Es steht unter beerware-lizenz - denkt daran, wenn ihr sie seht.
Es wurde neu geschrieben, weil inzwischen neue Dinge dazugekommen sind.
END -->
</head>
<body>
<?php
$info = new allginfo("akk.ini",1);
echo "<div id='titel'>\n";
echo "<h1>Statistik " . $info->veranstaltung . " " . $info->ort  . "</h1>\n";

$db = new mydb();
$sql = "select count(akkId) AS mitglieder,sum(akkPT) as akkreditiertPT,sum(akkAV) as akkreditiertAV,
               sum(offenerbeitrag<1) AS stimmbPT, sum(IF (nation IN ('" . implode("','", $info->nations) . "'),1,0)) AS stimmbAV
        from tblakk";
$row = $db->query($sql)->fetch();
if ($info->PT == 1 && $info->AV == 1) {
   echo "<h2>Akkreditiert PT: <span class='akkCount'>&nbsp;",$row['akkreditiertPT'],"&nbsp;</span>&nbsp;-&nbsp;Akkreditiert AV: <span class='akkCount'>&nbsp;",$row['akkreditiertAV'],"&nbsp;</span></h2>";
} else {
   echo "<h2>Akkreditiert: <span class='akkCount'>&nbsp;".($info->PT==1)?$row['akkreditiertPT']:$row['akkreditiertAV']."&nbsp;</span></h2>";
}

echo "<ul></ul>\n";
echo "</div>\n";
echo "<div id = 'result'>\n";
include("stat.php");
echo "</div>\n";

?>
</body>
</html>
