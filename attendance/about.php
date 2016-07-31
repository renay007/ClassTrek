<?php
	if (!isset($_COOKIE['xv']))
		header('location: ../index.php?expired');
	
	session_start();
	$user = $_SESSION['username'];
	require_once('../version_number.inc');
	require_once('resources/validateUser.inc');
	require_once('../functions.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>CCNY - Attendance</title>
  <link href="../libraries/bootstrap/css/bootstrap.css?ver=<?php echo $version ?>" rel="stylesheet">
  <link href="../css/animate.min.css" rel="stylesheet"> 
  <link href="../css/font-awesome.min.css?ver=<?php echo $version ?>" rel="stylesheet">
  <link href="../css/lightbox.css?ver=<?php echo $version ?>" rel="stylesheet">
  <link href="../css/main.css?ver=<?php echo $version ?>" rel="stylesheet">
  <link href="../css/style.css?ver=<?php echo $version ?>" rel="stylesheet">
  <link href="../libraries/bootstrap-select/dist/css/bootstrap-select.css?ver=<?php echo $version ?>" rel="stylesheet">
  <link id="css-preset" href="../css/presets/preset1.css?ver=<?php echo $version ?>" rel="stylesheet">
  <link href="../css/responsive.css?ver=<?php echo $version ?>" rel="stylesheet">

	<!--Swipe-->
	<link rel="stylesheet" type="text/css" href="../css/justified-nav.css?ver=<?php echo $version ?>">
	<link rel="stylesheet" type="text/css" href="../css/index.css?ver=<?php echo $version ?>">
	<link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.css?ver=<?php echo $version ?>">
	<script src="../js/date_time.js?ver=<?php echo $version ?>" type="text/javascript"> </script>

	<style>
	.modal-header, #myModal h4, #myModal .close {
			background-color: #028fcc !important;
			color:white !important;
			text-align: center;
			font-size: 30px;
	}
	.modal-footer {
			background-color: #f9f9f9;
	}
	.img-responsive {
			height: 150px;
			width: 150px;
			margin-left: 30%;
			border-radius: 100%;
	}
	</style>
  <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
  <![endif]-->
</head><!--/head-->
<body>
  <!--.preloader-->
  <div class="preloader"> <i class="fa fa-circle-o-notch fa-spin"></i></div>
  <!--/.preloader-->
  <header id="home">
    <div class="main-nav navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php?#">
						<p style="color: white; line-height: 33px;" id="date_time"></p>
            <script type="text/javascript">window.onload = date_time('date_time');</script>
          </a>                    
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-right">                 
            <li class="scroll"><a href="/?#">Scan ID</a></li> 
            <li class="scroll"><a href="studentLookup">Student Lookup</a></li>
            <li class="scroll"><a href="contact">Contact</a></li>       
            <li class="scroll active"><a href="about">About US</a></li>       
            <li id="signout"><a><i style="font-size: 19px; cursor: pointer;" class="fa fa-power-off"></i></a></li>
          </ul>
        </div>
      </div>
    </div><!--/#main-nav-->
  </header><!--/#home-->

	<section id="" style="height: 100vh;">
		<div class="container">
			<div class="row">
				<div class="heading text-center col-sm-8 col-sm-offset-2 wow fadeInUp animated" data-wow-duration="1200ms" data-wow-delay="300ms" style="visibility: visible; animation-duration: 1200ms; animation-delay: 300ms; animation-name: fadeInUp;">
					<h2 style="margin-top:10px;">The Team</h2>
				</div>
			</div>
			<div class="team-members">
				<div class="row">
					<div class="col-sm-6 col-md-4">
						<div class="team-member wow flipInY animated" data-wow-duration="1000ms" data-wow-delay="300ms" style="visibility: visible; animation-duration: 1000ms; animation-delay: 300ms; animation-name: flipInY;">
							<div class="member-image">
								<img class="img-responsive" src="../images/BlankPerson.JPG" alt="">
							</div>
							<div class="member-info">
								<h3>Prof. M. Umit Uyar</h3>
								<h4>Project Manager</h4>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-4">
						<div class="team-member wow flipInY animated" data-wow-duration="1000ms" data-wow-delay="500ms" style="visibility: visible; animation-duration: 1000ms; animation-delay: 500ms; animation-name: flipInY;">
							<div class="member-image">
								<img class="img-responsive" src="../images/BlankPerson.JPG" alt="">
							</div>
							<div class="member-info">
								<h3>Adarsha Subick</h3>
								<h4>Software Developer</h4>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-4">
						<div class="team-member wow flipInY animated" data-wow-duration="1000ms" data-wow-delay="800ms" style="visibility: visible; animation-duration: 1000ms; animation-delay: 800ms; animation-name: flipInY;">
							<div class="member-image">
								<img class="img-responsive" src="../images/BlankPerson.JPG" alt="">
							</div>
							<div class="member-info">
								<h3>Rene Midouin</h3>
								<h4>Software Developer</h4>
							</div>
						</div>
					</div>
				</div>
			</div>            
		</div>
	</section>

	<!-- Modal Logout Form -->
	<div id="logout" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-sm" style="margin-top: 15%;">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><i style="font-size: 19px; margin-right: 15px; cursor: pointer;" class="fa fa-power-off"></i>Log Out</h4>
				</div>
				<div class="modal-body">
					<p class="text-center">Are you sure you want to log out?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
					<button id="quit" type="button" class="btn btn-primary">Yes</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<!-- End Modal Logout Form -->
	
  <footer id="footer" style="background-color: #175690;">
    <div class="footer-top wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="300ms">
      <div class="container text-center">
        <div class="footer-logo">
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container">
        <div class="row">
          <div class="col-sm-6">
            <p>&copy; <?php echo date('Y'); ?> Bio-Inspired Computing Lab.</p>
          </div>
          <div class="col-sm-6">
          </div>
        </div>
      </div>
    </div>
  </footer>

  <script type="text/javascript" src="../js/jquery.js?ver=<?php echo $version ?>"></script>
	<script src="../js/jquery-1.11.3.min.js?ver=<?php echo $version; ?>" type="text/javascript"></script>
  <script type="text/javascript" src="../libraries/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="../js/jquery.inview.min.js"></script>
  <script type="text/javascript" src="../js/wow.min.js"></script>
  <script type="text/javascript" src="../js/mousescroll.js"></script>
  <script type="text/javascript" src="../js/smoothscroll.js"></script>
  <script type="text/javascript" src="../js/jquery.countTo.js"></script>
  <script type="text/javascript" src="../js/lightbox.min.js"></script>
  <script type="text/javascript" src="../js/main.js?ver=<?php echo $version ?>"></script>
  <script type="text/javascript" src="../libraries/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="../js/index.js?ver=<?php echo $version; ?>" type="text/javascript"></script>
	<script src="../js/accountStatus.js?ver=<?php echo $version; ?>" type="text/javascript"></script>
	<script src="../js/jquery.dataTables.min.js" type="text/javascript"> </script>
	<script>
		$(document).ready(function(){
			$("#signout").click(function(){
					$("#logout").modal({keyboard: true});
					$('#quit').on('click',function() {
						localStorage.clear();
						window.location.href="../logout.php";
					});
			});
		});
	</script>
	<script>
    var $user;                                                                 
    $user = "<?php echo $user; ?>"
  </script>
</body>
</html>
