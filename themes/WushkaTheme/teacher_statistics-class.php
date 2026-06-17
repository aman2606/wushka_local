<?php
/*
  Template Name: Teacher - Class Statistics
 */

//Must Be Logged In and be a Teacher User to view this page
if( ! is_user_logged_in() || ! current_user_can('teacher') ) {
    wp_redirect(home_url());
    exit;
}

get_header();

require_once('functions/student-statistics-group-functions.php');
$template_path = get_template_directory_uri();

//Get Teacher ID
$teacher_id     = $current_user->ID;
$teacher_school = wp_get_object_terms($teacher_id, 'school');
$school_id      = NULL;
if( isset($teacher_school) && ! empty($teacher_school) ) {
    $school_id = $teacher_school[0]->term_taxonomy_id;
}
if (current_user_can('school')) {
    $teacher_id = null;
}
$a_class_data = build_class_selector($school_id, $teacher_id, 'class-statistics');
$a_classes    = $a_class_data['classes'];
$a_menu       = $a_class_data['menu'];

//Ajax Nonce
$s_validate = wp_create_nonce('get_filtered_results_' . $current_user->ID);

?>

<script>
var thm_tmp_fnc_pth = '<?php echo $template_path; ?>';
var ajax_url = '<?php echo esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")); ?>';
var s_validate = '<?php echo $s_validate; ?>';
</script>
<link rel="stylesheet" type="text/css" href="<?php echo $template_path; ?>/css/teacher_student-statistics.css">
<script src="<?php echo $template_path; ?>/js/animateNumber/jquery.animateNumber.js"></script>
<?php unset($template_path); ?>

<div class="container-fluid">
    <div class="row">
        <div class="loading-screen loading-stamp">
            <div class="spin-icon">
                <i class="glyphicon glyphicon-cd x3"></i>
            </div>
            <h2>Loading Class Statistics</h2>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row mt15">
        <div class="col-xs-12">
            <div class="glyphicon-heading text-left">
                <span class="x2 glyphicon glyphicon-pie-chart hidden-xs"></span>
                <h2 class="glyphicon-heading-text text-left colour-white text-left pb0" style="line-height:39px;">
                    Class Statistics
                </h2>
                <div class="submodule-right"><?php echo $a_menu; ?></div>
            </div>
        </div>
    </div>
    <div class="row pt0">
        <div class="col-xs-12">
            <div class="form-group time-group col-xs-6" role="group">
                <label>School Hours:</label>
                <div class="btn-group">
                    <button type="button" class="btn btn-filter btn-time btn-tertiary" value="school">School
                    </button>
                    <button type="button" class="btn btn-filter btn-time btn-tertiary" value="home">Home
                    </button>
                    <button type="button" class="btn btn-filter btn-time btn-tertiary selected" value="both">
                        Both
                    </button>
                </div>
            </div>
            <div class="form-group year-group  col-xs-6" role="group">
                <label>Year Viewable:</label>
                <div class="btn-group">
                    <button type="button" class="btn btn-filter btn-current btn-tertiary selected"
                        value="current">Current
                    </button>
                    <button type="button" class="btn btn-filter btn-current btn-tertiary" value="all">
                        All
                        <span class="sr-only">All Year View</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="pt0 table-wrap">
        <div role="tabpanel">
            <div class="tab-content">
                <?php
                    $i_class = isset($_SESSION['class_id']) ? $_SESSION['class_id'] : NULL;
                    foreach( $a_classes as $i_key => $a_class ) {
                        if( isset($i_class) ) {
                            $b_first = $i_class == $a_class['ID'] ? TRUE : FALSE;
                        } else {
                            $b_first = $i_key == 0 ? TRUE : FALSE;
                        }
                        error_log('fill: ' . $b_first . ' class: ' . $a_class['ID'] . ' key: ' . $i_key . ' saved class: ' . $i_class);
                        echo $a_class['top'];
                        get_student_statistics('class', $teacher_id, $a_class['ID'], $b_first, TRUE);
                        echo $a_class['bottom'];
                    }
                    ?>
            </div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
    //Set table length for class page
    $('table').DataTable(get_datatable_args());

    //Filter Class Stats Table by Time
    $('.btn.btn-filter').on('click', function() {
        console.log('Click Filter Button');
        if ($(this).hasClass('selected')) {
            return false;
        }

        var this_filter = $(this);

        //Remove Selected class from current btn
        if ($(this).hasClass('btn-time')) {
            $('.btn.btn-filter.btn-time.selected').removeClass('selected');
        } else if ($(this).hasClass('btn-current')) {
            $('.btn.btn-filter.btn-current.selected').removeClass('selected');
        }

        //Add Selected class to this btn
        this_filter.addClass('selected');

        $('.table-wrap').fadeTo(200, 0);

        filter_results();
        return true;
    });

    //On Class Switch, Fitler Results
    $('.class-list.class-switch').on('click', function() {
        if ($(this).hasClass('active')) {
            return false;
        }

        $('.table-wrap').fadeTo(200, 0);

        var i_class = $(this).find('a').attr('href').replace('#', '').replace('-class', '').trim();
        filter_results(i_class);
        return true;
    });

    function filter_results(class_id, filter_hours, filter_years) {
        //Get Parameters
        var i_class, s_hours, s_years;

        if (typeof class_id == 'undefined' || class_id == null) {
            i_class = get_class_id();
        } else {
            i_class = class_id;
        }

        if (typeof filter_hours == 'undefined' || filter_hours == null) {
            s_hours = get_hours_type();
        } else {
            s_hours = filter_hours;
        }

        if (typeof filter_years == 'undefined' || filter_years == null) {
            s_years = get_years_type();
        } else {
            s_years = filter_years;
        }

        get_filtered_results(i_class, s_hours, s_years);
        return true;
    }

    function get_filtered_results(i_class, s_hours, s_years) {
        show_loading_screen();
        console.log('Getting Filtered Results For:');
        console.log('Class: ' + i_class);
        console.log('Hours Filter Type: ' + s_hours);
        console.log('Years Filter Type: ' + s_years);

        $.ajax({
            url: ajax_url,
            type: "POST",
            dataType: 'json',
            data: {
                'action': 'get_class_stats',
                'i_class': JSON.stringify(i_class),
                's_hours': JSON.stringify(s_hours),
                's_years': JSON.stringify(s_years),
                'validate': JSON.stringify(s_validate)
            },
            success: function(a_result) {
                success_callback(a_result, i_class);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log('Get Filtered Results Ajax Fail:');
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
            },
            complete: function() {
                $('.table-wrap').fadeTo(200, 1);
                hide_loading_screen();
            }
        });
    }

    function success_callback(a_results, i_class) {
        if (typeof a_results == 'undefined' || a_results == null) {
            console.log('ERROR: No Data Returned');
        }

        console.log('Return Status: ' + a_results.status);
        console.log('Return Message: ' + a_results.message);

        //Create New Table HTML
        var s_tags = 'student-view display table table-striped table-bordered table-condensed';

        var a_table = [];

        a_table.push('<table id="table-' + i_class + '" class="' + s_tags + '">');
        a_table.push(get_table_header().join(''));
        a_table.push(add_table_rows(a_results.data.rows).join(''));
        a_table.push(get_table_footer().join(''));
        a_table.push('</table>');


        //Get Active Section
        var e_active = $('#' + i_class + '-class');

        //Set table length for class page
        e_active.find('.dataTables_wrapper').remove();
        e_active.append(a_table.join(''));

        //Re-Initialise
        e_active.find('table').DataTable(get_datatable_args());

        $('#table-' + i_class + ' thead th').css({ 'width': '20%' });

        return true;
    }

    function get_table_header() {
        var a_head = [];
        a_head.push('<thead>');
        a_head.push('<tr class="student-view-table-heading strong">');
        a_head.push(get_header_rows().join(''));
        a_head.push('</tr>');
        a_head.push('</thead>');

        return a_head;
    }

    function get_table_footer() {
        var a_foot = [];
        a_foot.push('<tfoot>');
        a_foot.push('<tr class="student-view-table-heading strong">');
        a_foot.push(get_header_rows().join(''));
        a_foot.push('</tr>');
        a_foot.push('</tfoot>');

        return a_foot;
    }

    function get_header_rows() {
        var a_rows = [];
        a_rows.push('<th class="sorting">Student</th>');
        a_rows.push('<th class="sorting">Readers Completed</th>');
        a_rows.push('<th class="sorting">Average Time Per Reader</th>');
        a_rows.push('<th class="sorting">Average Readers Per Week</th>');
        a_rows.push('<th class="sorting">Average Quiz Result (%)</th>');
        return a_rows;
    }

    function add_table_rows(o_rows) {
        var a_rows = [];

        a_rows.push('<tbody>');

        if (o_rows.length > 0) {
            for (var i = 0; i < o_rows.length; i++) {
                var o_row = o_rows[i];
                a_rows.push(add_new_row(o_row).join(''));
            }
        }

        a_rows.push('</tbody>');

        return a_rows;
    }

    function add_new_row(o_row) {
        var a_student = [];

        a_student.push('<tr class="student-table-row row-odd" id="student-' + o_row.id + '">');
        a_student.push('<td class="student_name" data-order="' + o_row.last_name + '">' + o_row.first_name +
            ' ' + o_row.last_name + '</td>');
        a_student.push('<td class="overall_read">' + o_row.completed + '</td>');
        a_student.push('<td data-order="' + parseInt(o_row.avg_raw) + '" class="avg_time_book">' + o_row
            .avg_time + '</td>');
        a_student.push('<td class="avg_book_week">' + o_row.avg_books + '</td>');
        a_student.push('<td class = "quiz_results" >' + o_row.avg_quiz + '</td>');
        a_student.push('<input type="hidden" class="_student_wpn" name="_student_wpn" value="' + o_row
            .validator + '" />');
        a_student.push('</tr>');

        return a_student;
    }

    function get_class_id() {
        var e_tab = $('.tab-pane.in.active');

        return e_tab.attr('id').replace('-class', '').trim();
    }

    function get_hours_type() {
        var e_filter = $('.btn.btn-filter.btn-time.selected');

        return e_filter.attr('value').trim();
    }

    function get_years_type() {
        var e_filter = $('.btn.btn-filter.btn-current.selected');

        return e_filter.attr('value').trim();
    }

    function get_datatable_args() {
        return {
            'paging': false,
            'info': true,
            'retrieve': true,
            "order": [
                [0, 'asc']
            ],
            "columns": [
                { "width": "20%" },
                { "width": "20%" },
                { "width": "20%" },
                { "width": "20%" },
                { "width": "20%" }
            ]
        };
    }

    function show_loading_screen() {
        var o_screen = $('.loading-screen.loading-stamp');
        $('#statistics-wrap').addClass('processing');
        o_screen.show().fadeTo(1, 1, function() {
            o_screen.addClass('loading');
        });
    }

    function hide_loading_screen() {
        var o_screen = $('.loading-screen.loading-stamp');
        o_screen.fadeTo(1, 0, function() {
            $('#statistics-wrap').removeClass('processing');
            o_screen.removeClass('loading').hide();

        });
    }
});
</script>

<!-- Accessibility Fixes -->
<script>
$('.nav li a').each(function() {
    if ($(this).text() == '' || $(this).text() == 'undefined') {
        $(this).remove();
    }
    $(this).removeAttr('aria-controls')
});


$(function() {
    $(".dataTables_empty").each(function(i) {
        $(this).closest('table').prepend('<caption class="sr-only">' + $(this).text() + '</caption>');
    });
});
</script>
<?php
include 'dashboard_options.php';
get_footer();
?>