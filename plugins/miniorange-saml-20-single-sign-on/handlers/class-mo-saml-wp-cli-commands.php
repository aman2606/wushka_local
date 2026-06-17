<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



if (defined("\101\x42\123\120\101\x54\110")) {
    goto Qj;
}
exit;
Qj:
use MOSAML\LicenseLibrary\Mo_License_Service;
class Mo_Saml_WP_CLI_Commands
{
    public function fetch($MS, $pL)
    {
        $this->mo_cli_throw_cli_error_for_empty_values($pL, array("\143\x6f\x6e\146\x69\147"));
        $Ub = $this->mo_cli_fetch_and_validate_file_content($pL["\143\x6f\156\x66\151\147"]);
        if (Mo_License_Service::is_customer_license_verified()) {
            goto Rx;
        }
        WP_CLI::error(Mo_Saml_Cli_Error::USER_NOT_LOGGED_IN);
        Rx:
        mo_update_configuration_array($Ub, false);
        WP_CLI::success("\x53\x65\164\x74\x69\x6e\x67\163\x20\141\x70\x70\154\x69\x65\x64\40\163\x75\x63\x63\x65\163\163\x66\165\x6c\x6c\171\x2e");
        exit;
    }
    public function activate($MS, $pL)
    {
        if (!empty($pL)) {
            goto nX;
        }
        WP_CLI::error(Mo_Saml_Cli_Error::MISSING_ARGUMENTS);
        nX:
        $Zz = array("\146\x69\x6c\x65", "\144\157\x6d\x61\x69\156");
        $this->mo_cli_throw_cli_error_for_empty_values($pL, $Zz);
        $kH = $pL["\144\x6f\155\141\x69\x6e"];
        $Su = $this->mo_cli_fetch_and_validate_file_content($pL["\x66\x69\154\145"]);
        if (!(empty($Su) || !array($Su))) {
            goto px;
        }
        WP_CLI::error(Mo_Saml_Cli_Error::INVALID_JSON);
        px:
        $this->mo_cli_throw_cli_error_for_empty_values($Su, array("\x61\x64\155\151\x6e\x5f\x65\x6d\x61\151\x6c", "\x63\165\163\164\x6f\x6d\145\162\137\x6b\145\x79", "\143\165\163\x74\157\155\145\x72\x5f\x61\160\151\x5f\153\145\x79", "\143\x75\163\x74\x6f\x6d\x65\x72\137\x74\x6f\153\x65\x6e\x5f\x6b\145\x79", $kH));
        $b2 = $Su[$kH];
        $this->mo_cli_throw_cli_error_for_empty_values($b2, array("\x6d\157\x5f\x73\141\x6d\154\x5f\x6c\x69\143\145\x6e\163\145\137\153\145\x79"));
        $JP = $Su[$kH]["\155\157\x5f\163\141\155\x6c\x5f\154\x69\143\x65\x6e\163\x65\x5f\153\x65\x79"];
        $gv = Mo_SAML_Plugin::mo_saml_get_object();
        $gv->mo_sso_saml_deactivate();
        $this->mo_cli_save_details($Su["\x63\165\163\164\157\x6d\x65\x72\137\153\x65\x79"], $Su["\x63\165\x73\x74\x6f\x6d\x65\162\x5f\x61\x70\x69\x5f\153\145\171"], $Su["\x63\x75\163\164\157\x6d\145\x72\137\164\157\x6b\145\156\x5f\x6b\145\x79"], $Su["\x61\144\155\151\156\x5f\145\x6d\x61\x69\x6c"], $JP);
    }
    private function mo_cli_throw_cli_error_for_empty_values($uT, $x2)
    {
        foreach ($x2 as $R2) {
            if (!empty($uT[$R2])) {
                goto nV;
            }
            WP_CLI::error("\x54\150\x65\162\x65\40\x77\x61\x73\40\x61\156\x20\145\x72\x72\x6f\162\40\160\162\157\x63\145\x73\163\x69\x6e\x67\x20\x79\x6f\x75\x72\x20\162\x65\x71\165\145\x73\x74\x2e\x20" . $R2 . "\x20\x69\x73\40\145\x69\x74\150\x65\162\x20\x65\155\x70\164\171\x20\157\x72\40\156\x75\x6c\154");
            nV:
            hZ:
        }
        ba:
    }
    private function mo_cli_fetch_and_validate_file_content($iI)
    {
        $p0 = MO_SAML_PLUGIN_DIR . "\x2f" . $iI;
        $Z2 = $this->mo_cli_get_valid_file_data($p0);
        $lM = json_decode($Z2, true);
        if (!(json_last_error() !== JSON_ERROR_NONE)) {
            goto wU;
        }
        WP_CLI::error(Mo_Saml_Cli_Error::INVALID_JSON);
        wU:
        return $lM;
    }
    private function mo_cli_json_validator($jr)
    {
        if (empty($jr)) {
            goto Rs;
        }
        return is_string($jr) && is_array(json_decode($jr, true)) ? true : false;
        Rs:
        return false;
    }
    private function mo_cli_get_valid_file_data($p0)
    {
        if (file_exists($p0)) {
            goto Wg;
        }
        WP_CLI::error(Mo_Saml_Cli_Error::FILE_NOT_FOUND);
        Wg:
        $jr = file_get_contents($p0);
        if ($this->mo_cli_json_validator($jr)) {
            goto XW;
        }
        WP_CLI::error(Mo_Saml_Cli_Error::INCORRECT_FILE_FORMAT);
        XW:
        return $jr;
    }
    public function update()
    {
        if (!Mo_License_Service::is_customer_license_verified()) {
            goto dN;
        }
        $L5 = mo_options_plugin_constants::HOSTNAME;
        $Cw = mo_options_plugin_constants::VERSION;
        $LN = $L5 . "\x2f\155\157\x61\163\57\141\160\151\57\x70\x6c\x75\147\x69\x6e\x2f\x6d\145\x74\141\144\141\x74\141";
        $V1 = plugin_basename(MO_SAML_PLUGIN_DIR . "\x2f\x6c\157\147\x69\156\x2e\160\150\x70");
        $Vv = new mo_saml_update_framework($Cw, $LN, $V1);
        $mZ = $Vv->getRemote();
        if ("\123\125\x43\x43\x45\123\x53" === $mZ["\x73\x74\x61\x74\165\x73"]) {
            goto HO;
        }
        if ("\104\105\x4e\x49\x45\x44" === $mZ["\x73\x74\141\164\165\163"]) {
            goto HV;
        }
        WP_CLI::error("\x53\157\155\145\x74\x68\x69\156\147\x20\167\x65\156\164\x20\167\162\x6f\x6e\x67\x21");
        goto N7;
        HO:
        if (version_compare($Cw, $mZ["\156\145\x77\x56\145\162\163\151\157\x6e"], "\x3c")) {
            goto c_;
        }
        WP_CLI::line("\120\154\165\x67\x69\156\40\151\x73\x20\x61\x6c\x72\145\x61\x64\171\x20\x75\x70\x20\164\157\x20\144\x61\x74\x65\56");
        goto ti;
        c_:
        $FM = get_option("\x6d\x6f\137\163\141\x6d\154\137\x61\144\x6d\151\x6e\137\143\165\163\x74\157\x6d\x65\x72\137\x6b\145\x79");
        $LA = get_option("\155\x6f\x5f\163\141\x6d\154\x5f\x61\144\x6d\151\156\x5f\x61\x70\x69\137\153\145\x79");
        $pf = round(microtime(true) * 1000);
        $ff = $FM . number_format($pf, 0, '', '') . $LA;
        $Hy = hash("\163\150\x61\65\x31\62", $ff);
        $PX = mo_options_plugin_constants::HOSTNAME . "\x2f\155\x6f\141\163\x2f\x70\x6c\x75\x67\x69\x6e\x2f\144\157\x77\156\x6c\x6f\x61\144\x2d\165\x70\x64\x61\164\x65\77\160\154\x75\147\151\156\x53\x6c\165\x67\x3d" . $V1 . "\x26\154\x69\x63\x65\156\163\145\120\x6c\141\x6e\x4e\141\155\145\75" . mo_options_plugin_constants::LICENSE_PLAN_NAME . "\46\x63\x75\x73\164\x6f\155\x65\x72\x49\144\75" . $FM . "\x26\154\151\x63\145\x6e\x73\x65\124\171\160\145\x3d" . mo_options_plugin_constants::LICENSE_TYPE . "\46\141\x75\x74\150\124\x6f\153\145\x6e\75" . $Hy . "\46\x6f\164\160\124\x6f\x6b\145\156\75" . $pf;
        $py = wp_upload_dir(MO_SAML_PLUGIN_DIR);
        $Vv->mo_saml_create_backup_dir();
        $iS = $py["\142\141\x73\x65\144\151\162"] . DIRECTORY_SEPARATOR . "\142\141\143\x6b\165\160" . DIRECTORY_SEPARATOR . "\x6d\x69\156\x69\117\x72\141\x6e\x67\145\x2d\x73\141\x6d\x6c\x2d\62\x30\x2d\x73\x69\156\147\x6c\x65\55\x73\151\x67\x6e\55\x6f\x6e\x2d\145\156\164\145\x72\160\162\x69\x73\x65\x2d\x6c\x61\164\x65\x73\164\x2e\172\x69\160";
        file_put_contents($iS, file_get_contents($PX));
        $xF = plugin_dir_path(MO_SAML_PLUGIN_DIR);
        $this->mo_cli_unzip($iS, $xF);
        if (!file_exists($iS)) {
            goto XT;
        }
        wp_delete_file($iS);
        XT:
        WP_CLI::success("\120\x6c\x75\x67\151\x6e\x20\x69\x73\40\163\x75\143\143\x65\163\163\x66\x75\x6c\x6c\x79\x20\x75\x70\x64\x61\x74\145\x64\56");
        ti:
        goto N7;
        HV:
        WP_CLI::error("\x4c\x69\x63\x65\x6e\163\x65\40\151\163\x20\x65\170\160\x69\x72\145\x64\x2e\x20\120\x6c\145\x61\x73\145\x20\162\145\x6e\145\167\40\x79\157\x75\x72\x20\154\x69\143\x65\156\163\x65\56");
        N7:
        exit;
        dN:
        WP_CLI::error(Mo_Saml_Cli_Error::USER_NOT_LOGGED_IN);
        exit;
    }
    private function mo_cli_unzip($fy, $xF)
    {
        if (extension_loaded("\172\x69\x70")) {
            goto JW;
        }
        WP_CLI::line("\x27\x7a\151\x70\x27\40\x65\x78\164\145\x6e\163\x69\x6f\156\40\151\163\40\x6e\x6f\164\40\145\x6e\x61\142\x6c\145\144\56");
        goto Ea;
        JW:
        $dB = new ZipArchive();
        if ($dB->open($fy) === true) {
            goto sf;
        }
        WP_CLI::line("\x55\x6e\x7a\x69\160\x70\145\x64\40\x50\x72\x6f\143\x65\163\x73\40\146\141\151\154\145\144\56");
        goto gm;
        sf:
        $dB->extractTo($xF);
        $dB->close();
        gm:
        Ea:
    }
    private function mo_saml_check_if_user_already_logged_in($Su)
    {
        $ll = get_option(Mo_Saml_Options_Plugin_Admin::ADMIN_CUSTOMER_KEY);
        if (!empty($ll)) {
            goto LD;
        }
        return false;
        LD:
        if (!($ll === $Su["\x63\x75\163\x74\157\x6d\145\162\137\x6b\145\171"])) {
            goto j2;
        }
        return true;
        j2:
        return false;
    }
    private function mo_cli_save_details($FM, $cO, $Ii, $vP, $JP)
    {
        if (mo_saml_is_extension_installed("\143\x75\x72\154")) {
            goto cf;
        }
        WP_CLI::error(Mo_Saml_Cli_Error::CURL_ERROR);
        cf:
        update_option(Mo_Saml_Options_Plugin_Admin::VERIFY_CUSTOMER, '');
        delete_option(Mo_Saml_Options_Plugin_Admin::ADMIN_EMAIL);
        delete_option(Mo_Saml_Options_Plugin_Admin::ADMIN_PHONE);
        delete_option(Mo_Saml_Options_Plugin_Admin::SML_LK);
        delete_option(Mo_Saml_Options_Plugin_Admin::SITE_CHECK);
        $RY = sanitize_email($vP);
        update_option(Mo_Saml_Options_Plugin_Admin::ADMIN_EMAIL, $RY);
        $Uw = new CustomerSaml();
        $Qm = $Uw->check_customer($this);
        if ($Qm) {
            goto dn;
        }
        WP_CLI::error(Mo_Saml_Cli_Error::POOR_INTERNET);
        dn:
        $Qm = json_decode($Qm, true);
        if (!(isset($Qm["\x73\164\x61\x74\x75\x73"]) && strcasecmp($Qm["\163\x74\141\x74\x75\x73"], "\103\x55\123\124\x4f\115\105\122\x5f\116\x4f\124\x5f\106\117\125\116\x44") === 0)) {
            goto kp;
        }
        WP_CLI::error(Mo_Saml_Cli_Error::CUSTOMER_NOT_FOUND);
        kp:
        update_option(Mo_Saml_Options_Plugin_Admin::ADMIN_CUSTOMER_KEY, $FM);
        update_option(Mo_Saml_Options_Plugin_Admin::ADMIN_API_KEY, $cO);
        update_option(Mo_Saml_Options_Plugin_Admin::CUSTOMER_TOKEN, $Ii);
        delete_option(Mo_Saml_Options_Plugin_Admin::VERIFY_CUSTOMER);
        $fO = htmlspecialchars(trim($JP));
        Mo_Saml_License_Handler::mo_saml_verify_license_key($fO, $Uw, "\164\x72\x75\145");
    }
}
WP_CLI::add_command("\163\141\155\154", "\x4d\157\x5f\x53\141\x6d\154\137\x57\x50\137\x43\114\x49\137\103\x6f\155\x6d\x61\x6e\144\163");
