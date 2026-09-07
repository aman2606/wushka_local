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
/** @var $props */

//DataTables props prefix short key
$dt = 'table_datatables';

//reset
if (!$props["{$dt}_sticky"]) {
    $props["{$dt}_fixed_footer"] = $props["{$dt}_fixed_header"] = false;
}

//dataTables params array
$params = array(
    'elementID' => $props['element_id'],
    'tableID' => $props['table_id'],
    'searching' => $props["{$dt}_search"],
    'info' => $props["{$dt}_info"],
    'ordering' => $props["{$dt}_ordering"],
    'order' => $props["{$dt}_sorting_default_order"] ?: 'asc',
    'orderColumn' => is_numeric($defCol) ? json_encode([$defCol, $props["{$dt}_sorting_default_order"]]) : '',
    'paging' => $props["{$dt}_paging"],
    'lengthChange' => $props["{$dt}_lengthChange"],
    'searchHighlight' => $props["{$dt}_search"] && $props["{$dt}_search_highlight"] ? true : false,
    'pageLength' => (int)$props["{$dt}_pageLength"] ?: 10,
    'tableResponsive' => $props["table_responsive"],
    'scrollX' => ($props["table_responsive"] === 'overflow' && $props["{$dt}_scrollY"]) || ($props["{$dt}_sticky"] && ($props["{$dt}_fixed_header"] || $props["{$dt}_fixed_footer"])) ? true : false,
    'scrollY' => $props["{$dt}_scrollY"] ?: '', //!important
    'stateSave' => $props["{$dt}_save_state"],

    'fixedHeader' => array(
        'header' => $props["{$dt}_fixed_header"],
        'footer' => $props["{$dt}_fixed_footer"],
        'headerOffset' => (int)$props["{$dt}_fixed_header_offset"] ?: 0,
        'footerOffset' => (int)$props["{$dt}_fixed_footer_offset"] ?: 0,
    ),

    'filter' => array(
        'enable' => $props["{$dt}_filter"],
        'position' => $props["{$dt}_filter_position"] ?: 'header',
        'columns' => $dtFiltered,
        'splitTags' => $props["{$dt}_filter_split_tags"] ?: false,
        'regex' => $props["{$dt}_filter_regex"] ?: false,
        'dropdownSize' => $props["{$dt}_filter_dropdown_size"] ?: '',
        'dropdownWidth' => (int)$props["{$dt}_filter_dropdown_width"] ?: 85,
        'dropdownPlaceholder' => $props["{$dt}_filter_placeholder"] ?: 'col-name',
        'dropdownTextLimit' => (int)$props["{$dt}_filter_dropdown_limit"] ?: 0,
        'dropdownTextTransform' => $props["{$dt}_filter_text_transform"] ?: '',
    ),

    'translations' => array(
        'search' => $props["{$dt}_translation_search"],
        'zeroRecords' => $props["{$dt}_translation_zeroRecords"],
        'info' => $props["{$dt}_translation_info"],
        'infoEmpty' => $props["{$dt}_translation_infoEmpty"],
        'lengthMenu' => $props["{$dt}_translation_lengthMenu"],
        'paginationAll' => $props["{$dt}_translation_paginationAll"],
        'previous' => $props["{$dt}_translation_pagination_previous"],
        'next' => $props["{$dt}_translation_pagination_next"],
        'infoFiltered' => $props["{$dt}_translation_infoFiltered"],
        'filterAll' => $props["{$dt}_translation_filter_all"],
    ),
);

?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let jQuery = window.jQuery || window.$;
        if (jQuery) {
            window.jQuery = window.$ = jQuery;
            new FSdataTables(<?= json_encode($params) ?>);
        } else {
            UIkit.notification('Table Pro element: jQuery is not loaded.', {
                status: 'warning',
                pos: 'bottom-right'
            });
        }
    });
</script>