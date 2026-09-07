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

defined('_JEXEC') or defined('ABSPATH') or die();

// Ensure these variables exist
/** @var $props */
/** @var $child */

$searchable_text = "";
$searchable_fields = ['title', 'meta', 'content', 'link', 'custom_meta', 'custom_text'];
$child->props['link_text'] = $child->props['link_text'] ?: $props['link_text'];

foreach ($searchable_fields as $key => $field) {
    if (!empty($props["search_$field"])) {
        if (!empty($props["show_$field"]) && $child->props[$field] && in_array($field,
                ["title", "meta", "content", "link"])) {
            if ($field === 'link' && !empty($child->props['link_text'])) {
                $searchable_text .= " " . $child->props['link_text'];
            } else {
                $searchable_text .= " " . $child->props[$field];
            }
        } elseif ($props['use_custom_fields'] && in_array($field, ["custom_meta", "custom_text"])) {
            for ($fieldset = 1; $fieldset <= $props['custom_field_sets']; $fieldset++) {
                if ($props["show_custom_$fieldset"] && $props["show_{$field}_$fieldset"] && $child->props["{$field}_$fieldset"]) {
                    $searchable_text .= " " . $child->props["{$field}_$fieldset"];
                }
            }
        }
    }
}

$searchable_text = trim(preg_replace('/\s+/', ' ', strip_tags($searchable_text)));