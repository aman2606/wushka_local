<?php get_header(); ?>
<?php
/* For Bookmarking Button */
global $current_user;
require_once('functions/bookmarks/class_my-bookmarks.php');
if (!isset($_SESSION)) {
    session_start();
}

$s_type = user_can($current_user->ID, 'student') ? 'student' : 'teacher';
$c_bookmarks = new Wushka_Bookmarks($current_user->ID, $s_type);
$c_bookmarks->load_stylesheets();

$i_quiz = NULL;
$isLevelled = (empty(wp_get_post_terms($post->ID, 'phonics-phase'))) ? true : false;
if (current_user_can('teacher')) {
    if (isset($_SESSION['wushka_decodable_teacher']) && $_SESSION['wushka_decodable_teacher']) {
        if ($isLevelled) {
            redirect_404();
        }
    }

    if (isset($_SESSION['check_for_quiz']) && ! empty($_SESSION['check_for_quiz'])) {
        if (!isset($_SESSION['wushka_decodable_teacher'])) {
            $_SESSION['wushka_decodable_teacher'] = false;
        }
        if (!$_SESSION['wushka_decodable_teacher']) {
            $i_quiz                     = $_SESSION['check_for_quiz'];
?>
            <script>
                jQuery(document).ready(function($) {
                    $('#check-quiz').modal('toggle');
                    $(".teachsupportmat-box h2").equalHeights();
                    $(window).on("load", function() {
                        $(".teachsupportmat-box h2").equalHeights();
                    });
                });
            </script>
<?php
        }
        $_SESSION['check_for_quiz'] = NULL;
    }
}
?>

<div class="container-fluid">
    <div class="row mt30">
        <div class="col-xs-12">
            <h2 class="glyphicon-heading">
                <span class="x2 glyphicon glyphicon-book-open hidden-xs"></span>
                <span class="glyphicon-heading-text"><?php the_title(); ?></span>
            </h2>
        </div>
    </div>
    <div class="modal fade" id="check-quiz" tabindex="-1" role="dialog" aria-labelledby="check-quiz" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p>Would you like to complete the quiz for this Reader?</p>
                </div>
                <div class="modal-footer">
                    <form action="<?php echo home_url() . '/quiz/' . $i_quiz . '/'; ?>" method="POST">
                        <input type="hidden" name="ebook_id" value="<?php echo $post->ID; ?>" />
                        <input type="submit" class="btn btn-primary" value="Yes" />
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php $decodable =  get_the_terms($post->ID, 'phonics-phase'); ?>
    <div class="row singlepost pb30">
        <div class="col-xs-12 col-md-3 col-lg-3 col-lg-offset-1 post-gallery">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-glasses"></i> Read
                </div>
                <div class="panel-body">
                    <div class="ebook-cover-wrapper">
                        <?php
                        $res_country    = strtolower(get_post_meta(get_the_ID(), 'esiss_language', TRUE));
                        $table_language = $wpdb->prefix . "lessonzone_languagecode";
                        $resource_code  = NULL;
                        if ($res_country) {
                            //resource language found, Find Language code for this country
                            //If the Country is english, need to extend the search to find which standard to use.
                            if ($res_country == "english") {
                                $country_option = get_option('lzPA_setting_country');
                                $country_split  = explode("/", $country_option);
                                //Store Name of Country
                                $country_name = $country_split[1];
                                //Query code of this country
                                $query_language = $wpdb->get_var($wpdb->prepare("SELECT LANG_VAR FROM $table_language WHERE LANG_NAME = %s ", $country_name));
                            } else {
                                $query_language = $wpdb->get_var($wpdb->prepare("SELECT LANG_CODE FROM $table_language WHERE LANG_TYPE = %s AND LANG_BASE = %s ", 'Base', $res_country));
                            }
                        } else {
                            //No Language was found in resource meta data
                            //Use stored Country Data to collect language code code
                            $country_option = get_option('lzPA_setting_country');
                            $country_split  = explode("/", $country_option);
                            //Store Name of Country
                            $country_name = $country_split[1];
                            //Query code of this country
                            $query_language = $wpdb->get_var($wpdb->prepare("SELECT LANG_VAR FROM $table_language WHERE LANG_NAME = %s ", $country_name));
                        }
                        //Store Gathered Code for use
                        if ($query_language !== NULL) {
                            $resource_code = $query_language;
                        }
                        $resource_id = $post->esiss_resource_id;

                        $iframeURL = false;

                        $ebookIframeInfo = get_field('ebook_iframe_info',$post->ID);
                        if(isset($ebookIframeInfo) && $ebookIframeInfo['has_iframe_url']){

                            $iframeURL = $ebookIframeInfo['iframe_url'];

                        }

                        // if (has_term('jill-jet-no-audio', 'phonics-phase', $post->ID)) {
                        //     $isPdfBook = true;
                        // }


                        //Get Image Data
                        //$imgsrc = get_post_meta($post->ID, 'post_image', true);
                        $imgsrc    = $post->post_image;
                        $size      = get_post_meta(get_post_thumbnail_id(), 'size_info', TRUE);
                        //$imgwidth  = $size['width'];
                        //$imgheight = $size['height'];
                        $ebookImg  = "<figure><img class='img-responsive ebook-cover'  src='$imgsrc' alt='" . the_title_attribute(array('echo' => 0)) . ">' /></figure>";
                        //Show new sash for one month old books
                        $origpostdate = get_the_date('Y-m-d', $post->ID);
                        $days         = get_time_difference($origpostdate);
                        if(!$iframeURL){
                            echo "<a href='#' class='lz-res wushka_ebook' >$ebookImg<span class='glyphicon glyphicon-play-button btn-glyphicon-sample-play' style='display: inline; opacity: 1;'></span><span class='sr-only'>Read eBook</span></a>";
                        }else{
                            $pdfUrl = get_site_url().'/ereader/?book='.$resource_id;
                            echo "<a href='".$pdfUrl."' class='lz-res'>$ebookImg<span class='glyphicon glyphicon-play-button btn-glyphicon-sample-play' style='display: inline; opacity: 1;'></span><span class='sr-only'>Read eBook</span></a>";
                        }
                       


                        //Store Program Coordinator Display Mode (School||Teacher)
                        $sSchool = 'school';
                        if (isset($_SESSION['dashboard_selection']) && ! empty($_SESSION['dashboard_selection'])) {
                            $sSchool = $_SESSION['dashboard_selection'];
                        }

                        //Add BookMarks Button (Only show to Program Coords in Teacher Mode)
                        if (
                            current_user_can('student') //Students Can Bookmark
                            || (!current_user_can('school' && current_user_can('teacher')) //Regular Teachers can bookmark
                                || (current_user_can('school') && current_user_can('teacher') && $sSchool == 'teacher') //PC's in Teacher Mode Can Bookmark
                            )
                        ) {

                            $bookmark = $c_bookmarks->add_button($post->ID);
                            if (($decodable && hasDecodableAccess()) || (!$decodable && hasLevelledAccess())) {
                                echo $bookmark;
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php if (user_can($current_user, "student")) { ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="glyphicon glyphicon-circle-info"></i> Reading level
                    </div>
                    <div class="panel-body">
                        <ul>
                            <li><?php echo strip_tags(get_the_term_list($post->ID, 'reading-level', '<span class="blurb-meta">Reading Level: </span> ', ', ', ''), '<span>'); ?>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php } else { ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="glyphicon glyphicon-circle-info"></i> Book meta-data
                    </div>
                    <div class="panel-body">
                        <ul>
                            <li><?php echo strip_tags(get_the_term_list($post->ID, 'reading-level', '<span class="blurb-meta">Reading Box: </span> ', ', ', ''), '<span>'); ?>
                            </li>
                            <li><?php echo strip_tags(get_the_term_list($post->ID, 'year-level', '<span class="blurb-meta">Year: </span> ', ', ', ''), '<span>'); ?>
                            </li>
                            <li><?php echo strip_tags(get_the_term_list($post->ID, 'fiction', '<span class="blurb-meta">Fiction/Nonfiction: </span> ', ', ', ''), '<span>'); ?>
                            </li>
                            <li><?php echo strip_tags(get_the_term_list($post->ID, 'ebook-theme', '<span class="blurb-meta">Theme/Topic: </span> ', ', ', ''), '<span>'); ?>
                            </li>
                            <?php if (has_term('', 'genre')) { ?>
                                <li><?php echo strip_tags(get_the_term_list($post->ID, 'genre', '<span class="blurb-meta">Genre: </span> ', ', ', ''), '<span>'); ?>
                                </li>
                            <?php } ?>
                            <?php if ($isLevelled) {   ?>
                                <li><span class="blurb-meta">Strategy/Skills: </span><?php echo $post->esiss_strategy; ?></li>
                            <?php } ?>
                            <li><span class="blurb-meta">Page Count: </span><?php echo $post->esiss_page_count; ?></li>
                            <?php if (isset($post->esiss_word_count) && ! empty($post->esiss_word_count)) { ?>
                                <li><span class="blurb-meta">Word Count: </span><?php echo $post->esiss_word_count; ?></li>
                            <?php } ?>
                            <li><span class="blurb-meta">Reading Level: </span><?php echo $post->wushka_levels; ?></li>
                            <li><span class="blurb-meta">Item Code: </span><?php echo $post->esiss_resource_id; ?></li>
                            <?php if ($country_name == "United States" && !empty($post->esiss_curriculum)) { ?>
                                <li><span class="blurb-meta">Curriculum Codes: </span><?php echo $post->esiss_curriculum; ?>
                                </li>
                            <?php } ?>
                            <?php if ($isLevelled) {   ?>
                                <li><span class="blurb-meta">High Frequency Words: </span><?php echo $post->esiss_hfw; ?></li>
                            <?php } ?>
                            <?php
                            $decodable =  get_the_terms($post->ID, 'phonics-phase');
                            if (/* !empty(trim($post->esiss_sounds)) &&  */$decodable):
                            ?>
                                <li><span class="blurb-meta">Sounds: </span><?php echo $post->esiss_sounds; ?></li>
                            <?php endif; ?>
                            <?php if (has_term('', 'phonics-phase')) { ?>
                                <li><?php echo strip_tags(get_the_term_list($post->ID, 'phonics-phase', '<span class="blurb-meta">Phonics Phase: </span> ', ', ', ''), '<span>'); ?>
                                </li>
                            <?php } ?>
                            <?php if (/* !empty(trim($post->esiss_tricky)) && */$decodable): ?>
                                <li><span class="blurb-meta">Tricky Words: </span><?php echo $post->esiss_tricky; ?></li>
                            <?php endif; ?>
                            <?php
                            $extension = pathinfo($_SERVER['SERVER_NAME'], PATHINFO_EXTENSION);
                            if ($extension == 'au' && !empty($post->esiss_curriculum)):
                                $wushka_curriculum_codes = preg_replace('#\s+#', ', ', trim($post->esiss_curriculum));
                            ?>
                                <li><span class="blurb-meta">Curriculum Codes: </span><?= $wushka_curriculum_codes; ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div id="single-middle-column" class="col-xs-12 col-md-9 col-lg-7 post-details">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-notes"></i> Reader Description
                </div>
                <div class="panel-body">
                    <p>
                        <?php echo $post->esiss_blurb; ?>
                    </p>
                </div>
                <?php lessonzone_after_post_title(); // hook   
                ?>
                <?php lessonzone_after_post_meta(); // hook  
                ?>
            </div>
            <?php if (current_user_can('school') || current_user_can('teacher') ||  current_user_can(OPEN_HOUSE_CUSTOMER)) { ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="glyphicon glyphicon-list-alt"></i> Reader Detail
                    </div>
                    <div class="panel-body">
                        <p>
                            <?= str_replace(['<p>', '</p>'], '', get_the_content()); ?>
                        </p>
                    </div>
                    <?php lessonzone_after_post_content(); // hook  
                    ?>
                </div>
            <?php } ?>
            <div class="box-bordertopbottom" style="display:none;">
                <?php
                include_once(ABSPATH . 'wp-admin/includes/plugin.php');
                if (is_plugin_active('lessonZone-postAttachment/lzPA-postAttachment.php')) {
                    lzPA_singlePost_hook(); //Hook For lessonZone-PostAttachment Info
                }
                wushka_ebook_hook();
                ?>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-log-book"></i> After you have completed the Reader
                </div>
                <div class="panel-body">
                    <p class="teachsupport-description">
                        <?php echo $post->esiss_activities; ?>
                    </p>
                </div>
            </div>
            <?php if (is_user_logged_in() && user_can($current_user, 'student')) {
                $bShow = TRUE;
                $aAllowed = $current_user->allowed_shelves;
                if (isset($aAllowed) && ! empty($aAllowed)) {
                    if ($aAllowed['id'] == 'none') {
                        $bShow = FALSE;
                    }
                }
                if ($bShow) { ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="glyphicon glyphicon-thumbs-up"></i> Did you like the Reader?
                        </div>
                        <div class="panel-body">
                            <p class="more-ebooks-description">Here are some more you might enjoy!</p>

                            <div class="row">
                                <?php
                                $ids          = $post->esiss_related;
                                $resourceIds  = explode(',', $ids);
                                $args         = array(
                                    'post_type'      => 'ebook',
                                    'post_status'    => 'publish',
                                    'posts_per_page' => -1,
                                    'meta_query'     => array(
                                        array(
                                            'key'     => 'esiss_resource_id',
                                            'value'   => $ids,
                                            'compare' => 'IN'
                                        )
                                    )
                                );
                                $relatedBooks = get_posts($args);
                                if (isset($relatedBooks)) {
                                    foreach ($relatedBooks as $idx => $relatedBook) {
                                        $aTerms = wp_get_post_terms($relatedBook->ID, 'reading-level');
                                        $aTerm  = wp_list_pluck($aTerms, 'slug');
                                        $phonics = wp_get_post_terms($relatedBook->ID, 'phonics-phase');
                                        $phonicsTerm = wp_list_pluck($phonics, 'slug');
                                        $phonics_master = wp_get_post_terms($post->ID, 'phonics-phase');
                                        $phonicsTerm_master = wp_list_pluck($phonics_master, 'slug');
                                        $imgsrc = get_post_meta($relatedBook->ID, 'post_image', TRUE);
                                        if (in_array($aTerm[0], $current_user->prepared_shelves) || $phonicsTerm == $phonicsTerm_master) {
                                ?>
                                            <div class="col-xs-6 col-sm-3 col-lg-2">
                                                <a class="more-ebooks-cover" href="<?php echo get_permalink($relatedBook->ID); ?>"
                                                    title="<?= get_the_title($relatedBook->ID); ?>">
                                                    <figure>
                                                        <img src="<?php echo $imgsrc; ?>" alt="<?= get_the_title($relatedBook->ID); ?>"
                                                            class="img-responsive" />
                                                    </figure>
                                                </a>
                                            </div>
                                <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
            <?php if (! is_user_logged_in() || user_can($current_user, "teacher") || user_can($current_user, "administrator") || user_can($current_user, 'parent')) { ?>

                <?php if (($decodable && hasDecodableAccess()) || (!$decodable && hasLevelledAccess())) { ?> <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="glyphicon glyphicon-nails"></i> Support Materials
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <?php
                                /* ------- TEACHER SUPPORT MATERIALS SECTION --------- */
                                global $lzaws;
                                $support_materials['BM']       = array(
                                    'title' => 'Blackline Master',
                                    'id'    => NULL,
                                    'img'   => NULL,
                                    'name'  => NULL
                                );
                                $support_materials['LP']       = array(
                                    'title' => 'Lesson Plan',
                                    'id'    => NULL,
                                    'img'   => NULL,
                                    'name'  => NULL
                                );
                                $support_materials['Booklet']  = array(
                                    'title' => 'Printable Reader',
                                    'id'    => NULL,
                                    'img'   => NULL,
                                    'name'  => NULL
                                );
                                $support_materials['WBooklet'] = array(
                                    'title' => 'Printable Reader (Wordless)',
                                    'id'    => NULL,
                                    'img'   => NULL,
                                    'name'  => NULL
                                );
                                $support_materials['VR']       = array(
                                    'title' => 'Viewable Reader',
                                    'id'    => NULL,
                                    'img'   => NULL,
                                    'name'  => NULL
                                );
                                $support_materials['DC']       = array(
                                    'title' => 'Discussion Cards',
                                    'id'    => NULL,
                                    'img'   => NULL,
                                    'name'  => NULL
                                );
                                $support_materials['WC']       = array(
                                    'title' => 'Word Cards',
                                    'id'    => NULL,
                                    'img'   => NULL,
                                    'name'  => NULL
                                );
                                $support_materials['RR']       = array(
                                    'title' => 'Reading Record',
                                    'id'    => NULL,
                                    'img'   => NULL,
                                    'name'  => NULL
                                );
                                $support_materials['SS']       = array(
                                    'title' => 'Sequence Strip',
                                    'id'    => NULL,
                                    'img'   => NULL,
                                    'name'  => NULL
                                );
                                $support_materials['PA']       = array(
                                    'title' => 'Phonics Activity',
                                    'id'    => NULL,
                                    'img'   => NULL,
                                    'name'  => NULL
                                );
                                $support_materials['CA']       = array(
                                    'title' => 'Comprehension Assessment',
                                    'id'    => NULL,
                                    'img'   => NULL,
                                    'name'  => NULL
                                );

                                $attachments = $wpdb->get_results(
                                    $wpdb->prepare(
                                        "SELECT * FROM " . $wpdb->prefix . "posts WHERE post_mime_type IN(%s, %s) " .
                                            "AND post_parent = %d AND post_status = %s AND post_type = %s AND ( guid LIKE %s OR guid LIKE %s )",
                                        "application/pdf",
                                        "image/jpeg",
                                        $post->ID,
                                        'inherit',
                                        'attachment',
                                        '%01.jpg',
                                        '%.pdf'
                                    )
                                );

                                foreach ($attachments as $attachment) {
                                    foreach ($support_materials as $key => $value) {
                                        $img = "_" . $key . "01.jpg";
                                        $pdf = "_" . $key . ".pdf";
                                        if (substr_compare($attachment->guid, $img, -strlen($img), strlen($img)) === 0 && substr_compare($attachment->post_title, $resource_code, 6, 3) === 0) {
                                            $support_materials[$key]['img'] = "<img class='img-responsive img-thumbnail' src='$attachment->guid' alt='" . esc_html($attachment->post_title) . "'>";
                                        } else if (substr_compare($attachment->guid, $pdf, -strlen($pdf), strlen($pdf)) === 0 && substr_compare($attachment->post_title, $resource_code, 6, 3) === 0) {
                                            $support_materials[$key]['id']   = $attachment->ID;
                                            $support_materials[$key]['name'] = $attachment->post_name;
                                        }
                                    }
                                }

                                if (count($support_materials) > 0) {
                                    $section_teacher_support = "<!-- TEACHER SUPPORT MATERIALS SECTION -->";
                                    foreach ($support_materials as $material) {
                                        if (isset($material['id']) && $material['id'] !== NULL) {
                                            $material_display = "<div class='col-xs-12 col-sm-6 col-md-4 col-lg-3'><div class='teachsupportmat-box'>";
                                            $material_display .= "<h2>" . $material['title'] . "</h2>";
                                            $material_display .= "<div class='teachsupportmat-img'>";
                                            $material_display .= $material['img'];
                                            $material_display .= "</div>";
                                            $material_display .= "<div class='teachsupportmat-controls'>";
                                            if (is_user_logged_in()) {
                                                $material_display .= '<span class="sr-only">Download</span><input type="button" data-id="' . $material['id'] . '" class="btn btn-default btn-block btn-stamp support download" value="Download" />';
                                                $material_display .= '<span class="sr-only">View</span><input type="button" data-id="' . $material['id'] . '" class="btn btn-default btn-block btn-stamp support" value="View" />';
                                            } else {
                                                $material_display .= '<a href="#" onclick="javascript:window.location=\'' . home_url('/login/') . '\'; return false;" class="btn btn-default" style="width:100%;">Download</a>';
                                                $material_display .= '<a href="#" onclick="javascript:window.location=\'' . home_url('/login/') . '\'; return false;" class="btn btn-default" style="width:100%;">View</a>';
                                            }
                                            //$material_display .= "<input type='hidden' id='lzPA-ebookID' value='" . $material['id'] . "'/>";
                                            //$material_display .= "<input type='hidden' id='lzPA-ebookRes' value='$resource_id'/>";
                                            //$material_display .= "<input type='hidden' id='lzPA-ebookName' value='" . $material['name'] . "'/>";
                                            $material_display .= "</div>";
                                            $material_display .= "</div></div><!-- End of .teachsupportmat-box -->";
                                            $section_teacher_support .= $material_display;
                                        }
                                    }

                                    $section_teacher_support .= "<div class='clear'></div>";

                                    //Display All Teacher Support Data
                                    echo $section_teacher_support;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>
<?php
/* 
Estimated Stray tag
</div>
*/
?>

<?php
/* 
//Element style not allowed as child of element body in this context
<style>
.btn-ebook {
    // height: 16%; 
    width: 50%;
    top: 42%;
    left: 25%;
}
</style>
*/
?>
<script>
    //Accessiblity Fixes
    //$("#lzpa-postAttachData #lzPA_code").addClass("lzPA_code").removeAttr('id');
    //$("#lzpa-postAttachData #lzPA_pdfFileID").addClass("lzPA_pdfFileID").removeAttr('id');
    //$("#lzpa-postAttachData #lzPA_pdfFileName").addClass("lzPA_pdfFileName").removeAttr('id');

    /*
     * Ebook Validation
     */
    var siteUrl = "<?= get_site_url(); ?>";


    jQuery(document).ready(function($) {
        $(".teachsupportmat-box h2").equalHeights();
        $(window).on("load", function() {
            $(".teachsupportmat-box h2").equalHeights();
        });
        $(window).resize(function() {
            $(".teachsupportmat-box h2").equalHeights();
        });
        // remove link attribute from Author
        $(".post-details-inner h3 a").removeAttr("href");
        $(".post-details-inner h3 a").css("text-decoration", "none");
        $(".post-details-inner h3 a").css("cursor", "text");

        // remove link attribute from Blurb Meta tags
        $(".blurb-container.teacher a").removeAttr("href");
        $(".blurb-container.teacher a").css("text-decoration", "none");
        $(".blurb-container.teacher a").css("cursor", "text");

        // Newsletter Unsubscribe Button
        $("input[name='fue_submit']").addClass("btn btn-default");
        $("input[name='fue_submit']").before('<label></label>');

        /* Item Detail Page | Align Read button to middle center -------------------- */
        // Define variables for Parent and Child elements:
        //        var ebookCover = $('.ebook-cover');
        //        var ebookReadBtn = $('.btn-ebook');
        // Get Parent element's width/height:
        //        var ebookReadBtn_positionTop = ebookCover.height(); // console.log(ebookReadBtn_positionTop); console.log( 'Mitad: ' + ebookReadBtn_positionTop / 2);
        //        var ebookReadBtn_positionLeft = ebookCover.width(); // console.log(ebookReadBtn_positionLeft); console.log( 'Mitad: ' + ebookReadBtn_positionLeft / 2);
        // Get Child element's width/height and calculate it's half dimensions to align element to center:
        //        var ebookReadBtn_halfHeight = ebookReadBtn.height(); // console.log(ebookReadBtn_halfHeight); console.log( 'Mitad: ' + ebookReadBtn_halfHeight / 2);
        //        var ebookReadBtn_halfWidth = ebookReadBtn.width(); // console.log(ebookReadBtn_halfWidth); console.log( 'Mitad: ' + ebookReadBtn_halfWidth / 2);
        // Apply position to Button
        //        ebookReadBtn.css({
        //            'top': (ebookReadBtn_positionTop / 2) - ebookReadBtn_halfHeight,
        //            'left': (ebookReadBtn_positionLeft / 2) - ebookReadBtn_halfWidth
        //        });
        //        console.log('Read Button Aligned');

        /* Toggle Favourite Button Star */
        if ($('.btn-bookmark').hasClass('marked')) {
            $('.bookmark-glyph').addClass('starred');
            console.log('Book Marked');
        }
        $('.btn-bookmark').click(function() {
            $('.bookmark-glyph').toggleClass('starred');
        });

        $('.wushka_ebook').on('click', function(e) {
            e.preventDefault();
            var stampVarWrap = $(document).find('.pdfStamp_singlePostResource');
            var userCheck = stampVarWrap.find('#userAccountCheck').attr('value');

            var ebookWrap = $('#lzpa-postAttachData');
            var resourceId = ebookWrap.find('#lzPA_resourceID').val();
            var resourceCode = ebookWrap.find('#lzPA_code').val();
            var ereader_url = siteUrl + "/ereader?epub=/Resources/" + resourceId + "/" + resourceCode + "/";

            if (resourceCode === undefined) {
                var url = document.domain;
                var country = url.split('.').pop(-1);
                if (country == 'com')
                    var ereader_url = "/ereader?epub=/Resources/" + resourceId + "/" + resourceId + "S03" +
                        "/";
            }
            if (userCheck == 'false') {
                window.location.href = "<?php echo esc_url(home_url('/login/')); ?>";
            } else if (userCheck == 'true') {
                window.open(ereader_url, '_self');
            }
        });
    });
</script>
<?php
include 'dashboard_options.php';
get_footer();
/* ----- END OF FILE ----- */
