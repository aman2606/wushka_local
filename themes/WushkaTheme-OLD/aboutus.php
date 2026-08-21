<?php
  /* Template Name: Aboutus Template*/
  get_header();
?>

<link rel="stylesheet" href="/wp-content/themes/WushkaTheme/css/modaal.min.css" type="text/css">
<div class="aboutus-wrap">
    <div class="bubbles">
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
    </div>
    <div id="hero">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <h2 class="hero-title">About Us</h2>
                </div>
            </div>
        </div>
    </div>
    <section class="container-wrapper" id="main-content">
        <div class="container">
            <div class="row about-block">
                <div class="col-sm-12 col-md-6 col-lg-7">
                    <p class="para">Brought to you by MTA, Wushka was developed with the aim of providing a digital
                        reading program that supports best practice in the everyday teaching of reading, reflecting the
                        reality of literacy teaching with printed books, reading groups and take-home readers as well as
                        monitoring and assessment in classrooms across Australia and New Zealand. </p>
                    <p class="para">Our pedigree is strong; we have the privilege of access to world-leading levelled
                        literacy resources, originally developed by Learning Media. Learning Media has been a leading
                        New Zealand and international literacy publisher for over 100 years and has an enviable
                        reputation for bringing high-quality, innovative printed literacy resources to the education
                        community. </p>
                    <p class="para">In 2020, in an exciting new chapter for Wushka, we partnered with Beanstalk Books to
                        bring you the Wushka Decodable Library. Beanstalk Books was established by children’s author
                        Anna Kirschberg and is a division of Junior Learning, which was founded in 2009 by Kirschberg
                        and educational neuroscientist Dr. Duncan Milne. Together they have a global mission to support
                        teachers and parents by developing unique educational resources that are backed by
                        evidence-based research. </p>
                    <p class="para">Our team is a collaborative community of publishing folk, graphic designers and web
                        developers who are passionate about education and supporting teachers in getting the best
                        literacy learning outcomes for their students. </p>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-5">
                    <picture class="mobile">
                        <source
                            srcset="<?php echo get_template_directory_uri(); ?>/img/about-us/webp/img-about-us-wushka.webp"
                            type="image/webp">
                        <source
                            srcset="<?php echo get_template_directory_uri(); ?>/img/about-us/img-about-us-wushka.jpg"
                            type="image/jpeg">
                        <img src="<?php echo get_template_directory_uri(); ?>/img/about-us/img-about-us-wushka.jpg"
                            alt="About us">
                    </picture>
                </div>
            </div>
            <div class="watch-our-video">
                <h2 class="sub-heading">Videos</h2>
                <div class="video-block">
                    <div class="wushka-video">
                        <div class="video-wrapper">
                            <div class="embed-responsive embed-responsive-16by9">
                                <a href="//www.youtube.com/embed/8Ml-AzYJoxM" class="video btn-play-video" id="what-is-wushka" aria-label="What is Wushka?">
                                    <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/yt-play-icon.svg" alt="Youtube" />
                                </a>
                                <a href="#" onclick="event.preventDefault(); javascript:$('#what-is-wushka').click();" tabindex="-1" class="btn-play-video-layer"><img
                                        src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/activating-your-account.png"
                                        alt="Activate" class="video-thumb" /></a>
                                <!-- <iframe class="embed-responsive-item" src="//www.youtube.com/embed/8Ml-AzYJoxM"
                                    title="What is Wushka" allowfullscreen></iframe> -->
                            </div>
                        </div>
                        <div class="blue-box">
                            <div class="video-title">What Is Wushka?</div>
                        </div>
                    </div>
                    <div class="wushka-video">
                        <div class="video-wrapper">
                            <div class="embed-responsive embed-responsive-16by9">
                                <a href="//www.youtube.com/embed/S-Ou7KcneBI" class="video btn-play-video" id="st-hilda" aria-label="St Hilda love using Wushka">
                                
                                  <img
                                        src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/yt-play-icon.svg"
                                        alt="Youtube" /></a>
                                <a href="#" onclick="event.preventDefault(); javascript:$('#st-hilda').click();" tabindex="-1" class="btn-play-video-layer">
                                <img
                                        src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/see-why-st-hilda-love-using-wushka.png"
                                        alt="See Why St Hilda love using Wushka" class="video-thumb" /></a>
                                <!-- <iframe class="embed-responsive-item" src="//www.youtube.com/embed/S-Ou7KcneBI"
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