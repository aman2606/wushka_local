<?php
/**
 * REST API: WP_REST_Classes_Controller class
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
class WP_REST_Classes_Controller extends WP_REST_Controller {
	
	protected $meta;

	public function __construct() {
		$this->namespace = 'api/v1';
		$this->rest_base = 'classes';
		$this->rest_base_with_teachers = 'classesteachers';
		$this->rest_base_with_students = 'classesstudents';
		$this->rest_base_bulk = 'bulkclasses';

		$this->meta = new WP_REST_User_Meta_Fields();
	}
	 
	public function register_routes() {
        //GET , POST classes
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

        //GET, PUT/PATCH, DELETE classes
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)',
			array(
				'args'   => array(
					'id' => array(
						'description' => __( 'Unique identifier for the classes.' ),
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
        
        
        //PUT, DELETE classesteachers
        register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_with_teachers . '/(?P<id>[\d]+)',
			array(
				'args'   => array(
					'id' => array(
						'description' => __( 'Unique identifier for the classes.' ),
						'type'        => 'integer',
					),
                ), 
                array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_teacher_items' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ), 
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'remove_teachers' ),
					'permission_callback' => array( $this, 'delete_item_permissions_check' )
				), 
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
        
        //PUT, DELETE classesstudents
        register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_with_students . '/(?P<id>[\d]+)',
			array(
				'args'   => array(
					'id' => array(
						'description' => __( 'Unique identifier for the classes.' ),
						'type'        => 'integer',
					),
				), 
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'remove_students' ),
					'permission_callback' => array( $this, 'delete_item_permissions_check' )
                ), 
                array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_student_items' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ), 
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
        );
        
        //POST bulkclasses
        register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_bulk,
			array( 
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_class_items' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
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
		
        $datas = wushka_get_classes( $school_id );
		if ( is_wp_error( $datas ) ) {
			return $datas;
		} 
		$classes = array();
		foreach ( $datas as $data ) { 
            
			$class = $this->prepare_item_for_response( $data, $request );
			$classes[] = $this->prepare_response_for_collection( $class );
		}

		$response = rest_ensure_response( $classes ); 
   

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
		$class = $this->get_class_detail( $request['id'] );
  
		if ( is_wp_error( $class ) ) {
			return $class;
		}
		 
		if(!current_user_can( 'school' , get_current_user_id())){
			return wushka_rest_error('access_denied');
		}

		$school_id = get_school_id_from_api_key($request);
		
		$datas = wushka_get_classes( $school_id );
		if ( is_wp_error( $datas ) ) {
			return $datas;
        }
        
        $classes = json_decode(json_encode($datas), true); 
		$classes_id = array_column($classes, 'id');  
		 
		if(!in_array( $class->id, $classes_id, true))
		{
			return wushka_rest_error('class_invalid_id');
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
        $class = $this->get_class_detail( $request['id'] );
		if ( is_wp_error( $class ) ) {
			return $class;
        } 
        
        $class     = $this->prepare_item_for_response( $class, $request );
		$response = rest_ensure_response( $class );

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
		if ( in_array( 'student', (array) $user->roles ) && in_array( 'teacher', (array) $user->roles ) ) {
			return new WP_Error(
				'rest_cannot_create_user',
				__( 'Sorry, you are not allowed to create new class.' ),
				array( 'status' => rest_authorization_required_code() )
			);
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
				__( 'Invalid Class parameter(s): '.$error),
				array( 'status' => 400 )
			);
		}

        if(isset($request['size']) && $request['size'] < 0 ){
            return wushka_rest_error('invalid_class_size'); 
        }
        
        
        if(isset($request['licence'])){
            $licence_product = 0;
            $current_date = date('Y-m-d H:i:s');
            //Get school term with school id 
            $term = get_term( $request['school_id'], "school" );
            if(!empty($term->slug)){
                //list licence of the slug
                $licence_available = get_school_licence_available($term->slug, $current_date); 
                //dd($request['licence']);
                if( !in_array($request['licence'], $licence_available ) ){
                    return new WP_Error(
                        'rest_invalid_licence',
                        __( 'Invalid licence.'),
                        array( 'status' => 400 )
                    );
                }
            }
        }

		$class = $this->prepare_item_for_database( $request );
        $class = (array) $class;
                
        $school_id = get_school_id_from_api_key($request);
        $user_id = get_current_user_id();
         
		$new_class = wushka_create_class($class['name'], $user_id, $school_id, $class['size'], $request['licence']); 
        
		if ( empty( $new_class['id'] ) ) {
			return new WP_Error(
				'rest_class_error',
				__( "Sorry, class could not be created." ),
				array( 'status' => 400 )
			);
        } 

        if(isset($request['teachers'])){ 
            $teachers = $this->update_teachers_to_class($request, $new_class['id']);   
        }

        if(isset($request['students'])){ 
            $students = $this->update_students_to_class($request, $new_class['id']);   
        } 
        $class = $this->get_class_detail( $new_class['id'] );  
        if(isset($teachers) && !empty($teachers['invalid'])){
            $class->invalid_teacher = $teachers['invalid']; 
        } 
        if(isset($students) && !empty($students['invalid'])){
            $class->invalid_student = $students['invalid']; 
        } 
		do_action( 'rest_after_insert_class', $class, $request, true );

		$response = $this->prepare_item_for_response( $class, $request );
		$response = rest_ensure_response( $response );
		$response->set_data(
			array(
				'created'  => true,
				'data' => $response->get_data(),
			)
		);

		$response->set_status( 201 );
		$response->header( 'Location', rest_url( sprintf( '%s/%s/%d', $this->namespace, $this->rest_base, $new_class['id'] ) ) );

		return $response;
    }
    

    public function create_class_items($request){
        $classes = $request['classes'];
        
        $invalid = []; 
        $valid = [];
        $created_data = [];
        foreach($classes as $class){
            $data = array_map( 'sanitize_text_field' , $class);
            if(array_key_exists('name', $data)){
                if( ! empty( $data['name'] ) ){ 

                    $error = false;
                    if(isset($data['licence'])){
                        $licence_product = 0;
                        $current_date = date('Y-m-d H:i:s');
                        //Get school term with school id 
                        $term = get_term( get_school_id_from_api_key($request), "school" );
                        if(!empty($term->slug)){
                            //list licence of the slug
                            $licence_available = get_school_licence_available($term->slug, $current_date); 
                            //dd($request['licence']);
                            if( !in_array($data['licence'], $licence_available ) ){
                                $data['message'] = 'Invalid licence.';
                                array_push($invalid, $data );    
                                $error = true;
                            }
                        }
                    }

                    if(isset($data['size']) && !is_numeric($data['size']) ){
                        $data['message'] = 'Invalid class size.';
                        array_push($invalid, $data );    
                        $error = true;
                    }
                    
                    if(!$error){
                        array_push($valid, $data);
 
                        $class = $this->prepare_item_for_database( $data );
                        $class = (array) $class;
                                
                        $school_id = get_school_id_from_api_key($request);
                        $user_id = get_current_user_id();
                         
                        $new_class = wushka_create_class($class['name'], $user_id, $school_id, $class['size'], $data['licence']); 
     
                        array_push($created_data, array(
                            'id' => $new_class['id'], 
                            'name' => $class['name'], 
                            'size' => $class['size'], 
                            'licence' => $new_class['licence_product'], 
                        ) );
                    }                   
 
                }else{
                    $data['message'] = 'Fields can not be empty.';
                    array_push($invalid, $data );    
                }
            }else{ 
                $data['message'] = 'Missing parameter(s).';
                array_push($invalid, $data ); 
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
        $school_id = get_school_id_from_api_key($request); 
        $datas = wushka_get_classes( $school_id );
		if ( is_wp_error( $datas ) ) {
			return $datas;
        } 

        $classes = json_decode(json_encode($datas), true); 
		$classes_id = array_column($classes, 'id');  
		if(!in_array( (string) $request['id'], $classes_id, true))
		{
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
        $school_id = get_school_id_from_api_key($request);
        //Get class details
        $class = $this->get_class_detail( $request['id'] );
		if ( is_wp_error( $class ) ) {
			return $class;
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

        if( ! isset($request['size']) ){
            $request['size'] = (int) $class->students;
        }

        if(isset($request['size']) && $request['size'] < 0 ){
            return wushka_rest_error('invalid_class_size'); 
        }

        $prepared_data = $this->prepare_item_for_database( $request );
		$prepared_data = (array) $prepared_data; 
        
        if(!$prepared_data){
            return wushka_rest_error('invalid_data');  
        } 
        
        $prepared_data['id'] = $class->id;
        if($prepared_data['size']){
            $prepared_data['students'] = $prepared_data['size'];
            unset($prepared_data['size']);
        }

        if(isset($request['licence'])){
            $licence_product = 0;
            $current_date = date('Y-m-d H:i:s');
            //Get school term with school id 
            $term = get_term( $school_id, "school" );
            if(!empty($term->slug)){
                //list licence of the slug
                $licence_available = get_school_licence_available($term->slug, $current_date); 
                //dd($request['licence']);
                if( !in_array($request['licence'], $licence_available ) ){
                    return new WP_Error(
                        'rest_invalid_licence',
                        __( 'Invalid licence.'),
                        array( 'status' => 400 )
                    );
                }
            }
            $prepared_data['licence_product'] = $request['licence'];
        }

        $update = wushka_update_class( $prepared_data );
  
        if ( is_wp_error( $update ) ) {
			return $update;
        } 
        $atts['school_id'] = get_school_id_from_api_key($request); 
        $atts['class_id'] = $class->id; 

        $teachers = $this->get_teachers($atts);
        if(isset($request['teachers'])){ 
            $teachers = $this->update_teachers_to_class($request, $class->id);  
        }

        
        $students = $this->get_students($atts);
        if(isset($request['students'])){ 
            $students = $this->update_students_to_class($request, $class->id);   
        }  
        $class = $this->get_class_detail( $class->id ); 
        $response_array = array(
            'updated'  => true, 
            'data' => array(
                'id' => $class->id,
                'name' => $class->name,
                'size' => $class->students,
                'licence' => $class->licence_product,
                'teachers' => $teachers,
                'students' => $students

            )
        );
  
		$response = rest_ensure_response( $response_array );
		 

		return $response;
        
    }

    public function update_teacher_items($request){
        //Get class details
        $class = $this->get_class_detail( $request['id'] );
		if ( is_wp_error( $class ) ) {
			return $class;
        }

        //Check teachers param
        if( ! isset( $request['teachers'] ) ){
            return new WP_Error(
				'rest_missing_callback_param',
				__( 'Missing parameter(s): teachers' ),
				array( 'status' => 400 )
			);
        }

        //Check teachers param empty
        if( empty( $request['teachers'] ) ){
            return new WP_Error(
				'rest_missing_value',
				__( 'Missing values for teachers' ),
				array( 'status' => 400 )
			);
        }

        //Get all teachers id of the school
        $school_id = get_school_id_from_api_key($request); 
        $datas = wushka_get_school_users($school_id, 'teacher'); 
		if ( is_wp_error( $datas ) ) {
			return $datas;
        } 
        $school_teacher = json_decode(json_encode($datas), true); 
		$school_teacher_id = array_column($school_teacher, 'ID');
          
        //Sanitize input
        $teachers = $request['teachers'];
        $teachers = array_map( 'sanitize_text_field' , $teachers); 
    

        //get existing teacher for the class
        $atts['school_id'] = $school_id;
        $atts['class_id'] = $request['id'];
        $existing_datas = wushka_get_teachers( $atts );
		if ( is_wp_error( $existing_datas ) ) {
			return $existing_datas;
        } 

        $existing_teachers = json_decode(json_encode($existing_datas), true); 
		$existing_teachers_id = array_column($existing_teachers, 'teacher_id');
          
        $invalid; 
        $valid = []; 
        $updated_data = []; 
        foreach($teachers as $teacher){
            //Check if data is number or not
            if(is_numeric($teacher)){  
                //Check if request id exist for class teacher
                if(in_array( (int) $teacher, $school_teacher_id, true)){ 
                    if(!in_array($teacher, $existing_teachers_id, true)){
                        $data['id'] = $class->id;
                        $data['teacher_add'] = get_user_meta($teacher, 'id_hash', true);
                        $data['school_id'] = $school_id;

                        $update = wushka_update_class_teachers( $data );

                        if($update){
                            $valid[] .= $teacher;
                            array_push($updated_data, array(
                                'id' => $teacher, 
                                'first_name' => get_user_meta( $teacher , 'first_name', true), 
                                'last_name' => get_user_meta( $teacher , 'last_name', true),   
                                'class_id' => $class->id, 
                            ) );
                        }else{
                            $invalid[] = array(
                                'id' => $teacher,
                                'message' => 'Invalid id.',
                            ); 
                        }
                    }else{
                        $invalid[] = array(
                            'id' => $teacher,
                            'message' => 'Teacher id already exist in this class.',
                        ); 
                    }    
                }else{
                    $invalid[] = array(
                        'id' => $teacher,
                        'message' => 'Invalid id.',
                    ); 
                }  
            }else{  
                $invalid[] = array(
                    'id' => $teacher,
                    'message' => 'Invalid data format.',
                );
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
            'updated'  => true,
            'valid_data'  => $valid,
            'invalid_data'  => $invalid, 
            'updated_records' => $updated_data
        );
        $response->set_data($response_array);
        
        return rest_ensure_response( $response ); 
    }
    
    public function update_student_items($request){
        $school_id = get_school_id_from_api_key($request);
        //Get all students  
        $datas = wushka_get_classes( $school_id );
		if ( is_wp_error( $datas ) ) {
			return $datas;
        } 

        //Check students param
        if( ! isset( $request['students'] ) ){
            return new WP_Error(
				'rest_missing_callback_param',
				__( 'Missing parameter(s): students' ),
				array( 'status' => 400 )
			);
        }

        //Check students param empty
        if( empty( $request['students'] ) ){
            return new WP_Error(
				'rest_missing_value',
				__( 'Missing values for students' ),
				array( 'status' => 400 )
			);
        }

        $classes = json_decode(json_encode($datas), true); 
		$classes_id = array_column($classes, 'id');  

        //Get class details
        $class = $this->get_class_detail( $request['id'] );
		if ( is_wp_error( $class ) ) {
			return $class;
        }  

        //Sanitize input
        $students =  $request['students']; 
        $students = array_map( 'sanitize_text_field' , $students); 

 
        $invalid; 
        $valid;
        $updated_data = []; 
        foreach($students as $student){  
            if( ! empty($student) ){
                if(is_numeric($student)){  
                    //Check if student id exist or not
                    $user = get_userdata( $student ); 
                    if($user){ 
                        //Check if student belongs to same school or not
                        $student_class = get_user_meta( $student , 'class', true); 
                        if( in_array($student_class, $classes_id, true) || $school_id == get_user_meta( $student , 'school_id', true) ){
                            $update = update_user_meta($student, 'class', $class->id, $student_class );
                            if( $update !== FALSE){ 
                                $valid[] .= $student; 
                                //If User was inactive, make active
                                $o_user = get_user_meta( $student , 'active', true);
                                if( ! isset($o_user) || empty($o_user) || $o_user == '0' || $o_user == 0 ){
                                    update_user_meta($student, 'active', 1);
                                }  
                                array_push($updated_data, array(
                                    'id' => $student, 
                                    'first_name' => get_user_meta( $student , 'first_name', true), 
                                    'last_name' => get_user_meta( $student , 'last_name', true),  
                                    'username' => $user->user_login,  
                                    'class_id' => $class->id, 
                                ) );

                            }else{
                                $invalid[] = array(
                                    'id' => $student,
                                    'message' => 'Sorry, this student already exist on this class.', 
                                ); 
                            }
                        }else{
                            $invalid[] = array(
                                'id' => $student,
                                'message' => 'Invalid student id.', 
                            ); 
                        } 
                    }else{
                        $invalid[] = array(
                            'id' => $student,
                            'message' => 'Invalid student id.', 
                        ); 
                    } 
                }else{
                    $invalid[] = array(
                        'id' => $student,
                        'message' => 'Invalid data format.', 
                    ); 
                }
            }else{
                $invalid[] = array(
                    'id' => $student,
                    'message' => 'Fields can not be empty', 
                ); 
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
            'updated'  => true,
            'valid_data'  => $valid,
            'invalid_data'  => $invalid, 
            'updated_records' => $updated_data
        );
        $response->set_data($response_array);
        
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
                
		$class = $this->get_class_detail( $request['id'] );
		if ( is_wp_error( $class ) ) {
			return $class;
        }
 		
		//Allow to delete only from same school
		$school_id = get_school_id_from_api_key($request);
		
		$datas = wushka_get_classes( $school_id );
		if ( is_wp_error( $datas ) ) {
			return $datas;
		}
        
        $classes = json_decode(json_encode($datas), true); 
		$classes_id = array_column($classes, 'id');   
		if(!in_array( $class->id, $classes_id, true))
		{
			return wushka_rest_error('class_invalid_id');
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
		$class = $this->get_class_detail( $request['id'] );
		if ( is_wp_error( $class ) ) {
			return $class;
        }
 		 
        $id       = $class->id; 
 		 
		$request->set_param( 'context', 'edit' );

		$previous = $this->prepare_item_for_response( $class, $request );
		$result = $this->delete_class( $id );
 
		if ( ! $result ) {
			return new WP_Error(
				'rest_cannot_delete',
				__( 'The class cannot be deleted.' ),
				array( 'status' => 500 )
			);
		}

        $classes = $this->remove_teacher_from_class( $id ); 
        $students = $this->archive_students_of_class( $id );

		$response = new WP_REST_Response();
		$response->set_data(
			array(
				'deleted'  => true,
				'previous' => $previous->get_data(),
			)
		);

		//Fires immediately after a user is deleted via the REST API.		
		do_action( 'rest_delete_class', $class, $response, $request );

		return $response;
	}
  

    public function remove_teachers( $request ) {  
        $class = $this->get_class_detail( $request['id'] );
		if ( is_wp_error( $class ) ) {
			return $class;
        }

        $atts['school_id'] = get_school_id_from_api_key($request);
        $atts['class_id'] = $request['id'];
        $datas = wushka_get_teachers( $atts );
		if ( is_wp_error( $datas ) ) {
			return $datas;
        } 

        $existing_teachers = json_decode(json_encode($datas), true); 
		$existing_teachers_id = array_column($existing_teachers, 'teacher_id');
        
        
        $teachers = $request['teachers'];
        $teachers = array_map( 'sanitize_text_field' , $teachers); 
    
        $invalid = []; 
        $valid = []; 
        foreach($teachers as $teacher){   
            //Check if data is number or not
            if(is_numeric($teacher)){ 
                //Check if request id exist for class teacher
                if(in_array( $teacher, $existing_teachers_id, true)){
                    $user = get_userdata( $teacher );
                    //Check if user exist
                    if( $user ){
                        $valid[] .= $teacher;
                        $remove = $this->remove_specific_teacher_from_class($atts['class_id'], $atts['school_id'] , $teacher);
                    }else{
                        $invalid[] = array(
                            'id' => $teacher,
                            'message' => "Invalid id.",
        
                        );    
                    }
                }else{
                    $invalid[] = array(
                        'id' => $teacher,
                        'message' => "Invalid id.",
    
                    ); 
                }  
            }else{  
                $invalid[] = array(
                    'id' => $teacher,
                    'message' => "Invalid data format.",

                );
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
 
        $request->set_param( 'context', 'edit' ); 
        $class->hide_students = true;
		$previous = $this->prepare_item_for_response( $class, $request ); 
    
        $response = new WP_REST_Response();
        $response_array = array(
            'deleted'  => true,
            'valid_id'  => $valid,
            'invalid_id'  => $invalid,
            'previous' => $previous->get_data(),
        );
		$response->set_data($response_array);
        error_log($response_array);

		//Fires immediately after a user is deleted via the REST API.		
		do_action( 'rest_delete_classteacher', $class, $response, $request );

		return $response;
  
    }

    public function remove_students($request) {
        $class = $this->get_class_detail( $request['id'] );
		if ( is_wp_error( $class ) ) {
			return $class;
        }


        $existing_students = $this->get_students($request['id']);
        $existing_students_id = array_column($existing_students, 'id');
        
        
        $students = $request['students'];
        $students = array_map( 'sanitize_text_field' , $students);  
        //if no validation or sanitization, do it below 
        $invalid = []; 
        $valid = []; 
        foreach($students as $student){    
            //Check if data is number or not
            if(is_numeric($student)){ 
                //Check if request id exist for student 
                if(in_array( (int) $student, $existing_students_id, true)){ 
                    $user = get_userdata( $student );
                    //Check if user exist
                    if( $user ){
                        $valid[] .= $student;
                        //update_user_meta($student, 'active', 0); 
                        //Remove student from class
                        delete_user_meta($student, 'class', $request['id']); 
                        //Set student to school
                        if( !get_user_meta( $student, 'school_id', true ) ){
                            $school_id = get_school_id_from_api_key($request);
                            add_user_meta( $student, 'school_id', $school_id );
                        }

                    }else{
                        $invalid[] = array(
                            'id' => $student,
                            'message' => "Invalid id.",
        
                        );  
                    }
                }else{
                    $invalid[] = array(
                        'id' => $student,
                        'message' => "Invalid id.",
    
                    );
                }  
            }else{  
                $invalid[] = array(
                    'id' => $student,
                    'message' => "Invalid data format.",

                );
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
 
        $request->set_param( 'context', 'edit' ); 
        $class->hide_teachers = true;
		$previous = $this->prepare_item_for_response( $class, $request ); 
    
        $response = new WP_REST_Response();
        $response_array = array(
            'deleted'  => true,
            'valid_id'  => $valid,
            'invalid_id'  => $invalid,
            'previous' => $previous->get_data(),
        );
		$response->set_data($response_array); 

		//Fires immediately after a user is deleted via the REST API.		
		//do_action( 'rest_delete_classesstudents', $class, $response, $request );

		return $response;
    }
 
    public function get_school_years_name($school_year){         
        $class_year = explode(':', $school_year); 
        ( !empty( $class_year[0] ) ) ? $name = $class_year[1] : $name = '' ; 
        return $name; 
    }


    public function get_students($class_id){
        $args = array(
            'role'       => 'student', 
            'meta_query' => array(
                'relation' => 'AND',
                0 => array(
                    'key'     => 'class',
                    'value'   =>  $class_id
                ),
                1 => array(
                    'key'     => 'active',
                    'value'   => 1
                )
            )
        ); 
        $users = new WP_User_Query($args);

        $students = array();
        foreach( $users->results as $user ) {
            $students[] = array(
                'id'            =>  $user->ID,
                'username'      =>  $user->user_login,
                'first_name'    =>  get_user_meta($user->ID, 'first_name', true),
                'last_name'     =>  get_user_meta($user->ID, 'last_name', true),
            );
        }         
        return $students;
    }

    public function get_teachers($atts){
        $teachers = wushka_get_teachers($atts);

        $class_teacher = array();
        foreach($teachers as $teacher){
            $user = get_user_by('id', $teacher->teacher_id);
            if($user){  
                $class_teacher[] = array(
                            'id'              =>      $teacher->teacher_id,
                            'username'        =>      $user->data->user_login,
                            'first_name'      =>      get_user_meta($teacher->teacher_id, 'first_name', true),
                            'last_name'       =>      get_user_meta($teacher->teacher_id, 'first_name', true),
                );
            }
        }
        return $class_teacher; 
    }

    public function get_class_detail($class_id){
        global $wpdb; 
        $s_query = 'SELECT *, id as class_id FROM '.$wpdb->prefix.'classes WHERE id = %d';  
        $class = $wpdb->get_results(
            $wpdb->prepare($s_query, $class_id)
        );
        if($class){
            return $class[0];
        }
        return wushka_rest_error('class_invalid_id');
        
    }

    public function update_teachers_to_class($request, $class_id){
        $atts['school_id'] = get_school_id_from_api_key($request);
        $datas = wushka_get_teachers( $atts );
		if ( is_wp_error( $datas ) ) {
			return $datas;
        } 

        $existing_teachers = json_decode(json_encode($datas), true); 
		$existing_teachers_id = array_column($existing_teachers, 'ID');
        
        $teachers = $request['teachers'];
        $teachers = array_map( 'sanitize_text_field' , $teachers); 
    
        //Get current class teachers
        $atts['class_id'] = $class_id;
        $current_teachers  = $this->get_teachers($atts);
        $current_teachers_id  =  array_column($current_teachers, 'id');

        $invalid = []; 
        $valid = []; 
        $message = [];  
        foreach($teachers as $teacher){             
            //Check if data is number or not
            if(is_numeric($teacher) && $teacher > 0){ 
                //Check if request id exist for class teacher
                if(in_array( (int) $teacher, $existing_teachers_id, true)){
                    $user = get_userdata( $teacher );
                    //Check if user exist
                    if( $user ){
                        if(!in_array( $teacher, $current_teachers_id, true)){  
                            $data['id'] = $class_id;
                            $data['teacher_add'] = get_user_meta($user->ID, 'id_hash', true);
                            $data['school_id'] = $atts['school_id'];
                            $update = wushka_update_class_teachers($data); 
                            if($update){ 
                                array_push($valid, $teacher); 
                            }else{ 
                                array_push($invalid, $teacher); 
                                $message = array(
                                    'id'    =>  $teacher,
                                    'message'    =>  'An error occured.',
                                ); 
                                array_push($invalid, $message); 
                            } 
                        }else{
                            $message = array(
                                'id'    =>  $teacher,
                                'message'    =>  'Teacher already exist.',
                            );
                            array_push($invalid, $message); 
                        } 
                    }else{
                        $message = array(
                            'id'    =>  $teacher,
                            'message'    =>  'No teacher found.',
                        ); 
                        array_push($invalid, $message); 
                    }
                }else{
                    $message = array(
                        'id'    =>  $teacher,
                        'message'    =>  'You are not allowed to add this teacher to class.',
                    );
                    array_push($invalid, $message);  
                }  
            }else{  
                $message = array(
                    'id'    =>  $teacher,
                    'message'    =>  'Invalid data.',
                ); 
                array_push($invalid, $message); 
            }
        }

        $updated_teacher = false;
        if($valid){
            $updated_teacher = true;
        }

        return array(
            'updated'   =>      $updated_teacher,
            'valid'     =>      $valid,
            'invalid'   =>      $invalid,
        );
    }
 
    public function update_students_to_class($request, $class_id){
        $school_id = get_school_id_from_api_key($request);
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

		//Get list of students from the school 
		$datas = wushka_get_students( $class_ids, $key ); 
		if ( is_wp_error( $datas ) ) {
			return new WP_Error(
				'rest_classes_error',
				__( 'Sorry, no student found' ),
				array( 'status' => rest_authorization_required_code() )
			);
        }
        
        //Get only ids from the list of students
        $existing_students = json_decode(json_encode($datas), true); 
		$existing_students_id = array_column($existing_students, 'ID');
  
        //Sanitize input
        $students =  $request['students']; 
        $students = array_map( 'sanitize_text_field' , $students); 

 
        $invalid; 
        $valid = [];
        $updated_data = []; 
        foreach($students as $student){  
            if( ! empty($student) ){
                if(is_numeric($student)){  
                    //Check if student id exist or not
                    $user = get_userdata( $student ); 
                    if($user){ 
                        //Check if student belongs to same school or not
                        $student_class = get_user_meta( $student , 'class', true); 
                        if( in_array($student_class, $class_ids, true) ){
                            $update = update_user_meta($student, 'class', $class_id, $student_class );
                            if( $update !== FALSE){ 
                                $valid[] .= $student; 
                                //If User was inactive, make active
                                $o_user = get_user_meta( $student , 'active', true);
                                if( ! isset($o_user) || empty($o_user) || $o_user == '0' || $o_user == 0 ){
                                    update_user_meta($student, 'active', 1);
                                }  
                                array_push($updated_data, array(
                                    'id' => $student,   
                                    'class_id' => $class_id, 
                                ) );

                            }else{
                                $invalid[] = array(
                                    'id' => $student,
                                    'message' => 'Sorry, this student already exist on the same class.', 
                                ); 
                            }
                        }else{
                            $invalid[] = array(
                                'id' => $student,
                                'message' => 'Sorry, you are not allowed to update this student.', 
                            ); 
                        } 
                    }else{
                        $invalid[] = array(
                            'id' => $student,
                            'message' => 'Student not found with such id.', 
                        ); 
                    } 
                }else{
                    $invalid[] = array(
                        'id' => $student,
                        'message' => 'Invalid id.', 
                    ); 
                }
            }else{
                $invalid[] = array(
                    'id' => $student,
                    'message' => 'Fields can not be empty', 
                ); 
            }  
        }

        $updated_student = false;
        if($valid){
            $updated_student = true;
        }

        return array(
            'updated'   =>      $updated_student,
            'valid'     =>      $valid,
            'invalid'   =>      $invalid,
        );
    }

    public function delete_class($class_id){
        global $current_user;
        $current_user = wp_get_current_user();

        $data['id'] = $class_id;
        $data['archived']      = TRUE;
        $data['archived_date'] = current_time('mysql');
        $data['archived_by']   = $current_user->ID;

        $results = wushka_update_class($data);
        
        /* 
        global $wpdb; 
        $table = $wpdb->prefix . 'classes';
        $results = $wpdb->delete( $table, array( 'id' => $class_id ) );   
        */
        if($results){
            return true;
        }
        return false;
    }

    public function remove_teacher_from_class($class_id){
        global $wpdb;
		$table = $wpdb->prefix . 'classes_teachers';
        $wpdb->delete( $table, array( 'class_id' => $class_id ) ); 
    }

    public function remove_specific_teacher_from_class($class_id, $school_id , $teacher_id){
        global $wpdb;
		$table = $wpdb->prefix . 'classes_teachers';
        $wpdb->delete( $table, array( 
            'class_id' => $class_id ,
            'school_id' => $school_id ,
            'teacher_id' => $teacher_id ,
            ) 
        ); 
    }

    public function archive_students_of_class($class_id){
        $args = array(
            'role'       => 'student', 
            'meta_query' => array(
                'relation' => 'AND',
                0          => array(
                    'key'   => 'class',
                    'value' => $class_id
                ),
                1          => array(
                    'key'   => 'active',
                    'value' => 1
                )
            )
        );
        $students = new WP_User_Query($args);

        $students = json_decode(json_encode($students->results), true); 
		$existing_student_ids = array_column($students, 'ID'); 
        foreach($existing_student_ids as $student_id){
            $archive = update_user_meta($student_id, 'active', 0);
            error_log('Student Archived: '. $student_id);  
        } 

    }

	/* *
	 * Return only id array from user object
	 *
	 */
	/* public function get_teachers_id($datas){
		$items = json_decode(json_encode($datas), true); 
		$items_id = array_column($items, 'ID'); 
		return $items_id;
	} */
	
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
	/* public function check_reassign( $value, $request, $param ) {
		if ( is_numeric( $value ) ) {
			return $value;
		}

		if ( empty( $value ) || false === $value || 'false' === $value ) {
			return false;
		}

		return new WP_Error(
			'rest_invalid_param',
			__( 'Invalid user parameter(s).' ),
			array( 'status' => 400 )
		);
	} */

	/* protected function remove_teacher_from_class($teacher_id) {
		global $wpdb;
		$table = $wpdb->prefix . 'classes_teachers';
  
		$wpdb->delete( $table, array( 'teacher_id' => $teacher_id ) ); 
  
	} */

	/**
	 * Validates the user's input.
	 *  
	 * @return string empty field names.
	 */
	public function required_validation($request) {
		if ( empty( $request['name'] ) ) {	
			$invalid_params = 'name';   
			return $invalid_params;
		}

		return;
	}

	public function update_validation($request){
		$invalid_params = [];
		if( isset($request['name']) && empty($request['name']) ){
			$invalid_params[] .= 'name'; 
		}

		if( isset($request['size']) && empty($request['size']) ){
			$invalid_params[] .= 'size'; 
		}
 
 

		if( !empty($invalid_params) ){ 
			$invalid_params = implode (", ", $invalid_params);
		}

		return $invalid_params;
	}

	/* public function update_user_login($user_id, $username){
		global $wpdb; 
		$wpdb->update($wpdb->users, array('user_login' => $username), array('ID' => $user_id));
	} */

	/**
	 * Prepares a single user for creation or update.
	 *
	 * @since 4.7.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return object User object.
	 */
	protected function prepare_item_for_database( $request ) {
		
		$prepared_class = new stdClass;

		$schema = $this->get_item_schema();

		// Required arguments.
		if ( isset( $request['name'] ) && ! empty( $schema['properties']['name'] ) ) {
			$prepared_class->name = $request['name'];
        } 
        
        //optional with default        
        $prepared_class->size = 50;
        if(isset($request['size'])){
            $prepared_class->size = $request['size'];
        } 
 
		// Filters user data before insertion via the REST API.		  
		return apply_filters( 'rest_pre_insert_class', $prepared_class, $request );
	}
 

	public function prepare_item_for_response( $item, $request ){
		$data   = array();
        $fields = json_decode(json_encode($item), true);    
         
        $atts['school_id'] = get_school_id_from_api_key($request); 
        $atts['class_id'] = $item->id; 
   
        if(array_key_exists('invalid_teacher', $fields)){
            $data['invalid_teachers_id'] =  $item->invalid_teacher;
        }

        if(array_key_exists('invalid_student', $fields)){
            $data['invalid_students_id'] =  $item->invalid_student;
        }

		if ( array_key_exists( 'id', $fields ) ) { 
			$data['id'] = $item->id;
		}

		if ( array_key_exists( 'name', $fields) ) {
			$data['name'] = $item->name;
        } 

        if ( array_key_exists( 'students', $fields) ) {
			$data['size'] = $item->students;
        } 
        
        if ( array_key_exists( 'licence_product', $fields) ) {
			$data['licence'] = $item->licence_product;
        } 
        
        if ( array_key_exists( 'year', $fields) ) {
			$data['year'] = $this->get_school_years_name($item->year);
        } 
        
        if(array_key_exists( 'id', $fields)){
            if(!array_key_exists( 'hide_teachers', $fields)){ 
                $data['teachers'] = array($this->get_teachers($atts));
            }
            if(!array_key_exists( 'hide_students', $fields)){ 
                $data['students'] = array($this->get_students($item->id));
            }
        }

        
		  
		$context = ! empty( $request['context'] ) ? $request['context'] : 'embed';

		$data = $this->add_additional_fields_to_object( $data, $request );
		$data = $this->filter_response_by_context( $data, $context );

		// Wrap the data in a response object.
		$response = rest_ensure_response( $data );

		 
		// Filters user data returned from the REST API.
		  
		return apply_filters( 'rest_prepare_classes', $response, $item, $request );
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
			'title'      => 'class',
			'type'       => 'object',
			'properties' => array( 
				'name'         => array(
					//'required'    => true,
					'description' => __( 'Class name for the school.' ),
					'type'        => 'string',
					'context'     => array( 'embed', 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
                ), 
                'size'         => array( 
					'description' => __( 'Class size for the school.' ),
					'type'        => 'integer',
					'context'     => array( 'embed', 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
                ),
                'licence'         => array(
					//'required'    => true,
					'description' => __( 'Class licence.' ),
					'type'        => 'string',
					'context'     => array( 'embed', 'view', 'edit' ),
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
