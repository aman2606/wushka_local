<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



namespace MOSAML\LicenseLibrary\Exceptions;

if (defined("\x41\x42\123\120\101\x54\110")) {
    goto cE;
}
exit;
cE:
class Mo_License_Invalid_Expiry_Date_Exception extends \Exception
{
    const MESSAGE = "\x4d\x49\123\x53\x49\x4e\x47\x5f\117\x52\x5f\111\x4e\126\x41\114\x49\x44\137\105\x58\120\111\122\131\x5f\x44\101\x54\105";
    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
