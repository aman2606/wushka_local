<?php

//Include Wordpress Config
include 'bookmarks/class_my-bookmarks.php';

// Exit if accessed directly
if( ! defined('ABSPATH') ) {
    exit();
}

class Student_Statistics {

    private $a_results;
    private $a_events;
    private $a_stats;
    private $o_student;
    private $a_all_levels;
    private $a_user_levels;
    private $a_group;
    private $i_student;
    private $i_school;
    private $s_timezone;
    private $s_hours;
    private $s_years;
    private $d_yearStart;
    private $b_test;

    public function __construct() {

        $this->i_student = NULL;
        $this->i_school  = NULL;
        $this->s_hours   = NULL;
        $this->s_years   = NULL;

        //Get Timestamp of First Jan of Current Year
        $tCurrent = date('Y');
        $this->d_yearStart = strtotime('01 January ' . $tCurrent);

        $this->a_stats       = array();
        $this->a_events      = array();
        $this->a_all_levels  = array();
        $this->a_user_levels = array();
        $this->a_group       = array();

        $this->a_results = array(
            'status'  => 0,
            'message' => 'Generating Student Statistics',
            'data'    => array()
        );

        #TODO: Set to FALSE after testing
        $this->b_test = FALSE;
    }

    public function validating_post_parameters() {
        $this->log('----- Student Statistics -----');
        $this->log('Validating...');

        //Check User
        if( ! is_user_logged_in() ) {
            $this->log('Error: Invalid User.');

            return FALSE;
        }

        $this->log(print_r($_POST, true));
        $s_validate = json_decode(stripcslashes(filter_input(INPUT_POST, 's_validator')), TRUE);
        $i_student  = json_decode(stripcslashes(filter_input(INPUT_POST, 'i_student')), TRUE);
        $s_nonce    = json_decode(stripcslashes(filter_input(INPUT_POST, 's_nonce')), TRUE);
        $s_hours    = json_decode(stripcslashes(filter_input(INPUT_POST, 's_hours')), TRUE);
        $s_years    = json_decode(stripcslashes(filter_input(INPUT_POST, 's_years')), TRUE);

        //Validate Ajax Submission
        if( ! isset($s_validate) || empty($s_validate) ) {
            $this->log('Error: Invalid Submission');

            return FALSE;
        }

        //Verify Posted Validator
        if( ! wp_verify_nonce($s_validate, 'get_student_statistics') ) {
            $this->log('Error: Invalid Submission');

            return FALSE;
        }

        //Validate Passed Student ID
        if( ! isset($i_student) || empty($i_student) ) {
            $this->log('Error: Invalid Student');

            return FALSE;
        }

        //Validate Student Nonce
        if( ! isset($s_nonce) || empty($s_nonce) ) {
            $this->log('Error: Unable to Verify Student');

            return FALSE;
        }

        //Verify Nonce
        if( ! wp_verify_nonce($s_nonce, 'student_details_nonce_' . $i_student) ) {
            $this->log('Error: Unable to Verify Student');

            return FALSE;
        }

        //Validate Filter
        if( ! isset($s_hours) || empty($s_hours) ) {
            $this->log('Error: Invalid Hours Filter');

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
            $this->log('Error: Hours Filter Type Not Recognised');

            return FALSE;
        }

        //Validate Filter
        if( ! isset($s_years) || empty($s_years) ) {
            $this->log('Error: Invalid Years Filter');

            return FALSE;
        }

        //Ensure Filter Type Matches Acceptable Filter Slugs
        $a_accepted = array(
            'current',
            'all'
        );

        //Does Passed Filter Match a Valid Option
        if( ! in_array($s_years, $a_accepted) ) {
            $this->log('Error: Years Filter Type Not Recognised');

            return FALSE;
        }

        //Validation Passed
        $this->log('Validation Success!');

        //Store Parameters
        $this->i_student = $i_student;
        $this->s_hours   = $s_hours;
        $this->s_years   = $s_years;

        //Get licence
        $this->a_results['licence'] = $this->get_student_licence($i_student);

        return TRUE;
    }

    public function get_student_licence($i_student = NULL){
        //Validate Passed Student ID
        if( ! isset($i_student) || empty($i_student) ) {
            $this->log('Error: Invalid Student');

            return FALSE;
        }
        $student = get_user_by_hash($i_student);
        //Get class id of student
        $class = get_user_meta($student->ID, 'class');
        $class_id = '';
        if(isset($class)){
            $class_id = $class[0];
        }

        if(!$class_id){
            return;
        }

        $licence = get_class_licence($class_id);

        return $licence;        
    }

    public function set_parameters( $i_student = NULL, $s_hours = NULL, $s_years = NULL ) {

        //Validate Passed Student ID
        if( ! isset($i_student) || empty($i_student) ) {
            $this->log('Error: Invalid Student');

            return FALSE;
        }

        //Validate Filter
        if( ! isset($s_hours) || empty($s_hours) ) {
            $this->log('Error: Invalid Hours Filter');

            return FALSE;
        }

        //Validate Filter
        if( ! isset($s_years) || empty($s_years) ) {
            $this->log('Error: Invalid Years Filter');

            return FALSE;
        }

        //Validation Passed
        $this->log('Parameters Set');

        //Store Parameters
        $this->i_student = $i_student;
        $this->s_hours   = $s_hours;
        $this->s_years   = $s_years;

        return TRUE;
    }

    public function get_student_statistics() {
        if( ! isset($this->i_student) ) {
            return FALSE;
        }

        $o_student = $this->get_user($this->i_student);

        $this->o_student = $o_student;

        $a_data = $this->get_student_data($o_student);

        if( $a_data !== FALSE ) {
            $this->log('SUCCESS: Student Stats Have Been Generated');
            $this->a_results['status'] = 1;
            $this->a_results['data']   = $a_data;
        }

        return TRUE;
    }

    private function get_user( $s_user = NULL ) {
        if( isset($s_user) && ! empty($s_user) ) {
            $o_user = get_user_by_hash($s_user);
            if( $o_user !== FALSE ) {
                unset($o_user->data->user_pass);
                return $o_user;
            }
        }

        $this->log('Error: No User Was Found.');

        return FALSE;
    }

    private function get_student_data( $o_student = NULL ) {
        if( ! isset($o_student) || empty($o_student) ) {
            $this->log('Error: Cannot get data of null student');

            return FALSE;
        }

        $this->log('Generating Student Data...');

        $this->a_group = $this->get_reading_group_books($o_student->my_reading_group);

        $a_data = array();
        //Store Student Object in array
        $a_data['o_student'] = $o_student;
        #TODO: Is Linked Parameter Required?

        $a_data['fav'] = $this->get_student_favourites($o_student->ID);

        //Reading Record
        $a_data['graph'] = $this->get_graph_data($o_student);
        //Reading Progress
        $a_data['stats'] = $this->get_reading_progress($o_student);

        return $a_data;
    }

    private function get_student_favourites( $i_student = NULL ) {
        if( ! isset($i_student) ) {
            $this->log('Error: Cannot load favourites of NULL student');

            return FALSE;
        }

        $this->log('Get Student Favourite Books');
        $c_bookmarks = new Wushka_Bookmarks($i_student, 'student');
        $a_books     = $c_bookmarks->get_book_list();

        $a_top_books = array();
        $a_book      = array();
        //Limit Favourites to Five
        $ii = 0;

        error_log('Books = ' . print_r($a_books, TRUE));

        if( isset($a_books) && ! empty($a_books) ) {
            $a_args  = array(
                'post__in'    => $a_books,
                'orderby'     => 'post__in',
                'numberposts' => 5,
                'post_type'   => 'ebook'
            );
            $a_posts = get_posts($a_args);
            error_log('Found ' . count($a_posts) . ' Book posts');
            if( isset($a_posts) && ! empty($a_posts) ) {
                foreach( $a_posts as $i_key => $o_post ) {
                    if( $i_key >= 5 ) {
                        break;
                    }

                    $s_href = get_permalink($o_post->ID);

                    $a_book[] = '<div class="top-book">';
                    $a_book[] = '<a href="' . $s_href . '" title="' . trim(ucwords($o_post->post_title)) . '">';
                    $a_book[] = '<img src="' . trim($o_post->post_image) . '" id="top-book-' . $o_post->ID . '" class="mb15" alt="' . trim(ucwords($o_post->post_title)) . '" />';
                    $a_book[] = '<input type="hidden" class="res_id" value="' . $o_post->esiss_resource_id . '"/>';
                    $a_book[] = '</a>';
                    $a_book[] = '</div>';

                    $a_top_books[] = implode('', $a_book);
                    unset($a_book);

                    $ii++;
                }
            }
        }

        if( empty($a_top_books) ) {
            $s_error = '<p>You don\'t have any favourite Readers. To make a Reader your favourite, ' .
                'open the Reader detail page and click on the favourite button</p>';

            return $s_error;
        }

        return implode('', $a_top_books);
    }

    private function get_graph_data( $o_student ) {
        if( ! isset($o_student) || empty($o_student) ) {
            $this->log('Error: Cannot Get Graph Data of NULL user');

            return FALSE;
        }

        //Get School Events For Filtering
        $this->get_school_events();
        $this->get_student_levels($o_student);

        $a_data['line'] = $this->get_line_graph($o_student);
        $a_data['bar']  = $this->get_bar_graph($o_student);
        $a_data['quiz'] = $this->get_quiz_graph($o_student);

        return $a_data;
    }

    private function get_school_events() {

        //Get School User Based on School term id of current teacher user
        $o_school = $this->get_school_user();
        //Get School State
        $s_state = wushka_get_school_caldendar_state($this->i_school);
        //Load School Calendar Events
        $this->a_events = wushka_get_calendar_events($s_state, $o_school->ID);

        return TRUE;
    }

    private function get_student_levels( $o_student ) {

        $a_levels = get_terms('reading-level', array(
            'orderby' => 'slug',
            'order'   => 'ASC'
        ));

        $this->a_all_levels = $a_levels;

        $a_users = $o_student->prepared_shelves;

        foreach( $a_levels as $idx => $o_level ) {
            if( ! isset($a_users) || empty($a_users) || in_array($o_level->slug, $a_users) ) {
                $this->a_user_levels[] = $o_level->term_id;
            }
        }

        error_log('# Reading Levels: ' . count($this->a_all_levels));
        error_log('# User Levels: ' . count($this->a_user_levels));

        return TRUE;
    }

    private function get_school_user() {
        global $current_user;

        $o_school_term = wp_get_object_terms($current_user->ID, 'school');
        $i_school      = NULL;
        if( isset($o_school_term) && ! empty($o_school_term) ) {
            $i_school = $o_school_term[0]->term_taxonomy_id;
        }

        $this->i_school = $i_school;

        //Store School User TimeZone
        $this->s_timezone = wushka_get_school_timezone($i_school);

        //Get School Term User
        $o_school = wushka_get_school_term_user($i_school);
        if( isset($o_school) && ! empty($o_school) ) {
            return $o_school;
        }

        $this->log('Error: Could Not Find School User');

        //Store School User TimeZone
        $this->s_timezone = wushka_get_school_timezone($i_school);


        return FALSE;
    }

    private function get_line_graph( $o_student = NULL ) {
        //Number Of Columns in Graph
        $i_columns = 7;
        $s_format  = 'days';

        //Get Books For The Line Graph
        $a_books = $this->get_graph_books($i_columns, $s_format, $o_student);
        //Filter Books by selected Filters
        $a_filtered = $this->filter_books($a_books);

        //Prepare Variables For Graph Data Storage
        $a_graph = array();
        $i_count = 0;

        //Get Current TimeZone
        $tz_school = new DateTimeZone($this->s_timezone);

        //Modify Number of Columns to account array 0 index
        $i_days = ($i_columns > 0) ? $i_columns - 1 : 0;

        //Group books by day, for last seven days
        for( $i_day = $i_days; $i_day >= 0; $i_day-- ) {
            $s_days = '-' . $i_day . ' days';
            $d_day  = new DateTime('NOW', $tz_school);

            $d_day->modify($s_days);
            $s_current = $d_day->format('d-m-Y');

            $s_day  = $d_day->format('D');
            $s_date = $d_day->format('jS');

            $s_label = $s_day;
            if( $i_day == 0 ) {
                $s_label = 'Today';
            }

            $a_graph[ $i_count ] = array(
                'col'    => $i_day,
                'x_axis' => $s_label,
                'day'    => $s_day,
                'date'   => $s_date,
                'count'  => 0
            );

            //Group Books By Current Day in Loop
            foreach( $a_filtered as $i_key => $o_book ) {
                $d_read = date('d-m-Y', strtotime($o_book->created));
                if( $s_current == $d_read ) {
                    $a_graph[ $i_count ]['count']++;
                }
            }

            $this->log('Day ' . $i_day . ': ' . $s_current . ' Has ' . $a_graph[ $i_count ]['count'] . ' Books');

            $i_count++;
        }

        if( $this->b_test ) {
            error_log('Line Graph Data:');
            //error_log(print_r($a_graph, TRUE));
        }

        $this->log('Line Graph Data has been Generated');

        return $a_graph;
    }

    private function get_bar_graph( $o_student = NULL ) {

        //Number Of Columns in Graph
        $i_columns = 4;
        $s_format  = 'weeks';

        //Get Books For The Line Graph
        $a_books = $this->get_graph_books($i_columns, $s_format, $o_student);
        //Filter Books by selected Filters
        $a_filtered = $this->filter_books($a_books);
        //Group Books By Day

        //Prepare Variables For Graph Data Storage
        $a_graph = array();
        $i_count = 0;

        //Get Current TimeZone (UTC)
        $tz_school = new DateTimeZone($this->s_timezone);

        //Modify Number of Columns to account array 0 index
        $i_weeks = ($i_columns > 0) ? $i_columns - 1 : 0;

        //Group books by day, for last seven days
        for( $i_week = $i_weeks; $i_week >= 0; $i_week-- ) {
            $s_weeks = '-' . $i_week . ' weeks';
            $d_week  = new DateTime('NOW', $tz_school);

            $d_week->modify($s_weeks);
            $s_current = $d_week->format('W');

            $this->log('Load Student Line Graph');
            $this->log('Current Day = ' . $s_current);

            $s_day  = $d_week->format('j/m');
            $s_date = $d_week->format('jS');

            $s_label = $s_day;
            if( $i_week == 0 ) {
                $s_label = 'This Week';
            }

            $a_graph[ $i_count ] = array(
                'col'    => $i_week,
                'x_axis' => $s_label,
                'day'    => $s_day,
                'date'   => $s_date,
                'count'  => 0
            );

            //Group Books By Current Day in Loop
            foreach( $a_filtered as $i_key => $o_book ) {
                $d_read = date('W', strtotime($o_book->created));
                if( $s_current == $d_read ) {
                    $a_graph[ $i_count ]['count']++;
                }
            }

            error_log('Week : ' . $s_current . ' Has ' . $a_graph[ $i_count ]['count'] . ' Books');

            $i_count++;
        }

        if( $this->b_test ) {
            error_log('Bar Graph Data:');
            //error_log(print_r($a_graph, TRUE));
        }

        $this->log('Bar Graph Data has been Generated');

        return $a_graph;
    }

    private function get_quiz_graph( $o_student ) {
        $a_graph = array();

        //Get All Quiz Results for this student
        $a_results = $this->get_quiz_results($o_student->ID);
        //Filter Results based on stored filter parameters
        $a_filtered = $this->filter_quiz_results($a_results);
        //Only Return a max of ten results
        if( count($a_filtered) > 10 ) {
            $a_filtered = array_slice($a_filtered, count($a_filtered) - 10, count($a_filtered));
        }

        if( ! empty($a_filtered) ) {
            foreach( $a_filtered as $i_key => $o_result ) {
                $a_row = array(
                    'id'        => NULL,
                    'correct'   => 0,
                    'incorrect' => 0,
                    'x_axis'    => NULL
                );
                //Split Score String into Correct/InCorrect
                $a_score = explode('/', $o_result->score);
                if( count($a_score) > 1 ) {
                    $i_correct          = (int)trim($a_score[0]);
                    $i_total            = (int)trim($a_score[1]);
                    $a_row['id']        = $o_result->id;
                    $a_row['correct']   = $i_correct;
                    $a_row['incorrect'] = $i_total - $i_correct;
                    $a_row['x_axis']    = '';
                }

                $a_graph[] = $a_row;
                unset($a_row);
            }
        }

        if( $this->b_test ) {
            error_log('Area Graph Data:');
            //error_log(print_r($a_graph, TRUE));
        }

        $this->log('Area Graph Data has been Generated');

        return $a_graph;
    }

    private function get_graph_books( $i_columns = 7, $s_format = 'days', $o_student = NULL ) {
        if( ! isset($o_student) ) {
            $this->log('Cannot Get Graph Books of NULL Student');

            return array();
        }

        //High End Time Filter
        $tz_school = new DateTimeZone($this->s_timezone);

        //Get Time Start Date
        $d_start = new DateTime('NOW', $tz_school);
        $d_start->modify('-' . $i_columns . ' ' . $s_format);
        $s_start = $d_start->format('Y-m-d');

        //Get Time End Date
        $d_end = new DateTime('NOW', $tz_school);
        if( $s_format == 'weeks' ) {
            $d_end->modify('next monday');
        } else {
            $d_end->modify('+1 days');
        }
        $s_end = $d_end->format('Y-m-d');

        //Get Graph Books With These Parameters
        $a_books = $this->get_books($s_start, $s_end, $o_student->ID);

        $this->log('Graph Books Retrieved');

        return $a_books;
    }

    private function get_books( $s_start = NULL, $s_end = NULL, $i_student = NULL ) {
        if( ! isset($s_start, $s_end, $i_student) ) {
            $this->log('Error: Cannot Get Graph Books with NULL parameters');

            return array();
        }

        global $wpdb;

        $a_params = [
            $s_start,
            $s_end,
            1,
            $i_student
        ];

        //Query String
        $s_table = $wpdb->prefix . 'lessonzone_reading_analytics_reading_instance';
        $s_query = 'SELECT * FROM ' . $s_table . ' WHERE ' .
            '( created >= %s AND created < %s ) ' .
            'AND completed = %d AND user_id = %d ';
        $s_query .= 'ORDER BY created ASC';

        $a_books = $wpdb->get_results(
            $wpdb->prepare($s_query, $a_params)
        );

        error_log('Found ' . count($a_books) . ' between ' . $s_start . ' and ' . $s_end);

        if( isset($a_books) && ! empty($a_books) ) {
            //Get Books that are in users set levels OR are in current reading group
            foreach( $a_books as $idx => $o_book ) {
                if( ! in_array($o_book->level, $this->a_user_levels) &&
                    ! in_array($o_book->essis_resource_id, $this->a_group)
                ) {
                    unset($a_books[ $idx ]);
                    continue;
                }
            }

            return $a_books;
        }

        $this->log('No Graph Books Found');

        return array();
    }


    private function filter_books( $a_books = array() ) {
        //If No Books Passed, return Empty array
        if( empty($a_books) ) {
            return array();
        }

        //If No Filtering is set, return full book list
        if( ! isset($this->s_hours) || $this->s_hours == 'both' ) {
            return $a_books;
        }

        //Get DateTimeZone
        $tz_utc = new DateTimeZone('UTC');

        foreach( $a_books as $i_key => $o_book ) {
            $d_time = new DateTime($o_book->created, $tz_utc);

            //Determine if current time is 'in school' OR 'at home'
            $s_hours = 'home';
            if( wushka_is_time_school_hours($d_time->format('dS M Y g:ia'), $this->a_events) ) {
                $s_hours = 'school';
            }

            if( $s_hours !== $this->s_hours ) {
                unset($a_books[ $i_key ]);
            }
        }

        return $a_books;
    }

    private function get_quiz_results( $i_user = NULL ) {
        if( ! isset($i_user) ) {
            return array();
        }

        global $wpdb;

        $s_table = $wpdb->prefix . 'plugin_slickquiz_scores';

        $a_params = array($i_user);

        $s_query = 'SELECT * FROM ' . $s_table . ' WHERE createdBy = %d';

        if ($this->s_years !== 'all') {
            $s_query .= ' AND createdDate >= %s';
            $a_params[] = date('Y-m-d G:i:s', $this->d_yearStart);
        }

        $s_query .= ' ORDER BY createdDate ASC';

        $a_results = $wpdb->get_results(
            $wpdb->prepare($s_query, $a_params)
        );

        if( isset($a_results) && ! empty($a_results) ) {
            $this->log('Found ' . count($a_results) . ' Quiz Results for User: ' . $i_user);

            return $a_results;
        }

        $this->log('No Quiz Results Were Found');

        return array();
    }

    private function filter_quiz_results( $a_results = array() ) {
        if( empty($a_results) ) {
            return array();
        }

        $this->log('Filter results by :' . $this->s_hours . ' Type');

        //If No Filtering is set, return full book list
        if( ! isset($this->s_hours) || $this->s_hours == 'both' ) {
            return $a_results;
        }

        //Get DateTimeZone
        $tz_utc    = new DateTimeZone('UTC');
        $s_tz      = wushka_get_school_timezone($this->i_school);
        $tz_school = new DateTimeZone($s_tz);


        foreach( $a_results as $i_key => $o_quiz ) {
            $d_time = new DateTime($o_quiz->createdDate, $tz_utc);
            $d_time->setTimezone($tz_school);
            //Determine if current time is 'in school' OR 'at home'
            $s_hours = 'home';
            if( wushka_is_time_school_hours($d_time->format('dS M Y g:ia'), $this->a_events) ) {
                $s_hours = 'school';
            }

            if( $s_hours !== $this->s_hours ) {
                unset($a_results[ $i_key ]);
            }
        }

        $this->log('User: ' . $this->i_student . ' Has ' . count($a_results) . ' Filtered Results');

        return $a_results;
    }

    private function get_reading_progress( $o_student ) {
        //Step 1 - Get All Reading Levels
        $o_terms  = $this->a_all_levels;
        $a_levels = $this->a_user_levels;

        //Step 2 - Get All eBooks
        $a_counters = $this->get_level_book_count();
        //Step 3 - Get Student's Completed Books
        $a_books = $this->get_read_books($o_student->ID);
        //Step 4 - Filter Books by selected Filters
        $a_filtered = $this->filter_books($a_books);

        //Overview Statistics
        $a_stats = array(
            'total'    => 0,
            'read'     => 0,
            'fiction'  => 0,
            'narrated' => 0,
            'new'      => 0
        );

        $a_progress = array();

        foreach( $o_terms as $idx => $o_level ) {
            //Flag to show if Reading Level if user has access to it
            $b_set   = in_array($o_level->term_id, $a_levels) ? TRUE : FALSE;
            $a_level = array(
                'set'        => $b_set,
                'term_id'    => $o_level->term_id,
                'name'       => $o_level->name,
                'slug'       => $o_level->slug,
                'total'      => 0,
                'read'       => 0,
                'percentage' => 0
            );

            if( ! empty($a_counters[ $o_level->term_id ]) ) {
                $a_level['total'] = $a_counters[ $o_level->term_id ];
                $a_stats['total'] += $a_counters[ $o_level->term_id ];
            }

            $a_progress[ $o_level->term_id ] = $a_level;

            unset($a_level);
        }

        if( ! empty($a_filtered) ) {
            $a_ids = array();
            $books_read = array();
            foreach( $a_filtered as $i_key => $o_book ) {
                if( ! in_array($o_book->level, $a_levels) && ! in_array($o_book->essis_resource_id, $this->a_group) ) {
                    continue;
                }

                if( (int)$o_book->completed === 1 ) {
                    $a_stats['read']++;
                    $books_read[ $o_book->level ] [ $o_book->read_id ]= $o_book->essis_resource_id;
                }
                if( (int)$o_book->fiction == 1 ) {
                    $a_stats['fiction']++;
                }
                if( (int)$o_book->narrated === 1 ) {
                    $a_stats['narrated']++;
                }
                $a_ids[] = $o_book->essis_resource_id;

                $a_progress[ $o_book->level ]['read']++;
                $a_progress[ $o_book->level ]['distinct_read'] = 0;
            }
            
            //Set reading book count for unique book only
            foreach($books_read as $books_read_key => $books_read_value){
                $distinct_book_read = array();
                foreach($books_read_value as $book_read){
                    array_push($distinct_book_read, $book_read);
                }
                $a_progress[$books_read_key]['distinct_read'] = count(array_unique($distinct_book_read));
            }

            //Get Number of ReRead Books
            $a_stats['new'] = count(array_keys(array_count_values($a_ids), 1));
        }

        foreach( $a_progress as $i_term => $a_level ) {
            $i_percent = 0;
            //Not a set level and user hasn't read any books from it, remove
            if( $a_level['read'] == 0 && ! $a_level['set'] ) {
                unset($a_progress[ $i_term ]);
                continue;
            }

            $distinct = (int) (isset($a_level['distinct_read']))? $a_level['distinct_read'] : 0;

            if( ! empty($a_level['total']) ) {
                $i_percent = round(
                    ( $distinct / (int)$a_level['total']) * 100
                );
            }
            $a_progress[ $i_term ]['percentage'] = $i_percent;
        }

        //Generate HTML for Reading Level
        $a_html['stats']    = $this->process_stats($a_stats);
        $a_html['progress'] = $a_progress;
        $a_html['levels']   = $this->create_progress_html($a_progress, $o_student);

        return $a_html;
    }

    private function get_level_book_count() {
        global $wpdb;

        $s_table = $wpdb->prefix . 'term_taxonomy';

        $s_query = 'SELECT * FROM ' . $s_table . ' WHERE taxonomy = %s';

        $a_results = $wpdb->get_results(
            $wpdb->prepare($s_query, 'reading-level')
        );

        $a_counters = array();
        
        $user_licence = '';

        if( isset($this->a_results['licence']) && $this->a_results['licence'] == 'Wushka Levelled' ){
            $user_licence = 'Levelled';
        }

        if( isset($this->a_results['licence']) && $this->a_results['licence'] == 'Wushka Decodables' ){
            $decodable_only = true;
            $user_licence = 'Decodable';
        }

        if( isset($a_results) && ! empty($a_results) ) {
            foreach( $a_results as $idx => $o_term ) {
                $count = $this->get_term_count_filtered($o_term, $user_licence);
                $a_counters[ $o_term->term_id ] = $count;
            }

            return $a_counters;
        }

        return array();
    }


    private function get_term_count_filtered($term, $user_licence = null){
        $offset_count = 0;

        if($user_licence){
            global $wpdb;
            $term_relationships = $wpdb->prefix . 'term_relationships';
            $term_taxonomy = $wpdb->prefix . 'term_taxonomy';

            $sql = 'SELECT COUNT(`object_id`) as total FROM '.$term_relationships.' WHERE `object_id` IN (SELECT `object_id` FROM '.$term_relationships.' WHERE `term_taxonomy_id` = %d) AND `term_taxonomy_id` IN (SELECT `term_taxonomy_id` FROM '.$term_taxonomy.' WHERE `taxonomy` = "phonics-phase")';

            $result = $wpdb->get_results(
                $wpdb->prepare($sql, $term->term_id)
            );

            if($result){ 
                if($user_licence == 'Levelled'){
                    $offset_count = (int) $result[0]->total;
                }

                if($user_licence == 'Decodable'){
                    $offset_count = $term->count - (int) $result[0]->total;
                } 
                
            }

        }

        $count = $term->count - $offset_count;
        return $count;
    }


    private function get_read_books( $i_student = NULL ) {
        if( ! isset($i_student) ) {
            $this->log('Cannot get read books of NULL User');

            return array();
        }

        global $wpdb;

        $a_params = array(
            1,
            $i_student
        );

        $s_table = $wpdb->prefix . 'lessonzone_reading_analytics_reading_instance';
        $s_query = 'SELECT * FROM ' . $s_table . ' WHERE completed = %d AND user_id = %d ';

        if ($this->s_years !== 'all') {
            $s_query .= ' AND created >= %s';
            $a_params[] = date('Y-m-d G:i:s', $this->d_yearStart);
        }

        $a_books = $wpdb->get_results(
            $wpdb->prepare($s_query, $a_params)
        );

        if( isset($a_books) && ! empty($a_books) ) {
            return $a_books;
        }

        return array();
    }

    private function process_stats( $a_stats = array() ) {
        $a_processed = array(
            'i_new'      => 0,
            'i_read'     => 0,
            'i_fiction'  => 0,
            'i_narrated' => 0
        );

        if( $this->b_test ) {
            error_log('Raw Overview Stats:');
            error_log(print_r($a_stats, TRUE));
        }

        //Create Percentages For Student Pie Charts

        //New Books Read Vs. Books Reread
        $i_new = 0;
        if( ! empty($a_stats['read']) ) {
            $i_new = ($a_stats['new'] / $a_stats['read']) * 100;
            $i_new = ceil($i_new);
        }

        $a_processed['i_new'] = $i_new;

        //Total Read Books Vs. Books Not Read
        $i_read = 0;
        if( ! empty($a_stats['total']) ) {
            $i_read = ($a_stats['read'] / $a_stats['total']) * 100;
            $i_read = ceil($i_read);
        }

        $a_processed['i_read'] = $i_read;

        //Fiction Books Read Vs. Non-Fiction Books Read
        $i_fiction = 0;
        if( ! empty($a_stats['read']) ) {
            $i_fiction = ($a_stats['fiction'] / $a_stats['read']) * 100;
            $i_fiction = ceil($i_fiction);
        }

        $a_processed['i_fiction'] = $i_fiction;

        //Books Read With narrated Vs. Books Not Read With narrated
        $i_narrated = 0;
        if( ! empty($a_stats['read']) ) {
            $i_narrated = ($a_stats['narrated'] / $a_stats['read']) * 100;
            $i_narrated = ceil($i_narrated);
        }

        $a_processed['i_narrated'] = $i_narrated;

        if( $this->b_test ) {
            error_log('Overview Stat Percentages:');
            error_log(print_r($a_processed, TRUE));
        }

        return $a_processed;
    }

    private function create_progress_html( $a_progress = array(), $o_student = NULL ) {
        if( ! isset($o_student) || empty($a_progress) ) {
            $this->log('Cannot create progress html with NULL parameters');
        }

        $a_html         = array();
        $a_level_html   = array();
        $a_student_html = array();

        if( ! isset($a_progress) ) {
            return NULL;
        }

        $b_child = FALSE;
        if( current_user_can('child') || current_user_can('student') || current_user_can('parent') ) {
            $b_child = TRUE;
        }

        $i_total = 0;
        $i_read  = 0;

        foreach( $a_progress as $i_term => $a_level ) {
            $a_wrap = array();

            $i_total += $a_level['total'];
            $i_read += $a_level['distinct_read'];

            $a_wrap[] = '<div class="level-wrap">';
            $a_wrap[] = '<p>' . $a_level['name'] . '</p>';
            $selected = NULL;
            if( $o_student->level !== 'overall' && $o_student->level === $a_level['slug'] ) {
                $selected = ' selected';
            }
            if( $b_child ) {
                $a_wrap[] = '<button class="btn-xs btn-default btn-graph pull-left' . $selected . '" data-id="' . $a_level['slug'] . '"><span class="glyphicon glyphicon-signal x05 va-middle"></span></button>';
            }
            $a_wrap[] = '<div class="progress ' . $a_level['slug'] . ' btn-bar" data-id="' . $a_level['slug'] . '">';
            $a_wrap[] = '<div class="progress-bar ' . $a_level['slug'] . '" role="progressbar" aria-valuenow="' . $a_level['percentage'] . '" aria-valuemin="0" aria-valuemax="100" aria-label="'. $a_level['slug'] .'" style="min-width: 2em; width:' . $a_level['percentage'] . '%">' . $a_level['percentage'] . '%</div>';
            $a_wrap[] = '</div>';
            $a_wrap[] = '</div>';

            $a_level_html[] = implode($a_wrap);
            unset($a_wrap);
        }

        if( $b_child ) {
            $i_percent = 0;
            if( $i_total !== 0 ) {
                $i_percent = round($i_read / $i_total * 100);
            }

            $s_percent = $i_percent > 0 && $i_percent < 1 ? '<1' : $i_percent;

            $a_student_html[] = '<div class="level-wrap">';
            $a_student_html[] = '<p>Overall Books Read:  ' . $i_read . ' of ' . $i_total . '</p>';
            $a_student_html[] = '<button class="btn-xs btn-default btn-graph pull-left" data-id="overall"><span class="glyphicon glyphicon-signal x05 va-middle "></span></button>';
            $a_student_html[] = '<div class="progress overall btn-bar" data-id="overall">';
            $a_student_html[] = '<div class="progress-bar overall" role="progressbar" aria-valuenow="' . $i_percent . '" aria-valuemin="0" aria-valuemax="100" aria-label="overall" style="min-width: 2em; width:' . $i_percent . '%">' . $s_percent . '%</div>';
            $a_student_html[] = '</div>';
            $a_student_html[] = '</div>';
        }

        $a_html[] = '<div class="reading-level progress-wrap">';
        $a_html[] = '<div class="levels-wrap">';
        if( ! empty($a_student_html) ) {
            $a_html[] = implode('', $a_student_html);
        }
        $a_html[] = implode('', $a_level_html);
        $a_html[] = '</div>';
        $a_html[] = '</div>';

        return implode('', $a_html);
    }

    private function get_reading_group_books( $i_group = NULL ) {
        if( ! isset($i_group) || empty($i_group) ) {
            return array();
        }
        global $wpdb;

        $a_results = array();
        $s_query   = 'SELECT rg.*, pm.meta_value as res_id FROM ' . $wpdb->prefix . 'wushka_reading_groups_books rg ' .
            'LEFT JOIN ' . $wpdb->postmeta . ' pm ON pm.meta_key = %s AND pm.post_id = rg.post_id ' .
            'WHERE rg.group_id = %d AND rg.active = %d';
        $a_results = $wpdb->get_results(
            $wpdb->prepare($s_query, 'esiss_resource_id', $i_group, 1)
        );

        $a_ids = array();

        if( isset($a_results) && ! empty($a_results) ) {
            foreach( $a_results as $idx => $o_book ) {
                $a_ids[] = (int)$o_book->res_id;
            }
        }

        return $a_ids;
    }

    private function log( $s_text = NULL ) {
        if( $this->b_test && isset($s_text) && ! empty($s_text) ) {
            error_log($s_text);
            $this->a_results['message'] = $s_text;
        }

        return TRUE;
    }

    public function get_results() {
        return $this->a_results;
    }

}

/* ----- EOF ----- */