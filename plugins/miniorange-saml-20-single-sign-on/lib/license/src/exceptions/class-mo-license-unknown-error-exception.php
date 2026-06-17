<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



namespace MOSAML\LicenseLibrary\Exceptions;

if (defined("\x41\102\x53\x50\101\124\x48")) {
    goto LA;
}
exit;
LA:
class Mo_License_Unknown_Error_Exception extends \Exception
{
    const MESSAGE = "\125\116\113\x4e\x4f\127\116\x5f\x45\122\x52\x4f\122";
    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
