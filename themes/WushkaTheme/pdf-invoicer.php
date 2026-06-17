<?php

/* Template Name: PDF Invoicer Page */
if( ! defined('ABSPATH') ) {
    exit;
} // Exit if accessed directly
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
// check for plugin using plugin name
if( ! is_plugin_active('pdf-stamp-invoicer/pdf-stamp-invoicer.php') || $_SERVER['REQUEST_METHOD'] !== 'POST' ||
    ! isset($_POST['form_submit']) || $_POST['form_submit'] !== 'pdf_stamp_invoice'
) {
    //Redirect to Login Page
    wp_redirect(home_url());
    exit;
}

$c_invoice = new Stamp_Invoice_Plugin();
$c_invoice->ajax_invoice();

/* ----- EOF ------ */