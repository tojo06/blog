<?php
/**
 * The file contains subscription settings for the plugin.
 */
$cmb->add_field( array(
	'name' => '<i class="fa fa-envelope"></i>' . esc_html__( 'Subscription Options', 'content-locker' ),
	'id' => 'settings-subscription-tab',
	'type' => 'section',
	'render_row_cb' => 'cl_cmb_tab_open_tag',
) );

$cmb->add_field( array(
	'id' => 'settings-subscription-hint',
	'type' => 'title',
	'desc' => esc_html__( 'Set up here how you would like to save emails of your subscribers.', 'content-locker' ),
	'render_row_cb' => 'cl_cmb_alert',
) );

$cmb->add_field( array(
	'name' => esc_html__( 'Default List', 'content-locker' ),
	'id' => 'default_mailing',
	'type' => 'select',
	'desc' => esc_html__( 'Select a default list to be used when none is selected.', 'content-locker' ),
	'options' => cl_get_mailing_lists(),
	'classes' => 'no-border',
));

$group_field_id = $cmb->add_field( array(
	'id' => 'mailing',
	'type' => 'group',
	'repeatable' => true,
	'classes' => 'accordion scheme-white',
	'options' => array(
		'group_title'   => __( 'List {#}', 'content-locker' ),
		'add_button'    => __( 'Add List', 'content-locker' ),
		'group_title'   => __( 'Add Newsletter Service', 'cmb2' ),
		'remove_button' => __( 'Remove List', 'content-locker' ),
		'sortable' => false,
		'closed'        => true,
	),
) );

$cmb->add_group_field( $group_field_id, array(
	'id' => 'mailing-services',
	'type' => 'select-tabs',
	'render_row_cb' => 'cl_cmb_tab_open_tag',
) );

$cmb->add_group_field( $group_field_id, array(
	'name' => esc_html__( 'List Name', 'content-locker' ),
	'id' => 'mailing_name',
	'type' => 'text',
	'desc' => esc_html__( 'For internal use only.', 'content-locker' ),
	'classes' => 'no-border repeating-group-title',
));

$services = array( 'none' => esc_html__( 'Select service', 'content-locker' ) ) + cl_get_subscription_services();
$cmb->add_group_field( $group_field_id, array(
	'name' => esc_html__( 'Mailing Service', 'content-locker' ),
	'id' => 'mailing',
	'type' => 'select',
	'desc' => esc_html__( 'Add subscribers to your list.', 'content-locker' ),
	'options' => $services,
	'classes' => 'cl-select-tabs',
));

include_once cl()->plugin_dir() . '/admin/cl-subscription-functions.php';

foreach ( $services as $func => $item ) {
	$func = "cl_subscription_option_{$func}";
	if ( function_exists( $func ) ) {
		$func( $cmb, $group_field_id );
	}
}

$cmb->add_group_field( $group_field_id, array(
	'id' => 'mailing-services-close',
	'type' => 'select-tabs',
	'render_row_cb' => 'cl_cmb_tab_close_tag',
) );

$cmb->add_field( array(
	'id' => 'settings-subscription-tab-close',
	'type' => 'section_end',
	'render_row_cb' => 'cl_cmb_tab_close_tag',
) );
