<?php
/* ==========================================================
 * ------------------ PDF STAMP EDITOR PLUGIN ------------------
 *
 * Plugin Name: PDF Stamp
 * Description: This plugin will give you the option to stamp all pdf files accessed on this site.
 * Author: ESISS Pty Ltd.
 * Version: 1.5
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$stamp_plugin = new Stamp_Plugin();
/* ----------------------------------------------------------------------
 *
 *							PDF STAMP PLUGIN CLASS
 *
 * ---------------------------------------------------------------------- */

class Stamp_Plugin {
    // --- Private Constants --- \\
    private $o_user;
    private $s_dir;
    private $s_url;
    public  $s_autoload;

    // --- Constructor Method --- \\
    public function __construct() {
        $this->initialise_variables();
        $this->initialise_hooks();
    }

    // --- Declare Constants / Variables --- \\
    private function initialise_variables() {
        global $current_user;
        $this->s_dir  = plugin_dir_path(__FILE__);
        $this->s_url  = plugin_dir_url(__FILE__) . '/';
        $this->s_autoload = $this->s_dir . '/stamp/library/SetaPDF/Autoload.php';
        $this->o_user = $current_user;
    }

    // --- Declare Plugin Hooks --- \\
    private function initialise_hooks() {
        register_activation_hook(__FILE__, array( $this, 'install' ));
        register_deactivation_hook(__FILE__, array( $this, 'uninstall' ));
        add_action('wp_enqueue_scripts', array( $this, 'enqueue' ));
        $this->load_stamp();
    }

    // --- Activation Method --- \\
    public function install() {
        //Create new options and the default option variables
        $options = $this->get_default_options();
        if( get_option('pdf_stamp') === FALSE ) {
            add_option('pdf_stamp', $options);
        } else {
            update_option('pdf_stamp', $options);
        }
    }

    // --- DeActivation Method --- \\
    public function uninstall() {
        //Remove Options from DB on uninstall
        if( get_option('pdf_stamp') !== FALSE ) {
            delete_option('pdf_stamp');
        }
    }

    // --- Enqueue: Load Styles and Scripts --- \\
    public function enqueue() {
        $s_path = $this->s_url;
        //Post Attachment CSS enqueue
        wp_register_style('css_pdf-stamp', $s_path . 'css/css_pdf-stamp.css', __FILE__);
        wp_enqueue_style('css_pdf-stamp');
        wp_enqueue_script('js_pdf-stamp', $s_path . 'js/js_pdf-stamp.js', array('jquery'), FALSE);
        wp_localize_script('js_pdf-stamp', 'a_pdf_stamp_script',
            array(
                'page_url'   => home_url('/pdf-reader/'),
                'ajax_stamp' => $s_path . '/assets/ajax_pdf-stamp.php',
                'ajax_url'   => esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")),
            )
        );

    }

    private function load_stamp() {
        require_once('classes/class_create-stamp.php');
    }

    // --- Return current options --- \\
    private function get_options() {
        return get_option('pdf_stamp');
    }

    // --- Initialise Admin --- \\
    public function admin_init() {
        wp_enqueue_style('farbtastic');
        wp_enqueue_script('farbtastic');
    }

    // --- Admin Menu --- \\
    public function admin_menu() {
        $a_options = $this->save_new_options();

        require_once $this->s_dir . '/assets/page_stamp-admin.php';
    }

    // --- Create Default Options --- \\
    private function get_default_options() {
        //Page Form (default||portrait||landscape)
        $a_options ['page_layout'] = 'default';
        //Location of Stamper on Page (header||footer)
        $a_options ['page_placement'] = 'footer';
        //URL to Image
        $a_options ['logo_image'] = 'http://cdn5.lessonzone.com.au/Resources/lessonzone-logo.png';
        //How is Logo Size measured (auto||manual) NOTE: auto will use dimensions of logo image
        $a_options ['logo_size_type'] = 'auto';
        //Height and Width (px) of Logo Image
        $a_options ['logo_size_res'] = array(
            'width'  => 24,
            'height' => 24
        );
        //Type of Background (None||Colour||Image)
        $a_options ['background_type'] = 'noBackground';
        //Image (if any) of Background
        $a_options ['background_image'] = 'none';
        //Colour of Background
        $a_options ['background_colour'] = '#95f62d';
        //Colour of Stamp Text
        $a_options ['text_colour'] = '#000000';
        //Physical Rotation of Stamper Area (degrees)
        $a_options ['stamp_rotation'] = 0;
        //Offset (px) from page edge
        $a_options ['stamp_offset'] = 0;
        //Height (px) of Stamp Area
        $a_options ['stamp_height'] = 40;
        //Contents of copyright field
        $a_options ['field_copyright'] = 'Copyright \xC2\xA9 Lesson Zone All Rights Reserved';
        //Contents of Terms and Conditions field
        $a_options ['field_terms'] = 'http://lessonzone.com.au/terms-conditions/';
        //Contents of Custom Meta Field
        $a_options ['field_custom'] = 'none';

        return $a_options;
    }

    private function save_new_options() {
        $a_options = $this->get_options();
        if( $_SERVER['REQUEST_METHOD'] !== 'POST' || $_POST['option_page'] !== 'pdf_stamp' ||
            ! wp_verify_nonce($_POST['pdfstmp_nce'], 'pdf_stamp_nonce-' . $this->o_user->ID)
        ) {
            return $a_options;
        }

        foreach( $a_options as $s_key => $x_option ) {
            if( isset($_POST[ $s_key ]) ) {
                if( is_array($x_option) ) {
                    foreach( $x_option as $s_sub_key => $x_sub_option ) {
                        if( isset($POST[ $s_key ][ $s_sub_key ]) ) {
                            $a_options[ $s_key ][ $s_sub_key ] = $POST[ $s_key ][ $s_sub_key ];
                        }
                    }
                } else {
                    $a_options[ $s_key ] = $_POST[ $s_key ];
                }
            }
        }
        //Save Options to DB
        update_option('pdf_stamp', $a_options);

        return $a_options;
    }


}

/* ------------------------------------------------------------------------------- */