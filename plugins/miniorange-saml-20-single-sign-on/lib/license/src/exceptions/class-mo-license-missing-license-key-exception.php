<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



namespace MOSAML\LicenseLibrary\Exceptions;

if (defined("\x41\x42\x53\x50\101\x54\110")) {
    goto SY;
}
exit;
SY:
class Mo_License_Missing_License_Key_Exception extends \Exception
{
    const MESSAGE = "\115\111\123\x53\111\116\x47\137\114\111\103\105\116\123\x45\x5f\113\x45\131";
    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
