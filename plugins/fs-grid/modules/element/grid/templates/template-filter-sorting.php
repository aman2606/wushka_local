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

$filter_sorting = $this->el('div', [

    'class' => [
        'fs-grid-filter-sort',
        'uk-flex uk-flex-middle uk-flex-center',
        'uk-width-1-4 uk-width-1-3@m {@search}',
        'uk-width-1-1 {@!search}',
        'uk-text-nowrap',
        'uk-text-center {@search}',
        'uk-text-right {@!search}',
        '{filter_sorting_visibility}{@filter_sorting_visibility}',
    ],

]);

?>

<?= $filter_sorting($props) ?>

<?php
$fsd = $props['filter_sorting_order'] ?: 'asc';
$fsk = $props['filter_sorting_key'] ?: 'tag';
if ($fsk && $fsk !== 'tag' && $props["show_custom_filter_$fsk"]) {
    $fsk = "tag-$fsk";
} elseif ($fsk && $fsk !== 'tag' && !$props["show_custom_filter_$fsk"]) {
    $fsk = "tag";
    echo("<noindex><script>console.log('GRID PRO: Filter group that is selected as a filter sorting key is not enabled. Filter group -:tags:- will be used as fallback.');</script></noindex>");
}
?>

    <span class="<?= $fsd === 'desc' ? 'uk-active' : false ?>" uk-filter-control="sort: data-<?= $fsk ?>">
		<a class="uk-icon-link" href="#" uk-icon="icon: arrow-down"></a>
	</span>
    <span class="<?= $fsd === 'asc' ? 'uk-active' : false ?>" uk-filter-control="sort: data-<?= $fsk ?>; order: desc">
		<a class="uk-icon-link" href="#" uk-icon="icon: arrow-up"></a>
	</span>

<?= $filter_sorting->end() ?>