<?php
$disable = false;
if(isset($_SESSION['wushka_decodable_teacher']) && $_SESSION['wushka_decodable_teacher']){
    $disable = $_SESSION['wushka_decodable_teacher'];
    
}

// determine user type - set default to false
$teacher = false;
$school = false;
$student = false;
$parent = false;
// student and parent determination are fairly straight forward
if (is_user_logged_in() && user_can($current_user, "student")) {
    $student = true;
}
if (is_user_logged_in() && user_can($current_user, "parent") && isset($_SESSION['parent_login']) && $_SESSION['parent_login'] == "true") {
    $parent = true;
}
// school and teacher can have dual roles
if (is_user_logged_in() && (user_can($current_user, "teacher") || user_can($current_user, "school"))) {
    if (isset($_SESSION['dashboard_selection']) && user_can($current_user, $_SESSION['dashboard_selection'])) {
        if ($_SESSION['dashboard_selection'] === 'teacher') {
            $teacher = true;
        }
        if ($_SESSION['dashboard_selection'] === 'school') {
            $school = true;
        }
    } else {
        if (user_can($current_user, "teacher")) {
            $teacher = true;
        }
        if (user_can($current_user, "school")) {
            $school = true;
        }
    }
}
//error_log('teacher:' . (int)$teacher . ', school:' . (int)$school . ', student:' . (int)$student . ', parent:' . (int)$parent);
if ($teacher) { ?>
<?php
    $b_new_user = FALSE;
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_user']) && $_POST['new_user'] == 'new_teacher') {
        //Teacher is Entering Dashboard for First Time,
        //Display Tutorial Popups
        $b_new_user = TRUE;
        //Add Script for First time Content
        ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('.new-user').popover('toggle');
    $('#first-time-user-modal').show().fadeTo(250, 1);
    $('#first-time-user-modal').on('click', 'button[data-dismiss="modal"]', function() {
        $('#first-time-user-modal').fadeTo(250, 0, function() {
            $('#first-time-user-modal').hide();
        });
    });
});
</script>
<?php //Add CSS for First Time Content ?>
<style>
#child-list .parent-function.wrapper {
    border: solid 5px #FFCB00;
    border-radius: 10px;
}

#first-time-user-modal {
    overflow-y: hidden;
}

div.popover {
    width: 2500px;
    color: #444;
    font-size: 1.5rem;
}

div.popover h3 {
    font-weight: 600;
    font-size: 1.6rem;
}
</style>
<div class="modal fade in active" id="first-time-user-modal" tabindex="-1" role="dialog" aria-labelledby="scp-label"
    aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="scp-label">Welcome to Wushka!</h3>
            </div>
            <div class="modal-body">
                <p>This is your Dashboard, the place where you can access all Wushka features. You will always have
                    access to your Dashboard by clicking on the My Dashboard icon at the bottom of your page.</p>
                <p>First you will need to click on Manage Class List to set up the students within your class.</p>
                <p>After you have set up your students you will be able to set up your Reading Groups by clicking on the
                    Reading Groups icon at the bottom of your page.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<nav class="navbar navbar-fixed-bottom dash-bottom dash-teacher" aria-label="Bottom Nav">
    <!-- div class="container" -->
    <ul class="nav navbar-nav">
        <li><a class="nav-item nav-btn-teacher-dashboard <?php
                if (is_page('teacher-dashboard')) {
                    echo 'active';
                }
                ?>" href="<?php echo home_url(); ?>"> <span class="glyphicon glyphicon-dashboard nav-item-icon"></span>
                <span class="nav-item-text">My Dashboard</span> </a></li>
        <?php if ($b_new_user === TRUE) { ?>
        <li>
            <span class="empty help-popover new-user" data-toggle="popover" data-placement="top" data-trigger="hover"
                title="Manage Class List"
                data-content="Setup your class by clicking on the Class List button at any time">
                <a class="nav-item nav-btn-manage-child-list <?= ($disable)? 'disabled-footer-icon-link': ''; ?> <?php echo 'active'; ?>"
                    href="<?php echo home_url(); ?>/manage-class-list/"> <span
                        class="glyphicon glyphicon-group nav-item-icon"></span> <span class="nav-item-text">Class
                        List</span> </a>
            </span>
        </li>
        <?php } else { ?>
        <li><a class="nav-item nav-btn-manage-child-list <?= ($disable)? 'disabled-footer-icon-link': ''; ?> <?php
                    if (is_page('manage-class-list')) {
                        echo 'active';
                    }
                    ?>" href="<?php echo home_url(); ?>/manage-class-list/"> <span
                    class="glyphicon glyphicon-group nav-item-icon"></span> <span class="nav-item-text">Class
                    List</span> </a></li>
        <?php } ?>

        <li><a class="nav-item nav-btn-manage-reading-groups <?= ($disable)? 'disabled-footer-icon-link': ''; ?> <?php
                if (is_page('manage-reading-groups')) {
                    echo 'active';
                }
                ?>" href="<?php echo home_url(); ?>/manage-reading-groups/"> <span
                    class="glyphicon glyphicon-book-open nav-item-icon"></span> <span class="nav-item-text">Reading
                    Groups</span> </a></li>
        <li><a class="nav-item nav-btn-manage-students <?= ($disable)? 'disabled-footer-icon-link': ''; ?> <?php
                if (is_page('class-login')) {
                    echo 'active';
                }
                ?>" href="<?php echo home_url(); ?>/class-login"> <span
                    class="glyphicon glyphicon-keys nav-item-icon"></span> <span class="nav-item-text">Student
                    Login</span> </a></li>
        <li><a class="nav-item nav-btn-student-info <?= ($disable)? 'disabled-footer-icon-link': ''; ?> <?php
                if (is_page('class-statistics')) {
                    echo 'active';
                }
                ?>" href="<?php echo home_url(); ?>/class-statistics/"> <span
                    class="glyphicon glyphicon-pie-chart nav-item-icon"></span> <span class="nav-item-text">Class
                    Statistics</span> </a></li>
        <li><a class="nav-item nav-btn-student-statistics <?= ($disable)? 'disabled-footer-icon-link': ''; ?> <?php
                if (is_page('student-statistics')) {
                    echo 'active';
                }
                ?>" href="<?php echo home_url(); ?>/student-statistics/"> <span
                    class="glyphicon glyphicon-charts nav-item-icon"></span> <span class="nav-item-text">Student
                    Statistics</span> </a></li>
        <li><a class="nav-item nav-btn-my-bookmarks <?php 
                if ( is_page('my-bookmarks') ) { 
                    echo 'active';
                }
                ?>" href="<?php echo home_url(); ?>/my-bookmarks"> <span
                    class="glyphicon glyphicon-bookmark nav-item-icon"></span> <span class="nav-item-text">My
                    Bookmarks</span> </a></li>
        <li><a class="nav-item nav-btn-library <?= ($disable)? 'disabled-footer-icon-link': ''; ?> <?php
                if (is_page('levelled')) {
                    echo 'active';
                }
                ?>" href="<?php echo home_url(); ?>/levelled"> <span
                    class="glyphicon glyphicon-inbox nav-item-icon"></span> <span class="nav-item-text">Levelled
                    Library</span> </a></li>
        <li><a class="nav-item nav-btn-library <?php
                if (is_page('decodable')) {
                    echo 'active';
                }
                ?>" href="<?php echo home_url(); ?>/decodable"> <span
                    class="glyphicon glyphicon-inbox nav-item-icon"></span> <span class="nav-item-text">Decodable
                    Library</span> </a></li>
        <li><a class="nav-item nav-btn-records <?= ($disable)? 'disabled-footer-icon-link': ''; ?> <?php
                if (is_page('reader-records')) {
                    echo 'active';
                }
                ?>" href="<?php echo home_url(); ?>/reader-records/"> <span
                    class="glyphicon glyphicon-list-alt nav-item-icon"></span> <span class="nav-item-text">Reader
                    Records</span> </a></li>
        <!-- li><a class="nav-item my-statistics" href="#"> <span class="nav-item-icon"></span> <span class="nav-item-text">Stories</span> </a></li -->
    </ul>
    <!-- /div -->
</nav>
<?php } elseif ($student) { ?>
<nav class="navbar navbar-fixed-bottom dash-bottom dash-student" aria-label="Bottom Nav">
    <!-- div class="container" -->
    <ul class="nav navbar-nav">
        <?php if(hasLevelledAccess()){ ?>

        <li><a class="nav-item my-bookshelves <?php
                if (is_page('levelled')) {
                    echo 'active';
                }
                ?>" href="<?php echo home_url(); ?>/levelled"><span
                    class="glyphicon glyphicon-inbox nav-item-icon"></span><span class="nav-item-text">Levelled
                    Library</span></a></li>


        <?php }?>
        <?php if(hasDecodableAccess()){ ?>
        <li><a class="nav-item my-bookshelves <?php
                    if (is_page('decodable')) {
                        echo 'active';
                    }
                    ?>" href="<?php echo home_url(); ?>/decodable"><span
                    class="glyphicon glyphicon-inbox nav-item-icon"></span><span class="nav-item-text">Decodable
                    Library</span></a></li>
        <?php }?>

        <li><a class="nav-item my-page <?php
                if (is_page('my-page')) {
                    echo 'active';
                }
                ?>" href="<?php echo home_url() . '/my-page/'; ?>"><span
                    class="glyphicon glyphicon-address-book nav-item-icon"></span><span class="nav-item-text">My
                    Page</span></a></li>
        <li><a class="nav-item my-quizzes <?php
                if (is_page('quiz-results')) {
                    echo 'active';
                }
                ?>" href="/quiz-results/"><span class="glyphicon glyphicon-conversation nav-item-icon"></span><span
                    class="nav-item-text">Online Quizzes</span></a></li>
        <li><a class="nav-item my-favourites <?php
                if (is_page('my-favourites')) {
                    echo 'active';
                }
                ?>" href="<?php echo home_url() . '/my-favourites/'; ?>"><span
                    class="glyphicon glyphicon-star nav-item-icon"></span><span
                    class="nav-item-text">Favourites</span></a></li>
    </ul>
    <!-- /div -->
</nav>
<?php } elseif ($parent) { ?>
<?php
    $b_new_user = FALSE;
    // added tru = false just so this never fires
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_user']) && $_POST['new_user'] == 'new_parent' && true === false) {
        //Parent is Entering Dashboard for First Time,
        //Display Tutorial Popups
        $b_new_user = TRUE;
        ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('.new-user').popover('toggle');
    $('#first-time-user-modal').show().fadeTo(250, 1);
    $('#first-time-user-modal').on('click', 'button[data-dismiss="modal"]', function() {
        $('#first-time-user-modal').fadeTo(250, 0, function() {
            $('#first-time-user-modal').hide();
        });
    });
});
</script>
<?php //Add CSS for First Time Content   ?>
<style>
#child-list .parent-function.wrapper {
    border: solid 5px #FFCB00;
    border-radius: 10px;
}

#first-time-user-modal {
    overflow-y: hidden;
}

div.popover {
    width: 2500px;
    color: #444;
    font-size: 1.5rem;
}

div.popover h3 {
    font-weight: 600;
    font-size: 1.6rem;
}
</style>
<div class="modal fade in active" id="first-time-user-modal" tabindex="-1" role="dialog" aria-labelledby="scp-label"
    aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="scp-label">Welcome to Wushka!</h3>
            </div>
            <div class="modal-body">
                <p>Go to the 'Manage Profiles' page, and create a personal account for your children. It is important to
                    complete this step to participate in the Wushka Reading Program fully.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<nav class="navbar navbar-fixed-bottom dash-bottom dash-parent" aria-label="Footer Navigation">
    <!-- div class="container" -->
    <ul class="nav navbar-nav">
        <li><a class="nav-item nav-btn-parent-dashboard <?php
                if (is_page('parent-dashboard')) {
                    echo 'active';
                }
                ?>" href="<?php echo home_url(); ?>"> <span class="glyphicon glyphicon-dashboard nav-item-icon"></span>
                <span class="nav-item-text">Dashboard</span> </a></li>
        <?php if ($b_new_user === TRUE) { ?>
        <li>
            <span class="empty help-popover new-user" data-toggle="popover" data-placement="top" data-trigger="hover"
                title="Manage Class List"
                data-content="Setup your Child's username here to get them reading books straight away!">
                <a class="nav-item nav-btn-manage-child-list <?php
                        if (is_page('manage-child-list') || $b_new_user == TRUE) {
                            echo 'active';
                        }
                        ?>" href="<?php echo home_url(); ?>/manage-child-list/"> <span
                        class="glyphicon glyphicon-group nav-item-icon"></span> <span
                        class="nav-item-text">Profiles</span> </a>
            </span>
        </li>
        <?php } else { ?>
        <li><a class="nav-item nav-btn-manage-child-list <?php
                    if (is_page('manage-child-list') || $b_new_user == TRUE) {
                        echo 'active';
                    }
                    ?>" href="<?php echo home_url(); ?>/manage-child-list/"> <span
                    class="glyphicon glyphicon-group nav-item-icon"></span> <span class="nav-item-text">Profiles</span>
            </a></li>
        <?php } ?>
        <li><a class="nav-item nav-btn-child-statistics <?php
                if (is_page('child-statistics')) {
                    echo 'active';
                }
                ?>" href="<?php echo home_url(); ?>/child-statistics/"> <span
                    class="glyphicon glyphicon-charts nav-item-icon"></span> <span class="nav-item-text">Child
                    Statistics</span> </a></li>

        <li><a class="nav-item nav-btn-child-library <?php
                if (is_page('library')) {
                    echo 'active';
                }
                ?>" href="<?php echo home_url(); ?>/library"> <span
                    class="glyphicon glyphicon-inbox nav-item-icon"></span> <span class="nav-item-text">Reading
                    Boxes</span> </a></li>
        <li><a class="nav-item nav-btn-parent-account<?php
                if (is_page('my-account')) {
                    echo 'active';
                }
                ?>" href="<?php echo home_url() . '/my-account/'; ?>"> <span
                    class="glyphicon glyphicon-list-alt nav-item-icon"></span> <span class="nav-item-text">My Home
                    Account</span> </a></li>

        <li><a class="nav-item nav-btn-quiz-results <?php
                if (is_page('quiz-results')) {
                    echo 'active';
                }
                ?>" href="quiz-results"> <span class="glyphicon glyphicon-lightbulb nav-item-icon"></span> <span
                    class="nav-item-text">Comprehension</span> </a></li>

        <!-- li><a class="nav-item my-statistics" href="#"> <span class="nav-item-icon"></span> <span class="nav-item-text">Stories</span> </a></li -->
    </ul>
    <!-- /div -->
</nav>
<?php } elseif ($school) { ?>
<nav class="navbar navbar-fixed-bottom dash-bottom dash-parent" aria-label="Footer Navigation">
    <ul class="nav navbar-nav">
        <li><a class="nav-item nav-btn-school-dashboard <?php
                if (is_page('school-dashboard')) {
                    echo 'active';
                }
                ?>" href="<?php echo esc_url(get_permalink(get_page_by_title('School Dashboard'))); ?>"> <span
                    class="glyphicon glyphicon-dashboard nav-item-icon"></span> <span
                    class="nav-item-text">Dashboard</span> </a></li>
        <li><a class="nav-item nav-btn-school-settings <?php
                if (is_page('school-settings')) {
                    echo 'active';
                }
                ?>" href="<?php echo esc_url(get_permalink(get_page_by_title('School Settings'))); ?>"> <span
                    class="glyphicon glyphicon-settings nav-item-icon"></span> <span class="nav-item-text">1
                    Settings</span> </a></li>
        <li><a class="nav-item nav-btn-school-teachers <?php
                if (is_page('school-teachers')) {
                    echo 'active';
                }
                ?>" href="<?php echo esc_url(get_permalink(get_page_by_title('School Teachers'))); ?>"> <span
                    class="glyphicon glyphicon-education nav-item-icon"></span> <span class="nav-item-text">2
                    Teachers</span> </a></li>
        <li><a class="nav-item nav-btn-school-classes <?php
                if (is_page('school-classes')) {
                    echo 'active';
                }
                ?>" href="<?php echo esc_url(get_permalink(get_page_by_title('School Classes'))); ?>"> <span
                    class="glyphicon glyphicon-group nav-item-icon"></span> <span class="nav-item-text">3 Classes</span>
            </a></li>
        <li><a class="nav-item nav-btn-school-students <?php
                if (is_page('school-students')) {
                    echo 'active';
                }
                ?>" href="<?php echo esc_url(get_permalink(get_page_by_title('School Students'))); ?>"> <span
                    class="glyphicon glyphicon-person nav-item-icon"></span> <span class="nav-item-text">4
                    Students</span> </a></li>

        <li><a class="nav-item nav-btn-school-dashboard-overview <?php
                if (is_page('school-dashboard-overview')) {
                    echo 'active';
                }
                ?>" href="<?php echo esc_url(get_permalink(get_page_by_title('School Overview'))); ?>"> <span
                    class="glyphicon glyphicon-stats nav-item-icon"></span> <span class="nav-item-text">Overview</span>
            </a></li>
        <li><a class="nav-item nav-btn-school-notifications <?php
                if (is_page('school-notifications')) {
                    echo 'active';
                }
                ?>" href="/school-notifications"> <span class="glyphicon glyphicon-bullhorn nav-item-icon"></span>
                <span class="nav-item-text">Notifications</span> </a></li>
    </ul>
</nav>

<?php } ?>

<?php
 if(is_user_logged_in() && user_can($current_user, OPEN_HOUSE_CUSTOMER)){?>

<nav class="navbar navbar-fixed-bottom dash-bottom dash-teacher" aria-label="Bottom Nav">
    <!-- div class="container" -->
    <ul class="nav navbar-nav">
        <li><a class="nav-item nav-btn-teacher-dashboard disabled-footer-icon-link" href="javascript:void(0);"> <span class="glyphicon glyphicon-dashboard nav-item-icon"></span>
                <span class="nav-item-text">My Dashboard</span> </a></li>
       
        <li><a class="nav-item nav-btn-manage-child-list disabled-footer-icon-link" href="javascript:void(0)"> <span
                    class="glyphicon glyphicon-group nav-item-icon"></span> <span class="nav-item-text">Class
                    List</span> </a></li>
        <li><a class="nav-item nav-btn-manage-reading-groups disabled-footer-icon-link" href="javascript:void(0)"> <span
                    class="glyphicon glyphicon-book-open nav-item-icon"></span> <span class="nav-item-text">Reading
                    Groups</span> </a></li>
        <li><a class="nav-item nav-btn-manage-students disabled-footer-icon-link" href="javascript:void(0)"> <span
                    class="glyphicon glyphicon-keys nav-item-icon"></span> <span class="nav-item-text">Student
                    Login</span> </a></li>
        <li><a class="nav-item nav-btn-student-info disabled-footer-icon-link" href="javascript:void(0)"> <span
                    class="glyphicon glyphicon-pie-chart nav-item-icon"></span> <span class="nav-item-text">Class
                    Statistics</span> </a></li>
        <li><a class="nav-item nav-btn-student-statistics disabled-footer-icon-link" href="javascript:void(0)"> <span
                    class="glyphicon glyphicon-charts nav-item-icon"></span> <span class="nav-item-text">Student
                    Statistics</span> </a></li>
        <li><a class="nav-item nav-btn-my-bookmarks disabled-footer-icon-link" href="javascript:void(0)"> <span
                    class="glyphicon glyphicon-bookmark nav-item-icon"></span> <span class="nav-item-text">My
                    Bookmarks</span> </a></li>
        <li><a class="nav-item nav-btn-library" href="<?php echo home_url(); ?>/levelled"> <span
                    class="glyphicon glyphicon-inbox nav-item-icon"></span> <span class="nav-item-text">Levelled
                    Library</span> </a></li>
        <li><a class="nav-item nav-btn-library" href="<?php echo home_url(); ?>/decodable"> <span
                    class="glyphicon glyphicon-inbox nav-item-icon"></span> <span class="nav-item-text">Decodable
                    Library</span> </a></li>
        <li><a class="nav-item nav-btn-records disabled-footer-icon-link" href="javascript:void(0)"> <span
                    class="glyphicon glyphicon-list-alt nav-item-icon"></span> <span class="nav-item-text">Reader
                    Records</span> </a></li>
        <!-- li><a class="nav-item my-statistics" href="#"> <span class="nav-item-icon"></span> <span class="nav-item-text">Stories</span> </a></li -->
    </ul>
    <!-- /div -->
</nav>

 

 <?php }
?>

<script>
jQuery(document).ready(function($) {
    $('.shelf-wrapper.blocked').wrap('<div class="blockedWrapper"></div>');
    $('.blockedWrapper').prepend(
        '<div class="blockedWrapper-message"><span class="blockedWrapper-text">You must reach 80% of the previous level to open.</span></div>'
    );
    $('.blockedWrapper-message').css({
        'position': 'absolute',
        'z-index': '999',
        'text-align': 'center',
        'width': '100%',
        'height': '100%',
        'padding-top': '200px'
    });
    $('.blockedWrapper-text').css({
        'background-color': '#f2f2f2',
        'padding': '15px',
        //'border' : '1px solid rgba(0, 0, 0, 0.15)',
        'box-shadow': '0px 0 100px rgba(0,0,0,.5)',
        'border-radius': '3px',
        'display': 'inline-block'
    });
});
</script>
