<?php
ini_set('include_path', '../inc');
include("db.php");
$info = new allginfo("akk.ini",1);

header("Content-Type: text/html; charset=utf-8");

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="de">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<title>Akk Counter</title>
		<link rel="shortcut icon" href="/favicon.ico" >
		<link rel="stylesheet" type="text/css" href="/css/counter.css" media="screen">
		<script type="text/javascript">
			setInterval(function(){
				var bustCache = '?' + new Date().getTime();
				var oReq = new XMLHttpRequest();
				oReq.onload = function () {
					document.getElementById("count").textContent = this.response;
				};
				oReq.open('GET', '/api/counter.php' + bustCache, true);
				oReq.send();
			}, 5000);
			function toggleFullscreen(elem) {
				elem = elem || document.documentElement;
				if (!document.fullscreenElement && !document.mozFullScreenElement &&
					!document.webkitFullscreenElement && !document.msFullscreenElement) {
					if (elem.requestFullscreen) {
						elem.requestFullscreen();
					} else if (elem.msRequestFullscreen) {
						elem.msRequestFullscreen();
					} else if (elem.mozRequestFullScreen) {
						elem.mozRequestFullScreen();
					} else if (elem.webkitRequestFullscreen) {
						elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
					}
				} else {
					if (document.exitFullscreen) {
						document.exitFullscreen();
					} else if (document.msExitFullscreen) {
						document.msExitFullscreen();
					} else if (document.mozCancelFullScreen) {
						document.mozCancelFullScreen();
					} else if (document.webkitExitFullscreen) {
						document.webkitExitFullscreen();
					}
				}
			}
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
					<span id="count">0</span>
				</h2>
			</div> <!-- titel -->
			<div id="footer">
				<a href="about.php" id="about"> CC-BY-NC-SA </a>
				<a href="#" id="fullscreen" onclick="toggleFullscreen();return false;"> Fullscreen </a>
			</div> <!-- footer -->
		</div> <!-- wrapper -->
	</body>
</html>