<?php
//Include Wordpress Config
include $_SERVER['DOCUMENT_ROOT'] . '/wushka_local/wp-config.php';
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

include_once 'reading-groups/class_reading-groups.php';
include_once 'bookmarks/class_my-bookmarks.php';

error_log( '-------------------- START MANAGE READING GROUPS AJAX ---------------------' );

if (!isset($_SESSION)) {
    session_start();
}

$reading_group_ajax = new Manage_Reading_Group_Ajax();
$ajax_return = $reading_group_ajax->run_ajax_function();

echo json_encode( $ajax_return );

error_log( '-------------------- END MANAGE READING GROUPS AJAX ---------------------' );
die();

class Manage_Reading_Group_Ajax {

	/*Reading Group Update: New Constants*/
	private $_o_user;
	private $_i_school;

	private $_a_groups;
	private $_i_group;

	private $_a_classes;
	private $_i_class;

	private $_a_levels;
	private $_i_level;

	private $_a_students;

	private $_a_books;
	private $_i_book;

	private $_c_rg;

	private $_a_return;

	private $_s_function;

	private $_x_return_data;
	private $_x_return_error;
	private $_i_error_id;
	private $_s_error_msg;

	private $_s_old_group;
	private $_x_book_ids;
	private $_i_paged;
	private $_i_total_pages;

	private $a_bookmarks;

	private $phases;

	private $_s_sound = NULL;

	public function __construct() {
		//Declare Return Data Array

		$this->_a_return = array(
			'data' => NULL,
			'msg' => NULL,
			'error' => 0,
		);

		//Declare Error ID

		//Declare Data Constants
		$this->_a_groups 	= array();
		$this->_i_group 	= NULL;

		$this->_a_new		= array();

		$this->_a_classes 	= array();
		$this->_i_class 	= NULL;

		$this->_a_levels 	= array();
		$this->_i_level 	= NULL;

		$this->_s_function = NULL;
		$this->_i_level = NULL;
		$this->_i_group = NULL;
		$this->_s_old_group = NULL;
		$this->_a_students = array();
		$this->_i_book = NULL;
		//Can Be INT or Array
		$this->_x_book_ids = NULL;

		$this->_i_paged = NULL;
		$this->_i_total_pages = 0;

		$this->_c_rg = new Reading_Groups();

		$phase_ids   = array();
		$a_args  = array(
			'orderby' => 'slug',
			'order'   => 'ASC'
		);
		$phase_terms = get_terms('phonics-phase', $a_args);
		foreach( $phase_terms as $idx => $o_term ) {
			$phase_ids[] = $o_term->term_id;
		}
		$this->phases = $phase_ids;
	}

	/* ---------------------------------------------------------------------
	 *
	 * 					  PRIMARY ROOT AJAX FUNCTION
	 *
	 *	Functionality For the Current Ajax Function Will be Run through
	 *	this engine and then return clean data back to browser.
	 *
	 * ---------------------------------------------------------------------
	 */
	public function run_ajax_function() {
		//Validate All Aspects of the Ajax Function
		if ( $this->validate_system() === TRUE ) {
			//Validation Successfull, Run Function
			error_log('Perform Ajax Function: '.$this->_s_function);
			$s_function = 'perform_function_'.$this->_s_function;
			//Verify Posted Ajax Function is a legitemate function
			$this->$s_function();
		}

		//Check Error System
		$this->generate_errors();

		//Load Clean Data For Return
		$ajax_return = $this->_a_return;

		return $ajax_return;
	}
	/* ---------------------------------------------------------------------
	 *
	 * 						ERROR FUNCTIONS
	 * Legend of error code:
	 * 0-9 		- Validation Error
	 * 10-19 	- Change Reading Group Error
	 * 20-29 	- Change Reading Level Error
	 * 30-39	- Add New Group Error
	 *
	 * ---------------------------------------------------------------------
	 */
	private function generate_errors() {
		$error_id = $this->_a_return['error'];
		//If No Error Has Occured, Return True and Store no error data
		if ( $error_id == 0 ) {
			return TRUE;
		} else if ( $error_id == 1 ) {
			$response = 'Validation Error: Failed to Connect to Database.';
		} else if ( $error_id == 2 ) {
			$response = 'Validation Error: Invalid User Data.';
		} else if ( $error_id == 3 ) {
			$response = 'Validation Error: Invalid POST Data.';
		} else if ( $error_id == 10 ) {
			$response =  'Change Reading Group Error: No Group ID stored.';
		} else if ( $error_id == 11 ) {
			$response =  'Change Reading Group Error: No Teacher ID Found on Student Query.';
		} else if ( $error_id == 20 ) {
			$response =  'Change Reading Level Error: No Level ID stored.';
		} else if ( $error_id == 21 ) {
			$response =  'Change Reading Level Error: No Resources Found in Reading Level Post Query';
		} else if ( $error_id == 22 ) {
			$response = 'Load Class Groups Error: No Class ID stored.';
		} else if ( $error_id == 22 ) {
			$response = 'Load Class Groups Error: No Class ID stored.';
		} else if ( $error_id == 22 ) {
			$response = 'Load Class Groups Error: No Class ID stored.';
		} else if ( $error_id == 30 ) {
			$response = 'Add New Group Error: No Reading Groups Found';
		} else if ( $error_id == 31 ) {
			$response = 'Add New Group Error: Reading Group name Already Exists';
		} else if ( $error_id == 32 ) {
			$response = 'Add New Group Error: Failed to update reading group meta data';
		} else if ( $error_id == 40 ) {
			$response = 'Rename Group Error: No Reading Groups Found';
		} else if ( $error_id == 41 ) {
			$response = 'Rename Group Error: Failed to update reading group meta data';
		} else if ( $error_id == 45 ) {
			$response = 'Delete Group Error: Failed to Remove reading group from aray';
		} else if ( $error_id == 46 ) {
			$response = 'Rename Group Error: Failed to update reading group meta data';
		} else if ( $error_id == 50 ) {
			$response = 'Add Book to Group Error: No Book/Group ID passed to Function';
		} else if ( $error_id == 51 ) {
			$response = 'Add Book to Group Error: Failed to Add Book to Reading Group';
		} else if ( $error_id == 52 ) {
			$response = 'Add Book to Group Error: Book Already exists in this Group';
		} else if ( $error_id == 53 ) {
			$response = 'Add Book to Group Error: Failed to Update Reading Group with New Book List';
		} else if ( $error_id == 55 ) {
			$response = 'Delete Book From Group Error: No Book/Group ID passed to Function';
		} else if ( $error_id == 56 ) {
			$response = 'Delete Book From Group Error: Failed to delete Book from Reading Group';
		} else if ( $error_id == 57 ) {
			$response = 'Delete Book From Group Error: Book does not exist in this Group';
		} else if ( $error_id == 58 ) {
			$response = 'Delete Book From Group Error: Failed to Update Reading Group with New Book List';
		} else if ( $error_id == 60 ) {
			$response = 'Archive Reading Group Books Error: Failed to Archive Selected Books from Selected Group';
		} else if ( $error_id == 61 ) {
			$response = 'Archive Reading Group Books Error: No Book/Group IDs were passed to Function';
		} else if ( $error_id == 65 ) {
			$response = 'Update Student Reading Group Error: No Book/Group IDs were passed to Function';
		} else if ( $error_id == 66 ) {
			$response = 'Update Student Reading Group Error: Meta Data Update Failed.';
		} else {
			$response = 'Ajax Error: Unknown Error Code: '.$error_id.'.';
		}
		//Load Correct Error Response into Class Constant
		$this->_a_return['msg'] = $response;
		return FALSE;
	}
	/* ---------------------------------------------------------------------
	 *
	 * 						VALIDATION FUNCTIONS
	 * ----- PROCESS -----
	 * 1. Validate DATABASE connection
	 * 2. Validate USER data
	 * 3. Validate POST data
	 * 4. Validate Current Function
	 * ---------------------------------------------------------------------
	 */
	/* ------------- Initialise Validation ------------- */
	private function validate_system() {
		/* --- STEP 1 --- */
		if ( $this->validate_connection() === FALSE ) {
			return FALSE;
		}
		/* --- STEP 2 --- */
		if ( $this-> validate_user_data() === FALSE ) {
			return FALSE;
		}
		/* --- STEP 3 --- */
		if ( $this-> validate_post_data() === FALSE ) {
			return FALSE;
		}

		//All Validations Passed, Return True.
		return TRUE;
	}

	/* ------------- Step 1 - Validate Database Connection ------------- */
	private function validate_connection() {
		$db = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
		if ( ! mysqli_connect_errno() ) {
			return TRUE;
		}

		error_log('/*** ERROR ***/ - Failed to Connect to Database:');
		error_log( mysqli_connect_error() );
		$this->_a_return['error'] = 1;
		return false;
	}
	/* ------------- Step 2 - Validate USER data ------------- */
	private function validate_user_data() {
		//Check 1 - Is User Logged In?
		if ( is_user_logged_in() ) {

			//Check 2 - User Hash ID Passed
			$s_hash = json_decode( stripcslashes( filter_input( INPUT_POST, 'hash_id' ) ), true);
			if ( ($this->validate($s_hash)) !== FALSE ) {
				//Check 3 - Get User ID From Hash
				$o_teacher = get_user_by_hash( $s_hash );
				if ( $o_teacher !== FALSE ) {
					//Check 4 - Double Check Passed ID matches WP current user ID
					$this->_o_user = $o_teacher;
					$this->_i_school = wushka_get_user_school($this->_o_user->ID);

					unset($o_teacher);
					return TRUE;
				}
			}
		}

		$this->_a_return['error'] = 2;
		return FALSE;
	}
	/* ------------- Step 3 - Validate POST data ------------- */
	private function validate_post_data() {
		//Check POST 1 - Is request method POST?
		if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
			//Check POST 2 - Does Security Validation Verify?
			$s_nonce = json_decode( stripcslashes( filter_input( INPUT_POST, 'hash_nonce' ) ), true);
			if ( wp_verify_nonce( $s_nonce, 'manage-reading-groups_'.$this->_o_user->id_hash ) ) {
				//Current Function Variable
				$s_function = json_decode( stripcslashes( filter_input( INPUT_POST, 'ajax_function' ) ), true);
				if ( ($this->validate($s_function)) !== FALSE ) {
					$this->_s_function = $s_function;
					//Check POST 3 - Validate the POST data for the Current Ajax Function
					if ( $this->validate_current_function() === TRUE ) {
						return TRUE;
					}
				}
			}
		}

		$this->_a_return['error'] = 3;
		return FALSE;
	}

	/* ------------ UTILITY FUNCTIONS ------------*/
	private function validate( $x_vars = array() ) {
		if ( ! $x_vars ) {
			return FALSE;
		}

		if ( ! isset( $x_vars ) || empty( $x_vars ) ) {
			error_log('Invalid Variable');
			return FALSE;
		}
		if ( is_array($x_vars) ) {
			foreach ( $x_vars as $i_key => $x_var ) {
				if ( ! isset( $x_var ) || empty( $x_var ) ) {
					error_log('Invalid Variable: '.$i_key );
					return FALSE;
				}
			}
		}

		return TRUE;
	}


	private function store_classes() {
    	$this->_i_class = NULL;
		$this->_i_school = wushka_get_user_school($this->_o_user->ID);
		$a_classes = wushka_get_classes($this->_i_school, $this->_o_user->ID);

		if ( ! empty($a_classes) ) {
			foreach($a_classes as $o_class){
				$this->_a_classes[] = $o_class->class_id;
			}
		}

	    //Is there a Stored Class?
        if ( isset( $_SESSION['class_id'] ) ) {
        	foreach( $this->_a_classes as $i_key => $i_class ) {
				if ( (int) $_SESSION['class_id'] == $i_class ) {
					$this->_i_class = $i_class;
				}
        	}
		}
		if ( ! isset( $this->_i_class ) ) {
			$this->_i_class = (int) $this->_a_classes[0];
			$_SESSION['class_id'] = (int) $this->_a_classes[0];
		}

		//error_log('Current Class: '. $this->_i_class);
		return TRUE;
    }

    private function store_groups() {
    	$this->_i_group = NULL;

		if ( isset($this->_i_class) ) {
			if ( ($a_groups = $this->_c_rg->get_groups('class', $this->_i_class)) !== FALSE ) {
				$this->_a_groups = $a_groups;

			    //Is there a Stored Reading Group?
		        if ( isset( $_SESSION['reading_group'] ) ) {
		        	foreach ( $this->_a_groups as $i_key => $o_group ) {
		                if ( $_SESSION['reading_group'] == $o_group->ID ) {
		                    $this->_i_group = $o_group->ID;
		                }
		            }
		        }

		        if ( ! isset( $this->_i_group ) ) {
		        	unset( $_SESSION['reading_group'] );
		        }
				//error_log('Current Group: '. $this->_i_group);
				return TRUE;
			}

		} else {
			error_log('Manage Reading Groups (get groups): No Class Found');
		}

		error_log('Manage Reading Groups: Reading Groups MISSING, No Reading Groups Meta Data Found.');
		return FALSE;
    }

    private function store_levels() {
    	$this->_i_level = NULL;
    	$this->_a_levels = array();
    	$a_terms = get_terms('reading-level', array('orderby' => 'slug', 'order' => 'ASC'));

        if ( ! is_wp_error($a_terms) ) {
            $this->_a_levels = $a_terms;

            //Is there a Stored Reading Level?
	        if ( isset( $_SESSION['reading_level'] ) ) {
	            foreach ( $this->_a_levels as $i_key => $o_level ) {
	                if ( $_SESSION['reading_level'] == $o_level->term_taxonomy_id ) {
	                    $this->_i_level = $_SESSION['reading_level'];
	                }
	            }
	        }
	        //error_log('Current Level: '. $this->_i_level);
            return TRUE;
        }

		error_log('Manage Reading Groups: Reading Levels MISSING, No Reading Level Terms Found.');
		return FALSE;
    }

    private function store_students($i_group = NULL) {
        global $wpdb;

    // updated Feb 2019 to prevent count_total performing slow query
		$args = array(
		    'role' => 'student',
        'count_total'   => false,
		    'meta_query' => array(
		        'relation' => 'AND',
		        0 => array(
		            'key' => 'class',
		            'value' => $this->_a_classes,
		            'compare' => 'IN'
		        ),
				1 => array(
					'key' => 'active',
					'value' => 1
				)
		    )
		);

		if ( isset($i_group) ) {
			$args['meta_query'][2] = array(
				'key' => 'my_reading_group',
				'value' => $i_group
			);
		}

        $o_student_query = new WP_User_Query($args);  // args updated for slow query

        if (!empty($o_student_query->results)) {
        	return $o_student_query->results;
        }

        return FALSE;
    }

    private function store_group_books() {
    	$this->_a_books = array();
    	if ( isset( $this->_i_group ) ) {
    		if ( ($a_books = $this->_c_rg->get_books($this->_i_group)) !== FALSE ) {
    			$this->_a_books = $a_books;
    		}
    	}

    	return TRUE;
    }

	/* ------------- Step 4 - Validate Current Function ------------- */
	private function validate_current_function() {
		if ( ! isset( $this->_s_function ) ) {
			return FALSE;
		}

		$s_function = 'validate_function_'.$this->_s_function;
		//Verify Posted Ajax Function is a legitemate function
		$b_isValid = $this->$s_function();

		return $b_isValid;
	}
	/* ------------- Current Function: Change Reading Group ------------- */
	private function validate_function_load_group() {
		//Valid Check 1 - New Group Key Exists?
		$i_id = json_decode( stripcslashes( filter_input( INPUT_POST, 'group_id' ) ), true);
		if ( ($this->validate($i_id)) === TRUE ) {
			$this->_i_group = $i_id;
			return TRUE;
		}

		error_log( 'Change Reading Group - Missing Parameters' );
		return FALSE;
	}
	/* ------------- Current Function: Change Reading Level ------------- */
	private function validate_function_load_level() {
		//Store Group ID
		$a_params = array(
			'group_id' 	=> json_decode( stripcslashes( filter_input( INPUT_POST, 'group_id' ) ), true),
			'level_id' 	=> json_decode( stripcslashes( filter_input( INPUT_POST, 'level_id' ) ), true),
			'level_page'=> json_decode( stripcslashes( filter_input( INPUT_POST, 'level_page' ) ), true)
		);
		if ( ($this->validate($a_params)) === TRUE ) {
			$this->_i_group = $a_params['group_id'];
			$this->_i_level = $a_params['level_id'];
			$this->_i_paged = $a_params['level_page'];
			$s_sound = json_decode( stripcslashes( filter_input( INPUT_POST, 'sound_filter' ) ), true );
			if ( ! empty( $s_sound ) ) {
				$this->_s_sound = sanitize_text_field( $s_sound );
			}
			return TRUE;
		}

		error_log( 'Change Reading Level - Missing Parameters' );
		return FALSE;
	}
	/* ------------- Current Function: Change Class Groups ------------- */
	private function validate_function_load_class_groups() {
		//Store Group ID
		$i_class = json_decode( stripcslashes( filter_input( INPUT_POST, 'class_id' ) ), true);

		if ( ($this->validate($i_class)) === TRUE ) {
			$this->_i_class = $i_class;
			return TRUE;
		}

		error_log( 'Load Class Groups - Missing Parameters' );
		return FALSE;
	}
	/* ------------- Current Function: Add New Reading Group ------------- */
	private function validate_function_new_group() {

		$s_group = json_decode( stripcslashes( filter_input( INPUT_POST, 'group_new_name'	) ), true);
		$s_group = sanitize_text_field($s_group);
		$i_class = json_decode( stripcslashes( filter_input( INPUT_POST, 'class_id'			) ), true);

		if ( ($this->validate(array($s_group, $i_class))) === TRUE ) {

			$this->_a_new['group_name'] 	= (string) trim(strtolower($s_group));
			$this->_a_new['class_id'] 	= (int) trim($i_class);

			return TRUE;
		}

		error_log( 'Add New Reading Group - Missing Parameters' );
		return FALSE;
	}
	/* ------------- Current Function: Rename Reading Group ------------- */
	private function validate_function_rename_group() {
		//Store Group ID
		$i_id 	= json_decode( stripcslashes( filter_input( INPUT_POST, 'group_id' ) ), true);
		$s_name = json_decode( stripcslashes( filter_input( INPUT_POST, 'group_name' ) ), true);

		if ( ($this->validate(array($i_id, $s_name))) === TRUE ) {

			$this->_a_new['ID'] = $i_id;
			$this->_a_new['group_name'] = trim(strtolower($s_name));

			$this->_i_group = $i_id;
			return TRUE;
		}

		error_log('Rename Reading Group - Missing Parameters');
		return FALSE;
	}
	/* ------------- Current Function: Rename Reading Group ------------- */
	private function validate_function_delete_group() {
		//Store Group ID
		$i_id = json_decode( stripcslashes( filter_input( INPUT_POST, 'group_id' ) ), true);
		if ( ($this->validate($i_id)) === TRUE ) {

			$this->_i_group = $i_id;
			return TRUE;
		}

		error_log( 'Delete Reading Group - Missing Parameters' );
		return FALSE;
	}
	/* ------------- Current Function: Add new book to Reading Group ------------- */
	private function validate_function_add_group_book() {
		//Store Group ID
		$i_id 	= json_decode( stripcslashes( filter_input( INPUT_POST, 'group_id' ) ), true);
		$i_book = json_decode( stripcslashes( filter_input( INPUT_POST, 'book_id' ) ), true);

		if ( ($this->validate(array($i_id, $i_book))) === TRUE ) {
			$this->_i_group = $i_id;
			$this->_i_book  = $i_book;

			return TRUE;
		}

		error_log( 'Add Book to Group - Missing Parameters' );
		return FALSE;
	}

	/* ------------- Current Function: Delete book from Reading Group ------------- */
	private function validate_function_delete_group_book() {
		//Store Group ID
		$i_id 	= json_decode( stripcslashes( filter_input( INPUT_POST, 'group_id' ) ), true);
		$i_book = json_decode( stripcslashes( filter_input( INPUT_POST, 'book_id' ) ), true);

		if ( ($this->validate(array($i_id, $i_book))) === TRUE ) {
			$this->_i_group = $i_id;
			$this->_i_book  = $i_book;

			return TRUE;
		}

		error_log( 'Delete Book from Group - Missing Parameters' );
		return FALSE;
	}

	/* ------------- Current Function: Archive Books in Group ------------- */
	private function validate_function_archive_books_in_group() {
		//Store Group ID
		$i_id 	= json_decode( stripcslashes( filter_input( INPUT_POST, 'group_id' ) ), true);
		$s_book = json_decode( stripcslashes( filter_input( INPUT_POST, 'book_id' ) ), true);

		if ( ($this->validate(array($i_id, $s_book))) === TRUE ) {

			$this->_i_group = $i_id;
			$this->_i_book  = $s_book;

			return TRUE;
		}

		error_log( 'Delete Book from Group - Missing Parameters' );
		return FALSE;
	}
/* ------------- Current Function: Archive Books in Group ------------- */
	private function validate_function_update_students() {
		$i_id 	= json_decode( stripcslashes( filter_input( INPUT_POST, 'group_id' ) ), true);
		$a_students = json_decode( stripcslashes( filter_input( INPUT_POST, 'student_data' ) ), true);

		//Store Group ID
		if ( ($this->validate(array($i_id, $a_students))) === TRUE ) {

			$this->_a_group['ID'] = $i_id;
			$this->_a_group['students'] = $a_students;

			$this->_a_students = $a_students;

			return TRUE;
		}

		error_log( 'Update Student Groups - Missing Parameters' );
		return FALSE;
	}
	/* ------------- Current Function: Load Book Details ------------- */
	private function validate_function_load_book() {
		//Store Group ID
		$i_id = json_decode( stripcslashes( filter_input( INPUT_POST, 'book_id' ) ), true);
		if ( ( $this->validate($i_id) ) === TRUE ) {

			$this->_i_book = $i_id;

			return TRUE;
		}

		error_log('Load Book - Missing Parameters');
		return FALSE;
	}

/* ------------------------------------------------------------------------------
 *
 * 							PERFORM AJAX FUNCTIONS
 *
 * ---------- CONTENTS ----------
 * 1. Perform Function: Change Group
 * 2. Perform Function: Change Level
 * 3. Perform Function: Add New Group
 * ----------------------------------
 * ------------------------------------------------------------------------------
 */

	/* ---------------------------------------------------------------------
	 *
	* 						PERFORM FUNCTION: CHANGE GROUP
	* Step 1 - Build Group Resource Data
	* Step 2 - Gather List of Students in this Group
	* Step 3 - Generate HTML for Students in this Group
	*
	* ---------------------------------------------------------------------
	*/
	public function perform_function_load_group() {
		if ( ! isset($this->_i_group) ) {
			error_log('Perform Function - Load Group: No Current Group Set.');
			$this->_a_return['error'] = 10;
			return FALSE;
		}
		//Store Current Group Into Session
		$_SESSION['reading_group'] = (int) $this->_i_group;

		//Data Load 1 - Load All Resources Stored in this Reading group
		$a_books 	= $this->build_reading_group_html();
		//Data Load 2 - Load Student Data For Those in this Group
		$a_students = $this->build_student_html();

		$s_title = $this->_i_group;
		$a_title = explode('-', $s_title);
		unset($a_title[0]);
		$s_title = implode(' ', $a_title);

		$a_group['title'] = $s_title;
		$a_group['books'] = implode('', $a_books);
		$a_group['students'] = $a_students;

		//Verify No Errors Occured During The Function
		if ( $this->generate_errors() === TRUE ) {
			//Store Clean Data For Broswer Return
			$this->_a_return['data'] = $a_group;
			return TRUE;
		}

		return FALSE;
	}

	/* ---------- Change Group - Step 1 - Build Data ---------- */
	private function build_reading_group_html() {
		if ( ! isset($this->_i_group) ) {
			error_log('Load Group: No Group ID');
			return $this->empty_group_item();
		}

		//Load Meta Data of Current Reading Group
	    $this->_a_books = array();
    	if ( ($a_books = $this->_c_rg->get_books($this->_i_group)) !== FALSE ) {
    		$this->_a_books = $a_books;
    	}

    	if ( empty($this->_a_books) ) {
    		error_log('Load Group: No Books');
			return $this->empty_group_item();
    	}

		$a_includes = array();
		foreach( $this->_a_books as $o_book) {
			if ( (int) $o_book->active == 1 ) {
				$a_includes[] = (int) $o_book->post_id;
			}
		}


		//$a_prepare_vars[] = 'publish';

		$a_args = array(
			'post_type' 		=> 'ebook',
			'post_status' 		=> 'publish',
			'posts_per_page' 	=> -1,
			'include' 			=> $a_includes,
			'orderby'			=> 'post__in',
		);

		if ( ! empty($a_includes) > 0 ) {
			$a_posts = get_posts($a_args);
		}

		if ( ! isset($a_posts) || empty($a_posts) ) {
			error_log('Load Group: No Posts');
			return $this->empty_group_item();
		}

		$this->store_levels();

		$a_group_html = array();
		$a_posts = $this->post_filter_per_class_licence($a_posts);
		//Check again if post is empty or not after filter
		if ( ! isset($a_posts) || empty($a_posts) ) {
			error_log('Load Group: No Posts');
			return $this->empty_group_item();
		}

		foreach ( $a_posts as $o_book ) {
			$a_group_html[] = implode('', $this->group_content_item($o_book));
		}

		return $a_group_html;
	}

	private function post_filter_per_class_licence($a_posts){
		$class_id = $this->get_class_id_from_group_id($this->_i_group);

		if(!$class_id){
			return $a_posts;
		}

		$licence = get_class_licence($class_id);

		$posts = array();
		foreach($a_posts as $a_post){
			$book_type = 'levelled';
			if(has_term('', 'phonics-phase', $a_post)){
				$book_type = 'decodable';
			}
			if($licence == 'Wushka Decodables'){
				if($book_type == 'decodable'){
					array_push($posts, $a_post);
				}
			}elseif($licence == 'Wushka Levelled'){
				if($book_type == 'levelled'){
					array_push($posts, $a_post);
				}
			}else{
				array_push($posts, $a_post);
			}
		}
		return $posts;
	}

	private function get_class_id_from_group_id($group_id){
		global $wpdb;
		$table_name = $wpdb->prefix . 'wushka_reading_groups';
		$sql = 'SELECT `class_id` FROM '. $table_name . ' WHERE ID = %d';

		$results = $wpdb->get_results(
			$wpdb->prepare($sql, $group_id)
		);

		if(isset($results) && !empty($results)){
			return $results[0]->class_id;
		}
		
		return;
		
	}

	private function group_content_item( $o_book ) {
		if ( ! isset($o_book) ) {
			return array();
		}

		//Determine Reading Level current book is from
		$s_level_slug 	= null;
		$s_level_name   = null;

		if ( isset( $this->_a_levels ) ) {
			foreach( $this->_a_levels as $o_level ) {
				if ( has_term( $o_level->term_id, 'reading-level', $o_book->ID ) ) {
					$s_level_slug = $o_level->slug;
					$s_level_name = $o_level->name;
				}
			}
		}

		//Determine if book is fiction or non-fiction
		$s_fiction = 'NF';
		$s_fic_title = 'Non-Fiction';
		if ( has_term('fiction', 'fiction', $o_book->ID ) ) {
			$s_fiction = 'F';
			$s_fic_title = 'Fiction';
		}

        $a_book[] = '<div class="list-group-item group-content-item book-item col-xsp-12 col-xs-6" id="book-' . $o_book->ID . '">';
        $a_book[] = '<input type="hidden" class="book-value resource" id="resource-' . $o_book->esiss_resource_id . '" />';
        $a_book[] = '<img class="post-image img-responsive" src="' . $o_book->post_image . '" alt=""/>';
        $a_book[] = '<div class="book-cover" title="' . $o_book->post_title . '">';
        	$a_book[] = implode('', $this->single_book_archive_button());
        	$a_book[] = implode('', $this->single_book_remove_button());
			$a_book[] = implode('', $this->single_book_details_button());
		$a_book[] = '</div>';
        $a_book[] = '<div class="info-wrap">';
          	$a_book[] = '<div class="book-level ' . $s_level_slug . '" title="' . $s_level_name . '"></div>';
           	$a_book[] = '<div class="book-genre" title="'.$s_fic_title.'">'.$s_fiction.'</div>';
        $a_book[] = '</div>';
        $a_book[] = '</div>';

        return $a_book;
	}

	private function empty_group_item() {
		$a_item[] = '<a href="#" class="list-group-item empty-group-item">'.
			'This reading group does not contain any books. Books can be added by selecting them from the reading levels.'.
		'</a>';

		return $a_item;
	}

	/* ---------- Change Group - Step 2 - Gather Students ---------- */
	private function get_students($active = FALSE) {
		if ( ! isset( $this->_i_group ) ) {
			$this->_a_return['error'] = 11;
			return FALSE;
		}

		//Generate List of Classes
    // updated Feb 2019 to prevent count_total performing slow query
		$a_args = array(
		    'role' => 'student',
        'count_total'   => false,
		    'meta_query' => array(
		        'relation' => 'AND',
		        0 => array(
		           'key' => 'my_reading_group',
							'value' => $this->_i_group
		        )
		    )
		);

		if ( isset($active) ) {
			$a_args['meta_query'][1] = array(
				'key' => 'active',
				'value' => 1
			);
		}

		$o_students = new WP_User_Query($a_args);  // args updated for slow query

		$a_students = array();


        $sort_last_name = usort($this->_a_students, function ($a, $b)
        {
            if($cmp = strnatcasecmp($a->last_name, $b->last_name)) {
                return $cmp;
            }

            return strnatcasecmp($a->first_name, $b->first_name);   
        });
		

		if ( ! empty( $o_students->results )) {
			foreach( $o_students->results as $o_student) {
				$a_students[] = $o_student;
			}
		}


		/* Sorting By Last Name */
    	$sort_last_name = usort($a_students, function ($a, $b)
        {
            if($cmp = strnatcasecmp($a->last_name, $b->last_name)) {
                return $cmp;
            }

            return strnatcasecmp($a->first_name, $b->first_name);   
        });


		$this->_a_students = $a_students;




		return TRUE;
	}
	/* ---------- Change Group - Step 3 - Build Students ---------- */
	private function build_student_html() {
		 //Generate the Reading Group Student List
        $a_students = $this->build_student_content();

        $a_return = array(
        	'current' => '<div class="list-group users-content-list">'.implode('', $a_students['current']).'</div>',
        	'assigned' => implode('', $a_students['assigned']),
        	'unassigned' => implode('', $a_students['unassigned']),
        );

        return $a_return;
	}
	private function build_student_content() {
		//Step 1 - Load Students for This Group
		$a_students = array(
			'current' => array(),
			'assigned' => array(),
			'unassigned' => array(),
		);


		if ( $this->get_students(TRUE) !== FALSE ) {
			if ( ! empty($this->_a_students) ) {
				foreach ( $this->_a_students as $idx => $o_user ) {
					$s_type = 'current';
					$a_students[$s_type][] = implode('', $this->user_content_item($o_user) );
		        }
		        return $a_students;
			}
		}

        return array(
        	'current' => array('<a href="#" class="list-group-item users-content-item">No students assigned to this group</a>'),
        	'assigned' => array(),
        	'unassigned' => array(),
        );

	}

	private function user_content_item( $o_user = NULL ) {
		if ( ! isset($o_user) ) {
			return array();
		}

		$s_name = ucwords($o_user->first_name).' '.ucwords($o_user->last_name);

		$a_user[] = '<a href="#" class="list-group-item users-content-item" id="student-'.$o_user->id_hash.'">';

		$rg_na_message = "No Reading level";
		if(!empty($o_user->reading_level)) {
			if(is_array($o_user->reading_level)){
				$reading_level_name = (!empty($o_user->reading_level['name'])) ? $o_user->reading_level['name'] : $rg_na_message;
			}else if(is_object($o_user->reading_level)){
				$reading_level_name = (!empty($o_user->reading_level->name)) ? $o_user->reading_level->name : $rg_na_message;
			}else{
				$reading_level_name = (!empty($o_user->reading_level)) ? $o_user->reading_level : $rg_na_message;
			}
			$a_user[] = $s_name . ": " . $reading_level_name;
		}else{
			$a_user[] = $s_name . ": " . $rg_na_message;
		}
	    $a_user[] = '</a>';

		return $a_user;
	}

	/* ---------------------------------------------------------------------
	 *
	* 						PERFORM FUNCTION: LOAD LEVEL
	* Step 1 - Build Reading Level Data
	*
	* ---------------------------------------------------------------------
	*/
	public function perform_function_load_level() {
		if ( $this->_i_level === NULL ) {
			$this->_a_return['error'] = 20;
			return FALSE;
		}

		if ( ! isset($this->_i_paged) ) {
			$this->_i_paged = 1;
		}

		$a_return_data = array();
		if ( ( $a_books = $this->_c_rg->get_books($this->_i_group)) !== FALSE ) {
			$this->_a_books = $a_books;
		}


		$o_user = wp_get_current_user();
        $c_bookmarks = new Wushka_Bookmarks($o_user->ID, 'teacher');
        $this->a_bookmarks = $c_bookmarks->get_book_list();

		if ( ( $a_level_data = $this->build_reading_level_content() ) !== FALSE ) {
			//Successfully Gathered New Group, Save Group Key into session
			$_SESSION[ 'reading_level' ] = $this->_i_level;
			$a_return_data = implode( '', $a_level_data );
		}

		if ( $this->generate_errors() === TRUE ) {
			$this->_a_return['data'] = $a_return_data;
			return TRUE;
		}

		return FALSE;
	}
	/* ---------- Change Level - Step 1 - Build Data ---------- */
	private function build_reading_level_content() {
		$a_components = $this->build_reading_level_html();

    	$a_level[] = '<div class="level-wrap books-wrap" data-id="books-page-1" data-paged="' . $this->_i_paged . '">';
        	$a_level[] = implode('', $a_components['books']);
        $a_level[] = '</div>';
        $a_level[] = '<div class="level-wrap buttons-wrap">';
		if ( ! empty($a_components['buttons']) ) {
	        $a_level[] = '<nav id="navigation">';
	        $a_level[] = '<ul class="pager pagination pagination-lg">';
	        $a_level[] = '<li id="navigation-previous">';
	        $a_level[] = '<a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
	        $a_level[] = '</li>';
	        $a_level[] = implode('', $a_components['buttons']);
	        $a_level[] = '<li id="navigation-next">';
	        $a_level[] = '<a href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>';
	        $a_level[] = '</li>';
	        $a_level[] = '</ul>';
	        $a_level[] = '</nav>';
		}
        $a_level[] = '</div>';

		return $a_level;
	}

	private function build_reading_level_html() {
        $a_return = array( 'books' => array(), 'buttons' => array() );

        if ( ! isset( $this->_i_level ) ) {
            $a_return['books'] = $this->empty_level_item();
            return $a_return;
        }

        $a_archived = array();

        $a_books 	= array();


        if ( ! empty($this->_a_books) ) {
        	foreach( $this->_a_books as $o_book ) {
	        	if ( (int) $o_book->active == 1 ) {
	        		$a_books[] = $o_book->post_id;
	        	} else {
	        		$a_archived[] = $o_book->post_id;
	        	}
        	}
        }

        //$limit = 6;
        $limit = -1;

        $page_limit = ($this->_i_paged - 1) * $limit;

		// $a_args = array(
		// 	'post_type' 		=> 'ebook',
		// 	'post_status' 		=> 'publish',
		// 	'posts_per_page' 	=> $limit,
		// 	'offset' 			=> $page_limit,
		// 	'exclude' 			=> $a_books,
		// 	'orderby'			=> 'date',
		// 	'tax_query' 		=> array(
		// 		'relation' => 'OR'
		// 		array(
		// 			'taxonomy' 	=> 'reading-level',
		// 			'terms'	  	=> $this->_i_level
		// 		),
		// 		array(
		// 			'taxonomy'	=> 'phonics-phase',
		// 			'terms'			=> $this->_i_level
		// 		)
		// 	)
		// );
		
		$levelterm = get_term_by('id', $this->_i_level, 'reading-level');
		$phaseterm = get_term_by('id', $this->_i_level, 'phonics-phase');
		// error_log('level term: ' . print_r($term, true));
		// error_log('phase term: ' . print_r($term, true));

		$library_taxonomy = '';
		if (!$levelterm) {
			$library_taxonomy = 'phonics-phase';
			// error_log('term is a phase term');
		}
		if (!$phaseterm) {
			$library_taxonomy = 'reading-level';
			// error_log('term is a level term');
		}

		if($this->_i_group == 'new'){
			$a_books = [];
		}
		

		$a_args = array(
			'post_type' 		=> 'ebook',
			'post_status' 		=> 'publish',
			'posts_per_page' 	=> $limit,
			'offset' 			=> $page_limit,
			'exclude' 			=> $a_books, 
			'orderby'			=> 'date',
			'tax_query' 		=> array(
				array(
					'taxonomy' 	=> $library_taxonomy,
					'terms'	  	=> $this->_i_level
				)
			)
		);
		if ($library_taxonomy == 'reading-level') {
			$a_args['tax_query']['relation'] = 'AND';
			$a_args['tax_query'][] = array(
					'taxonomy' => 'phonics-phase',
					'terms' => $this->phases,
					'operator' => 'NOT IN'
			);
		}
		if ( ! empty( $this->_s_sound ) ) {
			// Broaden tax_query to all sibling phases with the same parent (e.g. all "Phase 2 *")
			if ( $library_taxonomy === 'phonics-phase' ) {
				$current_term = get_term_by( 'term_taxonomy_id', $this->_i_level, 'phonics-phase' );
				if ( $current_term ) {
					$parts       = explode( ' ', $current_term->name );
					$prefix      = isset( $parts[1] ) ? $parts[0] . ' ' . $parts[1] : $parts[0];
					$all_phases  = get_terms( array( 'taxonomy' => 'phonics-phase', 'hide_empty' => false ) );
					$sibling_ids = array();
					foreach ( $all_phases as $t ) {
						if ( strpos( $t->name, $prefix ) === 0 ) {
							$sibling_ids[] = $t->term_id;
						}
					}
					if ( ! empty( $sibling_ids ) ) {
						$a_args['tax_query'][0]['terms'] = $sibling_ids;
					}
				}
			}

			// REGEXP sound filter — same phonemes in order, any separator
			$phonemes = preg_split( '/[\s,]+/', trim( $this->_s_sound ), -1, PREG_SPLIT_NO_EMPTY );
			if ( ! empty( $phonemes ) ) {
				$pattern = '^' . implode( '[, ]*', array_map( 'preg_quote', $phonemes ) ) . '$';
				$a_args['meta_query'] = array(
					array( 'key' => 'esiss_sounds', 'value' => $pattern, 'compare' => 'REGEXP' )
				);
			} else {
				$a_args['meta_query'] = array(
					array( 'key' => 'esiss_sounds', 'value' => $this->_s_sound, 'compare' => '=' )
				);
			}
		}
		// error_log('load_level ' . print_r($a_args, true));


		// Bookmark sorting
		global $wpdb;
		$bookmark = $wpdb->prefix . 'wushka_bookmarks';
		$current_user_id = get_current_user_id();

		$sql = 'SELECT `post_id` FROM '.$bookmark.' WHERE `user_id` = '. $current_user_id .' ORDER BY `date_added` DESC';
		// execute custom query
		$results = $wpdb->get_results($sql);
		// map results to a simple array of post IDs
		$bookmark_posts = array_map( function($post) {
			return $post->post_id;
		}, $results );
		// prevent empty array
		$bookmark_posts = count($bookmark_posts) ? $bookmark_posts : array(-1); 

		//If bookmark is not empty then sort with bookmark
		if($bookmark_posts)
		{
			
			//bookmark_post array=>string to int
			$bookmark_array = array();
			foreach($bookmark_posts as $each_number)
			{
				$bookmark_array[] = (int) $each_number;
			}
			
			//Get post array
			$post_list =  get_posts($a_args);
			$posts = array(); 
			foreach ( $post_list as $post ) {
				$posts[] += $post->ID;
			}


			//Removing bookmark ids from post
			$post_id = \array_diff($posts, $bookmark_array);
			//Removing excluding post id from bookmark id
			$bookmark_id = \array_diff($bookmark_array, $a_books);

			//Merging both bookmark - post id 
			$post_array = array_merge($bookmark_id, $post_id);

			//Adding keys and values to args
			$a_args['post__in'] = $post_array;
			$a_args['orderby'] = array(
				'post__in'		=> 'ASC',
				'date'			=> 'DESC',
			);

			//var_dump($a_books);die;
		}

		//Bookmark sorting ends here


		$a_posts = get_posts($a_args);
		//var_dump($a_posts);die;
        if ( ! isset( $a_posts ) || empty($a_posts)) {
            //Level is Empty
        	$a_return['books'] = $this->empty_level_item();
            return $a_return;
        }

        $a_return['buttons'] = $this->level_pagination($limit, $a_books);

        //Load Meta Data of Current Reading Group
        $a_level_books = array();

        if ( $a_posts !== NULL && ! empty($a_posts) ) {
            foreach ( $a_posts as $o_book ) {
                $b_archive = ( in_array($o_book->ID, $a_archived) ) ? TRUE : FALSE;
                $b_bookmarked = ( in_array($o_book->ID, $this->a_bookmarks) ) ? TRUE : FALSE;
                $a_level_books[] = implode('', $this->level_content_item($o_book, $b_archive, $b_bookmarked) );
            }
        }

    	$a_return['books'] = $a_level_books;

        return $a_return;
	}

    private function level_content_item( $o_book = NULL, $b_archive = FALSE, $b_bookmarked = FALSE ) {
    	if ( ! isset($o_book) ) {
    		return array();
    	}

    	$arr = array("fiction");
        $s_value = null;
        foreach($arr as $id =>$taxonomy)
        {
            $index = get_taxonomy($taxonomy);
            $args = array('orderby' => 'slug', 'order' => 'ASC');
            $terms = get_terms($taxonomy, $args);
            $term_option =null;
            foreach ($terms as $id => $term){
                $s_taxonomy = $term->taxonomy;
                $s_term = $term->slug;
                if(has_term($s_term,$s_taxonomy,$o_book->ID))
                {
                    $term_option .= $s_term;
                }

            }
            $s_value .= 'data-'.$s_taxonomy.'="'.$term_option.'"';
        }





   		$a_item[] = '<div class="level-content-item book-item col-xsp-12 col-xs-6 col-md-4" id="book-' . $o_book->ID . '"'.$s_value.' >';
        $a_item[] = '<input type="hidden" class="book-value resource" id="resource-' .$o_book->esiss_resource_id. '" />';
        $a_item[] = '<img class="post-image img-responsive" src="' .$o_book->post_image. '" alt="" />';
        $a_item[] = '<div class="book-cover">';
        if ( $b_archive === TRUE ) {
        	$a_item[] = '<button type="button" data-id="book-add" class="btn btn-small btn-archive-retrieve" title="Retrieve this Book from the group archive"><span class="glyphicon glyphicon-folder-plus"></span></button>';
        } else {
        	$a_item[] = '<button type="button" data-id="book-add" class="btn btn-small btn-add-book" title="Add this Book to your current Reading Group"><span class="glyphicon glyphicon-plus"></span></button>';
        }

        //Show Bookmarked Resources
        if ( $b_bookmarked ) {
            $a_item[] = '<button type="button" class="btn btn-small btn-bookmark" title="You have bookmarked this resource"><i class="glyphicon glyphicon-star starred"></i></button>';
        }


       	$a_item[] = '<button type="button" data-id="book-view" class="btn btn-small btn-view-book" data-toggle="modal" data-target="#reading-group-modal" title="View additional details about this book"><span class="glyphicon glyphicon-search"></span></button>';
        $a_item[] = '</div>';
        $a_item[] = '</div>';

		return $a_item;
    }

    private function level_pagination( $i_limit = -1,  $a_books = array() ) {
    	$a_args = array(
			'post_type' 		=> 'ebook',
			'post_status' 		=> 'publish',
			'posts_per_page' 	=> -1,
    		'exclude' 			=> $a_books,
    		'tax_query' 		=> array(
    			array(
    				'taxonomy' 	=> 'reading-level',
    				'terms'	  	=> $this->_i_level
    			)
    		)
		);

		$a_total = get_posts($a_args);

        $i_total = count($a_total);
        unset($a_total);

        $i_count = $i_total / $i_limit;
        $i_count = ceil($i_count);

        $a_paged = array();

        for ($ii = 1; $ii <= $i_count; $ii++) {
            $s_end = null;
            if ( $ii == 1 ) {
                $s_end = 'first';
            } else if ( $ii == $i_count ) {
                $s_end = 'last';
            }
            $s_active = ( $ii == $this->_i_paged ) ? 'active' : null;
            $a_paged[] = '<li class="level-pages ' . $s_active .' '. $s_end . '" id="page-' . $ii . '"><a href="#">' . $ii . '</a></li>';
        }

        return $a_paged;
    }

	private function empty_level_item() {
		$a_item[] = '<a href="#" class="level-content-item empty-empty-item">'.
			'Select a Reading Level Colour to start allocating books to a Group'.
		'</a>';

		return $a_item;
	}

	//Excerpt Post Content
	public function excerpt_post_content($post_content = null) {
		if ( $post_content == null ) {
			return NULL;
		}
		$word_limit = 15;

		$s_post_ecerpt = NULL;
		$a_post_excerpt = array();
		$a_post_content = explode(' ', $post_content);
		foreach($a_post_content as $i_key => $word ) {
			if ($i_key < $word_limit ) {
				$a_post_excerpt[] = $word;
			}
		}

		$s_post_ecerpt = implode(' ', $a_post_excerpt);

		return $s_post_ecerpt;
	}

	/* ---------------------------------------------------------------------
	 *
	 *					PERFORM FUNCTION: LOAD CLASS GROUPS
	 * Step 1 - Load Groups
	 * Step 2 - Display First Group?
	 * Step 3 - Return Group Menu and Group Content Data(if step2 = TRUE)
	 *
	 * ---------------------------------------------------------------------
	 */
	private function perform_function_load_class_groups() {
		if ( ! isset($this->_i_class) ) {
			$this->_a_return['error'] = 22;
			return FALSE;
		}

		if ( ($a_groups = $this->_c_rg->get_groups('class', $this->_i_class) ) === FALSE ) {
			$a_groups = array();
		}

		$this->_i_group = $a_groups[0]->ID;
		$this->_a_return['data']['groups'] = implode('', $this->group_menu_items($a_groups));
		return TRUE;

	}

    private function group_menu_items( $a_groups = array()) {
    	$a_items = array();
    	$a_group_menu = array();

    	//Loop Through Reading Groups and Store Data and HTML Output in Array
        if ( ! empty( $a_groups ) ) {
        	foreach ( $a_groups as $i_key => $o_group) {
        		$a_group = array(
					'ID' 		 => $o_group->ID,
					'group_name' => $o_group->group_name,
					'class_id' 	 => $o_group->class_id,
        			'active'	=> NULL
				);
	            $a_items[] = implode('', $this->group_menu_item($a_group));
        	}
        } else {
			$_SESSION['reading_group'] = NULL;
	        $this->_i_group = NULL;
        }

        $a_group_menu[] = implode('', $this->build_group_btn_add());
       	$a_group_menu[] = implode('', $a_items);

        return $a_group_menu;
    }

    public function build_group_btn_add() {
        //Add 'Create' Button to menu
        $a_add[] = '<a href="#" class="list-group-item list-reading-group" id="reading-group-new" data-toggle="modal" data-target="#reading-group-modal" title="Create a new Group">';
        	$a_add[] = '<i class="glyphicon glyphicon-plus"></i>';
			$a_add[] = ' Create New';
        $a_add[] = '</a>';

        return $a_add;
    }

	/* ---------------------------------------------------------------------
	 *
	 *					PERFORM FUNCTION: ADD NEW GROUP
	 *
	 * ---------------------------------------------------------------------
	 */
	private function perform_function_new_group() {
		$x_group = $this->_c_rg->create_group(
			$this->_a_new['group_name'],
			$this->_a_new['class_id']
		);

		if ( $x_group ) {
			$a_group = array(
				'ID' 		 => $x_group,
				'group_name' => $this->_a_new['group_name'],
				'class_id' 	 => $this->_a_new['class_id'],
				'active'	 => NULL
			);

			if ( ($a_item = $this->group_menu_item($a_group)) !== FALSE ) {
				$this->_a_return['data']['new_group'] = implode('', $a_item);
				return TRUE;
			}
		}

		error_log('New Group Failed to be Created');
		return FALSE;
	}

	private function group_menu_item( $a_group = array() ) {
		if ( ! empty($a_group) ) {
			$s_title = ucwords($a_group['group_name']);

			$a_item[] = '<a href="#" id="reading-group-'.$a_group['ID'].'" class="list-group-item group-menu-item '.$a_group['active'].'" title="'.$s_title.'">';
			$a_item[] = $s_title;
			$a_item[] = '</a>';

			return $a_item;
		}

		return FALSE;
	}

	/* ---------------------------------------------------------------------
	 *
	 *					PERFORM FUNCTION: RENAME GROUP
	 * Step 1 - Verify New Group Name is Unique for this Teacher
	 * Step 2 - Add New Group to Teacher Meta
	 *
	 * ---------------------------------------------------------------------
	 */
	private function perform_function_rename_group() {
		if ( ($a_group = $this->_c_rg->get_groups('group', $this->_a_new['ID'])) === FALSE ) {
			error_log('Rename Group: No Group Found');
			return FALSE;
		}

		//Double Check New Name is same as original
		$s_new_name = trim(strtolower($this->_a_new['group_name']));
		$s_old_name = trim(strtolower($a_group[0]->group_name));

		$s_test_new = str_replace(' ', '-', $s_new_name);
		$s_test_old = str_replace(' ', '-', $s_old_name);

		if ( $s_test_new != $s_test_old ) {
			if ( $this->_c_rg->edit_group( $this->_a_new['ID'], 'group_name', $s_new_name ) !== FALSE ) {
				$this->_a_return['data'] = array(
					'new_name' => ucwords($s_new_name)
				);
				return TRUE;
			}
		}

		$this->_a_return['error'] = 40;
		return FALSE;
	}

	/* ---------------------------------------------------------------------
	 *
	 *					PERFORM FUNCTION: DELETE GROUP
	 * Step 1 - Verify New Group Name is Unique for this Teacher
	 * Step 2 - Add New Group to Teacher Meta
	 *
	 * ---------------------------------------------------------------------
	 */
	private function perform_function_delete_group() {
		if ( ($a_group = $this->_c_rg->get_groups('group', $this->_i_group)) === FALSE ) {
			error_log('Delete Group: No Group Found');
			return FALSE;
		}

		if ( $this->_c_rg->delete_group($this->_i_group) !== FALSE ) {
			unset($_SESSION['reading_group']);

    // updated Feb 2019 to prevent count_total performing slow query
		$args = array(
			    'role' => 'student',
					'count_total'   => false,
			    'meta_query' => array(
			        'relation' => 'AND',
			        0 => array(
						'key' => 'my_reading_group',
						'value' => $this->_i_group
					)
			    )
			);

	        $o_students = new WP_User_Query($args);  // args updated for slow query

	        if ( ! empty($o_students->results)) {
	        	foreach($o_students->results as $o_student ) {
					$this->_c_rg->user_meta($o_student->ID, NULL);
	        	}
	        }

			return TRUE;
		}

		$this->_a_return['error'] = 45;
		return FALSE;
	}

	/* ---------------------------------------------------------------------
	 *
	*				PERFORM FUNCTION: ADD BOOK TO READING GROUP
	* Step 1 - Get Current Reading Group
	* Step 2 - Check Book does not exist in group
	* Step 3 - Add Book ID to group list
	*
	* ---------------------------------------------------------------------
	*/
	private function perform_function_add_group_book() {
		if ( ! isset($this->_i_book, $this->_i_group) ) {
			$this->_a_return['error'] = 50;
			return FALSE;
		}

		$o_edit_book = NULL;
		if ( ($a_books = $this->_c_rg->get_books($this->_i_group)) !== FALSE ) {
			foreach( $a_books as $o_book ) {
				if ( (int)$o_book->post_id == (int)$this->_i_book ) {
					$o_edit_book = $o_book;
					break;
				}
			}
		}

		$i_new = NULL;
		//Found Matching Book in Group
		if ( isset($o_edit_book) ) {
			//Inactive book: Reactive/Unarchive
			if ( (int)$o_book->active == 0 ) {
				if ( ($this->_c_rg->edit_book($o_edit_book->ID, 'active', '1')) !== FALSE ) {
					$i_new = $o_edit_book->post_id;
				} else {
					error_log('Could Not Update Book ROW');
					$this->_a_return['error'] = 52;
					return FALSE;
				}
			}
		} else {
			//Add New Book to Group
			if ( ($x_new = $this->_c_rg->create_book($this->_i_group, $this->_i_book)) !== FALSE ) {
				$i_new = $this->_i_book;
			} else {
				error_log('Could Not Create Book ROW');
				$this->_a_return['error'] = 52;
				return FALSE;
			}
		}

		if ( $this->generate_errors() === TRUE ) {
			$this->store_levels();
			$o_new = get_post($i_new);
			$this->_a_return['data'] = implode('', $this->group_content_item($o_new));
			return TRUE;
		}

		$this->_a_return['error'] = 51;
		return FALSE;
	}

    private function single_book_details_button() {
		$a_item[] = '<button type="button" class="btn btn-small" data-id="book-view" data-toggle="modal" data-target="#reading-group-modal" title=" View additional details about this book">';
		$a_item[] = '<i class="glyphicon glyphicon-search"></i>';
		$a_item[] = '</button>';
		return $a_item;
	}

	private function single_book_remove_button() {
		$a_item[] = '<button type="button" class="btn btn-small" data-id="book-delete" title="Delete this book from the group">';
		$a_item[] = '<i class="glyphicon glyphicon-remove-2"></i>';
		$a_item[] = '</button>';
		return $a_item;
    }

	private function single_book_archive_button() {
    	$a_item[] = '<button type="button" class="btn btn-small" data-id="book-archive" title="Archive this book">';
		$a_item[] = '<i class="glyphicon glyphicon-folder-flag"></i>';
		$a_item[] = '</button>';
		return $a_item;
    }


	/* ---------------------------------------------------------------------
	 *
	*				PERFORM FUNCTION: DELETE BOOK FROM READING GROUP
	* Step 1 - Get Current Reading Group
	* Step 2 - Check Book does exist in group
	* Step 3 - Remove Book ID from group list
	*
	* ---------------------------------------------------------------------
	*/
	private function perform_function_delete_group_book() {
		if ( ! isset( $this->_i_book, $this->_i_group ) ) {
			$this->_a_return['error'] = 55;
			return FALSE;
		}

		error_log('Deleting Group Book');
		error_log('Book ID: '. $this->_i_book );
		error_log('Group ID: '. $this->_i_group);

		//Verify Passed Book ID is in group
		if ( ($a_group = $this->_c_rg->get_books($this->_i_group) ) !== FALSE ){
			foreach( $a_group as $i_key => $o_book ) {
				if ( (int)$o_book->post_id == (int)$this->_i_book ) {
					if ( ($x_return = $this->_c_rg->delete_book($o_book->ID)) !== FALSE ) {
						error_log('Book '.$this->_i_book.' Deleted From Group '.$this->_i_group);
						$this->_a_return['data'] = $this->_i_book;
						return TRUE;
					} else {
						error_log('Failed to delete book '.$this->_i_book.' from Group '.$this->_i_group);
						$this->_a_return['error'] = 58;
						return FALSE;
					}
				}
			}
		}

		$this->_a_return['error'] = 56;
		return FALSE;
	}

	private function get_teacher_reading_groups() {
		$a_reading_groups = get_user_meta( $this->_o_user->ID, 'reading_group', true );

		if ( ! is_array($a_reading_groups) || empty( $a_reading_groups ) ) {
			$a_reading_groups = FALSE;
			error_log('Manage Reading Groups: Reading Groups MISSING, No Reading Groups Meta Data Found.');
		}

		return $a_reading_groups;
	}

	/* ---------------------------------------------------------------------
	*
	* 		PERFORM FUNCTION: Update Student Reading Groups
	* Step 1 - Loop Through Array of Students
	* Step 2 - Update reading group data to
	* Step 3 - Generate HTML for Students in this Group
	*
	* ---------------------------------------------------------------------
	*/
	private function perform_function_update_students() {
		if ( $this->_a_students == NULL ) {
			$this->_a_return['error'] = 65;
			return FALSE;
		}

		foreach( $this->_a_students as $idx => $student ) {
			error_log( 'Student Hash = '.$student['student'].' Change to Group: '.$student['key'] );
			//Get User ID From Hash
			if ( ($o_student = get_user_by_hash( $student['student'] ) ) !== FALSE ) {
				$i_student_id = $o_student->ID;

				if ( ! isset($student['key']) || empty($student['key'] ) ) {
					error_log('No Key Found: Unassign Student From Current Group');
					//Delete Reading Group MetaData
					if ( delete_user_meta( $i_student_id, 'my_reading_group') === FALSE ) {
						error_log('delete user meta failed');
						$this->_a_return['error'] = 66;
						return FALSE;
					}

				} else {
					//Update User Reading Group With New Group Key
					error_log('Key Found: Update Student with new group: '.$student['key']);
					$x_updated = update_user_meta( $i_student_id, 'my_reading_group', $student['key'] );
					if ( $x_updated === FALSE ) {
						//Failed to Upload new group
						error_log('update user meta failed');
						$this->_a_return['error'] = 66;
						return FALSE;
					}
				}
			} else {
				error_log('Could Not Find Any Student With this Hash Key :'.$student['student']);
			}
		}
		error_log('all students updated');
		//All Changes Made, Load New Student Data
		$this->change_group_load_group_students();
		$a_student_data = $this->build_student_content();
		$this->_a_return['data'] = array(
			'student_data' => $a_student_data
		);
		return TRUE;
	}


	/* ---------------------------------------------------------------------
	 *
	 *				PERFORM FUNCTION: ARCHIVE BOOKS FROM READING GROUP
	 * Step 1 - Determine If x books or ALL books are being archived
	 * Step 2 - Check Book(s) exist in group
	 * Step 3 - Remove selected book(s) from group metadata and update.
	 * Step 4 - Create/Add books to Groups Archived metadata and update
	 *
	 * ---------------------------------------------------------------------
	 */
	private function perform_function_archive_books_in_group () {
		if ( ! isset($this->_i_group, $this->_i_book) ) {
			$this->_a_return['error'] = 61;
			return FALSE;
		}

		if ( ($a_group = $this->_c_rg->get_books($this->_i_group) ) !== FALSE ){
			foreach( $a_group as $i_key => $o_book ) {
				if ( $o_book->active == '0' ) { continue; }
				if ( $this->_i_book == 'all' || (int)$o_book->post_id == (int)$this->_i_book ) {
					if ( ($x_return = $this->_c_rg->edit_book($o_book->ID, 'active', 0)) !== FALSE ) {
						error_log('Book '.$this->_i_book.' Archived in Group '.$this->_i_group);
					} else {
						error_log('Failed to archive book '.$this->_i_book.' from Group '.$this->_i_group);
						$this->_a_return['error'] = 58;
						return FALSE;
					}
				}
			}
		}

		if ( $this->generate_errors() === TRUE ) {
			error_log('Archive Successful');
			return TRUE;
		}

		$this->_a_return['error'] = 58;
		error_log('Archive Meta Data Failed to Update');
		return FALSE;
	}


	function perform_function_load_book() {
		if ( ! isset($this->_i_book) ) {
			$this->_a_return['error'] = 55;
			return FALSE;
		}

		error_log('----- Loading Book Data -----');
		$o_book = get_post($this->_i_book);

		$post_permalink = get_permalink($o_book->ID);
		//$res_id = get_post_meta($o_book->ID, 'esiss_resource_id', TRUE);
		$s_img = get_post_meta($o_book->ID, 'post_image', TRUE);

		$a_book[] = '<div class="window-content book-item" id="book-'.$o_book->ID.'">';
        $a_book[] = '<div class="col-xs-12 col-sm-12 no-padding">';
                $a_book[] = '<h3 class="book-title">'.$o_book->post_title.'</h3>';
        		$a_book[] = '<div class="col-xs-12 col-sm-3 no-padding">';
                    $a_book[] = '<div class="book-img">';
                        $a_book[] = '<img class="post-image" src="'.$s_img.'" alt="" />';
                    $a_book[] = '</div>';
                $a_book[] = '</div>';
                $a_book[] = '<div class="col-xs-12 col-sm-9 no-padding">';
                    $a_book[] = '<div class="col-xs-12 col-sm-12">';
                        $a_book[] = '<div class="book-excerpt">';
                            $a_book[] =  $this->load_book_blurb($o_book);
                        $a_book[] = '</div>';
                    $a_book[] = '</div>';
                    $a_book[] = '<div class="col-xs-12 col-sm-12 col-lg-5">';
                        $a_book[] = '<div class="book-metadata">';
                            $a_book[] = $this->load_book_metadata($o_book);
                        $a_book[] = '</div>';
                    $a_book[] = '</div>';
                    $a_book[] = '<div class="col-xs-12 col-sm-12 col-lg-7">';
                        $a_book[] = '<div class="book-content">';
                            $a_book[] =  $this->load_book_content($o_book);
                        $a_book[] = '</div>';

                        $a_book[] = '<div class="book-buttons">';
                            //$a_book[] = '<input type="button" class="btn group-btn" id="book-archive" value="Archive" />';
                        $a_book[] = '</div>';
                    $a_book[] ='</div>';
                $a_book[] ='</div>';
			$a_book[] ='</div>';
		$a_book[] = '</div>';

		$a_return['body'] = implode('', $a_book);

		$a_btn[] = '<a href="'.$post_permalink.'">';
		$a_btn[] = '<input type="button" class="btn btn-primary temp-btn" value="Go to Reader" />';
		$a_btn[] = '</a>';

		$a_return['footer'] = implode('', $a_btn);

		$this->_a_return['data'] = $a_return;

		return TRUE;
	}

	private function load_book_metadata($o_book) {

	    $a_list[] = '<div class="panel panel-default">';
            $a_list[] = '<div class="panel-heading">';
                $a_list[] = '<i class="glyphicon glyphicon-circle-info"></i> Book meta-data';
            $a_list[] = '</div>';
            $a_list[] = '<div class="panel-body">';
                $a_list[] = '<ul>';
                    $a_list[] = '<li>'.get_the_term_list($o_book->ID, 'reading-level', '<span class="blurb-meta">Reading Level: </span> ', ', ', '').'</li>';
                    $a_list[] = '<li>'.get_the_term_list($o_book->ID, 'year-level', '<span class="blurb-meta">Year: </span> ', ', ', '').'</li>';
                    $a_list[] = '<li>'.get_the_term_list($o_book->ID, 'fiction', '<span class="blurb-meta">Fiction/Nonfiction: </span> ', ', ', '').'</li>';
                    $a_list[] = '<li>'.get_the_term_list($o_book->ID, 'ebook-theme', '<span class="blurb-meta">Theme/Topic: </span> ', ', ', '').'</li>';
                    $a_list[] = '<li><span class="blurb-meta">Strategy/Skills: </span>'.esc_html($o_book->esiss_strategy).'</li>';
                    $a_list[] = '<li><span class="blurb-meta">Page Count: </span>'. esc_html($o_book->esiss_page_count).'</li>';
                    if ( isset($o_book->esiss_word_count) && ! empty($o_book->esiss_word_count) ) {
                        $a_list[] = '<li><span class="blurb-meta">Word Count: </span>'. esc_html($o_book->esiss_word_count).'</li>';
                    }
                    $a_list[] = '<li><span class="blurb-meta">Reading Levels: </span>'. esc_html($o_book->wushka_levels).'</li>';
                    $a_list[] = '<li><span class="blurb-meta">Item Code: </span>'. esc_html($o_book->esiss_resource_id).'</li>';
                    $a_list[] = '<li><span class="blurb-meta">High Frequency Words: </span>'. esc_html($o_book->esiss_hfw).'</li>';
                $a_list[] = '</ul>';
            $a_list[] = '</div>';
        $a_list[] = '</div>';

        return implode('', $a_list);
    }

    private function load_book_blurb($o_book) {

        $a_list[] = '<div class="panel panel-default">';
            $a_list[] = '<div class="panel-heading">';
                $a_list[] = '<i class="glyphicon glyphicon-notes"></i> School Reader Description';
            $a_list[] = '</div>';
            $a_list[] = '<div class="panel-body">';
                $a_list[] = '<p>';
                    $a_list[] = esc_html($o_book->esiss_blurb);
                $a_list[] = '</p>';
            $a_list[] = '</div>';
        $a_list[] = '</div>';

        return implode('', $a_list);
    }

    private function load_book_content($o_book) {

        $a_list[] = '<div class="panel panel-default">';
            $a_list[] = '<div class="panel-heading">';
                $a_list[] = '<i class="glyphicon glyphicon-list-alt"></i> School Reader Detail';
            $a_list[] = '</div>';
            $a_list[] = '<div class="panel-body">';
                $a_list[] = '<p>';
                    $a_list[] = esc_html($o_book->post_content);
                $a_list[] = '</p>';
            $a_list[] = '</div>';
        $a_list[] = '</div>';

        return implode('', $a_list);
    }

}

/* ----- END OF MANAGE READING GROUP AJAX FILE ----- */
