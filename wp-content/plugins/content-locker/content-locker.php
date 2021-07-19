<?php
/**
 * Plugin Name: Content Locker
 * Plugin URI: http://mythemeshop.com/plugins/content-locker/
 * Description: Content Locker is the fastest way to grow your social media following and mailing list. Swap content for likes, shares and opt-ins and see how quickly your influence can grow!
 * Version: 1.1.10
 * Author: MyThemesShop
 * Author URI: http://mythemeshop.com/
 * Text Domain: content-locker
 *
 * @since     1.0.0
 * @copyright Copyright (c) 2013, MyThemesShop
 * @author    MyThemesShop
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

/**
 * Include Base Class
 * From which all other classes are derived
 */
include_once 'includes/class-cl-base.php';

/**
 * Check for free and pro plugin if it is activated.
 */
if ( class_exists( 'MTS_ContentLocker', false ) ) {
	add_action( 'admin_notices', function() {
		?>
			<div class="error">
				<p><?php esc_html_e( 'Please deactivate Content Locker plugin first to use the Premium features!', 'content-locker' ); ?></p>
			</div>
		<?php
	} );

	return false;
} else {

	if ( ! class_exists( 'MTS_ContentLocker' ) ) :

		final class MTS_ContentLocker extends CL_Base {

			/**
			 * Plugin Version
			 * @var string
			 */
			private $version = '1.1.10';

			/**
			 * Plugin Option key
			 * @var string
			 */
			protected $setting_key = 'mts_cl_options';

			/**
			 * Hold an instance of MTS_ContentLocker class.
			 * @var MTS_ContentLocker
			 */
			protected static $instance = null;

			/**
			 * Main MTS_ContentLocker instance.
			 * @return MTS_ContentLocker - Main instance.
			 */
			public static function get_instance() {

				if ( is_null( self::$instance ) ) {
					self::$instance = new MTS_ContentLocker;
				}

				return self::$instance;
			}

			/**
			 * You cannot clone this class.
			 */
			public function __clone() {
				_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'content-locker' ), $this->version );
			}

			/**
			 * You cannot unserialize instances of this class.
			 */
			public function __wakeup() {
				_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'content-locker' ), $this->version );
			}

			/**
			 * The Constructor
			 */
			private function __construct() {

				$this->includes();
				$this->autoloader();
				$this->hooks();

				// For developers to hook.
				cl_action( 'loaded' );
			}

			/**
			 * Include rquired files
			 */
			private function includes() {

				include_once 'includes/cl-helpers.php';
				include_once 'includes/cl-option-helpers.php';
				include_once 'includes/class-cl-install.php';

				if ( is_admin() ) {
					include_once 'admin/class-cl-stats.php';
					include_once 'admin/class-cl-leads.php';
					include_once 'admin/class-cl-connect.php';
				}
			}

			/**
			 * Register file autoloading mechanism.
			 */
			private function autoloader() {

				if ( function_exists( '__autoload' ) ) {
					spl_autoload_register( '__autoload' );
				}
				spl_autoload_register( array( $this, 'autoload' ) );
			}

			/**
			 * Add hooks
			 */
			private function hooks() {

				register_activation_hook( __FILE__, array( 'CL_Install', 'install' ) );
				$this->add_action( 'init', 'init', 0 );
			}

			/**
			 * Autoload strategy
			 *
			 * @param  string   $class
			 * @return void
			 */
			public function autoload( $class ) {

				if ( ! cl_str_start_with( 'CL_', $class ) ) {
					return;
				}

				$path = '';
				$class = strtolower( $class );
				$file = 'class-' . str_replace( '_', '-', strtolower( $class ) ) . '.php';
				$path = $this->plugin_dir() . '/includes/';

				if ( cl_str_start_with( 'cl_admin', $class ) ) {
					$path = $this->plugin_dir() . '/admin/';
				}

				if ( cl_str_start_with( 'cl_stats', $class ) ) {
					$path = $this->plugin_dir() . '/admin/stats/';
					$file = str_replace( array( 'cl-stats-', '-chart', '-table' ), '', $file );
				}

				if ( cl_str_start_with( 'cl_subscription', $class ) ) {
					$path = $this->plugin_dir() . '/includes/subscription/';
					$file = str_replace( 'subscription-', '', $file );
				}

				// Load File
				$load = $path . $file;
				if ( $load && is_readable( $load ) ) {
					include_once $load;
				}
			}

			/**
			 * Init the plugin
			 */
			public function init() {

				// For developers to hook.
				cl_action( 'before_init' );

				$this->load_textdomain();

				if ( is_admin() ) {
					include_once 'admin/class-cl-admin.php';
				} else {
					include_once 'includes/class-cl-locker.php';
					include_once 'includes/class-cl-locker-manager.php';
					include_once 'includes/class-cl-social-locker.php';
					include_once 'includes/class-cl-signin-locker.php';
					include_once 'includes/class-cl-site-front.php';
				}

				include_once 'includes/lib/CMB2/init.php';
				include_once 'includes/class-cl-post-types.php';
				include_once 'includes/class-cl-settings.php';

				// For developers to hook.
				cl_action( 'init' );
			}

			/**
			 * Load localization files
			 */
			private function load_textdomain() {

				$locale = apply_filters( 'plugin_locale', get_locale(), 'content-locker' );

				load_textdomain( 'content-locker', WP_LANG_DIR . '/content-locker/content-locker-' . $locale . '.mo' );
				load_plugin_textdomain( 'content-locker', false, $this->plugin_dir() . '/languages' );
			}

			/**
			 * Get template path
			 *
			 * @return string
			 */
			public function template_path() {
				return apply_filters( 'contentlocker_template_path', 'content-locker/' );
			}

			// Helper ------------------------------------------------------

			/**
			 * Get plugin directory
			 *
			 * @return string
			 */
			public function plugin_dir() {
				return untrailingslashit( plugin_dir_path( __FILE__ ) );
			}

			/**
			 * Get plugin uri
			 *
			 * @return string
			 */
			public function plugin_url() {
				return untrailingslashit( plugin_dir_url( __FILE__ ) );
			}

			/**
			 * Get plugin version
			 *
			 * @return string
			 */
			public function get_version() {
				return $this->version;
			}

			/**
			 * [is_pro description]
			 * @method is_pro
			 * @return boolean [description]
			 */
			public function is_pro() {
				return false;
			}

			/**
			 * [purchase_link description]
			 * @method purchase_link
			 * @return [type]        [description]
			 */
			public function purchase_link() {
				return esc_url( 'https://mythemeshop.com/plugins/content-locker-pro/' );
			}
		}

	endif;

	/**
	 * Main instance of MTS_ContentLocker.
	 *
	 * Returns the main instance of MTS_ContentLocker to prevent the need to use globals.
	 *
	 * @return MTS_ContentLocker
	 */

	function cl() {
		return MTS_ContentLocker::get_instance();
	}

	cl(); // Init it
}
