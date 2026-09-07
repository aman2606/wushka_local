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

/** @noinspection DuplicatedCode */

namespace YOOtheme;

// No direct access to this file
defined('_JEXEC') or defined('ABSPATH') or die();

return [
    'transforms' => [
        'render' => static function ($node) {
            // Don't render an element if content fields are empty
            return Str::length($node->props['title'])
                || Str::length($node->props['text_1'])
                || Str::length($node->props['text_2'])
                || Str::length($node->props['text_3'])
                || Str::length($node->props['text_4'])
                || Str::length($node->props['text_5'])
                || Str::length($node->props['text_6'])
                || Str::length($node->props['text_7'])
                || Str::length($node->props['text_8'])
                || Str::length($node->props['text_9'])
                || Str::length($node->props['text_10'])
                || Str::length($node->props['text_11'])
                || Str::length($node->props['text_12'])
                || Str::length($node->props['text_13'])
                || Str::length($node->props['text_14'])
                || Str::length($node->props['text_15'])
                || Str::length($node->props['text_16'])
                || Str::length($node->props['text_17'])
                || Str::length($node->props['text_18'])
                || Str::length($node->props['text_19'])
                || Str::length($node->props['text_20'])
                || $node->props['image']
                || $node->children;
        },
    ],
];