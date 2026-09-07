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
/** @var $title */
/** @var $meta */
/** @var $content */
/** @var $link */
/** @var $link_container */
/** @var $__dir */

include __DIR__ . "/template-content.php";

// Title Grid
$grid = $this->el('div', [
    'class' => [
        'uk-child-width-expand',
        $element['title_grid_column_gap'] === $element['title_grid_row_gap'] ? 'uk-grid-{title_grid_column_gap}' : '[uk-grid-column-{title_grid_column_gap}] [uk-grid-row-{title_grid_row_gap}]',
        'uk-margin[-{title_margin}]-top {@!title_margin: remove} {@image_align: top}' => !$props['meta'] || $element['meta_align'] !== 'above-title',
        'uk-margin[-{meta_margin}]-top {@!meta_margin: remove} {@image_align: top} {@meta_align: above-title}' => $props['meta'],
    ],
    'style' => ['height:100%; {@link_button_bottom}'],
    'uk-grid' => true,
]);

$cell_title = $this->el('div', [
    'class' => [
        'uk-width-{title_grid_width}[@{title_grid_breakpoint}]',
        'uk-margin-remove-first-child',
        'uk-flex uk-flex-column {@link_button_bottom}',
    ],
]);

$cell_content = $this->el('div', [
    'class' => [
        'uk-margin-remove-first-child',
        'uk-flex uk-flex-column {@link_button_bottom}',
    ],
]);

?>

<?php $grid_position = 'item-top'; ?>

<?php if ($element['show_sublayout'] && $element['sublayout_position'] === $grid_position && $element['sublayout_align'] === 'top') : ?>
    <?= $this->render("$__dir/template-sublayout", compact('props')) ?>
<?php endif ?>

<?= $this->render("$__dir/template-grids-positions", compact('props', 'grid_position')) ?>

<?php if ($element['show_sublayout'] && $element['sublayout_position'] === $grid_position && $element['sublayout_align'] === 'bottom') : ?>
    <?= $this->render("$__dir/template-sublayout", compact('props')) ?>
<?php endif ?>

<?php if ($props['title'] && $element['title_align'] === 'left'): ?>
    <?= $grid($element) ?>
    <?= $cell_title($element) ?>
<?php endif ?>

<?= $props['meta'] && $element['meta_align'] === 'above-title' ? $meta($element, $props['meta']) : '' ?>

<?php if ($props['title']): ?>
    <?= $title($element) ?>
    <?php if ($element['title_color'] === 'background'): ?>
        <span class="uk-text-background"><?= $props['title'] ?></span>
    <?php elseif ($element['title_decoration'] === 'line'): ?>
        <span><?= $props['title'] ?></span>
    <?php else: ?>
        <?= $props['title'] ?>
    <?php endif ?>
    <?= $title->end() ?>
<?php endif ?>

<?= $props['meta'] && $element['meta_align'] === 'below-title' ? $meta($element, $props['meta']) : '' ?>

<?php if ($props['title'] && $element['title_align'] === 'left'): ?>
    <?= $cell_title->end() ?>
    <?= $cell_content($element) ?>
<?php endif ?>

<?php if ($props['image'] && $element['image_align'] === 'between'): ?>
    <div>
        <?= $this->render("$__dir/template-image-layout", compact('props')) ?>
    </div>
<?php endif ?>

<?php if ($element['show_rating']): ?>
    <?= $this->render("$__dir/template-rating", compact('props')) ?>
<?php endif ?>

<?php $grid_position = 'above-content'; ?>

<?php if ($element['show_sublayout'] && $element['sublayout_position'] === $grid_position && $element['sublayout_align'] === 'top') : ?>
    <?= $this->render("$__dir/template-sublayout", compact('props')) ?>
<?php endif ?>

<?= $this->render("$__dir/template-grids-positions", compact('props', 'grid_position')) ?>

<?php if ($element['show_sublayout'] && $element['sublayout_position'] === $grid_position && $element['sublayout_align'] === 'bottom') : ?>
    <?= $this->render("$__dir/template-sublayout", compact('props')) ?>
<?php endif ?>

<?= $props['meta'] && $element['meta_align'] === 'above-content' ? $meta($element, $props['meta']) : '' ?>

<?php if ($props['content']): ?>

    <?php if ($element['content_hr_before']): ?>
        <?php echo '<hr class="content-before" />'; ?>
    <?php endif ?>

    <?= $content($element, $props['content']) ?>

    <?php if ($element['content_hr_after']): ?>
        <?php echo '<hr class="content-after" />'; ?>
    <?php endif ?>

<?php endif ?>

<?= $props['meta'] && $element['meta_align'] === 'below-content' ? $meta($element, $props['meta']) : '' ?>

<?php $grid_position = 'bellow-content'; ?>

<?php if ($element['show_sublayout'] && $element['sublayout_position'] === $grid_position && $element['sublayout_align'] === 'top') : ?>
    <?= $this->render("$__dir/template-sublayout", compact('props')) ?>
<?php endif ?>

<?= $this->render("$__dir/template-grids-positions", compact('props', 'grid_position')) ?>

<?php if ($element['show_sublayout'] && $element['sublayout_position'] === $grid_position && $element['sublayout_align'] === 'bottom') : ?>
    <?= $this->render("$__dir/template-sublayout", compact('props')) ?>
<?php endif ?>

<?php if ($props['link'] && ($props['link_text'] || $element['link_text'])): ?>
    <?= $link_container($element, $link($element, $props['link_text'] ?: $element['link_text'])) ?>
<?php endif ?>

<?php if ($props['title'] && $element['title_align'] === 'left'): ?>
    <?= $cell_content->end() ?>
    <?= $grid->end() ?>

<?php endif ?>

<?php $grid_position = 'item-bottom'; ?>

<?php if ($element['show_sublayout'] && $element['sublayout_position'] === $grid_position && $element['sublayout_align'] === 'top') : ?>
    <?= $this->render("$__dir/template-sublayout", compact('props')) ?>
<?php endif ?>

<?= $this->render("$__dir/template-grids-positions", compact('props', 'grid_position')) ?>

<?php if ($element['show_sublayout'] && $element['sublayout_position'] === $grid_position && $element['sublayout_align'] === 'bottom') : ?>
    <?= $this->render("$__dir/template-sublayout", compact('props')) ?>
<?php endif ?>