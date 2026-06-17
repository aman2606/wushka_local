<?php
$iframeBook = $args['iframeBook'][0];
$quiz = $args['quiz'];
$quiz_id = $args['quiz_id'];
$iframeURL = false;
$pdfReadingRecordID = $args['pdfReadingRecordID'];
$iFrameBookResourceId = $args['iFrameBookResourceId'];

$ebookIframeInfo = get_field('ebook_iframe_info', $iframeBook->ID);
if (isset($ebookIframeInfo) && $ebookIframeInfo['has_iframe_url']) {

    $iframeURL = $ebookIframeInfo['iframe_url'];
}
?>

<!DOCTYPE html>
<?php $lang = (get_language_attributes() == 'lang="en-AU"') ? 'lang="en"' : get_language_attributes(); ?>
<html <?= $lang; ?> ontouchmove id="simpleViewer">

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="p:domain_verify" content="0b1f38a6c5f52782dddde74afcc90cd1" />
    <title><?php wp_title('|', TRUE, 'right'); ?></title>
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
    <link href="<?php echo get_template_directory_uri(); ?>/style.css" rel="stylesheet">

    <?php wp_head(); ?>
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            /* Prevent scrollbars */
        }

        .iframe-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .iframe-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Close button styling */
        .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            border: none;
            padding: 8px 12px;
            font-size: 18px;
            border-radius: 4px;
            cursor: pointer;
            z-index: 9999;
            transition: background-color 0.2s ease;
        }

        .close-btn:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }
    </style>
</head>

<body>
    <div class="iframe-container">

     <?php if (($quiz !== 'no') && isset($quiz_id) && ! empty($quiz_id)) { ?>
            <a class="close-btn" href="<?= get_site_url().'/quiz/'.$quiz_id ?>">✖</a>
    <?php } else { ?>
       <a class="close-btn" href="<?= get_the_permalink($iframeBook->ID); ?>">✖</a>
    <?php } ?>
        <!-- Close button -->
        

        <!-- Fullscreen iframe -->
        <iframe
            src="<?= $iframeURL ?>"
            allow="autoplay; fullscreen"
            seamless
            scrolling="no"
            frameborder="0"
            allowtransparency="true"
            allowfullscreen="true">
        </iframe>
    </div>
    <script>

         var startReadingTime = new Date();

        function updatePDFReadingRecord() {

            console.log("Update Records Running......");
            const ajaxParams = {
                'url': "<?php echo admin_url('admin-ajax.php'); ?>",
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'action': 'ereaderAnalytics_updateRecord',
                    'function': 'duration',
                    'ebook': '<?php echo $iframeBook->ID; ?>',
                    'resource_id': '<?php echo $iFrameBookResourceId; ?>',
                    'record_id': <?php echo $pdfReadingRecordID; ?>,
                    'duration': (new Date() - startReadingTime) / 1000
                },
                'success': function(result) {
                    console.log('Update Record Success', result);
                    //updatePDFReadingRecord();
                }
            }

            console.log('update params', ajaxParams);
            $.ajax(ajaxParams);
        }
jQuery(function($){
    
    $('iframe').on('load', function () {
        startReadingTime = new Date();
        setInterval(() => {
            updatePDFReadingRecord();
        }, 10000);
    // Your code here
    });
});

    </script>
</body>

</html>