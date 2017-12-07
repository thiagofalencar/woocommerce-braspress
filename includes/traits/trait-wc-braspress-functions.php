<?php


/**
 * This file implements the Trait WC_Braspress_Functions.
 *
 * @since   1.0.0
 *
 * @package    WC_Braspress
 * @subpackage Includes\Traits
 *
 */

 /**
 * This Trait WC_Braspress_Functions implements general methods
 * to be used in Woocoomerce Braspress.
 *
 * @since   1.0.0
 *
 * @package    WC_Braspress
 * @subpackage Includes\Traits
 *
 */
trait WC_Braspress_Functions {

	/**
	 * This method format the zip code removing special chars and letters.
	 *
	 * @param   String  $zip_code   The Zip Code that will be formatted.
	 *
	 * @return  String  Return formatted without special chars ou letters.
	 */
	public function fix_zip_code( String $zip_code ) {

		return (string) $this->remove_chars( $zip_code );

	}

	/**
	 * @param string $text  Text to remove all characters
	 *
	 * @return string       The text formatted.
	 */
	public function remove_chars( $text = "" ){
		$text = preg_replace( '([^0-9])', '', $text );
		return (string) $text;
	}

	public function normalize_money( $money ) {
		$money = str_replace( '.', '', $money );
		$money = str_replace( ',', '.', $money );

		return $money;
	}

	private function fix_format( $value ) {
		$value = str_replace( ',', '.', $value );

		return $value;
	}

}