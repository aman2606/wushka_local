<?php 

//menu and sub menu function
function wushka_admin_menu() {
	add_menu_page( 'Export PC/Teacher Data', 'Export Data', 'manage_options', 'export_pc_teacher', 'wushka_export_teachers_pc', 'dashicons-media-spreadsheet', 6  );
    add_submenu_page( 'export_pc_teacher', 'Export PC/Teacher Data', 'PC/Teacher Data', 'manage_options', 'export_pc_teacher', 'wushka_export_teachers_pc' );
    add_submenu_page( 'export_pc_teacher', 'Export Rollover Data', 'Rollover Data', 'manage_options', 'export_rollover', 'wushka_export_rollover' );
    add_submenu_page( 'export_pc_teacher', 'Export SSO Activity', 'SSO Activity Data', 'manage_options', 'export_sso_activity', 'wushka_export_sso_activity' );
    add_submenu_page( 'export_pc_teacher', 'Export qrlogin Activity', 'QR Login Data', 'manage_options', 'export_qrlogin_activity', 'wushka_export_qrlogin_data' );
}
add_action( 'admin_menu', 'wushka_admin_menu' ); 



function wushka_admin_custom_css_js($hook_suffix){
    global $pagenow;
    if($pagenow != 'admin.php'){
        return;
    }

    if(($hook_suffix != 'toplevel_page_export_pc_teacher') && ($hook_suffix != 'export-data_page_export_rollover') && ($hook_suffix != 'export-data_page_export_sso_activity') && ($hook_suffix != 'export-data_page_export_qrlogin_activity')){
        return;
    }

    wp_enqueue_style('datatables', '//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css');
    wp_enqueue_style('datatables-custom', get_template_directory_uri(). '/css/admin/export-data.css');

    wp_enqueue_script('datatables', '//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js');

    wp_enqueue_script('datatables-button', 'https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js');
    wp_enqueue_script('datatables-jszip', 'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js');
    wp_enqueue_script('datatables-pdfmake', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js');
    wp_enqueue_script('datatables-fonts', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js');
    wp_enqueue_script('datatables-html5', 'https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js');

    wp_enqueue_script('datatables-init', get_template_directory_uri(). '/js/admin/export-data.js');
}
add_action( 'admin_enqueue_scripts', 'wushka_admin_custom_css_js' );



function wushka_sql_esc_like($string){
    global $wpdb;
    $wild = '%';
    return $wild . $wpdb->esc_like( $string ) . $wild;
}


require_once('export/teachers-pc-data.php');
require_once('export/rollover-data.php');
require_once('export/sso-activity-data.php');
require_once('export/qr-logged-data.php');

