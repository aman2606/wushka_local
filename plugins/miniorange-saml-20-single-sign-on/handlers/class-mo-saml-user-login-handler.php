<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



if (defined("\x41\x42\123\x50\x41\124\x48")) {
    goto rK;
}
exit;
rK:
require_once Mo_Saml_Plugin_Files::MO_SAML_ELEMENT_DECRYPTION_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_INVALID_ASSERTION_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_INVALID_LOGOUT_REQUEST_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_XMLSECLIBS_PROCESSING_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_DOM_DISABLED_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_CURL_DISABLED_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_OPENSSL_DISABLED_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_DUPLICATE_SAML_RESPONSE_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_INVALID_LICENSE_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_INVALID_XML_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_INVALID_AUDIENCE_URI_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_IDP_STATUS_INACTIVE_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_EXCEPTION_HANDLER;
class Mo_Saml_User_Login_Handler
{
    private static $instance;
    public static function mo_saml_get_object()
    {
        if (isset(self::$instance)) {
            goto av;
        }
        $A4 = __CLASS__;
        self::$instance = new $A4();
        av:
        return self::$instance;
    }
    public function mo_saml_login_validate()
    {
        try {
            mo_login_validate();
        } catch (Mo_SAML_XMLSecLibs_Processing_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5);
        } catch (Mo_SAML_DOM_Extension_Disabled_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5);
        } catch (Mo_SAML_Invalid_XML_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5);
        } catch (Mo_SAML_Invalid_Assertion_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5);
        } catch (Mo_SAML_Element_Decryption_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5);
        } catch (Mo_SAML_Invalid_Audience_URI_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5);
        } catch (Mo_SAML_Invalid_License_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5);
        } catch (Mo_SAML_Duplicate_SAML_Response_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5);
        } catch (Mo_SAML_Invalid_Logout_Request_Exception $k5) {
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
