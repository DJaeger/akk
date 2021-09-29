<?php

$id="upload";
ini_set('include_path', 'inc');
include("db.php");
include("define.php");
include("head.php");

if ($info->akkrolle != 9) die("Du bist nicht berechtigt diese Seite zu öffnen!");

function pUploadForm() {
    global $info;
?>
<br /><br />
<form enctype='multipart/form-data' action='upload.php' method='POST' class='form-horizontal'>
    <input type='hidden' name='MAX_FILE_SIZE' value='100000000' />
    <div class='form-group'>
	<label for="fileakk" class="col-sm-2 control-label">Akk-Datei:</label>
	<div class="col-sm-10">
	    <input id="fileakk" name='akk' type='file' accept='.csv' />
	</div>
    </div>
    <div class='form-group'>
	<label for="filebeitrag" class="col-sm-2 control-label">Beitrag-Datei:</label>
	<div class="col-sm-10">
	    <input id="filebeitrag" name='beitrag' type='file' accept='.csv' />
	</div>
    </div>
    <div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
	    <input type='submit' class="btn btn-default" name='submit_upload' value='Upload' />
	</div>
    </div>
</form>

<h3>Datei-Informationen</h3>

    <div class="panel-group" id="uploadinfo" role="tablist" aria-multiselectable="true">
	<div class="panel panel-info">
	    <div class="panel-heading" id="headingPT">
		<h4 class="panel-title">
		    <a role="button" data-toggle="collapse" data-parent="#uploadinfo" href="#collapsePT" aria-expanded="true" aria-controls="collapsePT">
			Notwendige Spalten der Akk-Datei für einen Parteitag 
		    </a>
		</h4>
	    </div>
	    <div id="collapsePT" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingPT">
		<div class="panel-body">
		    <ul>
			<li>mitgliedsnummer</li>
			<li>refcode</li>
			<li>vorname</li>
			<li>nachname</li>
			<li>strasse</li>
			<li>plz</li>
			<li>ort</li>
			<li>lv</li>
			<li>kv</li>
			<li>geburtsdatum</li>
			<li>eintrittsdatum</li>
			<li>offenerbeitrag</li>
		    </ul>
		    <h5>Optional</h5>
		    <ul>
			<li>schwebend</li>
			<li>suchname</li>
			<li>suchvname</li>
			<li>kommentar</li>
			<li>warnung</li>
		    </ul>
		</div>
	    </div>
	</div>
	<div class="panel panel-info">
	    <div class="panel-heading" id="headingAV">
		<h4 class="panel-title">
		    <a role="button" data-toggle="collapse" data-parent="#uploadinfo" href="#collapseAV" aria-expanded="true" aria-controls="collapseAV">
			Notwendige Spalten der Akk-Datei für eine Aufstellungsversammlung 
		    </a>
		</h4>
	    </div>
	    <div id="collapseAV" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingAV">
		<div class="panel-body">
		    <ul>
			<li>mitgliedsnummer</li>
			<li>refcode</li>
			<li>vorname</li>
			<li>nachname</li>
			<li>strasse</li>
			<li>plz</li>
			<li>ort</li>
			<li>lv</li>
			<li>kv</li>
			<li>geburtsdatum</li>
			<li>eintrittsdatum</li>
			<li>nation</li>
		    </ul>
		    <h5>Optional</h5>
		    <ul>
			<li>schwebend</li>
			<li>offenerbeitrag</li>
			<li>suchname</li>
			<li>suchvname</li>
			<li>kommentar</li>
			<li>warnung</li>
		    </ul>
		</div>
	    </div>
	</div>
	<div class="panel panel-info">
	    <div class="panel-heading" id="headingBeitrag">
		<h4 class="panel-title">
		    <a role="button" data-toggle="collapse" data-parent="#uploadinfo" href="#collapseBeitrag" aria-expanded="true" aria-controls="collapseAV">
			Notwendige Spalten der Beitrag-Datei
		    </a>
		</h4>
	    </div>
	    <div id="collapseBeitrag" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingBeitrag">
		<div class="panel-body">
		    <ul>
			<li>mnr</li>
			<li>opjahr</li>
			<li>beitragsoll</li>
			<li>beitragist</li>
			<li>datumsoll</li>
			<li>datumist</li>
			<li>bemerkung</li>
		    </ul>
		</div>
	    </div>
	</div>
    </div>
<?php
}

echo "<div class='clearfix'></div>";
$Fehler=0;
$FehlerBeitrag=0;
if (isset($_POST['submit_upload'])) {
    if (!isset($_FILES['akk']) || $_FILES['akk']['error'] != UPLOAD_ERR_OK ||
	!is_uploaded_file($_FILES['akk']['tmp_name']))
    {
	$Fehler=1;
	errmsg("Akk-Datei fehlt");
    }
    if (!isset($_FILES['beitrag']) || $_FILES['beitrag']['error'] != UPLOAD_ERR_OK ||
	!is_uploaded_file($_FILES['beitrag']['tmp_name']))
    {
	$FehlerBeitrag=1;
	warnmsg("Beitrag-Datei fehlt");
    }
    if(pathinfo(basename($_FILES["akk"]["name"]),PATHINFO_EXTENSION) != "csv") {
	$Fehler=1;
	errmsg("Akk-Datei ist keine CSV-Datei");
    }
    if ($FehlerBeitrag == 0) {
	if(pathinfo(basename($_FILES["beitrag"]["name"]),PATHINFO_EXTENSION) != "csv") {
	    $FehlerBeitrag=1;
	    errmsg("Beitrag-Datei ist keine CSV-Datei");
	}
    }
    if ($Fehler==0) {
	move_uploaded_file ($_FILES['akk']['tmp_name'],$info->rootdir . '/upload/uplakk.csv');
	echo "<h3>Import Akk-Datei</h3>\n";
	include("impakk.php");
	if ($FehlerBeitrag == 0) {
	    echo "<h3>Import Beitrag-Datei</h3>\n";
	    move_uploaded_file ($_FILES['beitrag']['tmp_name'],$info->rootdir . '/upload/uplbeitrag.csv');
	    include("impbeitrag.php");
	}
	echo "<br /><br /><a href=\"/\">Zur Akkreditierung</a>\n<br />";
	echo "<a href=\"/user.php\">Zur Benutzerverwaltung</a>\n<br />";
    } else {
	pUploadForm();
    }
} else {
    pUploadForm();
}

include("footer.php");
