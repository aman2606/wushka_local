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
/** @var $props */

$fields = [
    'image', 'title', 'text_1', 'text_2', 'text_3', 'text_4', 'text_5', 'text_6', 'text_7', 'text_8', 'text_9', 'text_10',
    'text_11', 'text_12', 'text_13', 'text_14', 'text_15', 'text_16', 'text_17', 'text_18', 'text_19', 'text_20', 'link'
];

// Detect fields that are used at least once in children
$filtered = array_values(array_filter($fields,
    static fn($field) => array_any($children, fn($child) => $child->props[$field] !== '')
));

?>

<?php if ($children): ?>
    <table>
        <?php if (array_any($filtered, fn($f) => $props["table_head_$f"])): ?>
            <thead>
            <tr>
                <?php foreach ($filtered as $f): ?>
                    <th><?= $props["table_head_$f"] ?></th>
                <?php endforeach ?>
            </tr>
            </thead>
        <?php endif ?>
        <tbody>
        <?php foreach ($children as $child): ?>
            <tr><?= $builder->render($child, ['element' => $props, 'filtered' => $filtered]) ?></tr>
        <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>