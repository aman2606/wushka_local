<?php
/**
 * @package   Grid Pro element for YOOtheme Pro
 * @author    Flart Studio https://www.flart.studio
 * @copyright Copyright (C) Flart Studio
 * @license   GNU General Public License version 3, or later
 */

// No direct access to this file
defined('_JEXEC') or defined('ABSPATH') or die();

?>

<script>
    <!--
    UIkit.util.ready(function() {
        const filterGroups = <?=json_encode($props['filter_groups'])?>;
	const filterMode = <?=json_encode($props['filter_groups_mode'])?>;
	const filterAll = document.querySelector(<?=json_encode("#{$props['nav_id']} .fs-grid-filter-all")?>);
	const filterDropdowns = document.querySelectorAll(<?=json_encode("#{$props['nav_id']} .fs-js-dropdown")?>);
	const filterSelectedIcon = <?=json_encode($props['filter_selected_icon'])?>;

	filterDropdowns.forEach((element) => {
		let items = element.querySelectorAll('.uk-dropdown-nav > li > a');

		items.forEach((item) => {
			item.onclick = function() {
			if (!item.classList.contains('fs-filter-reset')) {
				if (filterGroups && filterMode == 'match') {
					filterDropdowns.forEach((element) => {
						let label = element.querySelector('.fs-filter-label span.fs-filter-label-text');
						let icon = element.querySelector('.fs-filter-label i.fs-filter-label-icon');
						let active = element.querySelector('li.fs-filter-state');

						active.classList.remove('uk-active');
						label.innerHTML = label.dataset.label;
						icon?.setAttribute('uk-icon', icon.dataset.fsicon);
					});
				}

				let label = element.querySelector('.fs-filter-label span.fs-filter-label-text');
				let icon = element.querySelector('.fs-filter-label i.fs-filter-label-icon');
				let active = element.querySelector('li.fs-filter-state');

				active.classList.add('uk-active');
				label.innerHTML = item.textContent;
				icon?.setAttribute('uk-icon', filterSelectedIcon);
			} else {
				let label = element.querySelector('.fs-filter-label span.fs-filter-label-text');
				let icon = element.querySelector('.fs-filter-label i.fs-filter-label-icon');
				let active = element.querySelector('li.fs-filter-state');

				active.classList.remove('uk-active');
				label.innerHTML = label.dataset.label;
				icon?.setAttribute('uk-icon', icon.dataset.fsicon);
			}
			};
		});
	});

	// Reset Active Filters
	if (filterAll) {
		filterAll.addEventListener('click', function() {
			filterDropdowns.forEach((element) => {
				let label = element.querySelector('.fs-filter-label span.fs-filter-label-text');
				let icon = element.querySelector('.fs-filter-label i.fs-filter-label-icon');
				let active = element.querySelector('li.fs-filter-state');

				active.classList.remove('uk-active');
				label.innerHTML = label.dataset.label;
				icon?.setAttribute('uk-icon', icon.dataset.fsicon);
			});
		}, false);
	}
});
//-->
</script>
