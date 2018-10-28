<?php
/**
 * Plugin Name: Any List
 * Plugin URI: http://www.htmline.com/
 * Description: Any List
 * Version: 1.0.0
 * Author: Nir Goldberg
 * Author URI: http://www.htmline.com/
 * License: GPLv2+
 * Text Domain: anylist
 * Domain Path: /lang
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'ANYLIST' ) ) :

class ANYLIST {

	/** @var string The plugin version number */
	var $version = '1.0.0';

	/** @var array The plugin settings array */
	var $settings = array();

	/**
	 * __construct
	 *
	 * A dummy constructor to ensure ANYLIST is only initialized once
	 *
	 * @since		1.0.0
	 * @param		N/A
	 * @return		N/A
	 */
	function __construct() {

		/* Do nothing here */

	}

	/**
	 * initialize
	 *
	 * The real constructor to initialize ANYLIST
	 *
	 * @since		1.0.0
	 * @param		N/A
	 * @return		N/A
	 */
	function initialize() {

		// vars
		$version	= $this->version;
		$basename	= plugin_basename( __FILE__ );
		$path		= plugin_dir_path( __FILE__ );
		$url		= plugin_dir_url( __FILE__ );
		$slug		= dirname( $basename );

		// settings
		$this->settings = array(

			// basic
			'name'			=> __( 'Any List', 'anylist' ),
			'version'		=> $version,

			// urls
			'basename'		=> $basename,
			'path'			=> $path,		// with trailing slash
			'url'			=> $url,		// with trailing slash
			'slug'			=> $slug,

			// options
			'show_admin'	=> true,
			'widget_style'	=> true,
			'capability'	=> 'manage_options',
			'active_skin'	=> 'skin01',
			'debug'			=> false

		);

		// constants
		$this->define( 'ANYLIST',			true );
		$this->define( 'ANYLIST_VERSION',	$version );
		$this->define( 'ANYLIST_PATH',		$path );

		// include helpers
		include_once( ANYLIST_PATH . 'includes/api/api-helpers.php' );

		// admin
		if ( is_admin() ) {

			anylist_include( 'includes/admin/class-admin.php' );
			anylist_include( 'includes/admin/dashboard.php' );
			anylist_include( 'includes/admin/class-admin-settings-page.php' );
			anylist_include( 'includes/admin/class-admin-settings.php' );
			anylist_include( 'includes/admin/about.php' );
			anylist_include( 'includes/admin/widgets.php' );

		}

		// functions
		anylist_include( 'includes/anylist-hooks.php' );
		anylist_include( 'includes/anylist-functions.php' );

		// widgets
		anylist_include( 'widgets/anylist/anylist-widget.php' );
		anylist_include( 'widgets/anylist/anylist-widget-front.php' );

		// actions
		add_action( 'init',	array( $this, 'init' ), 5 );
		add_action( 'init',	array( $this, 'register_assets' ), 5 );

		// plugin activation / deactivation
		register_activation_hook( __FILE__,		array( $this, 'anylist_install' ) );
		register_deactivation_hook( __FILE__,	array( $this, 'anylist_uninstall' ) );

	}

	/**
	 * init
	 *
	 * This function will run after all plugins and theme functions have been included
	 *
	 * @since		1.0.0
	 * @param		N/A
	 * @return		N/A
	 */
	function init() {

		// exit if called too early
		if ( ! did_action( 'plugins_loaded' ) )
			return;

		// exit if already init
		if ( anylist_get_setting( 'init' ) )
			return;

		// only run once
		anylist_update_setting( 'init', true );

		// update url - allow another plugin to modify dir
		anylist_update_setting( 'url', plugin_dir_url( __FILE__ ) );

		// set textdomain
		$this->load_plugin_textdomain();

		// action for 3rd party
		do_action( 'anylist/init' );

	}

	/**
	 * define
	 *
	 * This function will safely define a constant
	 *
	 * @since		1.0.0
	 * @param		$name (string)
	 * @param		$value (string)
	 * @return		N/A
	 */
	function define( $name, $value = true ) {

		if ( ! defined( $name ) ) {
			define( $name, $value );
		}

	}

	/**
	 * load_plugin_textdomain
	 *
	 * This function will load the textdomain file
	 *
	 * @since		1.0.0
	 * @param		N/A
	 * @return		N/A
	 */
	function load_plugin_textdomain() {

		// vars
		$domain = 'anylist';
		$locale = apply_filters( 'plugin_locale', anylist_get_locale(), $domain );
		$mofile = $domain . '-' . $locale . '.mo';

		// load from the languages directory first
		load_textdomain( $domain, WP_LANG_DIR . '/plugins/' . $mofile );

		// load from plugin lang folder
		load_textdomain( $domain, anylist_get_path( 'lang/' . $mofile ) );

	}

	/**
	 * has_setting
	 *
	 * This function will return true if has setting
	 *
	 * @since		1.0.0
	 * @param		$name (string)
	 * @return		(boolean)
	 */
	function has_setting( $name ) {

		// return
		return isset( $this->settings[ $name ] );

	}

	/**
	 * get_setting
	 *
	 * This function will return a setting value
	 *
	 * @since		1.0.0
	 * @param		$name (string)
	 * @return		(mixed)
	 */
	function get_setting( $name ) {

		// return
		return isset( $this->settings[ $name ] ) ? $this->settings[ $name ] : null;

	}

	/**
	 * update_setting
	 *
	 * This function will update a setting value
	 *
	 * @since		1.0.0
	 * @param		$name (string)
	 * @param		$value (mixed)
	 * @return		N/A
	 */
	function update_setting( $name, $value ) {

		$this->settings[ $name ] = $value;

		// return
		return true;

	}

	/**
	 * register_assets
	 *
	 * This function will register scripts and styles
	 *
	 * @since		1.0.0
	 * @param		N/A
	 * @return		N/A
	 */
	function register_assets() {

		// vars
		$scripts	= array();
		$styles		= array();

		// append scripts
		$scripts	= array(

			'jquery-ui'					=> array(
				'src'					=> anylist_get_url( 'assets/js/libs/jquery-ui.min.js' ),
				'deps'					=> array( 'jquery' )
			),
			'jquery-ui-touch-punch'		=> array(
				'src'					=> anylist_get_url( 'assets/js/libs/jquery.ui.touch-punch.min.js' ),
				'deps'					=> array( 'jquery-ui' )
			),
			'anylist'					=> array(
				'src'					=> anylist_get_url( 'assets/js/min/anylist.min.js' ),
				'deps'					=> array( 'jquery-ui' )
			)

		);

		// register scripts
		foreach ( $scripts as $handle => $script ) {

			wp_register_script( $handle, $script[ 'src' ], $script[ 'deps' ], ANYLIST_VERSION );

		}

		// append styles
		$styles		= array(

			'jquery-ui'					=> array(
				'src'					=> anylist_get_url( 'assets/css/jquery-ui.min.css' ),
				'deps'					=> false
			),
			'anylist-admin'				=> array(
				'src'					=> anylist_get_url( 'assets/css/anylist-admin-style.css' ),
				'deps'					=> false
			)

		);

		// register styles
		foreach( $styles as $handle => $style ) {

			wp_register_style( $handle, $style[ 'src' ], $style[ 'deps' ], ANYLIST_VERSION );

		}

	}

	/**
	 * templates_path
	 *
	 * This function will return the templates path
	 *
	 * @since		1.0.0
	 * @param		N/A
	 * @return		(string)
	 */
	public function templates_path() {

		// return
		return apply_filters( 'anylist_template_path', 'anylist/templates/' );

	}

	/**
	 * skins_path
	 *
	 * This function will return the skins path
	 *
	 * @since		1.0.0
	 * @param		N/A
	 * @return		(string)
	 */
	public function skins_path() {

		// return
		return apply_filters( 'anylist_skin_path', 'anylist/skins/' );

	}

	/**
	 * anylist_install
	 *
	 * Actions perform on activation of plugin
	 *
	 * @since		1.0.0
	 * @param		N/A
	 * @return		N/A
	 */
	function anylist_install() {}

	/**
	 * anylist_uninstall
	 *
	 * Actions perform on deactivation of plugin
	 *
	 * @since		1.0.0
	 * @param		N/A
	 * @return		N/A
	 */
	function anylist_uninstall() {}

}

/**
 * anylist
 *
 * The main function responsible for returning the one true anylist instance
 *
 * @since		1.0.0
 * @param		N/A
 * @return		(object)
 */
function anylist() {

	// globals
	global $anylist;

	// initialize
	if( ! isset( $anylist ) ) {

		$anylist = new ANYLIST();

		$anylist->initialize();

	}

	// return
	return $anylist;

}

// initialize
anylist();

endif; // class_exists check