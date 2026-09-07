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

$sublayout = $this->el('div', ['class' => ["el-sublayout"]]);

?>

<?php $grid_position = 'item-image-cell-top'; ?>
<?php if ($element['show_sublayout'] && $element['sublayout_position'] === $grid_position && $element['sublayout_align'] === 'top') : ?>
    <?= $this->render("$__dir/template-sublayout", compact('props')) ?>
<?php endif ?>

<?= $this->render("$__dir/template-grids-positions", compact('props', 'grid_position')) ?>

<?php if ($element['show_sublayout'] && $element['sublayout_position'] === $grid_position && $element['sublayout_align'] === 'bottom') : ?>
    <?= $this->render("$__dir/template-sublayout", compact('props')) ?>
<?php endif ?>

<?= $props['image'] ?>

<?php $grid_position = 'item-image-cell-bottom'; ?>

<?php if ($element['show_sublayout'] && $element['sublayout_position'] === $grid_position && $element['sublayout_align'] === 'top') : ?>
    <?= $this->render("$__dir/template-sublayout", compact('props')) ?>
<?php endif ?>

<?= $this->render("$__dir/template-grids-positions", compact('props', 'grid_position')) ?>

<?php if ($element['show_sublayout'] && $element['sublayout_position'] === $grid_position && $element['sublayout_align'] === 'bottom') : ?>
    <?= $this->render("$__dir/template-sublayout", compact('props')) ?>
<?php endif ?>