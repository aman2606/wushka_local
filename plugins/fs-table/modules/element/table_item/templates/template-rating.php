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

/** @noinspection DuplicatedCode, CssUnresolvedCustomProperty */

defined('_JEXEC') or defined('ABSPATH') or die();

// Ensure these variables exist
/** @var $element */
/** @var $field */

$rating_id = "js-{$this->uid()}";

//fixing incorrect rating values
empty($props['rating']) || $props['rating'] == null ? $props['rating'] = 0 : false;
if (is_numeric($props['rating'])) {
    $props['rating'] = number_format($props['rating'], 1);
    $props['rating'] > 5 ? $props['rating'] = 5 : false;
    $props['rating'] < 0 ? $props['rating'] = 0 : false;
} else {
    $props['rating'] = 0;
}

//Set default rating style values if undefined
$element['rating_star_size'] = $element['rating_star_size'] ?: '40';
$element['rating_star_color'] = $element['rating_star_color'] ?: '#fc0';
$element['rating_star_background_color'] = $element['rating_star_background_color'] ?: '#e5e5e5';
$element['rating_star_spacing'] = $element['rating_star_spacing'] ?: '3';
$props['rating_title'] = sprintf(($props['aria-label'] ?? 'Rated %s out of 5'),
    htmlspecialchars($props['rating'], ENT_QUOTES, 'UTF-8'));
$props['rating_text'] = sprintf(($props['aria-label'] ?? '<span class="dt-search-ignore">Rated</span> %s <span class="dt-search-ignore">out of 5</span>'),
    htmlspecialchars($props['rating'], ENT_QUOTES, 'UTF-8'));

// Rating
$star_rating_container = $this->el('div', [
    'class' => [
        "el-rating-container",

        //Own field align including general settings breakpoint
        "uk-text-{{$field}_align}[@{text_align_breakpoint} [uk-text-{text_align_fallback}]{@!text_align: justify}] {@{$field}_align} {@!{$field}_align_ignore_general}",

        //General align including general settings breakpoint
        "uk-text-{text_align}[@{text_align_breakpoint} [uk-text-{text_align_fallback}]{@!text_align: justify}] {@!{$field}_align}",

        //Own field align ignoring general settings breakpoint
        "uk-text-{{$field}_align} {@{$field}_align}{@{$field}_align_ignore_general}",

        'uk-margin[-{rating_margin}]-top {@!rating_margin: remove}' => $props[$field] && $element['rating_align'] === 'bottom',
        'uk-margin-remove-top {@rating_margin: remove}',
        'uk-margin-remove-bottom',
        '[{rating_visibility} {@rating_visibility}]',
    ],
    'role' => 'img',
    'aria-label' => $props['rating_title'],
    'title' => $props['rating_title'],
]);

// Rating
$star_rating = $this->el('div', [
    'class' => [
        "el-rating",
        'uk-inline',
    ],
    'style' => [
        '--el-rating-val: ' . $props['rating'] . ' ;',
        '--el-rating-percent: calc((var(--el-rating-val) / 5 * 100%) - ({rating_star_spacing} / 400 * 100%) );',
        'font-size: {rating_star_size}px;',
        'font-family: Times!important;', // make sure ★ appears correctly
        'line-height: 1',
    ],
    'id' => $rating_id,
]);

$star_rating_label = $this->el('div', [
    'class' => [
        'el-rating-label',
        'uk-text-small',
        'uk-text-center',
        'uk-font-default',
        'uk-hidden',
    ],
]);

?>
<?= $star_rating_container($element) ?>
<?= $star_rating($element) ?>
    <style>
        <?="#$rating_id"?>.el-rating::before {
            content: '★★★★★';
            letter-spacing: <?=json_encode($element['rating_star_spacing'], JSON_NUMERIC_CHECK) . 'px'?>;
            background: linear-gradient(90deg, <?=trim(json_encode($element['rating_star_color']), '"')?> var(--el-rating-percent), <?=trim(json_encode($element['rating_star_background_color']), '"')?> var(--el-rating-percent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
<?= $star_rating_label($element, $props['rating_text']) ?>
<?= $star_rating->end() ?>
<?= $star_rating_container->end() ?>