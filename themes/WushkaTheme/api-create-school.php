<?php
/*
 * Load complete school data
 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
header('Content-type: application/json');

global $wpdb;
$return = array('processed' => 'failure');

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

  $term_args = array(
    'description' => $body['school_address'],
    'slug' => $id
  );
  error_log('checking school record');
  $term = get_term_by('slug', $id, 'school');
  if (!empty($term)) {
    error_log('school already exists -> updating');
    $term = wp_update_term($term->term_id, 'school', $term_args);
    if (is_wp_error($term)) {
      error_log('failed to update school ' . $id);
      $return['processed'] = 'failed to update school';
      echo json_encode($return);
      exit();
    }
  } else {
    error_log('creating new school record');
    $term = wp_insert_term($body['school_name'], 'school', $term_args);
    if (is_wp_error($term)) {
      error_log('failed to create school ' . $id);
      $return['processed'] = 'failed to create school';
      echo json_encode($return);
      exit();
    }
  }
  error_log('school ' . print_r($term, true));

  $school_options = array();
  $school_options['school_tz'] = $body['timezone'];
  $school_options['school_latitude'] = $body['latitude'];
  $school_options['school_longitude'] = $body['longitude'];
  $school_options['school_state'] = $body['state'];

  // check if program coordinator needs creating or not
  $qparams = array(
    'wp_capabilities',
    '%school%',
    $term['term_taxonomy_id']
  );
  $query = 'SELECT user_login from '.$wpdb->prefix.'users u join '.$wpdb->prefix.'term_relationships tr on tr.object_id = u.id join '.$wpdb->prefix.'usermeta um on um.user_id = u.id and um.meta_key = %s and um.meta_value like %s where tr.term_taxonomy_id = %d';
  $existing_user = $wpdb->get_var(
    $wpdb->prepare($query, $qparams)
  );

  if (isset($existing_user)) {
    error_log('existing coordinator found');
    $username = $existing_user;
    $password = '********';
  } else {
    error_log('creating program coordinator');
    $username = generate_unique_username($body['school_name']);
    $password = generate_unique_password();
    $email = $username . '@wushka.com.au';
  
    $new_user = array(
      'user_login' 	=> $username,
      'user_pass'		=> $password,
      'user_email' => $email,
      'role'			=> 'school'
    );
    error_log('genertating user ' . print_r($new_user, true));
    $user = wp_insert_user($new_user);
    if (is_wp_error($user)) {
      error_log('failed to create program coordinator ' . $id);
      echo json_encode($return);
      exit();
    }
    wp_set_object_terms($user, $term['term_taxonomy_id'], 'school');
    update_user_meta($user, 'show_admin_bar_front', 'false');
  
    $newuser = get_user_by('id', $user);
    $newuser->add_role('teacher');

    $school_options['school_hash'] = $newuser->id_hash;
  }

  update_option('taxonomy_' . $term['term_id'], $school_options, false);

  $return['processed'] = 'success';
  $return['coordinator_user'] = $username;
  $return['coordinator_pwd'] = $password;
}
echo json_encode($return);
exit();

function generate_unique_username( $username = NULL ) {
	$name = preg_replace('/[^A-Za-z0-9\-]\s*/','',ucwords(strtolower($username)));

	$username = $name .'-' . rand(1000, 99999);
  do {
    $username = $name . '-' . rand(1000, 99999);
  } while (username_exists($username));
  return $username;
}

function generate_unique_password() {
	$colours = array(
		'white', 'red', 'black', 'yellow', 'orange', 'blue', 'purple',
		'brown', 'violet', 'cyan', 'green', 'grey', 'magenta', 'pink'
	);

  return $colours[rand(1,14) - 1] . '-' . rand(1000, 9999);
}
?>