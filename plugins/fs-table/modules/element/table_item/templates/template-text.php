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
/** @var $checkbox */
/** @var $__dir */

$props["{$field}_additional_content"] = false;
if ((($element['show_sublayout'] && $element['sublayout_position'] === $field) || ($element['show_rating'] && $element['rating_position'] === $field))) {
    $props["{$field}_additional_content"] = true;
}

if ($props[$field] === '' && $props["{$field}_additional_content"] === false) {
    return;
}

// field style
$props['class'] = 'el-' . preg_replace('/-+/', '_', $field);
$el = $this->el($element["{$field}_element"] ?: 'div', [
    'class' => [
        $props['class'],
        "uk-{{$field}_style}{@!{$field}_style:label}",
        "uk-font-{{$field}_font_family}{@!{$field}_style:label}",
        "uk-text-{{$field}_color}{@!{$field}_style:label}",
        "uk-text-{{$field}_transform}{@!{$field}_style:label}",

        //Own field align including general settings breakpoint
        "uk-text-{{$field}_align}[@{text_align_breakpoint} [uk-text-{text_align_fallback}]{@!text_align: justify}] {@{$field}_align} {@!{$field}_align_ignore_general}",

        //General align including general settings breakpoint
        "uk-text-{text_align}[@{text_align_breakpoint} [uk-text-{text_align_fallback}]{@!text_align: justify}]{@!{$field}_align}",

        //Own field align ignoring general settings breakpoint
        "uk-text-{{$field}_align} {@{$field}_align}{@{$field}_align_ignore_general}",

        "uk-margin[-{{$field}_margin}]-top {@!{$field}_margin: remove}" => $props["{$field}_additional_content"] && (($element['show_sublayout'] && $element['sublayout_position'] === $field && $element['sublayout_align'] === 'top') || ($element['show_rating'] && $element['rating_position'] === $field && $element['rating_align'] === 'top')),
        "uk-margin-remove-top {@{$field}_margin: remove}",
        "uk-margin-remove-bottom",
    ],
]);

// label
$label_span = $this->el('span', [
    'class' => [
        "uk-width-1-1@l {@{$field}_label_width}",
        "uk-width-auto {@{$field}_label_width}",
        "uk-font-{{$field}_font_family}",
        "uk-text-{{$field}_color}",
        "uk-text-{{$field}_align}",
        "uk-text-{{$field}_transform}",
        "uk-label [uk-label-{{$field}_label_color}]",
    ],
]);

// Thead for Responsive Table
$thead = $this->el('span', [
    'class' => [
        'fs-thead-stacked',
        'uk-hidden@m',
    ],
]);

?>

<?php if ($element['show_sublayout'] && $element['sublayout_position'] === $field && $element['sublayout_align'] === 'top') : ?>
    <?= $this->render("$__dir/template-sublayout", compact('field')) ?>
<?php endif ?>

<?php if ($element['show_rating'] && $element['rating_position'] === $field && $element['rating_align'] === 'top') : ?>
    <?= $this->render("$__dir/template-rating", compact('field')) ?>
<?php endif ?>

<?php if ($props[$field] !== '') : ?>
    <?= $el($element) ?>

    <?php if (!$element['table_head_hide'] && $element['table_responsive'] === 'responsive' && $element["table_head_$field"]): ?>
        <?= $thead($element, $element["table_head_$field"] . ':') ?>
    <?php endif ?>

    <?php if ($checkbox && in_array(strtolower(strip_tags(trim($props[$field]))), ['1', '0'], true)): ?>
        <?= $this->render("$__dir/template-checkbox", compact('field', 'checkbox')) ?>
    <?php else: ?>

        <?= $element["{$field}_style"] === 'label' ? $label_span($element) : false ?>
        <?= $props[$field] ?>
        <?= $element["{$field}_style"] === 'label' ? $label_span->end() : false ?>

    <?php endif ?>

    <?= $el->end() ?>
<?php endif ?>

<?php if ($element['show_rating'] && $element['rating_position'] === $field && $element['rating_align'] === 'bottom') : ?>
    <?= $this->render("$__dir/template-rating", compact('field')) ?>
<?php endif ?>

<?php if ($element['show_sublayout'] && $element['sublayout_position'] === $field && $element['sublayout_align'] === 'bottom') : ?>
    <?= $this->render("$__dir/template-sublayout", compact('field')) ?>
<?php endif ?>