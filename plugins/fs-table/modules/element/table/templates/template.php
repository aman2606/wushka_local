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

defined('_JEXEC') or defined('ABSPATH') or die();

use YOOtheme\Arr;

// Ensure these variables exist
/** @var $builder */
/** @var $children */
/** @var $props */
/** @var $attrs */
/** @var $__dir */

$text_fields = [
    'title', 'text_1', 'text_2', 'text_3', 'text_4', 'text_5', 'text_6', 'text_7', 'text_8', 'text_9', 'text_10', 'text_11',
    'text_12', 'text_13', 'text_14', 'text_15', 'text_16', 'text_17', 'text_18', 'text_19', 'text_20'
];

switch ($props['table_order']) {
    case 1:
        $fields = [
            'title', 'text_1', 'text_2', 'text_3', 'image', 'text_4', 'text_5', 'text_6', 'text_7', 'text_8', 'text_9', 'text_10',
            'text_11', 'text_12', 'text_13', 'text_14', 'text_15', 'text_16', 'text_17', 'text_18', 'text_19', 'text_20', 'link'
        ];
        break;
    case 2:
        $fields = [
            'title', 'image', 'text_1', 'text_2', 'text_3', 'text_4', 'text_5', 'text_6', 'text_7', 'text_8', 'text_9', 'text_10',
            'text_11', 'text_12', 'text_13', 'text_14', 'text_15', 'text_16', 'text_17', 'text_18', 'text_19', 'text_20', 'link'
        ];
        break;
    case 3:
        $fields = [
            'image', 'title', 'link', 'text_2', 'text_1', 'text_2', 'text_3', 'text_4', 'text_5', 'text_6', 'text_7', 'text_8',
            'text_9', 'text_10', 'text_11', 'text_12', 'text_13', 'text_14', 'text_15', 'text_16', 'text_17', 'text_18',
            'text_19', 'text_20'
        ];
        break;
    case 4:
        $fields = [
            'image', 'title', 'text_1', 'text_2', 'text_3', 'text_4', 'text_5', 'text_6', 'text_7', 'text_8', 'text_9', 'text_10',
            'text_11', 'text_12', 'text_13', 'text_14', 'text_15', 'text_16', 'text_17', 'text_18', 'text_19', 'text_20', 'link'
        ];
        break;
    case 5:
        $fields = [
            'title', 'text_1', 'text_2', 'text_3', 'text_4', 'text_5', 'text_6', 'text_7', 'text_8', 'text_9', 'text_10',
            'text_11', 'text_12', 'text_13', 'text_14', 'text_15', 'text_16', 'text_17', 'text_18', 'text_19', 'text_20', 'link',
            'image'
        ];
        break;
    case 6:
        $fields = [
            'title', 'text_1', 'text_2', 'text_3', 'text_4', 'text_5', 'text_6', 'text_7', 'text_8', 'text_9', 'text_10',
            'text_11', 'text_12', 'text_13', 'text_14', 'text_15', 'text_16', 'text_17', 'text_18', 'text_19', 'text_20', 'image',
            'link'
        ];
        break;
    case 7:
        $fields = [
            'text_1', 'text_2', 'text_3', 'text_4', 'text_5', 'text_6', 'text_7', 'text_8', 'text_9', 'text_10', 'text_11',
            'text_12', 'text_13', 'text_14', 'text_15', 'text_16', 'text_17', 'text_18', 'text_19', 'text_20', 'title', 'image',
            'link'
        ];
        break;
    case 8:
        $fields = [
            'text_1', 'text_2', 'text_3', 'text_4', 'text_5', 'text_6', 'text_7', 'text_8', 'text_9', 'text_10', 'text_11',
            'text_12', 'text_13', 'text_14', 'text_15', 'text_16', 'text_17', 'text_18', 'text_19', 'text_20', 'image', 'title',
            'link'
        ];
        break;
    case 9:
        $fields = [
            'text_1', 'text_2', 'text_3', 'title', 'text_4', 'text_5', 'text_6', 'text_7', 'text_8', 'text_9', 'text_10',
            'text_11', 'text_12', 'text_13', 'text_14', 'text_15', 'text_16', 'text_17', 'text_18', 'text_19', 'text_20', 'image',
            'link'
        ];
        break;
    case 10:
        $fields = [
            'text_1', 'text_2', 'text_3', 'image', 'text_4', 'text_5', 'text_6', 'text_7', 'text_8', 'text_9', 'text_10',
            'text_11', 'text_12', 'text_13', 'text_14', 'text_15', 'text_16', 'text_17', 'text_18', 'text_19', 'text_20', 'title',
            'link'
        ];
        break;
    case 11:
        $fields = [
            'text_1', 'text_2', 'text_3', 'text_4', 'title', 'text_5', 'text_6', 'text_7', 'text_8', 'text_9', 'text_10',
            'text_11', 'text_12', 'text_13', 'text_14', 'text_15', 'text_16', 'text_17', 'text_18', 'text_19', 'text_20', 'image',
            'link'
        ];
        break;
    case 12:
        $fields = [
            'text_1', 'text_2', 'text_3', 'text_4', 'image', 'text_5', 'text_6', 'text_7', 'text_8', 'text_9', 'text_10',
            'text_11', 'text_12', 'text_13', 'text_14', 'text_15', 'text_16', 'text_17', 'text_18', 'text_19', 'text_20', 'title',
            'link'
        ];
        break;
    case 13:
        $fields = [
            'text_1', 'text_2', 'text_3', 'text_4', 'text_5', 'title', 'text_6', 'text_7', 'text_8', 'text_9', 'text_10',
            'text_11', 'text_12', 'text_13', 'text_14', 'text_15', 'text_16', 'text_17', 'text_18', 'text_19', 'text_20', 'image',
            'link'
        ];
        break;
    case 14:
        $fields = [
            'text_1', 'text_2', 'text_3', 'text_4', 'text_5', 'image', 'text_6', 'text_7', 'text_8', 'text_9', 'text_10',
            'text_11', 'text_12', 'text_13', 'text_14', 'text_15', 'text_16', 'text_17', 'text_18', 'text_19', 'text_20', 'title',
            'link'
        ];
        break;
}

$props['link_text'] = $props['link_text'] ?: 'Read More';

//Automatic modal integration
if ($props['show_link'] && $props['show_sublayout'] && $props['sublayout_mode'] === "modal" && $props['sublayout_modal_wrap'] === "all") {
    foreach ($children as $child) {
        if ($child->props['link_toggle'] && $child->props['link_toggle_modal_integration']) {
            $child->props['link'] = "#"; // to display a button if the link field is empty
        }
    }
}

// Find empty fields
$filtered = array_values(Arr::filter($fields, static function ($field) use ($props, $children) {
    //skip table cells where the sublayout or rating should be displayed
    if ($props["show_$field"] && $props['show_sublayout'] && $props['sublayout_position'] === $field && in_array($props['sublayout_mode'],
            ['native', 'mixed'], true)) {
        return $field;
    } elseif ($props["show_$field"] && $props['show_rating'] && $props['rating_position'] === $field) {
        return $field;
    } elseif ($field === 'link' && $props['show_button'] !== true) {
        return false;
    } else {
        return $props["show_$field"] && Arr::some($children, static function ($child) use ($field) {
                if ($field === 'image' && empty($child->props['image']) && !empty($child->props['icon'])) {
                    return 'image';
                } // Skip the checkbox with 0 value
                elseif ($child->props[$field] === '0') {
                    return $field;
                } else {
                    return $child->props[$field] != '';
                }
            });
    }
}));

// Add modal content to the table cell
if ($props['show_sublayout'] && $props['sublayout_mode'] === "modal") {
    foreach (array_reverse($filtered) as $field) {
        if (!in_array($field, ['image', 'description'])) {
            $props['sublayout_position'] = $field;
            break;
        }
    }
}

//Datatable
if ($props['enable_datatables']) {
    $props['uid'] = $this->uid();
    $props['element_id'] = "fs-table-{$props['uid']}";
    $props['table_id'] = $props['table_datatables_dtid'] ?: "fs-datatable-{$props['uid']}"; // Table save state
    $props['table_datatables_filter'] ? $dtFiltered = array() : $dtFiltered = '';
    $props['table_datatables_filter_image'] = false;

    if ($props['table_datatables_paging']) {
        $props['table_datatables_scrollY'] = '';
    } else {
        if ($props['table_datatables_scroll'] && $props['table_datatables_scrollY']) {
            $props['table_datatables_paging'] = false;
            $props['table_datatables_lengthChange'] = false;
            $props['table_datatables_info'] = false;
        } else {
            if (!$props['table_datatables_paging'] && !$props['table_datatables_scroll']) {
                $props['table_datatables_scrollY'] = '';
                $props['table_datatables_paging'] = false;
                $props['table_datatables_info'] = false;
            }
        }
    }
}

$el = $this->el('div', [
    'class' => [
        'fs-table',
        'uk-overflow-auto {@table_responsive: overflow}' => !$props['enable_datatables'] || !$props['table_datatables_sticky'],
        // YOOtheme Pro 5 Margins
        ...(($props['_yootheme_v4'] ?? false) === true ? [
            'uk-margin-top {@margin_top: default}',
            'uk-margin-{!margin_top: |default}-top',
            'uk-margin-bottom {@margin_bottom: default}',
            'uk-margin-{!margin_bottom: |default}-bottom',
        ] : []),
    ],
    'uk-lightbox' => [
        'toggle: a.fs-table-lightbox-link[data-type];' => $props['show_lightbox'],
    ],
]);

$table_wrapper = $this->el('div', [
    'id' => $this->expr(['{element_id} {@enable_datatables}'], $props) ?: false,
]);

$table = $this->el('table', [
    'id' => $this->expr(['{table_id} {@enable_datatables}'], $props) ?: false,
    'class' => [
        // Style
        'uk-table',
        'dataTable {@enable_datatables}',
        'dataTable-info {@enable_datatables}{@table_datatables_info}',
        'dataTable-search {@enable_datatables}{@table_datatables_search}',
        'dataTable-ordering {@enable_datatables}{@table_datatables_ordering}',
        'dataTable-filter {@enable_datatables}{@table_datatables_filter}',
        'dataTable-filter-header {@enable_datatables}{@table_datatables_filter}{@table_datatables_filter_position: header}',
        'dataTable-pagination {@enable_datatables}{@table_datatables_paging}',
        'uk-table-{table_style}',
        'uk-table-hover {@table_hover}',
        'uk-table-justify {@table_justify}',

        'uk-width-1-1',

        // Size
        'uk-table-{table_size}',

        // Vertical align
        'uk-table-middle {@table_vertical_align}',

        // Responsive
        'uk-table-responsive {@table_responsive: responsive}',
    ],
    'style' => ['width: 100%!important; max-width: 100%!important;'],
]);

?>

<?= $el($props, $attrs) ?>
<?= $table_wrapper($props) ?>
<?= $this->render("$__dir/template-legend", compact('props')) ?>
<?= $table($props) ?>

<?php if (Arr::some($filtered, static function ($field) use ($props) {
    return $props["table_head_$field"];
})): ?>

    <thead>
    <tr>
        <?php if ($props['show_counter']) : ?>
            <?= $this->render("$__dir/template-counter") ?>
        <?php endif ?>

        <?php foreach ($filtered as $i => $field) {
            //counter
            !empty($props['show_counter']) ? $filter_i = ($i + 1) : $filter_i = $i;

            if ($props['enable_datatables'] && $props['table_datatables_filter'] && is_array($dtFiltered) && $props["table_datatables_filter_$field"]) {
                $dtFiltered[] = ($filter_i);
            }

            //Datatables default sorting column
            if ($props['enable_datatables'] && !is_numeric($defCol) && $field !== 'image') {
                if ($props['table_datatables_sorting_default'] === $field && $props["table_datatables_sorting_$field"] && $props['table_datatables_sorting_default'] !== 'counter') {
                    if ($props['show_counter']) {
                        $defCol = ($i + 1);
                    } else {
                        $defCol = $i;
                    }
                } elseif ($props['table_datatables_sorting_default'] === 'counter' && $props["table_datatables_sorting_counter"] && $props['show_counter']) {
                    $defCol = 0;
                }
            }

            echo $this->el('th', [
                'class' => [
                    "fs-thead-column fs-thead-column-$i",

                    //Datatables Sorting
                    'th-image  no-sort {@enable_datatables}{@table_datatables_ordering}' => $field === 'image',
                    "th-$field no-sort {@enable_datatables}{@table_datatables_ordering}" => !$props["table_datatables_sorting_$field"] && $field !== 'image',

                    //Sorting Type
                    "sort-{{$field}_data} {@enable_datatables}{@table_datatables_ordering}{@{$field}_data}" => $props["table_datatables_sorting_$field"] && $field !== 'image',
                    "sort-string {@enable_datatables}{@table_datatables_ordering}{@!{$field}_data}" => $props["table_datatables_sorting_$field"] && $field !== 'image',

                    //Datatables Filter
                    "has-filter {@enable_datatables}{@table_datatables_filter}{@table_datatables_filter_$field}",

                    //Own field align including general settings breakpoint
                    "uk-text-{{$field}_align}[@{text_align_breakpoint} [uk-text-{text_align_fallback}]{@!text_align: justify}] {@{$field}_align} {@!{$field}_align_ignore_general}",

                    //General align including general settings breakpoint
                    "uk-text-{text_align}[@{text_align_breakpoint} [uk-text-{text_align_fallback}]{@!text_align: justify}] {@!{$field}_align}",

                    //Own field align ignoring general settings breakpoint
                    "uk-text-{{$field}_align} {@{$field}_align}{@{$field}_align_ignore_general}",

                    // Text nowrap
                    'uk-text-nowrap' => $field === 'link' || (in_array($field, $text_fields,
                                true) && $props["table_width_$field"] === 'shrink'),

                    //Visibility
                    "{{$field}_visibility} {@{$field}_visibility}",

                ],
            ], $props["table_head_$field"])->render($props);
        } ?>
    </tr>
    </thead>
<?php elseif ($props['enable_datatables']) : ?>
    <thead class="uk-hidden">
    <tr>
        <?php if ($props['show_counter']) : ?>
            <?= '<th class="fs-thead-column fs-thead-column-counter fs-thead-empty"></th>' ?>
        <?php endif ?>

        <?php foreach ($filtered as $i => $field) {
            echo $this->el('th', [
                'class' => [
                    "fs-thead-column fs-thead-column-$i",
                    "fs-thead-empty",
                    "{{$field}_visibility} {@{$field}_visibility}",
                ],
            ])->render($props);
        } ?>
    </tr>
    </thead>
<?php endif ?>
    <tbody class="fs-load-more-container">
    <?php foreach ($children as $i => $child): ?>
        <?php
        $table_row = $this->el('tr', [
            'id' => strip_tags($child->props['item_id'] ?? ''),
            'class' => [
                'el-item',
                'fs-table-row',
                "fs-table-row-$i",
                'fs-load-more-item',
                strip_tags($child->props['item_class'] ?? '')
            ],
            //Item Attributes
            strip_tags($child->props['item_attrs_tag'] ?? '')
        ]);
        ?>
        <?= $table_row($props) ?>
        <?= $builder->render($child,
            ['i' => $i, 'element' => $props, 'fields' => $fields, 'text_fields' => $text_fields, 'filtered' => $filtered]) ?>
        <?= $table_row->end() ?>
    <?php endforeach ?>
    </tbody>
<?php if ($props['enable_datatables'] && $props['table_datatables_search'] && $props['table_datatables_filter'] && $props['table_datatables_filter_position'] === 'footer' && !empty($dtFiltered) && Arr::some($filtered,
        static function ($field) use ($props) {
            return $props["table_head_$field"];
        })): ?>
    <tfoot>
    <tr>
        <?php if ($props['show_counter']) : ?>
            <?= $this->render("$__dir/template-counter") ?>
        <?php endif ?>

        <?php foreach ($filtered as $i => $field) {
            echo $this->el('th', [
                'class' => [
                    "fs-tfoot-column fs-tfoot-column-$i",
                    "{{$field}_visibility} {@{$field}_visibility}",
                ],
            ])->render($props);
        } ?>
    </tr>
    </tfoot>
<?php endif ?>

<?= $table->end() ?>
<?= $table_wrapper->end() ?>
<?= $el->end() ?>

<?php if ($props['enable_datatables']): ?>
    <?php include __DIR__ . "/template-datatable.php"; ?>
<?php endif ?>