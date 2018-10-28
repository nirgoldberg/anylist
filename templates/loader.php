<?php
/**
 * loader
 *
 * Display Any List widget loader indication
 *
 * Override this template by copying it to yourtheme/anylist/templates/loader.php
 *
 * @author		Nir Goldberg
 * @package		templates
 * @version		1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<div class="anylist-loader">

	<img src="<?php echo anylist_get_dir('assets/images/ajax-loader.gif'); ?>" width="16" height="16" />

</div>