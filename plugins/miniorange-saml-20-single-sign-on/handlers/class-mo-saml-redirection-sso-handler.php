<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



if (defined("\x41\102\x53\120\101\124\110")) {
    goto Im;
}
exit;
Im:
require_once Mo_Saml_Plugin_Files::MO_SAML_CURL_DISABLED_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_DOM_DISABLED_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_OPENSSL_DISABLED_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_IDP_STATUS_INACTIVE_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_EXCEPTION_HANDLER;
class Mo_Saml_Redirection_Sso_Handler
{
    private static $instance;
    public static function mo_saml_get_object()
    {
        if (isset(self::$instance)) {
            goto fm;
        }
        $A4 = __CLASS__;
        self::$instance = new $A4();
        fm:
        return self::$instance;
    }
    public function mo_saml_redirect_sso_for_authentication($Sf, $V9)
    {
        try {
            $IU = Mo_SAML_Plugin::mo_saml_get_object();
            $IU->mo_saml_redirect_for_authentication($Sf, $V9);
        } catch (Mo_SAML_DOM_Extension_Disabled_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5);
        } catch (Mo_SAML_CURL_Extension_Disabled_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5);
        } catch (Mo_SAML_OpenSSL_Extension_Disabled_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5);
        } catch (Mo_SAML_IDP_Status_Inactive_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5);
        }
    }
}
