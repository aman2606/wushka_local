<?php

/*
 * Try to login as a student from either teacher or parent dashboard
 */
include $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
global $current_user;
if (isset($_POST['id'])) {
    $user_id = $_POST['id'];
//    $user = get_user_by('id', $user_id);
    $user = get_user_by_hash($user_id);
    // check to see if this is a parent
    if (user_can($current_user, 'parent')) {
        $_SESSION['parent_login'] = "true";
        $_SESSION['parent_hash'] = $current_user->id_hash;
    }
    if ($user) {
        // check to see if child returning back to parent
        error_log('switching login - current user: ' . $current_user->user_login . ', new user: ' . $user->user_login);
        if (user_can($current_user, 'student') && user_can($user, 'parent')) {
            error_Log('switching back to parent');
            $creds = array();
            $creds['user_login'] = $user->user_login;
            $creds['user_password'] = $_POST['pw'];
            $user = wp_signon($creds, false);
            if (is_wp_error($user)) {
                error_log('failed to login as parent');
                error_log('credentials: ' . print_r($creds, true));
            } else {
                error_log('parent credentials correct');
            }
        } else {
            error_log('switching to child');
            wushka_user_login_events($user, 'logged in');
            wp_set_current_user($user->ID, $user->user_login);
            wp_set_auth_cookie($user->ID, true);
            do_action('wp_login', $user->user_login, $user);
        }
    }
}