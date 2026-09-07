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
/** @var $i */

$number = $i++;

$el = $this->el('td', [
    'class' => [
        'fs-table-column fs-table-column-counting fs-table-counting uk-text-nowrap uk-table-shrink',
        'uk-{counter_style}',
        'uk-text-{counter_color}',
        'uk-text-{counter_align}',
        '[{counter_visibility}{@counter_visibility}]',
    ],

    //DataTables sorting search and filter attributes
    'data-order' => $this->expr(["$number {@enable_datatables}{@table_datatables_ordering}"], $element) ?: false,
    'data-search' => $this->expr(["$number {@enable_datatables}{@table_datatables_search}"], $element) ?: false,
    'data-tags' => $this->expr(["$number {@enable_datatables}{@table_datatables_filter}"], $element) ?: false,
]);

$counter = $this->el('div', ['class' => ['el-counter']]);

// Thead for Responsive Table
$thead = $this->el('span', ['class' => ['fs-thead-stacked', 'uk-hidden@m']]);

?>

<?= $el($element) ?>
<?= $counter($element) ?>

<?php if (!$element['table_head_hide'] && $element['table_responsive'] === 'responsive' && $element['table_head_counter']): ?>
    <?= $thead($element, $element['table_head_counter'] . ' ') ?>
<?php endif ?>

<?= $i++ ?>

<?= $counter->end() ?>
<?= $el->end() ?>