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

defined('_JEXEC') or defined('ABSPATH') or die();

// Ensure these variables exist
/** @var $element */
/** @var $props */
/** @var $field */
/** @var $text_fields */

//Overrides
$props['link_target'] = $props['link_item_target'] ?: $element['link_target'];
$props['link_aria_label'] = $props['link_aria_label'] ?: $element['link_aria_label'];
$props['is_lightbox'] = $element['show_lightbox'] && $field === 'image';

//Custom links for the text 1-20 fields
$props['field_has_link'] = false;
if (in_array($field, $text_fields) && $element["{$field}_link"] && !empty($props["{$field}_link"])) {
    $props['field_link'] = $props["{$field}_link"];
    $props["{$field}_link_target"] = $props["{$field}_link_target"] ?: $element['link_target'];
    $props['field_has_link'] = true;
} else {
    $props['field_link'] = $props['link'];
}

// Woo URL parser
$props['link_woo_ok'] = false;
if ($props['is_lightbox'] === false && $props['field_link'] && $props['link_woo'] && $props['link_woo_sku']) {
    if (empty($props["{$field}_link"])) {
        $props['link_woo_id'] = '0';
        $props['link_woo_quantity'] = $props['link_woo_quantity'] ?: '1';
        $url_params = array();

        if (parse_url($props['field_link'], PHP_URL_QUERY)) {
            parse_str(parse_url($props['field_link'])['query'], $url_params);
            if (!empty($url_params['add-to-cart']) && is_numeric($url_params['add-to-cart'])) {
                $props['link_woo_id'] = $url_params['add-to-cart'];
                $props['link_woo_ok'] = true;
            }
        }
    }
}

$link = $props['field_link'] ? $this->el('a', ['href' => $props['field_link']]) : null;

if ($link) {
    $link->attr([
        'class' => [
            //WooCommerce
            $this->expr(['fs-add2cart button product_type_simple add_to_cart_button ajax_add_to_cart {@link_woo_ok}{@!is_lightbox}'],
                $props) ?: false,

            //Custom Fields Links
            $this->expr(["el-field-link fs-table-$field-link {@field_has_link}"], $props) ?: false,

            //Link Custom Classes
            $this->expr(['{link_class} {@link_class}{@!is_lightbox}{@!field_has_link}{@link_advanced}'], $props) ?: false,
        ],

        'target' => $this->expr([
            '_blank {@link_target}{@!link_toggle}{@!is_lightbox}{@!field_has_link}',
            "_blank {@{$field}_link_target}{@!{$field}_link_toggle}{@field_has_link}",
        ], $props) ?: false,

        'uk-scroll' => str_starts_with($props['field_link'],
                '#') && ((empty($props['link_toggle']) && !$props['field_has_link']) || (empty($props["{$field}_link_toggle"]) && $props['field_has_link'])),
        'uk-toggle' => str_starts_with($props['field_link'],
                '#') && ((!empty($props['link_toggle']) && !$props['field_has_link'] && !$props['link_woo_ok']) || (!empty($props["{$field}_link_toggle"]) && $props['field_has_link'])),

        'title' => $this->expr([
            '{link_title} {@link_title}{@!is_lightbox}{@!field_has_link}{@link_advanced}',
        ], $props) ?: false,

        'aria-label' => $this->expr([
            '{link_aria_label} {@link_aria_label}{@!is_lightbox}{@!field_has_link}',
        ], $props) ?: false,

        $this->expr([
            '{link_attrs_tag} {@link_attrs_tag}{@!is_lightbox}{@!field_has_link}{@link_advanced}'
        ], $props) ?: false,

        'rel' => $this->expr([
            'nofollow {@link_nofollow}{@!link_toggle}{@!is_lightbox}{@!link_woo_ok}{@!field_has_link} {@link_advanced}',
            'nofollow {@link_woo_ok}{@!is_lightbox}',
            'noopener {@link_noopener}{@!link_toggle}{@!is_lightbox}{@!field_has_link}{@link_advanced}',
            'noreferrer {@link_noreferrer}{@!link_toggle}{@!is_lightbox}{@!field_has_link}{@link_advanced}',
            'prefetch {@link_prefetch}{@!is_lightbox}{@!field_has_link}{@link_advanced}',
        ], $props) ?: false,

        //WooCommerce
        'data-quantity' => $this->expr(['{link_woo_quantity} {@link_woo_ok}{@!is_lightbox}'], $props) ?: false,
        'data-product_id' => $this->expr(['{link_woo_id} {@link_woo_ok}{@!is_lightbox}'], $props) ?: false,
        'data-product_sku' => $this->expr(['{link_woo_sku} {@link_woo_ok}{@!is_lightbox}'], $props) ?: false,
    ]);
}

if (!in_array($field, ['image', 'link'])) {
    if ($link && $props[$field] && $element["{$field}_link"] && $element['show_link']) {
        $props[$field] = $link($element, [
            'class' => [
                "[uk-link-{{$field}_hover_style}]",
                "[uk-display-block {@{$field}_style:label}]",
            ],
        ], $this->striptags($props[$field]));
    }
}

return $link;