<?php

/**
 * reset password form
 *
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
$user = false;
$attributes = array();
if (isset($_GET['login']) && isset($_GET['key'])) {
	$attributes['login'] = sanitize_text_field($_GET['login']);
	$attributes['key'] = sanitize_text_field($_GET['key']);

	// Error messages
	$errors = array();
	if (isset($_GET['error'])) {
		$error_codes = explode(',', sanitize_text_field($_GET['error']));

		foreach ($error_codes as $code) {
			$errors[] = get_error_message($code);
		}
	}

	if (isset($_GET['reset_error']) && !empty($_GET['reset_error'])) {
		$errors[] = 'Your password does not meet the password policy requirements.';
	}
	$attributes['errors'] = $errors;
	$user = get_user_by('login', $attributes['login']);
} else {
	$attributes['errors'][] = get_error_message('invalidkey');
}


?>
<style type="text/css">
	body {
		margin-bottom: 0 !important
	}

	.well {
		height: auto;
		overflow-y: hidden
	}

	main[role="main"] {
		padding-top: 80px;
		padding-bottom: 80px;
		background-color: #fff
	}

	.control-label {
		font-size: 16px;
		color: #333;
		font-family: LatoBold;
		font-weight: 700
	}

	.login-h1 {
		padding: 0;
		margin: 0;
		text-align: left;
		color: #111827 !important;
		font-size: 36px;
		text-transform: capitalize;
		font-family: LatoBold;
		font-weight: 700
	}

	.show-password {
		position: relative
	}

	.show-password .fa.inp {
		position: absolute;
		top: -3px;
		right: 0;
		padding-right: 10px;
		cursor: pointer
	}

	@media (min-width: 768px) {
		.role- .wrapper-main {
			min-height: 574px
		}
	}

	label.error {
		font-weight: 100;
		color: red;
		padding: 2px 0px;
		margin-top: 0px;
		display: block;
		font-size: 1.3rem;
	}

	input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]) {
		font-family: inherit;
		letter-spacing: normal;
		min-height: 35px;
		display: inline-block;
		padding: 4px 8px;
		margin-bottom: 0px;
		font-size: 1.4rem;
		line-height: 1.6;
		color: #222;
		vertical-align: middle;
		border-radius: 2px;
		background-color: #fff;
		border: 1px solid #ccc;
		box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
		transition: border linear .2s, box-shadow linear .2s;
	}

	label {
		display: inline-block;
		margin-bottom: 5px;
		font-weight: bold;
		margin-top: 3px;
	}
</style>
<main role="main" class="wushka-login-wrapper">
	<div class="container mt80">
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-xl-6 col-xl-offset-3">

				<?php if (!user_can($user, 'student')) { ?>
					<div class="alert alert-warning" role="alert">
						As a non-student user, your password must meet the password policy criteria (At least 15 characters long and include letters, numbers, and symbols).
					</div>
				<?php } ?>
				<!--erro msg starts-->
				<?php if (count($attributes['errors']) > 0) : ?>
					<?php foreach ($attributes['errors'] as $error) : ?>
						<?php
						$alert = [
							'type'        => 'danger',
							'message'    => $error
						];
						if (!empty($alert)) {
							$alert_type = isset($alert['type']) ? ($alert['type'] == 'error' ? 'danger' : $alert['type']) : '';
							$alert_message = (isset($alert['message'])) ? $alert['message'] : '';
						?>
							<div class="alert alert-<?= $alert_type; ?> alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">
										&times;
									</span>
								</button>
								<?= $alert_message; ?>
							</div>
						<?php } ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<!--error msg ends-->
			</div>
			<div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-xl-6 col-xl-offset-3">
				<div class="login-heading">
					<h1 class="login-h1">Password Reset</h1>
				</div>
				<section class="lost-password-wrapper">
					<form method="post" class="lost_reset_password validation" action="<?php echo site_url('wp-login.php?action=resetpass'); ?>">
						<input type="hidden" id="user_login" name="rp_login" value="<?php echo esc_attr($attributes['login']); ?>" autocomplete="off" />
						<input type="hidden" name="rp_key" value="<?php echo esc_attr($attributes['key']); ?>" />
						<div class="well">
							<div class="form-group">
								<div class="col-xs-12 control-label">
									<label for="password_1">
										<?php _e('Enter your new password', 'woocommerce'); ?> <span class="required">*</span>
									</label>
								</div>
								<div class="col-xs-12">
									<span class="show-password">
										<input type="password" class="input-text form-control" name="password_1" id="password_1" placeholder="Enter your new password" pattern="[A-Za-z0-9]+" />
										<i class="fa fa-eye inp" onclick="togglePasswordVisibility('password_1', 'inp')" id="inp"></i>
									</span>
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-12 control-label">
									<label for="password_2" id="password_2-error">
										<?php _e('Re-enter your new password', 'woocommerce'); ?> <span class="required">*</span>
									</label>
								</div>
								<div class="col-xs-12">
									<span class="show-password">
										<input type="password" class="input-text form-control" name="password_2" id="password_2" placeholder="Re-enter your new password" pattern="[A-Za-z0-9]+" />
										<i class="fa fa-eye inp" onclick="togglePasswordVisibility('password_2', 'inp1')" id="inp1"></i>
									</span>
								</div>
							</div>
							<div class="clear "></div>
							<div class="form-group col-xs-12" style="padding-top:20px;">
								<input type="hidden" name="wc_reset_password" value="true" />
								<input type="submit" class="btn btn-primary btn-block" value="Reset Password" id="submit" />
							</div>
							<?php wp_nonce_field(); ?>
						</div>
					</form>
				</section>
			</div>
		</div>
	</div>
</main>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js" type="text/javascript"></script>
<script>
	/*jQuery.validator.addMethod("pwcheck", function(value) {
		return /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{4,})/.test(value)
		});*/

	jQuery('.validation').validate({
		rules: {
			password_1: {
				minlength: 4,
				//pwcheck: true
			},
			password_2: {
				minlength: 4,
				equalTo: "#password_1"
			}
		}

	});

	$('#submit').click(function() {

		console.log($('.validation').valid());
	});
</script>
<?php /* --- EOF --- */ ?>