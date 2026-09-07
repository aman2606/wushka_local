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

// Gallery
$nav = $this->el('ul', [
    'class' => [
        'el-nav',
        'uk-{filter_style} {@filter_style: tab}',
        'uk-margin[-{filter_margin}] {@filter_position: top}',
    ],
    'uk-scrollspy-class' => in_array($props['animation'],
        ['none', 'parallax']) || !$props['item_animation'] ? false : (!empty($props['animation'])
        ? ['uk-animation-{0}' => $props['animation']]
        : true),
]);

$nav_horizontal = [
    'uk-subnav {@filter_style: subnav.*}',
    'uk-{filter_style}  {@filter_style: subnav-.*}',
    'uk-flex-{filter_align: right|center}',
    'uk-child-width-expand {@filter_align: justify}',
];

$nav_vertical = [
    'uk-nav uk-nav-{0} [uk-text-left {@text_align}] {@filter_style: subnav.*}' => $props['filter_style_primary'] ? 'primary' : 'default',
    'uk-tab-{filter_position} {@filter_style: tab}',
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

?>

<?= $nav($props, $nav_attrs) ?>

<?php if ($props['filter_all']): ?>
    <li class="uk-active" uk-filter-control><a href><?= $this->trans($props['filter_all_label'] ?: 'All') ?></a></li>
<?php endif ?>

<?php foreach ($tags as $tag => $name): ?>
    <?php $selector = htmlspecialchars("[data-tag~='" . str_replace("'", "\'", $tag) . "']", ENT_QUOTES, null, false) ?>
    <li <?= $tag === key($tags) && !$props['filter_all'] ? 'class="uk-active" ' : '' ?>uk-filter-control="<?= $selector ?>">
        <a href="#"><?= $name ?></a>
    </li>
<?php endforeach ?>

<?= $nav->end() ?>