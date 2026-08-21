<?php
/*
 * Retrieve assign group data for students
 */
include $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] != 'undefined' && isset($_POST['value']) && $_POST['value'] != 'undefined') {
    $result = array();

    if (isset($_POST['group']) && $_POST['group'] != 'undefined' && !empty($_POST['group'])) {
        $groupId = $_POST['group'];
        $meta_key = $groupId . '_books';
        $teacherId = $_POST['id'];
        $meta_value = $_POST['value'];

        /* Add books into particular group */
        if ($_POST['action'] == 'submit') {

            $curret_work = get_user_meta($teacherId, $meta_key, true);
            delete_user_meta($teacherId, $meta_key);

            $toAppend = array();
            if ($curret_work) {
                $new_work = $curret_work;
                $existing_work = in_array($meta_value, $curret_work);
                if (!$existing_work) {
                    array_push($toAppend, $meta_value);
                    array_push($new_work, $meta_value);
                }
            } else {
                $new_work = array($meta_value);
                array_push($toAppend, $meta_value);
            }

            //To show new books dynamically on a page   
            if (!empty($toAppend)) {

                $args = array(
                    'post_type' => 'ebook',
                    'post_status' => 'publish',
                    'post__in' => $toAppend,
                    'posts_per_page' => -1,
                );
                $posts = get_posts($args);
                foreach ($posts as $idx => $post) {
                    $imgsrc = get_post_meta($post->ID, 'post_image', true);
                    $result[$idx]['id'] = $post->ID;
                    $result[$idx]['title'] = $post->post_title;
                    $result[$idx]['imgsrc'] = $imgsrc;
                }
            }
            error_log('** Add ** ' . print_r($new_work, true));

            update_user_meta($teacherId, $meta_key, $new_work);
            echo json_encode($result);
        } 

        /* Show all books assigned to particular group */
        if ($_POST['action'] == 'show') {
            $books = get_user_meta($teacherId, $meta_key, true);
            // error_log('** $meta_key ** ' . print_r($meta_key,true));
            // delete_user_meta( $teacherId,  $meta_key);

            $result = array();
            if ($books) {
                $args = array(
                    'post_type' => 'ebook',
                    'post_status' => 'publish',
                    'post__in' => $books,
                    'posts_per_page' => -1,
                );

                $posts = get_posts($args);

                foreach ($posts as $idx => $post) {
                    $term = wp_get_post_terms($post->ID, 'reading-level');
                    $taxonomyId = wp_list_pluck($term, 'term_taxonomy_id');

                    $imgsrc = get_post_meta($post->ID, 'post_image', true);
                    $result[$idx]['id'] = $post->ID;
                    $result[$idx]['title'] = $post->post_title;
                    $result[$idx]['imgsrc'] = $imgsrc;
                    $result[$idx]['level'] = $taxonomyId;
                }
            }
            echo json_encode($result);
        }

        /* Delete books from a group */
        if (($_POST['action'] == 'delete') && !empty($_POST['value'])) {

            $new_work = array();
            $foundToDelete = array();
            $toDelete['deleted'] = $_POST['value'];

            $current_work = get_user_meta($teacherId, $meta_key, true);
            delete_user_meta($teacherId, $meta_key);

            foreach ($current_work as $bookId) {
                if ($toDelete['deleted'] != $bookId) {
                    array_push($new_work, $bookId);
                }
            }

            update_user_meta($teacherId, $groupId . '_books', $new_work);
            //   error_log('** Current ** ' . print_r($current_work,true));
            if (empty($new_work))
                $toDelete['empty'] = true;
            echo json_encode($toDelete);
        }
        /* Delete All books from selected shelf */
        if (($_POST['action'] == 'deleteAll') && !empty($_POST['group'])) {

            $groupId = $_POST['group'];
            $meta_key = $groupId . '_books';

            $current_work = get_user_meta($current_user->ID, $meta_key, true);
            error_log('** Delete  All ** ' . print_r($current_work, true));
            if ($current_work) {
                if (delete_user_meta($current_user->ID, $meta_key)) {
                    $current_work = '';
                    $current_work['value'] = 'deleted';
                }
            }

            echo json_encode($current_work);
        }
    }/* else {
      echo json_encode($result);
      } */

    /* Add reading groups */
    if (($_POST['action'] == 'add_reading_group') && !empty($_POST['id']) && !empty($_POST['value'])) {
        $check = false;
        $current_work = array();
        $toAdd = array();

        $current_work = get_user_meta($_POST['id'], 'reading_group', true);

        $value = clean($_POST['value']);

        $toAdd[0]['id'] = 'group-' . $value;
        $toAdd[0]['value'] = cleanOnlySpecialChar($_POST['value']);

        if ($toAdd[0]['value']) {
            delete_user_meta($_POST['id'], 'reading_group');
            if ($current_work) {
                foreach ($current_work as $idx => $group) {
                    if (ucwords($group['value']) == ucwords($toAdd[0]['value']) ||
                            ($group['id'] == $toAdd[0]['id'])
                    ) {
                        $toAdd[0]['value'] = 'Duplicate';
                        $check = true;
                        break;
                    }
                }
                if ($check != true) {
                    array_push($current_work, $toAdd[0]);
                }
            } else {
                $current_work[0]['id'] = 'group-' . $value;
                $current_work[0]['value'] = cleanOnlySpecialChar($_POST['value']);
            }
            update_user_meta($_POST['id'], 'reading_group', $current_work);
        }
        error_log('** $current_work** ' . print_r($current_work, true));
        echo json_encode($toAdd[0]);
    }
    //Edit Group name       
    if (($_POST['action'] == 'edit_group_name') && !empty($_POST['value']) && !empty($_POST['id'])) {

        $oldGroup = array();
        $current_groups = get_user_meta($current_user->ID, 'reading_group', true);
        delete_user_meta($current_user->ID, 'reading_group');


        foreach ($current_groups as $idx => $group) {

            if ($group['id'] == $_POST['id']) {
                $oldGroup[0]['id'] = $group['id'];
                $oldGroup[0]['name'] = $group['value'];
                $current_groups[$idx] = "";
                $current_groups[$idx]['id'] = $_POST['id'];
                $current_groups[$idx]['value'] = ucwords(cleanOnlySpecialChar($_POST['value']));
            }
        }

        update_user_meta($current_user->ID, 'reading_group', $current_groups);
        echo json_encode($oldGroup[0]);
    }

    if (($_POST['action'] == 'delete_reading_group') && !empty($_POST['value']) && !empty($_POST['id'])) {

        $new_work = array();
        $foundToDelete = array();
        $toDelete = $_POST['id'];

        $current_work = get_user_meta($current_user->ID, 'reading_group', true);
        delete_user_meta($current_user->ID, 'reading_group');
        delete_user_meta($current_user->ID, $toDelete . '_books');

        foreach ($current_work as $groupId) {
            if ($groupId['id'] != $toDelete) {
                array_push($new_work, $groupId);
            } else {
                //Delete related reading books
                $current_books = delete_user_meta($current_user->ID, $toDelete . '_books');
            }
        }
        update_user_meta($current_user->ID, 'reading_group', $new_work);

        error_log('** After delete ** ' . print_r($new_work, true));
        echo json_encode($toDelete);
    }
    if (($_POST['action'] == 'get_reading_groups_list')) {
        if (user_can($current_user, "teacher")) {
            $get_reading_groups = get_user_meta($current_user->ID, 'reading_group', true);
            $reading_group_name[''] = "";
            if ($get_reading_groups) {
                foreach ($get_reading_groups as $idx => $group) {
                    $group['value'] = ucwords($group['value']);
                    $reading_group_name[$group['value']][0] = $group['value'];
                }
            }
            error_log('** get_reading_groups_list ** ' . print_r($reading_group_name, true));
        }
        echo json_encode($reading_group_name);
    }
}