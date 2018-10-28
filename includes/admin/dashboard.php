<?php
/**
 * Admin dashboard filters, actions, variables and includes
 *
 * @author		Nir Goldberg
 * @package		admin
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'anylist_admin_dashboard' ) ) :

class anylist_admin_dashboard {

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

		// actions
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

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
		$slug			= 'anylist-dashboard';
		$dashboard_slug	= 'anylist-dashboard';
		$capability		= anylist_get_setting( 'capability' );

		// Dashboard
		add_submenu_page( $slug, __( 'Any List Dashboard', 'anylist' ), __( 'Dashboard', 'anylist' ), $capability, $dashboard_slug, array( $this, 'html' ) );

	}

	/**
	 * html
	 *
	 * Output html content
	 *
	 * @since		1.0.0
	 * @param		N/A
	 * @return		N/A
	 */
	function html() {

		// load view
		anylist_get_view( 'dashboard' );

	}

}

// initialize
new anylist_admin_dashboard();

endif; // class_exists check