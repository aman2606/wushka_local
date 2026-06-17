<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



namespace MOSAML\LicenseLibrary\Classes;

use MOSAML\LicenseLibrary\Handlers\Mo_License_Actions_Handler;
use MOSAML\LicenseLibrary\Mo_License_Config;
use MOSAML\LicenseLibrary\Utils\Mo_License_Actions_Utility;
if (defined("\x41\102\x53\120\101\124\110")) {
    goto mY;
}
exit;
mY:
class Mo_License_Actions
{
    private $license_action_handler;
    public function __construct($qB)
    {
        $this->license_action_handler = $qB;
        $this->add_license_actions();
    }
    public function add_license_actions()
    {
        add_action("\x69\x6e\x69\x74", array($this->license_action_handler, "\162\165\x6e\x5f\x6c\x69\143\x65\x6e\x73\145\x5f\x63\x72\x6f\156"));
        add_action("\141\x64\x6d\151\156\x5f\151\x6e\151\x74", array($this->license_action_handler, "\x64\151\163\x6d\x69\163\163\137\x61\144\x6d\x69\x6e\137\154\151\x63\x65\x6e\163\145\137\156\157\164\x69\143\x65"));
        add_action("\x61\144\x6d\151\x6e\x5f\x69\x6e\151\x74", array($this->license_action_handler, "\162\x65\146\x72\145\163\150\137\141\144\x6d\x69\x6e\137\167\151\x64\x67\145\164\137\145\170\160\151\162\x79"));
    }
}
