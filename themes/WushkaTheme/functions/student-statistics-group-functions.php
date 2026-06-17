<?php
if( ! defined('ABSPATH') ) {
    exit;
} // Exit if accessed directly

/* Build the Element that Displayed the Loading Screen and Error Messeges */

function get_loading_screen() {
    ?>
<div class="fixed-window-wrapper" id="loading">
    <div class="loading-wrapper">
        <div class="panel panel-info" id="loading-window">
            <div class="panel-heading">
                <label class="messages">Loading Student Details</label>
            </div>
            <div class="panel-body">
                <div class="loading-gif"></div>
                <div class="btn-wrap">
                    <a href="#" id="close-error-message-btn" class="btn btn-default">Ok</a>
                    <a href="<?= get_the_permalink(get_page_by_path('contact-us')->ID); ?>" class="btn btn-primary">
                        Contact Us
                    </a>
                    <?php 
                        /* 
                        <input id="close-error-message-btn" class="btn btn-default" type="button" value="Ok" />
                        <a href="#"><span class="sr-only">Contact Us</span><input type="button" class="btn btn-primary" value="Contact Us"/></a> 
                        */
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}

function get_popup_window() {
    ?>
<!-- Popup Window For Badges/Stories/QuizWritten -->
<div class="fixed-window-wrapper" id="popup">
    <div class="popup-wrapper">
        <div class="panel panel-default" id="popup-window">
            <div class="panel-heading">
                <h2 id="window-heading"><span class="sr-only">Statistics</span></h2>
            </div>
            <div class="panel-body">
                <div class="popup-window content-wrap" id="badge-content">
                    <?php //echo get_teacher_badges();  ?>
                </div>
                <div class="popup-window content-wrap" id="stories-content"></div>
                <div class="popup-window content-wrap" id="quiz-content"></div>
                <div class="popup-window content-wrap" id="graph-content"></div>
                <div class="popup-window close-wrap">
                    <input type="button" class="popup-window close-btn btn btn-primary" value="Close" />
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}

/* Build the Popup Window that Student Details will be displayed on. */

function get_student_detail_window( $s_page = 'class' ) {
//    $s_template = basename(get_page_template());
    error_log("page: " . $s_page);
//    if ($s_template == 'parent_statistics.php' || $s_template == 'student_my-page.php') {
//        require_once('parent_student-details.php');
//    } else {
//        require_once('teacher_student-details.php');
//    }
//    require_once('teacher_student-details.php');
    switch( $s_page ) {
        case 'student' :
            require_once('teacher_student-details.php');
            break;
        case 'child' :
            require_once('parent_student-details.php');
            break;
        case 'parent' :
            require_once('parent_student-details.php');
            break;
        case 'class' :
            require_once('teacher_student-details.php');
            break;
        case 'detailed' :
            require_once('teacher_student-details.php');
            break;
        default :
            require_once('teacher_student-details.php');

            return NULL;
            break;
    }
}

function get_student_statistics( $s_page = 'class', $user_id = 0, $i_class = NULL, $b_first = TRUE, $b_current = TRUE, $teacher_students = array()) {
    if (!isset($user_id) && !isset($i_class)) {
        error_log("Student Statistics: No User ID Found.");
        wp_redirect(home_url());
        exit;
    }

    error_log('incoming students: ' . count($teacher_students));
    switch( $s_page ) {
        case 'student' :
            //Returns WP_User_Object of Parent's Child Users
            if( ($parent_children = get_single_child($user_id)) === FALSE ) {
                error_log("Student Statistics: No Students Found");
            }
            if( ($table_overview = build_children_sidebar($parent_children)) === FALSE ) {
                error_log("Student Statistics: Overview Table Failed to Build");
                echo "<p>Table Failed to Build.</p>";

                return NULL;
            }
            break;
        case 'child' :
            //Returns WP_User_Object of Parent's Child Users
            if( ($parent_children = get_single_child($user_id)) === FALSE ) {
                error_log("Student Statistics: No Students Found");
            }
            if( ($table_overview = build_children_sidebar($parent_children)) === FALSE ) {
                error_log("Student Statistics: Overview Table Failed to Build");
                echo "<p>Table Failed to Build.</p>";

                return NULL;
            }
            break;
        case 'parent' :
            //Returns WP_User_Object of Parent's Child Users
            if( ($parent_children = get_parent_children($user_id)) === FALSE ) {
                error_log("Student Statistics: No Students Found");
            }
            if( ($table_overview = build_parent_sidebar($parent_children)) === FALSE ) {
                error_log("Student Statistics: Overview Table Failed to Build");
                echo "<p>Table Failed to Build.</p>";

                return NULL;
            }
            break;
        case 'class' :
            // only render selected class
            // $teacher_students = array();
            if ($b_first && empty($teacher_students)) {
                if( ($teacher_students = get_teacher_students($user_id, $i_class)) === FALSE ) {
                    error_log("Student Statistics: No Students Found");
                }
            }
            //Build Overview Table HTML
            if( ($table_overview = build_student_table($user_id, $i_class, $teacher_students, $b_first, $b_current)) === FALSE ) {
                error_log("Student Statistics: Overview Table Failed to Build");
                echo "<p>Table Failed to Build.</p>";

                return NULL;
            }
            break;
        case 'detailed' :
            // $teacher_students = array();
            if ($b_first && empty($teacher_students)) {
                // if( ($teacher_students = get_teacher_students($user_id, $i_class)) === FALSE ) {
                //     error_log("Student Statistics: No Students Found");
                // }
                $teacher_students = get_teacher_students($user_id, $i_class)->results;
            }
            //Build Sidebar HTML
            if( ($table_overview = build_student_sidebar($user_id, $teacher_students, $b_first)) === FALSE ) {
                error_log("Student Statistics: Overview Table Failed to Build");
                echo "<p>Table Failed to Build.</p>";

                return NULL;
            }
            break;
        default :
            return NULL;
            break;
    }
}


//Query to collect current teachers Students
function get_teacher_students( $teacher_id, $i_class = NULL ) {
    if( ! isset($i_class)) {
        return FALSE;
    }

    //Set Arguments for Student Query
	// updated Feb 2019 to prevent count_total performing slow query
    $args = array(
        'role'       => 'student',
        'count_total' => false,
        'meta_query' => array(
            'relation' => 'AND',
            0          => array(
                'key'   => 'class',
                'value' => $i_class,
            ),
            1          => array(
                'key'   => 'active',
                'value' => 1
            )
        )
    );


    //Return User Query
    //return new WP_User_Query($args);

    $user = new WP_User_Query($args);  // args updated for slow query

    return $user;

}


//Query to collect current teachers Students
function get_parent_children( $parent_id ) {
    if( ! isset($parent_id) || $parent_id == 0 ) {
        return FALSE;
    }

    //Set Arguments for Student Query
	// updated Feb 2019 to prevent count_total performing slow query
    $args = array(
        'role'       => 'student',
        'count_total' => false,
        'meta_query' => array(
            'relation' => 'AND',
            0          => array(
                'key'   => 'parent_id',
                'value' => $parent_id,
            ),
            1          => array(
                'key'   => 'active',
                'value' => 1
            )
        )
    );

    //Return User Query
    return new WP_User_Query($args);  // args updated for slow query
}

//Query to collect current teachers Students
function get_single_child( $student_id ) {
    if( ! isset($student_id) || $student_id == 0 ) {
        return FALSE;
    }
    //Set Arguments for Student Query
	// updated Feb 2019 to prevent count_total performing slow query
    $args = array(
        'include'    => $student_id,
        'role'       => 'student',
        'count_total' => false,
        'meta_query' => array(
            'relation' => 'AND',
            0          => array(
                'key'   => 'active',
                'value' => 1
            )
        )
    );

    //Return User Query
    return new WP_User_Query($args);  // args updated for slow query
}

//Build the Overview Table for Student Statistics
function build_student_table( $teacher_id = NULL, $i_class = NULL, $students = NULL, $b_fill = TRUE, $b_current = TRUE ) {
    if (!isset($teacher_id) && !isset($i_class) ) {
        return FALSE;
    }

    //Total number of Current Students
    $total = (isset($students->total_users)) ? $students->total_users : 0;

    //generate student table content
    $student_body_html = array();
    if ($b_fill) {
        $student_body_html = generate_student_table_content($teacher_id, $students->results, $b_fill, $b_current);
    }
    //Setup Initial Table
    ?>
<div class="class-view_info">
    <?php echo "<p>Total Students: $total </p>"; ?>
    <?php echo "<input type='hidden' data-id='total-student-count' value='$total' />"; ?>
</div>
<table id="table-<?php echo $i_class; ?>"
    class="student-view display table table-striped table-bordered table-condensed">
    <thead>
        <tr class="student-view-table-heading strong">
            <th class="sorting">Student</th>
            <th class="sorting">Overall Readers Completed</th>
            <th class="sorting">Average Time Per Reader</th>
            <th class="sorting">Average Readers Per Week</th>
            <th class="sorting">Average Quiz Result (%)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if( isset($student_body_html) && ! empty($student_body_html) ) {
            echo implode($student_body_html);
        }
        ?>
    </tbody>
    <tfoot>
        <tr class="student-view-table-heading strong">
            <th>Student</th>
            <th>Overall Readers Completed</th>
            <th>Average Time Per Reader</th>
            <th>Average Readers Per Week</th>
            <th>Average Quiz Result (%)</th>
        </tr>
    </tfoot>
</table>

<?php
}

function generate_student_table_content( $teacher_id = NULL, $students = NULL, $b_fill = TRUE, $b_current = TRUE ) {
    if( ! isset($students) || empty($students) ) {
        return NULL;
    }

    $a_book_data = array();

    if( $b_fill ) {
        $a_book_data = reader_analytics($students, NULL, $b_current);
    }

    $students_content = array();
    foreach( $students as $student ) {
        $student_content = '';
        if( $b_fill ) {
            $student_id          = $student->ID;
            $a_student_book_data = (array_key_exists($student_id, $a_book_data)) ? $a_book_data[ $student_id ] : array($student_id => array());

            $a_student_data = generate_student_book_data($a_student_book_data, $student, $b_current);

            $student_content .= '<tr class="student-table-row row-odd" id="student-' . $student->id_hash . '">';
            $student_content .= '<td class="student_name" data-order="' . $student->last_name . '">' . $student->first_name . ' ' . $student->last_name . '</td>';
            $student_content .= '<td class="overall_read">' . $a_student_data['book_completed'] . '</td>';
            $student_content .= '<td data-order="' . $a_student_data['duration_raw'] . '" class="avg_time_book">' . $a_student_data['duration'] . '</td>';
            $student_content .= '<td class="avg_book_week">' . $a_student_data['per_week'] . '</td>';
            $student_content .= '<td class="quiz_results">' . $a_student_data['quiz_avg'];
            $student_content .= '<input type="hidden" name="_student_wpn" class="_student_wpn" value="'. wp_create_nonce('student_details_nonce_' . $student->id_hash, '_student_wpn') .'" >';
            $student_content .= '</td>';
            //$student_content .= wp_nonce_field('student_details_nonce_' . $student->id_hash, '_student_wpn', FALSE, FALSE); //Added inside the quiz_results
            
            $student_content .= '</tr>';
        } else {
            $student_content .= '<tr class="student-table-row row-odd" id="student-' . $student->id_hash . '">';
            $student_content .= '<td class="student_name">' . $student->first_name . ' ' . $student->last_name . '</td>';
            $student_content .= '<td class="overall_read"></td>';
            $student_content .= '<td class="avg_time_book"></td>';
            $student_content .= '<td class="avg_book_week"></td>';
            $student_content .= '<td class="quiz_results">';
            $student_content .= '<input type="hidden" name="_student_wpn" class="_student_wpn" value="'. wp_create_nonce('student_details_nonce_' . $student->id_hash, '_student_wpn') .'" >';
            $student_content .= '</td>';
            //$student_content .= wp_nonce_field('student_details_nonce_' . $student->id_hash, '_student_wpn', FALSE, FALSE);  //Added inside the quiz_results
            
            $student_content .= '</tr>';
        }

        $students_content[] = $student_content;
        unset($student_content);
    }

    return $students_content;
}

function generate_student_book_data( $a_book_data = NULL, $o_student = NULL, $b_current = TRUE ) {
    $quiz_results = get_student_quiz_results($o_student, $b_current);

    $a_student_data = array(
        'book_completed'    => 0,
        'book_count'        => 0,
        'new_books'         => 0,
        'reread_books'      => 0,
        'fiction'           => 0,
        'narrated'          => 0,
        'favourite_book'    => 0,
        'duration'          => 0,
        'duration_raw'      => 0,
        'per_week'          => 0,
        'student_rating'    => 0,
        'difficulty_rating' => 0,
        'stories'           => 0,
        'quiz_total'        => $quiz_results['total'],
        'quiz_avg'          => $quiz_results['average']
    );

    //Components Not Available:
    // --- student_rating
    // --- difficulty_rating
    // --- stories

    if( isset($a_book_data) && ! empty($a_book_data) ) {
        //Number of books Finished
        if( isset($a_book_data['overall_books']) ) {
            $a_student_data['book_completed'] = $a_book_data['overall_books'];
        }
        //# of Books that are Fiction
        if( isset($a_book_data['fiction']) ) {
            $a_student_data['fiction'] = $a_book_data['fiction'];
        }
        //# of books that are narrated
        if( isset($a_book_data['narrated']) ) {
            $a_student_data['narrated'] = $a_book_data['narrated'];
        }
        //Average Reading Time Per Book
        if( isset($a_book_data['duration'], $a_book_data['overall_books']) ) {
            $i_avg_duration = 0;
            if( ! empty($a_book_data['overall_books']) ) {
                $i_avg_duration = round($a_book_data['duration'] / $a_book_data['overall_books']);
            }

            //Pass raw avg number to allow for accurate datatables sorting of processed duration
            $a_student_data['duration_raw'] = $i_avg_duration;

            if( $i_avg_duration < 60 ) {
                $a_student_data['duration'] = $i_avg_duration . ' seconds';
            } else {
                $a_student_data['duration'] = round(($i_avg_duration / 60), 1);
                if( $a_student_data['duration'] == 1.0 ) {
                    $a_student_data['duration'] .= ' minute';
                } else {
                    $a_student_data['duration'] .= ' minutes';
                }
            }
        }

        //Average Books Read per week
        if( isset($a_book_data['weeks']) && is_array($a_book_data['weeks']) ) {
            $a_student_data['per_week'] = calculate_books_per_week($a_book_data['weeks']);
        }

        //Calculate Rereads
        if( isset($a_book_data['book_ids']) && is_array($a_book_data['book_ids']) ) {
            //Get Number of New books and reread books
            $duplicate_books = array_count_values($a_book_data['book_ids']);
//            error_log('book count:' . print_r($duplicate_books, true));
            $book_count = count($duplicate_books);
//            error_log('unique books:' . print_r($book_count, true));
            $new_books    = 0;
            $reread_books = 0;
            if( ! empty($duplicate_books) ) {
                foreach( $duplicate_books as $book_id => $read_count ) {
                    if( $read_count == 1 ) {
                        $new_books++;
                    } else if( $read_count > 1 ) {
                        $reread_books++;
                    }
                }
            }

            //Get Most Read Book
            $highest_count                    = ! empty($duplicate_books) ? max($duplicate_books) : 0;
            $a_student_data['favourite_book'] = array_search($highest_count, $duplicate_books);
            $a_student_data['new_books']      = $new_books;
            $a_student_data['reread_books']   = $reread_books;
            $a_student_data['book_count']     = $book_count;
        }
    }

    return $a_student_data;
}

function calculate_books_per_week( $weeks = NULL ) {
    if( ! isset($weeks) ) {
        return NULL;
    }

    $books_per_week = 0;

    // $weeks Array contains the week number for each books read.
    // 1. Tally How many Books were read in each Week
    // 2. Calculate Average of Books per week
    $week_tally = [];
    foreach( $weeks as $week ) {
        //Step 1.
        if( ! array_key_exists($week, $week_tally) ) {
            $week_tally[ $week ] = 1;
        } else {
            $week_tally[ $week ]++;
        }
    }

    //Step 2.
    if( count($week_tally) > 0 ) {
        $books_per_week = round(array_sum($week_tally) / count($week_tally));
    }

    return $books_per_week;
}

function get_teacher_badges() {
    error_log('------------------- Loading Teacher Badges -------------------');
    //Returns Array of All available Badges HTML Items
    if( ($teacher_badges = generate_badges_html()) !== FALSE ) {
        //Load Badges into groups of 8 for pagination
        if( ($content_wrap = format_badge_window_content($teacher_badges)) === FALSE ) {
            $content_wrap = 'No Formatted Badges Found.';
        }
    } else {
        error_log('Loading Teacher Badges: No result From Badge DB query');
        $content_wrap = 'No Badges Found In DB.';
    }

    error_log('------------------- Teacher Badges Loaded -------------------');

    return $content_wrap;
}

/*
 * Generate Badges HTML
 *
 * Queries All available Badges(Achievements) And returns an Array of HTML badge items
 *
 * return $badges (array) or (FALSE)
 */

function generate_badges_html() {
    $all_badges     = array();
    $teacher_badges = array();

    include_once(ABSPATH . 'wp-admin/includes/plugin.php');

    // check for plugin using plugin name
    if( is_plugin_active('badgeos/badgeos.php') ) {
        $teacher_badges = badgeos_get_achievements(array(
            'post_type' => array(
                'badges',
                'community-badge'
            )
        ));
    }
    if( (is_array($teacher_badges) && ! empty($teacher_badges)) ) {
        foreach( $teacher_badges as $badge ) {
            $badge_html = '<div class="badge-wrap" id="badge-' . $badge->ID . '">';
            $badge_html .= '<div class="badge-thumbnail-wrap">';
            $badge_html .= badgeos_get_achievement_post_thumbnail($badge->ID, array(
                100,
                100
            ), 'badge-thumbnail-img');
            $badge_html .= '</div>';
            $badge_html .= '<h3>' . $badge->post_title . '</h3>';
            $badge_html .= '<div class="badge-earn-date-wrap"><em>Earned!<span id="badge-date">' . $badge->earn_date . '</span></em></div>';
            $badge_html .= '<div class="badge-excerpt-wrap"><em>' . $badge->post_excerpt . '</em></div>';
            $badge_html .= '<div class="badge-earn-text-wrap">' . $badge->award_text . '</div>';
            $badge_html .= '</div>';

            $all_badges[] = $badge_html;
        }

        return $all_badges;
    } else {
        error_log('Loading Teacher Badges: Achievement query did not return any results.');

        return array();
    }
}

/*
 * Generate Formatted Badge HTML
 * Format the list of badges to allow for pagination between groups of badges,
 * And then add the padgination HTML buttons to the returned data
 *
 * params $teacher_badges (array) - Array of Badge HTML Items
 *
 * return $formatted_html (string) - HTML Output for Paginated Badges *
 */

function format_badge_window_content( $teacher_badges = FALSE ) {

    if( $teacher_badges === FALSE ) {
        error_log('Loading Teacher Badges: Badge Array not passed to format function');

        return FALSE;
    }

    $group_html  = '';
    $badge_count = 1;

    error_log('--------- Formatting Teacher Badges ---------');
    //error_log('Initial Badges: '.print_r($teacher_badges, true));
    //Split the Teacher Badges into groups of 8
    $grouped_badges = array_chunk($teacher_badges, 8);
    //error_log('Grouped Badges: '.print_r($grouped_badges, true));

    $total_groups = count($grouped_badges);

    $all_groups[] = '<div class="badge-pagination-wrap">';
    foreach( $grouped_badges as $group_no => $group ) {
        $current    = ($group_no == 0) ? 'current' : NULL;
        $group_html = '<div class="badge-pagination ' . $current . '" id="badge-group-' . $group_no . '">';
        $group_html .= implode('', $group);
        $group_html .= '</div>';

        $all_groups[] = $group_html;
        unset($group_html);
    }
    $all_groups[] = '</div><!--End Pagination Wrap-->';
    //Add Pagination/Transition Buttons
    $all_groups[] = generate_badges_pagination($total_groups);
    //Add Student Overall Data for Badges
    $all_groups[] = generate_badges_footer();
    //Add Floating Window for Display Specific Badge Info
    $all_groups[] = generate_single_badge_display();

    $content_html = implode('', $all_groups);

    error_log('--------- END Formatting ---------');

    return $content_html;
}

//Returns the HTML for Pagination Buttons of Student Badges Window
function generate_badges_pagination( $page_total = 1 ) {
    $buttons = '<div class="badge-buttons-wrap">';
    $class   = ($page_total == 1) ? 'end' : NULL;
    $buttons .= '<div class="buttons-content-wrap">';
    //Down Button
    $buttons .= '<div class="badge-button down end btn btn-default">Previous</div>';
    //Window Numbers

    $buttons .= '<div class="badge-numbers"><span id="current">1</span>/<span id="total">' . $page_total . '</span></div>';
    //Up Button
    $buttons .= '<div class="badge-button up btn btn-default ' . $class . '">Next</div>';
    $buttons .= '</div>';
    $buttons .= '</div>';

    return $buttons;
}

function generate_badges_footer() {
    $footer_html = '<div class="badge-window-footer">';
    $footer_html .= '<div class="badge-points"><span class="strong">Total Points: </span><span>0</span></div>';
    $footer_html .= '<div class="last-badge"><span class="strong">Last Badge Awarded: </span><span></span></div>';
    $footer_html .= '<div class="complete-total"><span class="strong">Badges Completed: </span><span>0</span><span>%</span></div>';
    $footer_html .= '</div>';

    return $footer_html;
}

function generate_single_badge_display() {
    $badge_display = '<div class="badge-display-window">';
    $badge_display .= '<div class="badge-detail-content">';
    $badge_display .= '<div class="badge-thumbnail-wrap"></div>';
    $badge_display .= '<h3></h3>';
    $badge_display .= '<div class="badge-excerpt-wrap">To earn this badge you will need to: be awesome!</div>';
    $badge_display .= '<div class="badge-progress-wrap">Display the student\'s progress here, or any steps they need to achieve to unlock this badge.</div>';
    $badge_display .= '<div class="badge-earn-date-wrap"><em>Date Earned: <span id="badge-date">19/05/2014</span></em></div>';
    $badge_display .= '<div class="badge-earn-text-wrap">Congratulations! You have recieved this badge for being totally excellent!</div>';
    $badge_display .= '</div>';
    $badge_display .= '<div class="popup-window close-wrap">';
    $badge_display .= '<input type="button" class="badge-window close-btn" value="Close" />';
    $badge_display .= '</div>';
    $badge_display .= '</div>';

    return $badge_display;
}

function get_single_student_details( $o_user = NULL ) {
    if( ! isset($o_user) ) {
        echo '<p>No Student Data Found</p>';

        return FALSE;
    }
    $a_users[] = $o_user;
    error_log('retrieve reading analytics of user');
    $student_book_data = generate_student_table_content(0, $a_users);

    echo '<div id="single-student-data">';
    echo '<table><tbody>';
    foreach( $student_book_data as $book_data ) {
        echo $book_data;
    }
    echo '</tbody></table>';
    echo '</div>';

    return TRUE;
}

function get_student_quiz_results( $o_student = NULL, $b_current = TRUE ) {
    if( ! isset($o_student) ) {
        return NULL;
    }

    global $wpdb;

    $a_results = array(
        'total'   => 0,
        'average' => 0
    );

    $a_params[] = $o_student->ID;
    $s_param    = '%d';
    //Check for Child Link
    if( isset($o_student->child_link_id) && ! empty($o_student->child_link_id) ) {
        $a_params[] = $o_student->child_link_id;
        $s_param .= ', %d';
    }
    //Check for Student Link
    if( isset($o_student->student_link_id) && ! empty($o_student->student_link_id) ) {
        $a_params[] = $o_student->student_link_id;
        $s_param .= ', %d';
    }

    $tCurrent    = date('Y');
    $d_yearStart = date('Y-m-d G:i:s', strtotime('01 January ' . $tCurrent));

    $s_query = 'SELECT id, score, quiz_id, createdBy FROM '.$wpdb->prefix.'plugin_slickquiz_scores WHERE createdBy IN(' . $s_param . ')';

    if( $b_current ) {
        $s_query .= ' AND createdDate >= %s';
        $a_params[] = $d_yearStart;
    }

    $o_quiz_results = $wpdb->get_results(
        $wpdb->prepare($s_query, $a_params)
    );

    if( ! empty($o_quiz_results) && $o_quiz_results !== NULL ) {
        $a_results['total'] = count($o_quiz_results);

        $i_total_score     = 0;
        $i_total_questions = 0;
        $i_average         = 0;
        $a_average         = array();

        foreach( $o_quiz_results as $i_key => $o_result ) {
            $a_score = explode('/', $o_result->score);
            $i_score = 0;
            $i_total = 0;
            if( count($a_score) == 2 ) {
                $i_score     = (int)trim($a_score[0]);
                $i_total     = (int)trim($a_score[1]);
                $a_average[] = $i_score / $i_total * 100;
            }
        }

        $i_average = array_sum($a_average) / count($a_average);

        $a_results['average'] = round($i_average);
    }

    return $a_results;
}

function build_student_sidebar( $teacher_id = NULL, $students = NULL, $b_fill = TRUE ) {
    // if( ! isset($teacher_id) ) {
    //     return FALSE;
    // }

    if ($b_fill) {
        $a_students = generate_sidebar_content($students);
    }

    $a_sidebar[] = '<div class="panel panel-default">';
    $a_sidebar[] = '<div class="panel-heading"><i class="glyphicon glyphicon-user"></i>Students</div>';
    $a_sidebar[] = '<div class="panel-body">';
    $a_sidebar[] = '<div class="list-group student-list">';
    if( isset($a_students) && ! empty($a_students) ) {
        $a_sidebar[] = implode('', $a_students);
    } else {
        $a_sidebar[] = '<p>No Active Students in this Class</p>';
    }
    $a_sidebar[] = '</div>';
    $a_sidebar[] = '</div>';
    $a_sidebar[] = '</div><!--END PANEL-->';

    echo implode('', $a_sidebar);
}

function build_children_sidebar( $o_children = NULL ) {
    if( ! isset($o_children) ) {
        return FALSE;
    }

    $a_children = generate_sidebar_content($o_children->results);

    $a_sidebar[] = '<div class="panel panel-default">';
    $a_sidebar[] = '<div class="panel-heading"><i class="glyphicon glyphicon-user"></i>Children</div>';
    $a_sidebar[] = '<div class="panel-body">';
    $a_sidebar[] = '<div class="list-group student-list">';
    if( isset($a_children) && ! empty($a_children) ) {
        $a_sidebar[] = implode('', $a_children);
    } else {
        $a_sidebar[] = '<p>No Active Students in this Class</p>';
    }
    $a_sidebar[] = '</div>';
    $a_sidebar[] = '</div>';
    $a_sidebar[] = '</div><!--END PANEL-->';

    echo implode('', $a_sidebar);
}

function build_parent_sidebar( $o_children = NULL ) {
    if( ! isset($o_children) ) {
        return FALSE;
    }

    $a_children = generate_sidebar_content($o_children->results);

    $a_sidebar[] = '<div class="panel panel-default">';
    $a_sidebar[] = '<div class="panel-heading"><i class="glyphicon glyphicon-user"></i>Children</div>';
    $a_sidebar[] = '<div class="panel-body">';
    $a_sidebar[] = '<div class="list-group list-group-horizontal student-list">';
    if( isset($a_children) && ! empty($a_children) ) {
        $a_sidebar[] = implode('', $a_children);
    } else {
        $a_sidebar[] = '<p>No Active Students in this Class</p>';
    }
    $a_sidebar[] = '</div>';
    $a_sidebar[] = '</div>';
    $a_sidebar[] = '</div><!--END PANEL-->';

    echo implode('', $a_sidebar);
}

function generate_sidebar_content( $a_class = NULL ) {
    if( ! isset($a_class) ) {
        return NULL;
    }
    if( ! isset($_SESSION) ) {
        session_start();
    }

    error_log('Creating SideBar');

    error_log('Class: ' . count($a_class) . ' Students');
    //$a_book_data = reader_analytics($a_class);

    //error_log('book data:' . print_r($a_book_data, true));

    $a_students = array();

    /* Sorting By Last Name */
    $sort_last_name = usort($a_class, "cmp");

    foreach( $a_class as $i_key => $o_student ) {


        $student_id = $o_student->ID;
        //$a_student_book_data = (array_key_exists($student_id, $a_book_data)) ? $a_book_data[ $student_id ] : array($student_id => array());
        //$a_student_data      = generate_student_book_data($a_student_book_data, $o_student);
        $s_status = NULL;
        if( isset($_SESSION['class_student']) ) {
            if( $_SESSION['class_student'] == $o_student->id_hash ) {
                $s_status = 'active';
                error_log('ACTIVE SESSION STUDENT: ' . $o_student->first_name . ' ' . $o_student->last_name);
            }
        } else if( $i_key == 0 ) {
            $s_status = 'active';
        }

        if(!empty($o_student->first_name) || !empty($o_student->last_name)){
            $a_student[] = '<a href="#" class="list-group-item list-student ' . $s_status . '" data-id="' . $o_student->id_hash . '">';
            $a_student[] = '<div class="student_details">';
            //Add Hidden Student Data
            //$a_student[] = '<input type="hidden" id="books_completed" value="' . $a_student_data['book_completed'] . '" />';
            //$a_student[] = '<input type="hidden" id="books_fiction" value="' . $a_student_data['fiction'] . '" />';
            //$a_student[] = '<input type="hidden" id="books_unique" value="' . $a_student_data['book_count'] . '" />';
            //$a_student[] = '<input type="hidden" id="books_new" value="' . $a_student_data['new_books'] . '" />';
            //$a_student[] = '<input type="hidden" id="books_reread" value="' . $a_student_data['reread_books'] . '" />';
            //$a_student[] = '<input type="hidden" id="books_narrated" value="' . $a_student_data['narrated'] . '" />';
            //$a_student[] = '<input type="hidden" id="books_favourite" value="' . $a_student_data['favourite_book'] . '" />';
            //Add View Button and Security Nonce for Student
    //        $a_student[] = '<input class="btn btn-default view-details-btn" type="button" value="' . $o_student->first_name . ' ' . $o_student->last_name . '" />';
        //$a_student[] = wp_nonce_field('student_details_nonce_' . $o_student->id_hash, '_student_wpn', FALSE, FALSE);
            $a_student[] = '<input type="hidden" name="_student_wpn" class="_student_wpn" value="'. wp_create_nonce('student_details_nonce_' . $o_student->id_hash, '_student_wpn') .'" >';
            $a_student[] = '</div>';
            $a_student[] = $o_student->first_name . ' ' . $o_student->last_name;
            $a_student[] = '</a>';
        }

        $a_students[] = implode('', $a_student);
        unset($a_student);
    }

    //error_log('student data:' . print_r($a_students, true));
    return $a_students;
}


//Sorting Last Name

function cmp( $a, $b ) {
    if( ! isset($a) || ! isset($b) ) {
        error_log('Error: Cannot sort Undefined User');

        return FALSE;
    }

    if( ! is_object($a) || ! is_object($b) ) {
        error_log('Error: Cannot Sort Non-User Object');
        error_log($a);
        error_log($b);

        return FALSE;
    }

    if( $cmp = strnatcasecmp($a->last_name, $b->last_name) ) {
        return $cmp;
    }

    return strnatcasecmp($a->first_name, $b->first_name);
}

/* ----- END OF FILE: Student Statistics Group Functions ----- */