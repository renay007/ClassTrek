<?php
require_once('validateUser.inc');
if (!isValidUser()) {          
	returnError();
	exit;                                                                                                        
} else{} 
require_once('includes/RBIRread.inc');
date_default_timezone_set('America/New_York');
$date = date('Y-m-d h:i A');
if (!isset($_GET['OSIS'])) {
	returnError();
	exit();
} else {
	$OSIS		= $_GET['OSIS'];
	$course = $_GET['course'];
	$user		= $_GET['user'];
}
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
