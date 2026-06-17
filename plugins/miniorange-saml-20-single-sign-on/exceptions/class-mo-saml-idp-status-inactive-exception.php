<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



if (defined("\x41\x42\x53\x50\101\x54\110")) {
    goto s4;
}
exit;
s4:
class Mo_SAML_IDP_Status_Inactive_Exception extends Exception
{
    public function __construct($h9)
    {
        $h9 = $h9;
        $fO = 23;
        parent::__construct($h9, $fO, null);
    }
}
