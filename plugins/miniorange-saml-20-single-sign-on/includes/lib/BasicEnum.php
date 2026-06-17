<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



abstract class BasicEnum
{
    private static $constCacheArray = null;
    public static function getConstants()
    {
        if (!(self::$constCacheArray == null)) {
            goto EE;
        }
        self::$constCacheArray = array();
        EE:
        $Zl = get_called_class();
        if (!empty(self::$constCacheArray[$Zl])) {
            goto cp;
        }
        $uB = new ReflectionClass($Zl);
        self::$constCacheArray[$Zl] = $uB->getConstants();
        cp:
        return self::$constCacheArray[$Zl];
    }
    public static function isValidName($or, $io = false)
    {
        $xI = self::getConstants();
        if (!$io) {
            goto z1;
        }
        return !empty($xI[$or]);
        z1:
        $uy = array_map("\x73\164\x72\164\157\154\157\x77\145\x72", array_keys($xI));
        return SAMLSPUtilities::mo_saml_in_array(strtolower($or), $uy);
    }
    public static function isValidValue($EB, $io = true)
    {
        $Tr = array_values(self::getConstants());
        return SAMLSPUtilities::mo_saml_in_array($EB, $Tr, $io);
    }
}
