<?php

/*
Template Name: Teacher - My Bookmarks
*/

//Is User Logged In AND is user a teacher?
if ( ! is_user_logged_in() || ! current_user_can("teacher") ) {
	//Redirect to Login Page
	wp_redirect( home_url()."/wp-login.php" );
	exit;
}
//Check for Teacher Role

//load Teacher Dashboard Functions
require_once( 'functions/bookmarks/class_my-bookmarks.php');

/* --- Deploy Page --- */
//Add Header
get_header();

$c_bookmarks = new Wushka_Bookmarks($current_user->ID, 'teacher');

$c_bookmarks->load_stylesheets();

$c_bookmarks->load_page();

//Add Footer
include 'dashboard_options.php';
get_footer();

/* ---------- END OF TEMPLATE FILE ---------- */
?>