<?php
if( ! defined('ABSPATH') ) {
    exit;
} // Exit if accessed directly
//exits when file is load directly
if( ! function_exists('add_action') ) {
    echo "This page cannot be called directly.";
    exit;
}
require_once '../stamp/library/SetaPDF/Autoload.php';
#TODO: THIS IS A BLANK PDF STAMP CLASS TEMPLATE

class Stamp_ {
    private $o_user;
    private $a_options;
    private $s_type;
    private $b_display;
    private $s_file_name;
    private $s_file_url;
    private $b_test;

    //Stamper Object Components
    private $o_reader;
    private $o_write;
    private $o_document;
    private $o_stamper;
    private $o_pages;
    private $i_page_count;
    private $i_per_page;

    public function __construct() {
        $this->log('----- PDF Stamp -----');
        $this->a_options    = $this->load_options();

        $this->s_type       = NULL;
        $this->b_display    = TRUE;
        $this->s_file_name  = 'example.pdf';
        $this->s_file_url   = NULL;
        $this->i_page_count = 0;
        //Number of user stamps per page (student_coupons)
        $this->i_per_page = 3;

        #TODO: Remove After Testing is Completed
        $this->b_test = FALSE;
    }

    /* ----- Store Current Stamp Options ----- */
    private function load_options() {
        $x_options = get_option('pdf_stamp');

        $a_options = NULL;
        if( $x_options !== FALSE ) {
            $a_options = $x_options;
        }

        return $a_options;
    }


    public function validate() {
        if( ! is_user_logged_in() || (! current_user_can('teacher') && ! current_user_can('school')) ) {
            $this->log('Error: Current User is Invalid');

            return FALSE;
        }

        //Store Type
        $s_type    = stripcslashes(filter_input(INPUT_POST, 'type'));
        $s_display = stripcslashes(filter_input(INPUT_POST, 'display'));

        //Store Stamp Type
        if( ! isset($s_type) || empty($s_type) ) {
            $this->log('Error: stamp type failed validation');

            return FALSE;
        }

        //Store Display Setting
        if( ! isset($s_display) || empty($s_display) ) {
            $this->log('Error: display setting failed validation');

            return FALSE;
        }

        //By Default, New PDFs will be diplayed in browser.
        //Otherwise, download pdf file.
        $b_display = TRUE;
        if( $s_display == 'download' ) {
            $b_display = FALSE;
        }

        //Validation Past, Store Parameters
        global $current_user;
        $this->o_user    = $current_user;
        $this->s_type    = $s_type;
        $this->b_display = $b_display;

        return TRUE;
    }

    public function get_data() {
        #TODO: What Other Data do i need for Support Materials

        return TRUE;
    }


    public function stamp() {

    }

    private function parse_url( $s_url = NULL ) {
        if( ! isset($s_url) ) {
            return FALSE;
        }
        $a_new_url   = [];
        $s_parse_url = parse_url($s_url);
        foreach( $s_parse_url as $key => $value ) {
            if( $key == 'scheme' ) {
                $value = $value . '://';
            } else if( $key == 'port' ) {
                $value = ':' . $value;
            } else if( $key == 'query' ) {
                $value = '?' . $value;
            } else if( $key == 'path' ) {
                $explode_path = explode("/", $value);
                foreach( $explode_path as $part => $val ) {
                    $explode_path[ $part ] = urlencode($val);
                }
                $value = implode('/', $explode_path);
            }
            $a_new_url[] = $value;
        }

        $x_file_url = implode('', $a_new_url);
        if( isset($x_file_url) ) {
            $this->s_file_url = file_get_contents($x_file_url);

            return TRUE;
        }

        return FALSE;
    }

    private function convert_hex_to_rgb( $s_hex = NULL ) {
        if( ! isset($s_hex) ) {
            return NULL;
        }
        $i_raw = str_replace('#', '', $s_hex);
        if( strlen($i_raw) == 3 ) {
            $i_R = hexdec(substr($i_raw, 0, 1) . substr($i_raw, 0, 1));
            $i_G = hexdec(substr($i_raw, 1, 1) . substr($i_raw, 1, 1));
            $i_B = hexdec(substr($i_raw, 2, 1) . substr($i_raw, 2, 1));
        } else {
            $i_R = hexdec(substr($i_raw, 0, 2));
            $i_G = hexdec(substr($i_raw, 2, 2));
            $i_B = hexdec(substr($i_raw, 4, 2));
        }

        return array(
            'R' => ($i_R / 255),
            'G' => ($i_G / 255),
            'B' => ($i_B / 255)
        );
    }

    private function store_filename( $s_filename = NULL ) {
        $this->s_filename = trim($s_filename);
    }

    private function initiate_stamp_document() {
        //---------- Load Original PDF Document----------\\
        $this->o_reader = new SetaPDF_Core_Reader_String($this->s_file_url);
        $this->o_writer = new SetaPDF_Core_Writer_Http($this->s_file_name, $this->b_display);
        $this->log('Reader Writer Loaded');
        //Create Document Object
        $this->o_document = SetaPDF_Core_Document::load($this->o_reader, $this->o_writer);
        $this->log('Document Loaded');

        //Get Page Info
        $this->o_pages      = $this->o_document->getCatalog()->getPages();
        $this->i_page_count = $this->o_pages->count();
    }

    private function log( $s_text = NULL ) {
        if( $this->b_test && isset($s_text) ) {
            error_log($s_text);
        }

        return TRUE;
    }

}

/* ----- EOF ------ */