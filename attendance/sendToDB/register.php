<?php
require_once('../../functions.php');
if(isset($_POST['EMPLID']) && isset($_POST['barcode'])) { 
	$EMPLID 	 = htmlentities($_POST['EMPLID']);
	$barcode   = htmlentities($_POST['barcode']);
	$firstName = htmlentities($_POST['firstName']);
	$lastName  = htmlentities($_POST['lastName']);
	try {     
		$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser , $dbpass);
		$insertQuery = "UPDATE 
											`trial`.`studentInfo`
										SET 
											`barcode`= ? , registrationDate = NOW()
										WHERE
											`EMPLID`=?";
		$insertStmt = $dbConnection->prepare($insertQuery);
		$values = array($barcode, $EMPLID);
		$insertStmt->execute($values);
		$dbConnection = null;
	} catch (PDOException $e) {     
		die("Error 001: Database Error");
	}     
	try {     
		$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser , $dbpass);
		$insertQuery = "UPDATE 
											`trial`.`attendance`
										SET 
											`barcode`= ? 
										WHERE
											`firstName`= ? AND `lastName` = ?";
		$insertStmt = $dbConnection->prepare($insertQuery);
		$values = array($barcode, $firstName, $lastName);
		$insertStmt->execute($values);
		$dbConnection = null;
	} catch (PDOException $e) {     
		die("Error 001: Database Error");
	}     
}
?>
