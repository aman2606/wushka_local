<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



namespace RobRichards\XMLSecLibs;

use DOMElement;
use Exception;
class XMLSecurityKey
{
    const TRIPLEDES_CBC = "\150\x74\164\x70\x3a\x2f\57\167\167\167\x2e\167\x33\x2e\x6f\x72\x67\57\62\x30\60\x31\57\x30\x34\57\170\x6d\154\145\156\143\x23\164\x72\x69\160\154\145\144\145\x73\x2d\x63\x62\x63";
    const AES128_CBC = "\x68\x74\164\x70\72\57\57\167\167\x77\56\167\x33\56\x6f\x72\147\57\62\x30\x30\61\x2f\60\64\57\x78\155\x6c\x65\156\143\43\x61\145\x73\x31\62\x38\x2d\x63\x62\x63";
    const AES192_CBC = "\150\x74\164\160\72\x2f\57\x77\167\x77\x2e\x77\x33\x2e\x6f\x72\147\x2f\x32\60\60\x31\x2f\60\64\x2f\x78\x6d\x6c\x65\156\143\x23\x61\x65\x73\61\x39\x32\x2d\143\142\143";
    const AES256_CBC = "\x68\x74\x74\160\72\57\57\167\167\167\x2e\167\x33\56\x6f\162\147\x2f\x32\x30\x30\x31\x2f\60\x34\57\x78\x6d\154\145\156\143\x23\141\x65\x73\62\65\66\x2d\x63\142\x63";
    const AES128_GCM = "\150\164\164\x70\72\x2f\57\167\167\x77\x2e\167\x33\x2e\x6f\x72\147\x2f\x32\60\x30\71\57\170\x6d\154\x65\x6e\x63\x31\x31\x23\141\x65\x73\x31\62\x38\x2d\147\x63\x6d";
    const AES192_GCM = "\150\x74\x74\x70\x3a\57\57\167\x77\x77\x2e\x77\x33\x2e\x6f\x72\147\x2f\62\60\x30\71\x2f\x78\x6d\x6c\x65\156\143\61\61\x23\x61\145\x73\61\71\x32\55\147\143\155";
    const AES256_GCM = "\150\x74\x74\160\x3a\x2f\57\x77\x77\167\56\x77\x33\x2e\x6f\162\147\x2f\x32\x30\60\x39\x2f\170\x6d\x6c\x65\156\143\x31\x31\x23\141\145\x73\x32\x35\x36\55\147\x63\155";
    const RSA_1_5 = "\x68\x74\164\160\x3a\57\57\167\x77\x77\x2e\x77\x33\x2e\157\162\x67\x2f\x32\x30\60\61\57\60\64\57\170\155\154\145\x6e\x63\43\162\163\141\x2d\61\137\65";
    const RSA_OAEP_MGF1P = "\150\164\x74\160\x3a\57\x2f\x77\167\x77\56\x77\x33\x2e\x6f\x72\147\57\x32\x30\60\x31\x2f\x30\x34\57\x78\155\154\x65\x6e\x63\43\162\163\x61\55\157\x61\145\160\x2d\155\147\146\x31\x70";
    const RSA_OAEP = "\150\164\x74\x70\x3a\x2f\57\x77\167\167\x2e\167\63\x2e\157\162\x67\x2f\62\x30\60\71\57\170\x6d\x6c\x65\156\x63\x31\61\43\162\163\x61\55\x6f\141\145\160";
    const DSA_SHA1 = "\x68\164\164\160\72\x2f\57\x77\167\167\56\x77\x33\56\x6f\162\x67\57\62\x30\x30\x30\x2f\60\71\57\x78\155\x6c\x64\x73\x69\x67\x23\144\x73\x61\x2d\x73\150\x61\x31";
    const RSA_SHA1 = "\x68\164\x74\160\x3a\x2f\57\167\167\167\56\167\63\x2e\x6f\x72\147\57\62\60\x30\60\57\x30\71\x2f\170\x6d\x6c\144\x73\x69\x67\x23\162\163\x61\55\163\x68\141\x31";
    const RSA_SHA256 = "\x68\164\164\160\x3a\x2f\x2f\167\x77\167\x2e\167\63\56\157\162\x67\x2f\x32\x30\x30\x31\x2f\60\x34\x2f\170\155\154\x64\163\x69\x67\55\155\157\x72\x65\x23\x72\163\x61\55\x73\150\141\x32\x35\66";
    const RSA_SHA384 = "\x68\x74\x74\x70\x3a\x2f\57\x77\x77\167\x2e\x77\x33\56\157\x72\x67\57\x32\60\x30\61\x2f\60\x34\57\x78\155\154\x64\163\151\147\x2d\155\x6f\162\x65\43\162\163\141\55\x73\150\141\x33\70\x34";
    const RSA_SHA512 = "\150\x74\164\160\72\x2f\x2f\167\167\167\x2e\x77\63\x2e\x6f\162\147\57\62\60\60\61\57\60\64\57\x78\x6d\x6c\144\x73\151\x67\x2d\155\157\162\x65\x23\x72\x73\141\x2d\163\150\x61\65\61\62";
    const HMAC_SHA1 = "\150\164\164\x70\x3a\x2f\57\x77\167\167\x2e\167\x33\x2e\157\162\147\x2f\x32\x30\x30\60\57\x30\71\57\x78\x6d\x6c\x64\163\151\147\x23\x68\x6d\x61\143\x2d\163\150\x61\61";
    const AUTHTAG_LENGTH = 16;
    private $cryptParams = array();
    public $type = 0;
    public $key = null;
    public $passphrase = '';
    public $iv = null;
    public $name = null;
    public $keyChain = null;
    public $isEncrypted = false;
    public $encryptedCtx = null;
    public $guid = null;
    private $x509Certificate = null;
    private $X509Thumbprint = null;
    public function __construct($Gf, $e_ = null)
    {
        switch ($Gf) {
            case self::TRIPLEDES_CBC:
                $this->cryptParams["\154\151\142\x72\x61\162\x79"] = "\157\x70\145\156\x73\163\154";
                $this->cryptParams["\143\x69\160\x68\145\162"] = "\x64\x65\163\55\145\x64\x65\x33\55\143\x62\143";
                $this->cryptParams["\x74\x79\x70\x65"] = "\x73\x79\x6d\x6d\145\164\x72\151\143";
                $this->cryptParams["\155\145\x74\150\x6f\x64"] = "\150\164\164\x70\72\x2f\x2f\167\x77\167\x2e\167\63\56\157\162\147\57\62\x30\x30\x31\57\60\64\x2f\x78\x6d\154\145\156\143\43\164\x72\x69\x70\x6c\145\x64\145\163\55\x63\142\x63";
                $this->cryptParams["\x6b\x65\x79\163\x69\x7a\x65"] = 24;
                $this->cryptParams["\142\154\157\x63\x6b\163\x69\x7a\x65"] = 8;
                goto xt;
            case self::AES128_CBC:
                $this->cryptParams["\x6c\151\142\x72\x61\162\x79"] = "\x6f\x70\x65\156\163\x73\154";
                $this->cryptParams["\143\151\160\150\145\162"] = "\141\x65\163\55\x31\x32\x38\x2d\x63\142\143";
                $this->cryptParams["\x74\171\x70\x65"] = "\x73\x79\x6d\x6d\145\164\x72\151\x63";
                $this->cryptParams["\155\145\164\x68\157\x64"] = "\x68\164\164\x70\72\57\57\167\167\167\x2e\167\x33\x2e\x6f\x72\x67\x2f\x32\60\60\x31\57\60\64\57\170\155\x6c\x65\x6e\x63\x23\141\x65\163\61\62\x38\55\x63\142\143";
                $this->cryptParams["\x6b\145\x79\163\x69\x7a\x65"] = 16;
                $this->cryptParams["\x62\154\x6f\143\153\163\151\172\x65"] = 16;
                goto xt;
            case self::AES192_CBC:
                $this->cryptParams["\x6c\x69\x62\162\141\x72\171"] = "\157\x70\145\x6e\163\x73\x6c";
                $this->cryptParams["\143\x69\x70\150\x65\x72"] = "\x61\145\163\55\61\x39\62\x2d\x63\142\x63";
                $this->cryptParams["\164\x79\x70\x65"] = "\x73\171\x6d\x6d\x65\x74\162\151\143";
                $this->cryptParams["\x6d\x65\164\150\157\x64"] = "\150\x74\x74\160\72\57\57\167\167\x77\x2e\167\63\56\157\162\147\x2f\62\x30\60\x31\x2f\60\64\x2f\170\155\x6c\x65\156\x63\43\x61\x65\x73\61\x39\x32\x2d\143\x62\x63";
                $this->cryptParams["\153\x65\x79\x73\x69\x7a\145"] = 24;
                $this->cryptParams["\x62\x6c\157\143\153\163\151\172\x65"] = 16;
                goto xt;
            case self::AES256_CBC:
                $this->cryptParams["\x6c\151\142\162\x61\162\x79"] = "\157\x70\x65\x6e\163\163\x6c";
                $this->cryptParams["\x63\151\x70\150\x65\x72"] = "\141\145\x73\x2d\62\x35\x36\55\x63\x62\143";
                $this->cryptParams["\164\x79\160\x65"] = "\x73\171\155\155\145\x74\x72\151\143";
                $this->cryptParams["\155\145\164\x68\157\144"] = "\x68\x74\164\160\72\57\x2f\x77\167\167\56\x77\63\56\x6f\x72\147\57\x32\x30\60\x31\57\60\x34\x2f\170\x6d\154\145\156\x63\x23\141\x65\x73\62\x35\x36\x2d\143\142\x63";
                $this->cryptParams["\153\x65\x79\163\151\x7a\145"] = 32;
                $this->cryptParams["\142\154\x6f\143\x6b\163\151\x7a\145"] = 16;
                goto xt;
            case self::AES128_GCM:
                $this->cryptParams["\154\x69\142\162\x61\162\171"] = "\x6f\160\x65\156\163\x73\154";
                $this->cryptParams["\x63\151\160\x68\x65\x72"] = "\141\x65\x73\55\61\x32\x38\55\147\x63\155";
                $this->cryptParams["\164\171\x70\145"] = "\x73\171\155\155\x65\x74\162\151\x63";
                $this->cryptParams["\x6d\x65\x74\150\x6f\x64"] = "\150\x74\164\160\72\57\x2f\x77\167\x77\x2e\x77\x33\56\157\x72\x67\57\x32\60\x30\x39\x2f\170\155\x6c\145\x6e\143\61\61\x23\141\x65\163\x31\x32\x38\55\147\143\x6d";
                $this->cryptParams["\x6b\145\x79\163\151\x7a\x65"] = 16;
                $this->cryptParams["\142\x6c\157\143\153\x73\x69\x7a\145"] = 16;
                goto xt;
            case self::AES192_GCM:
                $this->cryptParams["\154\151\142\x72\141\162\x79"] = "\157\160\x65\x6e\x73\163\154";
                $this->cryptParams["\143\x69\x70\150\x65\162"] = "\141\x65\163\55\61\71\x32\55\147\143\155";
                $this->cryptParams["\164\171\160\x65"] = "\163\x79\x6d\155\145\164\x72\151\x63";
                $this->cryptParams["\155\x65\164\150\x6f\144"] = "\150\x74\164\160\x3a\57\57\x77\167\167\56\167\63\56\157\162\147\x2f\x32\x30\60\71\x2f\x78\x6d\x6c\145\156\x63\61\61\43\141\145\x73\61\x39\x32\55\147\x63\155";
                $this->cryptParams["\153\x65\x79\x73\151\x7a\x65"] = 24;
                $this->cryptParams["\x62\154\157\x63\x6b\163\151\172\145"] = 16;
                goto xt;
            case self::AES256_GCM:
                $this->cryptParams["\x6c\151\x62\162\141\x72\x79"] = "\x6f\160\145\156\x73\163\154";
                $this->cryptParams["\x63\151\160\x68\x65\x72"] = "\x61\x65\x73\55\62\x35\66\x2d\x67\x63\155";
                $this->cryptParams["\x74\x79\160\145"] = "\x73\x79\155\x6d\145\164\x72\x69\x63";
                $this->cryptParams["\155\145\164\150\x6f\x64"] = "\150\x74\x74\160\x3a\x2f\57\167\167\x77\56\x77\63\x2e\157\162\x67\x2f\62\x30\x30\71\x2f\x78\155\154\x65\x6e\143\x31\61\43\141\x65\x73\62\x35\x36\x2d\x67\143\x6d";
                $this->cryptParams["\x6b\145\171\163\151\x7a\x65"] = 32;
                $this->cryptParams["\142\154\x6f\143\153\x73\151\x7a\x65"] = 16;
                goto xt;
            case self::RSA_1_5:
                $this->cryptParams["\x6c\x69\x62\162\141\x72\x79"] = "\x6f\x70\x65\x6e\163\163\154";
                $this->cryptParams["\x70\x61\x64\x64\151\156\147"] = OPENSSL_PKCS1_PADDING;
                $this->cryptParams["\x6d\145\x74\150\x6f\x64"] = "\x68\x74\164\x70\72\57\57\167\167\x77\x2e\167\x33\56\157\162\x67\57\x32\x30\60\61\x2f\x30\x34\x2f\170\155\x6c\145\x6e\143\x23\162\163\x61\x2d\x31\x5f\x35";
                if (!(is_array($e_) && !empty($e_["\164\171\160\145"]))) {
                    goto U1;
                }
                if (!($e_["\x74\171\160\x65"] == "\x70\165\142\154\151\x63" || $e_["\x74\171\x70\x65"] == "\x70\162\x69\x76\141\164\145")) {
                    goto hb;
                }
                $this->cryptParams["\164\x79\160\145"] = $e_["\x74\171\x70\145"];
                goto xt;
                hb:
                U1:
                throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\103\x65\x72\164\151\x66\151\143\x61\x74\x65\40\x22\164\x79\160\x65\42\x20\x28\x70\x72\x69\x76\141\164\x65\x2f\x70\x75\142\x6c\x69\143\51\x20\155\165\163\x74\x20\142\x65\x20\x70\141\x73\x73\x65\x64\40\166\151\x61\x20\160\x61\162\x61\x6d\145\164\x65\x72\x73");
            case self::RSA_OAEP_MGF1P:
                $this->cryptParams["\154\151\x62\x72\141\162\x79"] = "\157\x70\145\x6e\163\x73\154";
                $this->cryptParams["\x70\x61\144\x64\x69\x6e\x67"] = OPENSSL_PKCS1_OAEP_PADDING;
                $this->cryptParams["\x6d\145\x74\150\157\144"] = "\x68\x74\x74\x70\72\x2f\57\x77\167\167\56\x77\x33\x2e\x6f\162\x67\57\x32\x30\x30\x31\57\x30\64\x2f\170\155\154\x65\156\143\43\x72\x73\141\x2d\x6f\x61\x65\160\x2d\155\147\x66\61\x70";
                $this->cryptParams["\x68\141\x73\x68"] = null;
                if (!(is_array($e_) && !empty($e_["\164\x79\x70\145"]))) {
                    goto E5;
                }
                if (!($e_["\x74\x79\x70\x65"] == "\x70\165\x62\154\x69\143" || $e_["\x74\x79\160\x65"] == "\160\162\x69\166\141\x74\x65")) {
                    goto UC;
                }
                $this->cryptParams["\x74\x79\x70\x65"] = $e_["\x74\171\x70\145"];
                goto xt;
                UC:
                E5:
                throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\103\x65\x72\x74\151\x66\151\x63\141\x74\145\x20\x22\x74\x79\x70\145\42\x20\50\160\162\151\166\141\164\145\x2f\160\x75\142\154\151\x63\51\x20\x6d\x75\163\x74\40\142\145\x20\160\141\163\163\x65\144\x20\166\151\141\x20\160\x61\162\x61\155\x65\x74\x65\162\x73");
            case self::RSA_OAEP:
                $this->cryptParams["\154\x69\142\x72\141\x72\x79"] = "\x6f\x70\x65\x6e\x73\x73\x6c";
                $this->cryptParams["\160\141\x64\x64\151\156\x67"] = OPENSSL_PKCS1_OAEP_PADDING;
                $this->cryptParams["\155\145\x74\150\x6f\144"] = "\x68\164\x74\x70\x3a\57\57\x77\167\167\56\167\x33\x2e\x6f\x72\147\57\x32\60\x30\71\x2f\x78\155\x6c\x65\x6e\143\x31\x31\x23\x72\163\x61\x2d\x6f\x61\145\160";
                $this->cryptParams["\150\x61\x73\150"] = "\150\x74\164\x70\72\x2f\x2f\x77\167\167\56\x77\x33\x2e\x6f\x72\x67\57\62\60\60\x39\57\170\x6d\154\145\x6e\x63\x31\x31\x23\155\x67\x66\61\x73\150\x61\x31";
                if (!(is_array($e_) && !empty($e_["\x74\171\160\145"]))) {
                    goto ke;
                }
                if (!($e_["\x74\171\x70\145"] == "\160\x75\142\x6c\x69\x63" || $e_["\x74\x79\160\x65"] == "\x70\x72\151\x76\141\164\x65")) {
                    goto QG;
                }
                $this->cryptParams["\x74\171\x70\145"] = $e_["\164\171\x70\145"];
                goto xt;
                QG:
                ke:
                throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x43\145\162\x74\151\146\x69\x63\x61\164\x65\40\x22\164\x79\160\x65\x22\40\50\x70\162\151\166\x61\164\145\57\x70\x75\x62\154\x69\x63\51\x20\155\165\163\164\40\142\145\40\160\141\163\163\145\144\40\166\151\x61\40\x70\x61\x72\141\155\145\x74\x65\x72\x73");
            case self::RSA_SHA1:
                $this->cryptParams["\x6c\x69\142\162\141\x72\171"] = "\157\160\x65\x6e\163\163\x6c";
                $this->cryptParams["\155\145\164\150\157\144"] = "\150\164\164\x70\72\x2f\x2f\167\x77\x77\x2e\167\63\56\x6f\x72\x67\x2f\62\x30\x30\x30\57\60\71\57\170\155\154\x64\163\x69\147\43\162\x73\x61\55\163\x68\141\61";
                $this->cryptParams["\x70\141\144\144\151\156\147"] = OPENSSL_PKCS1_PADDING;
                if (!(is_array($e_) && !empty($e_["\164\x79\x70\145"]))) {
                    goto tu;
                }
                if (!($e_["\x74\171\160\145"] == "\160\x75\142\154\x69\x63" || $e_["\x74\x79\160\145"] == "\x70\162\151\x76\x61\x74\145")) {
                    goto rv;
                }
                $this->cryptParams["\164\171\x70\x65"] = $e_["\164\171\160\x65"];
                goto xt;
                rv:
                tu:
                throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x43\x65\162\x74\x69\146\x69\143\141\x74\145\40\42\x74\x79\160\x65\x22\x20\50\160\x72\151\166\141\x74\145\x2f\160\165\x62\154\x69\143\51\40\155\x75\x73\164\40\x62\x65\40\160\141\163\163\x65\x64\40\166\x69\x61\40\160\x61\162\141\155\x65\164\145\162\163");
            case self::RSA_SHA256:
                $this->cryptParams["\x6c\151\x62\162\x61\162\171"] = "\x6f\x70\145\x6e\x73\x73\x6c";
                $this->cryptParams["\155\145\x74\150\157\x64"] = "\150\x74\164\160\72\x2f\x2f\167\x77\x77\56\167\63\x2e\157\162\x67\x2f\x32\x30\60\x31\57\x30\x34\x2f\170\155\x6c\x64\x73\x69\147\x2d\x6d\x6f\162\145\43\162\163\x61\x2d\163\x68\141\x32\65\x36";
                $this->cryptParams["\160\x61\x64\144\151\156\x67"] = OPENSSL_PKCS1_PADDING;
                $this->cryptParams["\144\151\147\x65\x73\x74"] = "\123\x48\x41\62\65\66";
                if (!(is_array($e_) && !empty($e_["\x74\x79\x70\145"]))) {
                    goto wC;
                }
                if (!($e_["\x74\x79\160\145"] == "\160\x75\x62\x6c\151\x63" || $e_["\164\x79\160\x65"] == "\160\x72\151\x76\141\x74\x65")) {
                    goto Qh;
                }
                $this->cryptParams["\x74\x79\160\145"] = $e_["\164\x79\160\x65"];
                goto xt;
                Qh:
                wC:
                throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\103\145\162\164\x69\x66\x69\143\x61\164\145\40\42\x74\x79\x70\145\42\40\x28\x70\162\x69\x76\141\x74\145\x2f\x70\165\x62\x6c\x69\x63\x29\40\155\165\163\164\40\142\x65\40\160\x61\x73\x73\x65\x64\40\x76\151\x61\40\x70\141\162\x61\155\145\x74\145\x72\163");
            case self::RSA_SHA384:
                $this->cryptParams["\x6c\151\142\162\141\162\171"] = "\157\x70\x65\156\x73\163\154";
                $this->cryptParams["\155\x65\x74\150\x6f\x64"] = "\x68\164\164\x70\x3a\57\57\167\167\167\x2e\167\63\x2e\157\x72\x67\57\62\x30\x30\x31\57\60\x34\57\x78\x6d\x6c\x64\x73\x69\x67\x2d\155\x6f\x72\x65\43\x72\163\141\55\x73\150\141\63\70\64";
                $this->cryptParams["\160\141\x64\x64\151\x6e\x67"] = OPENSSL_PKCS1_PADDING;
                $this->cryptParams["\x64\151\147\145\163\164"] = "\x53\x48\x41\x33\x38\x34";
                if (!(is_array($e_) && !empty($e_["\x74\171\x70\x65"]))) {
                    goto eQ;
                }
                if (!($e_["\x74\x79\x70\145"] == "\x70\165\142\154\x69\143" || $e_["\164\x79\x70\x65"] == "\x70\162\x69\166\x61\x74\145")) {
                    goto kD;
                }
                $this->cryptParams["\164\x79\160\x65"] = $e_["\164\171\160\x65"];
                goto xt;
                kD:
                eQ:
                throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x43\x65\x72\x74\x69\x66\x69\143\x61\x74\x65\x20\x22\x74\171\160\x65\x22\x20\50\x70\162\x69\166\x61\x74\145\x2f\x70\x75\142\154\x69\x63\51\x20\x6d\x75\163\164\x20\x62\x65\x20\x70\x61\163\x73\x65\x64\x20\166\x69\141\x20\160\141\x72\x61\155\145\x74\145\162\163");
            case self::RSA_SHA512:
                $this->cryptParams["\x6c\x69\x62\162\x61\x72\171"] = "\157\x70\x65\x6e\163\163\154";
                $this->cryptParams["\x6d\x65\x74\150\157\x64"] = "\x68\164\x74\160\72\x2f\57\x77\x77\167\x2e\167\x33\x2e\157\162\147\x2f\x32\x30\60\61\x2f\x30\64\x2f\170\x6d\x6c\144\163\151\147\x2d\155\x6f\x72\x65\43\x72\x73\141\x2d\x73\x68\x61\x35\x31\62";
                $this->cryptParams["\160\141\x64\144\x69\x6e\x67"] = OPENSSL_PKCS1_PADDING;
                $this->cryptParams["\144\x69\x67\145\163\164"] = "\x53\110\101\65\61\x32";
                if (!(is_array($e_) && !empty($e_["\164\171\x70\x65"]))) {
                    goto mL;
                }
                if (!($e_["\164\171\160\x65"] == "\160\x75\x62\x6c\x69\x63" || $e_["\164\171\160\x65"] == "\x70\x72\x69\166\141\164\145")) {
                    goto XQ;
                }
                $this->cryptParams["\164\x79\x70\x65"] = $e_["\164\x79\160\x65"];
                goto xt;
                XQ:
                mL:
                throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x43\145\162\x74\151\x66\x69\x63\141\x74\x65\x20\x22\164\x79\160\x65\x22\x20\50\160\x72\151\x76\x61\164\145\57\x70\x75\142\x6c\x69\x63\51\40\155\x75\163\164\x20\142\145\40\160\141\x73\x73\145\144\40\x76\151\x61\x20\160\141\162\x61\155\145\x74\x65\162\163");
            case self::HMAC_SHA1:
                $this->cryptParams["\154\151\x62\162\141\162\171"] = $Gf;
                $this->cryptParams["\155\145\x74\x68\x6f\x64"] = "\x68\x74\x74\x70\x3a\x2f\57\167\167\167\x2e\167\x33\56\x6f\x72\x67\57\62\x30\x30\60\57\60\x39\57\170\155\154\144\163\x69\147\43\150\155\141\x63\x2d\163\x68\x61\x31";
                goto xt;
            default:
                throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x49\x6e\x76\141\x6c\x69\x64\40\113\x65\171\40\x54\x79\160\145");
        }
        wu:
        xt:
        $this->type = $Gf;
    }
    public function getSymmetricKeySize()
    {
        if (!empty($this->cryptParams["\153\x65\171\x73\x69\x7a\145"])) {
            goto ru;
        }
        return null;
        ru:
        return $this->cryptParams["\153\145\x79\163\151\x7a\x65"];
    }
    public function generateSessionKey()
    {
        if (!empty($this->cryptParams["\x6b\145\171\163\151\x7a\145"])) {
            goto BF;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\125\x6e\153\x6e\157\167\x6e\x20\153\145\171\40\x73\x69\172\x65\40\x66\x6f\162\40\x74\171\x70\145\x20\42" . esc_attr($this->type) . "\42\x2e");
        BF:
        $Qt = $this->cryptParams["\x6b\145\x79\163\x69\x7a\145"];
        $R2 = openssl_random_pseudo_bytes($Qt);
        if (!($this->type === self::TRIPLEDES_CBC)) {
            goto qL;
        }
        $Ev = 0;
        hz:
        if (!($Ev < strlen($R2))) {
            goto mC;
        }
        $MN = ord($R2[$Ev]) & 0xfe;
        $dP = 1;
        $Ej = 1;
        bV:
        if (!($Ej < 8)) {
            goto yY;
        }
        $dP ^= $MN >> $Ej & 1;
        KL:
        $Ej++;
        goto bV;
        yY:
        $MN |= $dP;
        $R2[$Ev] = chr($MN);
        ay:
        $Ev++;
        goto hz;
        mC:
        qL:
        $this->key = $R2;
        return $R2;
    }
    public static function getRawThumbprint($Fh)
    {
        $qb = explode("\12", $Fh);
        $jr = '';
        $RM = false;
        foreach ($qb as $Wz) {
            if (!$RM) {
                goto Pg;
            }
            if (!(strncmp($Wz, "\55\x2d\x2d\x2d\55\105\116\x44\40\x43\105\122\124\111\106\111\103\101\x54\105", 20) == 0)) {
                goto Uv;
            }
            goto Wf;
            Uv:
            $jr .= trim($Wz);
            goto I1;
            Pg:
            if (!(strncmp($Wz, "\x2d\55\x2d\55\x2d\x42\105\x47\x49\116\40\x43\105\x52\x54\x49\x46\x49\x43\101\x54\x45", 22) == 0)) {
                goto ud;
            }
            $RM = true;
            ud:
            I1:
            ww:
        }
        Wf:
        if (empty($jr)) {
            goto U2;
        }
        return strtolower(sha1(base64_decode($jr)));
        U2:
        return null;
    }
    public function loadKey($R2, $C9 = false, $IV = false)
    {
        if ($C9) {
            goto mx;
        }
        $this->key = $R2;
        goto pl;
        mx:
        $this->key = file_get_contents($R2);
        pl:
        if ($IV) {
            goto q5;
        }
        $this->x509Certificate = null;
        goto R7;
        q5:
        $this->key = openssl_x509_read($this->key);
        openssl_x509_export($this->key, $Jz);
        $this->x509Certificate = $Jz;
        $this->key = $Jz;
        R7:
        if (!($this->cryptParams["\x6c\x69\142\x72\141\162\171"] == "\x6f\x70\x65\156\x73\x73\154")) {
            goto WU;
        }
        switch ($this->cryptParams["\x74\171\x70\x65"]) {
            case "\x70\x75\x62\154\151\x63":
                if (!$IV) {
                    goto qq;
                }
                $this->X509Thumbprint = self::getRawThumbprint($this->key);
                qq:
                $this->key = openssl_get_publickey($this->key);
                if ($this->key) {
                    goto Sv;
                }
                throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x55\156\141\x62\154\x65\40\x74\x6f\40\x65\170\164\162\141\143\164\40\x70\x75\x62\154\151\x63\40\x6b\x65\x79");
                Sv:
                goto rD;
            case "\160\162\x69\x76\x61\164\x65":
                $this->key = openssl_get_privatekey($this->key, $this->passphrase);
                goto rD;
            case "\163\x79\155\155\x65\164\x72\x69\143":
                if (!(strlen($this->key) < $this->cryptParams["\x6b\145\171\x73\151\172\145"])) {
                    goto Gx;
                }
                throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\113\x65\x79\x20\x6d\x75\x73\164\40\x63\x6f\156\x74\x61\x69\x6e\x20\x61\164\40\154\x65\x61\163\164\40" . esc_attr($this->cryptParams["\x6b\x65\171\163\151\172\x65"]) . "\40\143\x68\x61\x72\x61\x63\164\145\162\163\40\146\x6f\x72\x20\164\x68\151\163\40\x63\151\160\150\x65\x72\54\x20\143\x6f\156\164\x61\x69\x6e\x73\x20" . esc_attr(strlen($this->key)));
                Gx:
                goto rD;
            default:
                throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x55\156\153\156\x6f\x77\156\40\x43\162\171\160\164\x20\x4b\x65\x79\x20\164\171\160\x65");
        }
        Mq:
        rD:
        WU:
    }
    private function padISO10126($jr, $kK)
    {
        if (!($kK > 256)) {
            goto tc;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x42\x6c\x6f\143\153\40\163\x69\x7a\145\40\x68\151\x67\x68\x65\162\40\164\x68\x61\156\x20\62\x35\66\x20\156\157\x74\40\x61\x6c\154\x6f\x77\145\144");
        tc:
        $Vn = $kK - strlen($jr) % $kK;
        $sU = chr($Vn);
        return $jr . str_repeat($sU, $Vn);
    }
    private function unpadISO10126($jr)
    {
        $Vn = substr($jr, -1);
        $oF = ord($Vn);
        return substr($jr, 0, -$oF);
    }
    private function encryptSymmetric($jr)
    {
        $this->iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cryptParams["\143\151\x70\150\145\162"]));
        $VD = null;
        if (\SAMLSPUtilities::mo_saml_in_array($this->cryptParams["\x63\x69\x70\150\145\x72"], array("\x61\x65\x73\55\x31\62\70\55\x67\x63\x6d", "\x61\x65\x73\x2d\x31\x39\62\x2d\x67\x63\x6d", "\141\x65\163\x2d\62\65\x36\55\x67\x63\155"))) {
            goto AZ;
        }
        $jr = $this->padISO10126($jr, $this->cryptParams["\142\x6c\x6f\x63\153\163\151\x7a\145"]);
        $Jq = openssl_encrypt($jr, $this->cryptParams["\x63\x69\160\x68\x65\x72"], $this->key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $this->iv);
        goto KI;
        AZ:
        if (!(version_compare(PHP_VERSION, "\67\x2e\61\56\60") < 0)) {
            goto yn;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\120\110\120\40\x37\56\61\x2e\60\40\151\163\x20\x72\145\161\165\151\162\x65\x64\40\164\157\x20\x75\163\145\x20\x41\x45\x53\x20\x47\x43\115\40\x61\x6c\147\x6f\162\x69\x74\x68\155\163");
        yn:
        $VD = openssl_random_pseudo_bytes(self::AUTHTAG_LENGTH);
        $Jq = openssl_encrypt($jr, $this->cryptParams["\x63\151\160\x68\x65\162"], $this->key, OPENSSL_RAW_DATA, $this->iv, $VD);
        KI:
        if (!(false === $Jq)) {
            goto KM;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\106\x61\x69\154\x75\x72\x65\x20\145\156\x63\x72\x79\x70\164\151\x6e\147\x20\104\141\x74\141\40\x28\157\160\145\156\x73\x73\x6c\40\163\x79\155\x6d\145\164\x72\151\143\51\x20\x2d\x20" . esc_attr(openssl_error_string()));
        KM:
        return $this->iv . $Jq . $VD;
    }
    private function decryptSymmetric($jr)
    {
        $vz = openssl_cipher_iv_length($this->cryptParams["\143\x69\x70\150\145\x72"]);
        $this->iv = substr($jr, 0, $vz);
        $jr = substr($jr, $vz);
        $VD = null;
        if (\SAMLSPUtilities::mo_saml_in_array($this->cryptParams["\x63\x69\160\150\145\162"], array("\141\x65\163\x2d\61\62\x38\x2d\x67\x63\155", "\x61\145\163\55\x31\x39\x32\55\x67\x63\x6d", "\x61\x65\x73\55\62\x35\x36\x2d\147\x63\x6d"))) {
            goto vR;
        }
        $SE = openssl_decrypt($jr, $this->cryptParams["\143\151\160\150\145\162"], $this->key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $this->iv);
        goto Eg;
        vR:
        if (!(version_compare(PHP_VERSION, "\x37\x2e\61\56\x30") < 0)) {
            goto eq;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\120\110\120\x20\x37\56\61\56\60\x20\151\x73\40\x72\x65\x71\165\x69\x72\x65\x64\40\164\x6f\40\165\163\x65\x20\101\x45\123\x20\x47\103\x4d\40\141\x6c\147\157\x72\151\164\150\155\x73");
        eq:
        $Uu = 0 - self::AUTHTAG_LENGTH;
        $VD = substr($jr, $Uu);
        $jr = substr($jr, 0, $Uu);
        $SE = openssl_decrypt($jr, $this->cryptParams["\143\x69\160\x68\145\162"], $this->key, OPENSSL_RAW_DATA, $this->iv, $VD);
        Eg:
        if (!(false === $SE)) {
            goto An;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x46\x61\151\x6c\165\162\x65\x20\144\x65\143\162\171\x70\x74\x69\156\147\x20\104\x61\164\x61\x20\50\157\x70\x65\x6e\x73\163\x6c\x20\x73\x79\x6d\155\145\x74\x72\x69\x63\51\40\x2d\x20" . esc_attr(openssl_error_string()));
        An:
        return null !== $VD ? $SE : $this->unpadISO10126($SE);
    }
    private function encryptPublic($jr)
    {
        if (openssl_public_encrypt($jr, $Jq, $this->key, $this->cryptParams["\160\x61\x64\144\151\x6e\147"])) {
            goto VB;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\106\x61\151\x6c\x75\162\x65\x20\x65\156\143\162\171\160\164\151\156\147\40\x44\141\164\141\40\50\157\160\x65\x6e\163\163\x6c\40\x70\165\x62\x6c\x69\143\x29\x20\x2d\40" . esc_attr(openssl_error_string()));
        VB:
        return $Jq;
    }
    private function decryptPublic($jr)
    {
        if (openssl_public_decrypt($jr, $SE, $this->key, $this->cryptParams["\160\141\x64\144\151\x6e\147"])) {
            goto lT;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x46\x61\x69\x6c\165\162\145\x20\144\x65\143\x72\x79\x70\164\x69\156\x67\40\104\141\164\x61\40\50\157\x70\145\x6e\x73\x73\x6c\40\x70\x75\x62\154\151\143\x29\40\55\x20" . esc_attr(openssl_error_string()));
        lT:
        return $SE;
    }
    private function encryptPrivate($jr)
    {
        if (openssl_private_encrypt($jr, $Jq, $this->key, $this->cryptParams["\160\141\144\x64\x69\x6e\147"])) {
            goto i5;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x46\x61\151\154\165\162\x65\x20\x65\x6e\143\x72\171\160\x74\151\x6e\x67\x20\x44\x61\x74\x61\x20\50\157\x70\145\x6e\163\x73\x6c\x20\x70\x72\x69\x76\x61\164\145\51\40\55\40" . esc_attr(openssl_error_string()));
        i5:
        return $Jq;
    }
    private function decryptPrivate($jr)
    {
        if (openssl_private_decrypt($jr, $SE, $this->key, $this->cryptParams["\160\141\144\x64\x69\x6e\x67"])) {
            goto B8;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\106\141\x69\154\165\x72\145\40\144\x65\x63\162\171\160\164\151\x6e\x67\x20\x44\x61\164\x61\40\x28\x6f\160\145\156\x73\163\154\40\160\162\151\x76\x61\x74\x65\51\40\x2d\40" . esc_attr(openssl_error_string()));
        B8:
        return $SE;
    }
    private function signOpenSSL($jr)
    {
        $w7 = OPENSSL_ALGO_SHA1;
        if (empty($this->cryptParams["\x64\151\x67\145\163\164"])) {
            goto il;
        }
        $w7 = $this->cryptParams["\144\x69\147\x65\163\164"];
        il:
        if (openssl_sign($jr, $lo, $this->key, $w7)) {
            goto Jg;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\106\141\151\x6c\165\x72\145\x20\123\151\x67\x6e\x69\156\x67\x20\x44\141\x74\141\x3a\40" . esc_attr(openssl_error_string()) . "\40\x2d\x20" . esc_attr($w7));
        Jg:
        return $lo;
    }
    private function verifyOpenSSL($jr, $lo)
    {
        $w7 = OPENSSL_ALGO_SHA1;
        if (empty($this->cryptParams["\144\x69\x67\145\x73\164"])) {
            goto Nj;
        }
        $w7 = $this->cryptParams["\x64\x69\147\x65\x73\164"];
        Nj:
        return openssl_verify($jr, $lo, $this->key, $w7);
    }
    public function encryptData($jr)
    {
        if (!($this->cryptParams["\154\x69\x62\162\x61\162\x79"] === "\x6f\x70\145\156\x73\163\x6c")) {
            goto s8;
        }
        switch ($this->cryptParams["\x74\171\x70\145"]) {
            case "\x73\x79\x6d\155\x65\x74\x72\x69\x63":
                return $this->encryptSymmetric($jr);
            case "\160\x75\x62\x6c\x69\x63":
                return $this->encryptPublic($jr);
            case "\x70\x72\x69\x76\x61\164\x65":
                return $this->encryptPrivate($jr);
        }
        o5:
        Ht:
        s8:
    }
    public function decryptData($jr)
    {
        if (!($this->cryptParams["\154\151\142\162\141\162\171"] === "\x6f\x70\145\x6e\163\163\x6c")) {
            goto vQ;
        }
        switch ($this->cryptParams["\x74\171\160\x65"]) {
            case "\x73\171\155\155\x65\x74\162\151\143":
                return $this->decryptSymmetric($jr);
            case "\160\x75\x62\154\x69\143":
                return $this->decryptPublic($jr);
            case "\160\x72\151\x76\141\x74\145":
                return $this->decryptPrivate($jr);
        }
        Ky:
        Fl:
        vQ:
    }
    public function signData($jr)
    {
        switch ($this->cryptParams["\154\151\142\162\x61\x72\171"]) {
            case "\x6f\160\x65\x6e\163\x73\154":
                return $this->signOpenSSL($jr);
            case self::HMAC_SHA1:
                return hash_hmac("\x73\150\141\x31", $jr, $this->key, true);
        }
        KK:
        NB:
    }
    public function verifySignature($jr, $lo)
    {
        switch ($this->cryptParams["\154\x69\142\162\x61\162\171"]) {
            case "\157\x70\x65\156\163\163\x6c":
                return $this->verifyOpenSSL($jr, $lo);
            case self::HMAC_SHA1:
                $j9 = hash_hmac("\x73\x68\x61\61", $jr, $this->key, true);
                return strcmp($lo, $j9) == 0;
        }
        sY:
        FZ:
    }
    public function getAlgorith()
    {
        return $this->getAlgorithm();
    }
    public function getAlgorithm()
    {
        return $this->cryptParams["\x6d\x65\164\150\x6f\144"];
    }
    public static function makeAsnSegment($Gf, $BR)
    {
        switch ($Gf) {
            case 0x2:
                if (!(ord($BR) > 0x7f)) {
                    goto zr;
                }
                $BR = chr(0) . $BR;
                zr:
                goto ms;
            case 0x3:
                $BR = chr(0) . $BR;
                goto ms;
        }
        Cu:
        ms:
        $uX = strlen($BR);
        if ($uX < 128) {
            goto ZH;
        }
        if ($uX < 0x100) {
            goto vA;
        }
        if ($uX < 0x10000) {
            goto BT;
        }
        $Cr = null;
        goto yA;
        ZH:
        $Cr = sprintf("\45\x63\x25\143\45\x73", $Gf, $uX, $BR);
        goto yA;
        vA:
        $Cr = sprintf("\45\143\x25\x63\45\143\x25\163", $Gf, 0x81, $uX, $BR);
        goto yA;
        BT:
        $Cr = sprintf("\45\x63\45\143\x25\x63\45\x63\45\163", $Gf, 0x82, $uX / 0x100, $uX % 0x100, $BR);
        yA:
        return $Cr;
    }
    public static function convertRSA($v7, $Um)
    {
        $lW = self::makeAsnSegment(0x2, $Um);
        $C_ = self::makeAsnSegment(0x2, $v7);
        $Bj = self::makeAsnSegment(0x30, $C_ . $lW);
        $Dw = self::makeAsnSegment(0x3, $Bj);
        $Zt = pack("\x48\52", "\x33\x30\60\104\60\66\60\71\x32\101\70\66\64\70\x38\x36\x46\67\60\x44\x30\61\x30\x31\60\x31\x30\x35\60\60");
        $ve = self::makeAsnSegment(0x30, $Zt . $Dw);
        $lC = base64_encode($ve);
        $H3 = "\x2d\x2d\55\55\55\102\105\x47\111\116\40\x50\125\x42\x4c\111\x43\40\113\105\131\55\55\x2d\x2d\55\12";
        $Uu = 0;
        M8:
        if (!($Qg = substr($lC, $Uu, 64))) {
            goto TW;
        }
        $H3 = $H3 . $Qg . "\xa";
        $Uu += 64;
        goto M8;
        TW:
        return $H3 . "\x2d\55\55\55\55\105\x4e\104\40\120\x55\x42\x4c\x49\x43\40\x4b\x45\131\x2d\x2d\55\x2d\x2d\12";
    }
    public function serializeKey($ri)
    {
    }
    public function getX509Certificate()
    {
        return $this->x509Certificate;
    }
    public function getX509Thumbprint()
    {
        return $this->X509Thumbprint;
    }
    public static function fromEncryptedKeyElement(DOMElement $ss)
    {
        $bg = new XMLSecEnc();
        $bg->setNode($ss);
        if ($Nq = $bg->locateKey()) {
            goto u_;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\125\x6e\141\142\154\145\40\164\x6f\x20\x6c\157\x63\141\164\145\x20\141\x6c\147\157\162\151\x74\150\x6d\40\146\x6f\x72\40\x74\150\x69\x73\x20\x45\x6e\x63\162\171\160\164\x65\x64\40\x4b\x65\x79");
        u_:
        $Nq->isEncrypted = true;
        $Nq->encryptedCtx = $bg;
        XMLSecEnc::staticLocateKeyInfo($Nq, $ss);
        return $Nq;
    }
}
