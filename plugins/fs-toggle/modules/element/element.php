<?php /**
 * @package     [FS] Toggle element for YOOtheme Pro
 * @subpackage  fs-toggle
 *
 * @author      Flart Studio https://flart.studio
 * @copyright   Copyright (C) 2008-2026 Flart Studio. All rights reserved.
 * @license     GNU General Public License version 2 or later; see https://www.gnu.org/licenses/gpl-2.0.html
 * @license     Non-PHP assets in this package are proprietary — https://flart.studio/license
 * @link        https://flart.studio/yootheme-pro/toggle
 * @build       (FLART_BUILD_NUMBER)
 */

namespace YOOtheme;

defined('_JEXEC') or defined('ABSPATH') or die();

// Include the updates.php file to define the $updates array
include_once Path::get('./updates.php', __DIR__);

use FlartStudio\YOOtheme\Toggle\NodePropsHelper;

return [
    'transforms' => [
        'render' => static function ($node) {
            // Don't render empty element on frontend
            $hasContent = static function ($node) use (&$hasContent) {
                foreach ($node->children ?? [] as $child) {
                    if (!in_array($child->type, ['row', 'column'], true)) {
                        return true;
                    }
                    if ($hasContent($child)) {
                        return true;
                    }
                }

                return false;
            };

            if (empty($node->props['edit_mode']) && !$hasContent($node)) {
                return false;
            }

            // Load toggle JavaScript asset
            // The fs-toggle script ignores elements with the [data-disabled] attribute.
            app(Metadata::class)->set('script:fs-toggle', [
                'src' => Path::get('./assets/js/fs-toggle.min.js?v=1.1.4', __DIR__),
                'defer' => true,
            ]);

            // Adds the toggle helper script to auto-apply `uk-toggle` attribute to special format links (#fs-toggle=<element-id>[,<scroll-offset>]).
            if ($node->props['toggle_toggle_helper'] && $node->props['toggle_id']) {
                app(Metadata::class)->set('script:fs-toggle-helper', [
                    'src' => Path::get('./assets/js/fs-toggle-helper.min.js?v=2.0.4', __DIR__),
                    'defer' => true,
                ]);
            }

            return true;
        },
        // Runs before rendering the element
        'preload' => static fn($node) => NodePropsHelper::updateProps($node, 'preload'),

        // Executed before saving the element settings in the database.
        'presave' => static fn($node) => NodePropsHelper::updateProps($node, 'presave'),
    ],
];