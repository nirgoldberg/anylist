<?php
/**
 * Admin about filters, actions, variables and includes
 *
 * @author		Nir Goldberg
 * @package		admin
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'anylist_admin_about' ) ) :

class anylist_admin_about {

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
		$about_slug		= 'anylist-about';
		$capability		= anylist_get_setting( 'capability' );

		// Dashboard
		add_submenu_page( $slug, __( 'About Any List', 'anylist' ), __( 'About Any List', 'anylist' ), $capability, $about_slug, array( $this, 'html' ) );

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

		// vars
		$view = array(
			'tabs'			=> array(
				'new'		=> __( "What's New", 'anylist' ),
				'changelog'	=> __( "Changelog", 'anylist' )
			),
			'active'		=> 'new'
		);

		// set active tab
		if ( ! empty( $_GET[ 'tab' ] ) && array_key_exists( $_GET[ 'tab' ], $view[ 'tabs' ] ) ) {

			$view[ 'active' ] = $_GET[ 'tab' ];

		}

		// load view
		anylist_get_view( 'about', $view );

	}

}

/**
 * display_about_options
 *
 * This function will display the admin about page content
 *
 * @since		1.0.0
 * @param		N/A
 * @return		N/A
 */
function display_about_options() {

	if ( ! isset( $_GET[ 'tab' ] ) || $_GET[ 'tab' ] == 'new' ) {

		add_settings_section( 'anylist-about-new', false, 'display_about_new_content', 'anylist-about' );

	}
	else {

		add_settings_section( 'anylist-about-changelog', false, 'display_about_changelog_content', 'anylist-about' );

	}

}

/**
 * display_about_new_content
 *
 * This function will display the admin about What's New page content
 *
 * @since		1.0.0
 * @param		N/A
 * @return		N/A
 */
function display_about_new_content() {

	echo
		'<div class="feature-section">
			<h2>' . __( 'Section 1', 'anylist' ) . '</h2>
		</div>

		<hr />

		<div class="feature-section">
			<h2>' . __( 'Section 2', 'anylist' ) . '</h2>
		</div>';

}

/**
 * display_about_changelog_content
 *
 * This function will display the admin about Changelog page content
 *
 * @since		1.0.0
 * @param		N/A
 * @return		N/A
 */
function display_about_changelog_content() {

	// vars
	$readme		= '';
	$changelog	= '';

	// extract changelog and parse markdown
	$readme = file_get_contents( anylist_get_path( 'readme.txt' ) );

	if ( ! $readme )
		return;

	echo '<p class="about-description">' . sprintf( __( "We think you'll love the changes in %s.", 'anylist'), ANYLIST_VERSION ) . '</p>';

	if ( preg_match( '/(= ' . ANYLIST_VERSION . ' =)(.+?)(=|$)/s', $readme, $match ) && $match[2] ) {

		$changelog = anylist_parse_markdown( $match[2] );

	}

	echo anylist_parse_markdown( $changelog );

}
add_action( 'admin_init', 'display_about_options' );

// initialize
new anylist_admin_about();

endif; // class_exists check