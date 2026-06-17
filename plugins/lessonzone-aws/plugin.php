<?php
/*
Plugin Name: Site AWS link for lessonzone.com
Description: Site specific AWs functionality for lessonzone.com
Author: ESISS Pty Ltd
Version: 1.0
*/


/*function lz_aws_check_required_plugin() {
    if (class_exists('Amazon_Web_Services') || !is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
        return;
    }

    error_log('************* WUSHKA AWS ATTACHMENT PLUGIN *************');
    error_log('*************** CHECKING REQUIRED PLUGINS **************');

    require_once ABSPATH . '/wp-admin/includes/plugin.php';
    deactivate_plugins(__FILE__);

    $msg = sprintf(__('Lesson Zone AWS has been deactivated as it requires the <a href="%s">Amazon&nbsp;Web&nbsp;Services</a> plugin.', 'lzaws'), 'https://github.com/deliciousbrains/wp-amazon-web-services' ) . '<br /><br />';
    error_log($msg);
    
    ///amazon-web-services/amazon-web-services.php

    if (file_exists(WP_PLUGIN_DIR . '/lessonzone-aws/amazon-web-services.php' ) ) {
        $activate_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=lessonzone-aws/amazon-web-services.php', 'activate-plugin_amazon-web-services/amazon-web-services.php');
        $msg .= sprintf(__('It appears to already be installed. <a href="%s">Click here to activate it.</a>', 'lzaws'), $activate_url);
        error_log($msg);
        error_log('********************************************************');
    } else {
        $download_url = 'https://github.com/deliciousbrains/wp-amazon-web-services/releases/download/v0.1/amazon-web-services-0.1.zip';
        $msg .= sprintf(__('<a href="%s">Click here to download a zip of the latest version.</a> Then install and activate it. ', 'lzaws'), $download_url);
        error_log($msg);
        error_log('********************************************************');
    }

    $msg .= '<br /><br />' . __('Once it has been activated, you can activate Amazon&nbsp;S3&nbsp;and&nbsp;CloudFront.', 'lzaws');
    error_log($msg);

    wp_die( $msg );
    error_log('********************************************************');
}
add_action('plugins_loaded', 'lz_aws_check_required_plugin');*/
/** amazon webservice file starts */

function lz_aws_check_required_plugin() {
    
    if (class_exists('Amazon_Web_Services') || !is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
        return;
    }
    error_log('************* WUSHKA AWS ATTACHMENT PLUGIN *************');
    error_log('*************** CHECKING REQUIRED PLUGINS **************');

    require_once ABSPATH . '/wp-admin/includes/plugin.php';
    deactivate_plugins(__FILE__);

    $msg = sprintf(__('Lesson Zone AWS has been deactivated as it requires the <a href="%s">Amazon&nbsp;Web&nbsp;Services</a> plugin.', 'lzaws'), 'https://github.com/deliciousbrains/wp-amazon-web-services' ) . '<br /><br />';
    error_log($msg);
    
    ///amazon-web-services/amazon-web-services.php

    if (file_exists(WP_PLUGIN_DIR . '/lessonzone-aws.php/plugin.php' ) ) {
        $activate_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=lessonzone-aws/plugin.php', 'activate-plugin_amazon-web-services/amazon-web-services.php');
        $msg .= sprintf(__('It appears to already be installed. <a href="%s">Click here to activate it.</a>', 'lzaws'), $activate_url);
        error_log($msg);
        error_log('********************************************************');
    } else {
        $download_url = 'https://github.com/deliciousbrains/wp-amazon-web-services/releases/download/v0.1/amazon-web-services-0.1.zip';
        $msg .= sprintf(__('<a href="%s">Click here to download a zip of the latest version.</a> Then install and activate it. ', 'lzaws'), $download_url);
        error_log($msg);
        error_log('********************************************************');
    }

    $msg .= '<br /><br />' . __('Once it has been activated, you can activate Amazon&nbsp;S3&nbsp;and&nbsp;CloudFront.', 'lzaws');
    error_log($msg);

    wp_die( $msg );
    error_log('********************************************************');
}
add_action('plugins_loaded', 'lz_aws_check_required_plugin');

if ( is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) ) {
        if ( version_compare( PHP_VERSION, '5.3.3', '<' ) ) {
                amazon_web_services_incompatibile( __( 'The official Amazon Web Services SDK requires PHP 5.3.3 or higher. The plugin has now disabled itself.', 'amazon-web-services' ) );
        }
        elseif ( !function_exists( 'curl_version' ) 
                || !( $curl = curl_version() ) || empty( $curl['version'] ) || empty( $curl['features'] )
                || version_compare( $curl['version'], '7.16.2', '<' ) )
        {
                amazon_web_services_incompatibile( __( 'The official Amazon Web Services SDK requires cURL 7.16.2+. The plugin has now disabled itself.', 'amazon-web-services' ) );
        }
        elseif ( !( $curl['features'] & CURL_VERSION_SSL ) ) {
                amazon_web_services_incompatibile( __( 'The official Amazon Web Services SDK requires that cURL is compiled with OpenSSL. The plugin has now disabled itself.', 'amazon-web-services' ) );
        }
        elseif ( !( $curl['features'] & CURL_VERSION_LIBZ ) ) {
                amazon_web_services_incompatibile( __( 'The official Amazon Web Services SDK requires that cURL is compiled with zlib. The plugin has now disabled itself.', 'amazon-web-services' ) );
        }
}


require_once 'classes/aws-plugin-base.php';
require_once 'classes/amazon-web-services.php';

require_once 'vendor/autoload.php';
//require_once 'vendor/autoload.php';

function amazon_web_services_init() {
   
    global $amazon_web_services;
    $amazon_web_services = new Amazon_Web_Services( __FILE__ );
    
}

add_action( 'init', 'amazon_web_services_init' );

/*function amazon_web_services_activation() {
        // Migrate keys over from old Amazon S3 and CloudFront plugin settings
        if ( !( $as3cf = get_option( 'tantan_wordpress_s3' ) ) ) {
                return;
        }

        if ( !isset( $as3cf['key'] ) || !isset( $as3cf['secret'] ) ) {
                return;
        }

        if ( !get_site_option( Amazon_Web_Services::SETTINGS_KEY ) ) {
                add_site_option( Amazon_Web_Services::SETTINGS_KEY, array(
                        'access_key_id' => $as3cf['key'],
                        'secret_access_key' => $as3cf['secret']
                ) );
        }

        unset( $as3cf['key'] );
        unset( $as3cf['secret'] );

        update_option( 'tantan_wordpress_s3', $as3cf );
}

register_activation_hook( __FILE__, 'amazon_web_services_activation' );*/

function amazon_web_services_deactivation() {
    // Migrate keys over from old Amazon S3 and CloudFront plugin settings
    error_log('************* AWS ATTACHMENT PLUGIN *************');
    error_log('*************** DEACTIVATION HOOK ***************');
}

register_deactivation_hook( __FILE__, 'amazon_web_services_deactivation' );

function uninstall_aws() {
    error_log('************* AWS ATTACHMENT PLUGIN *************');
    error_log('**************** UNINSTALL NOTICE ***************');
}
register_uninstall_hook( __FILE__, 'uninstall_aws' );


/** lessson zone plugin starts */


function lz_aws_init($aws) {
    
    global $lzaws;
    require_once 'classes/lessonzone-aws.php';
    $lzaws = new LessonZone_AWS(__FILE__, $aws);
   
}
add_action('aws_init', 'lz_aws_init');

function deactivate_laws() {
    error_log('************* WUSHKA AWS ATTACHMENT PLUGIN *************');
    error_log('***************** DEACTIVATION NOTICE ******************');
}
register_deactivation_hook( __FILE__, 'deactivate_laws' );

function uninstall_laws() {
    error_log('************* WUSHKA AWS ATTACHMENT PLUGIN *************');
    error_log('******************* UNINSTALL NOTICE *******************');
}
register_uninstall_hook( __FILE__, 'uninstall_laws' );