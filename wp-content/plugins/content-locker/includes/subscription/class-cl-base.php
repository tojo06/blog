<?php
/**
 * A class for subscription services
 */
abstract class CL_Subscription_Base {

	/**
	 * To hold the configuration
	 * @var array
	 */
	public $config;

	/**
	 * The Constructor
	 * @param array $config [description]
	 */
	public function __construct( $config = array() ) {

		$this->config = $config;
	}

	//public abstract function get_lists( $api_key );
	public abstract function subscribe( $identity, $context, $options );

	/**
	 * Is a valid email address
	 * @param  string  $email
	 * @return boolean
	 */
	public function is_email( $email ) {
		return filter_var( $email, FILTER_VALIDATE_EMAIL );
	}

	/**
	 * Check for single optin method
	 * @return boolean
	 */
	public function has_single_optin() {
		return in_array( 'quick', $this->config['modes'] );
	}

	/**
	 * Get identity fullname
	 * @param  array $identity
	 * @return string
	 */
	public function get_fullname( $identity ) {

		if ( ! empty( $identity['name'] ) && ! empty( $identity['family'] ) ) {
			return $identity['name'] . ' ' . $identity['family'];
		}

		if ( ! empty( $identity['name'] ) ) {
			return $identity['name'];
		}

		if ( ! empty( $identity['family'] ) ) {
			return $identity['family'];
		}

		if ( ! empty( $identity['display_name'] ) ) {
			return $identity['display_name'];
		}

		return '';
	}
}
