<?php

/**
 * The Woocommerce Braspress AéroPress shipping method.
 *
 * @since      1.0.0
 *
 * @package    WC_Braspress
 * @subpackage shipping
 */

/**
 * This class implements the Brasspress Aeropress shipping method
 * to WooCommerce.
 *
 * Defines how the shipping method Braspress Aeropress works.
 *
 * @package    WC_Braspress
 * @subpackage Shipping
 * @author     Thiago Alencar <thiagofalencar@gmail.com>
 *
 */
class WC_Braspress_Aeropress extends WC_Braspress_Shipping_Method implements WC_Braspress_Shipping_Interface {

	protected $type;

	/**
	 * Initialize Braspress Aeropress.
	 *
	 * @param int $instance_id Shipping zone instance.
	 */
	public function __construct( $instance_id = 0 ) {

		$this->id           = 'braspress-aeropress';
		$this->title        = __( 'Braspress Aeropress', 'wc-braspress' );
		$this->method_title = __( 'Braspress Aeropress', 'wc-braspress' );
		$this->type         = "A";
		$this->category     = 1;

		parent::__construct( $instance_id );

	}


}