<?php

/*
  Plugin Name: Site Database Plugin for wushka
  Description: Site database specific changes for wushka
  Version: 2.1
  Author: Intygrate Pty Ltd
 */
/* Start Adding Functions Below this Line */
// fields
$prefix = 'esiss_';

global $wushka_licence_db_version;
$wushka_licence_db_version = "1.2";

function wushka_licence_update_db_check() {
    global $wushka_licence_db_version;
    if (get_site_option('wushka_licence_db_version') != $wushka_licence_db_version) {
        wushka_licence_install();
    }
}

add_action('plugins_loaded', 'wushka_licence_update_db_check');

/*
  When the plugin is activated, 'install' the table.
 */

function wushka_licence_install() {

    global $wpdb;
    global $wushka_licence_db_version;
    $installed_ver = get_option("wushka_licence_db_version");

    if ($installed_ver != $wushka_licence_db_version) {
        $table_name = $wpdb->prefix . "wushka_licence";

        $sql = "CREATE TABLE $table_name (
          account_id varchar(20) NOT NULL,
          licence_product VARCHAR(100),
          licence_type VARCHAR(100) NOT NULL,
          licence_end datetime,
          licence_count MEDIUMINT(6) UNSIGNED NOT NULL,
          created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          comment VARCHAR(255),
          PRIMARY KEY  (account_id, licence_product)
        );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);

        update_option("wushka_licence_db_version", $wushka_licence_db_version);
    }
}

$ebook_custom_meta_box = array(
    'id' => 'attributes',
    'title' => 'eBook attributes',
    'page' => 'ebook',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Resource Id',
            'desc' => 'Enter the unique resource identifier',
            'id' => $prefix . 'resource_id',
            'type' => 'text',
            'required' => true,
            'std' => ''
        ),
        array(
            'name' => 'ISBN',
            'desc' => 'Enter the ISBN',
            'id' => $prefix . 'isbn',
            'type' => 'text',
            'required' => true,
            'stf' => ''
        ),
        array(
            'name' => 'Illustrator / Photographer',
            'desc' => 'Enter the Illustrator / Photographer name',
            'id' => $prefix . 'illustrator',
            'type' => 'text',
            'required' => false,
            'std' => ''
        ),
        array(
            'name' => 'Page count',
            'desc' => 'Enter the number of pages in this ebook',
            'id' => $prefix . 'page_count',
            'type' => 'number',
            'options' => array(
                'min' => '0',
                'max' => '999',
                'step' => '1'
            ),
            'required' => false,
            'std' => '0'
        ),
        array(
            'name' => 'Word count',
            'desc' => 'Enter the number of words in this ebook',
            'id' => $prefix . 'word_count',
            'type' => 'number',
            'options' => array(
                'min' => '0',
                'max' => '9999',
                'step' => '1'
            ),
            'required' => false,
            'std' => '0'
        ),
        array(
            'name' => 'High Frequency Words',
            'desc' => 'Enter the High Frequency Words',
            'id' => $prefix . 'hfw',
            'type' => 'text',
            'required' => false,
            'std' => ''
        ),
        array(
            'name' => 'Tricky Words',
            'desc' => 'Enter the Tricky Words',
            'id' => $prefix . 'tricky',
            'type' => 'text',
            'required' => false,
            'std' => ''
        ),
        array(
            'name' => 'Approach',
            'desc' => 'Enter the Approach details',
            'id' => $prefix . 'approach',
            'type' => 'text',
            'required' => false,
            'std' => ''
        ),
        array(
            'name' => 'Strategy / Skills',
            'desc' => 'Enter the Strategy / Skills',
            'id' => $prefix . 'strategy',
            'type' => 'text',
            'required' => false,
            'std' => ''
        ),
        array(
            'name' => 'ERP Item Code',
            'desc' => 'Enter the ERP Item Code',
            'id' => $prefix . 'item_code',
            'type' => 'text',
            'required' => false,
            'std' => ''
        ),
        array(
            'name' => 'Curriculum Code',
            'id' => $prefix . 'sounds',
            'type' => 'editor'
        ),
        array(
            'name' => 'Free sample',
            'desc' => 'Free sample indicator or other priority flag',
            'id' => $prefix . 'free_sample',
            'type' => 'text',
            'required' => false,
            'std' => ''
        ),
        array(
            'name' => 'Sounds',
            'desc' => '',
            'id' => $prefix . 'sounds',
            'type' => 'text',
            'required' => false,
            'std' => ''
        )
    )
);

add_action('admin_menu', 'wushka_add_ebook_box');

// Add meta box
function wushka_add_ebook_box() {
    global $ebook_custom_meta_box;
    add_meta_box($ebook_custom_meta_box['id'], $ebook_custom_meta_box['title'], 'wushka_show_ebook_meta_box', $ebook_custom_meta_box['page'], $ebook_custom_meta_box['context'], $ebook_custom_meta_box['priority']);
}

// Callback function to show fields in meta box
function wushka_show_ebook_meta_box() {
    global $ebook_custom_meta_box, $post;
    // Use nonce for verification
    echo '<input type="hidden" name="wushka_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    echo '<table class="form-table">';
    foreach ($ebook_custom_meta_box['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);
        echo '<tr>',
        '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
        '<td>';
        switch ($field['type']) {
            case 'text':
                echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '<br />', $field['desc'];
                break;
            case 'number':
                echo '<input type="number" name="', $field['id'], '" id="', $field['id'], '" min="', $field['options']['min'], '" max="', $field['options']['max'], '" step="', $field['options']['step'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:', strlen($field['options']['max']) * 2, '%" />', '<br />', $field['desc'];
                break;
            case 'textarea':
                echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '<br />', $field['desc'];
                break;
            case 'select':
                echo '<select name="', $field['id'], '" id="', $field['id'], '">';
                foreach ($field['options'] as $option) {
                    echo '<option ', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                }
                echo '</select>';
                break;
            case 'radio':
                foreach ($field['options'] as $option) {
                    echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
                }
                break;
            case 'checkbox':
                echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />', '<br />', $field['desc'];
                break;
            case 'editor':
                echo wp_editor($meta ? $meta : $field['std'], $field['id']);
                break;
        }
        echo '</td><td>',
        '</td></tr>';
    }
    echo '</table>';
}

add_action('save_post', 'wushka_save_ebook_data');

// Save data from meta box
function wushka_save_ebook_data($post_id) {
    global $ebook_custom_meta_box;
    // verify nonce
    if (!isset($_POST['wushka_meta_box_nonce']) || !wp_verify_nonce($_POST['wushka_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    foreach ($ebook_custom_meta_box['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}

// add custom taxonomies to 'ebook' post type
function add_custom_ebook_taxonomies() {
    // register_taxonomy('ebook-author', 'ebook', array(
    //     'hierarchical' => false,
    //     'labels' => array(
    //         'name' => _x('Authors', 'taxonomy general name'),
    //         'singular_name' => _x('Author', 'taxonomy singular name'),
    //         'menu_name' => __('Authors'),
    //         'all_items' => __('All Authors'),
    //         'edit_item' => __('Edit Author'),
    //         'view_item' => __('View Author'),
    //         'update_item' => __('Update Author'),
    //         'add_new_item' => __('Add New Author'),
    //         'new_item_name' => __('New Author'),
    //         'parent_item' => __('Parent Author'),
    //         'parent_item_colon' => __('Parent Author:'),
    //         'search_items' => __('Search Author'),
    //         'popular_items' => __('Popular Author'),
    //         'separate_items_with_commas' => __('Separate Authors with commas'),
    //         'add_or_remove_items' => __('Add or remove Authors'),
    //         'choose_from_most_used' => __('Choose from the most used Authors'),
    //         'not_found' => __('No Authors found'),
    //     ),
    //     'public' => true,
    //     'show_ui' => true,
    //     'show_in_nav_menus' => true,
    //     'show_tagcloud' => true,
    //     'show_admin_column' => true,
    //     'update_count_callback' => '',
    //     'query_var' => true,
    //     'rewrite' => array(
    //         'slug' => 'ebook-author',
    //         'with_front' => false,
    //         'hierarchical' => true
    //     ),
    // ));
    // register_taxonomy_for_object_type('ebook-author', 'ebook');

    // register_taxonomy('series', 'ebook', array(
    //     'hierarchical' => true,
    //     'labels' => array(
    //         'name' => _x('Series', 'taxonomy general name'),
    //         'singular_name' => _x('Series', 'taxonomy singular name'),
    //         'menu_name' => __('Series'),
    //         'all_items' => __('All Series'),
    //         'edit_item' => __('Edit Series'),
    //         'view_item' => __('View Series'),
    //         'update_item' => __('Update Series'),
    //         'add_new_item' => __('Add New Series'),
    //         'new_item_name' => __('New Series'),
    //         'parent_item' => __('Parent Series'),
    //         'parent_item_colon' => __('Parent Series:'),
    //         'search_items' => __('Search Series'),
    //         'popular_items' => __('Popular Series'),
    //         'separate_items_with_commas' => __('Separate Series with commas'),
    //         'add_or_remove_items' => __('Add or remove Series'),
    //         'choose_from_most_used' => __('Choose from the most used Series'),
    //         'not_found' => __('No Series found'),
    //     ),
    //     'public' => true,
    //     'show_ui' => true,
    //     'show_in_nav_menus' => true,
    //     'show_tagcloud' => true,
    //     'show_admin_column' => true,
    //     'update_count_callback' => '',
    //     'query_var' => true,
    //     'rewrite' => array(
    //         'slug' => 'series',
    //         'with_front' => false,
    //         'hierarchical' => false
    //     ),
    // ));
    // register_taxonomy_for_object_type('series', 'ebook');

    register_taxonomy('reading-level', 'ebook', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => _x('Reading Levels', 'taxonomy general name'),
            'singular_name' => _x('Reading Level', 'taxonomy singular name'),
            'menu_name' => __('Reading Levels'),
            'all_items' => __('All Reading Levels'),
            'edit_item' => __('Edit Reading Level'),
            'view_item' => __('View Reading Level'),
            'update_item' => __('Update Reading Level'),
            'add_new_item' => __('Add New Reading Level'),
            'new_item_name' => __('New Reading Level'),
            'parent_item' => __('Parent Reading Level'),
            'parent_item_colon' => __('Parent Reading Level:'),
            'search_items' => __('Search Reading Level'),
            'popular_items' => __('Popular Reading Level'),
            'separate_items_with_commas' => __('Separate Reading Level with commas'),
            'add_or_remove_items' => __('Add or remove Reading Levels'),
            'choose_from_most_used' => __('Choose from the most used Reading Levels'),
            'not_found' => __('No Reading Levels found'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_admin_column' => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'reading-level',
            'with_front' => false,
            'hierarchical' => false
        ),
    ));
    register_taxonomy_for_object_type('reading-level', 'ebook');

    register_taxonomy('year-level', 'ebook', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => _x('Year Levels', 'taxonomy general name'),
            'singular_name' => _x('Year Level', 'taxonomy singular name'),
            'menu_name' => __('Year Level'),
            'all_items' => __('All Year Levels'),
            'edit_item' => __('Edit Year Level'),
            'view_item' => __('View Year Level'),
            'update_item' => __('Update Year Level'),
            'add_new_item' => __('Add New Year Level'),
            'new_item_name' => __('New Year Level'),
            'parent_item' => __('Parent Year Level'),
            'parent_item_colon' => __('Parent Year Level:'),
            'search_items' => __('Search Year Level'),
            'popular_items' => __('Popular Year Levels'),
            'separate_items_with_commas' => __('Separate Year Levels with commas'),
            'add_or_remove_items' => __('Add or remove Year Levels'),
            'choose_from_most_used' => __('Choose from the most used Year Levels'),
            'not_found' => __('No Year Levels found'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_admin_column' => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'year-level',
            'with_front' => false,
            'hierarchical' => false
        ),
    ));
    register_taxonomy_for_object_type('year-level', 'ebook');

    register_taxonomy('genre', 'ebook', array(
        'hierarchical' => false,
        'labels' => array(
            'name' => _x('Genres', 'taxonomy general name'),
            'singular_name' => _x('Genre', 'taxonomy singular name'),
            'menu_name' => __('Genres'),
            'all_items' => __('All Genres'),
            'edit_item' => __('Edit Genre'),
            'view_item' => __('View Genre'),
            'update_item' => __('Update Genre'),
            'add_new_item' => __('Add New Genre'),
            'new_item_name' => __('New Genre'),
            'parent_item' => __('Parent Genre'),
            'parent_item_colon' => __('Parent Genre:'),
            'search_items' => __('Search Genres'),
            'popular_items' => __('Popular Genres'),
            'separate_items_with_commas' => __('Separate Genres with commas'),
            'add_or_remove_items' => __('Add or remove Genres'),
            'choose_from_most_used' => __('Choose from the most used Genres'),
            'not_found' => __('No Genres found'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_admin_column' => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'genre',
            'with_front' => false,
            'hierarchical' => true
        ),
    ));
    register_taxonomy_for_object_type('genre', 'ebook');

    register_taxonomy('ebook-theme', 'ebook', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => _x('Theme', 'taxonomy general name'),
            'singular_name' => _x('Theme', 'taxonomy singular name'),
            'menu_name' => __('Theme'),
            'all_items' => __('All Themes'),
            'edit_item' => __('Edit Theme'),
            'view_item' => __('View Theme'),
            'update_item' => __('Update Theme'),
            'add_new_item' => __('Add New Theme'),
            'new_item_name' => __('New Theme'),
            'parent_item' => __('Parent Theme'),
            'parent_item_colon' => __('Parent Theme:'),
            'search_items' => __('Search Themes'),
            'popular_items' => __('Popular Themes'),
            'separate_items_with_commas' => __('Separate Themes with commas'),
            'add_or_remove_items' => __('Add or remove Theme'),
            'choose_from_most_used' => __('Choose from the most used Themes'),
            'not_found' => __('No Themes found'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_admin_column' => true,
        'update_count_callback' => '',
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'ebook-theme',
            'with_front' => false,
            'hierarchical' => true
        ),
    ));
    register_taxonomy_for_object_type('ebook-theme', 'ebook');

    register_taxonomy('fiction', 'ebook', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => _x('Fiction/Non-fiction', 'taxonomy general name'),
            'singular_name' => _x('Fiction/Non-fiction', 'taxonomy singular name'),
            'menu_name' => __('Fiction'),
            'all_items' => __('All Fiction/Non-fiction'),
            'edit_item' => __('Edit Fiction/Non-fiction'),
            'view_item' => __('View Fiction/Non-fiction'),
            'update_item' => __('Update Fiction/Non-fiction'),
            'add_new_item' => __('Add New Fiction/Non-fiction'),
            'new_item_name' => __('New Fiction/Non-fiction'),
            'parent_item' => __('Parent Fiction/Non-fiction'),
            'parent_item_colon' => __('Parent Fiction/Non-fiction:'),
            'search_items' => __('Search Fiction/Non-fiction'),
            'popular_items' => __('Popular Fiction/Non-fiction'),
            'separate_items_with_commas' => __('Separate Fiction/Non-fiction with commas'),
            'add_or_remove_items' => __('Add or remove Fiction/Non-fiction'),
            'choose_from_most_used' => __('Choose from the most used Fiction/Non-fiction'),
            'not_found' => __('No Fiction/Non-fiction found'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_admin_column' => true,
        'update_count_callback' => '',
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'fiction',
            'with_front' => false,
            'hierarchical' => false
        ),
    ));
    register_taxonomy_for_object_type('fiction', 'ebook');

    // register_taxonomy('esiss_kidstheme', 'ebook', array(
    //     'hierarchical' => true,
    //     'labels' => array(
    //         'name' => _x('Kid Themes', 'taxonomy general name'),
    //         'singular_name' => _x('Kid Theme', 'taxonomy singular name'),
    //         'menu_name' => __('Kid Themes'),
    //         'all_items' => __('All Kid Themes'),
    //         'edit_item' => __('Edit Kid Themes'),
    //         'view_item' => __('View Kid Themes'),
    //         'update_item' => __('Update Kid Themes'),
    //         'add_new_item' => __('Add New Kid Theme'),
    //         'new_item_name' => __('New Kid Theme'),
    //         'parent_item' => __('Parent Kid Theme'),
    //         'parent_item_colon' => __('Parent Kid Theme:'),
    //         'search_items' => __('Search Kid Themes'),
    //         'popular_items' => __('Popular Kid Themes'),
    //         'separate_items_with_commas' => __('Separate Kid Themes with commas'),
    //         'add_or_remove_items' => __('Add or remove Kid Themes'),
    //         'choose_from_most_used' => __('Choose from the most used Kid Themes'),
    //         'not_found' => __('No Kid Themes found'),
    //     ),
    //     'public' => true,
    //     'show_ui' => true,
    //     'show_in_nav_menus' => true,
    //     'show_tagcloud' => true,
    //     'show_admin_column' => true,
    //     'update_count_callback' => '',
    //     'query_var' => true,
    //     'rewrite' => array(
    //         'slug' => 'esiss_kidstheme',
    //         'with_front' => false,
    //         'hierarchical' => true
    //     ),
    // ));
    // register_taxonomy_for_object_type('esiss_kidstheme', 'ebook');

    register_taxonomy('strategy', 'ebook', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => _x('Strategies', 'taxonomy general name'),
            'singular_name' => _x('Strategy', 'taxonomy singular name'),
            'menu_name' => __('Strategy'),
            'all_items' => __('All Strategies'),
            'edit_item' => __('Edit Strategy'),
            'view_item' => __('View Strategy'),
            'update_item' => __('Update Strategy'),
            'add_new_item' => __('Add New Strategy'),
            'new_item_name' => __('New Strategy'),
            'parent_item' => __('Parent Strategy'),
            'parent_item_colon' => __('Parent Strategy:'),
            'search_items' => __('Search Strategies'),
            'popular_items' => __('Popular Strategies'),
            'separate_items_with_commas' => __('Separate Strategies with commas'),
            'add_or_remove_items' => __('Add or remove Strategy'),
            'choose_from_most_used' => __('Choose from the most used Strategies'),
            'not_found' => __('No Strategies found'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_admin_column' => true,
        'update_count_callback' => '',
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'strategy',
            'with_front' => false,
            'hierarchical' => true
        ),
    ));
    register_taxonomy_for_object_type('strategy', 'ebook');

    register_taxonomy('age', 'ebook', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => _x('Ages', 'taxonomy general name'),
            'singular_name' => _x('Age', 'taxonomy singular name'),
            'menu_name' => __('Age'),
            'all_items' => __('All Ages'),
            'edit_item' => __('Edit Age'),
            'view_item' => __('View Age'),
            'update_item' => __('Update Age'),
            'add_new_item' => __('Add New Age'),
            'new_item_name' => __('New Age'),
            'parent_item' => __('Parent Age'),
            'parent_item_colon' => __('Parent Age:'),
            'search_items' => __('Search Ages'),
            'popular_items' => __('Popular Ages'),
            'separate_items_with_commas' => __('Separate Ages with commas'),
            'add_or_remove_items' => __('Add or remove Age'),
            'choose_from_most_used' => __('Choose from the most used Ages'),
            'not_found' => __('No Ages found'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_admin_column' => true,
        'update_count_callback' => '',
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'age',
            'with_front' => false,
            'hierarchical' => true
        ),
    ));
    register_taxonomy_for_object_type('age', 'ebook');

    register_taxonomy('phonics-phase', 'ebook', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => _x('Phonics Phase', 'taxonomy general name'),
            'singular_name' => _x('Phonics Phase Level', 'taxonomy singular name'),
            'menu_name' => __('Phonics Phase Level'),
            'all_items' => __('All Phonics Phase Level'),
            'edit_item' => __('Edit Phonics Phase Level'),
            'view_item' => __('View Phonics Phase Level'),
            'update_item' => __('Update Phonics Phase Level'),
            'add_new_item' => __('Add New Phonics Phase Level'),
            'new_item_name' => __('New Phonics Phase Level'),
            'parent_item' => __('Parent Phonics Phase Level'),
            'parent_item_colon' => __('Parent Phonics Phase Level:'),
            'search_items' => __('Search Phonics Phase Level'),
            'popular_items' => __('Popular Phonics Phase Level'),
            'separate_items_with_commas' => __('Separate Phonics Phase Level with commas'),
            'add_or_remove_items' => __('Add or remove Phonics Phase Level'),
            'choose_from_most_used' => __('Choose from the most used Phonics Phase Level'),
            'not_found' => __('No Phonics Phase Level'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_admin_column' => true,
        'update_count_callback' => '',
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'phonics-phase-level',
            'with_front' => false,
            'hierarchical' => false
        ),
    ));
    register_taxonomy_for_object_type('phonics-phase', 'ebook');

    $getCountry = get_option('lzPA_setting_country');
    if (isset($getCountry)) {
        $splitCountry = explode("/", $getCountry);
        $getCountry = $splitCountry[1];
    }
    if (!isset($getCountry)) {
        $getCountry = 'Australia';
    }
    if ($getCountry == 'United Kingdom') {
        register_taxonomy('nc-level', 'ebook', array(
            'hierarchical' => true,
            'labels' => array(
                'name' => _x('NC Level', 'taxonomy general name'),
                'singular_name' => _x('NC Level', 'taxonomy singular name'),
                'menu_name' => __('NC Level'),
                'all_items' => __('All NC Level'),
                'edit_item' => __('Edit NC Level'),
                'view_item' => __('View NC Level'),
                'update_item' => __('Update NC Level'),
                'add_new_item' => __('Add New NC Level'),
                'new_item_name' => __('New NC Level'),
                'parent_item' => __('Parent NC Level'),
                'parent_item_colon' => __('Parent NC Level:'),
                'search_items' => __('Search NC Level'),
                'popular_items' => __('Popular NC Level'),
                'separate_items_with_commas' => __('Separate NC Level with commas'),
                'add_or_remove_items' => __('Add or remove NC Level'),
                'choose_from_most_used' => __('Choose from the most used NC Level'),
                'not_found' => __('No NC Level'),
            ),
            'public' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_admin_column' => true,
            'update_count_callback' => '',
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'nc-level',
                'with_front' => false,
                'hierarchical' => false
            ),
        ));
        register_taxonomy_for_object_type('nc-level', 'ebook');
    } else if ($getCountry == 'United States') {
        register_taxonomy('ebook-language', 'ebook', array(
            'hierarchical' => false,
            'labels' => array(
                'name' => _x('eBook Languages', 'taxonomy general name'),
                'singular_name' => _x('Language', 'taxonomy singular name'),
                'menu_name' => __('eBook Languages'),
                'all_items' => __('All eBook Languages'),
                'edit_item' => __('Edit eBook Language'),
                'view_item' => __('View eBook Language'),
                'update_item' => __('Update eBook Language'),
                'add_new_item' => __('Add New eBook Language'),
                'new_item_name' => __('New eBook Language'),
                'parent_item' => __('Parent Language'),
                'parent_item_colon' => __('Parent Language:'),
                'search_items' => __('Search eBook Languages'),
                'popular_items' => __('Popular eBook Languages'),
                'separate_items_with_commas' => __('Separate Languages with commas'),
                'add_or_remove_items' => __('Add or remove eBook Languages'),
                'choose_from_most_used' => __('Choose from the most used eBook Languages'),
                'not_found' => __('No eBook Languages found'),
            ),
            'public' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_admin_column' => true,
            'update_count_callback' => '',
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'ebook-language',
                'with_front' => false,
                'hierarchical' => true
            ),
        ));
        register_taxonomy_for_object_type('ebook-language', 'ebook');
    }

    register_taxonomy('school', 'user', array(
        'hierarchical' => false,
        'labels' => array(
            'name' => _x('Schools', 'taxonomy general name'),
            'singular_name' => _x('School', 'taxonomy singular name'),
            'menu_name' => __('Schools'),
            'all_items' => __('All Schools'),
            'edit_item' => __('Edit School'),
            'view_item' => __('View School'),
            'update_item' => __('Update School'),
            'add_new_item' => __('Add New School'),
            'new_item_name' => __('New School'),
            'parent_item' => __('Parent School'),
            'parent_item_colon' => __('Parent School:'),
            'search_items' => __('Search Schools'),
            'popular_items' => __('Popular Schools'),
            'separate_items_with_commas' => __('Separate Schools with commas'),
            'add_or_remove_items' => __('Add or remove Schools'),
            'choose_from_most_used' => __('Choose from the most used Schools'),
            'not_found' => __('No Schools found'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'update_count_callback' => 'wushka_update_school_term_count',
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'school',
            'with_front' => false
        ),
        'capabilities' => array(
            'manage_terms' => 'edit_users',
            'edit_terms' => 'edit_users',
            'delete_terms' => 'edit_users',
            'assign_terms' => 'read'
        ),
    ));
    register_taxonomy_for_object_type('school', 'user');
}

function wushka_update_school_term_count($terms, $taxonomy) {
    global $wpdb;

    foreach ((array) $terms as $term) {
        $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d", $term));

        do_action('edit_term_taxonomy', $term, $taxonomy);
        $wpdb->update($wpdb->term_taxonomy, compact('count'), array('term_taxonomy_id' => $term));
        do_action('edited_term_taxonomy', $term, $taxonomy);
    }
}

add_filter('parent_file', 'fix_user_school_page');

function fix_user_school_page($parent_file = '') {
	global $pagenow;

	if (!empty($_GET['taxonomy']) && $_GET['taxonomy'] == 'school' && $pagenow == 'edit-tags.php') {
            $parent_file = 'users.php';
	}

	return $parent_file;
}

add_action('admin_menu', 'wushka_add_school_admin_page' );

function wushka_add_school_admin_page() {

    $tax = get_taxonomy('school');
    add_users_page(
        esc_attr($tax->labels->menu_name ),
        esc_attr($tax->labels->menu_name ),
        $tax->cap->manage_terms,
        'edit-tags.php?taxonomy=' . $tax->name
    );
}

add_filter('manage_edit-school_columns', 'wushka_manage_school_user_column');

function wushka_manage_school_user_column($columns) {

    unset($columns['posts']);
    $columns['users'] = __('Users');
    return $columns;
}

add_action('manage_school_custom_column', 'wushka_manage_school_column', 10, 3 );

function wushka_manage_school_column($display, $column, $term_id) {

    if ('users' === $column) {
        $term = get_term($term_id, 'school');
        echo $term->count;
    }
}

add_action('show_user_profile', 'wushka_edit_user_school_section');
add_action('edit_user_profile', 'wushka_edit_user_school_section');

function wushka_edit_user_school_section($user) {

	$tax = get_taxonomy('school');

	if (!current_user_can($tax->cap->assign_terms)) {
            return;
        }
        
	$terms = wp_get_object_terms($user->ID, 'school');
        $term = 0;
        if (!empty($terms)) {
            $term = $terms[0]->term_id;
        }
        error_log('selected terms:' . print_r($terms, true));
        error_log('term:' . $term);
        ?>
	<h3><?php _e('School'); ?></h3>
	<table class="form-table">
            <tr>
                <td><?php wp_dropdown_categories('show_option_none=Select school&taxonomy=school&name=school&class=school&hide_empty=0&selected='.$term); ?></td>
            </tr>
	</table>
<?php }

add_action('delete_user', 'wushka_delete_user_object_term_relationships' );

function wushka_delete_user_object_term_relationships($user_id) {

    wp_delete_object_term_relationships($user_id, 'school');
}

add_action('personal_options_update', 'wushka_save_user_school_terms');
add_action('edit_user_profile_update', 'wushka_save_user_school_terms');

function wushka_save_user_school_terms($user_id) {

    $tax = get_taxonomy('school');

    if (!current_user_can('edit_user', $user_id) && current_user_can($tax->cap->assign_terms))
        return false;

    $term = esc_attr($_POST['school']);

    wp_set_object_terms($user_id, array(intval($term)), 'school', false);

    clean_object_term_cache($user_id, 'school');
}

// Register Custom Post Type
function add_custom_post_types() {

    $labels = array(
        'name' => _x('eBooks', 'Post Type General Name', 'wushka'),
        'singular_name' => _x('eBook', 'Post Type Singular Name', 'wushka'),
        'menu_name' => __('eBooks', 'wushka'),
        'parent_item_colon' => __('Parent eBooks:', 'wushka'),
        'all_items' => __('All eBooks', 'wushka'),
        'view_item' => __('View EBooks', 'wushka'),
        'add_new_item' => __('Add New eBook', 'wushka'),
        'add_new' => __('New eBook', 'wushka'),
        'edit_item' => __('Edit eBook', 'wushka'),
        'update_item' => __('Update eBook', 'wushka'),
        'search_items' => __('Search eBooks', 'wushka'),
        'not_found' => __('No eBooks found', 'wushka'),
        'not_found_in_trash' => __('No eBooks found in Trash', 'wushka'),
    );
    $args = array(
        'label' => __('eBooks', 'wushka'),
        'description' => __('eBook', 'wushka'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'comments', 'revisions', 'custom-fields',),
        'taxonomies' => array('category', 'post_tag'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
    );
    register_post_type('ebook', $args);
}

// Hook into the 'init' action
add_action('init', 'add_custom_post_types', 0);

add_action('init', 'add_custom_ebook_taxonomies', 0);

function wushkadb_rewrite_flush() {
    // First, we "add" the custom post type via the above written function.
    // Note: "add" is written with quotes, as CPTs don't get added to the DB,
    // They are only referenced in the post_type column with a post entry, 
    // when you add a post of this CPT.
    add_custom_post_types();

    // ATTENTION: This is *only* done during plugin activation hook in this example!
    // You should *NEVER EVER* do this on every page load!!
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'wushkadb_rewrite_flush');
/* Stop Adding Functions Below this Line */
?>