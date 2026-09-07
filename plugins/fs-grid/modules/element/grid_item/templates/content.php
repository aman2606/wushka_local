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

/** @noinspection DuplicatedCode, HtmlUnknownTarget */

defined('_JEXEC') or defined('ABSPATH') or die();

// Ensure these variables exist
/** @var $element */
/** @var $props */

?>

<?php if ($props['image']) : ?>
    <img src="<?= $props['image'] ?>" alt="<?= $props['image_alt'] ?>">
<?php endif ?>

<?php if ($props['title']) : ?>
    <<?= $element['title_element'] ?>><?= $props['title'] ?></<?= $element['title_element'] ?>>
<?php endif ?>

<?php if ($props['meta']) : ?>
    <p><?= $props['meta'] ?></p>
<?php endif ?>

<?php
$e = static fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
foreach (range(1, (int)$element['custom_field_sets']) as $f) :
    $img = $e($props["custom_image_$f"] ?? '');
    $meta = $e($props["custom_meta_$f"] ?? '');
    $text = $e($props["custom_text_$f"] ?? '');
    $link = $e($props["custom_link_$f"] ?? '');

    if (!($img || $meta || $text)) {
        continue;
    } ?>

    <div class="custom-fieldset">
        <?= $link ? sprintf('<a href="%s">', $link) : '' ?>
        <?= $img ? sprintf('<img src="%s" alt="" loading="lazy">', $img) : '' ?>
        <?= $meta ? sprintf('<p class="custom-meta">%s</p>', $meta) : '' ?>
        <?= $text ? sprintf('<p class="custom-text">%s</p>', $text) : '' ?>
        <?= $link ? '</a>' : '' ?>
    </div>
<?php endforeach; ?>

<?php if ($props['content']) : ?>
    <div><?= $props['content'] ?></div>
<?php endif ?>

<?php if ($props['link']) : ?>
    <p><a href="<?= $props['link'] ?>"><?= $props['link_text'] ?: $element['link_text'] ?></a></p>
<?php endif ?>