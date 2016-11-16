<?php
$id="statistik";
ini_set('include_path', '../inc');
include("db.php");

function zformat($zahl) {
   return number_format($zahl,2,',','.');
}

function td($x, $c = "", $a = "") {
   if ($a == "" || $x == "") {
      $a1=""; $a2="";
   }
   else {
      $a1="<a href='".$a."'>";
      $a2="</a>";
   }
   if ($c != "") {$c = " class='".$c."'";}
   echo "<td$c>$a1$x$a2</td>";
}

function tdz($x, $c = "") {
   $s = "<td class='r $c'>".number_format($x,2,',','.')."</td>";
   print $s;
}

function tdz0($x, $c = "") {
   $s = "<td class='r $c'>".number_format($x,0,',','.')."</td>";
   print $s;
}

function tdr($x, $c = "") {
   echo "<td class='r $c'>$x</td>";
}

function th($x, $c = "", $a = "") {
   if ($a == "") {
      $a1=""; $a2="";
   }
   else {
      $a1="<a href='".$a."'>";
      $a2="</a>";
   }
   if ($c != "") {$c = " class='".$c."'";}
   echo "<th$c>$a1$x$a2</th>";
}

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
echo "<div id = 'titel'>\n";
echo "<h1>Statistik " . $info->veranstaltung . " " . $info->ort  . "</h1>\n";

$db = new mydb();
$sql = "select count(akkId) AS mitglieder,sum(akk) as akkreditiert,
               sum(offenerbeitrag<1) AS stimmb
        from tblakk";
$row = $db->query($sql)->fetch();
echo "<h2>Akkreditiert: <span style='color: orange; background-color: white;'>&nbsp;",$row['akkreditiert'],"&nbsp;</span></h2>";

echo "<ul></ul>\n";
echo "</div>\n";
echo "<div id = 'result'>\n";
include("stat.php");
echo "</div>\n";

?>

</body>
</html>
