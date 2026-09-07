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

defined('_JEXEC') or defined('ABSPATH') or die();

// Ensure these variables exist
/** @var $builder */
/** @var $children */
/** @var $element */
/** @var $props */
/** @var $i */

//Generating Modal IDs
$modal_id = 'my-modal-id';
if ($element['sublayout_mode'] === 'modal' && $element['sublayout_modal_wrap'] === 'all') {
    $modal_id = $props['link_modal_id'] ?: 'my-unique-modal-id';
} elseif (($element['sublayout_mode'] === 'mixed' && !empty($element['sublayout_modal_wrap_custom'])) || $element['sublayout_modal_wrap'] === 'each') {
    if (!empty($children[$i]->children[0]->attrs['id'])) {
        $suffix = $children[$i]->children[0]->attrs['id'];
    } else {
        $suffix = $i;
    }
    if (!empty($props['link_modal_id'])) {
        $modal_id = $props['link_modal_id'] . '-' . $suffix;
    } else {
        $modal_id = "my-unique-modal-id-" . $suffix;
    }
}

$sublayout = $this->el('div', ['class' => ['el-sublayout']]);

$modal = $this->el('div', [
    'id' => $modal_id,
    'class' => [
        'el-sublayout-modal',
        'uk-modal-container {@!sublayout_modal_width}{@!sublayout_modal_full}',
        'uk-modal-full {@sublayout_modal_full}',
        'uk-flex-top', // uk-flex-top is needed to make vertical margin work for IE11
    ],
    'uk-modal' => true,
]);

$modal_container = $this->el('div', [
    'class' => ['el-sublayout-modal-container'],
    'style' => [
        '[max-width: 100%; {@sublayout_modal_width}{@!sublayout_modal_full}]',
        '[width: {sublayout_modal_width}px; {@sublayout_modal_width}{@!sublayout_modal_full}]',
        '[height: {sublayout_modal_height}px; {@sublayout_modal_height}{@!sublayout_modal_full}]',
    ],
    'uk-overflow-auto' => $element['sublayout_modal_height'],
]);

$modal_body = $this->el('div', [
    'class' => [
        'uk-modal-body',
        'uk-padding[-{sublayout_modal_padding}]{@sublayout_modal_padding}',
    ],
]);

?>

<?= $modal($element) ?>
    <div class="uk-modal-dialog uk-width-auto uk-margin-auto-vertical">

        <?php if ($element['sublayout_modal_full']) : ?>
            <button class="uk-modal-close-full uk-close-large" type="button" uk-close></button>
        <?php else : ?>
            <?php if ($element['sublayout_mode'] === 'modal' && $element['sublayout_modal_wrap'] === 'all' && $element['sublayout_modal_header'] && $props['link_modal_header_text']) : ?>
                <button class="uk-modal-close-default" type="button" uk-close></button>
            <?php else : ?>
                <button class="uk-modal-close-outside" type="button" uk-close></button>
            <?php endif ?>
        <?php endif ?>

        <?= $modal_container($element) ?>
        <?php if ($element['sublayout_mode'] === 'modal' && $element['sublayout_modal_wrap'] === 'all' && $element['sublayout_modal_header'] && $props['link_modal_header_text']) : ?>
            <div class="uk-modal-header">
                <h2 class="uk-modal-title"><?= $props['link_modal_header_text'] ?></h2>
            </div>
        <?php endif ?>
        <?= $modal_body($element) ?>
        <?php if ($element['sublayout_mode'] === 'modal' && $element['sublayout_modal_wrap'] === 'all') : ?>
            <?= $sublayout($element) ?>
            <?= $builder->render($children) ?>
            <?= "<style>#{$modal_id} .uk-modal-body [uk-scrollspy-class] {opacity: 1!important;}</style>" ?>
            <?= $sublayout->end() ?>
        <?php elseif (($element['sublayout_mode'] === 'modal' && $element['sublayout_modal_wrap'] === 'each') || ($element['sublayout_mode'] === 'mixed' && !empty($element['sublayout_modal_wrap_custom']))) : ?>
            <?= $sublayout($element) ?>
            <?= $builder->render($children[$i]) ?>
            <?= "<style>#{$modal_id} .uk-modal-body [uk-scrollspy-class] {opacity: 1!important;}</style>" ?>
            <?= $sublayout->end() ?>
        <?php endif ?>
        <?= $modal_body->end() ?>
        <?= $modal_container->end() ?>
    </div>
<?= $modal->end() ?>