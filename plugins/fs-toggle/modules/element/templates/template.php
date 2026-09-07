<?php /**
 * @package     [FS] Toggle element for YOOtheme Pro
 * @subpackage  fs-toggle
 *
 * @author      Flart Studio https://flart.studio
 * @copyright   Copyright (C) 2008-2026 Flart Studio. All rights reserved.
 * @license     GNU General Public License version 2 or later; see https://www.gnu.org/licenses/gpl-2.0.html
 * @license     Non-PHP assets in this package are proprietary — https://flart.studio/license
 * @link        https://flart.studio/yootheme-pro/toggle
 * @build       (FLART_BUILD_NUMBER)
 */

/** @noinspection DuplicatedCode, NestedTernaryOperatorInspection */

defined('_JEXEC') or defined('ABSPATH') or die();

// Ensure these variables exist
/** @var $builder */
/** @var $children */
/** @var $props */
/** @var $attrs */
/** @var $__dir */

// Sanitize Toggle ID: strip tags, replace invalid chars with dashes, collapse multiple dashes
$id = trim(preg_replace(['/[^\w-]+/', '/-+/'], ['-', '-'], strip_tags($props['toggle_id'] ?? '')), '-');
$props['toggle_id'] = $id === '' ? "fs-toggle-{$this->uid()}" : (ctype_digit($id[0]) ? "fs-toggle-$id" : $id);

// Defaults
$props['toggle_cls'] = trim(preg_replace('/[^\w\s-]/', '', strip_tags($props['toggle_cls'] ?? ''))) ?: 'uk-hidden';
$props['link_position'] = $props['toggle_mode'] !== 'click' ? 'top' : ($props['link_position'] ?? 'top');

// Resets
$props['toggle_state'] = $props['toggle_mode'] !== 'media' ? ($props['toggle_state'] ?? '') : '';
$props['show_link'] = $props['toggle_mode'] === 'media' ? false : $props['show_link'];

// Shortcuts
$totalRows = count($children);
$splitAt = !empty($props['toggle_rows']) ? (int)$props['toggle_rows'] : 0;
$props['toggle_rows'] = $splitAt > 0 && $totalRows > $splitAt;
$props['link_alt_state'] = $props['toggle_mode'] !== 'media'
    && (($props['link_icon'] && $props['link_icon_alt']) || ($props['link_text'] && $props['link_text_alt']));
$props['aria_controls'] = $props['toggle_rows']
    ? "{$props['toggle_id']}-head {$props['toggle_id']}-tail"
    : $props['toggle_id'];

// Element
$el = $this->el('div', [
    'class' => [
        'fs-toggle',

        // YOOtheme Pro 5 Margins
        ...(($props['_yootheme_v4'] ?? false) === true ? [
            'uk-margin-top {@margin_top: default}',
            'uk-margin-{!margin_top: |default}-top',
            'uk-margin-bottom {@margin_bottom: default}',
            'uk-margin-{!margin_bottom: |default}-bottom',
        ] : []),
    ],
    ...(empty($props['edit_mode']) ? [
        'data-toggle' => $this->expr([
            'id: {toggle_id}; mode: {toggle_mode}; [rows: true; {@toggle_rows}]',
            ...(($props['show_link']) ? ['link: true; [position: {link_position};] [state: true; {@link_alt_state}]'] : []),
        ], $props) ?: false,
    ] : [
        'data-disabled' => true, // fs-toggle.min.js: Prevents from initializing on this element.
        'data-nosnippet' => !$children, // Prevents search engines from indexing this element when it has no children.
    ]),
]);

// Container
$container = $this->el($props['html_element'] ?: 'div', [
    'id' => $props['toggle_id'],
    'class' => [
        'fs-toggle__container',

        ...($props['show_link'] ? [
            'uk-margin[-{toggle_margin}]-top {@link_position:top|auto}{@!toggle_margin: remove}',
            'uk-margin[-{toggle_margin}]-bottom {@link_position:bottom|auto}{@!toggle_margin: remove}',
        ] : []),

        // Edit mode padding
        'uk-padding {@edit_mode}' => !$props['toggle_rows'],
        'uk-text-left', // Force left-alignment on this container only, overriding parent text alignment
    ],
    'hidden' => $props['toggle_state'] === 'hidden' && !$props['edit_mode'] && !$props['toggle_rows'],
    'data-fs-toggle-container' => true,

    // Edit Mode
    ...(empty($props['edit_mode']) ? [
        'data-fs-toggle-helper' => $props['toggle_id'] && $props['toggle_toggle_helper'],
    ] : ['data-edit-mode' => true]),
]);

// Rows (only create when row splitting is active)
if ($props['toggle_rows']) {
    $group_head = $this->el('div',
        [
            'id' => "{$props['toggle_id']}-head",
            'class' => ['fs-toggle__group', 'uk-padding {@edit_mode}'],
            'data-fs-toggle-group' => true
        ]);
    $group_tail = $this->el('div',
        [
            'id' => "{$props['toggle_id']}-tail",
            'class' => ['fs-toggle__group', 'uk-padding uk-grid-margin {@edit_mode}'],
            'data-fs-toggle-group' => true
        ]);
    if ($props['toggle_mode'] !== 'media') {
        ($props['toggle_state'] === 'visible' ? $group_head : $group_tail)->attr([
            empty($props['edit_mode']) ? 'hidden' : 'data-fs-toggle-group-hidden' => true,
        ]);
    } else {
        $group_tail->attr(['class' => ['uk-grid-margin']]);
    }
} else {
    $group_head = $group_tail = null;
}

// Mode: media
if ($props['toggle_mode'] === 'media' && !empty($props['toggle_cls'])) {
    (empty($props['toggle_rows']) ? $container : $group_tail)->attr([
        ...(empty($props['edit_mode']) ? [
            'uk-toggle' => $this->expr([
                'mode: {toggle_mode};',
                'media: @{toggle_breakpoint};',
                'cls: {toggle_cls};',
            ], $props),
        ] : ['data-cls' => $props['toggle_cls']]), // edit mode class hint display
    ]);
}

?>

<?= $el($props, $attrs) ?>

<?php if ($props['edit_mode'] === true) : ?>
    <div class="uk-alert-warning" uk-alert <?= $children ? 'data-nosnippet' : '' ?>>
        <p class="uk-text-center uk-text-small">
            The Toggle element with ID:
            <strong>'<?= htmlspecialchars($props['toggle_id'], ENT_QUOTES, 'UTF-8') ?>'</strong>
            is currently in <strong>'Edit Mode'</strong> and fully visible on the page for editing purposes.
        </p>
    </div>
<?php elseif ($props['show_link'] && in_array($props['link_position'], ['top', 'auto'], true)) : ?>
    <?= $this->render("$__dir/template-link", ['props' => $props, 'slot' => 'top']) ?>
<?php endif ?>

<?= $container($props) ?>

<?php if ($children) : ?>
    <?php if ($props['toggle_rows']) : ?>
        <?= $group_head($props) ?>
        <?php for ($i = 0; $i < $totalRows; $i++) : ?>
            <?= $i === $splitAt ? $group_head->end() . $group_tail($props) : '' ?>
            <?= $builder->render($children[$i]) ?>
        <?php endfor; ?>
        <?= $group_tail->end() ?>
    <?php else : ?>
        <?= $builder->render($children) ?>
    <?php endif; ?>
<?php else : ?>
    <p class="uk-text-center uk-text-meta uk-margin-remove">— Empty Layout —</p>
<?php endif ?>

<?= $container->end() ?>

<?php if (!$props['edit_mode'] && $props['show_link'] && in_array($props['link_position'], ['bottom', 'auto'], true)) : ?>
    <?= $this->render("$__dir/template-link", ['props' => $props, 'slot' => 'bottom']) ?>
<?php endif ?>
<?= $el->end() ?>