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
/** @var $tags */
/** @var $__dir */

$props['filter_tags_label'] = $props['filter_tags_label'] ?: 'Tags';
$filter_horizontal = in_array($props['filter_position'], ['left', 'right']);

//overrides
$props['filter_no_items_placeholder'] = $props['filter_no_items_placeholder'] ?: 'No Items Found';

$nav_all_container = $this->el('div', [

    'class' => [
        'fs-grid-filter-all',
        'uk-width-auto {@filter_align: justify}{@filter_position: top}',
    ],

]);

$nav_all = $this->el('ul', [

    'class' => [
        'el-nav',
        'uk-{filter_style} {@filter_style: tab}',
        'uk-tab {@filter_style: dropdown|list}',
        'uk-flex-top',
    ],

    'uk-scrollspy-class' => in_array($props['animation'], [
        'none', 'parallax'
    ]) || !$props['item_animation'] ? false : (!empty($props['animation']) ? ['uk-animation-{0}' => $props['animation']] : true),

]);

$nav = $this->el('ul', [

    'class' => [
        'el-nav',
        'uk-{filter_style} {@filter_style: tab}',
        'uk-tab {@filter_style: dropdown|list}',
        'uk-grid-match {@filter_position: top}{@filter_style: list}',
        'uk-width-small {@filter_position: top}{@!filter_align: justify}{@filter_style: list}',
        'uk-text-center {@filter_position: top}{@!filter_align: justify}{@filter_style: list}',

        'uk-child-width-auto {@filter_position: top}{@filter_align: justify}',
        'uk-child-width-expand@s {@filter_position: top}{@filter_align: justify}{@!filter_style: list}',
        'uk-child-width-small@m {@filter_position: top}{@!filter_align: justify}{@filter_style: list}',
        'uk-child-width-1-1@s {@filter_position: top}{@filter_align: justify}{@filter_style: list}',

        'uk-flex-{filter_align} {@filter_position: top}{@!filter_align: justify}',
    ],

    'uk-scrollspy-class' => in_array($props['animation'], [
        'none', 'parallax'
    ]) || !$props['item_animation'] ? false : (!empty($props['animation']) ? ['uk-animation-{0}' => $props['animation']] : true),

]);

$dropdown = $this->el('div', [

    'class' => [
        'uk-dropdown-nav {@filter_style: dropdown}',
    ],

    'uk-dropdown' => $this->expr([
        'mode: click;',
        'pos: bottom-left',
    ], $props) ?: true,

]);

$nav_dropdown = $this->el('ul', [

    'class' => [
        'uk-nav uk-dropdown-nav {@filter_style: dropdown}',

        'uk-column-divider {@filter_dropdown_grid_divider}{@filter_style: dropdown}',
        'uk-column-{filter_dropdown_grid_default} {@filter_style: dropdown}',
        'uk-column-{filter_dropdown_grid_small}@s {@filter_dropdown_grid_small}{@filter_style: dropdown}',
        'uk-column-{filter_dropdown_grid_medium}@m {@filter_dropdown_grid_medium}{@filter_style: dropdown}',
        'uk-column-{filter_dropdown_grid_large}@l {@filter_dropdown_grid_large}{@filter_style: dropdown}',
        'uk-column-{filter_dropdown_grid_xlarge}@xl {@filter_dropdown_grid_xlarge}{@filter_style: dropdown}',
    ],

]);

$nav_horizontal = [
    'uk-subnav {@filter_style: subnav.*}',
    'uk-{filter_style}  {@filter_style: subnav-.*}',
    'nav-horizontal',
];

$nav_vertical = [
    'uk-nav uk-nav-{0} [uk-text-left {@text_align}] {@filter_style: subnav.*}' => $props['filter_style_primary'] ? 'primary' : 'default',
    'uk-tab-{filter_position} {@filter_style: tab|list}',
    'uk-width-1-1 {@filter_style: subnav-divider} {@filter_position: left|right}',
    'uk-nav-divider {@filter_style: subnav-divider} {@filter_position: left|right}',

    'uk-child-width-1-1 {@filter_style: dropdown} {@filter_position: left|right}',
    'uk-width-auto {@filter_position: left|right}{@!filter_style: dropdown|subnav-divider}',
    'uk-child-width-expand[@{filter_grid_breakpoint}] {@filter_position: left|right}{@!filter_style: dropdown}',
    'nav-vertical',
];

$nav_attrs = $props['filter_position'] === 'top'
    ? ['class' => $nav_horizontal]
    : [
        'class' => $nav_vertical,
        'uk-toggle' => [
            "cls: {$this->expr(array_merge($nav_vertical, $nav_horizontal), $props)};",
            'mode: media;',
            'media: @{filter_grid_breakpoint};',
        ],
    ];

$grid = $this->el('div', [

    'id' => $props['nav_id'],

    'class' => [
        'fs-filter-grid',
        'uk-grid-small uk-grid-margin',
        'uk-grid-divider',
        'uk-margin[-{filter_margin}] {@filter_position: top}',
        'uk-grid-match {@filter_position: top}',
        'uk-flex-{filter_align: right|center}',
        'uk-section-default {@filter_position_sticky}{@filter_position: top}',
        'uk-grid-row-medium {@filter_position: left|right}{@!filter_style:subnav-divider}',
    ],

    'uk-grid' => true,

]);

$grid_left_cell = $this->el('div', [

    'class' => [
        'fs-filter-grid-left-cell',
        'uk-width-expand@m {@filter_position: top}{@search}',
        'uk-width-expand {@filter_position: top}{@!search}',
        'uk-width-1-1    {@!filter_position: top}',
        'uk-width-expand {@!search}{@filter_sorting}{@!filter_position: top}',
    ],

    'uk-grid' => $props['filter_position'] === 'top',

]);

$grid_left_cell_content = $this->el('div', [

    'class' => [
        'fs-filter-grid-left-cell-content',

        'uk-grid-{filter_groups_grid_gap}{@filter_groups_grid_gap}{@filter_groups}',
        'uk-grid-row-small {@filter_position: top} {@filter_groups}',
        'uk-grid-row-medium {@filter_position: left|right}{@!filter_style:subnav-divider}',
        'uk-grid-divider {@filter_nav_dividers}',

        'uk-child-width-1-1@m {@!filter_position: top}',
        'uk-child-width-auto {@filter_position: top}',
        'uk-child-width-expand@s {@filter_align: justify}{@filter_position: top}',

        'uk-flex-{filter_align: right|center}',
        'uk-grid-match {@filter_style:subnav-divider}',
    ],

    'uk-grid' => true,

]);

$grid_right_cell = $this->el('div', [

    'class' => [

        'fs-filter-grid-right-cell',

        'uk-width-1-4@l  {@search}{@filter_sorting}{@filter_position: top}', // search and sort cell TOP
        'uk-width-1-3@m  {@search}{@filter_sorting}{@filter_position: top}', // search and sort cell TOP

        'uk-width-1-5@l  {@search}{@!filter_sorting}{@filter_position: top}', // search and sort cell TOP
        'uk-width-1-4@m  {@search}{@!filter_sorting}{@filter_position: top}', // search and sort cell TOP

        'uk-width-1-1  {@!filter_position: top}', // search and sort cell LEFT RIGHT
        'uk-flex-first {@search}{@!filter_position: top}', // search and sort cell LEFT RIGHT

        'uk-width-1-6 {@!search}{@filter_sorting}{@filter_position: top}', // single sort cell TOP
        'uk-width-auto@s {@!search}{@filter_sorting}{@filter_position: top}', // single sort cell TOP

        'uk-width-1-1 {@!search}{@filter_sorting}{@!filter_position: top}', // single sort cell LEFT RIGHT

    ],

]);

$grid_right_cell_content = $this->el('div', [

    'class' => [
        'fs-filter-grid-right-cell-content',
        'uk-grid-small',
        'uk-grid-divider',
    ],

    'uk-grid' => true,

]);

?>

<?= $grid($props) ?>

<?= $grid_left_cell($props) ?>
<?= $grid_left_cell_content($props) ?>

<?php if ($props['filter_all'] && $props['filter_groups'] && $props['use_custom_fields']): ?>
    <?= $nav_all_container($props) ?>
    <?= $nav_all($props, $nav_attrs) ?>
    <li class="uk-active" uk-filter-control>

        <?php if ($props['filter_style'] === 'dropdown' && $props['filter_all_icon']): ?>
        <a href class="fs-filter-label-all" <?= $filter_horizontal ? 'style="justify-content: flex-start;"' : false ?>>
				<span class="fs-filter-label-inner">
					<i uk-icon='<?= $props['filter_all_icon'] ?>' data-fsicon='<?= $props['filter_all_icon'] ?>'
                       class='fs-filter-label-icon uk-margin-small-right'></i>
				</span>
            <?php else: ?>
            <a href
               class="fs-filter-label-all" <?= $filter_horizontal ? 'style="justify-content: flex-start;"' : false ?>>
                <?php endif ?>
                <?= $this->trans($props['filter_all_label'] ?: 'All') ?>
            </a>
    </li>
    <?= $nav_all->end() ?>
    <?= $nav_all_container->end() ?>
<?php endif ?>

<div class="fs-grid-filter-0 fs-grid-filter-tags <?= $props['filter_style'] === 'dropdown' ? 'fs-js-dropdown' : false ?>">

    <?= $nav($props, $nav_attrs) ?>

    <?php if ($props['filter_style'] === 'dropdown'): ?>
    <li class="fs-filter-state">

        <a href="#"
           class="fs-filter-label <?= $filter_horizontal ? 'uk-width-1-1' : false ?>"
           uk-icon="triangle-down" <?= $filter_horizontal ? 'style="justify-content: space-between;"' : false ?>>
			<span class="fs-filter-label-inner">
			<?php if ($props['filter_tags_icon']): ?>
                <i uk-icon='<?= $props['filter_tags_icon'] ?>'
                   data-fsicon='<?= $props['filter_tags_icon'] ?>'
                   class='fs-filter-label-icon uk-margin-small-right'></i>
            <?php endif ?>
				<span data-label='<?= $props['filter_tags_label'] ?>' class="fs-filter-label-text">
					<?= $props['filter_tags_label'] ?>
				</span>
			</span>
        </a>

        <?= $dropdown($props) ?>
        <?= $nav_dropdown($props) ?>
        <?php endif ?>

        <?php if ($props['filter_all'] && (!$props['filter_groups'] || !$props['use_custom_fields'])): ?>
    <li class="uk-active" uk-filter-control>
        <a href class="fs-filter-label-all">
            <?= $this->trans($props['filter_all_label'] ?: 'All') ?>
        </a>
    </li>
<?php endif ?>

    <?php if ($props['filter_group_all'] && $props['filter_groups'] && $props['use_custom_fields']): ?>
        <li uk-filter-control="group: tags">
            <a class="fs-filter-reset <?= $props['filter_style'] === 'dropdown' ? "uk-padding-remove-top" : false ?> <?= !$props['filter_all'] ? "uk-active" : false ?>"><?= $props['filter_all_label'] . " " . $props['filter_tags_label'] ?></a>
        </li>
    <?php endif ?>

    <?php foreach ($tags as $tag => $name): ?>
        <li <?= $this->attrs([
            'class' => ['uk-active' => $tag === array_key_first($tags) && !$props['filter_all'] && !$props['filter_group_all']],
            'uk-filter-control' => $props['filter_groups_mode'] !== 'match' ? json_encode([
                'filter' => '[data-tag~="' . str_replace('"', '\"', $tag) . '"]', 'group' => 'tags'
            ]) : json_encode(['filter' => '[data-tag~="' . str_replace('"', '\"', $tag) . '"]']),
        ]) ?>>

            <?php $filter_link = ""; ?>
            <?php if ($props['filter'] && $props['filter_url_hash']) : ?>
                <?php if (function_exists('mb_strtolower')) : ?>
                    <?php $filter_link = mb_strtolower($tag, 'UTF-8'); ?>
                <?php else : ?>
                    <?php $filter_link = strtolower($tag); ?>
                <?php endif; ?>
                <?php $filter_link = "#" . htmlspecialchars(preg_replace('/[^a-zA-Z0-9_\p{L}\p{M}\-]/u', '', $filter_link),
                        ENT_QUOTES); ?>
            <?php else : ?>
                <?php $filter_link = "#"; ?>
            <?php endif; ?>

            <a href="<?= $filter_link ?>"
               class="<?= $props['filter_style'] === 'dropdown' ? "uk-padding-remove-top" : false ?>">
                <?= $name ?>
            </a>

        </li>
    <?php endforeach ?>

    <?php if ($props['filter_style'] === 'dropdown'): ?>
        <?= $nav_dropdown->end() ?>
        <?= $dropdown->end() ?>
        </li>
    <?php endif ?>

    <?= $nav->end() ?>

</div>

<?php if ($props['use_custom_fields'] && $props['filter_groups']): ?>
    <?= $this->render("$__dir/template-filter-custom", compact('props', 'nav', 'nav_attrs', 'dropdown', 'nav_dropdown')) ?>
<?php endif ?>

<?= $grid_left_cell_content->end() ?>
<?= $grid_left_cell->end() ?>

<?php if ($props['search'] || ($props['filter'] && $props['filter_sorting'])): ?>
    <?= $grid_right_cell($props) ?>
    <?= $grid_right_cell_content($props) ?>

    <?php if ($props['search']): ?>
        <?= $this->render("$__dir/template-search", compact('props')) ?>
    <?php endif ?>

    <?php if ($props['filter'] && $props['filter_sorting']): ?>
        <?= $this->render("$__dir/template-filter-sorting", compact('props')) ?>
    <?php endif ?>

    <?= $grid_right_cell_content->end() ?>
    <?= $grid_right_cell->end() ?>
<?php endif ?>

<?= $grid->end() ?>

<?php if ($props['filter_url_hash']): ?>
    <?php include __DIR__ . "/../assets/js/fs-grid-filter-url-hash.js.php"; ?>
<?php endif ?>

<?php if ($props['filter_style'] === 'dropdown'): ?>
    <?php include __DIR__ . "/../assets/js/fs-grid-filter-dropdowns.js.php"; ?>
<?php endif ?>

<?php include __DIR__ . "/../assets/js/fs-grid-filter-empty.js.php"; ?>

<script>
    UIkit.util.ready(() => {
        const el = document.getElementById(<?= json_encode($props['filter_id']) ?>);
        if (!el) return;

        const filter = el.closest('div.fs-grid[uk-filter]');
        if (!filter) return;

        // Remove transform immediately in case UIkit set it on init
        el.style.removeProperty('transform');

        // Listen for the afterFilter event
        UIkit.util.on(filter, 'afterFilter', () => {
            el.style.removeProperty('transform');
        });
    });
</script>