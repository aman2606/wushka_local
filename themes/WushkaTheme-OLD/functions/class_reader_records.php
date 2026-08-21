<?php

/**
 * Created by PhpStorm.
 * User: Jordan
 * Date: 1/06/2016
 * Time: 5:27 PM
 */
class Reader_Records {
    private $a_results;
    private $b_testing;

    public function __construct() {
        $this->a_results = array(
            'status' => 0,
            'log'    => '',
            'data'   => []
        );

        $this->log('---- Reader Records -----');

        #TODO: Set to FALSE once testing is complete
        $this->b_testing = TRUE;
    }

    public function create_class_records( $a_data = array() ) {
        $this->log('-------- Get Records --------');
        if( ! isset($a_data) || empty($a_data) ) {
            $this->log('Error: Cannot Get Class Records of NULL Classes');

            return array();
        }

        $this->log('Get Records for ' . count($a_data['classes']) . ' Classes');
        $this->log('Retrieve Records for ' . count($a_data['students']) . ' Student Users');
        
        $a_records = array();
        if( ! empty($a_data['students']) ) {
            $a_records = $this->get_records($a_data);
        }

        $this->a_results['status']          = 0;
        $this->a_results['data']['records'] = $a_records;

        //assign class licence here 
        $this->a_results['class']['licence'] = $this->get_class_licence($a_data['classes']);

        $this->log('--- Get Records Completed ---');
        return TRUE;
    }

    private function get_class_licence($classes = null){
        if(!$classes){
            return;
        }

        $licence = array();
        foreach($classes as $class_key => $class_value){
            foreach($class_value as $class_detail){
                if(is_object($class_detail) && property_exists($class_detail,'id')){
                    $licence[$class_detail->id] = $class_detail->licence_product; 
                }
                
            }
            
        }
        return $licence;
    }

    private function record_filter($a_data){
        $classes = $a_data['classes'];
        $class_id = (int) array_shift($classes)['class']->id;
        if(isset($_SESSION['class_id']) && !empty($_SESSION['class_id'])){
            $class_id = $_SESSION['class_id'];
        }
        $data = array();
        $students = array();
        foreach($a_data as $data_key => $data_value){            
            if($data_key == 'classes'){   
                foreach($data_value as $classKey => $classValue){ 
                    if($classValue['class']->id == $class_id){ 
                        $data[$data_key][$class_id] = $classValue;
                        $students = $data[$data_key][$class_id]['students'];
                    } 
                }
            }elseif($data_key == 'students'){
                unset($data['students']);
                $data['students'] = $students;  
            }else{
                $data[$data_key] = $data_value;
            }            
        }
        return $data; 
    } 

    private function get_records( $a_data ) {
        if( empty($a_data['students']) ) {
            $this->log('Error: Cannot Retrieve Records for 0 Users');

            return array();
        }

        $a_results = $this->get_reading_analytics($a_data['students']);
        $result_count = count($a_results, COUNT_RECURSIVE );
        $this->a_results['refresh'] = false;
        if($result_count >= 50000){ 
            $a_data = $this->record_filter($a_data);
            $a_results = $this->get_reading_analytics($a_data['students']); 
            $this->a_results['refresh'] = true;
        }

        //Get School Events
        $a_events = $this->get_school_events();

        //Get Post Data for RA records
        $a_posts = $this->get_posts($a_results);

        //Get books in all classes reading groups
        $a_groups      = $this->get_groups($a_data);
        $a_group_books = $this->get_group_books($a_groups);

        //Get books in reading levels
        $a_levels = $this->get_levels();
        //$a_level_books = $this->get_level_books($a_levels);


        $this->a_results['data']['groups'] = $a_groups;
        $this->a_results['data']['levels'] = $a_levels;
        
        //Combine RA and POST data into single Records Array
        $a_records = $this->combine_records($a_data, $a_results, $a_posts, $a_events, $a_group_books, $a_levels);

        return $a_records;
    }

    private function get_reading_analytics( $a_users ) {
        global $wpdb;

        $a_reg   = [];
        $a_ids   = [];
        $a_ids[] = 1; //add 'completed' parameter

        //Add int parameter for every user id
        foreach( $a_users as $idx => $o_student ) {
            $a_ids[] = $o_student->ID;
            $a_reg[] = '%d';
        }

        //Get RA Records
        $s_table = $wpdb->prefix . 'lessonzone_reading_analytics_reading_instance';
        $s_query = 'SELECT * FROM ' . $s_table . ' WHERE ' .
            'completed = %d AND user_id IN ( ' . implode(',', $a_reg) . ') ' .
            'ORDER BY created ASC';

        $a_results = $wpdb->get_results(
            $wpdb->prepare($s_query, $a_ids)
        );

        if( ! empty($a_results) ) {
            $this->log('Found ' . count($a_results) . ' Reading Analytics Records');

            return $a_results;
        }

        $this->log('Warning: Found 0 RA Records for Current Users');

        return array();
    }

    private function get_posts( $a_results ) {
        global $wpdb;

        $a_ids = [];
        $a_reg = [];

        $a_ids[] = 'esiss_resource_id';

        //Add int parameter for every user id
        foreach( $a_results as $idx => $o_result ) {
            $a_ids[] = $o_result->essis_resource_id;
            $a_reg[] = '%d';
        }

        //Get POST Data Records
        $s_query = 'SELECT p.ID, p.post_title, pm.meta_value as res_id ' .
            'FROM ' . $wpdb->prefix . 'posts p ' .
            'LEFT JOIN ' . $wpdb->prefix . 'postmeta pm ON pm.post_id = p.ID ' .
            'WHERE pm.meta_key = %s AND pm.meta_value IN (' . implode(',', $a_reg) . ' );';


        $a_results = $wpdb->get_results(
            $wpdb->prepare($s_query, $a_ids)
        );

        if( ! empty($a_results) ) {
            $this->log('Found ' . count($a_results) . ' Post Data Records');

            return $a_results;
        }

        $this->log('Warning: Found 0 Post Data Records for Current Users');

        return array();
    }

    private function combine_records( $a_data, $a_ra = array(), $a_posts = array(), $a_events = array(), $a_groups = array(), $a_levels = array() ) {
        $a_combined = array();
        if( ! empty($a_ra) && ! empty($a_posts) ) {
            foreach( $a_ra as $i => $o_record ) {
                $a_combined[] = $this->combine_record($o_record, $a_posts, $a_events, $a_levels);
            }
        }

        $this->log('Combined ' . count($a_combined) . ' Records');

        $a_records = array();
        $i_count   = 0;
        foreach( $a_data['classes'] as $i_class => $a_class ) {
            $a_records[ $i_class ] = array();
            foreach( $a_class['students'] as $ii => $o_user ) {
                $i_user  = $o_user->ID;
                $i_hash  = $o_user->id_hash;
                $s_name  = trim(ucwords($o_user->first_name . ' ' . $o_user->last_name));
                $i_group = NULL;
                if( isset($o_user->my_reading_group) && ! empty($o_user->my_reading_group) ) {
                    $i_group = (int)$o_user->my_reading_group;
                }
                $a_records[ $i_class ][ $i_hash ] = array(
                    'user'    => array(
                        'group' => $i_group
                    ),
                    'records' => array()
                );

                foreach( $a_combined as $iii => $a_record ) {
                    if( $i_user == $a_record['user_id'] ) {
                        //Join Reading Groups Attributes to Record
                        if( isset($i_group) && ! empty($a_groups) ) {
                            if( array_key_exists($i_group, $a_groups) ) {
                                if( in_array($a_record['id'], $a_groups[ $i_group ]) ) {
                                    $a_record['group'] = $i_group;
                                }
                            }
                        }

                        $a_record['username'] = $s_name;

                        $a_records[ $i_class ][ $i_hash ]['records'][] = $a_record;
                        $i_count++;
                    }
                }
            }
        }

        $this->log('Stored ' . $i_count . ' Records into class->user->records array');

        return $a_records;
    }

    private function combine_record( $o_record, $a_posts, $a_events, $a_levels ) {

        $s_fiction  = $o_record->fiction == '1' ? 'Yes' : 'No';
        $s_narrated = $o_record->narrated == '1' ? 'Yes' : 'No';

        $s_duration = $this->calculate_duration($o_record->duration);

        $a_record = array(
            'res_id'     => $o_record->essis_resource_id,
            'user_id'    => $o_record->user_id,
            'created'    => $o_record->created,
            'duration'   => $s_duration,
            'narrated'   => $s_narrated,
            'fiction'    => $s_fiction,
            'group'      => NULL,
            'level'      => array(
                'id'   => (int)$o_record->level,
                'name' => NULL,
            ),
            'hours'      => NULL,
            'years'      => 'all',
            'id_hash'    => NULL,
            'id'         => NULL,
            'post_title' => NULL
        );

        //Join WP Post Attributes to record
        foreach( $a_posts as $ii => $o_post ) {
            if( $a_record['res_id'] == $o_post->res_id ) {
                $a_record['id']         = (int)$o_post->ID;
                $a_record['post_title'] = $o_post->post_title;
                break;
            }
        }

        //Join Reading Level Attributes to Record
        if( ! empty($a_levels) ) {
            foreach( $a_levels as $i_level => $a_level ) {
                if( (int)$a_record['level']['id'] == $i_level ) {
                    $a_record['level']['name'] = $a_level['name'];
                }
            }
        }

        //Store Record Hours Type
        if( ! empty($a_events) ) {
            $tz_utc  = new DateTimeZone('UTC');
            $dt      = new DateTime($o_record->created, $tz_utc);
            $s_hours = 'home';
            if( wushka_is_time_school_hours($dt->format("l, dS M Y g:ia"), $a_events) ) {
                $s_hours = 'school';
            }

            $a_record['hours'] = $s_hours;
        }

        //Store Record Current Year Flag
        if( ! empty($a_events) ) {
            $tz_utc  = new DateTimeZone('UTC');
            $dt      = new DateTime($o_record->created, $tz_utc);

            $iYear = date('Y');
            $dCurrent = strtotime('01 January '.$iYear);

            $s_years = 'all';
            if( $dt->getTimestamp() >= $dCurrent ) {
                $s_years = 'current';
            }

            $a_record['years'] = $s_years;
        }

        return $a_record;
    }

    private function get_groups( $a_data = NULL ) {
        if( ! isset($a_data) || empty($a_data) ) {
            return array();
        }

        global $wpdb;

        //Get Array of Class IDS
        $a_reg    = [];
        $a_params = [];

        //Add int parameter for every user id
        foreach( $a_data['classes'] as $idx => $a_class ) {
            $a_params[] = (int) $a_class['class']->id;
            $a_reg[]    = '%d';
        }

        //$a_params[] = 1; //add 'active' parameter

        $s_query = 'SELECT * FROM ' . $wpdb->prefix . 'wushka_reading_groups ' .
            'WHERE class_id IN (' . implode(',', $a_reg) . ')';

        $a_results = $wpdb->get_results(
            $wpdb->prepare($s_query, $a_params)
        );
        
        $a_groups = array();

        if( isset($a_results) && ! empty($a_results) ) {
            foreach( $a_results as $idx => $o_group ) {
                $a_groups[ $o_group->ID ] = array(
                    'id'    => (int)$o_group->ID,
                    'class' => (int)$o_group->class_id,
                    'name'  => trim($o_group->group_name)
                );
            }
        }

        return $a_groups;
    }

    private function get_group_books( $a_groups = NULL ) {
        if( ! isset($a_groups) || empty($a_groups) ) {
            return array();
        }
        global $wpdb;

        //Get Array of Class IDS
        $a_reg    = [];
        $a_params = [];

        $a_books = array();
        $this->log('Get Reading Group Books For ' . count($a_groups) . ' Groups');

        //Add int parameter for every user id
        foreach( $a_groups as $idx => $a_group ) {
            $a_reg[]                   = '%d';
            $a_params[]                = $a_group['id'];
            $a_books[ $a_group['id'] ] = array();
        }

        $a_params[] = 1; //add 'active' parameter

        $s_query   = 'SELECT * FROM ' . $wpdb->prefix . 'wushka_reading_groups_books ' .
            'WHERE group_id IN (' . implode(',', $a_reg) . ') AND active = %d';
        $a_results = $wpdb->get_results(
            $wpdb->prepare($s_query, $a_params)
        );

        $a_books = array();

        if( isset($a_results) && ! empty($a_results) ) {
            foreach( $a_results as $idx => $o_book ) {
                $a_books[ (int)$o_book->group_id ][] = $o_book->post_id;
            }
        }

        return $a_books;
    }

    private function get_levels() {
        global $wpdb;

        $s_query   = 'SELECT * FROM ' . $wpdb->prefix . 'terms WHERE term_id IN ( ' .
            'SELECT term_id FROM ' . $wpdb->prefix . 'term_taxonomy WHERE taxonomy = %s AND count != %d ' .
            ');';
        $a_results = $wpdb->get_results(
            $wpdb->prepare($s_query, 'reading-level', 0)
        );

        $a_levels = array();

        if( isset($a_results) && ! empty($a_results) ) {
            foreach( $a_results as $idx => $o_level ) {
                $a_levels[ (int)$o_level->term_id ] = array(
                    'id'   => (int)$o_level->term_id,
                    'slug' => trim($o_level->slug),
                    'name' => trim($o_level->name)
                );
            }
        }

        $this->log('Found ' . count($a_levels) . ' Reading Levels');

        return $a_levels;
    }

    private function get_school_events() {

        //Get School User Based on School term id of current teacher user
        $o_school = $this->get_school_user();
        //Get School State
        $s_state  = wushka_get_school_caldendar_state($this->i_school);

        //Load School Calendar Events
        $a_events = wushka_get_calendar_events($s_state, $o_school->ID);

        return $a_events;
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

        $this->log('Error: Could Not Find School User');

        return FALSE;
    }

    private function calculate_duration($i_duration) {
        //Format Duration Seconds to Minutes, add string describer
        $s_duration = '0';

        //Average Reading Time Per Book
        if( isset($i_duration) && ! empty($i_duration) ) {
            //Average Time per book = Total Read Time in Seconds, Divided by Total Books
            $i_average = (int) $i_duration;

            if( $i_average < 60 ) {
                //If Less than 1 minutes, display in seconds
                $s_duration = $i_average . ' seconds';
            } else {
                //Display in Minutes
                $s_duration = round(($i_average / 60), 1);
                //Singular OR Plural?
                if( $s_duration == 1.0 ) {
                    $s_duration .= ' minute';
                } else {
                    $s_duration .= ' minutes';
                }
            }
        }

        return $s_duration;
    }

    public function get_results() {
        return $this->a_results;
    }

    private function log( $s_string = NULL ) {
        if( $this->b_testing && isset($s_string) && ! empty($s_string) ) {
            error_log($s_string);
            $this->a_results['log'] .= $s_string;
            $this->a_results['log'] .= '<br/>';
        }

        return TRUE;
    }

}
