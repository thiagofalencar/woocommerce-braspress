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
 * @var String     $id                  The WordPress ID of Shipping Method
 * @var int        $instance_id         The instance ID of Woocommerce Braspress object.
 * @var String     $method_description  The Braspress Shipping method description.
 * @var String     $method_title        The title of Woocommerce Shipping Method.
 * @var String     $title               The title of Woocommerce Shipping Method.
 * @var String     $fob_message         The label that will be displayed on checkout or cart.
 * @var Int        $shipping_class_id   The Woocommerce shipping class id.
 * @var String     $type                The shipment type.
 *  ## Possible values:
 *  - **A:** Air.
 *  - **R:** Road
 * @var int        $category            The category of shipment.
 *  ## Possible values:
 *  - **1:** CIF (Cost, Insurance and Freight).
 *  - **2:** FOB (Free On Board).
 * @var String[]   $supports            Features this Shipping method supports.
 * ## Possible features used by core:
 *  - **shipping-zones:**           Shipping zone functionality + instances
 *  - **instance-settings:**        Instance settings screens.
 *  - **settings Non-instance:**    settings screens. Enabled by default for BW compatibility with methods before instances existed.
 *  - **instance-settings-modal:**  Allows the instance settings to be loaded within a modal in the zones UI.
 */

abstract class WC_Braspress_Shipping_Method extends WC_Shipping_Method {

	/*
	 * Load the Trait's WC_Braspress_Functions and WC_CPF_CNPJ_Validate;
	 */
	use WC_Braspress_Functions;
	use WC_CPF_CNPJ_Validate;

	private $origin_postcode;
	private $destination_cpf_cnpj;
	private $show_deadline_days;

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

		$this->shipping_class_id    = (int) $this->get_option( 'shipping_class_id', '-1' );
		$this->origin_postcode      = $this->get_option('origin_postcode');
		$this->origin_cpf_cnpj      = $this->get_option('cnpj_origin');

		// TODO: REMOVE THIS HARD CODED
		$this->destination_cpf_cnpj = $this->get_destination_cpf_cnpj();
		$this->show_deadline_days   = $this->get_option( 'show_deadline_days' );
		$this->additional_days      = $this->get_option( 'additional_days' );

		// Save admin options.
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );

	}

	/**
	 * @return string
	 */
	public function get_destination_cpf_cnpj(): string {

		if ( isset( $_POST['post_data'] ) ) {
			$post_data = array();

			parse_str( $_POST['post_data'], $post_data );

			if ( isset( $post_data['billing_cpf_cnpj'] ) && $post_data['billing_cpf_cnpj'] != "" ) {
				$this->set_destination_cpf_cnpj($post_data['billing_cpf_cnpj']);
			}

		} elseif( isset( $_POST['calc_shipping_cpf'] ) && $_POST['calc_shipping_cpf'] != "" ) {
			$this->set_destination_cpf_cnpj($_POST['calc_shipping_cpf'] );
		}

		if ( isset( WC()->session ) ) {
			$cpf_cnpj_session = WC()->session->get( '_session_cpf_cnpj' );
		}

		if ( !is_null( $this->destination_cpf_cnpj ) ) {
			WC()->session->set('_session_cpf_cnpj', $this->destination_cpf_cnpj);
		} elseif ( is_null($this->destination_cpf_cnpj ) ) {
			$this->destination_cpf_cnpj = $cpf_cnpj_session;
		}

		return $this->remove_chars( $this->destination_cpf_cnpj );

	}

	/**
	 * @param string $destination_cpf_cnpj
	 */
	public function set_destination_cpf_cnpj( string $destination_cpf_cnpj ) {
		$this->destination_cpf_cnpj = $destination_cpf_cnpj;
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
			'show_deadline_days' => array(
				'title'   => __( 'Show/Hide', 'wc-braspress' ),
				'type'    => 'checkbox',
				'label'   => __( 'Show estimated delivery', 'wc-braspress' ),
				'default' => 'yes',
			),
			'additional_days' => array(
				'title'            => __( 'Additional days', 'wc-braspress' ),
				'type'             => 'text',
				'description'      => __( 'Additional days to the estimated delivery.', 'wc-braspress' ),
				'desc_tip'         => true,
				'default'          => '0',
				'placeholder'      => '0',
			),
			'shipping_class_id' => array(
				'title'       => __( 'Shipping Class', 'wc-braspress' ),
				'type'        => 'select',
				'description' => __( 'If necessary, select a shipping class to apply this method.', 'wc-braspress' ),
				'desc_tip'    => true,
				'default'     => '',
				'class'       => 'wc-enhanced-select',
				'options'     => $this->get_shipping_classes_options(),
			),
			'origin_postcode' => array(
				'title'       => __( 'Origin Postcode', 'wc-braspress' ),
				'type'        => 'text',
				'description' => __( 'The postcode of the location your packages are delivered from.', 'wc-braspress' ),
				'class'       => 'origin_postcode',
				'desc_tip'    => true,
				'placeholder' => '00000-000',
				'default'     => '',
			),
			'cnpj_origin' => array(
				'title'            => __( 'Origin CNPJ', 'wc-braspress' ) ,
				'type'             => 'text',
				'description'      => __( 'The CNPJ of your company', 'wc-braspress' ),
				'placeholder'      => '00.000.000/0000-00',
				'class'            => 'cpf_cnpj',
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
	 * Get shipping classes options.
	 *
	 * @return array
	 */
	protected function get_shipping_classes_options() {

		$shipping_classes = WC()->shipping->get_shipping_classes();
		$options          = array(
			'-1' => __( 'Any Shipping Class',   'wc-braspress' ),
			'0'  => __( 'No Shipping Class',    'wc-braspress' ),
		);

		if ( ! empty( $shipping_classes ) ) {
			$options += wp_list_pluck( $shipping_classes, 'name', 'term_id' );
		}

		return $options;
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

		// Check if valid to be calculeted.
		if ( '' === $package['destination']['postcode'] || 'BR' !== $package['destination']['country'] ) {
			return;
		}

		// Check for shipping classes.
		if ( ! $this->has_only_selected_shipping_class( $package ) ) {
			return;
		}

		$shipping = $this->get_rate( $package );

		// TODO: Verify error message.

		if ( ! isset( $shipping['TOTALFRETE'] ) ) {

			// Display Correios errors.
			if ( '' !== $shipping['erro'] && is_cart() ) {
				$notice      = sprintf(
					"<strong>%s:</strong><br /><u>%s</u>: <br />%s<br/>",
					__( 'Warning', 'wc-braspress' ),
					$this->title,
					$shipping['erro']
				);
				wc_add_notice( $notice, "notice" );
			}
			return;

		}


		// Set the shipping rates.
		$method_label   = $this->get_shipping_label( (int) $shipping['PRAZO'], $package );
		$cost           = $this->normalize_money( esc_attr( (string) $shipping['TOTALFRETE'] ) );

		// Exit if don't have price.
		if ( 0 === intval( $cost ) ) {
			return;
		}

		// Rate array
		$rate = array(
			'id'        => $this->id,
			'label'     => $method_label,
			'cost'      => $cost,
			'calc_tax'  => 'per_item'
		);

		// Register the rate
		$this->add_rate( $rate );

	}


	/**
	 * Get shipping rate.
	 *
	 * @param  array $package Cart package.
	 *
	 * @return SimpleXMLElement|null
	 */
	protected function get_rate( $package ) {

		$webservice = new WC_Braspress_Webservice( $this->id, $this->instance_id );
		$webservice->set_type( $this->get_type() );
		$webservice->set_mode($this->get_mode() );
		$webservice->set_type( $this->get_type() );
		$webservice->set_origin_postcode( $this->origin_postcode );
		$webservice->set_destination_postcode( $package['destination']['postcode'] );
		$webservice->set_origin_cpf_cnpj($this->get_origin_cpf_cnpj() );
		$webservice->set_destination_cpf_cnpj($this->get_destination_cpf_cnpj() );
		$webservice->set_total_price($this->get_total_price( $package ) );
		$webservice->set_total_weight($this->get_total_weight( $package ) );
		$webservice->set_total_package( $this->get_total_package( $package ) );

		$shipping = $webservice->get_shipping();

		return $shipping;
	}

	protected function get_total_price( $package ){
		return $package['contents_cost'];
	}

	protected function get_total_weight( $package ){
		$mensured_package = $this->measures_extract($package);
		return $mensured_package['weight'];
	}


	protected function measures_extract( $package ) {
		$count  = 0;
		$height = array();
		$width  = array();
		$length = array();
		$weight = array();


		// Shipping per item.
		foreach ( $package['contents'] as $item_id => $values ) {
			$product = $values['data'];
			$qty = $values['quantity'];



			if ( $qty > 0 && $product->needs_shipping() ) {


				if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) ) {
					$_height = wc_get_dimension( $this->fix_format( $product->height ), 'cm' );
					$_width  = wc_get_dimension( $this->fix_format( $product->width ), 'cm' );
					$_length = wc_get_dimension( $this->fix_format( $product->length ), 'cm' );
					$_weight = wc_get_weight( $this->fix_format( $product->weight ), 'kg' );
				} else {
					$_height = woocommerce_get_dimension( $this->fix_format( $product->height ), 'cm' );
					$_width  = woocommerce_get_dimension( $this->fix_format( $product->width ), 'cm' );
					$_length = woocommerce_get_dimension( $this->fix_format( $product->length ), 'cm' );
					$_weight = woocommerce_get_weight( $this->fix_format( $product->weight ), 'kg' );
				}

				$height[ $count ] = $_height;
				$width[ $count ]  = $_width;
				$length[ $count ] = $_length;
				$weight[ $count ] = $_weight;

				if ( $qty > 1 ) {
					$n = $count;
					for ( $i = 0; $i < $qty; $i++ ) {
						$height[ $n ] = $_height;
						$width[ $n ]  = $_width;
						$length[ $n ] = $_length;
						$weight[ $n ] = $_weight;
						$n++;
					}
					$count = $n;
				}

				$count++;
			}
		}

		return array(
			'height' => array_values( $height ),
			'length' => array_values( $length ),
			'width'  => array_values( $width ),
			'weight' => array_sum( $weight ),
		);
	}

	protected function get_total_package( $package ){

		$packageQuantity = 0;

		array_walk($package['contents'], function($value) use (&$packageQuantity){
			$packageQuantity += (int) $value['quantity'];
		});

		return $packageQuantity;
	}


	/**
	 * Check if package uses only the selected shipping class.
	 *
	 * @param  array $package Cart package.
	 * @return bool
	 */
	protected function has_only_selected_shipping_class( $package ) {
		$only_selected = true;

		if ( -1 === $this->shipping_class_id ) {
			return $only_selected;
		}

		foreach ( $package['contents'] as $item_id => $values ) {
			$product = $values['data'];
			$qty     = $values['quantity'];

			if ( $qty > 0 && $product->needs_shipping() ) {
				if ( $this->shipping_class_id !== $product->get_shipping_class_id() ) {
					$only_selected = false;
					break;
				}
			}
		}

		return $only_selected;
	}

	private function get_mode() {
		return $this->mode;
	}

	private function get_origin_cpf_cnpj() {
		return (string) $this->origin_cpf_cnpj;
	}

	private function get_shipping_label( $deadline_days, $package ) {

		if ( 'yes' === $this->show_deadline_days ) {
			return $this->get_estimating_delivery( $this->title, $deadline_days, $this->get_additional_days( $package ) );
		}

		return $this->title;

	}

	function get_estimating_delivery( $title, $deadline_days, $additional_days = 0 ) {
		$total_additional_days = intval( $deadline_days ) + intval( $additional_days );

		if ( $total_additional_days > 0 ) {
			$title .= ' (' . sprintf( _n( 'Delivery within %d working day', 'Delivery within %d working days', $total_additional_days, 'wc-braspress' ),  $total_additional_days ) . ')';
		}

		return $title;
	}

	private function get_additional_days( $package ) {
		return $this->additional_days;
	}

	/**
	 * Get setting form fields for instances of this shipping method.
	 *
	 * @return array
	 */
	public function get_instance_form_fields() {
		if ( is_admin() ) {
			wc_enqueue_js( "
				jQuery( function( $ ) {
				
					function wcBraspressShowHideAdditionalDays( el ) {						
				        var b = jQuery('input[id$=\"_additional_days\"]').closest(\"tr\");
				        $(el).is(\":checked\") ? b.show() : b.hide()
					}

					$( document.body ).on( 'change', 'input[id$=\"_show_deadline_days\"]', function() {
							wcBraspressShowHideAdditionalDays( this );
					});

				});
			" );
		}

		return parent::get_instance_form_fields();
	}

}