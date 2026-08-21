<?php
/**
 * Created by PhpStorm.
 * User: Jordan
 * Date: 16/03/2016
 * Time: 3:02 PM
 */
/* -----------------------------------------------
 * Wushka Ajax Functions
 * ----------------------------------------------- */

add_action('wp_enqueue_scripts', 'lessonzone_scripts');
add_action('admin_enqueue_scripts', 'wushka_school_term_link_script');
add_action('wp_enqueue_scripts', 'wushka_general_scripts');

function lessonzone_scripts() {
    $s_uri = get_template_directory_uri();

    wp_enqueue_script('jquery-validate', $s_uri . '/js/jquery.validate.min.js', array('jquery'), NULL, TRUE);
    wp_enqueue_script('jquery-touchswipe', $s_uri . '/js/jquery.touchSwipe.min.js', array('jquery'), NULL, TRUE);

    // Enqueued JQuery ui components
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-widget');
    wp_enqueue_script('jquery-ui-mouse');
    wp_enqueue_script('jquery-ui-accordion');
    wp_enqueue_script('jquery-ui-autocomplete');
    wp_enqueue_script('jquery-ui-slider');
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('jquery-ui-draggable');
    wp_enqueue_script('jquery-ui-droppable');
    wp_enqueue_script('jquery-ui-resize');
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_script('jquery-ui-button');
    wp_enqueue_script('jquery-touch-punch');
}

function wushka_school_term_link_script( $hook ) {
    if( 'edit-tags.php' !== $hook ) {
        return;
    }
    $s_uri = get_template_directory_uri() . '/js/school_term-links.js';
    wp_enqueue_script('school_term_link_script', $s_uri, array('jquery'), FALSE, TRUE);
    wp_localize_script('school_term_link_script', 'school_account_terms', array(
        'ajax_url' => esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")),
        'validate' => wp_create_nonce('account_term_link_validation')
    ));
}

//General Wushka Scripts
function wushka_general_scripts() {
    $s_uri = get_template_directory_uri() . '/js/jquery.form.2020.js';
    wp_enqueue_script('jquery-ajaxSubmit', $s_uri, array('jquery'), NULL, TRUE);

    $s_uri = get_template_directory_uri() . '/js/ereader_iframe.js';
    wp_enqueue_script('ereader_script', $s_uri, array('jquery'), NULL, TRUE);
    wp_localize_script('ereader_script', 'a_ereader_iframe',
        array(
            'ajax_url' => esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")),
            'validate' => wp_create_nonce('ereader_iframe_validation')
        )
    );

    //Animated Buttons
    $s_uri = get_template_directory_uri() . '/js/wushka_buttons.js';
    wp_enqueue_script('wushka_buttons_script', $s_uri, array('jquery'), FALSE, TRUE);

    if( is_page('new-teacher-confirmation') ) {
        $s_uri = get_template_directory_uri() . '/js/teacher_confirmation.js';
        wp_enqueue_script('teacher_confirm_script', $s_uri, array('jquery'), FALSE, TRUE);
        wp_localize_script('teacher_confirm_script', 'a_teacher_confirm',
            array(
                'ajax_url' => esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")),
                'validate' => wp_create_nonce('teacher_confirmation_validation')
            )
        );
    } else if( is_page('new-trial-activation') ) {
        $s_uri = get_template_directory_uri() . '/js/user_confirmation.js';
        wp_enqueue_script('user_confirm_script', $s_uri, array('jquery'), FALSE, TRUE);
        wp_localize_script('user_confirm_script', 'a_user_confirm',
            array(
                'ajax_url'      => esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")),
                'ajax_validate' => wp_create_nonce('user_confirmation_validation')
            )
        );
    } else if( is_page('child-add') ) {
        //Page JS
        $s_uri = get_template_directory_uri() . '/js/school_create_child.js';
        wp_enqueue_script('user_script', $s_uri, array('jquery'), FALSE, TRUE);
        wp_localize_script('user_script', 'a_user_script',
            array(
                'ajax_url' => esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")),
                'validate' => wp_create_nonce('child_validation')
            )
        );
        //Google Maps JS
        wp_enqueue_script('google_script', 'https://maps.googleapis.com/maps/api/js?region=AU', array('jquery'), NULL, TRUE);
    } else if( is_page('view-schools') ) {
        $s_uri = get_template_directory_uri() . '/js/bisdev_view-schools.js';
        wp_enqueue_script('view-schools_script', $s_uri, array('jquery'), FALSE, TRUE);
        wp_localize_script('view-schools_script', 'a_schools_script',
            array(
                'ajax_url' => esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")),
                'validate' => wp_create_nonce('bisdev_validation')
            )
        );
    } else if( is_page('student-statistics') || is_page('my-page') ) {
        $s_uri = get_template_directory_uri();
        //CSS
        wp_register_style('student_statistics', $s_uri . '/css/teacher_student-statistics.css', __FILE__);
        wp_enqueue_style('student_statistics');
        //JS
        wp_enqueue_script('image_capture', $s_uri . '/js/html2canvas.js', array('jquery'), FALSE, TRUE);
        wp_enqueue_script('student_statistics', $s_uri . '/js/teacher_student-statistics.js', array('jquery'), FALSE, TRUE);
        wp_localize_script('student_statistics', 'a_student_statistics',
            array(
                'ajax_url'   => esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")),
                's_validate' => wp_create_nonce('get_student_statistics')
            )
        );
    } else if( is_page('manage-class-list') ) {
        $s_uri = get_template_directory_uri();
        wp_register_style('page_mcl', $s_uri . '/css/teacher_manage-class-list.css', __FILE__);
        wp_enqueue_style('page_mcl');
    } else {
        return;
    }
}

/* ----- END OF FILE ----- */