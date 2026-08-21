<?php



$successPage = get_field('success_page', $id);
$success_page_middle_sections = get_field('success_page_middle_sections', $id);
$heading = "Cheers! Here's <br>Your Download";
$subHeading = get_the_title();
$downloadButtonText = "Download Now";
$trialHeading = "Start Your 30-Day Wushka Trial";
$trialDescription = "";
$trialButtonText = 'Start a Trial';
$trialButtonUrl = $successPage['trial_button_url'];
$isSingleDownload = false;
$isMultiDownload = false;
$enableBundleDownload = get_field('enable_bundle_download', $id);
$download_button_url = '';
$successCTAbutton = false;


if (isset($successPage) && !empty($successPage)) {

    $heading = $successPage['heading'];
    $subHeading = $successPage['sub_heading'];
    $downloadButtonText = $successPage['download_button_text'];
    $download_button_url = $successPage['download_button_url'];
    $trialHeading = $successPage['start_trial_heading'];
    $trialDescription = $successPage['trial_description'];
    $trialButtonText = $successPage['trial_button_text'];
    if (isset($successPage['success_cta_button']) && !empty($successPage['success_cta_button']) && !empty($successPage['success_cta_button']['button_text'])) {
        $successCTAbutton = $successPage['success_cta_button'];
    }
}
$downloadableAssets = get_field('downloadable_assets', get_the_ID());

$downloadables = [];
$singleDownloadableUrl = false;

// if (!empty($downloadableAssets)) {

//     foreach ($downloadableAssets as $d) {

//         if (!empty($d['downloadable_file'])) {

//             array_push($downloadables, $d);
//         }
//     }
// }

if (!empty($downloadableAssets)) {

    if (sizeof($downloadableAssets) > 1) {

        $isMultiDownload = true;
    } else {

        $isSingleDownload = true;
    }
}

$downloadables = $downloadableAssets;

// if (isset($_POST['bundle_download'])) {

//     $bundlesFiles = [];
//     if (!empty($downloadables)) {
//         foreach ($downloadables as $dn) {

//             array_push($bundlesFiles, $dn['downloadable_file']);
//         }

//         $zip = new ZipArchive();

//         # create a temp file & open it
//         $tmp_file = tempnam('.', '');
//         $zip->open($tmp_file, ZipArchive::CREATE);

//         # loop through each file
//         foreach ($bundlesFiles as $file) {
//             # download file
//             $download_file = file_get_contents($file);

//             #add it to the zip
//             $zip->addFromString(basename($file), $download_file);
//         }

//         # close zip
//         $zip->close();

//         # send the file to the browser as a download
//         header('Content-disposition: attachment; filename="Bundles.zip"');
//         header('Content-type: application/zip');
//         readfile($tmp_file);
//         unlink($tmp_file);
//         exit;
//     }
// }


?>
<!-- <form action="" method="POST" style="display:none;" id="bundle-download-form">
    <input type="hidden" name="bundle_download" value="bundle_download" />
</form> -->

<div class="educational-resource resource-single template3 success">

    <div class="breadcrumb-container <?php echo !$enableBundleDownload && !$isSingleDownload ? 'bundle_download_disabled' : '' ?>">
        <div class="container">
            <div class="breadcrumb-content" style="background-color:#fff;">
                <div class="row">
                    <!-- <div class="col-md-push-6 col-md-6 col-sm-12">
                    <h1 class="white success-heading">
                        <?php echo $heading; ?>
                    </h1>
                    <h4 style="color:#fff" class="mt30 success-subheading"><?php echo $subHeading; ?></h4>
                </div> -->

                    <div class="row">
                        <?php if ($enableBundleDownload || $isSingleDownload) { ?>

                            <div class="col-md-6">
                                <?php if (!empty($successPage['featured_image'])) { ?>
                                    <img src="<?= $successPage['featured_image']; ?>" class="img-responsive" alt="">
                                <?php } else { ?>
                                    <img src="<?= esc_url(remove_query_arg(['AWSAccessKeyId', 'Expires', 'Signature'], get_the_post_thumbnail_url(get_the_ID(), 'full'))); ?>" class="img-responsive" alt="">
                                <?php } ?>
                            </div>

                        <?php } ?>

                        <div class="col-md-<?php echo !$enableBundleDownload && !$isSingleDownload  ? '12' : '6' ?> col-sm-12">
                            <h1 class="title_heading success-heading mb0">
                                <?php echo $heading; ?>
                            </h1>
                            <h4 class="mt20 mb20 success-subheading"><?php echo $subHeading; ?></h4>
                            <?php if (!$successCTAbutton) { ?>
                                <?php if ($isSingleDownload) {
                                    if (isset($downloadables[0]['button_text']) && !empty($downloadables[0]['button_text'])) {
                                        if ($downloadables[0]['is_downloadable']) {
                                            $buttonUrl = $downloadables[0]['downloadable_file'];
                                        } else {
                                            $buttonUrl = $downloadables[0]['button_url'];
                                        }
                                ?>

                                        <a href="<?php echo $buttonUrl; ?>" class="btn btn-primary mb30" style="background-color:<?= $args['button_colour'] ?>;border-color:<?= $args['button_colour'] ?>;"><?php echo $downloadables[0]['button_text']; ?></a>
                                    <?php } ?>
                                <?php } ?>

                                <?php if ($isMultiDownload) { ?>

                                    <!-- <a href="javascript:void(0)" class="btn btn-primary mb30 download_bundles" style="background-color:<?= $args['button_colour'] ?>;border-color:<?= $args['button_colour'] ?>;">Download All</a> -->

                                <?php } ?>
                            <?php } else if ($successCTAbutton) {
                                if ($successCTAbutton['is_downloadable']) {
                                    $buttonUrl = $successCTAbutton['downloadable_file'];
                                } else {
                                    $buttonUrl = $successCTAbutton['button_url'];
                                }
                            ?>



                                <a href="<?php echo $buttonUrl ?>" class="btn btn-primary mb30" style="background-color:<?= $args['button_colour'] ?>;border-color:<?= $args['button_colour'] ?>;"><?php echo $successCTAbutton['button_text']; ?></a>

                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($downloadables) && sizeof($downloadables) > 1) { ?>

        <div class="container">
            <div class="resource-container">
                <div class="row resource-bg">
                    <div class="col-md-12 content">
                        <div class="downlodable_assets_container">
                            <div class="downlodable_assets">
                                <?php
                                foreach ($downloadables as $da) {
                                    if (!empty($da)) {


                                ?>
                                        <div class="asset">
                                            <img src="<?php echo $da['image'] ?>" />
                                            <h4><strong><?php echo $da['title'] ?></strong></h4>
                                            <?php
                                            if (isset($da['button_text']) && !empty($da['button_text'])) {
                                                if ($da['is_downloadable']) {
                                                    $buttonUrl = $da['downloadable_file'];
                                                } else {
                                                    $buttonUrl = $da['button_url'];
                                                }
                                            ?>
                                                <a href="<?php echo $buttonUrl; ?>" class="btn btn-primary mb30" style="background-color:<?= $args['button_colour'] ?>;border-color:<?= $args['button_colour'] ?>;"><?php echo $da['button_text']; ?></a>
                                            <?php } ?>

                                        </div>
                                    <?php } ?>

                                <?php }
                                ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    <?php } ?>

    <?php if (!empty($success_page_middle_sections)) { ?>

        <div class="container success-sections">

            <?php foreach ($success_page_middle_sections as $k => $section) { ?>
                <div class="success_section <?php echo ($k+1)%2 == 0 ? 'reverse' : ''?>">
                    <div class="success_section__item">
                        <img src="<?php echo $section['image']; ?>" class="img-responsive" />
                    </div>
                    <div class="success_section__item">
                        <h1><?php echo $section['heading']; ?></h1>
                        <?php
                        echo $section['description'];

                        if (!empty($section['button_text'])) { ?>

                            <a href="<?php echo $section['button_url']; ?>" class="btn btn-primary" style="background-color:<?= $args['button_colour'] ?>;border-color:<?= $args['button_colour'] ?>"><?php echo $section['button_text']; ?></a>

                        <?php } ?>


                    </div>
                </div>
            <?php } ?>
        </div>

    <?php } ?>

    <a href="#" id="trial-btn" data-toggle="modal" data-target="#wk-form-modal" class="btn btn-primary hidden"><?php echo $trialButtonText; ?></a>

    <div class="trial_container">
        <div class="row container">
            <div class="col-md-6 col-sm-12 trail_image">
                <?php if (!empty($successPage['trial_image'])) { ?>
                    <img src="<?= $successPage['trial_image']; ?>" alt="" class="img-responsive">
                <?php } else { ?>
                    <img src="<?= get_template_directory_uri() . '/img/trial-success_u.png'; ?>" alt="" class="img-responsive">

                <?php } ?>
            </div>
            <div class="col-md-6 col-sm-12 trial_u">
                <h2><?php echo $trialHeading; ?></h2>
                <p><?php echo $trialDescription; ?></p>
                <a href="<?php echo $trialButtonUrl; ?>" class="btn btn-primary" style="background-color:<?= $args['button_colour'] ?>;border-color:<?= $args['button_colour'] ?>;"><?php echo $trialButtonText; ?></a>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function($) {
        // $('.trial_u a').bind("click", function(e) {
        //     e.preventDefault();
        //     $('#trial-btn').trigger('click');
        // });

        $('.download_bundles').on('click', function() {
            $("#bundle-download-form").submit();
        });
    });
</script>