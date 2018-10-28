<?php
/**
 * Admin about HTML content
 *
 * @author		Nir Goldberg
 * @package		admin/views
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// extract args
extract( $args );

?>

<div class="wrap about-wrap" id="anylist-admin-about">

	<div id="icon-options-general" class="icon32"></div>

	<?php
		/**
		 * Display errors
		 */
		settings_errors();
	?>

	<h1><?php _e( "Welcome to Any List", 'anylist' ); ?> <?php echo ANYLIST_VERSION; ?></h1>
	<div class="about-text"><?php printf( __( "Thank you for installing Any List %s! We hope you like it.", 'anylist' ), ANYLIST_VERSION ); ?></div>

	<h2 class="nav-tab-wrapper">
		<?php foreach ( $tabs as $tab_slug => $tab_title ) : ?>
			<a class="nav-tab<?php echo ( $active == $tab_slug ) ? ' nav-tab-active' : ''; ?>" href="<?php echo admin_url( "admin.php?page=anylist-about&tab={$tab_slug}" ); ?>"><?php echo $tab_title; ?></a>
		<?php endforeach; ?>
	</h2>

	<form method="post" action="admin.php?page=anylist-about">
		<?php
			settings_fields( 'anylist-about-section' );

			do_settings_sections( 'anylist-about' );

			submit_button(); 
		?>
	</form>

</div><!-- #anylist-admin-about -->