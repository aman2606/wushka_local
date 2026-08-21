<?php
/*
 * Load complete school data
 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

$return     = array();
$return = $return['available'] = 'Failed';

// Only allow POST requests
if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
  error_log('not a POST');
  echo json_encode($return);
  exit();
}
// Make sure Content-Type is application/json 
$content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
error_log('content is ' . $content_type);
if (stripos($content_type, 'application/json') === false) {
  error_log('not json');
  echo json_encode($return);
  exit();
}
$body = json_decode(file_get_contents("php://input"), true);
error_log('request body ' . print_r($body, true));

if (is_array($body) && isset($body['school_id'])) {

  $id = $body['school_id'];
  error_log('processing school ' . $id);

  ini_set('auto_detect_line_endings', TRUE);

  /* Preparing for user meta */
  $allowed_shelves['id']   = 'all';
  $allowed_shelves['name'] = 'All Levels';

  $args  = array(
    'orderby' => 'slug',
    'order'   => 'ASC'
  );
  $terms = get_terms('reading-level', $args);

  foreach( $terms as $iidx => $term ) {
    $prepared_shelves[] = $term->slug;
  }
  
  $inputFileName = $_FILES['filename']['tmp_name'];
  $extension = pathinfo($_FILES['filename']['name'], PATHINFO_EXTENSION);

  if ($extension !== 'csv') {
    $return['available'] = 'Invalid';
    echo json_encode($return);
    exit();
  }

  $data = array();
  if (($handle = fopen($_FILES['filename']['tmp_name'], "r")) !== FALSE) {
    while (($school_data = fgetcsv($handle, 10000, ",")) !== FALSE) {
      switch ($school_data['0']) {
        case 'Teacher':
          $data[$school_data['0']][$school_data['1']]['firstname'] = $school_data['2'];
          $data[$school_data['0']][$school_data['1']]['lastname'] = $school_data['3'];
          $data[$school_data['0']][$school_data['1']]['email'] = $school_data['4'];
          break;
        case 'Student':
          $data[$school_data['0']][$school_data['1']]['firstname'] = $school_data['2'];
          $data[$school_data['0']][$school_data['1']]['lastname'] = $school_data['3'];
          break;
        case 'Class':
          $data[$school_data['0']][$school_data['1']]['classname'] = $school_data['2'];
          $data[$school_data['0']][$school_data['1']]['year'] = $school_data['3'];
          $data[$school_data['0']][$school_data['1']]['contents'] = $school_data['4'];
          break;
      }
    }
    fclose($handle);
  } else {
    $return['available'] = 'Invalid';
    echo json_encode($return);
    exit();
  }

  error_log('school data ' . print_r($data, true));
  if (!empty($data)) {
    $o_class  = wushka_get_class($id);
    $i_school = NULL;
    if (isset($o_class) && ! empty($o_class)) {
      $i_school = $o_class->school_id;
      if (! isset($i_school) || empty($i_school)) {
        error_log('Warning: No School ID found on Student Class;');
        error_log('Warning: Cannot Attache New Student Accounts to School Taxonomy Term');
      }
    }

    // foreach ($data as $idx => $student) {
    //   $student['A'] = cleanOnlySpecialChar($student['A']);
    //   $student['B'] = cleanOnlySpecialChar($student['B']);
    //   if (!empty($student['A']) && ! empty($student['B'])) {
    //     if ($student['A'] != 'firstname' && $student['A'] != 'lastname' && $student['B'] != 'firstname' && $student['B'] != 'lastname') {
    //       $return['available'] = $available;

    //       $license_key = strtoupper(substr($student['A'], 0, 1)) . strtoupper(substr($student['B'], 0, 1)) . '-' . rand(1000, 9999);
    //       $user_name    = $license_key;
    //       $user_pwd     = 'temppwd';
    //       $meta_lic_key = $license_key;
    //       $meta_lic_pwd = generateRandomString(1) . rand(100000, 999999);

    //       if (username_exists($user_name) ) {
    //         do {
    //           $oldName    = explode("-", $user_name);
    //           $ramdom_num = rand(1000, 9999);
    //           $user_name  = $oldName[0] . '-' . $ramdom_num;
    //         } while( username_exists($user_name) );
    //       }

    //       $userdata = array(
    //         'user_login' => $user_name,
    //         'user_pass'  => $user_pwd,
    //         'role'       => 'student',
    //         'first_name' => ucfirst($student['A']),
    //         'last_name'  => ucfirst($student['B'])
    //       );

    //       $user_id = wp_insert_user($userdata);

    //       add_user_meta($user_id, 'class', $id);
    //       add_user_meta($user_id, 'show_admin_bar_front', 'false');
    //       add_user_meta($user_id, 'license_key', $meta_lic_key);
    //       add_user_meta($user_id, 'license_pwd', $meta_lic_pwd);
    //       add_user_meta($user_id, 'show_user_pwd', $user_pwd);
    //       add_user_meta($user_id, 'allowed_shelves', $allowed_shelves);
    //       add_user_meta($user_id, 'prepared_shelves', $prepared_shelves);
    //       add_user_meta($user_id, 'narration', 'Yes');
    //       add_user_meta($user_id, 'quizzes', 'compulsory');
    //       add_user_meta($user_id, 'active', 1);

    //       if (isset($i_school) && ! empty($i_school)) {
    //         wp_set_object_terms($user_id, array(intval($i_school)), 'school', FALSE);
    //         clean_object_term_cache($user_id, 'school');
    //       }
    //     }
    //   }
    // }
  } else {
    $return['available'] = 'Empty';
  }
}
echo json_encode($return);
exit();
?>