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
class IDPMetadataReader
{
    private $identityProviders;
    private $serviceProviders;
    public function __construct(DOMNode $KD = null)
    {
        $this->identityProviders = array();
        $this->serviceProviders = array();
        $ma = SAMLSPUtilities::xpQuery($KD, "\56\57\x73\141\x6d\x6c\137\155\x65\x74\141\x64\x61\x74\141\72\105\x6e\164\151\x74\151\x65\163\x44\145\163\x63\x72\151\160\x74\157\162");
        if (!empty($ma)) {
            goto VqT;
        }
        $qQ = SAMLSPUtilities::xpQuery($KD, "\x2e\57\163\x61\x6d\154\x5f\x6d\x65\x74\141\x64\141\x74\x61\x3a\105\156\164\151\164\171\104\145\163\143\162\x69\160\164\x6f\x72");
        goto tFc;
        VqT:
        $qQ = SAMLSPUtilities::xpQuery($ma[0], "\x2e\57\163\141\155\x6c\137\x6d\x65\164\x61\144\141\164\x61\x3a\105\156\164\x69\164\x79\104\x65\163\x63\x72\151\x70\x74\x6f\162");
        tFc:
        foreach ($qQ as $Sn) {
            $Fs = SAMLSPUtilities::xpQuery($Sn, "\56\x2f\163\x61\155\154\137\155\145\164\x61\144\x61\x74\141\72\111\x44\x50\x53\123\x4f\x44\145\163\x63\x72\151\x70\164\x6f\x72");
            if (empty($Fs)) {
                goto oO7;
            }
            $this->identityProviders = SAMLSPUtilities::mo_saml_array_push($this->identityProviders, new IdentityProviders($Sn));
            oO7:
            MYQ:
        }
        y8i:
    }
    public function getIdentityProviders()
    {
        return $this->identityProviders;
    }
    public function getServiceProviders()
    {
        return $this->serviceProviders;
    }
}
class IdentityProviders
{
    private $idpName;
    private $entityID;
    private $loginDetails;
    private $logoutDetails;
    private $logoutResponseDetails;
    private $nameIdFormat;
    private $signingCertificate;
    private $encryptionCertificate;
    private $signedRequest;
    private $loginBinding;
    private $logoutBinding;
    public function __construct(DOMElement $KD = null)
    {
        $this->idpName = '';
        $this->loginDetails = array();
        $this->logoutDetails = array();
        $this->logoutResponseDetails = array();
        $this->nameIdFormat = array();
        $this->signingCertificate = array();
        $this->encryptionCertificate = array();
        if (!$KD->hasAttribute("\145\156\164\x69\x74\171\x49\104")) {
            goto sBr;
        }
        $this->entityID = $KD->getAttribute("\145\x6e\164\x69\x74\171\x49\x44");
        sBr:
        if (!$KD->hasAttribute("\127\x61\x6e\x74\x41\165\164\x68\156\x52\x65\161\x75\145\x73\x74\x73\x53\x69\x67\x6e\145\144")) {
            goto vO0;
        }
        $this->signedRequest = $KD->getAttribute("\127\141\x6e\164\x41\165\x74\150\156\x52\x65\x71\165\145\x73\x74\163\123\x69\147\x6e\145\144");
        vO0:
        $Fs = SAMLSPUtilities::xpQuery($KD, "\56\57\163\141\x6d\154\x5f\155\x65\164\141\144\x61\x74\x61\x3a\111\104\120\123\x53\117\x44\145\163\x63\x72\151\x70\164\157\162");
        if (count($Fs) > 1) {
            goto l3T;
        }
        if (empty($Fs)) {
            goto EBh;
        }
        goto e80;
        l3T:
        throw new Mo_SAML_Metadata_Reader_Exception("\115\157\162\x65\40\x74\150\x61\x6e\x20\157\x6e\x65\40\x3c\111\x44\x50\x53\x53\117\104\x65\x73\143\162\151\160\x74\x6f\x72\x3e\x20\151\x6e\x20\74\105\156\164\x69\164\x79\x44\x65\x73\143\162\x69\160\x74\157\x72\x3e\x2e");
        goto e80;
        EBh:
        throw new Mo_SAML_Metadata_Reader_Exception("\x4d\x69\x73\x73\x69\x6e\147\x20\x72\145\161\x75\x69\162\x65\144\40\74\111\104\120\x53\x53\x4f\104\x65\x73\x63\162\x69\160\164\157\162\76\40\151\156\x20\74\105\156\x74\x69\164\x79\x44\x65\x73\x63\x72\151\160\x74\157\x72\x3e\x2e");
        e80:
        $N2 = $Fs[0];
        $this->parseWantAuthnRequestsSigned($N2);
        $yS = SAMLSPUtilities::xpQuery($N2, "\56\x2f\x73\x61\x6d\154\137\155\x65\x74\x61\x64\141\164\x61\72\x45\170\164\145\156\163\151\x6f\x6e\163");
        if (!$yS) {
            goto HIL;
        }
        $this->parseInfo($yS[0]);
        HIL:
        $this->parseSSOService($N2);
        $this->parseSLOService($N2);
        $this->parseNameIdFormat($N2);
        $this->parsex509Certificate($N2);
    }
    private function parseInfo($KD)
    {
        $YD = SAMLSPUtilities::xpQuery($KD, "\56\x2f\x6d\144\165\x69\x3a\125\x49\x49\x6e\x66\157\x2f\x6d\144\x75\151\x3a\x44\151\x73\160\x6c\x61\171\116\x61\x6d\145");
        foreach ($YD as $or) {
            if (!($or->hasAttribute("\x78\x6d\154\x3a\154\x61\x6e\147") && $or->getAttribute("\170\155\x6c\x3a\154\x61\156\147") == "\x65\x6e")) {
                goto Hba;
            }
            $this->idpName = $or->textContent;
            Hba:
            Pf5:
        }
        pS3:
    }
    private function parseSSOService($KD)
    {
        $Q7 = SAMLSPUtilities::xpQuery($KD, "\56\57\163\141\x6d\x6c\137\x6d\145\x74\x61\144\141\x74\141\72\x53\x69\x6e\x67\154\145\x53\151\x67\x6e\x4f\156\123\145\162\166\x69\143\x65");
        foreach ($Q7 as $oQ) {
            $Yc = str_replace("\165\162\156\x3a\157\x61\x73\151\163\x3a\x6e\x61\x6d\145\x73\x3a\164\x63\x3a\x53\101\x4d\x4c\72\x32\x2e\x30\72\x62\151\156\144\x69\x6e\147\x73\x3a", '', $oQ->getAttribute("\x42\151\x6e\x64\151\156\x67"));
            if (!(!empty($Yc) && !empty($oQ->getAttribute("\114\157\143\x61\x74\151\x6f\x6e")))) {
                goto cnP;
            }
            $this->loginDetails = SAMLSPUtilities::mo_saml_array_merge($this->loginDetails, array($Yc => $oQ->getAttribute("\114\157\x63\141\x74\151\157\x6e")));
            if ($Yc == "\110\124\x54\120\x2d\122\145\x64\x69\x72\x65\143\164") {
                goto z4v;
            }
            if ($Yc == "\x48\x54\124\x50\55\x50\x4f\x53\x54") {
                goto Mq9;
            }
            goto mYe;
            z4v:
            $this->loginBinding = "\110\164\164\160\x52\x65\144\151\x72\145\143\x74";
            goto z31;
            goto mYe;
            Mq9:
            $this->loginBinding = "\110\164\x74\x70\120\x6f\163\x74";
            mYe:
            cnP:
            uH8:
        }
        z31:
    }
    private function parseSLOService($KD)
    {
        $QD = SAMLSPUtilities::xpQuery($KD, "\56\x2f\x73\141\155\x6c\x5f\x6d\x65\164\x61\x64\x61\164\141\x3a\123\151\x6e\x67\154\x65\114\x6f\147\157\165\x74\x53\145\162\166\x69\143\145");
        foreach ($QD as $uJ) {
            $Yc = str_replace("\165\x72\x6e\x3a\157\141\x73\151\163\x3a\156\141\155\145\163\x3a\x74\143\x3a\x53\x41\x4d\114\x3a\62\56\x30\72\142\x69\x6e\x64\x69\156\x67\x73\72", '', $uJ->getAttribute("\102\151\156\x64\x69\156\147"));
            if (empty($Yc)) {
                goto SM_;
            }
            $this->logoutDetails = SAMLSPUtilities::mo_saml_array_merge($this->logoutDetails, array($Yc => $uJ->getAttribute("\x4c\x6f\x63\x61\x74\x69\x6f\156")));
            $this->logoutResponseDetails = SAMLSPUtilities::mo_saml_array_merge($this->logoutResponseDetails, array($Yc => $uJ->getAttribute("\x52\x65\x73\160\x6f\x6e\x73\145\114\x6f\143\x61\164\151\157\156")));
            if ($Yc == "\110\124\124\120\x2d\122\x65\144\151\162\145\x63\164") {
                goto LDG;
            }
            if ($Yc == "\x48\124\124\120\55\x50\x4f\123\x54") {
                goto Rqn;
            }
            goto t1R;
            LDG:
            $this->logoutBinding = "\x48\x74\164\160\122\145\x64\x69\x72\145\143\x74";
            goto t1R;
            Rqn:
            $this->logoutBinding = "\x48\x74\x74\160\x50\157\163\x74";
            t1R:
            SM_:
            hsu:
        }
        jVa:
    }
    private function parseNameIdFormat($KD)
    {
        $he = SAMLSPUtilities::xpQuery($KD, "\x2e\57\163\x61\x6d\x6c\137\155\145\164\141\144\x61\164\141\x3a\116\141\x6d\145\111\104\x46\x6f\x72\155\x61\164");
        foreach ($he as $sV) {
            $CE = str_replace(Mo_Saml_Options_Enum_Prefixes::NAME_ID_FORMAT_PREFIX, '', $sV->nodeValue);
            $this->nameIdFormat = array_merge($this->nameIdFormat, array($CE));
            Qk2:
        }
        rG5:
    }
    private function parsex509Certificate($KD)
    {
        foreach (SAMLSPUtilities::xpQuery($KD, "\56\57\163\141\x6d\154\137\x6d\x65\x74\x61\x64\141\x74\x61\72\113\x65\x79\104\145\163\x63\162\151\160\x74\157\x72") as $HP) {
            if ($HP->hasAttribute("\x75\163\x65")) {
                goto mAR;
            }
            $this->parseSigningCertificate($HP);
            goto QeK;
            mAR:
            if ($HP->getAttribute("\x75\x73\x65") == "\x65\x6e\x63\162\171\160\164\151\x6f\156") {
                goto Pnn;
            }
            $this->parseSigningCertificate($HP);
            goto mP8;
            Pnn:
            $this->parseEncryptionCertificate($HP);
            mP8:
            QeK:
            cB7:
        }
        RZ1:
    }
    private function parseWantAuthnRequestsSigned($KD)
    {
        if (!$KD->hasAttribute("\127\141\x6e\164\101\165\164\150\156\122\x65\161\x75\145\163\164\163\123\x69\x67\156\145\144")) {
            goto xHg;
        }
        $this->signedRequest = $KD->getAttribute("\127\x61\156\x74\x41\x75\164\x68\156\x52\145\161\x75\145\x73\164\x73\123\x69\147\156\x65\x64");
        xHg:
    }
    private function parseSigningCertificate($KD)
    {
        $Jx = SAMLSPUtilities::xpQuery($KD, "\x2e\x2f\144\163\x3a\113\145\171\111\x6e\x66\157\x2f\x64\x73\72\130\x35\60\x39\x44\x61\164\141\x2f\x64\163\x3a\130\x35\x30\x39\x43\x65\162\x74\151\x66\x69\143\141\x74\145");
        if (empty($Jx)) {
            goto QBa;
        }
        foreach ($Jx as $fB) {
            $U2 = $fB->nodeValue;
            if (empty($U2)) {
                goto BDE;
            }
            $AC = trim($Jx[0]->textContent);
            $AC = str_replace(array("\xd", "\xa", "\x9", "\40"), '', $AC);
            $this->signingCertificate = SAMLSPUtilities::mo_saml_array_push($this->signingCertificate, SAMLSPUtilities::sanitize_certificate($AC));
            BDE:
            Cey:
        }
        UwE:
        QBa:
    }
    private function parseEncryptionCertificate($KD)
    {
        $Ek = SAMLSPUtilities::xpQuery($KD, "\56\x2f\x64\163\x3a\x4b\x65\171\111\x6e\146\157\x2f\x64\163\72\130\65\x30\x39\104\141\164\141\57\144\163\72\130\65\x30\x39\103\145\162\164\x69\146\x69\143\141\x74\x65");
        $q0 = trim($Ek[0]->textContent);
        $q0 = str_replace(array("\15", "\xa", "\11", "\x20"), '', $q0);
        if (empty($Ek)) {
            goto KRh;
        }
        $this->encryptionCertificate = SAMLSPUtilities::mo_saml_array_push($this->encryptionCertificate, $q0);
        KRh:
    }
    public function getIdpName()
    {
        return $this->idpName;
    }
    public function getEntityID()
    {
        return $this->entityID;
    }
    public function getLoginURL($Yc)
    {
        return !empty($this->loginDetails[$Yc]) ? $this->loginDetails[$Yc] : '';
    }
    public function getLogoutURL($Yc)
    {
        return !empty($this->logoutDetails[$Yc]) ? $this->logoutDetails[$Yc] : '';
    }
    public function getLogoutResponseURL($Yc)
    {
        return !empty($this->logoutResponseDetails[$Yc]) ? $this->logoutResponseDetails[$Yc] : '';
    }
    public function getLoginDetails()
    {
        return $this->loginDetails;
    }
    public function getLogoutDetails()
    {
        return $this->logoutDetails;
    }
    public function getLogoutResponseDetails()
    {
        return $this->logoutResponseDetails;
    }
    public function getNameIdFormats()
    {
        return $this->nameIdFormat;
    }
    public function getSigningCertificate()
    {
        return $this->signingCertificate;
    }
    public function getEncryptionCertificate()
    {
        return $this->encryptionCertificate[0];
    }
    public function isRequestSigned()
    {
        return $this->signedRequest;
    }
    public function getLoginBindingType()
    {
        return $this->loginBinding;
    }
    public function getLogoutBindingType()
    {
        return $this->logoutBinding;
    }
}
class ServiceProviders
{
}
