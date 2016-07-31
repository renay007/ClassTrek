<!DOCTYPE html>
<?php
	if (isset($_COOKIE['xv'])) 
		header('location: attendance/?#');

	require_once('version_number.inc');
?>
<html >
  <head>
    <meta charset="UTF-8">
    <title>City College of New York</title>
    <link rel="stylesheet" href="css/reset.css">
		<link rel='stylesheet' href='css/font-awesome.min.css'>
    <link rel="stylesheet" href="css/login_style.css">
  </head>
	<style>
	.container .info span {
		font-size: 36px;
	}
	</style>
  <body>
		<div class="container">
			<div class="info">
				<span>CLASS</span><span style="color:#028fcc;">TREK</span>
			</div>
		</div>
		<div class="form">
			<div class="thumbnail"><img src="images/hat.svg"/></div>
			<form class="register-form" method="post" action="/login/forms/login/register.php">
				<input type="text" name="user" placeholder="name"/>
				<input type="password" name="pass" placeholder="password"/>
				<input type="email" name="email" placeholder="email address"/>
				<button type="submit">create</button>
				<p class="message">Already registered? <a href="#">Sign In</a></p>
			</form>
			<form class="login-form" method="post" action="/login/forms/login/signin">
				<?php
				if (isset($_GET['error'])) {
				?>
					<p class="bg-danger">Invalid Account</p>
				<?php
				}
				if (isset($_GET['ack'])) {
				?>
					<p class="bg-danger">Your account is not activated yet.<br><br> Please try again later.</p>
				<?php
				}
				if (isset($_GET['wa'])) {
				?>
					<p class="bg-success">Your account was successfully created.<br><br> We will activate it soon.</p>
				<?php
				}
				if (isset($_GET['err'])) {
				?>
					<p class="bg-danger">Oops! Something went wrong.<br><br>Please make sure all fields are filled.</p>
				<?php
				}
				if (isset($_GET['expired'])) {
				?>
					<p class="bg-danger">Your session has expired.</p>
				<?php
				}
				?>
				<input type="text" name="username" placeholder="username" required autofocus/>
				<input type="password" name="password" placeholder="password" required/>
				<button type="submit">Login</button>
				<p class="message">Not registered? <a href="#">Create an account</a></p>
			</form>
		</div>
		<script src='js/jquery-2.1.4.min.js'></script>
		<script src="js/signin.js"></script>
  </body>
</html>
