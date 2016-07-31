<div class="container">
	<div class="container text-center">
		<script src="/login/js/scan.js?ver=<?php echo $version ?>" type="text/javascript"> </script>
		<form style="margin: 40 0 40 0;" class="form-inline" action="#">
		Barcode # <input type="text" id="OSIS" autofocus/>
		&nbsp;
		</form>
		<table id="pastSwipes" class="table table-striped table-condensed table-hover">
			<thead>
				<tr>
					<th>Student</th>
					<th>Time</th>
					<th>Barcode</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Absence</th>
				</tr>
			</thead>
			<tbody>
			<?php
			require_once('../../functions.php');
			require_once('includes/RBIRread.inc');
			date_default_timezone_set('America/New_York');
			$instructor = htmlentities($_GET['user']);
			$course 		= htmlentities($_GET['course']);
			$allInfo 		= getAllInfo($course, $instructor)['data'];
			$date = date('Y-m-d');
			$date = $date."%";
			try {     
				$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser , $dbpass);
				$insertQuery = "SELECT 
														`attendance`.*
												FROM
														trial.`attendance`
																LEFT JOIN
														trial.`classes` ON attendance.`identifier` = classes.`identifier`
												WHERE
														`timeStamp` LIKE ?
																AND classes.`username` = ?
																AND REPLACE(classes.`course_code`, ' ', '') = ?
												ORDER BY `index` DESC";
				$insertStmt = $dbConnection->prepare($insertQuery);
				$value = array($date, $instructor, $course);
				$insertStmt->execute($value);
				while ($result = $insertStmt->fetch(PDO::FETCH_ASSOC)) {
					$timeStamp = $result['timeStamp'];
					$firstName = $result['firstName'];
					$lastName  = $result['lastName'];
					$barcode	 = $result['barcode'];
					$index 		 = $result['index'];
					if (isset($allInfo[$barcode]['absent']))
						$absence = $allInfo[$barcode]['absent'];
					else
						$absence = 0;  
			?>
				<tr>
					<td><img src="../images/BlankPerson.JPG" class="smallHeadshot"/></td><td><?php echo $timeStamp; ?></td>
					<td><?php echo $barcode;?></td>
					<td><?php echo $firstName;?></td>
					<td><?php echo $lastName;?></td>
					<td><?php echo $absence?></td>
				</tr>
			<?php	
				}
				$dbConnection = null;
			} catch (PDOException $e) {     
					die("Error 001: Database Error");
			}     
			?>
			</tbody>
		</table>
	</div>
</div>
<script>
	$('#OSIS').focus();
</script>
