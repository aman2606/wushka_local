<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



require_once MO_SAML_PLUGIN_DIR . MO_SAML_OPTIONS_ENUM;
require_once Mo_Saml_Plugin_Files::MO_SAML_DOM_DISABLED_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_CURL_DISABLED_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_OPENSSL_DISABLED_EXCEPTION;
add_action("\x61\x64\x6d\151\x6e\x5f\x69\x6e\151\164", "\x6d\x6f\x5f\x73\141\155\x6c\137\x75\x70\x64\141\x74\145");
use MOSAML\LicenseLibrary\Mo_License_Service;
class mo_saml_update_framework
{
    private $current_version;
    private $update_path;
    private $plugin_slug;
    private $slug;
    private $plugin_file;
    private $new_version_changelog;
    public function __construct($Tw, $wX = "\x2f", $V1 = "\57")
    {
        $this->current_version = $Tw;
        $this->update_path = $wX;
        $this->plugin_slug = $V1;
        list($bS, $O0) = explode("\57", $V1);
        $this->slug = $bS;
        $this->plugin_file = $O0;
        add_filter("\160\162\x65\137\x73\145\x74\137\163\x69\x74\x65\137\164\x72\x61\x6e\163\151\x65\156\164\137\165\x70\x64\x61\164\x65\x5f\x70\154\x75\147\151\156\163", array(&$this, "\155\157\x5f\163\141\155\154\x5f\143\x68\145\x63\153\x5f\165\x70\144\141\164\x65"));
        add_filter("\160\154\165\147\151\x6e\163\137\141\160\151", array(&$this, "\x6d\157\137\x73\141\x6d\x6c\137\143\x68\145\x63\153\137\x69\x6e\146\x6f"), 10, 3);
    }
    public function mo_saml_check_update($pB)
    {
        try {
            SAMLSPUtilities::mo_saml_check_is_extension_installed();
        } catch (Mo_SAML_DOM_Extension_Disabled_Exception $k5) {
            return $pB;
        } catch (Mo_SAML_CURL_Extension_Disabled_Exception $k5) {
            return $pB;
        } catch (Mo_SAML_OpenSSL_Extension_Disabled_Exception $k5) {
            return $pB;
        }
        if (!empty($pB->checked)) {
            goto hz0;
        }
        return $pB;
        hz0:
        $mZ = $this->getRemote();
        if (isset($mZ["\x73\x74\x61\x74\x75\x73"]) && $mZ["\x73\x74\x61\164\x75\163"] == "\123\x55\x43\x43\105\123\x53") {
            goto oRg;
        }
        if (isset($mZ["\x73\x74\x61\x74\165\x73"]) && $mZ["\x73\164\x61\164\165\163"] == "\104\x45\116\x49\105\104") {
            goto eVZ;
        }
        goto DYo;
        oRg:
        if (!version_compare($this->current_version, $mZ["\156\145\x77\126\x65\162\163\151\157\156"], "\74")) {
            goto CAp;
        }
        ini_set("\155\141\x78\x5f\145\170\145\143\x75\x74\x69\157\x6e\x5f\x74\x69\155\145", 600);
        ini_set("\155\145\x6d\157\x72\x79\137\x6c\151\x6d\151\x74", "\61\60\62\64\115");
        $py = plugin_dir_path(__FILE__);
        $py = rtrim($py, "\x2f");
        $py = rtrim($py, "\134");
        $pS = $py . "\55\x70\162\145\155\151\x75\x6d\55\142\141\143\153\x75\x70\55" . $this->current_version . "\56\x7a\151\160";
        $this->mo_saml_create_backup_dir();
        $FP = $this->getAuthToken();
        $Cj = round(microtime(true) * 1000);
        $Cj = number_format($Cj, 0, '', '');
        $F7 = new stdClass();
        $F7->slug = $this->slug;
        $F7->new_version = $mZ["\x6e\145\167\126\145\162\x73\x69\x6f\x6e"];
        $F7->url = "\150\x74\164\160\163\x3a\x2f\x2f\x6d\151\x6e\151\157\x72\141\x6e\147\x65\56\143\x6f\155";
        $F7->plugin = $this->plugin_slug;
        $F7->package = mo_options_plugin_constants::HOSTNAME . "\57\155\x6f\x61\x73\57\x70\154\x75\147\x69\156\57\144\157\167\156\x6c\157\x61\x64\x2d\165\x70\144\141\164\145\77\160\154\x75\x67\x69\156\x53\154\165\x67\x3d" . $this->plugin_slug . "\x26\x6c\151\x63\x65\x6e\x73\145\120\154\141\x6e\x4e\141\x6d\x65\75" . mo_options_plugin_constants::LICENSE_PLAN_NAME . "\46\143\x75\x73\164\x6f\x6d\x65\x72\111\x64\75" . get_option("\155\x6f\137\163\141\x6d\x6c\x5f\x61\x64\x6d\x69\156\137\143\x75\x73\164\x6f\x6d\x65\x72\x5f\x6b\145\171") . "\46\154\x69\143\x65\156\163\145\124\x79\x70\x65\x3d" . mo_options_plugin_constants::LICENSE_TYPE . "\46\141\x75\x74\150\x54\x6f\153\145\156\x3d" . $FP . "\46\x6f\164\160\124\157\153\145\156\x3d" . $Cj;
        $F7->tested = $mZ["\x63\x6d\x73\x43\x6f\x6d\160\141\164\x69\142\x69\154\x69\164\171\x56\145\162\163\151\x6f\156"];
        $F7->icons = array("\x31\170" => $mZ["\x69\143\x6f\x6e"]);
        $F7->new_version_changelog = $mZ["\143\x68\141\x6e\x67\x65\x6c\x6f\x67"];
        $F7->status_code = $mZ["\163\x74\141\x74\x75\163"];
        Mo_License_Service::update_license_expiry($mZ["\x6c\151\x63\145\x6e\145\105\170\x70\x69\x72\171\x44\141\164\x65"]);
        $pB->response[$this->plugin_slug] = $F7;
        set_transient("\165\160\144\x61\x74\145\x5f\x70\154\x75\x67\151\156\x73", $pB);
        return $pB;
        CAp:
        goto DYo;
        eVZ:
        if (!version_compare($this->current_version, $mZ["\156\145\167\126\145\x72\x73\151\x6f\x6e"], "\74")) {
            goto GVu;
        }
        $F7 = new stdClass();
        $F7->slug = $this->slug;
        $F7->new_version = $mZ["\x6e\145\167\x56\145\x72\x73\x69\x6f\x6e"];
        $F7->url = "\x68\x74\164\x70\163\72\x2f\x2f\x6d\151\x6e\x69\157\x72\x61\x6e\147\145\56\x63\x6f\x6d";
        $F7->plugin = $this->plugin_slug;
        $F7->tested = $mZ["\143\x6d\163\x43\157\x6d\160\x61\x74\151\142\151\154\x69\164\171\x56\145\162\163\x69\157\156"];
        $F7->icons = array("\61\170" => $mZ["\x69\x63\x6f\156"]);
        $F7->status_code = $mZ["\x73\x74\x61\x74\165\163"];
        $F7->license_information = $mZ["\x6c\151\143\x65\156\x73\x65\x49\x6e\146\x6f\x72\155\141\164\x69\x6f\x6e"];
        Mo_License_Service::update_license_expiry($mZ["\154\x69\143\145\156\145\105\170\160\151\x72\x79\x44\141\164\x65"]);
        $pB->response[$this->plugin_slug] = $F7;
        set_transient("\165\x70\144\141\x74\145\x5f\x70\154\x75\x67\151\x6e\x73", $pB);
        return $pB;
        GVu:
        DYo:
        return $pB;
    }
    public function mo_saml_check_info($F7, $sZ, $GK)
    {
        if (!(($sZ == "\161\165\x65\162\x79\x5f\160\154\x75\x67\151\156\x73" || $sZ == "\160\x6c\165\x67\151\156\x5f\151\x6e\146\x6f\162\x6d\x61\164\151\157\x6e") && !empty($GK->slug) && ($GK->slug === $this->slug || $GK->slug === $this->plugin_file))) {
            goto hDU;
        }
        $tA = $this->getRemote();
        remove_filter("\160\154\x75\147\x69\156\x73\137\141\x70\x69", array($this, "\155\157\137\x73\141\155\x6c\137\143\150\x65\x63\x6b\x5f\x69\156\146\157"));
        $DU = plugins_api("\x70\154\x75\x67\x69\x6e\x5f\x69\156\146\157\x72\155\x61\164\151\x6f\x6e", array("\163\x6c\165\x67" => $this->slug, "\x66\151\x65\154\144\163" => array("\x61\x63\x74\151\166\145\137\x69\156\163\x74\x61\x6c\154\x73" => true, "\x6e\165\155\137\x72\x61\164\151\x6e\147\x73" => true, "\162\141\164\x69\156\x67" => true, "\162\x61\x74\x69\156\x67\x73" => true, "\162\x65\x76\x69\145\167\163" => true)));
        $XK = false;
        $RT = false;
        $Pu = false;
        $WS = false;
        $q2 = '';
        $vM = '';
        if (is_wp_error($DU)) {
            goto fDs;
        }
        $XK = $DU->active_installs;
        $RT = $DU->rating;
        $Pu = $DU->ratings;
        $WS = $DU->num_ratings;
        $q2 = $DU->sections["\144\145\x73\143\162\151\160\x74\x69\x6f\156"];
        $vM = $DU->sections["\x72\145\166\x69\x65\x77\163"];
        fDs:
        add_filter("\x70\x6c\x75\147\x69\156\x73\x5f\141\x70\151", array($this, "\x6d\x6f\x5f\163\x61\155\x6c\137\143\x68\145\143\x6b\137\x69\x6e\146\157"), 10, 3);
        if (isset($tA["\163\x74\141\164\165\x73"]) && $tA["\163\x74\x61\x74\165\x73"] == "\x53\125\x43\103\x45\123\123") {
            goto F6t;
        }
        if (isset($tA["\x73\164\x61\x74\x75\x73"]) && $tA["\x73\x74\x61\164\x75\x73"] == "\x44\x45\x4e\x49\x45\x44") {
            goto myv;
        }
        goto LEm;
        F6t:
        $LO = false;
        if (!version_compare($this->current_version, $tA["\x6e\145\x77\x56\145\x72\x73\151\157\156"], "\74\x3d")) {
            goto LP0;
        }
        $Hs = new stdClass();
        $Hs->slug = $this->slug;
        $Hs->name = $tA["\160\154\x75\147\151\x6e\116\x61\x6d\145"];
        $Hs->plugin = $this->plugin_slug;
        $Hs->version = $tA["\156\x65\167\x56\x65\162\x73\x69\157\156"];
        $Hs->new_version = $tA["\x6e\145\167\126\145\x72\x73\x69\157\156"];
        $Hs->tested = $tA["\143\155\163\103\157\x6d\x70\141\164\151\x62\x69\154\151\x74\x79\126\145\162\163\151\x6f\156"];
        $Hs->requires = $tA["\143\155\163\115\151\x6e\x56\x65\x72\163\x69\x6f\156"];
        $Hs->requires_php = $tA["\x70\150\x70\x4d\x69\x6e\x56\x65\x72\163\151\x6f\x6e"];
        $Hs->compatibility = array($tA["\143\x6d\163\103\x6f\155\x70\x61\164\151\x62\151\154\x69\x74\x79\126\145\162\x73\x69\157\156"]);
        $Hs->url = $tA["\x63\x6d\x73\x50\154\165\147\x69\156\x55\162\154"];
        $Hs->author = $tA["\160\x6c\165\147\x69\x6e\x41\x75\164\150\157\x72"];
        $Hs->author_profile = $tA["\x70\154\165\147\x69\x6e\x41\x75\164\150\157\162\120\162\x6f\x66\151\x6c\145"];
        $Hs->last_updated = $tA["\154\141\163\164\x55\160\x64\x61\164\145\144"];
        $Hs->banners = array("\154\x6f\x77" => $tA["\142\141\x6e\156\145\x72"]);
        $Hs->icons = array("\61\170" => $tA["\151\x63\157\x6e"]);
        $Hs->sections = array("\143\150\141\156\147\145\154\157\147" => $tA["\x63\150\141\156\147\x65\154\157\147"], "\154\151\143\145\156\163\x65\137\151\x6e\x66\x6f\162\x6d\141\164\x69\x6f\x6e" => _x($tA["\154\x69\x63\x65\x6e\x73\x65\x49\156\146\157\x72\155\141\x74\x69\157\156"], "\120\x6c\x75\x67\x69\156\40\x69\156\x73\164\x61\x6c\x6c\145\162\x20\x73\145\x63\164\x69\157\x6e\40\164\x69\x74\154\145"), "\144\x65\x73\x63\162\x69\x70\x74\x69\x6f\156" => $q2, "\x52\145\166\151\145\167\x73" => $vM);
        $FP = $this->getAuthToken();
        $Cj = round(microtime(true) * 1000);
        $Cj = number_format($Cj, 0, '', '');
        $Hs->download_link = mo_options_plugin_constants::HOSTNAME . "\x2f\x6d\x6f\141\x73\57\x70\x6c\165\x67\151\x6e\x2f\x64\157\x77\x6e\154\157\141\x64\55\x75\x70\x64\141\x74\x65\77\x70\x6c\165\147\x69\x6e\123\x6c\x75\x67\75" . $this->plugin_slug . "\x26\154\x69\143\x65\x6e\163\x65\x50\154\141\x6e\116\x61\155\x65\75" . mo_options_plugin_constants::LICENSE_PLAN_NAME . "\x26\x63\165\163\x74\157\155\145\162\111\x64\x3d" . get_option("\x6d\157\x5f\x73\x61\155\154\x5f\141\144\155\x69\156\x5f\x63\165\163\x74\157\155\145\x72\137\x6b\145\171") . "\x26\x6c\x69\x63\145\156\x73\x65\x54\x79\160\x65\x3d" . mo_options_plugin_constants::LICENSE_TYPE . "\46\141\x75\164\150\124\x6f\153\x65\156\x3d" . $FP . "\46\x6f\164\x70\x54\x6f\x6b\145\156\75" . $Cj;
        $Hs->package = $Hs->download_link;
        $Hs->external = '';
        $Hs->homepage = $tA["\x68\157\x6d\x65\160\141\x67\145"];
        $Hs->reviews = true;
        $Hs->active_installs = $XK;
        $Hs->rating = $RT;
        $Hs->ratings = $Pu;
        $Hs->num_ratings = $WS;
        Mo_License_Service::update_license_expiry($tA["\x6c\x69\x63\x65\156\x65\x45\x78\160\151\162\x79\104\141\164\145"]);
        return $Hs;
        LP0:
        goto LEm;
        myv:
        if (!version_compare($this->current_version, $tA["\156\145\167\x56\145\x72\163\151\x6f\x6e"], "\74")) {
            goto YsK;
        }
        $Hs = new stdClass();
        $Hs->slug = $this->slug;
        $Hs->plugin = $this->plugin_slug;
        $Hs->name = $tA["\160\154\x75\x67\x69\156\116\141\x6d\x65"];
        $Hs->version = $tA["\x6e\145\x77\x56\x65\x72\x73\151\x6f\x6e"];
        $Hs->new_version = $tA["\x6e\145\167\126\x65\162\163\151\x6f\x6e"];
        $Hs->tested = $tA["\x63\x6d\163\103\x6f\155\160\x61\164\151\x62\x69\154\151\164\x79\x56\x65\162\163\151\157\x6e"];
        $Hs->requires = $tA["\x63\155\x73\x4d\151\x6e\x56\x65\162\163\x69\x6f\156"];
        $Hs->requires_php = $tA["\x70\x68\160\x4d\151\156\x56\145\x72\x73\151\157\x6e"];
        $Hs->compatibility = array($tA["\x63\155\163\103\x6f\155\x70\141\x74\151\142\x69\x6c\x69\164\x79\x56\x65\x72\x73\x69\x6f\x6e"]);
        $Hs->url = $tA["\x63\x6d\163\x50\154\165\x67\x69\x6e\125\162\x6c"];
        $Hs->author = $tA["\160\154\x75\x67\151\x6e\x41\165\164\x68\157\162"];
        $Hs->author_profile = $tA["\160\154\165\147\x69\x6e\101\165\x74\x68\157\162\120\162\x6f\x66\x69\154\x65"];
        $Hs->last_updated = $tA["\154\141\163\x74\x55\160\144\x61\x74\145\x64"];
        $Hs->banners = array("\x6c\157\167" => $tA["\142\x61\x6e\x6e\145\162"]);
        $Hs->icons = array("\61\x78" => $tA["\151\143\x6f\x6e"]);
        $Hs->sections = array("\x63\150\141\x6e\147\x65\x6c\x6f\x67" => $tA["\x63\150\x61\156\x67\145\154\x6f\147"], "\154\x69\143\x65\156\163\x65\137\x69\156\146\157\162\x6d\141\x74\x69\157\156" => _x($tA["\154\151\x63\x65\156\163\145\111\x6e\x66\x6f\162\x6d\x61\164\x69\x6f\x6e"], "\x50\154\x75\147\x69\x6e\40\x69\x6e\163\164\x61\x6c\154\145\162\x20\x73\x65\143\x74\151\157\x6e\x20\x74\x69\x74\154\145"), "\144\x65\x73\143\162\151\160\x74\151\x6f\x6e" => $q2, "\x52\145\x76\151\145\x77\163" => $vM);
        $Hs->external = '';
        $Hs->homepage = $tA["\150\157\x6d\145\x70\141\147\145"];
        $Hs->reviews = true;
        $Hs->active_installs = $XK;
        $Hs->rating = $RT;
        $Hs->ratings = $Pu;
        $Hs->num_ratings = $WS;
        Mo_License_Service::update_license_expiry($tA["\154\151\x63\145\156\145\x45\170\160\151\162\x79\x44\x61\164\145"]);
        return $Hs;
        YsK:
        LEm:
        hDU:
        return $F7;
    }
    public function getRemote()
    {
        $oP = get_option("\x6d\x6f\137\163\141\155\154\x5f\141\144\155\151\156\x5f\143\x75\163\164\157\155\145\x72\x5f\153\x65\x79");
        $oK = get_option("\155\x6f\x5f\163\141\x6d\154\x5f\x61\144\x6d\x69\x6e\x5f\141\160\151\x5f\x6b\x65\x79");
        $Cj = round(microtime(true) * 1000);
        $Sc = $oP . number_format($Cj, 0, '', '') . $oK;
        $FP = hash("\x73\x68\141\x35\61\62", $Sc);
        $Cj = number_format($Cj, 0, '', '');
        $Te = array("\160\x6c\x75\x67\x69\156\x53\154\165\x67" => $this->plugin_slug, "\x6c\151\143\145\156\163\x65\120\154\x61\x6e\x4e\141\155\x65" => mo_options_plugin_constants::LICENSE_PLAN_NAME, "\x63\x75\163\x74\157\x6d\145\162\x49\x64" => $oP, "\x6c\x69\x63\x65\x6e\x73\x65\124\171\160\145" => mo_options_plugin_constants::LICENSE_TYPE);
        $e_ = array("\150\x65\x61\x64\145\x72\x73" => array("\x43\x6f\x6e\x74\145\156\164\x2d\x54\x79\160\145" => "\141\x70\160\154\x69\x63\141\x74\151\x6f\156\x2f\152\163\x6f\x6e\73\40\143\150\x61\162\163\145\164\75\165\x74\x66\x2d\x38", "\x43\x75\163\164\157\x6d\145\162\55\113\x65\x79" => $oP, "\x54\x69\155\x65\x73\x74\141\155\x70" => $Cj, "\101\x75\x74\x68\157\x72\x69\x7a\141\164\x69\157\x6e" => $FP), "\x62\x6f\144\171" => json_encode($Te), "\155\x65\x74\x68\157\x64" => "\x50\x4f\x53\x54", "\x64\141\164\x61\x5f\x66\157\x72\x6d\x61\x74" => "\x62\x6f\144\x79", "\x73\x73\x6c\166\x65\x72\x69\146\171" => false);
        $d4 = wp_remote_post($this->update_path, $e_);
        if (!(!is_wp_error($d4) || wp_remote_retrieve_response_code($d4) === 200)) {
            goto n9N;
        }
        $Tz = json_decode($d4["\x62\157\x64\x79"], true);
        return $Tz;
        n9N:
        return false;
    }
    private function getAuthToken()
    {
        $oP = get_option("\x6d\157\x5f\x73\141\155\x6c\x5f\x61\144\155\x69\x6e\137\x63\x75\163\164\x6f\x6d\x65\x72\x5f\x6b\x65\x79");
        $oK = get_option("\x6d\x6f\137\163\141\155\154\137\141\x64\x6d\x69\x6e\137\141\160\151\137\x6b\145\x79");
        $Cj = round(microtime(true) * 1000);
        $Sc = $oP . number_format($Cj, 0, '', '') . $oK;
        $FP = hash("\x73\x68\x61\x35\61\x32", $Sc);
        return $FP;
    }
    function zipData($fy, $xF)
    {
        if (!(extension_loaded("\172\151\x70") && file_exists($fy) && count(glob($fy . DIRECTORY_SEPARATOR . "\52")) !== 0)) {
            goto uOb;
        }
        $dB = new ZipArchive();
        if (!$dB->open($xF, ZIPARCHIVE::CREATE)) {
            goto w_7;
        }
        $fy = realpath($fy);
        if (is_dir($fy) === true) {
            goto RoD;
        }
        if (is_file($fy)) {
            goto Sdo;
        }
        goto kMh;
        RoD:
        $ky = new RecursiveDirectoryIterator($fy);
        $ky->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
        $ar = new RecursiveIteratorIterator($ky, RecursiveIteratorIterator::SELF_FIRST);
        foreach ($ar as $tH) {
            $tH = realpath($tH);
            if (is_dir($tH) === true) {
                goto yS1;
            }
            if (is_file($tH) === true) {
                goto FDi;
            }
            goto j2i;
            yS1:
            $dB->addEmptyDir(str_replace($fy . DIRECTORY_SEPARATOR, '', $tH . DIRECTORY_SEPARATOR));
            goto j2i;
            FDi:
            $dB->addFromString(str_replace($fy . DIRECTORY_SEPARATOR, '', $tH), file_get_contents($tH));
            j2i:
            lKx:
        }
        ntX:
        goto kMh;
        Sdo:
        $dB->addFromString(basename($fy), file_get_contents($fy));
        kMh:
        w_7:
        return $dB->close();
        uOb:
        return false;
    }
    function mo_saml_plugin_update_message($t_, $d4)
    {
        if (!empty($t_["\x73\x74\141\164\x75\x73\137\143\x6f\x64\x65"])) {
            goto Gx2;
        }
        return;
        Gx2:
        if ($t_["\163\x74\x61\164\x75\x73\x5f\143\x6f\x64\x65"] == "\x53\125\103\103\x45\123\x53") {
            goto lrA;
        }
        if ($t_["\x73\164\141\x74\x75\x73\137\x63\157\x64\x65"] == "\104\105\x4e\111\x45\104") {
            goto Wa4;
        }
        goto haa;
        lrA:
        $Dg = wp_upload_dir();
        $GS = $Dg["\142\x61\163\x65\144\151\162"];
        $Dg = rtrim($GS, "\57");
        $py = $Dg . DIRECTORY_SEPARATOR . "\x62\141\x63\153\165\x70";
        $py = str_replace("\x2f", "\134", $py);
        $pS = "\155\151\156\x69\157\x72\x61\156\147\145\x2d\x73\x61\x6d\154\55\x32\x30\x2d\x73\x69\156\147\x6c\145\x2d\x73\151\147\156\x2d\157\x6e\x2d\155\165\x6c\x74\151\x70\x6c\145\55\151\144\x70\55\x62\x61\143\x6b\165\x70\x2d" . esc_attr($this->current_version);
        $uT = explode("\74\57\165\x6c\76", $t_["\x6e\145\x77\x5f\x76\145\162\x73\151\157\x6e\137\x63\150\x61\x6e\147\145\x6c\157\x67"]);
        $Px = $uT[0];
        $X4 = $Px . "\x3c\57\x75\154\76";
        $HI = array("\x68\64" => array(), "\x64\x69\166" => array(), "\145\155" => array(), "\x75\x6c" => array(), "\154\151" => array());
        echo "\74\x64\151\166\76\xd\12\x9\x9\11\x9\x3c\142\x3e\74\x62\162\x2f\76\x41\x6e\40\141\165\x74\x6f\155\x61\x74\x69\x63\40\x62\x61\143\x6b\x75\x70\40\157\146\40\143\x75\162\x72\145\x6e\x74\40\x76\x65\162\x73\x69\x6f\156\x20" . esc_attr($this->current_version) . "\x20\x68\141\x73\40\142\x65\145\156\40\143\x72\145\141\164\x65\144\40\x61\164\40\x74\150\145\x20\x6c\x6f\143\141\x74\x69\157\156\40" . esc_attr($py) . "\x20\167\x69\x74\150\40\x74\x68\x65\40\x6e\141\155\x65\x20\74\x73\160\141\x6e\40\163\164\x79\x6c\145\75\42\143\157\x6c\x6f\x72\72\43\x30\60\67\x33\x61\x61\73\x22\x3e" . esc_attr($pS) . "\x3c\x2f\x73\x70\x61\156\76\56\40\111\x6e\x20\x63\141\x73\145\x2c\40\163\x6f\x6d\x65\x74\x68\151\x6e\147\40\x62\x72\x65\141\x6b\x73\40\144\x75\162\x69\x6e\147\x20\x74\x68\145\x20\x75\160\144\141\164\145\x2c\x20\171\x6f\x75\40\143\x61\156\40\x72\145\x76\x65\x72\x74\x20\x74\157\40\171\157\x75\162\x20\x63\165\162\x72\x65\156\x74\40\166\145\x72\163\151\157\156\x20\142\x79\x20\x72\x65\x70\x6c\x61\143\151\x6e\147\40\164\x68\x65\40\x62\141\x63\153\165\160\x20\x75\x73\x69\156\x67\x20\x46\124\x50\40\x61\143\x63\x65\163\x73\x2e\x3c\x2f\142\76\15\xa\x9\11\11\74\x2f\144\x69\166\x3e\xd\12\11\11\11\x3c\144\x69\x76\40\163\x74\x79\154\x65\x3d\42\x63\157\154\x6f\162\x3a\40\x23\146\60\60\x3b\42\x3e\xd\12\x9\11\x9\11\74\x62\x72\x2f\x3e\124\141\x6b\145\x20\141\x20\155\x69\156\165\x74\145\x20\164\157\40\x63\x68\145\x63\153\40\164\150\x65\40\x63\150\141\156\147\x65\x6c\157\147\x20\157\x66\x20\x6c\141\164\x65\x73\164\40\166\145\x72\163\151\x6f\156\x20\x6f\x66\x20\x74\150\145\x20\x70\154\165\147\x69\156\56\x20\110\x65\x72\x65\x27\163\x20\167\150\x79\x20\x79\x6f\x75\x20\x6e\x65\145\x64\x20\164\157\x20\165\160\x64\141\164\x65\72\15\xa\x9\11\11\74\x2f\x64\151\166\76";
        echo "\74\144\151\x76\40\163\164\171\154\145\x3d\42\146\x6f\156\x74\55\167\x65\151\147\x68\x74\x3a\40\156\x6f\x72\155\x61\x6c\x3b\x22\x3e" . wp_kses($X4, $HI) . "\74\57\x64\151\166\x3e\74\x62\x3e\x4e\x6f\x74\x65\x3a\x3c\57\142\76\40\x50\154\145\141\163\145\40\143\x6c\151\143\153\40\157\156\x20\x3c\142\x3e\126\151\145\167\x20\126\x65\162\163\151\x6f\x6e\40\144\145\164\x61\x69\x6c\x73\74\57\x62\76\x20\x6c\x69\156\153\x20\x74\157\40\147\x65\x74\40\x63\x6f\x6d\x70\154\x65\164\x65\x20\x63\x68\141\156\x67\x65\x6c\157\x67\x20\x61\156\144\x20\x6c\151\x63\145\x6e\x73\145\40\x69\x6e\x66\157\162\155\141\164\x69\157\156\56\x20\x43\154\151\143\153\40\x6f\x6e\x20\x3c\142\x3e\125\160\144\x61\164\x65\x20\116\157\x77\x3c\57\142\76\x20\154\x69\156\x6b\40\x74\x6f\x20\165\160\144\141\164\x65\x20\164\x68\x65\40\x70\154\165\147\151\x6e\40\164\157\x20\154\141\x74\145\x73\x74\x20\x76\x65\x72\163\151\157\156\56";
        goto haa;
        Wa4:
        echo esc_html($t_["\x6c\x69\143\145\x6e\x73\x65\137\151\x6e\146\157\x72\155\141\x74\151\x6f\x6e"]);
        haa:
    }
    public function mo_saml_dismiss_notice()
    {
        if (!empty($_GET["\x6d\x6f\163\141\x6d\154\55\144\151\x73\155\x69\x73\x73"])) {
            goto gyt;
        }
        return;
        gyt:
        if (wp_verify_nonce($_GET["\x6d\x6f\163\141\x6d\154\55\x64\x69\x73\155\x69\x73\x73"], "\163\x61\155\154\55\x64\151\x73\155\151\x73\163")) {
            goto MOw;
        }
        return;
        MOw:
        if (!(!empty($_GET["\155\157\x73\x61\155\154\55\144\x69\163\x6d\151\163\163"]) && wp_verify_nonce($_GET["\x6d\x6f\163\141\155\x6c\x2d\144\x69\163\x6d\151\x73\x73"], "\163\141\155\x6c\55\x64\x69\163\x6d\x69\163\163"))) {
            goto N58;
        }
        $eA = new DateTime();
        $eA->modify("\x2b\61\x20\x64\x61\x79");
        update_option("\155\x6f\55\163\x61\x6d\x6c\x2d\160\x6c\x75\x67\x69\x6e\55\164\x69\x6d\145\x72", $eA);
        N58:
    }
    function mo_saml_create_backup_dir()
    {
        $py = plugin_dir_path(__FILE__);
        $py = rtrim($py, "\x2f");
        $py = rtrim($py, "\x5c");
        $t_ = get_plugin_data(__FILE__);
        $a3 = $t_["\124\145\x78\x74\x44\157\x6d\141\x69\x6e"];
        $Dg = wp_upload_dir();
        $GS = $Dg["\142\x61\163\x65\144\151\162"];
        $Dg = rtrim($GS, "\57");
        $EP = $Dg . DIRECTORY_SEPARATOR . "\x62\x61\143\153\x75\160" . DIRECTORY_SEPARATOR . $a3 . "\55\160\162\x65\x6d\151\x75\155\x2d\142\141\x63\x6b\165\160\x2d" . $this->current_version;
        if (file_exists($EP)) {
            goto Lal;
        }
        mkdir($EP, 0777, true);
        Lal:
        $fy = $py;
        $xF = $EP;
        $this->mo_saml_copy_files_to_backup_dir($fy, $xF);
    }
    function mo_saml_copy_files_to_backup_dir($py, $EP)
    {
        if (!is_dir($py)) {
            goto eP4;
        }
        $c6 = scandir($py);
        eP4:
        if (!empty($c6)) {
            goto xUq;
        }
        return;
        xUq:
        foreach ($c6 as $Qm) {
            if (!($Qm == "\56" || $Qm == "\x2e\56")) {
                goto Qvd;
            }
            goto hpD;
            Qvd:
            $Vc = $py . DIRECTORY_SEPARATOR . $Qm;
            $Lf = $EP . DIRECTORY_SEPARATOR . $Qm;
            if (is_dir($Vc)) {
                goto AwD;
            }
            copy($Vc, $Lf);
            goto chJ;
            AwD:
            if (file_exists($Lf)) {
                goto HKB;
            }
            mkdir($Lf, 0777, true);
            HKB:
            $this->mo_saml_copy_files_to_backup_dir($Vc, $Lf);
            chJ:
            hpD:
        }
        sFH:
    }
}
function mo_saml_update()
{
    if (!mo_saml_is_customer_registered()) {
        goto JmZ;
    }
    $L5 = mo_options_plugin_constants::HOSTNAME;
    $Cw = mo_options_plugin_constants::VERSION;
    $LN = $L5 . "\x2f\155\x6f\x61\x73\x2f\141\x70\x69\57\160\x6c\165\x67\x69\156\x2f\155\145\164\x61\144\x61\x74\141";
    $V1 = plugin_basename(__DIR__ . "\x2f\x6c\x6f\x67\151\156\x2e\160\150\x70");
    $Iz = new mo_saml_update_framework($Cw, $LN, $V1);
    add_action("\151\156\x5f\x70\x6c\165\147\151\156\x5f\x75\160\x64\x61\x74\x65\137\155\x65\x73\x73\x61\147\x65\55{$V1}", array($Iz, "\155\x6f\137\163\x61\x6d\x6c\x5f\x70\154\165\147\x69\156\137\165\x70\x64\141\164\x65\137\x6d\x65\x73\x73\x61\x67\145"), 10, 2);
    add_action("\x61\x64\x6d\x69\x6e\137\x6e\157\164\x69\143\145\x73", array($Iz, "\x6d\157\137\x73\141\155\154\137\x64\x69\x73\155\x69\x73\x73\x5f\x6e\x6f\164\151\x63\x65"), 50);
    JmZ:
}
