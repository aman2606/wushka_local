<?php
/*
  Template Name: ereader
 */
?>

<?php
$b_sample_frame = FALSE;

$query = array();
$i_id = null;
if (!isset($_SESSION)) {
    session_start();
}


if ($_GET['book']) {
    $resource = explode('/', $_GET['book']);

    $args = array(
        'post_type' => 'ebook',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'meta_query' => array(
            0 => array('key' => 'esiss_resource_id',
                'value' => $resource[2],
                'compare' => '='
            )
        )
    );
    $query = get_posts($args);
    $i_id = $query[0];
}

if ( ! is_user_logged_in() ) {
	if (isset($_GET['reader']) && $_GET['reader'] == 'samples' ) {
		$b_sample_frame = TRUE;
	}
	$s_sample = get_post_meta( $i_id, 'esiss_free_sample', true );
	if ( $s_sample == 'Y' ) {
		$quiz_id = NULL;
		$narration = 'Yes';
		$s_page_link = '/';
	} else {
		wp_redirect(esc_url(get_permalink(get_page_by_title('My Account'))));
		exit();
	}
} else {

	if ( ! has_valid_subscription() ) {
	    wp_redirect(esc_url(get_permalink(get_page_by_title('Subscription'))));
	    exit;
	}

	$quiz_id = get_post_meta($i_id, 'wushka_quiz_id', true);
	$s_page_link = '/ebook/'.get_post_field('post_name', $i_id);

	if (current_user_can("teacher") || current_user_can("parent")) {
		$_SESSION['check_for_quiz'] = $quiz_id;
		$quiz_id = NULL;
	}

	$narration = "Yes";
	$quiz = 'compulsory';
	if (user_can($current_user, "student")) {
	    $narration = get_user_meta($current_user->ID, 'narration', true);
		$quiz = isset($current_user->quizzes) ? strtolower($current_user->quizzes) : 'compulsory';
	}

}


?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="p:domain_verify" content="0b1f38a6c5f52782dddde74afcc90cd1"/>
        <title> <?php wp_title('|', true, 'right'); ?> </title>
        <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.css">
        <link href="<?php echo get_template_directory_uri(); ?>/style.css" rel="stylesheet">
        <link href="<?php echo get_template_directory_uri(); ?>/css/glyphicons.css" rel="stylesheet">
        <link href="<?php echo get_template_directory_uri(); ?>/css/reader.css" rel="stylesheet">
        <?php wp_head(); ?>
        <?php if ( $b_sample_frame === TRUE ) { ?>
        	<style type="text/css">
				span#close_book {
					display:none;
					opacity:0;
				}
				span.label {
				    position: relative;
				    top: 10px;
				    left: -14px;
				    width: 55px;
				    color: inherit;
				    text-align: center;
				}
				.ctrl {
					vertical-align: top;
					height: 50px;
					width: 50px;
				}
				.student .ctrl-arrow-right {
					width: 60px;
  					height: 90px;
				}
				.student .ctrl-arrow-right span.label {
					left: -50px;
					top: 20px;
					color: #4E6218;
				}
			</style>
        <?php } else { ?>
        	<style type="text/css">
				span.label {
					display:none!important;
				}
			</style>
        <?php } ?>
    </head>

    <body <?php body_class(); ?> style="margin-bottom:0px;">
        <?php get_template_part('analyticstracking'); ?>
        <noscript>
        </noscript>
        <?php $s_narration = (isset($narration) && $narration == 'Yes') ? 'true' : 'false'; ?>
        <?php if ($quiz !== 'no' && (isset($quiz_id) && ! empty($quiz_id))) { ?>
        	<div id="reader" data-narration="<?php echo $s_narration; ?>" data-reader-style="student" data-home="/" data-info-page="/quiz/<?php echo $quiz_id; ?>"></div>
        <?php } else { ?>
        	<div id="reader" data-narration="<?php echo $s_narration; ?>" data-reader-style="student" data-home="/" data-info-page="<?php echo $s_page_link; ?>"></div>
        <?php } ?>
        <input type="hidden" name="ebook_id" id="ebook_id" value="<?php echo $i_id; ?>"/>
    </body>

    <script src="<?php echo get_template_directory_uri(); ?>/js/reader.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.touchSwipe.min.js"></script>
    <script>
    jQuery(document).ready(function ($) {
        $("body").swipe({
            //Generic swipe handler for all directions
            swipeLeft:function(event, direction, distance, duration, fingerCount) {
                $('#next').click();
            },
            swipeRight: function() {
                $('#prev').click();
            },
            threshold: 50,
            excludedElements:[]
        });
    });
    </script>
</html>
<?php /* ----- EOF ----- */ ?>