<?php
/* For books order , order by current language first */
global $wpdb;
global $wp;
global $current_user;

$library = add_query_arg(array(), $wp->request);
error_log('viewing library: ' . $library);
$library_taxonomy = '';
if ($library == 'levelled') {
    $library_taxonomy = 'reading-level';
} else {
    $library_taxonomy = 'phonics-phase';
}

$newLabelPhases = [
    '2c2-letter-sounds',
    '3c2-phonics',
    '4c2-blends',
    '5c2-vowel-sounds',
    '6c2-spelling',
    '2c3-letter-sounds',
    '3c3-phonics',
    '4c3-blends',
    '5c3-vowel-sounds',
    '6c3-spelling'
];

/* For Bookmarking Button */
$c_bookmarks = array();
$a_bookmarked = array();
if (is_user_logged_in()) {
    require_once('functions/bookmarks/class_my-bookmarks.php');
    $c_bookmarks = new Wushka_Bookmarks($current_user->ID);
    $c_bookmarks->load_stylesheets();
    $a_bookmarked = $c_bookmarks->get_book_list();
}

$is_child = FALSE;
if (is_user_logged_in() && current_user_can('student')) {
    $is_child = TRUE;
}

//Store Program Coordinator Display Mode (School||Teacher)
$sSchool = 'school';
if (isset($_SESSION['dashboard_selection']) && ! empty($_SESSION['dashboard_selection'])) {
    $sSchool = $_SESSION['dashboard_selection'];
}

/* For filter pop up */
$arr          = array(
    "ebook-theme",
    "fiction"
);
$button_names = array();
$args = array(
    'orderby' => 'slug',
    'order'   => 'ASC'
);

/* For Support Materials */
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
/* $support_materials['VR']       = array(
    'title' => 'Viewable Reader',
    'id'    => NULL,
    'img'   => NULL,
    'name'  => NULL
); */
if ($library == 'levelled') {
    $support_materials['DC']       = array(
        'title' => 'Discussion Cards',
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
}

$support_materials['WC']       = array(
    'title' => 'Word Cards',
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
/* $support_materials['PA']       = array(
    'title' => 'Phonics Activity',
    'id'    => NULL,
    'img'   => NULL,
    'name'  => NULL
);
 */
if ($library == 'levelled') {
    $support_materials['CA']       = array(
        'title' => 'Comprehension Assessment',
        'id'    => NULL,
        'img'   => NULL,
        'name'  => NULL
    );
}

// ---------------------------------------------------------------------------
// Helper: split a flat array of valid post objects into pages of $per_page.
// Skips any entry that is not an object or lacks post_title.
// Returns: [ [ $post, … ], … ]  (array of pages)
// ---------------------------------------------------------------------------
function pa_chunk_posts( array $posts, int $per_page = 6 ): array {
    $valid = array_values(
        array_filter( $posts, fn( $p ) => is_object( $p ) && isset( $p->post_title ) )
    );
    return array_chunk( $valid, $per_page );
}

/* For reading shelves */

$a_class_filter_ebooks_options    = array();
$a_class_filter_fiction_options   = array();
$a_class_filter_compStrat_options = array();
$a_class_filter_textType_options  = array();
$a_compStrat                      = get_wushka_comp_strats();
$a_textType                       = get_wushka_text_types();

//show shelf according to their reading level
$prepared_shelves = array();
$my_level         = array();
$a_groups = array();
$read_books = array();
$terms = array();
if (is_user_logged_in()) {
    foreach ($arr as $id => $taxonomy) {
        $index    = get_taxonomy($taxonomy);
        $titles[] = $index->label;
        $thisterms    = get_terms($taxonomy, $args);
        $terms = array_merge($terms, $thisterms);
        foreach ($thisterms as $idx => $term) {
            $button_names[$taxonomy][] = array(
                $term->name,
                $term->slug
            );
        }
    }
    //Set filter option for ebook
    foreach ($button_names as $idx => $buttons) {
        if ($idx == 'ebook-theme') {
            foreach ($buttons as $button) {
                $a_class_filter_ebooks_options[] = '<option value="' . $button[1] . '" data-tax="' . $idx . '">' . $button[0] . '</option>';
            }
        }
        if ($idx == 'fiction') {
            foreach ($buttons as $button) {
                $a_class_filter_fiction_options[] = '<option value="' . $button[1] . '" data-tax="' . $idx . '">' . $button[0] . '</option>';
            }
        }
    }
    if (isset($a_compStrat) && ! empty($a_compStrat)) {
        foreach ($a_compStrat as $idx => $aStrats) {
            $a_class_filter_compStrat_options[] = '<option value="' . trim($aStrats['name']) . '" data-tax="' . trim($aStrats['name']) . '">' . trim($aStrats['label']) . '</option>';
        }
    }
    if (isset($a_textType) && ! empty($a_textType)) {
        foreach ($a_textType as $idx => $aType) {
            $a_class_filter_textType_options[] = '<option value="' . trim($aType['name']) . '" data-tax="' . trim($aType['name']) . '">' . trim($aType['label']) . '</option>';
        }
    }

    $prepared_shelves = get_user_meta($current_user->ID, 'prepared_shelves', TRUE);
    $my_level         = get_user_meta($current_user->ID, 'allowed_shelves', TRUE);

    // echo "<pre>";
    // print_r($current_user->prepared_shelves);
    // exit;
    //Reading Groups
    include_once 'functions/reading-groups/class_reading-groups.php';
    $c_rg     = new Reading_Groups();
    $i_group  = NULL;

    // echo "<pre>";
    // print_r($prepared_shelves);
    // exit;
    if (current_user_can('student')) {
        //Check Student Reading Group Permissions Before Loading Reading Group Carousel Data
        $s_setting = $current_user->rg_setting;
        if (! isset($s_setting) || empty($s_setting)) {
            $s_setting = 'on';
        }
        if ($s_setting != 'off') {
            $b_load = $s_setting == 'on' ? TRUE : FALSE;
            if ($s_setting == 'school' || $s_setting == 'home') {
                error_log('Setting Hours = ' . $s_setting);
                //Only Load Reading Group Carousel on School/Home School Times
                $i_school = wushka_get_user_school($current_user->ID);
                //Get School Term User
                $o_school = wushka_get_school_term_user($i_school);

                if (isset($o_school) && ! empty($o_school)) {
                    //Get School Calendar Hours
                    error_log('Get State of School #' . $o_school->ID);
                    $s_state  = wushka_get_school_caldendar_state($i_school);
                    $a_events = wushka_get_calendar_events($s_state, $o_school->ID);
                    //Get DateTimeZone
                    $tz_utc    = new DateTimeZone('UTC');
                    $s_tz      = wushka_get_school_timezone($i_school);
                    $tz_school = new DateTimeZone($s_tz);
                    $d_time    = new DateTime('now', $tz_utc);
                    $d_time->setTimezone($tz_school);
                    error_log('Time = ' . $d_time->format('dS M Y g:ia'));
                    //Determine if current time is 'in school' OR 'at home'
                    $s_hours = 'home';
                    if (wushka_is_time_school_hours($d_time->format('dS M Y g:ia'), $a_events)) {
                        $s_hours = 'school';
                    }
                    error_log('Time is Within ' . $s_hours . ' Hours');
                    if ($s_hours == $s_setting) {
                        error_log('Setting Matches Time Hours');
                        $b_load = TRUE;
                    }
                }
            }
            if ($b_load) {
                $i_group = get_user_meta($current_user->ID, 'my_reading_group', TRUE);
                if (($x_groups = $c_rg->get_groups('group', $i_group)) !== FALSE) {
                    $a_groups = $x_groups;
                }
            }
        }
    }
    $a_id[]  = $current_user->ID;
    $s_param = '%d';
    if (isset($current_user->student_link_id) && ! empty($current_user->student_link_id)) {
        $a_id[] = $current_user->student_link_id;
        $s_param .= ',%d';
    }
    if (isset($current_user->child_link_id) && ! empty($current_user->child_link_id)) {
        $a_id[] = $current_user->child_link_id;
        $s_param .= ',%d';
    }
    $s_query    = 'SELECT * FROM ' . $wpdb->prefix . 'lessonzone_reading_analytics_reading_instance  WHERE user_id IN(' . $s_param . ')';
    $analytics  = $wpdb->get_results(
        $wpdb->prepare($s_query, $a_id)
    );
    foreach ($analytics as $idx => $record) {
        if ($record->completed) {
            if (isset($read_books[$record->essis_resource_id])) {
                $read_books[$record->essis_resource_id]++;
            } else {
                $read_books[$record->essis_resource_id] = 1;
            }
        }
    }
}

$master  = array();
$a_args  = array(
    'orderby' => 'slug',
    'order'   => 'ASC'
);

$level_terms = get_terms('reading-level', $a_args);
$phase_terms = get_terms('phonics-phase', $a_args);
$level_ids   = array();
$phase_ids   = array();


$a_shelves = isset($current_user->prepared_shelves) ? $current_user->prepared_shelves : [];

error_log('prepared shelves: ' . print_r($a_shelves, true));
foreach ($level_terms as $idx => $o_term) {
    if (is_user_logged_in()) {
        if (current_user_can('student')) {
            if (in_array($o_term->slug, $a_shelves)) {
                $level_ids[] = $o_term->term_id;
            }
        } else {
            $level_ids[] = $o_term->term_id;
        }
    } else {
        $level_ids[] = $o_term->term_id;
    }
}
foreach ($phase_terms as $idx => $o_term) {
    $phase_ids[] = $o_term->term_id;
}

// echo "<pre>";
// print_r($phase_ids);
// exit;

error_log('performing post taxonomy query');
if ($library_taxonomy == 'reading-level') {
    $a_ids = $level_ids;
} else {
    $a_ids = $phase_ids;
}



// echo "<pre>";
// print_r($a_ids);
// exit;

// echo "<pre>";
// print_r($level_ids);exit;
$a_posts = array();
if (! empty($level_ids)) {
    $p_args  = array(
        'post_type'      => 'ebook',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    );
    if (is_user_logged_in()) {
        $p_args['tax_query'] = array(
            array(
                'taxonomy' => $library_taxonomy,
                'field'    => 'term_id',
                'terms'    => $a_ids,
            ),
        );
    }
    // remove decodables from levelled library, add back if decodables are to show
    // if ($library_taxonomy == 'reading-level' && !hasDecodableAccess()) {
    if ($library_taxonomy == 'reading-level') {
        $p_args['tax_query']['relation'] = 'AND';
        $p_args['tax_query'][] = array(
            'taxonomy' => 'phonics-phase',
            'field' => 'term_id',
            'terms' => $phase_ids,
            'operator' => 'NOT IN'
        );
    }
    if ($library_taxonomy == 'phonics-phase') {
        $p_args['meta_key'] = 'esiss_resource_id';
        $p_args['orderby'] = 'meta_valu_num';
        $p_args['order'] = 'ASC';
        $p_args['tax_query']['relation'] = 'AND';
        $p_args['tax_query'][] = array(
            'taxonomy' => 'reading-level',
            'field' => 'term_id',
            'terms' => $level_ids,
            'operator' => 'IN'
        );
    }

    // echo "<pre>";
    // print_r($p_args);exit;
    error_log('taxonomy query params ' . print_r($p_args, true));
    $a_posts = get_posts($p_args);
}
error_log('finished performing post taxonomy query: ' . count($a_posts));
//Create Taxonomy Query (line 352, 370 class manage class list)
$a_term_books = [];
$tax_query    = 'SELECT * FROM ' . $wpdb->prefix . 'term_relationships WHERE term_taxonomy_id IN (
        SELECT term_id FROM ' . $wpdb->prefix . 'term_taxonomy Where taxonomy = "' . $library_taxonomy . '")';
$a_results    = $wpdb->get_results($tax_query);
// error_log('$a_results: ' . print_r($a_results, true));
if ($library_taxonomy == 'reading-level') {
    $a_terms = $level_terms;
} else {
    $a_terms = $phase_terms;
}

$a_support_material_posts = get_posts(array(
    'post_type'      => 'support_material',  // adjust slug if different
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'order'          => 'ASC'
));

foreach ($a_terms as $idx => $o_term) {
    if (ucwords($o_term->name) != 'Reading Level') {
        $a_level = array(
            'term'  => $o_term,
            'ids'   => array(),
            'books' => array(),
            'daily_slideshow' => array(),
            'planning_assessments' => array()
        );
        foreach ($a_results as $books) {
            if ($books->term_taxonomy_id == $o_term->term_id) {
                $a_level['ids'][] = (int)$books->object_id;
            }
        }
        if (! empty($a_posts)) {
            foreach ($a_posts as $id => $o_post) {
                if (in_array((int)$o_post->ID, $a_level['ids'])) {
                    //$a_books[$o_term->term_id][$o_post->id] = $o_post->post_image;
                    $a_level['books'][] = $o_post;
                }
            }
        }

        // ── 3. Filter Support Material posts by matching term ID + category ──
        if (! empty($a_support_material_posts)) {
            foreach ($a_support_material_posts as $o_sm_post) {

                // Get the ACF field value (term ID stored on this support material post)
                $sm_term_id = get_field('books_cetegory', $o_sm_post->ID);

                // Skip if ACF field is empty or doesn't match the current loop term
                if (empty($sm_term_id) || (int)$sm_term_id !== (int)$o_term->term_id) {
                    continue;
                }

                // Check which category this support material post belongs to
                $a_sm_categories = wp_get_post_terms($o_sm_post->ID, 'sm_types', ['fields' => 'all']);
                $a_sm_categories = is_wp_error($a_sm_categories) ? [] : $a_sm_categories;
                $o_sm_post->category_slugs = wp_list_pluck($a_sm_categories, 'slug');
                //$o_sm_post->category_names = wp_list_pluck($a_sm_categories, 'name');
                $o_sm_post->terms = $a_sm_categories;

                if (in_array('daily-slideshows-and-lesson-plans', $o_sm_post->category_slugs)) {
                    $a_level['daily_slideshow'][] = $o_sm_post;
                    $a_level['slideshows_assets_label'] = $o_sm_post->terms[0]->name;
                }

                if (in_array('planning-and-assessments', $o_sm_post->category_slugs)) {
                    $a_level['planning_assessments'][] = $o_sm_post;
                    $a_level['planning_assets_label'] = $o_sm_post->terms[0]->name;
                }
            }
        }

        $master[] = $a_level;
        unset($a_level);
    }
}
?>
<!-- End of Student reading analytics -->
<style>
    #sticky-filter .change-class-select {
        height: 35px;
        border: #a6a6a6 solid 1px;
        color: #222;
        border-image: initial;
        border-radius: 4px;
        padding: 2px 5px 0;
        margin-bottom: 10px;
        font-size: 1.4rem;
        line-height: 1.6;
        color: #222;
        box-shadow: none !important;
        vertical-align: middle
    }

    #sticky-filter input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]) {
        font-family: inherit;
        letter-spacing: normal;
        min-height: 35px;
        display: inline-block;
        padding: 4px 8px;
        margin-bottom: 10px;
        font-size: 1.4rem;
        line-height: 1.6;
        color: #222;
        vertical-align: middle;
        border-radius: 2px;
        background-color: #fff;
        border: 1px solid #999;
        box-shadow: inset 0 1px 1px #000;
        transition: border linear .2s, box-shadow linear .2s
    }

    #sticky-filter .row {
        margin-right: 0;
        margin-left: 0
    }

    #sticky-filter .filter-heading {
        margin: 0;
        border-bottom: 1px solid #c6c6c6
    }

    #sticky-filter .filter-heading label {
        color: #444;
        margin: 0;
        padding: 0;
        font-size: 13px
    }

    #sticky-filter .filter-heading-title {
        color: #444;
        margin: 0;
        padding: 0;
        font-size: 17px
    }

    #sticky-filter input[type="radio"],
    input[type="checkbox"] {
        margin: 4px 0 0;
        margin-top: 1px \9;
        line-height: normal;
        height: 20px;
        width: 20px;
        cursor: pointer
    }

    #sticky-filter ::placeholder {
        color: #a6a6a6
    }

    #sticky-filter .book-mark {
        margin-top: 2px
    }

    .panel-jill-jet-no-audio {
        border: 2px solid #F7941D;
    }

    .panel-jill-jet-no-audio .panel-heading,
    .panel-jill-jet-no-audio .panel-footer {
        border-color: #F7941D;
        background-color: #F7941D;
        color: #fff;
    }


    .panel-5p5a-sound-families-long-vowels,
    .panel-5p5b-sound-families-consonants-short-vowels,
    .panel-5p5c-sound-families-r-controlled {
        border: 2px solid #CB3B83;

    }

    .panel-5p5a-sound-families-long-vowels .panel-heading,
    .panel-5p5a-sound-families-long-vowels .panel-footer,
    .panel-5p5b-sound-families-consonants-short-vowels .panel-heading,
    .panel-5p5b-sound-families-consonants-short-vowels .panel-footer,
    .panel-5p5c-sound-families-r-controlled .panel-heading,
    .panel-5p5c-sound-families-r-controlled .panel-footer {
        border-color: #CB3B83;
        background-color: #CB3B83;
        color: #fff;
    }


    .panel-6-a-spelling-affixes {
        border: 2px solid #e92825;

    }

    .panel-6-a-spelling-affixes .panel-heading,
    .panel-6-a-spelling-affixes .panel-footer {
        border-color: #e92825;
        background-color: #e92825;
        color: #fff;
    }

    .panel-2c2-letter-sounds .panel-heading,
    .panel-2c2-letter-sounds .panel-footer,
    .panel-2c3-letter-sounds .panel-heading,
    .panel-2c3-letter-sounds .panel-footer {
        border-color: #f7941d;
        background-color: #f7941d;
        color: #fff;
    }

    .panel-3c2-phonics .panel-heading,
    .panel-3c2-phonics .panel-footer,
    .panel-3c3-phonics .panel-heading,
    .panel-3c3-phonics .panel-footer {
        border-color: #2f286f;
        background-color: #2f286f;
        color: #fff;
    }

    .panel-4c2-blends .panel-heading,
    .panel-4c2-blends .panel-footer,
    .panel-4c3-blends .panel-heading,
    .panel-4c3-blends .panel-footer {
        border-color: #8dc63f;
        background-color: #8dc63f;
        color: #fff;
    }

    .panel-5c2-vowel-sounds .panel-heading,
    .panel-5c2-vowel-sounds .panel-footer,
    .panel-5c3-vowel-sounds .panel-heading,
    .panel-5c3-vowel-sounds .panel-footer {
        border-color: #92278f;
        background-color: #92278f;
        color: #fff;
    }

    .panel-6c2-spelling .panel-heading,
    .panel-6c2-spelling .panel-footer,
    .panel-6c3-spelling .panel-heading,
    .panel-6c3-spelling .panel-footer {
        border-color: #e92825;
        background-color: #e92825;
        color: #fff;
    }

    .ebook__new-label {
        position: absolute;
        z-index: 1;
        background: #99FF00;
        top: 2%;
        color: #000;
        left: 3px;
    }

    .ebook__new-label h3 {
        font-weight: bold;
        padding: 5px 28px;
        left: 0;
        font-size: 20px;
    }

    .ebook__panel-body {
        position: relative;
    }

    /*Support Material style start here*/
    .shelf-wrapper.planning-and-assessment img.img-responsive.img-rounded, .shelf-wrapper.daily-slideshow img.img-responsive.img-rounded {
        box-shadow: none;
        width: 100px !important;
        height: 100px !important;
        object-fit: contain;
    }
    .shelf-wrapper.planning-and-assessment .bookshelf-item-wrapper .action-buttons > a > span, .shelf-wrapper.daily-slideshow .bookshelf-item-wrapper .action-buttons > a > span{
        color: #ffffff;
        font-weight: 500;
        padding: 2px 10px;
        font-size: 12px;
        border-radius: 3px;
        display: block;
    }
    .shelf-wrapper.planning-and-assessment .action-buttons, .shelf-wrapper.daily-slideshow .action-buttons {
        margin-top: 15px;
    }
    .shelf-wrapper.planning-and-assessment .panel-body.ebook__panel-body, .shelf-wrapper.daily-slideshow .panel-body.ebook__panel-body{
        padding: 40px 15px;
    }
    .shelf-wrapper.planning-and-assessment .action-buttons a, .shelf-wrapper.daily-slideshow .action-buttons a {
        display: block;
    }
    .shelf-wrapper.daily-slideshow .action-buttons a.secondary-button {
        margin-top: 5px !important;
    }
    .shelf-wrapper.planning-and-assessment .panel-heading, .shelf-wrapper.daily-slideshow .panel-heading{
        color: #ffffff;
    }
    .bookshelf-item-wrapper a:hover, .bookshelf-item-wrapper a:hover img.img-responsive.img-rounded {
        box-shadow: none !important;
        background: transparent;
    }
    .shelf-wrapper.daily-slideshow img.img-responsive.img-rounded {
        object-fit: contain;
    }
    .shelf-wrapper.daily-slideshow .bookshelf-item-wrapper > h6 {
        font-size: 18px;
    }

    .shelf-wrapper.daily-slideshow .day-icon-box {
        position: relative;
    }
    .shelf-wrapper.daily-slideshow span.day-week-icon-txt {
        position: absolute;
        top: 34px;
        font-size: 18px;
        font-weight: 700;
    }
    .shelf-wrapper.daily-slideshow div.a-week span.day-week-icon-txt {
        top: 45px !important;
        left: 31px;
    }
    .shelf-wrapper.daily-slideshow .day-icon-box span.number {
        position: absolute;
        left: 11px;
        bottom: 11px;
        font-size: 14px;
        color: #ffffff;
    }
</style>


<!-- ========================================== My Book shelf =========================================================-->
<?php if (current_user_can("school") || current_user_can(OPEN_HOUSE_CUSTOMER) || current_user_can("teacher") || current_user_can("administrator") || current_user_can("student")) { ?>
    <div id="sticky-filter">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 btn-sticky-wrapper text-right">
                    <button class="btn btn-default" id="reset-filter" style="display:none;">Reset</button>
                    <button class="btn btn-primary btn-sticky-filter" data-toggle="collapse"
                        data-target="#sticky-filter-body">Filter</button>
                </div>
            </div>
        </div>
        <?php if (current_user_has_role(array('student'))): ?>
            <script>
                jQuery("#sticky-filter").sticky({
                    topSpacing: 0
                });
                jQuery(".btn-sticky-filter").on("click", function() {
                    jQuery("#sticky-filter").css("z-index", "1029");
                    jQuery("#sticky-filter-sticky-wrapper").css("height", "auto");
                });
            </script>
        <?php endif; ?>

        <div id="sticky-filter-body" class="collapse">
            <div class="container-fluid filter-wrapper-ebook-theme filter-wrapper">
                <div class="row filter-heading">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-12 col-sm-9 filter-heading-inner">
                                <label class="control-label"><span class="filter-heading-title"> Filter Options:</span> </label>
                            </div>
                        </div>
                        <?php if (current_user_has_role(array('student'))): ?>
                            <div class="col-xs-12 col-sm-9 filter-heading-inner no-padding">
                                <div class="col-xs-12 col-sm-4 filter-heading-inner">
                                    <div class="col-sm-12 filter-option">
                                        <label for="ebook" class="control-label">Theme</label>
                                    </div>
                                    <div class="col-sm-12 filter-selection">
                                        <select class="change-class-select" id="ebook">
                                            <option value="">Select Filter ...</option>
                                            <?php echo implode('', $a_class_filter_ebooks_options); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 filter-heading-inner no-padding">
                                    <div class="col-xs-12 col-sm-4 col-lg-4 col-xl-3 filter-heading-inner">
                                        <div class="col-sm-12 filter-option">
                                            <label for="title" class="control-label">Search By Reader Title</label>
                                        </div>
                                        <div class="col-sm-12 filter-selection">
                                            <input type="text" class="change-class-select" id="title"
                                                Placeholder="Enter a word..." />
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-4 col-lg-4 col-xl-3 filter-heading-inner">
                                        <div class="col-sm-12 filter-option">
                                            <label for="ebook" class="control-label">Theme</label>
                                        </div>
                                        <div class="col-sm-12 filter-selection">
                                            <select class="change-class-select" id="ebook">
                                                <option value="">Select Filter ...</option>
                                                <?php echo implode('', $a_class_filter_ebooks_options); ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-4 col-lg-4 col-xl-3 filter-heading-inner">
                                        <div class="col-sm-12 filter-option">
                                            <label for="fiction" class="control-label">Fiction</label>
                                        </div>
                                        <div class="col-sm-12 filter-selection">
                                            <select class="change-class-select" id="fiction">
                                                <option value="">Select Filter ...</option>
                                                <?php echo implode('', $a_class_filter_fiction_options); ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-4 col-lg-4 col-xl-3  filter-heading-inner">
                                        <div class="col-sm-12 filter-option">
                                            <label for="page" class="control-label">Page</label>
                                        </div>
                                        <div class="col-sm-12 filter-selection">
                                            <select class="change-class-select" id="page">
                                                <option value="">Select Filter ...</option>
                                                <option value="8">8</option>
                                                <option value="12">12</option>
                                                <option value="16">16</option>
                                                <option value="24">24</option>
                                                <option value="32">32</option>
                                            </select>
                                        </div>
                                    </div>
                                    <?php if ($library == 'levelled') { ?>
                                        <div class="col-xs-12 col-sm-4 col-lg-4 col-xl-3  filter-heading-inner">
                                            <div class="col-sm-12 filter-option">
                                                <label for="comprehension" class="control-label">Comprehension Strategy</label>
                                            </div>
                                            <div class="col-sm-12 filter-selection">
                                                <select class="change-class-select" id="comprehension">
                                                    <option value="">Select Filter ...</option>
                                                    <?php echo implode('', $a_class_filter_compStrat_options); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-lg-4 col-xl-3 filter-heading-inner">
                                            <div class="col-sm-12 filter-option">
                                                <label for="textType" class="control-label">Text Type</label>
                                            </div>
                                            <div class="col-sm-12 filter-selection">
                                                <select class="change-class-select" id="textType">
                                                    <option value="">Select Filter ...</option>
                                                    <?php echo implode('', $a_class_filter_textType_options); ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php } ?>


                                    <?php

                                    //dd($attachments);
                                    ?>

                                    <div class="col-xs-12 col-sm-4 col-lg-4 col-xl-3  filter-heading-inner">
                                        <div class="col-sm-12 filter-option">
                                            <label for="support" class="control-label">Support Resource Type</label>
                                        </div>
                                        <div class="col-sm-12 filter-selection">
                                            <select class="change-class-select" id="support">
                                                <option value="">Select Filter ...</option>
                                                <?php foreach ($support_materials as $support_material_key => $support_material_value): ?>
                                                    <option
                                                        value="<?= sanitize_title_with_dashes($support_material_value['title']);  ?>"
                                                        data-tax="resource">
                                                        <?= $support_material_value['title'];  ?>
                                                    </option>

                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 filter-heading-inner filter-selection">
                                        <?php if (!current_user_has_role(array('student'))): ?>
                                            <div class="col-xs-12 filter-heading-inner align-items-center">
                                                <span class="book-mark align-items-center">
                                                    <div class="col-sm-6 filter-option">
                                                        <label for="bookmarked" class="control-label">Bookmarked Only</label>
                                                    </div>
                                                    <div class="col-sm-6 filter-selection">
                                                        <input type="checkbox" class="change-class-bookmarked" id="bookmarked" />
                                                    </div>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">

                        <div class="col-xs-6 col-sm-4 filter-option">
                            <button type="submit" class="btn btn-default btn-reset-filter pop_up_button"
                                data-toggle="collapse" data-target="#sticky-filter-body">Reset All Filters</button>
                        </div>
                        <div class="col-xs-6 col-sm-4" style="padding-right:0;">
                            <button type="submit" class="btn btn-primary btn-set-filter pop_up_button"
                                data-toggle="collapse" data-target="#sticky-filter-body">Filter Results</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
<?php } ?>

<!-- Group book shelf  -->
<!-- Refresh the page when a user clicks on back button -->
<input type="hidden" id="refreshed" value="no">

<script>
    var temp_directory = '<?php echo get_template_directory_uri(); ?>';
    jQuery(document).ready(function($) {
        onload = function() {
            var e = document.getElementById("refreshed");
            if (e.value == "no")
                e.value = "yes";
            else {
                e.value = "no";
            }
        }
    });
</script>
<?php
$b_hasLevels = FALSE;

if (is_user_logged_in()) { ?>
    <?php if (current_user_can("student")) { ?>
        <div class="group-shelf-wrapper" style="padding-top: 20px;" id="main-content">
            <?php
            /* Wushka Carousels Class */
            require_once('functions/wushka_carousels.php');
            $c_carousel = new Wushka_Carousel($read_books);
            $library    = $c_carousel->get_book($a_groups, $i_group, $read_books);
            if (isset($library) && ! empty($library)) {
                echo $library;
                $b_hasLevels = TRUE;
            }
            ?>
        </div><!-- END GROUP SHELVES WRAPPER -->
    <?php } ?>
<?php } ?>
<!-- ========================================== End of Group book shelf ======================================== -->
<script>
    //$(".bookshelf-item-wrapper img").removeAttr('title').attr('alt', '');
    if ($("#sticky-filter-sticky-wrapper").length == 1) {
        $("#sticky-filter-sticky-wrapper").removeAttr('id').attr('id', 'sticky-filter-sticky-wrapper-2');
    }
</script>
<div id="lmr" class="home-student wrapper">
    <!--Loading Screen -->
    <div class="container-fluid">
        <div class="row">
            <div class="loading-screen loading-stamp">
                <div class="spin-icon">
                    <i class="glyphicon glyphicon-cd x3"></i>
                </div>
                <h2>Loading Library</h2>
            </div>
        </div>
    </div>
    <?php

    $content_count = 1;
    foreach ($master as $level_id => $a_level) {

        $o_term = $a_level['term'];
        $posts  = $a_level['books'];

        //Support Materials
        $daily_slideshow  = $a_level['daily_slideshow'];
        $planning_assessments  = $a_level['planning_assessments'];
        $sh_asset_label = $a_level['slideshows_assets_label'];
        $plng_asset_label = $a_level['planning_assets_label'];

        get_template_part('template-parts/support-material-carousel', null, [
            'o_term'               => $o_term,
            'daily_slideshow'      => $daily_slideshow,
            'planning_assessments' => $planning_assessments,
            'ebooks'               => $posts, 
            'counter'              => $content_count,
            'sh_asset_label'       => $sh_asset_label,
            'plng_asset_label'       => $plng_asset_label,
            //'previous_carousel'    => $_SESSION['carousel-support-taxo-' . $o_term->term_taxonomy_id] ?? 0,
            //'current_user'         => $current_user,
        ]);

        //include __DIR__ . '/template-parts/shelf-support-carousel.php';

        $main_content = "";
        if (! is_user_logged_in() || !current_user_can('student')) {
            if ($content_count == 1) {
                $main_content = 'id="main-content"';
            }
        }
        $content_count++;

        if (! is_user_logged_in() || (current_user_can('school') || current_user_can('teacher') || current_user_can(OPEN_HOUSE_CUSTOMER))  || ($is_child && (in_array($o_term->slug, $prepared_shelves) || in_array(substr($o_term->name, 3), $prepared_shelves) || ($library_taxonomy == 'phonics-phase' && count($posts) > 0)))) {
            $blocked = '';
            if (is_user_logged_in() && ($is_child && ! in_array($o_term->slug, $prepared_shelves) && ! in_array(substr($o_term->name, 3), $prepared_shelves) && $library_taxonomy == 'reading-level')) {
                $blocked = ' blocked';
            }

            $b_hasLevels = TRUE;

            $no_indicators     = ceil(count($posts) / 6);
            $total_books       = count($posts);
            $previous_carousel = isset($_SESSION['carousel-taxo-' . $o_term->term_taxonomy_id]) ? $_SESSION['carousel-taxo-' . $o_term->term_taxonomy_id] : 0;
    ?>
            <div class="shelf-wrapper<?php echo $blocked ?>" <?= $main_content; ?>>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="expand-<?php echo $o_term->term_taxonomy_id; ?>" class="wk-panel-shelf expand"
                                data-id="accordion">
                                <div class="panel panel-<?php echo $o_term->term_taxonomy_id; ?> panel-<?php echo $o_term->slug; ?>"
                                    data-term="<?php echo $o_term->slug; ?>">
                                    <div class="carousel slide" id="carousel-taxo-<?php echo $o_term->term_taxonomy_id; ?>">
                                        <div class="panel-heading">
                                            <i class="glyphicon glyphicon-inbox bookshelf-glyphicon"></i>
                                            <?php echo $o_term->name; ?>
                                            <span class="pull-right">
                                                <a role="button" class="btn btn-small btn-shelf-expand" style="display:none"
                                                    href="#collapse-<?php echo $o_term->term_taxonomy_id; ?>"
                                                    <?php /* data-toggle="collapse" */ ?> data-parent="#accordion">
                                                    <span class="glyphicon glyphicon-circle-plus bookshelf-glyphicon "></span>
                                                    <span class="sr-only">Toggle</span>
                                                </a>
                                            </span>
                                            <span class="clearfix"></span>
                                        </div>
                                        <?php $counter = 0; ?>
                                        <div class="panel-body ebook__panel-body">
                                            <?php if (in_array($o_term->slug, $newLabelPhases)) { ?>
                                                <div class="ebook__new-label">
                                                    <h3>NEW</h3>
                                                </div>
                                            <?php } ?>
                                            <div class="carousel-inner">
                                                <div class="item <?php echo ($previous_carousel == 0) ? 'active' : ''; ?>">
                                                    <div class="row">
                                                        <?php
                                                        if ($posts) {
                                                            $completed_books_counter = 0;
                                                            foreach ($posts as $idx => $post) {
                                                                $resource_id = $post->esiss_resource_id;
                                                                $pages       = $post->esiss_page_count;
                                                                if (isset($read_books[$resource_id])) {
                                                                    $completed_books_counter++;
                                                                }
                                                                if ((($idx) % 6) == 0 && $idx != 0) {
                                                                    $counter++;
                                                        ?>
                                                    </div>
                                                </div>
                                                <div
                                                    class="item <?php echo ($counter == $previous_carousel) ? 'active' : ''; ?>">
                                                    <div class="row">
                                                    <?php } ?>

                                                    <?php
                                                                $s_value = NULL;
                                                                $term_option = NULL;
                                                                $oldtaxonomy = null;
                                                                $s_taxonomy = null;
                                                                foreach ($terms as $id => $term) {
                                                                    $s_taxonomy = $term->taxonomy;
                                                                    $s_term     = $term->slug;
                                                                    if ($s_taxonomy !== $oldtaxonomy && !empty($oldtaxonomy)) {
                                                                        $s_value .= 'data-' . $oldtaxonomy . '="' . rtrim($term_option) . '" ';
                                                                        $term_option = '';
                                                                    }
                                                                    if (has_term($s_term, $s_taxonomy, $post->ID)) {
                                                                        $term_option .= $s_term . ' ';
                                                                    }
                                                                    $oldtaxonomy = $s_taxonomy;
                                                                }
                                                                if (!empty($oldtaxonomy)) {
                                                                    $s_value .= 'data-' . $oldtaxonomy . '="' . rtrim($term_option) . '"';
                                                                }

                                                                $s_value .= ' data-comprehension="' . $post->wushka_comprehension . '"';
                                                                $s_value .= ' data-text="' . $post->wushka_text_type . '"';
                                                                $s_value .= ' data-title="' . strtolower(the_title_attribute(['echo' => FALSE])) . '"';

                                                                // Is This Resource bookmarked by current user?
                                                                $i_isMarked = 0;
                                                                if (in_array($post->ID, $a_bookmarked)) {
                                                                    $i_isMarked = 1;
                                                                }
                                                                $s_value .= ' data-bookmarked="' . $i_isMarked . '"';


                                                                /*============================================================================
                                                        *
                                                        * Support 
                                                        *
                                                        ===============================================================================*/

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
                                                                $resourceID = $wpdb->get_results(
                                                                    $wpdb->prepare(
                                                                        "SELECT meta_value FROM " . $wpdb->prefix . "postmeta WHERE `meta_key` = 'esiss_resource_id' AND `post_id` = %d",
                                                                        $post->ID
                                                                    )
                                                                );

                                                                $resourceID = $resourceID[0]->meta_value;
                                                                //dd($resourceID);
                                                                if (count($support_materials) > 0) {
                                                                    $section_teacher_support = "";
                                                                    foreach ($support_materials as $material) {
                                                                        if (isset($material['id']) && $material['id'] !== NULL && strpos($material['name'], $resourceID) !== false) {
                                                                            $material_display = sanitize_title_with_dashes($material['title']) . ' ';
                                                                            $section_teacher_support .= $material_display;
                                                                        }
                                                                    }
                                                                }
                                                    ?>
                                                    <div data-support="<?= $section_teacher_support; ?>"
                                                        class="thumb accordion-shelf-book col-xsp-12 col-xsl-6 col-xs-4 col-sm-2 text-center"
                                                        <?php echo $s_value; ?> data-pages="<?php echo $pages ?>"
                                                        id="<?php echo $o_term->slug . '-' . $idx; ?>">
                                                        <div class="item-detail link-<?php the_ID(); ?>">
                                                            <span class="sr-only"><?php the_title_attribute(); ?></span>
                                                            <div class="bookshelf-item-wrapper">
                                                                <?php
                                                                if (is_user_logged_in()) {
                                                                    //Add BookMarks Button (Only show to Program Coords in Teacher Mode)
                                                                    if (
                                                                        current_user_can('student') //Students Can Bookmark
                                                                        || (! current_user_can('school' && current_user_can('teacher')) //Regular Teachers can bookmark
                                                                            || (current_user_can('school') && current_user_can('teacher') && $sSchool == 'teacher') //PC's in Teacher Mode Can Bookmark
                                                                        )
                                                                    ) {
                                                                        $bookmark = $c_bookmarks->add_overlay_button($post->ID);
                                                                        echo $bookmark;
                                                                    }
                                                                }
                                                                ?>
                                                                <a href="<?php the_permalink(); ?>">
                                                                    <?php if (isset($read_books[$resource_id]) && !current_user_can(OPEN_HOUSE_CUSTOMER)) { ?>
                                                                        <img src="//cdn6.wushka.com.au/Resources/wk-sash-read.png"
                                                                            width="65" height="65" alt="" loading="lazy"
                                                                            class="sash-ebook-readit">
                                                                    <?php
                                                                    }
                                                                    $imgsrc = $post->post_image;
                                                                    ?>
                                                                    <input type="hidden" class="img-source"
                                                                        value="<?php echo $imgsrc; ?>" />
                                                                    <img class="img-responsive img-rounded"
                                                                        alt="<?= esc_html($post->post_title); ?>"
                                                                        data-value="<?php echo $imgsrc; ?>"
                                                                        src="<?php echo $imgsrc; ?>" loading="lazy"
                                                                        style="width:200px; height:284px;" />
                                                                </a>
                                                                <?php if (isset($read_books[$resource_id]) && !current_user_can(OPEN_HOUSE_CUSTOMER)) { ?>
                                                                    <span
                                                                        class="times-read bottom pull-right"><?php echo $read_books[$resource_id] ?>x</span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php
                                                            } // end of foreach
                                                            if (! user_can($current_user->ID, 'parent')) {
                                                                $complete_percentage = round(($completed_books_counter / $total_books) * 100);
                                                            }
                                                        } else {
                                                            echo 'This Box is empty';
                                                        }
                                            ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <a class="left carousel-control bg-frontpage"
                                                href="#carousel-taxo-<?php echo $o_term->term_taxonomy_id; ?>"
                                                data-slide="prev">
                                                <span class="arrow-left-wrapper">
                                                    <span class="glyphicon glyphicon-chevron-left x2 library-arrow left"></span>
                                                </span>
                                                <span class="sr-only">Left Slide</span>
                                            </a>
                                            <a class="right carousel-control bg-frontpage"
                                                href="#carousel-taxo-<?php echo $o_term->term_taxonomy_id; ?>"
                                                data-slide="next">
                                                <span class="arrow-right-wrapper">
                                                    <span
                                                        class="glyphicon glyphicon-chevron-right x2 library-arrow right"></span>
                                                </span>
                                                <span class="sr-only">Right Slide</span>
                                            </a>
                                        </div>
                                        <div class="panel-footer">
                                            <?php if (! user_can($current_user->ID, 'parent')) { ?>
                                                <?php
                                                //Reading level progress
                                                /* require_once('functions/class_reading_level_progress.php');
                                        $readers_completed = new ReadersCompleted($current_user);
                                        $readers_completed_results = $readers_completed->getResults(); 
                                        
                                        $reading_level_progress = 0;
                                        foreach($readers_completed_results as $reader_completed_restult){ 
                                            if($reader_completed_restult['term_id'] == $o_term->term_taxonomy_id){
                                                $reading_level_progress = $reader_completed_restult['percentage'];
                                            } 
                                        }  */
                                                ?>
                                                <div class="progress-label">Readers Completed</div>
                                                <div class="progress">
                                                    <?php  // Previous Readers Completed 
                                                    ?>
                                                    <div class="progress-bar <?php echo $o_term->slug; ?>" role="progressbar"
                                                        aria-valuenow="<?php echo $complete_percentage; ?>" aria-valuemin="0"
                                                        aria-valuemax="100" aria-label="Progress Bar"
                                                        style="min-width: 2em; width: <?php echo $complete_percentage; ?>%">
                                                        <?php echo $complete_percentage; ?>
                                                        %
                                                    </div>
                                                    <?php // Previous Readers Completed  
                                                    ?>
                                                    <?php /* New Readers Completed  ?>

                                        <div class="progress-bar <?php echo $o_term->slug; ?>" role="progressbar"
                                            aria-valuenow="<?php echo $reading_level_progress; ?>" aria-valuemin="0"
                                            aria-valuemax="100" aria-label="Progress Bar"
                                            style="min-width: 2em; width: <?php echo $reading_level_progress; ?>%">
                                            <?php echo $reading_level_progress; ?>
                                            %
                                        </div>

                                        <?php */ //New readers completed 
                                                    ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Visible Row -->

                            <!-- Hidden Rows -->
                            <div id="collapse-<?php echo $o_term->term_taxonomy_id; ?>" class="wk-panel-shelf collapse">
                                <div class="panel panel-<?php echo $o_term->term_taxonomy_id; ?> panel-<?php echo $o_term->slug; ?>"
                                    data-term="<?php echo $o_term->slug; ?>">
                                    <div class="panel-heading">
                                        <i class="glyphicon glyphicon-inbox bookshelf-glyphicon"></i>
                                        <?php echo $o_term->name; ?>
                                        <span class="pull-right">
                                            <a role="button" tabindex="0" class="btn btn-small btn-shelf-close-bottom"
                                                href="#collapse-<?php echo $o_term->term_taxonomy_id; ?>" data-toggle="collapse"
                                                data-parent="#accordion">
                                                <span class="glyphicon glyphicon-circle-minus bookshelf-glyphicon "></span>
                                                <span class="sr-only">Toggle</span>
                                            </a>
                                        </span>
                                        <span class="clearfix"></span>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <?php if (! user_can($current_user->ID, 'parent')) { ?>
                                            <div class="progress-label">Readers Completed</div>
                                            <div class="progress">
                                                <div class="progress-bar <?php echo $o_term->slug; ?>" role="progressbar"
                                                    aria-valuenow="<?php echo $complete_percentage; ?>" aria-valuemin="0"
                                                    aria-valuemax="100"
                                                    style="min-width: 2em; width: <?php echo $complete_percentage; ?>%">
                                                    <?php echo $complete_percentage; ?>
                                                    %
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
    } // endo of foreach

    /** EFREEDMAN REMOVE THIS LINE HERE TO MAKE 'NO LEVELS OR GROUPS' LABEL APPEAR **/
    $b_hasLevels = TRUE; //
    /** EFREEDMAN REMOVE ABOVE LINE HERE TO MAKE 'NO LEVELS OR GROUPS' LABEL APPEAR **/

    if (! $b_hasLevels) {
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <h1 class="noLevel">You don't have access to any Reading Levels at the moment!</h1>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
    <?php
    if (!is_user_logged_in()) {
        echo '</div>';
    }
    ?>



    <?php include 'dashboard_options.php'; ?>
    <script>
        jQuery(document).ready(function($) {

            $(".btn-shelf-expand").fadeIn("slow");
            $(".role-student .group-shelf-wrapper .btn-shelf-expand").hide();
            $(".role-student .group-shelf-wrapper .book-count").hide();
            var data_reading_box = jQuery('[id^="expand-"] .panel');
            var data_reading_box_collapse = jQuery('[id^="collapse-"] .panel');
            var a_books = [];
            var a_books_collapse = [];
            var reading_level;
            var reading_level_collapse = [];
            var a_remaining = [];

            //Create generic 1s timer before hiding loading screen
            setTimeout(function() {
                var o_loading = $('.loading-screen.loading-stamp');
                //Load Images for remaining Books
                $.each(a_remaining, function(iidx, o_book) {
                    var s_string = $(o_book).find('.img-source').attr('value').trim();
                    $(o_book).find('.img-rounded').attr('src', s_string);
                });
                //FadeOut Loading Screen
                o_loading.fadeTo(400, 0, function() {
                    o_loading.hide();
                });
            }, 1000);

            /*Hide Vertical shelf when user clicks on close book shelf button*/
            $(document).on('click', '.btn-shelf-close-bottom', function(e) {
                e.preventDefault();
                var $id = $(this).attr('href').replace('#collapse-', '');
                /* var scrollTo = $("#collapse-" + $id).offset().top - 70;
                if(scrollTo < 150){
                    scrollTo = 0;
                } 
                $("html, body").animate({
                    scrollTop: scrollTo
                }, 1000); */
                $("#collapse-" + $id).fadeOut('slow');
                $("#expand-" + $id).fadeIn("slow");
            });

            function reset_filter_selects() {
                $('select#ebook').val('');
                $('select#fiction').val('');
                $('select#page').val('');
                $('select#comprehension').val('');
                $('select#textType').val('');
                $('select#support').val('');
                $('input#bookmarked').attr('checked', null);
                $('input#title').val('');

                return true;
            }

            //Find Selector for filtering
            function find_query_book_page_fiction() {
                var fTheme = $('select#ebook').val();
                var fFiction = $('select#fiction').val();
                var fPage = $('select#page').val();
                var fComp = $('select#comprehension').val();
                var fText = $('select#textType').val();
                var fSupport = $('select#support').val();
                var fBookmarked = $('input#bookmarked').attr('checked');
                var fTitle = $('input#title').val();

                var bSetTheme = (fTheme && fTheme.length > 0);
                var bSetSupport = (fSupport && fSupport.length > 0);
                var bSetFiction = (fFiction && fFiction.length > 0);
                var bSetPage = (fPage && fPage.length > 0);
                var bSetComp = (fComp && fComp.length > 0);
                var bSetText = (fText && fText.length > 0);
                var bBookmarked = (typeof fBookmarked !== 'undefined' && fBookmarked === 'checked');
                var bTitle = (typeof fTitle !== 'undefined' && fTitle !== null && fTitle.length > 0);



                //If No Filters Set: Use Generic Selector to retrieve all books
                var bookSelector = '.thumb.accordion-shelf-book';

                //If any filter set: Generate Specific Selector
                if (!bSetTheme && !bSetFiction && !bSetPage && !bSetComp && !bTitle && !bSetText && !bBookmarked &&
                    !
                    bSetSupport) {
                    bookSelector += '[data-fiction]';
                } else {
                    //If Support Filter Set
                    if (bSetSupport) {
                        bookSelector += '[data-support*="' + fSupport + '"]';
                    }

                    //If Ebook Theme Filter Set
                    if (bSetTheme) {
                        bookSelector += '[data-ebook-theme*="' + fTheme + '"]';
                    }


                    //If Fiction Filter Set
                    if (bSetFiction) {
                        bookSelector += '[data-fiction="' + fFiction + '"]';
                    }
                    //If Page Filter Set
                    if (bSetPage) {
                        bookSelector += '[data-pages="' + fPage + '"]';
                    }
                    //If Comprehension Strategy Filter Set
                    if (bSetComp) {
                        bookSelector += '[data-comprehension*="' + fComp + '"]';
                    }
                    //If Text Type Filter Set
                    if (bSetText) {
                        bookSelector += '[data-text*="' + fText + '"]';
                    }
                    //If Bookmarked Filter Set
                    if (typeof fBookmarked !== 'undefined' && fBookmarked === 'checked') {
                        bookSelector += '[data-bookmarked="1"]';
                    }
                    //If Title Search Filter Has Text
                    if (bTitle) {
                        bookSelector += '[data-title*="' + fTitle.toLowerCase().trim() + '"]';
                    }
                }
                return bookSelector;
            }

            function load_book_array() {
                var book_array = [];
                //Create Master array to store all the books For Filter
                $(data_reading_box).map(function() {
                    books = $(this).find('.carousel-inner .item .row').clone();
                    var s_term = $(this).attr('data-term');
                    if (typeof s_term !== 'undefined') {
                        reading_level = s_term.trim();
                    }
                    a_books.push([reading_level, books]);
                    book_array.push([reading_level, books]);
                });
                return book_array;
            }

            function load_book_array_collapse() {
                var book_array = [];
                //Create Master array to store all the books For Filter
                $(data_reading_box_collapse).map(function() {
                    books = $(this).find('.panel-body .row').clone();
                    var s_term = $(this).attr('data-term');
                    if (typeof s_term !== 'undefined') {
                        reading_level_collapse = s_term.trim();

                    }
                    a_books_collapse.push([reading_level_collapse, books]);
                    book_array.push([reading_level_collapse, books]);
                });
                return book_array;
            }

            function filter_ebook_fiction_page() {
                var selector = find_query_book_page_fiction();

                var a_filter = [];
                if (a_books.length <= 0) {
                    a_filter = load_book_array();
                } else {
                    a_filter = a_books;
                }
                var book_filter = [];
                $(a_filter).each(function(key, value) {
                    var books = value[1].find(selector).clone();
                    var reading_level = value[0];
                    var book_length = books.length / 6;
                    book_filter.push([reading_level, books, book_length]);
                });
                return book_filter;
            }

            function filter_ebook_fiction_page_collapse() {
                var selector = find_query_book_page_fiction();

                var a_filter = [];
                if (a_books_collapse.length <= 0) {
                    a_filter = load_book_array_collapse();
                } else {
                    a_filter = a_books_collapse;
                }
                var book_filter = [];
                $(a_filter).each(function(key, value) {
                    var books = value[1].find(selector).clone();
                    var reading_level = value[0];
                    book_filter.push([reading_level, books]);
                });
                return book_filter;
            }

            function add_book() {
                var book_filter = [];
                var a_filter = [];
                if (a_books.length <= 0) {
                    a_filter = load_book_array();
                } else {
                    a_filter = a_books;
                }
                $(a_filter).each(function(key, value) {
                    var books = value[1].find('.thumb.accordion-shelf-book[data-fiction]').clone();
                    var reading_level = value[0];
                    var book_length = books.length / 6;
                    book_filter.push([reading_level, books, book_length]);
                });
                return book_filter;
            }

            function add_book_collapse() {
                var book_filter = [];
                var a_filter = [];
                if (a_books_collapse.length <= 0) {
                    a_filter = load_book_array_collapse();
                } else {
                    a_filter = a_books_collapse;
                }
                $(a_filter).each(function(key, value) {
                    var books = value[1].find('.thumb.accordion-shelf-book[data-fiction]').clone();
                    var reading_level = value[0];
                    book_filter.push([reading_level, books]);
                });
                return book_filter;
            }

            /* Carousel Settings
             * Dynamically  bookshelf,books, colors and CSS
             * when user clicks on expand bookshelf
             * This function hide horizontal bookshelf and redraw vertical bookshelf
             * */
            $(document).on('click', '.btn-shelf-expand', function(e) {
                e.preventDefault();
                $(window).unbind('scroll');
                var $id = $(this).attr('href').replace('#collapse-', '');
                var $expand = $("#expand-" + $id);
                var $collapse = $("#collapse-" + $id);
                $expand.fadeOut('slow');
                var $items = $expand.find('div.accordion-shelf-book').clone();
                $collapse.find('div.panel-body div.row').empty().append($items);
                $collapse.fadeIn('slow');
            });
            $('.shelf-wrapper.blocked .accordion-shelf-book a').replaceWith(function() {
                return '<div>' + $(this).html() + '</div>';
            });
            //Reset Filter
            $(document).on('click', '.btn-reset-filter', function() {
                //Reset Default value for filter
                reset_filter_selects();

                var item = '<div class="item">';
                var row = '<div class="row">';
                var count;
                var book_list;
                var rows = [];
                var reading_level;
                var row_books;
                var data_book = add_book();
                $('[id^="expand-"] .panel').find('.carousel-inner .item').remove();
                for (var i = 0; i < data_book.length; i++) {
                    count = 0;
                    book_list = data_book[i][1];
                    rows.push(data_book[i][2]);
                    reading_level = data_book[i][0];
                    var o_panel = $('.panel[data-term="' + reading_level + '"]');
                    for (var j = 0; j <= rows[i]; j++) {
                        row_books = book_list.slice(count, count + 6);
                        count = count + 6;
                        o_panel.find('.carousel-inner').append($(item).append($(row).append(row_books)));
                    }
                    //Set class of the first item is active
                    var o_first = o_panel.find('.carousel-inner .item').get(0);
                    $(o_first).addClass('active');
                    //Hide Reset Filter
                    $('#reset-filter').attr('style', 'display:none');
                }
            });
            //Reset Filter
            $(document).on('click', '#reset-filter', function() {
                reset_filter_selects();

                var item = '<div class="item">';
                var row = '<div class="row">';
                var row_collapse = '<div class="row" id="book-collapse">';
                var count;
                var book_list;
                var book_list_collapse;
                var rows = [];
                var reading_level;
                var reading_level_ebook_collapse;
                var row_books;
                var data_book = add_book();

                $('[id^="expand-"]').find('.panel').find('.carousel-inner .item').remove();

                for (var i = 0; i < data_book.length; i++) {
                    count = 0;
                    book_list = data_book[i][1];
                    rows.push(data_book[i][2]);
                    reading_level = data_book[i][0];
                    var o_panel = $('.panel[data-term="' + reading_level + '"]');
                    for (var j = 0; j <= rows[i]; j++) {
                        row_books = book_list.slice(count, count + 6);
                        count = count + 6;
                        o_panel.find('.carousel-inner').append($(item).append($(row).append(row_books)));
                    }

                    //Set class of the first item is active
                    var o_first = o_panel.find('.carousel-inner .item').get(0);
                    $(o_first).addClass('active');
                    //Hide Reset Filter
                    $('#reset-filter').attr('style', 'display:none');
                }

                //Collapse
                var data_book_collapse = add_book_collapse();
                $('[id^="collapse-"] .panel').find('.panel-body .row').remove();

                for (var ii = 0; ii < data_book_collapse.length; ii++) {
                    book_list_collapse = data_book_collapse[ii][1];
                    reading_level_ebook_collapse = data_book_collapse[ii][0];
                    var o_panel_collapse = $('.panel[data-term="' + reading_level_ebook_collapse + '"]');
                    //Add Book to collapse library

                    o_panel_collapse.find('.panel-body').append($(row_collapse).append(book_list_collapse));
                    //Remove Book in Expand library

                    $('[id^="expand-"] .panel').find('#book-collapse').remove();
                }
            });
            //Filter Result Button
            $(document).on('click', '.btn-set-filter', function() {
                var count;
                var book_list;
                var book_list_collapse;
                var rows = [];
                var reading_level_ebook;
                var reading_level_ebook_collapse;
                var row_books;
                var item = '<div class="item">';
                var item_no_book = '<div class="item book_filter">';
                var item_no_book_collapse = '<div class="item book_filter" id="book-collapse">';
                var row = '<div class="row">';
                var row_collapse = '<div class="row" id="book-collapse">';
                var data_book;
                var data_book_collapse;
                //Ebook_theme && Fiction Taxonomy && Page
                data_book = filter_ebook_fiction_page();
                $('[id^="expand-"]').find('.panel').find('.carousel-inner .item').remove();
                for (var i = 0; i < data_book.length; i++) {
                    count = 0;
                    book_list = data_book[i][1];
                    rows.push(data_book[i][2]);
                    reading_level_ebook = data_book[i][0];
                    var o_panel = $('.panel[data-term="' + reading_level_ebook + '"]');
                    for (var j = 0; j <= rows[i]; j++) {
                        if (book_list.length == 0) {
                            $('[id^="-collapse"] .panel').find('#book-collapse').remove();
                            o_panel.find('.carousel-inner').append($(item_no_book).append(
                                'No results were found with your selected filters'));
                        } else {
                            row_books = book_list.slice(count, count + 6);
                            count = count + 6;
                            o_panel.find('.carousel-inner').append($(item).append($(row).append(
                                row_books)));
                        }
                    }
                    //Set class of the first item is active
                    var o_first = o_panel.find('.carousel-inner .item').get(0);
                    $(o_first).addClass('active');
                }

                //Collapse
                /*Remove Book in Collapse library*/

                data_book_collapse = filter_ebook_fiction_page_collapse();
                $('[id^="collapse-"] .panel').find('.panel-body .row').remove();
                for (var i = 0; i < data_book_collapse.length; i++) {
                    book_list_collapse = data_book_collapse[i][1];
                    reading_level_ebook_collapse = data_book_collapse[i][0];
                    var o_panel_collapse = $('.panel[data-term="' + reading_level_ebook_collapse + '"]');
                    //Add Book to collapse library
                    o_panel_collapse.find('.panel-body').append($(row_collapse).append(book_list_collapse));
                    //Remove Book in Expand library
                    $('[id^="expand-"] .panel').find('#book-collapse').remove();
                }

                //Collapse the menu
                $('#close-filter').attr('style', 'display:none');
                //Add Reset Filter
                $('#reset-filter').attr('style', 'display:inline-block');
            });

            //Filter taxonomy
            $(document).on('click', '.btn-filter', function() {
                var this_filter = $(this);
                /*Add or Remove selected class to this btn */
                if (this_filter.hasClass('selected')) {
                    this_filter.removeClass('selected');
                } else {
                    this_filter.addClass('selected');
                }
            });


            $(window).on('load', function() {
                var element = document.querySelector('.panel-2c2-letter-sounds');

                if (element) {
                    // Function to handle the scroll with a "Self-Correction" check
                    function reliableScroll(attempts) {
                        element.scrollIntoView({
                            behavior: 'auto',
                            block: 'center'
                        });

                        // Wait for the smooth scroll animation to likely finish (~800ms)
                        setTimeout(function() {
                            // Check the current distance from the top of the viewport
                            var currentRect = element.getBoundingClientRect();

                            // If top is not near 0 (within 5px), the page layout shifted!
                            if (Math.abs(currentRect.top) > 5 && attempts < 3) {
                                // Force a second precise jump to fix the "miss"
                                element.scrollIntoView({
                                    behavior: 'auto',
                                    block: 'center'
                                });
                                // Increment attempts to prevent infinite loops
                                reliableScroll(attempts + 1);
                            }
                        }, 800);
                    }

                    // Initial trigger after a 300ms safety buffer for images to render
                    setTimeout(function() {
                        reliableScroll(1);
                    }, 300);
                }
            });

        });
    </script>