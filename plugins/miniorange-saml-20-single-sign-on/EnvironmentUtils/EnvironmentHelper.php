<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



require_once MO_SAML_PLUGIN_DIR . MO_SAML_OPTIONS_ENUM;
require_once Mo_Saml_Plugin_Files::MO_SAML_ENVIRONMENT_OBJECT;
class EnvironmentHelper
{
    public static function getBasePluginConfigurationArray()
    {
        $C2 = array();
        foreach (mo_options_enum_service_provider::getConstants() as $If) {
            $C2[$If] = get_option($If);
            vU:
        }
        QH:
        foreach (mo_options_enum_service_provider_upload_metadata::getConstants() as $If) {
            $C2[$If] = get_option($If);
            p4:
        }
        hx:
        foreach (mo_options_enum_identity_provider::getConstants() as $If) {
            $C2[$If] = get_option($If);
            Pi:
        }
        qY:
        foreach (mo_options_enum_sso_login::getConstants() as $If) {
            $C2[$If] = get_option($If);
            X9:
        }
        mr:
        foreach (mo_options_enum_custom_messages::getConstants() as $If) {
            $C2[$If] = get_option($If);
            Ug:
        }
        Hw:
        foreach (mo_options_enum_domain_mapping::getConstants() as $If) {
            $C2[$If] = get_option($If);
            tk:
        }
        nb:
        foreach (mo_options_enum_attribute_mapping::getConstants() as $If) {
            $C2[$If] = get_option($If);
            PG:
        }
        ej:
        foreach (mo_options_enum_domain_restriction::getConstants() as $If) {
            $C2[$If] = get_option($If);
            VU:
        }
        jk:
        foreach (mo_options_enum_role_mapping::getConstants() as $If) {
            $C2[$If] = get_option($If);
            X1:
        }
        Jp:
        foreach (mo_options_enum_test_configuration::getConstants() as $If) {
            $C2[$If] = get_option($If);
            HB:
        }
        YZ:
        return $C2;
    }
    public static function getPluginConfiguration($XP = '')
    {
        $TG = get_option("\x6d\x6f\137\145\x6e\141\142\x6c\x65\137\x6d\165\x6c\x74\x69\x70\x6c\x65\x5f\154\151\x63\x65\156\163\x65\163");
        if (!$TG) {
            goto p2;
        }
        $J5 = maybe_unserialize(get_option("\155\x6f\137\x73\141\x6d\x6c\x5f\x65\x6e\166\x69\x72\x6f\x6e\155\145\156\x74\x5f\x6f\142\152\145\143\x74\x73"));
        $XP = !empty($XP) ? $XP : self::getSelectedEnvironment();
        if (!isset($J5[$XP])) {
            goto OQ;
        }
        return $J5[$XP]->getPluginSettings();
        OQ:
        goto xn;
        p2:
        return self::getBasePluginConfigurationArray();
        xn:
        return array();
    }
    public static function getOptionForSelectedEnvironment($If, $dD = false, $XP = '')
    {
        $aG = get_option(mo_options_environments::Multiple_Licenses);
        $EB = false;
        if (!empty($aG)) {
            goto OX;
        }
        $EB = get_option($If);
        goto gA;
        OX:
        $mh = self::getPluginConfiguration($XP);
        if (!isset($mh[$If])) {
            goto rL;
        }
        $EB = $mh[$If];
        rL:
        gA:
        if (!($dD && (!is_array($EB) || empty($EB)))) {
            goto xR;
        }
        $EB = array();
        xR:
        return maybe_unserialize($EB);
    }
    public static function getCurrentEnvironment()
    {
        $Kk = site_url();
        $co = maybe_unserialize(get_option("\155\x6f\x5f\163\x61\155\154\x5f\x65\156\x76\151\162\x6f\x6e\155\x65\156\164\x5f\x6f\142\152\x65\x63\164\163"));
        $z9 = str_replace("\40", "\137", get_bloginfo("\x6e\x61\155\x65"));
        if (!is_array($co)) {
            goto Zj;
        }
        foreach ($co as $t7 => $U8) {
            if (!(self::parseEnvironmentUrl($U8->getWpSiteUrl()) == self::parseEnvironmentUrl($Kk))) {
                goto NT;
            }
            $z9 = $t7;
            NT:
            Hz:
        }
        Fy:
        Zj:
        return $z9;
    }
    public static function parseEnvironmentUrl($Oz)
    {
        $Zy = parse_url($Oz, PHP_URL_SCHEME);
        $Oz = str_replace($Zy . "\x3a\57\x2f", '', $Oz);
        return $Oz;
    }
    public static function isSelectedEnvironmentDefault()
    {
        if (!(self::getSelectedEnvironment() == self::getCurrentEnvironment())) {
            goto JC;
        }
        return true;
        JC:
        return false;
    }
    public static function getNewEnvironmentObject($Dr)
    {
        $kO = new EnvironmentObject($Dr);
        $rC = self::getBasePluginConfigurationArray();
        $IB = false;
        $I7 = false;
        if (!($Dr == site_url())) {
            goto Mn;
        }
        $IB = get_option("\x6d\x6f\x5f\x73\x61\x6d\154\x5f\x73\160\137\x62\141\x73\145\x5f\165\x72\x6c");
        $I7 = get_option("\155\x6f\137\163\141\x6d\x6c\x5f\163\x70\x5f\x65\156\x74\151\164\171\137\151\x64");
        Mn:
        $hK = !empty($IB) ? $IB : $Dr;
        $Uy = !empty($I7) ? $I7 : $hK . "\57\167\160\55\x63\x6f\x6e\x74\145\x6e\164\x2f\160\x6c\165\147\151\156\x73\x2f\155\x69\x6e\151\x6f\x72\x61\x6e\x67\145\x2d\x73\141\x6d\154\x2d\62\x30\55\x73\x69\156\x67\x6c\145\55\163\x69\147\156\55\x6f\x6e\x2f";
        $rC[mo_options_enum_identity_provider::SP_Base_Url] = $hK;
        $rC[mo_options_enum_identity_provider::SP_Entity_ID] = $Uy;
        $kO->setPluginSettings($rC);
        return $kO;
    }
    public static function fetchExistingEnvironmentName($t7, $Dr)
    {
        $co = maybe_unserialize(get_option("\x6d\x6f\x5f\x73\141\155\x6c\137\145\156\166\x69\x72\x6f\156\x6d\x65\x6e\x74\x5f\x6f\142\x6a\145\143\164\x73"));
        if (!empty($co)) {
            goto b1;
        }
        return false;
        b1:
        if (array_key_exists($t7, $co)) {
            goto Ma;
        }
        foreach ($co as $xb => $mv) {
            if (!(self::parseEnvironmentUrl($mv->getWpSiteUrl()) == self::parseEnvironmentUrl($Dr))) {
                goto Qe;
            }
            return $xb;
            Qe:
            PY:
        }
        w8:
        goto yi;
        Ma:
        return $t7;
        yi:
        return false;
    }
    public static function getSelectedEnvironment()
    {
        $Rt = get_option("\155\x6f\x5f\163\141\155\x6c\137\x73\x65\x6c\145\143\164\145\x64\137\145\x6e\166\151\162\157\x6e\x6d\x65\x6e\x74");
        $co = maybe_unserialize(get_option("\155\157\x5f\x73\x61\155\154\137\x65\156\x76\x69\162\x6f\156\155\x65\x6e\164\x5f\157\x62\152\x65\143\x74\x73"));
        if (!(empty($Rt) || empty($co) || !array_key_exists($Rt, $co) || !get_option("\155\157\x5f\145\x6e\x61\x62\x6c\x65\137\155\165\154\164\x69\160\x6c\145\x5f\154\151\x63\145\x6e\x73\x65\163"))) {
            goto y8;
        }
        $Rt = self::getCurrentEnvironment();
        y8:
        return $Rt;
    }
    public static function getIdpOfAllEnviornment()
    {
        $co = maybe_unserialize(get_option("\x6d\157\x5f\x73\x61\155\x6c\x5f\x65\x6e\166\x69\162\x6f\156\x6d\x65\156\164\x5f\x6f\x62\152\x65\x63\x74\163"));
        $aG = get_option(mo_options_environments::Multiple_Licenses);
        $zF = array();
        if (!empty($aG)) {
            goto hh;
        }
        $rK = get_option("\163\x61\155\x6c\137\x69\x64\x65\x6e\x74\x69\x74\171\x5f\x70\162\x6f\x76\x69\x64\x65\162\x73");
        if (!is_array($rK)) {
            goto wF;
        }
        foreach ($rK as $BB => $Qc) {
            array_push($zF, $Qc);
            t1:
        }
        Bn:
        wF:
        goto Yi;
        hh:
        if (!is_array($co)) {
            goto VP;
        }
        foreach ($co as $XP => $Jp) {
            $mh = self::getPluginConfiguration($XP);
            if (!isset($mh["\x73\141\x6d\x6c\137\151\x64\x65\x6e\164\x69\164\171\x5f\x70\x72\x6f\166\151\x64\145\x72\x73"])) {
                goto MR;
            }
            $rK = $mh["\x73\x61\155\154\x5f\x69\x64\x65\156\x74\151\164\171\x5f\x70\162\157\166\151\x64\x65\162\x73"];
            if (!is_array($rK)) {
                goto Mp;
            }
            foreach ($rK as $BB => $Qc) {
                array_push($zF, $Qc);
                te:
            }
            Lb:
            Mp:
            MR:
            BE:
        }
        Ff:
        VP:
        Yi:
        return $zF;
    }
}
