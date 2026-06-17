<?php
/**
 * REST API: WP_REST_Teachers_Controller class
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
class WP_REST_Teachers_Controller extends WP_REST_Controller {
	
	protected $meta;

	public function __construct() {
		$this->namespace = 'api/v1';
		$this->rest_base = 'teachers';
		$this->rest_base_bulk = 'bulkteachers';

		$this->meta = new WP_REST_User_Meta_Fields();
	}
	 
	public function register_routes() {

		//GET, POST teachers
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

		//GET, PUT, DELETE teachers
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)',
			array(
				'args'   => array(
					'id' => array(
						'description' => __( 'Unique identifier for the teacher.' ),
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

		//POST bulkteachers
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_bulk,
			array( 
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_teacher_items' ),
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
		$atts['school_id'] = get_school_id_from_api_key($request); 
		//Get teachers detail from school_id
		$datas = wushka_get_teachers( $atts );
		if ( is_wp_error( $datas ) ) {
			return $datas;
		}
		
		//Prepare for response
		$teachers = array();
		foreach ( $datas as $data ) {
			$teacher = $this->prepare_item_for_response( $data, $request );
			$teachers[] = $this->prepare_response_for_collection( $teacher );
		}

		$response = rest_ensure_response( $teachers );    

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

		//Check if teacher exist with the requested id
		$user = $this->get_user( $request['id'] );
  
		if ( is_wp_error( $user ) ) {
			return $user;
		}
		 
		//Check if current user can view the data
		if(!current_user_can( 'edit_user' , get_current_user_id())){
			return wushka_rest_error('access_denied');
		}

		$atts['school_id'] = get_school_id_from_api_key($request);
		
		//get teachers list from the school
		$datas = wushka_get_teachers( $atts );
		if ( is_wp_error( $datas ) ) {
			return $datas;
		}

		$teachers = $this->get_teachers_id($datas); 
		 
		//Check if requested id exist on the same school
		if(!in_array( $user->ID, $teachers, true))
		{
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
		$teacher = $this->get_user( $request['id'] );
		if ( is_wp_error( $teacher ) ) {
			return $teacher;
		} 

		$teacher     = $this->prepare_item_for_response( $teacher, $request );

		//Set additional data
		$teacher->data['last_login'] = get_user_meta( $teacher->data['id'], 'last_login', true );
		$teacher->data['classes'] = $this->get_teacher_classes($teacher->data['id']);

		//Set response
		$response = rest_ensure_response( $teacher );

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
			return  wushka_rest_error('invalid_api_key');
		}

		$user = wp_get_current_user();
		if ( in_array( 'student', (array) $user->roles ) && in_array( 'teacher', (array) $user->roles ) ) {
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
  
		$request['school_id'] = get_school_id_from_api_key($request);

		$error = $this->required_validation($request); 
		if($error){
			return new WP_Error(
				'rest_invalid_param',
				__( 'Invalid parameter(s): '.$error),
				array( 'status' => 400 )
			);
		}

		$user = $this->prepare_item_for_database( $request );
		$user = (array) $user; 
		$user['school_id'] = get_school_id_from_api_key($request);
		 
		$user = wushka_create_teacher($user); 
		
		if ( $user['result'] == 'failure' ) {
			return new WP_Error(
				'rest_user_error',
				__( $user['msg'] ),
				array( 'status' => 400 )
			);
		}
		$user = get_user_by( 'email', $user['user_email'] );
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

	public function create_teacher_items( $request ){
		$teachers = $request['teachers'];
		$school_id = get_school_id_from_api_key($request); 

		$invalid = []; 
        $valid = [];
        $created_data = [];
        foreach($teachers as $teacher){
			if( isset($teacher['first_name']) ){  
				$data['first_name'] = sanitize_text_field( $teacher['first_name'] );
			}
			if( isset($teacher['last_name']) ){  
				$data['last_name'] = sanitize_text_field( $teacher['last_name'] );
			}
			if( isset($teacher['email']) ){  
				$data['email'] = sanitize_email( $teacher['email'] );
			} 
            if(array_key_exists('first_name', $data) && array_key_exists('last_name', $data) && array_key_exists('email', $data)){
                if( ! empty( $data['first_name'] ) && ! empty( $data['last_name'] ) ){   
					if( $data['email'] ){
						$teacher = $this->prepare_item_for_database( $data );
						$teacher = (array) $teacher;
						$teacher['school_id'] = $school_id; 
						
						//Create teacher
						$new_teacher = wushka_create_teacher($teacher);
						
						if ( $new_teacher['result'] != 'failure' ) {
							array_push($valid, $data);

							$user = get_user_by( 'email', $teacher['email'] );
							$user_id = $user->ID;

							array_push($created_data, array(
								'id' => $user_id, 
								'first_name' => $teacher['first_name'], 
								'last_name' => $teacher['last_name'], 
								'email' => $teacher['email'], 
								'registered_date' => gmdate( 'c', strtotime( $user->user_registered ) ),  
							) );
						}else{ 
							$data['message'] = 'Sorry, Failed to create teacher.';
							array_push($invalid, $data);
						} 
					}else{
						$data['message'] = 'Invalid email';
						array_push($invalid, $data);
					} 
                }else{
					$data['message'] = 'Sorry, Fields can not be empty.';
                    array_push($invalid, $data);    
                }
            }else{ 
				$data['message'] = 'Missing parameter(s).';
                array_push($invalid, $data);
			}
			unset($data);
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
		$response->set_status( 201 ); 
        
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
			return  wushka_rest_error('invalid_api_key');
		}

		$user = $this->get_user( $request['id'] );
		if ( is_wp_error( $user ) ) {
			return $user;
		}

		if ( ! $user ) {
			return wushka_rest_error('user_invalid_id');
		}


		if(!isset($request['first_name']) && !isset($request['last_name']) && !isset($request['email'] )){
			return wushka_rest_error('invalid_data');
		}

		if ( ! empty( $request['roles'] ) ) { 
			return new WP_Error(
				'rest_cannot_edit_roles',
				__( 'Sorry, you are not allowed to edit roles of this teacher.' ),
				array( 'status' => rest_authorization_required_code() )
			);  
		}

		if ( in_array( 'student', (array) $user->roles ) && in_array( 'teacher', (array) $user->roles ) ) {
			return wushka_rest_error('user_invalid_id');
		}



		$atts['school_id'] = get_school_id_from_api_key($request);
		
		$datas = wushka_get_teachers( $atts );
		if ( is_wp_error( $datas ) ) {
			return $datas;
		}

		$teachers = $this->get_teachers_id($datas); 
		 
		if(!in_array( $user->ID, $teachers, true))
		{
			return wushka_rest_error('user_invalid_id');
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
				'rest_invalid_param',
				__( 'Invalid parameter(s): '.$error),
				array( 'status' => 400 )
			);
		}

		//Check if email already exist
		$owner_id = email_exists( $request['email'] ); 
		if ( $owner_id && $owner_id !== $id ) {
			return new WP_Error(
				'rest_user_invalid_email',
				__( 'Email address already exist.' ),
				array( 'status' => 400 )
			);
		}

		//Check if username already exist
		if( isset($request['username']) && !empty($request['username']) ){
			$owner_username = username_exists( $request['username'] );
			if( $owner_username && $owner_username !== $id ){
				return new WP_Error(
					'rest_user_invalid_username',
					__( 'Username already exist.' ),
					array( 'status' => 400 )
				);
			}
		}
   
 
		$user = $this->prepare_item_for_database( $request );
		$user = (array) $user; 
		$user['id'] = get_user_meta( $id, 'id_hash', true ); 
    
		$user = wushka_update_teacher($user); 

		//Since wordpress does not allow username update, use custom method to update username
		if( isset($request['username']) && !empty($request['username']) ){
			$this->update_user_login($id, $request['username']);
		}

		if ( ! empty($user['msg']) ) {
			return new WP_Error(
				'rest_user_error',
				__( $user['msg'] ),
				array( 'status' => 400 )
			);
		}
		   
		$user          = get_user_by( 'id', $id );
		$fields_update = $this->update_additional_fields_for_object( $user, $request );

		if ( is_wp_error( $fields_update ) ) {
			return $fields_update;
		}

		$request->set_param( 'context', 'edit' );

		/** This action is documented in wp-includes/rest-api/endpoints/class-wp-rest-users-controller.php */
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
			return  wushka_rest_error('invalid_api_key');
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
		$atts['school_id'] = get_school_id_from_api_key($request);
		
		$datas = wushka_get_teachers( $atts );
		if ( is_wp_error( $datas ) ) {
			return $datas;
		}

		$teachers = $this->get_teachers_id($datas); 
		 
		if(!in_array( $user->ID, $teachers, true))
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
		 
		$request->set_param( 'context', 'edit' );

		$previous = $this->prepare_item_for_response( $user, $request );

		$data['id'] = get_user_meta( $id, 'id_hash', true ); 
		$result = wushka_delete_teacher( $data );
 
		if ( ! $result ) {
			return new WP_Error(
				'rest_cannot_delete',
				__( 'The user cannot be deleted.' ),
				array( 'status' => 500 )
			);
		}

		$classes = $this->remove_teacher_from_class( $id ); 

		$response = new WP_REST_Response();
		$response->set_data(
			array(
				'deleted'  => true,
				'previous' => $previous->get_data(),
			)
		);

		/**
		 * Fires immediately after a user is deleted via the REST API.
		 *
		 * @since 4.7.0
		 *
		 * @param WP_User          $user     The user data.
		 * @param WP_REST_Response $response The response returned from the API.
		 * @param WP_REST_Request  $request  The request sent to the API.
		 */
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
	public function get_teachers_id($datas){
		$items = json_decode(json_encode($datas), true); 
		$items_id = array_column($items, 'ID'); 
		return $items_id;
	}

	/* *
	 * Returns class_id,name,archived array from teacher id
	 *
	 */
	public function get_teacher_classes($teacher_id){
		global $wpdb;
		$classes_teachers = $wpdb->prefix . 'classes_teachers';
		$classes = $wpdb->prefix . 'classes';
 
		$sql = 'SELECT `class_id`, `name` as `class_name`, `archived` FROM '. $classes_teachers .' AS `t` JOIN '. $classes .' AS `c` ON t.`class_id` = c.`id` WHERE t.`teacher_id` = %d';
		$results = $wpdb->get_results( 
			$wpdb->prepare($sql, $teacher_id) 
		);

		return $results;

	}
	
	/**
	 * Checks for a valid value for the reassign parameter when deleting users.
	 *
	 * The value can be an integer, 'false', false, or ''.
	 *
	 * @since 4.7.0
	 *
	 * @param int|bool        $value   The value passed to the reassign parameter.
	 * @param WP_REST_Request $request Full details about the request.
	 * @param string          $param   The parameter that is being sanitized.
	 * @return int|bool|WP_Error
	 */
	public function check_reassign( $value, $request, $param ) {
		if ( is_numeric( $value ) ) {
			return $value;
		}

		if ( empty( $value ) || false === $value || 'false' === $value ) {
			return false;
		}

		return wushka_rest_error('invalid_param');
	}

	protected function remove_teacher_from_class($teacher_id) {
		global $wpdb;
		$table = $wpdb->prefix . 'classes_teachers';
  
		$wpdb->delete( $table, array( 'teacher_id' => $teacher_id ) ); 
  
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

	public function update_validation($request){
		$invalid_params = [];
		if( isset($request['username']) && empty($request['username']) ){
			$invalid_params[] .= 'username'; 
		}

		if( isset($request['email']) && empty($request['email']) ){
			$invalid_params[] .= 'email'; 
		}

		if( isset($request['first_name']) && empty($request['first_name']) ){
			$invalid_params[] .= 'first_name'; 
		}

		if( isset($request['last_name']) && empty($request['last_name']) ){
			$invalid_params[] .= 'last_name'; 
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
		if ( isset( $request['email'] ) && ! empty( $schema['properties']['email'] ) ) {
			$prepared_user->email = $request['email'];
		} 

		if ( isset( $request['first_name'] ) && ! empty( $schema['properties']['first_name'] ) ) {
			$prepared_user->first_name = $request['first_name'];
		}

		if ( isset( $request['last_name'] ) && ! empty( $schema['properties']['last_name'] ) ) {
			$prepared_user->last_name = $request['last_name'];
		}

		//Optional arguments
		if( isset( $request['username'] ) ){
			$prepared_user->username = $request['username'];
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

	public function prepare_item_for_response( $item, $request )
	{
		$data   = array();
		$items = json_decode(json_encode($item), true); 
		$fields = $items['data'];  
		if ( array_key_exists( 'ID', $fields ) ) { 
			$data['id'] = $item->ID;
		}

		if ( array_key_exists( 'user_login', $fields) ) {
			$data['username'] = $item->user_login;
		}

		/* if ( array_key_exists( 'display_name', $fields) ) {
			$data['display_name'] = $item->display_name;
		} */

		if ( array_key_exists( 'user_email', $fields) ) {
			$data['email'] = $item->user_email;
		} 

		if ( array_key_exists( 'ID', $fields) ) {
			$data['first_name'] = get_user_meta( $item->ID, 'first_name', true );
			$data['last_name'] = get_user_meta( $item->ID, 'last_name', true );
			$data['description'] = get_user_meta( $item->ID, 'description', true );
		}
  
		if ( array_key_exists( 'user_registered', $fields) ) {
			$data['registered_date'] = gmdate( 'c', strtotime( $item->user_registered ) );
		}
  
		if ( array_key_exists( 'user_status', $fields) ) {
			$data['user_status'] = $item->user_status;
		}
 
		if ( array_key_exists( 'roles', $items) ) { 
			// Defensively call array_values() to ensure an array is returned.
			$data['roles'] =  array_values($items['roles']);
		}   

		$s_code = $item->tmp_pwd_verify;
		$s_date = $item->tmp_pwd_window;
		$data['user_activated'] = true;
		if( isset($s_code, $s_date) && ! empty($s_code) && ! empty($s_date) ) {
			$data['user_activated'] = false;
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
		return apply_filters( 'rest_prepare_teacher', $response, $item, $request );
	}

	/**
	 * Retrieves the user's schema, conforming to JSON Schema.
	 * 
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
					'description' => __( 'First name for the teacher.' ),
					'type'        => 'string',
					'context'     => array( 'embed', 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'last_name'          => array(
					'required'    => true,
					'description' => __( 'Last name for the teacher.' ),
					'type'        => 'string',
					'context'     => array( 'embed', 'view', 'edit' ), 
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field', 
					),
				),
				'email'              => array(
					'required'    => true,
					'type'        => 'string',
					'description' => __( 'The email address for the teacher.' ),
					'format'      => 'email',
					'context'     => array( 'embed', 'view', 'edit' ), 
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_email',
					),
				), 
				'username'              => array(
					'required'    => false,
					'type'        => 'string',
					'description' => __( 'The username for the teacher.' ), 
					'context'     => array( 'embed', 'view', 'edit' ), 
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_user',
					),
				),
			),
		);
 
		$schema['properties']['meta'] = $this->meta->get_field_schema();

		$this->schema = $schema;

		return $this->add_additional_fields_schema( $this->schema );
	}
 

}
