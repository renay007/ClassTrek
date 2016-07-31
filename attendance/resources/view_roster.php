<?php
require_once('../../functions.php');
date_default_timezone_set('America/New_York');
$user 				= htmlentities($_GET['user']);
$course 			= htmlentities($_GET['course']);
$course_space = str_replace("-"," ",$course);
$course 			= str_replace("-","",$course);
$instructor 	= $user;
$identifier		= "(SELECT 
										classes.identifier
								 FROM
											trial.classes
								 WHERE
											username = '".$instructor."'
												AND REPLACE(course_code, ' ', '') = '".$course."')";
try      
{     
	$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser , $dbpass);
	$insertQuery = "SELECT 
											*
									FROM
											`trial`.`studentInfo`
									WHERE
											`studentInfo`.`identifier` = ".$identifier."
									ORDER BY `studentInfo`.`lastName`";
	$insertStmt = $dbConnection->prepare($insertQuery);
	$insertStmt->execute();
	while ($result = $insertStmt->fetch(PDO::FETCH_ASSOC)) {
		$emplid		 = $result['EMPLID'];
		$firstName = $result['firstName'];
		$lastName  = $result['lastName'];
		$out[] = array(
									 'EMPLID'	 					=> $emplid,
									 'lastName'				 	=> $lastName,
									 'firstName'				=> $firstName
									 );
									 	
	}
	$dbConnection = null;
}     
catch (PDOException $e)
{     
	die("Error 001: Database Error");
}     
?>
<?php
$file_name = "view_roster.inc";
$fh=fopen($file_name,'w') or die("failed to create file");
$head  = '<header style="text-align: center;"><span class="start">'.$course_space.'</span><br>'."\n";
$head .= '</header>'."\n";
fwrite($fh,$head) or die("could not write to file");
fwrite($fh,"\n");
$header = <<< _END
  <table class="table table-striped" style="margin-bottom: 10px;">
    <thead>             
      <tr class="">             
        <th>EMPLID</th> 
        <th>Last Name</th>    
        <th>First Name</th>   
      </tr>             
    </thead>   
	</table>
  <div id="popup-scroll" class="popup-scroll">
	<table class="table table-striped">
	<tbody>
_END;
fwrite($fh,$header) or die("could not write to file");
fwrite($fh,"\n");
?>
		<?php
		foreach ($out as $info) {
		echo '<tr>'."\n";
		echo '	<td id="move_table_1">'.$info['EMPLID'].'</td>'."\n";
		echo '	<td id="move_table_2">'.$info['lastName'].'</td>'."\n";
		echo '	<td id="move_table_3">'.$info['firstName'].'</td>'."\n";
		echo '</tr>'."\n";
		$content  = '<tr>'."\n";
		$content .= '	<td id="move_table_1">'.$info['EMPLID'].'</td>'."\n";
		$content .= '	<td id="move_table_2">'.$info['lastName'].'</td>'."\n";
		$content .= '	<td id="move_table_3">'.$info['firstName'].'</td>'."\n";
		$content .= '</tr>'."\n";
		fwrite($fh,$content) or die("could not write to file");
		fwrite($fh,"\n");
		}
		?>
<?php
$footer = <<< _END
	</tbody>
</table>
</div>
_END;
fwrite($fh,$footer) or die("could not write to file");
fwrite($fh,"\n");
?>
	</tbody>	
</table>
<?php
//echo json_encode($out);
?>
