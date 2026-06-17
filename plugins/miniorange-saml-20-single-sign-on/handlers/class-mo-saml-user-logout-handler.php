<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



if (defined("\101\102\x53\x50\x41\124\110")) {
    goto dF;
}
exit;
dF:
require_once Mo_Saml_Plugin_Files::MO_SAML_INVALID_LOGOUT_REQUEST_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_XMLSECLIBS_PROCESSING_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_DOM_DISABLED_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_OPENSSL_DISABLED_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_INVALID_XML_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_EXCEPTION_HANDLER;
class Mo_Saml_User_Logout_Handler
{
    private static $instance;
    public static function mo_saml_get_object()
    {
        if (isset(self::$instance)) {
            goto M0;
        }
        $A4 = __CLASS__;
        self::$instance = new $A4();
        M0:
        return self::$instance;
    }
    public function mo_saml_logout_user($Ur, $V9 = '')
    {
        try {
            $Pf = Mo_SAML_Login_Widget::mo_saml_get_object();
            $Pf->mo_saml_logout($Ur, $V9);
        } catch (Mo_SAML_DOM_Extension_Disabled_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5);
        } catch (Mo_SAML_CURL_Extension_Disabled_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5);
        } catch (Mo_SAML_OpenSSL_Extension_Disabled_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5);
        } catch (Mo_SAML_Invalid_XML_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5);
        } catch (Mo_SAML_Invalid_Logout_Request_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5);
        } catch (Mo_SAML_XMLSecLibs_Processing_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5);
        }
    }
}
