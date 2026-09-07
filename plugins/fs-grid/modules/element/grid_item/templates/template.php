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
/** @var $attrs */
/** @var $__dir */

// Display
foreach (
    [
        'title', 'meta', 'content', 'link', 'custom_1', 'custom_2', 'custom_3', 'custom_4', 'custom_5', 'custom_6', 'custom_7',
        'custom_8', 'custom_9', 'custom_10', 'custom_11', 'custom_12', 'custom_13', 'custom_14', 'custom_15', 'custom_16',
        'custom_17', 'custom_18', 'custom_19', 'custom_20', 'custom_meta_1', 'custom_meta_2', 'custom_meta_3', 'custom_meta_4',
        'custom_meta_5', 'custom_meta_6', 'custom_meta_7', 'custom_meta_8', 'custom_meta_9', 'custom_meta_10', 'custom_meta_11',
        'custom_meta_12', 'custom_meta_13', 'custom_meta_14', 'custom_meta_15', 'custom_meta_16', 'custom_meta_17',
        'custom_meta_18', 'custom_meta_19', 'custom_meta_20', 'custom_text_1', 'custom_text_2', 'custom_text_3', 'custom_text_4',
        'custom_text_5', 'custom_text_6', 'custom_text_7', 'custom_text_8', 'custom_text_9', 'custom_text_10', 'custom_text_11',
        'custom_text_12', 'custom_text_13', 'custom_text_14', 'custom_text_15', 'custom_text_16', 'custom_text_17',
        'custom_text_18', 'custom_text_19', 'custom_text_20', 'custom_image_1', 'custom_image_2', 'custom_image_3',
        'custom_image_4', 'custom_image_5', 'custom_image_6', 'custom_image_7', 'custom_image_8', 'custom_image_9',
        'custom_image_10', 'custom_image_11', 'custom_image_12', 'custom_image_13', 'custom_image_14', 'custom_image_15',
        'custom_image_16', 'custom_image_17', 'custom_image_18', 'custom_image_19', 'custom_image_20', 'custom_link_1',
        'custom_link_2', 'custom_link_3', 'custom_link_4', 'custom_link_5', 'custom_link_6', 'custom_link_7', 'custom_link_8',
        'custom_link_9', 'custom_link_10', 'custom_link_11', 'custom_link_12', 'custom_link_13', 'custom_link_14',
        'custom_link_15', 'custom_link_16', 'custom_link_17', 'custom_link_18', 'custom_link_19', 'custom_link_20'
    ] as $key
) {
    if (!$element["show_$key"]) {
        $props[$key] = '';
    }
}

//Automatic modal integration
if ($element['show_link'] && $element['show_sublayout'] && $element['sublayout_mode'] === "modal" && $element['sublayout_modal_wrap'] === "all") {
    if ($props['link_item_toggle'] && $props['link_item_toggle_modal_integration']) {
        $props['link_modal_connect'] = "fs-grid-modal-{$this->uid()}";
        $props['link'] = "#{$props['link_modal_connect']}";
        $props['link_modal_id'] = $props['link_modal_connect'];
    }
}

if (!$element['show_image']) {
    $props['image'] = $props['icon'] = '';
}

//placeholder image
if ($element['image_placeholder_enable'] && $element['image_placeholder'] && !$props['image']) {
    $props['image'] = $element['image_placeholder'];
    $props['image_alt'] = $element['image_placeholder_alt'] ?: 'image placeholder';
    $props['image_title'] = $element['image_placeholder_alt'] ?: 'image placeholder';
}

// Resets
if ($props['icon'] && !$props['image']) {
    $element['panel_card_image'] = '';
}

// Override default settings
$element['panel_style'] = $props['panel_style'] ?: $element['panel_style'];
$element['link_text'] = $props['link_text'] ?: $element['link_text'];

// Reset link bottom if the button is not enabled
if (!$element['show_link'] || !$element['link_text'] || $element['grid_masonry']) {
    $element['link_button_bottom'] = false;
}

// New logic shortcuts
$element['has_panel_card_image'] = $props['image'] && $element['panel_card_image'] && $element['image_align'] !== 'between';
$element['has_content_padding'] = $props['image'] && $element['panel_content_padding'] && $element['image_align'] !== 'between';

// If link is not set use the default image for the lightbox
if (!$props['link'] && $element['lightbox']) {
    $props['link'] = $props['image'];
}

// Image
$props['image'] = $this->render("$__dir/template-image", compact('props'));

if ($element['image_transition'] && $element['show_image'] && $element['show_link'] && ($props['image'])) {
    $transition_toggle = $this->el('div', [
        'class' => [
            'uk-inline-clip [uk-transition-toggle {@image_link}]',
            'uk-border-{image_border}' => !$element['panel_style'] || ($element['panel_style'] && (!$element['panel_card_image'] || $element['image_align'] === 'between')),
            'uk-box-shadow-{image_box_shadow} {@!panel_style}',
            'uk-box-shadow-hover-{image_hover_box_shadow} {@!panel_style}' => $props['link'] && ($element['image_link'] || $element['panel_link']),
            'uk-margin[-{image_margin}]-top {@!image_margin: remove} {@!image_box_decoration}' => $element['image_align'] === 'between' || ($element['image_align'] === 'bottom' && !($element['panel_style'] && $element['panel_card_image'])),
        ],
    ]);
    $props['image'] = $transition_toggle($element, $props['image']);
}

if ($props['image'] && $element['image_box_decoration']) {
    $decoration = $this->el('div', [
        'class' => [
            'uk-box-shadow-bottom {@image_box_decoration: shadow}',
            'tm-mask-default {@image_box_decoration: mask}',
            'tm-box-decoration-{image_box_decoration: default|primary|secondary}',
            'tm-box-decoration-inverse {@image_box_decoration_inverse} {@image_box_decoration: default|primary|secondary}',
            'uk-inline {@!image_box_decoration: |shadow}',
            'uk-margin[-{image_margin}]-top {@!image_margin: remove}' => $element['image_align'] === 'between' || ($element['image_align'] === 'bottom' && !($element['panel_style'] && $element['panel_card_image'])),
        ],
    ]);
    $props['image'] = $decoration($element, $props['image']);
}

// Item Holder
$item_holder = $this->el($props['item_element'] ?: 'div', [
    'class' => [
        'fs-grid-item-holder',
        'uk-flex uk-flex-column' => $element['panel_style'] || $element['link_button_bottom'],
    ],
]);

// Panel/Card
$el = $this->el(($props['link'] || $props['link_panel_custom']) && $element['panel_link'] && $element['show_link'] ? 'a' : 'div',
    [
        'id' => strip_tags($props['item_id'] ?? ''),
        'class' => [
            strip_tags($props['item_class'] ?? '') => !$props['item_class_tag_in_container'],
            'el-item',
            'odd' => $element['grid_stack'] && !$props['item_reverse'],
            'even' => $element['grid_stack'] && $props['item_reverse'],
            'fs-grid-item-' . $props['item_num'],
            'uk-margin-auto uk-width-{item_maxwidth}',
            'uk-panel {@!panel_style}',
            'uk-card uk-{panel_style} [uk-card-{panel_size}]',
            'uk-card-hover {@!panel_style: |card-hover} {@panel_link}' => $props['link'],
            'uk-card-body {@panel_style} {@!has_panel_card_image}',
            'uk-margin-remove-first-child' => (!$element['panel_style'] && !$element['has_content_padding']) || ($element['panel_style'] && !$element['has_panel_card_image']),
            'uk-flex {@panel_style} {@has_panel_card_image} {@image_align: left|right}',
            // Let images cover the card height if the cards have different heights
            'uk-transition-toggle {@image_transition} {@panel_link}' => $props['image'],
            'uk-flex uk-flex-column' => $element['link_button_bottom'] || (in_array($element['image_align'],
                        array("top", "bottom")) == true && $element['grid_stack']),
            'uk-flex-1' => $element['panel_style'] || $element['link_button_bottom'],

            //Link
            'uk-link-toggle {@panel_link}',
            // Only if "uk-flex" is not already set in "template.php" to let images cover the card height if the cards have different heights
            'uk-display-block' => !($element['panel_style'] && $element['has_panel_card_image'] && in_array($element['image_align'],
                        ['left', 'right'])) && !$element['link_button_bottom'],
        ],
        //Item Attributes
        strip_tags($props['item_attrs_tag'] ?? '') => !$props['item_attrs_tag_in_container'],
    ]);

// Image align
$grid = $this->el('div', [
    'class' => [
        'uk-child-width-expand',
        $element['panel_style'] && $element['has_panel_card_image']
            ? 'uk-grid-collapse uk-grid-match'
            : ($element['image_grid_column_gap'] === $element['image_grid_row_gap']
            ? 'uk-grid-{image_grid_column_gap}'
            : '[uk-grid-column-{image_grid_column_gap}] [uk-grid-row-{image_grid_row_gap}]'),
        'uk-flex-middle {@image_vertical_align}{@!link_button_bottom}',
        'uk-flex-1 {@link_button_bottom}{@image_align: left|right}',
    ],
    'uk-grid' => true,
]);

$cell_image = $this->el('div', [
    'class' => [
        'uk-width-{image_grid_width}[@{image_grid_breakpoint}] {@image_align: left|right}',
        'uk-flex-last[@{image_grid_breakpoint}] {@image_align: right}' => !$props['item_reverse'],
        'uk-flex-first[@{image_grid_breakpoint}] {@image_align: right|bottom}' => $element['grid_stack'] && $props['item_reverse'],
        'uk-flex-last[@{image_grid_breakpoint}] {@image_align: left|top}' => $element['grid_stack'] && $props['item_reverse'],
    ],
    'style' => ['align-self: center; {@image_vertical_align}{@link_button_bottom}'],
]);

// Content
$content = $this->el('div', [
    'class' => [
        'uk-card-body uk-margin-remove-first-child {@panel_style} {@has_panel_card_image}',
        'uk-padding[-{!panel_content_padding: |default}] uk-margin-remove-first-child {@!panel_style} {@has_content_padding}',
        // 1 Column Content Width
        'uk-container uk-container-{panel_content_width}' => $props['image'] && $element['image_align'] === 'top' && !$element['panel_style'] && !$element['panel_content_padding'] && !$element['item_maxwidth'] && (!$element['grid_default'] || $element['grid_default'] === '1-1') && (!$element['grid_small'] || $element['grid_small'] === '1-1') && (!$element['grid_medium'] || $element['grid_medium'] === '1-1') && (!$element['grid_large'] || $element['grid_large'] === '1-1') && (!$element['grid_xlarge'] || $element['grid_xlarge'] === '1-1'),
        'uk-flex uk-flex-column uk-flex-1 {@link_button_bottom}',
    ],
]);

$cell_content = $this->el('div', [
    'class' => [
        'uk-margin-remove-first-child' => (!$element['panel_style'] && !$element['has_content_padding']) || ($element['panel_style'] && !$element['has_panel_card_image']),
        'uk-text-right' => $element['grid_stack'] && $props['item_reverse'] && $element['image_align'] !== 'right',
        'uk-flex uk-flex-column {@link_button_bottom}' => in_array($element['image_align'], ['left', 'right']),
    ],
]);

$panel_content_wrapper = $this->el('object', [
    'class' => ['uk-flex uk-flex-column uk-height-1-1 {@link_button_bottom}' => $element['panel_link']]
]);

// Limit Output
foreach (['title', 'meta', 'content'] as $field) {
    if (!empty($element["{$field}_limit"]) && !empty($props[$field])) {
        $limit = (int)($element["{$field}_limit_length"] ?? 0);
        $plain = strip_tags($props[$field]);
        $props[$field] = ($limit > 0 && mb_strlen($plain, 'UTF-8') > $limit) ? mb_substr($plain, 0, $limit,
                'UTF-8') . '...' : $props[$field];
    }
}

// Link
$link = include __DIR__ . "/template-link.php";

// Card media & Button Bottom Align
if (($element['panel_style'] && $element['has_panel_card_image'] && $props['image']) || ($element['show_link'] && $element['link_button_bottom'] && $props['image'])) {
    $props['image'] = $this->el('div', [
        'class' => [
            'fs-grid-image-holder' => $element['show_link'] && $element['link_button_bottom'],
            'uk-card-media-{image_align}' => $element['panel_style'] && $element['has_panel_card_image'],
            'uk-cover-container{@image_align: left|right}' => $element['panel_style'] && $element['has_panel_card_image'],
        ],
        'uk-toggle' => [
            'cls: uk-card-media-{image_align} uk-card-media-top; mode: media; media: @{image_grid_breakpoint} {@image_align: left|right}' => $element['panel_style'] && $element['has_panel_card_image'],
        ],
    ], $props['image'])->render($element);
}

?>

<?= $item_holder($element) ?>

<?php $grid_position = 'item-top-outside'; ?>

<?php if ($element['show_sublayout'] && $element['sublayout_position'] === $grid_position && $element['sublayout_align'] === 'top') : ?>
    <?= $this->render("$__dir/template-sublayout", compact('props')) ?>
<?php endif ?>

<?= $this->render("$__dir/template-grids-positions", compact('props', 'grid_position')) ?>

<?php if ($element['show_sublayout'] && $element['sublayout_position'] === $grid_position && $element['sublayout_align'] === 'bottom') : ?>
    <?= $this->render("$__dir/template-sublayout", compact('props')) ?>
<?php endif ?>

<?= $el($element) ?>

<?php if (($props['link'] || $props['link_panel_custom']) && $element['panel_link'] && $element['show_link']): ?>
    <?= $panel_content_wrapper($element) ?>
<?php endif ?>

<?php if ($props['image'] && in_array($element['image_align'], ['left', 'right'])): ?>

    <?= $grid($element) ?>

    <?= $cell_image($element) ?>
    <?= $this->render("$__dir/template-image-layout", compact('props')) ?>
    <?= $cell_image->end() ?>

    <?= $cell_content($element) ?>

    <?php if ($this->expr($content->attrs['class'], $element)): ?>
        <?= $content($element, $this->render("$__dir/template-layout-simple", compact('props', 'link'))) ?>
    <?php else: ?>
        <?= $this->render("$__dir/template-layout-simple", compact('props', 'link')) ?>
    <?php endif ?>

    <?= $cell_content->end() ?>
    <?= $grid->end() ?>

<?php else: ?>

    <?php if ($props['image'] && $element['image_align'] === 'top'): ?>
        <?= $cell_image($element) ?>
        <?= $this->render("$__dir/template-image-layout", compact('props')) ?>
        <?= $cell_image->end() ?>
    <?php endif ?>

    <?php if ($this->expr($content->attrs['class'], $element)): ?>
        <?= $content($element, $this->render("$__dir/template-layout-simple", compact('props', 'link'))) ?>
    <?php else: ?>
        <?= $this->render("$__dir/template-layout-simple", compact('props', 'link')) ?>
    <?php endif ?>

    <?php if ($props['image'] && $element['image_align'] === 'bottom'): ?>
        <?= $cell_image($element) ?>
        <?= $this->render("$__dir/template-image-layout", compact('props')) ?>
        <?= $cell_image->end() ?>
    <?php endif ?>

<?php endif ?>

<?php if (($props['link'] || $props['link_panel_custom']) && $element['panel_link'] && $element['show_link']): ?>
    <?= $panel_content_wrapper->end() ?>
<?php endif ?>

<?= $el->end() ?>

<?php $grid_position = 'item-bottom-outside'; ?>

<?php if ($element['show_sublayout'] && $element['sublayout_position'] === $grid_position && $element['sublayout_align'] === 'top') : ?>
    <?= $this->render("$__dir/template-sublayout", compact('props')) ?>
<?php endif ?>

<?= $this->render("$__dir/template-grids-positions", compact('props', 'grid_position')) ?>

<?php if ($element['show_sublayout'] && $element['sublayout_position'] === $grid_position && $element['sublayout_align'] === 'bottom') : ?>
    <?= $this->render("$__dir/template-sublayout", compact('props')) ?>
<?php endif ?>

<?= $item_holder->end() ?>