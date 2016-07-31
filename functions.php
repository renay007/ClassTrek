<?php // functions.php
$dbhost 	 = 'localhost'; 
$dbname 	 = 'trial';
$dbuser 	 = 'root'; 
$dbpass 	 = '$lice0fbread@611';

function setSession($user) {
	$_SESSION['username'] = $user;
	$salt1 = "aju^@"; 
	$salt2 = "b*k#$";
	$user = $salt1.$user.$salt2;
	$user = sha1($user);
	setcookie('xv',$user, time()+60*60*24, '/');
}

function get_courses($user) {
	global $dbhost,$dbname,$dbuser,$dbpass;
	$courses = array();
	$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser , $dbpass);
	$insertQuery = "SELECT 
											*
									FROM
											trial.classes
									WHERE
											username = ?
									ORDER BY course_code ASC";
	$insertStmt = $dbConnection->prepare($insertQuery);
	$value = array($user);
	$insertStmt->execute($value); 
	while ($result = $insertStmt->fetch(PDO::FETCH_ASSOC)) {
		$courses[] = $result['course_code'];
	}
	return $courses;
}
?>  
