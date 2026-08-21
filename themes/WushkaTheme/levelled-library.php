<?php
/*
  Template Name: ebook levelled library
 */
if (!isset($_SESSION)) {
  session_start();
}
global $current_user;



get_header();

if (!hasLevelledAccess()) {
    include 'levelled-excerpt.php';
    include 'dashboard_options.php';
} else{
    include 'bookshelves.php';
}

get_footer();
?>