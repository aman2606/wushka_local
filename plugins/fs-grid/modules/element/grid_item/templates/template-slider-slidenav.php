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

$slidenav = $this->el('a', [
    'class' => [
        'el-slidenav',
        'uk-slidenav-large {@slidenav_large}',
        'uk-position-{slidenav_margin} {@slidenav_grid: default|outside}',
    ],
    'href' => '#', // WordPress Preview reloads if 'href' is empty
]);

$attrs_slidenav_next = [
    'class' => [
        'uk-position-center-right {@slidenav_grid: default}',
        'uk-position-center-right-out {@slidenav_grid: outside}',
    ],
    'uk-slidenav-next' => true,
    'uk-slider-item' => 'next',
    'uk-toggle' => [
        'cls: uk-position-center-right-out uk-position-center-right; mode: media; media: @{slidenav_outside_breakpoint} {@slidenav_grid: outside}',
    ],
    'aria-label' => $this->expr(['{slidenav_aria_label_next} {@slidenav_aria_label_next}'], $element) ?: false,
];

$attrs_slidenav_previous = [
    'class' => [
        'uk-position-center-left {@slidenav_grid: default}',
        'uk-position-center-left-out {@slidenav_grid: outside}',
    ],
    'uk-slidenav-previous' => true,
    'uk-slider-item' => 'previous',
    'uk-toggle' => [
        'cls: uk-position-center-left-out uk-position-center-left; mode: media; media: @{slidenav_outside_breakpoint} {@slidenav_grid: outside}',
    ],
    'aria-label' => $this->expr(['{slidenav_aria_label_prev} {@slidenav_aria_label_prev}'], $element) ?: false,
];

$slidenav_container = $this->el('div', [
    'class' => [
        'uk-visible@{slidenav_breakpoint}',
        'uk-hidden-hover uk-hidden-touch {@slidenav_hover}',
        'uk-slidenav-container uk-position-{!slidenav_grid: default|outside} [uk-position-{slidenav_margin}]',
    ],
]);

if ($element['slidenav_grid'] === 'outside' && ($element['slidenav_color'] != $element['slidenav_outside_color'])) {
    $slidenav_container->attr([
        'class' => [
            'js-color-state uk-{slidenav_outside_color} {@!slidenav_color}',
            'js-color-state {@!slidenav_outside_color}',
        ],
        'uk-toggle' => [
            !$element['slidenav_color'] ?
                'cls: js-color-state uk-{slidenav_outside_color}; mode: media; media: @{slidenav_outside_breakpoint}' :
                (!$element['slidenav_outside_color'] ?
                    'cls: js-color-state uk-{slidenav_color}; mode: media; media: @{slidenav_outside_breakpoint}' :
                    'cls: uk-{slidenav_outside_color} uk-{slidenav_color}; mode: media; media: @{slidenav_outside_breakpoint}'),
        ],
    ]);
} else {
    $slidenav_container->attr('class', ['uk-{slidenav_color}']);
}

?>

<?= $slidenav_container($element) ?>
<?= $slidenav($element, $attrs_slidenav_previous, '') ?>
<?= $slidenav($element, $attrs_slidenav_next, '') ?>
<?= $slidenav_container->end() ?>