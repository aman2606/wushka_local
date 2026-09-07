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

use YOOtheme\Path;
use YOOtheme\Url;

// Ensure these variables exist
/** @var $builder */
/** @var $children */
/** @var $props */
/** @var $tags */
/** @var $searchable_text */
/** @var $__dir */

// Grid
$grid = $this->el('div', [
    'id' => $props['filter_id'],
    'class' => [
        'js-filter' => $tags,
        'uk-slider-items{@slider}',
        'uk-flex-center {@grid_column_align} {@!slider}',
        'uk-flex-middle {@grid_row_align}',
        $props['grid_column_gap'] === $props['grid_row_gap'] ? 'uk-grid-{grid_column_gap}' : '[uk-grid-column-{grid_column_gap}] [uk-grid-row-{grid_row_gap}]',
        'uk-grid-divider {@grid_divider} {@!grid_column_gap:collapse} {@!grid_row_gap:collapse}',
        'uk-grid-match {@!grid_masonry}',
        '[uk-child-width-{grid_default} {@grid_default}{@!slider}]',
        '[uk-child-width-{grid_small}@s {@grid_small}{@!slider}]',
        '[uk-child-width-{grid_medium}@m {@grid_medium}{@!slider}]',
        '[uk-child-width-{grid_large}@l {@grid_large}{@!slider}]',
        '[uk-child-width-{grid_xlarge}@xl {@grid_xlarge}{@!slider}]',
        'fs-load-more-container',
    ],
    'uk-grid' => $this->expr([
        'masonry: {grid_masonry};',
        'parallax: {grid_parallax};',
    ], $props) ?: true,
    'uk-grid-checked' => $props['panel_style'] === 'tile-checked'
        ? 'uk-tile-default,uk-tile-muted'
        : false,
    'uk-lightbox' => [
        'toggle: div.fs-load-more-item:not([style*="display: none"]) a.fs-grid-lightbox-link[data-type];' => $props['lightbox'],
    ],
]);

//Generate Item Containers
$i = 1;
foreach ($children as $child):

    if ($child->props['item_width_default'] || $child->props['item_width_small'] || $child->props['item_width_medium'] || $child->props['item_width_large'] || $child->props['item_width_xlarge']) {
        //Set item width values from the element if empty
        $child->props['item_width_default'] ??= $props['grid_default'];
        $child->props['item_width_small'] ??= $props['grid_small'];
        $child->props['item_width_medium'] ??= $props['grid_medium'];
        $child->props['item_width_large'] ??= $props['grid_large'];
        $child->props['item_width_xlarge'] ??= $props['grid_xlarge'];
    }

    ${"item_{$i}_container"} = $this->el('div', [
        'class' => [
            "fs-grid-item-$i-container",
            'fs-load-more-item',
            "fs-mw",

            //slider-width uk-child-width-5-6@s is impossible
            '[uk-width-{slider_width_default} {@slider_width_default}{@slider}]',
            '[uk-width-{grid_small}@s {@grid_small}{@slider}]' => !$child->props['item_width_small'],
            '[uk-width-{grid_medium}@m {@grid_medium}{@slider}]' => !$child->props['item_width_medium'],
            '[uk-width-{grid_large}@l {@grid_large}{@slider}]' => !$child->props['item_width_large'],
            '[uk-width-{grid_xlarge}@xl {@grid_xlarge}{@slider}]' => !$child->props['item_width_xlarge'],

            "uk-width-{$child->props['item_width_default']}" => $child->props['item_width_default'],
            "uk-width-{$child->props['item_width_small']}@s" => $child->props['item_width_small'],
            "uk-width-{$child->props['item_width_medium']}@m" => $child->props['item_width_medium'],
            "uk-width-{$child->props['item_width_large']}@l" => $child->props['item_width_large'],
            "uk-width-{$child->props['item_width_xlarge']}@xl" => $child->props['item_width_xlarge'],

            //Item Attributes
            strip_tags($child->props['item_class'] ?? '') => $child->props['item_class_tag_in_container'],

        ],
        //Item Attributes
        strip_tags($child->props['item_attrs_tag'] ?? '') => $child->props['item_attrs_tag_in_container'],
    ]);
    $i++;
endforeach;

?>

<?php if ($props['filter'] && $tags): ?>

    <?= $grid($props) ?>
    <?php $i = 1; ?>
    <?php foreach ($children as $child): ?>

        <?php if ($props['search']): ?>
            <?php include __DIR__ . "/template-search-content.php"; ?>
        <?php else: ?>
            <?php $searchable_text = false; ?>
        <?php endif ?>

        <?php $fs_filter_tags = ['data-tag' => $child->tags ?: true, 'data-search' => $searchable_text ?: true]; ?>
        <?php for ($f = 1; $f <= $props['custom_filters']; $f++): ?>
            <?php if (!empty(${"custom_text_{$f}_tags"})): ?>
                <?php $fs_filter_tags += array("data-tag-$f" => $child->{"custom_text_{$f}_tags"} ?: true); ?>
            <?php elseif (empty(${"custom_text_{$f}_tags"}) && $props['filter_sorting'] && $props['filter_sorting_key'] == $f) : ?>
                <?php $fs_filter_tags += array("data-tag-$f" => true); ?>
            <?php endif ?>
        <?php endfor ?>
        <?= ${"item_{$i}_container"}($props, $fs_filter_tags, $builder->render($child, ['element' => $props])) ?>
        <?php $i++; ?>

    <?php endforeach ?>
    <?= $grid->end() ?>

<?php else: ?>

    <?php if ($props['search']): ?>
        <?= $this->render("$__dir/template-search", compact('props')) ?>
        <?= $grid($props) ?>
        <?php $i = 1; ?>
        <?php foreach ($children as $child): ?>

            <?php include __DIR__ . "/template-search-content.php"; ?>

            <?= ${"item_{$i}_container"}($props, ['data-search' => $searchable_text ?: true],
                $builder->render($child, ['element' => $props])) ?>
            <?php $i++; ?>
        <?php endforeach ?>

        <?= $grid->end() ?>
    <?php else: ?>
        <?= $grid($props) ?>
        <?php $i = 1; ?>
        <?php foreach ($children as $child): ?>
            <?= ${"item_{$i}_container"}($props) ?>
            <?= $builder->render($child, ['element' => $props]) ?>
            <?= ${"item_{$i}_container"}->end() ?>
            <?php $i++; ?>
        <?php endforeach ?>
        <?= $grid->end() ?>

    <?php endif ?>

<?php endif ?>

<?php if ($props['filter'] || $props['search']): ?>
    <div class="<?= $props['search_id'] ?> uk-container uk-width-1-1 uk-hidden">
        <?php if ($props['search_no_items_lottie']): ?>
            <?php $props['lottie'] = 'lottie' . substr(md5(uniqid(mt_rand(), true)), -7); ?>
            <div class="uk-flex uk-flex-center">
                <div id="<?= $props['lottie'] ?>" style="width: 300px; height: 300px;"></div>
                <script src="<?= Url::to(Path::get(__DIR__ . '/../assets/js/lottie.min.js')) ?>"></script>
                <script type="text/javascript">
                    let <?= $props['lottie'] ?> = bodymovin.loadAnimation({
                        container: document.getElementById(<?=json_encode($props['lottie'])?>),
                        path: '<?= Url::to(Path::get(__DIR__ . '/../assets/lottie/search.json')) ?>',
                        autoplay: true,
                        renderer: 'svg',
                        loop: true
                    });
                </script>
            </div>
        <?php endif ?>
        <div class="fs-search-empty uk-alert uk-text-center" uk-alert></div>
    </div>
<?php endif ?>