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
/** @var $element */
/** @var $props */
/** @var $filtered */

?>

<?php foreach ($filtered as $field): ?>
    <td>
        <?php switch ($field):
            case 'image':
                if (!empty($props['image'])): ?>
                    <img src="<?= $props['image'] ?>"
                         alt="<?= $props['image_alt'] ?? '' ?>"
                         title="<?= $props['image_title'] ?? '' ?>">
                <?php endif;
                break;
            case 'title':
                echo $props['title'] ?? '';
                if (!empty($props['description'])) {
                    echo '<br>' . $props['description'];
                }
                break;
            case 'link':
                if (!empty($props['link'])) {
                    // Avoid undefined index and ensure fallback works correctly
                    $linkText = $props['link_text'] ?: ($element['link_text'] ?? 'Link');
                    echo '<a href="' . $props['link'] . '">' . $linkText . '</a>';
                }
                break;
            default:
                // Handles text_1 ... text_20 generically
                echo $props[$field] ?? '';
        endswitch; ?>
    </td>
<?php endforeach; ?>