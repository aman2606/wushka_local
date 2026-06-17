<?php
if( ! defined('ABSPATH') ) {
    exit;
}

if( ! function_exists('add_action') ) {
    echo "This page cannot be called directly.";
    exit;
}

require_once plugin_dir_path(__FILE__) . '../stamp/library/SetaPDF/Autoload.php';
require_once get_template_directory().'/functions/ajax_student-statistics.php';

class Stamp_Student_Statistics {
    //Variable Parameters
    private $c_stats;
    private $s_type;
    private $o_user;
    private $a_options;
    private $a_students;
    private $s_hours;
    private $i_class;
    private $s_class;
    private $i_user;
    private $s_image;

    //Stamp Parameters
    private $b_display;
    private $i_on_page;
    private $s_file_name;
    private $s_file_url;
    private $o_document;
    private $o_fresh_document;
    private $o_fresh_pages;
    private $i_pages;
    private $o_pages;
    private $o_stamper;
    private $i_xMod;
    private $i_yMod;

    //Turn Test State On/Off
    private $b_test;

    public function __construct( $s_type = NULL ) {
        $this->a_options = $this->load_options();
        $this->c_stats = new Student_Statistics();
        //Parameters
        $this->s_type    = $s_type;
        $this->b_display = TRUE;
        $this->s_hours  = 'both';
        $this->i_class   = NULL;
        $this->s_class   = NULL;
        $this->i_user    = NULL;
        $this->s_image   = NULL;
        //Document Settings
        $this->s_file_name = 'Wushka Student Statistics.pdf';
        $this->s_file_url  = NULL;
        $this->o_document  = NULL;
        $this->o_stamper   = NULL;
        $this->i_pages     = 0;
        $this->o_pages     = NULL;
        $this->i_on_page = 1;
        $this->xMod = NULL;
        $this->yMod = NULL;

        $this->o_fresh_document = NULL;
        $this->o_fresh_pages = NULL;

        $this->a_students = [];

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

        $s_type    = $this->s_type;
        $s_display = stripcslashes(filter_input(INPUT_POST, 'display'));
        $s_hours  = stripcslashes(filter_input(INPUT_POST, 's_hours'));
        $i_user    = stripcslashes(filter_input(INPUT_POST, 'user_id'));
        $i_class   = stripcslashes(filter_input(INPUT_POST, 'class_id'));
        $s_image   = filter_input(INPUT_POST, 's_image');

        //Store Stamp Type
        if( ! isset($s_type) || empty($s_type) ) {
            $this->log('Error: stamp type failed validation');

            return FALSE;
        }

        //List of Valid Types for Student Quiz Stamps
        $a_types = array(
            'student',
            'class'
        );
        if( ! in_array($s_type, $a_types) ) {
            $this->log('Error: Invalid Stamp Type');

            return FALSE;
        }

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

        //Store Stamp Filter
        if( isset($s_hours) || ! empty($s_hours) ) {
            //List of Valid Types for Student Quiz Stamps
            $a_types = array(
                'school',
                'home',
                'both'
            );
            if( in_array($s_hours, $a_types) ) {
                $this->s_hours = $s_hours;
            }
        }

        //Store User ID
        if( $s_type == 'student' && (! isset($i_user) || empty($i_user)) ) {
            $this->log('Warning: User ID Not Passed');

            return FALSE;
        }

        //Store Class ID
        if( $s_type == 'class' && (! isset($i_class) || empty($i_class)) ) {
            $this->log('Warning: Class ID Not Passed');

            return FALSE;
        }

        if ( ! isset($s_image) || empty($s_image)) {
            $this->log('Error: Statistics Canvas Image String not found');

            return FALSE;
        }
        

        $s_image = str_replace('data:image/jpeg;base64,', '', $s_image);
        $s_image = str_replace(' ', '+', $s_image);
        $o_image = base64_decode($s_image);


        //Validation Past, Store Parameters
        global $current_user;
        $this->o_user    = $current_user;
        $this->b_display = $b_display;
        $this->i_class   = $i_class;
        $this->i_user    = $i_user;
        $this->s_image   = $o_image;

        return TRUE;
    }

    public function get_data() {
        //1. Store PDF to Stamp
        $this->log('Loading Student Statistics File');
        if( ! $this->load_file() ) {
            return FALSE;
        }

        //2. Initialise PDF Document
        $this->log('Initialising Document');
        if( ! $this->initiate_document() ) {
            return FALSE;
        }

        //3. Load Student/Class User Data
        $this->log('Loading User Data');
        if( ! $this->load_user_list() ) {
            return FALSE;
        }

        /*//4. Load Student Quiz Results
        $this->log('Loading User Student Statistics Data');
        if( ! $this->load_statistics() ) {
            return FALSE;
        }*/

        return TRUE;
    }

    private function load_file() {
        //Get Post ID of Student Coupon Template PDF
        $i_post = $this->get_statistics_pdf_post();
        //Generate URL to File
        $s_url = $this->generate_file_url($i_post);

        $s_file = $this->get_file_contents($s_url);

        if( $s_file !== FALSE ) {
            $this->s_file_url = $s_file;

            return TRUE;
        }


        $this->log('Error: Failed to Load PDF URL.');

        return FALSE;
    }

    private function initiate_document() {
        //---------- Load Original PDF Document----------\\
        try {
            $this->log('Set Reader');
            $o_reader = new SetaPDF_Core_Reader_String($this->s_file_url);
            $fresh_reader = new SetaPDF_Core_Reader_String($this->s_file_url);

            $this->log('Set Writer');
            $o_writer = new SetaPDF_Core_Writer_Http($this->s_file_name, $this->b_display);
            $fresh_writer = new SetaPDF_Core_Writer_Http($this->s_file_name, $this->b_display);

            //Create Document Object
            $this->log('Set Document');
            $this->o_document = SetaPDF_Core_Document::load($o_reader, $o_writer);
            $this->o_fresh_document =  SetaPDF_Core_Document::load($fresh_reader, $fresh_writer);

            //Get Page Info
            $this->o_pages = $this->o_document->getCatalog()->getPages();
            $this->o_fresh_pages = $this->o_fresh_document->getCatalog()->getPages();

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

    private function load_user_list() {
        switch( $this->s_type ) {
            case 'student':
                return $this->get_student();
                break;
            case 'class':
                return $this->get_class();
                break;
        }

        return FALSE;
    }

    private function get_student() {
        $o_user = get_user_by_hash($this->i_user);
        if( $o_user !== FALSE ) {
            $this->log('Found User: ' . $o_user->ID . ' From Class: ' . $o_user->class);

            $this->a_students[] = array(
                'user'    => $o_user,
                'results' => array()
            );

            if( ! isset($this->s_class) ) {
                $this->log('Set Class Name where Class ID = '.$o_user->class);
                $this->set_class_name($o_user->class);
            }

            return TRUE;
        }

        $this->log('Error: Cannot Get User of ID:' . $this->i_user);

        return FALSE;
    }

    private function get_class() {
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
            $a_students = array();
            foreach( $o_query->results as $idx => $o_user ) {
                if( ! isset($this->s_class) ) {
                    $this->log('Set Class Name where Class ID = '.$o_user->class);
                    $this->set_class_name($o_user->class);
                }
                $a_students[] = array(
                    'user'    => $o_user,
                    'results' => array()
                );
            }

            $this->a_students = $a_students;

            return TRUE;
        }

        $this->log('Error: Student List is Empty');

        return FALSE;
    }

    private function set_class_name( $i_class = NULL ) {
        $o_class = wushka_get_class($i_class);
        if( isset($o_class) && ! empty($o_class) ) {
            $this->s_class = trim(ucwords($o_class->name));
            $this->log('Class Name is set to :'.$this->s_class);

            return TRUE;
        }

        $this->log('Error: Cannot Store Class name of Null Class');

        return FALSE;
    }

    private function load_statistics() {
        //1. Get Statistics For Students
        $this->get_results();

        //2. Process Results Data For Stamping
        return $this->set_user_results();
    }

    private function get_results() {
        if( ! empty($this->a_students) ) {
            foreach( $this->a_students as $idx => $a_user ) {
                $c_stats = new Student_Statistics();
                if ( $c_stats->set_parameters($a_user['user']->ID, $this->s_hours, $this->s_years) ) {
                    $c_stats->get_student_statistics();
                    $this->a_students[$idx]['results'] = $c_stats->get_results();
                }

                unset($c_stats);
            }

            return TRUE;
        }

        return FALSE;
    }

    private function set_user_results( $a_filtered = array() ) {
        $this->log('Get Quiz Results for ' . count($this->a_students) . ' Students');
        if( ! empty($a_filtered) ) {
            foreach( $this->a_students as $key => $a_student ) {
                $a_scores = array();
                foreach( $a_filtered as $idx => $o_score ) {
                    if( (int)$o_score->createdBy == (int)$a_student['user']->ID ) {
                        $a_quiz     = $this->process_result($o_score);
                        $a_scores[] = $a_quiz;
                    }
                }

                if( ! empty($a_scores) ) {
                    $this->a_students[ $key ]['results'] = $a_scores;
                    $this->log('Student #' . $a_student['user']->ID . ' Has :' . count($a_scores) . ' Quiz Scores');
                } else {
                    unset($this->a_students[ $key ]);
                }
                unset($a_scores);
            }

            $this->log('Removing Users with no results');
            $this->log(count($this->a_students) . ' Students to Be Added to PDF');

            return TRUE;
        }

        $this->log('Error: No Quiz Results Found');

        return FALSE;
    }

    private function process_result( $o_result = NULL ) {
        if( ! isset($o_result) || empty($o_result) ) {
            return FALSE;
        }

        $a_score = explode('/', $o_result->score);
        $i_score = (int)trim($a_score[0]);

        $a_scores  = json_decode($o_result->answers);
        $a_answers = array();

        foreach( $a_scores as $idx => $o_answer ) {
            $a_answers[] = ! empty($o_answer->valid) ? $o_answer->valid : NULL;
        }

        $a_quiz = array(
            'name'   => trim(ucwords($o_result->name)),
            'time'    => date("dS M Y g:ia", strtotime($o_result->createdDate)),
            'score'   => $i_score,
            'answers' => $a_answers
        );

        return $a_quiz;
    }

    private function get_events() {
        $i_school = wushka_get_user_school($this->o_user->ID);

        if( isset($i_school) && ! empty($i_school) ) {
            //Get School Term User
            $o_school = wushka_get_school_term_user($i_school);
            //Get School State
            $s_state  = wushka_get_school_caldendar_state($i_school);
            //Load School Calendar Events
            $a_events = wushka_get_calendar_events($s_state, $o_school->ID);

            return $a_events;
        }

        return array();
    }

    public function stamp() {
        $this->log('----- Begin Student Statistics PDF -----');

        //Now Stamp Student Data to Pages
        $this->log('Printing Statistics');
        $i_page = 1;

        $i_primary = 6; //Number of Rows on Primary Student Pages
        $i_extra   = 12; //Number of Rows on Extra rows Pages

        //Remove Original First Two Pages
        #TODO: Figure Out How Many Pages There Are
        $this->o_pages->deletePage(1);

        //Build Stamp Handler
        try {
            $this->o_stamper = new SetaPDF_Stamper($this->o_document);
        } catch( Exception $e ) {
            $this->log('Error: Failed to Create New Stamp Handler');
            $this->log($e->getMessage());

            return FALSE;
        }


        foreach( $this->a_students as $idx => $a_student ) {
            //Step 1 - Add Primary Page to Document
            $b_primary = TRUE;
            $p_primary = $this->o_fresh_pages->extract(1, $this->o_fresh_document);
            $this->o_pages->append($p_primary);


            $this->log('Stamping Student '.$idx);

            //Set Y Coord Pos of this Row
            $this->yMod = NULL;

            //Step 2 - Stamp Student Details
            if( ! $this->stamp_header($i_page, $a_student['user'] ) ) {
                $this->log('Error: Stamp Failed: Abort Process');

                return FALSE;
            }


            if( ! $this->stamp_image_field('stats', $i_page) ) {
                $this->log('Error: Stamp Failed: Abort Process');

                return FALSE;
            }

            //Move to Next Student
            $i_page++;
        }

        $this->o_pages = $this->o_document->getCatalog()->getPages();


        //Clean System, Finish PDF Document
        $this->log('Document Created, Save Final Product');
        $this->o_document->getCatalog()->setPageLayout(SetaPDF_Core_Document_PageLayout::SINGLE_PAGE);
        // save and send it to the client
        $this->o_document->save()->finish();
        $this->log('----- Quiz Results PDF Completed -----');

        return TRUE;
    }

    private function stamp_header( $i_page = NULL, $o_user = NULL ) {
        if( ! isset($i_page, $o_user) ) {
            $this->log('Error: Cannot stamp header with missing parameters');

            return FALSE;
        }

        //Stamp Report Heading
        $s_name = 'Statistics Report';
        if( ! $this->stamp_text_field('heading', $s_name, $i_page) ) {
            return FALSE;
        }

        //Stamp Student Name
        $s_name = trim(ucwords($o_user->first_name . ' ' . $o_user->last_name));
        if( ! $this->stamp_text_field('user', $s_name, $i_page) ) {
            return FALSE;
        }

        return TRUE;
    }

    private function stamp_row( $i_page, $a_result ) {
        if( ! isset($i_page, $a_result) || empty($a_result) ) {
            $this->log('Error: Cannot stamp row with missing parameters');

            return FALSE;
        }

        //Stamp Quiz Name
        $s_name = trim(ucwords($a_result['name']));
        if( ! $this->stamp_text_field('row_name', $s_name, $i_page) ) {
            return FALSE;
        }

        //Stamp Quiz Time
        $s_name = trim(ucwords($a_result['time']));
        if( ! $this->stamp_text_field('row_time', $s_name, $i_page) ) {
            return FALSE;
        }

        //Stamp Quiz Name
        $s_name = trim($a_result['score']);
        if( ! $this->stamp_text_field('row_score', $s_name, $i_page) ) {
            return FALSE;
        }

        foreach( $a_result['answers'] as $idx => $s_answer ) {
            //Set X Coord Pos of this Answer Stamp
            $this->set_answer_position($idx);
            //Stamp Quiz Name
            if( ! $this->stamp_image_field( $s_answer, $i_page) ) {
                return FALSE;
            }
        }

        return TRUE;
    }

    private function set_row_position( $b_primary = TRUE, $i_row = 0 ) {
        //Default Values
        #TODO: Adjust Default height values To Match Rows in Template PDF
        //Primary Page has large header, first row further down
        $i_pos = $b_primary ? 150 : 0;
        $i_height = 50;

        if( ! empty($i_row) ) {
            //Increase Height value per row counter
            $i_modifier = $i_height * $i_row;

            $i_pos = $i_pos + $i_modifier;
        }

        $this->set_yMod($i_pos);

        return TRUE;
    }

    private function set_answer_position( $i_answer = 0 ) {
        //Default Values
        #TODO: Adjust Default height values To Match Rows in Template PDF
        //Position = Left Page Edge + $x Pixels to Left Side of Stamp
        $i_pos = 365;
        $i_width = 35;

        if( ! empty($i_answer) ) {
            //Increase Height value per row counter
            $i_modifier = $i_width * $i_answer;

            $i_pos = $i_pos + $i_modifier;
        }

        $this->set_xMod($i_pos);

        return TRUE;
    }

    private function set_yMod($i_pos = NULL) {
        if ( isset($i_pos) && ! empty($i_pos) ) {
            $this->yMod = $i_pos;
        }
        return TRUE;
    }

    private function set_xMod($i_pos = NULL) {
        if ( isset($i_pos) && ! empty($i_pos) ) {
            $this->xMod = $i_pos;
        }
        return TRUE;
    }

    private function modify_coordinate_y($i_coord = 0) {
        if ( isset($this->yMod) && ! empty($this->yMod) ) {
            $i_coord = $i_coord - $this->yMod;
        }

        return $i_coord;
    }

    private function modify_coordinate_x($i_coord = 0) {
        if ( isset($this->xMod) && ! empty($this->xMod) ) {
            $i_coord = $i_coord + $this->xMod;
        }

        return $i_coord;
    }

    private function stamp_text_field( $s_type = 'default', $s_text = 'Insert Text Here', $i_page = 1) {
        if( ! isset($this->o_stamper) ) {
            $this->log('Cannot Create Text Stamp on Null Handler');

            return FALSE;
        }

        try {
            //Create New Text Stamp
            $o_font  = SetaPDF_Core_Font_Standard_Helvetica::create($this->o_document);
            $o_stamp = new SetaPDF_Stamper_Stamp_Text($o_font);

            //Get Stamp Parameters
            $a_params = $this->get_text_stamp_params($s_type);
            //Add Stamp Formatting
            $o_stamp->setAlign($a_params['text_align']);
            $o_stamp->setFontSize($a_params['font_size']);
            $o_stamp->setLineHeight($a_params['line_height']);
            $o_stamp->setOpacity($a_params['opacity']);
            $o_stamp->setTextColor($a_params['colour']);
            $o_stamp->setWidth($a_params['width']);

            //Add Text to Stamp
            $o_stamp->setText($s_text);

            //If Row Position isset, modify Y Coord
            $i_yCoord = $this->modify_coordinate_y($a_params['y']);

            //Store New Text Stamp to Stamp Handler
            $this->o_stamper->addStamp($o_stamp, array(
                'showOnPage' => $i_page,
                'translateX' => $a_params['x'],
                'translateY' => $i_yCoord
            ));

            //Trigger Stamp Generation
            $this->o_stamper->stamp();

            return TRUE;
        } catch( Exception $e ) {
            $this->log('Exception Error: Failed to Stamp Text Field');
            $this->log($e->getMessage());
        }

        return FALSE;
    }

    private function stamp_image_field( $s_type = 'default', $i_page = 1) {
        if( ! isset($this->o_stamper) ) {
            $this->log('Cannot Create Image Stamp on Null Handler');

            return FALSE;
        }

        try {
            //Get Stamp Parameters
            $a_params = $this->get_image_stamp_params($s_type);
            #TODO: Testing if passed image string DOES NOT require additional parsing
            $s_file = $a_params['url'];

            //Create New Reader For Image
            $s_reader = new SetaPDF_Core_Reader_String($s_file);

            //Create New Image Instance From Image File Reader
            $o_image = SetaPDF_Core_Image::get($s_reader);

            //initiate the image stamp
            $o_stamp = new SetaPDF_Stamper_Stamp_Image($o_image);


            //Add Stamp Formatting
            $o_stamp->setWidth($a_params['width']);
            $o_stamp->setHeight($a_params['height']);

            //Modify Coordinates
            $i_yCoord = $this->modify_coordinate_y($a_params['y']);
            $i_xCoord = $this->modify_coordinate_x($a_params['x']);

            //Store New Text Stamp to Stamp Handler
            $this->o_stamper->addStamp($o_stamp, array(
                'showOnPage' => $i_page,
                'translateX' => $i_xCoord,
                'translateY' => $i_yCoord
            ));

            //Trigger Stamp Generation
            $this->o_stamper->stamp();

            return TRUE;
        } catch( Exception $e ) {
            $this->log('Exception Error: Failed to Stamp Image Field');
            $this->log('Exception Msg: '.$e->getMessage());
        }

        return FALSE;
    }

    private function get_text_stamp_params( $s_type ) {
        switch( $s_type ) {
            case 'user' :
                return $this->stamp_text_user();
                break;
            case 'class' :
                return $this->stamp_text_class();
                break;
            case 'heading' :
                return $this->stamp_text_heading();
                break;
            default :
                return $this->default_stamp_text_params();
        }
    }

    private function get_image_stamp_params( $s_type ) {
        switch( $s_type ) {
            case 'stats' :
                return $this->stamp_image_stats();
                break;
            default :
                return $this->default_stamp_image_params();
        }
    }

    private function default_stamp_text_params() {
        return array(
            'font_size'   => 12,
            'text_align'  => SetaPDF_Core_Text::ALIGN_LEFT,
            'line_height' => 12,
            'opacity'     => 1,
            'colour'      => new SetaPDF_Core_DataStructure_Color_Rgb(0, 0, 0),
            'width'       => 300,
            'x'           => 300,
            'y'           => -100
        );
    }

    private function stamp_text_user() {
        $a_params = $this->default_stamp_text_params();

        //Alter Default Params For this Stamp
        $a_params['font_size'] = 20;
        $a_params['line_height'] = 20;
        $a_params['colour'] = new SetaPDF_Core_DataStructure_Color_Rgb(37 / 255, 64 / 255, 143 / 255);
        $a_params['text_align'] = SetaPDF_Core_Text::ALIGN_CENTER;
        $a_params['x'] = 10;
        $a_params['y'] = -120;
        $a_params['width'] = 573;

        return $a_params;
    }

    private function stamp_text_class() {
        $a_params = $this->default_stamp_text_params();

        //Alter Default Params For this Stamp
        $a_params['font_size'] = 20;
        $a_params['line_height'] = 20;
        $a_params['colour'] = new SetaPDF_Core_DataStructure_Color_Rgb(37 / 255, 64 / 255, 143 / 255);
        $a_params['y'] = -150;

        return $a_params;
    }

    private function stamp_text_heading() {
        $a_params = $this->default_stamp_text_params();

        //Alter Default Params For this Stamp
        $a_params['font_size'] = 20;
        $a_params['line_height'] = 20;
        $a_params['colour'] = new SetaPDF_Core_DataStructure_Color_Rgb(37 / 255, 64 / 255, 143 / 255);
        $a_params['text_align'] = SetaPDF_Core_Text::ALIGN_CENTER;
        $a_params['x'] = 10;
        $a_params['y'] = -90;
        $a_params['width'] = 573;

        return $a_params;
    }

    private function default_stamp_image_params() {
        return array(
            'url'    => CDN_URL.'/Resources/mark-correct.png',
            'height' => 641,
            'width'  => 573,
            'x'      => 10,
            'y'      => -170
        );
    }

    private function stamp_image_stats() {
        $a_params = $this->default_stamp_image_params();

        //Alter Default Params For this Stamp
        $a_params['url'] = $this->s_image;


        return $a_params;
    }

    private function generate_file_url( $i_post = NULL ) {
        if( ! isset($i_post) ) {
            return NULL;
        }

        $s3Info = get_post_meta($i_post, 'amazonS3_info', true);

        return CDN_URL.'/'.$s3Info['key'];

        // global $lzaws;

        // return $lzaws->get_secure_attachment_url($i_post, '300');
    }

    private function get_file_contents($s_url = NULL) {
        if ( isset($s_url) && ! empty($s_url) ) {
            $s_parsed = $this->parse_url($s_url);
            if ( isset($s_parsed) && ! empty($s_parsed) ) {
                $s_file = file_get_contents($s_parsed);
                if ( $s_file !== FALSE ) {
                    return $s_file;
                }
            }
        }

        $this->log('Unable to Get File Contents of NULL Parameters');

        return FALSE;
    }

    private function parse_url( $s_url = NULL ) {
        if( ! isset($s_url) ) {
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

            return $s_new;
        }

        $this->log('Error: Unable To Parse URL');

        return FALSE;
    }

    private function get_statistics_pdf_post() {
        global $wpdb;

        $i_post = NULL;

        $a_args = array(
            '_wp_attached_file',
            'Wushka-Student-Statistics.pdf'
        );

        $s_query = 'SELECT * FROM ' . $wpdb->prefix . 'postmeta WHERE meta_key = %s AND meta_value = %s';

        $o_row = $wpdb->get_row(
            $wpdb->prepare($s_query, $a_args)
        );

        if( isset($o_row) && ! empty($o_row) ) {
            $i_post = (int)$o_row->post_id;
            $this->log('Student Quiz Results PDF post_id = ' . $i_post);

            return $i_post;
        }

        $this->log('Error: No Student Quiz Results PDF found');

        return NULL;
    }

    private function log( $s_text = NULL ) {
        if( $this->b_test && isset($s_text) ) {
            error_log($s_text);
        }

        return TRUE;
    }

}

/* ----- EOF ------ */