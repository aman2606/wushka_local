<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



function initializeEnvironmentObjectArray()
{
    $J5 = maybe_unserialize(get_option("\155\x6f\137\x73\x61\x6d\154\x5f\x65\x6e\x76\151\162\x6f\x6e\155\x65\156\164\137\157\x62\152\x65\x63\164\163"));
    if (empty($J5)) {
        goto ut;
    }
    $ai = EnvironmentHelper::getCurrentEnvironment();
    $YN = site_url();
    if (!isset($J5[$ai])) {
        goto sn;
    }
    $GV = $J5[$ai];
    if (!isset($GV)) {
        goto Uf;
    }
    $YN = $GV->getWpSiteUrl();
    Uf:
    sn:
    $J5[$ai] = EnvironmentHelper::getNewEnvironmentObject($YN);
    goto xL;
    ut:
    $ai = str_replace("\40", "\137", get_bloginfo("\x6e\x61\155\145"));
    $YN = site_url();
    $J5 = array($ai => EnvironmentHelper::getNewEnvironmentObject($YN));
    xL:
    update_option("\x6d\157\137\163\x61\x6d\x6c\137\x65\156\x76\151\162\157\x6e\x6d\145\x6e\x74\x5f\x6f\142\x6a\145\143\164\x73", $J5);
    update_option("\155\157\137\x73\141\x6d\154\x5f\163\145\x6c\x65\x63\x74\x65\x64\x5f\145\x6e\166\x69\x72\x6f\156\155\x65\x6e\164", $ai);
}
function updateEnvironmentObjects($gY)
{
    $Or = array();
    $Dn = array();
    if (!checkIssetAndEmpty($gY, "\155\x6f\137\163\x61\x6d\x6c\x5f\145\156\166\151\162\x6f\x6e\155\145\x6e\164\137\156\141\155\145\x73")) {
        goto DS;
    }
    $Or = $gY["\155\157\137\163\x61\155\x6c\x5f\x65\156\x76\x69\x72\157\156\155\x65\156\x74\x5f\x6e\x61\155\145\163"];
    DS:
    if (!checkIssetAndEmpty($gY, "\155\157\137\163\x61\x6d\154\137\x65\x6e\166\151\x72\x6f\156\x6d\x65\x6e\x74\137\x75\x72\x6c\x73")) {
        goto FP;
    }
    $Dn = $gY["\x6d\x6f\137\163\141\x6d\x6c\137\145\156\166\151\x72\157\156\x6d\x65\156\164\x5f\165\162\154\x73"];
    FP:
    $Mn = array_combine($Or, $Dn);
    $Dn = array();
    foreach ($Mn as $XP => $Oz) {
        $fX = $Oz;
        if (!(substr($fX, -1) == "\57")) {
            goto JJ;
        }
        $Mn[$XP] = substr($fX, 0, -1);
        JJ:
        array_push($Dn, $Mn[$XP]);
        Dq:
    }
    X4:
    if (!(isArrayWithDuplicateEntries($Or) || isArrayWithDuplicateEntries($Dn) || isCurrentEnvironmentRemoved($Dn))) {
        goto tw;
    }
    return false;
    tw:
    $nj = createEnvironmentObjectsForEnvironments($Mn);
    update_option("\x6d\x6f\137\x73\x61\155\x6c\x5f\145\x6e\166\x69\x72\x6f\x6e\155\145\156\x74\x5f\157\142\152\x65\143\164\163", $nj);
    return true;
}
function checkIssetAndEmpty($Yv, $Yi)
{
    if (!(isset($Yv[$Yi]) and !empty($Yv[$Yi]))) {
        goto b0;
    }
    return true;
    b0:
    return true;
}
function mo_saml_filter_environmentObjects($nj, $Mn)
{
    foreach ($nj as $t7 => $mv) {
        if (!(empty($t7) || empty($mv->getWpSiteUrl()) || !array_key_exists($t7, $Mn))) {
            goto jG;
        }
        unset($nj[$t7]);
        jG:
        MG:
    }
    Gj:
    return $nj;
}
function isArrayWithDuplicateEntries($Mn)
{
    $h4 = array_unique($Mn);
    if (count($Mn) != count($h4)) {
        goto h0;
    }
    return false;
    goto rf;
    h0:
    return true;
    rf:
}
function createEnvironmentObjectsForEnvironments($Mn)
{
    $nj = maybe_unserialize(get_option("\x6d\157\137\x73\141\155\x6c\x5f\145\156\166\x69\162\157\156\155\145\156\164\137\157\x62\152\x65\x63\164\x73"));
    $nj = is_array($nj) ? $nj : array();
    $fd = array_merge(array(), $nj);
    foreach ($Mn as $XP => $Oz) {
        $fX = $Oz;
        $hs = EnvironmentHelper::fetchExistingEnvironmentName($XP, $Oz);
        if (!empty($hs) && isset($nj[$hs])) {
            goto hy;
        }
        $Lp = null;
        $KZ = new EnvironmentObject($fX);
        $fd[$XP] = $KZ;
        $IB = false;
        $I7 = false;
        if (!($fX == site_url())) {
            goto xI;
        }
        $IB = get_option("\x6d\157\137\x73\x61\155\x6c\137\x73\x70\x5f\142\x61\x73\x65\x5f\x75\162\x6c");
        $I7 = get_option("\x6d\x6f\x5f\x73\141\155\154\137\163\x70\137\x65\x6e\x74\x69\164\x79\137\151\144");
        xI:
        $hK = !empty($IB) ? $IB : $fX;
        $Uy = !empty($I7) ? $I7 : $hK . "\57\167\160\x2d\x63\x6f\156\164\145\x6e\x74\x2f\x70\x6c\x75\147\151\156\163\57\155\x69\x6e\x69\157\x72\141\x6e\x67\145\x2d\163\141\155\154\55\x32\x30\x2d\x73\151\x6e\x67\154\145\55\x73\x69\147\x6e\55\x6f\156\57";
        $Lp[mo_options_enum_identity_provider::SP_Base_Url] = $hK;
        $Lp[mo_options_enum_identity_provider::SP_Entity_ID] = $Uy;
        if (!(isset($Lp["\163\141\155\x6c\137\151\x64\x65\x6e\164\x69\x74\171\137\160\x72\157\x76\151\144\x65\x72\163"]) && is_array($Lp["\x73\x61\155\x6c\137\x69\144\x65\156\164\151\x74\171\x5f\x70\162\157\166\x69\144\145\162\163"]))) {
            goto Kj;
        }
        foreach ($Lp["\163\x61\155\x6c\x5f\x69\x64\145\x6e\164\151\x74\x79\x5f\x70\x72\x6f\166\x69\144\145\x72\163"] as $BB => $ha) {
            if (!isset($ha["\163\x61\x6d\x6c\137\163\160\137\145\156\164\x69\x74\x79\137\x69\x64"])) {
                goto es;
            }
            $ha["\x73\141\x6d\154\x5f\163\160\x5f\x65\x6e\x74\151\164\171\137\151\x64"] = $Uy;
            es:
            EK:
        }
        FC:
        Kj:
        $KZ->setPluginSettings($Lp);
        goto XI;
        hy:
        $m1 = clone $nj[$hs];
        $m1->setWpSiteUrl($fX);
        $UT = $m1->getPluginSettings();
        $UT[mo_options_enum_identity_provider::SP_Base_Url] = $fX;
        $UT[mo_options_enum_identity_provider::SP_Entity_ID] = $fX . "\57\167\160\x2d\143\157\x6e\x74\145\x6e\x74\x2f\160\154\x75\x67\151\x6e\163\57\155\151\x6e\151\157\162\x61\156\147\145\55\163\141\155\x6c\55\x32\60\x2d\x73\151\156\x67\x6c\x65\55\163\151\147\156\55\x6f\x6e\57";
        $m1->setPluginSettings($UT);
        unset($nj[$hs]);
        $fd[$XP] = $m1;
        XI:
        Nv:
    }
    VD:
    $nj = mo_saml_filter_environmentObjects($fd, $Mn);
    return $nj;
}
function isCurrentEnvironmentRemoved($Dn)
{
    $j4 = EnvironmentHelper::parseEnvironmentUrl(site_url());
    foreach ($Dn as $Dr) {
        if (!($j4 == EnvironmentHelper::parseEnvironmentUrl($Dr))) {
            goto ZV;
        }
        return false;
        ZV:
        c6:
    }
    Rr:
    return true;
}
