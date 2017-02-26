<?php
$id="mneu";
ini_set('include_path', 'inc');
include("db.php");
include("define.php");
$db = new mydb();
$akkid = 0;
$action = "new";

include("head.php");
include("editform.php");

include("footer.php");
?>