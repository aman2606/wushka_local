<?php
/*
  Template Name: ereadium
 */
?>



<?php
include_once 'functions/reading-groups/class_reading-groups.php';
$c_rg     = new Reading_Groups();
$i_group  = NULL;

global $current_user;
global $wp_query;

$b_sample_frame = FALSE;
if (! isset($_SESSION)) {
    session_start();
}

$query       = array();
$i_id        = NULL;
$resource_id = NULL;
if ($_GET['epub']) {
    $resource = (isset($_GET['epub']) && ! empty($_GET['epub'])) ? trim($_GET['epub']) : NULL;
    if (isset($resource)) {
        $aRes        = explode('/', $resource);
        $resource_id = $aRes[2];

        $args  = array(
            'post_type'      => 'ebook',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'meta_query'     => array(
                0 => array(
                    'key'     => 'esiss_resource_id',
                    'value'   => $resource_id,
                    'compare' => '='
                )
            )
        );
        $query = get_posts($args);
        $i_id  = $query[0];
    }
}
$iFrameBookResourceId = false;
$iframeBook = false;

if ($_GET['book']) {
    if (! is_user_logged_in()) {
        wp_redirect('/login');
        exit();
    }
    $iFrameBookResourceId = (isset($_GET['book']) && ! empty($_GET['book'])) ? trim($_GET['book']) : NULL;
    $args  = array(
        'post_type'      => 'ebook',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'meta_query'     => array(
            0 => array(
                'key'     => 'esiss_resource_id',
                'value'   => $iFrameBookResourceId,
                'compare' => '='
            )
        )
    );
    $iframeBook = get_posts($args);

    if ($iframeBook) {
        $i_id = $iframeBook[0]->ID;
    }
}



if (! is_user_logged_in()) {
    //die("dfdffd");
    if (isset($_GET['reader']) && $_GET['reader'] == 'samples') {
        $b_sample_frame = TRUE;
    }
    $s_sample = get_post_meta($i_id, 'esiss_free_sample', TRUE);
    if ($s_sample == 'Y') {
        $quiz_id     = NULL;
        $narration   = 'Yes';
        $s_page_link = '/';
    } else {

        wp_redirect(esc_url(get_permalink(get_page_by_title('My Account'))));
        exit();
    }
} else {
    if (! has_valid_subscription()) {
        wp_redirect(esc_url(get_permalink(get_page_by_title('Subscription'))));
        exit;
    }

    $quiz_id     = get_post_meta($i_id, 'wushka_quiz_id', TRUE);
    $s_page_link = '/ebook/' . get_post_field('post_name', $i_id);

    if (current_user_can('teacher') || current_user_can('parent')) {
        $_SESSION['check_for_quiz'] = $quiz_id;
        $quiz_id                    = NULL;
    }

    $narration = "Yes";
    $quiz      = 'compulsory';
    $a_shelves = [];
    if (current_user_can('student')) {
        $narration = get_user_meta($current_user->ID, 'narration', TRUE);
        $quiz      = isset($current_user->quizzes) ? strtolower($current_user->quizzes) : 'compulsory';
        $a_shelves = isset($current_user->prepared_shelves) ? $current_user->prepared_shelves : [];
        $readingLevel = get_the_terms($i_id, 'reading-level');
        $bookReadingLevels = [];
        if (!empty($readingLevel)) {
            foreach ($readingLevel as $l) {
                array_push($bookReadingLevels, $l->slug);
            }
        }

        // if (empty($a_shelves)) {
        //     wp_redirect('/404');
        //     exit();
        // }

        $allowReading = false;

        if (!empty($a_shelves)) {
            foreach ($a_shelves as $s) {

                if (in_array($s, $bookReadingLevels)) {
                    $allowReading = true;
                }
            }
        }else{
            $allowReading = true;
        }

        // if (!$allowReading) {
        //     // wp_redirect('/404');
        //     // exit();
        // }


        $a_groups = [];
        $i_group = get_user_meta($current_user->ID, 'my_reading_group', TRUE);
        if (($x_groups = $c_rg->get_groups('group', $i_group)) !== FALSE) {
            $a_groups = $x_groups;
        }
        include_once 'reading-groups/class_reading-groups.php';
        $c_rg = new Reading_Groups();

        $a_books = array();
        foreach ($a_groups as $idx => $o_group) {
            if (($x_books = $c_rg->get_books($o_group->ID)) !== FALSE) {
                foreach ($x_books as $o_bk) {
                    if ((int)$o_bk->active == 1) {
                        $a_books[] = $o_bk->post_id;
                    }
                }
            }
        }


        if (!$allowReading && (!in_array($i_id, $a_books))) {
            wp_redirect('/403');
            exit();
        }
    }

    $decodable =  get_the_terms($i_id, 'phonics-phase');

    if ($decodable && !hasDecodableAccess()) {
        //die("You don't have access to decodable Libraries Books !");
        wp_redirect('/404');
        exit();
    }

    if (!$decodable && !hasLevelledAccess()) {

        wp_redirect('/404');
        exit();
    }
}

$s_narration = (isset($narration) && $narration == 'Yes') ? 'true' : 'false';
$s_return    = (($quiz !== 'no') && isset($quiz_id) && ! empty($quiz_id)) ? '/quiz/' . $quiz_id : $s_page_link;

if ($_GET['book']) {

    if ($iframeBook) {

        global $wpdb;
        $table_name = $wpdb->prefix . 'lessonzone_reading_analytics_reading_instance';

        // $count = $wpdb->get_var(
        //     $wpdb->prepare(
        //         "SELECT COUNT(*) FROM $table_name WHERE user_id = %d AND essis_resource_id = %s",
        //         get_current_user_id(),
        //         $pdfResourceId
        //     )
        // );

        // if ($count < 1) {

        // Sample data to insert — update this with real values

        // check fiction-
        $aTerms = wp_get_post_terms($i_id, 'fiction');
        $aTerm  = wp_list_pluck($aTerms, 'name');

        $isFiction =  ! empty($aTerm) && $aTerm[0] == 'Fiction';


        // check level

        $aTermsCheckLevel = wp_get_post_terms($i_id, 'reading-level');
        $aTermCL  = wp_list_pluck($aTermsCheckLevel, 'term_taxonomy_id');


        $termLevel =  ! empty($aTermCL) ? $aTermCL[0] : NULL;


        $data = [
            'user_id'         => get_current_user_id(),
            'essis_resource_id'       => $iFrameBookResourceId,
            'created'      => current_time('mysql'),  // WordPress time format
            'completed' => 1,
            'duration' => 1,
            'fiction' => $isFiction,
            'level' => $termLevel
        ];

        // Insert into the database
        $wpdb->insert($table_name, $data);
        $pdfReadingRecordID = $wpdb->insert_id;
        // }


        get_template_part('content', 'iframe_book', ['iframeBook' => $iframeBook, 'quiz' => $quiz, 'quiz_id' => $quiz_id, 'pdfReadingRecordID' => $pdfReadingRecordID, 'iFrameBookResourceId' => $iFrameBookResourceId]);
        exit;
    } else {
        wp_redirect('/404');
        exit();
    }
}


?>
<!DOCTYPE html>
<?php $lang = (get_language_attributes() == 'lang="en-AU"') ? 'lang="en"' : get_language_attributes();  ?>
<html <?= $lang; ?> ontouchmove id="simpleViewer">
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="p:domain_verify" content="0b1f38a6c5f52782dddde74afcc90cd1" />
    <title> <?php wp_title('|', TRUE, 'right'); ?> </title>
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/readium-all.css">
    <link href="<?php echo get_template_directory_uri(); ?>/style.css" rel="stylesheet">
    <script>
        var allowNarration = <?php echo $s_narration ?>;
    </script>

    <?php wp_head(); ?>
    <script type="text/javascript"
        src="<?php echo get_template_directory_uri(); ?>/scripts/readium-js-viewer_all_LITE.js?ver=2.01"></script>
    <script>
        var readingAnalytics_recordId = null;
        var startReadingTime = new Date();

        function addRecord() {
            const ajaxParams = {
                'url': '/wp-admin/admin-ajax.php',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'action': 'ereaderAnalytics_addRecord',
                    'essis_resource_id': '<?php echo $resource_id; ?>'
                },
                'success': function(result) {
                    console.log('Add Record Success', result);
                    readingAnalytics_recordId = result;
                }
            };
            console.log('add params', ajaxParams);
            $.ajax(ajaxParams);
        }

        function updateRecord(status) {

            console.log("Update Records Running......");
            const ajaxParams = {
                'url': '<?php echo get_site_url(); ?>/wp-admin/admin-ajax.php',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'action': 'ereaderAnalytics_updateRecord',
                    'function': status,
                    'ebook': '<?php echo $i_id; ?>',
                    'resource_id': '<?php echo $resource_id; ?>',
                    'record_id': readingAnalytics_recordId,
                    'duration': (new Date() - startReadingTime) / 1000
                },
                'success': function(result) {
                    console.log('Update Record Success', result);
                    if (result.data.new) {
                        readingAnalytics_recordId = result.data.new;
                    }
                }
            }

            console.log('update params', ajaxParams);
            $.ajax(ajaxParams);
        }

        function updateNarrated() {
            const ajaxParams = {
                'url': '<?php echo get_site_url() ?>/wp-admin/admin-ajax.php',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'action': 'ereaderAnalytics_updateNarrated',
                    'lessonzone_read_instance_id': readingAnalytics_recordId
                },
                'success': function(result) {
                    console.log('Update Record Narration Success!', result);
                }
            };
            console.log('narrated params', ajaxParams);
            $.ajax(ajaxParams);
        }

        <?php
        if ($s_narration == 'true') {
        ?>
            let clickCount = 0;
            $(document).on('click', '#btn-play-audio', function() {
                if (clickCount == 0) {
                    updateNarrated();
                }
                clickCount++;
            });
        <?php
        }
        ?>

        var path = (window.location && window.location.pathname) ? window.location.pathname : '';

        // extracts path to index.html (or more generally: /PATH/TO/*.[x]html)
        path = path.replace(/(.*)\/.*\.[x]?html$/, "$1");

        // removes trailing slash
        path = path.charAt(path.length - 1) == '/' ?
            path.substr(0, path.length - 1) :
            path;

        var HTTPServerRootFolder =
            window.location ? (
                window.location.protocol +
                "//" +
                window.location.hostname +
                (window.location.port ? (':' + window.location.port) : '') +
                path
            ) : '';

        console.log('My Server Root Folder:');
        console.log(HTTPServerRootFolder);
        console.log('----------');
    </script>
    <?php if ($b_sample_frame === TRUE) { ?>
        <style type="text/css">
            #settbutt1 {
                display: none;
            }

            span#close_book {
                display: none;
                opacity: 0;
            }

            #btn-close {
                display: none;
            }

            span.label {
                position: relative;
                top: 10px;
                left: -14px;
                width: 55px;
                color: inherit;
                text-align: center;
            }

            .ctrl {
                vertical-align: top;
                height: 50px;
                width: 50px;
            }

            .student .ctrl-arrow-right {
                width: 60px;
                height: 90px;
            }

            .student .ctrl-arrow-right span.label {
                left: -50px;
                top: 20px;
                color: #4E6218;
            }
        </style>
    <?php } else { ?>
        <style type="text/css">
            #settbutt1 {
                display: none;
            }

            span.label {
                display: none !important;
            }

            #right-page-btn:active {
                opacity: 1;
                color: #fff;
                background-color: rgba(0, 0, 0, .2);
            }

            #left-page-btn:active {
                opacity: 1;
                color: #fff;
                background-color: rgba(0, 0, 0, .2);
            }

            @media (hover: hover) {
                #right-page-btn:active {
                    opacity: 1;
                    color: #fff;
                    background-color: rgba(0, 0, 0, .2);
                }

                #left-page-btn:active {
                    opacity: 1;
                    color: #fff;
                    background-color: rgba(0, 0, 0, .2);
                }
            }
        </style>
    <?php } ?>
    <script type="text/javascript">
        require.config({
            /* http://requirejs.org/docs/api.html#config-waitSeconds */
            waitSeconds: 0,
            config: {
                'readium_js_viewer/ModuleConfig': {
                    'mathJaxUrl': HTTPServerRootFolder + '/scripts/mathjax/MathJax.js',
                    'annotationCSSUrl': HTTPServerRootFolder + '/css/annotations.css',
                    'jsLibRoot': HTTPServerRootFolder + '/scripts/zip/',
                    'useSimpleLoader': false, // cloud reader (strictly-speaking, this config option is false by default, but we prefer to have it explicitly set here).
                    'epubLibraryPath': undefined, // defaults to /epub_content/epub_library.json relative to the application's root index.html
                    'imagePathPrefix': undefined,
                    'canHandleUrl': false,
                    'canHandleDirectory': false,
                    'workerUrl': undefined,
                    'epubReadingSystemUrl': undefined,
                    'homeUrl': '/',
                    'returnUrl': '<?php echo $s_return ?>',
                    'readerStyle': 'student',
                    'narration': <?php echo $s_narration ?>,
                    'showText': true,
                    'recordStats': updateRecord
                }
            }
        });

        // $(document).on('click', '#btn-play-audio', function (e) {
        //     console.log('--- Play Audio Button Clicked ---');

        //     console.log('Attempt Narration Analytics Update...');
        //     if (!readingAnalytics_recordId) {
        //         addRecord();
        //     }
        // });
        $(document).on('click', '#btn-close', function(e) {
            window.location.href = $("#reader").attr("data-info-page");
        });
    </script>
    <?php /* 
    <script>
    $(document).ajaxSuccess(function(){
        var iframefixed = $('.iframe-fixed');
        var i;
        for(i=0; i < iframefixed.length; i++)
        {
            var iframe = iframefixed[i];
            //Add link to fonts
            //iframe.contentDocument.head.append('<link href="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/fonts/OpenDyslexic3-Regular.ttf"></link>');
            
            var style = document.createElement('style');
            style.textContent = 
            '@font-face {' +
                'font-family: "OpenDyslexic";' +
                'src: url("https://dev.wushka.com.au/wp-content/themes/WushkaTheme/fonts/OpenDyslexic3-Regular.ttf");' +
            '}' + 
            ' body, p, span, .textStyle1, .textStyle2, .textStyle3, .textStyle4, .textStyle5, .textStyle6{ font-family: "OpenDyslexic", sans-serif; }' 
            ;
            iframe.contentDocument.head.appendChild(style); 
        } 
    });
    </script> 
    */
    ?>
</head>

<div <?php body_class(); ?>>
    <?php get_template_part('analyticstracking'); ?>
    <input type="hidden" name="ebook_id" id="ebook_id" value="<?php echo $i_id; ?>" />
    <?php //$s_narration = (isset($narration) && $narration == 'Yes') ? 'true' : 'false'; 
    ?>
    <?php if (($quiz !== 'no') && isset($quiz_id) && ! empty($quiz_id)) { ?>
        <div id="reader" data-narration="<?php echo $s_narration; ?>" data-reader-style="student" data-home="/"
            data-info-page="/quiz/<?php echo $quiz_id; ?>"></div>
    <?php } else { ?>
        <div id="reader" data-narration="<?php echo $s_narration; ?>" data-reader-style="student" data-home="/"
            data-info-page="<?php echo $s_page_link; ?>"></div>
    <?php } ?>
    <nav id="app-navbar" class="navbar" role="banner" aria-label="{{Strings.i18n_toolbar}}">
    </nav>
    <div id="app-container">
    </div>


    <script>
        jQuery(function($) {
            $('noscript').remove();

        });
    </script>
    </body>

</html>
<?php /* ----- EOF ----- */ ?>