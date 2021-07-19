<?php
/**
 * Post Types
 *
 * Registers post types and taxonomies.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

/**
 * CL_Post_types Class.
 */
class CL_Post_types extends CL_Base {

	/**
	 * The Constructor
	 */
	public function __construct() {

		$this->add_action( 'init', 'register_post_types', 5 );
		$this->add_action( 'init', 'register_post_status', 9 );
	}

	/**
	 * Register Post Types
	 *
	 * @return void
	 */
	public function register_post_types() {

		if ( post_type_exists( 'content-locker' ) ) {
			return;
		}

		cl_action( 'register_post_type' );

		$labels = array(
			'name'                   => esc_html__( 'Lockers', 'content-locker' ),
			'singular_name'          => esc_html__( 'Locker', 'content-locker' ),
			'all_items'				 => esc_html__( 'All Lockers', 'content-locker' ),
			'menu_name'              => esc_html_x( 'Content Locker', 'Admin menu name', 'content-locker' ),
			'add_new'                => esc_html__( '+ New Locker', 'content-locker' ),
			'add_new_item'           => esc_html__( 'Add New Locker', 'content-locker' ),
			'edit'                   => esc_html__( 'Edit', 'content-locker' ),
			'edit_item'              => esc_html__( 'Edit Item', 'content-locker' ),
			'new_item'               => esc_html__( 'New Item', 'content-locker' ),
			'view'                   => esc_html__( 'View', 'content-locker' ),
			'view_item'              => esc_html__( 'View Item', 'content-locker' ),
			'search_items'           => esc_html__( 'Search Items', 'content-locker' ),
			'not_found'              => esc_html__( 'No Items found', 'content-locker' ),
			'not_found_in_trash'     => esc_html__( 'No Items found in trash', 'content-locker' ),
			'parent'                 => esc_html__( 'Parent Item', 'content-locker' ),
		);

		register_post_type( 'content-locker',
			cl_filter( 'register_post_type_locker',
				array(
					'label'                  => esc_html__( 'Locker', 'content-locker' ),
					'description'            => esc_html__( 'This is where you can add new lockers.', 'content-locker' ),
					'labels'              => $labels,
					'supports'            => array( 'title' ),
					'hierarchical'        => false, // Hierarchical causes memory issues - WP loads all records!
					'public'              => false,
					'show_ui'             => true,
					'show_in_menu'        => true,
					'show_in_admin_bar'     => false,
					'show_in_nav_menus'   => false,
					'can_export'            => true,
					'has_archive'         => false,
					'exclude_from_search' => true,
					'menu_icon'				=> 'dashicons-lock',
					'publicly_queryable'  => false,
					'rewrite'             => false,
					'capability_type'        => 'post',
				)
			)
		);

		$labels = array(
			'name'                  => esc_html__( 'Leads', 'content-locker' ),
			'singular_name'         => esc_html__( 'Lead', 'content-locker' ),
			'all_items'				=> esc_html__( 'All Leads', 'content-locker' ),
			'menu_name'             => esc_html_x( 'Content Lead', 'Admin menu name', 'content-locker' ),
			'add_new'               => esc_html__( '+ New Lead', 'content-locker' ),
			'add_new_item'          => esc_html__( 'Add New Locker', 'content-locker' ),
			'edit'                  => esc_html__( 'Edit', 'content-locker' ),
			'edit_item'             => esc_html__( 'Edit Item', 'content-locker' ),
			'new_item'              => esc_html__( 'New Item', 'content-locker' ),
			'view'                  => esc_html__( 'View', 'content-locker' ),
			'view_item'             => esc_html__( 'View Item', 'content-locker' ),
			'search_items'          => esc_html__( 'Search Items', 'content-locker' ),
			'not_found'             => esc_html__( 'No Items found', 'content-locker' ),
			'not_found_in_trash'    => esc_html__( 'No Items found in trash', 'content-locker' ),
			'parent'                => esc_html__( 'Parent Item', 'content-locker' ),
		);

		register_post_type( 'cl-lead',
			cl_filter( 'register_post_type_lead',
				array(
					'label'               => esc_html__( 'Lead', 'content-locker' ),
					'description'         => esc_html__( 'This is where you can add new lockers.', 'content-locker' ),
					'labels'              => $labels,
					'supports'            => array( 'title' ),
					'hierarchical'        => false, // Hierarchical causes memory issues - WP loads all records!
					'public'              => false,
					'show_ui'             => true,
					'show_in_menu'        => false,
					'show_in_admin_bar'     => false,
					'show_in_nav_menus'   => false,
					'can_export'            => true,
					'has_archive'         => false,
					'exclude_from_search' => true,
					'publicly_queryable'  => false,
					'rewrite'             => false,
					'capability_type'     => 'post',
				)
			)
		);
	}

	/**
	 * Register Post Status
	 *
	 * @return void
	 */
	public function register_post_status() {

		$lead_statuses = array(
			'confirmed' => array(
				'label'                     => esc_html_x( 'Confirmed', 'Status General Name', 'content-locker' ),
				'label_count'               => _n_noop( 'Confirmed (%s)',  'Confirmed (%s)', 'content-locker' ),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
			),

			'not-confirmed' => array(
				'label'                     => esc_html_x( 'Not Confirmed', 'Status General Name', 'content-locker' ),
				'label_count'               => _n_noop( 'Not Confirmed (%s)',  'Not Confirmed (%s)', 'content-locker' ),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
			),
		);

		$lead_statuses = cl_filter( 'register_lead_post_status', $lead_statuses );

		foreach ( $lead_statuses as $status => $values ) {
			register_post_status( $status, $values );
		}
	}
}
new CL_Post_types;
