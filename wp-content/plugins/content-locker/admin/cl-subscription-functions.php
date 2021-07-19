<?php
/**
 * File contains all the subscription services forms
 */

/**
* Aweber Settings
*
* @param	CMB2	$cmb				The CMB2 object
* @param	string	$group_field_id		The group field id for repeater type
* @return void
*/
function cl_subscription_option_aweber( $cmb, $group_field_id ) {

	$prefix = 'aweber_';

	$cmb->add_group_field( $group_field_id, array(
		'id' => 'aweber',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_open_tag',
	));

	/*$cmb->add_group_field( $group_field_id, array(
		'id' => $prefix . 'help',
		'type' => 'view',
		'file' => 'hint-aweber-service',
		'render_row_cb' => 'cl_cmb_alert',
	)); */

	$aweber_option = get_option( '_mts_cl_aweber_access_key', array() );
	if ( empty( $aweber_option ) ) :
		$cmb->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'Authorization Code', 'content-locker' ),
			'id' => $prefix . 'api_key',
			'type' => 'textarea_small',
			'desc' => esc_html__( 'You will see after log in to your Aweber account.', 'content-locker' ),
			'classes' => 'no-border service-api-key',
			'attributes' => array(
				'data-api-id' => 'api_key',
			),
		));
	else :
		$cmb->add_group_field( $group_field_id, array(
			'id' => $prefix . 'api_key',
			'type' => 'textarea_small',
			'classes' => 'no-border service-api-key hidden',
		));
	endif;

	$list = array( 'none' => esc_html__( 'Select List', 'content-locker' ) ) + cl_get_service_list( 'aweber' );
	$cmb->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'List', 'content-locker' ),
		'id' => $prefix . 'list',
		'type' => 'select',
		'options' => $list,
		'classes' => 'no-border service-lists',
		'attributes' => array(
			'data-service' => 'aweber',
		),
	));

	$cmb->add_group_field( $group_field_id, array(
		'id' => $prefix . 'close',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_close_tag',
	) );
}

/**
* MailChimp Settings
*
* @param	CMB2	$cmb				The CMB2 object
* @param	string	$group_field_id		The group field id for repeater type
* @return void
*/
function cl_subscription_option_mailchimp( $cmb, $group_field_id ) {

	$prefix = 'mailchimp_';

	$cmb->add_group_field( $group_field_id, array(
		'id' => 'mailchimp',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_open_tag',
	) );

		$cmb->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'API Key', 'content-locker' ),
			'id' => $prefix . 'api_key',
			'type' => 'text',
			'desc' => esc_html__( 'The API key of your MailChimp account.', 'content-locker' ),
			'classes' => 'no-border service-api-key',
			'attributes' => array(
				'data-api-url' => 'http://kb.mailchimp.com/integrations/api-integrations/about-api-keys#Finding-or-generating-your-API-key',
				'data-api-id' => 'api_key',
			),
		));

		$list = array( 'none' => esc_html__( 'Select List', 'content-locker' ) ) + cl_get_service_list( 'mailchimp' );
		$cmb->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'List', 'content-locker' ),
			'id' => $prefix . 'list',
			'type' => 'select',
			'options' => $list,
			'classes' => 'no-border service-lists',
			'attributes' => array(
				'data-service' => 'mailchimp',
			),
		));

		$cmb->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'Double Opt-in', 'content-locker' ),
			'desc' => esc_html__( 'Send double opt-in notification', 'content-locker' ),
			'id' => $prefix . 'double_optin',
			'type' => 'checkbox',
			'classes' => 'no-border',
			'attributes' => array(
				'data-api-id' => 'double_optin',
			),
		));

	$cmb->add_group_field( $group_field_id, array(
		'id' => $prefix . 'close',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_close_tag',
	) );
}

/**
* MailerLite Settings
*
* @param	CMB2	$cmb				The CMB2 object
* @param	string	$group_field_id		The group field id for repeater type
* @return void
*/
function cl_subscription_option_mailerlite( $cmb, $group_field_id ) {

	$prefix = 'mailerlite_';

	$cmb->add_group_field( $group_field_id, array(
		'id' => 'mailerlite',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_open_tag',
	) );

		$cmb->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'API Key', 'content-locker' ),
			'id' => $prefix . 'api_key',
			'type' => 'text',
			'desc' => esc_html__( 'The API key of your MailerLite account.', 'content-locker' ),
			'classes' => 'no-border service-api-key',
			'attributes' => array(
				'data-api-url' => 'https://kb.mailerlite.com/does-mailerlite-offer-an-api/',
				'data-api-id' => 'api_key',
			),
		));

		$list = array( 'none' => esc_html__( 'Select List', 'content-locker' ) ) + cl_get_service_list( 'mailerlite' );
		$cmb->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'List', 'content-locker' ),
			'id' => $prefix . 'list',
			'type' => 'select',
			'options' => $list,
			'classes' => 'no-border service-lists',
			'attributes' => array(
				'data-service' => 'mailerlite',
			),
		));

		$cmb->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'Double Opt-in', 'content-locker' ),
			'desc' => esc_html__( 'Send double opt-in notification', 'content-locker' ),
			'id' => $prefix . 'double_optin',
			'type' => 'checkbox',
			'classes' => 'no-border',
			'attributes' => array(
				'data-api-id' => 'double_optin',
			),
		));

	$cmb->add_group_field( $group_field_id, array(
		'id' => $prefix . 'close',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_close_tag',
	) );
}
