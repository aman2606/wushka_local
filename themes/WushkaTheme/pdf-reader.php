<?php

/* Template Name: PDF Reader Page */

if( ! defined('ABSPATH') ) {
    exit;
} // Exit if accessed directly

include_once(ABSPATH . 'wp-admin/includes/plugin.php');
// check for plugin using plugin name
if( ! is_plugin_active('pdf-stamp/pdf-stamp.php') || $_SERVER['REQUEST_METHOD'] !== 'POST' ||
    ! isset($_POST['form_submit']) || $_POST['form_submit'] !== 'pdf-stamp' ||
    (! current_user_can("teacher") && ! current_user_can("school"))
) {

    //plugin is not active, abort redirect to front page
    //Redirect to Login Page
    wp_redirect(home_url('/login/'));
    exit;
}
if( current_user_can("teacher") && ! has_valid_subscription() ) {
    //User does not have permissions, or is not logged in
    //Redirect to Login Page
    wp_redirect(home_url('/login/'));
    exit;
}

$c_stamp = new Create_Stamp();

//Validate Parameters and Run Stamper
//IF An Error Occurs, Display Error Page
if( ! $c_stamp->validate() || $c_stamp->create_stamp() === FALSE ) {
    error_log('----- PDF Failed to Download -----');
    get_header();
?>
    <div class="container-fluid">
        <div class="row mt30">
            <div class="col-xs-12">
                <h1 class="glyphicon-heading">
                    <span class="x2 glyphicon glyphicon-pie-chart hidden-xs"></span>
                    <span class="glyphicon-heading-text">PDF Reader</span>
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-offset-3 col-sm-6">
            <h2>Sorry!</h2>
            <p>An error appears to have occurred while creating your pdf file.</p>
            <p class="center">
                <button class="btn btn-primary" onClick="window.location.reload()">Try Again</button>
            </p>
            </div>
        </div>
    </div>


    <?php get_footer();
}
/* ----- END OF FILE -----*/