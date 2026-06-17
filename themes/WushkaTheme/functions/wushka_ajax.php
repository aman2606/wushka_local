<?php
/**
 * Created by PhpStorm.
 * User: Jordan
 * Date: 16/03/2016
 * Time: 3:02 PM
 */
/* -----------------------------------------------
 * Wushka Ajax Functions
 * ----------------------------------------------- */

// add_action('wp_ajax_wushka_view_schools', 'wushka_view_schools_ajax');
add_action('wp_ajax_wushka_teacher_activation_email_resend', 'wushka_teacher_activation_email_resend');
add_action('wp_ajax_nopriv_wushka_user_confirmation_resend', 'wushka_resend_confirmation_email');
add_action('wp_ajax_nopriv_wushka_teacher_confirmation', 'wushka_teacher_confirmation_ajax');
add_action('wp_ajax_nopriv_wushka_user_confirmation', 'wushka_user_confirmation_ajax');
add_action('wp_ajax_nopriv_wushka_free_sample_reader', 'wushka_free_sample_reader_loading');
add_action('wp_ajax_wushka_check_student_link', 'wushka_check_student_link');
add_action('wp_ajax_wushka_load_school_years', 'wushka_load_school_years');
add_action('wp_ajax_get_class_stats', 'wushka_get_class_stats');
add_action('wp_ajax_get_student_stats', 'wushka_get_student_stats');
add_action('wp_ajax_get_student_graph_data', 'wushka_get_student_graph_data');
add_action('wp_ajax_wushka_school_save_pwd', 'wushka_ajax_school_save_pwd');
add_action('wp_ajax_ereaderAnalytics_addRecord', 'wushka_ajax_ereaderAnalytics_addRecord');
add_action('wp_ajax_ereaderAnalytics_updateDuration', 'wushka_ajax_ereaderAnalytics_updateDuration');
add_action('wp_ajax_ereaderAnalytics_updateNarrated', 'wushka_ajax_ereaderAnalytics_updateNarrated');
add_action('wp_ajax_ereaderAnalytics_updateCompleted', 'wushka_ajax_ereaderAnalytics_updateCompleted');
add_action('wp_ajax_ereaderAnalytics_updateRecord', 'wushka_ajax_ereaderAnalytics_updateRecord');

//Business Development Manager - View Schools Page
// function wushka_view_schools_ajax() {
//     include_once 'class_view-schools.php';

//     $c_view = new View_Schools();
//     if( $c_view->validate_ajax() ) {
//         $c_view->run_function();
//     }

//     $a_return = $c_view->get_result();
//     echo json_encode($a_return);
//     exit();
// }

//Resend Activation Email to Teacher User
function wushka_teacher_activation_email_resend() {
    $a_return = array('status' => 0);
    error_log('-------------RESEND TEACHER ACTIVATION EMAIL--------------');

    $i_hash = json_decode(trim(stripcslashes(filter_input(INPUT_POST, 'id_hash'))));
    if( isset($i_hash) && ! empty($i_hash) ) {
        $user = get_user_by_hash($i_hash);
        if( $user !== FALSE ) {
            ob_start();
            include get_template_directory() . "/customer-new-account.php";
            $message = ob_get_clean();
            wp_mail($user->user_email, 'Welcome to Wushka', $message, 'Content-Type: text/html; charset=UTF-8');
            $a_return['status'] = 1;
            error_log('Resending Activation Email to Teacher User ' . $user->ID);
        }
    }

    error_log('----------------------------------------------');
    echo json_encode($a_return);
    exit();
}

//Resend Email Confirmation
function wushka_resend_confirmation_email() {
    include_once 'parent-trial/class_parent-trial.php';

    $c_trial  = new Trial_Parent();
    $s_hash   = json_decode(trim(stripcslashes(filter_input(INPUT_POST, 's_var_1'))));
    $o_user   = get_user_by_hash($s_hash);
    $a_result = array('status' => 0);

    if( isset($o_user) && $o_user !== FALSE ) {
        $s_temp = $c_trial->create_code($o_user->ID);
        if( $s_temp !== FALSE ) {
            $a_result['status'] = 1;
        }
    }

    echo json_encode($a_result);
    exit();
}

//New Teacher User Confirmation Ajax
function wushka_teacher_confirmation_ajax() {
    include_once 'user_confirmation.php';

    $c_user = new User_Confirmation();
    $c_user->confirm_teacher();

    echo json_encode($c_user->_a_return);
    exit();
}

//New Parent User Confirmation Ajax
function wushka_user_confirmation_ajax() {
    include_once 'class_activate_parent.php';

    $c_user = new Activate_Parent();

    echo json_encode($c_user->a_return);
    exit();
}

//Free Sample Ereader Loading
function wushka_free_sample_reader_loading() {
    include_once 'ereader_iframe.php';

    $c_ereader     = new Ebook_Reader();
    $a_return_data = $c_ereader->ajax_return();

    echo json_encode($a_return_data);
    exit();
}

function wushka_check_student_link() {
    $s_validate = json_decode(stripcslashes(filter_input(INPUT_POST, 's_val')), TRUE);
    $s_username = json_decode(stripcslashes(filter_input(INPUT_POST, 's_name')), TRUE);
    $s_password = json_decode(stripcslashes(filter_input(INPUT_POST, 's_pwd')), TRUE);
    $a_return   = array(
        'status'  => 0,
        'message' => 'Could not validate student details',
        'data'    => NULL
    );
    error_log('validate:' . $s_validate);
    $nonce = wp_verify_nonce($s_validate, 'child_validation');
    if( $nonce ) {
        $a_return['message'] = 'Could not find user by that name. Please check you have entered the provided username correctly';
        if( username_exists($s_username) && ($o_user = get_user_by('login', $s_username)) !== FALSE ) {
            $a_return['message'] = 'Incorrect Password. Please check you have entered the provided password correctly';
            if( wp_check_password($s_password, $o_user->user_pass, $o_user->ID) ) {
                //Get School ID
                //Get Shool Year Level
                $i_class = $o_user->class;
                $o_class = wushka_get_class($i_class);
                $o_term  = get_term($o_class->school_id, 'school');

                $a_year  = explode(':', $o_class->year);
                $s_yslug = $a_year[0];
                $s_yname = $a_year[1];

                $a_return = array(
                    'status'  => 1,
                    'message' => 'Linked!',
                    'data'    => array(
                        'name'       => $o_user->user_login,
                        'first_name' => $o_user->first_name,
                        'last_name'  => $o_user->last_name,
                        's_id'       => $o_term->slug,
                        's_year'     => array(
                            'slug' => $s_yslug,
                            'name' => $s_yname
                        )
                    )
                );
            }

        }
    } else {
        error_log('nonce validation failed:' . $nonce);
    }

    echo json_encode($a_return);
    exit();
}

//Load School Years
function wushka_load_school_years() {
    $a_return_data = array(
        'status'  => 0,
        'message' => 'Validating',
        'html'    => NULL
    );

    $s_validate = json_decode(stripcslashes(filter_input(INPUT_POST, 's_var_1')), TRUE);
    $i_school   = json_decode(stripcslashes(filter_input(INPUT_POST, 's_var_2')), TRUE);

    if( wp_verify_nonce($s_validate, 'child_validation') ) {
        if( isset($i_school) && ! empty($i_school) ) {
            $a_return_data['message'] = 'Query School User';
	        // updated Feb 2019 to prevent count_total performing slow query
            $args                     = array(
                'role'      => 'school',
                'count_total' => false,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'school',
                        'terms'    => $i_school
                    )
                )
            );

            $a_users = new WP_User_Query($args);  // args updated for slow query
            if( isset($a_users) && ! empty($a_users) ) {
                $o_school                 = $a_users->results[0];
                $a_return_data['message'] = 'Load School User (' . $o_school->ID . ') Year Data';
                //All Wushka Year Types
                $a_years = get_wushka_school_years();
                //School User Year Types
                if( isset($o_school->school_years) && ! empty($o_school->school_years) ) {
                    $a_return_data['message'] = 'School User Year Data Found';
                    $a_school_years           = $o_school->school_years;
                } else {
                    $a_return_data['message'] = 'Using Default Year Data';
                    $a_school_years           = $a_years;
                }
                $a_options = array();
                foreach( $a_school_years as $idx => $a_year ) {
                    if( (int)$a_year['c'] == 1 ) {
                        $a_options[] = '<option value="' . $a_year['i'] . '">' . $a_year['v'] . '</option>';
                    }
                }
                if( ! empty($a_options) ) {
                    $a_return_data['message'] = 'Years Loaded';
                    $a_return_data['status']  = 1;
                    $a_return_data['html']    = implode('', $a_options);
                } else {
                    $a_return_data['message'] = 'No Years Found';
                }
            }

        }
    }

    echo json_encode($a_return_data);
    exit();
}

function wushka_get_class_stats() {
    include_once 'class_statistics.php';

    $c_stats = new Class_Statistics();
    if( $c_stats->validate_post_parameters() ) {
        $c_stats->generate_rows();
    }
    $a_results = $c_stats->get_results();

    echo json_encode($a_results);
    exit();
}

function wushka_get_student_stats() {
    include_once 'ajax_student-statistics.php';

    $c_stats = new Student_Statistics();
    if( $c_stats->validating_post_parameters() ) {
        $c_stats->get_student_statistics();
    }

    $a_results = $c_stats->get_results();

    echo json_encode($a_results);
    exit();
}

function wushka_get_student_graph_data() {
    include_once 'ajax_student-graph.php';

    $c_stats = new Student_Graph_Data();
    if( $c_stats->validating_post_parameters() ) {
        $c_stats->get_graph_data();
    }

    $a_results = $c_stats->get_results();

    echo json_encode($a_results);
    exit();
}

function wushka_ajax_school_save_pwd() {
    global $current_user;

    $a_result = array(
        'status'  => 0,
        'message' => ''
    );

    error_log('Attempting to Update Program Coordinator PWD');

    $i_user = $current_user->ID;
    if( ! is_user_logged_in() || ! isset($i_user) || empty($i_user) ) {
        echo json_encode($a_result);
        exit();
    }

    $s_old  = json_decode(stripcslashes(filter_input(INPUT_POST, 's_old')), TRUE);
    $s_pwd  = json_decode(stripcslashes(filter_input(INPUT_POST, 's_pwd')), TRUE);
    $s_auth = json_decode(stripcslashes(filter_input(INPUT_POST, 's_auth')), TRUE);

    if( ! isset($s_auth) || ! wp_verify_nonce($s_auth, 'adjust_school_' . $current_user->ID . '_pwd') ) {
        error_log('Error: Adjust School failed to Validate');
        $a_result['message'] = 'Error: Parameters failed to Validate';
        echo json_encode($a_result);
        exit();
    }

    if( ! isset($s_old) || empty($s_old) ) {
        error_log('Error: Missing Original');
        $a_result['message'] = 'Please enter your current password';
        echo json_encode($a_result);
        exit();
    }

    if( ! wp_check_Password($s_old, $current_user->user_pass, $current_user->ID) ) {
        error_log('Error: Invalid Current');
        $a_result['message'] = 'The current password you entered doesn\'t match';
        echo json_encode($a_result);
        exit();
    }

    if( ! isset($s_pwd) || empty($s_pwd) ) {
        error_log('Error: Missing New');
        $a_result['message'] = 'Please enter a new password';
        echo json_encode($a_result);
        exit();
    }

    //Save New Password
    wp_set_password($s_pwd, $i_user);
    wp_set_auth_cookie($i_user);

    error_log('Success: Current Updated');
    $a_result['message'] = 'Your password has been updated!';
    $a_result['status']  = 1;
    echo json_encode($a_result);
    exit();
}

/* ----- Ereader Analytics Ajax ----- */

function wushka_ajax_ereaderAnalytics_addRecord() {
    global $current_user;
    $aResult = array(
        'status'  => 0,
        'message' => ''
    );

    error_log('--- Run Ajax - Ereader Analytics - Add Record ---');

    $iUser = $current_user->ID;
    if( ! is_user_logged_in() || ! isset($iUser) || empty($iUser) ) {
        echo json_encode($aResult);
        exit();
    }

    include_once('class_EreaderAnalytics.php');
    $cAnalytics = new Ereader_Analytics();

    $iResId = stripcslashes(filter_input(INPUT_POST, 'essis_resource_id'));
    $sForm  = stripcslashes(filter_input(INPUT_POST, 'form_factor'));

    error_log('User: ' . $iUser);
    error_log('ResID: ' . $iResId);
    error_log('FormFactor: ' . $sForm);


    $xAdd = $cAnalytics->addRecord($iUser, $iResId, $sForm);
    error_log('Add Record Response: ' . print_r($xAdd, TRUE));

    $aResult = $cAnalytics->getResults();

    error_log('--- Ajax Complete ---');
    echo json_encode($aResult['data']['new']);
    exit();
}

function wushka_ajax_ereaderAnalytics_updateDuration() {
    global $current_user;
    $aResult = array(
        'status'  => 0,
        'message' => ''
    );

    error_log('--- Run Ajax - Ereader Analytics - Update Duration ---');

    $iUser = $current_user->ID;
    if( ! is_user_logged_in() || ! isset($iUser) || empty($iUser) ) {
        echo json_encode($aResult);
        exit();
    }

    include_once('class_EreaderAnalytics.php');
    $cAnalytics = new Ereader_Analytics();

    $iRecord   = stripcslashes(filter_input(INPUT_POST, 'lessonzone_read_instance_id'));
    $iDuration = stripcslashes(filter_input(INPUT_POST, 'duration'));

    $bDone = $cAnalytics->updateDuration($iRecord, $iDuration);
    error_log('Update Duration Response: ' . print_r($bDone, TRUE));

    $aResult = $cAnalytics->getResults();

    error_log('--- Ajax Complete ---');
    echo json_encode($aResult);
    exit();
}

function wushka_ajax_ereaderAnalytics_updateNarrated() {
    global $current_user;
    $aResult = array(
        'status'  => 0,
        'message' => ''
    );

    error_log('--- Run Ajax - Ereader Analytics - Update Narrated ---');

    $iUser = $current_user->ID;
    if( ! is_user_logged_in() || ! isset($iUser) || empty($iUser) ) {
        echo json_encode($aResult);
        exit();
    }

    include_once('class_EreaderAnalytics.php');
    $cAnalytics = new Ereader_Analytics();

    $iRecord = stripcslashes(filter_input(INPUT_POST, 'lessonzone_read_instance_id'));

   $iRecord = sanitize_text_field($iRecord);
    error_log('RecordId ' . $iRecord);
    $bDone = $cAnalytics->updateNarrated($iRecord);
    error_log('Update Narration Response: ' . print_r($bDone, TRUE));

    $aResult = $cAnalytics->getResults();

    error_log('--- Ajax Complete ---');
    echo json_encode($aResult);
    exit();
}

function wushka_ajax_ereaderAnalytics_updateCompleted() {
    global $current_user;
    $aResult = array(
        'status'  => 0,
        'message' => ''
    );

    error_log('--- Run Ajax - Ereader Analytics - Update Completed ---');

    $iUser = $current_user->ID;
    if( ! is_user_logged_in() || ! isset($iUser) || empty($iUser) ) {
        echo json_encode($aResult);
        exit();
    }

    include_once('class_EreaderAnalytics.php');
    $cAnalytics = new Ereader_Analytics();

    $iRecord = stripcslashes(filter_input(INPUT_POST, 'lessonzone_read_instance_id'));

    $bDone = $cAnalytics->updateCompleted($iRecord);
    error_log('Update Completed Response: ' . print_r($bDone, TRUE));

    $aResult = $cAnalytics->getResults();

    error_log('--- Ajax Complete ---');
    echo json_encode($aResult);
    exit();
}

function wushka_ajax_ereaderAnalytics_updateRecord() {
    global $current_user;
    $aResult = array(
        'status'  => 0,
        'message' => ''
    );

    error_log('--- Run Ajax - Ereader Analytics - Update Function ---');

    $iUser = $current_user->ID;
    if( ! is_user_logged_in() || ! isset($iUser) || empty($iUser) ) {
        echo json_encode($aResult);
        exit();
    }

    include_once('class_EreaderAnalytics.php');
    $cAnalytics = new Ereader_Analytics();
    error_log('Detected POST params:');
    $iRecord     = stripcslashes(filter_input(INPUT_POST, 'record_id'));
    $iPostId     = stripcslashes(filter_input(INPUT_POST, 'ebook'));
    $iResourceId = stripcslashes(filter_input(INPUT_POST, 'resource_id'));
    $sAction     = stripcslashes(filter_input(INPUT_POST, 'function'));
    $duration     = stripcslashes(filter_input(INPUT_POST, 'duration'));

    $iRecord = sanitize_text_field($iRecord);
    $iPostId = sanitize_text_field($iPostId);
    $iResourceId = sanitize_text_field($iResourceId);
    $sAction = sanitize_text_field($sAction);
    $duration = sanitize_text_field($duration);

    error_log('Posted Record Id: #' . $iRecord);
    error_log('Posted Ebook Id: #' . $iPostId);
    error_log('Posted Resource Id: #' . $iResourceId);
    error_log('Posted Update Type: ' . $sAction);
    error_log('Posted Duration Type: ' . $duration);

    error_log('Determine What method to run...');
    if( isset($sAction, $iRecord) && $sAction === 'end' ) {
        // update duration first
        if (isset($duration)) {
            $bDone = $cAnalytics->updateDuration($iRecord, $duration);
            error_log('Update END Duration Response: ' . print_r($bDone, TRUE));
        }
        //Get Current Users Configured Timezone
        $iSchool   = wushka_get_user_school($iUser);
        $sTimezone = wushka_get_school_timezone($iSchool);

        error_log('COMPLETE RECORD #' . $iRecord);
        $bDone = $cAnalytics->updateCompleted($iRecord, $sTimezone);
        error_log('Update Completed Response: ' . $bDone);
    } else if (isset($sAction, $iRecord, $duration) && $sAction === 'duration') {
        $bDone = $cAnalytics->updateDuration($iRecord, $duration);
        error_log('Update Duration Response: ' . print_r($bDone, TRUE));
    } else {
        error_log('CREATE NEW RA RECORD FOR USER #' . $iUser . ' & RESOURCE #' . $iResourceId);
        $iNewRecord = $cAnalytics->addRecord($iUser, $iResourceId);
        error_log('Update Completed Response: ' . $iNewRecord);
    }

    $aResult = $cAnalytics->getResults();

    error_log('--- Ajax Complete ---');
    echo json_encode($aResult);
    exit();
}

/* ----- END OF FILE ----- */
