jQuery(document).ready(function($) {
	console.log = function() {}

	$(document).on('click', '#generate-school-account-links', function(){
		if ( school_account_terms.length <= 0 ) {
			return false;
		}
		console.log('Begin Account Generation');
		$(this).attr('value', 'Generating...');
		var tmp_url = school_account_terms.ajax_url;
		var s_validate = school_account_terms.validate;
		//alert('generate button clicked');
		generate_account_link(tmp_url, s_validate);
	});

	function generate_account_link(s_tmp_url, s_validate) {
		$.ajax({
			type: "POST",
            dataType: "json",
            url: s_tmp_url,
            data: {action: "wushka_generate_school_links", validate : JSON.stringify(s_validate) },
            success: function (return_data) {
                console.log('Generated School Accounts Finished');
                console.log('Procedure Status: '+return_data.status);

                console.log('Total School Terms: '+return_data.terms);
                console.log('Number of Users ALREADY LINKED: '+return_data.linked);
                console.log('Number of Initial users: '+return_data.users);
                console.log('No. Accounts Created This Run : '+return_data.success);
                console.log('No. Failed Accounts This  Run : '+return_data.failed);
                console.log('No. Loop Iterations : '+return_data.loop);
                $('#generate-school-account-links').attr('value', 'Generation Complete!');
            },
            error: function(a, b, c) {
                $('#generate-school-account-links').attr('value', 'Error Occured!');
            	console.log(a);
             	console.log(b);
             	console.log(c);
            }
		});
	}
});