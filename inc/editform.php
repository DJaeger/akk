<?php
if ($action == "edit") {
    $mnr = intval($rows[0]['mitgliedsnummer']);
    $sqlb = "SELECT * FROM tblbeitrag WHERE mnr = :mnr order by opjahr";
    $rsb = $db->prepare($sqlb);
    $rsb->bindParam(':mnr', $mnr, PDO::PARAM_INT);
    $rsb->execute();
    $rowb = $rsb->fetchAll();

    $sqlp = "SELECT * FROM tblpay WHERE akkID = :akkid";
    $rsp = $db->prepare($sqlp);
    $rsp->bindParam(':akkid', $akkid, PDO::PARAM_INT);
    $rsp->execute();
    $rowpay = $rsp->fetchAll();

    $i = 0;
    $vid = $rows[$i]['akkID'];
    $mnr = $rows[$i]['mitgliedsnummer'];
    $vrefcode = $rows[$i]['refcode'];
    $vnachname = $rows[$i]['nachname'];
    $vvorname = $rows[$i]['vorname'];
    $vlv = $rows[$i]['lv'];
    $vkv = $rows[$i]['kv'];
    $vstrasse = $rows[$i]['strasse'];
    $vplz = $rows[$i]['plz'];
    $vort = $rows[$i]['ort'];
    $vnat = $rows[$i]['nation'];
    $voffen = $rows[$i]['offenerbeitrag'];
    $vgebdat = $rows[$i]['geburtsdatum'];
    $vkommentar = $rows[$i]['kommentar'];
    $vakkrediteurPT = $rows[$i]['akkrediteurPT'];
    $vakkrediteurAV = $rows[$i]['akkrediteurAV'];
    $warnung = $rows[$i]['warnung'];
} else {
    $vid = 0;
    $mnr = "";
    $vrefcode = "";
    $vnachname = "";
    $vvorname = "";
    $vlv = "";
    $vkv = "";
    $vstrasse = "";
    $vplz = "";
    $vort = "";
    $vnat = "";
    $voffen = "";
    $vgebdat = "";
    $vkommentar = "";
    $vakkrediteurPT = $info->akkuser;
    $vakkrediteurAV = $info->akkuser;
    $warnung = "";
}

echo "<form action='index.php' method='POST' class='form-horizontal'>\n";

    echo "<div class='form-group'>\n";
    echo "<label class='col-sm-2 control-label'>Mitgliedsnummer</label>\n";
    echo "<div class='col-sm-10'>\n";
    echo "<p class='form-control-static'>" . $mnr . "</p>\n";
    echo "</div></div>\n";

    echo "<div class='form-group'>\n";
    echo "<label class='col-sm-2 control-label'>Refcode</label>\n";
    echo "<div class='col-sm-10'>\n";
    echo "<input type='text' class='form-control' name='refcode' id='refcode' value='".$vrefcode."'>\n";
    echo "</div></div>\n";

    echo "<div class='form-group'>\n";
    echo "<label class='col-sm-2 control-label'>Nachname</label>\n";
    echo "<div class='col-sm-10'>\n";
    echo "<input type='text' class='form-control' name='nachname' id='nachname' value='".$vnachname."'>\n";
    echo "</div></div>\n";

    echo "<div class='form-group'>\n";
    echo "<label class='col-sm-2 control-label'>Vorname</label>\n";
    echo "<div class='col-sm-10'>\n";
    echo "<input type='text' class='form-control' name='vorname' id='vorname' value='".$vvorname."'>\n";
    echo "</div></div>\n";

    echo "<div class='form-group'>\n";
    echo "<label class='col-sm-2 control-label'>LV</label>\n";
    echo "<div class='col-sm-10'>\n";
    echo "<input type='text' class='form-control' name='lv' id='lv' value='".$vlv."'>\n";
    echo "</div></div>\n";

    echo "<div class='form-group'>\n";
    echo "<label class='col-sm-2 control-label'>KV</label>\n";
    echo "<div class='col-sm-10'>\n";
    echo "<input type='text' class='form-control' name='kv' id='kv' value='".$vkv."'>\n";
    echo "</div></div>\n";

    echo "<div class='form-group'>\n";
    echo "<label class='col-sm-2 control-label'>KV</label>\n";
    echo "<div class='col-sm-10'>\n";
    echo "<input type='text' class='form-control' name='kv' id='kv' value='".$vkv."'>\n";
    echo "</div></div>\n";

    echo "<div class='form-group'>\n";
    echo "<label class='col-sm-2 control-label'>Straße</label>\n";
    echo "<div class='col-sm-10'>\n";
    echo "<input type='text' class='form-control' name='strasse' id='strasse' value='".$vstrasse."'>\n";
    echo "</div></div>\n";

    echo "<div class='form-group'>\n";
    echo "<label class='col-sm-2 control-label'>PLZ</label>\n";
    echo "<div class='col-sm-10'>\n";
    echo "<input type='text' class='form-control' name='plz' id='plz' value='".$vplz."'>\n";
    echo "</div></div>\n";

    echo "<div class='form-group'>\n";
    echo "<label class='col-sm-2 control-label'>Ort</label>\n";
    echo "<div class='col-sm-10'>\n";
    echo "<input type='text' class='form-control' name='ort' id='ort' value='".$vort."'>\n";
    echo "</div></div>\n";

    echo "<div class='form-group'>\n";
    echo "<label class='col-sm-2 control-label'>Nationalit&auml;t</label>\n";
    echo "<div class='col-sm-10'>\n";
    echo "<input type='text' class='form-control' name='nat' id='nat' value='".$vnat."'>\n";
    echo "</div></div>\n";

    echo "<div class='form-group'>\n";
    echo "<label class='col-sm-2 control-label'>Offener Beitrag</label>\n";
    echo "<div class='col-sm-10'>\n";
    if ($action == "new") {
        echo "<input type='text' class='form-control' name='offenerbeitrag' id='offenerbeitrag' value='".$voffen."'>";
    } else {
        echo "<p class='form-control-static'>" . $voffen . "</p>\n";
    }
    echo "</div></div>\n";

    echo "<div class='form-group'>\n";
    echo "<label class='col-sm-2 control-label'>Geburtsdatum</label>\n";
    echo "<div class='col-sm-10'>\n";
    echo "<input type='text' class='form-control' name='gebdat' id='gebdat' value='".$vgebdat."'>\n";
    echo "</div></div>\n";

    echo "<div class='form-group'>\n";
    echo "<label class='col-sm-2 control-label'>Kommentar</label>\n";
    echo "<div class='col-sm-10'>\n";
    echo "<textarea class='form-control' name='kommentar' id='kommentar' cols='50' rows='3' maxlength='255'>" . $vkommentar . "</textarea>\n";
    echo "</div></div>\n";

    echo "<div class='form-group'>\n";
    echo "<label class='col-sm-2 control-label'>Warnung</label>\n";
    echo "<div class='col-sm-10'>\n";
    if ($info->akkrolle == 9) {
        echo "<select class='form-control' name='warnung' id='warnung'>";
        if ($warnung == "") {
            echo "<option selected value=''>Nein</option><option value='1'>Ja</option><option value='S'>Gesperrt</option>";
        } else if ($warnung == "S") {
            echo "<option value=''>Nein</option><option value='1'>Ja</option><option selected value='S'>Gesperrt</option>";
        } else {
            echo "<option value=''>Nein</option><option selected value='1'>Ja</option><option value='S'>Gesperrt</option>";
        }
        echo "</select>";
    } else {
        echo "<p class='form-control-static'>" . $warnung . "</p>\n";
    }
    echo "</div></div>\n";


    if ($info->PT == 1 && $info->AV == 1) {

        echo "<div class='form-group'>\n";
        echo "<label class='col-sm-2 control-label'>Akkrediteur PT</label>\n";
        echo "<div class='col-sm-10'>\n";
        echo "<p class='form-control-static'>" . $vakkrediteurPT . "</p>\n";
        echo "</div></div>\n";

        echo "<div class='form-group'>\n";
        echo "<label class='col-sm-2 control-label'>Akkrediteur AV</label>\n";
        echo "<div class='col-sm-10'>\n";
        echo "<p class='form-control-static'>" . $vakkrediteurAV . "</p>\n";
        echo "</div></div>\n";

    } elseif ($info->PT == 1 && $info->AV == 0) {

        echo "<div class='form-group'>\n";
        echo "<label class='col-sm-2 control-label'>Akkrediteur</label>\n";
        echo "<div class='col-sm-10'>\n";
        echo "<p class='form-control-static'>" . $vakkrediteurPT . "</p>\n";
        echo "</div></div>\n";

    } elseif ($info->PT == 0 && $info->AV == 1) {

        echo "<div class='form-group'>\n";
        echo "<label class='col-sm-2 control-label'>Akkrediteur</label>\n";
        echo "<div class='col-sm-10'>\n";
        echo "<p class='form-control-static'>" . $vakkrediteurAV . "</p>\n";
        echo "</div></div>\n";

    }

    echo "<div class='form-group'>\n";
    echo "<div class='col-sm-offset-2 col-sm-10'>\n";
    if ($action == "edit") {
        echo "<input type='hidden' name='fakkid' value='". $vid ."'>\n";
        echo "<input type='hidden' name='fmnr' value='". $rows[$i]['mitgliedsnummer']. "'>\n";
        echo "<input type='submit' class='btn btn-primary' name='fedit' value='Senden'>\n";
    } elseif ($action == "new") {
        echo "<input type='hidden' name='fakkid' value='0'>\n";
        echo "<input type='hidden' name='fmnr' value='0'>\n";
        echo "<input type='submit' class='btn btn-primary' name='fnew' value='Anlegen'>\n";
    }
    echo "<input type='submit' class='btn btn-default' name='feditcancel' value='Abbruch'>\n";
    echo "</div></div>\n";

    echo "</form>\n";


if ($action == "edit") {
echo <<<STUFF
<h2>Gezahlte Beiträge</h2>
<table class="table">
<tr><th>Mnr</th><th>Jahr</th><th>Beitrag Soll</th><th>Beitrag Ist</th><th>Datum Ist</th><th>Bemerkung</th></tr>
STUFF;
    for ($i = 0; $i < count($rowb); $i++) {
        echo "<tr>";
        tdr($rowb[$i]['mnr']);
        tdr($rowb[$i]['opjahr']);
        tdr($rowb[$i]['beitragsoll']);
        tdr($rowb[$i]['beitragist']);
        td((intval($rowb[$i]['datumist'])) != 0 ? $rowb[$i]['datumist'] : "");
        td($rowb[$i]['bemerkung']);
        echo "</tr>\n";
    }
    for ($i = 0; $i < count($rowpay); $i++) {
        echo "<tr>";
        tdr($rowpay[$i]['mitgliedsnummer']);
        td("BAR");
        td("");
        tdr($rowpay[$i]['gezahlt']);
        td($rowpay[$i]['geaendert']);
        td($rowpay[$i]['kommentar']);
        echo "</tr>\n";
    }
echo <<<STUFF2
</table>
<h2>Bisherige Änderungen</h2>
<table class="table">
<tr><th>Mnr</th><th>Nachname</th><th>Vorname</th><th>LV</th><th>KV</th><th>Straße</th><th>PLZ</th><th>Ort</th><th>Nat.</th><th>Geburtsdatum</th><th>Kommentar</th><th>Akkrediteur</th><th>Geändert</th></tr>
STUFF2;

    $sqla = "SELECT * FROM tbladress WHERE akkID = :akkid";
    $rsa = $db->prepare($sqla);
    $rsa->bindParam(':akkid', $akkid, PDO::PARAM_INT);
    $rsa->execute();
    $rowa = $rsa->fetchAll();
    for ($i = 0; $i < count($rowa); $i++) {
        echo "<tr>";
        tdr($rowa[$i]['mitgliedsnummer']);
        td($rowa[$i]['nachname']);
        td($rowa[$i]['vorname']);
        td($rowa[$i]['lv']);
        td($rowa[$i]['kv']);
        td($rowa[$i]['strasse']);
        td($rowa[$i]['plz']);
        td($rowa[$i]['ort']);
        td($rowa[$i]['nation']);
        td($rowa[$i]['geburtsdatum']);
        td($rowa[$i]['kommentar']);
        td($rowa[$i]['akkrediteur']);
        td($rowa[$i]['geaendert']);
        echo "</tr>\n";
    }
    echo "</table>\n";
}
