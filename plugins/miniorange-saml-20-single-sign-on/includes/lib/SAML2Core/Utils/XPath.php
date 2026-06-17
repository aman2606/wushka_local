<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



namespace RobRichards\XMLSecLibs\Utils;

class XPath
{
    const ALPHANUMERIC = "\134\167\x5c\x64";
    const NUMERIC = "\134\144";
    const LETTERS = "\x5c\167";
    const EXTENDED_ALPHANUMERIC = "\x5c\x77\134\x64\x5c\x73\x5c\x2d\137\x3a\x5c\56";
    const SINGLE_QUOTE = "\47";
    const DOUBLE_QUOTE = "\42";
    const ALL_QUOTES = "\x5b\47\42\135";
    public static function filterAttrValue($EB, $G7 = self::ALL_QUOTES)
    {
        return preg_replace("\43" . $G7 . "\x23", '', $EB);
    }
    public static function filterAttrName($or, $Ee = self::EXTENDED_ALPHANUMERIC)
    {
        return preg_replace("\x23\x5b\x5e" . $Ee . "\x5d\x23", '', $or);
    }
}
