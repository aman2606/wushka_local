<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



require_once Mo_Saml_Plugin_Files::MO_SAML_UTILITIES;
require_once Mo_Saml_Plugin_Files::MO_SAML_RESPONSE;
require_once Mo_Saml_Plugin_Files::MO_SAML_LOGOUT_REQUEST;
require_once Mo_Saml_Plugin_Files::MO_SAML_USER_LOGIN_HANDLER;
require_once Mo_Saml_Plugin_Files::MO_SAML_CONFIG_UTILITY;
if (class_exists("\101\105\123\x45\x6e\x63\162\171\160\x74\x69\157\156")) {
    goto W4j;
}
require_once Mo_Saml_Plugin_Files::MO_SAML_ENCRYPTION;
W4j:
require_once Mo_Saml_Plugin_Files::MO_SAML_XML_SEC_LIBS;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecEnc;
use MOSAML\LicenseLibrary\Mo_License_Service;
class Mo_SAML_Login_Widget extends WP_Widget
{
    private static $instance;
    public function __construct()
    {
        parent::__construct("\x53\141\x6d\x6c\x5f\x4c\157\147\x69\156\x5f\x57\151\x64\x67\145\164", "\114\x6f\x67\151\x6e", array("\x64\x65\x73\143\162\151\x70\164\x69\x6f\x6e" => __("\x54\x68\151\163\x20\151\x73\40\141\x20\155\x69\x6e\x69\x4f\x72\141\x6e\147\145\x20\123\x41\115\114\x20\x6c\157\147\151\x6e\40\167\151\x64\147\145\164\x2e", "\x6d\x6f\x73\141\x6d\154")));
    }
    public static function mo_saml_get_object()
    {
        if (isset(self::$instance)) {
            goto nr9;
        }
        $A4 = __CLASS__;
        self::$instance = new $A4();
        nr9:
        return self::$instance;
    }
    public function widget($MS, $e9)
    {
        extract($MS);
        if (!(isset($e9["\167\x69\x64\x5f\x74\x69\x74\154\x65"]) && !empty($e9["\167\x69\144\x5f\x74\x69\164\154\145"]))) {
            goto abq;
        }
        $on = apply_filters("\167\151\144\147\x65\164\x5f\164\151\164\x6c\145", $e9["\167\x69\144\137\164\x69\x74\x6c\145"]);
        abq:
        echo $MS["\142\x65\x66\157\x72\145\137\x77\x69\x64\x67\x65\164"];
        if (empty($on)) {
            goto fan;
        }
        echo $MS["\x62\145\146\157\x72\x65\x5f\x74\x69\164\x6c\145"] . $on . $MS["\x61\146\x74\x65\162\137\x74\x69\164\x6c\x65"];
        fan:
        $this->loginForm();
        echo $MS["\x61\x66\x74\x65\x72\x5f\x77\151\x64\147\145\x74"];
    }
    public function update($y9, $fn)
    {
        $e9 = array();
        $e9["\167\151\144\x5f\x74\x69\x74\x6c\145"] = strip_tags($y9["\167\x69\144\137\164\x69\x74\154\x65"]);
        return $e9;
    }
    public function form($e9)
    {
        $on = '';
        if (empty($e9["\x77\151\x64\x5f\164\151\164\x6c\145"])) {
            goto S30;
        }
        $on = $e9["\x77\x69\x64\137\x74\x69\x74\x6c\145"];
        S30:
        echo "\11\x9\74\160\x3e\xd\12\11\11\x9\74\x6c\141\142\145\x6c\40\x66\157\162\75\42";
        echo esc_attr($this->get_field_id("\167\x69\144\137\164\x69\x74\154\x65"));
        echo "\42\76\xd\xa\11\11\11\x9";
        esc_html_e("\x54\151\x74\154\145\72");
        echo "\11\11\x9\74\x2f\154\x61\x62\145\154\x3e\xd\xa\x9\x9\x9\74\x69\156\160\x75\x74\x20\x63\x6c\x61\163\x73\75\42\167\x69\144\145\x66\141\x74\x22\x20\x69\144\75\42";
        echo esc_attr($this->get_field_id("\x77\151\144\137\164\x69\164\154\145"));
        echo "\x22\x20\156\x61\x6d\x65\75\42";
        echo esc_attr($this->get_field_name("\x77\x69\x64\x5f\x74\151\164\x6c\x65"));
        echo "\42\x20\x74\171\160\145\75\42\164\145\x78\x74\42\40\x76\141\x6c\165\x65\75\x22";
        echo esc_attr($on);
        echo "\x22\x20\x2f\x3e\15\12\11\x9\x3c\57\160\76\xd\12\x9\11";
    }
    public function loginForm()
    {
        global $post;
        if (Mo_License_Service::is_customer_license_verified()) {
            goto HNi;
        }
        return;
        HNi:
        $CP = EnvironmentHelper::getCurrentEnvironment();
        if (!SAMLSPUtilities::mo_saml_is_user_logged_in()) {
            goto oc5;
        }
        $current_user = wp_get_current_user();
        $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\x61\155\154\x5f\151\x64\x65\x6e\x74\151\164\171\x5f\x70\162\157\x76\x69\x64\145\x72\163", true, $CP);
        $fk = get_user_meta($current_user->ID, "\155\157\x5f\x73\141\x6d\154\137\x6c\x6f\x67\147\x65\x64\137\151\156\x5f\x77\151\164\150\x5f\x69\x64\x70", true);
        $fk = isset($_SESSION["\x6d\x6f\137\x67\x75\x65\163\164\x5f\154\x6f\x67\x69\156"]["\x6c\157\x67\147\145\x64\137\x69\156\137\x69\144\160\x5f\156\x61\155\145"]) ? $_SESSION["\x6d\x6f\137\x67\x75\145\163\164\137\154\157\147\151\x6e"]["\154\x6f\x67\147\x65\144\x5f\151\156\137\x69\x64\x70\137\x6e\x61\155\145"] : get_user_meta($current_user->ID, "\155\157\x5f\x73\141\155\x6c\137\x6c\157\x67\147\145\x64\x5f\x69\x6e\x5f\167\151\x74\150\137\151\144\160", true);
        if (!empty($fk)) {
            goto yGl;
        }
        $mK = "\x44\105\x46\x41\125\114\124";
        goto v41;
        yGl:
        $mK = !empty($rK[$fk]) ? $rK[$fk] : array();
        v41:
        $Da = "\x48\x65\x6c\x6c\157\54";
        if (empty($mK["\x63\165\163\x74\157\155\x5f\147\162\145\145\164\151\x6e\x67\137\x74\145\170\x74"])) {
            goto rDH;
        }
        $Da = $mK["\143\x75\x73\x74\x6f\x6d\x5f\147\x72\x65\x65\164\x69\156\x67\137\164\145\x78\164"];
        rDH:
        $MO = '';
        if (empty($mK["\147\162\x65\x65\164\x69\x6e\147\x5f\156\x61\155\145"])) {
            goto k1T;
        }
        switch ($mK["\x67\x72\x65\x65\164\x69\156\x67\137\156\x61\x6d\x65"]) {
            case "\x55\x53\105\122\116\101\115\105":
                $MO = $current_user->user_login;
                goto sZY;
            case "\x45\115\101\111\x4c":
                $MO = $current_user->user_email;
                goto sZY;
            case "\106\x4e\x41\115\x45":
                $MO = $current_user->user_firstname;
                goto sZY;
            case "\114\116\x41\x4d\x45":
                $MO = $current_user->user_lastname;
                goto sZY;
            case "\x46\116\x41\x4d\105\x5f\x4c\116\x41\115\105":
                $MO = $current_user->user_firstname . "\x20" . $current_user->user_lastname;
                goto sZY;
            case "\114\x4e\101\115\105\x5f\x46\x4e\x41\115\105":
                $MO = $current_user->user_lastname . "\40" . $current_user->user_firstname;
                goto sZY;
            default:
                $MO = $current_user->user_login;
        }
        ACs:
        sZY:
        k1T:
        if (!empty(trim($MO))) {
            goto iJh;
        }
        $MO = $current_user->user_login;
        iJh:
        $AO = $Da . "\40" . $MO;
        $r_ = "\114\157\147\x6f\x75\164";
        if (empty($mK["\143\x75\163\164\157\155\x5f\x6c\157\147\x6f\x75\164\x5f\164\145\170\164"])) {
            goto FMP;
        }
        $r_ = $mK["\143\x75\x73\x74\157\155\x5f\154\157\x67\x6f\165\x74\x5f\164\145\170\164"];
        FMP:
        echo esc_attr($AO) . "\x20\x7c\x20\x3c\141\40\x68\162\145\146\x3d\42" . esc_url(wp_logout_url(home_url())) . "\x22\40\164\x69\x74\154\145\75\x22\x6c\x6f\x67\x6f\x75\x74\x22\40\76" . esc_attr($r_) . "\x3c\x2f\x61\76\x3c\57\x6c\x69\x3e";
        $Oz = saml_get_current_page_url();
        $s6 = new EnvironmentDao($CP);
        $s6->mo_save_environment_settings("\154\157\x67\157\165\x74\137\x72\145\144\151\x72\145\143\x74\x5f\x75\x72\x6c", $Oz);
        goto Yij;
        oc5:
        $V9 = saml_get_current_page_url();
        $gu = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\155\x6c\x5f\151\144\x65\156\x74\x69\x74\x79\137\x70\162\x6f\166\x69\x64\x65\162\163", true, $CP);
        $vi = EnvironmentHelper::getOptionForSelectedEnvironment("\163\x61\x6d\x6c\137\163\x73\157\137\x62\x75\x74\x74\x6f\156\137\151\x64\x70", true);
        if (!empty($gu)) {
            goto hQK;
        }
        echo "\120\x6c\x65\141\163\145\40\143\x6f\x6e\x66\x69\147\x75\162\x65\x20\x74\x68\145\40\155\x69\x6e\x69\117\162\x61\156\147\x65\40\123\101\x4d\114\40\x50\x6c\x75\147\x69\x6e\40\146\x69\x72\x73\164\56";
        goto ZpO;
        hQK:
        foreach ($gu as $fk) {
            if (!empty($fk["\145\156\x61\142\154\x65\x5f\151\144\160"])) {
                goto R3s;
            }
            goto Xse;
            R3s:
            if (!empty($fk["\151\x64\160\x5f\156\x61\155\x65"])) {
                goto FsF;
            }
            echo "\120\x6c\145\141\x73\x65\40\143\x6f\156\x66\x69\x67\165\162\x65\40\x74\x68\145\x20\x6d\151\x6e\x69\117\162\141\x6e\147\x65\x20\x53\101\115\x4c\x20\x50\x6c\165\147\151\156\x20\146\x69\162\x73\164\x2e";
            goto J_0;
            FsF:
            if (!empty($vi[$fk["\151\x64\160\x5f\156\141\155\x65"]]["\x75\163\145\137\x62\165\164\x74\157\x6e\137\x61\163\137\167\151\x64\147\145\164"])) {
                goto soV;
            }
            $s1 = "\154\x6f\x67\151\156\137" . $fk["\151\144\160\x5f\x6e\x61\155\x65"];
            $E8 = "\x6d\x6f\x73\x75\142\x6d\x69\x74\x73\x61\155\154\x66\x6f\x72\155\x5f" . $fk["\151\x64\x70\x5f\x6e\141\155\x65"];
            echo "\74\163\x63\x72\151\160\x74\x3e\15\xa\x9\11\x9\x9\11\x9\11\x6a\121\165\145\162\171\50\40\x64\x6f\x63\165\x6d\145\156\x74\x20\x29\x2e\162\145\x61\144\171\50\x66\x75\x6e\x63\x74\151\x6f\156\x28\51\40\x7b\xd\xa\11\x9\11\x9\11\11\11\x9\x6a\121\x75\x65\162\x79\50\x22\43" . esc_attr($E8) . "\x22\51\56\x63\154\151\143\153\x28\146\x75\x6e\x63\x74\151\157\x6e\x28\x65\51\x20\173\xd\12\11\11\x9\11\x9\x9\11\11\x9\145\56\160\x72\x65\x76\145\x6e\164\104\145\x66\141\165\x6c\x74\50\x29\x3b\xd\xa\11\11\x9\x9\x9\x9\11\11\x9\152\121\x75\145\x72\171\50\42\43" . esc_attr($s1) . "\42\x29\56\x73\165\x62\155\x69\x74\50\x29\73\xd\xa\11\x9\11\11\11\x9\11\11\x7d\51\73\15\xa\x9\x9\11\11\x9\x9\11\x7d\51\73\xd\12\x9\11\x9\x9\x9\x9\x9\74\57\x73\143\x72\151\x70\x74\x3e\xd\xa\x9\11\11\11\x9\x9\11\74\146\x6f\162\x6d\x20\156\141\x6d\145\x3d\x22" . esc_attr($s1) . "\42\40\151\x64\75\x22" . esc_attr($s1) . "\x22\x20\155\x65\164\x68\x6f\x64\x3d\42\160\x6f\163\x74\42\x20\141\143\x74\151\x6f\156\75\x22\x22\76\xd\xa\11\11\11\x9\x9\11\x9\11\x9\x3c\x69\x6e\160\x75\x74\x20\x74\171\x70\145\75\42\x68\x69\x64\144\x65\156\42\x20\x6e\x61\x6d\145\x3d\42\157\160\164\151\157\x6e\42\x20\166\x61\154\x75\145\75\42\x73\141\155\x6c\x5f\165\163\145\162\137\x6c\x6f\147\151\x6e\42\40\x2f\76\xd\12\11\x9\11\11\11\x9\x9\11\x9\x3c\x69\156\160\165\x74\x20\x74\171\160\145\75\42\150\x69\144\144\x65\x6e\x22\x20\x6e\x61\x6d\145\75\x22\162\x65\144\151\x72\145\143\164\137\164\x6f\x22\x20\x76\x61\154\165\145\75\x22" . esc_url($V9) . "\42\x20\57\x3e\xd\xa\x9\x9\11\x9\x9\11\x9\11\x9\74\x69\x6e\x70\x75\x74\x20\164\171\x70\x65\75\x22\x68\x69\x64\144\145\156\42\40\156\141\x6d\x65\75\x22\151\x64\160\x22\x20\166\x61\x6c\x75\x65\75\42" . esc_attr($fk["\x69\x64\160\137\x6e\x61\x6d\x65"]) . "\x22\x20\x2f\x3e\xd\12\11\x9\11\11\11\11\11\11\11\x3c\x66\157\x6e\164\x20\163\x69\x7a\x65\x3d\x22\53\61\42\x20\x73\x74\x79\154\x65\75\42\166\145\x72\x74\x69\143\141\154\x2d\x61\x6c\151\147\156\72\164\x6f\x70\x3b\42\x3e\40\74\x2f\x66\x6f\x6e\x74\76";
            $Wc = !empty($fk["\x63\165\x73\x74\x6f\x6d\x5f\154\157\x67\151\156\x5f\x74\x65\x78\x74"]) ? $fk["\143\x75\163\164\157\x6d\x5f\x6c\x6f\x67\151\156\137\x74\145\x78\x74"] : "\114\x6f\x67\x69\156\40\167\x69\164\150\40" . (!empty($fk["\x69\144\x70\137\144\x69\163\160\x6c\x61\171\x5f\156\x61\155\145"]) ? $fk["\151\144\x70\137\x64\151\163\160\154\x61\x79\137\x6e\141\155\x65"] : $fk["\x69\144\160\x5f\156\141\x6d\x65"]);
            echo "\x3c\x61\40\x68\162\145\146\75\x22\43\x22\x20\151\x64\x3d\42" . esc_attr($E8) . "\x22\x3e" . esc_html($Wc) . "\x3c\57\x61\76\x3c\57\146\x6f\x72\155\x3e";
            goto G8a;
            soV:
            $VP = new Mo_SAML_Plugin();
            echo $VP->mo_saml_get_sso_button_html($vi, $fk["\x69\x64\x70\137\156\x61\x6d\145"]);
            G8a:
            J_0:
            Xse:
        }
        Wex:
        ZpO:
        Yij:
    }
    public function mo_saml_widget_init()
    {
        if (!(isset($_REQUEST["\157\160\x74\x69\157\x6e"]) and $_REQUEST["\x6f\160\x74\x69\x6f\x6e"] == "\163\x61\x6d\x6c\137\165\163\145\x72\x5f\154\x6f\x67\157\165\x74")) {
            goto APt;
        }
        $user = SAMLSPUtilities::mo_saml_is_user_logged_in() ? wp_get_current_user() : null;
        if (empty($user)) {
            goto q8H;
        }
        wp_logout();
        q8H:
        APt:
    }
    function mo_saml_logout($Ur)
    {
        if (!(!session_id() || session_id() == '' || empty($_SESSION))) {
            goto F2s;
        }
        session_start();
        F2s:
        $BB = '';
        if (!empty($_SESSION["\155\157\137\x73\141\155\x6c"]["\154\x6f\147\x67\x65\x64\137\151\x6e\x5f\x77\x69\164\x68\137\x69\144\160"])) {
            goto TAG;
        }
        if (isset($_SESSION["\155\x6f\137\x67\x75\145\163\x74\x5f\x6c\157\147\x69\x6e"]["\154\157\x67\x67\145\x64\137\x69\x6e\137\151\144\160\137\156\141\x6d\145"])) {
            goto xj3;
        }
        return;
        goto mN5;
        xj3:
        $BB = $_SESSION["\155\157\137\147\165\x65\x73\164\x5f\x6c\157\x67\x69\x6e"]["\x6c\x6f\x67\147\145\144\137\x69\x6e\137\x69\144\160\137\156\x61\x6d\145"];
        mN5:
        goto Jls;
        TAG:
        $BB = $_SESSION["\155\x6f\x5f\163\x61\155\x6c"]["\154\157\x67\147\145\144\x5f\x69\x6e\x5f\x77\x69\164\x68\x5f\x69\x64\x70"];
        Jls:
        SAMLSPUtilities::mo_saml_check_is_extension_installed();
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\x6d\x6c\x5f\x69\144\x65\x6e\164\151\164\x79\x5f\160\162\157\x76\151\x64\145\162\163", true, $CP);
        $Sf = array();
        if (empty($rK[$BB])) {
            goto BeF;
        }
        $Sf = $rK[$BB];
        BeF:
        if (empty($Sf)) {
            goto ISm;
        }
        if (!empty($_REQUEST["\162\145\x64\x69\x72\x65\143\164\x5f\164\x6f"])) {
            goto BRV;
        }
        $E0 = wp_get_referer();
        goto Onl;
        BRV:
        $E0 = SAMLSPUtilities::mo_saml_is_array($_REQUEST["\162\145\144\x69\162\145\143\x74\x5f\164\157"]);
        Onl:
        if (!empty($E0)) {
            goto oG2;
        }
        $eo = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\x73\141\155\154\137\163\160\x5f\142\141\x73\x65\x5f\165\162\154", false, $CP);
        $E0 = !empty($eo) ? $eo : home_url();
        oG2:
        if (empty($E0)) {
            goto HDD;
        }
        $AD = parse_url($E0, PHP_URL_PATH);
        $BL = parse_url($E0, PHP_URL_QUERY);
        HDD:
        $AD = empty($AD) ? "\x2f" : $AD;
        if (!empty($BL)) {
            goto ZXB;
        }
        $E0 = $AD;
        goto UIq;
        ZXB:
        $E0 = $AD . "\x3f" . $BL;
        UIq:
        $E0 = apply_filters("\155\x6f\x5f\163\x61\x6d\x6c\137\x70\162\145\137\x6c\x6f\147\157\x75\x74\137\163\x6c\x6f\137\162\x65\x6c\141\171\x5f\x73\164\x61\x74\x65", $E0);
        $Wm = $Sf["\x73\x6c\157\x5f\x75\x72\x6c"];
        $Wm = apply_filters("\x6d\x6f\137\163\x61\x6d\x6c\x5f\154\157\x67\x6f\165\164\x5f\165\162\x6c", $Wm, $BB);
        $I0 = array_key_exists("\163\154\157\137\162\x65\163\x70\x6f\x6e\163\145\137\x75\162\154", $Sf) ? $Sf["\x73\154\157\137\162\145\x73\x70\157\156\x73\145\x5f\x75\x72\x6c"] : '';
        if (!empty($Wm) || !empty($I0)) {
            goto BUk;
        }
        SAMLSPUtilities::mo_saml_delete_plugin_cookies();
        wp_redirect($E0);
        exit;
        goto qdG;
        BUk:
        if (!(!session_id() || session_id() == '' || empty($_SESSION))) {
            goto lt7;
        }
        session_start();
        lt7:
        $JQ = $Sf["\163\154\157\137\x62\151\x6e\x64\151\x6e\147\137\164\171\160\145"];
        if (empty($_SESSION["\155\x6f\137\x73\141\155\x6c\137\154\x6f\x67\x6f\165\x74\x5f\x72\x65\161\165\x65\x73\x74"])) {
            goto pdb;
        }
        self::createLogoutResponseAndRedirect($Wm, $I0, $JQ, $Sf, $E0);
        exit;
        pdb:
        if (empty($Wm)) {
            goto yzV;
        }
        $user = get_user_by("\x69\x64", $Ur);
        $current_user = $user;
        if (isset($_SESSION["\155\x6f\137\147\165\x65\163\x74\137\x6c\x6f\147\x69\156"]["\x6e\x61\155\145\x49\x44"])) {
            goto WXr;
        }
        if (isset($_COOKIE["\x6e\x61\x6d\145\x49\x44"])) {
            goto Br4;
        }
        $T9 = get_user_meta($current_user->ID, "\x6d\x6f\x5f\163\x61\155\154\x5f\x6e\141\155\145\x5f\x69\144");
        delete_user_meta($current_user->ID, "\155\x6f\x5f\x73\x61\155\154\x5f\x6e\141\x6d\x65\x5f\151\144");
        goto lQV;
        WXr:
        $T9 = $_SESSION["\155\x6f\137\147\165\145\x73\164\x5f\x6c\x6f\x67\151\156"]["\x6e\141\155\x65\111\104"];
        goto lQV;
        Br4:
        $T9 = $_COOKIE["\156\x61\155\145\x49\104"];
        lQV:
        if (empty($T9)) {
            goto Rv1;
        }
        SAMLSPUtilities::mo_saml_delete_plugin_cookies();
        Rv1:
        $hK = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\x5f\x73\141\x6d\154\137\163\160\x5f\142\x61\x73\145\137\x75\x72\x6c", false, $CP);
        if (!empty($hK)) {
            goto AR2;
        }
        $hK = network_home_url();
        AR2:
        if (!(substr($hK, -1) == "\x2f")) {
            goto hBB;
        }
        $hK = substr($hK, 0, -1);
        hBB:
        if (!empty($Sf["\163\x61\155\154\137\x73\x70\x5f\145\x6e\x74\151\x74\171\x5f\151\x64"])) {
            goto YBo;
        }
        $g5 = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\137\163\141\x6d\x6c\137\x73\160\137\x65\x6e\164\151\x74\171\x5f\x69\144", false, $CP);
        if (!empty($g5)) {
            goto ibz;
        }
        $g5 = $hK . "\57\167\160\55\143\x6f\156\x74\x65\x6e\164\57\x70\154\165\x67\x69\x6e\x73\57\x6d\151\x6e\x69\x6f\162\x61\x6e\147\145\55\x73\x61\155\154\x2d\x32\60\55\x73\x69\x6e\x67\154\x65\55\163\151\147\156\55\x6f\x6e\x2f";
        ibz:
        goto d2R;
        YBo:
        $g5 = $Sf["\x73\x61\155\x6c\x5f\x73\160\137\x65\156\x74\x69\164\171\137\x69\x64"];
        d2R:
        $xF = $Wm;
        $nq = $Sf["\x6e\x61\155\x65\x69\144\x5f\146\x6f\162\x6d\x61\164"];
        if (isset($_SESSION["\x6d\157\137\147\165\145\x73\x74\x5f\154\157\x67\151\156"]["\x73\145\x73\163\x69\157\x6e\111\156\144\x65\170"])) {
            goto CMJ;
        }
        if (isset($_COOKIE["\x73\145\163\163\151\x6f\156\x49\x6e\144\145\x78"])) {
            goto bbz;
        }
        $X9 = get_user_meta($current_user->ID, "\155\x6f\137\163\x61\155\x6c\x5f\x73\x65\x73\163\151\x6f\156\x5f\151\156\x64\x65\170");
        delete_user_meta($current_user->ID, "\x6d\157\x5f\x73\x61\x6d\x6c\137\x73\x65\163\x73\x69\x6f\x6e\137\151\x6e\x64\145\170");
        goto VsZ;
        CMJ:
        $X9 = $_SESSION["\x6d\x6f\x5f\x67\x75\145\163\164\137\x6c\x6f\147\x69\x6e"]["\x73\145\x73\163\151\x6f\x6e\x49\156\x64\x65\x78"];
        goto VsZ;
        bbz:
        $X9 = $_COOKIE["\x73\x65\x73\x73\x69\x6f\156\x49\156\144\x65\x78"];
        VsZ:
        $r2 = SAMLSPUtilities::createLogoutRequest($T9, $g5, $xF, $JQ, $X9, $nq);
        $ML = $Sf["\162\x65\161\165\145\x73\x74\137\163\x69\147\156\145\x64"];
        if (empty($JQ) || $JQ == "\110\164\164\x70\x52\145\144\x69\x72\x65\x63\164") {
            goto D20;
        }
        if (!($ML == "\165\156\x63\150\145\143\153\145\144")) {
            goto oxD;
        }
        $IZ = base64_encode($r2);
        SAMLSPUtilities::postSAMLRequest($Wm, $IZ, $E0);
        exit;
        oxD:
        $IZ = SAMLSPUtilities::signXML($r2, $Sf, "\116\x61\155\x65\x49\104");
        SAMLSPUtilities::postSAMLRequest($Wm, $IZ, $E0);
        goto n6H;
        D20:
        $jG = $Wm;
        if (strpos($Wm, "\77") !== false) {
            goto XAM;
        }
        $jG .= "\x3f";
        goto U_A;
        XAM:
        $jG .= "\46";
        U_A:
        if (!($ML == "\x75\x6e\143\150\x65\143\153\145\144")) {
            goto I4H;
        }
        $jG .= "\123\x41\x4d\x4c\122\145\161\x75\x65\163\164\75" . $r2 . "\46\x52\x65\154\141\x79\x53\x74\141\x74\145\x3d" . urlencode($E0);
        header("\x63\141\x63\150\x65\55\x63\x6f\156\164\x72\157\154\x3a\40\155\141\170\55\x61\x67\145\x3d\60\54\x20\x70\x72\151\166\x61\x74\145\54\x20\x6e\157\x2d\163\164\x6f\x72\145\54\x20\156\157\x2d\143\x61\x63\x68\145\54\40\x6d\165\163\x74\x2d\x72\x65\166\x61\x6c\x69\x64\141\164\x65");
        header("\114\x6f\x63\x61\164\151\x6f\x6e\x3a\40" . $jG);
        exit;
        I4H:
        $r2 = "\123\101\115\114\122\145\x71\x75\x65\x73\x74\75" . $r2 . "\x26\122\145\x6c\x61\x79\x53\164\141\164\x65\75" . urlencode($E0) . "\x26\x53\151\x67\x41\x6c\147\x3d" . urlencode(XMLSecurityKey::RSA_SHA256);
        $EJ = array("\x74\171\160\x65" => "\x70\162\151\x76\141\x74\x65");
        $R2 = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, $EJ);
        $LB = mo_saml_get_sp_private_key_for_idp($Sf);
        $R2->loadKey($LB, false);
        $Nk = new XMLSecurityDSig();
        $lo = $R2->signData($r2);
        $lo = base64_encode($lo);
        $jG .= $r2 . "\x26\123\151\x67\156\x61\x74\165\162\x65\75" . urlencode($lo);
        header("\x63\x61\143\150\145\x2d\x63\157\156\164\162\x6f\154\x3a\x20\155\x61\170\55\x61\147\x65\x3d\60\54\x20\160\x72\151\166\141\164\145\x2c\40\x6e\x6f\x2d\163\164\157\x72\145\54\40\156\x6f\x2d\143\x61\143\x68\x65\x2c\40\155\x75\x73\164\55\x72\145\x76\x61\x6c\x69\144\141\x74\x65");
        header("\x4c\157\143\x61\164\x69\x6f\156\x3a\x20" . $jG);
        exit;
        n6H:
        yzV:
        qdG:
        ISm:
    }
    function createLogoutResponseAndRedirect($Wm, $I0, $JQ, $Sf, $Ih)
    {
        if (empty($I0)) {
            goto KhS;
        }
        $Wm = $I0;
        KhS:
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $hK = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\163\x61\x6d\x6c\x5f\x73\x70\137\142\141\163\145\137\165\162\154", false, $CP);
        if (!empty($hK)) {
            goto egh;
        }
        $hK = home_url();
        egh:
        $Vj = $_SESSION["\155\157\137\163\141\155\154\137\x6c\x6f\x67\157\x75\164\137\162\x65\x71\165\145\163\x74"];
        $Rn = !empty($Ih) ? $Ih : $_SESSION["\x6d\157\137\x73\x61\x6d\x6c\x5f\x6c\157\147\157\165\164\137\162\145\154\141\171\x5f\163\x74\x61\x74\x65"];
        $ML = $Sf["\162\145\161\165\x65\163\x74\137\163\x69\147\x6e\x65\x64"];
        if (!empty($Rn) && (filter_var($Rn, FILTER_VALIDATE_URL) || parse_url(home_url(), PHP_URL_HOST) === parse_url($Rn, PHP_URL_HOST))) {
            goto Cqb;
        }
        wp_redirect($hK);
        goto jhf;
        Cqb:
        wp_redirect($Rn);
        jhf:
        unset($_SESSION["\155\157\x5f\163\141\155\x6c\137\154\157\147\157\165\164\x5f\162\x65\161\165\145\163\x74"]);
        unset($_SESSION["\x6d\157\137\x73\x61\x6d\x6c\x5f\154\x6f\x67\x6f\165\164\137\x72\145\x6c\141\x79\137\x73\x74\x61\164\x65"]);
        SAMLSPUtilities::mo_saml_delete_plugin_cookies();
        $nI = SAMLSPUtilities::mo_saml_safe_load_xml($Vj, Mo_Saml_Error_Codes::$error_codes["\127\120\123\101\x4d\x4c\105\x52\x52\x30\x32\65"]);
        $Vj = $nI->firstChild;
        if (!($Vj->localName == "\114\x6f\147\157\x75\164\x52\x65\x71\165\x65\x73\164")) {
            goto Dx4;
        }
        $uu = new SAML2_LogoutRequest($Vj);
        $Uy = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\x6f\137\x73\x61\155\154\x5f\163\x70\137\145\156\x74\x69\x74\171\x5f\x69\x64", false, $CP);
        if (!empty($Uy)) {
            goto mgt;
        }
        $Uy = $hK . "\x2f\167\x70\55\x63\x6f\x6e\x74\145\156\164\57\160\154\165\147\151\x6e\x73\57\155\x69\x6e\x69\157\162\x61\x6e\147\x65\55\x73\x61\155\154\55\x32\x30\55\x73\151\x6e\147\154\145\55\163\x69\147\156\55\x6f\156\x2f";
        mgt:
        $Uy = !empty($Sf["\x73\x61\x6d\x6c\x5f\163\x70\x5f\145\x6e\164\151\x74\x79\x5f\151\x64"]) ? $Sf["\163\x61\x6d\x6c\137\163\x70\x5f\145\x6e\164\x69\164\171\x5f\x69\x64"] : $Uy;
        $xF = $Wm;
        $A2 = SAMLSPUtilities::createLogoutResponse($uu->getId(), $Uy, $xF, $JQ);
        if (empty($JQ) || $JQ == "\110\x74\x74\x70\x52\145\x64\151\162\x65\x63\x74") {
            goto kYA;
        }
        if (!($ML == "\x75\x6e\143\150\x65\x63\153\145\144")) {
            goto m6C;
        }
        $IZ = base64_encode($A2);
        SAMLSPUtilities::postSAMLResponse($Wm, $IZ, $Rn);
        exit;
        m6C:
        $IZ = SAMLSPUtilities::signXML($A2, $Sf, "\123\x74\x61\x74\165\x73");
        SAMLSPUtilities::postSAMLResponse($Wm, $IZ, $Rn);
        goto OF7;
        kYA:
        $jG = $Wm;
        if (strpos($Wm, "\x3f") !== false) {
            goto VEN;
        }
        $jG .= "\77";
        goto oWb;
        VEN:
        $jG .= "\x26";
        oWb:
        if (!($ML == "\x75\156\x63\x68\145\143\153\145\x64")) {
            goto MxA;
        }
        $jG .= "\x53\101\x4d\114\122\145\x73\160\157\156\x73\x65\75" . $A2 . "\46\122\x65\x6c\x61\x79\x53\x74\141\x74\x65\x3d" . urlencode($Rn);
        header("\x4c\157\143\141\164\x69\x6f\x6e\72\x20" . $jG);
        exit;
        MxA:
        $jG .= "\x53\101\x4d\x4c\122\145\163\x70\157\x6e\x73\x65\x3d" . $A2 . "\x26\x52\x65\154\141\171\123\x74\141\164\145\75" . urlencode($Rn);
        header("\x4c\157\x63\141\x74\x69\157\x6e\72\40" . $jG);
        exit;
        OF7:
        Dx4:
    }
}
function mo_login_validate()
{
    if (Mo_License_Service::is_customer_license_verified()) {
        goto kmw;
    }
    return;
    kmw:
    if (!(isset($_REQUEST["\x6f\160\164\151\157\156"]) && in_array($_REQUEST["\157\x70\x74\x69\x6f\156"], Mo_Saml_Plugin_Setting_Options::getConstants()))) {
        goto P5Q;
    }
    SAMLSPUtilities::mo_saml_check_is_extension_installed();
    P5Q:
    if (!(!empty($_REQUEST["\157\160\x74\x69\157\156"]) && $_REQUEST["\x6f\160\x74\x69\x6f\156"] == "\155\x6f\x73\141\155\154\137\x6d\x65\164\141\x64\141\164\x61" && Mo_License_Service::is_customer_license_valid())) {
        goto rQ8;
    }
    miniorange_generate_metadata();
    rQ8:
    if (!(!empty($_REQUEST["\x6f\160\x74\x69\x6f\x6e"]) && $_REQUEST["\157\x70\164\151\x6f\x6e"] == "\x65\x78\160\x6f\162\164\137\143\x6f\x6e\x66\151\x67\165\162\x61\164\x69\157\156" && check_admin_referer("\x65\170\160\157\162\x74\x5f\103\x6f\156\146\x69\x67\x75\162\141\x74\151\157\x6e"))) {
        goto Jzg;
    }
    if (!(current_user_can("\155\141\156\x61\x67\145\x5f\x6f\160\164\151\x6f\156\x73") && Mo_License_Service::is_customer_license_valid())) {
        goto NoH;
    }
    miniorange_import_export(true);
    NoH:
    exit;
    Jzg:
    if (mo_saml_is_sp_configured()) {
        goto Ojq;
    }
    return;
    Ojq:
    $CP = EnvironmentHelper::getCurrentEnvironment();
    $HD = EnvironmentHelper::getOptionForSelectedEnvironment(mo_options_enum_sso_login::Relay_states, true, $CP);
    $aO = !empty($HD["\154\x6f\147\x69\156\x5f\162\145\154\141\x79\x5f\x73\x74\x61\x74\x65"]) ? $HD["\x6c\157\147\x69\156\x5f\x72\x65\x6c\141\x79\x5f\163\164\x61\164\145"] : array();
    if (!(!empty($_REQUEST["\157\x70\164\151\157\x6e"]) && ("\163\x61\155\x6c\x5f\x75\163\145\x72\x5f\x6c\x6f\x67\x69\156" === $_REQUEST["\157\160\164\x69\x6f\156"] || "\164\x65\163\164\x43\157\x6e\146\x69\x67" === $_REQUEST["\x6f\x70\x74\x69\157\x6e"] || "\164\x65\163\x74\123\x53\117\114\157\x67\x69\156" === $_REQUEST["\x6f\160\x74\x69\x6f\156"]))) {
        goto VW0;
    }
    if (!(SAMLSPUtilities::mo_saml_is_user_logged_in() && $_REQUEST["\x6f\x70\164\151\157\x6e"] != "\164\x65\x73\x74\x43\x6f\x6e\146\x69\147" && "\x74\145\163\164\123\123\x4f\x4c\x6f\147\x69\x6e" != $_REQUEST["\157\160\164\x69\157\156"])) {
        goto ASz;
    }
    if (empty($_REQUEST["\162\145\144\x69\162\145\143\164\137\x74\157"])) {
        goto yBo;
    }
    $E0 = SAMLSPUtilities::mo_saml_is_array($_REQUEST["\162\145\144\x69\x72\145\143\164\137\x74\x6f"]);
    wp_safe_redirect($E0);
    exit;
    yBo:
    return;
    ASz:
    if (!("\x63\x68\x65\x63\x6b\145\x64" === get_option("\x6d\157\x5f\x65\156\141\142\x6c\x65\x5f\x6d\165\x6c\164\151\160\x6c\145\137\x6c\151\143\145\156\x73\145\x73") && !EnvironmentHelper::isSelectedEnvironmentDefault() && $_REQUEST["\157\x70\164\151\157\x6e"] === "\x74\145\163\x74\x43\x6f\x6e\146\x69\x67")) {
        goto PPA;
    }
    $Sx = EnvironmentHelper::getSelectedEnvironment();
    $Oz = admin_url("\57\141\x64\155\x69\x6e\55\141\x6a\x61\x78\56\x70\150\160");
    echo "\x3c\x64\151\x76\40\163\164\171\154\x65\x3d\42\x66\157\156\164\x2d\146\x61\155\x69\154\171\72\x43\x61\x6c\151\x62\x72\151\73\x70\x61\144\x64\151\x6e\x67\72\60\40\63\45\73\42\x3e\15\12\x20\x20\x20\40\40\x20\x20\x20\x20\x20\40\40\x20\40\x20\40\x3c\144\x69\166\40\163\164\171\x6c\145\75\42\143\157\154\157\x72\x3a\40\43\141\x39\x34\64\x34\62\73\x64\151\163\160\x6c\x61\x79\x3a\142\154\x6f\143\x6b\x3b\164\x65\x78\x74\55\141\154\151\147\x6e\x3a\x63\x65\x6e\x74\x65\x72\73\155\141\x72\147\151\x6e\x2d\142\x6f\164\x74\x6f\x6d\x3a\x34\x25\73\x66\157\x6e\x74\55\163\x69\172\145\72\61\x34\x70\164\73\x22\76\xd\xa\x20\x20\x20\x20\x20\x20\x20\x20\x20\40\x20\x20\x20\40\x20\40\40\40\x20\40\x3c\151\155\147\x20\x73\x74\171\154\x65\75\x22\167\x69\144\x74\x68\x3a\61\x35\x25\x3b\x22\x73\162\x63\75\x22" . esc_url(SAMLSPUtilities::mo_saml_get_plugin_base_url()) . "\151\x6d\x61\147\145\x73\57\167\162\157\156\147\56\x77\145\x62\x70\42\x3e\74\142\162\76\x3c\142\x72\76\15\12\40\x20\x20\x20\x20\40\x20\40\x20\x20\x20\x20\40\x20\40\40\40\x20\40\40\x3c\x62\76\124\145\x73\x74\x20\143\x6f\x6e\146\151\147\165\162\x61\164\x69\157\156\x20\x66\x61\x69\x6c\145\x64\x3a\x20\x4f\x70\x65\162\141\x74\151\x6f\156\x20\x6e\x6f\x74\40\163\x75\x70\x70\157\x72\x74\x65\144\74\x2f\142\76\15\12\x20\40\40\40\x20\40\40\x20\40\x20\40\40\x20\40\x20\x20\x3c\57\x64\151\166\x3e\xd\xa\40\40\40\40\40\x20\40\40\x20\40\40\x20\x20\x20\40\40\x3c\144\x69\x76\x20\163\164\x79\x6c\x65\75\42\155\141\x72\147\151\x6e\x2d\142\157\164\x74\x6f\155\x3a\x32\x30\160\x78\x3b\42\76\xd\12\40\x20\x20\40\x20\x20\x20\x20\40\40\x20\40\x20\x20\40\40\40\40\40\x20\74\160\x3e\xd\12\x20\40\x20\40\40\x20\40\40\40\x20\x20\40\40\x20\x20\x20\x20\x20\x20\40\x20\x20\x20\x20\131\157\x75\40\150\141\166\x65\40\x73\145\x6c\145\x63\x74\x65\144\40\x74\150\145\40\74\142\76" . esc_html($Sx) . "\x3c\x2f\x62\76\40\145\156\166\151\x72\x6f\156\x6d\145\156\x74\40\x77\x68\x69\143\150\40\151\163\x20\x6e\157\x74\x20\171\x6f\x75\x72\40\143\x75\162\162\145\156\164\x20\x65\156\166\151\x72\157\x6e\x6d\x65\156\x74\56\x20\101\163\40\x70\145\162\40\x74\150\x65\x20\123\x41\115\x4c\40\160\162\x6f\164\157\x63\x6f\x6c\40\x79\x6f\x75\x20\x63\x61\x6e\x20\x6f\x6e\154\171\x20\160\x65\x72\x66\157\x72\x6d\x20\164\x65\163\x74\x20\x63\157\156\x66\151\147\165\x72\141\x74\x69\x6f\156\40\x66\157\162\40\x61\156\40\145\x6e\166\151\x72\x6f\156\x6d\145\x6e\x74\40\x61\146\x74\x65\162\x20\x79\157\x75\x20\x6d\x69\x67\x72\141\164\145\40\x74\157\x20\x69\x74\x2e\x3c\142\162\x3e\x3c\142\x72\76\15\12\40\40\40\x20\x20\40\x20\x20\x20\x20\x20\40\40\40\40\x20\x20\x20\40\40\40\x20\x20\x20\74\x62\76\x4e\x6f\x74\x65\x3a\74\x2f\142\x3e\x20\131\157\x75\40\x63\x61\x6e\x20\164\x65\x73\164\40\x74\x68\x65\x20\x63\x6f\x6e\x66\151\147\165\x72\141\x74\x69\x6f\156\x73\40\146\157\162\x20\171\157\x75\162\x20\x63\x75\x72\162\x65\x6e\x74\40\145\156\x76\x69\x72\157\x6e\155\x65\x6e\164\x20\x28\x3c\142\x3e" . esc_html($CP) . "\74\57\142\76\x29\x20\x61\146\x74\x65\162\40\x73\167\151\164\x63\150\x69\156\147\x20\x74\x6f\40\x69\x74\x20\x69\x6e\40\164\x68\145\x20\160\154\165\147\x69\x6e\56\40\x49\x66\x20\x79\x6f\165\x20\167\151\x73\150\x20\x74\x6f\x20\x64\157\40\163\157\x20\160\154\x65\141\x73\x65\x20\143\x6c\151\143\x6b\40\157\x6e\x20\164\x68\145\x20\123\x65\154\145\143\164\x20\103\165\x72\x72\x65\x6e\164\40\105\156\x76\x69\x72\157\156\x6d\145\x6e\164\x20\142\165\164\164\x6f\x6e\40\x62\145\x6c\157\167\x20\x61\x6e\x64\40\x63\154\151\143\x6b\40\157\x6e\x20\x54\x65\163\164\40\x43\157\156\146\x69\x67\x75\162\141\164\x69\x6f\156\x20\x66\x6f\x72\x20\171\157\165\x72\40\111\x44\x50\x2e\xd\xa\x20\40\x20\40\40\x20\x20\x20\x20\40\x20\x20\x20\x20\x20\40\x20\x20\x20\x20\x3c\x2f\x70\76\xd\xa\40\40\x20\40\x20\x20\40\x20\x20\40\x20\40\x20\40\x20\x20\74\x2f\144\151\166\76\15\12\x20\x20\40\x20\x20\40\x20\40\40\40\x20\40\x20\x20\40\40\x3c\x64\x69\x76\x20\40\x73\164\171\x6c\145\x3d\42\144\x69\x73\x70\154\141\171\x3a\40\146\154\x65\170\73\x20\152\x75\163\x74\x69\x66\171\x2d\x63\x6f\156\x74\x65\x6e\x74\72\40\x63\145\156\164\145\162\x3b\x22\76\xd\12\x20\x20\x20\40\40\40\x20\40\40\40\x20\x20\x20\40\40\40\40\x20\x20\x20\74\151\156\160\x75\164\40\164\171\160\x65\x3d\42\x62\x75\x74\x74\x6f\x6e\x22\x20\x73\164\x79\154\x65\75\x22\160\141\x64\x64\x69\156\x67\x3a\61\45\x3b\142\141\143\x6b\147\162\x6f\x75\x6e\x64\x3a\40\x23\60\x30\71\x31\x43\x44\x20\156\x6f\x6e\x65\40\162\145\x70\145\x61\x74\40\x73\143\x72\x6f\x6c\x6c\x20\x30\x25\x20\60\45\73\x63\x75\x72\x73\157\x72\x3a\x20\x70\x6f\151\156\x74\145\x72\73\x66\x6f\x6e\164\55\163\x69\x7a\x65\x3a\x31\x35\x70\170\x3b\142\157\x72\x64\145\162\x2d\167\151\144\x74\150\72\x20\61\x70\170\73\x62\x6f\x72\x64\x65\162\x2d\x73\x74\171\154\x65\x3a\40\x73\157\154\151\144\73\142\x6f\x72\144\145\162\55\162\x61\144\x69\x75\x73\x3a\40\63\x70\x78\x3b\x77\150\151\164\x65\x2d\x73\x70\141\143\x65\72\40\156\157\167\162\x61\160\73\142\x6f\170\x2d\x73\x69\172\151\156\x67\x3a\x20\x62\157\x72\144\145\162\x2d\x62\157\x78\73\x62\x6f\x72\144\x65\162\x2d\x63\x6f\x6c\x6f\162\72\x20\x23\60\60\x37\x33\x41\x41\x3b\142\157\170\55\x73\150\141\x64\157\x77\72\x20\60\160\170\x20\x31\160\x78\40\x30\x70\170\x20\162\147\x62\x61\50\x31\62\x30\54\40\x32\60\x30\54\x20\62\63\x30\x2c\x20\x30\x2e\66\x29\x20\151\156\163\145\164\x3b\x63\157\154\x6f\162\x3a\x20\x23\x46\x46\106\x3b\x22\x20\x76\141\x6c\165\145\75\42\123\x65\154\x65\x63\164\x20\x43\165\162\162\145\156\x74\x20\105\156\x76\151\x72\157\x6e\155\x65\x6e\164\x22\x20\157\156\x63\x6c\x69\x63\153\75\42\163\165\142\x6d\x69\x74\123\145\154\x65\x63\164\x45\x6e\x76\x69\162\x6f\156\155\145\x6e\x74\x46\x6f\162\x6d\50\51\x3b\x22\x2f\76\x26\156\142\163\x70\46\156\x62\x73\x70\xd\xa\x20\40\40\x20\40\x20\40\40\x20\x20\40\40\x20\x20\40\x20\x20\40\x20\x20\x3c\151\156\x70\x75\164\40\164\x79\160\145\75\42\142\x75\x74\x74\x6f\156\x22\40\x73\x74\171\x6c\x65\x3d\42\160\x61\x64\144\151\x6e\x67\x3a\x31\x25\x3b\167\151\x64\x74\150\72\61\60\60\x70\170\73\x62\x61\x63\x6b\147\162\157\165\156\144\x3a\40\x23\x30\x30\x39\61\x43\x44\40\156\157\156\145\40\162\x65\x70\x65\x61\164\40\x73\143\x72\157\154\154\40\x30\45\x20\60\45\73\143\165\x72\163\157\162\72\x20\x70\157\151\x6e\164\145\162\73\146\x6f\156\164\55\x73\x69\172\145\72\61\x35\x70\x78\x3b\x62\157\162\x64\x65\162\55\167\151\144\x74\x68\72\x20\61\160\170\x3b\x62\157\x72\x64\x65\x72\x2d\163\x74\x79\154\145\72\x20\163\x6f\x6c\151\x64\x3b\142\157\x72\144\145\x72\55\x72\x61\x64\x69\x75\x73\72\40\x33\x70\x78\73\167\150\151\x74\x65\x2d\163\160\x61\143\x65\x3a\40\x6e\157\167\x72\141\x70\x3b\142\x6f\170\x2d\163\151\172\151\156\x67\x3a\x20\x62\157\x72\x64\145\162\55\142\x6f\x78\73\x62\157\162\x64\145\x72\x2d\x63\x6f\x6c\x6f\x72\72\40\43\60\60\x37\63\101\101\73\142\x6f\170\55\x73\150\x61\x64\x6f\167\x3a\x20\60\x70\x78\40\x31\x70\170\x20\x30\160\170\40\x72\147\x62\x61\50\x31\62\x30\x2c\x20\x32\60\60\x2c\40\x32\x33\x30\x2c\x20\x30\x2e\x36\x29\x20\151\156\x73\x65\164\x3b\x63\x6f\x6c\157\162\x3a\40\43\106\x46\x46\x3b\x22\40\x76\x61\154\165\x65\x3d\42\103\154\157\163\145\x22\x20\x6f\156\x63\x6c\x69\x63\153\x3d\42\x73\145\154\146\x2e\x63\x6c\157\x73\x65\x28\x29\73\42\x2f\x3e\15\12\40\x20\40\40\40\x20\x20\40\40\x20\40\40\x20\40\x20\40\x3c\x2f\144\151\x76\x3e\15\xa\40\x20\x20\40\40\40\x20\x20\x20\40\40\x20\74\x2f\x64\x69\166\x3e\15\12\x20\40\x20\40\40\40\40\x20\x20\40\40\x20\74\163\143\162\151\x70\164\x3e\xd\12\x20\x20\x20\x20\40\40\40\x20\x20\x20\40\40\40\x20\40\40\146\165\156\143\164\151\157\x6e\40\163\x75\x62\155\x69\x74\123\145\x6c\145\x63\164\x45\x6e\166\151\162\157\x6e\x6d\145\x6e\164\106\x6f\x72\155\x28\51\173\xd\xa\40\x20\x20\x20\40\40\40\40\x20\40\40\40\40\40\40\40\40\x20\40\x20\166\141\x72\x20\165\162\154\40\75\40\x22" . esc_url($Oz) . "\42\73\xd\12\x20\x20\40\x20\40\x20\x20\x20\x20\x20\40\x20\x20\40\x20\40\x20\x20\x20\40\x76\x61\162\40\146\x6f\162\155\104\141\x74\x61\40\x3d\40\x6e\145\x77\40\106\157\162\x6d\104\x61\164\x61\50\51\x3b\xd\xa\x20\x20\40\x20\x20\40\40\x20\40\40\x20\40\40\40\40\x20\x20\x20\x20\40\x66\x6f\162\x6d\104\x61\x74\141\56\x61\160\x70\145\x6e\144\x28\42\x61\x63\164\x69\x6f\x6e\x22\x2c\x20\x22\155\157\x5f\163\141\155\154\x5f\x63\x68\141\x6e\x67\x65\137\x65\x6e\x76\151\162\x6f\x6e\x6d\145\156\164\x22\51\73\15\xa\40\40\x20\x20\x20\x20\x20\40\40\x20\40\40\40\40\x20\40\40\40\x20\x20\xd\xa\40\x20\40\40\x20\40\x20\40\x20\x20\40\40\x20\x20\40\x20\x20\40\x20\40\146\145\164\143\150\x28\165\162\154\54\40\x7b\xd\xa\x20\x20\x20\x20\x20\x20\40\x20\40\x20\x20\x20\x20\40\x20\40\x20\x20\40\x20\40\x20\40\40\155\x65\164\x68\157\144\72\x20\x22\120\x4f\x53\124\42\54\15\xa\x20\40\40\x20\40\x20\x20\40\40\x20\x20\x20\x20\x20\40\x20\x20\x20\x20\40\x20\40\x20\40\142\157\144\x79\72\x20\146\x6f\162\155\104\141\x74\x61\15\12\40\40\x20\x20\40\x20\x20\40\40\x20\x20\x20\x20\40\x20\x20\40\x20\x20\40\175\x29\15\xa\x20\x20\x20\40\x20\40\x20\40\40\40\40\40\x20\x20\40\x20\x20\40\40\x20\x2e\164\150\x65\156\50\162\145\163\160\x6f\156\x73\x65\x20\x3d\x3e\40\x7b\15\xa\40\x20\40\40\x20\40\x20\x20\40\x20\x20\x20\x20\40\40\x20\40\40\x20\x20\x20\x20\x20\x20\151\x66\x20\x28\162\145\163\x70\157\156\163\x65\x2e\x6f\153\51\40\x7b\15\12\x20\40\40\x20\40\40\40\x20\40\x20\x20\40\40\x20\40\40\40\40\40\40\40\x20\x20\x20\40\40\x20\x20\x69\146\40\x28\x77\151\x6e\144\x6f\167\x2e\x6f\160\145\156\145\x72\x29\x20\173\15\12\x20\40\x20\40\x20\40\x20\40\x20\x20\40\x20\40\40\40\x20\x20\x20\x20\40\40\40\40\40\40\x20\40\x20\x20\40\x20\40\167\x69\156\144\157\167\56\157\x70\145\156\145\x72\x2e\154\157\x63\141\x74\x69\157\x6e\56\x72\x65\154\157\141\144\x28\x29\73\15\12\x20\x20\x20\x20\40\x20\x20\x20\40\40\40\x20\40\40\x20\x20\x20\40\40\x20\40\40\40\40\40\40\x20\40\175\15\12\40\x20\x20\x20\40\40\40\40\40\40\40\40\40\40\40\40\40\40\40\40\40\x20\x20\x20\x20\40\40\40\x73\x65\x6c\146\56\143\154\x6f\163\145\50\51\x3b\xd\12\40\40\40\x20\40\x20\x20\x20\40\40\40\40\40\40\40\x20\x20\x20\x20\40\40\x20\40\40\x7d\15\xa\40\40\40\40\40\x20\x20\40\x20\40\40\40\x20\40\40\40\x20\40\40\x20\175\51\15\xa\40\40\x20\x20\40\x20\40\x20\x20\x20\40\40\x20\40\40\40\x20\40\x20\x20\x2e\143\141\x74\143\150\50\145\x72\x72\157\x72\40\x3d\x3e\x20\173\xd\12\x20\40\40\x20\40\40\40\x20\40\x20\x20\40\x20\x20\x20\40\x20\x20\40\40\40\40\x20\x20\143\157\156\x73\157\154\145\x2e\145\162\x72\x6f\x72\50\x22\116\145\x74\x77\x6f\x72\153\x20\x65\162\x72\157\162\72\42\x2c\40\x65\162\x72\157\162\x29\x3b\15\xa\x20\40\x20\x20\40\x20\40\40\x20\x20\x20\40\x20\x20\x20\40\x20\x20\40\40\175\51\73\15\12\40\40\40\x20\40\x20\40\x20\40\40\x20\x20\x20\40\x20\40\x7d\15\12\40\40\x20\x20\40\40\x20\x20\x20\x20\x20\x20\x3c\x2f\x73\x63\162\151\x70\x74\76";
    exit;
    PPA:
    if (!mo_saml_is_sp_configured($CP)) {
        goto U02;
    }
    SAMLSPUtilities::mo_saml_disable_extra_idps($CP);
    $gu = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\x6d\154\x5f\151\x64\145\156\x74\x69\x74\171\137\160\162\157\x76\151\x64\145\162\163", true, $CP);
    $rK = array_change_key_case($gu, CASE_LOWER);
    if (!empty($_REQUEST["\x69\144\x70"])) {
        goto EUD;
    }
    if (!empty(EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\155\x6c\137\x64\x65\x66\x61\x75\154\164\137\151\144\160", false, $CP))) {
        goto hDI;
    }
    goto tY2;
    EUD:
    $Z1 = strtolower($_REQUEST["\151\x64\x70"]);
    goto tY2;
    hDI:
    $Z1 = strtolower(EnvironmentHelper::getOptionForSelectedEnvironment("\163\x61\x6d\154\x5f\x64\x65\146\141\x75\154\164\137\151\144\160", false, $CP));
    tY2:
    if (!empty($Z1) && !empty($rK[$Z1])) {
        goto xab;
    }
    Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\127\120\x53\101\x4d\114\105\122\x52\60\x33\66"]);
    goto l7M;
    xab:
    $fk = $rK[$Z1];
    l7M:
    if (empty($_REQUEST["\x65\x6e\x74\151\x74\171\x49\104"])) {
        goto WL6;
    }
    $BB = getIdpNameFromEntityId($gu, $_REQUEST["\x65\x6e\164\151\164\171\111\104"]);
    if ($BB) {
        goto K83;
    }
    Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\x57\x50\123\x41\x4d\114\x45\122\122\x30\63\66"]);
    K83:
    $fk = $gu[$BB];
    WL6:
    if (!empty($fk["\x65\156\x61\x62\154\145\137\151\144\160"])) {
        goto w1B;
    }
    if (!($_REQUEST["\157\160\164\x69\x6f\156"] === "\x73\x61\155\x6c\137\x75\163\145\162\x5f\x6c\x6f\x67\x69\x6e")) {
        goto Utc;
    }
    throw new Mo_SAML_IDP_Status_Inactive_Exception("\x49\x44\x50\40\x4e\x6f\x74\40\105\x6e\141\142\x6c\145\x64\x2e");
    Utc:
    w1B:
    $hK = EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\x5f\163\x61\155\x6c\x5f\x73\160\137\142\x61\x73\x65\x5f\x75\162\x6c", false, $CP);
    if (!empty($hK)) {
        goto BZ8;
    }
    $hK = home_url();
    BZ8:
    if ($_REQUEST["\x6f\x70\x74\x69\x6f\156"] == "\164\x65\163\164\x43\x6f\x6e\146\x69\147" and !empty($_REQUEST["\156\x65\x77\143\145\x72\x74"])) {
        goto WLa;
    }
    if ($_REQUEST["\x6f\160\164\151\x6f\x6e"] == "\x74\145\163\164\103\157\156\146\151\147") {
        goto tIF;
    }
    if ($_REQUEST["\x6f\160\164\151\x6f\x6e"] == "\x74\145\x73\164\x53\x53\x4f\x4c\x6f\147\151\156") {
        goto Wyx;
    }
    if (!empty($_REQUEST["\162\145\144\151\162\x65\x63\x74\137\x74\x6f"])) {
        goto Pg8;
    }
    $YL = wp_get_referer();
    if (!empty($YL)) {
        goto Vnx;
    }
    $E0 = home_url(remove_query_arg(array("\157\x70\x74\151\157\156", "\x69\144\x70"), $_SERVER["\122\105\121\x55\105\123\124\137\125\x52\x49"]));
    goto mwp;
    Vnx:
    $Fi = parse_url($YL, PHP_URL_QUERY);
    if (!empty($Fi)) {
        goto iPq;
    }
    $E0 = $YL;
    goto QBe;
    iPq:
    parse_str($Fi, $e_);
    $E0 = isset($e_["\162\145\144\x69\162\145\x63\164\x5f\x74\x6f"]) ? SAMLSPUtilities::mo_saml_is_array($e_["\162\x65\144\151\x72\x65\x63\164\x5f\164\157"]) : $YL;
    QBe:
    mwp:
    goto vPe;
    WLa:
    $E0 = "\164\145\x73\x74\116\x65\167\x43\x65\x72\x74\151\146\151\x63\141\x74\145";
    goto vPe;
    tIF:
    if (!(!is_user_logged_in() || !current_user_can("\155\141\x6e\x61\147\145\137\157\160\164\x69\x6f\x6e\163"))) {
        goto Ihl;
    }
    Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\x57\120\x53\101\x4d\114\x45\122\x52\60\x33\x38"]);
    Ihl:
    $E0 = "\164\x65\x73\x74\126\141\x6c\x69\144\141\x74\145";
    goto vPe;
    Wyx:
    $E0 = "\164\145\x73\x74\x53\x53\x4f\114\x6f\147\151\156";
    goto vPe;
    Pg8:
    $E0 = SAMLSPUtilities::mo_saml_is_array($_REQUEST["\x72\145\144\x69\x72\145\x63\164\x5f\164\x6f"]);
    vPe:
    if (!empty($E0)) {
        goto Xw0;
    }
    $E0 = $hK;
    Xw0:
    if (empty($E0)) {
        goto ZwT;
    }
    $AD = parse_url($E0, PHP_URL_PATH);
    $BL = parse_url($E0, PHP_URL_QUERY);
    ZwT:
    $AD = empty($AD) ? "\x2f" : $AD;
    if (!empty($BL)) {
        goto wZM;
    }
    $E0 = $AD;
    goto VyG;
    wZM:
    $E0 = $AD . "\77" . $BL;
    VyG:
    if (!($E0 !== "\x74\x65\163\164\126\x61\x6c\x69\144\x61\164\145" && $E0 !== "\164\x65\163\164\x4e\145\167\x43\x65\162\164\151\x66\x69\143\141\x74\x65" && $E0 !== "\x74\145\163\x74\x53\123\x4f\x4c\x6f\x67\x69\156")) {
        goto BpL;
    }
    $E0 = apply_filters("\x6d\157\137\x73\x61\x6d\x6c\137\x70\x72\x65\137\154\x6f\x67\x69\x6e\137\x73\163\x6f\x5f\x72\x65\154\x61\171\137\x73\x74\141\x74\145", $E0);
    BpL:
    $hk = $fk["\163\x73\157\137\x75\x72\x6c"];
    $hk = apply_filters("\155\157\137\x73\x61\x6d\x6c\x5f\x73\x73\157\x5f\x75\162\154", $hk, $fk["\x69\x64\160\x5f\x6e\x61\155\x65"]);
    $ML = $fk["\162\145\161\x75\145\x73\164\137\163\151\x67\x6e\145\144"];
    $AE = $fk["\x73\x73\157\137\x62\x69\x6e\x64\x69\156\147\x5f\x74\171\x70\145"];
    $Ri = EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\x5f\x73\141\155\154\x5f\146\x6f\162\x63\145\137\x61\165\164\x68\145\156\x74\x69\x63\x61\164\x69\x6f\x6e", false, $CP);
    $h8 = $hK . "\57";
    $Uy = EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\137\163\x61\155\154\x5f\x73\x70\x5f\x65\156\164\151\x74\171\137\151\144", false, $CP);
    $nq = $fk["\x6e\141\x6d\x65\151\x64\x5f\146\x6f\162\x6d\x61\164"];
    if (!empty($nq)) {
        goto Edk;
    }
    $nq = "\61\x2e\61\x3a\156\141\x6d\145\x69\x64\55\146\157\162\155\141\164\72\x75\x6e\x73\x70\145\x63\151\x66\151\145\x64";
    Edk:
    if (!empty($Uy)) {
        goto Ucl;
    }
    $Uy = $hK . "\x2f\167\160\x2d\143\x6f\x6e\x74\x65\156\164\57\x70\154\x75\x67\151\x6e\163\57\x6d\x69\x6e\151\157\x72\x61\156\147\145\x2d\163\141\x6d\154\x2d\62\x30\x2d\x73\151\156\x67\154\x65\55\x73\x69\x67\156\x2d\x6f\x6e\x2f";
    Ucl:
    $Uy = !empty($fk["\163\141\155\x6c\137\x73\x70\x5f\145\x6e\x74\x69\164\171\137\151\x64"]) ? $fk["\163\x61\155\x6c\x5f\163\x70\137\x65\x6e\164\151\x74\x79\x5f\151\144"] : $Uy;
    $Do = !empty($_POST["\x75\156\141\x6d\x65\x5f\x65\155\x61\151\x6c"]) ? $_POST["\x75\156\x61\x6d\145\x5f\145\x6d\x61\151\154"] : false;
    if (!$Do) {
        goto sNG;
    }
    if (is_email($Do)) {
        goto Ucs;
    }
    $user = get_user_by("\154\157\x67\x69\156", $Do);
    goto qK4;
    Ucs:
    $user = get_user_by("\145\x6d\141\151\154", $Do);
    qK4:
    if ($user) {
        goto DzG;
    }
    $Do = false;
    update_site_option("\x6d\x6f\x5f\163\141\155\x6c\x5f\x73\x68\x6f\x72\x74\x63\x6f\144\x65\x5f\x6d\145\163\x73\141\x67\145", "\125\163\x65\x72\40\144\x6f\145\163\40\x6e\157\x74\40\x45\170\151\x73\164\x73");
    return;
    DzG:
    $Do = $user->user_email;
    sNG:
    $r2 = SAMLSPUtilities::createAuthnRequest($h8, $Uy, $hk, $fk, $Ri, $AE, $nq);
    $tx = SAMLSPUtilities::mo_saml_sanitize_associative_array($_REQUEST);
    if (empty($AE) || $AE == "\x48\x74\164\160\122\145\x64\151\x72\145\x63\164") {
        goto i2E;
    }
    if (!($ML == "\165\156\143\x68\145\x63\x6b\145\144")) {
        goto N8c;
    }
    $IZ = base64_encode($r2);
    SAMLSPUtilities::postSAMLRequest($hk, $IZ, $E0, $tx, $Do);
    exit;
    N8c:
    if ($_REQUEST["\x6f\160\164\151\157\156"] == "\x74\145\x73\164\x69\x64\x70\x63\x6f\156\x66\151\x67" && $_REQUEST["\x6e\x65\x77\x63\145\x72\x74"] == true) {
        goto KR1;
    }
    $IZ = SAMLSPUtilities::signXML($r2, $fk, "\x4e\141\155\145\x49\x44\120\x6f\154\x69\143\x79");
    goto MOG;
    KR1:
    $IZ = SAMLSPUtilities::signXML($r2, $fk, "\116\x61\155\x65\x49\104\x50\157\154\151\143\171", true);
    MOG:
    SAMLSPUtilities::postSAMLRequest($hk, $IZ, $E0, $tx, $Do);
    goto sG9;
    i2E:
    $jG = $hk;
    if (strpos($hk, "\77") !== false) {
        goto uUp;
    }
    $jG .= "\x3f";
    goto Oxe;
    uUp:
    $jG .= "\46";
    Oxe:
    if (!($ML == "\x75\156\x63\150\145\x63\x6b\x65\x64")) {
        goto DoJ;
    }
    $jG .= "\x53\x41\x4d\114\122\145\x71\x75\145\x73\x74\x3d" . $r2 . SAMLSPUtilities::mo_saml_append_params_redirect_binding($tx) . "\x26\x52\x65\154\x61\171\x53\x74\141\164\x65\75" . urlencode($E0);
    if (!$Do) {
        goto JBp;
    }
    $jG .= "\46\105\x6d\141\151\154\75" . urlencode($Do);
    JBp:
    header("\143\x61\x63\150\x65\x2d\x63\157\156\164\162\157\x6c\x3a\40\x6d\x61\170\x2d\x61\x67\145\75\x30\54\40\160\162\151\166\141\x74\145\x2c\x20\156\x6f\55\x73\164\x6f\162\x65\x2c\x20\x6e\x6f\x2d\x63\x61\x63\x68\145\54\40\155\165\163\x74\x2d\162\x65\166\x61\x6c\151\144\141\164\x65");
    header("\x4c\x6f\x63\x61\x74\151\157\x6e\x3a\x20" . $jG);
    exit;
    DoJ:
    $r2 = "\x53\x41\115\114\122\145\x71\165\145\163\164\75" . $r2 . "\46\x52\x65\x6c\x61\x79\x53\x74\141\x74\145\x3d" . urlencode($E0) . "\x26\123\x69\x67\101\x6c\x67\x3d" . urlencode(XMLSecurityKey::RSA_SHA256);
    $EJ = array("\164\x79\160\145" => "\160\162\151\166\x61\x74\145");
    $R2 = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, $EJ);
    if ($_REQUEST["\157\x70\x74\x69\157\156"] == "\x74\145\163\x74\151\x64\160\x63\157\156\146\x69\x67" && $_REQUEST["\156\145\167\143\x65\x72\x74"] == true) {
        goto ty0;
    }
    $LB = mo_saml_get_sp_private_key_for_idp($fk);
    goto TrQ;
    ty0:
    $LB = file_get_contents(plugin_dir_path(__FILE__) . "\162\x65\163\x6f\165\162\x63\145\163" . DIRECTORY_SEPARATOR . mo_options_enum_default_sp_certificate::SP_PRIVATE_KEY_FILE_NAME);
    TrQ:
    $R2->loadKey($LB, false);
    $Nk = new XMLSecurityDSig();
    $lo = $R2->signData($r2);
    $lo = base64_encode($lo);
    $jG .= $r2 . "\x26\123\151\x67\156\141\x74\x75\162\x65\x3d" . urlencode($lo) . SAMLSPUtilities::mo_saml_append_params_redirect_binding($tx);
    if (!$Do) {
        goto stc;
    }
    $jG .= "\x26\x45\x6d\141\x69\x6c\x3d" . urlencode($Do);
    stc:
    header("\x63\x61\143\150\145\55\x63\x6f\156\164\162\157\x6c\x3a\40\x6d\x61\170\x2d\x61\x67\x65\x3d\60\54\x20\x70\x72\x69\x76\x61\x74\x65\x2c\40\156\x6f\55\x73\x74\157\162\x65\54\40\x6e\157\x2d\x63\141\143\x68\x65\54\40\155\165\163\x74\55\162\145\166\x61\x6c\x69\144\141\164\145");
    header("\x4c\157\143\141\x74\x69\x6f\156\x3a\40" . $jG);
    exit;
    sG9:
    U02:
    VW0:
    if (empty($_REQUEST["\x53\x41\115\x4c\x52\x65\x73\160\x6f\x6e\x73\145"])) {
        goto xCL;
    }
    SAMLSPUtilities::mo_saml_check_is_extension_installed();
    $hK = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\163\141\x6d\154\137\x73\160\x5f\x62\141\x73\145\137\x75\162\154", false, $CP);
    if (!empty($hK)) {
        goto z1O;
    }
    $hK = home_url();
    z1O:
    $rg = htmlspecialchars($_REQUEST["\x53\101\x4d\x4c\122\x65\x73\160\157\x6e\163\x65"]);
    $rg = SAMLSPUtilities::validate_compressed_xml($rg, "\123\x41\x4d\114\122\145\x73\160\x6f\x6e\x73\145");
    $nI = SAMLSPUtilities::mo_saml_safe_load_xml($rg, Mo_Saml_Error_Codes::$error_codes["\127\x50\x53\101\115\x4c\105\122\122\60\61\x37"]);
    $i2 = $nI->firstChild;
    $hT = $nI->documentElement;
    $zk = new DOMXpath($nI);
    $zk->registerNamespace("\x73\141\x6d\154\x70", "\x75\x72\156\72\x6f\141\x73\151\x73\x3a\x6e\141\155\x65\163\72\164\143\x3a\123\x41\115\x4c\x3a\x32\56\60\72\x70\x72\157\x74\157\143\x6f\154");
    $zk->registerNamespace("\x73\141\155\154", "\165\x72\x6e\72\x6f\141\163\x69\x73\72\x6e\141\x6d\x65\x73\x3a\164\x63\72\x53\101\115\x4c\x3a\x32\56\x30\72\x61\x73\163\145\162\x74\151\157\156");
    if ($i2->localName == "\114\x6f\147\x6f\x75\164\x52\145\163\160\x6f\x6e\x73\x65") {
        goto N3X;
    }
    $d6 = $zk->query("\x2f\x73\141\155\154\160\x3a\122\x65\x73\x70\x6f\x6e\x73\x65\x2f\163\141\x6d\x6c\160\72\x53\x74\x61\x74\165\163\57\x73\141\155\154\x70\x3a\123\x74\x61\164\x75\163\x43\x6f\144\x65", $hT);
    $Cf = !empty($d6) ? $d6->item(0)->getAttribute("\126\x61\x6c\165\145") : '';
    $h_ = explode("\x3a", $Cf);
    if (empty($h_[7])) {
        goto NFZ;
    }
    $d6 = $h_[7];
    NFZ:
    $u1 = $zk->query("\x2f\x73\141\155\154\x70\x3a\x52\x65\x73\160\x6f\x6e\163\145\x2f\163\141\x6d\x6c\160\72\123\x74\x61\164\165\x73\x2f\x73\141\155\x6c\x70\x3a\x53\164\x61\164\x75\x73\115\145\163\163\141\147\x65", $hT);
    $vl = !empty($u1) ? $u1->item(0) : '';
    if (empty($vl)) {
        goto ruu;
    }
    $vl = $vl->nodeValue;
    ruu:
    $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\x6d\x6c\137\x69\x64\145\156\164\x69\x74\171\x5f\160\162\157\x76\x69\144\145\x72\x73", true, $CP);
    if (!empty($_REQUEST["\122\x65\x6c\141\171\x53\164\141\x74\x65"]) && $_REQUEST["\x52\145\154\x61\x79\123\x74\x61\x74\145"] != "\57") {
        goto v3T;
    }
    $Yj = saml_get_current_page_url();
    goto Ll1;
    v3T:
    $Yj = $_REQUEST["\x52\145\154\x61\171\x53\x74\x61\164\x65"];
    Ll1:
    $fS = array("\x73\141\155\154\x5f\x72\145\x73\x70\x6f\156\163\145" => base64_encode($rg));
    $rg = new SAML2_Response($i2, get_option("\x6d\157\137\163\x61\x6d\154\137\x63\x75\x72\162\145\156\164\137\143\x65\x72\164\137\160\162\151\x76\x61\164\145\137\x6b\145\171"));
    SAMLSPUtilities::mo_saml_check_saml_response_for_replay_attack($rg);
    if (!(SAMLSPUtilities::mo_saml_is_user_logged_in() && "\164\x65\x73\x74\x56\x61\154\x69\144\x61\x74\x65" != $Yj && "\164\145\163\164\123\123\117\x4c\157\x67\151\156" != $Yj)) {
        goto xxo;
    }
    return;
    xxo:
    $dF = $rg->getIssuer();
    $XE = null;
    if (empty($rK)) {
        goto hRB;
    }
    foreach ($rK as $R2 => $EB) {
        if (!($EB["\x69\144\x70\x5f\145\x6e\x74\151\164\x79\x5f\151\144"] == $dF)) {
            goto Aq2;
        }
        $XE = $rK[$R2];
        goto O_B;
        Aq2:
        Kr6:
    }
    O_B:
    hRB:
    if (!($XE == null)) {
        goto AAQ;
    }
    $XE = apply_filters("\x6d\x6f\137\x73\x61\x6d\154\x5f\146\x69\154\x74\145\162\x5f\x69\144\145\156\x74\x69\x74\171\137\x70\162\x6f\166\151\144\x65\x72\x73", $rK, $dF);
    AAQ:
    if (SAMLSPUtilities::mo_saml_validate_idp($XE, $rK)) {
        goto dW9;
    }
    if ($Yj == "\x74\x65\x73\164\x56\x61\154\x69\144\x61\x74\145" or $Yj == "\164\145\163\x74\x4e\145\x77\103\x65\162\x74\151\x66\x69\143\x61\164\145") {
        goto u1r;
    }
    Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\127\x50\x53\101\x4d\x4c\105\x52\x52\60\61\60"]);
    goto QEh;
    u1r:
    $hU = mo_options_error_constants::Error_issuer_not_verified;
    $O4 = mo_options_error_constants::Cause_issuer_not_verified;
    $bm = "\74\160\x3e\74\163\x74\162\157\156\x67\x3e\x45\156\164\x69\x74\171\x20\111\x44\x20\x66\x6f\165\156\x64\x20\151\156\40\123\101\115\x4c\40\122\145\x73\160\x6f\156\163\x65\72\x20\x3c\x2f\x73\x74\x72\x6f\156\x67\76\x3c\146\157\156\x74\x20\146\141\143\145\75\42\x43\157\x75\x72\x69\145\x72\40\x4e\145\167\x22\73\146\157\x6e\x74\55\x73\x69\x7a\145\x3a\x31\x30\x70\164\x3e\74\x62\x72\76\74\x62\162\76" . esc_html($dF) . "\74\57\x70\x3e\x3c\x2f\x66\x6f\156\x74\76";
    Mo_Saml_Error_Message::mo_saml_display_test_config_error(Mo_Saml_Error_Codes::$error_codes["\x57\120\123\101\115\x4c\x45\x52\122\60\x31\x30"], $bm);
    mo_saml_download_logs($hU, $O4);
    exit;
    QEh:
    dW9:
    if (!($d6 != "\x53\x75\143\143\x65\163\x73")) {
        goto OuM;
    }
    show_status_error($d6, $Yj, $vl, $XE);
    OuM:
    $BB = '';
    if ($Yj == "\x74\x65\x73\164\116\x65\167\x43\145\162\164\x69\x66\x69\x63\x61\164\x65") {
        goto vTy;
    }
    $Jm = mo_saml_get_sp_private_key_for_idp($XE);
    goto lsX;
    vTy:
    $Jm = file_get_contents(plugin_dir_path(__FILE__) . "\x72\145\x73\x6f\x75\x72\x63\145\163" . DIRECTORY_SEPARATOR . mo_options_enum_default_sp_certificate::SP_PRIVATE_KEY_FILE_NAME);
    lsX:
    $rg->parseAssertions($i2, $Jm);
    $sa = $rg->getSignatureData();
    $Ps = current($rg->getAssertions())->getSignatureData();
    if (is_null($XE)) {
        goto aD0;
    }
    $BB = $XE["\151\x64\x70\x5f\x6e\141\x6d\145"];
    $rK[$BB] = SAMLSPUtilities::mo_saml_array_merge($rK[$BB], $fS);
    $rK = array_filter($rK, "\146\151\154\x74\x65\162\137\145\155\x70\x74\171\x5f\166\141\154\165\145\x73");
    $s6 = new EnvironmentDao($CP);
    $s6->mo_save_environment_settings("\x73\x61\155\154\137\151\x64\145\x6e\x74\151\x74\x79\137\x70\162\x6f\166\151\144\x65\x72\163", $rK, false);
    aD0:
    SAMLSPUtilities::mo_saml_disable_extra_idps($CP);
    $cA = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\x6f\x5f\x73\141\155\x6c\x5f\x65\156\141\142\x6c\x65\x64\137\151\144\x70\163", true, $CP);
    if (array_key_exists($BB, $cA)) {
        goto Q2x;
    }
    if (!($Yj != "\164\x65\x73\x74\x56\x61\154\151\144\x61\x74\x65" && $Yj != "\164\145\163\164\116\x65\x77\x43\145\162\x74\151\146\x69\x63\x61\x74\x65" && $Yj != "\164\x65\x73\164\x53\123\x4f\114\157\x67\x69\x6e")) {
        goto mhs;
    }
    throw new Mo_SAML_IDP_Status_Inactive_Exception("\x49\104\120\x20\116\x6f\x74\40\105\156\141\x62\154\145\x64\x2e");
    mhs:
    Q2x:
    if (!(empty($Ps) && empty($sa))) {
        goto nX6;
    }
    if ($Yj == "\x74\x65\x73\164\126\141\154\x69\x64\x61\164\145" or $Yj == "\164\x65\163\x74\116\145\x77\x43\x65\162\x74\151\146\x69\x63\x61\x74\x65") {
        goto x2i;
    }
    Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\127\120\x53\101\x4d\x4c\x45\x52\122\60\60\63"]);
    goto YR0;
    x2i:
    Mo_Saml_Error_Message::mo_saml_display_test_config_error(Mo_Saml_Error_Codes::$error_codes["\x57\x50\x53\101\x4d\114\x45\x52\122\60\60\x33"]);
    YR0:
    nX6:
    $ZT = maybe_unserialize($XE["\x78\65\x30\71\x5f\143\x65\x72\164\x69\x66\x69\x63\x61\x74\x65"]);
    $h8 = $hK . "\57";
    if (is_array($ZT)) {
        goto jEu;
    }
    $hS = XMLSecurityKey::getRawThumbprint($ZT);
    $hS = mo_saml_convert_to_windows_iconv($hS, $XE);
    $hS = preg_replace("\x2f\134\163\x2b\x2f", '', $hS);
    if (empty($sa)) {
        goto lsJ;
    }
    $al = SAMLSPUtilities::processResponse($h8, $hS, $sa, $rg, $ZT, $Yj);
    lsJ:
    if (empty($Ps)) {
        goto RCY;
    }
    $al = SAMLSPUtilities::processResponse($h8, $hS, $Ps, $rg, $ZT, $Yj);
    RCY:
    goto tWO;
    jEu:
    foreach ($ZT as $cP => $Fh) {
        $hS = XMLSecurityKey::getRawThumbprint(SAMLSPUtilities::sanitize_certificate($Fh));
        $hS = mo_saml_convert_to_windows_iconv($hS, $XE);
        $hS = preg_replace("\57\134\163\x2b\57", '', $hS);
        if (empty($sa)) {
            goto otz;
        }
        $al = SAMLSPUtilities::processResponse($h8, $hS, $sa, $rg, $Fh, $Yj);
        otz:
        if (empty($Ps)) {
            goto Mm2;
        }
        $al = SAMLSPUtilities::processResponse($h8, $hS, $Ps, $rg, $Fh, $Yj);
        Mm2:
        if (!$al) {
            goto m9l;
        }
        goto fKr;
        m9l:
        o12:
    }
    fKr:
    tWO:
    if (!(empty($Ps) && empty($sa))) {
        goto IJW;
    }
    echo "\116\x6f\x20\x73\151\147\156\141\x74\x75\162\x65\40\146\157\x75\156\x64\40\151\156\x20\x53\101\x4d\114\x20\x52\145\163\x70\157\156\163\x65\x20\x6f\162\40\x41\x73\x73\x65\162\164\151\x6f\156\56\x20\120\x6c\145\x61\163\145\40\x73\x69\x67\x6e\x20\141\x74\x20\x6c\x65\141\x73\164\40\157\156\x65\x20\157\x66\40\x74\x68\x65\x6d\56";
    exit;
    IJW:
    if ($sa) {
        goto PcC;
    }
    if ($Ps) {
        goto AII;
    }
    goto Yk_;
    PcC:
    if (!(count($sa["\x43\x65\x72\164\x69\x66\x69\x63\x61\164\145\x73"]) > 0)) {
        goto Blt;
    }
    $cS = $sa["\103\145\x72\x74\151\x66\x69\x63\141\x74\145\x73"][0];
    Blt:
    goto Yk_;
    AII:
    if (!(count($Ps["\103\x65\x72\164\x69\146\x69\x63\141\x74\145\x73"]) > 0)) {
        goto YeX;
    }
    $cS = $Ps["\x43\145\162\x74\x69\146\x69\x63\141\164\x65\163"][0];
    YeX:
    Yk_:
    if ($al) {
        goto qc2;
    }
    if ($Yj == "\164\x65\x73\164\126\141\154\x69\144\x61\x74\145" or $Yj == "\164\145\x73\164\x4e\145\x77\103\145\x72\x74\151\x66\151\143\x61\164\145") {
        goto O24;
    }
    Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\x57\120\123\x41\x4d\114\105\122\122\x30\60\x34"]);
    goto I3G;
    O24:
    $WX = "\x2d\55\x2d\x2d\55\x42\105\x47\111\116\x20\103\x45\x52\x54\x49\x46\x49\x43\101\124\x45\x2d\x2d\x2d\x2d\x2d\74\x62\x72\76" . chunk_split($cS, 64) . "\74\x62\x72\x3e\55\x2d\55\x2d\55\105\116\104\x20\103\105\122\124\111\x46\111\103\101\124\x45\55\x2d\x2d\x2d\55";
    $bm = "\x3c\x70\76\74\163\x74\162\157\x6e\x67\x3e\x43\145\162\164\151\x66\151\x63\141\x74\145\40\146\x6f\x75\x6e\x64\x20\151\x6e\x20\123\101\x4d\114\x20\x52\145\163\x70\157\x6e\x73\x65\72\x20\x3c\x2f\x73\x74\162\157\156\147\76\74\x66\x6f\x6e\x74\40\146\141\x63\145\x3d\x22\103\x6f\165\162\x69\x65\162\40\116\x65\167\x22\73\146\157\x6e\x74\x2d\x73\151\x7a\x65\x3a\61\x30\160\x74\76\74\x62\x72\76\74\142\162\x3e" . $WX . "\74\57\160\76\74\57\146\x6f\x6e\164\x3e";
    Mo_Saml_Error_Message::mo_saml_display_test_config_error(Mo_Saml_Error_Codes::$error_codes["\127\x50\x53\101\x4d\x4c\x45\x52\122\60\x30\64"], $bm);
    I3G:
    qc2:
    $Uy = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\x5f\163\141\x6d\x6c\137\163\x70\137\145\156\x74\151\164\x79\137\x69\x64", false, $CP);
    if (!empty($Uy)) {
        goto o84;
    }
    $Uy = $hK . "\57\x77\160\55\143\x6f\156\x74\x65\156\164\x2f\x70\154\x75\x67\x69\156\163\57\x6d\x69\156\151\157\162\141\x6e\x67\x65\x2d\163\141\155\154\x2d\62\x30\55\x73\x69\156\x67\154\x65\x2d\163\x69\147\x6e\55\157\x6e\x2f";
    o84:
    $Uy = !empty($XE["\163\x61\155\154\137\x73\x70\137\145\x6e\164\x69\164\171\x5f\151\144"]) ? $XE["\163\141\x6d\x6c\x5f\x73\160\x5f\x65\156\164\x69\164\x79\137\x69\x64"] : $Uy;
    $g5 = current($rg->getAssertions())->getIssuer();
    SAMLSPUtilities::validateIssuerAndAudience($rg, $Uy, $g5, $Yj, $BB);
    $i8 = sanitize_text_field(current(current($rg->getAssertions())->getNameId()));
    if (!empty($i8)) {
        goto vwd;
    }
    if ($Yj === "\x74\145\x73\164\x56\x61\154\151\x64\141\x74\x65" or $Yj === "\x74\x65\x73\x74\x4e\145\167\x43\145\x72\164\x69\146\x69\143\x61\164\145") {
        goto Kn2;
    }
    Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\x57\120\x53\x41\x4d\x4c\105\122\x52\x30\x30\x32"]);
    goto gCR;
    Kn2:
    Mo_Saml_Error_Message::mo_saml_display_test_config_error(Mo_Saml_Error_Codes::$error_codes["\127\120\x53\101\115\114\105\x52\122\x30\x30\x32"]);
    gCR:
    vwd:
    $r0 = current($rg->getAssertions())->getAttributes();
    $r0["\116\x61\x6d\x65\x49\x44"] = array("\x30" => $i8);
    $r0["\x73\x61\x6e\151\164\x69\x7a\x65\137\146\165\x72\x74\150\145\x72"] = true;
    $r0 = apply_filters("\x6d\157\137\163\141\155\154\x5f\163\141\156\151\164\x69\x7a\x65\137\141\x74\x74\162\x69\142\165\164\x65\163", $r0);
    $X9 = current($rg->getAssertions())->getSessionIndex();
    mo_saml_checkMapping($XE, $r0, wp_specialchars_decode($Yj), $X9);
    goto XLZ;
    N3X:
    $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\x61\155\x6c\x5f\151\x64\145\x6e\x74\151\164\x79\x5f\x70\162\157\166\x69\144\145\x72\x73", true, $CP);
    $rg = new SAML2_Response($i2, get_option("\155\157\x5f\163\x61\155\x6c\x5f\143\165\x72\x72\x65\x6e\x74\137\x63\x65\x72\x74\x5f\x70\162\151\x76\141\164\x65\x5f\x6b\x65\171"));
    $dF = $rg->getIssuer();
    $XE = null;
    if (empty($rK)) {
        goto u4d;
    }
    foreach ($rK as $R2 => $EB) {
        if (!($EB["\x69\x64\x70\137\x65\156\164\151\x74\x79\137\151\144"] == $dF)) {
            goto aRq;
        }
        $XE = $rK[$R2];
        goto Fj8;
        aRq:
        K0D:
    }
    Fj8:
    u4d:
    if (!empty($XE)) {
        goto enb;
    }
    $Rn = $hK;
    goto HVL;
    enb:
    $BB = $XE["\x69\x64\160\x5f\x6e\141\x6d\145"];
    $HD = EnvironmentHelper::getOptionForSelectedEnvironment(mo_options_enum_sso_login::Relay_states, true, $CP);
    $Ih = !empty($HD["\154\x6f\x67\x6f\165\x74\x5f\x72\145\x6c\141\x79\137\163\x74\x61\x74\145"]) ? $HD["\x6c\157\147\x6f\x75\x74\137\x72\145\154\x61\x79\x5f\x73\x74\141\164\x65"] : array();
    if (!empty($Ih["\104\105\x46\101\125\x4c\124"]) || !empty($Ih[$BB])) {
        goto iqU;
    }
    if (empty($_REQUEST["\122\x65\154\x61\x79\123\x74\141\x74\145"])) {
        goto PQx;
    }
    $Rn = $_REQUEST["\x52\145\x6c\141\171\x53\x74\141\x74\145"];
    PQx:
    if (!empty($Rn)) {
        goto cnM;
    }
    $Rn = $hK;
    cnM:
    goto Y5g;
    iqU:
    $Rn = !empty($BB) && !empty($Ih[$BB]) ? $Ih[$BB] : $Ih["\104\105\x46\x41\x55\x4c\124"];
    Y5g:
    HVL:
    if (!SAMLSPUtilities::mo_saml_is_user_logged_in()) {
        goto THN;
    }
    wp_destroy_current_session();
    wp_clear_auth_cookie();
    wp_set_current_user(0);
    THN:
    $Rn = apply_filters("\x6d\157\x5f\x73\141\x6d\x6c\x5f\x70\x6f\x73\164\x5f\x6c\x6f\147\157\x75\164\137\163\x6c\157\137\162\145\x6c\x61\171\x5f\163\164\141\164\x65", $Rn);
    header("\x4c\157\x63\141\x74\151\157\x6e\72\40" . $Rn);
    exit;
    XLZ:
    xCL:
    if (empty($_REQUEST["\123\x41\115\114\x52\x65\x71\165\x65\163\164"])) {
        goto xil;
    }
    SAMLSPUtilities::mo_saml_check_is_extension_installed();
    $r2 = $_REQUEST["\x53\101\115\x4c\x52\x65\161\x75\x65\163\x74"];
    $Yj = "\x2f";
    if (empty($_REQUEST["\122\145\x6c\141\171\123\x74\x61\x74\145"])) {
        goto TTi;
    }
    $Yj = $_REQUEST["\122\x65\x6c\x61\x79\123\164\141\x74\145"];
    TTi:
    $Yj = apply_filters("\155\157\137\x73\141\x6d\x6c\x5f\160\x6f\163\x74\x5f\x6c\x6f\x67\157\165\x74\x5f\x73\x6c\157\x5f\162\x65\x71\x75\x65\x73\x74\x5f\x72\145\x6c\141\x79\x5f\163\x74\141\164\x65", $Yj);
    $r2 = htmlspecialchars($_REQUEST["\x53\101\x4d\114\122\x65\161\x75\x65\163\164"]);
    $r2 = SAMLSPUtilities::validate_compressed_xml($r2, "\x53\x41\x4d\x4c\122\145\x71\165\145\x73\x74");
    $nI = SAMLSPUtilities::mo_saml_safe_load_xml($r2, Mo_Saml_Error_Codes::$error_codes["\127\x50\x53\101\115\114\x45\122\122\60\62\x38"]);
    $R1 = $nI->firstChild;
    if (!($R1->localName == "\114\157\x67\x6f\x75\164\x52\x65\x71\x75\x65\x73\164")) {
        goto Mn5;
    }
    $uu = new SAML2_LogoutRequest($R1);
    if (!(!session_id() || session_id() == '' || empty($_SESSION))) {
        goto Wu_;
    }
    session_start();
    Wu_:
    $_SESSION["\x6d\157\x5f\163\141\x6d\154\x5f\x6c\x6f\147\x6f\x75\x74\x5f\162\145\161\165\x65\x73\x74"] = $r2;
    $_SESSION["\155\x6f\x5f\x73\x61\x6d\154\137\154\157\x67\157\165\164\x5f\x72\145\154\141\x79\137\163\x74\x61\164\x65"] = $Yj;
    wp_logout();
    Mn5:
    xil:
}
function getIdpNameFromEntityId($rK, $Sd)
{
    if (!(!empty($rK) and is_array($rK))) {
        goto FLG;
    }
    foreach ($rK as $fk) {
        if (!($fk["\151\144\160\137\145\x6e\164\151\x74\171\x5f\x69\144"] == $Sd)) {
            goto ArC;
        }
        return $fk["\151\x64\x70\137\156\x61\155\x65"];
        ArC:
        zOo:
    }
    VgX:
    FLG:
    return false;
}
function mo_saml_checkMapping($XE, $r0, $Yj, $X9)
{
    $CP = EnvironmentHelper::getCurrentEnvironment();
    $BB = $XE["\x69\144\160\x5f\156\141\155\145"];
    $un = Mo_SAML_Config_Utility::mo_saml_check_if_idp_configurations_configured($BB, "\141\x74\x74\x72\151\x62\x75\x74\x65\x5f\x6d\x61\x70\160\x69\156\147", $CP) ? $BB : "\104\x45\x46\101\125\x4c\x54";
    $ey = Mo_SAML_Config_Utility::mo_saml_get_attr_configurations($un, $CP);
    $pj = !empty($ey["\165\x73\145\x72\156\x61\x6d\x65"]) ? $ey["\x75\x73\x65\x72\x6e\x61\155\x65"] : "\116\141\x6d\x65\x49\104";
    $T2 = !empty($ey["\145\x6d\x61\151\154"]) ? $ey["\x65\155\141\151\x6c"] : "\x4e\141\155\145\x49\x44";
    $mJ = !empty($ey["\146\151\x72\163\164\137\156\x61\x6d\145"]) ? $ey["\x66\151\x72\163\x74\x5f\156\x61\x6d\145"] : '';
    $S2 = !empty($ey["\154\x61\163\x74\137\156\141\x6d\x65"]) ? $ey["\x6c\141\163\164\x5f\x6e\x61\155\145"] : '';
    $zS = !empty($ey["\156\151\x63\x6b\x5f\x6e\141\155\x65"]) ? $ey["\156\x69\x63\153\x5f\156\141\x6d\x65"] : '';
    $kW = !empty($ey["\x64\x69\163\x70\x6c\141\171\x5f\156\x61\155\x65"]) ? $ey["\144\151\x73\160\x6c\x61\171\137\x6e\141\155\x65"] : '';
    $Io = !empty($r0[$pj][0]) ? $r0[$pj][0] : '';
    $RY = !empty($r0[$T2][0]) ? $r0[$T2][0] : '';
    $s9 = !empty($r0[$mJ][0]) ? $r0[$mJ][0] : '';
    $AA = !empty($r0[$S2][0]) ? $r0[$S2][0] : '';
    $K0 = !empty($r0[$zS][0]) ? $r0[$zS][0] : '';
    if (!empty($Io)) {
        goto PJ2;
    }
    if ($Yj === "\164\x65\x73\x74\x56\x61\154\151\144\x61\x74\145" or $Yj === "\x74\x65\x73\x74\116\145\x77\x43\x65\162\164\151\146\x69\x63\141\164\145") {
        goto AGC;
    }
    Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\127\120\123\x41\x4d\x4c\105\x52\122\60\63\x37"]);
    goto hYM;
    AGC:
    Mo_Saml_Error_Message::mo_saml_display_test_config_error(Mo_Saml_Error_Codes::$error_codes["\127\x50\x53\101\115\114\x45\122\122\x30\63\67"]);
    hYM:
    PJ2:
    if (!empty($RY)) {
        goto dUm;
    }
    if ($Yj === "\164\x65\163\x74\x56\x61\x6c\151\x64\x61\164\145" or $Yj === "\x74\145\163\164\x4e\x65\x77\103\145\x72\164\x69\146\151\143\x61\x74\x65") {
        goto zvf;
    }
    Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\127\x50\x53\101\x4d\x4c\105\122\122\x30\63\x37"]);
    goto u2B;
    zvf:
    Mo_Saml_Error_Message::mo_saml_display_test_config_error(Mo_Saml_Error_Codes::$error_codes["\x57\x50\123\x41\115\114\105\122\122\x30\63\67"]);
    u2B:
    dUm:
    if ($Yj == "\x74\145\163\164\x53\x53\117\114\157\147\151\156" || $Yj == "\x74\145\163\x74\126\141\x6c\151\x64\x61\164\145" || $Yj == "\164\145\x73\164\116\145\167\103\x65\162\164\x69\x66\x69\x63\141\x74\145") {
        goto kNM;
    }
    mo_saml_login_user($Io, $RY, $s9, $AA, $K0, $kW, $Yj, $XE, $X9, $r0);
    goto Sb4;
    kNM:
    if (Mo_License_Service::is_customer_license_valid()) {
        goto vMv;
    }
    throw new Mo_SAML_Invalid_License_Exception("\111\x6e\x76\141\154\151\x64\x20\x4c\151\x63\145\156\163\x65");
    vMv:
    $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\x6d\x6c\x5f\151\x64\x65\156\x74\151\164\x79\137\160\162\x6f\166\x69\144\145\x72\163", true, $CP);
    $fg = $XE["\x69\144\x70\x5f\156\141\x6d\145"];
    $fS = array("\164\145\163\164\x5f\x73\x74\141\164\165\x73" => "\124\x65\163\164\40\x73\165\x63\x63\x65\x73\163\x66\x75\154");
    if (empty($fg)) {
        goto xrA;
    }
    $rK[$fg] = SAMLSPUtilities::mo_saml_array_merge($rK[$fg], $fS);
    $rK = array_filter($rK, "\146\151\154\x74\x65\x72\x5f\x65\x6d\160\x74\171\x5f\x76\x61\x6c\165\x65\x73");
    $s6 = new EnvironmentDao($CP);
    $s6->mo_save_environment_settings("\163\141\155\x6c\137\151\144\145\x6e\164\151\x74\x79\137\160\x72\157\x76\x69\x64\x65\x72\163", $rK, false);
    xrA:
    mo_saml_show_test_result($RY, $r0, $XE, $Yj);
    Sb4:
}
function mo_saml_show_test_result($TS, $r0, $XE, $Yj)
{
    ob_end_clean();
    echo "\74\x64\151\x76\40\x73\164\x79\x6c\x65\75\42\146\x6f\156\x74\55\146\141\155\151\x6c\171\x3a\103\141\154\x69\142\162\151\x3b\x70\x61\144\x64\x69\x6e\147\x3a\60\40\63\45\73\x22\x3e";
    $fg = $XE["\x69\x64\x70\137\x6e\141\155\x65"];
    $CP = EnvironmentHelper::getCurrentEnvironment();
    if (!empty($TS)) {
        goto mt4;
    }
    echo "\74\144\151\166\40\163\x74\171\154\x65\75\42\x63\x6f\154\x6f\162\x3a\40\x23\141\x39\x34\64\x34\62\73\x62\141\x63\x6b\x67\162\157\165\x6e\x64\x2d\143\157\x6c\x6f\162\x3a\40\43\146\62\x64\x65\144\x65\x3b\160\141\x64\x64\151\156\x67\x3a\40\61\65\160\170\x3b\155\141\x72\x67\151\156\55\142\x6f\164\x74\157\x6d\x3a\40\x32\60\x70\170\x3b\164\x65\x78\x74\x2d\x61\x6c\x69\x67\x6e\x3a\x63\x65\156\164\x65\162\x3b\x62\x6f\162\144\145\x72\x3a\61\x70\x78\40\x73\157\154\x69\x64\40\x23\x45\x36\x42\x33\102\x32\x3b\x66\x6f\x6e\x74\x2d\x73\x69\x7a\x65\x3a\x31\70\160\164\x3b\42\76\x54\x45\123\x54\40\106\101\x49\114\x45\x44\x3c\x2f\144\x69\x76\x3e\15\12\11\11\x9\11\x3c\x64\151\x76\40\163\x74\171\154\x65\75\x22\x63\x6f\x6c\157\x72\x3a\x20\x23\x61\71\x34\x34\64\x32\73\x66\x6f\x6e\164\55\x73\151\172\145\x3a\x31\x34\x70\164\x3b\40\155\141\162\147\x69\x6e\55\142\x6f\x74\x74\x6f\x6d\x3a\62\60\x70\170\x3b\42\x3e\x57\x41\x52\116\x49\x4e\107\72\x20\123\x6f\x6d\145\40\101\x74\164\162\151\x62\x75\x74\145\x73\x20\104\x69\x64\40\x4e\157\164\40\115\x61\x74\143\150\x2e\x3c\x2f\144\x69\166\76\xd\12\11\x9\11\11\74\x64\151\x76\x20\x73\x74\171\x6c\145\x3d\42\x64\x69\x73\x70\x6c\x61\171\72\142\154\x6f\x63\x6b\73\x74\x65\x78\164\55\141\154\151\x67\x6e\x3a\143\x65\156\164\145\x72\x3b\155\141\x72\147\151\x6e\55\x62\157\164\x74\157\x6d\72\64\45\73\42\x3e\74\151\x6d\x67\x20\x73\x74\x79\x6c\x65\x3d\42\x77\x69\144\x74\x68\72\61\x35\45\73\x22\163\162\x63\x3d\x22" . esc_url(SAMLSPUtilities::mo_saml_get_plugin_base_url()) . "\151\155\141\147\145\163\57\167\162\157\x6e\147\x2e\167\145\142\160\x22\x3e\74\x2f\x64\151\166\76";
    goto fRV;
    mt4:
    $BM = EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\137\x73\141\155\154\137\164\145\x73\x74\x5f\143\157\x6e\x66\151\147\x5f\141\x74\x74\162\163", true, $CP);
    if (empty($XE)) {
        goto BNF;
    }
    $Ct = array($fg => $r0);
    $BM = SAMLSPUtilities::mo_saml_array_merge($BM, $Ct);
    $s6 = new EnvironmentDao($CP);
    $s6->mo_save_environment_settings("\155\x6f\x5f\163\141\155\x6c\137\164\145\163\x74\137\143\x6f\156\x66\151\x67\137\141\x74\164\162\163", $BM, false);
    BNF:
    echo "\x3c\144\x69\166\40\163\164\171\154\145\x3d\x22\143\157\x6c\x6f\162\x3a\40\43\x33\x63\x37\66\63\x64\73\xd\12\40\40\x20\x20\40\x20\x20\x20\40\40\40\x20\x20\40\x20\x20\142\141\143\153\147\162\x6f\x75\156\x64\55\143\x6f\154\x6f\162\72\40\x23\144\x66\146\x30\x64\x38\73\40\x70\x61\144\x64\151\x6e\x67\x3a\x32\x25\x3b\155\x61\x72\147\151\156\x2d\x62\x6f\164\x74\157\155\72\62\60\160\170\73\x74\x65\170\164\x2d\x61\154\x69\x67\156\72\x63\x65\x6e\164\145\162\73\40\142\157\162\x64\x65\x72\x3a\61\x70\170\x20\163\x6f\154\151\144\x20\x23\101\105\104\x42\71\x41\73\40\x66\157\156\164\x2d\163\x69\172\x65\72\x31\x38\x70\x74\x3b\x22\x3e\124\x45\123\124\40\x53\x55\103\x43\x45\123\x53\106\x55\x4c\74\57\x64\x69\x76\x3e\15\12\40\40\x20\40\40\40\x20\x20\40\40\x20\40\40\x20\40\40\x3c\x64\x69\166\x20\163\164\x79\154\x65\x3d\42\x64\x69\163\160\x6c\141\171\x3a\142\x6c\157\x63\153\73\164\145\x78\x74\55\141\x6c\151\x67\x6e\x3a\x63\x65\156\x74\x65\x72\x3b\155\x61\162\x67\151\156\x2d\142\x6f\164\164\x6f\155\x3a\x34\45\x3b\x22\x3e\x3c\151\x6d\x67\x20\x73\164\x79\154\x65\75\x22\167\x69\x64\x74\x68\72\61\65\x25\x3b\42\163\x72\x63\x3d\42" . esc_url(SAMLSPUtilities::mo_saml_get_plugin_base_url()) . "\151\155\141\x67\145\163\x2f\x67\162\145\x65\x6e\x5f\x63\x68\x65\x63\153\56\x77\x65\x62\160\42\76\x3c\57\x64\x69\x76\76";
    fRV:
    $sp = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\x61\155\154\137\144\157\x6d\141\151\156\137\x72\x65\163\164\x72\151\143\x74\x69\157\156", true, $CP);
    $GF = $fg;
    if (!empty($sp[$GF])) {
        goto UDe;
    }
    $GF = "\x44\105\x46\x41\125\114\x54";
    UDe:
    if (empty($sp[$GF])) {
        goto RBv;
    }
    $LE = $sp[$GF]["\x65\156\x61\142\154\145\137\144\157\x6d\141\151\156\x5f\162\x65\163\x74\162\x69\x63\164\x69\157\x6e"];
    RBv:
    if (empty($LE)) {
        goto Ago;
    }
    $zE = $sp[$GF]["\141\154\x6c\x6f\167\x5f\x64\x65\x6e\171\137\154\x6f\x67\x69\x6e"];
    if (!empty($zE) && $zE == "\x64\x65\x6e\171") {
        goto AwV;
    }
    $CK = $sp[$GF]["\145\155\141\x69\x6c\137\x64\x6f\155\141\x69\x6e\x73"];
    $ME = explode("\x3b", $CK);
    $ct = explode("\100", $TS);
    $yH = !empty($ct[1]) ? $ct[1] : '';
    if (SAMLSPUtilities::mo_saml_in_array($yH, $ME)) {
        goto bNa;
    }
    echo "\74\x70\x20\163\x74\171\154\x65\x3d\42\143\x6f\x6c\x6f\162\72\x72\x65\x64\73\42\76\x54\x68\x69\163\x20\x75\163\145\x72\x20\167\151\x6c\x6c\40\x6e\x6f\x74\40\142\x65\x20\x61\154\x6c\x6f\167\x65\144\x20\164\157\40\x6c\x6f\x67\x69\156\40\x61\x73\40\164\150\x65\40\x64\x6f\x6d\141\x69\x6e\x20\x6f\x66\x20\x74\x68\145\x20\x65\155\141\151\154\40\x69\163\40\x6e\157\164\x20\x69\x6e\143\x6c\165\144\145\144\40\x69\x6e\40\x74\x68\x65\x20\x61\154\x6c\x6f\x77\x65\x64\x20\x6c\x69\x73\x74\40\x6f\146\40\104\157\155\x61\151\156\40\122\145\x73\164\162\151\x63\164\151\157\156\x2e\74\x2f\x70\76";
    bNa:
    goto XTr;
    AwV:
    $CK = $sp[$GF]["\x65\x6d\141\x69\x6c\x5f\144\157\155\141\151\156\x73"];
    $ME = array_map("\164\162\151\155", explode("\x3b", $CK));
    $ME = array_map("\163\x74\x72\164\157\x6c\x6f\x77\x65\162", $ME);
    $ct = explode("\x40", $TS);
    $yH = !empty($ct[1]) ? $ct[1] : '';
    $yH = strtolower(trim($yH));
    if (!SAMLSPUtilities::mo_saml_in_array($yH, $ME)) {
        goto V_Z;
    }
    echo "\x3c\x70\40\163\164\171\154\145\75\42\143\x6f\154\x6f\x72\x3a\162\145\x64\x3b\42\76\x54\x68\x69\163\x20\165\x73\145\162\x20\167\x69\154\x6c\x20\156\157\x74\40\x62\x65\40\x61\154\154\x6f\167\145\144\40\x74\157\x20\x6c\157\x67\151\156\40\x61\x73\x20\164\150\x65\x20\144\157\x6d\141\x69\x6e\40\157\x66\x20\164\x68\145\40\145\155\x61\x69\154\40\x69\163\40\151\156\143\x6c\x75\144\145\x64\40\x69\156\x20\x74\150\x65\x20\x64\145\x6e\151\145\144\x20\x6c\151\x73\164\x20\x6f\x66\x20\x44\157\x6d\141\151\x6e\x20\122\145\163\164\162\x69\143\x74\x69\157\156\56\74\x2f\x70\x3e";
    V_Z:
    XTr:
    Ago:
    $ey = Mo_SAML_Config_Utility::mo_saml_get_attr_configurations($fg, $CP);
    $BM = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\137\x73\x61\155\154\x5f\x74\145\x73\x74\x5f\x63\x6f\156\x66\151\x67\x5f\141\164\x74\162\x73", true);
    $JW = '';
    if (empty($ey["\x75\163\145\162\x6e\x61\155\x65"])) {
        goto Qy6;
    }
    $Gv = $ey["\x75\x73\145\x72\156\x61\x6d\x65"];
    if (empty($BM[$fg][$Gv][0])) {
        goto jQy;
    }
    $JW = $BM[$fg][$Gv][0];
    jQy:
    Qy6:
    if (!($Yj == "\164\145\x73\164\x56\x61\x6c\x69\144\141\x74\145" || $Yj == "\x74\145\163\164\116\x65\x77\103\145\162\164\151\x66\151\143\x61\x74\145")) {
        goto u1n;
    }
    if (!(strlen($JW) > 60)) {
        goto V2k;
    }
    echo "\x3c\160\40\163\164\171\154\145\75\x22\143\157\154\x6f\162\72\x72\145\144\x3b\x22\x3e\116\117\124\x45\x20\72\40\124\150\151\163\40\x75\x73\145\162\x20\x77\151\x6c\x6c\40\156\x6f\164\40\142\x65\40\141\142\154\145\40\x74\x6f\40\154\157\147\151\x6e\40\x61\163\40\x74\x68\x65\x20\165\163\145\162\156\x61\x6d\x65\40\x76\x61\154\x75\x65\x20\x69\163\40\x6d\x6f\162\x65\x20\x74\150\141\156\40\66\60\x20\x63\x68\x61\x72\x61\143\164\x65\162\163\40\x6c\x6f\156\147\x2e\74\x62\x72\57\x3e\15\xa\x9\11\x20\x20\40\x20\120\x6c\x65\141\163\145\x20\x74\162\x79\x20\x63\150\141\x6e\x67\151\x6e\x67\40\164\x68\145\x20\x6d\x61\160\160\x69\x6e\x67\x20\157\x66\x20\125\163\145\162\156\x61\x6d\145\x20\x66\151\145\x6c\144\x20\151\156\x20\74\x61\x20\150\x72\145\146\x3d\x22\43\42\40\x6f\156\x43\154\x69\143\x6b\x3d\x22\143\154\x6f\x73\145\101\x6e\x64\x52\145\x64\151\162\145\143\x74\124\x6f\101\x74\164\x72\151\142\165\164\x65\x4d\x61\x70\160\x69\x6e\147\x28\x29\x3b\42\76\101\x74\x74\162\151\142\165\164\145\x2f\122\157\154\x65\40\115\x61\160\x70\x69\x6e\x67\x3c\57\x61\76\x20\x74\141\142\56\74\x2f\x70\76";
    V2k:
    echo "\74\x73\x70\x61\156\40\163\x74\x79\x6c\x65\x3d\42\146\x6f\x6e\x74\x2d\163\151\x7a\145\x3a\61\64\x70\164\x3b\42\x3e\x3c\x62\76\x48\x65\x6c\154\x6f\74\57\x62\76\54\40" . esc_html($TS) . "\x3c\x2f\163\x70\141\x6e\76\x3c\x62\162\57\76\x3c\x70\40\x73\x74\171\x6c\145\75\x22\146\x6f\x6e\164\x2d\x77\x65\x69\x67\150\164\72\x62\157\154\x64\x3b\146\157\156\x74\55\x73\x69\x7a\x65\x3a\61\64\x70\x74\73\x6d\141\x72\x67\151\x6e\x2d\x6c\x65\146\164\72\x31\x25\73\42\76\x41\124\124\x52\111\x42\125\x54\x45\x53\40\122\x45\103\x45\111\x56\105\x44\72\74\57\x70\x3e\xd\12\11\11\11\x9\x3c\164\x61\x62\154\x65\x20\x73\x74\x79\154\x65\x3d\42\x62\x6f\162\144\x65\162\55\x63\157\154\154\141\x70\163\x65\72\x63\157\x6c\x6c\x61\x70\163\145\x3b\x62\x6f\x72\x64\x65\x72\55\x73\x70\141\143\x69\156\147\72\x30\x3b\40\x77\x69\144\164\x68\x3a\61\x30\x30\45\73\x20\146\x6f\x6e\164\x2d\x73\151\x7a\x65\72\61\64\x70\164\x3b\142\141\143\x6b\147\x72\157\x75\156\144\x2d\143\157\154\x6f\x72\72\x23\105\x44\x45\x44\x45\x44\x3b\42\x3e\xd\12\11\x9\11\x9\x3c\164\162\x20\163\164\x79\x6c\x65\75\42\164\145\170\x74\x2d\x61\x6c\x69\147\x6e\72\143\x65\156\164\x65\x72\x3b\x22\x3e\x3c\x74\x64\40\x73\164\171\x6c\x65\75\42\146\157\x6e\x74\x2d\x77\145\151\x67\x68\164\72\142\x6f\x6c\x64\73\x62\157\x72\x64\145\162\72\x32\160\170\40\163\x6f\x6c\151\144\40\43\x39\64\71\x30\x39\x30\x3b\160\141\144\x64\x69\x6e\147\x3a\x32\x25\73\42\76\101\x54\x54\x52\111\x42\125\124\105\40\x4e\x41\x4d\x45\x3c\x2f\x74\x64\76\74\x74\144\40\163\164\171\154\145\x3d\x22\x66\x6f\156\x74\x2d\167\x65\x69\x67\x68\x74\x3a\142\x6f\154\144\73\160\141\x64\x64\x69\156\x67\72\62\45\x3b\142\x6f\x72\144\145\162\72\x32\160\170\x20\x73\x6f\154\x69\144\40\x23\x39\64\71\60\x39\60\x3b\x20\x77\x6f\162\x64\x2d\x77\x72\141\x70\72\x62\162\x65\x61\x6b\x2d\167\157\x72\144\x3b\x22\x3e\101\x54\x54\x52\111\102\125\124\105\40\x56\101\x4c\x55\x45\74\x2f\x74\144\76\x3c\57\164\162\x3e";
    if (!empty($r0)) {
        goto aTz;
    }
    echo "\116\157\x20\101\x74\x74\x72\151\x62\165\x74\x65\163\40\x52\145\143\145\151\x76\145\144\56";
    goto mgT;
    aTz:
    foreach ($r0 as $R2 => $EB) {
        echo "\x3c\x74\x72\x3e\74\164\144\40\x73\x74\x79\x6c\x65\x3d\47\x66\x6f\x6e\164\x2d\167\145\151\147\x68\x74\x3a\x62\157\154\x64\x3b\142\x6f\x72\144\145\162\x3a\62\x70\x78\x20\163\x6f\x6c\151\x64\40\43\71\x34\71\x30\x39\x30\73\160\141\144\144\x69\156\x67\72\62\45\x3b\167\x6f\162\x64\x2d\167\x72\141\160\x3a\x62\x72\145\141\153\x2d\167\x6f\x72\x64\73\x27\x3e" . esc_html($R2) . "\74\x2f\x74\144\76\x3c\164\x64\x20\x73\164\171\x6c\145\75\47\x70\141\x64\x64\151\x6e\147\x3a\x32\x25\73\x62\x6f\x72\x64\145\x72\x3a\x32\x70\x78\40\163\x6f\154\x69\x64\x20\43\x39\x34\71\x30\71\60\73\40\167\157\162\x64\x2d\167\x72\141\160\72\142\162\x65\141\153\55\167\157\x72\144\73\x27\76" . implode("\x3c\x68\162\57\76", map_deep($EB, "\x65\163\143\137\150\x74\x6d\154")) . "\x3c\57\x74\x64\76\x3c\57\x74\x72\x3e";
        an4:
    }
    BRs:
    mgT:
    echo "\x3c\x2f\x74\141\x62\x6c\x65\x3e\x3c\x2f\144\151\x76\x3e";
    echo "\x3c\x64\151\x76\x20\x73\x74\171\154\x65\x3d\42\155\x61\x72\147\151\x6e\x3a\63\45\73\x64\x69\163\x70\154\141\171\x3a\142\x6c\157\143\x6b\x3b\x74\x65\170\164\55\x61\x6c\x69\x67\x6e\x3a\x63\145\x6e\x74\x65\162\73\x22\76\xd\12\x20\40\40\40\x3c\x69\x6e\160\165\x74\40\x73\164\x79\154\x65\x3d\x22\160\141\x64\144\x69\x6e\x67\x3a\x31\x25\x3b\167\x69\x64\164\150\72\x32\65\60\x70\170\x3b\x62\x61\143\153\147\x72\x6f\x75\156\144\72\x20\x23\x30\x30\71\x31\103\104\x20\x6e\x6f\156\145\x20\162\145\x70\x65\x61\x74\x20\163\143\162\x6f\154\x6c\x20\x30\45\x20\60\x25\x3b\15\xa\x9\11\x63\165\162\x73\x6f\x72\x3a\x20\160\157\151\156\x74\145\162\x3b\146\157\x6e\x74\x2d\x73\151\x7a\145\x3a\x31\x35\160\170\73\x62\157\x72\144\145\162\x2d\167\x69\144\x74\150\x3a\x20\x31\x70\170\x3b\x62\x6f\x72\x64\145\x72\55\163\x74\171\154\145\72\x20\x73\x6f\154\151\x64\x3b\142\x6f\x72\144\145\x72\55\x72\x61\144\x69\x75\163\72\40\x33\160\170\73\167\150\x69\164\x65\x2d\163\160\x61\x63\x65\72\15\xa\11\x9\x20\156\x6f\x77\162\x61\x70\x3b\142\x6f\170\55\163\151\x7a\151\156\x67\72\40\x62\x6f\x72\x64\x65\162\55\x62\x6f\170\73\x62\x6f\162\x64\145\162\x2d\x63\157\x6c\157\162\x3a\x20\43\x30\x30\x37\63\x41\101\x3b\142\x6f\170\x2d\163\150\x61\144\157\x77\x3a\40\60\160\x78\x20\61\x70\x78\40\x30\x70\170\x20\162\x67\x62\141\x28\x31\62\60\54\x20\x32\60\60\54\40\x32\x33\60\54\40\60\x2e\66\x29\x20\x69\156\x73\x65\x74\73\143\x6f\x6c\x6f\x72\72\x20\x23\106\106\106\x3b\42\xd\12\40\x20\40\x20\40\x20\40\40\x20\40\40\x20\164\171\x70\x65\x3d\x22\142\165\164\164\157\156\42\x20\x76\141\x6c\x75\x65\x3d\42\103\x6f\156\x66\151\x67\x75\162\x65\40\101\x74\164\162\x69\142\x75\164\x65\57\122\157\154\145\40\115\x61\160\160\151\156\x67\x22\x20\157\156\103\x6c\151\143\153\x3d\x22\x63\154\x6f\x73\x65\x41\x6e\144\122\145\144\151\x72\x65\143\164\x54\157\101\x74\164\x72\151\x62\165\x74\x65\x4d\141\160\160\151\x6e\x67\x28\51\x3b\42\x3e\x20\46\x6e\x62\x73\160\x3b\x20\xd\12\x20\40\40\x20\x20\x20\40\x20\15\12\x20\x20\40\x20\x3c\151\156\160\x75\x74\x20\x73\164\x79\154\x65\75\x22\160\x61\x64\144\x69\x6e\147\x3a\61\45\x3b\x77\x69\144\x74\150\x3a\61\x30\x30\x70\170\x3b\x62\141\143\x6b\147\x72\157\165\156\144\72\x20\43\x30\60\71\x31\x43\104\x20\156\157\156\x65\40\x72\145\x70\145\x61\x74\x20\x73\x63\162\x6f\x6c\154\x20\60\45\x20\60\x25\73\143\165\x72\x73\x6f\x72\72\40\x70\157\x69\x6e\164\145\162\73\146\157\156\x74\x2d\x73\151\x7a\145\x3a\x31\65\160\x78\73\142\157\x72\x64\x65\x72\55\167\151\144\164\150\x3a\40\x31\x70\x78\x3b\142\157\x72\144\x65\162\x2d\x73\164\x79\x6c\x65\x3a\40\x73\157\x6c\151\144\73\x62\x6f\162\x64\x65\x72\55\162\x61\x64\151\x75\163\72\40\63\x70\170\x3b\167\150\x69\164\145\55\x73\160\x61\x63\145\72\40\x6e\157\167\x72\x61\160\x3b\x62\157\170\55\163\x69\x7a\x69\156\x67\72\x20\142\157\x72\x64\x65\x72\x2d\142\157\x78\x3b\142\157\x72\x64\x65\x72\x2d\x63\x6f\154\x6f\162\x3a\x20\x23\60\x30\67\63\101\x41\73\142\x6f\x78\55\x73\150\x61\144\157\x77\x3a\x20\60\160\170\40\x31\160\170\40\60\160\x78\x20\162\147\142\x61\x28\61\x32\x30\54\40\x32\x30\x30\54\40\x32\x33\x30\x2c\x20\60\x2e\x36\51\40\151\156\163\145\164\73\143\x6f\154\x6f\x72\72\40\43\x46\106\x46\73\x22\x74\x79\160\x65\x3d\x22\142\x75\x74\x74\157\x6e\42\40\166\141\154\x75\x65\75\x22\x44\x6f\156\x65\x22\x20\157\156\103\154\151\x63\153\x3d\x22\x63\x6c\157\163\x65\x41\156\x64\122\145\x66\x72\145\x73\x68\x28\x29\x22\x3e\x3c\x2f\144\x69\166\76";
    echo "\15\xa\x20\40\40\x20\74\163\x63\162\151\x70\x74\x3e\15\12\x20\x20\40\x20\x20\x20\x20\40\146\165\x6e\143\164\x69\157\156\40\143\x6c\157\163\x65\x41\156\144\122\145\144\x69\162\145\x63\164\124\x6f\x41\x74\x74\162\151\142\165\164\145\115\141\160\160\151\x6e\x67\x28\x29\40\173\15\12\40\40\40\x20\x20\x20\40\40\40\x20\x20\x20\151\146\40\x28\167\x69\x6e\144\x6f\167\x2e\x6f\160\x65\x6e\145\162\x29\40\173\xd\12\40\x20\40\40\40\x20\x20\x20\x20\x20\40\40\x20\x20\x20\40\x77\x69\156\144\x6f\167\x2e\157\160\145\x6e\x65\x72\x2e\162\x65\144\151\162\x65\x63\164\137\x74\x6f\137\141\x74\x74\x72\x69\x62\x75\x74\x65\137\x6d\141\x70\x70\x69\156\x67\x28\40\47" . esc_url_raw(mo_saml_get_attribute_mapping_url($fg)) . "\x27\40\x29\x3b\15\xa\40\x20\x20\40\40\40\x20\40\40\40\x20\x20\175\xd\xa\x20\40\40\40\x20\40\x20\40\40\40\40\x20\163\x65\x6c\146\56\x63\x6c\x6f\163\x65\50\x29\73\15\xa\40\40\40\x20\40\40\40\x20\x7d\40\40\15\xa\40\x20\x20\40\x20\40\40\x20\146\x75\156\x63\164\x69\x6f\x6e\x20\143\154\x6f\x73\x65\101\156\144\122\x65\146\162\145\x73\150\x28\x29\173\xd\xa\x20\x20\x20\x20\40\40\x20\40\x20\x20\40\40\151\146\40\x28\167\151\x6e\144\x6f\x77\56\x6f\160\x65\156\145\162\x29\40\x7b\15\xa\40\40\40\40\40\x20\40\x20\40\40\40\x20\40\40\40\x20\x77\151\x6e\x64\157\167\56\x6f\x70\x65\x6e\145\x72\56\x6c\x6f\143\x61\164\x69\157\156\56\162\145\154\x6f\141\144\50\51\x3b\15\xa\40\x20\40\x20\x20\40\40\40\40\x20\x20\40\175\15\12\40\40\x20\x20\x20\x20\x20\40\x20\40\40\x20\x73\x65\154\x66\x2e\x63\x6c\x6f\163\145\x28\x29\73\15\xa\40\40\x20\40\x20\40\x20\x20\175\x20\15\12\x20\40\40\x20\74\57\x73\143\162\151\160\164\76";
    u1n:
    exit;
}
function mo_saml_convert_to_windows_iconv($hS, $XE)
{
    $CP = EnvironmentHelper::getCurrentEnvironment();
    $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\155\x6c\137\x69\144\x65\156\164\x69\x74\x79\137\x70\162\157\x76\x69\x64\145\162\163", true, $CP);
    if (empty($XE["\151\144\160\x5f\x6e\141\x6d\x65"])) {
        goto icW;
    }
    $fg = $XE["\x69\x64\x70\137\156\x61\155\x65"];
    goto G8b;
    icW:
    $fg = '';
    G8b:
    if (!(!empty($fg) and !empty($rK[$fg]))) {
        goto PQw;
    }
    $yW = $rK[$fg]["\155\x6f\137\163\141\x6d\x6c\137\145\x6e\143\x6f\144\x69\156\147\137\145\156\x61\142\x6c\x65\x64"];
    if (!($yW === "\x63\x68\145\143\153\145\x64" && mo_saml_is_extension_installed(Mo_Saml_Options_Enum_Extension::ICONV))) {
        goto lgi;
    }
    return @iconv(Mo_Saml_Options_Enum_Encoding::ENCODING_UTF_8, Mo_Saml_Options_Enum_Encoding::ENCODING_CP1252, $hS);
    lgi:
    PQw:
    return $hS;
}
function mo_saml_login_user($Io, $RY, $s9, $AA, $K0, $kW, $Rn, $fk, $DE, $qZ)
{
    $Io = sanitize_user($Io, true);
    $Io = trim(apply_filters("\160\x72\145\x5f\x75\163\x65\x72\x5f\154\157\147\x69\156", sanitize_user($Io)));
    if (!(strlen($Io) > 60)) {
        goto PAr;
    }
    Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\127\x50\x53\101\x4d\x4c\x45\x52\122\x30\61\x31"]);
    PAr:
    $BB = $fk["\x69\x64\x70\137\156\x61\x6d\145"];
    if (!empty($fk["\145\156\141\x62\x6c\x65\137\x69\x64\160"])) {
        goto ORm;
    }
    throw new Mo_SAML_IDP_Status_Inactive_Exception("\111\x44\x50\40\x4e\x6f\x74\40\105\x6e\141\x62\154\145\x64\x2e");
    ORm:
    do_action("\155\x6f\137\x61\142\x72\137\146\151\154\164\x65\x72\137\x6c\x6f\x67\151\x6e", $qZ);
    $CP = EnvironmentHelper::getCurrentEnvironment();
    $CY = false;
    if (!SAMLSPUtilities::mo_saml_is_plugin_active("\155\x69\156\151\x6f\162\x61\156\x67\x65\x2d\x61\144\x76\141\156\143\x65\x64\55\162\157\154\145\55\155\141\160\160\151\156\147\x2f\x61\x64\x76\x61\x6e\x63\x65\x64\55\162\157\x6c\x65\x2d\x6d\x61\x70\160\151\156\x67\56\160\x68\160")) {
        goto Byg;
    }
    $CY = true;
    Byg:
    $xU = Mo_SAML_Config_Utility::mo_saml_check_if_idp_configurations_configured($BB, "\x72\157\x6c\145\137\155\x61\160\x70\151\156\x67", $CP) ? $BB : "\104\105\106\101\125\114\x54";
    $Lk = Mo_SAML_Config_Utility::mo_saml_check_if_idp_configurations_configured($BB, "\141\144\166\x61\156\x63\145\144\x5f\163\145\x74\x74\151\156\147\x73", $CP) ? $BB : "\x44\x45\106\101\125\x4c\124";
    $Jr = Mo_SAML_Config_Utility::mo_saml_get_attr_role_advanced_settings($Lk, $CP);
    if ($CY) {
        goto NTX;
    }
    mo_saml_check_if_attribute_restricted($qZ, $Jr);
    NTX:
    mo_saml_check_if_domain_restricted($RY, $Jr, $CP);
    $H9 = Mo_SAML_Config_Utility::mo_saml_get_role_mapping_values($xU, $CP);
    $tE = Mo_SAML_Config_Utility::mo_saml_get_role_mapping($xU, $CP);
    $f7 = !empty($tE["\x67\162\157\165\x70\x5f\x6e\x61\155\x65"]) ? $tE["\147\162\157\x75\x70\137\x6e\141\155\x65"] : '';
    $Au = !empty($qZ[$f7]) && is_array($qZ[$f7]) ? array_filter($qZ[$f7]) : array();
    $Au = apply_filters("\x6d\157\x5f\147\x72\x6f\x75\x70\x5f\163\145\x70\x61\x72\x61\164\157\x72", $Au);
    $user = false;
    if (username_exists($Io)) {
        goto iT4;
    }
    if (email_exists($RY)) {
        goto gYl;
    }
    goto W_k;
    iT4:
    $user = get_user_by("\154\x6f\147\151\156", $Io);
    goto W_k;
    gYl:
    $user = get_user_by("\145\155\x61\151\x6c", $RY);
    W_k:
    $bt = false;
    if (!is_multisite()) {
        goto M1I;
    }
    if (empty($user)) {
        goto PiZ;
    }
    $Ur = $user->ID;
    $blog_id = get_current_blog_id();
    if (is_user_member_of_blog($Ur, $blog_id)) {
        goto Ee2;
    }
    $bt = true;
    Ee2:
    PiZ:
    M1I:
    $QK = mo_options_user_meta::VALUE_SSO_USER;
    $V0 = mo_options_user_meta::KEY_USER_TYPE;
    if (!empty($user) && !$bt) {
        goto olT;
    }
    if (empty($user) || $bt) {
        goto OeZ;
    }
    goto wmW;
    olT:
    do_action("\x6d\x6f\137\x67\165\x65\x73\164\137\x6c\x6f\x67\x69\x6e", $qZ["\x4e\141\x6d\x65\111\x44"], $DE, $fk, false);
    if (!(!Mo_License_Service::is_customer_license_valid() && !user_can(get_user_by("\154\x6f\x67\151\x6e", $Io)->ID, "\x6d\141\156\141\x67\x65\137\157\x70\164\x69\157\x6e\x73"))) {
        goto K84;
    }
    throw new Mo_SAML_Invalid_License_Exception("\x49\x6e\166\141\x6c\x69\144\40\114\x69\x63\x65\x6e\163\x65");
    K84:
    if (username_exists($Io)) {
        goto BMS;
    }
    if (email_exists($RY)) {
        goto t9t;
    }
    goto kc6;
    BMS:
    $user = get_user_by("\154\x6f\147\151\156", $Io);
    goto kc6;
    t9t:
    $user = get_user_by("\x65\155\141\151\154", $RY);
    kc6:
    do_action("\x6d\x6f\x5f\163\141\x6d\x6c\137\x75\x70\x64\141\x74\x65\x5f\165\163\145\x72\x6e\141\155\x65", $Io, $BB);
    mo_saml_map_attributes($user, $s9, $AA, $K0, $kW, $qZ, true, $BB, $CP);
    if (!$CY) {
        goto sd5;
    }
    do_action("\x6d\157\137\x73\141\x6d\154\137\141\x73\x73\x69\147\156\x5f\x72\x6f\x6c\145\x5f\x61\x72\x6d", $user, $qZ, false, $BB);
    goto Q7j;
    sd5:
    $m0 = !empty($Jr["\153\145\145\x70\137\x65\170\x69\x73\164\x69\156\x67\x5f\165\x73\145\162\x73\x5f\162\157\x6c\145"]) ? $Jr["\x6b\145\145\x70\137\x65\170\x69\163\164\x69\x6e\x67\137\x75\163\145\162\163\x5f\162\x6f\154\145"] : '';
    $bw = !empty($tE["\x61\160\160\x6c\x79\137\162\x6f\154\145\137\x74\157\137\x61\144\x6d\x69\x6e"]) ? $tE["\x61\160\x70\154\x79\x5f\x72\x6f\x6c\x65\137\x74\x6f\137\141\144\x6d\151\156"] : '';
    if (!("\143\x68\x65\x63\153\x65\x64" !== $m0 && (!is_administrator_user($user) || "\x63\150\145\x63\153\x65\x64" === $bw))) {
        goto mCB;
    }
    mo_saml_assign_roles($user, false, $H9, $Au, $tE, $Jr);
    mCB:
    Q7j:
    update_user_meta($user->ID, $V0, $QK);
    mo_saml_create_cookie($user->ID, $BB, $DE, $qZ["\116\x61\155\145\x49\104"][0]);
    $fv = SAMLSPUtilities::mo_saml_get_redirect_url($Rn, $BB, $CP);
    do_action("\x6d\x69\x6e\151\x6f\x72\x61\x6e\x67\x65\137\160\x6f\163\x74\137\141\165\164\150\145\156\x74\x69\143\141\164\145\137\165\x73\x65\x72\x5f\x6c\157\x67\151\156", $user, null, $fv, true);
    do_action("\155\157\137\x73\141\x6d\x6c\x5f\141\x74\164\162\x69\142\x75\164\x65\x73", $Io, $RY, $s9, $AA, $Au, $BB, $qZ);
    do_action("\155\x6f\x5f\167\160\137\x75\163\x65\162\137\141\x74\164\x72\151\x62\165\x74\x65\x73", $user->ID, $qZ, $BB, "\x53\x41\115\114", false);
    do_action("\x77\x70\137\x6c\x6f\147\151\x6e", $user->user_login, $user);
    $fv = apply_filters("\x6d\x6f\x5f\x73\x61\155\154\x5f\160\x6f\163\x74\x5f\154\157\x67\x69\x6e\137\x73\x73\x6f\x5f\x72\145\154\141\171\x5f\x73\164\141\x74\x65", $fv);
    wp_redirect($fv);
    exit;
    goto wmW;
    OeZ:
    do_action("\155\x6f\137\147\x75\145\x73\164\137\x6c\157\147\x69\156", $qZ["\116\x61\155\145\x49\x44"], $DE, $fk, true);
    if (Mo_License_Service::is_customer_license_valid()) {
        goto Qi2;
    }
    throw new Mo_SAML_Invalid_License_Exception("\x49\156\166\141\154\x69\144\40\x4c\151\x63\x65\156\x73\145");
    Qi2:
    $xZ = !empty($Jr["\144\157\137\156\x6f\164\x5f\143\x72\x65\x61\x74\145\x5f\156\145\167\137\x75\163\145\x72\163"]) ? $Jr["\x64\x6f\x5f\156\x6f\164\137\x63\162\145\x61\x74\x65\137\x6e\145\167\137\x75\x73\x65\162\x73"] : '';
    if (!("\x63\150\x65\x63\x6b\x65\144" === $xZ)) {
        goto x3h;
    }
    Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\127\x50\123\x41\x4d\x4c\x45\122\x52\x30\61\70"]);
    x3h:
    $Zi = isset($tE["\x63\x72\x65\141\x74\145\x5f\x6e\145\x77\137\x75\x73\x65\x72"]) ? $tE["\143\x72\145\141\164\145\x5f\156\x65\x77\x5f\165\163\145\x72"] : "\x63\x68\145\x63\153\145\144";
    $uU = mo_saml_get_roles_to_assign($H9, $Au, $Jr);
    if (!("\x63\150\145\143\x6b\145\144" !== $Zi && empty($uU))) {
        goto YaM;
    }
    Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\x57\x50\123\x41\115\x4c\105\122\122\60\x31\x38"]);
    YaM:
    $user = mo_saml_create_user($user, $Io, $RY, $bt);
    if ($user) {
        goto XHi;
    }
    if (!empty($Rn)) {
        goto P_J;
    }
    wp_redirect(network_home_url());
    goto QRd;
    P_J:
    wp_redirect($Rn);
    QRd:
    exit;
    XHi:
    mo_saml_map_attributes($user, $s9, $AA, $K0, $kW, $qZ, true, $BB, $CP);
    if (!$CY) {
        goto WXz;
    }
    do_action("\x6d\157\137\163\x61\x6d\x6c\x5f\141\x73\163\151\147\x6e\x5f\x72\157\154\145\137\141\x72\x6d", $user, $qZ, true, $BB);
    goto fYQ;
    WXz:
    mo_saml_assign_roles($user, true, $H9, $Au, $tE, $Jr, $uU);
    fYQ:
    update_user_meta($user->ID, $V0, $QK);
    mo_saml_create_cookie($user->ID, $BB, $DE, $qZ["\x4e\141\x6d\x65\x49\104"][0]);
    $fv = SAMLSPUtilities::mo_saml_get_redirect_url($Rn, $BB, $CP);
    do_action("\x6d\151\156\151\x6f\162\x61\156\x67\145\x5f\x70\157\x73\x74\x5f\141\165\x74\x68\x65\x6e\x74\151\143\141\164\x65\137\165\x73\x65\162\x5f\154\157\x67\151\x6e", $user, null, $fv, false);
    do_action("\x6d\157\137\163\141\155\x6c\137\141\164\164\162\151\142\165\x74\x65\x73", $Io, $RY, $s9, $AA, $Au, $BB, $qZ);
    do_action("\155\157\137\x77\x70\x5f\165\163\x65\x72\137\141\x74\x74\x72\151\x62\x75\x74\145\x73", $user->ID, $qZ, $BB, "\x53\x41\115\114", true);
    do_action("\x77\x70\x5f\x6c\x6f\147\x69\156", $user->user_login, $user);
    $fv = apply_filters("\x6d\x6f\137\163\141\155\x6c\137\x70\x6f\163\164\137\154\x6f\147\x69\x6e\137\163\x73\x6f\x5f\x72\145\154\x61\x79\x5f\x73\164\x61\164\x65", $fv);
    wp_redirect($fv);
    exit;
    wmW:
}
function mo_saml_check_if_attribute_restricted($qZ, $Jr)
{
    $us = !empty($Jr["\141\x6c\x6c\x6f\x77\x5f\144\145\x6e\x79\x5f\x75\x73\x65\162\x5f\x61\x74\164\162\x69\142\165\x74\x65"]) ? $Jr["\141\154\154\157\x77\x5f\x64\145\156\x79\137\165\x73\x65\x72\137\141\x74\x74\162\151\142\165\164\x65"] : '';
    if (!("\x63\x68\x65\x63\153\x65\x64" === $us)) {
        goto a1x;
    }
    $VA = !empty($Jr["\162\145\163\164\162\x69\143\x74\x65\x64\x5f\141\164\164\162\x69\x62\x75\x74\x65"]) ? $Jr["\x72\x65\163\x74\x72\x69\x63\x74\145\x64\137\141\164\164\x72\x69\142\x75\164\145"] : '';
    $b0 = !empty($Jr["\162\145\163\164\x72\x69\x63\164\145\144\x5f\141\x74\164\x72\x69\142\x75\164\145\137\x76\x61\154\165\x65\x73"]) ? $Jr["\x72\145\163\x74\162\151\x63\x74\x65\x64\x5f\x61\x74\164\x72\151\x62\165\164\145\137\x76\x61\x6c\x75\145\163"] : '';
    $RJ = !empty($Jr["\141\154\x6c\157\x77\x5f\x64\x65\156\x79\x5f\141\164\164\162\137\157\160\164\151\x6f\156"]) ? $Jr["\141\154\x6c\x6f\x77\x5f\144\x65\x6e\x79\137\141\x74\164\162\137\157\160\x74\x69\157\156"] : "\141\154\x6c\157\167";
    $b0 = array_map("\164\x72\x69\155", array_filter(explode("\x3b", $b0)));
    $S3 = false;
    foreach ($b0 as $I2) {
        if (!SAMLSPUtilities::mo_saml_in_array($I2, $qZ[$VA], true)) {
            goto Kfc;
        }
        $S3 = true;
        goto p2v;
        Kfc:
        rdc:
    }
    p2v:
    if (!("\x64\145\156\171" === $RJ && $S3 || "\x61\154\x6c\x6f\167" === $RJ && !$S3)) {
        goto j7j;
    }
    Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\127\120\123\101\115\114\x45\x52\x52\x30\61\71"]);
    j7j:
    a1x:
}
function mo_saml_check_if_domain_restricted($RY, $Jr, $CP)
{
    $Hm = !empty($Jr["\x61\x6c\154\x6f\167\137\144\x65\x6e\171\137\165\163\145\x72\x5f\x64\157\155\x61\151\156"]) ? $Jr["\141\154\154\x6f\x77\x5f\144\145\x6e\171\x5f\165\x73\x65\x72\137\x64\157\x6d\x61\x69\x6e"] : '';
    if (!("\x63\x68\145\x63\x6b\x65\x64" === $Hm)) {
        goto Sn6;
    }
    $PL = !empty($Jr["\162\x65\163\164\x72\x69\x63\164\145\x64\x5f\144\157\155\141\151\x6e\x73"]) ? $Jr["\162\145\163\164\162\x69\x63\164\x65\144\137\144\157\155\141\151\156\163"] : '';
    $RJ = !empty($Jr["\141\x6c\154\x6f\167\x5f\144\x65\x6e\171\137\144\x6f\x6d\x61\151\x6e\x5f\x6f\160\164\151\x6f\156"]) ? $Jr["\141\x6c\x6c\157\x77\137\x64\145\x6e\x79\x5f\x64\157\155\x61\151\x6e\x5f\x6f\160\x74\x69\x6f\156"] : "\x61\x6c\x6c\x6f\167";
    $ME = array_map("\164\162\151\155", array_filter(explode("\x3b", $PL)));
    $ME = array_map("\x73\164\162\164\x6f\x6c\x6f\x77\145\x72", $ME);
    $Yl = explode("\x40", $RY);
    $vj = !empty($Yl[1]) ? strtolower(trim($Yl[1])) : '';
    $h9 = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\137\x73\141\155\x6c\x5f\162\145\163\x74\x72\151\x63\164\x65\x64\137\144\157\155\141\151\156\137\145\162\x72\157\162\137\x6d\x73\147", false, $CP);
    $i7 = SAMLSPUtilities::mo_saml_is_plugin_active(Mo_Saml_Addons_Directory::CUSTOM_SSO_ERROR_MESSAGE) ? true : false;
    if ("\141\154\154\x6f\167" === $RJ && !SAMLSPUtilities::mo_saml_in_array($vj, $ME)) {
        goto UeI;
    }
    if ("\144\x65\156\171" === $RJ && SAMLSPUtilities::mo_saml_in_array($vj, $ME)) {
        goto PeX;
    }
    goto lYX;
    UeI:
    if (!$i7) {
        goto hLn;
    }
    do_action("\x6d\x6f\x5f\143\165\x73\x74\x6f\x6d\x5f\x73\x73\157\137\145\162\x72\x6f\x72\137\x6d\x73\147", Mo_Saml_Hook_Constant::DOMAIN_RESTRICTION);
    hLn:
    if (empty($h9)) {
        goto Okz;
    }
    wp_die(esc_html($h9), "\120\145\x72\x6d\x69\163\163\151\x6f\x6e\40\x44\145\156\x69\x65\144\x20\72\40\116\x6f\x74\40\141\x20\x57\150\x69\x74\145\x6c\151\163\164\145\x64\40\x75\163\145\162\x2e");
    goto s7U;
    Okz:
    Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\127\120\123\101\115\x4c\105\122\x52\60\62\62"]);
    s7U:
    goto lYX;
    PeX:
    if (!$i7) {
        goto B9W;
    }
    do_action("\155\x6f\137\143\x75\163\x74\x6f\155\137\x73\163\157\137\x65\162\162\157\162\137\155\x73\x67", Mo_Saml_Hook_Constant::DOMAIN_RESTRICTION);
    B9W:
    if (empty($h9)) {
        goto po4;
    }
    wp_die(esc_html($h9), "\120\145\162\155\151\x73\163\151\x6f\156\40\104\145\x6e\x69\145\x64\x20\x3a\40\102\154\141\x63\x6b\x6c\151\163\x74\145\x64\40\x75\163\145\x72\56");
    goto InM;
    po4:
    Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\127\120\x53\101\x4d\114\105\x52\122\60\62\61"]);
    InM:
    lYX:
    Sn6:
}
function mo_saml_map_attributes($user, $s9, $AA, $K0, $kW, $qZ, $er, $BB, $XP)
{
    $BB = Mo_SAML_Config_Utility::mo_saml_check_if_idp_configurations_configured($BB, "\141\164\164\x72\151\142\165\164\x65\x5f\x6d\x61\160\160\151\x6e\x67", $XP) ? $BB : "\x44\x45\x46\101\125\114\x54";
    mo_saml_map_basic_attributes($user, $s9, $AA, $K0, $kW, $qZ, $er, $BB, $XP);
    mo_saml_map_custom_attributes($user, $qZ, $BB, $XP);
}
function mo_saml_map_basic_attributes($user, $s9, $AA, $K0, $kW, $qZ, $er, $BB, $XP)
{
    $Ur = $user->ID;
    if (empty($s9)) {
        goto ZPQ;
    }
    $user->data->first_name = $s9;
    ZPQ:
    if (empty($AA)) {
        goto KjG;
    }
    $user->data->last_name = $AA;
    KjG:
    if (empty($K0)) {
        goto BR2;
    }
    $user->data->nickname = $K0;
    BR2:
    update_user_meta($Ur, "\x6d\157\x5f\163\x61\155\154\x5f\165\x73\x65\x72\137\x61\164\x74\162\x69\142\165\x74\145\163", $qZ);
    $ey = Mo_SAML_Config_Utility::mo_saml_get_attr_configurations($BB, $XP);
    $Pe = !empty($ey["\144\157\x5f\x6e\x6f\164\137\165\160\x64\141\x74\x65\137\144\151\x73\160\x6c\x61\x79\x5f\156\x61\155\x65"]) ? $ey["\144\x6f\137\156\157\164\137\x75\160\144\x61\x74\x65\x5f\144\x69\x73\x70\154\x61\x79\x5f\156\x61\x6d\x65"] : '';
    if (!(!empty($kW) && ("\143\x68\145\x63\153\145\144" !== $Pe || !$er))) {
        goto P62;
    }
    if (strcmp($kW, "\125\123\105\x52\116\101\x4d\105") == 0) {
        goto V5M;
    }
    if (strcmp($kW, "\106\x4e\x41\115\105") == 0 && !empty($s9)) {
        goto q0G;
    }
    if (strcmp($kW, "\114\x4e\101\115\x45") == 0 && !empty($AA)) {
        goto YFe;
    }
    if (strcmp($kW, "\x4e\111\103\x4b\x5f\116\x41\115\x45") == 0 && !empty($K0)) {
        goto B7U;
    }
    if (strcmp($kW, "\x46\x4e\101\x4d\x45\x5f\114\x4e\x41\x4d\x45") == 0 && !empty($AA) && !empty($s9)) {
        goto y4T;
    }
    if (strcmp($kW, "\x4c\x4e\101\x4d\x45\137\x46\x4e\101\x4d\x45") == 0 && !empty($AA) && !empty($s9)) {
        goto MIw;
    }
    goto ib8;
    V5M:
    $user->data->display_name = $user->user_login;
    goto ib8;
    q0G:
    $user->data->display_name = $s9;
    goto ib8;
    YFe:
    $user->data->display_name = $AA;
    goto ib8;
    B7U:
    $user->data->display_name = $K0;
    goto ib8;
    y4T:
    $user->data->display_name = $s9 . "\x20" . $AA;
    goto ib8;
    MIw:
    $user->data->display_name = $AA . "\40" . $s9;
    ib8:
    P62:
    wp_update_user($user);
}
function mo_saml_map_custom_attributes($user, $qZ, $BB, $XP)
{
    $Ur = $user->ID;
    $rU = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\x73\x61\155\x6c\x5f\143\x75\x73\164\x6f\155\x5f\141\164\164\162\x73\137\x6d\x61\160\x70\x69\x6e\x67", true, $XP);
    $rU = !empty($rU[$BB]) ? $rU[$BB] : array();
    $qZ = apply_filters("\155\157\137\x73\x61\x6d\154\x5f\x63\x75\x73\164\157\155\137\x61\164\164\162\151\142\x75\164\x65\163\137\146\x69\x6c\164\145\162", $qZ);
    foreach ($rU as $R2 => $EB) {
        $EB = trim($EB);
        if (empty($qZ[$EB])) {
            goto Y5V;
        }
        if (is_array($qZ[$EB]) && count($qZ[$EB]) == 1) {
            goto sGc;
        }
        update_user_meta($Ur, trim($R2), $qZ[$EB]);
        goto VjZ;
        sGc:
        update_user_meta($Ur, trim($R2), $qZ[$EB][0]);
        VjZ:
        Y5V:
        sJM:
    }
    TW8:
}
function mo_saml_assign_roles($user, $zx, $gS, $Au, $tE, $Jr, $uU = array())
{
    if (!empty($uU)) {
        goto NHu;
    }
    $uU = mo_saml_get_roles_to_assign($gS, $Au, $Jr);
    NHu:
    if ($zx) {
        goto n25;
    }
    $vC = $Jr["\167\x68\151\164\x65\x6c\x69\x73\164\137\145\x78\151\163\164\x69\x6e\147\137\x75\x73\145\162\x73\x5f\x72\x6f\154\x65\x73"] ?? '';
    if (!("\x63\150\x65\143\153\145\x64" === $vC)) {
        goto Rfj;
    }
    $QM = is_array($Jr["\167\x68\x69\x74\x65\154\x69\163\164\x65\x64\137\162\157\154\145\x73"]) ? array_flip($Jr["\167\x68\151\x74\145\154\151\163\164\x65\x64\x5f\162\x6f\x6c\145\x73"]) : array();
    $jB = is_array($user->roles) ? $user->roles : array();
    $tD = array_intersect($QM, $jB);
    $uU = array_merge($uU, $tD);
    Rfj:
    n25:
    mo_saml_assign_roles_to_user($user, $uU, $zx, $tE);
}
function mo_saml_get_roles_to_assign($gS, $Au, $Jr)
{
    $Uc = !empty($Jr["\x65\156\x61\142\x6c\x65\x5f\162\x65\147\x65\170"]) ? $Jr["\145\156\x61\142\x6c\145\x5f\x72\x65\x67\145\x78"] : '';
    $uU = array();
    foreach ($gS as $zZ => $Fm) {
        $Fm = array_map("\164\162\151\155", array_filter(explode("\73", $Fm)));
        foreach ($Fm as $hQ) {
            foreach ($Au as $Ff) {
                if (!("\143\x68\145\143\x6b\145\x64" === $Uc && preg_match("\57" . $hQ . "\x2f", $Ff) || $Ff === $hQ)) {
                    goto GIB;
                }
                array_push($uU, $zZ);
                GIB:
                QxQ:
            }
            zI2:
            oCV:
        }
        xG_:
        gbt:
    }
    wOi:
    return $uU;
}
function mo_saml_create_cookie($Ur, $BB, $DE, $zI)
{
    wp_set_current_user($Ur);
    $dw = apply_filters("\x6d\x6f\137\162\x65\x6d\145\x6d\142\x65\162\x5f\155\x65", false);
    wp_set_auth_cookie($Ur, $dw, SAMLSPUtilities::mo_saml_is_ssl());
    if (empty($BB)) {
        goto pjq;
    }
    update_user_meta($Ur, "\x6d\157\x5f\x73\141\x6d\x6c\137\x6c\x6f\x67\x67\145\x64\x5f\151\156\x5f\x77\151\164\150\137\x69\x64\160", $BB);
    pjq:
    if (empty($DE)) {
        goto nhO;
    }
    update_user_meta($Ur, "\x6d\157\137\163\141\155\154\137\163\x65\x73\x73\151\x6f\156\x5f\151\x6e\144\145\x78", $DE);
    nhO:
    if (empty($zI)) {
        goto l4V;
    }
    update_user_meta($Ur, "\x6d\x6f\x5f\163\x61\x6d\x6c\x5f\156\x61\x6d\x65\x5f\151\x64", $zI);
    l4V:
    if (!(!session_id() || session_id() == '' || empty($_SESSION))) {
        goto mwB;
    }
    session_start();
    mwB:
    $_SESSION["\155\157\x5f\163\x61\155\154"]["\154\157\147\x67\x65\x64\137\151\x6e\137\167\x69\x74\x68\x5f\151\144\160"] = $BB;
    if (empty($DE)) {
        goto Bc5;
    }
    $_SESSION["\155\157\x5f\163\x61\155\x6c"]["\x73\145\x73\x73\151\x6f\156\111\x6e\x64\145\x78"] = $DE;
    Bc5:
    if (empty($zI)) {
        goto gvZ;
    }
    $_SESSION["\x6d\157\137\163\x61\x6d\154"]["\x6e\141\155\x65\111\144"] = $zI;
    gvZ:
}
function mo_saml_create_user($user, $Io, $RY, $bt)
{
    $yu = wp_generate_password(10, false);
    if (!$bt) {
        goto j_P;
    }
    $Ur = $user->ID;
    goto nQK;
    j_P:
    $Ur = wp_create_user($Io, $yu, $RY);
    if (!is_wp_error($Ur)) {
        goto dCG;
    }
    Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\x57\x50\x53\x41\x4d\114\105\122\x52\60\60\65"]);
    dCG:
    nQK:
    return get_user_by("\151\x64", $Ur);
}
function mo_saml_assign_roles_to_user($user, $uU, $zx, $tE)
{
    $dl = false;
    if (!empty($uU)) {
        goto Jhp;
    }
    if (!$zx && isset($tE["\165\x70\x64\141\x74\145\137\145\x78\151\x73\x74\151\x6e\147\137\165\163\x65\x72"]) && "\x63\x68\x65\x63\x6b\x65\x64" === $tE["\x75\x70\144\141\x74\145\x5f\145\170\x69\x73\x74\x69\156\x67\137\x75\x73\x65\x72"]) {
        goto WsZ;
    }
    if ($zx) {
        goto SHF;
    }
    goto tx7;
    Jhp:
    $user->set_role(false);
    foreach ($uU as $zZ) {
        $user->add_role($zZ);
        VnM:
    }
    aVW:
    goto tx7;
    WsZ:
    $dl = true;
    $Mm = !empty($tE["\x64\x65\146\141\165\x6c\164\x5f\162\x6f\154\145\137\146\157\x72\137\x65\x78\x69\163\x74\x69\156\147\137\x75\x73\x65\x72\163"]) ? $tE["\144\x65\146\x61\x75\x6c\164\137\x72\x6f\x6c\145\137\x66\157\x72\x5f\x65\x78\x69\163\x74\x69\x6e\x67\x5f\x75\x73\x65\x72\x73"] : get_option("\144\x65\146\141\165\x6c\164\137\x72\x6f\154\x65");
    goto tx7;
    SHF:
    $dl = true;
    $Mm = !empty($tE["\144\x65\146\x61\x75\x6c\164\137\162\x6f\154\x65\137\x66\x6f\x72\137\156\x65\x77\137\x75\x73\145\x72\163"]) ? $tE["\x64\145\x66\x61\x75\154\164\x5f\162\157\154\x65\x5f\146\157\162\137\x6e\x65\167\137\165\x73\x65\x72\163"] : get_option("\144\x65\146\141\165\154\164\137\x72\157\154\x65");
    tx7:
    if (!$dl) {
        goto KjW;
    }
    if ("\156\157\156\x65" === $Mm) {
        goto AYr;
    }
    $user->set_role($Mm);
    goto vuE;
    AYr:
    $user->set_role(false);
    vuE:
    KjW:
}
function show_status_error($vQ, $Yj, $l0, $XE)
{
    $vQ = strip_tags($vQ);
    $l0 = strip_tags($l0);
    if ($Yj == "\x74\145\x73\164\x56\141\154\x69\144\141\164\145" or $Yj == "\164\145\x73\x74\116\x65\167\103\x65\162\164\x69\146\151\143\x61\164\145") {
        goto pcA;
    }
    if ($vQ == "\x52\145\163\160\x6f\x6e\x64\x65\x72" || $vQ == "\x52\145\161\x75\145\x73\164\145\x72") {
        goto AyJ;
    }
    Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\127\120\123\x41\x4d\114\105\x52\122\60\60\x36"]);
    goto uu2;
    pcA:
    if (!($vQ == "\x52\x65\x73\160\x6f\x6e\144\x65\x72")) {
        goto uxL;
    }
    mo_saml_handle_azureb2c_cases($Yj, $l0, $XE);
    uxL:
    $bm = "\x3c\160\76\74\163\x74\162\157\x6e\147\76\123\x74\141\x74\x75\163\40\103\157\x64\x65\x20\x66\157\165\156\144\40\x69\x6e\40\123\x41\115\x4c\40\x52\145\x73\x70\x6f\x6e\x73\145\72\40\x3c\57\x73\x74\162\x6f\x6e\147\x3e\74\x66\x6f\x6e\164\40\x66\141\x63\145\75\42\103\157\x75\x72\151\x65\162\x20\116\145\167\x22\73\x66\157\x6e\164\x2d\x73\151\x7a\145\72\x31\60\x70\164\76\x3c\142\x72\x3e\74\142\162\x3e" . esc_html($vQ) . "\74\57\160\x3e\74\x2f\x66\157\x6e\164\76";
    Mo_Saml_Error_Message::mo_saml_display_test_config_error(Mo_Saml_Error_Codes::$error_codes["\x57\x50\123\101\x4d\114\105\122\122\x30\x30\66"], $bm, $l0);
    goto uu2;
    AyJ:
    mo_saml_handle_azureb2c_cases($Yj, $l0, $XE);
    uu2:
}
function mo_saml_handle_azureb2c_cases($Yj, $l0, $XE)
{
    switch ($l0) {
        case mo_options_plugin_azureb2c_statusmsg::Forgot:
            mo_saml_paswd_reset_url($XE, $Yj);
            goto MYT;
        case mo_options_plugin_azureb2c_statusmsg::Cancel:
            mo_saml_azureb2c_cancel_msg_case($Yj);
        case mo_options_plugin_azureb2c_statusmsg::Largeurl:
            mo_saml_paswd_reset_large_url_case($Yj);
            exit;
        default:
            if (!($Yj != "\x74\145\163\x74\126\141\x6c\x69\144\x61\x74\x65")) {
                goto P9E;
            }
            Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\x57\120\123\101\115\114\x45\x52\x52\60\x30\66"]);
            P9E:
    }
    wgX:
    MYT:
}
function mo_saml_paswd_reset_url($XE, $Yj)
{
    $G8 = $XE;
    $kh = !empty($G8["\x73\x61\155\154\x5f\160\167\x5f\162\x65\163\x65\164\x5f\165\162\x6c"]) ? html_entity_decode($G8["\x73\x61\x6d\154\x5f\x70\167\137\162\145\x73\145\x74\137\165\162\x6c"]) : '';
    if (!empty($kh)) {
        goto lEo;
    }
    if ($Yj != "\x74\x65\x73\164\126\x61\x6c\x69\144\141\x74\x65" && $Yj != "\164\x65\163\164\x4e\x65\x77\103\145\162\164\151\146\x69\143\141\164\145") {
        goto cl9;
    }
    goto WrR;
    lEo:
    wp_redirect($kh);
    exit;
    goto WrR;
    cl9:
    Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\x57\120\123\101\x4d\114\105\x52\x52\60\63\65"]);
    WrR:
}
function mo_saml_paswd_reset_large_url_case($Yj)
{
    if (!($Yj != "\164\x65\x73\x74\126\141\x6c\x69\144\141\164\145")) {
        goto kA6;
    }
    $Lb = parse_url($Yj);
    $Yj = $Lb["\160\x61\164\x68"];
    wp_redirect($Yj);
    kA6:
}
function mo_saml_azureb2c_cancel_msg_case($Yj)
{
    if ($Yj == "\x74\145\163\x74\x56\141\x6c\151\144\x61\164\x65") {
        goto Mst;
    }
    wp_redirect($Yj);
    exit;
    goto yPJ;
    Mst:
    wp_redirect(home_url());
    exit;
    yPJ:
}
function addLink($Uf, $WD)
{
    $X4 = "\74\x61\40\150\x72\x65\x66\75\x22" . $WD . "\42\76" . $Uf . "\x3c\57\x61\x3e";
    return $X4;
}
function get_status_message($vQ)
{
    switch ($vQ) {
        case "\x52\x65\x71\165\x65\x73\x74\145\162":
            return "\124\150\145\40\162\145\x71\165\x65\x73\x74\40\x63\x6f\165\x6c\x64\40\156\157\164\x20\x62\145\x20\x70\x65\162\x66\157\x72\155\145\x64\x20\144\x75\145\40\x74\157\40\141\x6e\40\145\x72\162\157\x72\x20\x6f\x6e\x20\x74\x68\x65\40\x70\141\162\164\x20\157\146\40\164\150\x65\x20\162\x65\x71\x75\145\163\x74\145\x72\56";
            goto o2a;
        case "\122\x65\163\160\157\x6e\144\145\162":
            return "\124\150\145\x20\x72\x65\x71\x75\x65\163\164\x20\143\x6f\x75\154\x64\x20\156\x6f\x74\x20\x62\x65\40\x70\x65\x72\x66\157\162\155\x65\x64\40\x64\165\145\x20\164\157\40\141\x6e\x20\145\162\x72\x6f\x72\x20\x6f\156\x20\164\150\145\40\160\141\162\x74\40\157\x66\40\x74\x68\x65\40\x53\x41\x4d\x4c\x20\162\x65\x73\x70\x6f\156\144\145\x72\40\x6f\x72\x20\123\x41\x4d\114\40\141\x75\x74\150\157\x72\151\164\171\56";
            goto o2a;
        case "\x56\145\x72\163\151\157\156\x4d\151\x73\155\x61\164\x63\150":
            return "\x54\x68\145\x20\x53\101\x4d\114\40\162\x65\x73\160\x6f\x6e\144\145\x72\40\143\157\x75\x6c\x64\40\156\157\164\x20\160\162\x6f\143\145\163\x73\40\164\150\x65\x20\x72\x65\x71\x75\145\x73\164\x20\x62\145\x63\141\x75\163\x65\40\x74\150\145\x20\x76\145\x72\x73\x69\x6f\156\40\x6f\146\x20\164\x68\x65\40\162\145\161\165\145\163\x74\x20\155\145\163\163\141\147\x65\x20\167\141\x73\40\x69\156\x63\157\x72\x72\x65\143\x74\x2e";
            goto o2a;
        default:
            return "\x55\156\x6b\156\x6f\167\156";
    }
    Ljc:
    o2a:
}
function is_administrator_user($user)
{
    $uF = $user->roles;
    if (!is_null($uF) && SAMLSPUtilities::mo_saml_in_array("\141\144\155\x69\x6e\151\x73\164\162\141\x74\157\x72", $uF)) {
        goto XAb;
    }
    return false;
    goto ERs;
    XAb:
    return true;
    ERs:
}
function mo_saml_is_customer_registered()
{
    $RY = get_option("\155\157\x5f\163\x61\x6d\154\x5f\141\x64\155\x69\x6e\137\x65\155\141\x69\x6c");
    $oP = get_option("\x6d\157\137\163\x61\x6d\154\137\141\x64\x6d\x69\156\137\x63\165\x73\x74\x6f\x6d\x65\162\137\x6b\145\x79");
    if (!$RY || !$oP || !is_numeric(trim($oP))) {
        goto azA;
    }
    return 1;
    goto WnY;
    azA:
    return 0;
    WnY:
}
function saml_get_current_page_url()
{
    $V8 = $_SERVER["\110\x54\x54\x50\x5f\110\117\123\x54"];
    if (!(substr($V8, -1) == "\57")) {
        goto CcX;
    }
    $V8 = substr($V8, 0, -1);
    CcX:
    $Rh = $_SERVER["\x52\x45\x51\125\x45\x53\124\137\125\122\x49"];
    if (!(substr($Rh, 0, 1) == "\57")) {
        goto Wk2;
    }
    $Rh = substr($Rh, 1);
    Wk2:
    $bA = !empty($_SERVER["\110\x54\124\x50\123"]) && strcasecmp($_SERVER["\x48\124\124\x50\x53"], "\157\x6e") == 0;
    $Rn = "\150\164\x74\x70" . ($bA ? "\163" : '') . "\x3a\57\x2f" . $V8 . "\57" . $Rh;
    return $Rn;
}
add_action("\x77\x69\144\x67\145\x74\163\137\151\156\151\164", function () {
    register_widget("\115\x6f\x5f\123\101\x4d\x4c\137\114\157\x67\151\x6e\137\127\x69\144\147\x65\164");
});
add_action("\x69\x6e\151\164", array(Mo_Saml_User_Login_Handler::mo_saml_get_object(), "\155\x6f\137\x73\141\155\154\x5f\154\x6f\x67\151\156\137\x76\141\x6c\151\x64\141\164\145"));
