<?php
/**
 * Created by PhpStorm.
 * User: thiagoalencar
 * Date: 7/29/17
 * Time: 11:35 AM
 */

class WC_Braspress_Webservice {

	use WC_Braspress_Functions;

	private $webservice_url = "http://www.braspress.com.br/cotacaoXml?";
	private $instance_id;
	private $method_id;
	private $destination_postcode;
	private $origin_postcode;
	private $package;
	private $type;
	private $origin_cpf_cnpj;
	private $destination_cpf_cnpj;
	private $total_weight;
	private $total_price;
	private $total_package;
	private $mode;
	private $timeout;


	public function __construct( $method_id, $instance_id = 0, $timeout = 30 ) {
		$this->method_id    = $method_id;
		$this->instance_id  = $instance_id;
		$this->emp_origin   = '2';
		$this->set_timeout( $timeout );
	}

	/**
	 * @param mixed $timeout
	 */
	public function setTimeout( $timeout ) {
		$this->timeout = $timeout;
	}

	/**
	 * @return mixed
	 */
	public function get_total_weight() {
		return $this->total_weight;
	}

	/**
	 * @param mixed $total_weight
	 */
	public function set_total_weight( $total_weight ) {
		$this->total_weight = $total_weight;
	}

	/**
	 * @return mixed
	 */
	public function get_total_price() {
		return $this->total_price;
	}

	/**
	 * @param mixed $total_price
	 */
	public function set_total_price( $total_price ) {
		$this->total_price = $total_price;
	}

	/**
	 * @return mixed
	 */
	public function get_total_package() {
		return  $this->remove_chars( $this->total_package );
	}

	/**
	 * @param mixed $total_package
	 */
	public function set_total_package( $total_package ) {
		$this->total_package = $total_package;
	}

	/**
	 * @return mixed
	 */
	public function get_mode() {
		return $this->mode;
	}

	/**
	 * @param mixed $mode
	 */
	public function set_mode( $mode ) {
		$this->mode = $mode;
	}

	public function get_webservice_url(){
		return $this->webservice_url;
	}

	public function set_type( $type) {
		$this->type = $type;
	}

	public function set_origin_postcode( $origin_postcode ) {
		$this->origin_postcode = $origin_postcode;
	}

	public function get_origin_postcode() {
		return $this->remove_chars( $this->origin_postcode );
	}

	public function set_destination_postcode( $postcode ) {
		$this->destination_postcode = $postcode;
	}

	public function get_destination_postcode() {
		return $this->destination_postcode;
	}

	public function get_shipping() {
		$shipping = null;

		// Checks if service and postcode are empty.
		if ( ! $this->is_available() ) {
			return $shipping;
		}

		$params = array(
			'param' => implode(',', $this->get_params() )
		);

		$url = add_query_arg( $params, $this->get_webservice_url() );

		$response = wp_safe_remote_get(
			esc_url_raw( $url ),
			array(
				'timeout' => $this->get_timeout()
			)
		);

		if ( is_wp_error( $response ) ) {
			error_log(sprintf( "WP_ERROR: %s",  $response->get_error_message() ) );
		} elseif ( $response['response']['code'] >= 200  && $response['response']['code'] < 300 ) {
			try {
				$shipping = $this->safe_load_xml( $response,  LIBXML_NOCDATA );
			} catch( Exception $exception ) {
				error_log( 'WC_Braspress_Webservice invalid XML: ' . $exception->getMessage() );
			}
		}

		return $shipping;

	}

	/**
	 * @return mixed
	 */
	public function get_origin_cpf_cnpj() {
		return $this->remove_chars($this->origin_cpf_cnpj );
	}

	/**
	 * @param mixed $origin_cpf_cnpj
	 */
	public function set_origin_cpf_cnpj( $origin_cpf_cnpj ) {
		$this->origin_cpf_cnpj = $origin_cpf_cnpj;
	}

	/**
	 * @return mixed
	 */
	public function get_destination_cpf_cnpj() {
		return $this->destination_cpf_cnpj;
	}

	/**
	 * @param mixed $destination_cpf_cnpj
	 */
	public function set_destination_cpf_cnpj( $destination_cpf_cnpj ) {
		$this->destination_cpf_cnpj = $destination_cpf_cnpj;
	}

	private function is_available() {

		foreach ( $this->get_params() as $param ) {
			if ( empty( $param ) ) {
				return false;
			}
		}

		return true;

	}

	/**
	 * This method return the params used to WebService.
	 *
	 * ### Compositions of the parameters:
	 *
	 *  - **CPF_CNPJ**:         CPF or CNPJ of origin.
	 *  - **EMP_ORIGIN**:       2 (it's a fixed value)
	 *  - **ORIGIN_ZIPCODE**:   Postcode of origin.
	 *  - **CEPDESTINO**:       Postcode of destination.
	 *  - **CPF_OR_CNPJ_DEST**: CPF or CNPJ of origin of the package.
	 *  - **CPF_OR_CNPJ_ORIG**: CPF or CNPJ of destination of the package.
	 *  - **SHIPPING_MODE**:    1 to CIF or 2 to FOB.
	 *  - **WEIGHT**:           Dimension using Kg/m³ or Weight.
	 *  - **PRICE**:            The declared price for the packages.
	 *  - **NUMBER_OF_PACKAG**: Number of packages.
	 *  - **MODE**:             Use R to Road or A to Aero.
	 *
	 * @since  1.0.0
	 *
	 * @return array
	 */
	private function get_params(){
		$params = array(
			$this->get_origin_cpf_cnpj(),
			$this->emp_origin,
			$this->remove_chars($this->get_origin_postcode()),
			$this->remove_chars($this->get_destination_postcode()),
			$this->remove_chars($this->get_origin_cpf_cnpj()),
			$this->remove_chars($this->get_destination_cpf_cnpj()),
			$this->get_type(),
			$this->get_total_weight(),
			$this->get_total_price(),
			$this->get_total_package(),
			$this->get_mode()
		);

		return $params;
	}

	private function get_type() {
		return $this->type;
	}

	private function set_timeout( $timeout ) {
		$this->timeout = $timeout;
	}

	private function get_timeout() {
		return (int) $this->timeout;
	}

	/**
	 * This method loads the xml from the webservice securely.
	 *
	 * - **libxml_disable_entity_loader**:  Disable the ability to load external entities
	 *
	 * @param array $response    The Webservice response body.
	 * @param int   $options     Bitwise OR of the libxml option constants.
	 *
	 * @return bool|SimpleXMLElement
	 * @throws Exception
	 */
	private function safe_load_xml( array $response, $options ) {
		$libxml_old = null;

		if ( function_exists( 'libxml_disable_entity_loader' ) ) {
			$libxml_old = libxml_disable_entity_loader( true );
		}


		$braspress_dom = new SimpleXmlElement(
			utf8_encode( $response['body'] ),
			$options
		);

		if ( ! is_null( $libxml_old ) ) {
			libxml_disable_entity_loader( $libxml_old );
		}

		if ( ! $braspress_dom ) {
			return false;
		}

		return ( array ) $braspress_dom;
	}


}