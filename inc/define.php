<?php
$info = new allginfo();
$action = "";
$h2 = "";

$website=array();
$website['start']      = array("hmenu"=>"", "menu"=>"", "page"=>"", "text"=>"Akkreditierung", "titel"=>"Akkreditierung");
$website['user']       = array("hmenu"=>"", "menu"=>"", "page"=>"user", "text"=>"Userverwaltung", "titel"=>"Userverwaltung");
$website['passwd']     = array("hmenu"=>"", "menu"=>"", "page"=>"passwd", "text"=>"Passwort ändern", "titel"=>"Passwort ändern");
$website['mneu']       = array("hmenu"=>"", "menu"=>"", "page"=>"mneu", "text"=>"Neuanlage Mitglied", "titel"=>"Neuanlage");
$website['printakk']   = array("hmenu"=>"", "menu"=>"", "page"=>"printakk", "text"=>"Druckliste Akkreditierte", "titel"=>"Druckansicht Akkreditierte");
$website['upload']     = array("hmenu"=>"", "menu"=>"", "page"=>"upload", "text"=>"Upload Mitgliedsdaten", "titel"=>"Upload");
$website['einnahmen']  = array("hmenu"=>"", "menu"=>"", "page"=>"einnahmen", "text"=>"Eingenommene Beiträge", "titel"=>"Eingenommene Beiträge");
$website['aenderungen']  = array("hmenu"=>"", "menu"=>"", "page"=>"aenderungen", "text"=>"Geänderte Mitglieder", "titel"=>"Geänderte Mitglieder");
$website['statistik']  = array("hmenu"=>"", "menu"=>"", "page"=>"statistik", "text"=>"Statistik", "titel"=>"Akkreditierungsstatistik");
$website['anonstat']  = array("hmenu"=>"", "menu"=>"", "page"=>"public/statistik", "text"=>"Öff. Statistik", "titel"=>"Akkreditierungsstatistik");
$website['logout']     = array("hmenu"=>"", "menu"=>"", "page"=>"logout", "text"=>"Logout " . $info->akkuser, "titel"=>"Logout");
$website['about']      = array("hmenu"=>"", "menu"=>"", "page"=>"about", "text"=>"Lizenz Akkreditierungstool und Dank", "titel"=>"Lizenz und Dank");

function href($seite, $currentid, $tag="", $linkid="", $c="", $ort="", $linktext="") {
// Gibt links zurück, ggf. mit HTML-tags
    global $website;
    if ($c != "") $c = " class = '".$c."'";
    if ($tag != "") {
        $a = "<".$tag.$c.">";
        $e = "</".$tag.">\n";
    }
    else {
        $a = "";
        $e = "";
    }
    $hmenu = $website[$seite]['hmenu'];
    $menu  = $website[$seite]['menu'];
    $page  = $website[$seite]['page'];
    $titel = $website[$seite]['titel'];
    if ($linktext == "") {$text  = $website[$seite]['text'];} else {$text = $linktext;}
    if ($titel == "") $titel = $text;
    if ($page == "")  $page  = $menu;

    ($seite == "start" || $hmenu=="start" || $hmenu=="") ? $link="/" : $link="/".$hmenu."/";
    if ($page != "") $link.=$page.".php";

    echo "$a<a";
    if ($linkid != "")        echo " id='$linkid'";
    if ($seite != $currentid || $seite == "start") echo " href='$link'";
    if ($seite == $currentid) echo " class='current'";
    echo " title='$titel'>$text</a>$e";
}

function ordutf8($string, &$offset) {
    $code = ord(substr($string, $offset,1));
    if ($code >= 128) {        //otherwise 0xxxxxxx
        if ($code < 224) $bytesnumber = 2;             //110xxxxx
        else if ($code < 240) $bytesnumber = 3;        //1110xxxx
        else if ($code < 248) $bytesnumber = 4;        //11110xxx
        $codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) - ($bytesnumber > 3 ? 16 : 0);
        for ($i = 2; $i <= $bytesnumber; $i++) {
            $offset ++;
            $code2 = ord(substr($string, $offset, 1)) - 128;        //10xxxxxx
            $codetemp = $codetemp*64 + $code2;
        }
        $code = $codetemp;
    }
    $offset += 1;
    if ($offset >= strlen($string)) $offset = -1;
    return $code;
}

function utoa($n) {
// ist dieselbe Funktion, wie auf dem SQL-Server, um dort die Suchstrings zu erstellen.
// von SQL direkt übersetzt in PHP
    if ($n >= 97 && $n <= 122) {$result = $n;}
    elseif ($n == 154) {$result = 115;}
    elseif ($n == 158) {$result = 122;}
    elseif ($n == 161) {$result = 105;}
    elseif ($n == 223) {$result = 115;}
    elseif ($n == 224) {$result = 97;}
    elseif ($n == 225) {$result = 97;}
    elseif ($n == 226) {$result = 97;}
    elseif ($n == 227) {$result = 97;}
    elseif ($n == 228) {$result = 97;}
    elseif ($n == 229) {$result = 97;}
    elseif ($n == 230) {$result = 97;}
    elseif ($n == 231) {$result = 99;}
    elseif ($n == 232) {$result = 101;}
    elseif ($n == 233) {$result = 101;}
    elseif ($n == 234) {$result = 101;}
    elseif ($n == 235) {$result = 101;}
    elseif ($n == 236) {$result = 105;}
    elseif ($n == 237) {$result = 105;}
    elseif ($n == 238) {$result = 105;}
    elseif ($n == 239) {$result = 105;}
    elseif ($n == 240) {$result = 111;}
    elseif ($n == 241) {$result = 110;}
    elseif ($n == 242) {$result = 111;}
    elseif ($n == 243) {$result = 111;}
    elseif ($n == 244) {$result = 111;}
    elseif ($n == 245) {$result = 111;}
    elseif ($n == 246) {$result = 111;}
    elseif ($n == 248) {$result = 111;}
    elseif ($n == 249) {$result = 117;}
    elseif ($n == 250) {$result = 117;}
    elseif ($n == 251) {$result = 117;}
    elseif ($n == 252) {$result = 117;}
    elseif ($n == 253) {$result = 121;}
    elseif ($n == 254) {$result = 121;}
    elseif ($n == 255) {$result = 121;}
    elseif ($n == 263) {$result = 99;}
    else $result = 0;
    return $result;
}

function fuzzystring($nstring)  {
    $fuzzystring = '';
    $s0 = '';
    $nstring = mb_strtolower($nstring, 'UTF-8');
    $nstring = str_replace('ae','a',$nstring);
    $nstring = str_replace('oe','o',$nstring);
    $nstring = str_replace('ue','u',$nstring);
    $nstring = str_replace('ck','k',$nstring);
    $nstring = str_replace('ie','i',$nstring);
    if (substr($nstring,0,1) === '\'') {
        $nstring = substr($nstring,1,1) . str_replace('h','',substr($nstring,1)); // substr ab position 1 , weil db->quote nen ' mitbringt!
    } else {
        $nstring = substr($nstring,0,1) . str_replace('h','',substr($nstring,0)); // substr ab position 1 , weil db->quote nen ' mitbringt!
    }
    $offset = 0;
    while ($offset >= 0) {
        $n =  ordutf8($nstring, $offset);
        $s = (utoa($n) === 0) ? "" : chr(utoa($n)) ;
        if ($s != $s0) {$fuzzystring = $fuzzystring . $s;}
        $s0 = $s;
    }
    return $fuzzystring; 
}

function zformat($zahl) {
   return number_format($zahl,2,',','.');
}

function td($x, $c = "", $a = "") {
   if ($a == "" || $x == "") {
      $a1=""; $a2="";
   }
   else {
      $a1="<a href='".$a."'>";
      $a2="</a>";
   }
   if ($c != "") {$c = " class='".$c."'";}
   echo "<td$c>$a1$x$a2</td>";
}

function tdz($x, $c = "") {
   $s = "<td class='r $c'>".number_format($x,2,',','.')."</td>";
   print $s;
}

function tdz0($x, $c = "") {
   $s = "<td class='r $c'>".number_format($x,0,',','.')."</td>";
   print $s;
}

function tdr($x, $c = "") {
   echo "<td class='r $c'>$x</td>";
}

function th($x, $c = "", $a = "") {
   if ($a == "") {
      $a1=""; $a2="";
   }
   else {
      $a1="<a href='".$a."'>";
      $a2="</a>";
   }
   if ($c != "") {$c = " class='".$c."'";}
   echo "<th$c>$a1$x$a2</th>";
}

function errmsg($msg) {
   echo "<p class='red'>$msg</p>";
}

function badinput($input) {
    $input=strtolower(trim($input));
    $s = 0;
    $input = " ".$input;
    $s = $s + strpos($input,"select") + strpos($input,"drop") + strpos($input,"insert") + strpos($input,"update") + strpos($input,"trunc") + strpos($input,";");
    if ($s == 0) {
       return false;
    }
    else {
       return true;
    }
}

function csv_to_array($filename='', $delimiter=',', $rowcount=false)
{
	if(!file_exists($filename) || !is_readable($filename))
		return FALSE;
	
	$header = NULL;
	$data = array();
	if (($handle = fopen($filename, 'r')) !== FALSE)
	{
		while (($row = fgetcsv($handle, 0, $delimiter, '"')) !== FALSE)
		{
			$num = count($row);
			if(!$header) {
				if ($rowcount && $num != $rowcount) {
					return false;
				}
				foreach($row AS $value) {
					$header[] = trim($value,'﻿"');
				}
			} else {
				if ($rowcount) {
					$row = array_slice($row, 0, $rowcount);
				}
				$data[] = array_combine($header, $row);
			}
		}
		fclose($handle);
	}
	return $data;
}

function placeholders($text, $count=0, $separator=","){
	$result = array();
	if($count > 0){
		for($x=0; $x<$count; $x++){
			$result[] = $text;
		}
	}

	return implode($separator, $result);
}

function parms($string,$data) {
	$indexed=$data==array_values($data);
	foreach($data as $k=>$v) {
		if(is_string($v)) $v="'$v'";
		if($indexed) $string=preg_replace('/\?/',$v,$string,1);
		else $string=str_replace(":$k",$v,$string);
	}
	return $string;
}

?>