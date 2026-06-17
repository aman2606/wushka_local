<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



namespace MOSAML\LicenseLibrary\Exceptions;

if (defined("\x41\x42\x53\120\101\124\x48")) {
    goto qC;
}
exit;
qC:
class Mo_License_Missing_Or_Invalid_Customer_Key_Exception extends \Exception
{
    const MESSAGE = "\115\111\x53\x53\111\116\x47\x5f\x4f\x52\x5f\x49\116\x56\x41\114\x49\104\137\103\x55\x53\124\x4f\x4d\x45\x52\137\113\x45\131";
    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
