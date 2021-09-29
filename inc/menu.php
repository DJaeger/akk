<?php 
echo "<ul>\n";
href("start", $id, "li", "");
href("statistik", $id, "li", "");
href("anonstat", $id, "li", "");
href("printakk",$id,"li","");
href("postallist",$id,"li","");
if ($info->akkrolle == 9) href("mneu",$id,"li","");
if ($info->akkrolle == 9) href("einnahmen",$id,"li","");
if ($info->akkrolle == 9) href("aenderungen",$id,"li","");
if ($info->akkrolle == 9) href("user",$id,"li","");
if ($info->akkrolle == 9) href("upload",$id,"li","");
href("passwd",$id,"li","");
href("logout",$id,"li","");
echo "</ul>\n";
