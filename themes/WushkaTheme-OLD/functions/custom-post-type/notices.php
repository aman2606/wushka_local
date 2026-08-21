<?php

if (!defined('ABSPATH')) {
    exit(); // Exit if accessed directly
}


/**
 * Helper function for custom post type notice
 *
 * @return array
 */
function helper_custom_post_type_notice()
{
    $custom_post_type = 'notice';
    $cpt_prefix = $custom_post_type . '_meta_';
    return array(
        'id'        => 'notice_attributes',
        'post_type' => $custom_post_type,
        'title'     => 'Notice attributes',
        'page'      => $custom_post_type,
        'context'   => 'normal',
        'priority'  => 'high'
    );
}

/**
 * Creates Custom Post type for notice
 *
 * @return void
 */
function create_custom_post_type_notice()
{

    $labels = array(
        'name' => _x('Notices', 'Post Type General Name', 'wushka'),
        'singular_name' => _x('Notice', 'Post Type Singular Name', 'wushka'),
        'menu_name' => _x('Notices', 'Admin Menu text', 'wushka'),
        'name_admin_bar' => _x('Notice', 'Add New on Toolbar', 'wushka'),
        'archives' => __('Notice', 'wushka'),
        'attributes' => __('Notice', 'wushka'),
        'parent_item_colon' => __('Notice', 'wushka'),
        'all_items' => __('All Notices', 'wushka'),
        'add_new_item' => __('Add New Notice', 'wushka'),
        'add_new' => __('Add New', 'wushka'),
        'new_item' => __('New Notice', 'wushka'),
        'edit_item' => __('Edit Notice', 'wushka'),
        'update_item' => __('Update Notice', 'wushka'),
        'view_item' => __('View Notice', 'wushka'),
        'view_items' => __('View Notices', 'wushka'),
        'search_items' => __('Search Notice', 'wushka'),
        'not_found' => __('Not found', 'wushka'),
        'not_found_in_trash' => __('Not found in Trash', 'wushka'),
        'featured_image' => __('Featured Image', 'wushka'),
        'set_featured_image' => __('Set featured image', 'wushka'),
        'remove_featured_image' => __('Remove featured image', 'wushka'),
        'use_featured_image' => __('Use as featured image', 'wushka'),
        'insert_into_item' => __('Insert into Notice', 'wushka'),
        'uploaded_to_this_item' => __('Uploaded to this Notice', 'wushka'),
        'items_list' => __('Notices list', 'wushka'),
        'items_list_navigation' => __('Notices list navigation', 'wushka'),
        'filter_items_list' => __('Filter Notices list', 'wushka'),
    );

    $args = array(
        'label' => __('Notice', 'wushka'),
        'description' => __('', 'wushka'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-format-gallery',
        'supports' => array('title'),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_admin_bar' => false,
        'can_export' => true,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    $helper = helper_custom_post_type_notice();
    $post_type = $helper['post_type'];

    register_post_type($post_type, $args);
}
add_action('init', 'create_custom_post_type_notice', 0);

/* Logics for all template goes here */

/**
 * Create notice_cookie with encrypted date value
 *
 * @return string
 */
add_action("wp_ajax_set_notice_cookie", "wushka_set_notice_cookie");
function wushka_set_notice_cookie()
{
    // Check for nonce security      
    if (!wp_verify_nonce($_POST['nonce'], 'notice_cookie')) {
        die('Busted!');
    }
    $date_encrypted = encrypt_decrypt('encrypt', date('Y-m-d'));

    $template = '';
    if (isset($_POST['TID']) && !empty($_POST['TID'])) {
        $template = encrypt_decrypt('decrypt', sanitize_text_field($_POST['TID']));
    }

    $cookie_name = 'wushka_notice';

    //Logics for template 2
    if ($template == 2) {
        set_current_user_demo_notice_meta();
        $cookie_name = 'wushka_demo_notice';
    }

    if ($template == 3) {
        set_current_user_subscribed_notice_meta();
        $cookie_name = 'wushka_subscribed_notice';
    }

    /* begin - MSEL-116 */

    if ($template == 4) {
        set_current_user_registration_notice_meta();
        $cookie_name = 'wushka_registration_notice';
    }

    /* end - MSEL-116 */

    if ($template == 5) {
        set_current_user_default_notice_meta();
        $cookie_name = 'wushka_default_notice';
    }



    setcookie($cookie_name, $date_encrypted, time() + 86400, '/', Null, true);
    wp_die('success');
}



/* Template 1 logics */
/**
 * Update user meta with wp_wushka_rollover to true
 *
 * @return string
 */
add_action("wp_ajax_set_rollover_complete", "wushka_set_rollover_complete");
function wushka_set_rollover_complete()
{
    // Check for nonce security      
    if (!wp_verify_nonce($_POST['nonce'], 'rollover_complete')) {
        die('Busted!');
    }

    // Set rollover complete to user meta
    if (empty(get_current_user_rollover_meta())) {
        set_current_user_rollover_meta();
    }
    wp_die('success');
}


/**
 * Get current user meta value of wp_wushka_rollover
 *
 * @return array
 */
function get_current_user_rollover_meta()
{
    $user_id = get_current_user_id();
    $user_meta = get_user_meta($user_id, 'wp_wushka_rollover', true);
    return $user_meta;
}

/**
 * Add/Update user meta to complete rollover
 *
 * @return void
 */
function set_current_user_rollover_meta()
{
    $user_id = get_current_user_id();
    $key = 'wp_wushka_rollover';
    $value = true;
    error_log('Rollover completed for user: ' . $user_id);
    if (get_current_user_rollover_meta() == '') {
        add_user_meta($user_id, $key, $value);
    } else {
        update_user_meta($user_id, $key, $value);
    }
    update_user_meta($user_id, 'wushka_rollover_time',date('Y-m-d H:i:s'));
}


/* Template 2 Logics */
/**
 * Add/Update user meta on demo notice closed
 *
 * @return void
 */
function set_current_user_demo_notice_meta()
{
    $user_id = get_current_user_id();
    $key = 'wp_wushka_demo_notice';

    $now = current_time('timestamp');
    $value = maybe_serialize([$now]);
    error_log('Demo Notice closed for user: ' . $user_id);

    $user_meta = get_user_meta($user_id, $key, true);
    if ($user_meta == '') {
        add_user_meta($user_id, $key, $value);
    } else {
        $unserialized_value = maybe_unserialize($user_meta);
        $unserialized_value[] = $now;
        $value = maybe_serialize($unserialized_value);
        update_user_meta($user_id, $key, $value);
    }
}

function set_current_user_subscribed_notice_meta()
{
    $user_id = get_current_user_id();
    $key = 'wp_wushka_subscribed_notice';

    $now = current_time('timestamp');
    $value = maybe_serialize([$now]);
    error_log('Subscribed Notice closed for user: ' . $user_id);

    $user_meta = get_user_meta($user_id, $key, true);
    if ($user_meta == '') {
        add_user_meta($user_id, $key, $value);
    } else {
        $unserialized_value = maybe_unserialize($user_meta);
        $unserialized_value[] = $now;
        $value = maybe_serialize($unserialized_value);
        update_user_meta($user_id, $key, $value);
    }
}


/* begin - MSEL-116 */

function set_current_user_registration_notice_meta()
{
    $user_id = get_current_user_id();
    $key = 'wushka_registration_notice';

    $now = current_time('timestamp');
    $value = maybe_serialize([$now]);
    error_log('Subscribed Notice closed for user: ' . $user_id);

    $user_meta = get_user_meta($user_id, $key, true);
    if ($user_meta == '') {
        add_user_meta($user_id, $key, $value);
    } else {
        $unserialized_value = maybe_unserialize($user_meta);
        $unserialized_value[] = $now;
        $value = maybe_serialize($unserialized_value);
        update_user_meta($user_id, $key, $value);
    }
}

/* end - MSEL-116 */


function set_current_user_default_notice_meta()
{
    $user_id = get_current_user_id();
    $key = 'wushka_default_notice';

    $now = current_time('timestamp');
    $value = maybe_serialize([$now]);
    error_log('Subscribed Notice closed for user: ' . $user_id);

    $user_meta = get_user_meta($user_id, $key, true);
    if ($user_meta == '') {
        add_user_meta($user_id, $key, $value);
    } else {
        $unserialized_value = maybe_unserialize($user_meta);
        $unserialized_value[] = $now;
        $value = maybe_serialize($unserialized_value);
        update_user_meta($user_id, $key, $value);
    }
}
