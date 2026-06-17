<?php 
include "../../../../wp-config.php";

//Check against invalid access
if ( ! defined( 'ABSPATH' ) ) {
	//error_log('ABORT - abspath');
	die("You are the weakest link. Goodbye.");
} else if ( !function_exists( 'add_action' ) ) {
	//error_log('ABORT - action');
	die("You are the weakest link. Goodbye.");
} else if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
	//error_log('ABORT - no post');
	die("You are the weakest link. Goodbye.");
} else if( ! isset( $_POST['my_stats_event'] ) || $_POST['my_stats_event'] !== 'click_event' ) {
	//error_log('ABORT - stat event');
	die("You are the weakest link. Goodbye.");
} else if( ! isset( $_POST['res_type'], $_POST['btn_type'], $_POST['data_check'], $_POST['event_location'] ) ||  $_POST['post_id'] == 0 ) {
	//error_log('ABORT - noset');
	die("You are the weakest link. Goodbye.");
} else if( ! wp_verify_nonce($_POST['data_check'], 'my_statistics_data_check') ) {
	//error_log('ABORT - data check');
	die("You are the weakest link. Goodbye.");
}

/* ----- RUN CLICK EVENT FUNCTION ----- */
//Declare Variables
$post_id 	   	= $_POST['post_id'];
$attachment_id 	= $_POST['attachment_id'];
$res_type 		= $_POST['res_type'];
$btn_type		= $_POST['btn_type'];
$event_location	= $_POST['event_location'];
$user_id 	   	= get_current_user_id(); 

/* 
	error_log('Click Event Results are: ');
	error_log("POST: ".$_POST['post_id']);
	error_log("ATTACHMENT: ".$_POST['attachment_id']);
	error_log("RES TYPE: ".$_POST['res_type']);
	error_log("BTN TYPE: ".$_POST['btn_type']);
	error_log("USER: ".$user_id);
	error_log("IS SAMPLE?: ".$is_sample); */

$return_data = my_statistics_create_event($user_id, $post_id, $attachment_id, $res_type, $btn_type, $event_location);
error_log('Statistics Insert Event result = '.$return_data);
echo json_encode($return_data);

die();

/* ----- EOF ----- */
?>