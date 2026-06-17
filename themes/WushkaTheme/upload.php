<?php

/*
 * Teachers uploading students' data
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';

/** PHPExcel_IOFactory */
include 'Classes/PHPExcel/IOFactory.php';

//$subscriptions = WC_Subscriptions_Manager::get_users_subscriptions();

$max_allowance = 500000;

//if (isset($subscriptions) && count($subscriptions) > 0) {
//    foreach ($subscriptions as $subscription) {
//        if ($subscription['status'] == 'active') {
//            $subscription_key = WC_Subscriptions_Manager::get_subscription_key($subscription['order_id'], $subscription['product_id']);
//            if (isset($subscription_key) && strlen($subscription_key) > 0) {
//                $product = get_product($subscription['product_id']);
//                $product_attributes = unserialize($product->product_custom_fields['_product_attributes'][0]);
//                $license_limit = $product_attributes['license_limit']['value'];
//                $max_allowance = isset($license_limit) ? $license_limit : 0;
//            }
//        }
//    }
//}
//$max_allowance = 35; //comment out this on Template and Live site
$row = 0;

$_SESSION['arhiveStudentList'] = [];

$arhiveStudentList = [];
if (isset($_POST['class_id'])) {

    $id = filter_input(INPUT_POST, 'class_id');
    $class_name = filter_input(INPUT_POST, 'class_name');

    //filter for class having decodable teacher licence
    $class_licence =  wushka_get_class($id);
    if (is_object($class_licence) && property_exists($class_licence, 'licence_product')) {
        $class_licence = $class_licence->licence_product;

        if ($class_licence == 'Wushka Decodable Teacher') {
            $return['available'] = 'Invalid Licence';
            echo json_encode($return);
            die;
        }
    }

    //    $user = get_user_by_hash($id);

    // updated Feb 2019 to prevent count_total performing slow query
    // $args = array(
    //     'role'       => 'student',
    //     'count_total' => false,
    //     'meta_query' => array(
    //         'relation' => 'AND',
    //         0          => array(
    //             'key'   => 'class',
    //             'value' => $id
    //         ),
    //         1          => array(
    //             'key'   => 'active',
    //             'value' => 1
    //         )
    //     )
    // );

    // $user_query = new WP_User_Query($args);  // args updated for slow query
    $students   = array();
    $return     = array();
    ini_set('auto_detect_line_endings', TRUE);

    $total = 0;
    // if( ! empty($user_query->results) ) {
    //     $total = count($user_query->results);
    // }
    $available = ($max_allowance - $total);

    /* Preparing for user meta */
    $allowed_shelves['id']   = 'all';
    $allowed_shelves['name'] = 'All Levels';

    $args  = array(
        'orderby' => 'slug',
        'order'   => 'ASC'
    );
    $terms = get_terms('reading-level', $args);

    foreach ($terms as $iidx => $term) {
        $prepared_shelves[] = $term->slug;
    }
    /* End of preparation for user meta */

    if ($available <= 0) {
        $return['available'] = $available;
        echo json_encode($return);
        exit();
    } else {
        $inputFileName = $_FILES['filename']['tmp_name'];  // File to read
        //error_log($_FILES['filename']['name']);  // File to read
        $extension = pathinfo($_FILES['filename']['name'], PATHINFO_EXTENSION);

        //error_log('Upload File : '.$inputFileName);
        //error_log('File Extension: '.$extension);
        /* for Excel/.xlsx file extension */
        if ($extension == 'xlsx') {
            //error_log('ready to import:' . $inputFileName);
            // bit of a crap way to do this, but might as well hijack the class uploading funtionality for School taxonomy load
            try {
                $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
            } catch (Exception $e) {
                $return['available'] = 'School import failed';
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
            }

            $sheetData = $objPHPExcel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);
            foreach ($sheetData as $idx => $data) {
                $students[$idx]['B'] = $data['A'];
                $students[$idx]['A'] = $data['B'];
                $students[$idx]['C'] = $data['C'];
            }
            /* For csv file extension */
        } else if ($extension == 'csv') {
            if (($handle = fopen($_FILES['filename']['tmp_name'], "r")) !== FALSE) {
                $row = 1;
                while (($student_data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                    $students[$row]['B'] = $student_data['0'];
                    $students[$row]['A'] = $student_data['1'];
                    $students[$row]['C'] = $student_data['2'];
                    $row++;
                }   //end while
                fclose($handle);
            } else {
                $return['available'] = 'Invalid';
            }
        } else {
            $return['available'] = 'Invalid';
        }



        /* Error checking, validation and writing to database */
        if (($extension == 'csv' || $extension == 'xlsx')) {
            if (!empty($students)) {
                $o_class  = wushka_get_class($id);
                $i_school = NULL;
                if (isset($o_class) && !empty($o_class)) {
                    $i_school = $o_class->school_id;
                    if (!isset($i_school) || empty($i_school)) {
                        error_log('Warning: No School ID found on Student Class;');
                        error_log('Warning: Cannot Attache New Student Accounts to School Taxonomy Term');
                    }
                }

                foreach ($students as $idx => $student) {
                    $student['A'] = cleanOnlySpecialChar($student['A']);
                    $student['B'] = cleanOnlySpecialChar($student['B']);

                    //$student['C'] email validation
                    if ($student['C'] != '') {

                        $student['C'] = sanitize_email($student['C']);
                        //Student email validation
                        $email_validation = student_email_validation($student['C']);
                        if ($email_validation['type'] == 'error') {
                            error_log('Error: Could not assign email for:' . $student['C']);
                            error_log('Error message: ' . $email_validation['message']);
                            $student['C'] = '';
                        }
                    }



                    if (!empty($student['A']) && !empty($student['B'])) {
                        if ($student['A'] != 'first name' && $student['A'] != 'surname' && $student['B'] != 'first name' && $student['B'] != 'surname') {

                            if (($available) > 0) {
                                $return['available'] = $available;

                                $license_key = strtoupper(substr($student['A'], 0, 1)) . strtoupper(substr($student['B'], 0, 1)) . '-' . rand(1000, 9999);
                                //                                $license_key = generateRandomString(3) . rand(1000, 9999);
                                $user_name    = $license_key;
                                $user_pwd     = 'temppwd';
                                $meta_lic_key = $license_key;
                                $meta_lic_pwd = generateRandomString(1) . rand(100000, 999999);

                                $emailExists = false;

                                $isActive = false;

                                $previousClass = false;

                                if (!empty($student['C'])) {

                                    // $userdata['user_email'] = $student['C'];

                                    $emailExists = email_exists($student['C']);

                                    if ($emailExists) {

                                        $isActive = get_user_meta($emailExists, 'active', true);
                                        $perviousClassId = get_user_meta($emailExists,'class',true);

                                        $previousClass = wushka_get_class($perviousClassId);

                                        if (isset($previousClass) && !empty($previousClass) && !empty($o_class)) {
                                            
                                            $oldSchoolId = $previousClass->school_id;

                                            if($oldSchoolId !== $o_class->school_id){

                                                error_log("Student {$student['C']} is from different school !");
                                               // error_log('Error message: ' . $email_validation['message']);

                                               continue;
                                            }

                                        }
                                    }

                                    
                                }

                                $studentIsArchive = false;


                                if (username_exists($user_name)) {
                                    do {
                                        $oldName    = explode("-", $user_name);
                                        $ramdom_num = rand(1000, 99999);
                                        $user_name  = $oldName[0] . '-' . $ramdom_num;
                                    } while (username_exists($user_name));
                                }

                                $userdata = array(
                                    'user_login' => $user_name,
                                    'user_pass'  => $user_pwd,
                                    'role'       => 'student',
                                    'first_name' => ucfirst($student['A']),
                                    'last_name'  => ucfirst($student['B'])
                                );

                                if (!empty($student['C'])) {
                                    $userdata['user_email'] = $student['C'];
                                }


                                if ($emailExists) {

                                    $user_id = $emailExists;
                                } else {

                                    $user_id = wp_insert_user($userdata);
                                }

                                

                                if($emailExists && ($isActive == false || $isActive == 0 || $isActive == '0')){

                                    $studentIsArchive = true;
                                }

                                if(!$studentIsArchive){

                                    update_user_meta($user_id, 'active', 1);
                                    update_user_meta($user_id, 'class', $id);
                                }


                                if (!$emailExists) {

                                    update_user_meta($user_id, 'show_admin_bar_front', 'false');
                                    update_user_meta($user_id, 'license_key', $meta_lic_key);
                                    update_user_meta($user_id, 'license_pwd', $meta_lic_pwd);
                                    update_user_meta($user_id, 'show_user_pwd', $user_pwd);
                                    update_user_meta($user_id, 'allowed_shelves', $allowed_shelves);
                                    update_user_meta($user_id, 'prepared_shelves', $prepared_shelves);
                                    update_user_meta($user_id, 'narration', 'Yes');
                                    update_user_meta($user_id, 'quizzes', 'compulsory');
                                }

                                /** if Archive student */

                                if($studentIsArchive){

                                    $archiveUser = get_userdata($user_id);
                                    

                                    // $archiveUser = json_encode()

                                    array_push($arhiveStudentList,['user'=>['id'=>$archiveUser->ID,'email'=>$archiveUser->user_email],'currentClass'=>$previousClass->name,'target_class_id' => $id, 'target_class_name' => $class_name ]);

                                    //resetStudentData($emailExists);
                                }

                                // update_user_meta($user_id, 'first_name', ucfirst($student['A']));
                                // update_user_meta($user_id, 'last_name', ucfirst($student['B']));

                                // Get School For School Event Notification
                                if (isset($i_school) && !empty($i_school)) {

                                    if (!$emailExists) {
                                        wp_set_object_terms($user_id, array(intval($i_school)), 'school', FALSE);
                                        clean_object_term_cache($user_id, 'school');
                                    }
                                }

                                --$available;
                            } else {
                                $return['available'] = 'Exceeded';
                                break;
                            }
                        }
                    } else {
                        $return['available'] = 'Empty';
                    }
                } //End ForEach
            } else {
                $return['available'] = $available;
            }
        }
        $return['arhiveStudentList'] = $arhiveStudentList;
        $_SESSION['arhiveStudentList'] = $arhiveStudentList;
        echo json_encode($return);
    }
}

