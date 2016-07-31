<?php
require_once('../../functions.php');
function getAllInfo($course, $instructor) 
{
	$url = "http://localhost/login/attendance/resources/get_attendance.php?user=".$instructor."&course=".$course."";
	$file = file_get_contents($url);
	$json = json_decode($file, true);
	$student_info = array();
	foreach ($json as $key=>$info) {
		$student_info[$key]['barcode'] = $info['barcode']; 	
		$student_info[$key]['absent'] = array_sum($info['num_absence']);
	}
	global $dbhost,$dbname,$dbuser,$dbpass;
	try {     
		$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser , $dbpass);
		$insertQuery = "SELECT 
												firstName AS 'FIRST NAME',
												lastName AS 'LAST NAME',
												EMPLID AS 'ID',
												barcode
										FROM
												trial.studentInfo
										WHERE
												identifier = (SELECT                                                                                                                              
															classes.identifier
													FROM     
															trial.classes
													WHERE    
															username = ?
																	AND REPLACE(course_code, ' ', '') = ?)";
		$insertStmt = $dbConnection->prepare($insertQuery);
		$values = array($instructor, $course);
		$insertStmt->execute($values);
		while ($results =$insertStmt->fetch(PDO::FETCH_ASSOC)) {
			if (isset($student_info[$results['ID']]['absent'])) {
				$absence = $student_info[$results['ID']]['absent'];
				$result['error']=false;
			} else {
				$absence = 0;
			}
			$result['data'][$results['barcode']] = array('FIRST NAME' => $results['FIRST NAME'],
																									 'LAST NAME'  => $results['LAST NAME'],
																									 'barcode'  	=> $results['barcode'],
																									 'ID'  	 		  => $results['ID'],
																									 'absent'		  => $absence
																										); 
		}
	} catch (PDOException $e) {     
		$result = array ("error"			 => "true",
										 "errorReason" => "databaseError"
										);
	}     
	if (!isset($result['error'])) {
		$result=array ("error"			 => "true",
									 "data"				 => "null",
									 "errorReason" => "noRecordsFound"
									);
	}
	return $result;
}

function getAllInfoEMPL($course, $instructor) 
{
	$url = "http://localhost/login/attendance/resources/get_attendance.php?user=".$instructor."&course=".$course."";
	$file = file_get_contents($url);
	$json = json_decode($file, true);
	$student_info = array();
	foreach ($json as $key=>$info) {
		$student_info[$key]['barcode'] = $info['barcode']; 	
		$student_info[$key]['absent'] = array_sum($info['num_absence']);
	}
	global $dbhost,$dbname,$dbuser,$dbpass;
	try {     
		$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser , $dbpass);
		$insertQuery = "SELECT 
												firstName AS 'FIRST NAME',
												lastName AS 'LAST NAME',
												EMPLID AS 'ID',
												barcode
										FROM
												trial.studentInfo
										WHERE
												identifier = (SELECT                                                                                                                              
															classes.identifier
													FROM     
															trial.classes
													WHERE    
															username = ?
																	AND REPLACE(course_code, ' ', '') = ?)";
		$insertStmt = $dbConnection->prepare($insertQuery);
		$values = array($instructor, $course);
		$insertStmt->execute($values);
		while ($results =$insertStmt->fetch(PDO::FETCH_ASSOC)) {
			if (isset($student_info[$results['ID']]['absent'])) {
				$absence = $student_info[$results['ID']]['absent'];
			} else {
				$absence = 0;
			}
			$result['data'][] = array('FIRST NAME' => $results['FIRST NAME'],
																'LAST NAME'  => $results['LAST NAME'],
																'barcode'  	 => $results['barcode'],
																'ID'  	 		 => $results['ID'],
																'absent'		 => $absence
															 ); 
	  }
	} catch (PDOException $e) {     
		$result=array("error"			 => "true",
									"errorReason" => "databaseError",
								 );
	}     
	if (!isset($result['data'][0]['FIRST NAME'])) {
		$result = array("error"			  => "true",
										"errorReason" => "noRecordsFound"
									 );
	} else {
		$result['error']=false;
	}
	return $result;
}

function getStudentInfoEMPL($EMPLID, $course, $instructor)
{
	global $dbhost,$dbname,$dbuser,$dbpass;
	$url = "http://localhost/login/attendance/resources/get_attendance.php?user=".$instructor."&course=".$course."";
	$file = file_get_contents($url);
	$json = json_decode($file, true);
	$student_info = array();
	foreach ($json as $key=>$info) {
		$student_info[$key]['barcode'] = $info['barcode']; 	
		$student_info[$key]['absent'] = array_sum($info['num_absence']);
	}
	try {     
		$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser , $dbpass);
		$insertQuery = "SELECT 
												*
										FROM
												trial.studentInfo
										WHERE
												`barcode` = ?
														AND identifier = (SELECT 
																classes.identifier
														FROM
																trial.classes
														WHERE
																username = ?
																		AND REPLACE(course_code, ' ', '') = ?)";

		$insertStmt = $dbConnection->prepare($insertQuery);
		$value = array($EMPLID, $instructor, $course);
		$insertStmt->execute($value);
		$results=$insertStmt->fetch(PDO::FETCH_ASSOC);
		if (!isset($results['firstName'])) {
			$results = array("error"			 => "true",
											 "errorReason" => "noRecordsFound"
			);
		} else {
			if (isset($student_info[$results['EMPLID']]['absent']))
				$results['absent'] = $student_info[$results['EMPLID']]['absent'];
			else 
				$results['absent'] = 0;
			$results['error']=false;
		}
	} catch (PDOException $e) {     
		$results = array("error"			 => "true",
										 "errorReason" => "databaseError",
										);
	}     
	if (!isset($results['firstName'])) {
		$results = array("error"			 => "true",
										 "errorReason" => "noRecordsFound",
										);
	} else {
		if (isset($student_info[$results['EMPLID']]['absent']))
			$results['absent'] = $student_info[$results['EMPLID']]['absent'];
		else 
			$results['absent'] = 0;
		$results['error']=false;
	}
	return $results;
}
?>
