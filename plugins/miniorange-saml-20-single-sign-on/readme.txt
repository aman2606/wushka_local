=== miniOrange SAML 2.0 Single Sign On ===
Contributors: miniOrange
Donate link: https://miniorange.com
Tags: saml, single sign on, SSO, single sign on saml, sso saml, sso integration WordPress, sso using SAML, SAML 2.0 Service Provider, WordPress SAML, SAML Single Sign-On, SSO using SAML, SAML 2.0, SAML 20, WordPress Single Sign On, ADFS, Okta, Google Apps, Google for Work, Salesforce, Shibboleth, SimpleSAMLphp, OpenAM, Centrify, Ping, RSA, IBM, Oracle, OneLogin, Bitium, WSO2, NetIQ, Novell Access Manager
Requires at least: 3.7
Tested up to: 6.8
Stable tag: 25.3.0
License: miniOrange
License URI: https://miniorange.com/usecases/miniOrange_User_Agreement.pdf

miniOrange SAML 2.0 Single Sign-On provides SSO to your WordPress site with any SAML compliant Identity Provider. (ACTIVE SUPPORT for IdP config)

== Description ==

miniOrange SAML 2.0 SSO allows users residing at SAML 2.0 compliant Identity Provider to login to your WordPress website. We support all known IdPs - Google Apps, ADFS, Okta, Salesforce, Shibboleth, SimpleSAMLphp, OpenAM, Centrify, Ping, RSA, IBM, Oracle, OneLogin, Bitium, WSO2, NetIQ etc. If you need detailed instructions on setting up these IdPs, we can give you step by step instructions.

miniOrange SAML SSO Plugin acts as a SAML 2.0 Service Provider which can be configured to establish the trust between the plugin and various SAML 2.0 supported Identity Providers to securely authenticate the user to the WordPress site.

If you require any Single Sign On application or need any help with installing this plugin, please feel free to email us at samlsupport@xecurify.com or <a href="https://miniorange.com/contact">Contact us</a>.

= Features :- =

*	Login to your WordPress site using SAML 2.0 compliant Identity Providers.
*   Easily Configure the Identity Provider by providing just the SAML login URL, IDP Entity ID and Certificate.
* 	Supports plethora of SAML 2.0 Identity Providers like Google Apps, ADFS, Okta, Salesforce, Shibboleth, SimpleSAMLphp, OpenAM, Centrify, Ping, RSA, IBM, Oracle, OneLogin, Bitium, WSO2, NetIQ etc.
*	Valid user registrations verified by the plugin.
*	Easily integrate the login link with your WordPress site using widgets/short code. Just drop it in a desirable place in your site.
*	Automatic user registration after login if the user is not already registered with your site.
*	Use the Attribute Mapping feature to map WordPress user profile attributes to your IdP attributes.
*	Use the Role Mapping feature to assign roles in your IdP to your WordPress users during auto registration.
*	Auto redirect users to your IdP for authentication without showing them your site's login page.
*	Force authentication with your IdP on each login attempt.
*   Supports multisite environment.

= Website - =
Check out our website for other plugins <a href="https://miniorange.com/plugins" >https://miniorange.com/plugins</a> or <a href="https://wordpress.org/plugins/search.php?q=miniorange" >click here</a> to see all our listed WordPress plugins.
For more support or info email us at samlsupport@xecurify.com or <a href="https://miniorange.com/contact" >Contact us</a>. You can also submit your query from plugin's configuration page.

== Installation ==

= From your WordPress dashboard =
1. Visit `Plugins > Add New`.
2. Search for `miniOrange SAML 2.0 Single Sign-On`. Find and Install `miniOrange SAML 2.0 Single Sign-On`.
3. Activate the plugin from your Plugins page.

= From WordPress.org =
1. Download miniOrange SAML 2.0 Single Sign-On plugin.
2. Unzip and upload the `miniorange-saml-20-single-sign-on` directory to your `/wp-content/plugins/` directory.
3. Activate miniOrange SAML 2.0 Single Sign-On from your Plugins page.

== Frequently Asked Questions ==

= I am not able to configure the Identity Provider with the provided settings =
Please email us at samlsupport@xecurify.com or <a href="https://miniorange.com/contact" >Contact us</a>. You can also submit your app request from plugin's configuration page.

= For any query/problem/request =
Visit Help & FAQ section in the plugin OR email us at samlsupport@xecurify.com or <a href="https://miniorange.com/contact">Contact us</a>. You can also submit your query from plugin's configuration page.

== Screenshots ==

1. General settings like auto redirect user to your IdP.
2. Guide to configure your WordPress site as Service Provider to your IdP.
3. Configure your IdP in your WordPress site.

== Changelog ==

= 25.3.0 =
Added option to use SSO button as shortcode and widget
Added a separate Test Configuration URL for end users
Added compatibility of Domain Mapping with WP VIP
Fixes for importing multiple environments configuration file

= 25.2.9 =
Added Compatibility with WordPress 6.8

= 25.2.8 =
Added Feature to hide and disable WordPress Default Login Form
Added compatibility with license library 1.0.8
Fixes for shortcode for redirect_to parameter

= 25.2.7 =
Added Compatibility with WordPress version 6.7.2
Added Filter Hook to change the Relay State URL before and after the SSO
Fixes for Relay State URL sent in the SAML Authentication Request

= 25.2.6 =
Added Compatibility with WordPress version 6.7
Added Compatibility with miniOrange SSO Add-on Integrator

= 25.2.5 =
Added functionality to exclude specific roles from being updated for existing users
Fixes for Logout Relay State URL
Fixes for loading css files for domain mapping functionality

= 25.2.4 =
Removed unused CSS and JS

= 25.2.3 =
Added the filter hook to modify the custom attributes data format
Added the redirect_to parameter for the Redirection & Shortcode flows
Added the plugin documentation near the plugin heading
Optimized the code of the customer verification flow
Optimized the code of licensing plans and redirection tab

= 25.2.2 =
Added new UI in Redirection tab
Improved the default Role Mapping assignment flow
Improved the UI for the Service Provider setup tab
Fixes for Metadata Sync flow for Multiple Environment settings
Fixes for the warning in upgrade framework flow

= 25.2.1 =
New and improved design of Attribute/Role Mapping Tab
New and improved design of the Service Provider Setup tab
Added WPCLI functionality to update, activate, and import configurations into the plugin.
Added logout response url option in the Service Provider Setup tab.
Added a form for syncing metadata in Service Provider Setup tab.
Added custom metadata fields to configuration file while exporting the configuration.
Added a button for Attribute Mapping Configuration in Test Configuration window.
Added an option to edit the IDP Name.
Added a hook to get complete SAML Assertion.
Added an option to configure Nickname.
Added default Public Page URL.
Added Test Configuration button below IDP Configuration.
Added an SSO User Tag for users logging in via SSO
Updated .htaccess rules for compatibity with Apache 2.4.59 and upwords.
Updated all CSS and JS Libraries.
Updated all font-awesome icons to svg.
Updated all images to webp format.
Compatibility with salesforce community addon.
Fix for Error codes text formats.
Minor bug fixes and UI improvements.

= 25.2.0 =
Fix for file path issue

= 25.1.9 =
Added the compatibility with WordPress 6.5
Added the compatibility with Guest User login Add-on
Added the warnings for required PHP extensions
Added the option to validate the assertion time of the SAML Response
Added the option to have IDP-specific Login relay state and Logout relay state
Added the certificate sync option from the IDP Metadata
Added the error codes for the case of failed SSO
Added the filter for the Role Mapping flow
Added the version number with the plugin heading
Fixed the import-export feature for the Multiple Environment Configuration
Fixed the displayed warning on invalid metadata file import
Fixed the redirection flow for users after the logout
Improved customization of the Single Sign-On (SSO) Button displayed on the login page
Improved the UI of the SSO Links Section
Removed non-admin user access from the test configuration endpoint

= 25.1.8 =
Added Multiple Environment Feature for configuring plugin settings for all environments (dev, test, production).
Added Metadata customization feature allowing admin to input Organization Name, Email Address, and Organization URL in the Service Provider Metadata.
Added compatibility for the IP based Redirection to IDP feature.
Fixed the compatibility issues with WP Smart Manager plugin.
Fixed invalid trigger of email updated notifications to users during SAML Authentication.
Fixed the base64_Decode issue of the Wordfence scanner.
Fixed the compatibility issues with 3rd party plugins or themes which use Utilites class name.

= 25.1.7 =
Added compatibility fixes for PHP 8.2.
Added confirmation screen for resetting mapping configuration.
Fixed HTTP Post binding issue with RSS feed.
Fixed Validations issues through out the plugin.
Fixed support email address through out the plugin.
Fixed Domain Mapping issues.
Fixed incorrect warning messages while configuring Service Provider Setup tab.
Minor fixes related to components text and placement.
Improved default IDP assignment flow.
Modified allowed characters for adding Identity Provider name.
Updated metadata contact information.

= 25.1.6 =
Fixed backdoor URL issue.
Fixed Single Logout Request using POST binding.
Fixed Vulnerabilities for XML parsing, insecure cookie creation, replay attack, exposed license file and SAML Request/Response jQuery.
Fixed iconv warning on Linux Environments.
Fixed metadata sync issue for default values.
Fixed redirection loop issue from WordPress login page.
Fixed .json file import/export issue.
Fixed invalid license issue on WordPress multisite environment.

= 25.1.5 =
Fixes in the Upgrade Notice
Updates in the Licensing Framework

= 25.1.4 =
Fixes for Shortcode functionality
Fixes for Auto-Redirection functionality when users are logged-in

= 25.1.3 =
Compatibility with WP 6.4
Redesigned Account Info tab
Added Error Codes Submenu
Updates in Licensing Framework 
Added Admin Dashboard Widget
Added notices in the plugin

= 25.1.2 =
WordPress 6.3 Compatibility
Fixed multiple roles assignment bug
Fixed attribute key assigned if value attribute empty 
Added proper error messages on failed domain mapping
Added error handling for max execution time on metadata upload
Modified the order of wp_login hook in the plugin
Removed extra Identity Provider Name field in plugin settings
Compatibility fixes for SiteGround hosting provider

= 25.1.1 =
Bug fix for encrypted SAML Responses
Fixes for auto-redirect functionality

= 25.1.0 =
WordPress 6.2 Compatibility
Added IDP specific shortcode functionality
PHP 8.1 fixes
Some bug fixes

= 25.0.9 =
WordPress 6.1 Compatibility
Added RSS feed feature
Added IDP selector UI
Added Azure multitenant compatibility
Added Password Reset flow for AzureB2c
Updated bootstrap version to 5.1.3
Updated the Licensing Plan Page
Fixed Single Logout for all WordPress versions
Fixed issue with IDP-initiated SLO
Fixed the redirect to Wordpress login page feature
Fixed the redirect-loop issue for public page url
Fixed issue with displaying custom attributes in user's menu
Fixed RelayState URL for SSO links
Fixed issue in color picker and position of SSO login button
Fixed the auto-selection of default idp
Some bug fixes

= 25.0.8 =
XSS Vulnerability fixes for malformed SAML Response in Test Configuration flow
Wordfence Compatibility Fixes

= 25.0.7 =
Minor bug Fix
Added compatibility fixes with WP SAML IDP plugin

= 25.0.6 =
Compatibility with WordPress 6.0
Fixed Domain Mapping issue for Disabled IDPs
Updated SAML handbook links
Added .htaccess in resources folder

= 25.0.5 =
Compatibility with WordPress 5.9

= 25.0.4 =
Compatibility with WordPress 5.8
Minor UI Fixes

= 25.0.3 =
Added new Certificate for Signing and Encryption
Bug fixes

= 25.0.2 =
Updated xmlseclibs
Fixed a bug in Metadata sync
Compatibility with WordPress 5.7

= 25.0.1 =
Updated SP Certificate
Compatibility with WordPress 5.5.3
Bug fixes

= 25.0.0 =
Optimization and bug fixes
Compatibility with WordPress 5.4.1
Vulnerability fixes
Fixed custom attribute display under the Users table.

= 21.3.5 =
Compatibility with WordPress 5.3
Compatibility for PHP 7.4

= 21.3.4 =
Bug fixes

= 21.3.3 =
Added Login button

= 21.3.2 =
Vulnerability fixes

= 21.3.1 =
Added support for Federation SSO add-on

= 21.3.0 =
Relative Url for Relay State

= 21.2.0 =
Auto Redirect to public Page of the site. 
Show the list of configured IDPs in the dropdown

= 3.7 =
Support for Integrated Windows Authentication - contact samlsupport@xecurify.com if interested

= 3.5 =
Decrypt assertion bug fix

= 3.4 =
Added some requested features and some bug fixes.

= 3.3 =
Added support for Google Apps as an Identity Provider.

= 3.2 =
Some bug fixes in role mapping.

= 3.1 =
Some bug fixes in auto registration.

= 3.0 =
Added option to use miniOrange Single Sign On Service
Made it simple to setup SAML authentication with your IdP.

= 2.3 =
Fixed forgot password bug for some users.

= 2.2 =
Added guides for configuring common Identity Providers like ADFS, SimpleSAMLphp, Salesforce, Okta and some bug fixes.

= 2.1 =
Removed unwanted JS files.

= 2.0 =
Added new feature like role mapping and auto redirect user to your IdP.

= 1.7.0 =
Resolved UI issues for some users

= 1.6.0 =
Added help and troubleshooting guide.

= 1.5.0 =
Added error messaging.

= 1.4.0 =
Added fixes.

= 1.3.0 =
Added validations and fixes.
UI Improvements.

= 1.2.0 =
* this is the third release.

= 1.1.0 =
* this is the second release.

= 1.0.0 =
* this is the first release.

== Upgrade Notice ==

= 25.3.0 =
Added option to use SSO button as shortcode and widget
Added a separate Test Configuration URL for end users
Added compatibility of Domain Mapping with WP VIP
Fixes for importing multiple environments configuration file

= 25.2.9 =
Added Compatibility with WordPress 6.8

= 25.2.8 =
Added Feature to hide and disable WordPress Default Login Form
Added compatibility with license library 1.0.8
Fixes for shortcode for redirect_to parameter

= 25.2.7 =
Added Compatibility with WordPress version 6.7.2
Added Filter Hook to change the Relay State URL before and after the SSO
Fixes for Relay State URL sent in the SAML Authentication Request

= 25.2.6 =
Added Compatibility with WordPress version 6.7
Added Compatibility with miniOrange SSO Add-on Integrator

= 25.2.5 =
Added functionality to exclude specific roles from being updated for existing users
Fixes for Logout Relay State URL
Fixes for loading css files for domain mapping functionality

= 25.2.4 =
Removed unused CSS and JS

= 25.2.3 =
Added the filter hook to modify the custom attributes data format
Added the redirect_to parameter for the Redirection & Shortcode flows
Added the plugin documentation near the plugin heading
Optimized the code of the customer verification flow
Optimized the code of licensing plans and redirection tab

= 25.2.2 =
Added new UI in Redirection tab
Improved the default Role Mapping assignment flow
Improved the UI for the Service Provider setup tab
Fixes for Metadata Sync flow for Multiple Environment settings
Fixes for the warning in upgrade framework flow

= 25.2.1 =
New and improved design of Attribute/Role Mapping Tab
New and improved design of the Service Provider Setup tab
Added WPCLI functionality to update, activate, and import configurations into the plugin.
Added logout response url option in the Service Provider Setup tab.
Added a form for syncing metadata in Service Provider Setup tab.
Added custom metadata fields to configuration file while exporting the configuration.
Added a button for Attribute Mapping Configuration in Test Configuration window.
Added an option to edit the IDP Name.
Added a hook to get complete SAML Assertion.
Added an option to configure Nickname.
Added default Public Page URL.
Added Test Configuration button below IDP Configuration.
Added an SSO User Tag for users logging in via SSO
Updated .htaccess rules for compatibity with Apache 2.4.59 and upwords.
Updated all CSS and JS Libraries.
Updated all font-awesome icons to svg.
Updated all images to webp format.
Compatibility with salesforce community addon.
Fix for Error codes text formats.
Minor bug fixes and UI improvements.

= 25.2.0 =
Fix for file path issue

= 25.1.9 =
Added the compatibility with WordPress 6.5
Added the compatibility with Guest User login Add-on
Added the warnings for required PHP extensions
Added the option to validate the assertion time of the SAML Response
Added the option to have IDP-specific Login relay state and Logout relay state
Added the certificate sync option from the IDP Metadata
Added the error codes for the case of failed SSO
Added the filter for the Role Mapping flow
Added the version number with the plugin heading
Fixed the import-export feature for the Multiple Environment Configuration
Fixed the displayed warning on invalid metadata file import
Fixed the redirection flow for users after the logout
Improved customization of the Single Sign-On (SSO) Button displayed on the login page
Improved the UI of the SSO Links Section
Removed non-admin user access from the test configuration endpoint

= 25.1.8 =
Added Multiple Environment Feature for configuring plugin settings for all environments (dev, test, production).
Added Metadata customization feature allowing admin to input Organization Name, Email Address, and Organization URL in the Service Provider Metadata.
Added compatibility for the IP based Redirection to IDP feature.
Fixed the compatibility issues with WP Smart Manager plugin.
Fixed invalid trigger of email updated notifications to users during SAML Authentication.
Fixed the base64_Decode issue of the Wordfence scanner.
Fixed the compatibility issues with 3rd party plugins or themes which use Utilites class name.

= 25.1.7 =
Added compatibility fixes for PHP 8.2.
Added confirmation screen for resetting mapping configuration.
Fixed HTTP Post binding issue with RSS feed.
Fixed Validations issues through out the plugin.
Fixed support email address through out the plugin.
Fixed Domain Mapping issues.
Fixed incorrect warning messages while configuring Service Provider Setup tab.
Minor fixes related to components text and placement.
Improved default IDP assignment flow.
Modified allowed characters for adding Identity Provider name.
Updated metadata contact information.

= 25.1.6 =
Fixed backdoor URL issue.
Fixed Single Logout Request using POST binding.
Fixed Vulnerabilities for XML parsing, insecure cookie creation, replay attack, exposed license file and SAML Request/Response jQuery.
Fixed iconv warning on Linux Environments.
Fixed metadata sync issue for default values.
Fixed redirection loop issue from WordPress login page.
Fixed .json file import/export issue.
Fixed invalid license issue on WordPress multisite environment.

= 25.1.5 =
Fixes in the Upgrade Notice
Updates in the Licensing Framework

= 25.1.4 =
Fixes for Shortcode functionality
Fixes for Auto-Redirection functionality whne users are logged-in

= 25.1.3 =
Compatibility with WP 6.4
Redesigned Account Info tab
Added Error Codes Submenu
Updates in Licensing Framework 
Added Admin Dashboard Widget
Added notices in the plugin

= 25.1.2 =
WordPress 6.3 Compatibility
Fixed multiple roles assignment bug
Fixed attribute key assigned if value attribute empty 
Added proper error messages on failed domain mapping
Added error handling for max execution time on metadata upload
Modified the order of wp_login hook in the plugin
Removed extra Identity Provider Name field in plugin settings
Compatibility fixes for SiteGround hosting provider

= 25.1.1 =
Bug fix for encrypted SAML Responses
Fixes for auto-redirect functionality

= 25.1.0 =
WordPress 6.2 Compatibility
Added IDP specific shortcode functionality
PHP 8.1 fixes
Some bug fixes

= 25.0.9 =
WordPress 6.1 Compatibility
Added RSS feed feature
Added IDP selector UI
Added Azure multitenant compatibility
Added Password Reset flow for AzureB2c
Updated bootstrap version to 5.1.3
Updated the Licensing Plan Page
Fixed Single Logout for all WordPress versions
Fixed issue with IDP-initiated SLO
Fixed the redirect to Wordpress login page feature
Fixed the redirect-loop issue for public page url
Fixed issue with displaying custom attributes in user's menu
Fixed RelayState URL for SSO links
Fixed issue in color picker and position of SSO login button
Fixed the auto-selection of default idp
Some bug fixes

= 25.0.8 =
XSS Vulnerability fixes for malformed SAML Response in Test Configuration flow
Wordfence Compatibility Fixes

= 25.0.7 =
Minor bug Fix
Added compatibility fixes with WP SAML IDP plugin

= 25.0.6 =
Compatibility with WordPress 6.0
Fixed Domain Mapping issue for Disabled IDPs
Updated SAML handbook links
Added .htaccess in resources folder

= 25.0.5 =
Compatibility with WordPress 5.9

= 25.0.4 =
Compatibility with WordPress 5.8
Minor UI Fixes

= 25.0.3 =
Added new Certificate for Signing and Encryption
Bug fixes

= 3.7 =
Support for Integrated Windows Authentication - contact samlsupport@xecurify.com if interested

= 3.5 =
Decrypt assertion bug fix

= 3.4 =
Added some requested features and some bug fixes.

= 3.0 =
Major Update. We have taken ut-most care to make sure that your existing login flow doesn't break. If you have issues after this update then please contact us. We will get back to you very soon. 
 
= 2.1 =
Removed unwanted JS files.

= 2.0 =
Added new feature like role mapping and auto redirect user to your IdP.

= 1.7 =
Resolved UI issues for some users

= 1.6 =
Added help and troubleshooting guide.

= 1.5 =
Added error messaging.

= 1.4 =
Added fixes.

= 1.3 =
Added validations and fixes.
UI Improvements.

= 1.2 =
Some UI improvements.

= 1.1 =
Added Attribute mapping / Role mapping and test application.

= 1.0 =
I will update this plugin when ever it is required.