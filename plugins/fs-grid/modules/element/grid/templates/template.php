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
/** @var $props */
/** @var $attrs */
/** @var $tags */
/** @var $__dir */

// Resets
if ($props['panel_link']) {
    $props['title_link'] = '';
    $props['image_link'] = '';
}

$el = $this->el('div', [
    'class' => [
        'fs-grid',

        // YOOtheme Pro 5 Margins
        ...(($props['_yootheme_v4'] ?? false) === true ? [
            'uk-margin-top {@margin_top: default}',
            'uk-margin-{!margin_top: |default}-top',
            'uk-margin-bottom {@margin_bottom: default}',
            'uk-margin-{!margin_bottom: |default}-bottom'
        ] : []),
    ],
    'uk-filter' => $tags ? [
        'target: .js-filter;',
        'animation: {filter_animation};',
    ] : false,
]);

?>

<?= $el($props, $attrs) ?>

<?php if ($props['slider'] && (!$props['filter'] && !$tags)): ?>
    <?= $this->render("$__dir/template-slider") ?>
<?php elseif ($props['filter'] && $tags): ?>
    <?= $this->render("$__dir/template-filter") ?>
<?php else: ?>
    <?= $this->render("$__dir/template-content") ?>
<?php endif ?>

<?= $el->end() ?>