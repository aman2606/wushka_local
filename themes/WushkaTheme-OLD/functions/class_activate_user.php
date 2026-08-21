<?php
/*
 * Activate a New Wushka User Account
 */
if( ! defined('ABSPATH') ) {
    exit;
} // Exit if accessed directly

/* ----------- User Account Confirmation Functions ----------- */

class Activate_User {

    public $a_return;
    public $a_params;
    public $o_user;

    public function __construct() {
        $this->a_params = $this->prepare_params();
        $this->a_return = $this->prepare_return();
        $this->o_user   = NULL;
        error_log('Activate User ( ' . $this->a_params['role'] . ': Prepare Function');
        //return TRUE;
        if( $this->validate_parameters() ) {
            if( $this->activate_password() ) {
                $this->a_return['url'] = home_url('/');

                return TRUE;
            }
        }

        return FALSE;
    }

    private function prepare_params() {
        return array(
            'role' => NULL,
            'code' => NULL,
            'pwd'  => NULL,
            'hash' => NULL
        );
    }

    private function prepare_return() {
        return array(
            'status'  => 0,
            'url'     => NULL,
            'message' => 'New User'
        );
    }

    public function validate_user_parameters() {
        if( isset($this->a_return, $this->a_params) ) {
            return $this->validate_parameters();
        }
        $this->a_return['message'] = 'No Parameter Data Found';

        return FALSE;
    }

    private function validate_parameters() {
        //Ajax Validation
        $s_validate = json_decode(stripcslashes(filter_input(INPUT_POST, 's_validate')), TRUE);
        if( ! isset($s_validate) || ! wp_verify_nonce($s_validate, 'user_confirmation_validation') ) {
            $this->a_return['message'] = 'Validation Failed';

            return FALSE;
        }

        //User Hash
        $s_var_1 = json_decode(stripcslashes(filter_input(INPUT_POST, 's_var_1')), TRUE);
        if( isset($s_var_1) || ! empty($s_var_1) ) {
            $o_user = get_user_by_hash($s_var_1);
            if( $o_user !== FALSE ) {
                $this->o_user = $o_user;
            }
        }

        if( $this->o_user === NULL ) {
            $this->a_return['message'] = 'Invalid User';

            return FALSE;
        }

        //User Validation
        $s_var_2 = json_decode(stripcslashes(filter_input(INPUT_POST, 's_var_2')), TRUE);
        if( ! isset($s_var_2) || ! wp_verify_nonce($s_var_2, 'passcode_validation_' . (int)$this->o_user->ID) ) {
            $this->a_return['message'] = 'User Validation Failed ( code:' . $s_var_2 . ' | user: ' . $this->o_user->ID . ')';

            return FALSE;
        }

        //Confirmation Code
        $s_var_3 = json_decode(stripcslashes(filter_input(INPUT_POST, 's_var_3')), TRUE);
        if( ! isset($s_var_3) || empty($s_var_3) ) {
            $this->a_return['message'] = 'Missing Field';

            return FALSE;
        } else {
            if( (string)$s_var_3 == (string)$this->o_user->tmp_pwd_verify ) {
                $this->a_params['code'] = $s_var_3;
                $td_now                 = new DateTime('NOW');
                $td_now->setTimeZone(new DateTimeZone('UTC'));
                $now = $td_now->format('Y-m-d');

                $td_window = new DateTime($this->o_user->tmp_pwd_window);
                $window    = $td_window->format('Y-m-d');

                if( $now > $window ) {
                    $this->a_return['message'] = 'Window Has Closed';

                    return FALSE;
                }

            } else {
                $this->a_return['message'] = 'Code does not MATCH';

                return FALSE;
            }
        }

        //Password
        $s_var_4 = json_decode(stripcslashes(filter_input(INPUT_POST, 's_var_4')), TRUE);
        if( ! isset($s_var_4) || empty($s_var_4) ) {
            $this->a_return['message'] = 'Missing Field';

            return FALSE;
        } else {
            $this->a_params['pwd'] = $s_var_4;
        }

        $this->a_return['status']  = 1;
        $this->a_return['message'] = 'Validation Passed';

        return TRUE;
    }

    public function activate_user_password() {
        if( isset($this->o_user) ) {
            return $this->activate_password();
        }
        $this->a_return['message'] = 'No User Data Found';

        return FALSE;
    }

    private function activate_password() {
        //Set Password
        wp_set_password($this->a_params['pwd'], $this->o_user->ID);

        //Remove Temporary Activate Parameters
        delete_user_meta($this->o_user->ID, 'tmp_pwd_verify');
        delete_user_meta($this->o_user->ID, 'tmp_pwd_window');

        $this->a_return['message'] = 'Logged In';
        $this->a_return['status']  = 2;

        return TRUE;
    }


}
/* ----- EOF ----- */