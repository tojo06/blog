<?php
/**
 * The Locker
 */

/**
 * Locker Manager Class
 */
class CL_Locker_Manager extends CL_Base {

	/**
	 * Lockers
	 * @var array
	 */
	private $lockers = array();

	/**
	 * Batch locker id
	 * @var int
	 */
	private $batch_id = null;

	/**
	 * Current Post Object
	 * @var WP_Post
	 */
	public $object = null;

	/**
	 * Is post excluded
	 * @var boolean
	 */
	private $is_excluded = false;

	/**
	 * The Constructor
	 */
	public function __construct() {

		$this->add_action( 'template_redirect', 'init', 1 );
	}

	/**
	 * Initialize the manager
	 */
	public function init() {

		if ( ! $this->handle_auto_unlock() ) {
			$this->handle_passcode();
			$this->handle_batch_locker();
			$this->handle_post_exclusion();

			if ( ! $this->excluded() ) {
				$this->object = get_post();
			}
		}
	}

	/**
	 * Handle auto-lock strategies
	 *
	 * @return boolean
	 */
	public function handle_auto_unlock() {

		$result = apply_filters( 'mts_cl_auto_unlock', null, get_post() );
		if ( ! is_null( $result ) ) {
			return $result;
		}
	}

	/**
	 * Handle passcode strategy
	 *
	 * @return boolean
	 */
	public function handle_passcode() {

		$passcode = cl()->settings->get( 'passcode' );
		$permenant = cl()->settings->get( 'permanent_passcode' );

		if ( empty( $passcode ) ) {
			return false;
		}

		if ( $permenant && isset( $_REQUEST[ $passcode ] ) ) {

			if ( empty( $this->cookie_passcode ) ) {
				$this->cookie_passcode = 'mts_cl_' . wp_create_nonce( 'passcode' );

				if ( isset( $_COOKIE[ $this->cookie_passcode ] ) && $this->cookie_set ) {
					return $this->excluded( true );
				}

				if ( ! headers_sent() ) {
					setcookie( $this->cookie_passcode, 1, time() + 60 * 60 * 24 * 5000, '/' );
					$this->cookie_set = true;
				}

				return $this->excluded( true );
			}
		} elseif ( isset( $_REQUEST[ $passcode ] ) ) {
			return $this->excluded( true );
		}

		return false;
	}

	/**
	 * Handle batch locker strategies
	 *
	 * @return boolean
	 */
	public function handle_batch_locker() {

		$post = get_post();
		$post_type = get_post_type( $post );
		$options = cl()->settings->get( $post_type );

		if ( isset( $options['locker'] ) ) {
			$id = $options['locker'];
			$options['is_batch'] = true;
			unset( $options['locker'] );

			cl()->manager->add( $id, $options );
		}
	}

	/**
	 * Handle if post is excluded using override metabox or batch exclusion
	 *
	 * @return boolean
	 */
	public function handle_post_exclusion() {

		if ( ! $this->batch_id ) {
			return;
		}
		// Get current post
		$post = get_post();

		// if Preview mode of post
		if ( 'publish' !== get_post_status( $post ) ) {
			return $this->excluded( true );
		}

		// Exclude if have any taxonomy set by batch locker
		$options = cl()->settings->get( $post->post_type );
		$taxonomies = get_object_taxonomies( $post->post_type );

		foreach ( $taxonomies as $taxonomy ) {

			if ( ! empty( $options[ $taxonomy ] ) ) {
				$terms = get_the_terms( $post, $taxonomy );
				if ( ! empty( $terms ) ) {
					foreach ( $terms as $term ) {
						if ( in_array( $term->term_id, $options[ $taxonomy ] ) ) {
							$this->excluded( true );
							break;
						}
					}
				}
			}
		}
	}

	/**
	 * Get Post URL
	 *
	 * @return string
	 */
	public function get_post_url() {

		$url = ! empty( $this->object ) ? get_permalink( $this->object->ID ) : null;
		return $url;
	}

	/**
	 * Get Post Title
	 * @return string
	 */
	public function get_post_title() {

		return ! empty( $this->object ) ? get_the_title( $this->object->ID ) : '';
	}

	/**
	 * Add lockers
	 *
	 * @param  int		$id
	 * @param  array 	$config
	 *
	 * @return CL_Locker
	 */
	public function add( $id = null, $config = array() ) {

		if ( is_null( $id ) ) {
			return;
		}

		$locker = null;
		$type = get_post_meta( $id, '_mts_cl_item_type', true );

		switch ( $type ) {
			case 'social-locker':
				$locker = new CL_Social_Locker( $id, $config );
				break;

			case 'signin-locker':
				$locker = new CL_Signin_Locker( $id, $config );
				break;
		}

		if ( ! is_null( $locker ) ) {
			$this->lockers[ $id ] = $locker;

			// is batch locker
			if ( $locker->is_batch ) {
				$this->batch_id = $id;
			}
			return $locker;
		}

		return false;
	}

	/**
	 * Get locker by id
	 *
	 * @param  int $id
	 * @return CL_Locker
	 */
	public function get( $id ) {

		if ( 'all' === $id ) {
			return $this->lockers;
		}

		return isset( $this->lockers[ $id ] ) ? $this->lockers[ $id ] : false;
	}

	/**
	 * Get batch locker
	 *
	 * @return CL_Locker
	 */
	public function batch_locker() {
		return $this->get( $this->batch_id );
	}

	/**
	 * If no lockers added
	 *
	 * @return boolean
	 */
	public function is_empty() {
		return empty( $this->lockers );
	}

	/**
	 * Get/Set post exclusion
	 *
	 * @return boolean
	 */
	public function excluded( $set = null ) {

		if ( ! is_null( $set ) ) {
			$this->is_excluded = $set;
		}

		return $this->is_excluded;
	}
}

/**
 * Init the locker manager
 */
cl()->manager = new CL_Locker_Manager;
