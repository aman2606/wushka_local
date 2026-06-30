<?php

if (!defined('ABSPATH')) {
    exit(); // Exit if accessed directly
}
include_once 'reading-groups/class_reading-groups.php';
include_once 'bookmarks/class_my-bookmarks.php';

/*
 * Managing Teacher Reading Group CLASS
 *
 */

class Manage_Reading_Groups
{

    //* New System Constants
    private $_o_user;
    private $_i_school;

    private $_a_classes;
    private $_i_class;

    private $_a_groups;
    private $_i_group;

    private $_a_levels;
    private $_a_phases;
    private $_i_level;

    private $_a_books;

    private $_c_rg;
    private $_i_i_paged;

    private $a_bookmarks;

    //Declare Construct Function
    public function __construct($current_user = NULL)
    {
        //Start Session if Not Started
        if (!isset($_SESSION)) {
            session_start();
        }

        $this->_c_rg = new Reading_Groups();
        $this->_i_i_paged = ($i_page = get_query_var('paged')) ? $i_page : 1;

        $this->store_user($current_user);
        $this->store_classes();
        $this->store_levels();
        $this->store_phases();
        $this->store_groups();
        $this->store_group_books();

        //Check For Stored Session Variables
    }

    public function load_stylesheets()
    {
        $s_template_path = get_template_directory_uri();
        $script_vars = '<script>';
        $script_vars .= 'var thm_tmp_fnc_pth = "' . $s_template_path . '"; ';
        $script_vars .= 'var int_teacher_hash = "' . $this->_o_user->id_hash . '"; ';
        $script_vars .= 'var friendly_loading_msg = "' . translate('All items loaded', 'lessonzone') . '"; ';
        $script_vars .= '</script>';

        echo $script_vars;
        //        echo '<link rel="stylesheet" type="text/css" href="' . $s_template_path . '/css/teacher_manage-reading-groups.css">';
        echo '<script src="' . $s_template_path . '/js/teacher_manage-reading-groups.js?ver=' . get_bloginfo('version') . '"></script>';
    }

    private function store_user($current_user = NULL)
    {
        if ($current_user == NULL || ! user_can($current_user, 'teacher')) {
            wp_redirect(home_url() . "/wp-login.php");
            exit;
        }

        $this->_o_user = $current_user;
        //error_log('USER ID:'.$this->_o_user->ID);

        return TRUE;
    }

    private function store_classes()
    {
        $this->_i_class = NULL;
        $this->_i_school = wushka_get_user_school($this->_o_user->ID);
        $a_classes = wushka_get_classes($this->_i_school, $this->_o_user->ID);

        if (! empty($a_classes)) {
            foreach ($a_classes as $o_class) {
                $this->_a_classes[] = $o_class->class_id;
            }
        }

        //Is there a Stored Class?
        if (isset($_SESSION['class_id'])) {
            if (!empty($this->_a_classes)) {
                foreach ($this->_a_classes as $i_key => $i_class) {
                    if ((int) $_SESSION['class_id'] == $i_class) {
                        $this->_i_class = $i_class;
                    }
                }
            }
        }
        if (!empty($this->_a_classes)) {

            if (! isset($this->_i_class)) {
                $this->_i_class = (int) $this->_a_classes[0];
                $_SESSION['class_id'] = (int) $this->_a_classes[0];
            }
        }

        //error_log('Current Class: '. $this->_i_class);
        return TRUE;
    }

    private function store_groups()
    {
        $this->_i_group = NULL;

        if (isset($this->_i_class)) {
            if (($a_groups = $this->_c_rg->get_groups('class', $this->_i_class)) !== FALSE) {
                $this->_a_groups = $a_groups;

                //Is there a Stored Reading Group?
                if (isset($_SESSION['reading_group'])) {
                    foreach ($this->_a_groups as $i_key => $o_group) {
                        if ($_SESSION['reading_group'] == $o_group->ID) {
                            $this->_i_group = $o_group->ID;
                        }
                    }
                }

                if (! isset($this->_i_group)) {
                    unset($_SESSION['reading_group']);
                }
                //error_log('Current Group: '. $this->_i_group);
                return TRUE;
            }
        } else {
            error_log('Manage Reading Groups (get groups): No Class Found');
        }

        error_log('Manage Reading Groups: Reading Groups MISSING, No Reading Groups Meta Data Found.');
        return FALSE;
    }

    private function store_levels()
    {
        $this->_i_level = NULL;
        $this->_a_levels = array();
        $a_terms = get_terms('reading-level', array('orderby' => 'slug', 'order' => 'ASC'));

        if (! is_wp_error($a_terms)) {
            $this->_a_levels = $a_terms;

            $this->_i_level = '15';
            //Is there a Stored Reading Level?
            if (isset($_SESSION['reading_level'])) {
                foreach ($this->_a_levels as $i_key => $o_level) {
                    if ($_SESSION['reading_level'] == $o_level->term_taxonomy_id) {
                        $this->_i_level = $_SESSION['reading_level'];
                    }
                }
            }
            //error_log('Current Level: '. $this->_i_level);
            return TRUE;
        }

        error_log('Manage Reading Groups: Reading Levels MISSING, No Reading Level Terms Found.');
        return FALSE;
    }

    private function store_phases()
    {
        $this->_i_level = NULL;
        $this->_a_phases = array();
        $a_terms = get_terms('phonics-phase', array('orderby' => 'slug', 'order' => 'ASC'));

        if (! is_wp_error($a_terms)) {
            $this->_a_phases = $a_terms;

            $this->_i_level = '15';
            //Is there a Stored Reading Level?
            if (isset($_SESSION['reading_level'])) {
                foreach ($this->_a_phases as $i_key => $o_level) {
                    if ($_SESSION['reading_level'] == $o_level->term_taxonomy_id) {
                        $this->_i_level = $_SESSION['reading_level'];
                    }
                }
            }
            //error_log('Current Level: '. $this->_i_level);
            return TRUE;
        }

        error_log('Manage Reading Groups: Phase Levels MISSING, No Phase Level Terms Found.');
        return FALSE;
    }

    private function get_phase_sounds_map()
    {
        global $wpdb;
        $sql = "
            SELECT t.term_id, t.name, t.slug, tt.term_taxonomy_id,
                   GROUP_CONCAT(pm.meta_value SEPARATOR ' | ') AS esiss_sounds
            FROM {$wpdb->terms} t
            INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id AND tt.taxonomy = 'phonics-phase'
            INNER JOIN {$wpdb->term_relationships} tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
            INNER JOIN {$wpdb->posts} p ON p.ID = tr.object_id AND p.post_type = 'ebook' AND p.post_status = 'publish'
            INNER JOIN {$wpdb->postmeta} pm ON pm.post_id = p.ID AND pm.meta_key = 'esiss_sounds'
            GROUP BY t.term_id ORDER BY t.slug
        ";

        $grouped        = [];
        $seen_per_phase = [];

        foreach ($wpdb->get_results($sql) as $row) {
            // Extract "Phase X" — same regex as get_sound_clusters()
            if (preg_match('/Phase\s+\d+/i', $row->name, $matches)) {
                $phase_key = $matches[0];
            } else {
                $phase_key = $row->name;
            }

            if (!isset($grouped[$phase_key])) {
                $grouped[$phase_key]        = ['first_ttid' => (int) $row->term_taxonomy_id, 'sounds' => []];
                $seen_per_phase[$phase_key] = [];
            }

            $sounds = array_filter(array_map('trim', explode('|', $row->esiss_sounds)), 'strlen');

            foreach ($sounds as $sound) {
                // Canonical-key uniqueness — same approach as get_sound_clusters()
                $parts = preg_split('/[\s,]+/', trim($sound), -1, PREG_SPLIT_NO_EMPTY);
                $canon = implode(',', $parts);

                if (!isset($seen_per_phase[$phase_key][$canon])) {
                    $seen_per_phase[$phase_key][$canon]  = true;
                    $grouped[$phase_key]['sounds'][]     = [
                        'sound' => $sound,
                        'ttid'  => (int) $row->term_taxonomy_id,
                    ];
                }
            }
        }

        // echo "<pre>";
        // print_r($grouped);

        return $grouped;
    }

    private function store_students($i_group = NULL)
    {
        global $wpdb;

        // updated Feb 2019 to prevent count_total performing slow query
        $args = array(
            'role' => 'student',
            'count_total'   => false,
            'meta_query' => array(
                'relation' => 'AND',
                0 => array(
                    'key' => 'class',
                    'value' => $this->_a_classes,
                    'compare' => 'IN'
                ),
                1 => array(
                    'key' => 'active',
                    'value' => 1,
                )
            )
        );

        if (isset($i_group)) {
            $args['meta_query'][2] = array(
                'key' => 'my_reading_group',
                'value' => $i_group
            );
        }

        $o_student_query = new WP_User_Query($args);  // args updated for slow query

        if (!empty($o_student_query->results)) {
            return $o_student_query->results;
        }

        return FALSE;
    }

    private function store_group_books()
    {
        $this->_a_books = array();
        if (isset($this->_i_group)) {
            if (($a_books = $this->_c_rg->get_books($this->_i_group)) !== FALSE) {
                $this->_a_books = $a_books;
            }
        }

        return TRUE;
    }

    public function get_licence()
    {
        $classes = $this->_a_classes;
        $licences = array();
        foreach ($classes as $class) {
            $licences[$class] = get_class_licence($class);
        }
        return $licences;
    }

    public function load_page()
    {
        echo $this->get_page_content();
    }

    /*
     * ==================================================================================
     *
     * 									----- PAGE CONTENT SECTION -----
     *
     * ===================================================================================
     */
    /* ---------------------- MAIN HTML OUTPUT --------------------- */

    private function get_page_content()
    {
        /* --- HTML is Comprised of Three Sections --- */
        // 1) Left SideBar 		- For Reading Level Links
        // 2) Middle Section 	- For Displaying Book Data
        // 3) Right SideBar 	- For Displaying the Student's in each group
        // -------------------------------------------
        // --- Page Load Process ---
        // - Check for Pagination / Session Variables
        // - Load First Group Data
        // - Add Group Data to Initial Layout
        // - Print Layout
        // - Add User WP Nonce
        // - Build JS/Ajax
        /* ------------------------------------------- */
        error_log('----- Manage Reading Groups - PAGE START -----');

        //Store All HTML Data
        $a_page_output[] = '<section class="page-section content-section row">';
        $a_page_output[] = $this->build_page_section_content();
        $a_page_output[] = wp_nonce_field('manage-reading-groups_' . $this->_o_user->id_hash, '_wp_rdm_hash', false, false);
        $a_page_output[] = $this->build_page_section_window();
        $a_page_output[] = '</section>';

        error_log('----- Manage Reading Groups - PAGE END -------');

        //Echo HTML output to Page
        echo implode('', $a_page_output);
    }

    private function build_page_section_content()
    {
        $a_content[] = '<div class="col-xs-12"><p><span class="visible-xs">Drag a Reader from the Reading Level panel to the Reading Group panel below to add it.</span><span class="hidden-xs">Drag a Reader from the Reading Level panel to the Reading Group panel on the right to add it.</span></p></div><span class="clearfix"></span>';
        $a_content[] = '<div class="col-xs-12 col-sm-6 col-md-6">';
        $a_content[] = '<div class="panel panel-default panel-reading-level">';
        $a_content[] = '<div class="panel-heading"><i class="glyphicon glyphicon-left-indent"></i> Reading Level';
        $a_content[] = '<div class="pull-right">';
        $a_content[] = '<div class="form-group btn-group fiction-group" role="group">';
        $a_content[] = '<div class="btn-group">';
        $a_content[] = '<button type="button" class="btn btn-filter btn-fiction" value="fiction">Fiction</button>';
        $a_content[] = '<button type="button" class="btn btn-filter btn-fiction" value="non-fiction">Non-Fiction</button>';
        $a_content[] = '<button type="button" class="btn btn-filter btn-fiction selected" value="both">Both</button>';
        $a_content[] = '</div>';
        $a_content[] = '</div>';
        $a_content[] = '</div>';
        $a_content[] = '</div>';
        $a_content[] = '<div class="panel-body panel-equalHeights">';
        $a_content[] = '<div class="row">';
        $a_content[] = $this->build_reading_level_menu();
        $a_content[] = $this->build_reading_level_content();
        $a_content[] = '</div>';
        $a_content[] = '</div>';
        $a_content[] = '</div>';
        $a_content[] = '</div>';

        $a_content[] = '<div class="col-xs-12 col-sm-6 col-md-6">';
        $a_content[] = '<div class="panel panel-default panel-reading-group">';
        $a_content[] = '<div class="panel-heading"><i class="glyphicon glyphicon-show-thumbnails"></i> Reading Group';
        $a_content[] = '<span class="pull-right">' . implode('', $this->build_group_btn_edit()) . '</span>';
        $a_content[] = '</div>';
        $a_content[] = '<div class="panel-body panel-equalHeights">';
        $a_content[] = '<div class="row">';
        $a_content[] = $this->build_reading_group_menu();
        $a_content[] = $this->build_reading_group_content();
        $a_content[] = '</div>';
        $a_content[] = '</div>';
        $a_content[] = '</div>';
        $a_content[] = '</div>';

        return implode('', $a_content);
    }

    /* ==================================================================================
     *
     * 							----- PAGE MENU SECTION -----
     *
     * ==================================================================================
     */

    private function build_reading_level_menu()
    {

        $a_section[] = '<div class="col-lg-3">';

        if (hasLevelledAccess()) {
            //Gather Reading level Data
            $a_levels = $this->build_reading_level_menu_items();
            $a_section[] = '<div class="panel panel-default reading-group-menu reading-group-levelled-menu" style="display: none;">';
            $a_section[] = '<div class="panel-heading"><i class="glyphicon glyphicon-menu-hamburger"></i> Levelled</div>';
            $a_section[] = '<div class="panel-body">';
            $a_section[] = implode('', $a_levels);
            $a_section[] = '</div>';
            $a_section[] = '</div>';
        }
        if (hasDecodableAccess()) {
            //Gather Phonics Phase Data
            $a_levels = $this->build_phonics_level_menu_items();
            $a_section[] = '<div class="panel panel-default reading-group-menu reading-group-decodables-menu" style="display: none;">';
            $a_section[] = '<div class="panel-heading"><i class="glyphicon glyphicon-menu-hamburger"></i> Decodables</div>';
            $a_section[] = '<div class="panel-body">';
            $a_section[] = implode('', $a_levels);
            $a_section[] = '</div>';
            $a_section[] = '</div>';
        }
        $a_section[] = '</div>';

        return implode('', $a_section);
    }

    private function build_reading_level_menu_items()
    {
        $a_items = array();
        $a_level_menu = array();

        foreach ($this->_a_levels as $i_key => $o_level) {
            $a_items[] = implode('', $this->level_menu_item($o_level));
        }

        $a_level_menu[] = '<div class="list-group level-menu-list">';
        $a_level_menu[] = implode('', $a_items);
        $a_level_menu[] = '</div>';
        unset($a_items);

        return $a_level_menu;
    }

    private function build_phonics_level_menu_items()
    {
        $sounds_map   = $this->get_phase_sounds_map();
        $a_level_menu = [];

        $a_level_menu[] = '<div class="list-group level-menu-list phonics-accordion">';


        foreach ($sounds_map as $phase_key => $phase_data) {
            $sounds     = $phase_data['sounds'];
            $first_ttid = $phase_data['first_ttid'];

            if (empty($sounds)) continue;

            $a_name    = explode(' ', $phase_key);
            $main_text = ucwords($a_name[0]) . (isset($a_name[1]) ? '&nbsp;' . ucwords($a_name[1]) : '');

            $inner = $main_text
                   . '<span class="glyphicon glyphicon-chevron-right pull-right phonics-chevron"></span>';

            $a_level_menu[] = '<div class="phonics-phase-wrap">';
            $a_level_menu[] = '<a href="#" class="list-group-item phonics-phase-header"'
                . ' data-phase-id="' . esc_attr($first_ttid) . '"'
                . ' title="' . esc_attr($phase_key) . '">';
            $a_level_menu[] = $inner;
            $a_level_menu[] = '</a>';

            $a_level_menu[] = '<div class="phonics-sounds-list" style="display:none">';
            foreach ($sounds as $i => $sound_data) {
                $a_level_menu[] = '<a href="#" class="list-group-item level-menu-item phonics-sound-item"'
                    . ' id="phonics-sound-' . esc_attr($first_ttid) . '-' . $i . '"'
                    . ' data-level-id="' . esc_attr($sound_data['ttid']) . '"'
                    . ' data-sound="' . esc_attr($sound_data['sound']) . '">'
                    . esc_html($sound_data['sound'])
                    . '</a>';
            }
            $a_level_menu[] = '</div>';

            $a_level_menu[] = '</div>';
        }

        $a_level_menu[] = '</div>';
        return $a_level_menu;
    }

    private function level_menu_item($o_level)
    {
        if (isset($o_level) && is_object($o_level)) {

            $s_name = str_replace('Levels ', '', $o_level->name);
            $a_name = explode(' ', $s_name);
            $s_active = NULL;

            if (isset($this->_i_level) && $this->_i_level == $o_level->term_taxonomy_id) {
                $s_active = 'active';
                $_SESSION['reading_level'] = $this->_i_level;
            }

            $a_item[] = '<a href="#" class="list-group-item level-menu-item ' . $s_active . '" id="reading-level-' . $o_level->term_taxonomy_id . '" title="' . $o_level->name . '">';
            if (count($a_name) <= 2) {
                $a_item[] = ucwords($a_name[0]) . '<span class="pull-right">' . $a_name[1] . '</span>';
            } else {
                $a_item[] = ucwords($a_name[0]) . '&nbsp;' . ucwords($a_name[1]) . '<small class="pull-right">' . $a_name[2] . '&nbsp;' . $a_name[3] . '</small>';
            }
            $a_item[] = '</a>';

            return $a_item;
        }

        return FALSE;
    }

    private function build_reading_group_menu()
    {
        //Gather Reading level Data
        $a_groups = $this->build_reading_group_menu_items();

        $a_section[] = '<div class="col-lg-3"><div class="panel panel-default reading-group-menu">';
        $a_section[] = '<div class="panel-heading"><i class="glyphicon glyphicon-stop"></i> Groups</div>';
        $a_section[] = '<div class="panel-body">';
        $a_section[] = implode('', $a_groups);
        $a_section[] = '</div>';
        $a_section[] = '</div></div>';

        return implode('', $a_section);
    }

    private function build_reading_group_menu_items()
    {
        $a_items = array();
        $a_group_menu = array();

        //Loop Through Reading Groups and Store Data and HTML Output in Array
        if (! empty($this->_a_groups)) {
            foreach ($this->_a_groups as $i_key => $o_group) {
                $a_items[] = implode('', $this->group_menu_item($o_group));
            }
        } else {
            $_SESSION['reading_group'] = NULL;
            $this->_i_group = NULL;
        }

        $a_group_menu[] = '<div class="list-group group-menu-list">';
        $a_group_menu[] = implode('', $this->build_group_btn_add());
        $a_group_menu[] = implode('', $a_items);
        $a_group_menu[] = '</div>';

        return $a_group_menu;
    }

    private function group_menu_item($o_group)
    {
        if (isset($o_group) && is_object($o_group)) {

            $s_title = ucwords($o_group->group_name);
            $s_active = NULL;

            //Check if this Group is active
            if (isset($this->_i_group) && $this->_i_group == $o_group->ID) {
                $s_active = 'active';
                $_SESSION['reading_group'] = $o_group->ID;
            }

            $a_item[] = '<a href="#" id="reading-group-' . $o_group->ID . '" class="list-group-item group-menu-item ' . $s_active . '">';
            $a_item[] = $s_title;
            $a_item[] = '<span class="sr-only">Reading Group ' . $s_title . '</span>';
            $a_item[] = '</a>';

            return $a_item;
        }

        return FALSE;
    }


    /* /\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/ */
    /* Code duplicated by Javier - READING LEVEL (DROPDOWN) MENU --------------------------------------------------------------- */
    /* /\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/ */
    //Runs the Query that gathers and displays the Reading Level HTML
    private function build_reading_level_menu_items_dropdown()
    {
        if ($this->_a_levels === FALSE) {
            $s_term_html = '<select class="menu-select level-select">';
            $s_term_html .= '<option id="no-reading-levels" class="menu-item level-item no-reading-levels"> No Reading Levels Found </option>';
            $s_term_html .= '</select>';
        } else {
            foreach ($this->_a_levels as $i_key => $o_level) {
                //Check Session Variable for Stored TermTaxID
                $s_session_class = NULL;
                if (isset($this->_i_level) && $this->_i_level == $o_level->term_taxonomy_id) {
                    $s_session_class = 'current';
                    $_SESSION['reading_level'] = $this->_i_level;
                }

                $s_name = trim(preg_replace('/\(([A-Za-z]+ [0-9]+-[0-9]+)\)/', '', $o_level->name));
                $s_name = trim(preg_replace('/\(([A-Za-z]+-[0-9]+\++\))/', '', $s_name));

                //Store HTML For Term Output
                //$s_term_html = '<span class="menu-item level-item ' . $s_session_class . ' " id="reading-level-' . $o_level->term_taxonomy_id . '" title="' . $o_level->name . '">';
                //$s_term_html .= '<p>' . $s_name . '</p>';
                //$s_term_html .= '</span>';

                //Store HTML For Term Output
                $s_term_html = '<select class="menu-select level-select">';
                $s_term_html .= '<option id="reading-level-' . $o_level->term_taxonomy_id . '" class="menu-item level-item ' . $s_session_class . ' ">' . $s_name . '</option>';
                $s_term_html .= '</select>';

                //push to array for later use
                $a_terms_html[] = $s_term_html;
                unset($s_term_name, $s_term_html);
            }
        }

        return $a_terms_html;
    }
    /* /\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/ */
    /* End of Code duplicated by Javier - READING LEVEL (DROPDOWN) MENU -------------------------------------------------------- */
    /* /\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/ */



    public function build_group_btn_add()
    {
        //Add 'Create' Button to menu
        $a_add[] = '<a href="#" class="list-group-item list-reading-group" id="reading-group-new" data-toggle="modal" data-target="#reading-group-modal" title="Create a new Group">';
        $a_add[] = '<i class="glyphicon glyphicon-plus"></i>';
        $a_add[] = ' Create New';
        $a_add[] = '</a>';

        return $a_add;
    }

    public function build_group_btn_edit()
    {
        //Add 'Rename' Button to menu
        $a_button[] = '<button type="button" class="btn btn-default btn-edit-group" data-toggle="modal" data-target="#group-edit-modal" title="View more options for this reading group" >';
        $a_button[] = '<i class="glyphicon glyphicon-settings"></i>';
        $a_button[] = '</button>';
        return $a_button;
    }

    public function build_group_btn_rename()
    {
        //Add 'Rename' Button to menu
        $a_rename[] = '<span id="reading-group-remame" class="menu-item group-item rename btn btn-default" title="Rename this Group">';
        $a_rename[] = 'Rename';
        $a_rename[] = '</span>';
        return $a_rename;
    }

    public function build_group_btn_delete()
    {
        //Add 'Delete' Button to menu
        $a_delete[] = '<span id="reading-group-delete" class="menu-item group-item delete btn btn-default" title="Delete this Group">';
        $a_delete[] = 'Delete';
        $a_delete[] = '</span>';
        return $a_delete;
    }

    private function build_reading_level_content()
    {

        $c_bookmarks = new Wushka_Bookmarks($this->_o_user->ID, 'teacher');
        $this->a_bookmarks = $c_bookmarks->get_book_list();

        $a_components = $this->build_reading_level_html();

        $a_level[] = '<div class="col-lg-9">';
        $a_level[] = '<div class="panel panel-default panel-level-books">';
        $a_level[] = '<div class="panel-heading"><i class="glyphicon glyphicon-book"></i> Readers</div>';
        $a_level[] = '<div class="panel-body scroll">';
        $a_level[] = '<div class="level-content-wrap">';
        $a_level[] = '<div class="level-wrap books-wrap" data-id="books-page-1" data-paged="' . $this->_i_i_paged . '">';
        $a_level[] = implode('', $a_components['books']);
        $a_level[] = '</div>';
        $a_level[] = '<div class="level-wrap buttons-wrap">';
        if (! empty($a_components['buttons'])) {
            $a_level[] = '<nav id="navigation">';
            $a_level[] = '<ul class="pager pagination pagination-lg">';
            $a_level[] = '<li id="navigation-previous">';
            $a_level[] = '<a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
            $a_level[] = '</li>';
            $a_level[] = implode('', $a_components['buttons']);
            $a_level[] = '<li id="navigation-next">';
            $a_level[] = '<a href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>';
            $a_level[] = '</li>';
            $a_level[] = '</ul>';
            $a_level[] = '</nav>';
        }
        $a_level[] = '</div>';
        $a_level[] = '</div>';
        $a_level[] = '</div>';
        $a_level[] = '</div></div>';

        return implode('', $a_level);
    }

    private function build_reading_group_content()
    {
        $a_books = $this->build_reading_group_html();
        $a_students = $this->build_student_html();

        $a_group[] = '<div class="col-lg-6">';
        $a_group[] = '<div class="panel panel-default reading-group-menu image-list">';
        $a_group[] = '<div class="panel-heading"><i class="glyphicon glyphicon-log-book"></i> Group Readers</div>';
        $a_group[] = '<div class="panel-body">';
        $a_group[] = '<div class="list-group group-content-list">';
        $a_group[] = '<div class="group-wrap books-wrap" data-id="books-page-' . $this->_i_i_paged . '">';
        $a_group[] = implode('', $a_books);
        $a_group[] = '</div>';
        $a_group[] = '</div>';
        $a_group[] = '</div>';
        $a_group[] = '</div></div>';
        $a_group[] = '<div class="col-lg-3">';
        $a_group[] = '<div class="panel panel-default reading-group-menu">';
        $a_group[] = '<div class="panel-heading"><i class="glyphicon glyphicon-user"></i> Group Students</div>';
        $a_group[] = '<div class="panel-body">';
        $a_group[] = '<div class="users-content-wrap">';
        $a_group[] = implode('', $a_students);
        $a_group[] = '</div>';
        $a_group[] = '</div>';
        $a_group[] = '</div></div>';

        return implode('', $a_group);
    }

    private function build_reading_group_html()
    {
        if (! isset($this->_i_group)) {
            return $this->no_group_item();
        }

        //Load Meta Data of Current Reading Group
        if (empty($this->_a_books)) {
            //Group is Empty
            return $this->empty_group_item();
        }

        $a_books = array();
        foreach ($this->_a_books as $o_book) {
            if ((int) $o_book->active == 1) {
                $a_books[] = (int) $o_book->post_id;
            }
        }

        $a_args = array(
            'post_type' => 'ebook',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'include' => $a_books,
            'orderby'    => 'post__in',
        );

        $a_posts = array();
        if (! empty($a_books) > 0) {
            $a_posts = get_posts($a_args);
        }


        if (! isset($a_posts) || empty($a_posts)) {
            //Group is Empty
            return $this->empty_group_item();
        }

        $a_group_books = array();
        $a_posts = $this->post_filter_per_class_licence($a_posts);
        //Check again if post is empty or not after filter
        if (! isset($a_posts) || empty($a_posts)) {
            //Group is Empty
            return $this->empty_group_item();
        }
        foreach ($a_posts as $i_key => $o_book) {
            $a_group_books[] = implode('', $this->group_content_item($o_book));
        }

        return $a_group_books;
    }

    private function post_filter_per_class_licence($a_posts)
    {
        $class_id = $this->get_class_id_from_group_id($this->_i_group);

        if (!$class_id) {
            return $a_posts;
        }

        $licence = get_class_licence($class_id);

        $posts = array();
        foreach ($a_posts as $a_post) {
            $book_type = 'levelled';
            if (has_term('', 'phonics-phase', $a_post)) {
                $book_type = 'decodable';
            }
            if ($licence == 'Wushka Decodables') {
                if ($book_type == 'decodable') {
                    array_push($posts, $a_post);
                }
            } elseif ($licence == 'Wushka Levelled') {
                if ($book_type == 'levelled') {
                    array_push($posts, $a_post);
                }
            } else {
                array_push($posts, $a_post);
            }
        }
        return $posts;
    }

    private function get_class_id_from_group_id($group_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wushka_reading_groups';
        $sql = 'SELECT `class_id` FROM ' . $table_name . ' WHERE ID = %d';

        $results = $wpdb->get_results(
            $wpdb->prepare($sql, $group_id)
        );

        if (isset($results) && !empty($results)) {
            return $results[0]->class_id;
        }

        return;
    }


    private function group_content_item($o_book = NULL)
    {
        if (! isset($o_book)) {
            return array();
        }

        //Determine Reading Level current book is from
        $s_level_slug = null;
        $_level_name = null;

        if (isset($this->_a_levels) && ! empty($this->_a_levels)) {
            foreach ($this->_a_levels as $o_level) {
                if (has_term($o_level->term_id, 'reading-level', $o_book->ID)) {
                    $s_level_name = $o_level->name;
                    $s_level_slug = $o_level->slug;
                }
            }
        }

        //Determine if book is fiction or non-fiction
        $s_fiction = 'NF';
        $s_fic_title = 'Non-Fiction';
        if (has_term('fiction', 'fiction', $o_book->ID)) {
            $s_fiction = 'F';
            $s_fic_title = 'Fiction';
        }

        //-----Book Item HTML-----\\
        //$a_book[] = '<a href="#" class="list-group-item group-content-item book-item col-xs-6" id="book-' . $o_book->ID . '">';   //Replaced with div to validate html (↓)
        $a_book[] = '<div class="list-group-item group-content-item book-item col-xs-6" id="book-' . $o_book->ID . '">';
        $a_book[] = '<input type="hidden" class="book-value resource" id="resource-' . $o_book->esiss_resource_id . '" />';
        $a_book[] = '<img class="post-image img-responsive" src="' . $o_book->post_image . '" alt=""/>';
        $a_book[] = '<div class="book-cover" title="' . $o_book->post_title . '">';
        $a_book[] = implode('', $this->single_book_archive_button());
        $a_book[] = implode('', $this->single_book_remove_button());
        $a_book[] = implode('', $this->single_book_details_button());
        $a_book[] = '</div>';
        $a_book[] = '<div class="info-wrap">';
        $a_book[] = '<div class="book-level ' . $s_level_slug . '" title="' . $s_level_name . '"></div>';
        $a_book[] = '<div class="book-genre" title="' . $s_fic_title . '">' . $s_fiction . '</div>';
        $a_book[] = '</div>';
        $a_book[] = '</div>';
        //$a_book[] = '</a>';  //Replaced with div to validate html

        return $a_book;
    }

    private function single_book_details_button()
    {
        $a_item[] = '<button type="button" class="btn btn-small" data-id="book-view" data-toggle="modal" data-target="#reading-group-modal" title=" View additional details about this reader">';
        $a_item[] = '<i class="glyphicon glyphicon-search"></i>';
        $a_item[] = '</button>';
        return $a_item;
    }

    private function single_book_remove_button()
    {
        $a_item[] = '<button type="button" class="btn btn-small" data-id="book-delete" title="Delete this reader from the group" >';
        $a_item[] = '<i class="glyphicon glyphicon-remove-2"></i>';
        $a_item[] = '</button>';
        return $a_item;
    }

    private function single_book_archive_button()
    {
        $a_item[] = '<button type="button" class="btn btn-small" data-id="book-archive" title="Archive this reader">';
        $a_item[] = '<i class="glyphicon glyphicon-folder-flag"></i>';
        $a_item[] = '</button>';
        return $a_item;
    }

    private function empty_group_item()
    {
        $a_item[] = '<a href="#" class="list-group-item empty-group-item">' .
            'This reading group does not contain any readers. Readers can be added by selecting them from the reading levels.' .
            '</a>';

        return $a_item;
    }

    private function no_group_item()
    {
        $a_item[] = '<a href="#" class="list-group-item empty-group-item">' .
            'Please create or select a reading group' .
            '</a>';

        return $a_item;
    }

    private function empty_level_item()
    {
        $a_item[] = '<a href="#" class="level-content-item empty-empty-item">' .
            'Select a Reading Level Colour to start allocating readers to a Group' .
            '</a>';

        return $a_item;
    }

    private function build_reading_level_html()
    {
        $a_return = array('books' => array(), 'buttons' => array());

        if (! isset($this->_i_level)) {
            $a_return['books'] = $this->empty_level_item();
            return $a_return;
        }

        $a_archived = array();
        $a_books     = array();
        if (! empty($this->_a_books)) {
            foreach ($this->_a_books as $o_book) {
                if ((int) $o_book->active == 1) {
                    $a_books[] = $o_book->post_id;
                } else {
                    $a_archived[] = $o_book->post_id;
                }
            }
        }

        //$limit = 6;
        $limit = -1;

        $page_limit = ($this->_i_i_paged - 1) * $limit;

        $a_args = array(
            'post_type'         => 'ebook',
            'post_status'         => 'publish',
            'posts_per_page'     => $limit,
            'offset'             => $page_limit,
            'exclude'             => $a_books,
            'orderby'            => 'date',
            'tax_query'         => array(
                array(
                    'taxonomy'     => 'reading-level',
                    'terms'          => $this->_i_level
                )
            )
        );

        $a_posts = get_posts($a_args);
        if (! isset($a_posts) || empty($a_posts)) {
            //Level is Empty
            $a_return['books'] = $this->empty_level_item();
            return $a_return;
        }

        $a_return['buttons'] = $this->level_pagination($limit, $a_books);

        //Load Meta Data of Current Reading Group
        $a_level_books = array();

        if ($a_posts !== NULL && ! empty($a_posts)) {
            foreach ($a_posts as $o_book) {
                $b_archive = (in_array($o_book->ID, $a_archived)) ? TRUE : FALSE;
                $b_bookmark = (in_array($o_book->ID, $this->a_bookmarks)) ? TRUE : FALSE;
                $a_level_books[] = implode('', $this->level_content_item($o_book, $b_archive, $b_bookmark));
            }
        }

        $a_return['books'] = $a_level_books;

        return $a_return;
    }


    private function level_content_item($o_book = NULL, $b_archive = FALSE, $b_bookmark = FALSE)
    {
        if (! isset($o_book)) {
            return array();
        }
        $arr = array("fiction");
        $s_value = null;
        foreach ($arr as $id => $taxonomy) {
            $index = get_taxonomy($taxonomy);
            $args = array('orderby' => 'slug', 'order' => 'ASC');
            $terms = get_terms($taxonomy, $args);
            $term_option = null;
            foreach ($terms as $id => $term) {
                $s_taxonomy = $term->taxonomy;
                $s_term = $term->slug;
                if (has_term($s_term, $s_taxonomy, $o_book->ID)) {
                    $term_option .= $s_term;
                }
            }
            $s_value .= 'data-' . $s_taxonomy . '="' . $term_option . '"';
        }

        $a_item[] = '<div class="level-content-item book-item col-xs-6 col-lg-4" id="book-' . $o_book->ID . '" ' . $s_value . '>';
        $a_item[] = '<input type="hidden" class="book-value resource" id="resource-' . $o_book->esiss_resource_id . '" />';
        $a_item[] = '<img class="post-image img-responsive" src="' . $o_book->post_image . '" title="' . $o_book->post_title . '" />';
        $a_item[] = '<div class="book-cover">';
        if ($b_archive === TRUE) {
            $a_item[] = '<button type="button" data-id="book-add" class="btn btn-small btn-archive-retrieve" title="Retrieve this Book from the group archive"><span class="glyphicon glyphicon-folder-plus"></span></button>';
        } else {
            $a_item[] = '<button type="button" data-id="book-add" class="btn btn-small btn-add-book" title="Add this Book to your current Reading Group"><span class="glyphicon glyphicon-plus"></span></button>';
        }

        //Show Bookmarked Resources
        if ($b_bookmark) {
            $a_item[] = '<button type="button" class="btn btn-small btn-bookmark" title="You have bookmarked this resource"><i class="glyphicon glyphicon-star starred"></i></button>';
        }

        $a_item[] = '<button type="button" data-id="book-view" class="btn btn-small btn-view-book" data-toggle="modal" data-target="#reading-group-modal" title="View additional details about this book"><span class="glyphicon glyphicon-search"></span></button>';
        $a_item[] = '</div>';
        $a_item[] = '</div>';

        return $a_item;
    }






















    private function level_pagination($i_limit = -1,  $a_books = array())
    {
        $a_args = array(
            'post_type'         => 'ebook',
            'post_status'         => 'publish',
            'posts_per_page'     => -1,
            'exclude'             => $a_books,
            'tax_query'         => array(
                array(
                    'taxonomy'     => 'reading-level',
                    'terms'          => $this->_i_level
                )
            )
        );

        $a_total = get_posts($a_args);

        $i_total = count($a_total);
        unset($a_total);

        $i_count = $i_total / $i_limit;
        $i_count = ceil($i_count);

        $a_paged = array();

        for ($ii = 1; $ii <= $i_count; $ii++) {
            $s_end = null;
            if ($ii == 1) {
                $s_end = 'first';
            } else if ($ii == $i_count) {
                $s_end = 'last';
            }
            $s_active = ($ii == $this->_i_i_paged) ? 'active' : null;
            $a_paged[] = '<li class="level-pages ' . $s_active . ' ' . $s_end . '" id="page-' . $ii . '"><a href="#">' . $ii . '</a></li>';
        }

        return $a_paged;
    }

    /*
     * ==================================================================================
     *
     * 							----- GROUP STUDENTS SECTION -----
     *
     * ==================================================================================
     */

    private function build_student_html()
    {
        //Generate the Reading Group Student List
        $a_students = $this->build_student_content();

        //Build The HTML For the Section
        $a_section_output[] = '<div class="list-group users-content-list">';
        $a_section_output[] = implode('', $a_students['current']);
        $a_section_output[] = '</div>';

        return $a_section_output;
    }

    private function get_student_levels($o_student)
    {

        $a_levels = get_terms('reading-level', array(
            'orderby' => 'slug',
            'order'   => 'ASC'
        ));

        $this->a_all_levels = $a_levels;

        if (is_object($o_student) && property_exists($o_student, 'prepared_shelves')) {
            $a_users = $o_student->prepared_shelves;
        }

        foreach ($a_levels as $idx => $o_level) {
            if (! isset($a_users) || empty($a_users) || in_array($o_level->slug, $a_users)) {
                $this->a_user_levels[] = $o_level->term_id;
            }
        }

        error_log('# Reading Levels: ' . count($this->a_all_levels));
        error_log('# User Levels: ' . count($this->a_user_levels));

        return TRUE;
    }

    private function build_student_content()
    {

        $a_students = $this->store_students($this->_i_group);

        $a_students_html = array(
            'current' => array(),
            'assigned' => array(),
            'unassigned' => array(),
        );

        //Generate HTML data from the Teacher's Students

        /* Sorting By Last Name */
        if (isset($a_students) && !empty($a_students)) {
            $sort_last_name = usort($a_students, function ($a, $b) {
                if ($cmp = strnatcasecmp($a->last_name, $b->last_name)) {
                    return $cmp;
                }

                return strnatcasecmp($a->first_name, $b->first_name);
            });
        }

        if (isset($this->_i_group) && ! empty($a_students)) {
            foreach ($a_students as $idx => $o_user) {
                if ((int) $o_user->my_reading_group != (int) $this->_i_group) {
                    continue;
                }
                $a_student_level = $this->get_student_levels($o_user->id_hash);

                $s_type = 'current';
                $s_name = $o_user->first_name . ' ' . $o_user->last_name;


                $a_student_html[] = '<a href="#" class="list-group-item users-content-item" id="student-' . $o_user->id_hash . '">';


                $rg_na_message = "No Reading level";
                if (!empty($o_user->reading_level)) {
                    if (is_array($o_user->reading_level)) {
                        $reading_level_name = (!empty($o_user->reading_level['name'])) ? $o_user->reading_level['name'] : $rg_na_message;
                    } else if (is_object($o_user->reading_level)) {
                        $reading_level_name = (!empty($o_user->reading_level->name)) ? $o_user->reading_level->name : $rg_na_message;
                    } else {
                        $reading_level_name = (!empty($o_user->reading_level)) ? $o_user->reading_level : $rg_na_message;
                    }
                    $a_student_html[] = ucwords($s_name) . ": " . $reading_level_name;
                } else {
                    $a_student_html[] = ucwords($s_name) . ": " . $rg_na_message;
                }

                $a_student_html[] = '</a>';

                $a_students_html[$s_type][] = implode('', $a_student_html);
                unset($a_student_html);
            }

            if (count($a_students_html['current']) > 0) {
                return $a_students_html;
            }
        }

        return array(
            'current' => array('<a href="#" class="list-group-item users-content-item">No students assigned to this group</a>'),
            'assigned' => array(),
            'unassigned' => array(),
        );
    }

    /*
     * ==================================================================================
     *
     * 							----- PAGE SECTION WINDOW -----
     *
     * ==================================================================================
     */

    private function build_page_section_window()
    {
        $a_window_html[] = implode('', $this->book_details_modal());
        $a_window_html[] = implode('', $this->duplicate_book_modal());
        $a_window_html[] = implode('', $this->group_edit_modal());
        $a_window_html[] = implode('', $this->confirm_delete_modal());
        $a_window_html[] = implode('', $this->no_group_modal());

        return implode('', $a_window_html);
    }

    /* ----------- Modals ----------- */
    private function book_details_modal()
    {
        $a_modal[] = '<div class="modal fade" id="reading-group-modal" tabindex="-1" role="dialog" aria-labelledby="reading-group-modal" aria-hidden="true">';
        $a_modal[] = '<div class="modal-dialog">';
        $a_modal[] = '<div class="modal-content">';
        $a_modal[] = '<div class="modal-header">';
        $a_modal[] = '<h3>Reading Group</h3>';
        $a_modal[] = '<label class="subheading">Your selected reading group</label>';
        $a_modal[] = '</div>';
        $a_modal[] = '<div class="modal-body">';
        $a_modal[] = '</div>';
        $a_modal[] = '<div class="modal-footer">';
        $a_modal[] = '<label class="save-msg"></label>';
        $a_modal[] = '<label class="error-msg"></label>';
        $a_modal[] = '<button type="button" id="submit-modal" class="btn btn-primary btn-submit" data-dismiss="modal">Submit</button>';
        $a_modal[] = '<button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>';
        $a_modal[] = '</div>';
        $a_modal[] = '</div>';
        $a_modal[] = '</div>';
        $a_modal[] = '</div>';

        return $a_modal;
    }

    private function duplicate_book_modal()
    {
        $a_modal[] = '<div class="modal fade" id="duplicate-book-modal" tabindex="-1" role="dialog" aria-labelledby="duplicate-book-modal" aria-hidden="true">';
        $a_modal[] = '<div class="modal-dialog">';
        $a_modal[] = '<div class="modal-content">';
        $a_modal[] = '<div class="modal-header">';
        $a_modal[] = '<h3>Already Added</h3>';
        $a_modal[] = '<label class="subheading">You have already added that book to the current reading group.</label>';
        $a_modal[] = '</div>';
        $a_modal[] = '<div class="modal-footer">';
        $a_modal[] = '<button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>';
        $a_modal[] = '</div>';
        $a_modal[] = '</div>';
        $a_modal[] = '</div>';
        $a_modal[] = '</div>';

        return $a_modal;
    }

    private function group_edit_modal()
    {
        $a_modal[] = '<div class="modal fade" id="group-edit-modal" tabindex="-1" role="dialog" aria-labelledby="group-edit-modal" aria-hidden="true">';
        $a_modal[] = '<div class="modal-dialog">';
        $a_modal[] = '<div class="modal-content">';
        $a_modal[] = '<div class="modal-header">';
        $a_modal[] = '<h3>Edit Reading Group</h3>';
        $a_modal[] = '<label class="subheading">Make changes to this reading group here</label>';
        $a_modal[] = '</div>';
        $a_modal[] = '<div class="modal-body">';
        $s_button_attr = 'type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#reading-group-modal" data-dismiss="modal"';
        $a_modal[] = '<div class="row">';
        $a_modal[] = '<div class="col-xs-4">';
        $a_modal[] = '<button id="rename-group" title="Rename this group" style="background-color:#8BC34A;border-color:#72A932;" ' . $s_button_attr . ' >';
        $a_modal[] = '<i class="glyphicon glyphicon-edit x2" style="left:5px;top:-4px;"></i>';
        $a_modal[] = '</button>';
        $a_modal[] = '<label class="section-label">Rename</label>';
        $a_modal[] = '</div>';
        $a_modal[] = '<div class="col-xs-4">';
        $a_modal[] = '<button id="archive-books" title="Archive all the books in this reading group" style="background-color:#FFC107;border-color:#FFC107;" ' . $s_button_attr . ' >';
        $a_modal[] = '<i class="glyphicon glyphicon-folder-flag x2"></i>';
        $a_modal[] = '</button>';
        $a_modal[] = '<label class="section-label">Archive all books</label>';
        $a_modal[] = '</div>';
        /* 	        	$a_modal[] = '<div class="col-xs-3">';
	        		$a_modal[] = '<button id="archive-students" title="Archive all the students in this group" style="background-color:#FF9800;border-color:#DD8A0F;" '.$s_button_attr.' >';
	        			$a_modal[] = '<i class="glyphicon glyphicon-group x2"></i>';
	        		$a_modal[] = '</button>';
	        		$a_modal[] = '<label class="section-label">Archive</label>';
	        	$a_modal[] = '</div>'; */
        $a_modal[] = '<div class="col-xs-4">';
        $a_modal[] = '<button id="delete-group" ' . $s_button_attr . '>';
        $a_modal[] = '<span class="sr-only">Delete</span>';
        $a_modal[] = '<i class="glyphicon glyphicon-remove x2"></i>';
        $a_modal[] = '</button>';
        $a_modal[] = '<label class="section-label">Delete</label>';
        $a_modal[] = '</div>';
        $a_modal[] = '</div>';
        $a_modal[] = '</div>';
        $a_modal[] = '<div class="modal-footer">';
        $a_modal[] = '<button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>';
        $a_modal[] = '</div>';
        $a_modal[] = '</div>';
        $a_modal[] = '</div>';
        $a_modal[] = '</div>';

        return $a_modal;
    }

    private function no_group_modal()
    {
        $a_modal[] = '<div class="modal fade" id="no-group-modal" tabindex="-1" role="dialog" aria-labelledby="no-group-modal" aria-hidden="true">';
        $a_modal[] = '<div class="modal-dialog">';
        $a_modal[] = '<div class="modal-content">';
        $a_modal[] = '<div class="modal-header">';
        $a_modal[] = '<h3>No Reading Group Selected</h3>';
        $a_modal[] = '<label class="subheading">Select a reading group first</label>';
        $a_modal[] = '</div>';
        $a_modal[] = '<div class="modal-footer" style="border-top:none;margin-top:0;">';
        $a_modal[] = '<button type="button" class="btn btn-primary btn-close" data-dismiss="modal">Close</button>';
        $a_modal[] = '</div>';
        $a_modal[] = '</div>';
        $a_modal[] = '</div>';
        $a_modal[] = '</div>';

        return $a_modal;
    }

    private function confirm_delete_modal()
    {
        $a_modal[] = '<div class="modal fade" id="group-delete-modal" tabindex="-1" role="dialog" aria-labelledby="group-delete-modal" aria-hidden="true">';
        $a_modal[] = '<div class="modal-dialog">';
        $a_modal[] = '<div class="modal-content">';
        $a_modal[] = '<div class="modal-header">';
        $a_modal[] = '<h3>Delete Reading Group</h3>';
        $a_modal[] = '<label class="subheading">Are you sure you wish to delete this reading group?</label>';
        $a_modal[] = '</div>';
        $a_modal[] = '<div class="modal-footer">';
        $a_modal[] = '<button type="button" class="btn btn-default btn-submit">Delete</button>';
        $a_modal[] = '<button type="button" class="btn btn-primary btn-close" data-dismiss="modal">No</button>';
        $a_modal[] = '</div>';
        $a_modal[] = '</div>';
        $a_modal[] = '</div>';
        $a_modal[] = '</div>';

        return $a_modal;
    }


    /* ----- END OF CLASS ----- */
}

/* ----- END MANAGE READING GROUPS CLASS FILE ----- */
