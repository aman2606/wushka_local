<?php /**
 * @package     [FS] Table Pro element for YOOtheme Pro
 * @subpackage  fs-table
 *
 * @author      Flart Studio https://flart.studio
 * @copyright   Copyright (C) 2026 Flart Studio. All rights reserved.
 * @license     GNU General Public License version 2 or later; see https://flart.studio/license
 * @link        https://flart.studio/yootheme-pro/table-pro
 * @build       (FLART_BUILD_NUMBER)
 */

/** @noinspection DuplicatedCode */

defined('_JEXEC') or defined('ABSPATH') or die();

// Ensure these variables exist
/** @var $element */
/** @var $props */
/** @var $field */
/** @var $link */
/** @var $__dir */

$props['link_text'] = $props['link_text'] ?: $element['link_text'];

if (!$props['link'] || !$props['link_text']) {
    return;
}

// Link
$el = $this->el('a', [
    'class' => [
        'el-link',
        'uk-{link_style: link-\w+}',
        'uk-button uk-button-{!link_style: |link-\w+} [uk-button-{link_size}]',
        '{0} {@!link_style: |text|link-\w+} {@link_fullwidth}' => $element['table_responsive'] === 'responsive' ? 'uk-width-auto uk-width-1-1@m' : 'uk-width-1-1',
    ],
]);

$el->attr($link->attrs);

echo $el($element, $props['link_text']);

if ($element['sublayout_position'] === $field && $element['show_sublayout'] && $element['sublayout_mode'] === "modal") {
    echo $this->render("$__dir/template-sublayout", compact('field'));
}