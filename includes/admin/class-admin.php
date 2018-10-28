<?php
/**
 * Admin filters, actions, variables and includes
 *
 * @author		Nir Goldberg
 * @package		includes/admin
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'anylist_admin' ) ) :

class anylist_admin {

	/** @var array Settings array */
	var $settings = array();

	/**
	 * __construct
	 *
	 * Initialize filters, actions, variables and includes
	 *
	 * @since		1.0.0
	 * @param		N/A
	 * @return		N/A
	 */
	function __construct() {

		// settings
		$this->settings = array(

			// slugs
			'slug'			=> 'anylist-dashboard',

			// titles
			'page_title'	=> __( 'Any List', 'anylist' ),
			'menu_title'	=> __( 'Any List', 'anylist' ),

			// menu item
			'icon'			=> anylist_get_url( 'assets/images/anylist-logo.png' ),
			'position'		=> '56.01.01'

		);

		// functions
		anylist_include( 'includes/admin/settings-api.php' );

		// actions
		add_action( 'admin_enqueue_scripts',	array( $this, 'admin_enqueue_scripts') );
		add_action( 'admin_menu',				array( $this, 'admin_menu' ) );

	}

	/**
	 * admin_enqueue_scripts
	 *
	 * This function will enque admin scripts and styles
	 *
	 * @since		1.0.0
	 * @param		N/A
	 * @return		N/A
	 */
	function admin_enqueue_scripts() {

		// enqueue styles
		wp_enqueue_style( 'anylist-admin' );

	}

	/**
	 * admin_menu
	 *
	 * This function will add Any List menu item to the WP admin
	 *
	 * @since		1.0.0
	 * @param		N/A
	 * @return		N/A
	 */
	function admin_menu() {

		// exit if no show_admin
		if( ! anylist_get_setting( 'show_admin' ) )
			return;

		// vars
		$capability = anylist_get_setting( 'capability' );

		// add menu page
		add_menu_page(
			$this->settings[ 'page_title' ],
			$this->settings[ 'menu_title' ],
			$capability,
			$this->settings[ 'slug' ],
			false,
			$this->settings[ 'icon' ],
			$this->settings[ 'position' ]
		);

	}

}

// initialize
new anylist_admin();

endif; // class_exists check