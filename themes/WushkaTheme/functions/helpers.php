<?php 
//Includes all the helper or generic function for application

/*  =====================================================
*
*   Generic message for wp error
*    
*   @type       =   set type to display error 
*   @message    =   user define message set
*   @status     =   set http status code
*
=========================================================== */
function wushka_rest_error($type = null, $message = null, $status = null){
    switch($type){
        case "user_invalid_id":
            $error_message = 'Invalid user ID.';
            break;
        case "invalid_api_key":
            $error_message = 'Invalid API Key.';
            break;
        case "class_invalid_id":
            $error_message = 'Invalid class ID.';
            break;
        case "school_invalid_id":
            $error_message = 'Invalid school ID.';
            break;
        case "school_invalid":
            $error_message = 'Invalid school slug.';
            break;
        case "invalid_class_size":
            $error_message = 'Sorry, Size must be greater than 0.';
            break;
        case "access_denied":
            $error_message = 'Access Denied.'; 
            break;
        case "invalid_data":
            $error_message = 'Invalid set of data.';
            $status = 400;
            break;
        case "invalid_param":
            $error_message = 'Invalid parameter(s).';
            $status = 400;
            break;
        default:
            $type = 'error';  
            $error_message = 'An error occured'; 
            $status = 404;
            break;
    }
    
    //set rest_ prefi to type
    $rest_type = 'rest_'.$type;

    // check to set generic message or user defined
    if( !$message ){
        $message = $error_message;
    } 

    //Set 403 if no status param
    if( !$status){    
        $status = rest_authorization_required_code();
    } 

    $error = new WP_Error(
        $rest_type,
        __( $message ),
        array( 'status' => $status )
    );

    return $error; 
}
 