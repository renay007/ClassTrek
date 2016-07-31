<?php
require_once('../../functions.php');

if(!isset($_POST['user']) || !isset($_POST['pass']) || !isset($_POST['email'])) {
		header("Location: login/index.php?err");
} else {
	$user     = htmlspecialchars($_POST['user']);
	$password = htmlspecialchars($_POST['pass']);
	$email    = htmlspecialchars($_POST['email']);

	$salt1 = "aju^@";
	$salt2 = "b*k#$";
	
	$encryptedPassword = sha1($salt1.$password.$salt2);

	try {
		$dbConnection = new PDO("mysql:host=$dbhost; dbname=$dbname", $dbuser, $dbpass);
		$statement = "INSERT INTO `trial`.`users` (`username`, `password`, `email`) VALUES (?, ?, ?)";
		$checkStatement = $dbConnection->prepare($statement);
		$values = array($user, $encryptedPassword, $email);
		$queryResult = $checkStatement->execute($values);
	
		if ($queryResult) {
			header('location: /login/index.php?wa');
		} else {
			header("Location: /login/index.php?err");
		}
	} catch (PDOException $e) {
		die("Error: Cannot access database");
	}
}
?>
