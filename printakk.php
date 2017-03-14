<?php
$id="printakk";
ini_set('include_path', 'inc');
include("db.php");
include("define.php");
$db = new mydb();
// reset Variablen
$num_rows = 0;

$mnr = intval($_REQUEST['mnr']);
$sql = "SELECT DISTINCTROW * FROM tblakk WHERE akk = 1 ORDER BY mitgliedsnummer";
$rs = $db->prepare($sql);
$rs->execute();
$rows = $rs->fetchAll();
$num_rows = count($rows);

header("Content-Type: text/html; charset=utf-8");
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="de">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Akk <?php print $website[$id]['titel']; ?></title>
<link rel="shortcut icon" href="/favicon.ico" >
<link rel="stylesheet" type="text/css" href="/css/akk.css" media="screen">
<link rel="stylesheet" type="text/css" href="/css/print.css" media="print">
<style type="text/css">
	#wrapper, #wrapper > #result {
		position: absolute;
		top: 0;
		margin-top: 10px;
		background-color: #FFFFFF;
	}
	#wrapper {
		display: block;
		width: 100%;
		right: 0px;
	}
	#titel {
		height:0px;
	}
</style>
</head>
<body>
<div id = "wrapper">
<div id = "titel">
<?php
	include ("menu.php");
?>
</div>
<div id = "result">
<table class="akk">
<colgroup>
<col width="10%">
<col width="10%">
<col width="10%">
<col width="10%">
</colgroup>
<?php
if ($num_rows > 0) {
    for ($i=0; $i<count($rows); $i++) {
        echo "<tr>";
        if (is_null($rows[$i]['mitgliedsnummer'])) {
            $mnrref = $rows[$i]['refcode'];
            $c = "refc";
        }
        else {
            $mnrref = $rows[$i]['mitgliedsnummer'];
            $c = "";
        }
        $id = $rows[$i]['akkID'] ;
        $adr = $rows[$i]['strasse'] . "<br>" . $rows[$i]['plz'] . " " . $rows[$i]['ort'];
        tdr($mnrref, $c);
        echo "<td><abbr datatitle='".$rows[$i]['geburtsdatum']."'> " .$rows[$i]['nachname'].  " </abbr> </td>";
        td($rows[$i]['vorname']);
        if (is_null($rows[$i]['mitgliedsnummer'])) {
            td($rows[$i]['kommentar']);
        } else {
            td("");
        }

        echo "</tr>\n";
    }
}

?>
</table>
</div> <!-- result -->   
</div> <!-- wrapper -->  
</body>
</html>
