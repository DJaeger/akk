<?php header("Content-Type: text/html; charset=utf-8"); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="de">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Akk <?php print $website[$id]['titel']; ?></title>
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
<body onload="if(document.getElementById('mnr')) document.getElementById('mnr').focus(); if(document.getElementById('zahlbetrag')) document.getElementById('zahlbetrag').focus();">
<div id = "wrapper">
<div id = "titel">
<?php
if ($id == "about") {
    echo "<h1>" . $website[$id]['text']. "</h1>\n";
}
else {
    ($id == "start" ) ? $br=" " : $br = "<br>";
    echo "<h1>" . $website[$id]['text'] . $br . $info->veranstaltung . " " . $info->ort . " / " . $info->akkuser . "</h1>\n";
}
$db = new mydb();
$sql = "select count(akkId) AS mitglieder,sum(akk) as akkreditiert,
               sum(offenerbeitrag<1) AS stimmb
        from tblakk";
$row = $db->query($sql)->fetch();
$alter = "-";
if (strlen($info->startdate) == 10 and $row['akkreditiert'] >= 5) {
    $dateparts    =    explode(".",$date);
    $sqldate = sprintf("%04d-%02d-%02d", $dateparts[2], $dateparts[1], $dateparts[0]);
    $sql = "SELECT ROUND(AVG(DATEDIFF('" . $sqldate . "', geburtsdatum))/365.25,2) AS 'alter' FROM tblakk WHERE akk = 1";
    $rxx = $db->query($sql)->fetch();
    $alter = number_format($rxx['alter'], 2);
}

echo "<h1>Akkreditiert: <span style='color: orange;'>",$row['akkreditiert'],"</span> Alter: <span style='color: orange;'>",$alter,"</span></h1>";

include ("menu.php");
if ($action == "akk") {include("searchform.php");}
if ($h2 != "") { echo "<h2>".$h2."</h2>\n";}
?>
</div> <!-- titel -->
<div id = "result">