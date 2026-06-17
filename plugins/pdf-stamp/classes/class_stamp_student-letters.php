<?php
if( ! defined('ABSPATH') ) {
    exit;
} // Exit if accessed directly
//exits when file is load directly
if( ! function_exists('add_action') ) {
    echo "This page cannot be called directly.";
    exit;
}
require_once plugin_dir_path(__FILE__) . '../stamp/library/SetaPDF/Autoload.php';

class Stamp_Student_Letters {
    //Variable Parameters
    private $o_user;
    private $a_options;
    private $a_students;
    private $i_class;

    //Stamp Parameters
    private $b_display;
    private $i_on_page;
    private $s_file_name;
    private $s_file_url;
    private $o_document;
    private $i_pages;
    private $o_pages;
    private $a_logs;

    //Turn Test State On/Off
    private $b_test;

    public function __construct() {
        $this->a_options = $this->load_options();

        //Parameters
        $this->b_display = TRUE;
        $this->i_class   = NULL;
        //Document Settings
        $this->s_file_name = 'student_letters.pdf';
        $this->s_file_url  = NULL;
        $this->o_document;
        $this->i_pages = 0;
        $this->o_pages = NULL;
        //Number of Student Letters Per Page;
        $this->i_on_page = 3;

        $this->a_students = [];
        $this->a_logs = array();

        /* ----- TEST ENVIRONMENT INDICATOR ----- */
        //Note: If TRUE, stamper will print log entries
        //Set to FALSE in Live Environments, to stop excessive error reporting
        #TODO: Remove After Testing is Completed
        $this->b_test = FALSE;
        $this->log('----- PDF Stamp -----');
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

        $s_display = stripcslashes(filter_input(INPUT_POST, 'display'));
        $i_class   = stripcslashes(filter_input(INPUT_POST, 'class_id'));

        //Store Display Setting
        if( ! isset($s_display) || empty($s_display) ) {
            $this->log('Error: display setting failed validation');

            return FALSE;
        }

        //By Default, New PDFs will be displayed in browser.
        //Otherwise, download pdf file.
        $b_display = TRUE;
        if( $s_display == 'download' ) {
            $b_display = FALSE;
        }

        //Store Class
        if( ! isset($i_class) || empty($i_class) ) {
            $this->log('Warning: Class ID Not Passed');

            return FALSE;
        }

        //Validation Past, Store Parameters
        global $current_user;
        $this->o_user    = $current_user;
        $this->b_display = $b_display;
        $this->i_class   = $i_class;

        return TRUE;
    }

    public function get_data() {
        //1. Store PDF to Stamp
        $this->log('Loading Student Letter File');
        if( ! $this->load_file() ) {
            return FALSE;
        }

        //2. Initialise PDF Document
        $this->log('Initialising Document');
        if( ! $this->initiate_document() ) {
            return FALSE;
        }

        //3. Load Student List
        $this->log('Loading Student List');
        if( ! $this->load_student_list() ) {
            return FALSE;
        }

        //4. Determine How Many Pages will be needed for this class
        $this->log('Calculating Number of Letter Pages Needed');
        if( ! $this->calculate_pages() ) {
            return FALSE;
        }

        return TRUE;
    }

    private function load_file() {
        //Get Post ID of Student Letter Template PDF
        $i_post = $this->get_student_letter_pdf_post();
        //Generate URL to File
        $s_url = $this->generate_file_url($i_post);

        $s_parsed = $this->parse_url($s_url);
        if( $s_parsed !== FALSE ) {
            $this->s_file_url = $s_parsed;
            return TRUE;
        }

        return FALSE;
    }

    private function initiate_document() {
        //---------- Load Original PDF Document----------\\
        try {
            $this->log('Set Reader');
            $o_reader = new SetaPDF_Core_Reader_String($this->s_file_url);
            $this->log('Set Writer');
            $o_writer = new SetaPDF_Core_Writer_Http($this->s_file_name, $this->b_display);

            error_log('s3 url ' . $this->s_file_url);
            error_log('s3 file ' . $this->s_file_name);
            //Create Document Object
            $this->log('Set Document');
            $this->o_document = SetaPDF_Core_Document::load($o_reader, $o_writer);

            //Get Page Info
            $this->o_pages = $this->o_document->getCatalog()->getPages();
            $this->i_pages = $this->o_pages->count();
            $this->log('Document Initialised');

            return TRUE;
        } catch( Exception $e ) {
            $this->log('Error: Unable to Create Stamp Document');
            $this->log('Code: ' . $e->getCode());
            $this->log($e->getMessage());
        }

        return FALSE;
    }

    private function load_student_list() {
        // updated Feb 2019 to prevent count_total performing slow query
        $args = array(
            'role'       => 'student',
            'count_total'   => false,
            'meta_query' => array(
                'relation' => 'AND',
                0          => array(
                    'key'   => 'class',
                    'value' => $this->i_class
                ),
                1          => array(
                    'key'   => 'active',
                    'value' => 1
                )
            )
        );

        $o_query = new WP_User_Query($args);  // args updated for slow query

        if( ! empty($o_query->results) ) {
            $this->log('Found ' . count($o_query->results) . ' Students in Class #' . $this->i_class);
            $this->a_students = $o_query->results;

            return TRUE;
        }

        $this->log('Error: Student List is Empty');

        return FALSE;
    }

    private function calculate_pages() {
        if( ! empty($this->a_students) ) {
            $i_user  = count($this->a_students);
            $i_limit = $this->i_on_page;

            //Determine number of pages to create.
            //Students per page ($i_per_page)
            //divided by
            //Number of students ($i_student_count)
            $i_pages = ceil($i_user / $i_limit);

            for( $ii = 1; $ii < $i_pages; $ii++ ) {
                //For every extra page we need to include all students
                //Add a duplicate of first page to end of file
                $nextPage = $this->o_pages->extract(1, $this->o_document);
                $this->o_pages->append($nextPage);
            }

            $this->i_pages = $this->o_pages->count();

            return TRUE;
        }

        $this->log('Cannot Calculate Pages With no Students');

        return FALSE;
    }


    public function stamp() {
        $this->log('----- Begin Student Letter PDF -----');

        //Now Stamp Student Data to Pages
        $this->log('Printing Letters');
        $i_page  = 1;
        $i_count = 0;
        foreach( $this->a_students as $idx => $a_student ) {
            if( $i_count >= $this->i_on_page ) {
                $i_page++;
                $i_count = 0;
            }

            $this->stamp_student_letter($i_page, $i_count, $a_student);
            $i_count++;
        }

        $this->log('Save Document');
        // Show the the while page at a time
        $this->o_document->getCatalog()->setPageLayout(SetaPDF_Core_Document_PageLayout::SINGLE_PAGE);
        // save and send it to the client
        $this->o_document->save()->finish();
        $this->log('----- Student Letter PDF Completed -----');

        return TRUE;
    }

    private function generate_file_url( $i_post = NULL ) {
        if( ! isset($i_post) ) {
            $this->log('Error: Cannot get Secure Attachment URL of null PostID');
            return NULL;
        }
        $s3Info = get_post_meta($i_post, 'amazonS3_info', true);

        return CDN_URL.'/'.$s3Info['key'];

        // global $lzaws;

        // return $lzaws->get_secure_attachment_url($i_post, 300);
    }

    private function parse_url( $s_url = NULL ) {
        if( ! isset($s_url) ) {
            $this->log('Error: Cannot parse url of null secure attachment string');
            return FALSE;
        }

        $a_url    = [];
        $a_parsed = parse_url($s_url);

        foreach( $a_parsed as $key => $value ) {
            if( $key == 'scheme' ) {
                $value = $value . '://';
            } else if( $key == 'port' ) {
                $value = ':' . $value;
            } else if( $key == 'query' ) {
                $value = '?' . $value;
            } else if( $key == 'path' ) {
                $a_exploded = explode("/", $value);
                foreach( $a_exploded as $part => $val ) {
                    $a_exploded[ $part ] = urlencode($val);
                }
                $value = implode('/', $a_exploded);
            }
            $a_url[] = $value;
        }

        if( ! empty($a_url) ) {
            $s_new = implode('', $a_url);

            $s_file = file_get_contents($s_new);
            if ($s_file) {
                return $s_file;
            } else {
                $this->log('Error: Unable To Get Contents of Parsed URL </br>'.$s_new);
            }
        } else {
            $this->log('Error: Unable To Parse URL');
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

    /**
     * @param int    $i_page
     * @param int    $i_pos
     * @param object $o_student
     *
     * @return bool
     */
    private function stamp_student_letter( $i_page = NULL, $i_pos = NULL, $o_student = NULL ) {
        if( ! isset($i_page, $i_pos, $o_student) ) {
            $this->log('Stamp Student Letter: No Student Data Passed');

            return FALSE;
        }
        $o_stamper = new SetaPDF_Stamper($this->o_document);

        //Calculate PlaceMent of Student Info
        $a_pos = $this->get_letter_position($i_pos);

        //Set parameters for username stamp
        $a_login = array(
            'text'  => $o_student->user_login,
            'width' => 200,
            'page'  => $i_page,
            'x'     => $a_pos['x'] + 245,
            'y'     => $a_pos['y'] - 212
        );

        //Set parameters for password stamp
        $a_pwd = array(
            'text'  => $o_student->show_user_pwd,
            'width' => 200,
            'page'  => $i_page,
            'x'     => $a_pos['x'] + 245,
            'y'     => $a_pos['y'] - 250
        );

        $b_name = FALSE;
        $s_name = trim(ucwords($o_student->first_name . ' ' . $o_student->last_name));

        if( strlen($s_name) >= 27 ) {
            $b_name = TRUE;
        }

        $s_name .= '\'s Login';

        //Set parameters for Full Name stamp
        $a_name = array(
            'text'  => $s_name,
            'width' => 400,
            'page'  => $i_page,
            'x'     => $a_pos['x'] + 125,
            'y'     => $a_pos['y'] - 165
        );

        try {
            //Stamp UserName
            $this->stamp_text_field($a_login, $o_stamper, FALSE);
            //Stamp Password
            $this->stamp_text_field($a_pwd, $o_stamper, FALSE);
            //Stamp Student Full Name
            $this->stamp_text_field($a_name, $o_stamper, TRUE, $b_name);

            return TRUE;
        } catch( Exception $e ) {
            $this->log('Error: Failed to Stamp letter for Student#:' . $o_student->ID);
            $this->log('Message:' . $e->getMessage());
        }

        return FALSE;
    }

    /* Get Letter Position
     * define custom starting position when $this->i_per_page is set to more than 1
     *
     */
    private function get_letter_position( $i_no = 0 ) {
        //Default: 1 stamp group per page
        $a_letter[] = array(
            'y' => 0,
            'x' => 0
        );
        //Customer: per page = $this->i_per_page
        $a_letter[] = array(
            'y' => -(211),
            'x' => 0
        );
        $a_letter[] = array(
            'y' => -(422),
            'x' => 0
        );

        return $a_letter[ $i_no ];
    }

    private function stamp_text_field( $a_data, $stamper, $s_header = FALSE, $b_long = FALSE ) {
        $font  = SetaPDF_Core_Font_Standard_Helvetica::create($this->o_document);
        $stamp = new SetaPDF_Stamper_Stamp_Text($font);

        if( $s_header === FALSE ) {
            $stamp->setAlign(SetaPDF_Core_Text::ALIGN_LEFT);
            $stamp->setFontSize(16);
            $stamp->setOpacity(1);
            $stamp->setLineHeight(20);
        } else {
            $o_colour = new SetaPDF_Core_DataStructure_Color_Rgb(37 / 255, 64 / 255, 143 / 255);

            $stamp->setAlign(SetaPDF_Core_Text::ALIGN_LEFT);
            $stamp->setFontSize(25);
            $stamp->setLineHeight(25);

            if( $b_long === TRUE ) {
                $stamp->setFontSize(15);
                $stamp->setLineHeight(15);
            }

            $stamp->setOpacity(1);
            $stamp->setTextColor($o_colour);
        }

        $stamp->setText($a_data['text']);
        $stamp->setWidth($a_data['width']);
        $stamper->addStamp($stamp, array(
            'showOnPage' => $a_data['page'],
            'translateX' => $a_data['x'],
            'translateY' => $a_data['y']
        ));
        $stamper->stamp();
    }

    private function get_student_letter_pdf_post() {
        global $wpdb;

        $i_post = NULL;

        $a_args = array(
            '_wp_attached_file',
            'Wushka-School-Student-User-Login-Cards.pdf'
        );

        $s_query = 'SELECT * FROM ' . $wpdb->prefix . 'postmeta WHERE meta_key = %s AND meta_value = %s';

        $o_row = $wpdb->get_row(
            $wpdb->prepare($s_query, $a_args)
        );

        if( isset($o_row) && ! empty($o_row) ) {
            $i_post = (int)$o_row->post_id;
            $this->log('Student Letters PDF post_id = ' . $i_post);

            return $i_post;
        }

        $this->log('Error: No Student Letters PDF found');

        return NULL;
    }

    private function log( $s_text = NULL ) {
        if( $this->b_test && isset($s_text) ) {
            $this->a_logs[] = $s_text;
            error_log($s_text);
        }

        return TRUE;
    }

    public function get_log() {
        return end($this->a_logs);
    }

}

/* ----- EOF ------ */