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
    $voffen = $rows[$i]['offenerbeitrag'];
    $vgebdat = $rows[$i]['geburtsdatum'];
    $vkommentar = $rows[$i]['kommentar'];
    $vwarnung = $rows[$i]['warnung'];
    $vakkrediteur = $rows[$i]['akkrediteur'];
}
else {
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
    $voffen = "";
    $vgebdat = "";
    $vkommentar = "";
    $vwarnung = "0";
    $vakkrediteur = $info->akkuser;
}

echo "<form action='index.php' method='POST'>\n";
echo "<table>\n";
echo "<tr>";
th("Mitgliedsnummer");
td($mnr);
echo "</tr>\n";
echo "<tr>";
th("Refcode");
$field = "<input type='Text' class='w20' name='refcode' id='refcode' value='".$vrefcode."'>";
td($field);
echo "</tr>\n";
echo "<tr>";
th("Nachname");
$field = "<input type='Text' class='w40' name='nachname' id='nachname' value='".$vnachname."'>";
td($field);
echo "</tr>\n";
echo "<tr>";
th("Vorname");
$field = "<input type='Text' class='w40' name='vorname' id='vorname' value='".$vvorname."'>";
td($field);
echo "</tr>\n";
echo "<tr>";
th("LV");
$field = "<input type='Text' class='w20' name='lv' id='lv' value='".$vlv."'>";
td($field);
echo "</tr>\n";
th("KV");
$field = "<input type='Text' class='w20' name='kv' id='kv' value='".$vkv."'>";
td($field);
echo "</tr>\n";
echo "<tr>";
th("Straße");
$field = "<input type='Text' class='w40' name='strasse' id='strasse' value='".$vstrasse."'>";
td($field);
echo "</tr>\n";
echo "<tr>";
th("PLZ");
$field = "<input type='Text' class='w40' name='plz' id='plz' value='".$vplz."'>";
td($field);
echo "</tr>\n";
echo "<tr>";
th("Ort");
$field = "<input type='Text' class='w40' name='ort' id='ort' value='".$vort."'>";
td($field);
echo "</tr>\n";
echo "<tr>";
th("Offener Beitrag");
if ($action == "new") {
    $field = "<input type='Text' class='w20' name='offenerbeitrag' id='offenerbeitrag' value='".$voffen."'>";
    td($field);
}
else {
    td($voffen);
}
echo "</tr>\n";    
echo "<tr>";
th("Geburtsdatum");
td($vgebdat);
echo "</tr>\n";    
echo "<tr>";
th("Kommentar");
$field = "<textarea name='kommentar' id='kommentar' cols='50' rows='3' maxlength='255'>" . $vkommentar . "</textarea>";
td($field);
echo "</tr>\n";
echo "<tr>";
th("Warnung");
function selected($option){
	global $vwarnung;
	if($vwarnung==$option) {
		return 'selected="selected"';
	} else {
		return '';
	}
};
$field = '<select name="warnung" id="warnung"><option value="0" '.selected("0").'>Nein</option><option value="1" '.selected("1").'>Ja</option><option value="2" '.selected("2").'>Gesperrt</option></select>';
td($field);
echo "</tr>\n";
echo "<tr>";
th("Akkrediteur");
td($vakkrediteur);
echo "</tr>\n";
echo "</table>\n";
if ($action == "edit") {
    echo "<input type='hidden' name='fakkid' value='". $vid ."' >\n";
    echo "<input type='hidden' name='fmnr' value='". $rows[$i]['mitgliedsnummer']. "' >\n";
    echo "<p><input class='payedit' type='submit' name='fedit' value='Senden'></p>\n";
}
elseif ($action == "new") {
    echo "<input type='hidden' name='fakkid' value='0' >\n";
    echo "<input type='hidden' name='fmnr' value='0' >\n";
    echo "<p><input class='payedit' type='submit' name='fnew' value='Anlegen'></p>\n";
}
echo "<p><input class='payedit cancel' type='submit' name='feditcancel' value='Abbruch'></p>\n";
echo "</form>\n";


if ($action == "edit") {
echo <<<STUFF
<h2>Gezahlte Beiträge</h2>
<table>
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
<table>
<tr><th>Mnr</th><th>Nachname</th><th>Vorname</th><th>LV</th><th>KV</th><th>Straße</th><th>PLZ</th><th>Ort</th><th>Kommentar</th><th>Akkrediteur</th><th>Geändert</th></tr>
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
        td($rowa[$i]['kommentar']);
        td($rowa[$i]['akkrediteur']);
        td($rowa[$i]['geaendert']);
        echo "</tr>\n";
    }
    echo "</table>\n";
}
?>
