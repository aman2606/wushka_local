<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//exits when file is load directly 
if ( !function_exists( 'add_action' ) ) {
	echo "This page cannot be called directly.";
	exit;
}

/** ============================================================================
 * 
 * 							My Area Statistics Page
 * The Following Statistics will be displayed Here:
 * - Most Flagged Resources 	(Top 10)
 * - Most Collected Resources 	(Top 10)
 * - Largest Collections 		(Top 10)
 * - Most Followed Collections 	(Top 10)
 * - Most Saved Collections 	(Top 10)
 * - User with most Followers 	(Top 10)
 * - User with most Flags 		(Top 10)
 * - User with most Collections (Top 10)
 * - Top User Overall 			(Top 10)
 * 
 * ============================================================================ **/

/* ------------------- User Status Check ---------------------
 * Ensure that the current user logged in has admin privileges
*/
function my_statistics_user_check() {	
	//Logged In?
	if( ! is_user_logged_in() ) {
		//No User, Abort Validation
		////error_log('my statistics: user is not logged in, no access allowed.');
		return false;
		die();
	}
	//Is Admin?
	if ( ! current_user_can('manage_options') ) {
		//error_log('my statistics: user is not admin, no access allowed.');
		return false;
	}
	//error_log('my statistics: admin user is logged in, return validation');
	return wp_get_current_user()->ID;
}
/* ------------------- Create User Verification ---------------------
 * Ensure that the current user logged in has admin privileges 
 */
function my_statistics_verify_start() {
	//Check User
	$user_id = my_statistics_user_check();
	if( ! $user_id ) {
		return false;
	}
	//return user nonce
	return wp_create_nonce("my-statistics-admin-user-$user_id-nonce");	
}
/* ------------------- Check User Verification ---------------------
 * Ensure that the current user logged in has admin privileges
*/
function my_statistics_verify_check( $chk_usr_nce = null ) {
	//Check User
	$user_id = my_statistics_user_check();
	if( ! $user_id ) {
		die();
	}
	
	if( ! $chk_usr_nce || !wp_verify_nonce( $chk_usr_nce, "my-statistics-admin-user-$user_id-nonce" ) ) {
		die();
	}
}


function my_statistics_get_items($current = 'user') {
	
	$plugins_dir = my_statistics_plugin_path('dir');
	require_once ($plugins_dir.'assets/my-statistics-items.php');
	
	foreach ($data_item as $item) {
		if ( is_array( $item['menu']) ) {
			if ( ! in_array( $current, $item['menu'] ) ) {
				continue;
			}
		} else {
			if ( $current !== $item['menu'] ) {
				continue;
			}
		}
		$return_item[] = $item;	
	}
	return $return_item;
}


/*	------------------ Declare Statistic Data ----------------------
 * This Function declares the individual 'statistics' you wish to gather.
 * This is passed back to the statistics page for gathering of Data.
 * Each statistic must have the following parameters:
 *  - slug (unique identification name)
 *  - title (Front end Title of statistic (will appear in menu)
 *  
*/
function my_statistics_declare_stats($ms_chk_nce = null, $current = 'user') {
	
	my_statistics_verify_check( $ms_chk_nce );
	
	//Add Data for each statistic you wish to create
	//error_log('my statistics: (get items) - start');
	$data_fields = my_statistics_get_items($current);
	
	//error_log('my statistics: (get items) - done');
	//Gather Query for each item
	//error_log('my statistics: (get item data) - start');
	if( isset( $data_fields ) && count( $data_fields ) > 0 ) {
		$data_fields = my_statistics_get_data($data_fields);
		//error_log('my statistics: (get item data) - end');
	} else {
		//error_log('my statistics: (get item data) - ERROR');
	}
	
	return $data_fields;
}

/*	------------------ Gather Statistic Data ----------------------
 * This function is where we will declare the query for each type of statistic.
 * Will be determined by the preset slug on the settings page.
 */
 function my_statistics_get_data( $data_fields = array() ) {
 	//Declare Globals
 	global $wpdb;
 	//Decare Variables
 	
 	if( ! $data_fields || ! count($data_fields) > 0 ) {
 		//No slug passed, return false
 		return null;
 	} else {
 		
 		/*
 		 *  Data Loop
 		 *   - Gather Perpared Queries for each data field
 		 *   - Get Results of each data feild
 		 *   - return all data to template for processing into HTML
 		 */
		foreach ( $data_fields as $field_id => $field ) {
			
			// ----- Step 1 - Get Query for this field
			//error_log('my statistics: (get queries)('.$field['slug'].') - start');
			if ( ! $field['query'] ) {
 				//error_log('my statistics: (get queries)('.$field['slug'].') - ERROR');
	 			continue;
	 		}		
	 		//error_log('my statistics: (get queries)('.$field['slug'].') - done');
	 		
	 		// ----- Step 2 - Get Results for this field
	 		//error_log('my statistics: (get results)('.$field['slug'].') - start');
	 		$field['results'] = $wpdb->get_results($field['query']);
	 		if($field['results'] == null ) {
	 			//error_log('my statistics: (get results)('.$field['slug'].') - ERROR');
	 			continue;
	 		} 
	 		//error_log('my statistics: (get results)('.$field['slug'].') - done');
	 		
	 		// ----- Step 3 - Print Results into HTML	
 			$data_fields[$field_id]['query'] = $field['query'];
	 		$data_fields[$field_id]['results'] = $field['results'];
	 		
	 		////error_log("{$field['title']} QUERY : ".print_r($data_fields[$field_id]['query'], true) );
	 		////error_log("{$field['title']} RESULTS : ".print_r($data_fields[$field_id]['results'], true) );
	 		
	 	} //End Data Fields Foreach
	 	
	 	//error_log('my statistics: (get items) - done ( result count - '.count( $data_fields[$field_id]['results']).' )' );
	 	return $data_fields;
 	} 
}
 
function my_statistics_create_event( $user_id = null, $post_id = null, $attachment_id = null, $res_type = null, $btn_type = null, $event_location = null) {
	global $wpdb;
	//error_log('my statistics create event function - BEGIN ');
	if ( ! isset( $user_id, $post_id, $attachment_id, $res_type, $btn_type ) ) {
		//error_log('my statistics create event function - FAILED SET CHECK ');
		return 0;
	}
	//Is this a free sample resource?
	$is_sample = 'N';
	$get_sample = get_post_meta( $post_id, 'esiss_free_sample', true );
	if( isset( $get_sample ) && $get_sample == 'Y' ) {
		$is_sample = 'Y';
	}
	//Gather Resource IDs
	$resource_id = get_post_meta( $post_id, 'esiss_resource_id', true );
	if ( ! isset( $resource_id ) ) {
		return 0;
	}
	
	//error_log('my statistics create event function - RUNNING INSERT');
	$wpdb->insert(
		$wpdb->prefix.'my_statistics_lessonzone',
			array(
					'user_id'  		=> $user_id,
					'post_id' 		=> $post_id,
					'attachment_id' => $attachment_id,
					'resource_id' 	=> $resource_id,
					'post_type' 	=> $res_type,
					'event_type'	=> $btn_type,
					'event_location'=> $event_location,
					'is_sample'		=> $is_sample,
					'date_created'	=> date("Y-m-d h:i:s"),
			),
			array('%d', '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s')
	);
	
	if ( ! $wpdb->insert_id ) {
		//error_log('my statistics create event function - FAILED INSERT');
		return 0;
	} else {
		//error_log('my statistics create event function - INSERTED!');
		return 1;
	}
	//error_log('my statistics create event function - END - Return FALSE ');
	return 0;
}

function my_statistics_menu_html(){
	//Create HTML for menu to help organise statistics tables
	//Menus are a element of the data_item array
	//Data items will be looped, only items with the correct menu value will be displayed  
	$current = 'user';
	if ( isset( $_GET['stat_menu'] ) ) {
		$current = $_GET['stat_menu'];
	}
	
	$menu_items[] = array('name' => 'user' , 'value' => 'Users' 	);
	$menu_items[] = array('name' => 'post' , 'value' => 'Resources' );
	$menu_items[] = array('name' => 'ebook', 'value' => 'eBooks' 	);
	$menu_items[] = array('name' => 'zone' , 'value' => 'My Zone' 	);
	

	$html_menu  = '<div class="statistics-menu-wrap">';
		$html_menu .= '<ul class="statistics-menu-shelf">';
			$html_menu .= '<div class="item_separator"></div>';
			
			foreach ( $menu_items as $no => $item ) {
				if ($no > 0) {
					$html_menu .= '<div class="item_separator"></div>';
				}
				$html_menu .= '<a href="?page=my-statistics&stat_menu='.$item['name'].'" class="statistics-menu-item '.my_statistics_menu_item($item['name'], $current).'">'.$item['value'].'</a>';
			}
			$html_menu .= '<div class="item_separator_end"></div>';
		$html_menu .= '</ul>';
	$html_menu .= '</div>';
	
	$return_data['html'] = $html_menu;
	$return_data['current'] = $current;
	
	return $return_data;
}


function my_statistics_menu_item( $name = null, $current = null ){
	if ( ! isset( $name, $current ) ) {
		return null;
	}
	
	if ( strtolower($name) == strtolower($current) ) {
		return 'selected';
	} else {
		return null;
	}

	return null;
}





/* ----- EOF ----- */