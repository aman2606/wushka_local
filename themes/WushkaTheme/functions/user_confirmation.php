<?php
/*
 * Add New School Account and Link to It's Term
 */
if( ! defined('ABSPATH') ) {
    exit;
} // Exit if accessed directly

/* ----------- User Account Confirmation Functions ----------- */

class User_Confirmation {

    public  $_a_return;
    private $_s_role;
    private $_o_user;
    private $_s_code;
    private $_s_pwd;

    public function __construct() {
        $this->_a_return = array(
            'status'  => FALSE,
            'url'     => NULL,
            'message' => 'Process Beginning'
        );
        $this->_s_role   = NULL;
        $this->_o_user   = NULL;
    }

    public function confirm_teacher() {
        $this->_s_role = 'teacher';
        $this->_s_code = NULL;
        //Function Validation
        if( ($b_status = $this->validate_parameters()) === TRUE ) {
            if( ($b_status = $this->activate_password()) === TRUE ) {
                $this->_a_return['status'] = 'success';
                $this->_a_return['url']    = home_url('/manage-class-list/');

                $teacher_school = wp_get_object_terms($this->_o_user->ID, 'school');
                $school_id      = NULL;
                if( isset($teacher_school) && ! empty($teacher_school) ) {
                    $school_id = $teacher_school[0]->term_taxonomy_id;
                }
                $a_event_args = array(
                    'school_id'   => (int)$school_id,
                    'event_type'  => 'admin',
                    'sub_type'    => 'teacher',
                    'action'      => 'activated',
                    'description' => ucwords($this->_o_user->first_name . ' ' . $this->_o_user->last_name) . ' has activated their account',
                    'meta_value'  => $this->_o_user->ID
                );

                wushka_load_school_event($a_event_args);
            }
        }

        return $this->_a_return;
    }

    private function validate_parameters() {
        //Ajax Validation
        $s_validate = json_decode(stripcslashes(filter_input(INPUT_POST, 's_validate')), TRUE);
        if( ! isset($s_validate) || ! wp_verify_nonce($s_validate, 'teacher_confirmation_validation') ) {
            $this->_a_return['status']  = 'failed';
            $this->_a_return['message'] = 'Validation Failed';

            return FALSE;
        }

        //User Hash
        $s_var_1 = json_decode(stripcslashes(filter_input(INPUT_POST, 's_var_1')), TRUE);
        if( isset($s_var_1) || ! empty($s_var_1) ) {
            $o_user = get_user_by_hash($s_var_1);
            if( $o_user !== FALSE ) {
                $this->_o_user = $o_user;
            }
        }

        if( $this->_o_user === NULL ) {
            $this->_a_return['status']  = 'failed';
            $this->_a_return['message'] = 'Invalid User';

            return FALSE;
        }

        //User Validation
        $s_var_2 = json_decode(stripcslashes(filter_input(INPUT_POST, 's_var_2')), TRUE);
        if( ! isset($s_var_2) || ! wp_verify_nonce($s_var_2, 'passcode_validation_' . (int)$this->_o_user->ID) ) {
            $this->_a_return['status']  = 'failed';
            $this->_a_return['message'] = 'User Validation Failed ( code:' . $s_var_2 . ' | user: ' . $this->_o_user->ID . ')';

            return FALSE;
        }

        //Confirmation Code
        $s_var_3 = json_decode(stripcslashes(filter_input(INPUT_POST, 's_var_3')), TRUE);
        if( ! isset($s_var_3) || empty($s_var_3) ) {
            $this->_a_return['status']  = 'failed';
            $this->_a_return['message'] = 'Missing Field';

            return FALSE;
        } else {
            if( (string)$s_var_3 == (string)$this->_o_user->tmp_pwd_verify ) {
                $this->_s_code = $s_var_3;
                $td_now        = new DateTime('NOW');
                $td_now->setTimezone(new DateTimeZone('UTC'));
                $now = $td_now->format('Y-m-d');

                $td_window = new DateTime($this->_o_user->tmp_pwd_window);
                $window    = $td_window->format('Y-m-d');

                if( $now > $window ) {
                    $this->_a_return['status']  = 'failed';
                    $this->_a_return['message'] = 'Window Has Closed';

                    return FALSE;
                }

            } else {
                $this->_a_return['status']  = 'failed';
                $this->_a_return['message'] = 'Code does not MATCH';

                return FALSE;
            }
        }

        //Password
        $s_var_4 = json_decode(stripcslashes(filter_input(INPUT_POST, 's_var_4')), TRUE);
        if( ! isset($s_var_4) || empty($s_var_4) ) {
            $this->_a_return['status']  = 'failed';
            $this->_a_return['message'] = 'Missing Field';

            return FALSE;
        } else {
            $this->_s_pwd = $s_var_4;
        }

        $this->_a_return['status']  = 'passed';
        $this->_a_return['message'] = 'Validation Passed';

        return TRUE;
    }

    private function activate_password() {
        //Set New Password
        wp_set_password($this->_s_pwd, $this->_o_user->ID);
        $a_creds = array(
            'user_login'    => $this->_o_user->user_login,
            'user_password' => $this->_s_pwd,
            'remember'      => FALSE
        );

        //Attempt Sign in
        error_log('attempting signon for id ' . $this->_o_user->ID . ' ' . print_r($a_creds, TRUE));
        // $user = wp_signon($a_creds, FALSE);
        $user = wp_signon($a_creds);
        error_log('user is ' . print_r($user, true));
        //If Successful sign in with new credentials
        if( ! is_wp_error($user) ) {
            error_log('User #' . $this->_o_user->ID . ' Has been Successfully Activated');

            //Remove Temp account & reminder email meta data fields
            delete_user_meta($this->_o_user->ID, 'tmp_pwd_verify');
            delete_user_meta($this->_o_user->ID, 'tmp_pwd_window');
            delete_user_meta($this->_o_user->ID, 'reminder_email_last');
            delete_user_meta($this->_o_user->ID, 'reminder_email_count');


            $this->_a_return['message'] = 'Logged In';

            return TRUE;
        } else {
            error_log('error signing on user');
            error_log('confirmation error ' . $user->get_error_message());
        }

        //Login Failed, reset pwd to force reactivation process
        $s_reset = wp_generate_password(12, TRUE);
        wp_set_password($s_reset, $this->_o_user->ID);

        $this->_a_return['status']  = 'failed';
        $this->_a_return['message'] = 'Login Failed. Please Try Again.';


        return FALSE;
    }


}
/* ----- EOF ----- */