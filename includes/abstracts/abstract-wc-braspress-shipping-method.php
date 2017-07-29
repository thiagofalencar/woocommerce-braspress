<?php

/**
 * This file is used during the implementation of abstract
 * class WC_Braspress_Shipping_Method.
 *
 * @since      1.0.0
 *
 * @package    WC_Braspress
 * @subpackage abstracts
 */

/**
 * This abstract class implements the Braspress Shipping Method
 * default methods.
 *
 * Defines default methods and properties to
 * the Woocommerce Braspress shipping method Class.
 *
 * @package    WC_Braspress
 * @subpackage abstracts
 * @author     Thiago Alencar <thiagofalencar@gmail.com>
 *
 * @property String     $id                  The WordPress ID of Shipping Method
 * @property int        $instance_id         The instance ID of Woocommerce Braspress object.
 * @property String     $method_description  The Braspress Shipping method description.
 * @property String     $method_title        The title of Woocommerce Shipping Method.
 * @property String     $title               The title of Woocommerce Shipping Method.
 * @property String     $fob_message         The label that will be displayed on checkout or cart.
 * @property String     $type                The shipment type.
 *  ## Possible values:
 *  - **A:** Air.
 *  - **R:** Road
 * @property int        $category            The category of shipment.
 *  ## Possible values:
 *  - **1:** CIF (Cost, Insurance and Freight).
 *  - **2:** FOB (Free On Board).
 * @property String[]   $supports            Features this Shipping method supports.
 * ## Possible features used by core:
 *  - **shipping-zones:**           Shipping zone functionality + instances
 *  - **instance-settings:**        Instance settings screens.
 *  - **settings Non-instance:**    settings screens. Enabled by default for BW compatibility with methods before instances existed.
 *  - **instance-settings-modal:**  Allows the instance settings to be loaded within a modal in the zones UI.
 */

abstract class WC_Braspress_Shipping_Method extends WC_Shipping_Method {

	/*
	 * Load the Trait WC_Braspress_Functions
	 */
	use WC_Braspress_Functions;

	/**
	 * WC_Braspress_Shipping_Method constructor.
	 *
	 * @param int $instance_id
	 */
	public function __construct( $instance_id = 0 ) {
		parent::__construct($instance_id );

		$this->instance_id          = absint( $instance_id );
		$this->method_description   = sprintf(__( "It's a shipping method that allow to the custumer to ship your product using %s.", 'wc-braspress' ), $this->method_title );

		$this->supports  = array(
			'shipping-zones',
			'instance-settings',
			'instance-settings-modal'
		);

		// Set the plugin enable
		$this->enabled  = 'yes';

		// Loads forms fields
		$this->init_form_fields();

		// Loads settings
		$this->init_settings();

		$this->fob_message = $this->get_option( 'fob_message' );

		// Save admin options.
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );

	}

	/**
	 * Admin options fields.
	 */
	public function init_form_fields() {
		$this->instance_form_fields = array(
			'enabled' => array(
				'title'   => __( 'Enable/Disable', 'wc-braspress' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable this shipping method', 'wc-braspress' ),
				'default' => 'yes',
			),
			'title' => array(
				'title'       => __( 'Title', 'wc-braspress' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'wc-braspress' ),
				'desc_tip'    => true,
				'default'     => $this->method_title,
			),
			'behavior_options' => array(
				'title'   => __( 'Behavior Options', 'wc-braspress' ),
				'type'    => 'title',
				'default' => '',
			),
			'origin_postcode' => array(
				'title'       => __( 'Origin Postcode', 'wc-braspress' ),
				'type'        => 'text',
				'description' => __( 'The postcode of the location your packages are delivered from.', 'wc-braspress' ),
				'desc_tip'    => true,
				'placeholder' => '00000-000',
				'default'     => '',
			),
			'cnpj_origin' => array(
				'title'            => __( 'Origin CNPJ', 'wc-braspress' ) ,
				'type'             => 'text',
				'description'      => __( 'The CNPJ of your company', 'wc-braspress' ),
				'placeholder'      => '00.000.000/0000-00',
				'desc_tip'         => true
			),
		);

		/**
		 * Admin options fields to FOB Methods.
		 */
		if ( $this->is_fob() ) {

			$this->instance_form_fields = array_merge(
				$this->instance_form_fields,
				array(
					'optional_services' => array(
						'title'         => __( 'Optional Services', 'wc-braspress' ),
						'type'          => 'title',
						'default'       => '',
					),
					'fob_message'       => array(
						'title'         => __( 'Message FOB (Free on Board)', 'wc-braspress' ),
						'type'          => 'text',
						'label'         => __( 'Enable', 'wc-braspress' ),
						'description'   => __( 'Message that will be displayed when FOB(Free on Board) mode is selected.', 'wc-braspress' ),
						'desc_tip'      => true,
						'default'       => __( 'Remove on conveyor', 'wc-braspress' )
					)
				)
			);
		}

	}

	/**
	 * Return if the method is fob.
	 *
	 * @return bool
	 */
	protected function is_fob(){
		return ( $this->get_category() == 2 );
	}

	/**
	 * Return the shipment type.
	 *
	 * @return String
	 */
	protected function get_type(){
		return (string) $this->type;
	}

	/**
	 * Return the shipment type.
	 *
	 * @return int
	 */
	protected function get_category(){
		return (int) $this->category;
	}

	/**
	 * This method calculate the shipping price.
	 *
	 * @param   array $package
	 *
	 * @return  void
	 */
	public function calculate_shipping( $package = array() ) {

		$post_data = array();

		$rate = array(
			'id' => $this->id,
			'label' => $this->title,
			'cost' => '0',
			'calc_tax' => 'per_item'
		);

		if ( isset( $_POST['post_data'] ) ) {
			parse_str( $_POST['post_data'], $post_data);
		}

		if ( isset( $post_data['billing_cpf_cnpj'] ) ) {
			$rate['cost'] = '200';
		}


		if ( !$this->is_fob() ) {
			$rate['cost'] = '9.80';
		}

		// Register the rate
		$this->add_rate( $rate );
	}

}