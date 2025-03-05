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
                    <a class="navbar-brand logo" href="https://piraten.tools/">
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
    print("Speichere Einstellungen in Konfigurations-Datei.\n<br />");
    $config['database'] = $_POST['database'];
    $config['database']['driver'] = 'mysql';
    $config['akk'] = $_POST['akk'];
    $config['system']['rootdir'] = realpath(dirname(__FILE__).'/../');
    $config_ini = arr2ini($config);
    file_put_contents('inc/akk.ini', $config_ini);
    $info = new allginfo('akk.ini',1);
    try {
        $db = new mydb();

        if ( $db->query("SHOW TABLES LIKE 'tbluser'")->rowCount() == 0 ) {
            print("Erstelle Datenbank-Tabellen neu.\n<br />");
            recreateTables($db);
        }

        if ( $db->query("SELECT login FROM tbluser")->rowCount() == 0 || !empty($_POST['admin']['password']) ) {
            $User=$_POST['admin']['username'];
            $Passwort=$_POST['admin']['password'];
            if ( empty($User) ) {
                print("Benutze standard-Admin-Name: admin.\n<br />");
                $User = 'admin';
            }
            if ( empty($Passwort) ) {
                print("Benutze standard Admin-Passwort: admin.\n<br />");
                $Passwort = 'admin';
            }
            print("Füge " . $User . " zu 'tbluser' hinzu.\n<br />");
            $sql ="INSERT INTO tbluser (login,name,rolle) VALUES (:adminname,'Administrator',9);";
            $rs = $db->prepare($sql);
            $rs->bindParam(':adminname', $User, PDO::PARAM_STR);
            $rs->execute();

            print("Füge " . $User . " zu htpasswd hinzu.\n<br />");
            pSetPasswd($User,$Passwort);
            
            print("Erstelle htaccess neu.\n<br />");
            recreateHtaccess();
        }
?>
                    <h1>Wohoo</h1>
                    <h2>System ist konfiguriert</h2>
                    <h4>Jetzt nur noch Mitgliedsdaten hochladen...<br />... und schon kann die Akkreditierung beginnen!</h4>
                    <h4 id="timer"></h4>

                    <script>
                        var count=5;
                        var counter=setInterval(timer, 1000); // 1000 will run it every 1 second
                        function timer() {
                            count=count-1;
                            if (count < 0) {
                                clearInterval(counter);
                                // counter ended, redirect to upload
                                window.location = "/upload.php";
                                return;
                            }
                            // Showing the number of seconds
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
    $db_configured = false;
    $db_connected = false;
    $adminuser = NULL;
    $adminuserlogin = "";
    try {
        $db = new mydb();
        $db_configured = true;
        if ( $db->query("SHOW TABLES LIKE 'tbluser'")->rowCount() !== 0 ) {
            $db_connected = true;
            $adminuser=$db->query("SELECT login FROM tbluser WHERE rolle = 9 LIMIT 1")->fetch();
            $adminuserlogin = $adminuser['login'];
        }
    } catch (\Throwable $t) {
        // Konnte keine Datenbank-Verbindung herstellen;
    }

    $settings = @parse_ini_file('akk.ini', TRUE);
    $akk = (object)$settings["akk"];
    $event_configured = (!empty($akk->Veranstaltung)&&!empty($akk->startdate)&&!empty($akk->enddate)&&!empty($akk->Ort)&&!empty($akk->Ebene)&&($akk->PT==1||$akk->AV==1));

?>
                <h2>Installation</h2>
                <form action="" method="post" class="form-horizontal">

                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Datenbank
                                    </a>
                                    <span class="label label-<?=($db_connected)?'success':'default';?> pull-right"><?=(!$db_configured)?'Nicht ':'';?>Konfiguriert und <?=((!$db_connected)?'noch nicht Eingerichtet':'Verbunden');?></span>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse<?=($db_configured)?'':' in';?>" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="database[driver]">Treiber:</label>
                                        <div class="col-sm-10">
                                            <select name='database[driver]' id='database_driver' class="form-control" disabled>
<?php
    $availableDrivers = PDO::getAvailableDrivers();
    foreach ($availableDrivers AS $driver) {
?>                                                <option <?=selected("database","driver",$driver);?>><?=$driver;?></option>
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
                                            <input class="form-control" type="text" name="database[host]" value="<?=@$settings['database']['host'];?>" placeholder="localhost">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="database[db]">Datenbank:</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="text" name="database[db]" value="<?=@$settings['database']['db'];?>" placeholder="akk">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="database[username]">Username:</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="text" name="database[username]" value="<?=@$settings['database']['username'];?>" placeholder="akk">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="database[password]">Password:</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="password" name="database[password]" value="<?=@$settings['database']['password'];?>" placeholder="">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingTwo">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                        Veranstaltung
                                    </a>
                                    <span class="label label-<?=($event_configured)?'success':'default';?> pull-right"><?=($event_configured)?'':'Nicht ';?>Konfiguriert</span>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse<?=($event_configured)?'':' in';?>" role="tabpanel" aria-labelledby="headingTwo">
                                <div class="panel-body">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="akk[Veranstaltung]">Veranstaltung:</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="text" name="akk[Veranstaltung]" value="<?=@$settings['akk']['Veranstaltung'];?>" placeholder="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="akk[startdate]">Startdatum:</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="date" name="akk[startdate]" value="<?=@$settings['akk']['startdate'];?>" placeholder="dd.mm.yyyy">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="akk[enddate">Enddatum:</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="date" name="akk[enddate]" value="<?=@$settings['akk']['enddate'];?>" placeholder="dd.mm.yyyy">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="akk[Ort]">Ort:</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="text" name="akk[Ort]" value="<?=@$settings['akk']['Ort'];?>" placeholder="Berlin">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="akk[Ebene]">Ebene:</label>
                                        <div class="col-sm-10">
                                            <select name='akk[Ebene]' id='akk_Ebene' class='form-control'>
                                                <option <?=selected($settings['akk'],"Ebene","BV");?> value='BV'>Bund</option>
                                                <option <?=selected($settings['akk'],"Ebene","LV");?> value='LV'>Land</option>
                                                <option <?=selected($settings['akk'],"Ebene","KV");?> value='KV'>Kreis</option>
                                                <option <?=selected($settings['akk'],"Ebene","EP");?> value='EP'>Europa Parlament</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" id="akk_av_wrapper">
                                        <label class="col-sm-2 control-label">Typ:</label>
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="akk[PT]" value="1" <?=checked($settings['akk'],"PT","1");?> />
                                                    Parteitag
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="akk[AV]" value="1" <?=checked($settings['akk'],"AV","1");?> />
                                                    Aufstellungsversammlung
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                        Administrator
                                    </a>
                                    <span class="label label-<?=($adminuser)?'success':'danger';?> pull-right"><?=($adminuser)?'':'Nicht ';?>Vorhanden</span>
                                </h4>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse<?=($db_connected)?'':' in';?>" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="admin[username]">Benutzername:</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="text" name="admin[username]" value="<?=@$adminuserlogin;?>" placeholder="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="admin[password]">Passwort:</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="password" name="admin[password]" value="" placeholder="">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

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
