<?php
/*
  Template Name: School Teacher Selection
 */

//Is User Logged In AND is user a school?
if (!is_user_logged_in() || (!user_can($current_user, "school") && !user_can($current_user, "teacher"))) {
    //Redirect to Login Page
    wp_redirect(home_url() . "/wp-login.php");
    exit;
}
/* --- Deploy Page --- */
//Add Header
get_header();
?>
<div class="container-fluid">
    <div class="row mt30">
        <div class="col-xs-12">
            <h2 class="glyphicon-heading"><span class="x2 glyphicon glyphicon-dashboard hidden-xs"></span><span
                    class="glyphicon-heading-text">Select your Dashboard</span></h2>
        </div>
        <section class='page-section padding-y grad-radial' id='selection-functions'>
            <div class='container'>
                <div class='row'>
                    <div class="school-dashboard col-xs-12 col-sm-6">
                        <a href="javascript:;" class="dashboard-header cursor-mouse grow">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <h3 class="mb10 text-center">Wushka Program Coordinator Dashboard</h3>
                                    <img class="img-responsive"
                                        src="//cdn1.wushka.com.au/Resources/school-dashboard-access.png"
                                        alt="Wushka Program Coordinator Dashboard">
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="teacher-dashboard col-xs-12 col-sm-6">
                        <a href="javascript:void(0);" class="dashboard-header cursor-mouse grow">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <h3 class="mb10 text-center">Teacher Dashboard</h3>
                                    <img class="img-responsive"
                                        src="//cdn1.wushka.com.au/Resources/teacher-dashboard-access.jpg"
                                        alt="Teacher Dashboard">
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $(document).on('click', 'div.school-dashboard', function() {
        selectDashboard('school');
    });
    $(document).on('click', 'div.teacher-dashboard', function() {
        selectDashboard('teacher');
    });

    function selectDashboard(dashboard) {
        $.ajax({
            url: '<?php echo get_template_directory_uri() . '/switch-dashboard.php'; ?>',
            type: "POST",
            data: {
                'type': dashboard
            },
            success: function(data) {
                window.location = '<?php echo get_home_url(); ?>';
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
            }
        });
    }
});
</script>
<?php
//Add Footer
get_footer();
?>