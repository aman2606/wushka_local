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

namespace FlartStudio\YOOtheme\Grid;

defined('_JEXEC') or defined('ABSPATH') or die();

use YOOtheme\Config;
use YOOtheme\Path;
use YOOtheme\Builder;
use YOOtheme\Theme\Styler\StylerConfig;

include_once Path::get('./src/StyleListener.php', __DIR__);
include_once Path::get('./src/TranslationListener.php', __DIR__);

return [
    'theme' => [
        'styles' => [
            'components' => [
                'fs_grid' => Path::get('./assets/less/fs-grid.less', __DIR__),
            ],
        ],
    ],
    'events' => [
        StylerConfig::class => [StyleListener::class => '@handle'],
        'customizer.init' => [TranslationListener::class => ['translate', 10]],
    ],
    'extend' => [
        Config::class => static function (Config $config) {
            $config->addFile('fs_grid', Path::get('./config/fs-grid.json', __DIR__));
            $config->addFilter('fs_grid', function ($value) use ($config) {
                return $config->get("fs_grid.$value");
            });
        },
        Builder::class => static function (Builder $builder) {
            $builder->addTypePath(Path::get('./element/*/element.json', __DIR__));
        },
    ],
];

