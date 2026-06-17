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
use MOSAML\LicenseLibrary\Utils\Mo_License_API_Utility;
if (defined("\x41\102\x53\120\x41\x54\110")) {
    goto hN;
}
exit;
hN:
class Mo_License_API_Client
{
    public static function fetch_license_info()
    {
        $Oz = Mo_License_Constants::HOSTNAME . "\57\x6d\x6f\141\163\57\162\145\x73\x74\x2f\x63\165\163\164\157\155\x65\162\x2f\x6c\151\143\145\x6e\x73\x65";
        $FM = Mo_License_Dao::mo_get_option(Mo_License_Config::CUSTOMER_KEY_OPTION);
        if ($FM) {
            goto lu;
        }
        return false;
        lu:
        $yd = Mo_License_API_Utility::get_current_time_in_millis($FM);
        $N8 = array("\x63\165\163\x74\x6f\155\x65\162\x49\144" => $FM, "\x61\x70\x70\154\x69\143\141\x74\x69\157\x6e\x4e\x61\155\x65" => Mo_License_Config::APPLICATION);
        $sB = Mo_License_API_Utility::get_api_headers($FM, $yd["\x6d\151\154\154\x69\124\x69\x6d\145"], $yd["\x68\x61\163\x68"]);
        $MS = Mo_License_API_Utility::get_api_args($N8, $sB);
        $d4 = Mo_License_API_Utility::mo_wp_remote_call($Oz, $MS);
        return $d4;
    }
}
