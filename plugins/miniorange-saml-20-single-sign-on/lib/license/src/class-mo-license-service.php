<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



namespace MOSAML\LicenseLibrary;

if (defined("\115\117\x5f\114\x49\103\x45\x4e\123\105\x5f\x4c\111\x42\x52\x41\x52\131\137\120\x41\x54\110")) {
    goto nk;
}
define("\115\x4f\x5f\x4c\x49\103\105\116\123\x45\137\x4c\111\x42\122\101\x52\x59\137\120\101\x54\x48", plugin_dir_url(__FILE__));
nk:
use MOSAML\LicenseLibrary\Classes\Mo_License_Constants;
use MOSAML\LicenseLibrary\Classes\Mo_License_Dao;
use MOSAML\LicenseLibrary\Exceptions\Mo_License_Grace_Expired_Exception;
use MOSAML\LicenseLibrary\Exceptions\Mo_License_Invalid_Expiry_Date_Exception;
use MOSAML\LicenseLibrary\Exceptions\Mo_License_Missing_Customer_Email_Exception;
use MOSAML\LicenseLibrary\Exceptions\Mo_License_Missing_License_Key_Exception;
use MOSAML\LicenseLibrary\Exceptions\Mo_License_Missing_Or_Invalid_Customer_Key_Exception;
use MOSAML\LicenseLibrary\Exceptions\Mo_License_Unknown_Error_Exception;
use MOSAML\LicenseLibrary\Utils\Mo_License_Actions_Utility;
use MOSAML\LicenseLibrary\Utils\Mo_License_Service_Utility;
if (defined("\x41\102\123\120\x41\x54\x48")) {
    goto j9;
}
exit;
j9:
class Mo_License_Service
{
    public static function is_license_expired()
    {
        try {
            Mo_License_Service_Utility::check_customer_login_and_license();
            $Hx = Mo_License_Service_Utility::is_license_grace_expired();
        } catch (Mo_License_Grace_Expired_Exception $G2) {
            return Mo_License_Service_Utility::return_true_with_code($G2::MESSAGE);
        } catch (Mo_License_Invalid_Expiry_Date_Exception $G2) {
            return Mo_License_Service_Utility::return_true_with_code($G2::MESSAGE);
        } catch (Mo_License_Missing_License_Key_Exception $G2) {
            return Mo_License_Service_Utility::return_true_with_code($G2::MESSAGE);
        } catch (Mo_License_Missing_Customer_Email_Exception $G2) {
            return Mo_License_Service_Utility::return_true_with_code($G2::MESSAGE);
        } catch (Mo_License_Missing_Or_Invalid_Customer_Key_Exception $G2) {
            return Mo_License_Service_Utility::return_true_with_code($G2::MESSAGE);
        } catch (Mo_License_Unknown_Error_Exception $G2) {
            return Mo_License_Service_Utility::return_true_with_code($G2::MESSAGE);
        }
        return Mo_License_Service_Utility::return_false_with_code($Hx["\103\x4f\x44\x45"]);
    }
    public static function is_customer_license_verified()
    {
        try {
            Mo_License_Service_Utility::check_customer_login_and_license();
        } catch (Mo_License_Missing_Customer_Email_Exception $G2) {
            return false;
        } catch (Mo_License_Missing_Or_Invalid_Customer_Key_Exception $G2) {
            return false;
        } catch (Mo_License_Missing_License_Key_Exception $G2) {
            return false;
        }
        return true;
    }
    public static function is_customer_logged_into_plugin()
    {
        try {
            Mo_License_Service_Utility::check_customer_login();
        } catch (Mo_License_Missing_Customer_Email_Exception $G2) {
            return false;
        } catch (Mo_License_Missing_Or_Invalid_Customer_Key_Exception $G2) {
            return false;
        }
        return true;
    }
    public static function get_html_disabled_status($Lm = true)
    {
        if ($Lm) {
            goto uS;
        }
        $Fk = self::is_customer_license_verified();
        goto MQ;
        uS:
        $Rr = self::is_license_expired();
        $Fk = !$Rr["\x53\124\x41\x54\125\123"];
        MQ:
        if (!(false === $Fk)) {
            goto qO;
        }
        return "\x64\x69\x73\141\142\154\x65\144";
        qO:
        return '';
    }
    public static function refresh_license_expiry()
    {
        $Wd = Mo_License_Actions_Utility::fetch_license_expiry_date();
        if (!$Wd) {
            goto Fr;
        }
        self::update_license_expiry($Wd);
        return $Wd;
        Fr:
        return false;
    }
    public static function mo_check_admin_referer($If = -1, $WI = "\137\167\x70\x6e\157\156\143\x65", $Lm = true)
    {
        $Ra = check_admin_referer($If, $WI);
        $Rr = false;
        if ($Lm) {
            goto zf;
        }
        $Fk = self::is_customer_license_verified();
        goto PH;
        zf:
        $Rr = self::is_license_expired();
        $Fk = !$Rr["\123\124\x41\124\125\123"];
        PH:
        if (!(!$Fk || !$Ra)) {
            goto K2;
        }
        wp_die(esc_html(Mo_License_Constants::ADMIN_ERROR_MESSAGE));
        K2:
        return true;
    }
    public static function mo_check_ajax_referer($sZ = -1, $WI = false, $zq = true, $Lm = true)
    {
        if ($Lm) {
            goto ko;
        }
        $Fk = self::is_customer_license_verified();
        goto dq;
        ko:
        $Rr = self::is_license_expired();
        $Fk = !$Rr["\x53\124\x41\x54\125\123"];
        dq:
        $h0 = check_ajax_referer($sZ, $WI, $zq);
        if (!(!$Fk || !$h0)) {
            goto ea;
        }
        wp_send_json_error(array("\155\x65\x73\163\x61\x67\x65" => "\x49\x6e\166\141\154\151\144\x20\114\x69\x63\145\156\x73\145\x20\x4b\145\x79\x2e"), 403);
        exit;
        ea:
        wp_send_json_success(array("\x6d\x65\x73\x73\141\x67\x65" => "\x52\145\x66\145\162\145\162\x20\166\145\162\x69\x66\x69\145\144\40\163\x75\143\x63\x65\x73\163\x66\x75\x6c\x6c\171\x2e"), 200);
    }
    public static function get_expiry_remaining_days($Wd)
    {
        $jk = strtotime($Wd);
        $es = strtotime(gmdate("\131\55\x6d\x2d\x64"));
        $dK = $jk - $es;
        $Hn = floor($dK / (60 * 60 * 24));
        return $Hn;
    }
    public static function get_grace_days_left($Wd)
    {
        $Hn = self::get_expiry_remaining_days($Wd);
        if (!($Hn > 0)) {
            goto Kl;
        }
        return false;
        Kl:
        return Mo_License_Config::GRACE_PERIOD_DAYS + $Hn;
    }
    public static function get_disable_date($Wd)
    {
        return gmdate("\x4d\40\144\54\40\131", strtotime($Wd . "\x2b" . Mo_License_Config::GRACE_PERIOD_DAYS . "\x20\x64\x61\x79\163"));
    }
    public static function get_expiry_date()
    {
        $vE = Mo_License_Service_Utility::mo_decrypt_data(Mo_License_Dao::mo_get_option(Mo_License_Constants::LICENSE_EXPIRY_DATE_OPTION));
        if ($vE) {
            goto J5;
        }
        $vE = Mo_License_Actions_Utility::fetch_license_expiry_date();
        if ($vE) {
            goto IU;
        }
        $vE = Mo_License_Constants::EPOCH_DATE;
        IU:
        self::update_license_expiry($vE);
        J5:
        return $vE;
    }
    public static function get_formatted_license_expiry_date($no)
    {
        try {
            $dG = new \DateTime($no);
            $ZW = $dG->getTimestamp();
            $no = gmdate("\x46\x20\152\54\40\131", $ZW);
            return $no;
        } catch (\Exception $G2) {
            return $no;
        }
    }
    public static function is_customer_license_valid($ln = false, $Lm = true)
    {
        if ($Lm) {
            goto B_;
        }
        $Fk = self::is_customer_license_verified();
        goto r2;
        B_:
        $Rr = self::is_license_expired();
        $Fk = !$Rr["\123\x54\101\124\x55\x53"];
        r2:
        if (!(true === $Fk)) {
            goto iY;
        }
        return $ln ? '' : true;
        iY:
        return $ln ? "\144\x69\x73\141\x62\154\145\144" : false;
    }
    public static function is_trial_license()
    {
        $Fk = self::is_customer_license_verified();
        if (!$Fk) {
            goto Zr;
        }
        $y3 = Mo_License_Dao::mo_get_option(Mo_License_Constants::IS_TRIAL);
        if (!("\x74\162\165\x65" === $y3)) {
            goto wr;
        }
        return true;
        wr:
        Zr:
        return false;
    }
    public static function update_license_expiry($Wd)
    {
        Mo_License_Dao::mo_update_option(Mo_License_Constants::LICENSE_EXPIRY_DATE_OPTION, Mo_License_Service_Utility::mo_encrypt_data(self::get_formatted_license_expiry_date($Wd)));
        $GW = self::is_license_expired();
        if (true === $GW["\123\x54\x41\x54\125\x53"]) {
            goto pL;
        }
        if (!Mo_License_Dao::mo_get_option(Mo_License_Constants::LICENSE_EXPIRED_OPTION)) {
            goto ZW;
        }
        self::reset_license_values();
        ZW:
        goto F8;
        pL:
        Mo_License_Dao::mo_update_option(Mo_License_Constants::LICENSE_EXPIRED_OPTION, true);
        F8:
    }
    public static function update_trial_status($y3)
    {
        if (self::is_customer_license_verified() && true === $y3) {
            goto tS;
        }
        Mo_License_Dao::mo_update_option(Mo_License_Constants::IS_TRIAL, "\146\141\154\x73\145");
        goto om;
        tS:
        Mo_License_Dao::mo_update_option(Mo_License_Constants::IS_TRIAL, "\x74\162\x75\145");
        om:
    }
    public static function reset_license_values()
    {
        $v0 = Mo_License_Constants::get_constants();
        foreach ($v0 as $R2 => $EB) {
            if (!(strpos($R2, "\117\x50\124\x49\x4f\116") !== false)) {
                goto po;
            }
            Mo_License_Dao::mo_delete_option($EB);
            po:
            EF:
        }
        aA:
    }
}
