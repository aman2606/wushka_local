<?php

/*
  Template Name: School teachers
 */
if( ! isset($current_user) ) {
    global $current_user;
}


//Is User Logged In AND is user a school?
if( ! current_user_can('administrator') ) {
    if( ! is_user_logged_in() || ! current_user_can('school') ) {
        //Redirect to Login Page
        wp_redirect(wp_login_url(get_permalink()));
        exit;
    }
}

$school    = $terms = wp_get_object_terms($current_user->ID, 'school');
$school_id = $school[0]->term_taxonomy_id;
$teachers  = wushka_get_teachers(array('school_id' => $school_id));

get_header();
?>

<div class="page-school-teachers container-fluid">

    <div class="row mt15">
        <div class="col-xs-12">
            <h2 class="glyphicon-heading text-left">
                <span class="x2 glyphicon glyphicon-education hidden-xs"></span>
                <span class="glyphicon-heading-text">Step 2: Teacher Users - <?php echo $school[0]->name; ?></span>
                <span class="glyphicon-heading-btn-group">
                    <span class="btn-back-dashboard">
                        <a href="/school-settings" role="button" class="btn btn-primary btn-back-to-dashboard">
                            <span class="glyphicon glyphicon-chevron-left"></span> Previous Step
                        </a>
                    </span>
                    <span class="btn-back-dashboard">
                        <a href="/school-classes" role="button" class="btn btn-primary btn-back-to-dashboard">
                            <span class="glyphicon glyphicon-chevron-right"></span> Go to Step 3
                        </a>
                    </span>
                </span>
            </h2>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-3">
            <div class="panel panel-default" id="add-new-teacher">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-10">
                            <i class="glyphicon glyphicon-education"></i> a. Add New Teacher User
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="well new-teacher">
                        <div class="form-group">
                            <label for="first_name" class="control-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" id="first_name" value=""
                                placeholder="First Name" />
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="control-label">Surname</label>
                            <input type="text" name="last_name" class="form-control" id="last_name" value=""
                                placeholder="Surname" />
                        </div>
                        <div class="form-group">
                            <label for="email" class="control-label">Email / Username</label>
                            <input type="email" name="email" class="form-control" id="email" value=""
                                placeholder="name@example.com" />
                            <em>Once a username is set it cannot be changed. A username must be a valid email
                                address.</em>
                        </div>

                    </div>
                    <div data-toggle="tooltip" data-placement="right" title="add new teacher"
                        style="text-align: center;">
                        <button type="button" class="btn btn-primary btn-lg btn-add-teacher" aria-label="Add Teacher">
                            <span>Add New Teacher</span>
                        </button>
                    </div>
                </div>
                <div class="label-danger" id="teacher-msg"></div>
            </div>
            <div class="legend">
                <div class="col-xs-12 school-admin">
                    <span class="glyphicon glyphicon-lock"></span>
                    : School Administrator account
                </div>
                <div class="col-xs-12 teacher-inactive">
                    <span class="glyphicon glyphicon-hourglass"></span>
                    : Teacher accounts pending activation
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-9">
            <div class="panel panel-default panel-teachers">
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-education"></i> b. Teacher Users
                    <span class="clearfix"></span>
                </div>
                <div class="panel-body">
                    <div class="row teacher-container">
                        <?php
                            foreach( $teachers as $teacher ) {

                                $b_school = FALSE;
                                $s_panel  = NULL;
                                if( user_can($teacher, 'school') ) {
                                    $b_school = TRUE;
                                    $s_panel  = 'panel-school';
                                } else {
                                    //Check if Teacher is Active
                                    $s_code = $teacher->tmp_pwd_verify;
                                    $s_date = $teacher->tmp_pwd_window;
                                    if( isset($s_code, $s_date) && ! empty($s_code) && ! empty($s_date) ) {
                                        $s_panel = 'panel-inactive';
                                    }
                                }


                                ?>
                        <div class="col-lg-4 col-md-4 teacher" data-id="<?php echo $teacher->id_hash; ?>">
                            <div class="panel panel-default panel-teacher <?php echo $s_panel; ?>">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <?php if( $b_school ) { ?>
                                            <span class="glyphicon glyphicon-lock"></span>
                                            <?php } ?>
                                            <?php if( $s_panel == 'panel-inactive' ) { ?>
                                            <span class="glyphicon glyphicon-hourglass" data-toggle="tooltip"
                                                data-placement="left" title="Account has not yet activated"></span>
                                            <?php } ?>
                                            <a href="#" class="editable" data-id="first_name" data-title="First name"
                                                aria-label="Edit First name">
                                                <?php echo $teacher->first_name; ?>
                                            </a>
                                            <a href="#" class="editable" data-id="last_name" data-title="Last name">
                                                <?php echo $teacher->last_name; ?>
                                            </a>
                                        </div>
                                        <?php if( ! $b_school ) { ?>
                                        <div class="col-lg-1 col-md-1 col-sm-1 pull-right" data-toggle="tooltip"
                                            data-placement="left" title="Delete This Teacher">
                                            <button type="button" class="close close-confirm teacher"
                                                aria-label="Delete Teacher">
                                                <span class="glyphicon glyphicon-remove-2"></span>
                                            </button>
                                        </div>
                                        <?php } ?>
                                        <?php if( $s_panel == 'panel-inactive' ) { ?>
                                        <div class="col-lg-1 col-md-1 col-sm-1 pull-right" data-toggle="tooltip"
                                            data-placement="left" title="Resend Activation Email">
                                            <button type="button" class="close resend-email teacher"
                                                aria-label="Resend">
                                                <span class="glyphicon glyphicon-envelope"></span>
                                            </button>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <i class="glyphicon glyphicon-user"></i>
                                            <a class="uneditable" data-id="login">
                                                <?php echo $teacher->user_login; ?>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <i class="glyphicon glyphicon-envelope"></i>
                                            <a href="#" class="editable" data-id="email" data-title="Email address">
                                                <?php echo $teacher->user_email; ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer"></div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="button-group-footer">
            <span class="btn-back-dashboard">
                <a href="/school-settings" role="button" class="btn btn-primary btn-back-to-dashboard">
                    <span class="glyphicon glyphicon-chevron-left"></span> Previous Step
                </a>
            </span>
            <span class="btn-back-dashboard">
                <a href="/school-classes" role="button" class="btn btn-primary btn-back-to-dashboard">
                    <span class="glyphicon glyphicon-chevron-right"></span> Finished: Go to Step 3
                </a>
            </span>
        </div>
    </div>
</div>
<!-- template components -->
<div class="col-lg-4 col-md-4 teacher" id="teacher-template" data-id="<?php echo $teacher->id_hash; ?>">
    <div class="panel panel-default panel-teacher panel-inactive">
        <div class="panel-heading">
            <div class="row">
                <div class="col-lg-10 col-md-10 col-sm-10">
                    <span class="glyphicon glyphicon-hourglass" data-toggle="tooltip" data-placement="left"
                        title="Account has not yet activated"></span>
                    <a class="editable" data-id="first_name"></a>
                    <a class="editable" data-id="last_name"></a>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 pull-right" data-toggle="tooltip" data-placement="left"
                    title="delete this teacher">
                    <button type="button" class="close close-confirm teacher" aria-label="Delete Teacher">
                        <span class="glyphicon glyphicon-remove-2"></span>
                    </button>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 pull-right" data-toggle="tooltip" data-placement="left"
                    title="Resend Activation Email">
                    <button type="button" class="close resend-email teacher" aria-label="Resend">
                        <span class="glyphicon glyphicon-envelope"></span>
                    </button>
                </div>

            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <i class="glyphicon glyphicon-user"></i>
                    <a class="uneditable" data-id="login"></a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <i class="glyphicon glyphicon-envelope"></i>
                    <a class="editable" data-id="email"></a>
                </div>
            </div>
        </div>
        <div class="panel-footer">
        </div>
    </div>
</div>

<div class="modal fade" id="teacher-delete-dialog" tabindex="-1" role="dialog" aria-labelledby="cd-label-delete-teacher"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-xl" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="cd-label-delete-teacher">Delete Teacher?</h3>
            </div>
            <div class="modal-body">
                <p>Confirm that you wish to delete this teacher</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-delete" data-id="">Delete Teacher
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="teacher-resend-dialog" tabindex="-1" role="dialog" aria-labelledby="cd-label"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-xl" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="cd-label">Resend Activation Email?</h3>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-resend" data-id="">
                    Send Email
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="help-media" tabindex="-1" role="dialog" aria-labelledby="help-media" aria-hidden="true">
    <div class="container-wrapper video-wushka">
        <div class="container">
            <div class="row mh200">
                <div class="col-xs-12 text-center dummy-video">
                    <div class="text-center embed-responsive embed-responsive-16by9 video-item-wrapper">
                        <video id="video1" controls="controls" preload="auto" class="wk-bg_b width100p"
                            poster="<?php echo get_template_directory_uri(); ?>/build/video-poster.png">
                            <source src="//cdn1.wushka.com.au/Resources/manage-class-list.mp4" type="video/mp4">
                        </video>
                    </div>
                </div>
                <div class="col-xs-4 col-xs-offset-4 padding-y">
                    <button type="button" class="btn btn-primary btn-block btn-close-video" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
    var s_ajax_url =
        '<?php echo esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php" ), "wp-admin/admin-ajax.php")); ?>';

    function tooltips() {
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
    }

    $('.btn-add-teacher').on('click', function() {
        $('#teacher-msg').hide();
        var e_btn = $(this);
        wushka_button_loading(e_btn, 'Creating...');
        var o_first = $('#first_name');
        var o_last = $('#last_name');
        var o_email = $('#email');

        var s_first = o_first.val().trim();
        var s_last = o_last.val().trim();

        s_first = capitalise_words(s_first);
        s_last = capitalise_words(s_last);

        var json = {
            first_name: s_first,
            last_name: s_last,
            email: o_email.val(),
            school_id: '<?php echo $school[0]->term_id; ?>',
            date: new Date().getTime()
        };

        $.ajax({
            url: s_ajax_url,
            type: "POST",
            data: {
                action: 'wushka_teacher_create',
                json: JSON.stringify(json)
            },
            success: function(data) {
                var result = JSON.parse(data);
                if (result.result === 'success') {
                    $teacher = $('#teacher-template').clone();
                    $teacher.attr('id', '');
                    $teacher.attr('data-id', result.id);
                    $teacher.find('[data-id="first_name"]').text(s_first);
                    $teacher.find('[data-id="last_name"]').text(s_last);
                    $teacher.find('[data-id="login"]').text(o_email.val());
                    $teacher.find('[data-id="email"]').text(o_email.val());
                    $('div.row.teacher-container').append($teacher);
                    o_first.val('');
                    o_last.val('');
                    o_email.val('');
                    wushka_button_finished(e_btn, 'Created!', 'Add New Teacher');
                } else {
                    var o_msg = $('#teacher-msg');
                    o_msg.text(result.msg);
                    o_msg.show();
                    wushka_button_failed(e_btn, 'Error', 'Add New Teacher');
                }
                //
                tooltips();
                editable();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
                wushka_button_failed(e_btn, 'Error', 'Add New Teacher');

            }
        });
    });
    //Toggle Delete Teacher Modal
    $(document).on("click", '.close.close-confirm', function() {
        var o_modal = $('#teacher-delete-dialog');
        o_modal.find('#confirm-delete').attr('data-id', $(this).closest('div.teacher').attr('data-id'));
        o_modal.modal('show');
    });

    //Delete Selected Teacher
    $(document).on("click", '#confirm-delete', function() {
        console.log('confirm deleted:' + $(this).attr('data-id'));
        $('div.row.teacher-container div.teacher[data-id="' + $(this).attr('data-id') + '"]').fadeTo(
            250, 0).remove();
        $.ajax({
            type: "POST",
            url: s_ajax_url,
            data: {
                action: "wushka_teacher_delete",
                json: JSON.stringify({
                    id: $(this).attr('data-id')
                })
            }
        });
        $('#teacher-delete-dialog').modal('hide');

    });

    //Toggle Resend Email Modal
    $(document).on("click", '.close.resend-email', function() {
        var o_modal = $('#teacher-resend-dialog');
        o_modal.find('#confirm-resend').attr('data-id', $(this).closest('div.teacher').attr('data-id'));
        o_modal.find('.modal-body').empty().append(
            '<p>Would you like to send another activation email to this Teacher?</p>');
        o_modal.find('.btn-close').text('Cancel');
        o_modal.find('.btn-primary').show();
        o_modal.modal('show');
    });

    //Resend Teacher Activation Email
    $(document).on("click", '#confirm-resend', function() {
        console.log('confirm email resend:' + $(this).attr('data-id').trim());
        var id_hash = $(this).attr('data-id').trim();
        resend_email(id_hash);
    });

    function resend_email(id_hash) {
        $.ajax({
            type: "POST",
            url: s_ajax_url,
            dataType: 'json',
            data: {
                action: 'wushka_teacher_activation_email_resend',
                id_hash: JSON.stringify(id_hash)
            },
            success: function(a_return) {
                if (typeof a_return.status !== 'undefined' && a_return.status == 1) {
                    var o_modal = $('#teacher-resend-dialog');
                    o_modal.find('.modal-body').fadeTo(200, 0, function() {
                        o_modal.find('.modal-body').empty().append(
                            '<p>Email has been successfully sent</p>');
                        o_modal.find('.modal-body').fadeTo(200, 1);
                    });

                    o_modal.find('.btn-close').text('Close');
                    o_modal.find('.btn-primary').hide();
                } else {
                    resend_email_error();
                }
            },
            error: function() {
                resend_email_error();
            }
        });
    }

    function resend_email_error() {
        var o_modal = $('#teacher-resend-dialog');
        o_modal.find('.modal-body').fadeTo(200, 0, function() {
            o_modal.find('.modal-body').empty().append('<p>There was an Error Sending Your Email</p>');
            o_modal.find('.modal-body').fadeTo(200, 1);
        });
    }

    function editable() {
        $(".editable").editable({
            type: 'text',
            emptytext: 'Not Set',
            success: function(response, value) {
                var i_teacher = $(this).closest('div.teacher').attr('data-id').trim();
                var s_field = $(this).attr('data-id').trim();
                var json = {
                    id: i_teacher
                };

                json[s_field] = value.trim();

                $.ajax({
                    type: "POST",
                    url: s_ajax_url,
                    data: {
                        action: "wushka_teacher_save",
                        json: JSON.stringify(json)
                    },
                    dataType: 'json',
                    success: function(o_return) {
                        if (typeof o_return !== 'undefined') {
                            if (o_return.status != 1) {
                                $('[data-id="' + i_teacher + '"]').find(
                                    '.editable[data-id="' + s_field + '"]').click();
                                $('.editable-popup').find('h3').append(
                                    '<span class="pull-right" style="color:red;">' +
                                    o_return.msg + '</span>');
                            }
                        }
                    }
                });
            }
        });
    }

    //Breaks a String apart and capitalises first character of each word
    function capitalise_words(s_string) {
        var a_words = s_string.split(' ');
        var a_new = [];

        for (var ii = 0; ii < a_words.length; ++ii) {
            a_new.push(a_words[ii].charAt(0).toUpperCase() + a_words[ii].slice(1));
        }

        return a_new.join(' ').trim();
    }

    editable();
    tooltips();
});
</script>
<?php
include 'dashboard_options.php';
//Add Footer
get_footer();