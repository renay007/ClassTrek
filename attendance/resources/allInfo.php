<?php
require_once('validateUser.inc');
if(!isValidUser()) {     
	returnError();
	exit;     
} else{} 
require_once('includes/RBIRread.inc');
date_default_timezone_set('America/New_York');
$date = date('Y-m-d h:i A');
$found=false;
$course = $_GET['course'];
$user		= $_GET['user'];
$studentInfo=getAllInfoEMPL($course, $user)['data'];
$dataOutput=array();
foreach($studentInfo as $individualStudent) {
	$tempOutput[0]=$individualStudent['barcode'];
	$tempOutput[1]=$individualStudent['ID'];
	$tempOutput[2]=$individualStudent['FIRST NAME'];
	$tempOutput[3]=$individualStudent['LAST NAME'];
	$tempOutput[4]=$individualStudent['absent'];
	$dataOutput[]=$tempOutput;
}
$output['data']=$dataOutput;
echo json_encode($output);
exit;
if($studentInfo['error']=='false') {
	$found=true;
}
if($found) {
	die(json_encode(array
	(
		"error"=>"false",
		"firstName"=>$studentInfo['FIRST NAME'],
		"lastName"=>$studentInfo['LAST NAME'],
		"timeStamp"=>$date
	)
	));
} else {
	returnError();
}

function returnError() {
	global $date;
	die(json_encode(array
	(
		"error"=>"true",
		"firstName"=>"unavailable",
		"lastName"=>"unavailable",
		"timeStamp"=>$date,
		"message"=>null
	)
	));
}
?>
