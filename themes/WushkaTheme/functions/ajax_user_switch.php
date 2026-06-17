<?php
/**
 * Created by PhpStorm.
 * User: Jordan
 * Date: 18/01/2016
 * Time: 11:05 AM
 */

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_switch']) ) {

    $c_ajax = new User_Switch_Ajax();
    $a_result = $c_ajax->get_result();

    echo json_encode($a_result);

    exit();
}

class User_Switch_Ajax {

    private $a_result;
    private $o_student;

    public function __construct() {
        if ( $this->is_valid() === TRUE ) {
            $s_method = $this->s_method;
            $this->$s_method;
        }
        $this->o_student = NULL;
        if ( is_user_logged_in() && current_user_can('student') ) {
            $this->o_student = wp_get_current_user();
        }
    }

    public function get_result() {
        return $this->a_result;
    }

    private function is_valid() {
        //Determine Function Type
        $s_type = null;
        if ( empty($s_type) ) {
            if ( $s_type !== 'class_list' && $s_type !== 'switch_user' ) {

            }
        }

        $this->a_result['message'] = 'An error occurred validating this procedure.';
        return FALSE;
    }

    private function get_class() {
        //Do Nothing
        $i_class = $this->o_student->class;

        $a_teachers = $this->get_class_teachers($i_class);

    }

    private function  user_switch() {
        //Do Nothing
    }

    private function get_class_teachers($i_class = NULL) {
        global $wpdb;
        $s_prepare = 'SELECT * FROM wushka_teacher_classes WHERE class_id = %d';
    }
}

/* ----- END OF FILE ----- */