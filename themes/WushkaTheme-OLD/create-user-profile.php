<?php
/*
 * Template Name: Create Child profile page
 */
get_header();

/*
 * User role checking.
 * Only parents, and admin are allowed to access this page.
 */
if (!(is_user_logged_in() && is_super_admin() || is_admin() || user_can($current_user, "parent"))) {
    wp_redirect(home_url());
    exit;
}

$a_schools = get_terms('school');
$a_terms = array();
$a_broken = array();
foreach ($a_schools as $i_key => $o_school) {
    $a_term_options = get_option('taxonomy_' . $o_school->term_id);
    $a_terms[] = array(
        'school' => $o_school,
        'options' => $a_term_options,
        'marker' => null
    );
    if (
            !isset($a_term_options['school_latitude'], $a_term_options['school_longitude']) ||
            empty($a_term_options['school_latitude']) ||
            empty($a_term_options['school_longitude'])
    ) {
        $a_broken[] = array(
            'school' => $o_school->name,
            'ID' => $o_school->slug
        );
    }
}
/* Selection dropdown for reading level */
$args = array(
    'orderby' => 'slug',
    'order' => 'ASC'
);
$terms = get_terms('reading-level', $args);
$slugs = array();
foreach ($terms as $idx => $term) {
    if (ucwords($term->name) != 'Reading Level') {
        $slugs[] = array('name' => $term->name, 'slug' => $term->slug);
    }
}
$comprehension_level = $slugs[0]['name'];
?>
<script>
    var db_user_profile_path = '<?php echo get_template_directory_uri() . '/db_user_profile.php'; ?>';
    var template_directory = '<?php echo get_template_directory_uri(); ?>';
    var a_terms = <?php echo json_encode($a_terms); ?>;
    var a_broken = <?php echo json_encode($a_broken); ?>;
    var i_current_user = '<?php echo $current_user->id_hash; ?>';
</script>
<style type="text/css">
    .settings {
        display: none;
        opacity: 0;
        position: absolute;
        width: 100%;
        left: 0px;
        top: 0;
        transition: left 0.4s;
    }
    .settings.fadeLeft {
        left: -100px;
    }

    .settings.fadeRight {
        left: 100px;
    }
    /*
    #map_search {
            display: block;
            opacity: 1;
    }
    */
    /*--- MAPS --- */
    .map-wrapper {
        height: 470px;
        background-color: #FFF;
        border-radius: 5px;
    }

    #map-canvas {
        position: absolute;
        height: 100%;
        width: 610px;
        top: 0px;
        z-index: 10;
        background-color: #FFF;
        border-radius: 5px;
    }

    .overlay-1{
        position: absolute;
        display: none;
        opacity: 0;
        z-index: 15;
        height:100%;
        width:610px;
        top: 0px;
        border-radius:	5px;
        background-color: rgba(0,0,0,0.1);
    }
    .overlay-1 label {
        margin: 175px auto;
        width: 50%;
        text-align: center;
    }

    .overlay-2 {
        position: absolute;
        display: none;
        opacity: 0;
        z-index: 15;
        height: 100%;
        width: 90%;
        bottom: -340px;
        border-radius: 5px;
        left: 35px;
    }

    #link-load-label {
        opacity: 0;
    }

    #link-load-glyph {
        transition: transform 2s;
        -webkit-transition: -webkit-transform 2s;
    }
    #link-load-glyph.loading {
        animation: loadglyph 1s linear infinite;
        -webkit-animation: loadglyph 1s linear infinite;
    }
    @keyframes loadglyph {
        from{
            transform: rotate(0deg);
            -webkit-transform: rotate(0deg);
        }
        to {
            transform: rotate(180deg);
            -webkit-transform: rotate(180deg);
        }
    }
    .panel-wrapper{margin-bottom: 100px;}
    .panel legend { line-height: 1.3; padding-bottom: 3px; }


</style>
<div class="screen"></div>
<div class="confirmation-msg" style="display:none;">Profile saved</div>
<?php
if (check_license_limit($current_user->ID) <= 0) {
    wp_redirect(home - url() . '/manage-child/');
    exit();
}
?>

<div class="container-fluid padding-y">
    <div class="row">
        <div class="col-xs-12">
            <h1 class="glyphicon-heading"><span class="x2 glyphicon glyphicon-user hidden-xs"></span><span class="glyphicon-heading-text">Setup Home User</span></h1>
        </div>
    </div>
    <div class="row">
        <form href="#" class="form-horizontal new-child user-profile-form" id="add-new-child" role="form">
            <div class="col-xs-12 col-md-8 col-md-offset-2">

                <!-- PANEL 1 -->
                <div class="panel panel-default settings panel-wrapper" id="panel_1">
                    <div class="panel-body">
                        <legend>Enter your child's Home User details</legend>
                        <div class="row"><div class="col-sm-7 col-sm-offset-1">
                                <div class="form-group">
                                    <label for="first_name" class="col-xs-12 col-sm-3 control-label">First Name <span class="colour-red">*</span></label>
                                    <div class="col-xs-12 col-sm-8">
                                        <input type="text" name="first_name" class="form-control" id="first_name" value="" placeholder="First Name"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="last_name" class="col-xs-12 col-sm-3 control-label">Surname <span class="colour-red">*</span></label>
                                    <div class="col-xs-12 col-sm-8">
                                        <input type="text" name="last_name" class="col-xs-12 col-sm-9 form-control" id="last_name" value="" placeholder="Last Name"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="username" class="col-xs-12 col-sm-3 control-label">User Name</label>
                                    <div class="col-xs-12 col-sm-8">
                                        <input type="text" name="username" class="col-xs-12 col-sm-9 form-control" readonly id="username" value="" placeholder="User Name"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="show_user_pwd" class="col-xs-12 col-sm-3 control-label">Password <span class="colour-red">*</span></label>
                                    <div class="col-xs-12 col-sm-8">
                                        <input type="text" name="show_user_pwd" class="col-xs-12 col-sm-9 form-control" id="show_user_pwd" value="" placeholder="Password"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="age" class="col-xs-12 col-sm-3 control-label">Date of Birth <span class="colour-red">*</span></label>
                                    <div class="col-xs-12 col-sm-9 form-date-dmy">
                                        <input type="text" name="dob_day" maxlength="2" id="dob_day" value="" placeholder="DD"/><span> / </span>
                                        <input type="text" name="dob_month" maxlength="2" id="dob_month" value="" placeholder="MM"/><span> / </span>
                                        <input type="text" name="dob_year"  maxlength="4" id="dob_year" value="" placeholder="YYYY"/>
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
                            </div></div>

                        <div class="row"><div class="col-sm-7 col-sm-offset-1">
                                <div class="panel panel-default" style="background-color: #F2F2F2;"><div class="panel-body">
                                        <p style="line-height:1.2;">If your school provided you with a School Student username and password, you can enter them below to link to the school</p>
                                        <div class="form-group">
                                            <label for="select_school" class="col-xs-12 col-sm-4 control-label" >School Student Username</label>
                                            <div class="col-xs-12 col-sm-7">
                                                <input type="text"  name="school_username" class="form-control" id="school_username" value=""/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="select_school" class="col-xs-12 col-sm-4 control-label">Student User Password</label>
                                            <div class="col-xs-12 col-sm-7">
                                                <input type="text"  name="school_password" class="form-control" id="school_password" value=""/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-8 col-sm-offset-4">
                                                <input type="button" class="btn btn-primary" id="check-student-acc" value="Link School Account"/>
                                            </div>
                                            <div class="col-sm-8 col-sm-offset-4 has-error-wrapper">
                                                <label class="control-label" id="link-load-label">
                                                    <div class="panel panel-danger mt10 mb0">
                                                        <div class="panel-heading">
                                                            <span class="glyphicon glyphicon-cogwheel loading" id="link-load-glyph" style="float:left; margin-right:5px;"></span>
                                                            <p class="m0 panel-validation-message" style="float: left; font-size: 16px !important; font-weight: normal;">Linking...</p>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                    </div></div>
                            </div></div>





                        <div class="row"><div class="col-sm-12">
                                <div class="form-group mt30 mb0">
                                    <div class="col-xs-12">
                                        <div class="col-xs-6">
                                            <a class="btn btn-default btn-block" href="/manage-child-list">Cancel</a>
                                        </div>
                                        <div class="col-xs-6">
                                            <a class="btn btn-primary btn-block btn-next" href="#">Next</a>
                                        </div>
                                    </div>
                                </div>
                            </div></div>

                    </div>
                </div><!-- END PANEL 1 -->

                <div class="panel panel-default settings fadeRight panel-wrapper" id="panel_2">
                    <div class="panel-body">
                        <legend>Do you want to link to your child's Primary School?  </br>It is important to work closely with your child's school when they are learning to read, linking is recommended.</legend>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-6 col-sm-offset-3 mb5">
                                <a class="btn btn-primary btn-block btn-school" href="#"><i class="glyphicon glyphicon-circle-ok-2 x2"></i><span>Link to your Primary School</span></a>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-sm-offset-3 mb5">
                                <a class="btn btn-primary btn-block btn-home" href="#"><i class="glyphicon glyphicon-circle-no-2 x2"></i><span>Don't Link</span></a>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                                <a class="btn btn-default btn-block btn-prev" href="#">Previous</a>
                            </div>
                        </div>
                    </div>

                </div><!-- END PANEL 2 -->
                <div class="panel panel-default settings fadeRight panel-wrapper" id="panel_3">
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="panel panel-danger m0">
                                    <div class="panel-body">
                                        <p class="m0">By Selecting home schooling, your childs account will be kept seperate from their school account.<br/>This can be changed at any time by going to the manage child page and clicking the 'edit' button on this childs icon</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt30 mb0">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <a class="btn btn-default btn-block btn-prev" href="#">Previous</a>
                                </div>
                                <div class="col-xs-6">
                                    <a class="btn btn-primary btn-block btn-next btn-confirm" href="#">Yes, keep them seperate</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- END HOME PANEL -->

                <div class="settings panel-wrapper" id="panel_4">
                    <!-- SEARCH SECION -->
                    <div class="col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div style="display:inline;">
                                    <div class="input-group">
                                        <input type="text" id="search_field" class="form-control" placeholder="Please enter your Primary School's postcode">
                                        <span id="activate_search" class="input-group-addon"><span class="glyphicon glyphicon-search"></span> </span>
                                    </div>
                                </div>
                                <input type="hidden" id="child_school" name="child_school" value="home" />
                                <input type="hidden" id="school_name" value="Home Schooled" />
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6" id="section-results">
                        <div class="panel panel-default">
                            <div class="panel-heading"><div class="panel-title"></div></div>
                            <div class="panel-body" id="results-wrap"></div>
                        </div>
                    </div>
                    <!-- RESULTS SECION -->
                    <div class="col-xs-12 col-sm-6">
                        <div class="map-wrapper">
                            <div class="overlay-1">
                                <div class="col-xs-12">
                                    <label class="label-loading form-control">Loading Results...</label>
                                </div>
                            </div>
                            <div class="overlay-2">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="col-xs-12">
                                            <label>Is this the correct Primary School?</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <a id="confirm-no" class="btn btn-default btn-block" href="#">No</a>
                                        </div>
                                        <div class="col-xs-6">
                                            <a id="confirm-yes" class="btn btn-primary btn-block" href="#">Yes</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="overlay-3"></div>
                            <div id="map-canvas"></div>
                        </div>
                    </div>
                    <!-- END MAP SECION -->
                    <div class="col-xs-12 padding-y">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group mb0">
                                    <div class="col-xs-6">
                                        <a class="btn btn-default btn-block btn-prev" href="#">Previous</a>
                                    </div>
                                    <div class="col-xs-6">
                                        <a id="school-confirmed" class="btn btn-primary btn-block btn-next btn-confirm disabled" href="#">Continue</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default settings fadeRight panel-wrapper" id="panel_5">
                    <div class="panel-body">
                        <legend>Select the year level of your child</legend>
                        <div class="form-group">
                            <label for="select_school" class="col-xs-12 col-sm-3 control-label">School</label>
                            <div class="col-xs-12 col-sm-9">
                                <label class="control-label" id="select_school"></label>
                            </div>
                        </div>
                        <div class="form-group" id="loading_years" style="display:none;opacity:0;">
                            <label class="col-xs-12 control-label" style="text-align:center;font-size:1.2rem;">
                                Loading School Year levels...
                            </label>
                        </div>
                        <div class="form-group" style="display:none;opacity:0;">
                            <label for="select_school" class="col-xs-12 col-sm-3 control-label" style="font-weight:normal;">School Year Level</label>
                            <div class="col-xs-12 col-sm-4">
                                <select name="child_year" class="form-control" id="child_year">
                                    <option value="" selected="selected"></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mt30 mb0">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <a class="btn btn-default btn-block btn-prev" href="#">Previous</a>
                                </div>
                                <div class="col-xs-6">
                                    <a class="btn btn-primary btn-block btn-next btn-confirm" href="#">Confirm School Link</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- END SCHOOL PANEL -->

                <div class="panel panel-default settings fadeRight panel-wrapper" id="panel_6">
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <legend>Account Details</legend>
                                <p class="mb0 ml15">Are these details correct?</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label class="col-xs-12 col-sm-3 control-label">First Name:</label>
                                <div class="col-xs-12 col-sm-9">
                                    <label id="confirm-first_name" class="control-label regular"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label class="col-xs-12 col-sm-3 control-label">Last Name:</label>
                                <div class="col-xs-12 col-sm-9">
                                    <label id="confirm-last_name" class="control-label regular"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label class="col-xs-12 col-sm-3 control-label">Date of Birth:</label>
                                <div class="col-xs-12 col-sm-9">
                                    <label id="confirm-dob" class="control-label regular"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label class="col-xs-12 col-sm-3 control-label">School:</label>
                                <div class="col-xs-12 col-sm-9">
                                    <label id="confirm-school" class="control-label regular"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label class="col-xs-12 col-sm-3 control-label">School Year:</label>
                                <div class="col-xs-12 col-sm-9">
                                    <label id="confirm-year" class="control-label regular" style="width: 100%; text-align: left;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label class="col-xs-12 col-sm-3 control-label">Student User Account:</label>
                                <div class="col-xs-12 col-sm-9">
                                    <label id="confirm-school_username" class="control-label regular"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt30 mb0">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <a class="btn btn-default btn-block btn-start" style="line-height:1.6;" href="#">No, let me change something</a>
                                </div>
                                <div class="col-xs-6">
                                    <!-- <a class="btn btn-primary btn-block" href="#">Yes, Create Account</a> -->
                                    <input type="submit" class="btn btn-primary btn-block" value="Finalise Setup" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div><!-- END ROW -->
</div>

<script>
    jQuery(document).ready(function ($) {
        // 1.Define function to move glyphicon position with some delay
        function moveGlyphiconPosition() {
            $('.has-error-wrapper').show();
            $('.glyphicon-circle-remove').prependTo('.panel-validation-message');
        }
        // 2.Call function on click
        $('#check-student-acc').click(function () {
            setTimeout(moveGlyphiconPosition, 500);
        });

        /* 
         $('#activate_search').click(function(){
         $('#section-results').show();
         }); */

    });
</script>

<?php
include 'dashboard_options.php';
get_footer();
?>
