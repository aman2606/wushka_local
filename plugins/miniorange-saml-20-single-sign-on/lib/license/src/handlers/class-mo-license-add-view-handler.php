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
use MOSAML\LicenseLibrary\Classes\Mo_License_Library;
use MOSAML\LicenseLibrary\Mo_License_Config;
use MOSAML\LicenseLibrary\Mo_License_Service;
use MOSAML\LicenseLibrary\Views\Mo_License_Notice_Views;
if (defined("\101\x42\x53\x50\101\124\x48")) {
    goto a0;
}
exit;
a0:
class Mo_License_Add_View_Handler
{
    private $license_views;
    public function __construct($gz)
    {
        $this->license_views = $gz;
    }
    public function add_admin_license_notice()
    {
        if (!(Mo_License_Service::is_customer_license_verified() && current_user_can("\155\141\x6e\141\147\x65\x5f\x6f\x70\164\x69\x6f\156\x73"))) {
            goto bL;
        }
        $XC = $this->license_views->get_license_notice();
        echo $XC;
        bL:
    }
    public function add_dashboard_license_widget()
    {
        if (!(Mo_License_Service::is_customer_license_verified() && current_user_can("\155\141\x6e\141\147\x65\x5f\x6f\160\x74\x69\x6f\x6e\x73"))) {
            goto qt;
        }
        global $wp_meta_boxes;
        wp_add_dashboard_widget(Mo_License_Constants::DASHBOARD_WIDGET_ID, Mo_License_Config::PLUGIN_NAME, array($this->license_views, "\144\151\163\x70\154\x61\171\x5f\144\x61\163\150\x62\157\x61\x72\144\137\167\x69\144\x67\145\164"));
        $jl = "\144\141\163\x68\142\157\x61\x72\x64";
        if (!("\x6e\x65\x74\167\157\162\x6b" === Mo_License_Library::$environment_type)) {
            goto nu;
        }
        $jl = "\x64\x61\x73\x68\142\157\x61\162\144\x2d\x6e\x65\164\167\x6f\x72\x6b";
        nu:
        $q5 = $wp_meta_boxes[$jl]["\156\x6f\x72\155\141\x6c"]["\x63\157\162\145"];
        $xf = array(Mo_License_Constants::DASHBOARD_WIDGET_ID => $q5[Mo_License_Constants::DASHBOARD_WIDGET_ID]);
        unset($q5[Mo_License_Constants::DASHBOARD_WIDGET_ID]);
        $q5 = !empty($q5) ? $q5 : array();
        $gM = array_merge($xf, $q5);
        $wp_meta_boxes[$jl]["\156\157\x72\155\x61\x6c"]["\143\157\162\145"] = $gM;
        qt:
    }
    public function add_plugin_license_scripts()
    {
        wp_enqueue_style("\155\x6f\137\x73\141\x6d\x6c\137\154\x69\x63\145\x6e\163\145\137\x76\x69\145\x77\x5f\x73\164\x79\x6c\x65", MO_LICENSE_LIBRARY_PATH . Mo_License_Constants::STYLES_FILE_PATH, array(), Mo_License_Constants::VERSION);
    }
}
