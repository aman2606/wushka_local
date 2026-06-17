<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



namespace MOSAML\LicenseLibrary\Classes;

use MOSAML\LicenseLibrary\Handlers\Mo_License_Add_View_Handler;
use MOSAML\LicenseLibrary\Mo_License_Config;
use MOSAML\LicenseLibrary\Utils\Mo_License_Actions_Utility;
if (defined("\101\x42\123\x50\101\x54\x48")) {
    goto gl;
}
exit;
gl:
class Mo_License_Add_View_Actions
{
    private $license_add_view_handler;
    public function __construct($Tt)
    {
        $this->license_add_view_handler = $Tt;
        $this->add_license_views();
    }
    public function add_license_views()
    {
        add_action("\141\x64\155\151\156\137\145\156\x71\165\x65\165\x65\x5f\x73\143\162\x69\160\164\x73", array($this->license_add_view_handler, "\x61\x64\x64\137\x70\x6c\165\147\151\x6e\x5f\154\x69\x63\x65\156\x73\145\x5f\163\x63\x72\x69\160\164\x73"));
        add_action(Mo_License_Actions_Utility::get_current_environment_hook_name("\141\x64\155\x69\156\x5f\x6e\x6f\164\x69\x63\145"), array($this->license_add_view_handler, "\x61\144\144\x5f\x61\x64\155\x69\x6e\x5f\154\151\x63\145\156\163\x65\137\x6e\157\164\x69\143\145"));
        if (!Mo_License_Config::ADD_DASHBOARD_WIDGET) {
            goto vz;
        }
        add_action(Mo_License_Actions_Utility::get_current_environment_hook_name("\x64\x61\163\x68\x62\157\x61\x72\144\x5f\167\151\144\147\x65\x74"), array($this->license_add_view_handler, "\x61\144\x64\x5f\x64\x61\163\x68\x62\157\141\162\144\137\154\151\x63\x65\x6e\163\145\x5f\167\151\144\x67\x65\x74"));
        vz:
    }
}
