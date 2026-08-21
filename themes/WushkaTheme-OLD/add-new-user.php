<?php

/*
 * Add new student data into
 */

include $_SERVER['DOCUMENT_ROOT'] . '/wushka_local/wp-config.php';

// To get License limit
$max_allowance = 200;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['class'])) {
    $class = json_decode(trim(filter_input(INPUT_POST, 'class')), TRUE);

    //Disallow adding students to class having Wushka decodable teacher licence
    $class_details = wushka_get_class($class);
    if (isset($class_details)) {
        $licence = $class_details->licence_product;
        if ($licence == "Wushka Decodable Teacher") {
            wp_send_json("Sorry your licence for this class does not allow to perform this action.", 400);
        }
    }
    //Get total number of students
    // updated Feb 2019 to prevent count_total performing slow query
    $args = array(
        'role'       => 'student',
        'count_total'   => false,
        'meta_query' => array(
            'relation' => 'AND',
            0          => array(
                'key'   => 'class',
                'value' => $class
            ),
            1          => array(
                'key'   => 'active',
                'value' => 1
            )
        )
    );

    $user_query = new WP_User_Query($args);  // args updated for slow query
    $total      = 0;
    if (!empty($user_query->results)) {
        $total = count($user_query->results);
    }
    $return    = $_POST;
    $available = ($max_allowance - $total);

    if ($available <= 0) {
        $return['available'] = $available;
        echo json_encode($return);
    } else {
        $first_name = json_decode(stripcslashes(filter_input(INPUT_POST, 'first_name')), TRUE);
        $last_name  = json_decode(stripcslashes(filter_input(INPUT_POST, 'last_name')), TRUE);
        $user_name  = json_decode(trim(filter_input(INPUT_POST, 'username')), TRUE);
        $s_email  = json_decode(trim(filter_input(INPUT_POST, 's_email')), TRUE);

        $emailExists = false;

        $isActive = false;

        $needStudentReset = false;

        if (isset($s_email) && !empty($s_email)) {


            $s_email  = json_decode(trim(filter_input(INPUT_POST, 's_email')), TRUE);
            $s_email_validate = student_email_validation($s_email);
            if ($s_email_validate['type'] == 'error') {
                wp_send_json($s_email_validate['message'], 400);
                wp_die();
            }

            $emailExists = email_exists($s_email);

            if ($emailExists) {

                $isActive = get_user_meta($emailExists, 'active', true);
            }

        }

        if($emailExists && ($isActive == false || $isActive == 0 || $isActive == '0')){

            $needStudentReset = true;
        }


        if (username_exists($user_name)) {
            (strlen($first_name) > 0) ? $fname = $first_name[0] : $fname;
            (strlen($last_name) > 0) ? $lname = $last_name[0] : $lname;

            while (username_exists($user_name)) {
                $user_name = strtoupper($fname . $lname . '-' . rand(1000, 99999));
            }
        }

        $student_data = [
            'first_name'    =>  sanitize_text_field($first_name),
            'last_name'     =>  sanitize_text_field($last_name),
            'user_name'     =>  sanitize_text_field($user_name),
            's_email'       =>  sanitize_text_field($s_email)
        ];



        if($needStudentReset === false && !$emailExists){

            $student = wushka_create_student($student_data, $class, $total, $available,  $return);
        }

        if($needStudentReset){

            $student = wushka_class_move_student($emailExists,$student_data, $class, $total, $available,  $return,true);

        }elseif($needStudentReset === false && $emailExists){

            $student = wushka_class_move_student($emailExists,$student_data, $class, $total, $available,  $return,false);
        }
        
        echo $student;
    }
}
/*----- END OF FILE -----*/
