<?php

if (!defined('ABSPATH')) {
    exit(); // Exit if accessed directly
}


/**
 * Helper function for custom post type educational resources
 *
 * @return array
 */
function helper_custom_post_type_educational_resource()
{
    $custom_post_type = 'educational_resource';
    $cpt_prefix = $custom_post_type . '_meta_';
    return array(
        'id'        => 'educational_resource_attributes',
        'post_type' => $custom_post_type,
        'title'     => 'Educational Resource attributes',
        'slug'      => 'educational-resources',
        'page'      => $custom_post_type,
        'context'   => 'normal',
        'priority'  => 'high'
    );
}

/**
 * Creates Custom Post type for educational resource
 *
 * @return void
 */
function create_custom_post_type_educational_resource()
{

    $helper = helper_custom_post_type_educational_resource();
    $post_type = $helper['post_type'];
    $slug = $helper['slug'];

    $labels = array(
        'name' => _x('Educational Resources', 'Post Type General Name', 'wushka'),
        'singular_name' => _x('Educational Resource', 'Post Type Singular Name', 'wushka'),
        'menu_name' => _x('Educational Resources', 'Admin Menu text', 'wushka'),
        'name_admin_bar' => _x('Educational Resource', 'Add New on Toolbar', 'wushka'),
        'archives' => __('Educational Resource', 'wushka'),
        'attributes' => __('Educational Resource', 'wushka'),
        'parent_item_colon' => __('Educational Resource', 'wushka'),
        'all_items' => __('All Educational Resources', 'wushka'),
        'add_new_item' => __('Add New Educational Resource', 'wushka'),
        'add_new' => __('Add New', 'wushka'),
        'new_item' => __('New Educational Resource', 'wushka'),
        'edit_item' => __('Edit Educational Resource', 'wushka'),
        'update_item' => __('Update Educational Resource', 'wushka'),
        'view_item' => __('View Educational Resource', 'wushka'),
        'view_items' => __('View Educational Resources', 'wushka'),
        'search_items' => __('Search Educational Resource', 'wushka'),
        'not_found' => __('Not found', 'wushka'),
        'not_found_in_trash' => __('Not found in Trash', 'wushka'),
        'featured_image' => __('Featured Image', 'wushka'),
        'set_featured_image' => __('Set featured image', 'wushka'),
        'remove_featured_image' => __('Remove featured image', 'wushka'),
        'use_featured_image' => __('Use as featured image', 'wushka'),
        'insert_into_item' => __('Insert into Educational Resource', 'wushka'),
        'uploaded_to_this_item' => __('Uploaded to this Educational Resource', 'wushka'),
        'items_list' => __('Educational Resources list', 'wushka'),
        'items_list_navigation' => __('Educational Resources list navigation', 'wushka'),
        'filter_items_list' => __('Filter Educational Resources list', 'wushka'),
    );

    $args = array(
        'label' => __('Educational Resource', 'wushka'),
        'description' => __('', 'wushka'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-portfolio',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'taxonomies' => array(),
        'hierarchical' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => true,
        'has_archive' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_admin_bar' => false,
        'can_export' => true,
        'show_in_nav_menus' => false,
        'menu_position' => 6,
        'capability_type' => 'post',
        'show_in_rest' => false,
        'rewrite'     => array('slug' => $slug),
    );

    register_post_type($post_type, $args);
}
add_action('init', 'create_custom_post_type_educational_resource', 0);


add_action('init', 'create_er_hierarchical_taxonomies', 0);

function create_er_hierarchical_taxonomies()
{

    $taxonomies = [

        'subjects' => [

            'labels' => array(
                'name' => _x('Subjects', 'taxonomy general name'),
                'singular_name' => _x('Subject', 'taxonomy singular name'),
                'search_items' =>  __('Search Subjects'),
                'all_items' => __('All Subjects'),
                'parent_item' => __('Parent Subject'),
                'parent_item_colon' => __('Parent Subject:'),
                'edit_item' => __('Edit Subject'),
                'update_item' => __('Update Subject'),
                'add_new_item' => __('Add New Subject'),
                'new_item_name' => __('New Subject Name'),
                'menu_name' => __('Subjects'),
            ),
            'rewrite' => array('slug' => 'subject'),

        ],
        'theme_and_event' => [
            'labels' => array(
                'name' => _x('Themes and Events', 'taxonomy general name'),
                'singular_name' => _x('Grade', 'taxonomy singular name'),
                'search_items' =>  __('Search Theme and Event'),
                'all_items' => __('All Themes and Events'),
                'parent_item' => __('Parent Theme and Event'),
                'parent_item_colon' => __('Parent Theme and Event:'),
                'edit_item' => __('Edit Theme and Event'),
                'update_item' => __('Update Theme and Event'),
                'add_new_item' => __('Add New Theme and Event'),
                'new_item_name' => __('New Theme and Event Name'),
                'menu_name' => __('Themes and Events'),
            ),
            'rewrite' => array('slug' => 'theme_and_event'),

        ],
        'grade' => [
            'labels' => array(
                'name' => _x('Grades', 'taxonomy general name'),
                'singular_name' => _x('Grade', 'taxonomy singular name'),
                'search_items' =>  __('Search Grades'),
                'all_items' => __('All Grades'),
                'parent_item' => __('Parent Grade'),
                'parent_item_colon' => __('Parent Grade:'),
                'edit_item' => __('Edit Grade'),
                'update_item' => __('Update Grade'),
                'add_new_item' => __('Add New Grade'),
                'new_item_name' => __('New Grade Name'),
                'menu_name' => __('Grades'),
            ),
            'rewrite' => array('slug' => 'grade'),

        ],

    ];

    // Add new taxonomy, make it hierarchical like categories
    //first do the translations part for GUI

    // $labels = array(
    //     'name' => _x('Subjects', 'taxonomy general name'),
    //     'singular_name' => _x('Subject', 'taxonomy singular name'),
    //     'search_items' =>  __('Search Subjects'),
    //     'all_items' => __('All Subjects'),
    //     'parent_item' => __('Parent Subject'),
    //     'parent_item_colon' => __('Parent Subject:'),
    //     'edit_item' => __('Edit Subject'),
    //     'update_item' => __('Update Subject'),
    //     'add_new_item' => __('Add New Subject'),
    //     'new_item_name' => __('New Subject Name'),
    //     'menu_name' => __('Subjects'),
    // );

    foreach ($taxonomies as $key => $taxonomy) {

        register_taxonomy($key, array('educational_resource'), array(
            'hierarchical' => true,
            'labels' => $taxonomy['labels'],
            'show_ui' => true,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => $taxonomy['rewrite'],
        ));
    }
}


//Clean url to get status
function wpd_parse_request($request)
{
    //dd($request);
}
add_action('parse_request', 'wpd_parse_request');

function wushka_resources_url_rewrites()
{
    $helper = helper_custom_post_type_educational_resource();
    $post_type = $helper['post_type'];
    $slug = $helper['slug'];
    $regex = $slug . '/([^/]+)/([^/]+)/?$';
    $query = 'index.php?' . $post_type . '=$matches[1]&status=$matches[2]';
    //dd($query);
    add_rewrite_rule($regex, $query, 'top');
}
add_action('init', 'wushka_resources_url_rewrites');

function wushka_resources_url_query_vars($query_vars)
{
    $query_vars[] = 'status';
    return $query_vars;
}
add_filter('query_vars', 'wushka_resources_url_query_vars');
