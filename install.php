<?php
$id="install";
ini_set('include_path', 'inc');
include("db.php");
include("functions.php");
include_once("passwdfkt.php");

header("Content-Type: text/html; charset=utf-8");

?><!DOCTYPE html>
<html lang="de">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <meta name="viewpOrt" content="width=device-width, initial-scale=1">
        <title>Akk Installation</title>
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
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container">
<?php
if ( !empty($_POST) ) {
?>
                <div class="text-center">
<?php
    $config['database'] = $_POST['database'];
	$config['database']['driver'] = 'mysql';
    $config['akk'] = $_POST['akk'];
    $config['system']['rootdir'] = realpath(dirname(__FILE__));
    $config_ini = arr2ini($config);
    file_put_contents('inc/akk.ini', $config_ini);
	$info = new allginfo('akk.ini',1);

    try {
        $db = new mydb();

		if ( $db->query("SHOW TABLES LIKE 'tbluser'")->rowCount() == 0 ) {
			recreateTables($db);
		}

		if ( $db->query("SELECT login FROM tbluser")->rowCount() == 0 ) {
			$sql ="INSERT INTO tbluser (login,name,rolle) VALUES (':adminname','Administrator',9);";
			$rs = $db->prepare($sql);
			$rs->bindParam(':adminname', $_POST['admin']['username'], PDO::PARAM_INT);
			$rs->execute();
			print("Added " . $_POST['admin']['username'] . " to 'tbluser'.\n<br />");

			$User=$_POST['admin']['username'];
			$Passwort=$_POST['admin']['password'];
			pSetPasswd($User,$Passwort);
            print("Added " . $_POST['admin']['username'] . " to htpasswd.\n<br />");
        }
?>
                    <h1>Wohoo</h1>
                    <h2>We are ready</h2>
                    <h3>Let's start</h3>
                    <h4 id="timer"></h4>

					<script>
						var count=5;
						var counter=setInterval(timer, 1000); //1000 will  run it every 1 second
						function timer() {
							count=count-1;
							if (count < 0) {
								clearInterval(counter);
								//counter ended, do something here
								window.location = "/";
								return;
							}
							//Do code for showing the number of seconds here
							document.getElementById("timer").innerHTML=count;
						}
					</script>
<?php
    } catch (\Throwable $t) {
        echo "Konnte keine Datenbank-Verbindung herstellen";
    }
?>
                </div>
<?php
} else {
?>
                <h2>Installation</h2>
                <form action="" method="post" class="form-horizontal">
                    <fieldset>

                        <legend>Datenbank</legend>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="database[driver]">Treiber:</label>
                            <div class="col-sm-10">
                                <select name='database[driver]' id='database_driver' class="form-control" disabled>
<?php
    $availableDrivers = PDO::getAvailableDrivers();
    foreach ($availableDrivers AS $driver) {
?>                                <option <?=selected("database","driver",$driver);?>><?=$driver;?></option>
<?php
    }
?>
								</select>
								<span id="helpBlock" class="help-block">
									Momentan wird nur mysql unterstützt.
								</span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="database[host]">Host:</label>
							<div class="col-sm-10">
								<input class="form-control" type="text" name="database[host]" value="<?=@$_POST['database']['host'];?>" placeholder="localhost">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="database[db]">Datenbank:</label>
							<div class="col-sm-10">
								<input class="form-control" type="text" name="database[db]" value="<?=@$_POST['database']['db'];?>" placeholder="akk">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="database[username]">Username:</label>
							<div class="col-sm-10">
								<input class="form-control" type="text" name="database[username]" value="<?=@$_POST['database']['username'];?>" placeholder="akk">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="database[password]">Password:</label>
							<div class="col-sm-10">
								<input class="form-control" type="password" name="database[password]" value="<?=@$_POST['database']['password'];?>" placeholder="">
							</div>
						</div>

					</fieldset>
					<fieldset>

						<legend>Akkreditierung</legend>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="akk[Veranstaltung]">Veranstaltung:</label>
							<div class="col-sm-10">
								<input class="form-control" type="text" name="akk[Veranstaltung]" value="<?=@$_POST['akk']['Veranstaltung'];?>" placeholder="">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="akk[startdate]">Startdatum:</label>
							<div class="col-sm-10">
								<input class="form-control" type="date" name="akk[startdate]" value="<?=@$_POST['akk']['startdate'];?>" placeholder="dd.mm.yyyy">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="akk[enddate">Enddatum:</label>
							<div class="col-sm-10">
								<input class="form-control" type="date" name="akk[enddate]" value="<?=@$_POST['akk']['enddate'];?>" placeholder="dd.mm.yyyy">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="akk[Ort]">Ort:</label>
							<div class="col-sm-10">
								<input class="form-control" type="text" name="akk[Ort]" value="<?=@$_POST['akk']['Ort'];?>" placeholder="Berlin">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="akk[Ebene]">Ebene:</label>
							<div class="col-sm-10">
								<select name='akk[Ebene]' id='akk_Ebene' class='form-control'>
									<option <?=selected("akk","Ebene","BV");?> value='BV'>Bund</option>
									<option <?=selected("akk","Ebene","LV");?> value='LV'>Land</option>
									<option <?=selected("akk","Ebene","KV");?> value='KV'>Kreis</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="akk[Typ]">Typ:</label>
							<div class="col-sm-10">
								<select name='akk[Typ]' id='akk_Typ' class='form-control'>
									<option <?=selected("akk","Typ","PT");?> value='PT'>Parteitag</option>
									<option <?=selected("akk","Typ","AV");?> value='AV'>Aufstellungsversammlung</option>
								</select>
							</div>
						</div>

					</fieldset>
					<fieldset>

						<legend>Administrator</legend>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="admin[username]">Benutzername:</label>
							<div class="col-sm-10">
								<input class="form-control" type="text" name="admin[username]" value="<?=@$_POST['admin']['username'];?>" placeholder="">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="admin[password]">Passwort:</label>
							<div class="col-sm-10">
								<input class="form-control" type="password" name="admin[password]" value="<?=@$_POST['admin']['password'];?>" placeholder="">
							</div>
						</div>

					</fieldset>

					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-primary">Einrichten</button>
						</div>
					</div>

                </form>
<?php
}
?>
                <script src="/js/jquery.min.js"></script>
                <script src="/js/bootstrap.min.js" ></script>
            </div><!-- /.container -->
        </div><!-- /.content -->
    </body>
</html>