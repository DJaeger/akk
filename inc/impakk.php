<?php
if($info->typ == "PT") {
	$cols = 23;
} elseif($info->typ == "AV") {
	$cols = 21;
} else {
	exit("Fehlerhafte Konfiguration");
}

$data = csv_to_array($info->rootdir . '/upload/uplakk.csv',";",$cols);
if($data == false) {
	echo "Falsches Format";
} else {
	try {
		$db = new mydb();
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->query('SET SESSION sql_mode="ALLOW_INVALID_DATES"');
		$db->beginTransaction(); // also helps speed up your inserts.
		$db->exec('DELETE FROM tblpay;');
		$db->exec('DELETE FROM tblakk;');
		$db->exec('DELETE FROM tbladress;');
		$question_marks = array();
		$insert_values = array();
		foreach($data as &$row){
			array_shift($row);
			$question_marks[] = '(' . placeholders('?', sizeof($row)) . ')';
			$insert_values[] = array_values($row);
		}
		# php < 5.6
		#$insert_values_combined = call_user_func_array('array_merge', $insert_values);
		# php >= 5.6
		$insert_values_combined = array_merge(...$insert_values);
		$insert_values_combined = str_replace('\N',null,$insert_values_combined);
		$sql = 'INSERT INTO tblakk (' . implode(',',array_keys($data[0])) . ') VALUES ' . implode(',',$question_marks) . ';';
		$stmt = $db->prepare($sql);
		$stmt->execute($insert_values_combined);
		$db->commit();
		echo "Akk-Daten wurden importiert";
	} catch (PDOException $e){
		$db->rollBack();
		echo "Fehler beim importieren der Akk-Daten!<br />\nError: <br />";
		echo $e->getMessage();
	}
}
?>