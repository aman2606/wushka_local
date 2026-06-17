<?php
// Exit if accessed directly
if( ! defined('ABSPATH') ) {
    exit();
}

//This Class Retrieves Graph Column Data For Student Statistics Line/Bar/Area Charts
class Student_Graph_Data {

    private $o_user;
    private $i_school;
    private $i_index;
    private $s_type;
    private $s_filter;
    private $a_stats;
    private $a_events;
    private $a_all_levels;
    private $a_user_levels;
    private $a_group;
    private $a_results;
    private $b_test;

    public function __construct() {

        $this->o_user   = NULL;
        $this->i_school = NULL;
        $this->i_index  = NULL;
        $this->s_type   = NULL;
        $this->s_filter = NULL;

        $this->a_stats       = array();
        $this->a_events      = array();
        $this->a_all_levels  = array();
        $this->a_user_levels = array();
        $this->a_group       = array();

        $this->a_results = array(
            'status'  => 0,
            'message' => '',
            'data'    => array()
        );

        #TODO: Set to FALSE after testing
        $this->b_test = FALSE;
    }

    public function validating_post_parameters() {
        $this->log('----- Student Graph Data -----');
        $this->log('Validating...');

        //Check User
        if( ! is_user_logged_in() ) {
            $this->log('Error: Invalid User.');

            return FALSE;
        }

        $s_validate = json_decode(stripcslashes(filter_input(INPUT_POST, 'validate')), TRUE);
        $i_hash     = json_decode(stripcslashes(filter_input(INPUT_POST, 'id_hash')), TRUE);
        $s_type     = json_decode(stripcslashes(filter_input(INPUT_POST, 'type')), TRUE);
        $i_index    = json_decode(stripcslashes(filter_input(INPUT_POST, 'index')), TRUE);
        $s_hours    = json_decode(stripcslashes(filter_input(INPUT_POST, 'hours')), TRUE);

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
        if( ! isset($i_hash) || empty($i_hash) ) {
            $this->log('Error: Invalid Student');

            return FALSE;
        }

        //Attempt to Retrieve Student User
        $o_user = get_user_by_hash($i_hash);
        if( $o_user == FALSE ) {
            $this->log('Error: Could Not Retrieve Student User');

            return FALSE;
        }

        //Validate Graph Type
        if( ! isset($s_type) || empty($s_type) ) {
            $this->log('Error: Unable to Verify Graph Type');

            return FALSE;
        }

        //Ensure Graph Type Matches Acceptable Filter Slugs
        $a_accepted = array(
            'line',
            'bar',
            'quiz'
        );

        //Does Passed Filter Match a Valid Option
        if( ! in_array($s_type, $a_accepted) ) {
            $this->log('Error: Graph Type Not Recognised');

            return FALSE;
        }

        //Validate Graph Index
        if( ! isset($i_index) ) {
            $this->log('Error: Invalid Graph Index');

            return FALSE;
        }

        //Validate Hours Filter Type
        if( ! isset($s_hours) || empty($s_hours) ) {
            $this->log('Error: Unable to Verify Selected Hours Filter');

            return FALSE;
        }

        //Ensure Graph Type Matches Acceptable Filter Slugs
        $a_accepted = array(
            'both',
            'school',
            'home'
        );

        //Does Passed Hours Filter Match a Valid Option
        if( ! in_array($s_hours, $a_accepted) ) {
            $this->log('Error: Filter Type Not Recognised');

            return FALSE;
        }

        //Validation Passed
        $this->log('Validation Success!');

        //Store Parameters
        $this->o_user   = $o_user;
        $this->s_type   = $s_type;
        $this->i_index  = $i_index;
        $this->s_filter = $s_hours;

        return TRUE;
    }

    public function get_graph_data() {
        $this->log('Student Graph Ajax Page : Run Book Content Function');
        $this->log('Prepare to Gather Books For: ');
        $this->log('Graph Type: ' . $this->s_type);
        $this->log('Graph Index: ' . $this->i_index);
        $this->log('For Student: #' . $this->o_user->ID);

        $this->a_group = $this->get_reading_group_books($this->o_user->my_reading_group);

        $s_content = $this->get_graph_content();
        if( isset($s_content) && ! empty($s_content) ) {
            $this->a_results['status'] = 1;
            $this->a_results['data']   = $s_content;
            $this->log('Success: Graph Data has Been collected');
        } else {
            $this->log('Warning: No Graph Data has been collected');
        }


        $this->log('-------------------- END STUDENT GRAPH AJAX ---------------------');

        return TRUE;
    }

    private function get_graph_content() {
        $s_content = NULL;

        switch( $this->s_type ) {
            case 'line' :
                $s_content = $this->get_book_content('line', $this->i_index);
                break;
            case 'bar' :
                $s_content = $this->get_book_content('bar', $this->i_index);
                break;
            case 'quiz' :
                $s_content = $this->get_quiz_content($this->i_index);
                break;
        }

        return $s_content;
    }

    private function get_book_content( $s_type = 'line', $i_index = 0 ) {
        $a_html = array();

        //Get School Term User
        $i_school = wushka_get_user_school($this->o_user->ID);
        $s_timezone = wushka_get_school_timezone($i_school);


        $a_timeframe = $this->calculate_timeframe($i_index, $s_type, $s_timezone);
        $a_books     = $this->get_books($a_timeframe);

        if( $s_type == 'line' ) {
            $a_books = $this->process_line_books($a_books, $a_timeframe);
        }

        $a_book_html = [];

        if( isset($a_books) ) {
            $o_school = wushka_get_school_term_user($i_school);

            //Get School State
            $s_state  = wushka_get_school_caldendar_state($i_school);
            //Load School Calendar Events
            $a_events = wushka_get_calendar_events($s_state, $o_school->ID);
            $utc      = new DateTimeZone('UTC');

            $a_reading_terms = get_terms('reading-level', array(
                'orderby' => 'slug',
                'order'   => 'ASC'
            ));

            //Get Student Levels
            $a_user_levels = $this->o_user->prepared_shelves;
            $a_terms       = array();
            foreach( $a_reading_terms as $idx => $o_level ) {
                if( in_array($o_level->slug, $a_user_levels) ) {
                    $a_terms[] = $o_level->term_id;
                }
            }

            foreach( $a_books as $i_key => $o_book ) {
                if( ! in_array($o_book->level, $a_terms) && ! in_array($o_book->post_id, $this->a_group) ) {
                    continue;
                }

                $dt     = new DateTime($o_book->created, new DateTimeZone($s_timezone));

                $s_when = 'home';
                if( wushka_is_time_school_hours($dt->format('dS M Y g:ia'), $a_events) ) {
                    $s_when = 'school';
                }

                if( $this->s_filter !== 'both' && $s_when !== $this->s_filter ) {
                    continue;
                }

                $o_term = NULL;
                foreach( $a_reading_terms as $idx => $o_level ) {
                    if( (int)$o_book->level == $o_level->term_id ) {
                        $o_term = $o_level;
                    }
                }

                $a_book_html[] = $this->get_book_html($o_book, $o_term, $s_when);
            }
        }
        
        $a_html[] = '<h3>Total Readers Found:' . count($a_book_html) . '</h3>';
        $a_html[] = '<div class="books-wrap">';
        $a_html[] = implode('', $a_book_html);
        $a_html[] = '</div>';

        return implode('', $a_html);
    }

    private function calculate_timeframe( $i_index, $s_type, $s_timezone = 'UTC' ) {
        if( ! isset($i_index, $s_type) ) {
            $this->log('Error: Cannot Calculate Timeframe with NULL parameters');

            return NULL;
        }

        $s_start = NULL;
        $s_end   = NULL;

        $dt = new DateTime('NOW', new DateTimeZone('UTC'));
        $this->log('Date Time UTC: '.$dt->format('Y-m-d'));
        $dt->setTimezone(new DateTimeZone($s_timezone));
        $this->log('Date Time Local('.$s_timezone.'): '.$dt->format('Y-m-d'));

        if( $s_type == 'line' ) {
            $dt->modify('-' . $i_index . ' days');
            $s_start = $dt->format('Y-m-d');
            $dt->modify('+1 days');
            $s_end = $dt->format('Y-m-d');
        } else if( $s_type == 'bar' ) {
            $dt->modify('next monday');
            $dt->modify('-' . $i_index . ' week');
            $s_end = $dt->format('Y-m-d');
            $dt->modify('last monday');
            $s_start = $dt->format('Y-m-d');
        }

        $a_time['start'] = $s_start;
        $a_time['end']   = $s_end;

        $this->log('Getting Books from ' . $a_time['start'] . ' until ' . $a_time['end']);

        return $a_time;
    }

    private function process_line_books( $o_books = NULL, $a_timeframe = NULL ) {
        if( ! isset($o_books, $a_timeframe, $a_timeframe['start'], $a_timeframe['end']) ) {
            error_log('Student Graph Ajax Error: Missing Parameters (process_line_books)');

            return NULL;
        }

        $a_processed = array();

        foreach( $o_books as $i_key => $o_book ) {
            $book_date = date('Y-m-d', strtotime($o_book->created));
            if( $book_date >= $a_timeframe['start'] && $book_date < $a_timeframe['end'] ) {
                $a_processed[] = $o_book;
            }
        }

        return $a_processed;
    }

    private function get_books( $a_times = array() ) {
        if( ! isset($a_times) || empty($a_times) ) {
            $this->log('Error: Cannot Get Books with NULL parameters');

            return NULL;
        }

        $s_start = $a_times['start'];
        $s_end   = $a_times['end'];
        if( ! isset($s_start, $s_end) || empty($s_start) || empty($s_end) ) {
            $this->log('Error: Cannot Get Books With Missing Prameters');
        }

        $a_results = $this->run_query($s_start, $s_end);
        $this->log('Notice: Book Query Returned ' . count($a_results) . ' results with current parameters');
        if( isset($a_results) && ! empty($a_results) ) {
            return $a_results;
        }

        return NULL;
    }

    private function run_query( $s_start = NULL, $s_end = NULL ) {
        if( ! isset($s_start, $s_end) ) {
            error_log('Student Graph Ajax Error: Missing Parameters (get_graph_book_query)');

            return NULL;
        }

        global $wpdb;
        $a_params = array();

        $a_params[] = 'esiss_resource_id';
        $a_params[] = $s_start;
        $a_params[] = $s_end;
        $a_params[] = 1;
        $a_params[] = $this->o_user->ID;
        $s_param    = '%d';

        if( $this->s_type == 'line' ) {
            $s_query = 'SELECT ra.essis_resource_id as res_id, ra.created, SUM(ra.duration) as read_time, count(ra.essis_resource_id) as book_count, pm.post_id, p.post_title, ra.level ' .
                'FROM '.$wpdb->prefix.'lessonzone_reading_analytics_reading_instance ra ' .
                'LEFT JOIN '.$wpdb->prefix.'postmeta pm ON ra.essis_resource_id = pm.meta_value AND pm.meta_key = %s ' .
                'LEFT JOIN '.$wpdb->prefix.'posts p ON pm.post_id = p.ID ' .
                'WHERE ( created >= %s AND created < %s )' .
                'AND completed = %d AND user_id IN (' . $s_param . ') ';
        } else {
            $s_query = 'SELECT ra.essis_resource_id as res_id, ra.created, SUM(ra.duration) as read_time, count(ra.essis_resource_id) as book_count, pm.post_id, p.post_title, ra.level ' .
                'FROM '.$wpdb->prefix.'lessonzone_reading_analytics_reading_instance ra ' .
                'LEFT JOIN '.$wpdb->prefix.'postmeta pm ON ra.essis_resource_id = pm.meta_value AND pm.meta_key = %s ' .
                'LEFT JOIN '.$wpdb->prefix.'posts p ON pm.post_id = p.ID ' .
                'WHERE ( created >= %s AND created < %s ) ' .
                'AND completed = %d AND user_id IN (' . $s_param . ') ';
        }

        $s_query .= 'GROUP BY ra.essis_resource_id ORDER BY ra.created ASC';

        $a_results = $wpdb->get_results(
            $wpdb->prepare($s_query, $a_params)
        );

        if( isset($a_results) && ! empty($a_results) ) {
            return $a_results;
        }

        return array();
    }

    private function get_book_html( $o_book = NULL, $o_term = NULL, $s_when ) {
        if( ! isset($o_book) ) {
            error_log('Student Graph Ajax Error: Missing Parameters (get_book_html)');

            return NULL;
        }
        //Determine Reading Level current book is from
        $s_level_slug = $o_term->slug;
        $s_level_name = $o_term->name;


        //Determine if book is fiction or non-fiction
        $s_fiction   = 'F';
        $s_fic_title = 'Fiction';
        if( has_term('non-fiction', 'fiction', $o_book->post_id) ) {
            $s_fiction   = 'NF';
            $s_fic_title = 'Non-Fiction';
        }

        $time = date('g:i a', strtotime($o_book->created));
        $date = date('jS M Y', strtotime($o_book->created));

        $s_duration = NULL;
        if( $o_book->read_time < 60 ) {
            $s_duration = $o_book->read_time . ' seconds';
        } else {
            $s_duration = round(($o_book->read_time / 60), 1);
            if( $s_duration == 1.0 ) {
                $s_duration .= ' minute';
            } else {
                $s_duration .= ' minutes';
            }
        }


        $a_html[] = '<div class="book-wrap" id="book-' . $o_book->post_id . '">';
        $a_html[] = '<p class="post-title"><label>' . $o_book->post_title . '</label></p>';
        $a_html[] = '<div class="btn-icon ' . $s_level_slug . '" title="' . $s_level_name . '"></div>';
        $a_html[] = '<div class="btn-icon fiction" title="' . $s_fic_title . '">' . $s_fiction . '</div>';
        $a_html[] = '<p><label>Read At:</label> ' . $s_when . '</p>';
        if (isset($o_book->esiss_page_count) && ! empty($o_book->esiss_page_count)) {
            $a_html[] = '<p><label>No. of pages:</label> ' .$o_book->esiss_page_count. '</p>';
        }
        $a_html[] = '<p><label>Read for:</label> ' . $s_duration . '</p>';
        $a_html[] = '<p><label>Time read:</label> ' . $time . '</p>';
        $a_html[] = '<p><label>Date read:</label> ' . $date . '</p>';
        if( $o_book->book_count > 1 ) {
            $a_html[] = '<p>This Book was Read <label>' . $o_book->book_count . '</label> times.</p>';
        }
        $a_html[] = '</div>';

        return implode('', $a_html);
    }

    private function get_reading_group_books( $i_group = NULL ) {
        if( ! isset($i_group) || empty($i_group) ) {
            return array();
        }
        global $wpdb;

        $s_query   = 'SELECT * FROM ' . $wpdb->prefix . 'wushka_reading_groups_books '.
            'WHERE group_id = %d AND active = %d';
        $a_results = $wpdb->get_results(
            $wpdb->prepare($s_query, $i_group, 1)
        );

        $a_ids = array();

        if( isset($a_results) && ! empty($a_results) ) {
            foreach( $a_results as $idx => $o_book ) {
                $a_ids[] = (int) $o_book->post_id;
            }
        }

        return $a_ids;
    }

    private function get_quiz_content( $quiz_id = NULL ) {
        if( ! isset($quiz_id) && $quiz_id == 'none' ) {
            $this->log('Error: Cannot retrieve graph data for quiz of NULL ID');

            return NULL;
        }

        global $wpdb;

        $a_html = array();

        $o_school_term = wp_get_object_terms($this->o_user->ID, 'school');
        $i_school      = NULL;
        if( isset($o_school_term) && ! empty($o_school_term) ) {
            $i_school = $o_school_term[0]->term_taxonomy_id;
        }

        //Get TimeZones For DateTime displaying
        $s_tz      = wushka_get_school_timezone($i_school);
        $tz_utc    = new DateTimeZone('UTC');
        $tz_school = new DateTimeZone($s_tz);


        $results_db_name = $wpdb->prefix . 'plugin_slickquiz_scores';
        $quiz_db_name    = $wpdb->prefix . 'plugin_slickquiz';

        $score = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT qz.id, qz.quiz_id, answers, score, qs.name, qs.publishedJson, qz.createdDate " .
                "FROM $results_db_name qz LEFT JOIN $quiz_db_name qs on qs.id = qz.quiz_id " .
                "WHERE qz.id = %d", $quiz_id
            )
        );


        $quiz    = json_decode($score->publishedJson);
        $answers = json_decode($score->answers);

        $dt = new DateTime($score->createdDate, $tz_utc);
        $dt->setTimezone($tz_school);

        $a_html[] = '<div class="table-responsive woocommerce">';
        $a_html[] = '<h3>Summary</h3>';
        $a_html[] = '<table class="table shop_table_responsive table-bordered table-hover">';
        $a_html[] = '<thead>';
        $a_html[] = '<tr>';
        $a_html[] = '<th>Date</th>';
        $a_html[] = '<th>Title</th>';
        $a_html[] = '<th>Score</th>';
        $a_html[] = '<th>Q 1</th>';
        $a_html[] = '<th>Q 2</th>';
        $a_html[] = '<th>Q 3</th>';
        $a_html[] = '<th>Q 4</th>';
        $a_html[] = '<th>Q 5</th>';
        $a_html[] = '</tr>';
        $a_html[] = '</thead>';
        $a_html[] = '<tbody>';
        $a_html[] = '<tr>';
        $a_html[] = '<td class="table-date" data-title="Date">' . $dt->format("l, dS M Y g:ia") . '</td>';
        $a_html[] = '<td class="table-title" data-title="Title">' . $score->name . '</td>';
        $a_html[] = '<td class="table-score" data-title="Score">' . $score->score . '</td>';
        $xx       = 0;
        foreach( $answers as $answer ) {
            $i_no     = $xx + 1;
            $a_html[] = '<td class="table-answer ' . $answer->valid . '"  data-title="Q ' . $i_no . '">' . $answer->valid . '</td>';
            $xx++;
        }
        while( $xx < 5 ) {
            $i_no     = $xx + 1;
            $a_html[] = '<td class="table-answer answer-na"  data-title="Q ' . $i_no . '">N/A</td>';
            $xx++;
        }
        $a_html[] = '</tr>';
        $a_html[] = '</tbody>';
        $a_html[] = '</table>';
        $a_html[] = '<h3>Details</h3>';
        $a_html[] = '<table class="table shop_table_responsive table-bordered table-hover">';
        $a_html[] = '<thead>';
        $a_html[] = '<tr>';
        $a_html[] = '<th>Number</th>';
        $a_html[] = '<th>Question</th>';
        $a_html[] = '<th>Answers</th>';
        $a_html[] = '<th>Mark</th>';
        $a_html[] = '</tr>';
        $a_html[] = '</thead>';
        $a_html[] = '<tbody>';
        foreach( $quiz->questions as $key => $question ) {
            $a_html[] = '<tr>';
            $a_html[] = '<td data-title="Number">' . ($key + 1) . '</td>';
            $a_html[] = '<td class="table-questions" data-title="Question">' . $question->q . '</td>';
            $a_html[] = '<td class="table-answers" data-title="Answers"><ol>';
            foreach( $question->a as $answer_key => $answer ) {
                $response = $answer_key == $answers[ $key ]->a ? 'true' : '';
                $correct  = (isset($answer->correct) && ! empty($answer->correct)) ? $answer->correct : NULL;
                $option   = $answer->option;
                if( $correct == 'checked' || $response == 'true' ) {
                    $option = '<span>' . $option . '</span>';
                }
                $a_html[] = '<li class="table-answer ' . $correct . $response . '">' . $option . '</li>';
            }
            $a_html[] = '</ol></td>';
            $a_html[] = '<td class="table-response ' . $answers[ $key ]->valid . '" data-title="Mark">';
            $a_html[] = $answers[ $key ]->valid;
            $a_html[] = '</td>';
            $a_html[] = '</tr>';
        }
        $a_html[] = '</tbody>';
        $a_html[] = '</table>';
        $a_html[] = '</div>';

        return implode('', $a_html);
    }

    private function log( $s_text = NULL ) {
        if( $this->b_test && isset($s_text) && ! empty($s_text) ) {
            error_log($s_text);
            $this->a_results['message'] .= $s_text . ' <br/>';
        }

        return TRUE;
    }

    public function get_results() {
        return $this->a_results;
    }
}
/* ----- EOF ----- */