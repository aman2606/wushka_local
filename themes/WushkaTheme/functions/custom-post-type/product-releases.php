<?php 

if (!defined('ABSPATH')) {
    exit(); // Exit if accessed directly
}

/**
 * Helper function for custom post type Product Releases
 *
 * @return array
 */
function helper_custom_post_type_product_release(){
    $custom_post_type = 'product_release';
    $cpt_prefix = $custom_post_type . '_meta_';
    return array(
        'id'        => 'product_release_attributes',
        'post_type' => $custom_post_type,
        'title'     => 'Product Release attributes',
        'slug'      => 'releases',
        'page'      => $custom_post_type,
        'context'   => 'normal',
        'priority'  => 'high'
    );
}

/**
 * Creates Custom Post type for Product Release
 *
 * @return void
 */
function create_custom_post_type_product_release() {

    $helper = helper_custom_post_type_product_release();
    $post_type = $helper['post_type'];
    $slug = $helper['slug'];

    $labels = array(
        'name' => _x( 'Product Releases', 'Post Type General Name', 'wushka' ),
        'singular_name' => _x( 'Product Release', 'Post Type Singular Name', 'wushka' ),
        'menu_name' => _x( 'Product Releases', 'Admin Menu text', 'wushka' ),
        'name_admin_bar' => _x( 'Product Release', 'Add New on Toolbar', 'wushka' ),
        'archives' => __( 'Product Release', 'wushka' ),
        'attributes' => __( 'Product Release', 'wushka' ),
        'parent_item_colon' => __( 'Product Release', 'wushka' ),
        'all_items' => __( 'All Product Releases', 'wushka' ),
        'add_new_item' => __( 'Add New Product Release', 'wushka' ),
        'add_new' => __( 'Add New', 'wushka' ),
        'new_item' => __( 'New Product Release', 'wushka' ),
        'edit_item' => __( 'Edit Product Release', 'wushka' ),
        'update_item' => __( 'Update Product Release', 'wushka' ),
        'view_item' => __( 'View Product Release', 'wushka' ),
        'view_items' => __( 'View Product Releases', 'wushka' ),
        'search_items' => __( 'Search Product Release', 'wushka' ),
        'not_found' => __( 'Not found', 'wushka' ),
        'not_found_in_trash' => __( 'Not found in Trash', 'wushka' ),
        'featured_image' => __( 'Featured Image', 'wushka' ),
        'set_featured_image' => __( 'Set featured image', 'wushka' ),
        'remove_featured_image' => __( 'Remove featured image', 'wushka' ),
        'use_featured_image' => __( 'Use as featured image', 'wushka' ),
        'insert_into_item' => __( 'Insert into Product Release', 'wushka' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Product Release', 'wushka' ),
        'items_list' => __( 'Product Releases list', 'wushka' ),
        'items_list_navigation' => __( 'Product Releases list navigation', 'wushka' ),
        'filter_items_list' => __( 'Filter Product Releases list', 'wushka' ),
    );
    
    $args = array(
        'label' => __( 'Product Release', 'wushka' ),
        'description' => __( '', 'wushka' ),
        'labels' => $labels,
        'menu_icon' => 'dashicons-database-add',
        'supports' => array('title', 'editor', 'thumbnail'),
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
add_action( 'init', 'create_custom_post_type_product_release', 0 );