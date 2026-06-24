<?php

/**
 * Created by PhpStorm.
 * User: Jordan
 * Date: 5/05/2016
 * Time: 4:59 PM
 */
class Class_List
{

    private $i_class;
    private $s_class;
    private $a_school;
    private $a_results;
    private $o_user;
    private $b_test;

    public function __construct()
    {
        $this->i_class  = NULL;
        $this->s_class  = NULL;
        $this->a_school = array(
            'i_term' => NULL,
            'i_user' => NULL
        );

        $this->a_results = array(
            'status'  => 0,
            'message' => 'Constructing Class list class',
            'data'    => array()
        );

        if (! isset($current_user)) {
            global $current_user;
        }

        $this->o_user = $current_user;

        #TODO: Set to FALSE after Testing
        $this->b_test = TRUE;

        return TRUE;
    }

    public function set_class($i_class, $s_type)
    {
        if (isset($i_class) && ! empty($i_class)) {
            $this->i_class = (int)$i_class;
        }

        if (isset($s_type) && ! empty($s_type)) {
            $this->s_class = $s_type;
        }

        return TRUE;
    }

    public function get_class_data($b_school = FALSE)
    {
        $this->log('----- LOADING USER CLASS TABLES -----');
        $this->log('Started at: ' . date('H:i:s'));
        //Validate Current User
        if (! is_user_logged_in() || (! current_user_can('teacher') && ! current_user_can('school'))) {
            $this->log('Error: Could not validate current user');

            return FALSE;
        }

        //Get Cookies
        $a_cookies = wushka_get_class_cookies();
        $this->set_class($a_cookies['id'], $a_cookies['type']);

        //Get User's School
        $this->a_school = $this->get_school();

        //Determine if loading Teacher or Program Coordinator Class Data
        if ($b_school && current_user_can('school')) {
            $a_classes = $this->get_school_classes($this->a_school['i_term']);
        } else {
            $a_classes = $this->get_teacher_classes($this->o_user->ID);
        }

        if (! isset($a_classes) || empty($a_classes)) {
            $this->log('Error: Failed to retrieve any class data for user #' . $this->o_user->ID);

            return FALSE;
        }

        //Get an Array of Just Class ids, not objects
        //Get Current School Term ID & user ID
        //Get Student Users linked to classes
        //Get The active, or initial, class to display first
        //Get Class Reading Groups
        $a_ids = $this->get_class_ids($a_classes);

        //$a_class_users  = $this->get_class_students($a_ids);
        $a_groups     = $this->get_reading_groups($a_ids);
        $a_json_users = $this->get_json_students($a_ids, $a_groups);
        $i_active     = $this->get_active($a_ids);
        $a_levels     = $this->get_levels();
        $a_decodables = $this->get_decodable_levels();
        $a_access     = $this->get_access_types();


        $a_compiled = $this->compile_classes($a_classes, $a_json_users, $a_groups, $i_active);
        $this->log('Compiled at: ' . date('H:i:s'));

        $a_menus = $this->build_menu($a_compiled, $b_school);

        $a_active = array(
            'id'   => $i_active,
            'type' => $this->s_class
        );

        $this->a_results['data']['active']  = $a_active;
        $this->a_results['data']['menus']   = $a_menus;
        $this->a_results['data']['classes'] = $a_compiled;
        //$this->a_results['data']['users']   = $a_json_users;
        //$this->a_results['data']['tables']  = $a_tables;
        $this->a_results['data']['levels']   = $a_levels;
        $this->a_results['data']['decodables']   = $a_decodables;
        $this->a_results['data']['access']   = $a_access;
        $this->a_results['data']['settings']      = $this->get_permission_types();
        $this->a_results['data']['sound_clusters'] = $this->get_sound_clusters();
        $this->a_results['data']['phase_access']   = $this->get_phase_access();

        $this->log('Built at: ' . date('H:i:s'));
        $this->log('----- CLASSES BUILT -----');

        return TRUE;
    }

    private function validate_parameters($i_id = NULL, $s_type = NULL)
    {
        //Validate Passed ID
        if (! isset($i_id) || empty($i_id)) {
            $this->log('Error: Cannot Validate NULL ID');

            return FALSE;
        }

        //Validate Passed Class Type
        if (! isset($s_type) || empty($s_type)) {
            $this->log('Error: Cannot Validate NULL type');

            return FALSE;
        }

        //Validate Current User
        if (! is_user_logged_in() || (! current_user_can('teacher') && ! current_user_can('school'))) {
            $this->log('Error: Could not validate current user');

            return FALSE;
        }

        //Check if passed class type matches valid types
        $a_valid = array(
            'class',
            'archive'
        );
        if (! in_array($s_type, $a_valid)) {
            $this->log('Error: Invalid Class Type');

            return FALSE;
        }

        return TRUE;
    }

    private function get_class($i_class)
    {
        $o_class = wushka_get_class($i_class);
        if (! isset($o_class) || empty($o_class)) {
            $this->log('Error: Could Not Find Class of ID: ' . $i_class);

            return NULL;
        }

        return $o_class;
    }

    private function get_school_classes($i_term = NULL)
    {
        if (! isset($i_term) || empty($i_term)) {
            $this->log('Error: Cannot get classes of NULL School');

            return NULL;
        }

        // $a_classes = wushka_get_classes($i_term, NULL, 'both', 'archived, id');
        $a_classes = wushka_get_classes($i_term, NULL, false, 'archived, id');
        if (! isset($a_classes) || empty($a_classes)) {
            $this->log('Error: Could Not Find Classes of School ID: ' . $i_term);

            return NULL;
        }

        $this->log('Found ' . count($a_classes) . ' Classes for School User ');

        return $a_classes;
    }

    private function get_teacher_classes($i_teacher)
    {
        $a_classes = wushka_get_teacher_classes($i_teacher);

        if (! isset($a_classes) || empty($a_classes)) {
            $this->log('Error: Could Not Find Classes of Teacher ID: ' . $i_teacher);

            return NULL;
        }

        $this->log('Found ' . count($a_classes) . ' Classes for Teacher User #' . $i_teacher);

        return $a_classes;
    }

    private function get_class_ids($a_class = array())
    {
        if (! isset($a_class) || empty($a_class)) {
            $this->log('Warning: Cannot Retrieve Class ids of empty classes array');

            return array();
        }

        $a_ids = array();
        foreach ($a_class as $idx => $o_class) {
            $a_ids[] = (int)$o_class->id;
        }

        return $a_ids;
    }

    private function get_school()
    {
        $a_school = array(
            'i_term' => NULL,
            'i_user' => NULL,
        );

        $a_terms = wp_get_object_terms($this->o_user->ID, 'school');
        if (! isset($a_terms) || empty($a_terms)) {
            $this->log('Error: Could not find school terms for user :' . $this->o_user->ID);

            return FALSE;
        }

        $a_school['i_term'] = $a_terms[0]->term_taxonomy_id;

        //Get School Term User
        $o_school = wushka_get_school_term_user($a_school['i_term']);
        if (! isset($o_school) && ! empty($o_school)) {
            $this->log('Warning: Could Not Retrieve School Type User of term id: ' . $a_school['i_term']);
        } else {
            $a_school['i_user'] = $o_school->ID;
        }

        return $a_school;
    }

    private function get_class_students($a_ids = array())
    {
        if (! isset($a_ids) || empty($a_ids)) {
            $this->log('Error: Cannot retrieve class users without class ids');

            return array();
        }

        $a_class_users = array();
        foreach ($a_ids as $idx => $i_class) {
            $a_class_users[$i_class] = array(
                'active'   => array(),
                'inactive' => array()
            );
        }

        // updated Feb 2019 to prevent count_total performing slow query
        $args = array(
            'role'       => 'student',
            'count_total'   => false,
            'meta_query' => array(
                'relation' => 'AND',
                0          => array(
                    'key'     => 'class',
                    'value'   => $a_ids,
                    'compare' => 'IN'
                )
            )
        );

        $o_query   = new WP_User_Query($args);  // args updated for slow query
        $a_results = $o_query->get_results();

        $this->log('Found ' . count($a_results) . ' Students in ' . count($a_ids) . ' classes');

        if (! empty($a_results)) {
            foreach ($a_results as $idx => $o_user) {
                $s_active = 'inactive';
                if (isset($o_user->active) && ! empty($o_user->active)) {
                    $s_active = 'active';
                }

                $a_class_users[(int)$o_user->class][$s_active][] = $o_user;
            }
        }

        return $a_class_users;
    }

    private function get_json_students($a_ids = array(), $a_groups = array())
    {
        if (! isset($a_ids) || empty($a_ids)) {
            $this->log('Error: Cannot retrieve class users without class ids');

            return array();
        }

        $a_class_users = array();

        $this->log('getting students for classes');
        // updated Feb 2019 to prevent count_total performing slow query
        $args = array(
            'role'       => 'student',
            'count_total'   => false,
            'meta_query' => array(
                'relation' => 'AND',
                0          => array(
                    'key'     => 'class',
                    'value'   => $a_ids,
                    'compare' => 'IN'
                )
            )
        );
        // memory error due to retrieving archived students, this resolves...
        // $args = array(
        //     'role'       => 'student',
        //     'count_total'   => false,
        //     'meta_query' => array(
        //         'relation' => 'AND',
        //         0          => array(
        //             'key'     => 'class',
        //             'value'   => $a_ids,
        //             'compare' => 'IN'
        //         ),
        //         1 => array(
        // 			'key' => 'active',
        // 			'value' => 1
        //         )
        //     )
        // );

        $o_query   = new WP_User_Query($args);  // args updated for slow query
        $a_results = $o_query->get_results();

        $this->log('Found ' . count($a_results) . ' Students in ' . count($a_ids) . ' classes');

        if (! empty($a_results)) {
            foreach ($a_results as $idx => $o_user) {
                $s_active = 'archive';
                if (isset($o_user->active) && ! empty($o_user->active)) {
                    $s_active = 'class';
                }

                $a_group = array(
                    'ID'    => '000',
                    'value' => 'No Group'
                );

                if (isset($o_user->my_reading_group)) {
                    if (! empty($a_groups)) {
                        foreach ($a_groups[(int)$o_user->class] as $id => $s_group) {
                            if ((int)$o_user->my_reading_group == (int)$id) {
                                $a_group['ID']    = $id;
                                $a_group['value'] = $s_group;
                            }
                        }
                    }
                }

                $a_level = array(
                    'name' => '',
                    'slug' => ''
                );
                $a_level['name'] = isset($o_user->reading_level) && isset($o_user->reading_level['name']) ? $o_user->reading_level['name'] : '';
                $a_level['slug'] = isset($o_user->reading_level) && isset($o_user->reading_level['slug']) ? $o_user->reading_level['slug'] : '';

                $a_setting['id']   = 'on';
                $a_setting['name'] = 'On';
                if (isset($o_user->rg_setting)) {
                    $a_setting['id']   = trim(strtolower($o_user->rg_setting));
                    $a_setting['name'] = trim(ucwords($o_user->rg_setting));
                }

                $allow_book_view = ($o_user->allow_book_view) ? $o_user->allow_book_view : 'No';
                $quiz_narration = ($o_user->quiz_narration) ? $o_user->quiz_narration : 'Yes';
                $quiz_detail_results = ($o_user->quiz_detail_results) ? $o_user->quiz_detail_results : 'Yes';

                $a_user = array(
                    'id_hash'          => $o_user->id_hash,
                    'first_name'       => trim(ucwords($o_user->first_name)),
                    'last_name'        => trim(ucwords($o_user->last_name)),
                    'username'         => trim($o_user->user_login),
                    'email'            => $o_user->user_email,
                    'user_pass'        => $o_user->show_user_pwd,
                    'reading_level'    => $a_level,
                    'allowed_shelves'  => trim(ucwords($o_user->allowed_shelves['name'])),
                    'my_reading_group' => $a_group,
                    'rg_setting'       => $a_setting,
                    'narration'        => trim(ucwords($o_user->narration)),
                    'allow_book_view'        => trim(ucwords($allow_book_view)),
                    'quizzes'          => trim(ucwords($o_user->quizzes)),
                    'quiz_narration'          => trim(ucwords($quiz_narration)),
                    'quiz_detail_results'          => trim(ucwords($quiz_detail_results)),
                    'sound_cluster'    => isset($o_user->sound_cluster) ? trim($o_user->sound_cluster) : '',
                    'phase_access'     => isset($o_user->phase_access)  ? trim($o_user->phase_access)  : ''
                );

                $a_class_users[(int)$o_user->class][$s_active][] = $a_user;
                unset($a_user, $a_group, $a_level, $s_active);
            }
        }


        return $a_class_users;
    }

    private function get_active($a_classes = array())
    {
        if (empty($a_classes)) {
            return NULL;
        }

        $i_active = $a_classes[0];

        if (isset($this->i_class) && ! empty($this->i_class)) {
            foreach ($a_classes as $idx => $i_class) {
                if ((int)$i_class == (int)$this->i_class) {
                    $i_active = $i_class;
                }
            }
        }

        return $i_active;
    }

    private function get_reading_groups($a_ids = array())
    {
        if (! isset($a_ids) || empty($a_ids)) {
            $this->log('Warning: Need at least 1 class to retrieve reading groups');

            return array();
        }
        global $wpdb;

        $a_params = array();
        $a_groups = array();

        foreach ($a_ids as $idx => $i_class) {
            $a_params[]              = '%d';
            $a_groups[$i_class]    = array();
            $a_groups[$i_class][0] = 'No Group';
        }

        $s_query   = 'SELECT * FROM ' . $wpdb->prefix . 'wushka_reading_groups WHERE class_id IN (' . implode(',', $a_params) . ');';
        $a_results = $wpdb->get_results(
            $wpdb->prepare($s_query, $a_ids)
        );

        if (isset($a_results) && ! empty($a_results)) {
            foreach ($a_results as $idx => $o_group) {

                $a_groups[(int)$o_group->class_id][(int)$o_group->ID] = trim(ucwords($o_group->group_name));
            }
        }

        $this->log('Found ' . count($a_results) . ' groups for ' . count($a_groups) . ' classes');

        return $a_groups;
    }

    private function get_levels()
    {
        $a_args = array(
            'orderby' => 'slug',
            'order'   => 'ASC'
        );

        $a_terms      = get_terms('reading-level', $a_args);
        $a_levels     = array();
        $a_levels[''] = '';
        $count = 0;
        foreach ($a_terms as $idx => $o_term) {
            if (ucwords($o_term->name) != 'Reading Level') {
                $a_levels[$o_term->slug] = $o_term->name;
            }
            $count++;
        }

        return $a_levels;
    }


    private function get_decodable_levels()
    {
        $a_args = array(
            'orderby' => 'slug',
            'order'   => 'ASC'
        );
        $a_terms      = get_terms('reading-level', $a_args);
        $a_levels     = array();
        $a_levels[''] = '';
        $count = 0;
        foreach ($a_terms as $idx => $o_term) {
            if (ucwords($o_term->name) != 'Reading Level') {
                if ($count >= 8) {
                    break;
                }
                $a_levels[$o_term->slug] = $o_term->name;
            }
            $count++;
        }

        return $a_levels;
    }

    private function get_access_types()
    {
        $a_types                                    = array();
        $a_types['']                                = "";
        $a_types['Reading Group Only']              = "Reading Group Only";
        $a_types['Reading Level Only']              = "Reading Level Only";
        $a_types['Reading Level + One Level Above'] = "Reading Level + One Level Above";
        $a_types['Reading Level + One Level Below'] = "Reading Level + One Level Below";
        $a_types['Reading Level + Levels Below']    = "Reading Level + Levels Below";
        $a_types['Levels Below Reading Level Only'] = "Levels Below Reading Level Only";
        $a_types['All Levels']                      = "All Levels";

        return $a_types;
    }

    private function get_permission_types()
    {
        $a_types           = array();
        $a_types['on']     = 'On';
        $a_types['school'] = 'School';
        $a_types['home']   = 'Home';
        $a_types['off']    = 'Off';

        return $a_types;
    }

    private function get_sound_clusters()
    {

        $soundsArray              = array();
        $soundsArray[0]          = 'Not Set';

        global $wpdb;

        $sql = "
    SELECT
        t.term_id AS phase_id,
        t.name    AS phase,
        t.slug    AS phase_slug,
        GROUP_CONCAT(pm.meta_value SEPARATOR ' | ') AS esiss_sounds
    FROM {$wpdb->terms} t
    INNER JOIN {$wpdb->term_taxonomy} tt
        ON t.term_id = tt.term_id
       AND tt.taxonomy = 'phonics-phase'
    INNER JOIN {$wpdb->term_relationships} tr
        ON tr.term_taxonomy_id = tt.term_taxonomy_id
    INNER JOIN {$wpdb->posts} p
        ON p.ID = tr.object_id
       AND p.post_type = 'ebook'
       AND p.post_status = 'publish'
    INNER JOIN {$wpdb->postmeta} pm
        ON pm.post_id = p.ID
       AND pm.meta_key = 'esiss_sounds'
    GROUP BY t.term_id, t.name, t.slug
    ORDER BY t.slug
";

        $results = $wpdb->get_results($sql);

        // echo "<pre>";
        // print_r($results);
        // exit;

        foreach ($results as $key => $row) {


            // Split the combined string into individual sounds
            $sounds = array_map('trim', explode(',', $row->esiss_sounds));

            // Remove blanks and duplicates while preserving first-seen order
            $sounds = array_filter($sounds, 'strlen');
            $sounds = array_values(array_unique($sounds));

            $row->esiss_sounds = implode(', ', $sounds);

            if (preg_match('/Phase\s+\d+/i', $row->phase, $matches)) {
                $phase = $matches[0];   // "Phase 6"
            } else {
                $phase = null;
            }

            if ($key > 1) {

                $tempArray  = explode("|", $row->esiss_sounds);

                if (!empty($tempArray)) {
                    foreach ($tempArray as $sound) {

                        if (!empty($sound)) {
                            $soundsArray[] = trim($phase . ' - ' . $sound);
                        }
                    }
                }
            }
        }

        // echo "<pre>";
        // print_r($soundsArray);
        // exit;
        $soundsArray = array_unique($soundsArray);

        $seen = [];
        $unique = [];

        foreach ($soundsArray as $value) {
            // split on commas OR spaces, trim, drop empties
            $parts = preg_split('/[\s,]+/', trim($value), -1, PREG_SPLIT_NO_EMPTY);

            $key = implode(',', $parts);   // canonical: "s,a,p,t" (no sorting)

            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $unique[] = $value;        // keep original formatting
            }
        }

        return $unique;
    }

    private function get_phase_access()
    {
        $a_phases            = array();
        $a_phases['']        = 'Not Set';
        $a_phases['sound-cluster-only'] = 'Sound cluster only';
        $a_phases['sound-cluster-one-below'] = 'Sound cluster + one below';
        $a_phases['sound-cluster-all-below-in-phase'] = 'Sound cluster + all below in phase';
        $a_phases['sound-cluster-all-below'] = 'Sound cluster + all below';
        $a_phases['whole-phase'] = 'Whole phase';
        $a_phases['all-phases'] = 'All phases';


        return $a_phases;
    }

    private function compile_classes($a_classes, $a_class_users, $a_class_groups, $i_active)
    {

        $a_compiled = array();

        foreach ($a_classes as $idx => $o_class) {
            $i_class = (int)$o_class->id;

            //Store Class Object
            $a_compiled[$i_class]['class'] = $o_class;

            //Set Active Flag
            $a_compiled[$i_class]['active'] = $i_active == $i_class ? TRUE : FALSE;
            $a_compiled[$i_class]['deleted'] = $o_class->archived == '1' ? TRUE : FALSE;

            //Get Class Users
            $a_users = array();
            if (array_key_exists($i_class, $a_class_users) && ! empty($a_class_users[$i_class])) {
                $a_users = $a_class_users[$i_class];
            }
            $a_compiled[$i_class]['users'] = $a_users;

            //Get Class Groups
            $a_groups = array();
            if (array_key_exists($i_class, $a_class_groups) && ! empty($a_class_groups[$i_class])) {
                $a_groups = $a_class_groups[$i_class];
            }
            $a_compiled[$i_class]['groups'] = $a_groups;

            unset($i_class, $a_users, $a_groups);
        }

        $this->log('Data Compile Complete: ' . count($a_compiled) . ' classes loaded');

        return $a_compiled;
    }

    private function build_menu($a_classes, $b_school = FALSE)
    {
        if (! isset($a_classes) || empty($a_classes)) {
            $this->log('Error: Cannot Build Class Menu for null classes');

            return FALSE;
        }

        $a_menus = array();
        foreach ($a_classes as $i_id => $a_class) {
            $a_menu   = array();
            $s_active = $a_class['active'] ? 'active' : NULL;
            $s_deleted = $a_class['deleted'] ? 'deleted' : 'active';
            $s_attr   = 'aria-label="Classes: ' . trim($a_class['class']->name) . '" role="tab" data-toggle="tab"';

            if (!empty($a_class['class']->name)) {
                if ($b_school) {
                    $s_classes = 'list-group-item class-list class-switch ' . $s_active;
                    $a_menu[] = '<a href="#' . $i_id . '-class" data-active="' . $s_deleted . '" data-class="' . $i_id . '" class="' . $s_classes . '" ' . $s_attr . ' >';
                    $a_menu[] = trim($a_class['class']->name);
                    $a_menu[] = '</a>';
                } else {
                    $a_menu[] = '<li role="presentation" class="class-list class-switch ' . $s_active . '">';
                    $a_menu[] = '<a href="#' . $i_id . '-class" data-class="' . $i_id . '" ' . $s_attr . ' >';
                    $a_menu[] = trim($a_class['class']->name);
                    $a_menu[] = '</a>';
                    $a_menu[] = '</li>';
                }
                $a_menus[] = implode('', $a_menu);
                unset($a_menu, $s_active);
            }
        }

        return $a_menus;
    }

    private function build_html($a_classes)
    {
        if (! isset($a_classes) || empty($a_classes)) {
            $this->log('Error: Cannot Build HTML for null classes');

            return FALSE;
        }

        $this->log('Building Tables');

        $a_tables = array();

        #TODO: Archive tables have archive-table tag, not class-table
        $s_table_tags = 'display table table-bordered table-condensed table-hover class-table';

        //Active Classes
        $this->log('Build Active Tables');
        foreach ($a_classes as $i_id => $a_class) {
            $a_table  = array();
            $s_active = $a_class['active'] && $this->s_class == 'class' ? 'in active' : NULL;

            $a_table[] = '<div role="tabpanel" id="' . $i_id . '-class" data-class="' . $i_id . '" class="tab-pane fade ' . $s_active . '">';
            $a_table[] = '<div class="table-responsive">';
            $a_table[] = '<table data-class="' . $i_id . '" data-type="class" class="' . $s_table_tags . '">';
            $a_table[] = implode('', $this->get_table_header());
            $a_table[] = implode('', $this->get_class_rows($a_class));
            $a_table[] = implode('', $this->get_table_footer());
            $a_table[] = '</table>';
            $a_table[] = '</div>';
            $a_table[] = '</div><!-- END tabpanel -->';

            $a_tables[] = implode('', $a_table);

            unset($a_table, $s_active);
        }
        $this->log('Build Archived Tables');
        //Archived Classes
        foreach ($a_classes as $i_id => $a_class) {
            $a_table = array();
            $s_active = $a_class['active'] && $this->s_class == 'archive' ? 'in active' : NULL;

            $a_table[] = '<div role="tabpanel" id="' . $i_id . '-archive" data-class="' . $i_id . '" class="tab-pane fade ' . $s_active . '">';
            $a_table[] = '<div class="table-responsive">';
            $a_table[] = '<table data-class="' . $i_id . '" data-type="archive" class="' . $s_table_tags . '">';
            $a_table[] = implode('', $this->get_table_header(TRUE));
            $a_table[] = implode('', $this->get_class_rows($a_class, TRUE));
            $a_table[] = implode('', $this->get_table_footer(TRUE));
            $a_table[] = '</table>';
            $a_table[] = '</div>';
            $a_table[] = '</div><!-- END tabpanel -->';

            $a_tables[] = implode('', $a_table);

            unset($a_table, $s_active);
        }

        #TODO: Build MCL Wrapper HTML
        #TODO: Add Class Tables HTML
        #TODO: Add Class User Rows

        $this->log('Tables Built');

        return $a_tables;
    }

    private function get_table_header($b_archive = FALSE)
    {
        $a_head   = array();
        $a_head[] = '<thead>';
        $a_head[] = '<tr class="class-view-table-heading">';
        $a_head[] = implode('', $this->get_header_rows($b_archive));
        $a_head[] = '</tr>';
        $a_head[] = '</thead>';

        return $a_head;
    }

    private function get_table_footer($b_archive = FALSE)
    {
        $a_foot   = array();
        $a_foot[] = '<tfoot>';
        $a_foot[] = '<tr class="class-view-table-heading">';
        $a_foot[] = implode('', $this->get_header_rows($b_archive));
        $a_foot[] = '</tr>';
        $a_foot[] = '</tfoot>';

        return $a_foot;
    }

    private function get_header_rows($b_archive = FALSE)
    {
        $a_rows   = array();
        $a_rows[] = '<th class="class-view-col-0">First Name</th>';
        $a_rows[] = '<th class="class-view-col-1">Surname</th>';
        $a_rows[] = '<th class="class-view-col-2">Username</th>';
        $a_rows[] = '<th class="class-view-col-3">Password</th>';
        $a_rows[] = '<th class="class-view-col-4">Reading Level</th>';
        $a_rows[] = '<th class="class-view-col-5">Levels Access</th>';
        $a_rows[] = '<th class="class-view-col-6">Reading Group</th>';
        $a_rows[] = '<th class="class-view-col-7">Allow Narration</th>';
        $a_rows[] = '<th class="class-view-col-8">Quizzes</th>';
        $a_rows[] = $b_archive ? '<th class="class-view-col-9">Archive</th>' : NULL;

        return $a_rows;
    }

    private function get_class_rows($a_class = array(), $b_archived = FALSE)
    {
        if (! isset($a_class) || empty($a_class)) {
            $this->log('Error: Cannot create class rows of null class');

            return NULL;
        }

        $a_users = ! $b_archived ? $a_class['users']['active'] : $a_class['users']['inactive'];
        $a_rows  = array();
        if (! empty($a_users)) {
            foreach ($a_users as $idx => $o_user) {
                $a_row   = array();
                $a_row[] = '<tr class="class-view-table-data row-odd" id="user-' . $o_user->id_hash . '">';
                $a_row[] = implode('', $this->build_user_row($o_user, $a_class['groups'], $b_archived));
                $a_row[] = '</tr>';

                $a_rows[] = implode('', $a_row);
                unset($a_row);
            }
        }

        return $a_rows;
    }

    private function build_user_row($o_user = NULL, $a_groups = array(), $b_archived = FALSE)
    {
        if (! isset($o_user) || empty($o_user)) {
            return array();
        }

        $a_group = array(
            'ID'    => '000',
            'value' => 'No Group'
        );

        if (isset($o_user->my_reading_group)) {
            foreach ($a_groups as $id => $s_group) {
                if ((int)$o_user->my_reading_group == (int)$id) {
                    $a_group['ID']    = $id;
                    $a_group['value'] = $s_group;
                }
            }
        }

        $a_level['name'] = isset($o_user->reading_level) ? $o_user->reading_level['name'] : '';
        $a_level['slug'] = isset($o_user->reading_level) ? $o_user->reading_level['slug'] : '';

        $a_row   = array();
        $a_row[] = '<td><span class="first_name">' . $o_user->first_name . '</span></td>';
        $a_row[] = '<td><span class="last_name">' . $o_user->last_name . '</span></td>';
        $a_row[] = '<td><span class="username">' . $o_user->user_login . '</span></td>';
        $a_row[] = '<td><span class="user_pass">' . $o_user->show_user_pwd . '</span></td>';
        $a_row[] = '<td data-order="' . $a_level['slug'] . '">';
        $a_row[] = '<span class="reading_level" data-value="' . $a_level['slug'] . '">';
        $a_row[] = $a_level['name'];
        $a_row[] = '</span>';
        $a_row[] = '</td>';
        $a_row[] = '<td><span class="allowed_shelves" data-value="' . $o_user->allowed_shelves['name'] . '">' . $o_user->allowed_shelves['name'] . '</span></td>';
        $a_row[] = '<td>';
        $a_row[] = '<span class="my_reading_group" data-value="' . $a_group['ID'] . '">';
        $a_row[] = $a_group['value'];
        $a_row[] = '</span>';
        $a_row[] = '</td>';
        $a_row[] = '<td><span class="narration yesorno" data-value="' . $o_user->narration . '">' . $o_user->narration . '</span></td>';
        $a_row[] = '<td><span class="quizzes" data-value="' . $o_user->quizzes . '">' . ucwords($o_user->quizzes) . '</span></td>';
        if ($b_archived) {
            $a_row[] = '<td><button class="btn btn-default btn-student-unarchive" type="button">Unarchive</button></td>';
        }


        return $a_row;
    }

    /** Log
     * --------------------------------------------------
     * Prints a string message to debug.log
     * adds same string to results array message index
     * ---------------------------------------------------
     *
     * @param string $s_text - String Containing a code status message
     *
     * @return true          - Return true on function completion
     **/
    private function log($s_text = NULL)
    {
        if ($this->b_test) {
            if (isset($s_text) && ! empty($s_text)) {
                error_log($s_text);
                $this->a_results['message'] .= '<br/>-' . $s_text;
            }
        }

        return TRUE;
    }

    /** Get Results
     * --------------------------------------
     * Returns the storage array of the class
     * --------------------------------------
     * @return array - Contains Three index:
     *               - int      'status': 1 on success, 0 on error
     *               - string   'message': Text Response
     *               - array    'data' : Any data to be returned on function completion (HTML, JSON etc)
     **/
    public function get_results()
    {
        return $this->a_results;
    }
}
/* ----- END OF FILE ----- */