<?php


/*
 * switch between school and teacher dashboards
 */
//include $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';

// if (!current_user_can('school') && !current_user_can('teacher')) {
   
//     exit;
// }

global $current_user;


if ( ! isset($_SESSION) ) {
    session_start();
}

if (isset($_POST['type'])) {
   
    $type = $_POST['type'];
    switch ($type) {
        case 'school':
            $_SESSION['dashboard_selection'] = $type;
            break;
        case 'teacher':
            $_SESSION['dashboard_selection'] = $type;
            break;
        default:
            break;
    }
}