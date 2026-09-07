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
/** @var $nav */
/** @var $nav_attrs */
/** @var $dropdown */
/** @var $nav_dropdown */

$filter_horizontal = in_array($props['filter_position'], ['left', 'right']);

?>

<?php for ($i = 1; $i <= $props['custom_filters']; $i++) { ?>

    <?php $props["filter_custom_{$i}_label"] = $props["filter_custom_{$i}_label"] ?: "Filter #$i" ?>

    <?php if (${"custom_text_{$i}_tags"} && $props["show_custom_filter_$i"]): ?>
        <div class="<?= 'fs-grid-filter-' . $i ?> fs-grid-filter-custom <?= $props['filter_style'] === 'dropdown' ? 'fs-js-dropdown' : false ?>">

            <?= $nav($props, $nav_attrs) ?>

            <?php if ($props['filter_style'] === 'dropdown'): ?>
            <li class="fs-filter-state">
                <a href="#"
                   class="fs-filter-label <?= $filter_horizontal ? 'uk-width-1-1' : false ?>"
                   uk-icon="triangle-down" <?= $filter_horizontal ? 'style="justify-content: space-between;"' : false ?>>
					<span class="fs-filter-label-inner">
					<?php if ($props["filter_custom_{$i}_icon"]): ?>
                        <i uk-icon='<?= $props["filter_custom_{$i}_icon"] ?>'
                           data-fsicon='<?= $props["filter_custom_{$i}_icon"] ?>'
                           class='fs-filter-label-icon uk-margin-small-right'></i>
                    <?php endif ?>
						<span data-label='<?= $props["filter_custom_{$i}_label"] ?>' class="fs-filter-label-text">
							<?= $props["filter_custom_{$i}_label"] ?>
						</span>
					</span>
                </a>

                <?= $dropdown($props) ?>
                <?= $nav_dropdown($props) ?>
                <?php endif ?>

                <?php if ($props['filter_group_all'] && $props['filter_groups'] && $props['use_custom_fields']): ?>
            <li uk-filter-control="group: <?= "custom_text_{$i}_tags" ?>">
                <a class="fs-filter-reset <?= $props['filter_style'] === 'dropdown' ? "uk-padding-remove-top" : false ?> <?= !$props['filter_all'] ? "uk-active" : false ?>"><?= $props['filter_all_label'] . " " . $props["filter_custom_{$i}_label"] ?></a>
            </li>
        <?php endif ?>

            <?php $first = key(${"custom_text_{$i}_tags"}) ?>
            <?php foreach (${"custom_text_{$i}_tags"} as $tag => $name): ?>

                <li <?= $this->attrs([
                    'class' => [
                        'uk-active' => $tag === array_key_first(${"custom_text_{$i}_tags"}) && !$props['filter_all'] && !$props['filter_group_all'] && $props['filter_groups_mode'] !== 'match'
                    ],
                    'uk-filter-control' => $props['filter_groups_mode'] !== 'match' ? json_encode([
                        'filter' => '[data-tag-' . $i . '~="' . str_replace('"', '\"', $tag) . '"]',
                        'group' => 'custom_text_' . $i . '_tags'
                    ]) : json_encode(['filter' => '[data-tag-' . $i . '~="' . str_replace('"', '\"', $tag) . '"]'])
                ]) ?>>

                    <?php $filter_link = ""; ?>
                    <?php if ($props['filter'] && $props['filter_url_hash']) : ?>
                        <?php if (function_exists('mb_strtolower')) : ?>
                            <?php $filter_link = mb_strtolower($tag, 'UTF-8'); ?>
                        <?php else : ?>
                            <?php $filter_link = strtolower($tag); ?>
                        <?php endif; ?>
                        <?php $filter_link = "#" . htmlspecialchars(preg_replace('/[^a-zA-Z0-9_\p{L}\p{M}\-]/u', '',
                                $filter_link), ENT_QUOTES); ?>
                    <?php else : ?>
                        <?php $filter_link = "#"; ?>
                    <?php endif; ?>

                    <a href="<?= $filter_link ?>"
                       class="<?= $props['filter_style'] === 'dropdown' ? "uk-padding-remove-top" : false ?>">
                        <?= $name ?>
                    </a>

                </li>
            <?php endforeach ?>

            <?php if ($props['filter_style'] === 'dropdown'): ?>
                <?= $nav_dropdown->end() ?>
                <?= $dropdown->end() ?>
                </li>
            <?php endif ?>

            <?= $nav->end() ?>
        </div>
    <?php endif ?>

<?php } ?>