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
/** @var $element */

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

// Rating
$star_rating = $this->el('div', [
    'class' => [
        "el-rating",
        'uk-inline',
        'uk-margin[-{rating_margin}]-top {@!rating_margin: remove} {@sublayout_mode: native|mixed}',
        'uk-margin-remove-top {@rating_margin: remove} {@sublayout_mode: native|mixed}',
        'uk-margin-remove-bottom',
        '[{rating_visibility} {@rating_visibility}]',
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

?>

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
<?= $star_rating->end() ?>