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

namespace YOOtheme;

defined('_JEXEC') or defined('ABSPATH') or die();

return [
    'transforms' => [
        'render' => static function ($node, $params): bool {
            $props = &$node->props; // modify directly

            // Disable fields based on parent "show_*"
            foreach (['title', 'meta', 'content', 'link', 'image'] as $key) {
                if (empty($params['parent']->props["show_$key"])) {
                    $props[$key] = '';
                    if ($key === 'image') {
                        $props['icon'] = '';
                    }
                }
            }

            // Helper: non-empty (with "0" valid)
            $hasValue = static fn($val): bool => $val !== null && (is_string($val) ? trim($val) !== '' : (bool)$val);

            // Base fields
            foreach (['title', 'meta', 'content', 'image', 'icon', 'link'] as $field) {
                if ($hasValue($props[$field] ?? null)) {
                    return true;
                }
            }

            // Children count as content
            if (!empty($node->children)) {
                return true;
            }

            // Custom fields
            $customSets = (int)($params['parent']->props['custom_field_sets'] ?? 0);
            for ($i = 1; $i <= $customSets; $i++) {
                foreach (["custom_text_$i", "custom_meta_$i", "custom_image_$i"] as $key) {
                    if ($hasValue($props[$key] ?? null)) {
                        return true;
                    }
                }
            }

            return false;
        },
        'load' => static function ($node) {
            // Reset navigator dropdowns for better UX
            $node->props['show_custom_settings'] = 'all';
        },
    ],
];