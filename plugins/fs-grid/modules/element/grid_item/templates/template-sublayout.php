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
/** @var $builder */
/** @var $children */
/** @var $element */
/** @var $props */
/** @var $__dir */

$i = 0;
$modal_wrap_custom = false;

$sublayout = $this->el('div', [
    'class' => [
        "el-sublayout",
        'uk-margin[-{sublayout_margin}]-top {@!sublayout_margin: remove} {@sublayout_mode: native|mixed}',
        'uk-margin-remove-top {@sublayout_margin: remove} {@sublayout_mode: native|mixed}',
        'uk-margin-remove-bottom',
    ],
]);

if ($element['sublayout_mode'] === 'mixed' && !empty($element['sublayout_modal_wrap_custom'])) {
    $max = (count($children));
    $check = explode(',', preg_replace('/\s+/', '', strip_tags(trim($element['sublayout_modal_wrap_custom']))));
    $check = array_filter(array_diff($check, [0]), static function ($x) use ($max) {
        return $x <= $max;
    });

    if ($diff = array_diff_key($check, array_filter($check, 'ctype_digit'))) {
        foreach ($diff as $failIndex => $failValue) {
            $modal_wrap_custom = false; // at least one is not a digit
        }
    } else {
        $sublayouts = [];
        foreach ($children as $i => $child) {
            if (!in_array(($i + 1), $check)) {
                array_push($sublayouts, ($i + 1));
            }
        }
        $modal_wrap_custom = $check;
    }
}

?>

<?php if ($children) : ?>
    <?php if ($children[0]->children) : ?>
        <?php if ($element['sublayout_mode'] === 'native') : ?>

            <?= $sublayout($element) ?>
            <?= $builder->render($children) ?>
            <?= $sublayout->end() ?>

        <?php elseif ($element['sublayout_mode'] === 'modal') : ?>

            <?php if ($element['sublayout_modal_wrap'] === 'each') : ?>
                <?php foreach ($children as $i => $child) : ?>
                    <?= $this->render("$__dir/template-sublayout-modal", compact('props', 'i')) ?>
                <?php endforeach ?>
            <?php else : ?>
                <?= $this->render("$__dir/template-sublayout-modal", compact('props', 'i')) ?>
            <?php endif ?>

        <?php elseif ($element['sublayout_mode'] === 'mixed' && is_array($modal_wrap_custom)) : ?>

            <?php if (!empty($modal_wrap_custom)) : ?>
                <?php foreach ($modal_wrap_custom as $child) : ?>
                    <?php $i = ($child - 1); ?>
                    <?= $this->render("$__dir/template-sublayout-modal", compact('props', 'i')) ?>
                <?php endforeach ?>
            <?php endif ?>

            <?php if (!empty($sublayouts)) : ?>
                <?php foreach ($sublayouts as $item) : ?>
                    <?= $sublayout($element) ?>
                    <?= $builder->render($children[($item - 1)]) ?>
                    <?= $sublayout->end() ?>
                <?php endforeach ?>
            <?php endif ?>

        <?php endif ?>
    <?php endif ?>
<?php endif ?>