<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//exits when file is load directly 
if ( !function_exists( 'add_action' ) ) {
	echo "This page cannot be called directly.";
	exit;
}
global $wpdb;

/* -----------------------------------------------------------------------------------------
 * 
 * 								Desclaration of Class Object
 * 
 *   
 *   
 * ---------------------------------------------------------------------------------------- */

class my_statistics_data_item {
	//Declare Class Variables
	var $title;
	var $slug;
	var $query;
	var $results;
	var $limit;
	var $orderby
	
	
	function initiate_results( $item_query ) {
		global $wpdb;
		$item_results = $wpdb->get_results( $item_query );
		
		return $item_results !== null;
	}
	
	
	
	
}

 /* EOF */
 ?>