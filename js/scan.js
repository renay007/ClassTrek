$(document).ready(function() {
  toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-center",
    "preventDuplicates": false,                                               
    "onclick": null,
    "showDuration": "3000",
    "hideDuration": "1000",
    "timeOut": "3000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  } 
	var osisValue;
	$('#OSIS').keydown(function(e) {
		if(e.keyCode==13) {
			osisValue=$('#OSIS').val();
			$('#OSIS').val("");
			getStudentInfo(osisValue);
		}
	});
});

function getStudentInfo(osis) {
	if(typeof(xhr1) != 'undefined') {
		xhr1.abort();
	}
	$course = localStorage.getItem('course_scan'); 
	$course = $course.replace(/ /g,'');
	var xhr1=	$.ajax ({
		url: "resources/studentInfo.php",
		async: true,              
		data: { OSIS : 	osis,
						course: $course,
						user:		$user },
		dataType: 'json',         
		success: function(data) {                         
			if(data.error=='true') {
				$('#pastSwipes')
				.prepend('<tr>'+
									 '<td></td>'+
									 '<td>'+data.timeStamp+'</td>'+
									 '<td>'+osis+'</td>'+
									 '<td class="text-center danger" colspan="3">'+
												'Unknown Student'+
									 '</td>'+
								 '</tr>');
			} else {
				var studentData = { firstName: {},
														lastName: {},
														date: {},
														barcode: {}
													};
				studentData.firstName = data.firstName;
				studentData.lastName = data.lastName;
				studentData.timeStamp = data.timeStamp;
				studentData.barcode = osis;
				studentData.instructor = $user; //$user is a global variable set in index.php
				studentData.course = (localStorage.getItem('course_scan') == null) ? null : localStorage.getItem('course_scan'); 
				sendAttendance(studentData);
				var absence = data.absent;

				if ($('#pastSwipes tbody tr').length != 0)
						absence = parseInt(absence) - 1;		
				else
						absence = data.absent;

				if (data.absent == 0)
						absence = data.absent;

				$('#pastSwipes')
				.prepend('<tr>'+
									 '<td>'+
										 '<img src="../images/BlankPerson.JPG" class="smallHeadshot"/>'+
									 '</td>'+
									 '<td>'+data.timeStamp+'</td>'+
									 '<td>'+osis+'</td>'+
									 '<td>'+data.firstName+'</td>'+
									 '<td>'+data.lastName+'</td>'+
									 '<td>'+absence+'</td>'+
								 '</tr>');
				if (absence >= 3)
					Command: toastr["error"](data.firstName+' '+data.lastName+': absent '+absence+' times');
			}
		},                        
	});                         
}  
$course = localStorage.getItem('course_scan'); 
$course = $course.replace(/ /g,'');

sendAttendance = function(studentData) {    
	$.ajax({    
		url: 'sendToDB/attendance.php?user='+$user+'&course='+$course,
		data: studentData,
		type: 'POST',
		dataType: 'json',                                                                                                                    
		success: function(response) {    
			content.html(response);
		}    
	});  
}; 
