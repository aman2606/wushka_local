<?php 
/*
	Plugin Name: LessonZone - Statistics
	Plugin URI:
	Description: Framework for storing and tracking LessonZone based information.
	Author: ESISS Pty Ltd.
	Author URI:
	Version: 1.0
	License: GPLv2
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* ---------- GET ROOT PLUGIN FOLDER PATH ---------- */
function my_statistics_plugin_path( $type = null ) {
	if( ! $type ) {
		return null;
	}
	if($type == 'dir') {
		$get_link = plugin_dir_path(__FILE__);
	} else if ($type == 'url') {
		$get_link = plugin_dir_url(__FILE__);
	}
	return $get_link;
}
/* ----------  END GET ROOT PLUGIN FOLDER PATH ---------- */

/* -------------------- MENU DECLARATION -------------------- */
//Register the Admin Settings Page
function register_my_statistics_menu_page() {
	add_menu_page( 'LessonZone Statistics', 'LZ Statistics', 'administrator', 'my-statistics', 'my_statistics_menu_page');
}
add_action( 'admin_menu', 'register_my_statistics_menu_page' );

//Load statistics content for menu page
function my_statistics_menu_page() {
	$plugins_dir = my_statistics_plugin_path('dir');
	require_once($plugins_dir.'assets/my-statistics-menu-page.php');
}
/* ----------------- END MENU DECLARATION ------------------- */

/* ----------------- INSTALLATION FUNCTIONS ----------------- */
function my_statistics_activate() {
	$plugins_dir = my_statistics_plugin_path('dir');
	require_once($plugins_dir.'assets/my-statistics-install.php');
}
register_activation_hook(__FILE__,'my_statistics_activate');

function my_statistics_deactivate() {
	$plugins_dir = my_statistics_plugin_path('dir');
	require_once($plugins_dir.'assets/my-statistics-uninstall.php');
}
register_deactivation_hook(__FILE__ , 'my_statistics_deactivate' );

/* --------------- END INSTALLATION FUNCTIONS ---------------- */

/* ---------- INCLUDE FUNCTIONS FILE ---------- */
$plugins_url = my_statistics_plugin_path();
require_once( $plugins_url.'assets/my-statistics-functions.php');
/* ---------- END INCLUDE FUNCTIONS FILE ---------- */

/* ------------ ENQUEUE STYLE/SCRIPT FILES ------------ */
function my_statistics_enqueue() {
	
	$plugin_url = my_statistics_plugin_path('url');
	
	wp_register_style( 'my-statistics-style', $plugin_url.'css/my-statistics-style.css' );
	wp_register_script( 'my-statistics-script', $plugin_url.'js/my-statistics-script.js', array('jquery') );
	
	wp_localize_script( 'my-statistics-script', 'mystat_ajax', array('ajax_url' => $plugin_url."assets/my-statistics-ajax.php", 'data_check' => wp_create_nonce('my_statistics_data_check') ) );
	
	wp_enqueue_style ( 'my-statistics-style' );
	wp_enqueue_script( 'my-statistics-script');	

}
add_action( 'wp_enqueue_scripts', 'my_statistics_enqueue' );
add_action( 'admin_enqueue_scripts', 'my_statistics_enqueue' );
/* ---------- END ENQUEUE STYLE/SCRIPT FILES ---------- */ 



/* EOF */ 
?>