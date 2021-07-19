<?php
/**
 * The Help Manager
 */

class CL_Help_Manager extends CL_Base {

	public function __construct() {
		$this->setup();
	}

	public function display() {

		$nav = $content = '';
		$page = isset( $_GET['cl-page'] ) ? $_GET['cl-page'] : 'getting-started';
		$base = cl_get_admin_url();

		foreach ( $this->sections as $label => $view ) {
			$active = $child = '';
			$key = $view;

			if ( is_array( $view ) ) {
				$key = current( $view );

				$child .= '<ul>';
				foreach ( $view as $c_label => $c_view ) {

					$c_active = '';
					if ( $c_view === $page ) {
						$c_active = ' class="active"';
						$active = ' class="active"';
					}
					$child .= sprintf( '<li%4$s><a href="%1$s&cl-page=%3$s">%2$s</a></li>', $base, $c_label, $c_view, $c_active );
				}
				$child .= '</ul>';
			}

			if ( $key === $page ) {
				$active = ' class="active"';
			}

			$nav .= sprintf( '<li%5$s><a href="%1$s&cl-page=%4$s">%2$s</a>%3$s</li>', $base, $label, $child, $key, $active );
		}
		?>
		<div class="mts-cl-help-wrapper">

			<div class="mts-cl-help-menu">
				<div class="mts-cl-help-logo"></div>
				<ul>
					<?php echo $nav ?>
				</ul>
			</div>
			<div class="mts-cl-help-content">
				<?php include "views/how-to-use/{$page}.php"; ?>
			</div>
		</div>
		<?php
	}

	private function setup() {

		$this->sections = array(
			esc_html__( 'Getting Started', 'content-locker' ) => 'getting-started',
			esc_html__( 'Social Locker', 'content-locker' ) => 'social-locker-guide',
			esc_html__( 'Sign-In Locker', 'content-locker' ) => 'signin-locker-guide',
			esc_html__( 'Creating Social Apps', 'content-locker' ) => array(
				esc_html__( 'Creating Facebook App', 'content-locker' ) => 'creating-facebook-app',
				esc_html__( 'Creating Twitter App', 'content-locker' ) => 'creating-twitter-app',
				esc_html__( 'Getting Google Client ID', 'content-locker' ) => 'creating-google-app',
				esc_html__( 'Getting LinkedIn API Key', 'content-locker' ) => 'creating-linkedin-app',
			),
		);
	}
}
