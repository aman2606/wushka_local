<?php

/*
 * Add new student data into database
 */
include $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
//For parents adding a new child into parent view
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['meta'])) {
    $meta_key = $_POST['meta'];

    $available = check_license_limit($current_user->ID);
    $parent_id = $current_user->ID;

    // Add new child from parent dashboard
    if ($meta_key == 'add-new-child') {
        $age_reading_level = '';

        if ($available <= 0) {
            $return['available'] = $available;
            echo json_encode($return);
        } else {
            $fname = $_POST['first_name'];
            $lname = $_POST['last_name'];
            $username = $_POST['username'];

            //School Account
            $s_school = $_POST['child_school'];
            $s_student_user = trim($_POST['school_username']);
            $s_student_pwd = trim($_POST['school_password']);

            $password = $_POST['show_user_pwd'];

            $i_dob = $_POST['dob_day'] . '/' . $_POST['dob_month'] . '/' . $_POST['dob_year'];
            $age = $i_dob;
            $i_level = $_POST['reading-level'];
            /* Check username is already taken or not */
            if (username_exists($username)) {
                do {
                    $oldName = explode("-", $username);
                    $ramdom_num = rand(1000, 99999);
                    $username = $oldName[0] . '-' . $ramdom_num;
                } while (username_exists($username));
            }

            if (!username_exists($username)) {
                $userdata = array(
                    'user_login' => $username,
                    'user_pass' => $password,
                    'role' => 'student'
                );

                $user_id = wp_insert_user($userdata);
                if ($user_id) {
                    update_user_meta($user_id, 'parent_id', $parent_id);
                    update_user_meta($user_id, 'first_name', $fname);
                    update_user_meta($user_id, 'last_name', $lname);
                    update_user_meta($user_id, 'show_admin_bar_front', 'false');
                    update_user_meta($user_id, 'show_user_pwd', $password);
                    update_user_meta($user_id, 'narration', 'Yes');
                    update_user_meta($user_id, 'active', 1);
                    update_user_meta($user_id, 'age', $age);
                    update_user_meta($user_id, 'user_dob', $i_dob);
                    update_user_meta($user_id, 'reading_level', $i_level);
                    #TODO: Create Email for teachers of School Year!
                    //school year for emails
                    update_user_meta($user_id, 'school_year_level', trim($_POST['child_year']));

                    if (isset($s_school)) {
                        //Home schooled, remove school terms
                        if ($s_school == 'home') {
                            wp_delete_object_term_relationships($user_id, 'school');
                        } else {
                            wp_set_object_terms($user_id, $s_school, 'school');
                        }
                    }
                    if (isset($s_student_user, $s_student_pwd)) {
                        //Determine if passed username and password matches a current Teacher Student Username
                        if (($o_student = get_user_by('login', trim($s_student_user)) ) !== FALSE) {
                            if (isset($o_student->show_user_pwd) && !empty($o_student->show_user_pwd)) {
                                if ($s_student_pwd == $o_student->show_user_pwd) {
                                    //Match Found: Store user ID in meta field
                                    if (!isset($o_student->child_link_id) || !empty($o_student->child_link_id)) {
                                        //Validated, Save Link
                                        update_user_meta($user_id, 'student_link_id', $o_student->ID);
                                        update_user_meta($o_student->ID, 'child_link_id', $user_id);
                                    }
                                }
                            }
                        }
                    }

                    /*
                     * Prepare reading level against age selected from profile page
                     */
//                    $args = array('orderby' => 'slug', 'order' => 'ASC');
//                    $reading_levels = get_terms('reading-level', $args);
//
//                    $age_reading_level = get_user_meta($parent_id, 'age_reading_level', true);
//
//                    /* Add reading level against age if it doesn't exist */
//                    if (!$age_reading_level) {
//                        $age_reading_level = get_readingLevel_with_age($reading_levels);
//                        update_user_meta($parent_id, 'age_reading_level', $age_reading_level);
//                    }
//
//                    /* Prepare reading level to show in bookshelf
//                     * Min-range and max-range of age limit are submitted from create user profile page
//                     */
//                    foreach ($age_reading_level as $idx => $level) {
//                        /* Two levels below from selected min age limit */
//                        if (($min_range - 2) < 4) {
//                            if (($min_range - 1) < 4) {
//                                $levels_below = $min_range;
//                            } else
//                                $levels_below = ($min_range - 1);
//                        } else
//                            $levels_below = ($min_range - 2);
//
//                        /* One level above from selected max age limit */
//                        if (($max_range + 1) > 12) {
//                            $level_above = $max_range;
//                        } else
//                            $level_above = ($max_range + 1);
//
//                        if ($level['age'] >= $levels_below && $level['age'] <= $level_above
//                        ) {
//                            $prepared_shelves['age-' . $level['age']][] = $level['slug'];
//                        }
//                    }
//
//                    update_user_meta($user_id, 'prepared_shelves', $prepared_shelves);
                    $a_levels   = wushka_get_reading_levels();
                    foreach( $a_levels as $idx => $o_level ) {
                        $prepared_shelves[] = $o_level->slug;
                        if( $o_level->slug == $i_level ) {
                            break;
                        }
                    }
                    update_user_meta($user_id, 'prepared_shelves', $prepared_shelves);
                    error_log('** User **' . print_r(get_user_meta($userID, 'prepared_shelves', true), true));

                    $return['id'] = $user_id;
                    //removed $total + 1 from return and replaced it with 1
                    $return['total'] = 1;
                    $return['available'] = $available;
                    if (!is_wp_error($user_id)) {
                        echo json_encode($return);
                    }
                } else {
                    $return['error'] = 'Account creation failed.';
                    echo json_encode($return);
                }
            } else {
                $return['error'] = 'Account creation failed.';
                echo json_encode($return);
            }
        }
    }
    // End of adding new child from parent dashboard
    // Edit child profile from parent dashboard
    if ($meta_key == 'edit-child-profile') {
        $fname = $_POST['first_name'];
        $lname = $_POST['last_name'];
        $username = $_POST['username'];
        $userID = get_user_by_hash($_POST['userID'])->ID;
        $password = $_POST['show_user_pwd'];
        $age = $_POST['age'];
        $i_dob = $_POST['dob_day'] . '/' . $_POST['dob_month'] . '/' . $_POST['dob_year'];
        $i_level = $_POST['reading-level'];
        $return = array();

        // save school linkage
        if (isset($_POST['child_school'])) {
            //Home schooled, remove school terms
            if ($_POST['child_school'] == 'home') {
                wp_delete_object_term_relationships($userID, 'school');
            } else {
                wp_set_object_terms($userID, $_POST['child_school'], 'school');
            }
        }
        if (isset($_POST['school_username'], $_POST['school_password'])) {
            //Determine if passed username and password matches a current Teacher Student Username
            if (($o_student = get_user_by('login', trim($_POST['school_username'])) ) !== FALSE) {
                $i_student_pwd = get_user_meta($o_student->ID, 'show_user_pwd', true);
                if (isset($i_student_pwd) && !empty($i_student_pwd)) {
                    if ($_POST['school_password'] == $i_student_pwd) {
                        //Match Found: Store user ID in meta field
                        if ((!isset($o_child->student_link_id) || empty($o_child->student_link_id)) &&
                                (!isset($o_student->child_link_id) || empty($o_student->child_link_id))) {
                            //Validated, Save Link
                            update_user_meta($userID, 'student_link_id', $o_student->ID);
                            update_user_meta($o_student->ID, 'child_link_id', $userID);
                        }
                    }
                }
            }
        }

        /** Compare changes against old data. If things has changed, email back to
         * a user for confirmation
         * * */
        if (user_can($current_user, "parent") && isset($current_user->user_email)) {
//            $user_info = get_userdata($current_user->ID);
//            $email = $user_info->user_email;

            $oldFName = get_user_meta($userID, 'first_name', true);
            $oldLName = get_user_meta($userID, 'last_name', true);
            $old_pwd = get_user_meta($userID, 'show_user_pwd', true);
            $old_age = get_user_meta($userID, 'age', true);
            $old_dob = get_user_meta($userID, 'user_dob', true);
            $old_reading_level = get_user_meta($userID, 'reading_level', true);

            if ($oldFName != $fname) {
                $changed_fname = 'First Name';
                update_user_meta($userID, 'first_name', $fname);
            }

            if ($oldLName != $lname) {
                $changed_lname = 'Last Name';
                update_user_meta($userID, 'last_name', $lname);
            }

            if ($old_pwd != $password) {
                $changedPassword = 'Password';
                wp_set_password($password, $userID);
                update_user_meta($userID, 'show_user_pwd', $password);
            }

            if ($old_age != $age) {
                $changed_age = 'Age';
                update_user_meta($userID, 'age', $age);

//                /*
//                 * Prepare reading level against age selected from profile page
//                 */
//                $args = array('orderby' => 'slug', 'order' => 'ASC');
//                $reading_levels = get_terms('reading-level', $args);
//
//                /* delete_user_meta( $parent_id, 'age_reading_level', true); */
//                $age_reading_level = get_user_meta($parent_id, 'age_reading_level', true);
//
//                /* Add reading level against age if it doesn't exist */
//                if (!$age_reading_level) {
//                    $age_reading_level = get_readingLevel_with_age($reading_levels);
//                    update_user_meta($parent_id, 'age_reading_level', $age_reading_level);
//                }
//                /* Prepare reading level to show in bookshelf
//                 * Min-range and max-range of age limit are submitted from create user profile page
//                 */
//
//                foreach ($age_reading_level as $idx => $level) {
//                    /* Two levels below from selected min age limit */
//
//                    if (($min_range - 2) < 4) {
//                        if (($min_range - 1) < 4) {
//                            $levels_below = $min_range;
//                        } else
//                            $levels_below = ($min_range - 1);
//                    } else
//                        $levels_below = ($min_range - 2);
//
//
//                    /* One level above from selected max age limit */
//                    if (($max_range + 1) > 12) {
//                        $level_above = $max_range;
//                    } else
//                        $level_above = ($max_range + 1);
//
//                    if ($level['age'] >= $levels_below && $level['age'] <= $level_above
//                    ) {
//                        //error_log('** $levels_below **' . print_r($level['age'],true));
//                        $prepared_shelves['age-' . $level['age']][] = $level['slug'];
//                    }
//                }
            }
            if ($old_dob !== $i_dob) {
                update_user_meta($userID, 'user_dob', $i_dob);
            }
            if ($old_reading_level !== $i_level) {
                update_user_meta($userID, 'reading_level', $i_level);
                $a_levels   = wushka_get_reading_levels();
                foreach( $a_levels as $idx => $o_level ) {
                    $prepared_shelves[] = $o_level->slug;
                    if( $o_level->slug == $i_level ) {
                        break;
                    }
                }
                update_user_meta($userID, 'prepared_shelves', $prepared_shelves);
                error_log('** User **' . print_r(get_user_meta($userID, 'prepared_shelves', true), true));
            }

            //error_log('** Shelves **' . print_r($prepared_shelves,true));
//            if (isset($changed_fname) || isset($changed_lname) || isset($changedPassword) || isset($changed_age_range)) {
            // prepare_send_email( $fname, $changed_fname, $lname, $changed_lname, $changedPassword, '' , $changed_age_range, $email);
//            }
        //
        } else {
            $return['status'] = 'You do not have a permission to make changes.';
        }
        echo json_encode($return);
    }
    if ($meta_key == 'delete-child-profile') {
        $userID = get_user_by_hash($_POST['userID'])->ID;
        update_user_meta($userID, 'active', 0);
        $return['status'] = 'profile deleted.';
        echo json_encode($return);
    }
    /*     * **End of editing child profile from parent dashboard *** */
}

function get_readingLevel_with_age($reading_levels) {
    foreach ($reading_levels as $idx => $reading_level) {
        if ($reading_level->slug == 'a-magenta-levels-1-2') {

            $age_reading_level[$idx - 1]['slug'] = $reading_level->slug;
            $age_reading_level[$idx - 1]['age'] = '4';
        }

        if (($reading_level->slug == 'a-magenta-levels-1-2') || ($reading_level->slug == 'b-red-levels-3-5') || ($reading_level->slug == 'c-yellow-levels-6-8')
        ) {
            $age_reading_level[$idx]['slug'] = $reading_level->slug;
            $age_reading_level[$idx]['age'] = '5';
        }

        if (//($reading_level->slug  == 'a-magenta-levels-1-2')
                ($reading_level->slug == 'd-blue-levels-9-11') || ($reading_level->slug == 'e-green-levels-12-14')
        ) {
            $age_reading_level[$idx]['slug'] = $reading_level->slug;
            $age_reading_level[$idx]['age'] = '6';
        }

        if (($reading_level->slug == 'f-orange-levels-15-16') || ($reading_level->slug == 'g-turquoise-levels-17-18')
        ) {
            $age_reading_level[$idx]['slug'] = $reading_level->slug;
            $age_reading_level[$idx]['age'] = '7';
        }

        if (($reading_level->slug == 'h-purple-levels-19-20') || ($reading_level->slug == 'i-gold-levels-21-22') || ($reading_level->slug == 'j-silver-levels-23-24')
        ) {
            $age_reading_level[$idx]['slug'] = $reading_level->slug;
            $age_reading_level[$idx]['age'] = '8';
        }

        if (($reading_level->slug == 'k-emerald-levels-25-26') || ($reading_level->slug == 'l-ruby-levels-27-28')
        ) {
            $age_reading_level[$idx]['slug'] = $reading_level->slug;
            $age_reading_level[$idx]['age'] = '9';
        }

        if (($reading_level->slug == 'm-sapphire-levels-29-30')
        ) {
            $age_reading_level[$idx]['slug'] = $reading_level->slug;
            $age_reading_level[$idx]['age'] = '10';
        }

        if (($reading_level->slug == 'n-bronze-levels-31')
        ) {
            $age_reading_level[$idx]['slug'] = $reading_level->slug;
            $age_reading_level[$idx]['age'] = '11';
        }

        if (($reading_level->slug == 'o-black-levels-31')
        ) {
            $age_reading_level[$idx]['slug'] = $reading_level->slug;
            $age_reading_level[$idx]['age'] = '12';
        }
    }
    // error_log('** $age_reading_level **' . print_r($age_reading_level,true));
    return $age_reading_level;
}
function wushka_get_reading_levels() {
    $a_args  = array(
        'orderby' => 'slug',
        'order'   => 'ASC'
    );
    $a_terms = get_terms('reading-level', $a_args);

    return $a_terms;
}

