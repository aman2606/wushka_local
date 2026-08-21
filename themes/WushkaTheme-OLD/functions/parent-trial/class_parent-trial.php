<?php
/**
 * Created by PhpStorm.
 * User: Jordan Thurston
 * Date: 27/07/2015
 * Time: 3:19 PM
 */

class Trial_Parent {

    public $a_user;
    public $a_return;

    public function __construct() {
        $this->a_user = array(
            'user_email'    => null,
            'first_name'    => null,
            'last_name'     => null
        );

        $this->a_return = array(
            'status'    => 0,
            'sent'      => FALSE,
            'message'   => null,
            'data'      => array()
        );

    }

    private function validate_post() {
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_trial']) ) {
            $s_email    = trim(filter_input(INPUT_POST, 'user_email'  ));
            $s_first    = trim(stripcslashes(filter_input(INPUT_POST, 'first_name'  )));
            $s_last     = trim(stripcslashes(filter_input(INPUT_POST, 'last_name'   )));

            if ( ! filter_var($s_email, FILTER_VALIDATE_EMAIL) ) {
                $this->a_return['message'] = 'Please enter a valid email address';
                return FALSE;
            }

            if ( empty($s_first) || empty($s_last) ) {
                $this->a_return['message'] = 'Please enter your name';
                return FALSE;
            }

            $this->a_user['user_email'] = $s_email;
            $this->a_user['first_name'] = $s_first;
            $this->a_user['last_name'] = $s_last;

            return TRUE;
        }

        return FALSE;
    }

    public function create_trail_account() {
        if ( $this->validate_post() ) {
            if ( $this->create_trial() ) {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * FUNCTION CREATE TRIAL (private)
     * creates the new trial user and sends an email with a temporary password attached
     * Temp PWD expires after 3 days OR session ends
     *
     * #TODO: Cron Job to remove expired temp pwds
     * #TODO: 'Resend Email' Functionality
     *
     * @return bool
     */
    private function create_trial() {
        //Check Email hasn't been used by an account yet
        if (username_exists($this->a_user['user_email'])) {
            $this->a_return['message'] = 'This email has already been used to create a Wushka account';
            return FALSE;
        }

        //Create Random Password to prevent login before activation
        $s_pwd = wp_generate_password(12, true);
        $a_data = array(
            'user_login' => $this->a_user['user_email'],
            'user_pass'  => $s_pwd,
            'user_email' => $this->a_user['user_email'],
            'first_name' => $this->a_user['first_name'],
            'last_name'  => $this->a_user['last_name'],
            'role' 		 => 'parent'
        );

        //Start Session if it hasn't already
        if (!isset($_SESSION)) {
            session_start();
        }

        //Create New WordPress Account
        $i_user = wp_insert_user($a_data);

        if ( $this->create_code($i_user) !== FALSE ) {
            $this->a_return['sent'] = TRUE;
            return TRUE;
        }

        return FALSE;
    }

    public function create_code($i_user = NULL) {
        if ( ! $i_user ) {
            return FALSE;
        }
        global $woocommerce;

        $o_user = get_user_by('id', $i_user);
        $s_pwd = $o_user->user_pass;
        //Create Temporary Password for activation email
        $s_temp = hash('sha256', $s_pwd . md5(time()));
        $_SESSION['trial_activate'] = $s_temp;

        //Setup 3 Day Window for temp password
        $dt = new DateTime('NOW');
        $dt->setTimeZone(new DateTimeZone(get_option('timezone_string')));
        $dt->modify('+ 6 months');
        $s_window = $dt->format('Y-m-d');

        update_user_meta($i_user, 'tmp_pwd_verify', $s_temp );
        update_user_meta($i_user, 'tmp_pwd_window', $s_window);

        // send the user a confirmation and their login details
        $mailer = $woocommerce->mailer();
        $mailer->customer_new_account( $i_user, $s_pwd );

        return $s_temp;
    }

}

/* ------ End Of File ----- */