<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



namespace MOSAML\LicenseLibrary\Exceptions;

if (defined("\x41\x42\123\x50\x41\124\110")) {
    goto zp;
}
exit;
zp:
class Mo_License_Missing_Customer_Email_Exception extends \Exception
{
    const MESSAGE = "\x4d\111\x53\x53\x49\x4e\x47\x5f\x43\x55\x53\x54\x4f\115\x45\x52\137\x45\x4d\101\x49\x4c";
    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
