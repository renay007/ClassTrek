<?php
require_once('../../functions.php');
$course						=	htmlentities($_GET['course']);
$course_header		= str_replace("-"," ",$course);
$date 						= date('Y-m-d');
$date 						= $date.'%';
$instructor 			= htmlentities($_GET['user']); 
$course 					= str_replace("-","",$course); 
$identifier				= "(SELECT 
												classes.identifier
										 FROM
													trial.classes
										 WHERE
													username = '".$instructor."'
														AND REPLACE(course_code, ' ', '') = '".$course."')";
$other_identifier = isset($_GET['other_identifier']) ? htmlentities($_GET['other_identifier']) : $identifier;
try {
	$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser , $dbpass);
	$insertQuery = "SELECT 
											*, $identifier AS class_id, $other_identifier AS other_id
									FROM
											((SELECT 
													y.`date`, y.`timeStamp`, y.`identifier`
											FROM
													(SELECT 
													*
											FROM
													trial.studentInfo
											WHERE
													identifier = ".$identifier.") AS x
											LEFT JOIN (SELECT 
													*
											FROM
													trial.attendance
											WHERE
													attendance.identifier = ".$identifier.") AS y ON x.barcode = y.barcode
											GROUP BY y.`date`) UNION (SELECT 
													y.`date`, y.`timeStamp`, y.`identifier`
											FROM
													(SELECT 
													*
											FROM
													trial.studentInfo
											WHERE
													identifier = ".$other_identifier.") AS x
											LEFT JOIN (SELECT 
													*
											FROM
													trial.attendance
											WHERE
													attendance.identifier = ".$other_identifier.") AS y ON x.barcode = y.barcode
											GROUP BY y.`date`)) AS z
									ORDER BY z.timeStamp ASC";
	$insertStmt = $dbConnection->prepare($insertQuery);
	$insertStmt->execute();

	$num_date = 0;
	$class_id = "";
	$other_id = "";
	$dates = array();
	while ($result = $insertStmt->fetch(PDO::FETCH_ASSOC)) {
		$date = $result['date'];
		if (!is_null($date)) {
			$class_id = $result['class_id'];
			$other_id	= $result['other_id'];
			$dates[$num_date] = array("date"       => $date,
																"timeStamp"  => $result['timeStamp'],
																"identifier" => $result['identifier']
															 );
			$num_date++;;
		}
	}
	$dbConnection = null;
} catch (PDOException $e) {     
	die("Error 001: Database Error");
}     

$day_info = array();
for ($i=0; $i < $num_date; ++$i) {
	$date 		  = $dates[$i]['date'];
	$id   			= $dates[$i]['identifier'];
	$timeStamp  = $dates[$i]['timeStamp'];
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
														attendance.identifier = ".$id."
																AND attendance.`date` = '$date'
												GROUP BY attendance.barcode) AS y ON x.barcode = y.barcode
										GROUP BY x.barcode
										ORDER BY x.lastName";
		$insertStmt = $dbConnection->prepare($insertQuery);
		$insertStmt->execute();

		while ($result = $insertStmt->fetch(PDO::FETCH_ASSOC)) {
			$absence_array[$result['EMPLID']] = array();
			$day_info[$result['EMPLID']][] = array("date"   			=> $date,
																						 "timeStamp"    => $timeStamp,
																						 "identifier"   => $id,
																						 "absent"				=> ($result['absent'] == 'Yes') ? 'x' : '',
																					);	

			$day_info[$result['EMPLID']]['lastName']  = $result['lastName'];
			$day_info[$result['EMPLID']]['firstName'] = $result['firstName'];
			$day_info[$result['EMPLID']]['barcode']   = $result['barcode'];

			if ($result['absent'] == 'Yes')
				$day_info[$result['EMPLID']]['num_absence'][] = 1;
			else
				$day_info[$result['EMPLID']]['num_absence'][] = 0;
		}
		$dbConnection = null;
	} catch (PDOException $e) {     
		die("Error 001: Database Error");
	}     
}

date_default_timezone_set('America/New_York');
$date = date("Y-m-d H:i ");
$date2 = date("Y_m_d");
$file_name = $course."_".$date2.".xls";

$fh=fopen($file_name,'w') or die("failed to create file");
$header = <<< _END
	<?xml version="1.0"?>
	<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
	 xmlns:o="urn:schemas-microsoft-com:office:office"
	 xmlns:x="urn:schemas-microsoft-com:office:excel"
	 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
	 xmlns:html="http://www.w3.org/TR/REC-html40">
	 <OfficeDocumentSettings xmlns="urn:schemas-microsoft-com:office:office">
		<AllowPNG/>
	 </OfficeDocumentSettings>
	 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
		<WindowHeight>17460</WindowHeight>
		<WindowWidth>28800</WindowWidth>
		<WindowTopX>0</WindowTopX>
		<WindowTopY>460</WindowTopY>
		<ProtectStructure>False</ProtectStructure>
		<ProtectWindows>False</ProtectWindows>
	 </ExcelWorkbook>
	 <Styles>
		<Style ss:ID="Default" ss:Name="Normal">
		 <Alignment ss:Vertical="Bottom"/>
		 <Borders/>
		 <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="12" ss:Color="#000000"/>
		 <Interior/>
		 <NumberFormat/>
		 <Protection/>
		</Style>
		<Style ss:ID="s63">
		 <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
		 <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="30" ss:Color="#000000"/>
		 <Interior ss:Color="#C0C0C0" ss:Pattern="Solid"/>
		</Style>
		<Style ss:ID="s65">
		 <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
		 <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="14" ss:Color="#000000"/>
		 <Interior ss:Color="#C0C0C0" ss:Pattern="Solid"/>
		</Style>
		<Style ss:ID="s67">
		 <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
		 <Borders/>
		 <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="16" ss:Color="#000000"
			ss:Bold="1"/>
		</Style>
		<Style ss:ID="s69">
		 <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
		 <Borders/>
		 <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="14" ss:Color="#000000"
			ss:Bold="1"/>
		</Style>
		<Style ss:ID="s70">
		 <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
		 <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="14" ss:Color="#000000"/>
		</Style>
		<Style ss:ID="s71">
		 <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
		 <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="14" ss:Color="#000000"/>
		 <Interior ss:Color="#99CCFF" ss:Pattern="Solid"/>
		</Style>
		<Style ss:ID="s72">
		 <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
		 <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="14" ss:Color="#DD0806"/>
		 <Interior ss:Color="#99CCFF" ss:Pattern="Solid"/>
		</Style>
		<Style ss:ID="s73">
		 <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
		 <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="14" ss:Color="#DD0806"/>
		</Style>
	 </Styles>
 <Worksheet ss:Name="Attendance">
  <Table ss:ExpandedColumnCount="146" ss:ExpandedRowCount="10000" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="168" ss:DefaultRowHeight="25">
   <Column ss:AutoFitWidth="0" ss:Width="129"/>
   <Column ss:AutoFitWidth="0" ss:Width="149"/>
   <Column ss:AutoFitWidth="0" ss:Width="155"/>
   <Column ss:AutoFitWidth="0" ss:Width="89"/>
   <Column ss:AutoFitWidth="0" ss:Width="76" ss:Span="141"/>
_END;
fwrite($fh,$header) or die("could not write to file");
fwrite($fh,"\n");
?>
<?php
$dynamic_header  ='<Row ss:AutoFitHeight="0">'."\n";
$dynamic_header .='<Cell ss:MergeAcross="3" ss:MergeDown="1" ss:StyleID="s63"><Data'."\n";
$dynamic_header .='ss:Type="String">'.$course_header.'</Data></Cell>'."\n";
$dynamic_header .='</Row>'."\n";
$dynamic_header .='<Row ss:AutoFitHeight="0"/>'."\n";
$dynamic_header .='<Row ss:AutoFitHeight="0">'."\n";
$dynamic_header .='<Cell ss:MergeAcross="3" ss:StyleID="s65"><Data ss:Type="String">Generated on '.date('m/d/y').'</Data></Cell>'."\n";
$dynamic_header .='</Row>'."\n";
fwrite($fh,$dynamic_header) or die("could not write to file");
fwrite($fh,"\n");
$data_header=<<< _END
   <Row ss:AutoFitHeight="0">
    <Cell ss:MergeDown="1" ss:StyleID="s67"><Data ss:Type="String">EMPLID</Data></Cell>
    <Cell ss:MergeDown="1" ss:StyleID="s67"><Data ss:Type="String">Last Name</Data></Cell>
    <Cell ss:MergeDown="1" ss:StyleID="s67"><Data ss:Type="String">First Name</Data></Cell>
    <Cell ss:MergeDown="1" ss:StyleID="s67"><Data ss:Type="String">Absence</Data></Cell>
_END;
fwrite($fh,$data_header) or die("could not write to file");
fwrite($fh,"\n");
?>
<?php
$date_info  ='';
if (isset($num_date) && $num_date!=0) {
	for ($i=0; $i < $num_date; ++$i) {
		if ($dates[$i]['identifier'] == $other_id && $dates[$i]['identifier'] != $class_id) {
			$class_date = $dates[$i]['date'];
			$class_date = date("n-j-y", strtotime($class_date));
			$date_info .='<Cell ss:MergeDown="1" ss:StyleID="s69"><Data ss:Type="String">Lecture&#13; '.$class_date.'</Data></Cell>'."\n";
		} elseif ($dates[$i]['identifier'] == $class_id && $dates[$i]['identifier'] != $other_id) {
			$class_date = $dates[$i]['date'];
			$class_date = date("n-j-y", strtotime($class_date));
			$date_info .='<Cell ss:MergeDown="1" ss:StyleID="s69"><Data ss:Type="String">Lab&#13; '.$class_date.'</Data></Cell>'."\n";
		} else {
			$class_date = $dates[$i]['date'];
			$class_date = date("n-j-y", strtotime($class_date));
			$date_info .='<Cell ss:MergeDown="1" ss:StyleID="s69"><Data ss:Type="String">'.$class_date.'&#160</Data></Cell>'."\n";
		}
	}
	fwrite($fh,$date_info) or die("could not write to file");
}
fwrite($fh,'</Row>');
fwrite($fh,"\n");
fwrite($fh,'<Row ss:AutoFitHeight="0"/>');
fwrite($fh,"\n");
if (isset($num_date) && $num_date!=0) {
	$i = 0;
	foreach ($day_info as $key=>$info) {
		fwrite($fh,'<Row ss:AutoFitHeight="0">');
		fwrite($fh,"\n");
		$beg_s='<Cell ss:StyleID="s70"><Data ss:Type="String">';
		$beg_n='<Cell ss:StyleID="s70"><Data ss:Type="Number">';
		if (($i%2) != 0) {
			$beg_s='<Cell ss:StyleID="s71"><Data ss:Type="String">';
			$beg_n='<Cell ss:StyleID="s71"><Data ss:Type="Number">';
		}

		$out[$i]['absent'] = array_sum($info['num_absence']);

		if ($out[$i]['absent'] > 0) {
				$ab_s='<Cell ss:StyleID="s73"><Data ss:Type="Number">';
			if (($i%2) != 0)
				$ab_s='<Cell ss:StyleID="s72"><Data ss:Type="Number">';
		} else {
				$ab_s='<Cell ss:StyleID="s70"><Data ss:Type="Number">';
			if (($i%2) != 0)
				$ab_s='<Cell ss:StyleID="s71"><Data ss:Type="Number">';
		}
		
		$end='</Data></Cell>';
		$output='';
		if (isset($info['firstName'])) {
		$output.=$beg_n.$key.$end."\n";
		$output.=$beg_s.$info['lastName'].$end."\n";
		$output.=$beg_s.$info['firstName'].$end."\n";
		$output.=$ab_s.array_sum($info['num_absence']).$end."\n";
		}
		for ($j=0; $j < $num_date; ++$j) {
			if (isset($info[$j]['absent'])) {
				$output.=$beg_s.$info[$j]['absent'].$end."\n";
			}
		}
		fwrite($fh,$output) or die("could not write to file");
		fwrite($fh,'</Row>');
		fwrite($fh,"\n");
		++$i;
	}
}
$footer=<<< _END
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <Header x:Margin="0.3"/>
    <Footer x:Margin="0.3"/>
    <PageMargins x:Bottom="0.75" x:Left="0.7" x:Right="0.7" x:Top="0.75"/>
   </PageSetup>
   <Unsynced/>
   <PageLayoutZoom>0</PageLayoutZoom>
   <Selected/>
   <FreezePanes/>
   <FrozenNoSplit/>
   <SplitHorizontal>5</SplitHorizontal>
   <TopRowBottomPane>5</TopRowBottomPane>
   <SplitVertical>4</SplitVertical>
   <LeftColumnRightPane>4</LeftColumnRightPane>
   <ActivePane>0</ActivePane>
   <Panes>
    <Pane>
     <Number>3</Number>
    </Pane>
    <Pane>
     <Number>1</Number>
    </Pane>
    <Pane>
     <Number>2</Number>
    </Pane>
    <Pane>
     <Number>0</Number>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
   <x:Print>    </x:Print>
  </WorksheetOptions>
 </Worksheet>
</Workbook>
_END;
fwrite($fh,$footer) or die("could not write to file");
?>
