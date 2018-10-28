<?php
/**
 * anylist-widget-tax-filter
 *
 * Display Any List widget taxonomy filters
 *
 * Override this template by copying it to yourtheme/anylist/templates/anylist-widget-tax-filter.php
 *
 * @author		Nir Goldberg
 * @package		templates/anylist-widget
 * @version		1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// get attributes
$taxonomies = anylist_widget_front()->get_attribute( 'taxonomies' );

if ( ! $taxonomies )
	return;

foreach ($taxonomies as $tax_name => $tax_data) { ?>

	<div class="anylist-filter anylist-tax-filter anylist-tax-filter-<?php echo $tax_name; ?>" <?php echo ( ! $tax_data[1] ? 'style="display: none;"' : '' ); ?>>

		<?php // filter title ?>
		<?php if ( $tax_data[0] ) { ?>
			<div class="anylist-filter-title anylist-tax-filter-title">

				<h3><?php echo $tax_data[0]; ?></h3>

			</div>
		<?php } ?>

		<?php // filter content ?>
		<div class="anylist-filter-content">

			<?php
				/**
				 * anylist_before_taxonomy_filter/{$tax_name} hook
				 */
				do_action( "anylist_before_taxonomy_filter/{$tax_name}" );
			?>

			<?php
				/**
				 * anylist_before_taxonomy_filter hook
				 */
				do_action( "anylist_before_taxonomy_filter" );
			?>

			<ul class="tax-terms">
				<?php echo apply_filters( "anylist_widget_tax_terms/{$tax_name}", anylist_widget_front()->display_tax_terms( $tax_name, $tax_data[2] ), $tax_name, $tax_data[2] ); ?>
			</ul>

			<?php
				/**
				 * anylist_after_taxonomy_filter/{$tax_name} hook
				 */
				do_action( "anylist_after_taxonomy_filter/{$tax_name}" );
			?>

			<?php
				/**
				 * anylist_after_taxonomy_filter hook
				 */
				do_action( "anylist_after_taxonomy_filter" );
			?>

		</div>

	</div>

<?php } ?>