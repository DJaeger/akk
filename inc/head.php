<?php

$db = new mydb();
$sql = "select count(akkId) AS mitglieder,sum(akk) as akkreditiert,
						   sum(offenerbeitrag<1) AS stimmb
				from tblakk";
$row = $db->query($sql)->fetch();
$akkreditiert = $row['akkreditiert'];
$alter = "-";
if (strlen($info->startdate) == 10 and $akkreditiert >= 5) {
		$dateparts    =    explode(".",$info->startdate);
		$sqldate = sprintf("%04d-%02d-%02d", $dateparts[2], $dateparts[1], $dateparts[0]);
		$sql = "SELECT ROUND(AVG(DATEDIFF('" . $sqldate . "', geburtsdatum))/365.25,2) AS 'alter' FROM tblakk WHERE akk = 1";
		$rxx = $db->query($sql)->fetch();
		$alter = number_format($rxx['alter'], 2);
}

header("Content-Type: text/html; charset=utf-8");
?><!DOCTYPE html>
<html lang="de">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <meta name="viewpOrt" content="width=device-width, initial-scale=1">
        <title>Akk <?php print $website[$id]['titel']; ?></title>
        <link rel="shOrtcut icon" href="/favicon.ico" >
        <link rel="stylesheet" type="text/css" href="/css/akk.css" media="screen">
        <link rel="stylesheet" type="text/css" href="/css/print.css" media="print">
        <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="/css/bootstrap-orange-diff.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="/css/bootstrap-akk.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css" media="screen" />
    </head>
    <body>
        <div class="navbar navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand logo" href="https://piraten-tools.de/">
                        <img src="/img/piratentools-logo.png">
                    </a>
                    <a class="navbar-brand" href="/">Akk</a>
<?php
if ($id != "about") {
?>
                <div  class="navbar-event navbar-right">
					<?=$info->veranstaltung . " " . $info->ort;?>
                </div>
<?php
}
?>
                </div>

                <div class="navbar-collapse collapse navbar-right">
                    <ul class="nav navbar-nav navbar-right">

						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-bar-chart" aria-hidden="true"></i>
								Statistik
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li><p class="navbar-text">Akkreditiert: <span><?=($akkreditiert)?$akkreditiert:0;?></span</p></li>
								<li><p class="navbar-text">Ø-Alter: <span><?=($alter)?$alter:0;?></span></p></li>
								<li role="separator" class="divider"></li>
								<li><a href="/statistik.php"> Interne Statistik</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="/public/statistik.php"> Öffentliche Statistik</a></li>
								<li><a href="/public/counter.php"> Counter</a></li>
							</ul>
						</li>

						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="glyphicon glyphicon-th" aria-hidden="true"></i>
								Extras
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li><a href="/printakk.php"><i class="glyphicon glyphicon-print"></i> Druckansicht Akkreditierte</a></li>
								<?php if ($info->akkrolle==9) { ?>
								<li><a href="/mneu.php"><i class="glyphicon glyphicon-plus"></i> Neues Mitglied</a></li>
								<li><a href="/einnahmen.php"><i class="fa fa-money"></i> Eingenommene Beiträge</a></li>
								<li><a href="/aenderungen.php"><i class="fa fa-table"></i> Geänderte Mitglieder</a></li>
								<li><a href="/user.php"><i class="fa fa-users"></i> Benutzerverwaltung</a></li>
								<li><a href="/upload.php" role="button"><i class="fa fa-upload"></i> Upload Mitgliedsdaten</a></li>
								<?php } ?>
							</ul>
						</li>

						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="glyphicon glyphicon-user" aria-hidden="true"></i>
								<?php echo $info->akkuser;?>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li><a href="/passwd.php"><i class="fa fa-user-circle-o"></i> Passwort ändern</a></li>
								<li><a href="/logout.php" role="button"><i class="glyphicon glyphicon-log-out"></i> Abmelden</a></li>
							</ul>
						</li>

						<li title="Über">
							<a href="/about.php">
								<span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
								<span class="visible-xs-inline-block">Über</span>
							</a>
						</li>
					</ul>
                </div><!--/.navbar-collapse -->

				<div class="navbar-search navbar-right">
					<form class="navbar-form navbar-right" role="search" method="post" action="/">
						<div class="input-group">
							<input type="text" class="form-control" value="" size="25" placeholder="Name, Mitgliedsnummer..." name="searchInput">
							<span class="input-group-btn">
								<button title="Suche starten…" type="submit" name="send" class="btn btn-default" aria-label="Submit">
									<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
								</button>
							</span>
						</div>
					</form>
				</div>

            </div>
        </div>

        <div class="content">
            <div class="container">
<?php
if ( !empty($website[$id]['text']) ) { echo "<h1 style='display:block;width:100%;'>" . $website[$id]['text'] . "</h1>\n";}
if ( $h2 != "" ) { echo "<h2>" . $h2 . "</h2>\n";}