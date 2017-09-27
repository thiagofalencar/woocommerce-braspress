<?php

/**
 * The Woocommerce Braspress Rodoviario shipping method.
 *
 * @since      1.0.0
 *
 * @package    WC_Braspress
 * @subpackage shipping
 */

/**
 * This class implements the Brasspress Rodoviario shipping method
 * to WooCommerce.
 *
 * Defines how the shipping method Braspress Rodoviario works.
 *
 * @package    WC_Braspress
 * @subpackage includes\shipping
 * @author     Thiago Alencar <thiagofalencar@gmail.com>
 *
 */
class WC_Braspress_Rodoviario_FOB extends WC_Braspress_Shipping_Method implements WC_Braspress_Shipping_Interface {

	/**
	 * Initialize Braspress Rodoviario.
	 *
	 * @param int $instance_id Shipping zone instance.
	 */
	public function __construct( $instance_id = 0 ) {

		$this->id           = 'braspress-rodoviario_fob';
		$this->title        = __( 'Braspress Rodoviario - FOB', 'wc-braspress' );
		$this->method_title = __( 'Braspress Rodoviario - FOB', 'wc-braspress' );
		$this->mode         = "R";
		$this->type         = 2;

		parent::__construct( $instance_id );

	}

}