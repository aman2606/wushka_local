<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



class AESEncryption
{
    public static function encrypt_data($jr, $R2)
    {
        $R2 = openssl_digest($R2, "\163\150\x61\62\65\x36");
        $lO = "\141\x65\x73\x2d\x31\x32\70\x2d\x65\143\142";
        $nK = openssl_encrypt($jr, $lO, $R2, OPENSSL_RAW_DATA || OPENSSL_ZERO_PADDING);
        return base64_encode($nK);
    }
    public static function decrypt_data($jr, $R2)
    {
        $h6 = base64_decode($jr);
        $R2 = openssl_digest($R2, "\163\x68\x61\62\65\66");
        $lO = "\x41\x45\123\55\x31\x32\x38\x2d\x45\x43\x42";
        $oL = openssl_cipher_iv_length($lO);
        $j_ = substr($h6, 0, $oL);
        $jr = substr($h6, $oL);
        $u4 = openssl_decrypt($jr, $lO, $R2, OPENSSL_RAW_DATA || OPENSSL_ZERO_PADDING, $j_);
        return $u4;
    }
}
