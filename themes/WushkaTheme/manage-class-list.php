<?php
/* Template Name: Manage Class List */

if (!isset($current_user)) {
    global $current_user;
}

if (!is_user_logged_in() || (!current_user_can('teacher') && !current_user_can('school'))) {
    wp_redirect(home_url());
    exit;
}

?>

<?php get_header(); ?>
<style>
    #archive-student-confirm-modal th {
        padding: 8px;
    }
</style>
<?php
include_once 'functions/class_manage_class_list.php';

$c_class = new Class_List();
$c_class->get_class_data();
$a_results = $c_class->get_results();


if(!empty($a_results['data'])){
$a_active  = $a_results['data']['active'];
$isQRDisabled = isQRDisabled();

//NOTE: CSS Rules have been moved to css/teacher_manage-class-list.css
?>
<!-- MCL PAGE DATA VARIABLES -->
<script>
    var isQRDisabled = "<?php echo $isQRDisabled ?>";
    isQRDisabled = !!isQRDisabled;
    var i_teacher = '<?php echo $current_user->ID; ?>';
    var i_hash = '<?php echo $current_user->id_hash; ?>';
    var s_template_url = '<?php echo get_template_directory_uri(); ?>';
    var o_active = <?php echo json_encode($a_active); ?>;
    var all_levels = <?php echo json_encode($a_results['data']['levels']); ?>;
    var decodable_levels = <?php echo json_encode($a_results['data']['decodables']); ?>;
    var o_levels = all_levels;
    var o_access = <?php echo json_encode($a_results['data']['access']); ?>;
    var o_settings = <?php echo json_encode($a_results['data']['settings']); ?>;
    var o_classes = <?php echo json_encode($a_results['data']['classes']); ?>;
    var o_sound_clusters = <?php echo json_encode($a_results['data']['sound_clusters']); ?>;
    var o_phase_access   = <?php echo json_encode($a_results['data']['phase_access']); ?>;

    function deleteStudentFromOtherClass(id_hash, classId) {



        o_classes[parseInt(classId)].users.class = o_classes[parseInt(classId)].users.class.filter(function(obj) {
            return obj.id_hash !== id_hash;
        });

        if (typeof o_classes[parseInt(classId)].users.archive !== 'undefined') {

            o_classes[parseInt(classId)].users.archive = o_classes[parseInt(classId)].users.archive.filter(function(obj) {
                return obj.id_hash !== id_hash;
            });

        }




    }
</script>
<!-- END MCL PAGE DATA VARIABLES -->
<div class="container-fluid">
    <div class="screen"></div>
    <div class="row mt15">
        <div class="col-xs-12">



            <div class="glyphicon-heading text-left">
                <span class="x2 glyphicon glyphicon-group hidden-xs"></span>
                <h2 class="glyphicon-heading-text colour-white text-left pb0" style="line-height:39px;">Manage Class
                    Lists</h2>
                <span class="flex-push-right flex-push-bottom">
                    <label class="classes-tab-label hidden-xs hidden-sm hidden-md" style="font-size:30px; margin-bottom:0;">Classes: </label>
                </span>
                <div class="submodule-right with-label">
                    <div role="tabpanel" class="class-statistics">
                        <ul class="nav nav-tabs" role="tablist">
                            <?php echo implode('', $a_results['data']['menus']); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9 col-md-9" id="class-list-column">
            <div class="panel panel-default panel-class-lists">
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-group"></i><span id="panel-title">
                        <?php
                        echo $a_active['type'] == 'archive' ? 'Archive List' : 'Class List';
                        ?>
                    </span>
                    <?php if (!empty($a_results['data']['classes'])) { ?>
                        <span class="pull-right">
                            <?php

                            $s_manage_btn = $a_active['type'] == 'class' ? NULL : 'display: none;';
                            $s_back_btn   = $a_active['type'] == 'class' ? 'display: none;' : NULL;
                            ?>
                            <button type="button" class="btn btn-default btn-mc-modal panel-manage-btn" style="<?php echo $s_manage_btn; ?>" data-toggle="modal" data-target="#manage-class-settings">Manage Class
                            </button>

                            <button type="button" class="btn btn-primary btn-mc-modal switch-table to-class" style="<?php echo $s_back_btn; ?>">
                                Back to Class
                            </button>
                        </span>
                    <?php } ?>
                    <span class="clearfix"></span>
                </div>
                <div class="panel-body">
                    <div role="tabpanel">
                        <!--TABLES WILL BE GENERATED HERE -->
                        <div class="tab-content"></div>

                        <?php /* Get all tabs */ ?>
                        <div class="hidden nav-hidden">
                            <?php
                            $classes = $a_results['data']['classes'];

                            $active_class = 0;
                            if (isset($_SESSION['class_id'])) {
                                $active_class = $_SESSION['class_id'];
                            }
                            foreach ($classes as $class) {
                                $class_id = $class['class']->id;
                                if ($class_id != $active_class) {
                                    echo '<div id="' . $class_id . '-class" role="tabpanel" data-class="' . $class_id . '" class="tab-pane"></div>';
                                }
                            }
                            ?>
                        </div>
                        <script>
                            $(document).on('click', 'li.class-switch', function() {
                                var current_tab = $('.tab-content table').data('class');
                                var switch_btn = $(this).find('a').data('class');
                                $('#' + switch_btn + '-class').attr('id', current_tab + '-class').attr('data-class', current_tab).removeClass('active');
                            });
                        </script>
                        <?php /* Get all tabs */ ?>

                    </div>
                </div>
                <div class="panel-footer" style="height:45px;">
                    <?php if (!empty($a_results['data']['classes'])) { ?>
                        <span class="pull-right">
                            <button type="button" class="btn btn-default btn-mc-modal panel-manage-btn" style="<?php echo $s_manage_btn; ?>" data-toggle="modal" data-target="#manage-class-settings">Manage Class
                            </button>
                            <button type="button" class="btn btn-primary btn-mc-modal switch-table to-class" style="<?php echo $s_back_btn; ?>">
                                Back to Class
                            </button>
                        </span>
                    <?php } ?>
                    <span class="clearfix"></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3" id="sticky-panel">
            <div class="panel panel-default" id="add-new-child">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-10 editable" data-class="class-name">
                            <i class="glyphicon glyphicon-user"></i> Add New Student
                        </div>
                        <div class="col-xs-2 text-right">
                            <button id="toggle-student-drawer" class="btn btn-xs btn-default" title="Hide student form">
                                <i class="glyphicon glyphicon-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="well new-child">
                        <div class="form-group">
                            <label for="first_name" class="control-label">First Name <span class="colour-red">*</span></label>
                            <input type="text" name="first_name" class="form-control" id="first_name" placeholder="First Name" />
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="control-label">Surname <span class="colour-red">*</span></label>
                            <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Surname" />
                        </div>
                        <div class="form-group">
                            <label for="student_email" class="control-label">Student Education Email</label>
                            <input type="text" name="student_email" class="form-control" id="student_email" placeholder="Email" />
                        </div>
                        <input type="hidden" name="username" class="form-control" id="username" value="" />
                        <input type="hidden" name="show_user_pwd" class="form-control" id="show_user_pwd" value="" />

                        <div style="text-align:right;">
                            <button class="btn btn-primary btn-add-student">
                                Add Student
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
/*** Arhive Student Confirm POP UP */

$arhiveStudentList = false;

if (isset($_SESSION['arhiveStudentList']) && !empty($_SESSION['arhiveStudentList'])) {

    $arhiveStudentList = $_SESSION['arhiveStudentList'];
    unset($_SESSION['arhiveStudentList']);
}

if ($arhiveStudentList) { ?>

    <div class="modal fade" id="archive-student-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="scp-label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="scp-label">Archived Students</h3>
                </div>
                <div class="modal-body">
                    <p>The below students already exist in a current class but are archived. Please click Confirm if you want to move these students from the current class to the new class.</p>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Email</th>
                                    <th scope="col">Current Class</th>
                                    <th scope="col">New Class</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($arhiveStudentList as $key => $list) { ?>

                                    <tr>
                                        <td><?php echo $list['user']['email'] ?></td>
                                        <td><?php echo $list['currentClass'] ?></td>
                                        <td><?php echo $list['target_class_name'] ?></td>
                                        <td><a href="javascript:void(0)" class="btn btn-sm btn-danger confirm-archive-ask" data-targetClass="<?php echo  $list['target_class_id'] ?>" data-userid="<?php echo $list['user']['id'] ?>">Confirm</a></td>

                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default close-ask-archive-confirm">Close
                    </button>
                </div>
            </div>
        </div>
    </div>



<?php } ?>


<?php
/* ------------------------------
 * MANAGE CLASS MODAL
 * ------------------------------ */
?>
<div class="modal fade" id="manage-class-settings" tabindex="-1" role="dialog" aria-labelledby="manage-class-settings" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title ulc-label">
                    Manage Class
                    <span class="pull-right">
                        <button type="button" class="close close-xl" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </span>
                </h3>
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                    <div class="row">
                        <label>Make changes to your class here</label>
                    </div>
                    <div class="row">


                        <?php /* ----- Export to excel ----- */ ?>
                        <div class="col-xsp-12 col-xsl-6 col-xs-4">
                            <span class="empty help-popover" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Upload Class List" data-content="Export the Class List wizard to easily export your whole class data.">
                                <button type="button" id="excelExport" class="btn btn-default btn-block btn-upload-class-list">
                                    <span class="sr-only">Export Class List</span>
                                    <span class="x2 glyphicon glyphicon-cloud-download"></span>
                                </button>
                            </span>
                            <label class="settings-label">Export Class List</label>
                        </div>




                        <?php /* ----- Upload Student List Button ----- */ ?>
                        <div class="col-xsp-12 col-xsl-6 col-xs-4">
                            <span class="empty help-popover" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Upload Class List" data-content="Activate the Class List Upload wizard to easily import your whole class.">
                                <button type="button" class="btn btn-default btn-block btn-upload-class-list" data-toggle="modal" data-target="#upload-dialog" data-dismiss="modal">
                                    <span class="sr-only">Upload Class List</span>
                                    <span class="x2 glyphicon glyphicon-upload"></span>
                                </button>
                            </span>
                            <label class="settings-label">Upload Class List</label>
                        </div>
                        <?php /* ----- Set Class Password Button ----- */ ?>
                        <div class="col-xsp-12 col-xsl-6 col-xs-4">
                            <span class="empty help-popover pass-popover" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Set Class Password" data-content="Set a password for your whole class.">
                                <button type="button" class="btn btn-default btn-block btn-set-class-password" data-toggle="modal" data-target="#class-password-dialog" data-dismiss="modal">
                                    <span class="sr-only">Set Class Password</span>
                                    <span class="x2 glyphicon glyphicon-user-lock"></span>
                                </button>
                            </span>
                            <label class="settings-label">Set Class Password</label>
                        </div>
                        <?php /* ----- Set Class Reading Level Button ----- */ ?>
                        <div class="col-xsp-12 col-xsl-6 col-xs-4">
                            <span class="empty help-popover pass-popover" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Set Class Reading Level" data-content="Set a reading level for the whole class.">
                                <button type="button" class="btn btn-default btn-block btn-set-class-level" data-toggle="modal" data-target="#class-level-modal" data-dismiss="modal">
                                    <span class="sr-only">Set Class Reading Level</span>
                                    <span class="x2 glyphicon glyphicon-book-open"></span>
                                </button>
                            </span>
                            <label class="settings-label">Set Class Reading Level</label>
                        </div>
                        <?php /* ----- Set Class Level Access Button ----- */ ?>
                        <div class="col-xsp-12 col-xsl-6 col-xs-4">
                            <span class="empty help-popover pass-popover" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Set Class Level Access" data-content="Set a level access for the whole class.">
                                <button type="button" class="btn btn-default btn-block btn-set-level-access" data-toggle="modal" data-target="#class-access-modal" data-dismiss="modal">
                                    <span class="sr-only">Set Class Level Access</span>
                                    <span class="x2 glyphicon glyphicon-book-open"></span>
                                </button>
                            </span>
                            <label class="settings-label">Set Class Level Access</label>
                        </div>
                        <?php /* ----- Set Class Narration Button ----- */ ?>
                        <div class="col-xsp-12 col-xsl-6 col-xs-4">
                            <span class="empty help-popover pass-popover" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Set Class Narration" data-content="Switch book narration on/off for the whole class.">
                                <button type="button" class="btn btn-default btn-block btn-set-class-narration" data-toggle="modal" data-target="#class-narration-modal" data-dismiss="modal">
                                    <span class="sr-only">Set Class Narration</span>
                                    <span class="x2 glyphicon glyphicon-volume-up"></span>
                                </button>
                            </span>
                            <label class="settings-label">Set Class Narration</label>
                        </div>
                        <?php /* ----- Set Class Quizzes Button ----- */ ?>
                        <div class="col-xsp-12 col-xsl-6 col-xs-4">
                            <span class="empty help-popover pass-popover" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Set Class Quiz Access" data-content="Turn quizzes on/off for the whole class.">
                                <button type="button" class="btn btn-default btn-block btn-set-class-quiz" data-toggle="modal" data-target="#class-quiz-modal" data-dismiss="modal">
                                    <span class="sr-only">Set Class Quizzes</span>
                                    <span class="x2 glyphicon glyphicon-conversation"></span>
                                </button>
                            </span>
                            <label class="settings-label">Set Class Quizzes</label>
                        </div>
                        <?php /* ----- Create New Reading Group Button ----- */ ?>
                        <div class="col-xsp-12 col-xsl-6 col-xs-4">
                            <span class="empty help-popover pass-popover" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Create Reading Group" data-content="Create a new reading group to add your students to">
                                <button type="button" class="btn btn-default btn-block btn-new-rg " data-toggle="modal" data-target="#create-group-modal" data-dismiss="modal">
                                    <span class="sr-only">Create Reading Group</span>
                                    <span class="x2 glyphicon glyphicon-book-open"></span>
                                </button>
                            </span>
                            <label class="settings-label">Create Reading Group</label>
                        </div>
                        <?php /* ----- Set Class Reading Group Setting Button ----- */ ?>
                        <?php /* ----- Create Student Reading Group Button ----- */ ?>
                        <div class="col-xsp-12 col-xsl-6 col-xs-4">
                            <span class="empty help-popover pass-popover" id="set-student-rg" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Student Reading Groups" data-content="Set multiple students to reading groups">
                                <button type="button" class="btn btn-default btn-block btn-new-rg " data-toggle="modal" data-target="#create-student-group-modal" data-dismiss="modal">
                                    <span class="sr-only">Set Student Reading Groups</span>
                                    <span class="x2 glyphicon glyphicon-book"></span>
                                </button>
                            </span>
                            <label class="settings-label">Set Student Reading Groups</label>
                        </div>
                        <?php /* ----- Set Student Reading Group Setting Button ----- */ ?>

                        <div class="col-xsp-12 col-xsl-6 col-xs-4">
                            <span class="empty help-popover" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Set Class Reading Group Permissions" data-content="Choose a reading group permissions setting for the whole class">
                                <button type="button" class="btn btn-default btn-block btn-set-class-setting" data-toggle="modal" data-target="#class-setting-modal" data-dismiss="modal">
                                    <span class="sr-only">Set Class Permissions</span>
                                    <span class="x2 glyphicon glyphicon-book-open"></span>
                                </button>
                            </span>
                            <label class="settings-label">Set Class Permissions</label>
                        </div>
                        <?php /* ----- Create Student Login Letter Button ----- */ ?>

                        <?php if($isQRDisabled){ ?>
                        <div class="col-xsp-12 col-xsl-6 col-xs-4">
                            <span class="empty help-popover" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Generate Student Login Letters" data-content="Create student login letters PDF">
                                <button type="button" class="btn btn-primary btn-block btn-generate-student-login btn-stamp" id="stamp-students">
                                    <span class="sr-only">Generate Student Letters</span>
                                    <span class="x2 glyphicon glyphicon-envelope"></span>
                                </button>
                            </span>
                            <label class="settings-label">Generate Student Letters</label>
                        </div>
                        <?php } ?>
                        <?php if(!$isQRDisabled){ ?>
                            <div class="col-xsp-12 col-xsl-6 col-xs-4">
                                <span class="empty help-popover" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Generate Student QR" data-content="Create student login QR PDF">
                                    <button type="button" class="btn btn-primary btn-block btn-stamp" id="stamp-students-qr">
                                        <span class="sr-only">Generate Student QR</span>
                                        <span class="x2 glyphicon glyphicon-qrcode"></span>
                                    </button>
                                </span>
                                <label class="settings-label">Generate Student QR</label>
                            </div>
                        <?php } ?>

                        <?php /* ----- Create Student Reading Group Button ----- */ ?>
                        <div class="col-xsp-12 col-xsl-6 col-xs-4">
                            <span class="empty help-popover pass-popover" id="set-class-quiz-narration" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Set Class Quiz Narration" data-content="Set multiple students quiz narration">
                                <button type="button" class="btn btn-primary btn-block btn-generate-student-login btn-stamp " data-toggle="modal" data-target="#set-whole-class-quiz-narration" data-dismiss="modal">
                                    <span class="sr-only">Set Class Quiz Narration</span>
                                    <span class="x2 glyphicon glyphicon-volume-up"></span>
                                </button>
                            </span>
                            <label class="settings-label">Set Class Quiz Narration</label>
                        </div>


                        <?php /* ----- Create Student Reading Group Button ----- */ ?>
                        <div class="col-xsp-12 col-xsl-6 col-xs-4">
                            <span class="empty help-popover pass-popover" id="set-class-quiz-results" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Set Class Quiz Results" data-content="Set quiz results for the class">
                                <button type="button" class="btn btn-primary btn-block btn-generate-student-login btn-stamp " data-toggle="modal" data-target="#set-whole-class-quiz-results" data-dismiss="modal">
                                    <span class="sr-only">Set Class Quiz Results</span>
                                    <span class="x2 glyphicon glyphicon-education"></span>
                                </button>
                            </span>
                            <label class="settings-label">Set Class Quiz Results</label>
                        </div>


                        <?php /* ----- Create Class Allow Book Read for Quiz ----- */ ?>
                        <div class="col-xsp-12 col-xsl-6 col-xs-4">
                            <span class="empty help-popover pass-popover" id="set-class-book-read" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Set Class Allow Book Read for Quiz" data-content="Set whole class to allow book read during quiz">
                                <button type="button" class="btn btn-primary btn-block btn-generate-student-login btn-stamp " data-toggle="modal" data-target="#set-whole-class-book-read" data-dismiss="modal">
                                    <span class="sr-only">Set Class Allow Book Read for Quiz</span>
                                    <span class="x2 glyphicon glyphicon-book"></span>
                                </button>
                            </span>
                            <label class="settings-label">Set Class Allow Book Read for Quiz</label>
                        </div>


                    </div>
                </div>
                <div class="col-xs-12 class-archiving-wrapper">
                    <div class="row">
                        <label>Class Archiving</label>
                    </div>
                    <?php /* ----- Archive Whole Class Button ----- */ ?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-4">
                            <button class="btn btn-primary btn-block" data-dismiss="modal" data-toggle="modal" data-target="#confirm-archive-class">
                                <i class="glyphicon glyphicon-folder-flag x2"></i>
                                <span class="sr-only">Archive Class</span>
                            </button>
                            <label class="settings-label">Archive Class</label>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <button class="btn btn-primary btn-block btn-archive-students" data-dismiss="modal" data-toggle="modal" data-target="#archive-student-modal">
                                <i class="glyphicon glyphicon-folder-flag x2"></i>
                                <span class="sr-only">Archive Students</span>
                            </button>
                            <label class="settings-label">Archive Students</label>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="archive-button switch-table to-archive">
                                <button class="btn btn-primary btn-block" data-dismiss="modal">
                                    <i class="glyphicon glyphicon-folder-flag x2"></i>
                                    <span class="sr-only">View Class Archive</span>
                                </button>
                            </div>
                            <label class="settings-label">View Class Archive</label>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
<?php
/* ------------------------------
 * UPLOAD CLASS LIST MODAL
 * ------------------------------ */
?>
<div class="modal fade" id="upload-dialog" tabindex="-1" role="dialog" aria-labelledby="upload-dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-xl" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title ulc-label">Add Whole Class List in 4
                    simple steps</h3>
            </div>
            <div class="modal-body">
                <form class="" id="studentForm" action="#" method="post">
                    <div class="step one">
                        <div class="icon">
                            <img class="img-responsive" src="<?php echo CDN_URL ?>/Resources/step1.png" alt="Step 1">
                        </div>
                        <div class="instruction">
                            <p>Download CSV file to use as a template</p>
                            <a class="btn btn-default btn-download-template" href="<?php echo get_template_directory_uri() . '/downloadTemplate.php'; ?>">
                                Download</a>
                        </div>
                    </div>
                    <div class="step two">
                        <div class="icon">
                            <img class="img-responsive" src="<?php echo CDN_URL ?>/Resources/step2.png" alt="Step 2">
                        </div>
                        <div class="instruction">
                            <p>Fill out Student names &amp; upload the saved CSV file</p>
                            <label for="filename" class="sr-only">Fill out Student names &amp; upload the saved CSV
                                file</label>
                            <input type="file" id="filename" name="filename" />
                        </div>
                    </div>
                    <div class="step three">
                        <div class="icon">
                            <img class="img-responsive" src="<?php echo CDN_URL ?>/Resources/step3.png" alt="Step 3">
                        </div>
                        <div class="instruction">
                            <label for="upload-class-select">Select the class you wish to upload these students
                                to</label>
                            <?php
                            $a_select = array();
                            foreach ($a_results['data']['classes'] as $idx => $a_class) {
                                $o_class = $a_class['class'];

                                //Class Session Variable Checking
                                $s_selected = $a_class['active'] ? 'selected=""' : NULL;

                                $a_item   = array();
                                if (!empty($o_class->name)) {
                                    $a_item[] = '<option class="upload-class-item" id="upload-class-option-' . $o_class->id . '" ' .
                                        'value="' . $o_class->id . '" ' . $s_selected . '>';
                                    $a_item[] = $o_class->name;
                                    $a_item[] = '</option>';
                                }
                                $a_select[] = implode('', $a_item);

                                unset($o_class, $s_selected, $a_item);
                            }
                            ?>
                            <select id="upload-class-select">
                                <?php echo implode('', $a_select); ?>
                            </select>
                        </div>
                    </div>
                    <div class="step three">
                        <div class="icon">
                            <img class="img-responsive" src="<?php echo CDN_URL ?>/Resources/step4.png" alt="Step 4">
                        </div>
                        <div class="instruction">
                            <p>Import file to use the class list</p>
                            <input class="btn btn-default btn-upload-class-list" id="importFile" type="submit" name="submit" value="Import" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings">Close
                </button>
            </div>
        </div>
    </div>
</div>
<?php
/* ------------------------------
 * SET CLASS PASSWORD MODAL
 * ------------------------------ */
?>
<div class="modal fade" id="class-password-dialog" tabindex="-1" role="dialog" aria-labelledby="scp-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-xl" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="scp-label">Set Class Password</h3>
            </div>
            <div class="modal-body">
                <p>Use this to set the one password for the whole class</p>

                <div class="input-group">
                    <label for="new-class-password" class="sr-only">New password</label>
                    <input class="form-control class-password" id="new-class-password" type="text" value="" placeholder="New Password" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings">Cancel
                </button>
                <button class="btn btn-primary btn-set-password" id="set-password" data-id="" data-dismiss="modal" type="button">Set Password
                </button>
            </div>
        </div>
    </div>
</div>
<?php
/* ------------------------------
 * NO CLASSES MODAL
 * ------------------------------ */
?>
<div class="modal fade" id="no-classes" tabindex="-1" role="dialog" aria-labelledby="scp-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <p>It appears you haven't been assigned any classes yet.</p>

                <p>To be able set up your students your School Program Coordinator will need to allocate you to a
                    class.
                    This may take a few moments.</p>

                <p>If you find you are not allocated to a class within a few moments, please contact you School
                    Program Coordinator</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings">Cancel
                </button>
            </div>
        </div>
    </div>
</div>
<?php
/* ------------------------------
 * ARCHIVING MODAL
 * ------------------------------ */
?>
<div class="modal fade" id="confirm-archive-class" tabindex="-1" role="dialog" aria-labelledby="confirm-archive-class" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title cn-label">Archive Class</h3>
            </div>
            <div class="modal-body">
                <p>Confirm that you wish to archive the whole class, this cannot be easily undone.</p>

                <p>Are you sure you wish to archive ALL the students in this class?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings">Cancel
                </button>
                <button type="button" class="btn btn-primary btn-archive-class-confirm" data-dismiss="modal">Archive
                    Class
                </button>
            </div>
        </div>
    </div>
</div>
<?php
/* ------------------------------
 * CREATE NEW READING GROUP MODAL
 * ------------------------------ */
?>
<div class="modal fade" id="create-group-modal" tabindex="-1" role="dialog" aria-labelledby="create-group-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title cn-label">Create New Reading Group</h3>
                <a role="button" class="btn-close-modal close-xl" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings" aria-hidden="true">&times;</a>
            </div>
            <div class="modal-body">
                <div class="panel panel-default panel-create-reading-group">
                    <div class="panel-body">
                        <h4 class="modal-body-h4">You must supply a unique name for this reading group</h4>

                        <div class="input-group modal-body-input-group">
                            <label for="new-group-name" class="sr-only">Reading Group Name</label>
                            <input class="form-control group-name required" id="new-group-name" type="text" value="" placeholder="Reading Group Name" />
                        </div>
                    </div>
                </div>

                <div class="message" id="group-message"></div>
                <h4 class="modal-body-h4">These are your current reading groups:</h4>

                <div id="teacher-existing-groups">
                    <!--ELEMENTS WILL BE APPENDED HERE -->
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="group-student" value="" />
                <button type="button" class="btn btn-primary btn-class-name-confirm" id="create-reading-group" data-id="">Create Group
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings">Close
                </button>
            </div>
        </div>
    </div>
</div>



<?php
/* ------------------------------
 * Set student READING GROUP MODAL
 * ------------------------------ */
?>
<div class="modal fade" id="create-student-group-modal" tabindex="-1" role="dialog" aria-labelledby="create-student-group-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title cn-label">Set Student Reading Groups</h3>
                <a role="button" class="btn-close-modal close-xl" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings" aria-hidden="true">&times;</a>
            </div>
            <div class="modal-body">
                <div class="panel panel-default panel-create-reading-group">
                    <div class="panel-body">
                        <h4 class="modal-body-h4 text-info">Please select reading group</h4>

                        <div id="set-student-reading">
                            <label for="set-sr-select" class="sr-only">Please select reading group</label>
                            <select id="set-sr-select" class="form-control"></select>
                        </div>

                    </div>
                </div>

                <div class="table-responsive noselect">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th data-sortable="false">
                                    <div class="checkbox checkbox-info">
                                        <input type="checkbox" id="selectAll" aria-label="Select All Students">
                                        <label><span class="sr-only">Select All</span></label>
                                    </div>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username</th>
                                <th>Reading Level</th>
                                <th>Reading Group</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-class-name-confirm" id="set-multiple-reading-group">Set <span class="hidden-xs">selected students to </span>reading groups
                </button>
                <button type="button" class="btn btn-default" onclick="$('#create-student-group-modal').modal('hide');">Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $("#set-student-rg").on('click', function() {
        if ($("#create-student-group-modal:visible").length) {
            $('body').addClass('modal-open-new');
        } else {
            $('body').addClass('modal-open-new');
        }
    });

    $('#create-student-group-modal').on('hidden.bs.modal', function() {
        $('body').removeClass('modal-open-new');
    })

    function getClassActive() {
        var o_active = $('.class-list.class-switch.active');
        if (o_active.length > 0) {
            return o_active.find('a').attr('data-class');
        }
        return false;
    }


    function getClassReadingGroups(activeClass = null) {
        if (activeClass == null) {
            var activeClass = getClassActive();
        }
        var o_group_list = o_classes[activeClass].groups;
        return o_group_list;
    }


    function setStudentReadingGroupsInput(activeClass = null) {
        o_group_list = getClassReadingGroups(activeClass);
        if (activeClass == null) {
            o_group_list = getClassReadingGroups();
        }

        var selectInput = [];
        $.each(o_group_list, function(group_id, group_name) {
            selectInput.push('<option value="' + group_id + '">' + group_name + '</option>');
        });
        return selectInput;
    }

    function listReadingLevelForBulk(activeClass = null) {
        if (activeClass == null) {
            var activeClass = getClassActive();
        }
        let active_class_licence = o_classes[activeClass]["class"]['licence_product'];
        o_levels = all_levels;
        if (active_class_licence == 'Wushka Decodables') {
            o_levels = decodable_levels;
        }

        $('#bulk-level-select-button').empty();
        $.each(o_levels, function(key, value) {
            if (key == '') {
                value = 'No Level';
            }
            let button_whole_class_level = '<div class="col-xs-12 col-sm-6">' +
                '<button type="button" class="btn btn-block btn-default set-class-level" data-dismiss="modal" ' +
                ' data-id="' + key + '">' + value + '</button>' +
                '</div>';

            $('#bulk-level-select-button').append(button_whole_class_level);
        });
    }

    function listStudentForReadingGroups(activeClass = null) {
        if (activeClass == null) {
            var activeClass = getClassActive();
        }
        let active_class_licence = o_classes[activeClass]["class"]['licence_product'];

        o_levels = all_levels;
        if (active_class_licence == 'Wushka Decodables') {
            o_levels = decodable_levels;
        }

        var studentList = o_classes[activeClass].users.class;

        var studentListData = [];
        $.each(studentList, function(index) {
            indexedList = o_classes[activeClass].users.class[index];

            id_hash = indexedList.id_hash;
            first_name = indexedList.first_name ? indexedList.first_name : 'Not Set';
            last_name = indexedList.last_name ? indexedList.last_name : 'Not Set';
            username = indexedList.username;
            reading_level = indexedList.reading_level["name"] ? indexedList.reading_level["name"] : 'Not Set';
            reading_group = indexedList.my_reading_group["value"];

            studentListTableRow = '<tr><td><fieldset><legend class="sr-only">' + username +
                '</legend><div class="checkbox checkbox-info"><input type="checkbox" name="id[]" value="' +
                id_hash + '" aria-label="' + username + '"><label><span class="sr-only">Select ' + username +
                '</span></label></div></fieldset></td>' +
                '<td>' + first_name + '</td>' +
                '<td>' + last_name + '</td>' +
                '<td>' + username + '</td>' +
                '<td>' + reading_level + '</td>' +
                '<td>' + reading_group + '</td>' +
                '</tr>';

            studentListData.push(studentListTableRow);
        });
        return studentListData;
    }

    //Ajax Function to set multiple Reading Group
    function set_multiple_group(i_class, s_group) {
        var s_group = s_group;
        var i_class = i_class;

        $.ajax({
            url: s_template_url + '/edit-user-data.php',
            type: "POST",
            dataType: 'json',
            data: {
                'id': JSON.stringify(i_class),
                'meta': JSON.stringify('multiple_rg'),
                'value': JSON.stringify(s_group)
            },
            success: function(a_return) {
                if (typeof a_return !== 'undefined' && a_return !== 0) {
                    var successMessage =
                        '<div class="alert alert-success alert-dismissible mb30" role="alert">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                        '<strong>Success: </strong> Updated student reading group' +
                        '</div>';
                    $('#set-multiple-reading-group').html("Data refreshing. Please wait ...").attr("disabled",
                        true).addClass('btn-success');
                    $("#create-student-group-modal .modal-body .panel").before(successMessage);

                    location.reload();

                }

            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
                //e_btn.text('Error!');
            },
            complete: function() {
                //e_btn.removeClass('running');
                //$('body').css({'cursor': 'default'});
            }
        });
    }


    function initialise_student_group_table() {
        $('#create-student-group-modal table').dataTable({
            "paging": false,
            "info": false,
            "searching": false,
            "columnDefs": [{
                "width": "8%",
                "targets": 0
            }]
        });
    }



    function checkbox_selection() {
        //On table row click check the checkbox
        $('#create-student-group-modal table tbody tr').on('click', function(event) {
            if (event.target.type !== 'checkbox') {
                $(':checkbox', this).trigger('click');

                if ($('#create-student-group-modal table thead th:first').find('input:checkbox').prop("checked")) {
                    $('#create-student-group-modal table thead th:first').find('input:checkbox').prop("checked",
                        false);
                }
            }
        });


        //Add selected class when checked
        $("#create-student-group-modal table tbody").on('change', 'input', function(e) {
            if ($(this).is(":checked")) {
                $(this).closest('tr').addClass("info");
            } else {
                $(this).closest('tr').removeClass("info");
            }
        });


        //Table head first cell click trigger select all checkbox
        $('#create-student-group-modal table thead th:first').click(function(event) {
            var isChecked = $(this).find('input:checkbox').prop("checked");
            if (event.target.type !== 'checkbox') {
                //$(':checkbox', this).trigger('click').parents("table");
                $(':checkbox', this).trigger('click');
            }
        });

        //On click toggle all checkbox
        $('#selectAll').click(function(e) {
            var table = $(e.target).closest('table');
            $('td input:checkbox', table).prop('checked', this.checked);
            if (this.checked) {
                $('#create-student-group-modal table tbody tr').addClass('info');
            } else {
                $('#create-student-group-modal table tbody tr').removeClass('info');
            }
        });

    }


    function disableSelectedGroup(selected = null) {
        if (selected == null) {
            var selected = $('#set-student-reading option:selected').text();
        }
        var tableRow = $("#create-student-group-modal table tbody td").filter(function() {
            return $(this).text() == selected;
        }).closest("tr").toggleClass('warning');
        $('.warning td input:checkbox').prop('disabled', true).prop('checked', false);

        if ($('.warning').length) {
            $('#create-student-group-modal  th input:checkbox').prop('disabled', true).prop('checked', false);
        } else {
            $('#create-student-group-modal  th input:checkbox').prop('disabled', false);
        }

    }

    function groupedAlready() {
        disableSelectedGroup();

        $('#set-student-reading select').on('change', function() {
            var selected = $('#set-student-reading option:selected').text();
            $("#create-student-group-modal table tbody tr").removeClass();
            $('#create-student-group-modal td input:checkbox').prop('disabled', false);

            disableSelectedGroup(selected);

        });

    }


    $('#set-student-reading select').html(setStudentReadingGroupsInput());
    $('#create-student-group-modal table tbody').html(listStudentForReadingGroups());
    initialise_student_group_table();
    checkbox_selection();
    groupedAlready();








    $('#set-multiple-reading-group').on('click', function() {

        var group_selected = $("#set-student-reading select").val();
        var student_selected = [];
        $('#create-student-group-modal table tr :checkbox:checked').each(function(i) {
            student_selected[i] = $(this).val();
        });


        if (student_selected.length != 0) {
            $('#set-multiple-reading-group').html("Processing <i class='fa fa-circle-o-notch fa-spin'></i>").attr(
                "disabled", true);
            set_multiple_group(student_selected, group_selected);
        }



    });




    //Accessibility fixes
    //$('.class-statistics li a').attr('href', '#');
</script>






<?php
/* ------------------------------
 * SET WHOLE CLASS QUIZ MODAL
 * ------------------------------ */
?>
<div class="modal fade" id="class-quiz-modal" tabindex="-1" role="dialog" aria-labelledby="class-quiz-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title cn-label">Set quiz participation for all students as:</h3>
                <a role="button" class="btn-close-modal close-xl" data-toggle="modal" data-target="#manage-class-settings" data-dismiss="modal" aria-hidden="true">&times;</a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <div class="col-xsp-12 col-xs-6 col-sm-4">
                            <button type="button" class="btn btn-primary btn-block set-class-quiz" data-dismiss="modal" data-id="no">
                                Not Required
                            </button>
                        </div>
                        <div class="col-xsp-12 col-xs-6 col-sm-4">
                            <button type="button" class="btn btn-primary btn-block set-class-quiz" data-dismiss="modal" data-id="optional">
                                Optional
                            </button>
                        </div>
                        <div class="col-xsp-12 col-xs-6 col-sm-4">
                            <button type="button" class="btn btn-primary btn-block set-class-quiz" data-dismiss="modal" data-id="compulsory">
                                Compulsory
                            </button>
                        </div>
                        <div class="col-xsp-12 col-xs-6 col-sm-4">
                            <button type="button" class="btn btn-primary btn-block set-class-quiz" data-dismiss="modal" data-id="school only">
                                School Only
                            </button>
                        </div>
                        <div class="col-xsp-12 col-xs-6 col-sm-4">
                            <button type="button" class="btn btn-primary btn-block set-class-quiz" data-dismiss="modal" data-id="home only">
                                Home Only
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#manage-class-settings" data-dismiss="modal">Close
                </button>
            </div>
        </div>
    </div>
</div>
<?php
/* ------------------------------
 * SET WHOLE CLASS NARRATION MODAL
 * ------------------------------ */
?>
<div class="modal fade" id="class-narration-modal" tabindex="-1" role="dialog" aria-labelledby="class-narration-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title cn-label">Set Whole Class Narration</h3>
                <a role="button" class="btn-close-modal close-xl" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings" aria-hidden="true">&times;</a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                        <div class="col-xs-6">
                            <button type="button" class="btn btn-default btn-block set-class-narration" data-dismiss="modal" data-id="Yes">
                                Yes
                            </button>
                        </div>
                        <div class="col-xs-6">
                            <button type="button" class="btn btn-default btn-block set-class-narration" data-dismiss="modal" data-id="No">
                                No
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings">Close
                </button>
            </div>
        </div>
    </div>
</div>

<?php
/* ----------------------------------
 * SET WHOLE CLASS Quiz NARRATION MODAL
 * ---------------------------------- */
?>
<div class="modal fade" id="set-whole-class-quiz-narration" tabindex="-1" role="dialog" aria-labelledby="set-whole-class-quiz-narration" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title cn-label">Set Whole Class Quiz Narration</h3>
                <a role="button" class="btn-close-modal close-xl" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings" aria-hidden="true">&times;</a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                        <div class="col-xs-6">
                            <button type="button" class="btn btn-success btn-block set-class-quiz-narration" data-dismiss="modal" data-id="Yes">
                                Yes
                            </button>
                        </div>
                        <div class="col-xs-6">
                            <button type="button" class="btn btn-danger btn-block set-class-quiz-narration" data-dismiss="modal" data-id="No">
                                No
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings">Close
                </button>
            </div>
        </div>
    </div>
</div>

<?php
/* ------------------------------
 * SET WHOLE CLASS READING GROUP PERMISSIONS
 * ------------------------------ */
?>
<div class="modal fade" id="class-setting-modal" tabindex="-1" role="dialog" aria-labelledby="class-setting-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title cn-label">Set Whole Class Reading Group Permissions</h3>
                <a role="button" class="btn-close-modal close-xl" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings" aria-hidden="true">&times;</a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="col-xsp-12 col-xsl-6 col-xs-6 col-sm-3">
                            <button type="button" class="btn btn-default btn-block set-class-setting" data-dismiss="modal" data-id="on">
                                On
                            </button>
                        </div>
                        <div class="col-xsp-12 col-xsl-6 col-xs-6 col-sm-3">
                            <button type="button" class="btn btn-default btn-block set-class-setting" data-dismiss="modal" data-id="school">
                                School
                            </button>
                        </div>
                        <div class="col-xsp-12 col-xsl-6 col-xs-6 col-sm-3">
                            <button type="button" class="btn btn-default btn-block set-class-setting" data-dismiss="modal" data-id="home">
                                Home
                            </button>
                        </div>
                        <div class="col-xsp-12 col-xsl-6 col-xs-6 col-sm-3">
                            <button type="button" class="btn btn-default btn-block set-class-setting" data-dismiss="modal" data-id="off">
                                Off
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings">Close
                </button>
            </div>
        </div>
    </div>
</div>
<?php
/* -----------------------------------
 * SET WHOLE CLASS READING LEVEL MODAL
 * ----------------------------------- */
?>
<div class="modal fade" id="class-level-modal" tabindex="-1" role="dialog" aria-labelledby="class-level-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title cn-label">Set Whole Class Reading Level</h3>
                <a role="button" class="btn-close-modal close-xl" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings" aria-hidden="true">&times;</a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div id="bulk-level-select-button"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings">Close
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="class-access-modal" tabindex="-1" role="dialog" aria-labelledby="class-access-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title cn-label">Set Whole Class Level Access</h3>
                <a role="button" class="btn-close-modal close-xl" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings" aria-hidden="true">&times;</a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <?php
                        if (!empty($a_results['data']['access'])) {
                            foreach ($a_results['data']['access'] as $s_slug => $s_name) {
                                if (!isset($s_slug) || empty($s_slug)) {
                                    $s_name = 'No Access Level';
                                }

                                $s_btn = '<div class="col-xs-12 col-sm-6">' .
                                    '<button type="button" class="btn btn-block btn-default set-class-access" data-dismiss="modal" ' .
                                    ' data-id="' . $s_slug . '">' . $s_name . '</button>' .
                                    '</div>';
                                echo $s_btn;
                                unset($s_btn);
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings">Close
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="archive-student-modal" tabindex="-1" role="dialog" aria-labelledby="archive-student-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title cn-label">Archive Student</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="student-archive-list" class="col-xs-12 col-sm-12">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings">Cancel
                </button>
            </div>
        </div>
    </div>
</div>


<?php
/* ----------------------------------
 * SET WHOLE CLASS Quiz Results MODAL
 * ---------------------------------- */
?>
<div class="modal fade" id="set-whole-class-quiz-results" tabindex="-1" role="dialog" aria-labelledby="set-whole-class-quiz-results" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title cn-label">Set Whole Class Quiz Results</h3>
                <a role="button" class="btn-close-modal close-xl" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings" aria-hidden="true">&times;</a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                        <div class="col-xs-6">
                            <button type="button" class="btn btn-success btn-block set-class-quiz-results" data-dismiss="modal" data-id="Yes">
                                Yes
                            </button>
                        </div>
                        <div class="col-xs-6">
                            <button type="button" class="btn btn-danger btn-block set-class-quiz-results" data-dismiss="modal" data-id="No">
                                No
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings">Close
                </button>
            </div>
        </div>
    </div>
</div>



<?php
/* ------------------------------
 * SET WHOLE CLASS NARRATION MODAL
 * ------------------------------ */
?>
<div class="modal fade" id="set-whole-class-book-read" tabindex="-1" role="dialog" aria-labelledby="set-whole-class-book-read" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title cn-label">Set Whole Class Allow Book Read for Quiz</h3>
                <a role="button" class="btn-close-modal close-xl" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings" aria-hidden="true">&times;</a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                        <div class="col-xs-6">
                            <button type="button" class="btn btn-success btn-block set-class-book-read" data-dismiss="modal" data-id="Yes">
                                Yes
                            </button>
                        </div>
                        <div class="col-xs-6">
                            <button type="button" class="btn btn-danger btn-block set-class-book-read" data-dismiss="modal" data-id="No">
                                No
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings">Close
                </button>
            </div>
        </div>
    </div>
</div>


<div id="scrolltotop">
    <a href="#">
        <span class="sr-only">Go to Top</span>
        <i class="icon-chevron-up"></i>
    </a>
</div>

<!-- Necessary script for Export to excel -->
<script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
<script>
    $("#excelExport").on("click", function() {
        $(".buttons-excel").trigger("click");
    });
</script>

<!-- Necessary script ends -->



<!--MCL PAGE SCRIPTS-->
<script>
    jQuery(document).ready(function($) {
        //Page Load Initialise Functions
        initiate_first_table();
        tooltips();
        is_class_empty();
        listReadingLevelForBulk();

        //table editable cells
        function initiate_editable_fields() {
            //1. Edit First Name Field
            $(".first_name").editable({
                emptytext: 'Not set',
                mode: 'inline',
                success: function(response, value) {
                    var id = $(this).closest('tr').attr('id').replace('user-', '').trim();
                    var meta = $(this).attr('class').split(' ')[0];
                    edit_user_data(id, meta, value);
                    update_user_property(id, meta, value);
                }
            });
            //2. Edit Last Name Field
            $(".last_name").editable({
                emptytext: 'Not set',
                mode: 'inline',
                success: function(response, value) {
                    var id = $(this).closest('tr').attr('id').replace('user-', '');
                    var meta = $(this).attr('class').split(' ')[0];
                    edit_user_data(id, meta, value);
                    update_user_property(id, meta, value);
                }
            });
            //3. Edit Password Field
            $(".user_pass").editable({
                emptytext: 'Not set',
                mode: 'inline',
                success: function(response, value) {
                    var id = $(this).closest('tr').attr('id').replace('user-', '');
                    var meta = $(this).attr('class').split(' ')[0];
                    edit_user_data(id, meta, value);
                    update_user_property(id, meta, value);
                }
            });
            //4. Edit Reading Level Field
            $(".reading_level").editable({
                type: 'select',
                emptytext: 'Not Set',
                mode: 'inline',
                source: o_levels,
                success: function(response, value) {
                    var id = $(this).closest('tr').attr('id').replace('user-', '');
                    var meta = $(this).attr('class').split(' ')[0];
                    edit_user_data(id, meta, value);
                    update_user_property(id, meta, value);
                }
            });
            //5. Edit Allowed Shelves Field
            $('.allowed_shelves').editable({
                type: 'select',
                emptytext: 'All Levels',
                mode: 'inline',
                source: o_access,
                success: function(response, value) {
                    var id = $(this).closest('tr').attr('id').replace('user-', '');
                    var checkReadingLevel = $("#user-" + id + " .reading_level").text().trim();
                    var meta = $(this).attr('class').split(' ')[0];
                    if (value === '') {
                        value = 'All Levels';
                    } else if ((checkReadingLevel === 'Not set' && value !== 'All Levels')) {
                        alert('Please select reading level first');
                        value = 'All Levels';
                    }
                    edit_user_data(id, meta, value);
                    update_user_property(id, meta, value);
                }
            });
            //6a. Edit Sound Cluster Field
            $('.sound_cluster').editable({
                type: 'select',
                emptytext: 'Not Set',
                mode: 'inline',
                source: o_sound_clusters,
                success: function(response, value) {
                    var id   = $(this).closest('tr').attr('id').replace('user-', '').trim();
                    var meta = $(this).attr('class').split(' ')[0];
                    edit_user_data(id, meta, value);
                    update_user_property(id, meta, o_sound_clusters[value] || 'Not Set');
                }
            });

            // Normalize a sound string: strip "Phase X - " prefix, keep only letters/digits (lowercase)
            function normalizeSounds(str) {
                return (str || '').replace(/^Phase\s+\d+\s*-\s*/i, '').toLowerCase().replace(/[^a-z0-9]/g, '');
            }

            // Inject a live search input above the select when the sound_cluster editable opens
            $(document).on('shown', '.sound_cluster', function(e, editable) {
                var $select = editable.input.$input;
                if ($select.prev('.sound-search-input').length) return;

                var $filter = $('<input>', {
                    type: 'text',
                    placeholder: 'Search sounds…',
                    'class': 'form-control sound-search-input',
                    style: 'margin-bottom:5px'
                });

                $filter.insertBefore($select);
                $filter.focus();

                $filter.on('input', function() {
                    var query = normalizeSounds($(this).val());
                    $select.find('option').each(function() {
                        var normalized = normalizeSounds($(this).text());
                        // Order-sensitive: normalized option must contain the query as a substring
                        $(this).prop('hidden', query && normalized.indexOf(query) === -1);
                    });
                });
            });

            //6b. Edit Phase Access Field
            $('.phase_access').editable({
                type: 'select',
                emptytext: 'Not Set',
                mode: 'inline',
                source: o_phase_access,
                success: function(response, value) {
                    var id   = $(this).closest('tr').attr('id').replace('user-', '').trim();
                    var meta = $(this).attr('class').split(' ')[0];
                    edit_user_data(id, meta, value);
                    update_user_property(id, meta, o_phase_access[value] || 'Not Set');
                }
            });
            //6. Edit Reading Group Field
            $('.my_reading_group').editable({
                type: 'select',
                emptytext: 'No Group',
                mode: 'inline',
                source: get_class_groups(),
                success: function(response, value) {
                    var user_id = $(this).closest('tr').attr('id').replace('user-', '').trim();

                    var meta = $(this).attr('class').split(' ')[0];
                    if (value === '0') {
                        value = null;
                    }
                    edit_user_data(user_id, meta, value);
                    update_user_property(user_id, meta, value);
                }
            });
            //7. Edit Reading Group Permissions Field
            $('.rg_setting').editable({
                type: 'select',
                mode: 'inline',
                source: get_rg_permissions(),
                success: function(response, value) {
                    var user_id = $(this).closest('tr').attr('id').replace('user-', '').trim();

                    var meta = $(this).attr('class').split(' ')[0];
                    edit_user_data(user_id, meta, value);
                    update_user_property(user_id, meta, value);
                }
            });
            //8. Edit Narration Field
            $(".narration").editable({
                type: 'select',
                emptytext: 'Not set',
                mode: 'inline',
                defaultValue: 'Yes',
                source: [{
                        value: 'Yes',
                        text: 'Yes'
                    },
                    {
                        value: 'No',
                        text: 'No'
                    }
                ],
                success: function(response, value) {
                    var id = $(this).closest('tr').attr('id').replace('user-', '');
                    var meta = $(this).attr('class').split(' ')[0];
                    edit_user_data(id, meta, value);
                    update_user_property(id, meta, value);
                }
            });

            //9. Edit Allow book view Field
            $(".allow_book_view").editable({
                type: 'select',
                emptytext: 'Not set',
                mode: 'inline',
                defaultValue: 'Yes',
                source: [{
                        value: 'Yes',
                        text: 'Yes'
                    },
                    {
                        value: 'No',
                        text: 'No'
                    }
                ],
                success: function(response, value) {
                    var id = $(this).closest('tr').attr('id').replace('user-', '');
                    var meta = $(this).attr('class').split(' ')[0];
                    edit_user_data(id, meta, value);
                    update_user_property(id, meta, value);
                }
            });
            //10. Edit Quizzes Field
            $(".quizzes").editable({
                type: 'select',
                emptytext: 'Not set',
                mode: 'inline',
                defaultValue: 'Yes',
                source: [{
                        value: 'no',
                        text: 'No'
                    },
                    {
                        value: 'optional',
                        text: 'Optional'
                    },
                    {
                        value: 'compulsory',
                        text: 'Compulsory'
                    },
                    {
                        value: 'school only',
                        text: 'School Only'
                    },
                    {
                        value: 'home only',
                        text: 'Home Only'
                    }
                ],
                success: function(response, value) {
                    var id = $(this).closest('tr').attr('id').replace('user-', '');
                    var meta = $(this).attr('class').split(' ')[0];
                    edit_user_data(id, meta, value);
                    update_user_property(id, meta, capitalise_words(value));
                }
            });


            //11. Edit Quiz Narration Field
            $(".quiz_narration").editable({
                type: 'select',
                emptytext: 'Not set',
                mode: 'inline',
                defaultValue: 'Yes',
                source: [{
                        value: 'Yes',
                        text: 'Yes'
                    },
                    {
                        value: 'No',
                        text: 'No'
                    }
                ],
                success: function(response, value) {
                    var id = $(this).closest('tr').attr('id').replace('user-', '');
                    var meta = $(this).attr('class').split(' ')[0];
                    edit_user_data(id, meta, value);
                    update_user_property(id, meta, value);
                }
            });

            //12. Edit Quiz detailled results Field
            $(".quiz_detail_results").editable({
                type: 'select',
                emptytext: 'Not set',
                mode: 'inline',
                defaultValue: 'Yes',
                source: [{
                        value: 'Yes',
                        text: 'Yes'
                    },
                    {
                        value: 'No',
                        text: 'No'
                    }
                ],
                success: function(response, value) {
                    var id = $(this).closest('tr').attr('id').replace('user-', '');
                    var meta = $(this).attr('class').split(' ')[0];
                    edit_user_data(id, meta, value);
                    update_user_property(id, meta, value);
                }
            });

            //13. Edit Student email Field
            $(".email").editable({
                emptytext: 'Not set',
                mode: 'inline',
                success: function(response, value) {
                    var id = $(this).closest('tr').attr('id').replace('user-', '').trim();
                    var meta = $(this).attr('class').split(' ')[0];
                    edit_user_data(id, meta, value);
                    //update_user_property(id, meta, value);
                }
            });
        }

        /* ----- WHOLE CLASS EDIT EVENTS ----- */
        //1. Set Whole CLASS Password Field
        $('#set-password').on('click', function() {
            var s_value = $('#new-class-password').val();
            edit_user_data(get_table_id(), 'classPass', s_value);
            update_user_property('all', 'user_pass', s_value);
        });
        //2. Set Whole CLASS Reading Level Field
        $(document).on('click', '.set-class-level', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var s_level = $(this).attr('data-id').trim();
            edit_user_data(get_table_id(), 'all_level', s_level);
            update_user_property('all', 'reading_level', s_level);
        });
        //3. Set Whole CLASS Narration Field
        $('.set-class-narration').on('click', function() {
            var s_narrate = $(this).attr('data-id').trim();
            edit_user_data(get_table_id(), 'allnarration', s_narrate);
            update_user_property('all', 'narration', capitalise_words(s_narrate));
        });
        //4. Set Whole CLASS Quizzes Field
        $('.set-class-quiz').on('click', function() {
            var s_quiz = $(this).attr('data-id').trim();
            edit_user_data(get_table_id(), 'allquiz', s_quiz);
            update_user_property('all', 'quizzes', capitalise_words(s_quiz));
        });
        //5. Archive Whole CLASS Users
        $('.btn-archive-class-confirm').on('click', function() {
            edit_user_data(get_table_id(), 'archiveAll', 0);
            toggle_user_archive_json('all', true);
        });
        //6. Set Whole CLASS Reading Group Permissions Setting
        $('.set-class-setting').on('click', function() {
            var s_setting = $(this).attr('data-id').trim();
            edit_user_data(get_table_id(), 'all_setting', s_setting);
            update_user_property('all', 'rg_setting', s_setting);
        });
        //7. Set Whole CLASS Quiz Narration Field
        $('.set-class-quiz-narration').on('click', function() {
            var class_quiz_narrate = $(this).attr('data-id').trim();
            edit_user_data(get_table_id(), 'all_quiz_narration', class_quiz_narrate);
            update_user_property('all', 'quiz_narration', capitalise_words(class_quiz_narrate));
        });
        //8. Set Whole CLASS Quiz Results Field
        $('.set-class-quiz-results').on('click', function() {
            var class_quiz_results = $(this).attr('data-id').trim();
            edit_user_data(get_table_id(), 'all_quiz_results', class_quiz_results);
            update_user_property('all', 'quiz_detail_results', capitalise_words(class_quiz_results));
        });
        //9. Set Class Allow Book Read for Quiz Field
        $('.set-class-book-read').on('click', function() {
            let class_book_read = $(this).attr('data-id').trim();
            edit_user_data(get_table_id(), 'all_book_read', class_book_read);
            update_user_property('all', 'allow_book_view', capitalise_words(class_book_read));
        });
        $(document).on('click', '.set-class-access', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var s_level = $(this).attr('data-id').trim();
            edit_user_data(get_table_id(), 'all_shelves', s_level);
            update_user_property('all', 'allowed_shelves', s_level);
        });
        /* ----------------------------------*/

        // ----- CLICK EVENTS ----- \\

        //----- CHANGE CLASS EVENT -----\\
        $('li.class-list').on('click', function() {
            var i_class = $(this).find('a').attr('data-class').trim();
            switch_class_tables(i_class);


            //Destroy the table and recreate table
            if ($.fn.DataTable.isDataTable('#create-student-group-modal table')) {
                $('#create-student-group-modal table').DataTable().destroy();
            }

            $('#create-student-group-modal tbody').empty();

            //On class change- change table and reading group option in modal
            $('#set-student-reading select').html(setStudentReadingGroupsInput(i_class));
            $('#create-student-group-modal table tbody').html(listStudentForReadingGroups(i_class));
            listReadingLevelForBulk(i_class);

            initialise_student_group_table();
            //checkbox_selection();
            groupedAlready();

            $('#create-student-group-modal table tbody tr').click(function(event) {
                if (event.target.type !== 'checkbox') {
                    $(':checkbox', this).trigger('click');
                }
            });
        });
        //----- SWITCH CLASS/ARCHIVE TOGGLE -----\\
        $(document).on('click', '.switch-table', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var i_class = get_table_id();
            var panel_header = $('.panel-class-lists').find('.panel-heading').find('#panel-title');
            var s_type = 'class';
            var s_title = 'Class List';

            if ($(this).hasClass('to-class')) {
                //Switch Back to Class View
                $('.btn.btn-mc-modal.panel-manage-btn').show();
                $('.btn.btn-mc-modal.to-class').hide();
            } else if ($(this).hasClass('to-archive')) {
                s_type = 'archive';
                s_title = 'Archive List';

                //Switch Back to Class View
                $('.btn.btn-mc-modal.panel-manage-btn').hide();
                $('.btn.btn-mc-modal.to-class').show();
            }

            panel_header.text(s_title);
            build_table(i_class, s_type);

            return true;
        });

        //----- Create List of Students to Archive -----\\
        $('.btn-archive-students').on('click', function() {
            var class_id = get_table_id();
            var o_users = o_classes[class_id].users.class;

            var s_classes = 'btn btn-primary btn-block btn-student-archive';

            var a_btns = [];
            $.each(o_users, function(idx, o_user) {
                var a_btn = [];
                a_btn.push('<div class="col-xs-12 col-sm-6">');
                a_btn.push('<button class="' + s_classes + '" data-dismiss="modal" data-id="' +
                    o_user.id_hash + '">');
                a_btn.push(o_user.first_name + ' ' + o_user.last_name);
                a_btn.push('</button>');
                a_btn.push('</div>');

                a_btns.push(a_btn.join(''));
            });

            $('#student-archive-list').empty().append(a_btns.join(''));
        });

        //----- Archive Single User -----\\
        $(document).on('click', '.btn-student-archive', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).attr('data-id').trim();
            edit_user_data(id, 'active', 0);
            toggle_user_archive_json(id, true);
        });

        //----- UnArchive Single User -----\\
        $(document).on('click', '.btn-student-unarchive', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).parents('tr').attr('id').replace('user-', '');
            edit_user_data(id, 'active', 1);
            toggle_user_archive_json(id, false);
        });

        //----- Display Existing Class Groups For New Group Modal -----\\
        $('.btn.btn-new-rg').on('click', function() {
            var class_id = get_table_id();
            var o_groups = o_classes[class_id].groups;

            $('#create-reading-group').text('Create Group');

            var a_btns = [];
            $.each(o_groups, function(s_slug, s_group) {
                var a_btn = [];
                if (s_slug !== '0') {
                    a_btn.push('<div class="existing-item" data-class="' + class_id +
                        '" data-id="' + s_slug + '" data-dismiss="modal">');
                    a_btn.push(s_group);
                    a_btn.push('</div>');
                }

                a_btns.push(a_btn.join(''));
            });

            $('#create-group-modal').find('#teacher-existing-groups').empty().append(a_btns.join(''));
            $('#group-message').text('');
        });

        // ----- Create New Group Event ----- \\
        $('#create-reading-group').on('click', function() {
            var new_group = $('#new-group-name').val();
            var class_id = get_table_id();
            var o_msg = $('#group-message');

            if (new_group.length <= 0) {
                o_msg.text('Please enter a name for your new group');
                return false;
            }

            var s_new = new_group.toLowerCase().trim();

            var a_exists = $('#teacher-existing-groups').find('.existing-item[data-class="' + class_id +
                '"]');
            var b_valid = true;
            $.each(a_exists, function(idx, o_exist) {
                var o_group = $(o_exist);
                if (o_group.text().toLowerCase().trim() == s_new) {
                    b_valid = false;
                }
                o_group = null;
            });

            if (!b_valid) {
                o_msg.text('Please enter a unique name');
                return false;
            }

            //Reset Error Message
            o_msg.text('');

            //Run Button Animations
            var e_btn = $(this);
            e_btn.addClass('running');
            e_btn.text('Creating...');
            $('body').css({
                'cursor': 'wait!important'
            });

            //Run Ajax
            create_new_group(class_id, new_group);

            return true;
        });

        //Create New Student User Event
        $('.btn-add-student').on('click', function() {
            var e_panel = $('#add-new-child').find('.new-child');
            var s_first = e_panel.find('input[name="first_name"]').val().trim();
            var s_last = e_panel.find('input[name="last_name"]').val().trim();
            var s_email = e_panel.find('input[name="student_email"]').val().trim();
            if (s_first.length <= 0 || s_last.length <= 0) {
                console.log('Enter student name');
                $('#add-new-child .panel-body').append(notificationDisplay('First & Last name are required field.', 'danger'));
                autoCloseNotification();
                return false;
            }

            //Capitalise Words in name
            s_first = capitalise_words(s_first);
            s_last = capitalise_words(s_last);
            var e_btn = $(this);

            if (s_email != '') {

                checkForExistingStudent(s_email).then(function(response) {

                    var response = JSON.parse(response);

                    if (typeof response.valid !== 'undefined' && response.valid == false) {
                        $('#add-new-child .panel-body').append(notificationDisplay(response.valid_message, 'danger'));
                        autoCloseNotification();
                        return false;

                    }

                    var con = false;

                    if (response.user_exists) {

                        ask.confirm.render({
                            heading: 'Archived Students',
                            message: response.confirm_message,
                            onConfirm: function() {

                                return student_update(s_first, s_last, s_email, e_btn, 'Moving...');

                            }

                        });

                        // con = confirm(response.confirm_message);
                        // if (con) {

                        //     return student_update(s_first, s_last, s_email, e_btn, 'Moving...');

                        // }

                    } else {



                        return student_update(s_first, s_last, s_email, e_btn, 'Adding...');




                    }



                });

            } else {


                return student_update(s_first, s_last, s_email, e_btn, 'Adding...');

            }
        });

        $('.btn-help').on('click', function() {
            $('.help-group-popover').popover('toggle');
        });

        if ($('div[role="tabpanel"] .tab-content').hasClass('no-classes')) {
            $('#no-classes').modal('toggle');
        }

        // ---------------------------------- \\
        // ------ BUILD TABLE FUNCTIONS ----- \\
        // ---------------------------------- \\

        function build_table(i_class, s_type) {

            var b_archived = s_type != 'class';
            var s_tags = 'display table table-bordered table-condensed table-hover class-table';
            var a_table = [];
            var a_users = o_classes[i_class]['users'][s_type];


            a_table.push('<div role="tabpanel" id="' + i_class + '-' + s_type + '" data-class="' + i_class +
                '" class="tab-pane fade in active">');
            a_table.push('<div class="table-responsive">');
            a_table.push('<table data-class="' + i_class + '" data-type="archive" class="' + s_tags + '">');
            a_table.push(get_table_header(b_archived).join(''));
            a_table.push(add_table_rows(a_users, b_archived).join(''));
            a_table.push(get_table_footer(b_archived).join(''));
            a_table.push('</table>');
            a_table.push('</div>');
            a_table.push('</div><!-- END tabpanel -->');

            var o_content = $('.tab-content');
            o_content.fadeTo(200, 0, function() {
                o_content.empty().append(a_table.join(''));
                //Run table formatting
                initiate_datatables();
                $('table[role=grid]').removeAttr('role').attr('role', 'presentation');
                if (!b_archived) {
                    initiate_editable_fields();
                }
                o_content.fadeTo(200, 1);
            });

            return true;
        }

        function get_table_header(b_archive) {
            var a_head = [];
            a_head.push('<thead>');
            a_head.push('<tr class="class-view-table-category">');
            a_head.push(get_category_row(b_archive).join(''));
            a_head.push('</tr>');
            a_head.push('<tr class="class-view-table-heading">');
            a_head.push(get_header_rows(b_archive).join(''));
            a_head.push('</tr>');
            a_head.push('</thead>');

            return a_head;
        }

        function get_table_footer(b_archive) {
            var a_foot = [];
            a_foot.push('<tfoot>');
            a_foot.push('<tr class="class-view-table-category table-footer">');
            a_foot.push(get_category_row(b_archive).join(''));
            a_foot.push('</tr>');
            a_foot.push('<tr class="class-view-table-heading table-footer">');
            a_foot.push(get_header_rows(b_archive).join(''));
            a_foot.push('</tr>');
            a_foot.push('</tfoot>');

            return a_foot;
        }

        function get_header_rows(b_archive) {
            var a_rows = [];
            // General
            a_rows.push('<th class="class-view-col-0">First Name</th>');
            a_rows.push('<th class="class-view-col-1">Surname</th>');
            a_rows.push('<th class="class-view-col-2">Username</th>');
            a_rows.push('<th class="class-view-col-2">Email</th>');
            a_rows.push('<th class="class-view-col-3">Password</th>');
            // Decodable Library
            a_rows.push('<th class="class-view-col-dec">Sound Cluster</th>');
            a_rows.push('<th class="class-view-col-dec">Phase Access</th>');
            // Reading Group (shared)
            a_rows.push('<th class="class-view-col-6">Reading Group</th>');
            a_rows.push('<th class="class-view-col-7">Reading Group Permissions</th>');
            // Levelled Library
            a_rows.push('<th class="class-view-col-4">Reading Level</th>');
            a_rows.push('<th class="class-view-col-5">Levels Access</th>');
            // No category
            a_rows.push('<th class="class-view-col-8">Allow Narration</th>');
            a_rows.push('<th class="class-view-col-9">Allow Book Read During Quiz</th>');
            a_rows.push('<th class="class-view-col-10">Quizzes</th>');
            a_rows.push('<th class="class-view-col-10">Allow Quiz Narration</th>');
            a_rows.push('<th class="class-view-col-10">Allow Detailed Quiz Results</th>');
            a_rows.push(b_archive ? '<th class="class-view-col-11">Archive</th>' : null);
            if(!isQRDisabled){
                a_rows.push('<th class="class-view-col-12">Regenerate QR</th>');
            }

            return a_rows;
        }

        function get_category_row(b_archive) {
            var a_rows = [];
            a_rows.push('<th colspan="5" class="col-category col-category-general text-center">General</th>');
            a_rows.push('<th colspan="2" class="col-category col-category-decodable text-center">Decodable Library</th>');
            a_rows.push('<th colspan="2" class="col-category"></th>');
            a_rows.push('<th colspan="2" class="col-category col-category-levelled text-center">Levelled Library</th>');
            a_rows.push('<th class="col-category"></th>');
            a_rows.push('<th class="col-category"></th>');
            a_rows.push('<th class="col-category"></th>');
            a_rows.push('<th class="col-category"></th>');
            a_rows.push('<th class="col-category"></th>');
            if (b_archive) { a_rows.push('<th class="col-category"></th>'); }
            if (!isQRDisabled) { a_rows.push('<th class="col-category"></th>'); }
            return a_rows;
        }

        function add_table_rows(a_users, b_archived) {
            var a_rows = [];

            $(a_users).each(function(idx, o_user) {
                var a_row = [];
                a_row.push('<tr class="class-view-table-data row-odd" id="user-' + o_user.id_hash + '">');
                a_row.push(add_user_row(o_user, b_archived).join(''));
                a_row.push('</tr>');

                a_rows.push(a_row.join(''));
                a_row = null;
            });

            return a_rows;
        }

        function add_user_row(o_user, b_archived) {

            var a_row = [];
            a_row.push('<td><button class="first_name">' + o_user.first_name + '</button></td>');
            a_row.push('<td><button class="last_name">' + o_user.last_name + '</button></td>');
            a_row.push('<td><span class="username">' + o_user.username + '</span></td>');
            a_row.push('<td><span class="email">' + o_user.email + '</span></td>');
            a_row.push('<td><button class="user_pass">' + o_user.user_pass + '</button></td>');
            // Decodable Library
            a_row.push('<td><button class="sound_cluster" data-value="' + (o_user.sound_cluster || '') + '">' + (o_user.sound_cluster || 'Not Set') + '</button></td>');
            a_row.push('<td><button class="phase_access" data-value="' + (o_user.phase_access || '') + '">' + (o_user.phase_access || 'Not Set') + '</button></td>');
            // Reading Group
            a_row.push('<td>');
            a_row.push('<button class="my_reading_group" data-value="' + o_user.my_reading_group.ID + '">');
            a_row.push(o_user.my_reading_group.value);
            a_row.push('</button>');
            a_row.push('</td>');
            a_row.push('<td>');
            a_row.push('<button class="rg_setting" aria-label="Reading Group Permission: ' + o_user.rg_setting
                .name + '" data-value="' + o_user.rg_setting.id + '">');
            a_row.push(o_user.rg_setting.name);
            a_row.push('</button>');
            a_row.push('</td>');
            // Levelled Library
            a_row.push('<td data-order="' + o_user.reading_level.slug + '">');
            a_row.push('<button class="reading_level" data-value="' + o_user.reading_level.slug + '">');
            a_row.push(o_user.reading_level.name);
            a_row.push('</button>');
            a_row.push('</td>');
            a_row.push('<td><button class="allowed_shelves" data-value="' + o_user.allowed_shelves + '">' + o_user
                .allowed_shelves + '</button></td>');
            a_row.push('<td><button class="narration yesorno" aria-label="Narration: ' + o_user.narration +
                '" data-value="' + o_user.narration + '">' + o_user
                .narration + '</button></td>');
            a_row.push('<td><button class="allow_book_view yesorno" aria-label="Allow book read during quiz: ' +
                o_user.allow_book_view + '" data-value="' + o_user.allow_book_view + '">' +
                o_user.allow_book_view + '</button></td>');
            a_row.push('<td><button class="quizzes" data-value="' + o_user.quizzes + '">' + o_user.quizzes +
                '</button></td>');
            a_row.push('<td><button class="quiz_narration yesorno" aria-label="Quiz Narration: ' + o_user
                .quiz_narration +
                '" data-value="' + o_user.quiz_narration + '">' + o_user
                .quiz_narration + '</button></td>');
            a_row.push('<td><button class="quiz_detail_results yesorno" aria-label="Allow Quiz detail results: ' + o_user
                .quiz_detail_results +
                '" data-value="' + o_user.quiz_detail_results + '">' + o_user
                .quiz_detail_results + '</button></td>');
            if (b_archived) {
                a_row.push(
                    '<td><button class="btn btn-default btn-student-unarchive" type="button">Unarchive</button></td>'
                );
            }
            if(!isQRDisabled){

                a_row.push('<td><button class="user_qr btn btn-small btn-default" data-id="' + o_user.id_hash + '">Regenerate</button></td>');

            }
           

            return a_row;
        }

        // -------------------------------------------- //
        //              GENERAL FUNCTIONS               //
        // -------------------------------------------- //

        //Builds First table on page load
        function initiate_first_table() {
            build_table(o_active.id, o_active.type);
        }

        //Loads jQuery DataTables Library on Class Table
        function initiate_datatables() {

            var activeClassName = $(".class-statistics .nav .active a").text();

            var a_tableArgs = {
                'paging': false,
                'iDisplayLength': 35,
                'info': true,
                'bFilter': false,
                'dom': 'Bfrtip',
                'buttons': [{
                    extend: 'excelHtml5',
                    filename: activeClassName,
                    title: ''
                }],
                "order": [
                    [1, 'asc']
                ]
            };

            //Set table length for class page
            $('table.class-table').DataTable(a_tableArgs);
        }

        //Checks if Current Class is Empty, Toggles Notice Popup
        function is_class_empty() {
            var class_size = $('.class-counter:visible').find('label.class-counter-current').text();
            if (class_size === "0") {
                $('.btn-upload-class-list')[0].click();
            }
        }

        //Get Currently Selected Table ID
        function get_table_id() {
            var o_active = $('.class-list.class-switch.active');
            if (o_active.length > 0) {
                return o_active.find('a').attr('data-class');
            }

            return false;
        }

        // Modal Interactions for Class Upload Modal
        var modal = function(isShown) {
            var o_screen = $('.screen');
            o_screen.html('<img src="' + s_template_url + '/img/ajax-loader-2.gif">');
            o_screen.find('img').css({
                'top': '20%',
                'left': '50%',
                'position': 'relative'
            });
            o_screen.css({
                'background-color': '#000000',
                opacity: 0.5,
                'width': $(document).width(),
                'height': $(document).height()
            });
            o_screen.css({
                'overflow': 'hidden',
                'z-index': 10
            });
            o_screen.css({
                'display': isShown,
                'position': 'absolute'
            });
        };

        //Breaks a String apart and capitalises first character of each word
        function capitalise_words(s_string) {
            var a_words = s_string.split(' ');
            var a_new = [];

            for (var ii = 0; ii < a_words.length; ++ii) {
                a_new.push(a_words[ii].charAt(0).toUpperCase() + a_words[ii].slice(1));
            }

            return a_new.join(' ').trim();
        }

        function get_rg_permissions() {
            return o_settings;
        }

        //Prompts Message If Error Occurs Uploading Class List
        function upload_class_error(s_error) {
            $('#studentForm').append(notificationDisplay(s_error, 'danger'));
            autoCloseNotification();
        }

        //Get Reading Groups for Class X
        function get_class_groups(i_class) {
            if (typeof i_class == 'undefined' || i_class == null || i_class.length <= 0) {
                i_class = get_table_id();
            }

            return o_classes[i_class].groups;
        }

        function is_iPad() {
            return (navigator.platform.indexOf("iPad") != -1);
        }

        //Show Tooltips
        function tooltips() {
            if (!is_iPad()) {
                $('[data-toggle="tooltip"]').tooltip();
                $('[data-toggle="popover"]').popover();
            }
        }

        //Create Random INT for Username Generation
        function getRandomInt(min, max) {
            return min + Math.floor(Math.random() * (max - min + 1));
        }

        //Adds new User HTML row to Class Table
        function add_new_row(o_user) {
            console.log('Creating Rows');
            var s_row = $('<tr>').append(
                $('<td>').append(
                    $('<span>').addClass('first_name').append(o_user.first_name)
                )
            ).append(
                $('<td>').append(
                    $('<span>').addClass('last_name').append(o_user.last_name)
                )
            ).append(
                $('<td>').append(
                    $('<span>').addClass('username').append(o_user.username)
                )
            ).append(
                $('<td>').append(
                    $('<span>').addClass('email').append(o_user.email)
                )
            ).append(
                $('<td>').append(
                    $('<span>').addClass('user_pass').append(o_user.user_pass)
                )
            ).append(
                $('<td>').append(
                    $('<span>').addClass('sound_cluster').attr('data-value', '').append('Not Set')
                )
            ).append(
                $('<td>').append(
                    $('<span>').addClass('phase_access').attr('data-value', '').append('Not Set')
                )
            ).append(
                $('<td>').append(
                    $('<span>').addClass('my_reading_group').attr('value', '0').append(o_user.my_reading_group.value)
                )
            ).append(
                $('<td>').append(
                    $('<span>').addClass('rg_setting').attr('value', 'on').append(o_user.rg_setting.name)
                )
            ).append(
                $('<td>').attr('data-order', '').append(
                    $('<span>').attr('data-value', '').addClass('reading_level').append(o_user.reading_level.name)
                )
            ).append(
                $('<td>').append(
                    $('<span>').addClass('allowed_shelves').attr('value', 'all').append(o_user.allowed_shelves)
                )
            ).append(
                $('<td>').append(
                    $('<span>').addClass('narration yesorno').attr('value', o_user.narration).append(o_user
                        .narration)
                )
            ).append(
                $('<td>').append(
                    $('<span>').addClass('allow_book_view yesorno').attr('value', o_user.allow_book_view)
                    .append(o_user.allow_book_view)
                )
            ).append(
                $('<td>').append(
                    $('<span>').addClass('quizzes').attr('value', o_user.quizzes).append(o_user.quizzes)
                )
            ).append(
                $('<td>').append(
                    $('<span>').addClass('quiz_narration yesorno').attr('value', o_user.quiz_narration).append(
                        o_user
                        .quiz_narration)
                )
            ).append(
                $('<td>').append(
                    $('<span>').addClass('quiz_detail_results yesorno').attr('value', o_user.quiz_detail_results).append(
                        o_user
                        .quiz_detail_results)
                )
            );


         
            if(!isQRDisabled){

                s_row =  s_row.append(
                $('<td>').append(
                    $('<button>').addClass('user_qr btn btn-small btn-default').attr('data-id', o_user.id_hash).append(
                        "Regenerate")
                )
               );

            }

          

            var o_active = $('.tab-pane.active');

            if (o_active.find('tbody').find('.class-view-table-data').length <= 0) {
                o_active.find('tbody').empty();
            }
            var this_table = $('div.in.active').find('table').dataTable({
                'paging': false,
                'iDisplayLength': 35,
                'info': false,
                'retrieve': true
            });

            if (o_active.find('tbody').find('.class-view-table-data').length <= 0) {
                console.log('Class empty, clear table');
                this_table.fnClearTable();
            }

            console.log('Adding data to rows');
            var rowIndex = this_table.fnAddData(s_row);

            return this_table.fnGetNodes(rowIndex);
        }

        //Triggers various labels&Classes for moving between Class Tables
        function switch_class_tables(i_class) {
            //Archive Student Filters
            var o_user_archived = $('.students-to-archive');
            o_user_archived.find('.archive-students-wrap').hide();
            o_user_archived.find('.archive-students-wrap[data-class="' + i_class + '"]').show();

            //Switch Back to Class View
            $('.btn.btn-mc-modal.panel-manage-btn').show();
            $('.btn.btn-mc-modal.to-class').hide();

            //Change Upload Class List Modal Selector to highlight current class
            $('#upload-dialog').find('#upload-class-select').val(i_class);

            //Build New Table
            build_table(i_class, 'class');
            //IF class is empty, open 'upload class list' modal
            is_class_empty();
        }

        //Add New User data to JSON object
        function add_user_to_json(i_class, a_user) {

            if (typeof o_classes[i_class].users.class == 'undefined') {
                o_classes[i_class].users.class = [];
            }
            o_classes[i_class].users.class.push(a_user);



        }

        //Update existing user in JSON object
        function update_user_property(i_hash, s_key, x_value) {
            var i_class = get_table_id();

            var o_users = o_classes[i_class].users.class;
            $.each(o_users, function(idx, o_user) {
                if (i_hash == 'all' || i_hash == o_user.id_hash) {
                    var i_id = idx;
                    if (s_key == 'reading_level') {
                        if (typeof x_value == 'undefined' || x_value.length <= 0) {
                            o_classes[i_class].users.class[i_id].reading_level.slug = '';
                            o_classes[i_class].users.class[i_id].reading_level.name = '';
                        } else {
                            o_classes[i_class].users.class[i_id].reading_level.slug = x_value;
                            o_classes[i_class].users.class[i_id].reading_level.name = o_levels[x_value];
                        }
                    } else if (s_key == 'my_reading_group') {
                        o_classes[i_class].users.class[i_id].my_reading_group.ID = x_value;
                        o_classes[i_class].users.class[i_id].my_reading_group.value = o_classes[i_class][
                            'groups'
                        ][x_value];
                    } else if (s_key == 'rg_setting') {
                        o_classes[i_class].users.class[i_id].rg_setting.id = x_value;
                        o_classes[i_class].users.class[i_id].rg_setting.name = o_settings[x_value];
                    } else if (s_key == 'allowed_shelves') {
                        o_classes[i_class].users.class[i_id].allowed_shelves = o_access[x_value];
                    } else {
                        o_classes[i_class].users.class[i_id][s_key] = x_value;
                    }
                }

                return true;
            });
        }

        //Move Users between Class & Archive User Arrays in JSON object
        function toggle_user_archive_json(i_hash, b_archive) {
            var i_class = get_table_id();

            var s_to = b_archive ? 'archive' : 'class';
            var s_from = b_archive ? 'class' : 'archive';

            var a_from = o_classes[i_class]['users'][s_from];
            var a_new = [];
            $.each(a_from, function(idx, o_user) {
                if (i_hash == 'all' || i_hash == o_user.id_hash) {
                    //Add User to new class type array
                    if (typeof o_classes[i_class]['users'][s_to] == 'undefined') {
                        o_classes[i_class]['users'][s_to] = [];
                    }

                    o_classes[i_class]['users'][s_to].push(o_user);
                } else {
                    //Build Clean User Array
                    a_new.push(o_user);
                }
            });

            o_classes[i_class]['users'][s_from] = a_new;
            return true;
        }

        //Adds new Class Group to JSON object
        function add_new_group_to_json(i_slug, s_group) {
            var i_class = get_table_id();
            o_classes[i_class].groups.splice(i_slug, 0, capitalise_words(s_group));
            return true;
        }

        // ----------------------------------------- //
        //              AJAX FUNCTIONS               //
        // ----------------------------------------- //

        //Ajax Function to Create new Reading Group
        function create_new_group(i_class, s_group) {
            var e_btn = $('#create-reading-group');
            $.ajax({
                url: s_template_url + '/edit-user-data.php',
                type: "POST",
                //dataType: 'json',
                data: {
                    'id': JSON.stringify(i_class),
                    'meta': JSON.stringify('new_rg'),
                    'value': JSON.stringify(s_group)
                },
                success: function(a_return) {
                    if (typeof a_return !== 'undefined' && a_return !== 0) {
                        e_btn.text('Created!');
                        //Dismiss New Reading Group Modal
                        $('#create-group-modal').modal('hide');
                        //Run JSON Update
                        o_classes[i_class].groups = a_return;
                        //add_new_group_to_json(parseInt(a_return), s_group);
                        build_table(get_table_id(), 'class');
                    } else {
                        e_btn.text('Error!');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                    console.log(thrownError);
                    e_btn.text('Error!');
                },
                complete: function() {
                    e_btn.removeClass('running');
                    $('body').css({
                        'cursor': 'default'
                    });
                }
            });
        }

        //Edit Meta Field on User
        function edit_user_data(id, meta, value) {
            var bRebuild = false;

            if (meta == 'active' || meta == 'classPass' || meta == 'all_level' || meta == 'all_shelves' ||
                meta == 'archiveAll' || meta == 'allquiz' || meta == 'allnarration' || meta == 'all_setting' ||
                meta == 'all_quiz_narration' || meta == 'quiz_detail_results' || meta == 'all_quiz_results' || meta == 'all_book_read' || meta == 'email') {
                bRebuild = true;
            }

            $.ajax({
                url: s_template_url + '/edit-user-data.php',
                type: "POST",
                //dataType: 'json',
                beforeSend: function() {
                    if (bRebuild) $('.tab-content').fadeTo(100, 0.3);
                },
                data: {
                    'value': JSON.stringify(value),
                    'id': JSON.stringify(id),
                    'meta': JSON.stringify(meta),
                    'class_id': JSON.stringify(get_table_id())
                },
                success: function(a_return) {
                    var class_id = get_table_id();
                    var type = 'class';

                    if (meta == 'className') {
                        $('#className').val(a_return.name);
                    }

                    if (meta == 'active') {
                        type = value == 1 ? 'archive' : 'class';
                    }


                    if (meta == 'email') {
                        let return_obj = JSON.parse(a_return);
                        if (return_obj.type == 'error') {
                            alert(return_obj.message);
                        }
                        if (return_obj.type == 'success') {
                            update_user_property(id, meta, value);
                        }
                    }

                    if (bRebuild) build_table(class_id, type);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                    console.log(thrownError);
                    if (bRebuild) $('.tab-content').fadeTo(0, 1);
                    alert(xhr.responseText);
                },
                complete: function() {
                    if (meta == 'classPass') {
                        $('#new-class-password').val('');
                    }
                }
            });
            return value;
        }

        //Create new Student User - Add row to Class Table
        function add_new_user(s_first, s_last, s_email = null) {
            var i_class = get_table_id();
            var s_user = s_first.charAt(0) + s_last.charAt(0) + '-' + getRandomInt(1000, 99999);

            $.ajax({
                url: s_template_url + '/add-new-user.php',
                type: "POST",
                //dataType: 'json',
                data: {
                    'teacher_id': JSON.stringify(i_hash),
                    'first_name': JSON.stringify(s_first),
                    'last_name': JSON.stringify(s_last),
                    's_email': JSON.stringify(s_email),
                    'username': JSON.stringify(s_user),
                    'class': JSON.stringify(i_class)
                },
                success: function(o_data) {

                    let o_return = JSON.parse(o_data);

                    if (typeof o_return.move !== 'undefined') {

                        try {

                            if (typeof o_return.user.id_hash !== 'undefined' && o_return.user.id_hash && typeof o_return.old_classId !== 'undefined' && o_return.old_classId) {

                                deleteStudentFromOtherClass(o_return.user.id_hash, o_return.old_classId);

                            }

                        } catch (err) {

                            console.log("Error -------->".err);

                        }


                        $('#add-new-child .panel-body').append(notificationDisplay('Student Moved to new class successfully.', 'success'));

                    } else {

                        $('#add-new-child .panel-body').append(notificationDisplay('Student created successfully.', 'success'));

                    }
                    autoCloseNotification();


                    if ((o_return.available > 0)) {
                        //Add New Row to Current Table
                        var row = add_new_row(o_return.user);

                        //Focus new User Rows
                        var i_user = o_return.user.id_hash;
                        $(row).addClass('class-view-table-data').attr('id', 'user-' + i_user).addClass(
                            'rows-odd');
                        $("#user-" + i_user + " td.focus_fname").keypress(function(event) {
                            if (event.which == 13) {
                                $("#user-" + i_user + " td.focus_lname").trigger('click');
                            }
                        });

                        //Add User to JSON object
                        add_user_to_json(i_class, o_return.user);

                        //Clear New User Form Inputs
                        $('#first_name, #last_name, #student_email').val('');

                    } else {
                        $('input[data-id="' + i_hash + '"]').attr('disabled', 'true');
                        $("#studentForm").next().html("You have used all your licenses<br/>").fadeIn(
                            1000).delay(5000).fadeOut(1000);
                    }
                    random_number = getRandomInt(1000, 99999);

                    var e_btn = $('.btn.btn-add-student');
                    wushka_button_finished(e_btn, 'Added!', 'Add Student');
                    initiate_editable_fields();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                    console.log(thrownError);
                    var e_btn = $('.btn.btn-add-student');
                    wushka_button_failed(e_btn, 'Error', 'Add Student');
                    $('#add-new-child .panel-body').append(notificationDisplay(xhr.responseText, 'danger'));
                    autoCloseNotification();
                },
                complete: function() {
                    var e_btn = $('.btn.btn-add-student');
                    e_btn.removeClass('running');
                }
            });
        }

        function notificationDisplay(message, messageType = 'success') {
            //Remove already open div
            if ($('.alert')) {
                $('.alert').fadeOut(1000, function() {
                    $(this).remove();
                });
            }
            var alertDiv = '<div class="alert alert-' + messageType + ' alert-dismissible" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                message +
                '</div>';
            return alertDiv;
        }

        function autoCloseNotification(delayTime = 4000) {
            $(".alert").delay(delayTime).slideUp(200, function() {
                $(this).alert('close');
            });
        }

        function student_update(s_first, s_last, s_email, e_btn, btn_text) {

            console.log('Create New User:');
            console.log('First Name: ' + s_first);
            console.log('Last Name: ' + s_last);
            console.log('Student Email: ' + s_email);
            e_btn.addClass('running');
            wushka_button_loading(e_btn, btn_text);

            add_new_user(s_first, s_last, s_email);
            return true;
        }

        function checkForExistingStudent(email) {


            var promise = new Promise(function(resolve, reject) {


                var classId = get_table_id();

                jQuery.ajax({
                    type: 'POST',
                    url: "<?php echo admin_url('admin-ajax.php') ?>",
                    data: {
                        classId: classId,
                        email: email,
                        action: 'wushka_student_exists'

                    },
                    success: function(response) {

                        resolve(response);

                    }
                });

            });

            return promise;

        }

        //Upload External Class List
        $('#importFile').on('click', function() {
            var e_btn = $('#importFile');
            if (e_btn.hasClass('disabled')) {
                return false;
            }

            e_btn.addClass('disabled');

            var formData = new FormData($('#studentForm')[0]);
            var i_class = $('#upload-dialog').find('#upload-class-select').val();
            var i_className = $('#upload-dialog').find('#upload-class-select option:selected').text();

            console.log('Upload students to class #' + i_class);

            formData.append("teacher_id", i_hash);
            formData.append("class_id", i_class);
            formData.append("class_name", i_className);
            $.ajax({
                url: s_template_url + '/upload.php',
                type: "POST",
                data: formData,
                dataType: "html",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    modal('block');
                },
                success: function(return_data) {
                    modal('none');
                    var obj = JSON.parse(return_data);
                    if (obj) {
                        var s_error = '';
                        var ava = obj.available;
                        if (ava == 0 || ava < 0) {
                            s_error = 'You have used all your licenses';
                        } else if (ava == "Invalid") {
                            s_error = 'Uploaded data format is not valid';
                        } else if (ava == "Empty") {
                            s_error = 'Uploaded file is empty';
                        } else if (ava == "Invalid Licence") {
                            s_error = 'Sorry licence for this class does not allow to perform this action';
                        }
                        if (s_error.length > 0) {
                            upload_class_error(s_error);
                            return false;
                        }

                        console.log(return_data);

                        location.reload();
                        return true;
                    }

                    upload_class_error('There was an error uploading your class');
                    return false;
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    modal('none');
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                    console.log(thrownError);
                },
                complete: function() {
                    e_btn.removeClass('disabled');
                }
            });
            return false;
        });


        $(document).on('click', '.confirm-archive-ask', function() {

            var cButton = $(this);

            var userId = $(this).data('userid');

            var targetClass = $(this).data('targetclass');

            cButton.html("Moving...");

            $.ajax({
                type: "POST",
                url: "<?php echo admin_url('admin-ajax.php') ?>",
                data: {
                    userId: userId,
                    targetClass: targetClass,
                    action: 'move_archive_student'
                },
                success: function(response) {

                    response = JSON.parse(response);

                    if (response.success) {

                        cButton.html("Completed");
                        cButton.addClass('disabled');
                        cButton.removeClass('btn-danger');
                        cButton.addClass('btn-success');

                    }

                },
                error: function() {

                    alert('Something went wrong ! Please try again.');

                    cButton.html("Confirm");

                }
            });


        });

        if ($("#archive-student-confirm-modal").length > 0) {
            $('#archive-student-confirm-modal').modal({
                backdrop: 'static',
                keyboard: false
            })
            $('#archive-student-confirm-modal').modal('show');
        }

        $(document).on('click', '.close-ask-archive-confirm', function() {

            $(this).html('Closing ...');
            location.reload();
        });

        // Add Student drawer toggle — appended to body with inline styles so position:fixed is never blocked
        $('body').append(
            '<div id="add-student-tab" title="Add New Student" style="display:none;position:fixed;right:0;top:50%;transform:translateY(-50%) rotate(180deg);writing-mode:vertical-rl;background:#337ab7;color:#fff;padding:14px 8px;cursor:pointer;border-radius:4px 0 0 4px;font-size:13px;font-weight:600;z-index:9999;align-items:center;gap:6px;user-select:none;box-shadow:-2px 0 6px rgba(0,0,0,0.15);">' +
            '<i class="glyphicon glyphicon-plus" style="margin-bottom:4px;"></i>' +
            '<i class="glyphicon glyphicon-user" style="margin-bottom:6px;"></i>' +
            '<span>Add Student</span>' +
            '</div>'
        );

        $('#toggle-student-drawer').on('click', function() {
            $('#sticky-panel').hide();
            $('#class-list-column').removeClass('col-lg-9 col-md-9').addClass('col-lg-12 col-md-12');
            $('#add-student-tab').css('display', 'flex');
        });

        $('#add-student-tab').on('click', function() {
            $(this).hide();
            $('#sticky-panel').show();
            $('#class-list-column').removeClass('col-lg-12 col-md-12').addClass('col-lg-9 col-md-9');
        });
    });
</script>
<!--END MCL PAGE SCRIPTS-->
<?php
}else{ ?>

  <div class="container alert alert-info" style="margin-top:50px">
  You must be assigned to at least one class in order to manage the class list. Please reach out to the Wushka program coordinator at your school.
  </div>

<?php }
include 'dashboard_options.php';
get_footer();

/* ----- EOF ----- */