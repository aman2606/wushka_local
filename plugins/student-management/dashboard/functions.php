<?php


function wsm_find_schools()
{

    $results = [];

    $results['items'] = [];

    $results['more'] = false;

    global $wpdb;

    $q = $_GET['q'];

    $schools = $wpdb->get_results(
        $wpdb->prepare("SELECT t.* FROM {$wpdb->prefix}terms as t INNER JOIN {$wpdb->prefix}term_taxonomy AS tt ON(t.term_id = tt.term_id) WHERE tt.taxonomy = 'school' AND (t.name LIKE %s OR t.slug LIKE %s)", "%{$q}%", "%{$q}%")
    );

    if (!empty($schools) && !empty($q)) {

        foreach ($schools as $school) {

            $record = [
                'id' => $school->term_id,
                'html' => $school->name . " ($school->slug)",
                'text' => $school->name . " ($school->slug)"
            ];

            array_push($results['items'], $record);
        }
    }

    echo json_encode(['results' => $results]);
    exit;
}


add_action('wp_ajax_wsm_find_schools', 'wsm_find_schools');


function find_classes_by_school()
{

    global $wpdb;

    $schoolId = $_GET['schoolId'];

    $classes = [];

    if (!empty($schoolId)) {

        $classes = $wpdb->get_results(
            $wpdb->prepare("SELECT `id`, `name` FROM {$wpdb->prefix}classes WHERE school_id = %d AND archived = %d ", "{$schoolId}", 0),
            ARRAY_A
        );
    }

    echo json_encode(['data' => $classes]);

    exit;
}
add_action('wp_ajax_wsm_find_classes_by_school', 'find_classes_by_school');

function wsm_find_student()
{

    global $wpdb;

    $results = [];

    $results['items'] = [];

    $results['more'] = false;

    $q = $_GET['q'];

    $students = $wpdb->get_results(
        $wpdb->prepare("SELECT u.ID, u.user_nicename,u.user_email,u.user_login 
        FROM {$wpdb->prefix}users AS u INNER JOIN {$wpdb->prefix}usermeta AS um
        ON u.ID = um.user_id 
        WHERE um.meta_key = 'wp_capabilities' AND um.meta_value LIKE %s AND (u.user_login LIKE %s OR u.user_email LIKE %s)", "%student%", "%{$q}%", "%{$q}%")
    );


    if (!empty($students) && !empty($q)) {

        foreach ($students as $student) {

            $info = $student->user_login;

            if (!empty($student->user_email)) {
                $info .= " | {$student->user_email}";
            }

            $o_class  = wushka_get_class(get_user_meta($student->ID, 'class', true));

            $classInfo = [];
            $schoolInfo = [];

            if (!empty($o_class)) {

                $classInfo  = ['id' => $o_class->id, 'name' => $o_class->name];

                $o_school = get_term_by('id', $o_class->school_id, 'school');

                if (!empty($o_school)) {

                    $schoolInfo  = ['id' => $o_school->term_id, 'name' => $o_school->name];
                }
            }



            $record = [
                'id' => $student->ID,
                'html' => $info,
                'text' => $info,
                'class_info' => $classInfo,
                'school_info' => $schoolInfo,
                'email' => $student->user_email,
                'username' => $student->user_login
            ];

            array_push($results['items'], $record);
        }
    }

    echo json_encode(['results' => $results]);
    exit;
}

add_action('wp_ajax_wsm_find_student', 'wsm_find_student');



function wsm_find_teacher()
{

    global $wpdb;

    $results = [];

    $results['items'] = [];

    $results['more'] = false;

    $q = $_GET['q'];

    $teachers = $wpdb->get_results(
        $wpdb->prepare("SELECT u.ID, u.user_nicename,u.user_email,u.user_login 
        FROM {$wpdb->prefix}users AS u INNER JOIN {$wpdb->prefix}usermeta AS um
        ON u.ID = um.user_id 
        WHERE um.meta_key = 'wp_capabilities' AND um.meta_value LIKE %s AND (u.user_login LIKE %s OR u.user_email LIKE %s)", "%teacher%", "%{$q}%", "%{$q}%")
    );


    if (!empty($teachers) && !empty($q)) {

        foreach ($teachers as $t) {

            $info = $t->user_login;

            if (!empty($t->user_email)) {
                $info .= " | {$t->user_email}";
            }

            $schoolInfo = [];

            $o_school = wp_get_object_terms($t->ID, 'school');

            if (!empty($o_school)) {

                $o_school = $o_school[0];

                $schoolInfo  = ['id' => $o_school->term_id, 'name' => $o_school->name];
            }




            $record = [
                'id' => $t->ID,
                'html' => $info,
                'text' => $info,
                'school_info' => $schoolInfo,
                'username' => $t->user_login,
                'email' => $t->user_email
            ];

            array_push($results['items'], $record);
        }
    }

    echo json_encode(['results' => $results]);
    exit;
}

add_action('wp_ajax_wsm_find_teacher', 'wsm_find_teacher');

function confirm_wsm_form()
{

    $wsmClass = $_POST['wsm_class'];
    $wsmSchool = $_POST['wsm_school'];
    $wsmStudent = $_POST['wsm_student'];
    $wsmClassName = $_POST['wsm_class_name'];
    $wsmSchoolName = $_POST['wsm_school_name'];

    $response = [

        'success' => false,
        'message' => 'Something went wrong. Please try again.',
        'transfer_info' => [

            'wsm_class' => $wsmClass,
            'wsm_school' => $wsmSchool,
            'wsm_student' => $wsmStudent
        ]
    ];

    if (isset($_POST['wsm_clr_st_data'])) {

        $response['transfer_info']['wsm_clr_st_data'] = true;
    }

    if (!empty($wsmClass) && !empty($wsmSchool) && !empty($wsmStudent)) {

        $classText = '';

        $userText = '';

        $activeText = '';

        $schoolText = '';

        $student = get_user_by('id', $wsmStudent);

        $active = get_user_meta($wsmStudent, 'active', true);

        if ($active == 0 || $active == '0') {

            $activeText .= " currently archived ";
        }

        $email = $student->user_email;

        $userLogin = $student->user_login;

        $userText .= "{$userLogin}";

        if (!empty($email)) {

            $userText .= " ({$email})";
        }



        $o_class  = wushka_get_class(get_user_meta($wsmStudent, 'class', true));

        if (!empty($o_class)) {

            $response['transfer_info']['current_class'] = $o_class->id;

            $classText .= " and currently is in class - <strong>{$o_class->name}</strong>";

            if ($o_class->archived == 1) {

                $classText .= " which is archived or deleted";
            }

            $o_school = get_term_by('id', $o_class->school_id, 'school');

            if (!empty($o_school)) {

                $schoolText .= " and school - <strong>{$o_school->name} ({$o_school->slug})</strong>";

                $response['transfer_info']['current_school'] = $o_school->term_id;
            }
        }

        $message = "Student <strong>{$userText}</strong>{$activeText}{$classText}{$schoolText}<br/>";
        $message .= "Do you want to transfer this student to School - <strong>{$wsmSchoolName}</strong> and Class - <strong>{$wsmClassName}</strong>";
        $message .= "<br/><br/><strong>CLICK OK TO CONFIRM</strong>";

        $response['success'] = true;
        $response['message'] = $message;
    }


    echo json_encode($response);
    exit;
}


add_action('wp_ajax_confirm_wsm_form', 'confirm_wsm_form');

function confirm_teacher_wsm_form()
{

    $wsmSchool = $_POST['wsm_school'];
    $wsmTeacher = $_POST['wsm_teacher'];
    $wsmSchoolName = $_POST['wsm_school_name'];

    $response = [

        'success' => false,
        'message' => 'Something went wrong. Please try again.',
        'transfer_info' => [

            'wsm_school' => $wsmSchool,
            'wsm_teacher' => $wsmTeacher
        ]
    ];

    if (isset($_POST['wsm_clr_st_data'])) {

        $response['transfer_info']['wsm_clr_st_data'] = true;
    }

    if (!empty($wsmSchool) && !empty($wsmTeacher)) {

        $userText = '';

        $activeText = '';

        $schoolText = '';

        $teacher = get_user_by('id', $wsmTeacher);

        $active = get_user_meta($teacher->ID, 'active', true);

        if ($active == 0 || $active == '0') {

            $activeText .= " currently archived ";
        }

        $email = $teacher->user_email;

        $userLogin = $teacher->user_login;

        $userText .= "{$userLogin}";

        // if (!empty($email)) {

        //     $userText .= " ({$email})";
        // }

        $o_school = wp_get_object_terms($teacher->ID, 'school');


        if (!empty($o_school)) {

            $o_school = $o_school[0];

            $schoolText .= " and school - <strong>{$o_school->name} ({$o_school->slug})</strong>";

            $response['transfer_info']['current_school'] = $o_school->term_id;
        }

        $message = "Teacher <strong>{$userText}</strong>{$activeText}{$schoolText}<br/>";
        $message .= "Do you want to transfer this teacher to School - <strong>{$wsmSchoolName}</strong>";
        $message .= "<br/>Also it will reset the teacher classes data if teachers transfers in different schools";
        $message .= "<br/><br/><strong>CLICK OK TO CONFIRM</strong>";

        $response['success'] = true;
        $response['message'] = $message;
    }

    echo json_encode($response);
    exit;
}

add_action('wp_ajax_confirm_teacher_wsm_form', 'confirm_teacher_wsm_form');


function updateStudentsSchools($userIds = [],$schoolId){

    global $wpdb;

    if(!empty($userIds)){

        $userIds = implode(",",$userIds);

        $wpdb->query(
            $wpdb->prepare(
                 "UPDATE {$wpdb->prefix}term_relationships AS tr 
                 INNER JOIN {$wpdb->prefix}term_taxonomy AS tt
                     ON tr.term_taxonomy_id = tt.term_id 
               SET tr.term_taxonomy_id = %d WHERE tt.taxonomy = 'school' AND tr.object_id IN($userIds)",
                 $schoolId
            )
         );

    }

}

function wsm_transfer_student()
{


    $tInfo = $_POST;

    $targetSchoolId = $tInfo['wsm_school'];

    $studentsData = $tInfo['studentsData'];

    $students = json_decode(stripslashes($studentsData));

    $userIds = [];

    if (!empty($students)) {
        foreach ($students as $student) {

            $studentId = $student->id;

            array_push($userIds,$studentId);

            $classId = $tInfo['wsm_class'];

            $o_class  = wushka_get_class(get_user_meta($studentId, 'class', true));

            $schoolId = false;

            if (!empty($o_class)) {

                $schoolId = $o_class->school_id;
            }

            update_user_meta($studentId, 'class', $classId);
            update_user_meta($studentId, 'active', 1);

            if (isset($tInfo['wsm_clr_st_data'])) {

                resetStudentData($studentId, $schoolId);
            }
        }

        updateStudentsSchools($userIds,$targetSchoolId);
    }



    $response = [
        'success' => true,
        'message' => ['info' => 'Students Transfer Successfully !', 'class' => 'alert alert-info']
    ];

    echo json_encode($response);

    exit;
}

add_action('wp_ajax_wsm_transfer_student', 'wsm_transfer_student');


function wsm_transfer_teacher()
{

    global $wpdb;

    $tInfo = $_POST;

    $response = [
        'success' => true,
        'message' => ['info' => 'Teacher transfer successfully.', 'class' => 'info']
    ];

    $teachersData = $tInfo['teachersData'];

    $teachers = json_decode(stripslashes($teachersData));

    $schoolId = (int)$tInfo['wsm_school'];

    if (!empty($teachers)) {
        foreach ($teachers as $teacher) {

            $oldSchoolId = false;

            $teacherId = (int)$teacher->id;

            $o_school = wp_get_object_terms($teacherId, 'school');

            if (!empty($o_school)) {

                $o_school = $o_school[0];

                $oldSchoolId = $o_school->term_id;
            }

            wp_set_object_terms($teacherId, $schoolId, 'school');

            $wpdb->delete($wpdb->prefix . 'classes_teachers', ['teacher_id' => $teacherId], ['%d']);

            if (isset($tInfo['wsm_clr_tc_data'])) {

                resetTeacherData($teacherId, $oldSchoolId);
            }
        }
    }

    echo json_encode($response);

    exit;
}


add_action('wp_ajax_wsm_transfer_teacher', 'wsm_transfer_teacher');


function upload_student_csv()
{

    global $wpdb;

    $fileTypeSupported = ['text/csv'];

    $response = [

        'success' => true,
        'message' => 'Transfer Successfully !',
        'v_errors' => [],
        'import_info' => [],
        'students' => []
    ];



    $studentFile = $_FILES['wsm_student_csv'];

    if (!in_array($studentFile['type'], $fileTypeSupported)) {

        $response['success'] = false;
        array_push($response['v_errors'], ['name' => 'wsm_student_csv', 'error' => 'Please select csv file.']);
    }



    if ($response['success'] === false) {

        echo json_encode($response);
        exit;
    }

    if (($handle = fopen($_FILES['wsm_student_csv']['tmp_name'], "r")) !== FALSE) {

        $row = 0;

        while (($student_data = fgetcsv($handle, 10000, ",")) !== FALSE) {

            if ($row > 0) {

                $studentIdentity = trim($student_data[0]);

                $studentInfo = $wpdb->get_results(
                    $wpdb->prepare("SELECT u.ID, u.user_nicename,u.user_email,u.user_login 
                    FROM {$wpdb->prefix}users AS u INNER JOIN {$wpdb->prefix}usermeta AS um ON u.ID = um.user_id    
                    WHERE um.meta_key = 'wp_capabilities' AND um.meta_value LIKE %s AND (u.user_login=%s OR u.user_email=%s)", "%student%", "{$studentIdentity}", "{$studentIdentity}")
                );

                if (!empty($studentInfo)) {

                    $student = $studentInfo[0];

                    $o_class  = wushka_get_class(get_user_meta($student->ID, 'class', true));

                    $classInfo = [];
                    $schoolInfo = [];

                    if (!empty($o_class)) {

                        $classInfo  = ['id' => $o_class->id, 'name' => $o_class->name];

                        $o_school = get_term_by('id', $o_class->school_id, 'school');

                        if (!empty($o_school)) {

                            $schoolInfo  = ['id' => $o_school->term_id, 'name' => $o_school->name];
                        }
                    }

                    array_push($response['students'], ['id' => $student->ID, 'class_info' => $classInfo, 'school_info' => $schoolInfo, 'username' => $student->user_login, 'email' => $student->user_email]);
                }
            }

            $row++;
        }   //end while
        fclose($handle);
    }

    if (empty($response['students'])) {

        $response['success'] = false;
        array_push($response['v_errors'], ['name' => 'wsm_student_csv', 'error' => 'No Student found in database !']);
    }


    echo json_encode($response);
    exit;
}
add_action('wp_ajax_upload_student_csv', 'upload_student_csv');



function upload_teacher_csv()
{

    global $wpdb;

    $fileTypeSupported = ['text/csv'];

    $response = [

        'success' => true,
        'message' => 'Transfer Successfully !',
        'v_errors' => [],
        'import_info' => [],
        'teachers' => []
    ];



    $teacherFile = $_FILES['wsm_teacher_csv'];

    if (!in_array($teacherFile['type'], $fileTypeSupported)) {

        $response['success'] = false;
        array_push($response['v_errors'], ['name' => 'wsm_teacher_csv', 'error' => 'Please select csv file.']);
    }



    if ($response['success'] === false) {

        echo json_encode($response);
        exit;
    }

    if (($handle = fopen($_FILES['wsm_teacher_csv']['tmp_name'], "r")) !== FALSE) {

        $row = 0;

        while (($teacher_data = fgetcsv($handle, 10000, ",")) !== FALSE) {

            if ($row > 0) {

                $teacherIdentity = trim($teacher_data[0]);

                $teacherInfo = $wpdb->get_results(
                    $wpdb->prepare("SELECT u.ID, u.user_nicename,u.user_email,u.user_login 
                    FROM {$wpdb->prefix}users AS u INNER JOIN {$wpdb->prefix}usermeta AS um ON u.ID = um.user_id    
                    WHERE um.meta_key = 'wp_capabilities' AND um.meta_value LIKE %s AND (u.user_login=%s OR u.user_email=%s)", "%teacher%", "{$teacherIdentity}", "{$teacherIdentity}")
                );

                if (!empty($teacherInfo)) {

                    $teacher = $teacherInfo[0];

                    $schoolInfo = [];

                    $o_school = wp_get_object_terms($teacher->ID, 'school');

                    if (!empty($o_school)) {

                        $o_school = $o_school[0];

                        $schoolInfo  = ['id' => $o_school->term_id, 'name' => $o_school->name];
                    }

                    array_push($response['teachers'], ['id' => $teacher->ID, 'school_info' => $schoolInfo, 'username' => $teacher->user_login, 'email' => $teacher->user_email]);
                }
            }

            $row++;
        }   //end while
        fclose($handle);
    }


    if (empty($response['teachers'])) {

        $response['success'] = false;
        array_push($response['v_errors'], ['name' => 'wsm_teacher_csv', 'error' => 'No Teacher found in database !']);
    }
    echo json_encode($response);
    exit;
}
add_action('wp_ajax_upload_teacher_csv', 'upload_teacher_csv');


function resetTeacherData($teacherId, $schoolId = false)
{

    global $wpdb;
    $bookmark_table = $wpdb->prefix . 'wushka_bookmarks';
    $school_events = $wpdb->prefix . 'wushka_school_events';

    $wpdb->delete($bookmark_table, ['user_id' => $teacherId], ['%d']);

    if ($schoolId) {
        $wpdb->delete($school_events, ['meta_value' => (string)$teacherId, 'school_id' => $schoolId], ['%s', '%d']);
    }
}





function wsm_transfer_class()
{

    global $wpdb;

    $className = sanitize_text_field($_POST['className']);

    $classId = sanitize_key($_POST['wsm_class']);

    $schoolId = sanitize_key($_POST['wsm_school']);

    $targetSchoolId = sanitize_key($_POST['wsm_school_target']);

    $classStatus = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}classes 
          WHERE school_id = %d AND `name` = %s AND archived = 0",
            $targetSchoolId,
            $className
        )
    );

    if($classStatus > 0){

        echo json_encode(['success'=> false,'message'=> "Class Name <strong>{$className}</strong> already Exists in Target School. Try class with different name."]);
        exit;
    }


    $wpdb->query(
        $wpdb->prepare(
            "UPDATE {$wpdb->prefix}classes 
          SET school_id = %d WHERE id = %d",
            $targetSchoolId,
            $classId
        )
    );

    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}classes_teachers 
           WHERE class_id = %d",
            $classId
        )
    );

    
    $wpdb->query(
        $wpdb->prepare(
             "UPDATE {$wpdb->prefix}term_relationships AS tr 
             INNER JOIN {$wpdb->prefix}term_taxonomy AS tt
                 ON tr.term_taxonomy_id = tt.term_id 
           SET tr.term_taxonomy_id = %d WHERE tt.taxonomy = 'school' AND tr.term_taxonomy_id = %d AND tr.object_id IN(SELECT user_id FROM {$wpdb->prefix}usermeta WHERE meta_key = 'class' AND meta_value = %d)",
             $targetSchoolId,
             $schoolId,
             $classId
        )
     );

    echo json_encode(['success'=> true]);

    exit;

    
}

add_action('wp_ajax_wsm_transfer_class', 'wsm_transfer_class');
