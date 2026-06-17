<?php
include $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
// Exit if accessed directly
if (
	! defined( 'ABSPATH' ) 					||
	$_SERVER[ 'REQUEST_METHOD' ] !== 'POST' ||
	! isset( $_POST['ajax_function'] ) 		||
	! is_user_logged_in()
) {
	echo json_encode(0);
	exit();
}
//Get Current User ID
//$i_teacher_id = get_current_user_id();

//Verify Current User is Teacher
/* if ( ! user_can( $i_teacher_id, 'teacher') ) {
	echo json_encode(0);
	exit();
} */

require_once( 'class_school-events.php');

$c_events = new School_Events();

if ( $_POST['ajax_function'] == 'save_event' ) {
	if ( isset($_POST['event_data']) ) {
		$x_saved = $c_events->save_event($_POST['event_data']);
		echo json_encode($x_saved);
		exit();
	}
}

echo json_encode(0);
exit();
/* ----------[ END AJAX FILE - School Events ]---------- */