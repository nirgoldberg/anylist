<?php
/**
 * Settings api functions
 *
 * @author		Nir Goldberg
 * @package		includes/admin
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * anylist_admin_display_form_element
 *
 * This function will display an admin form element
 *
 * @since		1.0.0
 * @param		$args (array)
 * @return		N/A
 */
function anylist_admin_display_form_element( $args ) {

	$value = get_option( $args[ 'uid' ] );

	if ( ! $value ) {
		$value = $args[ 'default' ];
	}

	switch ( $args[ 'type' ] ) {

		case 'text':
		case 'password':
		case 'number':
			printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $args[ 'uid' ], $args[ 'type' ], $args[ 'placeholder' ], $value );
			break;

		case 'textarea':
			printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $args[ 'uid' ], $args[ 'placeholder' ], $value );
			break;

		case 'select':
		case 'multiselect':
			if ( ! empty ( $args[ 'options' ] ) && is_array( $args[ 'options' ] ) ) {
				$attributes		= '';
				$options_markup	= '';

				foreach ( $args[ 'options' ] as $key => $label ) {
					$options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value[ array_search( $key, $value, true ) ], $key, false ), $label );
				}

				if ( $args[ 'type' ] === 'multiselect' ) {
					$attributes = ' multiple="multiple" ';
				}

				printf( '<select name="%1$s[]" id="%1$s" %2$s>%3$s</select>', $args[ 'uid' ], $attributes, $options_markup );
			}
			break;

		case 'radio':
		case 'checkbox':
			if ( ! empty ( $args[ 'options' ] ) && is_array( $args[ 'options' ] ) ) {
				$options_markup	= '';
				$iterator		= 0;

				foreach ( $args[ 'options' ] as $key => $label ) {
					$iterator++;
					$options_markup .= sprintf( '<label for="%1$s_%6$s"><input id="%1$s_%6$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>', $args[ 'uid' ], $args[ 'type' ], $key, checked( $value[ array_search( $key, $value, true ) ], $key, false ), $label, $iterator );
				}

				printf( '<fieldset>%s</fieldset>', $options_markup );
			}
			break;

	}

	// If there is helper text
	if ( $helper = $args[ 'helper' ] ) {
		printf( '<span class="helper"> %s</span>', $helper );
	}

	// If there is supplimental text
	if ( $supplimental = $args[ 'supplimental' ] ) {
		printf( '<p class="description">%s</p>', $supplimental );
	}

}