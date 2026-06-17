<style>
    .notice-container .notice-content img {
        width: 400px;
        height: 300px;
    }
    .notice-container{
        max-height: 80%;
    }
</style>
<?php
$show_notice = false;
$currentUserNotices = [];
$cookie_name = 'wushka_notice';
function array_keys_exist(array $needles, array $haystack)
{
    $return = false;
    foreach ($needles as $needle) {
        if (isset($haystack[$needle])) {
            $return = true;
        }
    }
    return $return;
}
$latest_notices = wp_get_recent_posts(['post_type' => 'notice', 'post_status' => 'publish'], OBJECT);

$isUserLoggedIn = is_user_logged_in();

$currentSchoolSlug = false;

$user = false;
$isPc = false;

if ($isUserLoggedIn) {
    $user = wp_get_current_user();
    $userSchool = wushka_get_user_school($user->ID);
    $schoolObject = get_term($userSchool);
    if (!empty($schoolObject)) {
        $currentSchoolSlug = $schoolObject->slug;
    }

    if (current_user_can('school')) {
        $isPc = true;
    }
}



if (!empty($latest_notices)) {

    foreach ($latest_notices as $notice) {

        $display_on = get_field('display_on_dashboard', $notice->ID);
        $template = get_field('template', $notice->ID);
        $logged_in = get_field('logged_in', $notice->ID);


        if ($isUserLoggedIn == $logged_in) {

            if ($display_on == 'all') {
                $display_on = ['school', 'teacher', 'student'];
            } elseif ($display_on == 'school-teacher') {
                $display_on = ['school'];
            } elseif ($display_on == 'teacher') {
                $display_on = ['teacher'];
            } else {
                $display_on = [$display_on];
            }

            if ($user && array_keys_exist($display_on, $user->allcaps)) {

                if ($isPc && sizeof($display_on) == 1 && $display_on[0] == 'teacher') {
                    continue;
                }

                if ($template == 1) {
                    if (empty(get_current_user_rollover_meta())) {

                        array_push($currentUserNotices, ['template' => 1, 'cookie_name' => 'wushka_notice', 'notice_id' => $notice->ID, 'file' => 'template-rollover.php']);
                    }
                }

                if ($template == 2) {

                    $cookie_name = 'wushka_demo_notice';
                    //Show to specific school slug only
                    $school_slug = get_field('school_slug', $notice->ID);
                    if (empty($school_slug)) {
                        array_push($currentUserNotices, ['template' => 2, 'cookie_name' => 'wushka_demo_notice', 'notice_id' => $notice->ID, 'file' => 'template-demo.php']);
                    } else {

                        $school_slug_array = array_map('trim', explode(',', $school_slug));
                        if (in_array($currentSchoolSlug, $school_slug_array)) {
                            array_push($currentUserNotices, ['template' => 2, 'cookie_name' => 'wushka_demo_notice', 'notice_id' => $notice->ID, 'file' => 'template-demo.php']);
                        }
                    }
                }

                if ($template == 3) {
                    $cookie_name = 'wushka_subscribed_notice';
                    //Show to specific school slug only
                    $s_school_slug = get_field('not_subscribed_school_slug', $notice->ID);
                    if (!empty($s_school_slug)) {

                        $school_slug_array = array_map('trim', explode(',', $s_school_slug));
                        if (!in_array($currentSchoolSlug, $school_slug_array)) {
                            array_push($currentUserNotices, ['template' => 3, 'cookie_name' => 'wushka_subscribed_notice', 'notice_id' => $notice->ID, 'file' => 'template-subscribed.php']);
                        }
                    }
                }

                /* begin - MSEL-116 */

                if ($template == 4) {
                    $cookie_name = 'wushka_registration_notice';
                    //Show to specific school slug only
                    $s_school_slug = get_field('not_subscribed_school_slug', $notice->ID);


                    $school_slug_array = array_map('trim', explode(',', $s_school_slug));
                    if (!in_array($currentSchoolSlug, $school_slug_array)) {
                        array_push($currentUserNotices, ['template' => 4, 'cookie_name' => 'wushka_registration_notice', 'notice_id' => $notice->ID, 'file' => 'template-registration.php']);
                    }
                }
                /* end - MSEL-116 */

                if ($template == 5) {
                    $cookie_name = 'wushka_default_notice';
                    //Show to specific school slug only
                    array_push($currentUserNotices, ['template' => 5, 'cookie_name' => 'wushka_default_notice', 'notice_id' => $notice->ID, 'file' => 'template-default.php']);
                }
            }
        }
    }
}

if (!empty($currentUserNotices)) {

    foreach ($currentUserNotices as $n) {

        $template = $n['template'];
        $notice_id = $n['notice_id'];

        if ($n['cookie_name'] && isset($_COOKIE[$n['cookie_name']])) {

            $cookie_date = encrypt_decrypt('decrypt', sanitize_text_field($_COOKIE[$n['cookie_name']]));

            $date = date('Y-m-d');

            if ($date !== $cookie_date) {

                require("template-notice/{$n['file']}");
                break;
            }
        } else {

            require("template-notice/{$n['file']}");
            break;
        }
    }
}

// if ($show_notice) {
//     if ($template == 1) {
//         require('template-notice/template-rollover.php');
//     } elseif ($template == 2) {
//         require('template-notice/template-demo.php');
//     } elseif ($template == 3) {
//         require('template-notice/template-subscribed.php');
//     }
// }
