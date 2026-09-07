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

    const items = [...document.querySelectorAll( <?= json_encode("#{$props['filter_id']} > .fs-load-more-item") ?> )];
    const searchBar = document.getElementById( <?= json_encode($props['search_id']) ?> );
    const mark = <?= json_encode($props['search_highlight']) ?> ;
    const normalize = <?= json_encode(!empty($props['search_diacritics']) ? true : false) ?> ;

    const searchEmptyContainer = document.querySelector( <?= json_encode("div.{$props['search_id']}") ?> );
    const searchAlertElement = document.querySelector( <?= json_encode(".{$props['search_id']} .fs-search-empty") ?> );

    if (mark) {
        var markInstance = new Mark(document.querySelectorAll( <?= json_encode("#{$props['filter_id']} .fs-search-mark") ?> ));
    }

    function getInput(e) {
        return e.target.value.replace(/[`~!@#$%^&*()_|+\=?;:'"<>\{\}\[\]\\\/]/gi, '');;
    }

    searchBar.addEventListener("input", (e) => {
        let userInput = getInput(e);
        userInput = normalize ? userInput.normalize("NFD").replace(/\p{Diacritic}/gu, "").replace(/ß/g, "ss") : userInput;

        let searchQuery = [userInput.toLowerCase()];

        if (mark) {
            const options = {
                separateWordSearch: false,
                diacritics: normalize
            };
            markInstance.unmark({
                done: function() {
                    markInstance.mark(searchQuery, options);
                }
            });
        }

        const matchingItems = items.filter((item) => {
            const search = normalize ? item.dataset.search.normalize("NFD").replace(/\p{Diacritic}/gu, "").replace(/ß/g, "ss") : item.dataset.search;
            return search?.toLowerCase().includes(searchQuery);
        });

        const nonMatchingItems = items.filter((item) => {
            return !item.dataset.search?.toLowerCase().includes(searchQuery);
        });

        nonMatchingItems.forEach((item) => {
            if (item.style.display !== "none") {
                item.classList.add("uk-hidden");
                item.style.display = "none";
                item.setAttribute("aria-hidden", "true");
            }
        });

        matchingItems.forEach((item) => {
            if (item.classList.value.includes("uk-hidden")) {
                item.classList.remove("uk-hidden");
                item.style.display = "";
                item.removeAttribute("aria-hidden");
            }
        });

        if (matchingItems.length === 0) {
            searchEmptyContainer.classList.remove("uk-hidden");
            const noItemsText = <?= json_encode($props['search_no_items_placeholder']) ?> ;
            searchAlertElement.innerHTML = noItemsText + "<strong> " + userInput + " </strong>";
        } else {
            searchEmptyContainer.classList.add("uk-hidden");
            searchAlertElement.innerHTML = "";
        }

        if (searchQuery == '') {
            document.querySelector( <?= json_encode("#{$props['nav_id']} .fs-filter-label-all") ?> )?.click();
        }

        UIkit.update(document.body, "update");

    });

});
//-->
</script>