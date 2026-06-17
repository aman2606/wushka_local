<?php
/*
 * Template Name: Parent Manage Child
 */
get_header();
/* User role checking.
 * Only teachers, and admin are allowed to access this page.
 */
if (!(is_user_logged_in() && is_super_admin() || is_admin() || user_can($current_user, "parent"))) {
    wp_redirect(home_url());
    exit;
}
$users = check_license_limit($current_user->ID);
$s_licenses = 'remining ';

if ($users >= 0) {
    $s_licenses = ($users == 1) ? $users . ' remaining home user' : $users . ' remaining licenses';
    $s_licenses = ($users == 0) ? 'No remaining Home User' : $s_licenses;
} else {
    $s_licenses = 'Subscription Inactive';
}
?>
<div class="container-fluid">
    <div class="row mt30">
        <div class="col-xs-12">
            <h1 class="glyphicon-heading"><span class="x2 glyphicon glyphicon-group hidden-xs"></span><span class="glyphicon-heading-text">Manage Profiles</span><span class="numLicenses"><?php echo $s_licenses; ?></span></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="class-login-container">
                <div class="panel-body">
                    <?php
                    echo '<div class="row">'; ?>
                    <article class="child-profile new col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <div id="<?php echo $current_user->id_hash; ?>" class="child-profile-inner">
                            <header class="child-profile-name text-center student-box btn-class-child-login">
                                <h2 class="student-name-heading">You</h2>
                                <?php echo get_avatar($current_user->id_hash, 100) ?>
                            </header>
                            <footer class="child-profile-options">
                                <form method="POST" action="/edit-child-profile">
                                    <input class="btn btn-primary btn-block btn-signon-profile" id="<?php echo $current_user->id_hash; ?>" type="button" value="Continue as You" />
                                </form>
                            </footer>
                        </div>
                    </article>
                    <?php 
                    $a_students = wushka_get_students($current_user->ID, 'parent_id', 1);
                    if (isset($a_students) && !empty($a_students)) {
                        foreach ($a_students as $idx => $student) {
                            $name = $student->first_name . " " . $student->last_name;
                            if (!empty(trim($name))) {
                                ?>

                                <article class="child-profile new col-xs-12 col-sm-4 col-md-3 col-lg-2">
                                    <div id="<?php echo $student->id_hash; ?>" class="child-profile-inner">
                                        <header class="child-profile-name text-center student-box btn-class-child-login">
                                            <h2 class="student-name-heading"><?php echo $name; ?></h2>
                                            <?php echo get_avatar($student->id_hash, 100) ?>
                                        </header>
                                        <footer class="child-profile-options">
                                            <form method="POST" action="/edit-child-profile">
                                                <input class="btn btn-default btn-block btn-edit-profile" type="hidden" name="edit_childID" value="<?php echo $student->id_hash; ?>">
                                                <input class="btn btn-default btn-block btn-edit-profile" type="submit" value="Edit Home User Details" />
                                                <input class="btn btn-primary btn-block btn-signon-profile" id="<?php echo $student->id_hash; ?>" type="button" value="Login as <?php echo $student->first_name ?>" />
                                                <input class="btn btn-primary btn-block btn-delete-profile" data-id="<?php echo $student->id_hash; ?>" type="button" value="Delete <?php echo $student->first_name ?>" />
                                            </form>
                                        </footer>
                                    </div>
                                </article>

                                <?php
                            } //End Id
                        } //End Foreach Student
                    }
                    while ($users > 0) {
                        $users--;
                        ?>
                        <article class="child-profile new col-xs-12 col-sm-4 col-md-3 col-lg-2">
                            <div class="child-profile-inner">
                                <header class="child-profile-name text-center student-box btn-class-child-login"><h2 class="student-name-heading">Empty Profile</h2></header>
                                <footer class="child-profile-options">
                                    <form method="POST" action="/child-add">
                                        <input class="btn btn-default btn-block btn-edit-profile" style="background-color: open #d64242;" type="submit" value="Add Child" />
                                    </form>
                                </footer>
                            </div>
                        </article>
                        <?php
                    }
                    echo '</div>';
                    if (isset($a_students) && empty($a_students)) {
                        echo '<p>Add a new child by clicking on an <b>Add Child</b> button.</p>';
                    } ?>
                </div><!-- End Panel-Body -->
            </div>
        </div>
    </div>
    <div class="modal fade" id="delete-profile" tabindex="-1" role="dialog" aria-labelledby="dp-title" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title" id="dp-title">Delete profile</h3>
          </div>
          <div class="modal-body">
              <div class="form-group">
                  <label for="parent-pw">Enter DELETE and press Confirm to <span id="delete-name"></span></label>
                  <input type="text" name="delete-confirm" id="delete-confirm" value="">
                  <div id="delete-message" class="error"></div>
              </div>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary btn-confirm-delete">Confirm</button>
          </div>
        </div>
      </div>
    </div>
</div><!-- End Body Container-fluid -->
</div><!-- End Wrap Container-fluid -->
<?php
include 'dashboard_options.php';

get_footer();
?>
<script>
    jQuery(document).ready(function ($) {

        $('.btn-signon-profile').on('click', function (e) {
            e.preventDefault();
            $id = $(this).attr('id');
            loginAsStudent($id);
        });
        /*To write in a database*/
        function loginAsStudent($id) {
            $.ajax({
                url: '<?php echo get_template_directory_uri() . '/login-as-student.php'; ?>',
                type: "POST",
                data: {
                    'id': $id
                },
                success: function (data) {
                    window.location = '<?php echo get_home_url(); ?>';
                    console.log(data);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                    console.log(thrownError);
                }
            });
        }
        $('.btn-delete-profile').on('click', function (e) {
            e.preventDefault();
            $('.btn-confirm-delete').attr('data-id', $(this).attr('data-id'));
            $('#delete-name').text($(this).val());
            $('#delete-profile').modal('show');
        });
        $('.btn-confirm-delete').on('click', function (e) {
            $('#delete-message').empty();
            if ($('#delete-confirm').val() !== 'DELETE') {
                $('#delete-message').text('You must enter DELETE to delete this profile');
                return false;
            }
            e.preventDefault();
            $id = $(this).attr('data-id');
            deleteStudent($id);
            $('#delete-profile').modal('hide');
        });
        function deleteStudent($id) {
            $.ajax({
                url: '<?php echo get_template_directory_uri() . '/db_user_profile.php'; ?>',
                type: "POST",
                data: {
                    'meta': 'delete-child-profile',
                    'userID': $id
                },
                success: function (data) {
                    console.log(data);
                    location.reload();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                    console.log(thrownError);
                }
            });
        }
    });
</script>
