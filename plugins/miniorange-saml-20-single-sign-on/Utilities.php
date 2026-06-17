<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



require_once MO_SAML_PLUGIN_DIR . MO_SAML_OPTIONS_ENUM;
require_once Mo_Saml_Plugin_Files::MO_SAML_XML_SEC_LIBS;
require_once Mo_Saml_Plugin_Files::MO_SAML_INVALID_XML_EXCEPTION;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecEnc;
use MOSAML\LicenseLibrary\Mo_License_Service;
class SAMLSPUtilities
{
    public static $pricing_faqs = "\x68\x74\x74\160\x73\x3a\x2f\57\160\x6c\165\x67\x69\156\x73\x2e\155\x69\x6e\x69\157\x72\x61\156\x67\x65\56\x63\157\x6d\x2f\x77\157\x72\144\160\162\x65\x73\163\x2d\163\x69\x6e\x67\154\145\x2d\x73\x69\147\x6e\x2d\157\x6e\55\x73\163\x6f\x23\160\162\151\143\x69\x6e\147";
    public static function generateID()
    {
        return "\x5f" . self::stringToHex(self::generateRandomBytes(21));
    }
    public static function stringToHex($qD)
    {
        $TB = '';
        $Ev = 0;
        DrU:
        if (!($Ev < strlen($qD))) {
            goto qp3;
        }
        $TB .= sprintf("\x25\x30\62\170", ord($qD[$Ev]));
        EMC:
        $Ev++;
        goto DrU;
        qp3:
        return $TB;
    }
    public static function generateRandomBytes($uX, $LQ = true)
    {
        return openssl_random_pseudo_bytes($uX);
    }
    public static function createAuthnRequest($h8, $g5, $xF, $fg, $Ri = "\x66\141\x6c\163\145", $AE = "\110\164\164\x70\x52\x65\144\x69\x72\x65\x63\x74", $nq = '')
    {
        $BB = $fg["\x69\144\160\137\156\141\155\145"];
        $nq = "\x75\x72\156\72\x6f\x61\163\151\x73\x3a\156\x61\155\x65\x73\72\x74\x63\72\x53\x41\115\114\72" . $nq;
        $jd = "\74\77\170\x6d\154\40\x76\x65\x72\x73\151\x6f\x6e\x3d\42\x31\x2e\60\x22\40\x65\x6e\143\x6f\144\151\x6e\147\75\42\x55\x54\106\x2d\x38\42\x3f\76" . "\74\x73\x61\155\x6c\x70\72\x41\x75\164\x68\x6e\122\x65\x71\165\x65\x73\164\x20\170\155\x6c\156\x73\72\x73\x61\x6d\x6c\x70\x3d\42\x75\x72\156\72\x6f\141\x73\151\163\72\156\141\155\145\x73\72\x74\x63\x3a\123\101\115\114\x3a\x32\x2e\x30\72\x70\x72\x6f\x74\157\x63\157\x6c\42\40\170\155\x6c\x6e\163\x3d\x22\165\x72\156\x3a\157\x61\x73\x69\163\72\x6e\141\x6d\145\163\x3a\x74\x63\x3a\123\x41\115\114\72\x32\56\x30\x3a\141\x73\x73\x65\x72\164\151\157\156\42\x20\111\104\75\x22" . self::generateID() . "\x22\40\126\145\x72\x73\151\x6f\x6e\75\42\62\x2e\60\42\40\111\163\163\165\145\111\x6e\x73\164\141\x6e\x74\x3d\x22" . self::generateTimestamp() . "\42";
        if (!($Ri == "\164\162\x75\145")) {
            goto uOA;
        }
        $jd .= "\40\106\x6f\x72\143\x65\x41\x75\164\150\x6e\75\x22\164\x72\x75\145\x22";
        uOA:
        $jd .= "\40\x50\x72\157\x74\x6f\143\x6f\x6c\x42\x69\156\144\x69\156\x67\x3d\x22\165\x72\156\x3a\157\141\163\151\163\72\x6e\x61\x6d\x65\x73\72\164\x63\72\x53\101\x4d\114\72\62\x2e\60\72\x62\x69\x6e\x64\151\x6e\x67\x73\x3a\110\x54\x54\120\x2d\120\117\x53\124\42\x20\101\163\x73\145\162\x74\151\x6f\x6e\103\157\x6e\x73\x75\x6d\145\162\x53\145\x72\166\151\143\x65\x55\122\x4c\75\42" . $h8 . "\42\x20\x44\145\x73\x74\151\x6e\141\164\x69\157\156\75\42" . $xF . "\x22\x3e\74\163\x61\x6d\154\72\111\163\x73\x75\x65\162\40\170\155\154\156\x73\72\x73\141\155\154\x3d\42\x75\162\156\x3a\157\x61\163\151\x73\72\x6e\x61\x6d\145\x73\x3a\x74\x63\x3a\x53\x41\115\x4c\72\x32\56\x30\72\141\x73\163\x65\162\x74\151\x6f\x6e\42\x3e" . $g5 . "\x3c\x2f\x73\x61\x6d\154\72\x49\x73\163\165\145\x72\x3e\x3c\x73\141\155\x6c\x70\72\116\141\x6d\145\111\x44\120\157\154\x69\x63\x79\x20\x41\154\154\x6f\x77\x43\162\145\x61\164\145\75\x22\164\x72\x75\145\x22\x20\106\157\162\155\x61\x74\x3d\x22" . $nq . "\x22\15\12\x20\x20\40\40\x20\x20\x20\40\40\40\40\40\x20\40\40\40\x20\40\x20\40\40\40\x20\x20\x2f\x3e\74\57\163\x61\x6d\154\160\x3a\101\x75\x74\x68\x6e\122\145\161\165\145\x73\164\76";
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\163\x61\155\154\x5f\x69\144\x65\156\x74\x69\x74\171\137\x70\162\x6f\166\151\144\145\x72\x73", true, $CP);
        $s6 = new EnvironmentDao($CP);
        if (empty($AE) || $AE == "\110\164\164\x70\x52\x65\144\151\x72\145\x63\164") {
            goto I1s;
        }
        $Qy = gzdeflate($jd);
        $tU = base64_encode($Qy);
        $fS = array("\163\141\155\154\x5f\162\145\x71\x75\145\x73\164" => $tU);
        $rK[$BB] = self::mo_saml_array_merge($rK[$BB], $fS);
        $rK = array_filter($rK, "\x66\151\154\164\145\x72\137\x65\x6d\x70\164\x79\137\166\141\154\x75\145\x73");
        $s6->mo_save_environment_settings("\x73\x61\155\x6c\137\x69\144\x65\x6e\164\151\x74\x79\137\160\x72\157\x76\151\x64\x65\162\163", $rK, false);
        goto teh;
        I1s:
        $Qy = gzdeflate($jd);
        $tU = base64_encode($Qy);
        $fS = array("\163\x61\155\154\x5f\162\145\x71\165\145\x73\x74" => $tU);
        $rK[$BB] = self::mo_saml_array_merge($rK[$BB], $fS);
        $rK = array_filter($rK, "\146\x69\154\164\x65\x72\x5f\x65\155\x70\x74\171\x5f\x76\x61\x6c\x75\145\163");
        $s6->mo_save_environment_settings("\x73\x61\155\154\x5f\x69\144\145\x6e\x74\151\x74\x79\x5f\x70\162\157\x76\151\144\145\162\x73", $rK, false);
        $kF = urlencode($tU);
        $jd = $kF;
        teh:
        return $jd;
    }
    public static function createLogoutRequest($T9, $g5, $xF, $Un = "\x48\x74\x74\x70\122\145\144\151\162\x65\143\x74", $X9 = "\x75\156\163\160\145\x63\151\146\151\145\x64", $nq = '')
    {
        $nq = "\165\162\x6e\x3a\x6f\x61\163\151\x73\x3a\156\141\x6d\145\x73\72\164\143\x3a\x53\101\115\114\x3a" . $nq;
        $jd = "\74\x3f\170\x6d\154\40\x76\145\162\163\151\x6f\156\75\x22\61\x2e\x30\42\x20\145\x6e\143\157\x64\x69\x6e\147\x3d\42\125\124\x46\55\x38\x22\77\x3e" . "\x3c\163\141\x6d\154\160\x3a\114\x6f\x67\157\x75\x74\122\x65\x71\165\x65\x73\164\40\170\155\154\x6e\x73\72\163\141\155\154\160\x3d\x22\165\162\156\x3a\x6f\141\x73\151\x73\x3a\156\141\x6d\145\x73\72\x74\x63\x3a\123\101\115\x4c\x3a\62\56\60\x3a\160\162\157\x74\x6f\143\157\x6c\x22\40\x78\155\x6c\156\163\72\x73\141\x6d\x6c\x3d\x22\165\x72\156\x3a\x6f\141\163\151\x73\72\156\141\x6d\x65\163\72\x74\x63\x3a\123\101\x4d\x4c\72\x32\x2e\60\x3a\141\163\x73\x65\x72\x74\x69\x6f\156\x22\40\111\104\x3d\x22" . self::generateID() . "\42\x20\111\163\163\x75\145\111\156\x73\164\x61\x6e\x74\x3d\x22" . self::generateTimestamp() . "\42\x20\x56\145\162\x73\151\x6f\156\75\x22\x32\56\60\42\40\104\x65\163\x74\151\156\141\x74\151\157\x6e\75\x22" . $xF . "\42\x3e\xd\xa\11\x9\x9\x9\x9\x9\x3c\x73\x61\155\154\x3a\x49\163\163\165\145\162\40\170\155\154\x6e\x73\72\x73\141\x6d\154\75\x22\165\162\x6e\72\157\141\163\151\x73\x3a\156\x61\155\145\163\x3a\x74\143\x3a\x53\x41\x4d\114\72\62\56\60\72\141\x73\163\x65\x72\164\151\x6f\156\42\76" . $g5 . "\x3c\x2f\x73\x61\155\x6c\72\x49\x73\163\165\x65\162\x3e\15\12\x9\11\x9\x9\11\x9\x3c\163\141\x6d\154\x3a\116\x61\x6d\x65\x49\104\40\170\155\154\156\163\x3a\163\141\x6d\x6c\x3d\42\x75\162\156\72\157\x61\163\x69\x73\72\x6e\x61\155\145\x73\x3a\164\143\x3a\123\101\x4d\x4c\x3a\x32\56\x30\x3a\x61\163\163\145\x72\x74\151\x6f\156\x22\x20\x46\157\162\x6d\x61\x74\75\x22" . $nq . "\42\x3e" . $T9[0] . "\x3c\x2f\163\x61\x6d\154\72\x4e\141\x6d\x65\x49\104\x3e";
        if (empty($X9)) {
            goto FJb;
        }
        $jd .= "\x3c\163\141\155\x6c\160\x3a\x53\145\x73\163\x69\x6f\x6e\x49\x6e\x64\x65\x78\x3e" . $X9[0] . "\74\x2f\x73\x61\x6d\154\160\x3a\x53\145\163\163\151\157\x6e\111\156\144\x65\170\x3e";
        FJb:
        $jd .= "\x3c\x2f\163\x61\x6d\154\160\72\114\157\x67\157\x75\164\x52\x65\161\x75\145\x73\x74\76";
        if (!(empty($Un) || $Un == "\110\164\164\160\x52\145\x64\x69\162\145\x63\164")) {
            goto aee;
        }
        $Qy = gzdeflate($jd);
        $tU = base64_encode($Qy);
        $kF = urlencode($tU);
        $jd = $kF;
        aee:
        return $jd;
    }
    public static function createLogoutResponse($JN, $g5, $xF, $Un = "\x48\x74\164\x70\x52\145\x64\151\162\x65\143\164")
    {
        $jd = "\x3c\77\170\155\x6c\40\166\x65\162\x73\151\x6f\x6e\75\x22\61\x2e\x30\42\x20\x65\156\143\157\144\151\x6e\147\75\x22\125\124\106\55\70\42\77\76" . "\x3c\163\141\155\154\160\72\114\x6f\147\157\x75\164\122\x65\x73\x70\x6f\156\x73\145\x20\170\155\154\x6e\163\72\163\141\155\154\x70\75\x22\x75\x72\x6e\x3a\x6f\141\x73\151\163\x3a\x6e\141\x6d\x65\163\72\x74\x63\72\x53\101\x4d\x4c\x3a\x32\x2e\x30\x3a\160\162\157\x74\157\x63\x6f\x6c\42\40\170\155\154\156\163\72\x73\x61\x6d\x6c\x3d\42\x75\x72\156\x3a\157\x61\163\x69\163\x3a\x6e\x61\155\145\163\x3a\164\143\x3a\123\x41\x4d\114\72\62\x2e\x30\72\141\163\163\145\162\164\x69\x6f\x6e\x22\40" . "\111\104\75\x22" . self::generateID() . "\x22\40" . "\126\145\162\x73\151\x6f\156\75\x22\62\x2e\60\x22\x20\x49\x73\x73\165\x65\x49\x6e\163\x74\x61\x6e\x74\x3d\42" . self::generateTimestamp() . "\x22\40" . "\x44\145\163\164\151\x6e\141\164\151\157\156\75\42" . $xF . "\42\40" . "\111\x6e\x52\x65\x73\160\157\156\x73\145\124\x6f\x3d\42" . $JN . "\x22\x3e" . "\x3c\163\141\155\154\72\111\x73\x73\x75\x65\x72\x20\170\155\x6c\156\163\x3a\x73\141\155\x6c\75\x22\165\x72\x6e\72\157\141\163\x69\x73\x3a\x6e\141\x6d\145\163\x3a\x74\x63\72\123\101\115\x4c\72\x32\56\60\x3a\x61\163\163\x65\162\x74\151\x6f\156\x22\76" . $g5 . "\74\57\x73\141\155\154\72\x49\163\163\x75\145\162\x3e" . "\74\x73\141\x6d\154\160\x3a\x53\x74\x61\x74\x75\163\x3e\74\x73\x61\x6d\x6c\160\x3a\x53\x74\141\164\x75\163\x43\157\x64\x65\x20\x56\141\154\165\145\x3d\x22\x75\162\x6e\72\157\x61\x73\151\x73\72\x6e\141\x6d\x65\163\72\x74\x63\72\123\x41\115\x4c\72\62\56\60\72\x73\x74\x61\164\165\x73\x3a\x53\165\x63\x63\x65\163\x73\42\57\x3e\74\57\163\x61\155\154\x70\x3a\x53\x74\141\x74\x75\x73\x3e\74\x2f\163\141\155\x6c\x70\x3a\x4c\x6f\x67\x6f\165\x74\x52\145\163\x70\x6f\156\x73\145\x3e";
        if (!(empty($Un) || $Un == "\110\164\164\160\x52\145\144\151\x72\x65\143\x74")) {
            goto eEZ;
        }
        $Qy = gzdeflate($jd);
        $tU = base64_encode($Qy);
        $kF = urlencode($tU);
        $jd = $kF;
        eEZ:
        return $jd;
    }
    public static function generateTimestamp($wa = null)
    {
        if (!($wa === null)) {
            goto I7l;
        }
        $wa = time();
        I7l:
        return gmdate("\x59\55\155\x2d\x64\x5c\x54\110\x3a\151\72\163\134\132", $wa);
    }
    public static function xpQuery(DOMNode $Fa, $yZ)
    {
        static $f0 = null;
        if ($Fa instanceof DOMDocument) {
            goto qWD;
        }
        $hT = $Fa->ownerDocument;
        goto sC0;
        qWD:
        $hT = $Fa;
        sC0:
        if (!($f0 === null || !$f0->document->isSameNode($hT))) {
            goto Gj6;
        }
        $f0 = new DOMXPath($hT);
        $f0->registerNamespace("\x73\157\x61\x70\x2d\x65\x6e\166", "\150\164\164\160\x3a\57\x2f\x73\x63\x68\145\155\x61\x73\56\x78\x6d\x6c\163\x6f\x61\160\x2e\x6f\x72\x67\x2f\163\x6f\141\160\x2f\x65\156\166\145\154\157\160\145\x2f");
        $f0->registerNamespace("\163\141\155\x6c\137\160\x72\x6f\164\157\143\157\x6c", "\x75\162\156\72\157\x61\163\x69\x73\72\x6e\141\155\x65\x73\x3a\x74\x63\72\123\x41\x4d\114\x3a\x32\56\60\72\160\x72\x6f\164\x6f\143\x6f\x6c");
        $f0->registerNamespace("\163\141\155\x6c\137\141\x73\x73\145\x72\164\x69\x6f\156", "\165\x72\x6e\x3a\157\141\163\x69\x73\72\156\141\x6d\x65\163\72\x74\x63\x3a\123\101\115\114\x3a\62\x2e\60\x3a\x61\x73\x73\145\162\x74\151\x6f\x6e");
        $f0->registerNamespace("\x73\x61\155\154\x5f\x6d\145\x74\x61\x64\141\x74\141", "\165\162\156\72\x6f\141\x73\x69\163\x3a\x6e\141\x6d\145\163\72\164\x63\72\x53\x41\x4d\114\x3a\x32\56\x30\72\155\x65\x74\x61\144\141\x74\141");
        $f0->registerNamespace("\144\x73", "\x68\x74\164\160\72\x2f\57\167\x77\167\56\x77\63\56\157\x72\x67\57\62\60\60\x30\x2f\60\x39\x2f\170\155\x6c\x64\163\x69\x67\x23");
        $f0->registerNamespace("\170\145\156\143", "\x68\164\164\x70\72\57\x2f\167\167\167\56\x77\63\56\x6f\x72\x67\57\x32\60\x30\61\x2f\x30\64\57\170\x6d\x6c\x65\x6e\x63\43");
        Gj6:
        $mw = $f0->query($yZ, $Fa);
        $TB = array();
        $Ev = 0;
        wib:
        if (!($Ev < $mw->length)) {
            goto Jki;
        }
        $TB[$Ev] = $mw->item($Ev);
        U_P:
        $Ev++;
        goto wib;
        Jki:
        return $TB;
    }
    public static function parseNameId(DOMElement $KD)
    {
        $TB = array("\126\x61\154\x75\x65" => trim($KD->textContent));
        foreach (array("\x4e\x61\155\x65\121\165\x61\x6c\x69\x66\x69\145\x72", "\x53\x50\x4e\141\155\x65\121\165\141\154\151\146\x69\145\x72", "\x46\x6f\162\155\141\x74") as $Zc) {
            if (!$KD->hasAttribute($Zc)) {
                goto E8j;
            }
            $TB[$Zc] = $KD->getAttribute($Zc);
            E8j:
            KhX:
        }
        ONX:
        return $TB;
    }
    public static function xsDateTimeToTimestamp($K7)
    {
        $Ky = array();
        $nM = "\57\x5e\x28\x5c\144\x5c\x64\x5c\144\134\x64\x29\x2d\50\x5c\144\134\x64\x29\x2d\50\134\x64\134\x64\51\x54\x28\134\x64\134\x64\x29\x3a\50\134\144\x5c\144\x29\x3a\x28\x5c\144\134\x64\x29\x28\77\72\134\x2e\x5c\x64\x2b\x29\77\132\44\x2f\x44";
        if (!(preg_match($nM, $K7, $Ky) == 0)) {
            goto Zrc;
        }
        echo esc_html(sprintf("\x49\x6e\x76\141\154\x69\x64\x20\x53\x41\x4d\x4c\62\40\164\151\155\x65\163\164\x61\x6d\160\x20\x70\141\x73\x73\145\144\x20\164\x6f\40\170\163\104\x61\164\145\124\x69\x6d\145\x54\x6f\x54\151\155\x65\163\x74\x61\155\160\72\x20" . $K7));
        exit;
        Zrc:
        $VF = intval($Ky[1]);
        $w4 = intval($Ky[2]);
        $B_ = intval($Ky[3]);
        $je = intval($Ky[4]);
        $S6 = intval($Ky[5]);
        $ja = intval($Ky[6]);
        $Ok = gmmktime($je, $S6, $ja, $w4, $B_, $VF);
        return $Ok;
    }
    public static function extractStrings(DOMElement $ri, $cd, $e4)
    {
        $TB = array();
        $Fa = $ri->firstChild;
        L77:
        if (!($Fa !== null)) {
            goto UDI;
        }
        if (!($Fa->namespaceURI !== $cd || $Fa->localName !== $e4)) {
            goto XYx;
        }
        goto lt6;
        XYx:
        $TB[] = trim($Fa->textContent);
        lt6:
        $Fa = $Fa->nextSibling;
        goto L77;
        UDI:
        return $TB;
    }
    public static function validateElement(DOMElement $le)
    {
        $iB = new XMLSecurityDSig();
        $iB->idKeys[] = "\111\104";
        $Yt = self::xpQuery($le, "\x2e\x2f\x64\x73\72\123\x69\x67\x6e\141\164\x75\162\145");
        if (count($Yt) === 0) {
            goto n5u;
        }
        if (count($Yt) > 1) {
            goto r2l;
        }
        goto Aq5;
        n5u:
        return false;
        goto Aq5;
        r2l:
        printf("\x58\115\x4c\x53\x65\143\72\x20\x6d\157\x72\x65\x20\164\150\x61\156\40\x6f\156\x65\40\163\x69\x67\156\141\164\x75\162\145\40\x65\x6c\145\x6d\x65\156\x74\40\x69\156\40\x72\x6f\157\164\56");
        exit;
        Aq5:
        $Yt = $Yt[0];
        $iB->sigNode = $Yt;
        $iB->canonicalizeSignedInfo();
        if ($iB->validateReference()) {
            goto tmX;
        }
        printf("\130\115\114\x73\145\x63\x3a\40\x64\x69\147\145\163\164\x20\166\x61\154\151\x64\x61\x74\151\157\x6e\40\x66\x61\151\x6c\x65\144");
        exit;
        tmX:
        $En = false;
        foreach ($iB->getValidatedNodes() as $mB) {
            if ($mB->isSameNode($le)) {
                goto m_M;
            }
            if ($le->parentNode instanceof DOMDocument && $mB->isSameNode($le->ownerDocument)) {
                goto d43;
            }
            goto mN7;
            m_M:
            $En = true;
            goto T0R;
            goto mN7;
            d43:
            $En = true;
            goto T0R;
            mN7:
            Som:
        }
        T0R:
        if ($En) {
            goto aqL;
        }
        printf("\x58\x4d\x4c\x53\145\x63\x3a\40\124\x68\x65\40\162\157\x6f\x74\40\145\x6c\x65\155\145\156\164\x20\x69\163\x20\x6e\x6f\164\x20\163\x69\147\x6e\x65\144\56");
        exit;
        aqL:
        $Xi = array();
        foreach (self::xpQuery($Yt, "\56\x2f\x64\x73\x3a\x4b\145\x79\111\x6e\x66\157\x2f\x64\163\x3a\130\x35\x30\x39\104\141\164\x61\57\144\163\x3a\x58\x35\x30\71\103\145\x72\164\151\146\x69\x63\x61\164\x65") as $Ek) {
            $q0 = trim($Ek->textContent);
            $q0 = str_replace(array("\15", "\xa", "\x9", "\40"), '', $q0);
            $Xi[] = $q0;
            yWT:
        }
        kao:
        $TB = array("\x53\151\x67\x6e\141\x74\165\x72\x65" => $iB, "\x43\145\x72\x74\x69\146\x69\143\141\x74\x65\163" => $Xi);
        return $TB;
    }
    public static function validateSignature(array $yS, XMLSecurityKey $R2)
    {
        $iB = $yS["\123\x69\x67\156\141\164\165\x72\145"];
        $fM = self::xpQuery($iB->sigNode, "\56\x2f\144\163\x3a\123\x69\147\x6e\145\144\x49\x6e\146\x6f\x2f\144\x73\x3a\x53\x69\x67\156\141\164\x75\162\x65\115\145\x74\x68\x6f\x64");
        if (!empty($fM)) {
            goto eh1;
        }
        printf("\x4d\x69\163\163\x69\x6e\147\x20\x53\151\x67\x6e\x61\164\165\x72\x65\115\145\164\x68\x6f\x64\40\x65\154\145\x6d\x65\x6e\x74");
        exit;
        eh1:
        $fM = $fM[0];
        if ($fM->hasAttribute("\x41\x6c\x67\157\162\151\x74\150\x6d")) {
            goto MoQ;
        }
        printf("\x4d\x69\163\163\151\156\x67\40\x41\x6c\x67\x6f\162\x69\x74\150\x6d\55\141\x74\164\162\x69\142\165\x74\x65\x20\x6f\156\x20\x53\151\147\156\141\x74\165\x72\x65\x4d\x65\164\150\157\144\x20\145\154\145\x6d\x65\156\164\x2e");
        exit;
        MoQ:
        $w7 = $fM->getAttribute("\101\x6c\147\x6f\x72\x69\164\150\155");
        if (!($R2->type === XMLSecurityKey::RSA_SHA1 && $w7 !== $R2->type)) {
            goto hHg;
        }
        $R2 = self::castKey($R2, $w7);
        hHg:
        if ($iB->verify($R2)) {
            goto wqk;
        }
        printf("\x55\156\x61\142\154\x65\x20\164\157\x20\x76\x61\x6c\x69\144\141\x74\145\x20\123\x69\147\156\x61\164\165\162\x65");
        exit;
        wqk:
    }
    public static function castKey(XMLSecurityKey $R2, $PS, $Gf = "\x70\x75\x62\x6c\151\143")
    {
        if (!($R2->type === $PS)) {
            goto Afl;
        }
        return $R2;
        Afl:
        $TE = openssl_pkey_get_details($R2->key);
        if (!($TE === false)) {
            goto qrR;
        }
        printf("\x55\156\x61\142\x6c\145\x20\164\x6f\x20\x67\x65\x74\x20\x6b\145\x79\40\144\x65\x74\x61\151\154\163\x20\146\162\157\155\40\x58\115\114\123\x65\143\165\162\x69\164\x79\x4b\145\x79\56");
        exit;
        qrR:
        if (!empty($TE["\153\x65\x79"])) {
            goto pk2;
        }
        printf("\x4d\151\163\x73\x69\x6e\147\x20\x6b\x65\x79\x20\151\156\40\x70\165\142\154\151\x63\40\x6b\145\x79\40\x64\x65\164\141\151\154\x73\56");
        exit;
        pk2:
        $TF = new XMLSecurityKey($PS, array("\x74\171\160\x65" => $Gf));
        $TF->loadKey($TE["\153\x65\171"]);
        return $TF;
    }
    public static function mo_saml_is_user_logged_in()
    {
        if (!is_user_logged_in()) {
            goto nzV;
        }
        return true;
        nzV:
        if (!(!empty(get_option("\x6d\x6f\137\145\156\141\x62\154\x65\137\147\x75\x65\163\164\x5f\154\x6f\147\151\156")) && get_option("\155\157\137\x65\x6e\x61\x62\x6c\145\137\x67\x75\x65\163\164\x5f\x6c\x6f\147\x69\x6e"))) {
            goto cAK;
        }
        if (!(!empty($_SESSION["\x6d\x6f\x5f\147\165\x65\163\164\137\x6c\x6f\147\151\x6e"]["\163\145\x73\x73\x69\x6f\x6e\111\156\144\x65\170"]) || !empty($_COOKIE["\x73\145\x73\163\151\x6f\156\x49\x6e\x64\x65\x78"]))) {
            goto fv1;
        }
        return true;
        fv1:
        cAK:
        return false;
    }
    public static function validate_compressed_xml($KD, $mA)
    {
        $Yx = base64_decode($KD);
        if (!(false === $Yx)) {
            goto RdV;
        }
        throw new Mo_SAML_Invalid_XML_Exception("\x49\156\x76\141\x6c\x69\144\40\130\x4d\114\x20\105\156\143\157\144\151\x6e\x67\56");
        RdV:
        if (!empty($_GET[$mA])) {
            goto hqg;
        }
        return $Yx;
        hqg:
        $KM = @gzinflate($Yx);
        if (!(false === $KM)) {
            goto fOC;
        }
        throw new Mo_SAML_Invalid_XML_Exception("\111\156\166\141\x6c\151\x64\x20\x58\115\x4c\x20\x43\x6f\x6d\x70\162\145\163\163\151\157\x6e\x2e");
        fOC:
        return $KM;
    }
    public static function mo_saml_delete_plugin_cookies()
    {
        $ug = self::mo_saml_get_secure_cookie_attribute();
        if (isset($_SESSION["\155\x6f\x5f\147\x75\x65\163\x74\137\154\157\147\x69\x6e"]["\x6e\x61\155\x65\x49\104"])) {
            goto dH1;
        }
        unset($_SESSION["\155\157\x5f\x73\141\x6d\x6c"]);
        unset($_COOKIE["\x6c\157\147\x67\x65\x64\x5f\151\156\137\x77\x69\x74\150\x5f\x69\x64\x70"]);
        unset($_COOKIE["\156\x61\x6d\x65\x49\104"]);
        unset($_COOKIE["\163\145\163\x73\x69\x6f\x6e\x49\x6e\144\x65\x78"]);
        goto S53;
        dH1:
        unset($_SESSION["\155\x6f\137\x67\165\145\x73\164\x5f\154\x6f\x67\x69\x6e"]);
        S53:
        setcookie("\x6e\141\155\145\x49\104", '', time() - 3600, "\x2f", '', $ug, true);
        setcookie("\163\x65\x73\163\151\157\156\111\x6e\x64\145\x78", '', time() - 3600, "\57", '', $ug, true);
        setcookie("\x6c\x6f\147\147\x65\x64\137\x69\156\x5f\x77\x69\x74\150\x5f\151\x64\160", '', time() - 3600, "\x2f", '', $ug, true);
    }
    public static function mo_saml_get_secure_cookie_attribute()
    {
        $ug = is_ssl() && "\x68\x74\x74\160\x73" === parse_url(get_option(Mo_Saml_Options::HOME), PHP_URL_SCHEME);
        return apply_filters("\x6d\157\137\163\141\x6d\154\x5f\x73\145\x74\x5f\x73\x65\143\165\x72\145\137\143\x6f\157\x6b\x69\x65\137\141\164\x74\x72\151\x62\x75\164\x65", $ug);
    }
    public static function processResponse($Vs, $uk, $xe, SAML2_Response $d4, $ZT, $Yj)
    {
        $MR = current($d4->getAssertions());
        $CP = EnvironmentHelper::getCurrentEnvironment();
        $rK = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\155\154\x5f\151\144\145\x6e\x74\151\x74\171\137\x70\x72\x6f\166\151\x64\x65\162\x73", true, $CP);
        $dF = $d4->getIssuer();
        $XE = null;
        foreach ($rK as $R2 => $EB) {
            if (!($EB["\151\x64\160\137\x65\x6e\x74\x69\164\171\x5f\x69\x64"] == $dF)) {
                goto dzM;
            }
            $XE = $rK[$R2];
            goto j8s;
            dzM:
            xem:
        }
        j8s:
        $Pn = !empty($XE["\155\157\137\163\x61\155\x6c\137\141\x73\x73\x65\162\164\x69\157\x6e\x5f\164\x69\x6d\145\x5f\x76\141\154\151\x64\151\164\171"]) ? $XE["\x6d\x6f\137\x73\x61\155\154\x5f\141\x73\163\145\x72\164\x69\x6f\x6e\137\x74\151\155\145\137\x76\141\154\x69\x64\x69\x74\171"] : "\143\150\x65\143\153\x65\144";
        if (!($Pn == "\x63\x68\145\x63\153\145\144")) {
            goto mcm;
        }
        $Nd = $MR->getNotBefore();
        if (!($Nd !== null && $Nd > time() + 60)) {
            goto bKk;
        }
        Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\x57\120\x53\101\x4d\114\105\122\122\60\x30\x37"]);
        bKk:
        $gB = $MR->getNotOnOrAfter();
        if (!($gB !== null && $gB <= time() - 60)) {
            goto P66;
        }
        Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\x57\x50\123\101\115\x4c\x45\122\122\x30\x30\x38"]);
        P66:
        $J7 = $MR->getSessionNotOnOrAfter();
        if (!($J7 !== null && $J7 <= time() - 60)) {
            goto Mp9;
        }
        Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\127\x50\x53\101\x4d\x4c\105\122\122\60\60\x38"]);
        Mp9:
        mcm:
        $zs = $d4->getDestination();
        if (!(substr($zs, -1) == "\x2f")) {
            goto Eeq;
        }
        $zs = substr($zs, 0, -1);
        Eeq:
        if (!(substr($Vs, -1) == "\x2f")) {
            goto RZ5;
        }
        $Vs = substr($Vs, 0, -1);
        RZ5:
        if (!($zs !== null && $zs !== $Vs)) {
            goto c4H;
        }
        $FK = apply_filters("\x6d\x6f\137\x73\x61\155\x6c\137\141\x63\163\137\x75\x72\x6c", false, $zs);
        if ($FK) {
            goto cMo;
        }
        echo "\x44\x65\163\164\x69\156\x61\164\151\x6f\x6e\x20\151\156\x20\x72\x65\163\x70\157\156\163\x65\x20\144\157\x65\163\x6e\47\164\40\155\x61\164\143\x68\40\164\150\145\x20\x63\165\x72\x72\x65\156\164\40\x55\122\x4c\x2e\x20\x44\x65\x73\x74\151\x6e\x61\x74\x69\x6f\x6e\x20\151\x73\x20\x22" . esc_url($zs) . "\x22\54\40\143\165\x72\162\x65\156\x74\x20\125\122\x4c\40\151\x73\40\x22" . esc_url($Vs) . "\x22\x2e";
        exit;
        cMo:
        c4H:
        $yQ = self::checkSign($uk, $xe, $ZT, $Yj);
        return $yQ;
    }
    public static function checkSign($uk, $xe, $ZT, $Yj)
    {
        $Xi = $xe["\103\145\162\x74\x69\146\x69\143\141\x74\x65\163"];
        if (count($Xi) === 0) {
            goto u0V;
        }
        $hq = self::findCertificate($uk, $Xi, $Yj);
        if (!($hq == false)) {
            goto hEg;
        }
        return false;
        hEg:
        goto uma;
        u0V:
        $hq = self::sanitize_certificate($ZT);
        uma:
        $UW = null;
        $R2 = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array("\164\x79\x70\x65" => "\160\165\142\x6c\151\143"));
        $R2->loadKey($hq);
        try {
            self::validateSignature($xe, $R2);
            return true;
        } catch (Exception $G2) {
            $UW = $G2;
        }
        if ($UW !== null) {
            goto Qc_;
        }
        return false;
        goto wzS;
        Qc_:
        throw $UW;
        wzS:
    }
    public static function validateIssuerAndAudience($rg, $tK, $Yr, $Yj, $XE)
    {
        $g5 = current($rg->getAssertions())->getIssuer();
        $MR = current($rg->getAssertions());
        $PC = $MR->getValidAudiences();
        if (strcmp($Yr, $g5) === 0) {
            goto xxJ;
        }
        if ($Yj == "\x74\145\163\164\126\x61\154\x69\144\x61\x74\145") {
            goto C7X;
        }
        Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\127\x50\x53\x41\115\114\105\x52\122\60\x31\60"]);
        goto zM1;
        xxJ:
        if (empty($PC)) {
            goto Nb7;
        }
        if (self::mo_saml_in_array($tK, $PC, true)) {
            goto pOS;
        }
        if ($Yj == "\x74\x65\x73\164\126\141\154\151\144\141\x74\145") {
            goto ys5;
        }
        throw new Mo_SAML_Invalid_Audience_URI_Exception("\111\156\166\141\x6c\151\x64\x20\101\x75\x64\151\x65\x6e\x63\145\40\x55\x52\x49\56");
        goto I9h;
        pOS:
        return true;
        goto I9h;
        ys5:
        $bm = "\x3c\x70\76\x3c\163\x74\x72\x6f\156\x67\76\x41\x75\x64\x69\x65\156\x63\145\x20\125\x52\111\40\143\x6f\156\x66\151\x67\165\x72\145\x64\x20\151\x6e\x20\x49\x64\x65\156\x74\151\x74\x79\x20\120\x72\157\166\x69\144\x65\162\72\x20\74\x2f\x73\164\x72\157\156\x67\76" . $PC[0] . "\x3c\x70\76\15\xa\x9\11\x9\11\x9\40\40\x20\40\74\160\x3e\74\163\164\x72\x6f\156\147\76\x41\x75\144\151\145\156\x63\145\40\125\122\111\40\x63\157\x6e\146\x69\x67\x75\162\145\x64\x20\x69\x6e\x20\164\150\145\x20\x70\154\x75\x67\x69\x6e\72\x20\74\57\x73\164\x72\157\x6e\147\x3e" . esc_html($tK) . "\x3c\57\x70\x3e";
        $hU = mo_options_error_constants::Error_invalid_audience;
        $O4 = mo_options_error_constants::Cause_invalid_audience;
        ob_end_clean();
        Mo_Saml_Error_Message::mo_saml_display_test_config_error(Mo_Saml_Error_Codes::$error_codes["\x57\120\123\101\115\x4c\x45\122\x52\60\60\71"], $bm);
        mo_saml_download_logs($hU, $O4, $XE);
        I9h:
        Nb7:
        goto zM1;
        C7X:
        ob_end_clean();
        $bm = "\74\x70\x3e\74\163\164\x72\157\156\x67\76\105\156\164\x69\164\171\40\111\x44\x20\x69\x6e\x20\123\101\x4d\114\x20\122\145\x73\160\157\x6e\163\x65\x3a\x20\74\x2f\x73\x74\162\157\x6e\x67\x3e" . esc_html($g5) . "\74\160\x3e\15\xa\40\x20\40\40\40\40\40\40\x20\40\x20\x20\x20\40\40\40\74\x70\76\x3c\163\x74\162\x6f\x6e\x67\76\105\x6e\164\x69\x74\x79\40\x49\x44\40\143\157\156\146\151\147\x75\x72\x65\x64\x20\151\x6e\x20\164\150\x65\x20\160\x6c\x75\147\x69\x6e\72\40\x3c\x2f\x73\164\x72\157\x6e\x67\x3e" . esc_html($Yr) . "\74\x2f\x70\x3e";
        $hU = mo_options_error_constants::Error_issuer_not_verified;
        $O4 = mo_options_error_constants::Cause_issuer_not_verified;
        Mo_Saml_Error_Message::mo_saml_display_test_config_error(Mo_Saml_Error_Codes::$error_codes["\x57\120\x53\x41\x4d\114\x45\122\x52\60\x31\60"], $bm);
        mo_saml_download_logs($hU, $O4, $XE);
        zM1:
    }
    private static function findCertificate($uk, $Xi, $Yj)
    {
        $fQ = array();
        foreach ($Xi as $Fh) {
            $JT = strtolower(sha1(base64_decode($Fh)));
            if ($JT == $uk) {
                goto gEm;
            }
            $fQ[] = $JT;
            return false;
            goto QNP;
            gEm:
            $WX = "\x2d\x2d\55\x2d\55\102\x45\x47\111\x4e\40\x43\x45\122\x54\x49\106\111\x43\x41\x54\x45\55\55\x2d\55\x2d\12" . chunk_split($Fh, 64) . "\55\55\55\x2d\55\105\x4e\x44\x20\103\x45\122\124\111\x46\x49\103\x41\124\x45\x2d\x2d\x2d\x2d\x2d\12";
            return $WX;
            QNP:
            avq:
        }
        zzF:
        if ($Yj === "\164\x65\163\x74\126\x61\154\151\144\141\164\145" or $Yj === "\x74\x65\x73\x74\x4e\x65\x77\103\x65\162\x74\151\146\x69\x63\x61\x74\x65") {
            goto tS7;
        }
        Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\127\x50\123\x41\115\114\105\122\122\x30\x31\63"]);
        goto TYl;
        tS7:
        Mo_Saml_Error_Message::mo_saml_display_test_config_error(Mo_Saml_Error_Codes::$error_codes["\x57\x50\123\x41\115\x4c\105\x52\122\60\61\63"]);
        TYl:
    }
    private static function doDecryptElement(DOMElement $o7, XMLSecurityKey $Vb, array &$bn)
    {
        $QU = new XMLSecEnc();
        $QU->setNode($o7);
        $QU->type = $o7->getAttribute("\124\x79\160\x65");
        $IX = $QU->locateKey($o7);
        if ($IX) {
            goto yIw;
        }
        printf("\103\157\x75\154\x64\x20\x6e\157\164\40\154\157\x63\141\164\145\40\153\x65\x79\40\x61\154\x67\x6f\x72\151\x74\x68\155\x20\x69\x6e\40\x65\156\143\x72\x79\x70\x74\145\144\40\x64\x61\x74\141\x2e");
        exit;
        yIw:
        $a_ = $QU->locateKeyInfo($IX);
        if ($a_) {
            goto Ooa;
        }
        printf("\x43\x6f\165\154\x64\40\x6e\157\x74\40\154\x6f\x63\141\x74\145\40\74\144\x73\151\147\72\x4b\x65\171\111\x6e\146\157\76\x20\146\157\162\40\x74\150\x65\40\x65\x6e\143\162\x79\160\164\x65\144\40\x6b\x65\171\56");
        exit;
        Ooa:
        $Ma = $Vb->getAlgorith();
        if ($a_->isEncrypted) {
            goto oIu;
        }
        $xc = $IX->getAlgorith();
        if (!($Ma !== $xc)) {
            goto VBu;
        }
        echo esc_html(sprintf("\x41\x6c\147\157\x72\x69\x74\150\155\40\x6d\x69\163\x6d\141\x74\x63\x68\40\142\x65\x74\167\145\145\x6e\x20\x69\156\160\x75\x74\40\x6b\x65\x79\40\141\x6e\x64\40\x6b\145\171\40\151\156\40\155\145\163\x73\141\147\x65\x2e\x20" . "\113\x65\x79\40\167\141\163\72\x20" . var_export($Ma, true) . "\73\x20\x6d\x65\x73\163\141\147\x65\x20\x77\x61\163\x3a\40" . var_export($xc, true)));
        exit;
        VBu:
        $IX = $Vb;
        goto Qln;
        oIu:
        $o3 = $a_->getAlgorith();
        if (!self::mo_saml_in_array($o3, $bn, true)) {
            goto i63;
        }
        echo esc_html(sprintf("\x41\x6c\x67\157\x72\x69\x74\x68\155\40\144\151\163\141\142\x6c\x65\144\72\x20" . var_export($o3, true)));
        exit;
        i63:
        if (!($o3 === XMLSecurityKey::RSA_OAEP_MGF1P && $Ma === XMLSecurityKey::RSA_1_5)) {
            goto Y1K;
        }
        $Ma = XMLSecurityKey::RSA_OAEP_MGF1P;
        Y1K:
        if (!($Ma !== $o3)) {
            goto Ta3;
        }
        echo esc_html(sprintf("\x41\154\x67\x6f\x72\x69\x74\150\155\40\155\151\x73\155\141\x74\x63\x68\40\x62\145\x74\x77\x65\145\x6e\40\151\x6e\x70\x75\164\40\x6b\145\x79\x20\x61\156\x64\40\x6b\x65\x79\x20\165\163\x65\x64\40\164\x6f\40\145\156\x63\162\171\x70\x74\x20" . "\40\164\x68\x65\x20\163\x79\155\155\145\x74\x72\151\x63\40\153\145\x79\x20\x66\x6f\162\x20\x74\150\145\40\155\x65\x73\x73\x61\147\145\x2e\x20\113\x65\171\x20\x77\x61\163\72\40" . var_export($Ma, true) . "\x3b\40\155\145\x73\x73\141\x67\145\40\x77\141\x73\x3a\x20" . var_export($o3, true)));
        exit;
        Ta3:
        $pg = $a_->encryptedCtx;
        $a_->key = $Vb->key;
        $n9 = $IX->getSymmetricKeySize();
        if (!($n9 === null)) {
            goto PAR;
        }
        echo esc_html(sprintf("\x55\x6e\153\156\157\x77\156\40\x6b\x65\x79\40\x73\x69\x7a\145\x20\146\x6f\162\40\145\156\x63\x72\x79\160\164\151\x6f\156\40\141\154\x67\x6f\162\x69\164\150\155\x3a\40" . var_export($IX->type, true)));
        exit;
        PAR:
        try {
            $R2 = $pg->decryptKey($a_);
            if (!(strlen($R2) != $n9)) {
                goto lF5;
            }
            echo esc_html(sprintf("\125\156\145\170\160\145\x63\164\x65\144\40\153\x65\x79\40\163\151\172\x65\40\50" . strlen($R2) * 8 . "\x62\151\164\163\x29\40\x66\157\x72\x20\145\x6e\143\x72\x79\160\x74\x69\157\x6e\40\x61\x6c\x67\157\162\x69\x74\x68\x6d\72\x20" . var_export($IX->type, true)));
            exit;
            lF5:
        } catch (Exception $G2) {
            $M1 = $pg->getCipherValue();
            $Hg = openssl_pkey_get_details($a_->key);
            $Hg = sha1(serialize($Hg), true);
            $R2 = sha1($M1 . $Hg, true);
            if (strlen($R2) > $n9) {
                goto eXV;
            }
            if (strlen($R2) < $n9) {
                goto TxQ;
            }
            goto TmE;
            eXV:
            $R2 = substr($R2, 0, $n9);
            goto TmE;
            TxQ:
            $R2 = str_pad($R2, $n9);
            TmE:
        }
        $IX->loadkey($R2);
        Qln:
        $PS = $IX->getAlgorith();
        if (!self::mo_saml_in_array($PS, $bn, true)) {
            goto M1T;
        }
        echo esc_html(sprintf("\101\x6c\147\x6f\162\151\164\150\155\x20\144\151\163\141\142\x6c\x65\x64\x3a\x20" . var_export($PS, true)));
        exit;
        M1T:
        $SE = $QU->decryptNode($IX, false);
        $KD = "\x3c\162\157\157\x74\40\x78\155\x6c\x6e\x73\72\163\x61\155\154\x3d\42\165\x72\156\72\157\x61\163\151\163\72\x6e\141\x6d\145\163\x3a\x74\x63\x3a\x53\101\115\x4c\x3a\62\x2e\60\72\x61\163\163\x65\162\164\151\x6f\x6e\x22\x20" . "\x78\155\154\156\x73\x3a\170\163\151\75\x22\150\x74\x74\x70\x3a\57\x2f\167\167\167\x2e\x77\63\56\157\162\147\x2f\62\x30\x30\61\57\130\x4d\x4c\123\143\x68\x65\x6d\x61\55\x69\x6e\x73\x74\141\156\143\x65\x22\76" . $SE . "\x3c\57\x72\x6f\157\x74\76";
        $Ew = self::mo_saml_safe_load_xml($KD, Mo_Saml_Error_Codes::$error_codes["\127\120\x53\x41\x4d\114\105\x52\122\x30\63\x33"]);
        if ($Ew) {
            goto eWu;
        }
        throw new Mo_SAML_Element_Decryption_Exception("\106\x61\x69\154\145\144\x20\x74\157\x20\x70\141\x72\163\145\40\x64\145\x63\162\x79\x70\x74\145\x64\x20\130\115\114\x2e\40\115\141\171\142\x65\x20\164\150\145\x20\167\x72\157\156\x67\40\163\x68\x61\x72\x65\x64\153\145\171\x20\x77\x61\x73\40\165\x73\x65\x64\77");
        eWu:
        $lu = $Ew->firstChild->firstChild;
        if (!($lu === null)) {
            goto Vj4;
        }
        throw new Mo_SAML_Element_Decryption_Exception("\x4d\x69\163\163\151\156\x67\x20\x65\156\143\x72\171\x70\164\x65\x64\x20\x65\154\x65\155\145\156\164\x2e");
        Vj4:
        if ($lu instanceof DOMElement) {
            goto lrv;
        }
        printf("\104\145\143\162\x79\x70\x74\145\x64\40\145\154\145\155\x65\156\x74\x20\167\141\x73\40\x6e\157\164\x20\x61\143\x74\165\141\x6c\x6c\171\x20\x61\40\x44\x4f\115\105\154\145\x6d\145\x6e\164\56");
        lrv:
        return $lu;
    }
    public static function decryptElement(DOMElement $o7, XMLSecurityKey $Vb, array $bn = array(), XMLSecurityKey $tV = null, $Yj = null)
    {
        try {
            return self::doDecryptElement($o7, $Vb, $bn);
        } catch (Exception $G2) {
            if ($Yj === "\164\x65\x73\x74\126\x61\x6c\151\x64\141\164\x65" or $Yj === "\x74\145\163\x74\x4e\145\x77\103\x65\162\164\x69\146\151\x63\x61\164\145") {
                goto fHY;
            }
            Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\127\x50\123\101\115\114\x45\x52\122\x30\61\x34"]);
            goto AcS;
            fHY:
            Mo_Saml_Error_Message::mo_saml_display_test_config_error(Mo_Saml_Error_Codes::$error_codes["\127\120\x53\101\x4d\x4c\105\122\122\x30\x31\x34"]);
            AcS:
            exit;
        }
    }
    public static function get_mapped_groups($sS, $bW)
    {
        $QH = array();
        if (empty($bW)) {
            goto q8c;
        }
        $w5 = array();
        $Ev = 1;
        cM6:
        if (!($Ev < 10)) {
            goto UyY;
        }
        $z4 = $sS->get("\147\x72\157\x75\160" . $Ev . "\137\x6d\x61\160");
        $w5[$Ev] = explode("\x3b", $z4);
        ++$Ev;
        goto cM6;
        UyY:
        q8c:
        foreach ($bW as $we) {
            if (empty($we)) {
                goto m2J;
            }
            $Ev = 0;
            $pI = false;
            jJC:
            if (!($Ev < 9 && !$pI)) {
                goto Kpt;
            }
            if (!(!empty($w5[$Ev]) && self::mo_saml_in_array($we, $w5[$Ev]))) {
                goto mPN;
            }
            $QH[] = $sS->get("\x67\x72\157\165\160" . $Ev);
            $pI = true;
            mPN:
            ++$Ev;
            goto jJC;
            Kpt:
            m2J:
            ZR6:
        }
        AOO:
        return array_unique($QH);
    }
    public static function getEncryptionAlgorithm($lO)
    {
        switch ($lO) {
            case "\x68\x74\164\x70\72\57\57\x77\167\x77\56\167\x33\56\x6f\x72\x67\57\62\60\x30\x31\57\60\x34\x2f\170\155\154\145\156\143\43\164\162\x69\x70\154\145\x64\x65\x73\x2d\143\x62\x63":
                return XMLSecurityKey::TRIPLEDES_CBC;
                goto SNp;
            case "\150\x74\164\160\72\x2f\57\x77\167\x77\x2e\167\x33\x2e\157\162\147\57\x32\x30\60\61\57\x30\64\57\x78\x6d\154\145\156\x63\43\141\145\163\x31\x32\70\55\143\x62\x63":
                return XMLSecurityKey::AES128_CBC;
            case "\x68\164\x74\x70\x3a\x2f\57\x77\167\167\56\167\x33\56\157\x72\x67\x2f\x32\60\x30\x31\57\60\64\57\x78\x6d\x6c\x65\156\143\43\141\x65\x73\61\x39\x32\55\143\142\x63":
                return XMLSecurityKey::AES192_CBC;
                goto SNp;
            case "\150\x74\x74\160\72\57\57\x77\x77\167\56\x77\x33\x2e\157\162\147\57\x32\x30\60\x31\57\x30\x34\57\x78\x6d\154\x65\156\x63\43\x61\x65\163\62\65\x36\55\x63\x62\x63":
                return XMLSecurityKey::AES256_CBC;
                goto SNp;
            case "\150\x74\164\160\72\x2f\x2f\x77\x77\x77\56\x77\63\56\x6f\x72\x67\57\62\x30\60\x31\x2f\60\64\57\x78\x6d\154\x65\x6e\143\43\162\x73\141\x2d\x31\137\65":
                return XMLSecurityKey::RSA_1_5;
                goto SNp;
            case "\150\x74\164\x70\x3a\57\x2f\167\x77\167\56\x77\x33\x2e\157\162\x67\57\62\x30\60\61\57\x30\64\x2f\170\x6d\154\x65\x6e\x63\43\162\163\x61\55\x6f\141\145\x70\x2d\x6d\147\146\61\160":
                return XMLSecurityKey::RSA_OAEP_MGF1P;
                goto SNp;
            case "\150\x74\x74\x70\72\x2f\57\167\x77\167\56\167\x33\56\157\x72\x67\x2f\62\x30\60\60\x2f\x30\71\57\x78\155\x6c\x64\163\151\x67\x23\x64\163\141\x2d\163\x68\x61\61":
                return XMLSecurityKey::DSA_SHA1;
                goto SNp;
            case "\150\164\164\x70\x3a\57\x2f\x77\167\167\x2e\167\63\x2e\x6f\162\x67\x2f\x32\x30\60\60\57\x30\x39\57\x78\x6d\x6c\144\x73\x69\147\43\162\163\141\x2d\163\x68\141\61":
                return XMLSecurityKey::RSA_SHA1;
                goto SNp;
            case "\x68\164\164\160\x3a\x2f\57\167\167\x77\56\167\x33\56\x6f\x72\147\x2f\x32\x30\60\61\57\x30\x34\x2f\170\x6d\154\144\x73\151\x67\x2d\x6d\157\162\x65\x23\x72\x73\141\55\163\150\x61\62\x35\66":
                return XMLSecurityKey::RSA_SHA256;
                goto SNp;
            case "\150\x74\164\x70\72\57\57\x77\167\x77\56\167\63\x2e\x6f\162\147\57\x32\60\x30\x31\x2f\60\x34\57\x78\x6d\x6c\144\163\x69\x67\55\155\157\x72\x65\43\162\x73\x61\x2d\163\x68\x61\63\x38\64":
                return XMLSecurityKey::RSA_SHA384;
                goto SNp;
            case "\x68\164\x74\160\x3a\57\x2f\167\167\167\56\x77\63\x2e\x6f\x72\147\x2f\x32\x30\60\61\x2f\x30\x34\x2f\x78\x6d\x6c\144\163\151\147\55\x6d\157\162\145\43\x72\163\141\x2d\x73\x68\141\65\61\x32":
                return XMLSecurityKey::RSA_SHA512;
                goto SNp;
            default:
                echo esc_html(sprintf("\111\156\x76\141\154\151\x64\40\x45\x6e\143\162\171\x70\x74\x69\157\x6e\x20\x4d\x65\x74\x68\157\x64\72\x20" . $lO));
                exit;
                goto SNp;
        }
        HSJ:
        SNp:
    }
    public static function insertSignature(XMLSecurityKey $R2, array $Xi, DOMElement $le, DOMNode $Bx = null)
    {
        $iB = new XMLSecurityDSig();
        $iB->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
        switch ($R2->type) {
            case XMLSecurityKey::RSA_SHA256:
                $Gf = XMLSecurityDSig::SHA256;
                goto VXl;
            case XMLSecurityKey::RSA_SHA384:
                $Gf = XMLSecurityDSig::SHA384;
                goto VXl;
            case XMLSecurityKey::RSA_SHA512:
                $Gf = XMLSecurityDSig::SHA512;
                goto VXl;
            default:
                $Gf = XMLSecurityDSig::SHA1;
        }
        bs3:
        VXl:
        $iB->addReferenceList(array($le), $Gf, array("\150\x74\164\x70\72\57\x2f\167\167\x77\56\167\63\56\x6f\x72\147\57\x32\60\x30\x30\57\x30\71\57\170\155\x6c\x64\163\x69\x67\43\145\x6e\166\145\x6c\157\160\x65\144\x2d\x73\151\147\156\141\x74\x75\162\x65", XMLSecurityDSig::EXC_C14N), array("\151\x64\137\156\141\155\145" => "\x49\104", "\157\166\x65\162\167\x72\151\x74\x65" => false));
        $iB->sign($R2);
        foreach ($Xi as $n6) {
            $iB->add509Cert($n6, true);
            RRz:
        }
        wbT:
        $iB->insertSignature($le, $Bx);
    }
    public static function getRemainingDaysOfCertificate($n6)
    {
        $Eh = openssl_x509_parse($n6);
        $yX = $Eh["\166\x61\x6c\x69\144\x54\x6f\x5f\164\x69\x6d\145\x5f\164"];
        $dK = $yX - time();
        return round($dK / (60 * 60 * 24));
    }
    public static function getExpiryDateOfCertificate($n6)
    {
        $Eh = openssl_x509_parse($n6);
        return $Eh["\166\141\154\151\144\124\x6f\x5f\164\x69\x6d\145\137\x74"];
    }
    public static function getValidUntilDateFromCert($n6)
    {
        $Eh = openssl_x509_parse($n6);
        $yX = $Eh["\x76\141\154\151\x64\124\x6f\x5f\164\151\x6d\145\137\164"];
        $No = date("\131\55\x6d\55\144", $yX);
        $dy = $No . "\124\x32\x33\72\x35\71\x3a\x35\71\132";
        return $dy;
    }
    public static function signXML($KD, $XE, $SA = '', $wh = false)
    {
        $EJ = array("\164\171\x70\x65" => "\160\162\151\166\141\x74\145");
        $R2 = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, $EJ);
        if ($wh) {
            goto cMw;
        }
        $f8 = mo_saml_get_sp_private_key_for_idp($XE);
        $R8 = mo_saml_get_sp_public_cert_for_idp($XE);
        goto RxM;
        cMw:
        $f8 = file_get_contents(plugin_dir_path(__FILE__) . "\162\x65\x73\157\165\x72\143\145\x73" . DIRECTORY_SEPARATOR . mo_options_enum_default_sp_certificate::SP_PRIVATE_KEY_FILE_NAME);
        $R8 = file_get_contents(plugin_dir_path(__FILE__) . "\x72\x65\163\157\165\162\143\145\163" . DIRECTORY_SEPARATOR . mo_options_enum_default_sp_certificate::SP_PUBLIC_CERT_FILE_NAME);
        RxM:
        $R2->loadKey($f8, false);
        $nI = self::mo_saml_safe_load_xml($KD, Mo_Saml_Error_Codes::$error_codes["\x57\x50\123\101\x4d\114\x45\122\x52\x30\x32\x38"]);
        $ss = $nI->firstChild;
        if (!empty($SA)) {
            goto tB5;
        }
        self::insertSignature($R2, array($R8), $ss);
        goto YY5;
        tB5:
        $Nf = $nI->getElementsByTagName($SA)->item(0);
        self::insertSignature($R2, array($R8), $ss, $Nf);
        YY5:
        $QQ = $ss->ownerDocument->saveXML($ss);
        $IZ = base64_encode($QQ);
        return $IZ;
    }
    public static function postSAMLRequest($Oz, $R1, $Yj, $tx = array(), $Do = false)
    {
        $Yj = is_string($Yj) ? $Yj : "\57";
        echo "\15\12\40\x20\40\40\40\x20\x20\x20\74\150\x74\155\154\76\xd\12\40\40\x20\x20\x20\40\x20\40\x20\40\x20\40\74\x62\157\144\171\x3e\15\12\40\40\40\40\x20\40\40\x20\40\x20\x20\x20\40\40\x20\x20\120\x6c\145\141\163\x65\40\x77\x61\151\x74\x2e\x2e\x2e\xd\12\40\x20\x20\40\40\40\40\40\x20\40\40\40\x20\x20\x20\40\74\x66\x6f\162\155\40\141\143\x74\151\157\x6e\x3d\x22" . esc_url($Oz) . "\x22\x20\x6d\145\164\150\157\144\75\x22\160\x6f\x73\x74\x22\40\151\x64\75\x22\x73\x61\155\154\55\162\x65\161\165\x65\x73\164\x2d\146\157\x72\x6d\x22\x3e\15\12\40\x20\40\x20\x20\x20\x20\40\40\40\x20\40\x20\40\40\40\x20\x20\40\x20\74\x69\156\160\x75\164\40\x74\x79\x70\x65\x3d\42\150\x69\144\x64\x65\156\42\x20\x6e\141\155\x65\75\42\123\x41\115\114\x52\145\x71\x75\145\163\x74\42\x20\x76\x61\154\x75\x65\75\x22" . esc_attr($R1) . "\x22\40\x2f\76\15\xa\x20\40\x20\40\x20\40\40\40\40\40\40\40\40\x20\x20\40\x20\x20\x20\x20\x3c\x69\156\x70\165\164\40\x74\x79\160\x65\75\x22\150\151\x64\x64\145\x6e\x22\40\x6e\x61\x6d\x65\x3d\x22\x52\x65\154\141\171\x53\x74\141\x74\145\42\40\166\x61\154\165\145\75\42" . esc_attr($Yj) . "\x22\x20\x2f\x3e";
        foreach ($tx as $R2 => $EB) {
            if (!("\157\160\164\151\157\156" !== $R2)) {
                goto LpR;
            }
            echo "\74\151\156\x70\x75\164\40\164\x79\160\145\75\x22\x68\151\x64\144\x65\x6e\42\40\x6e\x61\x6d\145\x3d\42" . esc_attr($R2) . "\x22\x20\x76\141\154\x75\x65\75\x22" . esc_attr($EB) . "\42\40\x2f\76";
            LpR:
            WB4:
        }
        DrC:
        if (!is_string($Do)) {
            goto jli;
        }
        echo "\74\x69\x6e\x70\165\x74\40\x74\x79\160\145\x3d\x22\x68\151\x64\144\145\x6e\x22\40\x6e\x61\x6d\145\75\42\x45\155\141\151\154\x22\x20\x76\x61\x6c\165\x65\75\42" . esc_attr($Do) . "\x22\40\x2f\x3e";
        jli:
        echo "\xd\xa\40\40\40\x20\40\x20\x20\40\x20\40\x20\40\x20\x20\40\40\x3c\x2f\146\157\162\x6d\x3e\xd\12\40\40\x20\x20\x20\x20\x20\40\40\40\40\40\x20\x20\x20\40\74\x73\x63\162\x69\160\164\76\15\xa\x20\x20\40\40\40\40\x20\x20\x20\40\x20\x20\40\40\40\x20\x20\x20\x20\40\144\157\143\x75\155\145\x6e\164\x2e\147\x65\x74\x45\x6c\x65\155\x65\x6e\x74\102\171\x49\144\x28\47\163\141\x6d\154\x2d\x72\145\x71\165\145\x73\x74\55\x66\157\162\155\47\51\56\163\165\142\155\151\164\x28\51\x3b\xd\xa\x20\40\40\40\40\x20\x20\x20\40\x20\x20\40\40\x20\40\40\x3c\x2f\x73\143\162\151\x70\164\x3e\xd\12\x20\40\40\40\x20\x20\x20\x20\x20\40\x20\40\x3c\57\142\x6f\x64\171\x3e\xd\xa\x20\x20\40\40\40\x20\x20\40\x3c\57\150\x74\155\154\x3e";
        exit;
    }
    public static function postSAMLResponse($Oz, $L0, $Yj)
    {
        echo "\xd\12\40\40\40\x20\40\40\40\40\74\x68\164\155\154\76\xd\12\x20\40\40\x20\x20\x20\40\40\x20\40\40\x20\74\142\x6f\144\x79\76\xd\xa\40\x20\x20\x20\x20\40\40\x20\40\x20\40\x20\x20\x20\x20\40\x50\154\145\x61\x73\x65\x20\x77\141\x69\164\x2e\56\56\15\xa\40\x20\x20\x20\x20\40\40\40\40\40\x20\x20\x20\40\x20\40\74\146\x6f\162\x6d\40\x61\143\164\x69\157\x6e\x3d\x22" . esc_url($Oz) . "\42\40\x6d\x65\x74\x68\x6f\144\75\42\x70\x6f\x73\164\42\40\x69\x64\x3d\x22\x73\141\155\x6c\55\162\x65\x73\x70\157\156\163\x65\x2d\x66\157\x72\x6d\42\76\x3c\151\156\160\x75\164\40\164\x79\x70\x65\x3d\x22\150\151\x64\x64\x65\156\42\40\x6e\x61\x6d\x65\75\x22\123\101\x4d\x4c\x52\x65\163\x70\157\156\x73\145\x22\x20\166\x61\x6c\x75\145\75\x22" . esc_attr($L0) . "\42\x20\x2f\x3e\xd\12\40\x20\x20\x20\40\40\40\x20\x20\40\x20\x20\x20\40\40\x20\40\40\40\x20\x3c\151\x6e\160\165\164\40\x74\171\x70\145\75\x22\x68\151\144\144\x65\x6e\x22\x20\156\141\x6d\145\75\42\122\x65\154\x61\171\x53\164\x61\x74\x65\42\x20\x76\x61\x6c\165\x65\75\42" . esc_attr($Yj) . "\42\x20\x2f\x3e\15\12\40\x20\x20\x20\x20\x20\40\x20\40\x20\40\40\40\40\40\40\74\x2f\146\157\162\x6d\76\15\12\40\x20\x20\40\40\40\x20\x20\40\40\x20\40\x20\x20\x20\40\74\163\x63\x72\x69\160\x74\x3e\xd\12\x20\x20\x20\x20\x20\x20\40\x20\40\40\x20\x20\x20\40\40\40\x20\x20\40\x20\x64\x6f\143\x75\155\x65\x6e\164\x2e\147\145\164\x45\154\145\155\x65\x6e\x74\102\171\x49\x64\50\x27\x73\x61\x6d\154\55\x72\145\x73\160\157\156\x73\145\55\x66\157\162\x6d\x27\51\x2e\x73\x75\x62\x6d\151\164\x28\51\73\15\12\x20\40\x20\40\x20\40\x20\40\40\40\40\x20\40\40\40\x20\x3c\x2f\x73\143\162\151\160\x74\76\15\12\x20\x20\x20\x20\x20\40\x20\40\40\x20\40\40\x3c\x2f\142\x6f\144\171\x3e\xd\12\x20\40\x20\x20\x20\40\40\x20\x3c\57\x68\164\155\154\76";
        exit;
    }
    public static function sanitize_certificate($n6)
    {
        $n6 = trim($n6);
        $n6 = preg_replace("\x2f\133\15\xa\135\x2b\57", '', $n6);
        $n6 = str_replace("\55", '', $n6);
        $n6 = str_replace("\102\105\107\111\116\40\103\x45\x52\124\x49\x46\111\103\101\124\105", '', $n6);
        $n6 = str_replace("\105\116\104\40\103\x45\x52\x54\111\106\x49\103\101\124\x45", '', $n6);
        $n6 = str_replace("\x20", '', $n6);
        $n6 = chunk_split($n6, 64, "\15\xa");
        $n6 = "\x2d\x2d\x2d\55\x2d\102\105\x47\x49\x4e\40\x43\x45\x52\x54\x49\106\x49\103\x41\124\x45\x2d\x2d\x2d\55\x2d\xd\xa" . $n6 . "\55\55\x2d\55\x2d\x45\116\104\40\103\x45\122\124\111\106\111\103\101\x54\x45\55\55\x2d\x2d\55";
        return $n6;
    }
    public static function desanitize_certificate($n6)
    {
        $n6 = preg_replace("\x2f\133\15\xa\135\x2b\57", '', $n6);
        $n6 = str_replace("\x2d\x2d\x2d\x2d\x2d\102\105\107\111\116\40\103\x45\x52\x54\111\106\x49\x43\x41\124\105\x2d\x2d\x2d\x2d\x2d", '', $n6);
        $n6 = str_replace("\55\55\x2d\x2d\x2d\x45\x4e\104\x20\103\105\x52\x54\111\x46\111\x43\101\x54\x45\55\x2d\x2d\x2d\55", '', $n6);
        $n6 = str_replace("\x20", '', $n6);
        return $n6;
    }
    public static function mo_saml_wp_remote_call($Oz, $MS = array(), $O1 = false)
    {
        if (!$O1) {
            goto sRl;
        }
        $d4 = wp_remote_get($Oz, $MS);
        goto HSX;
        sRl:
        $d4 = wp_remote_post($Oz, $MS);
        HSX:
        if (!is_wp_error($d4)) {
            goto ioR;
        }
        update_option("\155\157\137\x73\x61\x6d\x6c\x5f\155\x65\163\163\141\147\x65", "\x55\x6e\x61\142\x6c\145\40\164\x6f\40\143\157\156\x6e\145\x63\x74\40\x74\157\40\164\150\145\40\x49\x6e\164\145\x72\156\x65\164\56\40\x50\x6c\x65\141\163\x65\x20\x74\x72\x79\40\141\x67\141\151\x6e\x2e");
        self::mo_saml_show_error_message();
        return;
        goto u6f;
        ioR:
        return $d4["\142\157\144\171"];
        u6f:
    }
    public static function mo_saml_fetch_idps_count_in_license()
    {
        $R2 = get_option("\155\x6f\137\x73\x61\155\154\137\143\165\x73\164\157\155\x65\x72\x5f\164\157\x6b\x65\x6e");
        $nk = (int) AESEncryption::decrypt_data(get_option("\x6e\157\137\x6f\146\x5f\x73\x70"), $R2);
        if ($nk) {
            goto msc;
        }
        if (Mo_License_Service::is_customer_license_verified()) {
            goto WjC;
        }
        return 1;
        WjC:
        $Uw = new Customersaml();
        $Qm = $Uw->check_customer_ln();
        if ($Qm) {
            goto jnE;
        }
        return 1;
        jnE:
        $Qm = json_decode($Qm, true);
        if (isset($Qm["\x73\x74\x61\x74\165\x73"]) && "\123\125\x43\x43\105\x53\x53" === $Qm["\163\164\141\164\x75\x73"]) {
            goto r26;
        }
        $nk = 1;
        goto JO_;
        r26:
        update_customer_idp_count($Qm);
        if (!empty($Qm["\x6e\157\x4f\146\x53\120"])) {
            goto NDV;
        }
        $nk = 1;
        goto TCZ;
        NDV:
        $nk = $Qm["\156\157\117\x66\x53\x50"];
        TCZ:
        JO_:
        msc:
        return $nk;
    }
    public static function mo_saml_get_enabled_idps_configuration($Ac = '')
    {
        $ad = array();
        $tq = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\155\x6c\137\151\144\x65\x6e\x74\151\x74\171\x5f\x70\x72\157\x76\151\x64\145\x72\x73", true, $Ac);
        foreach ($tq as $BB => $Ug) {
            if (empty($Ug["\x65\x6e\x61\142\154\145\137\x69\144\160"])) {
                goto GTc;
            }
            $ad[$BB] = $Ug;
            GTc:
            rt7:
        }
        YA5:
        $s6 = new EnvironmentDao($Ac);
        $Sx = true;
        if (empty($Ac)) {
            goto j2C;
        }
        $Sx = false;
        j2C:
        $s6->mo_save_environment_settings("\155\157\x5f\163\141\155\154\x5f\145\x6e\141\x62\154\x65\x64\x5f\x69\x64\x70\163", $ad, $Sx);
        return $ad;
    }
    public static function mo_saml_disable_extra_idps($Ac = '')
    {
        $ad = self::mo_saml_get_enabled_idps_configuration($Ac);
        $uf = count($ad);
        $XY = self::mo_saml_fetch_idps_count_in_license();
        if (!($uf > $XY)) {
            goto CsL;
        }
        $tq = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\155\154\x5f\x69\x64\145\x6e\x74\151\164\171\137\160\162\x6f\x76\151\144\x65\x72\x73", true, $Ac);
        $Ib = array_keys($ad);
        $zH = EnvironmentHelper::getOptionForSelectedEnvironment("\163\x61\x6d\154\x5f\144\x65\146\141\x75\154\164\x5f\151\144\x70", false, $Ac);
        $Ev = $XY;
        s5a:
        if (!($Ev < $uf)) {
            goto ula;
        }
        $BB = $Ib[$Ev];
        if ($zH !== $BB) {
            goto NFe;
        }
        if (!empty($Ib[$Ev - 1])) {
            goto FV_;
        }
        goto wJR;
        NFe:
        unset($ad[$BB]);
        $tq[$BB]["\x65\x6e\x61\142\154\145\x5f\151\144\x70"] = false;
        goto wJR;
        FV_:
        $BB = $Ib[$Ev - 1];
        unset($ad[$BB]);
        $tq[$BB]["\x65\156\x61\142\x6c\x65\x5f\151\x64\x70"] = false;
        wJR:
        v29:
        $Ev++;
        goto s5a;
        ula:
        $s6 = new EnvironmentDao($Ac);
        $Sx = true;
        if (empty($Ac)) {
            goto m4d;
        }
        $Sx = false;
        m4d:
        $s6->mo_save_environment_settings("\x6d\x6f\137\x73\x61\155\x6c\137\145\x6e\x61\142\154\x65\x64\137\x69\x64\x70\x73", $ad, $Sx);
        $s6->mo_save_environment_settings("\163\141\155\x6c\137\x69\x64\x65\156\x74\151\164\171\x5f\x70\x72\x6f\166\151\x64\145\x72\163", $tq, $Sx);
        CsL:
    }
    public static function mo_saml_array_merge($O8, $Kl)
    {
        if (!(is_array($O8) && is_array($Kl))) {
            goto Ybl;
        }
        $Qs = array_merge($O8, $Kl);
        return $Qs;
        Ybl:
        return $O8;
    }
    public static function mo_saml_array_push($Yv, $ss)
    {
        if (!is_array($Yv)) {
            goto IJ1;
        }
        array_push($Yv, $ss);
        return $Yv;
        IJ1:
        return $Yv;
    }
    public static function mo_saml_in_array($EJ, $Yv, $io = false)
    {
        if (!is_array($Yv)) {
            goto T1x;
        }
        return in_array($EJ, $Yv, $io);
        T1x:
        return false;
    }
    public static function mo_saml_is_plugin_active($uH)
    {
        $BC = get_option("\x61\143\x74\x69\x76\x65\137\160\154\x75\147\x69\x6e\x73");
        return in_array($uH, (array) $BC);
    }
    public static function get_box_expiry_notice_heading($Ox)
    {
        $do = '';
        $Rr = Mo_License_Service::is_license_expired();
        if (true === $Rr["\x53\x54\101\x54\125\x53"]) {
            goto eLt;
        }
        if (false === $Rr["\123\124\101\x54\x55\123"] && "\114\111\103\105\116\123\x45\x5f\111\116\x5f\107\122\101\103\105" === $Rr["\x43\117\104\105"]) {
            goto gbM;
        }
        if ($Ox["\x23\43\162\x65\155\x61\151\x6e\151\156\147\x5f\144\x61\171\x73\x23\43"] <= 60) {
            goto Amy;
        }
        goto LsB;
        eLt:
        $do = "\x57\x61\162\x6e\x69\156\x67\40\x3a\x20\131\157\165\x72\x20\123\x53\x4f\40\150\x61\x73\x20\163\x74\x6f\x70\160\x65\x64\40\167\157\162\153\x69\156\147\56\40\x52\x65\156\145\167\40\x79\x6f\165\x72\40\x6c\x69\143\145\156\163\145\40\x6e\157\x77\x21";
        goto LsB;
        gbM:
        $do = "\x59\157\165\x72\x20\x70\x6c\165\147\x69\x6e\40\150\x61\x73\x20\x65\x78\160\x69\162\145\x64\40\141\156\144\x20\x53\x53\117\40\x77\x69\x6c\x6c\40\163\164\x6f\x70\x20\167\157\x72\153\x69\x6e\x67\40\x69\x6e\x20\74\x73\160\141\x6e\40\151\x64\x3d\42\155\157\x5f\x73\x61\x6d\154\137\160\162\x6f\x66\151\x6c\x65\137\142\157\x78\137\x63\157\165\156\164\x65\x72\42\x3e" . esc_html($Ox["\x23\43\147\x72\141\x63\x65\137\x64\x61\171\163\x5f\x6c\145\x66\164\43\x23"]) . "\74\57\163\160\141\156\x3e\40\x64\141\x79\163\56\40\122\145\156\145\x77\x20\x79\x6f\165\x72\40\x6c\151\143\x65\x6e\163\x65\x20\156\x6f\x77\x20\x74\157\x20\x61\x76\157\x69\x64\x20\144\151\x73\x72\165\160\x74\151\157\156\x2e";
        goto LsB;
        Amy:
        $do = "\x4c\x69\143\x65\156\163\145\x20\x45\x78\x70\151\x72\x79\x20\116\157\x74\151\143\x65\x20\x3a\40\x50\154\165\147\151\x6e\40\114\151\143\x65\x6e\x73\145\40\147\145\164\164\x69\156\147\x20\145\x78\160\x69\162\x65\x64\40\151\156\x20\x3c\163\160\141\156\40\151\x64\x3d\42\x6d\x6f\137\163\141\x6d\x6c\137\160\x72\157\x66\151\154\145\x5f\x62\157\170\137\x63\157\x75\x6e\164\x65\162\x22\x3e\x20" . esc_html($Ox["\43\x23\162\x65\155\x61\151\156\151\156\147\x5f\144\141\171\163\x23\x23"]) . "\x20\74\57\x73\x70\141\x6e\76\40\144\141\171\163";
        LsB:
        return $do;
    }
    public static function get_expiry_notice_class($Hn)
    {
        if ($Hn < 60 && $Hn > 0) {
            goto STk;
        }
        if ($Hn <= 0 && $Hn > -15) {
            goto fQK;
        }
        if ($Hn <= -15) {
            goto eBq;
        }
        goto FKA;
        STk:
        return "\x6d\x6f\55\163\141\x6d\x6c\55\x77\141\162\x6e\x69\156\x67\55\171\145\154\x6c\157\167";
        goto FKA;
        fQK:
        return "\x6d\x6f\55\x73\141\155\154\x2d\167\x61\x72\156\x69\156\147\x2d\x6f\x72\x61\156\147\x65";
        goto FKA;
        eBq:
        return "\155\157\x2d\x73\141\x6d\154\x2d\x77\x61\162\156\151\x6e\x67\55\162\x65\144";
        FKA:
        return '';
    }
    public static function mo_saml_show_success_message()
    {
        remove_action("\141\144\155\x69\156\137\156\157\x74\x69\143\x65\163", array("\123\x41\x4d\x4c\x53\120\125\164\x69\x6c\x69\x74\x69\145\163", "\155\157\137\163\141\155\154\137\145\162\x72\x6f\x72\137\155\145\x73\x73\141\147\x65"));
        add_action("\141\x64\x6d\x69\156\x5f\x6e\x6f\x74\151\x63\x65\163", array("\x53\101\115\x4c\123\x50\125\x74\x69\154\x69\164\151\x65\x73", "\x6d\157\137\x73\x61\x6d\154\x5f\163\x75\x63\143\x65\x73\163\137\155\x65\163\163\x61\147\145"));
    }
    public static function mo_saml_show_error_message()
    {
        remove_action("\141\x64\x6d\151\x6e\x5f\156\x6f\x74\151\143\x65\163", array("\x53\101\x4d\x4c\x53\120\x55\164\151\x6c\x69\x74\x69\x65\x73", "\155\x6f\x5f\163\x61\155\154\137\163\165\143\x63\x65\163\163\x5f\x6d\145\x73\163\x61\x67\145"));
        add_action("\141\x64\155\x69\156\x5f\x6e\x6f\x74\x69\143\x65\x73", array("\x53\101\115\x4c\x53\x50\x55\164\x69\154\151\x74\x69\x65\x73", "\155\x6f\x5f\x73\x61\155\154\137\x65\162\x72\x6f\162\x5f\x6d\145\x73\163\141\147\145"));
    }
    public static function mo_saml_error_message()
    {
        $A4 = "\145\x72\162\157\162";
        $h9 = get_option("\x6d\x6f\x5f\163\141\155\154\x5f\155\145\x73\x73\x61\x67\145");
        echo "\x3c\144\x69\166\x20\143\x6c\141\x73\163\75\47" . esc_html($A4) . "\x27\76\x20\x3c\x70\x3e" . wp_kses_post($h9) . "\74\57\160\x3e\x3c\57\x64\x69\166\76";
    }
    public static function mo_saml_success_message()
    {
        $A4 = "\x75\x70\144\x61\x74\x65\x64";
        $h9 = get_option("\x6d\x6f\x5f\163\x61\x6d\x6c\x5f\155\x65\163\x73\x61\x67\145");
        echo "\x3c\144\151\166\40\143\154\x61\163\x73\x3d\47" . esc_html($A4) . "\x27\x3e\x20\x3c\160\x3e" . wp_kses_post($h9) . "\74\x2f\x70\x3e\x3c\x2f\x64\151\166\x3e";
    }
    public static function mo_saml_check_saml_response_for_replay_attack($qt)
    {
        $i4 = apply_filters("\x6d\157\x5f\163\x61\x6d\x6c\137\163\x6b\x69\160\137\x63\x68\x65\x63\153\137\163\x61\155\x6c\x5f\162\145\x73\160\x6f\x6e\163\145\137\146\x6f\162\x5f\x72\x65\160\154\x61\171\137\141\x74\x74\141\x63\x6b", false);
        if (!$i4) {
            goto Mcg;
        }
        return;
        Mcg:
        if (!(current($qt->getAssertions()) == null)) {
            goto caD;
        }
        return;
        caD:
        $gB = current($qt->getAssertions())->getNotOnOrAfter();
        $dY = current($qt->getAssertions())->getId();
        if (null !== $gB) {
            goto tii;
        }
        $e3 = 15 * MINUTE_IN_SECONDS;
        goto dx0;
        tii:
        $e3 = $gB - time() + 300;
        dx0:
        $u_ = get_transient($dY);
        if (false === $u_) {
            goto G89;
        }
        Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\x57\120\123\x41\x4d\114\x45\x52\122\x30\x31\x36"]);
        goto T5p;
        G89:
        set_transient($dY, "\x65\x78\x69\x73\x74\145\x64", $e3);
        T5p:
    }
    public static function mo_saml_safe_load_xml($KD, $oU, $JS = false)
    {
        if (class_exists("\104\117\115\x44\x6f\143\x75\155\x65\156\164")) {
            goto pJb;
        }
        throw new Mo_SAML_DOM_Extension_Disabled_Exception("\x44\117\115\x44\157\143\165\x6d\145\156\164\40\116\x6f\164\x20\x49\x6e\163\164\x61\154\154\145\144\56");
        pJb:
        $nI = new DOMDocument();
        libxml_set_external_entity_loader(null);
        set_error_handler(array("\123\101\x4d\x4c\x53\x50\x55\x74\x69\154\x69\164\151\x65\x73", "\x6d\157\137\x73\141\x6d\x6c\137\x68\x61\156\x64\x6c\145\x5f\x78\x6d\154\x5f\x65\x72\162\x6f\162"));
        $nX = $nI->loadXML($KD, LIBXML_NONET);
        restore_error_handler();
        if (!$nX) {
            goto UDD;
        }
        foreach ($nI->childNodes as $dT) {
            if (!(XML_DOCUMENT_TYPE_NODE === $dT->nodeType)) {
                goto yr2;
            }
            self::mo_saml_show_error($oU, $JS);
            yr2:
            rCW:
        }
        Jkj:
        return $nI;
        UDD:
        self::mo_saml_show_error($oU, $JS);
    }
    public static function mo_saml_handle_xml_error($fa, $fC, $rZ, $rJ)
    {
        if (E_WARNING === $fa && substr_count($fC, "\104\x4f\115\104\x6f\x63\x75\155\x65\156\164\72\x3a\154\x6f\141\144\130\115\114\x28\x29") > 0) {
            goto Ri2;
        }
        return false;
        goto Yod;
        Ri2:
        return true;
        Yod:
    }
    public static function mo_saml_show_error($oU, $JS)
    {
        if ($JS) {
            goto c2w;
        }
        Mo_Saml_Error_Message::mo_saml_display_error_code_message($oU);
        goto gyI;
        c2w:
        Mo_Saml_Error_Message::mo_saml_display_error_notice_to_admin($oU);
        gyI:
    }
    public static function mo_saml_is_ssl()
    {
        if (!(is_ssl() || isset($_SERVER["\x48\124\124\x50\x5f\x58\137\106\x4f\122\127\101\122\x44\x45\104\137\120\122\117\124\117"]) && "\150\x74\x74\160\163" == $_SERVER["\110\x54\x54\x50\137\130\x5f\x46\117\x52\127\101\x52\x44\105\x44\x5f\120\x52\x4f\124\x4f"])) {
            goto rhC;
        }
        return true;
        rhC:
        return false;
    }
    public static function mo_saml_get_config_key_array($PI)
    {
        return $PI ? Mo_Saml_Import_Export_Required_Options::SINGLE_IDP_CONFIG_ARRAY : Mo_Saml_Import_Export_Required_Options::MULTIPLE_IDP_CONFIG_ARRAY;
    }
    public static function mo_saml_check_import_required_fields($PI, $ha)
    {
        $fG = self::mo_saml_get_config_key_array($PI);
        $RW = $fG["\x69\144\x70\x5f\x6e\141\x6d\145"];
        $Zd = $fG["\151\163\x73\165\145\x72"];
        $kj = $fG["\x78\65\60\x39\137\x63\x65\x72\x74\x69\146\151\x63\141\164\x65"];
        $g1 = $fG["\x6c\x6f\147\151\156\x5f\165\x72\154"];
        return isset($ha[$RW]) && isset($ha[$Zd]) && isset($ha[$kj]) && !empty(trim($ha[$Zd]) && isset($ha[$g1]));
    }
    public static function mo_saml_validate_identity_provider_name($PI, $ha)
    {
        $Dd = "\x2f\136\133\x61\x2d\172\x41\55\132\x30\55\x39\137\135\53\x24\57";
        $fG = self::mo_saml_get_config_key_array($PI);
        $RW = $fG["\x69\144\x70\x5f\x6e\x61\x6d\145"];
        if (isset($ha[$RW])) {
            goto hoK;
        }
        return false;
        goto sXl;
        hoK:
        return !empty(trim($ha[$RW])) && preg_match($Dd, $ha[$RW]);
        sXl:
    }
    public static function mo_saml_validate_certificate($PI, $ha)
    {
        $fG = self::mo_saml_get_config_key_array($PI);
        $kj = $fG["\170\x35\x30\x39\x5f\x63\145\162\x74\151\x66\x69\143\141\x74\x65"];
        if (isset($ha[$kj])) {
            goto whc;
        }
        return false;
        goto zXi;
        whc:
        return isset($ha[$kj]) && is_array($ha[$kj]) && isset($ha[$kj][0]) && !empty(trim($ha[$kj][0])) && @openssl_x509_read($ha[$kj][0]);
        zXi:
    }
    public static function mo_saml_validate_login_url($PI, $ha)
    {
        $fG = self::mo_saml_get_config_key_array($PI);
        $g1 = $fG["\154\157\147\x69\x6e\137\x75\162\x6c"];
        if (isset($ha[$g1])) {
            goto w4g;
        }
        return false;
        goto EvE;
        w4g:
        return filter_var($ha[$g1], FILTER_VALIDATE_URL);
        EvE:
    }
    public static function mo_saml_get_plugin_base_url()
    {
        return plugin_dir_url(__FILE__);
    }
    public static function mo_saml_validate_idp($XE, $rK)
    {
        if (!($XE === $rK)) {
            goto f8i;
        }
        return false;
        f8i:
        return true;
    }
    public static function mo_saml_validate_public_page_url($hY)
    {
        if (wp_http_validate_url($hY)) {
            goto mNJ;
        }
        throw new Exception(esc_attr(Mo_Saml_Error_Messages_Public_Page_URL::INVALID_URL));
        mNJ:
    }
    public static function mo_saml_trim_semi_colon_separated_values($Is)
    {
        if (empty($Is)) {
            goto nmV;
        }
        $Is = array_map("\164\162\x69\x6d", explode("\73", $Is));
        $Is = implode("\x3b", $Is);
        nmV:
        return $Is;
    }
    public static function mo_saml_delete_plugin_option($BJ = true)
    {
        $dM = Mo_Saml_Delete_Plugin_Option::DELETE_OPTIONS;
        $gy = array();
        foreach ($dM as $R2 => $EB) {
            if (!("\x4d\x75\x6c\x74\151\160\x6c\145\137\x45\x6e\166\151\162\157\x6e\x6d\x65\156\164\x73" === $R2 && !$BJ)) {
                goto O90;
            }
            goto gQO;
            O90:
            $gy[$R2] = mo_get_configuration_array($EB, true);
            gQO:
        }
        TpI:
        foreach ($gy as $u5 => $aT) {
            foreach ($aT as $uc => $If) {
                delete_option($If);
                d66:
            }
            BFf:
            p2Q:
        }
        q0g:
    }
    public static function mo_saml_delete_customer_details()
    {
        do_action("\155\157\137\163\141\155\x6c\x5f\x66\154\165\163\150\137\143\x61\143\x68\145");
        delete_option(Mo_Saml_Options_Plugin_Admin::HOST_NAME);
        delete_option(Mo_Saml_Options_Plugin_Admin::ADMIN_PHONE);
        delete_option(Mo_Saml_Options_Plugin_Admin::ADMIN_PASS);
        delete_option(Mo_Saml_Options_Plugin_Admin::VERIFY_CUSTOMER);
        delete_option(Mo_Saml_Options_Plugin_Admin::ADMIN_CUSTOMER_KEY);
        delete_option(Mo_Saml_Options_Plugin_Admin::ADMIN_API_KEY);
        delete_option(Mo_Saml_Options_Plugin_Admin::CUSTOMER_TOKEN);
        delete_option(Mo_Saml_Options_Plugin_Admin::ADMIN_NOTICES_MESSAGE);
        delete_option(Mo_Saml_Options_Plugin_Admin::SML_LK);
        delete_option(Mo_Saml_Options_Plugin_Admin::NO_OF_SP);
        delete_option(Mo_Saml_Options::LAST_SYNCED_TIME);
        delete_option(Mo_Saml_Options::LICENSE_CHECK_STATUS);
        delete_option(Mo_Saml_Options::LICENSE_UPDATE_TIME);
        delete_option(Mo_Saml_Options::SHOW_ADDONS_NOTICE);
        delete_option(Mo_Saml_Options::IS_TRIAL);
        delete_option(Mo_Saml_Options::LICENSE_EXPIRY_DATE);
    }
    public static function mo_saml_get_disabled_extensions()
    {
        $xL = Mo_Saml_Required_PHP_Extensions::PHP_EXTENSIONS;
        $WZ = array();
        foreach ($xL as $R2 => $EB) {
            if (mo_saml_is_extension_installed($R2)) {
                goto EYN;
            }
            array_push($WZ, $EB);
            EYN:
            zgJ:
        }
        oEO:
        return $WZ;
    }
    public static function mo_saml_check_is_extension_installed()
    {
        if (mo_saml_is_extension_installed("\144\157\x6d")) {
            goto nQH;
        }
        throw new Mo_SAML_DOM_Extension_Disabled_Exception("\104\117\x4d\x44\157\143\x75\155\x65\156\164\x20\x4e\x6f\x74\40\x49\156\x73\x74\141\154\154\145\144\56");
        nQH:
        if (mo_saml_is_extension_installed("\143\165\x72\x6c")) {
            goto Vkb;
        }
        throw new Mo_SAML_CURL_Extension_Disabled_Exception("\103\165\x72\x6c\x20\105\x78\x74\x65\x6e\163\x69\157\x6e\x20\116\x6f\x74\x20\111\156\163\164\x61\x6c\154\145\x64\56");
        Vkb:
        if (mo_saml_is_extension_installed("\157\x70\145\156\x73\x73\154")) {
            goto ycT;
        }
        throw new Mo_SAML_OpenSSL_Extension_Disabled_Exception("\117\x50\x45\116\123\x53\x4c\x20\116\x6f\x74\40\x49\156\163\164\x61\154\x6c\145\x64\x2e");
        ycT:
    }
    public static function mo_saml_extension_disabled_modal()
    {
        $sz = Mo_Saml_Plugin_Pages::PLUGIN_PAGES;
        if (!(!empty($_GET["\x70\141\147\x65"]) && in_array($_GET["\160\x61\147\x65"], $sz))) {
            goto oBu;
        }
        $WZ = self::mo_saml_get_disabled_extensions();
        if (empty($WZ)) {
            goto pVg;
        }
        echo "\xd\12\40\x20\40\40\40\40\x20\40\40\40\x20\40\x20\40\x20\40\74\144\151\166\x20\x63\x6c\x61\x73\x73\x3d\42\x6d\x6f\137\163\141\155\154\x5f\x61\x63\x74\x69\166\x61\164\x65\137\155\x6f\x64\141\x6c\42\76\xd\xa\40\40\40\40\x20\x20\40\x20\x20\40\40\40\x20\x20\40\x20\40\x20\40\x20\74\x64\x69\x76\40\143\x6c\x61\x73\x73\75\42\x6d\157\137\163\x61\155\x6c\137\155\x6f\x64\145\154\137\x63\157\x6e\x74\141\151\x6e\145\x72\x22\76\xd\12\x20\40\40\40\40\40\40\40\40\40\x20\x20\40\x20\x20\40\x20\x20\x20\x20\40\x20\40\x20\74\144\x69\166\x20\143\154\141\163\x73\x3d\42\x6d\157\x5f\163\x61\x6d\154\137\x6d\157\x64\x61\154\137\143\x6f\x6e\164\145\x6e\164\42\x3e\15\12\40\x20\40\40\40\x20\40\x20\x20\40\x20\x20\40\40\x20\40\x20\x20\x20\40\x20\x20\40\x20\x20\40\x20\x20\74\144\x69\166\x20\143\x6c\141\163\x73\75\x22\155\157\137\163\141\155\x6c\x5f\155\157\144\x61\x6c\x5f\143\157\x6e\164\x65\156\164\x5f\150\x65\x61\144\145\162\42\76\15\12\x20\x20\40\40\x20\x20\40\x20\40\40\40\40\x20\x20\x20\40\x20\40\x20\x20\x20\40\40\x20\x20\40\x20\x20\40\x20\x20\40\74\151\x6d\x67\x20\x73\162\143\75\x22" . esc_attr(plugin_dir_url(__FILE__)) . "\x69\155\x61\147\145\163\x2f\155\x69\x6e\151\x6f\162\141\x6e\x67\x65\55\154\x6f\x67\x6f\x2e\x77\x65\142\x70\x22\x20\x77\151\x64\164\x68\x3d\42\65\65\160\170\x22\x20\x68\145\151\147\150\164\75\42\x35\65\160\170\42\x3e\15\12\40\40\40\x20\40\40\x20\x20\40\x20\40\40\40\40\x20\40\x20\40\x20\40\40\40\40\x20\x20\40\40\40\x20\x20\40\x20\74\163\160\x61\x6e\40\x63\x6c\x61\x73\163\x3d\42\155\x6f\137\163\x61\155\154\137\x6d\x6f\x64\x61\154\x5f\x63\x6f\156\x74\145\156\164\x5f\x68\145\141\x64\145\x72\55\x74\x69\x74\x6c\145\x22\x3e\x6d\151\156\x69\x4f\162\141\x6e\x67\145\x20\x53\x53\117\40\x75\163\x69\x6e\x67\x20\x53\101\x4d\114\40\x32\x2e\x30\74\57\163\160\141\x6e\x3e\x3c\x2f\142\162\76\xd\xa\40\40\40\40\x20\40\x20\40\x20\x20\40\40\40\x20\40\40\x20\40\40\40\x20\40\x20\x20\x20\x20\40\40\74\x2f\x64\151\x76\x3e\xd\xa\40\x20\40\40\x20\x20\x20\x20\x20\x20\40\40\40\x20\40\40\x20\40\x20\x20\x20\40\40\x20\x20\x20\x20\x20\x3c\x64\x69\x76\x20\143\x6c\x61\163\x73\x3d\x22\155\x6f\137\163\x61\155\154\137\x6d\157\x64\x61\154\x5f\x63\157\x6e\x74\145\156\x74\x5f\145\x78\x74\x65\156\x73\164\151\157\x6e\163\x22\x3e\x3c\x2f\142\x72\x3e\74\163\160\141\156\x20\143\154\141\163\163\75\42\x6d\157\137\163\x61\x6d\154\137\155\x6f\144\x61\154\137\143\x6f\x6e\164\x65\x6e\x74\137\145\170\164\145\156\163\x74\151\157\x6e\163\x2d\x77\x61\162\x6e\151\x6e\x67\42\76\x3c\163\160\141\156\x20\143\154\x61\x73\163\x3d\42\x6d\157\137\163\141\155\x6c\x5f\x6d\157\x64\x61\154\137\143\x6f\x6e\x74\145\x6e\x74\137\x65\170\164\x65\x6e\163\x74\x69\x6f\156\x73\55\x77\141\162\x6e\151\156\147\62\x22\x3e\127\x61\162\156\151\156\147\72\x20\x50\154\165\147\x69\156\x20\x64\x69\163\141\x62\x6c\x65\x64\40\142\145\143\x61\x75\163\145\x20\x72\x65\x71\x75\x69\x72\145\144\x20\120\x48\120\40\x65\x78\164\x65\156\x73\151\157\156\x73\x20\141\162\x65\x20\155\x69\163\163\x69\156\147\56\74\x62\162\x3e\74\142\x72\76\74\x2f\x73\x70\141\156\76\74\163\x70\x61\156\76\x20\120\154\145\141\x73\x65\x20\145\x6e\x61\142\154\x65\x20\x74\150\x65\x20\146\157\154\x6c\x6f\167\x69\156\147\x20\120\x48\x50\40\x65\170\x74\145\156\x73\x69\157\156\x73\72\x3c\57\x73\160\141\x6e\x3e\x3c\142\x72\x3e";
        echo "\74\x6f\154\x3e";
        foreach ($WZ as $Yu) {
            echo "\74\x6c\151\x3e" . esc_attr($Yu) . "\x3c\57\x6c\151\76";
            e3m:
        }
        Gtb:
        echo "\74\x2f\157\154\x3e\74\x2f\x73\160\x61\x6e\x3e\74\142\x72\x3e\xd\xa\40\40\40\x20\x20\x20\40\40\40\x20\40\x20\x20\40\x20\x20\40\40\40\40\40\x20\x20\x20\x20\40\40\40\x20\40\40\x20\40\x20\40\x20\74\x73\x70\x61\156\76\x50\154\x65\141\163\x65\40\162\145\146\162\x65\163\150\40\164\x68\x65\x20\160\141\147\x65\x20\x61\146\164\x65\x72\x20\x65\x6e\141\x62\x6c\x69\156\x67\x20\x74\150\145\x20\x61\x62\x6f\x76\145\40\x65\x78\164\x65\156\x73\151\157\x6e\x73\56\74\x2f\163\160\x61\156\x3e\15\xa\40\x20\x20\40\40\40\x20\40\x20\40\x20\x20\x20\x20\x20\x20\40\40\x20\40\x20\x20\40\x20\40\40\x20\x20\x3c\57\x64\x69\x76\76";
        echo "\74\x68\x72\76\x3c\x70\x20\163\x74\171\154\x65\x3d\42\x74\145\170\164\x2d\x61\154\151\147\x6e\x3a\40\143\x65\x6e\x74\145\162\42\x3e\x46\157\162\40\141\x6e\x79\x20\146\x75\162\164\150\x65\162\x20\151\x73\x73\165\145\163\x2c\x20\160\x6c\145\141\163\x65\40\x73\x65\x6e\144\x20\x61\156\40\145\x6d\141\x69\x6c\40\164\x6f\x20\x3c\x61\x20\150\x72\145\146\75\x22\155\x61\x69\154\x74\157\72\x20\x73\141\x6d\x6c\163\x75\160\160\x6f\162\164\x40\170\x65\143\165\162\151\146\x79\x2e\x63\157\155\42\76\x3c\151\x3e\163\x61\x6d\154\163\x75\x70\160\x6f\x72\x74\x40\170\145\143\165\x72\151\x66\171\56\x63\157\155\74\57\x69\76\74\x2f\x61\76\x3c\x2f\160\x3e\15\xa\x20\40\40\x20\40\40\x20\x20\x20\40\40\x20\x20\40\40\40\x20\40\x20\x20\40\x20\x20\40\x3c\57\144\x69\166\76\15\12\x20\x20\x20\x20\x20\x20\40\x20\x20\40\x20\40\x20\40\40\40\40\40\40\40\74\x2f\x64\x69\166\76\15\xa\x20\x20\x20\x20\x20\40\x20\x20\x20\40\40\x20\40\x20\40\40\74\57\144\x69\166\x3e";
        exit;
        pVg:
        oBu:
    }
    public static function mo_saml_upload_idp_metadata_validations($rK)
    {
        foreach ($rK as $XE) {
            $sy = array();
            if (!empty($XE->getEntityID())) {
                goto ilR;
            }
            $sy[] = "\105\x6e\x74\151\164\171\x49\104";
            ilR:
            if (!empty($XE->getLoginDetails())) {
                goto g4p;
            }
            $sy[] = "\x4c\x6f\147\x69\x6e\x20\x55\x52\114";
            g4p:
            if (!empty($XE->getSigningCertificate())) {
                goto Ffi;
            }
            $sy[] = "\x53\151\147\x6e\151\x6e\x67\40\x43\145\x72\164\x69\x66\x69\143\141\164\x65";
            Ffi:
            if (empty($sy)) {
                goto ozs;
            }
            $VS = "\x54\x68\x65\x20\111\x64\145\x6e\164\151\x74\x79\40\x50\162\157\166\x69\144\x65\x72\47\x73\40\155\145\x74\141\144\x61\164\x61\40\x69\x73\x20\x6d\x69\x73\x73\x69\x6e\147\40\x74\150\x65\x20\146\157\154\154\x6f\x77\151\156\147\40\162\x65\x71\x75\151\x72\x65\144\x20\146\x69\x65\154\x64\x73\56\x20\120\154\x65\141\x73\145\40\x63\150\145\x63\x6b\40\171\157\165\x72\40\x49\x64\120\x20\143\x6f\156\x66\151\147\165\162\x61\x74\151\157\156\x2e";
            foreach ($sy as $A8) {
                $VS .= "\74\x6c\x69\x3e" . $A8 . "\x3c\x2f\154\x69\76";
                Bea:
            }
            NFJ:
            update_option("\x6d\x6f\x5f\x73\141\x6d\x6c\137\155\145\x73\163\x61\147\145", $VS);
            self::mo_saml_show_error_message();
            return true;
            ozs:
            vpk:
        }
        y5X:
        return false;
    }
    public static function mo_saml_append_params_redirect_binding($tx)
    {
        $e_ = '';
        foreach ($tx as $R2 => $EB) {
            if (!("\x6f\x70\164\151\157\x6e" !== $R2)) {
                goto aW1;
            }
            $EB = self::mo_saml_is_array($EB);
            $e_ .= "\46" . esc_attr($R2) . "\75" . urlencode($EB);
            aW1:
            VNG:
        }
        D_i:
        return $e_;
    }
    public static function mo_saml_sanitize_associative_array($p7)
    {
        $hO = array();
        foreach ($p7 as $R2 => $EB) {
            if (is_array($EB)) {
                goto hr9;
            }
            $hO[$R2] = sanitize_text_field($EB);
            goto nTn;
            hr9:
            $hO[$R2] = self::mo_saml_sanitize_associative_array($EB);
            nTn:
            BAj:
        }
        hkM:
        return $hO;
    }
    public static function mo_saml_check_idp_display_name($dv, $p6, $BB, $pD)
    {
        if (!(!empty($p6[$pD]) && $BB !== $p6[$pD] || !empty($dv[$pD]) && $BB !== $dv[$pD]["\x69\144\x70\x5f\x6e\141\x6d\x65"])) {
            goto SC4;
        }
        return true;
        SC4:
        return false;
    }
    public static function mo_saml_generate_idp_id($dv = array())
    {
        if (!empty($dv)) {
            goto Zwu;
        }
        $dv = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\155\154\x5f\x69\144\145\x6e\164\151\164\171\x5f\160\162\157\x76\151\x64\x65\162\163", true);
        Zwu:
        $cw = "\141\142\143\x64\x65\146\147\150\x69\152\153\x6c\x6d\156\x6f\x70\161\x72\x73\164\x75\166\x77\x78\171\172";
        $ql = "\x30\x31\x32\63\64\x35\x36\x37\70\71";
        $rT = str_shuffle($cw);
        $g_ = str_shuffle($ql);
        $c7 = substr($rT, 0, 3);
        $yD = substr($g_, 0, 3);
        $FU = $c7 . $yD;
        if (!(isset($dv[$FU]) && !empty($dv[$FU]))) {
            goto bjz;
        }
        return self::mo_saml_generate_idp_id($dv);
        bjz:
        return $FU;
    }
    public static function mo_saml_get_redirect_url($Rn, $BB, $XP = '')
    {
        $HD = EnvironmentHelper::getOptionForSelectedEnvironment(mo_options_enum_sso_login::Relay_states, true, $XP);
        $aO = !empty($HD["\154\x6f\147\x69\x6e\137\x72\145\x6c\141\x79\x5f\163\x74\x61\x74\x65"]) ? $HD["\154\x6f\147\x69\x6e\137\162\x65\x6c\141\171\137\x73\x74\x61\164\x65"] : array();
        $hK = EnvironmentHelper::getOptionForSelectedEnvironment("\155\x6f\137\163\141\155\154\137\163\160\137\142\x61\x73\x65\x5f\x75\162\x6c", false, $XP);
        if (!empty($hK)) {
            goto bEt;
        }
        $hK = home_url();
        bEt:
        $l6 = '';
        $qa = '';
        if (!(!empty($aO["\104\x45\106\x41\x55\114\x54"]) || !empty($aO[$BB]))) {
            goto tCy;
        }
        $qa = !empty($aO[$BB]) ? $aO[$BB] : $aO["\x44\x45\106\101\x55\114\x54"];
        tCy:
        if (!empty($qa)) {
            goto t1M;
        }
        if (!empty($Rn)) {
            goto P72;
        }
        $l6 = $hK;
        goto cmH;
        t1M:
        $l6 = $qa;
        goto cmH;
        P72:
        if (!filter_var($Rn, FILTER_VALIDATE_URL)) {
            goto Y3s;
        }
        if (parse_url(home_url(), PHP_URL_HOST) === parse_url($Rn, PHP_URL_HOST)) {
            goto tL1;
        }
        $l6 = $hK;
        goto nm9;
        Y3s:
        $l6 = $Rn;
        goto nm9;
        tL1:
        $l6 = $Rn;
        nm9:
        cmH:
        return $l6;
    }
    public static function mo_saml_update_selected_idp($rK, $sBg = false)
    {
        $Ia = get_option("\x6d\x6f\137\x73\x61\x6d\154\137\x6e\157\x74\x69\x63\x65\x5f\164\x6f\x5f\x64\x69\x73\x70\154\x61\x79") ? get_option("\155\x6f\x5f\163\x61\155\154\137\x6e\x6f\x74\x69\143\x65\x5f\x74\157\137\x64\x69\163\x70\x6c\141\x79") : array();
        if (!$sBg) {
            goto zPA;
        }
        $Ia = array();
        zPA:
        foreach ($rK as $XE) {
            foreach (Mo_Saml_Notice_Details::$product_details as $fF5 => $Shx) {
                if (!(false !== strpos($XE["\163\x73\157\x5f\x75\162\x6c"], $fF5))) {
                    goto Mvg;
                }
                if (array_key_exists($fF5, $Ia)) {
                    goto lKt;
                }
                $Ia[$fF5] = true;
                lKt:
                Mvg:
                aoZ:
            }
            GAe:
            Lyn:
        }
        PER:
        update_option("\x6d\x6f\137\x73\141\x6d\x6c\x5f\156\x6f\x74\x69\143\145\x5f\164\x6f\x5f\x64\x69\x73\160\x6c\x61\171", $Ia);
    }
    public static function mo_saml_delete_admin_notice($jba)
    {
        $Ia = get_option("\x6d\x6f\137\x73\x61\155\x6c\137\156\x6f\x74\x69\x63\145\x5f\164\157\137\144\151\163\x70\x6c\141\171") ? get_option("\x6d\157\x5f\x73\141\155\154\x5f\156\x6f\x74\151\143\x65\137\164\x6f\x5f\144\151\x73\160\x6c\141\171") : array();
        $kq = EnvironmentHelper::getOptionForSelectedEnvironment("\x73\141\155\154\x5f\151\144\x65\x6e\x74\151\x74\171\137\160\162\x6f\x76\x69\144\x65\162\163");
        $o2M = 0;
        foreach ($kq as $BB => $gu) {
            if (!(false !== strpos($jba, $gu["\163\163\157\137\165\x72\x6c"]))) {
                goto nmW;
            }
            ++$o2M;
            nmW:
            AY0:
        }
        iQt:
        foreach (Mo_Saml_Notice_Details::$product_details as $fF5 => $Shx) {
            if (!(false !== strpos($jba, $fF5) && 1 === $o2M)) {
                goto F9P;
            }
            $Ia[$fF5] = false;
            F9P:
            Y7H:
        }
        t4l:
        update_option("\155\157\x5f\163\x61\155\154\137\156\x6f\164\x69\143\x65\x5f\164\x6f\137\144\x69\163\160\154\141\x79", $Ia);
    }
    public static function mo_saml_get_public_page_url($CP)
    {
        $hY = EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\x5f\163\141\x6d\154\137\x69\x64\160\x5f\x6c\151\163\164\137\x75\x72\154", false, $CP);
        if (!empty($hY)) {
            goto yK0;
        }
        $hY = !empty(EnvironmentHelper::getOptionForSelectedEnvironment("\155\157\x5f\x73\141\155\x6c\137\x73\160\x5f\142\x61\163\x65\137\x75\x72\x6c", false, $CP)) ? EnvironmentHelper::getOptionForSelectedEnvironment("\x6d\157\137\x73\141\x6d\154\137\x73\x70\x5f\142\141\163\x65\137\x75\x72\154", false, $CP) . "\x2f" : home_url() . "\x2f";
        yK0:
        return $hY;
    }
    public static function mo_saml_get_enabled_idps($rK, $kd, $cA = 0)
    {
        $tHO = count($kd);
        foreach ($rK as $BB => $XE) {
            if (!$rK[$BB]["\x65\x6e\141\x62\154\x65\x5f\x69\x64\x70"]) {
                goto BoG;
            }
            ++$cA;
            if (!in_array($BB, $kd)) {
                goto ooE;
            }
            --$tHO;
            ooE:
            BoG:
            aF2:
        }
        LF7:
        return $cA + $tHO;
    }
    public static function mo_saml_check_idp_limit($VD5)
    {
        $MFV = self::mo_saml_fetch_idps_count_in_license();
        if (!($VD5 > $MFV)) {
            goto Xf7;
        }
        update_option("\155\x6f\x5f\x73\x61\x6d\x6c\137\155\145\163\x73\x61\147\145", "\x59\x6f\165\x20\x68\141\166\x65\40\105\x58\103\105\x45\x44\x45\104\x20\171\x6f\165\x72\40\x49\x44\x50\40\x6c\151\155\151\x74\x2e\40\131\x6f\165\40\x63\141\x6e\x20\165\x70\147\162\x61\x64\x65\40\171\157\x75\162\40\x6c\151\x63\x65\x6e\x73\145\40\x6f\162\x20\x64\145\154\x65\164\x65\x2f\144\151\x73\x61\x62\154\145\x20\165\x6e\x75\x73\145\x64\x20\111\104\120\56");
        self::mo_saml_show_error_message();
        return false;
        Xf7:
        return true;
    }
    public static function mo_saml_screen_options()
    {
        $cH = "\x70\x65\162\137\160\141\147\145";
        $MS = array("\154\x61\142\145\x6c" => "\111\x74\145\155\163\40\x70\x65\x72\40\x70\141\x67\145", "\144\145\146\x61\x75\x6c\x74" => 5, "\157\x70\x74\151\157\156" => "\151\x74\145\155\163\137\160\145\162\137\160\141\147\145");
        add_screen_option($cH, $MS);
        new Mo_Saml_IDP_List();
    }
    public static function mo_saml_delete_idp_configuration($RDm, $rJ4, $p6)
    {
        $s6 = new EnvironmentDao();
        foreach ($RDm as $BB) {
            self::mo_saml_delete_admin_notice($rJ4[$BB]["\x73\x73\x6f\x5f\165\162\154"]);
            if (empty($rJ4[$BB]["\151\x64\x70\137\x64\151\163\160\x6c\141\x79\x5f\156\x61\155\x65"])) {
                goto pfy;
            }
            $rm = $rJ4[$BB]["\x69\x64\160\137\144\x69\x73\160\x6c\141\x79\137\156\x61\x6d\x65"];
            if (empty($p6[$rm])) {
                goto I6H;
            }
            unset($p6[$rm]);
            I6H:
            pfy:
            unset($rJ4[$BB]);
            $rJ4 = array_filter($rJ4, "\x66\x69\x6c\164\x65\162\x5f\145\155\x70\164\x79\x5f\166\x61\154\165\x65\163");
            $Tyo = array_filter($p6, "\146\151\x6c\x74\145\x72\x5f\x65\155\160\164\x79\137\166\x61\x6c\x75\x65\163");
            self::mo_saml_delete_additional_settings("\163\x61\155\154\x5f\x69\x64\160\137\162\157\154\145\137\x6d\141\160\160\151\x6e\x67", $BB, $s6);
            self::mo_saml_delete_additional_settings("\x6d\x6f\137\x73\141\155\x6c\x5f\143\x6f\156\146\x69\x67\x75\162\x65\144\137\162\x6f\154\145\137\166\x61\x6c\x75\145\163", $BB, $s6);
            self::mo_saml_delete_additional_settings("\x6d\157\x5f\163\x61\155\154\x5f\x72\x6f\154\145\x5f\x6d\141\160\160\151\x6e\x67\x5f\143\x6f\156\x66\151\x67\165\162\141\x74\151\157\156\163", $BB, $s6);
            self::mo_saml_delete_additional_settings("\x73\x61\155\x6c\x5f\x69\144\160\x5f\x61\164\164\x72\151\142\x75\x74\145\137\155\x61\160\160\151\x6e\x67", $BB, $s6);
            self::mo_saml_delete_additional_settings("\x6d\157\137\163\141\155\154\137\x61\x74\164\x72\151\142\165\x74\145\137\x6d\141\x70\x70\151\156\147", $BB, $s6);
            self::mo_saml_delete_additional_settings("\x6d\x6f\x5f\163\x61\x6d\x6c\x5f\x63\x75\163\164\x6f\x6d\x5f\x61\164\164\162\163\x5f\155\141\160\160\x69\156\x67", $BB, $s6);
            self::mo_saml_delete_additional_settings("\163\x61\x6d\154\137\151\144\x70\x5f\144\x6f\155\x61\151\x6e\137\x6d\x61\160\160\151\156\x67", $BB, $s6);
            self::mo_saml_delete_additional_settings("\x73\x61\155\x6c\x5f\x73\163\x6f\137\142\x75\164\x74\157\156\x5f\x69\144\160", $BB, $s6);
            self::mo_saml_delete_additional_settings("\163\141\155\x6c\x5f\144\157\x6d\x61\151\x6e\x5f\x72\x65\163\x74\x72\151\143\x74\x69\157\x6e", $BB, $s6);
            self::mo_saml_delete_additional_settings("\x6d\157\137\163\141\x6d\154\137\x61\164\164\x72\x5f\162\157\x6c\145\137\x61\144\x76\x61\x6e\143\x65\x64\137\x73\x65\164\x74\151\x6e\147\163", $BB, $s6);
            self::mo_saml_delete_additional_settings("\155\x6f\x5f\x73\141\x6d\154\x5f\x74\145\x73\164\137\x63\x6f\156\x66\x69\147\137\x61\x74\164\x72\x73", $BB, $s6);
            self::mo_saml_delete_additional_settings("\163\x61\155\154\x5f\163\145\154\x65\143\x74\x5f\151\x64\x70\x5f\x6e\141\x6d\x65", $BB, $s6);
            $HD = EnvironmentHelper::getOptionForSelectedEnvironment(mo_options_enum_sso_login::Relay_states, true);
            if (empty($HD["\x6c\x6f\147\151\x6e\x5f\162\145\154\141\x79\137\163\164\141\x74\145"][$BB])) {
                goto pI0;
            }
            unset($HD["\154\157\x67\x69\x6e\x5f\x72\x65\x6c\x61\x79\137\x73\164\141\x74\x65"][$BB]);
            $HD["\x6c\157\x67\x69\156\137\x72\145\x6c\141\171\x5f\163\164\141\164\145"] = array_filter($HD["\x6c\x6f\x67\151\156\x5f\x72\x65\154\x61\171\137\x73\164\x61\164\145"], "\x66\x69\x6c\164\145\162\137\x65\x6d\160\x74\x79\137\x76\x61\x6c\x75\145\163");
            pI0:
            if (empty($HD["\x6c\157\x67\157\165\x74\137\162\x65\x6c\141\x79\x5f\163\x74\x61\x74\145"][$BB])) {
                goto Qy3;
            }
            unset($HD["\154\157\x67\x6f\x75\x74\x5f\x72\x65\x6c\141\171\137\x73\x74\141\164\145"][$BB]);
            $HD["\154\x6f\x67\x6f\x75\x74\137\162\x65\154\x61\x79\x5f\x73\x74\141\x74\x65"] = array_filter($HD["\x6c\x6f\147\157\165\164\x5f\162\145\x6c\141\171\x5f\x73\x74\x61\164\145"], "\146\151\x6c\164\145\162\137\x65\155\160\x74\171\137\166\141\x6c\165\145\163");
            Qy3:
            $s6->mo_save_environment_settings(mo_options_enum_sso_login::Relay_states, $HD);
            jzW:
        }
        dBk:
        $s6->mo_save_environment_settings("\x73\141\x6d\x6c\x5f\151\x64\145\x6e\x74\x69\164\x79\x5f\160\x72\157\x76\x69\x64\145\162\x73", $rJ4);
        $s6->mo_save_environment_settings("\x6d\157\x5f\x73\141\x6d\x6c\137\151\x64\160\x5f\156\141\x6d\145\x5f\151\x64\137\x6d\141\160", $p6);
        if (!empty($rJ4)) {
            goto dhr;
        }
        $s6->mo_save_environment_settings("\155\157\x5f\163\x61\x6d\154\137\145\156\x61\x62\x6c\x65\x5f\x64\x6f\155\141\x69\x6e\137\155\141\x70\x70\151\x6e\147", "\146\x61\154\163\145");
        dhr:
        $zzl = count($RDm) > 1 ? "\163" : '';
        update_option("\155\157\x5f\163\141\x6d\x6c\137\155\x65\163\163\x61\x67\145", "\74\145\155\76\x49\104\120" . $zzl . "\x3c\57\x65\x6d\x3e\x20\144\x65\154\x65\164\x65\144\40\x73\165\x63\x63\145\x73\x73\146\165\x6c\x6c\171\56");
        self::mo_saml_show_success_message();
    }
    public static function mo_saml_delete_additional_settings($cH, $BB, $s6)
    {
        $dMg = EnvironmentHelper::getOptionForSelectedEnvironment($cH, true);
        if (!(empty($dMg) || is_array($dMg) && array_key_exists($BB, $dMg))) {
            goto KQX;
        }
        $dMg = empty($uIr) ? $dMg : $dMg[$uIr];
        unset($dMg[$BB]);
        $dMg = array_filter($dMg, "\146\151\154\x74\145\162\x5f\145\155\x70\x74\x79\137\166\141\x6c\165\145\x73");
        KQX:
        $s6->mo_save_environment_settings($cH, $dMg);
    }
    public static function mo_saml_save_custom_screen_options($d6, $cH, $EB)
    {
        if (!("\151\164\145\x6d\x73\x5f\x70\x65\162\x5f\160\141\147\145" == $cH)) {
            goto VNV;
        }
        return $EB;
        VNV:
        return $d6;
    }
    public static function mo_saml_disable_metadata_sync($BB = false)
    {
        $s6 = new EnvironmentDao();
        $WT = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\155\154\x5f\x6d\145\x74\x61\x64\x61\x74\141\137\165\162\154\137\146\x6f\162\137\x73\x79\156\143", true, EnvironmentHelper::getCurrentEnvironment());
        if ($BB) {
            goto dwu;
        }
        $BB = $_POST["\163\141\155\x6c\137\145\144\151\164\x5f\x75\x70\x6c\157\141\144\x5f\155\x65\164\x61\x64\x61\164\x61\x5f\156\141\x6d\145"];
        dwu:
        if (!isset($WT[$BB])) {
            goto pIk;
        }
        unset($WT[$BB]);
        pIk:
        $s6->mo_save_environment_settings("\x73\x61\x6d\x6c\x5f\x6d\145\164\x61\x64\x61\164\141\137\x75\x72\x6c\137\146\157\162\x5f\163\171\156\x63", $WT);
        wp_unschedule_event(wp_next_scheduled("\155\145\x74\x61\x64\x61\164\141\x5f\163\x79\x6e\x63\137\x63\x72\157\x6e\137\141\x63\164\151\x6f\156", array($BB)), "\x6d\145\164\141\x64\x61\x74\x61\137\163\171\x6e\143\137\143\162\x6f\156\137\x61\143\x74\x69\x6f\156", array($BB));
    }
    public static function mo_saml_disable_metadata_sync_for_all_idps()
    {
        $WT = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\x6d\154\137\x6d\x65\x74\x61\144\x61\x74\x61\137\x75\x72\x6c\x5f\x66\157\x72\137\x73\171\156\143", true, EnvironmentHelper::getCurrentEnvironment());
        if (!(!empty($WT) && is_array($WT))) {
            goto jvR;
        }
        foreach ($WT as $R2 => $EB) {
            wp_unschedule_event(wp_next_scheduled("\x6d\145\x74\141\144\x61\164\x61\137\x73\x79\156\x63\x5f\143\x72\157\156\137\141\x63\x74\151\157\156", array($R2)), "\x6d\x65\164\141\x64\141\164\x61\137\x73\x79\x6e\x63\x5f\143\162\157\x6e\137\x61\x63\164\151\x6f\156", array($R2));
            phS:
        }
        eOU:
        jvR:
    }
    public static function mo_saml_enable_metadata_sync_for_all_idps()
    {
        $WT = EnvironmentHelper::getOptionForSelectedEnvironment("\163\141\155\154\137\155\145\164\x61\144\141\x74\x61\137\x75\x72\x6c\137\x66\157\x72\137\x73\x79\x6e\x63", true, EnvironmentHelper::getCurrentEnvironment());
        if (!(!empty($WT) && is_array($WT))) {
            goto sLO;
        }
        foreach ($WT as $R2 => $EB) {
            wp_schedule_event(time(), $EB["\163\x79\156\x63\x5f\151\156\x74\x65\162\166\x61\154"], "\x6d\145\164\141\144\141\164\141\137\163\x79\x6e\143\x5f\x63\162\x6f\x6e\137\x61\x63\x74\151\157\x6e", array($R2));
            PIK:
        }
        rYd:
        sLO:
    }
    public static function mo_saml_check_trailing_slash($Oz)
    {
        if (!(!empty($Oz) && substr($Oz, -1) !== "\57")) {
            goto QE1;
        }
        $Oz .= "\x2f";
        QE1:
        return $Oz;
    }
    public static function mo_saml_sanitize_saml_attrs($r0)
    {
        if (!(!empty($r0) && $r0["\x73\141\x6e\151\x74\151\172\x65\137\x66\x75\162\x74\150\145\x72"])) {
            goto X5q;
        }
        $r0 = map_deep($r0, "\163\141\156\151\164\x69\x7a\x65\x5f\x74\x65\x78\x74\137\x66\151\x65\x6c\144");
        X5q:
        unset($r0["\163\x61\156\x69\164\151\172\x65\137\x66\165\162\164\150\x65\162"]);
        return $r0;
    }
    public static function mo_saml_is_array($Ira)
    {
        return is_array($Ira) ? $Ira[0] : $Ira;
    }
}
