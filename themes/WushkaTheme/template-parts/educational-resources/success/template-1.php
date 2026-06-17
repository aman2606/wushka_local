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

<div class="educational-resource resource-success">
    <div class="clearfix cheers">
        <div class="container">
            <div class="col-md-6 hidden-sm hidden-xs">
                <img src="<?=  get_template_directory_uri().'/img/strategy-bookmarks-success.png'; ?>" alt="">
            </div>
            <div class="col-md-6 col-sm-12 content mt50">
                <h1><?php echo $heading; ?></h1>
                <p class="mt30 mb30"><?php  echo $subHeading; ?></p>
                <a href="<?= esc_url(remove_query_arg(['AWSAccessKeyId', 'Expires', 'Signature'], get_field('downloadable_file'))); ?>" class="btn btn-primary" target="_blank" download style="background-color:<?=$args['button_colour'] ?>;border-color:<?=$args['button_colour'] ?>;"><?php echo $downloadButtonText; ?></a>
            </div>
            <div class="col-sm-12 visible-sm visible-xs">
                <img src="<?=  get_template_directory_uri().'/img/strategy-bookmarks-success.png'; ?>" alt="">
            </div>
        </div>
    </div>

    <a href="#" id="trial-btn"  data-toggle="modal" data-target="#wk-form-modal" class="btn btn-primary hidden">Start a Trial</a>

    <div class="trial-container rotate-container">
        <div class="container">
            <div class="trial clearfix" style="background-color:<?= $args['background_color'] ?>;">        
                <div class="rotate-fix-content">
                    <div class="col-md-6">
                        <h2><?php echo $trialHeading; ?></h2>
                        <p><?php echo $trialDescription; ?></p>
                        <a href="#"  class="btn btn-primary" style="background-color:<?=$args['button_colour'] ?>;border-color:<?=$args['button_colour'] ?>;"><?php echo $trialButtonText; ?></a>
                    </div>
                    <div class="col-md-6">
                        <img src="<?= get_template_directory_uri().'/img/trial-success.png'; ?>" alt="" class="img-responsive">
                    </div>   
                </div>                     
            </div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
    $('.trial a').bind("click", function (e) {
        e.preventDefault();
        $('#trial-btn').trigger('click');
    });
});
</script>