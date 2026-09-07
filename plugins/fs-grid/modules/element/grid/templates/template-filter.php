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
/** @var $__dir */

//Position sticky is not working with uk-width-auto
$props['filter_grid_width'] = $props['filter_grid_width'] === 'auto' && $props['filter_position_sticky'] && !$props['slider'] ? 'small' : $props['filter_grid_width'];

// Filter
$filter_grid = $this->el('div', [
    'class' => [
        'uk-child-width-expand',
        $props['filter_grid_column_gap'] === $props['filter_grid_row_gap'] ? 'uk-grid-{filter_grid_column_gap}' : '[uk-grid-column-{filter_grid_column_gap}] [uk-grid-row-{filter_grid_row_gap}]',
    ],
    'uk-grid' => true,
]);

$filter_cell = $this->el('div', [
    'class' => [
        'uk-width-{filter_grid_width}@{filter_grid_breakpoint}',
        'uk-flex-last@{filter_grid_breakpoint} {@filter_position: right}',
    ],
]);

// Sticky
$sticky = $props['filter_position_sticky'] ? $this->el('div', [
    'class' => ['fs-grid-pro-filter-sticky uk-panel'],
    'style' => ['z-index: 2;'],
    'uk-sticky' => $this->expr([
        'offset: {filter_position_sticky_offset};',
        'width-element: false;',
        'bottom: true;',
        'media: @{filter_position_sticky_breakpoint};',
    ], $props) ?: true,
]) : null;

$filter_horizontal = in_array($props['filter_position'], ['left', 'right']);

?>

<?php if ($filter_horizontal): ?>
    <?= $filter_grid($props) ?>
    <?= $filter_cell($props) ?>
<?php endif ?>

<?= $sticky && !$props['slider'] ? $sticky($props) : false ?>
<?php
if (class_exists('Joomla\CMS\Helper\ModuleHelper')) {
    $modules = Joomla\CMS\Helper\ModuleHelper::getModules('grid-pro-filter-top');
    echo '<div class="grid-pro-filter-top uk-margin" type="module-position">';
    foreach ($modules as $module) {
        echo Joomla\CMS\Helper\ModuleHelper::renderModule($module);
    }
    echo '</div>';
}
?>
<?= $this->render("$__dir/template-filter-nav", compact('props')) ?>
<?php
if (class_exists('Joomla\CMS\Helper\ModuleHelper')) {
    $modules = Joomla\CMS\Helper\ModuleHelper::getModules('grid-pro-filter-bottom');
    echo '<div class="grid-pro-filter-bottom uk-margin" type="module-position">';
    foreach ($modules as $module) {
        echo Joomla\CMS\Helper\ModuleHelper::renderModule($module);
    }
    echo '</div>';
}
?>
<?= $sticky && !$props['slider'] ? $sticky->end() : false ?>

<?php if ($filter_horizontal): ?>
    <?= $filter_cell->end() ?>
    <div>
<?php endif ?>

<?php if ($props['slider']): ?>
    <?= $this->render("$__dir/template-slider") ?>
<?php else : ?>
    <?= $this->render("$__dir/template-content") ?>
<?php endif ?>

<?php if ($filter_horizontal): ?>
    </div>
    <?= $filter_grid->end() ?>
<?php endif ?>