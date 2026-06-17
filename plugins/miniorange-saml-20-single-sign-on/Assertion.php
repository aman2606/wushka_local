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
class SAML2_Assertion
{
    private $id;
    private $issueInstant;
    private $issuer;
    private $nameId;
    private $encryptedNameId;
    private $encryptedAttribute;
    private $encryptionKey;
    private $notBefore;
    private $notOnOrAfter;
    private $validAudiences;
    private $sessionNotOnOrAfter;
    private $sessionIndex;
    private $authnInstant;
    private $authnContextClassRef;
    private $authnContextDecl;
    private $authnContextDeclRef;
    private $AuthenticatingAuthority;
    private $attributes;
    private $nameFormat;
    private $signatureKey;
    private $certificates;
    private $signatureData;
    private $requiredEncAttributes;
    private $SubjectConfirmation;
    private $privateKeyUrl;
    protected $wasSignedAtConstruction = false;
    public function __construct(DOMElement $KD = null, $Jm)
    {
        $this->id = SAMLSPUtilities::generateId();
        $this->issueInstant = SAMLSPUtilities::generateTimestamp();
        $this->issuer = '';
        $this->authnInstant = SAMLSPUtilities::generateTimestamp();
        $this->attributes = array();
        $this->nameFormat = "\165\162\x6e\72\x6f\x61\163\151\163\x3a\x6e\x61\155\145\163\x3a\x74\143\72\123\101\115\114\72\x31\x2e\61\x3a\x6e\x61\x6d\x65\151\x64\x2d\x66\x6f\x72\155\x61\x74\x3a\165\156\x73\x70\145\x63\151\146\x69\145\x64";
        $this->certificates = array();
        $this->AuthenticatingAuthority = array();
        $this->SubjectConfirmation = array();
        if (!($KD === null)) {
            goto fp;
        }
        return;
        fp:
        if (!($KD->localName === "\105\156\x63\x72\171\x70\x74\145\144\x41\x73\163\x65\x72\x74\x69\157\x6e")) {
            goto gS;
        }
        $jr = SAMLSPUtilities::xpQuery($KD, "\x2e\x2f\x78\145\x6e\143\72\105\156\143\162\x79\x70\164\145\x64\104\x61\164\141");
        $Vo = SAMLSPUtilities::xpQuery($KD, "\57\x2f\52\133\154\157\143\141\154\55\x6e\x61\155\x65\50\51\75\x27\105\156\143\x72\x79\x70\164\145\x64\113\x65\x79\47\x5d\57\52\133\x6c\157\143\141\x6c\x2d\x6e\141\x6d\x65\50\51\75\47\x45\156\x63\x72\171\x70\164\151\157\156\x4d\145\164\150\x6f\144\47\135\57\x40\x41\x6c\147\157\162\x69\164\x68\155");
        $lO = $Vo[0]->value;
        $w7 = SAMLSPUtilities::getEncryptionAlgorithm($lO);
        if (count($jr) === 0) {
            goto mT;
        }
        if (count($jr) > 1) {
            goto mp;
        }
        goto x9;
        mT:
        throw new Mo_SAML_Invalid_Assertion_Exception("\115\151\163\x73\151\156\x67\x20\145\156\x63\162\x79\160\x74\145\144\x20\x64\x61\164\141\x20\x69\156\40\x3c\163\141\155\154\x3a\x45\x6e\x63\162\x79\x70\164\x65\x64\x41\163\x73\145\x72\x74\151\x6f\x6e\76\x2e");
        goto x9;
        mp:
        throw new Mo_SAML_Invalid_Assertion_Exception("\115\x6f\162\145\40\x74\150\x61\156\x20\157\x6e\x65\40\x65\x6e\x63\162\x79\160\164\x65\x64\x20\x64\x61\164\141\x20\145\x6c\x65\155\x65\x6e\x74\40\x69\x6e\40\x3c\163\x61\155\x6c\72\105\x6e\143\162\x79\160\164\145\x64\101\x73\163\x65\162\164\x69\x6f\156\x3e\x2e");
        x9:
        $R2 = new XMLSecurityKey($w7, array("\x74\171\160\x65" => "\x70\162\x69\166\141\x74\145"));
        $R2->loadKey($Jm, false);
        $bn = array();
        $Yj = isset($_POST["\x52\x65\x6c\x61\171\x53\x74\x61\x74\145"]) ? $_POST["\x52\145\154\x61\x79\x53\x74\x61\164\145"] : null;
        $KD = SAMLSPUtilities::decryptElement($jr[0], $R2, $bn, null, $Yj);
        gS:
        if ($KD->hasAttribute("\x49\x44")) {
            goto Fm;
        }
        throw new Mo_SAML_Invalid_Assertion_Exception("\x4d\x69\163\163\151\x6e\x67\x20\x49\104\40\141\164\164\162\x69\x62\165\x74\145\x20\x6f\156\x20\123\101\115\114\40\141\163\163\x65\x72\164\x69\157\156\x2e");
        Fm:
        $this->id = $KD->getAttribute("\x49\104");
        if (!($KD->getAttribute("\x56\x65\x72\163\x69\157\156") !== "\x32\x2e\x30")) {
            goto Wl;
        }
        throw new Mo_SAML_Invalid_Assertion_Exception("\x55\156\x73\x75\x70\x70\x6f\x72\x74\145\144\40\166\x65\x72\x73\151\157\x6e\x3a\x20" . esc_html($KD->getAttribute("\x56\145\x72\x73\151\x6f\156")));
        Wl:
        $this->issueInstant = SAMLSPUtilities::xsDateTimeToTimestamp($KD->getAttribute("\x49\163\163\x75\145\111\156\163\x74\141\156\x74"));
        $g5 = SAMLSPUtilities::xpQuery($KD, "\56\x2f\x73\x61\155\x6c\137\141\x73\163\x65\x72\164\x69\157\x6e\72\111\x73\x73\165\x65\162");
        if (!empty($g5)) {
            goto bW;
        }
        throw new Mo_SAML_Invalid_Assertion_Exception("\x4d\151\x73\163\151\156\147\x20\74\163\141\155\154\x3a\111\x73\x73\165\145\x72\76\x20\x69\x6e\x20\141\163\163\x65\x72\x74\x69\x6f\156\x2e");
        bW:
        $this->issuer = trim($g5[0]->textContent);
        $this->parseConditions($KD);
        $this->parseAuthnStatement($KD);
        $this->parseAttributes($KD);
        $this->parseEncryptedAttributes($KD);
        $this->parseSignature($KD);
        $this->parseSubject($KD);
    }
    private function parseSubject(DOMElement $KD)
    {
        $Lx = SAMLSPUtilities::xpQuery($KD, "\x2e\x2f\163\141\x6d\154\137\x61\163\163\x65\x72\x74\151\x6f\x6e\x3a\x53\165\142\152\x65\x63\x74");
        if (empty($Lx)) {
            goto c2;
        }
        if (count($Lx) > 1) {
            goto Z8;
        }
        goto fx;
        c2:
        return;
        goto fx;
        Z8:
        throw new Mo_SAML_Invalid_Assertion_Exception("\x4d\x6f\162\x65\x20\x74\x68\141\x6e\40\x6f\x6e\145\x20\x3c\x73\x61\x6d\x6c\x3a\123\165\142\x6a\145\143\x74\76\40\151\156\x20\74\x73\141\155\x6c\x3a\101\x73\x73\145\162\x74\151\157\x6e\x3e\56");
        fx:
        $Lx = $Lx[0];
        $T9 = SAMLSPUtilities::xpQuery($Lx, "\x2e\57\163\141\x6d\154\137\141\x73\x73\145\x72\x74\x69\157\x6e\72\116\141\x6d\x65\111\104\x20\174\x20\56\57\163\x61\155\x6c\137\141\163\x73\145\x72\x74\151\157\x6e\72\105\156\143\162\171\160\164\145\144\x49\104\x2f\170\x65\x6e\143\72\105\x6e\x63\162\x79\x70\164\145\x64\104\141\164\x61");
        if (empty($T9)) {
            goto Bm;
        }
        if (count($T9) > 1) {
            goto I2;
        }
        goto ad;
        Bm:
        $Rn = $_POST["\122\145\x6c\x61\x79\x53\x74\x61\x74\x65"];
        if ($Rn === "\164\x65\163\164\x56\141\x6c\151\x64\x61\164\145" or $Rn === "\x74\145\x73\x74\x4e\145\x77\103\145\x72\x74\151\x66\x69\x63\x61\164\145") {
            goto V9;
        }
        Mo_Saml_Error_Message::mo_saml_display_error_code_message(Mo_Saml_Error_Codes::$error_codes["\127\x50\123\x41\x4d\x4c\x45\x52\122\60\x30\x32"]);
        goto j1;
        V9:
        Mo_Saml_Error_Message::mo_saml_display_test_config_error(Mo_Saml_Error_Codes::$error_codes["\127\120\123\101\115\114\x45\122\122\x30\60\62"]);
        j1:
        goto ad;
        I2:
        throw new Mo_SAML_Invalid_Assertion_Exception("\x4d\157\162\145\x20\x74\150\x61\156\x20\157\x6e\145\40\74\x73\141\155\154\72\x4e\x61\155\145\111\x44\x3e\40\157\x72\x20\74\163\141\155\154\72\105\x6e\x63\162\x79\160\x74\145\x64\x44\x3e\x20\151\x6e\40\x3c\163\x61\x6d\154\x3a\x53\165\x62\x6a\x65\x63\x74\x3e\56");
        ad:
        $T9 = $T9[0];
        if ($T9->localName === "\x45\156\143\162\171\x70\164\145\144\x44\x61\164\141") {
            goto j4;
        }
        $this->nameId = SAMLSPUtilities::parseNameId($T9);
        goto xB;
        j4:
        $this->encryptedNameId = $T9;
        xB:
    }
    private function parseConditions(DOMElement $KD)
    {
        $Jf = SAMLSPUtilities::xpQuery($KD, "\x2e\57\x73\141\x6d\154\137\x61\x73\x73\145\x72\x74\151\157\x6e\x3a\103\157\156\x64\151\x74\x69\x6f\156\x73");
        if (empty($Jf)) {
            goto u4;
        }
        if (count($Jf) > 1) {
            goto Mj;
        }
        goto j8;
        u4:
        return;
        goto j8;
        Mj:
        throw new Mo_SAML_Invalid_Assertion_Exception("\115\x6f\162\x65\x20\164\x68\141\156\40\157\x6e\145\40\74\163\x61\x6d\154\x3a\x43\157\156\x64\x69\164\151\157\156\x73\x3e\x20\151\156\40\74\x73\x61\155\154\x3a\101\163\x73\x65\162\164\151\157\x6e\76\56");
        j8:
        $Jf = $Jf[0];
        if (!$Jf->hasAttribute("\x4e\157\x74\102\x65\x66\x6f\x72\145")) {
            goto mX;
        }
        $Nd = SAMLSPUtilities::xsDateTimeToTimestamp($Jf->getAttribute("\116\157\164\x42\145\x66\157\x72\145"));
        if (!($this->notBefore === null || $this->notBefore < $Nd)) {
            goto WC;
        }
        $this->notBefore = $Nd;
        WC:
        mX:
        if (!$Jf->hasAttribute("\116\x6f\164\x4f\x6e\117\x72\x41\x66\x74\x65\x72")) {
            goto bD;
        }
        $gB = SAMLSPUtilities::xsDateTimeToTimestamp($Jf->getAttribute("\x4e\157\x74\x4f\156\117\x72\101\x66\x74\145\162"));
        if (!($this->notOnOrAfter === null || $this->notOnOrAfter > $gB)) {
            goto CC;
        }
        $this->notOnOrAfter = $gB;
        CC:
        bD:
        $Fa = $Jf->firstChild;
        P_:
        if (!($Fa !== null)) {
            goto MI;
        }
        if (!$Fa instanceof DOMText) {
            goto FW;
        }
        goto Mu;
        FW:
        if (!($Fa->namespaceURI !== "\165\x72\156\72\x6f\x61\x73\x69\x73\72\156\x61\155\145\163\x3a\x74\143\72\123\101\115\114\72\x32\x2e\x30\x3a\x61\x73\163\x65\x72\x74\x69\x6f\156")) {
            goto OB;
        }
        throw new Mo_SAML_Invalid_Assertion_Exception("\125\x6e\x6b\x6e\157\x77\156\40\156\141\155\145\163\160\x61\143\145\40\157\x66\40\143\157\x6e\144\x69\164\x69\157\x6e\x3a\40" . esc_html(var_export($Fa->namespaceURI, true)));
        OB:
        switch ($Fa->localName) {
            case "\x41\165\x64\151\145\x6e\x63\x65\122\x65\163\x74\162\x69\x63\164\x69\x6f\156":
                $PC = SAMLSPUtilities::extractStrings($Fa, "\x75\162\x6e\x3a\157\x61\x73\x69\x73\x3a\x6e\x61\x6d\x65\x73\x3a\164\143\x3a\x53\x41\115\x4c\72\x32\x2e\60\x3a\141\163\163\x65\x72\x74\x69\x6f\156", "\x41\x75\x64\x69\x65\x6e\x63\x65");
                if ($this->validAudiences === null) {
                    goto E4;
                }
                $this->validAudiences = array_intersect($this->validAudiences, $PC);
                goto nW;
                E4:
                $this->validAudiences = $PC;
                nW:
                goto Fa;
            case "\x4f\156\x65\124\151\x6d\x65\125\x73\x65":
                goto Fa;
            case "\x50\162\157\x78\171\x52\x65\x73\164\162\x69\x63\164\151\157\x6e":
                goto Fa;
            default:
                throw new Mo_SAML_Invalid_Assertion_Exception("\x55\x6e\x6b\x6e\x6f\167\156\x20\x63\x6f\x6e\144\x69\x74\x69\x6f\156\72\40" . esc_html(var_export($Fa->localName, true)));
        }
        SE:
        Fa:
        Mu:
        $Fa = $Fa->nextSibling;
        goto P_;
        MI:
    }
    private function parseAuthnStatement(DOMElement $KD)
    {
        $oW = SAMLSPUtilities::xpQuery($KD, "\x2e\x2f\163\x61\x6d\x6c\x5f\141\x73\163\145\162\164\151\157\156\x3a\101\165\x74\x68\156\123\x74\x61\164\145\155\x65\x6e\x74");
        if (empty($oW)) {
            goto aL;
        }
        if (count($oW) > 1) {
            goto kG;
        }
        goto bi;
        aL:
        $this->authnInstant = null;
        return;
        goto bi;
        kG:
        throw new Mo_SAML_Invalid_Assertion_Exception("\x4d\x6f\162\145\x20\x74\150\141\164\40\x6f\156\x65\40\x3c\x73\x61\x6d\x6c\72\x41\165\x74\150\156\x53\164\x61\x74\145\x6d\145\x6e\x74\76\x20\x69\156\x20\74\x73\141\155\x6c\x3a\x41\163\163\145\162\164\151\x6f\156\x3e\40\x6e\157\x74\40\163\165\160\160\157\162\x74\145\144\56");
        bi:
        $h1 = $oW[0];
        if ($h1->hasAttribute("\101\x75\x74\x68\156\x49\x6e\x73\164\x61\156\x74")) {
            goto pO;
        }
        throw new Mo_SAML_Invalid_Assertion_Exception("\x4d\x69\163\x73\151\156\x67\x20\x72\x65\161\x75\x69\162\x65\144\x20\101\165\x74\150\156\111\156\163\x74\x61\x6e\x74\x20\x61\x74\164\162\x69\x62\x75\x74\145\x20\x6f\156\x20\x3c\x73\141\155\x6c\72\101\165\164\x68\156\123\x74\x61\164\145\155\145\156\164\x3e\x2e");
        pO:
        $this->authnInstant = SAMLSPUtilities::xsDateTimeToTimestamp($h1->getAttribute("\x41\x75\x74\150\156\x49\x6e\x73\x74\x61\156\x74"));
        if (!$h1->hasAttribute("\x53\x65\163\163\151\x6f\156\116\x6f\164\117\x6e\117\162\x41\x66\164\x65\162")) {
            goto K6;
        }
        $this->sessionNotOnOrAfter = SAMLSPUtilities::xsDateTimeToTimestamp($h1->getAttribute("\x53\x65\163\x73\x69\157\x6e\116\157\164\117\156\117\x72\x41\x66\x74\x65\x72"));
        K6:
        if (!$h1->hasAttribute("\x53\145\x73\163\x69\157\x6e\x49\156\144\145\170")) {
            goto iT;
        }
        $this->sessionIndex = $h1->getAttribute("\123\x65\163\163\151\157\x6e\111\x6e\x64\145\x78");
        iT:
        $this->parseAuthnContext($h1);
    }
    private function parseAuthnContext(DOMElement $rS)
    {
        $F4 = SAMLSPUtilities::xpQuery($rS, "\56\x2f\163\141\155\154\x5f\x61\163\163\145\162\164\x69\157\x6e\x3a\101\165\x74\x68\156\103\157\156\164\x65\170\164");
        if (count($F4) > 1) {
            goto Op;
        }
        if (empty($F4)) {
            goto ae;
        }
        goto ve;
        Op:
        throw new Mo_SAML_Invalid_Assertion_Exception("\115\157\162\x65\40\164\x68\x61\x6e\40\157\156\145\x20\74\x73\x61\155\154\x3a\x41\x75\164\x68\156\103\157\x6e\164\x65\x78\164\x3e\x20\x69\156\x20\74\x73\x61\155\154\72\101\x75\164\150\x6e\x53\x74\141\x74\x65\x6d\145\156\164\x3e\x2e");
        goto ve;
        ae:
        throw new Mo_SAML_Invalid_Assertion_Exception("\115\x69\163\x73\151\x6e\147\40\x72\x65\161\x75\x69\162\145\144\x20\x3c\163\x61\155\x6c\x3a\x41\x75\164\x68\156\x43\157\x6e\164\x65\x78\x74\x3e\x20\151\156\40\74\x73\x61\155\154\x3a\x41\x75\x74\150\x6e\123\164\x61\164\145\155\x65\156\164\x3e\56");
        ve:
        $mD = $F4[0];
        $Ot = SAMLSPUtilities::xpQuery($mD, "\x2e\x2f\163\x61\155\x6c\137\141\163\163\x65\x72\164\151\157\x6e\x3a\101\165\164\x68\156\x43\x6f\156\164\x65\x78\x74\x44\x65\x63\154\x52\x65\146");
        if (count($Ot) > 1) {
            goto V5;
        }
        if (count($Ot) === 1) {
            goto p5;
        }
        goto tW;
        V5:
        throw new Mo_SAML_Invalid_Assertion_Exception("\x4d\157\x72\x65\40\x74\x68\141\x6e\x20\x6f\156\145\x20\74\163\141\155\x6c\72\101\165\164\150\x6e\103\x6f\x6e\x74\145\x78\x74\104\145\143\154\x52\145\146\x3e\x20\146\x6f\x75\x6e\x64\77");
        goto tW;
        p5:
        $this->setAuthnContextDeclRef(trim($Ot[0]->textContent));
        tW:
        $hM = SAMLSPUtilities::xpQuery($mD, "\x2e\x2f\163\x61\x6d\x6c\x5f\x61\163\x73\145\x72\164\x69\x6f\x6e\x3a\x41\x75\x74\x68\x6e\103\x6f\156\x74\145\170\x74\x44\x65\143\x6c");
        if (count($hM) > 1) {
            goto Hy;
        }
        if (count($hM) === 1) {
            goto TK;
        }
        goto W0;
        Hy:
        throw new Mo_SAML_Invalid_Assertion_Exception("\115\x6f\162\145\x20\164\x68\141\x6e\x20\x6f\x6e\x65\x20\x3c\x73\x61\155\154\72\x41\165\x74\150\x6e\x43\157\156\x74\x65\x78\164\104\145\143\154\x3e\40\x66\x6f\165\x6e\x64\x3f");
        goto W0;
        TK:
        $this->setAuthnContextDecl(new SAML2_XML_Chunk($hM[0]));
        W0:
        $mC = SAMLSPUtilities::xpQuery($mD, "\x2e\57\x73\141\x6d\x6c\x5f\141\x73\x73\145\x72\164\151\157\156\x3a\x41\x75\164\150\156\x43\157\156\x74\145\170\164\103\x6c\x61\x73\163\122\x65\146");
        if (count($mC) > 1) {
            goto ig;
        }
        if (count($mC) === 1) {
            goto kL;
        }
        goto Sk;
        ig:
        throw new Mo_SAML_Invalid_Assertion_Exception("\115\x6f\162\145\x20\164\x68\141\x6e\x20\157\x6e\x65\x20\74\163\x61\x6d\x6c\x3a\101\x75\x74\150\x6e\103\x6f\156\x74\145\170\164\x43\x6c\x61\163\x73\122\145\146\76\40\x69\x6e\40\74\163\x61\155\154\72\101\165\164\150\x6e\103\157\156\164\145\170\164\76\x2e");
        goto Sk;
        kL:
        $this->setAuthnContextClassRef(trim($mC[0]->textContent));
        Sk:
        if (!(empty($this->authnContextClassRef) && empty($this->authnContextDecl) && empty($this->authnContextDeclRef))) {
            goto ev;
        }
        throw new Mo_SAML_Invalid_Assertion_Exception("\x4d\x69\x73\163\x69\156\147\x20\145\151\164\150\145\x72\40\x3c\x73\x61\x6d\154\72\x41\x75\164\x68\156\x43\x6f\x6e\x74\x65\x78\164\103\x6c\141\x73\x73\122\x65\x66\76\40\x6f\162\40\x3c\163\x61\x6d\154\x3a\101\x75\164\x68\156\103\x6f\x6e\164\x65\170\x74\x44\x65\143\x6c\122\145\146\x3e\40\157\x72\x20\74\163\141\155\154\x3a\x41\165\x74\x68\x6e\103\157\x6e\x74\145\170\164\x44\x65\143\x6c\76");
        ev:
        $this->AuthenticatingAuthority = SAMLSPUtilities::extractStrings($mD, "\165\x72\156\x3a\x6f\141\163\x69\x73\x3a\156\141\155\x65\x73\x3a\x74\143\x3a\x53\x41\115\114\72\62\x2e\60\72\141\163\x73\x65\x72\164\151\x6f\x6e", "\101\165\164\x68\x65\x6e\164\151\x63\141\x74\x69\x6e\x67\x41\165\x74\150\x6f\x72\151\x74\171");
    }
    private function parseAttributes(DOMElement $KD)
    {
        $Ly = true;
        $qZ = SAMLSPUtilities::xpQuery($KD, "\56\x2f\163\141\x6d\154\137\x61\163\x73\x65\162\164\x69\x6f\x6e\72\x41\x74\x74\x72\151\142\165\164\x65\123\x74\141\164\x65\x6d\x65\156\164\x2f\163\x61\155\x6c\x5f\141\163\x73\145\162\164\x69\x6f\156\72\x41\x74\164\x72\x69\142\165\164\x65");
        foreach ($qZ as $pn) {
            if ($pn->hasAttribute("\116\x61\x6d\x65")) {
                goto Wx;
            }
            throw new Mo_SAML_Invalid_Assertion_Exception("\115\x69\163\x73\x69\156\x67\40\156\141\155\145\x20\157\156\x20\x3c\163\141\155\x6c\72\101\x74\x74\x72\x69\142\165\x74\145\x3e\x20\x65\154\x65\155\x65\x6e\164\56");
            Wx:
            $or = $pn->getAttribute("\x4e\141\x6d\x65");
            if ($pn->hasAttribute("\x4e\x61\x6d\145\106\x6f\x72\x6d\141\164")) {
                goto RH;
            }
            $qC = "\165\x72\x6e\x3a\x6f\x61\x73\x69\x73\72\156\x61\x6d\145\163\x3a\x74\143\72\123\101\x4d\x4c\x3a\61\x2e\61\x3a\x6e\141\x6d\x65\151\x64\55\x66\x6f\162\x6d\141\164\72\165\x6e\163\160\x65\143\x69\x66\x69\x65\144";
            goto s5;
            RH:
            $qC = $pn->getAttribute("\x4e\x61\x6d\x65\106\157\x72\155\141\164");
            s5:
            if ($Ly) {
                goto Kr;
            }
            if ($this->nameFormat !== $qC) {
                goto m8;
            }
            goto vw;
            Kr:
            $this->nameFormat = $qC;
            $Ly = false;
            goto vw;
            m8:
            $this->nameFormat = "\x75\162\156\x3a\157\x61\x73\x69\163\72\156\x61\155\145\x73\x3a\164\143\x3a\123\101\x4d\114\72\61\x2e\61\72\x6e\x61\x6d\x65\151\x64\x2d\146\157\x72\x6d\x61\164\x3a\165\156\163\160\x65\x63\x69\x66\x69\x65\144";
            vw:
            if (!empty($this->attributes[$or])) {
                goto Rt;
            }
            $this->attributes[$or] = array();
            Rt:
            $Tr = SAMLSPUtilities::xpQuery($pn, "\56\x2f\x73\x61\x6d\154\x5f\141\163\x73\145\x72\x74\151\157\x6e\x3a\x41\x74\164\x72\x69\x62\x75\164\145\x56\141\x6c\165\x65");
            foreach ($Tr as $EB) {
                $this->attributes[$or][] = trim($EB->textContent);
                Dp:
            }
            jO:
            dG:
        }
        vb:
    }
    private function parseEncryptedAttributes(DOMElement $KD)
    {
        $this->encryptedAttribute = SAMLSPUtilities::xpQuery($KD, "\56\57\x73\x61\155\154\137\141\163\163\145\x72\x74\x69\x6f\156\x3a\x41\164\x74\162\151\x62\x75\164\145\123\x74\x61\164\145\x6d\145\x6e\x74\57\163\x61\155\x6c\137\141\x73\163\x65\x72\164\151\157\156\72\105\x6e\x63\162\x79\x70\x74\145\144\101\x74\x74\x72\151\x62\x75\x74\145");
    }
    private function parseSignature(DOMElement $KD)
    {
        $u2 = SAMLSPUtilities::validateElement($KD);
        if (!($u2 !== false)) {
            goto lf;
        }
        $this->wasSignedAtConstruction = true;
        $this->certificates = $u2["\x43\x65\162\164\x69\x66\x69\143\x61\164\x65\x73"];
        $this->signatureData = $u2;
        lf:
    }
    public function validate(XMLSecurityKey $R2)
    {
        if (!($this->signatureData === null)) {
            goto O7;
        }
        return false;
        O7:
        SAMLSPUtilities::validateSignature($this->signatureData, $R2);
        return true;
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
    public function getIssuer()
    {
        return $this->issuer;
    }
    public function setIssuer($g5)
    {
        $this->issuer = $g5;
    }
    public function getNameId()
    {
        if (!($this->encryptedNameId !== null)) {
            goto Zi;
        }
        throw new Mo_SAML_Invalid_Assertion_Exception("\101\x74\164\x65\155\160\x74\145\x64\40\164\157\40\162\145\x74\162\151\145\166\x65\x20\x65\x6e\x63\x72\171\160\x74\x65\144\40\116\x61\x6d\x65\x49\x44\40\167\151\x74\150\157\x75\x74\40\x64\145\x63\x72\171\x70\164\x69\156\x67\x20\151\x74\x20\x66\151\x72\163\164\x2e");
        Zi:
        return $this->nameId;
    }
    public function setNameId($T9)
    {
        $this->nameId = $T9;
    }
    public function isNameIdEncrypted()
    {
        if (!($this->encryptedNameId !== null)) {
            goto Nw;
        }
        return true;
        Nw:
        return false;
    }
    public function encryptNameId(XMLSecurityKey $R2)
    {
        $hT = new DOMDocument();
        $le = $hT->createElement("\162\x6f\x6f\164");
        $hT->appendChild($le);
        SAMLSPUtilities::addNameId($le, $this->nameId);
        $T9 = $le->firstChild;
        SAMLSPUtilities::getContainer()->debugMessage($T9, "\145\156\143\162\x79\160\x74");
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
            goto pb;
        }
        return;
        pb:
        $T9 = SAMLSPUtilities::decryptElement($this->encryptedNameId, $R2, $bn);
        SAMLSPUtilities::getContainer()->debugMessage($T9, "\144\145\x63\x72\171\x70\164");
        $this->nameId = SAMLSPUtilities::parseNameId($T9);
        $this->encryptedNameId = null;
    }
    public function decryptAttributes(XMLSecurityKey $R2, array $bn = array())
    {
        if (!($this->encryptedAttribute === null)) {
            goto e6;
        }
        return;
        e6:
        $Ly = true;
        $qZ = $this->encryptedAttribute;
        foreach ($qZ as $zf) {
            $pn = SAMLSPUtilities::decryptElement($zf->getElementsByTagName("\105\x6e\x63\162\x79\x70\x74\x65\x64\x44\141\164\x61")->item(0), $R2, $bn);
            if ($pn->hasAttribute("\x4e\x61\x6d\145")) {
                goto wL;
            }
            throw new Mo_SAML_Invalid_Assertion_Exception("\115\x69\163\x73\x69\x6e\147\x20\x6e\141\x6d\x65\40\157\156\40\x3c\163\141\x6d\x6c\x3a\x41\x74\x74\162\151\142\x75\164\145\76\40\x65\154\145\x6d\145\156\x74\x2e");
            wL:
            $or = $pn->getAttribute("\x4e\141\155\x65");
            if ($pn->hasAttribute("\116\141\155\145\x46\157\162\155\141\164")) {
                goto PU;
            }
            $qC = "\x75\162\x6e\72\x6f\141\163\x69\x73\72\156\141\x6d\145\x73\72\164\143\72\123\x41\x4d\x4c\x3a\62\56\x30\72\141\164\x74\162\156\141\155\145\55\x66\157\162\x6d\x61\x74\x3a\165\156\163\x70\145\x63\151\146\151\145\x64";
            goto x6;
            PU:
            $qC = $pn->getAttribute("\116\x61\x6d\145\106\x6f\x72\x6d\141\x74");
            x6:
            if ($Ly) {
                goto nK;
            }
            if ($this->nameFormat !== $qC) {
                goto wn;
            }
            goto Aj;
            nK:
            $this->nameFormat = $qC;
            $Ly = false;
            goto Aj;
            wn:
            $this->nameFormat = "\165\162\156\72\157\x61\x73\151\163\72\x6e\x61\155\145\163\x3a\x74\143\72\123\101\115\114\72\x32\x2e\x30\72\x61\x74\164\162\x6e\141\155\x65\55\x66\157\162\x6d\141\x74\72\165\x6e\x73\160\145\143\151\146\151\x65\144";
            Aj:
            if (!empty($this->attributes[$or])) {
                goto YM;
            }
            $this->attributes[$or] = array();
            YM:
            $Tr = SAMLSPUtilities::xpQuery($pn, "\56\57\x73\x61\155\x6c\x5f\x61\x73\163\145\x72\164\x69\x6f\156\72\x41\x74\x74\x72\151\142\x75\164\145\126\141\154\165\x65");
            foreach ($Tr as $EB) {
                $this->attributes[$or][] = trim($EB->textContent);
                o9:
            }
            zV:
            sG:
        }
        v_:
    }
    public function getNotBefore()
    {
        return $this->notBefore;
    }
    public function setNotBefore($Nd)
    {
        $this->notBefore = $Nd;
    }
    public function getNotOnOrAfter()
    {
        return $this->notOnOrAfter;
    }
    public function setNotOnOrAfter($gB)
    {
        $this->notOnOrAfter = $gB;
    }
    public function setEncryptedAttributes($sO)
    {
        $this->requiredEncAttributes = $sO;
    }
    public function getValidAudiences()
    {
        return $this->validAudiences;
    }
    public function setValidAudiences(array $J_ = null)
    {
        $this->validAudiences = $J_;
    }
    public function getAuthnInstant()
    {
        return $this->authnInstant;
    }
    public function setAuthnInstant($o5)
    {
        $this->authnInstant = $o5;
    }
    public function getSessionNotOnOrAfter()
    {
        return $this->sessionNotOnOrAfter;
    }
    public function setSessionNotOnOrAfter($J7)
    {
        $this->sessionNotOnOrAfter = $J7;
    }
    public function getSessionIndex()
    {
        return $this->sessionIndex;
    }
    public function setSessionIndex($X9)
    {
        $this->sessionIndex = $X9;
    }
    public function getAuthnContext()
    {
        if (empty($this->authnContextClassRef)) {
            goto CP;
        }
        return $this->authnContextClassRef;
        CP:
        if (empty($this->authnContextDeclRef)) {
            goto pG;
        }
        return $this->authnContextDeclRef;
        pG:
        return null;
    }
    public function setAuthnContext($sI)
    {
        $this->setAuthnContextClassRef($sI);
    }
    public function getAuthnContextClassRef()
    {
        return $this->authnContextClassRef;
    }
    public function setAuthnContextClassRef($en)
    {
        $this->authnContextClassRef = $en;
    }
    public function setAuthnContextDecl(SAML2_XML_Chunk $Qr)
    {
        if (empty($this->authnContextDeclRef)) {
            goto da;
        }
        throw new Mo_SAML_Invalid_Assertion_Exception("\x41\165\x74\x68\156\103\x6f\x6e\164\145\170\164\x44\145\x63\154\x52\145\146\x20\151\163\40\141\x6c\162\x65\x61\x64\171\40\162\x65\x67\151\163\164\145\x72\x65\x64\41\x20\x4d\141\x79\40\157\156\154\171\40\x68\141\166\x65\x20\145\151\x74\x68\145\x72\40\141\40\104\x65\x63\x6c\x20\x6f\162\40\141\40\x44\145\x63\x6c\x52\x65\x66\54\40\156\x6f\x74\x20\142\157\x74\150\x21");
        da:
        $this->authnContextDecl = $Qr;
    }
    public function getAuthnContextDecl()
    {
        return $this->authnContextDecl;
    }
    public function setAuthnContextDeclRef($EC)
    {
        if (empty($this->authnContextDecl)) {
            goto Wb;
        }
        throw new Mo_SAML_Invalid_Assertion_Exception("\x41\165\164\150\x6e\x43\x6f\x6e\164\x65\x78\x74\x44\145\143\x6c\40\x69\x73\x20\141\x6c\x72\x65\x61\144\x79\40\162\x65\147\151\x73\x74\145\x72\145\144\x21\40\115\x61\x79\40\x6f\156\x6c\171\40\150\141\x76\x65\40\x65\151\x74\x68\145\x72\40\141\x20\x44\x65\143\154\x20\x6f\162\40\x61\x20\x44\x65\x63\154\x52\145\146\54\x20\x6e\x6f\x74\40\x62\157\164\150\41");
        Wb:
        $this->authnContextDeclRef = $EC;
    }
    public function getAuthnContextDeclRef()
    {
        return $this->authnContextDeclRef;
    }
    public function getAuthenticatingAuthority()
    {
        return $this->AuthenticatingAuthority;
    }
    public function setAuthenticatingAuthority($WG)
    {
        $this->AuthenticatingAuthority = $WG;
    }
    public function getAttributes()
    {
        return $this->attributes;
    }
    public function setAttributes(array $qZ)
    {
        $this->attributes = $qZ;
    }
    public function getAttributeNameFormat()
    {
        return $this->nameFormat;
    }
    public function setAttributeNameFormat($qC)
    {
        $this->nameFormat = $qC;
    }
    public function getSubjectConfirmation()
    {
        return $this->SubjectConfirmation;
    }
    public function setSubjectConfirmation(array $KS)
    {
        $this->SubjectConfirmation = $KS;
    }
    public function getSignatureKey()
    {
        return $this->signatureKey;
    }
    public function setSignatureKey(XMLsecurityKey $VL = null)
    {
        $this->signatureKey = $VL;
    }
    public function getEncryptionKey()
    {
        return $this->encryptionKey;
    }
    public function setEncryptionKey(XMLSecurityKey $KV = null)
    {
        $this->encryptionKey = $KV;
    }
    public function setCertificates(array $Xi)
    {
        $this->certificates = $Xi;
    }
    public function getCertificates()
    {
        return $this->certificates;
    }
    public function getSignatureData()
    {
        return $this->signatureData;
    }
    public function getWasSignedAtConstruction()
    {
        return $this->wasSignedAtConstruction;
    }
    public function toXML(DOMNode $Bh = null)
    {
        if ($Bh === null) {
            goto YK;
        }
        $nI = $Bh->ownerDocument;
        goto mH;
        YK:
        $nI = new DOMDocument();
        $Bh = $nI;
        mH:
        $le = $nI->createElementNS("\165\162\x6e\x3a\x6f\x61\x73\x69\163\72\156\141\155\145\x73\x3a\x74\x63\72\123\101\x4d\x4c\72\x32\56\60\72\x61\163\163\145\162\x74\151\x6f\x6e", "\x73\141\155\154\x3a" . "\101\x73\x73\x65\x72\x74\151\157\x6e");
        $Bh->appendChild($le);
        $le->setAttributeNS("\x75\x72\156\72\x6f\x61\x73\x69\x73\x3a\x6e\x61\155\145\163\72\164\x63\72\123\x41\115\114\72\62\56\x30\x3a\x70\x72\157\x74\x6f\143\157\154", "\x73\x61\x6d\154\x70\x3a\164\155\160", "\164\155\160");
        $le->removeAttributeNS("\x75\x72\x6e\72\x6f\x61\163\x69\x73\72\156\141\155\x65\163\72\x74\143\72\123\101\x4d\x4c\x3a\x32\x2e\x30\x3a\x70\x72\157\164\x6f\x63\157\x6c", "\164\x6d\x70");
        $le->setAttributeNS("\x68\164\x74\x70\x3a\57\57\167\x77\x77\x2e\167\63\x2e\157\162\147\x2f\62\x30\x30\61\x2f\130\x4d\x4c\123\143\x68\x65\x6d\141\x2d\x69\156\163\x74\141\x6e\x63\x65", "\170\163\151\72\x74\155\x70", "\x74\x6d\160");
        $le->removeAttributeNS("\150\x74\x74\x70\72\x2f\57\x77\x77\x77\56\167\63\56\x6f\162\147\x2f\62\x30\x30\61\x2f\x58\x4d\x4c\123\143\150\145\155\141\x2d\x69\156\x73\x74\141\156\143\x65", "\164\155\160");
        $le->setAttributeNS("\x68\x74\164\160\x3a\x2f\x2f\x77\167\167\x2e\x77\63\x2e\x6f\x72\147\57\x32\60\60\x31\57\x58\x4d\x4c\123\143\150\145\x6d\141", "\170\x73\x3a\x74\155\160", "\x74\x6d\160");
        $le->removeAttributeNS("\150\x74\x74\160\x3a\57\57\x77\x77\x77\x2e\167\63\56\x6f\162\x67\57\62\x30\60\61\57\x58\115\114\x53\143\150\145\x6d\x61", "\164\155\160");
        $le->setAttribute("\111\x44", $this->id);
        $le->setAttribute("\126\x65\x72\163\x69\x6f\156", "\62\x2e\x30");
        $le->setAttribute("\111\163\x73\x75\145\x49\156\163\x74\x61\156\164", gmdate("\131\x2d\155\x2d\x64\134\x54\110\x3a\x69\72\163\x5c\x5a", $this->issueInstant));
        $g5 = SAMLSPUtilities::addString($le, "\x75\162\156\72\157\x61\x73\x69\163\72\x6e\x61\x6d\x65\163\72\164\143\72\123\101\115\x4c\72\62\56\60\x3a\x61\163\163\145\162\164\151\157\x6e", "\163\141\155\154\x3a\x49\163\x73\x75\x65\x72", $this->issuer);
        $this->addSubject($le);
        $this->addConditions($le);
        $this->addAuthnStatement($le);
        if ($this->requiredEncAttributes == false) {
            goto d0;
        }
        $this->addEncryptedAttributeStatement($le);
        goto hs;
        d0:
        $this->addAttributeStatement($le);
        hs:
        if (!($this->signatureKey !== null)) {
            goto b9;
        }
        SAMLSPUtilities::insertSignature($this->signatureKey, $this->certificates, $le, $g5->nextSibling);
        b9:
        return $le;
    }
    private function addSubject(DOMElement $le)
    {
        if (!($this->nameId === null && $this->encryptedNameId === null)) {
            goto rC;
        }
        return;
        rC:
        $Lx = $le->ownerDocument->createElementNS("\x75\162\x6e\72\157\141\163\x69\163\72\x6e\x61\x6d\145\x73\72\164\x63\x3a\x53\x41\x4d\114\x3a\x32\x2e\x30\72\x61\x73\163\145\x72\164\151\157\x6e", "\x73\x61\x6d\x6c\72\123\x75\142\152\x65\x63\x74");
        $le->appendChild($Lx);
        if ($this->encryptedNameId === null) {
            goto SJ;
        }
        $q6 = $Lx->ownerDocument->createElementNS("\165\x72\x6e\72\157\x61\x73\x69\x73\x3a\x6e\x61\x6d\145\163\72\x74\x63\72\x53\x41\115\114\72\62\x2e\60\72\x61\163\163\145\162\164\x69\157\156", "\163\x61\x6d\154\72" . "\105\156\143\162\171\160\x74\145\x64\111\x44");
        $Lx->appendChild($q6);
        $q6->appendChild($Lx->ownerDocument->importNode($this->encryptedNameId, true));
        goto h_;
        SJ:
        SAMLSPUtilities::addNameId($Lx, $this->nameId);
        h_:
        foreach ($this->SubjectConfirmation as $sD) {
            $sD->toXML($Lx);
            tf:
        }
        sx:
    }
    private function addConditions(DOMElement $le)
    {
        $nI = $le->ownerDocument;
        $Jf = $nI->createElementNS("\x75\x72\x6e\72\x6f\x61\x73\151\163\x3a\156\x61\155\x65\x73\x3a\x74\x63\72\123\x41\115\114\72\62\x2e\x30\x3a\x61\x73\163\145\x72\164\x69\x6f\x6e", "\163\141\x6d\154\72\x43\157\156\144\151\x74\x69\157\x6e\163");
        $le->appendChild($Jf);
        if (!($this->notBefore !== null)) {
            goto pA;
        }
        $Jf->setAttribute("\x4e\157\x74\x42\x65\x66\157\162\145", gmdate("\x59\55\155\55\x64\x5c\124\x48\x3a\151\72\163\134\x5a", $this->notBefore));
        pA:
        if (!($this->notOnOrAfter !== null)) {
            goto dy;
        }
        $Jf->setAttribute("\x4e\157\164\117\x6e\117\162\101\146\x74\145\162", gmdate("\131\x2d\155\55\x64\134\124\x48\x3a\151\72\x73\134\x5a", $this->notOnOrAfter));
        dy:
        if (!($this->validAudiences !== null)) {
            goto hY;
        }
        $XT = $nI->createElementNS("\x75\162\x6e\x3a\157\x61\163\x69\x73\x3a\156\141\x6d\145\x73\x3a\x74\143\72\x53\101\x4d\x4c\72\62\x2e\60\72\141\163\x73\145\162\164\x69\x6f\x6e", "\163\x61\155\154\x3a\101\x75\144\x69\145\x6e\x63\145\x52\145\x73\x74\162\151\143\x74\x69\157\156");
        $Jf->appendChild($XT);
        SAMLSPUtilities::addStrings($XT, "\165\x72\x6e\72\157\141\x73\x69\x73\72\156\x61\x6d\145\x73\72\164\143\72\x53\101\115\x4c\72\62\x2e\60\72\141\163\x73\x65\x72\x74\151\x6f\x6e", "\x73\x61\155\154\x3a\101\x75\x64\151\145\156\x63\145", false, $this->validAudiences);
        hY:
    }
    private function addAuthnStatement(DOMElement $le)
    {
        if (!($this->authnInstant === null || $this->authnContextClassRef === null && $this->authnContextDecl === null && $this->authnContextDeclRef === null)) {
            goto Kc;
        }
        return;
        Kc:
        $nI = $le->ownerDocument;
        $rS = $nI->createElementNS("\165\162\156\72\x6f\x61\x73\151\163\x3a\156\x61\x6d\x65\163\72\x74\x63\x3a\x53\x41\x4d\114\72\62\56\60\x3a\141\163\163\145\162\x74\x69\157\156", "\x73\141\x6d\154\72\x41\165\164\x68\156\x53\x74\x61\164\x65\x6d\x65\x6e\164");
        $le->appendChild($rS);
        $rS->setAttribute("\x41\165\x74\x68\x6e\111\156\x73\x74\141\156\x74", gmdate("\x59\x2d\x6d\x2d\144\134\x54\x48\x3a\x69\72\163\134\132", $this->authnInstant));
        if (!($this->sessionNotOnOrAfter !== null)) {
            goto hM;
        }
        $rS->setAttribute("\x53\x65\163\163\151\x6f\x6e\x4e\157\164\117\156\117\x72\x41\146\x74\145\162", gmdate("\131\55\155\x2d\144\x5c\x54\110\72\151\72\163\134\132", $this->sessionNotOnOrAfter));
        hM:
        if (!($this->sessionIndex !== null)) {
            goto W9;
        }
        $rS->setAttribute("\x53\x65\x73\x73\x69\x6f\x6e\111\156\144\145\x78", $this->sessionIndex);
        W9:
        $mD = $nI->createElementNS("\165\162\x6e\72\x6f\141\x73\x69\x73\72\156\x61\x6d\x65\x73\x3a\164\x63\72\123\101\x4d\x4c\x3a\62\56\60\72\x61\x73\x73\145\x72\x74\x69\x6f\156", "\x73\x61\x6d\x6c\x3a\x41\165\x74\x68\x6e\x43\157\156\164\145\170\x74");
        $rS->appendChild($mD);
        if (empty($this->authnContextClassRef)) {
            goto jV;
        }
        SAMLSPUtilities::addString($mD, "\x75\162\156\x3a\x6f\x61\x73\x69\x73\x3a\x6e\141\x6d\x65\163\x3a\164\143\72\x53\101\x4d\x4c\72\x32\x2e\60\x3a\141\163\163\145\x72\164\x69\157\x6e", "\x73\141\155\154\72\101\x75\164\x68\156\103\157\156\164\x65\170\164\103\x6c\x61\x73\163\122\x65\x66", $this->authnContextClassRef);
        jV:
        if (empty($this->authnContextDecl)) {
            goto Y3;
        }
        $this->authnContextDecl->toXML($mD);
        Y3:
        if (empty($this->authnContextDeclRef)) {
            goto ln;
        }
        SAMLSPUtilities::addString($mD, "\x75\162\x6e\x3a\x6f\x61\163\151\163\72\x6e\141\155\145\x73\x3a\164\x63\x3a\123\x41\115\114\72\x32\56\x30\72\141\163\163\145\x72\164\x69\x6f\x6e", "\x73\141\155\x6c\x3a\x41\165\x74\x68\x6e\x43\x6f\156\164\x65\170\164\104\145\143\x6c\122\x65\146", $this->authnContextDeclRef);
        ln:
        SAMLSPUtilities::addStrings($mD, "\x75\162\x6e\72\157\141\x73\151\x73\72\x6e\x61\x6d\x65\x73\72\164\x63\x3a\123\x41\115\114\x3a\62\56\60\72\x61\x73\x73\145\x72\x74\151\x6f\156", "\x73\141\155\154\x3a\x41\165\x74\x68\x65\x6e\164\151\x63\x61\x74\x69\x6e\x67\x41\x75\x74\150\157\x72\151\164\x79", false, $this->AuthenticatingAuthority);
    }
    private function addAttributeStatement(DOMElement $le)
    {
        if (!empty($this->attributes)) {
            goto i3;
        }
        return;
        i3:
        $nI = $le->ownerDocument;
        $jL = $nI->createElementNS("\165\x72\x6e\72\x6f\141\163\x69\163\72\156\141\x6d\x65\x73\72\x74\143\x3a\x53\x41\x4d\114\72\62\x2e\60\72\141\163\x73\145\x72\x74\151\157\x6e", "\x73\x61\155\x6c\72\101\164\x74\x72\x69\142\165\164\145\123\x74\141\164\145\x6d\x65\156\164");
        $le->appendChild($jL);
        foreach ($this->attributes as $or => $Tr) {
            $pn = $nI->createElementNS("\x75\x72\156\x3a\157\141\x73\x69\163\x3a\x6e\x61\155\145\163\72\x74\x63\x3a\123\101\x4d\114\72\x32\56\x30\x3a\x61\163\163\x65\162\164\x69\157\156", "\163\141\155\x6c\x3a\101\x74\x74\162\151\142\165\164\x65");
            $jL->appendChild($pn);
            $pn->setAttribute("\x4e\x61\155\145", $or);
            if (!($this->nameFormat !== "\165\x72\x6e\72\157\x61\163\x69\x73\x3a\x6e\141\x6d\145\163\72\164\x63\x3a\123\x41\115\114\x3a\x32\56\x30\72\141\164\x74\162\156\141\x6d\145\x2d\146\157\162\155\x61\x74\x3a\165\x6e\x73\x70\145\x63\151\146\151\145\144")) {
                goto IR;
            }
            $pn->setAttribute("\x4e\141\155\145\x46\x6f\x72\155\141\x74", $this->nameFormat);
            IR:
            foreach ($Tr as $EB) {
                if (is_string($EB)) {
                    goto cw;
                }
                if (is_int($EB)) {
                    goto ld;
                }
                $Gf = null;
                goto VT;
                cw:
                $Gf = "\170\x73\72\163\164\162\151\x6e\147";
                goto VT;
                ld:
                $Gf = "\x78\163\72\x69\x6e\x74\145\147\x65\162";
                VT:
                $W2 = $nI->createElementNS("\x75\x72\156\x3a\157\x61\163\x69\163\72\x6e\141\155\x65\x73\72\x74\143\72\123\x41\115\x4c\72\62\56\60\72\141\163\x73\145\162\164\x69\x6f\x6e", "\163\141\155\154\x3a\x41\164\x74\162\151\142\165\164\145\126\x61\x6c\x75\145");
                $pn->appendChild($W2);
                if (!($Gf !== null)) {
                    goto DW;
                }
                $W2->setAttributeNS("\x68\x74\x74\160\x3a\57\x2f\167\x77\167\x2e\x77\63\x2e\x6f\x72\x67\57\62\60\60\61\x2f\x58\x4d\x4c\123\143\150\145\155\141\x2d\x69\156\163\x74\x61\x6e\143\145", "\x78\x73\151\x3a\164\x79\160\x65", $Gf);
                DW:
                if (!is_null($EB)) {
                    goto w0;
                }
                $W2->setAttributeNS("\x68\x74\164\160\72\57\57\x77\167\x77\x2e\167\63\56\157\162\x67\x2f\x32\x30\60\61\57\130\x4d\x4c\123\143\150\145\155\141\55\x69\x6e\163\x74\x61\x6e\143\145", "\170\x73\151\x3a\156\x69\x6c", "\164\x72\x75\x65");
                w0:
                if ($EB instanceof DOMNodeList) {
                    goto wN;
                }
                $W2->appendChild($nI->createTextNode($EB));
                goto Rn;
                wN:
                $Ev = 0;
                m2:
                if (!($Ev < $EB->length)) {
                    goto my;
                }
                $Fa = $nI->importNode($EB->item($Ev), true);
                $W2->appendChild($Fa);
                bP:
                $Ev++;
                goto m2;
                my:
                Rn:
                X8:
            }
            w4:
            kC:
        }
        Uw:
    }
    private function addEncryptedAttributeStatement(DOMElement $le)
    {
        if (!($this->requiredEncAttributes == false)) {
            goto T7;
        }
        return;
        T7:
        $nI = $le->ownerDocument;
        $jL = $nI->createElementNS("\165\x72\x6e\x3a\157\141\x73\x69\x73\x3a\x6e\x61\155\x65\x73\x3a\164\x63\72\x53\x41\115\x4c\x3a\62\x2e\60\x3a\141\x73\x73\145\162\164\151\x6f\156", "\163\x61\155\x6c\x3a\101\x74\164\162\151\x62\165\x74\145\x53\x74\x61\x74\x65\x6d\145\156\x74");
        $le->appendChild($jL);
        foreach ($this->attributes as $or => $Tr) {
            $hA = new DOMDocument();
            $pn = $hA->createElementNS("\x75\162\156\x3a\157\141\x73\151\163\72\156\x61\155\145\x73\72\164\143\72\123\101\x4d\114\x3a\x32\56\60\72\x61\x73\163\x65\x72\164\x69\x6f\x6e", "\163\x61\x6d\x6c\x3a\101\164\164\162\151\x62\165\x74\145");
            $pn->setAttribute("\116\x61\155\x65", $or);
            $hA->appendChild($pn);
            if (!($this->nameFormat !== "\165\x72\156\x3a\157\141\x73\151\x73\72\x6e\141\x6d\145\163\x3a\x74\143\72\x53\x41\115\114\x3a\62\x2e\60\x3a\x61\164\x74\162\156\x61\155\145\55\146\157\162\155\141\x74\x3a\165\156\163\x70\145\x63\x69\146\151\145\x64")) {
                goto UL;
            }
            $pn->setAttribute("\x4e\141\155\145\106\157\162\155\x61\164", $this->nameFormat);
            UL:
            foreach ($Tr as $EB) {
                if (is_string($EB)) {
                    goto jI;
                }
                if (is_int($EB)) {
                    goto ua;
                }
                $Gf = null;
                goto S3;
                jI:
                $Gf = "\x78\163\72\x73\164\x72\151\156\x67";
                goto S3;
                ua:
                $Gf = "\170\163\72\x69\x6e\x74\x65\147\145\162";
                S3:
                $W2 = $hA->createElementNS("\165\162\156\x3a\157\141\163\151\x73\x3a\156\141\155\145\x73\72\x74\x63\72\123\x41\115\114\72\x32\x2e\60\x3a\141\163\x73\145\x72\x74\151\x6f\156", "\163\x61\x6d\154\72\x41\164\164\x72\x69\142\165\164\x65\x56\141\x6c\x75\145");
                $pn->appendChild($W2);
                if (!($Gf !== null)) {
                    goto f5;
                }
                $W2->setAttributeNS("\x68\164\x74\160\x3a\x2f\57\167\167\x77\56\167\x33\56\157\162\x67\x2f\62\60\60\61\x2f\130\x4d\x4c\x53\x63\150\145\x6d\x61\x2d\x69\156\x73\x74\x61\x6e\x63\x65", "\170\x73\x69\72\x74\171\160\145", $Gf);
                f5:
                if ($EB instanceof DOMNodeList) {
                    goto Aw;
                }
                $W2->appendChild($hA->createTextNode($EB));
                goto sS;
                Aw:
                $Ev = 0;
                uh:
                if (!($Ev < $EB->length)) {
                    goto Yc;
                }
                $Fa = $hA->importNode($EB->item($Ev), true);
                $W2->appendChild($Fa);
                Z1:
                $Ev++;
                goto uh;
                Yc:
                sS:
                JQ:
            }
            vX:
            $QI = new XMLSecEnc();
            $QI->setNode($hA->documentElement);
            $QI->type = "\x68\164\164\160\x3a\57\x2f\167\x77\167\56\167\x33\x2e\x6f\162\147\x2f\62\x30\x30\x31\x2f\x30\64\57\170\x6d\154\x65\x6e\143\43\x45\x6c\x65\x6d\x65\x6e\164";
            $IX = new XMLSecurityKey(XMLSecurityKey::AES256_CBC);
            $IX->generateSessionKey();
            $QI->encryptKey($this->encryptionKey, $IX);
            $g8 = $QI->encryptNode($IX);
            $Ng = $nI->createElementNS("\x75\162\x6e\72\x6f\x61\163\151\163\72\156\x61\155\145\x73\x3a\x74\x63\72\123\101\x4d\114\72\62\56\60\x3a\x61\163\x73\x65\x72\x74\151\157\x6e", "\x73\x61\x6d\x6c\72\105\156\x63\162\x79\160\164\145\x64\x41\164\164\162\x69\x62\165\164\145");
            $jL->appendChild($Ng);
            $il = $nI->importNode($g8, true);
            $Ng->appendChild($il);
            hA:
        }
        oT:
    }
}
