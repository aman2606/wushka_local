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
 * 					Installation for My Statistics
 * Following tables to be installed are:
 * 	- my_statistics_lz_events
 * 
 * 
 * -------------------------------------------------------------------- */


//---------------------- LESSONZONE STATISTICS TABLE -------------------------
$table = $wpdb->prefix ."my_statistics_lessonzone";
$structure = "CREATE TABLE IF NOT EXISTS $table ( ".
	"`id` 	  		  INT(13) NOT NULL AUTO_INCREMENT, ".
	"`user_id`  	  INT(13) NOT NULL, ".
	"`post_id`  	  INT(13) NOT NULL, ".
	"`attachment_id`  INT(13) NOT NULL, ".
	"`resource_id`    INT(13) NOT NULL, ".
	"`post_type` 	  VARCHAR(45) NOT NULL, ".
	"`event_type`	  VARCHAR(45) NOT NULL, ".
	"`event_location` VARCHAR(45) NOT NULL, ".
	"`is_sample`	  VARCHAR(1)  NOT NULL, ".
	"`date_created`	  DATETIME DEFAULT NULL, ".
	"PRIMARY KEY `id` (id) );";
$wpdb->query($structure);

/* EOF */
?>