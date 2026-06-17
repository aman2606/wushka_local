<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



require_once MO_SAML_PLUGIN_DIR . MO_SAML_OPTIONS_ENUM;
require Mo_Saml_Plugin_Files::MO_SAML_ASSERTION;
class SAML2_Response
{
    private $assertions;
    private $destination;
    private $certificates;
    private $signatureData;
    private $issuer;
    public function __construct(DOMElement $KD = null, $k7)
    {
        $this->assertions = array();
        $this->certificates = array();
        if (!($KD === null)) {
            goto mq1;
        }
        return;
        mq1:
        $u2 = SAMLSPUtilities::validateElement($KD);
        if (!($u2 !== false)) {
            goto kes;
        }
        $this->certificates = $u2["\103\x65\162\x74\x69\x66\x69\143\141\x74\145\163"];
        $this->signatureData = $u2;
        kes:
        if (!$KD->hasAttribute("\x44\145\x73\164\x69\156\x61\164\151\x6f\x6e")) {
            goto AZq;
        }
        $this->destination = $KD->getAttribute("\104\145\163\x74\x69\x6e\x61\164\151\x6f\156");
        AZq:
        $g5 = SAMLSPUtilities::xpQuery($KD, "\56\57\x73\141\155\154\137\x61\x73\163\145\162\x74\x69\157\x6e\x3a\111\x73\163\x75\x65\162");
        $this->issuer = trim($g5[0]->textContent);
        $this->parseAssertions($KD, $k7);
    }
    public function parseAssertions($KD, $Jm)
    {
        if (!($KD === null)) {
            goto Ztz;
        }
        return;
        Ztz:
        $Fa = $KD->firstChild;
        zzD:
        if (!($Fa !== null)) {
            goto ORB;
        }
        if (!($Fa->namespaceURI !== "\x75\162\x6e\x3a\157\141\163\151\x73\x3a\156\x61\155\145\163\x3a\x74\x63\x3a\x53\101\115\x4c\x3a\62\56\x30\x3a\141\x73\x73\145\162\x74\x69\x6f\156")) {
            goto nrA;
        }
        goto xBh;
        nrA:
        if (!($Fa->localName === "\101\163\x73\145\x72\164\151\x6f\156" || $Fa->localName === "\x45\x6e\x63\162\x79\160\x74\x65\144\x41\x73\163\145\162\164\x69\x6f\x6e")) {
            goto u5K;
        }
        $this->assertions[] = new SAML2_Assertion($Fa, $Jm);
        u5K:
        xBh:
        $Fa = $Fa->nextSibling;
        goto zzD;
        ORB:
    }
    public function getAssertions()
    {
        return $this->assertions;
    }
    public function setAssertions(array $wq)
    {
        $this->assertions = $wq;
    }
    public function getDestination()
    {
        return $this->destination;
    }
    public function getIssuer()
    {
        return $this->issuer;
    }
    public function getCertificates()
    {
        return $this->certificates;
    }
    public function getSignatureData()
    {
        return $this->signatureData;
    }
}
