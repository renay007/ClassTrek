$(document).ready(function() {
	$course = localStorage.getItem('course_scan');
	$default_course_nospace = $default_course.replace(/ /g,'');
	if ($course != null) {
		$course_with_space = $course;
		$course = $course.replace(/ /g,'');
		$('select[name="courses_lookup"]').find('option:contains("'+$course_with_space+'")').attr("selected",true).change();
		$('#course_chosen').html($course);
	} else  if ($default_course != null) {
		$('select[name="courses_lookup"]').find('option:contains("'+$default_course+'")').attr("selected",true).change();
		$('#course_chosen').html($default_course_nospace);
	} else {}

	var table=	$('#studentQuickLookup').DataTable ({
		"ajax": {
			'type': 'GET',
			'url' : 'resources/allInfo.php',
			'data': function(d) {
			    d.course = $('#course_chosen').html();
					d.user 	 = $('#user_chosen').html();
			}
		},	
		"columnDefs": [{
				"targets": -1,
				"data": null,
				"defaultContent": "<button class='scan_id'>Update Barcode</button>"
		 }],
		"drawCallback": function() {
			$('.scan_id').click(function() {
				var EMPLID		=$(this).parent().parent().find(":nth-child(2)").text();
				var firstName	=$(this).parent().parent().find(":nth-child(3)").text();
				var lastName	=$(this).parent().parent().find(":nth-child(4)").text();
				$(this).parent().parent().find('td:first').html('<input id="'+EMPLID+'"></input>');
				$('#'+EMPLID).focus();
				$('#'+EMPLID).keydown(function(e) {
					if (e.keyCode==13) {
						barcodeValue=$('#'+EMPLID).val();
						$('#'+EMPLID).parent().html(barcodeValue);
						var studentData = 
						{
							EMPLID: {},
							firstName: {},
							lastName: {},
							barcode: {}
						};
						studentData.barcode 	= barcodeValue;
						studentData.EMPLID 		= EMPLID;
						studentData.firstName = firstName;
						studentData.lastName 	= lastName;
						sendInfo(studentData);
					}
				});
			});
		}
	});
	$('#studentQuickLookup_wrapper').addClass("separatorBorder");
	$('#studentQuickLookup_wrapper').addClass("topMargin");
	$('#studentQuickLookup_wrapper').addClass("insidePadding");
	$('#courses_lookup').on('change', function() {
			$course = $(this).find(':selected').html();
			$course = $course.replace(/ /g,'');
			$('#course_chosen').html($course);
			table.ajax.reload();
	});
});

sendInfo = function(studentData) {    
	$.ajax({    
		url: 'sendToDB/register.php',
		data: studentData,
		type: 'POST',
		dataType: 'json',                                                                                                                    
		success: function(response) {    
			content.html(response);
		}    
	});  
}; 
