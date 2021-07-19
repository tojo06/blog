<?php
/**
 * The Front End Manager
 */

/**
 * Site Frontend Class
 */
class CL_Site_Front extends CL_Base {

	/**
	 * Hold JSON
	 * @var array
	 */
	private $json = array();

	/**
	 * Lockers
	 * @var array
	 */
	private $lockers = array();

	/**
	 * THe Constructor
	 */
	public function __construct() {
		$this->add_action( 'template_redirect', 'init' );
	}

	/**
	 * Initalize
	 */
	public function init() {

		// check for terms
		if ( isset( $_REQUEST['mts_cl'] ) ) {
			$action = $_REQUEST['mts_cl'];

			if ( 'terms-of-use' === $action || 'privacy-policy' === $action ) {
				return $this->show_policy_page( $action );
			}
		}

		// check if excluded
		if ( cl()->manager->excluded() ) {

			add_shortcode( 'sociallocker', array( $this, 'shortcode_handler' ) );
			add_shortcode( 'signinlocker', array( $this, 'shortcode_handler' ) );

			return;
		}

		// Search for nested lockers
		$this->has_embeded_locker();

		// Add Shortcode
		add_shortcode( 'sociallocker', array( $this, 'shortcode_handler' ) );
		add_shortcode( 'signinlocker', array( $this, 'shortcode_handler' ) );

		// Act Now
		$this->add_action( 'wp_enqueue_scripts', 'enqueue_assets' );
		$this->add_action( 'wp_footer', 'enqueue_json', 1 );
		$this->add_action( 'wp_footer', 'print_locker_templates', 25 );
		$this->add_filter( 'the_content', 'batch_lock', 10 );
	}

	/**
	 * Check if post contains inline lockers
	 */
	public function has_embeded_locker() {
		global $post, $shortcode_tags;

		// check
		if ( ! cl_str_contains( 'sociallocker', $post->post_content ) && ! cl_str_contains( 'signinlocker', $post->post_content ) ) {
			return;
		}

		// Back up current registered shortcodes and clear them all out
		$orig_shortcode_tags = $shortcode_tags;
		remove_all_shortcodes();

		add_shortcode( 'sociallocker', array( $this, 'parse_lockers' ) );
		add_shortcode( 'signinlocker', array( $this, 'parse_lockers' ) );

		// Do the shortcode
		$content = do_shortcode( $post->post_content );

		// Put the original shortcodes back
		$shortcode_tags = $orig_shortcode_tags;
	}

	/**
	 * Parse locker ids
	 *
	 * @see shortcode
	 */
	public function parse_lockers( $atts = array(), $content = '' ) {

		$id = intval( $atts['id'] );
		if ( ! $id ) {
			return;
		}

		// Check locker state
		$locker = get_post( $id );
		if ( is_null( $locker ) || 'publish' !== get_post_status( $locker ) ) {
			return;
		}

		cl()->manager->add( $id );
	}

	/**
	 * Locker shortcode handler
	 *
	 * @see shortcode
	 * @return html
	 */
	public function shortcode_handler( $atts = array(), $content = '' ) {

		if ( empty( $atts['id'] ) || cl()->manager->excluded() ) {
			return $content;
		}

		global $wp_embed;
		$locker = cl()->manager->get( $atts['id'] );

		// runs nested shortcodes
		$content = $wp_embed->autoembed( $content );
		$content = wpautop( do_shortcode( $content ) );

		if ( ! $locker ) {
			return $content;
		}

		return $locker->wrap_me( $content );
	}

	/**
	 * Enqueue Styles and Scripts for Frontend only
	 */
	public function enqueue_assets() {

		if ( cl()->manager->is_empty() ) {
			return;
		}

		wp_register_style( 'font-awesome', cl()->plugin_url() . '/admin/assets/vendors/font-awesome/css/font-awesome.min.css', array(), cl()->get_version() );
		wp_enqueue_style( 'cl-front', cl()->plugin_url() . '/assets/css/cl-front.css', array( 'font-awesome' ), null );
		wp_register_script( 'jquery-appear', cl()->plugin_url() . '/assets/js/jquery.appear.js', array(), null, true );
		wp_register_script( 'jquery-vague', cl()->plugin_url() . '/assets/js/jquery.vague.js', array(), null, true );
		wp_register_script( 'jquery-pin', cl()->plugin_url() . '/assets/js/jquery.pin.js', array(), null, true );
		wp_enqueue_script( 'cl-front', cl()->plugin_url() . '/assets/js/cl-front.js', array( 'jquery', 'underscore', 'jquery-appear', 'jquery-pin', 'jquery-vague' ), null, true );
	}

	/**
	 * Enqueue JSON
	 */
	public function enqueue_json() {

		wp_localize_script( 'cl-front', '__contentlocker', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'security' => wp_create_nonce( 'mts_cl_security' ),
			'global' => cl()->settings->get_globals(),
			'lang' => cl()->settings->get_lang(),
			'lockers' => $this->lockers,
		) + $this->json );
	}

	/**
	 * Show Terms pages
	 */
	public function show_policy_page( $action ) {

		if ( ! cl()->settings->get( 'terms_enabled' ) || cl()->settings->get( 'terms_use_pages' ) ) {
			return;
		}

		$page = 'terms-of-use' === $action ? 'terms_of_use_text' : 'privacy_policy_text';
		?>
		<html>
			<title><?php echo get_bloginfo( 'name' ) ?></title>
			<link rel='stylesheet' href='<?php echo cl()->plugin_url() . '/assets/css/cl-terms.css' ?>' type='text/css' media='all' />
			<body>
				<?php echo apply_filters( 'the_content', cl()->settings->get( $page ) ) ?>
			</body>
		<html>

		<?php
		exit;
	}

	/**
	 * Output locker template as javascript template
	 */
	public function print_locker_templates() {

		foreach ( cl()->manager->get( 'all' ) as $locker ) {
		?>
		<script type="text/template" id="tmpl-locker-<?php echo $locker->id ?>">
			<!-- block comment for cdata -->
			<?php $locker->get_template() ?>
		</script>
		<?php
		}
	}

	/**
	 * Add JSON to json array
	 *
	 * @param  string   $id
	 * @param  mixed   $value
	 */
	public function add_json( $id, $value = '' ) {

		if ( ! $id ) {
			return;
		}

		if ( isset( $this->json[ $id ] ) ) {
			if ( is_array( $this->json[ $id ] ) ) {
				$this->json[ $id ] = array_merge( $this->json[ $id ] , $value );
			} elseif ( is_string( $this->json[ $id ] ) ) {
				$this->json[ $id ] = $this->json[ $id ] . $value;
			}
		} else {
			$this->json[ $id ] = $value;
		}
	}

	/**
	 * Add locker to be output
	 *
	 * @param  string     $id
	 * @param  array     $json
	 */
	public function add_locker( $id, $json ) {
		$this->lockers[ $id ] = $json;
	}

	/**
	 * Batch lock post content strategy
	 *
	 * @param  html     $content
	 * @return html
	 */
	public function batch_lock( $content = '' ) {

		$locker = cl()->manager->batch_locker();
		if ( ! is_singular() || empty( $locker ) || ! in_array( $locker->bl_way, array( 'skip-lock', 'more-tag' ) ) ) {
			return $content;
		}

		// Skip Lock
		if ( 'skip-lock' === $locker->bl_way ) {

			if ( 0 == $locker->skip_number ) {
				$content = $locker->wrap_me( $content );
			} else {
				$para = explode( '</p>', $content );
				$new_content = '';

				$para = array_filter( $para, function( $p ) {
					$p = trim( $p );

					return $p ? true : false;
				});

				for ( $i = 0; $i < $locker->skip_number; $i++ ) {
					$new_content .= array_shift( $para ) . '</p>';
				}

				if ( ! empty( $para ) ) {
					$new_content .= $locker->wrap_me( join( '</p>', $para ) . '</p>' );
				}

				$content = $new_content;
			}
		} elseif ( 'more-tag' === $locker->bl_way && is_singular() ) {

			// More Tag
			global $post;

			$label = '<span id="more-' . $post->ID . '"></span>';
			$pos = strpos( $content, $label );

			// if no more tag return
			if ( false === $pos ) {
				return $content;
			}

			$offset = $pos + strlen( $label );
			if ( substr( $content, $offset, 4 ) == '</p>' ) {
				$offset += 4;
			}

			$content = substr( $content, 0, $offset ) . $locker->wrap_me( substr( $content, $offset ) );
		}

		return $content;
	}
}

/**
 * Init frontend manager
 */
cl()->front = new CL_Site_Front;
