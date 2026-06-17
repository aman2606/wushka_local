<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



use MOSAML\LicenseLibrary\Mo_License_Service;
class Mo_SAML_Integrator_Addon
{
    public static function mo_saml_register_addon_hooks()
    {
        add_filter("\x6d\x6f\x77\151\137\141\165\x74\x68\x65\156\x74\x69\143\x61\x74\x69\157\x6e\137\160\x6c\x75\x67\x69\x6e\x73", array("\115\x6f\137\x53\x41\115\114\137\111\x6e\164\145\x67\x72\141\x74\157\162\137\101\x64\144\157\x6e", "\x6d\x6f\x5f\x73\x61\155\154\x5f\x70\x6c\165\x67\151\x6e\x5f\x6e\x61\155\x65"));
        add_filter("\155\157\167\151\x5f\143\x6f\156\146\x69\147\x75\162\145\144\137\x69\144\160\163", array("\115\157\137\x53\101\x4d\x4c\x5f\111\156\164\145\x67\162\141\164\157\x72\137\101\x64\144\x6f\x6e", "\155\157\x5f\x73\x61\155\154\x5f\x63\157\156\146\x69\x67\165\x72\145\x64\137\151\144\x70\x73"), 10, 2);
        add_filter("\155\157\167\x69\x5f\x69\144\160\x5f\x74\145\163\164\137\141\x74\x74\x72\151\142\x75\164\145\163", array("\x4d\x6f\137\123\x41\x4d\114\x5f\111\156\164\145\147\x72\x61\x74\157\162\x5f\x41\144\144\157\x6e", "\x6d\157\137\163\x61\155\x6c\137\164\145\x73\x74\x5f\x61\164\164\162\151\142\x75\164\145\163"), 10, 3);
        add_filter("\x6d\x6f\x77\x69\137\151\x73\137\x6c\151\143\145\x6e\163\145\x5f\166\141\154\151\144", array("\115\x6f\x5f\123\101\x4d\114\x5f\x49\156\164\145\x67\162\141\164\x6f\x72\137\101\144\x64\157\156", "\155\x6f\137\163\141\x6d\x6c\x5f\151\x73\x5f\x6c\151\143\145\156\163\145\x5f\x76\141\154\151\144"), 10, 3);
        add_filter("\155\x6f\x77\151\x5f\151\x73\137\143\x75\163\x74\x6f\x6d\x65\162\x5f\x6c\x6f\x67\147\145\144\137\x69\x6e", array("\115\x6f\137\x53\x41\115\x4c\x5f\111\x6e\164\145\x67\162\141\x74\157\x72\137\x41\144\144\x6f\156", "\x6d\x6f\x5f\163\141\x6d\x6c\137\151\163\137\143\165\x73\x74\157\x6d\145\162\137\154\x6f\147\147\145\144\137\x69\156"));
        add_filter("\155\x6f\x77\151\137\154\x6f\147\x69\156\x5f\160\141\x67\145\x5f\165\x72\154", array("\x4d\x6f\137\x53\101\x4d\114\x5f\x49\x6e\x74\145\147\x72\141\x74\157\x72\x5f\x41\144\x64\157\156", "\x6d\157\137\163\141\x6d\x6c\137\154\x6f\147\151\x6e\137\x70\141\x67\145\x5f\165\162\x6c"));
        add_filter("\155\157\167\x69\x5f\x6c\157\x67\147\145\144\137\151\156\x5f\x63\x75\163\164\157\155\145\162\137\144\x65\164\x61\x69\154\163", array("\x4d\x6f\x5f\123\x41\115\114\x5f\111\x6e\x74\145\x67\162\141\x74\x6f\162\137\x41\x64\x64\157\x6e", "\x6d\157\137\163\141\x6d\154\137\x6c\157\x67\147\x65\144\137\x69\156\x5f\x63\x75\163\164\157\x6d\145\x72\x5f\x64\x65\164\x61\x69\x6c\163"));
    }
    public static function mo_saml_plugin_name($Yy)
    {
        return array_merge($Yy, array("\x73\x61\155\x6c" => "\123\101\x4d\x4c\40\x32\56\60\x20\x53\x53\117"));
    }
    public static function mo_saml_configured_idps($zF, $YT)
    {
        $jz = array();
        if (!("\x73\141\155\154" === $YT)) {
            goto gb;
        }
        $DQ = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\x61\x6d\x6c\137\151\x64\x65\156\164\151\164\x79\137\x70\162\x6f\166\x69\x64\145\162\163", true, EnvironmentHelper::getCurrentEnvironment());
        foreach ($DQ as $SJ => $gu) {
            $rm = isset($gu["\151\x64\160\x5f\144\151\x73\160\154\141\171\x5f\156\141\155\x65"]) ? $gu["\x69\144\x70\137\144\x69\x73\160\154\x61\x79\137\156\141\155\x65"] : $gu["\151\144\160\x5f\156\141\x6d\145"];
            $jz = array_merge($jz, array($SJ => $rm));
            rJ:
        }
        PJ:
        $zF[$YT] = $jz;
        gb:
        return $zF;
    }
    public static function mo_saml_test_attributes($qZ, $XE, $YT)
    {
        if (!("\163\x61\155\x6c" === $YT)) {
            goto QU;
        }
        $jZ = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\x5f\163\x61\x6d\154\137\x74\x65\163\x74\137\x63\157\156\x66\151\x67\137\141\x74\164\x72\163", true, EnvironmentHelper::getCurrentEnvironment());
        $qZ[$YT][$XE] = is_array($jZ) && isset($jZ[$XE]) ? $jZ[$XE] : array();
        QU:
        return $qZ;
    }
    public static function mo_saml_is_license_valid($d6, $ln, $Lm)
    {
        return Mo_License_Service::is_customer_license_valid($ln, $Lm);
    }
    public static function mo_saml_is_customer_logged_in()
    {
        return Mo_License_Service::is_customer_logged_into_plugin();
    }
    public static function mo_saml_login_page_url()
    {
        return "\x61\144\155\x69\156\x2e\160\x68\x70\x3f\160\x61\x67\145\75\155\157\x5f\163\141\155\154\137\x73\x65\164\x74\151\x6e\147\163\46\164\x61\142\75\154\x6f\x67\151\156";
    }
    public static function mo_saml_logged_in_customer_details()
    {
        return array("\105\115\101\111\114" => get_option("\155\157\137\x73\141\155\154\x5f\x61\x64\x6d\151\x6e\137\x65\155\x61\x69\x6c"), "\x50\110\x4f\x4e\105" => get_option("\155\x6f\x5f\163\x61\x6d\154\137\x61\x64\x6d\151\156\137\x70\150\x6f\x6e\145"), "\103\x55\123\x54\117\115\105\x52\x5f\x4b\105\x59" => get_option("\x6d\157\x5f\x73\x61\x6d\154\x5f\141\144\155\x69\156\x5f\x63\x75\x73\164\157\155\x65\x72\137\153\x65\x79"), "\x41\x50\111\x5f\113\105\131" => get_option("\x6d\x6f\x5f\163\x61\x6d\x6c\x5f\141\x64\x6d\x69\156\137\x61\x70\151\137\x6b\x65\x79"));
    }
}
