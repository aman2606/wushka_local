<!DOCTYPE html>
<?php $lang = (get_language_attributes() == 'lang="en-AU"') ? 'lang="en"' : get_language_attributes();  ?>
<html <?= $lang; ?>>
<?php
$current_user        = wp_get_current_user();
$wushka_template_url = get_template_directory_uri();

if (!is_user_logged_in() && (is_page('tips-tricks') || is_page('referrals'))) {
    wp_redirect('/');
    exit();
}

if (!isset($_SESSION)) {
    session_start();
}

function temp_meta_details()
{
    global $wpdb;
    $post_id = get_the_id();
    $table_name = $wpdb->prefix . "yoast_indexable";

    $title = '';
    $description = '';
    $updated_at = '';
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


    $sql = 'SELECT `title`, `description`, `updated_at` FROM ' . $table_name . ' WHERE object_id = %d ORDER BY `id` DESC';
    $results = $wpdb->get_results(
        $wpdb->prepare($sql, $post_id)
    );


    if (is_array($results) && !empty($results)) {
        if (isset($results[0])) {
            $title = $results[0]->title;
            $description = $results[0]->description;
            $updated_at = $results[0]->updated_at;
        }
    }

    if (empty($title)) {
        $title = get_the_title() . ' | ' . get_bloginfo('name');
    }

    if (is_post_type_archive('product_release')) {
        $title = 'What&apos;s New in Wushka | ' . get_bloginfo('name');
    }

    if (is_404()) {
        $title = 'Page not found | ' . get_bloginfo('name');
    }

    $ePost = get_post(get_the_ID());
    if(!empty($ePost)){
      if($ePost->post_type == 'educational_resource' ){
            $seo_fields = get_field('seo_fields',$ePost->ID);
            if(!empty($seo_fields['seo_title'])){

                $title = $seo_fields['seo_title'];

            }

            if(!empty($seo_fields['meta_description'])){

                $description = $seo_fields['meta_description'];

            }
      }
    }
    
    echo '
        <title>' . $title . '</title>
        <meta name="description" content="' . $description . '" />
        <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
        <link rel="canonical" href="' . esc_url($url) . '" />
        <meta property="og:locale" content="en-AU" />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="' . $title . '" />
        <meta property="og:description" content="' . $description . '" />
        <meta property="og:url" content="' . esc_url($url) . '" />
        <meta property="og:site_name" content="' . get_bloginfo('name') . '" />
        <meta property="article:modified_time" content="' . $updated_at . '" />
        <meta name="twitter:card" content="summary" />
    ';
}

?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msvalidate.01" content="B7ED223AB106964EF6B6CC0E1483AA00" />
    <?php temp_meta_details(); ?>
    <link rel="shortcut icon" href="<?php echo $wushka_template_url; ?>/favicon.ico">

    <?php wp_head(); ?>
    <script>
        var _sf_startpt = new Date().getTime();
    </script>

    <link rel="stylesheet" href="<?php echo $wushka_template_url; ?>/css/bootstrap.css">
    <!-- Fonts -->
    <link href="<?php echo $wushka_template_url; ?>/css/glyphicons.css" rel="stylesheet">
    <link href="<?php echo $wushka_template_url; ?>/css/glyphicons-bootstrap.css" rel="stylesheet">
    <link href="<?php echo $wushka_template_url; ?>/css/glyphicons-social.css" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo $wushka_template_url; ?>/style.css?ver=6.01">
    <link rel="stylesheet" href="<?php echo $wushka_template_url; ?>/css/dataTable.css">
    <link rel="stylesheet" href="<?php echo $wushka_template_url; ?>/css/bootstrap-editable.css">
    <link rel="stylesheet" href="<?php echo $wushka_template_url; ?>/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/bookshelves.css?ver=1.01">

    <script src="<?php echo $wushka_template_url; ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo $wushka_template_url; ?>/js/jquery.dataTable.min.js"></script>
    <script src="<?php echo $wushka_template_url; ?>/js/bootstrap-editable.min.js"></script>

    <script src="<?php echo $wushka_template_url; ?>/js/ask-me.js"></script>




    <?php if (!is_page('manage-class-list')) { ?>
        <link rel="stylesheet" href="<?php echo $wushka_template_url; ?>/css/ekko-lightbox.min.css">
        <link rel="stylesheet" href="<?php echo $wushka_template_url; ?>/build/mediaelementplayer.min.css" />


        <script src="<?php echo $wushka_template_url; ?>/js/ekko-lightbox.min.js"></script>
        <script src="<?php echo $wushka_template_url; ?>/js/velocity.min.js"></script>
        <script src="<?php echo $wushka_template_url; ?>/js/velocity.ui.js"></script>
        <script src="<?php echo $wushka_template_url; ?>/build/mediaelement-and-player.min.js"></script>
        <script src="<?php echo $wushka_template_url; ?>/js/modaal.min.js"></script>

        <script src="<?php echo $wushka_template_url; ?>/js/jquery.sticky.js"></script>
        <script src="<?php echo $wushka_template_url; ?>/js/bootstrap-datepicker.min.js"></script>

        <!-- ============================== Morris.js Charts ========================== -->
        <link rel="stylesheet" href="<?php echo $wushka_template_url; ?>/css/morris.css">
        <script src="<?php echo $wushka_template_url; ?>/js/raphael-min.js"></script>
        <script src="<?php echo $wushka_template_url; ?>/js/morris.min.js"></script>
    <?php } ?>

    <script>
        jQuery(document).ready(function($) {
            //console.log = function() {};
            $(document).on('click', 'li.class-switch', function() {
                var e_btn = $(this).find('a');
                var i_class = e_btn.attr('href').replace('#', '').replace('-class', '').trim();
                var s_archive = 'class';
                ajax_set_class_session(i_class, s_archive);
                first_student = $('#' + e_btn.attr('href').replace('#', '')).find('.list-student')[0];
                $(first_student).click();
            });

            $(document).on('click', 'a.class-switch', function() {
                var i_class = $(this).attr('href').replace('#', '').replace('-class', '').trim();
                var s_archive = 'class';
                ajax_set_class_session(i_class, s_archive);
            });
            $(document).on('click', '.switch-table', function() {
                var e_btn = $(this);
                var i_class = null;
                var o_active = $('.class-list.class-switch.active');
                if (o_active.length > 0) {
                    var o_class = o_active.find('a');
                    if (o_class.length <= 0) {
                        o_class = o_active;
                    }

                    i_class = o_class.attr('href').replace('#', '').replace('-class', '').trim();
                }
                var s_archive = null;
                if (e_btn.hasClass('to-class')) {
                    s_archive = 'class';
                } else if (e_btn.hasClass('to-archive')) {
                    s_archive = 'archive';
                }
                ajax_set_class_session(i_class, s_archive);
            });
            $(document).on('click', 'a.list-group-item.list-student', function() {
                var e_btn = $(this);
                var i_user = $(this).attr('data-id').trim();
                ajax_set_student_session(i_user);
            });

            function ajax_set_class_session(i_class, s_archive) {
                $.ajax({
                    url: '<?php echo get_template_directory_uri() . '/set-class.php'; ?>',
                    type: "POST",
                    data: {
                        'set_class_session': i_class,
                        'set_archive_session': s_archive
                    },
                    datatype: "json",
                    success: function(data) {
                        console.log('Class Set: ' + i_class);
                        console.log('Class Archive: ' + s_archive);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(xhr.status);
                        console.log(xhr.responseText);
                        console.log(thrownError);
                    }
                });
            }

            function ajax_set_student_session(i_user) {
                $.ajax({
                    url: '<?php echo get_template_directory_uri() . '/set-class.php'; ?>',
                    type: "POST",
                    data: {
                        'set_user_session': i_user
                    },
                    datatype: "json",
                    success: function(data) {
                        //console.log('User Set: '+i_user);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(xhr.status);
                        console.log(xhr.responseText);
                        console.log(thrownError);
                    }
                });
            }
            <?php
            if (is_user_logged_in() && user_can($current_user, "student") && isset($_SESSION['parent_login']) && $_SESSION['parent_login'] == "true" && isset($_SESSION['parent_hash'])) {
            ?>
                $('.btn-parent-login').on('click', function(e) {
                    e.preventDefault();
                    $id = $(this).attr('data-id');
                    $pw = $('#parent-pw').val();
                    loginAsStudent($id, $pw);
                });
                /*To write in a database*/
                function loginAsStudent($id, $pw) {
                    $.ajax({
                        url: '<?php echo get_template_directory_uri() . '/login-as-student.php'; ?>',
                        type: "POST",
                        data: {
                            'id': $id,
                            'pw': $pw,
                            'date': new Date().getTime()
                        },
                        success: function(data) {
                            window.location = '<?php echo get_home_url(); ?>';
                            console.log(data);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log(xhr.status);
                            console.log(xhr.responseText);
                            console.log(thrownError);
                        }
                    });
                }
            <?php } ?>
            <?php
            /* begin - MSEL-49 */
            if (is_user_logged_in() && (user_can($current_user, "school") && user_can($current_user, "teacher"))) {
            ?>
                $(document).on('click', '.btn-school', function() {
                    selectDashboard('school');
                });
                $(document).on('click', '.btn-teacher', function() {
                    selectDashboard('teacher');
                });

                $(document).on('click', '.is_school', function(e) {

                    e.preventDefault();

                    var href = $(this).prop('href');
                    selectDashboard('school', href);
                });

                if ($('.nav-btn-school-classes').length > 0) {

                    $('.btn-library').addClass('is_school');
                }

                function selectDashboard(dashboard, redirect_url = '<?php echo get_home_url(); ?>') {
                    $.ajax({
                        url: '<?php echo get_template_directory_uri() . '/switch-dashboard.php'; ?>',
                        type: "POST",
                        data: {
                            'type': dashboard
                        },
                        success: function(data) {
                            window.location = redirect_url;
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log(xhr.status);
                            console.log(xhr.responseText);
                            console.log(thrownError);
                        }
                    });
                }
            <?php }  /* end - MSEL-49 */ ?>
        });


        $(document).ready(function() {
            $(".dropdown-toggle").on('focus', function() {
                $('.dropdown').removeClass('open');
                var dropdownMenu = $(this).closest('.dropdown').children(".dropdown-menu");
                if (!dropdownMenu.is(":visible")) {
                    dropdownMenu.parent().toggleClass("open");
                }
            });
            $(".dropdown-toggle").on('click', function() {
                $('.open').removeClass('open');
                $(this).parent('.dropdown').toggleClass('open');
            });
            $("header a,.mta-bar-top a ").not(".dropdown-toggle").not(".dropdown-menu a").on('focus', function() {
                $('.dropdown').removeClass('open');
            });
            $('.dropdown').on('hide.bs.dropdown', function(e) {
                e.preventDefault();
            });
            $('body').on('click', function(e) {
                if (!$('li.dropdown').is(e.target) &&
                    $('li.dropdown').has(e.target).length === 0 &&
                    $('.open').has(e.target).length === 0
                ) {
                    $('li.dropdown').removeClass('open');
                }
            });
        });
    </script>


    <style>
        <?php if (is_user_logged_in() && is_super_admin() || is_admin() || user_can($current_user, "student") || user_can($current_user, "teacher") || user_can($current_user, "school")) {
        ?>@media screen and (min-width:1024px) {
            .navbar-nav .btn-default {
                background-color: #00bef2 !important;
                border-color: #00bef2 !important;
                border-radius: 5px;
            }

            <?php if (is_page('decodable')) {
            ?>.navbar-nav .btn-default.btn-library[href*="decodable"] {
                background-color: #7E57C2 !important;
                border-color: #7E57C2 !important;
            }

            <?php
            }

            ?><?php if (is_page('levelled')) {
                ?>.navbar-nav .btn-default.btn-library[href*="levelled"] {
                background-color: #AA83EE !important;
                border-color: #AA83EE !important;
            }

            <?php
                }

            ?>
        }

        .navbar-nav .dropdown-menu .btn-default {
            color: #000;
            background: none !important;
        }

        .hamburger-inner,
        .hamburger-inner::before,
        .hamburger-inner::after {
            background-color: #5E5E5E;
        }

        <?php
        } elseif (is_page('decodable') || is_page('levelled')) {

        ?>.hamburger-inner,
        .hamburger-inner::before,
        .hamburger-inner::after {
            background-color: #5E5E5E;
        }

        @media screen and (max-width: 1023px) {

            .nav.navbar-nav .dropdown:last-child,
            .nav.navbar-nav .dropdown:nth-last-child(2),
            .nav.navbar-nav .dropdown:nth-last-child(3) {
                margin: 0 20%;
            }
        }

        @media screen and (min-width:1024px) {
            .navbar-nav .btn-default {
                background-color: #00bef2 !important;
                border-color: #00bef2 !important;
                border-radius: 5px;
            }

            .navbar-nav ul.dropdown-menu .btn-default {
                background-color: #fff !important;
                border-color: #fff !important;
            }
        }

        <?php
        } elseif (!is_page('decodable') && !is_page('levelled') && !is_page('login') && !is_singular('ebook')) {
        ?>

        /* .navbar-nav .btn-default{color: #fff} */
        <?php
        } else {
        ?>@media screen and (min-width:1024px) {
            .navbar-nav .btn-default {
                background-color: #00bef2 !important;
                border-color: #00bef2 !important;
                border-radius: 5px;
            }
        }

        .navbar-nav .dropdown-menu .btn-default {
            color: #000;
            background: none !important;
            border: none !important;
        }

        <?php
        }

        ?>
    </style>

    <script>
        jQuery(function($) {
            $('noscript').remove();
            $('#Country__c').before('<label for="Country__c" class="hidden">Country</label>');
            $('#recordType').before('<label for="recordType" class="hidden">MTA Website Request</label>');
            $('#wk-form-modal .modal-copy__title').replaceWith($('<h1 class="modal-copy__title">' + $(
                '#wk-form-modal .modal-copy__title').html() + '</h1>'));
            $('#wk-form-modal .tip').replaceWith($('<h2 class="tip">' + $('#wk-form-modal .tip').html() + '</h2>'));
            $('#form-submission-modal .modal-copy__title').replaceWith($('<h1 class="modal-copy__title">' + $(
                '#form-submission-modal .modal-copy__title').html() + '</h1>'));
            <?php if (is_user_logged_in() ) 
                  { 
                    if(!user_can($current_user,OPEN_HOUSE_CUSTOMER)){ ?>

                            $('#wk-form-modal').empty();

                  <?php  
                   }
                 }  ?>
        });
    </script>
</head>

<body <?php if (!is_user_logged_in() && !is_page('decodable') && !is_page('levelled') && !is_page('login') && !is_singular('ebook')) {
            body_class("double-bar");
        } else {
            body_class();
        } ?>>
    <?php get_template_part('analyticstracking'); ?>

    <?php /*get_template_part('template-parts/content', 'skipnav');*/ ?>

    <?php if (!is_user_logged_in() && !is_page('decodable') && !is_page('levelled') && !is_page('login')  && !is_singular('ebook')) { ?>
        <div class="mta-bar-top " style="display:none;padding-bottom: 1rem;">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <a href="<?php echo home_url() ?>" class="multi-logo" title="Wushka">
                            <img alt="wushka logo" src="https://cdn1.wushka.com.au/public/2024/11/15165117/wushka_mta_header_transparent.webp" style="position: relative;margin-top: -30px;max-width: 450px;" />
                        </a>
                    </div>
                    <div class="col-xs-6 top-nav-btns hidden-xs">
                        <a class="navbar-btn btn btn-primary subscription-offer" data-toggle="modal" data-target="#wk-form-modal" href="#"><?= wushka_cta_button_text(); ?></a>
                        <?php if (!is_user_logged_in()) { ?>
                            <a class="navbar-btn btn btn-primary btn-login hidden-xs" href="<?php echo esc_url(get_permalink(get_page_by_title('Login'))); ?>"><i class="fa fa-user"></i> Log In</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->
    <?php } ?>
    <!-- Fixed navbar -->
    <div class="wrapper-main">
        <h1 class="sr-only"><?php wp_title(); ?></h1>
        <?php if (is_user_logged_in() && is_super_admin() || is_admin() || user_can($current_user, "student") || user_can($current_user, "teacher") || user_can($current_user, "school")) { ?>
            <header class="navbar navbar-wushka" id="top" style="background-color:#fff !important">
                <div class="container-fluid">
                <?php } elseif (!is_page('decodable') && !is_page('levelled') && !is_page('login')  && !is_singular('ebook')) { ?>
                    <header class="navbar navbar-wushka" id="top">
                        <div class="container">
                        <?php } elseif (is_page('login')) { ?>
                            <header class="navbar navbar-wushka" id="top" style="border-bottom: 1px solid #e5e5e5;box-shadow: 0 0 10px 4px #e5e5e5;">
                                <div class="container">
                                <?php } else { ?>
                                    <header class="navbar navbar-wushka" id="top" style="background-color:#fff !important">
                                        <div class="container-fluid">
                                        <?php } ?>

                                        <div class="navbar-header">

                                            <?php
                                            if (is_user_logged_in() && is_super_admin() || is_admin() || user_can($current_user, "student") || user_can($current_user, OPEN_HOUSE_CUSTOMER) || user_can($current_user, "teacher") || user_can($current_user, "school")) {
                                            ?>
                                                <a class="navbar-brand logo-wushka" href="<?php echo home_url() ?>">
                                                    <img alt="wushka logo" src="https://cdn1.wushka.com.au/public/2024/11/15165117/wushka_mta_header_transparent.webp" style="position: relative;margin-top: -30px;top: 10px;max-width: 450px;">
                                                </a>
                                            <?php } else if (!is_page('decodable') && !is_page('levelled') && !is_page('login')  && !is_singular('ebook')) { ?>

                                            <?php } else { ?>
                                                <a class="navbar-brand slogan" href="<?php echo home_url() ?>">
                                                    <img alt="wushka logo" src="https://cdn1.wushka.com.au/public/2024/11/15165117/wushka_mta_header_transparent.webp" style="position: relative;margin-top: -30px;top: 10px;max-width: 450px;" />
                                                </a>
                                            <?php } ?>
                                            <button class="hamburger hamburger--squeeze navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
                                                <span class="sr-only">Hamburger Menu</span>
                                                <span class="hamburger-box">
                                                    <span class="hamburger-inner"></span>
                                                </span>
                                            </button>
                                        </div>
                                        <?php if (is_user_logged_in()) { ?>
                                            <div class="user-welcome-wrapper">
                                                <div class="user-avatar"><?php echo get_avatar($current_user->ID, 50) ?>
                                                </div>
                                                <div class="user-welcome">Welcome back, <span class="username"><?php echo $current_user->first_name ?>!</span>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <nav class="collapse navbar-collapse bs-navbar-collapse" aria-label="Site Navigation">
                                            <div class="hidden">
                                                <p class="sr-only">Site Navigation</p>
                                            </div>



                                            <ul class="nav navbar-nav navbar-right">
                                                <?php if (!is_user_logged_in()) { ?>
                                                    <li class="dropdown">
                                                        <a href="#" class="navbar-btn dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false">
                                                            Libraries <span class="caret"></span>
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="/levelled-library" class="navbar-btn btn btn-default btn-navbar-wushka btn-library"><img class="nav-logo" src="<?= getCdnLink();  ?>/Resources/Levelled-Library.png" alt=""> Levelled Library</a></li>
                                                            <li><a href="/decodable-library" class="navbar-btn btn btn-default btn-navbar-wushka btn-library"><img class="nav-logo" src="<?= getCdnLink();  ?>/Resources/Decodable-Library-smaller.png" alt=""> Decodable Library</a></li>
                                                        </ul>
                                                    </li>
                                                    <li class="dropdown"><a href="#" class="navbar-btn dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false">Reading Boxes <span class="caret"></span></a>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="<?php echo esc_url(get_permalink(get_page_by_title('Levelled Library'))); ?>" class="navbar-btn btn btn-default btn-navbar-wushka btn-library"><img class="nav-logo" src="<?= getCdnLink();  ?>/Resources/Levelled-Library.png" alt=""> Levelled eBooks</a></li>
                                                            <li><a href="<?php echo esc_url(get_permalink(get_page_by_title('Decodable Library'))); ?>" class="navbar-btn btn btn-default btn-navbar-wushka btn-library"><img class="nav-logo" src="<?= getCdnLink();  ?>/Resources/Decodable-Library-smaller.png" alt=""> Decodable eBooks</a></li>
                                                        </ul>
                                                    </li>
                                                <?php } ?>
                                                <?php
                                                if ((is_user_logged_in() && hasLevelledAccess()) || is_admin()) { ?>
                                                    <li class="dropdown">
                                                        <a href="<?php echo esc_url(get_permalink(get_page_by_title('Levelled Library'))); ?>" class="navbar-btn btn btn-default btn-navbar-wushka btn-library <?= (isset($_SESSION['wushka_decodable_teacher']) && $_SESSION['wushka_decodable_teacher'] == true) ? 'disable-dashboard-block' : ''; ?>">
                                                            <span class="sr-only">Top menu: Levelled Library</span>
                                                            Levelled Library
                                                        </a>
                                                    </li>
                                                <?php }
                                                if ((is_user_logged_in() && hasDecodableAccess()) || is_admin() || (isset($_SESSION['wushka_decodable_teacher']) && $_SESSION['wushka_decodable_teacher'] == true)) { ?>
                                                    <li class="dropdown">
                                                        <a href="<?php echo esc_url(get_permalink(get_page_by_title('Decodable Library'))); ?>" class="navbar-btn btn btn-default btn-navbar-wushka btn-library">
                                                            <span class="sr-only">Top menu: Decodable
                                                                Library</span>Decodable Library
                                                        </a>
                                                    </li>
                                                <?php }
                                                if (!is_user_logged_in()) { ?>
                                                    <li class="dropdown"><a href="<?php echo esc_url(get_permalink(get_page_by_title('How It Works'))); ?>" class="navbar-btn btn btn-default btn-navbar-wushka btn-library">How
                                                            It Works</a>
                                                    </li>
                                                <?php }
                                                if (!is_user_logged_in()) { ?>
                                                    <li class="dropdown"><a href="/pricing" class="navbar-btn btn btn-default btn-navbar-wushka btn-library">Pricing</a>
                                                    </li>
                                                <?php }
                                                if (!is_user_logged_in()) {
                                                ?>
                                                    <li class="dropdown"><a href="<?php echo esc_url(get_permalink(get_page_by_title('Helpful Resources'))); ?>" class="navbar-btn btn btn-default btn-navbar-wushka btn-library">Resources</a>
                                                    </li>
                                                    <li class="dropdown">
                                                        <a href="<?php echo esc_url(get_permalink(get_page_by_title('Contact Us'))); ?>" class="navbar-btn btn btn-default btn-navbar-wushka btn-library">Contact Us</a>
                                                    </li>
                                                <?php } ?>
                                                <?php if (!is_user_logged_in()) { ?>
                                                    <li class="dropdown hidden-sm hidden-md hidden-lg"><a class="navbar-btn btn btn-default subscription-offer" data-toggle="modal" data-target="#wk-form-modal" href="#"><?= wushka_cta_button_text(); ?></a>
                                                    </li>
                                                    <li class="dropdown hidden-sm hidden-md hidden-lg"><a class="navbar-btn btn btn-default btn-login" href="<?php echo esc_url(get_permalink(get_page_by_title('Login'))); ?>"><i class="fa fa-user" aria-hidden="true"></i> Log In</a>
                                                    </li>
                                                <?php }
                                                if (!is_user_logged_in() && (is_page('decodable') || is_page('levelled'))) { ?>
                                                    <li class="dropdown hidden-xs">
                                                        <a class="navbar-btn btn btn-default" href="#" onclick="javascript:window.location='<?php echo esc_url(get_permalink(get_page_by_title('Login'))); ?>'; return false;">
                                                            <i class="fa fa-user" aria-hidden="true"></i> Log In
                                                        </a>
                                                    </li>
                                                    <?php }
                                                //School User "Switch To" Header Buttons
                                                if (is_user_logged_in() && (current_user_can("school") && current_user_can("teacher"))) {

                                                    /* begin - MSEL-49 */

                                                    $pc_page_slugs = [
                                                        'school_manage-class-list.php',
                                                        'school_dashboard_tiles.php',
                                                        'school_settings.php',
                                                        'school_teachers.php',
                                                        'school_classes.php',
                                                        'school_dashboard.php',
                                                        'school_notifications.php'
                                                    ];

                                                    $pageFileName = get_page_template_slug();

                                                    if (!empty($pageFileName) && $pageFileName !== 'school_teacher_selection.php' && !isset($_SESSION['dashboard_selection'])) {

                                                        if (in_array($pageFileName, $pc_page_slugs)) {


                                                            $_SESSION['dashboard_selection'] = 'school';
                                                        } else {

                                                            $_SESSION['dashboard_selection'] = 'teacher';
                                                        }
                                                    } elseif ((empty($pageFileName) && !isset($_SESSION['dashboard_selection']))) {

                                                        $_SESSION['dashboard_selection'] = 'teacher';
                                                    }
                                                    /* end - MSEL-49 */

                                                    if (isset($_SESSION['dashboard_selection']) && !empty($_SESSION['dashboard_selection'])) {
                                                        if ($_SESSION['dashboard_selection'] == "school") {
                                                    ?>
                                                            <li class="dropdown">
                                                                <button class="navbar-btn btn btn-default btn-navbar-wushka btn-teacher">Switch
                                                                    to
                                                                    Teacher
                                                                </button>
                                                            </li>
                                                        <?php
                                                        } else if ($_SESSION['dashboard_selection'] == "teacher") {
                                                        ?>
                                                            <li class="dropdown">
                                                                <button class="navbar-btn btn btn-default btn-navbar-wushka btn-school">Switch
                                                                    to
                                                                    School
                                                                </button>
                                                            </li>
                                                <?php
                                                        }
                                                    }
                                                } ?>
                                                <?php if (is_user_logged_in()) { ?>
                                                    <li class="dropdown"><a class="navbar-btn btn btn-default btn-logout" href="<?php echo esc_url(get_permalink(get_page_by_title('Logout'))); ?>">Log
                                                            Out</a>
                                                    </li>
                                                <?php } ?>

                                            </ul>

                                        </nav>
                                        </div>
                                    </header>
                                    <?php
                                    if (is_user_logged_in() && user_can($current_user, "student") && isset($_SESSION['parent_login']) && $_SESSION['parent_login'] == "true" && isset($_SESSION['parent_hash'])) {
                                    ?>
                                        <div class="modal fade" id="parent-login" tabindex="-1" role="dialog" aria-labelledby="pl-title" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h3 class="modal-title" id="pl-title">Back to Parent</h3>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="parent-pw">Enter parent password</label>
                                                            <input type="password" name="parent-pw" id="parent-pw" value="">
                                                        </div>
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary btn-parent-login" data-id="<?php echo $_SESSION['parent_hash'] ?>">Go
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
