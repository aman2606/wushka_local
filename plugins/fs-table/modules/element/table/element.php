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

/** @noinspection DuplicatedCode, JsonEncodingApiUsageInspection */

namespace YOOtheme;

defined('_JEXEC') or defined('ABSPATH') or die();

// Include the updates.php file to define the $updates array
include_once Path::get('./updates.php', __DIR__);

use FlartStudio\YOOtheme\Table\ElementPropsHelper;

return [
    'transforms' => [
        'render' => static function ($node) {
            $config = app(Config::class);

            // Force reload
            if ($config('app.isCustomizer')) {
                $node->attrs['data-preview'] = 'reload';
            }

            // Limit Items
            $limit = (int)($node->props['limit_items'] ?? 0);
            $limit > 0 and $node->children = array_slice($node->children, 0, $limit);

            // Disable Datatables if no fields are enabled
            if ($node->props['enable_datatables']) {
                $fields = [
                    'show_title',
                    'show_description',
                    'show_image',
                    'show_link',
                    ...array_map(static fn($i) => "show_text_$i", range(1, 20)),
                ];
                $node->props['enable_datatables'] = (bool)array_filter(
                    $fields,
                    static fn($f) => !empty($node->props[$f])
                );
            }

            // Datatables default sorting column
            $node->defCol = null;

            if ($node->props['enable_datatables']) {
                $metadata = app(Metadata::class);

                // Load jQuery only if the YOOtheme doesn't provide it and the option isn't disabled
                if ($node->props['enable_jquery'] !== 'disable' && in_array($config('~theme.jquery'), [null, false], true)) {
                    $metadata->set('script:jquery', [
                        'src' => Path::get('./assets/js/jquery.min.js?v=3.6.1', __DIR__),
                        'defer' => true,
                    ]);
                }

                // Core DataTables scripts
                $metadata->set('script:dataTables', [
                    'src' => Path::get('./assets/js/jquery.dataTables.min.js?v=1.13.11', __DIR__),
                    'defer' => true,
                ]);

                $metadata->set('script:dataTables.uikit', [
                    'src' => Path::get('./assets/js/dataTables.uikit.min.js?v=1.6.1', __DIR__),
                    'defer' => true,
                ]);

                // Natural ordering plugin
                if ($node->props['table_datatables_ordering']) {
                    $metadata->set('script:dataTables.natural', [
                        'src' => Path::get('./assets/js/dataTables.natural.min.js?v=1.6.1', __DIR__),
                        'defer' => true,
                    ]);
                }

                // Highlight search matches
                if ($node->props['table_datatables_search'] && $node->props['table_datatables_search_highlight']) {
                    $metadata->set('script:jquery.mark', [
                        'src' => Path::get('./assets/js/jquery.mark.min.js?v=9.0.0', __DIR__),
                        'defer' => true,
                    ]);

                    $metadata->set('script:dataTables.mark', [
                        'src' => Path::get('./assets/js/dataTables.mark.min.js?v=2.1.0', __DIR__),
                        'defer' => true,
                    ]);
                }

                // Sticky header/footer support
                if (
                    $node->props['table_datatables_sticky']
                    && ($node->props['table_datatables_fixed_header'] || $node->props['table_datatables_fixed_footer'])
                ) {
                    $metadata->set('script:dataTables.fixedHeader', [
                        'src' => Path::get('./assets/js/dataTables.fixedHeader.min.js?v=3.4.0', __DIR__),
                        'defer' => true,
                    ]);
                }

                // Initialisation script and styles
                $metadata->set('script:dataTables.init', [
                    'src' => Path::get('./assets/js/dataTables.init.min.js?v=1.6.1', __DIR__),
                    'defer' => true,
                ]);

                $metadata->set('style:dataTables.init', [
                    'href' => Path::get('./assets/css/dataTables.uikit.min.css?v=1.6.1', __DIR__),
                ]);

                // Sanitize translations
                $sanitize = static function ($value): string {
                    if ($value === null || $value === '') {
                        return '';
                    }
                    $str = trim(htmlspecialchars_decode(strip_tags((string)$value), ENT_QUOTES | ENT_HTML5));
                    if ($str === '') {
                        return '';
                    }
                    return addcslashes($str, "\"'");
                };

                // Default translations
                $translations = [
                    'search' => 'Search',
                    'zeroRecords' => 'Nothing found - sorry',
                    'info' => 'Showing page _PAGE_ of _PAGES_',
                    'infoEmpty' => 'No records available',
                    'lengthMenu' => 'Display _MENU_ records per page',
                    'paginationAll' => 'All',
                    'pagination_previous' => '',
                    'pagination_next' => '',
                    'infoFiltered' => '(filtered from _MAX_ total records)',
                    'filter_all' => 'All',
                    'filter_checkbox_true' => 'Yes',
                    'filter_checkbox_false' => 'No',
                ];

                foreach ($translations as $key => $default) {
                    $prop = "table_datatables_translation_$key";
                    $value = $node->props[$prop] ?? '';
                    $clean = $sanitize($value);
                    $node->props[$prop] = $clean !== '' ? $clean : $sanitize($default);
                }
            }
        },
        // Runs before rendering the element
        'preload' => static fn($node) => ElementPropsHelper::updateProps($node, 'preload'),

        // Executed before saving the element settings in the database.
        'presave' => static fn($node) => ElementPropsHelper::updateProps($node, 'presave'),
    ],
];