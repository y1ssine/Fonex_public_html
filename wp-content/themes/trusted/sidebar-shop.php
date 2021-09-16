<?php
/**
 * The sidebar containing the main widget area
 *
 * @package Trusted
 */

if ( ! is_active_sidebar( 'trusted-shop-sidebar' ) ) {
	return;
}
?>

<div id="secondary" class="widget-area" role="complementary">
	<?php dynamic_sidebar( 'trusted-shop-sidebar' ); ?>
</div><!-- #secondary -->
