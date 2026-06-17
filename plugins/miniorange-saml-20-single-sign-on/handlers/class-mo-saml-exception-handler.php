<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



if (defined("\x41\102\x53\x50\101\x54\x48")) {
    goto VR;
}
exit;
VR:
class Mo_Saml_Exception_Handler
{
    public static function mo_saml_throw_exception($OH, $np = false)
    {
        $fO = $OH->getCode();
        $oU = "\x57\x50\123\x41\x4d\x4c\x45\x52\x52";
        if (!(0 !== $fO)) {
            goto v5;
        }
        if ($fO < 10) {
            goto Q3;
        }
        $oU .= "\x30" . $fO;
        goto ec;
        Q3:
        $oU .= "\x30\60" . $fO;
        ec:
        if (empty(Mo_Saml_Error_Codes::$error_codes[$oU])) {
            goto gv;
        }
        if ($np) {
            goto mW;
        }
        Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes[$oU]);
        goto Z9;
        mW:
        Mo_Saml_Error_Message::mo_saml_display_error_notice_to_admin(Mo_Saml_Error_Codes::$error_codes[$oU]);
        Z9:
        gv:
        v5:
    }
}
