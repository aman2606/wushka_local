<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



if (defined("\101\x42\123\x50\x41\x54\110")) {
    goto Kq;
}
exit;
Kq:
require_once Mo_Saml_Plugin_Files::MO_SAML_ENVIRONMENT_UTILS;
require_once Mo_Saml_Plugin_Files::MO_SAML_ENVIRONMENT_DAO;
class Mo_Saml_Hide_WP_Login_Handler
{
    private static $instance = null;
    public static function mo_saml_get_object()
    {
        if (!(null === self::$instance)) {
            goto ST;
        }
        self::$instance = new self();
        ST:
        return self::$instance;
    }
    private function __construct()
    {
        add_action("\154\x6f\147\x69\x6e\x5f\151\156\151\164", array($this, "\155\x6f\137\163\x61\155\x6c\x5f\144\151\x73\x61\142\154\145\x5f\x6c\157\x73\x74\137\160\141\x73\163\x77\x6f\x72\x64"));
        add_action("\x72\145\x73\164\x5f\x61\x70\151\x5f\151\x6e\151\164", array($this, "\x6d\157\137\163\x61\155\x6c\137\162\x65\x73\164\162\151\143\164\137\141\x70\151\x5f\146\x6c\157\167"), 1);
        add_filter("\x77\160\137\141\x75\164\x68\x65\156\x74\151\143\x61\164\x65\137\x75\x73\145\162", array($this, "\x6d\x6f\137\x73\x61\x6d\x6c\137\x72\145\163\164\162\151\x63\x74\x5f\x62\x61\143\153\145\156\144\x5f\150\151\x64\145\x5f\x6c\x6f\147\151\156"), 30, 2);
    }
    public function mo_saml_disable_lost_password()
    {
        if (!(self::mo_saml_check_hide_login() && !empty($_GET["\x61\143\164\x69\x6f\156"]) && in_array($_GET["\x61\143\164\x69\x6f\156"], array("\154\157\x73\x74\160\x61\x73\x73\x77\x6f\x72\x64", "\x72\145\x74\162\151\x65\x76\145\x70\x61\163\x73\167\x6f\x72\144"), true))) {
            goto sE;
        }
        $yG = apply_filters("\155\157\137\163\x61\x6d\x6c\137\163\x68\157\167\x5f\x6c\x6f\x73\x74\137\x70\x61\x73\x73\x77\x6f\x72\x64\x5f\165\162\154", false);
        if ($yG) {
            goto Vw;
        }
        wp_redirect(wp_login_url(), 301);
        exit;
        Vw:
        sE:
    }
    public function mo_saml_restrict_backend_hide_login($Io, $lq)
    {
        if (!self::mo_saml_check_hide_login()) {
            goto fO;
        }
        wp_redirect(wp_login_url(), 301);
        exit;
        fO:
        return $Io;
    }
    public function mo_saml_restrict_api_flow()
    {
        if (!self::mo_saml_check_hide_login()) {
            goto Lx;
        }
        register_rest_route("\167\160\x2f\166\x32", "\57\165\163\145\162\163", array("\x6d\x65\164\150\x6f\144\163" => "\x50\x4f\123\124", "\143\141\x6c\x6c\142\x61\x63\153" => array($this, "\x6d\x6f\137\163\x61\x6d\154\x5f\162\x65\163\164\162\x69\143\x74\137\x61\x70\x69\x5f\162\145\x67\151\163\164\162\x61\x74\151\157\x6e\x5f\x61\156\x64\137\154\x6f\147\151\156"), "\160\145\x72\155\x69\x73\x73\151\x6f\156\137\143\141\154\x6c\142\141\x63\153" => "\137\137\x72\145\164\165\x72\x6e\x5f\x74\x72\165\145"));
        register_rest_route("\167\x70\x2f\x76\x32", "\x2f\154\x6f\x67\x69\x6e", array("\x6d\145\164\150\157\x64\x73" => "\x50\x4f\123\124", "\143\x61\x6c\154\x62\141\143\x6b" => array($this, "\155\x6f\137\163\x61\155\154\x5f\162\145\163\164\162\151\143\x74\137\141\x70\x69\137\x72\145\147\x69\163\x74\162\141\164\151\x6f\x6e\137\x61\156\144\137\x6c\157\x67\151\x6e"), "\x70\145\x72\x6d\151\163\163\x69\157\x6e\137\143\141\x6c\154\142\x61\143\153" => "\137\137\x72\x65\x74\x75\x72\156\x5f\x74\162\165\x65"));
        Lx:
    }
    public function mo_saml_restrict_api_registration_and_login($rO)
    {
        $VS = apply_filters("\155\x6f\x5f\163\141\155\154\137\141\x70\151\137\162\145\163\164\x72\151\x63\x74\x65\x64\137\x6d\145\163\x73\x61\147\145", __("\101\x50\x49\40\141\143\143\145\x73\x73\40\151\x73\40\162\x65\x73\x74\x72\x69\x63\x74\x65\x64\x20\55\40\125\163\x65\x20\x73\x69\x6e\147\x6c\x65\55\x73\x69\147\156\55\157\x6e\x20\50\x53\x53\117\x29\40\x66\157\x72\x20\154\x6f\147\147\x69\156\x67\40\151\156\x74\157\x20\x74\x68\x65\40\167\x65\142\163\151\164\x65\56"));
        return new WP_Error("\x61\x70\x69\137\x72\145\x73\164\x72\151\143\x74\x65\x64", $VS, array("\163\164\141\x74\x75\163" => 403));
    }
    public static function mo_saml_is_sso_button_disabled($CP = '')
    {
        $id = EnvironmentHelper::getOptionForSelectedEnvironment("\163\x61\155\x6c\x5f\163\x73\x6f\137\142\x75\x74\164\x6f\156\x5f\x69\x64\x70", true);
        $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\x6d\x6c\137\x69\x64\145\x6e\164\151\x74\x79\137\160\x72\x6f\166\x69\x64\145\162\x73", true, $CP);
        $a5 = array_filter($id, function ($XE) {
            return !empty($XE["\x61\x64\144\x5f\142\x75\x74\x74\x6f\156\137\167\x70\x5f\154\x6f\x67\151\x6e"]) && $XE["\141\144\144\x5f\142\165\x74\164\x6f\x6e\x5f\x77\160\x5f\154\157\x67\x69\x6e"] == "\x74\162\x75\x65";
        });
        $ag = 0;
        foreach ($a5 as $XE => $pN) {
            if (empty($rK[$XE]["\145\x6e\141\142\x6c\145\137\x69\x64\160"])) {
                goto dA;
            }
            $ag++;
            dA:
            c7:
        }
        ll:
        return $ag;
    }
    public static function mo_saml_check_hide_login()
    {
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $yT = EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\x5f\163\x61\155\x6c\x5f\x62\141\x63\x6b\x64\x6f\157\x72\x5f\165\162\x6c", false, $CP) ? EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\137\163\x61\155\x6c\137\142\x61\143\x6b\144\157\157\162\137\165\x72\154", false, $CP) : "\146\x61\x6c\163\145";
        $MA = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\x6f\x5f\163\x61\155\x6c\x5f\x65\156\x61\142\x6c\145\137\150\x69\144\x65\x5f\167\x70\137\x6c\x6f\147\151\x6e", false, $CP);
        $af = EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\137\163\141\155\x6c\x5f\141\154\x6c\x6f\x77\x5f\x77\x70\137\x73\151\x67\x6e\x69\156", false, $CP);
        $yL = "\164\x72\x75\x65" === $MA;
        if (!($yL && "\x74\x72\x75\x65" === $af)) {
            goto ZT;
        }
        if (!(!empty($_REQUEST["\x73\x61\x6d\154\x5f\163\x73\157"]) && $_REQUEST["\163\x61\155\154\137\163\163\157"] === $yT)) {
            goto F2;
        }
        $yL = false;
        F2:
        ZT:
        if (!($yL && self::mo_saml_is_sso_button_disabled() < 1)) {
            goto R4;
        }
        $yL = false;
        R4:
        return $yL;
    }
    public static function mo_saml_last_sso_button()
    {
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $id = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\x61\155\154\x5f\x73\x73\157\137\142\165\x74\164\x6f\156\137\151\x64\160", true, $CP);
        $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\155\x6c\x5f\x69\144\x65\x6e\164\x69\x74\171\137\160\162\157\x76\151\144\x65\x72\x73", true, $CP);
        $a5 = array_filter($id, function ($XE) {
            return !empty($XE["\x61\x64\144\137\142\x75\164\164\157\156\x5f\x77\160\x5f\x6c\157\x67\x69\x6e"]) && $XE["\141\144\144\x5f\142\x75\164\x74\157\156\137\167\x70\137\154\x6f\x67\x69\x6e"] == "\x74\x72\165\x65";
        }, ARRAY_FILTER_USE_BOTH);
        $a5 = array_reverse($a5);
        $EF = '';
        foreach ($a5 as $XE => $pN) {
            if (empty($rK[$XE]["\x65\156\141\142\x6c\x65\x5f\151\144\x70"])) {
                goto gF;
            }
            $EF = $XE;
            goto ol;
            gF:
            cb:
        }
        ol:
        return $EF;
    }
}
