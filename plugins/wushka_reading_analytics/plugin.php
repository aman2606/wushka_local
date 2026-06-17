<?php

/*
  Plugin Name: Wushka - Reading Analytics
  Description: Track user ebook reading activity
  Version: 2.0
  Author: Intygrate Pty Ltd
 */

register_activation_hook(__FILE__, 'wushka_reading_analytics_install');

global $lessonzone_reading_analytics_db_version;
$lessonzone_reading_analytics_db_version = "2.2";

function wushka_reading_analytics_update_db_check() {
    global $lessonzone_reading_analytics_db_version;
    if (get_site_option('lessonzone_reading_analytics_db_version') != $lessonzone_reading_analytics_db_version) {
        wushka_reading_analytics_install();
    }
}

add_action('plugins_loaded', 'wushka_reading_analytics_update_db_check');
/*
  When the plugin is activated, 'install' the table.
 */

function wushka_reading_analytics_install() {

    global $wpdb;
    global $lessonzone_reading_analytics_db_version;
    $installed_ver = get_option("lessonzone_reading_analytics_db_version");

    if ($installed_ver < $lessonzone_reading_analytics_db_version) {
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
          page_start int(3),
          page_end int(3),
          PRIMARY KEY id (read_id)
        );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);

        // if (wushka_reading_analytics_upgrade_data()) {
            update_option("lessonzone_reading_analytics_db_version", $lessonzone_reading_analytics_db_version);
        // };
    }
}

function wushka_reading_analytics_upgrade_data() {
    // NOTE: data is too big for this to run, data should be manually upgraded if reading every record is required.
    global $wpdb;
    $table_name = $wpdb->prefix . "lessonzone_reading_analytics_reading_instance";

    error_log('upgrading wushka analytics');
    $results = $wpdb->get_results('SELECT * FROM ' . $table_name);
    foreach ($results as $result) {
        $id = $result->read_id;
        $resource = $result->essis_resource_id;

        $post = $wpdb->get_row("SELECT * from wp_postmeta where meta_key='esiss_resource_id' and meta_value='$resource'");

        $fiction = get_fiction($post->post_id);
        $level = get_level($post->post_id);
        $read = $result->completed === 1 || $result->duration > 15;

        error_log('upgrading stat: ' . $id);
        $wpdb->update($table_name, array('fiction' => $fiction, 'level' => $level, 'completed' => $read), array('read_id' => $id));
    }

    return true;
}

function get_fiction($id) {
    $terms = wp_get_post_terms($id, 'fiction');
    $term = wp_list_pluck($terms, 'name');
    error_log("fiction:" . print_r($term, true));
    return !empty($term) && $term[0] == 'Fiction';
}

function get_level($id) {
    $terms = wp_get_post_terms($id, 'reading-level');
    $term = wp_list_pluck($terms, 'term_taxonomy_id');
    error_log("level:" . print_r($term, true));
    return !empty($term) ? $term[0] : null;
}

?>