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
    <tr>
        <th>Mnr</th>
        <th>Nachname</th>
        <th>Vorname</th>
        <th>LV</th>
        <th>Adresse</th>
        <th>Offen</th>
        <th class="c">Akk</th>
        <th class="c">pay</th>
        <th>Edit / Info</th>
    </tr>
<?php
if ($num_rows > 0) {
    for ($i=0; $i<count($rows); $i++) {
        $zusatz = "";
        $cr = "";
        $zusatzAV = "";
        $crAV = "";
		$datum18 = strtotime("-18 years");
		if (!is_null($rows[$i]['geburtsdatum']) && $rows[$i]['geburtsdatum'] != '1900-01-01') {
			$datummid = strtotime($rows[$i]['geburtsdatum']);
		} else {
			$datummid = $datum18;
		}

        if ($rows[$i]['warnung'] == "1") {            // Spezialbehandlung erfoderlich
            $cr = "warnung";
            $crAV = "warnung";
        }
        elseif ($rows[$i]['warnung'] == "S") {        // Ist gesperrt und kann nicht akkreditiert werden
            $cr = "gesperrt";
            $crAV = "gesperrt";
			$zusatz = " gesperrt! ";
			$zusatzAV = " gesperrt! ";
        }
        elseif ($rows[$i]['akk'] == 1) {              // bereits akkreditiert
            $cr = "akkreditiert";
            $crAV = "akkreditiert";
        }
        elseif ($rows[$i]['schwebend'] == -1) {       // muss noch entschwebt werden. noch klären, wo offener Beitrag errechnet weird
            $cr =  "schwebend";
            $crAV =  "schwebend";
            $zusatz = " schwebend! ";
            $zusatzAV = " schwebend! ";
        } else {
			if ($rows[$i]['offenerbeitrag'] == 0 && $rows[$i]['schwebend'] == 0) {   // hat keinen offenen Beitrag und nicht schwebend
				$cr =  "akkreditierbar";
			} else if ($rows[$i]['offenerbeitrag'] != 0) {   // hat noch offenen Beitrag
				$cr = "offenerbeitrag";
			}
			if (!in_array($rows[$i]['nation'],$info->EU) ) {
				$crAV =  "offenerbeitrag";
				$zusatzAV = " nation! ";
			} elseif ($datummid > $datum18) {
				$crAV =  "offenerbeitrag";
				$zusatzAV = " alter! ";
			} else {
				$crAV =  "akkreditierbar";
			}
		}
        echo "<tr class='".$cr."'>";
        if (is_null($rows[$i]['mitgliedsnummer'])) {
            $mnrref = $rows[$i]['refcode'];
            $c = "refc";
        } else {
            $mnrref = $rows[$i]['mitgliedsnummer'];
            $c = "";
        }

        $id = $rows[$i]['akkID'] ;
        $adr = $rows[$i]['strasse'] . "<br>" . $rows[$i]['plz'] . " " . $rows[$i]['ort'];
        tdr($mnrref, $c);
        td("<abbr datatitle='".$rows[$i]['geburtsdatum']."'> " .$rows[$i]['nachname'].  " </abbr>");
        td($rows[$i]['vorname']);
        td($rows[$i]['lv']);
        td($adr, "mini");
        tdz($rows[$i]['offenerbeitrag']);

		if ($rows[$i]['akk'] == 1) {
			$button = "<input class='akkbutton' type='submit' name='deakk[$id]' value='DeAkk'>";
		} elseif ($rows[$i]['offenerbeitrag'] == 0 && $rows[$i]['warnung'] != 'S') {
			$button = "<input class='akkbutton' type='submit' name='akk[$id]' value='Akk'>";
		} else {
			$button = "";
		}
        td($button, "c");

        if ($rows[$i]['offenerbeitrag'] == 0 && is_null($rows[$i]['pid'])  ) {
            $button = "";
        } elseif ($rows[$i]['offenerbeitrag'] == 0 && !(is_null($rows[$i]['pid']) ) ) {
            $button = "<input class='akkbutton deakk' type='submit' name='unpay[$id]' value='Unpay'>";
        } else  {
            $button = "<input class='akkbutton' type='submit' name='pay[$id]' value='Pay'>";
        }
        td($button, "c");
        $button = "<input class='akkbutton akkedit fr' type='submit' name='edit[$id]' value='Edit'>";
        td($zusatz . $rows[$i]['kommentar'] . $button);
        echo "</tr>\n";

        if ($info->AV == "1") {
			echo "<tr class='".$crAV."'>";
			td("");
			td("");
			td("");
			td("");
			td("Nationalit&auml;t: " . $rows[$i]['nation']);
			td("");
			if ($rows[$i]['akkav'] == 1) {
				$button = "<input class='akkbutton' type='submit' name='deakkav[$id]' value='DeAkkAV'>";
				td($button, "c");
			} elseif ($rows[$i]['schwebend'] == 0 && $rows[$i]['warnung'] != 'S' && in_array($rows[$i]['nation'],$info->EU) && $datummid <= $datum18) {
				$button = "<input class='akkbutton' type='submit' name='akkav[$id]' value='AkkAV'>";
				td($button, "c");
			} else {
				if ($rows[$i]['schwebend'] != 0)
					td("Schwebend");
				elseif ($rows[$i]['warnung'] == 'S')
					td("Gesperrt");
				else if ($datummid > $datum18)
					td("Alter");
				else
					td("Nation");
			}
			td("");
			td("");
			echo "</tr>\n";
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