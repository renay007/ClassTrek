<?php
if(isset($_COOKIE['xv'])) {
	unset($_COOKIE['xv']);
	setcookie('xv',$user, time()-60*60*24, '/');
}
header("Location: /index");
?>
