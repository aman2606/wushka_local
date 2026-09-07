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

defined('_JEXEC') or defined('ABSPATH') or die();

// Ensure these variables exist
/** @var $props */

$el = $this->el('th', [
    'class' => [
        'fs-thead-column',
        'fs-thead-column-counter',
        'th-counter',
        'no-sort {@enable_datatables}{@!table_datatables_sorting_counter}',
        'uk-text-center',
        '{counter_visibility}{@counter_visibility}',
    ],
]);

?>

<?= $el($props, $props['table_head_counter']) ?>