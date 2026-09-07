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

/** @noinspection NestedTernaryOperatorInspection, DuplicatedCode */

namespace YOOtheme;

defined('_JEXEC') or defined('ABSPATH') or die();

// Include the updates.php file to define the $updates array
include_once Path::get('./updates.php', __DIR__);

use FlartStudio\YOOtheme\Grid\NodePropsHelper;

return [
    'transforms' => [
        'render' => static function ($node) {
            $node->props['uid'] = 'js-' . substr(md5(uniqid(mt_rand(), true)), -5);
            $node->props['filter_id'] = 'js-' . substr(md5(uniqid(mt_rand(), true)), -5);

            if (isset($node->props['filter_nav_id']) && $node->props['filter_nav_id'] !== '' && $node->props['filter_url_hash']) {
                $node->props['nav_id'] = $node->props['filter_nav_id'];
            } elseif (isset($node->attrs['data-id']) && $node->attrs['data-id'] !== '') {
                $node->props['nav_id'] = 'fs-nav-' . substr(md5($node->attrs['data-id']), -5);
            } else {
                $node->props['nav_id'] = 'fs-nav-' . substr(md5(uniqid(mt_rand(), true)), -5);
            }

            $node->props['search_id'] = 'js-' . substr(md5(uniqid(mt_rand(), true)), -5);
            $node->props['custom_field_sets'] = 20;
            $node->props['custom_grids'] = 6;
            $node->props['custom_filters'] = 20;

            //Keyword highlight
            if ($node->props['search'] && $node->props['search_highlight']) {
                app(Metadata::class)->set('script:fs-mark',
                    ['src' => Path::get('./assets/js/mark.min.js?v=9.0.0', __DIR__), 'defer' => true]);
            }

            // Sorting
            $order_key = $node->props['order_key'];
            if (!empty($order_key) && $order_key !== 'random') {
                usort($node->children, static function ($a, $b) use ($order_key) {
                    if (!isset($a->props[$order_key])) {
                        return 1;
                    }
                    if (!isset($b->props[$order_key])) {
                        return -1;
                    }
                    if (!isset($a->props[$order_key]) && !isset($b->props[$order_key])) {
                        return -2;
                    }
                    return
                        strtolower(preg_replace('/\s+/', '-', strip_tags(trim($a->props[$order_key]))))
                        <=>
                        strtolower(preg_replace('/\s+/', '-', strip_tags(trim($b->props[$order_key]))));
                });

                if ($node->props['order_reverse']) {
                    $node->children = array_reverse($node->children, true);
                }
            } elseif (!empty($order_key) && ($order_key === 'random')) {
                shuffle($node->children);
            }

            // Limit Items
            $limit = $node->props['limit_items'] ?: 0;
            $node->children = $limit > 0 ? array_slice($node->children, 0, $limit) : $node->children;

            //Sublayouts
            if ($node->props['sublayout_mode'] === 'modal') {
                $node->props['sublayout_position'] = 'item-bottom-outside';
            }

            // Filter tags
            $node->tags = [];

            // Initialize custom filter tags if filter_groups are present
            if (!empty($node->props['filter_groups'])) {
                for ($i = 1; $i <= $node->props['custom_filters']; $i++) {
                    $node->{"custom_text_{$i}_tags"} = [];
                }
            }

            if (!empty($node->props['filter'])) {
                foreach ($node->children as $child) {
                    $child->tags = [];

                    // Initialize custom filter tags for each child if filter_groups are present
                    if (!empty($node->props['filter_groups'])) {
                        for ($i = 1; $i <= $node->props['custom_filters']; $i++) {
                            $child->{"custom_text_{$i}_tags"} = [];
                        }
                    }

                    // Process child tags
                    if (!empty($child->props['tags'])) {
                        foreach (explode(',', $child->props['tags']) as $tag) {
                            $tag = strip_tags($tag);  // Strip tags as a precaution
                            if (!empty($node->props['filter_alphaindex'])) {
                                // Alphabetic index mapping for tags
                                $mapping = [
                                    'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE',
                                    'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I',
                                    'Î' => 'I', 'Ï' => 'I', 'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
                                    'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U',
                                    'Ý' => 'Y', 'ß' => 'ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
                                    'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
                                    'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'd', 'ñ' => 'n', 'ò' => 'o',
                                    'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u',
                                    'û' => 'u', 'ü' => 'u', 'ý' => 'y', 'ÿ' => 'y'
                                ];
                                $tag = preg_replace('/\p{M}/u', '', strtr(mb_substr($tag, 0, 1), $mapping));
                            }
                            $key = str_replace(' ', '-', trim($tag));
                            if ($key) {
                                $child->tags[$key] = trim($tag);
                            }
                        }
                    }

                    // Merge child tags into node tags
                    $node->tags += $child->tags;

                    // Custom Filters
                    if (!empty($node->props['use_custom_fields']) && !empty($node->props['filter_groups'])) {
                        for ($i = 1; $i <= $node->props['custom_filters']; $i++) {
                            if (!empty($node->props["show_custom_filter_$i"]) && !empty($node->props["show_custom_$i"]) && !empty($child->props["custom_text_$i"])) {
                                foreach (explode(',', $child->props["custom_text_$i"]) as $custom_tag) {
                                    $custom_tag = strip_tags($custom_tag);  // Strip tags
                                    $key = str_replace(' ', '-', trim($custom_tag));
                                    if ($key) {
                                        $child->{"custom_text_{$i}_tags"}[$key] = trim($custom_tag);
                                    }
                                }
                                $node->{"custom_text_{$i}_tags"} += $child->{"custom_text_{$i}_tags"};

                                // Handle manual ordering of custom tags
                                if ($node->props['filter_order'] === 'manual' && !empty($node->props["filter_order_manual_$i"])) {
                                    $order = array_map('strtolower',
                                        array_map('trim', explode(',', $node->props["filter_order_manual_$i"])));
                                    uasort($node->{"custom_text_{$i}_tags"}, static function ($a, $b) use ($order) {
                                        $iA = array_search(strtolower($a), $order, true);
                                        $iB = array_search(strtolower($b), $order, true);
                                        return $iA !== false && $iB !== false ? $iA - $iB : ($iA !== false ? -1 : ($iB !== false ? 1 : strnatcmp($a,
                                            $b)));
                                    });
                                } elseif ($node->props['filter_order'] !== 'manual' && $node->props['filter_order'] !== 'disable') {
                                    natsort($node->{"custom_text_{$i}_tags"});
                                }

                                // Reverse order if required
                                if (!empty($node->props['filter_reverse']) && $node->props['filter_order'] !== 'disable') {
                                    $node->{"custom_text_{$i}_tags"} = array_reverse($node->{"custom_text_{$i}_tags"}, true);
                                }
                            }
                        }
                    }
                }

                // Sort main tags
                if ($node->props['filter_order'] === 'manual' && !empty($node->props['filter_order_manual'])) {
                    $order = array_map('strtolower', array_map('trim', explode(',', $node->props['filter_order_manual'])));
                    uasort($node->tags, static function ($a, $b) use ($order) {
                        $iA = array_search(strtolower($a), $order, true);
                        $iB = array_search(strtolower($b), $order, true);
                        return $iA !== false && $iB !== false ? $iA - $iB : ($iA !== false ? -1 : ($iB !== false ? 1 : strnatcmp($a,
                            $b)));
                    });
                } elseif ($node->props['filter_order'] !== 'manual' && $node->props['filter_order'] !== 'disable') {
                    natsort($node->tags);
                }

                // Reverse main tags if required
                if (!empty($node->props['filter_reverse']) && $node->props['filter_order'] !== 'disable') {
                    $node->tags = array_reverse($node->tags, true);
                }
            }

            //Generate Item Number
            $i = 1;
            foreach ($node->children as $child) {
                $child->props['image-src'] = $child->props['image'];
                $child->props['item_reverse'] = null;
                if ($node->props['grid_stack'] && $i % 2 === 0) {
                    $child->props['item_reverse'] = $i; //Grid stack
                }

                $child->props['item_num'] = $i;
                $i++;
            }

            if ($node->props['panel_style'] === 'tile-checked') {
                app(Metadata::class)->set('script:builder-grid',
                    ['src' => Path::get('./assets/js/grid.min.js', __DIR__), 'defer' => true]);
            }

            //init a new title search option
            if ($node->props['search'] && $node->props['search_title'] === null) {
                $node->props['search_title'] = true;
            }

            //disable search
            if ($node->props['search'] && !$node->props['search_title'] && !$node->props['search_meta'] && !$node->props['search_content'] && !$node->props['search_custom_meta'] && !$node->props['search_custom_text']) {
                $node->props['search'] = $node->props['search_highlight'] = false;
            }
        },
        // Runs before rendering the element
        'preload' => static fn($node) => NodePropsHelper::updateProps($node, 'preload'),

        // Executed before saving the element settings in the database.
        'presave' => static fn($node) => NodePropsHelper::updateProps($node, 'presave'),
    ],
];