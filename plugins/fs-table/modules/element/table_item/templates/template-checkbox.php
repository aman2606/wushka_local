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

defined('_JEXEC') or defined('ABSPATH') or die();

// Ensure these variables exist
/** @var $element */
/** @var $props */
/** @var $field */

// Checkbox title/aria-label
$props['checkbox_title'] = $props[$field] === '1'
    ? $element['table_datatables_translation_filter_checkbox_true']
    : $element['table_datatables_translation_filter_checkbox_false'];

// Checkbox container
$checkbox_container = $this->el('div', [
    'class' => ['fs-table-checkbox-container'],
    'title' => $props['checkbox_title'],
    'aria-label' => $props['checkbox_title'],
    'role' => 'img',
]);

$checkbox = $this->el('input', [
    'class' => [
        'fs-table-checkbox',
        'uk-checkbox',
    ],
    'type' => 'checkbox',
    'checked' => (bool)$props[$field],
    'disabled' => true,
    'readonly' => true,
    'name' => "fs-table-checkbox-{$this->uid()}",
    'aria-hidden' => ['true'],
]);

$checkbox_label = $this->el('div', [
    'class' => [
        "el-checkbox-label",
        'uk-text-small',
        //Own field align including general settings breakpoint
        "uk-text-{{$field}_align}[@{text_align_breakpoint} [uk-text-{text_align_fallback}]{@!text_align: justify}] {@{$field}_align} {@!{$field}_align_ignore_general}",

        //General align including general settings breakpoint
        "uk-text-{text_align}[@{text_align_breakpoint} [uk-text-{text_align_fallback}]{@!text_align: justify}]{@!{$field}_align}",

        //Own field align ignoring general settings breakpoint
        "uk-text-{{$field}_align} {@{$field}_align}{@{$field}_align_ignore_general}",
        'uk-font-default',
        'uk-hidden',
    ],
]);

?>

<?= $checkbox_container($props) ?>
<?= $checkbox($props) ?>
<?= $checkbox_label($props, $props['checkbox_title']) ?>
<?= $checkbox_container->end() ?>