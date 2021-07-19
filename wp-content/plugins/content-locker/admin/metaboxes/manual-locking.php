<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

add_action( 'cmb2_init', 'cl_add_metabox_manual_locking' );
/**
 * Shortcode Metabox
 */
function cl_add_metabox_manual_locking() {

	$prefix = '_mts_cl_';

	$cmb = new_cmb2_box( array(
		'id'           => 'cl-manual-locking',
		'title'        => esc_html__( 'Manual Locking (recommended)', 'content-locker' ),
		'object_types' => array( 'content-locker' ),
		'context'      => 'side',
		'priority'     => 'high',
	));

	$cmb->add_field( array(
		'id' => $prefix . 'shortcode',
		'type' => 'title',
		'desc' => esc_html__( 'Wrap content you want to lock via the following shortcode in your post editor: ', 'content-locker' ),
		'column'     => true,
		'render_row_cb' => 'cl_display_manual_locking',
	));
}

/**
 * Render shortcode field for locker in the edit view
 *
 * @param  array      $field_args Array of field arguments.
 * @param  CMB2_Field $field      The field object
 * @return void
 */
function cl_display_manual_locking( $field_args, $field ) {

	$shortcode = str_replace( '-', '', cl_get_item_type( $field->object_id ) );

	echo '<p>' . esc_html__( 'Wrap content you want to lock via the following shortcode in your post editor:', 'content-locker' ) . '</p>';

	printf( '<input class="mts-cl-shortcode code large-text" readonly="readonly" type="text" value=\'[%1$s id="%2$d"][/%1$s]\'><button type="button" class="button copy-shortcode-field dashicons-before dashicons-clipboard"></button>', $shortcode, $field->object_id );
}
