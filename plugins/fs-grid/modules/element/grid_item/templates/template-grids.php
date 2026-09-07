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
/** @var $__dir */
/** @var $grid_position */

//First Custom Grid is Always Enabled
$element['advanced_enable_grid_1_custom'] = $element['use_custom_fields'] ? true : false;

//Custom Grids
for ($i = 1; $i <= $element['custom_grids']; $i++) {
    //Override Custom Grid
    $element["grid_{$i}_custom_position"] = $props["grid_{$i}_custom_item_position"] ?: $element["grid_{$i}_custom_position"];

    //Generating Grids For Custom Fields
    if ($element['use_custom_fields'] && $element["advanced_enable_grid_{$i}_custom"] && $element["grid_{$i}_custom_position"] === $grid_position) {
        //Set all Custom Fields to Target Grid #1 if Advanced Grid System is Disabled
        if (!$element['advanced_grid']) {
            if ($i != 1) {
                $element["advanced_enable_grid_{$i}_custom"] = false;
            }
        }

        $element["grid_{$i}_custom_column_gap"] = $props["grid_{$i}_custom_item_column_gap"] ?: $element["grid_{$i}_custom_column_gap"];
        $element["grid_{$i}_custom_row_gap"] = $props["grid_{$i}_custom_item_row_gap"] ?: $element["grid_{$i}_custom_row_gap"];
        $element["grid_{$i}_custom_divider_top"] = $props["grid_{$i}_custom_item_divider_top"] ?: $element["grid_{$i}_custom_divider_top"];
        $element["grid_{$i}_custom_divider"] = $props["grid_{$i}_custom_item_divider"] ?: $element["grid_{$i}_custom_divider"];
        $element["grid_{$i}_custom_column_align"] = $props["grid_{$i}_custom_item_column_align"] ?: $element["grid_{$i}_custom_column_align"];
        $element["grid_{$i}_custom_row_align"] = $props["grid_{$i}_custom_item_row_align"] ?: $element["grid_{$i}_custom_row_align"];
        $element["grid_{$i}_custom_grid_match"] = $props["grid_{$i}_custom_item_grid_match"] ?: $element["grid_{$i}_custom_grid_match"];
        $element["grid_{$i}_custom_text_align"] = $props["grid_{$i}_custom_item_text_align"] ?: $element["grid_{$i}_custom_text_align"];

        $props["grid_{$i}_custom_item_text_align_breakpoint"] === 'inherit' ? $props["grid_{$i}_custom_item_text_align_breakpoint"] = '' : '';
        $element["grid_{$i}_custom_text_align_breakpoint"] = $props["grid_{$i}_custom_item_text_align_breakpoint"] ?: $element["grid_{$i}_custom_text_align_breakpoint"];

        $element["grid_{$i}_custom_text_align_breakpoint"] === 'always' ? $element["grid_{$i}_custom_text_align_breakpoint"] = '' : '';

        $element["grid_{$i}_custom_text_align_fallback"] = $props["grid_{$i}_custom_item_text_align_fallback"] ?: $element["grid_{$i}_custom_text_align_fallback"];

        $element["grid_{$i}_custom_visibility"] = $props["grid_{$i}_custom_item_visibility"] ?: $element["grid_{$i}_custom_visibility"];

        //3 state override
        $props["grid_{$i}_custom_item_margin"] === 'inherit' ? $props["grid_{$i}_custom_item_margin"] = '' : '';
        $element["grid_{$i}_custom_margin"] = $props["grid_{$i}_custom_item_margin"] ?: $element["grid_{$i}_custom_margin"];
        $element["grid_{$i}_custom_margin"] === 'default' ? $element["grid_{$i}_custom_margin"] = '' : '';

        $element["grid_{$i}_custom_slider"] = $props["grid_{$i}_custom_item_slider"] ?: $element["grid_{$i}_custom_slider"];
        $element["grid_{$i}_custom_slider"] === 'disable' ? $element["grid_{$i}_custom_slider"] = false : '';

        //Override Custom Grids Columns
        $element["grid_{$i}_custom_default"] = $props["grid_{$i}_custom_item_default"] ?: $element["grid_{$i}_custom_default"];
        $element["grid_{$i}_custom_small"] = $props["grid_{$i}_custom_item_small"] ?: $element["grid_{$i}_custom_small"];
        $element["grid_{$i}_custom_medium"] = $props["grid_{$i}_custom_item_medium"] ?: $element["grid_{$i}_custom_medium"];
        $element["grid_{$i}_custom_large"] = $props["grid_{$i}_custom_item_large"] ?: $element["grid_{$i}_custom_large"];
        $element["grid_{$i}_custom_xlarge"] = $props["grid_{$i}_custom_item_xlarge"] ?: $element["grid_{$i}_custom_xlarge"];

        // Override panel settings
        $element["grid_{$i}_custom_panel_style"] = $props["grid_{$i}_custom_panel_style"] ?: $element["grid_{$i}_custom_panel_style"];
        $element["grid_{$i}_custom_panel_card_offset"] = $props["grid_{$i}_custom_panel_card_offset"] ?: $element["grid_{$i}_custom_panel_card_offset"];
        $element["grid_{$i}_custom_panel_padding"] = $props["grid_{$i}_custom_panel_padding"] ?: $element["grid_{$i}_custom_panel_padding"];

        //Custom Grids
        ${"grid_{$i}_custom"} = $this->el('div', [

            'class' => [
                "fs-grid-nested-{$i}",
                'uk-slider-items' => $element["grid_{$i}_custom_slider"] && !$element['slider'],

                'uk-child-width-' . $element["grid_{$i}_custom_default"] => $element["grid_{$i}_custom_default"],
                'uk-child-width-' . $element["grid_{$i}_custom_small"] . '@s' => $element["grid_{$i}_custom_small"],
                'uk-child-width-' . $element["grid_{$i}_custom_medium"] . '@m' => $element["grid_{$i}_custom_medium"],
                'uk-child-width-' . $element["grid_{$i}_custom_large"] . '@l' => $element["grid_{$i}_custom_large"],
                'uk-child-width-' . $element["grid_{$i}_custom_xlarge"] . '@xl' => $element["grid_{$i}_custom_xlarge"],

                'uk-text-' . $element["grid_{$i}_custom_text_align"] . $element["grid_{$i}_custom_text_align_breakpoint"] => $element["grid_{$i}_custom_text_align"],

                //Space here is important
                ' uk-text-' . $element["grid_{$i}_custom_text_align_fallback"] => $element["grid_{$i}_custom_text_align_breakpoint"] && $element["grid_{$i}_custom_text_align_fallback"] && $element["grid_{$i}_custom_text_align"],

                'uk-flex-center' => $element["grid_{$i}_custom_column_align"] && !$element["grid_{$i}_custom_slider"],

                'uk-flex-middle' => $element["grid_{$i}_custom_row_align"],

                'uk-grid-column-' . $element["grid_{$i}_custom_column_gap"] => $element["grid_{$i}_custom_column_gap"],

                'uk-grid-row-' . $element["grid_{$i}_custom_row_gap"] => $element["grid_{$i}_custom_row_gap"],

                'uk-grid-divider' => $element["grid_{$i}_custom_divider"] && $element["grid_{$i}_custom_column_gap"] !== 'collapse' && $element["grid_{$i}_custom_row_gap"] !== 'collapse',

                "uk-margin[-{grid_{$i}_custom_margin}]-top {@!grid_{$i}_custom_margin: remove}",
                "uk-margin[-{grid_{$i}_custom_margin_bottom}]-bottom {@!grid_{$i}_custom_margin_bottom: remove} {@grid_{$i}_custom_position: item-top-outside}",
                'uk-grid-match' => $element["grid_{$i}_custom_grid_match"],

            ],

            'uk-grid' => true,

        ]);

        // Custom Grid Container
        ${"custom_grid_{$i}_container"} = $this->el('div', [

            'class' => [
                "fs-grid-nested-{$i}-container",
                'uk-panel',
                'uk-margin-small' => $element['use_custom_fields'] && $element['advanced_enable_grids_hightlight'],

                //Grid Visibility
                $element["grid_{$i}_custom_visibility"] => $element["grid_{$i}_custom_visibility"],
            ],

            'style' => [
                'border: 1px dashed #ddd;' => $element['use_custom_fields'] && $element['advanced_enable_grids_hightlight'],
                'padding-bottom: 30px;' => $element['use_custom_fields'] && $element['advanced_enable_grids_hightlight'],
            ],

        ]);

        //Slider
        ${'slider_' . $i} = $this->el('div', [

            'class' => [
                "uk-slider-container",
                "uk-slider-container-offset {@grid_{$i}_custom_panel_style}{@grid_{$i}_custom_panel_card_offset}{grid_{$i}_custom_panel_style: card-.*}",
            ],

            'uk-slider' => $this->expr([
                'sets: {slider_sets};',
                'center: {slider_center};',
                'finite: {slider_finite};',
                'velocity: {slider_velocity};',
                'autoplay: {slider_autoplay}; [pauseOnHover: false; {@!slider_autoplay_pause}] [autoplayInterval: {slider_autoplay_interval}000;]',
            ], $element) ?: true,

        ]);
    }
}

// Slider Container
$slider_container = $this->el('div', [

    'class' => [
        'uk-position-relative',
        'uk-visible-toggle {@slidenav_grid} {@slidenav_hover}',
    ],

    'tabindex' => ['-1 {@slidenav_grid} {@slidenav_hover}'],

]);

// Grid Highlighter
$custom_grid_hightlighter = $this->el('span', [

    'class' => [
        'uk-flex uk-flex-center uk-text-primary' => $element['use_custom_fields'] && $element['advanced_enable_grids_hightlight'],
    ],

    'style' => [
        'font-size:12px; text-transform: uppercase;' => $element['use_custom_fields'] && $element['advanced_enable_grids_hightlight'],
        'border: 1px dashed #ddd;' => $element['use_custom_fields'] && $element['advanced_enable_grids_hightlight'],
        'padding: 5px 10px;' => $element['use_custom_fields'] && $element['advanced_enable_grids_hightlight'],
        'margin: 5px;' => $element['use_custom_fields'] && $element['advanced_enable_grids_hightlight'],
    ],

]);

?>

<?php for ($g = 1; $g <= $element['custom_grids']; $g++) { ?>

    <?php if ($element['advanced_enable_grids_empty_disable'] && $element["advanced_enable_grid_{$g}_custom"] && $element["grid_{$g}_custom_position"] === $grid_position): ?>

        <?php for ($f = 1; $f <= $element['custom_field_sets']; $f++) {
            $element["custom_{$f}_grid"] = $props["custom_{$f}_grid_item"] ?: $element["custom_{$f}_grid"];
            if ($element["custom_{$f}_grid"] == $g && ($props["custom_meta_{$f}"] || $props["custom_text_{$f}"] || $props["custom_image_{$f}"] || $props["custom_image_{$f}_icon"])) {
                ${"grid_{$g}_active"} = true;
                break 1;
            } else {
                ${"grid_{$g}_active"} = false;
            }
        } ?>
    <?php else: ?>
        <?php ${"grid_{$g}_active"} = true; ?>
    <?php endif ?>

    <?php if ($element['use_custom_fields'] && $element["grid_{$g}_custom_position"] === $grid_position && $element["advanced_enable_grid_{$g}_custom"] && !$props["item_disable_custom_grid_{$g}"] && ${"grid_{$g}_active"}): ?>

        <?php if ($element["grid_{$g}_custom_divider_top"]): ?>
            <hr class="fs-grid-divider uk-margin-remove-bottom">
        <?php endif ?>

        <?= ${"custom_grid_{$g}_container"}($element) ?>

        <?php if ($element['use_custom_fields'] && $element['advanced_enable_grids_hightlight']): ?>
            <?= $custom_grid_hightlighter($element) ?>
            <?= "Custom Grid #{$g}" ?>
            <?= $custom_grid_hightlighter->end() ?>
        <?php endif ?>

        <?php if ($element['show_sublayout'] && $element['sublayout_position'] == "grid_{$g}" && $element['sublayout_align'] === 'top') : ?>
            <?= $this->render("$__dir/template-sublayout") ?>
        <?php endif ?>

        <?php if (!$element['slider'] && $element["grid_{$g}_custom_slider"]): ?>
            <?= ${"slider_{$g}"}($element) ?>
            <?= $slider_container($element) ?>
        <?php endif ?>

        <?= ${"grid_{$g}_custom"}($element) ?>

        <?php $grid_target = $g; ?>
        <?= $this->render("$__dir/template-grids-content", compact('props', 'grid_target')) ?>

        <?= ${"grid_{$g}_custom"}->end() ?>

        <?php if (!$element['slider'] && $element["grid_{$g}_custom_slider"]): ?>

            <?php if ($element['slidenav_grid']): ?>
                <?= $this->render("$__dir/template-slider-slidenav") ?>
            <?php endif ?>

            <?= $slider_container->end() ?>

            <?php if ($element['nav']): ?>
                <?= $this->render("$__dir/template-slider-nav") ?>
            <?php endif ?>

            <?= ${"slider_{$g}"}->end() ?>
        <?php endif ?>


        <?php if ($element['show_sublayout'] && $element['sublayout_position'] == "grid_{$g}" && $element['sublayout_align'] === 'bottom') : ?>
            <?= $this->render("$__dir/template-sublayout") ?>
        <?php endif ?>

        <?= ${"custom_grid_{$g}_container"}->end() ?>

    <?php endif ?>

<?php } ?>