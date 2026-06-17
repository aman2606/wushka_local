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
if (defined("\101\102\123\x50\101\x54\110")) {
    goto X_;
}
exit;
X_:
class Mo_License_Dao
{
    public static function mo_get_option($cH, $hx = false, $Dz = true)
    {
        switch (Mo_License_Config::PLUGIN_TYPE) {
            case "\x57\120\137\123\x53":
                return get_option($cH, $hx);
            case "\x57\x50\x5f\115\123":
                return get_site_option($cH, $hx, $Dz);
        }
        XH:
        iX:
    }
    public static function mo_update_option($cH, $EB)
    {
        switch (Mo_License_Config::PLUGIN_TYPE) {
            case "\x57\x50\x5f\x53\x53":
                return update_option($cH, $EB);
            case "\x57\x50\x5f\x4d\123":
                return update_site_option($cH, $EB);
        }
        F7:
        wa:
    }
    public static function mo_delete_option($cH)
    {
        switch (Mo_License_Config::PLUGIN_TYPE) {
            case "\127\120\x5f\x53\x53":
                return delete_option($cH);
            case "\x57\x50\x5f\115\x53":
                return delete_site_option($cH);
        }
        N3:
        ow:
    }
}
