<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



class Mo_Saml_Manage_User_Table_Sso_Action
{
    public function __construct()
    {
        add_action("\x61\x64\x6d\151\x6e\x5f\151\156\151\164", array($this, "\143\150\x65\x63\153\137\151\163\137\163\163\x6f\x5f\165\163\145\162\137\x74\141\x62\154\x65"));
    }
    function new_modify_user_table($Aw)
    {
        $Aw["\163\x73\157\x75\163\x65\162"] = "\125\163\145\x72\x20\124\171\160\145";
        return $Aw;
    }
    function check_is_sso_user_table()
    {
        if (!get_site_option(mo_saml_option_constants::SHOW_SSO_USER)) {
            goto KKg;
        }
        add_filter("\x6d\x61\x6e\141\x67\145\137\x75\163\x65\x72\x73\137\143\157\x6c\x75\x6d\x6e\163", array($this, "\x6e\x65\167\x5f\155\x6f\144\x69\146\171\x5f\165\163\x65\x72\137\164\141\x62\154\x65"), 1, 1);
        add_filter("\x6d\x61\x6e\x61\147\145\x5f\x75\163\x65\x72\163\x5f\x63\x75\163\164\157\155\137\x63\x6f\x6c\165\x6d\156", array($this, "\156\x65\x77\x5f\155\x6f\144\151\146\x79\137\x75\x73\x65\162\137\164\141\x62\x6c\x65\x5f\162\x6f\167"), 1, 3);
        add_filter("\x70\162\145\137\147\145\x74\x5f\x75\x73\x65\162\163", array($this, "\x66\x69\x6c\x74\145\162\137\163\x73\x6f\x5f\165\x73\145\x72\x5f\164\x61\142\x6c\145"), 99, 1);
        add_action("\155\x61\156\x61\147\x65\137\165\x73\145\162\x73\x5f\x65\170\x74\162\141\x5f\x74\x61\x62\154\145\x6e\141\166", array($this, "\x72\x65\156\144\x65\x72\137\146\151\154\x74\145\162\x5f\x73\x73\157\137\x75\x73\x65\x72\x5f\x61\143\164\x69\157\x6e\x73"), 99, 1);
        KKg:
    }
    function new_modify_user_table_row($wC, $Tu, $Ur)
    {
        switch ($Tu) {
            case "\163\163\x6f\x75\163\145\x72":
                return get_user_meta($Ur, mo_options_user_meta::KEY_USER_TYPE, true) ? "\x3c\x64\x69\x76\x3e\123\x53\x4f\40\x55\163\145\162\74\x2f\144\151\x76\x3e" : '';
                goto zjR;
            default:
        }
        nmS:
        zjR:
        return $wC;
    }
    function filter_sso_user_table($yZ)
    {
        if (is_admin()) {
            goto jH7;
        }
        return $yZ;
        jH7:
        global $pagenow;
        if (!("\165\x73\x65\162\163\x2e\x70\150\160" === $pagenow)) {
            goto aVM;
        }
        if (!(isset($_GET[mo_saml_option_constants::SSO_USER]) && $_GET[mo_saml_option_constants::SSO_USER] !== "\60")) {
            goto fhS;
        }
        switch ($_GET[mo_saml_option_constants::SSO_USER]) {
            case "\x73\163\x6f\55\x75\x73\x65\x72\x73":
                $HN = array(array("\x6b\x65\171" => mo_options_user_meta::KEY_USER_TYPE, "\166\x61\x6c\165\x65" => mo_options_user_meta::VALUE_SSO_USER, "\143\157\x6d\160\141\x72\x65" => "\75"));
                goto TKx;
            case "\156\157\156\55\163\163\157\55\x75\163\145\162\163":
                $HN = array(array("\153\x65\171" => mo_options_user_meta::KEY_USER_TYPE, "\143\157\155\160\x61\162\x65" => "\116\x4f\x54\x20\105\130\x49\x53\124\123"));
                goto TKx;
        }
        X_O:
        TKx:
        $yZ->set("\x6d\x65\x74\141\x5f\161\x75\145\162\171", $HN);
        fhS:
        aVM:
        return $yZ;
    }
    function render_filter_sso_user_actions($lk)
    {
        $yw = "\142\157\x74\x74\x6f\155" === $lk ? "\155\x6f\137\163\141\155\x6c\x5f\163\x73\157\137\165\x73\x65\x72\62" : mo_saml_option_constants::SSO_USER;
        echo "\x3c\x73\x65\154\145\143\164\40\157\x6e\x63\150\x61\x6e\x67\145\75\x22\151\x64\x70\106\151\x6c\164\145\x72\x41\x63\x74\151\157\x6e\163\x43\x68\x61\156\x67\145\x28\164\x68\151\x73\56\x6e\141\155\145\x29\x22\40\x69\x64\x3d\x22" . esc_attr($yw) . "\42\x20\x6e\x61\155\145\75\x22" . esc_attr($yw) . "\42\x3e\15\xa\x9\x9\11\x9\74\x6f\x70\164\151\157\156\40\x76\x61\154\x75\145\75\x22\x30\x22\76\x55\x73\x65\x72\x20\x54\x79\160\x65\74\x2f\157\x70\164\151\157\x6e\76\15\xa\x9\11\11\11\x3c\157\x70\164\151\157\x6e\x20\166\141\x6c\x75\x65\x3d\42\163\163\x6f\x2d\x75\x73\x65\x72\163\x22\40" . (isset($_GET[mo_saml_option_constants::SSO_USER]) && $_GET[mo_saml_option_constants::SSO_USER] == "\163\x73\x6f\x2d\165\x73\145\162\x73" ? "\x73\145\154\x65\143\164\145\x64" : '') . "\76\123\123\x4f\40\x55\x73\145\x72\163\x3c\x2f\x6f\160\164\151\157\x6e\x3e\15\xa\11\11\x9\x9\74\157\160\x74\x69\157\156\40\166\x61\x6c\165\145\75\x22\x6e\157\156\x2d\x73\163\x6f\x2d\165\x73\145\x72\x73\x22\x20" . (isset($_GET[mo_saml_option_constants::SSO_USER]) && $_GET[mo_saml_option_constants::SSO_USER] == "\156\157\156\x2d\163\163\157\55\x75\x73\x65\x72\x73" ? "\x73\x65\154\x65\143\164\x65\144" : '') . "\76\x4e\157\x6e\x2d\123\x53\117\40\x55\163\x65\162\163\x3c\x2f\x6f\160\x74\x69\x6f\156\76\xd\xa\x9\x9\11\74\57\163\145\154\145\143\x74\x3e\xd\12\xd\12\11\x9\x9\74\151\156\x70\165\x74\x20\x74\171\x70\x65\x3d\42\x73\165\142\155\151\164\x22\40\x63\x6c\141\163\x73\x3d\42\142\x75\164\164\157\156\x20\x61\143\164\151\157\156\42\40\166\141\x6c\x75\145\75\x22\x46\151\154\164\145\162\x22\x3e";
        echo "\74\163\x63\162\151\160\164\x3e\15\12\11\11\x9\11\146\x75\x6e\143\x74\x69\157\156\40\x69\144\160\106\x69\154\164\x65\162\101\x63\164\151\157\x6e\163\x43\x68\x61\x6e\147\145\x28\x6e\141\155\x65\51\x20\173\15\xa\x9\11\11\x9\11\151\146\50\x6e\141\x6d\x65\40\x3d\x3d\x20\x22\x6d\x6f\137\163\x61\155\154\x5f\x73\x73\x6f\x5f\165\163\145\162\x22\51\x20\x7b\xd\12\11\x9\x9\11\11\x9\x64\x72\157\x70\x64\x6f\x77\156\x31\x20\x3d\40\144\x6f\143\x75\x6d\x65\156\x74\56\147\145\164\x45\x6c\x65\155\x65\x6e\164\102\x79\111\x64\50\42\155\157\137\163\141\155\154\x5f\163\163\157\137\165\163\145\x72\x22\x29\73\xd\xa\11\x9\11\x9\x9\x9\166\141\x6c\x75\145\61\40\x3d\x20\x64\162\x6f\160\x64\157\167\156\61\x2e\x76\141\x6c\165\x65\x3b\15\xa\11\x9\11\11\x9\11\144\x72\x6f\x70\x64\x6f\167\156\x32\40\75\x20\x64\x6f\x63\x75\155\x65\x6e\164\x2e\147\x65\164\105\154\x65\155\145\156\x74\x42\171\111\144\50\42\x6d\x6f\x5f\163\141\155\x6c\137\163\163\x6f\137\165\x73\x65\x72\x32\42\x29\x3b\xd\xa\11\x9\11\x9\11\11\x64\x72\x6f\x70\144\x6f\x77\x6e\x32\56\166\x61\x6c\165\x65\x20\x3d\40\x76\141\x6c\x75\145\61\73\xd\xa\11\11\11\x9\x9\175\xd\xa\x9\x9\x9\11\11\145\x6c\163\x65\x20\151\x66\x28\x6e\141\155\145\40\x3d\x3d\x20\x22\155\x6f\137\163\141\155\x6c\x5f\x73\x73\157\137\x75\x73\145\x72\x32\42\51\x20\x7b\xd\12\11\11\x9\x9\x9\x9\x64\162\157\160\144\x6f\x77\156\x32\x20\75\x20\x64\157\143\x75\155\x65\156\164\x2e\x67\x65\164\105\154\x65\x6d\145\156\x74\102\171\111\144\50\x22\x6d\x6f\x5f\x73\x61\x6d\x6c\x5f\163\163\157\137\x75\163\145\162\62\42\x29\x3b\xd\12\x9\x9\11\11\x9\x9\166\141\154\165\x65\x32\40\x3d\x20\144\x72\157\x70\144\157\167\156\x32\x2e\x76\141\x6c\165\x65\x3b\xd\12\x9\x9\x9\x9\x9\11\144\162\157\160\x64\x6f\167\x6e\x31\x20\75\x20\x64\x6f\x63\165\155\145\156\x74\56\x67\145\x74\105\154\x65\x6d\145\156\x74\102\x79\111\x64\50\42\155\x6f\x5f\x73\x61\x6d\x6c\x5f\x73\x73\x6f\x5f\x75\163\145\x72\42\51\73\xd\xa\11\x9\11\x9\11\x9\x64\162\x6f\x70\144\157\x77\x6e\61\56\x76\x61\x6c\x75\145\40\75\40\166\141\x6c\165\x65\x32\73\15\xa\x9\11\x9\x9\11\175\15\12\11\11\11\x9\175\15\12\11\11\x9\74\57\x73\143\162\x69\x70\x74\76";
    }
}
new Mo_Saml_Manage_User_Table_Sso_Action();
