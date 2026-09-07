<?php /**
 * @package     [FS] Grid Pro element for YOOtheme Pro
 * @subpackage  fs-grid
 *
 * @author      Flart Studio https://flart.studio
 * @copyright   Copyright (C) 2008-2026 Flart Studio. All rights reserved.
 * @license     GNU General Public License version 2 or later; see https://www.gnu.org/licenses/gpl-2.0.html
 * @license     Non-PHP assets in this package are proprietary — https://flart.studio/license
 * @link        https://flart.studio/yootheme-pro/grid-pro
 * @build       (FLART_BUILD_NUMBER)
 */

/** @noinspection AutoloadingIssuesInspection, DuplicatedCode */

namespace FlartStudio\YOOtheme\Grid;

defined('_JEXEC') or defined('ABSPATH') or die();

use YOOtheme\Arr;
use YOOtheme\Application;
use YOOtheme\Config;

class NodePropsHelper
{
    private static ?array $updates = null;

    /**
     * Update node properties based on version migrations
     *
     * @param object $node The node to update
     * @param string $event The event type (presave, etc.)
     * @return void
     * @since 1.5.5
     */
    public static function updateProps(object $node, string $event): void
    {
        $version = $node->props['_fs_props_version'] ?? '0.0.0';
        self::$updates ??= [
            '1.6.0' => static function (object $node, string $event, string &$version): void {
                // Handle YOOtheme v4→v5 upgrade (no version bump - must run repeatedly)
                if (Arr::get($node->props, '_yootheme_v4') === true) {
                    if (version_compare(Application::getInstance()->get(Config::class)?->get('theme.version'), '5.0', '>=')) {
                        $node->props['_yootheme_v5'] = true;
                        unset($node->props['_yootheme_v4']);
                        // Bump only after successful migration
                        $event === 'presave' && $version = '1.6.0';
                    }
                } else {
                    // No v4 flag = fresh v5 install or already migrated, safe to bump
                    $event === 'presave' && $version = '1.6.0';
                }
            },
            '1.5.5' => static function (object $node, string $event, string &$version): void {
                // YOOtheme version detection
                $theme_version = Application::getInstance()->get(Config::class)?->get('theme.version');
                $node->props[version_compare($theme_version, '5.0', '<') ? '_yootheme_v4' : '_yootheme_v5'] = true;

                if (Arr::get($node->props, 'margin')) {
                    $node->props['margin_top'] = Arr::get($node->props, 'margin_remove_top') === true ? 'remove' :
                        Arr::get($node->props, 'margin');
                    $node->props['margin_bottom'] = Arr::get($node->props, 'margin_remove_bottom') === true ? 'remove' :
                        Arr::get($node->props, 'margin');
                    unset($node->props['margin'], $node->props['margin_remove_top'], $node->props['margin_remove_bottom']);
                }

                // Bump only on presave
                $event === 'presave' && $version = '1.5.5';
            },
        ];

        // Sort by version number ascending
        $updates = self::$updates;
        uksort($updates, 'version_compare');

        // Apply sequentially
        foreach ($updates as $updateVersion => $callback) {
            if (version_compare($version, $updateVersion, '<')) {
                $callback($node, $event, $version);
            }
        }

        // Reset navigators dropdown
        $node->props['show_element_settings'] = $node->props['show_custom_settings'] = 'all';

        // Store final props version
        $node->props['_fs_props_version'] = $version;
    }
}