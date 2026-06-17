<?php
/**
 * REST API: WP_REST_Students_Controller class
 *
 * @package WordPress
 * @subpackage REST_API
 * @since 4.7.0
 */

/**
 * Core class used to manage users via the REST API.
 *
 * @since 4.7.0
 *
 * @see WP_REST_Controller
 */
class WP_REST_Students_Controller extends WP_REST_Controller {
	
	protected $meta;

	public function __construct() {
		$this->namespace = 'api/v1';
		$this->rest_base = 'students';
		$this->rest_base_singular = 'student';
		$this->rest_base_bulk = 'bulkstudents';

		$this->meta = new WP_REST_User_Meta_Fields();
	}
	 
	public function register_routes() {

		//GET, POST students
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ), 
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

		//GET, PUT/PATCH, DELETE students
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)',
			array(
				'args'   => array(
					'id' => array(
						'description' => __( 'Unique identifier for the students.' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ), 
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_item' ),
					'permission_callback' => array( $this, 'delete_item_permissions_check' ), 
				), 
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
  
		//POST bulk students
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_bulk,
			array( 
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_students_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ), 
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
 
	}
	
	/**
	 * Check if a given request has access to get items
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function get_items_permissions_check( $request ) {
		if(!get_school_id_from_api_key($request)){
			return wushka_rest_error('invalid_api_key');
		}
		return true;
	}

	/**
	 * Get a collection of items
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {
		$school_id = get_school_id_from_api_key($request); 

		$datas = $this->get_students($school_id, $request); 
		$students = array();
		foreach ( $datas as $data ) { 
			$student = $this->prepare_item_for_response( $data, $request );
			$students[] = $this->prepare_response_for_collection( $student );
		}
		$students = wp_list_sort( $students, 'class_id' );
		$response = rest_ensure_response( $students ); 
   

		return $response;
	}

	/**
	 * Checks if a given request has access to read a user.
	 *
	 * @since 4.7.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return true|WP_Error True if the request has read access for the item, otherwise WP_Error object.
	 */
	public function get_item_permissions_check( $request ) {
		if(!get_school_id_from_api_key($request)){
			return wushka_rest_error('invalid_api_key');
		}
		$user = $this->get_user( $request['id'] );
		if ( is_wp_error( $user ) ) {
			return $user;
		}
		 
		if(!current_user_can( 'edit_user' , get_current_user_id())){
			//Not authorized to view student
			return wushka_rest_error('user_invalid_id');
		}

		$school_id = get_school_id_from_api_key($request);
		
		$datas = $this->get_students($school_id, $request); 

		$students = $this->get_students_id($datas); 
		 
		if(!in_array( $user->ID, $students, true))
		{
			//No students found
			return wushka_rest_error('user_invalid_id');
		} 

		return true;
 
	}

	/**
	 * Get one item from the collection
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_item( $request ) {
		$student = $this->get_user( $request['id'] );
		if ( is_wp_error( $student ) ) {
			return $student;
		} 

		$student     = $this->prepare_item_for_response( $student, $request );
		$response = rest_ensure_response( $student );

		return $response;
		
	}

	/**
	 * Checks if a given request has access create users.
	 *
	 * @since 4.7.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return true|WP_Error True if the request has access to create items, WP_Error object otherwise.
	 */
	public function create_item_permissions_check( $request ) { 
		if(!get_school_id_from_api_key($request)){
			return wushka_rest_error('invalid_api_key'); 
		}
		$user = wp_get_current_user();
		if ( in_array( 'student', (array) $user->roles ) ) {
			//Not authorized to create new student
			return wushka_rest_error('access_denied'); 
		}
 
		return true;
	}

	/**
	 * Create one item from the collection
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function create_item( $request ) {
		if ( ! empty( $request['id'] ) ) {
			return new WP_Error(
				'rest_user_exists',
				__( 'Cannot create existing user.' ),
				array( 'status' => 400 )
			);
		}
  
		$school_id = get_school_id_from_api_key($request); 

		//empty string validation
		$error = $this->required_validation($request); 
		if($error){
			return new WP_Error(
				'rest_invalid_param',
				__( 'Invalid parameter(s): '.$error),
				array( 'status' => 400 )
			);
		}
		
		if(isset($request['class_id'])){
			$class_id = $this->get_class_ids($school_id);  
			if( ! in_array($request['class_id'], $class_id) ){
				return wushka_rest_error('class_invalid_id');
			}
		}
  
		$username = $this->create_username($request); 
 
		$user = $this->prepare_item_for_database( $request );
		$user = (array) $user;  

		$first_name = $user['first_name'];
		$last_name = $user['last_name'];
		
		if( !isset($user['class_id']) && empty($user['class_id'])){
			$class_id = [
				'school_id' => $school_id
			];
		}else{
			$class_id = $user['class_id'];
		}
		
		//Create student
		$user = wushka_create_student($first_name, $last_name, $username, $class_id);  
		
		if ( ! $user ) {
			return new WP_Error(
				'rest_user_error',
				__( 'Failed to create user' ),
				array( 'status' => 400 )
			);
		}
		$user = get_user_by( 'login', $username );
		$user_id = $user->ID;

		do_action( 'rest_after_insert_user', $user, $request, true );

		$response = $this->prepare_item_for_response( $user, $request );
		$response = rest_ensure_response( $response );
		$response->set_data(
			array(
				'created'  => true,
				'data' => $response->get_data(),
			)
		);

		$response->set_status( 201 );
		$response->header( 'Location', rest_url( sprintf( '%s/%s/%d', $this->namespace, $this->rest_base, $user_id ) ) );

		return $response;
	}

	public function create_students_item( $request ) {
		$students = $request['students'];
		$school_id = get_school_id_from_api_key($request); 

		$invalid = []; 
        $valid = [];
        $created_data = [];
        foreach($students as $student){
			$data = array_map( 'sanitize_text_field' , $student);
			
            if(array_key_exists('first_name', $data) && array_key_exists('last_name', $data)){
                if( ! empty( $data['first_name'] ) && ! empty( $data['last_name'] ) ){  
					$student = $this->prepare_item_for_database( $data );
					$student = (array) $student;
					
					$username = $this->create_username($data);
					$first_name = $student['first_name'];
					$last_name = $student['last_name'];

					$error = false;
					if(isset($student['class_id'])){
						$class_id = $student['class_id'];

						$class_ids = $this->get_class_ids($school_id);  
						if( !in_array($class_id, $class_ids) ){
							$data['message'] = 'Invalid class id.';
							array_push($invalid, $data);
							$error = true;
						}
					}else{
						$class_id = [
							'school_id' => $school_id
						]; 
					}

					if(!$error){
						//Create student
						$new_student = wushka_create_student($first_name, $last_name, $username, $class_id);
						if ( $new_student ) {
							array_push($valid, $data);

							$user = get_user_by( 'login', $username );
							$user_id = $user->ID;

							if(!isset($student['class_id'])){
								array_push($created_data, array(
									'id' => $user_id, 
									'first_name' => $first_name, 
									'last_name' => $last_name, 
									'username' => $username, 
									'school_id' => $school_id, 
								) );
							}else{
								array_push($created_data, array(
									'id' => $user_id, 
									'first_name' => $first_name, 
									'last_name' => $last_name, 
									'username' => $username, 
									'class_id' => $class_id, 
								) );
							}
							
						}else{ 
							$data['message'] = 'Sorry, Failed to create student.';
							array_push($invalid, $data);
						}
					}  
                }else{
					$data['message'] = 'Sorry, Fields can not be empty.';
                    array_push($invalid, $data);    
                }
            }else{ 
				$data['message'] = 'Missing parameter(s).';
                array_push($invalid, $data);
            }
		} 
        if(empty($valid)){
            $response = new WP_REST_Response();
            $response_array = array(  
                'invalid_data'  => $invalid,  
            );
            $response->set_data($response_array);
            $response->set_status( 400 );

            return rest_ensure_response( $response );  
		} 
		

		$response = new WP_REST_Response();
        $response_array = array(
            'created'  => true,
            'valid_data'  => $valid,
            'invalid_data'  => $invalid, 
            'created_records' => $created_data
        );
        $response->set_data($response_array);
        
        return $response;

	}

	/**
	 * Checks if a given request has access to update a user.
	 *
	 * @since 4.7.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return true|WP_Error True if the request has access to update the item, WP_Error object otherwise.
	 */
	public function update_item_permissions_check( $request ) {
		if(!get_school_id_from_api_key($request)){
			return wushka_rest_error('invalid_api_key');
		}
		$user = $this->get_user( $request['id'] );
		if ( is_wp_error( $user ) ) {
			return $user;
		}

		if ( ! $user ) {
			return wushka_rest_error('user_invalid_id');
		}

		if ( ! empty( $request['roles'] ) ) { 
			return new WP_Error(
				'rest_cannot_edit_roles',
				__( 'Sorry, you are not allowed to edit roles of this student.' ),
				array( 'status' => rest_authorization_required_code() )
			);  
		}

		$currentUser = wp_get_current_user();
		$allowed_roles = array( 'administrator', 'school', 'teacher' );
		if ( ! array_intersect( $allowed_roles, $currentUser->roles ) ) {
			return wushka_rest_error('user_invalid_id');
		} 

		//Allow to update only from same school
		$school_id = get_school_id_from_api_key($request); 
		$datas = $this->get_class_ids( $school_id );
		if ( is_wp_error( $datas ) ) {
			return $datas;
		}  

		//Check if student is assigned to any class or school
		if(get_user_meta($user->ID, 'class', true)){
			$student_class = get_user_meta($user->ID, 'class', true);
			if(!in_array( $student_class, $datas, true))
			{ 
				return wushka_rest_error('user_invalid_id');
			}	
		}else{
			$student_school = get_user_meta($user->ID, 'school_id', true);
			if($student_school !=  get_school_id_from_api_key($request))
			{ 
				return wushka_rest_error('user_invalid_id');
			}
		}
		 
		
		if( isset( $request['class_id'] ) && ! in_array( $request['class_id'], $datas, true ) ){
			return wushka_rest_error('class_invalid_id');
		}
		return true;
	}


	/**
	 * Update one item from the collection
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function update_item( $request ) {
		$user = $this->get_user( $request['id'] );
		if ( is_wp_error( $user ) ) {
			return $user;
		}

		$id = $user->ID;

		if ( ! $user ) {
			return wushka_rest_error('user_invalid_id');
		}

		
		//Check if there is any invalid parameter
		$error = $this->update_validation($request); 
		if(! empty($error) ){
			return new WP_Error(
				'rest_invalid_param_value',
				__( 'Invalid parameter value(s): '.$error),
				array( 'status' => 400 )
			);
		}

		
		//Get current clas id
		$class_id = '';
		if( get_user_meta( $user->ID, 'class', true ) ){
			$class_id = get_user_meta( $user->ID, 'class', true );
		}

		//Reading group validation
		if( isset($request['my_reading_group']) ){
			$reading_group = $this->get_class_reading_group_id($class_id);
			if( !in_array($request['my_reading_group'], $reading_group, false ) ){
				return new WP_Error(
					'rest_invalid_data',
					__( 'Invalid reading group id.' ),
					array( 'status' => 400 )
				);
			}
		} 

		//Level Access validation
		if( isset($request['level_access']) ){
			$level_access = $this->get_access_types();
			if( !in_array($request['level_access'], $level_access, false ) ){
				return new WP_Error(
					'rest_invalid_data',
					__( 'Invalid level access data.' ),
					array( 'status' => 400 )
				);
			} 
		}

		//Reading level validation
		if( isset($request['reading_level']) ){ 
			$a_terms = get_terms('reading-level');
			$a_terms = json_decode(json_encode($a_terms), true);
			$a_terms = array_column($a_terms, 'slug');

			if( !in_array($request['reading_level'], $a_terms, false ) ){
				return new WP_Error(
					'rest_invalid_data',
					__( 'Invalid reading level data.' ),
					array( 'status' => 400 )
				);
			} 
		}
		 
  
		$user = $this->prepare_item_for_database( $request );
		$user = (array) $user; 

		//Use  to update user
		if(!$user){
			//Empty key
			return wushka_rest_error('invalid_data'); 
		} 
		  
		// Include edit-user-data.php to get access to functions
		require_once get_template_directory(). '/edit-user-data.php';
		$id_hash = get_user_meta($id, 'id_hash', true);
		
		$args = [
			'first_name', 
			'last_name', 
			'class_id', 
			'password', 
			'reading_level', 
			'level_access', 
			'my_reading_group', 
			'reading_group_permission', 
			'allow_narration', 
			'allow_book_read_during_quiz', 
			'quizzes', 
			'allow_quiz_narration', 
			'allow_quiz_detail_results', 
		   ];

		foreach($args as $arg){
			if( isset( $user[$arg] ) ){
				if($arg == 'level_access'){
					$meta_key = '';   
					$user[$arg] = strtolower($user[$arg]);
					wushka_set_student_allowed_levels($id_hash, ucwords($user[$arg]) );
				}elseif($arg == 'reading_level'){
					$meta_key = ''; 
					$user[$arg] = strtolower($user[$arg]);
					wushka_set_student_level($id_hash, $user[$arg] );
				}elseif($arg == 'class_id'){
					$meta_key = 'class';
					$user[$arg] = strtolower($user[$arg]);
				}elseif($arg == 'reading_group_permission'){					
					$meta_key = 'rg_setting';
					$user[$arg] = strtolower($user[$arg]);
				}elseif($arg == 'allow_narration'){
					$meta_key = 'narration';
					$user[$arg] = ucwords($user[$arg]);
				}elseif($arg == 'allow_book_read_during_quiz'){
					$meta_key = 'allow_book_view';
					$user[$arg] = ucwords($user[$arg]);
				}elseif($arg == 'allow_quiz_narration'){
					$meta_key = 'quiz_narration';
					$user[$arg] = ucwords($user[$arg]);
				}elseif($arg == 'allow_quiz_detail_results'){
					$meta_key = 'quiz_detail_results';
					$user[$arg] = ucwords($user[$arg]);
				}else{
					$meta_key = $arg;
				}

				if( !empty($meta_key) ){
					wushka_update_user_meta($id_hash, $meta_key, $user[$arg]); 
				}
				
			}
		}
 

		if( isset( $user['password'] ) ){
			wushka_set_student_pwd($id_hash, $user['password']);
		}
		  
		//$user = wushka_update_teacher($user); 
		/* if ( ! empty($user['msg']) ) {
			return new WP_Error(
				'rest_user_error',
				__( $user['msg'] ),
				array( 'status' => 400 )
			);
		} */
		   
		$user          = get_user_by( 'id', $id );
		$fields_update = $this->update_additional_fields_for_object( $user, $request );

		if ( is_wp_error( $fields_update ) ) {
			return $fields_update;
		}

		$request->set_param( 'context', 'edit' );

		//**  This action is documented in wp-includes/rest-api/endpoints/class-wp-rest-users-controller.php 
		do_action( 'rest_after_insert_user', $user, $request, false );

		$response = $this->prepare_item_for_response( $user, $request );
		$response = rest_ensure_response( $response );
		$response->set_data(
			array(
				'updated'  => true,
				'data' => $response->get_data(),
			)
		);

		return $response;
	}

	/**
	 * Checks if a given request has access delete a user.
	 *
	 * @since 4.7.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return true|WP_Error True if the request has access to delete the item, WP_Error object otherwise.
	 */
	public function delete_item_permissions_check( $request ) {
		if(!get_school_id_from_api_key($request)){
			return wushka_rest_error('invalid_api_key');  
		}
		$user = $this->get_user( $request['id'] );
		if ( is_wp_error( $user ) ) {
			return $user;
		}
 
		//Block self delete
		if($user->ID == get_current_user_id()){
			return wushka_rest_error('user_invalid_id');
		}

		
		//Allow to delete only from same school
		$school_id = get_school_id_from_api_key($request);
				
		$datas = $this->get_class_ids( $school_id );
		if ( is_wp_error( $datas ) ) {
			return $datas;
		}

		$student_class = get_user_meta($user->ID, 'class', true);
		 
		if(!in_array( $student_class, $datas, true))
		{ 
			return wushka_rest_error('user_invalid_id');
		} 
 
		return true;
	}

	/**
	 * Deletes a single user.
	 *
	 * @since 4.7.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function delete_item( $request ) {
		// We don't support delete requests in multisite.
		if ( is_multisite() ) {
			return new WP_Error(
				'rest_cannot_delete',
				__( 'The user cannot be deleted.' ),
				array( 'status' => 501 )
			);
		}

		$user = $this->get_user( $request['id'] );

		if ( is_wp_error( $user ) ) {
			return $user;
		}

		$id       = $user->ID; 

		$active = get_user_meta($id, 'active', true);

		if(!$active){
			return wushka_rest_error('user_invalid_id');
		}
		 
		$request->set_param( 'context', 'edit' );

		$previous = $this->prepare_item_for_response( $user, $request );  
		require_once get_template_directory(). '/edit-user-data.php';
		$id_hash = get_user_meta($id, 'id_hash', true);
		 

		$result = wushka_set_student_archive( $id_hash, 0 );

		if ( ! $result ) {
			return new WP_Error(
				'rest_cannot_delete',
				__( 'The student cannot be deleted.' ),
				array( 'status' => 500 )
			);
		}
		unset($previous->data['active']);
		$response = new WP_REST_Response();
		$response->set_data(
			array(
				'archived'  => true,
				'previous' => $previous->get_data(),
			)
		);
		unset($response->active);
 
		do_action( 'rest_delete_user', $user, $response, $request );

		return $response;
	}

	/**
	 * Get the user, if the ID is valid.
	 *
	 * @since 4.7.2
	 *
	 * @param int $id Supplied ID.
	 * @return WP_User|WP_Error True if ID is valid, WP_Error otherwise.
	 */
	protected function get_user( $id ) {
		if ( (int) $id <= 0 ) {
			return wushka_rest_error('user_invalid_id');
		}

		$user = get_userdata( (int) $id );
		if ( empty( $user ) || ! $user->exists() ) {
			return wushka_rest_error('user_invalid_id');
		}

		if ( is_multisite() && ! is_user_member_of_blog( $user->ID ) ) {
			return wushka_rest_error('user_invalid_id');
		}

		return $user;
	}
	
	/* *
	 * Return only id array from user object
	 *
	 */
	public function get_students_id($datas){
		$items = json_decode(json_encode($datas), true); 
		$items_id = array_column($items, 'ID'); 
		return $items_id;
	}
	
	public function get_students($school_id, $request){		
		$key = 'class';
		//Get list of class id from school id
		$classes = wushka_get_classes($school_id);  
		$class_ids = [];  
		foreach( $classes as $i_key => $o_class ) { 
			$class_ids[] .= (int)$o_class->class_id;  
		}  

		if(empty($class_ids)){
			return wushka_rest_error('class_invalid_id');
		}

		 
		$user_class = wushka_get_students( $class_ids, $key ); 

		//Get student without any class room assigned
		$user_school = wushka_get_students( $school_id, 'school_id' ); 

		$datas = array_merge($user_class, $user_school);
 
		if ( is_wp_error( $datas ) ) {
			return wushka_rest_error('user_invalid_id');
		}
		
		return $datas;
	}

	public function get_user_reading_group_name($user_id){
		global $wpdb;

		$id = get_user_meta( $user_id, 'my_reading_group', true );

		$table = $wpdb->prefix . 'wushka_reading_groups';
		$sql   = 'SELECT * FROM ' . $table . ' WHERE `id` = %d';
        $results = $wpdb->get_results(
            $wpdb->prepare($sql, $id)
		); 
		$name = 'No Group';
		if($results){
			$name = $results[0]->group_name; 
		}
		//(empty($name))? $name = 'No Group' : $name;
		return $name;

	}

	public function get_class_reading_group_id($class_id){
		global $wpdb;
		$table = $wpdb->prefix . 'wushka_reading_groups';

		$sql   = 'SELECT * FROM ' . $table . ' WHERE `class_id` = %d';
        $results = $wpdb->get_results(
            $wpdb->prepare($sql, $class_id)
		); 

		if($results){
			$results = json_decode(json_encode($results), true);
			$results = array_column($results, 'ID');
		}
		return $results; 
	}

	public function get_class_ids($school_id){
		if ( (int) $school_id <= 0 ) {
			return wushka_rest_error('user_invalid_id');
		}

		$datas = wushka_get_classes($school_id);

		$ids = array();
		foreach($datas as $data) {
			$ids[] .= $data->id;
		}

		return $ids;
	}
 
	/**
	 * Validates the user's input.
	 *  
	 * @return string empty field names.
	 */
	public function required_validation($request) {
		if ( empty( $request['first_name'] ) || empty( $request['last_name'] ) ) {	
			
			if(empty( $request['first_name'] ) && empty( $request['last_name'])){
				$invalid_params = 'first_name, last_name';
			}elseif(empty( $request['first_name'] )){
				$invalid_params = 'first_name';
			}else{
				$invalid_params = 'last_name';
			}
			 
			return $invalid_params;
		}

		return;
	}

	public function create_username($request){
		$first_name = $request['first_name'];
		(strlen($first_name) > 0)? $first_name = $first_name[0]: $first_name ;

		$last_name = $request['last_name'];
		(strlen($last_name) > 0)? $last_name = $last_name[0]: $last_name ;

		$username = strtoupper($first_name.$last_name.'-'.rand(1000, 99999)); 

		if(username_exists($username)){ 
			while(username_exists($username)){ 
				$username = strtoupper($first_name.$last_name.'-'.rand(1000, 99999));
			}
		}

		return $username;

	}

	public function wushka_set_student_allowed_levels( $i_hash, $s_allowed ) {
		$o_user = get_user_by_hash($i_hash);
		if( $o_user ) {
			$a_allowed = $this->wushka_get_allowed_levels();
			foreach( $a_allowed as $ii => $a_range ) {
				if( $s_allowed == $a_range['name'] ) {
					//Update Allowed Shelves
					update_user_meta($o_user->ID, 'allowed_shelves', $a_range);
					//Update Prepared Levels
					$this->wushka_set_student_prep_levels($o_user->ID);
				}
			}
		}
	}

	public function wushka_get_allowed_levels() {
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

	public function wushka_set_student_prep_levels( $i_user ) {
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


	public function update_validation($request){
		$invalid_params = []; 

		if( isset($request['first_name']) && empty($request['first_name']) ){
			$invalid_params[] .= 'first_name'; 
		}

		if( isset($request['last_name']) && empty($request['last_name']) ){
			$invalid_params[] .= 'last_name'; 
		}

		if( isset($request['class_id']) && empty($request['class_id']) ){
			$invalid_params[] .= 'class_id'; 
		}

		if( isset($request['password']) && empty($request['password']) ){
			$invalid_params[] .= 'password'; 
		}

		if( isset($request['reading_level']) && empty($request['reading_level']) ){
			$invalid_params[] .= 'reading_level'; 
		}

		if( isset($request['level_access']) && empty($request['level_access']) ){
			$invalid_params[] .= 'level_access'; 
		}

		if( isset($request['reading_group']) && empty($request['reading_group']) ){
			$invalid_params[] .= 'reading_group'; 
		}

		if( isset($request['reading_group_permissions']) && empty($request['reading_group_permissions']) ){
			$invalid_params[] .= 'reading_group_permissions'; 
		}

		if( isset($request['allow_narration']) && empty($request['allow_narration']) ){
			$invalid_params[] .= 'allow_narration'; 
		}

		if( isset($request['allow_book_read_during_quiz']) && empty($request['allow_book_read_during_quiz']) ){
			$invalid_params[] .= 'allow_book_read_during_quiz'; 
		}

		if( isset($request['quizzes']) && empty($request['quizzes']) ){
			$invalid_params[] .= 'quizzes'; 
		}

		if( isset($request['allow_quiz_narration']) && empty($request['allow_quiz_narration']) ){
			$invalid_params[] .= 'allow_quiz_narration'; 
		}

		if( isset($request['allow_detailled_quiz_results']) && empty($request['allow_detailled_quiz_results']) ){
			$invalid_params[] .= 'allow_detailled_quiz_results'; 
		}
 

		if( !empty($invalid_params) ){ 
			$invalid_params = implode (", ", $invalid_params);
		}

		return $invalid_params;
	}

	public function update_user_login($user_id, $username){
		global $wpdb; 
		$wpdb->update($wpdb->users, array('user_login' => $username), array('ID' => $user_id));
	}

	private function get_access_types() {
        $a_types                                    = array();
        $a_types['']                                = "";
        $a_types['Reading Group Only']              = "Reading Group Only";
        $a_types['Reading Level Only']              = "Reading Level Only";
        $a_types['Reading Level + One Level Above'] = "Reading Level + One Level Above";
        $a_types['Reading Level + One Level Below'] = "Reading Level + One Level Below";
        $a_types['Reading Level + Levels Below']    = "Reading Level + Levels Below";
        $a_types['Levels Below Reading Level Only'] = "Levels Below Reading Level Only";
        $a_types['All Levels']                      = "All Levels";

        return $a_types;
    }

	/**
	 * Prepares a single user for creation or update.
	 *
	 * @since 4.7.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return object User object.
	 */
	protected function prepare_item_for_database( $request ) {
		
		$prepared_user = new stdClass;

		$schema = $this->get_item_schema();

		// Required arguments.
		$args = [
			'first_name', 
			'last_name', 
			'class_id', 
			'school_id', 
			'password', 
			'reading_level', 
			'level_access', 
			'my_reading_group', 
			'reading_group_permission', 
			'allow_narration', 
			'allow_book_read_during_quiz', 
			'quizzes', 
			'allow_quiz_narration', 
			'allow_quiz_detail_results', 
		   ];

		foreach($args as $arg){
			if ( isset( $request[$arg] ) && ! empty( $schema['properties'][$arg] ) ) {
				$prepared_user->$arg = $request[$arg];
			}
		} 
		/**
		 * Filters user data before insertion via the REST API.
		 *
		 * @since 4.7.0
		 *
		 * @param object          $prepared_user User object.
		 * @param WP_REST_Request $request       Request object.
		 */
		return apply_filters( 'rest_pre_insert_user', $prepared_user, $request );
	}

	public function prepare_item_for_response( $item, $request ){
		$data   = array();
		$items = json_decode(json_encode($item), true); 
		$fields = $items['data']; 
		if ( array_key_exists( 'ID', $fields ) ) { 
			$data['id'] = $item->ID;

			//Class information 
			if( !empty(get_user_meta( $item->ID, 'class', true )) ){
				$data['class_id'] = (int) get_user_meta( $item->ID, 'class', true ); 
				$data['class_name'] = wushka_get_class($data['class_id'])->name;  
			}

			//School information
			if( !empty(get_user_meta( $item->ID, 'school_id', true )) ){
				$data['school_id'] = (int) get_user_meta( $item->ID, 'school_id', true );
			}
		}

		if ( array_key_exists( 'user_login', $fields) ) {
			$data['username'] = $item->user_login;
		}
 
		if ( array_key_exists( 'ID', $fields) ) {
			$data['first_name'] = get_user_meta( $item->ID, 'first_name', true );
			$data['last_name'] = get_user_meta( $item->ID, 'last_name', true ); 
			$data['password'] = get_user_meta( $item->ID, 'show_user_pwd', true );  
			
			$reading_level = get_user_meta( $item->ID, 'reading_level', true );  
			(!empty($reading_level))? $data['reading_level'] = $reading_level['name'] : $data['reading_level'] = '';
			
			$level_access = get_user_meta( $item->ID, 'allowed_shelves', true ); 
			(!empty($level_access))? $data['level_access'] = $level_access['name'] : $data['level_access'] = '';

			$data['my_reading_group'] = $this->get_user_reading_group_name($item->ID);
			$data['reading_group_permission'] = get_user_meta( $item->ID, 'rg_setting', true ); 
			$data['allow_narration'] = get_user_meta( $item->ID, 'narration', true ); 
			$data['allow_book_read_during_quiz'] = get_user_meta( $item->ID, 'allow_book_view', true ); 
			$data['quizzes'] = get_user_meta( $item->ID, 'quizzes', true ); 
			$data['allow_quiz_narration'] = get_user_meta( $item->ID, 'quiz_narration', true ); 
			$data['active'] = (int) get_user_meta( $item->ID, 'active', true );  

		}
  
		if ( array_key_exists( 'user_registered', $fields) ) {
			$data['registered_date'] = gmdate( 'c', strtotime( $item->user_registered ) );
		}
     
		$context = ! empty( $request['context'] ) ? $request['context'] : 'embed';

		$data = $this->add_additional_fields_to_object( $data, $request );
		$data = $this->filter_response_by_context( $data, $context );

		
		// Wrap the data in a response object.
		$response = rest_ensure_response( $data );

		 
		/**
		 * Filters user data returned from the REST API.
		 *
		 * @since 4.7.0
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param WP_User          $item     User object used to create teacher response.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( 'rest_prepare_student', $response, $item, $request );
	}

	/**
	 * Retrieves the user's schema, conforming to JSON Schema.
	 *
	 * @since 4.7.0
	 *
	 * @return array Item schema data.
	 */
	public function get_item_schema() {
		if ( $this->schema ) {
			return $this->add_additional_fields_schema( $this->schema );
		}
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'user',
			'type'       => 'object',
			'properties' => array( 
				'first_name'         => array(
					'required'    => true,
					'description' => __( 'First name for the student.' ),
					'type'        => 'string',
					'context'     => array( 'embed', 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'last_name'          => array(
					'required'    => true,
					'description' => __( 'Last name for the student.' ),
					'type'        => 'string',
					'context'     => array( 'embed', 'view', 'edit' ), 
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field', 
					),
				),
				'class_id'          => array( 
					'description' => __( 'Class id to assign for the student.' ),
					'type'        => 'integer',
					'context'     => array( 'embed', 'view', 'edit' ), 
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field', 
					),
				),
				'password'          => array( 
					'description' => __( 'Password for the student.' ),
					'type'        => 'string',
					'context'     => array( 'embed', 'view', 'edit'  ),  
				), 
				//serialized data
				'reading_level'          => array( 
					'description' => __( 'Reading level for the student.' ),
					'type'        => 'string',
					'context'     => array( 'embed', 'view', 'edit'  ), 
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field', 
					),
				), 
				//serialized data
				'level_access'          => array( 
					'description' => __( 'Level access for the student.' ),
					'type'        => 'string',
					'context'     => array( 'embed', 'view', 'edit'  ), 
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field', 
					),
				), 
				'my_reading_group'          => array( 
					'description' => __( 'Reading group for the student.' ),
					'type'        => 'integer',
					'context'     => array( 'embed', 'view', 'edit'  ), 
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field', 
					),
				), 
				'reading_group_permission'          => array( 
					'description' => __( 'Reading Group permission for the student.' ), 
					'enum'        => array( 'on', 'off', 'school', 'home' ),
					'type'        => 'string',
					'context'     => array( 'embed', 'view', 'edit'  ), 
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field', 
					),
				), 
				'allow_narration'          => array( 
					'description' => __( 'Allow narration for the student.' ),
					'enum'        => array( 'yes', 'no' ),
					'type'      => 'string', 
					'context'     => array( 'embed', 'view', 'edit'  ), 
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field', 
					),
				), 
				'allow_book_read_during_quiz'          => array( 
					'description' => __( 'Allow book read during quiz for the student.' ),
					'enum'        => array( 'yes', 'no' ),
					'type'        => 'string',
					'context'     => array( 'embed', 'view', 'edit'  ), 
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field', 
					),
				), 
				'quizzes'          => array( 
					'description' => __( 'Quizzes for the student.' ),
					'enum'        => array( 'no', 'optional', 'compulsory', 'school only', 'home only' ),
					'type'        => 'string',
					'context'     => array( 'embed', 'view', 'edit'  ), 
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field', 
					),
				), 
				'allow_quiz_narration'          => array( 
					'description' => __( 'Allow quiz narration for the student.' ),
					'enum'        => array( 'yes', 'no' ),
					'type'        => 'string',
					'context'     => array( 'embed', 'view', 'edit'  ), 
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field', 
					),
				), 
				'allow_quiz_detail_results'          => array( 
					'description' => __( 'Allow quiz detail result for the student.' ),
					'enum'        => array( 'yes', 'no' ),
					'type'        => 'string',
					'context'     => array( 'embed', 'view', 'edit'  ), 
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field', 
					),
				),   
			),
		);
  
		$schema['properties']['meta'] = $this->meta->get_field_schema();

		$this->schema = $schema; 

		return $this->add_additional_fields_schema( $this->schema );
	}
 

}
