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
/** @var $element */
/** @var $props */

$element['link_text'] = $props['link_text'] ?: $element['link_text'];

// Reset link bottom if the button is not enabled
if (!$element['show_link'] || !$element['link_text'] || $element['grid_masonry']) {
    $element['link_button_bottom'] = false;
}

// Title
$title = $this->el($element['title_element'], [
    'class' => [
        'el-title',
        'uk-{title_style}',
        'uk-card-title {@panel_style} {@!title_style}',
        'uk-heading-{title_decoration}',
        'uk-font-{title_font_family}',
        'uk-text-{title_color} {@!title_color: background}',
        'uk-margin[-{title_margin}]-top {@!title_margin: remove}',
        'uk-margin-remove-top {@title_margin: remove}',
        'uk-margin-remove-bottom',
        'fs-search-mark {@search}{@search_title}{@search_highlight}',
    ],
]);

// Meta
$meta = $this->el($element['meta_element'], [
    'class' => [
        'el-meta',
        'uk-{meta_style}',
        'uk-heading-{meta_decoration}',
        'uk-text-{meta_color}',
        'uk-margin[-{meta_margin}]-top {@!meta_margin: remove}',
        'uk-margin-remove-top {@meta_margin: remove}',
        'uk-margin-remove-bottom [uk-margin-{meta_margin: remove}-top]' => !in_array($element['meta_style'],
                ['', 'text-meta', 'text-lead', 'text-small', 'text-large'], true) || !in_array($element['meta_element'],
                ['div', 'span', 'object'], true),
        'fs-search-mark {@search}{@search_meta}{@search_highlight}',
        '{meta_visibility}',
    ],
]);

// Content
$content = $this->el('div', [
    'class' => [
        'el-content uk-panel',
        'uk-{content_style}',
        '[uk-text-left{@content_align}]',
        'uk-dropcap {@content_dropcap}',
        'uk-column-{content_column}[@{content_column_breakpoint}]',
        'uk-column-divider {@content_column} {@content_column_divider}',
        'uk-margin[-{content_margin}]-top {@!content_margin: remove}',
        'uk-margin-remove-top {@content_margin: remove}',
        'uk-margin-remove-bottom [uk-margin-{content_margin: remove}-top]' => !in_array($element['content_style'],
            ['', 'text-meta', 'text-lead', 'text-small', 'text-large'], true),
        'fs-search-mark {@search}{@search_content}{@search_highlight}',
        '{content_visibility}',
    ],
]);

// Link
$link_container = $this->el('div', [
    'class' => [
        'uk-margin[-{link_margin}]-top {@!link_margin: remove}',
        'uk-flex uk-flex-column uk-flex-1' => $element['link_button_bottom'],
        'uk-display-block' => !$element['link_button_bottom'],
        '{link_visibility}',
    ],
    'style' => $element['link_button_bottom'] ? [
        'justify-content: flex-end;',
        'align-self: flex-start; {@!text_align:right|center} {@!link_fullwidth}',
        'align-self: center; {@text_align:center} {@!link_fullwidth}',
        'align-self: flex-end; {@text_align:right} {@!link_fullwidth}',
    ] : null,
]);