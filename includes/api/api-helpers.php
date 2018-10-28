<?php
/**
 * Helper functions
 *
 * @author		Nir Goldberg
 * @package		includes/api
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * anylist_has_setting
 *
 * Alias of anylist()->has_setting()
 *
 * @since		1.0.0
 * @param		$name (string)
 * @return		(boolean)
 */
function anylist_has_setting( $name = '' ) {

	// return
	return anylist()->has_setting( $name );

}

/**
 * anylist_get_setting
 *
 * This function will return a value from the settings array found in the anylist object
 *
 * @since		1.0.0
 * @param		$name (string)
 * @return		(mixed)
 */
function anylist_get_setting( $name, $default = null ) {

	// vars
	$settings = anylist()->settings;

	// find setting
	$setting = anylist_maybe_get( $settings, $name, $default );

	// filter for 3rd party
	$setting = apply_filters( "anylist/settings/{$name}", $setting );

	// return
	return $setting;

}

/**
 * anylist_update_setting
 *
 * Alias of anylist()->update_setting()
 *
 * @since		1.0.0
 * @param		$name (string)
 * @param		$value (mixed)
 * @return		N/A
 */
function anylist_update_setting( $name, $value ) {

	// return
	return anylist()->update_setting( $name, $value );

}

/**
 * anylist_get_path
 *
 * This function will return the path to a file within the plugin folder
 *
 * @since		1.0.0
 * @param		$path (string) the relative path from the root of the plugin folder
 * @return		(string)
 */
function anylist_get_path( $path = '' ) {

	// return
	return ANYLIST_PATH . $path;

}

/**
 * anylist_get_url
 *
 * This function will return the url to a file within the plugin folder
 *
 * @since		1.0.0
 * @param		$path (string) the relative path from the root of the plugin folder
 * @return		(string)
 */
function anylist_get_url( $path = '' ) {

	// define ANYLIST_URL to optimize performance
	anylist()->define( 'ANYLIST_URL', anylist_get_setting( 'url' ) );

	// return
	return ANYLIST_URL . $path;

}

/**
 * anylist_include
 *
 * This function will include a file
 *
 * @since		1.0.0
 * @param		$file (string) the file name to be included
 * @return		N/A
 */
function anylist_include( $file ) {

	$path = anylist_get_path( $file );

	if ( file_exists( $path ) ) {

		include_once( $path );

	}

}

/**
 * anylist_get_view
 *
 * This function will load in a file from the 'includes/admin/views' folder and allow variables to be passed through
 *
 * @since		1.0.0
 * @param		$view_name (string)
 * @param		$args (array)
 * @return		N/A
 */
function anylist_get_view( $view_name = '', $args = array() ) {

	// vars
	$path = anylist_get_path( "includes/admin/views/{$view_name}.php" );

	if( file_exists( $path ) ) {

		include( $path );

	}

}

/**
 * anylist_maybe_get
 *
 * This function will return a variable if it exists in an array
 *
 * @since		1.0.0
 * @param		$array (array) the array to look within
 * @param		$key (key) the array key to look for
 * @param		$default (mixed) the value returned if not found
 * @return		(mixed)
 */
function anylist_maybe_get( $array = array(), $key = 0, $default = null ) {

	// return
	return isset( $array[ $key ] ) ? $array[ $key ] : $default;

}

/**
 * anylist_get_template_part
 *
 * Get template part
 *
 * @since		1.0.0
 * @param		$slug (mixed)
 * @param		$name (string)
 * @return		N/A
 */
function anylist_get_template_part( $slug, $name = '' ) {

	$template	= '';
	$debug		= anylist_get_setting( 'debug' );

	// look in yourtheme/anylist/templates/slug-name.php
	if ( $name && ! $debug ) {
		$template = locate_template( anylist()->templates_path() . "{$slug}-{$name}.php" );
	}

	// get default slug-name.php
	if ( ! $template && $name && file_exists( anylist_get_path( "templates/{$slug}-{$name}.php" ) ) ) {
		$template = anylist_get_path( "templates/{$slug}-{$name}.php" );
	}

	// if template file doesn't exist, look in yourtheme/anylist/templates/slug.php
	if ( ! $template && ! $debug ) {
		$template = locate_template( anylist()->templates_path() . "{$slug}.php" );
	}

	// get default slug.php
	if ( ! $template && file_exists( anylist_get_path( "templates/{$slug}.php" ) ) ) {
		$template = anylist_get_path( "templates/{$slug}.php" );
	}

	// allow 3rd party plugin filter template file from their plugin
	if ( ( ! $template && $debug ) || $template ) {
		$template = apply_filters( 'anylist_get_template_part', $template, $slug, $name );
	}

	if ( $template ) {
		load_template( $template, false );
	}

}

/**
 * anylist_enqueue_skin
 *
 * Enqueue skin style
 *
 * @since		1.0.0
 * @param		$slug (string)
 * @return		N/A
 */
function anylist_enqueue_skin( $slug ) {

	if ( ! $slug )
		return;

	$style			= '';
	$debug			= anylist_get_setting( 'debug' );
	$theme_path		= get_stylesheet_directory();
	$theme_uri		= get_stylesheet_directory_uri();

	// look in yourtheme/anylist/skins/slug.css
	if ( ! $debug && file_exists( $theme_path . '/' . anylist()->skins_path() . "{$slug}.css" ) ) {
		$style = $theme_uri . '/' . anylist()->skins_path() . "{$slug}.css";
	}

	// get default slug.css
	if ( ! $style && file_exists( anylist_get_path( "skins/css/{$slug}.css" ) ) ) {
		$style = anylist_get_url( "skins/css/{$slug}.css" );
	}

	// allow 3rd party plugin filter template file from their plugin
	if ( ( ! $style && $debug ) || $style ) {
		$style = apply_filters( 'anylist_enqueue_skin', $style, $slug );
	}

	if ( $style ) {
		wp_enqueue_style( $slug, $style, array(), ANYLIST_VERSION );
	}

}

/**
 * anylist_get_locale
 *
 * This function is a wrapper for the get_locale() function
 *
 * @since		1.0.0
 * @param		N/A
 * @return		(string)
 */
function anylist_get_locale() {

	// return
	return is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();

}

/**
 * anylist_parse_markdown
 *
 * A very basic regex-based Markdown parser function based off [slimdown](https://gist.github.com/jbroadway/2836900)
 *
 * @since		1.0.0
 * @param		$text (string)
 * @return		(string)
 */
function anylist_parse_markdown( $text = '' ) {

	// trim
	$text = trim($text);

	// rules
	$rules = array (
		'/=== (.+?) ===/'				=> '<h2>$1</h2>',					// headings
		'/== (.+?) ==/'					=> '<h3>$1</h3>',					// headings
		'/= (.+?) =/'					=> '<h4>$1</h4>',					// headings
		'/\[([^\[]+)\]\(([^\)]+)\)/' 	=> '<a href="$2">$1</a>',			// links
		'/(\*\*)(.*?)\1/' 				=> '<strong>$2</strong>',			// bold
		'/(\*)(.*?)\1/' 				=> '<em>$2</em>',					// intalic
		'/`(.*?)`/'						=> '<code>$1</code>',				// inline code
		'/\n\*(.*)/'					=> "\n<ul>\n\t<li>$1</li>\n</ul>",	// ul lists
		'/\n[0-9]+\.(.*)/'				=> "\n<ol>\n\t<li>$1</li>\n</ol>",	// ol lists
		'/<\/ul>\s?<ul>/'				=> '',								// fix extra ul
		'/<\/ol>\s?<ol>/'				=> '',								// fix extra ol
	);

	foreach( $rules as $k => $v ) {
		$text = preg_replace($k, $v, $text);
	}

	// autop
	$text = wpautop($text);

	// return
	return $text;

}