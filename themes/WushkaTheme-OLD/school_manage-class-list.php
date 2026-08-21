<?php
/* Template Name: School Manage Class List */
if (!isset($current_user)) {
    global $current_user;
}

if (!(is_user_logged_in() && current_user_can('school')) || is_admin()) {
    wp_redirect(home_url());
    exit;
}

$s_auth = wp_create_nonce('school_' . $current_user->ID . '_move_students');

include_once 'functions/class_manage_class_list.php';

$c_class = new Class_List();
$c_class->get_class_data(TRUE);
$a_results = $c_class->get_results();
$a_active  = $a_results['data']['active'];
$isQRDisabled = isQRDisabled();
$a_options = array();
$s_name    = NULL;
if (!empty($a_results['data']['classes'])) {
    foreach ($a_results['data']['classes'] as $i_class => $a_class) {
        if ($a_class['deleted'] == TRUE) {
            continue;
        }

        $o_class     = $a_class['class'];
        if (!empty($o_class->name)) {
            $a_options[] = '<option value="' . $i_class . '">' . $o_class->name . '</option>';
        }

        if ($i_class == $a_active['id']) {
            $s_name = $o_class->name;
        }
    }
}

get_header();
?>
<style>
    #archive-student-confirm-modal th {
        padding: 8px;
    }
</style>

<!-- MCL PAGE DATA VARIABLES -->
<script>
    var isQRDisabled = "<?php echo $isQRDisabled ?>";
    isQRDisabled = !!isQRDisabled;
    var i_teacher = '<?php echo $current_user->ID; ?>';
    var i_hash = '<?php echo $current_user->id_hash; ?>';
    var s_template_url = '<?php echo get_template_directory_uri(); ?>';
    var o_active = <?php echo json_encode($a_active); ?>;
    var o_levels = <?php echo json_encode($a_results['data']['levels']); ?>;
    var o_access = <?php echo json_encode($a_results['data']['access']); ?>;
    var o_settings = <?php echo json_encode($a_results['data']['settings']); ?>;
    var o_classes = <?php echo json_encode($a_results['data']['classes']); ?>;


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

<div class="page-school-manage-class-list container-fluid">
    <div class="screen"></div>
    <div class="row mt15">
        <div class="col-xs-12">
            <h2 class="glyphicon-heading text-left">
                <span class="x2 glyphicon glyphicon-group hidden-xs"></span>
                <span class="glyphicon-heading-text">Step 4: Manage Students</span>
                <span class="glyphicon-heading-btn-group">
                    <span class="btn-back-dashboard">
                        <a href="/school-classes" role="button" class="btn btn-primary btn-back-to-dashboard">
                            <span class="glyphicon glyphicon-chevron-left"></span> Previous Step
                        </a>
                    </span>
                    <span class="btn-back-dashboard">
                        <a href="/school-dashboard" role="button" class="btn btn-primary btn-back-to-dashboard">
                            Finish Setup <span class="glyphicon glyphicon-ok-2"></span>
                        </a>
                    </span>
                </span>
            </h2>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2 col-md-2 panel-classes">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-heading-text editable" data-class="class-name">
                        <i class="glyphicon glyphicon-group"></i> Classes
                    </div>
                    <?php /* ?>
                    <div class="form-group btn-group active-group form-group-classes" role="group">
                        <div class="btn-group">
                            <button type="button" class="btn btn-filter btn-class" value="active">Active
                            </button>
                            <button type="button" class="btn btn-filter btn-class" value="deleted">Deleted
                            </button>
                        </div>
                    </div> 
                    <?php */ ?>
                </div>
                <div class="panel-body">
                    <div class="list-group class-list" role="tablist">
                        <?php echo implode('', $a_results['data']['menus']); ?>
                    </div>
                </div>
            </div>
            <?php /* Accessibility fix for broken same page link */ ?>
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
                $(document).on('click', 'a.class-switch', function() {
                    var current_tab = $('.tab-content table').data('class');
                    var switch_btn = $(this).data('class');
                    $('#' + switch_btn + '-class').attr('id', current_tab + '-class').attr('data-class', current_tab).removeClass('active');
                });
            </script>
            <?php /* Accessibility fix for broken same page link ends here */ ?>
        </div>
        <div class="col-lg-7 col-md-7">
            <div class="panel panel-default panel-class-lists">
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-group"></i>
                    <span class="class-name"><?php echo $s_name; ?></span>
                    <span class="panel-title">
                        <?php echo $a_active['type'] == 'archive' ? 'Archive List' : 'Class List'; ?>
                    </span>
                    <?php
                    $s_manage_btn = $a_active['type'] == 'class' ? NULL : 'display: none;';
                    $s_back_btn   = $a_active['type'] == 'class' ? 'display: none;' : NULL;
                    ?>
                    <?php if (!empty($a_results['data']['classes'])) { ?>
                        <label for="change-class-select" class="sr-only">Move To:</label>
                        <select id="change-class-select">
                            <option value="">Move To:</option>
                            <?php echo implode('', $a_options); ?>
                        </select>
                        <button class="btn btn-primary btn-mc-modal btn-change-class disabled" type="button">
                            Move Students
                        </button>
                        <button class="btn btn-primary btn-transfer-modal btn-transfer-student" data-toggle="modal" data-target="#transfer-student-modal" type="button">
                            Transfer Students
                        </button>
                        <button type="button" class="btn btn-default btn-mc-modal panel-manage-btn" data-toggle="modal" data-target="#manage-class-settings" style="<?php echo $s_manage_btn; ?>">Manage
                            Class
                        </button>
                        <button type="button" class="btn btn-primary btn-mc-modal switch-table to-class" style="<?php echo $s_back_btn; ?>">
                            Back to Class
                        </button>

                    <?php } ?>
                    <span class="clearfix"></span>
                </div>
                <div class="panel-body">
                    <div role="tabpanel">
                        <!-- Tablist was here -->
                        <div class="tab-content <?php echo (empty($a_results['data']['classes'])) ? 'no-classes' : NULL; ?>">

                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <?php if (!empty($a_results['data']['classes'])) { ?>
                        <span class="pull-right">
                            <button type="button" class="btn btn-default btn-mc-modal panel-manage-btn" data-toggle="modal" data-target="#manage-class-settings" style="<?php echo $s_manage_btn; ?>">Manage Class
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
                        <div class="col-lg-11 col-md-10 editable" data-class="class-name">
                            <i class="glyphicon glyphicon-user"></i> Add new student
                        </div>
                        <div class="col-lg-1 col-md-2" data-toggle="tooltip" data-placement="left" title="add new student">
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="well new-child">
                        <div class="form-group">
                            <label for="first_name" class="control-label">First Name <span class="colour-red">*</span></label>
                            <input type="text" name="first_name" class="form-control" id="first_name" value="" placeholder="First Name" />
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="control-label">Surname <span class="colour-red">*</span></label> <input type="text" name="last_name" class="form-control" id="last_name" value="" placeholder="Surname" />
                        </div>
                        <div class="form-group">
                            <label for="student_email" class="control-label">Student Education Email</label>
                            <input type="text" name="student_email" class="form-control" id="student_email" placeholder="Email" />
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-primary btn-add-student" data-id="<?php echo $current_user->id_hash; ?>" aria-label="Add Student">Add
                                Student
                            </button>
                        </div>
                        <input type="hidden" name="username" class="form-control" id="username" value="" />
                        <input type="hidden" name="show_user_pwd" class="form-control" id="show_user_pwd" value="" />
                    </div>
                </div>
            </div>
            <div class="button-group-footer final-step">
                <span class="btn-back-dashboard">
                    <a href="/school-classes" role="button" class="btn btn-primary btn-back-to-dashboard"><span class="glyphicon glyphicon-chevron-left"></span> Previous Step</a>
                </span>
                <span class="btn-back-dashboard">
                    <a href="/school-dashboard" role="button" class="btn btn-primary btn-back-to-dashboard"> Finish
                        Setup <span class="glyphicon glyphicon-ok-2"></span></a>
                </span>
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
 * TRANSFER STUDENT MODAL
 * ------------------------------ */
?>
<div class="modal fade" id="transfer-student-modal" tabindex="-1" role="dialog" aria-labelledby="ulc-label-transfer-student" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="ulc-label-transfer-student">
                    Transfer Student
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
                        <label>Search for students to transfer to this Class:</label>
                    </div>
                    <div class="row transfer-search-input-wrap">
                        <label class="sr-only" for="transfer-student-input">Student name, Username or class name</label>
                        <input type="text" id="transfer-student-input" class="form-control search-students" placeholder="Enter a Student name, wushka username, or class name..." />
                        <button id="clear-search-input" class="btn btn-default btn-small" title="Reset Search">
                            Clear
                        </button>

                    </div>
                    <div class="row">
                        <label>Search Results</label>
                        <div id="transfer-student-search-results"></div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="modal-footer">
                <div class="col-sm-6 col-sm-offset-3">
                    <button id="submit-student-transfer" type="button" class="btn btn-primary btn-block">
                        Transfer Student
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
/* ------------------------------
 * MANAGE CLASS MODAL
 * ------------------------------ */
?>
<div class="modal fade" id="manage-class-settings" tabindex="-1" role="dialog" aria-labelledby="ulc-label-manage-class" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="ulc-label-manage-class">
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
                        <?php /* ----- Upload Student List Button ----- */ ?>
                        <div class="col-xs-4">
                            <span class="empty help-popover" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Upload Class List" data-content="Activate the Class List Upload wizard to easily import your whole class.">
                                <button type="button" class="btn btn-default btn-block btn-upload-class-list" data-toggle="modal" data-target="#upload-dialog" data-dismiss="modal">
                                    <span class="sr-only">Upload</span>
                                    <span class="x2 glyphicon glyphicon-upload"></span>
                                </button>
                            </span>
                            <label class="settings-label">Upload Class List</label>
                        </div>
                        <?php /* ----- Set Class Password Button ----- */ ?>
                        <div class="col-xs-4">
                            <span class="empty help-popover pass-popover" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Set Class Password" data-content="Set a password for your whole class.">
                                <button type="button" class="btn btn-default btn-block btn-set-class-password" data-toggle="modal" data-target="#class-password-dialog" data-dismiss="modal">
                                    <span class="sr-only">Set Class Password</span>
                                    <span class="x2 glyphicon glyphicon-user-lock"></span>
                                </button>
                            </span>
                            <label class="settings-label">Set Class Password</label>
                        </div>
                        <?php /* ----- Create Student Login Letter Button ----- */ ?>
                        <?php if($isQRDisabled){ ?>
                        <div class="col-xs-4">
                            <span class="empty help-popover" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Generate Student Login Letters" data-content="Create student login letters PDF">
                                <?php // Was: Generate PDF download of Student Login letters for parents. 
                                ?>
                                <button type="button" class="btn btn-default btn-block btn-generate-student-login btn-stamp school-students" id="stamp-students">
                                    <span class="sr-only">Generate Student Letters</span>
                                    <span class="x2 glyphicon glyphicon-envelope"></span>
                                </button>
                            </span>
                            <label class="settings-label">Generate Student Letters</label>
                        </div>
                        <?php } ?>
                        <?php if(!$isQRDisabled){ ?>
                        <div class="col-xs-4">
                            <span class="empty help-popover" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Generate Student QR" data-content="Create student login letters PDF">
                                <?php // Was: Generate PDF download of Student Login letters for parents. 
                                ?>
                                <button type="button" class="btn btn-default btn-block btn-generate-student-login btn-stamp school-students" id="stamp-students-qr">
                                    <span class="sr-only">Generate Student QR</span>
                                    <span class="x2 glyphicon glyphicon-qrcode"></span>
                                </button>
                            </span>
                            <label class="settings-label">Generate Student QR</label>
                        </div>
                        <?php } ?>

                    </div>
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
            <!-- div class="modal-footer"> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> </div -->
        </div>
    </div>
</div>
<?php
/* ------------------------------
 * UPLOAD CLASS LIST MODAL
 * ------------------------------ */
?>
<div class="modal fade" id="upload-dialog" tabindex="-1" role="dialog" aria-labelledby="ulc-label-add-class" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-xl" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="ulc-label-add-class">Add Whole Class List in 4
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
                            <label for="filename" class="sr-only">Upload CSV</label>
                            <input type="file" id="filename" name="filename" />
                        </div>
                    </div>
                    <div class="step three">
                        <div class="icon">
                            <img class="img-responsive" src="<?php echo CDN_URL ?>/Resources/step3.png" alt="Step 3">
                        </div>
                        <div class="instruction">
                            <p>Select the class you wish to upload these students to:</p>
                            <?php
                            $a_select = array();
                            foreach ($a_results['data']['classes'] as $idx => $a_class) {
                                if ($a_class['deleted'] == TRUE) {
                                    continue;
                                }
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
                            <label for="upload-class-select" class="sr-only">Select Class</label>
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
                    <label for="new-class-password" class="sr-only">New Password</label>
                    <input class="form-control class-password" id="new-class-password" type="text" value="" placeholder="New Password" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings">Cancel
                </button>
                <button class="btn btn-primary btn-set-password" id="set-password" data-id="" type="button">Set Password
                </button>
            </div>
        </div>
    </div>
</div>

<?php /* - Archive Single Student Modal */ ?>
<div class="modal fade" id="archive-student-modal" tabindex="-1" role="dialog" aria-labelledby="cn-label-archive-student" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="cn-label-archive-student">Archive Student</h3>
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

<?php /* - Archive Class Modal */ ?>

<div class="modal fade" id="confirm-archive-class" tabindex="-1" role="dialog" aria-labelledby="cn-label-archive-class" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="cn-label-archive-class">Archive Class</h3>
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


<?php /* No Classes Alert Modal */ ?>

<div class="modal fade" id="no-classes" tabindex="-1" role="dialog" aria-labelledby="scp-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <p>It appears you haven't created any classes yet.</p>
                <p>To be able set up your students you will need to go to Step 3 and create a new class.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#manage-class-settings">Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<div id="scrolltotop">
    <a href="#" aria-label="Scroll to top"><i class="icon-chevron-up"></i></a>
</div>
<!--General Scripts-->
<script>
    //Get Currently Selected Table ID
    function get_table_id() {
        var o_active = jQuery('.class-list.class-switch.active');
        if (o_active.length > 0) {
            return o_active.attr('data-class');
        }

        return false;
    }

    jQuery(document).ready(function($) {
        //Page Load Initialise Functions
        initiate_first_table();
        tooltips();

        //Class Filter Selection
        $('.active-group').find('.btn.btn-class').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            if ($(this).hasClass('selected')) {
                return false;
            }

            $('.btn.btn-class').removeClass('selected');
            $(this).addClass('selected');

            var s_filter = $(this).attr('value').trim();
            if (s_filter.length > 0) {
                $('.list-group-item.class-list').css('display', 'none');
                $('.list-group-item.class-list[data-active="' + s_filter + '"]').css('display', 'block');
            }

            return true;
        });

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
            //3. Edit Password Field
            $(".email").editable({
                emptytext: 'Not set',
                mode: 'inline',
                success: function(response, value) {
                    var id = $(this).closest('tr').attr('id').replace('user-', '');
                    var meta = $(this).attr('class').split(' ')[0];
                    edit_user_data(id, meta, value);
                    //update_user_property(id, meta, value);
                }
            });
        }


        /* ----------------------------------*/
        $('.btn[data-target="#manage-class-settings"]').on('click', function() {
            $('#set-password').text('Set Password');
            $('#new-class-password').val('');
        });

        // ----- CLICK EVENTS ----- \\
        //1. Set Whole CLASS Password Field
        $('#set-password').on('click', function() {
            if ($(this).hasClass('active')) {
                return false;
            }

            $(this).addClass('active');

            var s_value = $('#new-class-password').val();
            if (s_value.length <= 0) {
                console.log('Cant Save blank password');
                return false;
            }

            $(this).text('Saving...');

            edit_user_data(get_table_id(), 'classPass', s_value);
            update_user_property('all', 'user_pass', s_value);
        });

        //2. Archive Whole CLASS Users
        $('.btn-archive-class-confirm').on('click', function() {
            edit_user_data(get_table_id(), 'archiveAll', 0);
            toggle_user_archive_json('all', true);
        });


        //----- CHANGE CLASS EVENT -----\\
        $('.list-group-item.class-list').on('click', function() {
            var i_class = $(this).attr('data-class').trim();
            switch_class_tables(i_class);
        });

        //----- SWITCH CLASS/ARCHIVE TOGGLE -----\\
        $(document).on('click', '.switch-table', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var i_class = get_table_id();
            var panel_header = $('.panel-class-lists').find('.panel-heading').find('.panel-title');
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
                a_btn.push('<div class="col-xs-6 col-sm-6">');
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

        if ($('div[role="tabpanel"] .tab-content').hasClass('no-classes')) {
            $('#no-classes').modal('toggle');
        }

        //----- Select Transfer Student -----\\
        $('#transfer-student-search-results').on('click', 'div[id^="transfer-result-"]', function() {
            if ($(this).hasClass('selected')) {
                $('div[id^="transfer-result-"]').removeClass('selected');
                $('#submit-student-transfer').addClass('disabled');
                return true;
            }

            $('#submit-student-transfer').removeClass('disabled');
            $('div[id^="transfer-result-"]').removeClass('selected');
            $(this).addClass('selected');
        });

        //----- Reset Transfer Student List ------\\
        $('#clear-search-input').on('click', reset_transfer_search);
        $('.btn.btn-transfer-modal.btn-transfer-student').on('click', reset_transfer_search);

        function reset_transfer_search(e) {
            $('#transfer-student-input').val('');
            var eResultTable = $('#transfer-student-search-results');
            eResultTable.empty();
            $('#submit-student-transfer').addClass('disabled');
        }

        //----- Transfer Student Search -----\\
        var searchInputTimer = null;
        $('#transfer-student-input').on('keyup', function() {
            var newInput = $(this).val().trim().toLowerCase();
            $('#submit-student-transfer').addClass('disabled');

            //Reset timer until pause in typing
            clearTimeout(searchInputTimer);
            searchInputTimer = setTimeout(function() {
                searchKids(newInput, function(result) {
                    var eResultTable = $('#transfer-student-search-results');
                    var sRows = '';
                    if (result.length <= 0) {
                        sRows = '<em>No Students Found</em>';
                    } else {
                        var sHeader = '';
                        sHeader += '<div class="transfer-search-result-header col-xs-12">';
                        sHeader += '<div class="col-xs-3">First Name</div>';
                        sHeader += '<div class="col-xs-3">Last Name</div>';
                        sHeader += '<div class="col-xs-3">Username</div>';
                        sHeader += '<div class="col-xs-3">Class Name</div>';
                        sHeader += '</div>';
                        sRows += sHeader;

                        $.each(result, function(idx, oMatch) {
                            var sKid = '';
                            sKid += '<div id="transfer-result-' + oMatch.id_hash +
                                '" data-class="' + oMatch.classId +
                                '" class="col-xs-12">';
                            sKid +=
                                '<div class="transfer-fname result-cell col-xs-3">' +
                                oMatch.first_name + '</div>';
                            sKid +=
                                '<div class="transfer-lname result-cell col-xs-3">' +
                                oMatch.last_name + '</div>';
                            sKid +=
                                '<div class="transfer-uname result-cell col-xs-3">' +
                                oMatch.username + '</div>';
                            sKid +=
                                '<div class="transfer-cname result-cell col-xs-3">' +
                                oMatch.className + '</div>';
                            sKid += '</div>';
                            sRows += sKid;
                        });
                    }

                    eResultTable.empty().append(sRows);
                });
            }, 600);
        });

        //----- Start Student Transfer -----\\
        $('#submit-student-transfer').on('click', function() {
            //Get Current Class Id
            var newClassId = get_table_id();
            //Get Selected Student
            var eStudent = $('div[id^="transfer-result-"].selected');
            if (typeof eStudent == 'undefined' || eStudent === null || eStudent.length <= 0) {
                console.log('Error: Cannot Transfer Null Student');
                return false;
            }

            var id_hash = eStudent.attr('id').replace('transfer-result-', '').trim();
            var prevClassId = eStudent.attr('data-class');

            $(this).addClass('disabled');

            eStudent.find('.transfer-cname').empty().append('Updating...');
            transfer_student(id_hash, newClassId, prevClassId);
        });

        // ---------------------------------- \\
        // ------ BUILD TABLE FUNCTIONS ----- \\
        // ---------------------------------- \\

        function build_table(i_class, s_type) {

            var b_archived = s_type != 'class';

            var s_tags = 'display table table-bordered table-condensed table-hover class-table';
            var a_table = [];
            var a_users = o_classes[i_class]['users'][s_type];

            var s_name = o_classes[i_class]['class']['name'];
            $('.panel-heading').find('.class-name').empty().append(s_name);

            a_table.push('<div role="tabpanel" id="' + i_class + '-' + s_type + '" data-class="' + i_class +
                '" class="tab-pane fade in active">');
            a_table.push('<div class="table-responsive">');
            a_table.push('<table data-class="' + i_class + '" data-type="' + s_type + '" class="' + s_tags + '">');
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
                //Access assitant fix
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
            a_head.push('<tr class="class-view-table-heading">');
            a_head.push(get_header_rows(b_archive).join(''));
            a_head.push('</tr>');
            a_head.push('</thead>');

            return a_head;
        }

        function get_table_footer(b_archive) {
            var a_foot = [];
            a_foot.push('<tfoot>');
            a_foot.push('<tr class="class-view-table-heading">');
            a_foot.push(get_header_rows(b_archive, true).join(''));
            a_foot.push('</tr>');
            a_foot.push('</tfoot>');

            return a_foot;
        }

        function get_header_rows(b_archive, bFoot) {
            var a_rows = [];
            var iRow = 0;

            var scBox = !bFoot ? '<input type="checkbox" class="select-all" aria-label="Select All" />' :
                '<label class="sr-only">Select Fields</label>';
            a_rows.push('<th class="class-view-col-0"><span class="sr-only">Select All</span>' + scBox + '</th>');
            a_rows.push('<th class="class-view-col-1">First Name</th>');
            a_rows.push('<th class="class-view-col-2">Surname</th>');
            a_rows.push('<th class="class-view-col-3">Username</th>');
            a_rows.push('<th class="class-view-col-3">Email</th>');
            a_rows.push('<th class="class-view-col-4">Password</th>');
            a_rows.push(b_archive ? '<th class="class-view-col-5">Archive</th>' : null);
            if(!isQRDisabled){
              a_rows.push('<th class="class-view-col-6">Regenerate QR</th>');
            }

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

            a_row.push(
                '<td><span class="change_class"><input type="checkbox" class="change-class" aria-label="Select ' +
                o_user.username + '"/></span></td>');
            a_row.push('<td><button class="first_name">' + o_user.first_name + '</button></td>');
            a_row.push('<td><button class="last_name">' + o_user.last_name + '</button></td>');
            a_row.push('<td><span class="username">' + o_user.username + '</span></td>');
            a_row.push('<td><span class="email">' + o_user.email + '</span></td>');
            a_row.push('<td><button class="user_pass">' + o_user.user_pass + '</button></td>');
            if (b_archived) {
                a_row.push(
                    '<td><button class="btn btn-default btn-student-unarchive" type="button">Unarchive</button></td>'
                );
            }
            if(!isQRDisabled){
             a_row.push('<td><button class="user_qr school-students btn btn-small btn-default" data-id="' + o_user.id_hash + '">Regenerate</button></td>');
            }
            return a_row;
        }

        // -------------------------------------------- //
        //              GENERAL FUNCTIONS               //
        // -------------------------------------------- //

        //Builds First table on page load
        function initiate_first_table() {
            build_table(o_active.id, o_active.type);

            console.log("CLASES------------>");
            console.log(o_classes);
            //Determine if selected on page load class is live/deleted, display that list
            var sDeleted = o_classes[o_active.id]['deleted'];
            var sFilter = sDeleted === true ? 'deleted' : 'active';

            //Set correct filter button is active
            $('.btn.btn-class').removeClass('selected');
            $('.btn.btn-class[value="' + sFilter + '"]').addClass('selected');
            //Hide Correct filter list
            $('.list-group-item.class-list').css('display', 'none');
            $('.list-group-item.class-list[data-active="' + sFilter + '"]').css('display', 'block');
        }

        //Loads jQuery DataTables Library on Class Table
        function initiate_datatables() {
            var a_tableArgs = {
                'paging': false,
                'iDisplayLength': 35,
                'info': true,
                "order": [
                    [2, 'asc']
                ]
            };

            //Set table length for class page
            $('table.class-table').DataTable(a_tableArgs);
        }



        //Show Tooltips
        function tooltips() {
            $('[data-toggle="tooltip"]').tooltip();
            $('[data-toggle="popover"]').popover();
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

        //Prompts Message If Error Occurs Uploading Class List
        function upload_class_error(s_error) {
            var o_form = $("#studentForm");
            o_form.next().html(s_error + '<br/>').fadeIn(1000).delay(5000).fadeOut(1000);
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
                    $('<span>').addClass('change_class').append(
                        $('<input>').attr('type', 'checkbox').addClass('change-class')
                    )
                )
            ).append(
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
            );
            
            if(!isQRDisabled){
                s_row =  s_row.append(
                    $('<td>').append(
                        $('<button>').addClass('user_qr school-students btn btn-small btn-default').attr('data-id', o_user.id_hash).append(
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

            $('a.list-group-item.class-switch').removeClass('active');
            $('a.list-group-item[data-class="' + i_class + '"]').addClass('active');

            //Change Upload Class List Modal Selector to highlight current class
            $('#upload-dialog').find('#upload-class-select').val(i_class);

            //Build New Table
            build_table(i_class, 'class');
        }

        //Add New User data to JSON object
        function add_user_to_json(i_class, o_user) {
            if (typeof o_classes[i_class].users.class == 'undefined') {
                o_classes[i_class].users.class = [];
            }

            o_classes[i_class].users.class.push(o_user);
        }

        //Update existing user in JSON object
        function update_user_property(i_hash, s_key, x_value) {
            var i_class = get_table_id();

            var o_users = o_classes[i_class].users.class;
            $.each(o_users, function(idx, o_user) {
                if (i_hash == 'all' || i_hash == o_user.id_hash) {
                    o_classes[i_class].users.class[idx][s_key] = x_value;
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

        //----- Search Students -----\\
        function searchKids(searchInput, callback) {
            if (searchInput == null || searchInput.length <= 0) {
                return callback([]);
            }

            var aResults = [];

            $.each(o_classes, function(idx, o_class) {
                if (o_class.deleted !== true) {
                    var classStudents = o_class.users.class;
                    var className = o_class.class.name.toLowerCase();
                    var classId = o_class.class.id;
                    $.each(classStudents, function(idx, o_student) {
                        //Check First Name
                        if (
                            o_student.first_name.toLowerCase().indexOf(searchInput) > -1 ||
                            o_student.last_name.toLowerCase().indexOf(searchInput) > -1 ||
                            o_student.username.toLowerCase().indexOf(searchInput) > -1 ||
                            className.indexOf(searchInput) > -1
                        ) {
                            var potential = o_student;
                            potential.className = className;
                            potential.classId = classId;
                            aResults.push(potential);
                        }
                    });
                }
            });

            return callback(aResults);
        }

        // ----------------------------------------- //
        //              AJAX FUNCTIONS               //
        // ----------------------------------------- //

        //Edit Meta Field on User
        function edit_user_data(id, meta, value) {
            $.ajax({
                url: s_template_url + '/edit-user-data.php',
                type: "POST",
                //dataType: 'json',
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
                    if (meta == 'active' || meta == 'classPass' || meta == 'all_level' ||
                        meta == 'archiveAll' || meta == 'allquiz' || meta == 'allnarration' || meta ==
                        'all_setting') {
                        build_table(class_id, type);
                    }
                    if (meta == 'classPass') {
                        $('#set-password').text('Saved!');
                        $('#class-password-dialog').modal('toggle');
                    }
                    if (meta == 'email') {
                        let return_obj = JSON.parse(a_return);
                        if (return_obj.type == 'error') {
                            alert(return_obj.message);
                            build_table(class_id, type);
                        }
                        if (return_obj.type == 'success') {
                            update_user_property(id, meta, value);
                        }
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                    console.log(thrownError);
                    alert(xhr.responseText);
                },
                complete: function() {
                    if (meta == 'classPass') {
                        $('#set-password').removeClass('active');
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
                dataType: 'json',
                data: {
                    'teacher_id': JSON.stringify(i_hash),
                    'first_name': JSON.stringify(s_first),
                    'last_name': JSON.stringify(s_last),
                    's_email': JSON.stringify(s_email),
                    'username': JSON.stringify(s_user),
                    'class': JSON.stringify(i_class)
                },
                success: function(o_return) {

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
                        }
                        if (s_error.length > 0) {
                            upload_class_error(s_error);
                            return false;
                        }

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

        /* ----- On Class Selection, enable move students button ----- */
        //If One or more students are checked AND a class selected
        //Enable Move Students button
        $(document).on('click', '#change-class-select, input.change-class, #change-class-select option',
            function() {
                console.log('Check Move Button Status');
                return toggle_move_students_button();
            });

        function toggle_move_students_button() {
            var e_btn = $('.btn.btn-change-class');
            if (is_move_students_enabled()) {
                e_btn.removeClass('disabled');
            } else {
                e_btn.addClass('disabled');
            }

            return true;
        }

        function is_move_students_enabled() {
            var e_select = $(document).find('#change-class-select').find(':selected');
            var e_table = $('table[data-class="' + get_table_id() + '"]');
            console.log('Is Move Students Button Enabled?');

            var iClass = get_table_id();
            console.log(e_select);
            console.log(e_table);
            console.log('Current Class: ' + iClass);
            console.log('Any Checked Kids?');
            //Any Students Checked?
            if (e_table.find('.change-class:checked').length > 0) {
                console.log('Class has checked kids.');
                console.log('Has Selection been made?');
                //Class Selected?
                var sClass = e_select.val();
                console.log('Value of Selection: ' + sClass);
                if (typeof sClass !== 'undefined' && sClass !== null && sClass.length > 0) {
                    console.log('Value good; Is Class different?');
                    if (sClass !== iClass) {
                        console.log('Yes; Movement Approved');
                        return true;
                    }
                }
            }

            console.log('No; Movement Rejected');
            return false;
        }

        /* ----- Select All Checkbox For Student Table ----- */
        $(document).on('click', '.select-all', function(e) {
            e.stopPropagation();

            //Toggle student Cboxes
            var checkBoxes = $('input.change-class');
            checkBoxes.prop("checked", !checkBoxes.prop("checked"));
            //Check Move Status
            return toggle_move_students_button();
        });

        /* ----- Move Selected Students to New Class ----- */
        $('.btn.btn-change-class').on('click', function() {
            if (!is_move_students_enabled()) {
                return false;
            }

            var e_btn = $(this);
            var i_table = get_table_id();
            var e_table = $('table[data-class="' + get_table_id() + '"]');
            var b_type = e_table.attr('data-type');

            var i_class = $('#change-class-select').val();
            var a_students = [];

            e_table.find('input.change-class:checked').map(function() {
                var e_this = $(this);
                var i_student = e_this.parents('tr').attr('id').replace('user-', '').trim();
                a_students.push(i_student);
            });

            //                console.log('-----Move Students-----');
            //                console.log('Current Class: ' + i_table);
            //                console.log('New Class: ' + i_class);
            //                console.log('Students to move:');
            //                console.log(a_students);
            $.ajax({
                type: "POST",
                url: "<?php echo esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")); ?>",
                dataType: 'json',
                data: {
                    action: "wushka_school_move_students",
                    i_table: JSON.stringify(i_table),
                    i_class: JSON.stringify(i_class),
                    a_students: JSON.stringify(a_students),
                    s_auth: JSON.stringify('<?php echo $s_auth; ?>')
                },
                beforeSend: function() {
                    e_btn.empty().append('Moving...');
                },
                error: function(e1, e2, e3) {
                    e_btn.empty().append('Error!');
                    alert(e1.responseText);
                },
                success: function(a_return) {
                    e_btn.empty().append('Moved!');

                    if (typeof a_return !== 'undefined') {
                        if (typeof a_return.status !== 'undefined' && a_return.status == 1) {

                            move_students(i_table, i_class, b_type, a_students, function(
                                bDone) {
                                if (!bDone) return;
                                build_table(i_table, b_type);
                            });

                        }
                    }
                },
                complete: function() {
                    $('#change-class-select').val(null);
                    e_btn.fadeTo(600, 1, function() {
                        e_btn.fadeTo(200, 0, function() {
                            toggle_move_students_button();
                            e_btn.empty().append('Move Students');
                            e_btn.blur();
                            e_btn.fadeTo(200, 0.65, function() {
                                e_btn.attr('style', function(i, style) {
                                    return style.replace(
                                        /opacity[^;]+;?/g,
                                        '');
                                });
                            });

                        });
                    });
                }
            });

            return true;
        });

        function transfer_student(user_id, newClassId, prevClassId) {
            var a_students = [user_id];
            $.ajax({
                type: "POST",
                url: "<?php echo esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")); ?>",
                dataType: 'json',
                data: {
                    action: "wushka_school_move_students",
                    i_table: JSON.stringify(prevClassId),
                    i_class: JSON.stringify(newClassId),
                    a_students: JSON.stringify(a_students),
                    s_auth: JSON.stringify('<?php echo $s_auth; ?>')
                },
                error: function(e1, e2, e3) {
                    $('[id^="transfer-result-' + user_id + '"]').find('.transfer-cname').empty().append(
                        'ERROR');
                    $('.transfer-search-input-wrap').after(notificationDisplay(e1.responseText, 'danger row'));
                    autoCloseNotification();
                },
                success: function(a_return) {
                    if (typeof a_return !== 'undefined') {
                        if (typeof a_return.status !== 'undefined' && a_return.status == 1) {

                            var className = o_classes[newClassId].class.name;
                            $('[id^="transfer-result-' + user_id + '"]').find('.transfer-cname').empty()
                                .append(className);

                            move_students(prevClassId, newClassId, 'class', a_students, function(
                                bDone) {
                                if (!bDone) return;
                                build_table(newClassId, 'class');
                            });

                        }
                    }
                }
            });
        }

        //Move Students to New Class Table
        function move_students(i_oldClass, i_newClass, b_archived, a_students, callback) {
            var a_users = o_classes[i_oldClass]['users'][b_archived];
            var a_revised = [];

            $.each(a_users, function(idx, o_user) {
                //Is current User in "moved" students array?
                if (a_students.indexOf(o_user.id_hash) >= 0) {
                    //Move this user to new class
                    add_user_to_json(i_newClass, o_user);
                } else {
                    //Rebuild old class users array without moved students
                    a_revised.push(o_user);
                }
            });

            //Push revised user array into old Class
            o_classes[i_oldClass]['users'][b_archived] = a_revised;

            return callback(true);
        }

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

    });
</script>
<!--END SS PAGE SCRIPTS-->
<?php
include 'dashboard_options.php';
get_footer();

/* ----- EOF ----- */
