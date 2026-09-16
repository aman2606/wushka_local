<?php
global $current_user;
if (!isset($_SESSION)) {
    session_start();
}
if (is_page('home') && is_user_logged_in()) {
    if (is_super_admin() || is_admin() || user_can($current_user, "student")) {
        $active_user = get_user_meta($current_user->ID, 'active', true);
        if (user_can($current_user, "student") && !$active_user) {
            wp_logout();
            wp_redirect(home_url());
            exit();
        }
        if (user_can($current_user, "student")) {
            error_log('Found student: Redirect to My Page');
            wp_redirect(esc_url(home_url() . '/my-page/'));
            exit();
        }
        // get_header();
        // include 'bookshelves.php';
    }
    // school can have a dual role as a teacher, check for this first
    // error_log('session ' . isset($_SESSION['dashboard_selection']));
    // error_log('user roles' . print_r($current_user->roles, true));
    if (user_can($current_user, "school") && user_can($current_user, "teacher") && !isset($_SESSION['dashboard_selection'])) {
        error_log('dual role redirecting to selection');
        wp_redirect(esc_url(home_url() . '/school-teacher-selection/'));
        exit();
    }
    // check if dual role dashboard selection has been made
    if (isset($_SESSION['dashboard_selection']) && user_can($current_user, $_SESSION['dashboard_selection'])) {
        error_log('dashboard selected, redirecting to:' . $_SESSION['dashboard_selection']);
        wp_redirect(esc_url(home_url() . '/' . $_SESSION['dashboard_selection'] . '-dashboard/'));
        exit();
    }
    if (user_can($current_user, "bdm")) {
        error_log('Found bdm: Redirect to Teacher Dashboard');
        wp_redirect(esc_url(home_url() . '/view-schools/'));
        exit();
    }
    if (user_can($current_user, "teacher")) {
        error_log('Found Teacher: Redirect to Teacher Dashboard');
        wp_redirect(esc_url(home_url() . '/teacher-dashboard/'));
        exit();
    }
    if (user_can($current_user, "parent")) {
        wp_redirect(esc_url(home_url() . '/parent-dashboard/'));
        exit();
    }
    if (user_can($current_user, "school") || (isset($_SESSION['dashboard_selection']) && $_SESSION['dashboard_selection'] === 'school')) {
        error_log('Found School: Redirect to School Dashboard');
        wp_redirect(esc_url(home_url() . '/school-dashboard/'));
        exit();
    }
    
    if(user_can($current_user, OPEN_HOUSE_CUSTOMER)){

        wp_redirect(esc_url(home_url() . '/levelled/'));
        exit();

    }

}
?>


<?php
// On YOOtheme builder pages delegate entirely to the parent theme's page.php
// so get_section('builder') renders the builder content and UIkit drag works.
if (function_exists('wushka_is_yootheme_builder_page') && wushka_is_yootheme_builder_page()) {
    include get_template_directory() . '/page.php';
    return;
}
?>
<?php get_header(); ?>
	<div class="singlepage">
		<?php while (have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('page-wrapper'); ?>>
			<?php wp_reset_query(); ?>
			<div class="page-content">
				<?php
					the_content();
					wp_link_pages( array( 'before' => '<p><strong>' . __('Pages:', 'lessonzone') . '</strong>', 'after' => '</p>' ) );
					edit_post_link(__('Edit Page', 'lessonzone'),'<div class="edit-link"><p class="text-center">','</p></div>');
				?>
			</div>
		</div>
		<?php endwhile; ?>
	</div>
<?php
include 'dashboard_options.php';
get_footer();
?>