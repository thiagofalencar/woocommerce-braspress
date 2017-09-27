<?php

/**
 * This file define the Woocommerce-Braspress core class.
 *
 * @since      1.0.0
 *
 * @package    WC_Braspress
 * @subpackage includes
 */

/**
 * The Woocommerce-Braspress core class.
 *
 * A Woocommerce-Braspress core class, that defines all initial methods that will
 * be used in plugin.
 **
 * @since      1.0.0
 * @package    WC_Braspress
 * @subpackage includes
 * @author     Thiago Alencar <thiagofalencar@gmail.com>
 *
 * TODO: SAVE CPF_CNPJ WOOCOMMERCE CUSTOMER.
 *
 */
class WC_Braspress {

	/**
	 * The Woocommerce Braspress version.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      String    $version    The current version.
	 */
	protected $version;

	/**
	 * Responsible for store all hooks used by WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WC_Braspress_Loader    $loader    Contains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The name that identifier Woocommerce Braspress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      String    $name    Contain the name of Woocommerce Braspress.
	 */
	protected $name;


	/**
	 * Set the core of Woocommerce Braspress.
	 *
	 * This method register all the assets, internationalization
	 * and load the filters and hooks.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->name         = 'woocommerce-braspress';
		$this->version      = '1.0.0';

		$this->load_required_classes();
		$this->set_locale();
		$this->define_hooks();

	}

	/**
	 * Validate if Woocommerce is activated.
	 *
	 * This static method validate if the Woocommerce
	 * plugin is activated in WordPress.
	 *
	 * @since    1.0.0
	 */
	public static function is_woocommercer_activeted(){

		$activated_plugin = (is_multisite()) ?
			array_merge(
				get_option( 'active_plugins' ),
				get_option( 'active_sitewide_plugins' )
			) : get_option( 'active_plugins' );

		$activated_plugin = array_merge(
			$activated_plugin,
			array_keys(
				$activated_plugin
			)
		);

		return in_array( 'woocommerce/woocommerce.php', $activated_plugin );

	}

	/**
	 * Load all the required class for Woocommerce Braspress.
	 *
	 * Make an instance of Woocommerce_Braspress_Loader that will register
	 * all hooks and filters in the WordPress.
	 *
	 * ### Include the following classes of the plugin:
	    - **WC_Braspress_Loader:**
	        Load all filters and actions of Woocommerce Braspress.
	    - **WC_Braspress_i18n:**
	        Responsible for internationalization of Woocommerce Braspress.
	    - **WC_Braspress_Admin:**
	        Responsible for all actions in admin area of Woocommerce Braspress.
	    - **WC_Braspress_Public:**
	        Responsible for actions that executed in the public-facing of Woocommerce.
	 *
	 * ### Required classes files:
		- **class-woocommerce-braspress-loader:**   Class responsible load all filters and actions of Woocommerce Braspress.
		- **class-woocommerce-braspress-i18n:**     Class responsible for internationalization of Woocommerce Braspress.
		- **class-woocommerce-braspress-admin:**    Class responsible for all actions in admin area of Woocommerce Braspress.
		- **class-woocommerce-braspress-public:**   Class responsible for actions that executed in the public-facing of Woocommerce.

	 * ### Required paths:
		- **includes/interfaces/:**
			All interfaces required in Woocommerce Braspress plugin.
		- **includes/traits/:**
			All traits required in Woocommerce Braspress plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_required_classes() {

		/**
		 * Include necessaries classes to Woocommerce Braspress:
		 *
		 * @var array $required_classes
		 *
		 */
		$required_classes = array(
			"includes/{interfaces,traits}/*.php",
			"includes/class-wc-braspress-loader.php",
			"includes/class-wc-braspress-i18n.php",
			"admin/class-wc-braspress-admin.php",
			"public/class-wc-braspress-public.php",
			"includes/class-wc-braspress-webservice.php",
		);


		/*
		 * Load all classes defined in $required_classes,
		 * if any class not exists, the plugin will be stopped;
		 */
		array_walk( $required_classes, array($this, 'load_file') );

		$this->loader = new WC_Braspress_Loader();

	}


	/**
	 * This method load the class file.
	 *
	 * @param String $file_path     Path of the file.
	 */
	private function load_file( $file_path ){

		array_walk(
			glob(
				$this->get_path() . $file_path ,
				GLOB_BRACE
			),
			function( $file ) {
				file_exists($file ) ? require_once( $file ) : die();
			}
		);

	}

	/**
	 * This method return the path to this file.
	 *
	 * @return String
	 */
	private function get_path(){
		return (String) plugin_dir_path( dirname( __FILE__ ) );
	}

	/**
	 * Define internationalization locale for Woocommerce Braspress.
	 *
	 * Set the class Woocommerce_Braspress_i18n in order to register
	 * the hook with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$braspress_i18n = new WC_Braspress_i18n();

		$this->loader
			 ->add_action(
				'plugins_loaded',
				$braspress_i18n,
				'load_textdomain'
			 );
	}

	/**
	 * Register all hooks related of the admin area.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {


		$actions = array(
			array(
				'hook'      => 'admin_enqueue_scripts',
				'component' => 	new WC_Braspress_Admin(
					$this->get_name(),
					$this->get_version()
				),
				'callback'  => 'enqueue_assets'
			),

			array(
				'hook'      => 'woocommerce_after_shipping_calculator',
				'component' => 	new WC_Braspress_Public(
					$this->get_name(),
					$this->get_version()
				),
				'callback'  => 'enqueue_assets'
			)
		);

		foreach ( $actions as $action ){
			$this->loader->add_action(
				$action['hook'],
				$action['component'],
				$action['callback']
			);
		}

	}

	/**
	 * Register all of the actions related to Woocommerce Braspress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_wc_action() {

		$actions = array(
			'woocommerce_checkout_fields' => array(
				"component" => $this,
				"callback"  => 'add_cpf_cnpj_field',
				"priority"  => 10,
				"args"      => 1
			),
			// TODO: VALIDATE CPF_CNPJ ON CALCULATOR
			'woocommerce_checkout_process' => array(
				"component" => $this,
				"callback"  => 'customize_checkout_field_process',
				"priority"  => 10,
				"args"      => 1
			),
			'woocommerce_after_shipping_calculator' => array(
				"component" => $this,
				"callback"  => 'add_hidden_cpf_cnpj_field',
				"priority"  => 10,
				"args"      => 1
			),
		);

		array_walk($actions,
			function( $component, $hook ) {

				if ( !isset( $component['component'] ) ) {
					$component['component'] = null;
				}

				$this->loader->add_action(
					$hook,
					$component['component'],
					$component['callback']
				);
			}
		);
	}

	function customize_checkout_field_process()
	{
		// TODO: ADD AN CPF_CNPJ VALIDATION
		// $_POST['billing_cpf_cnpj'])
	}

	/**
	 * This method, add a CPF / CNPJ custom field to checkout fields.
	 *
	 * @param $fields   Array with all checkout fields.
	 *
	 * @return array
	 */
	public function add_cpf_cnpj_field( array $fields ){

		$fields['billing']['billing_cpf_cnpj'] = array(
			'ui-mask'       => "999.999.999-99",
			'label'         => 'CPF / CNPJ',
			'priority'      => 1,
			'maxlength'     => "18",
			'required'      => true,
			'placeholder'   => 'CPF / CNPJ',
			'class'         => array ('address-field', 'update_totals_on_change', 'cpf_cnpj' ),
			'default'       =>  WC()->session->get( '_session_cpf_cnpj' ),
		);

		return $fields;
	}

	public function add_hidden_cpf_cnpj_field(){

		$destination_cpf_cnpj = null;

		if ( isset( $_POST['post_data'] ) ) {
			$post_data = array();

			parse_str( $_POST['post_data'], $post_data );

			if ( isset( $post_data['billing_cpf_cnpj'] ) && $post_data['billing_cpf_cnpj'] != "" ) {
				$destination_cpf_cnpj = $post_data['billing_cpf_cnpj'];
			}

		} elseif( isset( $_POST['calc_shipping_cpf'] ) && preg_replace( '/[^0-9]/', '', $_POST['calc_shipping_cpf'] ) != "" ) {
			$destination_cpf_cnpj = preg_replace( '/[^0-9]/', '', $_POST['calc_shipping_cpf'] );
		}

		$cpf_cnpj_session = WC()->session->get( '_session_cpf_cnpj' );

		if ( !is_null( $destination_cpf_cnpj ) ) {
			WC()->session->set('_session_cpf_cnpj', $destination_cpf_cnpj);
		} elseif ( is_null($destination_cpf_cnpj ) ) {
			$destination_cpf_cnpj = $cpf_cnpj_session;
		}

		echo sprintf(
			"<input id='cpf_cnpj_post' value='%s' type='hidden' />",
			$destination_cpf_cnpj
		);
	}

	/**
	 * Register all of the hooks related to Woocommerce functionality
	 * of the Woocommerce Braspress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_wc_filters() {

		/**
		 * **woocommerce_shipping_calculator_enable_city**:
		 *  Remove the city field on cart shipping calculator.
		 *
		 * **woocommerce_shipping_methods**:
		 *  Add shipping methods to the Woocommerce.
		 *
		 */
		$filters = array(
			'woocommerce_shipping_methods' => array(
				"component" => $this,
				"callback"  => 'include_methods'
			),
		);

		array_walk($filters,
			function( $component, $hook ) {

				if ( !isset( $component['component'] ) ) {
					$component['component'] = null;
				}

				$this->loader->add_filter(
					$hook,
					$component['component'],
					$component['callback']
				);
			}
		);

	}

	/**
	 * Include Woocommerce Braspress shipping methods to WooCommerce.
	 *
	 * @param  array $methods Default shipping methods.
	 *
	 * @return array
	 */
	public function include_methods( $methods ){

		$this->load_file('includes/abstracts/abstract-wc-braspress-shipping-method.php');

		/**
		 * @var String[] $shipping_methods List of all Woocommerce Braspress methods.
		 */
		$shipping_methods = array(
			'braspress-aeropress' => array(
				"class"     => "WC_Braspress_Aeropress",
				"file_path" => "includes/shipping/class-wc-braspress-aeropress.php"
			),
			'braspress-rodoviario' => array(
				"class"     => "WC_Braspress_Rodoviario",
				"file_path" => "includes/shipping/class-wc-braspress-rodoviario.php"
			),
			'braspress-aeropress_fob' => array(
				"class"     => "WC_Braspress_Aeropress_FOB",
				"file_path" => "includes/shipping/class-wc-braspress-aeropress-fob.php"
			),
			'braspress-rodoviario_fob' => array(
				"class"     => "WC_Braspress_Rodoviario_FOB",
				"file_path" => "includes/shipping/class-wc-braspress-rodoviario-fob.php"
			),
		);

		if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.6.0', '>=' ) ) {

			array_walk($shipping_methods,
				function( $class, $method ) use ( &$methods ) {
					require_once( $this->get_path() . $class["file_path"] );
					if (in_array('WC_Braspress_Shipping_Interface', class_implements( $class["class"] ) ) )
						$methods[$method] = $class["class"];
				}
			);

		}

		return $methods;

	}

	/**
	 * Execute all of the hooks loaded in the $loader.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		$this->loader->run();

	}

	/**
	 * Name of the plugin used in WordPress to define the internationalization.
	 *
	 * @since     1.0.0
	 * @return    String    The name of Woocommerce Braspress plugin.
	 */
	public function get_name() {

		return $this->name;

	}

	/**
	 * This method return the loader, that contains all the
	 * filters and hooks of Woocommerce Braspress.
	 *
	 * @since     1.0.0
	 * @return    WC_Braspress_Loader    Class that manage the hooks and filter of Woocommerce Braspress.
	 */
	public function get_loader() {

		return $this->loader;

	}

	/**
	 * Return the version number of Woocommerce Braspress.
	 *
	 * @since     1.0.0
	 * @return    String    The version number of Woocommerce Braspress.
	 */
	public function get_version() {

		return $this->version;

	}

	/**
	 * Register all Woocommerce Braspress hooks.
	 *
	 * @since     1.0.0
	 */
	private function define_hooks() {

		$this->define_admin_hooks();
		$this->define_wc_filters();
		$this->define_wc_action();

	}

}
