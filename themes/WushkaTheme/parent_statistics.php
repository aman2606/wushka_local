<?php
/*
  Template Name: Parent - Detailed Child Statistics
 */
//Must Be Logged In and be a parent User to view this page
if (!is_user_logged_in() || !user_can($current_user->ID, 'parent')) {
    wp_redirect(home_url());
    exit;
}
error_log('Current User = ' . print_r($current_user, true));
require_once('functions/student-statistics-group-functions.php');
$template_path = get_template_directory_uri();

//Get parent ID
$parent_id = $current_user->ID;
$parent_school = wp_get_object_terms($parent_id, 'school');
$school_id = null;

if (isset($parent_school) && !empty($parent_school)) {
    $school_id = $parent_school[0]->term_taxonomy_id;
}
$total_books = count(get_posts(array('post_type' => 'ebook', 'posts_per_page' => -1)));

get_header();
?>

<script type="text/javascript">
    var thm_tmp_fnc_pth = '<?php echo $template_path; ?>';
</script>
<link rel="stylesheet" type="text/css" href="<?php echo $template_path; ?>/css/teacher_student-statistics.css">
<!--
<script type="text/javascript" src="<?php echo $template_path; ?>/js/animateNumber/jquery.animateNumber.js"></script>
-->
<script type="text/javascript" src="<?php echo $template_path; ?>/js/parent_student-statistics.js"></script>

<?php unset($template_path); ?>

<div class="container-fluid">
    <div class="row">
        <?php get_popup_window(); ?>
        <?php get_loading_screen(); ?>
    </div>

    <div class="row mt30">
        <div class="col-xs-12">
            <h1 class="glyphicon-heading">
                <span class="x2 glyphicon glyphicon-charts hidden-xs"></span>
                <span class="glyphicon-heading-text">Child Statistics: <span id="student_name"></span></span>
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div role="tabpanel">
                <div class="tab-content">
                    <?php
                    get_student_statistics('parent', $parent_id);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12" id="statistics-wrap">
            <?php get_student_detail_window('parent'); ?>
        </div>
    </div>
</div>

<?php
echo '<input type="hidden" id="total_books" value="' . $total_books . '">';
include 'dashboard_options.php';
get_footer();
?>