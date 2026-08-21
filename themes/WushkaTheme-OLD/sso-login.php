<?php 
/**
* Template Name: SSO Login template
*/
use App\Controllers\AzureAuth;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if(isset($_GET['login'])){
    if($_GET['login'] == 'failed'){
        $alert = [
            'type'		=> 'danger',
            'message'	=> 'Invalid username or password.'
        ];
    }
    if($_GET['login'] == 'empty'){
        $alert = [
            'type'		=> 'danger',
            'message'	=> 'All fields are required.'
        ];
    }
}

if(isset($_GET['sso']) && $_GET['sso'] == 'nsw_doe') {
    $provider = new AzureAuth();
    $authUrl = $provider->getAuthorizationUrl();
    wp_redirect( $authUrl );
    exit();
}

// Check if the user just requested a new password 
if(isset( $_GET['checkemail'] ) && $_GET['checkemail'] == 'confirm'){
    $alert = [
        'type'		=> 'danger',
        'message'	=> 'Check your email for a link to reset your password.'
    ];
}

if(isset( $_GET['password'] ) && $_GET['password'] == 'changed'){
    $alert = [
        'type'		=> 'success',
        'message'	=> 'Your password has been changed. You can sign in now.'
    ];
}


if (isset($_GET['code']) && isset($_GET['state'])) {
	if(isset($_SESSION['oauth_state'])){
		$code 	= 	sanitize_text_field($_GET['code']);
		$state 	= 	sanitize_text_field($_GET['state']);
		
		$oauthClient = new AzureAuth();

		$validate = $oauthClient->validateAuthState($code, $state);
		if($validate['type'] == 'success'){
			//Authenticate user with token
			$authenticate = $oauthClient->authenticateUser(); 
			$alert = $authenticate;
		}else{
			$alert = $validate;
		}
	}else{
		$alert = [
			'type'		=> 'danger',
			'message'	=> 'Unexpected state.'
		];
	}
}

get_header();
?>

<section class="sso-login-wrapper container mt70 pb70">
    <div class="clearfix">
        <div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-12">
            <h1 class="mb20">Login to your account</h1>

            <?php 
                if(!empty($alert)){
                    $alert_type = (isset($alert['type']))? ($alert['type'] == 'error')? 'danger': $alert['type'] : '';
                    $alert_message = (isset($alert['message']))? $alert['message'] : '';
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

            <div class="well p30">
                <form method="post" action="/wp-login.php" autocomplete="off">
                    <div class="form-group">
                        <label for="username">Username <span class="error">*</span></label>
                        <input type="text" class="form-control" id="username" name="log" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password <span class="error">*</span></label>
                        <input type="password" class="form-control" id="password" name="pwd" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="hidden" name="redirect" value="<?php echo esc_url(home_url()); ?>" />
                                <button type="submit" class="btn btn-primary btn-block" name="login">Login</button>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="<?php echo esc_url( home_url('/lost-password') ); ?>" class="forgot">Forgot Password?</a>
                        </div>
                    </div>

                    <div class="form-divider">
                        <span class="line"></span>
                        <p>or continue with</p>
                    </div>

                    <div class="form-group">
                        <a href="<?php echo esc_url( add_query_arg( 'sso', 'nsw_doe' ) ); ?>" class="btn btn-primary btn-block other-login-btn" title="Login with NSW Department of Education">
                            <img src="<?= get_template_directory_uri(); ?>/img/nsw-government-logo.svg" alt=""> 
                            Login with NSW DOE
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
        
<?php   get_footer();  