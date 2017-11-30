<?php
ini_set('include_path', '../inc');
include("db.php");
$info = new allginfo("akk.ini",1);

header("Content-Type: text/html; charset=utf-8");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="de">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<title>Akk Counter</title>
		<link rel="shortcut icon" href="/favicon.ico" >
		<link rel="stylesheet" type="text/css" href="/css/akk.css" media="screen">
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
		<script type="text/javascript">
			setInterval(function(){
				var bustCache = '?' + new Date().getTime();
				var oReq = new XMLHttpRequest();
				oReq.onload = function () {
					document.getElementById("count").textContent = this.$
				};
				oReq.open('GET', '/api/counter.php' + bustCache, true);
				oReq.send();
			}, 5000);
		</script>

		<!-- DO NOT REMOVE THIS
			Hier steht ein Dank an Wilm, der das erste Akk-Tool überhaupt für die Piratenpartei programmiert hat,
			und an Hendrik und Sebastian, die die Akkreditierung immer reibungslos zum Laufen gebracht haben.
			Das Akk-tool wurde zum ersten Mal auf dem BPT 12.2 in Offenbach eingesetzt.
			Es steht unter beerware-lizenz - denkt daran, wenn ihr sie seht.
			Es wurde neu geschrieben, weil inzwischen neue Dinge dazugekommen sind.
		END -->
	</head>
	<body>
		<div id="wrapper">
			<div id="titel">
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
					<span id="count" style="color:orange;">0</span>
				</h2>
			</div> <!-- titel -->
			<div id="footer">
				<a href="about.php"> CC-BY-NC-SA </a>
			</div> <!-- footer -->
		</div> <!-- wrapper -->
	</body>
</html>