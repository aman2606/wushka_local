<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



require_once MO_SAML_PLUGIN_DIR . MO_SAML_OPTIONS_ENUM;
use MOSAML\LicenseLibrary\Mo_License_Service;
add_action("\x61\144\x6d\151\156\137\151\x6e\151\164", "\155\x69\x6e\x69\x6f\162\x61\x6e\147\x65\137\x69\155\x70\157\x72\164\x5f\x65\170\x70\x6f\162\164");
define("\124\141\142\137\x43\x6c\x61\x73\x73\x5f\116\x61\x6d\145\163", serialize(array("\x53\123\117\x5f\114\157\147\x69\x6e" => "\155\157\x5f\x6f\x70\x74\x69\157\156\163\137\145\x6e\x75\155\x5f\x73\163\157\x5f\x6c\157\x67\151\156", "\111\144\145\x6e\x74\x69\x74\171\x5f\x50\x72\x6f\x76\x69\x64\145\162" => "\155\157\137\157\160\x74\151\157\156\163\x5f\145\156\x75\155\137\151\x64\x65\156\164\x69\x74\171\137\x70\x72\x6f\166\x69\x64\x65\162", "\123\x65\162\x76\151\x63\x65\137\120\x72\x6f\x76\151\144\145\x72" => "\155\157\137\157\x70\x74\x69\x6f\x6e\163\x5f\145\x6e\165\155\x5f\x73\x65\162\x76\151\x63\145\137\160\x72\157\166\151\144\145\x72", "\104\157\155\141\151\x6e\x20\115\141\x70\160\151\x6e\147" => "\x6d\157\x5f\x6f\160\x74\151\x6f\156\163\137\x65\x6e\x75\155\x5f\x64\157\x6d\x61\x69\x6e\137\155\141\160\x70\x69\x6e\x67", "\101\x74\x74\x72\151\x62\165\x74\x65\137\115\141\x70\160\x69\x6e\x67" => "\x6d\157\137\x6f\x70\164\151\x6f\x6e\163\137\145\x6e\165\155\137\141\x74\164\162\x69\142\x75\164\145\137\x6d\141\x70\x70\151\156\147", "\122\x6f\154\145\x5f\115\141\x70\160\x69\156\147" => "\x6d\157\x5f\x6f\x70\x74\151\157\156\x73\137\x65\x6e\165\155\x5f\162\157\x6c\145\x5f\155\x61\160\160\151\x6e\x67", "\103\x75\163\164\157\x6d\137\x43\145\162\164\x69\146\151\143\x61\164\145" => "\x6d\x6f\x5f\157\160\164\151\x6f\156\163\x5f\x65\x6e\x75\155\x5f\x63\165\x73\x74\157\x6d\137\x63\x65\162\164\151\146\151\x63\141\x74\145", "\x44\x6f\x6d\141\151\x6e\x5f\122\145\163\164\162\151\143\164\151\157\156" => "\x6d\x6f\137\x6f\160\x74\151\x6f\156\x73\137\145\x6e\165\155\x5f\144\x6f\155\x61\x69\156\x5f\162\145\163\x74\x72\x69\143\x74\x69\157\x6e", "\x43\x75\163\x74\x6f\x6d\x5f\115\145\x73\x73\141\x67\x65" => "\155\157\x5f\157\x70\x74\151\x6f\156\163\x5f\x65\x6e\x75\155\x5f\x63\x75\x73\164\157\x6d\x5f\155\145\x73\x73\x61\147\x65\x73", "\124\x65\x73\164\137\103\x6f\156\x66\151\147\x75\162\141\164\151\157\156" => "\155\157\x5f\157\160\164\x69\157\x6e\163\x5f\x65\156\165\155\137\164\145\163\x74\137\143\157\156\146\151\147\x75\x72\x61\x74\x69\157\156", "\x4d\x75\x6c\x74\x69\x70\x6c\145\40\x45\x6e\x76\x69\x72\x6f\156\x6d\x65\x6e\164\x73" => "\155\x6f\137\x6f\160\164\151\157\156\163\137\x65\x6e\166\151\162\x6f\156\x6d\145\x6e\164\x73", "\x4d\145\x74\141\x64\x61\164\141\137\x53\x79\x6e\143" => "\x6d\157\x5f\x6f\160\164\151\x6f\x6e\163\x5f\145\x6e\x75\155\x5f\163\145\162\x76\151\143\145\137\160\162\157\166\151\144\145\162\x5f\x75\x70\154\x6f\x61\144\137\155\x65\x74\x61\144\x61\164\141", "\101\x64\x76\x61\x6e\x63\145\144\x5f\x73\x65\164\x74\151\x6e\147\163" => "\x6d\157\x5f\x6f\160\x74\151\157\x6e\x73\137\141\164\x74\162\x5f\x72\x6f\x6c\145\137\141\x64\166\141\x6e\x63\145\144\137\x73\145\164\x74\151\x6e\147\x73", "\117\x72\147\141\156\151\163\141\x74\151\157\x6e\40\104\x65\164\x61\x69\154\x73" => "\115\x6f\x5f\123\141\155\x6c\137\117\x72\147\141\x6e\151\x7a\141\x74\151\x6f\x6e\x5f\115\145\164\141\x74\141\144\x61\x74\x61\137\x4f\160\x74\151\x6f\x6e\x73", "\x53\x68\x6f\167\40\x53\123\x4f\x20\125\163\145\162\163" => "\x6d\x6f\137\x73\x61\x6d\154\137\x6f\x70\164\151\x6f\x6e\137\x63\x6f\156\163\164\141\x6e\164\x73")));
function miniorange_keep_configuration_saml()
{
    echo "\74\144\x69\x76\40\143\x6c\x61\163\x73\75\42\155\x6f\137\163\x61\x6d\x6c\137\x73\165\160\160\157\x72\x74\137\x6c\141\171\157\x75\164\42\x20\151\x64\75\42\155\157\x5f\163\141\x6d\x6c\x5f\153\145\x65\160\x5f\x63\x6f\156\x66\151\147\165\x72\141\164\151\x6f\156\137\x69\156\x74\141\x63\x74\x22\x3e\xd\12\x20\x20\40\x20\40\40\x20\40\x3c\144\151\x76\40\163\164\x79\154\x65\x3d\x22\160\141\144\x64\x69\x6e\147\x2d\x72\x69\x67\150\x74\72\61\x30\160\170\x3b\160\x61\x64\x64\x69\156\x67\55\x62\157\x74\164\157\x6d\x3a\x32\64\160\170\42\x3e\15\xa\40\x20\x20\x20\x20\x20\40\40\x3c\150\x33\76\120\154\x75\147\151\156\40\103\157\156\x66\151\147\x75\x72\x61\x74\x69\157\156\163\74\x2f\150\x33\x3e\74\150\x72\x3e\x3c\142\x72\x2f\x3e\15\xa\x9\x9\74\146\x6f\x72\155\40\156\x61\x6d\x65\x3d\42\146\42\x20\x6d\x65\x74\x68\x6f\144\x3d\42\x70\157\163\x74\42\40\x61\143\164\151\157\x6e\75\42\42\x20\151\144\75\x22\163\145\164\164\x69\x6e\147\163\x5f\x69\x6e\x74\x61\x63\164\42\x3e";
    wp_nonce_field("\155\157\x5f\x73\x61\x6d\x6c\137\153\145\x65\x70\137\163\145\164\164\x69\x6e\147\163\137\157\x6e\x5f\144\x65\154\145\x74\x69\x6f\x6e");
    echo "\74\x69\156\x70\165\x74\40\164\x79\160\x65\75\x22\x68\x69\x64\x64\x65\156\x22\40\x6e\x61\x6d\x65\75\42\x6f\x70\x74\x69\x6f\156\x22\40\x76\141\x6c\x75\145\75\42\155\x6f\x5f\163\141\155\154\x5f\x6b\145\145\160\x5f\x73\x65\164\164\151\156\x67\x73\x5f\x6f\156\137\x64\145\154\x65\164\x69\157\156\x22\x2f\x3e\15\xa\x9\11\74\x6c\141\x62\145\x6c\x20\x63\154\x61\x73\x73\x3d\x22\x73\167\151\164\143\x68\42\x3e\15\12\x9\11\x3c\151\x6e\x70\165\164\x20\164\x79\x70\145\75\x22\143\150\145\143\x6b\142\157\x78\x22\x20\156\x61\x6d\145\x3d\42\x6d\157\137\x73\141\x6d\x6c\137\x6b\x65\145\x70\137\163\145\164\x74\x69\156\147\x73\x5f\x69\x6e\164\141\143\x74\x22\x20";
    checked(get_option("\x6d\157\137\163\x61\155\x6c\137\153\145\145\160\x5f\163\x65\164\x74\x69\156\147\x73\137\157\156\x5f\x64\145\154\145\x74\x69\157\156") == "\164\x72\x75\145");
    echo "\40" . esc_html(Mo_License_Service::is_customer_license_valid(true)) . "\40\x6f\x6e\143\x68\x61\x6e\147\x65\75\x22\144\157\x63\165\155\145\x6e\164\56\147\145\164\105\154\x65\155\145\x6e\x74\102\171\x49\144\x28\47\x73\145\164\164\151\156\x67\x73\137\151\156\164\141\143\x74\x27\x29\56\x73\165\142\x6d\x69\x74\x28\x29\73\42\57\x3e\xd\12\x9\11\x3c\163\x70\141\x6e\x20\143\154\x61\163\x73\75\42\x73\154\151\x64\x65\x72\40\162\x6f\x75\x6e\144\42\x3e\74\57\x73\x70\141\156\x3e\xd\12\11\11\74\x2f\154\x61\x62\145\154\76\xd\xa\11\11\x3c\163\160\x61\156\x20\x73\x74\171\154\x65\75\x22\x70\141\x64\x64\x69\156\147\55\x6c\145\146\x74\x3a\x35\x70\170\x3b\146\157\156\164\x2d\x73\x69\172\145\72\61\x36\x70\170\x22\x3e\74\x62\x3e\x4b\145\x65\160\x20\x53\145\x74\x74\151\156\147\163\40\x49\x6e\164\x61\x63\x74\x3c\x2f\142\x3e\74\57\x73\160\141\x6e\x3e\74\x62\162\57\x3e\x3c\x62\x72\57\76\xd\12\x9\11\105\156\x61\x62\154\151\156\147\x20\x74\150\x69\163\x20\167\157\165\x6c\144\40\153\145\145\160\x20\x79\x6f\165\x72\40\143\157\156\x66\151\x67\x75\162\141\x74\x69\157\156\163\x20\151\156\164\x61\x63\164\x20\145\166\145\156\40\167\x68\145\156\x20\x74\x68\145\x20\160\x6c\165\147\x69\x6e\40\x69\x73\x20\165\x6e\x69\x6e\x73\x74\x61\154\154\145\x64\56\15\xa\x20\40\40\x20\40\40\x20\x20\x3c\57\146\157\162\155\x3e";
    if (!EnvironmentHelper::isSelectedEnvironmentDefault()) {
        goto XG;
    }
    echo "\x3c\142\162\x20\x2f\x3e\x3c\x62\162\40\57\x3e\15\xa\11\11\74\x66\x6f\x72\155\40\155\x65\x74\150\x6f\144\x3d\42\x70\x6f\x73\164\42\x20\x69\x64\x3d\x22\x69\155\160\x6f\x72\x74\137\x63\157\x6e\x66\151\147\x22\x20\141\143\x74\x69\x6f\156\75\x22" . esc_url(admin_url()) . "\x61\144\x6d\x69\x6e\x2e\x70\x68\160\77\x70\141\147\x65\75\155\x6f\x5f\x73\x61\x6d\x6c\x5f\163\x65\x74\x74\x69\x6e\x67\x73\46\x74\141\142\x3d\163\x61\166\145" . "\x22\x20\145\156\x63\164\171\160\x65\75\42\x6d\x75\x6c\x74\x69\160\x61\x72\x74\57\x66\157\162\x6d\55\x64\141\x74\x61\42\x3e";
    wp_nonce_field("\155\x6f\x5f\x73\x61\x6d\x6c\137\151\155\160\x6f\x72\164");
    echo "\74\151\x6e\160\165\164\x20\x74\171\x70\x65\x3d\x22\x68\x69\144\x64\x65\156\x22\40\156\x61\155\x65\x3d\42\157\x70\164\151\x6f\x6e\42\x20\x76\141\154\165\145\x3d\42\155\x6f\x5f\x73\x61\155\154\x5f\151\155\x70\x6f\x72\164\x22\40\57\x3e\15\12\x9\11\74\164\x61\142\154\145\x3e\xd\xa\x9\11\74\x74\162\76\x3c\x74\144\76\x3c\x73\x70\x61\x6e\40\x73\x74\171\x6c\x65\75\42\146\x6f\x6e\x74\x2d\163\x69\172\x65\72\x31\66\x70\x78\42\x3e\74\x62\76\x49\x6d\160\157\162\164\x20\103\157\156\146\151\147\x75\x72\141\164\151\x6f\x6e\x73\74\57\x62\x3e\74\57\163\x70\x61\x6e\x3e\x3c\57\x74\x64\x3e\x3c\x2f\164\x72\x3e\xd\xa\11\x9\74\164\x72\76\74\164\x64\76\x3c\x62\x72\x2f\76\x3c\x2f\x74\144\76\x3c\57\x74\162\76\xd\xa\x9\11\74\x74\162\76\x3c\x74\144\x3e\74\x69\156\x70\165\164\x20\x74\171\160\x65\x3d\42\146\x69\154\x65\x22\40\x6e\x61\155\145\75\42\143\157\156\146\x69\x67\165\162\x61\164\x69\157\x6e\137\146\151\154\145\x22\40\x69\144\75\x22\143\157\156\146\151\147\x75\x72\141\x74\151\x6f\156\137\146\151\154\x65\x22\x20" . esc_html(Mo_License_Service::is_customer_license_valid(true)) . "\x3e\x3c\57\x74\x64\76\15\12\11\x9\74\164\x64\x3e\74\151\156\x70\165\x74\40\x74\x79\x70\145\x3d\x22\163\165\x62\x6d\x69\164\42\x20\x6e\141\x6d\x65\75\42\163\165\142\x6d\151\x74\42\x20\163\x74\171\x6c\145\x3d\x22\x77\x69\x64\x74\x68\72\x20\141\165\x74\x6f\x22\40\x63\x6c\x61\163\163\x3d\42\x62\x75\164\x74\157\156\x20\x62\165\164\x74\x6f\x6e\55\x70\x72\x69\x6d\x61\162\171\40\x62\x75\x74\164\x6f\156\55\154\141\162\x67\x65\x22\x20\166\141\154\165\145\75\42\x49\155\160\x6f\162\x74\x22\x20" . esc_html(Mo_License_Service::is_customer_license_valid(true)) . "\x2f\x3e\74\x2f\164\144\76\74\x2f\x74\x72\76\xd\xa\x9\11\15\xa\x9\11\x3c\57\164\141\x62\154\x65\x3e";
    XG:
    echo "\74\142\162\x3e\74\x62\162\x3e\xd\xa\x9\74\x2f\x64\151\166\76\15\12\x3c\x2f\x66\157\x72\x6d\x3e\15\xa\x3c\x2f\144\151\166\x3e";
}
function miniorange_import_export($KJ = false, $Z_ = false)
{
    if (Mo_License_Service::is_customer_license_verified()) {
        goto XO;
    }
    return;
    XO:
    if (!$KJ) {
        goto Eh;
    }
    $_POST["\157\160\x74\151\x6f\156"] = "\x6d\x6f\x5f\163\x61\x6d\x6c\x5f\145\x78\160\x6f\x72\164";
    Eh:
    if (empty($_POST["\x6f\160\164\151\x6f\156"])) {
        goto nQ;
    }
    if ($_POST["\x6f\x70\x74\151\157\156"] == "\155\157\137\163\141\x6d\154\x5f\145\x78\160\x6f\x72\x74") {
        goto a6;
    }
    if ($_POST["\157\x70\x74\x69\157\156"] == "\x6d\157\x5f\163\141\x6d\x6c\137\151\x6d\x70\x6f\162\x74" && check_admin_referer("\155\x6f\x5f\163\x61\x6d\154\137\x69\x6d\x70\157\162\164")) {
        goto mR;
    }
    if ($_POST["\x6f\160\x74\x69\x6f\156"] == "\x6d\x6f\137\x73\x61\155\154\x5f\153\x65\145\160\x5f\163\145\x74\164\x69\x6e\147\x73\137\157\156\137\x64\x65\x6c\145\x74\x69\x6f\156" && check_admin_referer("\x6d\x6f\x5f\163\x61\155\x6c\x5f\x6b\x65\145\160\137\163\x65\164\x74\x69\156\147\163\x5f\157\x6e\x5f\144\x65\x6c\x65\164\151\x6f\156")) {
        goto UG;
    }
    goto L4;
    a6:
    if (Mo_License_Service::is_customer_license_valid()) {
        goto Zu;
    }
    update_option("\155\157\x5f\x73\141\155\x6c\137\155\145\x73\x73\141\x67\145", "\123\x6f\155\145\164\150\151\x6e\x67\x20\167\x65\156\x74\x20\167\162\x6f\x6e\x67\x20\x77\150\151\154\x65\x20\x70\x72\157\x63\145\x73\163\151\156\147\x20\x74\x68\151\163\40\162\145\x71\165\145\163\x74\56");
    SAMLSPUtilities::mo_saml_show_error_message();
    return;
    Zu:
    if ($KJ && $Z_) {
        goto La;
    }
    $f_ = check_admin_referer("\x6d\157\x5f\163\x61\x6d\x6c\x5f\x65\x78\160\x6f\162\164");
    goto u9;
    La:
    $f_ = check_admin_referer("\x6d\157\x5f\163\141\155\154\137\x63\x6f\x6e\x74\141\x63\164\137\165\163\137\x71\165\x65\162\x79\137\x6f\160\164\x69\x6f\156");
    u9:
    if (!$f_) {
        goto b2;
    }
    $dM = unserialize(Tab_Class_Names);
    if (!$Z_) {
        goto Dh;
    }
    unset($dM["\115\x75\x6c\x74\x69\160\x6c\145\x20\x45\x6e\166\151\x72\157\x6e\155\145\156\x74\x73"]);
    Dh:
    $gy = array();
    foreach ($dM as $R2 => $EB) {
        $gy[$R2] = mo_get_configuration_array($EB);
        fA:
    }
    C7:
    $gy["\x56\145\162\163\151\x6f\156\x5f\x64\145\160\145\x6e\x64\x65\x6e\x63\x69\x65\x73"] = mo_get_version_informations();
    $Dy = phpversion();
    if (substr($Dy, 0, 3) === "\x35\56\x33") {
        goto G2;
    }
    $q7 = json_encode($gy, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    goto HA;
    G2:
    $q7 = json_encode($gy, JSON_PRETTY_PRINT);
    HA:
    if (!$Z_) {
        goto lh;
    }
    return $q7;
    lh:
    header("\103\x6f\x6e\164\x65\x6e\x74\55\104\151\163\x70\x6f\163\x69\164\151\157\x6e\72\40\141\x74\x74\x61\143\x68\155\x65\x6e\x74\x3b\40\x66\151\154\x65\x6e\x61\x6d\145\x3d\x6d\x69\x6e\x69\x6f\162\141\156\x67\x65\55\163\141\x6d\x6c\x2d\x63\157\x6e\x66\x69\x67\x2e\x6a\x73\157\156");
    echo $q7;
    exit;
    b2:
    goto L4;
    mR:
    if (Mo_License_Service::is_customer_license_valid()) {
        goto B5;
    }
    update_option("\155\x6f\137\163\x61\155\154\x5f\x6d\145\163\163\141\x67\x65", "\x53\157\x6d\145\x74\x68\151\x6e\147\40\x77\145\156\164\40\x77\x72\x6f\x6e\x67\40\167\x68\x69\154\145\x20\x70\x72\x6f\143\145\x73\163\x69\156\147\x20\164\150\x69\x73\x20\162\x65\x71\x75\145\163\x74\x2e");
    SAMLSPUtilities::mo_saml_show_error_message();
    return;
    B5:
    if (!function_exists("\167\x70\x5f\x68\141\156\144\x6c\145\x5f\x75\x70\154\157\141\x64")) {
        require_once ABSPATH . Mo_Saml_WordPress_Files::MO_SAML_WP_ADMIN_FILE;
    }
    $zp = $_FILES["\x63\x6f\156\146\x69\x67\165\x72\141\164\151\157\x6e\137\x66\x69\154\145"]["\164\171\x70\145"];
    $wY = substr($zp, strpos($zp, "\x2f") + 1);
    if (!($wY != "\152\x73\157\156")) {
        goto VZ;
    }
    update_option("\155\157\x5f\x73\x61\155\x6c\137\155\x65\163\163\x61\x67\145", "\x50\154\x65\x61\x73\145\40\x75\x70\x6c\157\141\144\40\141\40\166\141\x6c\151\x64\40\x63\157\x6e\x66\x69\x67\x75\x72\x61\164\x69\157\x6e\x20\x66\151\154\x65\x20\x69\x6e\x20\x2e\152\163\157\x6e\x20\x66\x6f\x72\x6d\141\x74");
    SAMLSPUtilities::mo_saml_show_error_message();
    return;
    VZ:
    if (empty($_FILES["\143\x6f\156\146\151\x67\165\x72\141\x74\151\x6f\156\137\146\151\154\x65"]["\x74\155\160\x5f\156\x61\x6d\145"])) {
        goto ME;
    }
    $tH = @file_get_contents($_FILES["\143\x6f\x6e\x66\151\147\165\x72\141\x74\151\157\x6e\137\146\151\x6c\145"]["\164\155\160\137\156\141\155\145"]);
    $gy = json_decode($tH, true);
    $iZ = !empty($gy["\x56\x65\162\163\x69\157\156\137\x64\145\160\145\156\x64\x65\x6e\143\x69\145\163"]["\x50\x6c\x75\147\x69\x6e\x5f\x76\x65\x72\x73\x69\x6f\x6e"]) ? $gy["\126\145\162\x73\x69\157\156\137\144\145\x70\145\156\144\x65\156\x63\151\x65\163"]["\x50\154\x75\x67\x69\156\137\166\x65\x72\x73\x69\x6f\x6e"] : '';
    if (!empty($iZ)) {
        goto aw;
    }
    update_option("\x6d\157\x5f\x73\x61\x6d\x6c\x5f\155\145\163\x73\x61\147\145", "\120\154\165\147\151\x6e\40\x76\145\162\163\x69\x6f\x6e\x20\x69\163\40\151\x6e\166\x61\x6c\151\144\x20\151\x6e\x20\x75\x70\154\157\x61\144\x65\144\x20\143\157\x6e\146\x69\x67\x75\162\x61\x74\x69\157\156\40\x66\151\154\x65");
    SAMLSPUtilities::mo_saml_show_error_message();
    return;
    goto SC;
    aw:
    if (version_compare($iZ, "\x32\61\56\x30\56\60") < 0) {
        goto nt;
    }
    mo_update_configuration_array($gy, false);
    goto ci;
    nt:
    mo_update_configuration_array($gy, true);
    ci:
    SC:
    ME:
    goto L4;
    UG:
    if (Mo_License_Service::is_customer_license_valid()) {
        goto i_;
    }
    update_option("\x6d\157\137\x73\x61\155\x6c\x5f\155\x65\x73\x73\141\147\x65", "\123\157\155\145\x74\150\151\x6e\147\x20\167\145\x6e\164\x20\167\x72\x6f\156\x67\x20\167\150\151\154\x65\x20\x70\162\157\x63\x65\163\163\151\x6e\x67\x20\x74\x68\151\163\40\162\x65\161\x75\x65\x73\x74\x2e");
    SAMLSPUtilities::mo_saml_show_error_message();
    return;
    i_:
    if (!empty($_POST["\x6d\157\137\163\x61\x6d\x6c\x5f\153\x65\145\160\137\163\x65\x74\164\x69\x6e\147\x73\x5f\x69\156\164\x61\143\164"])) {
        goto TF;
    }
    update_option("\155\x6f\x5f\x73\x61\x6d\154\x5f\x6b\145\145\x70\x5f\x73\145\164\164\151\156\x67\163\137\157\x6e\137\x64\145\x6c\x65\164\151\x6f\x6e", '');
    goto Mw;
    TF:
    update_option("\x6d\157\x5f\163\141\x6d\x6c\137\x6b\145\145\160\x5f\x73\x65\164\164\151\x6e\x67\163\137\x6f\156\x5f\x64\x65\x6c\x65\x74\x69\157\x6e", "\x74\162\165\x65");
    Mw:
    update_option("\155\157\137\163\141\x6d\x6c\137\x6d\145\x73\x73\141\147\x65", "\x4b\x65\x65\160\40\123\x65\x74\x74\151\156\147\163\x20\x49\x6e\164\141\143\x74\x20\x6f\x70\164\151\157\x6e\x20\165\160\144\x61\164\145\x64");
    SAMLSPUtilities::mo_saml_show_success_message();
    L4:
    nQ:
}
function mo_get_configuration_array($Pb, $uG = false)
{
    $Ig = call_user_func($Pb . "\x3a\x3a\x67\145\x74\103\157\x6e\x73\164\141\156\164\163");
    if (!$uG) {
        goto L8;
    }
    return $Ig;
    L8:
    $jD = array();
    foreach ($Ig as $R2 => $EB) {
        $yK = mo_get_option_values($Pb, $EB);
        $yK = maybe_unserialize($yK);
        if ("\105\156\166\x69\162\x6f\156\x6d\x65\156\x74\137\117\142\x6a\145\x63\164\163" === $R2) {
            goto PC;
        }
        $jD[$R2] = $yK;
        goto lS;
        PC:
        if (!empty($yK)) {
            goto zl;
        }
        $yK = array();
        zl:
        foreach ($yK as $iH => $Ft) {
            $jD[$R2][$iH]["\x77\160\137\163\151\164\145\x5f\x75\162\154"] = $Ft->getWpSiteUrl();
            $jD[$R2][$iH]["\x70\154\165\x67\151\156\x5f\163\145\x74\164\x69\x6e\x67\163"] = $Ft->getPluginSettings();
            Rk:
        }
        eR:
        lS:
        bj:
    }
    g7:
    return $jD;
}
function mo_get_option_values($Pb, $cH)
{
    if ("\155\x6f\x5f\x6f\x70\x74\x69\x6f\x6e\x73\x5f\x65\156\165\155\137\143\165\163\164\x6f\155\x5f\x63\x65\162\x74\x69\146\x69\143\141\x74\x65" === $Pb || "\155\x6f\x5f\157\160\164\x69\157\156\x73\137\145\156\166\151\x72\157\x6e\155\x65\x6e\164\x73" === $Pb) {
        goto ou;
    }
    $EB = EnvironmentHelper::getOptionForSelectedEnvironment($cH, false, EnvironmentHelper::getCurrentEnvironment());
    goto YV;
    ou:
    $EB = get_option($cH);
    YV:
    return $EB;
}
function mo_update_configuration_array($gy, $PI)
{
    if (mo_saml_check_required_fields($gy, $PI)) {
        goto N2;
    }
    update_option("\155\x6f\137\163\x61\x6d\x6c\137\x6d\145\163\x73\x61\147\x65", "\x50\154\x65\141\x73\x65\40\151\155\x70\x6f\x72\x74\40\x61\x20\166\x61\154\x69\x64\40\112\123\x4f\116\40\146\x69\x6c\x65\56\40\x52\145\161\165\151\162\145\x64\40\x66\x69\145\154\144\163\40\141\x72\145\x20\x65\155\160\164\171\x20\157\162\x20\151\156\166\x61\154\151\x64\56");
    SAMLSPUtilities::mo_saml_show_error_message();
    return;
    N2:
    $iZ = $gy["\126\145\x72\x73\151\157\156\x5f\144\145\x70\145\156\144\x65\156\143\151\x65\x73"]["\120\154\x75\147\x69\x6e\x5f\166\145\x72\163\x69\x6f\x6e"];
    $uZ = isset($gy["\x4d\165\154\x74\x69\x70\x6c\145\x20\105\156\166\x69\x72\157\156\x6d\145\156\164\163"]) && version_compare($iZ, "\62\x35\x2e\x31\56\x36", "\x3e\x3d") ? true : false;
    SAMLSPUtilities::mo_saml_delete_plugin_option($uZ);
    $z_ = array_change_key_case($gy["\123\x65\162\166\x69\x63\145\137\x50\162\157\166\151\x64\x65\162"], CASE_UPPER);
    $dM = unserialize(Tab_Class_Names);
    foreach ($dM as $pT => $Pb) {
        if (!empty($gy[$pT])) {
            goto Fh;
        }
        goto dQ;
        Fh:
        $VV = $gy[$pT];
        if (!("\115\x75\x6c\164\151\x70\x6c\145\40\x45\156\x76\151\x72\x6f\x6e\155\145\156\x74\x73" === $pT)) {
            goto qc;
        }
        if (!$uZ) {
            goto c1;
        }
        import_environment_configurations($VV, $Pb);
        c1:
        goto dQ;
        qc:
        if ($PI) {
            goto Bo;
        }
        import_all_configs($VV, $Pb);
        goto el;
        Bo:
        $BB = $z_["\111\104\105\x4e\x54\111\x54\x59\x5f\116\101\x4d\x45"];
        switch ($pT) {
            case "\123\x53\x4f\x5f\114\x6f\147\x69\x6e":
                import_sso_login_config($VV, $BB, $Pb);
                goto hm;
            case "\x53\x65\162\x76\151\143\145\137\120\x72\157\166\151\144\x65\x72":
                import_single_idp_config($VV, $BB);
                goto hm;
            case "\101\x74\x74\x72\x69\142\x75\164\x65\137\115\141\x70\x70\151\x6e\x67":
                import_attribute_mapping($VV, $BB);
                goto hm;
            case "\x52\x6f\x6c\145\137\x4d\141\x70\160\151\156\147":
                import_role_mapping($VV, $BB);
                goto hm;
            case "\104\x6f\x6d\x61\x69\156\x5f\122\x65\x73\164\x72\151\143\164\151\157\156":
                import_domain_restriction($VV, $BB);
                goto hm;
            case "\115\x65\x74\141\144\141\x74\x61\x5f\123\x79\x6e\143":
                mo_saml_import_metadata_sync_config($VV, $BB);
                goto hm;
            case "\123\150\157\x77\40\x53\x53\117\40\x55\163\x65\x72\x73":
                mo_saml_import_show_sso_user_config($VV);
                goto hm;
            case "\124\x65\163\164\x5f\x43\x6f\156\146\x69\147\x75\162\x61\x74\x69\157\x6e":
                mo_saml_import_test_configurations($VV, $BB);
                goto hm;
        }
        UH:
        hm:
        el:
        dQ:
    }
    Vv:
    SAMLSPUtilities::mo_saml_enable_metadata_sync_for_all_idps();
    update_option("\155\157\x5f\x73\x61\155\154\x5f\x6d\x65\x73\163\141\x67\145", "\x49\155\160\157\162\x74\x20\123\165\x63\143\x65\x73\x73\x66\x75\x6c\x21");
    SAMLSPUtilities::mo_saml_show_success_message();
}
function mo_saml_import_test_configurations($VV, $BB)
{
    $VV = array_change_key_case($VV, CASE_LOWER);
    $BM[$BB] = $VV["\x74\x65\x73\x74\137\x63\157\156\x66\151\147\x5f\141\x74\164\x69\x62\x75\x74\145\163"] ?? array();
    $s6 = new EnvironmentDao(EnvironmentHelper::getCurrentEnvironment());
    $s6->mo_save_environment_settings("\x6d\157\x5f\163\x61\155\x6c\x5f\164\145\163\x74\x5f\143\157\x6e\x66\x69\147\x5f\x61\x74\x74\162\x73", $BM);
}
function mo_saml_import_show_sso_user_config($VV)
{
    $s6 = new EnvironmentDao(EnvironmentHelper::getCurrentEnvironment());
    $s6->mo_save_environment_settings("\x6d\x6f\x5f\163\141\x6d\154\137\x73\x73\157\x5f\163\x68\157\167\137\x75\x73\x65\x72", mo_get_config_option_value($VV, "\x53\x48\x4f\127\x5f\x53\123\117\137\125\123\x45\x52"));
}
function import_environment_configurations($VV, $Pb)
{
    $WM = array();
    foreach ($VV as $R2 => $EB) {
        if (defined("{$Pb}\72\x3a{$R2}")) {
            goto Sb;
        }
        goto l9;
        Sb:
        $VH = constant("{$Pb}\x3a\72{$R2}");
        if ("\105\x6e\x76\x69\x72\157\x6e\x6d\x65\156\164\x5f\x4f\x62\x6a\x65\143\164\x73" === $R2) {
            goto WL;
        }
        update_option($VH, $EB);
        goto Rj;
        WL:
        foreach ($EB as $sl => $EM) {
            $Ft = new EnvironmentObject($EM["\x77\x70\137\x73\x69\x74\145\137\165\162\x6c"]);
            $Ft->setPluginSettings($EM["\x70\154\x75\x67\151\x6e\x5f\163\145\x74\x74\151\x6e\x67\x73"]);
            $WM = array_merge($WM, array($sl => $Ft));
            kt:
        }
        aI:
        update_option($VH, $WM);
        Rj:
        l9:
    }
    Kb:
}
function import_domain_restriction($xH, $BB)
{
    $xH = array_change_key_case($xH, CASE_LOWER);
    $BW = array("\x65\156\141\x62\154\145\137\x64\x6f\155\141\x69\x6e\x5f\162\145\x73\x74\x72\x69\143\164\x69\157\x6e" => mo_get_config_option_value($xH, "\x65\x6e\x61\x62\154\145\137\x64\x6f\x6d\141\x69\x6e\x5f\x72\x65\163\164\x72\x69\x63\164\x69\157\x6e\137\154\x6f\147\x69\156"), "\x61\154\154\x6f\167\137\144\145\156\171\x5f\x6c\x6f\147\x69\156" => mo_get_config_option_value($xH, "\141\154\154\157\x77\137\144\x65\x6e\x79\x5f\165\x73\x65\162\137\x77\151\x74\x68\x5f\x64\x6f\x6d\141\x69\156"), "\145\155\x61\151\x6c\137\x64\157\155\x61\151\156\x73" => mo_get_config_option_value($xH, "\145\155\x61\x69\154\x5f\144\x6f\x6d\x61\x69\x6e\163"));
    $sp = array($BB => $BW);
    $s6 = new EnvironmentDao(EnvironmentHelper::getCurrentEnvironment());
    $s6->mo_save_environment_settings("\x73\x61\x6d\x6c\137\x64\157\155\141\151\156\137\x72\x65\163\x74\162\151\x63\164\151\157\x6e", $sp);
}
function mo_saml_import_metadata_sync_config($i1, $BB)
{
    $i1 = array_change_key_case($i1, CASE_LOWER);
    $r1 = array("\165\162\x6c\x5f\146\x6f\162\x5f\163\x79\x6e\x63" => mo_get_config_option_value($i1, "\x73\141\x6d\x6c\x5f\x6d\x65\164\141\144\x61\x74\141\x5f\165\162\154\137\146\157\x72\137\x73\x79\x6e\x63"), "\163\x79\x6e\x63\x5f\x63\145\162\x74\151\146\151\x63\141\x74\145\x5f\x6d\145\x74\x61\144\x61\164\x61" => mo_get_config_option_value($i1, "\x73\141\x6d\x6c\x5f\x73\x79\x6e\x63\x5f\143\x65\x72\164\x69\x66\151\x63\141\164\145\137\x6d\145\x74\x61\x64\141\164\x61"));
    $Mh = array($BB => $r1);
    $s6 = new EnvironmentDao(EnvironmentHelper::getCurrentEnvironment());
    $s6->mo_save_environment_settings("\x73\141\x6d\154\x5f\x6d\x65\x74\141\x64\141\164\141\137\163\x79\x6e\143", $Mh);
}
function import_all_configs($VV, $Pb)
{
    $s6 = new EnvironmentDao(EnvironmentHelper::getCurrentEnvironment());
    foreach ($VV as $R2 => $EB) {
        if (!("\111\x64\x65\x6e\164\x69\x74\171\x5f\x6e\x61\155\145" === $R2 && is_array($EB))) {
            goto Yv;
        }
        SAMLSPUtilities::mo_saml_update_selected_idp($EB, true);
        Yv:
        if (defined("{$Pb}\x3a\x3a{$R2}")) {
            goto L2;
        }
        goto Qz;
        L2:
        $VH = constant("{$Pb}\72\72{$R2}");
        $s6->mo_save_environment_settings($VH, $EB);
        Qz:
    }
    hL:
}
function import_sso_login_config($R3, $BB, $Pb)
{
    $s6 = new EnvironmentDao(EnvironmentHelper::getCurrentEnvironment());
    $R3 = array_change_key_case($R3, CASE_LOWER);
    $u7 = isset($R3["\x73\x73\x6f\137\142\x75\164\164\157\156"]) ? "\163\163\x6f\x5f\142\x75\x74\x74\x6f\156" : "\x61\x64\x64\x5f\163\163\157\137\x62\165\164\164\x6f\156";
    $KL = array("\141\x64\x64\137\142\165\164\164\157\156\x5f\x77\x70\x5f\x6c\157\x67\151\x6e" => mo_get_config_option_value($R3, $u7, "\146\x61\x6c\x73\x65"), "\165\163\145\137\x62\x75\x74\164\x6f\x6e\137\x61\x73\x5f\x73\x68\157\x72\x74\143\x6f\x64\145" => mo_get_config_option_value($R3, "\165\163\145\x5f\142\x75\164\x74\157\x6e\x5f\141\163\137\163\150\157\162\x74\143\157\x64\x65", "\146\x61\x6c\x73\145"), "\165\163\145\137\x62\165\164\x74\157\x6e\x5f\x61\163\137\x77\x69\x64\147\x65\x74" => mo_get_config_option_value($R3, "\165\163\x65\137\x62\165\164\164\x6f\156\137\x61\x73\137\167\151\x64\x67\145\x74", "\x66\x61\154\163\145"), "\142\165\x74\164\x6f\x6e\x5f\163\151\x7a\145" => mo_get_config_option_value($R3, "\163\x73\157\137\x62\165\x74\164\157\x6e\x5f\x73\151\x7a\x65", "\65\60"), "\x62\165\x74\x74\157\x6e\x5f\167\x69\144\x74\x68" => mo_get_config_option_value($R3, "\163\x73\157\137\x62\x75\x74\x74\x6f\156\x5f\167\151\x64\x74\x68", "\x31\60\x30"), "\x62\x75\x74\x74\x6f\156\137\150\x65\151\147\150\164" => mo_get_config_option_value($R3, "\x73\x73\157\x5f\x62\165\x74\164\157\x6e\x5f\150\145\x69\x67\x68\164", "\x35\60"), "\x62\165\164\164\x6f\x6e\137\143\x75\162\166\x65" => mo_get_config_option_value($R3, "\163\x73\157\x5f\x62\x75\x74\x74\157\156\x5f\x63\x75\x72\166\x65", "\x35"), "\142\x75\164\x74\x6f\156\137\x63\157\x6c\157\162" => mo_get_config_option_value($R3, "\163\x73\157\137\142\165\164\x74\x6f\156\x5f\143\157\154\157\x72", "\60\x30\x38\x35\142\141"), "\142\x75\164\164\157\156\x5f\x74\145\x78\x74" => mo_get_config_option_value($R3, "\x73\x73\157\x5f\x62\165\164\x74\x6f\x6e\137\x74\x65\170\164", "\x4c\157\x67\151\x6e"), "\x62\165\x74\164\157\x6e\x5f\x74\171\160\x65" => mo_get_config_option_value($R3, "\163\163\157\137\x62\x75\164\164\157\156\x5f\x74\150\x65\x6d\x65", "\154\x6f\156\147\142\x75\x74\164\x6f\x6e"), "\x66\157\156\x74\x5f\x63\x6f\154\x6f\x72" => mo_get_config_option_value($R3, "\x73\x73\x6f\137\142\x75\x74\x74\x6f\x6e\x5f\146\157\x6e\164\137\x63\157\x6c\157\x72", "\x66\x66\x66\146\146\146"), "\x66\x6f\x6e\x74\x5f\x73\x69\172\x65" => mo_get_config_option_value($R3, "\x73\163\x6f\137\142\165\164\164\157\156\137\x66\157\x6e\x74\x5f\x73\x69\x7a\145", "\x32\60"), "\x62\165\164\x74\x6f\156\x5f\160\157\163\x69\x74\151\157\156" => mo_get_config_option_value($R3, "\x73\x73\x6f\x5f\x62\165\164\164\x6f\156\x5f\160\157\x73\x69\x74\x69\157\x6e", "\141\x62\x6f\166\145"));
    if (!(isset($R3["\x72\x65\154\x61\x79\137\163\x74\141\x74\x65"]) || isset($R3["\154\157\147\157\165\x74\137\162\x65\x6c\x61\171\137\163\164\141\164\145"]))) {
        goto uT;
    }
    $h5 = array();
    $aO = !empty($R3["\x72\145\154\x61\171\x5f\x73\x74\x61\x74\145"]) ? $R3["\162\145\x6c\141\x79\x5f\x73\164\141\x74\x65"] : '';
    $Ih = !empty($R3["\154\157\147\x6f\165\164\137\162\x65\154\x61\x79\137\x73\164\x61\164\x65"]) ? $R3["\x6c\157\147\157\165\164\137\x72\145\154\x61\x79\x5f\163\x74\x61\x74\x65"] : '';
    $h5["\154\x6f\147\x69\156\x5f\x72\x65\x6c\x61\171\x5f\163\x74\x61\164\145"]["\104\x45\106\101\125\x4c\124"] = $aO;
    $h5["\154\x6f\147\157\165\164\137\x72\x65\x6c\141\171\137\x73\164\x61\x74\x65"]["\104\105\x46\x41\125\x4c\x54"] = $Ih;
    $s6->mo_save_environment_settings("\x6d\157\137\163\141\x6d\x6c\x5f\x72\x65\154\x61\171\137\x73\x74\141\164\x65", $aO);
    $s6->mo_save_environment_settings("\155\x6f\137\163\141\x6d\154\x5f\162\x65\x6c\x61\x79\137\163\x74\141\164\x65\163", $h5);
    uT:
    if (!isset($R3["\162\x65\144\x69\162\x65\x63\164\137\151\x64\160"])) {
        goto Qr;
    }
    $s6->mo_save_environment_settings("\155\157\x5f\163\141\155\154\x5f\162\x65\144\x69\x72\145\143\164\137\144\145\146\x61\x75\154\164\x5f\151\x64\x70", $R3["\x72\x65\x64\x69\162\x65\143\164\x5f\x69\x64\x70"]);
    Qr:
    if (!isset($R3["\x66\x6f\162\x63\x65\137\141\x75\164\x68\145\x6e\164\151\143\141\x74\151\157\x6e"])) {
        goto Vm;
    }
    $s6->mo_save_environment_settings("\155\x6f\x5f\163\x61\x6d\154\x5f\x66\x6f\x72\143\145\137\141\x75\x74\x68\x65\156\164\x69\x63\141\164\151\157\156", $R3["\146\x6f\x72\143\x65\137\x61\x75\x74\x68\x65\x6e\164\x69\x63\141\164\x69\x6f\156"]);
    Vm:
    if (!isset($R3["\145\156\x61\142\x6c\x65\x5f\x61\x63\143\x65\163\x73\x5f\162\163\163"])) {
        goto ht;
    }
    $s6->mo_save_environment_settings("\x6d\157\x5f\163\141\x6d\154\137\145\156\x61\x62\x6c\145\137\x72\x73\x73\x5f\141\143\x63\x65\x73\163", $R3["\x65\x6e\x61\x62\x6c\x65\x5f\x61\x63\x63\145\163\x73\x5f\162\x73\163"]);
    ht:
    if (!isset($R3["\141\x6c\154\157\167\x5f\167\x70\137\x73\151\147\x6e\x69\156"])) {
        goto rd;
    }
    $s6->mo_save_environment_settings("\155\x6f\x5f\x73\141\155\x6c\137\141\x6c\154\157\x77\137\167\160\137\x73\x69\x67\x6e\151\156", $R3["\141\154\154\157\167\137\167\160\137\163\151\x67\x6e\151\156"]);
    rd:
    if (!isset($R3["\142\x61\143\x6b\x64\x6f\x6f\162\137\x75\162\154"])) {
        goto Po;
    }
    $s6->mo_save_environment_settings("\155\x6f\x5f\163\x61\x6d\154\x5f\142\141\143\x6b\144\x6f\x6f\x72\137\165\162\154", $R3["\142\141\x63\x6b\144\x6f\x6f\x72\137\x75\162\154"]);
    Po:
    if (!isset($R3["\x61\165\164\x6f\137\x72\x65\x64\151\x72\x65\x63\x74"])) {
        goto mh;
    }
    $s6->mo_save_environment_settings("\x6d\x6f\x5f\163\x61\155\x6c\x5f\x65\156\x61\142\154\x65\x5f\x6c\x6f\147\151\x6e\137\162\145\x64\x69\x72\x65\x63\x74", $R3["\141\x75\164\x6f\x5f\162\x65\x64\151\x72\145\143\164"]);
    mh:
    $id = array($BB => $KL);
    $s6->mo_save_environment_settings("\163\141\155\x6c\x5f\163\163\x6f\137\x62\165\x74\x74\157\x6e\137\151\144\x70", $id);
}
function import_role_mapping($D9, $BB)
{
    $D9 = array_change_key_case($D9, CASE_LOWER);
    $kP = array("\155\157\137\163\x61\155\x6c\137\x72\x65\x73\x74\x72\x69\143\x74\137\x75\x73\x65\x72\163\137\x77\151\164\150\137\147\x72\x6f\x75\160\163" => mo_get_config_option_value($D9, "\x72\x6f\154\x65\137\162\x65\163\164\162\151\x63\x74\137\165\x73\x65\x72\x73\x5f\167\x69\164\150\x5f\147\162\x6f\165\x70\x73"), "\x64\x65\146\x61\165\154\x74\137\x72\x6f\x6c\145" => mo_get_config_option_value($D9, "\x72\x6f\154\x65\137\x64\x65\x66\141\165\x6c\164\x5f\x72\157\x6c\x65"), "\144\x6f\x6e\x74\x5f\x63\162\145\x61\164\x65\137\165\x73\145\x72" => mo_get_config_option_value($D9, "\x72\157\x6c\x65\x5f\144\157\x5f\x6e\157\x74\137\x61\x75\164\157\x5f\x63\162\x65\x61\164\x65\x5f\x75\x73\145\162\x73"), "\144\157\156\164\137\x61\154\154\x6f\x77\137\x75\156\x6c\x69\x73\164\145\x64\x5f\165\x73\145\x72" => mo_get_config_option_value($D9, "\162\157\x6c\x65\137\144\157\137\x6e\x6f\x74\x5f\x61\163\x73\151\147\156\x5f\162\157\x6c\145\x5f\x75\156\154\151\x73\x74\x65\x64"), "\153\x65\x65\160\x5f\145\x78\151\x73\x74\151\156\147\137\165\163\x65\x72\163\x5f\x72\157\154\145" => mo_get_config_option_value($D9, "\x72\157\154\145\x5f\144\x6f\137\x6e\157\164\x5f\x75\x70\144\x61\x74\145\137\145\x78\151\x73\x74\151\156\147\137\x75\163\145\x72"), "\x64\x6f\156\164\x5f\141\154\154\157\167\x5f\165\163\x65\162\137\164\157\154\157\147\x69\156\x5f\x63\162\x65\141\x74\145\137\167\x69\164\x68\x5f\x67\x69\166\145\156\x5f\x67\162\157\x75\160\163" => mo_get_config_option_value($D9, "\x72\x6f\x6c\x65\x5f\144\x6f\x5f\x6e\x6f\164\137\154\157\147\151\x6e\x5f\x77\151\164\150\137\x72\x6f\154\145\163"), "\x61\x73\163\x69\x67\x6e\x5f\144\x65\146\x61\x75\154\x74\x5f\x72\157\x6c\145" => mo_get_config_option_value($D9, "\141\x73\163\151\x67\156\137\144\x65\146\x61\x75\x6c\164\x5f\x72\157\x6c\145"), "\x65\156\x61\142\154\145\x5f\x72\145\x67\x65\170" => mo_get_config_option_value($D9, "\x72\x6f\x6c\x65\137\x65\x6e\141\142\x6c\145\x5f\162\145\x67\145\170"), "\x61\154\154\x6f\x77\137\144\x65\156\x79\137\x75\163\145\x72\x5f\x61\x74\x74\x72\x69\x62\x75\164\145\163" => mo_get_config_option_value($D9, "\141\x74\x74\162\x69\142\165\x74\145\137\x72\x65\x73\x74\162\x69\143\x74\x69\x6f\x6e\x5f\x61\x6c\154\x6f\x77\x5f\x64\145\156\171"), "\x64\157\x6e\x74\x5f\143\x72\x65\x61\x74\145\x5f\156\x65\167\x5f\x75\x73\x65\x72\x73" => mo_get_config_option_value($D9, "\x64\157\156\x74\137\143\162\x65\141\x74\145\137\156\x65\x77\x5f\x75\163\145\162"), "\141\x70\160\154\171\137\162\x6f\x6c\145\x5f\x74\157\137\x61\144\x6d\x69\156" => mo_get_config_option_value($D9, "\162\x6f\x6c\x65\x5f\x75\x70\x64\141\x74\x65\137\141\144\x6d\x69\x6e\x5f\165\x73\145\x72\x5f\x72\x6f\x6c\145"));
    if (empty($D9["\x72\157\x6c\x65\x5f\155\x61\x70\160\151\156\147"])) {
        goto U4;
    }
    foreach ($D9["\x72\x6f\x6c\x65\x5f\155\x61\160\160\x69\156\x67"] as $zZ => $tC) {
        $kP[$zZ] = $tC;
        wx:
    }
    oZ:
    U4:
    $Jo = array($BB => $kP);
    $s6 = new EnvironmentDao(EnvironmentHelper::getCurrentEnvironment());
    $s6->mo_save_environment_settings("\163\x61\x6d\x6c\x5f\x69\x64\x70\137\162\157\x6c\145\137\x6d\141\160\160\x69\156\147", $Jo);
}
function import_attribute_mapping($Iu, $BB)
{
    $Iu = array_change_key_case($Iu, CASE_LOWER);
    $PQ = !empty(mo_get_config_option_value($Iu, "\x61\x74\164\x72\x69\142\x75\x74\145\x5f\x75\x70\x64\141\164\x65\137\x64\x69\x73\x70\x6c\x61\171\x5f\x6e\x61\x6d\145")) ? "\x63\x68\x65\x63\153\x65\144" : '';
    $kZ = array("\x75\163\145\x72\156\141\x6d\x65" => mo_get_config_option_value($Iu, "\141\164\164\162\151\x62\x75\164\145\x5f\165\x73\x65\162\156\141\155\145"), "\x65\x6d\141\151\x6c" => mo_get_config_option_value($Iu, "\x61\x74\164\x72\x69\x62\x75\x74\145\x5f\x65\x6d\141\151\x6c"), "\x66\151\x72\163\x74\x5f\156\x61\155\145" => mo_get_config_option_value($Iu, "\141\x74\x74\x72\151\142\165\164\145\x5f\146\151\162\163\164\x5f\x6e\x61\155\145"), "\154\141\x73\164\x5f\156\x61\x6d\145" => mo_get_config_option_value($Iu, "\141\x74\164\x72\x69\x62\x75\164\145\x5f\x6c\141\x73\x74\137\x6e\x61\x6d\145"), "\x67\162\157\165\160\x5f\x6e\141\155\x65" => mo_get_config_option_value($Iu, "\141\x74\x74\x72\151\x62\x75\164\x65\x5f\x67\162\x6f\165\160\137\x6e\141\x6d\x65"), "\x64\x69\x73\x70\x6c\x61\171\x5f\x6e\141\x6d\x65" => mo_get_config_option_value($Iu, "\141\164\164\x72\151\x62\x75\x74\145\137\x64\x69\163\160\154\141\171\137\156\x61\x6d\145"), "\156\x69\x63\153\137\x6e\x61\x6d\145" => mo_get_config_option_value($Iu, "\141\x74\x74\162\x69\x62\165\164\x65\x5f\x6e\151\143\153\x6e\141\x6d\145"), "\x64\x6f\137\x6e\x6f\164\137\x75\x70\x64\x61\164\145\x5f\144\x69\163\160\154\141\171\x5f\x6e\141\x6d\x65" => $PQ);
    $B9 = array();
    if (!(!empty($Iu["\141\164\x74\x72\x69\142\165\x74\x65\137\x63\x75\x73\164\x6f\155\x5f\155\x61\160\x70\151\156\147"]) && is_array($Iu["\141\164\x74\162\x69\142\165\x74\x65\x5f\143\165\x73\x74\157\x6d\x5f\x6d\141\160\x70\151\156\x67"]))) {
        goto W5;
    }
    foreach ($Iu["\x61\164\164\x72\x69\x62\165\x74\145\x5f\143\165\163\x74\x6f\155\x5f\x6d\x61\160\160\x69\x6e\x67"] as $R2 => $EB) {
        $B9[$R2] = $EB;
        vp:
    }
    We:
    W5:
    $s5 = array();
    if (!(!empty($Iu["\141\164\164\x72\151\142\x75\x74\x65\x5f\x73\150\x6f\167\137\x69\156\137\165\163\145\x72\137\x6d\x65\x6e\165"]) && is_array($Iu["\141\164\x74\x72\x69\142\165\x74\x65\x5f\163\x68\157\x77\137\151\x6e\x5f\165\163\145\x72\x5f\155\x65\x6e\x75"]))) {
        goto r1;
    }
    foreach ($Iu["\141\164\x74\x72\x69\x62\x75\x74\145\137\163\150\157\167\x5f\151\156\137\165\163\145\162\x5f\x6d\145\x6e\x75"] as $R2 => $EB) {
        $s5[$R2] = $EB;
        vG:
    }
    g1:
    r1:
    $Dj = array($BB => $kZ);
    $s6 = new EnvironmentDao(EnvironmentHelper::getCurrentEnvironment());
    $s6->mo_save_environment_settings("\x73\x61\155\x6c\x5f\x69\144\x70\137\x61\164\x74\162\151\142\x75\164\145\137\155\141\160\x70\x69\156\x67", $Dj);
    $de = array($BB => $B9);
    $s6->mo_save_environment_settings("\x6d\157\x5f\x73\x61\x6d\154\x5f\x63\x75\163\164\157\x6d\137\x61\x74\x74\x72\163\x5f\155\x61\x70\x70\x69\156\147", $de);
    $LC = array($BB => $s5);
    $s6->mo_save_environment_settings("\163\x61\x6d\x6c\x5f\141\x74\164\x72\x73\137\x74\x6f\x5f\x64\x69\163\x70\154\x61\x79\137\151\x64\x70", $LC);
}
function import_single_idp_config($IC, $BB)
{
    $IC = array_change_key_case($IC, CASE_LOWER);
    $dv = array("\x69\x64\x70\137\156\141\x6d\145" => mo_get_config_option_value($IC, "\x69\x64\x65\x6e\x74\x69\x74\171\x5f\156\141\x6d\x65"), "\x69\144\160\137\144\151\x73\x70\154\141\x79\x5f\x6e\x61\155\145" => mo_get_config_option_value($IC, "\151\x64\x65\x6e\164\x69\x74\171\137\156\x61\155\x65"), "\151\144\160\x5f\145\156\164\x69\x74\171\x5f\x69\x64" => mo_get_config_option_value($IC, "\151\163\x73\165\145\x72"), "\x73\163\x6f\x5f\165\162\154" => mo_get_config_option_value($IC, "\x6c\157\147\x69\x6e\137\x75\x72\x6c"), "\x73\163\x6f\137\x62\151\x6e\x64\x69\156\x67\x5f\164\171\x70\145" => mo_get_config_option_value($IC, "\x6c\157\x67\x69\x6e\x5f\142\x69\x6e\x64\151\156\x67\x5f\164\171\x70\145"), "\x73\x6c\x6f\x5f\165\162\x6c" => mo_get_config_option_value($IC, "\x6c\x6f\147\157\x75\164\137\165\x72\154"), "\x73\154\x6f\x5f\162\x65\x73\160\157\x6e\163\x65\x5f\x75\x72\154" => mo_get_config_option_value($IC, "\x6c\x6f\x67\157\x75\x74\x5f\162\145\x73\x70\x6f\x6e\163\x65\x5f\x75\x72\154"), "\163\x6c\x6f\x5f\x62\x69\x6e\144\x69\156\x67\137\x74\171\x70\x65" => mo_get_config_option_value($IC, "\x6c\x6f\147\x6f\x75\x74\x5f\x62\151\x6e\x64\151\x6e\147\137\x74\x79\160\145"), "\170\x35\x30\71\x5f\143\x65\162\164\151\146\x69\x63\x61\x74\x65" => mo_get_config_option_value($IC, "\x78\65\60\71\137\143\145\x72\x74\x69\146\151\143\x61\x74\x65"), "\x72\145\163\160\x6f\156\163\x65\137\x73\x69\x67\156\145\x64" => "\x59\145\163", "\141\163\x73\x65\162\164\x69\157\x6e\137\x73\x69\147\156\x65\144" => "\x59\x65\x73", "\x72\x65\x71\x75\145\x73\x74\x5f\163\x69\147\x6e\145\144" => mo_get_config_option_value($IC, "\162\145\161\x75\145\163\x74\137\x73\x69\x67\156\145\x64", "\x75\156\x63\x68\145\143\153\145\x64"), "\x6e\141\155\145\151\144\137\146\157\162\x6d\x61\x74" => mo_get_config_option_value($IC, "\156\x61\x6d\145\151\144\x5f\x66\x6f\162\155\141\x74"), "\x6d\157\137\x73\x61\155\154\137\x65\x6e\143\157\144\151\156\x67\137\145\156\141\142\154\x65\144" => mo_get_config_option_value($IC, "\x69\x73\137\145\x6e\x63\157\144\151\x6e\147\x5f\x65\x6e\x61\142\154\x65\x64", "\143\150\x65\x63\153\145\x64"), "\145\156\141\x62\154\145\x5f\151\144\x70" => true);
    SAMLSPUtilities::mo_saml_update_selected_idp(array($dv));
    $kq = array($BB => $dv);
    $s6 = new EnvironmentDao(EnvironmentHelper::getCurrentEnvironment());
    $s6->mo_save_environment_settings("\x73\141\x6d\x6c\x5f\151\144\x65\x6e\164\151\x74\171\x5f\160\162\x6f\x76\x69\x64\145\162\163", $kq);
    $s6->mo_save_environment_settings("\x73\x61\x6d\x6c\137\x64\x65\x66\141\x75\154\164\137\151\x64\x70", mo_get_config_option_value($IC, "\151\144\x65\x6e\164\151\164\x79\x5f\156\x61\155\145"));
}
function mo_get_config_option_value($ha, $jC, $hx = '')
{
    return !empty($ha[$jC]) ? $ha[$jC] : $hx;
}
function mo_get_version_informations()
{
    $uz = array();
    $uz["\x50\x6c\165\x67\151\156\137\166\x65\x72\x73\151\x6f\x6e"] = mo_options_plugin_constants::VERSION;
    $uz["\120\x48\x50\x5f\166\145\x72\163\x69\157\x6e"] = phpversion();
    $uz["\x57\157\x72\x64\160\x72\145\163\x73\x5f\166\145\162\x73\x69\157\x6e"] = get_bloginfo("\166\145\162\x73\151\x6f\156");
    $uz["\117\120\x45\116\137\x53\123\114"] = mo_saml_is_extension_installed("\x6f\160\x65\156\163\x73\154");
    $uz["\x43\125\x52\114"] = mo_saml_is_extension_installed("\x63\165\x72\x6c");
    $uz["\111\x43\117\x4e\x56"] = mo_saml_is_extension_installed("\144\x6f\x6d");
    $uz["\104\x4f\x4d"] = mo_saml_is_extension_installed("\144\157\x6d");
    return $uz;
}
function mo_saml_check_required_fields($hh, $so)
{
    if (!empty($hh["\123\x65\x72\x76\151\x63\x65\x5f\120\x72\x6f\166\151\x64\145\x72"])) {
        goto XA;
    }
    update_option("\x6d\x6f\137\163\141\x6d\x6c\137\x6d\145\x73\163\141\147\145", "\111\156\x76\x61\x6c\x69\144\x20\x66\151\x6c\x65\56\x20\x50\154\x65\x61\163\145\x20\x69\155\160\157\x72\164\x20\x61\x20\166\x61\x6c\x69\144\x20\112\x53\x4f\x4e\40\146\x69\x6c\x65\x2e");
    SAMLSPUtilities::mo_saml_show_error_message();
    return false;
    XA:
    if ($so) {
        goto mI;
    }
    if (!is_array($hh["\123\145\162\x76\x69\143\x65\137\x50\162\157\166\151\144\145\162"]["\x49\x64\145\156\164\151\x74\171\x5f\x6e\141\155\x65"])) {
        goto OL;
    }
    foreach ($hh["\123\x65\162\x76\151\x63\145\x5f\120\162\157\x76\x69\x64\145\162"]["\111\144\145\156\x74\151\164\171\137\x6e\141\155\x65"] as $dv) {
        $sA = SAMLSPUtilities::mo_saml_check_import_required_fields(false, $dv);
        $di = SAMLSPUtilities::mo_saml_validate_identity_provider_name(false, $dv);
        $eK = SAMLSPUtilities::mo_saml_validate_certificate(false, $dv);
        $og = SAMLSPUtilities::mo_saml_validate_login_url(false, $dv);
        if (!(!$sA || !$di || !$eK || !$og)) {
            goto Ov;
        }
        return false;
        Ov:
        z2:
    }
    sy:
    OL:
    goto bc;
    mI:
    $z_ = array_change_key_case($hh["\123\x65\x72\166\x69\x63\x65\137\x50\162\x6f\166\151\x64\x65\162"], CASE_UPPER);
    if (!(!SAMLSPUtilities::mo_saml_check_import_required_fields(true, $z_) || !SAMLSPUtilities::mo_saml_validate_identity_provider_name(true, $z_) || !SAMLSPUtilities::mo_saml_validate_certificate(true, $z_) || !SAMLSPUtilities::mo_saml_validate_login_url(true, $z_))) {
        goto Vi;
    }
    update_option("\x6d\157\x5f\x73\x61\155\x6c\x5f\x6d\145\x73\x73\x61\147\145", "\120\154\x65\141\163\x65\40\151\155\160\157\x72\x74\x20\x61\40\x76\x61\154\x69\144\40\x4a\x53\117\x4e\x20\x66\151\x6c\x65\x2e\x20\x52\x65\x71\x75\x69\x72\x65\x64\x20\146\x69\x65\x6c\x64\x73\x20\141\x72\x65\x20\x65\x6d\160\x74\x79\x20\157\x72\40\151\x6e\x76\141\154\151\144\x2e");
    SAMLSPUtilities::mo_saml_show_error_message();
    return false;
    Vi:
    bc:
    return true;
}
