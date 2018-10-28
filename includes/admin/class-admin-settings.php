<?php
/**
 * Admin settings filters, actions, variables and includes
 *
 * @author		Nir Goldberg
 * @package		includes/admin
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'anylist_admin_settings' ) ) :

class anylist_admin_settings extends anylist_admin_settings_page {

	/**
	 * initialize
	 *
	 * This function will setup the settings page data
	 *
	 * @since		1.0.0
	 * @param		N/A
	 * @return		N/A
	 */
	function initialize() {

		// settings
		$this->settings = array(

			// slugs
			'parent'				=> 'anylist-dashboard',
			'slug'					=> 'anylist-settings',

			// titles
			'page_title'			=> __( 'Any List Settings', 'anylist' ),
			'menu_title'			=> __( 'Settings', 'anylist' ),

			// tabs
			'tabs'					=> array(
				'general'			=> array(
					'title'			=> __( 'General', 'anylist' ),
					'sections'		=> array(
						'general-section1'	=> array(
							'title'			=> __( 'General Settings 1', 'anylist' ),
							'description'	=> 'Description for example'
						),
						'general-section2'	=> array(
							'title'			=> __( 'General Settings 2', 'anylist' ),
							'description'	=> ''
						)
					)
				),
				'pro'				=> array(
					'title'			=> __( 'Pro', 'anylist' ),
					'sections'		=> array(
						'pro-section'		=> array(
							'title'			=> __( 'Pro Settings', 'anylist' ),
							'description'	=> ''
						)
					)
				)
			),
			'active_tab'			=> 'general',

			// sections
			'sections'				=> array(),

			// fields
			'fields'				=> array(
				array(
					'uid'			=> 'awesome_text_field',
					'label'			=> 'Sample Text Field',
					'label_for'		=> 'awesome_text_field',
					'tab'			=> 'general',
					'section'		=> 'general-section1',
					'type'			=> 'text',
					'placeholder'	=> 'Some text',
					'helper'		=> 'Does this help?',
					'supplimental'	=> 'I am underneath!',
				),
				array(
					'uid'			=> 'awesome_password_field',
					'label'			=> 'Sample Password Field',
					'label_for'		=> 'awesome_password_field',
					'tab'			=> 'general',
					'section'		=> 'general-section1',
					'type'			=> 'password',
				),
				array(
					'uid'			=> 'awesome_number_field',
					'label'			=> 'Sample Number Field',
					'label_for'		=> 'awesome_number_field',
					'tab'			=> 'general',
					'section'		=> 'general-section2',
					'type'			=> 'number',
				),
				array(
					'uid'			=> 'awesome_textarea',
					'label'			=> 'Sample Text Area',
					'label_for'		=> 'awesome_textarea',
					'tab'			=> 'pro',
					'section'		=> 'pro-section',
					'type'			=> 'textarea',
				),
				array(
					'uid'			=> 'awesome_select',
					'label'			=> 'Sample Select Dropdown',
					'label_for'		=> 'awesome_select',
					'tab'			=> 'pro',
					'section'		=> 'pro-section',
					'type'			=> 'select',
					'options'		=> array(
						'option1'	=> 'Option 1',
						'option2'	=> 'Option 2',
						'option3'	=> 'Option 3',
						'option4'	=> 'Option 4',
						'option5'	=> 'Option 5',
					),
					'default'		=> array()
				),
				array(
					'uid'			=> 'awesome_multiselect',
					'label'			=> 'Sample Multi Select',
					'label_for'		=> 'awesome_multiselect',
					'tab'			=> 'pro',
					'section'		=> 'pro-section',
					'type'			=> 'multiselect',
					'options'		=> array(
						'option1'	=> 'Option 1',
						'option2'	=> 'Option 2',
						'option3'	=> 'Option 3',
						'option4'	=> 'Option 4',
						'option5'	=> 'Option 5',
					),
					'default'		=> array()
				),
				array(
					'uid'			=> 'awesome_radio',
					'label'			=> 'Sample Radio Buttons',
					'label_for'		=> 'awesome_radio',
					'tab'			=> 'pro',
					'section'		=> 'pro-section',
					'type'			=> 'radio',
					'options'		=> array(
						'option1'	=> 'Option 1',
						'option2'	=> 'Option 2',
						'option3'	=> 'Option 3',
						'option4'	=> 'Option 4',
						'option5'	=> 'Option 5',
					),
					'default'		=> array()
				),
				array(
					'uid'			=> 'awesome_checkboxes',
					'label'			=> 'Sample Checkboxes',
					'label_for'		=> 'awesome_checkboxes',
					'tab'			=> 'pro',
					'section'		=> 'pro-section',
					'type'			=> 'checkbox',
					'options'		=> array(
						'option1'	=> 'Option 1',
						'option2'	=> 'Option 2',
						'option3'	=> 'Option 3',
						'option4'	=> 'Option 4',
						'option5'	=> 'Option 5',
					),
					'default'		=> array()
				)
			)

		);

	}

}

// initialize
new anylist_admin_settings();

endif; // class_exists check