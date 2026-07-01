<?php 

if (!defined('ABSPATH')) {
    exit(); // Exit if accessed directly
}

/**
 * Helper function for custom post type Support Material
 *
 * @return array
 */
function helper_custom_post_type_support_material(){
    $custom_post_type = 'support_material';
    $cpt_prefix = $custom_post_type . '_meta_';
    return array(
        'id'        => 'support_material_attributes',
        'post_type' => $custom_post_type,
        'title'     => 'Support Material attributes',
        'slug'      => 'support-material',
        'page'      => $custom_post_type,
        'context'   => 'normal',
        'priority'  => 'high'
    );
}

/**
 * Creates Custom Post type for Support Material
 *
 * @return void
 */
function create_custom_post_type_support_material() {

    $helper = helper_custom_post_type_support_material();
    $post_type = $helper['post_type'];
    $slug = $helper['slug'];

    $labels = array(
        'name' => _x( 'Support Materials', 'Post Type General Name', 'wushka' ),
        'singular_name' => _x( 'Support Material', 'Post Type Singular Name', 'wushka' ),
        'menu_name' => _x( 'Support Materials', 'Admin Menu text', 'wushka' ),
        'name_admin_bar' => _x( 'Support Material', 'Add New on Toolbar', 'wushka' ),
        'archives' => __( 'Support Material', 'wushka' ),
        'attributes' => __( 'Support Material', 'wushka' ),
        'parent_item_colon' => __( 'Support Material', 'wushka' ),
        'all_items' => __( 'All Support Materials', 'wushka' ),
        'add_new_item' => __( 'Add New Support Material', 'wushka' ),
        'add_new' => __( 'Add New', 'wushka' ),
        'new_item' => __( 'New Support Material', 'wushka' ),
        'edit_item' => __( 'Edit Support Material', 'wushka' ),
        'update_item' => __( 'Update Support Material', 'wushka' ),
        'view_item' => __( 'View Support Material', 'wushka' ),
        'view_items' => __( 'View Support Materials', 'wushka' ),
        'search_items' => __( 'Search Support Material', 'wushka' ),
        'not_found' => __( 'Not found', 'wushka' ),
        'not_found_in_trash' => __( 'Not found in Trash', 'wushka' ),
        'featured_image' => __( 'Featured Image', 'wushka' ),
        'set_featured_image' => __( 'Set featured image', 'wushka' ),
        'remove_featured_image' => __( 'Remove featured image', 'wushka' ),
        'use_featured_image' => __( 'Use as featured image', 'wushka' ),
        'insert_into_item' => __( 'Insert into Support Material', 'wushka' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Support Material', 'wushka' ),
        'items_list' => __( 'Support Materials list', 'wushka' ),
        'items_list_navigation' => __( 'Support Materials list navigation', 'wushka' ),
        'filter_items_list' => __( 'Filter Support Materials list', 'wushka' ),
    );
    
    $args = array(
        'label' => __( 'Support Material', 'wushka' ),
        'description' => __( '', 'wushka' ),
        'labels' => $labels,
        'menu_icon' => 'dashicons-database-add',
        'supports' => array('title', 'thumbnail'),
        'taxonomies' => array(),
        'hierarchical' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => true,
        'has_archive' => true,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_admin_bar' => false,
        'can_export' => true,
        'show_in_nav_menus' => true,
        'menu_position' => 6,
        'capability_type' => 'post',
        'show_in_rest' => false,
        'rewrite'     => array( 'slug' => $slug ),
    );

    register_post_type( $post_type, $args );

}
add_action( 'init', 'create_custom_post_type_support_material', 0 );


function register_support_material_taxonomy() {
    register_taxonomy( 'sm_types', [ 'support_material' ], [
        'label'        => __( 'Support Material Types' ),
        'rewrite'      => [ 'slug' => 'sm_types' ],
        'hierarchical' => true,
    ] );
}
add_action( 'init', 'register_support_material_taxonomy' );