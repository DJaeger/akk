<?php
$id="postallist";
ini_set('include_path', 'inc');
include("db.php");
include("define.php");
include("head.php");
$db = new mydb();


if ($info->PT == 1) {
?>
    <h1>Briefwahl Adressliste PT</h1>

    <table class="table table-bordered">
        <colgroup>
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
        </colgroup>
<?php

    // reset variable
    $num_rows = 0;

    $mnr = intval($_REQUEST['mnr']);
    $sql = "SELECT DISTINCTROW * FROM tblakk WHERE akkPT = 1 ORDER BY mitgliedsnummer";
    $rs = $db->prepare($sql);
    $rs->execute();
    $rows = $rs->fetchAll();
    $num_rows = count($rows);

    if ($num_rows > 0) {
        for ($i=0; $i<count($rows); $i++) {
            echo "<tr>";
            if (is_null($rows[$i]['mitgliedsnummer'])) {
                td($rows[$i]['refcode']);
            } else {
                td($rows[$i]['mitgliedsnummer']);
            }
            td($rows[$i]['vorname']);
            td($rows[$i]['nachname']);
            td($rows[$i]['strasse']);
            td($rows[$i]['plz']);
            td($rows[$i]['ort']);

            echo "</tr>\n";
        }
    }

?>
    </table>
<?php
}

if ($info->AV == 1) {
?>
    <h1>Briefwahl Adressliste AV</h1>

    <table class="table table-bordered">
        <colgroup>
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
        </colgroup>
<?php

    // reset variable
    $num_rows = 0;

    $mnr = intval($_REQUEST['mnr']);
    $sql = "SELECT DISTINCTROW * FROM tblakk WHERE akkPT = 1 ORDER BY mitgliedsnummer";
    $rs = $db->prepare($sql);
    $rs->execute();
    $rows = $rs->fetchAll();
    $num_rows = count($rows);

    if ($num_rows > 0) {
        for ($i=0; $i<count($rows); $i++) {
            echo "<tr>";
            if (is_null($rows[$i]['mitgliedsnummer'])) {
                td($rows[$i]['refcode']);
            } else {
                td($rows[$i]['mitgliedsnummer']);
            }
            td($rows[$i]['vorname']);
            td($rows[$i]['nachname']);
            td($rows[$i]['strasse']);
            td($rows[$i]['plz']);
            td($rows[$i]['ort']);

            echo "</tr>\n";
        }
    }

?>
    </table>
<?php
}

include("footer.php");
