<?php

/* ------------------------------------------------------ *
 *
 *			  --------- School Events ---------
 *
 * ------------------------------------------------------ */

class School_Events {

    private $_s_table;
    private $_a_required;
    private $_a_query_args;
    private $_s_limit_query;
    private $_a_limit_params;
    private $_s_full_query;
    private $_a_full_params;
    private $_s_timezone;

    //If Table doesn't exist, create it.
    public function __construct() {
        //If Table Does Not Exist, Create.
        $this->initialise_system();

        $this->_s_timezone = 'UTC';

        //Set Default Arguments for Querying Events
        $this->_a_query_args = array(
            'ID'           => NULL,
            'school_id'    => NULL,
            'event_type'   => NULL,
            'sub_type'     => NULL,
            'action'       => NULL,
            'date_created' => NULL,
            'order_by'     => 'ID',
            'order'        => 'ASC',
            'limit'        => 10,
            'page_no'      => 1,
            'time'         => NULL
        );

        $this->_a_required = array(
            'school_id',
            'event_type',
            'sub_type',
            'action',
            'description'
        );
    }

    public function load_stylesheets() {
        $tmp_dir         = get_template_directory_uri() . '/functions/school-events/';
        $a_stylesheets[] = '<link type="text/css" rel="stylesheet" href="' . $tmp_dir . 'css_school-events.css" />';
        $a_stylesheets[] = '<script type="text/javascript">';
        $a_stylesheets[] = 'var temp_fle_drctry = "' . $tmp_dir . '";';
        $a_stylesheets[] = '</script>';
        $a_stylesheets[] = '<script type="text/javascript" src="' . $tmp_dir . 'js_school-events.js"></script>';
        echo implode('', $a_stylesheets);
    }

    public function set_timezone($school_id) {
        $this->_s_timezone = wushka_get_school_timezone($school_id);

        return true;
    }

    /* ---------- Initialise System ---------- */
    private function initialise_system() {
        global $wpdb;

        // ----- Step 1 ----- \\
        $this->_s_table = $wpdb->prefix . 'wushka_school_events';
        //Verify Table is installed, if not setup table
        if( $this->_s_table != $wpdb->get_var("SHOW TABLES LIKE '" . $this->_s_table . "'") ) {
            //table is not created. you may create the table here.
            $structure = 'CREATE TABLE IF NOT EXISTS ' . $this->_s_table .
                '(`ID` INT(13) NOT NULL AUTO_INCREMENT, ' .
                '`school_id` INT(13) NULL, ' .
                '`event_type` VARCHAR(45) NULL, ' .
                '`sub_type` VARCHAR(45) NULL, ' .
                '`action` VARCHAR(45) NULL, ' .
                '`description` VARCHAR(120) NULL, ' .
                '`meta_value` VARCHAR(45) NULL, ' .
                '`date_created` DATETIME NOT NULL, ' .
                '`sso_login` VARCHAR(10) NULL, ' .
                'UNIQUE KEY (`ID`) );';

            $wpdb->query($structure);
        }
    }

    //Determine Appropriate Glyphicon for this event
    public function get_glyph( $o_event = NULL ) {
        if( ! isset($o_event) ) {
            return NULL;
        }

        $a_glyphs = array(
            'admin'   => array(
                'teacher' => array(
                    'created' => 'education',
                    'edited'  => 'education',
                    'deleted' => 'education',
                ),
                'class'   => array(
                    'created' => 'education',
                    'edited'  => 'education',
                    'deleted' => 'education',
                )
            ),
            'teacher' => array(
                'student' => array(
                    'created' => 'user',
                    'edited'  => 'user',
                    'deleted' => 'user'
                ),
                'teacher' => array(
                    'logged in'  => 'log-in',
                    'logged out' => 'log-out',
                ),
                'class'   => array(
                    'created' => 'education',
                    'edited'  => 'education',
                    'deleted' => 'education',
                )
            ),
            'student' => array(
                'student' => array(
                    'logged in'  => 'log-in',
                    'logged out' => 'log-out',
                )
            )
        );

        $s_glyph = 'flash';
        if( array_key_exists($o_event->action, $a_glyphs[ $o_event->event_type ][ $o_event->sub_type ]) ) {
            $s_glyph = $a_glyphs[ $o_event->event_type ][ $o_event->sub_type ][ $o_event->action ];
        }

        return 'glyphicon-' . $s_glyph;
    }

    public function format_time( $s_date = NULL ) {
        if( ! isset($s_date) ) {
            return NULL;
        }

        $t_now = new DateTime('NOW');
        $t_now->setTimezone(new DateTimeZone('UTC'));
        $now_time = strtotime($t_now->format('Y-m-d H:i:s'));

        $t_event = new DateTime($s_date, new DateTimeZone($this->_s_timezone));
        $t_event->setTimezone(new DateTimeZone('UTC'));
        $event_time = strtotime($t_event->format('Y-m-d H:i:s'));

        $time     = $now_time - $event_time;
        $s_return = NULL;

        $tokens = array(
            1        => 'second',
            60       => 'minute',
            3600     => 'hour',
            86400    => 'day',
            604800   => 'week',
            2592000  => 'month',
            31536000 => 'year'
        );

        $i_unit    = NULL;
        $s_measure = NULL;
        
        foreach( $tokens as $unit => $text ) {
            if( $time < $unit ) {
                continue;
            }
            $numberOfUnits = floor($time / $unit);
            $i_unit        = $numberOfUnits;
            $s_measure     = $text;
        }

        //Just Now Clause
        if( $s_measure == 'second' ) {
            $s_return = 'Just Now';
        } else {
            if( $i_unit > 1 ) {
                $s_measure .= 's';
            }
            $s_return = $i_unit . ' ' . $s_measure . ' ago';
        }

        return $s_return;
    }

    /* ---------- Query Events ---------- */
    public function get_events( $a_args = array() ) {
        $this->set_query_args($a_args);
        $this->prepare_query();
        //Run Full Query to Get Total Results
        $a_results['total']  = $this->run_full_query();
        $a_results['events'] = $this->run_query();

        return $a_results;
    }

    /* ---------- Save Event ---------- */
    public function save_event( $a_data = array() ) {
        if( empty($a_data) ) {
            return FALSE;
        }

        //Make Sure All necessary Fields have been passed
        if( ($b_valid = $this->validate_save_data($a_data)) === TRUE ) {
            if( ($x_event_id = $this->save_event_to_table($a_data)) === TRUE ) {
                error_log('New Event Inserted into Table');
                error_log('Event ID: ' . $x_event_id);

                return TRUE;
            } else {
                error_log('School Event Ajax Error: Insert Statement returned FALSE');
            }
        } else {
            error_log('School Event Ajax Error: Missing Required Fields Passed');

            return FALSE;
        }

        return FALSE;
    }

    private function set_query_args( $a_args = array() ) {
        if( ! empty($a_args) ) {
            foreach( $this->_a_query_args as $s_key => $x_value ) {
                if( isset($a_args[ $s_key ]) && ! empty($a_args[ $s_key ]) ) {
                    $this->_a_query_args[ $s_key ] = $a_args[ $s_key ];
                }
            }
        }

        return TRUE;
    }

    //Build Dynamic Query Based on Set Args
    private function prepare_query() {
        $s_query     = NULL;
        $a_params    = array();
        $a_condition = array();

        error_log('Query Args = ' . print_r($this->_a_query_args, TRUE));

        foreach( $this->_a_query_args as $k => $x ) {
            if( isset($x) && ! empty($x) ) {
                if( $k == 'order' || $k == 'order_by' || $k == 'limit' || $k == 'page_no' ) {
                    continue;
                } else if( $k == 'time' ) {
                    if( $x == 'all' ) {
                        continue;
                    }
                    $s_days = '-1 ' . $x;

                    //Low End Time Filter
                    $d_start = new DateTime('NOW');
                    $d_start->setTimezone(new DateTimeZone('UTC'));
                    $d_start->modify($s_days);
                    $s_start = $d_start->format('Y-m-d H:i:s');

                    //High End Time Filter
                    $d_end = new DateTime('NOW');
                    $d_end->setTimezone(new DateTimeZone('UTC'));
                    $d_end->modify('+1 day');
                    $s_end = $d_end->format('Y-m-d');

                    $a_condition[] = '( date_created >= %s AND date_created < %s )';
                    $a_params[]    = $s_start;
                    $a_params[]    = $s_end;
                } else {
                    if( is_array($x) ) {
                        $a_x2 = [];
                        foreach( $x as $k2 => $x2 ) {
                            $a_params[] = $x2;
                            $a_x2[]     = '%s';
                        }
                        $a_condition[] = $k . ' IN (' . implode(',', $a_x2) . ')';
                    } else {
                        $a_condition[] = $k . ' = %s';
                        $a_params[]    = $x;
                    }
                }
            }
        }

        $s_query = 'SELECT * FROM ' . $this->_s_table . ' ';
        if( ! empty($a_condition) ) {
            $s_query .= 'WHERE ' . implode(' AND ', $a_condition) . ' ';
        }
        $s_query .= 'ORDER BY ' . $this->_a_query_args['order_by'] . ' ' . $this->_a_query_args['order'];
        //Store FULL Query
        $this->_s_full_query  = $s_query;
        $this->_a_full_params = $a_params;

        //Calculate Page Number
        $i_page = ((int)$this->_a_query_args['page_no'] - 1) * (int)$this->_a_query_args['limit'];
        error_log('Full Query = ' . $s_query);
        error_log('Full Query Params = ' . print_r($a_params, TRUE));
        $s_query .= ' LIMIT %d, %d';
        $a_params[] = $i_page;
        $a_params[] = $this->_a_query_args['limit'];

        //Store Actual Query
        $this->_s_limit_query  = $s_query;
        $this->_a_limit_params = $a_params;

        return TRUE;
    }


    //Determine Complete Total of Query Before Limits are put in place
    private function run_full_query() {
        if( ! isset($this->_s_full_query, $this->_a_full_params) ||
            empty($this->_s_full_query) ||
            empty($this->_a_full_params)
        ) {
            error_log('School Event Query Failed: Query/Params not set');

            return FALSE;
        }

        global $wpdb;

        $a_events = $wpdb->get_results(
            $wpdb->prepare($this->_s_full_query, $this->_a_full_params)
        );

        if( ! isset($a_events) || empty($a_events) ) {
            error_log('School Event Query: No Events Found');

            return FALSE;
        }

        return count($a_events);
    }

    private function run_query() {
        if( ! isset($this->_s_limit_query, $this->_a_limit_params) ||
            empty($this->_s_limit_query) ||
            empty($this->_a_limit_params)
        ) {
            error_log('School Event Query Failed: Query/Params not set');

            return FALSE;
        }

        global $wpdb;

        $a_events = $wpdb->get_results(
            $wpdb->prepare($this->_s_limit_query, $this->_a_limit_params)
        );

        if( ! isset($a_events) || empty($a_events) ) {
            error_log('School Event Query: No Events Found');

            return FALSE;
        }

        return $a_events;
    }

    private function validate_save_data( $a_data = array() ) {
        if( empty($a_data) ) {
            return FALSE;
        }

        foreach( $this->_a_required as $s_field ) {
            if( ! isset($a_data[ $s_field ]) || empty($a_data[ $s_field ]) ) {
                return FALSE;
            }
        }

        return TRUE;
    }

    //Save Validated Event to Table
    private function save_event_to_table( $a_data = array() ) {
        global $wpdb;

        $event = $wpdb->insert(
            $this->_s_table,
            array(
                'school_id'    => $a_data['school_id'],
                'event_type'   => $a_data['event_type'],
                'sub_type'     => $a_data['sub_type'],
                'action'       => $a_data['action'],
                'description'  => $a_data['description'],
                'meta_value'   => $a_data['meta_value'],
                'date_created' => current_time('mysql'),
                'sso_login'    => $a_data['sso_login']
            ),
            array(
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );

        if( $event !== FALSE ) {
            return TRUE;
        }

        return FALSE;
    }
}