<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



if (defined("\101\x42\x53\120\x41\124\110")) {
    goto wG;
}
exit;
wG:
use MOSAML\LicenseLibrary\Mo_License_Service;
class Mo_Saml_License_Handler
{
    public static function mo_saml_verify_license_key($fO, $Uw, $JC = "\x66\x61\x6c\163\145")
    {
        $Di = Mo_SAML_Plugin::mo_saml_get_object();
        $Qm = $Uw->mo_saml_verify_license($fO, $Di);
        if (!(false === $Qm)) {
            goto m3;
        }
        if ("\164\x72\x75\145" === $JC) {
            goto C2;
        }
        update_option("\x6d\157\x5f\x73\141\155\x6c\137\155\x65\163\163\x61\x67\145", "\x53\157\x6d\x65\164\x68\x69\x6e\x67\40\167\145\x6e\164\40\167\x72\157\156\x67\x20\167\x68\151\x6c\x65\x20\160\x72\x6f\143\145\x73\163\151\x6e\147\x20\164\150\151\x73\40\x72\x65\x71\x75\x65\163\x74\56\40\120\x6c\x65\x61\x73\x65\40\154\x6f\x67\151\156\40\141\156\144\x20\x74\x72\171\40\x61\147\x61\151\156");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto b8;
        C2:
        WP_CLI::error(Mo_Saml_Cli_Error::POOR_INTERNET);
        b8:
        return;
        m3:
        $Qm = json_decode($Qm, true);
        if (!empty($Qm["\163\x74\141\164\x75\x73"])) {
            goto TA;
        }
        if ("\164\162\165\x65" === $JC) {
            goto AE;
        }
        update_option("\x6d\x6f\137\163\141\155\154\x5f\155\x65\163\x73\x61\x67\145", "\124\150\145\x20\154\x69\143\145\156\x73\x65\40\153\x65\x79\40\171\x6f\165\40\x68\141\x76\x65\40\x65\x6e\164\x65\162\145\x64\40\151\163\40\x69\156\x76\141\x6c\151\144\56\x20\120\154\x65\141\163\x65\x20\x65\x6e\164\145\x72\40\x61\40\x76\x61\x6c\x69\x64\40\154\x69\143\145\x6e\163\x65\40\153\145\171\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto mS;
        AE:
        WP_CLI::error(Mo_Saml_Cli_Error::INVALID_CUSTOMER_OR_LICENSE);
        mS:
        return;
        TA:
        $Pr = $Uw->check_customer_ln();
        if ($Pr) {
            goto G0;
        }
        if ("\164\162\x75\145" === $JC) {
            goto tU;
        }
        update_option("\155\157\137\x73\x61\x6d\154\137\x6d\x65\x73\163\x61\x67\x65", "\123\x6f\x6d\145\164\150\151\x6e\x67\x20\x77\145\156\x74\40\x77\162\x6f\156\147\x20\167\x68\x69\x6c\145\40\160\x72\157\143\x65\x73\x73\x69\x6e\147\40\x74\150\x69\163\x20\x72\145\161\165\145\163\x74\56\40\120\154\x65\141\x73\x65\40\154\x6f\x67\x69\156\x20\x61\x6e\x64\40\164\162\171\x20\141\147\x61\151\x6e");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto Su;
        tU:
        WP_CLI::error(Mo_Saml_Cli_Error::POOR_INTERNET);
        Su:
        return;
        G0:
        $Pr = json_decode($Pr, true);
        if (strcasecmp($Qm["\163\x74\x61\164\165\163"], "\x53\125\x43\103\105\123\x53") === 0 && strcasecmp($Pr["\x73\164\x61\164\165\163"], "\123\x55\x43\103\x45\x53\123") === 0) {
            goto j6;
        }
        if (strcasecmp($Qm["\163\164\141\x74\165\x73"], "\x46\101\x49\114\x45\x44") === 0 || strcasecmp($Pr["\x73\x74\x61\x74\x75\163"], "\x46\x41\x49\x4c\x45\104") === 0) {
            goto sF;
        }
        if ("\164\x72\x75\x65" === $JC) {
            goto Db;
        }
        update_option("\x6d\157\x5f\163\141\x6d\154\137\x6d\x65\163\x73\x61\147\145", "\101\x6e\x20\145\x72\x72\157\x72\x20\157\143\x63\165\x72\x72\145\144\x20\167\150\x69\x6c\145\x20\160\162\157\x63\x65\x73\163\x69\x6e\x67\40\x79\x6f\165\162\40\162\x65\161\165\x65\163\164\56\x20\120\x6c\x65\141\x73\x65\40\x54\x72\x79\40\141\147\141\151\x6e\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto jb;
        Db:
        WP_CLI::error("\x53\157\x6d\x65\x74\x68\151\x6e\147\40\167\145\x6e\x74\x20\167\162\157\156\147\x21");
        jb:
        return;
        goto HC;
        j6:
        $R2 = get_option("\155\157\137\163\141\155\x6c\x5f\143\165\x73\164\157\155\x65\162\137\x74\x6f\153\x65\156");
        update_option("\163\155\x6c\137\154\153", AESEncryption::encrypt_data($fO, $R2));
        if (empty($Pr["\154\x69\143\145\156\x73\x65\x45\170\160\x69\x72\x79"])) {
            goto zH;
        }
        Mo_License_Service::update_license_expiry($Pr["\x6c\151\143\145\156\x73\145\105\170\x70\151\x72\x79"]);
        zH:
        Mo_License_Service::update_trial_status($Pr["\164\162\151\x61\154"]);
        update_option(Mo_Saml_Options::LAST_SYNCED_TIME, time());
        update_customer_idp_count($Pr);
        if ("\x74\162\165\x65" === $JC) {
            goto RF;
        }
        update_option("\x6d\x6f\137\x73\x61\155\x6c\137\x6d\145\163\163\141\x67\x65", "\x59\157\165\162\40\154\151\143\145\x6e\x73\145\40\151\x73\40\166\145\162\x69\146\x69\x65\x64\56\x20\131\157\165\x20\143\141\156\40\x6e\x6f\167\40\163\145\x74\165\x70\x20\x74\150\145\x20\160\154\x75\147\x69\156\x2e");
        SAMLSPUtilities::mo_saml_show_success_message();
        goto Q5;
        RF:
        WP_CLI::success("\x4c\x69\143\145\156\163\x65\40\141\160\160\x6c\x69\x65\144\x20\163\x75\x63\x63\x65\163\163\x66\x75\x6c\154\x79\x2e");
        Q5:
        return;
        goto HC;
        sF:
        if (strcasecmp($Qm["\x6d\145\x73\163\141\147\x65"], "\x43\x6f\x64\145\x20\150\x61\163\x20\105\x78\160\151\x72\145\x64") === 0) {
            goto Ud;
        }
        if (strcasecmp($Pr["\x6d\145\x73\163\x61\147\145"], "\114\x69\143\145\x6e\163\x65\x20\x4e\157\164\x20\x46\x6f\x75\156\144\x2e") === 0) {
            goto FX;
        }
        if ("\164\x72\165\x65" === $JC) {
            goto ZU;
        }
        update_option("\155\157\x5f\x73\x61\155\154\137\155\145\x73\163\x61\147\x65", "\131\157\165\40\150\x61\166\x65\x20\145\156\164\145\x72\145\144\40\x61\x6e\x20\151\156\166\x61\154\x69\x64\x20\154\151\143\x65\x6e\x73\x65\x20\153\145\x79\56\40\120\154\x65\x61\x73\x65\x20\x65\156\164\x65\x72\40\x61\x20\x76\141\x6c\x69\x64\x20\x6c\x69\143\145\156\x73\x65\x20\x6b\145\171\56");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto yj;
        ZU:
        WP_CLI::error(Mo_Saml_Cli_Error::INVALID_LICENSE);
        yj:
        return;
        goto Wn;
        Ud:
        if ("\164\162\x75\x65" === $JC) {
            goto ak;
        }
        update_option("\155\x6f\x5f\163\141\155\154\137\155\x65\163\x73\141\x67\x65", "\x4c\x69\143\145\x6e\163\x65\x20\153\145\171\x20\x79\157\x75\40\150\141\166\x65\40\145\156\x74\145\x72\145\x64\40\x68\x61\x73\x20\x61\x6c\x72\145\x61\x64\171\x20\142\x65\x65\x6e\x20\x75\x73\x65\144\56\x20\120\154\x65\x61\x73\x65\40\145\x6e\x74\x65\162\40\141\40\153\145\171\x20\x77\x68\x69\x63\150\40\x68\141\x73\40\156\157\x74\x20\142\145\x65\x6e\x20\x75\163\145\144\40\x62\x65\146\x6f\x72\x65\40\x6f\156\40\x61\156\171\x20\x6f\164\150\145\x72\x20\x69\156\x73\x74\x61\156\x63\145\x20\x6f\x72\x20\x69\x66\x20\171\x6f\x75\40\x68\141\x76\145\x20\x65\x78\x68\x61\165\163\x74\145\144\x20\141\154\154\x20\x79\157\x75\162\x20\153\145\x79\163\40\164\x68\145\x6e\x20\143\x6f\x6e\164\141\x63\x74\x20\165\x73\40\164\157\x20\x62\x75\x79\x20\x6d\x6f\x72\145\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto x2;
        ak:
        WP_CLI::error(Mo_Saml_Cli_Error::CODE_EXPIRED);
        x2:
        return;
        goto Wn;
        FX:
        if ("\x74\x72\165\145" === $JC) {
            goto uH;
        }
        update_option("\155\157\137\163\141\155\154\137\x6d\x65\163\x73\141\147\145", "\131\x6f\x75\x20\144\157\40\156\x6f\x74\x20\150\x61\x76\145\40\x61\40\166\x61\x6c\151\144\x20\x6c\x69\143\x65\x6e\x73\145\x20\x74\x6f\40\141\x63\x74\151\x76\x61\164\145\40\x74\x68\145\40\160\x6c\x75\147\151\x6e\56\40\x50\x6c\x65\141\163\145\40\162\145\141\x63\150\x20\157\x75\164\40\164\x6f\x20\165\x73\40\141\164\40\74\141\40\150\x72\x65\146\x3d\42\155\x61\151\x6c\x74\157\72\x73\141\155\154\163\x75\x70\x70\x6f\162\x74\x40\170\x65\143\x75\x72\x69\x66\x79\x2e\143\x6f\x6d\x22\76\x73\141\155\154\x73\x75\x70\x70\157\162\164\x40\x78\145\x63\165\162\151\146\x79\56\x63\x6f\155\74\x2f\141\x3e\40\x74\x6f\x20\x75\160\x67\162\141\144\145\x20\x79\157\165\x72\x20\154\x69\143\x65\x6e\163\x65\40\157\162\40\151\146\x20\x79\x6f\165\40\150\141\166\145\x20\141\156\171\40\x71\165\x65\163\164\x69\x6f\156\x73\x2e");
        SAMLSPUtilities::mo_saml_show_error_message();
        goto K3;
        uH:
        WP_CLI::error(Mo_Saml_Cli_Error::NOT_UPGRADED);
        K3:
        return;
        Wn:
        HC:
    }
}
