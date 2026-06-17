<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



require_once MO_SAML_PLUGIN_DIR . MO_SAML_OPTIONS_ENUM;
require_once Mo_Saml_Plugin_Files::MO_SAML_UTILITIES;
require_once Mo_Saml_Plugin_Files::MO_SAML_XML_SEC_LIBS;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecEnc;
class SAML2_LogoutRequest
{
    private $tagName;
    private $id;
    private $issuer;
    private $destination;
    private $issueInstant;
    private $certificates;
    private $validators;
    private $notOnOrAfter;
    private $encryptedNameId;
    private $nameId;
    private $sessionIndexes;
    public function __construct(DOMElement $KD = null)
    {
        $this->tagName = "\x4c\x6f\x67\157\x75\164\x52\x65\x71\165\x65\163\x74";
        $this->id = SAMLSPUtilities::generateID();
        $this->issueInstant = time();
        $this->certificates = array();
        $this->validators = array();
        if (!($KD === null)) {
            goto IpZ;
        }
        return;
        IpZ:
        if ($KD->hasAttribute("\111\x44")) {
            goto zb0;
        }
        throw new Mo_SAML_Invalid_Logout_Request_Exception("\115\151\x73\x73\151\156\x67\x20\x49\x44\40\141\x74\164\162\x69\x62\165\x74\x65\x20\157\156\x20\123\x41\115\x4c\x20\155\x65\163\x73\x61\x67\145\x2e");
        zb0:
        $this->id = $KD->getAttribute("\111\x44");
        if (!($KD->getAttribute("\126\x65\x72\163\151\157\x6e") !== "\62\x2e\x30")) {
            goto uBs;
        }
        throw new Mo_SAML_Invalid_Logout_Request_Exception("\x55\x6e\163\165\160\160\157\162\164\145\x64\x20\x76\x65\162\x73\x69\x6f\x6e\72\40" . esc_html($KD->getAttribute("\126\145\x72\x73\x69\157\156")));
        uBs:
        $this->issueInstant = SAMLSPUtilities::xsDateTimeToTimestamp($KD->getAttribute("\x49\x73\163\x75\x65\x49\x6e\x73\x74\x61\x6e\x74"));
        if (!$KD->hasAttribute("\104\x65\x73\x74\x69\156\x61\164\x69\x6f\x6e")) {
            goto i6l;
        }
        $this->destination = $KD->getAttribute("\x44\x65\163\x74\x69\x6e\x61\x74\x69\x6f\156");
        i6l:
        $g5 = SAMLSPUtilities::xpQuery($KD, "\x2e\57\163\x61\x6d\154\137\x61\x73\x73\145\162\164\x69\157\x6e\x3a\111\x73\163\x75\145\162");
        if (empty($g5)) {
            goto Mh8;
        }
        $this->issuer = trim($g5[0]->textContent);
        Mh8:
        try {
            $u2 = SAMLSPUtilities::validateElement($KD);
            if (!($u2 !== false)) {
                goto Klu;
            }
            $this->certificates = $u2["\103\145\162\164\151\146\x69\x63\141\164\145\163"];
            $this->validators[] = array("\x46\165\x6e\x63\x74\x69\x6f\x6e" => array("\123\x41\x4d\114\123\x50\125\x74\151\154\x69\164\151\145\x73", "\166\141\x6c\x69\144\141\x74\x65\123\x69\x67\x6e\141\x74\x75\x72\x65"), "\x44\141\164\141" => $u2);
            Klu:
        } catch (Exception $G2) {
        }
        $this->sessionIndexes = array();
        if (!$KD->hasAttribute("\116\x6f\x74\x4f\156\117\162\101\146\x74\145\x72")) {
            goto L9l;
        }
        $this->notOnOrAfter = SAMLSPUtilities::xsDateTimeToTimestamp($KD->getAttribute("\116\157\164\117\x6e\117\162\101\x66\x74\x65\162"));
        L9l:
        $T9 = SAMLSPUtilities::xpQuery($KD, "\56\57\163\x61\155\x6c\x5f\141\163\x73\x65\x72\x74\x69\157\x6e\72\x4e\141\155\x65\111\104\x20\x7c\x20\56\x2f\x73\141\155\154\x5f\x61\x73\x73\145\162\x74\x69\x6f\156\x3a\x45\156\x63\162\x79\x70\164\x65\x64\x49\x44\57\170\x65\156\143\x3a\105\x6e\143\x72\171\160\x74\x65\x64\104\141\164\141");
        if (empty($T9)) {
            goto zsr;
        }
        if (count($T9) > 1) {
            goto Ens;
        }
        goto MkN;
        zsr:
        throw new Mo_SAML_Invalid_Logout_Request_Exception("\115\151\163\x73\x69\x6e\x67\x20\x3c\x73\141\x6d\x6c\72\x4e\x61\x6d\145\111\104\x3e\x20\157\x72\x20\x3c\x73\141\155\154\72\105\x6e\143\x72\171\160\x74\x65\144\111\x44\76\x20\x69\x6e\40\74\163\141\155\x6c\160\72\114\x6f\x67\x6f\x75\x74\122\x65\x71\165\x65\x73\x74\76\x2e");
        goto MkN;
        Ens:
        throw new Mo_SAML_Invalid_Logout_Request_Exception("\x4d\x6f\x72\x65\x20\164\x68\x61\x6e\40\x6f\156\145\x20\x3c\x73\x61\x6d\154\72\116\x61\x6d\x65\111\x44\76\x20\157\162\x20\x3c\x73\141\x6d\154\72\105\x6e\x63\162\171\160\x74\x65\x64\104\76\x20\x69\x6e\x20\74\163\141\155\154\160\72\x4c\157\147\157\x75\164\122\x65\x71\x75\x65\x73\x74\x3e\56");
        MkN:
        $T9 = $T9[0];
        if ($T9->localName === "\x45\x6e\143\162\x79\x70\164\x65\x64\104\x61\x74\x61") {
            goto ntC;
        }
        $this->nameId = SAMLSPUtilities::parseNameId($T9);
        goto KH_;
        ntC:
        $this->encryptedNameId = $T9;
        KH_:
        $bM = SAMLSPUtilities::xpQuery($KD, "\x2e\x2f\x73\x61\x6d\154\137\x70\x72\157\x74\x6f\143\x6f\154\72\123\145\163\163\x69\157\x6e\x49\156\x64\145\170");
        foreach ($bM as $X9) {
            $this->sessionIndexes[] = trim($X9->textContent);
            Ntp:
        }
        rH8:
    }
    public function getNotOnOrAfter()
    {
        return $this->notOnOrAfter;
    }
    public function setNotOnOrAfter($gB)
    {
        $this->notOnOrAfter = $gB;
    }
    public function isNameIdEncrypted()
    {
        if (!($this->encryptedNameId !== null)) {
            goto kJy;
        }
        return true;
        kJy:
        return false;
    }
    public function encryptNameId(XMLSecurityKey $R2)
    {
        $hT = new DOMDocument();
        $le = $hT->createElement("\162\x6f\x6f\x74");
        $hT->appendChild($le);
        SAML2_Utils::addNameId($le, $this->nameId);
        $T9 = $le->firstChild;
        SAML2_Utils::getContainer()->debugMessage($T9, "\145\156\x63\x72\171\x70\164");
        $QU = new XMLSecEnc();
        $QU->setNode($T9);
        $QU->type = XMLSecEnc::Element;
        $IX = new XMLSecurityKey(XMLSecurityKey::AES128_CBC);
        $IX->generateSessionKey();
        $QU->encryptKey($R2, $IX);
        $this->encryptedNameId = $QU->encryptNode($IX);
        $this->nameId = null;
    }
    public function decryptNameId(XMLSecurityKey $R2, array $bn = array())
    {
        if (!($this->encryptedNameId === null)) {
            goto a6B;
        }
        return;
        a6B:
        $T9 = SAML2_Utils::decryptElement($this->encryptedNameId, $R2, $bn);
        SAML2_Utils::getContainer()->debugMessage($T9, "\x64\145\x63\x72\x79\x70\x74");
        $this->nameId = SAML2_Utils::parseNameId($T9);
        $this->encryptedNameId = null;
    }
    public function getNameId()
    {
        if (!($this->encryptedNameId !== null)) {
            goto Egm;
        }
        throw new Mo_SAML_Invalid_Logout_Request_Exception("\101\x74\164\145\155\160\x74\145\144\x20\x74\x6f\x20\162\145\164\162\151\x65\166\x65\40\x65\x6e\143\162\x79\x70\x74\x65\x64\x20\116\x61\x6d\145\x49\x44\40\167\x69\164\150\157\x75\164\x20\x64\x65\x63\162\x79\x70\164\x69\156\147\40\x69\164\x20\146\151\x72\163\x74\56");
        Egm:
        return $this->nameId;
    }
    public function setNameId($T9)
    {
        $this->nameId = $T9;
    }
    public function getSessionIndexes()
    {
        return $this->sessionIndexes;
    }
    public function setSessionIndexes(array $bM)
    {
        $this->sessionIndexes = $bM;
    }
    public function getSessionIndex()
    {
        if (!empty($this->sessionIndexes)) {
            goto eop;
        }
        return null;
        eop:
        return $this->sessionIndexes[0];
    }
    public function setSessionIndex($X9)
    {
        if (is_null($X9)) {
            goto vKD;
        }
        $this->sessionIndexes = array($X9);
        goto iLN;
        vKD:
        $this->sessionIndexes = array();
        iLN:
    }
    public function getId()
    {
        return $this->id;
    }
    public function setId($pC)
    {
        $this->id = $pC;
    }
    public function getIssueInstant()
    {
        return $this->issueInstant;
    }
    public function setIssueInstant($WC)
    {
        $this->issueInstant = $WC;
    }
    public function getDestination()
    {
        return $this->destination;
    }
    public function setDestination($xF)
    {
        $this->destination = $xF;
    }
    public function getIssuer()
    {
        return $this->issuer;
    }
    public function setIssuer($g5)
    {
        $this->issuer = $g5;
    }
}
