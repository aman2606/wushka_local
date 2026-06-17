<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



namespace MOSAML\LicenseLibrary\Utils;

use MOSAML\LicenseLibrary\Classes\Mo_License_Dao;
use MOSAML\LicenseLibrary\Mo_License_Config;
if (defined("\x41\102\123\x50\x41\124\x48")) {
    goto dt;
}
exit;
dt:
class Mo_License_API_Utility
{
    public static function get_current_time_in_millis($FM)
    {
        $LA = Mo_License_Dao::mo_get_option(Mo_License_Config::API_KEY_OPTION);
        $W6 = round(microtime(true) * 1000);
        $ff = $FM . number_format($W6, 0, '', '') . $LA;
        $Hy = hash("\163\150\141\x35\x31\62", $ff);
        $W6 = number_format($W6, 0, '', '');
        return array("\x6d\151\154\154\151\x54\x69\x6d\145" => $W6, "\150\x61\x73\150" => $Hy);
    }
    public static function get_api_headers($FM, $P5, $Hy)
    {
        return array("\x43\157\156\164\145\156\164\x2d\x54\171\x70\x65" => "\141\x70\x70\154\x69\143\x61\x74\151\157\156\x2f\x6a\163\157\156", "\x43\x75\163\164\157\x6d\x65\162\55\x4b\x65\x79" => $FM, "\124\x69\155\145\163\164\141\x6d\160" => $P5, "\x41\x75\164\150\157\162\151\x7a\x61\x74\x69\x6f\x6e" => $Hy);
    }
    public static function get_api_args($N8, $sB)
    {
        $bD = wp_json_encode($N8);
        return array("\155\145\x74\150\x6f\144" => "\x50\x4f\123\124", "\x62\x6f\x64\171" => $bD, "\x74\x69\x6d\145\157\165\164" => "\x31\x30", "\x72\x65\144\x69\162\145\x63\164\x69\x6f\x6e" => "\65", "\150\x74\164\x70\166\145\162\163\x69\157\156" => "\61\56\60", "\142\154\157\x63\153\x69\x6e\x67" => true, "\150\145\x61\x64\x65\162\x73" => $sB);
    }
    public static function mo_wp_remote_call($Oz, $MS = array(), $O1 = false)
    {
        if (!$O1) {
            goto tM;
        }
        $d4 = wp_remote_get($Oz, $MS);
        goto MT;
        tM:
        $d4 = wp_remote_post($Oz, $MS);
        MT:
        if (!is_wp_error($d4)) {
            goto OV;
        }
        return false;
        goto Ol;
        OV:
        return $d4["\x62\x6f\x64\171"];
        Ol:
    }
}
