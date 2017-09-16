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
class WC_Braspress_Rodoviario extends WC_Braspress_Shipping_Method implements WC_Braspress_Shipping_Interface {

	/**
	 * Initialize Braspress Rodoviario.
	 *
	 * @param int $instance_id Shipping zone instance.
	 */
	public function __construct( $instance_id = 0 ) {

		$this->id           = 'braspress-rodoviario';
		$this->title        = __( 'Braspress Rodoviario', 'wc-braspress' );
		$this->method_title = __( 'Braspress Rodoviario', 'wc-braspress' );
		$this->mode         = "A";
		$this->type         = 1;

		parent::__construct( $instance_id );

	}

}