<?php

/*
Template Name: Teacher Dashboard
*/

//Is User Logged In AND is user a teacher?
if ( ! is_user_logged_in() || ! user_can( $current_user, "teacher") ) {
	//Redirect to Login Page
	wp_redirect( home_url()."/wp-login.php" );
	exit;
}
//Check for Teacher Role

//load Teacher Dashboard Functions
require_once( 'functions/teacher-dashboard-functions.php');

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
    var teacherFunctionBoxes = $('.teacher-functions-box');
    var teacherFunctionContent = $('.teacher-function.content');
    var teacherFunctionHeading = $('.teacher-function.heading-wrapper');
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
    $('#class-stories .teacher-function.wrapper').grayed();
    $('#badges .teacher-function.wrapper').grayed();
});
</script>
<?php
//Add Footer
get_footer();

?>