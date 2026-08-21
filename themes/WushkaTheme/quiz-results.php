<?php
/*
 * Template Name: Quiz Results page
 */
if( ! is_user_logged_in() ) {
    wp_redirect(home_url('/login'));
    exit();
}
global $current_user;

error_log('including group functions');
require_once('functions/student-statistics-group-functions.php');

get_header();

$a_classes = array();
$a_users   = array();

$a_vars = array(
    'ajax_url'   => esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")),
    's_validate' => wp_create_nonce('get_student_statistics')
);

error_log('collecting users');
if( user_can($current_user->ID, 'student') ) {
    $a_users[ $current_user->ID ] = $current_user;
    $a_kids = [$current_user];
} else if( user_can($current_user->ID, 'parent') ) {
    $a_kids = wushka_get_students($current_user->ID, 'parent_id');
    if( isset($a_kids) && ! empty($a_kids) ) {
        foreach( $a_kids as $idx => $o_kid ) {
            $a_users[ $o_kid->ID ] = $o_kid;
        }
    }
} else if( user_can($current_user->ID, 'teacher') ) {
    $teacher_school = wp_get_object_terms($current_user->ID, 'school');
    $school_id      = NULL;
    if( isset($teacher_school) && ! empty($teacher_school) ) {
        $school_id = $teacher_school[0]->term_taxonomy_id;
    }
    $a_class_data = build_class_selector($school_id, $current_user->ID, 'class-statistics');
    $a_classes    = $a_class_data['classes'];
    $a_menu       = $a_class_data['menu'];
    $i_class = isset($_SESSION['class_id']) ? $_SESSION['class_id'] : NULL;
    if( isset($i_class) ) {
        $class_id = $i_class;
    } else {
        $class_id = $a_classes[0]['ID'];
    }
    $a_kids = wushka_get_students($class_id, 'class');
    if( isset($a_kids) && ! empty($a_kids) ) {
        foreach( $a_kids as $idx => $o_kid ) {
            $a_users[ $o_kid->ID ] = $o_kid;
        }
    }

    // foreach( $a_classes as $iix => $a_class ) {
    //     $a_kids = wushka_get_students($a_class['ID'], 'class');
    //     if( isset($a_kids) && ! empty($a_kids) ) {
    //         foreach( $a_kids as $idx => $o_kid ) {
    //             $a_users[ $o_kid->ID ] = $o_kid;
    //         }
    //     }
    // }
}
error_log('... completed collecting users');
$s_table_body = wushka_get_quiz_results($a_users);

$s_empty_response = 'You have not completed any quizzes yet. To complete a quiz open and complete a Reader, ' .
    'when you exit the Reader the quiz will appear';
if( user_can($current_user->ID, 'parent') || user_can($current_user->ID, 'teacher') ) {
    $s_empty_response = 'This student has not completed any quizzes yet. To complete a quiz they will need to open and complete a Reader. ' .
        'When they click close, they will then be directed to the quiz for that Reader.';
}

?>

<div class="quiz-results-wrapper padding-y">

    <div class="container-fluid">
        <div class="row">
            <div class="loading-screen loading-stamp">
                <div class="spin-icon">
                    <i class="glyphicon glyphicon-cd x3"></i>
                </div>
                <h2>Loading Students</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="glyphicon-heading text-left">
                    <span class="x2 glyphicon glyphicon-lightbulb hidden-xs"></span>
                    <h2 class="glyphicon-heading-text text-left colour-white text-left pb0" style="line-height:39px;">
                        Online Quiz Results
                    </h2>
                    <div class="submodule-right"><?php echo (isset($a_menu) && ! empty($a_menu)) ? $a_menu : NULL; ?></div>
                </div> 
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <?php if(user_can($current_user->ID, 'student')) { ?>
            <div class="col-xs-0" style="display:none;">
                <div role="tabpanel">
                    <div class="tab-content">
                        <?php get_student_statistics('student', $current_user->ID); ?>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                <?php } else if(user_can($current_user->ID, 'parent')) { ?>
                <div class="col-xs-12 col-md-2">
                    <div role="tabpanel">
                        <div class="tab-content">
                            <?php get_student_statistics('parent', $current_user->ID); ?>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-10 col-lg-8">
                    <?php } else if(user_can($current_user->ID, 'teacher')) { ?>
                    <div class="col-xs-12 col-md-2">
                        <div role="tabpanel">
                            <div class="tab-content">
                                <?php 
                foreach( $a_classes as $i_key => $a_class ) {
                    if( isset($i_class) ) {
                        $b_first = $i_class == $a_class['ID'] ? TRUE : FALSE;
                    } else {
                        $b_first = $i_key == 0 ? TRUE : FALSE;
                    }
                    echo $a_class['top'];
                    get_student_statistics('detailed', $current_user->ID, $a_class['ID'], $b_first, TRUE, $a_kids);
                    echo $a_class['bottom'];
                }
                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-10 col-lg-8">
                        <?php } else { ?>
                        <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                            <?php } ?>
                            <div id="empty-response-panel" class="panel panel-info">
                                <div class="panel-heading">
                                    <p style="margin-bottom: 0;text-align:center;"><?php echo $s_empty_response; ?></p>
                                </div>
                            </div>
                            <div id="empty-hours-panel" class="panel panel-info">
                                <div class="panel-heading">
                                    <p style="margin-bottom: 0;text-align:center;">
                                        No quizzes have been completed in this time period.<br />
                                        Use the time filter buttons to see results of quizzes completed at school, and
                                        quizzes completed at
                                        home.
                                    </p>
                                </div>
                            </div>
                            <div id="quiz-table" class="table-responsive woocommerce">
                                <table id="exportData" class="table shop_table_responsive table-bordered table-hover"
                                    style="margin-bottom: 20px;">
                                    <thead>
                                        <tr>
                                            <th>Quiz</th>
                                            <th>Viewable</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Title</th>
                                            <th>Score</th>
                                            <th>Q 1</th>
                                            <th>Q 2</th>
                                            <th>Q 3</th>
                                            <th>Q 4</th>
                                            <th>Q 5</th>
                                            <th>Time Spent Reading</th>
                                            <?php if( ! user_can($current_user, 'student') ) { ?>
                                            <th>Details</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $s_table_body; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if( current_user_can('teacher') || current_user_can('school') ) { ?>
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 mb20">
                                    <button class="btn btn-primary btn-block btn-stamp disabled" id="student-quiz"
                                        data-id="">
                                        Generate Student Quiz Report - PDF
                                    </button>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 mb20">
                                    <button class="btn btn-primary btn-block btn-stamp disabled" id="class-quiz"
                                        data-id="">
                                        Generate Class Quiz Report - PDF
                                    </button>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 mb20">
                                    <button class="btn btn-primary btn-block btn-stamp" id="export-student-quiz">
                                        Export Student Quiz Report - CSV
                                    </button>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 mb20">
                                    <button class="btn btn-primary btn-block btn-stamp" id="export-class-quiz">
                                        Export Class Quiz Report - CSV
                                    </button>
                                </div>
                            </div>
                            <?php } ?>
                        </div>


                        <?php if( current_user_can("student") ) {
    echo "
                <script>
                    jQuery(document).ready(function ($) {
                        $('#filter-quiz').attr('style', 'display:none');
                    });
                </script>
            ";
}
?>
                        <div class="col-xs-12 col-md-2" id="filter-quiz">
                            <div class="form-group time-group" role="group">
                                <label>School Hours:</label>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-filter btn-time btn-tertiary"
                                        value="school">School
                                    </button>
                                    <button type="button" class="btn btn-filter btn-time btn-tertiary" value="home">Home
                                    </button>
                                    <button type="button" class="btn btn-filter btn-time btn-tertiary selected"
                                        value="both">
                                        Both
                                    </button>
                                </div>
                            </div>
                            <div class="form-group year-group" role="group">
                                <label>Year Viewable:</label>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-filter btn-current btn-tertiary selected"
                                        value="current">Current
                                    </button>
                                    <button type="button" class="btn btn-filter btn-current btn-tertiary" value="all">
                                        <span class="sr-only">Year view </span> All
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!--END ROW -->

                        <div class="modal fade" id="quiz-result-modal" tabindex="-1" role="dialog"
                            aria-labelledby="quiz-result-modal" aria-hidden="true">
                            <div class="modal-dialog quiz-result-modal">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title">Quiz Results</h3>
                                    </div>
                                    <div class="modal-body">

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary"
                                            data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php /* Div below is added for missing closing div */ ?>
            </div>
            <?php /* div closing tag */ ?>
            <script>
            jQuery(document).ready(function($) {
                var a_ajax = <?php echo json_encode($a_vars); ?>;
                /* On Class Switch, Select First Student */
                $('.class-switch').on('click', function() {


                    if ($(this).hasClass('active')) {
                        return false;
                    }


                    console.log('Switch to New Class First User');


                    var i_class = $(this).find('a').attr('href').replace('#', '').replace('-class',
                        '').trim();

                    $('#' + i_class + '-class').hide();
                    $('#quiz-table').hide();
                    $('#filter-quiz').hide();

                    show_loading_screen();
                    console.log('Class: ' + i_class);


                    //CSV
                    var contextThis = $(this).text();

                    $.ajax({
                        url: a_ajax.ajax_url,
                        type: "POST",
                        //context:this,
                        dataType: 'json',
                        data: {
                            'action': 'wushka_get_quiz_results',
                            'json': JSON.stringify({
                                'object_id': i_class,
                                'type': 'class'
                            }),
                            'validate': JSON.stringify(a_ajax.s_validate)
                        },
                        success: function(a_result) {
                            var loaded_class = $('#' + i_class + '-class').find(
                                '.list-group.student-list');
                            loaded_class.html(a_result.sidebar);
                            $('#quiz-table').find('tbody').html(a_result.result);
                            $('.btn-stamp#class-quiz').attr('data-id', i_class)
                                .removeClass('disabled');
                            loaded_class.find('.list-student:eq(0)').click();
                            //refreshing page for table data
                            location.reload(true);

                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log('Get Filtered Results Ajax Fail:');
                            console.log(xhr.status);
                            console.log(xhr.responseText);
                            console.log(thrownError);
                        },
                        complete: function() {
                            $('#' + i_class + '-class').show();
                            $('#quiz-table').show();
                            $('#filter-quiz').show();


                            // location.reload();
                            //Export to csv
                            csvClassFileName = string_to_slug(contextThis) + "-" +
                                csvFileNameAdd;
                            var tabClass = $(".class-statistics .active a").attr("href")
                                .replace("#", '');
                            studentSelected = $("#" + tabClass +
                                " .student-list .list-student:first").text();
                            //getDataId = $("#exportData tr:visible td:first").text();
                            //studentSelected = $('.student-list').find("[data-id='" + getDataId + "']").text();
                            csvStudentFileName = string_to_slug(studentSelected) + "-" +
                                csvFileNameAdd;

                            //$('#exportData').dataTable().fnDestroy();
                            initiateTable("exportData", studentSelected, classSelected,
                                csvStudentFileName, csvClassFileName);
                            //Csv ends

                            //hide_loading_screen();

                        }
                    });

                    return true;
                });

                /*School Hour Time Filter Btns*/
                $('.btn.btn-filter.btn-time').on('click', function() {
                    if ($(this).hasClass('selected')) {
                        return false;
                    }

                    var this_filter = $(this);
                    var s_filter = this_filter.text().trim();

                    //Remove Selected class from current btn
                    $('.btn.btn-filter.btn-time.selected').removeClass('selected');

                    //Add Selected class to this btn
                    this_filter.addClass('selected');
                    console.log('Filtering Results by:' + s_filter + ' Times');

                    //Get Current Active Student
                    var e_student = $('.list-group-item.list-student.active');
                    display_kid_quizzes(e_student);
                });

                /*Current School Year Filter Btns*/
                $('.btn.btn-filter.btn-current').on('click', function() {
                    if ($(this).hasClass('selected')) {
                        return false;
                    }

                    var this_filter = $(this);
                    var s_filter = this_filter.attr('value').trim();

                    //Remove Selected class from current btn
                    $('.btn.btn-filter.btn-current.selected').removeClass('selected');

                    //Add Selected class to this btn
                    this_filter.addClass('selected');
                    console.log('Filtering Results by:' + s_filter + ' Recorded Years');

                    //Get Current Active Student
                    var e_student = $('.list-group-item.list-student.active');
                    display_kid_quizzes(e_student);



                    //Export to csv
                    yearViewSelected = $(this).text().toLowerCase().replace(/\s/g, '');
                    csvFileNameAdd = 'quiz-result-' + yearViewSelected;
                    //alert(csvFileNameAdd);

                    classSelected = $('.class-statistics .active a').text();
                    csvClassFileName = string_to_slug(classSelected) + "-" + csvFileNameAdd;
                    var tabClass = $(".class-statistics .active a").attr("href").replace("#", '');
                    studentSelected = $("#" + tabClass + " .student-list .list-student:first")
                        .text();
                    //getDataId = $("#exportData tr:visible td:first").text();
                    //studentSelected = $('.student-list').find("[data-id='" + getDataId + "']").text();
                    csvStudentFileName = string_to_slug(studentSelected) + "-" + csvFileNameAdd;

                    //alert(csvClassFileName);

                    //$('#exportData').dataTable().fnDestroy();
                    initiateTable("exportData", studentSelected, classSelected, csvStudentFileName,
                        csvClassFileName);
                    //Csv ends 
                });

                var selected_class = $('.list-group.student-list')[0];

                /* Get ID of Active Class */
                var i_class = null;
                if ($('li.class-switch.active').length > 0) {
                    selected_class = $('li.class-switch.active a').attr('href').trim();
                    i_class = selected_class.replace('#', '').replace('-class', '').trim();
                }

                if (i_class !== null) {
                    //Add Class ID to Quiz PDF Generator Button
                    var e_generate_class = $('.btn-stamp#class-quiz');
                    e_generate_class.attr('data-id', i_class);
                    e_generate_class.removeClass('disabled');
                }


                //If Active Class has a Student in it, load the first child's results
                if ($(selected_class).find('.list-student:first-child').length <= 0) {
                    if ($('#quiz-table tr:first-child').length > 0) {
                        $('#quiz-table').show();
                        $('#empty-response-panel').hide();
                    } else {
                        $('#quiz-table').hide();
                        $('#empty-response-panel').show();
                    }
                } else {
                    if ($(selected_class).find('.list-group-item.list-student.active').length > 0) {
                        first_class_student = $(selected_class).find(
                            '.list-group-item.list-student.active');
                    } else {
                        first_class_student = $(selected_class).find('.list-group-item.list-student')[0];
                    }
                    console.log('Run Student Click');
                    display_kid_quizzes($(first_class_student));
                }

                // $('.list-group-item.list-student, .list-group-item.list-student:first-child').on('click', function () {
                $(document).on('click',
                    '.list-group-item.list-student, .list-group-item.list-student:first-child',
                    function() {
                        // if ($(this).hasClass('active')) {
                        //     return false;
                        // }
                        console.log('Run Student Click');
                        display_kid_quizzes($(this));
                    });

                // $(document).on('click', 'tr[id^="quiz-row-"] td.table-details button[type=submit]', function () {
                //     var student_id = null;
                //     var quiz_id = null;
                //     console.log('Load Quiz Details Window');
                //     //Get Student ID
                //     if ($(this).parent().find('input[name="quiz_user"]').attr('value').length > 0) {
                //         student_id = $(this).parent().find('input[name="quiz_user"]').attr('value').trim();
                //     }
                //     console.log('Student ID: ' + student_id);

                //     //Get Quiz ID
                //     if ($(this).parent().find('input[name="quiz_id"]').attr('value').length > 0) {
                //         quiz_id = $(this).parent().find('input[name="quiz_id"]').attr('value').trim();
                //     }
                //     console.log('Student ID: ' + quiz_id);
                //     if (student_id == null || quiz_id == null) {
                //         console.log('Invalid Parameter, Abort Quiz Details Load');
                //     }
                //     load_quiz_details(student_id, quiz_id);
                // });
                $(document).on('click', '.btn-quiz-result-details', function() {
                    console.log('Load Quiz Details Window');
                    var student_id = $(this).attr('data-user');
                    console.log('Student ID: ' + student_id);
                    var quiz_id = $(this).attr('data-quiz');
                    console.log('Student ID: ' + quiz_id);
                    load_quiz_details(student_id, quiz_id);
                });

                $(document).on('click', '.modal-footer button[data-dismiss="modal"]', function() {
                    $('#quiz-result-modal').find('.modal-body').empty();
                });

                //Filter Quiz Results by Student User
                function display_kid_quizzes(student_button) {
                    if (typeof student_button == 'undefined' || student_button == null) {
                        return null;
                    }

                    //Store Student ID
                    var student_id = student_button.attr('data-id').trim();
                    //Remove All Active Classes
                    $('.list-group-item.list-student').removeClass('active');
                    //Add Active class to selected student
                    student_button.addClass('active');

                    //If Student has no results, display 'no results' msg
                    //Else, Hide All quiz resuls except selected students
                    var e_all_student = $('table tbody tr[data-quiz-id=quiz-row-' + student_id + ']');
                    //Get Time Filter Selector
                    var e_time = $('.btn.btn-filter.btn-time.selected');
                    var s_time = e_time.attr('value').trim();

                    //Get Current Year Filter Selector
                    var e_current = $('.btn.btn-filter.btn-current.selected');
                    var s_current = e_current.attr('value').trim();

                    console.log('Current Year Filter Selected: ' + s_current);


                    if (e_all_student.length <= 0) {
                        $('#quiz-table').hide();
                        $('#empty-hours-panel').hide();
                        $('#empty-response-panel').show();
                    } else {
                        var s_filterSelector = 'table tbody tr[data-quiz-id="quiz-row-' + student_id + '"]';

                        //Show selected Year Rows
                        if (s_current !== 'all') {
                            s_filterSelector += '[data-current="' + s_current + '"]';
                        }

                        //Show Selected Hours Rows
                        if (s_time !== 'both') {
                            s_filterSelector += '[data-hours="' + s_time + '"]';
                        }

                        var e_filtered = $(s_filterSelector);
                        if (e_filtered.length <= 0) {
                            $('#quiz-table').hide();
                            $('#empty-response-panel').hide();
                            $('#empty-hours-panel').show();
                        } else {
                            $('#quiz-table').show();
                            $('#empty-response-panel').hide();
                            $('#empty-hours-panel').hide();
                            $('table tbody tr').hide();
                            e_filtered.show();
                        }

                    }

                    //Add Student ID to Quiz Result generator Btn
                    var e_quiz_btn = $('.btn-stamp#student-quiz');
                    e_quiz_btn.attr('data-id', student_id);
                    //Enable Quiz Result generator Btn
                    e_quiz_btn.removeClass('disabled');

                    return true;
                }

                //Show Additional Details of Specific Quiz in Popup Modal
                function load_quiz_details(student_id, quiz_id) {
                    var e_body = $('#quiz-result-modal').find('.modal-body');
                    e_body.empty().append('Loading Data...');
                    $.ajax({
                        url: a_ajax.ajax_url,
                        type: "POST",
                        dataType: 'json',
                        data: {
                            action: 'get_student_graph_data',
                            id_hash: JSON.stringify(student_id),
                            validate: JSON.stringify(a_ajax.s_validate),
                            type: JSON.stringify('quiz'),
                            index: JSON.stringify(quiz_id),
                            hours: JSON.stringify('both')
                        },
                        success: function(quiz_data) {
                            //Load Data into Modal Body
                            e_body.empty().append(quiz_data.data);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log('Quiz Details Ajax Fail:');
                            console.log(xhr.status);
                            console.log(xhr.responseText);
                            console.log(thrownError);
                        }
                    });
                }

                function get_validation_code(student_id) {
                    if (typeof student_id == 'undefined' || student_id == null) {
                        return null;
                    }
                    var student_code = null;
                    var student_item = $('.student-list').find('.list-group-item.list-student[data-id="' +
                        student_id + '"]');
                    if (student_item.length <= 0) {
                        console.log('---Could Not Find Student Code---');
                        return null;
                    }
                    if (student_item.find('#_student_wpn').attr('value').length > 0) {
                        student_code = student_item.find('#_student_wpn').attr('value');
                    }
                    return student_code;
                }

                function show_loading_screen() {
                    var o_screen = $('.loading-screen.loading-stamp');
                    o_screen.show().fadeTo(1, 1, function() {
                        o_screen.addClass('loading');
                    });
                }

                function hide_loading_screen() {
                    var o_screen = $('.loading-screen.loading-stamp');
                    o_screen.fadeTo(1, 0, function() {
                        o_screen.removeClass('loading').hide();
                    });
                }

            });
            </script>


            <!-- Necessary script for Export to excel -->
            <script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>



            <script>
            var studentSelected = $('.student-list .active').text();
            var classSelected = $('.class-statistics .active a').text();
            var viewYear = $(".year-group button.selected").text().toLowerCase().replace(/\s/g, '');

            var csvFileNameAdd = 'quiz-result-' + viewYear;
            var csvStudentFileName = string_to_slug(studentSelected) + "-" + csvFileNameAdd;
            var csvClassFileName = string_to_slug(classSelected) + "-" + csvFileNameAdd;

            initiateTable("exportData", studentSelected, classSelected, csvStudentFileName, csvClassFileName);

            $(".list-student").on('click', function() {
                studentSelected = $(this).text();
                csvStudentFileName = string_to_slug($(this).text()) + "-" + csvFileNameAdd;
                initiateTable("exportData", studentSelected, classSelected, csvStudentFileName,
                    csvClassFileName);
            });


            $("#export-class-quiz").on("click", function() {
                $(".buttons-csv-class").trigger("click");
            });

            $("#export-student-quiz").on("click", function() {
                $(".buttons-csv-student").trigger("click");
            });


            function string_to_slug(str) {
                str = str.replace(/^\s+|\s+$/g, ''); // trim
                str = str.toLowerCase();

                // remove accents, swap ñ for n, etc
                var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
                var to = "aaaaeeeeiiiioooouuuunc------";
                for (var i = 0, l = from.length; i < l; i++) {
                    str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
                }

                str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                    .replace(/\s+/g, '-') // collapse whitespace and replace by -
                    .replace(/-+/g, '-'); // collapse dashes

                return str;
            }

            function initiateTable(tableId, StudentName, ClassName, StudentFileName, ClassFileName) {

                $('#' + tableId).DataTable({
                    "searching": false,
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    dom: 'Bfrtip',
                    buttons: [{
                            //For student
                            extend: 'csvHtml5',
                            autoFilter: true,
                            className: 'buttons-csv-student',
                            filename: StudentFileName,
                            title: '',
                            //sheetName: studentSelected,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                                rows: ':visible'
                            },
                            //Function which customize the CSV (input : csv is the object that you can preprocesss)
                            customize: function(csv) {
                                //Split the csv to get the rows
                                var split_csv = csv.split("\n");
                                //Remove the row one to personnalize the headers
                                split_csv[0] =
                                    '"Class","Student","Date","Time","Title","Score","Q1","Q2","Q3","Q4","Q5"';
                                //Get active student id
                                var studentId = $(".student-list .active").data('id');
                                //For each row except the first one (header)
                                $.each(split_csv.slice(1), function(index, csv_row) {
                                    //Split on quotes and comma to get each cell
                                    var csv_cell_array = csv_row.split('","');
                                    //Replace unnecessary "
                                    var quizRowId = csv_cell_array[0].replace('"', '');
                                    //Check if quiz-row-id = active student data-id
                                    if (quizRowId == studentId) {
                                        //Remove replace the two quotes which are left at the beginning and the end (first and last cell)
                                        var dateCell = csv_cell_array[2];
                                        var timeCell = csv_cell_array[3];
                                        var TitleCell = csv_cell_array[4];
                                        var scoreCell = csv_cell_array[5];
                                        var q1Cell = removeFromString(csv_cell_array[6]);
                                        var q2Cell = removeFromString(csv_cell_array[7]);
                                        var q3Cell = removeFromString(csv_cell_array[8]);
                                        var q4Cell = removeFromString(csv_cell_array[9]);
                                        var q5Cell = removeFromString(csv_cell_array[10]);

                                        csv_cell_array = [
                                            ClassName, StudentName, dateCell, timeCell,
                                            TitleCell, scoreCell.substring(0, 1),
                                            q1Cell, q2Cell, q3Cell, q4Cell, q5Cell
                                        ];

                                        //Join the table on the quotes and comma; add back the quotes at the beginning 
                                        csv_cell_array_quotes = '"' + csv_cell_array.join(
                                            '","');
                                    } else {
                                        //csv_cell_array = [];
                                        //Join the table on the quotes and comma; add back the quotes at the beginning and end
                                        csv_cell_array_quotes = [];
                                    }
                                    //Insert the new row into the rows array at the previous index (index +1 because the header was sliced)
                                    split_csv[index + 1] = csv_cell_array_quotes;

                                });

                                //Join the rows with line breck and return the final csv (datatables will take the returned csv and process it)
                                csv = split_csv.join("\n").replace(/(^[ \t]*\n)/gm, "");
                                //alert(csv);
                                return csv;
                            }
                        },
                        {
                            //For Class 
                            className: 'buttons-csv-class',
                            extend: 'csvHtml5',
                            autoFilter: true,
                            filename: ClassFileName,
                            title: '',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                                //rows: ':visible',
                            },
                            customize: function(csv) {
                                //Split the csv to get the rows
                                var split_csv = csv.split("\n");
                                //Remove the row one to personnalize the headers
                                split_csv[0] =
                                    '"Class","Student","Date","Time","Title","Score","Q1","Q2","Q3","Q4","Q5"';
                                //For each row except the first one (header)
                                $.each(split_csv.slice(1).sort(), function(index, csv_row) {
                                    //Split on quotes and comma to get each cell
                                    var csv_cell_array = csv_row.split('","');
                                    //Replace unnecessary "
                                    var quizRowId = csv_cell_array[0].replace('"', '');
                                    var dataView = csv_cell_array[1];
                                    //Get viewable
                                    var viewable = $(".year-group button.selected").text()
                                        .toLowerCase().replace(/\s/g, '');
                                    //Get Student name from quiz id
                                    var DynamicStudentName = $(".student-list a[data-id='" +
                                        quizRowId + "']").text();

                                    //Remove replace the two quotes which are left at the beginning and the end (first and last cell)
                                    var dateCell = csv_cell_array[2];
                                    var timeCell = csv_cell_array[3];
                                    var TitleCell = csv_cell_array[4];
                                    var scoreCell = csv_cell_array[5];
                                    var q1Cell = removeFromString(csv_cell_array[6]);
                                    var q2Cell = removeFromString(csv_cell_array[7]);
                                    var q3Cell = removeFromString(csv_cell_array[8]);
                                    var q4Cell = removeFromString(csv_cell_array[9]);
                                    var q5Cell = removeFromString(csv_cell_array[10]);

                                    csv_cell_array = [
                                        ClassName, DynamicStudentName, dateCell,
                                        timeCell, TitleCell, scoreCell.substring(0, 1),
                                        q1Cell, q2Cell, q3Cell, q4Cell, q5Cell
                                    ];

                                    //If all selected join data to cell array
                                    if (viewable == 'all') {
                                        //Join the table on the quotes and comma; add back the quotes at the beginning 
                                        csv_cell_array_quotes = '"' + csv_cell_array.join(
                                            '","');

                                    } else {
                                        //When current selected filter only current data
                                        if (viewable == dataView) {
                                            //Join the table on the quotes and comma; add back the quotes at the beginning 
                                            csv_cell_array_quotes = '"' + csv_cell_array
                                                .join('","');
                                        } else {
                                            csv_cell_array_quotes = [];
                                        }
                                    }
                                    //Insert the new row into the rows array at the previous index (index +1 because the header was sliced)
                                    split_csv[index + 1] = csv_cell_array_quotes;

                                });

                                //Join the rows with line breck and return the final csv (datatables will take the returned csv and process it)
                                csv = split_csv.join("\n").replace(/(^[ \t]*\n)/gm, "");
                                return csv;
                            }
                        }
                    ],
                    "bDestroy": true
                });
            }


            function removeFromString(string){
                var updatedString = string.toLowerCase();
                if(updatedString.match(/answer/gi)){
                    updatedString = updatedString.replace(/answer\w+\s+/g, '').trim();  
                }

                if(updatedString.match(/mark/gi)){
                    updatedString = updatedString.replace(/mark\s+/g, '').trim();  
                } 

                return updatedString;
            }

            </script>



            <?php

function is_score_valid( $o_row ) {
    $a_answers = json_decode($o_row->answers);
    $a_score   = explode('/', $o_row->score);
    $i_score   = trim($a_score[0]);
    $i_total   = trim($a_score[1]);

    $i_tally     = 0;
    $i_questions = 0;
    for( $i = 0; $i <= 4; $i++ ) {
        if( isset($a_answers[ $i ]) ) {
            $i_questions++;
            $o_answer = $a_answers[ $i ];
            if( $o_answer->valid == 'correct' ) {
                $i_tally++;
            }
        }
    }

    $b_match = $i_tally == (int)$i_score ? TRUE : FALSE;

    return $b_match;
}


include 'dashboard_options.php';
get_footer();
/* ----- EOF ----- */