<?php

/**
 * The public functionality of the Woocommerce Braspress.
 *
 * @since      1.0.0
 *
 * @package    WC_Braspress
 * @subpackage public
 */

/**
 * The public functionality of the Woocommerce Braspress.
 *
 * Defines the plugin name, version, and hooks
 * to the admin stylesheet and JavaScript.
 *
 * @package    WC_Braspress
 * @subpackage public
 * @author     Thiago Alencar <thiagofalencar@gmail.com>
 */
class WC_Braspress_Public {

	/**
	 * The name of Woocommerce Braspress plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      String    $name    The name of Woocommerce Braspress.
	 */
	private $name;

	/**
	 * The version of Woocommerce Braspress plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      String    $version    The current version of Woocommerce Braspress.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      String    $name       The name of Woocommerce Braspress.
	 * @param      String    $version    The version of Woocommerce Braspress.
	 */
	public function __construct( $name, $version ) {

		$this->name     = $name;
		$this->version  = $version;

	}

	/**
	 * Register the stylesheets for the public side of Woocommerce Braspress.
	 *
	 * @since    1.0.0
	 */
	private function enqueue_styles() {

		/**
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
			plugin_dir_url( __FILE__ ) . 'css/woocommerce-braspress-public.css', array(),
			$this->version,
			'all'
		);

	}

	/**
	 * Register the JavaScript for the public side of the Woocommerce Braspress.
	 *
	 * @since    1.0.0
	 */
	private function enqueue_scripts() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Braspress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Braspress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$scripts = array(
			array(
				'handle'        => 'input_mask',
				'src'           => 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js',
				'dependencies'  => array( 'jquery' ),
				'version'       => $this->version,
				'in_footer'     => true
			),
			array(
				'handle'        => 'default_js',
				'src'           => plugin_dir_url( __FILE__ ) . 'js/woocommerce-braspress-public.js',
				'dependencies'  => array( 'jquery' ),
				'version'       => $this->version,
				'in_footer'     => true
			),
			array(
				'handle'        => 'validate_cpf_cnpj',
				'src'           => plugin_dir_url( __FILE__ ) . 'js/wc-braspress-cpf-cnpj-validate.js',
				'dependencies'  => array( 'jquery' ),
				'version'       => $this->version,
				'in_footer'     => true
			),
		);

		array_walk($scripts,
			function( $script ) {
				wp_enqueue_script(
					$script['handle'],
					$script['src'],
					$script['dependencies'],
					$script['version'],
					$script['in_footer']
				);
			}
		);
	}

	/**
	 * Register all CSS and JavaScript assets.
	 *
	 * @since   1.0.0
	 */
	public function enqueue_assets(){

		$this->enqueue_styles();
		$this->enqueue_scripts();

	}

}
