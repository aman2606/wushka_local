<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



namespace MOSAML\LicenseLibrary\Classes;

use MOSAML\LicenseLibrary\Mo_License_Config;
if (defined("\x41\x42\123\x50\x41\124\x48")) {
    goto Q7;
}
exit;
Q7:
class Mo_License_Constants
{
    const VERSION = "\61\x2e\60\56\70";
    const HOSTNAME = "\150\x74\164\x70\x73\x3a\x2f\x2f\154\157\147\x69\x6e\x2e\x78\145\143\x75\x72\x69\x66\x79\56\x63\x6f\155";
    const EPOCH_DATE = "\x4a\141\156\x75\x61\162\171\40\61\54\x20\61\71\67\x30";
    const LAST_CHECK_TIME_OPTION = Mo_License_Config::OPTION_PREFIX . "\154\156\137\143\x68\145\x63\153\x5f\164";
    const LICENSE_EXPIRY_DATE_OPTION = Mo_License_Config::OPTION_PREFIX . "\x6c\x65\x64";
    const EXPIRY_NOTICE_CLOSE_OPTION = Mo_License_Config::OPTION_PREFIX . "\145\170\160\137\x6e\157\164\x69\143\145\x5f\143\x6c\157\x73\x65";
    const LICENSE_EXPIRED_OPTION = Mo_License_Config::OPTION_PREFIX . "\154\x69\143\145\156\x73\145\137\x65\x78\160\151\162\x65\x64";
    const IS_TRIAL = Mo_License_Config::OPTION_PREFIX . "\164\x6c\x61";
    const DASHBOARD_WIDGET_ID = Mo_License_Config::OPTION_PREFIX . "\154\x69\143\145\156\163\x65\137\144\x65\164\x61\x69\154\163\137\x77\x69\x64\x67\145\x74";
    const DASHBOARD_WIDGET_REFRESH_ID = Mo_License_Config::OPTION_PREFIX . "\162\145\146\x72\x65\x73\150\137\x65\170\x70\151\162\171";
    const ADMIN_NOTICE_DISMISS_ID = Mo_License_Config::OPTION_PREFIX . "\154\151\x63\x65\x6e\x73\145\x5f\x61\x64\x6d\x69\x6e\137\x6e\157\x74\x69\x63\145\x5f\144\151\x73\155\151\x73\x73";
    const ADMIN_ERROR_MESSAGE = "\124\150\x65\x20\x6c\x69\156\153\x20\x79\157\165\40\x66\157\x6c\x6c\157\x77\x65\x64\x20\150\x61\163\40\x65\170\x70\151\x72\145\x64\x2e\x20\x4f\162\x20\171\157\x75\162\x20\x70\154\x75\x67\x69\156\40\x6c\151\x63\145\156\163\x65\40\151\x73\40\151\x6e\166\141\x6c\x69\x64\x2e";
    const EXPIRY_IN_30_TO_60_DAYS = 60;
    const EXPIRY_IN_10_TO_30_DAYS = 30;
    const EXPIRY_IN_10_DAYS = 10;
    const GRACE_PERIOD_STARTED = 0;
    const GRACE_PERIOD_EXPIRED = "\107\122\101\x43\105\137\105\130\x50\x49\122\105\104";
    const TRIAL_PERIOD_STARTED = "\124\x52\x49\101\114\x5f\x53\x54\x41\x52\x54\x45\x44";
    const TRIAL_PERIOD_EXPIRED = "\124\x52\x49\101\114\x5f\105\x58\x50\111\x52\x45\104";
    const MINIORANGE_LOGO_PATH = "\166\151\145\167\163\57\151\x6e\x63\154\165\x64\145\x73\x2f\x69\x6d\141\147\x65\163\57\x6d\x69\x6e\x69\157\x72\141\x6e\147\145\55\154\x6f\147\157\56\x70\156\x67";
    const STYLES_FILE_PATH = "\x76\151\x65\x77\x73\x2f\x69\156\x63\154\165\144\145\163\57\143\x73\x73\57\x6c\x69\x63\x65\156\x73\x65\x2d\x76\151\x65\167\x73\x2d\163\164\171\x6c\145\x2e\155\x69\x6e\x2e\x63\x73\163";
    const PLUGIN_FILE_PATH = "\x2f\x77\x70\55\x61\x64\155\x69\156\x2f\x69\156\x63\154\x75\x64\145\163\57\160\154\x75\147\151\x6e\56\x70\x68\160";
    const ENVIRONMENT_SPECIFIC_HOOKS = array("\144\x61\163\x68\x62\157\141\x72\x64\x5f\x77\x69\144\147\x65\164" => array("\x6e\145\164\167\157\162\x6b" => "\x77\x70\137\156\x65\x74\x77\157\x72\x6b\137\144\141\163\x68\142\x6f\141\x72\144\x5f\163\145\x74\x75\x70", "\163\x74\x61\x6e\144\141\154\x6f\x6e\145" => "\167\160\137\144\x61\x73\x68\x62\x6f\x61\162\x64\x5f\x73\x65\x74\165\160"), "\x61\144\x6d\151\156\x5f\156\157\x74\151\x63\x65" => array("\x6e\145\x74\167\x6f\x72\153" => "\x6e\145\x74\x77\157\162\153\137\x61\x64\x6d\151\x6e\137\x6e\x6f\164\151\x63\145\x73", "\163\164\x61\156\x64\x61\154\x6f\x6e\145" => "\x61\x64\155\x69\156\x5f\156\x6f\164\151\143\145\163"));
    public static function get_constants()
    {
        try {
            $hB = new \ReflectionClass(static::class);
            return $hB->getConstants();
        } catch (\ReflectionException $G2) {
            return array();
        }
    }
}
