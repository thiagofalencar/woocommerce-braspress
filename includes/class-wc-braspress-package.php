<?php
/**
 * Created by PhpStorm.
 * User: thiagoalencar
 * Date: 7/29/17
 * Time: 11:28 AM
 * @property array package array of Woocommerce Package.
 */

class WC_Braspress_Package {

	/**
	 * WC_Braspress_Package constructor.
	 *
	 * @param array $package    Woocommerce package.
	 */
	public function __construct( $package = array() ) {
		$this->package = $package;
	}

}