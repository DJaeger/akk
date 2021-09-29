<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<table class="table table-condensed">
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
    <tr>
        <th>Mnr</th>
        <th>Nachname</th>
        <th>Vorname</th>
        <th>LV</th>
        <th>Adresse</th>
<?php
if ( $info->PT == 0 && $info->AV == 1 ) {
    th("Nationalit&auml;t");
} else {
    th("Offen");
}
?>
        <th class="c">Akk</th>
        <th class="c">pay</th>
        <th>Edit / Info</th>
    </tr>
<?php
if ($num_rows > 0) {
    for ($i=0; $i<count($rows); $i++) {
        $zusatzPT = "";
        $crPT = "";
        $zusatzAV = "";
        $crAV = "";
        $datum18 = strtotime("-18 years");
        if (!is_null($rows[$i]['geburtsdatum']) && $rows[$i]['geburtsdatum'] != '1900-01-01') {
            $datummid = strtotime($rows[$i]['geburtsdatum']);
        } else {
            $datummid = $datum18;
        }

        if ($rows[$i]['warnung'] == "1") {            // Spezialbehandlung erfoderlich
            $crPT = "warnung";
            $crAV = "warnung";
        } elseif ($rows[$i]['warnung'] == "S") {        // Ist gesperrt und kann nicht akkreditiert werden
            $crPT = "gesperrt";
            $crAV = "gesperrt";
            $zusatzPT = " gesperrt! ";
            $zusatzAV = " gesperrt! ";
        } elseif ($rows[$i]['schwebend'] == -1) {       // muss noch entschwebt werden. noch klären, wo offener Beitrag errechnet weird
            $crPT =  "schwebend";
            $crAV =  "schwebend";
            $zusatzPT = " schwebend! ";
            $zusatzAV = " schwebend! ";
        } else {
            if ($rows[$i]['akkPT'] == 1) {              // bereits akkreditiert für PT
                $crPT = "akkreditiert";
            } elseif ($rows[$i]['offenerbeitrag'] == 0 && $rows[$i]['schwebend'] == 0) {   // hat keinen offenen Beitrag und nicht schwebend
				$crPT =  "akkreditierbar";
			} else if ($rows[$i]['offenerbeitrag'] != 0) {   // hat noch offenen Beitrag
				$crPT = "offenerbeitrag";
			}
            if ($rows[$i]['akkAV'] == 1) {              // bereits akkreditiert für AV
                $crAV = "akkreditiert";
            } elseif (!in_array($rows[$i]['nation'],$info->nations) ) {
				$crAV =  "offenerbeitrag";
				$zusatzAV = " nation! ";
			} elseif ($datummid > $datum18) {
				$crAV =  "offenerbeitrag";
				$zusatzAV = " alter! ";
			} else {
				$crAV =  "akkreditierbar";
			}
		}
        
        if (is_null($rows[$i]['mitgliedsnummer'])) {
            $mnrref = $rows[$i]['refcode'];
            $c = "refc";
        } else {
            $mnrref = $rows[$i]['mitgliedsnummer'];
            $c = "";
        }
        $id = $rows[$i]['akkID'] ;
        $adr = $rows[$i]['strasse'] . "<br>" . $rows[$i]['plz'] . " " . $rows[$i]['ort'];

        if ($info->PT == 1) {
            echo "<tr class='".$crPT."'>";

            tdr($mnrref, $c);
            td("<abbr datatitle='".$rows[$i]['geburtsdatum']."'> " .$rows[$i]['nachname'].  " </abbr>");
            td($rows[$i]['vorname']);
            td($rows[$i]['lv']);
            td($adr, "mini");
            tdz($rows[$i]['offenerbeitrag']);

            if ($rows[$i]['akkPT'] == 1) {
                $button = "<input class='btn btn-danger' type='submit' name='deakkpt[$id]' value='DeAkkPT'>";
            } elseif ( $rows[$i]['schwebend'] == 0 && $rows[$i]['offenerbeitrag'] == 0 && $rows[$i]['warnung'] != 'S' ) {
                $button = "<input class='btn btn-default' type='submit' name='akkpt[$id]' value='AkkPT'>";
            } else {
                $button = "";
            }
            td($button, "c");

            if ( $rows[$i]['offenerbeitrag'] == 0 && is_null($rows[$i]['pid'] ) ) {
                $button = "";
            } elseif ( $rows[$i]['schwebend'] == 0 && $rows[$i]['offenerbeitrag'] == 0 && !is_null($rows[$i]['pid']) ) {
                $button = "<input class='btn btn-danger' type='submit' name='unpay[$id]' value='Unpay'>";
            } else  {
                $button = "<input class='btn btn-default' type='submit' name='pay[$id]' value='Pay'>";
            }
            td($button, "c");
            $button = "<input class='btn btn-default akkedit fr' type='submit' name='edit[$id]' value='Edit'>";
            td($zusatzPT . $rows[$i]['kommentar'] . $button);
            echo "</tr>\n";
		}


        if ($info->AV == 1) {
			echo "<tr class='".$crAV."'>";
            if ($info->PT == 1) {
                td("");
                td("");
                td("");
                td("");
                td("Nationalit&auml;t: " . $rows[$i]['nation'], "mini");
                td("");
            } else {
                tdr($mnrref, $c);
                td("<abbr datatitle='".$rows[$i]['geburtsdatum']."'> " .$rows[$i]['nachname'].  " </abbr>");
                td($rows[$i]['vorname']);
                td($rows[$i]['lv']);
                td($adr, "mini");
                td($rows[$i]['nation']);
            }
			if ($rows[$i]['akkAV'] == 1) {
				$button = "<input class='btn btn-danger' type='submit' name='deakkav[$id]' value='DeAkkAV'>";
			} elseif ($rows[$i]['schwebend'] == 0 && $rows[$i]['warnung'] != 'S' && in_array($rows[$i]['nation'],$info->nations) && $datummid <= $datum18) {
				$button = "<input class='btn btn-default' type='submit' name='akkav[$id]' value='AkkAV'>";
            } else {
                $button = "";
			}
            td($button, "c");
            if ($info->PT == 1) {
                td("");
                td($zusatzAV);
            } else {
                // Pay Button
                if ( $rows[$i]['offenerbeitrag'] == 0 && is_null($rows[$i]['pid'] ) ) {
                    $button = "";
                } elseif ( $rows[$i]['schwebend'] == 0 && $rows[$i]['offenerbeitrag'] == 0 && !is_null($rows[$i]['pid']) ) {
                    $button = "<input class='btn btn-danger' type='submit' name='unpay[$id]' value='Unpay'>";
                } else  {
                    $button = "<input class='btn btn-default' type='submit' name='pay[$id]' value='Pay'>";
                }
                td($button, "c");
                // Edit Button
                $button = "<input class='btn btn-default akkedit fr' type='submit' name='edit[$id]' value='Edit'>";
                td($zusatzAV . $rows[$i]['kommentar'] . $button);
            }
			echo "</tr>\n";
		}

        if ( $info->AV == 1 && $info->PT == 1 ) {
			echo "<tr>";
			td("&nbsp;");
			td("");
			td("");
			td("");
			td("");
			td("");
			td("");
			td("");
			td("");
			echo "</tr>\n";
		}
    }
}

?>
</table>
</form>
