<?php
$id="start";
ini_set('include_path', 'inc');
include("db.php");
include("define.php");
$db = new mydb();
// reset Variablen
$num_rows = 0;
$akkid = 0;
$action = "akk";
$errmsg = "";

// im life-Betrieb auskommentieren!
$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

// Mitglied wird akkreditiert
if (isset($_REQUEST['akk'])) {
    $k = each($_REQUEST['akk']);
    $akkid = $k['key'];
    $sql = "UPDATE tblakk SET akk = 1, akkrediteur = :akkrediteur, geaendert = now() WHERE akkID = :akkID";
    $rs = $db->prepare($sql);
    $rs->bindParam(':akkID', $akkid, PDO::PARAM_INT);
    $rs->bindParam(':akkrediteur', $info->akkuser, PDO::PARAM_STR);
    $rs->execute();
}

// Mitglied wird deakkreditiert
if (isset($_REQUEST['deakk'])) {
    $k = each($_REQUEST['deakk']);
    $akkid = $k['key'];
    $sql = "UPDATE tblakk SET akk = 0, akkrediteur = :akkrediteur, geaendert = now() WHERE akkID = :akkID";
    $rs = $db->prepare($sql);
    $rs->bindParam(':akkID', $akkid, PDO::PARAM_INT);
    $rs->bindParam(':akkrediteur', $info->akkuser, PDO::PARAM_STR);
    $rs->execute();
}

// Mitglied will bezahlen
if (isset($_REQUEST['pay'])) {
    $k = each($_REQUEST['pay']);
    $akkid = $k['key'];
    $action = "topay";
    $h2 = "Barzahlung auf dem Parteitag";
}

// Bezahlung abgebrochen
if (isset($_REQUEST['paycancel'])) {
    $akkid = $_REQUEST['fakkid'];
}

// Mitglied hat gezahlt
if (isset($_REQUEST['paid'])) {
    if (badinput($_REQUEST['kommentar']) || badinput($_REQUEST['zahlbetrag']) ) {
        die("nice try");
        exit();
    }

    $akkid = $_REQUEST['fakkid'];
    $voffen = $_REQUEST['offen'];
    $vmnr = trim($_REQUEST['mnr']);
    $vzahlbetrag = intval($_REQUEST['zahlbetrag']);
    $vkommentar = trim($_REQUEST['kommentar']);
    $vkommentar = $vkommentar ." | Gezahlt: " . $vzahlbetrag . " EUR";
// neuer Eintrag in tblbeitrag
    $sql = "INSERT INTO tblpay (akkID, mitgliedsnummer, beitragoffen, gezahlt, akkrediteur, geaendert, kommentar) values (:akkid, :mnr, :offen, :gezahlt, :akkrediteur, now(), :kommentar)";
    $rs = $db->prepare($sql);
    $rs->bindParam(':akkid', $akkid, PDO::PARAM_INT);
    $rs->bindParam(':mnr', $vmnr, PDO::PARAM_STR);
    $rs->bindParam(':offen', $voffen, PDO::PARAM_INT);
    $rs->bindParam(':gezahlt', $vzahlbetrag, PDO::PARAM_INT);
    $rs->bindParam(':akkrediteur', $info->akkuser, PDO::PARAM_STR);
    $rs->bindParam(':kommentar', $vkommentar, PDO::PARAM_STR);
    $rs->execute();
// tblakk aktualisieren
    $sql = "UPDATE tblakk SET offenerbeitrag = 0, akkrediteur = :akkrediteur, geaendert = now(), kommentar = concat(kommentar,' | ', :kommentar) WHERE akkID = :akkID";
    $rs = $db->prepare($sql);
    $rs->bindParam(':akkID', $akkid, PDO::PARAM_INT);
    $rs->bindParam(':akkrediteur', $info->akkuser, PDO::PARAM_STR);
    $rs->bindParam(':kommentar', $vkommentar, PDO::PARAM_STR);
    $rs->execute();
}

// Mitglied hat doch nicht bezahlt
if (isset($_REQUEST['unpay'])) {
    $k = each($_REQUEST['unpay']);
    $akkid = $k['key'];
    $sql = "UPDATE tblakk SET akk = 0, offenerbeitrag = offenerbeitragold, kommentar = concat(kommentar,' | doch nicht gezahlt'),  akkrediteur = :akkrediteur, geaendert = now() WHERE akkID = :akkID";
    $rs = $db->prepare($sql);
    $rs->bindParam(':akkID', $akkid, PDO::PARAM_INT);
    $rs->bindParam(':akkrediteur', $info->akkuser, PDO::PARAM_STR);
    $rs->execute();
// neuer Eintrag in tblbeitrag
    $sql = "SELECT MIN(mitgliedsnummer) AS mnr, SUM(gezahlt) AS sum, MIN(beitragoffen) AS offen FROM tblpay WHERE akkID = :akkID";
    $rs = $db->prepare($sql);
    $rs->bindParam(':akkID', $akkid, PDO::PARAM_STR);
    $rs->execute();
    $row=$rs->fetch();
    $sum = -$row['sum'];
    $vmnr = $row['mnr'];
    $voffen = $row['offen'];
    $kommentar = '| Unpay: ' . $sum . ' EUR';

    $sql = "INSERT INTO tblpay (akkID, mitgliedsnummer, beitragoffen, gezahlt, akkrediteur, geaendert, kommentar) values (:akkid, :mnr, :offen, :gezahlt, :akkrediteur, now(), :kommentar)";
    $rs = $db->prepare($sql);
    $rs->bindParam(':akkid', $akkid, PDO::PARAM_INT);
    $rs->bindParam(':mnr', $vmnr, PDO::PARAM_STR);
    $rs->bindParam(':offen', $voffen, PDO::PARAM_INT);
    $rs->bindParam(':gezahlt', $sum, PDO::PARAM_INT);
    $rs->bindParam(':kommentar', $kommentar, PDO::PARAM_INT);
    $rs->bindParam(':akkrediteur', $info->akkuser, PDO::PARAM_STR);
    $rs->execute();
 }

// Mitgliedsdaten editieren
if (isset($_REQUEST['edit'])) {
    $k = each($_REQUEST['edit']);
    $akkid = $k['key'];
    $action = "edit";
    $h2 = "Mitgliedsdaten ändern";
}

// Editieren abgebrochen
if (isset($_REQUEST['feditcancel'])) {
    $akkid = $_REQUEST['fakkid'];
}

// Mitgliedsdaten wurden editiert, oder neues Mitglied angelegt, jetzt eintragen
if (isset($_REQUEST['fedit']) || isset($_REQUEST['fnew'])) {
    if (badinput($_REQUEST['kommentar']) || badinput($_REQUEST['vorname']) || badinput($_REQUEST['nachname']) || badinput($_REQUEST['strasse']) || badinput($_REQUEST['plz']) || badinput($_REQUEST['ort']) || badinput($_REQUEST['lv']) || badinput($_REQUEST['kv']) ) {
        die("nice try");
        exit();
    }
    if (isset($_REQUEST['fnew'])) {
// checken, ob alle Daten eingegeben wurden, sonst zurück auf Los
        if (trim($_REQUEST['vorname']) == "" || trim($_REQUEST['nachname']) == "" || trim($_REQUEST['plz']) == "" || trim($_REQUEST['ort']) == "" || trim ($_REQUEST['strasse']) == "" || trim($_REQUEST['lv']) == "") {
            header("Location: http://".$_SERVER['HTTP_HOST']."/index.php");    
        }
// neues Mitglied in tblakk eintragen
        $sql = "INSERT INTO tblakk (refcode, vorname, nachname, strasse, plz, ort, lv, kv, stimmberechtigung, offenerbeitrag, suchname, suchvname, akk, kommentar, offenerbeitragold) ";
        $sql .= "values('NEU', :vorname, :nachname, :strasse, :plz, :ort, :lv, :kv, 0, :offenerbeitrag, :suchname, :suchvname, 0, :kommentar, :offenerbeitragold)";
        $rs = $db->prepare($sql);
        $rs->bindParam(':vorname', $_REQUEST['vorname'], PDO::PARAM_STR);
        $rs->bindParam(':nachname', $_REQUEST['nachname'], PDO::PARAM_STR);
        $rs->bindParam(':strasse', $_REQUEST['strasse'], PDO::PARAM_STR);
        $rs->bindParam(':plz', $_REQUEST['plz'], PDO::PARAM_STR);
        $rs->bindParam(':ort', $_REQUEST['ort'], PDO::PARAM_STR);
        $rs->bindParam(':lv', $_REQUEST['lv'], PDO::PARAM_STR);
        $rs->bindParam(':kv', $_REQUEST['kv'], PDO::PARAM_STR);
        $rs->bindParam(':offenerbeitrag', $_REQUEST['offenerbeitrag'], PDO::PARAM_INT);
        $rs->bindParam(':suchname', fuzzystring($_REQUEST['nachname']), PDO::PARAM_STR);
        $rs->bindParam(':suchvname', fuzzystring($_REQUEST['vorname']), PDO::PARAM_STR);
        $rs->bindParam(':kommentar', $_REQUEST['kommentar'], PDO::PARAM_STR);
        $rs->bindParam(':offenerbeitragold', $_REQUEST['offenerbeitrag'], PDO::PARAM_INT);
        $rs->execute();
// akkid ermitteln
        $akkid = $db->lastInsertId(); 
// sql für INSERT in tbladress        
        $sql = "INSERT INTO tbladress (akkID, mitgliedsnummer, vorname, nachname, strasse, plz, ort, lv, kv, akkrediteur, geaendert, kommentar, new)  values(:akkid, :mitgliedsnummer, :vorname, :nachname, :strasse, :plz, :ort, :lv, :kv, :akkrediteur, now(), :kommentar, 1)";
   }
    else {       
        $akkid = $_REQUEST['fakkid'];
// sql für INSERT in tbladress        
        $sql = "INSERT INTO tbladress (akkID, mitgliedsnummer, vorname, nachname, strasse, plz, ort, lv, kv, akkrediteur, geaendert, kommentar, edit)  values(:akkid, :mitgliedsnummer, :vorname, :nachname, :strasse, :plz, :ort, :lv, :kv, :akkrediteur, now(), :kommentar, 1)";
    }
// neuen Datensatz in tbladress eintragen
    $rs = $db->prepare($sql);
    $rs->bindParam(':akkid', $akkid, PDO::PARAM_INT);
    $rs->bindParam(':mitgliedsnummer', $_REQUEST['fmnr'], PDO::PARAM_INT);
    $rs->bindParam(':vorname', $_REQUEST['vorname'], PDO::PARAM_STR);
    $rs->bindParam(':nachname', $_REQUEST['nachname'], PDO::PARAM_STR);
    $rs->bindParam(':strasse', $_REQUEST['strasse'], PDO::PARAM_STR);
    $rs->bindParam(':plz', $_REQUEST['plz'], PDO::PARAM_STR);
    $rs->bindParam(':ort', $_REQUEST['ort'], PDO::PARAM_STR);
    $rs->bindParam(':lv', $_REQUEST['lv'], PDO::PARAM_STR);
    $rs->bindParam(':kv', $_REQUEST['kv'], PDO::PARAM_STR);
    $rs->bindParam(':akkrediteur', $info->akkuser, PDO::PARAM_STR);
    $rs->bindParam(':kommentar', $_REQUEST['kommentar'], PDO::PARAM_STR);
    $rs->execute();

    // bei Änderung noch Kommentar in tblakk ändern    
    if (isset($_REQUEST['fedit'])) {
        $sql = "UPDATE tblakk SET kommentar = concat(kommentar,' | neue Adresse/Gliederung') WHERE akkID = :akkID";
        $rs = $db->prepare($sql);
        $rs->bindParam(':akkID', $akkid, PDO::PARAM_INT);
        $rs->execute();
    }
}

// es wurde ein bestimmtes Mitglied ausgewählt
if ($akkid > 0) {   
    $sql = "SELECT DISTINCTROW a.*, p.akkID AS pid FROM tblakk a LEFT JOIN tblpay p ON a.akkID = p.akkID WHERE a.akkID = :akkid";
    $rs = $db->prepare($sql);
    $rs->bindParam(':akkid', $akkid, PDO::PARAM_INT);
    $rs->execute();
    $rows = $rs->fetchAll();
    $num_rows = count($rows);
}

// Suchanfrage gesendet
if ( isset($_REQUEST['send']) && ($_REQUEST['send']=="Suchen") ) {
    if  (intval($_REQUEST['mnr']) > 0 && intval($_REQUEST['mnr']) < 99999 ) {
        $mnr = intval($_REQUEST['mnr']);
        $sql = "SELECT DISTINCTROW a.*, p.akkID AS pid FROM tblakk a LEFT JOIN tblpay p ON a.akkID = p.akkID WHERE a.mitgliedsnummer = :mnr";
        $rs = $db->prepare($sql);
        $rs->bindParam(':mnr', $mnr, PDO::PARAM_INT);
        $rs->execute();
        $rows = $rs->fetchAll();
        $num_rows = count($rows);
    }
    elseif ( (isset($_REQUEST['mnr']) && strlen(trim($_REQUEST['mnr'])) > 1 ) || (isset($_REQUEST['vorname']) && strlen(trim($_REQUEST['vorname'])) > 1) ) {
        $nachname = $db->quote($_REQUEST['mnr']);
        $vorname = $db->quote($_REQUEST['vorname']);
        $fuzzyname = fuzzystring($nachname);
        $fuzzyvorname = fuzzystring($vorname);
        if (($fuzzyname != "" || $fuzzyvorname != "") && (strlen($fuzzyname) > 1 || strlen($fuzzyvorname) > 1)) {
//            $fuzzyname = "%".$fuzzyname."%";
            $fuzzyname = $fuzzyname."%";
//            $fuzzyvorname = "%".$fuzzyvorname."%";
            $fuzzyvorname = $fuzzyvorname."%";
            $sql = "SELECT DISTINCTROW a.*, p.akkID AS pid FROM tblakk a LEFT JOIN tblpay p ON a.akkID = p.akkID WHERE suchname LIKE :fuzzyname AND suchvname LIKE :fuzzyvorname ORDER BY nachname, vorname";
            $rs = $db->prepare($sql);
            $rs->bindParam(':fuzzyname', $fuzzyname, PDO::PARAM_STR);
            $rs->bindParam(':fuzzyvorname', $fuzzyvorname, PDO::PARAM_STR);
            $rs->execute();
            $rows = $rs->fetchAll();
            $num_rows = count($rows);
        }
    }
}

include("head.php");

if ($action == "akk") {
// Akkreditierungsformular
    include("akkform.php");
}
elseif ($action == "topay") {
// Bezahlformular
    include("payform.php");
}
elseif ($action == "edit") {
// Editierformular
    include("editform.php");
}

include("footer.php");
?>
