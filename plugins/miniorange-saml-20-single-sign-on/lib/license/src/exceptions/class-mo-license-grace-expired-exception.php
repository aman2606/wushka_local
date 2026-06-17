<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



namespace MOSAML\LicenseLibrary\Exceptions;

if (defined("\x41\102\x53\x50\101\x54\x48")) {
    goto bm;
}
exit;
bm:
class Mo_License_Grace_Expired_Exception extends \Exception
{
    const MESSAGE = "\114\111\103\x45\x4e\123\105\x5f\x47\x52\101\x43\x45\137\105\x58\120\x49\122\x45\x44";
    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
