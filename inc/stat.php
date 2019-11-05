<?php

$db = new mydb();
if ($info->ebene == "LV") {
  $selebene="kv";
  $selhead="KV";
} else if ($info->ebene == "KV") {
  $selebene="ort";
  $selhead="Ort";
} else { # Ebene = BV
  $selebene="lv";
  $selhead="LV";
}

$sql = "select count(akkId) AS mitglieder,
			   sum(akk) as akkreditiert,
			   sum(akkav) as akkreditiertAV,
			   sum(offenerbeitrag<1) AS stimmb,
			   sum(IF (nation IN ('" . implode("','", $info->EU) . "'),1,0)) AS stimmbAV
		from tblakk";
$bigrow = $db->query($sql)->fetch();

$sql = "select " . $selebene . ",
			   count(akkId) AS mitglieder,
			   sum(akk) as akkreditiert,
			   sum(akkav) as akkreditiertAV,
			   sum(offenerbeitrag<1) AS stimmb,
			   sum(IF (nation IN ('" . implode("','", $info->EU) . "'),1,0)) AS stimmbAV
	from tblakk
	group by " . $selebene . "
	order by " . $selebene . "";
$q=$db->query($sql);
echo "<table>\n";
if ($info->AV == "1") {
	echo "<tr><thead>";
	echo "<th>" . $selhead . "</th>";
	echo "<th title=\"Mitglieder\">Mtgld</th>";
	echo "<th title=\"Stimmberechtigte Parteitag\">Stimmb. PT</th>";
	echo "<th>%</th>";
	echo "<th title=\"Akkreditierte Parteitag\">Akk. PT</th>";
	echo "<th title=\"Stimmgewicht auf dem Parteitag\">Anteil PT</th>";
	echo "<th title=\"Stimmberechtigte Aufstellungsversammlung\">Stimmb. AV</th>";
	echo "<th title=\"Akkreditierte Aufstellungsversammlung\">Akk. AV</th>";
	echo "<th title=\"Stimmgewicht auf der Aufstellungsversammlung\">Anteil AV</th>";
	echo "</tr></thead>\n";
} else {
	echo "<tr><thead>";
	echo "<th>" . $selhead . "</th>";
	echo "<th title=\"Mitglieder\">Mtgld</th>";
	echo "<th title=\"Stimmberechtigte\">Stimmb.</th>";
	echo "<th>%</th>";
	echo "<th title=\"Akkreditierte\">Akk.</th>";
	echo "<th title=\"Anteil Akkreditierte / Mitglieder\">% Akk. / Mtgld</th>";
	echo "<th title=\"Anteil Akkreditierte / Stimmberechtigte\">Akk. Stimmb.</th>";
	echo "<th title=\"Stimmgewicht auf dem Parteitag\">Parteitag Anteil</th>";
	echo "</tr></thead>\n";
}
echo "<tbody>";
while ($row=$q->fetch()) {

    echo "<tr>";
    td($row[$selebene]);
    td($row['mitglieder'],"r");
    td($row['stimmb'],"r");
    if ($row['mitglieder'] == 0)
      	td("");
    else
      	td(number_format(100 * $row['stimmb'] / $row['mitglieder'],1) . "&nbsp;%","r");
    td($row['akkreditiert'],"r");
    if ($info->AV == "1") {
	    if ($bigrow['akkreditiert'] == 0)
	    	td("");
	    else
	      	td(number_format(100 * $row['akkreditiert'] / $bigrow['akkreditiert'],1) . "&nbsp;%","r");

      	td($row['stimmbAV'],"r");
      	td($row['akkreditiertAV'],"r");

	    if ($bigrow['akkreditiertAV'] == 0)
	    	td("");
	    else
	      	td(number_format(100 * $row['akkreditiertAV'] / $bigrow['akkreditiertAV'],1) . "&nbsp;%","r");
	} else {
	    if ($row['mitglieder'] == 0)
	    	td("");
	    else
	    	td(number_format(100 * $row['akkreditiert'] / $row['mitglieder'],1) . "&nbsp;%","r");

	    if ($row['stimmb'] == 0)
	      	td("");
	    else
	      	td(number_format(100 * $row['akkreditiert'] / $row['stimmb'],1) . "&nbsp;%","r");

	    if ($bigrow['akkreditiert'] == 0)
	    	td("");
	    else
	      	td(number_format(100 * $row['akkreditiert'] / $bigrow['akkreditiert'],1) . "&nbsp;%","r");
	}

    echo "</tr>\n";
}
echo "</tbody>\n";
$row = $bigrow;
echo "<tfoot><tr>";
td("Summe");
td($row['mitglieder'],"r");
td($row['stimmb'],"r");
if ($row['mitglieder'] == 0)
  td("");
else
  td(number_format(100 * $row['stimmb'] / $row['mitglieder'],2) . " %","r");
td($row['akkreditiert'],"r");
if ($info->AV == "1") {
	td("");
  	td($row['stimmbAV'],"r");
  	td($row['akkreditiertAV'],"r");
	td("");
} else {
	if ($row['mitglieder'] == 0)
	  	td("");
	else
	  	td(number_format(100 * $row['akkreditiert'] / $row['mitglieder'],2) . " %","r");

	if ($row['stimmb'] == 0)
	  	td("");
	else
	  	td(number_format(100 * $row['akkreditiert'] / $row['stimmb'],2) . " %","r");

	td("");
}
echo "</tr></tfoot></table>\n";
?>
