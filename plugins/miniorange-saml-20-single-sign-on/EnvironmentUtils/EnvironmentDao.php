<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



class EnvironmentDao
{
    public function mo_save_environment_settings($If, $KC, $Ze = true)
    {
        if ($Ze) {
            goto kT;
        }
        $XP = EnvironmentHelper::getCurrentEnvironment();
        goto a5;
        kT:
        $XP = EnvironmentHelper::getSelectedEnvironment();
        a5:
        $J5 = maybe_unserialize(get_option("\155\157\137\163\141\155\x6c\137\145\x6e\x76\151\x72\x6f\156\155\x65\156\x74\137\x6f\142\152\x65\x63\164\163"));
        $aG = get_option(mo_options_environments::Multiple_Licenses);
        if ($aG && $J5 && isset($J5[$XP])) {
            goto QO;
        }
        update_option($If, $KC);
        goto Ns;
        QO:
        $Ft = clone $J5[$XP];
        $UT = $Ft->getPluginSettings();
        $UT[$If] = $KC;
        $Ft->setPluginSettings($UT);
        $J5[$XP] = $Ft;
        update_option("\x6d\x6f\137\x73\141\155\x6c\x5f\145\x6e\166\151\162\x6f\x6e\x6d\x65\x6e\x74\x5f\157\142\x6a\x65\143\x74\163", $J5);
        Ns:
    }
}
