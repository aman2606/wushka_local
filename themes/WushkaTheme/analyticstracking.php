<?php
global $wpdb;
global $wp_user;

$ga_userId   = 0;
$ga_userType = 'anonymous';
$ga_section  = 'ebook';
$ga_fsCookie = isset($_COOKIE['lessonzone_freeSampleUser']) ? $_COOKIE['lessonzone_freeSampleUser'] : 'noSet';

/* ---------- Set The Appropriate Variable for Current Account Type ----------
 * - User NOT logged in:
 * - Anonymous: User is not logged in and has NOT signed up for Free Sampeles
 * - Register : User is not logged in but HAS signed up for Free Samples
 * - User LOGGED IN:
 * - Subscribe: User is logged in and does NOT have a customer type account
 * - Customer : User is logged in and DOES have a customer type account
 */
if( ! is_user_logged_in() ) {
    //No User
    if ( $ga_fsCookie == 'isSet') {
    	$ga_userType = 'register';
    }
} else {
    //User is logged in
    $o_user = wp_get_current_user();
    $ga_userId = $o_user->ID;
    $ga_userType = 'subscribe';
    $a_types = array( 'teacher', 'student', 'customer' );

    foreach( $a_types as $i_key => $s_type ) {
	    if ( user_can( $o_user->ID, $s_type ) ) {
			$ga_userType = $s_type;
			break;
	    }
    }
}

$tag_manager = of_get_option('google_tag_manager');
$bing_verification = of_get_option('bing_verification');
$pinterest_verification = of_get_option('pinterest_verification');
?>
<script>
var dataID 	= '<?php echo $ga_userId;   ?>';
var dataType	= '<?php echo $ga_userType; ?>';
var dataSec	= '<?php echo $ga_section; ?>';

dataLayer = [{ 'cID' : dataID, 'memType' : dataType, 'sec' : dataSec }];
</script>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=<?php echo $tag_manager ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?php echo $tag_manager ?>');</script>
<!-- End Google Tag Manager -->