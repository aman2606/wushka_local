<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//exits when file is load directly 
if ( !function_exists( 'add_action' ) ) {
	echo "This page cannot be called directly.";
	exit;
}
global $wpdb;
/* --------------------------------------------------------------------
 * 	
 * 					Uninstallation for My Statistics
 * Following tables to be uninstalled are:
 * 	- my_statistics_lz_events
 * 
 * 
 * -------------------------------------------------------------------- */


//---------------------- LESSONZONE STATISTICS TABLE -------------------------
    //$table = $wpdb->prefix ."my_statistics_lessonzone";
    //$structure = "drop table if exists $table";
    //$wpdb->query($structure);  

/* EOF */
?>