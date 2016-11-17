<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<table class="akk">
<colgroup>
<col width="9%">
<col width="10%">
<col width="10%">
<col width="4%">
<col width="20%">
<col width="6%">
<col width="8%">
<col width="8%">
<col width="24%">
</colgroup>
<?php
if ($num_rows > 0) {
    for ($i=0; $i<count($rows); $i++) {
        $zusatz = "";
        if ($rows[$i]['warnung'] != "") {              // Spezialbehandlung erfoderlich
            $cr = "warning";
        }
        elseif ($rows[$i]['akk'] == 1) {               // bereits akkreditiert
            $cr = "akkreditiert";
        }
        elseif ($rows[$i]['schwebend'] == -1) {       // muss noch entschwebt werden. noch klären, wo offener Beitrag errechnet weird
            $cr =  "schwebend";
            $zusatz = " schwebend!";
        }
        elseif ((($info->typ == "PT" && $rows[$i]['offenerbeitrag'] == 0) || $info->typ == "AV") && $rows[$i]['schwebend'] == 0) {   // hat keinen offenen Beitrag, wenn keine AV und nicht schwebend
            $cr =  "akkreditierbar";
        }
        elseif ($rows[$i]['offenerbeitrag'] != 0) {   // hat noch offenen Beitrag
            $cr = "offenerbeitrag";
        }
        echo "<tr class='".$cr."'>";
        if (is_null($rows[$i]['mitgliedsnummer'])) {
            $mnrref = $rows[$i]['refcode'];
            $c = "refc";
        }
        else {
            $mnrref = $rows[$i]['mitgliedsnummer'];
            $c = "";
        }
        $id = $rows[$i]['akkID'] ;
        $adr = $rows[$i]['strasse'] . "<br>" . $rows[$i]['plz'] . " " . $rows[$i]['ort'];
        tdr($mnrref, $c);
        echo "<td><abbr datatitle='".$rows[$i]['geburtsdatum']."'> " .$rows[$i]['nachname'].  " </abbr> </td>";
        td($rows[$i]['vorname']);
        td($rows[$i]['lv']);
        td($adr, "mini");
        tdz($rows[$i]['offenerbeitrag']);

        if ($rows[$i]['akk'] == 1) {
            $button = "<input class='akkbutton' type='submit' name='deakk[$id]' value='DeAkk'>";
        }
        elseif (($info->typ == "PT" && $rows[$i]['offenerbeitrag'] == 0) || $info->typ == "AV") {
            $button = "<input class='akkbutton' type='submit' name='akk[$id]' value='Akk'>";
        }
        else {
            $button = "";
        }
        td($button, "c");

        if ($rows[$i]['offenerbeitrag'] == 0 && is_null($rows[$i]['pid'])  ) {
            $button = "";
        }
        elseif ($rows[$i]['offenerbeitrag'] == 0 && !(is_null($rows[$i]['pid']) ) ) {
            $button = "<input class='akkbutton deakk' type='submit' name='unpay[$id]' value='Unpay'>";
        }
       else  {
            $button = "<input class='akkbutton' type='submit' name='pay[$id]' value='Pay'>";
        }
        td($button, "c");
        $button = "<input class='akkbutton akkedit fr' type='submit' name='edit[$id]' value='Edit'>";
        td($zusatz . $rows[$i]['kommentar'] . $button);
        echo "</tr>\n";
    }
}

?>
</table>
</form>