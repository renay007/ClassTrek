<?php
require_once('../../functions.php');
try  {     
	$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser , $dbpass);
	$insertQuery = "SELECT 
											*
									FROM
											trial.classes";

	$insertStmt = $dbConnection->prepare($insertQuery);
	$insertStmt->execute();
	while ($results =$insertStmt->fetch(PDO::FETCH_ASSOC)) {
		$result['data'][$results['username']][$results['course_code']] = array('identifier' 	=> $results['identifier'],
																																						'username'  	=> $results['username'],
																																						'course_code' => $results['course_code'],
																																						'course_name' => $results['course_name'],
																																						'parent'  	  => $results['parent'],
																																						'child'  	 		=> $results['child'],
																																						'info'		 		=> $results['info']
																																					 ); 
	}
	$result['error'] = false;
} catch (PDOException $e) {     
		$result = array("error"				=> "true",
										"errorReason" => "databaseError"
									 );
}     
if (!isset($result['error'])) {
	$result = array("error"			  => "true",
									"errorReason" => "noRecordsFound"
								 );
}
echo json_encode( $result);
die;

date_default_timezone_set('America/New_York');
$date = date('Y-m-d h:i A');
$found=false;
$studentInfo=getStudentInfoEMPL($OSIS, $course, $user);
if ($studentInfo['error']==false) {
	$found=true;
} else {
	returnError();
	exit();
}
if ($found) {
	die(json_encode(array (
										"error"    =>"false",
										"firstName"=>$studentInfo['firstName'],
										"lastName" =>$studentInfo['lastName'],
										"barcode"  =>$studentInfo['barcode'],
										"absent"  =>$studentInfo['absent'],
										"timeStamp"=>$date
								 )));
} else {
	returnError();
}

function returnError() {
	global $date;
	die(json_encode(array (
										"error"=>"true",
										"firstName"=>"unavailable",
										"lastName"=>"unavailable",
										"timeStamp"=>$date,
										"message"=>null
									)));
}
?>
