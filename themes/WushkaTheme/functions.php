<?php


// Comment out the autoload php as we suspect the composer libraries specified in composer.json are not required anymore
require 'vendor/autoload.php';

global $wushka_theme_db_version;
$wushka_theme_db_version = "1.0";

//Include Files
include_once 'functions/helpers.php';
include_once 'functions/wushka_enqueue.php';
include_once 'functions/wushka_ajax.php';
//include_once 'app/Controllers/AzureAuth.php';

define('LICENCE_WDT', "Wushka Decodable Teacher");
define('SIS_REST_API_ENABLED', "false");
define('OPEN_HOUSE_CUSTOMER', "ohc");
// Remove type text/javascript/css from script and styles
add_action('after_setup_theme', function () {
    add_theme_support('html5', ['script', 'style']);
});


function wushka_page_header($s_page_title = 'Insert Page Title Here', $s_glyph_class = 'user')
{

    $s_title = trim(ucfirst($s_page_title));
    $s_glyph = 'glyphicon-' . trim(strtolower($s_glyph_class));

    $a_html = array();

    $a_html[] = trim(ucfirst($s_title));
    $a_html[] = '<i class="glyphicon ' . trim($s_glyph) . '"></i>';

    echo implode('', $a_html);

    return TRUE;
}

function wushka_setup_theme()
{

    global $wushka_theme_db_version;
    if (get_site_option('wushka_theme_db_version') != $wushka_theme_db_version) {
        wushka_theme_db_install();
    }
}

add_action('after_switch_theme', 'wushka_setup_theme');

function wushka_theme_db_install()
{

    global $wpdb;
    global $wushka_theme_db_version;
    $installed_ver = get_option("wushka_theme_db_version");

    if ($installed_ver != $wushka_theme_db_version) {
        $table_name = $wpdb->prefix . "classes";

        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            name VARCHAR(255),
            year VARCHAR(255),
            students int(2) DEFAULT '0',
            user_id int(11),
            school_id int(11),
            archived boolean NOT NULL DEFAULT '0',
            created_date datetime,
            archived_date datetime,
            archived_by int(11),
            released_control boolean NOT NULL DEFAULT '0',
            licence_product VARCHAR(100) NOT NULL DEFAULT '0',
            PRIMARY KEY id (id)
        );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_name_two = $wpdb->prefix . "classes_teachers";

        $sql_two = "CREATE TABLE $table_name_two (
            id int(11) NOT NULL AUTO_INCREMENT,
            class_id int(11),
            school_id int(11),
            teacher_id int(11),
            PRIMARY KEY id (id)
        );";

        $s_name   = $wpdb->prefix . "usermeta_temp";
        $q_create = "CREATE TABLE $s_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            id_hash VARCHAR(255),
            show_temp_pwd VARCHAR(255),
            PRIMARY KEY id (id)
        );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($q_create);

        update_option("wushka_theme_db_version", $wushka_theme_db_version);
        wushka_update_school_meta();
        wushka_school_hash_to_tax_options();
        wushka_theme_upgrade_data();
    }
}

function wushka_theme_upgrade_data($reset = FALSE)
{
    global $wpdb;

    error_log('----- Attach School Terms to Student Users -----');
    //Update existing students to have a school taxonomy term
    $s_query = 'SELECT um.user_id, c.id, c.school_id FROM ' . $wpdb->prefix . 'usermeta um ' .
        'LEFT JOIN ' . $wpdb->prefix . 'classes c ON c.id = um.meta_value ' .
        'WHERE um.meta_key = %s AND um.meta_value != %s AND c.school_id != "" ' .
        ' AND c.school_id != %s ORDER BY user_id ASC LIMIT 0, 1000;';

    $a_results = $wpdb->get_results(
        $wpdb->prepare($s_query, 'class', 'false', '0')
    );

    $i_updated = 0;
    $i_exists  = 0;
    $i_fail    = 0;

    if (isset($a_results) && !empty($a_results)) {
        $i_results = count($a_results);
        $i_ten     = round($i_results / 10);
        $i_count   = 0;
        $i_times   = 1;
        error_log('Found : ' . count($a_results) . ' Class Students');
        foreach ($a_results as $i_id => $o_row) {
            //For Testing tracking
            $i_count++;
            if ($i_count % $i_ten == 0) {
                $i_percent = 10 * $i_times;
                error_log('Progress: ' . $i_percent . '% Complete');
                $i_times++;
            }

            $i_user   = (int)$o_row->user_id;
            $i_class  = (int)$o_row->id;
            $i_school = (int)$o_row->school_id;

            if (isset($reset) && !empty($reset) && $reset == 'reset') {
                wp_delete_object_term_relationships($i_user, 'school');
            }

            if (is_object_in_term($i_user, 'school', $i_school)) {
                $i_exists++;
                continue;
            }

            //Give User School Term
            $b_inserted = $wpdb->insert(
                $wpdb->term_relationships,
                array(
                    'object_id'        => $i_user,
                    'term_taxonomy_id' => $i_school
                ),
                array(
                    '%d',
                    '%d'
                )
            );

            clean_object_term_cache($i_user, 'school');

            if (!$b_inserted) {
                $i_fail++;
            } else {
                $i_updated++;
            }

            unset($i_user, $i_class, $i_school);
        }
    }
    error_log('------- Process Completed -------');
    error_log('# Users already linked to School Term: ' . $i_exists);
    error_log('# users school term update successful: ' . $i_updated);
    error_log('# users school term update error:      ' . $i_fail);
    error_log('------- Function Complete -------');

    return TRUE;
}

function clean_school_taxonomy_list()
{
    global $wpdb;

    error_log('----- Get All school IDs attached to active classes -----');
    //Update existing students to have a school taxonomy term
    $s_query = 'SELECT school_id FROM ' . $wpdb->prefix . 'classes ' .
        'WHERE school_id != "" AND school_id != %s ' .
        'GROUP BY school_id ORDER BY school_id ASC;';

    $a_results = $wpdb->get_results(
        $wpdb->prepare($s_query, '0')
    );

    $i_updated   = 0;
    $i_exists    = 0;
    $i_duplicate = 0;
    $i_fail      = 0;
    $i_removed   = 0;

    if (isset($a_results) && !empty($a_results)) {
        $i_results = count($a_results);
        $i_ten     = round($i_results / 10);
        $i_count   = 0;
        $i_times   = 1;
        error_log('Found : ' . count($a_results) . ' Active Schools');
        foreach ($a_results as $i_id => $o_row) {
            //For Testing tracking
            $i_count++;
            if ($i_count % $i_ten == 0) {
                $i_percent = 10 * $i_times;
                error_log('Progress: ' . $i_percent . '% Complete');
                $i_times++;
            }

            $i_term = (int)$o_row->school_id;

            //Check if Original Still Exists
            $o_school = get_term_by('id', $i_term, 'school');
            if (!$o_school) {
                error_log('----- CRITICAL ERROR -----');
                error_log('No School Term found with term_id = ' . $i_term);
                error_log('--------------------------');
                $i_exists++;
                continue;
            }

            //Check if Duplicate has been created accidentally
            $o_copy = get_term_by('name', (string)$i_term, 'school');
            if (!$o_copy) {
                $i_updated++;
            } else {
                error_log('school_id (' . $i_term . ') - Duplicate Term Found: Duplicate Name:' . $o_copy->name . ' ID:' . $o_copy->term_taxonomy_id);
                $i_duplicate++;

                //Remove Duplicate Term
                $x_remove = wp_delete_term((int)$o_copy->term_taxonomy_id, 'school');
                if (is_wp_error($x_remove)) {
                    $i_fail++;
                } else {
                    $i_removed++;
                }
            }
        }
    }
    error_log('------- Process Completed -------');
    error_log('# School IDs that do NOT have a school taxonomy term: ' . $i_exists);
    error_log('# Safe School Terms: ' . $i_updated);
    error_log('# Duplicate School Terms Found:      ' . $i_duplicate);
    error_log('# Duplicate remove success:      ' . $i_removed);
    error_log('# Duplicate remove fail:      ' . $i_fail);
    error_log('------- Function Complete -------');

    return TRUE;
}


//Take Any School User with a show_temp_pwd metafield
//Add meta to separate table and remove meta field
function wushka_update_school_meta()
{
    global $wpdb;

    // updated Feb 2019 to prevent count_total performing slow query
    $a_args    = array(
        'role' => 'school',
        'count_total'   => false
    );
    $i_updated = 0;
    $i_error   = 0;
    $o_results = new WP_User_Query($a_args);  // args updated for slow query
    if ($o_results->get_total() > 0) {
        $a_users = $o_results->get_results();
        if (isset($a_users) && !empty($a_users)) {
            foreach ($a_users as $idx => $o_user) {
                if (isset($o_user->show_temp_pwd) && !empty($o_user->show_temp_pwd)) {
                    $x_insert = $wpdb->insert(
                        $wpdb->prefix . 'usermeta_temp',
                        array(
                            'id_hash'       => $o_user->id_hash,
                            'show_temp_pwd' => $o_user->show_temp_pwd
                        ),
                        array(
                            '%s',
                            '%s'
                        )
                    );
                    if ($x_insert === FALSE) {
                        error_log('FAILED to insert show_temp_pwd for school user #' . $o_user->ID);
                        $i_error++;
                    } else {
                        $i_updated++;
                        delete_user_meta($o_user->ID, 'show_temp_pwd');
                    }
                }
            }
        }
        error_log('-----------------------------------');
        error_log('School Users Updated: ' . $i_updated);
        error_log('School Users Failed to Update: ' . $i_error);
        error_log('-----------------------------------');
    }

    return TRUE;
}

// Add role class to body
function add_role_to_body($classes)
{

    global $current_user;
    $user_role = array_shift($current_user->roles);

    $classes[] = 'role-' . $user_role;

    return $classes;
}

add_filter('body_class', 'add_role_to_body');
//add_filter('admin_body_class', 'add_role_to_body');

//Register Footer Menus
function register_custom_menus()
{
    register_nav_menus(
        array(
            'footer-nav-1'      => __('Footer Nav 1'),
            'footer-nav-2'      => __('Footer Nav 2'),
            'footer-nav-3'      => __('Footer Nav 3'),
            'footer-nav-4'      => __('Footer Nav 4'),
            'footer-nav-social' => __('Footer Nav Social'),
        )
    );
}

add_action('init', 'register_custom_menus');

// Upload SVG files
function cc_mime_types($mimes)
{
    $mimes['svg'] = 'image/svg+xml';

    return $mimes;
}

add_filter('upload_mimes', 'cc_mime_types');

// user registration / update has id
function wushka_post_register_update($user_id)
{
    //$user = get_user_by('id', $user_id);
    error_log('profile:' . $user_id . " created - hashing");

    $time = md5(time());

    // check this has does not already exist, it must be unique
    $algo = 'sha256';
    while (TRUE) {
        $id_hash    = hash($algo, $user_id . $time);
        // updated Feb 2019 to prevent count_total performing slow query
        $user_query = new WP_User_Query(  // args updated for slow query
            array(
                'count_total'   => false,
                'meta_key'   => 'id_hash',
                'meta_value' => $id_hash
            )
        );
        if (empty($user_query->results)) {
            break;
        } else {
            $time = md5(rand(1, 999999999999));
        }
    }
    add_user_meta($user_id, 'id_hash', $id_hash);
    $user_meta = get_userdata($user_id);
    $user_roles = $user_meta->roles;
    if (in_array('teacher', $user_roles)) {
        $active = get_user_meta($user_id, 'active', true);
        if (empty($active)) {
            add_user_meta($current_user->ID, 'active', 1);
        }
    }
}

add_action('user_register', 'wushka_post_register_update', 10, 1);

function get_user_by_hash($id_hash = 0)
{

    if (empty($id_hash)) {
        return FALSE;
    }

    // Query for users based on the meta data
    // updated Feb 2019 to prevent count_total performing slow query
    $user_query = new WP_User_Query(  // args updated for slow query
        array(
            'count_total'   => false,
            'meta_key'   => 'id_hash',
            'meta_value' => $id_hash
        )
    );

    if (!empty($user_query->results)) {
        // Get the results from the query, returning the first user
        $users = $user_query->get_results();

        return $users[0];
    } else {
        return FALSE;
    }
}

function wushka_login_url($url)
{
    return home_url();
}
// add_filter('login_headerurl', '/');
add_filter('login_headerurl', 'wushka_login_url');

function login_redirect($redirect_to = NULL, $request, $user)
{
    if (isset($user->roles) && is_array($user->roles)) {
        //check for admins
        if (in_array('administrator', $user->roles) || in_array('subadmin', $user->roles) || in_array('marketingmanager', $user->roles) || in_array('modernstar_employee', $user->roles)) {
            // redirect them to the default place
            return $redirect_to;
        } else {
            return home_url('/');
        }
    }
    return home_url('/');
}

add_filter('login_redirect', 'login_redirect', 10, 3);

//Register Teacher, Student and Parent Roles:
add_role(
    // Role:
    'teacher',
    // Display_name:
    __('Teacher'),
    // Capabilities:
    array(
        'read' => TRUE
        // Use TRUE to allow this capability or FALSE to explicitly deny
    )
);
add_role(
    // Role:
    'student',
    // Display_name:
    __('Student'),
    // Capabilities:
    array(
        'read' => TRUE
    )
);

add_role(
    // Role:
    OPEN_HOUSE_CUSTOMER,
    // Display_name:
    __('Open House Customer'),
    // Capabilities:
    array(
        'read' => TRUE
    )
);

add_role(
    // Role:
    'home-user',
    // Display_name:
    __('Home User'),
    // Capabilities:
    array(
        'read' => TRUE
    )
);

add_role(
    // Role:
    'parent',
    // Display_name:
    __('Parent'),
    // Capabilities:
    array(
        'read' => TRUE
    )
);

add_role(
    // Role:
    'school',
    // Display_name:
    __('School'),
    // Capabilities:
    array(
        'read' => TRUE
    )
);
add_role(
    // Role:
    'school_admin',
    // Display_name:
    __('School Admin'),
    // Capabilities:
    array(
        'read' => TRUE
    )
);
add_role(
    // Role:
    'bdm',
    // Display_name:
    __('Business Development Manager'),
    // Capabilities:
    array(
        'read' => TRUE
    )
);

//Page Slug Body Class
function add_slug_body_class($classes)
{
    global $post;
    if (isset($post)) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    }

    return $classes;
}

add_filter('body_class', 'add_slug_body_class');

// remove junk from head
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'noindex', 1);

add_filter('wp_headers', 'lessonzone_remove_x_pingback');

function lessonzone_remove_x_pingback($headers)
{
    unset($headers['X-Pingback']);

    return $headers;
}

load_theme_textdomain('lessonzone', get_template_directory() . '/languages');

if (!function_exists('optionsframework_init')) {
    define('OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/');
    require_once dirname(__FILE__) . '/inc/options-framework.php';
}

register_nav_menus(array('top_nav' => __('Top Navigation', 'lessonzone')));
register_nav_menus(array('footer_nav' => __('Footer Navigation', 'lessonzone')));

register_sidebar(array(
    'id'            => 'wushka-sidebar-left',
    'name'          => 'sidebar-left',
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '<h4>',
    'after_title'   => '</h4>'
));
register_sidebar(array(
    'id'            => 'wushka-sidebar-right',
    'name'          => 'sidebar-right',
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '<h4>',
    'after_title'   => '</h4>'
));

add_theme_support('post-thumbnails');
add_theme_support('custom-background', array(
    'default-color' => 'f2f2f2',
));

function lessonzone_footer_scripts()
{
    wp_enqueue_script('lzk', get_template_directory_uri() . '/js/jquery.lzk.js', array('jquery'), NULL, TRUE);
    //Opens and closes confirmation window for subscription cancel
    if (is_page('my-account')) {
?>
        <style>
            .account-popup.sub-cancel {
                display: none;
                opacity: 0;
            }
        </style>
        <script>
            jQuery(document).ready(function($) {
                //Open the cancel confirmation window
                $(document).on('click', 'input[type="button"][id^="cancel-sub-"]', function() {
                    var window_id = $(this).attr('id').replace('cancel-sub-', '').trim();
                    $('#popup-cancel-' + window_id).show().fadeTo(200, 1);
                });
                //Close confirmation Window
                $(document).on('click', '.account-popup.close-window', function() {
                    $(this).parents('.account-popup.sub-cancel').fadeTo(200, 0).hide();
                });
                //Enable swiping...
                $(".carousel-inner").swipe({
                    //Generic swipe handler for all directions
                    swipeLeft: function(event, direction, distance, duration, fingerCount) {
                        $(this).parent().carousel('prev');
                    },
                    swipeRight: function() {
                        $(this).parent().carousel('next');
                    }
                });
            });
        </script>
    <?php
    }
}

add_action('wp_footer', 'lessonzone_footer_scripts');

// template actions
function lessonzone_after_post_title()
{
    do_action('lessonzone_after_post_title');
}

function lessonzone_after_post_meta()
{
    do_action('lessonzone_after_post_meta');
}

function lessonzone_after_post_content()
{
    do_action('lessonzone_after_post_content');
}

function lessonzone_after_post()
{
    do_action('lessonzone_after_post');
}

function lessonzone_after_post_comments()
{
    do_action('lessonzone_after_post_comments');
}

function wushka_ebook_hook()
{
    do_action('wushka_ebook_hook');
}

//PdfStamper Hook Function for LMR
function stampiT_studentsList_hook()
{
    //do_action('stampiT_studentsList_hook');
}

//Post Attachment Hook Function
function lzPA_singlePost_hook()
{
    do_action('lzPA_singlePost_hook');
}

//Post Attachment Hook Function
function lzPA_langPost_hook()
{
    do_action('lzPA_langPost_hook');
}

//Loads the Required URL for Ereader to run
function wushka_load_sample_reader_urls($i_postid = NULL)
{
    if (!isset($i_postid) || empty($i_postid)) {
        return '#';
    }
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    if (is_plugin_active('lessonZone-postAttachment/lzPA-postAttachment.php')) {
        $x_return = apply_filters('wushka_lzpa_sample_books', $i_postid);

        return isset($x_return) ? $x_return : '#';
    }

    return '#';
}

function lessonzone_query($query)
{
    if (!is_admin() && $query->is_main_query()) {
        if ($query->is_search) {
            // only include post and ebook in search results
            $query->set('post_type', array(
                'post',
                'ebook'
            ));
        }
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
    }

    return $query;
}

add_action('pre_get_posts', 'lessonzone_query');

function new_excerpt_more($more)
{
    global $post;

    //return '<a class="moretag" href="' . get_permalink($post->ID) . '"> ...More</a>';
    return '...';
}

add_filter('excerpt_more', 'new_excerpt_more');

// Retrieve particular content by title
function show_post($path)
{
    $post    = get_page_by_title($path);
    $content = apply_filters('the_content', $post->post_content);
    echo $content;
}

function get_time_difference($origpostdate)
{
    //   echo $origpostdate;
    $current  = new DateTime(date('Y-m-d'));
    $postDate = new DateTime($origpostdate);

    //   echo $datetime2;
    return $postDate->diff($current)->format("%a");
}

function authorize_user_login($o_user, $pass)
{

    // echo "<pre>";
    // print_r($o_user);exit;

    $referrer = home_url('/login/');

    if (!is_wp_error($o_user) && isset($o_user->data->user_email)) {
        $email  = $o_user->data->user_email;
        $domain = substr(strrchr($email, '@'), 1);

        if (strpos($domain, 'nsw') !== false) {
            wp_redirect(add_query_arg('login', 'nsw_login_disabled', $referrer));
            exit;
        } 
    }


    if (in_array('modernstar_employee', (array) $o_user->roles, true)) {
        return new WP_Error(
            'authentication_failed',
            __('Login is disabled for your account. Please contact the administrator.')
        );
    }

    if (!wp_check_password($pass, $o_user->data->user_pass, $o_user->ID)) {
        return $o_user;
    }

    if (is_wp_error($o_user) || user_can($o_user, 'administrator')) {
        return $o_user;
    }

    if (!isset($_SESSION)) {
        session_start();
    }

    $o_user = wushka_valid_login($o_user);
    return $o_user;
}

function wushka_valid_login($o_user, $sso_login = NULL)
{


    $b_valid = school_has_active_sub($o_user);

    //If User is Valid, Login
    if ($b_valid) {
        //Store Login School Event
        wushka_user_login_events($o_user, 'logged in', $sso_login);
        update_user_meta($o_user->ID, 'last_login', current_time('mysql'));
        if (isset($_SESSION['temp_licencing'])) {
            $_SESSION['licencing'] = $_SESSION['temp_licencing'];
            unset($_SESSION['temp_licencing']);
            error_log('licencing set to ' . print_r($_SESSION['licencing'], true));
        }
    } else {
        //Return Invalid Login Error to login page form
        $o_user = new WP_Error('inactive_sub', '<strong>ERROR</strong>: The subscription your account is linked to is not active');
    }

    return $o_user;
}

add_action('wp_authenticate_user', 'authorize_user_login', 10, 2);

function user_logout_callback()
{
    error_log('logging out user and clearing session');
    // remove sesssion info for parent
    if (current_user_can('parent') && isset($_SESSION['parent_login'])) {
        unset($_SESSION['parent_login']);
    }
    if (isset($_SESSION['dashboard_selection'])) {
        error_log('unsetting dashboard_selection');
        unset($_SESSION['dashboard_selection']);
    }
    if (isset($_SESSION['licencing'])) {
        error_log('unsetting licencing');
        unset($_SESSION['licencing']);
    }
    if (isset($_SESSION['wushka_decodable_teacher'])) {
        unset($_SESSION['wushka_decodable_teacher']);
    }
    $current_user = wp_get_current_user();
    //Store Login School Event
    wushka_user_login_events($current_user, 'logged out');

    return TRUE;
}

add_action('clear_auth_cookie', 'user_logout_callback');

function check_pwd($pass, $userlogin)
{
    $user = get_user_by('login', $userlogin);

    if (wp_check_password($pass, $user->data->user_pass, $user->ID)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function check_license_limit($user_id)
{
    $subscriptions = WC_Subscriptions_Manager::get_users_subscriptions();
    $max_allowance = 1;

    if (isset($subscriptions) && count($subscriptions) > 0) {
        foreach ($subscriptions as $subscription) {
            if ($subscription['status'] == 'active') {
                $subscription_key = WC_Subscriptions_Manager::get_subscription_key($subscription['order_id'], $subscription['product_id']);
                if (isset($subscription_key) && strlen($subscription_key) > 0) {
                    $product       = get_product($subscription['product_id']);
                    $license_limit = $product->get_attribute('license_limit');
                    $max_allowance = isset($license_limit) ? $license_limit : 0;
                }
            }
        }
    }

    // updated Feb 2019 to prevent count_total performing slow query
    $args = array(
        'role'       => 'student',
        'count_total'   => false,
        'meta_query' => array(
            'relation' => 'AND',
            0          => array(
                'key'   => 'parent_id',
                'value' => $user_id
            ),
            1          => array(
                'key'   => 'active',
                'value' => 1
            )
        )
    );

    $total      = 0;
    $user_query = new WP_User_Query($args);  // args updated for slow query

    // User Loop
    if (!empty($user_query->results)) {
        foreach ($user_query->results as $idx => $count) {
            $total++;
        }
    }

    error_log('license max:' . $max_allowance);
    error_log('current usage:' . $total);

    return ($max_allowance - $total);
}

function check_active_children($user_id = NULL)
{
    if (!isset($user_id)) {
        return 0;
    }
    // updated Feb 2019 to prevent count_total performing slow query
    $args       = array(
        'role'       => 'student',
        'count_total'   => false,
        'meta_query' => array(
            'relation' => 'AND',
            0          => array(
                'key'   => 'parent_id',
                'value' => $user_id
            ),
            1          => array(
                'key'   => 'active',
                'value' => 1
            )
        )
    );
    $total      = 0;
    $user_query = new WP_User_Query($args);  // args updated for slow query
    // User Loop
    if (!empty($user_query->results)) {
        foreach ($user_query->results as $idx => $count) {
            $total++;
        }
    }

    return $total;
}

function generateRandomString($length)
{
    return substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXTZ"), 0, $length);
}

/* remove special character and space from string */

function clean($string)
{
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9 -]+/', '', $string);
    $string = preg_replace('/\s/', '-', $string);

    return trim($string, '-');
}

/* remove special character from string */

function cleanOnlySpecialChar($string)
{

    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9 -]+/', '', $string);

    return trim($string);
}

/* get reader analytics data for class view page on LZ */

function reader_analytics($users = array(), $tax = NULL, $b_current = FALSE)
{
    global $wpdb;
    error_log('Running Reader Analytics Query');

    if (empty($users)) {
        error_log('Reader Analytics: Error - User Array is Empty');

        return array();
    }

    if (isset($tax) && $tax !== 'overall') {
        error_log('Found Taxonomy: ' . $tax);
        $o_term = get_term_by('slug', $tax, 'reading-level');
        $tax    = $o_term->term_taxonomy_id;
        error_log('Get Taxonomy reading stats for: ' . $tax);
    }

    $a_records = array();

    $a_params   = array();
    $user_prepare = array();

    foreach ($users as $idx => $user) {
        $a_params[]   = $user->ID;
        $user_prepare[] = '%d';

        if (isset($user->child_link_id) && !empty($user->child_link_id)) {
            $a_params[]   = $user->child_link_id;
            $user_prepare[] = '%d';
        }
        if (isset($user->student_link_id) && !empty($user->student_link_id)) {
            $a_params[]   = $user->student_link_id;
            $user_prepare[] = '%d';
        }

        $a_records[$user->ID] = array(
            'overall_books' => 0,
            'fiction'       => 0,
            'narrated'      => 0,
            'no_of_access'  => 0,
            'duration'      => 0,
            'weeks'         => array(),
            'book_ids'      => array()
        );
    }

    $a_params[] = 1;

    $s_prep = implode(', ', $user_prepare);

    $s_query = 'SELECT *, WEEK(created) as week_no FROM ' . $wpdb->prefix . 'lessonzone_reading_analytics_reading_instance WHERE user_id IN (' . $s_prep . ') AND completed = %d';

    if (isset($tax)) {
        $a_params[] = $tax;
        $s_query .= ' AND level = %d';
    }

    if ($b_current) {
        $tCurrent = date('Y');

        $s_query .= ' AND created >= %s';
        $a_params[] = date('Y-m-d G:i:s', strtotime('01 January ' . $tCurrent));
        error_log('GET CURRENT YEAR RA');
        error_log($s_query);
        error_log(print_r($a_params, true));
    }

    $a_results = $wpdb->get_results(
        $wpdb->prepare($s_query, $a_params)
    );

    foreach ($a_results as $idx => $o_row) {
        foreach ($users as $idx => $user) {
            if ($o_row->user_id != $user->ID) {
                if (isset($user->child_link_id) && $o_row->user_id == $user->child_link_id) {
                    //Do nothing
                } else if (isset($user->student_link_id) && $o_row->user_id == $user->student_link_id) {
                    //Do Nothing
                } else {
                    continue;
                }
            }

            $a_records[$user->ID]['overall_books'] += $o_row->completed;
            $a_records[$user->ID]['fiction'] += $o_row->fiction;
            $a_records[$user->ID]['narrated'] += $o_row->narrated;
            $a_records[$user->ID]['no_of_access'] += 1;
            $a_records[$user->ID]['duration'] += $o_row->duration;
            $a_records[$user->ID]['weeks'][]    = $o_row->week_no;
            $a_records[$user->ID]['book_ids'][] = $o_row->essis_resource_id;
        }
    }

    if (empty($a_records)) {
        error_log('Reading Analytics: Warning - No Records Found');
    }

    return $a_records;
}

/* Prepare array regarding reading history on child history page */

function child_reading_history_analytics($user)
{
    global $wpdb;
    $reading_history = array();
    $analytics       = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "lessonzone_reading_analytics_reading_instance  WHERE user_id = " . $user);
    //print_r($analytics);

    foreach ($analytics as $idx => $book) {
        if (!isset($reading_history[$book->user_id]['book_ids'])) {
            $reading_history[$book->user_id]['book_ids'] = array();
        }

        if (in_array($book->essis_resource_id, array_keys($reading_history))) {
            $reading_history[$book->essis_resource_id]['times_read'] += 1;
            $reading_history[$book->essis_resource_id]['duration'] += $book->duration;
        } else {
            $reading_history[$book->essis_resource_id]['times_read'] = 1;
            $reading_history[$book->essis_resource_id]['duration']   = $book->duration;
            $reading_history[$book->essis_resource_id]['created']    = $book->created;
        }
        $reading_history[$book->essis_resource_id]['read_id']      = $book->read_id;
        $reading_history[$book->essis_resource_id]['user_id']      = $book->user_id;
        $reading_history[$book->essis_resource_id]['last_created'] = $book->created;
        $reading_history[$book->essis_resource_id]['completed']    = $book->completed;
        $reading_history[$book->essis_resource_id]['narrated']     = $book->narrated;
        $reading_history[$book->essis_resource_id]['form_factor']  = $book->form_factor;
        if ($book->completed) {
            array_push($reading_history[$book->user_id]['book_ids'], $book->essis_resource_id);
        }
    }

    return $reading_history;
}

function get_reading_groups_list($user)
{
    if (user_can($user, "teacher")) {
        $get_reading_groups     = get_user_meta($user->ID, 'reading_group', TRUE);
        $reading_group_name[''] = "";
        if ($get_reading_groups) {
            foreach ($get_reading_groups as $idx => $group) {
                // $reading_groups[$group['id']] = $group['value'];
                $group['value']                           = ucwords($group['value']);
                $reading_group_name[$group['value']][0] = $group['value'];
            }
        }
    }

    return $reading_group_name;
}

function has_valid_subscription()
{

    global $wp_user;
    $hasAccess = FALSE;

    if (is_user_logged_in()) {
        $wp_user = wp_get_current_user();

        if (user_can($wp_user, "administrator") || user_can($wp_user, "teacher")) {
            $hasAccess = TRUE;
        }

        if (!$hasAccess) {
            $hasAccess = school_has_active_sub($wp_user);
        }

        // $userID = $wp_user->ID;

        // $hasClass  = get_user_meta($userID, 'class', TRUE);
        // $hasParent = get_user_meta($userID, 'parent_id', TRUE);
        // $hasAccess = WC_Subscriptions_Manager::user_has_subscription($userID, '', 'active');

        // if( ! $hasAccess ) {
        //     if( (user_can($wp_user, "student") && $hasClass) || (user_can($wp_user, "student") && $hasParent && WC_Subscriptions_Manager::user_has_subscription($hasParent, '', 'active')) ) {
        //         $hasAccess = TRUE;
        //     }
        // }
    }

    return $hasAccess;
}

function wushka_ebook_resource($content)
{

    if (is_singular(array('ebook'))) {
        $access = has_valid_subscription() ? 'true' : 'false';
        //error_log('has access:' . $access);
        $content = '<div class="pdfStamp_singlePostResource">';
        $content .= '<input type="hidden" id="userAccountCheck" value="' . $access . '" />';
        $content .= '</div><!-- end pdfStamp single post resources -->';

        echo $content;
    }
}

add_action('wushka_ebook_hook', 'wushka_ebook_resource');

add_shortcode('wushka_login', 'wushka_login_shortcode');

function wushka_login_shortcode($atts)
{

    include('form-login.php');
}

add_shortcode('wushka_forgot', 'wushka_forgot_shortcode');

function wushka_forgot_shortcode($atts)
{

    include('form-lost-password.php');
}

add_shortcode('wushka_reset', 'wushka_reset_shortcode');

function wushka_reset_shortcode($atts)
{

    include('form-reset-password.php');
}

// School dashboard functions
//
// register ajax actions
add_action('wp_ajax_wushka_school_save_settings', 'wushka_ajax_school_save_settings');
add_action('wp_ajax_wushka_school_move_students', 'wushka_ajax_school_move_students');
add_action('wp_ajax_wushka_school_save_timezone', 'wushka_ajax_school_save_timezone');
add_action('wp_ajax_wushka_class_save', 'wushka_ajax_class_save');
add_action('wp_ajax_wushka_class_create', 'wushka_ajax_class_create');
add_action('wp_ajax_wushka_class_delete', 'wushka_ajax_class_delete');
add_action('wp_ajax_wushka_teacher_save', 'wushka_ajax_teacher_save');
add_action('wp_ajax_wushka_teacher_create', 'wushka_ajax_teacher_create');
add_action('wp_ajax_wushka_teacher_delete', 'wushka_ajax_teacher_delete');
add_action('wp_ajax_wushka_get_students', 'wushka_ajax_get_students');
add_action('wp_ajax_wushka_get_class_login', 'wushka_ajax_get_class_login');
add_action('wp_ajax_wushka_get_class_students', 'wushka_ajax_get_class_students');
add_action('wp_ajax_wushka_get_quiz_results', 'wushka_ajax_get_quiz_results');
add_action('wp_ajax_wushka_student_exists', 'wushka_student_exists');
// graphs
add_action('wp_ajax_wushka_class_graph', 'wushka_ajax_class_graph');
//Create api
add_action('wp_ajax_wushka_create_api_key', 'wushka_ajax_create_api_key');

function get_wushka_comp_strats()
{
    return array(
        0  => [
            'name'  => 'Making Connections',
            'label' => 'Making Connections'
        ],
        1  => [
            'name'  => 'Monitoring and Clarifying',
            'label' => 'Monitoring and Clarifying'
        ],
        2  => [
            'name'  => 'Making Predictions',
            'label' => 'Making Predictions'
        ],
        3  => [
            'name'  => 'Finding Main Ideas',
            'label' => 'Finding Main Ideas'
        ],
        4  => [
            'name'  => 'Summarising',
            'label' => 'Summarising'
        ],
        5  => [
            'name'  => 'Making Inferences',
            'label' => 'Making Inferences'
        ],
        6  => [
            'name'  => 'Visualising',
            'label' => 'Visualising'
        ],
        7  => [
            'name'  => 'Recognising Bias and Prejudice',
            'label' => 'Recognising Bias and Prejudice'
        ],
        8  => [
            'name'  => 'Compare and Contrast',
            'label' => 'Compare and Contrast'
        ],
        9  => [
            'name'  => 'Sequencing',
            'label' => 'Sequencing'
        ],
        10 => [
            'name'  => 'Recognising Cause and Effect',
            'label' => 'Recognising Cause and Effect'
        ],
        11 => [
            'name'  => 'Distinguishing Between Fact and Opinion',
            'label' => 'Distinguishing Between Fact and Opinion'
        ],
        12 => [
            'name'  => 'Questioning',
            'label' => 'Questioning'
        ]
    );
}

function get_wushka_text_types()
{
    return array(
        0  => [
            'name'  => 'Biography/Autobiography',
            'label' => 'Biography/Autobiography'
        ],
        1  => [
            'name'  => 'Biography',
            'label' => 'Biography'
        ],
        2  => [
            'name'  => 'Diary, Journal or Log',
            'label' => 'Diary, Journal or Log'
        ],
        3  => [
            'name'  => 'Explanation',
            'label' => 'Explanation'
        ],
        4  => [
            'name'  => 'Fantasy',
            'label' => 'Fantasy'
        ],
        5  => [
            'name'  => 'Fiction',
            'label' => 'Fiction'
        ],
        6  => [
            'name'  => 'Folk Tale',
            'label' => 'Folk Tale'
        ],
        7  => [
            'name'  => 'General Narrative',
            'label' => 'General Narrative'
        ],
        8  => [
            'name'  => 'Graphic Text',
            'label' => 'Graphic Text'
        ],
        9  => [
            'name'  => 'Historical Fiction',
            'label' => 'Historical Fiction'
        ],
        10 => [
            'name'  => 'Interview',
            'label' => 'Interview'
        ],
        11 => [
            'name'  => 'Email, Letter or Postcard',
            'label' => 'Email, Letter or Postcard'
        ],
        12 => [
            'name'  => 'Memoir',
            'label' => 'Memoir'
        ],
        13 => [
            'name'  => 'Mystery',
            'label' => 'Mystery'
        ],
        14 => [
            'name'  => 'Myth or Legend',
            'label' => 'Myth or Legend'
        ],
        15 => [
            'name'  => 'Narrative',
            'label' => 'Narrative'
        ],
        16 => [
            'name'  => 'Non-Fiction',
            'label' => 'Non-Fiction'
        ],
        17 => [
            'name'  => 'Persuasive Text',
            'label' => 'Persuasive Text'
        ],
        18 => [
            'name'  => 'Play',
            'label' => 'Play'
        ],
        19 => [
            'name'  => 'Poetry',
            'label' => 'Poetry'
        ],
        20 => [
            'name'  => 'Procedure',
            'label' => 'Procedure'
        ],
        21 => [
            'name'  => 'Realistic Fiction',
            'label' => 'Realistic Fiction'
        ],
        22 => [
            'name'  => 'Recount',
            'label' => 'Recount'
        ],
        23 => [
            'name'  => 'Report',
            'label' => 'Report'
        ],
        24 => [
            'name'  => 'Science Fiction',
            'label' => 'Science Fiction'
        ],
        25 => [
            'name'  => 'Tall Tale',
            'label' => 'Tall Tale'
        ],
        26 => [
            'name'  => 'Traditional Story',
            'label' => 'Traditional Story'
        ]
    );
}

function get_wushka_school_years()
{
    return array(
        array(
            'i' => 'year-0',
            'v' => 'Foundation',
            'c' => 1
        ),
        array(
            'i' => 'year-0-1',
            'v' => 'Foundation + 1',
            'c' => 1
        ),
        array(
            'i' => 'year-1',
            'v' => 'Year 1',
            'c' => 1
        ),
        array(
            'i' => 'year-1-2',
            'v' => 'Year 1 + 2',
            'c' => 1
        ),
        array(
            'i' => 'year-2',
            'v' => 'Year 2',
            'c' => 1
        ),
        array(
            'i' => 'year-2-3',
            'v' => 'Year 2 + 3',
            'c' => 1
        ),
        array(
            'i' => 'year-3',
            'v' => 'Year 3',
            'c' => 1
        ),
        array(
            'i' => 'year-3-4',
            'v' => 'Year 3 + 4',
            'c' => 1
        ),
        array(
            'i' => 'year-4',
            'v' => 'Year 4',
            'c' => 1
        ),
        array(
            'i' => 'year-4-5',
            'v' => 'Year 4 + 5',
            'c' => 1
        ),
        array(
            'i' => 'year-5',
            'v' => 'Year 5',
            'c' => 1
        ),
        array(
            'i' => 'year-5-6',
            'v' => 'Year 5 + 6',
            'c' => 1
        ),
        array(
            'i' => 'year-6',
            'v' => 'Year 6',
            'c' => 1
        ),
        array(
            'i' => 'year-7',
            'v' => 'Year 7',
            'c' => 1
        ),
        array(
            'i' => 'year-8',
            'v' => 'Year 8',
            'c' => 1
        )
    );
}

function wushka_ajax_school_save_settings()
{

    global $current_user;
    $current_user = wp_get_current_user();

    $json = filter_input(INPUT_POST, 'json');
    $data = json_decode(stripcslashes($json), TRUE);
    $user = $current_user->ID;
    $meta = FALSE;
    $role = FALSE;
    foreach ($data as $key => $value) {
        if (strpos($key, 'shipping') === 0 || strpos($key, 'principal') === 0 || strpos($key, 'deputy') === 0) {
            $meta = TRUE;
        }
        if (strpos($key, 'teacher') === 0) {
            $role = TRUE;
        }
    }
    $data['ID'] = $user;
    error_log('updating school settings:' . $user);
    error_log('data:' . print_r($data, TRUE));
    error_log('meta:' . $meta . ', role:' . $role);
    if ($user) {
        if ($meta) {
            foreach ($data as $key => $value) {
                update_user_meta($user, $key, $value);
            }
        } else if (!$role) {
            wp_update_user($data);
        } else {
            $active = get_user_meta($current_user->ID, 'active', true);
            if (strtolower($data['teacher']) === 'on') {
                error_log('adding teacher role');
                $current_user->add_role('teacher');
                if (empty($active)) {
                    add_user_meta($current_user->ID, 'active', 1);
                }
            } else {
                error_log('removing teacher role');
                $current_user->remove_role('teacher');
                if (!empty($active)) {
                    delete_user_meta($current_user->ID, 'active', 1);
                }
            }
        }
        exit();
    } else {
        exit();
    }
}

// user contact info filter for schools
add_filter('user_contactmethods', 'wushka_user_contactmethods', 99);
function wushka_user_contactmethods($user_contactmethods)
{
    $user_contactmethods['user_phone']                          = 'Phone number';
    $user_contactmethods['user_jobtitle']                       = 'Job title';
    $user_contactmethods['user_terms']                          = 'Accepted terms &amp; conditions';
    $user_contactmethods['user_subscriptionprice']              = 'Subscription price';
    $user_contactmethods['user_subscriptiondiscount']           = 'Subscription discount';
    $user_contactmethods['user_subscriptiondiscountexpiration'] = 'Subscription discount expiration';
    $user_contactmethods['user_subscriptiontrialexpiration']    = 'Subscription trial expiration';

    return $user_contactmethods;
}

function wushka_ajax_school_move_students()
{
    global $current_user;
    //Do Nothing
    $a_result = array(
        'status'  => 0,
        'updated' => 0,
        'message' => 'Validate Data'
    );

    error_log('----- Move School Students -----');
    $i_user = $current_user->ID;
    if (!is_user_logged_in() || !isset($i_user) || empty($i_user)) {
        $a_result['message'] = 'No Valid user Detected';
        echo json_encode($a_result);
        exit();
    }

    $i_table    = (int)json_decode(stripcslashes(filter_input(INPUT_POST, 'i_table')), TRUE);
    $i_class    = (int)json_decode(stripcslashes(filter_input(INPUT_POST, 'i_class')), TRUE);
    $a_students = json_decode(stripcslashes(filter_input(INPUT_POST, 'a_students')), TRUE);
    $s_auth     = json_decode(stripcslashes(filter_input(INPUT_POST, 's_auth')), TRUE);

    $class_details = wushka_get_class($i_class);
    if (isset($class_details)) {
        $licence = $class_details->licence_product;
        if ($licence == LICENCE_WDT) {
            wp_send_json("Sorry your licence for this class does not allow to perform this action.", 400);
        }
    }

    if (!isset($s_auth) || !wp_verify_nonce($s_auth, 'school_' . $current_user->ID . '_move_students')) {
        $a_result['message'] = 'Invalid Authorisation';
        echo json_encode($a_result);
        exit();
    }

    if (!isset($i_class) || empty($i_class) || $i_class == '0' || $i_class == 0) {
        $a_result['message'] = 'Cannot Move Students to Null Class';
        echo json_encode($a_result);
        exit();
    }

    error_log('Current Class ID:' . $i_table);
    error_log('New Class ID:' . $i_class);
    error_log('----------------------------------------');
    if (isset($a_students) && !empty($a_students)) {
        foreach ($a_students as $idx => $s_hash) {
            error_log('Student: ' . $s_hash);
            $o_user = get_user_by_hash($s_hash);
            if ($o_user !== FALSE) {
                error_log('Name: ' . $o_user->first_name . ' ' . $o_user->last_name . ', current class: ' . $o_user->class);
                error_log('Student Class Matches Current Table:');
                if ($i_table == (int)$o_user->class) {
                    error_log('Yes. Update User Class');
                    $x_updated = update_user_meta($o_user->ID, 'class', $i_class, $o_user->class);
                    if ($x_updated !== FALSE) {
                        //If User was inactive, make active
                        if (!isset($o_user->active) || empty($o_user->active) || $o_user->active == '0' || $o_user->active == 0) {
                            update_user_meta($o_user->ID, 'active', 1);
                        }

                        error_log('Updated!');
                        $a_result['updated']++;
                    } else {
                        error_log('User Was not Updated');
                    }
                } else {
                    error_log('No');
                }
            } else {
                error_log('No User Matching that Hash');
            }
        }
    } else {
        $a_result['status']  = 1;
        $a_result['message'] = 'No Students passed to function; No Students moved.';
        echo json_encode($a_result);
        exit();
    }

    /**
     * Fires after a student is moved.
     *
     * @param array     $a_students     Student Ids.
     * @param int       $i_table        Class ID From.
     * @param int       $i_class        Class ID To.
     */
    do_action('wushka_move_student_action', $a_students, $i_table, $i_class);

    error_log('----------------------------------------');

    $a_result['status']  = 1;
    $a_result['message'] = 'Students Moved';



    echo json_encode($a_result);
    exit();
}

function wushka_ajax_school_save_timezone()
{
    //error_log('Saving New School Tiemzone to');
    global $current_user;
    $terms  = wp_get_object_terms($current_user->ID, 'school');
    $school = $terms[0];

    $json = filter_input(INPUT_POST, 'json');
    $data = json_decode(stripcslashes($json), TRUE);

    $timezone = $data['school_tz'];
    //error_log('New TimeZone = '.$timezone);
    $school_options = get_option('taxonomy_' . $school->term_id);
    if (isset($timezone)) {
        $school_options['school_tz'] = $timezone;
    }

    $latitude = $data['latitude'];
    if (isset($latitude)) {
        $school_options['school_latitude'] = $latitude;
    }
    $longitude = $data['longitude'];
    if (isset($longitude)) {
        $school_options['school_longitude'] = $longitude;
    }

    update_option('taxonomy_' . $school->term_id, $school_options);

    exit();
}

function wushka_ajax_class_save()
{

    $json = filter_input(INPUT_POST, 'json');
    $data = json_decode(stripcslashes($json), TRUE);

    if (isset($data['id']) && (isset($data['year']) || isset($data['name']) || isset($data['students']))) {
        unset($data['teacher_add']);
        unset($data['teacher_rem']);
        wushka_update_class($data);
        exit();
    }

    if (isset($data['id']) && (isset($data['teacher_add']) || isset($data['teacher_rem']))) {
        //Send warning alert when teacher holds wushka decodable licence
        if (isset($data['teacher_add']) && !isset($data['teacher_rem'])) {
            $teacher = get_user_by_hash($data['teacher_add']);
            $teacher_name = ucwords(trim($teacher->first_name . ' ' . $teacher->last_name));
            $class_details = wushka_get_teacher_classes($teacher->ID);
            if (!empty($class_details)) {
                $teacher_licences = array_column(json_decode(json_encode($class_details), true), 'licence_product');
                if (in_array(LICENCE_WDT, $teacher_licences)) {
                    wushka_update_class_teachers($data);
                    wp_send_json($teacher_name . ' has already been assigned to the other class and holds a (' . LICENCE_WDT . ') licence. ' . $teacher_name . '\'s licence will still have the characteristics of a (' . LICENCE_WDT . ').', 202);
                } else {
                    $class_details = wushka_get_class($data['id']);
                    $licence = '';
                    if (isset($class_details) && !empty($class_details)) {
                        $licence = $class_details->licence_product;
                    }

                    if ($licence == LICENCE_WDT) {
                        wushka_update_class_teachers($data);
                        wp_send_json($teacher_name . ' has already been assigned to the class with other licence. ' . $teacher_name . ' will now have the characteristics of a (' . LICENCE_WDT . ').', 202);
                    }
                }
            }
        }

        wushka_update_class_teachers($data);
        exit();
    }

    if (isset($data['id']) && isset($data['licence']) && isset($data['school_id'])) {
        $data['licence_product'] = sanitize_text_field($data['licence']);
        $data['school_id'] = sanitize_text_field($data['school_id']);

        unset($data['licence']);
        $valid = check_licence_valid($data['licence_product'], $data['school_id']);


        if ($data['licence_product'] == LICENCE_WDT) {
            $students = wushka_get_classes_students([$data['id']]);
            if (!empty($students)) {
                $valid = false;
            }
        }

        if ($valid) {
            wushka_update_class($data);

            $teacher_licence = get_teachers_with_wdt($data['id'], $data['licence_product']);
            $return_data = ['licence_product' => $data['licence_product']];
            if (!empty($teacher_licence)) {
                if ($data['licence_product'] == LICENCE_WDT) {
                    $return_data['message'] = $teacher_licence . ' has already been assigned to the class with other licence. ' . $teacher_licence . ' will now have the characteristics of a (' . LICENCE_WDT . ').';
                } else {
                    $return_data['message'] = $teacher_licence . ' has already been assigned to the class with (' . LICENCE_WDT . ') licence. ' . $teacher_licence . ' will still have the characteristics of a (' . LICENCE_WDT . ').';
                }
                wp_send_json(json_encode($return_data), 202);
            } else {
                wp_send_json($data['licence_product']);
            }
        } else {
            wp_send_json("This licence does not support class with students.");
        }
    }

    exit();
}

function get_teachers_with_wdt($class_id, $licence_check = null)
{
    global $wpdb;

    //Get all teachers id from class id
    $table = $wpdb->prefix . 'classes_teachers';
    $sql = "SELECT `teacher_id` FROM " . $table . " WHERE `class_id` = %d";

    $results = $wpdb->get_results(
        $wpdb->prepare($sql, $class_id)
    );
    $teachers_id = array_column(json_decode(json_encode($results), true), 'teacher_id');

    $teachers = [];
    foreach ($teachers_id as $teacher_id) {
        //Only get active teachers
        if (get_user_meta($teacher_id, 'active', true)) {
            //Get teachers details and get name 
            $teacher = get_userdata($teacher_id);
            $teacher_name = ucwords(trim($teacher->first_name . ' ' . $teacher->last_name));

            //Get teachers licence for their class
            $class_details = wushka_get_teacher_classes($teacher_id);
            $class_licence = array_column(json_decode(json_encode($class_details), true), 'licence_product');


            if (in_array(LICENCE_WDT, $class_licence) || $licence_check == LICENCE_WDT) {
                array_push($teachers, $teacher_name);
            }
        }
    }
    $teachers = implode(', ', $teachers);
    $teachers = trim($teachers, ', ');
    return $teachers;
}

function wushka_get_class_teacher_licence($class_id)
{
    global $wpdb;

    $table = $wpdb->prefix . 'classes_teachers';
    $sql = "SELECT `teacher_id` FROM " . $table . " WHERE `class_id` = %d";

    $teachers = $wpdb->get_results(
        $wpdb->prepare($sql, $class_id)
    );
    $teachers_id = array_column(json_decode(json_encode($teachers), true), 'teacher_id');


    $licences = [];
    foreach ($teachers_id as $teacher_id) {
        if (get_user_meta($teacher_id, 'active', true)) {
            $class_details = wushka_get_teacher_classes($teacher_id);

            $class = [];
            foreach ($class_details as $class_detail) {
                $class_filtered = [
                    'class_id' => $class_detail->id,
                    'class_name' => $class_detail->name,
                    'licence' => $class_detail->licence_product,

                ];
                array_push($class, $class_filtered);
            }
            $licence = [
                'teacher_id' => $teacher_id,
                'class' => $class
            ];
            array_push($licences, $licence);
        }
    }
    return $licences;
}

function wushka_ajax_class_delete()
{

    global $current_user;
    $current_user = wp_get_current_user();

    $json = filter_input(INPUT_POST, 'json');
    $data = json_decode(stripcslashes($json), TRUE);

    if (isset($data['id'])) {
        $data['archived']      = TRUE;
        $data['archived_date'] = current_time('mysql');
        $data['archived_by']   = $current_user->ID;

        wushka_update_class($data);

        /**
         * Fires after a class is archived.
         *
         * @param int     $data['id']         Class ID.
         */
        do_action('wushka_delete_class_action', $data['id']);
    }

    exit();
}

function wushka_ajax_class_create()
{

    $json             = filter_input(INPUT_POST, 'json');
    $data             = json_decode(stripcslashes($json), TRUE);
    $result           = array();
    $result['result'] = 'failure';

    error_log('class create:' . print_r($data, TRUE));

    $data['name'] = sanitize_text_field($data['name']);

    if (!empty($data['name'])) {
        $class = wushka_create_class($data['name'], 0, $data['school'], $data['size']);
        if (isset($class)) {
            $result['result'] = 'success';
            $result['id']     = $class['id'];
            $result['licence']     = $class['licence_product'];
        }
    } else {
        $result['msg'] = 'Class name must be entered';
    }

    error_log('class create result:' . print_r($result, TRUE));

    echo json_encode($result);
    exit();
}

function wushka_ajax_teacher_save()
{

    $json             = filter_input(INPUT_POST, 'json');
    $data             = json_decode(stripcslashes($json), TRUE);
    $result           = array();
    $result['result'] = 'failure';
    $result['status'] = 0;

    error_log('----- Save New Teacher Details -----');
    error_log('Passed Parameters:');
    error_log(print_r($data, TRUE));

    if (isset($data) && !empty($data)) {
        $b_valid = TRUE;
        $s_type  = 'Input';
        foreach ($data as $s_key => $s_value) {
            if (!isset($s_value) || empty($s_value)) {
                $b_valid = FALSE;
                $s_type  = ucfirst($s_key);
                break;
            }

            if ($s_key == 'email') {
                if (!is_valid_email_address($s_value)) {
                    $b_valid = FALSE;
                    $s_type  = ucfirst($s_key);
                    break;
                }
            }
        }

        if ($b_valid) {
            $result           = wushka_update_teacher($data);
            $result['status'] = 1;
        } else {
            $result['msg'] = 'Invalid ' . $s_type;
        }
    }

    error_log('teacher save result:' . print_r($result, TRUE));

    echo json_encode($result);
    exit();
}

function wushka_ajax_teacher_create()
{

    $json             = filter_input(INPUT_POST, 'json');
    $data             = json_decode(stripcslashes($json), TRUE);
    $result           = array();
    $result['result'] = 'failure';

    error_log('teacher create:' . print_r($data, TRUE));

    $data['first_name'] = sanitize_text_field($data['first_name']);
    $data['last_name'] = sanitize_text_field($data['last_name']);
    $data['email'] = sanitize_text_field($data['email']);

    if (!empty($data['first_name']) && !empty($data['last_name']) && !empty($data['email'])) {
        $result = wushka_create_teacher($data);
    } else {
        $result['msg'] = 'first name, surname and email must be entered';
    }

    error_log('teacher create result:' . print_r($result, TRUE));

    echo json_encode($result);
    exit();
}

function wushka_ajax_teacher_delete()
{

    $json             = filter_input(INPUT_POST, 'json');
    $data             = json_decode(stripcslashes($json), TRUE);
    $result           = array();
    $result['result'] = 'failure';

    error_log('teacher delete:' . print_r($data, TRUE));

    if (!empty($data['id'])) {


        $result = wushka_delete_teacher($data);
        //Store Login School Event
        $user_id = get_user_by_hash($data['id']);
    } else {
        $result['msg'] = 'no teacher selected';
    }

    error_log('teacher delete result:' . print_r($result, TRUE));

    echo json_encode($result);
    exit();
}

function wushka_ajax_get_quiz_results()
{

    error_log('wushka_jax_get_quix_results');
    $json             = filter_input(INPUT_POST, 'json');
    $data             = json_decode(stripcslashes($json), TRUE);
    $result           = array();
    $result['result'] = 'failure';

    if (!empty($data['object_id']) && !empty($data['type'])) {
        $students = wushka_get_students($data['object_id'], $data['type']);
        $result['sidebar'] = wushka_generate_sidebar_content($students);
        $result['result'] = wushka_get_quiz_results($students);
    } else {
        $result['msg'] = 'invalid selection';
    }

    echo json_encode($result);
    exit();
}

function wushka_ajax_get_class_students()
{

    $json             = filter_input(INPUT_POST, 'json');
    $data             = json_decode(stripcslashes($json), TRUE);
    $result           = array();
    $result['result'] = 'failure';

    error_log('get students:' . print_r($data, TRUE));
    if (!empty($data['object_id']) && !empty($data['type'])) {
        $students = wushka_get_students($data['object_id'], $data['type']);
        $result = wushka_generate_sidebar_content($students);
    } else {
        $result['msg'] = 'invalid selection';
    }

    echo json_encode($result);
    exit();
}

function wushka_ajax_get_class_login()
{

    $json             = filter_input(INPUT_POST, 'json');
    $data             = json_decode(stripcslashes($json), TRUE);
    $result           = array();
    $result['result'] = 'failure';

    error_log('get students:' . print_r($data, TRUE));

    if (!empty($data['object_id']) && !empty($data['type'])) {
        $result = wushka_get_class_login($data['object_id'], $data['type']);
    } else {
        $result['msg'] = 'invalid selection';
    }

    echo json_encode($result);
    exit();
}

function wushka_ajax_get_students()
{

    $json             = filter_input(INPUT_POST, 'json');
    $data             = json_decode(stripcslashes($json), TRUE);
    $result           = array();
    $result['result'] = 'failure';

    error_log('get students:' . print_r($data, TRUE));

    if (!empty($data['object_id']) && !empty($data['type'])) {
        $result = wushka_get_students($data['object_id'], $data['type']);
    } else {
        $result['msg'] = 'invalid selection';
    }

    echo json_encode($result);
    exit();
}

function wushka_ajax_class_graph()
{

    $type   = json_decode(stripcslashes(filter_input(INPUT_POST, 'type')), TRUE);
    $school = json_decode(stripcslashes(filter_input(INPUT_POST, 'school')), TRUE);

    $graph = wushka_class_graph($school, $type);

    echo json_encode($graph);

    exit();
}

function wushka_class_graph($school, $type)
{

    $data           = wushka_get_school_years($school);
    $a_school_years = get_wushka_school_years();
    $a_years        = array();
    foreach ($a_school_years as $idx => $a_school_year) {
        foreach ($data as $i_key => $a_value) {
            $a_year = explode(':', $a_value->year);
            if ($a_year[0] == $a_school_year['i']) {
                $a_years[] = $a_value;
            }
        }
    }
    unset($data, $a_school_years, $a_year);
    $graph = array();

    switch ($type) {
        case 'donut':
            foreach ($a_years as $key => $value) {
                $year                           = explode(':', $value->year);
                $graph['data'][$key]['label'] = $year[1];
                $graph['data'][$key]['id']    = $year[0];
                $graph['data'][$key]['value'] = (int)$value->count;
            }
            break;
        case 'bar':
            foreach ($a_years as $key => $value) {
                $year                            = explode(':', $value->year);
                $graph['data'][$key]['class']  = $year[1];
                $graph['data'][$key]['number'] = (int)$value->count;
            }
            $graph['xkey']   = 'class';
            $graph['ykeys']  = 'number';
            $graph['labels'] = 'classes';

            break;
        case 'line':
            foreach ($a_years as $key => $value) {
                $year                            = explode(':', $value->year);
                $graph['data'][$key]['class']  = $year[1];
                $graph['data'][$key]['number'] = (int)$value->count;
            }
            $graph['xkey']   = 'class';
            $graph['ykeys']  = 'number';
            $graph['labels'] = 'classes';

            break;
        default:
            break;
    }

    return $graph;
}

function wushka_get_user_school($i_user = NULL)
{
    if (!isset($i_user) || empty($i_user)) {
        error_log('Function: wushka_get_user_school');
        error_log('Error: Null Value Passed to Function');

        return NULL;
    }

    $a_user_schools = wp_get_object_terms($i_user, 'school');
    $school_id      = NULL;

    if (isset($a_user_schools) && !empty($a_user_schools)) {
        $school_id = $a_user_schools[0]->term_taxonomy_id;
    }

    if (!isset($school_id) || empty($school_id)) {
        error_log('Function: wushka_get_user_school');
        error_log('Error: Null School ID Value Returned');

        return NULL;
    }

    return $school_id;
}

function wushka_get_class($class_id = NULL)
{
    if (!isset($class_id)) {
        return NULL;
    }
    global $wpdb;

    $class = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "classes  WHERE id = %d ", $class_id));

    return $class;
}

function wushka_get_teacher_classes($i_teacher = NULL)
{
    if (!isset($i_teacher)) {
        return NULL;
    }

    global $wpdb;

    $s_table_1 = $wpdb->prefix . 'classes';
    $s_table_2 = $wpdb->prefix . 'classes_teachers';

    $s_query = 'SELECT * FROM ' . $s_table_1 . ' WHERE id IN ( ' .
        ' SELECT class_id FROM ' . $s_table_2 . ' WHERE teacher_id = %d ' .
        ') AND archived != %d ORDER BY id ASC;';

    $a_results = $wpdb->get_results(
        $wpdb->prepare($s_query, $i_teacher, 1)
    );

    return $a_results;
}

function wushka_get_classes($school_id = NULL, $teacher_id = NULL, $archived = FALSE, $s_orderby = 'id', $s_order = 'ASC')
{
    global $wpdb;

    $classes  = array();
    $a_params = array();

    if (isset($teacher_id)) {
        error_log('getting class details by teacher');
        $a_params[] = $teacher_id;
        $a_params[] = $school_id;
        $a_params[] = $archived;

        $s_query = 'SELECT * FROM ' . $wpdb->prefix . 'classes_teachers JOIN ' . $wpdb->prefix . 'classes on ' .
            $wpdb->prefix . 'classes.id = ' . $wpdb->prefix . 'classes_teachers.class_id and ' .
            $wpdb->prefix . 'classes.school_id = ' . $wpdb->prefix . 'classes_teachers.school_id WHERE ' .
            $wpdb->prefix . 'classes_teachers.teacher_id = %d AND ' . $wpdb->prefix . 'classes_teachers.school_id = %d ' .
            'AND ' . $wpdb->prefix . 'classes.archived = %s';

        $classes = $wpdb->get_results(
            $wpdb->prepare($s_query, $a_params)
        );
    } else if (isset($school_id)) {
        error_log('getting class details by school');
        $a_params[] = $school_id;

        $s_query = 'SELECT *, id as class_id FROM ' . $wpdb->prefix . 'classes WHERE school_id = %d ';

        if ($archived !== 'both') {
            $a_params[] = $archived;
            $s_query .= 'AND archived = %s ';
        }
        $s_query .= 'ORDER BY ' . $s_orderby . ' ' . $s_order;

        $classes = $wpdb->get_results(
            $wpdb->prepare($s_query, $a_params)
        );
    }

    return $classes;
}

function wushka_get_class_teacher($i_class = NULL)
{
    if (!isset($i_class)) {
        return NULL;
    }

    global $wpdb;

    $s_query = 'SELECT teacher_id FROM ' . $wpdb->prefix . 'classes_teachers WHERE class_id = %d';

    $i_teacher = $wpdb->get_var(
        $wpdb->prepare($s_query, $i_class)
    );

    return $i_teacher;
}

function wushka_get_teachers($atts)
{
    global $wpdb;

    if (!current_user_can('school') && !current_user_can('administrator')) {
        return array();
    }

    $school_id = $atts['school_id'];

    if (array_key_exists('class_id', $atts)) {
        $class_id = $atts['class_id'];
        // get teachers for a class
        if (!empty($class_id)) {
            $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "classes_teachers WHERE class_id = %d AND school_id = %d", $class_id, $school_id));

            return $results;
        }
    }

    // get teachers for a school
    return wushka_get_school_users($atts['school_id'], 'teacher');
}

function wushka_get_parents($school_id)
{

    global $current_user;

    if (!user_can($current_user, "school") && !user_can($current_user, "administrator")) {
        return array();
    }

    // get parents for a school
    return wushka_get_school_users($school_id, 'parent');
}

function wushka_get_school_users($school_id = '', $type)
{
    global $wpdb;

    $a_ids = array();

    if (!empty($school_id)) {
        $s_query = 'SELECT * FROM ' . $wpdb->prefix . 'term_relationships WHERE term_taxonomy_id = %d';

        $a_results = $wpdb->get_results(
            $wpdb->prepare($s_query, $school_id)
        );

        if (!isset($a_results) || empty($a_results)) {

            return array();
        }

        foreach ($a_results as $o_result) {
            $a_ids[] = $o_result->object_id;
        }

        // updated Feb 2019 to prevent count_total performing slow query
        $args = array(
            'count_total'   => false,
            'include' => $a_ids,
            'role'    => $type,
            'orderby' => 'display_name',
            'order'   => 'ASC'
        );

        $o_query = new WP_User_Query($args);  // args updated for slow query

        if (isset($o_query) && !empty($o_query->results)) {

            return $o_query->results;
        }
    }

    return array();
}

function wushka_student_exists()
{

    $data = [
        'user_exists' => false
    ];

    $email = $_POST['email'];

    $classId = $_POST['classId'];

    $emailExists = false;

    $isActive = false;

    $needStudentReset = false;

    $emailExists = email_exists($email);

    if ($emailExists) {

        $isActive = get_user_meta($emailExists, 'active', true);
    }

    $perfixReset = "";
    if ($emailExists && ($isActive == false || $isActive == 0 || $isActive == '0')) {

        $needStudentReset = true;

        $perfixReset .= ' but is archived ';
    }


    if ($emailExists) {

        $o_class  = wushka_get_class(get_user_meta($emailExists, 'class', true));
        $i_school = NULL;
        if (isset($o_class) && !empty($o_class)) {
            $i_school = $o_class->school_id;

            $previousClass = wushka_get_class($classId);

            if (isset($previousClass) && !empty($previousClass)) {

                $oldSchoolId = $previousClass->school_id;

                if ($oldSchoolId !== $i_school) {

                    $data['valid'] = false;
                    $data['valid_message'] = "This Student already exists in different school. You don't have permission to add in your class.";
                }

                if ($previousClass->id === $o_class->id && ($isActive == 1 || $isActive)) {

                    $data['valid'] = false;

                    $data['valid_message'] = "This Student is already exists in this class.";
                }
            }
        }



        $data['user_exists'] = true;
        $data['class'] = $o_class;
        $data['needStudentReset'] = $needStudentReset;
        $data['confirm_message'] = "This student already exists in class <strong>{$o_class->name}</strong> {$perfixReset}. Do you still want to move this student into the new class?";
    }



    $data = $data;

    echo json_encode($data);
    exit;
}

function wushka_create_class($className, $user_id, $school_id = NULL, $class_size = 0, $licence = null)
{

    global $wpdb;

    $licence_product = $licence;
    if (!$licence) {
        $licence_product = 0;
        $current_date = date('Y-m-d H:i:s');
        //Get school term with school id 
        $term = get_term($school_id, "school");

        if (!empty($term->slug)) {
            //list licence of the slug
            $licence_available = get_school_licence_available($term->slug, $current_date);

            //Get default licence as per available licence
            $licence_product = get_default_licence_value($licence_available);
        }
    }

    $class   = $wpdb->insert(
        $wpdb->prefix . 'classes',
        array(
            'name'         => $className,
            'user_id'      => $user_id,
            'school_id'    => $school_id,
            'students'     => $class_size,
            'created_date' => current_time('mysql'),
            'licence_product' => $licence_product
        ),
        array(
            '%s',
            '%s',
            '%d',
            '%d',
            '%s',
            '%s'
        )
    );
    $i_class = $wpdb->insert_id;
    if ($class !== FALSE) {
        $a_event  = array(
            'school_id'   => $school_id,
            'event_type'  => 'admin',
            'sub_type'    => 'class',
            'action'      => 'created',
            'description' => 'Class: ' . $className . ' created',
            'meta_value'  => $i_class
        );
        $b_status = wushka_load_school_event($a_event);
    }


    /**
     * Fires after a class is created in the database
     * 
     *
     * @param int       $i_class        Class ID.
     * @param string    $className      Class name.
     * @param string    $school_id      Id of school where school is assigned.
     * @param int       $class_size     Student size for the class  
     */
    do_action('wushka_insert_class_action', $i_class, $className, $school_id, $class_size);

    error_log('Class Successfully Created, ID=' . $i_class);

    $data = ['id' =>  $i_class, 'licence_product' => $licence_product];

    return $data;
}

function wushka_update_class($data)
{

    global $wpdb;

    $class_id = $data['id'];
    unset($data['id']);
    error_log('updating class:' . $class_id);
    error_log('data:' . print_r($data, TRUE));

    $o_class  = wushka_get_class($class_id);

    $class = $wpdb->update(
        $wpdb->prefix . 'classes',
        $data,
        array('ID' => $class_id)
    );

    if (isset($data['archived']) && $data['archived'] == 1) {
        //Archive All students in Class
        include_once 'edit-user-data.php';
        wushka_set_whole_class_archive($class_id, 0);
    } else {
        /**
         * Fires after a class is updated and not archived.
         * 
         * @param int     $class_id                  Class ID. 
         * @param array   $args                      List of updated arguments.
         * @param array   $o_class (OPTIONAL)        Old Value.
         */
        do_action('wushka_update_class_action', $class_id, $data, $o_class);
    }

    if ($class !== FALSE) {

        $s_action = (isset($data['archived']) && $data['archived'] == 1) ? 'deleted' : 'updated';
        $a_event  = array(
            'school_id'   => $o_class->school_id,
            'event_type'  => 'admin',
            'sub_type'    => 'class',
            'action'      => $s_action,
            'description' => 'Class: ' . $o_class->name . ' ' . $s_action,
            'meta_value'  => $class_id
        );

        $b_status = wushka_load_school_event($a_event);
    }

    return $class;
}

function wushka_update_class_teachers($data)
{

    global $wpdb;
    global $current_user;

    $o_class  = wushka_get_class($data['id']);

    $class        = FALSE;
    $s_event_des  = NULL;
    $o_teacher    = get_user_by_hash($data['teacher_add']);
    $s_event_type = 'edited Teacher';
    if (isset($data['teacher_add'])) {
        $class_id = $data['id'];
        $class       = $wpdb->insert(
            $wpdb->prefix . 'classes_teachers',
            array(
                'class_id'   => $data['id'],
                'school_id'  => $data['school_id'],
                'teacher_id' => $o_teacher->ID
            )
        );
        $s_event_des = 'added Teacher';
    }
    if (isset($data['teacher_rem'])) {
        $class_id = $data['teacher_rem'];
        $class       = $wpdb->delete(
            $wpdb->prefix . 'classes_teachers',
            array(
                'class_id'   => $data['teacher_rem'],
                'school_id'  => $data['school_id'],
                'teacher_id' => $o_teacher->ID
            )
        );
        $s_event_des = 'removed Teacher';
    }

    /**
     * Fires after a teacher is added or removed
     * 
     * @param int     $class_id                  Class ID. 
     * @param array   $args                      List of updated arguments.
     * @param array   $o_class (OPTIONAL)        Old Value.
     */
    do_action('wushka_update_class_action', $class_id, $data);

    if ($class !== FALSE) {
        $a_event  = array(
            'school_id'   => $data['school_id'],
            'event_type'  => 'admin',
            'sub_type'    => 'class',
            'action'      => 'updated',
            'description' => 'Class: ' . $o_class->name . ' ' . $s_event_des,
            'meta_value'  => $data['id']
        );
        $b_status = wushka_load_school_event($a_event);
    }


    return $class;
}

function wushka_get_school_years($school_id = NULL)
{

    global $wpdb;
    global $current_user;

    $years = array();

    if (!user_can($current_user, "school") && !user_can($current_user, "administrator")) {
        return $years;
    }

    if (!empty($school_id)) {
        $years = $wpdb->get_results($wpdb->prepare("SELECT year, count(*) as count FROM " . $wpdb->prefix . "classes WHERE school_id = %d and year is not null group by year", $school_id));
    }

    //        error_log('years:' . print_r($years, true));
    return $years;
}

function wushka_create_teacher($data)
{

    error_log('checking duplicate user');
    if (username_exists($data['email']) || email_exists($data['email'])) {
        $return['result'] = 'failure';
        $return['msg']    = 'A teacher with this email address already exists';

        return $return;
    }

    if (!is_valid_email_address($data['email'])) {
        $return['result'] = 'failure';
        $return['msg']    = 'This is an invalid email address';

        return $return;
    }

    $user_login = $data['email'];
    if (isset($data['username']) && !empty($data['username'])) {
        if (username_exists($data['username'])) {
            $return['result'] = 'failure';
            $return['msg']    = 'A teacher with this username address already exists';

            return $return;
        }
        $user_login = $data['username'];
    }

    error_log('generating password');
    $password = wp_generate_password(12, TRUE);
    error_log('generating password finished');

    $userdata = array(
        'user_login' => $user_login,
        'user_pass'  => $password,
        'user_email' => $data['email'],
        'first_name' => $data['first_name'],
        'last_name'  => $data['last_name'],
        'role'       => 'teacher'
    );

    $tempdata = $userdata;
    unset($tempdata['user_pass']);

    error_log('creating new user . ' . print_r($tempdata, true));
    $user_id = wp_insert_user($userdata);
    // if (is_wp_error($user_id)) {
    //     error_log('error creating user ' . $user_id->get_error_message());
    // }
    error_log('creating new user finished');

    $user = get_user_by('id', $user_id);

    error_log('updating school taxonomy');
    wp_set_object_terms($user_id, array(intval($data['school_id'])), 'school', FALSE);
    clean_object_term_cache($user_id, 'school');
    error_log('updating school taxonomy finished');

    $return              = $userdata;
    $return['user_pass'] = '';
    $return['result']    = 'success';
    $return['id']        = $user->id_hash;

    // send the user a confirmation and their login details
    $s_temp = hash('sha256', $password . md5(time()));

    $dt = new DateTime('NOW');
    $dt->setTimezone(new DateTimeZone('UTC'));

    $s_now = $dt->format('Y-m-d');

    $dt->modify('+ 6 months');
    $s_window = $dt->format('Y-m-d');

    error_log('updating meta');
    $active = get_user_meta($user->ID, 'active', true);
    if (empty($active)) {
        add_user_meta($user->ID, 'active', 1);
    }
    add_user_meta($user->ID, 'tmp_pwd_verify', $s_temp);
    add_user_meta($user->ID, 'tmp_pwd_window', $s_window);
    add_user_meta($user->ID, 'reminder_email_last', $s_now);
    add_user_meta($user->ID, 'reminder_email_count', 0);
    error_log('updating meta finished');


    error_log('adding school event');
    $a_event_args = array(
        'school_id'   => (int)$data['school_id'],
        'event_type'  => 'admin',
        'sub_type'    => 'teacher',
        'action'      => 'created',
        'description' => 'Teacher: ' . $data['first_name'] . ' ' . $data['last_name'] . ' created',
        'meta_value'  => $user_id
    );

    $b_stats = wushka_load_school_event($a_event_args);
    error_log('adding school event finished');

    error_log('emailing new user');
    ob_start();
    include('customer-new-account.php');
    $message = ob_get_clean();
    wp_mail($data['email'], 'Welcome to Wushka', $message, 'Content-Type: text/html; charset=UTF-8');
    error_log('emailing new user finished');

    return $return;
}

add_action('profile_update', 'set_active_meta_to_teacher', 10, 3);
function set_active_meta_to_teacher($user_id, $user_data)
{
    if (in_array('teacher', $user_data->roles)) {
        $active = get_user_meta($user_id, 'active', true);
        if (empty($active)) {
            add_user_meta($user_id, 'active', 1);
        }
    }
}

function is_valid_email_address($s_email = NULL)
{
    if (!isset($s_email) || empty($s_email)) {
        return FALSE;
    }

    //Verify email address follows standard email address structure
    preg_match_all('/\@/', $s_email, $a_duplicate);
    if (isset($a_duplicate) && !empty($a_duplicate) && count($a_duplicate) > 1) {
        error_log('Found more than one @ symbol in email address');

        return FALSE;
    }

    if (filter_var(trim($s_email), FILTER_VALIDATE_EMAIL) === FALSE || !preg_match('/^[a-zA-Z0-9\-\_\.]+\@+[a-zA-Z0-9]+/', $s_email)) {
        error_log('email doesnt follow standard email structure');

        return FALSE;
    }

    return TRUE;
}

function wushka_update_teacher($data)
{

    $o_user = get_user_by_hash($data['id']);

    $userdata = array(
        'ID' => $o_user->ID
    );
    if (isset($data['email']) && !empty($data['email'])) {
        $meta_key = 'user_email';
        $userdata[$meta_key] = $data['email'];
    }
    if (isset($data['first_name']) && !empty($data['first_name'])) {
        $meta_key = 'first_name';
        $userdata[$meta_key] = $data['first_name'];
    }
    if (isset($data['last_name']) && !empty($data['last_name'])) {
        $meta_key = 'last_name';
        $userdata[$meta_key] = $data['last_name'];
    }


    /**
     * Fires before user is updated.
     *
     * @param int       $id              USER id.
     * @param string    $meta_key        Meta Key.
     * @param string    $meta_value      Meta Value.
     */
    do_action('wushka_edit_user_action', $data['id'], $meta_key, $userdata[$meta_key]);

    $user_id = wp_update_user($userdata);

    $return = $data;

    if (is_wp_error($user_id)) {
        $return['result'] = 'success';
        $return['msg']    = 'update of teacher failed';

        return $return;
    } else {
        $return['result'] = 'success';
    }

    $teacher_school = wp_get_object_terms($o_user->ID, 'school');
    $school_id      = NULL;
    if (isset($teacher_school) && !empty($teacher_school)) {
        $school_id = $teacher_school[0]->term_taxonomy_id;
    }

    $s_first = isset($userdata['first_name']) ? $userdata['first_name'] : $o_user->first_name;
    $s_last  = isset($userdata['last_name']) ? $userdata['last_name'] : $o_user->last_name;

    $a_event_args = array(
        'school_id'   => (int)$school_id,
        'event_type'  => 'admin',
        'sub_type'    => 'teacher',
        'action'      => 'updated',
        'description' => 'Teacher: ' . $s_first . ' ' . $s_last . ' edited',
        'meta_value'  => $o_user->ID
    );

    $b_stats = wushka_load_school_event($a_event_args);

    return $return;
}

function wushka_delete_teacher($data)
{
    $return = $data;

    $o_user = get_user_by_hash($data['id']);

    $teacher_school = wp_get_object_terms($o_user->ID, 'school');
    $school_id      = NULL;
    if (isset($teacher_school) && !empty($teacher_school)) {
        $school_id = $teacher_school[0]->term_taxonomy_id;
    }

    $a_event_args = array(
        'school_id'   => (int)$school_id,
        'event_type'  => 'admin',
        'sub_type'    => 'teacher',
        'action'      => 'deleted',
        'description' => 'Teacher: ' . $o_user->first_name . ' ' . $o_user->last_name . ' removed',
        'meta_value'  => $o_user->ID
    );

    $b_stats = wushka_load_school_event($a_event_args);

    // Include user admin functions to get access to wp_delete_user().
    require_once ABSPATH . 'wp-admin/includes/user.php';

    wp_delete_user($o_user->ID);

    return $return;
}

function wushka_get_students($object_id, $type, $active = 1)
{

    $return = array();

    $user_fields = array('first_name', 'last_name');
    // updated Feb 2019 to prevent count_total performing slow query
    $args = array(
        'role'       => 'student',
        'count_total'   => false,
        'meta_query' => array(
            'relation' => 'AND',
            0          => array(
                'key'   => $type,
                'value' => $object_id
            ),
            1          => array(
                'key'   => 'active',
                'value' => $active
            )
        )
    );
    $user_query = new WP_User_Query($args);  // args updated for slow query
    if (!empty($user_query->results)) {
        $return = $user_query->results;
    }

    return $return;
}


function wushka_class_move_student($studentId, $student_data, $class, $total = 1, $available = 1, $return = 1, $resetData)
{

    $oldClassId = get_user_meta($studentId, 'class', true);

    $o_class  = wushka_get_class($oldClassId);

    $schoolId = false;

    if (!empty($o_class)) {

        $schoolId = $o_class->school_id;
    }

    if ($resetData) {
        resetStudentData($studentId, $schoolId);
    }


    $o_user = get_user_by('id', $studentId);

    //// custom

    $a_group = array(
        'ID'    => '000',
        'value' => 'No Group'
    );


    if (isset($o_user->my_reading_group)) {
        if (!empty($a_groups)) {
            foreach ($a_groups[(int)$o_user->class] as $id => $s_group) {
                if ((int)$o_user->my_reading_group == (int)$id) {
                    $a_group['ID']    = $id;
                    $a_group['value'] = $s_group;
                }
            }
        }
    }

    if ($resetData === false && isset($o_user->my_reading_group) && !empty($o_user->my_reading_group)) {

        $a_group = moveStudentGetReadingGroup($o_user->my_reading_group, $class);
    }

    $a_level = array(
        'name' => '',
        'slug' => ''
    );
    $a_level['name'] = isset($o_user->reading_level) && isset($o_user->reading_level['name']) ? $o_user->reading_level['name'] : '';
    $a_level['slug'] = isset($o_user->reading_level) && isset($o_user->reading_level['slug']) ? $o_user->reading_level['slug'] : '';

    $a_setting['id']   = 'on';
    $a_setting['name'] = 'On';
    if (isset($o_user->rg_setting)) {
        $a_setting['id']   = trim(strtolower($o_user->rg_setting));
        $a_setting['name'] = trim(ucwords($o_user->rg_setting));
    }

    $allow_book_view = ($o_user->allow_book_view) ? $o_user->allow_book_view : 'No';
    $quiz_narration = ($o_user->quiz_narration) ? $o_user->quiz_narration : 'Yes';
    $quiz_detail_results = ($o_user->quiz_detail_results) ? $o_user->quiz_detail_results : 'Yes';



    /*** end custom */

    update_user_meta($studentId, 'class', $class);
    update_user_meta($studentId, 'active', 1);



    //Get School For School Event Notification
    // $o_class  = wushka_get_class($class);
    // $i_school = NULL;
    // if (isset($o_class) && !empty($o_class)) {
    //     $i_school = $o_class->school_id;
    // }


    $a_user = array(
        'id_hash'          => $o_user->id_hash,
        'first_name'       => trim(ucwords($o_user->first_name)),
        'last_name'        => trim(ucwords($o_user->last_name)),
        'username'         => trim($o_user->user_login),
        'email'            => $o_user->user_email,
        'user_pass'        => $o_user->show_user_pwd,
        'reading_level'    => $a_level,
        'allowed_shelves'  => trim(ucwords($o_user->allowed_shelves['name'])),
        'my_reading_group' => $a_group,
        'rg_setting'       => $a_setting,
        'narration'        => trim(ucwords($o_user->narration)),
        'allow_book_view'        => trim(ucwords($allow_book_view)),
        'quizzes'          => trim(ucwords($o_user->quizzes)),
        'quiz_narration'          => trim(ucwords($quiz_narration)),
        'quiz_detail_results'          => trim(ucwords($quiz_detail_results))
    );



    if ($return) {

        $return['total']     = $total + 1;
        $return['available'] = $available;
        $return['user']      = $a_user;
        $return['old_classId'] = $oldClassId;
        $return['move']      = true;
    }

    if (!is_wp_error($studentId)) {
        return json_encode($return);
    }
}

function wushka_create_student($student_data, $class, $total = 1, $available = 1, $return = 1)
{
    $first_name = (isset($student_data['first_name'])) ? $student_data['first_name'] : '';
    $last_name  = (isset($student_data['last_name'])) ? $student_data['last_name'] : '';
    $user_name  = (isset($student_data['user_name'])) ? $student_data['user_name'] : '';
    $s_email    = (isset($student_data['s_email'])) ? $student_data['s_email'] : '';

    $user_pwd   = "temppwd";

    $allowed_shelves['id']   = 'all';
    $allowed_shelves['name'] = 'All Levels';

    $args  = array(
        'orderby' => 'slug',
        'order'   => 'ASC'
    );
    $terms = get_terms('reading-level', $args);

    foreach ($terms as $idx => $term) {
        $prepared_shelves[] = $term->slug;
    }

    error_log('adding user:' . $user_name);
    error_log('first name:' . $first_name);
    error_log('last name:' . $last_name);
    //error_log('pwd:' . $user_pwd);

    $a_user = array(
        'user_login' => $user_name,
        'user_pass'  => $user_pwd,
        'role'       => 'student',
        'first_name' => ucwords($first_name),
        'last_name'  => ucwords($last_name)
    );

    if (!empty($s_email)) {
        $a_user['user_email'] = $s_email;
    }

    $user_id = wp_insert_user($a_user);

    // update_user_meta($user_id, 'first_name', ucwords($first_name));
    // update_user_meta($user_id, 'last_name', ucwords($last_name));
    if (is_array($class) && isset($class['school_id'])) {
        add_user_meta($user_id, 'school_id', $class['school_id']);
    } else {
        add_user_meta($user_id, 'class', $class);
    }
    add_user_meta($user_id, 'show_admin_bar_front', 'false');
    add_user_meta($user_id, 'show_user_pwd', $user_pwd);
    add_user_meta($user_id, 'allowed_shelves', $allowed_shelves);
    add_user_meta($user_id, 'prepared_shelves', $prepared_shelves);
    add_user_meta($user_id, 'narration', 'Yes');
    add_user_meta($user_id, 'quizzes', 'compulsory');
    add_user_meta($user_id, 'quiz_narration', 'Yes');
    add_user_meta($user_id, 'quiz_detail_results', 'Yes');
    add_user_meta($user_id, 'rg_setting', 'on');
    add_user_meta($user_id, 'active', 1);

    $o_user = get_user_by('id', $user_id);

    //Get School For School Event Notification
    $o_class  = wushka_get_class($class);
    $i_school = NULL;
    if (isset($o_class) && !empty($o_class)) {
        $i_school = $o_class->school_id;
    }
    if ($i_school !== NULL) {
        error_log('updating school taxonomy');
        wp_set_object_terms($user_id, array(intval($i_school)), 'school', FALSE);
        clean_object_term_cache($user_id, 'school');
        error_log('updating school taxonomy finished');

        $a_event_args = array(
            'school_id'   => (int)$i_school,
            'event_type'  => 'teacher',
            'sub_type'    => 'student',
            'action'      => 'created',
            'description' => 'Student: ' . $first_name . ' ' . $last_name . ' created',
            'meta_value'  => $user_id
        );

        $b_stats = wushka_load_school_event($a_event_args);
    }

    $a_group = array(
        'ID'    => '000',
        'value' => 'No Group'
    );

    $a_level = array(
        'name' => '',
        'slug' => ''
    );


    $allow_book_view = ($o_user->allow_book_view) ? $o_user->allow_book_view : 'No';
    $quiz_narration = ($o_user->quiz_narration) ? $o_user->quiz_narration : 'Yes';
    $quiz_detail_results = ($o_user->quiz_detail_results) ? $o_user->quiz_detail_results : 'Yes';

    $a_user = array(
        'id_hash'          => $o_user->id_hash,
        'first_name'       => trim(ucwords($o_user->first_name)),
        'last_name'        => trim(ucwords($o_user->last_name)),
        'username'         => trim($o_user->user_login),
        'email'            => trim($o_user->user_email),
        'user_pass'        => trim($o_user->show_user_pwd),
        'reading_level'    => $a_level,
        'allowed_shelves'  => trim(ucwords($o_user->allowed_shelves['name'])),
        'my_reading_group' => $a_group,
        'narration'        => trim(ucwords($o_user->narration)),
        'allow_book_view'  => trim(ucwords($allow_book_view)),
        'quiz_narration'  => trim(ucwords($quiz_narration)),
        'quiz_detail_results'  => trim(ucwords($quiz_detail_results)),
        'quizzes'          => trim(ucwords($o_user->quizzes)),
        'rg_setting' => 'on'
    );

    if ($return) {
        $return['total']     = $total + 1;
        $return['available'] = $available;
        $return['user']      = $a_user;
    }

    if (!is_wp_error($user_id)) {
        return json_encode($return);
    }
}



/**
 * student_email_validation
 *
 * @param  string $email
 * @return array
 */
function student_email_validation($email, $email_exists_validation = false)
{
    //Check if email is empty
    if (empty($email)) {
        return [
            'type'      =>  'error',
            'message'   =>  'Empty email address.'

        ];
    }

    //Check if input is valid email address
    if (!is_email($email)) {
        return [
            'type'      =>  'error',
            'message'   =>  'Invalid email address.'

        ];
    }
    /*
    //Only allow email addresses with edu au
    $allowed_extension = ['.edu.au', '.gov.au'];
    //Adding additional . for validation purpose only
    $allowed_extension = array_map(function($domain_extension) { return "$domain_extension."; }, $allowed_extension);
    $post_domain_extension = array_pop(explode('@', $email)).'.'; 
    $allowed = ($post_domain_extension != str_ireplace($allowed_extension,"XX",$post_domain_extension))? true: false;
    if(!$allowed){
        return [
            'type'      =>  'error',
            'message'   =>  'Sorry, '.rtrim($post_domain_extension, '.').' is a prohibited domain extension.'

        ];
    }
    */

    if ($email_exists_validation) {

        //Check if email alreadys exist
        if (email_exists($email)) {
            return [
                'type'      =>  'error',
                'message'   =>  'Sorry, that email address is already used!'

            ];
        }
    }

    return [
        'type'  =>  'success',
        'email' =>  $email
    ];
}

function wushka_get_teacher_count($school_id)
{
    $atts['school_id'] = $school_id;
    $atts['class_id']  = NULL;

    return count(wushka_get_teachers($atts));
}

function wushka_get_class_count($school_id)
{
    return count(wushka_get_classes($school_id));
}

function wushka_get_parent_count($school_id)
{
    return count(wushka_get_parents($school_id));
}

function build_class_selector($i_school_id = NULL, $i_teacher_id = NULL, $s_page_slug = NULL)
{
    if (!isset($_SESSION)) {
        session_start();
    }
    if (!isset($i_school_id) && !isset($i_teacher_id)) {
        return FALSE;
    }

    $a_classes = wushka_get_classes($i_school_id, $i_teacher_id, FALSE);
    if (empty($a_classes)) {
        error_log('Error: No Classes Found');

        return FALSE;
    }

    $a_classes_menu  = array();
    $a_classes_panel = array();
    foreach ($a_classes as $i_key => $o_class) {
        $i_class_id     = (int)$o_class->class_id;
        $s_active       = NULL;
        $s_panel_active = NULL;
        if (isset($_SESSION['class_id'])) {
            if ((int)$_SESSION['class_id'] == $i_class_id) {
                $s_active       = 'active';
                $s_panel_active = 'in active';
            }
        } else if ($i_key == 0) {
            $s_active       = 'active';
            $s_panel_active = 'in active';
        }

        $a_menu = [];
        
        if (!empty($o_class->name)) {

            $a_menu[] = '<li role="presentation" class="class-list class-switch ' . $s_active . '">';
            $a_menu[] = '<a href="#' . $i_class_id . '-class" role="tab" data-toggle="tab" style="height:49px;font-size:1.5rem;">' . $o_class->name . '</a>';
            $a_menu[] = '</li>';
        }


        $a_classes_menu[] = implode('', $a_menu);
        unset($a_menu);

        $a_panel['ID']      = $i_class_id;
        $a_panel['top']     = '<div role="tabpanel" class="tab-pane fade ' . $s_panel_active . '" id="' . $i_class_id . '-class"><div class="table-responsive">';
        $a_panel['content'] = NULL;
        $a_panel['bottom']  = '</div></div>';
        $a_panel['active'] = $s_active;

        $a_classes_panel[] = $a_panel;
        unset($a_panel);
    }

    //Build Tab Menu
    $a_html[] = '<div role="tabpanel" class="' . $s_page_slug . '">';
    $a_html[] = '<ul class="nav nav-tabs" role="tablist">';
    foreach ($a_classes_menu as $i_class => $s_menu) {
        $a_html[] = $s_menu;
    }
    $a_html[] = '</ul>';
    $a_html[] = '</div>';

    $a_return['classes'] = $a_classes_panel;
    $a_return['menu']    = implode('', $a_html);

    return $a_return;
}

function wushka_load_teacher_page()
{
    error_log('--- Generate Teacher Class Menus ---');
    global $current_user;
    $a_page = array(
        'classes'  => array(),
        'students' => array(),
        'cookies'  => array(),
        'menus'    => array(
            'tabs'  => array(),
            'lists' => array()
        ),
    );

    if (!current_user_can('teacher') && !current_user_can('school')) {
        return $a_page;
    }

    $a_query = wushka_get_teacher_classes($current_user->ID);

    if (!isset($a_query) || empty($a_query)) {
        return $a_page;
    }

    //Get Array Of Class IDS
    $a_ids             = array();
    $a_cookies         = wushka_get_class_cookies();
    $a_page['cookies'] = $a_cookies;

    $class_id = $a_query[0]->id;
    if (!isset($a_cookies['id']) || empty($a_cookies['id'])) {
        $a_cookies['id'] = $class_id;
    }

    foreach ($a_query as $idx => $o_class) {
        $a_ids[] = $o_class->id;
        $a_class = array(
            'class'    => NULL,
            'active'   => FALSE,
            'students' => array(),
            'current'  => NULL
        );

        $a_class['class']   = $o_class;
        $a_class['active']  = $a_cookies['id'] == (int)$o_class->id ? TRUE : FALSE;
        // if ($a_class['active']) {
        //     $a_ids[] = $o_class->id;
        // }
        $a_class['current'] = isset($a_cookies['student']) ? $a_cookies['student'] : NULL;

        $a_page['classes'][$o_class->id] = $a_class;
    }


    error_log('Get Students for all classes');
    $a_results = wushka_get_classes_students($a_ids);

    if (!empty($a_results)) {

        /* Sorting By Last Name */
        $sort_last_name = usort($a_results, "sort_user_by_lastname");

        foreach ($a_results as $idx => $o_user) {
            $a_page['students'][]                              = $o_user;
            $a_page['classes'][$o_user->class]['students'][] = $o_user;
        }
    }

    $a_page['menus'] = wushka_create_class_menus($a_page['classes']);

    return $a_page;
}

function wushka_create_class_menus($a_classes)
{
    //Build Class List Selectors
    error_log('Create menu html for all classses');
    $a_menu = array(
        'tabs'  => array(),
        'lists' => array()
    );

    $a_tabs  = array();
    $a_lists = array();
    foreach ($a_classes as $idx => $a_class) {
        $a_tabs[]  = wushka_create_class_tab_selector($a_class);
        $a_lists[] = wushka_create_class_list_selector($a_class);
    }

    //Create Class Tab Menu
    $a_menu['tabs'][] = '<div role="tabpanel" class="class-statistics">';
    $a_menu['tabs'][] = '<ul class="nav nav-tabs" role="tablist">';
    $a_menu['tabs'][] = implode('', $a_tabs);
    $a_menu['tabs'][] = '</ul>';
    $a_menu['tabs'][] = '</div>';

    //Create Class List Menu
    $a_menu['lists'][] = '<div role="tabpanel">';
    $a_menu['lists'][] = '<div class="tab-content student-tabs">';
    $a_menu['lists'][] = implode('', $a_lists);
    $a_menu['lists'][] = '</div>';
    $a_menu['lists'][] = '</div>';

    return $a_menu;
}

//Pass an array of class IDS, get array of active student users
function wushka_get_classes_students($a_classes = array())
{
    if (!isset($a_classes) || empty($a_classes)) {
        return array();
    }

    // updated Feb 2019 to prevent count_total performing slow query
    $a_args = array(
        'role'       => 'student',
        'count_total'   => false,
        'meta_query' => array(
            'relation' => 'AND',
            0          => array(
                'key'     => 'class',
                'value'   => $a_classes,
                'compare' => 'IN'
            ),
            1          => array(
                'key'   => 'active',
                'value' => 1
            )
        )
    );

    $a_results = new WP_User_Query($a_args);  // args updated for slow query
    if (isset($a_results->results) && !empty($a_results->results)) {
        return $a_results->get_results();
    }

    return array();
}

//Create a Class Tab Selector
function wushka_create_class_tab_selector($a_class)
{
    if (!isset($a_class) || empty($a_class)) {
        return NULL;
    }

    $o_class = $a_class['class'];

    $s_active = $a_class['active'] ? 'active' : NULL;

    $a_item   = array();
    $a_item[] = '<li role="presentation" class="class-list class-switch ' . $s_active . '">';
    $a_item[] = '<a href="#' . $o_class->id . '-class" role="tab" data-toggle="tab">';
    $a_item[] = trim(ucwords($o_class->name));
    $a_item[] = '</a>';
    $a_item[] = '</li>';

    return implode('', $a_item);
}

//Create a tab panel menu list of students in a class
function wushka_create_class_list_selector($a_class = array())
{
    if (!isset($a_class) || empty($a_class)) {
        return NULL;
    }

    $o_class    = $a_class['class'];
    $a_students = $a_class['students'];
    //Cookies
    $active_class = $a_class['active'] ? 'in active' : NULL;

    $s_cookie = $a_class['current'];

    $a_item = array();

    $a_item[] = '<div role="tabpanel" class="tab-pane fade ' . $active_class . '" id="' . $o_class->id . '-class">';
    $a_item[] = '<div class="table-responsive">';
    $a_item[] = '<div class="panel panel-default">';
    $a_item[] = '<div class="panel-heading"><i class="glyphicon glyphicon-user"></i>Students</div>';
    $a_item[] = '<div class="panel-body">';
    $a_item[] = '<div class="list-group student-list">';
    if (!empty($a_students)) {
        //Set Current Student for this class
        $s_current = $a_students[0]->id_hash;
        if ($a_class['active']) {
            foreach ($a_students as $id => $o_student) {
                $s_current = isset($s_cookie) && $s_cookie == $o_student->id_hash ? $s_cookie : $s_current;
            }
        }

        foreach ($a_students as $id => $o_student) {
            $s_active    = $s_current == $o_student->id_hash ? 'active' : NULL;
            $a_student   = array();
            $a_student[] = '<a href="#" class="list-group-item list-student ' . $s_active . '" data-id="' . $o_student->id_hash . '">';
            //$a_student[] = wp_nonce_field('student_details_nonce_' . $o_student->id_hash, '_student_wpn', FALSE, FALSE);
            $a_student[] = '<input type="hidden" name="_student_wpn" class="_student_wpn" value="' . wp_create_nonce('student_details_nonce_' . $o_student->id_hash, '_student_wpn') . '" >';
            $a_student[] = trim(ucwords($o_student->first_name . ' ' . $o_student->last_name));
            $a_student[] = '</a>';

            $a_item[] = implode('', $a_student);
            unset($s_active, $a_student);
        }
    }
    $a_item[] = '</div>';
    $a_item[] = '</div>';
    $a_item[] = '</div>';
    $a_item[] = '</div>';
    $a_item[] = '</div>';

    return implode('', $a_item);
}

//Return Start/End Times For Student's School
function wushka_get_school_hours($i_class = 0)
{
    if (!isset($i_class) || !$i_class) {
        return NULL;
    }
    //#TODO: VERIFY THIS WORKS WITH STRING TIMEZONE VALUE
    //Get Student School
    $s_user_tz = NULL;
    $o_class   = wushka_get_class($i_class);
    $i_school  = $o_class->school_id;

    $school_meta = get_option('taxonomy_' . $i_school);
    if (isset($school_meta['school_tz'])) {
        $s_school_tz = $school_meta['school_tz'];
    }

    $a_tzlist        = get_wushka_timezones();
    $s_user_timezone = NULL;
    foreach ($a_tzlist as $i_key => $tz) {
        if ($i_key == $s_school_tz) {
            $s_user_tz = $tz;
            break;
        }
    }

    return $s_user_tz;
}

/* ---------- Student Login Time Function ---------- */
function wushka_is_school_hours($s_school_time = NULL, $school_user)
{
    global $wpdb;

    if (isset($s_school_time)) {
        $s_timezone = $s_school_time;
    } else {
        $s_timezone = 'UTC';
    }
    $dt = new DateTime('NOW', new DateTimeZone($s_timezone));

    // retrieve school calendar
    $s_state  = wushka_get_school_caldendar_state($school_user->ID);
    $a_events = wushka_get_calendar_events($s_state, $school_user->ID);

    // determine which event today is part of
    $term = NULL;
    foreach ($a_events as $i_key => $o_result) {
        $from = new DateTime($o_result->date);
        $end  = new DateTime($o_result->date_end);
        if ($dt >= $from && $dt <= $end && $o_result->category <= 5) {
            $term = $o_result;
        }
    }

    error_log('determined we are in: ' . $term->title . ', from: ' . $term->date . ', to: ' . $term->date_end . ', time: ' . $term->time);

    //Category 5 == Public Holiday. Is Not School Hours
    if ($term->category === 5) {
        return FALSE;
    }

    $time    = explode('-', $term->time);
    $s_start = $time[0];
    $s_end   = $time[1];

    error_log('Student TimeZone = ' . $s_timezone);

    $i_time_current = strtotime($dt->format('g:i a'));
    $i_time_start   = strtotime($s_start);
    $i_time_end     = strtotime($s_end);

    error_log('Determine if In School Hours');
    error_log('School Current: ' . $i_time_current);
    error_log('School Start:   ' . $i_time_start);
    error_log('School End:     ' . $i_time_end);

    //Is Current Time Between Start/End Time?
    if (($i_time_current > $i_time_start) && ($i_time_current < $i_time_end)) {
        error_log('We Are in School Hours');

        return TRUE;
    }

    error_log('This is Not School Hours');

    return FALSE;
}

function wushka_get_school_caldendar_state($i_school)
{
    error_log('--- Get User Calendar State ---');
    $s_state = 'WORLD';

    $a_options  = get_option('taxonomy_' . $i_school);
    $s_country  = isset($a_options['school_country']) ? trim($a_options['school_country']) : NULL;
    $s_optstate = isset($a_options['school_state']) ? trim($a_options['school_state']) : NULL;

    if (($a_options['school_state']) == 'NZ') {
        $s_country = 'NZ';
    }

    $a_states = array(
        'VIC' => 'Australia/Melbourne',
        'NSW' => 'Australia/Sydney',
        'QLD' => 'Australia/Brisbane',
        'SA'  => 'Australia/Adelaide',
        'WA'  => 'Australia/Perth',
        'NT'  => 'Australia/Darwin',
        'ACT' => 'Australia/Canberra',
        'TAS' => 'Australia/Hobart'
    );

    error_log('User School Country:' . $s_country);
    //Modify State Value if AU||NZ School
    switch ($s_country) {
        case 'AU':
            $o_school = wushka_get_school_term_user($i_school);
            $s_state  = trim(get_user_meta($o_school->ID, 'shipping_state', TRUE));
            break;
        case 'NZ':
            $s_state = 'NZ';
            break;
        default:
            if (!isset($s_country) && (isset($s_optstate) && array_key_exists($s_optstate, $a_states))) {
                $s_state = $s_optstate;
            }
    }

    error_log('School #' . $i_school . ' Using Calendar WHERE State = ' . $s_state);

    return $s_state;
}

function wushka_get_calendar_events($s_state = 'NSW', $i_school = NULL)
{
    global $wpdb;

    if (empty($s_state)) {
        $s_state = 'NSW';
    }

    $s_calendar = $wpdb->prefix . 'spidercalendar_calendar';
    $s_event    = $wpdb->prefix . 'spidercalendar_event';

    $a_params   = array();
    $a_params[] = trim(strtoupper($s_state));

    //error_log('Get Calendar for State: ' . $s_state);

    //Get This Years Events For This State
    $s_query = 'SELECT * FROM ' . $s_event . ' WHERE calendar = (' .
        'SELECT id FROM ' . $s_calendar . ' WHERE title = %s ' .
        ')';

    //Add School Specific Events
    if (isset($i_school)) {
        $s_query .= ' AND userID IN ("", %d) ';
        $a_params[] = $i_school;
    } else {
        $s_query .= ' AND userID = "" ';
    }

    $s_query .= ' ORDER BY userID DESC;';

    error_log('calendar query ' . $s_query);
    $a_results = $wpdb->get_results(
        $wpdb->prepare($s_query, $a_params)
    );

    $a_events = array();

    if (isset($a_results) && !empty($a_results)) {
        $a_terms   = array();
        $a_default = array();
        $a_year    = array(
            1 => NULL,
            2 => NULL,
            3 => NULL,
            4 => NULL,
        );

        //error_log('Process Event Results:');
        foreach ($a_results as $i_key => $o_event) {
            $i_cat  = (int)$o_event->category;
            $d_year = new DateTime($o_event->date);
            $i_year = (int)$d_year->format('Y');

            //For every unique year, add an array of empty categories
            if (!array_key_exists($i_year, $a_terms)) {
                $a_terms[$i_year] = $a_year;
            }

            //Public Holidays don't need to be processed, add to event array

            if ($i_cat == 5) {
                //error_log('Holiday: Category ' . $i_cat . ' Year ' . $i_year);
                $a_events[] = $o_event;
                continue;
            }

            if (isset($o_event->userID) && !empty($o_event->userID)) {
                //error_log('User: Category ' . $i_cat . ' Year ' . $i_year);
                $a_terms[$i_year][$i_cat] = $o_event;
            } else {
                //error_log('Default: Category ' . $i_cat . ' Year ' . $i_year);
                $a_default[$i_year][$i_cat] = $o_event;
            }
        }

        if (!empty($a_terms)) {
            foreach ($a_terms as $i_year => $a_rows) {
                if (!empty($a_rows)) {
                    foreach ($a_rows as $i_cat => $o_event) {
                        if ($i_cat == 5) {
                            continue;
                        }
                        if (!isset($o_event) || empty($o_event)) {
                            if (isset($a_default[$i_year][$i_cat]) && !empty($a_default[$i_year][$i_cat])) {
                                $a_terms[$i_year][$i_cat] = $a_default[$i_year][$i_cat];
                            }
                        }
                    }
                }
            }
        } else {
            $a_terms = $a_default;
        }

        //error_log('---------------------------------');
        //error_log('Processed Event Results:');

        if (!empty($a_terms)) {
            foreach ($a_terms as $i_year => $a_rows) {
                if (!empty($a_rows)) {
                    foreach ($a_rows as $i_cat => $o_event) {
                        if (isset($o_event) && !empty($o_event)) {
                            //error_log('Event '.$o_event->id.': Category ' . $i_cat . ' Year - ' . $i_year.' User - '.$o_event->userID);
                            $a_events[] = $o_event;
                        }
                    }
                }
            }
        }
    } else {
        error_log('Warning: DB Query found No matching Calendar Events');
    }


    if (empty($a_events)) {
        error_log('Warning: No Calendar events were found for this school (id=' . $i_school . ')');
    }

    return $a_events;
}

function wushka_get_school_term_user($i_term = NULL)
{
    if (!isset($i_term)) {
        return NULL;
    }
    global $wpdb;

    $a_params = array(
        $wpdb->prefix . 'capabilities',
        '%school%',
        $i_term
    );

    $s_query = 'SELECT ID FROM ' . $wpdb->prefix . 'users u ' .
        'LEFT JOIN ' . $wpdb->prefix . 'term_relationships t ON u.ID = t.object_id ' .
        'WHERE u.ID IN ( ' .
        'SELECT user_id from ' . $wpdb->prefix . 'usermeta WHERE meta_key = %s AND meta_value LIKE %s ' .
        ') ' .
        'AND t.term_taxonomy_id = %d;';

    $i_user = $wpdb->get_var(
        $wpdb->prepare($s_query, $a_params)
    );

    if (isset($i_user)) {
        $o_user = new WP_User($i_user);
        if ($o_user->exists()) {
            return $o_user;
        }
    }

    return NULL;
}

function wushka_update_school_timezones($i_offset = 0, $i_limit = 100)
{
    global $wpdb;

    error_log('-----TIMEZONE UPDATE-----');

    $a_states = array(
        'VIC' => 'Australia/Melbourne',
        'NSW' => 'Australia/Sydney',
        'QLD' => 'Australia/Brisbane',
        'SA'  => 'Australia/Adelaide',
        'WA'  => 'Australia/Perth',
        'NT'  => 'Australia/Darwin',
        'ACT' => 'Australia/Canberra',
        'TAS' => 'Australia/Hobart'
    );

    $s_query = 'SELECT t.object_id as user_id, t.term_taxonomy_id, o.option_value FROM ' . $wpdb->prefix . 'term_relationships t ' .
        'LEFT JOIN ' . $wpdb->prefix . 'options o ON o.option_name = concat(\'taxonomy_\',t.term_taxonomy_id) ' .
        'WHERE object_id IN ( ' .
        'SELECT user_id FROM ' . $wpdb->prefix . 'usermeta WHERE meta_key = \'' . $wpdb->prefix . 'capabilities\' AND meta_value LIKE \'%school%\' ' .
        ') AND term_taxonomy_id IN ( ' .
        'SELECT term_id FROM ' . $wpdb->prefix . 'term_taxonomy WHERE taxonomy = \'school\' ' .
        ') AND o.option_value LIKE \'%school_state"%\' LIMIT ' . $i_offset . ',' . $i_limit . ';';

    $a_results = $wpdb->get_results($s_query);

    if (isset($a_results) && !empty($a_results)) {
        $i_count = 0;
        $a_zones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        foreach ($a_results as $i_key => $o_result) {
            $a_options  = unserialize($o_result->option_value);
            $s_timezone = 311;
            $s_tz       = $a_states[trim(strtoupper($a_options['school_state']))];
            foreach ($a_zones as $i_zone => $s_zone) {
                if ($s_tz == $s_zone) {
                    $s_timezone = trim($i_zone);
                    break;
                }
            }

            if ($a_options['school_tz'] !== $s_timezone) {
                $i_count++;
                error_log('Update #' . $i_count . ' - Previous TZ: ' . $a_options['school_tz'] . ', New TZ ' . $s_timezone . ' (' . $a_options['school_state'] . ')');
                $a_options['school_tz']    = $s_timezone;
                $a_options['school_state'] = trim(strtoupper($a_options['school_state']));
                update_option('taxonomy_' . $o_result->term_taxonomy_id, $a_options);
            }
        }
    }

    unset($a_results);

    error_log('-----END TIMEZONE UPDATE-----');

    return TRUE;
}

function wushka_get_school_timezone($i_school = NULL)
{
    $s_timezone = 'UTC';
    //#TODO: VERIFY THIS WORKS WITH STRING TIMEZONE VALUE

    if (isset($i_school) && !empty($i_school)) {
        $a_options = get_option('taxonomy_' . $i_school);
        if ($a_options !== FALSE && !empty($a_options)) {
            $i_tz = $a_options['school_tz'];

            $a_zones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
            foreach ($a_zones as $i_zone => $s_zone) {
                if ((int)$i_tz == (int)$i_zone) {
                    $s_timezone = trim($s_zone);
                }
            }
            error_log('School ' . $i_school . ' TimeZone = ' . $s_timezone);
        }
    }

    return $s_timezone;
}

function wushka_is_time_school_hours($s_time = NULL, $a_events = array())
{
    if (!isset($s_time) || empty($a_events)) {
        if (!isset($s_time)) {
            error_log('Is Time in School Hours - Missing Time String');
        }
        if (empty($a_events)) {
            error_log('Is Time in School Hours - No Calendar Events Found');
        }

        return FALSE;
    }

    $d_time      = new DateTime($s_time);
    $s_date      = $d_time->format('Y-m-d');
    $i_time_year = (int)$d_time->format('Y');

    //If the Day of the passed time is a Saturday/Sunday
    //Time IS NOT school hours
    $s_day = $d_time->format('l');
    if ($s_day == 'Saturday' || $s_day == 'Sunday') {
        return FALSE;
    }
    //Does the time parameter occur within a stored event?
    foreach ($a_events as $i_key => $o_event) {
        $d_start = new DateTime($o_event->date);
        $s_start = $d_start->format('Y-m-d');
        $d_end   = new DateTIme($o_event->date_end);
        $d_end->modify('+1 day');
        $s_end  = $d_end->format('Y-m-d');
        $i_year = (int)$d_start->format('Y');

        //Remove Any Events that don't have same year as this time
        if ($i_year !== $i_time_year) {
            unset($a_events[$i_key]);
            continue;
        }

        if ($d_time < $d_start || $d_time >= $d_end) {
            unset($a_events[$i_key]);
            continue;
        }
    }

    $i_time = (int)$d_time->format('Gi');

    foreach ($a_events as $i_key => $o_event) {
        $d_start = new DateTime($o_event->date);
        $i_year  = (int)$d_start->format('Y');
    }

    foreach ($a_events as $i_key => $o_event) {
        //If No Event was found, or the event is a public holiday, return FALSE
        if ((int)$o_event->category == 5) {
            return FALSE;
        }

        //Event Was Found, Does time parameter occur within the event time range?
        $a_time = explode('-', $o_event->time);
        $d_open = new DateTime($a_time[0]);
        $i_open = (int)$d_open->format('Gi');

        $d_close = new DateTime($a_time[1]);
        $i_close = (int)$d_close->format('Gi');

        //Time Occurred During School Hours, return TRUE
        if ($i_time >= $i_open && $i_time <= $i_close) {
            return TRUE;
        }
    }

    return FALSE;
}

function wushka_is_date_in_current_year($s_time = NULL)
{
    if (!isset($s_time) || empty($s_time)) {
        return FALSE;
    }

    //Get Date Of 10 Jan of Current Year
    $tCurrent = date('Y');
    $dCurrent = strtotime('01 January ' . $tCurrent);

    $dNow = strtotime($s_time);

    if ($dNow >= $dCurrent) {
        return TRUE;
    }

    return FALSE;
}

function wushka_load_school_event($a_data)
{
    require_once('functions/school-events/class_school-events.php');
    $c_events = new School_Events();

    return $c_events->save_event($a_data);
}

function wushka_user_login_events($o_user = NULL, $s_type = 'logged in', $sso_login = NULL)
{
    if (!isset($o_user)) {
        return FALSE;
    }

    $b_status = FALSE;

    $school    = wp_get_object_terms($o_user->ID, 'school');
    $school_id = NULL;

    if (!is_wp_error($school)) {
        if (isset($school[0]->term_taxonomy_id)) {
            $school_id = $school[0]->term_taxonomy_id;
        }
    }

    $a_event = array(
        'school_id'   => $school_id,
        'event_type'  => NULL,
        'sub_type'    => NULL,
        'action'      => $s_type,
        'description' => NULL,
        'meta_value'  => $o_user->ID,
        'sso_login'   => $sso_login
    );

    if (user_can($o_user->ID, 'teacher')) {
        $a_event['event_type']  = 'teacher';
        $a_event['sub_type']    = 'teacher';
        $a_event['description'] = 'Teacher: ' . $o_user->first_name . ' ' . $o_user->last_name . ' ' . $s_type;
        $b_status               = wushka_load_school_event($a_event);
    } else if (user_can($o_user->ID, OPEN_HOUSE_CUSTOMER)) {

        $a_event['event_type']  =  OPEN_HOUSE_CUSTOMER;
        $a_event['sub_type']    = OPEN_HOUSE_CUSTOMER;
        $a_event['description'] = 'Open House Customer: ' . $o_user->first_name . ' ' . $o_user->last_name . ' ' . $s_type;
        $b_status               = wushka_load_school_event($a_event);
    } else if (user_can($o_user->ID, 'parent')) {
        $a_event['event_type']  = 'admin';
        $a_event['sub_type']    = 'parent';
        $a_event['description'] = 'Parent: ' . $o_user->first_name . ' ' . $o_user->last_name . ' ' . $s_type;
    } else if (user_can($o_user->ID, 'student')) {
        $a_event['event_type']  = 'student';
        $a_event['sub_type']    = 'student';
        $o_class                = wushka_get_class($o_user->class);
        $school_id              = $o_class->school_id;
        $a_event['school_id']   = $school_id;
        $a_event['description'] = 'Student: ' . $o_user->first_name . ' ' . $o_user->last_name . ' ' . $s_type;
        $b_status               = wushka_load_school_event($a_event);
    } else if (user_can($o_user->ID, 'school')) {
        $a_event['event_type']  = 'admin';
        $a_event['sub_type']    = 'admin';
        $a_event['description'] = 'Admin: ' . $o_user->first_name . ' ' . $o_user->last_name . ' ' . $s_type;
    }

    return $b_status;
}

function get_wushka_timezones()
{
    //#TODO: VERIFY THIS WORKS WITH STRING TIMEZONE VALUE
    $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

    return $tzlist;
}

//Schools Taxonomy TimeZone MetaField
// Add term page
function wushka_schools_add_new_meta_field()
{
    //#TODO: VERIFY THIS WORKS WITH STRING TIMEZONE VALUE
    $tzlist  = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
    $aStates = array(
        'VIC'   => 'Victoria',
        'NSW'   => 'New South Wales',
        'QLD'   => 'Queensland',
        'NT'    => 'Northern Territory',
        'TAS'   => 'Tasmania',
        'WA'    => 'Western Australia',
        'ACT'   => 'Australian Capital Territory',
        'SA'    => 'South Australia',
        'NZ'    => 'New Zealand',
        'WORLD' => 'International'
    );

    ?>
    <div class="form-field">
        <label for="term_meta[school_state]"><?php _e('Default Calendar', 'wushka'); ?></label>

        <p class="description"><?php _e('Set School Calendar Category (AUS state/Nz/World)', 'wushka'); ?></p>
        <select name="term_meta[school_state]" id="term_meta[school_state]">
            <?php
            foreach ($aStates as $sKey => $sState) {
                echo '<option id="' . $sKey . '" value="' . $sKey . '">' . $sState . '</option>';
            }
            ?>
        </select>
    </div>

    <div class="form-field">
        <label for="term_meta[school_tz]"><?php _e('Set School Timezone', 'wushka'); ?></label>

        <p class="description"><?php _e('Set the Timezone of your school', 'wushka'); ?></p>
        <select name="term_meta[school_tz]" id="term_meta[school_tz]">
            <?php
            foreach ($tzlist as $i_key => $tz) {
                echo '<option id="tz-' . $i_key . '" value="' . $i_key . '">' . $tz . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="form-field">
        <label for="term_meta[school_latitude]"><?php _e('Latitude', 'wushka'); ?></label>

        <p class="description"><?php _e('Set the Latitude of your school', 'wushka'); ?></p>
        <input type="text" id="term_meta[school_latitude]" name="term_meta[school_latitude]" value="" />
    </div>
    <div class="form-field">
        <label for="term_meta[school_longitude]"><?php _e('Longitude', 'wushka'); ?></label>

        <p class="description"><?php _e('Set the Longitude of your school', 'wushka'); ?></p>
        <input type="text" id="term_meta[school_longitude]" name="term_meta[school_longitude]" value="" />
    </div>

    <?php
    $generate_school_account = false;

    $form_field_style = '';
    $disabled = '';
    $cover_button_layer = '';
    $input_id = 'generate-school-account-links';
    if (!$generate_school_account) {
        $form_field_style = 'style="position: relative; opacity: 0.8"';
        $input_id = '';
        $disabled = 'disabled="disabled"';
        $cover_button_layer = '<div style="display: block; height: 35px; position: absolute; top: 18px; width: 100%; z-index: 999; cursor: not-allowed; "></div>';
    }
    ?>
    <div class="form-field" <?= $form_field_style; ?>>
        <p>Loading School User Creation Procedure</p>
        <input type="button" id="<?= $input_id; ?>" value="Generate School Account" <?= $disabled; ?> />
        <?= $cover_button_layer; ?>
        <p>Number of Schools Already Linked: <?php echo count(get_terms('school', array(
                                                    'orderby' => 'slug',
                                                    'order'   => 'ASC'
                                                ))); ?>
    </div>
<?php
}

add_action('school_add_form_fields', 'wushka_schools_add_new_meta_field', 10, 2);

function wushka_schools_edit_meta_field($term)
{
    //#TODO: VERIFY THIS WORKS WITH STRING TIMEZONE VALUE
    // put the term ID into a variable
    $t_id = $term->term_id;

    // retrieve the existing value(s) for this meta field. This returns an array
    $school_meta = get_option("taxonomy_$t_id");
    $tzlist      = get_wushka_timezones();

    $aStates = array(
        'VIC'   => 'Victoria',
        'NSW'   => 'New South Wales',
        'QLD'   => 'Queensland',
        'NT'    => 'Northern Territory',
        'TAS'   => 'Tasmania',
        'WA'    => 'Western Australia',
        'ACT'   => 'Australian Capital Territory',
        'SA'    => 'South Australia',
        'NZ'    => 'New Zealand',
        'WORLD' => 'International'
    );

?>
    <tr class="form-field">
        <th scope="row" valign="top">
            <label for="term_meta[school_state]"><?php _e('Default Calendar', 'wushka'); ?></label>
        </th>
        <td>
            <p class="description"><?php _e('Set School Calendar Category (AUS state/Nz/World)', 'wushka'); ?></p>
            <select name="term_meta[school_state]" id="term_meta[school_state]">
                <?php
                foreach ($aStates as $sKey => $sState) {
                    $s_class = NULL;
                    if ($sKey == $school_meta['school_state']) {
                        $s_class = 'selected="selected"';
                    }
                    echo '<option id="' . $sKey . '" value="' . $sKey . '" ' . $s_class . '>' . $sState . '</option>';
                }
                ?>
            </select>
        </td>
    </tr>

    <tr class="form-field">
        <th scope="row" valign="top">
            <label for="term_meta[school_tz]"><?php _e('Set School Timezone', 'wushka'); ?></label>
        </th>
        <td>
            <p class="description"><?php _e('Set the Timezone of your school', 'wushka'); ?></p>
            <select name="term_meta[school_tz]" id="term_meta[school_tz]">
                <?php
                foreach ($tzlist as $i_key => $tz) {
                    $s_class = NULL;
                    if ($i_key == $school_meta['school_tz']) {
                        $s_class = 'selected="selected"';
                    }
                    echo '<option id="tz-' . $i_key . '" value="' . $i_key . '" ' . $s_class . '>' . $tz . '</option>';
                }
                ?>
            </select>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top">
            <label for="term_meta[school_latitude]"><?php _e('Latitude', 'wushka'); ?></label>
        </th>
        <td>
            <input type="text" id="term_meta[school_latitude]" name="term_meta[school_latitude]" value="<?php echo $school_meta['school_latitude']; ?>" />

            <p class="description"><?php _e('Set the Latitude of your school', 'wushka'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top">
            <label for="term_meta[school_longitude]"><?php _e('Longitude', 'wushka'); ?></label>
        </th>
        <td>
            <input type="text" id="term_meta[school_longitude]" name="term_meta[school_longitude]" value="<?php echo $school_meta['school_longitude']; ?>" />

            <p class="description"><?php _e('Set the Longitude of your school', 'wushka'); ?></p>
        </td>
    </tr>
<?php
}

add_action('school_edit_form_fields', 'wushka_schools_edit_meta_field', 10, 2);


function wushka_generate_school_links_ajax()
{
    global $current_user;
    $current_user = wp_get_current_user();

    $s_validate = json_decode(stripcslashes(filter_input(INPUT_POST, 'validate')), TRUE);

    include_once('functions/school_account-term-link.php');
    error_log('-----Performing Generate Schools Ajax-----');

    $b_status = generate_school_accounts($current_user->ID, $s_validate);
    echo json_encode($b_status);
    exit();
}

add_action('wp_ajax_wushka_generate_school_links', 'wushka_generate_school_links_ajax');

// Save extra taxonomy fields callback function.
function save_wushka_school_custom_meta($term_id)
{
    //#TODO: VERIFY THIS WORKS WITH STRING TIMEZONE VALUE
    if (isset($_POST['term_meta'])) {
        $t_id      = $term_id;
        $term_meta = get_option("taxonomy_$t_id");
        $cat_keys  = array_keys($_POST['term_meta']);
        foreach ($cat_keys as $key) {
            if (isset($_POST['term_meta'][$key])) {
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }

        if ($term_meta['school_state'] == 'NZ') {
        }

        // Save the option array.
        update_option("taxonomy_$t_id", $term_meta);
    }
}

add_action('edited_school', 'save_wushka_school_custom_meta', 10, 2);
add_action('create_school', 'save_wushka_school_custom_meta', 10, 2);

/* ----- CRON JOB FOR ACTIVATION EMAIL WINDOW ----- */
if (!wp_next_scheduled('check_exp_pwds')) {
    wp_schedule_event(time(), 'weekly', 'check_exp_pwds');
}
if (!wp_next_scheduled('remind_inactive_teachers')) {
    wp_schedule_event(time(), 'daily', 'remind_inactive_teachers');
}
// error_log('check-exps_pwds ' . wp_next_scheduled('check_exp_pwds'));
// error_log('remind_inactive_teachers ' . wp_next_scheduled('remind_inactive_teachers'));

function wushka_remove_expired_accounts()
{
    global $wpdb;
    $t_now = strtotime('Y-m-d', 'NOW');

    $s_table = $wpdb->prefix . 'usermeta';

    $a_params[] = 'tmp_pwd_verify';
    $a_params[] = 'tmp_pwd_window';
    $a_params[] = $t_now;

    $s_query = 'SELECT * FROM ' . $s_table . ' WHERE meta_key = %s AND user_id IN ( ' .
        'SELECT user_id FROM ' . $s_table . ' WHERE meta_key = %s AND meta_value > %s ' .
        ') ORDER BY user_id, meta_key';

    $a_results = $wpdb->get_results(
        $wpdb->prepare($s_query, $a_params)
    );
    if (isset($a_results) && !empty($a_results)) {
        foreach ($a_results as $idx => $o_row) {
            delete_user_meta($o_row->ID, 'tmp_pwd_verify');
            delete_user_meta($o_row->ID, 'tmp_pwd_window');
        }
    }
}

add_action('check_exp_pwds', 'wushka_remove_expired_accounts');

function wushka_remind_inactive_teachers()
{
    // error_log('-------------SEND REMINDER EMAILS--------------');
    // $d_now = new DateTime('NOW');
    // $d_now->setTimeZone(new DateTimeZone('UTC'));
    // $s_now = $d_now->format('Y-m-d');

    // $s_table = $wpdb->prefix . 'usermeta';

    // $a_params[] = 'tmp_pwd_verify';
    // $a_params[] = 'tmp_pwd_window';
    // $a_params[] = $s_now;

    // $s_query = 'SELECT * FROM ' . $s_table . ' WHERE meta_key = %s AND user_id IN ( ' .
    //     'SELECT user_id FROM ' . $s_table . ' WHERE meta_key = %s AND meta_value > %s ' .
    //     ') ORDER BY user_id, meta_key';

    // $a_results = $wpdb->get_results(
    //     $wpdb->prepare($s_query, $a_params)
    // );

    // //Get Date - 3 days
    // $d_now->modify('- 3 days');
    // $s_three = $d_now->format('Y-m-d');
    // //Get Date - 5 days
    // $d_now->modify('- 2 days');
    // $s_five = $d_now->format('Y-m-d');
    // //Get Date - 7 days
    // $d_now->modify('- 2 days');
    // $s_seven = $d_now->format('Y-m-d');

    // if (isset($a_results) && !empty($a_results)) {
    //     $i_test = 0;
    //     error_log('Reminder Emails: ' . count($a_results) . ' Inactive Teachers');

    //     foreach ($a_results as $idx => $o_row) {
    //         $user     = new WP_User($o_row->user_id);
    //         $b_reminded = FALSE;

    //         if (!isset($user->reminder_email_last) || empty($user->reminder_email_last)) {
    //             continue;
    //         }

    //         $s_last  = date('Y-m-d', strtotime($user->reminder_email_last));
    //         $i_count = (int)$user->reminder_email_count;

    //         error_log('User :' . $user->first_name . ' (' . $user->ID . ')');
    //         error_log('Current Time = ' . $s_now);
    //         error_log('Last Email Sent = ' . $s_last);
    //         error_log('# Emails Sent = ' . $user->reminder_email_count);

    //         if ($i_count == 0) {
    //             if ($s_last <= $s_three) {
    //                 error_log('Three Days Since User Creation: Send Reminder');
    //                 $b_reminded = TRUE;
    //             }
    //         } else if ($i_count == 1) {
    //             if ($s_last <= $s_five) {
    //                 error_log('Five Days Since First Reminder: Send Another');
    //                 $b_reminded = TRUE;
    //             }
    //         } else if ($i_count > 1) {
    //             if ($s_last <= $s_seven) {
    //                 error_log('Seven Days Since Last Reminder: Send Another');
    //                 $b_reminded = TRUE;
    //             }
    //         }

    //         if ($b_reminded === TRUE) {
    //             $teacher_school = wp_get_object_terms($user->ID, 'school');
    //             $school_id      = NULL;
    //             if (isset($teacher_school) && !empty($teacher_school)) {
    //                 $school_id = $teacher_school[0]->term_taxonomy_id;
    //             }

    //             $i_school = wushka_get_school_term_user($school_id);
    //             if (!isset($i_school)) {
    //                 continue;
    //             }
    //             if (!school_has_active_sub($user)) {
    //                 continue;
    //             }

    //             //Send Reminder Email
    //             ob_start();
    //             include('customer-new-account.php');
    //             $message = ob_get_clean();
    //             wp_mail($data['email'], 'Welcome to Wushka', $message, 'Content-Type: text/html; charset=UTF-8');

    //             $i_test++;
    //             $i_new = $i_count++;
    //             //Update Reminder Email Fields
    //             update_user_meta($user->ID, 'reminder_email_last', $s_now);
    //             update_user_meta($user->ID, 'reminder_email_count', $i_new);

    //             //Submit notification to school event list
    //             $a_event_args = array(
    //                 'school_id'   => (int)$school_id,
    //                 'event_type'  => 'admin',
    //                 'sub_type'    => 'teacher',
    //                 'action'      => 'reminder',
    //                 'description' => 'Activation reminder email no. ' . $i_new . ' sent to ' . ucwords($user->first_name . ' ' . $user->last_name),
    //                 'meta_value'  => $user->ID
    //             );

    //             wushka_load_school_event($a_event_args);
    //         }

    //         unset($user, $b_reminded, $i_count);
    //     }

    //     error_log('Sending Reminder Emails to :' . $i_test . ' Users');
    // }
    // error_log('----------------------------------------------');
}

add_action('remind_inactive_teachers', 'wushka_remind_inactive_teachers');

// Remove emoji.js built into wordpress 4.2
function disable_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    //add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
}

add_action('init', 'disable_emojis');

/*----- UPDATE SCHOOL USERS TAX OPTIONS WITH ID HAS ----- */
function wushka_school_hash_to_tax_options()
{
    $a_args    = array('role' => 'school');
    $a_query   = new WP_User_Query($a_args);
    $a_schools = $a_query->get_results();
    if (!empty($a_schools)) {
        foreach ($a_schools as $i_id => $o_user) {
            $school   = wp_get_object_terms($o_user->ID, 'school');
            $i_school = $school[0]->term_id;
            if (isset($i_school) && !empty($i_school)) {
                $a_options = get_option('taxonomy_' . $i_school);
                if (isset($o_user->id_hash) && !empty($o_user->id_hash)) {
                    $a_options['school_hash'] = $o_user->id_hash;
                    update_option('taxonomy_' . $i_school, $a_options);
                }
            }
        }
    }
}

//Includes Functions for Caroursel/Free Sample Panels
include_once 'functions/wushka_carousels.php';

// actions for zapier
function wushka_statistics()
{
    error_log('adding wushka statistics cron');
    if (!wp_next_scheduled('wushka_daily_statistics')) {
        wp_schedule_event('1447599600', 'hourly', 'wushka_daily_statistics');
    }
}

add_action('after_switch_theme', 'wushka_statistics');

add_filter('rest_authentication_errors', function ($result) {
    if (!empty($result)) {
        return $result;
    }
    if (!is_user_logged_in() && $_SERVER['REQUEST_URI'] !== "/wp-json/jwt-auth/v1/token" && $_SERVER['REQUEST_URI'] !== "/wp-json/jwt-auth/v1/token/validate") {
        return new WP_Error('restx_logged_out', 'Sorry, you must be logged in to make a request.', array('status' => 401));
    }

    return $result;
});


function wushka_get_class_cookies()
{
    if (!isset($_SESSION)) {
        session_start();
    }

    $a_cookies = array(
        'id'      => NULL,
        'type'    => 'class',
        'student' => NULL
    );

    //Get Class ID from Session Variable
    if (isset($_SESSION['class_id']) && !empty($_SESSION['class_id'])) {
        $a_cookies['id'] = (int)$_SESSION['class_id'];
    }

    //Get Class type from Session Variable
    if (isset($_SESSION['class_archive']) && !empty($_SESSION['class_archive'])) {
        $a_cookies['type'] = $_SESSION['class_archive'];
    }

    //Get Active User From Session Variable
    if (isset($_SESSION['class_student']) && !empty($_SESSION['class_student'])) {
        $a_cookies['student'] = $_SESSION['class_student'];
    }

    return $a_cookies;
}

//Sorting Last Name
function sort_user_by_lastname($oUser1, $oUser2)
{
    if ($cmp = strnatcasecmp($oUser1->last_name, $oUser2->last_name)) {
        return $cmp;
    }

    return strnatcasecmp($oUser1->first_name, $oUser2->first_name);
}


function author_page_redirect()
{
    if (is_author()) {
        wp_redirect(home_url());
    }
}

add_action('template_redirect', 'author_page_redirect');

// stop stupid WP queries using slow commands
if (!function_exists('wushka_set_no_found_rows')) :
    function wushka_set_no_found_rows(\WP_Query $wp_query)
    {
        $wp_query->set('no_found_rows', true);
    }
endif;
add_filter('pre_get_posts', 'wushka_set_no_found_rows', 10, 1);
if (!function_exists('wushka_set_found_posts')) :
    function wushka_set_found_posts($clauses, \WP_Query $wp_query)
    {

        // Don't proceed if it's a singular page.
        if ($wp_query->is_singular()) {
            return $clauses;
        }

        global $wpdb;

        // Check if they're set.
        $where = isset($clauses['where']) ? $clauses['where'] : '';
        $join = isset($clauses['join']) ? $clauses['join'] : '';
        $distinct = isset($clauses['distinct']) ? $clauses['distinct'] : '';

        // Construct and run the query. Set the result as the 'found_posts'
        // param on the main query we want to run.
        $wp_query->found_posts = $wpdb->get_var("SELECT $distinct COUNT(*) FROM {$wpdb->posts} $join WHERE 1=1 $where");

        // Work out how many posts per page there should be.
        $posts_per_page = (!empty($wp_query->query_vars['posts_per_page']) ? absint($wp_query->query_vars['posts_per_page']) : absint(get_option('posts_per_page')));

        // Set the max_num_pages.
        $wp_query->max_num_pages = ceil($wp_query->found_posts / $posts_per_page);

        // Return the $clauses so the main query can run.
        return $clauses;
    }
endif;
add_filter('posts_clauses', 'wushka_set_found_posts', 10, 2);

function wushka_get_class_login($class_id, $type, $active = 1)
{

    error_log('Get Students class: ' . $class_id);
    $a_students = wushka_get_students($class_id, $type, $active);

    $a_page = [];
    if (isset($a_students) && !empty($a_students)) {
        $sort_last_name = usort($a_students, "sort_user_by_lastname");

        foreach ($a_students as $idx => $student) {
            $name = $student->first_name . " " . $student->last_name;
            if (!empty(trim($name))) {
                $a_page[] = '<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">';
                $a_page[] = '<div id="' . $student->id_hash . '" class="text-center student-box btn btn-class-student-login grow">';
                $a_page[] = '<p>' . $name . '</p>';
                $a_page[] = get_avatar($student->id_hash, 100);
                $a_page[] = '</div></div>';
            }
        }
    }

    return implode('', $a_page);
}

function wushka_generate_sidebar_content($students)
{
    if (!isset($students)) {
        return NULL;
    }
    if (!isset($_SESSION)) {
        session_start();
    }

    error_log('Creating SideBar');
    error_log('Class: ' . count($students) . ' Students');

    $a_students = array();

    /* Sorting By Last Name */
    $sort_last_name = usort($students, "sort_user_by_lastname");

    foreach ($students as $i_key => $o_student) {
        $student_id = $o_student->ID;
        $s_status = NULL;
        if (isset($_SESSION['class_student'])) {
            if ($_SESSION['class_student'] == $o_student->id_hash) {
                $s_status = 'active';
                error_log('ACTIVE SESSION STUDENT: ' . $o_student->first_name . ' ' . $o_student->last_name);
            }
        } else if ($i_key == 0) {
            $s_status = 'active';
        }

        $a_student[] = '<a href="#" class="list-group-item list-student ' . $s_status . '" data-id="' . $o_student->id_hash . '">';
        $a_student[] = '<td class="student_details">';
        //$a_student[] = wp_nonce_field('student_details_nonce_' . $o_student->id_hash, '_student_wpn', FALSE, FALSE);
        $a_student[] = '<input type="hidden" name="_student_wpn" class="_student_wpn" value="' . wp_create_nonce('student_details_nonce_' . $o_student->id_hash, '_student_wpn') . '" >';
        $a_student[] = '</td>';
        $a_student[] = $o_student->first_name . ' ' . $o_student->last_name;
        $a_student[] = '</a>';

        $a_students[] = implode('', $a_student);
        unset($a_student);
    }

    return $a_students;
}

function resetStudentAllowedPreparedShelves($studentId)
{

    $allowed_shelves['id']   = 'all';
    $allowed_shelves['name'] = 'All Levels';

    $args  = array(
        'orderby' => 'slug',
        'order'   => 'ASC'
    );
    $terms = get_terms('reading-level', $args);

    foreach ($terms as $idx => $term) {
        $prepared_shelves[] = $term->slug;
    }

    update_user_meta($studentId, 'allowed_shelves', $allowed_shelves);
    update_user_meta($studentId, 'prepared_shelves', $prepared_shelves);
}

function deleteStudentMetaData($studentId, $schoolId)
{

    global $wpdb;
    $quiz_score_table = $wpdb->prefix . 'plugin_slickquiz_scores';
    $bookmark_table = $wpdb->prefix . 'wushka_bookmarks';
    $school_events = $wpdb->prefix . 'wushka_school_events';
    $reading_instance = $wpdb->prefix . 'lessonzone_reading_analytics_reading_instance';

    $wpdb->delete($quiz_score_table, ['createdBy' => $studentId], ['%d']);
    $wpdb->delete($bookmark_table, ['user_id' => $studentId], ['%d']);
    $wpdb->delete($reading_instance, ['user_id' => $studentId], ['%d']);

    if ($schoolId) {

        $wpdb->delete($school_events, ['meta_value' => (string)$studentId, 'school_id' => $schoolId], ['%s', '%d']);
    }


    resetStudentAllowedPreparedShelves($studentId);
}

function resetStudentData($id, $schoolId = false)
{
    wp_set_password('temppwd', $id);
    update_user_meta($id, 'show_user_pwd', 'temppwd');
    delete_user_meta($id, 'reading_level');
    delete_user_meta($id, 'my_reading_group');
    delete_user_meta($id, 'rg_setting');
    //delete_user_meta($id,'multiple_rg');

    deleteStudentMetaData($id, $schoolId);
}

function wushka_get_quiz_results($students)
{
    error_log('collecting quiz results');
    global $wpdb, $current_user;

    $a_params = array();
    $a_reg    = array();

    $results_db_name = $wpdb->prefix . 'plugin_slickquiz_scores';
    $quiz_db_name    = $wpdb->prefix . 'plugin_slickquiz';

    $a_users   = array();

    $i_school = wushka_get_user_school($current_user->ID);
    $o_school = wushka_get_school_term_user($i_school);

    //Get TimeZones For DateTime displaying
    $s_tz         = wushka_get_school_timezone($i_school);

    $tz_utc       = new DateTimeZone('UTC');
    $tz_school    = new DateTimeZone($s_tz);

    //Load School Calendar Events
    $s_state  = wushka_get_school_caldendar_state($i_school);
    $a_events = wushka_get_calendar_events($s_state, $o_school->ID);

    foreach ($students as $idx => $o_user) {
        $a_params[] = $o_user->ID;
        $a_reg[]    = '%d';
        if (isset($o_user->child_link_id) && !empty($o_user->child_link_id)) {
            $a_params[] = $o_user->child_link_id;
            $a_reg[]    = '%d';
        }
        if (isset($o_user->student_link_id) && !empty($o_user->student_link_id)) {
            $a_params[] = $o_user->student_link_id;
            $a_reg[]    = '%d';
        }
        $a_users[$o_user->ID] = $o_user;
    }
    $scoreResults    = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT $results_db_name.id, answers, score, $quiz_db_name.name, $quiz_db_name.id as quiz_id, $results_db_name.createdDate, $results_db_name.createdBy " .
                "FROM $results_db_name LEFT JOIN $quiz_db_name on $quiz_db_name.id = $results_db_name.quiz_id " .
                "WHERE $results_db_name.createdBy IN (" . implode(',', $a_reg) . ") ORDER BY createdDate",
            $a_params
        )
    );
    error_log('... completed collecting quiz results');


    $s_table_body = array();
    foreach ($scoreResults as $iii => $score) {
        //error_log('Quiz  #' . $iii . ' Name: ' . $score->name . ' Time: ' . $score->createdDate);
        //log removed above ^, due to issue with quiz results for large rows

        $dt = new DateTime($score->createdDate, $tz_utc);
        $dt->setTimezone($tz_school);
        $s_hours = 'home';

        if (wushka_is_time_school_hours($dt->format("l, dS M Y g:ia"), $a_events)) {
            $s_hours = 'school';
        }

        //was this record created during the current school year?
        $s_current = wushka_is_date_in_current_year((string)$score->createdDate) ? 'current' : 'all';

        $answers  = json_decode($score->answers);
        $scoreRow = "";
        $score_id = $a_users[$score->createdBy]->id_hash;
        $scoreRow .= '<tr data-quiz-id="quiz-row-' . $score_id . '" style="display:none;" data-current="' . $s_current . '" data-hours="' . $s_hours . '">';
        $scoreRow .= '<td class="table-quiz" data-title="Quiz">' . $score_id . '</td>';
        $scoreRow .= '<td class="table-quiz" data-title="View">' . $s_current . '</td>';
        $scoreRow .= '<td class="table-date" data-title="Date">' . $dt->format("l - dS M Y") . '</td>';
        $scoreRow .= '<td class="table-date" data-title="Time">' . $dt->format("H:i:s") . '</td>';
        $scoreRow .= '<td class="table-title" data-title="Title">' . $score->name . '</td>';
        $scoreRow .= '<td class="table-score" data-title="Score">' . $score->score . '</td>';

        for ($i = 0; $i <= 4; $i++) {
            $i_no = $i + 1;
            if (isset($answers[$i])) {
                $o_answer = $answers[$i];
                $scoreRow .= '<td class="table-answer ' . $o_answer->valid . '" data-title="Question ' . $i_no . '">' . ucfirst($o_answer->valid) . '</td>';
            } else {
                $scoreRow .= '<td class="table-answer answer-na" data-title="Question ' . $i_no . '">N/A</td>';
            }
        }

        /* =================================================
        *
        *   Get reading analytics
        *
        ====================================================*/
        //Get post id
        $postmeta = $wpdb->prefix . 'postmeta';
        $post_id =  $wpdb->get_results(
            $wpdb->prepare('SELECT `post_id` FROM ' . $postmeta . ' WHERE `meta_key` = %s AND `meta_value` = %d', "wushka_quiz_id", $score->quiz_id)
        );
        $post_id = $post_id[0]->post_id;

        //Get resource id
        $resource_id_query = 'SELECT pm.meta_value FROM ' . $wpdb->prefix . 'posts p ' .
            'LEFT JOIN ' . $wpdb->prefix . 'postmeta pm ' .
            'ON pm.post_id = p.ID ' .
            'WHERE p.ID = %d AND p.post_type = "ebook" AND p.post_status = "publish" AND `meta_key` = "esiss_resource_id"';
        $resource_id_results = $wpdb->get_results(
            $wpdb->prepare($resource_id_query, $post_id)
        );
        $resource_id = $resource_id_results[0]->meta_value;

        //Get analytics for resource id
        $analytics_table = $wpdb->prefix . 'lessonzone_reading_analytics_reading_instance';
        $analytics_query = 'SELECT `duration` FROM ' . $analytics_table . ' WHERE ' .
            '`created` < %s AND `completed` = 1 AND `essis_resource_id` = %d AND `user_id` = %d ORDER BY `created` DESC LIMIT 1';
        $analytics_results = $wpdb->get_results(
            $wpdb->prepare($analytics_query, $dt->format('Y-m-d H:i:s'), $resource_id, $a_users[$score->createdBy]->ID)
        );


        if (empty($analytics_results)) {
            $analytics_query = 'SELECT `duration` FROM ' . $analytics_table . ' WHERE ' .
                '`created` > %s AND `completed` = 1 AND `essis_resource_id` = %d AND `user_id` = %d ORDER BY `created` DESC LIMIT 1';
            $analytics_results = $wpdb->get_results(
                $wpdb->prepare($analytics_query, $dt->format('Y-m-d H:i:s'), $resource_id, $a_users[$score->createdBy]->ID)
            );
        }

        $duration = "0 seconds";
        if (!empty($analytics_results)) {
            $duration = calculate_time_duration($analytics_results[0]->duration);
        }

        $scoreRow .= '<td class="table-time-spent" data-title="Spent">' . $duration . '</td>';
        /* =================================================
        *
        *   Reading analytics ends here
        *
        ====================================================*/


        if (!user_can($current_user, 'student')) {
            $scoreRow .= '<td data-title="View Details" class="table-details">';
            $scoreRow .= '<button data-toggle="modal" data-target="#quiz-result-modal" class="btn btn-default btn-quiz-result-details" id="' . $score->id . '" data-quiz="' .  $score->id . '" data-user="' . $score_id . '" type="submit">View</button>';
            $scoreRow .= '</td>';
        }
        $scoreRow .= '</tr>';
        $s_table_body[] = $scoreRow;
    }

    return implode('', $s_table_body);
}


// new 2020 functions
add_action('wp_login_failed', 'custom_login_failed');
function custom_login_failed($username)
{
    $referrer = wp_get_referer();
    if ($referrer && !strstr($referrer, 'wp-login') && !strstr($referrer, 'wp-admin')) {
        if ($referrer == $_POST['redirect'] || empty($_POST['redirect'])) {
            $referrer = $referrer . '/' . 'login/';
        }

        if (isset($_GET['loggedout']) && $_GET['loggedout']) {
            wp_redirect(home_url());
            exit;
        }

        if (!isset($_GET['login']) && sanitize_text_field($_GET['login']) != 'empty') {
            wp_redirect(add_query_arg('login', 'failed', $referrer));
            exit;
        }
    }
}
add_filter('authenticate', 'custom_authenticate_username_password', 30, 3);
function custom_authenticate_username_password($user, $username, $password)
{

    // $blocked_role = 'modernstar_employee'; // change role here

    // if (in_array($blocked_role, (array) $user->roles)) {

    //     $user  = new WP_Error('authentication_failed', __('Login is disabled for your account. Please contact the administrator.'));
    //     return $user;
    // }

    if (is_a($user, 'WP_User')) {
        return $user;
    }

    if (empty($username) || empty($password)) {
        $user  = new WP_Error('authentication_failed', __('<strong>ERROR</strong>: Invalid username or incorrect password.'));

        return $user;
    }
}
function custom_hide_admin_bar_if_non_admin($show)
{
    if (!current_user_can('administrator')) $show = false;
    return $show;
}

add_filter('show_admin_bar', 'custom_hide_admin_bar_if_non_admin', 20, 1);

function school_has_active_sub($user)
{

    global $wpdb;
    $active = FALSE;

    $args   = array('orderby' => 'term_taxonomy_id');
    $school = wp_get_object_terms($user->ID, 'school', $args);
    error_log('authorise login, school ' . print_r($school[0], true));
    if (!isset($school) || empty($school[0])) {
        return false;
    }

    $valid = array(
        'Trial',
        'Subscription',
    );

    $query = 'SELECT * FROM ' . $wpdb->prefix . 'wushka_licence WHERE account_id = "' . $school[0]->slug . '"';
    $results = $wpdb->get_results($query);

    // loop through licences as there could be more than 1
    foreach ($results as $id => $row) {
        $today = new DateTime(date('Y-m-d'));
        $licence_end = new DateTime($row->licence_end);
        if (in_array($row->licence_type, $valid) && $today <= $licence_end) {
            $active = 1;
            $_SESSION['temp_licencing'][] = $row;
        }
    }

    // if (!empty($results)) {
    //     $today = new DateTime(date('Y-m-d'));
    //     $licence_end = new DateTime($results[0]->licence_end);
    //     if (in_array($results[0]->licence_type, $valid) && $today <= $licence_end) {
    //         $active = 1;
    //     }
    // }
    error_log('licencing: ' . print_r($_SESSION['licencing'], true));
    return $active;
}
// redirects
add_action('login_form_lostpassword', 'redirect_to_wushka_lostpassword');
add_action('login_form_rp', 'redirect_to_wushka_password_reset');
add_action('login_form_resetpass', 'redirect_to_wushka_password_reset');
// form handlers
add_action('login_form_lostpassword', 'do_wushka_lostpassword');
add_action('login_form_rp', 'do_wuhska_password_reset');
add_action('login_form_resetpass', 'do_wushka_password_reset');

function redirect_to_wushka_lostpassword()
{
    if ('GET' == $_SERVER['REQUEST_METHOD']) {
        if (is_user_logged_in()) {
            wp_redirect(home_url());
            exit;
        }

        wp_redirect(home_url('forgot-password'));
        exit;
    }
}

function redirect_to_wushka_password_reset()
{
    if ('GET' == $_SERVER['REQUEST_METHOD']) {
        // Verify key / login combo
        $user = check_password_reset_key($_REQUEST['key'], $_REQUEST['login']);
        if (!$user || is_wp_error($user)) {
            if ($user && $user->get_error_code() === 'expired_key') {
                wp_redirect(home_url('login?login=expiredkey'));
            } else {
                wp_redirect(home_url('login?login=invalidkey'));
            }
            exit;
        }

        $redirect_url = home_url('password-reset');
        $redirect_url = add_query_arg('login', esc_attr($_REQUEST['login']), $redirect_url);
        $redirect_url = add_query_arg('key', esc_attr($_REQUEST['key']), $redirect_url);

        wp_redirect($redirect_url);
        exit;
    }
}

function do_wushka_lostpassword()
{
    if ('POST' == $_SERVER['REQUEST_METHOD']) {
        $errors = retrieve_password();
        if (is_wp_error($errors)) {
            // Errors found
            $redirect_url = home_url('lost-password');
            $redirect_url = add_query_arg('errors', join(',', $errors->get_error_codes()), $redirect_url);
        } else {
            // Email sent
            $redirect_url = home_url('login');
            $redirect_url = add_query_arg('checkemail', 'confirm', $redirect_url);
        }

        wp_redirect($redirect_url);
        exit;
    }
}

function do_wushka_password_reset()
{
    if ('POST' == $_SERVER['REQUEST_METHOD']) {
        $rp_key = $_REQUEST['rp_key'];
        $rp_login = $_REQUEST['rp_login'];

        $user = check_password_reset_key($rp_key, $rp_login);

        if (!$user || is_wp_error($user)) {
            if ($user && $user->get_error_code() === 'expired_key') {
                wp_redirect(home_url('login?login=expiredkey'));
            } else {
                wp_redirect(home_url('login?login=invalidkey'));
            }
            exit;
        }

        if (isset($_POST['password_1'])) {
            if ($_POST['password_1'] != $_POST['password_2']) {
                // Passwords don't match
                $redirect_url = home_url('password-reset');

                $redirect_url = add_query_arg('key', $rp_key, $redirect_url);
                $redirect_url = add_query_arg('login', $rp_login, $redirect_url);
                $redirect_url = add_query_arg('error', 'password_reset_mismatch', $redirect_url);

                wp_redirect($redirect_url);
                exit;
            }

            if (empty($_POST['password_1'])) {
                // Password is empty
                $redirect_url = home_url('password-reset');

                $redirect_url = add_query_arg('key', $rp_key, $redirect_url);
                $redirect_url = add_query_arg('login', $rp_login, $redirect_url);
                $redirect_url = add_query_arg('error', 'password_reset_empty', $redirect_url);

                wp_redirect($redirect_url);
                exit;
            }

            $strict_allowed_roles = passwordPolicyAllowedRoles();

            if(!empty($_POST['password_1']) && !empty($user) && array_intersect($strict_allowed_roles, $user->roles) && !is_password_policy_valid($_POST['password_1'])){
                $redirect_url = home_url('password-reset');
                $redirect_url = add_query_arg('key', $rp_key, $redirect_url);
                $redirect_url = add_query_arg('login', $rp_login, $redirect_url);
                $redirect_url = add_query_arg('reset_error', 'invalid_password_policy', $redirect_url);
                wp_redirect($redirect_url);
                exit;
            }


            //die("Password will be reset");

            // Parameter checks OK, reset password
            reset_password($user, $_POST['password_1']);
            wp_redirect(home_url('login?password=changed'));
        } else {
            echo "Invalid request.";
        }

        exit;
    }
}

function get_error_message($error_code)
{
    switch ($error_code) {
        // Login errors

        case 'empty_username':
            return __('You do have an email address, right?', 'personalize-login');

        case 'empty_password':
            return __('You need to enter a password to login.', 'personalize-login');

        case 'invalid_username':
            return __(
                "We don't have any users with that email address. Maybe you used a different one when signing up?",
                'personalize-login'
            );

        case 'incorrect_password':
            $err = __(
                "The password you entered wasn't quite right. <a href='%s'>Did you forget your password</a>?",
                'personalize-login'
            );
            return sprintf($err, wp_lostpassword_url());

            // Registration errors

        case 'email':
            return __('The email address you entered is not valid.', 'personalize-login');

        case 'email_exists':
            return __('An account exists with this email address.', 'personalize-login');

        case 'closed':
            return __('Registering new users is currently not allowed.', 'personalize-login');

        case 'captcha':
            return __('The Google reCAPTCHA check failed. Are you a robot?', 'personalize-login');

            // Lost password

        case 'empty_username':
            return __('You need to enter your email address to continue.', 'personalize-login');

        case 'invalid_email':
        case 'invalidcombo':
            return __('There are no users registered with this email address.', 'personalize-login');

            // Reset password

        case 'expiredkey':
        case 'invalidkey':
            return __('The password reset link you used is not valid anymore.', 'personalize-login');

        case 'password_reset_mismatch':
            return __("The two passwords you entered don't match.", 'personalize-login');

        case 'password_reset_empty':
            return __("Sorry, we don't accept empty passwords.", 'personalize-login');

        default:
            break;
    }

    return __('An unknown error occurred. Please try again later.', 'personalize-login');
}

/* =============================================== 
*
*   Return class id allocated for student
*
*================================================ */
function wp_get_allocated_class($student_id)
{
    // Get classr meta data for $user_id
    $classID = get_user_meta($student_id, 'class');
    return $classID;
}


/* =============================================== 
*
*   Return licence Product of class
*
*================================================ */
function get_class_licence($class_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . "classes";

    $licence_product = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT `licence_product` FROM " . $table_name . " WHERE `id` = %d",
            $class_id
        )
    );
    return $licence_product[0]->licence_product;
}

/* =============================================== 
*
*   Return true if valid
*
*================================================ */
function check_student_licence($userID, $valid, $access)
{
    //get user role
    $user_meta = get_userdata($userID);
    $user_roles = $user_meta->roles;

    if (in_array('student', (array) $user_roles)) {
        //The user has the "student" role 
        $class_licence = get_class_licence(wp_get_allocated_class($userID));

        if (in_array($class_licence, $valid)) {
            $access = true;
        } else {
            $access = false;
        }
    }
    return $access;
}

function hasLevelledAccess()
{
    global $wpdb;
    if (!is_user_logged_in()) {
        return true;
    }

    $valid = array(
        'Wushka Levelled',
        'Wushka Plus',
    );
    $validType = array(
        'Trial',
        'Subscription',
    );

    $user = wp_get_current_user();
    $args   = array('orderby' => 'term_taxonomy_id');
    $school = wp_get_object_terms($user->ID, 'school', $args);

    $access = false;

    $query = 'SELECT * FROM ' . $wpdb->prefix . 'wushka_licence WHERE account_id = "' . $school[0]->slug . '"';
    $licences = $wpdb->get_results($query);

    // loop through licences as there could be more than 1
    foreach ($licences as $id => $licence) {
        $today = new DateTime(date('Y-m-d'));
        $licence_end = new DateTime($licence->licence_end);
        if (in_array($licence->licence_type, $validType) && $today <= $licence_end && in_array($licence->licence_product, $valid)) {
            $access = true;
        }
    }

    if ($access) {
        $access = check_student_licence($user->ID, $valid, $access);
    }

    error_log('has levelled access ' . $access);
    return $access;
}

function hasDecodableAccess()
{
    global $wpdb;
    if (!is_user_logged_in()) {
        return true;
    }

    $valid = array(
        'Wushka Decodables',
        'Wushka Plus',
    );
    $validType = array(
        'Trial',
        'Subscription',
    );

    $user = wp_get_current_user();
    $args   = array('orderby' => 'term_taxonomy_id');
    $school = wp_get_object_terms($user->ID, 'school', $args);

    $access = false;

    $query = 'SELECT * FROM ' . $wpdb->prefix . 'wushka_licence WHERE account_id = "' . $school[0]->slug . '"';
    $licences = $wpdb->get_results($query);

    // loop through licences as there could be more than 1
    foreach ($licences as $id => $licence) {
        $today = new DateTime(date('Y-m-d'));
        $licence_end = new DateTime($licence->licence_end);
        if (in_array($licence->licence_type, $validType) && $today <= $licence_end && in_array($licence->licence_product, $valid)) {
            $access = true;
        }
    }

    if ($access) {
        $access = check_student_licence($user->ID, $valid, $access);
    }

    error_log('has decodable access ' . $access);
    return $access;
}

function hasDecodableAccessOnly()
{
    global $wpdb;

    if (!is_user_logged_in()) {
        return false;
    }

    $valid = array(
        'Wushka Decodables',
        'Wushka Decodable Teacher',
    );
    $validType = array(
        'Trial',
        'Subscription',
    );

    $user = wp_get_current_user();
    $args   = array('orderby' => 'term_taxonomy_id');
    $school = wp_get_object_terms($user->ID, 'school', $args);

    $valid_licence = array();

    $query = 'SELECT * FROM ' . $wpdb->prefix . 'wushka_licence WHERE account_id = "' . $school[0]->slug . '"';
    $licences = $wpdb->get_results($query);

    // loop through licences as there could be more than 1
    foreach ($licences as $id => $licence) {
        $today = new DateTime(date('Y-m-d'));
        $licence_end = new DateTime($licence->licence_end);
        if (in_array($licence->licence_type, $validType) && $today <= $licence_end) {
            //$valid_licence[] = $licence->licence_product;
            array_push($valid_licence, $licence->licence_product);
        }
    }

    if (!$valid_licence) {
        return false;
    }

    $access = false;
    if (!array_diff($valid_licence, $valid)) {
        $access = true;
    }
    return $access;
}

/*########################################################
*
*       Remove Jquery and Migrate added by wordpress     # 
*
##########################################################*/

function wushka_jquery_update_scripts()
{
    wp_deregister_script('jquery');

    //wp_register_script('jquery', get_template_directory_uri() . '/js/jquery-3.5.1.js','','',false);  //Development version
    wp_register_script('jquery', get_template_directory_uri() . '/js/jquery-3.5.1.min.js', '', '', false);  //Production version

    //wp_register_script('jqueryMigrate', get_template_directory_uri() . '/js/jquery-migrate-3.3.1.js','','',false);  //Development version
    wp_register_script('jqueryMigrate', get_template_directory_uri() . '/js/jquery-migrate-3.3.1.min.js', '', '', false);  //Production version

    wp_enqueue_script('jquery');
    wp_enqueue_script('jqueryMigrate');
}
if (!is_admin()) {
    add_action('wp_enqueue_scripts', 'wushka_jquery_update_scripts', 99);
}


/*
*  Disable autocomplete in wp-login.php form
*/
function wushka_disable_autocomplete_login()
{
    echo <<<html
        <script>
            document.getElementById( "loginform" ).autocomplete = "off";
        </script>
        html;
}

add_action('login_form', 'wushka_disable_autocomplete_login');


/* 
* Return cdn url checking domain extension
*/
function getCdnLink()
{
    $extension = pathinfo($_SERVER['SERVER_NAME'], PATHINFO_EXTENSION);
    $cdn = 'https://cdn1.wushka.com.au';
    if ($extension == 'nz') {
        $cdn = 'https://cdn1.wushka.co.nz';
    }
    return $cdn;
}

if (!function_exists('current_user_has_role')) {
    function current_user_has_role($role)
    {
        return user_has_role_by_user_id(get_current_user_id(), $role);
    }
}

if (!function_exists('get_user_roles_by_user_id')) {
    function get_user_roles_by_user_id($user_id)
    {
        $user = get_userdata($user_id);
        return empty($user) ? array() : $user->roles;
    }
}

if (!function_exists('user_has_role_by_user_id')) {
    function user_has_role_by_user_id($user_id, $role)
    {

        $user_roles = get_user_roles_by_user_id($user_id);

        if (is_array($role)) {
            return array_intersect($role, $user_roles) ? true : false;
        }

        return in_array($role, $user_roles);
    }
}

/* if (!function_exists('dd')) {
    function dd($data)
    {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die;
    }
 } */

if (!function_exists('calculate_time_duration')) {
    function calculate_time_duration($i_duration)
    {
        //Format Duration Seconds to Minutes, add string describer
        $s_duration = '0 seconds';

        //Average Reading Time Per Book
        if (isset($i_duration) && !empty($i_duration)) {
            //Average Time per book = Total Read Time in Seconds, Divided by Total Books
            $i_average = (int) $i_duration;

            if ($i_average < 60) {
                //If Less than 1 minutes, display in seconds
                $s_duration = $i_average . ' seconds';
            } else {
                //Display in Minutes
                $s_duration = round(($i_average / 60), 1);
                //Singular OR Plural?
                if ($s_duration == 1.0) {
                    $s_duration .= ' minute';
                } else {
                    $s_duration .= ' minutes';
                }
            }
        }

        return $s_duration;
    }
}


/*  =====================================================
*
*
*   Ajax Quiz Result summary after finishing the quiz
*
=========================================================== */
add_action('wp_ajax_wushka_quiz_score_summary', 'wushka_quiz_score_summary');
function wushka_quiz_score_summary()
{
    $nonce = $_POST['nonce'];
    // nonce check for an extra layer of security, the function will exit if it fails
    if (!wp_verify_nonce($nonce, "wushka_quiz_score_summary")) {
        die('Nonce value cannot be verified.');
    }

    if (isset($_REQUEST)) {
        if (current_user_can('student') || current_user_can('administrator') || current_user_can('school') || current_user_can('teacher')) {
            global $wpdb, $current_user;


            /* $actual_link = $_SERVER['HTTP_REFERER']; 
            $a_quizid    = explode('/quiz/', $actual_link);
            $s_id        = $a_quizid[1];
            $quiz_id        = explode('/', $s_id)[0];
 
            if($quiz_id !=  $_REQUEST['quiz_id'])
            {
                die('Sorry, you are not allowed to access this content. ');
            } */

            $quiz_user = $current_user->ID;
            $quiz_detail_results = get_user_meta($quiz_user, 'quiz_detail_results', true);

            if (!empty($quiz_detail_results) && $quiz_detail_results != 'Yes') {
                die();
            }

            $quiz_id = $_REQUEST['quiz_id'];

            $results_db_name = $wpdb->prefix . 'plugin_slickquiz_scores';
            $quiz_db_name = $wpdb->prefix . 'plugin_slickquiz';


            $score = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT results.id, results.quiz_id, results.answers, results.score, quiz.name, quiz.publishedJson, results.createdDate FROM $results_db_name as results LEFT JOIN $quiz_db_name as quiz on quiz.id = results.quiz_id WHERE results.createdBy = %d AND results.quiz_id = %d ORDER BY results.id DESC LIMIT 1 ",
                    $quiz_user,
                    $quiz_id
                )
            );
            $quiz = json_decode($score->publishedJson);
            $answers = json_decode($score->answers);


            $o_school_term = wp_get_object_terms($current_user->ID, 'school');
            $i_school      = NULL;
            if (isset($o_school_term) && !empty($o_school_term)) {
                $i_school = $o_school_term[0]->term_taxonomy_id;
            }


            //Get TimeZones For DateTime displaying
            $s_tz = wushka_get_school_timezone($i_school);
            $tz_utc = new DateTimeZone('UTC');
            $tz_school = new DateTimeZone($s_tz);


            //Quiz result Summary
            $dt = new DateTime($score->createdDate, $tz_utc);
            $dt->setTimeZone($tz_school);
            $scoreRow = "";
            $scoreRow .= '<tr>';
            $scoreRow .= '<td class="table-date">' . $dt->format("l, dS M Y g:ia") . '</td>';
            $scoreRow .= '<td class="table-title">' . $score->name . '</td>';
            $scoreRow .= '<td class="table-score">' . $score->score . '</td>';
            $xx = 0;

            //table head question
            $count = 1;
            $thead = '';
            foreach ($answers as $answer) {
                $scoreRow .= '<td class="table-answer ' . $answer->valid . '">' . $answer->valid . '</td>';
                $xx++;

                $thead .= '<th>Question ' . $count . '</th>';
                $count++;
            }
            $scoreRow .= '</tr>';

            //Result Summary
            $resultRow = "";
            foreach ($quiz->questions as $key => $question) {
                $quizScoreRow = "";
                $quizScoreRow .= '<tr>';
                $quizScoreRow .= '<td>' . ($key + 1) . '</td>';
                $quizScoreRow .= '<td class="table-questions">' . $question->q . '</td>';
                $quizScoreRow .= '<td class="table-answers"><ol>';
                foreach ($question->a as $answer_key => $answer) {
                    $response = $answer_key == $answers[$key]->a ? 'true' : '';
                    $correct = $answer->correct;
                    $option = $answer->option;
                    if ($correct == 'checked' || $response == 'true') {
                        $option = '<span>' . $option . '</span>';
                    }
                    $quizScoreRow .= '<li class="table-answer ' . $correct . $response . '">' . $option . '</li>';
                }
                $quizScoreRow .= '</ol></td>';
                $quizScoreRow .= '<td class="table-response ' . $answers[$key]->valid . '">';
                $quizScoreRow .= $answers[$key]->valid;
                $quizScoreRow .= '</td>';
                $quizScoreRow .= '</tr>';

                $resultRow .= $quizScoreRow;
            }

            $html = '
    <div class="row">
        <div class="col-xs-12">  
            <div class="table-responsive">
                <h2>Quiz Result Summary</h2>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Score</th>
                            ' . $thead . '
                        </tr>
                    </thead>
                    <tbody>
                    ' . $scoreRow . '
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <h2>Quiz Result Details</h2>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Question</th>
                            <th>Answers</th>
                            <th>Mark</th>
                        </tr>
                    </thead>
                    <tbody>
                        ' . $resultRow . '
                    </tbody>
                </table>
            </div> 
        </div> 
    </div> 
            ';

            echo $html;
        } else {
            die('Sorry, you are not allowed to access this content. ');
        }
    }
    // Always die in functions echoing ajax content
    die();
}


/*  =====================================================
*
*
*   Database table and column update
*
=========================================================== */

function wp_wushka_db_modify()
{
    global $wpdb;
    //Alter wp_classes table to Add licence_product column
    $table_name = $wpdb->prefix . "classes";
    $column_name = 'licence_product';

    $row = $wpdb->get_results('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = "' . $table_name . '" AND column_name = "' . $column_name . '"');

    if (empty($row)) {
        $wpdb->query('ALTER TABLE ' . $table_name . ' ADD ' . $column_name . ' VARCHAR(100) NOT NULL DEFAULT 0');
    }

    //Create table Wushka Api keys if not found
    $table_name2 = $wpdb->prefix . "wushka_keys";
    $row2 = $wpdb->get_results('SHOW TABLES LIKE "' . $table_name2 . '"');
    if (empty($row2)) {
        $sql = "CREATE TABLE $table_name2 (
            account_id VARCHAR(20) NOT NULL,
            school_id INT(11),
            api_key VARCHAR(255),
            creation_date TIMESTAMP NOT NULL
        );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        error_log('----- wushka keys created -----');
    }
}
add_action('init', 'wp_wushka_db_modify', 10);



/*  =====================================================
*
*   Get default licence
*   
*   @param array of licence
*   @return default licence value checking the licence param
*
=========================================================== */
function get_default_licence_value($licence)
{
    $levelled = 'Wushka Levelled';
    $decodables = 'Wushka Decodables';
    $plus = 'Wushka Plus';

    if (in_array($levelled, $licence) && in_array($decodables, $licence) && in_array($plus, $licence)) {
        $default = $levelled;
    } elseif (in_array($levelled, $licence) && in_array($decodables, $licence)) {
        $default = $levelled;
    } elseif (in_array($levelled, $licence) && in_array($plus, $licence)) {
        $default = $levelled;
    } elseif (in_array($plus, $licence) && in_array($decodables, $licence)) {
        $default = $decodables;
    } elseif (in_array($decodables, $licence)) {
        $default = $decodables;
    } elseif (in_array($plus, $licence)) {
        $default = $plus;
    } elseif (in_array($levelled, $licence)) {
        $default = $levelled;
    } else {
        $default = 0;
    }
    return $default;
}

/*  =====================================================
*
*   Get available licence of school
*   
*   @param string licence, int school_id
*   @return true when valid
*
=========================================================== */
function get_school_licence_available($account_id, $end_date)
{
    global $wpdb;
    //Table name with prefix 
    $table_licence = $wpdb->prefix . "wushka_licence";

    $sql = 'SELECT `licence_product` FROM ' . $table_licence . ' WHERE `account_id` = %d AND `licence_end` > %s AND `licence_type` NOT LIKE "%Cancelled%" AND `licence_count` > 0 ORDER BY `licence_product` DESC';
    $licence = $wpdb->get_results(
        $wpdb->prepare($sql, $account_id, $end_date)
    );
    $licence_available = array_column(json_decode(json_encode($licence), true), 'licence_product');

    return $licence_available;
}

/*  ====================================================================================
*
*   Set default licence for all classes which have no licence assigned
*   
*   @Notice: Call this function only one time to set default licence
*   @Warning: If licence has been removed by school to class, it will re assign default 
*
======================================================================================= */
function set_default_licence_all()
{
    global $wpdb;
    //Table name with prefix
    $table_classes = $wpdb->prefix . "classes";
    $current_date = date('Y-m-d H:i:s');

    //Select classes having licence product as 0
    $sql = 'SELECT `id`, `school_id` FROM ' . $table_classes . ' WHERE `licence_product` = %d ORDER BY `id` DESC';
    $classes = $wpdb->get_results(
        $wpdb->prepare($sql, 0)
    );
    error_log('----- Starting default licence for empty classes -----');
    foreach ($classes as $class) {
        //Get school term with school id 
        $term = get_term($class->school_id, "school");

        if (!empty($term->slug)) {
            //list licence of the slug
            $licence_available = get_school_licence_available($term->slug, $current_date);

            if (!empty($licence_available)) {
                error_log('Setting class id ' . $class->id);
                //Get default licence as per available licence
                $default = get_default_licence_value($licence_available);

                //Update table with default 
                $update_query = 'UPDATE ' . $table_classes . ' SET `licence_product` = %s WHERE `id` = %d';
                //$update_query = 'UPDATE '.$table_classes.' SET `licence_product` = "'.$default.'" WHERE `id` = '.$class->id;
                //echo '<pre>';
                //echo $update_query;
                //echo '</pre>';

                $wpdb->query(
                    $wpdb->prepare($update_query, $default, $class->id)
                );
                //=================================================== Remove wpdb query to execute the update query ^^^^^^^^^^^^^^^^^^^
            }
        }
    }
    error_log('----- Finish default licence for empty classes -----');
}

/*  =====================================================
*
*   Check licence valid to school
*   
*   @param string licence, int school_id
*   @return true when valid
*
=========================================================== */
function check_licence_valid($licence, $school_id)
{
    $valid = false;
    $current_date = date('Y-m-d H:i:s');

    //Get school term with school id 
    $term = get_term($school_id, "school");

    if (!empty($term->slug)) {
        //list licence of the slug
        $licence_available = get_school_licence_available($term->slug, $current_date);

        //Check if avilable licence matches with the licence provided
        if (in_array($licence, $licence_available) || $licence == 0) {
            $valid = true;
        }
    }
    return $valid;
}

/*  =====================================================
*
*   Two way encryption and decryption
*    
*   @action = 'encrypt' for encryption
*   @action = 'decrypt' for decryption
*
=========================================================== */
function encrypt_decrypt($action, $string)
{
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = NONCE_KEY;
    $secret_iv = substr(NONCE_KEY, 0, 10);
    // hash
    $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}


if (SIS_REST_API_ENABLED != "false") {
    /*  =====================================================
    *
    *   Creates new api key
    *    
    *   @return api key
    *
    =========================================================== */
    function generate_api_key()
    {
        // Generates a random string of ten digits
        $salt = mt_rand();

        // Computes the signature by hashing the salt with the secret key as the key
        $signature = hash_hmac('sha256', $salt, NONCE_SALT, true);

        // base64 encode...
        $encodedSignature = base64_encode($signature);

        // urlencode...
        $encodedSignature = urlencode($encodedSignature);

        //Get only alphanumeric characters
        $alphaNumeric = preg_replace("/[^a-zA-Z0-9]+/", "", $encodedSignature);

        //Get only 32 characters from the alphaNumeric key
        $key = substr($alphaNumeric, 0, 32);

        return 'wushka_' . $key;
    }

    function wushka_ajax_create_api_key()
    {
        $user_id = get_current_user_id();
        $school    = $terms = wp_get_object_terms($user_id, 'school');
        $school_id = $school[0]->term_taxonomy_id;
        $account_id = $school[0]->slug;
        $api_key = generate_api_key();

        if (!check_api_exist($api_key)) {
            $create = create_api_key($account_id, $school_id, $api_key);
        }

        if ($create) {
            echo $api_key;
            exit();
        }
        return false;
    }

    //Check if api exist
    function check_api_exist($api_key)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "wushka_keys";
        $sql = 'SELECT `school_id` FROM ' . $table_name . ' WHERE `api_key` = %s';
        $results = $wpdb->get_results(
            $wpdb->prepare($sql, $api_key)
        );

        if ($results) {
            return true;
        }
        return false;
    }

    //insert or update the api key
    function create_api_key($account_id, $school_id, $api_key)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "wushka_keys";

        $sql = 'SELECT `school_id` FROM ' . $table_name . ' WHERE `account_id` = %d AND `school_id` = %d';
        $results = $wpdb->get_results(
            $wpdb->prepare($sql, $account_id, $school_id)
        );


        //if exist update the data instead of insert 
        if (!$results) {
            $insert = $wpdb->insert($table_name, array(
                'account_id' => $account_id,
                'school_id' => $school_id,
                'api_key' => encrypt_decrypt('encrypt', $api_key),
                'creation_date' => current_time('mysql')
            ));
            return true;
        } else {
            $update = $wpdb->update(
                $table_name,
                array(
                    'account_id' => $account_id,
                    'school_id' => $school_id,
                    'api_key' => encrypt_decrypt('encrypt', $api_key),
                    'creation_date' => current_time('mysql')
                ),
                array(
                    'account_id' => $account_id,
                    'school_id' => $school_id,
                )
            );
            return true;
        }
        return false;
    }

    function get_api_key($account_id, $school_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "wushka_keys";

        $sql = 'SELECT `api_key` FROM ' . $table_name . ' WHERE `account_id` = %d AND `school_id` = %d';
        $results = $wpdb->get_results(
            $wpdb->prepare($sql, $account_id, $school_id)
        );

        $api_key = $results[0]->api_key;
        return encrypt_decrypt('decrypt', $api_key);
    }

    function get_school_id_from_api_key($request)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "wushka_keys";


        $api_key = $request->get_header('x-api-key');
        $api_key = encrypt_decrypt('encrypt', $api_key);


        $sql = 'SELECT `school_id` FROM ' . $table_name . ' WHERE `api_key` = %s';
        $results = $wpdb->get_results(
            $wpdb->prepare($sql, $api_key)
        );

        return $results[0]->school_id;
    }

    /*=======================================================
    *
    *   Register routes from controller
    *
    ========================================================*/
    require get_parent_theme_file_path('rest-api/class-wp-rest-teachers-controller.php');
    require get_parent_theme_file_path('rest-api/class-wp-rest-students-controller.php');
    require get_parent_theme_file_path('rest-api/class-wp-rest-classes-controller.php');
    require get_parent_theme_file_path('rest-api/class-wp-rest-schools-controller.php');
    function wushka_api_init()
    {
        $rest_teachers_controller = new WP_REST_Teachers_Controller();
        $rest_teachers_controller->register_routes();

        $rest_students_controller = new WP_REST_Students_Controller();
        $rest_students_controller->register_routes();

        $rest_classes_controller = new WP_REST_Classes_Controller();
        $rest_classes_controller->register_routes();


        $rest_schools_controller = new WP_REST_Schools_Controller();
        $rest_schools_controller->register_routes();
    }
    add_action('rest_api_init', 'wushka_api_init');
}


function create_missing_meta_active_to_teacher()
{
    global $wpdb;

    $check_date = '2020-03-01 00:00:00';

    //Get all users not having meta_key active and last_login >= 2020-03-01
    $sql = "SELECT `user_id` FROM `" . $wpdb->prefix . "usermeta` WHERE `user_id` NOT IN (SELECT `user_id` FROM " . $wpdb->prefix . "usermeta m1 WHERE m1.meta_key = 'active') AND meta_key = 'last_login' AND `meta_value` >= %s AND `user_id` > 1 ";
    $results = $wpdb->get_results(
        $wpdb->prepare($sql, $check_date)
    );

    foreach ($results as $result) {
        $user_id  = $result->user_id;
        $user = get_userdata($user_id);

        //Check if user has teacher role
        if (in_array('teacher', $user->roles)) {
            error_log('Active meta_key added to user: ' . $user_id);
            add_user_meta($user_id, 'active', 1);
        }
    }
}


//Subadmin access manager
require_once('functions/access-manager/roles.php');

add_action('init', 'set_session_teacher_decodable_licence');
function set_session_teacher_decodable_licence()
{
    if (!isset($_SESSION)) {
        session_start();
    }
    if (is_user_logged_in() && current_user_can('teacher') && !current_user_can('school') && !current_user_can('administration')) {
        $user_id = get_current_user_id();

        $teacher = wushka_get_teacher_classes($user_id);
        $licence = array_column(json_decode(json_encode($teacher), true), 'licence_product');

        if (in_array(LICENCE_WDT, $licence)) {
            $_SESSION['wushka_decodable_teacher'] = true;
            restrict_url_teacher_decodable_licence();
        } else {
            $_SESSION['wushka_decodable_teacher'] = false;
        }
    }
}

function restrict_url_teacher_decodable_licence()
{
    $url = rtrim($_SERVER['REQUEST_URI'], "/");
    if (
        $url == "/manage-class-list"
        || $url == '/manage-reading-groups'
        || $url == '/class-login'
        || $url == '/class-statistics'
        || $url == '/student-statistics'
        || $url == '/reader-records'
        || $url == '/levelled'
        || strpos($url, '/quiz') === 0
    ) {
        redirect_404();
    }
}

function redirect_404()
{
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    get_template_part(404);
    exit();
}


add_action('login_init', 'wushka_login_validate');
function wushka_login_validate()
{
    if (isset($_POST['log']) && isset($_POST['pwd'])) {
        if (empty($_POST['log']) || empty($_POST['pwd'])) {
            wp_redirect(add_query_arg('login', 'empty', home_url('/') . '/login'));
            exit;
        }
    }
    return;
}


/*=======================================================
*
*   Quiz var optimization for wp updates
*
========================================================*/
function wushka_filter_rewrites()
{
    add_rewrite_rule('quiz/([0-9]+)/?', 'index.php?pagename=quiz&id=$matches[1]', 'top');
}
add_action('init', 'wushka_filter_rewrites');

function wushka_filter_query_vars($query_vars)
{
    $query_vars[] = 'id';
    return $query_vars;
}
add_filter('query_vars', 'wushka_filter_query_vars');



/*====================================================================
*
*   Restrict school and teacher from accessing wp-admin/profile.php
*
==================================================================*/

add_action('admin_menu', 'wushka_stop_access_profile');
function wushka_stop_access_profile()
{
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        $allowed_roles = array('administrator', 'subadmin', 'marketingmanager','modernstar_employee');
        if (!array_intersect($allowed_roles, $user->roles)) {
            remove_menu_page('profile.php');
            remove_submenu_page('users.php', 'profile.php');
            $url = trim($_SERVER['REQUEST_URI'], "/");
            if (IS_PROFILE_PAGE || $url == 'wp-admin') {
                redirect_404();
            }
        }
    }
}


/*====================================================================
*
*   Disable plugins update notification
*
==================================================================*/
function wushka_filter_plugin_updates($value)
{
    $plugins = [
        'amazon-web-services/amazon-web-services.php',
        'lessonZone-postAttachment/lzPA-postAttachment.php',
        'lessonzone-statistics/my-statistics.php',
        'pdf-stamp/pdf-stamp.php',
        'lessonzone-aws/plugin.php',
        'wushka-database/plugin.php',
        'lessonlocker/plugin.php',
        'slickquiz/slickquiz.php',
        'wushka_reading_analytics/plugin.php'
    ];

    foreach ($plugins as $plugin) {
        if (isset($value->response[$plugin])) {
            unset($value->response[$plugin]);
        }
    }
    return $value;
}
add_filter('site_transient_update_plugins', 'wushka_filter_plugin_updates');

//User activity log
//require_once('functions/logs/UserActivityLog.php'); 


function wushka_cta_button_text()
{
    $extension = pathinfo($_SERVER['SERVER_NAME'], PATHINFO_EXTENSION);
    $text = 'Request Free Trial';
    if ($extension != 'nz') {
        $text = 'Get Started for FREE';
    }
    return $text;
}



/*====================================================================
*
*   Include Custom Post Type
*
==================================================================*/
require_once('functions/custom-post-type/notices.php');
require_once('functions/custom-post-type/educational-resources.php');
require_once('functions/custom-post-type/product-releases.php');


/**
 * Returns cached user counts if there are some, otherwise fetches current user counts and stores them for later use.
 *
 * @param int       $count Count to be overridden, will be null when called by pre_count_users.
 * @param string    $strategy The computational strategy to use when counting the users.
 * @param int|null  $site_id The site ID to count users for.
 *
 * @return array Includes a grand total and an array of counts indexed by role strings.
 */
function wushka_admin_cached_user_count($count, $strategy, $site_id)
{
    // Respect any value already set by another filter
    if (!is_null($count)) {
        return $count;
    }

    $count = get_transient('wushka_admin_user_count');

    if ($count === false) {
        // No cached value, so fetch current user count
        $count = wushka_admin_latest_user_count($strategy, $site_id);
    }

    return $count;
}
add_filter('pre_count_users', 'wushka_admin_cached_user_count', 10, 3);

/**
 * Counts current users as per count_users() and stores the value for use by wushka_admin_cached_user_count() filter.
 *
 * @param string   $strategy Optional. The computational strategy to use when counting the users.
 *                           Accepts either 'time' or 'memory'. Default 'time'.
 * @param int|null $site_id  Optional. The site ID to count users for. Defaults to the current site.
 * @return array Includes a grand total and an array of counts indexed by role strings.
 *
 * @see count_users()
 */
function wushka_admin_latest_user_count($strategy = 'time', $site_id = null)
{
    // Unhook our filters before fetching the counts
    remove_filter('pre_count_users', 'wushka_admin_cached_user_count');
    $count = count_users($strategy, $site_id);
    add_filter('pre_count_users', 'wushka_admin_cached_user_count', 10, 3);

    // Save the value in our cache for 12 hours
    set_transient('wushka_admin_user_count', $count, 12 * HOUR_IN_SECONDS);

    return $count;
}



add_action('init', 'wushka_remove_cpt_support');
function wushka_remove_cpt_support()
{
    remove_post_type_support('page', 'author');
    remove_post_type_support('page', 'comments');
    remove_post_type_support('post', 'author');
    remove_post_type_support('post', 'comments');
}


/*########################################################
*
*       Bootstrap Pagination                             # 
*
##########################################################*/
function bootstrap_pagination($query = null)
{
    global $wp_query;
    $query = $query ? $query : $wp_query;
    $big = 999999999;

    $paginate = paginate_links(
        array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'type' => 'array',
            'total' => $query->max_num_pages,
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'prev_text' => __('Prev'),
            'next_text' => __('Next'),
        )
    );

    if ($query->max_num_pages > 1) :
        echo '<div class="row">
                <nav class="col-md-12" aria-label="Page navigation">
                    <ul class="pagination">';
        foreach ($paginate as $page) {
            echo '<li>' . $page . '</li>';
        }
        echo '      </ul>
                </nav>
            </div>
        ';
    endif;
}
/*=======================================================
*
*   Shorten String
*
========================================================*/
function shorten_string($string, $wordsreturned)
{
    $retval = $string;
    $string = preg_replace('/(?<=\S,)(?=\S)/', ' ', $string);
    $string = str_replace("\n", " ", $string);
    $array = explode(" ", $string);
    if (count($array) <= $wordsreturned) {
        $retval = $string;
    } else {
        array_splice($array, $wordsreturned);
        $retval = implode(" ", $array) . " ...";
    }
    return $retval;
}

/*########################################################
*
*       Share link creator                         # 
*
##########################################################*/
function share_link($social_media)
{
    global $post;
    $id = $post->ID;
    $title = get_the_title($id);
    $post_link = get_permalink($id);

    switch ($social_media) {
        case ('facebook'):
            $link = 'https://www.facebook.com/sharer/sharer.php?u=' . $post_link . '&t=' . $title . '';
            break;
        case ('twitter'):
            $link = 'https://twitter.com/intent/tweet?text=' . $title . '&url=' . $post_link . '';
            break;
        case ('linkedin'):
            $link = 'https://www.linkedin.com/shareArticle?url=' . $post_link . '&title=' . $title . '';
            break;
        case ('pinterest'):
            $link = 'http://pinterest.com/pin/create/button/?url=' . $post_link;
            break;
        default:
            $link = 'mailto:?subject=' . $title . '&amp;body=' . $post_link . '';
    }

    return $link;
}

function isQRDisabled()
{

    $schoolDisabledQR = get_option('disable_QR_login_school');

    $schoolId = wushka_get_user_school(get_current_user_id());

    $disabledQRSchoolsId = [];

    if (!empty($schoolDisabledQR)) {

        $schoolDisabledQR = json_decode(stripslashes(htmlspecialchars_decode($schoolDisabledQR)));

        if ($schoolDisabledQR) {
            foreach ($schoolDisabledQR as $ds) {

                array_push($disabledQRSchoolsId, $ds->id);
            }
        }
    }

    if (in_array($schoolId, $disabledQRSchoolsId)) {

        return true;
    }

    return false;
}

function student_login_by_QR_code()
{

    global $wpdb;

    $response = [
        'success' => false,
        'message' => 'Your QR code is not valid ! Please contact your school.'
    ];

    $codeValue = sanitize_text_field($_POST['code_value']);

    if (!empty($codeValue)) {

        $userId = $wpdb->get_var(
            $wpdb->prepare("SELECT um.user_id FROM {$wpdb->prefix}usermeta as um  WHERE um.meta_key=%s AND um.meta_value=%s", "st_qr_token", "{$codeValue}")
        );

        $isActive = get_user_meta($userId, 'active', true);

        if (!empty($userId) && (empty($isActive) || $isActive == 0 || $isActive == '0')) {

            $response = [
                'success' => false,
                'message' => 'Your account is not activated yet !. Please contact your school'
            ];

            echo json_encode($response);
            exit;
        }

        if (!empty($userId)) {

            $user_meta = get_userdata($userId);
            $user_roles = $user_meta->roles;
            if (!in_array('student', $user_roles)) {

                $response = [
                    'success' => false,
                    'message' => 'Only Student can login with the QR code.'
                ];

                echo json_encode($response);
                exit;
            }
        }




        if (!empty($userId)) {

            $response['message'] = "You are not registered with any school.";

            $userSchool = wushka_get_user_school($userId);


            if (!empty($userSchool)) {

                $schoolObject = get_term($userSchool);

                $disabledQRSchoolsId = [];

                if (!empty($schoolObject)) {

                    $userSchoolId = $schoolObject->term_id;

                    $schoolDisabledQR = get_option('disable_QR_login_school');

                    if (!empty($schoolDisabledQR)) {

                        $schoolDisabledQR = json_decode(stripslashes(htmlspecialchars_decode($schoolDisabledQR)));

                        if ($schoolDisabledQR) {
                            foreach ($schoolDisabledQR as $ds) {

                                array_push($disabledQRSchoolsId, $ds->id);
                            }
                        }
                    }

                    // $school_id_array = array_map('trim', explode(',', $schoolDisabledQR));
                    if (in_array($userSchoolId, $disabledQRSchoolsId)) {

                        $response['message'] = "You are not allowed to login with QR code";
                    } else {

                        wp_clear_auth_cookie();
                        wp_set_current_user($userId); // Set the current user detail
                        wp_set_auth_cookie($userId); // Set auth details in cookie


                        $qr_logged_in = get_user_meta($userId, 'qr_logged_in');

                        if (!empty($qr_logged_in)) {

                            $qr_logged_in = (int)$qr_logged_in + 1;
                        } else {

                            $qr_logged_in = 1;
                        }

                        update_user_meta($userId, 'qr_logged_in', $qr_logged_in);


                        $response['message'] = 'logged IN';
                        $response['success'] = true;
                    }
                }
            }
        }
    }

    echo json_encode($response);
    exit;
}

add_action('wp_ajax_nopriv_student_login_by_QR_code', 'student_login_by_QR_code');

function move_archive_student()
{
    $user_id = $_POST['userId'];
    $targetClass = $_POST['targetClass'];

    $oldSchoolId = false;

    $o_class_old  = wushka_get_class(get_user_meta($user_id, 'class', true));

    if (!empty($o_class_old)) {

        $oldSchoolId = $o_class_old->school_id;
    }

    update_user_meta($user_id, 'active', 1);
    update_user_meta($user_id, 'class', $targetClass);
    update_user_meta($user_id, 'show_admin_bar_front', 'false');
    update_user_meta($user_id, 'narration', 'Yes');
    update_user_meta($user_id, 'quizzes', 'compulsory');

    $o_class  = wushka_get_class($targetClass);
    $i_school = NULL;
    if (isset($o_class) && !empty($o_class)) {
        $i_school = $o_class->school_id;
        if (isset($i_school) && !empty($i_school)) {

            //if (!$emailExists) {
            wp_set_object_terms($user_id, array(intval($i_school)), 'school', FALSE);
            clean_object_term_cache($user_id, 'school');
            //}
        }
    }

    resetStudentData($user_id, $oldSchoolId);

    echo json_encode(['success' => true]);
    exit;
}
add_action('wp_ajax_move_archive_student', 'move_archive_student');


function getSalesforceAccessToken()
{

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => SLF_TOKEN_API_URL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "username=" . SLF_USERNAME . "&password=" . SLF_PASSWORD . "&client_id=" . SLF_CLIENT_ID . "&client_secret=" . SLF_CLIENT_SECRET . "&grant_type=password",
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/x-www-form-urlencoded"
        ],
    ]);

    $res = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if (!$err) {

        $res = json_decode($res);
        if (isset($res->access_token)) {
            return $res->access_token;
        } else {
            return false;
        }
    }

    return false;
}


function salesforce_wushka_trail()
{


    $response = [
        'success' => false,
        'message' => 'Something went wrong ! Please try again.'
    ];

    $accessToken = getSalesforceAccessToken();

    if (!$accessToken) {

        $response['message'] = "Access token not found !";
        echo json_encode($response);
        exit;
    }

    $salesForceData = [

        'firstName' => $_POST['Cat_First_Name__c'],
        'lastName' => $_POST['Cat_Last_Name__c'],
        'email' => $_POST['Cat_Email__c'],
        'mobileNumber' => $_POST['Cat_Phone__c'],
        'EducationSector' => $_POST['education_sector'],
        'jobTitle' => $_POST['Cat_Title__c'],
        'numberOfClasses' => $_POST['Cat_Num_of_Classes__c'],
        'nameOfSchool' => $_POST['Cat_Account_Name__c'],
        'postCode' => $_POST['Cat_Postcode__c'],
        'schoolAddress1' => $_POST['Cat_Address_1__c'],
        'schoolAddress2' => $_POST['Cat_Address_2__c'],
        'suburb' => $_POST['Cat_Suburb__c'],
        'state' => $_POST['Cat_State__c'],
        'categoryInterest' => "Wushka",
        'wushkaMarketingConsent' => true
    ];

    if (!isset($_POST['Cat_Account_Name__c']) && isset($_POST['account'])) {
        $salesForceData['nameOfSchool'] = $_POST['account'];
    }

    if (!isset($_POST['Cat_Postcode__c']) && isset($_POST['post-code-input'])) {
        $salesForceData['postCode'] = $_POST['post-code-input'];
    }

    if (!isset($_POST['Cat_Suburb__c'])) {

        $salesForceData['suburb'] = '';
    }

    if (!isset($_POST['Cat_State__c'])) {

        $salesForceData['state'] = '';
    }

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => SLF_REGISTRATION_API_URL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($salesForceData, JSON_PRETTY_PRINT),
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer {$accessToken}",
            "Content-Type: application/json"
        ],
    ]);

    $res = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if (!$err) {
        $res = json_decode($res);
        if (isset($res->statusCode) && $res->statusCode == 200) {

            echo json_encode(['success' => true, 'message' => 'processed successfully.', 'data_sent' => $salesForceData]);
        } else {

            echo json_encode(['success' => false, 'message' => json_encode($res), 'api_response' => $res]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => json_encode($res), 'api_response' => $err]);
    }

    exit;
}

add_action('wp_ajax_nopriv_salesforce_wushka_trail', 'salesforce_wushka_trail');
add_action('wp_ajax_salesforce_wushka_trail', 'salesforce_wushka_trail');

apply_filters('get_site_icon_url', get_stylesheet_directory_uri() . '/favicon.ico');


/***
 * logic to force password policy
 * 
 * 
 */

function is_password_policy_valid($password)
{
    return (
        strlen($password) >= 15 &&
        preg_match('/[a-zA-Z]/', $password) &&  // at least one letter
        preg_match('/\d/', $password) &&        // at least one number
        preg_match('/[\W_]/', $password)        // at least one symbol
    );
}

function passwordPolicyAllowedRoles()
{
    //return ['administrator', 'subadmin', 'marketingmanager'];
    //return ['subadmin','customer', 'student', 'parent','school','school_admin'];
    return ['subadmin','customer','parent','school_admin','school','teacher','marketingmanager'];
}

// function check_password_policy($user_id)
// {
//     $password = $_POST['pwd'] ?? '';
//     if (isset($_POST['pwd']) && !is_password_policy_valid($password)) {
//         update_user_meta($user_id, 'password_needs_reset', true);
//     } else {
//         delete_user_meta($user_id, 'password_needs_reset');
//     }
// }

// add_action('wp_login', function ($user_login, $user) {
//     check_password_policy($user->ID);
// }, 10, 2);

add_filter('wp_authenticate_user', 'check_password_policy_on_auth_user', 10, 2);

function check_password_policy_on_auth_user($user, $password)
{
    if (isset($password) && !is_password_policy_valid($password)) {
        update_user_meta($user->ID, 'password_needs_reset', true);
    } else {
        delete_user_meta($user->ID, 'password_needs_reset');
    }

    return $user;
}


function user_must_change_password_redirect()
{
    if (!is_user_logged_in()) {
        return;
    }

    // Skip redirect when an admin has switched into this account via the User Switching plugin.
    if (function_exists('current_user_switched') && current_user_switched()) {
        return;
    }

    $user_id = get_current_user_id();
    $user = wp_get_current_user();
    $allowed_roles = passwordPolicyAllowedRoles();

    // Only enforce policy for selected roles
    if (!array_intersect($allowed_roles, $user->roles)) {
        return;
    }

    $needs_reset = get_user_meta($user_id, 'password_needs_reset', true);
    if (!$needs_reset) {
        return;
    }

    // Allow access to the password change page and logout/login URLs
    $allowed_paths = [
        '/change-password',
        '/logout'
    ];

    $current_path = $_SERVER['REQUEST_URI'];

    foreach ($allowed_paths as $allowed_path) {
        if (strpos($current_path, $allowed_path) !== false) {
            return; // allow access
        }
    }

    // Redirect to password change page if not on allowed path
    wp_redirect(site_url('/change-password'));
    exit;
}
add_action('template_redirect', 'user_must_change_password_redirect');
add_action('admin_init', 'user_must_change_password_redirect');

/*** end logic force password policy */


/*** miniOrange filters */

add_filter('mo_saml_error_message', function ($message, $error_code) {
    if ($error_code === 'WPSAMLERR018') {
        return 'Login failed: This user does not exist in our system.';
    }
    return $message;
}, 10, 2);


/*** gravity Forms filters */
add_filter( 'gform_save_field_value_5_4', 'strict_sanitize_single_line_text', 10, 4 );
add_filter( 'gform_save_field_value_5_2', 'strict_sanitize_single_line_text', 10, 4 );

function strict_sanitize_single_line_text( $value, $lead, $field, $form ) {
    // Strips all HTML tags and leaves purely plain text
    return sanitize_text_field( $value );
}


require_once('app/admin/admin.php');

/* --- End Of Functions File --- */
