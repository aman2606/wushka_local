<?php
ob_start();

/*
 * Template Name: Class Login
 */
//Ajax Nonce
$s_validate = wp_create_nonce('get_filtered_results_' . $current_user->ID);

get_header();
?>
<script>
var ajax_url = '<?php echo esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")); ?>';
var s_validate = '<?php echo $s_validate; ?>';
</script>
<?php
/* User role checking.
 * Only teachers, and admin are allowed to access this page.
 */
if( ! (is_user_logged_in() && is_super_admin() || is_admin() || current_user_can('teacher')) ) {
    wp_redirect(home_url());
    exit;
}

//Get Teacher ID
$teacher_id     = $current_user->ID;
$teacher_school = wp_get_object_terms($teacher_id, 'school');
$school_id      = NULL;
if( isset($teacher_school) && ! empty($teacher_school) ) {
    $school_id = $teacher_school[0]->term_taxonomy_id;
}
$a_class_data = build_class_selector($school_id, $teacher_id, 'class-statistics');
$a_classes    = $a_class_data['classes'];
$a_menu       = $a_class_data['menu'];

?>

<div class="container-fluid">
    <div class="row">
        <div class="loading-screen loading-stamp">
            <div class="spin-icon">
                <i class="glyphicon glyphicon-cd x3"></i>
            </div>
            <h2>Loading Students</h2>
        </div>
    </div>
</div>

<div class="container-fluid pt15">
    <div class="row">
        <div class="col-xs-12 text-left">
            <div class="glyphicon-heading">
                <span class="hidden-xs x2 glyphicon glyphicon-keys"></span>
                <h2 class="glyphicon-heading-text text-left colour-white text-left pb0" style="line-height:39px;">
                    Student Login</h2>
                <div class="submodule-right">
                    <?php echo $a_menu; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="class-login-container">
                <p class="class-login_message">Please select the student you wish to log on to this device:</p>
                <?php
                ?>

                <div role="tabpanel">
                    <div class="tab-content">
                        <?php
                        foreach( $a_classes as $i_key => $a_class ) {
                            echo $a_class['top'];
                            echo '<div id="' . $a_class['ID'] . '-container" class="row xs-gutter">';
                            if ($a_class['active'] == 'active') {
                                echo wushka_get_class_login($a_class['ID'], 'class', 1);
                            }
                            echo '</div>';
                            echo $a_class['bottom'];
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- End Body Container-fluid -->

<script>
jQuery(document).ready(function($) {
    $('li.class-list').on('click', function() {
        var i_class = $(this).find('a').attr('href').replace('#', '').replace('-class', '').trim();
        switch_class_tables(i_class);
    });
    $(document).on('click', '.student-box', function(e) {
        e.preventDefault();

        if ($(this).hasClass('loading')) {
            return false;
        }

        $(this).addClass('loading');

        $id = $(this).attr('id');
        loginAsStudent($id);
    });

    function loginAsStudent($id) {
        $.ajax({
            url: '<?php echo get_template_directory_uri() . '/login-as-student.php'; ?>',
            type: "POST",
            data: {
                'id': $id
            },
            success: function(data) {
                window.location.reload(true);
                console.log(data);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
            }
        });
    }

    function switch_class_tables(i_class) {
        $('#' + i_class + '-container').hide();
        show_loading_screen(i_class);
        console.log('Class: ' + i_class);

        $.ajax({
            url: ajax_url,
            type: "POST",
            dataType: 'json',
            data: {
                'action': 'wushka_get_class_login',
                'json': JSON.stringify({
                    'object_id': i_class,
                    'type': 'class'
                }),
                'validate': JSON.stringify(s_validate)
            },
            success: function(a_result) {
                success_callback(a_result, i_class);
                changeDivToButton();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log('Get Filtered Results Ajax Fail:');
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
            },
            complete: function() {
                hide_loading_screen(i_class);
                changeDivToButton();
            }
        });
    }

    function success_callback(a_results, i_class) {
        $('#' + i_class + '-container').html(a_results);
        $('#' + i_class + '-container').show();
        return true;
    }

    function show_loading_screen() {
        var o_screen = $('.loading-screen.loading-stamp');
        // $('#statistics-wrap').addClass('processing');
        o_screen.show().fadeTo(1, 1, function() {
            o_screen.addClass('loading');
        });
    }

    function hide_loading_screen() {
        var o_screen = $('.loading-screen.loading-stamp');
        o_screen.fadeTo(1, 0, function() {
            // $('#statistics-wrap').removeClass('processing');
            o_screen.removeClass('loading').hide();

        });
    }

    $('.table-responsive').removeClass();
});

//Accessibility fixes 
$('.nav li a').each(function() {
    if ($(this).text() == '' || $(this).text() == 'undefined') {
        $(this).remove();
    }
    $(this).removeAttr('aria-controls')
});

function changeDivToButton() {
    var replacementTag = 'button';
    $(".student-box").each(function(i) {
        var outer = this.outerHTML;
        // Replace opening tag
        var regex = new RegExp('<' + this.tagName, 'i');
        var newTag = outer.replace(regex, '<' + replacementTag);
        // Replace closing tag
        regex = new RegExp('</' + this.tagName, 'i');
        newTag = newTag.replace(regex, '</' + replacementTag);
        //Replace 
        $(this).replaceWith(newTag);
    });
}
changeDivToButton();
</script>
<?php
include 'dashboard_options.php';
get_footer();
?>