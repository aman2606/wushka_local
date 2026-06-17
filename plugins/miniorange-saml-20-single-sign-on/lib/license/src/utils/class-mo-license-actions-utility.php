<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



namespace MOSAML\LicenseLibrary\Utils;

use MOSAML\LicenseLibrary\Classes\Mo_License_API_Client;
use MOSAML\LicenseLibrary\Classes\Mo_License_Constants;
use MOSAML\LicenseLibrary\Classes\Mo_License_Library;
use MOSAML\LicenseLibrary\Mo_License_Config;
use MOSAML\LicenseLibrary\Classes\Mo_License_Dao;
if (defined("\x41\102\123\120\x41\124\110")) {
    goto ON;
}
exit;
ON:
class Mo_License_Actions_Utility
{
    public static function fetch_license_expiry_date()
    {
        try {
            $k4 = Mo_License_API_Client::fetch_license_info();
            if (!empty($k4)) {
                goto sO;
            }
            return false;
            sO:
            $k4 = json_decode($k4, true);
            if (!(!empty($k4["\163\x74\141\x74\x75\x73"]) && strcasecmp($k4["\x73\164\x61\x74\165\x73"], "\123\x55\x43\x43\105\x53\x53") === 0)) {
                goto y_;
            }
            if (empty($k4["\x6c\x69\x63\145\x6e\163\x65\x45\x78\x70\151\x72\x79"])) {
                goto i9;
            }
            return $k4["\x6c\151\x63\145\156\163\145\x45\x78\x70\151\x72\171"];
            i9:
            return false;
            y_:
            return false;
        } catch (\Exception $G2) {
            return false;
        }
    }
    public static function get_current_environment_hook_name($As)
    {
        return Mo_License_Constants::ENVIRONMENT_SPECIFIC_HOOKS[$As][Mo_License_Library::$environment_type];
    }
    public static function get_environment_type()
    {
        if (!function_exists("\151\x73\137\x70\x6c\x75\147\x69\x6e\137\x61\143\164\151\166\145\x5f\x66\157\162\137\156\145\164\x77\x6f\x72\153")) {
            require_once ABSPATH . Mo_License_Constants::PLUGIN_FILE_PATH;
        }
        $jW = explode("\x2f", Mo_License_Config::PLUGIN_FILE);
        $BC = (array) Mo_License_Dao::mo_get_option("\x61\143\164\x69\166\145\x5f\163\x69\164\145\167\x69\144\x65\137\x70\154\x75\147\151\x6e\163", array());
        if (is_plugin_active_for_network(Mo_License_Config::PLUGIN_FILE)) {
            goto cn;
        }
        if (!empty($BC)) {
            goto Xa;
        }
        goto Pr;
        cn:
        return "\156\x65\x74\x77\x6f\x72\x6b";
        goto Pr;
        Xa:
        foreach ($BC as $uq => $EB) {
            if (!(strpos($uq, $jW[0]) !== false)) {
                goto pC;
            }
            return "\156\145\164\x77\x6f\162\153";
            pC:
            HZ:
        }
        eX:
        Pr:
        return "\x73\x74\x61\x6e\144\x61\154\x6f\x6e\x65";
    }
}
