<?php
require_once('../../functions.php');
session_start();

$errors         = array();      // array to hold validation errors
$data           = array();      // array to pass back data

// validate the variables ======================================================
    // if any of these variables don't exist, add an error to our $errors array

if (empty($_POST['name']))
		$errors['name'] = 'Name is required.';

if (empty($_POST['last_name']))
		$errors['last_name'] = 'Last name is required.';

if (empty($_POST['emplid']))
		$errors['emplid'] = 'EMPLID is required.';

if (empty($_POST['selectOption']))
		$errors['selectOption'] = 'Please choose a course.';

// return a response ===========================================================

// if there are any errors in our errors array, return a success boolean of false
if (!empty($errors)) {
		// if there are items in our errors array, return those errors
		$data['success'] = false;
		$data['errors']  = $errors;
} else {
	$name 		 		= ucfirst(htmlentities($_POST['name']));
	$last_name 		= ucfirst(htmlentities($_POST['last_name']));
	$barcode 	 		= isset($_POST['barcode']) ? htmlentities($_POST['barcode']) : 'x x x';
	$emplid 	 		= isset($_POST['emplid']) ? htmlentities($_POST['emplid']) : null; 
	$course 	 		= htmlentities($_POST['selectOption']);
	$course  	 		= preg_replace('/\s+/', '', $course);
	$user 		 		= $_SESSION['username'];
	$instructor 	= $user;
	$identifier		= "(SELECT 
											classes.identifier
									 FROM
												trial.classes
									 WHERE
												username = '".$instructor."'
													AND REPLACE(course_code, ' ', '') = '".$course."')";
	try {     
		$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser , $dbpass);
		$insertQuery = "SELECT 
												COUNT(*) as num
										FROM
												`trial`.`studentInfo`
										WHERE
												`studentInfo`.`identifier` = $identifier
														AND (`studentInfo`.`barcode` = '$barcode'
														OR `studentInfo`.`EMPLID` = '$emplid')";
		$insertStmt = $dbConnection->prepare($insertQuery);
		$insertStmt->execute();
		$result = $insertStmt->fetch(PDO::FETCH_ASSOC);
	} catch (PDOException $e) {     
		die("Error 001: Database Error");
	}     

	if ($result['num'] >=1) {
		$data['success'] = false;
		$data['title'] = 'Oops!';
		$data['message'] = 'Student exists already.';
	} else {
		try {
			$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser , $dbpass);
			$insertQuery = "REPLACE INTO 
													`trial`.`studentInfo` (`EMPLID`, `barcode`, `registrationDate`, `firstName`, `lastName`, `identifier`)
											SELECT 
													?, ?, NOW(), ?, ?, `identifier`
											FROM 
													`trial`.`classes`
											WHERE 
													username = ? 
														AND REPLACE(course_code, ' ', '') = ?";
			$insertStmt = $dbConnection->prepare($insertQuery);
			$values = array($emplid, $barcode, $name, $last_name, $user, $course);
			$insertStmt->execute($values);
			$dbConnection = null;
		} catch (PDOException $e) {     
			die("Error 001: Database Error");
		}     

		// show a message of success and provide a true success variable
		$data['success'] = true;
		$data['message'] = ucfirst($_POST['name']).' has been added to the class roster.';
	}
}
// return all our data to an AJAX call
echo json_encode($data);
