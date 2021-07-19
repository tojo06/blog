<?php
/**
 * The Theme Options
 */

class CL_Settings extends CL_Base {

	/**
	 * Options array
	 * @var array
	 */
	protected $options = array();

	/**
	 * Current option key
	 * @var string
	 */
	protected $key = '';

	/**
	 * The Constructor
	 * @param  string     $key
	 */
	public function __construct( $key ) {

		$this->key = $key;
		$this->cmb_id = "{$key}_metabox";

		$this->add_action( 'admin_init', 'init' );
		add_action( 'admin_enqueue_scripts', array( 'CMB2_hookup', 'enqueue_cmb_css' ), 25 );
		$this->add_action( 'cmb2_admin_init', 'add_options' );
		$this->add_action( 'cmb2_admin_init', 'save_defaults', 25 );
	}

	/**
	 * Save default on plugin activation
	 * @return void
	 */
	public function save_defaults() {

		$options = get_option( $this->key, false );

		if ( $options ) {
			return;
		}

		$defaults = array();
		$cmb = cmb2_get_metabox( $this->cmb_id, $this->key );

		foreach ( $cmb->meta_box['fields'] as $id => $field ) {
			if ( ! empty( $field['default'] ) ) {
				$defaults[ $id ] = $field['default'];
			}
		}

		update_option( $this->key, $defaults );
	}

	/**
	 * Initialize
	 */
	public function init() {

		register_setting( $this->key, $this->key );
	}

	/**
	 * [display description]
	 * @method display
	 * @return [type]  [description]
	 */
	public function display() {

		cmb2_metabox_form( $this->cmb_id, $this->key, array(
			'save_button' => esc_html__( 'Save Changes', 'content-locker' ),
		) );
	}

	/**
	 * Create option object and add settings
	 */
	function add_options() {

		// hook in our save notices
		add_action( "cmb2_save_options-page_fields_{$this->cmb_id}", array( $this, 'settings_notices' ), 10, 2 );

		$cmb = new_cmb2_box( array(
			'id'         => $this->cmb_id,
			'hookup'     => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key ),
			),
		) );

		$settings = array( 'social-options', 'lock-options', 'subscription-options', 'batch-options', 'stats-options', 'front-options', 'terms-options' );

		$cmb->add_field( array(
			'id' => 'mts-cl-setting-tabs',
			'type' => 'tabs',
			'render_row_cb' => 'cl_cmb_tabs_open_tag',
			'classes' => 'has-state',
		) );

		foreach ( $settings as $setting ) {
			include_once cl()->plugin_dir() . "/admin/settings/$setting.php";
		}

		$cmb->add_field( array(
			'id' => 'mts-cl-setting-tabs-close',
			'type' => 'tabs',
			'render_row_cb' => 'cl_cmb_tabs_close_tag',
		) );
	}

	/**
	 * Add notices
	 */
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}
		add_settings_error( $this->key . '-notices', '', esc_html__( 'Settings updated.', 'content-locker' ), 'updated' );
		settings_errors( $this->key . '-notices' );
	}

	/**
	 * Get Setting
	 *
	 * @param  string	$field_id
	 * @param  mixed	$default
	 *
	 * @return mixed
	 */
	public function get( $field_id = '', $default = false ) {

		$opts = $this->get_options();

		if ( 'all' == $field_id ) {
			return $opts;
		} elseif ( isset( $opts[ $field_id ] ) ) {
			return false !== $opts[ $field_id ] ? $opts[ $field_id ] : $default;
		}

		return $default;
	}

	/**
	 * Get all settings
	 *
	 * @return array
	 */
	public function all() {
		return $this->get( 'all' );
	}

	/**
	 * Get Global setting for JSON
	 *
	 * @return array
	 */
	public function get_globals() {

		$settings = array(

			// Social
			'lang' => $this->get( 'lang' ),
			'short_lang' => $this->get( 'short_lang' ),
			'lazyload' => $this->get( 'lazyload' ),
			'facebook' => array(
				'appid' => $this->get( 'facebook_appid' ),
				'version' => $this->get( 'facebook_version', 'v2.6' ),
			),
			'twitter' => array(
				'consumer_key' => $this->get( 'twitter_consumer_key' ),
			),
			'google' => array(
				'client_id' => $this->get( 'google_client_id' ),
			),
			'linkedin' => array(
				'client_id' => $this->get( 'linkedin_client_id' ),
			),

			// Lock
			'is_mobile' => $this->get( 'is_mobile' ),
			'managed_hook' => false,
			'overlap_mode' => $this->get( 'alt_overlap_mode' ),
			'content_visibility' => $this->get( 'content_visibility' ),
			'timeout' => $this->get( 'timeout' ),

			// Stats
			'google_analytics' => $this->get( 'google_analytics' ),
			'tracking' => $this->get( 'tracking' ),
		);

		if ( $this->get( 'terms_enabled' ) ) {

			$terms_of_use = $this->get( 'terms_of_use_text' );
			$privacy_policy = $this->get( 'privacy_policy_text' );
			$use_pages = $this->get( 'terms_use_pages' );

			if ( $use_pages ) {

				$terms_post = get_post( $this->get( 'terms_of_use_page' ) );
				if ( $terms_post ) {
					$terms_of_use = $terms_post->post_content;
				}

				$policy_post = get_post( $this->get( 'privacy_policy_page' ) );
				if ( $policy_post ) {
					$privacy_policy = $policy_post->post_content;
				}
			}

			if ( $terms_of_use ) {
				$settings['terms'] = $use_pages && $terms_post ? get_permalink( $terms_post ) : add_query_arg( array( 'mts_cl' => 'terms-of-use' ), site_url() );
			}
			if ( $privacy_policy ) {
				$settings['policy'] = $use_pages && $policy_post ? get_permalink( $policy_post ) : add_query_arg( array( 'mts_cl' => 'privacy-policy' ), site_url() );
			}
		}

		return $settings;
	}

	/**
	 * Get language options for json
	 *
	 * @return array
	 */
	public function get_lang() {

		$lang = array(
			'misc' => array(
				'data_processing' => $this->get( 'trans_misc_data_processing' ),
				//'or_enter_email' => $this->get( 'trans_misc_or_enter_email' ),
				'enter_your_email' => $this->get( 'trans_misc_enter_your_email' ),
				'your_agree_with' => $this->get( 'trans_misc_your_agree_with' ),
				'terms_of_use' => $this->get( 'trans_misc_terms_of_use' ),
				'privacy_policy' => $this->get( 'trans_misc_privacy_policy' ),
				'close' => $this->get( 'trans_misc_close' ),
				'or' => $this->get( 'trans_misc_or' ),
			),
			'onestep' => array(
				'screen_title' => $this->get( 'trans_onestep_screen_title' ),
				'screen_instruction' => $this->get( 'trans_onestep_screen_instruction' ),
				'screen_button' => $this->get( 'trans_onestep_screen_button' ),
			),
			'errors' => array(
				'empty_email' => $this->get( 'trans_errors_empty_email' ),
				'incorrect_email' => $this->get( 'trans_errors_inorrect_email' ),
				'empty_name' => $this->get( 'trans_errors_empty_name' ),
				'not_signed_in' => $this->get( 'trans_errors_not_signed_in' ),
				'not_granted' => $this->get( 'trans_errors_not_granted' ),
			),
		);

		return $lang;
	}

	/**
	 * Get options once for use throughout the plugin cycle
	 *
	 * @return void
	 */
	public function get_options() {

		if ( empty( $this->options ) && ! empty( $this->key ) ) {

			$options = get_option( $this->key, array() );
			$post_types = cl_cmb_post_types();

			if ( empty( $options ) ) {
				$this->options = array();
				return $this->options;
			}

			foreach ( $options as $key => $value ) {

				if ( array_key_exists( $key, $post_types ) ) {
					$value = $value[0];
				}
				$this->options[ $key ] = cl_normalize_data( $value );
			}

			// Short Lang
			if ( $this->options['lang'] ) {
				$this->options['short_lang'] = explode( '_', $this->options['lang'] )[0];
			}

			// Mobile Check
			if ( ! isset( cl()->mobile_detector ) ) {
				include_once cl()->plugin_dir() . '/includes/lib/mobile_detect.php';
				cl()->mobile_detector = new Mobile_Detect;
			}

			$this->options['is_mobile'] = cl()->mobile_detector->isMobile();
			$this->options['is_tablet'] = cl()->mobile_detector->isTablet();
		}

		return $this->options;
	}
}

/**
 * Init the setting manager
 */
cl()->settings = new CL_Settings( cl()->setting_key );
