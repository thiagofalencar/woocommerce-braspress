<?php

/**
 * Create the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 *
 * @package    WC_Braspress
 * @subpackage includes
 */

/**
 * Create the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    WC_Braspress
 * @subpackage includes
 * @author     Thiago Alencar <thiagofalencar@gmail.com>
 */
class WC_Braspress_i18n {


	/**
	 * It loads all the methods, files and functionalities needed
	 * for the internationalization of Woocommerce Braspress.
	 *
	 * @since    1.0.0
	 */
	public function load_textdomain() {

		load_plugin_textdomain(
			'wc-braspress',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

}
