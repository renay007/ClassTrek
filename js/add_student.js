$(document).ready(function() {
  toastr.options = {
    "closeButton": false,
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

    // process the form
    $('form#new_student').submit(function(event) {

        // get the form data
        // there are many ways to get this data using jQuery (you can use the class or id also)
        var formData = {
            'name'         : $('input[name=name]').val(),
            'last_name'     : $('input[name=last_name]').val(),
            'barcode'      : $('input[name=barcode]').val(),
            'emplid' 	     : $('input[name=emplid]').val(),
            'selectOption' : $('select[name="selectOption"]').val()
        };

        // process the form
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'resources/add_student.php', // the url where we want to POST
            data        : formData, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
            encode      : true
        })
            // using the done promise callback
            .done(function(data) {

                // log data to the console so we can see
                console.log(data); 

                // here we will handle errors and validation messages
							 // here we will handle errors and validation messages
								if (!data.success) {
										
										// handle errors for name ---------------
										//if (data.errors.name) {
										//  Command: toastr["error"](data.errors.name)
										//}

										//if (data.errors.last_name) {
										//  Command: toastr["error"](data.errors.last_name)
										//}

										//if (data.errors.barcode) {
										//  Command: toastr["error"](data.errors.barcode)
										//}

										//if (data.errors.selectOption) {
										//  Command: toastr["error"](data.errors.selectOption)
										//}

										if (data.message) {
											swal(data.title, data.message, "error");
										}

								} else {

										// ALL GOOD! just show the success message!
										//Command: toastr["success"](data.message)
											swal(data.message, "success");
										// usually after form submission, you'll want to redirect
										// window.location = '/thank-you'; // redirect a user to another page
										$('#myModal').modal('hide');
								}

            });

        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });

});
