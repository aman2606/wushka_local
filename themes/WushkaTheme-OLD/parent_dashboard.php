<?php

/*
Template Name: Parent Dashboard
*/

//Is User Logged In AND is user a teacher?
if ( ! is_user_logged_in() || ! user_can( $current_user, "parent") ) {
    //Redirect to Login Page
    wp_redirect( home_url()."/wp-login.php" );
    exit;
}
//Check for Teacher Role

//load Teacher Dashboard Functions
require_once( 'functions/parent-dashboard-functions.php');

/* --- Deploy Page --- */
//Add Header
get_header();

//Add Body
get_teacher_dashboard();

// Add Teacher/Student Dashboard
include 'dashboard_options.php';

//Add Footer
get_footer();

?>
<script>
jQuery(document).ready(function ($) {
    /* Teacher Dashboard */
    
    // Equal Teacher Dashboard's boxes height
    var teacherFunctionBoxes = $('.parent-functions-box');
    var teacherFunctionContent = $('.parent-function.content');
    var teacherFunctionHeading = $('.parent-function.heading-wrapper');
        teacherFunctionBoxes.equalHeights();
        teacherFunctionContent.equalHeights();
        teacherFunctionHeading.equalHeights();
    
    // Add disabled layer
    $.fn.grayed = function(){
        this.each(function(){
            $(this).prepend('<div class="grayed"></div>');
            $(this).css('opacity','.7');
            $(this).removeClass('grow');
        });    
    };
    $('#child-stories .parent-function.wrapper').grayed();
    $('#child-badges .parent-function.wrapper').grayed();

});

</script>