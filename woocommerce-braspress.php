<?php

/**
 * This is the bootstrap file of Woocommerce Braspress.
 *
 * This file is ready to load the initial functions of
 * the Woocommerce Braspress plugin, like WordPress hooks,
 * filters, actions and dependencies.
 *
 * @since             1.0.0
 * @package           WC-Braspress
 *
 * Plugin Name:       Woocommerce Braspress
 * Plugin URI:        https://github.com/thiagofalencar/woocommerce-braspress
 * Description:       This is an unofficial Braspress Shipping method to Woocommerce.
 * Version:           1.0.0
 * Author:            Thiago Alencar <thiagofalencar@gmail.com>
 * Author URI:        https://github.com/thiagofalencar
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-braspress
 * Domain Path:       /languages
 */

// Abort if was executed directly.
defined( 'WPINC' ) or die;

/**
 * The core class of Woocommerce-Braspress,
 * that is used to define many aspects about this plugin.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wc-braspress.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-wc-braspress-loader.php';

/**
 * This function runs during Woocommerce-Braspress activation.
 */
function activate_woocommerce_braspress() {
	wcbra_require_class(
		plugin_dir_path( __FILE__ ) . 'includes/class-wc-braspress-activator.php',
		'WC_Braspress_Activator'
	)->activate();
}



/**
 * This function runs during Woocommerce-Braspress deactivation.
 */
function deactivate_woocommerce_braspress() {
	wcbra_require_class(
		plugin_dir_path( __FILE__ ) . 'includes/class-wc-braspress-deactivator.php',
		'WC_Braspress_Deactivator'
	)->deactivate();
}

register_activation_hook(   __FILE__, 'activate_woocommerce_braspress'   );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_braspress' );

/**
 * This function require a class file an return
 * an instantiate of the class.
 *
 * @since   1.0.0
 * @param   String      $file_path path to the class file.
 * @param   String      $class_name class to be instantiated.
 * @return  object|null The instance of the class.
 */
function wcbra_require_class( $file_path, $class_name ) {

	if (file_exists($file_path)) {
		require_once( $file_path );
		return new $class_name;
	}
	return null;

}

/**
 * This function starts the plugin execution.
 *
 * @since    1.0.0
 */
function run_woocommerce_braspress() {

	/**
	 * Validating if Woocommerce plugin was activated.
	 */
	if (WC_Braspress::is_woocommercer_activeted()){

		$required_classes = array(
			"includes/class-wc-braspress-loader.php",
			"includes/class-wc-braspress-i18n.php",
			"admin/class-wc-braspress-admin.php",
			"public/class-wc-braspress-public.php",
			"includes/{interfaces,traits}/*.php",
		);

		$plugin = new WC_Braspress(
			new WC_Braspress_Loader( $required_classes )
		);

		$plugin->run();
	}

}

run_woocommerce_braspress();
