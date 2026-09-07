<?php /**
 * @package     [FS] Toggle element for YOOtheme Pro
 * @subpackage  fs-toggle
 *
 * @author      Flart Studio https://flart.studio
 * @copyright   Copyright (C) 2008-2026 Flart Studio. All rights reserved.
 * @license     GNU General Public License version 2 or later; see https://www.gnu.org/licenses/gpl-2.0.html
 * @license     Non-PHP assets in this package are proprietary — https://flart.studio/license
 * @link        https://flart.studio/yootheme-pro/toggle
 * @build       (FLART_BUILD_NUMBER)
 */

namespace FlartStudio\YOOtheme\Toggle;

defined('_JEXEC') or defined('ABSPATH') or die();

use YOOtheme\Config as ThemeConfig;
use YOOtheme\File;
use YOOtheme\Theme\Styler\StylerConfig;

class StyleListener
{
    public static function handle(StylerConfig $config, ThemeConfig $settings): StylerConfig
    {
        $file = File::find("~theme/css/theme{.{$settings->get('theme.id')},}.css");
        if ($file !== null && File::exists($file)) {
            $isCompiled = strpos(File::getContents($file), '.fs-toggle [data-fs-toggle-group]');
            if ($isCompiled === false) {
                $config['update'] = true;
            }
        }

        return $config;
    }
}