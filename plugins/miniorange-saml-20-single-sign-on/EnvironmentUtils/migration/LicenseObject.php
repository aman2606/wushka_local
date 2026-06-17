<?php
/**
 * This file is a part of the miniorange-saml-20-single-sign-on plugin.
 *
 * @link https://plugins.miniorange.com/
 * @author miniOrange
 * @package miniorange-saml-20-single-sign-on
 */



class LicenseObject
{
    private $wp_site_url;
    private $plugin_settings = array();
    public function __construct($uR)
    {
        $this->wp_site_url = $uR;
    }
    public function getWpSiteUrl()
    {
        return $this->wp_site_url;
    }
    public function setWpSiteUrl($uR)
    {
        $this->wp_site_url = $uR;
    }
    public function convertEnvironmentObjectToArray()
    {
        return get_object_vars($this);
    }
    public function getPluginSettings()
    {
        return $this->plugin_settings;
    }
    public function setPluginSettings($UT)
    {
        $this->plugin_settings = $UT;
    }
}
