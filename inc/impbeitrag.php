<?php
$data = csv_to_array($info->rootdir . '/upload/uplbeitrag.csv',";",7);
if($data == false) {
	echo "Falsches Format";
} else {
	try {
		$db = new mydb();
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->query('SET SESSION sql_mode="ALLOW_INVALID_DATES"');
		$db->beginTransaction(); // also helps speed up your inserts.
		$db->exec('SET FOREIGN_KEY_CHECKS=0;');
		$db->exec('DELETE FROM tblbeitrag;');
		$db->exec('SET FOREIGN_KEY_CHECKS=1;');
		$question_marks = array();
		$insert_values = array();
		foreach($data as $row){
			$question_marks[] = '(' . placeholders('?', sizeof($row)) . ')';
			$insert_values[] = array_values($row);
		}
		# php < 5.6
		$insert_values_combined = call_user_func_array('array_merge', $insert_values);
		# php >= 5.6
		#$insert_values_combined = array_merge(...$insert_values);
		#$insert_values_combined = str_replace('\N',null,$insert_values_combined);
		$sql = 'INSERT INTO tblbeitrag (' . implode(',',array_keys($data[0])) . ') VALUES ' . implode(',',$question_marks) . ';';
		$stmt = $db->prepare($sql);
		$stmt->execute($insert_values_combined);
		$db->exec('DELETE FROM tblbeitrag WHERE mnr IN (SELECT mitgliedsnummer FROM tblakk WHERE offenerbeitrag=0);');
		$db->commit();
		successmsg("Beitrag-Daten wurden importiert");
	} catch (PDOException $e){
		$db->rollBack();
		errmsg("Fehler beim importieren der Beitrags-Daten!<br />\nError: <br />" . $e->getMessage());
	}
}
