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

namespace YOOtheme;

defined('_JEXEC') or defined('ABSPATH') or die();

// Ensure these variables exist
/** @var $element */
/** @var $props */
/** @var $link */
/** @var $__dir */

if (!$props['image'] && !$props['icon']) {
    return;
}

//Needs to align image/icon
$image_container = $this->el('div', [
    'class' => [
        'el-image-container',

        //Own field align including general settings breakpoint
        'uk-text-{image_align}[@{text_align_breakpoint} [uk-text-{text_align_fallback}]{@!text_align: justify}] {@image_align} {@!image_align_ignore_general}',

        //General align including general settings breakpoint
        'uk-text-{text_align}[@{text_align_breakpoint} [uk-text-{text_align_fallback}]{@!text_align: justify}]{@!image_align}',

        //Own field align ignoring general settings breakpoint
        'uk-text-{image_align} {@image_align}{@image_align_ignore_general}',
    ],
]);

// Image
if ($props['image']) {
    // If a link is not set, use the default image for the lightbox
    if (!empty($element['show_lightbox'])) {
        $props['link'] = $props['image'];
    }

    // Image
    $image = $this->el('image', [
        'class' => [
            'el-image',
            'uk-preserve-width',
            'uk-border-{image_border}',
            'uk-box-shadow-{image_box_shadow}',
            'uk-text-{image_svg_color} {@image_svg_inline}' => $this->isImage($props['image']) === 'svg',
        ],

        'src' => $props['image'],
        'alt' => $props['image_alt'],
        'title' => $props['image_title'],
        'aria-label' => $props['image_aria_label'],
        'width' => $element['image_width'],
        'height' => $element['image_height'],
        'focal_point' => $props['image_focal_point'],
        'uk-svg' => $element['image_svg_inline'],

        //Custom Image Attributes Set
        'loading' => $element['image_attr_loading'] ? false : null,
        'fetchpriority' => $element['image_attr_fetchpriority'] ? 'high' : null,
        'thumbnail' => !$element['image_thumbnails_disable'],
        'decoding' => $element['image_attr_decoding'] ? 'async' : null,
    ]);

    //wrapping image in the container
    $image = $image_container($element, $image($element));

    if ($element['image_link']) {
        // Lightbox
        if ($element['show_lightbox']) {
            // Link
            $link = include __DIR__ . "/link.php";

            if ($type = $this->isImage($props['link'])) {
                if ($type !== 'svg' && ($element['lightbox_image_width'] || $element['lightbox_image_height'])) {
                    $thumbnail = [
                        $element['lightbox_image_width'], $element['lightbox_image_height'],
                        $element['lightbox_image_orientation']
                    ];
                    if (!empty($props['lightbox_image_focal_point'])) {
                        [$y, $x] = explode('-', $props['lightbox_image_focal_point']);
                        $thumbnail += [3 => $x, 4 => $y];
                    }

                    $props['link'] = "{$props['link']}#thumbnail=" . implode(',', $thumbnail);
                }

                $link->attr([
                    'href' => Url::to($props['link']),
                    'data-alt' => $props['image_alt'],
                    'data-type' => 'image',
                ]);
            } elseif ($this->isVideo($props['link'])) {
                $link->attr('data-type', 'video');
            } elseif (!$this->iframeVideo($props['link'])) {
                $link->attr('data-type', 'iframe');
            } else {
                $link->attr('data-type', true);
            }

            $link->attr([
                'class' => [
                    'fs-lightbox-link',
                    'fs-table-lightbox-link',
                ],
                'title' => $this->expr(['{image_title} {@image_title}'], $props) ?: false,
                'aria-label' => $this->expr(['{image_aria_label} {@image_aria_label}'], $props) ?: 'Lightbox Element',
            ]);

            // Caption
            $caption = '';

            if ($props['title']) {
                $caption .= "<h4 class='uk-margin-remove'>{$props['title']}</h4>";
            }

            if ($props['description']) {
                $caption .= $props['description'];
            }

            if ($caption) {
                $link->attr('data-caption', $caption);
            }

            $image = $link($element, $image);
        } elseif ($link && $element['show_link'] && $props['link']) {
            $image = $link($element, $image);
        }
    }

    echo $image;
// Icon
} elseif ($props['icon']) {
    $element['icon_color'] = $props['icon_color'] ?: $element['icon_color'];
    $icon = $this->el('span', [
        'class' => [
            'el-image',
            'uk-text-{icon_color}',
        ],
        'uk-icon' => [
            'icon: {0};' => $props['icon'],
            'width: {0};' => $element['icon_width'],
        ]
    ]);

    // Wrapping icon in the container
    $icon = $image_container($element, $icon($element));

    if ($link && $element['image_link'] && $element['show_link']) {
        $icon = $link($element, $icon);
    }

    echo $icon;
}