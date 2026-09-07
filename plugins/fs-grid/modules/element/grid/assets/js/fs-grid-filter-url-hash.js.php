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
        const navId = <?=json_encode($props['nav_id']);?>;
	const doScroll = <?=json_encode($props['filter_url_hash_scroll']);?>;
	const filterStyle = <?=json_encode($props['filter_style']);?>;
	const filterDropdowns = document.querySelectorAll(<?=json_encode("#{$props['nav_id']} .fs-js-dropdown")?>);
	const filterSelectedIcon = <?=json_encode($props['filter_selected_icon'])?>;
	const gridProCustomNav	= document.querySelectorAll(".fs-grid-pro-toggle");

	let viewed = false;
	let intervalId;

    intervalId = setInterval(() => {
        if (!document.hidden && !viewed) {
            clearInterval(intervalId);
            viewed = true;

            if (document.location.hash) {
                const urlHashes = decodeURI(document.location.hash);
                const segments = urlHashes.split(';');

                segments.forEach((str) => {
                    str = str.replace('#', '');
                    let arr = str.split('|');

                    if (arr.length === 1) {
                        let navLink = document.querySelector('#' + navId + ' a[href="#' + arr + '" i]');
                        if (navLink) {
                            navLink.click();
                            if (filterStyle === 'dropdown') {
                                filterDropdowns.forEach((element) => {
                                    let link = element.querySelector('a[href="#' + arr + '"] i');
                                    if (link) {
                                        const label = element.querySelector('span.fs-filter-label-text');
                                        const icon = element.querySelector('i.fs-filter-label-icon');
                                        const active = element.querySelector('li.fs-filter-state');

                                        label.innerHTML = navLink.innerHTML;
                                        icon?.setAttribute('uk-icon', filterSelectedIcon);
                                        active.classList.add('uk-active');
                                    }
                                });
                            }
                            if (doScroll) {
                                UIkit.scroll('', {offset: 30}).scrollTo(document.getElementById(navId));
                            }
                        }
                    } else if (arr.length > 1 && navId === arr[0]) {
                        arr = arr.filter((item) => item !== navId);
                        arr.forEach((hash) => {
                            let navLink = document.querySelector('#' + navId + ' a[href="#' + hash + '" i]');
                            if (navLink) {
								setTimeout(() => {
								  navLink.click();
								}, "250");
                                if (filterStyle === 'dropdown') {
                                    filterDropdowns.forEach((element) => {
                                        let link = element.querySelector('a[href="#' + hash + '" i]');
                                        if (link) {
                                            const label = element.querySelector('span.fs-filter-label-text');
                                            const icon = element.querySelector('i.fs-filter-label-icon');
                                            const active = element.querySelector('li.fs-filter-state');

                                            label.innerHTML = navLink.innerHTML;
                                            icon?.setAttribute('uk-icon', filterSelectedIcon);
                                            active.classList.add('uk-active');
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            }

            for (const customNav of gridProCustomNav) {
                const links = customNav.querySelectorAll("a");

                // set uk-active class if url hash
                if (document.location.hash && customNav.querySelector('a[href="' + document.location.hash + '" i]')) {
                    customNav.querySelector('a[href="' + document.location.hash + '" i]').classList.add('uk-active');
                }

                for (const link of links) {
                    link.removeAttribute('uk-scroll');
                    link.addEventListener("click", function (e) {
                        let href = decodeURI(link.href.split("#")[1]);

                        // checking if the custom nav has an id to connect to a selected grid pro instance
                        if (customNav.hasAttribute('id') && document.querySelector('#fs-' + customNav.getAttribute('id'))) {
                            for (const l of customNav.querySelectorAll("a")) {
                                l.classList.remove('uk-active');
                                if (l.href == link.href) {
                                    l.classList.add('uk-active');
                                }
                                link.classList.add('uk-active');
                            }

                            const element = '#fs-' + customNav.getAttribute('id');
                            const target = document.querySelector(element + ' a[href="#' + href + '" i]');
                            if (target) {
                                target.click();
                                if (doScroll && (!customNav.classList.contains("fs-no-scroll") && !link.classList.contains("fs-no-scroll"))) {
									UIkit.scroll('', {offset: 30}).scrollTo(document.querySelector(element));
                                }
                            }
                        } else {
                            for (const customNav of gridProCustomNav) {
                                for (const l of customNav.querySelectorAll("a")) {
                                    l.classList.remove('uk-active');
                                    if (l.href == link.href) {
                                        l.classList.add('uk-active');
                                    }
                                    link.classList.add('uk-active');
                                }
                            }

                            const target = document.querySelector('#' + navId + ' a[href="#' + href + '" i]');
                            if (target) {
                                target.click();
                                if (doScroll && (!customNav.classList.contains("fs-no-scroll") && !link.classList.contains("fs-no-scroll"))) {
									UIkit.scroll('', {offset: 30}).scrollTo(document.getElementById(navId));
                                }
                            }
                        }
                    });
                }
            }
        }
    }, 1000);
});
//-->
</script>