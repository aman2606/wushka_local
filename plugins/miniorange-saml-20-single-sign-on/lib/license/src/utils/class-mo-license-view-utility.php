<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



namespace MOSAML\LicenseLibrary\Utils;

use MOSAML\LicenseLibrary\Classes\Mo_License_Constants;
use MOSAML\LicenseLibrary\Classes\Mo_License_Dao;
use MOSAML\LicenseLibrary\Mo_License_Config;
use MOSAML\LicenseLibrary\Mo_License_Service;
if (defined("\x41\102\x53\x50\x41\x54\110")) {
    goto p9;
}
exit;
p9:
class Mo_License_View_Utility
{
    public static function get_expiry_admin_notice_class($Hn)
    {
        if ($Hn > 10) {
            goto Nu;
        }
        if ($Hn <= 10) {
            goto gd;
        }
        goto pq;
        Nu:
        return "\156\x6f\x74\x69\143\145\x2d\167\x61\162\x6e\151\x6e\147";
        goto pq;
        gd:
        return "\156\x6f\x74\x69\x63\145\55\145\162\162\157\162";
        pq:
        return '';
    }
    public static function get_admin_notice_html($eF, $Ox)
    {
        $X4 = Mo_License_Config::$notice_html[$eF];
        $X4["\143\157\156\x74\145\x6e\x74"] = strtr($X4["\143\x6f\156\x74\x65\156\x74"], $Ox);
        return $X4;
    }
    public static function get_notice_day_key($Hn)
    {
        if (!Mo_License_Service::is_trial_license()) {
            goto BM;
        }
        if ($Hn > 0) {
            goto so;
        }
        return Mo_License_Constants::TRIAL_PERIOD_EXPIRED;
        goto gM;
        so:
        return Mo_License_Constants::TRIAL_PERIOD_STARTED;
        gM:
        BM:
        if ($Hn <= 60 && $Hn > 30) {
            goto IW;
        }
        if ($Hn <= 30 && $Hn > 10) {
            goto Ku;
        }
        if ($Hn <= 10 && $Hn >= 0) {
            goto oS;
        }
        if ($Hn < 0 && $Hn >= -Mo_License_Config::GRACE_PERIOD_DAYS) {
            goto GM;
        }
        if ($Hn < -Mo_License_Config::GRACE_PERIOD_DAYS) {
            goto zD;
        }
        goto WP;
        IW:
        return Mo_License_Constants::EXPIRY_IN_30_TO_60_DAYS;
        goto WP;
        Ku:
        return Mo_License_Constants::EXPIRY_IN_10_TO_30_DAYS;
        goto WP;
        oS:
        return Mo_License_Constants::EXPIRY_IN_10_DAYS;
        goto WP;
        GM:
        return Mo_License_Constants::GRACE_PERIOD_STARTED;
        goto WP;
        zD:
        return Mo_License_Constants::GRACE_PERIOD_EXPIRED;
        WP:
        return false;
    }
    public static function get_widget_notice($Ox)
    {
        $mQ = '';
        $Rr = Mo_License_Service::is_license_expired();
        if (Mo_License_Service::is_trial_license()) {
            goto QZ;
        }
        if (true === $Rr["\123\124\101\x54\125\x53"]) {
            goto fg;
        }
        if (false === $Rr["\x53\x54\101\124\125\123"] && "\114\111\103\105\116\x53\105\137\x49\116\x5f\x47\x52\x41\x43\x45" === $Rr["\x43\x4f\x44\x45"]) {
            goto jL;
        }
        if ($Ox["\x23\x23\x72\145\x6d\x61\151\156\151\156\147\x5f\144\x61\171\x73\x23\x23"] < 60) {
            goto KG;
        }
        goto U5;
        QZ:
        if (true === $Rr["\123\x54\101\x54\125\123"]) {
            goto ZI;
        }
        $mQ = "\131\x6f\165\40\141\162\145\40\x63\x75\162\x72\x65\156\x74\154\x79\x20\x6f\156\40\x74\162\x69\141\x6c\40\x70\154\x75\x67\151\156\x20\154\151\x63\x65\156\x73\145\x2e\x20\120\x6c\145\141\163\145\x20\x70\165\162\x63\150\x61\163\x65\x20\164\x68\145\40\160\x6c\165\147\151\x6e\40\164\x6f\40\143\157\x6e\164\x69\156\165\x65\x20\x77\151\x74\150\x20\163\x65\x61\x6d\154\x65\x73\x73\x20\x53\123\x4f\x20\x65\x78\160\x65\162\151\x65\156\x63\x65\56";
        goto jN;
        ZI:
        $mQ = "\131\157\165\162\x20\x74\162\x69\x61\154\x20\160\154\x75\x67\x69\x6e\x20\x6c\x69\x63\145\156\163\145\40\150\x61\163\x20\145\170\x70\x69\162\x65\144\x2e\x20\120\154\145\x61\163\145\40\x70\165\x72\143\150\141\x73\145\40\x74\x68\x65\x20\160\x6c\x75\x67\151\156\40\164\x6f\x20\143\157\156\x74\151\156\x75\x65\x20\167\x69\x74\x68\40\163\145\141\x6d\x6c\145\163\x73\40\123\123\117\x20\x65\x78\160\145\x72\x69\x65\156\x63\145\56";
        jN:
        goto U5;
        fg:
        $mQ = "\131\157\x75\162\x20\x70\x6c\165\x67\x69\x6e\40\154\x69\x63\145\156\x73\145\40\150\x61\x73\x20\x65\170\x70\151\x72\x65\144\40\x61\x6e\x64\40\164\x68\145\x20\160\x6c\x75\x67\x69\x6e\40\x68\141\x73\x20\x73\164\157\x70\x70\145\x64\x20\x77\x6f\162\153\151\x6e\147\56\40\120\154\x65\141\163\x65\40\x3c\x61\40\x68\162\x65\x66\x3d\42" . Mo_License_Config::RENEWAL_FAQ . "\42\x20\164\x61\x72\147\x65\164\x3d\42\x5f\x62\154\141\156\153\x22\76\x72\145\x6e\145\x77\40\x79\x6f\165\x72\x20\x6c\x69\143\145\156\163\x65\x3c\57\x61\76\x20\x69\x6d\x6d\x65\144\x69\141\x74\x65\x6c\x79\56";
        goto U5;
        jL:
        $mQ = "\x59\x6f\165\x20\x61\x72\145\x20\143\x75\162\x72\145\x6e\x74\x6c\171\40\157\156\40\147\162\141\x63\x65\40\x70\145\x72\151\x6f\144\40\146\x6f\162\x20\x72\x65\156\x65\x77\141\154\56\x20" . esc_html($Ox["\x23\43\x67\x72\141\143\145\137\144\141\171\163\137\154\145\x66\x74\43\43"]) . "\40\x64\141\171\163\40\x6c\145\x66\x74\40\142\145\146\157\162\x65\x20\123\x53\117\x20\151\163\x20\144\x69\163\141\142\154\x65\144\40\x6f\156\40\x79\157\x75\x72\x20\163\x69\x74\145\x2e";
        goto U5;
        KG:
        $mQ = "\131\157\165\x72\40\x70\x6c\x75\x67\x69\156\x20\x6c\151\x63\x65\156\x73\145\40\151\163\x20\147\x6f\x69\x6e\147\x20\x74\x6f\40\x65\170\x70\151\162\x65\x20\151\156\40" . esc_html($Ox["\43\43\162\145\x6d\x61\151\156\151\156\147\x5f\144\141\171\x73\x23\x23"]) . "\40\144\141\171\x73";
        U5:
        return $mQ;
    }
    public static function show_expiry_notice($Hn)
    {
        $XX = Mo_License_Dao::mo_get_option(Mo_License_Constants::EXPIRY_NOTICE_CLOSE_OPTION);
        if (!isset($Hn) || $Hn > 60) {
            goto OJ;
        }
        if ($Hn <= 10) {
            goto F9;
        }
        if (!$XX && $Hn <= 60) {
            goto ZL;
        }
        if ($XX && $XX > 30 && $Hn <= 30) {
            goto I_;
        }
        goto m7;
        OJ:
        return false;
        goto m7;
        F9:
        return true;
        goto m7;
        ZL:
        return true;
        goto m7;
        I_:
        return true;
        m7:
        return false;
    }
}
