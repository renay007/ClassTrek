<?php
session_start();
ob_start();
require_once('../../functions.php');
if(!isset($_POST['username']) || !isset($_POST['password'])) {
		header("Location: /login/index?error");
} else {
	$user			=	htmlspecialchars($_POST['username']);
	$password	=	htmlspecialchars($_POST['password']);

	$salt1 = "aju^@";
	$salt2 = "b*k#$";
	
	$encryptedPassword = sha1($salt1.$password.$salt2);

	try {                  
		$dbConnection = new PDO("mysql:host=$dbhost; dbname=$dbname", $dbuser, $dbpass);
		$statement = "SELECT * 
									FROM trial.users 
									WHERE users.username = ? AND users.password = ? ";
		$checkStatement = $dbConnection->prepare($statement);
		$values = array($user, $encryptedPassword);
		$checkStatement->execute($values);
		$queryResult = $checkStatement->fetch(PDO::FETCH_ASSOC);
	} catch (PDOException $e) {
		die("Error: Cannot access database");
	}                      
	switch(true) {       
		case !$queryResult:
			header("Location: /login/index?error");
			break;           
		case $queryResult['active'] == 0:
			header("Location: /login/index?ack");
			break;           
		case $queryResult['active'] == 1:
			setSession($user);
			header("Location: /login/index?#");
			break;           
		default:           
			header("Location: /login/index?error");
			break;                                                                                                                           
	}            
}
ob_end_flush();
?>
