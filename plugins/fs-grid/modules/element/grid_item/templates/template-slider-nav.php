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

$nav = $this->el('ul', [
    'class' => [
        'el-nav uk-slider-nav',
        'uk-{nav}',
        'uk-flex-{nav_align}',
    ],
    'uk-margin' => true,
]);

$nav_container = $this->el('div', [
    'class' => [
        'uk-margin[-{nav_margin}]-top',
        'uk-visible@{nav_breakpoint}',
        'uk-{nav_color}',
    ],
    'aria-label' => $this->expr(['{nav_aria_label} {@nav_aria_label}'], $element) ?: false,
]);

echo $element['nav_color'] ? $nav_container($element, $nav($element, '')) : $nav($element, $nav_container->attrs, '');