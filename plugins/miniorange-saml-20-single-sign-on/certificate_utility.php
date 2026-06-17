<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



class CertificateUtility
{
    public static function generate_certificate($H4, $ha, $Hz)
    {
        $Q9 = openssl_pkey_new();
        $H5 = openssl_csr_new($H4, $Q9, $ha);
        $MX = openssl_csr_sign($H5, null, $Q9, $Hz, $ha, time());
        openssl_csr_export($H5, $Gp);
        openssl_x509_export($MX, $yc);
        openssl_pkey_export($Q9, $oX);
        z3:
        if (!(($G2 = openssl_error_string()) !== false)) {
            goto H1;
        }
        error_log("\x43\x65\162\x74\151\x66\x69\143\141\x74\145\x55\x74\x69\154\x69\164\171\72\x20\x45\x72\162\x6f\162\40\x67\x65\x6e\x65\162\x61\x74\x69\x6e\147\x20\x63\145\x72\164\x69\146\x69\x63\141\164\x65\56\40" . $G2);
        goto z3;
        H1:
        $Xi = array("\x70\x75\142\154\151\143\x5f\x6b\x65\171" => $yc, "\x70\x72\x69\166\141\164\x65\137\153\145\x79" => $oX);
        return $Xi;
    }
}
