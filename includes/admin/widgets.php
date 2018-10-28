<?php
/**
 * Admin widgets filters, actions, variables and includes
 *
 * @author		Nir Goldberg
 * @package		admin
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'anylist_admin_widgets' ) ) :

class anylist_admin_widgets {

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
		add_action( 'admin_print_styles-widgets.php', array( $this, 'anylist_widgets_style' ) );

	}

	/**
	 * anylist_widgets_style
	 *
	 * This function will add specific style to Any List widgets in WP admin
	 *
	 * @since		1.0.0
	 * @param		N/A
	 * @return		N/A
	 */
	function anylist_widgets_style() {

		// exit if no widget_style
		if( ! anylist_get_setting( 'widget_style' ) )
			return;

		echo <<<EOF
			<style type="text/css">
				div.widget[id*=anylist_widget] .widget-title h3 {
					color: #2191bf;
				}
			</style>
EOF;

	}

}

// initialize
new anylist_admin_widgets();

endif; // class_exists check