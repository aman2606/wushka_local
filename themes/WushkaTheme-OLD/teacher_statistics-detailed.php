<?php
/*
  Template Name: Teacher - Detailed Statistics
 */

//Must Be Logged In and be a Teacher User to view this page
if( ! is_user_logged_in() || ! user_can($current_user, "teacher") ) {
    wp_redirect(home_url());
    exit;
}

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
$total_books  = count(get_posts(array(
    'post_type'      => 'ebook',
    'posts_per_page' => -1
)));
$s_validate   = wp_create_nonce('get_filtered_results_' . $current_user->ID);

get_header();
?>

<script>
var thm_tmp_fnc_pth = '<?php echo $template_path; ?>';
var ajax_url = '<?php echo esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")); ?>';
var s_validate = '<?php echo $s_validate; ?>';
</script>
<!-- <link rel="stylesheet" href="<?php echo $template_path; ?>/css/teacher_student-statistics.css"> -->
<script src="<?php echo $template_path; ?>/js/animateNumber/jquery.animateNumber.js"></script>
<?php unset($template_path); ?>

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
        <?php get_popup_window(); ?>
        <?php get_loading_screen(); ?>
    </div>

    <div class="row mt15">
        <div class="col-xs-12">
            <div class="glyphicon-heading text-left">
                <span class="x2 glyphicon glyphicon-charts hidden-xs"></span>
                <h2 class="glyphicon-heading-text text-left colour-white text-left pb0" style="line-height:39px;">
                    Detailed Statistics: <span id="student_name"></span></h2>
                <div class="submodule-right"><?php echo $a_menu; ?></div>
            </div>
        </div>
    </div>

    <div class="row pt0">
        <div class="col-xs-12">
            <div class="form-group time-group col-xs-6" role="group">
                <label>School Hours:</label>
                <div class="btn-group">
                    <button type="button" class="btn btn-filter btn-time btn-tertiary" value="school">School</button>
                    <button type="button" class="btn btn-filter btn-time btn-tertiary" value="home">Home</button>
                    <button type="button" class="btn btn-filter btn-time btn-tertiary selected" value="both">Both
                    </button>
                </div>
            </div>
            <div class="form-group year-group col-xs-6" role="group">
                <label>Year Viewable:</label>
                <div class="btn-group">
                    <button type="button" class="btn btn-filter btn-current btn-tertiary selected"
                        value="current">Current
                    </button>
                    <button type="button" class="btn btn-filter btn-current btn-tertiary" value="all">
                        All
                        <span class="sr-only">View all year</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row pt10">
        <div class="col-xs-12 col-md-2">
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
                            echo $a_class['top'];
                            get_student_statistics('detailed', $teacher_id, $a_class['ID'], $b_first);
                            echo $a_class['bottom'];
                        }
                        ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-10">
            <?php
                get_student_detail_window('detailed');
                ?>
            <?php if( current_user_can('teacher') || current_user_can('school') ) { ?>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-md-offset-3">
                <button class="btn btn-primary btn-block btn-stamp disabled" id="student-stats" data-id="">
                    Generate Student Statistics Report
                </button>
            </div>
            <?php } ?>
        </div>

    </div>
</div>
<script>
jQuery(document).ready(function($) {
    $('.class-list.class-switch').on('click', function() {
        if ($(this).hasClass('active')) {
            return false;
        }


        var i_class = $(this).find('a').attr('href').replace('#', '').replace('-class', '').trim();

        $('.tab-content').hide();
        // $('#' + i_class + '-class').hide();

        show_loading_screen();
        console.log('Getting Filtered Results For:');
        console.log('Class: ' + i_class);

        $.ajax({
            url: ajax_url,
            type: "POST",
            dataType: 'json',
            data: {
                'action': 'wushka_get_class_students',
                'json': JSON.stringify({
                    'object_id': i_class,
                    'type': 'class'
                }),
                'validate': JSON.stringify(s_validate)
            },
            success: function(a_result) {
                var loaded_class = $('#' + i_class + '-class').find(
                    '.list-group.student-list');
                loaded_class.html(a_result);
                loaded_class.find('.list-student:eq(0)').click();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log('Get Filtered Results Ajax Fail:');
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
            },
            complete: function() {
                // $('#' + i_class + '-class').show();
                $('.tab-content').show();
                hide_loading_screen();
            }
        });

        return true;
    });

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

setTimeout(function() {
    if (!$('svg').attr('aria-label')) {
        $('svg').attr('aria-label', 'Statistics Overview');
    }
}, 3000);
</script>



<!-- Accessibility Fixes -->
<script>
function removeEmptyList() {
    $('.nav li a, .list-group a').each(function() {
        if ($(this).text() == '' || $(this).text() == 'undefined' || $.trim($(this).text()) == '') {
            $(this).remove();
        }
        $(this).removeAttr('aria-controls')
    });
}
$(function() {
    removeEmptyList();
    $(document).ajaxSuccess(function() {
        removeEmptyList();
    });
});
</script>


<?php
echo '<input type="hidden" id="total_books" value="' . $total_books . '">';
include 'dashboard_options.php';
get_footer();

/* ----- END OF FILE ----- */