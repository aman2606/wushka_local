<?php
  /* Template Name: Helpful Resources */
  get_header();
  $extension = pathinfo($_SERVER['SERVER_NAME'], PATHINFO_EXTENSION);
?>
<link rel="stylesheet" href="/wp-content/themes/WushkaTheme/css/modaal.min.css" type="text/css">



<div class="helpful-resources-wrap">
    <div class="">
        <div class="b1">
            <picture>
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b-green-orange.webp"
                    type="image/webp">
                <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-green-orange.png"
                    type="image/jpeg">
                <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-green-orange.png" alt="">
            </picture>
        </div>
        <div class="b2">
            <picture>
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b2-purple-s2.webp"
                    type="image/webp">
                <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b2-purple-s2.png"
                    type="image/jpeg">
                <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b2-purple-s2.png" alt="">
            </picture>
        </div>
        <div class="b3">
            <picture>
                <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b-orange.webp"
                    type="image/webp">
                <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-orange.png"
                    type="image/jpeg">
                <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-orange.png" alt="">
            </picture>
        </div>
        <div class="b4">
            <picture>
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b-purple-orange.webp"
                    type="image/webp">
                <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-purple-orange.png"
                    type="image/jpeg">
                <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-purple-orange.png" alt="">
            </picture>
        </div>
        <div class="b5">
            <picture>
                <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b-blue.webp"
                    type="image/webp">
                <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-blue.png"
                    type="image/jpeg">
                <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-blue.png" alt="">
            </picture>
        </div>
    </div>
    <div id="hero">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <h2 class="hero-title">Helpful Resources</h2>
                </div>
            </div>
        </div>
    </div>
    <section class="container-wrapper">
        <div class="getting-started">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <p class="para">On this page you’ll find a range of support resources for Wushka, including our
                            comprehensive privacy policy. The ‘Getting Started’ section has everything you need to get
                            up and running with Wushka and the reading level correlation chart and curriculum links
                            demonstrate how easily Wushka can be integrated into your teaching program. For a more
                            detailed overview of Wushka for your team or school staff, check out our introductory
                            webinars.</p>
                        <p class="para m0imp">If you are a parent looking for resources, check out our <a
                                href="/parent-resources" aria-label="Parent Resources Here (New Window)"
                                class="parent-resources" target="_blank">Parent Resources Here.</a></p>
                    </div>
                </div>
                <div class="row getting-wrap">
                    <h2 class="sub-heading">Getting Started</h2>
                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <picture>
                            <source
                                srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/program-coordinator-easy-setup-guide.webp"
                                type="image/webp">
                            <source
                                srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/program-coordinator-easy-setup-guide.jpg"
                                type="image/jpeg">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/program-coordinator-easy-setup-guide.jpg"
                                class="getting-thumb" alt="Program Coordinator Set Up Guide" />
                        </picture>
                        <h3 class="sub-title">Program Coordinator Set Up Guide</h3>
                        <p class="para">This guide will support the Program or Literacy Coordinator to set up teachers,
                            classes and students.</p>
                        <a href="#"
                            onclick="javascript:window.open('<?= getCdnLink(); ?>/Resources/Wushka-Program-Coordinator-Setup-Guide_v2.pdf', '_blank'); return false;"
                            aria-label="Program Coordinator Set Up Guide (New Window)"
                            class="btn btn-blue download-btn">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/download.svg"
                                alt="" class="download-icon" />
                            Download
                        </a>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <picture>
                            <source
                                srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/teacher-user-easy-setup-guide.webp"
                                type="image/webp">
                            <source
                                srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/teacher-user-easy-setup-guide.jpg"
                                type="image/jpeg">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/teacher-user-easy-setup-guide.jpg"
                                class="getting-thumb" alt="Teacher User Set Up Guide" />
                        </picture>
                        <h3 class="sub-title">Teacher User Set Up Guide</h3>
                        <p class="para">Using this guide teachers can upload students to their class, generate student
                            logins, manage class list settings and create reading groups.</p>
                        <a href="#"
                            onclick="javascript:window.open('<?= getCdnLink(); ?>/Resources/Wushka-Teacher-Setup-Guide_v2.pdf', '_blank'); return false;"
                            aria-label="Teacher User Set Up Guide (New Window)" class="btn btn-blue download-btn"><img
                                src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/download.svg"
                                alt="" class="download-icon" /> Download</a>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <picture>
                            <source
                                srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/my-school-login-details.webp"
                                type="image/webp">
                            <source
                                srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/my-school-login-details.jpg"
                                type="image/jpeg">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/my-school-login-details.jpg"
                                class="getting-thumb" alt="Student Log In Letter" />
                        </picture>
                        <h3 class="sub-title">Student Log In Letter</h3>
                        <p class="para">This editable and printable PDF lets teachers add student usernames and
                            passwords to a letter to be sent home to parents.</p>
                        <a href="#"
                            onclick="javascript:window.open('<?= getCdnLink(); ?>/Resources/wushka-student_user_login_details_model.pdf', '_blank'); return false;"
                            aria-label="Student Log In Letter (New Window)" class="btn btn-blue download-btn"><img
                                src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/download.svg"
                                alt="" class="download-icon" /> Download</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="watch-our-video">
            <div class="container">
                <h2 class="sub-heading">Watch Our Videos Below to Help you <br />Get Started with Wushka</h2>
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <div class="wushka-video">
                            <div class="video-wrapper">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <a href="//www.youtube.com/embed/8WeMKIexQ7o" id="activate-your-account" class="video btn-play-video">
                                        <span class="sr-only">Play Activating Your Account</span>
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/yt-play-icon.svg"
                                            alt="" />
                                    </a>
                                    <a href="#" onclick="event.preventDefault(); javascript:$('#activate-your-account').click();" tabindex="-1" class="btn-play-video-layer">
                                        <span class="sr-only">Play: Activating Your Account</span>
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/activating-your-account.png"
                                            alt="" class="video-thumb" />
                                    </a>
                                    <!-- <iframe class="embed-responsive-item" src="//www.youtube.com/embed/8WeMKIexQ7o"
                                        aria-label="Activating your account" title="Activating your account"
                                        allowfullscreen></iframe> -->
                                </div>
                            </div>
                            <div class="blue-box">
                                <div class="video-title">Activating Your Account</div>
                                <div class="video-caption">Video 1</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <div class="wushka-video">
                            <div class="video-wrapper">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <a href="//www.youtube.com/embed/jSjwSkQ4iZQ" id="manage-class-lists" class="video btn-play-video">
                                        <span class="sr-only">Play Setting Up for Independent and Guided Reading using 'Manage Class Lists'</span>
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/yt-play-icon.svg"
                                            alt="" />
                                    </a>
                                    <a href="#" onclick="event.preventDefault(); javascript:$('#manage-class-lists').click();" tabindex="-1" class="btn-play-video-layer">
                                        <span class="sr-only">Play: Setting Up for Independent and Guided Reading using 'Manage Class Lists'</span>
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/manage-class-lists.png"
                                            class="video-thumb" alt="" />
                                    </a>
                                    <!-- <iframe class="embed-responsive-item" src="//www.youtube.com/embed/jSjwSkQ4iZQ"
                                        aria-label="Setting Up for Independent and Guided Reading using 'Manage Class Lists"
                                        title="Setting Up for Independent and Guided Reading using 'Manage Class Lists'"
                                        allowfullscreen></iframe>-->
                                </div> 
                            </div>
                            <div class="blue-box">
                                <div class="video-title">Setting Up for Independent and Guided <br />Reading using
                                    'Manage Class Lists'</div>
                                <div class="video-caption">Video 2</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <div class="wushka-video">
                            <div class="video-wrapper">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <a href="//www.youtube.com/embed/brp7kA79Akg" id="manage-reading-groups" class="video btn-play-video">
                                        <span class="sr-only">Play Setting Up Reading Groups Using 'Manage Reading Groups'</span>
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/yt-play-icon.svg"
                                            alt="" />
                                    </a>
                                    <a href="#" onclick="event.preventDefault(); javascript:$('#manage-reading-groups').click();" tabindex="-1" class="btn-play-video-layer">
                                        <span class="sr-only">Play: Setting Up Reading Groups Using 'Manage Reading Groups'</span>
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/manage-reading-groups.png"
                                            class="video-thumb" alt="" />
                                    </a>
                                    <!-- <iframe class="embed-responsive-item" src="//www.youtube.com/embed/brp7kA79Akg"
                                        aria-label="Setting Up Reading Groups Using Manage Reading Groups"
                                        title="Setting Up Reading Groups Using Manage Reading Groups"
                                        allowfullscreen></iframe> -->
                                </div>
                            </div>
                            <div class="blue-box">
                                <div class="video-title">Setting Up Reading Groups Using <br />'Manage Reading Groups'
                                </div>
                                <div class="video-caption">Video 3</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="privacy-wrap text-center">
            <div class="container">
                <h2 class="sub-heading">Privacy</h2>
                <p class="para first-child"><strong>We take privacy seriously!</strong></p>

                <?php if($extension == 'nz'): ?>

                <p class="para">Modern Teaching Aids Pty Ltd CN 305 775 and each of its related bodies corporate
                    (MTA, we, us and our) is committed to managing personal information in accordance with the Privacy
                    Principles under the Privacy Act 1988 (Cth), the Privacy Principles under the Privacy Act 1993 (NZ)
                    and in accordance with other applicable privacy laws.</p>
                <p class="para">We only collect student information which is necessary to provide students with access
                    to the resources, products and services that we supply. This includes collecting personal
                    information such as their name, year level, class number and teacher name. If you choose to use
                    pseudonyms for student names or only give student first names, you will still have full access to
                    Wushka and all if its features.</p>
                <p class="para">Wushka uses third-party Amazon Web Services (AWS) data storage facilities that are
                    located in the Asia-Pacific Region.</p>

                <?php else: ?>

                <p class="para">Modern Teaching Aids Pty Ltd ACN 000 628 786 and each of its related bodies corporate
                    (MTA, we, us and our) is committed to managing personal information in accordance with the
                    Australian Privacy Principles under the Privacy Act 1988 (Cth), the Privacy Principles under the
                    Privacy Act 1993 (NZ) and in accordance with other applicable privacy laws.</p>
                <p class="para"> We only collect student information which is necessary to provide students with access
                    to the resources, products and services that we supply. This includes collecting personal
                    information such as their name, year level, class number and teacher name. If you choose to use
                    pseudonyms for student names or only give student first names, you will still have full access to
                    Wushka and all if its features.</p>
                <p class="para"> Wushka uses third-party Amazon Web Services (AWS) data storage facilities that are
                    located in Sydney, Australia.</p>

                <?php endif; ?>

                <p class="para"> To review our full privacy policy click below.</p>
                <div class="clearfix"></div>
                <a href="/privacy" class="btn btn-blue privacy-btn">See Privacy Policy</a>

            </div>
        </div>
        <div class="supporting-documents">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-3 col-lg-3"></div>
                    <div class="col-sm-12 col-md-9 col-lg-9">
                        <h2 class="sub-heading">Supporting Documents</h2>
                    </div>
                </div>
                <div class="row first-child">
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <picture>
                            <source
                                srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/reading-level-correlation-chart.webp"
                                type="image/webp">
                            <source
                                srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/reading-level-correlation-chart.jpg"
                                type="image/jpeg">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/reading-level-correlation-chart.jpg"
                                alt="Reading Level Correlation Chart" class="chart-thumb" />
                        </picture>
                    </div>
                    <div class="col-sm-12 col-md-9 col-lg-9">
                        <h3 class="sub-title">Reading Level Correlation Chart</h3>
                        <p class="para">Wushka Levelled Library reading boxes are carefully levelled from Magenta (Level
                            1-2) through to Black (Levels 31+) and complement any classroom reading program, with
                            levelling aligned to all common reading systems. Click below to download our reading level
                            correlation chart.</p>
                        <a href="#"
                            onclick="javascript:window.open('<?= getCdnLink(); ?>/Resources/wushka-reading-level-correlation-chart.pdf', '_blank'); return false;"
                            aria-label="Reading Level Correlation Chart (New Window)"
                            class="btn btn-blue download-btn"><img
                                src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/download.svg"
                                alt="" class="download-icon" /> Download</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <picture>
                            <source
                                srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/national-curriculum-links.webp"
                                type="image/webp">
                            <source
                                srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/national-curriculum-links.jpg"
                                type="image/jpeg">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/national-curriculum-links.jpg"
                                alt="National Curriculum Links" class="chart-thumb" />
                        </picture>
                    </div>
                    <div class="col-sm-12 col-md-9 col-lg-9">
                        <h3 class="sub-title">National Curriculum Links</h3>
                        <p class="para">Click download below to see how Wushka links to the National English Curriculum.
                        </p>
                        <a href="#"
                            onclick="javascript:window.open('<?= getCdnLink(); ?>/Resources/National-English-Curriculum-Links.pdf', '_blank'); return false;"
                            aria-label="National Curriculum Links (New Window)" class="btn btn-blue download-btn"><img
                                src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/download.svg"
                                alt="" class="download-icon" /> Download</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="watch-our-video video-section">
            <div class="container">
                <h2 class="sub-heading">Videos</h2>
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <div class="wushka-video">
                            <div class="video-wrapper">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <a href="//www.youtube.com/embed/8Ml-AzYJoxM" id="what-is-wushka" class="video btn-play-video">
                                        <span class="sr-only">Play What Is Wushka?</span>
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/yt-play-icon.svg"
                                            alt="" />
                                    </a>
                                    <a href="#" onclick="event.preventDefault(); javascript:$('#what-is-wushka').click();" tabindex="-1" class="btn-play-video-layer">
                                        <span class="sr-only">Play: What Is Wushka?</span>
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/what-is-wushka.png?ver=1.1"
                                            alt="" class="video-thumb" />
                                    </a>
                                    <!-- <iframe class="embed-responsive-item" src="//www.youtube.com/embed/8Ml-AzYJoxM"
                                        aria-label="What Is Wushka?" title="What Is Wushka?" allowfullscreen></iframe> -->
                                </div>
                            </div>
                            <div class="blue-box">
                                <div class="video-title">What Is Wushka?</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <div class="wushka-video">
                            <div class="video-wrapper">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <a href="//www.youtube.com/embed/MkC3VNxpC5E" id="wushka-webinar" class="video btn-play-video">
                                        <span class="sr-only">Play Wushka Webinar</span>
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/yt-play-icon.svg"
                                            alt="" />
                                    </a>
                                    <a href="#" onclick="event.preventDefault(); javascript:$('#wushka-webinar').click();" tabindex="-1" class="btn-play-video-layer">
                                        <span class="sr-only">Play: Wushka Webinar</span>
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/wushka-webinar.png"
                                            class="video-thumb" alt="" />
                                    </a>
                                    <!-- <iframe class="embed-responsive-item" src="//www.youtube.com/embed/MkC3VNxpC5E"
                                        aria-label="Wushka Webinar" title="Wushka Webinar" allowfullscreen></iframe> -->
                                </div>
                            </div>
                            <div class="blue-box">
                                <div class="video-title">Wushka Webinar</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <div class="wushka-video">
                            <div class="video-wrapper">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <a href="//www.youtube.com/embed/S-Ou7KcneBI" id="st-hilda" class="video btn-play-video">
                                        <span class="sr-only">Play to See Why St Hilda’s Loves Using Wushka</span>
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/yt-play-icon.svg"
                                            alt="" />
                                    </a>
                                    <a href="#" onclick="event.preventDefault(); javascript:$('#st-hilda').click();" tabindex="-1" class="btn-play-video-layer">
                                        <span class="sr-only">Play: See Why St Hilda’s Loves Using Wushka</span>
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/see-why-st-hilda-love-using-wushka.png"
                                            class="video-thumb" alt="" />
                                    </a>
                                    <!-- <iframe class="embed-responsive-item" src="//www.youtube.com/embed/S-Ou7KcneBI"
                                        aria-label="See Why St Hilda’s Loves Using Wushka"
                                        title="See Why St Hilda’s Loves Using Wushka" allowfullscreen></iframe> -->
                                </div>
                            </div>
                            <div class="blue-box">
                                <div class="video-title">See Why St Hilda’s Loves Using Wushka</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="/wp-content/themes/WushkaTheme/js/modaal.min.js"></script>

<script>
jQuery(document).ready(function($) {
    /* Video - Modaal Settings */
    jQuery('.video').modaal({
        type: 'video',
        overlay_close: true
    });
    /* Video - Custom button */
    jQuery(".btn-play-video")
        .on('mouseenter', function() {
            jQuery(this).css("backgroundColor", "#ff001d");
        })
        .on('mouseleave', function() {
            jQuery(this).css("backgroundColor", "#4a4947");
        });
    jQuery(".btn-play-video-layer")
        .on('mouseenter', function() {
            jQuery(this).parent().find(".btn-play-video").css("backgroundColor", "#ff001d");
        })
        .on('mouseleave', function() {
            jQuery(this).parent().find(".btn-play-video").css("backgroundColor", "#4a4947");
        });

    if (window.matchMedia("(max-width:767px)").matches) {
        $(".hero-copy").hide();
    }
});
</script>

<?php get_footer(); ?>