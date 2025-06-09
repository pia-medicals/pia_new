jQuery(document).ready(function($) {
	
	jQuery('form.admin_form').validate();

/*$('form.admin_form').each(function () {
    $(this).validate();
});
*/

jQuery('form.admin_form1').validate();


	jQuery( "input[type=file]" ).rules( "add", {
	  required: true,
	});

	jQuery( "form#admin_form1 input[name=logo]" ).rules( "add", {
	  required: false,
	});

	jQuery( "#profile_picture" ).rules( "add", {
	  required: false,
	});

	jQuery( "input#customer_code" ).rules( "add", {
	  lettersonly: true
	});

	jQuery.validator.addMethod("lettersonly", function(value, element) {
	  return this.optional(element) || /^[a-z]+$/i.test(value);
	}, "Albhabets only."); 


	jQuery("form.admin_form input[type=email]").rules("add", {
	  valid_email: true
	});
	
	jQuery.validator.addMethod("valid_email", function(value, element) {
	  // allow any non-whitespace characters as the host part
	  return this.optional( element ) || /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i.test( value );
	}, 'Please enter a valid email address.');



});



