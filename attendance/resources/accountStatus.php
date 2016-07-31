<?php
require_once('validateUser.inc');
if(!isValidUser())
	echo json_encode(array("accountStatus"=>"Expired"));
else 
	echo json_encode(array("accountStatus"=>"Good"));
exit;
?>
