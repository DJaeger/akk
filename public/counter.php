<?php
ini_set('include_path', '../inc');
include("db.php");
$info = new allginfo("akk.ini",1);

$db = new mydb();
$sql = "select count(akkId) AS mitglieder,sum(akk) as akkreditiert from tblakk";
$row = $db->query($sql)->fetch();

header("Content-Type: text/html; charset=utf-8");
header("Refresh: 5");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="de">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<title>Akk Counter</title>
		<link rel="shortcut icon" href="/favicon.ico" >
		<link rel="stylesheet" type="text/css" href="/css/akk.css" media="screen">
		<link rel="stylesheet" type="text/css" href="/css/print.css" media="print">
		<style type="text/css">
			h1, h2, #titel h2 {
				float: none;
				text-align: center;
			}
			h1 {
				font-size:3em;
			}
			h2 span {
				font-size:4em;
			}
			#titel h2 {
				font-size:2em;
				margin-top: 1em;
			}
			#titel {
				padding-top: 4em;
			}
		</style>
		<!-- DO NOT REMOVE THIS
			Hier steht ein Dank an Wilm, der das erste Akk-Tool überhaupt für die Piratenpartei programmiert hat,
			und an Hendrik und Sebastian, die die Akkreditierung immer reibungslos zum Laufen gebracht haben.
			Das Akk-tool wurde zum ersten Mal auf dem BPT 12.2 in Offenbach eingesetzt.
			Es steht unter beerware-lizenz - denkt daran, wenn ihr sie seht.
			Es wurde neu geschrieben, weil inzwischen neue Dinge dazugekommen sind.
		END -->
	</head>
	<body>
		<div id = "wrapper">
			<div id = "titel">
				<h1>
					<?=$info->veranstaltung;?>
					<br />
					<?=$info->ort;?>
				</h1>
				<br />
				<br />
				<h2>
					Akkreditiert:
					<br />
					<span style='color: orange;'>
						<?=$row['akkreditiert'];?>
					</span>
				</h2>
<?php
include("footer.php");
?>
