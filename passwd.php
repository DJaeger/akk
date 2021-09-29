<?php

$id="passwd";
ini_set('include_path', 'inc');
include("db.php");
include("define.php");
include("head.php");
include_once("passwdfkt.php");

function pPassForm() {
    $User = $_SERVER["REMOTE_USER"];
    echo "<form method='post' action='passwd.php' class='form-horizontal'><br>\n";
    echo "<input type='hidden' name='user' value='" . $User . "' />\n";

    echo "<div class='form-group'>\n";
    echo "<label class='col-sm-2 control-label'>Username</label>\n";
    echo "<div class='col-sm-10'>\n";
    echo "<p class='form-control-static'>" . $User . "</p>\n";
    echo "</div></div>\n";

    echo "<div class='form-group'>\n";
    echo "<label class='col-sm-2 control-label'>Passwort alt</label>\n";
    echo "<div class='col-sm-10'>\n";
    echo "<input type='password' class='form-control' name='pass1' size=20 autofocus>\n";
    echo "</div></div>\n";

    echo "<div class='form-group'>\n";
    echo "<label class='col-sm-2 control-label'>Passwort neu</label>\n";
    echo "<div class='col-sm-10'>\n";
    echo "<input type='password' class='form-control' name='pass2' size=20 autofocus>\n";
    echo "</div></div>\n";

    echo "<div class='form-group'>\n";
    echo "<label class='col-sm-2 control-label'>Wiederholung</label>\n";
    echo "<div class='col-sm-10'>\n";
    echo "<input type='password' class='form-control' name='pass3' size=20 autofocus>\n";
    echo "</div></div>\n";

    echo "<div class='form-group'>\n";
    echo "<div class='col-sm-offset-2 col-sm-10'>\n";
    echo "<input type='submit' class='btn btn-primary' name='submit_pass1' value='Ändern'>\n";
    echo "</div></div>\n";

    echo "</form>\n";
}

function pPassSet($User,$Pass1,$Pass2) {
    $res=pCheckPasswd($User,$Pass1);
    if ($res>0) {
        die("Nanu, User ist nicht konfiguriert");
        return;
    }
    if ($res<0) {
        errmsg("Das alte Passwort ist falsch");
        pPassForm();
        return;
    }
    pSetPasswd($User,$Pass2);
    echo "<p>Das Passwort wurde erfolgreich gesetzt</p>\n";
    return;
}

$User="";
$Pass1="";
$Pass2="";
$Pass3="";

if (isset($_POST['user'])) {
    $User=preg_replace("/[^0-9a-zA-Z]/","",$_POST['user']);
}
if (isset($_POST['pass1'])) {
    $Pass1=preg_replace($pass_chr,"",$_POST['pass1']);
    if ($Pass1=="")
        errmsg("Das alte Passwort muss angegeben werden");
}
if (isset($_POST['pass2'])) {
    $Pass2=preg_replace($pass_chr,"",$_POST['pass2']);
    if ($Pass2 != $_POST['pass2']) {
        errmsg($pass_err);
        $Pass2="";
    } elseif ($Pass2=="") {
        errmsg("Es muss ein neues Passwort angegeben sein");
    }
}
if (isset($_POST['pass3'])) {
    $Pass3=preg_replace($pass_chr,"",$_POST['pass3']);
    if ($Pass3=="" && $Pass2!="") {
        errmsg("Das Passwort muss wiederholt werden");
    } elseif ($Pass3 != $Pass2 && $Pass2!="") {
        errmsg("Die Wiederholung stimmt nicht mit dem neuen Passwort &uuml;berein");
        $Pass3="";
    }
}
if (isset($_POST['submit_pass1']) && $_POST['submit_pass1'] = 'Ändern' &&
    $User!="" && $Pass1!="" && $Pass2!="" && $Pass3!="") {
      if ($User != $_SERVER["REMOTE_USER"])
          die("You are using this formular under abnormal conditions ;)");
      else
          pPassSet($User,$Pass1,$Pass2);
} else {
    pPassForm();
}

include("footer.php");
