<?php 
/**
* Template Name: Rollover template
*/

get_header();

$postId = get_the_ID();

$video_embed_url = get_field('video_embed_url',$postId);
$how_to_guid_pdf = get_field('how_to_guid_pdf',$postId);
$webinar_link = get_field('webinar_link',$postId);

?>
<link rel="stylesheet" href="/wp-content/themes/WushkaTheme/css/modaal.min.css" type="text/css">

<section class="rollover-container">
    <div class="hero-container">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-1 col-xs-5 ">
                    <h1>Wushka Data<br/>Rollover</h1>
                </div>
                <div class="col-md-6 col-xs-7">
                <img src="<?= get_template_directory_uri(); ?>/img/rollover.svg" alt="">
                </div>
            </div>
        </div>
    </div>
    <div class="main-container">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <h2 class="text-center mb30 pb0">
                        Welcome to rollover for Wushka!
                    </h2>
                    <p class="text-center mb50">Rollover is simply the process of rolling over your data and setting up classes for a new academic year. <br/> All Wushka schools will need to perform a rollover to ensure students and teachers are assigned to their correct classes for the new academic year. Don’t worry, it’s easy!</p>
                    <p class="mb30">Before you get started, you’ll need:</p>
                    <ul>
                        <li><strong>Access to a Program Coordinator account:</strong> You must be a Program Coordinator to perform a rollover.</li>
                        <li><strong>Class Lists for the new academic year:</strong> Have new Class Lists handy (including new kindys!)</li>
                        <li><strong>Focus time:</strong> About 15-60 minutes focus time (depending on your school size)</li>
                    </ul>
                    <p class="text-center mb50 mt50"><strong>Ok, let’s go….</strong></p>


                    <div class="row mb60">
                        <div class="col-sm-4 col-xs-4 col-xsl-12">
                            <a href="<?php echo $video_embed_url; ?>" class="video rollover-block-btn">
                                <img src="<?= get_template_directory_uri(); ?>/img/rollover-watch.svg" alt="">
                                <strong>WATCH</strong>
                                <p>View the <br/>step-by-step <br/>video</p>
                            </a>
                        </div>
                        <div class="col-sm-4 col-xs-4 col-xsl-12">
                            <a href="<?= $how_to_guid_pdf; ?>" class="rollover-block-btn" target="_blank">
                                <img src="<?= get_template_directory_uri(); ?>/img/rollover-read.svg" alt="">
                                <strong>READ</strong>
                                <p>Download <br/>the how-to <br/>guide</p>
                            </a>
                        </div>
                        <div class="col-sm-4 col-xs-4 col-xsl-12">
                            <a href="<?php echo $webinar_link; ?>" class="rollover-block-btn" target="_blank">
                                <img src="<?= get_template_directory_uri(); ?>/img/rollover-learn.svg" alt="">
                                <strong>LEARN</strong>
                                <p>Have a <br/>Wushka expert<br/>teach you how</p>
                            </a>
                        </div>
                    </div>

                    <!-- div class="text-center mb50">
                        <p class="primary-text">
                            <strong>
                                Completed your rollover?
                            </strong>
                        </p>
                        <p>
                            Let us know you’ve completed your rollover by selecting <span class="primary-text">“click here”</span> on the pop-up tile located on your Program Coordinator dashboard.
                        </p>
                        <img src="<?= get_template_directory_uri(); ?>/img/notice-2023.png" alt="" style="width: 350px">
                    </div -->

                    <div class="text-center">
                        <p class="primary-text"><strong>Need support? We’re here to help.</strong></p>
                        <p>
                            Simply email MTA Online Learning Support: <a href="mailto:onlinelearning@teaching.com.au">onlinelearning@teaching.com.au</a> <br/>
                            <!-- or call <a href="tel:1800251497">1800 251 497</a> to speak to an eLearning representative. -->
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script src="<?= get_template_directory_uri(); ?>/js/modaal.min.js"></script>

<script>
jQuery(document).ready(function($) {
    /* Video - Modaal Settings */
    jQuery('.video').modaal({
        type: 'video'
    });
});
</script>
 
<?php get_footer(); ?>