<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WC_Braspress
 * @subpackage admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WC_Braspress
 * @subpackage admin
 * @author     Thiago Alencar <thiagofalencar@gmail.com>
 */
class WC_Braspress_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      String    $Woocommerce_Braspress    The ID of this plugin.
	 */
	private $name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      String    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      String    $name       The name of this plugin.
	 * @param      String    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {

		$this->name     = $name;
		$this->version  = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	private function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Braspress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Braspress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style(
			$this->name,
			plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css',
			array(),
			$this->version, 'all'
		);

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	private function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Braspress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Braspress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register all assets of Woocommerce Braspress.
	 *
	 * @since   1.0.0
	 */
	public function enqueue_assets(){
		$this->enqueue_styles();
		$this->enqueue_scripts();
	}

}
