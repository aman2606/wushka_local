<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://modernstar.com
 * @since      1.0.0
 *
 * @package    Ms_Pardot_Form
 * @subpackage Ms_Pardot_Form/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ms_Pardot_Form
 * @subpackage Ms_Pardot_Form/includes
 * @author     Modern Star <sshrestha@modernstar.com>
 */
class Ms_Pardot_Form_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ms-pardot-form',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
