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

namespace FlartStudio\YOOtheme\Table;

defined('_JEXEC') or defined('ABSPATH') or die();

use YOOtheme\Config;
use YOOtheme\Path;
use YOOtheme\Builder;

include_once Path::get('./src/TranslationListener.php', __DIR__);

return [
    'events' => ['customizer.init' => [TranslationListener::class => ['translate', 10]]],
    'extend' => [
        Config::class => static function (Config $config) {
            $config->addFile('fs_table', Path::get('./config/fs-table.json', __DIR__));
            $config->addFilter('fs_table', function ($value) use ($config) {
                return $config->get("fs_table.$value");
            });
        },
        Builder::class => static function (Builder $builder) {
            $builder->addTypePath(Path::get('./element/*/element.json', __DIR__));
        }
    ],
];