<?php
$data = csv_to_array($info->rootdir . '/upload/uplakk.csv',";");
if($data == false) {
	echo "Falsches Format";
} else {
	try {
		$db = new mydb();
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->query('SET SESSION sql_mode="ALLOW_INVALID_DATES"');
		$db->beginTransaction(); // also helps speed up your inserts.
		$db->exec('SET FOREIGN_KEY_CHECKS=0;');
		$db->exec('DELETE FROM tblpay;');
		$db->exec('DELETE FROM tblakk;');
		$db->exec('DELETE FROM tbladress;');
		$db->exec('SET FOREIGN_KEY_CHECKS=1;');
		$question_marks = array();
		$insert_values = array();
		foreach($data as &$row){
			array_shift($row);
			// Not allowed to be imported
			unset( $row['akk'], $row['akkPT'], $row['akkAV'], $row['akkrediteur'], $row['akkrediteurPT'], $row['akkrediteurAV'] );
			unset( $row['id'], $row['stimmberechtigung'], $row['offenerbeitragold'], $row['geaendert'] );
			$row['offenerbeitragold'] = $row['offenerbeitrag'];
			if ( empty( $row['suchname'] ) ) {
				$row['suchname'] = fuzzystring($db->quote($row['nachname']));
			}
			if ( empty( $row['suchvname'] ) ) {
				$row['suchvname'] = fuzzystring($db->quote($row['vorname']));
			}
			$question_marks[] = '(' . placeholders('?', sizeof($row)) . ')';
			$insert_values[] = array_values($row);
		}
		$insert_values_combined = array_merge(...$insert_values);
		$insert_values_combined = str_replace('\N',null,$insert_values_combined);
		$sql = 'INSERT INTO tblakk (' . implode(',',array_keys($data[0])) . ') VALUES ' . implode(',',$question_marks) . ';';
		$stmt = $db->prepare($sql);
		$stmt->execute($insert_values_combined);
		$db->commit();
		successmsg("Akk-Daten wurden importiert");
	} catch (PDOException $e){
		$db->rollBack();
		errmsg("Fehler beim importieren der Akk-Daten!<br />\nError: <br />" . $e->getMessage());
	}
}
