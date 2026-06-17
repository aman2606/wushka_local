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
class XMLSecEnc
{
    const template = "\x3c\x78\145\156\x63\x3a\x45\x6e\143\x72\171\x70\164\145\x64\104\141\164\x61\x20\x78\x6d\154\156\163\72\170\x65\x6e\x63\75\x27\x68\164\x74\160\72\x2f\x2f\167\x77\167\56\167\x33\x2e\x6f\x72\147\x2f\62\60\x30\61\57\60\x34\57\170\155\x6c\x65\x6e\x63\43\47\76\15\12\x20\x20\40\x3c\x78\x65\x6e\143\x3a\x43\151\x70\150\145\x72\104\x61\164\141\x3e\15\xa\40\40\x20\40\40\40\74\x78\145\x6e\143\72\103\x69\x70\x68\x65\162\x56\141\x6c\x75\145\76\74\57\170\145\156\x63\x3a\103\151\x70\150\145\x72\x56\x61\x6c\x75\145\76\15\12\40\x20\40\74\57\x78\x65\x6e\x63\72\x43\x69\160\150\x65\x72\x44\x61\164\x61\x3e\xd\xa\x3c\x2f\170\x65\156\143\72\x45\156\x63\x72\171\x70\164\145\144\104\141\x74\141\x3e";
    const Element = "\150\164\164\x70\x3a\57\x2f\167\167\167\56\167\x33\56\x6f\x72\x67\57\x32\60\60\61\57\x30\64\57\170\x6d\154\145\156\x63\x23\105\154\x65\155\145\156\x74";
    const Content = "\150\164\164\x70\x3a\57\57\167\x77\167\x2e\x77\63\x2e\157\x72\147\57\x32\60\60\x31\57\60\x34\57\x78\x6d\154\x65\156\x63\x23\103\157\156\164\x65\x6e\x74";
    const URI = 3;
    const XMLENCNS = "\150\164\x74\160\72\57\x2f\167\167\x77\x2e\167\x33\x2e\157\x72\x67\x2f\62\60\60\x31\x2f\x30\x34\57\x78\x6d\154\x65\156\x63\x23";
    private $encdoc = null;
    private $rawNode = null;
    public $type = null;
    public $encKey = null;
    private $references = array();
    public function __construct()
    {
        $this->_resetTemplate();
    }
    private function _resetTemplate()
    {
        $this->encdoc = new DOMDocument();
        $this->encdoc->loadXML(self::template);
    }
    public function addReference($or, $Fa, $Gf)
    {
        if ($Fa instanceof DOMNode) {
            goto Md;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x24\x6e\x6f\x64\145\40\x69\x73\40\x6e\x6f\x74\x20\157\x66\40\x74\171\x70\x65\40\x44\117\x4d\116\x6f\x64\x65");
        Md:
        $kV = $this->encdoc;
        $this->_resetTemplate();
        $QL = $this->encdoc;
        $this->encdoc = $kV;
        $cN = XMLSecurityDSig::generateGUID();
        $ss = $QL->documentElement;
        $ss->setAttribute("\111\x64", $cN);
        $this->references[$or] = array("\156\x6f\x64\145" => $Fa, "\164\171\160\145" => $Gf, "\145\x6e\143\x6e\157\144\x65" => $QL, "\x72\145\146\165\x72\x69" => $cN);
    }
    public function setNode($Fa)
    {
        $this->rawNode = $Fa;
    }
    public function encryptNode($Nq, $Hl = true)
    {
        $jr = '';
        if (!empty($this->rawNode)) {
            goto en;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x4e\x6f\144\x65\x20\x74\157\x20\x65\x6e\x63\162\x79\x70\x74\x20\150\141\x73\x20\x6e\x6f\x74\x20\142\145\x65\156\40\x73\145\164");
        en:
        if ($Nq instanceof XMLSecurityKey) {
            goto wm;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\111\x6e\166\141\x6c\x69\144\40\113\145\x79");
        wm:
        $hT = $this->rawNode->ownerDocument;
        $Fr = new DOMXPath($this->encdoc);
        $Oe = $Fr->query("\x2f\x78\145\x6e\143\x3a\x45\x6e\x63\x72\x79\160\164\145\x64\x44\141\x74\x61\x2f\x78\145\x6e\x63\72\103\151\x70\150\x65\162\x44\x61\164\x61\x2f\x78\x65\156\143\72\103\151\x70\x68\x65\x72\126\141\x6c\x75\x65");
        $GL = $Oe->item(0);
        if (!($GL == null)) {
            goto jj;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\105\x72\x72\x6f\x72\40\154\157\x63\x61\164\151\156\x67\x20\x43\151\x70\150\145\x72\126\141\x6c\165\x65\40\145\x6c\145\x6d\x65\x6e\x74\x20\x77\x69\x74\x68\x69\x6e\x20\164\145\x6d\x70\154\x61\x74\145");
        jj:
        switch ($this->type) {
            case self::Element:
                $jr = $hT->saveXML($this->rawNode);
                $this->encdoc->documentElement->setAttribute("\x54\171\x70\145", self::Element);
                goto a7;
            case self::Content:
                $XD = $this->rawNode->childNodes;
                foreach ($XD as $dT) {
                    $jr .= $hT->saveXML($dT);
                    Wk:
                }
                uZ:
                $this->encdoc->documentElement->setAttribute("\x54\x79\160\x65", self::Content);
                goto a7;
            default:
                throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x54\171\x70\x65\x20\x69\x73\40\143\165\162\x72\145\x6e\x74\x6c\x79\40\156\157\x74\x20\163\165\x70\x70\157\162\164\145\x64");
        }
        n4:
        a7:
        $Za = $this->encdoc->documentElement->appendChild($this->encdoc->createElementNS(self::XMLENCNS, "\x78\x65\x6e\x63\72\x45\x6e\143\x72\x79\x70\x74\151\157\156\115\145\x74\150\x6f\x64"));
        $Za->setAttribute("\x41\154\x67\x6f\162\151\164\150\155", $Nq->getAlgorithm());
        $GL->parentNode->parentNode->insertBefore($Za, $GL->parentNode->parentNode->firstChild);
        $t8 = base64_encode($Nq->encryptData($jr));
        $EB = $this->encdoc->createTextNode($t8);
        $GL->appendChild($EB);
        if ($Hl) {
            goto qD;
        }
        return $this->encdoc->documentElement;
        goto RU;
        qD:
        switch ($this->type) {
            case self::Element:
                if (!($this->rawNode->nodeType == XML_DOCUMENT_NODE)) {
                    goto zn;
                }
                return $this->encdoc;
                zn:
                $jY = $this->rawNode->ownerDocument->importNode($this->encdoc->documentElement, true);
                $this->rawNode->parentNode->replaceChild($jY, $this->rawNode);
                return $jY;
            case self::Content:
                $jY = $this->rawNode->ownerDocument->importNode($this->encdoc->documentElement, true);
                rj:
                if (!$this->rawNode->firstChild) {
                    goto Ex;
                }
                $this->rawNode->removeChild($this->rawNode->firstChild);
                goto rj;
                Ex:
                $this->rawNode->appendChild($jY);
                return $jY;
        }
        iE:
        rP:
        RU:
    }
    public function encryptReferences($Nq)
    {
        $jR = $this->rawNode;
        $MD = $this->type;
        foreach ($this->references as $or => $RP) {
            $this->encdoc = $RP["\x65\156\x63\156\x6f\x64\x65"];
            $this->rawNode = $RP["\156\x6f\x64\145"];
            $this->type = $RP["\164\x79\x70\x65"];
            try {
                $vV = $this->encryptNode($Nq);
                $this->references[$or]["\145\156\x63\x6e\x6f\144\145"] = $vV;
            } catch (Exception $G2) {
                $this->rawNode = $jR;
                $this->type = $MD;
                throw $G2;
            }
            Oo:
        }
        S8:
        $this->rawNode = $jR;
        $this->type = $MD;
    }
    public function getCipherValue()
    {
        if (!empty($this->rawNode)) {
            goto jh;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x4e\157\144\145\x20\x74\157\40\144\x65\143\x72\x79\x70\164\40\x68\x61\x73\x20\156\x6f\x74\x20\x62\x65\x65\156\x20\x73\145\164");
        jh:
        $hT = $this->rawNode->ownerDocument;
        $Fr = new DOMXPath($hT);
        $Fr->registerNamespace("\170\155\x6c\x65\x6e\143\162", self::XMLENCNS);
        $yZ = "\x2e\x2f\170\155\x6c\145\x6e\x63\162\x3a\103\151\x70\x68\145\162\104\141\164\141\57\x78\x6d\x6c\x65\156\143\162\x3a\103\151\x70\x68\145\162\x56\x61\x6c\165\145";
        $KI = $Fr->query($yZ, $this->rawNode);
        $Fa = $KI->item(0);
        if ($Fa) {
            goto ih;
        }
        return null;
        ih:
        return base64_decode($Fa->nodeValue);
    }
    public function decryptNode($Nq, $Hl = true)
    {
        if ($Nq instanceof XMLSecurityKey) {
            goto lv;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\111\156\x76\141\154\151\x64\40\113\x65\x79");
        lv:
        $o7 = $this->getCipherValue();
        if ($o7) {
            goto cM;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x43\141\x6e\156\x6f\x74\x20\x6c\157\143\x61\164\x65\40\x65\156\x63\162\171\160\x74\x65\144\x20\144\x61\164\141");
        goto KS;
        cM:
        $SE = $Nq->decryptData($o7);
        if ($Hl) {
            goto ku;
        }
        return $SE;
        goto bK;
        ku:
        switch ($this->type) {
            case self::Element:
                $tP = new DOMDocument();
                $tP->loadXML($SE);
                if (!($this->rawNode->nodeType == XML_DOCUMENT_NODE)) {
                    goto lM;
                }
                return $tP;
                lM:
                $jY = $this->rawNode->ownerDocument->importNode($tP->documentElement, true);
                $this->rawNode->parentNode->replaceChild($jY, $this->rawNode);
                return $jY;
            case self::Content:
                if ($this->rawNode->nodeType == XML_DOCUMENT_NODE) {
                    goto tb;
                }
                $hT = $this->rawNode->ownerDocument;
                goto yS;
                tb:
                $hT = $this->rawNode;
                yS:
                $IH = $hT->createDocumentFragment();
                $IH->appendXML($SE);
                $ri = $this->rawNode->parentNode;
                $ri->replaceChild($IH, $this->rawNode);
                return $ri;
            default:
                return $SE;
        }
        J_:
        nc:
        bK:
        KS:
    }
    public function encryptKey($k_, $fU, $gQ = true)
    {
        if (!(!$k_ instanceof XMLSecurityKey || !$fU instanceof XMLSecurityKey)) {
            goto P7;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\111\x6e\x76\x61\x6c\151\144\x20\x4b\x65\171");
        P7:
        $Hf = base64_encode($k_->encryptData($fU->key));
        $le = $this->encdoc->documentElement;
        $pg = $this->encdoc->createElementNS(self::XMLENCNS, "\170\145\156\x63\x3a\x45\x6e\x63\162\171\x70\164\145\144\x4b\145\x79");
        if ($gQ) {
            goto zE;
        }
        $this->encKey = $pg;
        goto kF;
        zE:
        $TE = $le->insertBefore($this->encdoc->createElementNS("\150\164\x74\160\72\57\57\x77\167\x77\x2e\x77\x33\56\x6f\x72\147\57\x32\60\x30\60\x2f\60\x39\x2f\x78\155\x6c\144\x73\151\x67\43", "\144\163\151\x67\72\x4b\x65\171\x49\156\x66\x6f"), $le->firstChild);
        $TE->appendChild($pg);
        kF:
        $Za = $pg->appendChild($this->encdoc->createElementNS(self::XMLENCNS, "\x78\x65\156\143\x3a\x45\156\x63\x72\171\160\x74\x69\157\156\115\145\164\x68\x6f\144"));
        $Za->setAttribute("\101\x6c\147\157\162\x69\164\150\x6d", $k_->getAlgorith());
        if (empty($k_->name)) {
            goto Km;
        }
        $TE = $pg->appendChild($this->encdoc->createElementNS("\x68\x74\164\x70\x3a\x2f\x2f\167\167\167\56\167\x33\x2e\x6f\162\147\x2f\x32\x30\x30\60\x2f\x30\71\57\170\x6d\x6c\144\163\151\x67\x23", "\x64\163\151\x67\x3a\113\145\x79\111\x6e\146\x6f"));
        $TE->appendChild($this->encdoc->createElementNS("\150\164\164\160\72\57\57\167\167\167\56\x77\x33\x2e\x6f\x72\x67\x2f\x32\60\60\x30\57\x30\x39\x2f\x78\x6d\154\x64\x73\151\147\43", "\144\163\x69\147\72\x4b\x65\x79\116\x61\155\x65", $k_->name));
        Km:
        $zW = $pg->appendChild($this->encdoc->createElementNS(self::XMLENCNS, "\170\145\x6e\143\x3a\103\151\160\150\x65\x72\104\141\x74\141"));
        $zW->appendChild($this->encdoc->createElementNS(self::XMLENCNS, "\x78\x65\x6e\x63\x3a\x43\x69\x70\150\145\162\x56\x61\154\x75\145", $Hf));
        if (!(is_array($this->references) && count($this->references) > 0)) {
            goto Ww;
        }
        $KW = $pg->appendChild($this->encdoc->createElementNS(self::XMLENCNS, "\x78\145\156\143\72\x52\145\x66\145\162\x65\x6e\143\x65\114\151\163\164"));
        foreach ($this->references as $or => $RP) {
            $cN = $RP["\x72\x65\146\x75\x72\151"];
            $cD = $KW->appendChild($this->encdoc->createElementNS(self::XMLENCNS, "\170\x65\156\143\x3a\104\x61\164\x61\122\x65\146\x65\162\145\156\x63\145"));
            $cD->setAttribute("\125\122\x49", "\x23" . $cN);
            Lo:
        }
        HF:
        Ww:
        return;
    }
    public function decryptKey($pg)
    {
        if ($pg->isEncrypted) {
            goto td;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x4b\145\171\x20\x69\163\40\x6e\157\164\40\105\156\143\x72\x79\x70\x74\145\x64");
        td:
        if (!empty($pg->key)) {
            goto TL;
        }
        throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x4b\145\171\40\x69\x73\40\x6d\151\x73\x73\x69\156\x67\40\x64\141\x74\x61\x20\164\157\40\x70\145\x72\x66\x6f\162\x6d\40\x74\x68\x65\40\x64\145\143\x72\x79\x70\164\151\x6f\156");
        TL:
        return $this->decryptNode($pg, false);
    }
    public function locateEncryptedData($ss)
    {
        if ($ss instanceof DOMDocument) {
            goto IQ;
        }
        $hT = $ss->ownerDocument;
        goto bu;
        IQ:
        $hT = $ss;
        bu:
        if (!$hT) {
            goto tQ;
        }
        $zk = new DOMXPath($hT);
        $yZ = "\57\x2f\52\133\154\157\143\x61\x6c\55\156\141\155\x65\50\51\x3d\x27\105\x6e\x63\x72\171\x70\x74\x65\144\104\141\164\141\47\x20\141\156\144\40\x6e\x61\x6d\145\163\160\141\143\x65\x2d\x75\162\151\x28\51\75\x27" . self::XMLENCNS . "\47\x5d";
        $KI = $zk->query($yZ);
        return $KI->item(0);
        tQ:
        return null;
    }
    public function locateKey($Fa = null)
    {
        if (!empty($Fa)) {
            goto Pb;
        }
        $Fa = $this->rawNode;
        Pb:
        if ($Fa instanceof DOMNode) {
            goto cS;
        }
        return null;
        cS:
        if (!($hT = $Fa->ownerDocument)) {
            goto Qi;
        }
        $zk = new DOMXPath($hT);
        $zk->registerNamespace("\x78\x6d\x6c\163\x65\x63\x65\156\x63", self::XMLENCNS);
        $yZ = "\56\x2f\57\x78\155\x6c\163\145\143\145\x6e\x63\x3a\x45\156\143\162\x79\160\x74\x69\157\156\x4d\x65\164\150\x6f\x64";
        $KI = $zk->query($yZ, $Fa);
        if (!($H7 = $KI->item(0))) {
            goto Pu;
        }
        $AS = $H7->getAttribute("\101\x6c\x67\157\x72\x69\x74\150\x6d");
        try {
            $Nq = new XMLSecurityKey($AS, array("\x74\x79\160\145" => "\x70\x72\x69\166\x61\x74\145"));
        } catch (Exception $G2) {
            return null;
        }
        return $Nq;
        Pu:
        Qi:
        return null;
    }
    public static function staticLocateKeyInfo($Wa = null, $Fa = null)
    {
        if (!(empty($Fa) || !$Fa instanceof DOMNode)) {
            goto iR;
        }
        return null;
        iR:
        $hT = $Fa->ownerDocument;
        if ($hT) {
            goto kf;
        }
        return null;
        kf:
        $zk = new DOMXPath($hT);
        $zk->registerNamespace("\170\x6d\154\163\x65\143\145\156\x63", self::XMLENCNS);
        $zk->registerNamespace("\170\155\x6c\163\x65\143\144\163\151\147", XMLSecurityDSig::XMLDSIGNS);
        $yZ = "\x2e\57\170\x6d\154\x73\x65\x63\x64\163\x69\x67\x3a\x4b\145\x79\x49\x6e\x66\x6f";
        $KI = $zk->query($yZ, $Fa);
        $H7 = $KI->item(0);
        if ($H7) {
            goto u1;
        }
        return $Wa;
        u1:
        foreach ($H7->childNodes as $dT) {
            switch ($dT->localName) {
                case "\113\145\171\116\x61\155\x65":
                    if (empty($Wa)) {
                        goto KT;
                    }
                    $Wa->name = $dT->nodeValue;
                    KT:
                    goto Le;
                case "\113\x65\171\x56\141\154\165\145":
                    foreach ($dT->childNodes as $cY) {
                        switch ($cY->localName) {
                            case "\x44\x53\x41\113\x65\171\x56\x61\x6c\165\x65":
                                throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\104\x53\101\113\x65\171\126\x61\x6c\165\145\x20\143\x75\x72\162\x65\156\x74\x6c\171\x20\x6e\157\164\40\163\165\x70\160\157\x72\x74\x65\x64");
                            case "\122\123\101\x4b\145\x79\x56\x61\154\165\x65":
                                $v7 = null;
                                $Um = null;
                                if (!($GT = $cY->getElementsByTagName("\115\x6f\x64\x75\154\165\x73")->item(0))) {
                                    goto QM;
                                }
                                $v7 = base64_decode($GT->nodeValue);
                                QM:
                                if (!($On = $cY->getElementsByTagName("\105\170\x70\157\156\145\x6e\164")->item(0))) {
                                    goto uN;
                                }
                                $Um = base64_decode($On->nodeValue);
                                uN:
                                if (!(empty($v7) || empty($Um))) {
                                    goto kr;
                                }
                                throw new \Mo_SAML_XMLSecLibs_Processing_Exception("\x4d\x69\x73\x73\151\x6e\x67\40\x4d\x6f\x64\x75\x6c\x75\163\x20\157\x72\x20\105\x78\160\x6f\156\x65\156\x74");
                                kr:
                                $bH = XMLSecurityKey::convertRSA($v7, $Um);
                                $Wa->loadKey($bH);
                                goto Y8;
                        }
                        sV:
                        Y8:
                        RQ:
                    }
                    a2:
                    goto Le;
                case "\x52\145\164\162\151\x65\x76\x61\154\x4d\x65\x74\150\x6f\x64":
                    $Gf = $dT->getAttribute("\124\x79\160\145");
                    if (!($Gf !== "\150\x74\164\x70\72\x2f\x2f\x77\167\167\x2e\x77\63\56\x6f\x72\147\x2f\62\x30\x30\x31\x2f\60\64\57\x78\x6d\x6c\145\x6e\143\x23\105\156\x63\162\171\x70\164\145\144\113\145\171")) {
                        goto Lh;
                    }
                    goto Le;
                    Lh:
                    $s8 = $dT->getAttribute("\125\122\111");
                    if (!($s8[0] !== "\43")) {
                        goto eA;
                    }
                    goto Le;
                    eA:
                    $pC = substr($s8, 1);
                    $yZ = "\x2f\x2f\x78\x6d\x6c\x73\145\143\145\x6e\x63\x3a\105\x6e\143\162\x79\x70\x74\145\144\113\145\171\x5b\100\111\x64\75\42" . XPath::filterAttrValue($pC, XPath::DOUBLE_QUOTE) . "\42\x5d";
                    $OR = $zk->query($yZ)->item(0);
                    if ($OR) {
                        goto fS;
                    }
                    throw new \Mo_SAML_XMLSecLibs_Processing_Exception(esc_attr("\x55\x6e\141\142\x6c\x65\40\164\x6f\x20\x6c\x6f\x63\x61\x74\145\40\x45\x6e\x63\x72\171\x70\164\x65\x64\113\145\171\40\x77\x69\x74\150\40\x40\111\x64\75\47{$pC}\47\56"));
                    fS:
                    return XMLSecurityKey::fromEncryptedKeyElement($OR);
                case "\105\156\143\162\x79\x70\x74\145\144\x4b\145\x79":
                    return XMLSecurityKey::fromEncryptedKeyElement($dT);
                case "\130\65\x30\x39\104\x61\x74\x61":
                    if (!($Li = $dT->getElementsByTagName("\x58\x35\60\71\103\x65\162\164\151\x66\x69\x63\x61\164\x65"))) {
                        goto B9;
                    }
                    if (!($Li->length > 0)) {
                        goto T5;
                    }
                    $cU = $Li->item(0)->textContent;
                    $cU = str_replace(array("\15", "\xa", "\x20"), '', $cU);
                    $cU = "\x2d\55\55\x2d\55\102\105\x47\x49\116\40\x43\105\122\124\x49\106\x49\103\x41\x54\x45\55\55\x2d\55\x2d\12" . chunk_split($cU, 64, "\xa") . "\x2d\55\x2d\55\x2d\x45\116\x44\x20\x43\105\x52\124\x49\x46\111\103\x41\x54\105\x2d\55\x2d\55\x2d\12";
                    $Wa->loadKey($cU, false, true);
                    T5:
                    B9:
                    goto Le;
            }
            ZN:
            Le:
            yO:
        }
        ir:
        return $Wa;
    }
    public function locateKeyInfo($Wa = null, $Fa = null)
    {
        if (!empty($Fa)) {
            goto re;
        }
        $Fa = $this->rawNode;
        re:
        return self::staticLocateKeyInfo($Wa, $Fa);
    }
}
