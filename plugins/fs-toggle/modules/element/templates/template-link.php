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

defined('_JEXEC') or defined('ABSPATH') or die();

// Ensure these variables exist
/** @var $props */
/** @var $slot */

// Shortcuts (link_alt_state is already computed in template.php, no duplicate needed)
$props['is_button_hidden'] = $props['link_position'] === 'auto' && $slot === 'bottom';

// Fallbacks
if (empty($props['link_text'])) {
    $props['link_text'] = $props['link_icon'] ? '' : 'Toggle Content';
    $props['link_aria_label'] = $props['link_aria_label'] ?: 'Toggle Content';
}

// Container
$link_container = $this->el('div', [
    'class' => [
        'fs-toggle__control',
        "fs-toggle__control--$slot {@link_position: auto}",
        'uk-flex uk-flex-{link_align}[@{link_align_breakpoint} [uk-flex-{link_align_fallback}]] {@!link_width:1-1}',
        'uk-hidden' => $props['is_button_hidden'],
    ],
]);

// Link
$link = $this->el('a', [
    'href' => '#',
    'id' => ["{$props['toggle_id']}-link[-$slot{@link_position: auto}]"],
    'class' => $this->expr([
        'fs-toggle__link',
        'fs-toggle__link--interactive {@link_alt_state}',
        'uk-width-{link_width}',
        'uk-{link_style: link-\w+}' => ['link_style' => $props['link_style']],
        'uk-button uk-button-{!link_style: |link-\w+} [uk-button-{link_size}]' => ['link_style' => $props['link_style']],
    ], $props),
    'uk-toggle' => $this->expr([
        $props['toggle_rows'] ? 'target: #{toggle_id} > .fs-toggle__group;' : 'target: #{toggle_id};',
        'mode: {toggle_mode};',
        'animation: uk-animation-{toggle_animation};',
    ], $props),
    'title' => ['{link_title}'],
    'aria-label' => ['{link_aria_label}{@!link_text}'],
    'aria-expanded' => $props['toggle_state'] === 'hidden' ? 'false' : 'true',
    'aria-controls' => ['{aria_controls}'],
    'data-fs-toggle-link' => $props['toggle_id'],
    'data-fs-toggle-hidden' => $props['is_button_hidden'],
    'type' => 'button',
]);

// Custom click handler (safe try/catch wrapper)
if ($props['link_onclick']) {
    $props['link_onclick'] = htmlspecialchars(trim($props['link_onclick']), ENT_QUOTES, 'UTF-8');
    $link->attr(['onclick' => "try{{$props['link_onclick']}}catch(e){console.warn('Toggle element: onclick handler failed.', e)};return true;"]);
}

// Text
$link_text = !empty($props['link_text']) ? $this->el('span', [
    'class' => ['fs-toggle__link-text'],
    'data-fs-toggle-state' => $this->expr(['true {@link_text}{@link_text_alt}{@link_alt_state}'], $props) ?: false,
]) : null;

// Icon
$icon = $props['link_icon'] ? $this->el('span', [
    'class' => [
        'fs-toggle__link-icon',
        'uk-margin-small-[left {@link_icon_align:right}][right {@link_icon_align:left}] {@link_text}',
    ],
    'data-fs-toggle-state' => $this->expr(['true {@link_icon}{@link_icon_alt}{@link_alt_state}'], $props) ?: false,
]) : null;

// Render the icon block based on alignment
$renderIcon = $icon ? static function ($alignment) use ($props, $icon) {
    if ($props['link_icon_align'] === $alignment) {
        echo $icon($props, ['uk-icon' => $props['link_icon']], '');

        if ($props['link_icon_alt'] && $props['link_alt_state']) {
            echo $icon($props,
                ['class' => ['fs-toggle__link-state--alt uk-hidden'], 'uk-icon' => $props['link_icon_alt']], '');
        }
    }
} : null;

?>

<?= $link_container($props) ?>
<?= $link($props) ?>

<?php $icon && $renderIcon('left'); ?>

<?php if ($link_text) : ?>
    <?= $link_text($props, $props['link_text']) ?>
    <?php if ($props['link_text_alt'] && $props['link_alt_state']) : ?>
        <?= $link_text($props, ['class' => ['fs-toggle__link-state--alt uk-hidden']], $props['link_text_alt']) ?>
    <?php endif ?>
<?php endif ?>

<?php $icon && $renderIcon('right'); ?>

<?= $link->end() ?>
<?= $link_container->end() ?>