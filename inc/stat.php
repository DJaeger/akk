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

$sql = "select count(akkId) AS mitglieder,sum(akk) as akkreditiert";
if ($info->typ == "PT") {
	$sql .= ", sum(offenerbeitrag<1) AS stimmb";
} else { # Typ = AV
	$sql .= ", count(akkId) AS stimmb";
}
	$sql .= " from tblakk";
$bigrow = $db->query($sql)->fetch();

$sql = "select " . $selebene . ", count(akkId) AS mitglieder,sum(akk) as akkreditiert";
if ($info->typ == "PT") {
	$sql .= ", sum(offenerbeitrag<1) AS stimmb";
} else { # Typ = AV
	$sql .= ", count(akkId) AS stimmb";
}
$sql .= " from tblakk group by " . $selebene . " order by " . $selebene . "";
$q=$db->query($sql);
echo "<table><tr><thead><th>" . $selhead . "</th><th title=\"Mitglieder\">Mtgld</th><th title=\"Stimmberechtigte\">Stimmb.</th><th>%</th><th title=\"Akkreditierte\">Akk.</th><th title=\"Anteil Akkreditierte / Mitglieder\">% Akk. / Mtgld</th><th title=\"Anteil Akkreditierte / Stimmberechtigte\">Akk. Stimmb.</th><th title=\"Stimmgewicht auf dem Parteitag\">Parteitag Anteil</th></tr></thead>\n";
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
if ($row['mitglieder'] == 0)
  td("");
else
  td(number_format(100 * $row['akkreditiert'] / $row['mitglieder'],2) . " %","r");
if ($row['stimmb'] == 0)
  td("");
else
  td(number_format(100 * $row['akkreditiert'] / $row['stimmb'],2) . " %","r");
td("");
echo "</tr></tfoot></table>\n";
?>
