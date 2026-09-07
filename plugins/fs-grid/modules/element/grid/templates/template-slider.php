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

// Ensure these variables exist
/** @var $props */
/** @var $__dir */

// No direct access to this file
defined('_JEXEC') or defined('ABSPATH') or die();

//Slider
$slider = $this->el('div', [
    'class' => [
        'uk-slider-container {@!slidenav: outside}',
        'uk-slider-container-offset {@panel_style} {@panel_card_offset} {@!slidenav: outside}',
    ],
    'uk-slider' => $this->expr([
        'sets: {slider_sets};',
        'center: {slider_center};',
        'finite: {slider_finite};',
        'velocity: {slider_velocity};',
        'autoplay: {slider_autoplay}; [pauseOnHover: false; {@!slider_autoplay_pause}] [autoplayInterval: {slider_autoplay_interval}000;]',
    ], $props) ?: true,
]);

// Slider Container
$slider_container = $this->el('div', [
    'class' => [
        'uk-position-relative',
        'uk-visible-toggle {@slidenav} {@slidenav_hover}',
    ],
    'tabindex' => ['-1 {@slidenav} {@slidenav_hover}'],
]);

$slider_inner = $this->el('div', [
    'class' => [
        'uk-slider-container {@slidenav: outside}',
        'uk-slider-container-offset {@panel_style} {@panel_card_offset} {@slidenav: outside}',
    ],
]);

?>

<?= $slider($props) ?>
<?= $slider_container($props) ?>
<?= $props['slidenav'] === 'outside' ? $slider_inner($props,
    $this->render("$__dir/template-content")) : $this->render("$__dir/template-content") ?>
<?= $props['slidenav'] ? $this->render("$__dir/template-slider-slidenav") : '' ?>
<?= $slider_container->end() ?>
<?= $props['nav'] ? $this->render("$__dir/template-slider-nav") : '' ?>
<?= $slider->end() ?>