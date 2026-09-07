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
/** @var $grid_position */
/** @var $__dir */

//Exit if custom fields are not enabled
if (!$element['use_custom_fields']) {
    return;
}

?>

<?php for ($g = 1; $g <= $element['custom_grids']; $g++) : ?>
    <?php $element["grid_{$g}_custom_position"] = $props["grid_{$g}_custom_item_position"] ?: $element["grid_{$g}_custom_position"]; ?>
    <?php if ($element["grid_{$g}_custom_position"] === $grid_position): ?>
        <?= $this->render("$__dir/template-grids", ['props', 'grid_position', 'grid' => $g]) ?>
        <?php break; ?>
    <?php endif ?>
<?php endfor ?>