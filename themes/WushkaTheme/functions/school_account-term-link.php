<?php
/*
 * Add New School Account and Link to It's Term
 */
include $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
if (!defined('ABSPATH')) exit; // Exit if accessed directly

/* ----------- Generate School Accounts ----------- *
 * 	----- Procedure ------
 * 	1. Get All School Role Accounts
 *  2. Get All School Terms (TEST: do only a couple for testing)
 *  3. For Each Term:
 *  	3a. Does a School Account Have this Term Already?
 *  	---- IF YES, MOVE TO NEXT TERM ----
 *  	3b. Generate School Account UserName
 *  	3c. Generate Temp Password (plaintext & obscure)
 *  	3d.
*/
function generate_school_accounts( $i_user = NULL, $s_validate = NULL ) {
    global $wpdb;
	$return_data = array('status' => 'Begin', 'success' => 0, 'failed' => 0, 'loop' => 0, 'terms' => 0, 'users' => 0, 'linked' => 0);

	if ( ! user_can( $i_user, 'administrator' ) ) {
		$return_data['status'] = 'Invalid User';
		return $return_data;
	}

	if ( ! isset($s_validate) || ! wp_verify_nonce($s_validate, 'account_term_link_validation') ) {
		$return_data['status'] = 'Validation Failed';
		return $return_data;
	}

	//1. Get All School Role Accounts
	// updated Feb 2019 to prevent count_total performing slow query
	$a_user_args = array(
			'role' => 'school',
			'count_total' => false
	);
	$o_school_query = new WP_User_Query($a_user_args);  // args updated for slow query
	$a_school_users = NULL;

	if ( isset($o_school_query) && ! empty($o_school_query->results) ) {
		$a_school_users = $o_school_query->results;
	}

	$return_data['users'] = count($a_school_users);

	//2. Get All School Terms (TEST: do only a couple for testing)
	$a_term_args = array('orderby' => 'slug', 'order' => 'ASC', 'hide_empty' => false );
	$a_terms = get_terms('school', $a_term_args );

	if ( ! isset($a_terms) || empty($a_terms) ) {
		$return_data['status'] = 'No School Terms Found';
		return $return_data;
	}

	$return_data['terms'] = count( $a_terms );

	//3. For Each Term:
	foreach ($a_terms as $ix => $o_term ) {
		$return_data['loop']++;

		//3a. Does a School Account Have this Term Already?
		$b_hasTerm = FALSE;
		if ( isset($a_school_users) ) {
			foreach ( $a_school_users as $i_key => $o_user ) {
				if ( has_term( $o_term->term_id, 'school',  $o_user->ID ) ) {
					//This Term already has a school linked to it.
					$b_hasTerm = TRUE;
					break;
				}
			}
		}
		//Has a school account already been made for this term?
		if ( $b_hasTerm ) {
			$return_data['linked']++;
			//---- IF YES, MOVE TO NEXT TERM ----
			continue;
		}

		//3b. Generate School Account UserName
		$s_username = generate_unique_username($o_term->name);
		//3c. Generate Temp Password (plaintext & obscure)
		$s_password = generate_unique_password($s_username);

		$a_new_school = array(
			'user_login' 	=> $s_username,
			'user_pass'		=> $s_password,
			'role'			=> 'school'
		);

		error_log('---------- CREATING SCHOOL USER ----------');
		error_log('Username: '.$s_username);
		error_log('Password: '.$s_password);
		error_log('Term to Link to: '.$o_term->term_taxonomy_id);
		error_log('------------------------------------------');

		$i_new_school = wp_insert_user($a_new_school);

		if ( is_wp_error( $i_new_school ) ) {
			$return_data['failed']++;
		} else {
			$return_data['success']++;

			wp_set_object_terms($i_new_school, $o_term->term_taxonomy_id, 'school');

		 	update_user_meta($i_new_school, 'show_admin_bar_front', 'false');
		 	update_user_meta($i_new_school, 'narration', 'false');
			update_user_meta($i_new_school, 'quizzes', 'false');



			$o_new_user = get_user_by('id', $i_new_school);
            //Replaced with new insert after hash creation
            //update_user_meta($i_new_school, 'show_temp_pwd', $s_password);
            if ( user_can($o_new_user->ID, 'school') ) {

                $x_insert = $wpdb->insert(
                    $wpdb->prefix.'usermeta_temp',
                    array(
                        'id_hash' => $o_new_user->id_hash,
                        'show_temp_pwd' => $s_password
                    ),
                    array(
                        '%s',
                        '%s'
                    )
                );
                if ( $x_insert === FALSE ) {
                    error_log('FAILED TO INSERT show_temp_pwd for school user #'.$o_new_user->ID);
                }
            }

			$a_school_users[] = $o_new_user;
		}
	}

	error_log('-------------------- END SCHOOL USER CREATION LOOP ---------------------');
	$return_data['status'] = 'Procedure Fully Completed';
	return $return_data;
}

function generate_unique_username( $s_username = NULL ) {
	if ( ! isset($s_username) || empty($s_username) ) {
		return NULL;
	}

	//Get First two words of school name
	$a_names = explode(' ', $s_username);
	$s_name = preg_replace('/[^A-Za-z0-9\-]/', '', $a_names[0]);
	if ( isset($a_names[1]) && ! empty($a_names[1]) ) {
		$s_name .= preg_replace('/[^A-Za-z0-9\-]/', '', $a_names[1]);
	}

	$s_username = $s_name.'-'.rand(1000, 99999);

  	if (username_exists($s_username)) {
    	do {
            $s_oldName = explode("-", $s_username);
        	$s_username = $s_oldName[0]. '-' .rand(1000, 99999);
    	} while (username_exists($s_username));
    }

    if ( ! username_exists($s_username) ) {
    	return $s_username;
    }
	return NULL;
}

function generate_unique_password( $s_username = NULL ) {
	if ( ! isset($s_username) || empty($s_username) ) {
		return NULL;
	}

	$a_colour = array(
		'white', 'red', 'black', 'yellow', 'orange', 'blue', 'purple',
		'brown', 'violet', 'cyan', 'green', 'grey', 'magenta', 'pink'
	);

	$i_colour = rand(1,14) - 1;
	$s_colour = $a_colour[$i_colour];

	$a_name = explode('-', $s_username);

	//Generate Password
	$s_password = preg_replace('/[^A-Za-z0-9\-]/', '', $a_name[0]).'-'.$s_colour.'-'.rand(100, 999);

	return $s_password;
}
/* ----- EOF ----- */