<?php
/*
  Template Name: Teacher - First Time Password Change
 */

$s_get_acc = (string) trim(filter_input(INPUT_GET, 'u'));
$s_get_confirm = (string) trim(filter_input(INPUT_GET, 'c'));

if ( ! isset($_GET['u'], $_GET['c']) || ! $s_get_acc || ! $s_get_confirm || is_user_logged_in() ) {
	error_log('Invalid parameters: Redirect to Home Page');
	wp_redirect(home_url('/'));
	exit('You do not have access to this page');
}

$new_teacher = get_user_by_hash( $s_get_acc );
if ( ! isset($new_teacher) || $new_teacher === FALSE || is_wp_error($new_teacher) ) {
	error_log('Could Not Find Passed User: Redirect to Home Page');
	wp_redirect(home_url('/'));
	exit('User email not found');
}

$b_window_open = FALSE;

$s_meta_code = (string) $new_teacher->tmp_pwd_verify;
$s_meta_window = (string) $new_teacher->tmp_pwd_window;
$s_var_1 = NULL;
$s_var_2 = NULL;
$s_var_3 = NULL;

error_log('meta code ' . $s_meta_code);
error_log('meta window ' . $s_meta_window);
if ( isset($s_meta_code, $s_meta_window) && ! empty($s_meta_code) && ! empty($s_meta_window) ) {

	//Double Check Window is Still open
	$td_now = new DateTime('NOW');
	$td_now->setTimezone(new DateTimeZone('UTC'));
	$now = $td_now->format('Y-m-d');

	$td_window = new DateTime($s_meta_window);
	$window = $td_window->format('Y-m-d');

	if ( $s_get_acc == $new_teacher->id_hash && $s_get_confirm == $s_meta_code) {
		if ($now <= $window) {
			$b_window_open = 1;

			//User Hash
			$s_var_1 = $new_teacher->id_hash;
			//Security
			$s_var_2 = wp_create_nonce( 'passcode_validation_'.(int)$new_teacher->ID );
			//Confirmation Code
			$s_var_3 = $s_get_confirm;
		} else {
			$b_window_open = 2;
		}
	}
}
error_log('Loading Teacher Code Confirmation Page');
get_header();
?>
<style>
#teacher-first-password{background-color:#fff}
#panel{background-color:#f5f5f5;border:1px solid #e3e3e3}
.label{font-size:16px;color:#333;font-family:LatoBold;font-weight:700;line-height:2;padding:0!important}
.show-password{position:relative}
.show-password .fa.inp{position:absolute;top:-3px;right:0;padding-right:10px;cursor:pointer}
.glyphicon-heading-text{color:#111827!important;font-family:LatoBold;font-weight:700;font-size:36px}
@media (max-width: 375px) {
	.glyphicon-heading-text{display:block;width:auto;font-size:28px;margin-left:-40px}
}
@media (max-width: 768px) {
	.glyphicon-heading{flex-direction:row;align-items:center;margin-left:0;margin-top:0}
}
@media (min-width: 1024px) {
	.glyphicon-heading{margin-top:6rem;margin-bottom:20px;margin-left:100px}
}
@media (min-width: 768px) {
	.glyphicon-heading{margin-top:6rem;margin-bottom:20px;margin-left:0}
	.role- .wrapper-main{min-height:500px;background-color:#fff}
}
</style>
<div class="container" id ="teacher-first-password">
	<div class="row mt80">
		<div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-xl-6 col-xl-offset-3" >
			<h1 class="glyphicon-heading " >
				<?php if ( $b_window_open == 1 ) { ?>
					<span class="glyphicon-heading-text">1...2...3... and Wushka! You're in!</span>
				<?php } else if ($b_window_open == 2) { ?>
					<span class="glyphicon-heading-text">Your password has expired</span>
				<?php } else { ?>
				<span class="glyphicon-heading-text">Your account has previously been activated</span>
				<?php } ?>
			</h1>
		</div>
	</div>
	<!--error msg starts-->
	<div class="row">
		<div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-xl-6 col-xl-offset-3">
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
	<!--error msg ends-->
  <div class="row mb60">
		<div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-xl-6 col-xl-offset-3">
			<?php if ( $b_window_open == 2) { ?>
			<div class="panel panel-danger" >
				<div class="panel-heading">
					<p style="margin:0;">It appears that your Wushka login has expired. Please contact your school coordinator<br/>Please contact your school program coordinator</p>
				</div>
				<div class="panel-body">
					<div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-xl-6 col-xl-offset-3">
						<a href="<?php echo home_url('/'); ?>">
							<input type="button" class="btn btn-primary btn-block" value="Return to home page" />
						</a>
					</div>
				</div>
			</div>
			<?php } else if ($b_window_open == 1) { ?>
			<div class="panel panel-success" id="new-password-form">
				<div class="panel-heading">
					<p style="margin:0;">Welcome to Wushka!</p>
				</div>
				<div class="panel-body"id="panel">
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<label for="new_password" class="form-label label">Please Enter a password for your account:</label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<span class="show-password"><input type="password" id="new_password" class="form-control" placeholder="letters and numbers only..." />
							<i class="fa fa-eye inp" onclick="togglePasswordVisibility('new_password', 'inp')" id ="inp"></i></span>
						</div>
					</div>
					<div class="form-group" >
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<label for="new_password" class="form-label label" style="margin-top:10px;">Confirm your new password:</label>
							
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<span class="show-password"><input type="password" id="confirm_password" class="form-control" placeholder="letters and numbers only..." />
							<i class="fa fa-eye inp" onclick="togglePasswordVisibility('confirm_password', 'inp1')" id ="inp1"></i></span>
						</div>
					<!--here-->
					</div>
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 padding-y">
							<input type="hidden" id="_var_1" value="<?php echo $s_var_1; ?>" />
							<input type="hidden" id="_var_2" value="<?php echo $s_var_2; ?>" />
							<input type="hidden" id="_var_3" value="<?php echo $s_var_3; ?>" />
							<input type="hidden" id="_var_4" value="" />
							<input type="button" class="btn btn-primary btn-block" id="teacher-password-confirmation" value="Set Password" />
						</div>
					</div>
				</div>
			</div><!-- END PANEL -->
			<?php } else { ?>
				<div class="panel panel-warning">
				<div class="panel-heading">
					<p style="margin:0;">It appears that your Wushka account has already been activated.</p>
				</div>
				<div class="panel-body">
					<div class="col-xs-12 col-md-6 col-md-offset-5">
						<a class="navbar-btn btn btn-primary btn-login" href="<?php echo esc_url(get_permalink(get_page_by_title('Login'))); ?>">Login</a>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<?php
get_footer();
/* ----- End Of File ----- */
