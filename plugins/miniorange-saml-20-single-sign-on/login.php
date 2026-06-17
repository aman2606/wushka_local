<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */

/*
 * Plugin Name: miniOrange SAML SSO Multiple IDP
 * Plugin URI: https://miniorange.com/
 * Description: (Single Site Multiple IdP)miniOrange SAML 2.0 SSO enables user to perform Single Sign On with any SAML 2.0 enabled Identity Provider.
 * Version: 25.3.0
 * Author: miniOrange
 * Author URI: https://miniorange.com/
 * License: miniOrange
 * License URI: https://miniorange.com/usecases/miniOrange_User_Agreement.pdf
 */


define("\115\x4f\137\123\101\x4d\114\x5f\120\114\125\x47\x49\x4e\137\104\x49\122", __DIR__);
define("\x4d\x4f\x5f\x53\x41\115\114\x5f\117\x50\124\111\x4f\x4e\x53\137\105\x4e\x55\115", "\57\x69\x6e\x63\x6c\x75\144\145\x73\x2f\x6c\x69\142\57\x6d\157\55\x6f\160\164\151\157\156\163\x2d\145\x6e\x75\x6d\x2e\160\150\160");
require_once MO_SAML_PLUGIN_DIR . MO_SAML_OPTIONS_ENUM;
require Mo_Saml_Plugin_Files::MO_SAML_CLASS_CUSTOMER;
require Mo_Saml_Plugin_Files::MO_SAML_SETTINGS_PAGE;
require Mo_Saml_Plugin_Files::MO_SAML_METADATA_READER;
require_once Mo_Saml_Plugin_Files::MO_SAML_PLUGIN_VERSION_UPDATE;
require_once Mo_Saml_Plugin_Files::MO_SAML_ENVIRONMENT_UTILS;
require_once Mo_Saml_Plugin_Files::MO_SAML_ENVIRONMENT_DAO;
require_once Mo_Saml_Plugin_Files::MO_SAML_SSO_WIDGET;
require_once Mo_Saml_Plugin_Files::MO_SAML_LICENSE_LIB_AUTOLOADER;
require_once Mo_Saml_Plugin_Files::MO_SAML_XML_SEC_LIBS;
require_once Mo_Saml_Plugin_Files::SSO_USER_TABLE_SECTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_USER_LOGOUT_HANDLER;
require_once Mo_Saml_Plugin_Files::MO_SAML_METADATA_IMPORT_HANDLER;
require_once Mo_Saml_Plugin_Files::MO_SAML_REDIRECTION_SSO_HANDLER;
require_once Mo_Saml_Plugin_Files::MO_SAML_LICENSE_HANDLER;
require_once Mo_Saml_Plugin_Files::MO_SAML_COMMON_INTEGRATOR_CLASS;
require_once Mo_Saml_Plugin_Files::MO_SAML_HIDE_WP_LOGIN_HANDLER;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecEnc;
use MOSAML\LicenseLibrary\Classes\Mo_License_Library;
use MOSAML\LicenseLibrary\Mo_License_Service;
class Mo_SAML_Plugin
{
    private $mo_saml_user_logout_handler;
    private static $instance;
    public static function mo_saml_get_object()
    {
        if (isset(self::$instance)) {
            goto Bk;
        }
        $A4 = __CLASS__;
        self::$instance = new $A4();
        Bk:
        return self::$instance;
    }
    function __construct()
    {
        if (!mo_saml_is_extension_installed("\x6f\x70\x65\156\x73\x73\x6c")) {
            goto RW;
        }
        new Mo_License_Library();
        RW:
        add_action("\141\144\x6d\151\x6e\x5f\x6d\x65\156\165", array($this, "\155\x69\156\x69\x6f\162\141\156\147\145\x5f\163\163\x6f\137\155\145\156\165"));
        add_action("\x61\x64\x6d\151\x6e\x5f\151\156\x69\164", array($this, "\155\157\137\163\141\155\x6c\x5f\141\x64\155\x69\156\137\151\x6e\x69\x74\x5f\x61\x63\164\151\157\156\163"));
        add_action("\141\x64\155\151\156\x5f\145\x6e\161\165\145\165\145\x5f\163\143\162\151\x70\x74\163", array($this, "\x70\154\x75\147\151\156\x5f\163\145\x74\164\x69\156\147\x73\x5f\x73\x74\171\154\x65"));
        register_activation_hook(__FILE__, array($this, "\155\157\x5f\x73\x73\157\137\163\141\x6d\x6c\x5f\x61\x63\164\x69\x76\141\164\x65"));
        register_deactivation_hook(__FILE__, array($this, "\x6d\x6f\137\163\x73\x6f\x5f\163\141\x6d\154\x5f\144\x65\x61\x63\x74\x69\x76\x61\x74\x65"));
        add_action("\x61\x64\155\x69\156\137\x65\156\161\x75\145\165\145\137\x73\143\162\151\160\164\163", array($this, "\x70\154\x75\147\x69\156\x5f\163\145\x74\x74\x69\156\147\x73\x5f\x73\143\x72\151\x70\x74"));
        remove_action("\x61\144\x6d\x69\x6e\x5f\x6e\x6f\x74\151\143\145\x73", array($this, "\155\x6f\x5f\163\x61\x6d\x6c\137\163\x75\x63\143\x65\163\x73\137\155\x65\163\x73\141\147\x65"));
        remove_action("\x61\144\155\x69\156\137\156\x6f\164\151\x63\x65\163", array($this, "\155\x6f\137\x73\x61\155\x6c\x5f\145\x72\162\157\162\137\155\x65\x73\163\x61\147\145"));
        add_filter("\x61\165\x74\x68\145\156\164\151\x63\141\x74\145", array($this, "\x6d\157\137\163\x61\155\154\x5f\141\165\164\x68\145\x6e\x74\x69\143\141\164\x65"), 30, 3);
        add_filter("\x73\145\164\55\163\143\x72\145\x65\x6e\55\x6f\x70\x74\151\157\x6e", array("\123\x41\x4d\x4c\x53\x50\x55\164\x69\154\x69\164\151\x65\x73", "\x6d\x6f\x5f\x73\x61\x6d\x6c\x5f\x73\x61\166\145\137\143\165\x73\164\x6f\x6d\137\163\x63\162\x65\x65\x6e\137\157\160\164\151\x6f\156\x73"), 10, 3);
        add_action("\167\160\x5f\x61\x75\x74\x68\145\x6e\164\151\143\x61\164\145", array($this, "\x6d\157\137\x73\141\x6d\154\137\162\145\x64\151\x72\145\x63\164\x5f\164\157\137\x69\144\160\x5f\x6c\151\163\x74\x5f\x70\141\x67\145\137\x66\162\157\155\x5f\154\x6f\147\x69\156\137\160\141\147\x65"));
        add_action("\167\x70", array($this, "\x6d\x6f\x5f\163\x61\155\x6c\x5f\x61\165\164\157\137\x72\x65\x64\151\162\x65\x63\x74"));
        $Fb = Mo_SAML_Login_Widget::mo_saml_get_object();
        add_action("\x69\156\x69\x74", array($Fb, "\x6d\157\137\x73\141\155\x6c\137\167\x69\x64\147\145\164\x5f\x69\x6e\x69\164"));
        add_action("\x6c\157\x67\x69\x6e\137\146\157\162\x6d", array($this, "\155\x6f\x5f\x73\x61\155\154\137\x6d\157\144\x69\146\171\x5f\154\157\x67\x69\156\x5f\x66\x6f\162\155"), 10, 1);
        add_action("\x6c\x6f\147\151\156\x5f\x66\157\162\x6d", array($this, "\155\x6f\x5f\x73\141\155\x6c\137\x61\144\144\137\x6c\x6f\147\151\156\x5f\154\x69\x6e\153\x73"), 15, 1);
        add_shortcode("\115\117\x5f\x53\x41\115\x4c\137\114\x4f\x47\x49\x4e", array($this, "\155\x6f\137\x73\141\155\154\137\147\145\x74\x5f\151\144\x70\137\x73\150\x6f\162\x74\143\x6f\x64\145"));
        add_shortcode("\x4d\117\x5f\123\101\x4d\x4c\137\111\x44\120\137\114\x49\x53\124", array($this, "\155\157\x5f\147\x65\x74\x5f\163\x61\x6d\154\137\x69\x64\x70\137\x6c\x69\163\x74\x5f\163\150\x6f\162\164\143\157\144\x65"));
        add_shortcode("\x4d\x4f\x5f\x53\x41\x4d\114\137\x46\117\x52\115", array($this, "\155\x6f\x5f\163\141\x6d\154\x5f\147\x65\x74\x5f\151\x64\160\x5f\163\x68\x6f\x72\x74\x63\157\144\x65"));
        add_action("\x69\x6e\151\x74", array($this, "\155\x6f\137\x73\x61\155\x6c\137\151\156\151\164\137\x6c\x6f\147\151\x6e\137\146\157\162\x6d"));
        add_action("\151\156\x69\x74", array($this, "\155\x6f\137\x73\x61\155\154\x5f\x69\156\x69\x74\137\x77\x70\137\x63\154\x69"));
        add_action("\x6c\x6f\x67\151\x6e\137\x66\157\x6f\164\145\x72", array($this, "\x6d\157\x5f\x73\141\x6d\x6c\x5f\x66\157\157\164\x65\162\137\146\157\162\155"));
        add_action("\154\x6f\x67\151\156\x5f\x65\x6e\161\x75\145\165\145\x5f\163\143\162\151\x70\164\163", array($this, "\x6d\x6f\x5f\163\x61\155\x6c\137\x6a\x71\165\x65\x72\171\x5f\x64\x65\146\x61\x75\x6c\164\x5f\x6c\157\147\x69\156"));
        add_filter("\x63\162\157\156\x5f\x73\143\150\145\x64\x75\x6c\145\x73", array($this, "\x6d\171\160\x72\x65\x66\151\x78\137\141\144\x64\137\x63\162\x6f\156\137\x73\x63\x68\x65\x64\165\154\145"));
        add_action("\x6d\145\x74\x61\144\x61\164\x61\137\163\171\x6e\x63\x5f\x63\162\x6f\156\137\141\x63\x74\x69\x6f\x6e", array($this, "\x6d\145\x74\141\x64\141\x74\x61\137\x73\171\x6e\143\x5f\143\x72\x6f\x6e\x5f\x61\x63\x74\x69\x6f\x6e"), 10, 1);
        add_action("\x70\154\x75\147\151\156\137\141\x63\164\151\157\x6e\137\x6c\x69\x6e\x6b\163\137" . plugin_basename(__FILE__), array($this, "\155\x6f\137\163\141\155\x6c\137\160\154\165\147\x69\156\x5f\x61\143\164\x69\x6f\x6e\x5f\154\151\x6e\153\163"));
        add_filter("\155\141\x6e\x61\x67\145\x5f\165\x73\x65\x72\163\x5f\143\157\x6c\x75\155\x6e\x73", array($this, "\155\x6f\137\x73\x61\x6d\x6c\x5f\x63\165\x73\x74\157\x6d\x5f\x61\164\164\162\x5f\x63\x6f\x6c\165\155\x6e"));
        add_filter("\155\x61\156\141\147\x65\x5f\x75\163\x65\x72\163\x5f\x63\165\163\x74\x6f\155\137\x63\157\x6c\165\155\x6e", array($this, "\x6d\157\x5f\x73\141\x6d\x6c\x5f\x61\x74\164\162\137\x63\157\154\x75\x6d\x6e\x5f\143\157\156\164\x65\156\164"), 1, 3);
        add_action("\167\160\x5f\x61\x6a\141\x78\x5f\155\x6f\x5f\x73\141\155\x6c\x5f\163\x79\156\x63\x5f\154\x69\x63\145\156\163\x65\137\157\x6e\x5f\145\x78\160\x69\162\171", array($this, "\x6d\x6f\137\163\x61\x6d\x6c\137\x73\171\156\x63\x5f\154\151\143\145\x6e\x73\x65\x5f\143\141\x6c\x6c\x62\x61\143\x6b"), 1, 3);
        Mo_Saml_Hide_WP_Login_Handler::mo_saml_get_object();
        global $wp_version;
        $this->mo_saml_user_logout_handler = Mo_Saml_User_Logout_Handler::mo_saml_get_object();
        if ((float) $wp_version < 5.5 && (float) $wp_version > 5.2) {
            goto Xq;
        }
        add_action("\x77\x70\137\x6c\x6f\147\x6f\165\x74", array($this->mo_saml_user_logout_handler, "\x6d\x6f\137\x73\141\155\x6c\137\x6c\x6f\147\x6f\165\164\137\x75\163\x65\x72"), 1, 1);
        goto FG;
        Xq:
        add_filter("\x6c\157\x67\157\x75\x74\137\162\145\x64\x69\162\x65\x63\164", array($this, "\155\x6f\137\x73\x61\155\154\137\154\157\x67\157\x75\164\x5f\142\162\157\x6b\x65\x72\137\167\151\164\x68\137\146\x69\154\164\x65\162"), 10, 3);
        FG:
        add_filter("\154\x6f\x67\x69\156\137\145\162\x72\157\x72\163", array($this, "\x6d\x6f\x5f\163\x61\x6d\x6c\137\x63\165\163\x74\157\x6d\x5f\154\157\147\151\x6e\137\145\162\162\157\x72\x5f\155\145\x73\163\141\x67\x65"));
        add_action("\167\160\137\x61\152\141\x78\137\x6d\157\x5f\x73\141\x6d\154\x5f\143\x68\x61\x6e\x67\145\137\x65\156\x76\151\x72\x6f\x6e\155\x65\156\x74", array($this, "\155\x6f\x5f\163\x61\155\x6c\137\141\x6a\141\x78\x5f\143\141\154\154\x5f\x68\141\x6e\144\154\145\x72"));
        add_action("\151\156\x69\x74", array("\x4d\151\x67\162\x61\164\x65\137\x45\156\x76\151\162\x6f\156\x6d\x65\x6e\164\x5f\x53\145\x74\164\x69\156\147\x73", "\155\157\137\163\141\155\154\x5f\155\151\x67\x72\x61\x74\x65\137\x65\156\166\x69\x72\x6f\156\155\x65\156\x74\137\163\x65\164\x74\x69\x6e\x67\x73"));
        add_action("\141\144\x6d\x69\156\137\156\x6f\x74\151\x63\x65\x73", array($this, "\155\157\x5f\163\141\155\154\137\141\x64\144\x5f\x61\144\x6d\x69\156\x5f\156\157\164\151\143\145\163"));
        add_filter("\163\x61\x66\145\x5f\x73\x74\x79\154\145\137\x63\x73\163", array($this, "\x6d\157\137\x73\141\155\154\x5f\154\x6f\141\144\137\163\x61\x66\x65\137\143\x73\x73"));
        add_filter("\155\x6f\x5f\x73\141\x6d\154\137\163\x61\156\x69\x74\151\x7a\145\137\141\164\164\162\151\142\x75\164\x65\x73", array("\x53\x41\115\x4c\x53\x50\125\x74\x69\x6c\x69\164\151\145\163", "\x6d\x6f\x5f\163\141\155\x6c\x5f\163\x61\156\151\164\151\172\145\x5f\163\141\155\x6c\x5f\141\164\164\162\163"), 10);
        Mo_SAML_Integrator_Addon::mo_saml_register_addon_hooks();
    }
    function mo_saml_load_safe_css($Yb)
    {
        $Yb[] = "\x64\x69\163\160\154\x61\x79";
        return $Yb;
    }
    public function mo_saml_add_admin_notices()
    {
        $this->mo_saml_add_idp_specific_notice();
    }
    function mo_saml_sync_license_callback()
    {
        if (current_user_can("\x6d\141\156\141\147\145\137\157\160\x74\x69\157\x6e\163")) {
            goto m6;
        }
        wp_send_json_error([]);
        return;
        m6:
        check_admin_referer("\155\157\137\163\141\155\x6c\x5f\163\171\156\x63\x5f\x6c\x69\x63\145\156\163\x65\137\141\x6a\x61\170\137\x6e\x6f\x6e\x63\x65", "\156\157\x6e\x63\x65");
        Mo_License_Service::refresh_license_expiry();
        $vE = Mo_License_Service::get_formatted_license_expiry_date(Mo_License_Service::get_expiry_date());
        $Hn = Mo_License_Service::get_expiry_remaining_days($vE);
        $d4 = array("\155\145\x73\x73\x61\x67\x65" => "\x4c\x69\143\x65\x6e\x73\145\x20\x73\x79\x6e\143\x65\144\40\x73\x75\143\x63\x65\x73\163\x66\165\x6c\154\x79\x21", "\x6c\x61\x73\x74\x5f\x73\171\x6e\x63\145\x64" => gmdate("\x4d\x20\x64\x2c\40\131\x20\x48\x3a\x69\x3a\x73"), "\162\145\155\141\x69\x6e\x69\x6e\147\137\x64\x61\171\163" => $Hn, "\x65\x78\160\151\162\x79\x5f\x64\x61\x74\145" => $vE);
        wp_send_json_success($d4);
    }
    public function mo_saml_add_idp_specific_notice()
    {
        if (!(empty(get_option("\155\x6f\137\163\141\x6d\154\137\x6e\157\164\151\143\x65\x5f\x74\157\x5f\x64\x69\163\x70\x6c\141\x79")) && Mo_License_Service::is_customer_license_valid())) {
            goto yw;
        }
        $rK = EnvironmentHelper::getIdpOfAllEnviornment();
        SAMLSPUtilities::mo_saml_update_selected_idp($rK);
        yw:
        $Ia = get_option("\x6d\157\137\x73\x61\x6d\x6c\x5f\x6e\157\164\x69\x63\x65\x5f\164\x6f\137\144\x69\163\x70\x6c\x61\171") ? get_option("\x6d\x6f\137\x73\x61\x6d\x6c\137\x6e\157\x74\x69\143\145\x5f\164\x6f\x5f\144\x69\163\x70\x6c\x61\171") : array();
        if (!current_user_can("\155\141\156\x61\x67\145\137\x6f\160\x74\151\157\156\x73")) {
            goto Wt;
        }
        $me = "\62\x72\145\x6d";
        foreach ($Ia as $R2 => $EB) {
            if (!(true === $EB)) {
                goto Z2;
            }
            mo_saml_display_plugin_notice($R2, $me);
            $me = "\x31\162\x65\x6d";
            Z2:
            NN:
        }
        lz:
        Wt:
    }
    public function mo_saml_do_plugin_extension_checks()
    {
        $Pp = Mo_Saml_Plugin_Pages::PLUGIN_PAGES;
        if (!(!(!empty($_GET["\160\141\x67\145"]) && in_array($_GET["\x70\x61\147\x65"], $Pp)) && current_user_can("\155\141\156\x61\x67\145\x5f\157\x70\x74\x69\157\156\163"))) {
            goto k0;
        }
        add_action("\x61\x64\x6d\151\x6e\137\x6e\x6f\x74\151\x63\x65\x73", array($this, "\x73\150\157\167\x5f\144\151\x73\141\142\x6c\145\144\x5f\145\x78\164\145\x6e\x73\151\x6f\x6e\137\156\x6f\x74\x69\x63\145"));
        k0:
    }
    function mo_saml_ajax_call_handler()
    {
        if (!(current_user_can("\x6d\x61\x6e\x61\x67\x65\x5f\x6f\x70\x74\x69\157\x6e\163") && Mo_License_Service::is_customer_license_valid())) {
            goto ym;
        }
        update_option("\x6d\x6f\x5f\x73\141\x6d\x6c\137\163\145\x6c\145\x63\164\x65\x64\x5f\x65\156\166\x69\x72\x6f\156\155\145\x6e\164", EnvironmentHelper::getCurrentEnvironment());
        wp_send_json_success();
        ym:
        wp_send_json_error();
    }
    function mo_saml_custom_login_error_message($e1)
    {
        global $errors;
        $gC = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\137\163\x61\x6d\154\x5f\x65\x6e\x61\x62\154\x65\137\x64\157\155\141\x69\x6e\137\x6d\x61\160\160\x69\156\x67", false, EnvironmentHelper::getCurrentEnvironment());
        if (!("\164\162\165\x65" === $gC && !empty($errors) && (SAMLSPUtilities::mo_saml_in_array("\x69\156\166\141\154\x69\x64\x5f\145\155\141\x69\x6c", $errors->get_error_codes()) || SAMLSPUtilities::mo_saml_in_array("\151\x6e\x63\157\x72\162\145\x63\164\137\x70\x61\163\x73\x77\157\x72\x64", $errors->get_error_codes())))) {
            goto g0;
        }
        $e1 = "\40\x54\x68\x65\40\x65\x6e\x74\145\162\145\x64\x20\145\x6d\x61\x69\154\40\x61\x64\144\x72\x65\163\x73\x20\x6f\162\40\160\141\x73\x73\x77\x6f\162\x64\x20\x69\163\40\x69\156\x63\157\x72\x72\145\x63\x74\x2e\x20\120\154\145\x61\163\145\x20\145\x6e\x74\145\x72\40\x61\40\166\141\154\151\x64\x20\x65\x6d\141\x69\x6c\40\x61\x64\x64\x72\x65\163\163\x20\x6f\162\x20\160\x61\163\163\x77\x6f\x72\x64\x2e";
        g0:
        return $e1;
    }
    function mo_saml_logout_broker_with_filter($V9, $Ms, $user)
    {
        $this->mo_saml_user_logout_handler->mo_saml_logout_user($user->ID, $V9);
    }
    function mo_sso_saml_activate()
    {
        $this->mo_saml_sso_fetch_exisitng_configuration();
        if (!(get_option("\155\157\137\163\x61\155\154\x5f\153\145\x65\x70\137\163\145\x74\x74\x69\x6e\x67\163\x5f\157\156\x5f\x64\x65\x6c\145\164\x69\x6f\x6e") == false)) {
            goto u7;
        }
        add_option("\x6d\x6f\x5f\x73\141\x6d\154\137\x6b\145\x65\160\x5f\x73\145\164\164\151\156\x67\163\137\157\x6e\137\144\145\154\145\164\151\x6f\x6e", "\x74\x72\165\x65");
        u7:
    }
    function show_disabled_extension_notice()
    {
        $WZ = SAMLSPUtilities::mo_saml_get_disabled_extensions();
        if (empty($WZ)) {
            goto GR;
        }
        $Rp = implode("\x2c\x20", $WZ);
        echo "\xd\12\11\11\x20\x20\40\40\40\40\40\40\x3c\144\151\166\40\x63\154\141\163\x73\x3d\42\x6e\x6f\x74\x69\143\145\40\x6e\157\x74\x69\x63\145\55\x77\x61\162\156\x69\156\147\x20\x6d\x6f\x5f\163\x61\155\154\137\x74\162\x69\141\x6c\x5f\156\157\164\151\143\x65\x5f\x62\x61\156\x6e\145\x72\42\x3e\15\12\40\40\40\x20\40\x20\40\40\x20\40\40\x20\40\x20\40\40\40\x20\x20\40\74\x64\151\166\x20\x73\164\x79\154\145\x3d\42\144\x69\x73\x70\154\x61\x79\x3a\x20\x66\x6c\145\x78\x3b\x6a\165\163\164\151\146\x79\x2d\x63\157\x6e\x74\x65\156\164\72\40\x6c\145\x66\164\73\155\141\162\147\151\156\x3a\x20\x34\160\170\42\x3e\15\xa\x9\x9\11\11\11\x3c\151\x6d\x67\x20\163\162\x63\75\42" . esc_attr(plugin_dir_url(__FILE__)) . "\x69\x6d\141\x67\145\x73\57\155\x69\156\151\157\x72\141\156\x67\145\55\x6c\157\x67\157\x2e\167\145\x62\160\x22\x20\x77\x69\x64\164\x68\x3d\x22\64\x30\x70\170\42\40\150\x65\151\x67\x68\164\75\x22\x34\x30\160\x78\42\x3e\xd\xa\x9\11\11\11\x9\74\x73\x70\141\156\x20\x73\x74\171\x6c\145\75\x22\x70\141\144\x64\x69\156\x67\x3a\40\66\x70\170\x3b\x22\x3e\74\163\x70\x61\x6e\x20\x73\164\x79\x6c\x65\x3d\x22\x63\x6f\x6c\x6f\162\72\x20\x72\145\x64\73\x66\157\x6e\x74\55\167\x65\x69\147\x68\164\72\40\x62\157\154\144\42\76\x57\x61\x72\156\x69\156\x67\72\x3c\57\163\160\x61\156\76\40\x46\x6f\154\x6c\x6f\x77\151\x6e\147\x20\x50\110\x50\40\145\170\164\145\156\163\151\157\x6e\x73\x20\x28\x3c\x69\40\x73\x74\x79\154\145\x3d\42\146\157\x6e\x74\x2d\167\145\x69\x67\x68\x74\72\x20\142\157\154\144\x22\76" . esc_attr($Rp) . "\x3c\x2f\151\76\x29\40\x61\x72\145\x20\144\151\163\141\x62\x6c\x65\144\40\x77\x68\151\143\x68\40\141\x72\x65\x20\151\155\160\157\x72\164\141\x6e\164\40\146\157\x72\x20\x53\x53\117\x20\x63\x6f\156\x66\151\x67\165\162\141\x74\x69\157\x6e\x2e\x20\x50\154\x65\141\163\145\40\x65\156\x61\142\x6c\x65\x20\164\150\x65\x73\x65\x20\x65\170\x74\x65\156\x73\x69\157\156\163\40\164\x6f\x20\143\157\156\x74\x69\x6e\x75\x65\40\165\x73\151\x6e\147\40\123\123\117\40\157\156\40\171\157\x75\162\x20\163\151\x74\x65\x2e\x3c\x2f\163\160\141\x6e\x3e\74\57\142\x72\x3e\xd\12\40\40\x20\x20\40\x20\x20\40\x20\40\40\x20\40\40\40\x20\40\40\40\x20\x3c\57\144\x69\166\x3e";
        echo "\x3c\57\x73\160\141\156\x3e\74\57\144\151\166\x3e";
        GR:
    }
    function mo_saml_sso_fetch_exisitng_configuration()
    {
        $kq = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\x6d\154\137\x69\144\x65\156\x74\151\x74\x79\x5f\x70\162\157\x76\151\144\x65\x72\x73", true, EnvironmentHelper::getCurrentEnvironment());
        if (!(empty($kq) && !empty(get_option("\x73\x61\155\154\x5f\151\144\x65\x6e\x74\x69\x74\x79\x5f\156\141\155\x65")))) {
            goto hI;
        }
        $this->fetch_existing_saml_idp_config();
        hI:
    }
    function fetch_existing_saml_idp_config()
    {
        $this->mo_saml_fetch_service_provider_setup();
        $Q0 = !empty(get_option("\163\x61\x6d\x6c\137\151\x64\x65\156\164\151\164\171\x5f\156\x61\x6d\x65")) ? get_option("\x73\x61\x6d\x6c\137\151\144\x65\x6e\x74\151\164\171\137\x6e\141\x6d\x65") : '';
        $s6 = new EnvironmentDao(EnvironmentHelper::getCurrentEnvironment());
        $s6->mo_save_environment_settings("\x6d\157\137\x73\x61\x6d\x6c\137\141\x74\164\x72\x5f\162\x6f\154\x65\x5f\163\x65\x6c\145\143\x74\145\x64\137\x69\144\x70", $Q0);
        $this->mo_saml_fetch_attribute_mapping($Q0);
        $this->mo_saml_fetch_custom_attribute_mapping($Q0);
        $this->mo_saml_fetch_advance_restriction($Q0);
        $this->mo_saml_fetch_role_mapping($Q0);
        $this->mo_saml_fetch_existing_redirection_sso_links_settings();
        $this->mo_saml_delete_existing_options();
    }
    function mo_saml_fetch_service_provider_setup()
    {
        $kq = array();
        $PG = array();
        $RF = get_option("\163\x61\155\154\x5f\151\x64\x65\156\x74\151\164\x79\x5f\x6e\x61\x6d\x65");
        $K3 = get_option("\x73\141\155\x6c\x5f\151\144\x65\156\x74\x69\164\171\x5f\x6e\141\155\x65");
        $tX = get_option("\163\x61\155\x6c\x5f\x6c\x6f\147\151\156\137\x75\x72\x6c");
        $uo = get_option("\x73\141\155\x6c\x5f\x69\x73\163\165\145\x72");
        $Rv = !empty(get_option("\155\x6f\x5f\163\141\155\154\137\x65\156\143\157\x64\x69\x6e\147\x5f\145\156\x61\142\154\x65\144")) ? get_option("\x6d\157\137\163\141\155\x6c\x5f\x65\156\x63\x6f\x64\151\x6e\147\137\145\156\x61\x62\154\145\x64") : "\x63\150\145\143\153\x65\x64";
        $Pn = !empty(get_option("\155\157\137\x73\141\x6d\x6c\x5f\x61\163\x73\x65\x72\x74\151\x6f\156\137\x74\x69\155\x65\137\166\141\154\151\144\151\x74\x79")) ? get_option("\x6d\157\137\163\141\x6d\154\137\x61\x73\163\x65\x72\x74\x69\157\x6e\x5f\164\151\155\145\x5f\x76\141\154\151\144\x69\x74\171") : "\143\x68\x65\x63\x6b\x65\144";
        $CF = get_option("\x73\141\155\154\137\x78\65\x30\x39\137\143\x65\x72\164\151\x66\x69\x63\141\x74\x65");
        $Kp = get_option("\x73\141\155\154\137\x6c\157\147\x69\x6e\x5f\142\x69\x6e\144\151\156\147\x5f\x74\x79\x70\145");
        $XM = get_option("\163\141\x6d\154\x5f\x6c\x6f\x67\x6f\x75\164\137\165\x72\154");
        $uN = get_option("\163\x61\x6d\x6c\x5f\x6c\x6f\x67\157\x75\x74\x5f\x62\151\x6e\x64\151\x6e\x67\x5f\x74\171\x70\145");
        $nq = get_option("\x73\141\155\154\137\156\141\x6d\145\x69\x64\137\x66\x6f\162\x6d\x61\164");
        $ML = !empty(get_option("\x73\141\x6d\x6c\137\x72\x65\x71\165\x65\x73\164\x5f\x73\151\147\x6e\x65\144")) ? get_option("\x73\x61\x6d\x6c\137\162\145\x71\x75\145\163\x74\137\163\151\147\156\x65\x64") : "\x75\x6e\143\x68\145\143\153\145\x64";
        $EH = "\x59\x65\163";
        $td = "\131\145\x73";
        $kq[$RF] = array("\x69\x64\x70\137\x6e\x61\155\x65" => $RF, "\151\x64\160\137\x64\151\x73\160\x6c\x61\171\137\x6e\141\x6d\x65" => $K3, "\x69\144\x70\x5f\x65\156\x74\x69\x74\x79\137\151\144" => $uo, "\x73\x73\157\x5f\x75\x72\x6c" => $tX, "\163\163\x6f\x5f\x62\151\156\144\x69\156\x67\x5f\x74\x79\160\x65" => $Kp, "\x73\x6c\x6f\x5f\165\x72\154" => $XM, "\x73\154\157\x5f\x62\151\156\144\x69\156\x67\137\x74\x79\160\145" => $uN, "\x78\x35\60\71\137\143\145\x72\x74\x69\x66\x69\x63\141\164\145" => $CF, "\x72\145\x73\160\x6f\x6e\x73\145\137\163\x69\147\156\x65\x64" => $EH, "\x61\163\163\x65\x72\164\x69\157\156\137\163\151\x67\x6e\145\x64" => $td, "\162\145\x71\x75\x65\163\x74\x5f\x73\x69\147\156\145\x64" => $ML, "\156\141\x6d\x65\x69\144\137\x66\x6f\x72\x6d\141\164" => $nq, "\155\x6f\137\x73\141\x6d\154\137\145\156\x63\x6f\144\151\156\x67\x5f\145\x6e\x61\x62\x6c\145\144" => $Rv, "\x6d\157\x5f\163\141\155\x6c\137\141\x73\163\x65\162\x74\151\x6f\156\x5f\x74\x69\x6d\x65\137\x76\141\x6c\x69\x64\x69\x74\171" => $Pn, "\x65\x6e\141\142\154\145\137\x69\144\160" => true);
        $PG[$K3] = $RF;
        if (empty($kq[''])) {
            goto hk;
        }
        unset($kq['']);
        hk:
        $s6 = new EnvironmentDao(EnvironmentHelper::getCurrentEnvironment());
        $s6->mo_save_environment_settings("\163\x61\155\x6c\137\x69\x64\x65\156\x74\x69\164\x79\x5f\x70\162\x6f\x76\151\144\145\162\x73", $kq);
        $s6->mo_save_environment_settings("\163\x61\x6d\154\x5f\144\x65\x66\141\x75\x6c\x74\137\x69\x64\x70", $RF);
        $s6->mo_save_environment_settings("\155\157\x5f\x73\141\x6d\x6c\x5f\151\144\x70\137\156\141\155\x65\137\151\144\137\155\x61\x70", $PG);
    }
    function mo_saml_fetch_attribute_mapping($Q0)
    {
        $El = !empty(get_option("\x73\x61\x6d\x6c\x5f\x61\x6d\137\x75\163\x65\x72\156\x61\x6d\x65")) ? get_option("\163\141\155\x6c\x5f\141\x6d\137\165\x73\145\x72\x6e\x61\x6d\x65") : '';
        $Gn = !empty(get_option("\163\141\x6d\154\137\x61\155\137\145\155\x61\151\x6c")) ? get_option("\163\141\155\x6c\137\x61\x6d\137\145\x6d\141\151\x6c") : '';
        $Sa = !empty(get_option("\163\141\x6d\x6c\137\x61\155\137\x66\151\x72\163\164\137\x6e\141\155\x65")) ? get_option("\x73\141\155\x6c\x5f\141\x6d\137\146\x69\x72\163\x74\137\156\x61\155\145") : '';
        $uv = !empty(get_option("\163\x61\155\x6c\137\141\x6d\137\x6c\x61\x73\164\x5f\x6e\141\x6d\x65")) ? get_option("\x73\141\x6d\154\x5f\x61\155\x5f\154\141\x73\x74\x5f\x6e\141\155\x65") : '';
        $eh = !empty(get_option("\163\x61\155\x6c\137\141\x6d\137\156\x69\143\x6b\x6e\x61\x6d\145")) ? get_option("\163\141\x6d\x6c\x5f\x61\155\x5f\156\x69\143\x6b\156\x61\155\145") : '';
        $k1 = !empty(get_option("\163\141\x6d\x6c\x5f\141\155\137\x64\x69\163\x70\154\x61\171\x5f\x6e\x61\x6d\145")) ? get_option("\163\141\x6d\x6c\x5f\x61\155\x5f\144\151\x73\x70\x6c\x61\x79\x5f\x6e\141\x6d\x65") : '';
        $PO = !empty(get_option("\x73\x61\x6d\x6c\137\141\x6d\137\165\x70\144\141\x74\145\137\x64\x69\x73\160\x6c\x61\171\137\x6e\141\x6d\145")) ? "\x63\150\145\143\153\145\144" : '';
        $ey[$Q0] = array("\x75\163\x65\162\x6e\141\x6d\145" => $El, "\145\155\141\x69\x6c" => $Gn, "\x66\151\x72\163\164\137\x6e\x61\x6d\145" => $Sa, "\x6c\141\163\x74\137\x6e\x61\x6d\x65" => $uv, "\x6e\x69\143\x6b\x5f\x6e\x61\155\x65" => $eh, "\144\151\163\x70\154\x61\171\137\156\141\155\x65" => $k1, "\x64\157\x5f\x6e\157\164\137\165\160\144\141\x74\145\137\x64\151\x73\x70\x6c\x61\171\x5f\156\141\155\x65" => $PO);
        $o1 = !empty(get_option("\x6d\157\x5f\x73\141\155\154\x5f\x74\145\x73\x74\137\143\x6f\156\x66\x69\x67\137\141\x74\x74\x72\163")) ? get_option("\155\x6f\x5f\x73\x61\x6d\154\137\x74\x65\163\164\137\143\x6f\156\x66\151\x67\137\141\x74\x74\162\x73") : '';
        $o1 = maybe_unserialize($o1);
        $Ct[$Q0] = $o1;
        $s6 = new EnvironmentDao(EnvironmentHelper::getCurrentEnvironment());
        $s6->mo_save_environment_settings("\x6d\x6f\137\163\141\155\x6c\137\x61\x74\164\162\x69\x62\165\x74\145\137\155\141\x70\160\151\156\147", $ey);
        $s6->mo_save_environment_settings("\155\157\x5f\x73\141\x6d\154\137\164\145\163\164\x5f\x63\157\x6e\x66\151\x67\137\x61\164\x74\x72\163", $Ct);
    }
    function mo_saml_fetch_custom_attribute_mapping($Q0)
    {
        $Q3 = !empty(get_option("\x6d\x6f\137\163\141\155\x6c\137\x63\165\x73\x74\157\x6d\x5f\141\x74\x74\x72\163\137\155\141\x70\x70\x69\x6e\147")) ? get_option("\155\x6f\x5f\x73\x61\x6d\154\x5f\x63\x75\x73\x74\157\155\x5f\x61\164\x74\x72\163\x5f\155\x61\x70\x70\x69\x6e\x67") : '';
        $Q3 = maybe_unserialize($Q3);
        $P0 = !empty(get_option("\163\x61\155\x6c\x5f\163\150\x6f\167\137\165\163\145\162\137\x61\x74\164\162\151\142\165\x74\x65")) ? get_option("\x73\141\x6d\154\137\163\150\157\x77\x5f\x75\163\x65\162\x5f\141\164\164\162\151\142\165\x74\x65") : '';
        $P0 = maybe_unserialize($P0);
        $oj[$Q0] = $Q3;
        $oM[$Q0] = $P0;
        $s6 = new EnvironmentDao(EnvironmentHelper::getCurrentEnvironment());
        $s6->mo_save_environment_settings("\x6d\x6f\x5f\163\141\155\x6c\x5f\143\165\x73\x74\x6f\x6d\137\x61\x74\164\162\163\137\x6d\141\160\x70\151\156\x67", $oj);
        $s6->mo_save_environment_settings("\163\x61\155\154\137\141\164\x74\162\x73\137\164\157\x5f\144\151\x73\x70\154\141\x79\137\151\144\160", $oM);
    }
    function mo_saml_fetch_advance_restriction($Q0)
    {
        $LE = !empty(get_option("\155\x6f\x5f\163\141\155\154\137\x65\156\141\142\x6c\x65\137\x64\157\155\141\x69\x6e\x5f\162\145\163\164\x72\151\143\x74\x69\157\x6e\137\x6c\x6f\147\x69\156")) ? get_option("\155\x6f\137\163\x61\155\154\x5f\x65\x6e\141\x62\154\145\x5f\x64\157\x6d\x61\151\156\137\162\145\x73\x74\162\151\143\164\151\157\x6e\x5f\154\157\147\x69\156") : '';
        $zE = !empty(get_option("\x6d\157\137\163\x61\x6d\154\x5f\x61\x6c\x6c\x6f\x77\x5f\x64\x65\156\171\x5f\x75\163\145\x72\x5f\167\151\164\150\137\x64\157\x6d\141\x69\x6e")) ? get_option("\x6d\x6f\x5f\163\141\x6d\154\x5f\x61\154\154\x6f\167\x5f\x64\x65\156\171\137\x75\163\x65\162\x5f\167\151\x74\x68\137\144\157\155\x61\151\x6e") : '';
        $b1 = !empty(get_option("\x73\x61\x6d\x6c\137\x61\155\x5f\145\x6d\141\x69\x6c\x5f\144\157\x6d\x61\151\x6e\x73")) ? get_option("\x73\x61\155\154\137\141\x6d\137\x65\x6d\141\x69\x6c\137\x64\157\x6d\141\151\156\x73") : '';
        $Zj = !empty(get_option("\x6d\x6f\137\163\141\155\154\137\162\145\163\164\x72\151\143\x74\x5f\165\163\145\x72\x73\x5f\x77\x69\164\150\x5f\x67\x72\157\165\160\163")) ? get_option("\155\x6f\137\163\x61\155\x6c\137\162\145\163\164\x72\x69\143\164\137\x75\163\x65\x72\x73\x5f\167\151\x74\x68\137\x67\x72\x6f\165\x70\163") : '';
        $eI = !empty(get_option("\163\x61\155\154\137\x61\x6d\137\x64\157\156\x74\137\141\x6c\154\x6f\167\137\x75\163\145\x72\x5f\x74\157\154\157\x67\x69\156\x5f\x63\162\x65\141\x74\x65\137\167\151\x74\x68\137\147\151\x76\x65\156\x5f\147\162\x6f\165\160\x73")) ? get_option("\x73\141\155\154\x5f\141\155\137\x64\157\x6e\x74\137\141\x6c\154\x6f\167\x5f\165\163\145\x72\137\x74\157\154\157\x67\151\156\137\x63\x72\x65\x61\x74\145\137\x77\x69\x74\x68\x5f\x67\151\x76\x65\156\137\x67\162\157\x75\160\163") : '';
        $VR = !empty(get_option("\x6d\157\137\x73\x61\x6d\154\x5f\x61\164\x74\x72\137\x72\145\163\164\x72\151\143\x74\x69\x6f\x6e")) ? get_option("\155\157\137\163\141\155\154\137\x61\x74\x74\162\x5f\162\x65\163\x74\162\151\143\x74\151\157\x6e") : '';
        $m2 = !empty(get_option("\x6d\157\x5f\x73\141\x6d\154\x5f\x61\154\154\x6f\x77\137\x64\145\156\x79\137\x75\x73\145\x72\x5f\167\151\164\150\x5f\147\162\x6f\x75\160\137\x76\141\x6c\x75\x65\x73")) ? get_option("\x6d\x6f\137\x73\x61\155\x6c\x5f\141\154\154\157\167\137\x64\145\156\171\x5f\x75\x73\145\x72\x5f\167\x69\x74\x68\137\147\162\x6f\165\x70\x5f\166\141\154\x75\x65\x73") : '';
        $n2 = !empty(get_option("\163\x61\155\154\137\x61\x6d\x5f\144\157\x6e\164\x5f\x63\x72\x65\141\x74\x65\x5f\x6e\145\x77\137\x75\163\145\x72")) ? get_option("\x73\x61\155\154\x5f\x61\155\x5f\x64\x6f\156\164\137\x63\x72\145\x61\164\x65\137\156\x65\167\137\165\x73\145\162") : '';
        $Uc = !empty(get_option("\155\x6f\x5f\163\x61\155\x6c\x5f\162\157\154\x65\137\145\156\141\142\x6c\145\137\x72\145\147\x65\x78")) ? get_option("\x6d\x6f\x5f\x73\x61\x6d\154\137\x72\157\154\145\x5f\145\156\x61\142\154\x65\137\162\x65\147\x65\170") : '';
        $hd = !empty(get_option("\x73\141\x6d\154\x5f\x61\x6d\137\x64\x6f\156\164\137\x75\x70\x64\141\164\145\x5f\x65\170\151\163\164\x69\156\147\137\165\163\x65\x72\137\x72\x6f\154\x65")) ? get_option("\x73\x61\x6d\154\x5f\x61\x6d\137\144\157\x6e\164\x5f\165\x70\144\141\x74\x65\x5f\145\x78\151\163\164\151\156\147\x5f\x75\163\145\x72\137\162\157\x6c\145") : '';
        $rL[$Q0] = array("\x61\154\154\x6f\167\137\x64\145\156\x79\x5f\x75\x73\x65\162\x5f\141\164\164\x72\151\x62\x75\164\145" => $eI, "\x72\145\x73\164\x72\151\x63\x74\x65\144\137\141\164\164\162\x69\x62\x75\164\x65\x5f\166\x61\x6c\165\145\x73" => $Zj, "\x61\154\154\x6f\x77\137\144\x65\156\x79\x5f\x61\164\x74\162\x5f\157\x70\164\x69\x6f\156" => $m2, "\x72\145\x73\x74\x72\151\x63\x74\145\144\137\141\x74\x74\x72\x69\142\x75\164\x65" => $VR, "\x64\157\x5f\x6e\x6f\164\x5f\x63\162\x65\x61\x74\x65\137\x6e\x65\x77\x5f\165\x73\x65\162\x73" => $n2, "\x61\154\154\x6f\x77\x5f\x64\145\x6e\x79\137\165\x73\x65\x72\x5f\144\157\x6d\x61\x69\x6e" => $LE, "\x72\145\x73\x74\x72\x69\143\164\145\x64\137\x64\x6f\x6d\141\x69\156\x73" => $b1, "\x61\x6c\x6c\157\x77\137\x64\x65\x6e\171\137\144\157\155\x61\x69\156\137\x6f\160\164\151\x6f\x6e" => $zE, "\x65\156\141\142\154\145\137\x72\145\x67\x65\170" => $Uc, "\153\x65\145\160\137\x65\170\x69\x73\x74\151\156\x67\x5f\x75\163\x65\162\163\x5f\x72\x6f\154\145" => $hd);
        $s6 = new EnvironmentDao(EnvironmentHelper::getCurrentEnvironment());
        $s6->mo_save_environment_settings("\x6d\x6f\137\x73\x61\x6d\x6c\x5f\141\x74\x74\x72\x5f\x72\157\154\145\x5f\x61\144\x76\141\156\143\x65\x64\137\x73\145\164\164\151\x6e\x67\163", $rL);
    }
    function mo_saml_fetch_role_mapping($Q0)
    {
        $AN = !empty(get_option("\x73\141\x6d\x6c\x5f\141\x6d\137\162\157\154\x65\x5f\155\x61\160\160\151\x6e\x67")) ? get_option("\163\x61\155\154\x5f\141\155\137\x72\157\x6c\x65\137\x6d\141\160\160\151\156\x67") : '';
        $AN = maybe_unserialize($AN);
        $CR = !empty(get_option("\x73\x61\x6d\154\137\141\x6d\x5f\x67\x72\x6f\165\160\137\x6e\141\x6d\145")) ? get_option("\163\141\155\x6c\137\141\x6d\x5f\x67\x72\x6f\x75\160\137\x6e\141\155\145") : '';
        $im = !empty(get_option("\163\x61\x6d\154\137\x61\155\137\141\x73\x73\x69\147\156\x5f\144\145\146\x61\165\x6c\164\137\162\157\154\x65")) ? get_option("\163\x61\155\154\137\141\155\137\141\x73\163\x69\x67\x6e\137\x64\145\146\141\x75\x6c\x74\x5f\162\x6f\x6c\145") : '';
        $Rx = !empty(get_option("\155\x6f\x5f\x73\141\x6d\154\137\144\157\156\x74\137\143\x72\145\141\x74\x65\137\x75\x73\x65\x72\137\151\x66\x5f\x72\157\x6c\145\x5f\156\x6f\164\x5f\x6d\141\x70\x70\x65\x64")) ? get_option("\155\x6f\x5f\x73\141\155\154\x5f\144\157\x6e\164\x5f\x63\162\145\x61\164\145\x5f\165\163\x65\x72\x5f\151\146\137\x72\157\x6c\145\137\x6e\x6f\x74\137\x6d\141\160\160\x65\x64") : '';
        $kS = !empty(get_option("\x73\141\x6d\x6c\x5f\x61\155\x5f\x64\x6f\x6e\164\137\141\x6c\154\x6f\x77\137\165\156\x6c\151\x73\x74\145\x64\x5f\x75\x73\145\162\137\162\x6f\154\145")) ? get_option("\x73\x61\x6d\154\137\x61\155\x5f\144\x6f\156\x74\x5f\x61\x6c\x6c\157\x77\x5f\x75\156\154\151\x73\164\x65\x64\x5f\165\x73\x65\x72\137\x72\157\154\145") : '';
        $X0 = !empty(get_option("\163\x61\x6d\154\x5f\x61\155\137\x75\160\144\141\164\x65\137\141\x64\155\151\x6e\x5f\165\163\145\162\163\137\x72\x6f\x6c\145")) ? get_option("\x73\x61\x6d\154\137\141\x6d\137\x75\160\x64\x61\164\145\x5f\141\x64\155\x69\x6e\137\165\x73\145\162\163\x5f\x72\157\x6c\145") : '';
        $gL = !empty(get_option("\163\x61\155\154\137\141\155\137\144\145\146\141\165\x6c\x74\x5f\165\163\x65\x72\137\x72\157\x6c\x65")) ? get_option("\163\141\x6d\x6c\x5f\x61\155\137\144\145\x66\141\x75\x6c\164\137\165\x73\x65\x72\x5f\x72\157\x6c\x65") : '';
        $qm = false;
        $wp_roles = new WP_Roles();
        $NM = $wp_roles->get_names();
        $Of = array();
        foreach ($NM as $UX => $fH) {
            $v1 = !empty($AN[$UX]) ? $AN[$UX] : '';
            if (!(!$qm && !empty($v1))) {
                goto Pq;
            }
            $qm = true;
            Pq:
            $Of[$Q0][$UX] = SAMLSPUtilities::mo_saml_trim_semi_colon_separated_values($v1);
            PE:
        }
        QF:
        if (!empty($Rx)) {
            goto k4;
        }
        if (!empty($kS)) {
            goto kb;
        }
        if (!empty($im) && $qm) {
            goto Yw;
        }
        $FH = '';
        goto Tp;
        k4:
        $FH = "\144\x6f\156\164\137\x63\x72\x65\141\x74\145\x5f\165\163\145\x72\137\141\156\144\x5f\x64\157\156\x74\137\165\160\144\x61\164\x65\x5f\145\x78\x69\x73\164\x69\156\147";
        goto Tp;
        kb:
        $FH = "\141\x73\x73\x69\x67\156\x5f\x6e\157\x6e\145\137\x72\157\154\x65";
        goto Tp;
        Yw:
        $FH = "\x61\163\x73\151\x67\156\x5f\144\145\x66\141\x75\x6c\164\x5f\x72\157\x6c\145";
        Tp:
        $N6[$Q0]["\147\x72\157\x75\160\137\x6e\x61\x6d\x65"] = $CR;
        $N6[$Q0]["\x61\143\x74\151\x6f\x6e\137\x69\146\137\x72\x6f\154\x65\x5f\156\x6f\164\137\141\163\163\151\x67\x6e\145\x64"] = $FH;
        $N6[$Q0]["\144\x65\146\x61\x75\154\x74\x5f\162\x6f\x6c\145"] = $gL;
        $N6[$Q0]["\141\160\160\154\x79\x5f\162\x6f\154\x65\137\x74\157\137\x61\x64\x6d\x69\156"] = $X0;
        $s6 = new EnvironmentDao(EnvironmentHelper::getCurrentEnvironment());
        $s6->mo_save_environment_settings("\155\157\137\163\x61\155\154\x5f\143\x6f\156\146\x69\147\x75\x72\x65\144\137\162\157\x6c\145\x5f\166\x61\154\x75\x65\x73", $Of);
        $s6->mo_save_environment_settings("\155\x6f\137\x73\141\x6d\x6c\x5f\162\x6f\154\145\137\x6d\x61\160\160\x69\x6e\147\x5f\143\157\156\146\151\147\x75\162\x61\x74\x69\x6f\x6e\x73", $N6);
    }
    function mo_saml_fetch_existing_redirection_sso_links_settings()
    {
        $BB = !empty(get_option("\163\141\155\154\137\151\x64\145\x6e\164\151\x74\171\137\156\x61\155\145")) ? get_option("\163\x61\x6d\154\x5f\151\x64\x65\156\164\151\x74\x79\137\156\141\x6d\145") : '';
        $aO = !empty(get_option("\155\157\137\x73\141\155\154\137\162\x65\154\141\x79\137\x73\x74\141\164\145")) ? get_option("\155\x6f\x5f\x73\x61\155\x6c\137\x72\145\154\141\171\137\x73\164\141\164\x65") : '';
        $Ih = !empty(get_option("\x6d\x6f\137\163\141\x6d\154\137\x6c\x6f\x67\157\x75\x74\x5f\x72\145\154\x61\x79\x5f\163\x74\141\x74\x65")) ? get_option("\155\157\137\x73\x61\155\154\137\x6c\157\x67\157\165\164\x5f\x72\x65\x6c\x61\x79\x5f\x73\164\x61\x74\145") : '';
        $jx[$BB] = $aO;
        $gT[$BB] = $Ih;
        $HD["\154\x6f\147\151\x6e\137\x72\x65\x6c\141\171\137\163\x74\141\x74\145"] = $jx;
        $HD["\x6c\157\147\x6f\x75\x74\137\162\145\x6c\x61\x79\x5f\x73\x74\141\x74\x65"] = $gT;
        $mS = !empty(get_option("\155\157\137\163\x61\155\154\137\x72\x65\147\x69\x73\x74\145\162\x65\x64\x5f\157\x6e\154\x79\137\x61\x63\143\x65\x73\x73")) ? get_option("\x6d\157\137\x73\141\155\154\137\162\x65\x67\151\163\164\x65\162\x65\x64\137\x6f\x6e\x6c\x79\x5f\x61\143\x63\x65\x73\163") : '';
        $xQ = !empty(get_option("\x6d\x6f\137\163\141\x6d\154\137\162\145\144\x69\x72\x65\x63\x74\137\164\x6f\137\x77\160\137\154\x6f\147\x69\156")) ? get_option("\155\157\x5f\163\x61\155\154\x5f\162\145\144\x69\162\145\x63\x74\x5f\164\x6f\137\167\160\137\154\157\147\x69\x6e") : '';
        $OM = !empty(get_option("\x6d\157\x5f\x73\141\155\154\137\x61\x64\144\137\163\163\157\137\142\x75\164\164\157\x6e\x5f\x77\160")) ? get_option("\155\157\137\163\x61\155\x6c\x5f\141\x64\x64\137\x73\x73\157\x5f\142\x75\x74\x74\157\x6e\x5f\167\160") : '';
        $s6 = new EnvironmentDao(EnvironmentHelper::getCurrentEnvironment());
        if (empty($OM)) {
            goto hU;
        }
        $Xr = !empty(get_option("\x6d\157\x5f\163\141\155\x6c\x5f\165\163\x65\x5f\142\165\164\164\157\156\x5f\141\163\x5f\163\x68\157\x72\164\143\157\x64\145")) ? get_option("\155\x6f\x5f\x73\141\x6d\x6c\x5f\165\163\145\x5f\142\165\x74\164\x6f\156\137\x61\163\x5f\x73\x68\x6f\x72\x74\143\x6f\144\145") : false;
        $aa = !empty(get_option("\155\x6f\137\x73\x61\x6d\x6c\137\165\x73\145\137\x62\165\164\x74\157\156\137\141\x73\137\x77\151\144\x67\x65\x74")) ? get_option("\155\x6f\x5f\x73\141\155\154\137\x75\163\145\137\142\x75\x74\x74\157\x6e\137\x61\163\x5f\x77\151\x64\x67\145\x74") : false;
        $lJ = !empty(get_option("\155\x6f\x5f\x73\x61\155\x6c\x5f\142\x75\164\164\x6f\156\x5f\x74\x68\x65\x6d\x65")) ? get_option("\155\x6f\x5f\163\141\x6d\154\x5f\142\165\164\164\x6f\156\x5f\164\150\x65\155\x65") : '';
        $FG = !empty(get_option("\155\157\x5f\x73\x61\x6d\x6c\x5f\142\x75\x74\x74\157\156\x5f\x73\x69\172\145")) ? get_option("\x6d\157\x5f\163\x61\155\154\137\142\x75\x74\x74\157\x6e\137\163\x69\x7a\x65") : '';
        $Ny = !empty(get_option("\x6d\x6f\137\x73\x61\155\x6c\137\142\x75\x74\x74\x6f\x6e\137\167\x69\x64\164\x68")) ? get_option("\155\157\x5f\163\141\155\x6c\x5f\x62\165\x74\164\157\x6e\x5f\167\x69\144\x74\x68") : '';
        $PB = !empty(get_option("\155\157\x5f\x73\141\155\x6c\137\142\x75\164\164\157\x6e\x5f\x68\145\x69\147\150\164")) ? get_option("\155\157\x5f\163\x61\155\x6c\x5f\142\165\164\x74\x6f\x6e\137\x68\145\151\x67\150\x74") : '';
        $ym = !empty(get_option("\155\157\x5f\x73\141\155\x6c\137\142\x75\x74\164\x6f\x6e\137\143\x75\162\x76\145")) ? get_option("\155\157\x5f\x73\x61\x6d\x6c\x5f\x62\x75\x74\164\157\x6e\x5f\143\165\x72\x76\145") : '';
        $TT = !empty(get_option("\x6d\x6f\x5f\x73\x61\x6d\x6c\x5f\x62\x75\x74\164\157\156\137\x63\x6f\x6c\157\x72")) ? get_option("\x6d\x6f\x5f\x73\141\x6d\154\x5f\x62\165\164\164\157\x6e\x5f\x63\x6f\154\x6f\162") : '';
        $gW = !empty(get_option("\x6d\157\x5f\163\141\x6d\154\x5f\142\x75\164\164\157\x6e\x5f\164\x65\170\164")) ? get_option("\x6d\x6f\x5f\163\141\x6d\x6c\137\142\x75\x74\x74\x6f\156\137\x74\x65\x78\164") : '';
        $fb = !empty(get_option("\x6d\157\137\x73\x61\x6d\154\x5f\x62\x75\164\164\157\x6e\x5f\150\145\x69\147\150\x74")) ? get_option("\155\x6f\x5f\163\141\155\154\137\142\165\164\x74\x6f\x6e\137\x68\145\151\147\150\x74") : '';
        $Cp = !empty(get_option("\155\x6f\137\163\x61\x6d\x6c\137\146\157\x6e\x74\x5f\x73\x69\172\x65")) ? get_option("\155\x6f\137\x73\141\x6d\x6c\137\146\157\156\x74\137\x73\x69\172\145") : '';
        $SU = !empty(get_option("\163\163\x6f\x5f\142\x75\x74\x74\157\x6e\137\154\x6f\x67\151\156\x5f\146\x6f\x72\x6d\x5f\160\157\x73\151\x74\151\157\156")) ? get_option("\163\163\x6f\137\142\x75\164\x74\x6f\156\137\154\157\x67\x69\156\137\146\157\162\x6d\x5f\x70\x6f\x73\151\164\x69\x6f\156") : '';
        $id[$BB] = array("\141\x64\144\x5f\x62\165\164\164\x6f\x6e\137\167\160\137\x6c\157\x67\151\156" => $OM, "\165\x73\145\137\x62\x75\164\164\157\x6e\137\x61\163\137\163\150\x6f\162\164\143\157\x64\145" => $Xr, "\x75\x73\x65\137\142\165\164\164\157\x6e\137\141\163\x5f\167\151\x64\147\x65\164" => $aa, "\142\x75\164\164\x6f\x6e\137\164\171\160\145" => $lJ, "\142\165\164\164\157\x6e\x5f\163\x69\x7a\145" => $FG, "\x62\x75\164\164\x6f\156\137\167\151\144\164\x68" => $Ny, "\x62\165\164\x74\157\156\x5f\150\145\151\x67\150\164" => $PB, "\142\x75\x74\x74\157\156\137\x63\x75\x72\x76\145" => $ym, "\x62\x75\x74\x74\157\x6e\137\143\157\154\x6f\162" => $TT, "\x62\x75\164\164\x6f\156\137\x74\145\x78\x74" => $gW, "\146\x6f\x6e\164\137\143\157\154\x6f\x72" => $fb, "\146\x6f\x6e\164\x5f\163\151\172\145" => $Cp, "\x62\165\x74\164\157\x6e\x5f\x70\157\x73\x69\164\151\157\x6e" => $SU);
        $s6->mo_save_environment_settings("\163\x61\x6d\154\x5f\x73\163\x6f\137\142\165\164\164\x6f\156\x5f\151\144\160", $id);
        $s6->mo_save_environment_settings("\163\141\155\154\137\163\145\154\x65\143\164\137\151\144\160\x5f\156\x61\x6d\x65", $BB);
        hU:
        $s6->mo_save_environment_settings(Mo_Saml_Admin_Referer_Options::RELAY_STATE_IDP_NAME, $BB);
        $s6->mo_save_environment_settings(mo_options_enum_sso_login::Relay_state, $aO);
        $s6->mo_save_environment_settings(mo_options_enum_sso_login::Relay_states, $HD);
        if (!empty($mS)) {
            goto ET;
        }
        if (!empty($xQ)) {
            goto Cz;
        }
        goto ta;
        ET:
        $s6->mo_save_environment_settings("\x6d\x6f\137\163\141\x6d\x6c\x5f\x72\145\144\x69\x72\x65\x63\x74\137\144\x65\146\141\x75\154\164\137\151\x64\160", "\164\162\165\x65");
        goto ta;
        Cz:
        $s6->mo_save_environment_settings("\x6d\x6f\x5f\x73\141\155\x6c\x5f\x72\145\147\151\x73\x74\x65\162\x65\144\137\157\x6e\154\171\137\141\x63\x63\145\163\163", "\x74\162\165\145");
        ta:
    }
    function mo_saml_delete_existing_options()
    {
        delete_option("\163\141\x6d\154\x5f\151\144\145\156\164\151\x74\171\137\x6e\141\155\145");
        delete_option("\x73\141\x6d\x6c\x5f\x6c\157\147\x69\x6e\137\x75\162\x6c");
        delete_option("\x6d\x6f\137\x73\x61\x6d\154\137\145\156\143\157\x64\151\156\x67\x5f\145\156\141\142\x6c\x65\144");
        delete_option("\x73\x61\155\x6c\137\151\x73\163\165\x65\162");
        delete_option("\x73\x61\155\154\x5f\x78\x35\x30\71\137\x63\x65\162\x74\151\146\151\x63\x61\164\x65");
        delete_option("\163\x61\x6d\154\137\x6c\157\x67\151\156\137\142\x69\x6e\144\x69\156\147\137\164\x79\160\145");
        delete_option("\x73\x61\x6d\x6c\x5f\x6c\157\147\157\x75\x74\x5f\x75\x72\154");
        delete_option("\x73\141\x6d\154\x5f\x6c\x6f\147\x6f\165\x74\137\142\x69\x6e\x64\x69\x6e\147\x5f\x74\x79\x70\x65");
        delete_option("\163\141\155\154\137\x6e\141\155\x65\151\144\137\146\x6f\x72\x6d\x61\164");
        delete_option("\x73\141\155\154\x5f\x61\155\137\144\x65\x66\141\165\154\x74\137\x75\x73\x65\x72\x5f\x72\157\154\145");
        delete_option("\163\141\155\x6c\137\162\x65\161\x75\x65\x73\x74\137\x73\x69\x67\156\145\x64");
        delete_option("\163\141\155\x6c\137\x61\155\137\x75\x73\x65\x72\x6e\141\155\x65");
        delete_option("\163\x61\x6d\x6c\137\x61\155\x5f\145\155\141\151\x6c");
        delete_option("\x73\141\x6d\154\137\141\x6d\x5f\x66\x69\x72\163\x74\x5f\x6e\141\x6d\145");
        delete_option("\x73\141\x6d\154\137\141\x6d\x5f\154\x61\163\164\x5f\x6e\141\155\145");
        delete_option("\163\x61\x6d\x6c\x5f\x61\155\x5f\156\x69\x63\x6b\x6e\x61\155\x65");
        delete_option("\163\x61\x6d\x6c\x5f\141\x6d\x5f\144\x69\x73\x70\154\141\171\x5f\x6e\141\x6d\x65");
        delete_option("\x73\141\x6d\154\137\141\x6d\x5f\x67\162\157\165\160\x5f\x6e\141\x6d\145");
        delete_option("\163\x61\x6d\154\137\x61\155\137\x64\145\x66\141\165\154\x74\x5f\x75\163\145\x72\x5f\x72\157\154\x65");
        delete_option("\x73\x61\x6d\154\x5f\141\155\x5f\162\157\x6c\145\137\x6d\x61\160\160\x69\x6e\147");
        delete_option("\155\x6f\x5f\163\141\x6d\x6c\x5f\x64\x6f\156\x74\137\143\x72\x65\141\x74\145\137\165\x73\x65\x72\x5f\151\146\x5f\x72\157\154\145\137\x6e\x6f\164\x5f\x6d\141\160\160\x65\x64");
        delete_option("\x73\141\x6d\154\x5f\x61\155\137\x64\x6f\x6e\x74\137\x61\154\154\157\167\137\x75\x6e\154\151\163\x74\145\144\137\165\163\x65\162\x5f\162\157\x6c\145");
        delete_option("\x73\141\155\154\137\x61\x6d\137\x64\157\x6e\x74\137\x75\x70\x64\x61\164\145\x5f\x65\x78\x69\x73\164\x69\x6e\147\137\165\163\x65\162\x5f\162\x6f\x6c\x65");
        delete_option("\x6d\157\x5f\163\x61\x6d\154\x5f\x72\x65\163\x74\162\151\x63\x74\137\x75\163\x65\162\163\137\167\151\164\x68\x5f\x67\x72\x6f\165\160\163");
        delete_option("\x6d\157\x5f\x73\x61\x6d\x6c\137\x65\156\x61\142\x6c\x65\x5f\x64\x6f\155\141\x69\x6e\137\x72\145\163\164\x72\x69\x63\164\x69\x6f\x6e\x5f\x6c\157\147\151\156");
        delete_option("\x6d\x6f\137\x73\x61\x6d\154\x5f\x61\x6c\x6c\157\167\137\x64\x65\x6e\171\137\165\163\145\x72\x5f\x77\x69\x74\150\137\144\157\155\141\x69\156");
        delete_option("\163\x61\x6d\154\137\x61\x6d\x5f\145\x6d\141\151\x6c\137\x64\157\x6d\x61\151\156\163");
        delete_option("\x6d\x6f\137\x73\x61\x6d\154\137\162\x65\154\x61\x79\x5f\x73\164\141\x74\x65");
        delete_option("\155\157\137\x73\141\155\x6c\x5f\x6c\157\147\x6f\165\164\x5f\162\145\x6c\141\x79\137\x73\x74\x61\x74\x65");
        delete_option("\155\157\137\x73\x61\x6d\154\x5f\156\x6f\164\151\143\x65\137\x74\157\137\x64\x69\x73\160\x6c\x61\x79");
        delete_option("\x6d\157\x5f\x73\141\155\154\x5f\162\x65\x67\151\163\164\x65\x72\x65\144\137\157\156\154\x79\x5f\x61\x63\x63\145\x73\163");
        delete_option("\155\x6f\x5f\x73\141\x6d\154\x5f\x72\145\144\151\162\x65\143\164\x5f\164\x6f\137\167\160\137\x6c\157\147\x69\156");
    }
    function handle_environment_migration()
    {
        $Zu = EnvironmentHelper::getPluginConfiguration(EnvironmentHelper::getCurrentEnvironment());
        if (!is_array($Zu)) {
            goto kI;
        }
        foreach ($Zu as $R2 => $EB) {
            update_option($R2, $EB);
            eF:
        }
        KO:
        kI:
    }
    function default_certificate()
    {
        $Fh = file_get_contents(plugin_dir_path(__FILE__) . "\x72\145\x73\157\165\x72\143\145\163" . DIRECTORY_SEPARATOR . mo_options_enum_default_sp_certificate::SP_PUBLIC_CERT_FILE_NAME);
        $lh = file_get_contents(plugin_dir_path(__FILE__) . "\162\x65\163\157\165\x72\x63\x65\163" . DIRECTORY_SEPARATOR . mo_options_enum_default_sp_certificate::SP_PRIVATE_KEY_FILE_NAME);
        if (!(!get_option("\x6d\157\137\163\x61\x6d\x6c\137\x63\165\162\x72\x65\156\x74\137\x63\145\162\x74") && !get_option("\x6d\157\x5f\163\141\x6d\154\137\143\165\162\162\145\156\164\x5f\143\145\x72\164\137\160\162\151\166\141\164\x65\137\153\145\x79"))) {
            goto Lp;
        }
        if (get_option("\x6d\x6f\x5f\x73\141\x6d\154\x5f\x63\145\x72\164") && get_option("\x6d\x6f\x5f\163\141\x6d\154\x5f\x63\x65\162\x74\x5f\x70\x72\151\x76\x61\164\x65\137\153\x65\171")) {
            goto Ao;
        }
        update_option("\x6d\x6f\137\x73\141\155\x6c\x5f\143\x75\x72\x72\x65\x6e\164\x5f\143\145\162\x74", $Fh);
        update_option("\x6d\x6f\137\163\x61\155\x6c\137\x63\165\162\x72\x65\156\x74\137\143\145\162\x74\x5f\x70\162\151\166\141\x74\x65\137\x6b\x65\171", $lh);
        goto th;
        Ao:
        update_option("\155\157\137\x73\141\155\x6c\x5f\x63\165\x72\x72\145\156\x74\x5f\143\145\162\164", get_option("\x6d\x6f\x5f\x73\141\x6d\x6c\137\x63\145\x72\164"));
        update_option("\x6d\x6f\x5f\x73\141\x6d\154\x5f\x63\165\x72\x72\145\156\164\x5f\143\x65\x72\x74\137\160\162\151\166\x61\164\x65\x5f\153\145\x79", get_option("\x6d\x6f\137\163\x61\x6d\x6c\x5f\143\145\x72\x74\x5f\160\162\151\166\141\164\145\x5f\x6b\x65\171"));
        th:
        Lp:
    }
    function mo_saml_plugin_action_links($Ue)
    {
        $Ue = array_merge(array("\x3c\x61\x20\x68\162\145\146\x3d\42" . esc_url(admin_url("\141\144\155\151\156\56\x70\150\x70\x3f\x70\x61\x67\145\x3d\155\157\137\163\x61\x6d\154\x5f\163\x65\164\x74\x69\156\147\163")) . "\x22\x3e" . __("\x53\x65\x74\164\151\x6e\x67\x73", "\164\145\170\164\144\157\155\141\x69\156") . "\x3c\57\141\76"), $Ue);
        return $Ue;
    }
    function myprefix_add_cron_schedule($hF)
    {
        $hF["\167\x65\x65\153\154\x79"] = array("\151\x6e\164\145\162\166\x61\154" => 604800, "\144\151\163\x70\x6c\x61\171" => __("\x4f\156\x63\x65\x20\x57\x65\x65\153\154\171"));
        $hF["\x6d\157\x6e\164\150\x6c\171"] = array("\151\x6e\x74\x65\x72\166\x61\154" => 2629746, "\x64\151\163\160\154\x61\171" => __("\x4f\x6e\x63\145\x20\115\157\156\x74\x68\154\171"));
        return $hF;
    }
    function mo_saml_custom_attr_column($a0)
    {
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $rU = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\163\141\x6d\x6c\137\143\165\x73\164\157\155\137\141\x74\x74\x72\x73\x5f\155\141\x70\x70\151\156\x67", true, $CP);
        $rU = maybe_unserialize($rU);
        $PU = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\155\x6c\x5f\x61\164\164\x72\163\x5f\x74\x6f\137\x64\x69\x73\160\x6c\141\x79\137\151\144\x70", true, $CP);
        foreach ($rU as $R2 => $EB) {
            $Ev = 0;
            if (!is_array($EB)) {
                goto C1;
            }
            foreach ($EB as $e0 => $sg) {
                if (empty($e0)) {
                    goto cm;
                }
                if (!(!empty($PU[$R2]) && SAMLSPUtilities::mo_saml_in_array($Ev, $PU[$R2]))) {
                    goto Cn;
                }
                $a0[$e0] = $e0;
                Cn:
                cm:
                ++$Ev;
                M_:
            }
            ss:
            C1:
            JA:
        }
        sp:
        return $a0;
    }
    function mo_saml_attr_column_content($Cr, $Tu, $Ur)
    {
        $rU = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\x5f\163\141\x6d\154\x5f\143\x75\163\x74\157\x6d\x5f\141\x74\164\162\163\137\x6d\141\160\160\x69\x6e\x67", true, EnvironmentHelper::getCurrentEnvironment());
        if (empty($rU)) {
            goto WZ;
        }
        foreach ($rU as $XE => $Tr) {
            if (empty($Tr)) {
                goto fD;
            }
            foreach ($Tr as $R2 => $EB) {
                if (!($R2 === $Tu)) {
                    goto q0;
                }
                $Qm = get_user_meta($Ur, $Tu, false);
                if (empty($Qm)) {
                    goto GH;
                }
                if (!is_array($Qm[0])) {
                    goto ja;
                }
                $Al = '';
                foreach ($Qm[0] as $sg) {
                    $Al = $Al . $sg;
                    if (!next($Qm[0])) {
                        goto lW;
                    }
                    $Al = $Al . "\x20\174\x20";
                    lW:
                    Z_:
                }
                qH:
                $Al = map_deep(wp_unslash($Al), "\145\163\x63\x5f\x68\164\x6d\x6c");
                return $Al;
                goto w5;
                ja:
                return esc_html($Qm[0]);
                w5:
                GH:
                q0:
                cP:
            }
            cQ:
            fD:
            ds:
        }
        M2:
        WZ:
        return $Cr;
    }
    function metadata_sync_cron_action($BB)
    {
        $K7 = ini_get("\x6d\141\170\137\145\170\145\143\165\x74\x69\x6f\156\x5f\164\151\x6d\145");
        $K7 = !empty($K7) ? intval($K7) : 30;
        set_time_limit(0);
        error_log("\x6d\x69\x6e\151\157\x72\141\x6e\x67\145\x20\x3a\x20\122\x41\116\x20\x53\131\x4e\x43\x20\55\40" . time() . $BB);
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $WT = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\x61\155\x6c\137\155\x65\164\141\144\141\x74\141\137\165\162\154\x5f\x66\x6f\x72\x5f\x73\171\x6e\143", true, $CP);
        $Oz = '';
        $zH = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\x6d\x6c\137\x64\145\x66\141\165\x6c\164\137\151\144\x70", false, $CP);
        $gu = maybe_unserialize(EnvironmentHelper::getOptionForSelectedEnvironment("\x73\x61\155\154\x5f\x69\x64\x65\x6e\164\151\164\171\137\x70\x72\157\166\151\x64\145\162\163", true, $CP));
        if (!empty($WT)) {
            goto w9;
        }
        wp_unschedule_event(wp_next_scheduled("\155\145\x74\141\x64\141\164\141\137\163\x79\156\143\137\x63\x72\157\156\137\x61\x63\164\151\157\x6e"), "\155\145\x74\141\144\x61\x74\141\137\x73\171\156\x63\x5f\143\162\x6f\x6e\x5f\x61\x63\164\151\157\156");
        goto wB;
        w9:
        if (!(!empty($WT[$BB]) and !empty($gu[$BB]))) {
            goto GU;
        }
        $Oz = $WT[$BB]["\x6d\145\x74\x61\144\x61\164\141\x5f\165\162\154"];
        $VK = $WT[$BB]["\x73\171\156\143\x5f\x63\x65\162\x74\151\146\151\x63\141\164\x65\x5f\x6d\x65\164\141\144\141\164\x61"] === "\143\150\145\143\x6b\145\144" ? true : false;
        $tH = wp_remote_get($Oz);
        $tH = is_wp_error($tH) || empty($tH["\x62\157\x64\171"]) ? '' : $tH["\142\x6f\x64\171"];
        $uM = Mo_Saml_Metadata_Import_Handler::mo_saml_get_object();
        $uM->mo_saml_upload_metadata($tH, $BB, $VK, true);
        GU:
        wB:
        $s6 = new EnvironmentDao($CP);
        $s6->mo_save_environment_settings("\163\x61\x6d\154\x5f\144\145\x66\x61\165\154\164\x5f\x69\144\x70", $zH);
        set_time_limit($K7);
    }
    function mo_login_widget_saml_options()
    {
        global $wpdb;
        update_option("\155\x6f\137\x73\141\155\x6c\x5f\150\157\x73\164\137\156\141\x6d\145", "\x68\164\164\x70\x73\x3a\x2f\57\x6c\157\147\151\156\x2e\170\145\x63\165\x72\151\146\x79\56\143\x6f\x6d");
        $L5 = get_option("\155\x6f\x5f\163\x61\155\x6c\137\x68\157\x73\x74\x5f\x6e\x61\155\145");
        mo_register_saml_sso();
    }
    function mo_saml_success_message()
    {
        $A4 = "\145\162\162\x6f\x72";
        $h9 = get_option("\x6d\x6f\x5f\x73\141\155\154\137\155\145\163\x73\141\147\x65");
        echo "\x3c\144\151\x76\x20\x63\154\x61\x73\x73\75\47" . esc_attr($A4) . "\x27\x3e\x20\74\x70\76" . esc_html($h9) . "\74\57\160\76\x3c\x2f\144\151\166\x3e";
    }
    function mo_saml_error_message()
    {
        $A4 = "\x75\160\x64\141\x74\145\144";
        $h9 = get_option("\155\x6f\137\163\x61\x6d\x6c\x5f\x6d\145\163\163\x61\x67\x65");
        echo "\74\144\x69\x76\x20\143\x6c\141\x73\163\x3d\x27" . esc_attr($A4) . "\x27\76\x20\x3c\160\76" . esc_html($h9) . "\x3c\57\x70\76\x3c\x2f\144\151\166\76";
    }
    public function mo_sso_saml_deactivate()
    {
        if (!is_plugin_active_for_network(plugin_basename(Mo_Saml_Plugin_Files::MAIN_PLUGIN_FILE))) {
            goto dX;
        }
        global $wpdb;
        $Dl = $wpdb->get_col("\x53\x45\114\x45\x43\124\40\x62\x6c\x6f\x67\137\x69\x64\x20\106\x52\117\115\x20{$wpdb->blogs}");
        $QF = get_current_blog_id();
        foreach ($Dl as $blog_id) {
            switch_to_blog($blog_id);
            if (!get_option(Mo_Saml_Options_Plugin_Admin::SML_LK)) {
                goto Hl;
            }
            $Uw = new Customersaml();
            $Uw->mo_saml_update_key_status($this);
            Hl:
            SAMLSPUtilities::mo_saml_delete_customer_details();
            Fb:
        }
        Ek:
        switch_to_blog($QF);
        goto Ia;
        dX:
        if (!get_option(Mo_Saml_Options_Plugin_Admin::SML_LK)) {
            goto PO;
        }
        $Uw = new Customersaml();
        $Uw->mo_saml_update_key_status($this);
        PO:
        SAMLSPUtilities::mo_saml_delete_customer_details();
        Ia:
        SAMLSPUtilities::mo_saml_disable_metadata_sync_for_all_idps();
    }
    function plugin_settings_style($W8)
    {
        wp_enqueue_style("\x6d\157\137\163\141\155\154\x5f\141\144\x6d\151\x6e\x5f\x6e\x6f\x74\151\x63\x65\x5f\x73\x74\171\x6c\x65", plugins_url("\x69\156\143\x6c\x75\x64\x65\163\57\143\163\163\x2f\155\157\137\x73\141\x6d\154\137\x61\x64\155\x69\156\137\156\x6f\164\x69\143\145\137\x73\x74\x79\154\x65\x2e\x6d\x69\x6e\56\143\163\163", __FILE__), array(), mo_options_plugin_constants::VERSION, "\141\x6c\154");
        if (!("\x74\157\160\x6c\145\166\x65\x6c\x5f\x70\x61\x67\145\137\x6d\157\x5f\163\141\155\x6c\137\x73\145\164\164\151\x6e\147\163" != $W8 && "\x6d\151\x6e\151\x6f\x72\141\156\147\145\x2d\x73\141\155\154\55\x32\55\x30\x2d\x73\163\157\x5f\x70\141\x67\x65\137\155\157\x5f\x73\x61\155\x6c\x5f\146\145\x64\145\162\x61\x74\151\x6f\156\x5f\x73\163\157" != $W8 && "\155\151\x6e\151\x6f\162\141\x6e\147\x65\55\x73\x61\x6d\x6c\x2d\x32\x2d\x30\x2d\163\x73\x6f\137\x70\x61\147\145\x5f\x6d\x6f\137\145\162\x72\x6f\x72\137\143\157\144\145\163" !== $W8 && "\155\x69\x6e\x69\157\162\x61\x6e\147\145\x2d\163\141\155\154\x2d\x32\55\x30\55\x73\163\x6f\137\x70\141\x67\x65\x5f\155\x6f\x5f\x6d\165\x6c\x74\x69\160\x6c\x65\x5f\145\156\x76\x69\162\x6f\x6e\155\x65\x6e\164" != $W8)) {
            goto dC;
        }
        return;
        dC:
        wp_enqueue_style("\155\157\x5f\x73\x61\155\x6c\137\142\x6f\157\x74\x73\164\x72\x61\160\137\x63\x73\163", plugins_url("\151\156\x63\x6c\x75\144\x65\163\57\143\163\163\x2f\142\157\157\164\163\x74\x72\x61\160\x2f\x62\157\157\164\163\x74\162\x61\x70\55\x74\157\147\147\154\x65\x2e\143\163\163", __FILE__), array(), mo_options_plugin_constants::VERSION);
        wp_enqueue_style("\155\157\137\x73\x61\x6d\x6c\137\x61\144\x6d\151\156\137\x73\x65\164\x74\x69\156\x67\163\137\163\164\x79\154\145\137\164\162\x61\x63\153\145\162", plugins_url("\x69\156\143\154\x75\x64\x65\x73\x2f\x63\163\163\57\160\x72\157\147\x72\x65\163\163\x2d\164\162\x61\x63\x6b\145\x72\x2e\x63\163\163", __FILE__), array(), mo_options_plugin_constants::VERSION);
        wp_enqueue_style("\155\157\137\163\141\x6d\154\137\x61\x64\x6d\151\x6e\x5f\x73\145\x74\164\x69\x6e\x67\x73\137\160\x68\157\156\145\137\x73\x74\171\154\145", plugins_url("\151\x6e\143\154\x75\144\145\163\x2f\143\x73\163\x2f\160\150\157\156\145\56\155\151\156\56\143\x73\163", __FILE__), array(), mo_options_plugin_constants::VERSION);
        wp_enqueue_style("\155\x6f\137\x73\x61\x6d\x6c\x5f\x6d\x61\x6e\141\x67\145\x5f\x6c\151\143\x65\156\163\145\x5f\x73\x65\164\x74\x69\156\147\163\137\163\x74\x79\154\x65", plugins_url("\x45\x6e\166\x69\x72\x6f\x6e\x6d\x65\x6e\x74\125\x74\151\154\x73\57\x76\151\x65\167\163\57\105\156\166\x69\x72\157\x6e\x6d\145\156\x74\x56\151\x65\167\x2e\x63\x73\163", __FILE__), array(), mo_options_plugin_constants::VERSION);
        wp_enqueue_style("\155\x6f\x5f\x73\x61\x6d\154\137\x61\x64\155\x69\x6e\137\163\145\164\164\151\156\x67\163\137\x73\164\x79\x6c\x65", plugins_url("\x69\x6e\x63\x6c\x75\144\145\163\57\143\x73\x73\57\163\164\171\154\145\x5f\163\x65\x74\164\151\156\x67\x73\x2e\x6d\x69\x6e\56\x63\163\163", __FILE__), array(), mo_options_plugin_constants::VERSION);
    }
    function plugin_settings_script($W8)
    {
        if (!("\x74\157\160\x6c\x65\166\145\154\137\160\141\147\x65\x5f\155\x6f\137\x73\141\x6d\x6c\x5f\163\145\164\164\x69\x6e\x67\x73" != $W8 && "\x6d\151\156\151\x6f\x72\141\156\x67\145\55\x73\141\155\154\x2d\62\x2d\60\55\x73\x73\157\x5f\160\141\x67\x65\137\155\157\x5f\x73\141\155\x6c\x5f\x66\145\x64\145\162\x61\164\151\x6f\x6e\137\163\x73\x6f" != $W8 && "\155\151\x6e\151\x6f\x72\141\156\147\x65\55\163\141\155\154\55\x32\55\60\x2d\x73\x73\x6f\x5f\160\x61\x67\x65\137\155\x6f\137\145\162\162\157\x72\137\143\x6f\144\145\x73" !== $W8 && "\155\151\x6e\x69\157\162\x61\156\147\145\x2d\163\141\x6d\x6c\x2d\x32\55\60\55\163\163\157\x5f\160\141\x67\145\x5f\155\x6f\137\x6d\x75\154\x74\x69\160\154\x65\137\145\x6e\x76\x69\x72\157\x6e\x6d\145\156\x74" != $W8)) {
            goto z6;
        }
        return;
        z6:
        wp_enqueue_script("\155\157\137\163\141\x6d\154\137\x61\x64\155\151\x6e\x5f\x73\x65\x74\164\151\156\x67\x73\x5f\x63\x6f\x6c\x6f\162\137\x73\143\x72\x69\x70\x74", plugins_url("\x69\156\x63\x6c\165\x64\145\163\57\152\163\57\x6a\x73\143\157\154\x6f\162\x2f\x6a\x73\143\x6f\x6c\157\162\x2e\x6a\x73", __FILE__), array(), mo_options_plugin_constants::VERSION, false);
        wp_enqueue_script("\x6a\161\165\x65\162\x79");
        wp_enqueue_script("\x6d\157\x5f\163\x61\x6d\x6c\137\142\x6f\157\164\163\164\162\141\x70\x5f\163\x63\x72\151\x70\x74", plugins_url("\151\x6e\143\154\x75\x64\145\x73\x2f\x6a\x73\57\x62\157\157\x74\x73\x74\162\x61\160\x2f\x62\157\x6f\164\163\x74\162\141\x70\x5f\x74\157\147\x67\154\x65\x2e\x6d\151\156\x2e\x6a\163", __FILE__), array(), mo_options_plugin_constants::VERSION);
        wp_enqueue_script("\155\157\x5f\x73\141\155\x6c\x5f\x61\x64\155\x69\156\137\163\x65\164\164\151\x6e\147\163\137\160\x68\157\156\x65\x5f\163\143\162\151\x70\164", plugins_url("\151\x6e\x63\154\x75\144\145\x73\57\x6a\x73\57\160\150\x6f\156\145\x2e\x6d\151\156\56\152\x73", __FILE__), array(), mo_options_plugin_constants::VERSION);
        wp_enqueue_script("\155\157\137\x73\x61\x6d\x6c\x5f\141\144\x6d\151\156\x5f\163\145\x74\164\151\156\x67\163\137\163\143\x72\151\x70\164", plugins_url("\151\156\x63\154\165\144\x65\x73\x2f\152\163\x2f\163\145\x74\164\x69\156\147\163\x2e\x6d\x69\156\56\x6a\x73", __FILE__), array(), mo_options_plugin_constants::VERSION);
        $vE = Mo_License_Service::get_formatted_license_expiry_date(Mo_License_Service::get_expiry_date());
        $Hn = Mo_License_Service::get_expiry_remaining_days($vE);
        $qc = isset($_GET["\x74\x61\x62"]) ? sanitize_text_field($_GET["\164\141\142"]) : '';
        wp_enqueue_script("\155\157\55\163\141\155\x6c\55\141\152\x61\170", plugins_url("\x69\156\143\154\165\x64\x65\x73\x2f\x6a\x73\x2f\155\x6f\137\x73\141\x6d\x6c\137\x61\152\141\x78\56\x6d\151\x6e\x2e\x6a\163", __FILE__), array(), mo_options_plugin_constants::VERSION, true);
        wp_localize_script("\x6d\x6f\x2d\163\141\155\154\55\141\152\141\170", "\155\x6f\123\141\155\x6c\x41\x6a\x61\170", array("\x61\152\x61\170\137\x75\162\x6c" => admin_url("\x61\144\155\151\x6e\55\141\x6a\x61\170\x2e\x70\x68\160"), "\x6e\157\x6e\143\x65" => wp_create_nonce("\155\x6f\x5f\x73\141\x6d\154\137\x73\171\x6e\143\x5f\154\x69\143\145\x6e\163\145\137\x61\152\x61\170\x5f\x6e\157\x6e\x63\145"), "\162\145\x6d\x61\x69\x6e\151\x6e\147\x5f\144\141\x79\163" => $Hn, "\x63\165\x72\162\x65\156\x74\137\x74\141\142" => $qc));
    }
    static function mo_check_option_admin_referer($If)
    {
        return !empty($_POST["\x6f\160\x74\x69\x6f\x6e"]) and $_POST["\x6f\160\164\151\157\x6e"] == $If and check_admin_referer($If);
    }
    function mo_saml_admin_init_actions()
    {
        $this->miniorange_login_widget_saml_save_settings();
        $this->mo_saml_show_screen_options();
        $this->mo_saml_do_plugin_extension_checks();
        $this->default_certificate();
        mo_saml_download();
    }
    function mo_saml_show_screen_options()
    {
        if (!current_user_can("\155\x61\x6e\x61\x67\x65\137\157\160\x74\x69\157\x6e\163")) {
            goto Y4;
        }
        if (!(isset($_GET["\164\141\142"]) && $_GET["\164\x61\x62"] === "\163\141\x76\145" && isset($_GET["\141\143\x74\x69\157\x6e"]) && ($_GET["\x61\x63\164\151\x6f\x6e"] === "\145\x64\x69\x74" || $_GET["\141\143\164\x69\x6f\x6e"] === "\165\160\154\157\141\144\x5f\155\x75\x6c\164\151\x70\x6c\x65" || $_GET["\141\x63\164\x69\x6f\156"] === "\x61\x64\x64"))) {
            goto aY;
        }
        add_filter("\x73\x63\x72\145\145\x6e\x5f\x6f\160\x74\x69\157\x6e\x73\x5f\x73\x68\157\x77\x5f\x73\143\162\x65\x65\156", "\x5f\x5f\x72\145\164\165\x72\x6e\x5f\x66\141\x6c\x73\145");
        aY:
        Y4:
    }
    function miniorange_login_widget_saml_save_settings()
    {
        if (!current_user_can("\x6d\141\156\x61\x67\145\137\157\160\164\x69\x6f\156\163")) {
            goto f9i;
        }
        $s6 = new EnvironmentDao();
        $Nj = call_user_func("\x4d\157\x5f\x53\141\155\x6c\x5f\101\x64\x6d\151\156\137\122\x65\x66\145\162\145\162\137\117\x70\164\x69\x6f\156\163\x3a\x3a\147\145\164\103\157\x6e\163\x74\x61\x6e\164\163");
        $HF = Mo_Saml_Bulk_Actions::$bulk_actions;
        if (!(!empty(EnvironmentHelper::getOptionForSelectedEnvironment(mo_options_enum_sso_login::Relay_state)) && empty(EnvironmentHelper::getOptionForSelectedEnvironment(mo_options_enum_sso_login::Relay_states, true)))) {
            goto dd;
        }
        $Rn = array();
        $Rn["\x6c\x6f\x67\x69\x6e\137\x72\x65\x6c\x61\171\x5f\163\164\x61\x74\x65"]["\104\105\106\101\125\114\x54"] = EnvironmentHelper::getOptionForSelectedEnvironment(mo_options_enum_sso_login::Relay_state);
        $s6->mo_save_environment_settings(mo_options_enum_sso_login::Relay_states, $Rn);
        dd:
        if (!(isset($_POST["\157\x70\164\151\157\156"]) && in_array($_POST["\x6f\x70\x74\x69\157\x6e"], $Nj) && !Mo_License_Service::is_customer_license_valid())) {
            goto BU;
        }
        update_option("\155\x6f\137\x73\x61\x6d\x6c\137\x6d\145\x73\x73\141\x67\x65", "\123\x6f\155\145\x74\x68\151\156\147\x20\x77\145\x6e\164\40\167\x72\x6f\156\147\40\x77\x68\x69\x6c\145\x20\x70\162\x6f\143\x65\163\163\151\156\x67\40\164\150\x69\x73\40\x72\145\x71\x75\x65\163\164\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        BU:
        if (!self::mo_check_option_admin_referer("\155\x6f\137\x6d\x75\154\164\151\x70\x6c\145\137\145\x6e\x76\151\x72\x6f\x6e\x6d\145\156\164")) {
            goto p3;
        }
        if (array_key_exists("\x6d\x6f\137\145\156\x61\142\154\x65\137\x6d\165\x6c\164\151\x70\x6c\x65\137\154\151\x63\145\156\x73\x65\163", $_POST)) {
            goto Na;
        }
        $this->handle_environment_migration();
        delete_option("\x6d\x6f\x5f\x65\156\x61\x62\x6c\145\x5f\x6d\x75\154\164\151\x70\154\x65\137\154\151\x63\x65\156\x73\145\x73");
        update_option("\x6d\157\137\163\x61\155\154\x5f\155\145\163\x73\141\147\x65", "\115\x75\154\164\x69\160\154\145\40\x45\x6e\x76\x69\162\157\x6e\155\x65\x6e\164\163\40\144\151\163\x61\142\154\145\x64\x2e");
        SAMLSPUtilities::mo_saml_show_success_message();
        goto AJ;
        Na:
        update_option("\x6d\x6f\x5f\x65\x6e\141\142\x6c\x65\x5f\155\x75\x6c\x74\151\x70\154\x65\x5f\154\151\x63\x65\156\163\x65\x73", "\143\150\145\143\x6b\145\144");
        $s_ = isset($_POST["\143\157\160\171\137\145\170\x69\x73\164\x69\156\147\137\x63\x6f\x6e\146\151\147\x75\162\x61\164\151\x6f\156\163"]) ? $_POST["\x63\157\x70\x79\137\145\170\151\x73\x74\151\x6e\x67\137\143\x6f\x6e\x66\x69\147\165\162\141\x74\x69\x6f\x6e\x73"] : false;
        if ("\x63\150\145\143\153\x65\x64" === $s_) {
            goto zb;
        }
        initializeEnvironmentObjectArray();
        goto CK;
        zb:
        Migrate_Environment_Settings::mo_saml_copy_existing_configurations();
        CK:
        delete_option("\x6d\x6f\x5f\163\x61\155\154\137\145\156\166\151\162\157\x6e\x6d\145\x6e\x74\x5f\157\142\152\145\143\x74\163\x5f\x70\x72\145\155\151\x75\x6d");
        update_option("\155\x6f\x5f\x73\x61\155\x6c\137\x6d\x65\163\x73\x61\147\145", "\x4d\165\x6c\164\151\160\154\x65\40\105\x6e\x76\x69\162\157\156\155\x65\156\164\163\40\x65\156\x61\142\x6c\x65\x64\56");
        SAMLSPUtilities::mo_saml_show_success_message();
        AJ:
        p3:
        if (!self::mo_check_option_admin_referer("\x6d\157\137\141\144\144\151\x6e\147\x5f\x61\x6c\x74\x65\162\156\141\164\145\x5f\x65\x6e\166\x69\x72\x6f\156\x6d\x65\156\164\x73")) {
            goto V2;
        }
        if (updateEnvironmentObjects($_POST)) {
            goto vn;
        }
        update_option("\x6d\157\137\163\141\155\x6c\x5f\155\145\x73\x73\x61\147\x65", "\131\x6f\x75\x72\x20\x63\150\x61\x6e\x67\145\x73\40\x77\x65\x72\x65\40\x6e\x6f\x74\x20\163\x61\166\145\144\56\x20\120\154\x65\141\x73\x65\x20\160\x72\x6f\166\x69\144\145\x20\x75\156\x69\161\165\145\x20\166\x61\154\165\x65\163\40\146\x6f\162\x20\x79\157\165\162\x20\145\156\166\x69\162\x6f\x6e\x6d\145\156\x74\x73\x20\141\x6e\x64\x20\144\157\x6e\47\164\40\x72\x65\x6d\x6f\x76\x65\x20\x74\x68\145\40\143\x75\x72\x72\x65\x6e\x74\x20\145\x6e\x76\151\162\157\x6e\x6d\x65\156\x74");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto U6;
        vn:
        update_option("\x6d\x6f\137\x73\x61\x6d\154\x5f\155\145\163\x73\141\x67\145", "\105\x6e\166\x69\162\157\x6e\155\x65\156\x74\163\40\x75\160\x64\141\x74\x65\x64\x20\163\x75\143\x63\145\163\163\146\x75\154\x6c\171");
        SAMLSPUtilities::mo_saml_show_success_message();
        U6:
        V2:
        if (!self::mo_check_option_admin_referer("\155\157\137\x63\150\141\x6e\147\x65\137\145\x6e\166\x69\162\x6f\x6e\155\145\x6e\164")) {
            goto OD;
        }
        update_option("\x6d\157\137\163\x61\x6d\154\137\163\x65\x6c\x65\x63\x74\145\x64\x5f\145\x6e\166\x69\x72\157\156\x6d\145\x6e\x74", $_POST["\x65\x6e\x76\151\162\x6f\x6e\x6d\145\x6e\x74"]);
        update_option("\155\157\x5f\163\141\155\154\x5f\x6d\145\163\x73\x61\x67\x65", "\105\x6e\166\151\162\157\x6e\x6d\x65\x6e\164\40\x63\x68\x61\x6e\x67\145\x64\x20\x73\165\143\x63\145\x73\163\146\165\154\x6c\x79");
        SAMLSPUtilities::mo_saml_show_success_message();
        OD:
        if (!self::mo_check_option_admin_referer("\x6c\x6f\147\151\156\x5f\x77\x69\144\x67\145\164\137\x73\x61\155\x6c\137\163\x61\x76\x65\x5f\x73\x65\x74\x74\x69\156\x67\163")) {
            goto gJ;
        }
        if (mo_saml_is_extension_installed("\x63\165\x72\x6c")) {
            goto zw;
        }
        update_option("\x6d\157\x5f\163\x61\155\x6c\x5f\155\x65\x73\163\x61\147\145", "\x45\x52\x52\x4f\x52\72\x20\74\141\40\150\x72\145\x66\x3d\x22\150\x74\164\160\x3a\x2f\x2f\160\150\x70\56\156\x65\x74\x2f\x6d\x61\x6e\x75\141\x6c\57\x65\156\57\x63\x75\x72\x6c\x2e\151\x6e\x73\x74\141\x6c\x6c\x61\x74\151\x6f\x6e\56\160\x68\160\x22\40\164\141\162\147\145\164\75\x22\137\x62\x6c\x61\x6e\x6b\x22\x3e\120\x48\x50\x20\x63\125\122\114\x20\x65\x78\x74\145\x6e\x73\151\x6f\x6e\x3c\x2f\141\x3e\x20\x69\163\40\x6e\157\164\40\x69\x6e\x73\164\141\154\x6c\x65\144\40\157\x72\x20\144\151\163\x61\142\154\145\144\x2e\x20\x53\x61\166\x65\x20\111\x64\145\156\x74\x69\164\171\40\x50\162\157\166\x69\x64\145\x72\40\x43\157\x6e\x66\x69\147\165\162\x61\x74\x69\157\x6e\x20\146\141\151\154\145\144\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        zw:
        $gu = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\x6d\154\137\151\144\x65\156\x74\151\x74\171\137\x70\162\157\x76\x69\x64\145\x72\x73", true);
        $p6 = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\163\141\155\x6c\137\151\144\160\x5f\x6e\141\155\x65\x5f\x69\144\x5f\x6d\141\160", true);
        $sZ = isset($_POST["\163\141\x6d\154\137\x73\141\166\145\137\163\145\x74\x74\x69\156\147\163\137\x61\x63\x74\151\157\x6e"]) ? sanitize_text_field($_POST["\163\x61\155\x6c\x5f\x73\141\166\145\x5f\163\145\x74\164\151\156\147\163\x5f\x61\x63\164\151\157\x6e"]) : '';
        $RF = '';
        $K3 = '';
        $Kp = '';
        $tX = '';
        $uN = '';
        $XM = '';
        $Dc = '';
        $uo = '';
        $CF = '';
        $nq = '';
        $EH = "\x59\145\163";
        $td = "\x59\x65\163";
        $B8 = '';
        $ML = "\165\x6e\143\150\145\143\x6b\145\144";
        $sn = "\143\x68\145\x63\x6b\145\x64";
        $Pn = "\143\x68\145\143\153\145\x64";
        $kd = !empty($_POST["\163\141\155\154\x5f\145\144\x69\x74\x5f\x69\144\x70\137\x6e\141\x6d\x65"]) && is_array($_POST["\163\x61\155\x6c\x5f\x65\x64\151\x74\x5f\151\144\x70\x5f\156\141\155\145"]) ? array_map("\x73\141\156\x69\164\151\x7a\145\x5f\164\145\170\164\x5f\146\x69\145\154\144", $_POST["\x73\141\x6d\x6c\137\x65\144\x69\x74\x5f\151\x64\160\137\x6e\141\155\x65"]) : array();
        if (!(isset($_POST["\163\141\155\154\x5f\x69\144\145\x6e\164\x69\164\171\x5f\x6e\x61\155\145"]) && !preg_match("\x23\136\x28\77\x3d\x2e\x2a\x5b\x61\x2d\x7a\101\55\x5a\x30\55\71\x5d\51\133\141\55\x7a\101\55\132\x30\x2d\x39\x5c\x73\x5f\134\x2d\100\135\x2b\x24\x23", $_POST["\163\141\155\x6c\x5f\151\x64\x65\156\x74\151\164\171\137\156\141\155\x65"]))) {
            goto Gu;
        }
        update_option("\x6d\x6f\137\163\x61\155\154\x5f\155\145\163\x73\141\147\x65", "\x50\154\145\x61\x73\x65\x20\x6d\x61\164\x63\x68\40\164\x68\x65\40\162\145\161\165\145\x73\164\145\144\40\x66\x6f\162\x6d\x61\164\x20\x66\157\162\x20\111\x64\145\156\x74\x69\x74\x79\x20\x50\162\157\166\151\x64\145\x72\x20\x4e\x61\x6d\x65\x2e\40\x53\x70\145\143\x69\141\x6c\40\143\150\x61\162\141\143\164\x65\162\x73\x20\141\x72\x65\40\156\157\x74\40\141\154\x6c\157\167\x65\x64\40\145\x78\x63\145\160\164\40\x75\156\x64\145\x72\x73\x63\x6f\162\x65\x28\137\x29\x2c\x20\x68\171\x70\x68\x65\x6e\x28\55\51\x20\x61\x6e\144\40\x40\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        Gu:
        if (!(!empty($_POST["\x73\x61\155\154\x5f\x65\x64\x69\164\x5f\x69\144\x70\x5f\x6e\141\x6d\x65"]) && !is_array($_POST["\x73\x61\x6d\x6c\x5f\145\x64\151\x74\x5f\151\x64\160\137\x6e\x61\155\x65"]))) {
            goto lP;
        }
        $B8 = sanitize_text_field($_POST["\163\141\155\x6c\x5f\145\x64\x69\164\137\151\144\x70\137\x6e\141\x6d\x65"]);
        lP:
        if (!in_array($sZ, Mo_Saml_Bulk_Actions::$bulk_actions) && "\143\165\163\164\x6f\155" !== $sZ && (empty($_POST["\x73\141\155\154\x5f\x69\x64\145\x6e\x74\151\164\x79\137\156\x61\155\x65"]) || empty($_POST["\x73\x61\155\x6c\137\x6c\x6f\x67\x69\x6e\x5f\x75\162\x6c"]) || empty($_POST["\163\x61\155\154\137\x69\163\x73\x75\x65\x72"]))) {
            goto EJ;
        }
        if (!in_array($sZ, Mo_Saml_Bulk_Actions::$bulk_actions) && "\143\165\x73\x74\x6f\x6d" !== $sZ) {
            goto qX;
        }
        goto BS;
        EJ:
        update_option("\155\x6f\137\163\141\155\154\x5f\155\145\x73\163\141\x67\145", "\101\154\154\x20\x74\150\145\x20\146\x69\x65\154\144\x73\40\141\162\x65\x20\162\x65\161\165\x69\162\x65\x64\56\x20\x50\154\145\141\x73\145\x20\x65\x6e\x74\x65\162\40\x76\141\154\151\144\x20\145\x6e\164\162\x69\x65\x73\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        goto BS;
        qX:
        $RF = !empty($B8) && "\145\x64\x69\x74" === $sZ ? $B8 : SAMLSPUtilities::mo_saml_generate_idp_id($gu);
        $K3 = sanitize_text_field(wp_unslash($_POST["\163\141\155\154\137\x69\x64\x65\x6e\x74\x69\x74\171\137\x6e\x61\x6d\x65"]));
        $tX = esc_url_raw(filter_var($_POST["\163\x61\x6d\x6c\137\154\x6f\x67\x69\156\x5f\x75\x72\x6c"], FILTER_SANITIZE_URL));
        $tg = sanitize_text_field($_POST["\x73\141\155\154\137\163\x70\137\145\156\x74\151\x74\x79\137\151\144"]);
        $rR = sanitize_text_field($_POST["\x73\x61\x6d\x6c\137\151\x64\145\156\164\151\x74\x79\x5f\x70\162\x6f\166\x69\144\x65\x72\137\x67\x75\x69\x64\145\x5f\x6e\x61\x6d\x65"]);
        if (!(!empty($gu[$B8]) && !empty($gu[$B8]["\x69\x64\160\137\x64\151\x73\160\154\x61\x79\137\156\141\x6d\145"]) && $K3 !== $gu[$B8]["\151\x64\160\x5f\144\x69\163\160\x6c\141\171\137\x6e\141\155\x65"])) {
            goto b5;
        }
        $xK = $gu[$B8]["\151\x64\160\x5f\x64\x69\163\160\x6c\141\171\137\x6e\141\155\145"];
        unset($p6[$xK]);
        b5:
        if (empty($_POST["\163\141\x6d\x6c\137\154\x6f\147\x69\156\x5f\x62\x69\x6e\144\x69\156\147\x5f\x74\x79\160\x65"])) {
            goto Xp;
        }
        $Kp = sanitize_text_field($_POST["\x73\x61\x6d\x6c\137\154\x6f\147\x69\156\x5f\142\151\156\x64\x69\x6e\x67\137\x74\x79\x70\145"]);
        Xp:
        if (empty($_POST["\x73\x61\155\154\x5f\154\x6f\147\x6f\x75\164\x5f\142\x69\x6e\x64\151\x6e\x67\137\x74\171\x70\x65"])) {
            goto wl;
        }
        $uN = sanitize_text_field($_POST["\x73\x61\x6d\x6c\137\154\x6f\x67\x6f\x75\x74\137\142\151\x6e\144\151\x6e\x67\x5f\x74\x79\160\145"]);
        wl:
        $nq = sanitize_text_field($_POST["\163\x61\155\154\x5f\x6e\x61\x6d\145\151\144\x5f\146\x6f\x72\x6d\x61\x74"]);
        if (!empty($_POST["\145\156\x61\x62\x6c\145\x5f\x69\143\x6f\156\x76"])) {
            goto kR;
        }
        $sn = '';
        goto aM;
        kR:
        $sn = "\x63\150\x65\143\x6b\145\144";
        aM:
        if (!empty($_POST["\155\x6f\x5f\x73\x61\155\x6c\137\141\x73\163\x65\x72\164\x69\x6f\x6e\x5f\164\151\x6d\145\x5f\x76\x61\x6c\151\144\151\164\x79"])) {
            goto H7;
        }
        $Pn = "\165\x6e\143\x68\x65\x63\x6b\x65\x64";
        H7:
        if (empty($_POST["\163\141\x6d\x6c\x5f\x6c\x6f\x67\157\x75\x74\137\x75\162\154"])) {
            goto k1;
        }
        $XM = esc_url_raw(filter_var($_POST["\163\141\155\x6c\x5f\x6c\x6f\147\157\165\164\x5f\x75\x72\x6c"], FILTER_SANITIZE_URL));
        k1:
        if (empty($_POST["\163\141\x6d\154\x5f\154\157\147\x6f\165\164\137\x72\x65\163\160\x6f\156\163\x65\x5f\165\x72\x6c"])) {
            goto UD;
        }
        $Dc = esc_url_raw(filter_var($_POST["\163\x61\155\x6c\137\x6c\157\x67\157\x75\x74\x5f\x72\145\x73\160\x6f\156\x73\145\137\165\x72\x6c"], FILTER_SANITIZE_URL));
        UD:
        if (!array_key_exists("\x73\x61\155\154\137\160\167\x5f\x72\145\x73\145\x74\137\x75\162\154", $_POST)) {
            goto rx;
        }
        $kh = esc_url_raw(filter_var($_POST["\x73\141\155\154\x5f\x70\167\x5f\x72\x65\163\145\x74\137\165\162\x6c"], FILTER_SANITIZE_URL));
        rx:
        $uo = sanitize_text_field($_POST["\x73\x61\155\154\137\x69\163\x73\165\x65\162"]);
        $CF = $_POST["\x73\141\155\x6c\x5f\170\x35\x30\x39\137\143\145\x72\x74\x69\146\x69\143\141\164\145"];
        if (empty($_POST["\163\141\x6d\x6c\137\162\145\x73\x70\157\156\x73\145\137\x73\151\x67\x6e\145\x64"])) {
            goto oa;
        }
        $EH = "\x63\150\x65\143\x6b\x65\x64";
        oa:
        if (empty($_POST["\163\x61\x6d\x6c\137\x61\x73\163\x65\x72\164\x69\157\x6e\137\163\151\147\x6e\145\x64"])) {
            goto bF;
        }
        $td = "\143\x68\145\x63\153\145\x64";
        bF:
        if (empty($_POST["\163\x61\x6d\154\x5f\x72\145\161\x75\145\x73\164\137\x73\151\147\x6e\145\x64"])) {
            goto JY;
        }
        $ML = "\x63\150\145\143\x6b\145\144";
        JY:
        BS:
        if ($sZ == "\x61\144\x64" && !empty($gu[$RF])) {
            goto Gr;
        }
        if ($sZ == "\145\144\151\164" && !empty($gu[$B8]) || $sZ == "\141\x64\144") {
            goto K1;
        }
        if ($sZ == "\x63\165\x73\x74\157\x6d" && !empty($gu[$B8])) {
            goto g2;
        }
        if ($sZ === $HF["\142\165\x6c\153\x5f\144\145\154\145\164\145"]) {
            goto Ed;
        }
        goto pV;
        Gr:
        update_option("\x6d\x6f\x5f\163\141\x6d\x6c\x5f\155\145\x73\163\141\147\145", "\x49\x64\x65\156\x74\151\164\x79\40\120\162\157\166\151\x64\x65\162\x20\167\151\164\x68\40\x3c\145\x6d\x3e" . esc_html($RF) . "\x3c\57\145\155\x3e\x20\x61\x6c\162\x65\x61\x64\x79\40\145\170\151\163\164\163\56\x20\124\x72\x79\40\x61\x6e\157\x74\150\x65\162\x20\x6e\x61\x6d\145\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        goto pV;
        K1:
        if (!array_key_exists($RF, $gu)) {
            goto nd;
        }
        SAMLSPUtilities::mo_saml_delete_admin_notice($gu[$RF]["\163\163\x6f\x5f\x75\x72\154"]);
        nd:
        $fP = SAMLSPUtilities::mo_saml_check_idp_display_name($gu, $p6, $B8, $K3);
        if (!$fP) {
            goto J6;
        }
        update_option("\x6d\157\x5f\163\x61\155\154\x5f\155\145\163\x73\141\x67\x65", "\111\144\x65\156\x74\151\x74\x79\40\120\162\x6f\x76\x69\144\x65\x72\40\x77\151\164\x68\40\x6e\x61\155\x65\40\x3c\145\155\x3e" . esc_html($K3) . "\x3c\57\x65\x6d\76\40\x61\x6c\x72\x65\141\144\171\40\145\170\x69\163\x74\x73\56\40\x54\162\x79\40\141\x6e\x6f\164\150\x65\162\x20\x49\x64\x65\156\x74\x69\x74\x79\x20\120\x72\x6f\166\x69\x64\145\x72\40\x6e\x61\155\x65\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        J6:
        $oa = !empty($gu[$RF]["\143\x75\163\164\x6f\x6d\x5f\154\157\x67\151\156\137\x74\145\170\x74"]) ? $gu[$RF]["\x63\x75\x73\164\157\x6d\x5f\154\x6f\x67\x69\156\x5f\x74\145\170\164"] : '';
        $Da = !empty($gu[$RF]["\x63\165\163\x74\x6f\155\137\x67\162\145\145\164\151\x6e\147\x5f\x74\145\170\164"]) ? $gu[$RF]["\x63\x75\x73\x74\157\x6d\x5f\x67\x72\145\145\x74\x69\156\147\x5f\164\145\x78\164"] : '';
        $fc = !empty($gu[$RF]["\x67\162\x65\x65\x74\151\x6e\147\x5f\156\x61\155\x65"]) ? $gu[$RF]["\147\x72\145\145\x74\x69\x6e\x67\x5f\x6e\x61\x6d\145"] : '';
        $Qz = !empty($gu[$RF]["\x63\x75\163\164\157\x6d\137\x6c\157\147\x6f\165\x74\x5f\164\145\170\x74"]) ? $gu[$RF]["\x63\165\163\164\157\155\x5f\154\157\x67\x6f\x75\164\x5f\164\x65\170\164"] : '';
        $LH = !empty($gu[$RF]["\x73\141\x6d\154\x5f\162\x65\x71\x75\145\x73\164"]) ? $gu[$RF]["\x73\x61\x6d\154\137\x72\x65\161\x75\x65\163\x74"] : '';
        $Er = !empty($gu[$RF]["\163\x61\155\x6c\137\x72\x65\163\x70\x6f\x6e\x73\145"]) ? $gu[$RF]["\163\141\155\154\137\x72\x65\x73\160\157\156\163\145"] : '';
        $n_ = !empty($gu[$RF]["\x74\x65\163\164\137\x73\164\141\164\x75\x73"]) ? $gu[$RF]["\x74\x65\x73\164\137\x73\x74\141\x74\165\163"] : '';
        $vs = array("\x63\165\163\x74\157\155\x5f\154\157\147\x69\156\x5f\x74\145\170\x74" => sanitize_text_field($oa), "\x63\165\x73\x74\x6f\x6d\137\147\162\145\145\164\151\x6e\x67\137\x74\145\170\x74" => sanitize_text_field($Da), "\147\x72\x65\145\x74\x69\x6e\x67\x5f\x6e\141\x6d\x65" => sanitize_text_field($fc), "\x63\x75\x73\164\x6f\x6d\137\154\157\147\157\x75\x74\x5f\x74\x65\x78\x74" => sanitize_text_field($Qz));
        $nG = array("\x73\141\155\x6c\x5f\162\x65\161\x75\145\x73\x74" => $LH, "\x73\x61\x6d\154\x5f\x72\x65\163\160\157\x6e\x73\x65" => $Er, "\x74\x65\163\x74\x5f\x73\164\141\x74\x75\x73" => $n_);
        $MW = isset($gu[$B8]) ? $gu[$B8]["\145\x6e\x61\x62\154\145\x5f\151\x64\160"] : true;
        unset($gu[$B8]);
        if (is_array($CF)) {
            goto XD;
        }
        $CF = trim($CF);
        goto ZF;
        XD:
        foreach ($CF as $R2 => $EB) {
            $CF[$R2] = SAMLSPUtilities::sanitize_certificate($EB);
            if (@openssl_x509_read($CF[$R2])) {
                goto G7;
            }
            update_option("\155\157\x5f\x73\x61\155\x6c\x5f\x6d\145\x73\x73\141\147\145", "\x49\x6e\166\141\154\151\144\40\x63\145\x72\164\151\146\x69\143\x61\x74\145\x3a\x20\x50\154\x65\x61\163\145\x20\160\162\157\166\x69\x64\x65\40\x61\40\166\x61\x6c\151\144\40\143\x65\162\164\151\x66\x69\x63\x61\x74\145\x2e");
            SAMLSPUtilities::mo_saml_show_error_message();
            return;
            G7:
            Ba:
        }
        uP:
        ZF:
        if (!empty($CF)) {
            goto tz;
        }
        update_option("\155\x6f\x5f\x73\141\155\154\x5f\155\x65\163\163\141\x67\145", "\x49\156\x76\141\154\151\x64\40\143\x65\x72\x74\151\146\151\x63\x61\164\x65\x3a\x20\x50\x6c\x65\x61\163\x65\40\x70\x72\x6f\166\151\144\x65\40\x61\40\166\x61\154\151\144\40\x63\145\x72\164\151\x66\151\x63\x61\164\x65\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        tz:
        $gu[$RF] = array("\151\144\160\137\x6e\x61\x6d\x65" => sanitize_text_field(wp_unslash($RF)), "\x69\x64\160\x5f\x64\151\163\160\x6c\x61\x79\x5f\156\141\155\x65" => sanitize_text_field(wp_unslash($K3)), "\151\144\x70\137\x65\156\x74\151\x74\x79\137\151\144" => sanitize_text_field($uo), "\x73\141\155\154\x5f\163\x70\x5f\145\156\164\151\x74\x79\137\x69\x64" => sanitize_text_field($tg), "\163\x73\157\x5f\x75\x72\x6c" => esc_url_raw($tX), "\163\x73\157\137\142\x69\156\x64\x69\156\147\x5f\x74\171\160\x65" => sanitize_text_field($Kp), "\163\x6c\x6f\137\x75\162\x6c" => esc_url_raw($XM), "\163\x6c\x6f\137\x72\x65\163\x70\157\x6e\163\145\137\165\162\x6c" => esc_url_raw($Dc), "\x73\154\x6f\x5f\x62\x69\x6e\144\151\156\x67\x5f\x74\x79\x70\145" => sanitize_text_field($uN), "\170\x35\x30\71\137\143\x65\162\x74\x69\146\151\143\x61\164\145" => $CF, "\x72\x65\163\x70\x6f\156\x73\145\137\163\x69\147\156\145\144" => sanitize_text_field($EH), "\141\x73\x73\x65\162\x74\151\x6f\156\137\163\x69\147\156\x65\x64" => sanitize_text_field($td), "\x72\x65\x71\x75\145\163\164\x5f\x73\151\147\156\x65\144" => sanitize_text_field($ML), "\x6e\141\155\x65\x69\x64\137\146\157\x72\155\141\164" => sanitize_text_field($nq), "\x6d\x6f\x5f\x73\141\x6d\x6c\137\x65\x6e\143\157\x64\x69\156\147\x5f\145\156\141\x62\x6c\x65\x64" => sanitize_text_field($sn), "\x6d\x6f\x5f\x73\141\x6d\154\x5f\141\x73\163\x65\x72\x74\x69\157\x6e\137\x74\x69\155\145\137\166\141\154\151\x64\x69\164\171" => sanitize_text_field($Pn), "\163\141\x6d\x6c\x5f\151\144\x65\x6e\x74\x69\164\171\137\x70\162\157\x76\x69\x64\145\x72\137\147\165\151\144\x65\x5f\x6e\x61\155\x65" => sanitize_text_field($rR), "\x73\141\155\x6c\137\160\x77\137\x72\x65\163\x65\164\137\165\162\x6c" => esc_url_raw($kh), "\x65\x6e\141\x62\154\145\137\x69\144\160" => $MW);
        SAMLSPUtilities::mo_saml_update_selected_idp(array($gu[$RF]));
        $gu[$RF] = SAMLSPUtilities::mo_saml_array_merge($gu[$RF], $vs);
        $gu[$RF] = SAMLSPUtilities::mo_saml_array_merge($gu[$RF], $nG);
        $p6[$K3] = $RF;
        $gu = array_filter($gu, "\x66\x69\x6c\164\145\x72\x5f\145\x6d\x70\164\171\137\166\141\154\165\x65\x73");
        $s6->mo_save_environment_settings("\163\x61\x6d\154\x5f\151\x64\145\x6e\x74\151\164\x79\137\x70\162\x6f\166\151\x64\145\x72\x73", $gu);
        $s6->mo_save_environment_settings("\155\157\x5f\163\141\155\154\x5f\x69\144\x70\137\x6e\x61\155\x65\x5f\151\x64\137\155\x61\x70", $p6);
        if (!(count($gu) == 1)) {
            goto qJ;
        }
        $s6->mo_save_environment_settings("\163\x61\155\x6c\x5f\x64\x65\x66\x61\165\x6c\x74\x5f\151\144\x70", $RF);
        qJ:
        update_option("\155\x6f\x5f\x73\x61\155\x6c\x5f\155\x65\x73\163\x61\x67\x65", "\x49\x64\x65\x6e\x74\151\164\x79\x20\x50\162\157\x76\151\x64\145\x72\x20\144\145\164\141\x69\154\163\x20\163\141\x76\x65\144\x20\163\x75\143\143\145\163\x73\146\165\154\154\171\x2e");
        SAMLSPUtilities::mo_saml_show_success_message();
        goto pV;
        g2:
        $oa = sanitize_text_field($_POST["\x6d\157\137\163\141\155\x6c\x5f\x63\x75\163\x74\157\x6d\x5f\x6c\157\147\151\x6e\137\164\145\170\164"]);
        $Da = sanitize_text_field($_POST["\x6d\157\x5f\x73\141\x6d\x6c\x5f\143\x75\x73\164\157\155\x5f\x67\162\x65\145\164\151\156\147\137\164\145\x78\x74"]);
        $fc = sanitize_text_field($_POST["\x6d\157\137\163\x61\x6d\154\137\147\x72\x65\x65\x74\151\156\x67\137\156\x61\155\x65"]);
        $Qz = sanitize_text_field($_POST["\155\x6f\137\x73\141\x6d\154\x5f\x63\x75\163\x74\157\155\137\x6c\x6f\147\x6f\165\164\x5f\x74\145\x78\164"]);
        $vs = array("\x63\165\163\164\x6f\155\137\x6c\157\x67\x69\x6e\x5f\164\x65\x78\164" => $oa, "\x63\x75\x73\164\157\x6d\x5f\x67\162\x65\145\164\x69\x6e\147\137\164\145\x78\164" => $Da, "\x67\162\x65\x65\x74\x69\x6e\x67\x5f\x6e\x61\155\145" => $fc, "\x63\x75\163\164\157\155\137\154\157\147\x6f\x75\x74\x5f\164\x65\170\x74" => $Qz);
        $YS = !empty($_POST["\x6d\x6f\x5f\x73\x61\x6d\154\137\141\x70\160\x6c\x79\137\x77\x69\144\x67\x65\x74\x5f\143\x6f\156\146\151\x67\x5f\164\157\137\x61\x6c\154\x5f\x69\144\160\163"]) ? "\x74\162\x75\145" : "\146\x61\x6c\x73\145";
        if ($YS == "\x74\162\x75\145") {
            goto GS;
        }
        $gu[$B8] = SAMLSPUtilities::mo_saml_array_merge($gu[$B8], $vs);
        goto e3;
        GS:
        $gu[$B8]["\x63\165\163\x74\x6f\x6d\x5f\154\x6f\147\x69\x6e\137\164\145\x78\164"] = $oa;
        foreach ($gu as $R2 => $EB) {
            $gu[$R2]["\143\x75\x73\164\157\x6d\x5f\x67\x72\145\x65\164\x69\156\x67\137\x74\x65\x78\164"] = $Da;
            $gu[$R2]["\147\x72\x65\145\164\x69\156\147\137\156\141\155\145"] = $fc;
            $gu[$R2]["\x63\x75\x73\x74\x6f\x6d\x5f\154\x6f\x67\x6f\x75\x74\137\x74\145\x78\x74"] = $Qz;
            oW:
        }
        e_:
        e3:
        $gu = array_filter($gu, "\146\x69\154\164\145\162\x5f\145\155\160\x74\x79\137\x76\141\x6c\165\145\x73");
        $s6->mo_save_environment_settings("\163\x61\155\154\137\x69\144\x65\x6e\x74\x69\164\x79\x5f\160\x72\157\166\x69\x64\x65\162\163", $gu);
        $s6->mo_save_environment_settings("\x6d\x6f\x5f\163\141\x6d\x6c\x5f\141\x70\160\x6c\x79\137\167\151\x64\x67\x65\164\137\x63\x6f\x6e\146\151\147\x5f\x74\157\x5f\x61\x6c\x6c\x5f\x69\144\160\x73", $YS);
        update_option("\x6d\157\137\x73\141\155\154\137\x6d\145\163\163\141\147\145", "\103\165\x73\164\x6f\x6d\x20\x57\x69\x64\x67\x65\164\x20\144\145\x74\141\151\x6c\x73\x20\x73\x61\166\x65\144\40\163\165\x63\143\145\163\x73\x66\165\x6c\x6c\171\x2e");
        SAMLSPUtilities::mo_saml_show_success_message();
        goto pV;
        Ed:
        $HE = isset($_POST["\x6d\x6f\x5f\163\x61\x6d\154\137\163\x65\x6c\145\143\x74\145\x64\137\x64\145\146\141\165\154\164\x5f\151\144\x70"]) ? sanitize_text_field($_POST["\x6d\157\137\163\141\x6d\x6c\x5f\x73\145\154\x65\143\164\145\144\x5f\144\145\x66\141\x75\x6c\164\137\151\144\160"]) : '';
        $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\163\x61\155\154\x5f\151\x64\x65\x6e\x74\151\164\171\x5f\x70\162\x6f\166\x69\144\145\162\163", true);
        $vX = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\x6d\154\137\x64\145\x66\141\x75\154\164\137\x69\144\x70");
        if (!(in_array($vX, $kd) && count($rK) !== count($kd))) {
            goto lD;
        }
        if (!empty($HE) && array_key_exists($HE, $rK)) {
            goto E6;
        }
        if (count($rK) > 1) {
            goto OP;
        }
        goto cZ;
        E6:
        if ($rK[$HE]["\145\x6e\141\142\x6c\x65\x5f\151\x64\160"]) {
            goto Yr;
        }
        $rK[$HE]["\145\156\x61\142\154\x65\x5f\x69\x64\x70"] = true;
        $s6->mo_save_environment_settings("\x73\141\x6d\154\x5f\151\x64\145\x6e\164\x69\x74\x79\137\160\x72\x6f\x76\151\144\145\162\x73", $rK);
        Yr:
        $s6->mo_save_environment_settings("\x73\141\155\154\x5f\x64\x65\146\141\x75\x6c\x74\x5f\151\144\x70", $HE);
        goto cZ;
        OP:
        update_option("\155\x6f\137\x73\141\x6d\154\137\155\x65\x73\163\141\x67\145", "\120\154\145\141\163\x65\40\143\150\141\x6e\x67\x65\x20\164\x68\145\x20\x64\145\146\141\x75\154\164\40\x49\104\120\x20\x62\x65\146\157\162\x65\x20\x64\x65\154\x65\164\x69\x6e\x67\40\151\x74\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        cZ:
        lD:
        SAMLSPUtilities::mo_saml_delete_idp_configuration($kd, $rK, $p6);
        pV:
        gJ:
        if (!self::mo_check_option_admin_referer("\x6d\157\x5f\x73\x61\x6d\154\x5f\141\164\x74\162\137\x72\157\x6c\145\137\163\145\154\x65\143\x74\x65\x64\x5f\151\144\160\x5f\x66\x6f\162\155")) {
            goto zh;
        }
        if (!empty($_POST["\x6d\x6f\137\x73\141\x6d\154\137\x61\x74\x74\x72\x5f\162\157\x6c\x65\x5f\163\145\154\145\143\164\x65\x64\137\x69\x64\160"])) {
            goto di;
        }
        update_option("\x6d\157\x5f\163\x61\x6d\x6c\137\x6d\x65\x73\x73\141\x67\x65", "\x53\157\x6d\x65\x74\x68\151\x6e\147\40\x77\x65\x6e\164\x20\167\x72\x6f\x6e\x67\41");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto yP;
        di:
        $s6->mo_save_environment_settings("\x6d\x6f\137\x73\141\x6d\x6c\x5f\141\164\x74\162\x5f\162\x6f\154\x65\x5f\x73\x65\154\145\x63\164\x65\144\x5f\x69\x64\x70", sanitize_text_field(trim($_POST["\155\x6f\x5f\163\141\x6d\154\x5f\x61\164\x74\162\137\162\157\154\x65\x5f\163\x65\154\145\x63\x74\x65\144\x5f\151\x64\x70"])));
        update_option("\x6d\157\x5f\163\x61\155\154\x5f\x6d\145\x73\x73\x61\x67\x65", "\x49\104\120\40\163\145\154\x65\143\164\x65\144\x20\163\x75\x63\143\145\163\x73\146\165\154\154\x79");
        SAMLSPUtilities::mo_saml_show_success_message();
        yP:
        zh:
        if (!self::mo_check_option_admin_referer("\x6d\x6f\x5f\163\141\155\154\x5f\141\x74\x74\x72\x69\x62\165\x74\145\137\155\141\160\160\x69\156\x67\137\x66\157\162\155")) {
            goto li;
        }
        $gu = EnvironmentHelper::getOptionForSelectedEnvironment("\163\x61\155\154\x5f\x69\x64\145\x6e\164\151\x74\x79\x5f\x70\x72\157\166\151\x64\145\162\163", true);
        $ey = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\x6f\137\x73\141\x6d\154\137\141\x74\164\162\151\x62\165\x74\145\137\x6d\141\160\160\x69\x6e\147", true);
        $BB = !empty($_POST["\x73\x65\154\145\x63\x74\x65\x64\137\x69\x64\160\137\156\141\155\x65"]) ? trim(sanitize_text_field($_POST["\x73\x65\x6c\145\143\164\145\144\137\151\144\x70\x5f\x6e\x61\x6d\x65"])) : EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\x73\x61\x6d\154\137\141\x74\x74\162\137\162\157\154\145\137\x73\145\x6c\x65\x63\164\x65\144\137\151\144\160");
        $s6->mo_save_environment_settings("\x6d\157\137\x73\x61\x6d\x6c\x5f\141\x74\164\162\137\162\157\x6c\145\x5f\x73\x65\x6c\x65\x63\164\x65\x64\x5f\x69\144\x70", $BB);
        if (!empty($gu[$BB]) || "\x44\105\x46\x41\125\114\124" === $BB) {
            goto Ny;
        }
        update_option("\155\157\x5f\163\141\x6d\154\x5f\155\x65\x73\163\141\147\145", "\x53\x6f\155\x65\x74\150\151\x6e\147\x20\x77\145\x6e\164\x20\167\162\157\156\x67\x21");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto rt;
        Ny:
        if (!empty(trim($_POST["\x6d\157\x5f\163\x61\x6d\154\137\141\x6d\x5f\165\163\x65\162\156\x61\x6d\145"])) && !empty(trim($_POST["\x6d\157\137\163\141\155\154\x5f\141\x6d\137\x65\155\141\x69\x6c"]))) {
            goto kx;
        }
        update_option("\155\x6f\x5f\163\x61\x6d\154\x5f\155\x65\x73\163\x61\x67\145", "\x50\154\145\x61\163\145\40\x70\162\157\166\x69\x64\x65\40\141\40\x76\141\154\151\144\40\x76\x61\154\x75\x65\x20\x66\157\162\40\x75\x73\x65\162\156\x61\x6d\145\40\x61\156\144\40\x65\x6d\x61\x69\154\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        goto ws;
        kx:
        $ey[$BB]["\x75\x73\x65\162\156\141\x6d\x65"] = trim(sanitize_text_field($_POST["\x6d\x6f\137\163\x61\155\154\137\x61\155\x5f\x75\x73\x65\x72\156\141\155\x65"]));
        $ey[$BB]["\145\x6d\141\x69\x6c"] = trim(sanitize_text_field($_POST["\155\157\x5f\x73\x61\x6d\x6c\137\x61\x6d\137\145\155\x61\x69\154"]));
        $ey[$BB]["\146\x69\162\x73\164\137\x6e\141\155\145"] = trim(sanitize_text_field($_POST["\x6d\x6f\x5f\163\141\155\x6c\x5f\141\x6d\x5f\x66\x69\162\x73\164\137\x6e\141\x6d\x65"]));
        $ey[$BB]["\x6c\141\163\x74\x5f\156\141\155\145"] = trim(sanitize_text_field($_POST["\155\x6f\137\x73\x61\155\x6c\x5f\141\x6d\x5f\154\141\x73\164\137\x6e\141\155\x65"]));
        $ey[$BB]["\144\151\163\x70\x6c\141\171\x5f\156\141\155\145"] = trim(sanitize_text_field($_POST["\155\x6f\x5f\x73\141\155\x6c\137\x61\155\137\x64\151\163\160\154\141\171\137\156\x61\155\145"]));
        $ey[$BB]["\156\x69\143\x6b\x5f\156\141\x6d\x65"] = trim(sanitize_text_field($_POST["\x6d\157\137\163\141\155\x6c\137\x61\155\137\156\x69\143\153\137\156\141\155\x65"]));
        ws:
        $ey[$BB]["\x64\x6f\x5f\156\157\164\137\165\160\144\x61\x74\145\x5f\x64\151\x73\x70\154\x61\171\x5f\156\141\x6d\145"] = isset($_POST["\x6d\x6f\137\x73\x61\x6d\154\137\x64\157\x5f\156\157\164\137\x75\x70\144\141\x74\145\137\x64\x69\163\x70\154\141\x79\137\156\x61\x6d\145"]) ? sanitize_text_field($_POST["\x6d\157\137\x73\141\155\154\x5f\x64\x6f\137\156\157\164\x5f\165\x70\144\141\164\145\x5f\144\x69\x73\x70\154\x61\171\x5f\x6e\141\x6d\x65"]) : '';
        $v8 = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\x5f\163\x61\x6d\154\137\x63\165\163\164\157\x6d\x5f\x61\164\x74\x72\x73\x5f\155\x61\x70\x70\x69\x6e\x67", true);
        $GJ = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\155\x6c\137\141\x74\x74\162\x73\x5f\x74\x6f\x5f\x64\151\163\160\x6c\141\171\137\151\144\x70", true);
        $Sm = !empty($_POST["\x6d\157\x5f\163\x61\x6d\154\x5f\143\165\163\x74\x6f\x6d\137\141\164\x74\162\x5f\x6b\145\171\163"]) ? $_POST["\155\157\137\163\141\155\154\137\x63\165\x73\x74\157\155\137\x61\164\x74\162\137\x6b\145\x79\163"] : array();
        $Q4 = !empty($_POST["\155\x6f\137\x73\141\155\154\x5f\143\165\x73\x74\157\x6d\137\x61\x74\x74\x72\137\166\x61\x6c\165\145\x73"]) ? $_POST["\155\157\x5f\x73\x61\x6d\x6c\x5f\143\165\x73\164\157\155\137\141\164\164\x72\x5f\x76\x61\x6c\x75\x65\163"] : array();
        $Rs = !empty($_POST["\x6d\x6f\137\x73\141\x6d\154\x5f\163\150\157\167\137\143\x75\x73\x74\x6f\x6d\137\141\164\x74\162\163"]) ? $_POST["\155\157\137\163\x61\x6d\x6c\137\163\150\x6f\x77\137\x63\165\x73\164\157\x6d\137\141\164\x74\162\x73"] : array();
        $Uq = array();
        $IJ = array();
        $Ev = 0;
        foreach ($Sm as $R2 => $EB) {
            if (!(!empty(trim($EB)) && !empty(trim($Q4[$R2])))) {
                goto mM;
            }
            $IJ[sanitize_text_field(trim($EB))] = sanitize_text_field(trim($Q4[$R2]));
            if (!SAMLSPUtilities::mo_saml_in_array($R2, $Rs)) {
                goto ug;
            }
            array_push($Uq, $Ev);
            ug:
            ++$Ev;
            mM:
            Y2:
        }
        Gw:
        $GJ[$BB] = $Uq;
        $v8[$BB] = $IJ;
        $s6->mo_save_environment_settings("\x6d\x6f\137\x73\141\x6d\154\137\141\164\x74\162\x69\x62\x75\x74\x65\x5f\x6d\x61\x70\x70\151\x6e\147", $ey);
        $s6->mo_save_environment_settings("\155\x6f\x5f\x73\x61\x6d\x6c\137\143\165\x73\x74\x6f\x6d\137\x61\164\164\x72\x73\137\x6d\x61\x70\x70\151\x6e\147", $v8);
        $s6->mo_save_environment_settings("\163\x61\155\x6c\x5f\x61\x74\164\162\163\x5f\164\x6f\137\x64\x69\163\x70\x6c\x61\171\x5f\x69\x64\160", $GJ);
        $h9 = "\101\x74\164\162\151\142\x75\164\145\40\x4d\141\x70\x70\x69\156\x67\40\x73\x61\x76\x65\x64\40\x73\x75\143\143\145\x73\163\146\165\x6c\x6c\171\x2e";
        update_option("\155\x6f\x5f\163\141\155\154\137\155\145\x73\163\x61\147\145", $h9);
        SAMLSPUtilities::mo_saml_show_success_message();
        rt:
        li:
        if (!self::mo_check_option_admin_referer("\155\x6f\137\163\141\155\154\x5f\x72\145\x73\x65\164\x5f\x61\164\x74\162\151\x62\x75\164\x65\137\162\x6f\154\145")) {
            goto NV;
        }
        $fg = !empty($_POST["\163\x65\x6c\145\x63\164\x65\144\x5f\151\144\x70\137\x6e\141\155\145"]) ? trim(sanitize_text_field($_POST["\x73\x65\154\145\143\x74\145\144\x5f\151\x64\160\137\156\x61\x6d\x65"])) : EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\x6f\x5f\x73\x61\x6d\x6c\137\141\x74\x74\x72\137\162\157\154\145\137\163\x65\154\x65\143\x74\x65\x64\x5f\x69\144\x70");
        $ey = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\163\x61\155\154\137\141\164\164\162\151\142\x75\x74\x65\x5f\x6d\141\x70\x70\151\156\x67", true);
        $v8 = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\x6f\x5f\x73\141\x6d\x6c\x5f\x63\x75\163\164\x6f\155\x5f\141\x74\x74\x72\x73\x5f\155\x61\x70\160\151\x6e\147", true);
        $GJ = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\x6d\x6c\137\141\x74\164\x72\163\x5f\x74\157\137\144\x69\163\x70\154\141\171\137\x69\144\x70", true);
        $s6->mo_save_environment_settings("\x6d\x6f\137\x73\141\x6d\x6c\x5f\141\x74\x74\x72\x5f\x72\157\x6c\145\137\163\x65\x6c\x65\x63\164\145\144\x5f\x69\x64\160", $fg);
        unset($ey[$fg]);
        unset($v8[$fg]);
        unset($GJ[$fg]);
        $s6->mo_save_environment_settings("\x6d\157\x5f\163\x61\155\154\137\x61\x74\x74\162\151\x62\165\x74\x65\137\155\x61\x70\160\x69\x6e\x67", $ey);
        $s6->mo_save_environment_settings("\x6d\x6f\x5f\163\x61\x6d\x6c\137\x63\x75\163\x74\x6f\x6d\x5f\x61\x74\x74\162\163\137\155\x61\x70\x70\x69\x6e\x67", $v8);
        $s6->mo_save_environment_settings("\x73\141\155\154\x5f\141\x74\x74\x72\x73\x5f\x74\157\x5f\x64\x69\x73\160\154\x61\171\137\151\x64\x70", $GJ);
        $Qo = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\155\x6c\x5f\151\144\x70\x5f\x61\164\164\162\151\x62\165\164\x65\137\x6d\x61\160\160\151\156\x67", true);
        if (empty(array_filter($Qo))) {
            goto Ty;
        }
        unset($Qo[$fg]);
        $s6->mo_save_environment_settings("\x73\x61\155\x6c\x5f\x69\x64\x70\x5f\x61\x74\164\162\x69\142\x75\164\x65\137\x6d\141\x70\160\151\156\x67", $Qo);
        Ty:
        update_option("\x6d\157\x5f\163\141\155\x6c\137\x6d\x65\163\x73\141\x67\145", "\x41\x74\164\162\x69\x62\165\x74\x65\40\x4d\141\160\160\151\156\x67\40\103\157\x6e\x66\151\x67\x75\162\x61\164\x69\157\x6e\163\40\162\x65\163\145\164\x20\x73\x75\x63\143\145\x73\163\x66\x75\154\154\x79\x2e");
        SAMLSPUtilities::mo_saml_show_success_message();
        NV:
        if (!self::mo_check_option_admin_referer("\x6d\157\x5f\163\x61\x6d\154\137\145\156\141\x62\154\145\x5f\154\x6f\x67\151\156\137\x72\x65\x64\151\162\145\143\164\x5f\x6f\x70\164\151\157\156")) {
            goto Un;
        }
        if (mo_saml_is_sp_configured()) {
            goto Ew;
        }
        update_option("\155\157\137\x73\x61\x6d\154\x5f\x6d\145\163\163\141\147\x65", "\x50\x6c\145\141\x73\145\x20\x63\x6f\x6d\x70\x6c\x65\164\x65\x20" . addLink("\x53\x65\162\x76\x69\x63\145\40\x50\x72\x6f\x76\151\x64\x65\162", add_query_arg(array("\x74\141\142" => "\x73\141\166\145"), $_SERVER["\x52\x45\x51\x55\x45\x53\124\x5f\125\x52\x49"])) . "\x20\143\x6f\x6e\146\151\x67\165\162\141\x74\151\157\x6e\40\146\x69\x72\x73\164\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto D_;
        Ew:
        if (!empty($_POST["\x6d\x6f\x5f\163\141\155\154\x5f\x65\x6e\141\142\x6c\145\x5f\x6c\157\147\151\156\x5f\162\x65\x64\x69\162\x65\x63\x74"])) {
            goto Lk;
        }
        $kn = "\146\141\x6c\163\145";
        goto V4;
        Lk:
        $kn = htmlspecialchars($_POST["\x6d\x6f\x5f\163\x61\155\x6c\137\145\x6e\x61\142\154\x65\x5f\x6c\x6f\x67\x69\156\137\x72\145\x64\x69\x72\x65\x63\164"]);
        V4:
        if ($kn == "\x74\x72\165\145") {
            goto Wz;
        }
        $s6->mo_save_environment_settings("\x6d\x6f\137\163\141\155\154\x5f\x65\156\x61\x62\x6c\x65\137\154\157\147\151\x6e\x5f\x72\145\144\x69\162\145\143\164", '');
        goto EI;
        Wz:
        $s6->mo_save_environment_settings("\x6d\x6f\137\163\141\x6d\154\x5f\145\x6e\x61\142\x6c\x65\137\x6c\x6f\x67\x69\156\137\x72\x65\144\151\162\x65\143\164", "\x74\162\x75\145");
        $s6->mo_save_environment_settings("\x6d\x6f\137\163\x61\155\154\137\141\x6c\x6c\x6f\167\x5f\x77\160\x5f\163\151\147\156\x69\x6e", "\x74\162\165\145");
        EI:
        update_option("\155\x6f\137\163\x61\x6d\x6c\x5f\x6d\145\x73\x73\141\x67\x65", "\123\x69\147\x6e\x20\151\156\40\x6f\x70\164\151\157\156\x73\40\x75\160\x64\x61\x74\145\144\56");
        SAMLSPUtilities::mo_saml_show_success_message();
        D_:
        Un:
        if (!self::mo_check_option_admin_referer("\155\157\x5f\x73\141\155\x6c\137\x65\x6e\x61\x62\154\145\x5f\150\x69\x64\x65\x5f\x77\x70\137\154\x6f\x67\151\156\137\157\x70\164\x69\x6f\156")) {
            goto tX;
        }
        if (mo_saml_is_sp_configured()) {
            goto GF;
        }
        update_option("\155\157\137\163\141\x6d\x6c\137\155\145\163\163\141\147\x65", "\x50\x6c\145\x61\163\145\40\143\x6f\x6d\x70\x6c\145\x74\x65\40" . addLink("\123\x65\x72\x76\151\143\x65\x20\x50\x72\x6f\166\151\144\145\162", add_query_arg(array("\164\x61\142" => "\163\x61\x76\x65"), $_SERVER["\x52\x45\x51\x55\105\123\124\x5f\x55\x52\x49"])) . "\40\143\x6f\156\x66\151\147\x75\x72\x61\x74\x69\157\156\x20\x66\x69\x72\x73\x74\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto gQ;
        GF:
        if (!empty($_POST["\x6d\157\137\x73\141\155\x6c\x5f\x65\x6e\141\142\154\145\137\x68\x69\144\x65\x5f\167\160\137\154\x6f\147\151\156"])) {
            goto qP;
        }
        $Kh = "\146\x61\154\163\145";
        goto BL;
        qP:
        if (!(Mo_Saml_Hide_WP_Login_Handler::mo_saml_is_sso_button_disabled() <= 0)) {
            goto an;
        }
        update_option("\155\157\137\163\141\155\x6c\x5f\155\x65\x73\163\x61\x67\145", "\x59\157\x75\40\x6d\165\163\x74\40\141\144\144\40\74\142\76\123\123\x4f\x20\x62\165\x74\x74\157\x6e\x28\163\51\x3c\57\x62\76\x20\x74\x6f\40\x74\x68\145\x20\154\x6f\x67\151\x6e\40\160\141\147\145\40\x62\x65\146\x6f\x72\x65\x20\x68\151\144\151\156\x67\40\x74\x68\x65\40\144\145\146\x61\165\154\x74\x20\127\x6f\x72\144\120\162\145\x73\163\x20\x6c\157\x67\x69\156\x20\x66\157\x72\x6d\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        an:
        $Kh = htmlspecialchars($_POST["\x6d\x6f\x5f\163\x61\x6d\154\137\145\156\141\142\154\145\x5f\x68\151\144\x65\x5f\x77\160\x5f\154\x6f\x67\x69\156"]);
        BL:
        if ($Kh == "\x74\162\165\x65") {
            goto Fg;
        }
        $s6->mo_save_environment_settings("\155\157\137\x73\x61\155\x6c\137\x65\156\141\142\x6c\145\137\x68\151\144\x65\x5f\167\160\x5f\154\157\147\151\x6e", '');
        goto ph;
        Fg:
        $s6->mo_save_environment_settings("\x6d\157\x5f\163\x61\x6d\x6c\137\x65\x6e\141\142\154\145\137\x68\151\x64\145\137\167\x70\137\x6c\x6f\x67\x69\156", "\x74\162\x75\145");
        $s6->mo_save_environment_settings("\155\157\x5f\163\x61\x6d\154\137\141\x6c\154\x6f\x77\137\x77\160\x5f\163\x69\x67\156\x69\156", "\x74\x72\x75\145");
        ph:
        update_option("\155\x6f\x5f\163\141\x6d\154\x5f\x6d\x65\x73\163\141\147\x65", "\110\x69\144\145\57\104\x69\163\x61\x62\x6c\145\40\127\x6f\x72\x64\x50\x72\x65\163\x73\x20\104\145\x66\141\165\154\164\40\x4c\x6f\x67\151\x6e\40\x46\x6f\162\x6d\x20\x6f\160\164\x69\x6f\x6e\40\165\160\x64\x61\x74\x65\144\x20\163\x75\143\143\145\x73\x73\146\165\154\154\171\56");
        SAMLSPUtilities::mo_saml_show_success_message();
        gQ:
        tX:
        if (!self::mo_check_option_admin_referer("\155\x6f\x5f\x73\141\x6d\x6c\137\x72\145\x73\x65\x74\x5f\162\x6f\154\x65\x5f\155\x61\160\x70\x69\156\x67")) {
            goto rO;
        }
        $fg = !empty($_POST["\163\x65\x6c\145\x63\x74\145\x64\x5f\151\x64\x70\137\156\x61\x6d\145"]) ? trim(sanitize_text_field($_POST["\x73\145\x6c\145\x63\x74\145\144\137\151\144\160\x5f\156\x61\x6d\x65"])) : EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\x5f\x73\141\155\x6c\x5f\141\164\x74\162\x5f\162\x6f\x6c\x65\137\x73\145\154\x65\143\164\145\144\x5f\x69\x64\x70");
        $aD = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\163\141\155\154\137\162\x6f\154\145\x5f\x6d\x61\160\160\x69\156\147\x5f\143\157\156\x66\x69\x67\165\162\141\x74\151\x6f\156\163", true);
        $H9 = EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\137\163\141\x6d\x6c\137\x63\x6f\x6e\x66\151\147\165\x72\x65\144\137\x72\x6f\154\x65\137\166\141\x6c\x75\x65\163", true);
        $s6->mo_save_environment_settings("\x6d\x6f\x5f\163\x61\x6d\154\137\x61\x74\164\162\x5f\162\x6f\x6c\145\137\x73\x65\x6c\x65\x63\x74\x65\x64\137\x69\144\x70", $fg);
        unset($aD[$fg]);
        unset($H9[$fg]);
        $s6->mo_save_environment_settings("\155\157\x5f\163\141\155\154\137\162\x6f\x6c\x65\137\x6d\141\x70\160\x69\156\147\x5f\x63\x6f\156\146\151\x67\x75\162\141\x74\x69\157\156\x73", $aD);
        $s6->mo_save_environment_settings("\155\x6f\137\x73\x61\155\x6c\x5f\x63\x6f\156\146\151\147\x75\x72\145\144\137\x72\x6f\x6c\x65\137\x76\x61\x6c\165\x65\x73", $H9);
        $U6 = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\x6d\x6c\x5f\151\x64\160\137\x72\x6f\x6c\145\x5f\x6d\x61\160\160\151\x6e\x67", true);
        if (empty(array_filter($U6))) {
            goto Il;
        }
        unset($U6[$fg]);
        $s6->mo_save_environment_settings("\163\x61\x6d\154\x5f\151\144\x70\x5f\x72\157\154\x65\137\155\x61\160\x70\151\x6e\x67", $U6);
        Il:
        update_option("\x6d\x6f\x5f\x73\x61\155\154\137\155\145\163\163\141\147\x65", "\x52\157\x6c\145\x20\x4d\x61\160\160\x69\156\147\x20\143\157\x6e\x66\151\147\x75\x72\141\164\151\157\156\163\40\162\145\x73\145\164\x20\163\x75\143\x63\145\x73\163\x66\165\154\154\x79\x2e");
        SAMLSPUtilities::mo_saml_show_success_message();
        rO:
        if (!self::mo_check_option_admin_referer("\x6d\157\x5f\163\141\155\154\x5f\x61\164\x74\x72\x5f\162\x6f\154\x65\137\x61\x64\x76\141\156\x63\145\x64\137\x73\x65\164\x74\x69\x6e\147\163\x5f\x66\157\x72\x6d")) {
            goto Cj;
        }
        $Jr = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\x6f\x5f\x73\141\x6d\154\x5f\x61\164\164\x72\x5f\x72\157\154\145\x5f\x61\144\166\x61\156\143\145\144\137\163\x65\x74\164\151\x6e\147\163", true);
        $fg = !empty($_POST["\163\145\x6c\x65\x63\164\145\144\137\x69\x64\160\x5f\156\x61\155\145"]) ? trim(sanitize_text_field($_POST["\x73\x65\154\145\143\164\145\144\x5f\x69\x64\x70\137\156\141\x6d\x65"])) : EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\163\x61\x6d\x6c\x5f\x61\x74\x74\x72\137\x72\157\154\x65\x5f\163\x65\x6c\x65\x63\164\x65\x64\137\151\144\160");
        $s6->mo_save_environment_settings("\155\x6f\137\163\x61\x6d\154\x5f\141\x74\x74\x72\x5f\x72\157\x6c\145\x5f\x73\145\154\x65\x63\x74\x65\144\x5f\x69\x64\160", $fg);
        $fo = isset($Jr[$fg]) ? $Jr[$fg] : array();
        $fo["\x64\157\x5f\156\x6f\164\x5f\143\x72\x65\x61\164\145\x5f\156\x65\x77\x5f\165\163\145\162\163"] = !empty($_POST["\155\157\137\x73\x61\155\154\137\144\157\x6e\x74\137\143\x72\x65\x61\x74\x65\137\x6e\x65\x77\137\165\x73\x65\x72\x73"]) ? sanitize_text_field(trim($_POST["\x6d\x6f\137\x73\141\x6d\154\137\144\157\156\x74\x5f\143\162\x65\x61\164\145\137\156\x65\x77\x5f\165\x73\x65\x72\163"])) : '';
        $fo["\x6b\145\145\x70\137\x65\x78\151\163\x74\x69\156\x67\137\165\163\x65\162\163\x5f\x72\x6f\x6c\145"] = !empty($_POST["\155\x6f\x5f\x73\x61\155\154\137\144\157\x5f\x6e\157\x74\x5f\165\x70\x64\x61\x74\145\137\x65\170\x69\x73\x74\x69\156\x67\x5f\165\163\145\162"]) ? sanitize_text_field(trim($_POST["\155\x6f\x5f\x73\141\x6d\154\x5f\144\157\x5f\x6e\157\x74\x5f\165\x70\144\141\x74\x65\137\145\170\151\163\164\151\x6e\147\137\165\163\x65\162"])) : '';
        if (SAMLSPUtilities::mo_saml_is_plugin_active("\x6d\151\156\x69\157\x72\x61\x6e\147\x65\55\141\x64\x76\141\156\143\145\x64\x2d\x72\x6f\x6c\145\55\155\x61\x70\x70\x69\x6e\147\x2f\141\x64\x76\141\x6e\143\145\144\x2d\x72\157\x6c\x65\55\155\141\160\160\151\x6e\147\56\x70\x68\160")) {
            goto XX;
        }
        $fo["\x61\x6c\154\x6f\167\x5f\x64\x65\x6e\171\x5f\165\x73\145\x72\137\141\164\x74\162\x69\x62\165\x74\145"] = !empty($_POST["\141\x6c\154\157\167\137\144\x65\x6e\x79\137\x69\x64\x70\137\x67\x72\x6f\165\160\137\x61\x74\x74\x72\151\x62\x75\164\x65"]) ? sanitize_text_field(trim($_POST["\x61\154\x6c\x6f\167\x5f\144\x65\156\171\x5f\x69\x64\x70\x5f\x67\162\157\x75\160\137\141\164\x74\162\151\x62\165\x74\x65"])) : '';
        if (empty($_POST["\x61\x6c\x6c\x6f\x77\137\x64\x65\x6e\x79\x5f\x69\144\x70\x5f\x67\x72\x6f\x75\160\x5f\141\164\164\x72\x69\x62\165\x74\145"])) {
            goto t2;
        }
        if (!empty($_POST["\155\157\137\x73\x61\x6d\154\137\x61\x74\164\x72\137\x72\145\x73\164\x72\151\143\x74\151\x6f\x6e\137\147\x72\x6f\x75\x70"])) {
            goto hd;
        }
        update_option("\x6d\x6f\137\x73\x61\x6d\154\x5f\155\145\x73\x73\141\x67\x65", "\x50\154\145\141\x73\145\40\163\x65\x6c\x65\x63\x74\x20\x6f\x72\40\145\156\164\x65\162\40\x74\150\145\40\x49\104\x50\40\147\x72\157\x75\160\40\141\x74\x74\162\151\142\165\x74\145\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        hd:
        if (!empty($_POST["\x6d\x6f\137\x73\x61\x6d\154\137\x61\x74\x74\162\137\162\145\x73\164\x72\x69\x63\164\151\x6f\x6e\x5f\x76\141\154\165\145"])) {
            goto Iw;
        }
        update_option("\x6d\157\x5f\x73\141\x6d\x6c\137\155\x65\x73\x73\x61\x67\145", "\x50\154\145\x61\163\145\x20\x65\156\164\x65\162\x20\164\150\145\x20\x49\104\120\40\147\x72\157\x75\160\x20\x61\x74\164\x72\151\142\x75\164\x65\40\166\x61\154\x75\x65\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        Iw:
        $fo["\162\145\x73\164\162\151\143\164\145\144\x5f\141\164\x74\x72\151\x62\x75\164\x65"] = !empty($_POST["\x6d\x6f\137\x73\x61\x6d\x6c\x5f\x61\x74\x74\x72\137\162\x65\x73\x74\162\151\x63\164\x69\157\156\137\147\162\157\165\160"]) ? sanitize_text_field(trim($_POST["\x6d\x6f\x5f\163\141\x6d\x6c\137\x61\x74\164\162\137\162\x65\163\x74\x72\151\143\164\151\x6f\156\137\147\x72\157\x75\160"])) : '';
        $fo["\x72\x65\x73\x74\162\151\x63\x74\x65\x64\137\x61\x74\164\x72\151\x62\x75\164\x65\137\166\141\154\x75\145\163"] = !empty($_POST["\155\x6f\x5f\x73\x61\x6d\x6c\x5f\x61\x74\x74\162\x5f\x72\x65\x73\x74\162\151\143\x74\x69\x6f\156\137\166\141\154\x75\x65"]) ? sanitize_text_field(trim($_POST["\155\157\137\x73\x61\x6d\x6c\137\141\x74\164\162\x5f\x72\145\x73\x74\x72\x69\143\164\151\157\156\x5f\166\x61\x6c\165\x65"])) : '';
        $fo["\x61\154\x6c\x6f\167\x5f\x64\x65\156\171\x5f\x61\x74\164\x72\x5f\157\x70\164\x69\x6f\x6e"] = !empty($_POST["\x6d\157\137\x73\141\155\154\x5f\141\154\x6c\x6f\x77\x5f\144\145\x6e\x79\x5f\141\164\x74\x72\x5f\x72\145\163\x74\x72\151\x63\164\x69\157\x6e\x5f\x67\x72\x6f\165\160"]) ? sanitize_text_field(trim($_POST["\x6d\x6f\x5f\163\x61\x6d\x6c\137\141\154\154\x6f\167\137\144\145\x6e\x79\137\x61\164\x74\x72\137\162\x65\x73\x74\162\x69\x63\x74\x69\157\156\x5f\x67\x72\x6f\165\x70"])) : '';
        t2:
        if (empty($_POST["\x6d\157\137\x73\141\x6d\154\x5f\x64\157\137\156\157\164\x5f\x75\160\x64\x61\x74\145\137\x65\170\151\x73\164\x69\x6e\x67\137\x75\163\145\x72"])) {
            goto O3;
        }
        $fo["\x77\x68\151\164\x65\x6c\x69\x73\164\x5f\x65\170\151\x73\164\151\156\x67\137\165\x73\x65\x72\163\x5f\162\157\x6c\x65\x73"] = '';
        goto uJ;
        O3:
        $vC = isset($_POST["\155\157\137\x73\141\155\x6c\x5f\167\x68\x69\x74\x65\x6c\x69\x73\164\137\x65\x78\151\x73\164\151\156\x67\x5f\x75\163\x65\x72\x73\137\x72\x6f\x6c\145\x73"]) ? sanitize_text_field(trim($_POST["\155\157\x5f\163\141\155\154\x5f\167\x68\x69\164\145\154\151\163\164\x5f\x65\x78\x69\163\x74\x69\156\147\137\x75\163\x65\162\163\x5f\162\x6f\154\x65\163"])) : '';
        if (!("\x63\150\145\x63\153\145\144" === $vC)) {
            goto SG;
        }
        $QM = $_POST["\155\x6f\x5f\163\x61\x6d\x6c\x5f\167\x68\x69\164\x65\154\151\x73\x74\145\x64\137\x72\x6f\154\145\163"] ?? "\x6e\x6f\156\x65";
        if (!is_array($QM)) {
            goto vW;
        }
        $a6 = array_map("\163\141\x6e\x69\164\x69\172\x65\137\153\x65\x79", array_keys($QM));
        $R6 = array_map("\x73\x61\x6e\151\x74\x69\x7a\x65\x5f\x74\x65\170\x74\137\x66\151\x65\154\x64", $QM);
        $QM = array_combine($a6, $R6);
        vW:
        $fo["\x77\150\x69\x74\x65\x6c\151\x73\x74\x65\144\137\162\157\x6c\x65\163"] = $QM;
        SG:
        $fo["\x77\x68\151\164\x65\154\x69\x73\x74\x5f\145\x78\151\163\164\x69\x6e\x67\x5f\165\163\145\162\163\x5f\x72\x6f\154\145\x73"] = $vC;
        uJ:
        XX:
        $fo["\141\x6c\154\x6f\x77\137\144\x65\156\x79\x5f\x75\163\145\x72\x5f\x64\157\x6d\x61\x69\x6e"] = !empty($_POST["\141\154\x6c\157\x77\137\144\145\156\x79\137\165\163\x65\162\137\x64\157\155\141\151\x6e"]) ? sanitize_text_field(trim($_POST["\141\154\154\x6f\167\x5f\x64\x65\x6e\171\137\165\163\x65\x72\x5f\144\x6f\x6d\x61\x69\156"])) : '';
        if (empty($_POST["\141\x6c\154\x6f\167\137\144\x65\x6e\x79\137\165\x73\x65\162\137\144\157\155\141\x69\156"])) {
            goto HJ;
        }
        $nM = "\57\x5e\x5c\x73\x2a\50\x3f\72\x5b\141\x2d\x7a\x41\55\132\60\55\x39\x5d\53\x28\77\72\x2d\x5b\x61\x2d\x7a\101\x2d\x5a\x30\55\x39\x5d\53\51\52\50\x5c\x2e\133\x61\55\x7a\x41\55\132\60\55\x39\x5d\x2b\x28\77\72\55\133\141\x2d\x7a\101\x2d\x5a\60\x2d\71\135\53\x29\52\51\x2b\134\x73\52\73\x5c\163\52\51\52\x5b\141\55\172\x41\x2d\132\60\55\x39\135\53\50\77\x3a\55\133\141\x2d\x7a\101\55\x5a\x30\55\71\x5d\53\51\x2a\x28\x5c\x2e\133\x61\55\x7a\x41\55\132\x30\x2d\x39\x5d\x2b\50\77\x3a\55\x5b\x61\55\172\x41\x2d\x5a\x30\x2d\71\x5d\x2b\51\x2a\x29\x2b\x5c\x73\x2a\x3b\x3f\134\163\x2a\x24\x2f";
        if (empty($_POST["\x61\x6c\x6c\x6f\167\x5f\144\145\x6e\171\x5f\165\x73\145\162\137\144\157\155\x61\x69\x6e\x5f\166\x61\154\x75\x65"])) {
            goto l7;
        }
        if (!preg_match($nM, $_POST["\141\x6c\x6c\157\167\x5f\144\145\156\x79\x5f\x75\163\x65\x72\137\x64\157\x6d\x61\x69\x6e\137\166\141\x6c\x75\145"])) {
            goto b6;
        }
        goto Xm;
        l7:
        update_option("\155\x6f\137\x73\141\x6d\x6c\137\155\x65\163\x73\141\x67\x65", "\120\x6c\x65\x61\163\145\40\145\x6e\x74\x65\x72\40\164\150\x65\40\144\x6f\x6d\x61\x69\x6e\163\40\x76\141\x6c\x75\x65\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        goto Xm;
        b6:
        update_option("\x6d\157\137\x73\x61\155\154\137\x6d\x65\x73\163\141\x67\145", "\x50\x6c\x65\x61\x73\x65\40\x65\156\164\145\162\40\157\156\145\x20\x6f\162\x20\x6d\x6f\162\x65\x20\x76\141\154\x69\144\40\144\157\155\141\x69\156\40\156\141\155\x65\x73\x20\163\145\x70\x61\162\x61\164\145\144\40\x62\171\x20\163\x65\155\x69\x63\157\154\x6f\x6e\x73\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        Xm:
        $fo["\x72\145\163\164\162\151\x63\x74\145\x64\137\144\x6f\155\141\151\156\163"] = !empty($_POST["\x61\154\154\x6f\167\x5f\x64\x65\x6e\x79\137\165\163\x65\x72\137\144\x6f\x6d\x61\x69\x6e\x5f\166\x61\x6c\x75\145"]) ? sanitize_text_field(trim($_POST["\x61\154\x6c\x6f\167\x5f\x64\145\x6e\171\x5f\165\x73\x65\x72\137\x64\x6f\x6d\141\x69\x6e\x5f\166\141\x6c\165\x65"])) : '';
        $fo["\141\x6c\x6c\157\x77\x5f\144\145\156\x79\137\x64\157\x6d\141\151\x6e\137\157\x70\164\x69\157\156"] = !empty($_POST["\x6d\157\137\163\141\x6d\154\137\141\154\154\x6f\167\x5f\x64\145\156\x79\x5f\165\x73\145\x72\137\144\x6f\155\x61\151\156"]) ? sanitize_text_field(trim($_POST["\155\x6f\137\163\x61\155\154\137\x61\154\154\x6f\x77\137\x64\x65\156\171\x5f\x75\163\145\162\x5f\x64\157\x6d\x61\x69\156"])) : '';
        HJ:
        $fo["\x65\156\141\142\154\145\x5f\x72\145\x67\x65\x78"] = !empty($_POST["\x6d\157\137\x73\x61\x6d\x6c\137\x65\156\x61\x62\x6c\145\x5f\x72\145\x67\x65\170"]) ? sanitize_text_field(trim($_POST["\155\x6f\x5f\163\x61\155\154\137\x65\x6e\x61\142\154\145\x5f\162\145\x67\x65\x78"])) : '';
        $Jr[$fg] = $fo;
        $s6->mo_save_environment_settings("\155\157\137\163\141\155\154\137\141\164\x74\x72\137\162\157\154\x65\x5f\x61\144\166\x61\156\143\145\144\137\x73\145\164\164\x69\x6e\147\x73", $Jr);
        update_option("\155\157\x5f\x73\x61\x6d\154\x5f\155\145\163\x73\141\x67\x65", "\101\144\166\x61\156\x63\145\x64\40\x73\x65\164\x74\x69\156\147\x73\40\163\141\x76\145\x64\40\163\x75\143\x63\x65\x73\x73\x66\x75\x6c\154\x79\56");
        SAMLSPUtilities::mo_saml_show_success_message();
        Cj:
        if (!self::mo_check_option_admin_referer("\155\x6f\137\x73\141\155\x6c\137\x73\x73\157\x5f\x73\x68\157\x77\x5f\165\x73\x65\x72")) {
            goto gC;
        }
        if (mo_saml_is_sp_configured() && Mo_License_Service::is_customer_license_valid()) {
            goto Se;
        }
        update_option("\x6d\x6f\x5f\163\x61\x6d\154\x5f\x6d\x65\163\x73\x61\147\145", "\120\x6c\145\x61\x73\145\40\143\x6f\x6e\146\151\x67\165\162\x65\x20\x74\150\145\40\x49\104\x50\40\x73\145\x74\164\x69\156\x67\x73\x20\x74\x6f\40\x65\x6e\141\x62\x6c\x65\40\x74\x68\151\163\x20\x73\145\164\x74\151\156\x67\x73");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto YC;
        Se:
        $qX = '';
        if (empty($_POST["\155\x6f\137\x73\141\155\154\137\x73\x73\x6f\x5f\163\150\157\167\137\165\163\x65\162"])) {
            goto iq;
        }
        $qX = sanitize_text_field($_POST["\x6d\x6f\137\163\141\155\x6c\x5f\163\x73\157\x5f\x73\x68\157\167\137\x75\x73\x65\162"]);
        iq:
        update_option("\x6d\157\x5f\x73\x61\x6d\x6c\x5f\155\x65\163\x73\141\x67\x65", "\x53\150\157\x77\40\x53\123\x4f\40\x75\163\145\x72\x20\151\156\40\125\163\145\162\40\163\145\x74\164\x69\156\x67\163\40\165\160\x64\141\164\145\x64");
        SAMLSPUtilities::mo_saml_show_success_message();
        $s6->mo_save_environment_settings("\155\157\137\x73\x61\x6d\154\x5f\163\x73\157\137\163\150\157\x77\137\165\x73\145\162", $qX);
        YC:
        gC:
        if (!self::mo_check_option_admin_referer("\x6d\157\137\163\141\155\x6c\137\162\145\163\145\164\137\x61\x64\166\x61\x6e\143\145\x64\137\x73\145\x74\x74\x69\156\147\x73")) {
            goto o4;
        }
        $fg = !empty($_POST["\x73\x65\x6c\145\143\x74\145\x64\137\x69\144\160\137\x6e\141\x6d\x65"]) ? trim(sanitize_text_field($_POST["\163\x65\x6c\x65\x63\x74\145\x64\137\x69\x64\160\x5f\156\x61\x6d\145"])) : EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\x6f\137\x73\141\155\x6c\x5f\x61\x74\x74\x72\137\162\157\x6c\145\137\x73\x65\154\145\143\x74\x65\144\137\151\144\160");
        $Jr = EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\137\163\141\155\x6c\137\x61\x74\x74\x72\137\x72\157\154\145\137\x61\144\166\141\x6e\x63\x65\144\x5f\163\145\x74\164\151\x6e\x67\x73", true);
        $s6->mo_save_environment_settings("\x6d\157\137\x73\x61\x6d\154\137\141\164\x74\162\x5f\x72\x6f\x6c\145\137\163\x65\154\x65\x63\x74\x65\144\137\x69\144\160", $fg);
        unset($Jr[$fg]);
        $s6->mo_save_environment_settings("\x6d\x6f\137\163\141\x6d\154\x5f\x61\164\x74\162\x5f\162\157\x6c\x65\137\141\x64\166\141\156\x63\x65\144\x5f\x73\x65\x74\x74\x69\x6e\147\163", $Jr);
        $Yf = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\x61\155\x6c\137\x64\x6f\155\141\151\x6e\x5f\x72\145\163\164\162\x69\143\x74\x69\157\x6e", true);
        if (empty(array_filter($Yf))) {
            goto Oy;
        }
        unset($Yf[$fg]);
        $s6->mo_save_environment_settings("\163\141\155\x6c\137\x64\157\x6d\x61\x69\156\x5f\162\145\163\164\162\151\143\x74\151\x6f\x6e", $Yf);
        Oy:
        update_option("\155\x6f\x5f\x73\141\155\x6c\x5f\x6d\x65\x73\163\x61\147\x65", "\x41\x64\x76\141\156\x63\x65\144\40\163\145\x74\x74\151\x6e\147\x73\x20\162\x65\163\145\164\40\x73\165\x63\143\x65\163\163\x66\165\x6c\154\171\56");
        SAMLSPUtilities::mo_saml_show_success_message();
        o4:
        if (!self::mo_check_option_admin_referer("\x6d\157\x5f\x73\141\155\154\137\162\157\154\x65\137\x6d\x61\160\160\x69\156\147\x5f\146\157\162\155")) {
            goto vE;
        }
        if (!SAMLSPUtilities::mo_saml_is_plugin_active("\155\151\x6e\x69\x6f\162\x61\156\x67\x65\55\141\144\166\141\156\143\x65\x64\55\x72\157\154\x65\x2d\155\141\x70\160\x69\156\147\x2f\x61\x64\x76\x61\156\x63\145\x64\55\162\157\x6c\145\x2d\155\x61\x70\160\x69\x6e\x67\x2e\x70\x68\160")) {
            goto wP;
        }
        update_option("\155\x6f\x5f\x73\141\x6d\x6c\x5f\155\x65\x73\x73\141\147\x65", "\x50\154\145\141\163\145\x20\143\x6f\156\x66\151\x67\165\x72\145\x20\x74\x68\145\40\x52\157\154\x65\x20\115\141\160\160\x69\156\147\x20\x69\x6e\40\164\x68\145\40\101\x64\x76\141\x6e\143\x65\144\40\122\x6f\x6c\145\40\x4d\x61\x70\160\151\156\147\40\141\144\x64\x6f\x6e\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        wP:
        $eZ = array();
        $fg = !empty($_POST["\x73\145\x6c\145\143\x74\x65\144\x5f\151\x64\x70\x5f\x6e\141\x6d\145"]) ? trim(sanitize_text_field($_POST["\x73\x65\x6c\x65\143\x74\145\144\x5f\x69\x64\160\137\x6e\x61\x6d\x65"])) : EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\x6f\137\163\x61\x6d\154\137\141\x74\164\162\137\x72\x6f\x6c\x65\x5f\163\x65\154\145\143\x74\145\x64\x5f\x69\x64\x70");
        $f7 = !empty($_POST["\155\157\137\x73\x61\x6d\154\137\162\x6d\137\147\162\x6f\x75\x70\137\x6e\141\x6d\145"]) ? sanitize_text_field(trim($_POST["\x6d\157\137\x73\x61\x6d\154\x5f\162\x6d\x5f\x67\x72\x6f\165\x70\x5f\156\x61\x6d\x65"])) : '';
        $s6->mo_save_environment_settings("\155\x6f\137\x73\x61\x6d\x6c\137\x61\164\x74\x72\x5f\x72\157\x6c\x65\137\163\145\x6c\145\143\164\x65\x64\x5f\x69\x64\160", $fg);
        if (empty($f7)) {
            goto cX;
        }
        $eZ["\147\x72\x6f\165\x70\x5f\x6e\141\x6d\145"] = $f7;
        $eZ["\x61\160\160\x6c\x79\x5f\162\x6f\x6c\x65\x5f\x74\x6f\137\141\144\155\x69\x6e"] = !empty($_POST["\155\x6f\x5f\x73\141\x6d\x6c\137\141\x70\x70\154\x79\x5f\x72\x6f\154\145\x5f\164\157\137\x61\144\x6d\x69\x6e"]) ? sanitize_text_field(trim($_POST["\155\x6f\137\163\x61\155\x6c\x5f\141\160\160\154\x79\137\x72\x6f\x6c\145\x5f\164\x6f\x5f\x61\144\155\151\156"])) : '';
        $Vt = new WP_Roles();
        $wp_roles = $Vt->get_names();
        $Xo = array();
        foreach ($wp_roles as $zZ => $fH) {
            if (!isset($_POST["\155\157\137\163\x61\x6d\x6c\x5f\x72\x6f\x6c\145\x5f\166\x61\154\x75\x65\x5f" . $zZ])) {
                goto bf;
            }
            $Xo[$zZ] = sanitize_text_field(trim($_POST["\x6d\157\137\x73\x61\155\x6c\x5f\162\x6f\154\145\x5f\x76\x61\154\x75\145\137" . $zZ]));
            bf:
            Vp:
        }
        l8:
        $Fm = EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\x5f\163\x61\155\154\x5f\143\x6f\156\x66\151\147\x75\162\145\144\137\162\157\x6c\145\x5f\166\x61\x6c\165\145\x73", true);
        $Fm[$fg] = $Xo;
        $s6->mo_save_environment_settings("\155\157\137\163\x61\155\x6c\137\143\x6f\156\x66\x69\x67\165\162\145\144\137\162\x6f\154\145\x5f\x76\x61\154\x75\x65\x73", $Fm);
        cX:
        $eZ["\x63\162\x65\141\164\145\137\x6e\x65\x77\x5f\x75\x73\145\162"] = !empty($_POST["\x6d\157\137\x73\x61\x6d\x6c\137\x63\x72\x65\141\x74\x65\137\156\x65\167\137\165\x73\145\x72"]) ? sanitize_text_field(trim($_POST["\x6d\157\137\163\x61\155\154\137\143\x72\x65\141\164\145\x5f\x6e\x65\167\x5f\165\163\145\162"])) : '';
        if (!("\143\x68\x65\x63\153\x65\144" === $eZ["\x63\162\x65\141\x74\x65\x5f\156\x65\167\x5f\165\163\x65\x72"])) {
            goto XC;
        }
        $eZ["\144\x65\146\x61\165\154\164\137\x72\157\154\145\137\146\157\x72\137\x6e\x65\167\137\x75\163\145\x72\163"] = !empty($_POST["\x6d\157\x5f\x73\141\x6d\x6c\137\144\145\x66\141\x75\x6c\164\137\x72\157\x6c\x65\137\156\145\x77"]) ? sanitize_text_field(trim($_POST["\155\x6f\137\x73\141\155\x6c\x5f\144\145\x66\x61\165\x6c\x74\137\x72\x6f\154\x65\x5f\156\145\167"])) : '';
        XC:
        $eZ["\165\x70\144\141\x74\145\137\x65\x78\x69\x73\x74\x69\156\x67\137\165\163\x65\x72"] = !empty($_POST["\155\157\x5f\163\x61\155\x6c\x5f\x75\x70\144\x61\164\x65\137\x65\x78\151\x73\164\151\156\147\x5f\x75\x73\x65\x72"]) ? sanitize_text_field(trim($_POST["\x6d\157\x5f\x73\x61\x6d\154\x5f\165\x70\144\141\x74\145\x5f\x65\170\151\163\x74\151\x6e\x67\137\165\x73\x65\162"])) : '';
        if (!("\x63\150\x65\143\153\x65\x64" === $eZ["\x75\x70\x64\141\164\x65\137\x65\170\151\163\x74\151\156\147\x5f\165\163\x65\x72"])) {
            goto vd;
        }
        $eZ["\x64\145\146\141\x75\x6c\x74\x5f\162\157\x6c\x65\x5f\x66\x6f\x72\x5f\x65\170\x69\x73\x74\x69\x6e\147\137\165\x73\145\x72\163"] = !empty($_POST["\x6d\157\137\x73\141\x6d\x6c\137\144\x65\x66\141\x75\x6c\x74\x5f\162\157\154\145\137\x65\x78\151\163\x74\x69\x6e\147"]) ? sanitize_text_field(trim($_POST["\x6d\x6f\x5f\x73\141\x6d\154\x5f\x64\145\x66\141\165\154\164\x5f\x72\x6f\154\145\x5f\145\x78\151\x73\164\x69\x6e\x67"])) : '';
        vd:
        $N6 = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\137\x73\x61\155\154\x5f\x72\157\154\x65\137\155\x61\x70\160\x69\156\147\137\x63\x6f\x6e\146\151\x67\x75\162\x61\164\151\157\156\163", true);
        $N6[$fg] = $eZ;
        $s6->mo_save_environment_settings("\x6d\157\x5f\163\x61\x6d\154\x5f\162\x6f\x6c\145\x5f\155\141\x70\x70\x69\x6e\147\x5f\x63\x6f\156\x66\151\x67\165\162\x61\164\151\x6f\x6e\x73", $N6);
        update_option("\155\157\x5f\163\141\x6d\x6c\x5f\155\145\163\163\141\147\x65", "\122\157\154\x65\x20\x4d\x61\160\160\x69\156\147\40\144\145\164\x61\151\x6c\163\x20\163\141\166\x65\144\x20\163\165\143\143\x65\x73\x73\x66\165\x6c\x6c\x79\x2e");
        SAMLSPUtilities::mo_saml_show_success_message();
        vE:
        if (!self::mo_check_option_admin_referer("\155\157\x5f\163\x61\155\154\137\165\x70\144\x61\x74\x65\x5f\x69\144\160\137\163\145\164\x74\x69\156\147\x73\x5f\157\160\164\151\x6f\156")) {
            goto Fu;
        }
        if (!empty($_POST["\x6d\157\x5f\163\141\155\x6c\137\x73\x70\137\142\141\x73\x65\137\165\162\x6c"]) && !empty($_POST["\155\x6f\137\x73\141\155\154\137\163\x70\137\x65\156\x74\x69\x74\x79\137\151\x64"])) {
            goto Vx;
        }
        update_option("\x6d\157\137\x73\141\155\x6c\137\155\145\x73\163\x61\147\145", "\x50\x6c\145\141\x73\x65\40\x65\x6e\x74\145\x72\x20\141\x20\x76\141\x6c\151\x64\x20\123\120\40\x42\141\x73\x65\x20\x55\x52\114\x20\x6f\x72\x20\123\x50\40\x45\156\164\151\164\171\40\x49\104\57\x49\163\163\165\145\x72\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto yk;
        Vx:
        $hK = esc_url_raw(filter_var($_POST["\x6d\x6f\137\x73\x61\155\x6c\x5f\x73\160\137\142\141\x73\x65\137\x75\x72\x6c"], FILTER_SANITIZE_URL));
        $Uy = sanitize_text_field($_POST["\155\x6f\137\163\x61\155\x6c\137\x73\160\x5f\x65\156\164\x69\x74\x79\137\x69\x64"]);
        if (!(substr($hK, -1) == "\x2f")) {
            goto hg;
        }
        $hK = substr($hK, 0, -1);
        hg:
        $s6->mo_save_environment_settings("\155\157\137\163\x61\x6d\154\x5f\163\160\x5f\x62\x61\163\x65\x5f\165\x72\154", $hK);
        $s6->mo_save_environment_settings("\x6d\x6f\x5f\x73\x61\x6d\154\137\163\160\137\145\156\x74\151\164\x79\137\151\x64", $Uy);
        $hK = EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\137\x73\141\x6d\154\137\x73\160\137\142\x61\163\x65\x5f\x75\162\x6c") ? EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\137\163\x61\x6d\x6c\x5f\163\160\x5f\x62\x61\163\x65\137\165\x72\154") : home_url();
        $UK = Mo_License_Service::is_customer_license_valid() ? home_url() . "\57\x3f\x6f\x70\x74\151\157\x6e\75\x6d\157\x73\x61\155\154\137\155\x65\164\x61\x64\x61\164\x61" : "\152\x61\166\x61\163\x63\x72\x69\x70\x74\72\166\x6f\151\x64\50\60\x29";
        $h9 = "\x53\x65\x72\x76\x69\143\145\x20\x50\162\x6f\x76\x69\x64\145\x72\40\x45\x6e\144\160\157\151\156\x74\x73\x20\163\x61\x76\x65\x64\40\163\165\143\x63\x65\163\163\146\165\x6c\154\x79\56\40\131\157\165\x20\143\x61\x6e\40\166\x69\145\x77\x20\x74\150\145\x20\x75\160\144\x61\164\145\x64\40\x64\x65\164\x61\x69\x6c\x73\x20\142\x79\x20\143\154\151\x63\153\x69\156\147\x20\150\x65\x72\x65\x2e\40\x5b\x20\74\141\x20\x69\x64\75\42\155\145\164\141\144\141\164\141\137\x75\162\154\42\40\164\x61\x72\147\x65\x74\75\x22\137\142\154\141\x6e\x6b\42\40\150\162\x65\x66\x3d\42" . esc_url($UK) . "\x22\x3e\74\142\x3e\126\x69\x65\167\40\x4d\x65\164\141\x64\x61\x74\141\74\57\x62\x3e\74\57\141\76\x20\x5d";
        if (EnvironmentHelper::isSelectedEnvironmentDefault()) {
            goto SL;
        }
        $h9 = "\x53\x65\162\x76\x69\x63\x65\40\x50\x72\157\x76\151\144\x65\162\40\105\x6e\144\x70\157\151\156\164\x73\40\x73\x61\166\x65\144\x20\163\165\143\x63\x65\x73\x73\146\x75\154\154\x79\56";
        SL:
        update_option("\x6d\x6f\137\x73\x61\155\154\137\155\x65\163\x73\141\x67\x65", $h9);
        SAMLSPUtilities::mo_saml_show_success_message();
        yk:
        Fu:
        if (!self::mo_check_option_admin_referer("\155\x6f\x5f\x73\x61\x6d\x6c\137\x75\160\x64\141\164\x65\x5f\170\x6d\154\137\x6f\x72\x67\141\x6e\151\172\x61\x74\151\x6f\156\x5f\155\145\164\x61\x64\x61\x74\x61")) {
            goto DV;
        }
        if (!empty($_POST["\155\x6f\137\163\141\155\x6c\137\x6f\x72\x67\x5f\x6e\141\x6d\145"]) && !empty($_POST["\x6d\157\x5f\x73\x61\155\x6c\x5f\x6f\x72\147\137\x75\162\x6c"]) && !empty($_POST["\x6d\x6f\137\163\141\155\x6c\137\x6f\x72\147\137\144\x69\x73\x70\154\141\171\137\156\141\x6d\x65"]) && !empty($_POST["\x6d\x6f\137\163\141\155\154\137\x74\145\x63\x68\137\156\x61\155\145"]) && !empty($_POST["\x6d\x6f\x5f\163\141\155\x6c\x5f\164\145\x63\x68\x5f\145\155\141\x69\x6c"]) && !empty($_POST["\155\x6f\x5f\163\x61\x6d\x6c\137\163\x75\x70\160\157\162\x74\x5f\156\141\x6d\145"]) && !empty($_POST["\x6d\157\137\x73\141\155\154\137\163\165\160\x70\x6f\162\164\x5f\145\x6d\x61\x69\x6c"])) {
            goto wK;
        }
        update_option("\155\157\137\163\x61\x6d\x6c\137\x6d\145\x73\163\141\147\145", "\x41\154\154\x20\146\x69\x65\154\x64\163\x20\141\x72\145\40\x72\x65\161\165\x69\x72\x65\x64\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto WA;
        wK:
        $Ta = sanitize_text_field(wp_unslash($_POST["\x6d\x6f\137\163\141\155\154\x5f\157\162\x67\137\156\141\155\x65"]));
        $UJ = sanitize_text_field(wp_unslash($_POST["\x6d\157\137\x73\141\x6d\154\x5f\157\162\x67\137\144\151\x73\x70\154\x61\x79\137\x6e\x61\155\x65"]));
        $Uh = filter_var($_POST["\155\x6f\x5f\163\141\155\x6c\x5f\157\162\147\137\165\x72\154"], FILTER_SANITIZE_URL);
        $rG = sanitize_text_field(wp_unslash($_POST["\x6d\157\x5f\x73\141\155\154\x5f\x74\x65\x63\x68\137\x6e\x61\x6d\x65"]));
        $rP = sanitize_text_field(wp_unslash($_POST["\x6d\x6f\x5f\x73\141\x6d\154\x5f\163\165\x70\x70\157\x72\164\x5f\x6e\x61\155\x65"]));
        $Gy = filter_var($_POST["\155\157\x5f\163\x61\x6d\x6c\137\x73\x75\x70\160\x6f\x72\164\137\x65\x6d\141\151\154"], FILTER_SANITIZE_EMAIL);
        $Re = filter_var($_POST["\x6d\157\137\163\x61\155\154\x5f\164\145\143\x68\137\x65\155\141\151\154"], FILTER_SANITIZE_EMAIL);
        if (!is_email($Gy) || !is_email($Re)) {
            goto tH;
        }
        if (!filter_var($Uh, FILTER_VALIDATE_URL)) {
            goto LL;
        }
        update_option(Mo_Saml_Organization_Metatadata_Options::ORGANIZATION_NAME_OPTION, $Ta);
        update_option(Mo_Saml_Organization_Metatadata_Options::ORGANIZATION_URL_OPTION, $Uh);
        update_option(Mo_Saml_Organization_Metatadata_Options::ORGANIZATION_DISPLAY_NAME_OPTION, $UJ);
        update_option(Mo_Saml_Organization_Metatadata_Options::TECHNICAL_PERSON_NAME_OPTION, $rG);
        update_option(Mo_Saml_Organization_Metatadata_Options::TECHNICAL_PERSON_EMAIL_OPTION, $Re);
        update_option(Mo_Saml_Organization_Metatadata_Options::SUPPORT_PERSON_NAME_OPTION, $rP);
        update_option(Mo_Saml_Organization_Metatadata_Options::SUPPORT_PERSON_EMAIL_OPTION, $Gy);
        $hK = EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\x5f\x73\x61\x6d\154\x5f\163\160\x5f\142\141\163\145\x5f\x75\162\154") ? EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\163\141\155\154\x5f\x73\x70\x5f\142\x61\163\x65\x5f\165\x72\x6c") : home_url();
        $UK = Mo_License_Service::is_customer_license_valid() ? home_url() . "\x2f\77\157\x70\x74\151\157\x6e\75\x6d\x6f\x73\x61\155\x6c\137\155\145\164\141\x64\x61\164\x61" : "\152\x61\x76\141\x73\x63\x72\151\160\x74\72\x76\x6f\x69\x64\50\x30\51";
        $h9 = "\x4f\x72\x67\141\x6e\x69\172\x61\x74\x69\157\x6e\x20\144\145\164\141\151\154\x73\40\165\160\x64\141\164\145\144\x20\163\x75\143\x63\x65\x73\x73\146\x75\154\154\x79\56\40\131\x6f\165\x20\143\141\x6e\x20\166\151\x65\x77\x20\x74\x68\145\40\x75\160\x64\141\x74\x65\144\x20\144\x65\x74\141\x69\154\163\x20\x62\x79\x20\143\x6c\x69\x63\153\x69\156\147\x20\x68\145\x72\x65\56\x20\x5b\40\74\x61\40\151\144\x3d\x22\155\145\164\141\x64\141\x74\x61\137\165\162\x6c\x22\40\164\141\x72\147\x65\x74\x3d\42\137\x62\x6c\x61\x6e\x6b\x22\x20\150\x72\x65\x66\75\42" . esc_url($UK) . "\x22\76\74\x62\x3e\x56\151\145\167\40\x4d\145\x74\141\x64\141\164\x61\74\57\x62\76\x3c\x2f\141\76\40\x5d";
        if (EnvironmentHelper::isSelectedEnvironmentDefault()) {
            goto Fv;
        }
        $h9 = "\x4f\162\x67\x61\x6e\151\172\141\164\x69\x6f\x6e\x20\x64\145\164\x61\x69\154\x73\40\165\160\144\x61\164\145\x64\x20\163\165\x63\x63\145\163\163\146\165\154\154\171\x2e";
        Fv:
        update_option("\155\x6f\x5f\x73\x61\155\154\137\155\x65\163\x73\141\147\x65", $h9);
        SAMLSPUtilities::mo_saml_show_success_message();
        goto Z0;
        tH:
        update_option("\155\x6f\x5f\163\141\x6d\x6c\137\155\145\163\x73\x61\147\x65", "\120\154\145\141\x73\145\x20\145\156\164\x65\162\40\x76\141\x6c\151\x64\x20\145\x6d\x61\151\154\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto Z0;
        LL:
        update_option("\x6d\157\x5f\x73\x61\x6d\x6c\x5f\155\x65\x73\163\x61\x67\145", "\120\x6c\x65\141\x73\x65\x20\145\x6e\164\145\162\x20\166\x61\x6c\x69\x64\x20\x75\162\154\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        Z0:
        WA:
        DV:
        if (!self::mo_check_option_admin_referer("\x73\141\155\154\137\x75\160\x6c\157\141\144\137\x6d\145\x74\141\144\x61\x74\141")) {
            goto bZ;
        }
        if (!function_exists("\x77\x70\137\x68\x61\x6e\x64\154\145\x5f\x75\160\154\x6f\141\x64")) {
            require_once ABSPATH . Mo_Saml_WordPress_Files::MO_SAML_WP_ADMIN_FILE;
        }
        if (!(isset($_POST["\x73\x61\155\x6c\137\151\144\x65\156\164\151\164\171\x5f\155\x65\x74\x61\x64\141\x74\x61\x5f\x70\162\x6f\166\x69\x64\x65\x72"]) && !preg_match("\x23\x5e\x28\x3f\x3d\56\52\133\x61\55\172\101\55\132\x30\x2d\71\135\x29\x5b\x61\x2d\x7a\x41\55\x5a\60\x2d\71\134\163\137\x5c\55\100\135\53\x24\43", $_POST["\163\141\155\154\137\x69\x64\x65\156\164\x69\164\x79\x5f\155\x65\x74\x61\144\141\164\x61\137\160\162\157\x76\151\x64\x65\x72"]))) {
            goto z0;
        }
        update_option("\x6d\157\x5f\x73\141\x6d\154\x5f\155\x65\163\163\141\147\x65", "\x50\x6c\x65\141\x73\x65\x20\155\141\164\143\150\x20\x74\150\x65\40\x72\x65\x71\165\145\163\x74\145\x64\x20\146\x6f\162\155\x61\164\x20\x66\x6f\x72\40\111\x64\145\156\164\x69\164\171\x20\x50\x72\157\166\151\x64\x65\x72\x20\116\141\x6d\145\x2e\x20\x53\160\145\143\151\x61\154\x20\x63\150\x61\x72\141\x63\164\145\162\x73\x20\x61\x72\x65\40\156\x6f\x74\x20\141\x6c\154\157\167\x65\x64\x20\x65\x78\x63\145\x70\164\40\165\x6e\144\x65\x72\163\x63\157\162\145\50\x5f\x29\54\40\x68\171\x70\x68\x65\156\x28\x2d\x29\x20\141\x6e\x64\x20\x40\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        z0:
        $s6->mo_save_environment_settings("\x63\165\162\x72\x65\156\164\x5f\151\144\x70\137\144\x65\164\x61\151\154\x73", array());
        $K7 = ini_get("\155\x61\x78\x5f\145\x78\x65\x63\x75\164\x69\157\x6e\137\x74\151\155\x65");
        $K7 = !empty($K7) ? intval($K7) : 30;
        set_time_limit(0);
        $uM = Mo_Saml_Metadata_Import_Handler::mo_saml_get_object();
        $uM->mo_saml_handle_upload_metadata();
        set_time_limit($K7);
        bZ:
        if (!self::mo_check_option_admin_referer("\155\160\137\x73\x61\155\154\137\x63\x65\162\164\x5f\151\x64\160\137\157\160\x74\x69\x6f\156")) {
            goto Dc;
        }
        if (!empty($_POST["\163\x61\155\x6c\137\x63\145\x72\x74\x5f\151\x64\x70"])) {
            goto os;
        }
        update_option("\x6d\x6f\x5f\x73\x61\155\x6c\x5f\x63\145\x72\x74\137\151\x64\160\x5f\156\141\155\145", "\104\105\106\x41\x55\114\x54");
        goto pB;
        os:
        update_option("\x6d\157\x5f\163\141\155\154\x5f\x63\145\x72\164\137\x69\x64\x70\137\x6e\x61\x6d\145", htmlspecialchars($_POST["\163\x61\155\154\137\x63\x65\162\x74\x5f\x69\144\160"]));
        pB:
        Dc:
        if (!self::mo_check_option_admin_referer("\x75\160\147\162\x61\144\x65\137\x63\x65\x72\164")) {
            goto Vf;
        }
        $Fh = file_get_contents(plugin_dir_path(__FILE__) . "\162\x65\163\x6f\165\162\x63\145\x73" . DIRECTORY_SEPARATOR . mo_options_enum_default_sp_certificate::SP_PUBLIC_CERT_FILE_NAME);
        $lh = file_get_contents(plugin_dir_path(__FILE__) . "\162\145\x73\x6f\x75\162\143\145\163" . DIRECTORY_SEPARATOR . mo_options_enum_default_sp_certificate::SP_PRIVATE_KEY_FILE_NAME);
        $XE = "\104\x45\x46\101\125\114\124";
        if (empty($_POST["\x69\x64\160\x5f\156\x61\x6d\145"])) {
            goto fP;
        }
        $XE = htmlspecialchars($_POST["\151\x64\x70\x5f\x6e\x61\155\x65"]);
        fP:
        $kq = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\155\x6c\x5f\151\144\145\x6e\x74\151\x74\x79\137\160\x72\x6f\x76\x69\x64\145\x72\x73", true, EnvironmentHelper::getCurrentEnvironment());
        if ($XE == "\104\105\x46\x41\x55\x4c\124") {
            goto oq;
        }
        $kq[$XE]["\x73\160\x5f\x63\x65\x72\x74"] = $Fh;
        $kq[$XE]["\163\x70\x5f\160\162\x69\166\x5f\x6b\145\171"] = $lh;
        goto RO;
        oq:
        update_option("\155\x6f\137\x73\x61\155\154\x5f\x63\165\162\162\x65\156\x74\x5f\x63\145\162\x74", $Fh);
        update_option("\x6d\x6f\x5f\163\141\155\154\137\x63\x75\162\162\145\x6e\x74\x5f\143\x65\162\x74\x5f\x70\x72\151\x76\141\164\x65\x5f\153\145\x79", $lh);
        foreach ($kq as $fk) {
            unset($fk["\163\x70\137\x63\145\x72\x74"]);
            unset($fk["\x73\160\x5f\x70\x72\151\x76\x5f\153\x65\x79"]);
            kO:
        }
        pn:
        RO:
        $s6->mo_save_environment_settings("\163\x61\x6d\x6c\137\151\144\145\156\x74\x69\164\x79\x5f\160\162\157\166\x69\144\145\x72\163", $kq);
        update_option("\x6d\157\137\163\x61\x6d\154\x5f\155\x65\163\x73\141\147\145", "\103\145\x72\164\x69\146\x69\x63\141\x74\x65\x20\x55\x70\x67\162\141\x64\145\144\x20\163\165\x63\x63\x65\163\163\146\165\x6c\154\171");
        SAMLSPUtilities::mo_saml_show_success_message();
        Vf:
        if (self::mo_check_option_admin_referer("\x61\144\144\137\x63\x75\x73\x74\157\155\x5f\143\145\162\x74\x69\146\151\143\x61\164\x65")) {
            goto DN;
        }
        if (self::mo_check_option_admin_referer("\141\x64\144\x5f\143\x75\x73\x74\x6f\155\137\x6d\x65\x73\163\141\147\x65\163")) {
            goto TV;
        }
        goto ra;
        DN:
        if (!empty($_POST["\x73\x75\x62\155\151\164"]) and $_POST["\x73\x75\142\x6d\151\x74"] == "\125\x70\154\157\141\144") {
            goto am;
        }
        if (!empty($_POST["\x73\165\142\155\151\x74"]) and $_POST["\163\165\x62\155\x69\x74"] == "\122\x65\x73\145\x74") {
            goto E1;
        }
        goto Wr;
        am:
        if (!@openssl_x509_read($_POST["\x73\x61\155\154\137\x70\x75\x62\154\x69\x63\x5f\x78\x35\60\x39\137\x63\145\162\x74\151\146\x69\x63\x61\164\145"])) {
            goto QN;
        }
        if (!@openssl_x509_check_private_key($_POST["\x73\141\155\154\137\160\165\x62\154\x69\143\x5f\x78\x35\60\71\137\143\x65\x72\x74\151\x66\151\x63\x61\164\145"], $_POST["\x73\x61\155\154\137\160\x72\x69\166\141\164\x65\x5f\x78\x35\60\71\137\x63\x65\162\x74\151\146\x69\143\141\x74\x65"])) {
            goto P2;
        }
        if (openssl_x509_read($_POST["\163\x61\x6d\x6c\137\x70\165\142\x6c\x69\x63\x5f\x78\65\60\71\x5f\143\145\162\164\x69\146\151\x63\141\x74\x65"]) && openssl_x509_check_private_key($_POST["\x73\x61\155\x6c\x5f\160\x75\142\x6c\x69\143\137\170\x35\60\71\137\143\145\162\164\151\146\151\x63\141\164\x65"], $_POST["\x73\x61\x6d\x6c\137\160\x72\151\166\x61\164\145\x5f\170\65\60\x39\x5f\143\145\162\x74\x69\x66\151\143\141\164\x65"])) {
            goto O0;
        }
        goto ho;
        QN:
        update_option("\155\x6f\137\x73\141\155\154\x5f\x6d\145\x73\163\x61\x67\145", "\x49\x6e\166\x61\x6c\x69\144\x20\103\145\x72\164\x69\x66\151\x63\x61\164\145\x20\146\x6f\x72\x6d\141\x74\56\x20\120\x6c\145\141\163\145\x20\145\156\164\x65\x72\40\141\40\166\141\154\151\x64\x20\x63\145\x72\x74\151\146\151\143\x61\164\x65\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        goto ho;
        P2:
        update_option("\155\x6f\137\163\x61\x6d\x6c\137\x6d\145\x73\x73\141\x67\x65", "\x49\156\166\x61\x6c\x69\x64\x20\120\162\151\166\x61\x74\145\40\x4b\145\171\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        goto ho;
        O0:
        $N1 = $_POST["\x73\141\x6d\154\x5f\x70\165\x62\154\151\143\x5f\x78\x35\x30\71\137\143\x65\162\x74\x69\x66\x69\143\x61\x74\x65"];
        $k7 = $_POST["\x73\141\x6d\x6c\x5f\160\x72\x69\166\x61\164\145\x5f\170\x35\x30\71\137\143\x65\162\164\151\x66\x69\x63\141\164\145"];
        update_option("\155\157\x5f\x73\141\155\x6c\137\143\x75\x73\x74\157\x6d\137\x63\145\x72\x74", $N1);
        update_option("\x6d\157\137\x73\x61\155\154\137\x63\165\x73\x74\157\155\x5f\143\x65\162\164\137\160\162\x69\x76\141\164\x65\x5f\x6b\145\x79", $k7);
        update_option("\155\157\137\163\141\x6d\x6c\x5f\x63\x75\x72\x72\145\156\x74\x5f\143\145\162\x74", $N1);
        update_option("\x6d\157\x5f\x73\x61\155\x6c\137\143\165\162\162\x65\156\x74\x5f\x63\x65\x72\164\x5f\160\162\151\166\141\164\145\137\153\145\x79", $k7);
        update_option("\x6d\157\137\163\141\x6d\154\137\155\x65\163\163\141\147\145", "\x43\x75\x73\164\157\x6d\40\103\x65\162\164\x69\146\x69\x63\x61\164\x65\40\x75\160\144\141\164\145\x64\40\163\165\x63\x63\x65\163\x73\x66\x75\154\154\x79\x2e");
        SAMLSPUtilities::mo_saml_show_success_message();
        ho:
        goto Wr;
        E1:
        delete_option("\155\x6f\137\x73\x61\x6d\154\x5f\x63\x75\x73\x74\157\x6d\137\143\145\162\x74");
        delete_option("\155\157\137\163\141\x6d\x6c\x5f\143\x75\163\x74\x6f\x6d\x5f\143\145\x72\x74\137\160\162\151\x76\141\x74\145\137\x6b\x65\171");
        update_option("\155\x6f\137\163\x61\x6d\154\x5f\143\x75\162\x72\145\156\x74\x5f\143\145\x72\x74", !empty($Fh));
        update_option("\155\x6f\137\163\x61\x6d\x6c\137\143\165\x72\162\x65\156\164\x5f\143\x65\x72\164\137\x70\162\x69\x76\x61\x74\145\137\153\145\x79", !empty($lh));
        update_option("\155\x6f\137\x73\x61\155\154\x5f\x6d\145\163\x73\141\x67\x65", "\122\145\163\x65\164\x20\103\145\x72\x74\x69\x66\x69\143\x61\164\x65\40\163\165\143\143\145\163\x73\x66\165\x6c\x6c\x79\x2e");
        SAMLSPUtilities::mo_saml_show_success_message();
        Wr:
        goto ra;
        TV:
        $s6->mo_save_environment_settings("\155\157\x5f\163\x61\x6d\154\x5f\141\x63\143\x6f\165\156\164\137\143\162\145\x61\x74\151\157\156\x5f\x64\x69\163\141\142\x6c\145\144\137\x6d\x73\147", sanitize_text_field($_POST["\x6d\x6f\x5f\x73\141\x6d\154\137\141\x63\143\157\165\x6e\x74\x5f\x63\x72\x65\141\164\x69\x6f\x6e\137\x64\x69\x73\x61\142\x6c\x65\x64\x5f\155\x73\147"]));
        $s6->mo_save_environment_settings("\155\x6f\137\x73\141\x6d\x6c\x5f\x72\x65\163\164\x72\151\x63\x74\x65\x64\137\x64\157\155\x61\x69\156\137\x65\x72\162\157\162\x5f\x6d\163\x67", sanitize_text_field($_POST["\x6d\x6f\x5f\x73\x61\155\x6c\137\162\145\163\x74\162\x69\x63\x74\145\144\x5f\x64\157\155\x61\151\156\137\x65\x72\162\x6f\x72\137\x6d\x73\147"]));
        update_option("\155\x6f\x5f\163\141\x6d\154\137\x6d\x65\x73\163\141\x67\145", "\103\157\156\x66\x69\x67\x75\x72\x61\164\151\x6f\x6e\40\150\141\163\x20\142\x65\145\156\40\x73\141\166\x65\x64\x20\163\165\x63\143\x65\x73\163\146\x75\x6c\154\x79\x2e");
        SAMLSPUtilities::mo_saml_show_success_message();
        ra:
        if (!self::mo_check_option_admin_referer("\155\x6f\137\x72\145\154\x61\171\x5f\x73\x74\141\164\x65\x5f\151\144\160\137\x6e\x61\155\x65")) {
            goto Ip;
        }
        if (empty($_REQUEST["\155\x6f\137\163\x61\x6d\154\137\162\x65\x6c\141\171\137\163\x74\141\164\x65\x5f\151\144\x70\137\x6e\141\x6d\145"])) {
            goto i6;
        }
        $BB = sanitize_text_field(wp_unslash($_REQUEST["\155\x6f\x5f\163\141\155\x6c\x5f\162\x65\154\x61\x79\x5f\163\x74\x61\164\x65\x5f\x69\x64\x70\x5f\156\141\x6d\145"]));
        $s6->mo_save_environment_settings(Mo_Saml_Admin_Referer_Options::RELAY_STATE_IDP_NAME, $BB);
        update_option("\155\x6f\137\x73\x61\155\x6c\137\155\145\x73\x73\x61\147\x65", "\123\x53\117\x20\x6c\x6f\x67\x69\x6e\40\x6f\x70\164\151\157\156\163\40\163\141\x76\145\x64\40\163\x75\143\143\145\163\x73\x66\165\154\x6c\x79\x2e");
        SAMLSPUtilities::mo_saml_show_success_message();
        i6:
        Ip:
        if (!self::mo_check_option_admin_referer("\x6d\x6f\x5f\163\x61\x6d\154\x5f\162\145\x6c\141\171\x5f\x73\x74\141\164\145\x5f\x6f\x70\164\x69\x6f\156")) {
            goto ml;
        }
        $BB = !empty($_POST["\x6d\x6f\137\x73\x61\155\154\x5f\x72\145\x6c\141\x79\137\x73\164\141\164\145\x5f\151\x64\x70\137\x6e\x61\155\145"]) ? sanitize_text_field(wp_unslash($_POST["\x6d\x6f\137\163\141\x6d\154\137\162\x65\x6c\x61\x79\137\163\164\x61\x74\x65\137\151\x64\x70\x5f\156\x61\155\x65"])) : "\x44\105\106\101\125\x4c\x54";
        $HD = EnvironmentHelper::getOptionForSelectedEnvironment(mo_options_enum_sso_login::Relay_states, true);
        $jx = !empty($HD["\154\157\147\x69\156\x5f\162\145\x6c\x61\171\137\163\x74\141\x74\x65"]) ? $HD["\154\x6f\147\151\156\x5f\x72\x65\154\x61\171\137\x73\x74\141\x74\145"] : array();
        $gT = !empty($HD["\154\157\147\157\165\164\137\x72\145\154\141\x79\137\163\164\x61\x74\x65"]) ? $HD["\154\x6f\147\157\x75\x74\x5f\x72\145\x6c\141\171\x5f\x73\164\x61\x74\x65"] : array();
        $aO = '';
        $Ih = '';
        if (!empty(trim($_POST["\155\x6f\137\x73\x61\x6d\154\137\x6c\157\147\151\x6e\x5f\x72\x65\x6c\141\171\137\x73\x74\x61\x74\x65"])) && filter_var($_POST["\155\x6f\137\163\141\155\154\137\154\x6f\x67\x69\156\x5f\x72\x65\x6c\x61\x79\137\x73\164\x61\x74\x65"], FILTER_VALIDATE_URL) === false || !empty(trim($_POST["\x6d\157\137\x73\141\155\x6c\x5f\x6c\x6f\147\x6f\x75\164\x5f\x72\x65\x6c\141\171\137\x73\x74\x61\164\145"])) && filter_var($_POST["\x6d\157\137\x73\141\x6d\x6c\137\154\x6f\x67\x6f\165\x74\x5f\162\145\x6c\x61\x79\137\x73\x74\x61\164\x65"], FILTER_VALIDATE_URL) === false) {
            goto tG;
        }
        $aO = !empty($_POST["\x6d\x6f\x5f\x73\141\155\x6c\x5f\154\157\x67\151\x6e\x5f\x72\x65\154\141\x79\x5f\163\x74\141\164\145"]) ? esc_url_raw(filter_var($_POST["\x6d\157\x5f\163\141\x6d\154\137\154\157\x67\151\156\x5f\x72\145\154\141\x79\137\163\x74\x61\x74\145"], FILTER_SANITIZE_URL)) : '';
        $aO = SAMLSPUtilities::mo_saml_check_trailing_slash($aO);
        $jx[$BB] = $aO;
        $Ih = !empty($_POST["\x6d\157\137\x73\x61\x6d\154\137\x6c\x6f\x67\157\165\164\137\162\145\154\141\171\137\163\x74\141\164\x65"]) ? esc_url_raw(filter_var($_POST["\x6d\157\137\163\141\x6d\154\x5f\x6c\157\147\x6f\x75\164\137\x72\145\154\141\x79\137\163\164\141\x74\145"], FILTER_SANITIZE_URL)) : '';
        $Ih = SAMLSPUtilities::mo_saml_check_trailing_slash($Ih);
        $gT[$BB] = $Ih;
        goto D5;
        tG:
        update_option("\x6d\x6f\x5f\x73\x61\155\x6c\137\x6d\x65\x73\163\141\147\145", "\x49\x6e\x76\x61\154\151\144\x20\x52\x65\x6c\141\171\x20\x53\x74\x61\164\145\50\163\51\40\x66\157\165\156\x64\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        D5:
        $HD["\x6c\157\x67\x69\156\x5f\x72\145\x6c\141\171\x5f\163\164\141\164\x65"] = $jx;
        $HD["\154\x6f\x67\157\x75\164\137\x72\145\x6c\141\171\x5f\x73\164\x61\x74\x65"] = $gT;
        $s6->mo_save_environment_settings(mo_options_enum_sso_login::Relay_state, $aO);
        $s6->mo_save_environment_settings(mo_options_enum_sso_login::Relay_states, $HD);
        update_option("\x6d\x6f\137\x73\x61\x6d\x6c\x5f\155\x65\163\163\x61\x67\145", "\x52\145\154\141\171\40\123\x74\x61\x74\145\40\165\x70\x64\x61\164\x65\x64\40\x73\165\143\143\x65\163\x73\x66\165\154\154\171\x2e");
        SAMLSPUtilities::mo_saml_show_success_message();
        ml:
        if (!(self::mo_check_option_admin_referer("\x6d\157\x5f\163\141\x6d\x6c\137\x69\144\160\x5f\154\x69\x73\164\x5f\x6f\x70\x74\x69\157\x6e") && isset($_POST["\x6d\x6f\137\x73\x61\155\154\x5f\x69\x64\x70\137\x6c\151\163\x74\137\x75\162\154"]))) {
            goto Yn;
        }
        $Bt = sanitize_text_field($_POST["\155\157\137\x73\x61\x6d\x6c\137\151\x64\160\x5f\154\x69\163\164\x5f\165\162\154"]);
        if (!(substr($Bt, -1) != "\x2f" && strpos($Bt, "\x3f") == false && strpos($Bt, "\x23") == false)) {
            goto Lu;
        }
        $Bt = $Bt . "\57";
        Lu:
        try {
            SAMLSPUtilities::mo_saml_validate_public_page_url($Bt);
        } catch (Exception $G2) {
            $VS = $G2->getMessage();
            update_option(Mo_Saml_Options_Plugin_Admin::ADMIN_NOTICES_MESSAGE, $VS);
            SAMLSPUtilities::mo_saml_show_error_message();
            return;
        }
        $s6->mo_save_environment_settings("\155\x6f\x5f\x73\x61\155\x6c\137\x69\144\160\x5f\x6c\x69\163\x74\x5f\165\x72\x6c", $Bt);
        update_option("\155\x6f\137\x73\141\x6d\x6c\x5f\155\x65\x73\x73\x61\147\x65", "\x50\x75\142\154\x69\143\40\x50\x61\147\145\40\x55\x72\154\40\x75\160\144\x61\x74\145\x64\x20\x73\165\x63\x63\145\x73\163\x66\165\154\x6c\171\56");
        SAMLSPUtilities::mo_saml_show_success_message();
        Yn:
        if (!self::mo_check_option_admin_referer("\155\x6f\x5f\163\x61\x6d\x6c\137\163\x68\x6f\x72\x74\143\x6f\x64\145\x5f\157\160\164\x69\x6f\156")) {
            goto pR;
        }
        $z2 = empty($_POST["\x6d\x6f\137\163\141\155\x6c\x5f\163\150\157\162\x74\x63\157\144\x65\x5f\x6c\x6f\x67\x69\x6e\137\x74\x65\170\164"]) ? "\114\157\147\x69\156\x20\167\151\x74\150\40" : sanitize_text_field($_POST["\155\x6f\x5f\163\x61\155\154\137\163\150\157\162\x74\x63\x6f\144\145\137\x6c\157\x67\151\156\137\164\x65\x78\164"]);
        $s6->mo_save_environment_settings("\155\157\x5f\x73\x61\155\154\137\163\150\x6f\162\164\143\x6f\x64\145\137\154\157\147\x69\156\137\164\x65\170\x74", $z2);
        update_option("\x6d\x6f\137\163\x61\155\154\137\x6d\x65\163\x73\x61\147\x65", "\123\150\x6f\x72\x74\x63\157\144\x65\40\x4c\x6f\x67\x69\x6e\40\x74\x65\170\x74\40\165\x70\x64\x61\164\145\144\40\163\165\143\x63\x65\163\x73\146\165\154\154\171\56");
        SAMLSPUtilities::mo_saml_show_success_message();
        pR:
        if (!self::mo_check_option_admin_referer("\x6d\157\x5f\163\141\155\154\137\151\144\x70\137\163\150\x6f\162\164\x63\x6f\144\145\x5f\x6f\160\x74\x69\x6f\x6e")) {
            goto DC;
        }
        $z2 = empty($_POST["\155\x6f\x5f\163\141\155\x6c\137\151\x64\x70\137\163\x68\x6f\x72\x74\143\x6f\x64\145\137\x6c\157\147\x69\x6e\137\164\145\x78\x74"]) ? "\114\x6f\147\x69\156\40\167\x69\x74\150\40\x23\x23\x49\x44\x50\43\43" : sanitize_text_field($_POST["\155\x6f\137\x73\141\x6d\154\137\151\x64\160\137\163\x68\x6f\x72\164\143\x6f\144\145\x5f\x6c\x6f\147\151\x6e\x5f\x74\145\170\164"]);
        $s6->mo_save_environment_settings("\x6d\x6f\x5f\163\x61\x6d\x6c\x5f\151\144\160\137\x73\150\x6f\162\164\x63\x6f\x64\145\x5f\x6c\x6f\x67\151\156\x5f\x74\x65\170\x74", $z2);
        update_option("\155\157\x5f\163\141\x6d\154\137\155\x65\163\163\x61\x67\145", "\123\150\x6f\162\164\x63\157\x64\145\40\114\x6f\147\x69\x6e\x20\164\145\170\164\40\x75\x70\144\x61\164\145\x64\x20\x73\165\143\x63\x65\x73\x73\146\165\154\154\171\x2e");
        SAMLSPUtilities::mo_saml_show_success_message();
        DC:
        if (!self::mo_check_option_admin_referer("\163\x73\157\x5f\x62\165\164\x74\x6f\156\137\157\160\164\x69\x6f\156")) {
            goto yl;
        }
        $a8 = Mo_Saml_Hide_WP_Login_Handler::mo_saml_is_sso_button_disabled('');
        $m3 = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\x6f\x5f\x73\x61\155\154\x5f\x65\x6e\x61\x62\x6c\x65\137\150\x69\x64\145\x5f\x77\x70\137\154\x6f\147\151\x6e");
        if (empty($_POST["\x73\141\x6d\154\x5f\x73\145\x6c\145\143\164\x5f\x69\144\160\137\156\x61\x6d\x65"])) {
            goto mq;
        }
        $pK = htmlspecialchars($_POST["\x73\141\x6d\x6c\137\x73\145\154\x65\x63\x74\x5f\x69\x64\x70\x5f\x6e\141\x6d\x65"]);
        mq:
        if (!empty($_POST["\x6d\x6f\x5f\163\x61\155\154\137\141\x64\x64\137\x73\163\157\137\142\x75\164\164\157\x6e\x5f\x77\x70"])) {
            goto Ue;
        }
        if (!($a8 <= 1 && "\164\x72\x75\x65" === $m3)) {
            goto S9;
        }
        update_option("\x6d\x6f\x5f\163\141\x6d\x6c\x5f\x6d\x65\163\x73\x61\x67\x65", "\131\157\x75\x20\x63\x61\x6e\x6e\157\164\40\162\x65\x6d\157\166\x65\40\141\154\154\40\164\x68\x65\40\123\123\117\40\x62\165\164\164\x6f\x6e\40\167\x68\x69\x6c\145\x20\164\x68\x65\x20\x3c\x73\164\x72\157\x6e\x67\x3e\x48\151\144\145\x2f\104\151\163\141\142\154\x65\40\127\157\162\x64\120\x72\145\x73\163\x20\x44\x65\x66\x61\165\x6c\164\x20\x4c\157\147\151\156\x20\x46\x6f\x72\x6d\x3c\57\163\164\x72\157\x6e\x67\x3e\x20\157\x70\164\x69\157\156\x20\x65\x6e\141\x62\x6c\145\x64\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        S9:
        $aW = "\x66\x61\x6c\163\145";
        goto Pp;
        Ue:
        $aW = htmlspecialchars($_POST["\155\157\x5f\163\x61\x6d\154\137\141\144\x64\x5f\x73\x73\x6f\137\142\165\x74\x74\x6f\x6e\x5f\x77\x70"]);
        Pp:
        if (!empty($_POST["\155\157\x5f\x73\x61\x6d\154\137\165\x73\145\x5f\x62\165\x74\x74\x6f\x6e\137\x61\x73\137\x73\150\x6f\162\164\143\x6f\x64\145"])) {
            goto fL;
        }
        $C7 = false;
        goto ha;
        fL:
        $C7 = htmlspecialchars($_POST["\x6d\x6f\137\x73\x61\155\x6c\x5f\x75\x73\x65\x5f\142\x75\x74\164\157\x6e\137\x61\163\x5f\163\x68\157\162\x74\x63\157\x64\145"]);
        ha:
        if (!empty($_POST["\155\157\x5f\163\141\x6d\x6c\x5f\x75\x73\x65\137\142\x75\x74\164\x6f\x6e\137\x61\163\x5f\167\151\144\x67\x65\164"])) {
            goto WW;
        }
        $U9 = false;
        goto Hq;
        WW:
        $U9 = htmlspecialchars($_POST["\x6d\x6f\x5f\x73\x61\x6d\x6c\x5f\165\163\x65\137\142\165\x74\x74\157\x6e\137\x61\163\x5f\167\x69\x64\x67\x65\164"]);
        Hq:
        $VX = '';
        $Bw = '';
        $gl = '';
        $Ow = '';
        $Ep = '';
        $KU = '';
        $XI = '';
        $Nw = '';
        $tl = '';
        $Uo = "\x61\142\x6f\x76\x65";
        if (empty($_POST["\x6d\157\137\x73\141\155\x6c\x5f\142\165\164\164\157\x6e\x5f\163\151\x7a\145"])) {
            goto T0;
        }
        $Bw = htmlspecialchars($_POST["\x6d\x6f\x5f\163\141\155\x6c\137\x62\165\164\164\x6f\156\137\x73\x69\x7a\x65"]);
        T0:
        if (empty($_POST["\x6d\x6f\x5f\163\x61\x6d\x6c\x5f\142\x75\164\164\157\156\137\167\151\x64\164\150"])) {
            goto SN;
        }
        $gl = htmlspecialchars($_POST["\x6d\x6f\x5f\x73\x61\155\154\x5f\142\165\164\164\x6f\x6e\x5f\x77\x69\x64\164\150"]);
        SN:
        if (empty($_POST["\x6d\x6f\137\x73\x61\x6d\x6c\x5f\142\165\x74\x74\x6f\156\x5f\150\145\x69\x67\150\164"])) {
            goto A7;
        }
        $Ow = htmlspecialchars($_POST["\155\x6f\137\x73\141\x6d\x6c\x5f\x62\x75\x74\x74\157\x6e\137\x68\x65\151\147\x68\164"]);
        A7:
        if (empty($_POST["\x6d\157\137\163\x61\x6d\x6c\x5f\x62\x75\x74\164\x6f\x6e\x5f\143\x75\x72\x76\145"])) {
            goto Pv;
        }
        $Ep = htmlspecialchars($_POST["\x6d\157\x5f\x73\141\x6d\154\x5f\x62\x75\164\164\157\x6e\137\x63\x75\162\166\145"]);
        Pv:
        if (empty($_POST["\x6d\157\137\163\x61\x6d\154\137\x62\x75\x74\164\157\x6e\137\x63\x6f\x6c\157\162"])) {
            goto sB;
        }
        $KU = htmlspecialchars($_POST["\x6d\x6f\137\x73\x61\x6d\x6c\137\x62\x75\x74\x74\157\156\137\143\157\154\157\162"]);
        sB:
        if (empty($_POST["\x6d\157\x5f\x73\141\x6d\154\137\142\x75\x74\x74\x6f\x6e\x5f\164\x68\x65\x6d\x65"])) {
            goto EG;
        }
        $VX = htmlspecialchars($_POST["\155\x6f\x5f\x73\x61\x6d\x6c\137\142\x75\x74\x74\157\x6e\137\x74\150\x65\x6d\145"]);
        EG:
        if (empty($_POST["\x6d\157\x5f\163\141\155\x6c\x5f\142\x75\164\x74\x6f\156\137\164\145\170\x74"])) {
            goto sb;
        }
        $XI = htmlspecialchars($_POST["\155\157\137\x73\141\x6d\x6c\x5f\x62\165\x74\164\x6f\156\x5f\x74\x65\170\164"]);
        if (!(empty($XI) || $XI == "\x4c\157\x67\x69\156")) {
            goto iv;
        }
        $XI = "\x4c\x6f\147\151\156";
        iv:
        $XI = str_replace("\43\x23\111\104\x50\x23\43", $pK, $XI);
        sb:
        if (empty($_POST["\x6d\x6f\x5f\x73\141\155\154\x5f\x66\157\x6e\x74\x5f\143\157\x6c\157\162"])) {
            goto hW;
        }
        $Nw = htmlspecialchars($_POST["\155\x6f\137\x73\x61\x6d\x6c\x5f\x66\x6f\156\x74\137\143\157\x6c\x6f\x72"]);
        hW:
        if (empty($_POST["\155\x6f\137\x73\x61\155\154\x5f\146\x6f\156\164\x5f\x73\151\x7a\x65"])) {
            goto g6;
        }
        $tl = htmlspecialchars($_POST["\x6d\157\137\163\141\155\154\x5f\x66\x6f\156\x74\137\163\151\x7a\x65"]);
        g6:
        if (empty($_POST["\x73\163\x6f\137\142\165\164\x74\x6f\156\137\154\x6f\147\x69\x6e\137\x66\x6f\162\155\137\160\157\x73\x69\x74\151\x6f\156"])) {
            goto by;
        }
        $Uo = htmlspecialchars($_POST["\163\x73\x6f\x5f\x62\165\164\164\x6f\x6e\137\154\157\x67\151\156\x5f\146\x6f\162\155\x5f\x70\x6f\x73\151\x74\x69\157\156"]);
        by:
        $u7 = array("\141\144\x64\x5f\142\165\x74\x74\157\x6e\x5f\x77\160\x5f\x6c\157\x67\x69\156" => $aW, "\x75\x73\x65\x5f\x62\165\x74\x74\x6f\x6e\x5f\x61\163\137\x73\150\x6f\162\164\143\x6f\144\145" => $C7, "\165\x73\145\x5f\142\x75\x74\164\157\x6e\137\x61\x73\137\x77\151\144\147\145\x74" => $U9, "\142\165\164\x74\157\156\x5f\x74\171\160\145" => $VX, "\x62\x75\x74\164\x6f\x6e\x5f\x73\151\x7a\145" => $Bw, "\142\165\164\164\x6f\156\137\x77\151\144\x74\150" => $gl, "\x62\165\x74\164\157\156\x5f\150\145\x69\x67\150\164" => $Ow, "\142\165\164\x74\157\156\137\x63\x75\x72\166\145" => $Ep, "\x62\x75\x74\x74\157\x6e\x5f\x63\x6f\154\157\162" => $KU, "\x62\x75\164\x74\157\156\x5f\164\145\x78\164" => $XI, "\146\157\x6e\x74\137\x63\x6f\154\157\x72" => $Nw, "\146\157\156\x74\137\x73\x69\172\x65" => $tl, "\142\165\164\164\157\156\137\160\157\163\x69\164\x69\157\156" => $Uo);
        $id = EnvironmentHelper::getOptionForSelectedEnvironment("\163\x61\155\x6c\137\163\x73\x6f\x5f\x62\x75\164\164\x6f\156\137\x69\144\160", true);
        $id[$pK] = $u7;
        $s6->mo_save_environment_settings("\x73\141\x6d\154\137\163\x73\x6f\x5f\142\165\164\x74\157\x6e\137\x69\x64\160", $id);
        $s6->mo_save_environment_settings("\163\141\155\154\137\163\x65\x6c\145\143\x74\137\151\x64\160\137\156\141\x6d\145", $pK);
        update_option("\x6d\157\137\x73\x61\155\154\137\x6d\145\163\x73\141\147\145", "\114\x6f\x67\x69\x6e\40\142\x75\164\164\157\x6e\x20\165\x70\x64\141\164\145\x64\x20\x73\165\143\x63\x65\163\x73\x66\165\x6c\x6c\x79\x2e");
        SAMLSPUtilities::mo_saml_show_success_message();
        yl:
        if (!self::mo_check_option_admin_referer("\x73\163\157\x5f\x62\165\164\x74\157\156\137\151\144\x70\137\156\x61\155\145\137\x6f\160\x74\x69\157\156")) {
            goto SX;
        }
        if (!empty($_POST["\163\x73\157\x5f\x62\165\x74\164\x6f\x6e\137\x69\144\x70"])) {
            goto BO;
        }
        $BB = "\104\x45\106\101\x55\x4c\x54";
        goto AO;
        BO:
        $BB = htmlspecialchars($_POST["\x73\163\157\x5f\x62\165\164\x74\157\x6e\137\x69\144\160"]);
        AO:
        $s6->mo_save_environment_settings("\x73\141\155\x6c\x5f\163\145\x6c\x65\143\164\137\x69\x64\160\137\156\141\155\145", $BB);
        SX:
        if (!empty($_POST["\x6f\160\164\151\157\x6e"]) and $_POST["\x6f\160\x74\151\157\x6e"] == "\162\145\x73\145\x74\137\x73\163\157\x5f\x62\x75\x74\x74\157\156\x5f\x6f\160\164\151\x6f\x6e") {
            goto Zp;
        }
        if (self::mo_check_option_admin_referer("\143\154\x65\141\162\x5f\x61\164\164\162\x73\137\154\151\163\x74")) {
            goto Uq;
        }
        if (self::mo_check_option_admin_referer("\155\x6f\137\x73\141\x6d\154\137\x63\154\x6f\163\145\x5f\x6e\157\164\x69\x63\145")) {
            goto xf;
        }
        if (self::mo_check_option_admin_referer("\x6d\157\137\163\141\155\154\137\141\165\164\157\137\x72\x65\144\151\162\145\143\164\151\157\156\x5f\157\160\164\x69\157\x6e\x5f\x66\157\x72\155")) {
            goto M4;
        }
        if (self::mo_check_option_admin_referer("\x6d\157\x5f\163\141\x6d\x6c\137\145\x6e\x61\x62\x6c\x65\137\162\x73\163\x5f\141\143\x63\145\163\x73\x5f\157\x70\164\x69\157\x6e")) {
            goto Lv;
        }
        if (self::mo_check_option_admin_referer("\155\157\137\x73\141\155\154\137\x66\157\x72\x63\x65\x5f\141\165\x74\x68\145\x6e\164\151\x63\x61\164\x69\x6f\156\137\157\160\164\x69\x6f\x6e")) {
            goto E7;
        }
        if (self::mo_check_option_admin_referer("\x6d\157\137\163\x61\x6d\154\x5f\x64\x6f\x6d\141\x69\x6e\x5f\x6d\141\x70\160\151\x6e\x67\137\x63\x6f\156\x66\151\147")) {
            goto IS;
        }
        if (mo_saml_is_sp_configured() && self::mo_check_option_admin_referer("\x6d\157\x5f\163\141\155\154\x5f\141\154\154\157\167\x5f\x77\x70\137\x73\x69\147\156\151\x6e\137\157\160\x74\x69\157\x6e")) {
            goto R8;
        }
        if (self::mo_check_option_admin_referer("\146\x65\x64\145\162\x61\164\x69\157\x6e")) {
            goto pw;
        }
        if (self::mo_check_option_admin_referer("\155\x6f\137\x73\x61\155\154\137\141\x64\x64\137\x66\145\144\145\x72\x61\x74\x69\157\156\x73")) {
            goto IK;
        }
        if (self::mo_check_option_admin_referer("\x65\x64\x69\164\137\x66\145\144\x65\x72\x61\164\151\157\156")) {
            goto mB;
        }
        if (self::mo_check_option_admin_referer("\x64\145\x6c\x65\x74\x65\x5f\x66\x65\x64\145\x72\141\x74\x69\157\x6e")) {
            goto UW;
        }
        if (!empty($sZ) && ($HF["\142\165\x6c\x6b\137\x64\x65\x61\143\164\x69\x76\141\x74\x65"] === $sZ || $HF["\x62\165\154\153\137\x61\x63\x74\x69\x76\x61\x74\x65"] === $sZ)) {
            goto D9;
        }
        goto e2;
        Zp:
        if (mo_saml_is_sp_configured()) {
            goto CQ;
        }
        return;
        CQ:
        $s6->mo_save_environment_settings("\163\141\x6d\154\x5f\163\x73\157\x5f\142\165\164\164\x6f\156\137\x69\x64\160", '');
        update_option("\x6d\x6f\x5f\x73\141\x6d\154\x5f\155\x65\x73\x73\141\147\x65", "\114\x6f\x67\x69\156\40\x62\165\164\x74\157\156\x20\162\145\x73\145\x74\40\x73\x75\143\143\x65\163\163\146\165\x6c\154\171\x2e");
        SAMLSPUtilities::mo_saml_show_success_message();
        goto e2;
        Uq:
        $Ct = EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\x5f\x73\x61\155\x6c\x5f\x74\x65\x73\x74\x5f\143\157\x6e\146\151\x67\x5f\x61\x74\x74\x72\x73", true);
        if (empty($_POST["\151\144\x70\x5f\x6e\x61\155\x65"])) {
            goto GN;
        }
        $XE = htmlspecialchars($_POST["\151\144\x70\x5f\156\x61\x6d\145"]);
        if (empty($Ct[$XE])) {
            goto sL;
        }
        unset($Ct[$XE]);
        sL:
        GN:
        $s6->mo_save_environment_settings("\x6d\x6f\x5f\x73\x61\155\x6c\137\x74\x65\163\164\137\143\x6f\156\146\x69\147\137\141\x74\x74\162\x73", $Ct);
        update_option("\155\x6f\x5f\x73\x61\155\x6c\137\x6d\145\x73\x73\141\x67\x65", "\x41\x74\164\x72\151\x62\x75\164\x65\x73\40\154\x69\x73\x74\x20\162\x65\x6d\157\166\145\x64\40\163\x75\x63\143\145\163\163\x66\x75\x6c\x6c\171");
        SAMLSPUtilities::mo_saml_show_success_message();
        goto e2;
        xf:
        $nu = sanitize_text_field($_POST["\x6d\x6f\137\163\141\155\x6c\137\x63\x6c\157\163\145\x5f\x6e\157\164\151\143\x65"]);
        $TD = get_option("\x6d\157\x5f\163\141\x6d\x6c\137\x6e\157\x74\151\x63\x65\x5f\x74\157\x5f\144\151\163\160\x6c\x61\171");
        $TD[$nu] = false;
        update_option("\155\157\x5f\x73\x61\x6d\x6c\137\156\157\164\x69\143\145\x5f\164\157\x5f\x64\x69\163\x70\154\x61\171", $TD);
        goto e2;
        M4:
        if (mo_saml_is_sp_configured()) {
            goto ri;
        }
        update_option("\x6d\157\x5f\163\141\x6d\154\137\155\x65\163\x73\x61\147\145", "\120\x6c\145\141\163\x65\40\x63\157\155\x70\x6c\x65\x74\145\40\x3c\x61\x20\150\162\x65\x66\75\x22" . add_query_arg(array("\164\141\142" => "\x73\141\166\145"), $_SERVER["\122\x45\x51\x55\105\123\124\137\x55\122\111"]) . "\42\x20\57\x3e\123\145\x72\x76\x69\x63\x65\40\x50\x72\157\x76\151\144\x65\162\x3c\x2f\141\76\40\143\x6f\156\x66\151\x67\165\x72\x61\164\x69\157\156\40\x66\x69\162\163\x74\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        ri:
        if (!(!isset($_POST["\155\157\x5f\x73\141\x6d\x6c\137\145\156\141\x62\x6c\145\137\x61\x75\x74\157\x5f\162\x65\144\151\x72\x65\x63\x74"]) && isset($_POST["\155\x6f\137\x73\141\x6d\154\137\x61\x75\164\157\x5f\x72\x65\x64\x69\162\x65\x63\164\x69\157\156\137\157\160\164\151\157\x6e\x73"]))) {
            goto EL;
        }
        update_option("\155\157\137\163\x61\155\154\x5f\155\x65\163\x73\141\147\145", "\120\154\x65\141\163\145\40\x65\156\x61\142\x6c\145\x20\101\x75\164\x6f\x2d\x52\x65\144\x69\x72\145\x63\x74\40\x66\x72\x6f\x6d\40\123\x69\x74\x65\40\x4f\160\164\x69\157\156\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        EL:
        if (isset($_POST["\155\x6f\x5f\x73\141\x6d\x6c\137\145\x6e\141\142\x6c\x65\x5f\x61\165\164\157\137\x72\145\x64\151\162\x65\143\x74"]) && "\164\162\165\x65" === $_POST["\155\x6f\137\163\141\x6d\x6c\x5f\x65\156\x61\x62\154\145\137\141\165\164\157\x5f\162\145\x64\151\x72\x65\143\x74"]) {
            goto vZ;
        }
        $s6->mo_save_environment_settings("\x6d\157\137\x73\141\x6d\x6c\x5f\145\156\141\142\x6c\x65\137\141\165\164\x6f\137\x72\145\x64\151\x72\145\x63\164", "\165\156\143\150\x65\143\x6b\145\144");
        goto Zn;
        vZ:
        $QY = isset($_POST["\x6d\x6f\x5f\163\141\155\x6c\x5f\x61\165\164\157\137\162\x65\x64\151\162\145\x63\164\151\157\x6e\x5f\x6f\x70\x74\151\157\x6e\x73"]) ? $_POST["\x6d\157\x5f\x73\141\155\154\137\x61\x75\164\157\x5f\162\145\x64\x69\x72\145\143\x74\x69\x6f\x6e\x5f\157\x70\164\x69\x6f\156\163"] : '';
        $hY = isset($_POST["\155\157\137\163\141\x6d\x6c\x5f\160\165\x62\154\x69\x63\x5f\160\x61\147\x65\137\164\x6f\137\x72\145\x64\x69\x72\x65\143\x74"]) ? $_POST["\155\x6f\x5f\163\141\155\154\137\x70\165\142\154\151\143\x5f\160\141\x67\145\137\164\157\137\x72\145\144\x69\162\145\x63\164"] : '';
        $hY = SAMLSPUtilities::mo_saml_check_trailing_slash($hY);
        $s6->mo_save_environment_settings("\x6d\x6f\137\163\x61\x6d\154\137\145\x6e\x61\x62\154\x65\137\141\165\x74\x6f\137\x72\x65\144\x69\162\145\x63\x74", "\143\x68\145\143\153\145\x64");
        $s6->mo_save_environment_settings("\x6d\x6f\x5f\x73\141\x6d\x6c\137\141\x75\x74\x6f\137\x72\145\x64\151\x72\x65\x63\164\151\x6f\156\137\157\x70\164\x69\x6f\x6e\x73", $QY);
        if ("\x72\x65\x64\x69\162\x65\x63\x74\x5f\164\157\x5f\144\x65\x66\141\165\x6c\164\x5f\x69\144\x70" === $QY) {
            goto Dg;
        }
        if ("\x72\x65\144\151\x72\145\x63\x74\137\x74\x6f\137\x77\160\x5f\154\157\x67\x69\x6e" === $QY) {
            goto NL;
        }
        if ("\x72\x65\x64\151\x72\145\143\164\137\164\157\x5f\160\x75\x62\x6c\x69\x63\137\x70\141\x67\145" === $QY) {
            goto FB;
        }
        goto OZ;
        Dg:
        $s6->mo_save_environment_settings("\155\x6f\x5f\163\x61\155\x6c\x5f\162\145\144\x69\x72\145\x63\x74\x5f\144\x65\146\x61\x75\154\x74\137\x69\144\x70", "\x74\x72\x75\145");
        $s6->mo_save_environment_settings("\x6d\x6f\x5f\163\x61\155\154\137\162\x65\147\151\x73\x74\145\162\145\144\137\x6f\x6e\154\171\137\x61\x63\143\x65\x73\163", '');
        $s6->mo_save_environment_settings("\155\157\x5f\163\141\155\154\137\141\x75\x74\x6f\x5f\162\x65\x64\151\x72\145\143\164\x5f\x74\157\137\x70\165\142\x6c\151\143\x5f\160\x61\x67\x65", '');
        goto OZ;
        NL:
        $s6->mo_save_environment_settings("\155\157\x5f\163\x61\x6d\x6c\x5f\x72\x65\144\x69\x72\145\x63\x74\137\x64\x65\x66\141\x75\x6c\x74\x5f\151\144\160", '');
        $s6->mo_save_environment_settings("\155\157\137\x73\x61\x6d\154\137\162\x65\147\151\163\x74\x65\162\x65\144\x5f\157\x6e\x6c\x79\137\x61\143\x63\x65\163\x73", "\x74\x72\x75\145");
        $s6->mo_save_environment_settings("\x6d\157\x5f\163\141\x6d\x6c\x5f\141\165\x74\x6f\x5f\x72\145\144\151\x72\145\143\x74\x5f\164\157\137\x70\x75\x62\x6c\151\x63\x5f\160\x61\147\145", '');
        goto OZ;
        FB:
        if (empty($hY)) {
            goto dl;
        }
        $s6->mo_save_environment_settings("\x6d\157\137\163\x61\x6d\154\x5f\x69\x64\160\137\x6c\151\163\x74\137\x75\162\x6c", $hY);
        goto Es;
        dl:
        if (!empty(EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\137\x73\141\155\154\137\x69\144\x70\x5f\x6c\x69\x73\164\137\x75\162\x6c"))) {
            goto Jz;
        }
        $aG = get_option(mo_options_environments::Multiple_Licenses);
        if (!empty($aG)) {
            goto kl;
        }
        $Ty = home_url();
        goto tA;
        kl:
        $Ty = sanitize_text_field(EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\x5f\163\141\x6d\154\137\163\x70\x5f\x62\141\163\145\x5f\165\162\154"));
        tA:
        if (!(substr($Ty, -1) != "\x2f" && strpos($Ty, "\x3f") == false && strpos($Ty, "\x23") == false)) {
            goto b7;
        }
        $Ty = $Ty . "\x2f";
        b7:
        $s6->mo_save_environment_settings("\155\157\137\x73\x61\155\x6c\x5f\151\144\x70\137\x6c\151\163\164\137\x75\162\154", $Ty);
        Jz:
        Es:
        $s6->mo_save_environment_settings("\155\x6f\137\x73\141\x6d\154\137\x72\x65\x64\151\162\x65\x63\164\x5f\144\x65\146\x61\165\x6c\x74\x5f\x69\x64\160", '');
        $s6->mo_save_environment_settings("\x6d\157\x5f\x73\141\155\154\137\162\145\147\151\x73\164\x65\x72\x65\x64\x5f\x6f\x6e\154\171\137\141\143\143\x65\163\x73", '');
        $s6->mo_save_environment_settings("\x6d\x6f\x5f\x73\x61\x6d\154\137\x61\x75\164\157\x5f\162\x65\x64\x69\x72\145\143\x74\x5f\164\157\137\x70\165\142\154\x69\143\x5f\160\x61\x67\x65", "\164\x72\x75\145");
        OZ:
        Zn:
        update_option("\155\x6f\x5f\163\141\x6d\154\137\155\145\163\163\x61\147\145", "\x41\x75\164\x6f\x20\x72\x65\144\x69\x72\x65\x63\164\x69\157\x6e\x20\146\162\x6f\155\x20\163\151\164\x65\40\x6f\160\164\x69\157\156\163\x20\x73\141\x76\x65\144\40\x73\165\x63\143\145\x73\163\x66\x75\x6c\154\171\x2e");
        SAMLSPUtilities::mo_saml_show_success_message();
        goto e2;
        Lv:
        if (mo_saml_is_sp_configured()) {
            goto mz;
        }
        update_option("\x6d\157\x5f\x73\x61\x6d\x6c\x5f\155\x65\x73\x73\x61\x67\145", "\120\154\145\141\163\145\40\x63\157\x6d\160\x6c\x65\164\145\x20" . addLink("\123\145\162\x76\x69\143\145\x20\120\x72\157\166\151\144\x65\x72", add_query_arg(array("\x74\x61\142" => "\x73\x61\x76\145"), $_SERVER["\x52\105\x51\125\105\x53\124\x5f\x55\122\111"])) . "\40\x63\157\156\x66\151\147\x75\x72\141\164\x69\x6f\156\x20\x66\x69\x72\163\x74\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto Ps;
        mz:
        if (array_key_exists("\155\157\x5f\163\141\x6d\x6c\137\145\x6e\x61\142\154\145\137\162\163\x73\x5f\141\143\143\x65\x73\x73", $_POST)) {
            goto kH;
        }
        $YH = false;
        goto fh;
        kH:
        $YH = sanitize_text_field($_POST["\x6d\157\137\163\x61\155\154\x5f\145\156\x61\x62\x6c\x65\137\x72\163\163\137\141\x63\x63\145\x73\x73"]);
        fh:
        if ($YH == "\164\x72\x75\x65") {
            goto pY;
        }
        $s6->mo_save_environment_settings("\x6d\x6f\137\x73\141\155\x6c\137\145\x6e\x61\142\x6c\145\x5f\162\163\163\x5f\x61\143\x63\x65\x73\163", '');
        goto Vh;
        pY:
        $s6->mo_save_environment_settings("\x6d\157\x5f\x73\x61\x6d\x6c\x5f\x65\156\x61\x62\x6c\145\137\x72\x73\163\137\x61\143\143\145\x73\x73", "\x74\162\x75\145");
        Vh:
        update_option("\x6d\157\137\x73\141\155\x6c\137\x6d\x65\163\x73\x61\x67\145", "\122\123\x53\x20\x46\145\x65\144\x20\157\x70\164\x69\x6f\x6e\x20\x75\160\144\141\x74\x65\x64\x2e");
        SAMLSPUtilities::mo_saml_show_success_message();
        Ps:
        goto e2;
        E7:
        if (mo_saml_is_sp_configured()) {
            goto WQ;
        }
        update_option("\155\x6f\137\x73\x61\x6d\154\137\x6d\145\x73\163\141\x67\145", "\120\154\145\x61\x73\x65\40\143\157\155\160\154\145\x74\145\x20\74\141\x20\x68\x72\x65\146\x3d\x22" . add_query_arg(array("\164\x61\142" => "\163\141\166\x65"), $_SERVER["\122\x45\x51\x55\x45\123\124\137\125\x52\x49"]) . "\42\x20\x2f\76\123\x65\162\166\151\x63\145\40\x50\x72\x6f\x76\x69\x64\145\x72\74\x2f\x61\x3e\40\x63\x6f\x6e\x66\151\x67\x75\x72\141\164\151\x6f\x6e\x20\146\x69\x72\163\164\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto kQ;
        WQ:
        if (!empty($_POST["\155\157\x5f\163\141\155\x6c\x5f\146\x6f\x72\143\145\x5f\141\165\x74\150\145\156\x74\151\x63\x61\x74\x69\157\x6e"])) {
            goto PB;
        }
        $Ri = "\x66\141\154\163\145";
        goto ry;
        PB:
        $Ri = $_POST["\x6d\157\137\163\141\155\x6c\x5f\146\x6f\162\x63\x65\137\x61\x75\164\150\x65\x6e\164\151\x63\x61\x74\x69\x6f\x6e"];
        ry:
        $s6->mo_save_environment_settings("\155\157\137\163\141\x6d\x6c\137\x66\x6f\x72\143\x65\x5f\141\165\164\x68\x65\156\x74\151\143\x61\x74\151\x6f\156", $Ri);
        update_option("\x6d\157\137\163\x61\155\154\x5f\x6d\145\163\163\141\x67\x65", "\x41\x75\164\x6f\40\x72\x65\144\x69\162\x65\143\x74\151\x6f\156\40\146\162\x6f\x6d\40\x73\x69\164\145\40\x6f\160\164\151\157\x6e\x73\x20\x73\x61\x76\145\x64\40\x73\x75\143\143\x65\x73\163\146\x75\154\154\x79\56");
        SAMLSPUtilities::mo_saml_show_success_message();
        kQ:
        goto e2;
        IS:
        if (mo_saml_is_sp_configured()) {
            goto J9;
        }
        update_option("\155\x6f\137\163\141\155\x6c\137\155\145\163\x73\141\147\145", "\x50\154\145\x61\x73\x65\x20\143\157\155\x70\x6c\145\164\x65\40\74\x61\x20\x68\162\x65\x66\x3d\x22" . add_query_arg(array("\x74\x61\142" => "\x73\x61\166\x65"), $_SERVER["\x52\x45\x51\125\x45\123\124\x5f\125\x52\111"]) . "\x22\40\x2f\76\x53\x65\162\x76\x69\x63\145\x20\120\162\x6f\x76\151\144\145\162\74\x2f\141\76\40\143\157\x6e\146\x69\x67\x75\162\x61\164\x69\x6f\156\x20\146\x69\162\x73\x74\x2e");
        goto cz;
        J9:
        $bY = "\x66\141\x6c\163\145";
        if (empty($_POST["\x6d\x6f\x5f\163\141\x6d\x6c\x5f\145\x6e\141\142\154\145\137\144\157\155\x61\x69\156\x5f\155\x61\x70\160\151\156\147"])) {
            goto lc;
        }
        $gu = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\x6d\x6c\137\151\144\145\x6e\164\151\164\x79\x5f\160\162\157\x76\x69\144\x65\x72\x73", true);
        $gC = array();
        foreach ($gu as $R2 => $EB) {
            $Zc = "\163\x61\x6d\x6c\x5f\x64\157\155\x61\151\x6e\x5f\155\x61\160\160\151\156\147\x5f" . $R2;
            $GH = !empty($_POST[$Zc]) ? sanitize_text_field($_POST[$Zc]) : '';
            $gC[$R2] = SAMLSPUtilities::mo_saml_trim_semi_colon_separated_values($GH);
            if (preg_match("\x2f\x5e\x5c\x53\52\x24\x2f", $gC[$R2])) {
                goto Qm;
            }
            update_option("\155\157\137\163\x61\x6d\x6c\137\155\145\163\x73\141\x67\x65", "\123\x70\141\x63\x65\x73\x20\141\162\x65\x20\156\x6f\164\x20\141\x6c\x6c\x6f\167\x65\144\40\151\x6e\40\x74\x68\x65\40\x64\x6f\155\x61\x69\x6e\40\166\141\154\x75\x65\163\56");
            SAMLSPUtilities::mo_saml_show_error_message();
            return;
            Qm:
            sv:
        }
        YS:
        $gC = array_filter($gC, "\x66\151\x6c\164\x65\162\x5f\145\155\160\x74\x79\x5f\x76\x61\x6c\x75\x65\x73");
        $s6->mo_save_environment_settings("\x73\141\155\x6c\137\x69\144\160\137\x64\x6f\x6d\141\x69\x6e\x5f\155\141\160\160\x69\156\x67", $gC);
        $bY = $_POST["\x6d\x6f\x5f\x73\x61\155\x6c\x5f\x65\x6e\x61\142\x6c\145\137\144\x6f\155\141\x69\156\137\x6d\x61\x70\160\x69\x6e\x67"];
        $ZN = !empty($_POST["\144\x6f\x6d\x61\151\x6e\137\154\x6f\147\x69\x6e\x5f\146\141\151\154\145\144\137\x6f\x70\164\x69\x6f\x6e"]) ? sanitize_text_field($_POST["\144\157\155\141\x69\156\x5f\154\157\x67\x69\156\x5f\x66\141\x69\x6c\x65\x64\x5f\x6f\x70\x74\x69\x6f\x6e"]) : '';
        if ($ZN === "\x72\145\x64\x69\x72\145\143\164\x5f\164\157\137\x64\145\x66\x61\165\x6c\164\137\x69\x64\x70") {
            goto jJ;
        }
        $h3 = "\164\162\x75\145";
        $mT = '';
        goto SM;
        jJ:
        $h3 = '';
        $mT = "\164\x72\x75\x65";
        SM:
        $s6->mo_save_environment_settings("\x6d\157\x5f\163\x61\155\x6c\137\144\157\155\141\x69\x6e\137\x6c\157\147\x69\156\137\146\x61\x69\154", $h3);
        $s6->mo_save_environment_settings("\x6d\x6f\137\x73\141\x6d\x6c\137\x66\x61\x6c\154\142\141\143\x6b\x5f\164\157\x5f\144\x65\146\141\165\x6c\164", $mT);
        lc:
        $s6->mo_save_environment_settings("\155\x6f\137\163\x61\x6d\x6c\137\145\156\x61\142\x6c\x65\x5f\144\x6f\x6d\141\x69\156\x5f\155\x61\x70\160\151\x6e\x67", $bY);
        update_option("\x6d\x6f\x5f\x73\141\155\154\x5f\x6d\145\163\x73\x61\x67\145", "\104\157\155\x61\151\x6e\x20\115\x61\160\x70\x69\x6e\147\40\144\145\164\141\x69\x6c\x73\40\x73\x61\x76\145\x64\40\x73\165\143\143\145\x73\x73\146\x75\154\154\171\56");
        SAMLSPUtilities::mo_saml_show_success_message();
        cz:
        goto e2;
        R8:
        $Qn = !empty($_POST["\155\157\137\163\141\155\x6c\137\142\x61\143\x6b\x64\157\x6f\162\x5f\165\162\x6c"]) ? trim($_POST["\x6d\x6f\x5f\x73\141\x6d\x6c\137\142\x61\143\x6b\x64\x6f\x6f\162\x5f\x75\x72\154"]) : '';
        if (isset($_POST["\x6d\157\x5f\163\141\x6d\x6c\137\141\154\154\x6f\167\137\167\160\x5f\x73\151\x67\x6e\151\x6e"]) && !empty(sanitize_text_field($_POST["\155\157\137\163\x61\155\154\x5f\x61\154\154\157\x77\137\167\160\137\x73\151\147\x6e\151\156"]))) {
            goto vy;
        }
        $jN = "\x66\141\154\163\x65";
        goto l1;
        vy:
        $jN = sanitize_text_field($_POST["\x6d\157\137\x73\x61\x6d\x6c\137\141\x6c\154\157\x77\x5f\167\x70\137\163\x69\147\x6e\151\156"]);
        l1:
        $s6->mo_save_environment_settings("\x6d\x6f\x5f\x73\141\155\154\x5f\x61\x6c\154\x6f\x77\137\167\160\137\163\151\147\156\151\156", $jN);
        if (!preg_match("\57\136\133\x61\x2d\172\x41\x2d\132\x30\55\x39\x5f\134\55\135\53\44\x2f", $Qn)) {
            goto qB;
        }
        $s6->mo_save_environment_settings("\155\x6f\137\163\141\155\154\x5f\x62\141\143\153\x64\157\x6f\x72\137\165\162\154", $Qn);
        SAMLSPUtilities::mo_saml_show_success_message();
        update_option("\155\x6f\137\163\141\x6d\154\137\x6d\145\163\x73\141\x67\x65", "\x53\151\147\156\40\151\156\40\x6f\160\x74\x69\x6f\x6e\x73\x20\165\160\x64\141\x74\145\x64\56");
        goto ia;
        qB:
        update_option("\x6d\x6f\137\163\141\155\154\x5f\x6d\145\163\163\141\147\x65", "\x4f\156\154\171\40\141\x6c\x70\150\141\x6e\x75\155\145\x72\151\x63\40\143\150\141\x72\141\x63\x74\x65\162\x20\x61\x72\145\40\141\x6c\x6c\157\x77\145\x64\x2e\40\x41\x6c\163\157\40\x74\150\x65\40\146\x69\145\x6c\144\40\x63\x61\x6e\156\x6f\164\40\142\x65\40\x65\155\x70\x74\171\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        ia:
        goto e2;
        pw:
        $PZ = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\x73\141\x6d\x6c\x5f\146\x65\x64\145\x72\141\164\151\157\156\163", true);
        $hK = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\137\x73\x61\155\154\x5f\x73\x70\x5f\x62\141\x73\145\x5f\165\x72\154");
        if (!empty($hK)) {
            goto H4;
        }
        $hK = home_url();
        H4:
        $Uy = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\x5f\x73\x61\155\154\x5f\x73\160\137\x65\156\164\151\164\171\x5f\151\144");
        if (!empty($Uy)) {
            goto vr;
        }
        $Uy = $hK . "\57\167\160\x2d\x63\x6f\x6e\x74\x65\156\164\x2f\x70\154\x75\x67\x69\x6e\x73\x2f\155\x69\156\x69\157\x72\x61\156\147\x65\x2d\x73\141\x6d\154\55\62\60\55\x73\151\156\x67\154\x65\x2d\163\151\x67\156\x2d\x6f\x6e\x2f";
        vr:
        $t4 = home_url() . "\x2f\x3f\x6f\160\x74\151\x6f\x6e\x3d\163\x61\x6d\x6c\x5f\x75\163\145\x72\137\154\x6f\147\151\156";
        if ($PZ) {
            goto j5;
        }
        $PZ["\111\x6e\103\157\x6d\155\157\x6e"] = array("\x66\145\x64\x65\x72\141\x74\x69\157\156\137\156\141\155\145" => "\x49\156\103\x6f\x6d\x6d\x6f\x6e", "\144\x69\x73\143\x6f\x76\x65\162\x79\137\165\x72\x6c" => "\150\x74\164\160\163\72\x2f\57\167\x61\171\146\x2e\x69\x6e\x63\x6f\155\x6d\x6f\156\146\145\x64\145\162\x61\x74\151\x6f\156\56\157\x72\147\57\x44\123\57\127\101\131\x46", "\x70\141\162\x61\155\145\x74\145\162\x73" => array("\145\x6e\164\151\164\x79\111\x44" => $Uy, "\162\145\164\165\162\x6e" => $t4), "\x65\156\x61\142\154\x65" => false);
        $PZ["\110\101\x4b\x41"] = array("\x66\145\144\x65\162\141\164\x69\x6f\156\137\x6e\x61\155\x65" => "\x48\101\113\101", "\144\x69\163\x63\x6f\166\x65\x72\x79\x5f\x75\x72\x6c" => "\x68\x74\164\x70\163\72\57\57\x68\x61\x6b\141\56\146\165\x6e\145\x74\x2e\146\x69\x2f\x73\150\151\142\x62\x6f\x6c\145\164\150\x2f\x57\x41\131\x46", "\x70\141\162\x61\x6d\145\x74\145\x72\163" => array("\145\x6e\164\x69\164\x79\111\104" => $Uy, "\x72\x65\x74\x75\162\x6e" => $t4), "\x65\x6e\x61\x62\154\145" => false);
        $PZ["\110\x4b\x41\106"] = array("\146\145\144\x65\162\141\x74\x69\x6f\156\137\x6e\x61\155\x65" => "\110\113\x41\x46", "\x64\x69\163\x63\x6f\166\x65\162\x79\137\x75\x72\x6c" => "\150\164\164\x70\x73\x3a\x2f\x2f\144\163\56\x68\153\141\146\56\145\144\165\x2e\x68\153\x2f\x64\x69\x73\143\x6f\x76\145\x72\x79", "\160\141\162\141\155\x65\164\145\x72\163" => array("\x65\156\164\x69\164\171\x49\104" => $Uy, "\162\145\164\x75\162\x6e" => $t4), "\145\156\x61\x62\x6c\x65" => false);
        j5:
        if (!empty($_POST["\145\x6e\141\x62\154\x65\137\x66\145\144\x5f\x73\x73\x6f"])) {
            goto cI;
        }
        foreach ($PZ as $JX => $pO) {
            $PZ[$JX]["\x65\156\x61\142\154\x65"] = false;
            bb:
        }
        KP:
        goto hf;
        cI:
        $gG = $_POST["\x65\156\141\x62\154\145\137\146\x65\x64\x5f\x73\x73\x6f"];
        foreach ($PZ as $JX => $pO) {
            if (SAMLSPUtilities::mo_saml_in_array($JX, $gG)) {
                goto V8;
            }
            $PZ[$JX]["\145\156\141\142\154\x65"] = false;
            goto Xu;
            V8:
            $PZ[$JX]["\145\156\141\142\154\x65"] = true;
            Xu:
            r4:
        }
        IP:
        hf:
        $s6->mo_save_environment_settings("\155\x6f\137\x73\141\x6d\154\x5f\x66\145\x64\145\x72\x61\x74\x69\x6f\x6e\163", $PZ);
        update_option("\155\157\137\163\x61\x6d\x6c\137\155\x65\x73\163\x61\147\x65", "\106\x65\x64\x65\162\x61\164\x69\x6f\156\x20\x69\x6e\146\x6f\x72\x6d\141\164\x69\157\156\x20\x73\x61\166\x65\x64\40\x73\165\x63\143\145\x73\x73\146\165\154\x6c\171\x2e");
        SAMLSPUtilities::mo_saml_show_success_message();
        goto e2;
        IK:
        $PZ = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\x6f\x5f\x73\x61\x6d\x6c\137\x66\145\144\x65\162\x61\x74\151\157\x6e\x73", true);
        if (!(!empty($_POST["\x66\145\144\x65\x72\141\x74\151\157\x6e\x5f\156\x61\155\145"]) and !empty($_POST["\x64\151\x73\143\157\x76\145\162\x79\137\x75\162\154"]))) {
            goto Mf;
        }
        $SC = $_POST["\x66\145\x64\145\162\141\x74\151\x6f\x6e\137\156\141\155\145"];
        $vh = $_POST["\x64\151\x73\143\x6f\166\145\162\171\x5f\165\162\154"];
        $gb = $this->mo_saml_save_federation_parameters();
        $PZ[$SC] = array("\x66\x65\144\145\x72\x61\164\x69\157\x6e\137\156\141\155\x65" => $SC, "\x64\x69\163\143\157\166\x65\x72\x79\x5f\165\162\x6c" => $vh, "\145\x6e\141\142\x6c\x65" => false);
        if (empty($gb)) {
            goto FD;
        }
        $PZ[$SC]["\x70\141\x72\141\x6d\145\x74\145\162\x73"] = $gb;
        FD:
        $s6->mo_save_environment_settings("\x6d\157\137\163\x61\155\154\x5f\x66\x65\x64\145\162\141\164\151\157\x6e\163", $PZ);
        Mf:
        goto e2;
        mB:
        $PZ = EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\x5f\x73\x61\155\154\x5f\x66\x65\144\145\x72\141\164\x69\x6f\x6e\163", true);
        $SC = $_POST["\146\x65\144\145\162\141\164\151\157\x6e\137\x6e\x61\155\145"];
        if (empty($PZ[$SC])) {
            goto SP;
        }
        $pO = $PZ[$SC];
        $vh = $_POST["\x64\151\x73\143\x6f\166\145\162\171\x5f\x75\162\154"];
        $pO["\x64\151\x73\x63\x6f\166\x65\162\x79\x5f\165\x72\154"] = $vh;
        $gb = $this->mo_saml_save_federation_parameters();
        if (empty($gb)) {
            goto bk;
        }
        $pO["\x70\141\x72\x61\x6d\145\x74\145\162\x73"] = $gb;
        bk:
        $PZ[$SC] = $pO;
        $s6->mo_save_environment_settings("\x6d\x6f\137\163\141\155\154\137\146\145\x64\145\162\141\164\151\157\156\x73", $PZ);
        SP:
        goto e2;
        UW:
        $PZ = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\x5f\163\141\x6d\x6c\137\x66\x65\x64\145\x72\x61\164\151\x6f\156\163", true);
        $SC = $_POST["\146\145\x64\x65\x72\141\x74\x69\157\x6e\137\156\141\155\x65"];
        unset($PZ[$SC]);
        $s6->mo_save_environment_settings("\x6d\157\x5f\163\x61\155\x6c\x5f\146\x65\144\x65\x72\141\164\151\157\x6e\163", $PZ);
        goto e2;
        D9:
        $HE = isset($_POST["\155\157\x5f\163\x61\x6d\154\137\x73\145\154\145\x63\164\145\144\137\144\145\146\141\165\154\x74\x5f\151\144\160"]) ? sanitize_text_field($_POST["\155\x6f\137\x73\141\155\154\137\x73\145\154\145\143\x74\x65\x64\x5f\x64\145\146\141\165\154\x74\x5f\x69\144\160"]) : '';
        $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\155\x6c\x5f\x69\x64\145\x6e\164\x69\x74\x79\x5f\160\x72\x6f\166\151\x64\145\162\163", true);
        $vX = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\x61\155\154\137\144\x65\146\141\165\154\x74\x5f\x69\x64\160");
        $G_ = count($kd);
        if (!(in_array($vX, $kd) && $HF["\142\x75\x6c\x6b\x5f\144\x65\141\x63\164\151\x76\141\x74\x65"] === $sZ)) {
            goto PX;
        }
        if (!empty($HE) && array_key_exists($HE, $rK)) {
            goto R6;
        }
        if (count($rK) > 1) {
            goto km;
        }
        goto Ls;
        R6:
        if ($rK[$HE]["\x65\156\x61\142\x6c\145\x5f\x69\x64\x70"]) {
            goto we;
        }
        $rK[$HE]["\145\156\x61\x62\154\145\137\151\x64\160"] = true;
        $s6->mo_save_environment_settings("\x73\141\155\x6c\x5f\151\144\145\x6e\164\x69\x74\171\137\160\x72\157\166\x69\x64\x65\162\x73", $rK);
        we:
        if (!in_array($HE, $kd)) {
            goto ic;
        }
        $R2 = array_search($HE, $kd);
        if (!($R2 !== false)) {
            goto df;
        }
        unset($kd[$R2]);
        df:
        ic:
        $s6->mo_save_environment_settings("\x73\141\x6d\x6c\137\144\x65\x66\x61\165\x6c\164\x5f\x69\x64\160", $HE);
        goto Ls;
        km:
        update_option("\155\157\x5f\x73\x61\x6d\x6c\x5f\155\145\x73\x73\141\x67\x65", "\x50\154\145\x61\163\x65\x20\x63\150\x61\156\147\145\x20\x74\x68\x65\40\144\x65\x66\x61\165\x6c\x74\x20\111\104\120\40\x62\x65\146\157\162\x65\x20\144\145\154\145\x74\151\156\x67\x20\x69\164\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        Ls:
        PX:
        if (!($HF["\x62\165\154\x6b\137\x61\143\164\151\166\141\164\145"] === $sZ)) {
            goto r0;
        }
        $cA = SAMLSPUtilities::mo_saml_get_enabled_idps($rK, $kd);
        if (SAMLSPUtilities::mo_saml_check_idp_limit($cA)) {
            goto od;
        }
        return;
        od:
        r0:
        foreach ($kd as $BB) {
            if ($HF["\x62\x75\154\x6b\137\141\143\x74\x69\166\x61\164\x65"] === $sZ) {
                goto iw;
            }
            if ($HF["\142\165\154\153\137\144\x65\x61\x63\164\151\x76\x61\x74\x65"] === $sZ) {
                goto Sq;
            }
            goto vx;
            iw:
            $rK[$BB]["\x65\156\141\142\x6c\145\x5f\x69\144\160"] = true;
            goto vx;
            Sq:
            $rK[$BB]["\145\x6e\x61\x62\154\145\x5f\x69\x64\x70"] = false;
            vx:
            Dt:
        }
        fC:
        $t3 = $G_ > 1 ? "\163" : '';
        if (empty($rK[''])) {
            goto qk;
        }
        unset($rK['']);
        qk:
        $s6->mo_save_environment_settings("\163\141\x6d\x6c\137\151\144\145\x6e\164\x69\x74\x79\137\x70\x72\157\x76\x69\144\x65\x72\x73", $rK);
        update_option("\x6d\x6f\137\x73\x61\155\154\137\x6d\145\163\163\141\x67\145", "\x49\x44\120" . $t3 . "\x20" . $sZ . "\x64\x20\163\x75\143\143\x65\x73\x73\x66\165\x6c\x6c\x79\56");
        SAMLSPUtilities::mo_saml_show_success_message();
        e2:
        if (self::mo_check_option_admin_referer("\155\x6f\x5f\x73\x61\x6d\154\137\x63\150\145\143\x6b\137\154\x69\x63\145\156\163\145")) {
            goto lV;
        }
        if (self::mo_check_option_admin_referer("\x6d\157\137\163\141\155\x6c\x5f\x76\x65\x72\151\x66\x79\137\154\151\x63\x65\x6e\163\x65")) {
            goto TI;
        }
        goto wI;
        lV:
        $this->mo_saml_sso_fetch_exisitng_configuration();
        $Uw = new Customersaml();
        $Qm = $Uw->check_customer_ln();
        if (!($Qm === false)) {
            goto Xn;
        }
        update_option("\x6d\x6f\x5f\163\x61\155\x6c\x5f\155\145\x73\x73\141\x67\145", "\x53\x6f\x6d\145\164\150\151\x6e\x67\x20\167\x65\156\164\x20\x77\x72\157\156\x67\x20\x77\x68\x69\x6c\x65\40\160\x72\x6f\x63\145\163\163\x69\x6e\147\40\x74\150\151\163\x20\x72\145\x71\x75\145\163\x74\56\40\x50\154\145\x61\163\145\x20\154\157\147\x69\x6e\x20\x61\156\x64\40\164\x72\x79\x20\141\x67\x61\151\156");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        Xn:
        if ($Qm) {
            goto Vz;
        }
        return;
        Vz:
        $Qm = json_decode($Qm, true);
        if (strcasecmp($Qm["\x73\164\x61\x74\165\163"], "\x53\x55\103\x43\105\123\123") == 0) {
            goto MO;
        }
        $R2 = get_option("\x6d\x6f\x5f\163\x61\x6d\154\x5f\143\165\x73\164\157\x6d\145\x72\x5f\164\x6f\x6b\x65\156");
        update_option("\163\151\x74\145\137\x63\x6b\x5f\x6c", AESEncryption::encrypt_data("\x66\141\x6c\163\145", $R2));
        update_option("\155\x6f\137\x73\141\x6d\154\x5f\155\145\x73\x73\141\x67\x65", "\131\157\x75\40\x68\x61\x76\x65\40\x6e\157\x74\40\165\160\147\162\x61\x64\x65\x64\40\x79\x65\x74\x2e\40" . addLink("\103\154\x69\143\153\x20\x68\145\x72\145", Mo_Saml_External_Links::PRICING_PAGE_LINK) . "\x20\x74\x6f\40\x75\x70\147\x72\x61\x64\145\40\164\x6f\x20\x70\x72\145\x6d\151\x75\x6d\40\x76\145\162\163\x69\x6f\156\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto ii;
        MO:
        if (!empty($Qm["\154\x69\x63\x65\x6e\x73\x65\x50\x6c\141\156"])) {
            goto Q2;
        }
        $R2 = get_option("\x6d\157\137\x73\x61\x6d\154\137\143\165\x73\x74\x6f\155\145\162\x5f\x74\157\x6b\x65\156");
        update_option("\163\x69\x74\145\x5f\x63\153\x5f\154", AESEncryption::encrypt_data("\146\141\x6c\163\145", $R2));
        update_option("\x6d\x6f\137\163\x61\x6d\154\x5f\x6d\x65\163\x73\141\x67\x65", "\x59\x6f\x75\40\150\141\166\x65\40\x6e\x6f\164\40\x75\x70\147\x72\x61\144\x65\x64\40\x79\x65\164\x2e\x20" . addLink("\103\154\151\143\x6b\x20\x68\x65\162\145", Mo_Saml_External_Links::PRICING_PAGE_LINK) . "\x20\x74\157\40\x75\160\147\x72\141\144\x65\40\164\x6f\40\160\162\x65\x6d\x69\x75\x6d\x20\x76\145\162\163\x69\x6f\156\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto Er;
        Q2:
        $VO = $Qm["\x6c\151\143\x65\156\163\x65\120\154\x61\156"];
        update_option("\155\157\x5f\x73\x61\155\x6c\137\154\x69\143\x65\x6e\x73\145\137\156\x61\x6d\x65", base64_encode($VO));
        $R2 = get_option("\155\x6f\x5f\x73\x61\155\x6c\137\x63\165\163\x74\157\x6d\x65\162\x5f\x74\157\153\145\156");
        if (empty($Qm["\156\157\117\146\125\x73\x65\x72\x73"])) {
            goto UR;
        }
        update_option("\x6d\157\137\x73\x61\155\154\137\x75\x73\x72\137\x6c\x6d\x74", AESEncryption::encrypt_data($Qm["\x6e\157\x4f\146\125\x73\145\x72\163"], $R2));
        UR:
        if (empty($Qm["\x6c\x69\143\x65\156\163\145\x45\170\160\151\x72\171"])) {
            goto Ar;
        }
        Mo_License_Service::update_license_expiry($Qm["\154\151\143\145\156\163\145\105\x78\160\151\x72\171"]);
        Ar:
        Mo_License_Service::update_trial_status($Qm["\x74\x72\x69\x61\154"]);
        update_option(Mo_Saml_Options::LAST_SYNCED_TIME, time());
        update_option("\163\151\164\145\x5f\x63\x6b\x5f\154", AESEncryption::encrypt_data("\x74\162\x75\x65", $R2));
        update_customer_idp_count($Qm);
        $py = plugin_dir_path(__FILE__);
        $eV = home_url();
        $eV = trim($eV, "\57");
        if (preg_match("\x23\136\150\164\164\160\x28\163\x29\77\x3a\57\x2f\x23", $eV)) {
            goto Gf;
        }
        $eV = "\150\164\x74\160\72\x2f\57" . $eV;
        Gf:
        $TW = parse_url($eV);
        $kH = preg_replace("\57\136\x77\x77\167\x5c\56\57", '', $TW["\150\x6f\x73\164"]);
        $d2 = wp_upload_dir();
        $yq = $kH . "\x2d" . $d2["\x62\141\x73\x65\x64\x69\162"];
        $K2 = hash_hmac("\x73\150\141\x32\x35\x36", $yq, "\x34\x44\x48\146\152\147\146\x6a\x61\x73\156\144\146\x73\x61\x6a\146\110\x47\112");
        $rh = $this->djkasjdksa();
        $gZ = round(strlen($rh) / rand(2, 20));
        $rh = substr_replace($rh, $K2, $gZ, 0);
        $Ba = base64_decode($rh);
        if (is_writable($py . "\154\151\x63\x65\156\x73\x65")) {
            goto M6;
        }
        $rh = str_rot13($rh);
        $sL = "\142\107\x4e\153\x61\155\x74\150\x63\62\160\x6b\141\63\116\150\x59\62\167\75";
        $uP = base64_decode($sL);
        update_option($uP, $rh);
        goto d3;
        M6:
        file_put_contents($py . "\x6c\x69\x63\145\156\163\x65", $Ba);
        d3:
        update_option("\x6c\x63\x77\162\x74\x6c\146\163\x61\155\154", true);
        $Oz = add_query_arg(array("\x74\141\142" => "\x67\145\x6e\x65\x72\141\154"), $_SERVER["\x52\105\x51\x55\x45\123\x54\x5f\125\x52\111"]);
        update_option("\155\157\137\x73\x61\x6d\154\x5f\155\x65\x73\x73\141\147\x65", "\x59\x6f\165\40\150\x61\166\x65\40\163\165\143\143\145\163\163\146\165\x6c\x6c\x79\40\163\x79\x6e\143\145\x64\40\x79\x6f\x75\x72\40\154\151\x63\145\x6e\163\x65\x2e");
        SAMLSPUtilities::mo_saml_show_success_message();
        Er:
        ii:
        goto wI;
        TI:
        if (!empty($_POST["\155\x6f\x5f\x73\x61\x6d\154\137\154\151\143\x65\x6e\x73\145\x5f\153\x65\171"])) {
            goto Jj;
        }
        update_option("\x6d\157\137\x73\x61\155\154\x5f\155\x65\163\163\141\x67\145", "\x41\x6c\x6c\x20\164\150\145\x20\x66\x69\145\x6c\144\163\x20\141\162\x65\40\162\145\x71\x75\151\x72\145\144\56\x20\x50\154\x65\141\163\145\x20\145\x6e\164\x65\x72\x20\166\141\154\x69\144\40\x6c\151\x63\x65\156\163\145\x20\x6b\x65\x79\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        Jj:
        $fO = trim($_POST["\x6d\157\x5f\x73\x61\155\154\x5f\x6c\x69\x63\145\156\x73\x65\137\153\145\x79"]);
        $Uw = new Customersaml();
        Mo_Saml_License_Handler::mo_saml_verify_license_key($fO, $Uw, "\x66\x61\154\x73\x65");
        wI:
        if (self::mo_check_option_admin_referer("\x6d\x6f\x5f\x73\x61\155\x6c\x5f\162\x65\x6d\x6f\166\145\x5f\x61\143\x63\x6f\165\x6e\x74")) {
            goto AQ;
        }
        if (self::mo_check_option_admin_referer("\x6d\x6f\x5f\163\x61\x6d\x6c\137\x76\x65\162\x69\x66\x79\137\x63\165\163\x74\x6f\x6d\145\162")) {
            goto r3;
        }
        if (self::mo_check_option_admin_referer("\x6d\x6f\137\x73\x61\155\x6c\137\143\x6f\x6e\x74\141\x63\x74\137\165\x73\x5f\161\x75\145\x72\x79\x5f\x6f\x70\x74\x69\157\156")) {
            goto oE;
        }
        if (self::mo_check_option_admin_referer("\155\157\137\163\x61\155\x6c\137\x64\x65\x66\141\x75\154\164\x5f\151\144\x70")) {
            goto T6P;
        }
        goto Di;
        AQ:
        $this->mo_sso_saml_deactivate();
        Mo_License_Service::reset_license_values();
        $Oz = add_query_arg(array("\164\x61\x62" => "\x6c\x6f\147\151\156"), $_SERVER["\122\x45\x51\125\x45\x53\124\x5f\x55\122\x49"]);
        header("\114\x6f\x63\x61\164\151\x6f\x6e\72\x20" . $Oz);
        goto Di;
        r3:
        if (mo_saml_is_extension_installed("\x63\165\162\x6c")) {
            goto Ja;
        }
        update_option("\x6d\x6f\x5f\x73\x61\x6d\154\x5f\155\145\163\163\x61\x67\145", "\x45\x52\122\x4f\x52\72\40\x3c\x61\40\x68\x72\x65\x66\x3d\x22\x68\164\x74\x70\72\x2f\x2f\160\150\160\x2e\x6e\x65\164\57\155\141\156\x75\141\154\x2f\x65\x6e\x2f\143\x75\162\154\x2e\x69\156\163\x74\141\154\154\x61\x74\x69\x6f\x6e\56\160\x68\160\42\40\x74\x61\162\x67\x65\x74\x3d\42\137\142\x6c\x61\156\x6b\x22\76\120\110\x50\x20\x63\125\x52\114\x20\x65\x78\x74\145\x6e\163\x69\x6f\x6e\74\x2f\x61\x3e\x20\x69\x73\x20\156\x6f\x74\40\151\156\x73\164\x61\x6c\154\x65\144\x20\157\162\x20\144\151\163\x61\142\x6c\x65\x64\56\40\114\x6f\x67\x69\156\x20\146\141\x69\154\x65\x64\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        Ja:
        $RY = '';
        $lq = '';
        if (empty($_POST["\x65\x6d\x61\x69\x6c"]) || empty($_POST["\160\x61\x73\163\x77\157\162\144"])) {
            goto Vs;
        }
        if ($this->checkPasswordPattern(strip_tags($_POST["\x70\141\x73\x73\167\x6f\162\144"]))) {
            goto WO;
        }
        $RY = sanitize_email($_POST["\145\x6d\141\x69\154"]);
        $lq = stripslashes(strip_tags($_POST["\x70\x61\x73\163\x77\157\x72\144"]));
        goto SD;
        Vs:
        update_option("\x6d\157\x5f\x73\141\x6d\154\137\155\145\163\x73\141\147\145", "\101\x6c\154\x20\164\x68\x65\x20\146\151\x65\x6c\144\x73\x20\x61\x72\145\x20\x72\145\161\165\151\162\x65\x64\56\x20\x50\x6c\x65\x61\x73\145\40\x65\156\164\x65\162\x20\x76\141\154\151\144\40\x65\156\164\162\151\x65\x73\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        goto SD;
        WO:
        update_option("\155\157\137\x73\x61\x6d\x6c\137\x6d\145\x73\163\141\x67\145", "\x4d\x69\x6e\x69\155\165\155\x20\66\x20\x63\150\141\x72\x61\143\164\145\x72\163\x20\163\x68\x6f\x75\x6c\144\x20\142\x65\40\x70\x72\145\x73\x65\156\164\56\40\115\x61\x78\x69\x6d\x75\155\40\x31\65\x20\143\x68\141\x72\141\143\x74\145\162\163\40\x73\150\157\165\154\x64\40\142\145\40\160\162\145\163\145\x6e\164\56\40\x4f\156\154\x79\x20\146\x6f\154\x6c\157\x77\151\x6e\147\40\163\x79\x6d\x62\x6f\154\x73\40\x28\41\x40\43\56\44\45\136\x26\52\x2d\137\51\40\163\x68\x6f\x75\154\144\40\x62\145\x20\160\x72\145\163\145\x6e\164\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        SD:
        update_option("\155\x6f\x5f\x73\141\x6d\154\137\x61\144\x6d\x69\156\x5f\145\x6d\x61\x69\154", $RY);
        $Uw = new Customersaml();
        $Qm = $Uw->get_customer_key($RY, $lq);
        if ($Qm) {
            goto he;
        }
        return;
        he:
        $oP = json_decode($Qm, true);
        if (json_last_error() == JSON_ERROR_NONE) {
            goto gy;
        }
        update_option("\x6d\157\x5f\x73\x61\155\x6c\137\155\145\x73\x73\x61\147\x65", "\x49\x6e\166\x61\154\x69\144\40\165\x73\x65\162\156\x61\x6d\x65\x20\x6f\x72\40\160\x61\163\163\167\x6f\x72\144\x2e\40\120\x6c\145\141\x73\145\x20\x74\162\x79\x20\x61\147\141\151\x6e\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto JM;
        gy:
        update_option("\155\157\x5f\x73\141\x6d\154\x5f\x61\x64\155\151\156\x5f\x63\165\x73\164\157\155\x65\162\x5f\153\x65\x79", $oP["\x69\144"]);
        update_option("\155\x6f\137\163\x61\x6d\x6c\x5f\141\144\x6d\x69\x6e\137\x61\x70\151\x5f\153\145\x79", $oP["\x61\160\x69\x4b\145\x79"]);
        update_option("\155\157\x5f\x73\x61\x6d\154\x5f\143\x75\x73\x74\157\155\x65\x72\137\164\157\153\x65\x6e", $oP["\164\x6f\x6b\145\x6e"]);
        if (empty($oP["\x70\150\157\x6e\145"])) {
            goto UE;
        }
        update_option("\155\157\x5f\163\141\155\154\137\x61\x64\x6d\151\156\x5f\x70\150\x6f\156\x65", $oP["\x70\x68\157\156\145"]);
        UE:
        update_option("\155\x6f\x5f\x73\x61\x6d\x6c\x5f\x6d\145\x73\163\x61\147\x65", "\x43\165\163\x74\x6f\155\145\162\40\162\145\x74\162\151\145\166\x65\x64\x20\x73\165\143\143\x65\163\x73\146\165\154\x6c\171");
        delete_option("\x6d\x6f\x5f\x73\x61\x6d\154\x5f\x76\x65\162\x69\146\171\137\x63\165\x73\x74\157\x6d\x65\162");
        if (get_option("\x73\155\x6c\137\x6c\153")) {
            goto fy;
        }
        SAMLSPUtilities::mo_saml_show_success_message();
        goto It;
        fy:
        $R2 = get_option("\155\157\137\163\141\155\x6c\137\143\x75\x73\164\157\x6d\145\x72\x5f\164\x6f\153\x65\156");
        $fO = AESEncryption::decrypt_data(get_option("\x73\155\154\x5f\x6c\x6b"), $R2);
        $Qm = json_decode($Uw->mo_saml_verify_license($fO, $this), true);
        if (strcasecmp($Qm["\x73\164\x61\x74\165\163"], "\x53\x55\x43\103\105\x53\123") == 0) {
            goto TB;
        }
        update_option("\155\157\137\163\x61\155\154\137\155\x65\163\x73\141\x67\x65", "\114\151\143\145\156\x73\x65\40\153\x65\x79\40\146\157\x72\40\164\150\151\163\x20\151\156\x73\164\x61\156\x63\x65\40\151\163\40\x69\156\x63\157\162\162\145\143\164\56\40\x4d\x61\x6b\x65\40\163\165\162\145\40\x79\157\x75\40\x68\x61\166\x65\40\x6e\157\x74\x20\x74\141\x6d\160\145\162\x65\x64\x20\167\x69\164\150\40\151\x74\x20\141\x74\40\x61\x6c\x6c\x2e\x20\120\154\x65\141\163\x65\x20\x65\156\x74\145\162\x20\x61\x20\166\141\x6c\x69\144\40\x6c\151\143\145\x6e\163\145\x20\x6b\145\x79\56");
        delete_option("\163\x6d\x6c\137\x6c\153");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto Ae;
        TB:
        SAMLSPUtilities::mo_saml_show_success_message();
        Ae:
        It:
        SAMLSPUtilities::mo_saml_enable_metadata_sync_for_all_idps();
        JM:
        goto Di;
        oE:
        if (mo_saml_is_extension_installed("\143\x75\162\154")) {
            goto NZ;
        }
        update_option("\155\157\137\x73\141\x6d\x6c\x5f\155\145\x73\x73\x61\x67\x65", "\x45\122\x52\x4f\x52\72\40\x3c\141\40\150\x72\145\x66\x3d\42\150\164\x74\160\x3a\x2f\x2f\160\x68\160\56\x6e\x65\x74\57\x6d\x61\x6e\x75\x61\154\x2f\x65\156\57\x63\165\162\x6c\56\151\156\x73\164\x61\x6c\x6c\x61\x74\x69\x6f\156\56\160\x68\160\x22\40\164\x61\x72\147\145\x74\75\42\x5f\x62\x6c\141\156\x6b\x22\76\120\110\x50\x20\143\125\x52\114\x20\x65\x78\164\x65\156\163\x69\157\x6e\x3c\x2f\x61\76\x20\151\x73\40\x6e\157\164\40\151\x6e\163\164\141\x6c\x6c\x65\x64\40\x6f\x72\x20\144\x69\x73\141\x62\x6c\145\144\x2e\x20\x51\165\x65\162\171\40\x73\165\142\155\151\x74\x20\146\x61\151\154\x65\144\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        NZ:
        $RY = $_POST["\155\x6f\x5f\163\141\x6d\154\x5f\143\157\156\164\x61\143\164\137\x75\163\x5f\x65\x6d\x61\151\x6c"];
        $TZ = $_POST["\155\157\137\163\141\155\x6c\x5f\143\x6f\156\x74\x61\143\x74\x5f\x75\x73\137\x70\150\x6f\x6e\145"];
        $yZ = trim($_POST["\x6d\157\137\x73\141\x6d\x6c\137\143\x6f\x6e\x74\141\143\164\x5f\165\x73\137\161\x75\145\x72\x79"]);
        if (!empty($_POST["\x73\145\x6e\144\x5f\160\x6c\165\147\151\156\137\x63\x6f\156\x66\x69\x67"])) {
            goto bT;
        }
        update_option("\163\145\x6e\x64\137\160\x6c\165\x67\x69\x6e\x5f\x63\157\156\x66\151\x67", "\157\x66\146");
        goto F_;
        bT:
        $ii = miniorange_import_export(true, true);
        $yZ .= $ii;
        update_option("\163\145\x6e\x64\x5f\160\x6c\165\x67\x69\x6e\137\143\157\x6e\x66\151\147", "\157\156");
        F_:
        $Uw = new CustomerSaml();
        if (empty($RY) || empty($yZ) || !filter_var($RY, FILTER_VALIDATE_EMAIL)) {
            goto lp;
        }
        $ka = $Uw->submit_contact_us($RY, $TZ, $yZ, $this);
        if ($ka == false) {
            goto W4;
        }
        update_option("\155\157\x5f\x73\x61\x6d\154\137\x6d\145\x73\x73\x61\x67\145", "\x54\150\x61\156\x6b\163\40\146\x6f\x72\40\x67\x65\x74\x74\151\156\x67\x20\x69\156\40\164\157\165\x63\150\41\x20\x57\x65\40\x73\150\141\x6c\154\40\147\x65\164\x20\x62\141\x63\x6b\40\x74\x6f\40\x79\x6f\165\x20\x73\150\x6f\162\x74\x6c\171\56");
        SAMLSPUtilities::mo_saml_show_success_message();
        goto K4;
        W4:
        update_option("\155\157\137\163\x61\155\154\x5f\x6d\x65\x73\x73\x61\x67\x65", "\x59\157\165\x72\x20\161\165\145\x72\171\40\143\x6f\165\x6c\x64\x20\156\x6f\x74\x20\142\145\40\163\165\142\155\151\x74\164\x65\x64\56\x20\120\x6c\145\x61\x73\x65\40\164\x72\x79\40\141\147\x61\151\156\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        K4:
        goto tL;
        lp:
        update_option("\155\157\x5f\163\141\x6d\154\x5f\155\145\x73\x73\141\x67\145", "\120\154\x65\x61\163\145\x20\x66\151\154\x6c\40\165\160\40\x45\x6d\141\x69\x6c\x20\x61\x6e\144\40\x51\165\x65\x72\x79\40\146\x69\145\154\144\163\x20\164\157\40\163\165\142\x6d\151\x74\x20\171\x6f\165\x72\40\x71\x75\x65\162\171\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        tL:
        goto Di;
        T6P:
        $XE = sanitize_text_field($_POST["\x69\144\160\x5f\x6e\x61\x6d\x65"]);
        $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\155\154\137\x69\x64\145\156\x74\x69\x74\171\x5f\x70\x72\x6f\166\151\144\x65\x72\163", true);
        if ($rK[$XE]["\145\x6e\141\x62\154\x65\137\151\144\160"]) {
            goto WY;
        }
        update_option("\155\x6f\x5f\x73\x61\155\x6c\x5f\155\145\x73\163\141\x67\x65", "\120\154\x65\141\163\x65\x20\105\156\141\142\x6c\145\x20\164\150\x65\40\x49\x44\x50\x20\x74\x6f\40\155\141\153\x65\40\x69\x74\x20\104\x65\x66\x61\165\x6c\x74\40\x49\104\x50\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto St;
        WY:
        $s6->mo_save_environment_settings("\163\141\155\x6c\137\144\145\x66\x61\165\154\x74\137\151\144\160", $XE);
        update_option("\155\x6f\137\x73\x61\155\154\x5f\155\145\x73\163\141\147\145", "\x44\145\146\141\x75\x6c\164\40\x49\104\120\40\165\x70\144\141\164\145\144\x20\163\165\143\143\145\163\163\165\x6c\x6c\x79\56");
        SAMLSPUtilities::mo_saml_show_success_message();
        St:
        Di:
        if (!self::mo_check_option_admin_referer("\154\157\147\x69\x6e\137\167\x69\144\x67\145\164\137\x73\x61\x6d\x6c\x5f\x6d\145\164\x61\144\x61\x74\x61\137\x73\171\x6e\143")) {
            goto VJ6;
        }
        $mc = !empty($_POST["\x73\x79\x6e\143\x5f\x6d\145\164\x61\x64\x61\x74\141"]) ? sanitize_text_field($_POST["\x73\x79\156\143\137\155\x65\x74\x61\x64\141\x74\x61"]) : '';
        $E6 = trim(sanitize_text_field($_POST["\x73\171\156\x63\137\x69\156\164\x65\x72\x76\141\154"]));
        $UK = esc_url_raw(filter_var($_POST["\155\145\164\141\x64\141\164\x61\137\x75\162\154"], FILTER_SANITIZE_URL));
        $yo = Mo_Options_Enum_Cron_Intervals::$cron_intervals;
        if (!empty($mc) && !empty($UK) && !empty($E6) && isset($yo[$E6])) {
            goto p0u;
        }
        SAMLSPUtilities::mo_saml_disable_metadata_sync();
        update_option("\x6d\157\x5f\x73\141\x6d\154\137\x6d\x65\x73\163\x61\x67\x65", "\x4d\145\164\x61\x64\x61\164\141\40\x73\x79\x6e\x63\x20\x63\x72\x6f\x6e\x20\151\163\40\144\x69\163\141\x62\x6c\x65\144\x20\x73\165\x63\143\145\x73\x73\x66\x75\x6c\154\x79\56");
        SAMLSPUtilities::mo_saml_show_success_message();
        goto W2c;
        p0u:
        $uM = Mo_Saml_Metadata_Import_Handler::mo_saml_get_object();
        $uM->mo_saml_handle_upload_metadata();
        W2c:
        VJ6:
        f9i:
    }
    function mo_saml_save_federation_parameters()
    {
        $gb = array();
        $YQ = array();
        $ze = array();
        $JM = array();
        $hK = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\x5f\x73\141\155\154\137\163\x70\137\x62\x61\163\x65\137\165\162\154");
        if (!empty($hK)) {
            goto ffq;
        }
        $hK = home_url();
        ffq:
        $Uy = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\x6f\137\x73\x61\155\x6c\x5f\x73\x70\x5f\145\156\164\151\164\x79\x5f\151\144");
        if (!empty($Uy)) {
            goto Ea1;
        }
        $Uy = $hK . "\x2f\167\x70\x2d\143\157\156\x74\x65\156\164\57\160\x6c\x75\147\151\x6e\x73\x2f\x6d\151\x6e\151\x6f\162\x61\x6e\147\145\x2d\x73\141\x6d\154\55\62\60\55\x73\x69\156\x67\154\x65\x2d\163\x69\147\156\x2d\x6f\156\x2f";
        Ea1:
        $t4 = $hK . "\x2f\77\157\x70\x74\x69\157\x6e\x3d\x73\x61\155\154\137\165\163\145\x72\137\x6c\157\147\x69\156";
        if (empty($_POST["\144\x65\x66\141\165\x6c\x74\137\x70\x61\162\141\155\x65\164\x65\x72\x73"])) {
            goto ALa;
        }
        $JM = array("\x65\156\x74\x69\x74\x79\111\x44" => $Uy, "\x72\145\164\165\x72\156" => $t4);
        ALa:
        if (empty($_POST["\155\x6f\x5f\163\x61\x6d\x6c\137\160\141\x72\141\155\x65\164\145\x72\137\156\141\155\x65\x73"])) {
            goto U42;
        }
        $YQ = $_POST["\x6d\x6f\x5f\163\141\x6d\154\137\160\141\x72\x61\155\x65\164\x65\162\137\x6e\x61\155\145\163"];
        U42:
        if (empty($_POST["\155\157\137\163\141\x6d\x6c\137\160\x61\x72\141\x6d\145\164\x65\x72\x5f\x76\141\154\165\x65\163"])) {
            goto Wn2;
        }
        $ze = $_POST["\x6d\x6f\137\x73\141\x6d\154\x5f\160\x61\x72\141\155\x65\164\x65\x72\x5f\166\x61\154\165\145\x73"];
        Wn2:
        $gb = array_combine($YQ, $ze);
        $gb = array_filter($gb);
        $gb = Utilitites::mo_saml_array_merge($gb, $JM);
        return $gb;
    }
    function djkasjdksa()
    {
        $D1 = "\x21\x7e\x40\43\x24\x25\x5e\46\52\50\x29\137\53\174\173\175\x3c\76\77\60\61\x32\x33\x34\65\x36\x37\70\71\141\142\x63\144\x65\146\x67\x68\x69\x6a\153\154\x6d\x6e\x6f\x70\161\162\163\x74\165\x76\x77\170\171\x7a\x41\102\103\104\x45\x46\107\x48\x49\112\x4b\x4c\x4d\116\x4f\x50\121\x52\123\x54\125\x56\127\130\x59\x5a";
        $mu = strlen($D1);
        $N3 = '';
        $Ev = 0;
        rlU:
        if (!($Ev < 10000)) {
            goto tPs;
        }
        $N3 .= $D1[rand(0, $mu - 1)];
        J29:
        $Ev++;
        goto rlU;
        tPs:
        return $N3;
    }
    function miniorange_sso_menu()
    {
        $W8 = add_menu_page("\x4d\x4f\40\123\101\115\x4c\40\123\145\x74\164\151\156\x67\x73\40" . __("\103\157\156\x66\151\x67\x75\x72\x65\x20\x53\x41\x4d\x4c\x20\x49\144\x65\x6e\164\x69\164\x79\x20\120\162\x6f\x76\x69\x64\x65\x72\40\x66\157\162\x20\123\x53\117", "\x6d\157\x5f\163\x61\155\x6c\137\163\x65\x74\164\x69\156\147\163"), "\155\151\156\x69\117\162\141\x6e\147\x65\40\x53\101\x4d\x4c\40\62\x2e\x30\40\x53\123\x4f", "\141\x64\x6d\151\156\151\163\x74\162\141\164\157\x72", "\x6d\157\x5f\x73\141\x6d\x6c\x5f\163\x65\x74\164\x69\156\147\163", array($this, "\155\157\137\154\x6f\147\x69\156\x5f\x77\151\144\147\145\x74\137\x73\141\x6d\154\137\x6f\x70\x74\151\x6f\x6e\163"), plugin_dir_url(__FILE__) . "\151\155\141\147\145\x73\x2f\x6d\x69\156\151\157\162\x61\x6e\x67\145\56\167\x65\142\160");
        if (!(isset($_GET["\164\x61\x62"]) && "\x73\x61\x76\145" === $_GET["\x74\141\x62"])) {
            goto BPv;
        }
        add_action("\x6c\x6f\x61\144\x2d{$W8}", array("\x53\101\115\114\123\120\x55\164\x69\154\151\x74\151\145\x73", "\155\157\137\x73\141\x6d\154\137\x73\x63\162\145\145\x6e\x5f\157\160\164\x69\157\156\163"));
        BPv:
        if (!is_plugin_active("\x6d\151\156\x69\x6f\162\x61\156\147\145\x2d\x66\145\x64\145\x72\141\x74\x69\157\156\x2d\163\163\157\x2f\146\x65\144\145\x72\x61\164\x69\x6f\156\x2d\x73\163\x6f\x2e\x70\150\x70")) {
            goto eXq;
        }
        add_submenu_page("\155\157\137\x73\x61\155\154\137\163\145\x74\x74\151\156\x67\163", "\x46\145\144\145\162\x61\164\151\157\x6e\40\x53\123\x4f", "\106\145\144\x65\x72\x61\164\x69\157\156\40\123\123\117", "\141\144\155\151\x6e\151\163\164\162\141\x74\157\x72", "\x6d\x6f\137\x73\141\155\154\137\x66\145\144\145\x72\141\164\151\x6f\x6e\137\x73\x73\157", "\x6d\x6f\x5f\x73\141\x6d\154\137\146\x65\x64\145\162\x61\164\151\x6f\156\137\x73\x73\157");
        eXq:
        add_submenu_page("\x6d\x6f\137\163\x61\155\x6c\x5f\x73\x65\164\164\x69\156\147\163", "\x4d\x61\x6e\x61\x67\145\x20\x4d\165\154\164\151\x70\x6c\145\40\105\x6e\x76\x69\162\157\x6e\155\145\x6e\x74\163", "\115\x61\x6e\x61\x67\x65\x20\115\165\x6c\164\x69\x70\154\145\x20\105\x6e\166\x69\x72\x6f\x6e\x6d\x65\x6e\164\163", "\x61\144\x6d\x69\156\x69\163\164\162\141\164\x6f\x72", "\x6d\x6f\x5f\x6d\x75\154\x74\x69\160\154\145\137\x65\x6e\x76\x69\x72\x6f\156\x6d\x65\156\x74", "\x6d\157\x5f\155\165\154\x74\x69\160\154\145\x5f\145\156\x76\x69\x72\x6f\156\155\x65\156\164");
        add_submenu_page("\155\x6f\x5f\163\141\155\x6c\x5f\x73\x65\164\x74\151\x6e\x67\x73", "\105\162\162\x6f\x72\40\103\157\144\145\163", "\x45\x72\x72\157\x72\x20\x43\157\x64\x65\x73", "\141\x64\155\x69\156\x69\x73\x74\162\141\164\x6f\x72", "\x6d\x6f\x5f\145\x72\162\x6f\162\x5f\x63\x6f\144\x65\163", array("\x4d\x6f\137\x53\141\x6d\154\x5f\x45\162\x72\157\x72\137\x43\x6f\144\145\x73\137\126\x69\x65\167", "\155\157\x5f\x73\141\x6d\154\x5f\x67\145\x74\x5f\145\162\x72\157\162\x5f\x63\157\x64\145\163\x5f\166\151\x65\167"));
    }
    function mo_saml_redirect_for_authentication($XE, $Rn)
    {
        SAMLSPUtilities::mo_saml_check_is_extension_installed();
        if (Mo_License_Service::is_customer_license_verified()) {
            goto SAa;
        }
        return;
        SAa:
        if (!(!empty($XE) && !SAMLSPUtilities::mo_saml_is_user_logged_in())) {
            goto QKM;
        }
        $V7 = esc_url(home_url()) . "\57\77\x6f\x70\164\151\157\156\75\163\141\x6d\x6c\137\165\x73\x65\x72\x5f\x6c\157\147\151\x6e\x26\151\x64\x70\75" . esc_html($XE["\x69\x64\160\x5f\x6e\141\x6d\145"]) . "\x26\x72\145\x64\x69\162\x65\143\x74\x5f\x74\x6f\x3d" . urlencode($Rn);
        if (is_feed()) {
            goto ODb;
        }
        echo "\x3c\x73\x63\x72\x69\x70\164\76\167\151\x6e\x64\157\x77\56\x6c\157\143\141\x74\151\x6f\156\x2e\150\162\x65\x66\x3d\x27" . $V7 . "\47\x3b\74\57\x73\x63\162\151\x70\164\x3e";
        exit;
        goto k5W;
        ODb:
        wp_redirect($V7);
        k5W:
        QKM:
    }
    function mo_saml_authenticate($user, $Io, $lq)
    {
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $yT = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\x6f\x5f\163\x61\155\x6c\137\x62\x61\143\153\144\x6f\157\x72\137\165\x72\x6c", false, $CP) ? EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\163\x61\155\x6c\137\142\141\143\153\144\x6f\157\x72\x5f\165\162\x6c", false, $CP) : "\x66\x61\154\x73\x65";
        if (Mo_License_Service::is_customer_license_verified() && EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\137\x73\141\x6d\x6c\137\x65\156\x61\142\154\145\x5f\x64\x6f\x6d\x61\x69\x6e\137\155\x61\x70\x70\151\156\147", false, $CP) == "\164\x72\165\145" && (empty($_REQUEST["\163\x61\x6d\154\137\x73\163\157"]) || !empty($_REQUEST["\163\141\155\x6c\x5f\x73\x73\x6f"]) && $_REQUEST["\163\141\155\154\137\x73\x73\x6f"] != $yT)) {
            goto k1z;
        }
        return $user;
        goto wBE;
        k1z:
        if (empty($Io)) {
            goto v82;
        }
        $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\x61\x6d\x6c\x5f\x69\x64\145\x6e\164\x69\x74\x79\137\x70\x72\x6f\x76\151\144\145\x72\x73", true, $CP);
        $gC = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\155\154\x5f\x69\144\x70\137\144\157\155\x61\151\x6e\137\155\141\160\160\x69\156\147", true, $CP);
        $V9 = '';
        if (empty($_REQUEST["\x72\145\x64\151\162\x65\143\164\137\164\157"])) {
            goto l0R;
        }
        $V9 = SAMLSPUtilities::mo_saml_is_array($_REQUEST["\162\x65\x64\151\x72\x65\143\164\137\x74\157"]);
        l0R:
        if (filter_var($Io, FILTER_VALIDATE_EMAIL)) {
            goto mVK;
        }
        if (empty($rK) || EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\x6f\x5f\163\x61\x6d\154\x5f\x61\x6c\154\x6f\x77\137\x77\x70\137\163\x69\147\156\151\156", false, $CP) != "\146\x61\x6c\163\145" && !empty($_REQUEST["\x73\x61\155\x6c\137\163\x73\x6f"]) && $_REQUEST["\163\x61\155\154\x5f\x73\x73\157"] == $yT) {
            goto RMW;
        }
        if (!empty($lq)) {
            goto FDm;
        }
        $e1 = new WP_Error("\155\157\137\163\x61\x6d\154\137\x69\156\x76\141\x6c\151\144\137\145\x6d\x61\151\x6c", __("\74\163\x74\x72\x6f\x6e\x67\76\x45\x52\122\117\x52\x3c\57\163\x74\x72\x6f\156\147\x3e\x3a\40\120\154\145\x61\x73\x65\x20\145\156\x74\x65\162\40\x61\40\166\141\154\151\x64\x20\x65\x6d\141\x69\x6c\x20\x61\144\144\x72\145\163\x73\56"), '');
        return $e1;
        goto exk;
        mVK:
        $rr = explode("\100", $Io, 2);
        $kH = trim($rr[1]);
        $kH = strtolower($kH);
        $oe = false;
        if (empty($gC)) {
            goto iSX;
        }
        foreach ($gC as $R2 => $EB) {
            $EB = str_replace("\x20", '', $EB);
            $GH = array_map("\164\162\x69\155", explode("\73", $EB));
            $GH = array_map("\163\x74\162\x74\157\154\x6f\167\145\162", $GH);
            if (!(SAMLSPUtilities::mo_saml_in_array($kH, $GH) && !empty($rK[$R2]["\145\156\141\x62\x6c\x65\x5f\151\x64\x70"]))) {
                goto IO1;
            }
            $Sf = $rK[$R2];
            $oe = true;
            if (empty($rK[$R2]["\x65\156\141\142\x6c\145\x5f\x69\144\x70"])) {
                goto F81;
            }
            $F6 = Mo_Saml_Redirection_Sso_Handler::mo_saml_get_object();
            $F6->mo_saml_redirect_sso_for_authentication($Sf, $V9);
            goto viw;
            F81:
            $Al = wp_authenticate_username_password($user, $Io, $lq);
            return $Al;
            viw:
            IO1:
            ccv:
        }
        HLd:
        iSX:
        if ($oe) {
            goto OH7;
        }
        if (!EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\x5f\163\x61\x6d\154\x5f\146\141\154\x6c\x62\x61\x63\x6b\137\164\157\137\144\145\146\141\165\154\x74", false, $CP)) {
            goto vdE;
        }
        $XR = EnvironmentHelper::getOptionForSelectedEnvironment("\163\x61\155\x6c\137\144\145\x66\x61\165\x6c\164\137\151\144\160", false, $CP);
        if ($XR) {
            goto x2M;
        }
        $s6 = new EnvironmentDao($CP);
        $s6->mo_save_environment_settings("\x6d\x6f\137\x73\x61\155\154\137\x66\141\154\x6c\142\x61\x63\153\137\164\157\137\144\145\146\x61\165\x6c\x74", '');
        $s6->mo_save_environment_settings("\x6d\157\137\x73\141\x6d\x6c\137\144\157\x6d\141\151\156\x5f\x6c\157\x67\151\x6e\137\146\141\151\154", "\164\162\165\145");
        $hK = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\x5f\x73\141\155\154\137\x73\160\x5f\142\141\x73\x65\x5f\x75\x72\154", false, $CP);
        if (!empty($hK)) {
            goto N2H;
        }
        $hK = home_url();
        N2H:
        Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\x57\x50\x53\x41\x4d\x4c\105\122\x52\60\x33\64"]);
        goto KeV;
        x2M:
        $F6 = Mo_Saml_Redirection_Sso_Handler::mo_saml_get_object();
        $F6->mo_saml_redirect_sso_for_authentication($rK[$XR], $V9);
        KeV:
        vdE:
        return $user;
        OH7:
        goto exk;
        RMW:
        return $user;
        goto exk;
        FDm:
        return $user;
        exk:
        v82:
        $e1 = new WP_Error();
        $e1->add("\x65\x6d\160\x74\171\x5f\x75\x73\x65\x72\x6e\x61\x6d\x65", __("\x3c\x73\164\x72\157\x6e\x67\x3e\x45\122\122\117\122\74\57\x73\x74\x72\157\x6e\147\76\x3a\40\105\x6d\160\x74\x79\x20\x75\163\145\x72\156\x61\155\x65\x2e"));
        $e1->add("\x65\x6d\x70\x74\x79\137\160\x61\163\x73\167\x6f\162\x64", __("\x3c\163\x74\x72\x6f\156\x67\x3e\105\x52\x52\x4f\122\x3c\x2f\x73\x74\162\x6f\156\x67\76\x3a\40\105\155\x70\x74\171\x20\x70\141\163\x73\167\x6f\x72\144\x2e"));
        return $e1;
        wBE:
    }
    function mo_saml_redirect_to_idp_list_page_from_login_page()
    {
        if (Mo_License_Service::is_customer_license_verified()) {
            goto L4V;
        }
        return;
        L4V:
        $V9 = '';
        if (empty($_REQUEST["\162\x65\x64\x69\162\145\143\164\x5f\x74\x6f"])) {
            goto RAS;
        }
        $V9 = SAMLSPUtilities::mo_saml_is_array($_REQUEST["\x72\145\x64\x69\162\x65\x63\164\x5f\164\x6f"]);
        RAS:
        if (!SAMLSPUtilities::mo_saml_is_user_logged_in()) {
            goto jul;
        }
        if (!empty($V9)) {
            goto bZO;
        }
        header("\x4c\x6f\x63\x61\164\151\157\156\72\40" . home_url());
        goto vcl;
        bZO:
        header("\114\157\x63\x61\164\x69\x6f\x6e\x3a\40" . $V9);
        vcl:
        exit;
        jul:
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $zH = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\155\x6c\137\144\145\x66\x61\165\x6c\164\137\x69\144\x70", false, $CP);
        if (!(EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\x5f\163\141\155\154\137\145\156\x61\x62\154\x65\x5f\154\x6f\147\x69\156\137\x72\x65\144\151\162\x65\143\164", false, $CP) == "\x74\x72\165\145")) {
            goto Ypo;
        }
        if (!empty($zH)) {
            goto el0;
        }
        Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\127\x50\123\101\115\x4c\105\x52\x52\60\x33\64"]);
        el0:
        $Dh = apply_filters("\x6d\157\137\x73\141\155\x6c\x5f\x70\162\145\137\141\x75\x74\x6f\137\162\145\144\x69\162\x65\x63\x74\151\x6f\x6e", false);
        if (!$Dh) {
            goto m3R;
        }
        return;
        m3R:
        $yT = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\163\x61\x6d\154\137\x62\x61\143\153\144\x6f\x6f\x72\137\x75\162\154", false, $CP) ? trim(EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\x73\x61\x6d\x6c\137\142\141\x63\x6b\x64\x6f\157\162\x5f\x75\162\x6c", false, $CP)) : "\146\x61\154\163\x65";
        if (!(EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\x5f\163\141\155\154\x5f\141\x6c\x6c\157\x77\x5f\x77\160\137\163\151\x67\156\151\156", false, $CP) == "\x74\162\x75\x65")) {
            goto Efk;
        }
        if (!empty($_REQUEST["\x73\141\x6d\154\137\x73\x73\x6f"]) && $_REQUEST["\163\141\x6d\x6c\x5f\x73\x73\x6f"] === $yT) {
            goto cEH;
        }
        if (!empty($_REQUEST["\162\145\x64\151\x72\145\143\x74\137\x74\157"])) {
            goto ccy;
        }
        goto i6O;
        cEH:
        return;
        goto i6O;
        ccy:
        $V9 = htmlspecialchars(SAMLSPUtilities::mo_saml_is_array($_REQUEST["\x72\x65\x64\x69\162\145\143\164\x5f\164\157"]));
        if (!(strpos($V9, "\167\x70\x2d\141\x64\x6d\151\156") !== false && strpos($V9, "\x73\x61\155\x6c\x5f\163\x73\157\75" . $yT) !== false)) {
            goto QA0;
        }
        return;
        QA0:
        i6O:
        Efk:
        $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\x61\155\154\137\151\144\x65\x6e\x74\x69\x74\x79\x5f\160\x72\x6f\166\151\144\145\162\x73", true, $CP);
        $fk = '';
        foreach ($rK as $Wq) {
            if (!($Wq["\151\144\x70\137\x6e\x61\x6d\x65"] === $zH)) {
                goto z7w;
            }
            $fk = $Wq;
            z7w:
            Gaz:
        }
        QgR:
        $F6 = Mo_Saml_Redirection_Sso_Handler::mo_saml_get_object();
        $F6->mo_saml_redirect_sso_for_authentication($fk, $V9);
        Ypo:
        if (!(EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\x5f\163\x61\x6d\154\x5f\x61\x75\164\157\137\x72\x65\144\x69\x72\x65\143\164\137\164\x6f\x5f\x70\165\x62\154\x69\143\x5f\160\x61\147\x65", false, $CP) == "\164\162\x75\x65")) {
            goto qj_;
        }
        $XH = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\x73\141\155\154\137\x65\156\141\142\154\x65\x5f\x61\x75\x74\x6f\x5f\x72\145\x64\x69\x72\x65\143\164", false, $CP);
        $xQ = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\x6f\x5f\163\x61\x6d\x6c\137\x64\x6f\155\141\x69\156\137\x6c\x6f\147\x69\156\x5f\x66\x61\151\154");
        $w3 = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\x5f\163\x61\155\154\137\146\x61\x6c\x6c\142\x61\x63\x6b\137\164\x6f\x5f\x64\x65\146\141\x75\x6c\x74");
        $b_ = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\x5f\163\x61\155\154\137\x61\x75\164\x6f\x5f\162\145\144\x69\x72\145\143\x74\x5f\x74\x6f\137\160\x75\x62\x6c\x69\x63\x5f\x70\x61\x67\x65");
        if ($XH) {
            goto pph;
        }
        if (!EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\x5f\163\x61\155\x6c\x5f\x6e\x6f\137\141\x75\164\157\137\x72\x65\x64\x69\162\x65\x63\x74") && ($w3 || $xQ || $b_)) {
            goto KBM;
        }
        $XH = "\165\x6e\x63\150\145\x63\x6b\145\x64";
        goto fVr;
        KBM:
        $XH = "\143\x68\145\x63\x6b\145\x64";
        fVr:
        pph:
        if (!("\165\x6e\x63\x68\x65\143\153\x65\144" === $XH)) {
            goto Eak;
        }
        return;
        Eak:
        $yT = EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\x5f\163\141\x6d\x6c\137\x62\x61\x63\x6b\144\157\x6f\162\x5f\x75\x72\x6c", false, $CP) ? EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\137\x73\141\155\154\x5f\142\141\x63\x6b\144\157\157\x72\x5f\x75\x72\x6c", false, $CP) : "\146\x61\x6c\163\145";
        if (!empty($_GET["\154\157\x67\147\145\x64\x6f\165\x74"]) && $_GET["\154\157\x67\x67\x65\x64\157\165\164"] == "\164\x72\165\145") {
            goto TQx;
        }
        if (EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\x5f\x73\141\155\154\137\x61\x6c\154\x6f\x77\137\167\x70\137\x73\x69\147\x6e\x69\156", false, $CP) != "\146\x61\x6c\163\145") {
            goto ppG;
        }
        goto FK0;
        TQx:
        header("\114\157\143\x61\164\x69\x6f\156\x3a\40" . home_url());
        exit;
        goto FK0;
        ppG:
        if (!empty($_REQUEST["\x73\x61\x6d\x6c\x5f\x73\163\157"]) && $_REQUEST["\163\x61\155\x6c\137\x73\163\157"] === $yT) {
            goto aE9;
        }
        if (!empty($_REQUEST["\162\145\x64\x69\x72\145\143\x74\137\x74\x6f"])) {
            goto KYt;
        }
        goto BVP;
        aE9:
        return;
        goto BVP;
        KYt:
        $V9 = SAMLSPUtilities::mo_saml_is_array($_REQUEST["\162\145\144\151\x72\145\x63\164\137\164\157"]);
        if (!(strpos($V9, "\167\160\55\141\144\155\151\156") !== false && strpos($V9, "\40\x20\x73\x61\x6d\154\137\163\x73\x6f\75" . $yT) !== false)) {
            goto hMS;
        }
        return;
        hMS:
        BVP:
        FK0:
        $Bt = SAMLSPUtilities::mo_saml_get_public_page_url($CP);
        if (!(strpos($Bt, wp_login_url()) !== false)) {
            goto K4I;
        }
        return;
        K4I:
        if (!(strpos($Bt, admin_url()) !== false)) {
            goto pOb;
        }
        return;
        pOb:
        $Mw = (!empty($_SERVER["\x48\124\124\120\123"]) ? "\x68\x74\164\x70\x73" : "\150\x74\164\160") . "\72\57\57{$_SERVER["\110\x54\x54\x50\x5f\x48\x4f\x53\x54"]}{$_SERVER["\x52\105\x51\x55\105\123\x54\137\125\x52\111"]}";
        if (!strcmp($Mw, $Bt)) {
            goto yGs;
        }
        header("\114\x6f\x63\141\164\x69\157\x6e\x3a\40" . $Bt);
        exit;
        goto Jvk;
        yGs:
        return;
        Jvk:
        qj_:
    }
    function mo_saml_auto_redirect()
    {
        if (!(!Mo_License_Service::is_customer_license_verified() || SAMLSPUtilities::mo_saml_is_user_logged_in())) {
            goto b5B;
        }
        return;
        b5B:
        $CP = EnvironmentHelper::getCurrentEnvironment();
        if (!(EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\x5f\x73\141\x6d\154\137\x65\x6e\141\x62\x6c\x65\x5f\x72\163\163\x5f\141\x63\143\145\163\163", false, $CP) == "\164\x72\165\145" && is_feed())) {
            goto LoX;
        }
        return;
        LoX:
        $XH = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\x73\141\x6d\154\137\x65\156\141\x62\154\145\x5f\141\165\164\x6f\137\162\x65\x64\x69\162\x65\143\x74", false, $CP);
        $w3 = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\x5f\163\141\x6d\x6c\137\x72\x65\x64\x69\162\x65\143\x74\x5f\144\145\x66\141\x75\x6c\164\137\151\144\160", false, $CP);
        $xQ = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\x5f\x73\141\155\154\x5f\x72\145\147\151\163\164\145\162\145\x64\x5f\x6f\156\x6c\171\x5f\141\x63\x63\145\163\x73", false, $CP);
        $b_ = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\x5f\163\141\155\154\137\141\165\x74\157\137\162\145\x64\151\162\145\x63\164\137\x74\x6f\137\160\165\x62\154\151\x63\x5f\160\x61\147\145", false, $CP);
        $Rn = saml_get_current_page_url();
        if ($XH) {
            goto SY9;
        }
        if (!(!EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\x73\141\155\154\137\156\x6f\x5f\x61\165\x74\x6f\137\x72\x65\144\x69\162\x65\x63\x74") && ($w3 || $xQ || $b_))) {
            goto iNH;
        }
        $XH = "\143\x68\145\x63\x6b\x65\x64";
        iNH:
        SY9:
        if (!("\143\x68\145\x63\x6b\145\144" !== $XH)) {
            goto Hff;
        }
        return;
        Hff:
        if (!($b_ == "\x74\162\165\x65")) {
            goto DMB;
        }
        $Dh = apply_filters("\155\x6f\137\x73\141\155\x6c\x5f\160\162\145\137\141\165\x74\x6f\137\162\x65\x64\x69\162\145\143\x74\x69\x6f\x6e", false);
        if (!$Dh) {
            goto ACm;
        }
        return;
        ACm:
        $yT = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\x6f\137\x73\x61\x6d\x6c\137\x62\141\143\x6b\x64\157\157\162\137\x75\162\154", false, $CP) ? EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\x5f\x73\x61\155\154\x5f\x62\141\143\x6b\144\157\157\162\x5f\165\162\154", false, $CP) : "\146\141\154\163\145";
        if (!empty($_GET["\154\157\x67\x67\145\x64\x6f\x75\x74"]) && $_GET["\x6c\x6f\x67\x67\145\x64\157\165\164"] == "\x74\x72\x75\145") {
            goto q5a;
        }
        if (EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\x5f\x73\141\x6d\x6c\137\141\154\154\157\x77\137\167\x70\137\x73\x69\x67\156\x69\x6e", false, $CP) != "\146\141\154\x73\x65") {
            goto Lmz;
        }
        goto isM;
        q5a:
        header("\114\157\x63\x61\164\151\157\156\72\x20" . home_url());
        exit;
        goto isM;
        Lmz:
        if (!empty($_REQUEST["\x73\141\x6d\154\137\x73\x73\x6f"]) && $_REQUEST["\163\141\155\154\x5f\x73\163\x6f"] === $yT) {
            goto gsg;
        }
        if (!empty($_REQUEST["\x72\x65\x64\151\162\x65\x63\x74\x5f\164\x6f"])) {
            goto FRl;
        }
        goto xbT;
        gsg:
        return;
        goto xbT;
        FRl:
        $V9 = SAMLSPUtilities::mo_saml_is_array($_REQUEST["\x72\145\144\151\x72\145\x63\164\x5f\x74\157"]);
        if (!(strpos($V9, "\x77\160\55\x61\x64\155\x69\156") !== false && strpos($V9, "\x20\x20\x73\x61\155\x6c\x5f\x73\163\x6f\x3d" . $yT) !== false)) {
            goto DVp;
        }
        return;
        DVp:
        xbT:
        isM:
        $Bt = SAMLSPUtilities::mo_saml_get_public_page_url($CP);
        $Mw = (!empty($_SERVER["\x48\x54\x54\120\123"]) ? "\150\164\164\x70\x73" : "\x68\164\164\160") . "\72\x2f\x2f{$_SERVER["\x48\x54\124\120\x5f\x48\x4f\x53\124"]}{$_SERVER["\x52\x45\121\125\105\x53\124\x5f\125\122\x49"]}";
        $Xd = parse_url($Bt);
        $R0 = parse_url($Mw);
        $ah = $Xd["\163\x63\x68\145\155\145"] . "\72\x2f\57" . $Xd["\150\157\163\x74"] . $Xd["\160\141\x74\x68"];
        $qd = $R0["\x73\x63\x68\x65\155\x65"] . "\72\x2f\57" . $R0["\150\x6f\163\164"] . $R0["\160\141\164\x68"];
        if (!strcmp($qd, $ah)) {
            goto nFT;
        }
        if (empty($Rn)) {
            goto wvo;
        }
        $Bt = $Bt . "\x3f\x72\x65\144\151\x72\145\x63\x74\x5f\x74\x6f\75" . urlencode($Rn);
        wvo:
        wp_safe_redirect($Bt);
        exit;
        goto Neq;
        nFT:
        return;
        Neq:
        DMB:
        if (!($w3 == "\x74\162\x75\145")) {
            goto LW6;
        }
        $Dh = apply_filters("\155\157\137\163\141\155\154\x5f\x70\162\x65\137\x61\165\164\x6f\137\x72\x65\x64\151\162\x65\143\x74\151\x6f\x6e", false);
        if (!$Dh) {
            goto e4G;
        }
        return;
        e4G:
        $this->mo_saml_redirect_to_default_idp($Rn);
        LW6:
        if (!($xQ === "\x74\162\x75\x65")) {
            goto Yby;
        }
        $Dh = apply_filters("\x6d\157\137\163\141\155\x6c\x5f\x70\x72\x65\137\x61\165\x74\x6f\137\162\x65\x64\151\x72\x65\x63\x74\x69\x6f\x6e", false);
        if (!$Dh) {
            goto jnV;
        }
        return;
        jnV:
        if (EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\x5f\163\141\155\154\x5f\x65\x6e\141\x62\154\145\x5f\154\157\147\151\156\137\x72\x65\x64\x69\162\x65\x63\164", false, $CP) === "\x74\x72\165\145") {
            goto unj;
        }
        $Oz = site_url() . "\x2f\x77\160\x2d\x6c\157\x67\x69\156\x2e\x70\150\x70";
        if (empty($Rn)) {
            goto xtA;
        }
        $Oz = $Oz . "\x3f\x72\145\x64\x69\x72\x65\143\164\137\x74\x6f\x3d" . urlencode($Rn) . "\46\x72\x65\x61\165\164\x68\75\61";
        xtA:
        wp_safe_redirect($Oz);
        exit;
        goto QL1;
        unj:
        $this->mo_saml_redirect_to_default_idp($Rn);
        QL1:
        Yby:
    }
    function mo_saml_redirect_to_default_idp($Rn)
    {
        $fk = '';
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $zH = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\155\154\137\144\x65\146\x61\165\x6c\x74\x5f\x69\x64\160", false, $CP);
        if (!empty($zH)) {
            goto F21;
        }
        Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\x57\120\123\101\x4d\x4c\x45\x52\122\x30\x33\64"]);
        F21:
        $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\x6d\154\137\x69\144\x65\x6e\x74\x69\x74\x79\x5f\160\x72\x6f\166\x69\144\145\x72\x73", true, $CP);
        if (!(!empty($rK) && is_array($rK))) {
            goto qJD;
        }
        foreach ($rK as $Wq) {
            if (!($Wq["\151\144\160\137\156\x61\x6d\145"] === $zH)) {
                goto wOW;
            }
            $fk = $Wq;
            goto Kgx;
            wOW:
            QMI:
        }
        Kgx:
        qJD:
        $F6 = Mo_Saml_Redirection_Sso_Handler::mo_saml_get_object();
        $F6->mo_saml_redirect_sso_for_authentication($fk, $Rn);
    }
    function mo_saml_modify_login_form()
    {
        if (Mo_License_Service::is_customer_license_verified()) {
            goto KiB;
        }
        return;
        KiB:
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $yT = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\x6f\x5f\x73\x61\155\154\137\142\141\x63\153\x64\157\x6f\x72\137\x75\x72\154", false, $CP) ? EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\x5f\163\x61\155\x6c\137\142\x61\x63\x6b\x64\x6f\157\x72\x5f\x75\x72\154", false, $CP) : "\146\x61\x6c\x73\x65";
        $MA = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\x5f\163\x61\x6d\154\x5f\145\156\x61\142\x6c\x65\x5f\150\x69\x64\x65\x5f\x77\160\x5f\x6c\x6f\147\151\x6e", false, $CP);
        if (!Mo_Saml_Hide_WP_Login_Handler::mo_saml_check_hide_login()) {
            goto vuM;
        }
        wp_enqueue_script("\155\x6f\x5f\x73\141\x6d\x6c\x5f\x68\151\x64\145\x5f\x77\x70\x5f\x6c\x6f\x67\x69\x6e\137\163\x63\162\151\160\x74", plugins_url("\151\x6e\x63\154\x75\144\145\x73\57\x6a\x73\57\x68\151\x64\145\55\167\x70\x2d\x6c\x6f\x67\151\156\56\x6a\163", __FILE__), array(), mo_options_plugin_constants::VERSION);
        vuM:
        if (!(EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\x73\x61\x6d\x6c\137\141\154\x6c\x6f\x77\137\167\x70\x5f\x73\x69\x67\x6e\x69\156", false, $CP) != "\x66\x61\154\x73\145" && (!empty($_REQUEST["\163\141\155\154\137\163\x73\157"]) && $_REQUEST["\x73\x61\x6d\154\137\163\163\157"] == $yT))) {
            goto gwW;
        }
        if (!("\x74\x72\165\x65" != $MA)) {
            goto arJ;
        }
        echo "\x9\x9\11\x3c\163\143\162\x69\160\x74\76\15\xa\11\11\x9\11\152\121\165\x65\x72\x79\x28\x22\x23\x75\163\x65\162\137\160\x61\x73\163\42\51\56\162\145\155\157\x76\145\x41\x74\164\x72\50\x22\x64\x69\x73\x61\x62\154\145\x64\x22\51\73\xd\12\x9\11\x9\x3c\x2f\x73\143\162\151\160\164\76\15\xa\x9\x9\x9";
        arJ:
        echo "\x3c\x69\x6e\x70\165\164\x20\x74\171\x70\x65\75\x22\150\151\x64\144\145\156\42\40\156\x61\155\x65\x3d\42\163\141\x6d\154\x5f\x73\163\x6f\42\x20\x76\x61\154\165\145\75\x22" . esc_attr($yT) . "\42\76";
        return;
        gwW:
    }
    function mo_saml_add_login_links()
    {
        if (Mo_License_Service::is_customer_license_verified()) {
            goto bR2;
        }
        return;
        bR2:
        $this->add_federation_link();
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\x61\155\x6c\x5f\x69\x64\145\x6e\164\x69\x74\171\x5f\160\162\x6f\x76\151\x64\x65\162\163", true, $CP);
        $id = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\155\x6c\x5f\x73\x73\x6f\x5f\x62\165\164\164\x6f\x6e\137\x69\144\160", true, $CP);
        if (Mo_Saml_Hide_WP_Login_Handler::mo_saml_check_hide_login()) {
            goto Jky;
        }
        echo "\x3c\163\143\162\x69\160\x74\76\xd\12\11\11\11\11\152\121\x75\145\162\x79\50\x64\157\143\165\x6d\x65\156\164\x29\x2e\x72\x65\141\x64\171\x28\146\165\156\143\164\151\157\x6e\40\x28\x29\x20\173\xd\xa\11\11\x9\11\11\x6a\121\165\145\162\x79\50\x22\43\x6c\157\147\x69\x6e\x66\x6f\162\155\42\x29\x2e\141\160\160\x65\156\144\x28\140\x3c\144\151\x76\x20\x63\x6c\141\x73\x73\x3d\42\163\x73\157\55\142\x75\x74\164\157\156\x73\x2d\x62\145\154\157\x77\x22\40\163\x74\171\154\145\x3d\42\x70\x61\x64\x64\151\156\147\55\x74\x6f\160\x3a\40\x33\x35\160\x78\73\42\x3e\74\x2f\144\151\166\x3e\x60\x29\x3b\xd\12\x9\11\11\x9\x9\166\141\162\40\44\x65\x6c\145\x6d\x65\156\x74\x20\x3d\x20\152\121\165\145\162\x79\x28\x22\x23\x75\x73\x65\x72\x5f\154\157\x67\151\156\42\x29\x3b\xd\12\11\11\x9\x9\11\152\x51\165\145\162\x79\x28\x60\x3c\x64\151\x76\x20\x63\x6c\x61\163\163\75\42\163\x73\157\55\142\165\x74\164\x6f\156\x73\55\x61\142\157\x76\145\x22\x20\x73\x74\x79\x6c\145\75\x22\160\x61\x64\x64\x69\156\x67\x2d\x74\157\160\72\x20\61\x35\160\x78\73\42\76\x3c\57\144\x69\166\x3e\x60\51\56\151\x6e\x73\x65\162\x74\x42\145\146\x6f\x72\145\50\152\x51\x75\x65\x72\x79\x28\x22\x6c\141\x62\145\x6c\x5b\146\x6f\162\x3d\x27\42\x2b\44\145\x6c\145\155\x65\x6e\x74\x2e\x61\x74\x74\x72\x28\47\151\x64\47\51\x2b\42\47\135\x22\51\x29\73\15\xa\11\11\x9\x9\175\51\73\15\xa\x9\x9\74\57\x73\x63\162\x69\160\164\x3e";
        goto iWC;
        Jky:
        echo "\x3c\163\x63\x72\x69\x70\x74\x3e\15\xa\11\x9\x9\11\152\121\165\x65\162\x79\50\144\x6f\x63\165\155\145\156\164\51\56\162\x65\141\144\x79\50\x66\165\x6e\x63\164\x69\157\156\x20\x28\x29\40\x7b\15\12\11\x9\11\11\x9\152\x51\x75\145\x72\x79\50\x22\x23\x6c\157\147\151\x6e\146\x6f\x72\x6d\x22\x29\x2e\160\x72\x65\160\x65\156\x64\x28\140\74\144\x69\x76\x20\x63\154\x61\163\x73\75\42\x73\x73\x6f\x2d\142\x75\164\x74\x6f\x6e\163\x22\40\163\164\171\154\145\x3d\42\x70\x61\144\144\x69\156\x67\x2d\164\157\160\x3a\x20\x31\62\x70\x78\73\x22\76\74\57\x64\151\x76\76\x60\x29\x3b\15\xa\11\x9\11\x9\11\175\x29\73\15\12\x9\x9\x9\x3c\57\163\143\162\151\160\164\x3e";
        iWC:
        if (!(!empty($rK) and is_array($rK))) {
            goto F5E;
        }
        foreach ($rK as $fk) {
            $pK = $fk["\x69\x64\160\x5f\x6e\x61\x6d\145"];
            if (!(!empty($id[$pK]["\x61\144\x64\x5f\x62\x75\164\x74\x6f\x6e\137\167\160\x5f\154\157\147\x69\156"]) and $id[$pK]["\x61\x64\x64\x5f\x62\x75\164\x74\157\x6e\137\167\x70\137\154\x6f\147\x69\x6e"] == "\164\x72\165\x65")) {
                goto Dja;
            }
            $this->mo_saml_add_sso_button($id, $pK);
            Dja:
            xjA:
        }
        JIU:
        F5E:
    }
    function mo_saml_get_sso_button_html($id, $pK)
    {
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $hK = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\x5f\163\x61\x6d\154\137\x73\160\x5f\142\x61\163\145\x5f\x75\x72\x6c", false, $CP);
        if (!empty($hK)) {
            goto N0D;
        }
        $hK = home_url();
        N0D:
        $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\x6d\154\137\151\144\145\156\164\151\164\171\x5f\160\x72\157\x76\x69\144\x65\162\x73", true, $CP);
        if (!empty($rK[$pK]["\145\x6e\141\142\154\145\137\x69\144\160"])) {
            goto IEH;
        }
        return '';
        IEH:
        $Lt = get_sso_button($id, $pK);
        $V9 = '';
        if (empty($_GET["\162\145\x64\151\162\145\143\x74\137\x74\x6f"])) {
            goto hu7;
        }
        $V9 = SAMLSPUtilities::mo_saml_is_array($_GET["\162\x65\144\151\162\145\x63\164\137\164\157"]);
        $V9 = urlencode($V9);
        hu7:
        $X4 = "\74\141\40\x68\x72\x65\x66\75\x22" . $hK . "\57\77\157\160\164\151\x6f\156\75\x73\141\x6d\x6c\137\165\x73\x65\x72\137\154\x6f\147\151\x6e\x26\151\144\x70\x3d" . $pK . "\46\162\145\144\151\x72\145\143\x74\137\x74\157\75" . $V9 . "\x22\x20\x73\x74\x79\x6c\145\x3d\42\x74\x65\170\164\55\x64\x65\143\157\162\141\164\151\x6f\x6e\x3a\x6e\x6f\156\x65\73\x64\151\x73\160\x6c\x61\x79\72\x66\x6c\x65\170\73\x66\x6c\x65\170\55\x64\151\x72\145\143\164\151\157\156\x3a\162\x6f\167\73\141\x6c\151\147\x6e\x2d\151\x74\x65\x6d\x73\72\x63\145\x6e\x74\x65\162\73\x6a\165\x73\x74\151\x66\171\55\143\x6f\x6e\164\145\x6e\x74\72\x63\x65\x6e\164\x65\x72\73\42\76" . $Lt["\154\x6f\x67\x69\x6e\x5f\x62\x75\164\x74\x6f\x6e"] . "\x3c\x2f\141\x3e";
        $X4 = "\74\144\x69\x76\40\x73\x74\x79\154\145\x3d\x22\x77\151\x64\x74\x68\72\146\151\164\x2d\x63\x6f\156\164\x65\x6e\x74\73\x22\76" . $X4 . "\74\x2f\x64\151\166\x3e";
        $Ic = apply_filters("\x6d\x6f\137\163\x61\155\x6c\x5f\141\x64\144\137\x63\165\163\164\x6f\155\x5f\x63\163\163\x5f\x69\x6e\137\x73\163\157\137\x62\165\x74\x74\x6f\156", $X4, $pK);
        $Ic = preg_replace("\x23\74\x73\143\x72\151\160\164\50\x2e\x2a\x3f\51\x3e\x28\56\52\77\x29\74\x2f\x73\143\x72\x69\x70\164\x3e\43\151\x73", '', $Ic);
        if (empty($Ic)) {
            goto kNa;
        }
        $X4 = $Ic;
        kNa:
        return $X4;
    }
    function mo_saml_add_sso_button($id, $pK)
    {
        if (SAMLSPUtilities::mo_saml_is_user_logged_in()) {
            goto o69;
        }
        $X4 = $this->mo_saml_get_sso_button_html($id, $pK);
        $Lt = get_sso_button($id, $pK);
        $Uo = $Lt["\142\x75\x74\164\157\x6e\137\x70\157\163\151\x74\x69\x6f\156"];
        $HI = array("\x64\151\x76" => array("\x69\x64" => array(), "\163\x74\x79\154\145" => array(), "\156\141\155\x65" => array()), "\x61" => array("\150\x72\145\x66" => array(), "\163\x74\x79\154\x65" => array()), "\x69\x6d\x67" => array("\x73\164\171\x6c\145" => array(), "\163\x72\143" => array()), "\x73\x63\x72\151\x70\164" => array("\164\171\x70\x65" => array()), "\142" => array());
        if (!Mo_Saml_Hide_WP_Login_Handler::mo_saml_check_hide_login()) {
            goto sRU;
        }
        $HO = Mo_Saml_Hide_WP_Login_Handler::mo_saml_last_sso_button();
        $X4 = "\x3c\x64\151\166\x20\x69\144\x3d\x22" . "\x73\163\x6f\x5f\142\165\164\164\x6f\x6e" . $pK . "\x22\x20\163\164\x79\154\x65\x3d\42\x74\x65\170\164\x2d\x61\154\x69\x67\156\x3a\143\x65\156\164\x65\x72\x22\x3e" . $X4;
        if (!($HO != $pK)) {
            goto zl7;
        }
        $X4 .= "\74\x64\x69\166\x20\x73\x74\171\154\x65\75\42\160\141\x64\144\x69\x6e\x67\x3a\x31\60\x70\x78\x3b\x66\157\x6e\x74\x2d\x73\x69\172\x65\72\61\64\x70\170\73\42\76\74\x62\x3e\117\122\x3c\57\x62\76\74\57\144\x69\166\76\74\57\x64\x69\x76\x3e";
        zl7:
        echo "\74\x73\x63\162\x69\160\164\x3e\xd\xa\11\x9\11\x9\152\121\165\x65\162\171\50\x64\x6f\143\165\155\x65\156\164\x29\x2e\162\x65\141\144\171\x28\x66\x75\x6e\143\164\x69\157\156\40\x28\x29\x20\173\15\12\11\11\11\x9\x9\152\121\x75\145\x72\x79\x28\42\56\163\163\x6f\x2d\x62\x75\x74\x74\x6f\x6e\163\x22\51\x2e\x61\x70\160\145\156\144\50\x27" . wp_kses($X4, $HI) . "\47\51\x3b\xd\xa\x9\11\11\x9\x7d\x29\x3b\xd\xa\11\x9\x9\x9\x3c\57\x73\x63\x72\151\160\164\76";
        return;
        sRU:
        if ($Uo == "\141\142\157\166\145") {
            goto bnt;
        }
        $HI = array("\144\151\166" => array("\x69\144" => true, "\x73\164\171\x6c\x65" => true), "\x61" => array("\150\x72\145\x66" => true, "\x73\164\x79\x6c\x65" => true), "\x69\155\x67" => array("\x73\x74\x79\154\145" => true, "\163\162\x63" => true), "\x62" => array());
        $X4 = "\74\x64\x69\x76\40\x69\x64\75\x22" . "\163\163\x6f\x5f\142\165\164\x74\x6f\x6e" . $pK . "\42\x20\x73\x74\171\154\145\x3d\42\x74\x65\x78\164\x2d\141\x6c\x69\x67\156\72\143\x65\x6e\164\x65\x72\x22\76\x3c\144\151\166\40\x73\x74\x79\154\x65\75\x22\160\141\x64\x64\x69\156\147\x3a\x20\61\x30\x70\170\40\x30\73\x66\x6f\x6e\x74\55\163\151\172\145\72\61\64\x70\170\73\42\76\x3c\142\x3e\117\x52\x3c\x2f\x62\x3e\x3c\57\144\x69\x76\x3e" . $X4 . "\74\57\x64\151\x76\x3e";
        echo "\x3c\163\143\x72\151\x70\164\76\xd\xa\x9\x9\11\x9\x6a\121\165\x65\162\x79\50\144\157\143\x75\155\145\156\x74\x29\56\162\145\141\144\171\x28\x66\165\156\x63\164\151\x6f\156\x20\x28\x29\40\173\xd\12\x9\x9\x9\11\11\x6a\x51\x75\x65\162\x79\50\x22\56\163\x73\157\55\x62\x75\x74\x74\x6f\x6e\x73\x2d\142\x65\x6c\x6f\x77\x22\x29\x2e\x61\160\160\x65\x6e\x64\x28\x27" . wp_kses($X4, $HI) . "\x27\x29\x3b\xd\xa\x9\x9\x9\11\x7d\x29\x3b\15\12\11\x9\x9\11\x3c\x2f\163\x63\162\151\160\x74\76";
        return;
        goto u2t;
        bnt:
        $X4 = "\74\144\151\166\x20\x69\x64\x3d\42" . "\x73\163\157\x5f\142\x75\164\x74\157\156" . $pK . "\x22\40\x73\164\x79\x6c\x65\x3d\x22\x74\145\x78\x74\55\x61\154\x69\x67\x6e\x3a\143\x65\x6e\164\x65\162\42\76" . $X4 . "\74\x64\151\x76\40\163\164\x79\x6c\x65\x3d\42\x70\141\x64\144\x69\x6e\x67\72\x31\x30\x70\170\x3b\146\157\156\164\55\163\151\172\145\72\61\64\160\x78\x3b\x22\x3e\74\x62\76\x4f\x52\74\57\142\x3e\x3c\x2f\x64\x69\166\x3e\x3c\57\144\151\x76\76";
        echo "\74\163\x63\162\151\160\164\76\xd\12\11\11\x9\x9\152\121\x75\x65\x72\171\x28\144\x6f\x63\165\155\145\x6e\164\51\x2e\162\x65\141\144\x79\x28\x66\165\156\143\164\151\x6f\156\40\50\x29\40\173\15\12\x9\x9\x9\x9\11\152\121\x75\145\x72\171\x28\42\x2e\x73\x73\x6f\x2d\x62\x75\164\164\x6f\156\x73\55\141\x62\x6f\x76\x65\42\51\x2e\x61\160\x70\x65\156\144\x28\47" . wp_kses($X4, $HI) . "\47\51\73\15\12\x9\x9\11\11\x7d\51\73\15\12\x9\11\x9\11\x3c\x2f\x73\143\162\x69\x70\164\76";
        u2t:
        o69:
    }
    function add_federation_link()
    {
        if (SAMLSPUtilities::mo_saml_is_plugin_active("\x6d\x69\156\151\157\x72\x61\x6e\147\x65\55\x66\145\144\x65\162\x61\164\x69\x6f\156\55\x73\x73\x6f\57\x66\x65\x64\x65\x72\141\x74\x69\157\156\55\163\x73\157\56\x70\x68\x70")) {
            goto LJL;
        }
        return;
        LJL:
        $PZ = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\x5f\163\141\x6d\x6c\x5f\146\x65\144\x65\162\x61\164\151\157\x6e\163");
        if (empty($PZ)) {
            goto Z1H;
        }
        foreach ($PZ as $JX => $pO) {
            if (!$pO["\145\156\x61\142\x6c\x65"]) {
                goto qUH;
            }
            $Oz = $pO["\x64\151\x73\143\x6f\166\145\x72\x79\137\x75\x72\154"];
            if (empty($pO["\x70\x61\x72\141\155\x65\x74\145\162\163"])) {
                goto AeI;
            }
            if (empty($pO["\x70\x61\162\141\155\145\x74\145\162\x73"])) {
                goto zlk;
            }
            $Oz = $Oz . "\77";
            foreach ($pO["\x70\x61\162\141\x6d\x65\x74\145\x72\163"] as $or => $EB) {
                $Oz = $Oz . $or . "\75" . $EB;
                if (!next($pO["\x70\141\162\x61\155\145\x74\x65\x72\163"])) {
                    goto MNn;
                }
                $Oz = $Oz . "\46";
                MNn:
                qtj:
            }
            FIE:
            zlk:
            AeI:
            $X4 = "\xd\12\x9\x9\11\11\11\74\x62\162\x2f\x3e\15\xa\x9\x9\x9\x9\x9\x3c\x68\x72\76\15\xa\x9\x9\11\11\11\74\x64\151\x76\x20\x73\x74\171\x6c\145\x3d\x22\164\145\170\164\x2d\x61\x6c\151\x67\x6e\x3a\143\145\x6e\164\145\162\73\40\x70\x61\x64\x64\151\156\147\x3a\65\x70\170\73\x22\76\xd\xa\11\x9\x9\11\11\11\74\150\x34\x3e\x4c\x6f\x67\151\156\x20\x77\x69\x74\150\x3c\57\150\64\76\xd\xa\x9\11\11\11\11\11\x3c\142\x72\57\x3e\xd\12\11\11\11\x9\11\11\74\141\x20\150\x72\x65\x66\x3d\x22" . $Oz . "\x22\40\163\x74\x79\154\x65\75\42\164\x65\170\x74\55\x64\145\143\157\x72\x61\x74\151\157\x6e\72\156\157\156\145\73\42\40\x74\141\162\x67\x65\x74\x3d\x22\137\142\154\x61\x6e\x6b\42\76";
            if ($JX == "\111\156\103\157\155\x6d\157\156") {
                goto SBd;
            }
            if ($JX == "\110\101\113\101") {
                goto VQL;
            }
            if ($JX == "\x48\113\x41\x46") {
                goto yz8;
            }
            $X4 = $X4 . "\x3c\x69\x6e\160\165\x74\x20\xd\12\x9\x9\11\11\11\11\11\11\11\164\x79\160\x65\75\x22\x62\165\164\164\x6f\x6e\42\xd\12\x9\x9\11\x9\11\x9\11\11\x9\166\x61\x6c\x75\145\x3d\x22" . $JX . "\x22\15\xa\x9\11\x9\11\x9\x9\11\11\11\x73\164\x79\154\x65\75\42\x77\151\x64\x74\150\x3a\x31\x30\x30\x70\170\73\15\12\11\11\x9\x9\x9\11\11\11\x9\150\145\151\x67\150\164\72\65\60\x70\x78\x3b\15\12\x9\x9\11\11\11\11\11\x9\11\x62\157\x72\x64\x65\162\55\x72\x61\x64\x69\165\x73\72\65\160\x78\73\xd\xa\11\x9\x9\x9\x9\x9\11\11\x9\x62\x61\143\x6b\x67\162\157\x75\156\144\x2d\x63\x6f\154\x6f\162\72\x23\60\60\x38\65\x62\141\73\15\xa\x9\11\11\x9\11\11\11\11\11\142\157\162\x64\x65\162\55\143\157\154\x6f\x72\72\x74\162\x61\156\163\160\x61\x72\x65\x6e\164\x3b\15\12\11\11\x9\x9\x9\11\11\11\x9\143\x6f\x6c\157\162\x3a\43\x66\x66\146\x66\146\146\x3b\xd\12\11\x9\11\x9\11\x9\11\11\11\146\157\x6e\x74\55\163\x69\x7a\x65\72\x32\60\160\170\73\xd\xa\11\x9\x9\x9\11\11\x9\x9\x9\x70\141\144\x64\x69\x6e\x67\x3a\x30\x70\170\73\42\xd\12\x9\11\x9\x9\11\11\11\x9\76";
            goto PSI;
            SBd:
            $X4 = $X4 . "\x3c\x69\155\x67\x20\163\x72\x63\75\x22" . SAMLSPUtilities::mo_saml_get_plugin_base_url() . "\x69\x6d\141\147\145\163\x2f\x69\156\x63\x6f\x6d\x6d\157\156\56\167\x65\142\160\x22\x20\x73\164\171\154\x65\x3d\x22\142\157\162\144\x65\162\x3a\156\x6f\x6e\145\x3b\40\167\x69\x64\x74\150\72\x32\70\60\x70\170\73\x20\150\145\151\x67\x68\164\72\66\x30\160\x78\x3b\42\40\141\x6c\164\x3d\x22\111\x6e\143\157\x6d\155\157\156\40\x76\151\x61\x20\x6d\x69\156\x69\x4f\162\141\x6e\x67\x65\x22\x3e";
            goto PSI;
            VQL:
            $X4 = $X4 . "\x3c\151\155\x67\40\163\162\143\x3d\x22" . SAMLSPUtilities::mo_saml_get_plugin_base_url() . "\x69\x6d\141\x67\145\163\x2f\x68\141\153\x61\x2e\167\x65\142\x70\42\x20\x73\x74\x79\x6c\x65\x3d\42\142\x6f\x72\x64\x65\x72\x3a\x6e\x6f\x6e\145\73\40\x77\x69\x64\164\x68\x3a\x31\x35\x30\x70\x78\x3b\40\150\x65\151\147\x68\164\x3a\67\x35\160\170\73\x22\x20\x61\x6c\x74\x3d\x22\110\141\153\141\40\166\151\141\40\x6d\151\156\x69\117\x72\x61\156\x67\x65\42\x3e";
            goto PSI;
            yz8:
            $X4 = $X4 . "\x3c\151\x6d\147\x20\x73\x72\143\75\x22" . SAMLSPUtilities::mo_saml_get_plugin_base_url() . "\x69\155\x61\x67\x65\163\57\x68\x6b\x61\146\56\167\x65\x62\160\x22\x20\x73\x74\x79\x6c\145\75\42\142\157\x72\x64\x65\162\72\x6e\x6f\x6e\145\73\40\x77\x69\x64\x74\x68\72\x32\60\x30\x70\170\x3b\x20\x68\145\x69\x67\x68\164\72\66\x30\x70\x78\x3b\42\x20\x61\154\164\75\42\110\x4b\x41\x46\40\x76\151\x61\x20\155\151\x6e\151\117\x72\141\156\x67\x65\x22\76";
            PSI:
            $X4 = $X4 . "\15\xa\x9\x9\x9\x9\11\11\74\57\x61\76\15\12\11\x9\x9\x9\11\74\57\x64\x69\x76\76\15\xa\11\x9\x9\11\11\74\x68\x72\x3e\xd\xa\11\x9\11\11\x9\74\142\162\x2f\x3e";
            $HI = array("\142\x72" => array(), "\150\162" => array(), "\x64\151\166" => array("\x73\x74\171\154\145" => array(), "\x69\x64" => array()), "\x69\x6e\x70\x75\x74" => array("\x76\x61\x6c\165\145" => array(), "\x74\171\x70\x65" => array(), "\x73\x74\171\x6c\145" => array()), "\x61" => array("\x68\162\x65\146" => array(), "\163\164\x79\x6c\145" => array(), "\x74\141\162\x67\x65\164" => array()), "\x69\155\x67" => array("\x73\162\x63" => array(), "\141\x6c\164" => array(), "\163\x74\x79\154\145" => array()));
            echo wp_kses($X4, $HI);
            qUH:
            lKQ:
        }
        nU_:
        Z1H:
    }
    function mo_saml_jquery_default_login()
    {
        wp_enqueue_script("\x6a\161\x75\145\x72\171");
    }
    function mo_saml_footer_form()
    {
        if (Mo_License_Service::is_customer_license_verified()) {
            goto HEL;
        }
        return;
        HEL:
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $bY = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\x5f\163\x61\x6d\154\137\x65\156\x61\x62\154\145\x5f\144\x6f\x6d\x61\x69\156\x5f\155\141\160\x70\151\x6e\147", false, $CP);
        $ae = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\163\141\155\154\137\144\157\155\141\151\x6e\137\154\x6f\x67\x69\156\x5f\146\141\x69\154", false, $CP);
        $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\x61\155\154\137\151\144\x65\x6e\x74\151\164\x79\137\x70\162\x6f\166\151\x64\x65\162\x73", true, $CP);
        $MA = EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\x5f\x73\x61\155\154\x5f\x65\156\x61\142\x6c\145\x5f\150\x69\x64\145\137\x77\160\137\154\157\x67\151\x6e", false, $CP);
        $GH = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\x6d\x6c\137\151\144\x70\137\144\x6f\155\141\x69\x6e\137\155\x61\160\x70\x69\156\x67", true, $CP);
        $yT = EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\x5f\x73\141\155\x6c\137\x62\141\x63\x6b\x64\157\157\x72\x5f\x75\x72\x6c", false, $CP) ? EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\x5f\x73\141\155\x6c\x5f\x62\x61\x63\x6b\x64\x6f\x6f\x72\137\165\x72\x6c", false, $CP) : "\146\x61\x6c\163\145";
        $jN = EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\x5f\163\141\155\154\137\141\154\154\x6f\167\137\167\160\x5f\x73\151\147\156\x69\156", false, $CP);
        $UQ = array();
        $Gl = array();
        foreach ($GH as $R2 => $EB) {
            if (!(!empty($rK[$R2]) && empty($rK[$R2]["\x65\x6e\x61\142\154\x65\137\x69\x64\x70"]))) {
                goto Oim;
            }
            $Gl = $rK[$R2]["\x69\x64\x70\137\x6e\141\155\x65"];
            if (empty($UQ)) {
                goto euV;
            }
            $UQ = array($UQ, $Gl);
            goto Kc3;
            euV:
            $UQ = $Gl;
            Kc3:
            Oim:
            U4_:
        }
        zJ6:
        if (!is_array($UQ)) {
            goto L0k;
        }
        $UQ = implode("\x2c", $UQ);
        L0k:
        if (!($jN != "\x66\141\154\x73\145" && (!empty($_REQUEST["\x73\141\155\x6c\x5f\x73\163\x6f"]) && $_REQUEST["\163\141\155\x6c\x5f\163\x73\157"] == $yT))) {
            goto L3V;
        }
        return;
        L3V:
        if ("\x74\x72\x75\x65" != $MA && $bY == "\x74\162\165\x65" && !empty($rK)) {
            goto Z_7;
        }
        echo "\11\x9\x9\x3c\163\x63\162\151\160\x74\x3e\15\xa\11\11\x9\xd\xa\x9\x9\11\152\121\165\x65\x72\x79\x28\x27\154\141\x62\145\154\x5b\146\x6f\162\x3d\42\165\x73\145\162\x5f\x70\141\x73\x73\x22\x5d\x27\x29\56\x73\150\x6f\167\50\51\73\xd\xa\11\11\11\152\121\165\145\162\171\x28\47\43\165\163\145\x72\137\160\x61\163\163\47\x29\56\163\x68\x6f\167\50\51\73\15\xa\11\11\x9\x6a\x51\x75\x65\x72\x79\50\47\144\x69\x76\x23\154\157\147\151\156\40\160\x23\x6e\141\x76\47\x29\56\x73\150\x6f\x77\50\x29\73\15\12\11\x9\11\xd\12\11\x9\x9\152\121\x75\x65\x72\x79\50\47\43\x75\x73\145\x72\137\x70\141\163\x73\47\x29\56\x76\x61\154\x28\x22\42\51\x3b\15\12\11\x9\11\xd\xa\11\x9\11\x3c\x2f\x73\x63\x72\151\160\x74\76\15\xa\x9\x9\11";
        goto SZP;
        Z_7:
        echo "\11\x9\11\74\163\143\162\x69\x70\164\x3e\xd\12\11\x9\11\x9\x63\x6f\156\163\164\x20\165\163\x65\162\114\x6f\147\151\x6e\40\75\40\144\x6f\x63\x75\155\x65\x6e\x74\x2e\161\165\145\162\171\123\145\154\145\x63\164\157\x72\50\47\x6c\141\x62\145\154\x5b\x66\x6f\x72\x3d\42\x75\163\145\x72\x5f\x6c\x6f\x67\x69\x6e\42\x5d\47\51\x3b\xd\12\x9\x9\11\x9\x75\x73\x65\x72\x4c\157\147\151\156\56\x74\145\x78\164\103\157\x6e\x74\x65\x6e\164\40\x3d\40\42\x45\155\x61\x69\154\40\x41\144\144\162\145\163\x73\x22\x3b\15\xa\11\x9\11\x9\x6a\121\165\x65\x72\171\x28\x27\43\x75\163\145\x72\137\160\141\163\163\x27\51\x2e\141\164\x74\162\x28\x27\144\151\x73\x61\142\x6c\145\x64\x27\54\x20\x27\144\x69\163\x61\x62\x6c\145\144\47\x29\73\15\12\11\11\x9\11\152\121\165\145\x72\x79\50\47\43\x75\x73\145\162\x5f\x70\x61\163\x73\47\x29\56\x72\145\155\x6f\x76\x65\x41\164\x74\x72\x28\47\162\x65\161\165\151\162\x65\144\x27\x29\73\15\12\11\11\x9\x9\152\x51\x75\x65\162\x79\50\47\142\157\144\171\56\x6c\157\147\151\x6e\x2d\141\143\x74\151\x6f\156\55\x6c\x6f\x67\x69\x6e\x20\144\151\166\x23\x6c\157\147\x69\156\40\146\157\x72\155\43\x6c\157\147\x69\x6e\x66\157\x72\155\40\160\56\146\x6f\162\147\x65\164\x6d\x65\x6e\157\x74\47\51\x2e\x68\x69\x64\x65\x28\x29\x3b\xd\12\11\11\11\x9\x6a\121\x75\145\162\x79\50\x22\43\154\157\147\151\156\146\157\162\x6d\x22\x29\x2e\163\165\142\155\151\164\x28\146\x75\156\143\164\x69\157\156\50\145\166\x65\x6e\164\x29\x20\x7b\xd\12\x9\11\11\11\11\x76\x61\x72\40\165\x73\x65\x72\156\x61\x6d\x65\40\75\40\152\x51\165\145\x72\x79\x28\47\x23\x75\x73\145\162\137\x6c\x6f\x67\151\x6e\x27\x29\x2e\x76\x61\x6c\x28\51\73\15\xa\11\x9\11\x9\x9\152\121\x75\x65\162\x79\x2e\141\x6a\x61\x78\50\x7b\15\xa\x9\11\11\x9\11\11\x75\x72\x6c\72\40\42";
        echo esc_url(home_url()) . "\x3f\x6d\157\137\163\x61\155\154\x5f\144\x6f\x6d\x61\x69\156\x5f\x6d\x61\160\160\151\x6e\x67\137\x65\155\x61\x69\x6c\75";
        echo "\x22\40\x2b\40\x75\163\145\162\156\141\155\x65\x2c\15\xa\x9\x9\x9\11\11\x9\141\x73\x79\x6e\x63\x3a\x20\x66\141\x6c\x73\x65\x2c\15\12\11\x9\x9\11\11\11\x73\165\x63\x63\145\x73\x73\x3a\40\x66\165\156\x63\x74\x69\x6f\156\x28\x72\145\x73\x70\157\x6e\x73\x65\51\x20\x7b\xd\xa\x9\x9\11\x9\x9\11\x9\x74\x72\171\x20\173\xd\12\11\x9\x9\x9\x9\11\x9\x9\x76\141\x72\x20\162\x65\x73\165\154\x74\40\75\40\112\123\x4f\116\56\x70\141\x72\x73\145\x28\162\145\163\x70\157\156\163\145\51\x3b\15\12\11\x9\11\11\11\11\x9\x9\x69\146\x20\x28\162\145\163\165\154\164\56\163\x74\x61\164\165\163\40\x3d\75\x3d\40\x27\162\x65\144\151\x72\145\143\x74\x27\51\x20\x7b\xd\12\11\11\x9\x9\11\11\11\11\x9\x72\145\164\x75\x72\156\73\xd\xa\x9\x9\x9\x9\11\x9\x9\11\175\x20\x65\x6c\x73\x65\x20\151\x66\x20\50\x72\x65\163\165\154\164\x2e\x73\x74\141\x74\x75\x73\40\x3d\75\75\40\47\x77\x70\x5f\154\x6f\147\x69\x6e\47\x29\40\173\xd\12\11\11\x9\x9\x9\11\x9\11\x9\x6a\x51\x75\145\x72\x79\50\47\154\141\142\145\154\x5b\x66\157\162\75\42\x75\x73\145\x72\x5f\x70\x61\163\x73\x22\135\x27\51\x2e\163\x68\x6f\x77\x28\51\73\xd\12\11\11\11\x9\11\x9\11\11\x9\x6a\x51\x75\145\162\171\x28\x27\43\x75\x73\145\x72\x5f\x70\141\163\x73\47\51\x2e\163\x68\x6f\167\50\51\x3b\15\xa\x9\11\x9\x9\x9\x9\x9\11\x9\x6a\x51\x75\145\162\171\x28\x27\x64\x69\166\x23\154\x6f\x67\x69\156\x20\x70\43\x6e\141\166\47\51\x2e\163\x68\157\x77\50\x29\x3b\15\xa\11\x9\x9\x9\11\11\11\11\x9\152\x51\165\x65\x72\171\x28\x27\x23\x75\163\x65\162\137\x70\141\x73\163\x27\51\56\162\145\155\x6f\x76\145\x41\164\x74\162\50\x27\x64\151\x73\x61\142\x6c\145\x64\x27\51\x3b\15\12\11\x9\11\11\11\x9\x9\11\x9\x6a\121\165\x65\162\171\50\x27\142\x6f\x64\x79\56\154\157\147\x69\156\55\141\x63\164\x69\x6f\x6e\55\154\x6f\x67\x69\x6e\x20\144\x69\166\43\154\157\147\151\x6e\x20\146\157\162\x6d\x23\154\157\147\x69\156\x66\157\162\155\40\x70\x2e\x66\157\162\147\145\164\155\x65\x6e\157\x74\x27\51\56\163\x68\x6f\x77\x28\51\x3b\15\xa\11\x9\x9\x9\x9\x9\x9\x9\x9\152\x51\165\x65\162\x79\50\47\160\56\146\x6f\x72\147\x65\164\155\x65\156\x6f\x74\x27\51\x2e\x63\x73\x73\x28\47\144\x69\x73\160\154\141\171\x27\54\x20\47\x66\x6c\x65\170\47\x29\56\143\x73\x73\x28\47\x61\x6c\151\147\x6e\x2d\x69\x74\145\x6d\163\47\x2c\40\47\x63\145\156\164\x65\162\47\x29\x3b\15\12\11\x9\x9\x9\11\11\11\x9\x9\152\x51\165\145\162\171\50\x27\x23\165\x73\145\x72\137\x70\x61\x73\x73\47\x29\x2e\141\164\164\162\50\x27\162\x65\161\x75\x69\162\x65\x64\47\54\x20\164\162\165\x65\51\x3b\15\12\11\x9\x9\11\x9\11\x9\x9\11\166\141\162\x20\160\141\163\163\x77\x6f\162\144\x20\75\x20\152\121\165\x65\x72\x79\x28\47\43\x75\x73\145\x72\x5f\160\x61\163\163\x27\x29\56\x76\x61\154\x28\51\73\15\xa\x9\x9\11\x9\11\11\x9\x9\x9\x69\146\40\50\x70\141\163\163\167\157\162\144\x20\x3d\75\x20\47\x27\51\x20\x7b\15\xa\11\x9\x9\11\11\x9\11\11\11\11\145\x76\x65\x6e\164\x2e\x70\162\x65\166\145\x6e\164\104\145\x66\141\165\154\164\50\51\73\xd\12\x9\11\11\11\11\11\11\11\x9\x7d\15\xa\x9\x9\x9\x9\11\x9\x9\x9\x7d\x20\145\x6c\163\x65\40\151\146\x20\x28\x72\145\163\x75\x6c\x74\56\163\x74\141\x74\165\x73\x20\75\75\x3d\40\47\x65\x72\162\157\x72\x27\51\x20\x7b\xd\xa\x9\x9\x9\11\11\11\11\x9\11\152\121\165\x65\x72\x79\50\47\x23\x75\x73\145\162\x5f\x70\x61\163\x73\47\x29\56\162\x65\x6d\157\166\145\101\164\x74\162\x28\47\x64\151\163\141\142\154\145\x64\x27\x29\73\15\xa\11\11\x9\x9\x9\11\11\11\x7d\15\xa\11\11\11\11\x9\11\x9\175\x20\143\141\x74\143\150\x20\50\145\x29\x20\173\xd\12\x9\x9\x9\x9\x9\x9\x9\11\162\145\x74\165\x72\x6e\x3b\xd\12\x9\11\x9\x9\x9\11\11\x7d\xd\xa\x9\11\x9\11\11\11\x7d\15\12\11\x9\11\x9\11\175\x29\x3b\15\12\x9\11\x9\11\x7d\51\73\xd\xa\x9\x9\x9\74\x2f\163\143\x72\151\160\164\x3e\15\xa\x9\x9\11";
        SZP:
        $this->mo_saml_modify_login_form();
    }
    function mo_saml_init_wp_cli()
    {
        if (!(defined("\x57\120\x5f\103\114\x49") && WP_CLI)) {
            goto LFd;
        }
        require_once Mo_Saml_Plugin_Files::MO_SAML_WPCLI;
        LFd:
    }
    function mo_saml_init_login_form()
    {
        if (!(!Mo_License_Service::is_customer_license_verified() && !Mo_License_Service::is_customer_license_valid(false, false))) {
            goto T0i;
        }
        return;
        T0i:
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $bY = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\x6f\x5f\163\141\155\154\x5f\145\x6e\x61\142\154\x65\x5f\x64\157\x6d\141\151\x6e\137\x6d\x61\x70\x70\151\156\147", false, $CP);
        $MA = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\x73\x61\x6d\154\x5f\x65\156\x61\142\x6c\x65\x5f\150\151\144\x65\x5f\x77\x70\137\154\x6f\x67\x69\156", false, $CP);
        if (!("\164\162\165\x65" != $MA && ($bY === "\x74\x72\165\x65" || $bY === true))) {
            goto NL7;
        }
        $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\163\x61\155\154\x5f\x69\x64\145\156\x74\x69\x74\171\137\160\162\157\166\x69\144\145\162\163", true, $CP);
        if (empty($rK)) {
            goto MjK;
        }
        $gC = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\x6d\x6c\137\151\x64\160\x5f\144\x6f\155\x61\x69\156\137\155\x61\160\x70\x69\156\147", true, $CP);
        $yT = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\x5f\x73\141\x6d\x6c\x5f\x62\141\143\x6b\x64\157\x6f\x72\137\165\162\154", false, $CP) ? EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\137\163\141\155\154\137\142\141\x63\x6b\x64\157\x6f\162\x5f\x75\162\x6c", false, $CP) : "\146\141\154\x73\145";
        $jN = EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\137\163\x61\155\x6c\137\x61\154\154\157\x77\137\x77\160\x5f\163\x69\x67\x6e\x69\x6e", false, $CP);
        if (!empty($_GET["\x67\145\164\x5f\x64\157\155\x61\151\156\x5f\155\141\x70\x70\x69\x6e\147"]) && $_GET["\x67\x65\x74\x5f\144\157\155\141\151\x6e\137\x6d\x61\x70\160\151\156\x67"] == "\x74\162\165\145") {
            goto nH4;
        }
        if ($jN != "\146\141\154\163\x65" && (!empty($_REQUEST["\x73\141\x6d\154\137\x73\163\157"]) && $_REQUEST["\x73\x61\x6d\154\137\x73\163\157"] == $yT)) {
            goto UN2;
        }
        if (!empty($_GET["\x6d\157\x5f\163\x61\155\154\x5f\144\x6f\155\141\151\156\137\x6d\141\160\160\x69\156\x67\x5f\x65\155\x61\151\154"]) && $_GET["\x6d\x6f\137\163\x61\x6d\154\x5f\144\157\x6d\141\x69\x6e\137\155\x61\160\x70\151\156\x67\137\145\155\x61\151\154"] != false && !SAMLSPUtilities::mo_saml_is_user_logged_in()) {
            goto wxB;
        }
        goto pWA;
        nH4:
        echo json_encode($gC);
        exit;
        goto pWA;
        UN2:
        return;
        goto pWA;
        wxB:
        $Io = $_GET["\x6d\x6f\137\163\141\x6d\154\137\x64\157\155\x61\151\156\x5f\155\x61\x70\x70\x69\156\147\x5f\145\155\x61\151\154"];
        $V9 = '';
        if (empty($_REQUEST["\162\x65\144\151\162\145\143\164\x5f\164\157"])) {
            goto zlb;
        }
        $V9 = SAMLSPUtilities::mo_saml_is_array($_REQUEST["\162\x65\144\x69\x72\x65\x63\x74\137\x74\157"]);
        zlb:
        $Al = $this->mo_saml_handle_redirection_based_on_domain_mapping($Io, $V9);
        if ($Al["\x73\164\141\x74\x75\x73"] === "\162\145\144\151\162\x65\x63\x74") {
            goto KVi;
        }
        echo json_encode($Al);
        exit;
        goto abw;
        KVi:
        exit;
        abw:
        pWA:
        add_filter("\x67\145\164\164\145\170\x74", function ($hD) {
            $wx = "\167\160\55\154\x6f\x67\151\x6e\x2e\x70\x68\x70";
            $Bf = "\160\x61\147\145\x6e\157\167";
            if (!(!empty($GLOBALS[$Bf]) && SAMLSPUtilities::mo_saml_in_array($GLOBALS[$Bf], array($wx)))) {
                goto kqE;
            }
            if (!("\125\x73\x65\x72\x6e\141\x6d\145" == $hD)) {
                goto IyG;
            }
            return "\105\x6d\x61\x69\154";
            IyG:
            kqE:
            return $hD;
        }, 20);
        wp_register_style("\x68\151\144\145\x2d\154\x6f\x67\x69\x6e", plugins_url("\151\156\143\154\x75\144\145\163\x2f\x63\x73\x73\57\x68\x69\144\145\x2d\154\x6f\147\151\156\x2e\143\163\163\77\166\x65\162\x73\x69\x6f\156\x3d\64\56\x31\x2e\63", __FILE__));
        wp_register_style("\163\x68\x6f\x77\55\154\157\x67\151\156", plugins_url("\x69\x6e\143\x6c\165\x64\145\163\57\x63\x73\163\57\x73\150\x6f\167\55\x6c\157\x67\151\x6e\56\143\x73\x73\77\166\x65\x72\163\151\x6f\156\75\x34\56\x31\x2e\63", __FILE__));
        wp_enqueue_style("\x73\150\157\167\x2d\x6c\x6f\x67\x69\156");
        wp_enqueue_style("\150\x69\x64\145\55\x6c\157\147\x69\156");
        MjK:
        NL7:
    }
    function mo_get_saml_idp_list_shortcode()
    {
        if (Mo_License_Service::is_customer_license_verified()) {
            goto lkB;
        }
        return;
        lkB:
        if (SAMLSPUtilities::mo_saml_is_user_logged_in()) {
            goto l6l;
        }
        $X4 = '';
        if (mo_saml_is_sp_configured()) {
            goto IgE;
        }
        $X4 = "\x50\x6c\145\141\x73\x65\40\143\x6f\156\146\x69\147\165\162\145\40\x74\x68\x65\40\x6d\151\156\x69\117\x72\x61\x6e\147\x65\40\x53\101\x4d\x4c\40\x50\154\165\x67\x69\156\40\x66\151\162\x73\x74\x2e";
        goto S7R;
        IgE:
        if (isset($_REQUEST["\162\145\x64\151\162\x65\x63\x74\137\x74\x6f"])) {
            goto VLy;
        }
        $V9 = urlencode(saml_get_current_page_url());
        goto qx1;
        VLy:
        $V9 = htmlspecialchars(SAMLSPUtilities::mo_saml_is_array($_REQUEST["\162\x65\x64\x69\162\145\x63\164\137\x74\x6f"]));
        qx1:
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\155\154\x5f\151\x64\x65\156\x74\151\x74\x79\137\x70\162\x6f\166\x69\144\x65\162\x73", true, $CP);
        $Wc = EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\137\163\x61\x6d\x6c\x5f\x73\150\157\x72\x74\143\157\x64\145\x5f\154\x6f\147\151\x6e\x5f\164\145\x78\164", false, $CP);
        if ($Wc) {
            goto bzn;
        }
        $Wc = "\x4c\157\x67\x69\156\x20\x77\151\164\x68\x20\x20";
        bzn:
        $X4 = $Wc . "\40\40";
        $X4 = $X4 . "\x3c\163\145\154\x65\143\x74\40\x6f\x6e\143\150\x61\156\147\145\x3d\x22\x72\145\x64\151\x72\x65\143\x74\x54\157\111\104\120\50\x74\150\151\x73\x2e\166\x61\154\165\145\x29\x22\x3e\xd\xa\11\11\11\x9\x9\x3c\x6f\160\164\x69\x6f\x6e\x20\144\x69\x73\x61\142\154\x65\144\x20\163\145\x6c\x65\143\x74\145\144\x3e\x2d\x2d\x53\145\154\145\143\x74\40\x79\x6f\x75\162\x20\x49\144\x50\x2d\55\x3c\57\157\x70\164\x69\x6f\156\x3e";
        $Mr = '';
        foreach ($rK as $R2 => $EB) {
            if (!empty($EB["\x65\x6e\141\142\x6c\x65\137\x69\x64\x70"])) {
                goto X_w;
            }
            goto cyI;
            X_w:
            $EI = !empty($EB["\x69\144\x70\x5f\x64\x69\x73\x70\154\x61\x79\137\156\141\155\145"]) ? $EB["\151\144\x70\137\x64\151\163\x70\154\x61\171\x5f\156\141\x6d\x65"] : $R2;
            $Mr = $Mr . "\74\x6f\160\164\x69\x6f\x6e\40\x76\141\x6c\165\145\x3d\x22" . home_url() . "\57\x3f\157\160\164\151\157\x6e\x3d\x73\x61\x6d\x6c\137\165\163\x65\162\137\x6c\x6f\147\151\x6e\x26\x69\144\160\75" . $R2 . "\46\162\x65\x64\151\162\145\143\x74\137\x74\x6f\75" . $V9 . "\42\76" . esc_html($EI) . "\74\57\x6f\x70\x74\151\x6f\156\x3e";
            cyI:
        }
        VP5:
        $X4 = $X4 . $Mr . "\74\57\x73\145\154\x65\x63\x74\76\xd\xa\x9\11\x9\11\x9\74\x73\143\162\x69\x70\164\76\15\12\11\11\x9\11\x9\x66\x75\x6e\143\164\x69\x6f\x6e\x20\x72\145\144\151\x72\145\143\x74\124\x6f\x49\x44\x50\50\x75\x72\154\x29\x7b\xd\12\11\11\11\x9\x9\x9\x6c\x6f\143\x61\x74\151\157\x6e\40\x3d\x20\165\162\x6c\73\xd\12\x9\11\11\11\11\175\xd\12\x9\x9\11\x9\x9\x3c\x2f\163\143\162\151\160\x74\76\15\12\x9\11\x9\11\x9";
        $X4 = apply_filters("\x6d\x6f\x5f\x73\141\x6d\x6c\137\x61\144\144\137\143\165\x73\x74\157\x6d\137\143\163\163\x5f\151\x6e\137\x73\150\157\162\x74\143\x6f\144\145\137\144\x72\x6f\160\144\157\167\x6e", $X4);
        S7R:
        return $X4;
        l6l:
        return self::mo_saml_get_shortcode_for_logged_in_users();
    }
    function mo_saml_get_idp_shortcode($wL = array(), string $Qm = '', string $qw = "\115\x4f\137\x53\101\115\x4c\x5f\x4c\x4f\107\x49\116")
    {
        if (Mo_License_Service::is_customer_license_verified()) {
            goto YTc;
        }
        return;
        YTc:
        if (!SAMLSPUtilities::mo_saml_is_user_logged_in()) {
            goto fdJ;
        }
        return self::mo_saml_get_shortcode_for_logged_in_users();
        fdJ:
        if (mo_saml_is_sp_configured()) {
            goto lit;
        }
        $X4 = "\x50\154\x65\141\x73\x65\40\143\157\156\146\151\147\165\x72\145\x20\x74\x68\x65\40\x6d\x69\x6e\151\117\x72\141\x6e\x67\x65\40\123\x41\115\x4c\40\x50\x6c\x75\147\151\x6e\40\146\151\x72\163\164\56";
        goto DU6;
        lit:
        $wL = map_deep(wp_unslash($wL), "\x73\141\156\151\x74\x69\172\145\137\164\145\x78\x74\x5f\x66\x69\145\x6c\144");
        if (isset($_REQUEST["\162\145\144\151\162\x65\143\x74\x5f\x74\157"])) {
            goto d7E;
        }
        $V9 = urlencode(saml_get_current_page_url());
        goto DCF;
        d7E:
        $V9 = htmlspecialchars(SAMLSPUtilities::mo_saml_is_array($_REQUEST["\x72\x65\x64\x69\162\145\143\164\137\164\x6f"]));
        DCF:
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $Wc = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\x5f\163\141\x6d\x6c\x5f\151\144\160\x5f\163\x68\157\x72\x74\x63\157\x64\145\x5f\154\157\x67\x69\x6e\137\x74\145\170\x74", false, $CP);
        $Yd = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\155\154\137\151\144\145\156\x74\151\164\x79\x5f\160\162\x6f\x76\151\144\x65\162\163", true, $CP);
        $vi = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\x6d\154\x5f\x73\x73\157\137\142\165\164\x74\157\x6e\137\x69\144\x70", true);
        if ($Wc) {
            goto oZ_;
        }
        $Wc = "\x4c\157\x67\x69\x6e\40\x77\151\164\x68\40\x23\x23\x49\x44\120\43\x23";
        oZ_:
        $XE = '';
        $rm = '';
        if (empty($wL["\x69\144\x70"])) {
            goto xQm;
        }
        $XE = $wL["\151\x64\160"];
        $rm = !empty($Yd[$XE]["\151\x64\160\137\144\x69\x73\x70\x6c\x61\171\x5f\x6e\x61\155\x65"]) ? sanitize_text_field(wp_unslash($Yd[$XE]["\151\x64\160\137\144\151\163\x70\x6c\141\x79\x5f\x6e\141\155\145"])) : $XE;
        if (!empty($Yd[$XE])) {
            goto AU0;
        }
        $X4 = "\120\x6c\x65\x61\x73\145\x20\143\157\156\146\151\x67\x75\x72\145\40\164\x68\x65\40\155\151\156\x69\x4f\x72\x61\156\x67\145\x20\123\x41\115\114\x20\120\154\165\147\x69\x6e\40\167\x69\x74\x68\40\x3c\163\164\x72\x6f\x6e\x67\x3e" . esc_html($rm) . "\x3c\x2f\163\x74\162\157\x6e\147\76\x20\x66\x69\162\163\x74\x2e";
        return $X4;
        AU0:
        goto IS0;
        xQm:
        $XE = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\x6d\154\x5f\x64\x65\146\x61\x75\x6c\164\137\151\x64\x70", false, $CP);
        $rm = !empty($Yd[$XE]["\151\x64\x70\137\144\151\x73\160\x6c\141\171\x5f\156\x61\x6d\145"]) ? sanitize_text_field(wp_unslash($Yd[$XE]["\151\144\160\x5f\x64\x69\163\x70\x6c\141\171\137\156\x61\155\145"])) : $XE;
        IS0:
        $Wc = str_replace("\43\43\111\x44\x50\43\43", $rm, $Wc);
        $X4 = $Wc . "\x20\40";
        if ($qw == "\x4d\x4f\137\123\101\115\114\137\114\x4f\107\111\x4e") {
            goto agK;
        }
        if (!empty($vi[$XE]["\x75\163\x65\137\142\x75\164\x74\157\x6e\137\x61\163\137\163\150\157\x72\164\143\157\x64\145"])) {
            goto d3x;
        }
        $X4 = "\x3c\x61\40\163\164\171\x6c\145\40\x3d\40\x22\x74\x65\170\x74\55\144\x65\x63\157\x72\141\164\x69\x6f\156\40\x3a\x20\156\x6f\x6e\x65\73\x22\x68\x72\145\146\75\42" . esc_url(home_url()) . "\57\x3f\157\x70\164\151\157\x6e\x3d\163\141\155\x6c\x5f\165\x73\x65\x72\x5f\x6c\157\147\151\x6e\x26\x69\x64\x70\x3d" . esc_html($XE) . "\x26\x72\x65\144\151\162\x65\143\x74\137\x74\x6f\x3d" . $V9 . "\42\x3e" . $X4 . "\74\x2f\141\x3e";
        goto EzJ;
        d3x:
        $X4 = $this->mo_saml_get_sso_button_html($vi, $XE);
        EzJ:
        goto z80;
        agK:
        $h9 = get_site_option("\155\157\x5f\163\x61\x6d\x6c\x5f\x73\150\x6f\x72\164\143\x6f\144\x65\137\155\145\x73\x73\x61\x67\x65");
        $X4 = "\74\x73\164\x79\x6c\x65\x3e\56\151\x73\141\137\145\x72\x72\157\x72\x20\173\xd\12\x9\11\11\11\11\143\157\x6c\x6f\x72\x3a\x20\x23\104\70\60\x30\60\103\x3b\15\xa\11\x9\11\11\x9\142\x61\x63\153\x67\162\x6f\x75\x6e\x64\55\143\x6f\x6c\x6f\x72\72\x20\x23\106\x46\x44\62\x44\x32\73\15\xa\11\11\11\x9\x7d\15\12\x9\11\x9\x9\x2e\151\163\141\137\x65\x72\162\x6f\162\x20\x69\40\173\xd\xa\x9\11\11\x9\x9\155\x61\x72\147\x69\156\x3a\61\x30\160\x78\x20\62\x32\x70\x78\73\xd\12\x9\x9\11\11\11\146\157\x6e\164\55\x73\x69\172\145\72\62\x65\155\x3b\xd\xa\x9\11\11\11\x9\166\x65\162\x74\x69\x63\141\154\55\x61\x6c\151\147\x6e\x3a\155\x69\x64\x64\154\145\73\xd\12\11\x9\11\11\175\15\12\11\11\x9\11\56\x69\163\141\x5f\x65\x72\x72\157\162\40\173\15\12\x9\11\x9\x9\11\x6d\141\162\147\x69\x6e\x3a\x20\x35\x70\170\40\60\x70\x78\x3b\xd\12\11\x9\x9\11\x9\x70\x61\144\x64\151\x6e\147\x3a\x31\62\x70\x78\73\11\x9\15\12\11\11\11\11\175\xd\xa\11\11\x9\x9\x3c\x2f\163\x74\171\154\x65\76\15\12\x9\11\x9\x9\74\146\x6f\162\155\x20\141\143\164\151\x6f\x6e\x3d\x22\42\40\156\141\155\145\75\42\x73\141\x6d\154\137\162\x65\x71\165\x65\163\x74\x5f\x77\x69\164\x68\x5f\145\155\x61\151\154\x22\40\x6d\145\164\150\x6f\144\75\x22\x70\x6f\163\x74\x22\x3e";
        if (!$h9) {
            goto pa1;
        }
        $X4 .= "\74\x64\x69\x76\40\x63\154\x61\x73\163\75\42\x69\x73\141\x5f\145\x72\x72\157\x72\42\x3e" . esc_html($h9) . "\74\x2f\x64\151\166\76";
        pa1:
        $X4 .= "\74\x62\x72\76\74\x66\x6f\162\155\x20\141\143\164\x69\x6f\x6e\75\x22\42\x20\156\141\x6d\145\x3d\x22\163\x61\155\x6c\137\x72\145\x71\165\x65\163\164\x5f\x77\x69\164\150\137\x65\155\141\151\154\x22\x20\155\x65\x74\x68\157\144\x3d\x22\160\157\163\x74\x22\76\15\12\x9\11\11\x9\74\154\141\142\145\x6c\40\146\157\162\x3d\42\x75\x6e\141\x6d\x65\x22\76\x3c\142\x3e\x55\163\145\x72\156\x61\x6d\145\x2f\105\x6d\141\x69\154\74\57\142\76\x3c\x2f\154\141\x62\x65\x6c\x3e\xd\12\x9\x9\x9\11\74\151\x6e\x70\165\x74\40\164\171\x70\x65\75\42\x74\x65\170\164\x22\40\160\154\141\143\145\150\157\x6c\144\145\x72\x3d\x22\x45\156\164\145\x72\x20\x55\x73\145\x72\x6e\x61\x6d\145\57\x45\155\x61\151\x6c\x22\40\156\141\x6d\x65\x3d\x22\x75\x6e\x61\155\x65\137\x65\155\x61\x69\x6c\42\40\162\145\x71\165\151\x72\145\144\x3e\xd\xa\11\x9\11\x9\x3c\x69\156\160\x75\164\x20\164\x79\x70\x65\x3d\x22\150\x69\x64\x64\145\156\42\40\x6e\x61\155\145\75\42\x6f\x70\x74\x69\157\156\42\40\x76\x61\154\165\x65\x3d\x22\x73\x61\155\x6c\137\165\x73\x65\162\137\x6c\157\147\151\156\42\x2f\76\xd\xa\x9\x9\11\x9\x3c\151\156\160\x75\x74\40\x74\171\x70\145\x3d\42\150\x69\144\144\x65\x6e\x22\x20\156\141\x6d\145\x3d\x22\x6f\x70\x74\151\157\156\x6e\42\40\166\x61\x6c\165\145\x3d\x22\155\x6f\137\x73\x61\x6d\154\x5f\x72\145\161\x75\145\x73\164\x5f\167\x69\164\x68\x5f\145\x6d\141\x69\154\42\x2f\76\15\xa\x9\x9\x9\x9\x3c\151\x6e\x70\x75\164\40\164\x79\160\x65\75\x22\x68\x69\144\x64\145\x6e\x22\x20\156\141\155\145\75\42\151\x64\160\x22\x20\x76\141\x6c\165\145\x3d\x22" . esc_html($XE) . "\42\76\xd\xa\11\11\x9\11\74\142\165\x74\164\157\156\40\164\171\160\x65\75\x22\x73\x75\x62\x6d\151\x74\x22\40\143\154\x61\163\163\75\x22\142\165\164\x74\x6f\x6e\40\x62\x75\164\x74\157\156\55\160\x72\x69\x6d\141\162\171\40\142\165\x74\x74\157\156\55\x6c\141\162\147\x65\x22\x20\76\x4c\x6f\x67\x69\x6e\x3c\x2f\x62\x75\164\164\x6f\156\76\xd\12\x9\11\11\11\74\57\x66\157\x72\155\x3e";
        delete_site_option("\155\157\x5f\163\x61\155\154\x5f\x73\x68\157\162\x74\x63\157\144\145\137\x6d\145\163\163\141\x67\145");
        z80:
        DU6:
        return $X4;
    }
    function mo_saml_get_shortcode_for_logged_in_users()
    {
        $X4 = '';
        $current_user = wp_get_current_user();
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\x6d\154\137\151\x64\145\x6e\x74\151\x74\x79\x5f\160\162\x6f\166\x69\x64\x65\x72\x73", true, $CP);
        if (empty(get_user_meta($current_user->ID, "\155\157\x5f\x73\x61\155\x6c\137\x6c\157\147\x67\145\144\137\x69\x6e\137\167\x69\164\x68\x5f\151\144\x70", true))) {
            goto bEw;
        }
        $p5 = get_user_meta($current_user->ID, "\x6d\157\x5f\163\141\x6d\154\x5f\x6c\x6f\x67\x67\145\144\x5f\151\156\137\x77\151\164\x68\x5f\151\x64\160", true);
        $mK = !empty($rK[$p5]) ? $rK[$p5] : array();
        bEw:
        $Da = "\x48\x65\x6c\154\157\x2c";
        if (empty($mK["\x63\165\x73\164\x6f\155\x5f\147\x72\x65\145\164\x69\156\147\x5f\164\x65\170\x74"])) {
            goto ecr;
        }
        $Da = $mK["\143\165\x73\x74\157\155\137\x67\x72\x65\x65\164\151\156\x67\137\x74\145\x78\164"];
        ecr:
        $MO = '';
        if (empty($mK["\147\x72\145\x65\164\x69\x6e\x67\137\x6e\141\x6d\145"])) {
            goto L0G;
        }
        switch ($mK["\147\x72\145\145\x74\151\x6e\147\137\156\141\155\145"]) {
            case "\x55\x53\105\122\116\x41\x4d\x45":
                $MO = $current_user->user_login;
                goto QS9;
            case "\105\115\101\x49\114":
                $MO = $current_user->user_email;
                goto QS9;
            case "\x46\x4e\x41\115\x45":
                $MO = $current_user->user_firstname;
                goto QS9;
            case "\114\x4e\x41\115\105":
                $MO = $current_user->user_lastname;
                goto QS9;
            case "\106\116\x41\115\105\x5f\114\x4e\101\115\105":
                $MO = $current_user->user_firstname . "\40" . $current_user->user_lastname;
                goto QS9;
            case "\x4c\x4e\101\x4d\x45\137\106\x4e\x41\115\105":
                $MO = $current_user->user_lastname . "\40" . $current_user->user_firstname;
                goto QS9;
            default:
                $MO = $current_user->user_login;
        }
        kBN:
        QS9:
        L0G:
        if (!empty(trim($MO))) {
            goto OCt;
        }
        $MO = $current_user->user_login;
        OCt:
        $AO = $Da . "\x20" . $MO;
        $r_ = "\x4c\157\147\157\x75\164";
        if (empty($mK["\143\x75\163\164\157\x6d\x5f\154\157\147\157\165\164\x5f\164\145\x78\164"])) {
            goto iCm;
        }
        $r_ = $mK["\143\x75\163\164\x6f\x6d\x5f\x6c\157\147\157\x75\164\x5f\x74\145\x78\x74"];
        iCm:
        $X4 = $AO . "\x20\x7c\40\74\141\x20\x68\162\145\146\x3d\x22" . wp_logout_url(saml_get_current_page_url()) . "\42\x20\x74\x69\164\154\x65\75\42\154\157\147\157\x75\164\x22\x20\x3e" . $r_ . "\74\x2f\141\76\x3c\x2f\x6c\151\x3e";
        $Oz = saml_get_current_page_url();
        $s6 = new EnvironmentDao($CP);
        $s6->mo_save_environment_settings("\154\157\x67\157\x75\x74\137\x72\x65\x64\x69\162\x65\x63\x74\x5f\165\162\x6c", $Oz);
        return $X4;
    }
    function _handle_upload_metadata()
    {
        if (!(!empty($_FILES["\x6d\x65\164\x61\144\x61\164\141\137\x66\151\154\145"]) || !empty($_POST["\155\145\x74\141\x64\141\x74\141\137\165\x72\x6c"]))) {
            goto dFi;
        }
        if (!empty($_FILES["\x6d\x65\x74\141\x64\x61\x74\141\x5f\146\x69\x6c\x65"]["\164\155\160\137\x6e\141\155\145"])) {
            goto nIF;
        }
        if (mo_saml_is_extension_installed("\143\x75\x72\154")) {
            goto ZR9;
        }
        update_option("\155\157\137\163\141\x6d\x6c\137\x6d\145\163\163\141\x67\x65", "\120\110\120\x20\143\125\x52\114\40\x65\x78\x74\x65\x6e\x73\151\x6f\x6e\x20\x69\x73\x20\156\157\x74\x20\x69\156\x73\164\x61\154\154\145\144\40\157\162\40\144\151\163\x61\x62\154\x65\144\x2e\40\103\141\x6e\x6e\x6f\164\40\146\145\164\143\150\40\x6d\145\164\141\144\141\x74\x61\x20\x66\162\x6f\155\40\x55\x52\114\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        ZR9:
        $Oz = filter_var(htmlspecialchars($_POST["\x6d\145\x74\x61\144\141\x74\141\x5f\165\162\x6c"]), FILTER_SANITIZE_URL);
        $d4 = SAMLSPUtilities::mo_saml_wp_remote_call($Oz, array("\163\x73\x6c\166\x65\162\x69\x66\171" => false), true);
        if ($d4) {
            goto dwB;
        }
        update_option("\155\x6f\137\163\x61\x6d\154\137\x6d\x65\x73\163\x61\147\x65", "\x50\x6c\x65\141\163\x65\x20\x70\162\157\x76\x69\x64\x65\x20\x61\40\166\x61\x6c\151\x64\x20\x6d\x65\164\x61\x64\x61\164\x61\40\x55\x52\x4c\x2e");
        return;
        dwB:
        if (!is_null($d4)) {
            goto e4p;
        }
        $tH = null;
        goto UPv;
        e4p:
        $tH = $d4;
        UPv:
        goto NS4;
        nIF:
        $tH = @file_get_contents($_FILES["\155\145\164\141\x64\141\x74\141\137\146\x69\154\x65"]["\164\155\160\x5f\x6e\141\155\x65"]);
        NS4:
        $s6 = new EnvironmentDao();
        $WT = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\x61\155\154\x5f\x6d\145\164\141\144\x61\164\x61\x5f\x75\162\x6c\x5f\x66\157\x72\x5f\x73\171\x6e\143", true, EnvironmentHelper::getCurrentEnvironment());
        $BB = !empty($_POST["\x73\x61\x6d\154\137\x65\144\151\164\137\165\x70\154\157\x61\144\x5f\x6d\x65\164\141\144\141\x74\x61\x5f\x6e\x61\x6d\x65"]) ? sanitize_text_field(wp_unslash($_POST["\x73\141\x6d\154\x5f\145\144\151\x74\x5f\165\x70\x6c\157\141\x64\137\x6d\x65\164\141\144\x61\x74\x61\x5f\156\141\x6d\145"])) : SAMLSPUtilities::mo_saml_generate_idp_id();
        if (empty($BB)) {
            goto I10;
        }
        if (!empty($_POST["\x73\171\x6e\x63\137\x6d\145\164\141\x64\141\x74\141"])) {
            goto boZ;
        }
        if (!isset($WT[$BB])) {
            goto u7Q;
        }
        unset($WT[$BB]);
        u7Q:
        $s6->mo_save_environment_settings("\x73\141\155\154\137\x6d\145\164\x61\144\x61\x74\141\137\x75\162\x6c\x5f\x66\x6f\x72\137\163\171\x6e\x63", $WT);
        $s6->mo_save_environment_settings("\x73\x61\x6d\x6c\x5f\155\145\x74\141\144\x61\164\x61\x5f\165\162\154\x5f\x66\157\162\x5f\x73\x79\x6e\143\137\155\x75\154\x74\x69\x70\x6c\x65\137\151\x64\x70", '');
        $s6->mo_save_environment_settings("\163\141\x6d\x6c\137\155\145\x74\141\144\x61\x74\141\x5f\x73\x79\x6e\x63\x5f\151\x6e\x74\145\162\x76\x61\x6c", '');
        wp_unschedule_event(wp_next_scheduled("\x6d\145\x74\141\144\x61\164\141\137\163\171\156\x63\x5f\x63\x72\x6f\x6e\x5f\x61\143\x74\x69\157\156", array($BB)), "\155\145\164\141\144\141\164\141\137\x73\171\156\x63\137\x63\x72\x6f\156\137\141\x63\x74\x69\x6f\x6e", array($BB));
        goto op5;
        boZ:
        $EB = array();
        $EB["\x6d\145\164\x61\x64\141\164\x61\137\x75\162\x6c"] = esc_url_raw(filter_var($_POST["\x6d\x65\x74\x61\144\x61\164\141\137\165\162\x6c"], FILTER_SANITIZE_URL));
        $EB["\x73\171\156\x63\137\x69\x6e\x74\145\x72\x76\x61\x6c"] = trim(sanitize_text_field($_POST["\x73\171\156\143\x5f\x69\x6e\164\145\x72\x76\141\154"]));
        $EB["\163\x79\156\x63\137\x63\145\x72\164\151\x66\151\143\x61\x74\x65\137\155\145\x74\x61\x64\x61\x74\x61"] = isset($_POST["\163\171\x6e\143\x5f\x63\145\162\x74\x69\x66\151\x63\x61\164\145\137\155\145\164\x61\x64\x61\x74\141"]) ? sanitize_text_field($_POST["\163\x79\156\143\137\x63\x65\162\x74\x69\x66\x69\x63\x61\x74\x65\137\155\x65\x74\x61\144\x61\164\x61"]) : '';
        $WT[$BB] = $EB;
        $s6->mo_save_environment_settings("\x73\x61\x6d\x6c\137\155\x65\x74\x61\x64\141\164\141\x5f\x75\x72\x6c\137\x66\157\162\137\163\171\x6e\x63", $WT);
        if (!wp_next_scheduled("\x6d\145\164\141\x64\x61\x74\141\137\x73\x79\x6e\143\x5f\x63\x72\157\x6e\x5f\141\143\164\151\x6f\156", array($BB))) {
            goto dGv;
        }
        wp_unschedule_event(wp_next_scheduled("\x6d\x65\164\141\144\x61\164\141\x5f\x73\x79\156\143\x5f\x63\x72\157\156\x5f\x61\x63\164\x69\157\x6e", array($BB)), "\x6d\x65\164\x61\144\x61\x74\x61\137\x73\171\156\x63\137\x63\162\157\x6e\x5f\x61\143\x74\x69\157\x6e", array($BB));
        wp_schedule_event(time(), $EB["\x73\171\x6e\x63\137\151\156\164\145\x72\x76\141\154"], "\155\145\164\x61\144\141\164\x61\137\163\171\156\143\x5f\143\162\157\156\137\141\143\x74\x69\157\156", array($BB));
        goto Cn4;
        dGv:
        wp_schedule_event(time(), $EB["\163\x79\x6e\143\137\x69\156\x74\x65\162\x76\141\154"], "\x6d\145\x74\141\x64\x61\164\x61\x5f\163\x79\156\143\137\x63\162\x6f\x6e\x5f\141\x63\x74\151\157\156", array($BB));
        Cn4:
        op5:
        I10:
        $Xs = !empty($EB) && "\143\x68\145\x63\x6b\145\144" === $EB["\x73\171\x6e\143\x5f\x63\145\x72\164\x69\146\x69\x63\141\164\x65\137\155\145\x74\141\144\x61\x74\x61"] && "\154\157\147\151\x6e\137\167\151\144\x67\x65\x74\137\x73\x61\155\x6c\x5f\x6d\x65\x74\x61\x64\x61\164\141\137\163\171\156\143" === sanitize_text_field(wp_unslash($_POST["\x6f\160\x74\x69\157\x6e"])) ? true : false;
        $ke = "\154\157\147\x69\156\x5f\167\151\144\x67\x65\164\137\x73\x61\155\x6c\x5f\155\x65\164\x61\x64\141\164\141\137\x73\x79\156\x63" === sanitize_text_field(wp_unslash($_POST["\157\160\x74\151\157\x6e"])) ? true : false;
        $uM = Mo_Saml_Metadata_Import_Handler::mo_saml_get_object();
        $uM->mo_saml_upload_metadata($tH, $BB, $Xs, $ke);
        dFi:
    }
    function upload_metadata($tH, $BB = '', $VK = false, $q4 = false)
    {
        if (!empty($tH)) {
            goto Tki;
        }
        update_option("\155\157\x5f\163\141\155\x6c\137\155\145\163\x73\141\x67\x65", "\x50\x6c\x65\x61\163\145\x20\160\x72\x6f\x76\x69\x64\145\x20\x61\x20\x76\x61\x6c\151\x64\40\x6d\x65\x74\141\144\x61\164\141\40\146\151\154\145\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        goto Kfj;
        Tki:
        $nI = SAMLSPUtilities::mo_saml_safe_load_xml($tH, Mo_Saml_Error_Codes::$error_codes["\127\x50\123\x41\115\114\x45\122\122\60\x32\x36"], true);
        Kfj:
        if ($nI) {
            goto XGF;
        }
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        XGF:
        $EW = $nI->firstChild;
        if (!empty($EW)) {
            goto Ldt;
        }
        if (!empty($_FILES["\x6d\x65\164\x61\x64\141\164\141\x5f\146\151\154\x65"]["\164\155\160\x5f\x6e\141\155\x65"])) {
            goto pKA;
        }
        if (!empty($_POST["\155\145\164\141\144\141\164\x61\137\x75\x72\154"])) {
            goto COr;
        }
        if (!empty($UK)) {
            goto EXf;
        }
        update_option("\155\x6f\x5f\x73\x61\155\x6c\x5f\155\145\163\163\141\147\x65", "\x50\x6c\145\x61\x73\x65\x20\x70\x72\x6f\166\x69\144\145\40\141\40\x76\141\x6c\151\x64\40\155\x65\164\x61\x64\x61\164\141\x20\146\x69\x6c\145\40\157\162\40\141\x20\166\x61\x6c\x69\144\x20\125\x52\x4c\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        goto Qdb;
        Ldt:
        $NV = new IDPMetadataReader($nI);
        $rK = $NV->getIdentityProviders();
        if (empty($rK)) {
            goto eXo;
        }
        $sy = SAMLSPUtilities::mo_saml_upload_idp_metadata_validations($rK);
        if (!$sy) {
            goto UTg;
        }
        return;
        UTg:
        goto zIg;
        eXo:
        if (empty($_FILES["\155\145\164\x61\144\141\164\x61\x5f\x66\x69\154\145"]) && "\125\160\x6c\x6f\141\x64" === $sZ) {
            goto pO4;
        }
        update_option("\x6d\157\x5f\x73\x61\x6d\154\137\155\145\x73\163\x61\147\x65", "\x50\x6c\145\x61\163\x65\40\160\x72\x6f\166\x69\144\x65\x20\x61\40\x76\x61\x6c\x69\144\x20\155\145\164\141\144\141\x74\141\x20\x61\x6e\144\x20\x63\150\145\x63\153\x20\171\x6f\x75\x72\x20\111\104\120\40\143\157\156\x66\x69\147\x75\162\x61\164\151\x6f\156\40\x61\147\141\151\156\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        goto tjf;
        pO4:
        update_option("\155\157\x5f\x73\141\x6d\x6c\x5f\155\145\x73\163\x61\147\145", "\x50\154\x65\141\x73\x65\40\x70\x72\x6f\x76\151\x64\x65\x20\x61\40\x76\x61\x6c\x69\144\x20\155\145\164\141\x64\x61\x74\x61\x20\x66\x69\154\145\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        return;
        tjf:
        zIg:
        $VN = array();
        $CP = '';
        $D7 = true;
        if (!$q4) {
            goto Iiz;
        }
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $D7 = false;
        Iiz:
        $gu = EnvironmentHelper::getOptionForSelectedEnvironment("\163\x61\x6d\154\137\x69\144\x65\156\x74\151\x74\x79\x5f\160\162\157\166\x69\x64\x65\x72\x73", true, $CP);
        $p6 = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\137\x73\141\x6d\x6c\x5f\151\x64\x70\x5f\x6e\141\155\145\137\x69\x64\x5f\155\x61\160", true, $CP);
        foreach ($rK as $R2 => $XE) {
            $K3 = $XE->getIdpName();
            $sU = "\57\x5b\x5e\141\55\x7a\x41\x2d\132\x30\55\71\134\163\x5f\x5c\x2d\x40\x5d\x2f";
            $K3 = preg_replace($sU, "\137", $K3);
            if (!(empty($K3) || !preg_match("\43\x5e\50\x3f\x3d\56\52\x5b\x61\55\x7a\101\55\132\x30\55\71\x5d\51\x5b\141\55\172\101\55\132\60\x2d\71\x5c\x73\x5f\134\x2d\x40\135\x2b\44\43", $K3))) {
                goto bSt;
            }
            if (!empty($_POST["\x73\x61\155\x6c\137\151\144\x65\156\164\x69\164\171\x5f\x6d\x65\164\141\x64\x61\x74\141\137\160\x72\x6f\x76\x69\144\x65\162"])) {
                goto I8p;
            }
            if (!empty($gu[$BB])) {
                goto WnS;
            }
            update_option("\155\157\x5f\x73\141\x6d\154\x5f\x6d\x65\163\163\141\147\x65", "\x50\x6c\x65\x61\x73\x65\40\145\x6e\164\x65\162\x20\x61\x20\166\x61\154\x69\x64\40\111\144\x65\156\x74\x69\x74\x79\40\120\x72\157\x76\x69\x64\x65\162\40\116\x61\155\145\x2e");
            SAMLSPUtilities::mo_saml_show_error_message();
            return;
            goto kLA;
            I8p:
            $K3 = sanitize_text_field(wp_unslash($_POST["\x73\141\x6d\x6c\x5f\x69\x64\145\x6e\164\x69\x74\171\137\x6d\x65\x74\141\144\x61\164\141\137\160\162\x6f\x76\x69\x64\145\162"]));
            goto kLA;
            WnS:
            $K3 = !empty($gu[$BB]["\x69\x64\160\x5f\x64\151\x73\160\x6c\141\171\x5f\x6e\x61\x6d\x65"]) ? $gu[$BB]["\x69\144\x70\x5f\144\151\x73\x70\154\x61\171\x5f\156\141\x6d\x65"] : $BB;
            kLA:
            bSt:
            if ($q4) {
                goto DQV;
            }
            if (!(!empty($K3) && !preg_match("\x23\136\x28\x3f\75\56\x2a\133\141\x2d\x7a\101\55\x5a\x30\55\71\x5d\51\133\141\x2d\x7a\101\x2d\132\60\x2d\71\x5c\x73\x5f\x5c\x2d\x40\x5d\53\44\x23", $K3))) {
                goto i5p;
            }
            update_option("\155\157\x5f\163\x61\155\x6c\x5f\155\145\163\163\141\147\x65", "\x50\x6c\x65\x61\x73\x65\40\x6d\x61\164\143\150\x20\164\x68\145\x20\162\x65\x71\x75\x65\x73\164\x65\x64\40\x66\157\x72\155\x61\x74\40\146\157\x72\x20\x49\144\x65\156\x74\x69\164\171\x20\120\162\157\166\x69\144\145\x72\x20\116\141\155\145\56\40\123\x70\x65\x63\151\141\154\x20\143\150\x61\x72\141\143\164\x65\162\163\40\x61\162\145\40\x6e\157\x74\x20\x61\x6c\154\x6f\x77\x65\x64\40\145\x78\x63\x65\x70\164\40\165\x6e\144\x65\x72\x73\x63\x6f\162\x65\50\137\51\x2c\40\150\171\160\150\145\x6e\50\55\x29\40\141\156\x64\40\100\56");
            SAMLSPUtilities::mo_saml_show_error_message();
            return;
            i5p:
            if (!(!empty($gu[$BB]) && !empty($gu[$BB]["\x69\144\160\x5f\144\151\x73\160\154\141\171\x5f\156\141\x6d\145"]) && $K3 !== $gu[$BB]["\x69\x64\160\x5f\x64\x69\x73\x70\154\x61\x79\137\x6e\141\155\x65"])) {
                goto Evf;
            }
            $xK = $gu[$BB]["\151\x64\x70\137\x64\151\163\x70\154\141\171\137\x6e\141\x6d\x65"];
            unset($p6[$xK]);
            Evf:
            $fP = SAMLSPUtilities::mo_saml_check_idp_display_name($gu, $p6, $BB, $K3);
            if (!$fP) {
                goto Avl;
            }
            update_option("\155\x6f\137\163\x61\x6d\x6c\x5f\x6d\x65\x73\x73\141\x67\x65", "\x49\x64\145\x6e\164\x69\164\x79\x20\x50\x72\x6f\x76\151\144\145\x72\x20\167\x69\164\150\40\156\141\x6d\145\40\x3c\x65\155\x3e" . esc_html($K3) . "\74\57\145\155\x3e\40\x61\x6c\x72\145\x61\144\171\x20\145\x78\x69\x73\x74\163\x2e\x20\x54\162\171\40\141\156\x6f\164\150\x65\x72\x20\x49\144\145\x6e\x74\x69\164\171\x20\x50\162\x6f\x76\x69\144\145\162\40\156\x61\155\x65\56");
            SAMLSPUtilities::mo_saml_show_error_message();
            return;
            Avl:
            DQV:
            if (!$VK) {
                goto JjV;
            }
            if (!(!$q4 || $XE->getEntityID() === $gu[$BB]["\x69\x64\160\137\x65\156\x74\151\164\171\x5f\x69\x64"] && $q4)) {
                goto jXs;
            }
            $CF = $XE->getSigningCertificate();
            if (!is_array($CF)) {
                goto orE;
            }
            foreach ($CF as $R2 => $EB) {
                $CF[$R2] = SAMLSPUtilities::sanitize_certificate($EB);
                m0X:
            }
            sHu:
            orE:
            $VN = array("\x78\65\x30\x39\x5f\x63\145\x72\x74\x69\146\151\143\141\164\145" => $CF);
            jXs:
            goto V48;
            JjV:
            if (!(array_key_exists($BB, $gu) && !$q4)) {
                goto LOK;
            }
            SAMLSPUtilities::mo_saml_delete_admin_notice($gu[$BB]["\x73\163\x6f\x5f\x75\162\x6c"]);
            LOK:
            if (!(!$q4 || $XE->getEntityID() === $gu[$BB]["\151\144\x70\x5f\x65\156\x74\x69\164\x79\137\x69\x64"] && $q4)) {
                goto Y68;
            }
            $VN = $this->fetchMetadata($XE, $BB, $K3, $q4);
            Y68:
            V48:
            $s6 = new EnvironmentDao();
            if (!(0 === count($gu))) {
                goto dN0;
            }
            $s6->mo_save_environment_settings("\163\141\x6d\154\x5f\144\x65\x66\141\165\154\164\137\x69\144\160", $BB, $D7);
            dN0:
            if (empty($gu[$BB])) {
                goto Y4q;
            }
            $VN = SAMLSPUtilities::mo_saml_array_merge($gu[$BB], $VN);
            Y4q:
            $gu[$BB] = $VN;
            $p6[$K3] = $BB;
            if (empty($gu[''])) {
                goto oV7;
            }
            unset($gu['']);
            oV7:
            $s6->mo_save_environment_settings("\163\x61\155\154\137\x69\x64\145\156\164\151\x74\x79\x5f\x70\162\x6f\x76\x69\x64\x65\162\163", $gu, $D7);
            $s6->mo_save_environment_settings("\x6d\x6f\137\x73\141\x6d\x6c\137\151\x64\160\137\156\141\x6d\145\137\x69\144\137\155\141\x70", $p6, $D7);
            OJe:
        }
        WYM:
        update_option("\x6d\157\x5f\163\x61\155\154\x5f\x6d\x65\163\163\141\147\145", "\111\x64\145\x6e\x74\151\x74\x79\40\x50\162\157\x76\x69\144\x65\162\x20\x64\145\x74\x61\x69\x6c\x73\x20\x72\x65\x74\162\x69\145\166\x65\x64\x20\x73\165\143\143\x65\163\163\x66\x75\x6c\x6c\171\56");
        SAMLSPUtilities::mo_saml_show_success_message();
        goto Qdb;
        pKA:
        update_option("\155\157\137\163\141\155\154\x5f\x6d\x65\x73\163\141\147\145", "\x50\154\x65\x61\x73\x65\40\x70\162\x6f\166\151\x64\145\40\x61\x20\166\x61\154\x69\x64\x20\x6d\145\164\141\x64\x61\164\141\40\x66\x69\x6c\x65\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto Qdb;
        COr:
        update_option("\155\x6f\x5f\x73\x61\x6d\x6c\x5f\x6d\145\x73\x73\141\147\x65", "\x50\154\x65\141\x73\x65\x20\x70\162\x6f\x76\151\144\145\x20\141\40\x76\141\x6c\x69\x64\40\x6d\145\x74\141\144\x61\164\141\40\x55\x52\x4c\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto Qdb;
        EXf:
        update_option("\x6d\157\x5f\163\x61\155\x6c\137\155\145\163\163\141\147\145", "\x55\x6e\x61\x62\x6c\145\x20\x74\x6f\40\146\145\x74\143\x68\40\x4d\145\x74\141\144\x61\x74\x61\56\x20\x50\154\145\x61\x73\x65\x20\x63\150\145\x63\x6b\x20\171\157\x75\x72\x20\x49\x44\120\40\143\x6f\x6e\146\x69\x67\x75\162\141\x74\x69\157\x6e\x20\x61\147\x61\151\x6e\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        Qdb:
    }
    function fetchMetadata($XE, $RF, $K3, $q4 = false)
    {
        $oa = '';
        $Da = '';
        $fc = '';
        $Qz = '';
        $LH = '';
        $Er = '';
        $n_ = '';
        $vs = array("\143\x75\x73\x74\157\x6d\137\154\157\147\151\x6e\137\164\x65\x78\x74" => $oa, "\143\165\x73\164\x6f\155\x5f\147\x72\x65\145\164\x69\x6e\147\137\164\x65\x78\164" => $Da, "\147\162\x65\145\164\151\x6e\147\x5f\156\141\155\145" => $fc, "\143\x75\x73\164\x6f\x6d\137\154\157\x67\x6f\x75\164\137\x74\145\170\164" => $Qz);
        $nG = array("\163\x61\155\x6c\x5f\162\x65\161\165\x65\x73\164" => $LH, "\x73\x61\155\154\x5f\162\x65\x73\160\157\x6e\163\145" => $Er, "\164\145\163\x74\x5f\163\x74\141\164\x75\x73" => $n_);
        $Kp = $XE->getLoginBindingType();
        $tX = $XE->getLoginURL(mo_saml_binding_type($Kp));
        $uN = "\110\164\x74\160\x52\145\144\151\x72\145\x63\164";
        $XM = '';
        $Dc = '';
        if (!$XE->getLogoutDetails()) {
            goto GVZ;
        }
        $XM = $XE->getLogoutURL("\110\x54\x54\x50\55\122\x65\x64\151\162\145\143\x74");
        if (!empty($XM)) {
            goto aB0;
        }
        $XM = $XE->getLogoutURL("\110\124\124\x50\55\x50\117\123\x54");
        if (empty($XM)) {
            goto kzk;
        }
        $uN = "\110\x74\x74\160\120\157\x73\x74";
        kzk:
        aB0:
        GVZ:
        if (!$XE->getLogoutResponseDetails()) {
            goto KP_;
        }
        $Dc = $XE->getLogoutResponseURL("\110\x54\124\x50\x2d\122\x65\144\151\162\145\143\164");
        if (!empty($Dc)) {
            goto RFG;
        }
        $Dc = $XE->getLogoutResponseURL("\x48\124\124\x50\55\x50\117\123\x54");
        RFG:
        KP_:
        $nA = $XE->isRequestSigned() === "\x74\162\165\145" ? "\143\150\145\x63\153\x65\x64" : "\165\x6e\x63\x68\145\x63\x6b\x65\144";
        $uo = $XE->getEntityID();
        $CF = $XE->getSigningCertificate();
        if (!is_array($CF)) {
            goto nPa;
        }
        foreach ($CF as $R2 => $EB) {
            $CF[$R2] = SAMLSPUtilities::sanitize_certificate($EB);
            bfA:
        }
        QZI:
        nPa:
        $lF = $XE->getNameIdFormats();
        $nq = mo_options_enum_nameid_formats::UNSPECIFIED;
        if (empty($lF)) {
            goto uQq;
        }
        $HB = mo_options_enum_nameid_formats::getConstants();
        foreach ($lF as $R2 => $EB) {
            $rv = $lF[$R2];
            if (!in_array($rv, $HB, true)) {
                goto j2e;
            }
            $nq = $rv;
            goto vrF;
            j2e:
            wni:
        }
        vrF:
        uQq:
        $VN = array("\151\144\x70\137\x6e\141\x6d\x65" => $RF, "\x69\144\160\x5f\144\x69\x73\x70\154\x61\x79\137\x6e\x61\x6d\145" => $K3, "\163\x73\x6f\x5f\x62\151\x6e\x64\x69\156\147\x5f\x74\x79\160\145" => $Kp, "\163\x73\157\x5f\x75\x72\154" => $tX, "\163\154\157\137\x62\151\x6e\144\x69\156\x67\137\164\x79\x70\145" => $uN, "\x73\154\x6f\137\x75\x72\x6c" => $XM, "\x73\x6c\157\137\x72\x65\x73\160\x6f\x6e\x73\145\x5f\x75\x72\154" => $Dc, "\151\x64\x70\x5f\145\x6e\x74\x69\164\171\137\x69\144" => $uo, "\156\141\155\145\151\x64\x5f\x66\157\x72\x6d\x61\x74" => $nq, "\x78\x35\x30\71\x5f\x63\x65\x72\x74\151\x66\151\x63\141\164\145" => $CF, "\162\145\x73\160\x6f\x6e\163\145\x5f\163\x69\147\x6e\x65\144" => "\143\150\145\x63\153\x65\x64", "\141\163\x73\x65\162\x74\x69\x6f\x6e\137\163\x69\x67\x6e\145\144" => "\143\x68\145\143\x6b\145\144", "\x72\145\x71\165\145\163\164\137\163\x69\x67\156\145\x64" => $nA, "\155\x6f\x5f\x73\x61\155\x6c\137\x65\x6e\x63\x6f\x64\151\156\x67\x5f\x65\x6e\x61\x62\154\x65\x64" => "\x63\150\145\x63\x6b\145\x64", "\x65\x6e\x61\142\154\x65\x5f\x69\144\x70" => true);
        if ($q4) {
            goto qaf;
        }
        SAMLSPUtilities::mo_saml_update_selected_idp(array($VN));
        qaf:
        $VN = SAMLSPUtilities::mo_saml_array_merge($VN, $vs);
        $VN = SAMLSPUtilities::mo_saml_array_merge($VN, $nG);
        return $VN;
    }
    function checkPasswordPattern($lq)
    {
        $sU = "\57\136\133\50\x5c\167\x29\52\x28\134\x21\x5c\100\x5c\43\134\44\134\x25\x5c\136\x5c\x26\x5c\x2a\134\x2e\134\x2d\134\137\x29\x2a\x5d\x2b\44\x2f";
        return !preg_match($sU, $lq);
    }
    function mo_saml_parse_expiry_date($kf)
    {
        $nE = new DateTime($kf);
        $ZW = $nE->getTimestamp();
        return date("\106\40\x6a\x2c\40\131", $ZW);
    }
    function is_license_expired($vE)
    {
        $nE = new DateTime($vE);
        $M7 = new DateTime();
        if ($M7 > $nE) {
            goto hAt;
        }
        return false;
        goto uFc;
        hAt:
        return true;
        uFc:
    }
    function mo_saml_handle_redirection_based_on_domain_mapping($Io, $V9 = '')
    {
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\155\154\x5f\x69\x64\x65\x6e\164\x69\164\x79\x5f\x70\x72\157\166\x69\144\x65\x72\163", true, $CP);
        $gC = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\x61\x6d\154\x5f\x69\144\x70\x5f\x64\x6f\155\141\151\156\x5f\x6d\x61\160\x70\151\156\x67", true, $CP);
        if (!filter_var($Io, FILTER_VALIDATE_EMAIL)) {
            goto fbm;
        }
        $kH = strtolower(trim(explode("\100", $Io, 2)[1]));
        $oe = false;
        if (empty($gC)) {
            goto LA9;
        }
        foreach ($gC as $R2 => $EB) {
            $EB = str_replace("\x20", '', $EB);
            $GH = array_map("\164\x72\x69\x6d", explode("\73", $EB));
            $GH = array_map("\x73\x74\162\x74\x6f\x6c\157\x77\145\162", $GH);
            if (!(SAMLSPUtilities::mo_saml_in_array($kH, $GH) && !empty($rK[$R2]["\145\x6e\x61\142\x6c\x65\137\151\x64\x70"]))) {
                goto Kwx;
            }
            $Sf = $rK[$R2];
            $oe = true;
            $F6 = Mo_Saml_Redirection_Sso_Handler::mo_saml_get_object();
            $F6->mo_saml_redirect_sso_for_authentication($Sf, $V9);
            return array("\x73\x74\x61\164\x75\x73" => "\x72\x65\x64\x69\162\145\x63\164", "\155\x65\x73\x73\141\x67\x65" => "\122\x65\144\x69\162\x65\143\164\x69\156\x67\x20\x74\x6f\x20\x49\104\120\x2e\x2e\x2e");
            Kwx:
            wJQ:
        }
        Arh:
        LA9:
        if ($oe) {
            goto pYK;
        }
        if (!EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\137\x73\x61\155\x6c\137\x66\x61\154\154\142\141\x63\x6b\137\164\x6f\x5f\144\x65\146\141\165\154\x74", false, $CP)) {
            goto nGv;
        }
        $XR = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\155\154\137\x64\145\146\141\165\x6c\x74\x5f\151\x64\x70", false, $CP);
        if ($XR) {
            goto YVE;
        }
        return array("\x73\x74\x61\x74\x75\163" => "\145\162\162\x6f\162", "\155\145\163\163\x61\147\x65" => "\116\x6f\40\x64\x65\146\141\x75\154\164\x20\x49\x44\120\x20\x63\157\x6e\146\151\x67\x75\162\145\144\x2e");
        goto XTv;
        YVE:
        $F6 = Mo_Saml_Redirection_Sso_Handler::mo_saml_get_object();
        $F6->mo_saml_redirect_sso_for_authentication($rK[$XR], $V9);
        return array("\x73\x74\x61\x74\x75\x73" => "\162\x65\x64\x69\x72\x65\x63\164", "\155\145\x73\x73\x61\x67\x65" => "\x52\x65\144\x69\162\145\x63\164\x69\156\x67\40\164\157\x20\144\145\146\x61\x75\x6c\164\40\111\x44\x50\56\x2e\56");
        XTv:
        nGv:
        return array("\163\x74\x61\x74\165\163" => "\x77\160\137\x6c\x6f\147\151\156", "\x6d\145\x73\x73\x61\x67\145" => "\104\x6f\x6d\x61\151\156\x20\156\157\x74\x20\x66\x6f\x75\x6e\144\x2e\x20\x50\x6c\145\141\163\145\x20\x6c\x6f\x67\x69\x6e\x20\165\163\151\156\x67\40\127\x6f\162\x64\x50\162\x65\x73\x73\x20\x63\x72\145\144\x65\x6e\x74\151\x61\x6c\x73\x2e");
        pYK:
        fbm:
        return array("\x73\x74\141\x74\x75\x73" => "\145\x72\x72\157\162", "\155\145\163\163\141\147\145" => "\x49\x6e\x76\141\154\151\x64\x20\145\155\x61\x69\154\x20\146\157\162\155\141\x74\x2e");
    }
}
function mo_saml_binding_type($Yc)
{
    if ($Yc == "\110\164\x74\160\122\x65\x64\x69\x72\x65\x63\x74") {
        goto gtk;
    }
    return "\110\124\x54\120\55\x50\x4f\123\124";
    goto FmS;
    gtk:
    return "\x48\124\124\x50\55\122\x65\144\151\162\x65\x63\164";
    FmS:
}
Mo_SAML_Plugin::mo_saml_get_object();
