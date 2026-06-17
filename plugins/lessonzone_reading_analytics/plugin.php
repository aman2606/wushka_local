<?php

/*
  Plugin Name: LessonZone - Reading Analytics
  Description: Track user ebook reading activity
  Version: 1.1
  Author: ESISS Pty Ltd
 */

register_activation_hook(__FILE__, 'lessonzone_reading_analytics_install');

add_action('wp_ajax_reading_analytics_register', 'lessonzone_reading_analytics_ajax_register');
add_action('wp_ajax_reading_analytics_update_duration', 'lessonzone_reading_analytics_ajax_update_duration');
add_action('wp_ajax_reading_analytics_mark_complete', 'lessonzone_reading_analytics_mark_complete');
add_action('wp_ajax_reading_analytics_mark_narrated', 'lessonzone_reading_analytics_mark_narrated');

global $lessonzone_reading_analytics_db_version;
$lessonzone_reading_analytics_db_version = "2.1";

function lessonzone_reading_analytics_update_db_check() {
    global $lessonzone_reading_analytics_db_version;
    if (get_site_option('lessonzone_reading_analytics_db_version') != $lessonzone_reading_analytics_db_version) {
        lessonzone_reading_analytics_install();
    }
}

add_action('plugins_loaded', 'lessonzone_reading_analytics_update_db_check');
/*
  When the plugin is activated, 'install' the table.
 */

function lessonzone_reading_analytics_install() {

    global $wpdb;
    global $lessonzone_reading_analytics_db_version;
    $installed_ver = get_option("lessonzone_reading_analytics_db_version");

    if ($installed_ver != $lessonzone_reading_analytics_db_version) {
        $table_name = $wpdb->prefix . "lessonzone_reading_analytics_reading_instance";

        $sql = "CREATE TABLE $table_name (
          read_id int(11) NOT NULL AUTO_INCREMENT,
          essis_resource_id VARCHAR(255),
          user_id int(11),
          created datetime,
          duration int(11),
          completed boolean NOT NULL DEFAULT '0',
          narrated boolean NOT NULL DEFAULT '0',
          form_factor VARCHAR(255),
          fiction boolean NOT NULL DEFAULT '0',
          level int(11),
          PRIMARY KEY id (read_id)
        );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);

        update_option("lessonzone_reading_analytics_db_version", $lessonzone_reading_analytics_db_version);
        lessonzone_reading_analytics_upgrade_data();
    }
}

function lessonzone_reading_analytics_upgrade_data() {
    global $wpdb;
    $table_name = $wpdb->prefix . "lessonzone_reading_analytics_reading_instance";

    $results = $wpdb->get_results('SELECT * FROM ' . $table_name);
    foreach ($results as $result) {
        $id = $result->read_id;
        $resource = $result->essis_resource_id;

        $post = $wpdb->get_row("SELECT * from wp_postmeta where meta_key='esiss_resource_id' and meta_value='$resource'");

        $fiction = get_fiction($post->post_id);
        $level = get_level($post->post_id);

        $wpdb->update($table_name, array('fiction' => $fiction, 'level' => $level), array('read_id' => $id));
    }
}

function get_fiction($id) {
    $terms = wp_get_post_terms($id, 'fiction');
    $term = wp_list_pluck($terms, 'name');
    error_log("fiction:" . print_r($term, true));
    return $term[0] == 'Fiction';
}

function get_level($id) {
    $terms = wp_get_post_terms($id, 'reading-level');
    $term = wp_list_pluck($terms, 'term_taxonomy_id');
    error_log("level:" . print_r($term, true));
    return $term[0];
}

/*
  Initial function called to track a student reading a book.
 */

function lessonzone_reading_analytics_ajax_register() {
    global $wpdb;

    global $current_user;
    get_currentuserinfo();

    $ess = sanitize_text_field($_POST['essis_resource_id']);
    $form = sanitize_text_field($_POST['form_factor']);
    $user = $current_user->ID;

    if ($user) {
        $post = $wpdb->get_row("SELECT * from wp_postmeta where meta_key='esiss_resource_id' and meta_value='$ess'");

        $fiction = get_fiction($post->post_id);
        $level = get_level($post->post_id);

        $table_name = $wpdb->prefix . "lessonzone_reading_analytics_reading_instance";

        $rows_affected = $wpdb->insert(
            $table_name, array(
                'essis_resource_id' => $ess,
                'duration' => 0,
                'user_id' => $user,
                'created' => current_time('mysql'),
                'form_factor' => $form,
                'fiction' => $fiction,
                'level' => $level
            )
        );

        echo $wpdb->insert_id;
        die();
    } else {

        echo 'no user';
        die();
    }
}

/*
  Call to update the duration so we can work out how long it takes for a student to read the book.
 */

function lessonzone_reading_analytics_ajax_update_duration() {

    global $wpdb;

    $table_name = $wpdb->prefix . "lessonzone_reading_analytics_reading_instance";

    $lessonzone_read_instance_id = intval($_POST['lessonzone_read_instance_id']);
    $duration = intval($_POST['duration']);

    $rows_affected = $wpdb->update(
        $table_name, array(
            'duration' => $duration
        ), array(
            'read_id' => $lessonzone_read_instance_id
        ), $format = null, $where_format = null
    );
}

/*
  Call to mark the read instance as 'complete' (all of the book read).
 */

function lessonzone_reading_analytics_mark_complete() {

    global $wpdb;

    $table_name = $wpdb->prefix . "lessonzone_reading_analytics_reading_instance";

    $lessonzone_read_instance_id = intval($_POST['lessonzone_read_instance_id']);

    $rows_affected = $wpdb->update(
        $table_name, array(
            'completed' => 1
        ), array(
            'read_id' => $lessonzone_read_instance_id
        ), $format = null, $where_format = null
    );
}

/*
  Call to mark the read instance as using narration.
 */

function lessonzone_reading_analytics_mark_narrated() {

    global $wpdb;

    $table_name = $wpdb->prefix . "lessonzone_reading_analytics_reading_instance";

    $lessonzone_read_instance_id = intval($_POST['lessonzone_read_instance_id']);

    $rows_affected = $wpdb->update(
        $table_name, array(
            'narrated' => 1
        ), array(
            'read_id' => $lessonzone_read_instance_id
        ), $format = null, $where_format = null
    );
}

?>