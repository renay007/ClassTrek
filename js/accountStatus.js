$(document).ready(function(){
	setInterval(getStudentInfo,30000)
});

function getStudentInfo() {                             
	if(typeof(xhr1) != 'undefined') {
		xhr1.abort();
	}
	var xhr1=	$.ajax ({                   
		url: "resources/accountStatus.php",
		async: true,              
		dataType: 'json',         
		success: function(data) {                         
		  if(data.accountStatus=='Expired') {
			  console.log("session Expired")
				window.location.href="../index.php?expired"
			}
		}                       
	});                         
}  
