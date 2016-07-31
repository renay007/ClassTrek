<?php
require_once('../../functions.php');
date_default_timezone_set('America/New_York');
if(isset($_POST['firstName']) && 
	 isset($_POST['lastName']) && 
	 isset($_POST['timeStamp']) && 
	 isset($_POST['barcode'])) { 
	
	$firstName = htmlentities($_POST['firstName']);
	$lastName  = htmlentities($_POST['lastName']);
	$timeStamp = htmlentities($_POST['timeStamp']);
	$barcode   = htmlentities($_POST['barcode']);
	
	$instructor = htmlentities($_GET['user']);
  $course     = htmlentities($_GET['course']);
	$date 			= $date = date('Y-m-d');

	try {
		$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser , $dbpass);
		$insertQuery = "REPLACE INTO 
												`trial`.`attendance` (`date`, `timeStamp`, `firstName`, `lastName`, `barcode`, `identifier`)
										SELECT 
												DATE('".$date."'), '".$timeStamp."', '".$firstName."', '".$lastName."', '".$barcode."', `classes`.`identifier` 
										FROM 
												`trial`.`classes`
										WHERE
                    		username = '".$instructor."'
                        	AND REPLACE(course_code, ' ', '') = '".$course."'";
		$insertStmt = $dbConnection->prepare($insertQuery);
		$insertStmt->execute();
		$dbConnection = null;
	} catch (PDOException $e) {     
		die("Error 001: Database Error");
	}     
}
?>

