$(document).ready(function()
{
	//Download attendance
	$('#download_excel').on('click', function() {
		$course = localStorage.getItem('course_scan');
		$course_nospace = localStorage.getItem('course_scan');
		if( $course == null) {
			swal('Please choose a course');
		} else {
			$.ajax({
				async: false,
				url: 'resources/class_status.php',
				type: 'GET',
				dataType: 'json',
				success: function(result) {
								if (result['data'][$user][$course].parent == null) {
									$other_identifier = '';
								} else {
									$data = result['data'][$user][$course].parent;
									$other_identifier = '&other_identifier='+$data;
								}
				}
			});
			var d = new Date();
			var year = d.getFullYear();
			var month = d.getMonth()+1;
			var day = d.getDate();
			var date = year + '_' + (month<10 ? '0'+month : month) + '_' + (day<10 ? '0'+day : day);
			$course_dash = $course.replace(/ /g,'-');
			$course_nospace = $course.replace(/ /g,'');
			$file_to_download = $course_nospace+'_'+date+'.xls';
			console.log('file to download is: '+$file_to_download);
			$.ajax({
				async: false,
				url: 'excel/attendance.php?user='+$user+'&course='+$course_dash+$other_identifier,
				type: 'GET',
				success: function(resp) {
					console.log('Good! Proceed ...');
					swal({   
						title: $course,   
						text: "Download attendance sheet",   
						type: "info",   
						showCancelButton: true,   
						closeOnConfirm: false,   
						showLoaderOnConfirm: true, }, 
						function(){   
							setTimeout(function(){     
								window.location.href = 'excel/'+$file_to_download;
								swal('Thanks for downloading!');   
							}, 2000); 
						});
				}
			});
		}
	});

	$('a[href="#test-popup"]').on('click', function() {
		if( $course == null) {
			swal('Please choose a course');
			return false;
		} else {
			$('#test-popup').load('resources/view_roster.inc');
		}
	});

	$('#courses').on('change', function() {
		$('#OSIS').focus();
		$('#OSIS').focusout(function(){$('#OSIS').focus()});
		$this = $(this);
		$course = $this.find(':selected').html();
		localStorage.setItem('course_scan',$course);
		$course_dash = $course.replace(/ /g,'-');
		$course = $course.replace(/ /g,'');
		$('#content').load('resources/scan_index.php?user='+$user+'&course='+$course);
		$.ajax({
						async: false,
						url: 'resources/view_roster.php?user='+$user+'&course='+$course_dash,
						type: 'GET',
						success: function(resp) {
								console.log('inc file created');
						}
		});
	});
  $('#courses option').each(function() {
	 $(this).on('click', function() {
		 $('#OSIS').focus();                                                                                                         
		 $('#OSIS').focusout(function(){$('#OSIS').focus()});
	 });
  });
}); // end document.ready()
