<?php
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
//exits when file is load directly
if (!function_exists('add_action')) {
    echo "This page cannot be called directly.";
    exit;
}

class Create_Stamp
{

    private $a_results;

    public function __construct()
    {
        $this->a_results = array(
            'status'  => 0,
            'message' => 'Creating New PDF Stamp',
            'data'    => array()
        );

        #TODO: Remove After Testing is Completed
        $this->b_test = FALSE;
    }

    private function log($s_text = NULL)
    {
        if ($this->b_test && isset($s_text)) {
            $this->a_results['message'] = $s_text;
            error_log($s_text);
        }

        return TRUE;
    }

    public function get_results()
    {
        return $this->a_results;
    }

    public function validate()
    {
        if (!is_user_logged_in() || (!current_user_can('teacher') && !current_user_can('school'))) {
            $this->log('Error: Current User is Invalid');

            return FALSE;
        }

        //Store Type
        $s_type = stripcslashes(filter_input(INPUT_POST, 'type'));

        //Store Stamp Type
        if (!isset($s_type) || empty($s_type)) {
            $this->log('Error: stamp type failed validation');

            return FALSE;
        }

        $this->log('Prepare to Stamp: ' . $s_type);
        $this->s_type = $s_type;

        return TRUE;
    }



    public function create_stamp()
    {
        //Run Specific Stamper
        $s_function = 'stamp_' . $this->s_type;
        if ($this->$s_function() !== FALSE) {
            return TRUE;
        }

        return FALSE;
    }

    private function stamp_support_materials()
    {
        include_once 'class_stamp_support-materials.php';

        $c_stamp = new Stamp_Support_Materials();
        if ($c_stamp->validate()) {
            if ($c_stamp->get_data()) {
                return $c_stamp->stamp();
            }
        }

        return FALSE;
    }


    private function stamp_student_letters()
    {
        include_once 'class_stamp_student-letters.php';

        $c_stamp = new Stamp_Student_Letters();
        if ($c_stamp->validate()) {
            if ($c_stamp->get_data()) {
                return $c_stamp->stamp();
            }
        }

        $this->log($c_stamp->get_log());

        return FALSE;
    }

    private function stamp_student_letters_qr()
    {
        include_once 'class_stamp_student-qr.php';

        $c_stamp = new Stamp_Student_QR();
        if ($c_stamp->validate()) {
            if ($c_stamp->get_data()) {
                return $c_stamp->stamp();
            }
        }

        $this->log($c_stamp->get_log());

        return FALSE;
    }

    private function stamp_student_letters_qr_by_id()
    {
        include_once 'class_stamp_student-qr.php';

        $c_stamp = new Stamp_Student_QR();
        if ($c_stamp->validate()) {
            if ($c_stamp->get_data()) {
                return $c_stamp->stamp();
            }
        }

        $this->log($c_stamp->get_log());

        return FALSE;
    }

    private function stamp_student_quizzes()
    {
        include_once 'class_stamp_student-quizzes.php';

        $c_stamp = new Stamp_Student_Quizzes('student');
        if ($c_stamp->validate()) {
            if ($c_stamp->get_data()) {
                return $c_stamp->stamp();
            }
        }

        return FALSE;
    }

    private function stamp_class_quizzes()
    {
        include_once 'class_stamp_student-quizzes.php';

        $c_stamp = new Stamp_Student_Quizzes('class');
        if ($c_stamp->validate()) {
            if ($c_stamp->get_data()) {
                return $c_stamp->stamp();
            }
        }

        return FALSE;
    }

    private function stamp_student_statistics()
    {
        include_once 'class_stamp_student-statistics.php';

        $c_stamp = new Stamp_Student_Statistics('student');
        if ($c_stamp->validate()) {
            if ($c_stamp->get_data()) {
                return $c_stamp->stamp();
            }
        }

        return FALSE;
    }

    private function stamp_class_statistics()
    {
        include_once 'class_stamp_student-statistics.php';

        $c_stamp = new Stamp_Student_Statistics('class');
        if ($c_stamp->validate()) {
            if ($c_stamp->get_data()) {
                return $c_stamp->stamp();
            }
        }

        return FALSE;
    }
}

/* ----- EOF ------ */