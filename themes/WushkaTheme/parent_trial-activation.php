<?php
/*
  Template Name: Parent - Trial User Activation
 */

$s_get_acc = (string) trim(filter_input(INPUT_GET, 'u'));
$s_get_confirm = (string) trim(filter_input(INPUT_GET, 'c'));

//Start Session if it hasn't already
if (!isset($_SESSION)) {
    session_start();
}

#TODO: Use session variable to confirm authenticate code ($_SESSION['trial_activate'])
if ( ! isset($s_get_acc)) {
    error_log('Trial Activation FAIL: missing activation parameters');
    wp_redirect(home_url('/'));
    exit();
}

if ( is_user_logged_in() ) {
	wp_redirect(home_url('/'));
	error_log('User already logged in');
	exit();
}

$new_user = get_user_by_hash( $s_get_acc );
if ( ! isset($new_user) || is_wp_error($new_user) || empty($new_user) ) {
	wp_redirect(home_url('/'));
	error_log('Could Not Find Passed User: Redirect to Home Page');
	exit();
}

$b_window_open = FALSE;
$s_meta_code = (string) $new_user->tmp_pwd_verify;
$s_meta_window = (string) $new_user->tmp_pwd_window;
$s_var_1 = NULL;
$s_var_2 = NULL;
$s_var_3 = NULL;

if ( isset($s_meta_code, $s_meta_window) && ! empty($s_meta_code) && ! empty($s_meta_window) ) {
	//Double Check Window is Still open
	$td_now = new DateTime('NOW');
	$td_now->setTimeZone(new DateTimeZone(get_option('timezone_string')));
	$now = $td_now->format('Y-m-d');

	$td_window = new DateTime($s_meta_window);
	$window = $td_window->format('Y-m-d');

	if ( $s_get_acc == $new_user->id_hash && $s_get_confirm == $s_meta_code && $now <= $window ) {
		$b_window_open = TRUE;

		//User Hash
		$s_var_1 = $new_user->id_hash;
		//Security
		$s_var_2 = wp_create_nonce( 'passcode_validation_'.(int)$new_user->ID );
		//Confirmation Code
		$s_var_3 = $s_get_confirm;
    }
}
error_log('Loading Teacher Code Confirmation Page');
get_header();
?>

<div class="container-fluid">
	<div class="row mt30">
	    <div class="col-xs-12">
	      <h1 class="glyphicon-heading">
			<?php if ( $b_window_open ) { ?>
		        <span class="x2 glyphicon glyphicon-ok-2"></span>
		        <span class="glyphicon-heading-text">1...2...3... and Wushka! You're in!</span>
	        <?php } else { ?>
				<span class="x2 glyphicon glyphicon-remove-2"></span>
		        <span class="glyphicon-heading-text">Your password has expired</span>
	        <?php } ?>
	      </h1>
	    </div>
	</div>
    <div class="row">
        <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-6 col-lg-offset-3">
        	<?php if ( ! $b_window_open ) { ?>
				<div class="panel panel-danger" id="resend-email-wrap">
	            	<div class="panel-heading">
	            		<p style="margin:0;">It appears the temporary confirmation code provided in your email has expired.<br/>Click here to resend your activation email</p>
	            	</div>
	            	<div class="panel-body">
                        <div class="col-xs-12 col-md-6 col-md-offset-3" >
							<input type="hidden" name="user_hash" id="user_hash" value="<?php echo $new_user->id_hash; ?>" />
							<input type="button" name="resend_email" id="resend_activation" class="btn btn-primary btn-block" value="Resend Email" />
                        </div>
	            	</div>
				</div>
                <div class="panel panel-success" id="resend-email-success" style="display:none;opacity:0;">
                    <div class="panel-heading">
                        <p style="margin:0;">Your Email has been sent!</p>
                    </div>
                    <div class="panel-body">
                        <div class="col-xs-12" >
                            <p style="margin:0;">In a few moments your email will be sent. Once it has, click the link inside it to activate your Wushka Trial.</p>
                        </div>
                    </div>
                </div>
                <div class="panel panel-warning" id="resend-email-error" style="display:none;opacity:0;">
                    <div class="panel-heading">
                        <p style="margin:0;">Email did not send!</p>
                    </div>
                    <div class="panel-body">
                        <div class="col-xs-12" >
                            <p style="margin:0;">An error occurred while trying to send you email. Please reload the page and trying again. <br/>If that doesn't work, contact us immediately and we'll fix it for you.</p>
                        </div>
                    </div>
                </div>
			<?php } else { ?>
					<div class="panel panel-success" id="new-password-form">
		            	<div class="panel-heading">
		            		<p style="margin:0;">Welcome to Wushka!</p>
		            	</div>
		   	            <div class="panel-body" id="section-password">
		            		<div class="form-group">
			            		<div class="col-xs-12 col-md-8 col-md-offset-2">
									<label for="new_password" class="form-label">Please Enter a password for your account:</label>
								</div>
								<div class="col-xs-12 col-md-8 col-md-offset-2">
									<input type="password" id="new_password" class="form-control" placeholder="letters and numbers only..." />
								</div>
							</div>
							<div class="form-group" >
								<div class="col-xs-12 col-md-8 col-md-offset-2">
									<label for="new_password" class="form-label" style="margin-top:10px;">Confirm your new password:</label>
								</div>
								<div class="col-xs-12 col-md-8 col-md-offset-2">
									<input type="password" id="confirm_password" class="form-control" placeholder="letters and numbers only..." />
								</div>
								<div class="col-xs-12 col-md-8 col-md-offset-2">
									<div class="panel panel-danger" id="panel_mismatch" style="display:none;margin-top:20px;">
						            	<div class="panel-heading">
						            		<p style="margin:0;"><i class="glyphicon glyphicon-warning-sign" style="vertical-align:sub;"></i><span> Your passwords do not match</span></p>
						            	</div>
						            </div>
						            <div class="panel panel-success" id="panel_match" style="display:none;margin-top:20px;">
						            	<div class="panel-heading">
						            		<p style="margin:0;"><i class="glyphicon glyphicon-circle-ok" style="vertical-align:sub;"></i><span> Passwords Match!</span></p>
						            	</div>
						            </div>
						        </div>
							</div>
							<div class="form-group">
								<div class="col-xs-12 col-sm-8 col-sm-offset-2 padding-y">
									<input type="hidden" id="_var_1" value="<?php echo $s_var_1; ?>" />
									<input type="hidden" id="_var_2" value="<?php echo $s_var_2; ?>" />
									<input type="hidden" id="_var_3" value="<?php echo $s_var_3; ?>" />
									<input type="hidden" id="_var_4" value="" />
									<input type="button" class="btn btn-primary btn-block" id="user-password-confirmation" value="Set Password" />
								</div>
							</div>
		            	</div>
					</div><!-- END PANEL -->
			<?php } ?>
    	</div>
	</div>
</div>

<?php
get_footer();
/* ----- End Of File ----- */