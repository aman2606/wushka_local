<?php

/*
  Plugin Name: Site Plugin for lessonlocker.com
  Description: Site specific code changes for lessonlocker.com
  Version: 1.0
  Author: ESISS Pty Ltd
 */
/* Start Adding Functions Below this Line */
//Allow Text widgets to execute shortcodes
add_filter('widget_text', 'do_shortcode');

// get taxonomies terms links
function custom_taxonomies_terms_links($atts) {
    extract(shortcode_atts(array('custom_taxonomy' => '', 'separator' => ', ', 'link' => 0), $atts));
    global $post;
    if ($link > 0) {
        $terms = get_the_term_list($post->ID, $custom_taxonomy, '', $separator, '');
        if (!is_wp_error($terms)) {
            return $terms;
        }
    } else {
        $term_list = get_the_terms($post->ID, $custom_taxonomy);
        if ($term_list && !is_wp_error($term_list)) {
            $terms = array();
            foreach ($term_list as $term) {
                $terms[] = $term->name;
            }
            return join($separator, $terms);
        }
    }
}

add_shortcode('post_terms', 'custom_taxonomies_terms_links');

function post_meta($atts) {

//$post->post_author
//$post->post_date
//$post->post_date_gmt
//$post->post_content
//$post->post_content_filtered
//$post->post_title
//$post->post_excerpt
//$post->post_status
//$post->post_type
//$post->comment_status
//$post->ping_status
//$post->post_password
//$post->post_name
//$post->to_ping
//$post->pinged
//$post->post_modified
//$post->post_modified_gmt
//$post->post_parent
//$post->menu_order
//$post->guid
//$post->category
//$post->post_mime_type
//$post->comment_count
//
    extract(shortcode_atts(array('field' => 'ID', 'custom' => false), $atts));
    global $post;
    if ($field == "post_password") {
        return "oh no, not that easily";
    }
    if (!$custom) {
        return $post->$field;
    }
    return get_post_meta($post->ID, $field, true);
}

add_shortcode('post_meta', 'post_meta');

function menu_shortcode($atts, $content = null) {
    extract(shortcode_atts(array('name' => null, 'depth' => 0), $atts));
    return wp_nav_menu(array('menu' => $name, 'depth' => $depth, 'echo' => false));
}

add_shortcode('menu', 'menu_shortcode');

function menu_switch($atts, $content = null) {
	extract(shortcode_atts(array('name' => null, 'depth' => 0), $atts));

	$query_tax = get_query_var('taxonomy');
	$query_term = get_query_var('term');

	//error_log( '--- Side Menu ---');
	//error_log( 'Query Taxonomy: '.$query_tax);
	//error_log( 'Query Term: '.$query_term );

	$term = get_term_by('slug', $query_term, $query_tax);
	//error_log( 'Term Object: '.print_r($term, true) );
	
	if ( $term && !empty( $term->parent ) ) { 
		$parent = get_term($term->parent, $query_tax);
		
		//error_log( 'Parent Object: '.print_r($parent, true) );

		if ( $parent && !empty($parent->parent) ) {
			$parent = get_term($parent->parent, $query_tax);
			//error_log('Nested Parent Object: '.print_r($parent, true));
		}

		$p_name = isset( $parent->name ) ? $parent->name : null;
		$p_term = isset( $parent->term_id ) ? $parent->term_id : null;	
		$nested = 1;
	} else {
		$tax = get_taxonomy($term->taxonomy);
		$tax_name = $tax->label;
			//error_log($tax_name);
		$p_name = $tax_name;
		$p_term = 0;
		$nested = 0;

	}
	
	echo '<h4>' . $p_name . '</h4>';
	echo '<ul class="menu">';
	$args = array(
			'taxonomy'      => $query_tax,
			'title_li'      => null,
			'hierarchical'  => $nested,
			'show_count'    => 0,
			'child_of'      => $p_term,
			'walker'        => new Navigator_Walker_Category,
			'orderby'	=> 'slug',
			'order'		=> 'ASC'
	);
	wp_list_categories($args);
	echo '</ul>';
	return;
}

add_shortcode('menuswitch', 'menu_switch');


// IMPORTATNT - this function needs to override that in WooCommerce Subscriptions classes/class-we-subscriptions-cart
//function cart_needs_payment($needs_payment, $cart) {
//    //if ( self::cart_contains_subscription() && $cart->total == 0 && false === $needs_payment && $cart->recurring_total > 0 && 'yes' !== get_option( WC_Subscriptions_Admin::$option_prefix . '_turn_off_automatic_payments', 'no' ) )
//    // added check for _accept_manual_renewals, if a cart is fully paid even with a subscription, credit card details should not be required if:
//    //   a) _turn_off_automatic_payments = N or
//    //   b) _accept_manual_renewals = Y
//    // functionality should already be in place to cater for situation where CC details are removed from an account, so a subscription cannot be auto-renewed,
//    // paying via a coupon for the original subscription period and then not having a automatic payment method for renewal should not differ to this.
//    if (self::cart_contains_subscription() && $cart->total == 0 && false === $needs_payment && $cart->recurring_total > 0 && 'yes' !== get_option(WC_Subscriptions_Admin::$option_prefix . '_turn_off_automatic_payments', 'no') && 'yes' !== get_option(WC_Subscriptions_Admin::$option_prefix . '_accept_manual_renewals', 'no'))
//        $needs_payment = true;
//
//    return $needs_payment;
//}

function custom_admin_columns($columns) {
    unset($columns['tags']);
    unset($columns['categories']);

    return $columns;
}

function custom_admin_column_init() {
    add_filter('manage_posts_columns', 'custom_admin_columns');
}

add_action('admin_init', 'custom_admin_column_init');

class lessonzone_walker_nav_menu extends Walker_Nav_Menu {

    function start_lvl(&$output, $depth = 0, $args = array()) {
        if ($depth < 3) {
            $indent = str_repeat("\t", $depth);
            $output .= "\n$indent<ul class=\"sub-menu\">\n";
        }
    }

    function end_lvl(&$output, $depth = 0, $args = array()) {
        if ($depth < 3) {
            $indent = str_repeat("\t", $depth);
            $output .= "$indent</ul>\n";
        }
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $indent = ( $depth ) ? str_repeat("\t", $depth) : '';

        $class_names = $value = '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        if (strstr($class_names, 'heading') !== false && strstr($class_names, 'first-heading') === false && strstr($class_names, 'sub-heading') === false) {
            lessonzone_walker_nav_menu::end_lvl($output);
            $output .= "\n$indent<ul class=\"group-wrap\">\n";
        }

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $value . $class_names . '>';

        $atts = array();
        $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';
        $atts['href'] = !empty($item->url) ? $item->url : '';
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ( 'href' === $attr ) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    function end_el(&$output, $item, $depth = 0, $args = array()) {
        $output .= "</li>\n";
    }

}

class Navigator_Walker_Category extends Walker_Category {

    /**
     * Starts the list before the elements are added.
     *
     * @see Walker::start_lvl()
     *
     * @since 2.1.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of category. Used for tab indentation.
     * @param array  $args   An array of arguments. Will only append content if style argument value is 'list'.
     *                       @see wp_list_categories()
     */
    function start_lvl(&$output, $depth = 0, $args = array()) {
        if ('list' != $args['style'] || $depth == 0)
            return;

        $indent = str_repeat("\t", $depth);
        $output .= "$indent<ul class='children$depth'>\n";
    }

    /**
     * Ends the list of after the elements are added.
     *
     * @see Walker::end_lvl()
     *
     * @since 2.1.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of category. Used for tab indentation.
     * @param array  $args   An array of arguments. Will only append content if style argument value is 'list'.
     *                       @wsee wp_list_categories()
     */
    function end_lvl(&$output, $depth = 0, $args = array()) {
        if ('list' != $args['style'] || $depth == 0)
            return;

        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    /**
     * Start the element output.
     *
     * @see Walker::start_el()
     *
     * @since 2.1.0
     *
     * @param string $output   Passed by reference. Used to append additional content.
     * @param object $category Category data object.
     * @param int    $depth    Depth of category in reference to parents. Default 0.
     * @param array  $args     An array of arguments. @see wp_list_categories()
     * @param int    $id       ID of the current category.
     */
    function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0) {
        extract($args);

        $cat_name = esc_attr($category->name);
        $cat_name = apply_filters('list_cats', $cat_name, $category);
        $link = '<a href="' . esc_url(get_term_link($category)) . '" ';
        if ($use_desc_for_title == 0 || empty($category->description))
            $link .= 'title="' . esc_attr(sprintf(__('View all resources filed under %s'), $cat_name)) . '"';
        else
            $link .= 'title="' . esc_attr(strip_tags(apply_filters('category_description', $category->description, $category))) . '"';
        $link .= '>';
        $link .= $cat_name . '</a>';

        if (!empty($show_count))
            $link .= ' (' . intval($category->count) . ')';

        if ('list' == $args['style']) {
            $output .= "\t<li";
            $class = 'cat-item cat-item-' . $category->term_id;
            if (!empty($current_category)) {
                $_current_category = get_term($current_category, $category->taxonomy);
                if ($category->term_id == $current_category)
                    $class .= ' current-cat';
                elseif ($category->term_id == $_current_category->parent)
                    $class .= ' current-cat-parent';
            }
            //$parent = get_terms()
            if (isset($has_children) && !empty($has_children)) {
                $class .= ' heading';
                $link = $cat_name;
            }
            $output .= ' class="' . $class . '"';
            $output .= ">$link\n";
        } else {
            $output .= "\t$link<br />\n";
        }
    }

    /**
     * Ends the element output, if needed.
     *
     * @see Walker::end_el()
     *
     * @since 2.1.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $page   Not used.
     * @param int    $depth  Depth of category. Not used.
     * @param array  $args   An array of arguments. Only uses 'list' for whether should append to output. @see wp_list_categories()
     */
    function end_el(&$output, $page, $depth = 0, $args = array()) {
        if ('list' != $args['style'])
            return;

        $output .= "</li>\n";
    }

}

/* Stop Adding Functions Below this Line */
?>