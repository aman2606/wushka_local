<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



namespace RobRichards\XMLSecLibs;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use Exception;
use RobRichards\XMLSecLibs\Utils\XPath;
class XMLSecurityDSig
{
    const XMLDSIGNS = "\150\164\x74\x70\72\57\x2f\167\x77\x77\56\x77\63\x2e\157\x72\147\57\x32\60\60\60\57\60\x39\x2f\170\x6d\x6c\144\163\x69\x67\43";
    const SHA1 = "\x68\x74\164\x70\x3a\x2f\57\x77\x77\x77\x2e\x77\x33\x2e\157\162\147\57\x32\60\x30\60\x2f\60\x39\57\x78\x6d\x6c\x64\x73\x69\x67\x23\163\x68\141\x31";
    const SHA256 = "\x68\164\164\x70\72\x2f\x2f\x77\x77\167\56\x77\x33\x2e\157\x72\147\57\x32\60\x30\61\57\x30\64\x2f\170\155\x6c\x65\x6e\x63\x23\x73\150\x61\x32\65\66";
    const SHA384 = "\x68\164\164\160\72\x2f\57\x77\x77\167\x2e\x77\x33\x2e\x6f\162\x67\x2f\62\x30\x30\61\x2f\60\64\x2f\170\155\x6c\x64\x73\151\x67\x2d\155\157\162\x65\x23\x73\x68\x61\x33\x38\x34";
    const SHA512 = "\150\164\164\x70\72\57\x2f\x77\167\x77\56\x77\x33\x2e\x6f\162\x67\57\x32\x30\60\x31\57\60\x34\57\x78\155\154\x65\156\x63\x23\163\150\141\65\61\62";
    const RIPEMD160 = "\150\164\x74\x70\72\57\x2f\167\167\x77\x2e\x77\63\56\157\x72\x67\57\62\60\x30\61\x2f\x30\x34\x2f\x78\155\154\145\156\x63\x23\162\151\x70\x65\x6d\x64\61\66\60";
    const C14N = "\150\x74\x74\x70\72\57\x2f\167\x77\x77\56\x77\x33\x2e\x6f\162\x67\57\x54\x52\x2f\x32\x30\60\61\57\x52\105\x43\55\170\x6d\154\55\x63\61\64\156\x2d\x32\x30\60\x31\x30\63\61\65";
    const C14N_COMMENTS = "\150\x74\x74\160\72\x2f\x2f\167\x77\x77\56\x77\63\x2e\x6f\x72\147\x2f\124\122\57\x32\x30\x30\x31\57\122\105\103\55\x78\155\154\55\143\61\x34\156\x2d\62\60\60\x31\60\63\x31\65\43\x57\x69\164\x68\x43\x6f\155\155\x65\x6e\x74\x73";
    const EXC_C14N = "\x68\164\164\x70\72\x2f\57\167\167\167\x2e\x77\63\x2e\x6f\162\x67\x2f\x32\x30\60\x31\57\x31\60\57\x78\x6d\x6c\55\145\x78\x63\55\x63\61\64\x6e\x23";
    const EXC_C14N_COMMENTS = "\150\164\164\160\72\57\57\167\x77\x77\x2e\167\x33\56\157\x72\x67\x2f\x32\60\60\x31\x2f\61\x30\57\x78\155\154\55\x65\170\x63\55\143\x31\64\156\43\x57\x69\x74\x68\x43\x6f\155\155\x65\x6e\x74\163";
    const template = "\74\x64\x73\x3a\x53\151\147\156\x61\x74\165\x72\145\40\170\x6d\154\x6e\x73\x3a\x64\x73\x3d\42\x68\x74\x74\160\x3a\x2f\57\x77\x77\167\56\x77\x33\x2e\x6f\x72\147\x2f\62\x30\60\60\x2f\x30\71\x2f\170\x6d\154\144\x73\151\147\43\42\76\15\12\x20\x20\74\144\x73\72\123\x69\147\156\145\144\x49\156\x66\157\76\xd\12\x20\40\x20\40\x3c\144\163\x3a\123\151\x67\x6e\x61\164\x75\x72\x65\115\145\x74\x68\157\x64\40\57\x3e\15\12\x20\40\74\57\144\x73\72\x53\x69\147\x6e\x65\144\x49\156\x66\157\x3e\15\12\x3c\x2f\144\x73\x3a\x53\x69\x67\156\x61\x74\x75\x72\x65\x3e";
    const BASE_TEMPLATE = "\x3c\x53\x69\147\156\141\164\165\x72\x65\x20\x78\x6d\x6c\156\x73\75\x22\150\164\x74\160\72\57\57\167\x77\167\56\x77\x33\x2e\x6f\x72\147\x2f\x32\x30\60\x30\x2f\x30\71\x2f\170\x6d\154\144\x73\x69\147\43\x22\x3e\15\12\40\40\74\x53\151\147\156\x65\x64\x49\156\146\157\76\xd\12\x20\x20\40\x20\x3c\x53\151\147\156\141\164\x75\x72\145\x4d\x65\x74\150\157\x64\x20\57\x3e\15\12\40\x20\x3c\57\123\x69\x67\156\x65\144\111\x6e\x66\157\76\15\xa\x3c\57\x53\151\147\156\x61\164\165\x72\x65\x3e";
    public $sigNode = null;
    public $idKeys = array();
    public $idNS = array();
    private $signedInfo = null;
    private $xPathCtx = null;
    private $canonicalMethod = null;
    private $prefix = '';
    private $searchpfx = "\x73\x65\x63\144\163\x69\147";
    private $validatedNodes = null;
    public function __construct($a7 = "\x64\163")
    {
        $hm = self::BASE_TEMPLATE;
        if (empty($a7)) {
            goto eg;
        }
        $this->prefix = $a7 . "\x3a";
        $Xl = array("\x3c\x53", "\74\57\x53", "\x78\x6d\154\x6e\x73\75");
        $Hl = array("\74{$a7}\72\x53", "\74\x2f{$a7}\72\x53", "\x78\155\154\156\x73\72{$a7}\x3d");
        $hm = str_replace($Xl, $Hl, $hm);
        eg:
        $LX = new DOMDocument();
        $LX->loadXML($hm);
        $this->sigNode = $LX->documentElement;
    }
    private function resetXPathObj()
    {
        $this->xPathCtx = null;
    }
    private function getXPathObj()
    {
        if (!(empty($this->xPathCtx) && !empty($this->sigNode))) {
            goto jp;
        }
        $zk = new DOMXPath($this->sigNode->ownerDocument);
        $zk->registerNamespace("\x73\145\x63\144\163\x69\x67", self::XMLDSIGNS);
        $this->xPathCtx = $zk;
        jp:
        return $this->xPathCtx;
    }
    public static function generateGUID($a7 = "\160\x66\x78")
    {
        $Fo = md5(uniqid(mt_rand(), true));
        $ih = $a7 . substr($Fo, 0, 8) . "\55" . substr($Fo, 8, 4) . "\55" . substr($Fo, 12, 4) . "\55" . substr($Fo, 16, 4) . "\55" . substr($Fo, 20, 12);
        return $ih;
    }
    public static function generate_GUID($a7 = "\160\146\x78")
    {
        return self::generateGUID($a7);
    }
    public function locateSignature($Q5, $FS = 0)
    {
        if ($Q5 instanceof DOMDocument) {
            goto RB;
        }
        $hT = $Q5->ownerDocument;
        goto Kh;
        RB:
        $hT = $Q5;
        Kh:
        if (!$hT) {
            goto Hs;
        }
        $zk = new DOMXPath($hT);
        $zk->registerNamespace("\163\145\x63\x64\163\151\x67", self::XMLDSIGNS);
        $yZ = "\x2e\x2f\x2f\163\x65\x63\x64\x73\x69\x67\72\x53\151\147\156\x61\164\165\x72\145";
        $KI = $zk->query($yZ, $Q5);
        $this->sigNode = $KI->item($FS);
        $yZ = "\x2e\x2f\x73\x65\x63\x64\163\151\x67\x3a\123\151\x67\x6e\x65\144\x49\156\x66\x6f";
        $KI = $zk->query($yZ, $this->sigNode);
        if (!($KI->length > 1)) {
            goto oN;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x49\x6e\x76\x61\x6c\151\x64\x20\x73\164\162\x75\143\164\165\162\x65\40\55\x20\124\x6f\x6f\x20\x6d\x61\x6e\x79\x20\123\x69\x67\x6e\145\144\x49\x6e\146\157\40\145\x6c\x65\155\145\x6e\164\163\x20\146\157\x75\x6e\144");
        oN:
        return $this->sigNode;
        Hs:
        return null;
    }
    public function createNewSignNode($or, $EB = null)
    {
        $hT = $this->sigNode->ownerDocument;
        if (!is_null($EB)) {
            goto l0;
        }
        $Fa = $hT->createElementNS(self::XMLDSIGNS, $this->prefix . $or);
        goto LZ;
        l0:
        $Fa = $hT->createElementNS(self::XMLDSIGNS, $this->prefix . $or, $EB);
        LZ:
        return $Fa;
    }
    public function setCanonicalMethod($lO)
    {
        switch ($lO) {
            case "\x68\164\x74\160\72\x2f\x2f\x77\x77\167\x2e\x77\63\56\157\162\x67\x2f\x54\122\x2f\62\x30\60\x31\57\122\105\x43\55\x78\x6d\154\55\x63\61\x34\156\x2d\62\60\x30\61\60\x33\61\x35":
            case "\150\x74\164\x70\x3a\57\x2f\167\x77\167\x2e\167\63\56\157\162\147\x2f\x54\x52\57\62\60\60\61\57\x52\105\x43\55\x78\x6d\x6c\55\x63\61\x34\156\x2d\62\x30\x30\x31\60\63\x31\x35\43\127\151\x74\150\103\x6f\155\x6d\x65\x6e\x74\x73":
            case "\150\x74\164\x70\72\57\x2f\167\167\x77\x2e\x77\x33\x2e\x6f\x72\147\x2f\x32\60\60\61\57\61\x30\x2f\170\x6d\154\x2d\x65\x78\143\x2d\143\x31\x34\156\43":
            case "\x68\x74\x74\160\72\x2f\57\167\167\167\x2e\167\x33\56\x6f\162\x67\57\x32\60\x30\x31\57\x31\60\x2f\x78\155\154\x2d\x65\x78\143\x2d\143\x31\x34\x6e\43\127\x69\x74\150\x43\x6f\x6d\x6d\x65\x6e\164\163":
                $this->canonicalMethod = $lO;
                goto DH;
            default:
                throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x49\x6e\166\141\x6c\x69\144\x20\x43\x61\156\x6f\x6e\x69\x63\x61\154\x20\115\x65\164\150\x6f\144");
        }
        WF:
        DH:
        if (!($zk = $this->getXPathObj())) {
            goto eP;
        }
        $yZ = "\56\x2f" . $this->searchpfx . "\x3a\123\151\x67\156\145\144\x49\x6e\x66\x6f";
        $KI = $zk->query($yZ, $this->sigNode);
        if (!($w0 = $KI->item(0))) {
            goto W3;
        }
        $yZ = "\56\57" . $this->searchpfx . "\103\x61\x6e\x6f\x6e\x69\143\x61\x6c\x69\172\x61\164\151\x6f\x6e\115\145\x74\x68\157\144";
        $KI = $zk->query($yZ, $w0);
        if ($eS = $KI->item(0)) {
            goto NS;
        }
        $eS = $this->createNewSignNode("\x43\141\x6e\x6f\x6e\x69\143\141\x6c\x69\172\141\164\151\157\x6e\x4d\x65\x74\150\x6f\144");
        $w0->insertBefore($eS, $w0->firstChild);
        NS:
        $eS->setAttribute("\101\x6c\147\157\162\x69\x74\150\x6d", $this->canonicalMethod);
        W3:
        eP:
    }
    private function canonicalizeData($Fa, $ft, $RU = null, $t0 = null)
    {
        $pl = false;
        $SR = false;
        switch ($ft) {
            case "\x68\164\164\160\x3a\57\x2f\167\167\x77\56\167\x33\56\x6f\162\x67\57\124\x52\57\x32\x30\x30\61\x2f\122\x45\x43\55\x78\155\x6c\x2d\x63\61\x34\156\x2d\62\x30\60\x31\60\x33\x31\x35":
                $pl = false;
                $SR = false;
                goto mo;
            case "\150\x74\164\x70\72\x2f\x2f\x77\167\x77\x2e\x77\x33\x2e\x6f\x72\147\57\x54\122\x2f\x32\x30\x30\x31\57\122\x45\103\55\170\155\154\55\x63\61\64\156\55\62\x30\x30\x31\x30\x33\x31\65\43\127\151\164\150\103\x6f\155\155\x65\156\x74\x73":
                $SR = true;
                goto mo;
            case "\x68\x74\164\160\x3a\57\57\167\x77\167\56\x77\63\56\x6f\x72\147\57\x32\60\60\61\57\61\60\57\x78\x6d\154\x2d\145\170\143\x2d\143\61\64\x6e\x23":
                $pl = true;
                goto mo;
            case "\x68\x74\164\160\72\57\x2f\x77\x77\167\x2e\x77\x33\x2e\157\x72\x67\57\x32\x30\x30\61\x2f\61\x30\57\170\155\x6c\x2d\145\x78\143\55\x63\61\x34\x6e\43\x57\151\164\150\103\x6f\x6d\155\145\x6e\164\x73":
                $pl = true;
                $SR = true;
                goto mo;
        }
        XE:
        mo:
        if (!(is_null($RU) && $Fa instanceof DOMNode && $Fa->ownerDocument !== null && $Fa->isSameNode($Fa->ownerDocument->documentElement))) {
            goto CS;
        }
        $ss = $Fa;
        Xe:
        if (!($oh = $ss->previousSibling)) {
            goto NC;
        }
        if (!($oh->nodeType == XML_PI_NODE || $oh->nodeType == XML_COMMENT_NODE && $SR)) {
            goto Gs;
        }
        goto NC;
        Gs:
        $ss = $oh;
        goto Xe;
        NC:
        if (!($oh == null)) {
            goto Qn;
        }
        $Fa = $Fa->ownerDocument;
        Qn:
        CS:
        return $Fa->C14N($pl, $SR, $RU, $t0);
    }
    public function canonicalizeSignedInfo()
    {
        $hT = $this->sigNode->ownerDocument;
        $ft = null;
        if (!$hT) {
            goto yo;
        }
        $zk = $this->getXPathObj();
        $yZ = "\56\x2f\x73\x65\143\144\163\151\147\72\123\x69\x67\156\x65\144\x49\x6e\146\x6f";
        $KI = $zk->query($yZ, $this->sigNode);
        if (!($KI->length > 1)) {
            goto L6;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x49\x6e\166\141\x6c\151\144\x20\163\x74\x72\165\143\x74\165\162\x65\40\55\40\x54\157\x6f\40\x6d\x61\x6e\171\40\x53\x69\x67\156\x65\x64\x49\x6e\146\x6f\40\145\154\145\155\x65\x6e\x74\163\40\146\157\165\156\x64");
        L6:
        if (!($OK = $KI->item(0))) {
            goto ei;
        }
        $yZ = "\x2e\57\x73\x65\143\x64\163\x69\147\72\x43\x61\x6e\157\156\x69\143\x61\154\x69\172\x61\164\x69\x6f\156\115\145\x74\150\157\144";
        $KI = $zk->query($yZ, $OK);
        $t0 = null;
        if (!($eS = $KI->item(0))) {
            goto dZ;
        }
        $ft = $eS->getAttribute("\x41\x6c\147\x6f\x72\151\164\150\155");
        foreach ($eS->childNodes as $Fa) {
            if (!($Fa->localName == "\111\x6e\x63\154\x75\163\x69\166\145\116\x61\155\145\x73\160\141\x63\145\x73")) {
                goto bl;
            }
            if (!($c9 = $Fa->getAttribute("\120\x72\x65\146\x69\x78\114\151\163\164"))) {
                goto ZD;
            }
            $aw = array_filter(explode("\40", $c9));
            if (!(count($aw) > 0)) {
                goto OT;
            }
            $t0 = \SAMLSPUtilities::mo_saml_array_merge($t0 ? $t0 : array(), $aw);
            OT:
            ZD:
            bl:
            gn:
        }
        Ko:
        dZ:
        $this->signedInfo = $this->canonicalizeData($OK, $ft, null, $t0);
        return $this->signedInfo;
        ei:
        yo:
        return null;
    }
    public function calculateDigest($xu, $jr, $vH = true)
    {
        switch ($xu) {
            case self::SHA1:
                $hN = "\x73\x68\x61\61";
                goto Cb;
            case self::SHA256:
                $hN = "\x73\x68\x61\62\x35\66";
                goto Cb;
            case self::SHA384:
                $hN = "\163\x68\141\x33\x38\x34";
                goto Cb;
            case self::SHA512:
                $hN = "\163\150\141\65\x31\x32";
                goto Cb;
            case self::RIPEMD160:
                $hN = "\x72\151\x70\x65\x6d\144\x31\66\60";
                goto Cb;
            default:
                throw new \Mo_SAML_XMLSecLibs_Processing_Exception(esc_attr("\x43\x61\x6e\156\x6f\x74\x20\166\141\x6c\151\x64\x61\x74\x65\x20\144\x69\x67\x65\163\164\72\40\125\156\163\x75\x70\x70\157\162\164\145\144\x20\x41\x6c\x67\157\x72\x69\164\x68\155\x20\74{$xu}\x3e"));
        }
        sr:
        Cb:
        $Ob = hash($hN, $jr, true);
        if (!$vH) {
            goto gi;
        }
        $Ob = base64_encode($Ob);
        gi:
        return $Ob;
    }
    public function validateDigest($qO, $jr)
    {
        $zk = new DOMXPath($qO->ownerDocument);
        $zk->registerNamespace("\163\145\x63\144\163\151\147", self::XMLDSIGNS);
        $yZ = "\x73\164\x72\151\156\147\x28\x2e\57\163\x65\143\144\x73\x69\147\72\104\151\147\145\x73\x74\x4d\x65\164\150\157\x64\57\x40\101\x6c\x67\157\x72\151\x74\x68\x6d\x29";
        $xu = $zk->evaluate($yZ, $qO);
        $bd = $this->calculateDigest($xu, $jr, false);
        $yZ = "\163\164\162\x69\x6e\147\50\56\57\163\145\143\x64\x73\151\147\x3a\104\x69\x67\145\163\164\x56\141\x6c\x75\145\x29";
        $Qj = $zk->evaluate($yZ, $qO);
        return $bd === base64_decode($Qj);
    }
    public function processTransforms($qO, $oC, $D4 = true)
    {
        $jr = $oC;
        $zk = new DOMXPath($qO->ownerDocument);
        $zk->registerNamespace("\163\x65\143\144\163\x69\x67", self::XMLDSIGNS);
        $yZ = "\x2e\57\x73\145\143\x64\163\151\x67\72\124\x72\141\x6e\163\x66\x6f\162\x6d\x73\57\163\x65\x63\144\163\151\147\x3a\124\162\141\156\163\146\x6f\162\155";
        $Nt = $zk->query($yZ, $qO);
        $Og = "\x68\164\x74\x70\72\57\57\167\167\x77\56\x77\x33\x2e\157\x72\147\57\x54\x52\x2f\62\x30\x30\x31\x2f\122\x45\x43\x2d\x78\155\x6c\55\x63\61\64\x6e\55\62\60\60\61\x30\63\x31\x35";
        $RU = null;
        $t0 = null;
        foreach ($Nt as $ej) {
            $PS = $ej->getAttribute("\x41\x6c\147\x6f\x72\x69\x74\150\155");
            switch ($PS) {
                case "\x68\x74\164\x70\72\57\57\x77\167\167\x2e\167\63\x2e\157\x72\x67\57\62\60\60\61\57\x31\x30\57\x78\x6d\154\55\145\170\x63\55\143\61\64\x6e\x23":
                case "\x68\164\164\x70\x3a\57\57\167\167\167\56\167\63\56\x6f\x72\x67\57\x32\x30\x30\61\x2f\x31\x30\x2f\x78\155\x6c\x2d\x65\x78\x63\55\143\61\x34\156\x23\x57\x69\164\150\x43\x6f\155\155\145\156\164\163":
                    if (!$D4) {
                        goto yr;
                    }
                    $Og = $PS;
                    goto EV;
                    yr:
                    $Og = "\150\x74\x74\x70\x3a\57\57\167\167\x77\56\167\x33\56\157\x72\x67\57\62\60\x30\61\x2f\x31\x30\x2f\170\x6d\x6c\x2d\x65\170\x63\55\x63\61\64\x6e\x23";
                    EV:
                    $Fa = $ej->firstChild;
                    ag:
                    if (!$Fa) {
                        goto S1;
                    }
                    if (!($Fa->localName == "\111\x6e\143\154\x75\163\x69\166\145\x4e\141\x6d\x65\163\160\141\143\x65\x73")) {
                        goto GW;
                    }
                    if (!($c9 = $Fa->getAttribute("\120\162\x65\146\x69\x78\114\x69\x73\x74"))) {
                        goto fW;
                    }
                    $aw = array();
                    $dn = explode("\x20", $c9);
                    foreach ($dn as $c9) {
                        $wC = trim($c9);
                        if (empty($wC)) {
                            goto qF;
                        }
                        $aw[] = $wC;
                        qF:
                        Ph:
                    }
                    uW:
                    if (!(count($aw) > 0)) {
                        goto iC;
                    }
                    $t0 = $aw;
                    iC:
                    fW:
                    goto S1;
                    GW:
                    $Fa = $Fa->nextSibling;
                    goto ag;
                    S1:
                    goto M5;
                case "\x68\x74\164\x70\72\x2f\x2f\x77\167\x77\56\x77\x33\56\x6f\162\147\x2f\x54\x52\x2f\x32\x30\x30\x31\57\x52\x45\x43\x2d\170\155\154\55\x63\x31\64\156\55\62\60\x30\61\x30\63\61\x35":
                case "\x68\x74\x74\x70\x3a\57\x2f\167\167\x77\56\167\x33\x2e\x6f\162\147\x2f\x54\x52\x2f\x32\60\x30\x31\x2f\x52\105\103\55\170\x6d\154\x2d\x63\x31\x34\156\55\62\x30\60\x31\x30\63\x31\65\43\127\x69\x74\x68\x43\x6f\155\155\145\156\164\x73":
                    if (!$D4) {
                        goto xr;
                    }
                    $Og = $PS;
                    goto jW;
                    xr:
                    $Og = "\150\164\x74\x70\x3a\57\57\167\167\167\x2e\x77\63\56\x6f\162\x67\x2f\x54\x52\x2f\x32\60\60\x31\x2f\x52\x45\103\x2d\x78\x6d\154\x2d\x63\61\x34\156\x2d\x32\60\60\x31\x30\63\x31\x35";
                    jW:
                    goto M5;
                case "\150\x74\x74\160\72\57\57\x77\167\167\x2e\x77\63\x2e\157\x72\147\57\124\x52\57\61\x39\x39\71\x2f\x52\x45\x43\x2d\x78\x70\141\x74\150\55\61\x39\x39\71\x31\61\61\x36":
                    $Fa = $ej->firstChild;
                    Ei:
                    if (!$Fa) {
                        goto py;
                    }
                    if (!($Fa->localName == "\x58\x50\141\164\150")) {
                        goto dJ;
                    }
                    $RU = array();
                    $RU["\x71\165\145\x72\x79"] = "\50\x2e\57\x2f\x2e\40\174\x20\56\x2f\x2f\100\x2a\40\x7c\x20\56\x2f\57\156\141\x6d\145\163\160\141\143\145\x3a\72\52\x29\x5b" . $Fa->nodeValue . "\x5d";
                    $RU["\x6e\x61\155\x65\x73\x70\141\143\x65\163"] = array();
                    $HX = $zk->query("\x2e\x2f\x6e\x61\155\x65\x73\x70\141\143\x65\x3a\x3a\x2a", $Fa);
                    foreach ($HX as $qE) {
                        if (!($qE->localName != "\x78\155\x6c")) {
                            goto Az;
                        }
                        $RU["\156\141\x6d\x65\163\160\141\143\145\x73"][$qE->localName] = $qE->nodeValue;
                        Az:
                        Vd:
                    }
                    Y5:
                    goto py;
                    dJ:
                    $Fa = $Fa->nextSibling;
                    goto Ei;
                    py:
                    goto M5;
            }
            bU:
            M5:
            Ju:
        }
        DD:
        if (!$jr instanceof DOMNode) {
            goto AM;
        }
        $jr = $this->canonicalizeData($oC, $Og, $RU, $t0);
        AM:
        return $jr;
    }
    public function processRefNode($qO)
    {
        $Ww = null;
        $D4 = true;
        if ($s8 = $qO->getAttribute("\x55\x52\x49")) {
            goto h9;
        }
        $D4 = false;
        $Ww = $qO->ownerDocument;
        goto wZ;
        h9:
        $yC = parse_url($s8);
        if (!empty($yC["\x70\x61\x74\x68"])) {
            goto MD;
        }
        if ($TJ = $yC["\146\162\x61\147\x6d\x65\156\x74"]) {
            goto RZ;
        }
        $Ww = $qO->ownerDocument;
        goto Av;
        RZ:
        $D4 = false;
        $Fr = new DOMXPath($qO->ownerDocument);
        if (!($this->idNS && is_array($this->idNS))) {
            goto Je;
        }
        foreach ($this->idNS as $Xh => $du) {
            $Fr->registerNamespace($Xh, $du);
            eE:
        }
        Ih:
        Je:
        $EV = "\100\x49\x64\x3d\42" . XPath::filterAttrValue($TJ, XPath::DOUBLE_QUOTE) . "\42";
        if (!is_array($this->idKeys)) {
            goto OM;
        }
        foreach ($this->idKeys as $ny) {
            $EV .= "\40\x6f\162\x20\x40" . XPath::filterAttrName($ny) . "\75\x22" . XPath::filterAttrValue($TJ, XPath::DOUBLE_QUOTE) . "\42";
            Tv:
        }
        J0:
        OM:
        $yZ = "\x2f\57\52\x5b" . $EV . "\x5d";
        $Ww = $Fr->query($yZ)->item(0);
        Av:
        MD:
        wZ:
        $jr = $this->processTransforms($qO, $Ww, $D4);
        if ($this->validateDigest($qO, $jr)) {
            goto J4;
        }
        return false;
        J4:
        if (!$Ww instanceof DOMNode) {
            goto J1;
        }
        if (!empty($TJ)) {
            goto HY;
        }
        $this->validatedNodes[] = $Ww;
        goto EQ;
        HY:
        $this->validatedNodes[$TJ] = $Ww;
        EQ:
        J1:
        return true;
    }
    public function getRefNodeID($qO)
    {
        if (!($s8 = $qO->getAttribute("\125\122\x49"))) {
            goto zt;
        }
        $yC = parse_url($s8);
        if (!empty($yC["\x70\x61\164\x68"])) {
            goto oc;
        }
        if (!($TJ = $yC["\146\x72\x61\x67\x6d\145\x6e\x74"])) {
            goto DA;
        }
        return $TJ;
        DA:
        oc:
        zt:
        return null;
    }
    public function getRefIDs()
    {
        $Kc = array();
        $zk = $this->getXPathObj();
        $yZ = "\x2e\x2f\163\x65\143\x64\163\x69\147\x3a\x53\151\x67\x6e\x65\x64\111\156\146\x6f\x5b\61\135\x2f\163\145\x63\144\163\151\147\72\x52\145\x66\145\x72\145\156\x63\145";
        $KI = $zk->query($yZ, $this->sigNode);
        if (!($KI->length == 0)) {
            goto PK;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\122\145\146\x65\162\x65\x6e\x63\x65\x20\x6e\x6f\x64\145\x73\x20\x6e\157\164\40\x66\x6f\x75\156\x64");
        PK:
        foreach ($KI as $qO) {
            $Kc[] = $this->getRefNodeID($qO);
            KN:
        }
        yc:
        return $Kc;
    }
    public function validateReference()
    {
        $Mj = $this->sigNode->ownerDocument->documentElement;
        if ($Mj->isSameNode($this->sigNode)) {
            goto vt;
        }
        if (!($this->sigNode->parentNode != null)) {
            goto Od;
        }
        $this->sigNode->parentNode->removeChild($this->sigNode);
        Od:
        vt:
        $zk = $this->getXPathObj();
        $yZ = "\56\57\163\145\143\x64\x73\x69\x67\x3a\123\151\x67\156\x65\x64\x49\156\x66\x6f\x5b\61\x5d\57\163\145\143\144\x73\x69\147\72\122\x65\146\145\x72\x65\x6e\143\x65";
        $KI = $zk->query($yZ, $this->sigNode);
        if (!($KI->length == 0)) {
            goto to;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x52\145\146\145\x72\145\156\143\x65\40\156\x6f\x64\145\163\40\156\x6f\x74\x20\146\157\x75\x6e\144");
        to:
        $this->validatedNodes = array();
        foreach ($KI as $qO) {
            if ($this->processRefNode($qO)) {
                goto DE;
            }
            $this->validatedNodes = null;
            throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\122\x65\146\x65\x72\145\x6e\143\145\x20\x76\141\154\151\x64\x61\x74\x69\x6f\x6e\40\146\x61\151\154\145\x64");
            DE:
            Fx:
        }
        rR:
        return true;
    }
    private function addRefInternal($Qv, $Fa, $PS, $J1 = null, $Nj = null)
    {
        $a7 = null;
        $LW = null;
        $Y7 = "\x49\x64";
        $st = true;
        $QN = false;
        if (!is_array($Nj)) {
            goto IO;
        }
        $a7 = empty($Nj["\x70\162\x65\146\x69\x78"]) ? null : $Nj["\160\x72\145\x66\151\x78"];
        $LW = empty($Nj["\x70\x72\x65\146\x69\x78\x5f\156\163"]) ? null : $Nj["\160\162\x65\x66\x69\x78\x5f\156\x73"];
        $Y7 = empty($Nj["\151\x64\x5f\156\141\155\145"]) ? "\x49\x64" : $Nj["\151\x64\x5f\156\141\x6d\x65"];
        $st = empty($Nj["\157\x76\145\162\x77\x72\151\x74\145"]) ? true : (bool) $Nj["\x6f\166\145\162\167\x72\151\x74\x65"];
        $QN = empty($Nj["\146\x6f\162\143\x65\x5f\165\x72\151"]) ? false : (bool) $Nj["\x66\x6f\162\143\x65\x5f\165\162\x69"];
        IO:
        $qS = $Y7;
        if (empty($a7)) {
            goto Ub;
        }
        $qS = $a7 . "\72" . $qS;
        Ub:
        $qO = $this->createNewSignNode("\122\145\x66\145\162\145\x6e\x63\145");
        $Qv->appendChild($qO);
        if (!$Fa instanceof DOMDocument) {
            goto Is;
        }
        if ($QN) {
            goto Pe;
        }
        goto IY;
        Is:
        $s8 = null;
        if ($st) {
            goto Gd;
        }
        $s8 = $LW ? $Fa->getAttributeNS($LW, $Y7) : $Fa->getAttribute($Y7);
        Gd:
        if (!empty($s8)) {
            goto GX;
        }
        $s8 = self::generateGUID();
        $Fa->setAttributeNS($LW, $qS, $s8);
        GX:
        $qO->setAttribute("\x55\122\x49", "\43" . $s8);
        goto IY;
        Pe:
        $qO->setAttribute("\125\122\x49", '');
        IY:
        $Gt = $this->createNewSignNode("\124\x72\141\156\x73\x66\157\x72\155\163");
        $qO->appendChild($Gt);
        if (is_array($J1)) {
            goto Qx;
        }
        if (!empty($this->canonicalMethod)) {
            goto XS;
        }
        goto OW;
        Qx:
        foreach ($J1 as $ej) {
            $Mz = $this->createNewSignNode("\x54\162\x61\x6e\x73\x66\157\x72\x6d");
            $Gt->appendChild($Mz);
            if (is_array($ej) && !empty($ej["\x68\x74\x74\160\72\57\x2f\x77\167\167\x2e\167\x33\x2e\x6f\162\147\57\124\x52\57\x31\71\71\71\x2f\x52\105\103\x2d\170\x70\141\x74\150\55\x31\x39\x39\x39\61\61\61\66"]) && !empty($ej["\x68\164\x74\160\72\57\57\167\x77\167\x2e\x77\x33\x2e\x6f\162\147\x2f\x54\x52\57\x31\x39\x39\x39\57\x52\x45\103\55\170\160\141\x74\x68\x2d\x31\71\71\71\x31\x31\x31\66"]["\161\x75\145\162\x79"])) {
                goto tO;
            }
            $Mz->setAttribute("\101\x6c\147\x6f\x72\x69\164\x68\155", $ej);
            goto Cx;
            tO:
            $Mz->setAttribute("\101\x6c\147\157\162\x69\x74\x68\x6d", "\x68\164\164\x70\72\57\57\167\167\167\x2e\167\63\x2e\x6f\162\147\57\124\122\57\x31\x39\x39\x39\57\x52\x45\103\x2d\x78\160\141\x74\150\55\x31\x39\71\x39\61\61\61\x36");
            $B1 = $this->createNewSignNode("\x58\120\141\x74\150", $ej["\x68\164\x74\160\72\57\x2f\167\167\x77\x2e\x77\63\56\x6f\162\147\57\x54\x52\57\x31\x39\x39\x39\57\122\x45\103\x2d\170\x70\141\164\x68\55\x31\x39\71\x39\x31\x31\x31\66"]["\x71\165\145\162\x79"]);
            $Mz->appendChild($B1);
            if (empty($ej["\x68\164\x74\160\x3a\57\57\167\x77\x77\56\x77\63\x2e\x6f\162\x67\x2f\124\122\x2f\x31\71\x39\x39\x2f\x52\105\103\55\170\x70\141\164\x68\55\61\x39\x39\71\61\61\61\66"]["\156\141\x6d\145\163\x70\x61\143\x65\163"])) {
                goto c9;
            }
            foreach ($ej["\x68\x74\x74\x70\72\57\x2f\167\167\x77\x2e\167\x33\x2e\157\x72\x67\x2f\124\x52\57\61\71\71\71\x2f\122\x45\x43\x2d\x78\x70\141\x74\x68\55\61\x39\x39\71\61\x31\x31\x36"]["\156\141\x6d\x65\x73\160\x61\x63\145\x73"] as $a7 => $Pi) {
                $B1->setAttributeNS("\150\x74\164\160\x3a\57\57\167\167\x77\x2e\x77\x33\56\157\162\147\x2f\62\60\x30\x30\57\x78\155\x6c\156\163\57", "\x78\x6d\x6c\x6e\163\72{$a7}", $Pi);
                v6:
            }
            JF:
            c9:
            Cx:
            wQ:
        }
        ck:
        goto OW;
        XS:
        $Mz = $this->createNewSignNode("\124\162\x61\156\163\146\157\162\155");
        $Gt->appendChild($Mz);
        $Mz->setAttribute("\x41\x6c\x67\x6f\162\x69\164\x68\155", $this->canonicalMethod);
        OW:
        $jg = $this->processTransforms($qO, $Fa);
        $bd = $this->calculateDigest($PS, $jg);
        $pp = $this->createNewSignNode("\104\x69\147\145\x73\x74\x4d\145\164\150\157\144");
        $qO->appendChild($pp);
        $pp->setAttribute("\x41\154\x67\x6f\x72\x69\x74\150\x6d", $PS);
        $Qj = $this->createNewSignNode("\104\151\147\x65\x73\164\x56\x61\x6c\165\x65", $bd);
        $qO->appendChild($Qj);
    }
    public function addReference($Fa, $PS, $J1 = null, $Nj = null)
    {
        if (!($zk = $this->getXPathObj())) {
            goto nI;
        }
        $yZ = "\x2e\57\x73\145\143\x64\163\151\147\72\x53\151\147\x6e\x65\144\x49\156\x66\157";
        $KI = $zk->query($yZ, $this->sigNode);
        if (!($QO = $KI->item(0))) {
            goto MW;
        }
        $this->addRefInternal($QO, $Fa, $PS, $J1, $Nj);
        MW:
        nI:
    }
    public function addReferenceList($Rg, $PS, $J1 = null, $Nj = null)
    {
        if (!($zk = $this->getXPathObj())) {
            goto gr;
        }
        $yZ = "\x2e\x2f\x73\145\143\x64\x73\151\147\72\123\151\x67\156\145\x64\x49\x6e\146\157";
        $KI = $zk->query($yZ, $this->sigNode);
        if (!($QO = $KI->item(0))) {
            goto Si;
        }
        foreach ($Rg as $Fa) {
            $this->addRefInternal($QO, $Fa, $PS, $J1, $Nj);
            zu:
        }
        xU:
        Si:
        gr:
    }
    public function addObject($jr, $mO = null, $H3 = null)
    {
        $cp = $this->createNewSignNode("\117\142\x6a\145\x63\164");
        $this->sigNode->appendChild($cp);
        if (empty($mO)) {
            goto gk;
        }
        $cp->setAttribute("\x4d\151\155\145\x54\171\x70\145", $mO);
        gk:
        if (empty($H3)) {
            goto zj;
        }
        $cp->setAttribute("\105\x6e\x63\x6f\144\151\x6e\147", $H3);
        zj:
        if ($jr instanceof DOMElement) {
            goto Iq;
        }
        $i_ = $this->sigNode->ownerDocument->createTextNode($jr);
        goto eC;
        Iq:
        $i_ = $this->sigNode->ownerDocument->importNode($jr, true);
        eC:
        $cp->appendChild($i_);
        return $cp;
    }
    public function locateKey($Fa = null)
    {
        if (!empty($Fa)) {
            goto RT;
        }
        $Fa = $this->sigNode;
        RT:
        if ($Fa instanceof DOMNode) {
            goto qG;
        }
        return null;
        qG:
        if (!($hT = $Fa->ownerDocument)) {
            goto i8;
        }
        $zk = new DOMXPath($hT);
        $zk->registerNamespace("\163\x65\143\144\163\x69\147", self::XMLDSIGNS);
        $yZ = "\163\164\x72\151\156\147\x28\56\x2f\x73\x65\x63\144\163\x69\x67\72\123\151\147\x6e\x65\144\x49\x6e\146\x6f\x2f\x73\145\143\x64\x73\x69\x67\72\x53\151\x67\156\x61\164\x75\162\x65\115\x65\x74\x68\157\x64\57\100\x41\x6c\147\157\162\151\x74\x68\155\51";
        $PS = $zk->evaluate($yZ, $Fa);
        if (!$PS) {
            goto N0;
        }
        try {
            $Nq = new XMLSecurityKey($PS, array("\164\171\160\145" => "\x70\165\x62\x6c\x69\x63"));
        } catch (Exception $G2) {
            return null;
        }
        return $Nq;
        N0:
        i8:
        return null;
    }
    public function verify($Nq)
    {
        $hT = $this->sigNode->ownerDocument;
        $zk = new DOMXPath($hT);
        $zk->registerNamespace("\163\145\143\144\163\x69\147", self::XMLDSIGNS);
        $yZ = "\x73\x74\x72\x69\x6e\147\x28\56\57\x73\x65\x63\x64\163\x69\147\72\123\151\147\x6e\x61\x74\165\x72\x65\x56\x61\x6c\165\x65\51";
        $li = $zk->evaluate($yZ, $this->sigNode);
        if (!empty($li)) {
            goto Ry;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x55\x6e\141\x62\154\x65\40\164\157\x20\154\x6f\143\141\x74\x65\x20\123\151\147\x6e\141\x74\x75\x72\145\126\x61\154\x75\x65");
        Ry:
        return $Nq->verifySignature($this->signedInfo, base64_decode($li));
    }
    public function signData($Nq, $jr)
    {
        return $Nq->signData($jr);
    }
    public function sign($Nq, $e5 = null)
    {
        if (!($e5 != null)) {
            goto cA;
        }
        $this->resetXPathObj();
        $this->appendSignature($e5);
        $this->sigNode = $e5->lastChild;
        cA:
        if (!($zk = $this->getXPathObj())) {
            goto zW;
        }
        $yZ = "\56\57\163\145\x63\144\x73\x69\x67\72\x53\x69\147\156\x65\144\x49\x6e\146\157";
        $KI = $zk->query($yZ, $this->sigNode);
        if (!($QO = $KI->item(0))) {
            goto cH;
        }
        $yZ = "\56\57\163\145\143\x64\x73\151\x67\72\123\x69\x67\156\x61\x74\165\x72\145\115\145\164\150\x6f\x64";
        $KI = $zk->query($yZ, $QO);
        $HC = $KI->item(0);
        $HC->setAttribute("\101\154\147\x6f\x72\x69\164\x68\x6d", $Nq->type);
        $jr = $this->canonicalizeData($QO, $this->canonicalMethod);
        $li = base64_encode($this->signData($Nq, $jr));
        $pW = $this->createNewSignNode("\x53\151\x67\x6e\x61\164\165\162\145\126\141\x6c\165\145", $li);
        if ($zl = $QO->nextSibling) {
            goto g9;
        }
        $this->sigNode->appendChild($pW);
        goto Ho;
        g9:
        $zl->parentNode->insertBefore($pW, $zl);
        Ho:
        cH:
        zW:
    }
    public function appendCert()
    {
    }
    public function appendKey($Nq, $ri = null)
    {
        $Nq->serializeKey($ri);
    }
    public function insertSignature($Fa, $GY = null)
    {
        $nI = $Fa->ownerDocument;
        $Yt = $nI->importNode($this->sigNode, true);
        if ($GY == null) {
            goto io;
        }
        return $Fa->insertBefore($Yt, $GY);
        goto uG;
        io:
        return $Fa->insertBefore($Yt);
        uG:
    }
    public function appendSignature($JE, $Bx = false)
    {
        $GY = $Bx ? $JE->firstChild : null;
        return $this->insertSignature($JE, $GY);
    }
    public static function get509XCert($Fh, $m7 = true)
    {
        $Ql = self::staticGet509XCerts($Fh, $m7);
        if (empty($Ql)) {
            goto MZ;
        }
        return $Ql[0];
        MZ:
        return '';
    }
    public static function staticGet509XCerts($Ql, $m7 = true)
    {
        if ($m7) {
            goto At;
        }
        return array($Ql);
        goto pP;
        At:
        $jr = '';
        $cc = array();
        $qb = explode("\12", $Ql);
        $RM = false;
        foreach ($qb as $Wz) {
            if (!$RM) {
                goto JK;
            }
            if (!(strncmp($Wz, "\55\x2d\x2d\x2d\x2d\x45\116\104\x20\x43\105\x52\x54\x49\x46\x49\x43\x41\124\105", 20) == 0)) {
                goto TX;
            }
            $RM = false;
            $cc[] = $jr;
            $jr = '';
            goto HD;
            TX:
            $jr .= trim($Wz);
            goto fF;
            JK:
            if (!(strncmp($Wz, "\x2d\55\x2d\55\55\x42\x45\107\111\116\x20\x43\x45\122\x54\111\x46\111\103\x41\124\105", 22) == 0)) {
                goto OY;
            }
            $RM = true;
            OY:
            fF:
            HD:
        }
        H_:
        return $cc;
        pP:
    }
    public static function staticAdd509Cert($xw, $Fh, $m7 = true, $RO = false, $zk = null, $Nj = null)
    {
        if (!$RO) {
            goto CU;
        }
        $Fh = file_get_contents($Fh);
        CU:
        if ($xw instanceof DOMElement) {
            goto vk;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\111\x6e\166\141\x6c\x69\144\40\160\x61\x72\145\156\x74\40\116\157\x64\x65\x20\160\x61\x72\x61\x6d\x65\x74\x65\162");
        vk:
        $Gz = $xw->ownerDocument;
        if (!empty($zk)) {
            goto dM;
        }
        $zk = new DOMXPath($xw->ownerDocument);
        $zk->registerNamespace("\x73\x65\x63\x64\x73\x69\147", self::XMLDSIGNS);
        dM:
        $yZ = "\56\x2f\163\145\x63\x64\x73\151\x67\x3a\113\x65\171\111\x6e\x66\x6f";
        $KI = $zk->query($yZ, $xw);
        $TE = $KI->item(0);
        $vc = '';
        if (!$TE) {
            goto cG;
        }
        $c9 = $TE->lookupPrefix(self::XMLDSIGNS);
        if (empty($c9)) {
            goto Xr;
        }
        $vc = $c9 . "\72";
        Xr:
        goto FI;
        cG:
        $c9 = $xw->lookupPrefix(self::XMLDSIGNS);
        if (empty($c9)) {
            goto Lm;
        }
        $vc = $c9 . "\72";
        Lm:
        $wD = false;
        $TE = $Gz->createElementNS(self::XMLDSIGNS, $vc . "\113\x65\171\111\x6e\146\157");
        $yZ = "\56\57\163\x65\143\144\x73\x69\147\72\117\x62\152\145\x63\164";
        $KI = $zk->query($yZ, $xw);
        if (!($ZV = $KI->item(0))) {
            goto g5;
        }
        $ZV->parentNode->insertBefore($TE, $ZV);
        $wD = true;
        g5:
        if ($wD) {
            goto Ts;
        }
        $xw->appendChild($TE);
        Ts:
        FI:
        $Ql = self::staticGet509XCerts($Fh, $m7);
        $Qb = $Gz->createElementNS(self::XMLDSIGNS, $vc . "\130\x35\60\71\104\141\x74\x61");
        $TE->appendChild($Qb);
        $Q8 = false;
        $fD = false;
        if (!is_array($Nj)) {
            goto za;
        }
        if (empty($Nj["\151\x73\x73\x75\x65\162\123\x65\162\151\141\154"])) {
            goto uk;
        }
        $Q8 = true;
        uk:
        if (empty($Nj["\163\165\x62\x6a\x65\143\x74\x4e\141\x6d\145"])) {
            goto QX;
        }
        $fD = true;
        QX:
        za:
        foreach ($Ql as $PF) {
            if (!($Q8 || $fD)) {
                goto mK;
            }
            if (!($q0 = openssl_x509_parse("\55\55\x2d\55\x2d\102\x45\107\x49\116\40\x43\105\x52\124\111\x46\111\x43\x41\x54\x45\55\55\55\x2d\55\12" . chunk_split($PF, 64, "\xa") . "\55\x2d\55\55\x2d\105\x4e\x44\x20\103\105\x52\124\x49\106\111\x43\x41\x54\x45\x2d\x2d\55\x2d\55\12"))) {
                goto hi;
            }
            if (!($fD && !empty($q0["\x73\165\x62\x6a\145\143\x74"]))) {
                goto LM;
            }
            if (is_array($q0["\x73\165\142\152\x65\x63\164"])) {
                goto yN;
            }
            $Ol = $q0["\151\163\163\x75\145\x72"];
            goto hp;
            yN:
            $Xk = array();
            foreach ($q0["\163\x75\x62\152\x65\143\x74"] as $R2 => $EB) {
                if (is_array($EB)) {
                    goto Yp;
                }
                array_unshift($Xk, "{$R2}\x3d{$EB}");
                goto To;
                Yp:
                foreach ($EB as $eW) {
                    array_unshift($Xk, "{$R2}\75{$eW}");
                    t4:
                }
                Cf:
                To:
                WB:
            }
            ce:
            $Ol = implode("\54", $Xk);
            hp:
            $iq = $Gz->createElementNS(self::XMLDSIGNS, $vc . "\x58\x35\x30\71\x53\x75\142\x6a\145\x63\164\116\x61\x6d\x65", $Ol);
            $Qb->appendChild($iq);
            LM:
            if (!($Q8 && !empty($q0["\151\163\163\165\145\162"]) && !empty($q0["\163\145\162\x69\141\154\116\x75\x6d\142\145\x72"]))) {
                goto ao;
            }
            if (is_array($q0["\151\x73\x73\165\145\x72"])) {
                goto RE;
            }
            $y2 = $q0["\151\x73\x73\x75\x65\x72"];
            goto YX;
            RE:
            $Xk = array();
            foreach ($q0["\151\x73\163\165\x65\162"] as $R2 => $EB) {
                array_unshift($Xk, "{$R2}\x3d{$EB}");
                ys:
            }
            x3:
            $y2 = implode("\54", $Xk);
            YX:
            $ei = $Gz->createElementNS(self::XMLDSIGNS, $vc . "\x58\x35\x30\71\111\x73\x73\x75\145\162\x53\x65\x72\x69\141\154");
            $Qb->appendChild($ei);
            $AU = $Gz->createElementNS(self::XMLDSIGNS, $vc . "\x58\x35\60\x39\111\x73\x73\165\145\162\x4e\x61\155\145", $y2);
            $ei->appendChild($AU);
            $AU = $Gz->createElementNS(self::XMLDSIGNS, $vc . "\x58\x35\60\71\123\145\162\151\x61\154\x4e\165\x6d\142\x65\162", $q0["\x73\145\162\151\x61\x6c\x4e\165\x6d\142\x65\x72"]);
            $ei->appendChild($AU);
            ao:
            hi:
            mK:
            $yr = $Gz->createElementNS(self::XMLDSIGNS, $vc . "\x58\x35\x30\71\x43\145\x72\164\151\146\151\143\x61\164\145", $PF);
            $Qb->appendChild($yr);
            hK:
        }
        SI:
    }
    public function add509Cert($Fh, $m7 = true, $RO = false, $Nj = null)
    {
        if (!($zk = $this->getXPathObj())) {
            goto d4;
        }
        self::staticAdd509Cert($this->sigNode, $Fh, $m7, $RO, $zk, $Nj);
        d4:
    }
    public function appendToKeyInfo($Fa)
    {
        $xw = $this->sigNode;
        $Gz = $xw->ownerDocument;
        $zk = $this->getXPathObj();
        if (!empty($zk)) {
            goto Ha;
        }
        $zk = new DOMXPath($xw->ownerDocument);
        $zk->registerNamespace("\163\145\143\x64\x73\x69\x67", self::XMLDSIGNS);
        Ha:
        $yZ = "\x2e\57\x73\x65\143\144\x73\151\x67\72\x4b\x65\x79\x49\156\146\x6f";
        $KI = $zk->query($yZ, $xw);
        $TE = $KI->item(0);
        if ($TE) {
            goto nH;
        }
        $vc = '';
        $c9 = $xw->lookupPrefix(self::XMLDSIGNS);
        if (empty($c9)) {
            goto Bi;
        }
        $vc = $c9 . "\x3a";
        Bi:
        $wD = false;
        $TE = $Gz->createElementNS(self::XMLDSIGNS, $vc . "\x4b\145\x79\111\x6e\146\x6f");
        $yZ = "\x2e\x2f\x73\145\x63\144\163\x69\x67\72\117\x62\x6a\145\143\x74";
        $KI = $zk->query($yZ, $xw);
        if (!($ZV = $KI->item(0))) {
            goto T3;
        }
        $ZV->parentNode->insertBefore($TE, $ZV);
        $wD = true;
        T3:
        if ($wD) {
            goto q9;
        }
        $xw->appendChild($TE);
        q9:
        nH:
        $TE->appendChild($Fa);
        return $TE;
    }
    public function getValidatedNodes()
    {
        return $this->validatedNodes;
    }
}
