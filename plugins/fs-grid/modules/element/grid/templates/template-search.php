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
/** @var $tags */
/** @var $__div */

$props['search_input_placeholder'] = $props['search_input_placeholder'] ?: 'Search...';
$props['search_no_items_placeholder'] = $props['search_no_items_placeholder'] ?: 'No items found matching keyword:';

$search_grid = $this->el('div', [
    'class' => [
        'uk-width-1-4@m',
        'uk-width-1-1',
        'uk-margin-auto-left@m',
        'uk-grid-small uk-grid-margin-small',
        'uk-grid-divider',
        'uk-margin',
    ],
    'uk-grid' => true,
]);

$search = $this->el('div', [
    'class' => [
        'fs-grid-filter-search',
        'uk-width-3-4 uk-width-2-3@m {@filter}{@filter_sorting}',
        'uk-width-1-1 {@!filter}',
        'uk-width-1-1 {@!filter_sorting}',
    ],
]);

?>

<?php if (!$props['filter'] || !$tags): ?>
    <?= $search_grid($props) ?>
<?php endif ?>

<?= $search($props) ?>

    <span class="uk-search uk-width-1-1 uk-search-default uk-inline">
	<span uk-search-icon></span>
		<input id="<?= $props['search_id'] ?>" class="uk-search-input" type="search" name="fs-grid-search"
               placeholder="<?= $props['search_input_placeholder'] ?>"
               aria-label="<?= $props['search_input_placeholder'] ?>" style="padding-left:40px;">
</span>

<?php include __DIR__ . "/../assets/js/fs-grid-filter-search.js.php"; ?>

<?= $search->end() ?>

<?php if (!$props['filter'] || !$tags): ?>
    <?= $search_grid->end() ?>
<?php endif ?>