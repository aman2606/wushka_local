<?php
/**
 * REST API: WP_REST_Schools_Controller class
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
class WP_REST_Schools_Controller extends WP_REST_Controller {
	
	protected $meta;

	public function __construct() {
		$this->namespace = 'api/v1';
		$this->rest_base = 'schools'; 
		$this->rest_base_by_slug = 'schoolsbyslug'; 

		$this->meta = new WP_REST_User_Meta_Fields();
	}
	 
	public function register_routes() {
 
		//GET by id
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
				'schema' => array( $this, 'get_public_item_schema' ),
			)
        );


		//GET by slug
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_by_slug . '/(?P<slug>[\d]+)',
			array(
				'args'   => array(
					'slug' => array(
						'description' => __( 'Unique slug id for the schools.' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ), 
				), 
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
         
 
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

		if(isset($request['slug'])){
			$school_id = get_term_by('slug', $request['slug'], 'school');
			if($school_id){
				$request_id = $school_id->term_id;
			}else{
				return wushka_rest_error('school_invalid');
			}
		}else{
			$request_id = $request['id'];
		}

        $schools = get_terms([
            'taxonomy' => 'school',
            'hide_empty' => false,
        ]);

        $schools = json_decode(json_encode($schools), true); 
		$schools_id = array_column($schools, 'term_id');  
         
        if(!in_array( $request_id, $schools_id, true))
		{
			return wushka_rest_error('school_invalid_id'); 
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
		if(isset($request['slug'])){
			$school_id = get_term_by('slug', $request['slug'], 'school');
			if($school_id){
				$school_id = $school_id->term_id;
			}else{
				return wushka_rest_error('school_invalid_id','Invalid school slug.');
			}
		}else{
			$school_id = $request['id'];
		}
        
        $datas = wushka_get_classes( $school_id );
		if ( is_wp_error( $datas ) ) {
			return $datas;
        }  

        $school = get_term_by('id', $school_id, 'school');   

		$schools = array(
            'id' => $school->term_id,
            'name' => $school->name,
			'classes' => []
        );
		//dd($datas); 
		foreach ( $datas as $data ) {  
            $atts['school_id'] = $school_id;
            $atts['class_id'] = $data->id;

            $teacher_details = $this->get_teachers($atts);
             
            if ( is_wp_error( $teacher_details ) ) {
                return $teacher_details;
            } 
            $data->teachers = $teacher_details;

            $student_details = $this->get_students( $data->id );
            if ( is_wp_error( $student_details ) ) {
                return $student_details;
            } 
            $data->students = $student_details;  
			$school = $this->prepare_item_for_response( $data, $request );
			$schools['classes'][] = $this->prepare_response_for_collection( $school );
		}
         
        $response = rest_ensure_response( $schools ); 
         

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
                            'last_name'       =>      get_user_meta($teacher->teacher_id, 'last_name', true),
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

	public function prepare_item_for_response( $item, $request ){
		$data   = array();
        $fields = json_decode(json_encode($item), true);   
  
		if ( array_key_exists( 'id', $fields ) ) { 
            $data = array(
                'id' => $item->id,
                'name' => $item->name
            ); 

            $data['teachers'] = $item->teachers;
            $data['students'] = $item->students;
		} 
 
		  
		$context = ! empty( $request['context'] ) ? $request['context'] : 'embed';

		$data = $this->add_additional_fields_to_object( $data, $request );
		$data = $this->filter_response_by_context( $data, $context );

		// Wrap the data in a response object.
		$response = rest_ensure_response( $data );

		 
		// Filters user data returned from the REST API. 
		return apply_filters( 'rest_prepare_schools', $response, $item, $request );
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
			'title'      => 'school',
			'type'       => 'object',
			'properties' => array( 
				'name'         => array( 
					'description' => __( 'Name for the school.' ),
					'type'        => 'string',
					'context'     => array( 'embed', 'view', 'edit' ), 
                ),  
			),
		);
 
		$schema['properties']['meta'] = $this->meta->get_field_schema();

		$this->schema = $schema;

		return $this->add_additional_fields_schema( $this->schema );
	}
 

}
