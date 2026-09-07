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
/** @var $grid_target */

$g = $grid_target;

$props["grid_{$g}_custom_image_grid_breakpoint"] === 'inherit' ? $props["grid_{$g}_custom_image_grid_breakpoint"] = '' : false;

//Image Settings Overriding
$image_settings = [
    'custom_image_width', 'custom_image_height', 'custom_image_border', 'custom_icon_width', 'custom_icon_color',
    'custom_image_align', 'custom_image_grid_width', 'custom_image_grid_column_gap', 'custom_image_grid_row_gap',
    'custom_image_grid_breakpoint', 'custom_image_vertical_align', 'custom_image_svg_inline', 'custom_image_svg_animate',
    'custom_image_svg_color', 'custom_panel_style', 'custom_panel_card_offset', 'custom_panel_padding'
];

foreach ($image_settings as $key => $setting) {
    $element["grid_{$g}_$setting"] = $props["grid_{$g}_$setting"] ?: $element["grid_{$g}_$setting"];
}

//Custom Fields and Overrides
for ($i = 1; $i <= $element['custom_field_sets']; $i++) {
    if ($element['use_custom_fields'] && $element["show_custom_$i"]) {
        //Override Target Grid
        $element["custom_{$i}_grid"] = $props["custom_{$i}_grid_item"] ?: $element["custom_{$i}_grid"];

        //Override Field Sets Visibility
        $element["custom_{$i}_visibility"] = $props["custom_{$i}_visibility_item"] ?: $element["custom_{$i}_visibility"];

        //Set all Custom Fields to Target Grid #1 if Advanced Grid System is Disabled
        !$element['advanced_grid'] ? $element["custom_{$i}_grid"] = '1' : false;

        //shorten statements
        $mixed_width = $element['use_custom_fields'] && $element['advanced_grid'] && $element['advanced_enable_mixed_width'] ? true : false;

        //Override Mixed Width
        if ($mixed_width) {
            $element["custom_{$i}_mixed_width_default"] = $props["custom_{$i}_mixed_width_item_default"] ?: $element["custom_{$i}_mixed_width_default"];
            $element["custom_{$i}_mixed_width_small"] = $props["custom_{$i}_mixed_width_item_small"] ?: $element["custom_{$i}_mixed_width_small"];
            $element["custom_{$i}_mixed_width_medium"] = $props["custom_{$i}_mixed_width_item_medium"] ?: $element["custom_{$i}_mixed_width_medium"];
            $element["custom_{$i}_mixed_width_large"] = $props["custom_{$i}_mixed_width_item_large"] ?: $element["custom_{$i}_mixed_width_large"];
            $element["custom_{$i}_mixed_width_xlarge"] = $props["custom_{$i}_mixed_width_item_xlarge"] ?: $element["custom_{$i}_mixed_width_xlarge"];

            //Set values from the element if empty
            if (!$element["custom_{$i}_mixed_width_default"]) {
                $element["custom_{$i}_mixed_width_default"] = $props["grid_{$g}_custom_item_default"] ?: $element["grid_{$g}_custom_default"];
            }
            if (!$element["custom_{$i}_mixed_width_small"]) {
                $element["custom_{$i}_mixed_width_small"] = $props["grid_{$g}_custom_item_small"] ?: $element["grid_{$g}_custom_small"];
            }
            if (!$element["custom_{$i}_mixed_width_medium"]) {
                $element["custom_{$i}_mixed_width_medium"] = $props["grid_{$g}_custom_item_medium"] ?: $element["grid_{$g}_custom_medium"];
            }
            if (!$element["custom_{$i}_mixed_width_large"]) {
                $element["custom_{$i}_mixed_width_large"] = $props["grid_{$g}_custom_item_large"] ?: $element["grid_{$g}_custom_large"];
            }
            if (!$element["custom_{$i}_mixed_width_xlarge"]) {
                $element["custom_{$i}_mixed_width_xlarge"] = $props["grid_{$g}_custom_item_xlarge"] ?: $element["grid_{$g}_custom_xlarge"];
            }
        }

        //Generate Custom Meta Fields
        ${"custom_{$i}_meta"} = $this->el($element["custom_{$i}_meta_element"] ?? 'div', [
            'class' => [
                "fs-grid-meta",
                "fs-grid-meta-$i",
                "uk-{custom_{$i}_meta_style}",
                "uk-[text-{@custom_{$i}_meta_style: meta}]{custom_{$i}_meta_style}",
                "[uk-text-{custom_{$i}_meta_color}]",
                "[uk-link-{custom_{$i}_meta_hover_style}{@custom_{$i}_meta_hover_style}]",
                "uk-margin[-{custom_{$i}_meta_margin}]-top {@!custom_{$i}_meta_margin: remove}",
                "uk-margin-remove-bottom [uk-margin-{custom_{$i}_meta_margin: remove}-top]" => !in_array($element["custom_{$i}_meta_style"],
                        ["", "meta"], true) || ($element["custom_{$i}_meta_element"] ?? 'div') !== "div",
                "[uk-flex-{text_align}{@!text_align: justify}]",
                "[{custom_{$i}_meta_visibility}]",
                'fs-search-mark {@search}{@search_custom_meta}{@search_highlight}',
            ],
        ]);

        //Generate Custom Text Fields
        ${"custom_$i"} = $this->el($element["custom_{$i}_element"] ?? 'div', [
            'class' => [
                "fs-grid-text",
                "fs-grid-text-$i",
                "uk-{custom_{$i}_style}",
                "[uk-text-{custom_{$i}_color}]",
                "[uk-link-{custom_{$i}_hover_style}{@custom_{$i}_hover_style}]",
                "uk-margin[-{custom_{$i}_margin}]-top {@!custom_{$i}_margin: remove}",
                "uk-margin-remove-bottom [uk-margin-{custom_{$i}_margin: remove}-top]" => !in_array($element["custom_{$i}_style"],
                        ["", "meta"]) || ($element["custom_{$i}_element"] ?? 'div') !== "div",
                "[uk-flex-{text_align}{@!text_align: justify}]",
                "{custom_{$i}_visibility}",
                'fs-search-mark {@search}{@search_custom_text}{@search_highlight}',
            ],
        ]);

        //Generate Custom Fields Containers
        ${"custom_{$i}_container"} = $this->el('div', [
            'class' => [
                "fs-grid-fieldset",
                "fs-grid-fieldset-$i",
                "fs-mw",
                "[uk-width-{custom_{$i}_mixed_width_default}]" => $mixed_width,
                "[uk-width-{custom_{$i}_mixed_width_small}@s]" => $mixed_width,
                "[uk-width-{custom_{$i}_mixed_width_medium}@m]" => $mixed_width,
                "[uk-width-{custom_{$i}_mixed_width_large}@l]" => $mixed_width,
                "[uk-width-{custom_{$i}_mixed_width_xlarge}@xl]" => $mixed_width,
                "{custom_{$i}_visibility}",
            ],
        ]);

        //Generate Custom Images
        if ($props["custom_image_$i"] && $element["show_custom_image_$i"]) {
            $image_custom = $this->el('image', [
                'class' => [
                    "fs-grid-image",
                    "fs-grid-image-$i",
                    "[{grid_{$g}_custom_image_margin_top}-top {@grid_{$g}_custom_image_align:bottom}]",
                    "[{grid_{$g}_custom_image_margin_bottom}-bottom {@grid_{$g}_custom_image_align:top}]",
                    "[uk-border-{grid_{$g}_custom_image_border}]",
                    "[uk-animation-stroke {@grid_{$g}_custom_image_svg_animate}]",
                    "uk-text-{grid_{$g}_custom_image_svg_color}" => $element["grid_{$g}_custom_image_svg_inline"] && $this->isImage($props["custom_image_$i"]) === 'svg',
                ],
                'src' => $props["custom_image_$i"],
                'alt' => $props["custom_image_{$i}_alt"],
                'title' => $props["custom_image_{$i}_alt"],
                'width' => $element["grid_{$g}_custom_image_width"],
                'height' => $element["grid_{$g}_custom_image_height"],

                //Custom Image Attributes Set
                'loading' => $element["grid_{$g}_custom_image_attr_loading"] ? false : null,
                'fetchpriority' => $element["grid_{$g}_custom_image_attr_fetchpriority"] ? 'high' : null,
                'thumbnail' => $element["grid_{$g}_custom_image_attr_thumbnails"] ? false : true,
                'decoding' => $element["grid_{$g}_custom_image_attr_decoding"] ? 'async' : null,
                'uk-svg' => $element["grid_{$g}_custom_image_svg_inline"] ? $this->expr([
                    'stroke-animation: true;',
                ], $element) : false,
            ]);
            $props["custom_image_$i"] = $image_custom($element);
        } elseif ($props["custom_image_{$i}_icon"]) {
            $icon_custom = $this->el('span', [
                'class' => [
                    "fs-grid-image",
                    "fs-grid-image-$i",
                    "fs-grid-icon-$i",
                    "[uk-text-{grid_{$g}_custom_icon_color}]",
                    "[{grid_{$g}_custom_image_margin_top}-top {@grid_{$g}_custom_image_align:bottom}]",
                    "[{grid_{$g}_custom_image_margin_bottom}-bottom {@grid_{$g}_custom_image_align:top}]",
                ],
                'uk-icon' => [
                    "icon: {0};" => $props["custom_image_{$i}_icon"],
                    "[width: {grid_{$g}_custom_icon_width};]",
                    "[height: {grid_{$g}_custom_icon_width};]",
                ],
            ]);
            $props["custom_image_$i"] = $icon_custom($element);
        }

        // Custom Image align
        ${"grid_{$i}_image_custom"} = $this->el('div', [
            'class' => [
                "uk-child-width-expand",
                "[uk-flex-middle {@grid_{$g}_custom_image_vertical_align}{@grid_{$g}_custom_image_align:left|right}]",
                "[uk-grid-column-{grid_{$g}_custom_image_grid_column_gap}]",
                "[uk-grid-row-{grid_{$g}_custom_image_grid_row_gap}]",
            ],
            'uk-grid' => true,
        ]);

        // Cell Image for Custom Fields
        ${"grid_{$i}_cell_image_custom"} = $this->el('div', [
            'class' => [
                "fs-grid-cell-image",
                "[uk-width-{grid_{$g}_custom_image_grid_width}[{grid_{$g}_custom_image_grid_breakpoint}{@!grid_{$g}_custom_image_grid_breakpoint: always}]]",
                "[uk-flex-last[{grid_{$g}_custom_image_grid_breakpoint}{@!grid_{$g}_custom_image_grid_breakpoint: always}]{@grid_{$g}_custom_image_align:right}]",
            ],
        ]);

        // Cell Content for Custom Fields
        ${"grid_{$i}_cell_content_custom"} = $this->el('div', [
            'class' => [
                "fs-grid-cell-text",
                'uk-margin-remove-first-child',
            ],
        ]);

        // Links for Custom Fields
        //PHP 8.1 strip_tags() check if not NULL
        $props["custom_link_{$i}_uk_scroll"] = false;
        if (!empty($props["custom_link_$i"])) {
            if (strpos($props["custom_link_$i"], "#") === 0) {
                $props["custom_link_{$i}_uk_scroll"] = true;
            }
        }

        ${"custom_link_{$i}_container"} = $this->el($props["custom_link_$i"] ? 'a' : 'span', [
            'class' => [
                "uk-panel [uk-{grid_{$g}_custom_panel_style: tile-.*}] {@grid_{$g}_custom_panel_style: |tile-.*}",
                "uk-card uk-{grid_{$g}_custom_panel_style: card-.*} [uk-card-{!grid_{$g}_custom_panel_padding: |default}]",
                "uk-tile-hover {@grid_{$g}_custom_panel_style: tile-.*} " => $props["custom_link_$i"],
                "uk-card-hover {@!grid_{$g}_custom_panel_style: |card-hover|tile-.*} " => $props["custom_link_$i"],
                "uk-padding[-{!grid_{$g}_custom_panel_padding: default}] {@grid_{$g}_custom_panel_style: |tile-.*} {@grid_{$g}_custom_panel_style}",
                "uk-card-body {@grid_{$g}_custom_panel_style: card-.*} {@grid_{$g}_custom_panel_padding}",
                "uk-margin-remove-first-child" => !in_array($element["grid_{$g}_custom_image_align"],
                        ['left', 'right']) || !($element["grid_{$g}_custom_panel_padding"]),
                "uk-flex {@grid_{$g}_custom_panel_style} {@grid_{$g}_custom_image_align: left|right}",
                // Let images cover the card/tile height if they have different heights
                'uk-link-toggle' => $props["custom_link_$i"],
            ],
            'href' => $props["custom_link_$i"],
            'target' => $this->expr(["_blank {@custom_link_{$i}_item_target}{@!custom_link_{$i}_toggle}"], $props) ?: false,
            'uk-scroll' => $props["custom_link_{$i}_uk_scroll"] && !$props["custom_link_{$i}_toggle"],
            'uk-toggle' => $props["custom_link_{$i}_uk_scroll"] && $props["custom_link_{$i}_toggle"],
        ]);

        // Limit Output
        if (!empty($props["custom_text_$i"]) && $element["show_custom_$i"] && $element["custom_{$i}_limit"] && $element["custom_{$i}_limit_length"]) {
            $text = strip_tags($props["custom_text_$i"]);
            $limit = $element["custom_{$i}_limit_length"];

            if (function_exists('mb_strlen') && function_exists('mb_substr')) {
                // Use mb_strlen() and mb_substr() if available
                if (mb_strlen($text, 'UTF-8') > $limit) {
                    $props["custom_text_$i"] = mb_substr($text, 0, $limit, 'UTF-8') . '...';
                }
            } else {
                // Use a fallback method if mbstring functions are not available
                if (strlen($text) > $limit) {
                    $props["custom_text_$i"] = rtrim(substr($text, 0, $limit), ' .,;:-') . '...';
                }
            }
        }
    }
}

?>

<?php $items = 0; ?>

<?php for ($i = 1; $i <= $element['custom_field_sets']; $i++) { ?>

    <?php if ($element["show_custom_$i"] && ($element["show_custom_text_$i"] || $element["show_custom_image_$i"]) && ($props["custom_meta_$i"] || $props["custom_text_$i"] || $props["custom_image_$i"]) && $element["custom_{$i}_grid"] == $grid_target): ?>

        <?php ++$items; ?>

        <?= ${"custom_{$i}_container"}($element) ?>

        <?= ${"custom_link_{$i}_container"}($element) ?>

        <?php if ($props["custom_image_$i"] && $element["show_custom_image_$i"] && in_array($element["grid_{$g}_custom_image_align"],
                ['left', 'right'])): ?>

            <?= ${"grid_{$i}_image_custom"}($element) ?>

            <?= ${"grid_{$i}_cell_image_custom"}($element, $props["custom_image_$i"]) ?>
            <?= ${"grid_{$i}_cell_content_custom"}($element) ?>

            <?php if ($element["custom_{$i}_meta_position"] === 'above-custom-text' && $props["custom_meta_$i"] && $element["show_custom_meta_$i"]): ?>
                <?= ${"custom_{$i}_meta"}($element, $props["custom_meta_$i"]) ?>
            <?php endif ?>

            <?php if ($props["custom_text_$i"] && $element["show_custom_text_$i"]): ?>
                <?= ${"custom_$i"}($element, $props["custom_text_$i"]) ?>
            <?php endif ?>

            <?php if ($element["custom_{$i}_meta_position"] === "bellow-custom-text" && $props["custom_meta_$i"] && $element["show_custom_meta_$i"]): ?>
                <?= ${"custom_{$i}_meta"}($element, $props["custom_meta_$i"]) ?>
            <?php endif ?>

            <?= ${"grid_{$i}_cell_content_custom"}->end() ?>
            <?= ${"grid_{$i}_image_custom"}->end() ?>

        <?php else: ?>

            <?php if ($props["custom_image_$i"] && $element["show_custom_image_$i"] && $element["grid_{$g}_custom_image_align"] === 'top'): ?>
                <div><?= $props["custom_image_$i"] ?></div>
            <?php endif ?>

            <?php if ($element["custom_{$i}_meta_position"] === 'above-custom-text' && $props["custom_meta_$i"] && $element["show_custom_meta_$i"]): ?>
                <?= ${"custom_{$i}_meta"}($element, $props["custom_meta_$i"]) ?>
            <?php endif ?>

            <?php if ($props["custom_text_$i"] && $element["show_custom_text_$i"]): ?>
                <?= ${"custom_$i"}($element, $props["custom_text_$i"]) ?>
            <?php endif ?>

            <?php if ($element["custom_{$i}_meta_position"] === "bellow-custom-text" && $props["custom_meta_$i"] && $element["show_custom_meta_$i"]): ?>
                <?= ${"custom_{$i}_meta"}($element, $props["custom_meta_$i"]) ?>
            <?php endif ?>

            <?php if ($props["custom_image_$i"] && $element["show_custom_image_$i"] && $element["grid_{$g}_custom_image_align"] === 'bottom'): ?>
                <div><?= $props["custom_image_$i"] ?></div>
            <?php endif ?>

        <?php endif ?>

        <?= ${"custom_link_{$i}_container"}->end() ?>

        <?= ${"custom_{$i}_container"}->end() ?>

    <?php elseif ($i == $element['custom_field_sets'] && $element['advanced_enable_grids_empty_notification'] && $items == 0): ?>

        <div class="fs-blank-grid uk-width-1-1 uk-text-small uk-text-center uk-text-muted">Grid
            #<?= $grid_target ?> is empty. <br/>Please add content or disable.
        </div>

    <?php endif ?>

<?php } ?>