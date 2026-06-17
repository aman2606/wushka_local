jQuery(document).ready(function($) {
	// console.log = function() {}

	$('#ereader-modal [data-dismiss="modal"]').on('click', function(){
		$('#ereader-modal #iframe-wrapper').empty();
	});


	$(document).on('click', 'a.item-detail.wushka-sample', function(){
		if ( a_ereader_iframe.length <= 0 || $(this).hasClass('running') ) {
			console.log('Already Running');
			return false;
		}

		var s_var_1 = $(this).attr('id').replace('wushka-sample-', '').trim();
		if ( s_var_1.length <= 0 || s_var_1 == null ) {
			console.log('Missing Parameters');
			return false;
		}

		var s_var_2 = $(this).find('#wsh_a').val() == undefined ? $(this).find('.wsh_a').val().trim() : $(this).find('#wsh_a').val().trim();
		if ( s_var_2.length <= 0 || s_var_2 == null ) {
			console.log('Missing Parameters');
			return false;
		}

		var s_var_3 = $(this).find('#wsh_b').val() == undefined ? $(this).find('.wsh_b').val().trim() : $(this).find('#wsh_b').val().trim();
		if ( s_var_3.length <= 0 || s_var_3 == null ) {
			console.log('Missing Parameters');
			return false;
		}

		//Get Variables
		var tmp_url = a_ereader_iframe.ajax_url;
		var s_validate = a_ereader_iframe.validate;

		console.log('book: '+s_var_1, s_var_2, s_var_3);

		$(this).addClass('running');
		run_ereader_ajax(tmp_url, s_validate, s_var_1, s_var_2, s_var_3);
	});

	function run_ereader_ajax( s_tmp_url, s_validate, s_var_1, s_var_2, s_var_3 ) {
		console.log('----- Start Ereader Ajax -----');
		$.ajax({
			type: "POST",
            dataType: "json",
            url: s_tmp_url,
            data: {
            	action   	: 'wushka_free_sample_reader',
             	s_validate 	: JSON.stringify(s_validate),
            	s_var_1 	: JSON.stringify(s_var_1),
            	s_var_2 	: JSON.stringify(s_var_2),
            	s_var_3 	: JSON.stringify(s_var_3),
            },
            success: function (return_data) {
            	console.log('Ajax Success:');
				console.log('Status = '+return_data.status);
				console.log('Message = '+return_data.message);
				if ( validate_ajax_return(return_data) === false ) {
					console.log('Error Found in Return Data');
	           		$('#ereader-modal').modal('hide');
            	} else {
            		//Print Iframe to modal
	           		$('#ereader-modal #iframe-wrapper').empty().append(return_data.html);
    			}
            	return true;
            },
            error: function(jqXHR, textStatus, errorThrown ) {
				console.log('Ajax Error:');
            	console.log(jqXHR);
            	console.log(textStatus);
            	console.log(errorThrown);
            },
            complete: function(){
            	$('a.item-detail.wushka-sample').removeClass('running');
            	console.log('Ajax Completed');
            }
		});
		console.log('------ End Ereader Ajax ------');
	}

	function validate_ajax_return(a_return) {
		//Undefined Check
		if ( typeof a_return == 'undefined' || a_return === undefined ) {
			return false;
		}
		//Empty Check
		if ( a_return == null || a_return.length <= 0 ) {
			return false;
		}
		//Return Status Field Check
		if ( a_return.status == 'failed' ) {
			return false;
		}

		return true;
	}
});
/* ----- EOF -----*/