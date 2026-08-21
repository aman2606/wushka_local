<?php

//Sub admin configurations
define('SUB_ADMIN', 'subadmin'); 
define('MODERNSTAR_EMPLOYEE','modernstar_employee');
 
function wushka_check_user_role($user = null){
    if(!$user){
        $user = wp_get_current_user();  
    } 
    if(!empty($user->roles)){
        if(!in_array(SUB_ADMIN, $user->roles) && !in_array(MODERNSTAR_EMPLOYEE, $user->roles)){
            return false;
        } 
    }
    return true; 
}
 
//Hide administrator tab from user list
add_filter("views_users", "wushka_admin_list_user_views");
function wushka_admin_list_user_views($views){ 
    if(wushka_check_user_role()){
        unset($views['administrator']);        
    }
   return $views;
}

//Block if user try to access from restricted page from url
add_action('admin_init', 'wushka_no_page_access', 100);
function wushka_no_page_access(){ 
    if(wushka_check_user_role()){ 
        //restrict user to edit admin
        (isset($_GET['user_id'])) ? $query_var = sanitize_text_field($_GET['user_id']) : $query_var = '' ; 

        if(isset($_GET['action'])){
            //restrict user to delete admin
            if($_GET['action'] == 'delete'){
                (isset($_GET['user'])) ? $query_var = sanitize_text_field($_GET['user']) : $query_var = '' ; 
            } 
            //restrict user to switch admin
            if($_GET['action'] == 'switch_to_user'){
                (isset($_GET['user_id'])) ? $query_var = sanitize_text_field($_GET['user_id']) : $query_var = '' ; 
            } 
        } 

           

        if( isset($_SERVER['REQUEST_URI']) ){ 
            
            /* $args = array(
                'role'    => 'administrator' 
            );
            $users = get_users( $args );
            $users = json_decode(json_encode($users), true); 
            $users_id = array_column($users, 'ID');  */
            
            if( $_SERVER['REQUEST_URI'] == '/wp-admin/users.php?role=administrator' 
                || $_SERVER['REQUEST_URI'] == '/wp-admin/update-core.php' 
            //    || in_array($query_var, $users_id)
            ){
                wp_die('Access Denied');
            } 
        } 
    }
    return true;
}

//Remove switch to button
add_filter( 'user_has_cap', function( $allcaps, $caps, $args, $user ) {
    if ( 'switch_to_user' === $args[0] &&  wushka_check_user_role()) { 
            $allcaps['switch_users'] = true; 
    }
    return $allcaps;
}, 9, 4 );


// Remove Administrator role from roles list
add_action( 'editable_roles' , 'hide_adminstrator_editable_roles' );
function hide_adminstrator_editable_roles( $roles ){
    if(wushka_check_user_role() && isset( $roles['administrator'] ) ){ 
            unset( $roles['administrator'] ); 
    } 
    return $roles;
}

//Hides update notiftication
add_action('admin_menu','wushka_hide_wp_update_nag');
function wushka_hide_wp_update_nag() {
    if(wushka_check_user_role()){
        remove_action( 'admin_notices', 'update_nag', 3 );
    } 
}
    


//Pre query for wp user
/* add_action('pre_user_query', 'wushka_pre_user_query');
function wushka_pre_user_query( $user_search){  
    if(wushka_check_user_role()){
        global $wpdb;  
        $user_search->query_where = 
        str_replace('WHERE 1=1', 
            "WHERE 1=1 AND {$wpdb->users}.ID IN (
                 SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta 
                    WHERE {$wpdb->usermeta}.meta_key = '{$wpdb->prefix}user_level' 
                    AND {$wpdb->usermeta}.meta_value < 10)", 
            $user_search->query_where
        ); 
    }
     
} */

add_action('wp_dashboard_setup', 'wushka_remove_all_dashboard_meta_boxes', 9999 ); 
function wushka_remove_all_dashboard_meta_boxes()
{
    if(wushka_check_user_role()){
        global $wp_meta_boxes;
        $wp_meta_boxes['dashboard']['normal']['core'] = array();
        $wp_meta_boxes['dashboard']['side']['core'] = array();
    }
}

/* Subadmin configuration ends here */