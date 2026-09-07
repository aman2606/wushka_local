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
/** @var $filtered */
/** @var $text_fields */
/** @var $__dir */
/** @var $i */

$element['show_checkbox'] = $props['show_checkbox'] ?: $element['show_checkbox'];
$element['link_text'] = $props['link_text'] ?: $element['link_text'];
$element['table_datatables_filter_dropdown_width'] = $element['table_datatables_filter_dropdown_width'] ?: 85;

$source_date_format = $element["table_date_format"] ?: 'd-m-Y';
$date_format_new = $element["table_date_format_new"] ?: false;
$number_format_decimals = $element["table_number_format_decimal_places"] ?: false;
$number_format_new = $element["table_number_format_new"] ?: false;

//Automatic modal integration
if ($element['show_link'] && $element['show_sublayout'] && $element['sublayout_mode'] === "modal" && $element['sublayout_modal_wrap'] === "all") {
    if ($props['link_toggle'] && $props['link_toggle_modal_integration']) {
        $props['link_modal_connect'] = "fs-table-modal-{$this->uid()}";
        $props['link'] = "#{$props['link_modal_connect']}";
        $props['link_modal_id'] = $props['link_modal_connect'];
    }
}

if ($element['show_counter']) {
    echo $this->render("$__dir/template-counter", compact('i'));
}

foreach ($filtered as $j => $field) {
    $date = '';
    $data_order = '';
    $data_search = '';
    $data_filter = '';
    $checkbox = false;

    // Adding data-order attribute for DataTables sorting and filtering feature
    if ($props[$field] != '' && in_array($field, $text_fields)) {
        //Strip Tags, trim, remove double spaces for sorting
        $props["{$field}_stripped"] = htmlspecialchars(
            trim(preg_replace('/\s+/', ' ', strip_tags($props[$field]))),
            ENT_QUOTES
        );

        //ISO 8601 date format YYYY-MM-DD is required for proper DataTables sorting
        $date = str_replace(['/', '.'], ['-', '-'], $props["{$field}_stripped"]);
        $date = DateTime::createFromFormat($source_date_format, $date);

        //check if field contains date
        if ($date && $element["{$field}_data"] === 'date') {
            $data_order = $date->format('Y-m-d');
            $data_search = $data_filter = $props["{$field}_stripped"];

            //Date Format New
            if ($date_format_new) {
                $props[$field] = $date->format($date_format_new);
                $data_search = $data_filter = $props[$field];
            }
        } //check if field contains number
        elseif (is_numeric(str_replace(',', '.', $props["{$field}_stripped"])) && $element["{$field}_data"] === 'number') {
            $data_order = str_replace(',', '.', $props["{$field}_stripped"]);
            $data_search = $data_filter = $props["{$field}_stripped"];

            //skip checkboxes from converting
            if ($element['show_checkbox'] && in_array($props["{$field}_stripped"], ['0', '1']) === true) {
                if ($props["{$field}_stripped"] === '0') {
                    $data_order = $data_search = $data_filter = $element["table_datatables_translation_filter_checkbox_false"] ?: 'No';
                } elseif ($props["{$field}_stripped"] === '1') {
                    $data_order = $data_search = $data_filter = $element["table_datatables_translation_filter_checkbox_true"] ?: 'Yes';
                }
                $checkbox = true;
            } elseif ($number_format_new || !empty($number_format_decimals)) {
                //change decimal places
                if (!empty($number_format_decimals) && $number_format_decimals != '0') {
                    $props["{$field}_stripped"] = sprintf("%.{$number_format_decimals}f", $data_order);
                }
                //change decimal separator
                if ($number_format_new) {
                    //Number Format New
                    $nfn = $number_format_new;
                    $props[$field] = str_replace([',', '.'], [$nfn, $nfn], $props["{$field}_stripped"]);
                    $data_search = $data_filter = $props[$field];
                }
            }
        } // field contains string
        else {
            $data_search = $data_filter = $data_order = $props["{$field}_stripped"];
        }
    } elseif ($field === 'link') {
        //Strip Tags, trim, remove double spaces for sorting
        $props["link_stripped"] = trim(preg_replace('/\s+/', ' ', strip_tags(trim($element['link_text']))));
        if (!empty($props['link'])) {
            $data_search = $data_filter = $data_order = $props["link_stripped"];
        }
    } elseif (in_array($field, $text_fields) && $element['show_rating'] && $element['rating_position'] === $field) {
        //fixing incorrect rating values
        empty($props['rating']) || $props['rating'] == null ? $props['rating'] = 0 : false;
        if (is_numeric($props['rating'])) {
            $props['rating'] = number_format($props['rating'], 1);
            $props['rating'] > 5 ? $props['rating'] = 5 : false;
            $props['rating'] < 0 ? $props['rating'] = 0 : false;
        } else {
            $props['rating'] = 0;
        }
        $data_search = $data_filter = $data_order = $props["rating"];
    }

    // set custom table width if empty
    if ($element["table_width_$field"] === 'custom' && empty($element["table_width_{$field}_custom"])) {
        $element["table_width_{$field}_custom"] = '100';
    }

    // Limit Output
    if (!empty($element["{$field}_limit"]) && !empty($props[$field]) && !in_array($field, ['image', 'link'])) {
        $limit = (int)($element["{$field}_limit_length"] ?? 0);
        $plain = strip_tags($props[$field]);
        $props[$field] = ($limit > 0 && mb_strlen($plain, 'UTF-8') > $limit) ? mb_substr($plain, 0, $limit,
                'UTF-8') . '...' : $props[$field];
    }

    // Link
    $link = include __DIR__ . "/link.php";

    //enables description and label text search
    if ($element['show_title'] && $props['title'] && $element['enable_datatables'] && $element['table_datatables_search'] && $field === 'title') {
        if (!$element['table_datatables_filter'] || ($element['table_datatables_filter'] && !$element['table_datatables_filter_regex'])) {
            if ($element['show_label'] && $props['label'] && $element['table_datatables_search_label']) {
                $data_search .= ' ' . $props['label'];
            }
            if ($element['show_description'] && $props['description'] && $element['table_datatables_search_description']) {
                $data_search .= ' ' . $props['description'];
            }
        }
    }

    //Template file selector
    $file = $field !== 'title' && in_array($field, $text_fields, true) ? 'text' : $field;

    // Diacritics-neutralise
    if ($element['enable_datatables'] && $element['table_datatables_search'] && $element['table_datatables_diacritics_neutralise']) {
        include __DIR__ . "/template-diacritics.php";
    }

    $data_order = strip_tags($data_order ?? '');
    $data_search = strip_tags($data_search ?? '');
    $data_filter = strip_tags($data_filter ?? '');

    echo $this->el('td', [

        'class' => [
            "fs-table-column fs-table-column-$j",
            "fs-table-$field",
            'uk-text-nowrap' => ($field === 'link' || ($field !== 'title' && $element["table_width_$field"] === 'shrink' && (!$element['show_sublayout'] || ($element['show_sublayout'] && ($element['sublayout_position'] !== $field || ($element['sublayout_position'] === $field && $element['sublayout_mode'] === 'modal')))))),
            "uk-[table {@table_width_$field: shrink}][width {@!table_width_$field: shrink}]-{table_width_$field}" => $i == 0,
            "uk-table-expand{@!table_width_$field}", // expand
            "{{$field}_visibility} {@{$field}_visibility}",
        ],

        'style' => $this->expr([
            "min-width: {table_width_{$field}_custom}px;" => $element["table_width_$field"] === 'custom',
            "width: {table_width_{$field}_custom}px;" => $element["table_width_$field"] === 'custom',
            "max-width: {table_width_{$field}_custom}px;" => $element["table_width_$field"] === 'custom',
            "width:100%;{@!table_width_$field}", // expand
            "min-width: {table_datatables_filter_dropdown_width}px; {@enable_datatables}{@table_datatables_search}{@table_datatables_filter}{@table_datatables_filter_$field}" => $element["table_width_$field"] === 'shrink',
        ], $element) ?: false,

        //DataTables sorting search and filter attributes
        'data-order' => $this->expr(["$data_order {@enable_datatables}{@table_datatables_ordering}"],
            $element) ?: $element['enable_datatables'],
        'data-search' => $this->expr(["$data_search {@enable_datatables}{@table_datatables_search}"],
            $element) ?: $element['enable_datatables'],
        'data-tags' => $this->expr(["$data_filter {@enable_datatables}{@table_datatables_search}{@table_datatables_filter}"],
            $element) ?: $element['enable_datatables'],

    ], $this->render("$__dir/template-$file",
        compact('props', 'link', 'field', 'checkbox', 'data_filter', 'i')))->render($element);
}