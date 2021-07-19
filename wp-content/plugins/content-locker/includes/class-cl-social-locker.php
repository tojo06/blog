<?php
/**
 * The Social Locker
 */

/**
 * Social Locker Class
 */
class CL_Social_Locker extends CL_Locker {

	/**
	 * The Constructor
	 */
	public function __construct( $id = null, $config = array() ) {

		$post_title = cl()->manager->get_post_title();

		$defaults = array(

			// Basic
			'theme' => 'flat',
			'layout' => 'horizontal',
			'overlap_mode' => 'full',
			'relock' => false,
			'post_lock' => false,
			'bl_way'       => 'skip-lock',

			// Facebook Like
			'facebook_like_active' => false,
			'facebook_like_url' => '',
			'facebook_like_button_title' => esc_html__( 'like us', 'content-locker' ),

			// Facebook Share
			'facebook_share_active' => false,
			'facebook_share_url' => '',
			'facebook_share_button_title' => esc_html__( 'share', 'content-locker' ),
			'facebook_share_message_name'        => $post_title,
			'facebook_share_message_caption'     => '',
			'facebook_share_message_description' => '',
			'facebook_share_message_image' => false,

			// Tweet
			'tweet_active' => false,
			'tweet_url' => '',
			'tweet_text'         => $post_title,
			'tweet_auth'         => false,
			'tweet_via' => false,
			'tweet_button_title' => esc_html__( 'tweet', 'content-locker' ),

			// Twitter Follow
			'twitter_follow_active' => false,
			'twitter_follow_url' => '',
			'twitter_follow_hide_name' => '',
			'twitter_follow_button_title' => esc_html__( 'follow us', 'content-locker' ),

			// Google+
			'google_plus_active' => false,
			'google_plus_url' => '',
			'google_plus_button_title' => esc_html__( '+1 us', 'content-locker' ),

			// Google Share
			'google_share_active' => false,
			'google_share_url' => '',
			'google_share_button_title' => esc_html__( 'share', 'content-locker' ),

			// Youtube Subscribe
			'youtube_subscribe_active' => false,
			'youtube_subscribe_channel_id'	 => false,
			'youtube_subscribe_button_title' => esc_html__( 'subscribe', 'content-locker' ),

			// Linkedin Share
			'linkedin_share_active' => false,
			'linkedin_share_url' => '',
			'linkedin_share_button_title' => esc_html__( 'share', 'content-locker' ),
		);

		$this->config( $defaults );
		parent::__construct( $id, $config );
	}

	/**
	 * Convert locker data to be used on front end
	 *
	 * @return array
	 */
	public function to_json() {

		// get from cache
		if ( isset( $this->json ) ) {
			return $this->json;
		}

		$json = array(

			'locker_id' => $this->id,
			'post_id' => cl()->manager->object->ID,
			'type' => $this->item_type,

			// Basic
			'theme' => $this->theme,
			'layout' => $this->layout,
			'overlap' => array(
				'mode' => $this->overlap_mode,
				'position' => $this->overlap_position,
				'intensity' => 5,
			),

			// Visibility
			'expires' => false,
			'always' => $this->always,

			// Advance
			'close' => $this->close,
			'timer' => false,
			//'ajax' => $this->ajax,
			'highlight' => $this->highlight,

			'group' => array(
				'counters' => $this->show_counters,
				'url' => esc_url( cl()->manager->get_post_url() ),
				'buttons' => $this->get_buttons(),
			),
		);

		// Overlap
		if ( 'full' === $this->overlap_mode ) {
			$json['overlap'] = false;
		}

		// Save for later use
		$this->json = $json;

		return $json;
	}

	/**
	 * Get locker buttons to be render on front-end
	 *
	 * @return array
	 */
	private function get_buttons() {

		$buttons = array();
		$post_url = esc_url( cl()->manager->get_post_url() );

		if ( $this->facebook_like_active ) {

			$buttons['facebook-like'] = array(
			    'url' => $this->get_dynamic_url( $this->facebook_like_url, $post_url ),
			    'button_title' => $this->facebook_like_button_title,
				'icon' => 'fa fa-facebook',
			);
		}

		if ( $this->facebook_share_active ) {

			if ( ! $this->facebook_share_message_image && ! cl_has_seo_plugins() ) {

				if ( has_post_thumbnail() ) {
					$this->facebook_share_message_image = wp_get_attachment_image_url( get_post_thumbnail_id(), 'full' );
				}
				else {
					$this->facebook_share_message_image = cl()->settings->get( 'social_default_image' );
				}
			}
			if ( ! $this->facebook_share_message_description && ! cl_has_seo_plugins() ) {
				$this->facebook_share_message_description = strip_tags(  get_the_excerpt() );
			}
			$buttons['facebook-share'] = array(
			    'url' => $this->get_dynamic_url( $this->facebook_share_url, $post_url ),
				'dialog' => $this->facebook_share_dialog,
			    'button_title' => $this->facebook_share_button_title,
			    'name' => $this->facebook_share_message_name,
				'caption' => $this->facebook_share_message_caption,
				'description' => $this->facebook_share_message_description,
				'image' => $this->facebook_share_message_image,
				'icon' => 'fa fa-facebook',
			);
		}

		if ( $this->tweet_active ) {

			$buttons['twitter-tweet'] = array(
		 	    'url' => $this->get_dynamic_url( $this->tweet_url , $post_url ),
		 	    'text' => $this->tweet_text,
				'auth' => $this->tweet_auth,
		 	    'via' => $this->tweet_via,
				'button_title' => $this->tweet_button_title,
				'icon' => 'fa fa-twitter',
			);
		}

		if ( $this->twitter_follow_active ) {

			$buttons['twitter-follow'] = array(
				'username' => $this->twitter_follow_url,
				'hide_name' => $this->twitter_follow_hide_name,
				'button_title' => $this->twitter_follow_button_title,
				'icon' => 'fa fa-twitter',
			);
		}

		if ( $this->google_plus_active ) {

			$buttons['google-plus'] = array(
				'url' => $this->get_dynamic_url( $this->google_plus_url, $post_url ),
				'button_title' => $this->google_plus_button_title,
				'icon' => 'fa fa-google-plus',
			);
		}

		if ( $this->google_share_active ) {

			$buttons['google-share'] = array(
				'url' => $this->get_dynamic_url( $this->google_share_url, $post_url ),
				'button_title' => $this->google_share_button_title,
				'icon' => 'fa fa-google-plus',
			);
		}

		if ( $this->youtube_subscribe_active ) {

			$buttons['youtube-subscribe'] = array(
				'channel_id' => $this->youtube_subscribe_channel_id,
				'button_title' => $this->youtube_subscribe_button_title,
				'icon' => 'fa fa-youtube',
			);
		}

		if ( $this->linkedin_share_active ) {

			$buttons['linkedin-share'] = array(
				'url' => $this->get_dynamic_url( $this->linkedin_share_url, $post_url ),
				'button_title' => $this->linkedin_share_button_title,
				'icon' => 'fa fa-linkedin',
			);
		}

		$button_order = array_intersect( explode( ',', $this->button_order ), array_keys( $buttons ) );
		$buttons = array_replace( array_flip( $button_order ), $buttons );

		return $buttons;
	}

	/**
	 * Get dynamic url
	 *
	 * @param  string          $url
	 * @param  string          $default
	 * @return string
	 */
	protected function get_dynamic_url( $url, $default ) {

		if ( empty( $url ) ) {
			return $default;
		}

		return esc_url( $url );
	}

	/**
	 * Get control template according to theme
	 *
	 * @return void
	 */
	public function get_controls() {
		$json = $this->to_json();
		$group = $json['group'];

		printf(
			'<div class="mts-cl-group mts-cl-social-buttons%s">',
			( $group['counters'] ? ' mts-cl-has-counters' : '')
		);

		foreach ( $group['buttons'] as $name => $button ) {

			$classes = array(
				'mts-cl-control',
				'mts-cl-' . explode( '-', $name )[0],
				'mts-cl-' . $name,
			);

			$control = array(
				'classes' => join( ' ', $classes ),
				'button_title' => $button['button_title'],
				'icon' => $button['icon'],
			);

			cl_get_template_part( 'control-wrapper', array(
				'control' => $control,
				'locker' => $this,
			) );
		}
		echo '</div>';
	}
}
