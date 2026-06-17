<?php

/*
Template Name: School Dashboard Tiles
*/

//Is User Logged In AND is user a school?
if ( ! is_user_logged_in() || ! user_can( $current_user, "school") ) {
    //Redirect to Login Page
    wp_redirect( home_url()."/wp-login.php" );
    exit;
}
// Check that school has accepted T&C's
if ($current_user->user_terms != 'Yes') {
    wp_redirect( home_url()."/school-settings" );
}
//load Teacher Dashboard Functions
require_once( 'functions/school_dashboard_functions.php');

/* --- Deploy Page --- */
//Add Header
get_header();

//Add Body
get_teacher_dashboard();

// Add Teacher/Student Dashboard
include 'dashboard_options.php';
?>
<script>
jQuery(document).ready(function($) {
    /* Teacher Dashboard */

    // Equal Teacher Dashboard's boxes height
    var teacherFunctionBoxes = $('.parent-functions-box');
    var teacherFunctionContent = $('.parent-function.content');
    var teacherFunctionHeading = $('.parent-function.heading-wrapper');
    teacherFunctionBoxes.equalHeights();
    teacherFunctionContent.equalHeights();
    teacherFunctionHeading.equalHeights();

    // Add disabled layer
    $.fn.grayed = function() {
        this.each(function() {
            $(this).prepend('<div class="grayed"></div>');
            $(this).css('opacity', '.7');
            $(this).removeClass('grow');
        });
    };
    $('#child-stories .parent-function.wrapper').grayed();
    $('#child-badges .parent-function.wrapper').grayed();
});
</script>

<?php
//Add Footer
get_footer();

?>