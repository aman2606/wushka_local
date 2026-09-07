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

/** @noinspection DuplicatedCode */

namespace FlartStudio\YOOtheme\Toggle;

defined('_JEXEC') or defined('ABSPATH') or die();

use JsonException;
use YOOtheme\Config;
use YOOtheme\Path;
use YOOtheme\Translator;

class TranslationListener
{
    public static function translate(Config $config, Translator $translator): void
    {
        $rawLocale = (string)$config->get('locale.code');
        $localeCode = basename($rawLocale);

        // Strict whitelist: only letters, digits, underscore, hyphen
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $localeCode)) {
            return;
        }

        $languageFile = Path::join(__DIR__, '..', 'languages', $localeCode . '.json');

        if (is_file($languageFile)) {
            try {
                $contents = file_get_contents($languageFile);
                json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

                $translator->addResource($languageFile);
            } catch (JsonException) {
                // Invalid JSON — fall back silently
            }
        }
    }
}