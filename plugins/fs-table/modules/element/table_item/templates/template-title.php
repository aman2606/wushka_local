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
/** @var $field */
/** @var $link */
/** @var $__dir */

$props["{$field}_additional_content"] = false;
if ((($element['show_sublayout'] && $element['sublayout_position'] === $field) || ($element['show_rating'] && $element['rating_position'] === $field))) {
    $props["{$field}_additional_content"] = true;
}

if ($props['title'] === '' && $props["{$field}_additional_content"] === false) {
    return;
}

// Title
$title = $this->el($element['title_element'] ?: 'div', [
    'class' => [
        'el-title',
        'uk-{title_style}',
        'uk-font-{title_font_family}',
        'uk-text-{title_color}',
        'uk-text-{title_transform}',

        //Own field align including general settings breakpoint
        'uk-text-{title_align}[@{text_align_breakpoint} [uk-text-{text_align_fallback}]{@!text_align: justify}] {@title_align} {@!title_align_ignore_general}',

        //General align including general settings breakpoint
        'uk-text-{text_align}[@{text_align_breakpoint} [uk-text-{text_align_fallback}]{@!text_align: justify}]{@!title_align}',

        //Own field align ignoring general settings breakpoint
        'uk-text-{title_align} {@title_align}{@title_align_ignore_general}',

        "uk-margin[-{title_margin}]-top {@!title_margin: remove}" => $props["{$field}_additional_content"] && (($element['show_sublayout'] && $element['sublayout_position'] === $field && $element['sublayout_align'] === 'top') || ($element['show_rating'] && $element['rating_position'] === $field && $element['rating_align'] === 'top')),
        "uk-margin-remove-top {@title_margin: remove}",
        'uk-margin-remove-bottom',
    ],
]);

// Description
$description = $this->el('div', [
    'class' => [
        'el-description',
        'uk-{description_style}',
        'uk-text-{description_color}',
        'uk-text-{description_align}',
        'uk-margin[-{description_margin}]-top {@!description_margin: remove}',
        'uk-margin-remove-top {@description_margin: remove}',
        'uk-margin-remove-bottom',
        'dt-search-ignore {@table_datatables_search}{@table_datatables_search_highlight}{@!table_datatables_search_description}',
        'dt-search-ignore {@table_datatables_search}{@table_datatables_search_highlight}{@table_datatables_filter}{@table_datatables_filter_regex}',
        '{description_visibility}' => $element['description_visibility'],
    ],
]);

//Label
$label = $this->el('span', [
    'class' => [
        'el-label',
        'uk-label',
        "uk-label-{$props['label_color']} {@!label_custom_color}",
        'dt-search-ignore {@table_datatables_search}{@table_datatables_search_highlight}{@!table_datatables_search_label}',
        'dt-search-ignore {@table_datatables_search}{@table_datatables_search_highlight}{@table_datatables_filter}{@table_datatables_filter_regex}',
        '{label_visibility}' => $element['label_visibility'],
    ],
    'style' => [
        "background: {$props['label_custom_color']};" => $props['label_custom_color'] && $props['label_color'] === 'custom',
        'margin-top: -15px; line-height: 1em;',
    ],
]);

// Thead for Responsive Table
$thead = $this->el('span', [
    'class' => [
        'fs-thead-stacked',
        'uk-hidden@m',
    ],
]);

//Limit Description Output
if ($element['description_limit'] && $props['description']) {
    $length = (int)$element['description_limit_length'];
    if ($length > 0) {
        $text = strip_tags($props['description']);
        strlen($text) > $length ? $props['description'] = substr($text, 0, $length) . "..." : false;
    }
}

if ($link && $props['description'] && $element['description_link'] && $element['show_link']) {
    $props['description'] = $link($element, [
        'class' => [
            'uk-link-{description_hover_style}' => $element['description_hover_style'],
        ],
    ], $this->striptags($props['description']));
}

?>

<?php if ($element['show_sublayout'] && $element['sublayout_position'] === $field && $element['sublayout_align'] === 'top') : ?>
    <?= $this->render("$__dir/template-sublayout", compact('field')) ?>
<?php endif ?>

<?php if ($element['show_rating'] && $element['rating_position'] === $field && $element['rating_align'] === 'top') : ?>
    <?= $this->render("$__dir/template-rating", compact('field')) ?>
<?php endif ?>

<?php if (!empty($props['title'])) : ?>
    <?= $title($element) ?>

    <?php if (!$element['table_head_hide'] && $element['table_responsive'] === 'responsive' && $element['table_head_title']): ?>
        <?= $thead($element, $element['table_head_title'] . ':') ?>
    <?php endif ?>

    <?= $props['title'] ?>

    <?php if ($element['show_label'] && $props['label']): ?>
        <?= $label($element, $props['label']) ?>
    <?php endif ?>

    <?= $title->end() ?>

    <?php if ($element['show_description'] && $props['description']): ?>
        <?= $description($element, $props['description']) ?>
    <?php endif ?>
<?php endif ?>

<?php if ($element['show_rating'] && $element['rating_position'] === $field && $element['rating_align'] === 'bottom') : ?>
    <?= $this->render("$__dir/template-rating", compact('field')) ?>
<?php endif ?>

<?php if ($element['show_sublayout'] && $element['sublayout_position'] === $field && $element['sublayout_align'] === 'bottom') : ?>
    <?= $this->render("$__dir/template-sublayout", compact('field')) ?>
<?php endif ?>