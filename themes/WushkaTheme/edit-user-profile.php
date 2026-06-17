<?php
/*
 * Template Name: Edit Child profile page
 */
get_header();
/* User role checking.
 * Only parents, and admin are allowed to access this page.
 */
if (!(is_user_logged_in() && is_super_admin() || is_admin() || user_can($current_user, "parent"))) {
    wp_redirect(home_url());
    exit;
}
?>
<script>
    var template_directory = '<?php echo get_template_directory_uri(); ?>';
    var db_user_profile_path = '<?php echo get_template_directory_uri() . '/db_user_profile.php'; ?>';
</script>
<?php
/* Get user data */
$_SESSION['edit_childID'] = isset($_POST['edit_childID']) ? $_POST['edit_childID'] : $_SESSION['edit_childID'];
$child_id = isset($_POST['edit_childID']) ? $_POST['edit_childID'] : $_SESSION['edit_childID'];
if (!isset($child_id)) {
    wp_redirect(home_url() . '/manage-child-list/');
}
//$user_info = get_userdata($child_id);
$user_info = get_user_by_hash($child_id);

//Check is School Form was submitted
if (isset($_POST['school_submit'])) {
    //Save School Selection
}

$a_schools = get_terms('school');
$child_school = wp_get_object_terms($user_info->ID, 'school');

$school_id = NULL;
if (isset($child_school) && !empty($child_school)) {
    $school_id = $child_school[0]->term_id;
}

$i_dob_day = NULL;
$i_dob_month = NULL;
$i_dob_year = NULL;
if (isset($user_info->user_dob)) {
    $s_dob = $user_info->user_dob;
    $a_dob = explode('/', $s_dob);
    $i_dob_day = $a_dob[0];
    $i_dob_month = $a_dob[1];
    $i_dob_year = $a_dob[2];
}
/* Selection dropdown for reading level */
$args      = array(
    'orderby' => 'slug',
    'order'   => 'ASC'
);
$terms     = get_terms('reading-level', $args);
$slugs     = array();
foreach( $terms as $idx => $term ) {
    if( ucwords($term->name) != 'Reading Level' ) {
        $slugs[] = array('name' => $term->name, 'slug' => $term->slug);
    }
}
if (!isset($user_info->comprehension_level)) {
    $comprehension_level = $slugs[0]['name'];
} else {
    $comprehension_level = $slugs[$user_info->comprehension_level]['name'];
}
foreach ($slugs as $key => $slug) {
    if ($slug['name'] !== $comprehension_level) {
        unset($slugs[$key]);
    } else {
        break;
    }
}
/*
  <select name="dob_day" class="col-xs-12 col-md-9 select" id="dob_day">
  <?php
  for ($ii = 1; $ii <= 31; $ii++) {
  $s_class = ($i_dob_day == $ii) ? 'selected="selected"' : NULL;
  echo '<option value="' . $ii . '" ' . $s_class . '>' . $ii . '</option>';
  }
  ?>
  </select>
  <select name="dob_month" class="col-xs-12 col-md-9 col-md-offset-3 select" id="dob_month">
  <?php
  for ($ii = 1; $ii <= 12; $ii++) {
  $s_class = ($i_dob_month == $ii) ? 'selected="selected"' : NULL;
  $a_month = array(1 => 'January', 2 => 'Febuary', 3 => 'March', 4 => 'April',
  5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October',
  11 => 'November', 12 => 'December'
  );
  echo '<option value="' . $ii . '" ' . $s_class . '>' . $a_month[$ii] . '</option>';
  }
  ?>
  </select>
  <select name="dob_year" class="col-xs-12 col-md-9 col-md-offset-3 select" id="dob_year">
  <?php
  for ($ii = 2015; $ii >= 2000; $ii--) {
  $s_class = ($i_dob_year == $ii) ? 'selected="selected"' : NULL;
  echo '<option value="' . $ii . '" ' . $s_class . '>' . $ii . '</option>';
  }
  ?>
  </select>
 */
?>
<div class="screen"></div>
<div class="confirmation-msg" style="display:none;">Profile saved</div>
<div class="container-fluid padding-y">
    <div class="row">
        <div class="col-xs-12">
            <h1 class="glyphicon-heading"><span class="x2 glyphicon glyphicon-user hidden-xs"></span><span class="glyphicon-heading-text">Edit Home User Details</span></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-lg-8 col-lg-offset-2">
            <form href="#" method="POST" class="form-horizontal edit-child user-profile-form" id="edit-child-profile" role="form">
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <!-- div class="right-navigation" -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="parent-view-heading"><i class="glyphicon glyphicon-list"></i> Edit Home User details</h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="first_name" class="col-xs-12 col-md-3 control-label">First Name</label>
                                        <div class="col-xs-12 col-sm-9">
                                            <input type="text"  name="first_name"  class="form-control" id="first_name" value="<?php echo $user_info->first_name; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name" class="col-xs-12 col-md-3 control-label">Last Name</label>
                                        <div class="col-xs-12 col-sm-9">
                                            <input type="text"  name="last_name"  class="form-control" id="last_name" value="<?php echo $user_info->last_name; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="username" class="col-xs-12 col-md-3 control-label">User Name</label>
                                        <div class="col-xs-12 col-sm-9">
                                            <input type="text"  name="username" readonly="readonly" class="form-control" id="username" value="<?php echo $user_info->user_login; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="show_user_pwd" class="col-xs-12 col-md-3 control-label">Password</label>
                                        <div class="col-xs-12 col-sm-9">
                                            <input type="text"  name="show_user_pwd"  class="form-control" id="show_user_pwd" value="<?php echo $user_info->show_user_pwd; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="age" class="col-xs-12 col-md-3 control-label">Date of Birth</label>
                                        <div class="col-xs-12 col-sm-9 pull-right">
                                            <input type="text" class="col-xs-2 col-sm-2" name="dob_day" maxlength="2" id="dob_day" value="<?php echo $i_dob_day; ?>" placeholder="DD"/>
                                            <label class="col-xs-1"> / </label>
                                            <input type="text" class="col-xs-2 col-sm-2" name="dob_month" maxlength="2" id="dob_month" value="<?php echo $i_dob_month; ?>" placeholder="MM"/>
                                            <label class="col-xs-1"> / </label>
                                            <input type="text" class="col-xs-4 col-sm-3" name="dob_year"  maxlength="4" id="dob_year" value="<?php echo $i_dob_year; ?>" placeholder="YYYY"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="reading-level" class="col-xs-12 col-md-3 control-label">Reading level</label>
                                        <div class="col-xs-12 col-sm-9">
                                            <select name="reading-level" class="form-control" id="reading-level">
                                                <?php
                                                foreach ($slugs as $slug) {
                                                    $s_level = (isset($user_info->reading_level) && $slug['slug'] === $user_info->reading_level) ? 'selected="selected"' : NULL;
                                                    echo '<option value="' . $slug['slug'] . '" ' . $s_level . '>' . $slug['name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="comprehension-level" class="col-xs-12 col-md-3 control-label">Comprehension level</label>
                                        <div class="col-xs-12 col-sm-9">
                                            <input type="text" name="comprehension-level" readonly="readonly" class="form-control" id="comprehension-level" value="<?php echo $comprehension_level; ?>"/>
                                        </div>
                                    </div>
                                    <input type="hidden" name="userID" value="<?php echo $child_id; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <!-- div class="right-navigation" -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="parent-view-heading"><i class="glyphicon glyphicon-education"></i> Edit School Link</h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="select_school" class="col-xs-12 col-md-3 control-label">School</label>
                                        <div class="col-xs-12 col-sm-9">
                                            <select name="child_school" class="form-control" id="child_school">
                                                <?php $s_home = ($school_id == NULL) ? 'selected="selected"' : NULL; ?>
                                                <option value="home" <?php echo $s_home; ?>>Home Schooling</option>
                                                <?php
                                                foreach ($a_schools as $i_key => $o_school) {
                                                    $s_select = ( $school_id == $o_school->term_id && $s_home == NULL ) ? 'selected="selected"' : NULL;
                                                    echo '<option value="' . $o_school->slug . '" ' . $s_select . '>' . $o_school->name . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php
                                    if (isset($user_info->student_link_id)) {
                                        $o_student = get_user_by('id', $user_info->student_link_id);
                                        echo '<label class="col-xs-12 text-left"><span><i class="glyphicon glyphicon-user x2" style="color: #A8CF37;margin-right:10px;"></i></span>Linked to Student: ' . $o_student->user_login . '</label>';
                                    } else {
                                        ?>
                                        <div class="form-group">
                                            <?php $s_class = (isset($user_info->student_link_id)) ? 'readonly' : NULL; ?>
                                            <label for="select_school" class="col-xs-12 col-md-3 control-label">User Name</label>
                                            <div class="col-xs-12 col-sm-9">
                                                <input type="text"  name="school_username" <?php echo $s_class; ?> class="form-control" id="school_username" value="<?php echo $user_info->school_username; ?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="select_school" class="col-xs-12 col-md-3 control-label">Password</label>
                                            <div class="col-xs-12 col-sm-9">
                                                <input type="text"  name="school_password" <?php echo $s_class; ?> class="form-control" id="school_password" value="<?php echo $user_info->school_pwd; ?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-bottom:50px;">
                                            <label class="col-xs-12 control-label" style="text-align:left;font-size:1.3rem;">
                                                To link your child to this school,
                                                you must enter the username and password
                                                that was provided to you
                                            </label>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- END ROW -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="col-xs-12 col-sm-6">
                                            <a href="/manage-child-list"><input type="button" class="btn btn-default btn-block" value="Cancel" /></a>
                                        </div>
                                        <div class="col-xs-12 col-sm-6">
                                            <input type="submit" class="btn btn-primary btn-block" value="Save Changes" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
include 'dashboard_options.php';
get_footer(); ?>
<script>
jQuery(document).ready(function ($) {
});
</script>
