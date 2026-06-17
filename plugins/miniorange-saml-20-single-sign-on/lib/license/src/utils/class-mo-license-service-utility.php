<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



namespace MOSAML\LicenseLibrary\Utils;

use MOSAML\LicenseLibrary\Classes\Mo_AESEncryption;
use MOSAML\LicenseLibrary\Classes\Mo_License_Dao;
use MOSAML\LicenseLibrary\Exceptions\Mo_License_Grace_Expired_Exception;
use MOSAML\LicenseLibrary\Exceptions\Mo_License_Invalid_Expiry_Date_Exception;
use MOSAML\LicenseLibrary\Exceptions\Mo_License_Missing_Customer_Email_Exception;
use MOSAML\LicenseLibrary\Exceptions\Mo_License_Missing_License_Key_Exception;
use MOSAML\LicenseLibrary\Exceptions\Mo_License_Missing_Or_Invalid_Customer_Key_Exception;
use MOSAML\LicenseLibrary\Exceptions\Mo_License_Unknown_Error_Exception;
use MOSAML\LicenseLibrary\Classes\Mo_License_Constants;
use MOSAML\LicenseLibrary\Mo_License_Config;
use MOSAML\LicenseLibrary\Mo_License_Service;
if (defined("\101\x42\123\x50\x41\x54\x48")) {
    goto ER;
}
exit;
ER:
class Mo_License_Service_Utility
{
    public static function check_customer_login()
    {
        $RY = Mo_License_Dao::mo_get_option(Mo_License_Config::CUSTOMER_EMAIL_OPTION);
        $FM = Mo_License_Dao::mo_get_option(Mo_License_Config::CUSTOMER_KEY_OPTION);
        if (!$RY) {
            goto hT;
        }
        if (!$FM || !is_numeric(trim($FM))) {
            goto nC;
        }
        goto HN;
        hT:
        throw new Mo_License_Missing_Customer_Email_Exception();
        goto HN;
        nC:
        throw new Mo_License_Missing_Or_Invalid_Customer_Key_Exception();
        HN:
    }
    public static function check_customer_login_and_license()
    {
        self::check_customer_login();
        $JP = Mo_License_Dao::mo_get_option(Mo_License_Config::LICENSE_KEY_OPTION);
        if ($JP) {
            goto Dn;
        }
        throw new Mo_License_Missing_License_Key_Exception();
        Dn:
    }
    public static function is_license_grace_expired()
    {
        $Wd = self::mo_decrypt_data(Mo_License_Dao::mo_get_option(Mo_License_Constants::LICENSE_EXPIRY_DATE_OPTION));
        if ($Wd) {
            goto Vl;
        }
        throw new Mo_License_Invalid_Expiry_Date_Exception();
        Vl:
        try {
            $vR = gmdate("\131\x2d\x6d\55\144", strtotime($Wd));
            if (Mo_License_Service::is_trial_license()) {
                goto WS;
            }
            $Y5 = strtotime("\x2b" . Mo_License_Config::GRACE_PERIOD_DAYS . "\40\x64\x61\x79\x73", strtotime($Wd));
            goto B2;
            WS:
            $Y5 = strtotime("\x2b" . 0 . "\40\x64\x61\171\x73", strtotime($Wd));
            B2:
            $AZ = gmdate("\131\x2d\x6d\55\x64", $Y5);
            $wF = new \DateTime();
            $wF = $wF->format("\x59\x2d\x6d\55\144");
        } catch (\Exception $G2) {
            throw new Mo_License_Unknown_Error_Exception();
        }
        if ($wF > $AZ) {
            goto fM;
        }
        if ($wF > $vR) {
            goto vq;
        }
        goto cT;
        fM:
        throw new Mo_License_Grace_Expired_Exception();
        goto cT;
        vq:
        return self::return_false_with_code("\114\111\x43\x45\116\x53\x45\137\x49\116\137\107\122\x41\x43\x45");
        cT:
        return self::return_false_with_code("\x4c\x49\x43\105\116\123\x45\x5f\x56\101\114\111\x44");
    }
    public static function return_true_with_code($fO)
    {
        return array("\123\x54\x41\x54\125\x53" => true, "\103\x4f\x44\x45" => $fO);
    }
    public static function return_false_with_code($fO)
    {
        return array("\123\x54\101\124\x55\123" => false, "\x43\117\x44\105" => $fO);
    }
    public static function mo_decrypt_data($jr)
    {
        $R2 = Mo_License_Dao::mo_get_option(Mo_License_Config::CUSTOMER_TOKEN_OPTION);
        $jh = Mo_AESEncryption::decrypt_data($jr, $R2);
        return $jh;
    }
    public static function mo_encrypt_data($jr)
    {
        $R2 = Mo_License_Dao::mo_get_option(Mo_License_Config::CUSTOMER_TOKEN_OPTION);
        $KR = Mo_AESEncryption::encrypt_data($jr, $R2);
        return $KR;
    }
}
