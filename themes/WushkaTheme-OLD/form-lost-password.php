<?php

/**
 * Lost password form
 *
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
$attributes['errors'] = array();
if (isset($_GET['errors'])) {
	$error_codes = explode(',', $_GET['errors']);

	foreach ($error_codes as $error_code) {
		$attributes['errors'][] = get_error_message($error_code);
	}
}

$args['form'] = 'lost_password';
?>
<style>
@media (min-width: 768px) {
.role- .wrapper-main{min-height:518px}
}
.wushka-login-wrapper{background-color:#fff}
#lost-password{margin-top:6rem}
.label-lost-password{font-size:16px;color:#333;font-family:LatoBold;font-weight:700}
.label-lost-heading{color:#111827;font-family:LatoBold;font-weight:700;font-size:36px}
</style>
<div role="main" class="wushka-login-wrapper">
	<div class="container">
		<div class="row" id="lost-password">

			<div class="login-heading col-xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-xl-6 col-xl-offset-3">
				<h1 class="label-lost-heading">Lost Your Password?</h1>
			</div>
			<div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-xl-6 col-xl-offset-3">

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
				<section class="lost-password-wrapper" id="main-content">
					<form method="post" class="lost_reset_password" action="<?php echo wp_lostpassword_url(); ?>">
						<div class="well">


							<div class="form-group">
								<div class="col-sm-12" style="padding-bottom: 10px;">
									<p class="woo_lost-psw-msg">
										<?php echo apply_filters('woocommerce_lost_password_message', __('Please enter your username. You will receive a link to create a new password via email.', 'woocommerce')); ?>
									</p>
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 control-label"><label for="user_login" class="label-lost-password"><?php _e('Username', 'woocommerce'); ?></label></div>
								<div class="col-xs-12 col-sm-12"><input class="input-text form-control" type="text" name="user_login" id="user_login" required /></div>
							</div>
							<div class="clear"></div>
							<div class="form-group" style="padding-top:30px;">
								<input type="hidden" name="wc_reset_password" value="true" />
								<div class="col-xs-12 col-sm-12 mt15"><button type="submit" class="btn btn-primary btn-block">
										<?php echo 'lost_password' == $args['form'] ? __('Reset Password', 'woocommerce') : __('Save', 'woocommerce'); ?>
									</button></div>
							</div>
							<?php wp_nonce_field($args['form']); ?>
						</div>
					</form>
				</section>
			</div>
		</div>
	</div>
</div>
<?php /* --- EOF --- */ ?>