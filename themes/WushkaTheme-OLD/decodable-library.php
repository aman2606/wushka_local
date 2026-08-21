<?php
/*
  Template Name: ebook decodable library
 */
if (!isset($_SESSION)) {
  session_start();
}
global $current_user;



get_header();

if (!hasDecodableAccess()) {
    if($_SESSION['wushka_decodable_teacher']){
        include 'bookshelves.php';
    }else{
        include 'decodable-excerpt.php';
        include 'dashboard_options.php';
    }    
} else{
    include 'bookshelves.php';
}

get_footer();
?>