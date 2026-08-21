<?php

/**
 * Created by PhpStorm.
 * User: Jordan
 * Date: 16/03/2016
 * Time: 3:43 PM
 */
class Class_Statistics {

    private $a_results;
    private $i_class;
    private $i_school;
    private $s_hours;
    private $s_years;
    private $a_records;
    private $a_events;


    public function __construct() {
        $this->i_class    = NULL;
        $this->i_school   = NULL;
        $this->s_hours    = NULL;
        $this->s_years    = NULL;
        $this->a_records  = array();
        $this->a_students = array();
        $this->a_events   = array();
        $this->a_results  = array(
            'status'  => 0,
            'message' => 'Validating Parameters',
            'data'    => array()
        );
    }

    public function validate_post_parameters() {
        global $current_user;

        if( ! current_user_can('school') && ! current_user_can('teacher') ) {
            $this->a_results['message'] = 'Invalid User';

            return FALSE;
        }

        //Store Posted Parameters
        $s_validate = json_decode(stripcslashes(filter_input(INPUT_POST, 'validate')), TRUE);
        $i_class    = json_decode(stripcslashes(filter_input(INPUT_POST, 'i_class')), TRUE);
        $s_hours    = json_decode(stripcslashes(filter_input(INPUT_POST, 's_hours')), TRUE);
        $s_years    = json_decode(stripcslashes(filter_input(INPUT_POST, 's_years')), TRUE);

        //Check Validation Nonce
        if( ! isset($s_validate) || empty($s_validate) ) {
            $this->a_results['message'] = 'Class Filter Failed Validation';

            return FALSE;
        }
        //Validate Nonce
        if( ! wp_verify_nonce($s_validate, 'get_filtered_results_' . $current_user->ID) ) {
            $this->a_results['message'] = 'Class Filter Failed Validation';

            return FALSE;
        }

        //Validate Class ID
        if( ! isset($i_class) || empty($i_class) ) {
            $this->a_results['message'] = 'Class ID was inValid';

            return FALSE;
        }

        //Validate Hours Filter Type
        if( ! isset($s_hours) || empty($s_hours) ) {
            $this->a_results['message'] = 'Filter Slug was inValid';

            return FALSE;
        }

        //Ensure Filter Type Matches Acceptable Filter Slugs
        $a_accepted = array(
            'home',
            'school',
            'both'
        );

        //Does Passed Filter Match a Valid Option
        if( ! in_array($s_hours, $a_accepted) ) {
            $this->a_results['message'] = 'Passed Hours filter did not match valid slugs';

            return FALSE;
        }

        //Validate Year Filter Type
        if( ! isset($s_years) || empty($s_years) ) {
            $this->a_results['message'] = 'Filter Slug was inValid';

            return FALSE;
        }

        //Ensure Filter Type Matches Acceptable Filter Slugs
        $a_accepted = array(
            'current',
            'all'
        );

        //Does Passed Filter Match a Valid Option
        if( ! in_array($s_years, $a_accepted) ) {
            $this->a_results['message'] = 'Passed Years filter did not match valid slugs';

            return FALSE;
        }

        //Validation Passed, Store Params
        $this->i_class = $i_class;
        $this->s_hours = $s_hours;
        $this->s_years = $s_years;

        return TRUE;
    }

    public function set_parameters( $i_class = NULL, $s_hours = NULL, $s_years = NULL ) {
        //Validate Class ID
        if( ! isset($i_class) || empty($i_class) ) {
            $this->a_results['message'] = 'Class ID was inValid';

            return FALSE;
        }

        //Validate Filter Type
        if( ! isset($s_hours) || empty($s_hours) ) {
            $this->a_results['message'] = 'Filter Slug was inValid';

            return FALSE;
        }
        //Ensure Filter Type Matches Acceptable Filter Slugs
        $a_accepted = array(
            'home',
            'school',
            'both'
        );
        if( ! in_array($s_hours, $a_accepted) ) {
            $this->a_results['message'] = 'Passed filter did not match valid slugs';

            return FALSE;
        }

        //Validate Year Filter Type
        if( ! isset($s_years) || empty($s_years) ) {
            $this->a_results['message'] = 'Filter Slug was inValid';

            return FALSE;
        }

        //Ensure Filter Type Matches Acceptable Filter Slugs
        $a_accepted = array(
            'current',
            'all'
        );

        //Does Passed Filter Match a Valid Option
        if( ! in_array($s_years, $a_accepted) ) {
            $this->a_results['message'] = 'Passed Years filter did not match valid slugs';

            return FALSE;
        }

        //Validation Passed, Store Params
        $this->i_class = $i_class;
        $this->s_hours = $s_hours;
        $this->s_years = $s_years;

        return TRUE;
    }

    public function generate_rows() {
        $a_students = $this->get_class_students();
        $a_rows     = $this->get_student_rows($a_students);
        //Store Row Data For Parsing
        $this->a_results['data']['rows'] = $a_rows;


        return TRUE;
    }

    private function get_class_students() {
        //Set Arguments for Student Query
        // updated Feb 2019 to prevent count_total performing slow query
        $args = array(
            'role'       => 'student',
            'count_total'   => false,
            'meta_query' => array(
                'relation' => 'AND',
                0          => array(
                    'key'   => 'class',
                    'value' => $this->i_class,
                ),
                1          => array(
                    'key'   => 'active',
                    'value' => 1
                )
            )
        );

        //Return User Query Student Array
        $o_results = new WP_User_Query($args);  // args updated for slow query
        if( ! empty($o_results->results) ) {
            return $o_results->get_results();
        }

        //No Students Found
        $this->a_results['message'] = 'No Students Where Found for Class #' . $this->i_class;

        return array();
    }

    private function get_student_rows( $a_students = array() ) {
        if( empty($a_students) ) {
            return array();
        }

        //Get Student Reading Analytics
        $a_results = $this->get_reading_analytics($a_students);
        //Filter Analytics
        $a_filtered = $this->filter_results($a_results);
        //Process Data For Front End
        $a_processed = $this->process_results($a_filtered);
        //Create Table Rows From Processed Data
        $a_rows = $this->create_table_rows($a_processed);

        $this->a_results['message'] = count($a_rows) . ' Table Rows Have Been Created';
        $this->a_results['status']  = 1;

        return $a_rows;
    }

    /**
     * @param array $a_students
     *
     * @return array|mixed
     */
    private function get_reading_analytics( $a_students = array() ) {
        if( empty($a_students) ) {
            return array();
        }

        global $wpdb;

        $a_params    = array();
        $a_char_type = array();

        $tCurrent = date('Y');
        $d_yearStart = date('Y-m-d G:i:s', strtotime('01 January ' . $tCurrent));

        foreach( $a_students as $idx => $o_user ) {
            #TODO: Will need modification if Student->Child Account Links are Re-Implemented
            //Store User Records array For Data Storage After processing
            $a_params[]    = $o_user->ID;
            $a_char_type[] = '%d';

            $this->a_students[ $o_user->ID ] = $o_user;
        }

        $s_prep = implode(', ', $a_char_type);

        //Add Completed Value to params
        $a_params[] = 1;
        $s_table = $wpdb->prefix . 'lessonzone_reading_analytics_reading_instance';

        $s_query = 'SELECT *, WEEK(created) as week_no FROM ' . $s_table . ' WHERE ' .
            'user_id IN (' . $s_prep . ') AND completed = %d';

        if ($this->s_years !== 'all') {
            $s_query .= ' AND created >= %s';
            $a_params[] = $d_yearStart;
        }

        $a_results = $wpdb->get_results(
            $wpdb->prepare($s_query, $a_params)
        );

        if( isset($a_results) && ! empty($a_results) ) {
            return $a_results;
        }

        $this->a_results['message'] = 'No Reading Analytics Where Found for ' . count($a_students) . ' students';

        return array();
    }

    private function filter_results( $a_results = array() ) {
        if( empty($a_results) ) {
            return array();
        }
        //If no filter set, return all results
        if( ! $this->s_hours || $this->s_hours == 'both' ) {
            return $a_results;
        }

        //Get School User Based on School term id of current teacher user
        $o_school = $this->get_school_user();
        //Get School State
        $s_state = wushka_get_school_caldendar_state($this->i_school);
        //Load School Calendar Events
        $a_events = wushka_get_calendar_events($s_state, $o_school->ID);

        //Store For Quiz Filter
        $this->a_events = $a_events;

        //Get DateTimeZone
        $tz_utc    = new DateTimeZone('UTC');
        $s_tz      = wushka_get_school_timezone($this->i_school);
        $tz_school = new DateTimeZone($s_tz);

        foreach( $a_results as $i_key => $o_result ) {
            $dt = new DateTime($o_result->created, $tz_school);

            //$dt->setTimezone($tz_school);

            //Determine if current time is 'in school' OR 'at home'
            $s_hours = 'home';
            if( wushka_is_time_school_hours($dt->format('dS M Y g:ia'), $a_events) ) {
                $s_hours = 'school';
            }

            if( $s_hours !== $this->s_hours ) {
                unset($a_results[ $i_key ]);
            }
        }

        if( ! empty($a_results) ) {
            return $a_results;
        }

        $this->a_results['message'] = 'No Reading Analytics were created in ' . $this->s_hours . ' Filter';

        return array();
    }

    private function process_results( $a_filtered = array() ) {
        $a_records = array();

        foreach( $this->a_students as $i_user => $o_user ) {
            $a_data = $this->get_default_records();

            //Collect records grouped by User ID
            if( ! empty($a_filtered) ) {
                foreach( $a_filtered as $i_key => $o_result ) {
                    if( (int)$o_result->user_id !== $i_user ) {
                        continue;
                    }

                    $a_data['completed'] += $o_result->completed;
                    $a_data['avg_time'] += $o_result->duration;
                    $a_data['weeks'][]    = $o_result->week_no;
                    $a_data['book_ids'][] = $o_result->essis_resource_id;
                }
            }

            $a_records[ $i_user ] = $a_data;
            unset($a_data);
        }

        if( ! empty($a_records) ) {
            return $a_records;
        }

        $this->a_results['message'] = 'Could Not Process Results for ' . count($this->a_students) . ' Students';

        return array();
    }

    private function create_table_rows( $a_processed = array() ) {
        $a_rows = array();

        foreach( $this->a_students as $i_student => $o_student ) {

            //Store Student's Process Data Array
            $a_data = $a_processed[ $i_student ];

            //Get Average Books Per Week Read
            $i_books = 0;
            if( isset($a_data['weeks']) && ! empty($a_data['weeks']) ) {
                $i_books = $this->get_book_average($a_data['weeks']);
            }
            //Get Average Reading Time Per Book
            $a_time = array(
                'raw'   => 0,
                'final' => 0
            );

            if( isset($a_data['avg_time']) && ! empty($a_data['avg_time']) ) {
                $a_time = $this->get_time_average($a_data['avg_time'], $a_data['completed']);
            }
            //Get Average Quiz Score
            $i_quiz = $this->get_quiz_average($i_student);

            //Prep Table Table
            $a_row = array(
                'first_name' => ucwords($o_student->first_name),
                'last_name'  => ucwords($o_student->last_name),
                'id'         => $o_student->id_hash,
                'completed'  => $a_data['completed'],
                'avg_time'   => $a_time['final'],
                'avg_raw'    => $a_time['raw'],
                'avg_books'  => $i_books,
                'avg_quiz'   => $i_quiz,
                'validator'  => wp_create_nonce('student_details_nonce_' . $o_student->id_hash)
            );

            $a_rows[] = $a_row;
        }

        return $a_rows;
    }

    private function get_quiz_average( $i_student = NULL ) {
        if( ! isset($i_student) || empty($i_student) ) {
            return array();
        }

        global $wpdb;

        $tCurrent = date('Y');
        $d_yearStart = date('Y-m-d G:i:s', strtotime('01 January ' . $tCurrent));

        $s_table = $wpdb->prefix . 'plugin_slickquiz_scores';
        $s_query   = 'SELECT * FROM ' . $s_table . ' WHERE createdBy = %d';

        $a_params = array($i_student);

        if ($this->s_years !== 'all') {
            $s_query .= ' AND createdDate >= %s';
            $a_params[] = $d_yearStart;
        }

        $a_results = $wpdb->get_results(
            $wpdb->prepare($s_query, $a_params)
        );

        $i_average = 0;
        $a_average = array();

        if( isset($a_results) && ! empty($a_results) ) {
            $utc = new DateTimeZone('UTC');
            error_log('FOUND '.count($a_results).' QUIZ RESULTS TO AVG.');
            foreach( $a_results as $i_key => $o_result ) {
                if( $this->s_hours !== 'both' ) {
                    //Get DateTimeZone
                    $dt = new DateTime($o_result->createdDate, $utc);
                    //Determine if current time is 'in school' OR 'at home'
                    $s_hours = 'home';
                    if( wushka_is_time_school_hours($dt->format('dS M Y g:ia'), $this->a_events) ) {
                        $s_hours = 'school';
                    }

                    if( $s_hours !== $this->s_hours ) {
                        continue;
                    }
                }
                $a_score = explode('/', $o_result->score);
                if( count($a_score) == 2 ) {
                    $i_score     = (int)trim($a_score[0]);
                    $i_total     = (int)trim($a_score[1]);
                    $a_average[] = $i_score / $i_total * 100;
                }
            }

            if( ! empty($a_average) ) {
                $i_average = array_sum($a_average) / count($a_average);
            }
        }

        return round($i_average);

    }

    private function get_time_average( $i_time = 0, $i_books = 0 ) {
        $s_average = '0';
        $a_return  = array(
            'raw'   => 0,
            'final' => '0'
        );

        //Average Reading Time Per Book
        if( isset($i_time, $i_books) && ! empty($i_time) && ! empty($i_books) ) {
            //Average Time per book = Total Read Time in Seconds, Divided by Total Books
            $i_average = round($i_time / $i_books);

            $a_return['raw'] = $i_average;

            if( $i_average < 60 ) {
                //If Less than 1 minutes, display in seconds
                $s_average = $i_average . ' seconds';
            } else {
                //Display in Minutes
                $s_average = round(($i_average / 60), 1);
                //Singular OR Plural?
                if( $s_average == 1.0 ) {
                    $s_average .= ' minute';
                } else {
                    $s_average .= ' minutes';
                }
            }
        }

        $a_return['final'] = $s_average;

        return $a_return;
    }

    private function get_book_average( $a_weeks = array() ) {
        if( ! isset($a_weeks) || empty($a_weeks) ) {
            return 0;
        }

        $i_books = 0;
        $a_tally = array();

        // $weeks Array contains the week number for each books read.
        // 1. Tally How many Books were read in each Week
        // 2. Calculate Average of Books per week
        foreach( $a_weeks as $i_week ) {
            //Step 1.
            if( ! array_key_exists($i_week, $a_tally) ) {
                $a_tally[ $i_week ] = 1;
            } else {
                $a_tally[ $i_week ]++;
            }
        }

        //Step 2.
        if( count($a_tally) > 0 ) {
            $i_books = round(array_sum($a_tally) / count($a_tally));
        }

        return $i_books;
    }

    private function get_school_user() {
        global $current_user;

        $o_school_term = wp_get_object_terms($current_user->ID, 'school');
        $i_school      = NULL;
        if( isset($o_school_term) && ! empty($o_school_term) ) {
            $i_school = $o_school_term[0]->term_taxonomy_id;
        }

        $this->i_school = $i_school;

        //Get School Term User
        $o_school = wushka_get_school_term_user($i_school);
        if( isset($o_school) && ! empty($o_school) ) {
            return $o_school;
        }


        error_log('Could Not Find School User');
        $this->a_results['message'] = 'Could Not Find School User';

        return FALSE;
    }

    private function get_default_records() {
        return array(
            'completed' => 0,
            'avg_time'  => 0,
            'weeks'     => array(),
            'book_ids'  => array()
        );
    }

    public function get_results() {
        return $this->a_results;
    }

}

/* ----- END OF FILE ----- */