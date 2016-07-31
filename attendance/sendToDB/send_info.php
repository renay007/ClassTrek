<?php
require_once('../../functions.php');
if(isset($_POST['firstName']) && 
	 isset($_POST['lastName'])  && 
	 isset($_POST['timeStamp']) && 
	 isset($_POST['barcode'])) { 
	$firstName = htmlentities($_POST['firstName']);
	$lastName  = htmlentities($_POST['lastName']);
	$timeStamp = htmlentities($_POST['timeStamp']);
	$barcode   = htmlentities($_POST['barcode']);

	try      
	{     
		$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser , $dbpass);
		$insertQuery = "REPLACE INTO `trial`.`studentInfo` (`barcode`, `registrationDate`, `firstName`, `lastName`) VALUES (?,?,?,?)";
		$insertStmt = $dbConnection->prepare($insertQuery);
		$values = array($barcode, $timeStamp, $firstName, $lastName);
		$insertStmt->execute($values);

		$dbConnection = null;
	}     
	catch (PDOException $e)
	{     
		die("Error 001: Database Error");
	}     
}
?>

