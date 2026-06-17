<?php
/*
  Template Name: Manage Reading Groups
 *
 * This is a page for group maintainece
 * Teachers can add, edit and delete reading groups from this page
 */

//Is User Logged In AND is user a teacher?
if ( ! is_user_logged_in() || ! user_can( $current_user, "teacher") ) {
	//Redirect to Login Page
	wp_redirect( home_url()."/wp-login.php" );
	exit;
}

require_once('functions/class_manage-reading-groups.php');
require_once('functions/bookmarks/class_my-bookmarks.php');

//Get Teacher ID
$teacher_id = $current_user->ID;
$teacher_school = wp_get_object_terms($teacher_id, 'school');
$school_id = null;
if (isset($teacher_school) && !empty($teacher_school)) {
    $school_id = $teacher_school[0]->term_taxonomy_id;
}
$a_class_data = build_class_selector( $school_id, $teacher_id, 'class-statistics');


$a_classes = $a_class_data['classes'];
$a_menu = $a_class_data['menu'];

get_header();
$c_manage_reading_groups = new Manage_Reading_Groups( $current_user );

//$c_collection = new Teacher_Collection($current_user->ID);
//$c_collection = new Wushka_Bookmarks($current_user->ID);

//Load Reading Group Style Sheet
$c_manage_reading_groups->load_stylesheets();
//$c_collection->load_stylesheets();

if(!empty($a_class_data)){
?>
<div class="container-fluid">
    <div class="row mt15">
        <div class="col-xs-12">

            <div class="glyphicon-heading text-left">
                <span class="x2 glyphicon glyphicon-book-open hidden-xs"></span>
                <h2 class="glyphicon-heading-text text-left colour-white text-left pb0" style="line-height:39px;">
                    Manage Reading Groups</h2>
                <div class="submodule-right"><?php echo $a_menu; ?></div>
            </div>

        </div>
    </div>
    <?php
		$c_manage_reading_groups->load_page();
	?>
    <div class="hidden nav-hidden">
        <?php  
        foreach($a_classes as $class){  
            echo '<div id="'.$class['ID'].'-class" role="tabpanel" data-class="'. $class['ID'] .'" class="tab-pane"></div>'; 
        } 
        ?>
    </div>
</div>
<script>
//Accessibility fixes
$('.class-statistics li a').attr('aria-controls', '');
$('.books-wrap, [data-id="books-page-1"] a').removeAttr('role');


$('.modal-content label, .modal-content h3, .nav li a').each(function() {
    if ($(this).text() == '' || $(this).text() == 'undefined') {
        $(this).remove();
    }
});

<?php   /* Show menu as per licence available for class */   ?>
var o_licence = <?php echo json_encode($c_manage_reading_groups->get_licence()); ?>;

function getClassActive() {
    var o_active = $('.class-list.class-switch.active');
    if (o_active.length > 0) {
        return o_active.find('a').attr('href').split('-').shift().split('#').pop();
    }
    return false;
}

function reading_level_menu_display(licence = null){
    switch(licence){
        case 'Wushka Decodables':
            $('.reading-group-decodables-menu').show();
            $('.reading-group-levelled-menu').hide();
            break;
        case 'Wushka Levelled':
            $('.reading-group-levelled-menu').show();
            $('.reading-group-decodables-menu').hide();
            break;
        default:
            $('.reading-group-levelled-menu').show();
            $('.reading-group-decodables-menu').show();
            break;

    }
}

function reading_level_option(activeClass = null){
    if (activeClass == null) {
        activeClass = getClassActive();
    }
    let active_class_licence = o_licence[activeClass];
    reading_level_menu_display(active_class_licence);
}

function reset_reading_level_panel(){
    let reset_html_content = '<div class="level-wrap books-wrap" data-id="books-page-1" data-paged="1"><a href="#" class="level-content-item empty-empty-item ui-draggable ui-draggable-handle">Select a Reading Level Colour to start allocating readers to a Group</a></div>';
    //empty the content and add
    $('.level-content-wrap').empty();

    $('.level-content-wrap').html(reset_html_content);
}

$(function() {
    reading_level_option();

    $('.class-switch').on('click', function () {
        reading_level_option($(this).find('a').attr('href').split('-').shift().split('#').pop());
        reset_reading_level_panel();
    });
});
</script>
<?php
}else{ ?>

    <div class="container alert alert-info" style="margin-top:50px">
    You must be assigned to at least one class in order to manage the reading groups. Please reach out to the Wushka program coordinator at your school.
    </div>
  
  <?php }
include 'dashboard_options.php';

get_footer();
/* ----- END OF MANAGE READING GROUP FILE ----- */
?>