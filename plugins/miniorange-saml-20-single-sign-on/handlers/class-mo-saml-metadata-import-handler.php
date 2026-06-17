<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



if (defined("\x41\102\123\120\x41\x54\x48")) {
    goto gY;
}
exit;
gY:
require_once Mo_Saml_Plugin_Files::MO_SAML_METADATA_READER_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_DOM_DISABLED_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_INVALID_XML_EXCEPTION;
require_once Mo_Saml_Plugin_Files::MO_SAML_EXCEPTION_HANDLER;
class Mo_Saml_Metadata_Import_Handler
{
    private static $instance;
    public static function mo_saml_get_object()
    {
        if (isset(self::$instance)) {
            goto qK;
        }
        $A4 = __CLASS__;
        self::$instance = new $A4();
        qK:
        return self::$instance;
    }
    public function mo_saml_upload_metadata($tH, $BB = '', $VK = false, $q4 = false)
    {
        try {
            $IU = Mo_SAML_Plugin::mo_saml_get_object();
            $IU->upload_metadata($tH, $BB, $VK, $q4);
        } catch (Mo_SAML_Metadata_Reader_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5, true);
        } catch (Mo_SAML_DOM_Extension_Disabled_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5, true);
        } catch (Mo_SAML_Invalid_XML_Exception $k5) {
            Mo_Saml_Data_Access_Object::mo_saml_update_option(Mo_Saml_Options_Plugin_Admin::ADMIN_NOTICES_MESSAGE, "\111\156\x76\x61\154\x69\144\40\x66\x69\x6c\145\x2e\40\x50\x6c\x65\x61\x73\x65\40\165\160\154\157\x61\x64\40\141\x20\166\141\x6c\151\144\x20\130\x4d\x4c\40\x66\x69\154\x65\x2e");
            SAMLSPUtilities::mo_saml_show_error_message();
        }
    }
    public function mo_saml_handle_upload_metadata()
    {
        try {
            $IU = Mo_SAML_Plugin::mo_saml_get_object();
            $IU->_handle_upload_metadata();
        } catch (Mo_SAML_Metadata_Reader_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5, true);
        } catch (Mo_SAML_DOM_Extension_Disabled_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5, true);
        } catch (Mo_SAML_Invalid_XML_Exception $k5) {
            Mo_Saml_Exception_Handler::mo_saml_throw_exception($k5, true);
        }
    }
}
