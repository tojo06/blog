<?php
/**
 * The Locker Class
 */
class CL_Locker extends CL_Base {

	/**
	 * The Constructor
	 */
	public function __construct( $id = null, $config = array() ) {

		if ( is_null( $id ) ) {
			return;
		}

		$this->id = $id;
		$this->is_batch = false;
		$this->config( $config );
		$this->set_locker_data();

		if ( empty( $this->item_type ) ) {
			return;
		}
	}

	/**
	 * Set locker data to locker
	 *
	 * @return void
	 */
	protected function set_locker_data() {

		$meta = get_post_meta( $this->id );
		foreach ( $meta as $key => $value ) {

			if ( ! cl_str_start_with( '_mts_cl_', $key ) ) {
				continue;
			}

			$key = str_replace( '_mts_cl_', '', $key );
			$this->$key = cl_normalize_data( maybe_unserialize( $value[0] ) );
		}
	}

	/**
	 * Wrap the content into locker
	 *
	 * @param  html  $content
	 * @return html
	 */
	public function wrap_me( $content ) {

		if ( is_singular() && ! $this->can_lock() ) {
			return $content;
		}

		cl()->front->add_locker( $this->id, $this->to_json() );

		return sprintf( '<div class="content-locker-call" style="display:none" data-locker-id="%1$d">%2$s</div><!-- #locker-%d -->', $this->id, $content );
	}

	/**
	 * Can lock the post content based on locker options
	 *
	 * @return boolean
	 */
	protected function can_lock() {

		// Hide for Member
		if ( is_user_logged_in() && $this->hide_for_member ) {
			return false;
		}

		// isMobile
		if ( ! $this->mobile && $this->maybe_mobile() ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if the site is requested from a mobile.
	 *
	 * @return boolean
	 */
	protected function maybe_mobile() {

		if ( ! isset( cl()->mobile_detector ) ) {
			include_once cl()->plugin_dir() . '/includes/lib/mobile_detect.php';
			cl()->mobile_detector = new Mobile_Detect;
		}

		return cl()->mobile_detector->isMobile();
	}

	/**
	 * Convert interval to seconds
	 *
	 * @param  int 		$interval
	 * @param  string	$unit
	 * @return int
	 */
	protected function convert_to_seconds( $interval, $unit ) {

		if ( 'days' === $unit ) {
			return 24 * 60 * 60 * $interval;
		}
		if ( 'hours' === $unit ) {
			return 60 * 60 * $interval;
		}
		if ( 'minutes' === $unit ) {
			return 60 * $interval;
		}
	}

	// Locker Type Templating -----------------------------------------------

	/**
	 * Get template based on locker theme
	 *
	 * @return void
	 */
	public function get_template() {
		$args = array( 'locker' => $this );

		cl_get_template_part( 'wrapper-start', $args );

			echo '<div class="mts-cl-header">';
				cl_get_template_part( 'title', $args );
			echo '</div><!-- /.mts-cl-header -->';

			$this->get_controls();

		cl_get_template_part( 'wrapper-end', $args );
	}

	/**
	 * Get css classes for theme
	 *
	 * @return string
	 */
	public function get_class() {
		$classes = array(
			'mts-cl',
			'mts-cl-' . $this->theme,
			'mts-cl-' . $this->layout,
			'mts-cl-' . $this->item_type,
			'mts-cl-' . cl()->settings->get( 'lang' ),
		);

		return join( ' ', $classes );
	}
}
