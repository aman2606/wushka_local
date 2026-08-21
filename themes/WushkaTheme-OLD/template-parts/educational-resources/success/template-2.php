<?php
$successPage = get_field('success_page',$id);
$heading = "Cheers! Here's <br>Your Download";
$subHeading = get_the_title();
$downloadButtonText = "Download Now";
$trialHeading = "Start Your 30-Day Wushka Trial";
$trialDescription = "";
$trialButtonText = 'Start a Trial';

if(isset($successPage) && !empty($successPage)){

    $heading = $successPage['heading'];
    $subHeading = $successPage['sub_heading'];
    $downloadButtonText = $successPage['download_button_text'];
    $trialHeading = $successPage['start_trial_heading'];
    $trialDescription = $successPage['trial_description'];
    $trialButtonText = $successPage['trial_button_text'];

}
?>
<div class="educational-resource resource-single template2 success">

<div class="breadcrumb-container rotate-container">
    <div class="container">
        <div class="breadcrumb-content" style="background-color:<?= $args['background_color'] ?>;">
            <div class="row rotate-fix-content">
                <div class="col-md-push-6 col-md-6 col-sm-12">
                    <h1 class="white success-heading">
                        <?php echo $heading; ?>
                    </h1>
                    <h4 style="color:#fff" class="mt30 success-subheading"><?php  echo $subHeading; ?></h4>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <div class="clearfix cheers">
    <div class="container">
        <div class="col-md-6 hidden-sm hidden-xs">
            <img src="<?= get_template_directory_uri() . '/img/strategy-bookmarks-success.png'; ?>" alt="">
        </div>
        <div class="col-md-6 col-sm-12 content mt50">
            <h1>Cheers! Here’s <br />Your Download</h1>
            <p class="mt30 mb30"><?php the_title(); ?></p>
            <a href="<?= esc_url(remove_query_arg(['AWSAccessKeyId', 'Expires', 'Signature'], get_field('downloadable_file'))); ?>" class="btn btn-primary" target="_blank" download>Download Now</a>
        </div>
        <div class="col-sm-12 visible-sm visible-xs">
            <img src="<?= get_template_directory_uri() . '/img/strategy-bookmarks-success.png'; ?>" alt="">
        </div>
    </div>
</div> -->

<div class="container">
    <div class="col-md-6 breadcrumb-thumbnail">
        <img src="<?= esc_url(remove_query_arg(['AWSAccessKeyId', 'Expires', 'Signature'], get_the_post_thumbnail_url(get_the_ID(), 'full'))); ?>" class="img-responsive" alt="">
    </div>
    <div class="resource-container rotate-container">
        <div class="row resource-bg rotate-fix-content">
            <div class="col-md-12 content">
                <div class="downlodable_assets_container">
                    <?php
                    $downloadableAssets = get_field('downloadable_assets', get_the_ID());

                    if (!empty($downloadableAssets)) {  ?>
                        <div class="downlodable_assets">
                            <?php
                            foreach ($downloadableAssets as $da) {
                                if (!empty($da)) {
                            ?>
                                    <div class="asset">
                                        <img src="<?php echo $da['preview_image'] ?>" />
                                        <h4><strong><?php echo $da['title'] ?></strong></h4>
                                        <a href="<?php echo esc_url(remove_query_arg(['AWSAccessKeyId', 'Expires', 'Signature'], $da['asset_pdf'])); ?>" class="btn btn-primary" style="background-color:<?= $args['button_colour'] ?>;border-color:<?= $args['button_colour'] ?>;">Download Now</a>
                                    </div>
                                <?php } ?>

                            <?php }
                            ?>
                        </div>
                    <?php } ?>
                </div>

            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 trail_image">
                        <img src="<?= get_template_directory_uri() . '/img/trial-success_u.png'; ?>" alt="" class="img-responsive">
                    </div>
                    <div class="col-md-6 trial_u">
                        <h2><?php echo $trialHeading; ?></h2>
                        <p><?php echo $trialDescription; ?></p>
                        <a href="#" class="btn btn-primary" style="background-color:<?= $args['button_colour'] ?>;border-color:<?= $args['button_colour'] ?>;"><?php echo $trialButtonText; ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a href="#" id="trial-btn" data-toggle="modal" data-target="#wk-form-modal" class="btn btn-primary hidden"><?php echo $trialButtonText; ?></a>

    <!-- <div class="trial-container rotate-container">
        <div class="container">
            <div class="trial clearfix">
                <div class="rotate-fix-content">
                    <div class="col-md-6">
                        <h2>Start Your 30-Day Wushka Trial</h2>
                        <p>See how Wushka brings next-level rigour to reading with <b>1000+ digital levelled and decodable readers</b>, built-in <b>quizzes</b>, real-time <b>reporting</b> and <b>teacher resources</b>-a-plenty! So, whether your goal is to tackle learning loss, kick-start that phonics program or simply build fluency and comprehension, Wushka has your back!</p>
                        <a href="#" class="btn btn-primary">Start a Trial</a>
                    </div>
                    <div class="col-md-6">
                        <img src="<?= get_template_directory_uri() . '/img/trial-success.png'; ?>" alt="" class="img-responsive">
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</div>
</div>
<script>
jQuery(document).ready(function($) {
    $('.trial_u a').bind("click", function(e) {
        e.preventDefault();
        $('#trial-btn').trigger('click');
    });
});
</script>