<?php
include $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
// Exit if accessed directly
if(
    ! defined('ABSPATH') ||
    $_SERVER['REQUEST_METHOD'] !== 'POST' ||
    ! isset($_POST['ajax_function']) ||
    ! is_user_logged_in()
) {
    echo json_encode(0);
    exit();
}
//Get Current User ID
$i_user_id = get_current_user_id();

//Verify Current User is Teacher
if( ! user_can($i_user_id, 'teacher') && ! user_can($i_user_id, 'student') ) {
    echo json_encode(0);
    exit();
}

require_once('class_my-bookmarks.php');

$c_bookmarks = new Wushka_Bookmarks($i_user_id);
if( $c_bookmarks->_b_status === FALSE ) {
    echo json_encode(0);
    exit();
}

if( $_POST['ajax_function'] == 'toggle_book' ) {
    //Check Teacher ID was passed
    if( isset($_POST['book_id']) ) {
        $b_result = $c_bookmarks->toggle_book_to_bookmarks($_POST['book_id']);
        if( $b_result === TRUE ) {
            echo json_encode(1);
            exit();
        }
    }

    echo json_encode(0);
    exit();
}
/* ----------[ END AJAX FILE - My Bookmarks ]---------- */