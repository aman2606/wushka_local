<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



namespace MOSAML\LicenseLibrary\Handlers;

use MOSAML\LicenseLibrary\Classes\Mo_License_Constants;
use MOSAML\LicenseLibrary\Classes\Mo_License_Dao;
use MOSAML\LicenseLibrary\Mo_License_Config;
use MOSAML\LicenseLibrary\Mo_License_Service;
use MOSAML\LicenseLibrary\Utils\Mo_License_Actions_Utility;
use MOSAML\LicenseLibrary\Views\Mo_License_Notice_Views;
if (defined("\101\102\x53\x50\101\x54\110")) {
    goto Q4;
}
exit;
Q4:
class Mo_License_Actions_Handler
{
    private $license_expiry_date;
    public function __construct($vE)
    {
        $this->license_expiry_date = $vE;
    }
    public function run_license_cron()
    {
        if (Mo_License_Service::is_customer_license_verified()) {
            goto pr;
        }
        return;
        pr:
        $BS = Mo_License_Dao::mo_get_option(Mo_License_Constants::LAST_CHECK_TIME_OPTION);
        if (!$BS) {
            goto rh;
        }
        $BS = intval($BS);
        if (!(time() - $BS < 3600 * 24 * $this->get_license_cron_interval())) {
            goto k2;
        }
        return;
        k2:
        rh:
        $Wd = Mo_License_Actions_Utility::fetch_license_expiry_date();
        if (!$Wd) {
            goto RL;
        }
        Mo_License_Service::update_license_expiry($Wd);
        Mo_License_Dao::mo_update_option(Mo_License_Constants::LAST_CHECK_TIME_OPTION, time());
        RL:
    }
    private function get_license_cron_interval()
    {
        $Hn = Mo_License_Service::get_expiry_remaining_days($this->license_expiry_date);
        if ($Hn >= 60) {
            goto Td;
        }
        if ($Hn > 10 && $Hn < 60) {
            goto D6;
        }
        if ($Hn <= 10) {
            goto c3;
        }
        goto ar;
        Td:
        return Mo_License_Config::LICENSE_CRON_INTERVAL["\x44\x45\x46\x41\125\x4c\124"];
        goto ar;
        D6:
        return Mo_License_Config::LICENSE_CRON_INTERVAL["\x45\x58\120\111\122\131\137\127\x49\124\x48\x49\116\137\x36\60\x5f\104\x41\x59\x53"];
        goto ar;
        c3:
        return Mo_License_Config::LICENSE_CRON_INTERVAL["\x45\130\120\x49\122\x45\104\137\114\x49\103\x45\x4e\123\105"];
        ar:
    }
    public function dismiss_admin_license_notice()
    {
        if (!(current_user_can("\155\x61\x6e\141\147\x65\137\157\x70\164\x69\157\156\163") && !empty($_POST["\x6f\160\164\x69\x6f\x6e"]) && Mo_License_Constants::ADMIN_NOTICE_DISMISS_ID === $_POST["\157\x70\x74\151\157\156"] && check_admin_referer(Mo_License_Constants::ADMIN_NOTICE_DISMISS_ID))) {
            goto DJ;
        }
        $Hn = Mo_License_Service::get_expiry_remaining_days($this->license_expiry_date);
        Mo_License_Dao::mo_update_option(Mo_License_Constants::EXPIRY_NOTICE_CLOSE_OPTION, $Hn);
        DJ:
    }
    public function refresh_admin_widget_expiry()
    {
        if (!(current_user_can("\x6d\x61\x6e\141\x67\x65\x5f\x6f\x70\164\151\x6f\156\163") && !empty($_POST["\x6f\160\x74\x69\157\x6e"]) && Mo_License_Constants::DASHBOARD_WIDGET_REFRESH_ID === $_POST["\157\x70\164\x69\157\x6e"] && check_admin_referer(Mo_License_Constants::DASHBOARD_WIDGET_REFRESH_ID))) {
            goto JH;
        }
        Mo_License_Service::refresh_license_expiry();
        JH:
    }
}
