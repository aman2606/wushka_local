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

namespace YOOtheme;

defined('_JEXEC') or defined('ABSPATH') or die();

// Ensure these variables exist
/** @var $element */
/** @var $props */
/** @var $el */

//PHP 8.1 strip_tags() check if not NULL
!empty($props['link_class']) ? $props['link_class'] = strip_tags($props['link_class']) : false;
!empty($props['link_title']) ? $props['link_title'] = strip_tags($props['link_title']) : false;
!empty($props['link_attrs_tag']) ? $props['link_attrs_tag'] = strip_tags($props['link_attrs_tag']) : false;

//aria-label override
$props['link_aria_label'] = $props['link_aria_label'] ?: $element['link_aria_label'];

//Overrides
$props['link_target'] = $props['link_item_target'] ?: $element['link_target'];

// Woo URL parser
$props['link_woo_ok'] = false;
if ($props['link'] && $props['link_woo'] && $props['link_woo_sku']) {
    $props['link_woo_id'] = '0';
    $props['link_woo_quantity'] = $props['link_woo_quantity'] ?: '1';
    $url_params = array();

    if (parse_url($props['link'], PHP_URL_QUERY)) {
        parse_str(parse_url($props['link'])['query'], $url_params);
        if (!empty($url_params['add-to-cart']) && is_numeric($url_params['add-to-cart'])) {
            $props['link_woo_id'] = $url_params['add-to-cart'];
            $props['link_woo_ok'] = true;
        }
    }
}

$link = $link_panel = $props['link'] ? $this->el('a', [
    'href' => $props['link'],
]) : null;

$link_lightbox = $props['link'] ? $this->el('a', [
    'href' => $props['link'],
]) : null;

$link_panel_custom = $props['link_panel_custom'] ? $this->el('a', [
    'href' => $props['link_panel_custom'],
]) : null;

$props['lightbox_auto'] = false;
if ($props['image'] && $element['lightbox'] && ($this->isImage($props['link']) || $this->isVideo($props['link']) || $this->iframeVideo($props['link']))) {
    $props['lightbox_auto'] = true;
}

if ($link) {
    $link->attr([
        'rel' => $this->expr([
            'nofollow {@link_woo_ok}{@!link_item_toggle}',
            'nofollow {@link_item_nofollow}{@link_advanced}{@!link_woo_ok}{@!link_item_toggle}',
            'sponsored {@link_item_sponsored}{@link_advanced}{@!link_item_toggle}',
            'ugc {@link_item_ugc}{@link_advanced}{@!link_item_toggle}',
            'noopener {@link_item_noopener}{@link_advanced}{@!link_item_toggle}',
            'noreferrer {@link_item_noreferrer}{@link_advanced}{@!link_item_toggle}',
            'prefetch {@link_item_prefetch}{@link_advanced}',
        ], $props) ?: false,
        'class' => [
            'uk-position-relative uk-position-z-index',

            //WooCommerce
            $this->expr(['fs-add2cart button product_type_simple add_to_cart_button ajax_add_to_cart {@link_woo_ok}'],
                $props) ?: false,

            //Link Custom Classes
            $this->expr(['{link_class} {@link_class}{@link_advanced}'], $props) ?: false,

        ],

        'target' => $this->expr(['_blank {@link_target}{@!lightbox_auto}{@!link_woo_ok}{@!link_item_toggle}'], $props) ?: false,
        'uk-scroll' => strpos($props['link'], '#') === 0 && !$props['link_item_toggle'],
        'uk-toggle' => strpos($props['link'], '#') === 0 && $props['link_item_toggle'] && !$props['link_woo_ok'],

        //Link Custom Attributes
        'title' => $this->expr(['{link_title} {@link_title}{@link_advanced}'], $props) ?: false,
        'aria-label' => $this->expr(['{link_aria_label} {@link_aria_label}'], $props) ?: false,
        $this->expr(['{link_attrs_tag} {@link_attrs_tag}{@link_advanced}'], $props) ?: false,

        //WooCommerce
        'data-quantity' => $this->expr(['{link_woo_quantity} {@link_woo_ok}'], $props) ?: false,
        'data-product_id' => $this->expr(['{link_woo_id} {@link_woo_ok}'], $props) ?: false,
        'data-product_sku' => $this->expr(['{link_woo_sku} {@link_woo_ok}'], $props) ?: false,
    ]);
}

// Lightbox
if ($link_lightbox && $element['lightbox']) {
    if ($type = $this->isImage($props['link'])) {
        if ($type !== 'svg' && ($element['lightbox_image_width'] || $element['lightbox_image_height'])) {
            $thumbnail = [
                $element['lightbox_image_width'], $element['lightbox_image_height'], $element['lightbox_image_orientation']
            ];
            if (!empty($props['lightbox_image_focal_point'])) {
                [$y, $x] = explode('-', $props['lightbox_image_focal_point']);
                $thumbnail += [3 => $x, 4 => $y];
            }
            $props['link'] = "{$props['link']}#thumbnail=" . implode(',', $thumbnail);
        }
        $link_lightbox->attr([
            'href' => Url::to($props['link']),
            'data-alt' => $props['image_alt'],
            'data-type' => 'image',
        ]);
    } elseif ($this->isVideo($props['link'])) {
        $link_lightbox->attr('data-type', 'video');
    } elseif (!$this->iframeVideo($props['link'])) {
        $link_lightbox->attr('data-type', 'iframe');
    } else {
        $link_lightbox->attr('data-type', true);
    }

    $link_lightbox->attr([
        'class' => [
            'fs-lightbox-link',
            'fs-grid-lightbox-link',
            'uk-position-relative uk-position-z-index',
        ],
        'data-alt' => $this->expr(['{image_alt} {@image_alt}' => $element['link_image_lightbox']], $props) ?: false,
        'title' => $this->expr(['{image_title} {@image_title}'], $props) ?: false,
        'aria-label' => $this->expr(['{image_title} {@image_title}'], $props) ?: 'Lightbox Element',
    ]);

    // Caption
    $caption = '';

    if ($props['title'] && $element['title_display'] !== 'item') {
        $caption .= "<h4 class='uk-margin-remove'>{$props['title']}</h4>";
        if ($element['title_display'] === 'lightbox') {
            $props['title'] = '';
        }
    }

    if ($props['content'] && $element['content_display'] !== 'item') {
        $caption .= $props['content'];
        if ($element['content_display'] === 'lightbox') {
            $props['content'] = '';
        }
    }

    if ($caption) {
        $link_lightbox->attr('data-caption', $caption);
    }

    //Convert links to Lightbox
    if ($props['lightbox_auto']) {
        $link = $link_lightbox;

        //Add lightbox to the images
        if (!$element['panel_link']) {
            $props['image'] = $link($element, [
                'class' => [
                    'uk-display-block' => $element['panel_style'] && $element['has_panel_card_image'] && in_array($element['image_align'],
                            ['left', 'right']),
                ]
            ], $props['image']);
        }
    }
}

if ($link_panel_custom) {
    $link_panel_custom->attr([

        'rel' => $this->expr([
            'nofollow {@link_panel_nofollow}{@link_panel_advanced}{@!link_panel_toggle}',
            'sponsored {@link_panel_sponsored}{@link_panel_advanced}{@!link_panel_toggle}',
            'ugc {@link_panel_ugc}{@link_panel_advanced}{@!link_panel_toggle}',
            'noopener {@link_panel_noopener}{@link_panel_advanced}{@!link_panel_toggle}',
            'noreferrer {@link_panel_noreferrer}{@link_panel_advanced}{@!link_panel_toggle}',
            'prefetch {@link_panel_prefetch}{@link_panel_advanced}',
        ], $props) ?: false,

        'target' => $this->expr(['_blank {@link_panel_custom_item_target}{@!link_panel_toggle}'], $props) ?: false,
        'uk-scroll' => strpos($props['link_panel_custom'], '#') === 0 && !$props['link_panel_toggle'],
        'uk-toggle' => strpos($props['link_panel_custom'], '#') === 0 && $props['link_panel_toggle'],

        'title' => $this->expr(['{link_panel_title} {@link_panel_title}{@link_panel_advanced}'], $props) ?: false,
        'aria-label' => $this->expr([
            '{link_panel_custom_aria_label} {@link_panel_custom_aria_label}{@link_panel_advanced}',

        ], $props) ?: false,
    ]);
}

// Panel Link
if ($element['panel_link'] && ($link_panel || $link_panel_custom)) {
    if ($link_panel_custom) {
        $link_panel = $link_panel_custom;
    } elseif ($link_panel && $props['lightbox_auto']) {
        $link_panel = $link_lightbox;
    } else {
        $link_panel = $link;
    }

    $el->attr($link_panel->attrs + [
            'class' => [
                'uk-link-toggle',
                // Only if "uk-flex" is not already set in "template.php" to let images cover the card height if the cards have different heights
                'uk-display-block' => !($element['panel_style'] && $element['has_panel_card_image'] && in_array($element['image_align'],
                        ['left', 'right'])),
            ],
        ]);

    $props['title'] = $this->striptags($props['title']);
    $props['meta'] = $this->striptags($props['meta']);
    $props['content'] = $this->striptags($props['content']);

    if ($props['title'] && $element['title_hover_style'] !== 'reset') {
        $props['title'] = $this->el('span', [
            'class' => [
                'uk-link-{title_hover_style: heading}',
                'uk-link {!title_hover_style}',
            ],
        ], $props['title'])->render($element);
    }
}

//Link Title
if ($link && $props['title'] && $element['title_link'] && !$element['panel_link']) {
    $props['title'] = $link($element, [
        'class' => [
            'uk-link-{title_hover_style}',
        ],
    ], $this->striptags($props['title']));
}

//Link Image
if ($link && $props['image'] && $element['image_link']) {
    //Lightbox Image
    if (!$props['lightbox_auto']) {
        if ($element['lightbox'] && $element['link_image_lightbox']) {
            $props['image'] = $link_lightbox($element, ['href' => $props['image-src']], $props['image']);
        } elseif (!$element['panel_link'] && !$element['link_image_lightbox']) {
            $props['image'] = $link($element, [
                'class' => [
                    'uk-display-block' => $element['panel_style'] && $element['has_panel_card_image'] && in_array($element['image_align'],
                            ['left', 'right']),
                ]
            ], $props['image']);
        }
    } elseif ($link_panel_custom) {
        if ($element['lightbox'] && $element['link_image_lightbox']) {
            $props['image'] = $link_lightbox($element, ['href' => $props['image-src']], $props['image']);
        }
    }
}

if ($link && ($props['link_text'] || $element['link_text'])) {
    if ($element['panel_link'] && !$props['link_panel_custom']) {
        $link = $this->el('div');
    }

    $link->attr([
        'class' => [
            'el-link',
            'uk-{link_style: link-(muted|text)}',
            'uk-button uk-button-{!link_style: |link-muted|link-text} [uk-button-{link_size}] [uk-width-1-1 {@link_fullwidth}]',
            // Keep link style if the panel link
            'uk-link {@link_style:} {@panel_link}',
            'uk-text-muted {@link_style: link-muted} {@panel_link}',
            'fs-search-mark {@search}{@search_link}{@search_highlight}',
        ],
    ]);
}

return $link;