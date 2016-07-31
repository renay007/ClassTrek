<?php
// simple check to verify if user is still logged in.
// definitely need something more robust :)
function isValidUser() {
	if (!isset($_COOKIE['xv']))
		$returnValue=false;	
	else 
		$returnValue=true;
	return $returnValue;
}
?>
