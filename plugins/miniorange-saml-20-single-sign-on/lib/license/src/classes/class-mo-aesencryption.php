<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



namespace MOSAML\LicenseLibrary\Classes;

if (defined("\101\102\123\120\101\124\x48")) {
    goto Th;
}
exit;
Th:
class Mo_AESEncryption
{
    public static function encrypt_data($jr, $R2)
    {
        $R2 = openssl_digest($R2, "\163\x68\x61\62\65\66");
        $lO = "\141\x65\163\x2d\x31\62\x38\55\x65\143\142";
        $Xj = openssl_encrypt($jr, $lO, $R2, OPENSSL_RAW_DATA || OPENSSL_ZERO_PADDING);
        return base64_encode($Xj);
    }
    public static function decrypt_data($jr, $R2)
    {
        $gk = base64_decode($jr);
        $R2 = openssl_digest($R2, "\163\x68\141\x32\65\x36");
        $lO = "\x41\105\x53\x2d\x31\x32\x38\55\105\103\102";
        $Lz = openssl_cipher_iv_length($lO);
        $j_ = substr($gk, 0, $Lz);
        $jr = substr($gk, $Lz);
        $u4 = openssl_decrypt($jr, $lO, $R2, OPENSSL_RAW_DATA || OPENSSL_ZERO_PADDING, $j_);
        return $u4;
    }
}
