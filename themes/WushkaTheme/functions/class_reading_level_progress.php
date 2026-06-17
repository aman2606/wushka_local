<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class ReadersCompleted {  
    private $d_yearStart; 
    private $a_all_levels;
    private $a_user_levels;
    private $reading_level_progress;
    private $s_years;
    
    public function __construct($user) { 
        //Get Timestamp of First Jan of Current Year
        $tCurrent = date('Y');
        $this->d_yearStart = strtotime('01 January ' . $tCurrent);
        
        $this->s_years   = NULL;
        
        $this->a_all_levels = $a_levels = get_terms('reading-level', array(
            'orderby' => 'slug',
            'order'   => 'ASC'
        )); 

        foreach( $a_levels as $idx => $o_level ) {
            if( ! isset($a_users) || empty($a_users) || in_array($o_level->slug, $a_users) ) {
                $this->a_user_levels[] = $o_level->term_id;
            }
        }         
        $this->reading_level_progress = $this->get_reading_progress($user);
 
    }  

    public function getResults(){
        return $this->reading_level_progress;
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
            foreach( $a_filtered as $i_key => $o_book ) {
                if( ! in_array($o_book->level, $a_levels) && ! in_array($o_book->essis_resource_id, $this->a_group) ) {
                    continue;
                }

                if( (int)$o_book->completed === 1 ) {
                    $a_stats['read']++;
                }
                if( (int)$o_book->fiction == 1 ) {
                    $a_stats['fiction']++;
                }
                if( (int)$o_book->narrated === 1 ) {
                    $a_stats['narrated']++;
                }
                $a_ids[] = $o_book->essis_resource_id;

                $a_progress[ $o_book->level ]['read']++;
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

            if( ! empty($a_level['total']) ) {
                $i_percent = round(
                    ((int)$a_level['read'] / (int)$a_level['total']) * 100
                );
            }
            $a_progress[ $i_term ]['percentage'] = $i_percent;
        } 
        $a_html = $a_progress;  
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

        if( isset($a_results) && ! empty($a_results) ) {
            foreach( $a_results as $idx => $o_term ) {
                $a_counters[ $o_term->term_id ] = $o_term->count;
            }

            return $a_counters;
        }

        return array();
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
}