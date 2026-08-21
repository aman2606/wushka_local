<?php
/*
  Template Name: School Settings
 */

//Is User Logged In AND is user a school?
if( ! current_user_can('administrator') ) {
    if( ! is_user_logged_in() || ! current_user_can('school') ) {
        //Redirect to Login Page
        wp_redirect(esc_url(get_permalink(get_page_by_title('Login'))));
        exit;
    }
}

$terms        = wp_get_object_terms($current_user->ID, 'school');
$school       = $terms[0];
$school_years = $current_user->school_years;
if( ! isset($school_years) || empty($school_years) ) {
    $school_years = get_wushka_school_years();
}

$s_auth = wp_create_nonce('adjust_school_' . $current_user->ID . '_pwd');
$s_state = wushka_get_school_caldendar_state($school->term_taxonomy_id);

$school_id = $school->term_taxonomy_id;
$account_id = $school->slug;

$api_button_text = ''; 
$api_confirm_text = ''; 
if(SIS_REST_API_ENABLED != "false"){
    $api_key = get_api_key($account_id, $school_id);

    if(!$api_key){
        $api_key = "Please generate a new API key";
        $no_key = true;
    } 


    $api_button_text = 'Generate and replace API key'; 
    $api_confirm_text = 'Do you want to replace current API with a new API key?'; 

    $api_button_text_replaced = $api_button_text;
    $api_confirm_text_replaced = $api_confirm_text;

    if($no_key){
        $api_button_text = 'Create new API Key'; 
        $api_confirm_text = 'Do you want to create a new API key?'; 
    }
}
                               

//Add Header
get_header();
?>

<form class="page-school-settings form-horizontal" id="schoolsettings">
    <div class="container-fluid">

        <div class="row mt15">
            <div class="col-xs-12">
                <h2 class="glyphicon-heading text-left">
                    <span class="x2 glyphicon glyphicon-settings hidden-xs"></span>
                    <span class="glyphicon-heading-text">Step 1: Wushka Program Coordinator Settings -
                        <?php echo $school->name ?></span>
                    <span class="glyphicon-heading-btn-group">
                        <?php if ($s_state !== 'WORLD') { ?>
                        <a href="school-calendar" role="button" class="btn btn-default btn-calendar"
                            title="School Calendar">
                            <span class="glyphicon glyphicon-calendar"></span>
                            <span class="sr-only">School Calendar</span>
                        </a>
                        <?php } ?>
                        <span class="btn-back-dashboard">
                            <a href="#" data-ref="/school-teachers" id="step2" role="button"
                                class="btn btn-primary btn-back-to-dashboard proceed">
                                <span class="glyphicon glyphicon-chevron-right"></span> Go to Step 2
                            </a>
                        </span>
                    </span>
                </h2>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-6">
                <?php if( $current_user->user_terms != 'Yes' ) { ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="glyphicon glyphicon-check"></i> Terms &amp; Conditions
                    </div>
                    <div class="panel-body">
                        <div class="well">
                            <p>Terms and conditions of use can be found <a href="/school-terms-and-conditions/"
                                    target="_blank">here</a></p>

                            <div class="checkboxes">
                                <label><input type="checkbox" name="user_terms" id="user_terms" value="Yes" <?php if( $current_user->user_terms === 'Yes' ) {
                                            echo 'checked="checked"';
                                        } ?> required>I accept the terms and conditions of use</label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="glyphicon glyphicon-list"></i> a. Details
                        <span class="pull-right">
                            <button type="button" class="btn btn-default" data-toggle="modal"
                                data-target="#edit-password-modal">
                                Change Password
                            </button>
                        </span>
                    </div>
                    <div class="panel-body">
                        <h3>Wushka Program Coordinator Details</h3>

                        <div class="well form-horizontal">
                            <div class="form-group">
                                <label for="first_name" class="col-lg-3">First Name<em>*</em></label>

                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="first_name" id="first_name"
                                        value="<?php echo $current_user->first_name ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="last_name" class="col-lg-3">Surname<em>*</em></label>

                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="last_name" id="last_name"
                                        value="<?php echo $current_user->last_name ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="user_phone" class="col-lg-3">Phone (Mobile Preferred)<em>*</em></label>

                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="user_phone" id="user_phone"
                                        value="<?php echo $current_user->user_phone ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="user_email" class="col-lg-3">Email<em>*</em></label>

                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="user_email" id="user_email"
                                        value="<?php echo $current_user->user_email ?>" required>
                                </div>
                                <span class="help-block col-lg-offset-3 col-lg-9">This is the email address to which
                                    account notifications will be sent.</span>
                            </div>
                            <div class="form-group">
                                <label for="user_jobtitle" class="col-lg-3">School Job Title<em>*</em></label>

                                <div class="col-lg-9">
                                    <select class="form-control" name="user_jobtitle" id="user_jobtitle">
                                        <option value="administration" <?php if( $current_user->user_jobtitle === 'administration' ) {
                                            echo ' selected="selected"';
                                        } ?>>School Management / Administration
                                        </option>
                                        <option value="literacy" <?php if( $current_user->user_jobtitle === 'literacy' ) {
                                            echo ' selected="selected"';
                                        } ?>>Literacy Coordinator (or similar)
                                        </option>
                                        <option value="teacher" <?php if( $current_user->user_jobtitle === 'teacher' ) {
                                            echo ' selected="selected"';
                                        } ?>>Teacher
                                        </option>
                                        <option value="principal" <?php if( $current_user->user_jobtitle === 'principal' ) {
                                            echo ' selected="selected"';
                                        } ?>>Principal / Head
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="teacher" class="col-lg-3">Coordinator is a teacher?<em>*</em></label>

                                <div class="col-lg-9">
                                    <input type="checkbox" name="teacher" id="teacher"
                                        <?php echo user_can($current_user, 'teacher') ? 'checked' : '' ?>>
                                </div>
                                <span class="help-block col-lg-offset-3 col-lg-9">If you are a Teacher, please check
                                    this box to gain access to both School and Teacher dashboards.</span>
                            </div>
                        </div>
                        <h3>Mailing Address</h3>

                        <div class="well">
                            <div class="form-group">
                                <label for="shipping_company" class="col-lg-3">School Name<em>*</em></label>

                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="shipping_company"
                                        id="shipping_company" value="<?php echo $current_user->shipping_company ?>"
                                        required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="shipping_address_1" class="col-lg-3">Address Line 1<em>*</em></label>

                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="shipping_address_1"
                                        id="shipping_address_1" value="<?php echo $current_user->shipping_address_1 ?>"
                                        required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="shipping_address_2" class="col-lg-3">Address Line 2</label>

                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="shipping_address_2"
                                        id="shipping_address_2" value="<?php echo $current_user->shipping_address_2 ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="shipping_city" class="col-lg-3">Suburb / Town<em>*</em></label>

                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="shipping_city" id="shipping_city"
                                        value="<?php echo $current_user->shipping_city ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="shipping_postcode" class="col-lg-3">Postcode<em>*</em></label>

                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="shipping_postcode"
                                        id="shipping_postcode" value="<?php echo $current_user->shipping_postcode ?>"
                                        required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="shipping_state" class="col-lg-3">State<em>*</em></label>

                                <div class="col-lg-9">
                                    <select class="form-control" name="shipping_state" id="shipping_state">
                                        <option value="ACT" <?php if( $current_user->shipping_state === 'ACT' ) {
                                            echo ' selected="selected"';
                                        } ?>>Australian Capital Territory
                                        </option>
                                        <option value="NSW" <?php if( $current_user->shipping_state === 'NSW' ) {
                                            echo ' selected="selected"';
                                        } ?>>New South Wales
                                        </option>
                                        <option value="VIC" <?php if( $current_user->shipping_state === 'VIC' ) {
                                            echo ' selected="selected"';
                                        } ?>>Victoria
                                        </option>
                                        <option value="QLD" <?php if( $current_user->shipping_state === 'QLD' ) {
                                            echo ' selected="selected"';
                                        } ?>>Queensland
                                        </option>
                                        <option value="SA" <?php if( $current_user->shipping_state === 'SA' ) {
                                            echo ' selected="selected"';
                                        } ?>>South Australia
                                        </option>
                                        <option value="WA" <?php if( $current_user->shipping_state === 'WA' ) {
                                            echo ' selected="selected"';
                                        } ?>>Western Australia
                                        </option>
                                        <option value="TAS" <?php if( $current_user->shipping_state === 'TAS' ) {
                                            echo ' selected="selected"';
                                        } ?>>Tasmania
                                        </option>
                                        <option value="NT" <?php if( $current_user->shipping_state === 'NT' ) {
                                            echo ' selected="selected"';
                                        } ?>>Northern Territory
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="glyphicon glyphicon-user"></i> b. Contacts
                    </div>
                    <div class="panel-body">
                        <h3>Literacy Coordinator (or similar)</h3>

                        <div class="well">
                            <div class="form-group">
                                <label for="principal_first_name" class="col-lg-3">First Name</label>

                                <div class="col-lg-9">
                                    <input type="text" class="form-control" id="principal_first_name"
                                        value="<?php echo $current_user->principal_first_name ?>"
                                        placeholder="Literacy Coordinator (or similar) first name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="principal_last_name" class="col-lg-3">Surname</label>

                                <div class="col-lg-9">
                                    <input type="text" class="form-control" id="principal_last_name"
                                        value="<?php echo $current_user->principal_last_name ?>"
                                        placeholder="Literacy Coordinator (or similar) surname">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="principal_email" class="col-lg-3">Email</label>

                                <div class="col-lg-9">
                                    <input type="text" class="form-control" id="principal_email"
                                        value="<?php echo $current_user->principal_email ?>"
                                        placeholder="name@example.com">
                                </div>
                            </div>
                        </div>
                        <h3>Principal / Deputy Principal</h3>

                        <div class="well">
                            <div class="form-group">
                                <label for="deputy_first_name" class="col-lg-3">First Name</label>

                                <div class="col-lg-9">
                                    <input type="text" class="form-control" id="deputy_first_name"
                                        value="<?php echo $current_user->deputy_first_name ?>"
                                        placeholder="Principal / Deputy Principal first name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="deputy_last_name" class="col-lg-3">Surname</label>

                                <div class="col-lg-9">
                                    <input type="text" class="form-control" id="deputy_last_name"
                                        value="<?php echo $current_user->deputy_last_name ?>"
                                        placeholder="Principal / Deputy Principal surname">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="deputy_email" class="col-lg-3">Email</label>

                                <div class="col-lg-9">
                                    <input type="text" class="form-control" id="deputy_email"
                                        value="<?php echo $current_user->deputy_email ?>"
                                        placeholder="name@example.com">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-12"><em>*</em> These fields are compulsory</label>
                        </div>
                    </div>
                </div>

                <?php if(SIS_REST_API_ENABLED != "false"){ ?>
                <!-- API Key-->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-key"></i> c. API Key
                    </div>
                    <div class="panel-body">
                        <div class="well">
                            <div class="form-groups">
                                <p class="col-lg-3">API Key</p> 
                                <div class="col-lg-9">
                                    <p id="show-api-key"><?= $api_key; ?></p>
                                </div> 
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <button id="create-api-button" data-toggle="modal" data-target="#create-api-modal" type="button" class="btn btn-success">
                                 <?= $api_button_text; ?>                                  
                                </button>
                            </div> 
                        </div>
                    </div>
                </div>
                <!-- API Key Ends -->
                <?php } ?>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="pull-right">
                    <span class="btn-back-dashboard">
                        <a href="#" data-ref="<?= home_url('/'); ?>school-teachers" role="button"
                            class="btn btn-primary btn-back-to-dashboard proceed2">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                            Finished: Go to Step 2
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal -->
<div class="modal fade" id="create-api-modal" tabindex="-1" role="dialog" aria-labelledby="create-api-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
            <?= $api_button_text; ?>
        </h4>
      </div>
      <div class="modal-body">
            <p><?= $api_confirm_text; ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">No, Cancel</button>
        <button type="button" id="create-api" class="btn btn-success" data-dismiss="modal">Yes, Create</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit-password-modal" tabindex="-1" role="dialog" aria-labelledby="cn-label"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="cn-label">Change Password</h3>
                <a role="button" class="btn-close-modal close-xl" data-dismiss="modal" data-toggle="modal"
                    data-target="#manage-class-settings" aria-hidden="true">&times;</a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="old_password" class="col-lg-5">Current Password</label>

                            <div class="col-lg-7">
                                <input type="password" class="form-control" name="old_password" id="old_password"
                                    value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_password" class="col-lg-5">New Password</label>

                            <div class="col-lg-7">
                                <input type="password" class="form-control" name="new_password" id="new_password"
                                    value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password" class="col-lg-5">Confirm Password</label>

                            <div class="col-lg-7">
                                <input type="password" class="form-control" name="confirm_password"
                                    id="confirm_password" value="">
                            </div>
                        </div>
                        <div class="col-lg-9 pull-right text-right">
                            <p id="password-error" class="error">please enter a password</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal"
                    data-target="#manage-class-settings">Close
                </button>
                <input type="button" id="update-school-password" class="btn btn-primary" value="Change Password" />
            </div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {

    <?php   if(SIS_REST_API_ENABLED != "false"){ ?>
    //Create new api key
    $('#create-api').on('click', function(e){
        //e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")); ?>",
            data: {
                action: "wushka_create_api_key"
            },
            success: function(data) {
                $('#show-api-key').text(data); 
                var api_button_text = '<?= $api_button_text_replaced; ?>';
                var api_confirm_text = '<?= $api_confirm_text_replaced; ?>';
                $("#create-api-button").text(api_button_text);
                $("#create-api-modal h4").text(api_button_text);
                $("#create-api-modal p").text(api_confirm_text);
 
            }
        });
    });
    <?php }  ?>
    function tooltips() {
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
    }

    function formValid() {
        if ($('#schoolsettings').valid()) {
            $('a.proceed').attr('href', $('.proceed').attr('data-ref')).addClass('enabled');
            $('a.proceed2').attr('href', $('.proceed2').attr('data-ref')).addClass('enabled');
        } else {
            $('a.proceed').attr('href', '#').removeClass('enabled');
            $('a.proceed2').attr('href', '#').removeClass('enabled');
        }
    }

    $('button[data-target="#edit-password-modal"]').on('click', function() {
        $('#update-school-password').attr('value', 'Change Password');
        $('#password-error').empty().hide();
    });

    //Update School Account Password
    $('#update-school-password').on('click', function() {
        var e_btn = $(this);

        var e_old = $('#old_password');
        var e_new = $('#new_password');
        var e_con = $('#confirm_password');
        var e_label = $('#password-error');

        e_label.empty();
        e_label.show();
        var s_old = e_old.val();
        if (s_old.length <= 0) {
            e_label.empty().append('Please enter your current password');
            return false;
        }
        s_old.trim();

        var s_new = e_new.val();
        if (s_new.length <= 0) {
            e_label.empty().append('Please enter your new password');
            return false;
        }
        s_new.trim();

        var s_con = e_con.val();
        if (s_con.length <= 0) {
            e_label.empty().append('Please confirm your new password');
            return false;
        }
        s_con.trim();

        if (s_old == s_new) {
            e_label.empty().append('Your new password must be different');
            return false;
        }

        if (s_new !== s_con) {
            e_label.empty().append('Confirmation password did not match');
            return false;
        }

        console.log('Ready to Submit Changed Password');
        e_label.hide();
        e_btn.attr('value', 'Saving...');
        $.ajax({
            type: "POST",
            url: "<?php echo esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")); ?>",
            dataType: 'json',
            data: {
                action: "wushka_school_save_pwd",
                s_pwd: JSON.stringify(s_new),
                s_old: JSON.stringify(s_old),
                s_auth: JSON.stringify('<?php echo $s_auth; ?>')
            },
            success: function(a_return) {
                console.log(a_return);
                e_old.val(null);
                e_new.val(null);
                e_con.val(null);
                if (a_return.status == 1) {
                    e_btn.attr('value', 'Password Saved!');
                    $('button[data-target="#manage-class-settings"]').hide();
                    location.reload();
                } else {
                    e_btn.attr('value', 'Change Password');
                }
                e_label.show();
                e_label.empty().append(a_return.message);
            }
        });
        return true;
    });


    //Standard Input Field Save
    $('select, input:checkbox, input:text').on('change', function() {
        var id = $(this).attr('id');
        if (id == 'old_password' || id == 'new_password' || id == 'confirm_password' || id ==
            'update-school-password') {
            return false;
        }

        var json = {};
        if ($(this).attr('type') === 'checkbox') {
            console.log($(this).attr('id') + ":" + $(this).prop('checked'));
            json[id] = $(this).is(':checked') ? $(this).val() : 'no';
        } else if ($(this).is('select')) {
            console.log($(this).attr('id') + ":" + $(this).val());
            json[id] = $(this).val();
        } else {
            console.log($(this).attr('id') + ":" + $(this).val());
            json[id] = $(this).val();
        }
        //            var years = $("input[type=checkbox]").map(function (i, e) {
        //                return {
        //                    i: $(e).attr("id"),
        //                    v: $("label[for='" + $(e).attr("id") + "']").text(),
        //                    c: $(e).prop("checked")
        //                };
        //            }).get();
        //            console.log(years);

        //json['shool_years'] = years;
        console.log(json);
        if ($(this).valid()) {
            $.ajax({
                type: "POST",
                url: "<?php echo esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")); ?>",
                data: {
                    action: "wushka_school_save_settings",
                    json: JSON.stringify(json)
                }
            });
        }
        formValid();
    });

    $('select#school_tz').on('change', function() {
        var newe_tz = $(this).find('option:selected').attr('value');
        var json = {
            school_tz: new_tz
        };
        console.log('Changing School TimeZone to ' + new_tz);
        $.ajax({
            type: "POST",
            url: "<?php echo esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")); ?>",
            data: {
                action: "wushka_school_save_timezone",
                json: JSON.stringify(json)
            }
        });
    });

    $('#schoolsettings').validate({
        messages: {
            user_terms: '<br>the terms and conditions must be accepted in order to use Wushka'
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('id') === 'user_terms') {
                error.insertAfter($(element).closest('label'));
            } else {
                error.insertAfter(element);
            }
        }
    });
    formValid();
    tooltips();
});
</script>
<?php
include 'dashboard_options.php';
get_footer();
/* ----- END OF FILE ------ */