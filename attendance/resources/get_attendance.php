<?php
require_once('../../functions.php');
$course				=	htmlentities($_GET['course']);
$course_header= str_replace("-"," ",$course);
$date 				= date('Y-m-d');
$date 				= $date.'%';
$instructor 	= htmlentities($_GET['user']); 
$course 			= str_replace("-","",$course); 
$identifier		= "(SELECT 
										classes.identifier
								 FROM
											trial.classes
								 WHERE
											username = '".$instructor."'
												AND REPLACE(course_code, ' ', '') = '".$course."')";
try {     
	$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser , $dbpass);
	$insertQuery = "SELECT 
											y.`date`
									FROM
											(SELECT 
													*
											FROM
													trial.studentInfo
											WHERE
													identifier = ".$identifier.") AS x
													LEFT JOIN
											(SELECT 
													*
											FROM
													trial.attendance
											WHERE
													attendance.identifier = ".$identifier.") AS y ON x.barcode = y.barcode
									GROUP BY y.`date`";
	$insertStmt = $dbConnection->prepare($insertQuery);
	$insertStmt->execute();

	$num_date = 0;
	$dates = array();
	while ($result = $insertStmt->fetch(PDO::FETCH_ASSOC)) {
		$date = $result['date'];
		if (!is_null($date)) {
			array_push($dates, $date);
			$num_date++;;
		}
	}
	$dbConnection = null;
} catch (PDOException $e) {     
	die("Error 001: Database Error");
}     

$day_info = array();
for ($i=0; $i < $num_date; ++$i) {
	$date = $dates[$i];
	try {     
		$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser , $dbpass);
		$insertQuery = "SELECT 
												x.EMPLID,
												x.barcode,
												x.lastName,
												x.firstName,
												y.timeStamp,
												COUNT(y.timeStamp) AS count,
												IF(ISNULL(timeStamp), 'Yes', 'No') AS absent
										FROM
												(SELECT 
														*
												FROM
														trial.studentInfo
												WHERE
														identifier = ".$identifier." 
																AND barcode !='') AS x
														LEFT JOIN
												(SELECT 
														*
												FROM
														trial.attendance
												WHERE
														attendance.identifier = ".$identifier."
																AND attendance.`date` = '$date'
												GROUP BY attendance.barcode) AS y ON x.barcode = y.barcode
										GROUP BY x.barcode";
		$insertStmt = $dbConnection->prepare($insertQuery);
		$insertStmt->execute();

		while ($result = $insertStmt->fetch(PDO::FETCH_ASSOC)) {
			$absence_array[$result['EMPLID']] = array();
			$day_info[$result['EMPLID']][] = array("date"   			=> $date,
																						 "absent"				=> ($result['absent'] == 'Yes') ? 'x' : '',
																					);	

			$day_info[$result['EMPLID']]['lastName'] = $result['lastName'];
			$day_info[$result['EMPLID']]['firstName'] = $result['firstName'];
			$day_info[$result['EMPLID']]['barcode'] = $result['barcode'];
			if ($result['absent'] == 'Yes') {
				$day_info[$result['EMPLID']]['num_absence'][] = 1;
			} else {
				$day_info[$result['EMPLID']]['num_absence'][] = 0;
			}
		}
		$dbConnection = null;
	} catch (PDOException $e) {     
		die("Error 001: Database Error");
	}     
}

if (is_array($day_info))
	echo json_encode($day_info);

die;
?>
