<?php
/**
 * anylist-widget-categories-menu
 *
 * Display Any List widget categories menu
 *
 * Override this template by copying it to yourtheme/anylist/templates/anylist-widget-categories-menu.php
 *
 * @author		Nir Goldberg
 * @package		templates/anylist-widget
 * @version		1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<div class="anylist-filter anylist-category-filter">

	<?php // filter title ?>
	<div class="anylist-filter-title anylist-category-filter-title">

		<h3><?php echo apply_filters( 'anylist_widget_categories_menu_title', __('Product Categories', 'anylist') ); ?></h3>
		<?php anylist_get_template_part('loader'); ?>

	</div>

	<?php // filter content ?>
	<div class="anylist-filter-content">

		<?php
			/**
			 * anylist_before_category_filter hook
			 */
			do_action( 'anylist_before_category_filter' );
		?>

		<ul class="categories">
			<?php echo apply_filters( 'anylist_widget_categories_menu_items', anylist_widget_front()->display_categories_menu_items() ); ?>
		</ul>

		<?php
			/**
			 * anylist_after_category_filter hook
			 */
			do_action( 'anylist_after_category_filter' );
		?>

	</div>

</div>