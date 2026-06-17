<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



if (defined("\x41\102\123\120\101\x54\110")) {
    goto oj;
}
exit;
oj:
spl_autoload_register("\x6d\x6f\x5f\163\x61\x6d\x6c\x5f\x6c\x69\143\145\156\163\145\x5f\x63\x6c\x61\163\163\145\163\137\x61\x75\164\x6f\x6c\157\x61\144\x65\x72");
function mo_saml_license_classes_autoloader($A4)
{
    $Pi = "\115\x4f\123\101\x4d\x4c\x5c\114\151\143\x65\x6e\x73\x65\114\x69\x62\162\x61\162\171";
    if (!(strpos($A4, $Pi) !== 0)) {
        goto db;
    }
    return;
    db:
    $iE = __DIR__ . DIRECTORY_SEPARATOR . "\163\162\143";
    $Qp = strtolower(str_replace("\134", DIRECTORY_SEPARATOR, substr($A4, strlen($Pi))));
    $Pt = strrchr($Qp, DIRECTORY_SEPARATOR);
    $Hi = "\x63\x6c\141\x73\x73\55" . str_replace("\137", "\55", str_replace(DIRECTORY_SEPARATOR, '', $Pt)) . "\56\x70\x68\x70";
    $v_ = str_replace($Pt, DIRECTORY_SEPARATOR . $Hi, $Qp);
    $CO = $iE . $v_;
    if (!file_exists($CO)) {
        goto VA;
    }
    require_once $CO;
    VA:
}
