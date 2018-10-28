<?php
/**
 * anylist-widget-price-filter
 *
 * Display Any List widget price filter
 *
 * Override this template by copying it to yourtheme/anylist/templates/anylist-widget-price-filter.php
 *
 * @author		Nir Goldberg
 * @package		templates/anylist-widget
 * @version		1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<div class="anylist-filter anylist-price-filter">

	<?php // filter title ?>
	<?php $price_title = anylist_widget_front()->get_attribute( 'price_title' ); ?>

	<?php if ( $price_title ) { ?>

		<div class="anylist-filter-title anylist-price-filter-title">
			<h3><?php echo $price_title; ?></h3>
		</div>

	<?php } ?>

	<?php // filter content ?>
	<div class="anylist-filter-content">

		<?php
			/**
			 * anylist_before_price_filter hook
			 */
			do_action( 'anylist_before_price_filter' );
		?>

		<input type="text" id="anylist-price-filter-amount" readonly>
		<div id="anylist-price-filter-slider"></div>

		<?php
			/**
			 * anylist_after_price_filter hook
			 */
			do_action( 'anylist_after_price_filter' );
		?>

	</div>

</div>