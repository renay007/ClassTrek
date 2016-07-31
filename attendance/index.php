<!DOCTYPE html>
<?php
session_start();
$user = $_SESSION['username'];

if (!isset($_COOKIE['xv'])) 
	header('location: ../index?expired');

require_once('../version_number.inc');
require_once('resources/validateUser.inc');
require_once('../functions.php');
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>CCNY - Attendance</title>
  <link href="../libraries/bootstrap/css/bootstrap.css?ver=<?php echo $version; ?>" rel="stylesheet">
  <link href="../css/animate.min.css" rel="stylesheet"> 
  <link href="../css/font-awesome.min.css?ver=<?php echo $version; ?>" rel="stylesheet">
  <link href="../css/lightbox.css?ver=<?php echo $version; ?>" rel="stylesheet">
  <link href="../css/main.css?ver=<?php echo $version; ?>" rel="stylesheet">
  <link href="../libraries/bootstrap-select/dist/css/bootstrap-select.css?ver=<?php echo $version; ?>" rel="stylesheet">
  <link id="css-preset" href="../css/presets/preset1.css?ver=<?php echo $version; ?>" rel="stylesheet">
  <link href="../css/responsive.css?ver=<?php echo $version; ?>" rel="stylesheet">

	<!--Swipe-->
	<link rel="stylesheet" type="text/css" href="../css/justified-nav.css?ver=<?php echo $version; ?>">
	<link rel="stylesheet" type="text/css" href="../css/index.css?ver=<?php echo $version; ?>">
  <link rel="stylesheet" type="text/css" href="../css/quote.css?ver=<?php echo $version; ?>">
  <link rel="stylesheet" type="text/css" href="../css/style.css?ver=<?php echo $version; ?>">
  <link rel="stylesheet" type="text/css" href="../libraries/toastr/toastr.css?ver=<?php echo $version; ?>">
	<link rel="stylesheet" type="text/css" href="../libraries/sweetalert/dist/sweetalert.css?ver=<?php echo $version; ?>">
  <link rel="stylesheet" type="text/css" href="../css/quick_look.css?ver=<?php echo $version; ?>">
  <link rel="stylesheet" type="text/css" href="../css/magnific-popup.css?ver=<?php echo $version; ?>">

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
	</style>
  <!--[if lt IE 9]>
    <script src="../js/html5shiv.js"></script>
    <script src="../js/respond.min.js"></script>
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
          <a class="navbar-brand" href="/?#">
						<p style="color: white; line-height: 33px;" id="date_time"></p>
            <script type="text/javascript">window.onload = date_time('date_time');</script>
          </a>                    
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-right">                 
            <li class="scroll active"><a href="/?#">Scan ID</a></li> 
            <li class="scroll"><a href="studentLookup">Student Lookup</a></li>
            <li class="scroll"><a href="contact">Contact</a></li>       
            <li class="scroll"><a href="about">About Us</a></li>       
            <li id="signout"><a><i style="font-size: 19px; cursor: pointer;" class="fa fa-power-off"></i></a></li>
          </ul>
        </div>
      </div>
    </div><!--/#main-nav-->
  </header><!--/#home-->
	
  <section id="services">
    <div class="container">
      <div class="text-center our-services">
        <div class="row">
					<a id="download_excel" href="#">
          <div style="cursor: pointer;" class="col-sm-4 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
            <div class="service-icon">
              <i class="fa fa-file-excel-o"></i>
            </div>
            <div class="service-info">
              <h3>Download Attendance Sheet</h3>
              <p style="color: #666;">Download the attendance sheet in excel format.</p>
            </div>
          </div>
					</a>
          <div id="inline-popups" style="cursor: pointer;" class="links col-sm-4 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="450ms">
						<a href="#test-popup" data-effect="mfp-zoom-in">
            <div class="service-icon">
              <i class="fa fa-users"></i>
            </div>
            <div class="service-info">
              <h3>View Roster</h3>
              <p style="color: #666;">Take a quick look at the class roster.</p>
            </div>
						</a>
          </div>
          <div id="add_student" style="cursor: pointer;" class="col-sm-4 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="550ms">
            <div class="service-icon">
              <i style="color: white;"class="fa fa-plus"></i>
            </div>
            <div class="service-info">
              <h3>Add Student</h3>
              	<p>Add a student that is not from the class roster.</p>
            </div>
          </div>
        </div>
      </div>
      <div id="attendance_heading" class="heading wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="300ms">
        <div class="row">
          <div class="text-center col-sm-8 col-sm-offset-2">
						<form id="attendance_label" class="form-inline" style="margin-top:10px; margin-bottom:0px;">
							<div class="form-group">                       
								<select id="courses" name="selectCourses" class="selectpicker" data-live-search="false" title="Choose a course ...">
									<?php 
									$courses = get_courses($user);
									$i = 0;
									foreach ($courses as $course) {
										++$i;
									?>
										<option value="<?php echo str_replace(" ","",$course); ?>"><?php echo $course; ?></option>
									<?php
										$default_course = $course;
									}
									?>
								</select>                                    
							</div>                                         
						</form> 
          </div>
        </div> 
      </div>
    </div>
		<div id="content" class="container text-center wow fadeInUp animated">                                                                     
		</div>
  </section> <!--/#services-->

	<!-- Modal Add Student Form -->
	<div id="myModal" class=" modal fade"  tabindex="-1">
		<div class="modal-dialog form-div">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:#750909 !important">&times;</button>
					<h4><span class="glyphicon glyphicon-plus" style="margin-right:10px;"></span> Add student</h4>
				</div>
				
				<div class="modal-body">
					<form role="form" id="new_student" action="resources/add_student.php" method="post">
							<p class="name">
								<input name="barcode" type="text" class="validate[required,custom[onlyLetter],length[0,100]] feedback-input" placeholder="Barcode #" id="barcode" autofocus="focus"/>
							</p>
							<p class="name">
								<input name="emplid" type="text" class="validate[required,custom[onlyLetter],length[0,100]] feedback-input" placeholder="EMPLID #*" id="emplid" required>
							</p>
							<p class="name">
								<input style="width:50%; float:left;" name="name" type="text" class="validate[required,custom[onlyLetter],length[0,100]] feedback-input" placeholder="First Name*" id="name" required/>
								<input style="width:50%; border-left-color:#A9A9AE;" name="last_name" type="text" class="validate[required,custom[onlyLetter],length[0,100]] feedback-input" placeholder="Last Name*" id="last_name" required/>
							</p>
							<div id="question" class="mc-select-wrap feedback-input">
								<div class="mc-select">
										<select name="selectOption" class="select" required>
												<option id="dummySelect" value="" disabled selected hidden>Choose a course* ...</option>
													<?php 
													$courses = get_courses($user);
													foreach ($courses as $course) {
													?>
														<option><?php echo $course; ?></option>
													<?php
													}
													?>
												<i class="fa fa-angle-down"></i>
										</select>
								</div>
							</div>
							<div class="modal-footer">
								<button id="sendForm" name="sendForm" type="submit" class="mc-btn btn-style-4">
									<i class="fa fa-paper-plane" style="margin-right:15px;"></i>ADD
								</button>
							</div>
					</form>
				</div>

			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<!-- End Modal Contact Form -->

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

	<!-- Quick Look Roster Form -->
	<div id="test-popup" class="white-popup mfp-with-anim mfp-hide">
	</div>
	<!-- End Quick Look Roster Form -->

  <footer id="footer" class="hidden">
    <div class="footer-top wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="300ms">
      <div class="container text-center">
        <div class="footer-logo">
        </div>
        <div class="social-icons">
          <ul class="hidden">
            <li><a class="envelope" ><i class="fa fa-envelope"></i></a></li>
            <li><a class="twitter" ><i class="fa fa-twitter"></i></a></li> 
            <li><a class="dribbble"><i class="fa fa-dribbble"></i></a></li>
            <li><a class="facebook"><i class="fa fa-facebook"></i></a></li>
            <li><a class="linkedin"><i class="fa fa-linkedin"></i></a></li>
            <li><a class="tumblr"><i class="fa fa-tumblr-square"></i></a></li>
          </ul>
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

  <script type="text/javascript" src="../js/jquery.js"></script>
	<script src="../js/jquery-1.11.3.min.js?ver=<?php echo $version; ?>" type="text/javascript"></script>
  <script type="text/javascript" src="../libraries/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="../libraries/Magnific-Popup/dist/jquery.magnific-popup.min.js"></script>
  <script type="text/javascript" src="../js/jquery.inview.min.js"></script>
  <script type="text/javascript" src="../js/wow.min.js"></script>
  <script type="text/javascript" src="../js/mousescroll.js"></script>
  <script type="text/javascript" src="../js/smoothscroll.js"></script>
  <script type="text/javascript" src="../js/jquery.countTo.js"></script>
  <script type="text/javascript" src="../js/lightbox.min.js"></script>
  <script type="text/javascript" src="../js/main.js"></script>
  <script type="text/javascript" src="../libraries/sweetalert/dist/sweetalert.min.js"></script>
  <script type="text/javascript" src="../libraries/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="../libraries/toastr/toastr.js?ver=<?php echo $version; ?>" type="text/javascript"></script>
	<script src="../js/index.js?ver=<?php echo $version; ?>" type="text/javascript"></script>
	<script src="../js/accountStatus.js?ver=<?php echo $version; ?>" type="text/javascript"></script>
	<script src="../js/add_student.js?ver=<?php echo $version; ?>" type="text/javascript"></script>
	<script src="../js/quick_look.js?ver=<?php echo $version ?>" type="text/javascript"> </script>
	<script>
		$(document).ready(function(){
				$('select[name="selectOption"]').on('change', function() {
					$(this).css('cssText','color: #3c3c3c !important');
				});
				$course = localStorage.getItem('course_scan');
				$('select[name="selectCourses"]').find('option:contains("'+$course+'")').attr("selected",true).change();
				$("#add_student").click(function(){
					$("#myModal").modal({keyboard: true});
					$("#company").focus();
					// Clear form
					$('input[name=name]').val('');
					$('input[name=last_name]').val('');
					$('input[name=barcode]').val('');
					$('input[name=emplid]').val('');

					if (localStorage.getItem('course_scan') == null) {
						// Don't try to prefill
					} else {
						$course = localStorage.getItem('course_scan');
						$('select[name="selectOption"]').val($course);
					}
				});
				$("#signout").click(function(){
						$("#logout").modal({keyboard: true});
						$('#quit').on('click',function() {
							localStorage.clear();
							window.location.href="../logout";
						});
				});
		});
	</script>
	<script>
		var $user;
		$user = "<?php echo $user; ?>"
	</script>
	<script>
		var $default_course;
		$default_course = "<?php echo $default_course; ?>"
	</script>
	
	<?php
	if ($i == 1) {
	?>
		<script>
		$(document).ready(function(){
			$('select[name="selectCourses"]').find('option:contains("'+$default_course+'")').attr("selected",true).change();
		});
		</script>
	<?php
	}
	?>
</body>
</html>
