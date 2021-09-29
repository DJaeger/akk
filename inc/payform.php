<?php
$mnr = intval($rows[0]['mitgliedsnummer']);
$sqlb = "SELECT * FROM tblbeitrag WHERE mnr = :mnr order by opjahr";
$rsb = $db->prepare($sqlb);
$rsb->bindParam(':mnr', $mnr, PDO::PARAM_INT);
$rsb->execute();
$rowb = $rsb->fetchAll();

?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<table class="table">
<colgroup>
<col width="9%">
<col width="10%">
<col width="10%">
<col width="4%">
<col width="6%">
<col width="10%">
<col width="30%">
<col width="20%">
</colgroup>
<tr>
<th>Mnr</th>
<th>Nachname</th>
<th>Vorname</th>
<th>LV</th>
<th>Offen</th>
<th>Bezahlt</th>
<th>Anmerkung</th>
<th></th>
</tr>
<tr>
<?php
$i = 0;
$vid = $rows[$i]['akkID'] ;
if (is_null($rows[$i]['mitgliedsnummer']) || $rows[$i]['mitgliedsnummer'] == '') {
    $mnrref = $rows[$i]['refcode'];
    $c = "refc";
}
else {
    $mnrref = $rows[$i]['mitgliedsnummer'];
    $c = "";
}

tdr($mnrref,$c);
td($rows[$i]['nachname']);
td($rows[$i]['vorname']);
td($rows[$i]['lv']);
tdz($rows[$i]['offenerbeitrag']);
echo "<td><input type='Text' class='form-control' name='zahlbetrag' id='zahlbetrag' size='1'></td>";
echo "<td><input type='Text' class='form-control' name='kommentar' id='kommentar' size='1'></td>";
echo "<td class='text-right'>";
echo "<input class='btn btn-primary' type='submit' name='paid' value='Bezahlen!'>";
echo "<input class='btn btn-default cancel' type='submit' name='paycancel' value='Abbruch'";
echo "</td>";

?>
</tr>
</table>
<input type="hidden" name="fakkid" value="<?php echo $vid; ?>" >
<input type="hidden" name="mnr" value="<?php echo $mnrref; ?>" >
<input type="hidden" name="offen" value="<?php echo $rows[$i]['offenerbeitrag']; ?>" >
</form>

<h2>Gezahlte Beiträge</h2>
<table class="table">
<tr><th>Mnr</th><th>Jahr</th><th>Beitrag Soll</th><th>Beitrag Ist</th><th>Datum Ist</th><th>Bemerkung</th></tr>
<?php
echo "<tr>";
for ($i=0; $i<count($rowb); $i++) {
    tdr($rowb[$i]['mnr']);
    tdr($rowb[$i]['opjahr']);
    tdr($rowb[$i]['beitragsoll']);
    tdr($rowb[$i]['beitragist']);
    td((intval($rowb[$i]['datumist'])) != 0 ? $rowb[$i]['datumist'] : "");
    td($rowb[$i]['bemerkung']);
    echo "</tr>\n";
}
?>
</table>
