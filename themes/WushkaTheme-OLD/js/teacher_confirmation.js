jQuery(document).ready(function($) {
	var b_matching_pwd = false;
	console.log = function() {}
	$('#new-password-form').on('keyup', '#new_password, #confirm_password', function(){
		//Check Both password fields Match
		check_password_fields();
	});

	$(document).on('click', '#teacher-password-confirmation', function(){
		if ( a_teacher_confirm.length <= 0 || $(this).hasClass('running') ) {
			return false;
		}

		//Check Both password fields Match
		check_password_fields();

		if ( b_matching_pwd === false ) {
			return false;
		}

		//Get Variables
		var tmp_url = a_teacher_confirm.ajax_url;
		var s_validate = a_teacher_confirm.validate;
		var s_var_1 = $(this).parent().find('#_var_1').attr('value').trim();
		var s_var_2 = $(this).parent().find('#_var_2').attr('value').trim();
		var s_var_3 = $(this).parent().find('#_var_3').attr('value').trim();
		var s_var_4 = $('#new-password-form #new_password').val().trim();

		$(this).addClass('running');
		$(this).attr('value', 'Activating account...');
		$('body').css({'cursor':'wait'});

		console.log('Passwords do match, Continue!');

		generate_account_link(tmp_url, s_validate, s_var_1, s_var_2, s_var_3, s_var_4);
	});

	function generate_account_link( s_tmp_url, s_validate, s_var_1, s_var_2, s_var_3, s_var_4 ) {
		$.ajax({
			type: "POST",
            dataType: "json",
            url: s_tmp_url,
            data: {
            	action   : 'wushka_teacher_confirmation',
            	s_var_1 : JSON.stringify(s_var_1),
            	s_var_2 : JSON.stringify(s_var_2),
            	s_var_3 : JSON.stringify(s_var_3),
            	s_var_4 : JSON.stringify(s_var_4),
            	s_validate : JSON.stringify(s_validate)
            },
            success: function (return_data) {
				console.log('Ajax Confirmed!');
				console.log('Status = '+return_data.status);
				console.log('Message = '+return_data.message);

				if ( typeof return_data == 'undefined' || return_data == null || return_data.status == 'failed' ) {
            		toggle_match_panels(false);
                	$('#panel_mismatch p span').empty().append('An Error Occurred saving ');
                	return false;
            	}

            	if ( return_data.status == 'success' ) {
            		toggle_match_panels(true);
                	$('#panel_match p span').empty().append('Your password has been saved!');
					window.location.href = return_data.url;
                	//load_first_time_dashboard(return_data.url);
            	}
            	return true;
            },
            error: function(jqXHR, textStatus, errorThrown ) {
				console.log('Ajax Error!');
            	toggle_match_panels(false);
            	$('#panel_mismatch p span').empty().append('An Error Occurred saving ');
            	console.log(jqXHR);
            	console.log(textStatus);
            	console.log(errorThrown);
            	location.reload();
            }
		});
	}

	function toggle_match_panels(b_state) {
		switch(b_state) {
			case true:
				$('#panel_match').show();
				$('#panel_mismatch').hide();
				break;
			case false:
				$('#panel_match').hide();
				$('#panel_mismatch').show();
				break;
			default:
				$('#panel_match').hide();
				$('#panel_mismatch').hide();
				break;
		}
	}

	function check_password_fields() {
		var new_pwd = $('#new-password-form #new_password').val().trim();
		var confirm_pwd = $('#new-password-form #confirm_password').val().trim();
		$('#panel_mismatch p span').empty().append(' Your passwords do not match');

		if ( new_pwd == '' || confirm_pwd == '' ) {
			b_matching_pwd = false;
			toggle_match_panels(false);
			//console.log('No Password Entered');
			$('#panel_mismatch p span').empty().append(' Please fill both password fields');
			return false;
		}

		if ( new_pwd == confirm_pwd ) {
			b_matching_pwd = true;
			toggle_match_panels(true);
		} else {
			b_matching_pwd = false;
			toggle_match_panels(false);
		}
	}

	function load_first_time_dashboard(s_url) {
		var e_form = document.createElement('form');
		$(e_form).attr("action", s_url).attr("method", "POST");
		$(e_form).html('<input type="hidden" name="new_user" value="new_teacher" />');

        document.body.appendChild(e_form);
        $(e_form).submit();
        document.body.removeChild(e_form);
    }
});