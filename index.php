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

// PT Mitglied wird akkreditiert
if (isset($_REQUEST['akkpt'])) {
    $k = each($_REQUEST['akkpt']);
    $akkid = $k['key'];
    $sql = "UPDATE tblakk SET akkPT = 1, akkrediteurPT = :akkrediteur, geaendert = now() WHERE akkID = :akkID";
    $rs = $db->prepare($sql);
    $rs->bindParam(':akkID', $akkid, PDO::PARAM_INT);
    $rs->bindParam(':akkrediteur', $info->akkuser, PDO::PARAM_STR);
    $rs->execute();
}

// AV Mitglied wird akkreditiert
if (isset($_REQUEST['akkav'])) {
    $k = each($_REQUEST['akkav']);
    $akkid = $k['key'];
    $sql = "UPDATE tblakk SET akkAV = 1, akkrediteurAV = :akkrediteur, geaendert = now() WHERE akkID = :akkID";
    $rs = $db->prepare($sql);
    $rs->bindParam(':akkID', $akkid, PDO::PARAM_INT);
    $rs->bindParam(':akkrediteur', $info->akkuser, PDO::PARAM_STR);
    $rs->execute();
}

// PT Mitglied wird deakkreditiert
if (isset($_REQUEST['deakk'])) {
    $k = each($_REQUEST['deakk']);
    $akkid = $k['key'];
    $sql = "UPDATE tblakk SET akkPT = 0, akkrediteurPT = :akkrediteur, geaendert = now() WHERE akkID = :akkID";
    $rs = $db->prepare($sql);
    $rs->bindParam(':akkID', $akkid, PDO::PARAM_INT);
    $rs->bindParam(':akkrediteur', $info->akkuser, PDO::PARAM_STR);
    $rs->execute();
}

// AV Mitglied wird deakkreditiert
if (isset($_REQUEST['deakk'])) {
    $k = each($_REQUEST['deakk']);
    $akkid = $k['key'];
    $sql = "UPDATE tblakk SET akkAV = 0, akkrediteurAV = :akkrediteur, geaendert = now() WHERE akkID = :akkID";
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
    $sql = "UPDATE tblakk SET offenerbeitragold = offenerbeitrag, offenerbeitrag = 0, schwebend=0, akkrediteurPT = :akkrediteur, akkrediteurAV = :akkrediteur, geaendert = now(), kommentar = concat(kommentar,' | ', :kommentar) WHERE akkID = :akkID";
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
    $sql = "UPDATE tblakk SET akkPT = 0, offenerbeitrag = offenerbeitragold, schwebend = IF(mitgliedsnummer IS NULL,1,0), kommentar = concat(kommentar,' | doch nicht gezahlt'),  akkrediteurPT = :akkrediteur, akkrediteurAV = :akkrediteur, geaendert = now() WHERE akkID = :akkID";
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
    $kommentar = 'Unpay: ' . $sum . ' EUR';

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
    if (badinput($_REQUEST['kommentar']) || badinput($_REQUEST['vorname']) || badinput($_REQUEST['nachname']) || badinput($_REQUEST['strasse']) || badinput($_REQUEST['plz']) || badinput($_REQUEST['ort']) || badinput($_REQUEST['nat']) || badinput($_REQUEST['lv']) || badinput($_REQUEST['kv']) ) {
        die("nice try");
        exit();
    }
    $kommentar = $_REQUEST['kommentar'];
    $gebdat = date("Y-m-d",strtotime($_REQUEST['gebdat']));
	if ($_REQUEST['warnung'] == '1') {
		$kommentar = $kommentar . " - Warnung!";
	} elseif ($_REQUEST['warnung'] == 'S') {
		$kommentar = $kommentar . " - Gesperrt!";
	}
    $refcode = ($_REQUEST['refcode'])?$_REQUEST['refcode']:"NEU";
    if (isset($_REQUEST['fnew'])) {
// checken, ob alle Daten eingegeben wurden, sonst zurück auf Los
        if (trim($_REQUEST['vorname']) == "" || trim($_REQUEST['nachname']) == "" || trim($_REQUEST['plz']) == "" || trim($_REQUEST['ort']) == "" || trim ($_REQUEST['strasse']) == "" || trim($_REQUEST['lv']) == "") {
            header("Location: http://".$_SERVER['HTTP_HOST']."/index.php");
        }
// neues Mitglied in tblakk eintragen
        $sql = "INSERT INTO tblakk (refcode, vorname, nachname, strasse, plz, ort, nation, lv, kv, offenerbeitrag, suchname, suchvname, akkPT, akkAV, kommentar, offenerbeitragold, warnung, geburtsdatum) ";
        $sql .= "values(:refcode, :vorname, :nachname, :strasse, :plz, :ort, :nation, :lv, :kv, :offenerbeitrag, :suchname, :suchvname, 0, 0, :kommentar, :offenerbeitragold, :warnung, :gebdat)";
        $rs = $db->prepare($sql);
        $rs->bindParam(':refcode', $_REQUEST['refcode'], PDO::PARAM_STR);
        $rs->bindParam(':vorname', $_REQUEST['vorname'], PDO::PARAM_STR);
        $rs->bindParam(':nachname', $_REQUEST['nachname'], PDO::PARAM_STR);
        $rs->bindParam(':strasse', $_REQUEST['strasse'], PDO::PARAM_STR);
        $rs->bindParam(':plz', $_REQUEST['plz'], PDO::PARAM_STR);
        $rs->bindParam(':ort', $_REQUEST['ort'], PDO::PARAM_STR);
        $rs->bindParam(':nation', $_REQUEST['nat'], PDO::PARAM_STR);
        $rs->bindParam(':lv', $_REQUEST['lv'], PDO::PARAM_STR);
        $rs->bindParam(':kv', $_REQUEST['kv'], PDO::PARAM_STR);
        $rs->bindParam(':offenerbeitrag', $_REQUEST['offenerbeitrag'], PDO::PARAM_INT);
        $rs->bindParam(':suchname', fuzzystring($db->quote($_REQUEST['nachname'])), PDO::PARAM_STR);
        $rs->bindParam(':suchvname', fuzzystring($db->quote($_REQUEST['vorname'])), PDO::PARAM_STR);
        $rs->bindParam(':kommentar', $kommentar, PDO::PARAM_STR);
        $rs->bindParam(':gebdat', $gebdat, PDO::PARAM_STR);
        $rs->bindParam(':offenerbeitragold', $_REQUEST['offenerbeitrag'], PDO::PARAM_INT);
        if ($_REQUEST['warnung'] == "1")
          $warnung = "1";
        else if ($_REQUEST['warnung'] == "S")
          $warnung = "S";
        else
          $warnung = "";
        $rs->bindParam(':warnung', $warnung, PDO::PARAM_STR);
        $rs->execute();
// akkid ermitteln
        $akkid = $db->lastInsertId();
// sql für INSERT in tbladress
        $sql = "INSERT INTO tbladress (akkID, mitgliedsnummer, vorname, nachname, strasse, plz, ort, nation, lv, kv, akkrediteur, geaendert, kommentar, geburtsdatum, new)  values(:akkid, :mitgliedsnummer, :vorname, :nachname, :strasse, :plz, :ort, :nation, :lv, :kv, :akkrediteur, now(), :kommentar, :gebdat, 1)";
    } else {
        $akkid = $_REQUEST['fakkid'];
// sql für INSERT in tbladress
        $sql = "INSERT INTO tbladress (akkID, mitgliedsnummer, vorname, nachname, strasse, plz, ort, lv, kv, akkrediteur, geaendert, kommentar, edit)  values(:akkid, :mitgliedsnummer, :vorname, :nachname, :strasse, :plz, :ort, :lv, :kv, :akkrediteur, now(), :kommentar, 1)";
    }
// neuen Datensatz in tbladress eintragen
    $rs = $db->prepare($sql);
    $rs->bindParam(':akkid', $akkid, PDO::PARAM_INT);
    $mnr = ($_REQUEST['fmnr'])?$_REQUEST['fmnr']:'0';
    $rs->bindParam(':mitgliedsnummer', $mnr, PDO::PARAM_INT);
    $rs->bindParam(':vorname', $_REQUEST['vorname'], PDO::PARAM_STR);
    $rs->bindParam(':nachname', $_REQUEST['nachname'], PDO::PARAM_STR);
    $rs->bindParam(':strasse', $_REQUEST['strasse'], PDO::PARAM_STR);
    $rs->bindParam(':plz', $_REQUEST['plz'], PDO::PARAM_STR);
    $rs->bindParam(':ort', $_REQUEST['ort'], PDO::PARAM_STR);
    $rs->bindParam(':nation', $_REQUEST['nat'], PDO::PARAM_STR);
    $rs->bindParam(':lv', $_REQUEST['lv'], PDO::PARAM_STR);
    $rs->bindParam(':kv', $_REQUEST['kv'], PDO::PARAM_STR);
    $rs->bindParam(':akkrediteur', $info->akkuser, PDO::PARAM_STR);
    $rs->bindParam(':kommentar', $kommentar, PDO::PARAM_STR);
    $rs->bindParam(':gebdat', $gebdat, PDO::PARAM_STR);
    $rs->execute();

    // Ändere noch tblakk
    if (isset($_REQUEST['fedit'])) {
        $sql = "UPDATE tblakk SET 
					mitgliedsnummer = :mitgliedsnummer,
					refcode = :refcode,
					vorname = :vorname,
					nachname = :nachname,
					strasse = :strasse,
					plz = :plz,
					ort = :ort,
					nation = :nation,
					lv = :lv,
					kv = :kv,
					suchname = :suchname,
					suchvname = :suchvname,
					kommentar = :kommentar,
					warnung = :warnung
				WHERE akkID = :akkID";
        $rs = $db->prepare($sql);
        $rs->bindParam(':akkID', $akkid, PDO::PARAM_INT);
	    $rs->bindParam(':mitgliedsnummer', $mnr, PDO::PARAM_INT);
        $rs->bindParam(':refcode', $_REQUEST['refcode'], PDO::PARAM_STR);
		$rs->bindParam(':vorname', $_REQUEST['vorname'], PDO::PARAM_STR);
		$rs->bindParam(':nachname', $_REQUEST['nachname'], PDO::PARAM_STR);
		$rs->bindParam(':strasse', $_REQUEST['strasse'], PDO::PARAM_STR);
		$rs->bindParam(':plz', $_REQUEST['plz'], PDO::PARAM_STR);
		$rs->bindParam(':ort', $_REQUEST['ort'], PDO::PARAM_STR);
        $rs->bindParam(':nation', $_REQUEST['nat'], PDO::PARAM_STR);
		$rs->bindParam(':lv', $_REQUEST['lv'], PDO::PARAM_STR);
		$rs->bindParam(':kv', $_REQUEST['kv'], PDO::PARAM_STR);
        $rs->bindParam(':suchname', fuzzystring($db->quote($_REQUEST['nachname'])), PDO::PARAM_STR);
        $rs->bindParam(':suchvname', fuzzystring($db->quote($_REQUEST['vorname'])), PDO::PARAM_STR);
		$rs->bindParam(':kommentar', $_REQUEST['kommentar'], PDO::PARAM_STR);
        if ($_REQUEST['warnung'] == "1")
          $warnung = "1";
        else if ($_REQUEST['warnung'] == "S")
          $warnung = "S";
        else
          $warnung = "";
        $rs->bindParam(':warnung', $warnung, PDO::PARAM_STR);
        $rs->bindParam(':gebdat', $gebdat, PDO::PARAM_STR);
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
if ( isset($_REQUEST['send']) && !empty($_REQUEST['searchInput']) ) {
    if ( intval($_REQUEST['searchInput']) > 0 && intval($_REQUEST['searchInput']) < 99999 ) {
        $searchInput = intval($_REQUEST['searchInput']);
        $sql = "SELECT DISTINCTROW a.*, p.akkID AS pid FROM tblakk a LEFT JOIN tblpay p ON a.akkID = p.akkID WHERE a.mitgliedsnummer = :mnr";
        $rs = $db->prepare($sql);
        $rs->bindParam(':mnr', $searchInput, PDO::PARAM_INT);
        $rs->execute();
        $rows = $rs->fetchAll();
        $num_rows = count($rows);
    } elseif ( isset($_REQUEST['searchInput']) && strlen(trim($_REQUEST['searchInput'])) > 1 ) {
        $searchInput = $db->quote($_REQUEST['searchInput']);
        $fuzzySearch = fuzzystring($searchInput);
        if ( $fuzzySearch != "" && strlen($fuzzySearch) > 1 ) {
            $fuzzySearch = $fuzzySearch."%";
            $sql = "SELECT DISTINCTROW a.*, p.akkID AS pid FROM tblakk a LEFT JOIN tblpay p ON a.akkID = p.akkID WHERE suchname LIKE :fuzzySearch OR suchvname LIKE :fuzzySearch ORDER BY nachname, vorname";
            $rs = $db->prepare($sql);
            $rs->bindParam(':fuzzySearch', $fuzzySearch, PDO::PARAM_STR);
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
