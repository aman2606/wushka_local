<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



define("\x4d\x4f\137\x53\x41\115\x4c\x5f\x50\114\x55\x47\111\x4e\137\104\111\122", __DIR__);
define("\115\117\x5f\123\x41\x4d\x4c\137\117\x50\x54\x49\x4f\116\123\x5f\105\x4e\x55\x4d", "\x2f\151\x6e\x63\154\165\x64\x65\x73\57\x6c\x69\142\x2f\155\157\x2d\x6f\x70\164\x69\157\x6e\x73\55\x65\x6e\165\x6d\56\160\150\x70");
require_once MO_SAML_PLUGIN_DIR . MO_SAML_OPTIONS_ENUM;
require_once Mo_Saml_Plugin_Files::MO_SAML_IMPORT_EXPORT;
require_once Mo_Saml_Plugin_Files::MO_SAML_UTILITIES;
if (defined("\127\x50\x5f\x55\x4e\x49\x4e\123\124\x41\x4c\x4c\137\x50\x4c\x55\107\111\x4e")) {
    goto R5v;
}
exit;
R5v:
if (!(get_option("\155\x6f\137\163\141\x6d\154\x5f\153\145\145\x70\137\x73\145\x74\164\x69\x6e\x67\163\x5f\x6f\156\137\x64\145\x6c\145\x74\151\157\156") !== "\164\x72\165\145")) {
    goto ziD;
}
if (!is_multisite()) {
    goto WNI;
}
global $wpdb;
$Dl = $wpdb->get_col("\123\x45\x4c\105\103\x54\x20\142\x6c\x6f\x67\137\x69\144\x20\x46\x52\x4f\115\40{$wpdb->blogs}");
$QF = get_current_blog_id();
foreach ($Dl as $blog_id) {
    switch_to_blog($blog_id);
    delete_plugin_configuration();
    bUG:
}
r12:
switch_to_blog($QF);
goto UMM;
WNI:
delete_plugin_configuration();
UMM:
ziD:
function delete_plugin_configuration()
{
    SAMLSPUtilities::mo_saml_delete_plugin_option();
    $o4 = get_users(array());
    foreach ($o4 as $user) {
        delete_user_meta($user->ID, "\x6d\157\x5f\x73\x61\155\154\137\165\x73\145\162\137\141\164\164\162\x69\x62\x75\x74\145\163");
        delete_user_meta($user->ID, "\x6d\x6f\137\163\x61\155\154\137\x73\x65\x73\x73\x69\157\156\x5f\x69\x6e\x64\145\x78");
        delete_user_meta($user->ID, "\x6d\x6f\137\x73\141\155\x6c\137\156\141\x6d\x65\137\151\144");
        yAs:
    }
    gTG:
}
