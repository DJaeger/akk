<?php
  $id="upload";
  ini_set('include_path', 'inc');
  include("db.php");
  include("define.php");
  include("head.php");

  if ($info->akkrolle != 9) die("Du bist nicht berechtigt diese Seite zu öffnen!");

  function pUploadForm()
  {
	global $info;
	if($info->typ == "PT") {
		echo 'Du benötigst den Bericht 319 (Akk) und 320 (Beitrag).';
	} elseif($info->typ == "AV") {
		echo 'Du benötigst den Bericht 323 (Akk) und 324 (Beitrag).';
	} else {
		exit("Fehlerhafte Konfiguration");
	}

    echo "<br /><br />\n";
    echo "<form enctype='multipart/form-data' action='upload.php' method='POST'>\n";
    echo "<input type='hidden' name='MAX_FILE_SIZE' value='100000000' />\n";
    echo "<table>\n";
    echo "<tr><td>Akk-Datei:</td><td> <input name='akk' type='file' accept='.csv' /></td></tr>\n";
    echo "<tr><td>Beitrag-Datei:</td><td> <input name='beitrag' type='file' accept='.csv' /></td></tr>\n";
    echo "<tr><td><input type='submit' name='submit_upload' value='Upload' /></td></tr>\n";
    echo "</table></form>\n";
  }

  $Fehler=0;
  if (isset($_POST['submit_upload']))
  {
    if (!isset($_FILES['akk']) || $_FILES['akk']['error'] != UPLOAD_ERR_OK ||
        !is_uploaded_file($_FILES['akk']['tmp_name']))
    {
      $Fehler=1;
      errmsg("Akk-Datei fehlt");
    }
    if (!isset($_FILES['beitrag']) || $_FILES['beitrag']['error'] != UPLOAD_ERR_OK ||
        !is_uploaded_file($_FILES['beitrag']['tmp_name']))
    {
      $Fehler=1;
      errmsg("Beitrag-Datei fehlt");
    }
	if(pathinfo(basename($_FILES["akk"]["name"]),PATHINFO_EXTENSION) != "csv") {
		$Fehler=1;
		errmsg("Akk-Datei ist keine CSV-Datei");
	}
	if(pathinfo(basename($_FILES["beitrag"]["name"]),PATHINFO_EXTENSION) != "csv") {
		$Fehler=1;
		errmsg("Beitrag-Datei ist keine CSV-Datei");
	}
    if ($Fehler==0)
    {
      move_uploaded_file ($_FILES['akk']['tmp_name'],$info->rootdir . '/upload/uplakk.csv');
      echo "<h3>Import Akk-Datei</h3>\n";
	  include("impakk.php");
      echo "<h3>Import Beitrag-Datei</h3>\n";
      move_uploaded_file ($_FILES['beitrag']['tmp_name'],$info->rootdir . '/upload/uplbeitrag.csv');
	  include("impbeitrag.php");
    }
    else
    {
      pUploadForm();
    }
  }
  else
  {
    pUploadForm();
  }
?>

<?php
  include("footer.php");
?>
