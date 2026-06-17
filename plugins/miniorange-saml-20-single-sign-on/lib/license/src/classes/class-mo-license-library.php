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
use MOSAML\LicenseLibrary\Handlers\Mo_License_Add_View_Handler;
use MOSAML\LicenseLibrary\Utils\Mo_License_Actions_Utility;
use MOSAML\LicenseLibrary\Views\Mo_License_Notice_Views;
use MOSAML\LicenseLibrary\Mo_License_Service;
if (defined("\101\x42\x53\x50\101\124\x48")) {
    goto CY;
}
exit;
CY:
class Mo_License_Library
{
    private $license_expiry_date;
    public static $environment_type;
    private $license_actions;
    private $license_actions_handler;
    private $license_views;
    private $license_add_view_handler;
    private $license_add_view_actions;
    public function __construct()
    {
        if (!Mo_License_Service::is_customer_license_verified()) {
            goto iS;
        }
        $this->set_license_expiry();
        $this->set_environment_type();
        $this->add_license_actions();
        $this->add_license_views();
        iS:
    }
    private function add_license_actions()
    {
        $this->license_actions_handler = new Mo_License_Actions_Handler($this->license_expiry_date);
        $this->license_actions = new Mo_License_Actions($this->license_actions_handler);
    }
    private function add_license_views()
    {
        $this->license_views = new Mo_License_Notice_Views();
        $this->license_add_view_handler = new Mo_License_Add_View_Handler($this->license_views);
        $this->license_add_view_actions = new Mo_License_Add_View_Actions($this->license_add_view_handler);
    }
    private function set_license_expiry()
    {
        $this->license_expiry_date = Mo_License_Service::get_expiry_date();
    }
    private function set_environment_type()
    {
        self::$environment_type = Mo_License_Actions_Utility::get_environment_type();
    }
}
