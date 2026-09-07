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

// Ensure these variables exist
/** @var $data_search */
/** @var $data_order */
/** @var $data_filter */

// Mapping of diacritic characters to their base characters
$diacritics = [
    'Á' => 'A', 'À' => 'A', 'Â' => 'A', 'Ä' => 'A', 'Ã' => 'A', 'Å' => 'A', 'Ā' => 'A', 'Ă' => 'A', 'Ą' => 'A',
    'á' => 'a', 'à' => 'a', 'â' => 'a', 'ä' => 'a', 'ã' => 'a', 'å' => 'a', 'ā' => 'a', 'ă' => 'a', 'ą' => 'a',
    'Ç' => 'C', 'Ć' => 'C', 'Č' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C',
    'ç' => 'c', 'ć' => 'c', 'č' => 'c', 'ĉ' => 'c', 'ċ' => 'c',
    'Đ' => 'D', 'Ď' => 'D', 'Ḑ' => 'D', 'Ḍ' => 'D', 'Ḓ' => 'D',
    'đ' => 'd', 'ď' => 'd', 'ḑ' => 'd', 'ḍ' => 'd', 'ḓ' => 'd',
    'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E', 'Ĕ' => 'E', 'Ė' => 'E', 'Ę' => 'E', 'Ě' => 'E',
    'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e', 'ē' => 'e', 'ĕ' => 'e', 'ė' => 'e', 'ę' => 'e', 'ě' => 'e',
    'Í' => 'I', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ī' => 'I', 'Ĭ' => 'I', 'Į' => 'I', 'İ' => 'I',
    'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i', 'ī' => 'i', 'ĭ' => 'i', 'į' => 'i', 'ı' => 'i',
    'Ñ' => 'N', 'Ń' => 'N', 'Ň' => 'N', 'Ņ' => 'N', 'Ṅ' => 'N',
    'ñ' => 'n', 'ń' => 'n', 'ň' => 'n', 'ņ' => 'n', 'ṅ' => 'n',
    'Ó' => 'O', 'Ò' => 'O', 'Ô' => 'O', 'Ö' => 'O', 'Õ' => 'O', 'Ō' => 'O', 'Ŏ' => 'O', 'Ø' => 'O',
    'ó' => 'o', 'ò' => 'o', 'ô' => 'o', 'ö' => 'o', 'õ' => 'o', 'ō' => 'o', 'ŏ' => 'o', 'ø' => 'o',
    'Ś' => 'S', 'Š' => 'S', 'Ş' => 'S', 'Ŝ' => 'S',
    'ś' => 's', 'š' => 's', 'ş' => 's', 'ŝ' => 's',
    'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ū' => 'U', 'Ŭ' => 'U', 'Ů' => 'U', 'Ű' => 'U', 'Ų' => 'U',
    'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u', 'ū' => 'u', 'ŭ' => 'u', 'ů' => 'u', 'ű' => 'u', 'ų' => 'u',
    'Ý' => 'Y', 'Ÿ' => 'Y', 'Ŷ' => 'Y',
    'ý' => 'y', 'ÿ' => 'y', 'ŷ' => 'y',
    'Ź' => 'Z', 'Ž' => 'Z', 'Ż' => 'Z',
    'ź' => 'z', 'ž' => 'z', 'ż' => 'z',
    'Þ' => 'Th', 'þ' => 'th',
    'Ð' => 'Dh', 'ð' => 'dh',
    'Æ' => 'Ae', 'æ' => 'ae',
    'Œ' => 'Oe', 'œ' => 'oe',
];

// Process diacritics neutralize using strtr
$data_search = strtr($data_search, $diacritics);
$data_order = strtr($data_order, $diacritics);
$data_filter = strtr($data_filter, $diacritics);
