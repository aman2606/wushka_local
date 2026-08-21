<?php

/* 
 * Adding, Editing and Deleting children related information
 */
include $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
  // error_log('** Children ** ' . print_r($_POST,true));
if ( $_SERVER['REQUEST_METHOD'] === 'POST' 
        && isset($_POST['action']) && ($_POST['action'] != 'undefined') 
        && isset($_POST['value'])  && ($_POST['value'] != 'undefined') 
){
    
    $parent_id = $current_user->ID;
    $action    = $_POST['action'];
    // updated Feb 2019 to prevent count_total performing slow query
    $args = array(
        'role' => 'student',
        'count_total'   => false,
        'meta_query' => array(
            'relation' => 'AND',
            0 => array(
                'key' => 'parent_id',
                'value' => $parent_id
            ),
            1 => array(
                'key' => 'active',
                'value' => 1
            )
        )
    );        
   $total = 0;
   $user_query = new WP_User_Query($args);  // args updated for slow query
  
   if( $action == 'children_list') {
        if ( ! empty( $user_query->results ) ) {
            foreach ( $user_query->results as $idx => $user ) { 
                $userMeta = get_user_meta($user->id);
                $list.= '<div id="child-' . $user->id .'"><span>'. $user->first_name . $user->last_name .' (' .$user->user_login .')</span>';
                $list.= '<form method="POST" action="/child"><input type="hidden" name="childID" id="' . $user->id . '">';
                $list.= '<input class="to-student-view btn btn-default btn-sm" type="submit" value="Profile" /></form></div>';
            }
        }
        echo $list;
    } 
} else {
    echo $list .= '<div> No users found.</div>';
}
?>