jQuery(document).ready(function($) {
    //Store Floating Validation Array from Wordpress enqueue
    var a_confirm = a_user_confirm;
	var b_matching_pwd = false;

	console.log = function() {}

	$('#new-password-form').on('keyup', '#new_password, #confirm_password', function(){
		//Check Both password fields Match
		check_password_fields();
	});

    //Resend Confirmation Email
    $(document).on('click', '#resend_activation', function() {
        if ( a_confirm.length <= 0 || $(this).hasClass('running') ) {
            return false;
        }
        $(this).addClass('running');
        $(this).attr('value', 'Sending Email...');
        $('body').css({'cursor':'wait!important'});
        var s_hash = $('#resend-email-wrap').find('#user_hash').val().trim();
        console.log('resend email for user '+s_hash);
        resend_activation_ajax(s_hash);
    });

    function resend_activation_ajax(s_hash) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: a_confirm.ajax_url,
            data: {
                action      : 'wushka_user_confirmation_resend',
                s_var_1     : JSON.stringify(s_hash)
            },
            success: function (return_data) {
                console.log('Ajax Confirmed!');
                console.log('Status = '+return_data.status);

                if ( typeof return_data == 'undefined' || return_data == null || return_data.status == 0 ) {
                    $('#resend-email-wrap').fadeTo(300, 0, function(){
                        $('#resend-email-wrap').hide();
                        $('#resend-email-error').show().fadeTo(300, 1);
                    });

                } else if ( return_data.status > 0 ) {
                    $('#resend-email-wrap').fadeTo(300, 0, function(){
                        $('#resend-email-wrap').hide();
                        $('#resend-email-success').show().fadeTo(300, 1);
                    });
                }

                $(this).removeClass('running');
                $('body').css({'cursor':'default'});
                return true;
            },
            error: function(jqXHR, textStatus, errorThrown ) {
                console.log('Ajax Error!');
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
                $('#resend-email-wrap').fadeTo(300, 0, function(){
                    $('#resend-email-wrap').hide();
                    $('#resend-email-error').show().fadeTo(300, 1);
                });
                $(this).removeClass('running');
                $('body').css({'cursor':'default'});
            }
        });
    }

    var preventSubmit = function(event) {
        if( event.keyCode == 13 ) {
            event.preventDefault();
            $('#user-password-confirmation').click();
        }
    }

    $('#new-password-form').keypress(preventSubmit);
    $('#new-password-form').keydown(preventSubmit);
    $('#new-password-form').keyup(preventSubmit);

	$(document).on('click', '#user-password-confirmation', function(){
		if ( a_confirm.length <= 0 || $(this).hasClass('running') ) {
			return false;
		}

		//Check Both password fields Match
		check_password_fields();

		if ( b_matching_pwd === false ) {
			return false;
		}

		//Get Variables
		var tmp_url = a_confirm.ajax_url;
		var s_validate = a_confirm.ajax_validate;
		var s_var_1 = $(this).parent().find('#_var_1').attr('value').trim();
		var s_var_2 = $(this).parent().find('#_var_2').attr('value').trim();
		var s_var_3 = $(this).parent().find('#_var_3').attr('value').trim();
		var s_var_4 = $('#new-password-form').find('#new_password').val().trim();

        run_load_css();

		console.log('Passwords do match, Continue!');

		generate_account_link(tmp_url, s_validate, s_var_1, s_var_2, s_var_3, s_var_4);
	});

	function generate_account_link( s_tmp_url, s_validate, s_var_1, s_var_2, s_var_3, s_var_4 ) {
		var e_alert = $('#panel_mismatch').find('p').find('span');
		$.ajax({
			type: "POST",
            dataType: "json",
            url: s_tmp_url,
            data: {
            	action      : 'wushka_user_confirmation',
            	s_var_1     : JSON.stringify(s_var_1),
            	s_var_2     : JSON.stringify(s_var_2),
            	s_var_3     : JSON.stringify(s_var_3),
            	s_var_4     : JSON.stringify(s_var_4),
            	s_validate : JSON.stringify(s_validate)
            },
            success: function (return_data) {
				console.log('Ajax Confirmed!');
				console.log('Status = '+return_data.status);
				console.log('Message = '+return_data.message);
                console.log('URL = '+return_data.url);

				if ( typeof return_data == 'undefined' || return_data == null || return_data.status == 0 ) {
            		toggle_match_panels(false);
					e_alert.empty().append(' An Error Occurred saving ');
                    console.log(' An Error Occurred saving ');
                } else if ( return_data.status > 0 ) {
            		toggle_match_panels(true);
					e_alert.empty().append(' Your password has been saved!');
                    $('#user-password-confirmation').attr('value', 'Preparing Trial...');
                    window.location.href = return_data.url;
                	//load_first_time_dashboard(return_data.url);
            	}

                end_load_css();
                return true;
            },
            error: function(jqXHR, textStatus, errorThrown ) {
				console.log('Ajax Error!');
            	toggle_match_panels(false);
				e_alert.empty().append(' An Error Occurred saving ');
            	console.log(jqXHR);
            	console.log(textStatus);
            	console.log(errorThrown);
                end_load_css();
            }
		});
	}

    function run_load_css() {
        var e_btn = $('#user-password-confirmation');
        e_btn.addClass('running');
        e_btn.attr('value', 'Activating Account...');
        $('body').css({'cursor':'wait!important'});
    }

    function end_load_css() {
        var e_btn = $('#user-password-confirmation');
        e_btn.removeClass('running');
        e_btn.attr('value', 'Set Password');
        $('body').css({'cursor':'default'});
    }

	function toggle_match_panels(b_state) {
		var e_match 	= $('#panel_match');
		var e_mismatch 	= $('#panel_mismatch');

		switch(b_state) {
			case true:
				e_match.show();
				e_mismatch.hide();
				break;
			case false:
				e_match.hide();
				e_mismatch.show();
				break;
			default:
				e_match.hide();
				e_mismatch.hide();
				break;
		}
	}

	function check_password_fields() {
		var e_form = $('#new-password-form');
		var e_new = e_form.find('#new_password');
		var e_confirm = e_form.find('#confirm_password');
		var new_pwd = e_new.val().trim();
		var confirm_pwd = e_confirm.val().trim();
		var e_alert = $('#panel_mismatch').find('p').find('span');

		e_alert.empty().append(' Your passwords do not match');
		if ( new_pwd.length <= 0 || confirm_pwd.length <= 0 ) {
			b_matching_pwd = false;
			toggle_match_panels(false);
			//console.log('No Password Entered');
			e_alert.empty().append(' Please fill both password fields');
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
		return true;
    }
});