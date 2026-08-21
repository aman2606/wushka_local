<?php
/*
  Template Name: Student - My Page (statistics)
 */

//Must Be Logged In and be a parent User to view this page
if( ! is_user_logged_in() || ! user_can($current_user->ID, 'student') ) {
    wp_redirect(home_url());
    exit;
}
error_log('Current User = ' . print_r($current_user, TRUE));
require_once('functions/student-statistics-group-functions.php');
$template_path = get_template_directory_uri();

//Get parent ID
$child_id  = $current_user->ID;
$parent_id = $current_user->parent_id;

$total_books = count(get_posts(array(
    'post_type'      => 'ebook',
    'posts_per_page' => -1
)));

get_header();
$student = isset($current_user->parent_id) ? 'child' : 'student';
?>

<script>
var thm_tmp_fnc_pth = '<?php echo $template_path; ?>';
</script>
<link rel="stylesheet" type="text/css" href="<?php echo $template_path; ?>/css/teacher_student-statistics.css">
<!--
<script src="<?php echo $template_path; ?>/js/animateNumber/jquery.animateNumber.js"></script>
-->
<?php if( $student === 'student' ) { ?>
<script src="<?php echo $template_path; ?>/js/teacher_student-statistics.js?ver=<?= get_bloginfo( 'version' ); ?>"></script>
<?php } else { ?>
<script src="<?php echo $template_path; ?>/js/parent_student-statistics.js?ver=<?= get_bloginfo( 'version' ); ?>"></script>
<?php } ?>
<?php unset($template_path); ?>

<div class="container-fluid">
    <div class="row">
        <?php get_popup_window(); ?>
        <?php get_loading_screen(); ?>
    </div>

    <div class="row mt30">
        <div class="col-xs-12">
            <h2 class="glyphicon-heading text-left">
                <span class="x2 glyphicon glyphicon-address-book hidden-xs"></span>
                <span class="glyphicon-heading-text">My Page<span id="student_name" style="display:none;"></span></span>
            </h2>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-lg-10 col-lg-offset-1">
            <div class="col-xs-12" style="display:none;">
                <div class="col-xs-12">
                    <div class="form-group btn-group time-group col-xs-6" role="group">
                        <label>School Hours:</label><br>

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
                    <div class="form-group year-group col-xs-6" role="group">
                        <label>Year Viewable:</label>
                        <div class="btn-group">
                            <button type="button" class="btn btn-filter btn-current btn-tertiary"
                                value="current">Current
                            </button>
                            <button type="button" class="btn btn-filter btn-current btn-tertiary selected" value="all">All
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-1 col-lg-1" style="display:none;">
            <div role="tabpanel">
                <div class="tab-content">
                    <?php
                        get_student_statistics($student, $child_id);
                        ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-lg-10 col-lg-offset-1">
            <?php get_student_detail_window($student); ?>
        </div>
    </div>


</div>


<script>
$(document).ajaxComplete(function() {
    $('svg').attr('aria-label', 'Class Overview');
    $('.progress-wrap .level-wrap').each(function(index) {
        var ptext = $('.progress-wrap .level-wrap:eq(' + index + ') p').text();
        var progress = $('.progress-wrap .level-wrap:eq(' + index + ') .progress').text();
        $('.progress-wrap .level-wrap:eq(' + index + ') button').attr('tabindex', '-1').append(
            '<span class="sr-only">' + ptext + ' - ' + progress + '</span>');
    });
});
setTimeout(function() {
    $('svg').attr('aria-label', 'Class Overview');
    /* if($('.progress-wrap button').text() == "")
    {
        $('.progress-wrap .level-wrap').each(function(index){
            var ptext = $('.progress-wrap .level-wrap:eq('+ index +') p').text();
            var progress = $('.progress-wrap .level-wrap:eq('+ index +') .progress').text();
            $('.progress-wrap .level-wrap:eq('+ index +') button').attr('tabindex','-1').append('<span class="sr-only">'+ ptext + ' - ' + progress + '</span>');
        });
    } */
}, 3000);
</script>

<?php
echo '<input type="hidden" id="total_books" value="' . $total_books . '">';
include 'dashboard_options.php';
get_footer();
?>