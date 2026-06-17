<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



class Customersaml
{
    public $email;
    public $phone;
    private $defaultCustomerKey = "\61\66\65\65\65";
    private $defaultApiKey = "\x66\106\x64\62\130\143\x76\124\107\x44\x65\x6d\x5a\166\142\x77\x31\142\x63\125\145\x73\x4e\x4a\x57\105\161\x4b\142\x62\x55\x71";
    function get_customer_key($RY, $lq)
    {
        $Oz = mo_options_plugin_constants::HOSTNAME . "\57\x6d\157\x61\x73\57\162\145\163\x74\57\x63\165\x73\164\x6f\155\x65\x72\57\x6b\145\x79";
        if (!(empty($RY) || empty($lq))) {
            goto XxW;
        }
        return false;
        XxW:
        $N8 = array("\145\x6d\x61\151\x6c" => $RY, "\160\x61\x73\x73\167\157\x72\x64" => $lq);
        $bD = json_encode($N8);
        $sB = array("\103\x6f\156\164\x65\156\x74\x2d\x54\171\160\145" => "\141\160\160\154\151\143\141\x74\151\x6f\156\57\x6a\x73\157\x6e", "\x63\x68\x61\162\x73\x65\x74" => "\125\124\106\55\70", "\101\x75\164\150\x6f\x72\151\172\x61\164\151\157\x6e" => "\x42\141\x73\151\143");
        $MS = array("\155\145\164\x68\157\144" => "\120\x4f\123\124", "\x62\157\144\171" => $bD, "\164\x69\155\145\157\165\x74" => "\61\60", "\162\145\144\151\x72\145\143\x74\151\157\156" => "\x35", "\150\164\x74\x70\x76\x65\x72\163\x69\x6f\x6e" => "\x31\56\60", "\x62\x6c\x6f\x63\x6b\151\156\x67" => true, "\150\145\141\144\145\162\163" => $sB);
        $d4 = SAMLSPUtilities::mo_saml_wp_remote_call($Oz, $MS, false);
        return $d4;
    }
    function check_customer($qx)
    {
        $Oz = mo_options_plugin_constants::HOSTNAME . "\x2f\x6d\157\141\x73\57\x72\145\163\164\57\x63\165\x73\164\157\x6d\x65\162\x2f\143\150\x65\x63\x6b\55\151\146\55\145\170\151\163\164\x73";
        $RY = get_option("\155\157\x5f\163\141\155\154\x5f\x61\x64\155\x69\156\137\x65\155\x61\151\x6c");
        if (!empty($RY)) {
            goto X5P;
        }
        return false;
        X5P:
        $N8 = array("\145\x6d\x61\x69\x6c" => $RY);
        $bD = json_encode($N8);
        $sB = array("\x43\157\x6e\164\145\156\x74\55\x54\171\160\x65" => "\141\x70\160\154\x69\143\x61\x74\151\x6f\x6e\x2f\152\163\x6f\x6e", "\x63\x68\x61\x72\163\x65\x74" => "\125\x54\x46\x2d\x38", "\101\x75\164\x68\157\x72\151\x7a\x61\x74\x69\157\x6e" => "\x42\141\x73\x69\x63");
        $MS = array("\155\145\164\150\x6f\144" => "\x50\117\123\x54", "\142\x6f\x64\x79" => $bD, "\x74\151\x6d\x65\x6f\x75\164" => "\61\x30", "\x72\x65\x64\151\162\145\x63\x74\151\157\x6e" => "\65", "\150\164\x74\x70\x76\x65\162\163\151\157\156" => "\61\56\x30", "\x62\154\x6f\143\x6b\x69\156\x67" => true, "\150\x65\x61\144\x65\x72\163" => $sB);
        $d4 = SAMLSPUtilities::mo_saml_wp_remote_call($Oz, $MS, false);
        return $d4;
    }
    function submit_contact_us($RY, $TZ, $yZ, $qx)
    {
        $current_user = wp_get_current_user();
        $Mv = get_option(Mo_Saml_Options_Plugin_Admin::ADMIN_CUSTOMER_KEY);
        $yZ = "\x5b\127\120\40\123\x41\x4d\114\x20\62\56\x30\x20\123\x50\40\123\x53\117\x20" . mo_options_plugin_constants::LICENSE_PLAN_TYPE . "\40\120\154\x75\147\151\x6e\x20\x76" . mo_options_plugin_constants::VERSION . "\135\x20" . $yZ . "\x3c\x62\x72\76\74\142\162\x3e\103\165\163\x74\x6f\x6d\x65\x72\x20\111\x44\x20\72\40" . $Mv;
        $N8 = array("\146\x69\162\163\x74\x4e\141\155\145" => $current_user->user_firstname, "\x6c\141\163\164\116\141\155\x65" => $current_user->user_lastname, "\143\157\155\160\x61\156\x79" => $_SERVER["\123\x45\122\x56\x45\x52\137\116\x41\115\x45"], "\145\155\141\151\154" => $RY, "\143\143\x45\x6d\x61\x69\x6c" => "\163\141\155\154\163\x75\160\160\157\x72\x74\100\x78\145\x63\x75\x72\151\146\171\x2e\143\157\x6d", "\x70\x68\x6f\156\145" => $TZ, "\x71\165\145\x72\x79" => $yZ);
        $bD = json_encode($N8);
        $Oz = mo_options_plugin_constants::HOSTNAME . "\x2f\155\x6f\141\x73\x2f\162\145\163\x74\x2f\143\165\163\164\x6f\x6d\145\x72\x2f\x63\x6f\156\x74\x61\x63\x74\55\x75\x73";
        $sB = array("\x43\157\156\164\145\x6e\x74\55\124\x79\160\145" => "\141\160\160\154\x69\143\x61\164\151\157\156\x2f\x6a\x73\157\156", "\x63\150\141\162\x73\x65\164" => "\125\x54\x46\55\x38", "\x41\x75\164\x68\157\x72\x69\172\x61\x74\151\157\x6e" => "\x42\141\x73\151\x63");
        $MS = array("\x6d\145\164\x68\157\144" => "\x50\x4f\x53\124", "\142\157\x64\x79" => $bD, "\x74\x69\155\x65\157\165\164" => "\x31\60", "\x72\145\x64\151\162\x65\x63\x74\x69\x6f\156" => "\x35", "\150\164\164\160\166\x65\162\x73\151\x6f\x6e" => "\x31\56\x30", "\x62\154\157\143\x6b\151\x6e\x67" => true, "\x68\145\141\x64\145\x72\x73" => $sB);
        $d4 = SAMLSPUtilities::mo_saml_wp_remote_call($Oz, $MS, false);
        return $d4;
    }
    function mo_saml_verify_license($fO, $qx)
    {
        $Oz = mo_options_plugin_constants::HOSTNAME . "\x2f\x6d\x6f\x61\x73\57\141\160\x69\57\142\141\x63\x6b\165\x70\143\157\x64\145\57\x76\x65\162\151\146\171";
        $oP = get_option("\155\x6f\x5f\x73\141\155\154\137\x61\144\155\x69\156\137\143\x75\x73\x74\x6f\x6d\145\162\137\x6b\x65\x79");
        $oK = get_option("\155\x6f\137\x73\141\x6d\x6c\x5f\141\x64\x6d\x69\x6e\x5f\141\160\x69\x5f\x6b\145\x79");
        if (!(empty($oK) || empty($oP))) {
            goto BQa;
        }
        return false;
        BQa:
        $Cj = round(microtime(true) * 1000);
        $Sc = $oP . number_format($Cj, 0, '', '') . $oK;
        $FP = hash("\163\x68\141\x35\x31\x32", $Sc);
        $OJ = "\103\x75\x73\x74\x6f\x6d\145\x72\x2d\113\x65\171\72\40" . $oP;
        $Jd = "\124\151\x6d\145\x73\x74\x61\x6d\x70\x3a\40" . number_format($Cj, 0, '', '');
        $QC = "\101\x75\164\x68\x6f\162\151\172\x61\x74\x69\x6f\x6e\72\x20" . $FP;
        $Cj = number_format($Cj, 0, '', '');
        $N8 = '';
        $N8 = array("\143\x6f\144\145" => $fO, "\143\x75\163\164\x6f\x6d\x65\x72\x4b\x65\x79" => $oP, "\141\144\144\151\164\x69\157\x6e\x61\154\x46\x69\x65\x6c\x64\163" => array("\146\x69\145\154\x64\x31" => home_url()));
        $bD = json_encode($N8);
        $sB = array("\103\x6f\x6e\x74\x65\156\164\x2d\x54\171\160\145" => "\x61\160\160\x6c\x69\143\141\164\x69\157\x6e\x2f\152\x73\x6f\x6e", "\103\165\x73\164\157\x6d\x65\162\x2d\113\145\171" => $oP, "\124\x69\x6d\x65\x73\164\x61\155\160" => $Cj, "\101\165\164\150\x6f\162\x69\172\x61\x74\x69\157\156" => $FP);
        $MS = array("\155\x65\x74\150\157\144" => "\x50\x4f\x53\124", "\x62\157\144\x79" => $bD, "\x74\151\x6d\145\x6f\x75\164" => "\x31\x30", "\x72\x65\144\151\162\x65\143\x74\151\x6f\156" => "\x35", "\x68\x74\164\x70\x76\145\162\x73\151\x6f\x6e" => "\x31\x2e\x30", "\142\154\x6f\x63\153\151\x6e\x67" => true, "\x68\x65\x61\x64\145\x72\x73" => $sB);
        $d4 = SAMLSPUtilities::mo_saml_wp_remote_call($Oz, $MS, false);
        return $d4;
    }
    function check_customer_ln()
    {
        $Oz = mo_options_plugin_constants::HOSTNAME . "\x2f\x6d\x6f\x61\163\x2f\x72\145\163\164\57\143\x75\163\164\x6f\x6d\x65\x72\x2f\x6c\151\143\x65\156\x73\x65";
        $oP = get_option("\x6d\157\x5f\x73\x61\155\154\137\141\144\x6d\x69\x6e\x5f\143\x75\x73\x74\x6f\155\x65\x72\x5f\x6b\145\171");
        $oK = get_option("\x6d\x6f\x5f\163\141\155\154\x5f\141\x64\x6d\151\156\x5f\x61\x70\x69\x5f\153\x65\171");
        if (!(empty($oP) || empty($oK))) {
            goto IId;
        }
        return false;
        IId:
        $Cj = round(microtime(true) * 1000);
        $Sc = $oP . number_format($Cj, 0, '', '') . $oK;
        $FP = hash("\163\x68\141\65\61\62", $Sc);
        $OJ = "\x43\x75\x73\x74\x6f\x6d\x65\x72\55\113\145\x79\72\40" . $oP;
        $Jd = "\124\151\155\x65\x73\164\141\x6d\160\72\x20" . number_format($Cj, 0, '', '');
        $QC = "\101\165\164\150\157\x72\x69\x7a\x61\x74\x69\157\x6e\x3a\x20" . $FP;
        $N8 = '';
        $N8 = array("\x63\x75\x73\x74\157\155\145\x72\x49\144" => $oP, "\x61\x70\x70\x6c\x69\x63\141\164\151\157\156\116\x61\155\145" => mo_options_plugin_constants::LICENSE_PLAN_NAME);
        $Cj = number_format($Cj, 0, '', '');
        $bD = json_encode($N8);
        $sB = array("\x43\x6f\156\164\x65\156\x74\55\x54\x79\x70\145" => "\141\160\x70\154\x69\x63\141\x74\151\x6f\156\57\152\163\157\x6e", "\x43\x75\x73\164\157\x6d\145\162\x2d\x4b\145\171" => $oP, "\124\x69\155\x65\163\164\x61\x6d\x70" => $Cj, "\101\x75\x74\x68\157\162\151\x7a\x61\x74\151\157\156" => $FP);
        $MS = array("\155\x65\164\150\x6f\x64" => "\120\117\x53\x54", "\x62\x6f\x64\171" => $bD, "\x74\151\x6d\145\x6f\x75\x74" => "\x31\x30", "\162\x65\144\x69\162\x65\143\x74\x69\157\x6e" => "\65", "\x68\164\x74\160\166\145\162\x73\x69\x6f\x6e" => "\61\56\x30", "\142\x6c\x6f\x63\153\x69\156\147" => true, "\x68\145\141\x64\145\x72\163" => $sB);
        $d4 = SAMLSPUtilities::mo_saml_wp_remote_call($Oz, $MS, false);
        return $d4;
    }
    function mo_saml_update_key_status($qx)
    {
        $Oz = mo_options_plugin_constants::HOSTNAME . "\x2f\x6d\157\141\163\57\x61\x70\151\x2f\x62\141\x63\x6b\165\x70\143\157\144\145\57\165\160\x64\141\x74\145\163\x74\x61\164\x75\x73";
        $oP = get_option("\x6d\x6f\137\x73\141\x6d\154\137\x61\144\x6d\151\x6e\137\143\x75\x73\164\x6f\155\145\162\137\x6b\145\171");
        $oK = get_option("\x6d\x6f\x5f\163\x61\x6d\154\x5f\141\x64\155\151\x6e\137\x61\x70\151\137\x6b\x65\171");
        if (!(empty($oP) || empty($oK))) {
            goto UGR;
        }
        return;
        UGR:
        $Cj = round(microtime(true) * 1000);
        $Sc = $oP . number_format($Cj, 0, '', '') . $oK;
        $FP = hash("\x73\x68\x61\65\61\62", $Sc);
        $OJ = "\103\165\x73\164\157\155\x65\x72\55\x4b\145\x79\72\x20" . $oP;
        $Jd = "\124\x69\155\x65\163\x74\141\x6d\x70\x3a\x20" . number_format($Cj, 0, '', '');
        $QC = "\x41\x75\164\x68\x6f\162\151\172\141\x74\x69\157\156\x3a\x20" . $FP;
        $R2 = get_option("\x6d\157\x5f\x73\x61\155\154\137\143\165\163\x74\157\155\x65\x72\137\164\157\x6b\x65\x6e");
        $Cj = number_format($Cj, 0, '', '');
        $fO = AESEncryption::decrypt_data(get_option("\163\155\x6c\137\154\153"), $R2);
        $N8 = '';
        $N8 = array("\143\157\x64\145" => $fO, "\143\165\163\x74\x6f\155\145\x72\113\x65\171" => $oP);
        $bD = json_encode($N8);
        $sB = array("\x43\x6f\x6e\164\x65\x6e\164\x2d\124\171\160\145" => "\x61\x70\160\154\x69\143\x61\x74\x69\x6f\x6e\57\x6a\163\x6f\x6e", "\103\165\163\x74\157\155\145\162\x2d\113\145\x79" => $oP, "\x54\151\x6d\145\x73\x74\141\x6d\160" => $Cj, "\x41\165\164\x68\x6f\162\x69\172\x61\x74\151\157\x6e" => $FP);
        $MS = array("\x6d\145\164\x68\x6f\144" => "\120\117\123\x54", "\x62\157\x64\171" => $bD, "\164\x69\155\145\x6f\165\x74" => "\x31\x30", "\162\x65\144\151\162\145\x63\x74\151\x6f\156" => "\x35", "\x68\164\x74\x70\x76\x65\162\x73\x69\x6f\156" => "\61\x2e\x30", "\142\x6c\x6f\143\x6b\x69\x6e\147" => true, "\150\x65\x61\144\145\162\163" => $sB);
        $d4 = SAMLSPUtilities::mo_saml_wp_remote_call($Oz, $MS, false);
        return $d4;
    }
}
