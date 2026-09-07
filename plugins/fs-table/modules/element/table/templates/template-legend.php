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

// Table Legend Title
$table_title = $this->el($props['table_title_element'], [
    'class' => [
        'el-table-title',
        'uk-{table_title_style}',
        '[uk-text-left{@table_title_align}]',
        'uk-card-table_title {@panel_style} {@!table_title_style}',
        'uk-heading-{table_title_decoration}',
        'uk-font-{table_title_font_family}',
        'uk-text-{table_title_color} {@!table_title_color: background}',
        'uk-margin[-{table_title_margin}]-top {@!table_title_margin: remove}{@table_meta_position:above-table-title}',
        'uk-margin-remove-top {@table_title_margin: remove}{@table_meta_position:above-table-title}',
        'uk-margin-remove-bottom',
        '{table_title_visibility} {@table_title_visibility}',
    ],
]);

// Table Legend Meta
$table_meta = $this->el($props['table_meta_element'], [
    'class' => [
        'el-table-meta',
        'uk-{table_meta_style}',
        '[uk-text-left{@table_meta_align}]',
        'uk-text-{table_meta_color}',
        'uk-heading-{table_meta_decoration}',
        'uk-margin[-{table_meta_margin}]-top {@!table_meta_margin: remove}',
        'uk-margin-remove-top {@table_meta_margin: remove}',
        'uk-margin-remove-bottom',
        '[{table_meta_visibility} {@table_meta_visibility}]',
    ],
]);

// Table Legend Content
$table_content = $this->el('div', [
    'class' => [
        'el-table-content uk-panel',
        'uk-{table_content_style}',
        '[uk-text-left{@table_content_align}]',
        'uk-dropcap {@table_content_dropcap}',
        'uk-column-{table_content_column}[@{table_content_column_breakpoint}]',
        'uk-column-divider {@table_content_column} {@table_content_column_divider}',
        'uk-margin[-{table_content_margin}]-top {@!table_content_margin: remove}',
        'uk-margin-remove-top {@table_content_margin: remove}',
        'uk-margin-remove-bottom',
        '[{table_content_visibility} {@table_content_visibility}]',
    ],
]);

// Limit Output
foreach (['table_title', 'table_meta', 'table_content'] as $field) {
    if (!empty($props["{$field}_limit"]) && !empty($props[$field])) {
        $limit = (int)($props["{$field}_limit_length"] ?? 0);
        $plain = strip_tags($props[$field]);
        $props[$field] = ($limit > 0 && mb_strlen($plain, 'UTF-8') > $limit) ? mb_substr($plain, 0, $limit,
                'UTF-8') . '...' : $props[$field];
    }
}

// Table Legend Title Grid
$grid = $this->el('div', [
    'class' => [
        'uk-child-width-expand',
        $props['table_title_grid_column_gap'] === $props['table_title_grid_row_gap'] ? 'uk-grid-{table_title_grid_column_gap}' : '[uk-grid-column-{table_title_grid_column_gap}] [uk-grid-row-{table_title_grid_row_gap}]',
    ],
    'uk-grid' => true,
]);

$cell_table_title = $this->el('div', [
    'class' => [
        'uk-width-{table_title_grid_width}[@{table_title_grid_breakpoint}]',
        'uk-margin-remove-first-child',
    ],
]);

$cell_table_content = $this->el('div', ['class' => ['uk-margin-remove-first-child']]);

?>

<?php if ($props['show_table_title'] && $props['table_title'] && $props['table_title_position'] === 'left'): ?>
    <?= $grid($props) ?>
    <?= $cell_table_title($props) ?>
<?php endif ?>

<?php if ($props['show_table_meta'] && $props['table_meta'] && $props['table_meta_position'] === 'above-table-title'): ?>
    <?= $table_meta($props, $props['table_meta']) ?>
<?php endif ?>

<?php if ($props['show_table_title'] && $props['table_title']): ?>
    <?= $table_title($props) ?>
    <?php if ($props['table_title_color'] === 'background'): ?>
        <span class="uk-text-background"><?= $props['table_title'] ?></span>
    <?php elseif ($props['table_title_decoration'] === 'line'): ?>
        <span><?= $props['table_title'] ?></span>
    <?php else: ?>
        <?= $props['table_title'] ?>
    <?php endif ?>
    <?= $table_title->end() ?>
<?php endif ?>

<?php if ($props['show_table_meta'] && $props['table_meta'] && $props['table_meta_position'] === 'below-table-title'): ?>
    <?= $table_meta($props, $props['table_meta']) ?>
<?php endif ?>

<?php if ($props['show_table_title'] && $props['table_title'] && $props['table_title_position'] === 'left'): ?>
    <?= $cell_table_title->end() ?>
    <?= $cell_table_content($props) ?>
<?php endif ?>

<?php if ($props['show_table_meta'] && $props['table_meta'] && $props['table_meta_position'] === 'above-table-content'): ?>
    <?= $table_meta($props, $props['table_meta']) ?>
<?php endif ?>

<?php if ($props['show_table_content'] && $props['table_content']): ?>
    <?= $table_content($props, $props['table_content']) ?>
<?php endif ?>

<?php if ($props['show_table_meta'] && $props['table_meta'] && $props['table_meta_position'] === 'below-table-content'): ?>
    <?= $table_meta($props, $props['table_meta']) ?>
<?php endif ?>

<?php if ($props['show_table_title'] && $props['table_title'] && $props['table_title_position'] === 'left'): ?>
    <?= $cell_table_content->end() ?>
    <?= $grid->end() ?>
<?php endif ?>