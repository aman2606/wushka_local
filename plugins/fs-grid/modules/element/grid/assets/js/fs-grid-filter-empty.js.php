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
    UIkit.util.ready(() => {
        const { $$, on } = UIkit.util;
        const filterEmptyContainer = document.querySelector(<?=json_encode(".{$props['search_id']}")?>);
    const filterAlertElement = filterEmptyContainer.querySelector(<?=json_encode(".{$props['search_id']} .fs-search-empty")?>);
    const filter = [...document.querySelectorAll('div.fs-grid[uk-filter]')]
        .find(instance => instance.querySelector(<?=json_encode("#{$props['filter_id']}")?>));

    // Helper function to count visible items
    const countVisibleItems = (selector) => {
        const items = $$(selector);
        return items.filter(item => getComputedStyle(item).display !== 'none').length;
    };

    const filterCount = () => {
        const match = countVisibleItems(<?=json_encode("#{$props['filter_id']} .fs-load-more-item")?>);

        // Update the UI based on the count of visible items
        filterEmptyContainer.classList.toggle('uk-hidden', match > 0);
        filterAlertElement.innerHTML = match === 0 ? <?=json_encode($props['filter_no_items_placeholder'])?> : '';
        
        // Trigger UIkit update
        UIkit.update(document.body, 'update');
    };

    // Initialize filter count
    filterCount();

    // Recalculate filter count when shown or after filtering
    on(filter, 'shown afterFilter', filterCount);
});
//-->
</script>
