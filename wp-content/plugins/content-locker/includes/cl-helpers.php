<?php
/**
 * Helper Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

// ---------------- STRING HELPERS ---------------------------------

/**
 * Check if the string begins with the given value
 *
 * @param  string	$needle   The sub-string to search for
 * @param  string	$haystack The string to search
 *
 * @return bool
 */
function cl_str_start_with( $needle, $haystack ) {
	return substr_compare( $haystack, $needle, 0, strlen( $needle ) ) === 0;
}

/**
 * Check if the string contains the given value
 *
 * @param  string	$needle   The sub-string to search for
 * @param  string	$haystack The string to search
 *
 * @return bool
 */
function cl_str_contains( $needle, $haystack ) {
	return strpos( $haystack, $needle ) !== false;
}

/**
 * Get the locker type in this order
 * 	1. From QueryString
 * 	2. From Post Meta
 *
 * @param  mixed 	$post
 * @return string
 */
function cl_get_item_type( $post = null ) {

	if ( isset( $_GET['cl_item_type'] ) ) {
		return $_GET['cl_item_type'];
	}

	$post = get_post( $post );
	if ( $post ) {
		return get_post_meta( $post->ID, '_mts_cl_item_type', true );
	}

	return '';
}

// ---------------- CMB2 HELPERS ---------------------------------

/**
 * Render the Tabs
 *
 * @param  array      $field_args Array of field arguments.
 * @param  CMB2_Field $field      The field object
 * @return void
 */
function cl_cmb_tabs_open_tag( $field_args, $field ) {

	$cmb = $field->get_cmb();

	echo '<div class="' . $field->row_classes() . '">';

	echo '<ul class="cl-cmb-tabs-menu" data-id="' . $field->args( 'order_id' ) . '" data-sortable="' . ( $field->args( 'sortable' ) ? 'true' : 'false'  ) . '">';

	$first = ' class="active"';
	$drag = $field->args( 'sortable' ) ? '<i class="fa fa-th-large ui-drag"></i>' : '';
	foreach ( $cmb->meta_box['fields'] as $field_name => $field ) {
		if ( 'section' === $field['type'] ) {
			printf( '<li id="%1$s">%3$s<a href="#section-%1$s"%4$s>%2$s</a></li>', $field['id'], $field['name'], $drag, $first );
			$first = '';
		}
	}

	echo '</ul>';
}

/**
 * Render end of the Tabs
 *
 * @param  array      $field_args Array of field arguments.
 * @param  CMB2_Field $field      The field object
 * @return void
 */
function cl_cmb_tabs_close_tag( $field_args, $field ) {
	echo '</div>';
}

/**
 * Render the Tab
 *
 * @param  array      $field_args Array of field arguments.
 * @param  CMB2_Field $field      The field object
 * @return void
 */
function cl_cmb_tab_open_tag( $field_args, $field ) {
	$id = $field->args( 'id' );
	echo '<div class="' . $field->row_classes() . '" id="section-' . $id . '">';
}

/**
 * Render end of the Tab
 *
 * @param  array      $field_args Array of field arguments.
 * @param  CMB2_Field $field      The field object
 * @return void
 */
function cl_cmb_tab_close_tag( $field_args, $field ) {
	echo '</div>';
}

/**
 * Render the alert
 * 	Alert Types
 * 	1. From file
 * 	2. With Title
 * 	3. Without Title
 *
 * @param  array      $field_args Array of field arguments.
 * @param  CMB2_Field $field      The field object
 * @return void
 */
function cl_cmb_alert( $field_args, $field ) {

	if ( 'view' === $field->args( 'type' ) ) {
		include cl()->plugin_dir() . '/admin/views/' . $field->args( 'file' ) . '.php';
		return;
	}

	if ( 'title' === $field->args( 'type' ) ) {
		echo '<div class="' . $field->row_classes() . '">';

		if ( $name = $field->args( 'name' ) ) {
				printf( '<h3>%s</h3>', $name );
		}

			echo wpautop( wp_kses_post( $field->args( 'desc' ) ) );

		echo '</div>';
		return;
	}

	echo '<div class="alert alert-' . $field->args( 'type' ) . ' ' . $field->row_classes() . '">';

	if ( $name = $field->args( 'name' ) ) {
			printf( '<p class="alert-title">%s</p>', $name );
	}

		echo wp_kses_post( $field->args( 'desc' ) );

	echo '</div>';
}

/**
 * Render the More Option Divider
 *
 * @param  array      $field_args Array of field arguments.
 * @param  CMB2_Field $field      The field object
 * @return void
 */
function cl_cmb_more_open_tag( $field_args, $field ) {

	echo '<div class="' . $field->row_classes() . '">';

		printf( '<a href="#" class="more-link-show">%s</a>', $field->args( 'name' ) );
		echo '<a href="#" class="more-link-hide" style="display:none"><span>' . esc_html__( 'hide extra options', 'content-locker' ) . '</span></a>';

		echo '<div class="cmb-type-more-content">';
}

/**
 * Render end of the More Option Divider
 *
 * @param  array      $field_args Array of field arguments.
 * @param  CMB2_Field $field      The field object
 * @return void
 */
function cl_cmb_more_close_tag( $field_args, $field ) {

		echo '</div>';

	echo '</div>';
}

// ---------------- DATA HELPERS ---------------------------------

/**
 * Get all the builtin and registerd post type
 *
 * @return array
 */
function cl_cmb_post_types() {
	global $wp_post_types;

	$cpts = $wp_post_types;
	unset( $cpts['nav_menu_item'], $cpts['revision'], $cpts['content-locker'], $cpts['cl-lead'], $cpts['vc_grid_item'], $cpts['customize_changeset'], $cpts['custom_css'] );
	$cpts = wp_list_pluck( $cpts, 'label' );

	return $cpts;
}

/**
 * Get Pages
 *
 * @return array
 */
function cl_cmb_get_pages() {
	$pages = get_pages( 'numer=30' );

	return wp_list_pluck( $pages, 'post_title', 'ID' );
}

/**
 * Get all lockers
 *
 * @param  string         $type   Type of locker we want to return
 * @param  string         $output Format data according to this type i.e cmb, vc, tinymce
 * @return array
 */
function cl_get_lockers( $type, $output = null ) {

	$args = array(
		'post_type'		=> 'content-locker',
		'numberposts' => -1,
	);

	if ( ! empty( $type ) ) {
		$args['meta_key'] = '_mts_cl_item_type';
		$args['meta_value'] = $type;
	}

	$lockers = get_posts( $args );

	foreach ( $lockers as $locker ) {
		$locker->post_title = empty( $locker->post_title )  ? sprintf( __( '(no titled, ID=%s)', 'content-locker' ), $locker->ID ) : $locker->post_title;
	}

	// CMB
	if ( 'cmb' === $output ) {

		$result = array();
		foreach ( $lockers as $locker ) {
			$locker->post_title = empty( $locker->post_title )  ? sprintf( __( '(no titled, ID=%s)', 'content-locker' ), $locker->ID ) : $locker->post_title;
			if ( $type = get_post_meta( $locker->ID, '_mts_cl_item_type', true ) ) {
				$locker->post_title = sprintf( '[%s] %s', $type, $locker->post_title );
			}

			$result[ $locker->ID ] = $locker->post_title;
		}

		return $result;
	}

	// Visual Composer format
	if ( 'vc' === $output ) {
		$result = array();
		foreach ( $lockers as $locker ) {
			$result[ $locker->post_title ] = $locker->ID;
		}
		return $result;
	}

	// TinyMCE format
	if ( 'tinymce' === $output ) {
		$result = array();
		foreach ( $lockers as $locker ) {
			$result[] = array(
				'text' => $locker->post_title,
				'value' => $locker->ID,
			);
		}
		return $result;
	}

	return $lockers;
}


/**
 * Get term for options
 *
 * @param  CMB2_Field $field      The field object
 * @return array
 */
function cl_cmb_get_term_options( $field ) {
	$args = $field->args( 'get_terms_args' );
	$args = is_array( $args ) ? $args : array();

	$args = wp_parse_args( $args, array( 'taxonomy' => 'category' ) );

	$taxonomy = $args['taxonomy'];
	$terms = (array) cmb2_utils()->wp_at_least( '4.5.0' ) ? get_terms( $args ) : get_terms( $taxonomy, $args );

	// Initate an empty array
	$term_options = array();
	if ( ! empty( $terms ) ) {
		foreach ( $terms as $term ) {
			$term_options[ $term->term_id ] = $term->name;
		}
	}

	return $term_options;
}


// ---------------- WORDPRESS HELPERS ---------------------------------

function cl_the_content( $content = '' ) {

	$content = wptexturize( $content );
	$content = wpautop( $content );
	$content = shortcode_unautop( $content );
	$content = prepend_attachment( $content );
	$content = wp_make_content_images_responsive( $content );
	$content = do_shortcode( $content );
	$content = convert_smilies( $content );

	return $content;
}

/**
 * Get template part in this order
 * 	1. From theme
 * 	2. From Plugin
 *
 * @see get_template_part
 */
function cl_get_template_part( $template_name, $args ) {

	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args );
	}

	$located = cl_locate_template( $template_name, $locker->theme, $locker->item_type );

	if ( ! file_exists( $located ) ) {
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '2.1' );
		return;
	}

	include( $located );
}

/**
 * Locate the template in this order
 *
 * @see locate_template
 */
function cl_locate_template( $template_name, $theme, $type = 'social' ) {

	$template_path = cl()->template_path();
	$type = str_replace( '-locker', '', $type );

	$templates = array(

		// By type
		"{$template_path}{$theme}/{$type}-{$template_name}.php",
		"{$template_path}{$theme}/{$template_name}.php",

		"{$template_path}{$type}-{$template_name}.php",
		"{$template_path}{$template_name}.php",
	);

	/**
	 * Look within passed path within the theme - this is priority.
	 *
	 * child-theme/template_path/theme_name/type-template_name
	 * child-theme/template_path/type-template_name
	 *
	 * theme/template_path/theme_name/type-template_name
	 * theme/template_path/type-template_name
	 */
	$template = locate_template( $templates );

	// Fallback to default
	if ( ! $template ) {
		$template_path = cl()->plugin_dir() . '/templates/';

		/**
		 * Look within passed path within the plugin - this is priority.
		 *
		 * plugin/templates/theme_name/template_name
		 * plugin/templates/template_name
		 */
		$templates = array(

			 // By type
			 "{$template_path}{$theme}/{$type}-{$template_name}.php",
			 "{$template_path}{$theme}/{$template_name}.php",

			 "{$template_path}{$type}-{$template_name}.php",
			 "{$template_path}{$template_name}.php",
		);

		foreach ( $templates as $name ) {

			if ( file_exists( $name ) ) {
				$template = $name;
				break;
			}
		}
	}

	// Return what we found.
	return $template;
}

/**
 * Get admin url
 *
 * @param  string	$page
 * @param  array 	$args
 * @return string
 */
function cl_get_admin_url( $page = 'help', $args = array() ) {

	$base = admin_url( 'edit.php?post_type=content-locker' );
	$args['page'] = 'cl-' . $page;

	return add_query_arg( $args, $base );
}

/**
 * Get Help Url
 *
 * @param  string 	$page
 * @return string
 */
function cl_get_help_url( $page = null ) {
	return cl_get_admin_url( 'help', array( 'cl-page' => $page ) );
}

function cl_has_seo_plugins() {

	// Yoast
	if ( defined( 'WPSEO_FILE' ) ) {
		return true;
	}

	// ALl in One SEO
	if ( defined( 'AIOSEOP_VERSION' ) ) {
		return true;
	}

	return false;
}

// ---------------- COMMON HELPERS ---------------------------------

/**
 * [normalize_data description]
 * @method normalize_data
 * @param  [type]         $value [description]
 * @return [type]                    [description]
 */
function cl_normalize_data( $value ) {

	if ( 'true' === $value || 'on' === $value ) {
		$value = true;
	} elseif ( 'false' === $value || 'off' === $value ) {
		$value = false;
	} elseif ( '0' === $value || '1' === $value ) {
		$value = intval( $value );
	}

	return $value;
}

/**
 * Generate html attribute string for array
 *
 * @param  array         $attributes
 * @return string
 */
function cl_attributes( $attributes = array(), $prefix = '' ) {

	// If empty return false
	if ( empty( $attributes ) ) {
		return false;
	}

	$out = '';
	foreach ( $attributes as $key => $value ) {

		$key = $prefix . $key;

		if ( true === $value ) {
			$value = 'true';
		}

		if ( false === $value ) {
			$value = 'false';
		}

		$out .= sprintf( ' %s="%s"', esc_html( $key ), esc_attr( $value ) );
	}

	return $out;
}

/**
 * Check for faceook app id if not generate errors
 * @return mixed
 */
function cl_cmb_facebook_errors() {

	$app_id = cl()->settings->get( 'facebook_appid' );
	if ( empty( $app_id ) || '117100935120196' === $app_id ) {
		$url = cl_get_admin_url( 'settings#facebook_appid' );
		return wp_kses_post( sprintf( __( 'To enable this service, you need to register a Facebook App for your website. Please <a href="%s" target="_blank">click here</a> to know more.', 'content-locker' ), $url ) );
	}

	return false;
}

/**
 * Check for twitter app key and secret if not generate errors
 * @return mixed
 */
function cl_cmb_twitter_errors() {

	$key = cl()->settings->get( 'twitter_consumer_key' );
	$secret = cl()->settings->get( 'twitter_consumer_secret' );

	if ( empty( $key ) || empty( $secret ) ) {
		$url = cl_get_admin_url( 'settings#twitter_consumer_key' );
		return wp_kses_post( sprintf( __( 'To enable this service, please set the Key & Secret of your Twitter App for your website. Please <a href="%s" target="_blank">click here</a> to know more.', 'content-locker' ), $url ) );
	}

	return false;
}


/**
 * Check for google client id if not generate errors
 * @return mixed
 */
function cl_cmb_google_errors() {

	$client_id = cl()->settings->get( 'google_client_id' );
	if ( empty( $client_id ) ) {
		$url = cl_get_admin_url( 'settings#google_client_id' );
		return wp_kses_post( sprintf( __( 'To enable this service, you need to get a Google Client ID for your website. Please <a href="%s" target="_blank">click here</a> to know more.', 'content-locker' ), $url ) );
	}

	return false;
}

/**
 * Check for linkedin client id and secret if not generate errors
 * @return mixed
 */
function cl_cmb_linkedin_errors() {

	$client_id = cl()->settings->get( 'linkedin_client_id' );
	$client_secret = cl()->settings->get( 'linkedin_client_secret' );

	if ( empty( $client_id ) || empty( $client_secret ) ) {
		$url = cl_get_admin_url( 'settings#linkedin_client_id' );
		return wp_kses_post( sprintf( __( 'To enable this service, you need to get LinkedIn Client ID and Secret for your website. Please <a href="%s" target="_blank">click here</a> to learn more.', 'content-locker' ), $url ) );
	}

	return false;
}

/**
 * Get bytes into human readbable text
 *
 * @param  int 		$bytes
 * @return string
 */
function cl_get_human_size( $bytes ) {

	if ( $bytes >= 1073741824 ) {
		$bytes = number_format( $bytes / 1073741824, 2 ) . ' GB';
	} elseif ( $bytes >= 1048576 ) {
		$bytes = number_format( $bytes / 1048576, 2 ) . ' MB';
	} elseif ( $bytes >= 1024 ) {
		$bytes = number_format( $bytes / 1024, 2 ) . ' KB';
	} elseif ( $bytes > 1 ) {
		$bytes = $bytes . ' bytes';
	} elseif ( 1 === $bytes ) {
		$bytes = $bytes . ' byte';
	} else {
		$bytes = '0 bytes';
	}

	return $bytes;
}

// ---------------- SUBSCRIPTION HELPERS ---------------------------------

/**
 * Get mailing lists
 *
 * @param string 	$type
 * @return array
 */
function cl_get_mailing_lists( $type = 'options' ) {

	$lists = cl()->settings->get( 'mailing' );

	if ( empty( $lists ) ) {
		return array(
			'database' => esc_html__( 'Database', 'content-locker' ),
		);
	}

	$data = array();
	if ( 'options' === $type ) {
		$data['database'] = esc_html__( 'Database', 'content-locker' );
	} elseif ( 'list' === $type ) {
		$data['default'] = esc_html__( 'Default', 'content-locker' );
	}

	foreach ( $lists as $i => $list ) {

		if ( 'none' === $list['mailing'] ) {
			continue;
		}

		$name = $list['mailing'];
		$id = isset( $list[ $name . '_list' ] ) ? $name . '_' . $list[ $name . '_list' ] : $name;
		$search = $name . '_';

		$data[ $id ] = ! empty( $list['mailing_name'] ) ? $list['mailing_name'] : esc_html__( 'No Title', 'content-locker' );
		$new[ $id ] = array(
			'service' => $name,
			'title' => ! empty( $list['mailing_name'] ) ? $list['mailing_name'] : esc_html__( 'No Title', 'content-locker' ),
		);

		foreach ( $list as $key => $val ) {

			if ( cl_str_start_with( $search, $key ) ) {
				$new[ $id ][ str_replace( $search, '', $key ) ] = $val;
			}
		}
	}

	if ( 'setting' === $type ) {
		return $new;
	}

	return $data;
}

/**
 * Get mailing list options
 *
 * @param  string	$id Selected List
 * @return array
 */
function cl_get_mailing_options( $id ) {

	$lists = cl_get_mailing_lists( 'setting' );
	return isset( $lists[ $id ] ) ? $lists[ $id ] : array();
}

/**
 * Get subscription service info
 *
 * @param  string	$id
 * @return string
 */
function cl_get_subscription_info( $id ) {

	$result = cl_get_subscription_services( 'result' );

	return isset( $result[ $id ] ) ? $result[ $id ] : null;
}

/**
 * Get subscription service class instance
 *
 * @param  string 	$id
 * @return object
 */
function cl_get_subscription_service( $id ) {

	$info = cl_get_subscription_info( $id );

	if ( is_null( $info ) ) {
		return null;
	}

	return new $info['class']( $info );
}

/**
 * Get service list stored in db as trasient
 *
 * @param  string 	$name
 * @return array
 */
function cl_get_service_list( $name = '' ) {

	if ( ! $name ) {
		return;
	}

	$list = get_transient( 'mts_cl_' . $name . '_lists' );

	return empty( $list ) ? array() : $list;
}

// GDPR Compliant - Export User Information

if(!function_exists('cl_data_exporter')) {
	function cl_data_exporter( $email_address, $page = 1 ) {

		$export_items = array();
		global $wpdb;
		$lead_data = $wpdb->get_row( "SELECT ID FROM $wpdb->posts WHERE post_excerpt = '$email_address'" );
		if(!empty($lead_data)) {
			$post_id = $lead_data->ID;
			$meta_data = apply_filters('cl_export_data_fields', array(
											'name' => __('Name', 'content-locker'),
											'family' => __('Family', 'content-locker'),
											'item_title' => __('Item Title', 'content-locker'),
											'ip' => __('IP', 'content-locker'),
											'source' => __('Source', 'content-locker'),
											'social' => __('Social', 'content-locker'),
											'twitter_url' => __('Twitter URL', 'content-locker'),
											'facebook_url' => __('Facebook URL', 'content-locker'),
											'google_url' => __('Google URL', 'content-locker'),
											'linkedin_url' => __('Linkedin URL', 'content-locker'),
											'image' => __('Lead Image', 'content-locker'),
											'list_id' => __('List ID', 'content-locker'),
											'email_confirmed' => __('Email Confirmed', 'content-locker'),
											'subscription_confirmed' => __('Subscription Confirmed', 'content-locker'),
											'gender' => __('Gender', 'content-locker'),
										));

			$data = array();
			foreach($meta_data as $key => $meta_value) {
				$value = get_post_meta($post_id, '_mts_cl_lead_'.$key, true);
				$data[] = array( 'name'	=> $meta_value, 'value'	=> get_post_meta($post_id, '_mts_cl_lead_'.$key, true) );
			}
			$export_items[] = array(
				'group_id'	=> 'cl_data',
				'group_label'	=> apply_filters('cl_export_data_group_label', __('CL Lead Data', 'content-locker')),
				'item_id'	=> 'cl_info',
				'data'	=> $data,
			);
		}

		return array(
			'data'	=> $export_items,
			'done'	=> true,
		);
	}
}

// Filter function to register data exporter
if(!function_exists('cl_register_data_exporter')) {
	function cl_register_data_exporter( $exporters ) {
		$exporters[] = array(
			'exporter_friendly_name'	=> apply_filters('cl_exporter_friendly_name', __( 'Content Locker Data', 'content-locker' )),
			'callback'	=> 'cl_data_exporter',
		);
		return $exporters;
	}
}

add_filter( 'wp_privacy_personal_data_exporters', 'cl_register_data_exporter', 10 );

// GDPR Compliant - Erase User Information
if(!function_exists('cl_data_eraser')) {

	function cl_data_eraser( $email_address, $page = 1 ) {

		global $wpdb;
		if ( empty( $email_address ) ) {
			return array(
				'items_removed'  => false,
				'items_retained' => false,
				'messages'       => array(),
				'done'           => true,
			);
		}

		$items_retained = false;
		$items_removed = false;
		$message = '';

		$export_items = array();
		$lead_data = $wpdb->get_row( "SELECT * FROM $wpdb->posts WHERE post_excerpt = '$email_address'" );

		if(!empty($lead_data)) {

			$lead_id = $lead_data->ID;

			$meta_data = apply_filters('cl_erase_data_fields', array( 'name', 'family', 'ip', 'source', 'twitter_url', 'facebook_url', 'google_url', 'linkedin_url', 'image'));

			// Update the post into the database
		  $updated = wp_update_post( array(
		  	'ID' => $lead_id,
		  	'post_title' => wp_privacy_anonymize_data('text', $lead->post_title),
		  	'post_excerpt' => wp_privacy_anonymize_data('email', $lead->post_excerpt)
		  ) );

		  if(!is_wp_error($updated)) {
		  	$items_removed = true;
		  	foreach($meta_data as $meta_key ) {
		  		delete_post_meta($lead_id, '_mts_cl_lead_'.$meta_key);
		  	}
		  	$message = __('Removed Content Locker Data', 'content-locker');
		  } else {
		  	$items_retained = true;
		  }
		}

		return array(
			'items_removed'	=> $items_removed,
			'items_retained'	=> $items_retained,
			'messages'	=> array( apply_filters('cl_data_eraser_message', $message)),
			'done'	=> true,
		);
	}
}

// Filter function to register data eraser
if(!function_exists('cl_register_data_eraser')) {
	function cl_register_data_eraser( $exporters ) {
		$exporters[] = array(
			'eraser_friendly_name'	=> apply_filters('cl_eraser_friendly_name', __( 'Content Locker', 'content-locker' )),
			'callback'	=> 'cl_data_eraser',
		);
		return $exporters;
	}
}
add_filter( 'wp_privacy_personal_data_erasers', 'cl_register_data_eraser', 10 );