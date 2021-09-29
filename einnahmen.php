<?php
$id="einnahmen";
ini_set('include_path', 'inc');
include("db.php");
include("define.php");
include("head.php");
$db = new mydb();

$sql = "SELECT p.mitgliedsnummer, a.nachname, a.vorname, p.gezahlt, p.akkrediteur, p.geaendert, p.kommentar FROM tblpay p JOIN tblakk a ON p.akkID = a.akkID ORDER BY p.geaendert";
$q=$db->query($sql);
echo "<table class='table table-borderes'>\n";
echo "<thead><tr>";
th("Mnr");
th("Nachname");
th("Vorname");
th("gezahlt");
th("Akkrediteur");
th("Zeitpunkt");
th("Bemerkung");
echo "</tr></thead>\n";
echo "<tbody>";
while ($row=$q->fetch()) {
    echo "<tr>\n";
    td($row['mitgliedsnummer'],"r");
    td($row['nachname']);
    td($row['vorname']);
    tdz($row['gezahlt']);
    td($row['akkrediteur']);
    td($row['geaendert']);
    td($row['kommentar']);
    echo "</tr>\n";
}
$sql = "SELECT sum(gezahlt) as gesamt FROM tblpay";
$q=$db->query($sql);
$row=$q->fetch();
echo "</tbody>";
echo "<tfoot>";
echo "<tr><td colspan='3'>Gesamt</td>";
tdz($row['gesamt']);
echo "<td colspan='4'>&nbsp;</td>";
echo "</tr></tfoot>\n";
echo "</table>\n";
include("footer.php");
