<?php
/*
 * Update students related information into database
 */
include $_SERVER['DOCUMENT_ROOT'] . '/wushka_local/wp-config.php';
/* Update user meta data */

if( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['meta']) ) {
    global $wpdb;

    $id         = json_decode(stripcslashes(trim(filter_input(INPUT_POST, 'id'))), TRUE);
    $meta_key   = json_decode(stripcslashes(trim(filter_input(INPUT_POST, 'meta'))), TRUE);
    $meta_value = json_decode(stripcslashes(trim(filter_input(INPUT_POST, 'value'))), TRUE);

    $class_id = null;
    if(isset($_POST['class_id'])){
        $class_id = json_decode(stripcslashes(trim(filter_input(INPUT_POST, 'class_id'))), TRUE);
    }
    

    error_log('--------------Edit User Data ---------------');
    error_log('ID: ' . $id);
    error_log('Meta Key: ' . $meta_key);
    error_log('Meta Value: ' . $meta_value);
    error_log('--------------------------------------------');

    $user_id = NULL;
    $o_user = get_user_by_hash($id);
    if ( $o_user ) {
        $user_id = $o_user->ID;
    }


    /**
     * Fires before user is updated.
     *
     * @param int       $id              USER id.
     * @param string    $meta_key        Meta Key.
     * @param string    $meta_value      Meta Value.
     */
    do_action( 'wushka_edit_user_action', $id, $meta_key, $meta_value );

    switch( $meta_key ) {
        case 'className':
            wushka_edit_user_class($id, $meta_value);
            echo json_encode($meta_value);
            break;
        case 'archiveAll':
            wushka_set_whole_class_archive($id, $meta_value);
            break;
        case 'active' :
            wushka_set_student_archive($id, $meta_value);
            break;
        case 'classPass':
            wushka_set_whole_class_password($id, $meta_value);
            break;
        case 'allquiz':
            wushka_update_class_meta($id, 'quizzes', $meta_value);
            break;
        case 'allnarration':
            wushka_update_class_meta($id, 'narration', $meta_value);
            break;
        case 'all_quiz_narration':
            $q_narration = wushka_update_class_meta($id, 'quiz_narration', $meta_value);
            echo json_encode($q_narration);
            break;
        case 'all_quiz_results':
                $q_results = wushka_update_class_meta($id, 'quiz_detail_results', $meta_value);
                echo json_encode($q_results);
                break;
        case 'all_book_read':
            $q_results = wushka_update_class_meta($id, 'allow_book_view', $meta_value);
            echo json_encode($q_results);
            break;
        case 'all_level':
            wushka_set_whole_class_level($id, $meta_value, $class_id);
            break;
        case 'all_shelves':
            wushka_set_whole_class_allowed_levels($id, $meta_value);
            break;
        case 'allowed_shelves':
            wushka_set_student_allowed_levels($id, $meta_value);
            break;
        case 'reading_level':
            $s_level = wushka_set_student_level($id, $meta_value, $class_id);
            echo json_encode(array('slug' => $s_level));
            break;
        case 'my_reading_group':
            $b_return = wushka_set_reading_group($id, $meta_value);
            echo json_encode($b_return);
            break;
        case 'multiple_rg': 
            foreach($id as $user_id){  
                $i_group = wushka_set_reading_group($user_id, $meta_value);
            }
            echo json_encode($i_group); 
            break;
        case 'rg_setting':
            $i_group = wushka_set_reading_group_permission($id, $meta_value);
            echo json_encode($i_group);
            break;
        case 'all_setting':
            $i_group = wushka_update_class_meta($id, 'rg_setting', $meta_value);
            echo json_encode($i_group);
            break;
        case 'new_rg':
            $i_group = wushka_set_new_reading_group($id, $meta_value);
            echo json_encode($i_group);
            break;
        case 'form':
            $a_return = wushka_update_student_form($id);
            echo json_encode($a_return);
            break;
        case 'update-form':
            $a_return = wushka_update_student_form($id);
            echo json_encode($a_return);
            break;
        case 'sign-in-first-time-form':
            $a_return = wushka_student_first_login($id);
            echo json_encode($a_return);
            break;
        case 'first_name' :
            $a_args = array(
                'ID'           => $user_id,
                'display_name' => $meta_value,
                'first_name'   => $meta_value
            );
            wp_update_user($a_args);
            echo json_encode($meta_value);
            break;
        case 'last_name' :
            $a_args = array(
                'ID'        => $user_id,
                'last_name' => $meta_value
            );
            wp_update_user($a_args);
            echo json_encode($meta_value);
            break;
        case 'user_pass' :
            wushka_set_student_pwd($id, $meta_value);
            echo json_encode($meta_value);
            break;
        case 'email':
            $validate = student_email_validation($meta_value);
            if($validate['type'] == 'success'){ 
                $a_args = array(
                    'ID'        => $user_id,
                    'user_email' => $meta_value
                );
                wp_update_user($a_args);                
            }
            echo json_encode($validate, 400);
            break;
        default:
            wushka_update_user_meta($id, $meta_key, $meta_value);
            echo json_encode($meta_key . ' ' . $meta_value);
            break;
    }

    exit();
}

/** Wushka Update User Meta
 * ----------------------------------------------------------
 * Updates a Single Meta Field of a student.
 * Uses hash id to find chosen user
 * ----------------------------------------------------------
 *
 * @param $i_hash  - Student Hash ID
 * @param $s_key   - Meta Field Key
 * @param $x_value - Meta Field Value
 *
 * @return bool
 */
function wushka_update_user_meta( $i_hash, $s_key, $x_value ) {
    $o_user = get_user_by_hash($i_hash);
    if( isset($o_user) ) {
        update_user_meta($o_user->ID, $s_key, $x_value);
    }

    return TRUE;
}

/** Wushka Update Class Meta
 * ----------------------------------------------------------
 * Updates a Single Meta Field of every student
 * in a chosen class.
 * ----------------------------------------------------------
 *
 * @param $i_class - Student Hash ID
 * @param $s_key   - Meta Field Key
 * @param $x_value - Meta Field Value
 *
 * @return bool
 */
function wushka_update_class_meta( $i_class, $s_key, $x_value ) {
    $a_students = wushka_get_students($i_class, 'class', 1);
    if( ! empty($a_students) ) {
        foreach( $a_students as $idx => $o_user ) {
            update_user_meta($o_user->ID, $s_key, $x_value);
        }
    }

    return TRUE;
}


/** Wushka Set Student PWD
 * -----------------------------------------------------------
 * Sets PWD and visible PWD for student users
 * -----------------------------------------------------------
 *
 * @param $i_hash - User ID
 * @param $s_pwd  - New Password
 *
 * @return bool
 */
function wushka_set_student_pwd( $i_hash, $s_pwd ) {
    $o_user = get_user_by_hash($i_hash);
    if( $o_user ) {
        //Set PWD
        wp_set_password($s_pwd, $o_user->ID);
        //Set Visible PWD (for students only)
        update_user_meta( $o_user->ID, 'show_user_pwd', esc_html($s_pwd) );
    }

    return TRUE;
}

/** Wushka Edit User Class
 * ----------------------------------------------------------
 * Updates existing class's name, or creates a new one if
 * no class is found matching passed ID.
 * ----------------------------------------------------------
 *
 * @param (int) $i_id  - Class ID
 * @param (string) $s_name - New Class Name
 *
 * @return bool
 */
function wushka_edit_user_class( $i_id, $s_name ) {
    $o_user = get_user_by_hash($i_id);

    if( ! isset($o_user->class) ) {
        // create new class
        error_log('creating new class:' . $s_name);
        $i_class = wushka_create_class($s_name, $o_user->ID);

        //Add Class id to User
        error_log('Update User Meta: User-' . $o_user->ID . '. Class-' . $i_class . '.');
        update_user_meta($o_user->ID, 'class', $i_class);
    } else {
        error_log('Updating class name:' . $s_name);
        $a_args = array(
            'id'   => $o_user->class,
            'name' => $s_name
        );
        wushka_update_class($a_args);
    }

    return TRUE;
}

/** Wushka Set Whole Class Archive
 * ----------------------------------------------------------
 * Sets the Archive value for all students in class
 * and then updates their reading history
 * ----------------------------------------------------------
 *
 * @param (int) $i_id  - Class ID
 * @param (int) $i_active - active Value
 *
 * @return bool
 */
function wushka_set_whole_class_archive( $i_id, $i_active ) {
    $a_students = wushka_get_students($i_id, 'class', 1);
    if( ! empty($a_students) ) {
        foreach( $a_students as $idx => $o_user ) {
            update_user_meta($o_user->ID, 'active', $i_active);
            $a_records = reader_analytics([$o_user]);
            if( ! empty($a_records) ) {
                update_user_meta($o_user->ID, 'reading_history', $a_records);
            }
        }
    }

    //Get School ID For School Event Notification
    $o_class = wushka_get_class($i_id);
    if( isset($o_class) && ! empty($o_class) ) {
        $i_school     = $o_class->school_id;
        $s_class_name = $o_class->name;
        if( $i_school !== NULL ) {
            $a_event_args = array(
                'school_id'   => (int)$i_school,
                'event_type'  => 'teacher',
                'sub_type'    => 'class',
                'action'      => 'archived',
                'description' => 'Class: ' . $s_class_name . ' archived',
                'meta_value'  => $i_id
            );
            wushka_load_school_event($a_event_args);
        }
    }

    return TRUE;
}

/** Wushka Set Student Archive
 * ----------------------------------------------------------
 * Sets the Archive value for a student, and creates an
 * event notification
 * ----------------------------------------------------------
 *
 * @param $i_hash   - Student ID Hash
 * @param $i_active - Active Value
 *
 * @return bool
 */
function wushka_set_student_archive( $i_hash, $i_active ) {
    if( (int)$i_active !== 1 && (int)$i_active !== 0 ) {
        return FALSE;
    }

    $o_user = get_user_by_hash($i_hash);
    //dd($o_user);
    if( $o_user ) {
        //Block to perform action if student's class has wushka decodable teacher licence
        if(isset($o_user->class)){
            $o_class = wushka_get_class($o_user->class);
        }else{
            $class = get_user_meta( $o_user, 'class', true );
            $o_class = wushka_get_class($o_user->class);
        }

        $licence = $o_class->licence_product;
        if($licence == "Wushka Decodable Teacher"){
            wp_send_json( "Sorry your licence for this class does not allow to perform this action.", 400 );
        }
        

        $s_action = 'archived';
        if( $i_active == 1 ) {
            $s_action = 'unarchived';
        } else {
            $a_records = reader_analytics($o_user);
            if( ! empty($a_records) ) {
                update_user_meta($o_user->ID, 'reading_history', $a_records);
            }
        }
        update_user_meta($o_user->ID, 'active', $i_active);

        //Get School For School Event Notification  
        if( isset($o_class) && ! empty($o_class) ) {
            $i_school = $o_class->school_id;
            if( isset($i_school) ) {
                $a_event_args = array(
                    'school_id'   => (int)$i_school,
                    'event_type'  => 'teacher',
                    'sub_type'    => 'student',
                    'action'      => $s_action,
                    'description' => 'Student: ' . $o_user->first_name . ' ' . $o_user->last_name . ' ' . $s_action,
                    'meta_value'  => $o_user->ID
                );

                wushka_load_school_event($a_event_args);
            }
        }
 

    }

    return TRUE;
}

/** Wushka Set Whole Class Password
 * ----------------------------------------------------------
 * Sets a new password for all students in the class
 * ----------------------------------------------------------
 *
 * @param (int) $i_id  - Class ID
 * @param (string) $s_pwd - New Password
 *
 * @return bool
 */
function wushka_set_whole_class_password( $i_class, $s_pwd ) {
    $a_students = wushka_get_students($i_class, 'class', 1);
    if( ! empty($a_students) ) {
        foreach( $a_students as $idx => $o_user ) {
            wushka_set_student_pwd($o_user->id_hash, $s_pwd);
        }
    }

    return TRUE;
}

/** Wushka Set Whole Class Level
 * ----------------------------------------------------------
 * Sets a new reading level for all students in the class
 * ----------------------------------------------------------
 *
 * @param (int) $i_id  - Class ID
 * @param (string) $s_level - New Reading Level
 *
 * @return bool
 */
function wushka_set_whole_class_level( $i_class, $s_level, $class_id ) {
    //Get Class Students
    $a_students = wushka_get_students($i_class, 'class', 1);

    if( ! empty($a_students) ) {
        foreach( $a_students as $idx => $o_user ) {
            wushka_set_student_level($o_user->id_hash, $s_level, $class_id);
        }
    }

    return TRUE;
}
 function wushka_set_whole_class_allowed_levels($i_class, $s_level) {
    //Get Class Students
    $a_students = wushka_get_students($i_class, 'class', 1);

    if( ! empty($a_students) ) {
        foreach( $a_students as $idx => $o_user ) {
            wushka_set_student_allowed_levels($o_user->id_hash, $s_level);
        }
    }

    return TRUE;

 }

/** Wushka Set Reading Group
 * ----------------------------------------------------------
 * Sets new reading group for single student.
 * If set, Update linked account's reading group also.
 * ----------------------------------------------------------
 *
 * @param int $i_hash  - Student User Hash
 * @param int $i_group - Reading Group ID
 *
 * @return bool
 */
function wushka_set_reading_group( $i_hash, $i_group ) {
    //Set Student Reading Group
    wushka_update_user_meta($i_hash, 'my_reading_group', $i_group);

    //Update Any Linked Account
    $o_user = get_user_by_hash($i_hash);
    if( isset($o_user->child_link_id) && ! empty($o_user->child_link_id) ) {
        update_user_meta($o_user->child_link_id, 'my_reading_group', $i_group);
    }

    return TRUE;
}

/** Wushka Set Reading Group Permissions
 * ----------------------------------------------------------
 * Sets new reading group permissions for single student.
 * ----------------------------------------------------------
 *
 * @param int $i_hash  - Student User Hash
 * @param string $s_setting - Reading Group Permissions slug
 *
 * @return bool
 */
function wushka_set_reading_group_permission($i_hash, $s_setting) {
    //Set Student Reading Group Permissions
    wushka_update_user_meta($i_hash, 'rg_setting', $s_setting);

    return TRUE;
}

/** Wushka Set New Reading Group
 * -----------------------------------------------------------
 * Create new group and add passed student to it
 * -----------------------------------------------------------
 *
 * @param $i_class     - Class ID
 * @param $s_new_group - New Group Name
 *
 * @return bool
 */
function wushka_set_new_reading_group( $i_class, $s_new_group ) {
    //Get User

    //Get Reading Group Class
    include_once 'functions/reading-groups/class_reading-groups.php';
    $c_rg = new Reading_Groups();
    //Retrieve all groups for this class
    $b_exists = FALSE;
    $a_groups = array();
    if( ($a_groups = $c_rg->get_groups('class', $i_class)) !== FALSE ) {
        //Verify new group name doesn't already exist (for this class)
        foreach( $a_groups as $ix => $o_group ) {
            if( $s_new_group == $o_group->group_name ) {
                $b_exists = TRUE;
                error_log('Group already Exists, Abort');
            }
        }
    }

    //If does not exist, create and add passed student to it
    if( ! $b_exists ) {
        if( ($x_new = $c_rg->create_group($s_new_group, $i_class)) !== FALSE ) {
            $a_return = array();
            $a_return[0] = 'No Group';
            $a_new = $c_rg->get_groups('class', $i_class);
            foreach( $a_new as $ix => $o_group ) {
                $a_return[$o_group->ID] = ucwords($o_group->group_name);
            }

            return $a_return;
        }
    }

    error_log('Could not create new Group, Abort');

    return FALSE;
}

/** Wushka Student First Login
 * ----------------------------------------------------------
 * Check if passed user login exists, returns true if found
 * ----------------------------------------------------------
 *
 * @param int $i_hash - Student User Hash
 *
 * @return $a_return  - POST data with boolean error flag.
 */
function wushka_student_first_login( $i_hash ) {
    $o_user            = get_user_by_hash($i_hash);
    $a_return          = $_POST;
    $a_return['error'] = FALSE;

    if( $o_user ) {
        //Check user name is unique
        if( isset($_POST['user_login']) && ($_POST['user_login']) ) {
            $s_login = trim(filter_input(INPUT_POST, 'user_login'));
            if( username_exists($s_login) ) {
                $a_return['error'] = TRUE;
            }
        }
    }

    return $a_return;
}

/** Wushka Update Student Form
 * ----------------------------------------------------------
 * Checks POST global for user meta fields,
 * updates if found
 * ----------------------------------------------------------
 *
 * @param int $i_hash - Student User Hash
 *
 * @return $a_return  - POST data with boolean error flag.
 */
function wushka_update_student_form( $i_hash ) {
    $o_user            = get_user_by_hash($i_hash);
    $a_return          = $_POST;
    $a_return['error'] = FALSE;

    if( $o_user ) {
        $i_user = $o_user->ID;

        //Update First Name
        if( isset($_POST['first_name']) && ! empty($_POST['first_name']) ) {
            $s_first_name = trim(stripcslashes(filter_input(INPUT_POST, 'first_name')));
            update_user_meta($i_user, 'first_name', $s_first_name);
        }

        //Update Last Name
        if( isset($_POST['last_name']) && ! empty($_POST['last_name']) ) {
            $s_last_name = trim(stripcslashes(filter_input(INPUT_POST, 'last_name')));
            update_user_meta($i_user, 'last_name', $s_last_name);
        }

        //Update User Login
        if( isset($_POST['user_login']) && ! empty($_POST['user_login']) ) {
            $s_login = trim(filter_input(INPUT_POST, 'user_login'));
            update_user_meta($i_user, 'user_login', $s_login);
        }

        //Update User Email
        if( isset($_POST['user_email']) && ! empty($_POST['user_email']) ) {
            $s_login = trim(filter_input(INPUT_POST, 'user_email'));
            update_user_meta($i_user, 'user_email', $s_login);
        }

        //Update User Password
        if( isset($_POST['user_pass']) && ! empty($_POST['user_pass']) ) {
            $s_pwd = trim(filter_input(INPUT_POST, 'user_pass'));
            wp_set_password($s_pwd, $i_user);
            update_user_meta($i_user, 'show_user_pwd', $s_pwd);
        }
    }

    return $a_return;
}


function wushka_get_reading_levels($decodable = false) {
    $a_args  = array(
        'orderby' => 'slug',
        'order'   => 'ASC'
    );
    $a_terms = get_terms('reading-level', $a_args);
    
    $terms = array();
    $count = 0;
    foreach($a_terms as $a_term){ 
        if($decodable && $count >= 8){
            break;
        }
        array_push($terms, $a_term);
        $count++;
    } 
    return $terms;
}

function wushka_get_allowed_levels() {
    return array(
        array(
            'id'   => 'none',
            'name' => 'Reading Group Only'
        ),
        array(
            'id'   => 'only',
            'name' => 'Reading Level Only'
        ),
        array(
            'id'   => 'current_below',
            'name' => 'Reading Level + Levels Below'
        ),
        array(
            'id'   => 'below',
            'name' => 'Levels Below Reading Level Only'
        ),
        array(
            'id'   => 'current_above',
            'name' => 'Reading Level + One Level Above'
        ),
        array(
            'id'   => 'current_one_below',
            'name' => 'Reading Level + One Level Below'
        ),
        array(
            'id'   => 'all',
            'name' => 'All Levels'
        ),
    );
}

function wushka_set_student_level( $i_hash, $s_level, $class_id = null ) {
    $o_user = get_user_by_hash($i_hash);

    $s_return = TRUE;

    //Get class licence and filter if needed
    $decodable = false;
    if($class_id != null){
        $licence = get_class_licence($class_id);
        if($licence == "Wushka Decodables"){
            $decodable = true;
        }
    }

    if( $o_user ) {
        //Get Reading Levels
        $a_terms = wushka_get_reading_levels($decodable);
        if( isset($a_terms) && ! empty($a_terms) ) {
            $a_level = array(
                'name' => '',
                'slug' => ''
            );
            //Store Empty Level
            if( isset($s_level) && ! empty($s_level) ) {
                //Confirm passed level exists
                foreach( $a_terms as $idx => $o_term ) {
                    if( $o_term->slug == $s_level ) {
                        $a_level = array(
                            'name' => $o_term->name,
                            'slug' => $o_term->slug
                        );
                        break;
                    }
                }
            }

            //Store New Reading Level Meta
            update_user_meta($o_user->ID, 'reading_level', $a_level);
            //Update Prepared Levels according to allowed levels params.
            wushka_set_student_prep_levels($o_user->ID);

            $s_return = $a_level['slug'];
        }
    }

    return $s_return;
}

function wushka_set_student_allowed_levels( $i_hash, $s_allowed ) {
    $o_user = get_user_by_hash($i_hash);
    if( $o_user ) {
        $a_allowed = wushka_get_allowed_levels();
        foreach( $a_allowed as $ii => $a_range ) {
            if( $s_allowed == $a_range['name'] ) {
                //Update Allowed Shelves
                update_user_meta($o_user->ID, 'allowed_shelves', $a_range);
                //Update Prepared Levels
                wushka_set_student_prep_levels($o_user->ID);
            }
        }
    }
}

function wushka_set_student_prep_levels( $i_user ) {
    $a_levels   = wushka_get_reading_levels();
    $a_level    = get_user_meta($i_user, 'reading_level', TRUE);
    $a_allowed  = get_user_meta($i_user, 'allowed_shelves', TRUE);
    $a_prep_new = array();

    $s_slug = $a_allowed['id'];

    if( isset($s_slug) && ! empty($s_slug) ) {
        switch( $s_slug ) {
            case 'only':
                $a_prep_new[] = $a_level['slug'];
                break;
            case 'current_below':
                foreach( $a_levels as $idx => $o_level ) {
                    $a_prep_new[] = $o_level->slug;
                    if( $o_level->slug == $a_level['slug'] ) {
                        break;
                    }
                }
                break;
            case 'below':
                foreach( $a_levels as $idx => $o_level ) {
                    if( $o_level->slug == $a_level['slug'] ) {
                        break;
                    }
                    $a_prep_new[] = $o_level->slug;
                }
                break;
            case 'current_above':
                $i_level = NULL;
                foreach( $a_levels as $idx => $o_level ) {
                    if( $o_level->slug == $a_level['slug'] ) {
                        $i_level      = $idx + 1;
                        $a_prep_new[] = $o_level->slug;
                    }
                    if( isset($i_level) && $idx == $i_level ) {
                        $a_prep_new[] = $o_level->slug;
                        break;
                    }
                }
                break;
            case 'current_one_below':
                $i_level = NULL;
                foreach( $a_levels as $idx => $o_level ) {
                    if( $o_level->slug == $a_level['slug'] ) {
                        $i_level      = $idx - 1;
                        $a_prep_new[] = $o_level->slug;
                    }
                }

                foreach( $a_levels as $idx => $o_level ) {
                    if( isset($i_level) && $idx == $i_level ) {
                        $a_prep_new[] = $o_level->slug;
                        break;
                    }
                }
                break;
            case 'all':
                foreach( $a_levels as $idx => $o_level ) {
                    $a_prep_new[] = $o_level->slug;
                }
                break;
        }
    } else {
        $a_prep_new [] = $a_level['slug'];
    }

    error_log('New Prepared Levels:');
    error_log(print_r($a_prep_new, TRUE));

    update_user_meta($i_user, 'prepared_shelves', $a_prep_new);

    return TRUE;
}
/*----- EOF ----- */