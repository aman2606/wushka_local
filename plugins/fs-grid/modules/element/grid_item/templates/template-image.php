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

//PHP 8.1 strip_tags() check if not NULL
!empty($props['image_attrs_tag']) ? $props['image_attrs_tag'] = strip_tags($props['image_attrs_tag']) : false;

// Image
if ($props['image']) {
    $image = $this->el('image', [
        'class' => [
            'el-image',
            'uk-border-{image_border} {@!image_transition}' => !$element['panel_style'] || ($element['panel_style'] && (!$element['panel_card_image'] || $element['image_align'] == 'between')),
            'uk-box-shadow-{image_box_shadow} {@!panel_style} {@!image_transition}',
            'uk-box-shadow-hover-{image_hover_box_shadow} {@!panel_style} {@!image_transition}' => $props['link'] && ($element['image_link'] || $element['panel_link']),
            'uk-transition-{image_transition} uk-transition-opaque' => $props['link'] && ($element['image_link'] || $element['panel_link']),

            'uk-text-{image_svg_color} {@image_svg_inline}' => $this->isImage($props['image']) === 'svg',
            'uk-margin[-{image_margin}]-top {@!image_margin: remove} {@!image_box_decoration} {@!image_transition}' => $element['image_align'] === 'between' || ($element['image_align'] === 'bottom' && !($element['panel_style'] && $element['panel_card_image'])) && !$element['grid_stack'],
            '{image_visibility}{@image_visibility}',

            'uk-margin[-{image_margin}]-top {@image_align: top}{@grid_stack}' => $props['item_reverse'] && !$element['panel_card_image'],
            'uk-margin[-{image_margin}]-bottom uk-margin-remove-top {@image_align: bottom}{@grid_stack}' => $props['item_reverse'] && !$element['panel_card_image'],
        ],
        'src' => $props['image'],
        'alt' => $props['image_alt'],
        'title' => $this->expr(['{image_title} {@image_title}'], $props),
        'width' => $element['image_width'],
        'height' => $element['image_height'],
        'focal_point' => $props['image_focal_point'],
        'uk-svg' => $element['image_svg_inline'],
        'uk-cover' => $element['panel_style'] && $element['panel_card_image'] && in_array($element['image_align'],
                ['left', 'right']),

        //Custom Image Attributes Set
        'loading' => $element['image_attr_loading'] ? false : null,
        'fetchpriority' => $element['image_attr_fetchpriority'] ? 'high' : null,
        'thumbnail' => $element['image_thumbnails_disable'] ? false : true,
        'decoding' => $element['image_attr_decoding'] ? 'async' : null,

        $props['image_attrs_tag'] => $props['image_attrs_tag'],

    ]);
    echo $image($element, []);

    // Placeholder image if card and layout left or right
    if ($image->attrs['uk-cover']) {
        echo $image($element, [
            'class' => ['uk-invisible'],
            'uk-cover' => false,
        ]);
    }
// Icon
} elseif ($props['icon']) {
    $icon = $this->el('span', [
        'class' => [
            'el-image',
            'uk-text-{icon_color}',
            'uk-margin[-{image_margin}]-top {@!image_margin: remove}' => $element['image_align'] === 'between' || ($element['image_align'] === 'bottom' && !($element['panel_style'] && $element['panel_card_image'])),
        ],
        'uk-icon' => [
            'icon: {0};' => $props['icon'],
            'width: {icon_width};',
            'height: {icon_width};',
        ],
    ]);
    echo $icon($element, '');
}