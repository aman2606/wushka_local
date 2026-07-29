<?php
/**
 * Plugin Name: Wushka Sound Cluster Sync
 * Description: WP-CLI command to sync the sound_cluster post meta on ebook posts from a CSV file.
 * Version: 1.0
 * Author: Grazitti
 */

if (!defined('WP_CLI') || !WP_CLI) {
    return;
}

class Sound_Cluster_Sync_Command
{
    /**
     * Sync the sound_cluster post meta on ebook posts from a CSV file.
     *
     * ## OPTIONS
     *
     * [--csv=<path>]
     * : Absolute path to the CSV file. Defaults to the CSV bundled with this plugin.
     *
     * [--dry-run]
     * : Print what would be updated without writing anything to the database.
     *
     * ## EXAMPLES
     *
     *     wp sync-sound-cluster --csv="/home/ubuntu/Phonic-Phase-Books.csv"
     *     wp sync-sound-cluster --csv="/home/ubuntu/Phonic-Phase-Books.csv" --dry-run
     *
     * @when after_wp_load
     */
    public function __invoke($args, $assoc_args)
    {
        $csv_path = isset($assoc_args['csv'])
            ? $assoc_args['csv']
            : plugin_dir_path(__FILE__) . 'Phonic-Phase-Books.csv';

        $dry_run = isset($assoc_args['dry-run']);

        if (!file_exists($csv_path)) {
            WP_CLI::error("CSV file not found: {$csv_path}");
            return;
        }

        $handle = fopen($csv_path, 'r');
        if ($handle === false) {
            WP_CLI::error("Could not open CSV file: {$csv_path}");
            return;
        }

        if ($dry_run) {
            WP_CLI::log('-- DRY RUN: no changes will be written --');
        }

        // Skip header row.
        fgetcsv($handle);

        $updated   = 0;
        $skipped   = 0;
        $not_found = 0;

        while (($row = fgetcsv($handle)) !== false) {

            $slug          = trim($row[2] ?? '');
            $sound_cluster = trim($row[4] ?? '');

            if ($slug === '') {
                $skipped++;
                continue;
            }

            $posts = get_posts([
                'name'        => $slug,
                'post_type'   => 'ebook',
                'post_status' => 'any',
                'numberposts' => 1,
            ]);

            if (empty($posts)) {
                WP_CLI::warning("Post not found for slug: {$slug}");
                $not_found++;
                continue;
            }

            $post_id = $posts[0]->ID;

            if ($dry_run) {
                WP_CLI::log("[dry-run] Would update '{$slug}' (ID {$post_id}) → sound_cluster = '{$sound_cluster}'");
            } else {
                update_post_meta($post_id, 'sound_cluster', $sound_cluster);
                WP_CLI::log("Updated '{$slug}' (ID {$post_id}) → sound_cluster = '{$sound_cluster}'");
            }

            $updated++;
        }

        fclose($handle);

        $action = $dry_run ? 'Would update' : 'Updated';
        WP_CLI::success("{$action} {$updated} post(s). Skipped (empty slug): {$skipped}. Not found: {$not_found}.");
    }
}

WP_CLI::add_command('sync-sound-cluster', 'Sound_Cluster_Sync_Command');
