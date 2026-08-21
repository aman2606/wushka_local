<?php
/*
* Template Name: Quiz shortcode page
*/

global $wpdb, $current_user;


$newLabelPhases = [
    '5p5a-sound-families-long-vowels',
    '5p5b-sound-families-consonants-short-vowels',
    '5p5c-sound-families-r-controlled',
    '6-a-spelling-affixes'
];

$s_id = 0;

$isRead = true;
if (get_query_var('id') != null && get_query_var('id') != '') {
    $s_id = sanitize_text_field(get_query_var('id'));

    $actual_link = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $a_quizid    = explode('/quiz/', $actual_link);
    $check_id        = array_pop($a_quizid);
    $check_id        = str_replace('/', "", $check_id);

    if ($check_id != $s_id) {
        redirect_404();
    }
}

function isBookRead($userId, $bookId)
{

    global $wpdb;

    $readData = $wpdb->prepare("SELECT `read_id` FROM `" . $wpdb->prefix . "lessonzone_reading_analytics_reading_instance` WHERE `essis_resource_id` = %d AND `user_id` = %d AND `completed`= 1 AND `duration` > 0 ", $bookId, $userId);
    $readId = $wpdb->get_var($readData);
    if (empty($readId)) {

        return false;
    }
    return true;
}


$post_meta = $wpdb->prepare("SELECT `post_id` FROM `" . $wpdb->prefix . "postmeta` where `meta_key` ='wushka_quiz_id' and `meta_value` = %d", $s_id);
$post_ID = $wpdb->get_var($post_meta);

$bookSlug = false;

if (!empty($post_ID)) {
    $bookSlug = get_the_permalink($post_ID);
}


if (current_user_can('student') || current_user_can('administrator') || current_user_can('school') || current_user_can('teacher')) {
    $s_quiz = $current_user->quizzes;

    if (current_user_can('student') && $s_quiz == 'no') {
        if (!empty($post_ID)) {
            $s_link = 'ebook/' . get_post_field('post_name', $post_ID);
            wp_redirect(home_url('/') . $s_link);
        } else {
            wp_redirect(home_url());
        }
        exit();
    }

    if (current_user_can('student')) {

        $bookId =  get_post_meta($post_ID, 'esiss_resource_id', true);

        $isRead = isBookRead(get_current_user_id(), $bookId);
    }

    //If quiz meta = school only. Quiz can only be done in school hours
    //if quiz meta = home only, only outside of school hours
    if ($s_quiz == 'school only' || $s_quiz == 'home only') {
        //Get School Time
        $o_class     = wushka_get_class($current_user->class);
        $i_school    = $o_class->school_id;
        $school_meta = get_option('taxonomy_' . $i_school);
        if (isset($school_meta['school_hash'])) {
            $i_hash = $school_meta['school_hash'];

            $b_active_school = FALSE;
            $o_school        = get_user_by_hash($i_hash);
            if ($o_school !== FALSE) {
                $a_hours         = wushka_get_school_hours($current_user->class);
                $b_valid_student = wushka_is_school_hours($a_hours, $o_school);

                if (
                    ($s_quiz == 'school only' && !$b_valid_student) ||
                    ($s_quiz == 'home only' && $b_valid_student)
                ) {
                    $s_link = 'ebook/' . get_post_field('post_name', $post_ID);
                    wp_redirect(home_url('/') . $s_link);
                    exit();
                }
            }
        }
    }
} else {
    wp_redirect(home_url());
    exit;
}

$terms = get_the_terms($post_ID, 'phonics-phase');

$phaseSlug = false;

if ($terms && isset($terms[0])) {
    $phaseSlug = $terms[0]->slug;
}


get_header();


?>

<div class="quiz-shortcode-wrapper padding-y grad-radial">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="glyphicon-heading text-left"><span class="x2 glyphicon glyphicon-conversation hidden-xs"></span> <span class="glyphicon-heading-text">Quiz</span></h2>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <?php if ($isRead) { ?>
                <div id="quiz-col" class="col-xs-12 col-md-10 col-md-offset-1 col-lg-6 col-lg-offset-3">
                    <?php echo do_shortcode('[slickquiz id=]'); ?>
                    <div id='quizbackbtn' class="hidden">
                        <button onclick="location.href = '<?= get_permalink($post_ID) ?>' ">Finish</button>
                    </div>
                </div>
            <?php } else { ?>

                <div class="col-md-12">
                    <div class="alert alert-info">
                        Please read the book first. <a href="<?php echo $bookSlug ?>"><strong>Click here</strong></a> to read the book.
                    </div>
                </div>

            <?php } ?>

        </div>
        <div class="quiz-results-display"></div>
        <?php
        $quiz_id        = $s_id;

        $nonce = wp_create_nonce("wushka_quiz_score_summary");
        $ajax_url = admin_url('admin-ajax.php?action=wushka_quiz_score_summary&quiz_id=' . $quiz_id);


        // Button 
        $s_query = 'SELECT post_id FROM ' . $wpdb->prefix . 'postmeta WHERE meta_key = %s AND meta_value = %s';
        $i_id    = $wpdb->get_var(
            $wpdb->prepare(
                $s_query,
                'wushka_quiz_id',
                $quiz_id
            )
        );
        $s_link = get_permalink($i_id);

        ?>


    </div>
</div>

<script>
    //Load quiz after window content is fully loaded
    $(".quizName").text('<?= get_the_title($post_ID); ?>');
    var loadingButton = '<button tabIndex="-1" class="button loadingbtn disabled pull-left" style="background-color:#D64242 !important; opacity:0.5">Loading Quiz</button>';

    $('.quizHeader .buttonWrapper .startQuiz').before(loadingButton);

    $(".button.startQuiz").addClass("hidden");
    $(".quizResults").addClass("hidden");

    $(window).on("load", function() {
        $('.loadingbtn').css('opacity', '0');
        $(".button.hidden").removeClass("hidden");
        $(".quizResults").removeClass("hidden");
    });


    //load ends here
    <?php
    //Get user meta
    $user_id = get_current_user_id();
    $key = 'allow_book_view';
    $user_meta = get_user_meta($user_id, $key);
    if (isset($user_meta[0])) {
        $user_meta = $user_meta[0];
    }
    if ((!empty($user_meta) && $user_meta == 'Yes') || current_user_can("school") || current_user_can("teacher") || current_user_can("administrator")) :
        //Button to open relevant quiz
    ?>
        $('.startQuiz').on('click', function() {
            $(".questions .question a.nextQuestion").after(
                '<a href="<?= $s_link; ?>" target="_blank" class="button" role="button" style="background-color:#D64242 !important; margin-bottom:15px;">Read Book Again <i class="fa fa-book"></i></a>'
            );
            $('#quizbackbtn').removeClass('hidden');
        });
        <?php
    endif;

    $key = 'quiz_narration';
    $narration_meta = get_user_meta($user_id, $key);
    if (isset($narration_meta[0])) {
        $narration_meta = $narration_meta[0];
    }
    //dd($narration_meta);
    if (!empty($narration_meta)) :
        if ($narration_meta == 'No') :

        ?>
            var quizNarrationState = false;
    <?php
        endif;
    endif;
    ?>

    //Quiz Summary
    var triggered = false;
    $('.quiz-results-display').hide();
    $(document).ajaxSuccess(function() {
        if (triggered == false) {
            $.ajax({
                url: "<?= $ajax_url; ?>",
                type: "post",
                data: {
                    nonce: "<?= $nonce; ?>"
                },
                success: function(data) {
                    triggered = true;
                    $('.quiz-results-display').html(data).show();
                    $('.quiz-results-display').appendTo('.quizResultsCopy');
                    $("#quiz-col").removeClass("col-md-10 col-md-offset-1 col-lg-6 col-lg-offset-3")
                        .addClass("col-md-12 col-lg-8 col-lg-offset-2");
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.responseText);
                }
            });
        }
    });


    setTimeout(function() {
        //Accessibility Fixes
        $(".quizArea input").each(function(i) {
            if (!$(this).attr('id')) {
                $(this).attr('id', 'quiz-input-' + i);
                $(this).parent().find('label').attr('for', 'quiz-input-' + i);
                if ($(this).parent().find('label').text() == '') {
                    $(this).parent().find('label').html('<span class="sr-only">Quiz Input</span>')
                }
            }
        });

        $(".question h3").each(function() {
            $(this).text($(this).text());
        });

        $('.answers').wrap('<fieldset />');
        $('.answers').prepend('<legend class="sr-only">Select appropriate answer</legend>');


    }, 1000);


    var newLabelPhases = [
        '5p5a-sound-families-long-vowels',
        '5p5b-sound-families-consonants-short-vowels',
        '5p5c-sound-families-r-controlled',
        '6-a-spelling-affixes',
        '2c2-letter-sounds',
        '3c2-phonics',
        '4c2-blends',
        '5c2-vowel-sounds',
        '6c2-spelling',
        '2c3-letter-sounds',
        '3c3-phonics',
        '4c3-blends',
        '5c3-vowel-sounds',
        '6c3-spelling'
    ];

    var phaseSlug = '<?= $phaseSlug ?>';
</script>


<?php
include 'dashboard_options.php';
get_footer();
?>